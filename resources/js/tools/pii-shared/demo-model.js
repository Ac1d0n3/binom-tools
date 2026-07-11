import {
    REFERENCE_MODEL_ACCESS_GROUPS,
    REFERENCE_MODEL_NAME,
    REFERENCE_PII_VERSION,
    REFERENCE_SOURCE_TABLE,
} from './reference-scenario.js';
import {
    DEFAULT_CONTENT_SCAN_MIN_MATCH_RATE,
    normalizeContentHeuristicRules,
} from './content-heuristic-rules.js';
import { DEFAULT_HEURISTIC_RULES, normalizeHeuristicRules } from './heuristic-rules.js';
import { normalizeWarehouseId } from './warehouse-templates.js';

export const DEFAULT_REVIEW_ROLES = ['dpo', 'security'];

/** @typedef {'internal' | 'external' | 'none'} PiiScope */

/**
 * @typedef {Object} AccessRules
 * @property {string[]} masked
 * @property {string[]} unmasked
 */

/**
 * @typedef {Object} ModelColumn
 * @property {string} name
 * @property {string} [description]
 * @property {string} category
 * @property {string[]} [accessRoles]
 */

/**
 * @typedef {Object} DbtModelState
 * @property {number} version
 * @property {string} modelName
 * @property {string} sourceTable
 * @property {string} piiVersion
 * @property {string} modelDescription
 * @property {string} descriptionExtra
 * @property {PiiScope} defaultScope
 * @property {boolean} useAccessRoles
 * @property {string[]} defaultAccessRoles
 * @property {AccessRules} accessRules
 * @property {string[]} defaultModelAccessGroups
 * @property {import('./warehouse-templates.js').WarehouseId} selectedWarehouse
 * @property {import('./heuristic-rules.js').HeuristicRule[]} nameHeuristicRules
 * @property {import('./content-heuristic-rules.js').ContentHeuristicRule[]} contentHeuristicRules
 * @property {string[]} defaultReviewRoles
 * @property {number} contentScanDefaultMinMatchRate
 * @property {ModelColumn[]} columns
 */

export const piiTypeOptions = [
    'none',
    'name',
    'email',
    'ip',
    'address',
    'phone',
    'url',
    'geo',
    'card',
    'iban',
    'passport',
    'license_plate',
    'date_of_birth',
    'custom',
];

export const scopeOptions = ['internal', 'external', 'none'];

export const ACCESS_MODE_ROLE_LINE = 'Access mode: role-based (access_roles per PII column).';
export const ACCESS_MODE_RULES_LINE =
    'Access mode: rule-based (access_rules masked/unmasked per PII column).';

/**
 * @param {string} sourceTable
 * @param {string} piiVersion
 * @param {boolean} [useAccessRoles]
 * @returns {string}
 */
export function buildDefaultModelDescription(
    sourceTable = 'raw.example_table',
    piiVersion = 'cf38c9353be46d305f35c22a8d926c62',
    useAccessRoles = true,
) {
    return [
        `This model contains data from the '${sourceTable}' table.`,
        `PII details version: ${piiVersion}`,
        useAccessRoles ? ACCESS_MODE_ROLE_LINE : ACCESS_MODE_RULES_LINE,
    ].join('\n');
}

/** @param {string} modelDescription @param {boolean} useAccessRoles */
export function syncAccessModeLineInDescription(modelDescription, useAccessRoles) {
    const nextLine = useAccessRoles ? ACCESS_MODE_ROLE_LINE : ACCESS_MODE_RULES_LINE;
    const rolePattern = /Access mode: role-based[^\n]*/;
    const rulesPattern = /Access mode: rule-based[^\n]*/;

    if (rolePattern.test(modelDescription) || rulesPattern.test(modelDescription)) {
        return modelDescription.replace(rolePattern, nextLine).replace(rulesPattern, nextLine);
    }

    return modelDescription;
}

/** @param {DbtModelState} state */
export function prepareColumnsForAccessMode(state) {
    for (const column of state.columns) {
        if (!column.category || column.category === 'none') {
            delete column.accessRoles;
            continue;
        }

        if (state.useAccessRoles) {
            if (!column.accessRoles?.length) {
                column.accessRoles = [...state.defaultAccessRoles];
            }
        } else {
            delete column.accessRoles;
        }
    }
}

/** @returns {ModelColumn[]} */
export function createDefaultColumns() {
    return [
        { name: 'id', category: 'none', description: '' },
        { name: 'agent_id', category: 'none', description: '' },
        { name: 'agent_name', category: 'name_internal', description: '', accessRoles: ['analyst', 'support'] },
        { name: 'customer_email', category: 'email_internal', description: '', accessRoles: ['support', 'admin'] },
        { name: 'client_ip', category: 'ip_external', description: '', accessRoles: ['security', 'admin'] },
        { name: 'shipping_address', category: 'address_internal', description: '', accessRoles: ['analyst', 'fulfillment'] },
        { name: 'info_mail', category: 'none', description: '' },
        { name: 'created_at', category: 'none', description: '' },
    ];
}

/** @returns {DbtModelState} */
export function createDefaultModelState() {
    const sourceTable = REFERENCE_SOURCE_TABLE;
    const piiVersion = REFERENCE_PII_VERSION;
    return {
        version: 2,
        modelName: REFERENCE_MODEL_NAME,
        sourceTable,
        piiVersion,
        modelDescription: buildDefaultModelDescription(sourceTable, piiVersion),
        descriptionExtra: '',
        defaultScope: 'internal',
        useAccessRoles: true,
        defaultAccessRoles: ['analyst', 'support'],
        accessRules: {
            masked: ['analyst', 'support'],
            unmasked: ['admin', 'dpo'],
        },
        defaultModelAccessGroups: [...REFERENCE_MODEL_ACCESS_GROUPS],
        selectedWarehouse: 'snowflake',
        nameHeuristicRules: normalizeHeuristicRules(DEFAULT_HEURISTIC_RULES),
        contentHeuristicRules: normalizeContentHeuristicRules(null),
        defaultReviewRoles: [...DEFAULT_REVIEW_ROLES],
        contentScanDefaultMinMatchRate: DEFAULT_CONTENT_SCAN_MIN_MATCH_RATE,
        columns: createDefaultColumns(),
    };
}

/** @param {Partial<DbtModelState> | null | undefined} raw @returns {DbtModelState} */
export function normalizeModelState(raw) {
    const defaults = createDefaultModelState();
    if (!raw || typeof raw !== 'object') return defaults;

    return {
        version: raw.version ?? defaults.version,
        modelName: raw.modelName ?? defaults.modelName,
        sourceTable: raw.sourceTable ?? defaults.sourceTable,
        piiVersion: raw.piiVersion ?? defaults.piiVersion,
        modelDescription: raw.modelDescription ?? '',
        descriptionExtra: raw.descriptionExtra ?? '',
        defaultScope: raw.defaultScope ?? defaults.defaultScope,
        useAccessRoles: raw.useAccessRoles ?? defaults.useAccessRoles,
        defaultAccessRoles: Array.isArray(raw.defaultAccessRoles) ? raw.defaultAccessRoles : defaults.defaultAccessRoles,
        accessRules: {
            masked: raw.accessRules?.masked ?? defaults.accessRules.masked,
            unmasked: raw.accessRules?.unmasked ?? defaults.accessRules.unmasked,
        },
        defaultModelAccessGroups: Array.isArray(raw.defaultModelAccessGroups)
            ? raw.defaultModelAccessGroups
            : defaults.defaultModelAccessGroups,
        selectedWarehouse: normalizeWarehouseId(raw.selectedWarehouse ?? defaults.selectedWarehouse),
        nameHeuristicRules: normalizeHeuristicRules(raw.nameHeuristicRules ?? defaults.nameHeuristicRules),
        contentHeuristicRules: normalizeContentHeuristicRules(
            raw.contentHeuristicRules ?? defaults.contentHeuristicRules,
        ),
        defaultReviewRoles: Array.isArray(raw.defaultReviewRoles)
            ? raw.defaultReviewRoles
            : defaults.defaultReviewRoles,
        contentScanDefaultMinMatchRate:
            raw.contentScanDefaultMinMatchRate ?? defaults.contentScanDefaultMinMatchRate,
        columns: Array.isArray(raw.columns)
            ? raw.columns.map((col) => ({
                  name: col.name ?? '',
                  description: col.description ?? '',
                  category: col.category ?? 'none',
                  accessRoles: col.accessRoles,
              }))
            : defaults.columns,
    };
}

/** @param {PiiScope} scope */
export function scopeSuffix(scope) {
    if (scope === 'none') return '';
    return `_${scope}`;
}

/** @param {string} piiType @param {PiiScope} scope */
export function buildCategoryValue(piiType, scope) {
    if (piiType === 'none' || scope === 'none') return 'none';
    return `${piiType}_${scope}`;
}

/** @param {string} category */
export function splitCategoryValue(category) {
    if (!category || category === 'none') {
        return { piiType: 'none', scope: 'none' };
    }
    const match = category.match(/^(.*)_(internal|external)$/);
    if (!match) {
        return { piiType: category, scope: 'internal' };
    }
    return { piiType: match[1], scope: /** @type {PiiScope} */ (match[2]) };
}

/**
 * @param {string} columnName
 * @param {PiiScope} [defaultScope]
 * @param {import('./heuristic-rules.js').HeuristicRule[]} [rules]
 */
export function suggestCategoryFromColumnName(columnName, defaultScope = 'internal', rules = DEFAULT_HEURISTIC_RULES) {
    const lower = columnName.trim().toLowerCase();
    if (!lower) return 'none';
    if (lower === 'id') return 'none';

    const normalizedRules = normalizeHeuristicRules(rules);
    for (const rule of normalizedRules) {
        const pattern = rule.pattern.toLowerCase();
        if (!pattern || !lower.includes(pattern)) continue;
        if (rule.piiType === 'none') return 'none';
        if (pattern === 'mail' && lower.includes('info')) continue;
        if (pattern === 'name' && lower.includes('filename')) continue;
        return buildCategoryValue(rule.piiType, defaultScope);
    }

    return 'none';
}

/** @returns {string} */
export function generatePiiVersionHash() {
    const random = Math.random().toString(16).slice(2) + Date.now().toString(16);
    return random.padEnd(32, '0').slice(0, 32);
}
