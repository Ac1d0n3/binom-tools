/** @returns {string} */
export function newRowId() {
    if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
        return crypto.randomUUID();
    }
    return `row-${Date.now()}-${Math.random().toString(36).slice(2, 9)}`;
}

/**
 * Remove legacy discovery draft keys if present (tools must not persist plan data).
 * @param {string[]} keys
 */
export function purgeLegacyDraftKeys(keys) {
    for (const key of keys) {
        try {
            localStorage.removeItem(key);
        } catch {
            /* ignore */
        }
    }
}
