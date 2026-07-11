import { copyFileSync, readFileSync } from 'node:fs';

/** @returns {string} */
function readAppUrl() {
    try {
        const env = readFileSync('.env', 'utf8');
        const match = env.match(/^APP_URL=(.+)$/m);
        return match?.[1]?.trim().replace(/^["']|["']$/g, '') ?? '';
    } catch {
        return '';
    }
}

/**
 * @param {string} appUrl
 * @returns {'local' | 'production'}
 */
function resolveHtaccessVariant(appUrl) {
    if (!appUrl) {
        return 'local';
    }

    try {
        const pathname = new URL(appUrl).pathname.replace(/\/$/, '');
        if (pathname && pathname !== '/') {
            return 'local';
        }
    } catch {
        return 'local';
    }

    return 'production';
}

const appUrl = readAppUrl();
const variant = resolveHtaccessVariant(appUrl);
const source =
    variant === 'local' ? 'public/.htaccess.local' : 'public/.htaccess.production';

copyFileSync(source, 'public/.htaccess');
console.log(`Synced public/.htaccess from ${source} (APP_URL=${appUrl || 'unset'})`);
