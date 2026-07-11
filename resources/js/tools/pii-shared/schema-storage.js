import { extractPiiMeta, normalizePiiMeta } from './pii-meta.js';
import { createDefaultModelState } from './demo-model.js';

const STORAGE_KEY = 'binom-tools-pii-schema-state';
const META_KEY = 'binom-tools-pii-schema-meta';
const YAML_SESSION_KEY = 'binom-tools-schema-yaml-draft';
const EVENT_NAME = 'binom-tools:pii-schema-updated';

/** @typedef {{ savedAt: string, source: 'pii-policy-generator' | 'schema-yml-editor' | 'manual' }} SchemaMeta */

/**
 * @returns {{ state: import('./pii-meta.js').PiiMetaState, meta: SchemaMeta } | null}
 */
export function loadPiiMetaState() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return null;
        const parsed = JSON.parse(raw);
        const metaRaw = localStorage.getItem(META_KEY);
        const meta = metaRaw ? /** @type {SchemaMeta} */ (JSON.parse(metaRaw)) : { savedAt: '', source: 'manual' };
        return { state: normalizePiiMeta(parsed), meta };
    } catch {
        return null;
    }
}

/** @deprecated Use loadPiiMetaState */
export function loadSchemaState() {
    return loadPiiMetaState();
}

/**
 * @param {import('./pii-meta.js').PiiMetaState} state
 * @param {SchemaMeta['source']} [source]
 */
export function savePiiMetaState(state, source = 'manual') {
    const normalized = normalizePiiMeta(state);
    const meta = /** @type {SchemaMeta} */ ({
        savedAt: new Date().toISOString(),
        source,
    });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(normalized));
    localStorage.setItem(META_KEY, JSON.stringify(meta));
    window.dispatchEvent(new CustomEvent(EVENT_NAME, { detail: { state: normalized, meta } }));
}

/** @param {import('./demo-model.js').DbtModelState} state @param {SchemaMeta['source']} [source] */
export function saveSchemaState(state, source = 'manual') {
    savePiiMetaState(extractPiiMeta(state), source);
}

export function clearSchemaState() {
    localStorage.removeItem(STORAGE_KEY);
    localStorage.removeItem(META_KEY);
    window.dispatchEvent(
        new CustomEvent(EVENT_NAME, {
            detail: { state: normalizePiiMeta(createDefaultModelState()), meta: null },
        }),
    );
}

/** @returns {string | null} */
export function loadYamlDraft() {
    try {
        return sessionStorage.getItem(YAML_SESSION_KEY);
    } catch {
        return null;
    }
}

/** @param {string} yaml */
export function saveYamlDraft(yaml) {
    try {
        sessionStorage.setItem(YAML_SESSION_KEY, yaml);
    } catch {
        // sessionStorage may be unavailable in private mode
    }
}

export function clearYamlDraft() {
    try {
        sessionStorage.removeItem(YAML_SESSION_KEY);
    } catch {
        // ignore
    }
}

/** @param {(payload: { state: import('./pii-meta.js').PiiMetaState | null, meta: SchemaMeta | null }) => void} callback */
export function subscribeSchemaState(callback) {
    const onCustom = (/** @type {CustomEvent} */ event) => {
        const detail = event.detail;
        callback({
            state: detail?.state ? normalizePiiMeta(detail.state) : null,
            meta: detail.meta ?? null,
        });
    };

    const onStorage = (/** @type {StorageEvent} */ event) => {
        if (event.key !== STORAGE_KEY) return;
        const loaded = loadPiiMetaState();
        if (loaded) callback({ state: loaded.state, meta: loaded.meta });
    };

    window.addEventListener(EVENT_NAME, onCustom);
    window.addEventListener('storage', onStorage);

    return () => {
        window.removeEventListener(EVENT_NAME, onCustom);
        window.removeEventListener('storage', onStorage);
    };
}

let saveTimer = 0;

/**
 * @param {import('./demo-model.js').DbtModelState} state
 * @param {SchemaMeta['source']} source
 */
export function debouncedSaveSchemaState(state, source) {
    window.clearTimeout(saveTimer);
    saveTimer = window.setTimeout(() => savePiiMetaState(extractPiiMeta(state), source), 300);
}
