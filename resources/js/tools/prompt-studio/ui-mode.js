/** @typedef {import('./config-validator.js').PromptParameterDef} PromptParameterDef */

export const UI_MODE_KEY = 'binom-tools-prompt-studio-mode';

/** @typedef {'regular' | 'tech'} UiMode */

/** @returns {UiMode} */
export function getUiMode() {
    const stored = localStorage.getItem(UI_MODE_KEY);
    return stored === 'tech' ? 'tech' : 'regular';
}

/** @param {UiMode} mode */
export function setUiMode(mode) {
    localStorage.setItem(UI_MODE_KEY, mode);
    document.getElementById('prompt-studio-app')?.classList.toggle('prompt-studio--tech', mode === 'tech');
    document.getElementById('prompt-studio-app')?.classList.toggle('prompt-studio--regular', mode !== 'tech');
}

/** @returns {boolean} */
export function isTechMode() {
    return getUiMode() === 'tech';
}

/** Parameters hidden in Regular mode unless chain needs previousOutput */
export const TECH_ONLY_PARAMETER_IDS = new Set([
    'stakeholders',
    'documents',
    'kpis',
    'conversationHistory',
    'version',
    'architecture',
    'standards',
    'security',
    'tests',
    'size',
    'lighting',
    'camera',
    'reference',
    'negativePrompt',
]);

const REGULAR_PRIMARY_LIMIT = 4;

/**
 * @param {PromptParameterDef[]} parameters
 * @param {{ chainOpen?: boolean, taskId?: string }} [options]
 * @returns {{ primary: PromptParameterDef[], advanced: PromptParameterDef[] }}
 */
export function splitParametersForMode(parameters, options = {}) {
    if (isTechMode()) {
        return { primary: parameters, advanced: [] };
    }

    const documentPrimaryTasks = new Set(['document-summarize', 'document-create']);

    /** @type {PromptParameterDef[]} */
    const primary = [];
    /** @type {PromptParameterDef[]} */
    const advanced = [];

    for (const def of parameters) {
        const forcePrimary = def.id === 'documents' && documentPrimaryTasks.has(options.taskId ?? '');

        const isTechOnly =
            !forcePrimary &&
            (TECH_ONLY_PARAMETER_IDS.has(def.id) ||
                def.techOnly === true ||
                (def.id === 'previousOutput' && !options.chainOpen) ||
                (def.id === 'documents' && !documentPrimaryTasks.has(options.taskId ?? '')));

        if (isTechOnly) {
            advanced.push(def);
            continue;
        }

        if (def.required || primary.length < REGULAR_PRIMARY_LIMIT) {
            primary.push(def);
        } else {
            advanced.push(def);
        }
    }

    return { primary, advanced };
}

/** @param {HTMLElement} root */
export function applyUiModeClasses(root) {
    const mode = getUiMode();
    root.classList.toggle('prompt-studio--tech', mode === 'tech');
    root.classList.toggle('prompt-studio--regular', mode !== 'tech');
}
