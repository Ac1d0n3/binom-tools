const STORAGE_KEY = 'binom-tools-playbook-read';

/** @type {Set<string>|null} */
let serverReadCache = null;

/** @returns {boolean} */
export function isServerReadMode() {
    return document.documentElement.dataset.accountsReadMode === '1'
        || document.documentElement.dataset.accountsReadMode === 'true';
}

/**
 * Seed server-side read slugs (accounts mode) from the page bootstrap.
 * @param {string[]} slugs
 */
export function hydrateServerReadSlugs(slugs) {
    serverReadCache = new Set(
        (Array.isArray(slugs) ? slugs : []).filter((slug) => typeof slug === 'string' && slug !== ''),
    );
}

/** @returns {Record<string, number>} */
function readStore() {
    if (isServerReadMode()) {
        /** @type {Record<string, number>} */
        const store = {};
        for (const slug of serverReadCache || []) {
            store[slug] = 1;
        }
        return store;
    }

    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        const parsed = raw ? JSON.parse(raw) : null;

        if (!parsed || typeof parsed !== 'object' || Array.isArray(parsed)) {
            return {};
        }

        /** @type {Record<string, number>} */
        const store = {};

        Object.entries(parsed).forEach(([slug, readAt]) => {
            if (typeof slug === 'string' && slug !== '' && typeof readAt === 'number') {
                store[slug] = readAt;
            }
        });

        return store;
    } catch {
        return {};
    }
}

/** @param {Record<string, number>} store */
function writeStore(store) {
    if (isServerReadMode()) {
        return;
    }
    localStorage.setItem(STORAGE_KEY, JSON.stringify(store));
}

/**
 * @param {string} slug
 */
export function isPlaybookRead(slug) {
    if (!slug) {
        return false;
    }

    return Object.hasOwn(readStore(), slug);
}

/**
 * @returns {string[]}
 */
export function getReadPlaybookSlugs() {
    return Object.keys(readStore());
}

/** @returns {boolean} */
export function hasAnyPlaybookRead() {
    return getReadPlaybookSlugs().length > 0;
}

/**
 * @param {string} slug
 * @returns {boolean} Whether the slug was newly marked as read.
 */
export function markPlaybookRead(slug) {
    if (!slug || isPlaybookRead(slug)) {
        return false;
    }

    if (isServerReadMode()) {
        if (!serverReadCache) {
            serverReadCache = new Set();
        }
        serverReadCache.add(slug);
        window.dispatchEvent(new CustomEvent('binom-tools:playbook-read', { detail: { slug } }));
        const url = document.documentElement.dataset.accountsReadUrl;
        if (url) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            fetch(url.replace('__SLUG__', encodeURIComponent(slug)), {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            }).catch(() => {
                // Keep optimistic UI; server may already have marked on show().
            });
        }
        return true;
    }

    const store = readStore();
    store[slug] = Math.floor(Date.now() / 1000);
    writeStore(store);

    window.dispatchEvent(new CustomEvent('binom-tools:playbook-read', { detail: { slug } }));

    return true;
}

/** @returns {boolean} Whether any entries were cleared. */
export function clearAllPlaybookRead() {
    if (isServerReadMode()) {
        return false;
    }

    if (!hasAnyPlaybookRead()) {
        return false;
    }

    localStorage.removeItem(STORAGE_KEY);
    window.dispatchEvent(new CustomEvent('binom-tools:playbook-read-reset'));

    return true;
}

/** @visibleForTesting */
export function __resetPlaybookReadStoreForTests() {
    serverReadCache = null;
    localStorage.removeItem(STORAGE_KEY);
    delete document.documentElement.dataset.accountsReadMode;
    delete document.documentElement.dataset.accountsReadUrl;
}
