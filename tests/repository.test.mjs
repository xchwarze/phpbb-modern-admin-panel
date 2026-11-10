import assert from 'node:assert/strict';
import { readFileSync, readdirSync, existsSync } from 'node:fs';
import path from 'node:path';
import { test } from 'node:test';

const runtime = 'dsr/modernadminpanel';
const metadata = JSON.parse(readFileSync(`${runtime}/composer.json`));
const read = file => readFileSync(file, 'utf8');
function files(directory) {
  return readdirSync(directory, { withFileTypes: true }).flatMap(entry => {
    const file = path.join(directory, entry.name);
    return entry.isDirectory() ? files(file) : [file];
  });
}

test('phpBB feed matches the installable version and release asset', () => {
  assert.equal(metadata.name, runtime);
  assert.equal(metadata.type, 'phpbb-extension');
  assert.match(metadata.version, /^\d+\.\d+\.\d+$/);
  const feed = JSON.parse(read('last_ext_version.json'));
  const info = feed.stable[metadata.version.split('.').slice(0, 2).join('.')];
  assert.equal(info.current, metadata.version);
  assert.equal(info.announcement, `${metadata.homepage}/releases/tag/v${metadata.version}`);
  assert.equal(info.download, `${metadata.homepage}/releases/download/v${metadata.version}/dsr_modernadminpanel_${metadata.version}.zip`);
  assert.equal(typeof info.security, 'boolean');
  assert.equal(info.eol, null);
  const check = metadata.extra['version-check'];
  assert.equal(check.ssl, true);
  assert.equal(`https://${check.host}${check.directory}${check.filename}`,
    'https://raw.githubusercontent.com/xchwarze/phpbb-modern-admin-panel/master/last_ext_version.json');
});

test('CI runs EPV against the installable extension', () => {
  const workflow = read('.github/workflows/tests.yml');
  assert.match(workflow, /epv:\n\s+runs-on: ubuntu-latest/);
  assert.match(workflow, /php-version: '8\.3'/);
  assert.match(workflow, /php src\/EPV\.php run --dir="\$GITHUB_WORKSPACE\/dsr\/modernadminpanel"/);
});

test('namespaced template and bundled asset references exist', () => {
  const style = `${runtime}/adm/style`;
  let references = 0;
  for (const file of files(style).filter(file => file.endsWith('.html'))) {
    for (const [, target] of read(file).matchAll(/@dsr_modernadminpanel\/([^'"\s)]+)/g)) {
      assert.ok(existsSync(path.join(style, target)), `${file}: missing ${target}`);
      references++;
    }
  }
  assert.ok(references > 0);
  for (const file of files(style).filter(file => file.endsWith('.css'))) {
    for (const [, target] of read(file).matchAll(/url\(\s*["']?([^\s"')]+)["']?\s*\)/g)) {
      if (/^(?:data:|https?:|#)/i.test(target)) continue;
      assert.ok(existsSync(path.resolve(path.dirname(file), target.split(/[?#]/)[0])), `${file}: missing ${target}`);
    }
  }
});

test('local Markdown links resolve', () => {
  for (const file of ['README.md', ...files('docs').filter(file => file.endsWith('.md'))]) {
    for (const [, href] of read(file).matchAll(/(?<!!)\[[^\]]*\]\(([^\s)]+)\)/g)) {
      if (/^[a-z][a-z0-9+.-]*:|^#/i.test(href)) continue;
      assert.ok(existsSync(path.resolve(path.dirname(file), decodeURIComponent(href.split('#')[0]))), `${file}: ${href}`);
    }
  }
});

test('runtime licences remain available and GPL copies match', () => {
  assert.equal(read('LICENSE'), read(`${runtime}/license.txt`));
  assert.match(read(`${runtime}/adm/style/images/ICONS-LICENSE.txt`), /Apache License/);
});
