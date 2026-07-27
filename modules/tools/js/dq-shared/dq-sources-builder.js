/**
 * @param {string} sourceTable
 * @returns {{ sourceName: string, tableName: string, database?: string }}
 */
export function parseSourceTable(sourceTable) {
    const parts = sourceTable.split('.').filter(Boolean);
    if (parts.length >= 3) {
        return { database: parts[0], sourceName: parts[1], tableName: parts[2] };
    }
    if (parts.length === 2) {
        return { sourceName: parts[0], tableName: parts[1] };
    }
    return { sourceName: 'raw', tableName: parts[0] || 'table' };
}

/**
 * @param {import('./dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqSourcesYaml(state) {
    const { sourceName, tableName, database } = parseSourceTable(state.sourceTable);
    const databaseLine = database ? `\n    database: ${database}` : '';

    return `# Step 2 — DQ Rules Generator
# Source for model "${state.modelName}" — columns in schema.yml mirror this table

version: 2

sources:
  - name: ${sourceName}${databaseLine}
    tables:
      - name: ${tableName}
        description: Source table for ${state.modelName} data quality rules.
`;
}

/**
 * @param {import('./dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqModelSql(state) {
    const { sourceName, tableName, database } = parseSourceTable(state.sourceTable);
    const databaseComment = database ? `\n-- Source database: ${database}` : '';

    return `-- models/marts/${state.modelName}.sql
-- Step 2 — DQ Rules Generator
-- Source: ${state.sourceTable}
-- Requires: models/schema/${state.modelName}.yml with meta.dq_rules (this tool)
-- Requires: macros/dq_governance.sql + tests/generic/dq_rule.sql (Step 1)${databaseComment}

{{ config(
    materialized='view',
    tags=['dq']
) }}

select *
from {{ source('${sourceName}', '${tableName}') }}

{# Run DQ tests:
   dbt test --select ${state.modelName},tag:dq
#}
`;
}
