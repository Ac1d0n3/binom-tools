#!/usr/bin/env node
/**
 * Build assets + pack the FTP upload set for governance.binom.net.
 * Does NOT modify public/.htaccess in the working tree (local dev stays intact).
 *
 * Incremental FTP:
 * - Unchanged file *content* keeps the previous pack mtime (so "upload newer only" works).
 * - Changed files go to deploy-ftp-delta/ (upload only that tree for small updates).
 * - CHANGED.txt lists every relative path that actually changed.
 *
 * Usage: npm run deploy:ftp
 */
import {
    cpSync,
    createReadStream,
    existsSync,
    mkdirSync,
    readdirSync,
    readFileSync,
    renameSync,
    rmSync,
    statSync,
    utimesSync,
    writeFileSync,
} from 'node:fs';
import { createHash } from 'node:crypto';
import { execSync } from 'node:child_process';
import { dirname, join, relative, sep } from 'node:path';
import { fileURLToPath } from 'node:url';
import { finished } from 'node:stream/promises';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const outDir = join(root, 'deploy-ftp');
const prevDir = join(root, 'deploy-ftp.prev');
const deltaDir = join(root, 'deploy-ftp-delta');
const copyOpts = { recursive: true, preserveTimestamps: true };

const metaSkipNames = new Set([
    'UPLOAD.txt',
    'CHANGED.txt',
    'DELETED.txt',
    '.pack-manifest.json',
    '.DS_Store',
]);

/** Paths relative to project root — merge into the existing server tree. */
const deployPaths = [
    'resources/views',
    'content',
    'app/Playbooks',
    'app/Support',
    'app/Catalog',
    'app/Console/Commands',
    'app/Providers/AppServiceProvider.php',
    // Entire Controllers tree — avoid missing new hubs/tools after route updates
    'app/Http/Controllers',
    'app/Accounts',
    'app/SprintPlanner',
    'app/Calendar',
    // Governance Radar / Hub domain services (NOT covered by Controllers alone)
    'app/Governance',
    // Eloquent models for DB storage driver (sessions, radar feeds, accounts, …)
    'app/Models',
    'app/Mail',
    'app/Http/Middleware',
    'bootstrap/app.php',
    'config/tools.php',
    'config/playbooks.php',
    'config/legal.php',
    'config/app.php',
    'config/accounts.php',
    'config/compliance.php',
    'config/compliance-items.php',
    'config/vendor-resources.php',
    'config/sprint-planner.php',
    'config/calendar.php',
    'config/governance.php',
    'config/governance-radar.php',
    'config/storage.php',
    // Schema for DB-mode hubs (run migrate on server when using MySQL)
    'database/migrations',
    'lang',
    'routes/web.php',
    // Seeded story view/like counters (JSON files; created on first like/view if missing)
    'storage/app/playbook-stats',
];

/** Supplier Library configs (catalog + wave files required by suppliers-catalog.php). */
const supplierConfigFiles = readdirSync(join(root, 'config'))
    .filter((name) => name === 'suppliers.php' || name.startsWith('suppliers-'))
    .filter((name) => name.endsWith('.php'))
    .map((name) => `config/${name}`);

deployPaths.push(...supplierConfigFiles);

/**
 * Hard fail if these are missing from the packed tree — prevents “looks fine locally, broken on FTP”.
 */
const requiredPackedPaths = [
    'app/Governance/GovernanceRadarFeedSync.php',
    'app/Governance/GovernanceSessionStore.php',
    'app/Models/BnTools/BnGovernanceRadarSource.php',
    'app/Models/BnTools/BnGovernanceRadarFeedItem.php',
    'app/Models/BnTools/BnGovernanceSession.php',
    'app/Http/Controllers/Governance/GovernanceHubController.php',
    'config/governance.php',
    'config/governance-radar.php',
    'config/storage.php',
    'resources/views/governance/radar.blade.php',
    'resources/views/governance/index.blade.php',
    'routes/web.php',
    'public/build/manifest.json',
    'database/migrations/2026_07_26_000004_create_bn_governance_radar_feed_tables.php',
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
 * @param {string} dir
 * @returns {string[]}
 */
function listFilesRecursive(dir) {
    /** @type {string[]} */
    const out = [];
    if (!existsSync(dir)) {
        return out;
    }

    /**
     * @param {string} current
     */
    function walk(current) {
        for (const name of readdirSync(current)) {
            if (name === '.DS_Store') {
                continue;
            }
            const full = join(current, name);
            const st = statSync(full);
            if (st.isDirectory()) {
                walk(full);
            } else if (st.isFile()) {
                out.push(full);
            }
        }
    }

    walk(dir);
    return out;
}

/**
 * @param {string} filePath
 * @returns {Promise<string>}
 */
async function sha256File(filePath) {
    const hash = createHash('sha256');
    const stream = createReadStream(filePath);
    stream.on('data', (chunk) => hash.update(chunk));
    await finished(stream);

    return hash.digest('hex');
}

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

        cpSync(join(srcDir, name), join(destDir, name), copyOpts);
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
    for (const prefix of ['fa-solid-900', 'fa-brands-400', 'fa-regular-400']) {
        if (!files.some((file) => file.startsWith(prefix) && file.endsWith('.woff2'))) {
            throw new Error(`Font Awesome build incomplete — expected ${prefix}*.woff2 in public/build/assets`);
        }
    }

    if (!files.some((file) => file.startsWith('radar-') && file.endsWith('.js'))) {
        throw new Error('Radar JS missing from public/build/assets (expected radar-*.js). Check vite entry resources/js/governance/radar.js');
    }

    if (!files.some((file) => file.startsWith('app-') && file.endsWith('.css'))) {
        throw new Error('Main app CSS missing from public/build/assets (expected app-*.css)');
    }
}

/**
 * @param {string} packedRoot
 */
function assertRequiredPackedPaths(packedRoot) {
    const missing = requiredPackedPaths.filter((rel) => !existsSync(join(packedRoot, rel)));
    if (missing.length > 0) {
        throw new Error(
            `FTP pack incomplete — missing required paths:\n  - ${missing.join('\n  - ')}\n`
            + 'Update scripts/pack-ftp-deploy.mjs deployPaths before uploading.',
        );
    }
}

/**
 * Compare new pack to previous pack by content hash.
 * Unchanged files get previous mtimes so FTP "upload newer only" can skip them.
 *
 * @param {string} nextRoot
 * @param {string} previousRoot
 * @returns {Promise<{ changed: string[], deleted: string[], unchanged: number }>}
 */
async function stabilizeMtimesAndCollectDelta(nextRoot, previousRoot) {
    /** @type {Map<string, { hash: string, mtimeMs: number }>} */
    const prevByRel = new Map();
    const hasPrev = existsSync(previousRoot);

    if (hasPrev) {
        for (const full of listFilesRecursive(previousRoot)) {
            const rel = relative(previousRoot, full).split(sep).join('/');
            if (metaSkipNames.has(rel.split('/').pop() || '')) {
                continue;
            }
            const st = statSync(full);
            prevByRel.set(rel, {
                hash: await sha256File(full),
                mtimeMs: st.mtimeMs,
            });
        }
    }

    /** @type {string[]} */
    const changed = [];
    /** @type {Record<string, { hash: string, mtimeMs: number }>} */
    const nextManifest = {};
    let unchanged = 0;

    for (const full of listFilesRecursive(nextRoot)) {
        const rel = relative(nextRoot, full).split(sep).join('/');
        const base = rel.split('/').pop() || '';
        if (metaSkipNames.has(base)) {
            continue;
        }

        const hash = await sha256File(full);
        const prev = prevByRel.get(rel);
        const st = statSync(full);

        if (prev && prev.hash === hash) {
            utimesSync(full, new Date(prev.mtimeMs), new Date(prev.mtimeMs));
            unchanged += 1;
            nextManifest[rel] = { hash, mtimeMs: prev.mtimeMs };
            prevByRel.delete(rel);
            continue;
        }

        changed.push(rel);
        nextManifest[rel] = { hash, mtimeMs: st.mtimeMs };
        prevByRel.delete(rel);
    }

    const deleted = [...prevByRel.keys()].sort();
    changed.sort();

    return { changed, deleted, unchanged, nextManifest };
}

/**
 * @param {string} nextRoot
 * @param {string[]} changed
 * @param {string} deltaRoot
 */
function writeDeltaTree(nextRoot, changed, deltaRoot) {
    if (existsSync(deltaRoot)) {
        rmSync(deltaRoot, { recursive: true, force: true });
    }
    mkdirSync(deltaRoot, { recursive: true });

    for (const rel of changed) {
        const src = join(nextRoot, rel);
        const dest = join(deltaRoot, rel);
        if (!existsSync(src)) {
            continue;
        }
        mkdirSync(dirname(dest), { recursive: true });
        cpSync(src, dest, copyOpts);
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
    cpSync(src, dest, copyOpts);
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
    cpSync(srcDir, destDir, copyOpts);
    bnToolsSeedCount += 1;
}
console.log(
    bnToolsSeedCount > 0
        ? `bn-tools seeds packed: ${bnToolsSeedCount} item(s) → app/SprintPlanner/bn-tools-seed/ (users/teams/acl + templates/plans/read-state)`
        : 'No local bn-tools runtime found — FTP bundle will not include account/plan seeds',
);

// Keep previous pack for content-hash / mtime stabilization.
if (existsSync(prevDir)) {
    rmSync(prevDir, { recursive: true, force: true });
}
if (existsSync(outDir)) {
    renameSync(outDir, prevDir);
}

mkdirSync(outDir, { recursive: true });

copyPublicTree(join(root, 'public'), join(outDir, 'public'));
cpSync(join(root, 'public/.htaccess.production'), join(outDir, 'public/.htaccess'), copyOpts);

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
    cpSync(src, dest, copyOpts);
}

assertRequiredPackedPaths(outDir);

// Direct upload mirror of local runtime (gitignored) — same paths as on the server.
if (existsSync(bnToolsRuntime)) {
    const runtimeDest = join(outDir, 'storage/app/bn-tools');
    mkdirSync(dirname(runtimeDest), { recursive: true });
    cpSync(bnToolsRuntime, runtimeDest, copyOpts);
    console.log('bn-tools runtime mirrored → deploy-ftp/storage/app/bn-tools/');
}

console.log('Comparing to previous pack (content hash)…');
const { changed, deleted, unchanged, nextManifest } = await stabilizeMtimesAndCollectDelta(outDir, prevDir);

writeDeltaTree(outDir, changed, deltaDir);

writeFileSync(join(outDir, 'CHANGED.txt'), changed.length > 0 ? `${changed.join('\n')}\n` : '(no content changes vs previous pack)\n');
writeFileSync(join(outDir, 'DELETED.txt'), deleted.length > 0 ? `${deleted.join('\n')}\n` : '(no deletions vs previous pack)\n');
writeFileSync(
    join(outDir, '.pack-manifest.json'),
    JSON.stringify({ builtAt: new Date().toISOString(), files: nextManifest }, null, 2),
);
cpSync(join(outDir, 'CHANGED.txt'), join(deltaDir, 'CHANGED.txt'));
if (deleted.length > 0) {
    cpSync(join(outDir, 'DELETED.txt'), join(deltaDir, 'DELETED.txt'));
}

if (existsSync(prevDir)) {
    rmSync(prevDir, { recursive: true, force: true });
}

const buildStamp = new Date().toISOString();
const uploadHelp = `FTP-Deploy für governance.binom.net
===================================

Zwei Upload-Modi:

A) Inkrementell (empfohlen nach dem 1. Full-Upload)
   - Ordner: deploy-ftp-delta/
   - Enthält NUR Dateien mit geändertem Inhalt (${changed.length} Datei(en))
   - FTP: diesen Baum ins Webroot mergen / überschreiben

B) Full pack + „nur Neuere“
   - Ordner: deploy-ftp/
   - Unveränderte Inhalte behalten die mtime vom letzten Pack
     (${unchanged} unverändert, ${changed.length} geändert)
   - FTP-Client: „nur neuere Dateien hochladen“ funktioniert dann
   - Beim ersten Pack nach diesem Update wirkt noch alles neu — ab dem 2. Lauf greift’s

Gelöschte Pfade vs. letzter Pack: siehe DELETED.txt (${deleted.length})
Geänderte Pfade: siehe CHANGED.txt

Pflicht bei Full-Replace (oder wenn Delta unsicher):
   - public/build/ komplett (neue hashed Assets)
   - resources/views/, app/Governance/, app/Models/, config/governance*.php
   - database/migrations/ (bei MySQL: php artisan migrate)

Nach Upload:
   - Hard-Refresh (Cmd+Shift+R)
   - Optional: storage/framework/views/*.php löschen
   - Kontrolle: /governance/radar ohne Class-not-found

Falls vorhanden, LÖSCHEN: public/tools/

Build: ${buildStamp}
`;

writeFileSync(join(outDir, 'UPLOAD.txt'), uploadHelp);
writeFileSync(join(deltaDir, 'UPLOAD.txt'), uploadHelp);

console.log(`\nReady full:  ${outDir}`);
console.log(`Ready delta: ${deltaDir} (${changed.length} changed file(s), ${unchanged} unchanged, ${deleted.length} deleted)`);
console.log('Upload deploy-ftp-delta/ for incremental updates, or deploy-ftp/ with "newer only".');
console.log('Verified: Governance Radar PHP + config + migrations + radar-*.js + fa-regular fonts are in the pack.');
