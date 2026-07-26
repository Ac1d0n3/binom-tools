<?php

/**
 * Wave 6 warehouse SQL examples — Banking Core source-native (Landing RAW + Curated).
 * Warehouse-neutral dialect — not T24/Avaloq/Vault/Fusion core DDL clones.
 * Curated facts = account/posting/product metadata KPIs — NOT cleartext payment payloads or secrets.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'temenos' => [
        'sqlExamples' => [
            [
                'id' => 'raw-temenos-customer-account',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Customer + Account Landing', 'en' => 'RAW customer + account landing'],
                'notes' => [
                    'de' => 'T24 CIF/Account Shape — Name/Adresse = PII; keine Payment-Message-Bodies.',
                    'en' => 'T24 CIF/account shape — name/address = PII; no payment message bodies.',
                ],
                'sql' => "-- Warehouse-neutral RAW from T24 CIF/Account extract\nCREATE TABLE raw_temenos_customer (\n  customer_no          VARCHAR,\n  mnemonic             VARCHAR,\n  short_name           VARCHAR, -- PII\n  legal_id             VARCHAR, -- PII\n  sector               VARCHAR,\n  date_of_birth        DATE, -- PII\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_temenos_account (\n  account_number       VARCHAR,\n  customer_no          VARCHAR,\n  category             VARCHAR,\n  currency             VARCHAR,\n  opening_date         DATE,\n  working_balance      DECIMAL(18,2),\n  close_date           DATE,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-temenos-arrangement-stmt-entry',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Arrangement + Statement Entry Landing', 'en' => 'RAW arrangement + statement entry landing'],
                'notes' => [
                    'de' => 'AA.ARRANGEMENT und STMT.ENTRY Shape — Narrative-Freitext nicht landen.',
                    'en' => 'AA.ARRANGEMENT and STMT.ENTRY shape — do not land narrative free text.',
                ],
                'sql' => "CREATE TABLE raw_temenos_arrangement (\n  arrangement_id       VARCHAR,\n  customer_no          VARCHAR,\n  product_id           VARCHAR,\n  start_date           DATE,\n  maturity_date        DATE,\n  status               VARCHAR, -- CURRENT | EXPIRED\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_temenos_statement_entry (\n  entry_id             VARCHAR,\n  account_number       VARCHAR,\n  value_date           DATE,\n  booking_date         DATE,\n  amount_lcy           DECIMAL(18,2),\n  trans_code           VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no narrative / free-text field\n);\n\nCREATE TABLE raw_temenos_limit (\n  limit_reference      VARCHAR,\n  customer_no          VARCHAR,\n  limit_amount         DECIMAL(18,2),\n  expiry_date          DATE,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-temenos-statement-entry',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact temenos statement entry', 'en' => 'Curated fact temenos statement entry'],
                'notes' => [
                    'de' => 'Ledger-KPI-Grain — Account Pflicht; ohne Narrative-Freitext.',
                    'en' => 'Ledger KPI grain — account required; no narrative free text.',
                ],
                'sql' => "CREATE TABLE curated_fct_temenos_statement_entry AS\nSELECT\n  s.entry_id,\n  s.account_number,\n  s.value_date,\n  s.booking_date,\n  s.amount_lcy,\n  s.trans_code\nFROM raw_temenos_statement_entry s\nWHERE s.account_number IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-temenos',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim customer / account fact', 'en' => 'Curated dim customer / account fact'],
                'notes' => [
                    'de' => 'Customer ohne Name/Adresse; Account-Fact mit Overdraft-Flag.',
                    'en' => 'Customer without name/address; account fact with overdraft flag.',
                ],
                'sql' => "CREATE TABLE curated_dim_temenos_customer AS\nSELECT\n  customer_no,\n  sector,\n  date_of_birth\n  -- omit short_name, legal_id from default analytics dims\nFROM raw_temenos_customer;\n\nCREATE TABLE curated_fct_temenos_account AS\nSELECT\n  a.account_number,\n  a.customer_no,\n  a.category,\n  a.currency,\n  a.opening_date,\n  a.working_balance,\n  CASE WHEN a.close_date IS NULL THEN TRUE ELSE FALSE END AS is_active,\n  CASE WHEN a.working_balance < 0 THEN TRUE ELSE FALSE END AS is_overdrawn\nFROM raw_temenos_account a;",
            ],
            [
                'id' => 'curated-measure-temenos',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Accounts Opened, Active Accounts, Postings Count, Limit Utilization — Periodenfilter anpassen.',
                    'en' => 'Accounts opened, active accounts, postings count, limit utilization — adapt period filters.',
                ],
                'sql' => "-- Accounts opened in period\nSELECT COUNT(*) AS accounts_opened\nFROM curated_fct_temenos_account\nWHERE opening_date BETWEEN :period_start AND :period_end;\n\n-- Active accounts (snapshot)\nSELECT COUNT(*) AS active_accounts\nFROM curated_fct_temenos_account\nWHERE is_active = TRUE;\n\n-- Postings in period\nSELECT COUNT(*) AS postings_count\nFROM curated_fct_temenos_statement_entry\nWHERE value_date BETWEEN :period_start AND :period_end;\n\n-- Limit utilization (needs used_amount from linked overdraft/arrangement balances)\nSELECT\n  SUM(l.used_amount) / NULLIF(SUM(l.limit_amount), 0) AS limit_utilization\nFROM raw_temenos_limit l\nWHERE l.expiry_date IS NULL OR l.expiry_date >= CURRENT_DATE;",
            ],
        ],
    ],

    'avaloq' => [
        'sqlExamples' => [
            [
                'id' => 'raw-avaloq-bp-contract',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Business Partner + Contract Landing', 'en' => 'RAW business partner + contract landing'],
                'notes' => [
                    'de' => 'Avaloq BP/Contract Shape — Name/Adresse = PII; keine Payment-Message-Bodies.',
                    'en' => 'Avaloq BP/contract shape — name/address = PII; no payment message bodies.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Avaloq business partner/contract extract\nCREATE TABLE raw_avaloq_business_partner (\n  bp_id                VARCHAR,\n  name                 VARCHAR, -- PII\n  address              VARCHAR, -- PII\n  tax_id               VARCHAR, -- PII\n  date_of_birth        DATE, -- PII\n  segment              VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_avaloq_contract (\n  contract_id          VARCHAR,\n  bp_id                VARCHAR,\n  contract_type        VARCHAR,\n  currency             VARCHAR,\n  opening_date         DATE,\n  status               VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-avaloq-booking-balance',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Booking + Balance Landing', 'en' => 'RAW booking + balance landing'],
                'notes' => [
                    'de' => 'Booking/Balance Shape — Booking-Freitext nicht landen.',
                    'en' => 'Booking/balance shape — do not land booking free text.',
                ],
                'sql' => "CREATE TABLE raw_avaloq_booking (\n  booking_id           VARCHAR,\n  contract_id          VARCHAR,\n  value_date           DATE,\n  booking_date         DATE,\n  amount               DECIMAL(18,2),\n  booking_type         VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no booking text / reference free text\n);\n\nCREATE TABLE raw_avaloq_balance (\n  balance_id           VARCHAR,\n  contract_id          VARCHAR,\n  balance_date         DATE,\n  balance_amount       DECIMAL(18,2),\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_avaloq_limit (\n  limit_id             VARCHAR,\n  bp_id                VARCHAR,\n  limit_amount         DECIMAL(18,2),\n  expiry_date          DATE,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-avaloq-booking',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact avaloq booking', 'en' => 'Curated fact avaloq booking'],
                'notes' => [
                    'de' => 'Ledger-KPI-Grain — Contract Pflicht; ohne Freitext.',
                    'en' => 'Ledger KPI grain — contract required; no free text.',
                ],
                'sql' => "CREATE TABLE curated_fct_avaloq_booking AS\nSELECT\n  b.booking_id,\n  b.contract_id,\n  b.value_date,\n  b.booking_date,\n  b.amount,\n  b.booking_type\nFROM raw_avaloq_booking b\nWHERE b.contract_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-avaloq',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim business partner / contract fact', 'en' => 'Curated dim business partner / contract fact'],
                'notes' => [
                    'de' => 'BP ohne Name/Adresse; Contract-Fact mit Latest-Balance-Join.',
                    'en' => 'BP without name/address; contract fact with latest-balance join.',
                ],
                'sql' => "CREATE TABLE curated_dim_avaloq_business_partner AS\nSELECT\n  bp_id,\n  segment,\n  date_of_birth\n  -- omit name, address from default analytics dims\nFROM raw_avaloq_business_partner;\n\nCREATE TABLE curated_fct_avaloq_contract AS\nSELECT\n  c.contract_id,\n  c.bp_id,\n  c.contract_type,\n  c.currency,\n  c.opening_date,\n  c.status,\n  bal.balance_amount AS latest_balance\nFROM raw_avaloq_contract c\nLEFT JOIN (\n  SELECT contract_id, balance_amount,\n         ROW_NUMBER() OVER (PARTITION BY contract_id ORDER BY balance_date DESC) AS rn\n  FROM raw_avaloq_balance\n) bal ON bal.contract_id = c.contract_id AND bal.rn = 1;",
            ],
            [
                'id' => 'curated-measure-avaloq',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Contracts Opened, Active Contracts, Bookings Count, Limit Utilization — Periodenfilter anpassen.',
                    'en' => 'Contracts opened, active contracts, bookings count, limit utilization — adapt period filters.',
                ],
                'sql' => "-- Contracts opened in period\nSELECT COUNT(*) AS contracts_opened\nFROM curated_fct_avaloq_contract\nWHERE opening_date BETWEEN :period_start AND :period_end;\n\n-- Active contracts (snapshot)\nSELECT COUNT(*) AS active_contracts\nFROM curated_fct_avaloq_contract\nWHERE status = 'active';\n\n-- Bookings in period\nSELECT COUNT(*) AS bookings_count\nFROM curated_fct_avaloq_booking\nWHERE value_date BETWEEN :period_start AND :period_end;\n\n-- Limit utilization (needs used_amount from linked contract overdrafts)\nSELECT\n  SUM(l.used_amount) / NULLIF(SUM(l.limit_amount), 0) AS limit_utilization\nFROM raw_avaloq_limit l\nWHERE l.expiry_date IS NULL OR l.expiry_date >= CURRENT_DATE;",
            ],
        ],
    ],

    'thought-machine' => [
        'sqlExamples' => [
            [
                'id' => 'raw-vault-customer-account',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Customer + Account Landing', 'en' => 'RAW customer + account landing'],
                'notes' => [
                    'de' => 'Vault Core API Shape — given/family name = PII; keine Payment-Device-Tokens.',
                    'en' => 'Vault Core API shape — given/family name = PII; no payment device tokens.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Vault Core API extract\nCREATE TABLE raw_vault_customer (\n  customer_id          VARCHAR,\n  given_name           VARCHAR, -- PII\n  family_name          VARCHAR, -- PII\n  date_of_birth        DATE, -- PII\n  status               VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_vault_account (\n  account_id           VARCHAR,\n  customer_id          VARCHAR,\n  product_version_id   VARCHAR,\n  status               VARCHAR, -- ACCOUNT_STATUS_OPEN | _CLOSED\n  opening_timestamp    TIMESTAMP,\n  closure_timestamp    TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-vault-posting-balance',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Posting + Balance Landing', 'en' => 'RAW posting + balance landing'],
                'notes' => [
                    'de' => 'Posting/Balance Shape — Instruction Metadata nicht landen.',
                    'en' => 'Posting/balance shape — do not land instruction metadata.',
                ],
                'sql' => "CREATE TABLE raw_vault_posting_instruction_batch (\n  pib_id               VARCHAR,\n  client_batch_id      VARCHAR,\n  value_timestamp      TIMESTAMP,\n  insertion_timestamp  TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_vault_posting (\n  client_transaction_id VARCHAR,\n  pib_id                VARCHAR,\n  account_id            VARCHAR,\n  amount                DECIMAL(18,2),\n  denomination          VARCHAR,\n  phase                 VARCHAR, -- committed | pending_outgoing | pending_incoming\n  _loaded_at            TIMESTAMP\n  -- no instruction_details / metadata free text\n);\n\nCREATE TABLE raw_vault_balance (\n  balance_id           VARCHAR,\n  account_id           VARCHAR,\n  total_debit          DECIMAL(18,2),\n  total_credit         DECIMAL(18,2),\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_vault_restriction (\n  restriction_id                     VARCHAR,\n  account_id                         VARCHAR,\n  restriction_definition_version_id  VARCHAR,\n  effective_timestamp                TIMESTAMP,\n  _loaded_at                         TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-vault-posting',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact vault posting', 'en' => 'Curated fact vault posting'],
                'notes' => [
                    'de' => 'Ledger-KPI-Grain — nur committed Phase; ohne Metadata.',
                    'en' => 'Ledger KPI grain — committed phase only; no metadata.',
                ],
                'sql' => "CREATE TABLE curated_fct_vault_posting AS\nSELECT\n  p.client_transaction_id,\n  p.account_id,\n  p.amount,\n  p.denomination,\n  p.phase,\n  b.value_timestamp\nFROM raw_vault_posting p\nJOIN raw_vault_posting_instruction_batch b ON b.pib_id = p.pib_id\nWHERE p.phase = 'committed';",
            ],
            [
                'id' => 'curated-dims-vault',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim customer / account fact', 'en' => 'Curated dim customer / account fact'],
                'notes' => [
                    'de' => 'Customer ohne Namen; Account-Fact mit Balance-Join.',
                    'en' => 'Customer without names; account fact with balance join.',
                ],
                'sql' => "CREATE TABLE curated_dim_vault_customer AS\nSELECT\n  customer_id,\n  status\n  -- omit given_name, family_name, date_of_birth from default analytics dims\nFROM raw_vault_customer;\n\nCREATE TABLE curated_fct_vault_account AS\nSELECT\n  a.account_id,\n  a.customer_id,\n  a.product_version_id,\n  a.status,\n  a.opening_timestamp,\n  bal.total_credit - bal.total_debit AS net_balance\nFROM raw_vault_account a\nLEFT JOIN raw_vault_balance bal ON bal.account_id = a.account_id;",
            ],
            [
                'id' => 'curated-measure-vault',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Accounts Opened, Active Accounts, Postings Count, Restrictions Active — Periodenfilter anpassen.',
                    'en' => 'Accounts opened, active accounts, postings count, restrictions active — adapt period filters.',
                ],
                'sql' => "-- Accounts opened in period\nSELECT COUNT(*) AS accounts_opened\nFROM curated_fct_vault_account\nWHERE opening_timestamp BETWEEN :period_start AND :period_end;\n\n-- Active accounts (status snapshot)\nSELECT COUNT(*) AS active_accounts\nFROM curated_fct_vault_account\nWHERE status = 'ACCOUNT_STATUS_OPEN';\n\n-- Committed postings in period\nSELECT COUNT(*) AS postings_count\nFROM curated_fct_vault_posting\nWHERE value_timestamp BETWEEN :period_start AND :period_end;\n\n-- Accounts with an active restriction\nSELECT COUNT(DISTINCT account_id) AS restrictions_active\nFROM raw_vault_restriction\nWHERE effective_timestamp <= CURRENT_TIMESTAMP;",
            ],
        ],
    ],

    'finastra' => [
        'sqlExamples' => [
            [
                'id' => 'raw-finastra-customer-account',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Customer (CIF) + Account Landing', 'en' => 'RAW customer (CIF) + account landing'],
                'notes' => [
                    'de' => 'Fusion CIF/Account Shape — ShortName/Address = PII; keine Payment-Message-Bodies.',
                    'en' => 'Fusion CIF/account shape — ShortName/address = PII; no payment message bodies.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Finastra Fusion CIF/account extract\nCREATE TABLE raw_finastra_customer (\n  cif_number           VARCHAR,\n  short_name           VARCHAR, -- PII\n  address              VARCHAR, -- PII\n  tax_id               VARCHAR, -- PII\n  date_of_birth        DATE, -- PII\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_finastra_account (\n  account_number       VARCHAR,\n  cif_number           VARCHAR,\n  product_code         VARCHAR,\n  currency             VARCHAR,\n  open_date            DATE,\n  balance              DECIMAL(18,2),\n  close_date           DATE,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-finastra-facility-gl-entry',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Facility + GL Entry Landing', 'en' => 'RAW facility + GL entry landing'],
                'notes' => [
                    'de' => 'Facility/GL Entry Shape — Narrative-Freitext nicht landen.',
                    'en' => 'Facility/GL entry shape — do not land narrative free text.',
                ],
                'sql' => "CREATE TABLE raw_finastra_facility (\n  facility_id          VARCHAR,\n  cif_number           VARCHAR,\n  product_code         VARCHAR,\n  start_date           DATE,\n  maturity_date        DATE,\n  facility_limit       DECIMAL(18,2),\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_finastra_gl_entry (\n  entry_id             VARCHAR,\n  account_number       VARCHAR,\n  posting_date         DATE,\n  value_date           DATE,\n  amount               DECIMAL(18,2),\n  dr_cr_indicator      VARCHAR, -- D | C\n  batch_id             VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no narrative / description free text\n);\n\nCREATE TABLE raw_finastra_limit (\n  limit_id             VARCHAR,\n  cif_number           VARCHAR,\n  limit_amount         DECIMAL(18,2),\n  expiry_date          DATE,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-finastra-gl-entry',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact finastra GL entry', 'en' => 'Curated fact finastra GL entry'],
                'notes' => [
                    'de' => 'Ledger-KPI-Grain — Account Pflicht; ohne Freitext.',
                    'en' => 'Ledger KPI grain — account required; no free text.',
                ],
                'sql' => "CREATE TABLE curated_fct_finastra_gl_entry AS\nSELECT\n  g.entry_id,\n  g.account_number,\n  g.posting_date,\n  g.value_date,\n  g.amount,\n  g.dr_cr_indicator\nFROM raw_finastra_gl_entry g\nWHERE g.account_number IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-finastra',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim customer / account fact', 'en' => 'Curated dim customer / account fact'],
                'notes' => [
                    'de' => 'Customer ohne ShortName/Address; Account-Fact mit Active-Flag.',
                    'en' => 'Customer without ShortName/address; account fact with active flag.',
                ],
                'sql' => "CREATE TABLE curated_dim_finastra_customer AS\nSELECT\n  cif_number,\n  date_of_birth\n  -- omit short_name, address, tax_id from default analytics dims\nFROM raw_finastra_customer;\n\nCREATE TABLE curated_fct_finastra_account AS\nSELECT\n  a.account_number,\n  a.cif_number,\n  a.product_code,\n  a.currency,\n  a.open_date,\n  a.balance,\n  CASE WHEN a.close_date IS NULL THEN TRUE ELSE FALSE END AS is_active\nFROM raw_finastra_account a;",
            ],
            [
                'id' => 'curated-measure-finastra',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Accounts Opened, Active Accounts, GL Entries Count, Facility Utilization — Periodenfilter anpassen.',
                    'en' => 'Accounts opened, active accounts, GL entries count, facility utilization — adapt period filters.',
                ],
                'sql' => "-- Accounts opened in period\nSELECT COUNT(*) AS accounts_opened\nFROM curated_fct_finastra_account\nWHERE open_date BETWEEN :period_start AND :period_end;\n\n-- Active accounts (snapshot)\nSELECT COUNT(*) AS active_accounts\nFROM curated_fct_finastra_account\nWHERE is_active = TRUE;\n\n-- GL entries in period\nSELECT COUNT(*) AS gl_entries_count\nFROM curated_fct_finastra_gl_entry\nWHERE posting_date BETWEEN :period_start AND :period_end;\n\n-- Facility utilization (needs drawn_amount from linked loan account balances)\nSELECT\n  SUM(f.drawn_amount) / NULLIF(SUM(f.facility_limit), 0) AS facility_utilization\nFROM raw_finastra_facility f\nWHERE f.maturity_date IS NULL OR f.maturity_date >= CURRENT_DATE;",
            ],
        ],
    ],
];
