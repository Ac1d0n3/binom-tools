<?php

/**
 * Wave 2 warehouse SQL examples — ERP/HCM source-native (Landing RAW + Curated).
 * Warehouse-neutral dialect — not SAP Open SQL / SuiteQL / OData DDL.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'sap-s4hana' => [
        'sqlExamples' => [
            [
                'id' => 'raw-vbak-vbap',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Sales Order (VBAK/VBAP) Landing', 'en' => 'RAW sales order (VBAK/VBAP) landing'],
                'notes' => [
                    'de' => 'Source-shaped Landing aus SD-Extract — kunnr/netwr taggen; kein Open SQL im Mart.',
                    'en' => 'Source-shaped landing from SD extract — tag kunnr/netwr; no Open SQL in mart.',
                ],
                'sql' => "-- Warehouse-neutral RAW example (adapt dialect)\nCREATE TABLE raw_sap_vbak (\n  vbeln        VARCHAR,\n  kunnr        VARCHAR,\n  audat        DATE,\n  erdat        DATE,\n  netwr        DECIMAL(18,2),\n  waerk        VARCHAR,\n  gbstk        VARCHAR,\n  vkorg        VARCHAR,\n  vtweg        VARCHAR,\n  spart        VARCHAR,\n  _loaded_at   TIMESTAMP\n);\n\nCREATE TABLE raw_sap_vbap (\n  vbeln        VARCHAR,\n  posnr        VARCHAR,\n  matnr        VARCHAR,\n  kwmeng       DECIMAL(18,3),\n  netwr        DECIMAL(18,2),\n  pstyv        VARCHAR,\n  _loaded_at   TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-kna1-lfa1',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Customer + Vendor (KNA1/LFA1) Landing', 'en' => 'RAW customer + vendor (KNA1/LFA1) landing'],
                'notes' => [
                    'de' => 'smtp_addr/telf1 sind PII — RAW restriktiv; getrennte Grants vs. Facts.',
                    'en' => 'smtp_addr/telf1 are PII — keep RAW restricted; separate grants vs facts.',
                ],
                'sql' => "CREATE TABLE raw_sap_kna1 (\n  kunnr        VARCHAR,\n  name1        VARCHAR,\n  land1        VARCHAR,\n  pstlz        VARCHAR, -- quasi/direct PII\n  smtp_addr    VARCHAR, -- direct PII\n  telf1        VARCHAR, -- direct PII\n  _loaded_at   TIMESTAMP\n);\n\nCREATE TABLE raw_sap_lfa1 (\n  lifnr        VARCHAR,\n  name1        VARCHAR,\n  land1        VARCHAR,\n  smtp_addr    VARCHAR, -- direct PII\n  _loaded_at   TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-sales-order',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact sales order / line', 'en' => 'Curated fact sales order / line'],
                'notes' => [
                    'de' => 'Conformed Fact — keine Klartext-PII; kunnr/matnr als Keys + Measures.',
                    'en' => 'Conformed fact — no cleartext PII; kunnr/matnr as keys + measures.',
                ],
                'sql' => "CREATE TABLE curated_fct_sap_sales_order AS\nSELECT\n  h.vbeln,\n  h.kunnr,\n  h.audat,\n  h.netwr AS header_netwr,\n  h.waerk AS currency_code,\n  h.gbstk AS overall_status,\n  h.vkorg,\n  l.posnr,\n  l.matnr,\n  l.kwmeng,\n  l.netwr AS line_netwr\nFROM raw_sap_vbak h\nJOIN raw_sap_vbap l ON l.vbeln = h.vbeln;\n-- Open backlog: filter gbstk + open qty in mart logic.",
            ],
            [
                'id' => 'curated-dims-sap',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim customer / material', 'en' => 'Curated dim customer / material'],
                'notes' => [
                    'de' => 'Dimensionen ohne E-Mail/PLZ-Klartext in Default-Marts.',
                    'en' => 'Dims without email/postal cleartext in default marts.',
                ],
                'sql' => "CREATE TABLE curated_dim_sap_customer AS\nSELECT\n  kunnr,\n  name1,\n  land1 AS country\n  -- omit smtp_addr, pstlz from default analytics dims\nFROM raw_sap_kna1;\n\nCREATE TABLE curated_dim_sap_material AS\nSELECT\n  matnr,\n  mtart AS material_type,\n  matkl AS material_group,\n  meins AS base_uom\nFROM raw_sap_mara;",
            ],
            [
                'id' => 'curated-measure-sap',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Order Intake, Open Backlog, Units — Periodenfilter anpassen.',
                    'en' => 'Order intake, open backlog, units — adapt period filters.',
                ],
                'sql' => "-- Order intake in period\nSELECT SUM(header_netwr) AS order_intake\nFROM curated_fct_sap_sales_order\nWHERE audat BETWEEN :period_start AND :period_end;\n\n-- Open order backlog (example — adjust status filter)\nSELECT SUM(line_netwr) AS open_backlog\nFROM curated_fct_sap_sales_order\nWHERE overall_status NOT IN ('C', 'X');\n\n-- Units ordered\nSELECT SUM(kwmeng) AS units_ordered\nFROM curated_fct_sap_sales_order\nWHERE audat BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'netsuite' => [
        'sqlExamples' => [
            [
                'id' => 'raw-ns-transaction',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Transaction Landing', 'en' => 'RAW transaction landing'],
                'notes' => [
                    'de' => 'SuiteQL/Connect-Shape vereinfacht — entity, type, status, amounts.',
                    'en' => 'Simplified SuiteQL/Connect shape — entity, type, status, amounts.',
                ],
                'sql' => "-- Warehouse-neutral RAW from NetSuite extract (not SuiteQL)\nCREATE TABLE raw_ns_transaction (\n  transaction_id     BIGINT,\n  tranid             VARCHAR,\n  type               VARCHAR, -- CustInvc, SalesOrd, etc.\n  entity_id          BIGINT,\n  subsidiary_id      BIGINT,\n  status             VARCHAR,\n  trandate           DATE,\n  foreignamount      DECIMAL(18,2),\n  currency_id        BIGINT,\n  postingperiod_id   BIGINT,\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-ns-line-entity',
                'stage' => 'raw',
                'title' => ['de' => 'RAW transactionline + customer', 'en' => 'RAW transactionline + customer'],
                'notes' => [
                    'de' => 'Lines für Produktmix; customer.email = PII — RAW restriktiv.',
                    'en' => 'Lines for product mix; customer.email = PII — keep RAW restricted.',
                ],
                'sql' => "CREATE TABLE raw_ns_transactionline (\n  transactionline_id BIGINT,\n  transaction_id     BIGINT,\n  item_id            BIGINT,\n  quantity           DECIMAL(18,4),\n  foreignamount      DECIMAL(18,2),\n  _loaded_at         TIMESTAMP\n);\n\nCREATE TABLE raw_ns_customer (\n  customer_id        BIGINT,\n  entityid           VARCHAR,\n  companyname        VARCHAR,\n  email              VARCHAR, -- direct PII\n  subsidiary_id      BIGINT,\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-ns-transaction',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact transaction / line', 'en' => 'Curated fact transaction / line'],
                'notes' => [
                    'de' => 'Posted Invoices für Revenue; Open SO für Backlog — Status-Map in Curated.',
                    'en' => 'Posted invoices for revenue; open SO for backlog — status map in Curated.',
                ],
                'sql' => "CREATE TABLE curated_fct_ns_transaction AS\nSELECT\n  t.transaction_id,\n  t.tranid,\n  t.type,\n  t.entity_id,\n  t.subsidiary_id,\n  t.status,\n  t.trandate,\n  t.foreignamount,\n  t.currency_id,\n  l.transactionline_id,\n  l.item_id,\n  l.quantity,\n  l.foreignamount AS line_amount,\n  CASE WHEN t.type = 'CustInvc' AND t.status = 'B' THEN TRUE ELSE FALSE END AS is_posted_invoice\nFROM raw_ns_transaction t\nLEFT JOIN raw_ns_transactionline l ON l.transaction_id = t.transaction_id;",
            ],
            [
                'id' => 'curated-dims-ns',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim customer / item', 'en' => 'Curated dim customer / item'],
                'notes' => [
                    'de' => 'Customer ohne email in Default-Marts; subsidiary für Legal Entity.',
                    'en' => 'Customer without email in default marts; subsidiary for legal entity.',
                ],
                'sql' => "CREATE TABLE curated_dim_ns_customer AS\nSELECT\n  customer_id,\n  entityid,\n  companyname,\n  subsidiary_id\n  -- omit email from default analytics dims\nFROM raw_ns_customer;\n\nCREATE TABLE curated_dim_ns_item AS\nSELECT\n  item_id,\n  itemid AS sku,\n  displayname,\n  itemtype\nFROM raw_ns_item;",
            ],
            [
                'id' => 'curated-measure-ns',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Posted Revenue, Open SO Amount, Units — Währung nicht mischen.',
                    'en' => 'Posted revenue, open SO amount, units — do not mix currencies.',
                ],
                'sql' => "-- Posted invoice revenue in period\nSELECT SUM(foreignamount) AS posted_revenue\nFROM curated_fct_ns_transaction\nWHERE is_posted_invoice = TRUE\n  AND trandate BETWEEN :period_start AND :period_end;\n\n-- Open sales order amount\nSELECT SUM(foreignamount) AS open_so_amount\nFROM curated_fct_ns_transaction\nWHERE type = 'SalesOrd'\n  AND status NOT IN ('H', 'C');\n\n-- Units on posted invoices\nSELECT SUM(quantity) AS units_invoiced\nFROM curated_fct_ns_transaction\nWHERE is_posted_invoice = TRUE\n  AND trandate BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'workday' => [
        'sqlExamples' => [
            [
                'id' => 'raw-wd-worker',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Worker Landing', 'en' => 'RAW worker landing'],
                'notes' => [
                    'de' => 'RaaS/API-Shape — Workforce PII taggen; nationalId default skip in Curated.',
                    'en' => 'RaaS/API shape — tag workforce PII; default skip nationalId in Curated.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Workday RaaS/API extract\nCREATE TABLE raw_wd_worker (\n  worker_id            VARCHAR,\n  employee_id          VARCHAR,\n  hire_date            DATE,\n  termination_date     DATE,\n  primary_work_email   VARCHAR, -- workforce PII\n  cost_center_id       VARCHAR,\n  supervisory_org_id   VARCHAR,\n  job_profile_id       VARCHAR,\n  worker_status        VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-wd-position-org',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Position + Org Landing', 'en' => 'RAW position + org landing'],
                'notes' => [
                    'de' => 'Position/Org für Hierarchie — keine Bank/ nationalId Felder default laden.',
                    'en' => 'Position/org for hierarchy — do not load bank/nationalId fields by default.',
                ],
                'sql' => "CREATE TABLE raw_wd_position (\n  position_id            VARCHAR,\n  position_title         VARCHAR,\n  supervisory_org_id     VARCHAR,\n  cost_center_id         VARCHAR,\n  job_profile_id         VARCHAR,\n  effective_date         DATE,\n  _loaded_at             TIMESTAMP\n);\n\nCREATE TABLE raw_wd_supervisory_org (\n  org_id                 VARCHAR,\n  org_name               VARCHAR,\n  manager_worker_id      VARCHAR,\n  org_level              INT,\n  _loaded_at             TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-wd-headcount',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact headcount snapshot', 'en' => 'Curated fact headcount snapshot'],
                'notes' => [
                    'de' => 'Active Worker Grain — hire/termination für Perioden-Headcount und Turnover.',
                    'en' => 'Active worker grain — hire/termination for period headcount and turnover.',
                ],
                'sql' => "CREATE TABLE curated_fct_wd_headcount AS\nSELECT\n  w.worker_id,\n  w.employee_id,\n  w.hire_date,\n  w.termination_date,\n  w.cost_center_id,\n  w.supervisory_org_id,\n  w.job_profile_id,\n  CASE\n    WHEN w.termination_date IS NULL OR w.termination_date > :as_of_date\n    THEN TRUE ELSE FALSE\n  END AS is_active_as_of\nFROM raw_wd_worker w\nWHERE w.hire_date IS NOT NULL\n  AND w.hire_date <= :as_of_date;",
            ],
            [
                'id' => 'curated-dims-wd',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim org / cost center', 'en' => 'Curated dim org / cost center'],
                'notes' => [
                    'de' => 'Org-Hierarchie ohne Workforce-Email; manager_id als Key only.',
                    'en' => 'Org hierarchy without workforce email; manager_id as key only.',
                ],
                'sql' => "CREATE TABLE curated_dim_wd_org AS\nSELECT\n  org_id,\n  org_name,\n  manager_worker_id,\n  org_level\nFROM raw_wd_supervisory_org;\n\nCREATE TABLE curated_dim_wd_cost_center AS\nSELECT\n  cost_center_id,\n  cost_center_name,\n  company_id\nFROM raw_wd_cost_center;",
            ],
            [
                'id' => 'curated-measure-wd',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Headcount, Hires, Terminations — as-of vs flow definieren.',
                    'en' => 'Headcount, hires, terminations — define as-of vs flow.',
                ],
                'sql' => "-- Active headcount as of date\nSELECT COUNT(*) AS active_headcount\nFROM curated_fct_wd_headcount\nWHERE is_active_as_of = TRUE;\n\n-- Hires in period\nSELECT COUNT(*) AS hires\nFROM curated_fct_wd_headcount\nWHERE hire_date BETWEEN :period_start AND :period_end;\n\n-- Terminations in period\nSELECT COUNT(*) AS terminations\nFROM curated_fct_wd_headcount\nWHERE termination_date BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'successfactors' => [
        'sqlExamples' => [
            [
                'id' => 'raw-sf-emp-job',
                'stage' => 'raw',
                'title' => ['de' => 'RAW EmpJob Landing', 'en' => 'RAW EmpJob landing'],
                'notes' => [
                    'de' => 'OData EmpJob Shape — department, managerId, costCenter; kein OData im Mart.',
                    'en' => 'OData EmpJob shape — department, managerId, costCenter; no OData in mart.',
                ],
                'sql' => "-- Warehouse-neutral RAW from SuccessFactors OData extract\nCREATE TABLE raw_sf_emp_job (\n  user_id              VARCHAR,\n  person_id_external   VARCHAR,\n  start_date           DATE,\n  end_date             DATE,\n  department           VARCHAR,\n  department_name      VARCHAR,\n  manager_id           VARCHAR,\n  cost_center          VARCHAR,\n  job_title            VARCHAR,\n  event_reason         VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-sf-person-employment',
                'stage' => 'raw',
                'title' => ['de' => 'RAW PerPerson + EmpEmployment', 'en' => 'RAW PerPerson + EmpEmployment'],
                'notes' => [
                    'de' => 'nationalId/email = PII — RAW restriktiv; Curated ohne Klartext.',
                    'en' => 'nationalId/email = PII — keep RAW restricted; no cleartext in Curated.',
                ],
                'sql' => "CREATE TABLE raw_sf_per_person (\n  person_id_external   VARCHAR,\n  date_of_birth        DATE, -- sensitive PII\n  country_of_birth     VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_sf_emp_employment (\n  user_id              VARCHAR,\n  person_id_external   VARCHAR,\n  start_date           DATE,\n  end_date             DATE,\n  original_start_date  DATE,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_sf_per_email (\n  person_id_external   VARCHAR,\n  email_address        VARCHAR, -- workforce PII\n  is_primary           BOOLEAN,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-sf-employment',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact employment / job', 'en' => 'Curated fact employment / job'],
                'notes' => [
                    'de' => 'Headcount-Grain = EmpEmployment × aktives EmpJob — ohne PII-Klartext.',
                    'en' => 'Headcount grain = EmpEmployment × active EmpJob — no PII cleartext.',
                ],
                'sql' => "CREATE TABLE curated_fct_sf_employment AS\nSELECT\n  e.user_id,\n  e.person_id_external,\n  e.start_date AS employment_start,\n  e.end_date AS employment_end,\n  j.department,\n  j.department_name,\n  j.manager_id,\n  j.cost_center,\n  j.job_title,\n  CASE\n    WHEN (e.end_date IS NULL OR e.end_date > :as_of_date)\n     AND j.start_date <= :as_of_date\n     AND (j.end_date IS NULL OR j.end_date > :as_of_date)\n    THEN TRUE ELSE FALSE\n  END AS is_active_as_of\nFROM raw_sf_emp_employment e\nLEFT JOIN raw_sf_emp_job j\n  ON j.user_id = e.user_id\n AND j.start_date <= :as_of_date\n AND (j.end_date IS NULL OR j.end_date > :as_of_date);",
            ],
            [
                'id' => 'curated-dims-sf',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim department / manager', 'en' => 'Curated dim department / manager'],
                'notes' => [
                    'de' => 'Department aus Picklist/BC; Manager ohne Email in Default-Marts.',
                    'en' => 'Department from picklist/BC; manager without email in default marts.',
                ],
                'sql' => "CREATE TABLE curated_dim_sf_department AS\nSELECT DISTINCT\n  department AS department_code,\n  department_name\nFROM raw_sf_emp_job\nWHERE department IS NOT NULL;\n\nCREATE TABLE curated_dim_sf_manager AS\nSELECT\n  user_id AS manager_id,\n  job_title AS manager_title\n  -- omit email from default analytics dims\nFROM raw_sf_emp_job\nWHERE user_id IN (SELECT DISTINCT manager_id FROM raw_sf_emp_job);",
            ],
            [
                'id' => 'curated-measure-sf',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Active HC, Hires, Dept HC — Picklist-Maps vor Aggregaten joinen.',
                    'en' => 'Active HC, hires, dept HC — join picklist maps before aggregates.',
                ],
                'sql' => "-- Active headcount as of date\nSELECT COUNT(*) AS active_headcount\nFROM curated_fct_sf_employment\nWHERE is_active_as_of = TRUE;\n\n-- Hires in period\nSELECT COUNT(*) AS hires\nFROM curated_fct_sf_employment\nWHERE employment_start BETWEEN :period_start AND :period_end;\n\n-- Headcount by department\nSELECT department_name, COUNT(*) AS dept_headcount\nFROM curated_fct_sf_employment\nWHERE is_active_as_of = TRUE\nGROUP BY department_name;",
            ],
        ],
    ],
];
