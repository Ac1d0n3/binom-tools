#!/usr/bin/env node
/**
 * Ensure Vite build output contains every tool/playbook entry from vite.config.js.
 */
import { existsSync, readFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const manifestPath = join(root, 'public/build/manifest.json');

/** Keep in sync with vite.config.js → laravel({ input: [...] }). */
const viteEntries = [
    'resources/css/app.css',
    'resources/js/app.js',
    'modules/playbooks/css/playbooks.css',
    'modules/playbooks/js/show.js',
    'modules/tools/js/dbt-governance-macro-generator/index.js',
    'modules/tools/js/pii-recommend-generator/index.js',
    'modules/tools/js/pii-unreviewed-gate-generator/index.js',
    'modules/tools/js/prompt-studio/index.js',
    'modules/tools/js/governance-ai-sanitizer/index.js',
    'modules/tools/js/pii-policy-generator/index.js',
    'modules/tools/js/schema-yml-editor/index.js',
    'modules/tools/js/meta-export-generator/index.js',
    'modules/tools/js/bi-python-toolkit/index.js',
    'modules/tools/js/dbt-dq-macro-generator/index.js',
    'modules/tools/js/dbt-dq-rules-generator/index.js',
    'modules/tools/js/dbt-dq-history-generator/index.js',
];

if (!existsSync(manifestPath)) {
    console.error('Missing build manifest. Run npm run build first.');
    process.exit(1);
}

const manifest = JSON.parse(readFileSync(manifestPath, 'utf8'));
const missing = viteEntries.filter((entry) => manifest[entry] === undefined);

if (missing.length > 0) {
    console.error('Missing built bundles for Vite entries:');
    missing.forEach((entry) => console.error(`  - ${entry}`));
    process.exit(1);
}

console.log(`Validated ${viteEntries.length} Vite bundles.`);
