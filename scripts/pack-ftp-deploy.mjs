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
    'app/Console/Commands',
    'app/Providers/AppServiceProvider.php',
    'app/Http/Controllers/Tools',
    'app/Http/Controllers/About',
    'app/Http/Controllers/Playbooks',
    'app/Http/Controllers/Legal',
    'app/Http/Controllers/SprintPlanner',
    'app/Http/Controllers/Accounts',
    'app/Accounts',
    'app/SprintPlanner',
    'app/Http/Middleware',
    'bootstrap/app.php',
    'config/tools.php',
    'config/playbooks.php',
    'config/legal.php',
    'config/app.php',
    'config/accounts.php',
    'routes/web.php',
    // Seeded story view/like counters (JSON files; created on first like/view if missing)
    'storage/app/playbook-stats',
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

const statsSeedDir = join(root, 'app/Playbooks/stats-seed');
const seedJsonCount = existsSync(statsSeedDir)
    ? readdirSync(statsSeedDir).filter((name) => name.endsWith('.json')).length
    : 0;

if (seedJsonCount === 0) {
    console.log('No playbook stats seeds found — running php artisan playbooks:seed-stats --force…');
    execSync('php artisan playbooks:seed-stats --force', {
        cwd: root,
        stdio: 'inherit',
    });
} else {
    console.log(`Playbook stats seeds present: ${seedJsonCount} JSON file(s)`);
}

// Snapshot local accounts / user-templates into FTP-safe seeds (not in Git).
const bnToolsRuntime = join(root, 'storage/app/bn-tools');
const bnToolsSeedDir = join(root, 'app/SprintPlanner/bn-tools-seed');
mkdirSync(bnToolsSeedDir, { recursive: true });

/**
 * @param {string} src
 * @param {string} dest
 */
function copyFileIfExists(src, dest) {
    if (!existsSync(src)) {
        return false;
    }
    mkdirSync(dirname(dest), { recursive: true });
    cpSync(src, dest);
    return true;
}

let bnToolsSeedCount = 0;
for (const name of ['users.json', 'teams.json', 'story-acl.json']) {
    if (copyFileIfExists(join(bnToolsRuntime, name), join(bnToolsSeedDir, name))) {
        bnToolsSeedCount += 1;
    }
}
for (const dirName of ['user-templates', 'plans', 'read-state']) {
    const srcDir = join(bnToolsRuntime, dirName);
    const destDir = join(bnToolsSeedDir, dirName);
    if (!existsSync(srcDir)) {
        continue;
    }
    mkdirSync(destDir, { recursive: true });
    cpSync(srcDir, destDir, { recursive: true });
    bnToolsSeedCount += 1;
}
console.log(
    bnToolsSeedCount > 0
        ? `bn-tools seeds packed: ${bnToolsSeedCount} item(s) → app/SprintPlanner/bn-tools-seed/ (users/teams/acl + templates/plans/read-state)`
        : 'No local bn-tools runtime found — FTP bundle will not include account/plan seeds',
);

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

// Direct upload mirror of local runtime (gitignored) — same paths as on the server.
if (existsSync(bnToolsRuntime)) {
    const runtimeDest = join(outDir, 'storage/app/bn-tools');
    mkdirSync(dirname(runtimeDest), { recursive: true });
    cpSync(bnToolsRuntime, runtimeDest, { recursive: true });
    console.log('bn-tools runtime mirrored → deploy-ftp/storage/app/bn-tools/');
}

writeFileSync(
    join(outDir, 'UPLOAD.txt'),
    `FTP-Deploy für governance.binom.net
===================================

Ziel: Server = aktueller lokaler Stand. deploy-ftp/ IST dieser Snapshot.

1. Gesamten Inhalt von deploy-ftp/ ins Webroot hochladen und BESTEHENDE
   Dateien ÜBERSCHREIBEN (nicht „nur fehlende Dateien“).

2. Pflicht — ohne kompletten Replace bleibt der Server alt:
   - public/build/              kompletter Ordner ersetzen (neue show-*.js Hashes)
   - public/build/manifest.json
   - content/sprint-plans/      alle *.md (Sprint- + Task-dependsOn, Help-Links)
   - resources/views/
   - app/SprintPlanner/ + app/Accounts/

3. Weitere Pfade:
   - public/                (images, favicons, …) + public/.htaccess (Production)
   - content/               Stories + Sprint-Pläne
   - app/… bootstrap/app.php config/ routes/web.php
   - storage/app/playbook-stats/
   - app/Playbooks/stats-seed/
   - app/SprintPlanner/bn-tools-seed/
   - storage/app/bn-tools/  Runtime (Login + Pläne)

   Wichtig: Gesamten deploy-ftp/-Baum hochladen.
   Ohne EnsureToolEnabled.php + aktualisiertes bootstrap/app.php → 500 auf der Startseite.

4. Nach Upload:
   - Hard-Refresh (Cmd+Shift+R)
   - Optional: storage/framework/views/*.php löschen
   - Kontrolle: Seite lädt NEUES show-*.js (nicht show-BDJxt-FY.js)
   - Kontrolle: Fabric-Template taskDeps=36 (nicht 0)

5. Falls vorhanden, LÖSCHEN:
   - public/tools/   (blockiert /tools/*)

6. Accounts/.env:
   BINOM_TOOLS_ACCOUNTS_ENABLED=true
   SESSION_DRIVER=file
   Bei Login-Problemen users.json aus deploy-ftp/storage/app/bn-tools/ überschreiben.

Build: ${new Date().toISOString()}
`,
);

console.log(`\nReady: ${outDir}`);
console.log('Upload deploy-ftp/ to the server. Local public/.htaccess was not changed.');
