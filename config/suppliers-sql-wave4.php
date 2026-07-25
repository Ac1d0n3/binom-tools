<?php

/**
 * Wave 4 warehouse SQL examples — Finance/Spend source-native (Landing RAW + Curated).
 * Warehouse-neutral dialect — not Stripe Sigma / Concur reporting / Ariba ITK / Coupa API DDL.
 * Amounts: Stripe RAW keeps minor units; convert to major units in curated facts.
 * Never include PAN / CVC fields.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'stripe' => [
        'sqlExamples' => [
            [
                'id' => 'raw-stripe-charge',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Charge / PaymentIntent Landing', 'en' => 'RAW charge / PaymentIntent landing'],
                'notes' => [
                    'de' => 'API-Shape — amount in Minor Units (Cents); kein PAN/CVC; customer_id als Key.',
                    'en' => 'API shape — amount in minor units (cents); no PAN/CVC; customer_id as key.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Stripe API extract (not Sigma DDL)\n-- amount_* are minor units (e.g. cents) — convert in curated\nCREATE TABLE raw_stripe_charge (\n  charge_id          VARCHAR,\n  payment_intent_id  VARCHAR,\n  customer_id        VARCHAR,\n  status             VARCHAR,\n  amount             BIGINT, -- minor units\n  amount_refunded    BIGINT, -- minor units\n  currency           VARCHAR,\n  livemode           BOOLEAN,\n  created_at         TIMESTAMP,\n  card_brand         VARCHAR,\n  card_last4         VARCHAR, -- last4 only — never PAN/CVC\n  _loaded_at         TIMESTAMP\n);\n\nCREATE TABLE raw_stripe_payment_intent (\n  payment_intent_id  VARCHAR,\n  customer_id        VARCHAR,\n  status             VARCHAR,\n  amount             BIGINT, -- minor units\n  currency           VARCHAR,\n  livemode           BOOLEAN,\n  created_at         TIMESTAMP,\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-stripe-customer-refund',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Customer + Refund Landing', 'en' => 'RAW customer + refund landing'],
                'notes' => [
                    'de' => 'email = Kunden-PII — RAW restriktiv; Curated ohne Klartext; Refund amount in Minor Units.',
                    'en' => 'email = customer PII — keep RAW restricted; no cleartext in Curated; refund amount in minor units.',
                ],
                'sql' => "CREATE TABLE raw_stripe_customer (\n  customer_id        VARCHAR,\n  email              VARCHAR, -- customer PII\n  name               VARCHAR, -- customer PII\n  created_at         TIMESTAMP,\n  livemode           BOOLEAN,\n  _loaded_at         TIMESTAMP\n);\n\nCREATE TABLE raw_stripe_refund (\n  refund_id          VARCHAR,\n  charge_id          VARCHAR,\n  amount             BIGINT, -- minor units\n  currency           VARCHAR,\n  status             VARCHAR,\n  created_at         TIMESTAMP,\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-stripe-charge',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact stripe charge', 'en' => 'Curated fact stripe charge'],
                'notes' => [
                    'de' => 'Finance-Grain — Minor Units → Major Units (/100); nur livemode; kein PAN.',
                    'en' => 'Finance grain — minor units → major units (/100); livemode only; no PAN.',
                ],
                'sql' => "CREATE TABLE curated_fct_stripe_charge AS\nSELECT\n  c.charge_id,\n  c.payment_intent_id,\n  c.customer_id,\n  c.status,\n  c.amount / 100.0 AS amount_major, -- convert minor units\n  c.amount_refunded / 100.0 AS amount_refunded_major,\n  (c.amount - c.amount_refunded) / 100.0 AS net_amount_major,\n  c.currency,\n  c.created_at,\n  c.card_brand,\n  c.card_last4,\n  CASE WHEN c.status = 'succeeded' THEN TRUE ELSE FALSE END AS is_succeeded\nFROM raw_stripe_charge c\nWHERE c.livemode = TRUE\n  AND c.currency IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-stripe',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim customer / refund', 'en' => 'Curated dim customer / refund'],
                'notes' => [
                    'de' => 'Customer ohne email in Default-Marts; Refund amounts in Major Units.',
                    'en' => 'Customer without email in default marts; refund amounts in major units.',
                ],
                'sql' => "CREATE TABLE curated_dim_stripe_customer AS\nSELECT\n  customer_id,\n  created_at,\n  livemode\n  -- omit email, name from default analytics dims\nFROM raw_stripe_customer\nWHERE livemode = TRUE;\n\nCREATE TABLE curated_fct_stripe_refund AS\nSELECT\n  r.refund_id,\n  r.charge_id,\n  r.amount / 100.0 AS amount_major,\n  r.currency,\n  r.status,\n  r.created_at\nFROM raw_stripe_refund r\nJOIN raw_stripe_charge c ON c.charge_id = r.charge_id\nWHERE c.livemode = TRUE;",
            ],
            [
                'id' => 'curated-measure-stripe',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'GMV, Net Revenue, Refund Rate — Periodenfilter anpassen; Beträge bereits Major Units.',
                    'en' => 'GMV, net revenue, refund rate — adapt period filters; amounts already major units.',
                ],
                'sql' => "-- Gross payment volume (succeeded) in period\nSELECT SUM(amount_major) AS gmv_major\nFROM curated_fct_stripe_charge\nWHERE is_succeeded = TRUE\n  AND created_at BETWEEN :period_start AND :period_end;\n\n-- Net revenue after refunds\nSELECT SUM(net_amount_major) AS net_revenue_major\nFROM curated_fct_stripe_charge\nWHERE is_succeeded = TRUE\n  AND created_at BETWEEN :period_start AND :period_end;\n\n-- Refund amount in period\nSELECT SUM(amount_major) AS refunds_major\nFROM curated_fct_stripe_refund\nWHERE status = 'succeeded'\n  AND created_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'sap-concur' => [
        'sqlExamples' => [
            [
                'id' => 'raw-concur-report-entry',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Expense Report + Entry Landing', 'en' => 'RAW expense report + entry landing'],
                'notes' => [
                    'de' => 'Expense API Shape — Owner als EmployeeID; keine Receipt Binaries/OCR.',
                    'en' => 'Expense API shape — owner as EmployeeID; no receipt binaries/OCR.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Concur Expense API extract\nCREATE TABLE raw_concur_expense_report (\n  report_id          VARCHAR,\n  report_name        VARCHAR,\n  owner_employee_id  VARCHAR, -- workforce key\n  approval_status    VARCHAR,\n  submitted_at       TIMESTAMP,\n  approved_at        TIMESTAMP,\n  total_amount       DECIMAL(18,2),\n  currency           VARCHAR,\n  _loaded_at         TIMESTAMP\n);\n\nCREATE TABLE raw_concur_expense_entry (\n  entry_id           VARCHAR,\n  report_id          VARCHAR,\n  expense_type_code  VARCHAR,\n  transaction_date   DATE,\n  amount             DECIMAL(18,2),\n  currency           VARCHAR,\n  vendor_name        VARCHAR,\n  has_receipt        BOOLEAN, -- flag only — no image binary\n  card_last4         VARCHAR, -- last4 only — never PAN\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-concur-allocation-employee',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Allocation + Employee Landing', 'en' => 'RAW allocation + employee landing'],
                'notes' => [
                    'de' => 'Allocation für Cost-Center-Spend; email = Workforce-PII in RAW.',
                    'en' => 'Allocation for cost-center spend; email = workforce PII in RAW.',
                ],
                'sql' => "CREATE TABLE raw_concur_allocation (\n  allocation_id      VARCHAR,\n  entry_id           VARCHAR,\n  report_id          VARCHAR,\n  cost_center_code   VARCHAR,\n  allocation_pct     DECIMAL(9,4),\n  allocated_amount   DECIMAL(18,2),\n  _loaded_at         TIMESTAMP\n);\n\nCREATE TABLE raw_concur_employee (\n  employee_id        VARCHAR,\n  email_address      VARCHAR, -- workforce PII\n  first_name         VARCHAR,\n  last_name          VARCHAR,\n  active             BOOLEAN,\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-concur-expense',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact concur expense entry', 'en' => 'Curated fact concur expense entry'],
                'notes' => [
                    'de' => 'Spend-Grain mit Report-Status — ohne Receipt Content und Comment Bodies.',
                    'en' => 'Spend grain with report status — no receipt content or comment bodies.',
                ],
                'sql' => "CREATE TABLE curated_fct_concur_expense_entry AS\nSELECT\n  e.entry_id,\n  e.report_id,\n  r.owner_employee_id,\n  r.approval_status,\n  e.expense_type_code,\n  e.transaction_date,\n  e.amount,\n  e.currency,\n  e.has_receipt,\n  e.card_last4,\n  r.submitted_at,\n  r.approved_at,\n  CASE WHEN r.approval_status = 'Approved' THEN TRUE ELSE FALSE END AS is_approved\nFROM raw_concur_expense_entry e\nJOIN raw_concur_expense_report r ON r.report_id = e.report_id\nWHERE e.expense_type_code IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-concur',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim employee / allocation', 'en' => 'Curated dim employee / allocation'],
                'notes' => [
                    'de' => 'Employee ohne email; Allocation Facts für Cost-Center-Rollups.',
                    'en' => 'Employee without email; allocation facts for cost-center rollups.',
                ],
                'sql' => "CREATE TABLE curated_dim_concur_employee AS\nSELECT\n  employee_id,\n  active\n  -- omit email_address, names from default analytics dims\nFROM raw_concur_employee;\n\nCREATE TABLE curated_fct_concur_allocation AS\nSELECT\n  a.allocation_id,\n  a.entry_id,\n  a.report_id,\n  a.cost_center_code,\n  a.allocation_pct,\n  a.allocated_amount\nFROM raw_concur_allocation a\nWHERE a.cost_center_code IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-concur',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Approved Spend, by Cost Center, Reports Submitted — Periodenfilter anpassen.',
                    'en' => 'Approved spend, by cost center, reports submitted — adapt period filters.',
                ],
                'sql' => "-- Approved expense spend in period\nSELECT SUM(amount) AS approved_spend\nFROM curated_fct_concur_expense_entry\nWHERE is_approved = TRUE\n  AND approved_at BETWEEN :period_start AND :period_end;\n\n-- Approved spend by cost center\nSELECT\n  a.cost_center_code,\n  SUM(a.allocated_amount) AS spend_by_cc\nFROM curated_fct_concur_allocation a\nJOIN curated_fct_concur_expense_entry e ON e.entry_id = a.entry_id\nWHERE e.is_approved = TRUE\n  AND e.approved_at BETWEEN :period_start AND :period_end\nGROUP BY a.cost_center_code;\n\n-- Reports submitted in period\nSELECT COUNT(DISTINCT report_id) AS reports_submitted\nFROM curated_fct_concur_expense_entry\nWHERE submitted_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'sap-ariba' => [
        'sqlExamples' => [
            [
                'id' => 'raw-ariba-po-invoice',
                'stage' => 'raw',
                'title' => ['de' => 'RAW PO + Invoice Landing', 'en' => 'RAW PO + invoice landing'],
                'notes' => [
                    'de' => 'Reporting/API Shape — Supplier System ID; keine Attachment Binaries.',
                    'en' => 'Reporting/API shape — supplier system id; no attachment binaries.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Ariba extract (not ITK/reporting DDL)\nCREATE TABLE raw_ariba_purchase_order (\n  po_id              VARCHAR,\n  po_number          VARCHAR,\n  supplier_system_id VARCHAR,\n  requester_user_id  VARCHAR, -- workforce key\n  status             VARCHAR,\n  ordered_at         TIMESTAMP,\n  total_amount       DECIMAL(18,2),\n  currency           VARCHAR,\n  commodity_code     VARCHAR,\n  _loaded_at         TIMESTAMP\n);\n\nCREATE TABLE raw_ariba_invoice (\n  invoice_id         VARCHAR,\n  invoice_number     VARCHAR,\n  po_id              VARCHAR,\n  po_number          VARCHAR,\n  supplier_system_id VARCHAR,\n  status             VARCHAR,\n  invoiced_at        TIMESTAMP,\n  total_amount       DECIMAL(18,2),\n  currency           VARCHAR,\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-ariba-supplier-line',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Supplier + PO Line Landing', 'en' => 'RAW supplier + PO line landing'],
                'notes' => [
                    'de' => 'Supplier Master Keys; Contact-email = PII in RAW; Line Commodity für Category Spend.',
                    'en' => 'Supplier master keys; contact email = PII in RAW; line commodity for category spend.',
                ],
                'sql' => "CREATE TABLE raw_ariba_supplier (\n  supplier_system_id VARCHAR,\n  erp_vendor_id      VARCHAR,\n  supplier_name      VARCHAR,\n  contact_email      VARCHAR, -- supplier PII\n  active             BOOLEAN,\n  _loaded_at         TIMESTAMP\n);\n\nCREATE TABLE raw_ariba_po_line (\n  po_line_id         VARCHAR,\n  po_id              VARCHAR,\n  line_number        INT,\n  commodity_code     VARCHAR,\n  quantity           DECIMAL(18,4),\n  unit_price         DECIMAL(18,4),\n  line_amount        DECIMAL(18,2),\n  currency           VARCHAR,\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-ariba-po',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact ariba purchase order', 'en' => 'Curated fact ariba purchase order'],
                'notes' => [
                    'de' => 'Procure-Grain — Supplier Pflicht für Vendor Spend; ohne Attachment/Comment Bodies.',
                    'en' => 'Procure grain — supplier required for vendor spend; no attachment/comment bodies.',
                ],
                'sql' => "CREATE TABLE curated_fct_ariba_purchase_order AS\nSELECT\n  p.po_id,\n  p.po_number,\n  p.supplier_system_id,\n  p.requester_user_id,\n  p.status,\n  p.ordered_at,\n  p.total_amount,\n  p.currency,\n  p.commodity_code,\n  CASE WHEN p.status NOT IN ('Composing', 'Canceled') THEN TRUE ELSE FALSE END AS is_ordered\nFROM raw_ariba_purchase_order p\nWHERE p.supplier_system_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-ariba',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim supplier / invoice fact', 'en' => 'Curated dim supplier / invoice fact'],
                'notes' => [
                    'de' => 'Supplier ohne Contact-email; Invoice mit PO-Join für Match-Analysen.',
                    'en' => 'Supplier without contact email; invoice with PO join for match analysis.',
                ],
                'sql' => "CREATE TABLE curated_dim_ariba_supplier AS\nSELECT\n  supplier_system_id,\n  erp_vendor_id,\n  supplier_name,\n  active\n  -- omit contact_email from default analytics dims\nFROM raw_ariba_supplier;\n\nCREATE TABLE curated_fct_ariba_invoice AS\nSELECT\n  i.invoice_id,\n  i.invoice_number,\n  i.po_id,\n  i.po_number,\n  i.supplier_system_id,\n  i.status,\n  i.invoiced_at,\n  i.total_amount,\n  i.currency,\n  p.total_amount AS po_total_amount,\n  CASE\n    WHEN i.po_id IS NULL THEN TRUE\n    ELSE FALSE\n  END AS is_non_po,\n  CASE\n    WHEN p.po_id IS NOT NULL\n     AND ABS(i.total_amount - p.total_amount) / NULLIF(p.total_amount, 0) > 0.05\n    THEN TRUE\n    ELSE FALSE\n  END AS amount_mismatch_flag\nFROM raw_ariba_invoice i\nLEFT JOIN raw_ariba_purchase_order p ON p.po_id = i.po_id;",
            ],
            [
                'id' => 'curated-measure-ariba',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'PO Spend, Invoice Total, Match Exceptions — Periodenfilter anpassen.',
                    'en' => 'PO spend, invoice total, match exceptions — adapt period filters.',
                ],
                'sql' => "-- Ordered PO spend in period\nSELECT SUM(total_amount) AS po_spend\nFROM curated_fct_ariba_purchase_order\nWHERE is_ordered = TRUE\n  AND ordered_at BETWEEN :period_start AND :period_end;\n\n-- Invoice total in period\nSELECT SUM(total_amount) AS invoice_total\nFROM curated_fct_ariba_invoice\nWHERE invoiced_at BETWEEN :period_start AND :period_end;\n\n-- Amount mismatch / non-PO exception count\nSELECT COUNT(*) AS match_exceptions\nFROM curated_fct_ariba_invoice\nWHERE amount_mismatch_flag = TRUE\n   OR is_non_po = TRUE;",
            ],
        ],
    ],

    'coupa' => [
        'sqlExamples' => [
            [
                'id' => 'raw-coupa-po-invoice',
                'stage' => 'raw',
                'title' => ['de' => 'RAW PO + Invoice Landing', 'en' => 'RAW PO + invoice landing'],
                'notes' => [
                    'de' => 'REST-Shape — requester als user id; keine Attachment/Invoice Image Binaries.',
                    'en' => 'REST shape — requester as user id; no attachment/invoice image binaries.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Coupa API extract\nCREATE TABLE raw_coupa_purchase_order (\n  po_id              VARCHAR,\n  po_number          VARCHAR,\n  supplier_id        VARCHAR,\n  requester_user_id  VARCHAR, -- workforce key\n  status             VARCHAR,\n  ordered_at         TIMESTAMP,\n  total_amount       DECIMAL(18,2),\n  currency           VARCHAR,\n  contract_id        VARCHAR,\n  is_non_catalog     BOOLEAN,\n  _loaded_at         TIMESTAMP\n);\n\nCREATE TABLE raw_coupa_invoice (\n  invoice_id         VARCHAR,\n  invoice_number     VARCHAR,\n  po_id              VARCHAR,\n  supplier_id        VARCHAR,\n  status             VARCHAR,\n  invoiced_at        TIMESTAMP,\n  total_amount       DECIMAL(18,2),\n  currency           VARCHAR,\n  requires_po        BOOLEAN,\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-coupa-supplier-line-user',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Supplier + Line + User Landing', 'en' => 'RAW supplier + line + user landing'],
                'notes' => [
                    'de' => 'COA/Commodity auf Line; User-email = Workforce-PII in RAW.',
                    'en' => 'COA/commodity on line; user email = workforce PII in RAW.',
                ],
                'sql' => "CREATE TABLE raw_coupa_supplier (\n  supplier_id        VARCHAR,\n  supplier_number    VARCHAR,\n  supplier_name      VARCHAR,\n  contact_email      VARCHAR, -- supplier PII\n  active             BOOLEAN,\n  _loaded_at         TIMESTAMP\n);\n\nCREATE TABLE raw_coupa_po_line (\n  po_line_id         VARCHAR,\n  po_id              VARCHAR,\n  line_number        INT,\n  account_code       VARCHAR,\n  commodity_id       VARCHAR,\n  line_amount        DECIMAL(18,2),\n  currency           VARCHAR,\n  _loaded_at         TIMESTAMP\n);\n\nCREATE TABLE raw_coupa_user (\n  user_id            VARCHAR,\n  login              VARCHAR,\n  email              VARCHAR, -- workforce PII\n  active             BOOLEAN,\n  _loaded_at         TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-coupa-invoice',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact coupa invoice', 'en' => 'Curated fact coupa invoice'],
                'notes' => [
                    'de' => 'Spend-Grain mit Maverick/Non-PO Flags — ohne Approval Comment Bodies.',
                    'en' => 'Spend grain with maverick/non-PO flags — no approval comment bodies.',
                ],
                'sql' => "CREATE TABLE curated_fct_coupa_invoice AS\nSELECT\n  i.invoice_id,\n  i.invoice_number,\n  i.po_id,\n  i.supplier_id,\n  i.status,\n  i.invoiced_at,\n  i.total_amount,\n  i.currency,\n  i.requires_po,\n  CASE\n    WHEN i.requires_po = TRUE AND i.po_id IS NULL THEN TRUE\n    ELSE FALSE\n  END AS is_maverick_non_po,\n  p.total_amount AS po_total_amount,\n  p.is_non_catalog,\n  p.contract_id,\n  CASE\n    WHEN p.po_id IS NOT NULL\n     AND ABS(i.total_amount - p.total_amount) / NULLIF(p.total_amount, 0) > 0.05\n    THEN TRUE\n    ELSE FALSE\n  END AS amount_mismatch_flag\nFROM raw_coupa_invoice i\nLEFT JOIN raw_coupa_purchase_order p ON p.po_id = i.po_id\nWHERE i.status NOT IN ('draft', 'voided');",
            ],
            [
                'id' => 'curated-dims-coupa',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim supplier / user / PO fact', 'en' => 'Curated dim supplier / user / PO fact'],
                'notes' => [
                    'de' => 'Supplier/User ohne email; PO Fact für Catalog/Contract Compliance.',
                    'en' => 'Supplier/user without email; PO fact for catalog/contract compliance.',
                ],
                'sql' => "CREATE TABLE curated_dim_coupa_supplier AS\nSELECT\n  supplier_id,\n  supplier_number,\n  supplier_name,\n  active\n  -- omit contact_email from default analytics dims\nFROM raw_coupa_supplier;\n\nCREATE TABLE curated_dim_coupa_user AS\nSELECT\n  user_id,\n  login,\n  active\n  -- omit email from default analytics dims\nFROM raw_coupa_user;\n\nCREATE TABLE curated_fct_coupa_purchase_order AS\nSELECT\n  po_id,\n  po_number,\n  supplier_id,\n  requester_user_id,\n  status,\n  ordered_at,\n  total_amount,\n  currency,\n  contract_id,\n  is_non_catalog,\n  CASE WHEN contract_id IS NULL OR is_non_catalog = TRUE THEN TRUE ELSE FALSE END AS maverick_indicator\nFROM raw_coupa_purchase_order\nWHERE supplier_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-coupa',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Invoice Spend, Maverick Rate, PO Spend — Periodenfilter anpassen.',
                    'en' => 'Invoice spend, maverick rate, PO spend — adapt period filters.',
                ],
                'sql' => "-- Invoice spend in period\nSELECT SUM(total_amount) AS invoice_spend\nFROM curated_fct_coupa_invoice\nWHERE invoiced_at BETWEEN :period_start AND :period_end;\n\n-- Maverick / non-PO invoice rate\nSELECT\n  SUM(CASE WHEN is_maverick_non_po THEN 1 ELSE 0 END) * 1.0\n    / NULLIF(COUNT(*), 0) AS maverick_invoice_rate\nFROM curated_fct_coupa_invoice\nWHERE invoiced_at BETWEEN :period_start AND :period_end;\n\n-- PO spend with maverick indicator\nSELECT\n  SUM(total_amount) AS po_spend,\n  SUM(CASE WHEN maverick_indicator THEN total_amount ELSE 0 END) AS maverick_po_spend\nFROM curated_fct_coupa_purchase_order\nWHERE ordered_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],
];
