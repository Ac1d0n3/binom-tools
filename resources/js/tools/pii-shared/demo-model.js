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
    const sourceTable = 'raw.example_table';
    const piiVersion = 'cf38c9353be46d305f35c22a8d926c62';
    return {
        version: 2,
        modelName: 'example_table',
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
 * @param {PiiScope} defaultScope
 */
export function suggestCategoryFromColumnName(columnName, defaultScope = 'internal') {
    const lower = columnName.trim().toLowerCase();
    if (!lower) return 'none';

    if (lower.includes('info_mail') || lower.includes('info-mail') || lower === 'info_email') {
        return 'none';
    }
    if (lower === 'id' || lower.endsWith('_id') || lower.includes('created_at') || lower.includes('updated_at')) {
        return 'none';
    }
    if (lower.includes('email') || (lower.includes('mail') && !lower.includes('info'))) {
        return buildCategoryValue('email', defaultScope);
    }
    if (lower.includes('agent_name') || lower.includes('full_name') || lower.includes('person_name') || (lower.includes('name') && !lower.includes('filename'))) {
        return buildCategoryValue('name', defaultScope);
    }
    if (lower.includes('ipv4') || lower.includes('ipv6') || lower.includes('ip') || lower.includes('client_ip')) {
        return buildCategoryValue('ip', defaultScope);
    }
    if (lower.includes('address') || lower.includes('street') || lower.includes('postal') || lower.includes('zip')) {
        return buildCategoryValue('address', defaultScope);
    }
    if (lower.includes('phone') || lower.includes('mobile') || lower.includes('tel')) {
        return buildCategoryValue('phone', defaultScope);
    }
    if (lower.includes('geo') || lower.includes('latitude') || lower.includes('longitude') || lower.includes('lat') || lower.includes('lon')) {
        return buildCategoryValue('geo', defaultScope);
    }
    if (lower.includes('card') || lower.includes('pan')) {
        return buildCategoryValue('card', defaultScope);
    }
    if (lower.includes('iban')) {
        return buildCategoryValue('iban', defaultScope);
    }
    if (lower.includes('passport')) {
        return buildCategoryValue('passport', defaultScope);
    }
    if (lower.includes('plate') || lower.includes('license')) {
        return buildCategoryValue('license_plate', defaultScope);
    }
    if (lower.includes('dob') || lower.includes('birth') || lower.includes('date_of_birth')) {
        return buildCategoryValue('date_of_birth', defaultScope);
    }
    if (lower.includes('url') || lower.includes('link') || lower.includes('website')) {
        return buildCategoryValue('url', defaultScope);
    }

    return 'none';
}

/** @returns {string} */
export function generatePiiVersionHash() {
    const random = Math.random().toString(16).slice(2) + Date.now().toString(16);
    return random.padEnd(32, '0').slice(0, 32);
}
