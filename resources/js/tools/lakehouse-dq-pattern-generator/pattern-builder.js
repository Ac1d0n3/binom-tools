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

/** @param {{ table: string, keys: string[], required: string[], freshness: string, pii: string[], owner: string, pattern: string, toolId?: string }} state */
export function buildFabricSql(state) {
    const table = q(state.table);
    const keys = state.keys.length ? state.keys : ['business_key'];
    const required = state.required.length ? state.required : keys;
    const keyJoin = keys.map((col) => `[${col}]`).join(', ');
    const nullChecks = required.map((col) => `    SUM(CASE WHEN [${col}] IS NULL THEN 1 ELSE 0 END) AS ${col}_nulls`).join(',\n');

    if (state.toolId === 'fabric-dq-rule-generator') {
        const ruleRows = required.map((col) => `    SELECT '${state.table}' AS table_name, '${col}' AS column_name, 'not_null' AS rule_type, 'error' AS severity`).join('\n    UNION ALL\n');
        return `-- Fabric DQ Rule Generator
-- Register these rules as your model/app release gate.
WITH dq_rules AS (
${ruleRows}
    UNION ALL
    SELECT '${state.table}', '${keys.join(',')}', 'unique_key', 'error'
    UNION ALL
    SELECT '${state.table}', '${state.freshness}', 'freshness_24h', 'warning'
)
SELECT *
FROM dq_rules;

-- Runtime check starter
WITH base AS (
    SELECT * FROM ${table}
),
duplicate_keys AS (
    SELECT ${keyJoin}, COUNT(*) AS duplicate_count
    FROM base
    GROUP BY ${keyJoin}
    HAVING COUNT(*) > 1
)
SELECT
${nullChecks},
    (SELECT COUNT(*) FROM duplicate_keys) AS duplicate_key_count,
    DATEDIFF(hour, MAX([${state.freshness}]), SYSUTCDATETIME()) AS freshness_hours
FROM base;`;
    }

    if (state.toolId === 'fabric-notebook-snippet-generator') {
        return `-- Fabric Notebook Snippet Generator
-- Optional SQL pre-check cell before the PySpark validation cell.
SELECT
    '${state.table}' AS notebook_target,
    COUNT(*) AS row_count,
    MAX([${state.freshness}]) AS max_freshness,
    '${state.owner}' AS owner_group
FROM ${table};

-- Use the generated notebook output for executable validation cells.`;
    }

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
        const piiRows = state.pii.length
            ? state.pii.map((col) => `    SELECT '${col}' AS column_name, 'PII' AS sensitivity, '${state.owner}' AS owner_group`).join('\n    UNION ALL\n')
            : `    SELECT 'review_required' AS column_name, 'UNKNOWN' AS sensitivity, '${state.owner}' AS owner_group`;
        return `-- Fabric governance gate
SELECT
    '${state.table}' AS table_name,
    '${state.owner}' AS expected_owner,
    COUNT(*) AS row_count,
    MAX([${state.freshness}]) AS last_update
FROM ${table};

-- Suggested PII review register rows for Purview/Fabric workspace review.
WITH pii_columns AS (
${piiRows}
)
SELECT *
FROM pii_columns;

-- Gate before publish: sensitivity labels set, workspace access reviewed, export risk accepted.`;
    }

    if (state.pattern === 'pipeline') {
        return `-- Fabric pipeline release checklist query
SELECT
    '${state.table}' AS table_name,
    '${state.owner}' AS owner_group,
    COUNT(*) AS row_count,
    MAX([${state.freshness}]) AS last_update,
    CASE WHEN COUNT(*) = 0 THEN 'BLOCK' ELSE 'OK' END AS row_gate
FROM ${table};

-- Checklist before scheduling:
-- 1. Source increment and retry behavior documented.
-- 2. Delta/SCD load validated with duplicate-key checks.
-- 3. Failed-run alert owner set to ${state.owner}.
-- 4. Downstream semantic model refresh dependency documented.`;
    }

    if (state.pattern === 'semantic') {
        return `-- Fabric semantic model guardrails
SELECT
    '${state.table}' AS semantic_source,
    '${state.owner}' AS semantic_owner,
    COUNT(*) AS row_count,
    MAX([${state.freshness}]) AS last_update
FROM ${table};

-- Guardrails:
-- - Hide technical keys and sensitive source fields from report consumers.
-- - Keep measures in the semantic layer; do not duplicate KPI formulas in visuals.
-- - Review export permissions for: ${state.pii.join(', ') || 'no PII listed'}.
-- - Require owner sign-off before broad workspace/app publishing.`;
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

/** @param {{ table: string, keys: string[], required: string[], freshness: string, pii: string[], owner: string, pattern: string, toolId?: string }} state */
export function buildFabricNotebook(state) {
    const requiredList = JSON.stringify(state.required.length ? state.required : state.keys);
    if (state.toolId === 'fabric-notebook-snippet-generator') {
        return `# Fabric Notebook Snippet Generator
from pyspark.sql import functions as F

table_name = \"${state.table}\"
key_columns = ${JSON.stringify(state.keys)}
required_columns = ${requiredList}
freshness_column = \"${state.freshness}\"
owner_group = \"${state.owner}\"

df = spark.read.table(table_name)

checks = []
for column_name in required_columns:
    null_count = df.where(F.col(column_name).isNull()).count()
    checks.append({\"check\": \"not_null\", \"column\": column_name, \"failed_rows\": null_count})

duplicate_count = df.groupBy(*key_columns).count().where(F.col(\"count\") > 1).count()
freshness = df.agg(F.max(freshness_column).alias(\"max_freshness\")).collect()[0][\"max_freshness\"]

display({
    \"table\": table_name,
    \"owner_group\": owner_group,
    \"checks\": checks,
    \"duplicate_key_count\": duplicate_count,
    \"max_freshness\": freshness,
})

assert duplicate_count == 0, \"Duplicate business keys found\"
assert all(item[\"failed_rows\"] == 0 for item in checks), \"Required values missing\"`;
    }

    if (state.pattern === 'pipeline') {
        return `# Fabric pipeline checklist starter
table_name = \"${state.table}\"
owner_group = \"${state.owner}\"
key_columns = ${JSON.stringify(state.keys)}
freshness_column = \"${state.freshness}\"

df = spark.read.table(table_name)
duplicate_count = df.groupBy(*key_columns).count().filter(\"count > 1\").count()
row_count = df.count()

display({
    \"table\": table_name,
    \"owner_group\": owner_group,
    \"row_count\": row_count,
    \"duplicate_key_count\": duplicate_count,
    \"release_gate\": \"alerts, retry path, semantic refresh dependency, owner sign-off\",
})

assert row_count > 0, \"Pipeline output is empty\"
assert duplicate_count == 0, \"Duplicate business keys found\"`;
    }

    if (state.pattern === 'semantic') {
        return `# Fabric semantic model guardrails starter
table_name = \"${state.table}\"
owner_group = \"${state.owner}\"
pii_columns = ${JSON.stringify(state.pii)}

display({
    \"semantic_source\": table_name,
    \"owner_group\": owner_group,
    \"guardrails\": [
        \"hide technical keys\",
        \"centralize KPI measures\",
        \"review export permissions\",
        \"require owner sign-off before publishing\",
    ],
    \"pii_columns\": pii_columns,
})`;
    }

    if (state.pattern === 'governance') {
        return `# Fabric PII / governance review starter
table_name = \"${state.table}\"
owner_group = \"${state.owner}\"
pii_columns = ${JSON.stringify(state.pii)}
freshness_column = \"${state.freshness}\"

df = spark.read.table(table_name)

summary = {
    \"table\": table_name,
    \"owner_group\": owner_group,
    \"pii_columns\": pii_columns,
    \"row_count\": df.count(),
    \"last_update\": df.agg({freshness_column: \"max\"}).collect()[0][0],
    \"review_gate\": \"set sensitivity labels, validate role access, document export risk\",
}

display(summary)
assert owner_group, \"Owner group required before publishing app model\"`;
    }

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

/** @param {{ table: string, keys: string[], required: string[], freshness: string, pii: string[], owner: string, pattern: string, toolId?: string }} state */
export function buildDatabricksSql(state) {
    const table = bt(state.table);
    const keys = state.keys.length ? state.keys : ['business_key'];
    const required = state.required.length ? state.required : keys;
    const requiredExpectations = required.map((col) => `CONSTRAINT ${col}_not_null EXPECT (${col} IS NOT NULL)`).join('\n');

    if (state.toolId === 'databricks-dq-expectation-generator') {
        return `-- Databricks DQ Expectation Generator
CREATE OR REFRESH LIVE TABLE ${state.table.replaceAll('.', '_')}_validated
${requiredExpectations}
CONSTRAINT ${keys.join('_')}_unique EXPECT (${keys.map((col) => `${col} IS NOT NULL`).join(' AND ')})
CONSTRAINT freshness_24h EXPECT (${state.freshness} >= current_timestamp() - INTERVAL 24 HOURS)
AS
SELECT *
FROM ${table};

-- Add pipeline setting: expectation violations fail the release pipeline for severity=error.`;
    }

    if (state.toolId === 'unity-catalog-governance-generator') {
        return `-- Unity Catalog Governance Generator
ALTER TABLE ${table} SET TAGS (
  'owner' = '${state.owner}',
  'data_product' = '${state.table.split('.').pop()}',
  'release_gate' = 'governance_review_required'
);

${state.pii.map((col) => `ALTER TABLE ${table} ALTER COLUMN ${col} SET TAGS ('classification' = 'pii', 'masking_required' = 'true');`).join('\n')}

GRANT SELECT ON TABLE ${table} TO \`${state.owner}\`;
GRANT MODIFY ON TABLE ${table} TO \`data-platform-admins\`;

-- Review before publish:
-- - Owner group exists and is not a personal user.
-- - PII tags match the app exposure.
-- - Grants are group-based and least privilege.
-- - Lineage is visible in Unity Catalog.`;
    }

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

    if (state.pattern === 'dbt') {
        return `-- dbt on Databricks starter: model contract and Unity Catalog handoff
-- models/${state.table.split('.').pop()}.sql
{{ config(
    materialized='incremental',
    file_format='delta',
    incremental_strategy='merge',
    unique_key=${JSON.stringify(state.keys.length ? state.keys : ['business_key'])}
) }}

SELECT *
FROM {{ source('raw', '${state.table.split('.').pop()}') }}
{% if is_incremental() %}
WHERE ${state.freshness} > (SELECT COALESCE(MAX(${state.freshness}), TIMESTAMP '1900-01-01') FROM {{ this }})
{% endif %};

-- Add schema.yml tests for required fields and Unity Catalog tags for sensitive columns.`;
    }

    return `-- Delta Live Tables expectations starter
CREATE OR REFRESH LIVE TABLE ${state.table.replaceAll('.', '_')}_dq
${requiredExpectations}
CONSTRAINT freshness_ok EXPECT (${state.freshness} >= current_timestamp() - INTERVAL 24 HOURS)
AS SELECT * FROM ${table};`;
}

/** @param {{ table: string, keys: string[], required: string[], freshness: string, pii: string[], owner: string, pattern: string, toolId?: string }} state */
export function buildDatabricksNotebook(state) {
    if (state.pattern === 'dbt') {
        return `# Databricks job handoff for dbt
job_name = \"dbt_${state.table.replaceAll('.', '_')}\"
warehouse = \"serverless-sql-warehouse\"
owner_group = \"${state.owner}\"

run_steps = [
    \"dbt deps\",
    \"dbt build --select ${state.table.split('.').pop()}+\",
    \"dbt test --select ${state.table.split('.').pop()}\",
]

display({
    \"job_name\": job_name,
    \"warehouse\": warehouse,
    \"owner_group\": owner_group,
    \"run_steps\": run_steps,
    \"release_gate\": \"dbt tests green, Unity Catalog grants reviewed, freshness SLA accepted\",
})`;
    }

    if (state.pattern === 'governance') {
        return `# Databricks Unity Catalog PII / governance review starter
from pyspark.sql import functions as F

table_name = \"${state.table}\"
owner_group = \"${state.owner}\"
pii_columns = ${JSON.stringify(state.pii)}
freshness_column = \"${state.freshness}\"

df = spark.table(table_name)
summary = {
    \"table\": table_name,
    \"owner_group\": owner_group,
    \"pii_columns\": pii_columns,
    \"row_count\": df.count(),
    \"last_update\": df.agg(F.max(freshness_column)).collect()[0][0],
    \"review_gate\": \"set Unity Catalog tags, validate grants, document export risk\",
}

display(summary)
assert owner_group, \"Owner group required before publishing app model\"`;
    }

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

/** @param {'fabric' | 'databricks'} platform @param {{ table: string, keys: string[], required: string[], freshness: string, pii: string[], owner: string, pattern: string, toolId?: string }} state */
export function buildRunbook(platform, state) {
    const platformLabel = platform === 'fabric' ? 'Fabric' : 'Databricks';
    const patternLabel = {
        dq: 'DQ rules',
        delta: 'Delta load',
        scd2: 'SCD2',
        governance: 'Governance gate',
        pipeline: 'Pipeline checklist',
        semantic: 'Semantic model guardrails',
        dbt: 'dbt on Databricks',
    }[state.pattern] || 'DQ / Governance';

    if (state.toolId === 'fabric-pipeline-checklist-generator') {
        return `# Fabric Pipeline Checklist

## Pipeline
- Target table: ${state.table}
- Owner group: ${state.owner}
- Increment key(s): ${state.keys.join(', ') || 'not set'}
- Freshness column: ${state.freshness}

## Required Gates
- Source increment and watermark defined.
- Retry behavior and idempotency checked.
- Duplicate key gate runs before publish.
- Failed-run alert goes to ${state.owner}.
- Semantic model refresh dependency documented.
- Rollback or reload path written down.

## Evidence To Attach
- Last successful run.
- Row counts before/after load.
- DQ result summary.
- Known data gaps accepted by owner.`;
    }

    if (state.toolId === 'fabric-semantic-model-guardrails') {
        return `# Fabric Semantic Model Guardrails

## Model Ownership
- Semantic source: ${state.table}
- Owner group: ${state.owner}
- KPI/measures owner named before release.

## Guardrails
- Hide technical keys: ${state.keys.join(', ') || 'none listed'}.
- Keep KPI formulas in measures, not duplicated in visuals.
- Review export permissions for sensitive fields: ${state.pii.join(', ') || 'none listed'}.
- Validate RLS/OLS against the app audience.
- Publish only after owner sign-off and refresh SLA check.

## App Release Evidence
- Screenshot/list of visible tables and fields.
- Measure definitions and owner.
- Access groups and export setting.
- Refresh history and fallback owner.`;
    }

    if (state.toolId === 'databricks-dbt-on-databricks-generator') {
        return `# Databricks dbt-on-Databricks Runbook

## dbt Model
- Target relation: ${state.table}
- Unique key: ${state.keys.join(', ') || 'business_key'}
- Incremental filter: ${state.freshness}
- Owner group: ${state.owner}

## Setup Steps
- Configure Databricks SQL Warehouse profile.
- Create source and model YAML entries.
- Add not_null and unique tests for required/key fields.
- Add Unity Catalog tags for sensitive fields: ${state.pii.join(', ') || 'none listed'}.
- Run dbt build in a Databricks job with owner notification.

## Release Gate
- dbt build green.
- Unity Catalog grants reviewed.
- Freshness SLA accepted.
- Downstream app refresh tested.`;
    }

    if (state.toolId === 'unity-catalog-governance-generator') {
        return `# Unity Catalog Governance Review

## Scope
- Table: ${state.table}
- Owner group: ${state.owner}
- Sensitive columns: ${state.pii.join(', ') || 'none listed'}

## Controls
- Table owner tag set.
- PII/classification tags set on columns.
- Grants are group-based and least privilege.
- Lineage visible from source to app model.
- Access review documented before broad app release.

## Evidence
- DESCRIBE EXTENDED output or catalog screenshot.
- Grants list.
- PII column review.
- App audience approval.`;
    }

    if (state.toolId === 'delta-load-scd-pattern-generator') {
        return `# Delta Load / SCD Pattern Runbook

## Load Pattern
- Target table: ${state.table}
- Key columns: ${state.keys.join(', ') || 'not set'}
- Freshness / watermark: ${state.freshness}
- Required fields: ${state.required.join(', ') || 'not set'}

## Decide
- Delta MERGE for current-state facts or snapshots.
- SCD2 when attribute history must be queryable.
- Reject duplicate keys before merge.
- Store row_hash, valid_from, valid_to and is_current for SCD2.

## Release Gate
- Backfill tested.
- Re-run is idempotent.
- Late arriving data behavior documented.
- Downstream semantic model impact reviewed.`;
    }

    return `# ${platformLabel} ${patternLabel} Runbook

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
