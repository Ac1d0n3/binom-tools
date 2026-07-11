import { createDefaultDqModelState, normalizeDqModelState } from './dq-demo-model.js';

/** @typedef {import('./dq-demo-model.js').DqModelState} DqModelState */

/**
 * @param {DqModelState} state
 * @returns {DqModelState}
 */
export function extractDqMeta(state) {
    return normalizeDqModelState(state);
}

/**
 * @param {Partial<DqModelState>} base
 * @param {Partial<DqModelState>} patch
 * @returns {DqModelState}
 */
export function mergeDqMeta(base, patch) {
    const normalizedBase = normalizeDqModelState(base);
    const normalizedPatch = normalizeDqModelState({ ...createDefaultDqModelState(), ...patch });

    return normalizeDqModelState({
        ...normalizedBase,
        ...normalizedPatch,
        columns: normalizedPatch.columns?.length ? normalizedPatch.columns : normalizedBase.columns,
        modelRules: normalizedPatch.modelRules?.length ? normalizedPatch.modelRules : normalizedBase.modelRules,
    });
}

export { normalizeDqModelState };
