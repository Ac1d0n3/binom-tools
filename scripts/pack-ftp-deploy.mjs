#!/usr/bin/env node
/**
 * Pack all PHP/view files + production build for FTP upload to governance.binom.net
 * Usage: npm run deploy:ftp-pack
 */
import { cpSync, mkdirSync, rmSync, existsSync, writeFileSync } from 'node:fs';
import { execSync } from 'node:child_process';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const outDir = join(root, 'deploy-ftp');

const paths = [
    'routes/web.php',
    'config/tools.php',
    'app/Support/ToolWorkflow.php',
    'app/Support/ToolsNav.php',
    'app/Catalog/LandingCatalog.php',
    'app/Http/Controllers/Tools/DbtGovernanceMacroGeneratorController.php',
    'app/Http/Controllers/Tools/PiiRecommendGeneratorController.php',
    'app/Http/Controllers/Tools/PiiUnreviewedGateGeneratorController.php',
    'app/Http/Controllers/Tools/ToolsLandingController.php',
    'app/Http/Controllers/Tools/ToolsOverviewController.php',
    'resources/views/tools/landing.blade.php',
    'resources/views/tools/overview.blade.php',
    'resources/views/tools/dbt-governance-macro-generator',
    'resources/views/tools/pii-policy-generator/show.blade.php',
    'resources/views/tools/pii-recommend-generator',
    'resources/views/tools/pii-unreviewed-gate-generator',
    'resources/views/components/tools/workflow-flowchart.blade.php',
    'resources/views/components/tools/workflow-nav.blade.php',
    'resources/views/components/tools/sidebar.blade.php',
    'public/.htaccess.production',
];

console.log('Building production assets…');
execSync('npm run build:production', { cwd: root, stdio: 'inherit' });

if (existsSync(outDir)) {
    rmSync(outDir, { recursive: true, force: true });
}
mkdirSync(outDir, { recursive: true });

for (const rel of paths) {
    const src = join(root, rel);
    const dest = join(outDir, rel);
    if (!existsSync(src)) {
        console.warn(`Skip missing: ${rel}`);
        continue;
    }
    mkdirSync(dirname(dest), { recursive: true });
    cpSync(src, dest, { recursive: true });
}

cpSync(join(root, 'public/build'), join(outDir, 'public/build'), { recursive: true });
cpSync(join(root, 'public/.htaccess.production'), join(outDir, 'public/.htaccess'));

writeFileSync(
    join(outDir, 'UPLOAD-ANLEITUNG.txt'),
    `FTP-Deploy für governance.binom.net
================================

1. Inhalt von deploy-ftp/ in das Webroot hochladen (Ordnerstruktur beibehalten).

2. Wichtigste Dateien (500-Fix):
   - routes/web.php
   - app/Http/Controllers/Tools/*.php
   - config/tools.php

3. Optional Cache per FTP löschen (falls vorhanden):
   - bootstrap/cache/routes-v7.php
   - bootstrap/cache/config.php
   - storage/framework/views/*.php

4. Test: https://governance.binom.net/
   https://governance.binom.net/tools/pii-unreviewed-gate-generator

Build: ${new Date().toISOString()}
`,
);

console.log(`\nReady: ${outDir}`);
console.log('Upload the deploy-ftp/ folder contents to your server webroot.');
