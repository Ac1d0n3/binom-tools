import { normalizeWarehouseId } from '../pii-shared/warehouse-templates.js';
import { createDefaultRule, normalizeRule } from './rule-types.js';

/**
 * @typedef {Object} DqColumn
 * @property {string} name
 * @property {string} [description]
 * @property {import('./rule-types.js').DqRule[]} dqRules
 */

/**
 * @typedef {Object} DqModelState
 * @property {number} version
 * @property {string} modelName
 * @property {string} modelDescription
 * @property {import('../pii-shared/warehouse-templates.js').WarehouseId} selectedWarehouse
 * @property {import('./rule-types.js').DqRule[]} modelRules
 * @property {DqColumn[]} columns
 */

/** @returns {DqColumn[]} */
export function createDefaultColumns() {
    return [
        { name: 'id', description: 'Primary key', dqRules: [normalizeRule({ id: 'id_not_null', type: 'not_null', severity: 'error' })] },
        {
            name: 'email',
            description: 'Customer email',
            dqRules: [
                normalizeRule({ id: 'email_not_null', type: 'not_null', severity: 'error' }),
                normalizeRule({
                    id: 'email_format',
                    type: 'regex',
                    pattern: '^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$',
                    severity: 'warn',
                }),
            ],
        },
        {
            name: 'status',
            description: 'Order status',
            dqRules: [
                normalizeRule({
                    id: 'status_values',
                    type: 'accepted_values',
                    values: ['pending', 'active', 'cancelled'],
                    severity: 'error',
                }),
            ],
        },
        { name: 'amount', description: 'Order amount', dqRules: [normalizeRule({ id: 'amount_range', type: 'range', min: 0, max: 999999, severity: 'error' })] },
        {
            name: 'created_at',
            description: 'Created timestamp',
            dqRules: [normalizeRule({ id: 'created_fresh', type: 'freshness', max_hours: 48, severity: 'warn' })],
        },
    ];
}

/** @returns {DqModelState} */
export function createDefaultDqModelState() {
    return {
        version: 1,
        modelName: 'orders',
        modelDescription: 'Example orders model for data quality rules.',
        selectedWarehouse: 'snowflake',
        modelRules: [
            normalizeRule({ id: 'orders_row_count', type: 'row_count_between', min: 1, max: 5000000, severity: 'warn' }),
        ],
        columns: createDefaultColumns(),
    };
}

/** @param {Partial<DqModelState> | null | undefined} raw @returns {DqModelState} */
export function normalizeDqModelState(raw) {
    const defaults = createDefaultDqModelState();
    if (!raw || typeof raw !== 'object') return defaults;

    return {
        version: raw.version ?? defaults.version,
        modelName: raw.modelName ?? defaults.modelName,
        modelDescription: raw.modelDescription ?? defaults.modelDescription,
        selectedWarehouse: normalizeWarehouseId(raw.selectedWarehouse ?? defaults.selectedWarehouse),
        modelRules: Array.isArray(raw.modelRules) ? raw.modelRules.map(normalizeRule) : defaults.modelRules,
        columns: Array.isArray(raw.columns)
            ? raw.columns.map((col) => ({
                  name: col.name ?? '',
                  description: col.description ?? '',
                  dqRules: Array.isArray(col.dqRules) ? col.dqRules.map(normalizeRule) : [],
              }))
            : defaults.columns,
    };
}
