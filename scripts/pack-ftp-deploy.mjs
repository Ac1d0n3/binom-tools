#!/usr/bin/env node
/**
 * Build assets + pack the FTP upload set for governance.binom.net.
 * Does NOT modify public/.htaccess in the working tree (local dev stays intact).
 *
 * Usage: npm run deploy:ftp
 */
import { cpSync, existsSync, mkdirSync, rmSync, writeFileSync } from 'node:fs';
import { execSync } from 'node:child_process';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const outDir = join(root, 'deploy-ftp');

/** Paths relative to project root — merge into the existing server tree. */
const deployPaths = [
    'public/build',
    'public/images',
    'resources/views',
    'content',
    'app/Playbooks',
    'app/Support',
    'app/Catalog',
    'app/Http/Controllers/Tools',
    'app/Http/Controllers/Playbooks',
    'app/Http/Controllers/Legal',
    'app/Http/Middleware/SetLocaleFromRoute.php',
    'bootstrap/app.php',
    'config/tools.php',
    'config/legal.php',
    'routes/web.php',
];

console.log('Building assets (local .htaccess unchanged)…');
execSync('vite build && node scripts/rewrite-build-asset-urls.mjs', {
    cwd: root,
    stdio: 'inherit',
});

if (existsSync(outDir)) {
    rmSync(outDir, { recursive: true, force: true });
}
mkdirSync(outDir, { recursive: true });

for (const rel of deployPaths) {
    const src = join(root, rel);
    const dest = join(outDir, rel);

    if (!existsSync(src)) {
        console.warn(`Skip missing: ${rel}`);
        continue;
    }

    mkdirSync(dirname(dest), { recursive: true });
    cpSync(src, dest, { recursive: true });
}

mkdirSync(join(outDir, 'public'), { recursive: true });
cpSync(join(root, 'public/.htaccess.production'), join(outDir, 'public/.htaccess'));

writeFileSync(
    join(outDir, 'UPLOAD.txt'),
    `FTP-Deploy für governance.binom.net
===================================

1. Inhalt von deploy-ftp/ ins Webroot hochladen (Ordnerstruktur beibehalten).

2. Enthaltene Pfade:
   - public/build/          JS/CSS
   - public/images/         Logos, Story-Bilder
   - public/.htaccess       Production-Rewrites (RewriteBase /)
   - resources/views/       Blade-Templates
   - content/               Story-Markdown
   - app/Support/           Locale-Helper, Nav
   - app/Playbooks/         Story-Renderer
   - app/Catalog/
   - app/Http/Controllers/Tools|Playbooks|Legal/
   - app/Http/Middleware/SetLocaleFromRoute.php
   - bootstrap/app.php
   - config/tools.php
   - routes/web.php         lädt locale_route()-Helper

   Wichtig: Gesamten deploy-ftp/-Baum hochladen — nicht nur public/.

3. Optional Cache auf dem Server leeren:
   - storage/framework/views/*.php

Build: ${new Date().toISOString()}
`,
);

console.log(`\nReady: ${outDir}`);
console.log('Upload deploy-ftp/ to the server. Local public/.htaccess was not changed.');
