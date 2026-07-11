/** @typedef {'snowflake' | 'bigquery' | 'redshift' | 'databricks' | 'postgres'} WarehouseId */

/** @typedef {Object} WarehouseTemplate
 * @property {string} label
 * @property {(columnRef: string) => string} maskExpr
 * @property {(columnRef: string, pattern: string) => string} regexMatch
 * @property {() => string} nullCast
 * @property {(limit: number) => string} sampleClause
 */

/** @type {Record<WarehouseId, WarehouseTemplate>} */
export const warehouseTemplates = {
    snowflake: {
        label: 'Snowflake',
        maskExpr: (col) =>
            `concat('***', substring(${col}, 1, 2), '…', substring(${col}, greatest(length(${col}) - 1, 1)))`,
        regexMatch: (col, pat) => `REGEXP_LIKE(${col}, '${pat}')`,
        nullCast: () => 'cast(null as varchar)',
        sampleClause: (limit) => `SAMPLE (${limit} ROWS)`,
    },
    bigquery: {
        label: 'BigQuery',
        maskExpr: (col) =>
            `CONCAT('***', SUBSTR(${col}, 1, 2), '…', SUBSTR(${col}, GREATEST(LENGTH(${col}) - 1, 1)))`,
        regexMatch: (col, pat) => `REGEXP_CONTAINS(${col}, r'${pat}')`,
        nullCast: () => 'CAST(NULL AS STRING)',
        sampleClause: (limit) => `TABLESAMPLE SYSTEM (10 PERCENT) LIMIT ${limit}`,
    },
    redshift: {
        label: 'Redshift',
        maskExpr: (col) =>
            `'***' || SUBSTRING(${col}, 1, 2) || '…' || SUBSTRING(${col}, GREATEST(LENGTH(${col}) - 1, 1))`,
        regexMatch: (col, pat) => `${col} ~ '${pat}'`,
        nullCast: () => 'CAST(NULL AS VARCHAR(256))',
        sampleClause: (limit) => `LIMIT ${limit}`,
    },
    databricks: {
        label: 'Databricks',
        maskExpr: (col) =>
            `concat('***', substring(${col}, 1, 2), '…', substring(${col}, greatest(length(${col}) - 1, 1)))`,
        regexMatch: (col, pat) => `${col} RLIKE '${pat}'`,
        nullCast: () => 'cast(null as string)',
        sampleClause: (limit) => `LIMIT ${limit}`,
    },
    postgres: {
        label: 'Postgres',
        maskExpr: (col) =>
            `'***' || substring(${col} from 1 for 2) || '…' || substring(${col} from greatest(length(${col}) - 1, 1))`,
        regexMatch: (col, pat) => `${col} ~ '${pat}'`,
        nullCast: () => 'cast(null as text)',
        sampleClause: (limit) => `LIMIT ${limit}`,
    },
};

/** @type {WarehouseId[]} */
export const warehouseIds = ['snowflake', 'bigquery', 'redshift', 'databricks', 'postgres'];

/** @param {string} id @returns {WarehouseId} */
export function normalizeWarehouseId(id) {
    return /** @type {WarehouseId} */ (
        warehouseIds.includes(/** @type {WarehouseId} */ (id)) ? id : 'snowflake'
    );
}

/** @param {WarehouseId} id @returns {WarehouseTemplate} */
export function getWarehouseTemplate(id) {
    return warehouseTemplates[normalizeWarehouseId(id)];
}
