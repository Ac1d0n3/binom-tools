#!/usr/bin/env node
/**
 * Build assets + pack the FTP upload set for governance.binom.net.
 * Does NOT modify public/.htaccess in the working tree (local dev stays intact).
 *
 * Incremental FTP:
 * - Unchanged file *content* keeps the previous pack mtime (so "upload newer only" works).
 * - Delta is vs last *acknowledged upload* (deploy-ftp-uploaded.json), NOT vs last pack.
 *   Pack-vs-pack alone falsely drops stories/images that were packed but never uploaded.
 * - Changed files go to deploy-ftp-delta/ (upload only that tree for small updates).
 * - CHANGED.txt lists every relative path in the upload delta.
 *
 * Usage:
 *   npm run deploy:ftp
 *   npm run deploy:ftp -- --resync-content   # force content/ + playbook images into delta
 *   npm run deploy:ftp:ack                  # after a successful FTP upload
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
/** Hashes last confirmed on the server — delta baseline (not the previous local pack). */
const uploadedManifestPath = join(root, 'deploy-ftp-uploaded.json');
const copyOpts = { recursive: true, preserveTimestamps: true };

const cliArgs = new Set(process.argv.slice(2));
const resyncContent = cliArgs.has('--resync-content');
const forceDeltaAll = cliArgs.has('--force-delta-all');
/** Skip bulky media/content unless explicitly opted in — otherwise FTP takes hours. */
const withImages = cliArgs.has('--with-images') || resyncContent || forceDeltaAll;
const withContent = cliArgs.has('--with-content') || resyncContent || forceDeltaAll;
const withStorage = cliArgs.has('--with-storage') || forceDeltaAll;
/** Code/config/views/routes + full Vite build — never playbook images/content. */
const codeOnly = cliArgs.has('--code-only');
/**
 * ALWAYS ship the full hashed Vite tree in the delta (default).
 * Partial build uploads cause "CSS not found" (manifest points at new hashes, files missing).
 * Opt out only with --no-include-build (debug).
 */
const includeBuild = !cliArgs.has('--no-include-build');

/** Always force these trees into the delta when --resync-content is set. */
const resyncContentPrefixes = [
    'content/',
    'public/images/playbooks/',
];

const codeOnlyExcludePrefixes = [
    'public/images/',
    'content/',
    'storage/',
    'app/Playbooks/stats-seed/',
    'app/SprintPlanner/bn-tools-seed/',
];

const metaSkipNames = new Set([
    'UPLOAD.txt',
    'CHANGED.txt',
    'DELETED.txt',
    'PACK-CHANGED.txt',
    '.pack-manifest.json',
    '.DS_Store',
]);

/**
 * Paths relative to project root — merge into the existing server tree.
 * Prefer whole trees over cherry-picks so new hubs/features cannot be forgotten.
 * (public/ is mirrored separately; Vite assets live under public/build/)
 */
const deployPaths = [
    'app',
    'config',
    'resources/views',
    // content/ only with --with-content / --resync-content (no MD spam in default packs)
    ...(withContent ? ['content'] : []),
    'database/migrations',
    'lang',
    'routes',
    'bootstrap/app.php',
    'bootstrap/providers.php',
    // Seeded story view/like counters (JSON files; created on first like/view if missing)
    'storage/app/playbook-stats',
];

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
    'app/Glossary/BuzzwordQuizGenerator.php',
    'app/Calendar/CalendarEventAggregator.php',
    'app/Accounts/GlossaryQuizResultStore.php',
    'app/Providers/AppServiceProvider.php',
    'bootstrap/app.php',
    'bootstrap/providers.php',
    'config/governance.php',
    'config/governance-radar.php',
    'config/storage.php',
    'config/roles.php',
    'config/glossary.php',
    'config/glossary-buzzwords-wave2.php',
    'config/glossary-buzzwords-wave7.php',
    'config/learning-paths.php',
    'config/calendar.php',
    'config/accounts.php',
    'config/tools.php',
    'config/playbooks.php',
    'config/mail.php',
    'config/session.php',
    'resources/views/roles/index.blade.php',
    'resources/views/roles/show.blade.php',
    'resources/views/roles/partials/bridge-card.blade.php',
    'resources/views/glossary/index.blade.php',
    'resources/views/glossary/bingo.blade.php',
    'resources/views/calendar/index.blade.php',
    'resources/views/governance/radar.blade.php',
    'resources/views/governance/index.blade.php',
    'resources/views/accounts/profile.blade.php',
    'routes/web.php',
    'public/build/manifest.json',
    'database/migrations/2026_07_26_000004_create_bn_governance_radar_feed_tables.php',
    'database/migrations/2026_07_25_000002_create_bn_calendar_holiday_tables.php',
    'database/migrations/2026_07_27_000001_create_bn_glossary_quiz_results_table.php',
];

/** Source trees that must be mirrored 1:1 into the pack (no silent skips). */
const requiredMirrorTrees = [
    'app',
    'config',
    'resources/views',
    'routes',
    'database/migrations',
    'lang',
];

// content/ only when opted in — default packs stay free of story markdown.
if (withContent || forceDeltaAll) {
    requiredMirrorTrees.push('content');
}

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
 * Mirror public/ so Font Awesome fonts, favicons and build hashes stay in sync.
 * Playbook images are omitted unless --with-images (keeps deploy folders upload-safe).
 * @param {string} srcDir
 * @param {string} destDir
 * @param {{ includeImages?: boolean }} [opts]
 */
function copyPublicTree(srcDir, destDir, opts = {}) {
    const includeImages = opts.includeImages === true;
    mkdirSync(destDir, { recursive: true });

    for (const name of readdirSync(srcDir)) {
        if (publicSkipNames.has(name)) {
            continue;
        }
        if (!includeImages && name === 'images') {
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

    if (!files.some((file) => file.startsWith('calendar-public-') && file.endsWith('.js'))) {
        throw new Error(
            'Calendar public JS missing from public/build/assets (expected calendar-public-*.js). Check vite entry resources/js/calendar/calendar-public.js',
        );
    }

    if (!files.some((file) => file.startsWith('glossary-quiz-') && file.endsWith('.js'))) {
        throw new Error(
            'Glossary quiz JS missing from public/build/assets (expected glossary-quiz-*.js). Check dynamic import in resources/js/app.js',
        );
    }

    if (!files.some((file) => file.startsWith('glossary-bingo-') && file.endsWith('.js'))) {
        throw new Error(
            'Glossary bingo JS missing from public/build/assets (expected glossary-bingo-*.js). Check dynamic import in resources/js/app.js',
        );
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
 * Fail if any source file under critical trees did not land in the pack.
 *
 * @param {string} packedRoot
 */
function assertMirrorTreesComplete(packedRoot) {
    /** @type {string[]} */
    const missing = [];

    for (const tree of requiredMirrorTrees) {
        const srcRoot = join(root, tree);
        if (!existsSync(srcRoot)) {
            missing.push(`${tree}/ (source tree missing)`);
            continue;
        }

        for (const full of listFilesRecursive(srcRoot)) {
            const rel = relative(root, full).split(sep).join('/');
            const base = rel.split('/').pop() || '';
            if (base === '.DS_Store' || base.endsWith('.sqlite') || base.endsWith('.sqlite-journal')) {
                continue;
            }
            if (!existsSync(join(packedRoot, rel))) {
                missing.push(rel);
            }
        }
    }

    if (missing.length > 0) {
        const shown = missing.slice(0, 40);
        const more = missing.length > shown.length ? `\n  … and ${missing.length - shown.length} more` : '';
        throw new Error(
            `FTP pack incomplete — source trees not fully mirrored:\n  - ${shown.join('\n  - ')}${more}\n`,
        );
    }
}

/**
 * @returns {Map<string, string>} rel → sha256
 */
function loadUploadedHashes() {
    /** @type {Map<string, string>} */
    const map = new Map();
    if (!existsSync(uploadedManifestPath)) {
        return map;
    }

    try {
        const raw = JSON.parse(readFileSync(uploadedManifestPath, 'utf8'));
        const files = raw?.files && typeof raw.files === 'object' ? raw.files : {};
        for (const [rel, entry] of Object.entries(files)) {
            const hash = typeof entry === 'string' ? entry : entry?.hash;
            if (typeof hash === 'string' && hash.length > 0) {
                map.set(rel, hash);
            }
        }
    } catch (err) {
        console.warn(`Could not read ${uploadedManifestPath}:`, err instanceof Error ? err.message : err);
    }

    return map;
}

/**
 * @param {string} rel
 * @param {string[]} prefixes
 */
function matchesAnyPrefix(rel, prefixes) {
    return prefixes.some((prefix) => rel === prefix.replace(/\/$/, '') || rel.startsWith(prefix));
}

/**
 * Drop bulky trees from the upload delta unless the caller opted in.
 * Full pack (deploy-ftp/) still contains everything for rare full syncs.
 *
 * @param {string[]} rels
 * @param {string[]} allPackedRels
 * @returns {string[]}
 */
function filterUploadDelta(rels, allPackedRels) {
    /** @type {Set<string>} */
    const out = new Set(rels);

    // Default: entire hashed Vite tree — prevents CSS/JS 404 after hash rename.
    if (includeBuild) {
        for (const rel of allPackedRels) {
            if (rel.startsWith('public/build/')) {
                out.add(rel);
            }
        }
    }

    // code-only: force PHP/Blade/config that the live site needs, never media.
    if (codeOnly) {
        const codePrefixes = [
            'app/',
            'config/',
            'resources/views/',
            'routes/',
            'bootstrap/',
            'database/migrations/',
            'lang/',
        ];
        for (const rel of allPackedRels) {
            if (rel === 'public/.htaccess' || matchesAnyPrefix(rel, codePrefixes)) {
                out.add(rel);
            }
        }
    }

    /** @type {string[]} */
    const effectiveExclude = [];
    if (codeOnly) {
        effectiveExclude.push(...codeOnlyExcludePrefixes);
    } else {
        if (!withImages) {
            effectiveExclude.push('public/images/');
        }
        if (!withContent) {
            effectiveExclude.push('content/');
        }
        if (!withStorage) {
            effectiveExclude.push('storage/app/bn-tools/', 'app/SprintPlanner/bn-tools-seed/');
        }
    }

    return [...out]
        .filter((rel) => !matchesAnyPrefix(rel, effectiveExclude))
        .sort();
}

/**
 * Hard-fail if bulky trees leaked into the upload delta without an opt-in flag.
 * @param {string[]} changed
 */
function assertDeltaHasNoBulkySurprises(changed) {
    const images = changed.filter((rel) => rel.startsWith('public/images/'));
    const markdown = changed.filter((rel) => rel.startsWith('content/') && rel.endsWith('.md'));

    if (!withImages && images.length > 0) {
        throw new Error(
            `FTP delta contains ${images.length} image file(s) but --with-images was not set. `
            + 'Refusing to write deploy-ftp-delta/. This is a packer bug — fix filterUploadDelta.',
        );
    }
    if (!withContent && markdown.length > 0) {
        throw new Error(
            `FTP delta contains ${markdown.length} markdown file(s) but --with-content was not set. `
            + 'Refusing to write deploy-ftp-delta/. This is a packer bug — fix filterUploadDelta.',
        );
    }

    const buildCount = changed.filter((rel) => rel.startsWith('public/build/')).length;
    console.log(
        `Delta hygiene: images=${images.length}, markdown=${markdown.length}, build=${buildCount}`
        + (includeBuild ? ' (full public/build forced)' : ''),
    );
}

/**
 * Compare new pack to previous pack by content hash.
 * Unchanged files get previous mtimes so FTP "upload newer only" can skip them.
 * Upload delta is computed separately against the last acknowledged upload.
 *
 * @param {string} nextRoot
 * @param {string} previousRoot
 * @param {Map<string, string>} uploadedByRel
 * @returns {Promise<{
 *   packChanged: string[],
 *   uploadChanged: string[],
 *   deleted: string[],
 *   unchanged: number,
 *   nextManifest: Record<string, { hash: string, mtimeMs: number }>,
 *   usedUploadBaseline: boolean,
 * }>}
 */
async function stabilizeMtimesAndCollectDelta(nextRoot, previousRoot, uploadedByRel) {
    /** @type {Map<string, { hash: string, mtimeMs: number }>} */
    const prevByRel = new Map();
    const hasPrev = existsSync(previousRoot);
    const usedUploadBaseline = uploadedByRel.size > 0;

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
    const packChanged = [];
    /** @type {string[]} */
    const uploadChanged = [];
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
        } else {
            packChanged.push(rel);
            nextManifest[rel] = { hash, mtimeMs: st.mtimeMs };
            prevByRel.delete(rel);
        }

        const uploadedHash = uploadedByRel.get(rel);
        const forceResync = resyncContent && matchesAnyPrefix(rel, resyncContentPrefixes);
        const forceAll = forceDeltaAll;
        const vsUpload = usedUploadBaseline
            ? !uploadedHash || uploadedHash !== hash
            : !prev || prev.hash !== hash;

        if (forceAll || forceResync || vsUpload) {
            uploadChanged.push(rel);
        }

        uploadedByRel.delete(rel);
    }

    const deletedRaw = usedUploadBaseline
        ? [...uploadedByRel.keys()].sort()
        : [...prevByRel.keys()].sort();
    // Intentionally omitted bulky trees are NOT server deletions.
    const deleted = deletedRaw.filter((rel) => {
        if (!withImages && rel.startsWith('public/images/')) {
            return false;
        }
        if (!withContent && (rel === 'content' || rel.startsWith('content/'))) {
            return false;
        }
        if (!withStorage && (
            rel.startsWith('storage/app/bn-tools/')
            || rel.startsWith('app/SprintPlanner/bn-tools-seed/')
        )) {
            return false;
        }
        return true;
    });
    packChanged.sort();
    uploadChanged.sort();

    return {
        packChanged,
        uploadChanged,
        deleted,
        unchanged,
        nextManifest,
        usedUploadBaseline,
    };
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
for (const dirName of ['user-templates', 'plans', 'read-state', 'calendar']) {
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
        ? `bn-tools seeds packed: ${bnToolsSeedCount} item(s) → app/SprintPlanner/bn-tools-seed/ (users/teams/acl + templates/plans/read-state/calendar)`
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

copyPublicTree(join(root, 'public'), join(outDir, 'public'), { includeImages: withImages });
cpSync(join(root, 'public/.htaccess.production'), join(outDir, 'public/.htaccess'), copyOpts);

assertFontAwesomeBuildAssets(join(outDir, 'public/build/assets'));

if (withImages) {
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
} else {
    console.log('Playbook images NOT packed (default). Opt in: --with-images / --resync-content');
}

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
assertMirrorTreesComplete(outDir);

// Direct upload mirror of local runtime (gitignored) — same paths as on the server.
if (existsSync(bnToolsRuntime)) {
    const runtimeDest = join(outDir, 'storage/app/bn-tools');
    mkdirSync(dirname(runtimeDest), { recursive: true });
    cpSync(bnToolsRuntime, runtimeDest, copyOpts);
    console.log('bn-tools runtime mirrored → deploy-ftp/storage/app/bn-tools/');
}

console.log('Comparing to previous pack (mtime) + last upload (delta)…');
const uploadedByRel = loadUploadedHashes();
if (uploadedByRel.size === 0) {
    console.warn(
        'No deploy-ftp-uploaded.json — delta falls back to pack-vs-pack '
        + '(stories/images packed earlier but never uploaded will be MISSING). '
        + 'After a good FTP upload: npm run deploy:ftp:ack\n'
        + 'To force content/ + playbook images into this delta: npm run deploy:ftp -- --resync-content',
    );
} else {
    console.log(`Upload baseline: ${uploadedByRel.size} file hash(es) from deploy-ftp-uploaded.json`);
}
if (resyncContent) {
    console.log('Force-resync: content/ + public/images/playbooks/ → delta');
}
if (forceDeltaAll) {
    console.log('Force-resync: ALL packed files → delta');
}
if (codeOnly) {
    console.log('Code-only delta: no images/content/storage seeds (use --with-images if needed)');
}
if (includeBuild) {
    console.log('Include-build: all public/build/** forced into delta (avoids CSS hash 404s)');
} else {
    console.warn('WARNING: --no-include-build set — CSS/JS hash mismatches are likely on the server.');
}
if (!withImages && !forceDeltaAll) {
    console.log('Images excluded from pack + delta by default (override: --with-images)');
}
if (!withContent && !forceDeltaAll) {
    console.log('content/*.md excluded from pack + delta by default (override: --with-content)');
}

const {
    packChanged,
    uploadChanged,
    deleted,
    unchanged,
    nextManifest,
    usedUploadBaseline,
} = await stabilizeMtimesAndCollectDelta(outDir, prevDir, uploadedByRel);

const allPackedRels = Object.keys(nextManifest);
const changed = filterUploadDelta(uploadChanged, allPackedRels);
assertDeltaHasNoBulkySurprises(changed);
const excludedByFilter = uploadChanged.filter((rel) => !changed.includes(rel));
if (excludedByFilter.length > 0) {
    console.log(
        `Delta filter dropped ${excludedByFilter.length} bulky file(s) `
        + `(images/content/storage/…). Not in deploy-ftp-delta/.`,
    );
}

writeDeltaTree(outDir, changed, deltaDir);

writeFileSync(join(outDir, 'CHANGED.txt'), changed.length > 0 ? `${changed.join('\n')}\n` : '(no content changes vs upload baseline)\n');
writeFileSync(join(outDir, 'DELETED.txt'), deleted.length > 0 ? `${deleted.join('\n')}\n` : '(no deletions vs upload baseline)\n');
writeFileSync(
    join(outDir, 'PACK-CHANGED.txt'),
    packChanged.length > 0
        ? `${packChanged.join('\n')}\n`
        : '(no content changes vs previous local pack)\n',
);
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

const baselineLabel = usedUploadBaseline
    ? 'last acknowledged upload (deploy-ftp-uploaded.json)'
    : 'previous local pack (no upload ack yet)';
const buildStamp = new Date().toISOString();
const uploadHelp = `FTP-Deploy für governance.binom.net
===================================

WICHTIG — schnell & korrekt:
   - Lade NUR deploy-ftp-delta/ hoch (NICHT den ganzen deploy-ftp/ Ordner).
   - public/build/** ist IMMER komplett im Delta (Vite-Hashes / CSS sonst 404).
   - Bilder + Story-Markdown sind standardmäßig NICHT im Pack/Delta:
       npm run deploy:ftp -- --with-images
       npm run deploy:ftp -- --with-content
       npm run deploy:ftp -- --resync-content   (= beides)
   - Nur Code + Build (empfohlen nach CSS/Layout-Fixes):
       npm run deploy:ftp:code

A) Inkrementell (immer bevorzugen)
   - Ordner: deploy-ftp-delta/
   - Enthält Diff vs. ${baselineLabel} + volles public/build (${changed.length} Datei(en))
   - FTP: diesen Baum ins Webroot mergen / überschreiben
   - Nach erfolgreichem Upload: npm run deploy:ftp:ack
     (ohne Ack kein zuverlässiges Diff)

B) Full pack — NUR Erst-Setup / Notfall
   - Ordner: deploy-ftp/
   - Ohne --with-images/--with-content ebenfalls OHNE Bilder/MDs

Gelöschte Pfade vs. Baseline: siehe DELETED.txt (${deleted.length})
Upload-Delta: siehe CHANGED.txt
Lokal vs. letzter Pack: siehe PACK-CHANGED.txt

Flags:
   --code-only         Code/Views/Config + public/build, OHNE Bilder/Content/Storage
   --no-include-build  Build NICHT erzwingen (vermeiden — CSS-404-Risiko)
   --with-images       Playbook-Bilder ins Pack/Delta
   --with-content      content/*.md ins Pack/Delta
   --with-storage      bn-tools Runtime/Seeds ins Delta
   --resync-content    content/ + Playbook-Bilder erzwingen
   --force-delta-all   ALLES (vermeiden — Stunden-Upload)

Nach Upload:
   - NUR lokal: npm run deploy:ftp:ack
   - Kein Browser-Cache leeren, keine Server-Console, kein views-löschen.
     HTML ist no-store; Vite-Hashes kommen mit dem nächsten Seitenaufruf.

Build: ${buildStamp}
`;

writeFileSync(join(outDir, 'UPLOAD.txt'), uploadHelp);
writeFileSync(join(deltaDir, 'UPLOAD.txt'), uploadHelp);

console.log(`\nReady full:  ${outDir}`);
console.log(`Ready delta: ${deltaDir} (${changed.length} upload-delta file(s), ${packChanged.length} pack-changed, ${unchanged} mtime-stable, ${deleted.length} deleted)`);
console.log(`Delta baseline: ${baselineLabel}`);
console.log('Upload ONLY deploy-ftp-delta/. Default pack has 0 images and 0 markdown.');
console.log('After FTP succeeds: npm run deploy:ftp:ack');
console.log('Verified: Governance Radar PHP + config + migrations + radar-*.js + fa-regular fonts are in the pack.');
