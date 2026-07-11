import { createDefaultModelState } from './demo-model.js';

/**
 * PII classification data shared between tools — no model names, descriptions, or doc text.
 * @typedef {Object} PiiMetaState
 * @property {string} piiVersion
 * @property {import('./demo-model.js').PiiScope} defaultScope
 * @property {boolean} useAccessRoles
 * @property {string[]} defaultAccessRoles
 * @property {import('./demo-model.js').AccessRules} accessRules
 * @property {Array<{ name: string, category: string, accessRoles?: string[] }>} columns
 */

/** @param {import('./demo-model.js').DbtModelState} state @returns {PiiMetaState} */
export function extractPiiMeta(state) {
    return {
        piiVersion: state.piiVersion,
        defaultScope: state.defaultScope,
        useAccessRoles: state.useAccessRoles,
        defaultAccessRoles: [...state.defaultAccessRoles],
        accessRules: {
            masked: [...state.accessRules.masked],
            unmasked: [...state.accessRules.unmasked],
        },
        columns: state.columns
            .filter((col) => col.name.trim())
            .map((col) => ({
                name: col.name.trim(),
                category: col.category,
                ...(state.useAccessRoles && col.accessRoles
                    ? { accessRoles: [...col.accessRoles] }
                    : {}),
            })),
    };
}

/** @param {Partial<PiiMetaState> | import('./demo-model.js').DbtModelState | null | undefined} raw @returns {PiiMetaState} */
export function normalizePiiMeta(raw) {
    const defaults = extractPiiMeta(createDefaultModelState());
    if (!raw || typeof raw !== 'object') return defaults;

    // Migrate legacy full-state payloads still in localStorage.
    if ('modelName' in raw || 'modelDescription' in raw || 'sourceTable' in raw) {
        return extractPiiMeta(
            /** @type {import('./demo-model.js').DbtModelState} */ (
                /** @type {Record<string, unknown>} */ (raw)
            ),
        );
    }

    return {
        piiVersion: typeof raw.piiVersion === 'string' ? raw.piiVersion : defaults.piiVersion,
        defaultScope: raw.defaultScope ?? defaults.defaultScope,
        useAccessRoles: raw.useAccessRoles ?? defaults.useAccessRoles,
        defaultAccessRoles: Array.isArray(raw.defaultAccessRoles)
            ? raw.defaultAccessRoles
            : defaults.defaultAccessRoles,
        accessRules: {
            masked: raw.accessRules?.masked ?? defaults.accessRules.masked,
            unmasked: raw.accessRules?.unmasked ?? defaults.accessRules.unmasked,
        },
        columns: Array.isArray(raw.columns)
            ? raw.columns.map((col) => ({
                  name: col.name ?? '',
                  category: col.category ?? 'none',
                  accessRoles: col.accessRoles,
              }))
            : defaults.columns,
    };
}

/**
 * Apply stored PII meta onto workspace state without overwriting model/doc fields.
 * @param {import('./demo-model.js').DbtModelState} workspace
 * @param {PiiMetaState | null | undefined} piiMeta
 * @returns {import('./demo-model.js').DbtModelState}
 */
export function mergePiiMeta(workspace, piiMeta) {
    if (!piiMeta) return workspace;

    const metaByName = new Map(
        piiMeta.columns.filter((col) => col.name.trim()).map((col) => [col.name.trim(), col]),
    );

    const mergedColumns = workspace.columns.map((col) => {
        const meta = metaByName.get(col.name.trim());
        if (!meta) return col;
        return {
            ...col,
            category: meta.category,
            accessRoles: meta.accessRoles,
        };
    });

    for (const metaCol of piiMeta.columns) {
        const name = metaCol.name.trim();
        if (!name || mergedColumns.some((col) => col.name.trim() === name)) continue;
        mergedColumns.push({
            name,
            description: '',
            category: metaCol.category,
            accessRoles: metaCol.accessRoles,
        });
    }

    return {
        ...workspace,
        piiVersion: piiMeta.piiVersion || workspace.piiVersion,
        defaultScope: piiMeta.defaultScope ?? workspace.defaultScope,
        useAccessRoles: piiMeta.useAccessRoles ?? workspace.useAccessRoles,
        defaultAccessRoles: piiMeta.defaultAccessRoles ?? workspace.defaultAccessRoles,
        accessRules: piiMeta.accessRules ?? workspace.accessRules,
        columns: mergedColumns,
    };
}
