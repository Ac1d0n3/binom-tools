/** @typedef {import('./md-export.js').OutputKind} OutputKind */

/** @typedef {'prompt' | 'rule' | 'agent'} StudioArea */

export const STUDIO_AREA_KEY = 'binom-tools-prompt-studio-area';

/** @type {StudioArea[]} */
export const STUDIO_AREAS = ['prompt', 'rule', 'agent'];

/**
 * @param {unknown} value
 * @returns {StudioArea}
 */
export function normalizeStudioArea(value) {
    const id = String(value ?? '').trim();
    return STUDIO_AREAS.includes(/** @type {StudioArea} */ (id))
        ? /** @type {StudioArea} */ (id)
        : 'prompt';
}

/** @returns {StudioArea} */
export function getStudioArea() {
    return normalizeStudioArea(localStorage.getItem(STUDIO_AREA_KEY));
}

/** @param {StudioArea} area */
export function setStudioArea(area) {
    const normalized = normalizeStudioArea(area);
    localStorage.setItem(STUDIO_AREA_KEY, normalized);
    document.getElementById('prompt-studio-app')?.setAttribute('data-ps-studio-area', normalized);
    return normalized;
}

/**
 * @param {StudioArea} area
 * @returns {OutputKind}
 */
export function outputKindForArea(area) {
    if (area === 'rule') return 'rule';
    if (area === 'agent') return 'agent-task';
    return 'prompt';
}

/**
 * @param {OutputKind} kind
 * @returns {StudioArea}
 */
export function areaForOutputKind(kind) {
    if (kind === 'rule') return 'rule';
    if (kind === 'agent-task') return 'agent';
    return 'prompt';
}
