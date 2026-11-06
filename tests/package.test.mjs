import assert from 'node:assert/strict';
import { execFileSync, spawnSync } from 'node:child_process';
import { cpSync, mkdtempSync, mkdirSync, rmSync, writeFileSync, existsSync } from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { test } from 'node:test';

const root = process.cwd();

test('release command packages a clean tag and rejects dirty trees or wrong tags', () => {
  const fixture = mkdtempSync(path.join(os.tmpdir(), 'modernadminpanel-package-'));
  try {
    const git = (...args) => execFileSync('git', args, { cwd: fixture, encoding: 'utf8', stdio: ['ignore', 'pipe', 'pipe'] });
    const run = (...args) => spawnSync('node', [path.join(root, 'tools/package.mjs'), ...args], { cwd: fixture, encoding: 'utf8' });
    git('init', '--quiet');
    git('config', 'user.name', 'Package Test');
    git('config', 'user.email', 'package-test@example.invalid');
    git('config', 'commit.gpgsign', 'false');
    cpSync(path.join(root, 'dsr'), path.join(fixture, 'dsr'), { recursive: true });
    for (const file of ['.gitattributes', '.gitignore', 'tests/archive.php']) {
      mkdirSync(path.dirname(path.join(fixture, file)), { recursive: true });
      if (existsSync(path.join(root, file))) cpSync(path.join(root, file), path.join(fixture, file));
    }
    writeFileSync(path.join(fixture, 'README.md'), 'Not part of the installable ZIP.\n');
    git('add', '.');
    git('commit', '--quiet', '-m', 'Fixture');
    git('tag', 'v2.0.0');
    let result = run('--tag', 'v2.0.0');
    assert.equal(result.status, 0, result.stdout + result.stderr);
    assert.ok(existsSync(path.join(fixture, 'dist/dsr_modernadminpanel_2.0.0.zip')));
    const archive = path.join(fixture, 'dist/dsr_modernadminpanel_2.0.0.zip');
    execFileSync('php', ['-r', '$zip = new ZipArchive(); $zip->open($argv[1]); $zip->addFromString("dsr/modernadminpanel/ext.php", "corrupt"); $zip->close();', archive]);
    let check = spawnSync('php', ['tests/archive.php', archive], { cwd: fixture, encoding: 'utf8' });
    assert.notEqual(check.status, 0);
    assert.match(check.stdout + check.stderr, /differs from Git/);
    result = run();
    assert.equal(result.status, 0, result.stdout + result.stderr);
    execFileSync('php', ['-r', '$zip = new ZipArchive(); $zip->open($argv[1]); $zip->deleteName("dsr/modernadminpanel/license.txt"); $zip->close();', archive]);
    check = spawnSync('php', ['tests/archive.php', archive], { cwd: fixture, encoding: 'utf8' });
    assert.notEqual(check.status, 0);
    assert.match(check.stdout + check.stderr, /complete dsr\/modernadminpanel/);
    result = run('--tag', 'v9.9.9');
    assert.notEqual(result.status, 0);
    assert.match(result.stderr, /Tag must match/);
    writeFileSync(path.join(fixture, 'untracked.txt'), 'Do not release this');
    result = run();
    assert.notEqual(result.status, 0);
    assert.match(result.stderr, /Working tree must be clean/);
    rmSync(path.join(fixture, 'untracked.txt'));
    writeFileSync(path.join(fixture, 'dsr/modernadminpanel/license.txt'), 'Uncommitted runtime change');
    result = run();
    assert.notEqual(result.status, 0);
    assert.match(result.stderr, /Working tree must be clean/);
    git('add', '.');
    git('commit', '--quiet', '-m', 'Advance fixture');
    result = run('--tag', 'v2.0.0');
    assert.notEqual(result.status, 0);
    assert.match(result.stderr, /Tag must point to HEAD/);
  } finally {
    rmSync(fixture, { recursive: true, force: true });
  }
});
