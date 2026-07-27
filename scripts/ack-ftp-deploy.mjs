#!/usr/bin/env node
/**
 * Mark the current deploy-ftp pack as successfully uploaded.
 * Next `npm run deploy:ftp` builds the delta against this baseline.
 *
 * Usage: npm run deploy:ftp:ack
 */
import { existsSync, readFileSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const packManifestPath = join(root, 'deploy-ftp', '.pack-manifest.json');
const uploadedManifestPath = join(root, 'deploy-ftp-uploaded.json');

if (!existsSync(packManifestPath)) {
    console.error('Missing deploy-ftp/.pack-manifest.json — run npm run deploy:ftp first.');
    process.exit(1);
}

const pack = JSON.parse(readFileSync(packManifestPath, 'utf8'));
const files = pack?.files && typeof pack.files === 'object' ? pack.files : null;
if (!files || Object.keys(files).length === 0) {
    console.error('Pack manifest has no files — aborting ack.');
    process.exit(1);
}

const payload = {
    ackedAt: new Date().toISOString(),
    builtAt: pack.builtAt ?? null,
    fileCount: Object.keys(files).length,
    files,
};

writeFileSync(uploadedManifestPath, `${JSON.stringify(payload, null, 2)}\n`);
console.log(
    `Acked upload baseline → ${uploadedManifestPath} (${payload.fileCount} files, pack builtAt=${payload.builtAt ?? 'n/a'})`,
);
console.log('Next deploy:ftp delta will only include changes after this ack.');
