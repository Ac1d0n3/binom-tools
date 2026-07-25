<?php

/**
 * Source-native warehouse SQL examples (Landing RAW + Curated) for Supplier Library.
 * Warehouse-neutral dialect — not vendor SOQL/API DDL.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */
return [

    'salesforce' => [
        'sqlExamples' => [
            [
                'id' => 'raw-opportunity',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Opportunity Landing', 'en' => 'RAW opportunity landing'],
                'notes' => [
                    'de' => 'Source-shaped Landing — PII-Spalten taggen, Zugriff einschränken. Kein SOQL.',
                    'en' => 'Source-shaped landing — tag PII columns, restrict access. Not SOQL.',
                ],
                'sql' => "-- Warehouse-neutral RAW example (adapt dialect)\nCREATE TABLE raw_opportunity (\n  opportunity_id   VARCHAR,\n  account_id       VARCHAR,\n  owner_id         VARCHAR,\n  amount           DECIMAL(18,2),\n  currency_code    VARCHAR,\n  stage_name       VARCHAR,\n  is_won           BOOLEAN,\n  is_closed        BOOLEAN,\n  probability      DECIMAL(5,2),\n  close_date       DATE,\n  created_at       TIMESTAMP,\n  modified_at      TIMESTAMP,\n  is_deleted       BOOLEAN,\n  _loaded_at       TIMESTAMP\n);\n-- Load 1:1 from source extract; do not drop PII policy tags here.",
            ],
            [
                'id' => 'raw-account-user',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Account + User Landing', 'en' => 'RAW account + user landing'],
                'notes' => [
                    'de' => 'Account-Adressen und User-Email sind PII/Workforce — getrennte Grants.',
                    'en' => 'Account addresses and user email are PII/workforce — separate grants.',
                ],
                'sql' => "CREATE TABLE raw_account (\n  account_id VARCHAR,\n  name VARCHAR,\n  billing_country VARCHAR,\n  billing_postal_code VARCHAR, -- quasi/direct PII\n  owner_id VARCHAR,\n  created_at TIMESTAMP,\n  modified_at TIMESTAMP,\n  is_deleted BOOLEAN,\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_user (\n  user_id VARCHAR,\n  name VARCHAR,\n  email VARCHAR, -- workforce PII\n  is_active BOOLEAN,\n  _loaded_at TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-opportunity',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact opportunity', 'en' => 'Curated fact opportunity'],
                'notes' => [
                    'de' => 'Conformed Fact — keine Klartext-PII aus Contact; nur Keys + Measures.',
                    'en' => 'Conformed fact — no cleartext contact PII; keys + measures only.',
                ],
                'sql' => "CREATE TABLE curated_fct_opportunity AS\nSELECT\n  o.opportunity_id,\n  o.account_id,\n  o.owner_id,\n  o.amount,\n  o.currency_code,\n  o.stage_name,\n  o.is_won,\n  o.is_closed,\n  o.probability,\n  o.close_date,\n  o.created_at,\n  (o.amount * o.probability / 100.0) AS weighted_amount\nFROM raw_opportunity o\nWHERE COALESCE(o.is_deleted, FALSE) = FALSE;\n-- Join dims in marts; keep RAW grants tighter than curated.",
            ],
            [
                'id' => 'curated-dims',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim account / owner', 'en' => 'Curated dim account / owner'],
                'notes' => [
                    'de' => 'Dimensionen ohne unnötige Adress-Klartexte; Owner ohne Email in Curated-Marts.',
                    'en' => 'Dims without unnecessary address cleartext; owner without email in curated marts.',
                ],
                'sql' => "CREATE TABLE curated_dim_account AS\nSELECT\n  account_id,\n  name,\n  billing_country AS region,\n  owner_id\n  -- omit billing_postal_code from default curated marts\nFROM raw_account\nWHERE COALESCE(is_deleted, FALSE) = FALSE;\n\nCREATE TABLE curated_dim_owner AS\nSELECT\n  user_id AS owner_id,\n  name AS owner_name\n  -- omit email from default analytics dims\nFROM raw_user\nWHERE is_active = TRUE;",
            ],
            [
                'id' => 'curated-measure-examples',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Beispiel-Berechnungen — kopieren und Periodenfilter anpassen.',
                    'en' => 'Example calculations — copy and adapt period filters.',
                ],
                'sql' => "-- Won revenue in period\nSELECT SUM(amount) AS won_revenue\nFROM curated_fct_opportunity\nWHERE is_won = TRUE\n  AND close_date BETWEEN :period_start AND :period_end;\n\n-- Customers with won opp\nSELECT COUNT(DISTINCT account_id) AS customers_with_won\nFROM curated_fct_opportunity\nWHERE is_won = TRUE\n  AND close_date BETWEEN :period_start AND :period_end;\n\n-- Won revenue by owner\nSELECT owner_id, SUM(amount) AS won_revenue\nFROM curated_fct_opportunity\nWHERE is_won = TRUE\n  AND close_date BETWEEN :period_start AND :period_end\nGROUP BY owner_id;",
            ],
        ],
    ],

    'hubspot' => [
        'sqlExamples' => [
            [
                'id' => 'raw-deals',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Deals Landing', 'en' => 'RAW deals landing'],
                'notes' => [
                    'de' => 'HubSpot Deal-Properties 1:1 — hs_is_closed_won / amount / pipeline / dealstage.',
                    'en' => 'HubSpot deal properties 1:1 — hs_is_closed_won / amount / pipeline / dealstage.',
                ],
                'sql' => "-- Warehouse-neutral RAW from HubSpot CRM extract (not HubSpot SQL)\nCREATE TABLE raw_hubspot_deal (\n  deal_id            VARCHAR,\n  dealname           VARCHAR,\n  amount             DECIMAL(18,2),\n  deal_currency_code VARCHAR,\n  pipeline           VARCHAR,\n  dealstage          VARCHAR,\n  hs_is_closed       BOOLEAN,\n  hs_is_closed_won   BOOLEAN,\n  closedate          TIMESTAMP,\n  createdate         TIMESTAMP,\n  hs_lastmodifieddate TIMESTAMP,\n  hubspot_owner_id   VARCHAR,\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-contacts-companies',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Contacts + Companies + Associations', 'en' => 'RAW contacts + companies + associations'],
                'notes' => [
                    'de' => 'Associations sind eigene Objekte — Deal↔Contact/Company nicht aus Deal-Properties raten.',
                    'en' => 'Associations are separate objects — do not guess deal↔contact/company from deal properties.',
                ],
                'sql' => "CREATE TABLE raw_hubspot_contact (\n  contact_id VARCHAR,\n  email VARCHAR, -- direct PII\n  firstname VARCHAR,\n  lastname VARCHAR,\n  phone VARCHAR,\n  createdate TIMESTAMP,\n  hs_lastmodifieddate TIMESTAMP,\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_hubspot_company (\n  company_id VARCHAR,\n  name VARCHAR,\n  domain VARCHAR,\n  city VARCHAR,\n  zip VARCHAR, -- quasi PII\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_hubspot_association_deal_contact (\n  deal_id VARCHAR,\n  contact_id VARCHAR,\n  association_type VARCHAR,\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_hubspot_association_deal_company (\n  deal_id VARCHAR,\n  company_id VARCHAR,\n  association_type VARCHAR,\n  _loaded_at TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-deal',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact deal', 'en' => 'Curated fact deal'],
                'notes' => [
                    'de' => 'hs_is_closed_won statt Stage-Label-Parsing; Amount nur wenn closed-won sinnvoll.',
                    'en' => 'Use hs_is_closed_won instead of parsing stage labels; amount meaningful when closed-won.',
                ],
                'sql' => "CREATE TABLE curated_fct_hubspot_deal AS\nSELECT\n  d.deal_id,\n  d.amount,\n  d.deal_currency_code AS currency_code,\n  d.pipeline,\n  d.dealstage,\n  d.hs_is_closed,\n  d.hs_is_closed_won,\n  d.closedate,\n  d.createdate,\n  d.hubspot_owner_id AS owner_id,\n  ac.company_id,\n  ct.contact_id AS primary_contact_id\nFROM raw_hubspot_deal d\nLEFT JOIN raw_hubspot_association_deal_company ac ON ac.deal_id = d.deal_id\nLEFT JOIN raw_hubspot_association_deal_contact ct ON ct.deal_id = d.deal_id;",
            ],
            [
                'id' => 'curated-dims-hubspot',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim company / contact', 'en' => 'Curated dim company / contact'],
                'notes' => [
                    'de' => 'Contact-Email nicht in Default-Marts; Company eher Domain/Region.',
                    'en' => 'Omit contact email from default marts; company prefers domain/region.',
                ],
                'sql' => "CREATE TABLE curated_dim_hubspot_company AS\nSELECT\n  company_id,\n  name,\n  domain\n  -- omit zip from default curated marts\nFROM raw_hubspot_company;\n\nCREATE TABLE curated_dim_hubspot_contact AS\nSELECT\n  contact_id,\n  firstname,\n  lastname\n  -- omit email/phone from default analytics dims\nFROM raw_hubspot_contact;",
            ],
            [
                'id' => 'curated-measure-hubspot',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Closed-won Amount und Win-Rate über hs_is_closed_won / hs_is_closed.',
                    'en' => 'Closed-won amount and win rate via hs_is_closed_won / hs_is_closed.',
                ],
                'sql' => "-- Closed-won amount in period\nSELECT SUM(amount) AS closed_won_amount\nFROM curated_fct_hubspot_deal\nWHERE hs_is_closed_won = TRUE\n  AND closedate BETWEEN :period_start AND :period_end;\n\n-- Open pipeline\nSELECT SUM(amount) AS open_pipeline_amount\nFROM curated_fct_hubspot_deal\nWHERE hs_is_closed = FALSE;\n\n-- Win rate (closed deals)\nSELECT\n  SUM(CASE WHEN hs_is_closed_won THEN 1 ELSE 0 END) * 1.0\n    / NULLIF(SUM(CASE WHEN hs_is_closed THEN 1 ELSE 0 END), 0) AS win_rate\nFROM curated_fct_hubspot_deal\nWHERE hs_is_closed = TRUE\n  AND closedate BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'ga4' => [
        'sqlExamples' => [
            [
                'id' => 'raw-ga4-events',
                'stage' => 'raw',
                'title' => ['de' => 'RAW GA4 events Landing', 'en' => 'RAW GA4 events landing'],
                'notes' => [
                    'de' => 'BigQuery-Export-Shape vereinfacht — event_params als Key/Value, nicht als flache Spalten raten.',
                    'en' => 'Simplified BigQuery export shape — event_params as key/value, do not invent flat columns.',
                ],
                'sql' => "-- Warehouse-neutral RAW inspired by GA4 BQ export (adapt UNNEST per dialect)\nCREATE TABLE raw_ga4_event (\n  event_date        DATE,\n  event_timestamp   BIGINT,\n  event_name        VARCHAR,\n  user_pseudo_id    VARCHAR,\n  user_id           VARCHAR, -- may be empty; consent-aware\n  ga_session_id     BIGINT,\n  device_category   VARCHAR,\n  geo_country       VARCHAR,\n  traffic_source    VARCHAR,\n  traffic_medium    VARCHAR,\n  _loaded_at        TIMESTAMP\n);\n\nCREATE TABLE raw_ga4_event_param (\n  event_date      DATE,\n  event_timestamp BIGINT,\n  user_pseudo_id  VARCHAR,\n  event_name      VARCHAR,\n  param_key       VARCHAR,\n  param_string    VARCHAR,\n  param_int       BIGINT,\n  param_float     DOUBLE,\n  _loaded_at      TIMESTAMP\n);\n-- Never land email/phone inside param_string without PII controls.",
            ],
            [
                'id' => 'raw-ga4-items',
                'stage' => 'raw',
                'title' => ['de' => 'RAW GA4 items (ecommerce)', 'en' => 'RAW GA4 items (ecommerce)'],
                'notes' => [
                    'de' => 'Item-Arrays aus purchase/add_to_cart — eigene Grain item × event.',
                    'en' => 'Item arrays from purchase/add_to_cart — grain item × event.',
                ],
                'sql' => "CREATE TABLE raw_ga4_item (\n  event_date      DATE,\n  event_timestamp BIGINT,\n  user_pseudo_id  VARCHAR,\n  event_name      VARCHAR,\n  item_id         VARCHAR,\n  item_name       VARCHAR,\n  item_category   VARCHAR,\n  item_brand      VARCHAR,\n  price           DECIMAL(18,2),\n  quantity        BIGINT,\n  item_revenue    DECIMAL(18,2),\n  _loaded_at      TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-ga4-session',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact session / event', 'en' => 'Curated fact session / event'],
                'notes' => [
                    'de' => 'Session-Key = user_pseudo_id + ga_session_id; Purchase-Revenue aus Params/Items.',
                    'en' => 'Session key = user_pseudo_id + ga_session_id; purchase revenue from params/items.',
                ],
                'sql' => "CREATE TABLE curated_fct_ga4_event AS\nSELECT\n  e.event_date,\n  e.event_timestamp,\n  e.event_name,\n  e.user_pseudo_id,\n  e.user_id,\n  e.ga_session_id,\n  CONCAT(e.user_pseudo_id, '-', CAST(e.ga_session_id AS VARCHAR)) AS session_key,\n  e.device_category,\n  e.geo_country,\n  e.traffic_source,\n  e.traffic_medium,\n  MAX(CASE WHEN p.param_key = 'value' THEN COALESCE(p.param_float, CAST(p.param_int AS DOUBLE)) END) AS event_value,\n  MAX(CASE WHEN p.param_key = 'currency' THEN p.param_string END) AS currency_code\nFROM raw_ga4_event e\nLEFT JOIN raw_ga4_event_param p\n  ON p.event_timestamp = e.event_timestamp\n AND p.user_pseudo_id = e.user_pseudo_id\n AND p.event_name = e.event_name\nGROUP BY\n  e.event_date, e.event_timestamp, e.event_name, e.user_pseudo_id, e.user_id,\n  e.ga_session_id, e.device_category, e.geo_country, e.traffic_source, e.traffic_medium;",
            ],
            [
                'id' => 'curated-fct-ga4-purchase',
                'stage' => 'curated',
                'title' => ['de' => 'Curated purchase / item fact', 'en' => 'Curated purchase / item fact'],
                'notes' => [
                    'de' => 'Nur event_name = purchase (oder in_app_purchase) für GMV-ähnliche Measures.',
                    'en' => 'Restrict to event_name = purchase (or in_app_purchase) for GMV-like measures.',
                ],
                'sql' => "CREATE TABLE curated_fct_ga4_purchase_item AS\nSELECT\n  i.event_date,\n  i.event_timestamp,\n  i.user_pseudo_id,\n  i.item_id,\n  i.item_name,\n  i.item_category,\n  i.price,\n  i.quantity,\n  COALESCE(i.item_revenue, i.price * i.quantity) AS item_revenue\nFROM raw_ga4_item i\nWHERE i.event_name = 'purchase';",
            ],
            [
                'id' => 'curated-measure-ga4',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Sessions, Purchase-Revenue, Conversion-Rate — consent/PII in Params prüfen.',
                    'en' => 'Sessions, purchase revenue, conversion rate — check consent/PII in params.',
                ],
                'sql' => "-- Sessions\nSELECT COUNT(DISTINCT session_key) AS sessions\nFROM curated_fct_ga4_event\nWHERE event_date BETWEEN :period_start AND :period_end;\n\n-- Purchase revenue\nSELECT SUM(item_revenue) AS purchase_revenue\nFROM curated_fct_ga4_purchase_item\nWHERE event_date BETWEEN :period_start AND :period_end;\n\n-- Session conversion rate (sessions with purchase)\nSELECT\n  COUNT(DISTINCT CASE WHEN event_name = 'purchase' THEN session_key END) * 1.0\n    / NULLIF(COUNT(DISTINCT session_key), 0) AS conversion_rate_session\nFROM curated_fct_ga4_event\nWHERE event_date BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'dynamics365' => [
        'sqlExamples' => [
            [
                'id' => 'raw-opportunity',
                'stage' => 'raw',
                'title' => ['de' => 'RAW opportunity Landing', 'en' => 'RAW opportunity landing'],
                'notes' => [
                    'de' => 'Dataverse Opportunity — actualvalue, estimatedvalue, statecode/statuscode, transactioncurrencyid.',
                    'en' => 'Dataverse opportunity — actualvalue, estimatedvalue, statecode/statuscode, transactioncurrencyid.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Dataverse export (not FetchXML)\nCREATE TABLE raw_d365_opportunity (\n  opportunityid           VARCHAR,\n  name                    VARCHAR,\n  parentaccountid         VARCHAR,\n  owninguser              VARCHAR,\n  actualvalue             DECIMAL(18,2),\n  estimatedvalue          DECIMAL(18,2),\n  closeprobability        INT,\n  statecode               INT,  -- 0 Open, 1 Won, 2 Lost\n  statuscode              INT,\n  actualclosedate         DATE,\n  estimatedclosedate      DATE,\n  transactioncurrencyid   VARCHAR,\n  createdon               TIMESTAMP,\n  modifiedon              TIMESTAMP,\n  statecode_name          VARCHAR,\n  _loaded_at              TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-account-systemuser',
                'stage' => 'raw',
                'title' => ['de' => 'RAW account + systemuser', 'en' => 'RAW account + systemuser'],
                'notes' => [
                    'de' => 'emailaddress1 und Adressfelder sind PII — RAW restriktiv.',
                    'en' => 'emailaddress1 and address fields are PII — keep RAW restricted.',
                ],
                'sql' => "CREATE TABLE raw_d365_account (\n  accountid VARCHAR,\n  name VARCHAR,\n  address1_country VARCHAR,\n  address1_postalcode VARCHAR, -- PII/quasi\n  ownerid VARCHAR,\n  createdon TIMESTAMP,\n  modifiedon TIMESTAMP,\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_d365_systemuser (\n  systemuserid VARCHAR,\n  fullname VARCHAR,\n  internalemailaddress VARCHAR, -- workforce PII\n  isdisabled BOOLEAN,\n  _loaded_at TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-opportunity',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact opportunity', 'en' => 'Curated fact opportunity'],
                'notes' => [
                    'de' => 'Won = statecode = 1; Open Pipeline über estimatedvalue und statecode = 0.',
                    'en' => 'Won = statecode = 1; open pipeline via estimatedvalue and statecode = 0.',
                ],
                'sql' => "CREATE TABLE curated_fct_d365_opportunity AS\nSELECT\n  opportunityid,\n  parentaccountid AS account_id,\n  owninguser AS owner_id,\n  actualvalue,\n  estimatedvalue,\n  closeprobability,\n  statecode,\n  statuscode,\n  actualclosedate,\n  estimatedclosedate,\n  transactioncurrencyid AS currency_id,\n  createdon,\n  (estimatedvalue * closeprobability / 100.0) AS weighted_estimatedvalue,\n  CASE WHEN statecode = 1 THEN TRUE ELSE FALSE END AS is_won,\n  CASE WHEN statecode = 0 THEN TRUE ELSE FALSE END AS is_open\nFROM raw_d365_opportunity;",
            ],
            [
                'id' => 'curated-dims-d365',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim account / owner', 'en' => 'Curated dim account / owner'],
                'notes' => [
                    'de' => 'Keine Klartext-Emails in Default-Dims; Region statt PLZ.',
                    'en' => 'No cleartext emails in default dims; region instead of postal code.',
                ],
                'sql' => "CREATE TABLE curated_dim_d365_account AS\nSELECT\n  accountid AS account_id,\n  name,\n  address1_country AS region,\n  ownerid AS owner_id\nFROM raw_d365_account;\n\nCREATE TABLE curated_dim_d365_owner AS\nSELECT\n  systemuserid AS owner_id,\n  fullname AS owner_name\n  -- omit internalemailaddress from default marts\nFROM raw_d365_systemuser\nWHERE COALESCE(isdisabled, FALSE) = FALSE;",
            ],
            [
                'id' => 'curated-measure-d365',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'actualvalue für Won; estimatedvalue für Open; Win-Rate über statecode.',
                    'en' => 'actualvalue for won; estimatedvalue for open; win rate via statecode.',
                ],
                'sql' => "-- Won actualvalue in period\nSELECT SUM(actualvalue) AS won_actualvalue\nFROM curated_fct_d365_opportunity\nWHERE statecode = 1\n  AND actualclosedate BETWEEN :period_start AND :period_end;\n\n-- Open estimatedvalue\nSELECT SUM(estimatedvalue) AS open_estimatedvalue\nFROM curated_fct_d365_opportunity\nWHERE statecode = 0;\n\n-- Win rate (closed opps: won vs won+lost)\nSELECT\n  SUM(CASE WHEN statecode = 1 THEN 1 ELSE 0 END) * 1.0\n    / NULLIF(SUM(CASE WHEN statecode IN (1, 2) THEN 1 ELSE 0 END), 0) AS win_rate\nFROM curated_fct_d365_opportunity\nWHERE statecode IN (1, 2)\n  AND actualclosedate BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'servicenow' => [
        'sqlExamples' => [
            [
                'id' => 'raw-incident',
                'stage' => 'raw',
                'title' => ['de' => 'RAW incident Landing', 'en' => 'RAW incident landing'],
                'notes' => [
                    'de' => 'task/incident Felder — number, state, priority, caller_id, assignment_group, opened_at/resolved_at.',
                    'en' => 'task/incident fields — number, state, priority, caller_id, assignment_group, opened_at/resolved_at.',
                ],
                'sql' => "-- Warehouse-neutral RAW from ServiceNow table API / export\nCREATE TABLE raw_sn_incident (\n  sys_id            VARCHAR,\n  number            VARCHAR,\n  short_description VARCHAR,\n  state             INT,\n  priority          INT,\n  impact            INT,\n  urgency           INT,\n  category          VARCHAR,\n  caller_id         VARCHAR,\n  assignment_group  VARCHAR,\n  assigned_to       VARCHAR,\n  cmdb_ci           VARCHAR,\n  opened_at         TIMESTAMP,\n  resolved_at       TIMESTAMP,\n  closed_at         TIMESTAMP,\n  sys_created_on    TIMESTAMP,\n  sys_updated_on    TIMESTAMP,\n  close_code        VARCHAR,\n  _loaded_at        TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-user-ci',
                'stage' => 'raw',
                'title' => ['de' => 'RAW sys_user + cmdb_ci', 'en' => 'RAW sys_user + cmdb_ci'],
                'notes' => [
                    'de' => 'sys_user Email/Phone = Workforce-PII; CI-Klassen nicht flatten ohne Class Manager.',
                    'en' => 'sys_user email/phone = workforce PII; do not flatten CI classes without Class Manager.',
                ],
                'sql' => "CREATE TABLE raw_sn_sys_user (\n  sys_id VARCHAR,\n  user_name VARCHAR,\n  name VARCHAR,\n  email VARCHAR, -- workforce PII\n  active BOOLEAN,\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_sn_cmdb_ci (\n  sys_id VARCHAR,\n  name VARCHAR,\n  sys_class_name VARCHAR,\n  install_status INT,\n  _loaded_at TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-incident',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact incident', 'en' => 'Curated fact incident'],
                'notes' => [
                    'de' => 'MTTR = resolved_at − opened_at; Open = state not closed/resolved per process definition.',
                    'en' => 'MTTR = resolved_at − opened_at; open = state not closed/resolved per process definition.',
                ],
                'sql' => "CREATE TABLE curated_fct_sn_incident AS\nSELECT\n  sys_id AS incident_id,\n  number,\n  state,\n  priority,\n  category,\n  caller_id,\n  assignment_group,\n  assigned_to,\n  cmdb_ci AS ci_id,\n  opened_at,\n  resolved_at,\n  closed_at,\n  close_code,\n  CASE\n    WHEN resolved_at IS NOT NULL AND opened_at IS NOT NULL\n    THEN EXTRACT(EPOCH FROM (resolved_at - opened_at)) / 3600.0\n  END AS mttr_hours,\n  CASE WHEN state NOT IN (6, 7, 8) THEN TRUE ELSE FALSE END AS is_open\nFROM raw_sn_incident;\n-- Adjust closed state codes to your instance choice lists.",
            ],
            [
                'id' => 'curated-dims-sn',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim user / CI', 'en' => 'Curated dim user / CI'],
                'notes' => [
                    'de' => 'User ohne Email in Default-Marts; CI mit Class für Drilldowns.',
                    'en' => 'User without email in default marts; CI with class for drilldowns.',
                ],
                'sql' => "CREATE TABLE curated_dim_sn_user AS\nSELECT\n  sys_id AS user_id,\n  user_name,\n  name\n  -- omit email from default analytics dims\nFROM raw_sn_sys_user\nWHERE active = TRUE;\n\nCREATE TABLE curated_dim_sn_ci AS\nSELECT\n  sys_id AS ci_id,\n  name AS ci_name,\n  sys_class_name AS ci_class\nFROM raw_sn_cmdb_ci;",
            ],
            [
                'id' => 'curated-measure-sn',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Open Incidents, MTTR, Created — Assignment Group als Dimension joinen.',
                    'en' => 'Open incidents, MTTR, created — join assignment group as dimension.',
                ],
                'sql' => "-- Open incidents\nSELECT COUNT(*) AS open_incidents\nFROM curated_fct_sn_incident\nWHERE is_open = TRUE;\n\n-- MTTR (hours) for resolved in period\nSELECT AVG(mttr_hours) AS mttr_hours\nFROM curated_fct_sn_incident\nWHERE resolved_at BETWEEN :period_start AND :period_end\n  AND mttr_hours IS NOT NULL;\n\n-- Incidents created\nSELECT COUNT(*) AS incidents_created\nFROM curated_fct_sn_incident\nWHERE opened_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'zendesk' => [
        'sqlExamples' => [
            [
                'id' => 'raw-tickets',
                'stage' => 'raw',
                'title' => ['de' => 'RAW tickets Landing', 'en' => 'RAW tickets landing'],
                'notes' => [
                    'de' => 'Tickets API Shape — status, priority, requester_id, assignee_id, created_at/solved_at.',
                    'en' => 'Tickets API shape — status, priority, requester_id, assignee_id, created_at/solved_at.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Zendesk Tickets API export\nCREATE TABLE raw_zd_ticket (\n  ticket_id      BIGINT,\n  subject        VARCHAR,\n  status         VARCHAR, -- new/open/pending/hold/solved/closed\n  priority       VARCHAR,\n  type           VARCHAR,\n  requester_id   BIGINT,\n  submitter_id   BIGINT,\n  assignee_id    BIGINT,\n  organization_id BIGINT,\n  group_id       BIGINT,\n  created_at     TIMESTAMP,\n  updated_at     TIMESTAMP,\n  solved_at      TIMESTAMP,\n  _loaded_at     TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-users-metrics',
                'stage' => 'raw',
                'title' => ['de' => 'RAW users + ticket_metrics', 'en' => 'RAW users + ticket_metrics'],
                'notes' => [
                    'de' => 'End-user Email = PII; ticket_metrics für First Reply / Full Resolution Time.',
                    'en' => 'End-user email = PII; ticket_metrics for first reply / full resolution time.',
                ],
                'sql' => "CREATE TABLE raw_zd_user (\n  user_id BIGINT,\n  name VARCHAR,\n  email VARCHAR, -- end-user / agent PII\n  role VARCHAR,  -- end-user / agent / admin\n  organization_id BIGINT,\n  active BOOLEAN,\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_zd_ticket_metric (\n  ticket_id BIGINT,\n  reply_time_in_minutes_calendar INT,\n  reply_time_in_minutes_business INT,\n  full_resolution_time_in_minutes_calendar INT,\n  requester_wait_time_in_minutes_calendar INT,\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_zd_satisfaction_rating (\n  rating_id BIGINT,\n  ticket_id BIGINT,\n  score VARCHAR, -- good/bad/offered\n  created_at TIMESTAMP,\n  _loaded_at TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-ticket',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact ticket', 'en' => 'Curated fact ticket'],
                'notes' => [
                    'de' => 'Open = status in (new, open, pending, hold); Solved/Closed für Throughput.',
                    'en' => 'Open = status in (new, open, pending, hold); solved/closed for throughput.',
                ],
                'sql' => "CREATE TABLE curated_fct_zd_ticket AS\nSELECT\n  t.ticket_id,\n  t.status,\n  t.priority,\n  t.type,\n  t.requester_id,\n  t.assignee_id,\n  t.organization_id,\n  t.group_id,\n  t.created_at,\n  t.solved_at,\n  m.reply_time_in_minutes_calendar AS first_reply_minutes,\n  m.full_resolution_time_in_minutes_calendar AS full_resolution_minutes,\n  CASE WHEN t.status IN ('new', 'open', 'pending', 'hold') THEN TRUE ELSE FALSE END AS is_open,\n  CASE WHEN t.status IN ('solved', 'closed') THEN TRUE ELSE FALSE END AS is_solved\nFROM raw_zd_ticket t\nLEFT JOIN raw_zd_ticket_metric m ON m.ticket_id = t.ticket_id;",
            ],
            [
                'id' => 'curated-dims-zd',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim user / CSAT', 'en' => 'Curated dim user / CSAT'],
                'notes' => [
                    'de' => 'User-Dims ohne Email; CSAT als Fact oder periodische Aggregation.',
                    'en' => 'User dims without email; CSAT as fact or periodic aggregation.',
                ],
                'sql' => "CREATE TABLE curated_dim_zd_user AS\nSELECT\n  user_id,\n  name,\n  role,\n  organization_id\n  -- omit email from default analytics dims\nFROM raw_zd_user\nWHERE active = TRUE;\n\nCREATE TABLE curated_fct_zd_csat AS\nSELECT\n  rating_id,\n  ticket_id,\n  score,\n  created_at,\n  CASE WHEN score = 'good' THEN 1 WHEN score = 'bad' THEN 0 END AS csat_flag\nFROM raw_zd_satisfaction_rating\nWHERE score IN ('good', 'bad');",
            ],
            [
                'id' => 'curated-measure-zd',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Created/Solved, Open, First Reply, CSAT — business vs calendar minutes wählen.',
                    'en' => 'Created/solved, open, first reply, CSAT — choose business vs calendar minutes.',
                ],
                'sql' => "-- Tickets created\nSELECT COUNT(*) AS tickets_created\nFROM curated_fct_zd_ticket\nWHERE created_at BETWEEN :period_start AND :period_end;\n\n-- Tickets solved\nSELECT COUNT(*) AS tickets_solved\nFROM curated_fct_zd_ticket\nWHERE is_solved = TRUE\n  AND solved_at BETWEEN :period_start AND :period_end;\n\n-- Open tickets\nSELECT COUNT(*) AS open_tickets\nFROM curated_fct_zd_ticket\nWHERE is_open = TRUE;\n\n-- First reply time (avg minutes)\nSELECT AVG(first_reply_minutes) AS avg_first_reply_minutes\nFROM curated_fct_zd_ticket\nWHERE created_at BETWEEN :period_start AND :period_end\n  AND first_reply_minutes IS NOT NULL;\n\n-- CSAT score\nSELECT AVG(csat_flag) AS csat_score\nFROM curated_fct_zd_csat\nWHERE created_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'shopify' => [
        'sqlExamples' => [
            [
                'id' => 'raw-orders',
                'stage' => 'raw',
                'title' => ['de' => 'RAW orders Landing', 'en' => 'RAW orders landing'],
                'notes' => [
                    'de' => 'Orders API/GraphQL — shop_money Beträge, financial_status, customer_id, created_at.',
                    'en' => 'Orders API/GraphQL — shop_money amounts, financial_status, customer_id, created_at.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Shopify Admin API / bulk export\nCREATE TABLE raw_shopify_order (\n  order_id            BIGINT,\n  name                VARCHAR, -- e.g. #1001\n  customer_id         BIGINT,\n  email               VARCHAR, -- PII\n  financial_status    VARCHAR,\n  fulfillment_status  VARCHAR,\n  currency            VARCHAR,\n  total_price         DECIMAL(18,2),\n  subtotal_price      DECIMAL(18,2),\n  total_discounts     DECIMAL(18,2),\n  total_tax           DECIMAL(18,2),\n  created_at          TIMESTAMP,\n  processed_at        TIMESTAMP,\n  cancelled_at        TIMESTAMP,\n  _loaded_at          TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-line-customer-refund',
                'stage' => 'raw',
                'title' => ['de' => 'RAW line items + customers + refunds', 'en' => 'RAW line items + customers + refunds'],
                'notes' => [
                    'de' => 'Line Items für Units/GMV-Detail; Refunds separat; Customer Email = PII.',
                    'en' => 'Line items for units/GMV detail; refunds separate; customer email = PII.',
                ],
                'sql' => "CREATE TABLE raw_shopify_order_line_item (\n  line_item_id BIGINT,\n  order_id BIGINT,\n  product_id BIGINT,\n  variant_id BIGINT,\n  sku VARCHAR,\n  title VARCHAR,\n  quantity INT,\n  price DECIMAL(18,2),\n  total_discount DECIMAL(18,2),\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_shopify_customer (\n  customer_id BIGINT,\n  email VARCHAR, -- PII\n  first_name VARCHAR,\n  last_name VARCHAR,\n  orders_count INT,\n  total_spent DECIMAL(18,2),\n  created_at TIMESTAMP,\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_shopify_refund (\n  refund_id BIGINT,\n  order_id BIGINT,\n  created_at TIMESTAMP,\n  _loaded_at TIMESTAMP\n);\n\nCREATE TABLE raw_shopify_refund_line_item (\n  refund_id BIGINT,\n  order_id BIGINT,\n  line_item_id BIGINT,\n  quantity INT,\n  subtotal DECIMAL(18,2),\n  _loaded_at TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-order',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact order / line', 'en' => 'Curated fact order / line'],
                'notes' => [
                    'de' => 'GMV typischerweise paid/partially_paid und nicht cancelled; shop currency behalten.',
                    'en' => 'GMV typically paid/partially_paid and not cancelled; keep shop currency.',
                ],
                'sql' => "CREATE TABLE curated_fct_shopify_order AS\nSELECT\n  order_id,\n  customer_id,\n  financial_status,\n  fulfillment_status,\n  currency AS currency_code,\n  total_price,\n  subtotal_price,\n  total_discounts,\n  created_at,\n  processed_at,\n  CASE\n    WHEN cancelled_at IS NULL\n     AND financial_status IN ('paid', 'partially_paid')\n    THEN TRUE ELSE FALSE\n  END AS is_gmv_order\nFROM raw_shopify_order;\n\nCREATE TABLE curated_fct_shopify_order_line AS\nSELECT\n  li.line_item_id,\n  li.order_id,\n  o.customer_id,\n  o.created_at AS order_created_at,\n  o.currency_code,\n  li.product_id,\n  li.variant_id,\n  li.sku,\n  li.quantity,\n  li.price,\n  (li.price * li.quantity - COALESCE(li.total_discount, 0)) AS line_net,\n  o.is_gmv_order\nFROM raw_shopify_order_line_item li\nJOIN curated_fct_shopify_order o ON o.order_id = li.order_id;",
            ],
            [
                'id' => 'curated-dims-shopify',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim customer', 'en' => 'Curated dim customer'],
                'notes' => [
                    'de' => 'Customer ohne Email in Default-Marts; Refund-Fact für Refund-Rate.',
                    'en' => 'Customer without email in default marts; refund fact for refund rate.',
                ],
                'sql' => "CREATE TABLE curated_dim_shopify_customer AS\nSELECT\n  customer_id,\n  first_name,\n  last_name,\n  orders_count,\n  total_spent,\n  created_at\n  -- omit email from default analytics dims\nFROM raw_shopify_customer;\n\nCREATE TABLE curated_fct_shopify_refund AS\nSELECT\n  r.refund_id,\n  r.order_id,\n  r.created_at,\n  SUM(ri.subtotal) AS refund_subtotal,\n  SUM(ri.quantity) AS refund_units\nFROM raw_shopify_refund r\nLEFT JOIN raw_shopify_refund_line_item ri ON ri.refund_id = r.refund_id\nGROUP BY r.refund_id, r.order_id, r.created_at;",
            ],
            [
                'id' => 'curated-measure-shopify',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'GMV, AOV, Orders, Units, Refund-Rate — Währung nicht mischen.',
                    'en' => 'GMV, AOV, orders, units, refund rate — do not mix currencies.',
                ],
                'sql' => "-- GMV\nSELECT SUM(total_price) AS gmv\nFROM curated_fct_shopify_order\nWHERE is_gmv_order = TRUE\n  AND created_at BETWEEN :period_start AND :period_end;\n\n-- Orders + AOV\nSELECT\n  COUNT(*) AS orders_count,\n  AVG(total_price) AS aov\nFROM curated_fct_shopify_order\nWHERE is_gmv_order = TRUE\n  AND created_at BETWEEN :period_start AND :period_end;\n\n-- Units sold\nSELECT SUM(quantity) AS units_sold\nFROM curated_fct_shopify_order_line\nWHERE is_gmv_order = TRUE\n  AND order_created_at BETWEEN :period_start AND :period_end;\n\n-- Refund rate (refunded subtotal / GMV)\nSELECT\n  SUM(r.refund_subtotal) * 1.0\n    / NULLIF((SELECT SUM(total_price) FROM curated_fct_shopify_order o\n              WHERE o.is_gmv_order = TRUE\n                AND o.created_at BETWEEN :period_start AND :period_end), 0) AS refund_rate\nFROM curated_fct_shopify_refund r\nWHERE r.created_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

];
