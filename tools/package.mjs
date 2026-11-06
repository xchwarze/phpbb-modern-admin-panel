import assert from 'node:assert/strict';
import { execFileSync } from 'node:child_process';
import { mkdirSync, readFileSync } from 'node:fs';
import path from 'node:path';

try {
  const git = (...args) => execFileSync('git', args, { encoding: 'utf8' }).trim();
  assert.equal(git('rev-parse', '--show-prefix'), '', 'Run from the repository root');
  const metadata = JSON.parse(readFileSync('dsr/modernadminpanel/composer.json'));
  assert.match(metadata.version, /^\d+\.\d+\.\d+$/, 'A stable version is required');
  const args = process.argv.slice(2);
  if (args.length) {
    assert.equal(args.length, 2, 'Usage: node tools/package.mjs [--tag vX.Y.Z]');
    assert.equal(args[0], '--tag', 'Usage: node tools/package.mjs [--tag vX.Y.Z]');
    assert.equal(args[1], `v${metadata.version}`, 'Tag must match composer.json version');
    assert.equal(git('rev-parse', `refs/tags/${args[1]}^{commit}`), git('rev-parse', 'HEAD'), 'Tag must point to HEAD');
  }
  assert.equal(git('status', '--porcelain', '--untracked-files=all'), '', 'Working tree must be clean');
  mkdirSync('dist', { recursive: true });
  const archive = path.resolve(`dist/dsr_modernadminpanel_${metadata.version}.zip`);
  execFileSync('git', ['archive', '--format=zip', `--output=${archive}`, 'HEAD']);
  execFileSync('php', ['tests/archive.php', archive, 'HEAD'], { stdio: 'inherit' });
  console.log(archive);
} catch (error) {
  console.error(error.message);
  process.exitCode = 1;
}
