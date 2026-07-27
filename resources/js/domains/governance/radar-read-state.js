const READ_STORAGE_KEY = 'binom-tools-governance-radar-read';
const HIDE_READ_STORAGE_KEY = 'binom-tools-governance-radar-hide-read';

/** @returns {Record<string, number>} */
function readStore() {
    try {
        const raw = localStorage.getItem(READ_STORAGE_KEY);
        const parsed = raw ? JSON.parse(raw) : null;
        if (!parsed || typeof parsed !== 'object' || Array.isArray(parsed)) {
            return {};
        }
        /** @type {Record<string, number>} */
        const store = {};
        Object.entries(parsed).forEach(([id, readAt]) => {
            if (typeof id === 'string' && id !== '' && typeof readAt === 'number') {
                store[id] = readAt;
            }
        });
        return store;
    } catch {
        return {};
    }
}

/** @param {Record<string, number>} store */
function writeStore(store) {
    try {
        localStorage.setItem(READ_STORAGE_KEY, JSON.stringify(store));
    } catch {
        // Ignore storage failures.
    }
}

/**
 * @param {string} itemId
 * @returns {boolean}
 */
export function isRadarItemRead(itemId) {
    if (!itemId) {
        return false;
    }
    return Object.hasOwn(readStore(), itemId);
}

/** @returns {string[]} */
export function getRadarReadItemIds() {
    return Object.keys(readStore());
}

/** @returns {boolean} */
export function hasAnyRadarRead() {
    return getRadarReadItemIds().length > 0;
}

/**
 * Hide-read defaults to ON when the key is missing.
 * @returns {boolean}
 */
export function readRadarHideRead() {
    try {
        return localStorage.getItem(HIDE_READ_STORAGE_KEY) !== 'false';
    } catch {
        return true;
    }
}

/**
 * @param {boolean} hide
 */
export function writeRadarHideRead(hide) {
    try {
        localStorage.setItem(HIDE_READ_STORAGE_KEY, hide ? 'true' : 'false');
    } catch {
        // Ignore storage failures.
    }
}

/**
 * @param {string} itemId
 * @returns {boolean}
 */
export function markRadarItemRead(itemId) {
    if (!itemId || isRadarItemRead(itemId)) {
        return false;
    }
    const store = readStore();
    store[itemId] = Math.floor(Date.now() / 1000);
    writeStore(store);
    window.dispatchEvent(new CustomEvent('binom-tools:radar-read', { detail: { itemId } }));
    return true;
}

/**
 * @param {string} itemId
 * @returns {boolean}
 */
export function unmarkRadarItemRead(itemId) {
    if (!itemId || !isRadarItemRead(itemId)) {
        return false;
    }
    const store = readStore();
    delete store[itemId];
    writeStore(store);
    window.dispatchEvent(new CustomEvent('binom-tools:radar-read', { detail: { itemId, unread: true } }));
    return true;
}

/**
 * @param {string} itemId
 * @returns {boolean} Whether the item is read after toggle.
 */
export function toggleRadarItemRead(itemId) {
    if (isRadarItemRead(itemId)) {
        unmarkRadarItemRead(itemId);
        return false;
    }
    markRadarItemRead(itemId);
    return true;
}

/** @returns {boolean} */
export function clearAllRadarRead() {
    if (!hasAnyRadarRead()) {
        return false;
    }
    try {
        localStorage.removeItem(READ_STORAGE_KEY);
    } catch {
        // Ignore.
    }
    window.dispatchEvent(new CustomEvent('binom-tools:radar-read-reset'));
    return true;
}

/** @visibleForTesting */
export function __resetRadarReadStoreForTests() {
    try {
        localStorage.removeItem(READ_STORAGE_KEY);
        localStorage.removeItem(HIDE_READ_STORAGE_KEY);
    } catch {
        // Ignore.
    }
}
