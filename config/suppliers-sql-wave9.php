<?php

/**
 * Wave 9 warehouse SQL examples — ERP/Finance/HCM source-native (Landing RAW + Curated).
 * Warehouse-neutral dialect — not DATEV EXTF/ASCII layout, Sage API schema, or Oracle Fusion OTBI/FBDI dumps.
 * Curated facts = voucher/master-data KPIs — NEVER salary/compensation cleartext or unhashed tax IDs.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'datev' => [
        'sqlExamples' => [
            [
                'id' => 'raw-datev-booking-account',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Buchungssatz + Konto Landing', 'en' => 'RAW booking record + account landing'],
                'notes' => [
                    'de' => 'EXTF-Shape — Steuer-ID/IBAN separat in Stammdaten, nicht im Buchungssatz.',
                    'en' => 'EXTF shape — tax ID/IBAN live in master data, not the booking record.',
                ],
                'sql' => "-- Warehouse-neutral RAW from a DATEV EXTF/ASCII export (not the EXTF layout clone)\nCREATE TABLE raw_datev_booking_record (\n  mandant_number       VARCHAR,\n  beleg_feld1          VARCHAR,\n  booking_date         DATE,\n  account_number       VARCHAR,\n  contra_account_number VARCHAR,\n  amount               NUMERIC(15,2),\n  tax_key              VARCHAR,\n  cost_center          VARCHAR,\n  posting_text         VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_datev_account (\n  account_number       VARCHAR,\n  account_name         VARCHAR,\n  account_type         VARCHAR, -- Sachkonto | Personenkonto\n  skr_type             VARCHAR, -- SKR03 | SKR04\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-datev-debtor-creditor',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Debitor + Kreditor Landing', 'en' => 'RAW debtor + creditor landing'],
                'notes' => [
                    'de' => 'PII/sensible Felder markiert — tax_id/iban restriktiv behandeln.',
                    'en' => 'PII/sensitive fields flagged — treat tax_id/iban restrictively.',
                ],
                'sql' => "CREATE TABLE raw_datev_debtor (\n  debtor_number        VARCHAR,\n  mandant_number       VARCHAR,\n  name                 VARCHAR, -- direct PII\n  address              VARCHAR, -- direct PII\n  tax_id               VARCHAR, -- sensitive — hash beyond RAW\n  iban                 VARCHAR, -- sensitive — restrict beyond RAW\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_datev_creditor (\n  creditor_number      VARCHAR,\n  mandant_number       VARCHAR,\n  name                 VARCHAR, -- direct PII\n  address              VARCHAR, -- direct PII\n  tax_id               VARCHAR, -- sensitive — hash beyond RAW\n  iban                 VARCHAR, -- sensitive — restrict beyond RAW\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_datev_tax_key (\n  code                 VARCHAR,\n  rate                 NUMERIC(5,4),\n  description          VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-datev-booking',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact datev booking', 'en' => 'Curated fact DATEV booking'],
                'notes' => [
                    'de' => 'Voucher-Grain mit Kontoart-Join — kein Debitoren-/Kreditoren-Klartext.',
                    'en' => 'Voucher grain with account-type join — no debtor/creditor cleartext.',
                ],
                'sql' => "CREATE TABLE curated_fct_datev_booking AS\nSELECT\n  b.mandant_number,\n  b.beleg_feld1,\n  b.booking_date,\n  b.account_number,\n  a.account_type,\n  b.amount,\n  b.tax_key,\n  b.cost_center\nFROM raw_datev_booking_record b\nJOIN raw_datev_account a ON a.account_number = b.account_number;",
            ],
            [
                'id' => 'curated-dims-datev',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim debtor/creditor (hashed)', 'en' => 'Curated dim debtor/creditor (hashed)'],
                'notes' => [
                    'de' => 'Nur Nummer + gehashte Steuer-ID; Name/Adresse/IBAN bleiben in RAW.',
                    'en' => 'Number + hashed tax ID only; name/address/IBAN stay in RAW.',
                ],
                'sql' => "CREATE TABLE curated_dim_datev_debtor AS\nSELECT\n  debtor_number,\n  mandant_number,\n  SHA2(tax_id, 256) AS tax_id_hash\n  -- omit name, address, iban from default analytics dims\nFROM raw_datev_debtor;\n\nCREATE TABLE curated_dim_datev_creditor AS\nSELECT\n  creditor_number,\n  mandant_number,\n  SHA2(tax_id, 256) AS tax_id_hash\n  -- omit name, address, iban from default analytics dims\nFROM raw_datev_creditor;",
            ],
            [
                'id' => 'curated-measure-datev',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Umsatz gebucht, Offene Posten, Umsatzsteuer-Zahllast — Periodenfilter anpassen.',
                    'en' => 'Revenue booked, open items, VAT liability — adapt period filters.',
                ],
                'sql' => "-- Revenue booked in period\nSELECT SUM(amount) AS revenue_booked\nFROM curated_fct_datev_booking\nWHERE account_type = 'Erlöskonto'\n  AND booking_date BETWEEN :period_start AND :period_end;\n\n-- VAT liability in period\nSELECT SUM(b.amount * t.rate) AS vat_liability\nFROM curated_fct_datev_booking b\nJOIN raw_datev_tax_key t ON t.code = b.tax_key\nWHERE b.booking_date BETWEEN :period_start AND :period_end;\n\n-- Open items debtors (requires clearing flag from source export)\nSELECT SUM(amount) AS open_items_debtors\nFROM curated_fct_datev_booking\nWHERE account_number IN (SELECT debtor_number FROM curated_dim_datev_debtor);",
            ],
        ],
    ],

    'sage' => [
        'sqlExamples' => [
            [
                'id' => 'raw-sage-gl-journal',
                'stage' => 'raw',
                'title' => ['de' => 'RAW GL Account + Journal Entry Landing', 'en' => 'RAW GL account + journal entry landing'],
                'notes' => [
                    'de' => 'API-Shape — Debit/Credit getrennt, Cost Center und Tax Code als Dimensionen.',
                    'en' => 'API shape — debit/credit separated, cost center and tax code as dimensions.',
                ],
                'sql' => "-- Warehouse-neutral RAW from a Sage API extract (not the API DDL clone)\nCREATE TABLE raw_sage_gl_account (\n  account_code         VARCHAR,\n  account_name         VARCHAR,\n  account_type         VARCHAR, -- asset | liability | revenue | expense\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_sage_journal_entry (\n  id                   VARCHAR,\n  entry_date           DATE,\n  account_code         VARCHAR,\n  debit_amount         NUMERIC(15,2),\n  credit_amount        NUMERIC(15,2),\n  cost_center          VARCHAR,\n  tax_code             VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-sage-customer-supplier-invoice',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Customer/Supplier + Invoice Landing', 'en' => 'RAW customer/supplier + invoice landing'],
                'notes' => [
                    'de' => 'Kontakt/Bankdaten als PII markiert — Invoice-Meta ohne Anhänge.',
                    'en' => 'Contact/bank data flagged as PII — invoice meta without attachments.',
                ],
                'sql' => "CREATE TABLE raw_sage_customer (\n  customer_id          VARCHAR,\n  name                 VARCHAR,\n  contact_email        VARCHAR, -- PII\n  billing_address      VARCHAR, -- PII\n  credit_limit         NUMERIC(15,2),\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_sage_supplier (\n  supplier_id          VARCHAR,\n  name                 VARCHAR,\n  contact_email        VARCHAR, -- PII\n  bank_details         VARCHAR, -- sensitive\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_sage_sales_invoice (\n  id                   VARCHAR,\n  customer_id          VARCHAR,\n  invoice_date         DATE,\n  due_date             DATE,\n  amount               NUMERIC(15,2),\n  status               VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no PDF attachment\n);\n\nCREATE TABLE raw_sage_purchase_invoice (\n  id                   VARCHAR,\n  supplier_id          VARCHAR,\n  invoice_date         DATE,\n  due_date             DATE,\n  amount               NUMERIC(15,2),\n  status               VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no PDF attachment\n);",
            ],
            [
                'id' => 'curated-fct-sage-journal',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact sage journal entry', 'en' => 'Curated fact Sage journal entry'],
                'notes' => [
                    'de' => 'Voucher-Grain mit Account-Type-Join für GL-Rollups.',
                    'en' => 'Voucher grain with account-type join for GL rollups.',
                ],
                'sql' => "CREATE TABLE curated_fct_sage_journal_entry AS\nSELECT\n  j.id,\n  j.entry_date,\n  j.account_code,\n  a.account_type,\n  j.debit_amount,\n  j.credit_amount,\n  j.cost_center,\n  j.tax_code\nFROM raw_sage_journal_entry j\nJOIN raw_sage_gl_account a ON a.account_code = j.account_code;",
            ],
            [
                'id' => 'curated-dims-sage',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim customer/supplier (no contact PII)', 'en' => 'Curated dim customer/supplier (no contact PII)'],
                'notes' => [
                    'de' => 'Nur ID + credit_limit; Kontakt/Bankdaten bleiben in RAW.',
                    'en' => 'ID + credit_limit only; contact/bank data stay in RAW.',
                ],
                'sql' => "CREATE TABLE curated_dim_sage_customer AS\nSELECT\n  customer_id,\n  credit_limit\n  -- omit name, contact_email, billing_address from default analytics dims\nFROM raw_sage_customer;\n\nCREATE TABLE curated_dim_sage_supplier AS\nSELECT\n  supplier_id\n  -- omit name, contact_email, bank_details from default analytics dims\nFROM raw_sage_supplier;\n\nCREATE TABLE curated_fct_sage_sales_invoice AS\nSELECT\n  id, customer_id, invoice_date, due_date, amount, status\nFROM raw_sage_sales_invoice\nWHERE customer_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-sage',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Revenue Invoiced, AR/AP Outstanding, Opex je Cost Center — Periodenfilter anpassen.',
                    'en' => 'Revenue invoiced, AR/AP outstanding, opex by cost center — adapt period filters.',
                ],
                'sql' => "-- Revenue invoiced in period\nSELECT SUM(amount) AS revenue_invoiced\nFROM curated_fct_sage_sales_invoice\nWHERE invoice_date BETWEEN :period_start AND :period_end;\n\n-- AR outstanding\nSELECT SUM(amount) AS ar_outstanding\nFROM curated_fct_sage_sales_invoice\nWHERE status <> 'paid';\n\n-- Opex by cost center in period\nSELECT\n  cost_center,\n  SUM(debit_amount - credit_amount) AS opex\nFROM curated_fct_sage_journal_entry\nWHERE account_type = 'expense'\n  AND entry_date BETWEEN :period_start AND :period_end\nGROUP BY cost_center;",
            ],
        ],
    ],

    'oracle-fusion' => [
        'sqlExamples' => [
            [
                'id' => 'raw-fusion-gl-journal',
                'stage' => 'raw',
                'title' => ['de' => 'RAW GL Journal Landing', 'en' => 'RAW GL journal landing'],
                'notes' => [
                    'de' => 'ERP Cloud REST/FBDI-Shape — Ledger und Business Unit als Dimensionen.',
                    'en' => 'ERP Cloud REST/FBDI shape — ledger and business unit as dimensions.',
                ],
                'sql' => "-- Warehouse-neutral RAW from an Oracle Fusion ERP Cloud extract (not OTBI/FBDI DDL clone)\nCREATE TABLE raw_fusion_gl_journal (\n  journal_id           VARCHAR,\n  ledger               VARCHAR,\n  accounting_period    VARCHAR,\n  account_combination  VARCHAR,\n  entered_dr           NUMERIC(15,2),\n  entered_cr           NUMERIC(15,2),\n  business_unit        VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-fusion-worker-invoice',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Worker + AP/AR Invoice Landing', 'en' => 'RAW worker + AP/AR invoice landing'],
                'notes' => [
                    'de' => 'Worker-PII markiert; Compensation-Felder nicht Teil des Standard-Extracts.',
                    'en' => 'Worker PII flagged; compensation fields are not part of the standard extract.',
                ],
                'sql' => "CREATE TABLE raw_fusion_worker (\n  person_id            VARCHAR,\n  person_number        VARCHAR,\n  display_name         VARCHAR, -- direct PII\n  work_email           VARCHAR, -- direct PII\n  national_identifier  VARCHAR, -- sensitive — hash beyond RAW\n  hire_date            DATE,\n  business_unit        VARCHAR,\n  department            VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no compensation / element entry fields\n);\n\nCREATE TABLE raw_fusion_assignment (\n  assignment_id        VARCHAR,\n  person_id            VARCHAR,\n  position             VARCHAR,\n  grade                VARCHAR,\n  assignment_status    VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- salary_basis is a reference only, never the compensation amount\n);\n\nCREATE TABLE raw_fusion_ap_invoice (\n  invoice_id           VARCHAR,\n  supplier_id          VARCHAR,\n  invoice_date         DATE,\n  amount               NUMERIC(15,2),\n  status               VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-fusion-gl-journal',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact fusion GL journal', 'en' => 'Curated fact Fusion GL journal'],
                'notes' => [
                    'de' => 'Voucher-Grain mit Business-Unit-Dimension für Konsolidierung.',
                    'en' => 'Voucher grain with business-unit dimension for consolidation.',
                ],
                'sql' => "CREATE TABLE curated_fct_fusion_gl_journal AS\nSELECT\n  journal_id,\n  ledger,\n  accounting_period,\n  account_combination,\n  entered_dr,\n  entered_cr,\n  business_unit\nFROM raw_fusion_gl_journal\nWHERE business_unit IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-fusion',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim worker (no PII, no compensation)', 'en' => 'Curated dim worker (no PII, no compensation)'],
                'notes' => [
                    'de' => 'Nur person_id/-number, Org-Dims; national_identifier gehasht separat gehalten.',
                    'en' => 'person_id/-number and org dims only; national_identifier kept hashed separately.',
                ],
                'sql' => "CREATE TABLE curated_dim_fusion_worker AS\nSELECT\n  person_id,\n  person_number,\n  business_unit,\n  department,\n  hire_date\n  -- omit display_name, work_email, national_identifier from default analytics dims\nFROM raw_fusion_worker;\n\nCREATE TABLE curated_fct_fusion_assignment AS\nSELECT\n  a.assignment_id,\n  a.person_id,\n  a.position,\n  a.grade,\n  a.assignment_status\n  -- no salary_basis amount, no compensation join\nFROM raw_fusion_assignment a\nWHERE a.person_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-fusion',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Revenue Booked, AP Liability, Headcount, New Hires — Periodenfilter anpassen.',
                    'en' => 'Revenue booked, AP liability, headcount, new hires — adapt period filters.',
                ],
                'sql' => "-- GL revenue booked in period\nSELECT SUM(entered_cr - entered_dr) AS revenue_booked\nFROM curated_fct_fusion_gl_journal\nWHERE account_combination LIKE 'revenue%'\n  AND accounting_period IN (:period_start_period, :period_end_period);\n\n-- AP liability outstanding\nSELECT SUM(amount) AS ap_liability_outstanding\nFROM raw_fusion_ap_invoice\nWHERE status <> 'paid';\n\n-- Active headcount by department\nSELECT\n  w.department,\n  COUNT(*) AS active_headcount\nFROM curated_dim_fusion_worker w\nJOIN curated_fct_fusion_assignment a ON a.person_id = w.person_id\nWHERE a.assignment_status = 'active'\nGROUP BY w.department;\n\n-- New hires in period\nSELECT COUNT(*) AS new_hires\nFROM curated_dim_fusion_worker\nWHERE hire_date BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'personio' => [
        'sqlExamples' => [
            [
                'id' => 'raw-personio-employee-position',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Employee + Position Landing', 'en' => 'RAW employee + position landing'],
                'notes' => [
                    'de' => 'API-Shape — direkte PII und sensible IDs markiert.',
                    'en' => 'API shape — direct PII and sensitive IDs flagged.',
                ],
                'sql' => "-- Warehouse-neutral RAW from a Personio API extract (not the API DDL clone)\nCREATE TABLE raw_personio_employee (\n  id                   VARCHAR,\n  employee_number      VARCHAR,\n  first_name           VARCHAR, -- direct PII\n  last_name            VARCHAR, -- direct PII\n  email                VARCHAR, -- direct PII\n  status               VARCHAR, -- active | inactive | onboarding\n  department           VARCHAR,\n  hire_date            DATE,\n  termination_date     DATE,\n  tax_id               VARCHAR, -- sensitive — hash beyond RAW\n  iban                 VARCHAR, -- sensitive — restrict beyond RAW\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_personio_position (\n  id                   VARCHAR,\n  name                 VARCHAR,\n  department           VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-personio-absence-compensation',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Absence + Compensation Landing', 'en' => 'RAW absence + compensation landing'],
                'notes' => [
                    'de' => 'Absence ohne Diagnose-Freitext; Compensation-Betrag nur restriktiv in RAW.',
                    'en' => 'Absence without diagnosis free text; compensation amount only restricted in RAW.',
                ],
                'sql' => "CREATE TABLE raw_personio_absence (\n  id                   VARCHAR,\n  employee_id          VARCHAR,\n  absence_type         VARCHAR, -- vacation | sick | parental\n  start_date           DATE,\n  end_date             DATE,\n  days_count           NUMERIC(5,1),\n  status               VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no diagnosis / free-text reason\n);\n\nCREATE TABLE raw_personio_compensation (\n  id                   VARCHAR,\n  employee_id          VARCHAR,\n  amount               NUMERIC(15,2), -- sensitive — restrict; never beyond RAW\n  compensation_type    VARCHAR,\n  effective_date       DATE,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-personio-absence',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact personio absence', 'en' => 'Curated fact Personio absence'],
                'notes' => [
                    'de' => 'Approved-Grain für Absence-Days-Measure.',
                    'en' => 'Approved grain for the absence-days measure.',
                ],
                'sql' => "CREATE TABLE curated_fct_personio_absence AS\nSELECT\n  a.id,\n  a.employee_id,\n  a.absence_type,\n  a.start_date,\n  a.end_date,\n  a.days_count\nFROM raw_personio_absence a\nWHERE a.status = 'approved'\n  AND a.days_count > 0;",
            ],
            [
                'id' => 'curated-dims-personio',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim employee (no PII, no compensation)', 'en' => 'Curated dim employee (no PII, no compensation)'],
                'notes' => [
                    'de' => 'Nur id/-number, department, status, hire/termination; kein Klartext-PII/Compensation.',
                    'en' => 'id/-number, department, status, hire/termination only; no cleartext PII/compensation.',
                ],
                'sql' => "CREATE TABLE curated_dim_personio_employee AS\nSELECT\n  id,\n  employee_number,\n  status,\n  department,\n  hire_date,\n  termination_date\n  -- omit first_name, last_name, email, tax_id, iban from default analytics dims\nFROM raw_personio_employee;\n\nCREATE TABLE curated_fct_personio_compensation_flag AS\nSELECT\n  employee_id,\n  compensation_type,\n  effective_date,\n  TRUE AS has_compensation_record\n  -- amount intentionally excluded — existence/type only\nFROM raw_personio_compensation;",
            ],
            [
                'id' => 'curated-measure-personio',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Headcount, New Hires, Turnover, Absence Days — Periodenfilter anpassen.',
                    'en' => 'Headcount, new hires, turnover, absence days — adapt period filters.',
                ],
                'sql' => "-- Active headcount by department\nSELECT\n  department,\n  COUNT(*) AS active_headcount\nFROM curated_dim_personio_employee\nWHERE status = 'active'\nGROUP BY department;\n\n-- New hires in period\nSELECT COUNT(*) AS new_hires\nFROM curated_dim_personio_employee\nWHERE hire_date BETWEEN :period_start AND :period_end;\n\n-- Turnover rate in period\nSELECT\n  COUNT(*) FILTER (WHERE termination_date BETWEEN :period_start AND :period_end)::FLOAT\n  / NULLIF((SELECT COUNT(*) FROM curated_dim_personio_employee WHERE status = 'active'), 0) AS turnover_rate\nFROM curated_dim_personio_employee;\n\n-- Absence days total by type in period\nSELECT\n  absence_type,\n  SUM(days_count) AS absence_days_total\nFROM curated_fct_personio_absence\nWHERE start_date BETWEEN :period_start AND :period_end\nGROUP BY absence_type;",
            ],
        ],
    ],
];
