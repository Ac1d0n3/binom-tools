/**
 * Copy curated Font Awesome Free solid SVGs into public/icons/avatar/
 * so avatar chips/pickers do not depend on the webfont.
 */
import { copyFileSync, existsSync, mkdirSync, readdirSync, rmSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const sourceDir = join(root, 'node_modules/@fortawesome/fontawesome-free/svgs/solid');
const targetDir = join(root, 'public/icons/avatar');

const ICONS = [
    'user',
    'user-tie',
    'user-gear',
    'user-graduate',
    'user-astronaut',
    'user-ninja',
    'user-secret',
    'user-doctor',
    'users',
    'people-group',
    'person',
    'face-smile',
    'hat-wizard',
    'crown',
    'rocket',
    'flask',
    'code',
    'laptop-code',
    'database',
    'chart-line',
    'shield-halved',
    'lightbulb',
    'star',
    'heart',
    'paw',
    'cat',
    'dog',
    'mug-hot',
    'headphones',
    'camera',
    'gamepad',
    'music',
    'brush',
    'compass',
    'sitemap',
    'brain',
    'briefcase',
    'gear',
    'globe',
    'trophy',
    'puzzle-piece',
    'microscope',
    'leaf',
    'fire',
    'moon',
    'sun',
    'dragon',
    'fish',
    'handshake',
    'key',
    'wrench',
    'gift',
    'glasses',
    'feather',
    'cube',
    'cloud',
    'bug',
    'book',
    'bicycle',
    'seedling',
    'yin-yang',
    'atom',
    'bolt',
    'ghost',
    'gem',
    'ankh',
    'om',
    'peace',
    'spa',
    'wand-magic-sparkles',
    'infinity',
    'fingerprint',
    'cookie',
    'bomb',
    'layer-group',
    'diagram-project',
    'circle-nodes',
];

if (!existsSync(sourceDir)) {
    console.error(`Missing Font Awesome SVGs at ${sourceDir}. Run npm install.`);
    process.exit(1);
}

mkdirSync(targetDir, { recursive: true });
for (const file of readdirSync(targetDir)) {
    if (file.endsWith('.svg')) {
        rmSync(join(targetDir, file));
    }
}

const missing = [];
for (const icon of ICONS) {
    const src = join(sourceDir, `${icon}.svg`);
    if (!existsSync(src)) {
        missing.push(icon);
        continue;
    }
    copyFileSync(src, join(targetDir, `${icon}.svg`));
}

if (missing.length) {
    console.error(`Missing FA solid icons: ${missing.join(', ')}`);
    process.exit(1);
}

console.log(`Synced ${ICONS.length} Font Awesome avatar SVGs → public/icons/avatar/`);
