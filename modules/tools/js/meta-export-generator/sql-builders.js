/**
 * Copy-paste catalog/meta export recipes per platform (no live DB connect).
 * @typedef {'cloud'|'relational'|'document'} PlatformGroup
 * @typedef {'schemas'|'tables'|'columns'|'access'} SectionId
 * @typedef {{ id: string, label: string, group: PlatformGroup, notes?: string }} PlatformDef
 * @typedef {{ id: SectionId, query: string }} SectionQuery
 */

/** @type {PlatformDef[]} */
export const platforms = [
    { id: 'snowflake', label: 'Snowflake', group: 'cloud' },
    { id: 'bigquery', label: 'BigQuery', group: 'cloud' },
    { id: 'redshift', label: 'Redshift', group: 'cloud' },
    { id: 'databricks', label: 'Databricks', group: 'cloud' },
    { id: 'postgres', label: 'Postgres', group: 'cloud' },
    { id: 'fabric', label: 'Microsoft Fabric', group: 'cloud', notes: 'SQL analytics endpoint / INFORMATION_SCHEMA (T-SQL-like).' },
    { id: 'mysql', label: 'MySQL', group: 'relational', notes: 'Also works for MariaDB with minor differences.' },
    { id: 'oracle', label: 'Oracle', group: 'relational' },
    { id: 'sqlserver', label: 'SQL Server', group: 'relational' },
    { id: 'mongodb', label: 'MongoDB', group: 'document', notes: 'mongosh / driver scripts — not SQL.' },
];

/** @type {string[]} */
export const platformIds = platforms.map((p) => p.id);

/** @param {string} id */
export function normalizePlatformId(id) {
    return platformIds.includes(id) ? id : 'snowflake';
}

/** @param {string} id @returns {PlatformDef} */
export function getPlatform(id) {
    const normalized = normalizePlatformId(id);
    return platforms.find((p) => p.id === normalized) || platforms[0];
}

/** @returns {{ id: PlatformGroup, labelKey: string, platforms: PlatformDef[] }[]} */
export function groupedPlatforms() {
    /** @type {Record<PlatformGroup, PlatformDef[]>} */
    const buckets = { cloud: [], relational: [], document: [] };
    for (const p of platforms) {
        buckets[p.group].push(p);
    }
    return [
        { id: 'cloud', labelKey: 'metaExport.group.cloud', platforms: buckets.cloud },
        { id: 'relational', labelKey: 'metaExport.group.relational', platforms: buckets.relational },
        { id: 'document', labelKey: 'metaExport.group.document', platforms: buckets.document },
    ];
}

/** @type {Record<string, Record<SectionId, string>>} */
const queries = {
    snowflake: {
        schemas: `-- Schemas in the current database (set DATABASE / USE DATABASE first)
SELECT
  catalog_name AS database_name,
  schema_name,
  schema_owner
FROM information_schema.schemata
ORDER BY 1, 2;`,
        tables: `-- Tables and views
SELECT
  table_catalog AS database_name,
  table_schema,
  table_name,
  table_type
FROM information_schema.tables
WHERE table_schema NOT IN ('INFORMATION_SCHEMA')
ORDER BY 1, 2, 3;`,
        columns: `-- Columns
SELECT
  table_catalog AS database_name,
  table_schema,
  table_name,
  column_name,
  data_type,
  is_nullable,
  ordinal_position
FROM information_schema.columns
WHERE table_schema NOT IN ('INFORMATION_SCHEMA')
ORDER BY 1, 2, 3, ordinal_position;`,
        access: `-- Grants (requires ACCOUNT_USAGE privilege; adjust lookback if needed)
SELECT
  grantee_name,
  granted_on,
  table_catalog || '.' || table_schema || '.' || name AS object_name,
  privilege,
  granted_to,
  grant_option
FROM snowflake.account_usage.grants_to_roles
WHERE deleted_on IS NULL
ORDER BY grantee_name, object_name
LIMIT 10000;`,
    },
    bigquery: {
        schemas: `-- Datasets (schemas) in the current project
SELECT
  catalog_name AS project_id,
  schema_name AS dataset_name,
  location
FROM \`region-eu\`.INFORMATION_SCHEMA.SCHEMATA
-- Replace region-eu with your region (e.g. region-us)
ORDER BY 1, 2;`,
        tables: `SELECT
  table_catalog AS project_id,
  table_schema AS dataset_name,
  table_name,
  table_type
FROM \`region-eu\`.INFORMATION_SCHEMA.TABLES
ORDER BY 1, 2, 3;`,
        columns: `SELECT
  table_catalog AS project_id,
  table_schema AS dataset_name,
  table_name,
  column_name,
  data_type,
  is_nullable,
  ordinal_position
FROM \`region-eu\`.INFORMATION_SCHEMA.COLUMNS
ORDER BY 1, 2, 3, ordinal_position;`,
        access: `-- Dataset-level IAM is in the Cloud Console / Resource Manager.
-- Table ACL (legacy) sample via INFORMATION_SCHEMA if enabled:
SELECT *
FROM \`region-eu\`.INFORMATION_SCHEMA.TABLE_OPTIONS
WHERE option_name = 'access'
LIMIT 1000;`,
    },
    redshift: {
        schemas: `SELECT
  nspname AS schema_name,
  pg_get_userbyid(nspowner) AS schema_owner
FROM pg_namespace
WHERE nspname NOT LIKE 'pg_%'
  AND nspname <> 'information_schema'
ORDER BY 1;`,
        tables: `SELECT
  table_schema,
  table_name,
  table_type
FROM information_schema.tables
WHERE table_schema NOT IN ('pg_catalog', 'information_schema')
ORDER BY 1, 2;`,
        columns: `SELECT
  table_schema,
  table_name,
  column_name,
  data_type,
  is_nullable,
  ordinal_position
FROM information_schema.columns
WHERE table_schema NOT IN ('pg_catalog', 'information_schema')
ORDER BY 1, 2, ordinal_position;`,
        access: `SELECT
  schemaname,
  objectname,
  usename,
  privilege_type
FROM (
  SELECT n.nspname AS schemaname, c.relname AS objectname,
         u.usename, p.privilege_type
  FROM pg_class c
  JOIN pg_namespace n ON n.oid = c.relnamespace
  CROSS JOIN pg_user u
  JOIN (
    SELECT 'SELECT' AS privilege_type UNION ALL
    SELECT 'INSERT' UNION ALL SELECT 'UPDATE' UNION ALL SELECT 'DELETE'
  ) p ON has_table_privilege(u.usename, c.oid, p.privilege_type)
  WHERE n.nspname NOT IN ('pg_catalog', 'information_schema')
) g
ORDER BY 1, 2, 3
LIMIT 10000;`,
    },
    databricks: {
        schemas: `-- Unity Catalog / hive_metastore
SHOW SCHEMAS;
-- Or:
SELECT catalog_name, schema_name
FROM system.information_schema.schemata
ORDER BY 1, 2;`,
        tables: `SELECT
  table_catalog,
  table_schema,
  table_name,
  table_type
FROM system.information_schema.tables
ORDER BY 1, 2, 3;`,
        columns: `SELECT
  table_catalog,
  table_schema,
  table_name,
  column_name,
  data_type,
  is_nullable,
  ordinal_position
FROM system.information_schema.columns
ORDER BY 1, 2, 3, ordinal_position;`,
        access: `-- Privileges (Unity Catalog)
SELECT
  privilege_type,
  securable_type,
  securable_full_name AS object_name,
  grantee,
  grantor
FROM system.information_schema.table_privileges
ORDER BY object_name, grantee
LIMIT 10000;`,
    },
    postgres: {
        schemas: `SELECT
  schema_name,
  schema_owner
FROM information_schema.schemata
WHERE schema_name NOT IN ('pg_catalog', 'information_schema', 'pg_toast')
ORDER BY 1;`,
        tables: `SELECT
  table_schema,
  table_name,
  table_type
FROM information_schema.tables
WHERE table_schema NOT IN ('pg_catalog', 'information_schema')
ORDER BY 1, 2;`,
        columns: `SELECT
  table_schema,
  table_name,
  column_name,
  data_type,
  is_nullable,
  ordinal_position
FROM information_schema.columns
WHERE table_schema NOT IN ('pg_catalog', 'information_schema')
ORDER BY 1, 2, ordinal_position;`,
        access: `SELECT
  grantee,
  table_schema,
  table_name,
  privilege_type,
  is_grantable
FROM information_schema.role_table_grants
WHERE table_schema NOT IN ('pg_catalog', 'information_schema')
ORDER BY 1, 2, 3;`,
    },
    fabric: {
        schemas: `-- Fabric SQL analytics endpoint / Warehouse
SELECT
  TABLE_CATALOG AS database_name,
  SCHEMA_NAME AS schema_name
FROM INFORMATION_SCHEMA.SCHEMATA
ORDER BY 1, 2;`,
        tables: `SELECT
  TABLE_CATALOG AS database_name,
  TABLE_SCHEMA,
  TABLE_NAME,
  TABLE_TYPE
FROM INFORMATION_SCHEMA.TABLES
ORDER BY 1, 2, 3;`,
        columns: `SELECT
  TABLE_CATALOG AS database_name,
  TABLE_SCHEMA,
  TABLE_NAME,
  COLUMN_NAME,
  DATA_TYPE,
  IS_NULLABLE,
  ORDINAL_POSITION
FROM INFORMATION_SCHEMA.COLUMNS
ORDER BY 1, 2, 3, ORDINAL_POSITION;`,
        access: `-- Workspace / lakehouse roles live in Fabric admin UI.
-- SQL grants (when available on the warehouse):
SELECT
  dp.name AS principal_name,
  dp.type_desc AS principal_type,
  o.name AS object_name,
  p.permission_name,
  p.state_desc
FROM sys.database_permissions p
JOIN sys.database_principals dp ON p.grantee_principal_id = dp.principal_id
LEFT JOIN sys.objects o ON p.major_id = o.object_id
ORDER BY principal_name, object_name;`,
    },
    mysql: {
        schemas: `-- Databases (schemas). MariaDB: same query.
SELECT
  schema_name AS database_name,
  default_character_set_name
FROM information_schema.schemata
WHERE schema_name NOT IN ('mysql', 'information_schema', 'performance_schema', 'sys')
ORDER BY 1;`,
        tables: `SELECT
  table_schema AS database_name,
  table_name,
  table_type,
  engine
FROM information_schema.tables
WHERE table_schema NOT IN ('mysql', 'information_schema', 'performance_schema', 'sys')
ORDER BY 1, 2;`,
        columns: `SELECT
  table_schema AS database_name,
  table_name,
  column_name,
  column_type AS data_type,
  is_nullable,
  ordinal_position
FROM information_schema.columns
WHERE table_schema NOT IN ('mysql', 'information_schema', 'performance_schema', 'sys')
ORDER BY 1, 2, ordinal_position;`,
        access: `SELECT
  grantee,
  table_schema,
  table_name,
  privilege_type,
  is_grantable
FROM information_schema.schema_privileges
UNION ALL
SELECT
  grantee,
  table_schema,
  table_name,
  privilege_type,
  is_grantable
FROM information_schema.table_privileges
ORDER BY 1, 2, 3;`,
    },
    oracle: {
        schemas: `SELECT
  username AS schema_name,
  account_status,
  created
FROM all_users
ORDER BY 1;`,
        tables: `SELECT
  owner AS schema_name,
  table_name,
  'TABLE' AS object_type
FROM all_tables
UNION ALL
SELECT
  owner,
  view_name,
  'VIEW'
FROM all_views
ORDER BY 1, 2;`,
        columns: `SELECT
  owner AS schema_name,
  table_name,
  column_name,
  data_type,
  nullable AS is_nullable,
  column_id AS ordinal_position
FROM all_tab_columns
ORDER BY 1, 2, column_id;`,
        access: `SELECT
  grantee,
  owner AS schema_name,
  table_name,
  privilege,
  grantable
FROM all_tab_privs
ORDER BY 1, 2, 3;`,
    },
    sqlserver: {
        schemas: `SELECT
  s.name AS schema_name,
  u.name AS schema_owner
FROM sys.schemas s
JOIN sys.database_principals u ON s.principal_id = u.principal_id
WHERE s.name NOT IN ('sys', 'INFORMATION_SCHEMA', 'guest')
ORDER BY 1;`,
        tables: `SELECT
  TABLE_CATALOG AS database_name,
  TABLE_SCHEMA,
  TABLE_NAME,
  TABLE_TYPE
FROM INFORMATION_SCHEMA.TABLES
ORDER BY 1, 2, 3;`,
        columns: `SELECT
  TABLE_CATALOG AS database_name,
  TABLE_SCHEMA,
  TABLE_NAME,
  COLUMN_NAME,
  DATA_TYPE,
  IS_NULLABLE,
  ORDINAL_POSITION
FROM INFORMATION_SCHEMA.COLUMNS
ORDER BY 1, 2, 3, ORDINAL_POSITION;`,
        access: `SELECT
  dp.name AS principal_name,
  dp.type_desc AS principal_type,
  o.name AS object_name,
  p.permission_name,
  p.state_desc
FROM sys.database_permissions p
JOIN sys.database_principals dp ON p.grantee_principal_id = dp.principal_id
LEFT JOIN sys.objects o ON p.major_id = o.object_id
WHERE dp.name NOT IN ('public')
ORDER BY principal_name, object_name;`,
    },
    mongodb: {
        schemas: `// Databases (mongosh)
db.adminCommand({ listDatabases: 1 })`,
        tables: `// Collections in current DB
db.getCollectionNames()
// Or with types:
db.runCommand({ listCollections: 1 })`,
        columns: `// Infer fields from a sample (adjust collection + limit)
const coll = 'your_collection';
db[coll].aggregate([
  { $sample: { size: 100 } },
  { $project: { fields: { $objectToArray: '$$ROOT' } } },
  { $unwind: '$fields' },
  { $group: {
      _id: '$fields.k',
      types: { $addToSet: { $type: '$fields.v' } },
      count: { $sum: 1 }
  }},
  { $sort: { _id: 1 } }
])`,
        access: `// Users & roles (requires privileges on admin)
db.getUsers()
db.getRoles({ showPrivileges: true })`,
    },
};

/** @type {SectionId[]} */
export const sectionIds = ['schemas', 'tables', 'columns', 'access'];

/**
 * @param {string} platformId
 * @returns {SectionQuery[]}
 */
export function buildSections(platformId) {
    const id = normalizePlatformId(platformId);
    const map = queries[id] || queries.snowflake;
    return sectionIds.map((sectionId) => ({
        id: sectionId,
        query: map[sectionId],
    }));
}

/**
 * @param {string} platformId
 * @param {SectionId} sectionId
 */
export function getSectionQuery(platformId, sectionId) {
    const sections = buildSections(platformId);
    return sections.find((s) => s.id === sectionId)?.query || '';
}
