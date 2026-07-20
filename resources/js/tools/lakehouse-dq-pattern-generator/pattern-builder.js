/** @param {string} value */
export function splitCsv(value) {
    return value.split(',').map((item) => item.trim()).filter(Boolean);
}

/** @param {string} value */
function q(value) {
    return value.includes('.') ? value.split('.').map((part) => `[${part}]`).join('.') : `[${value}]`;
}

/** @param {string} value */
function bt(value) {
    return value.includes('.') ? value.split('.').map((part) => `\`${part}\``).join('.') : `\`${value}\``;
}

/** @param {{ table: string, keys: string[], required: string[], freshness: string, pii: string[], owner: string, pattern: string }} state */
export function buildFabricSql(state) {
    const table = q(state.table);
    const keys = state.keys.length ? state.keys : ['business_key'];
    const required = state.required.length ? state.required : keys;
    const keyJoin = keys.map((col) => `[${col}]`).join(', ');
    const nullChecks = required.map((col) => `    SUM(CASE WHEN [${col}] IS NULL THEN 1 ELSE 0 END) AS ${col}_nulls`).join(',\n');

    if (state.pattern === 'delta') {
        return `-- Fabric Warehouse / Lakehouse incremental load pattern
MERGE INTO ${table} AS target
USING [staging].[${state.table.split('.').pop()}_incremental] AS source
ON ${keys.map((col) => `target.[${col}] = source.[${col}]`).join(' AND ')}
WHEN MATCHED AND source.[${state.freshness}] > target.[${state.freshness}] THEN
    UPDATE SET *
WHEN NOT MATCHED THEN
    INSERT *;

-- Gate: compare affected rows, reject unexpected key duplicates before merge.`;
    }

    if (state.pattern === 'scd2') {
        return `-- Fabric SCD2 starter pattern
WITH source_rows AS (
    SELECT *, HASHBYTES('SHA2_256', CONCAT_WS('|', *)) AS row_hash
    FROM [staging].[${state.table.split('.').pop()}]
),
changed AS (
    SELECT source_rows.*
    FROM source_rows
    LEFT JOIN ${table} current_rows
      ON ${keys.map((col) => `source_rows.[${col}] = current_rows.[${col}]`).join(' AND ')}
     AND current_rows.[is_current] = 1
    WHERE current_rows.[row_hash] IS NULL OR source_rows.[row_hash] <> current_rows.[row_hash]
)
SELECT * FROM changed;

-- Add transaction steps: expire current row, insert new row with valid_from/valid_to/is_current.`;
    }

    if (state.pattern === 'governance') {
        return `-- Fabric governance gate
SELECT
    '${state.table}' AS table_name,
    '${state.owner}' AS expected_owner,
    COUNT(*) AS row_count,
    MAX([${state.freshness}]) AS last_update
FROM ${table};

-- Check sensitivity labels and workspace access before publishing semantic model.`;
    }

    return `-- Fabric DQ checks
WITH base AS (
    SELECT * FROM ${table}
),
dupes AS (
    SELECT ${keyJoin}, COUNT(*) AS duplicate_count
    FROM base
    GROUP BY ${keyJoin}
    HAVING COUNT(*) > 1
)
SELECT
    COUNT(*) AS row_count,
${nullChecks},
    (SELECT COUNT(*) FROM dupes) AS duplicate_key_count,
    DATEDIFF(hour, MAX([${state.freshness}]), SYSUTCDATETIME()) AS freshness_hours
FROM base;`;
}

/** @param {{ table: string, keys: string[], required: string[], freshness: string, pii: string[], owner: string, pattern: string }} state */
export function buildFabricNotebook(state) {
    const requiredList = JSON.stringify(state.required.length ? state.required : state.keys);
    return `# Fabric notebook validation starter
table_name = \"${state.table}\"
required_columns = ${requiredList}
key_columns = ${JSON.stringify(state.keys)}
freshness_column = \"${state.freshness}\"

df = spark.read.table(table_name)

null_counts = {
    col: df.filter(df[col].isNull()).count()
    for col in required_columns
}
duplicate_count = df.groupBy(*key_columns).count().filter(\"count > 1\").count()

display({
    \"table\": table_name,
    \"null_counts\": null_counts,
    \"duplicate_key_count\": duplicate_count,
    \"owner\": \"${state.owner}\",
})

assert duplicate_count == 0, \"Duplicate business keys found\"
assert all(count == 0 for count in null_counts.values()), \"Required values missing\"`;
}

/** @param {{ table: string, keys: string[], required: string[], freshness: string, pii: string[], owner: string, pattern: string }} state */
export function buildDatabricksSql(state) {
    const table = bt(state.table);
    const keys = state.keys.length ? state.keys : ['business_key'];
    const required = state.required.length ? state.required : keys;
    const requiredExpectations = required.map((col) => `CONSTRAINT ${col}_not_null EXPECT (${col} IS NOT NULL)`).join('\n');

    if (state.pattern === 'delta') {
        return `-- Databricks Delta MERGE pattern
MERGE INTO ${table} AS target
USING staging.${state.table.split('.').pop()}_incremental AS source
ON ${keys.map((col) => `target.${col} = source.${col}`).join(' AND ')}
WHEN MATCHED AND source.${state.freshness} > target.${state.freshness} THEN UPDATE SET *
WHEN NOT MATCHED THEN INSERT *;

OPTIMIZE ${table};
VACUUM ${table} RETAIN 168 HOURS;`;
    }

    if (state.pattern === 'scd2') {
        return `-- Databricks SCD2 changed-row detector
WITH source_rows AS (
    SELECT *, sha2(concat_ws('|', *), 256) AS row_hash
    FROM staging.${state.table.split('.').pop()}
),
changed AS (
    SELECT source_rows.*
    FROM source_rows
    LEFT JOIN ${table} current_rows
      ON ${keys.map((col) => `source_rows.${col} = current_rows.${col}`).join(' AND ')}
     AND current_rows.is_current = true
    WHERE current_rows.row_hash IS NULL OR source_rows.row_hash <> current_rows.row_hash
)
SELECT * FROM changed;`;
    }

    if (state.pattern === 'governance') {
        return `-- Unity Catalog governance starter
ALTER TABLE ${table} SET TAGS ('owner' = '${state.owner}', 'dq_required' = 'true');
${state.pii.map((col) => `ALTER TABLE ${table} ALTER COLUMN ${col} SET TAGS ('pii' = 'true');`).join('\n')}

GRANT SELECT ON TABLE ${table} TO \`${state.owner}\`;`;
    }

    return `-- Delta Live Tables expectations starter
CREATE OR REFRESH LIVE TABLE ${state.table.replaceAll('.', '_')}_dq
${requiredExpectations}
CONSTRAINT freshness_ok EXPECT (${state.freshness} >= current_timestamp() - INTERVAL 24 HOURS)
AS SELECT * FROM ${table};`;
}

/** @param {{ table: string, keys: string[], required: string[], freshness: string, pii: string[], owner: string, pattern: string }} state */
export function buildDatabricksNotebook(state) {
    return `# Databricks notebook validation starter
from pyspark.sql import functions as F

table_name = \"${state.table}\"
key_columns = ${JSON.stringify(state.keys)}
required_columns = ${JSON.stringify(state.required.length ? state.required : state.keys)}

df = spark.table(table_name)

null_counts = {
    col: df.where(F.col(col).isNull()).count()
    for col in required_columns
}
duplicate_count = df.groupBy(*key_columns).count().where(F.col(\"count\") > 1).count()

result = {
    \"table\": table_name,
    \"owner\": \"${state.owner}\",
    \"null_counts\": null_counts,
    \"duplicate_key_count\": duplicate_count,
}
display(result)

assert duplicate_count == 0, \"Duplicate keys found\"
assert all(count == 0 for count in null_counts.values()), \"Required values missing\"`;
}

/** @param {'fabric' | 'databricks'} platform @param {{ table: string, keys: string[], required: string[], freshness: string, pii: string[], owner: string, pattern: string }} state */
export function buildRunbook(platform, state) {
    const platformLabel = platform === 'fabric' ? 'Fabric' : 'Databricks';
    return `# ${platformLabel} DQ / Governance Runbook

## Scope
- Table: ${state.table}
- Owner: ${state.owner}
- Keys: ${state.keys.join(', ') || 'not set'}
- Required fields: ${state.required.join(', ') || 'not set'}
- Freshness column: ${state.freshness}
- Sensitive fields: ${state.pii.join(', ') || 'none listed'}

## Checks
- Run null checks for every required field.
- Run duplicate checks on the business key.
- Check freshness before publishing downstream app items.
- Review PII/sensitive fields before granting broad access.

## Release gate
- DQ result attached to task.
- Owner accepted known data gaps.
- Access reviewed for report/app consumers.
- Lineage/catalog entry updated.
- Rollback path documented.`;
}
