import { execFileSync } from 'node:child_process';
import { readdirSync } from 'node:fs';
import path from 'node:path';

function files(directory) {
  return readdirSync(directory, { withFileTypes: true }).flatMap(entry => {
    const file = path.join(directory, entry.name);
    return entry.isDirectory() ? files(file) : [file];
  });
}

const php = [...files('dsr/modernadminpanel'), ...files('tests')].filter(file => file.endsWith('.php'));
for (const file of php) execFileSync('php', ['-l', file]);
console.log(`PHP syntax: ${php.length} files OK`);
execFileSync('node', ['--check', 'dsr/modernadminpanel/adm/style/js/studio.js']);
execFileSync('php', ['tests/helpers.php'], { stdio: 'inherit' });
execFileSync('node', ['--test', ...files('tests').filter(file => file.endsWith('.test.mjs'))], { stdio: 'inherit' });
