/** @typedef {'to-sanitizer' | 'to-studio'} BridgeDirection */

/**
 * @typedef {Object} PromptBridgePayload
 * @property {number} version
 * @property {BridgeDirection} direction
 * @property {{ compiled: string, sections?: Record<string, string> }} prompt
 * @property {{
 *   roleId?: string,
 *   taskId?: string,
 *   modelId?: string,
 *   parameterValues?: Record<string, unknown>,
 * }} [studioContext]
 * @property {{
 *   outbound?: string,
 *   restored?: string,
 *   findingCount?: number,
 * }} [sanitizerContext]
 */

/** @typedef {{ savedAt: string, source: 'prompt-studio' | 'governance-ai-sanitizer', direction: BridgeDirection }} PromptBridgeMeta */

export const BRIDGE_STORAGE_KEY = 'binom-tools-prompt-bridge';
export const BRIDGE_META_KEY = 'binom-tools-prompt-bridge-meta';
export const BRIDGE_EVENT_NAME = 'binom-tools:prompt-bridge-updated';

/** @returns {PromptBridgePayload} */
export function createEmptyBridgePayload() {
    return {
        version: 1,
        direction: 'to-sanitizer',
        prompt: { compiled: '', sections: {} },
        studioContext: {},
        sanitizerContext: {},
    };
}

/**
 * @param {unknown} raw
 * @returns {PromptBridgePayload}
 */
export function normalizeBridgePayload(raw) {
    const base = createEmptyBridgePayload();
    if (!raw || typeof raw !== 'object') return base;

    const obj = /** @type {Record<string, unknown>} */ (raw);
    const prompt = obj.prompt && typeof obj.prompt === 'object' ? /** @type {Record<string, unknown>} */ (obj.prompt) : {};
    const studioContext =
        obj.studioContext && typeof obj.studioContext === 'object'
            ? /** @type {Record<string, unknown>} */ (obj.studioContext)
            : {};
    const sanitizerContext =
        obj.sanitizerContext && typeof obj.sanitizerContext === 'object'
            ? /** @type {Record<string, unknown>} */ (obj.sanitizerContext)
            : {};

    return {
        version: typeof obj.version === 'number' ? obj.version : 1,
        direction: obj.direction === 'to-studio' ? 'to-studio' : 'to-sanitizer',
        prompt: {
            compiled: typeof prompt.compiled === 'string' ? prompt.compiled : '',
            sections:
                prompt.sections && typeof prompt.sections === 'object'
                    ? /** @type {Record<string, string>} */ (prompt.sections)
                    : {},
        },
        studioContext: {
            roleId: typeof studioContext.roleId === 'string' ? studioContext.roleId : undefined,
            taskId: typeof studioContext.taskId === 'string' ? studioContext.taskId : undefined,
            modelId: typeof studioContext.modelId === 'string' ? studioContext.modelId : undefined,
            parameterValues:
                studioContext.parameterValues && typeof studioContext.parameterValues === 'object'
                    ? /** @type {Record<string, unknown>} */ (studioContext.parameterValues)
                    : {},
        },
        sanitizerContext: {
            outbound: typeof sanitizerContext.outbound === 'string' ? sanitizerContext.outbound : undefined,
            restored: typeof sanitizerContext.restored === 'string' ? sanitizerContext.restored : undefined,
            findingCount:
                typeof sanitizerContext.findingCount === 'number' ? sanitizerContext.findingCount : undefined,
        },
    };
}

/** @returns {{ payload: PromptBridgePayload, meta: PromptBridgeMeta } | { corrupt: true } | null} */
export function loadPromptBridge() {
    try {
        const raw = localStorage.getItem(BRIDGE_STORAGE_KEY);
        if (!raw) return null;
        const parsed = JSON.parse(raw);
        const metaRaw = localStorage.getItem(BRIDGE_META_KEY);
        const meta = metaRaw
            ? /** @type {PromptBridgeMeta} */ (JSON.parse(metaRaw))
            : { savedAt: '', source: 'prompt-studio', direction: 'to-sanitizer' };
        return { payload: normalizeBridgePayload(parsed), meta };
    } catch {
        return { corrupt: true };
    }
}

/**
 * @param {PromptBridgePayload} payload
 * @param {PromptBridgeMeta['source']} source
 */
export function savePromptBridge(payload, source) {
    const normalized = normalizeBridgePayload(payload);
    const meta = /** @type {PromptBridgeMeta} */ ({
        savedAt: new Date().toISOString(),
        source,
        direction: normalized.direction,
    });

    localStorage.setItem(BRIDGE_STORAGE_KEY, JSON.stringify(normalized));
    localStorage.setItem(BRIDGE_META_KEY, JSON.stringify(meta));
    window.dispatchEvent(new CustomEvent(BRIDGE_EVENT_NAME, { detail: { payload: normalized, meta } }));
}

export function clearPromptBridge() {
    localStorage.removeItem(BRIDGE_STORAGE_KEY);
    localStorage.removeItem(BRIDGE_META_KEY);
    window.dispatchEvent(
        new CustomEvent(BRIDGE_EVENT_NAME, {
            detail: { payload: createEmptyBridgePayload(), meta: null },
        }),
    );
}

/**
 * @param {unknown} loaded
 * @returns {boolean}
 */
export function isBridgeLoadCorrupt(loaded) {
    return loaded !== null && typeof loaded === 'object' && 'corrupt' in loaded && loaded.corrupt === true;
}

/**
 * @param {{
 *   compiled: string,
 *   sections?: Record<string, string>,
 *   studioContext?: PromptBridgePayload['studioContext'],
 * }} input
 */
export function saveToSanitizer(input) {
    savePromptBridge(
        {
            version: 1,
            direction: 'to-sanitizer',
            prompt: {
                compiled: input.compiled,
                sections: input.sections ?? {},
            },
            studioContext: input.studioContext ?? {},
            sanitizerContext: {},
        },
        'prompt-studio',
    );
}

/**
 * @param {{
 *   outbound?: string,
 *   restored?: string,
 *   findingCount?: number,
 *   studioContext?: PromptBridgePayload['studioContext'],
 *   sections?: Record<string, string>,
 * }} input
 */
export function saveToStudio(input) {
    const compiled = input.restored ?? input.outbound ?? '';
    savePromptBridge(
        {
            version: 1,
            direction: 'to-studio',
            prompt: {
                compiled,
                sections: input.sections ?? { user: compiled },
            },
            studioContext: input.studioContext ?? {},
            sanitizerContext: {
                outbound: input.outbound,
                restored: input.restored,
                findingCount: input.findingCount,
            },
        },
        'governance-ai-sanitizer',
    );
}

/** @param {(payload: { payload: PromptBridgePayload | null, meta: PromptBridgeMeta | null }) => void} callback */
export function subscribePromptBridge(callback) {
    const onCustom = (/** @type {CustomEvent} */ event) => {
        const detail = event.detail ?? {};
        callback({
            payload: detail.payload ? normalizeBridgePayload(detail.payload) : null,
            meta: detail.meta ?? null,
        });
    };

    const onStorage = (/** @type {StorageEvent} */ event) => {
        if (event.key !== BRIDGE_STORAGE_KEY) return;
        const loaded = loadPromptBridge();
        if (loaded && 'payload' in loaded) {
            callback({ payload: loaded.payload, meta: loaded.meta });
        }
    };

    window.addEventListener(BRIDGE_EVENT_NAME, onCustom);
    window.addEventListener('storage', onStorage);

    return () => {
        window.removeEventListener(BRIDGE_EVENT_NAME, onCustom);
        window.removeEventListener('storage', onStorage);
    };
}

/**
 * @param {number} [maxAgeMs]
 * @returns {boolean}
 */
export function hasFreshBridgePayload(maxAgeMs = 1000 * 60 * 60 * 24) {
    const loaded = loadPromptBridge();
    if (!loaded || !('payload' in loaded) || !loaded.meta.savedAt) return false;
    const age = Date.now() - new Date(loaded.meta.savedAt).getTime();
    return age <= maxAgeMs && Boolean(loaded.payload.prompt.compiled.trim());
}
