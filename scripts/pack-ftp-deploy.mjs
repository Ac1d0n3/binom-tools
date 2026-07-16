#!/usr/bin/env node
/**
 * Build assets + pack the FTP upload set for governance.binom.net.
 * Does NOT modify public/.htaccess in the working tree (local dev stays intact).
 *
 * Usage: npm run deploy:ftp
 */
import { cpSync, existsSync, mkdirSync, readdirSync, rmSync, writeFileSync } from 'node:fs';
import { execSync } from 'node:child_process';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const outDir = join(root, 'deploy-ftp');

/** Paths relative to project root — merge into the existing server tree. */
const deployPaths = [
    'resources/views',
    'content',
    'app/Playbooks',
    'app/Support',
    'app/Catalog',
    'app/Http/Controllers/Tools',
    'app/Http/Controllers/About',
    'app/Http/Controllers/Playbooks',
    'app/Http/Controllers/Legal',
    'app/Http/Middleware/SetLocaleFromRoute.php',
    'bootstrap/app.php',
    'config/tools.php',
    'config/legal.php',
    'routes/web.php',
];

/** Never mirror these from public/ (dev-only or replaced below). */
const publicSkipNames = new Set([
    '.htaccess',
    '.htaccess.local',
    '.htaccess.production',
    '.DS_Store',
    'tools',
]);

/**
 * Mirror public/ so Font Awesome fonts, favicons, build hashes and images stay in sync.
 * @param {string} srcDir
 * @param {string} destDir
 */
function copyPublicTree(srcDir, destDir) {
    mkdirSync(destDir, { recursive: true });

    for (const name of readdirSync(srcDir)) {
        if (publicSkipNames.has(name)) {
            continue;
        }

        cpSync(join(srcDir, name), join(destDir, name), { recursive: true });
    }
}

/**
 * @param {string} assetsDir
 */
function assertFontAwesomeBuildAssets(assetsDir) {
    if (!existsSync(assetsDir)) {
        throw new Error(`Missing build assets directory: ${assetsDir}`);
    }

    const files = readdirSync(assetsDir);
    for (const prefix of ['fa-solid-900', 'fa-brands-400']) {
        if (!files.some((file) => file.startsWith(prefix) && file.endsWith('.woff2'))) {
            throw new Error(`Font Awesome build incomplete — expected ${prefix}*.woff2 in public/build/assets`);
        }
    }
}

console.log('Building assets (local .htaccess unchanged)…');
execSync('npm run build', {
    cwd: root,
    stdio: 'inherit',
});

if (existsSync(outDir)) {
    rmSync(outDir, { recursive: true, force: true });
}
mkdirSync(outDir, { recursive: true });

copyPublicTree(join(root, 'public'), join(outDir, 'public'));
cpSync(join(root, 'public/.htaccess.production'), join(outDir, 'public/.htaccess'));

assertFontAwesomeBuildAssets(join(outDir, 'public/build/assets'));

const playbookImagesDir = join(outDir, 'public/images/playbooks');
const pngCount = existsSync(playbookImagesDir)
    ? readdirSync(playbookImagesDir).filter((name) => name.endsWith('.png')).length
    : 0;
const webpCount = existsSync(playbookImagesDir)
    ? readdirSync(playbookImagesDir).filter((name) => name.endsWith('.webp')).length
    : 0;

if (pngCount > 0 && webpCount < pngCount) {
    throw new Error(
        `WebP sync incomplete in deploy package (${webpCount}/${pngCount} playbook images). Run npm run sync:images before deploy.`,
    );
}

console.log(`Playbook images packed: ${webpCount} WebP / ${pngCount} PNG`);

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

writeFileSync(
    join(outDir, 'UPLOAD.txt'),
    `FTP-Deploy für governance.binom.net
===================================

1. Inhalt von deploy-ftp/ ins Webroot hochladen (Ordnerstruktur beibehalten).

2. Enthaltene Pfade:
   - public/                Komplett (build/, images/, favicons, prompt-studio/config, …)
     Font Awesome: public/build/assets/fa-solid-*.woff2 + fa-brands-*.woff2
   - public/.htaccess       Production-Rewrites (alle /tools/* → Laravel)
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

   Wichtig: Gesamten deploy-ftp/-Baum hochladen — besonders public/build/assets/ komplett!

3. WICHTIG — falls von früheren Deploys noch vorhanden, per FTP LÖSCHEN:
   - public/tools/   (physischer Ordner blockiert /tools/ und alle Tool-Seiten!)

4. Nach Deploy prüfen: /tools/, /tools/prompt-studio/, Icons sichtbar.

5. Optional Cache auf dem Server leeren:
   - storage/framework/views/*.php

Build: ${new Date().toISOString()}
`,
);

console.log(`\nReady: ${outDir}`);
console.log('Upload deploy-ftp/ to the server. Local public/.htaccess was not changed.');
