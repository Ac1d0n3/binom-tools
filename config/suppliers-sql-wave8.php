<?php

/**
 * Wave 8 warehouse SQL examples — DMS/Content source-native (Landing RAW + Curated).
 * Warehouse-neutral dialect — metadata/ACL/version KPIs only; NEVER file/OCR bodies.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'opentext' => [
        'sqlExamples' => [
            [
                'id' => 'raw-ot-doc-user',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW OpenText Document + User Landing',
                    'en' => 'RAW OpenText document + user landing',
                ],
                'notes' => [
                    'de' => 'Metadata only — keine File Bodies; email = PII in RAW.',
                    'en' => 'Metadata only — no file bodies; email = PII in RAW.',
                ],
                'sql' => '-- Warehouse-neutral RAW from OpenText metadata extract
CREATE TABLE raw_ot_document (
  document_id             VARCHAR,
  name                 VARCHAR,
  mime_type            VARCHAR,
  size_bytes           BIGINT,
  owner_id             VARCHAR,
  folder_id         VARCHAR,
  created_at           TIMESTAMP,
  modified_at          TIMESTAMP,
  status               VARCHAR,
  _loaded_at           TIMESTAMP
  -- no file body / OCR text
);

CREATE TABLE raw_ot_user (
  user_id              VARCHAR,
  login                VARCHAR,
  email                VARCHAR, -- workforce PII
  display_name         VARCHAR,
  active               BOOLEAN,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'raw-ot-version-acl',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Version + ACL Landing',
                    'en' => 'RAW version + ACL landing',
                ],
                'notes' => [
                    'de' => 'Version Meta + ACL Principal ids — keine Binaries.',
                    'en' => 'Version meta + ACL principal ids — no binaries.',
                ],
                'sql' => 'CREATE TABLE raw_ot_version (
  version_id           VARCHAR,
  document_id             VARCHAR,
  version_number       INT,
  size_bytes           BIGINT,
  created_by           VARCHAR,
  created_at           TIMESTAMP,
  _loaded_at           TIMESTAMP
  -- no binary stream
);

CREATE TABLE raw_ot_permission (
  permission_id        VARCHAR,
  document_id             VARCHAR,
  principal_id         VARCHAR,
  role                 VARCHAR,
  inherited            BOOLEAN,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'curated-fct-ot-version',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated fact version',
                    'en' => 'Curated fact version',
                ],
                'notes' => [
                    'de' => 'Version KPI Grain — Parent Pflicht; kein Body.',
                    'en' => 'Version KPI grain — parent required; no body.',
                ],
                'sql' => 'CREATE TABLE curated_fct_ot_version AS
SELECT
  v.version_id,
  v.document_id,
  v.version_number,
  v.size_bytes,
  v.created_by,
  v.created_at
FROM raw_ot_version v
WHERE v.document_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-dims-ot',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated dim document / ACL fact',
                    'en' => 'Curated dim document / ACL fact',
                ],
                'notes' => [
                    'de' => 'Doc Dim ohne Owner-Klartext; ACL als ids.',
                    'en' => 'Doc dim without owner cleartext; ACL as ids.',
                ],
                'sql' => 'CREATE TABLE curated_dim_ot_document AS
SELECT
  document_id,
  mime_type,
  size_bytes,
  owner_id,
  folder_id,
  created_at,
  modified_at,
  status
  -- omit email/display_name joins in default dim
FROM raw_ot_document;

CREATE TABLE curated_fct_ot_permission AS
SELECT
  permission_id,
  document_id,
  principal_id,
  role,
  inherited
FROM raw_ot_permission
WHERE principal_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-measure-ot',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated measure SELECTs',
                    'en' => 'Curated measure SELECTs',
                ],
                'notes' => [
                    'de' => 'Documents created, versions, storage, ACL grants — Periodenfilter anpassen.',
                    'en' => 'Documents created, versions, storage, ACL grants — adapt period filters.',
                ],
                'sql' => '-- Documents created in period
SELECT COUNT(*) AS documents_created
FROM curated_dim_ot_document
WHERE created_at BETWEEN :period_start AND :period_end;

-- Versions count
SELECT COUNT(*) AS versions_count
FROM curated_fct_ot_version
WHERE created_at BETWEEN :period_start AND :period_end;

-- Storage volume (bytes)
SELECT COALESCE(SUM(size_bytes), 0) AS storage_bytes
FROM curated_dim_ot_document
WHERE status = \'active\';

-- ACL grants per document (avg)
SELECT AVG(grant_count) AS avg_acl_grants
FROM (
  SELECT document_id, COUNT(*) AS grant_count
  FROM curated_fct_ot_permission
  GROUP BY document_id
) g;',
            ],
        ],
    ],
    'fabasoft' => [
        'sqlExamples' => [
            [
                'id' => 'raw-faba-doc-user',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Fabasoft Document + User Landing',
                    'en' => 'RAW Fabasoft document + user landing',
                ],
                'notes' => [
                    'de' => 'Metadata only — keine File Bodies; email = PII in RAW.',
                    'en' => 'Metadata only — no file bodies; email = PII in RAW.',
                ],
                'sql' => '-- Warehouse-neutral RAW from Fabasoft metadata extract
CREATE TABLE raw_faba_object (
  object_id             VARCHAR,
  name                 VARCHAR,
  mime_type            VARCHAR,
  size_bytes           BIGINT,
  owner_id             VARCHAR,
  room_id         VARCHAR,
  created_at           TIMESTAMP,
  modified_at          TIMESTAMP,
  status               VARCHAR,
  _loaded_at           TIMESTAMP
  -- no file body / OCR text
);

CREATE TABLE raw_faba_person (
  user_id              VARCHAR,
  login                VARCHAR,
  email                VARCHAR, -- workforce PII
  display_name         VARCHAR,
  active               BOOLEAN,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'raw-faba-version-acl',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Version + ACL Landing',
                    'en' => 'RAW version + ACL landing',
                ],
                'notes' => [
                    'de' => 'Version Meta + ACL Principal ids — keine Binaries.',
                    'en' => 'Version meta + ACL principal ids — no binaries.',
                ],
                'sql' => 'CREATE TABLE raw_faba_content_version (
  version_id           VARCHAR,
  object_id             VARCHAR,
  version_number       INT,
  size_bytes           BIGINT,
  created_by           VARCHAR,
  created_at           TIMESTAMP,
  _loaded_at           TIMESTAMP
  -- no binary stream
);

CREATE TABLE raw_faba_permission (
  permission_id        VARCHAR,
  object_id             VARCHAR,
  principal_id         VARCHAR,
  role                 VARCHAR,
  inherited            BOOLEAN,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'curated-fct-faba-version',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated fact version',
                    'en' => 'Curated fact version',
                ],
                'notes' => [
                    'de' => 'Version KPI Grain — Parent Pflicht; kein Body.',
                    'en' => 'Version KPI grain — parent required; no body.',
                ],
                'sql' => 'CREATE TABLE curated_fct_faba_version AS
SELECT
  v.version_id,
  v.object_id,
  v.version_number,
  v.size_bytes,
  v.created_by,
  v.created_at
FROM raw_faba_content_version v
WHERE v.object_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-dims-faba',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated dim document / ACL fact',
                    'en' => 'Curated dim document / ACL fact',
                ],
                'notes' => [
                    'de' => 'Doc Dim ohne Owner-Klartext; ACL als ids.',
                    'en' => 'Doc dim without owner cleartext; ACL as ids.',
                ],
                'sql' => 'CREATE TABLE curated_dim_faba_object AS
SELECT
  object_id,
  mime_type,
  size_bytes,
  owner_id,
  room_id,
  created_at,
  modified_at,
  status
  -- omit email/display_name joins in default dim
FROM raw_faba_object;

CREATE TABLE curated_fct_faba_permission AS
SELECT
  permission_id,
  object_id,
  principal_id,
  role,
  inherited
FROM raw_faba_permission
WHERE principal_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-measure-faba',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated measure SELECTs',
                    'en' => 'Curated measure SELECTs',
                ],
                'notes' => [
                    'de' => 'Documents created, versions, storage, ACL grants — Periodenfilter anpassen.',
                    'en' => 'Documents created, versions, storage, ACL grants — adapt period filters.',
                ],
                'sql' => '-- Documents created in period
SELECT COUNT(*) AS documents_created
FROM curated_dim_faba_object
WHERE created_at BETWEEN :period_start AND :period_end;

-- Versions count
SELECT COUNT(*) AS versions_count
FROM curated_fct_faba_version
WHERE created_at BETWEEN :period_start AND :period_end;

-- Storage volume (bytes)
SELECT COALESCE(SUM(size_bytes), 0) AS storage_bytes
FROM curated_dim_faba_object
WHERE status = \'active\';

-- ACL grants per document (avg)
SELECT AVG(grant_count) AS avg_acl_grants
FROM (
  SELECT object_id, COUNT(*) AS grant_count
  FROM curated_fct_faba_permission
  GROUP BY object_id
) g;',
            ],
        ],
    ],
    'elo' => [
        'sqlExamples' => [
            [
                'id' => 'raw-elo-doc-user',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW ELO Document + User Landing',
                    'en' => 'RAW ELO document + user landing',
                ],
                'notes' => [
                    'de' => 'Metadata only — keine File Bodies; email = PII in RAW.',
                    'en' => 'Metadata only — no file bodies; email = PII in RAW.',
                ],
                'sql' => '-- Warehouse-neutral RAW from ELO metadata extract
CREATE TABLE raw_elo_sord_document (
  obj_id             VARCHAR,
  name                 VARCHAR,
  mime_type            VARCHAR,
  size_bytes           BIGINT,
  owner_id             VARCHAR,
  sord_folder_id         VARCHAR,
  created_at           TIMESTAMP,
  modified_at          TIMESTAMP,
  status               VARCHAR,
  _loaded_at           TIMESTAMP
  -- no file body / OCR text
);

CREATE TABLE raw_elo_elo_user (
  user_id              VARCHAR,
  login                VARCHAR,
  email                VARCHAR, -- workforce PII
  display_name         VARCHAR,
  active               BOOLEAN,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'raw-elo-version-acl',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Version + ACL Landing',
                    'en' => 'RAW version + ACL landing',
                ],
                'notes' => [
                    'de' => 'Version Meta + ACL Principal ids — keine Binaries.',
                    'en' => 'Version meta + ACL principal ids — no binaries.',
                ],
                'sql' => 'CREATE TABLE raw_elo_sord_version (
  version_id           VARCHAR,
  obj_id             VARCHAR,
  version_number       INT,
  size_bytes           BIGINT,
  created_by           VARCHAR,
  created_at           TIMESTAMP,
  _loaded_at           TIMESTAMP
  -- no binary stream
);

CREATE TABLE raw_elo_permission (
  permission_id        VARCHAR,
  obj_id             VARCHAR,
  principal_id         VARCHAR,
  role                 VARCHAR,
  inherited            BOOLEAN,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'curated-fct-elo-version',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated fact version',
                    'en' => 'Curated fact version',
                ],
                'notes' => [
                    'de' => 'Version KPI Grain — Parent Pflicht; kein Body.',
                    'en' => 'Version KPI grain — parent required; no body.',
                ],
                'sql' => 'CREATE TABLE curated_fct_elo_version AS
SELECT
  v.version_id,
  v.obj_id,
  v.version_number,
  v.size_bytes,
  v.created_by,
  v.created_at
FROM raw_elo_sord_version v
WHERE v.obj_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-dims-elo',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated dim document / ACL fact',
                    'en' => 'Curated dim document / ACL fact',
                ],
                'notes' => [
                    'de' => 'Doc Dim ohne Owner-Klartext; ACL als ids.',
                    'en' => 'Doc dim without owner cleartext; ACL as ids.',
                ],
                'sql' => 'CREATE TABLE curated_dim_elo_sord_document AS
SELECT
  obj_id,
  mime_type,
  size_bytes,
  owner_id,
  sord_folder_id,
  created_at,
  modified_at,
  status
  -- omit email/display_name joins in default dim
FROM raw_elo_sord_document;

CREATE TABLE curated_fct_elo_permission AS
SELECT
  permission_id,
  obj_id,
  principal_id,
  role,
  inherited
FROM raw_elo_permission
WHERE principal_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-measure-elo',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated measure SELECTs',
                    'en' => 'Curated measure SELECTs',
                ],
                'notes' => [
                    'de' => 'Documents created, versions, storage, ACL grants — Periodenfilter anpassen.',
                    'en' => 'Documents created, versions, storage, ACL grants — adapt period filters.',
                ],
                'sql' => '-- Documents created in period
SELECT COUNT(*) AS documents_created
FROM curated_dim_elo_sord_document
WHERE created_at BETWEEN :period_start AND :period_end;

-- Versions count
SELECT COUNT(*) AS versions_count
FROM curated_fct_elo_version
WHERE created_at BETWEEN :period_start AND :period_end;

-- Storage volume (bytes)
SELECT COALESCE(SUM(size_bytes), 0) AS storage_bytes
FROM curated_dim_elo_sord_document
WHERE status = \'active\';

-- ACL grants per document (avg)
SELECT AVG(grant_count) AS avg_acl_grants
FROM (
  SELECT obj_id, COUNT(*) AS grant_count
  FROM curated_fct_elo_permission
  GROUP BY obj_id
) g;',
            ],
        ],
    ],
    'docuware' => [
        'sqlExamples' => [
            [
                'id' => 'raw-dw-doc-user',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW DocuWare Document + User Landing',
                    'en' => 'RAW DocuWare document + user landing',
                ],
                'notes' => [
                    'de' => 'Metadata only — keine File Bodies; email = PII in RAW.',
                    'en' => 'Metadata only — no file bodies; email = PII in RAW.',
                ],
                'sql' => '-- Warehouse-neutral RAW from DocuWare metadata extract
CREATE TABLE raw_dw_document (
  document_id             VARCHAR,
  name                 VARCHAR,
  mime_type            VARCHAR,
  size_bytes           BIGINT,
  owner_id             VARCHAR,
  file_cabinet_id         VARCHAR,
  created_at           TIMESTAMP,
  modified_at          TIMESTAMP,
  status               VARCHAR,
  _loaded_at           TIMESTAMP
  -- no file body / OCR text
);

CREATE TABLE raw_dw_user (
  user_id              VARCHAR,
  login                VARCHAR,
  email                VARCHAR, -- workforce PII
  display_name         VARCHAR,
  active               BOOLEAN,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'raw-dw-version-acl',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Version + ACL Landing',
                    'en' => 'RAW version + ACL landing',
                ],
                'notes' => [
                    'de' => 'Version Meta + ACL Principal ids — keine Binaries.',
                    'en' => 'Version meta + ACL principal ids — no binaries.',
                ],
                'sql' => 'CREATE TABLE raw_dw_revision (
  version_id           VARCHAR,
  document_id             VARCHAR,
  version_number       INT,
  size_bytes           BIGINT,
  created_by           VARCHAR,
  created_at           TIMESTAMP,
  _loaded_at           TIMESTAMP
  -- no binary stream
);

CREATE TABLE raw_dw_permission (
  permission_id        VARCHAR,
  document_id             VARCHAR,
  principal_id         VARCHAR,
  role                 VARCHAR,
  inherited            BOOLEAN,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'curated-fct-dw-version',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated fact version',
                    'en' => 'Curated fact version',
                ],
                'notes' => [
                    'de' => 'Version KPI Grain — Parent Pflicht; kein Body.',
                    'en' => 'Version KPI grain — parent required; no body.',
                ],
                'sql' => 'CREATE TABLE curated_fct_dw_version AS
SELECT
  v.version_id,
  v.document_id,
  v.version_number,
  v.size_bytes,
  v.created_by,
  v.created_at
FROM raw_dw_revision v
WHERE v.document_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-dims-dw',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated dim document / ACL fact',
                    'en' => 'Curated dim document / ACL fact',
                ],
                'notes' => [
                    'de' => 'Doc Dim ohne Owner-Klartext; ACL als ids.',
                    'en' => 'Doc dim without owner cleartext; ACL as ids.',
                ],
                'sql' => 'CREATE TABLE curated_dim_dw_document AS
SELECT
  document_id,
  mime_type,
  size_bytes,
  owner_id,
  file_cabinet_id,
  created_at,
  modified_at,
  status
  -- omit email/display_name joins in default dim
FROM raw_dw_document;

CREATE TABLE curated_fct_dw_permission AS
SELECT
  permission_id,
  document_id,
  principal_id,
  role,
  inherited
FROM raw_dw_permission
WHERE principal_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-measure-dw',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated measure SELECTs',
                    'en' => 'Curated measure SELECTs',
                ],
                'notes' => [
                    'de' => 'Documents created, versions, storage, ACL grants — Periodenfilter anpassen.',
                    'en' => 'Documents created, versions, storage, ACL grants — adapt period filters.',
                ],
                'sql' => '-- Documents created in period
SELECT COUNT(*) AS documents_created
FROM curated_dim_dw_document
WHERE created_at BETWEEN :period_start AND :period_end;

-- Versions count
SELECT COUNT(*) AS versions_count
FROM curated_fct_dw_version
WHERE created_at BETWEEN :period_start AND :period_end;

-- Storage volume (bytes)
SELECT COALESCE(SUM(size_bytes), 0) AS storage_bytes
FROM curated_dim_dw_document
WHERE status = \'active\';

-- ACL grants per document (avg)
SELECT AVG(grant_count) AS avg_acl_grants
FROM (
  SELECT document_id, COUNT(*) AS grant_count
  FROM curated_fct_dw_permission
  GROUP BY document_id
) g;',
            ],
        ],
    ],
    'box' => [
        'sqlExamples' => [
            [
                'id' => 'raw-box-doc-user',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Box Document + User Landing',
                    'en' => 'RAW Box document + user landing',
                ],
                'notes' => [
                    'de' => 'Metadata only — keine File Bodies; email = PII in RAW.',
                    'en' => 'Metadata only — no file bodies; email = PII in RAW.',
                ],
                'sql' => '-- Warehouse-neutral RAW from Box metadata extract
CREATE TABLE raw_box_file (
  file_id             VARCHAR,
  name                 VARCHAR,
  mime_type            VARCHAR,
  size_bytes           BIGINT,
  owner_id             VARCHAR,
  folder_id         VARCHAR,
  created_at           TIMESTAMP,
  modified_at          TIMESTAMP,
  status               VARCHAR,
  _loaded_at           TIMESTAMP
  -- no file body / OCR text
);

CREATE TABLE raw_box_user (
  user_id              VARCHAR,
  login                VARCHAR,
  email                VARCHAR, -- workforce PII
  display_name         VARCHAR,
  active               BOOLEAN,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'raw-box-version-acl',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Version + ACL Landing',
                    'en' => 'RAW version + ACL landing',
                ],
                'notes' => [
                    'de' => 'Version Meta + ACL Principal ids — keine Binaries.',
                    'en' => 'Version meta + ACL principal ids — no binaries.',
                ],
                'sql' => 'CREATE TABLE raw_box_file_version (
  version_id           VARCHAR,
  file_id             VARCHAR,
  version_number       INT,
  size_bytes           BIGINT,
  created_by           VARCHAR,
  created_at           TIMESTAMP,
  _loaded_at           TIMESTAMP
  -- no binary stream
);

CREATE TABLE raw_box_permission (
  permission_id        VARCHAR,
  file_id             VARCHAR,
  principal_id         VARCHAR,
  role                 VARCHAR,
  inherited            BOOLEAN,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'curated-fct-box-version',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated fact version',
                    'en' => 'Curated fact version',
                ],
                'notes' => [
                    'de' => 'Version KPI Grain — Parent Pflicht; kein Body.',
                    'en' => 'Version KPI grain — parent required; no body.',
                ],
                'sql' => 'CREATE TABLE curated_fct_box_version AS
SELECT
  v.version_id,
  v.file_id,
  v.version_number,
  v.size_bytes,
  v.created_by,
  v.created_at
FROM raw_box_file_version v
WHERE v.file_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-dims-box',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated dim document / ACL fact',
                    'en' => 'Curated dim document / ACL fact',
                ],
                'notes' => [
                    'de' => 'Doc Dim ohne Owner-Klartext; ACL als ids.',
                    'en' => 'Doc dim without owner cleartext; ACL as ids.',
                ],
                'sql' => 'CREATE TABLE curated_dim_box_file AS
SELECT
  file_id,
  mime_type,
  size_bytes,
  owner_id,
  folder_id,
  created_at,
  modified_at,
  status
  -- omit email/display_name joins in default dim
FROM raw_box_file;

CREATE TABLE curated_fct_box_permission AS
SELECT
  permission_id,
  file_id,
  principal_id,
  role,
  inherited
FROM raw_box_permission
WHERE principal_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-measure-box',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated measure SELECTs',
                    'en' => 'Curated measure SELECTs',
                ],
                'notes' => [
                    'de' => 'Documents created, versions, storage, ACL grants — Periodenfilter anpassen.',
                    'en' => 'Documents created, versions, storage, ACL grants — adapt period filters.',
                ],
                'sql' => '-- Documents created in period
SELECT COUNT(*) AS documents_created
FROM curated_dim_box_file
WHERE created_at BETWEEN :period_start AND :period_end;

-- Versions count
SELECT COUNT(*) AS versions_count
FROM curated_fct_box_version
WHERE created_at BETWEEN :period_start AND :period_end;

-- Storage volume (bytes)
SELECT COALESCE(SUM(size_bytes), 0) AS storage_bytes
FROM curated_dim_box_file
WHERE status = \'active\';

-- ACL grants per document (avg)
SELECT AVG(grant_count) AS avg_acl_grants
FROM (
  SELECT file_id, COUNT(*) AS grant_count
  FROM curated_fct_box_permission
  GROUP BY file_id
) g;',
            ],
        ],
    ],
];
