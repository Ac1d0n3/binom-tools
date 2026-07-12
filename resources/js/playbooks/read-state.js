const STORAGE_KEY = 'binom-tools-playbook-read';

/** @returns {Record<string, number>} */
function readStore() {
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

    const store = readStore();
    store[slug] = Math.floor(Date.now() / 1000);
    writeStore(store);

    window.dispatchEvent(new CustomEvent('binom-tools:playbook-read', { detail: { slug } }));

    return true;
}

/** @returns {boolean} Whether any entries were cleared. */
export function clearAllPlaybookRead() {
    if (!hasAnyPlaybookRead()) {
        return false;
    }

    localStorage.removeItem(STORAGE_KEY);
    window.dispatchEvent(new CustomEvent('binom-tools:playbook-read-reset'));

    return true;
}

/** @visibleForTesting */
export function __resetPlaybookReadStoreForTests() {
    localStorage.removeItem(STORAGE_KEY);
}
