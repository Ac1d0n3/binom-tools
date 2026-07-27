import { createDefaultDqModelState, normalizeDqModelState } from './dq-demo-model.js';

const STORAGE_KEY = 'binom-tools-dq-schema-state';
const META_KEY = 'binom-tools-dq-schema-meta';
const EVENT_NAME = 'binom-tools:dq-schema-updated';

/** @typedef {{ savedAt: string, source: 'dbt-dq-macro-generator' | 'dbt-dq-rules-generator' | 'dbt-dq-history-generator' | 'manual' }} DqSchemaMeta */

/**
 * @returns {{ state: import('./dq-demo-model.js').DqModelState, meta: DqSchemaMeta } | { corrupt: true } | null}
 */
export function loadDqMetaState() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return null;
        const parsed = JSON.parse(raw);
        const metaRaw = localStorage.getItem(META_KEY);
        const meta = metaRaw ? /** @type {DqSchemaMeta} */ (JSON.parse(metaRaw)) : { savedAt: '', source: 'manual' };
        return { state: normalizeDqModelState(parsed), meta };
    } catch {
        return { corrupt: true };
    }
}

/** @param {unknown} loaded @returns {boolean} */
export function isDqStorageLoadCorrupt(loaded) {
    return loaded !== null && typeof loaded === 'object' && 'corrupt' in loaded && loaded.corrupt === true;
}

/**
 * @param {import('./dq-demo-model.js').DqModelState} state
 * @param {DqSchemaMeta['source']} [source]
 */
export function saveDqMetaState(state, source = 'manual') {
    const normalized = normalizeDqModelState(state);
    const meta = /** @type {DqSchemaMeta} */ ({
        savedAt: new Date().toISOString(),
        source,
    });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(normalized));
    localStorage.setItem(META_KEY, JSON.stringify(meta));
    window.dispatchEvent(new CustomEvent(EVENT_NAME, { detail: { state: normalized, meta } }));
}

/** @type {ReturnType<typeof setTimeout> | 0} */
let saveTimer = 0;

/**
 * @param {import('./dq-demo-model.js').DqModelState} state
 * @param {DqSchemaMeta['source']} [source]
 */
export function debouncedSaveDqState(state, source = 'manual') {
    if (saveTimer) clearTimeout(saveTimer);
    saveTimer = setTimeout(() => saveDqMetaState(state, source), 300);
}

/** @param {(detail: { state: import('./dq-demo-model.js').DqModelState, meta: DqSchemaMeta | null }) => void} listener */
export function subscribeDqState(listener) {
    window.addEventListener(EVENT_NAME, (event) => {
        const detail = /** @type {CustomEvent<{ state: import('./dq-demo-model.js').DqModelState, meta: DqSchemaMeta | null }>} */ (
            event
        ).detail;
        listener(detail);
    });
}

export function clearDqState() {
    localStorage.removeItem(STORAGE_KEY);
    localStorage.removeItem(META_KEY);
    window.dispatchEvent(
        new CustomEvent(EVENT_NAME, {
            detail: { state: createDefaultDqModelState(), meta: null },
        }),
    );
}
