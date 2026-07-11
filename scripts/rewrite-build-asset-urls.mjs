#!/usr/bin/env node
/**
 * Rewrite absolute /build/assets/ font URLs to relative paths so one CSS build
 * works under any APP_URL subdirectory (MAMP) and at domain root (production).
 */
import { readdirSync, readFileSync, writeFileSync } from 'node:fs';
import { join } from 'node:path';

const assetsDir = join(process.cwd(), 'public/build/assets');
const pattern = /url\(\s*["']?(?:\/[^"')]+)?\/build\/assets\/([^"')]+)["']?\s*\)/g;

let changed = 0;

for (const file of readdirSync(assetsDir)) {
    if (!file.endsWith('.css')) continue;

    const path = join(assetsDir, file);
    const source = readFileSync(path, 'utf8');
    const next = source.replace(pattern, 'url(./$1)');

    if (next !== source) {
        writeFileSync(path, next);
        changed += 1;
    }
}

if (changed > 0) {
    console.log(`Rewrote font URLs in ${changed} CSS file(s) to relative paths.`);
}
