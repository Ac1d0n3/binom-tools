#!/usr/bin/env node
/**
 * Convert playbook PNGs to WebP alongside the source files.
 * Incremental: only processes PNGs that are new or newer than their WebP sibling.
 *
 * Usage:
 *   npm run sync:images
 *   npm run sync:images:force
 *   node scripts/sync-playbook-images.mjs [--dry-run] [--force] [--verbose]
 */
import { readdirSync, statSync } from 'node:fs';
import { dirname, extname, join, relative } from 'node:path';
import { fileURLToPath } from 'node:url';
import sharp from 'sharp';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const args = new Set(process.argv.slice(2));
const dryRun = args.has('--dry-run');
const force = args.has('--force');
const verbose = args.has('--verbose');

const WEBP_QUALITY = 82;
const WEBP_EFFORT = 4;

/** @type {string[]} */
const scanRoots = [
    join(root, 'public/images/playbooks'),
    join(root, 'public/apple-touch-icon.png'),
];

/**
 * @param {string} dir
 * @returns {string[]}
 */
function collectPngFiles(dir) {
    if (!dir.endsWith('.png')) {
        if (!statSync(dir, { throwIfNoEntry: false })?.isDirectory()) {
            return [];
        }

        /** @type {string[]} */
        const files = [];

        for (const entry of readdirSync(dir, { withFileTypes: true })) {
            const fullPath = join(dir, entry.name);

            if (entry.isDirectory()) {
                files.push(...collectPngFiles(fullPath));
                continue;
            }

            if (entry.isFile() && extname(entry.name).toLowerCase() === '.png') {
                files.push(fullPath);
            }
        }

        return files;
    }

    return statSync(dir, { throwIfNoEntry: false })?.isFile() ? [dir] : [];
}

/**
 * @param {string} pngPath
 */
function shouldConvert(pngPath) {
    const webpPath = pngPath.replace(/\.png$/i, '.webp');

    if (force) {
        return true;
    }

    const pngStat = statSync(pngPath);
    const webpStat = statSync(webpPath, { throwIfNoEntry: false });

    if (!webpStat) {
        return true;
    }

    return pngStat.mtimeMs > webpStat.mtimeMs;
}

/**
 * @param {string} pngPath
 */
async function convertPng(pngPath) {
    const webpPath = pngPath.replace(/\.png$/i, '.webp');
    const pngSize = statSync(pngPath).size;

    if (dryRun) {
        if (verbose) {
            console.log(`[dry-run] would convert ${relative(root, pngPath)}`);
        }

        return { converted: true, savedBytes: 0 };
    }

    await sharp(pngPath)
        .webp({ quality: WEBP_QUALITY, effort: WEBP_EFFORT })
        .toFile(webpPath);

    const webpSize = statSync(webpPath).size;

    if (verbose) {
        const saved = pngSize - webpSize;
        const pct = pngSize > 0 ? Math.round((saved / pngSize) * 100) : 0;
        console.log(
            `converted ${relative(root, pngPath)} (${formatBytes(pngSize)} → ${formatBytes(webpSize)}, -${pct}%)`,
        );
    }

    return { converted: true, savedBytes: Math.max(0, pngSize - webpSize) };
}

/**
 * @param {number} bytes
 */
function formatBytes(bytes) {
    if (bytes < 1024) {
        return `${bytes} B`;
    }

    if (bytes < 1024 * 1024) {
        return `${(bytes / 1024).toFixed(1)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}

/** @type {string[]} */
const pngFiles = scanRoots.flatMap((path) => collectPngFiles(path));

if (pngFiles.length === 0) {
    console.error('No PNG files found under public/images/playbooks/.');
    process.exit(1);
}

let converted = 0;
let skipped = 0;
let savedBytes = 0;
/** @type {string[]} */
const failures = [];

for (const pngPath of pngFiles) {
    if (!shouldConvert(pngPath)) {
        skipped += 1;

        if (verbose) {
            console.log(`skipped ${relative(root, pngPath)}`);
        }

        continue;
    }

    try {
        const result = await convertPng(pngPath);
        converted += result.converted ? 1 : 0;
        savedBytes += result.savedBytes;
    } catch (error) {
        failures.push(`${relative(root, pngPath)}: ${error instanceof Error ? error.message : String(error)}`);
    }
}

/** @type {string[]} */
const missingWebp = pngFiles.filter((pngPath) => !statSync(pngPath.replace(/\.png$/i, '.webp'), { throwIfNoEntry: false }));

const prefix = dryRun ? '[dry-run] ' : '';
console.log(
    `${prefix}sync-playbook-images: ${converted} converted, ${skipped} skipped, ${pngFiles.length} total, saved ${formatBytes(savedBytes)}`,
);

if (missingWebp.length > 0) {
    console.error(`Missing WebP output for ${missingWebp.length} PNG file(s):`);

    for (const pngPath of missingWebp.slice(0, 10)) {
        console.error(`  - ${relative(root, pngPath)}`);
    }

    if (missingWebp.length > 10) {
        console.error(`  … and ${missingWebp.length - 10} more`);
    }

    process.exit(1);
}

if (failures.length > 0) {
    console.error('Conversion failed for:');

    for (const failure of failures) {
        console.error(`  - ${failure}`);
    }

    process.exit(1);
}

if (converted === 0 && skipped === pngFiles.length) {
    console.log('All WebP files are up to date.');
}
