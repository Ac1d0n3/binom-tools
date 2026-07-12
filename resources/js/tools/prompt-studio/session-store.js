import { createDefaultDraft, normalizeDraft, STORAGE_KEYS } from './storage.js';

/** @typedef {import('./storage.js').PromptDraftState} PromptDraftState */

/**
 * @typedef {Object} PromptSessionVariants
 * @property {boolean} enabled
 * @property {string} varyingFieldId
 * @property {string[]} values
 * @property {number} currentIndex
 */

/**
 * @typedef {Object} PromptSessionChainStep
 * @property {string} id
 * @property {string} roleId
 * @property {string} taskId
 * @property {string} [label]
 * @property {Record<string, unknown>} [parameterValues]
 * @property {string} [previousOutput]
 */

/**
 * @typedef {Object} PromptSessionChain
 * @property {string | null} presetId
 * @property {number} currentStepIndex
 * @property {boolean} isOpen
 * @property {PromptSessionChainStep[]} steps
 */

/**
 * @typedef {Object} PromptStudioSession
 * @property {PromptDraftState} draft
 * @property {PromptSessionChain} activeChain
 * @property {PromptSessionVariants} variants
 * @property {string[]} forbiddenSongWords
 */

export const SESSION_STORAGE_KEY = 'binom-tools-prompt-studio-session';
const LIBRARY_OPEN_KEY = 'binom-tools-prompt-studio-library-open';
const PREVIEW_OPEN_KEY = 'binom-tools-prompt-studio-preview-open';

/** @returns {PromptSessionVariants} */
export function createDefaultVariants() {
    return {
        enabled: false,
        varyingFieldId: 'motiv',
        values: [],
        currentIndex: 0,
    };
}

/** @returns {PromptSessionChain} */
export function createDefaultChain() {
    return {
        presetId: null,
        currentStepIndex: 0,
        isOpen: false,
        steps: [],
    };
}

/** @returns {PromptStudioSession} */
export function createDefaultSession() {
    return {
        draft: createDefaultDraft(),
        activeChain: createDefaultChain(),
        variants: createDefaultVariants(),
        forbiddenSongWords: [],
    };
}

/**
 * @param {unknown} raw
 * @returns {PromptStudioSession}
 */
export function normalizeSession(raw) {
    const base = createDefaultSession();
    if (!raw || typeof raw !== 'object') return base;

    const obj = /** @type {Record<string, unknown>} */ (raw);
    const chainRaw = obj.activeChain && typeof obj.activeChain === 'object' ? obj.activeChain : {};
    const chain = /** @type {Record<string, unknown>} */ (chainRaw);
    const variantsRaw = obj.variants && typeof obj.variants === 'object' ? obj.variants : {};
    const variants = /** @type {Record<string, unknown>} */ (variantsRaw);

    /** @type {PromptSessionChainStep[]} */
    let steps = [];
    if (Array.isArray(chain.steps)) {
        steps = chain.steps
            .filter((s) => s && typeof s === 'object')
            .map((s) => {
                const step = /** @type {Record<string, unknown>} */ (s);
                return {
                    id: typeof step.id === 'string' ? step.id : `step_${Date.now()}`,
                    roleId: typeof step.roleId === 'string' ? step.roleId : '',
                    taskId: typeof step.taskId === 'string' ? step.taskId : '',
                    label: typeof step.label === 'string' ? step.label : undefined,
                    parameterValues:
                        step.parameterValues && typeof step.parameterValues === 'object'
                            ? /** @type {Record<string, unknown>} */ (step.parameterValues)
                            : {},
                    previousOutput: typeof step.previousOutput === 'string' ? step.previousOutput : '',
                };
            });
    }

    return {
        draft: normalizeDraft(obj.draft),
        activeChain: {
            presetId: typeof chain.presetId === 'string' ? chain.presetId : null,
            currentStepIndex:
                typeof chain.currentStepIndex === 'number' && Number.isFinite(chain.currentStepIndex)
                    ? Math.max(0, Math.floor(chain.currentStepIndex))
                    : 0,
            isOpen: chain.isOpen === true,
            steps,
        },
        variants: {
            enabled: variants.enabled === true,
            varyingFieldId: typeof variants.varyingFieldId === 'string' ? variants.varyingFieldId : base.variants.varyingFieldId,
            values: Array.isArray(variants.values) ? variants.values.map(String).filter(Boolean) : [],
            currentIndex:
                typeof variants.currentIndex === 'number' && Number.isFinite(variants.currentIndex)
                    ? Math.max(0, Math.floor(variants.currentIndex))
                    : 0,
        },
        forbiddenSongWords: Array.isArray(obj.forbiddenSongWords)
            ? obj.forbiddenSongWords.map(String).filter(Boolean)
            : [],
    };
}

/** @returns {{ data: PromptStudioSession, meta: { savedAt: string, source: string } } | { corrupt: true } | null} */
export function loadSession() {
    try {
        const raw = localStorage.getItem(SESSION_STORAGE_KEY);
        if (!raw) {
            const legacy = localStorage.getItem(STORAGE_KEYS.draft);
            if (legacy) {
                return {
                    data: normalizeSession({ draft: JSON.parse(legacy) }),
                    meta: { savedAt: '', source: 'legacy' },
                };
            }
            return null;
        }
        const parsed = JSON.parse(raw);
        const metaRaw = localStorage.getItem(`${SESSION_STORAGE_KEY}-meta`);
        const meta = metaRaw ? JSON.parse(metaRaw) : { savedAt: '', source: 'auto' };
        return { data: normalizeSession(parsed), meta };
    } catch {
        return { corrupt: true };
    }
}

/** @param {Partial<PromptStudioSession>} patch @param {'auto' | 'manual'} [source] */
export function saveSession(patch, source = 'auto') {
    const current = loadSession();
    const base = current && 'data' in current ? current.data : createDefaultSession();
    const next = normalizeSession({
        ...base,
        ...patch,
        draft: patch.draft ? normalizeDraft({ ...base.draft, ...patch.draft }) : base.draft,
        activeChain: patch.activeChain ? { ...base.activeChain, ...patch.activeChain } : base.activeChain,
        variants: patch.variants ? { ...base.variants, ...patch.variants } : base.variants,
        forbiddenSongWords: patch.forbiddenSongWords ?? base.forbiddenSongWords,
    });

    const meta = { savedAt: new Date().toISOString(), source };
    localStorage.setItem(SESSION_STORAGE_KEY, JSON.stringify(next));
    localStorage.setItem(`${SESSION_STORAGE_KEY}-meta`, JSON.stringify(meta));
    localStorage.setItem(STORAGE_KEYS.draft, JSON.stringify(next.draft));

    window.dispatchEvent(
        new CustomEvent('binom-tools:prompt-studio-session-updated', {
            detail: { session: next, meta },
        }),
    );

    return next;
}

let sessionSaveTimer = 0;

/** @param {Partial<PromptStudioSession>} patch @param {'auto' | 'manual'} [source] */
export function debouncedSaveSession(patch, source = 'auto') {
    window.clearTimeout(sessionSaveTimer);
    sessionSaveTimer = window.setTimeout(() => saveSession(patch, source), 300);
}

/** @returns {boolean} */
export function isLibraryDrawerOpen() {
    const stored = localStorage.getItem(LIBRARY_OPEN_KEY);
    if (stored === null) return false;
    return stored === 'true';
}

/** @param {boolean} open */
export function setLibraryDrawerOpen(open) {
    localStorage.setItem(LIBRARY_OPEN_KEY, open ? 'true' : 'false');
}

/** @returns {boolean} */
export function isPreviewDrawerOpen() {
    const stored = localStorage.getItem(PREVIEW_OPEN_KEY);
    if (stored === null) return true;
    return stored === 'true';
}

/** @param {boolean} open */
export function setPreviewDrawerOpen(open) {
    localStorage.setItem(PREVIEW_OPEN_KEY, open ? 'true' : 'false');
}

/** @param {string[]} words */
export function saveForbiddenSongWords(words) {
    const session = loadSession();
    const base = session && 'data' in session ? session.data : createDefaultSession();
    saveSession({ ...base, forbiddenSongWords: words }, 'manual');
}

/** @returns {string[]} */
export function loadForbiddenSongWords() {
    const session = loadSession();
    if (session && 'data' in session) {
        const words = session.data.forbiddenSongWords;
        return Array.isArray(words) ? words.map(String).filter(Boolean) : [];
    }
    return [];
}
