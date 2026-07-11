import {
    DEFAULT_CONTENT_SCAN_MIN_MATCH_RATE,
    normalizeContentHeuristicRules,
} from './content-heuristic-rules.js';
import { createDefaultModelState, DEFAULT_REVIEW_ROLES } from './demo-model.js';
import { normalizeHeuristicRules } from './heuristic-rules.js';
import { normalizeWarehouseId } from './warehouse-templates.js';

/**
 * PII classification data shared between tools — no model names, descriptions, or doc text.
 * @typedef {Object} PiiMetaState
 * @property {import('./warehouse-templates.js').WarehouseId} selectedWarehouse
 * @property {string} piiVersion
 * @property {import('./demo-model.js').PiiScope} defaultScope
 * @property {boolean} useAccessRoles
 * @property {string[]} defaultAccessRoles
 * @property {import('./demo-model.js').AccessRules} accessRules
 * @property {string[]} defaultModelAccessGroups
 * @property {import('./heuristic-rules.js').HeuristicRule[]} nameHeuristicRules
 * @property {import('./content-heuristic-rules.js').ContentHeuristicRule[]} contentHeuristicRules
 * @property {string[]} defaultReviewRoles
 * @property {number} contentScanDefaultMinMatchRate
 * @property {Array<{ name: string, category: string, accessRoles?: string[] }>} columns
 */

/** @param {import('./demo-model.js').DbtModelState} state @returns {PiiMetaState} */
export function extractPiiMeta(state) {
    return {
        selectedWarehouse: normalizeWarehouseId(state.selectedWarehouse),
        piiVersion: state.piiVersion,
        defaultScope: state.defaultScope,
        useAccessRoles: state.useAccessRoles,
        defaultAccessRoles: [...state.defaultAccessRoles],
        accessRules: {
            masked: [...state.accessRules.masked],
            unmasked: [...state.accessRules.unmasked],
        },
        defaultModelAccessGroups: [...(state.defaultModelAccessGroups ?? [])],
        nameHeuristicRules: normalizeHeuristicRules(state.nameHeuristicRules),
        contentHeuristicRules: normalizeContentHeuristicRules(state.contentHeuristicRules),
        defaultReviewRoles: [...(state.defaultReviewRoles ?? DEFAULT_REVIEW_ROLES)],
        contentScanDefaultMinMatchRate:
            state.contentScanDefaultMinMatchRate ?? DEFAULT_CONTENT_SCAN_MIN_MATCH_RATE,
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
        selectedWarehouse: normalizeWarehouseId(
            typeof raw.selectedWarehouse === 'string' ? raw.selectedWarehouse : defaults.selectedWarehouse,
        ),
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
        defaultModelAccessGroups: Array.isArray(raw.defaultModelAccessGroups)
            ? raw.defaultModelAccessGroups
            : defaults.defaultModelAccessGroups,
        nameHeuristicRules: normalizeHeuristicRules(raw.nameHeuristicRules ?? defaults.nameHeuristicRules),
        contentHeuristicRules: normalizeContentHeuristicRules(
            raw.contentHeuristicRules ?? defaults.contentHeuristicRules,
        ),
        defaultReviewRoles: Array.isArray(raw.defaultReviewRoles)
            ? raw.defaultReviewRoles
            : defaults.defaultReviewRoles,
        contentScanDefaultMinMatchRate:
            typeof raw.contentScanDefaultMinMatchRate === 'number'
                ? raw.contentScanDefaultMinMatchRate
                : defaults.contentScanDefaultMinMatchRate,
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
        selectedWarehouse: piiMeta.selectedWarehouse ?? workspace.selectedWarehouse,
        piiVersion: piiMeta.piiVersion || workspace.piiVersion,
        defaultScope: piiMeta.defaultScope ?? workspace.defaultScope,
        useAccessRoles: piiMeta.useAccessRoles ?? workspace.useAccessRoles,
        defaultAccessRoles: piiMeta.defaultAccessRoles ?? workspace.defaultAccessRoles,
        accessRules: piiMeta.accessRules ?? workspace.accessRules,
        defaultModelAccessGroups: piiMeta.defaultModelAccessGroups ?? workspace.defaultModelAccessGroups,
        nameHeuristicRules: piiMeta.nameHeuristicRules ?? workspace.nameHeuristicRules,
        contentHeuristicRules: piiMeta.contentHeuristicRules ?? workspace.contentHeuristicRules,
        defaultReviewRoles: piiMeta.defaultReviewRoles ?? workspace.defaultReviewRoles,
        contentScanDefaultMinMatchRate:
            piiMeta.contentScanDefaultMinMatchRate ?? workspace.contentScanDefaultMinMatchRate,
        columns: mergedColumns,
    };
}

/** @param {import('./schema-storage.js').SchemaMeta['source']} source @returns {string} */
export function formatSyncSourceLabel(source) {
    const labels = {
        'dbt-governance-macro-generator': 'Governance Macro Generator',
        'pii-policy-generator': 'DBT Policy Generator',
        'pii-unreviewed-gate-generator': 'Unreviewed Table Gate Generator',
        'pii-recommend-generator': 'PII Recommend Generator',
        'schema-yml-editor': 'Schema YML Editor',
        manual: 'manual',
    };
    return labels[source] ?? source;
}
