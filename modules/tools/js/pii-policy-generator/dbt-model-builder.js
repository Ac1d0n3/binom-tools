/**
 * @param {string} sourceTable
 * @returns {{ sourceName: string, tableName: string, database?: string }}
 */
function parseSourceTable(sourceTable) {
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
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildDbtModelExample(state) {
    const piiColumns = state.columns.filter((col) => col.category && col.category !== 'none');
    const plainColumns = state.columns.filter((col) => !col.category || col.category === 'none');
    const { sourceName, tableName, database } = parseSourceTable(state.sourceTable);

    const selectLines = [
        ...plainColumns.map((col) => `    ${col.name},`),
        ...piiColumns.map(
            (col) => `    {{ pii_column_for_role('${col.name}', var('pii_user_role'), '${state.modelName}') }} as ${col.name},`,
        ),
    ];

    if (selectLines.length) {
        selectLines[selectLines.length - 1] = selectLines[selectLines.length - 1].replace(/,$/, '');
    }

    const databaseComment = database ? `\n-- Source database: ${database}` : '';

    return `-- models/marts/${state.modelName}_secure.sql
-- Step 2 — PII Policy Generator
-- Requires: macros/pii_governance.sql (Step 1 — PII Macro Generator)
-- Requires: models/schema/${state.modelName}.yml with meta.pii_details (this tool)
-- Runnable example: set var pii_user_role in dbt_project.yml or via --vars${databaseComment}

{{ config(
    materialized='view',
    tags=['pii', 'governance']
) }}

select
${selectLines.join('\n')}
from {{ source('${sourceName}', '${tableName}') }}

{# Test locally:
   dbt run --select ${state.modelName}_secure --vars '{"pii_user_role": "analyst"}'
   dbt run --select ${state.modelName}_secure --vars '{"pii_user_role": "admin"}'
#}
`;
}
