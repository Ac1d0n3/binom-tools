<?php

/**
 * Wave 7 warehouse SQL examples — Markets & Insurance source-native (Landing RAW + Curated).
 * Warehouse-neutral dialect — not Murex Datamart DDL / FIS Profile schema / Guidewire gw / Duck Creek XSD dumps.
 * Curated facts = aggregated exposure/premium/incurred KPIs — NOT raw trade tickets, claim narratives, or medical notes.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'murex' => [
        'sqlExamples' => [
            [
                'id' => 'raw-murex-trade-position',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Trade + Position Landing', 'en' => 'RAW trade + position landing'],
                'notes' => [
                    'de' => 'Datamart-Shape — Ticket-Freitext/Broker-Notes nicht laden; notionalAmount ist geschäftssensibel.',
                    'en' => 'Datamart shape — ticket free text/broker notes not loaded; notionalAmount is business-sensitive.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Murex Datamart extract (not MxML DDL clone)\nCREATE TABLE raw_murex_trade (\n  deal_id              VARCHAR,\n  trade_date           DATE,\n  value_date           DATE,\n  product_type         VARCHAR,\n  notional_amount      NUMERIC, -- business-sensitive\n  currency             VARCHAR,\n  counterparty_id      VARCHAR,\n  book_id              VARCHAR,\n  trader_id            VARCHAR, -- workforce PII\n  status               VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no ticket free text / broker notes\n);\n\nCREATE TABLE raw_murex_position (\n  position_id          VARCHAR,\n  book_id              VARCHAR,\n  instrument_id        VARCHAR,\n  position_date        DATE,\n  quantity             NUMERIC,\n  market_value         NUMERIC,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-murex-counterparty-risk',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Counterparty + Risk Result Landing', 'en' => 'RAW counterparty + risk result landing'],
                'notes' => [
                    'de' => 'Counterparty-Name/LEI geschäftssensibel; Confirmation nur Meta, keine FpML/SWIFT-Payloads.',
                    'en' => 'Counterparty name/LEI business-sensitive; confirmation meta only, no FpML/SWIFT payloads.',
                ],
                'sql' => "CREATE TABLE raw_murex_counterparty (\n  counterparty_id      VARCHAR,\n  lei                  VARCHAR, -- business-sensitive\n  name                 VARCHAR, -- business-sensitive\n  counterparty_type    VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_murex_risk_result (\n  risk_result_id       VARCHAR,\n  book_id              VARCHAR,\n  as_of_date           DATE,\n  pnl_amount           NUMERIC,\n  var_contribution     NUMERIC,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_murex_confirmation_meta (\n  confirmation_id      VARCHAR,\n  trade_id             VARCHAR,\n  match_status         VARCHAR,\n  confirmed_at         TIMESTAMP,\n  _loaded_at           TIMESTAMP\n  -- no raw FpML/SWIFT payload\n);",
            ],
            [
                'id' => 'curated-fct-murex-position',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact murex position', 'en' => 'Curated fact murex position'],
                'notes' => [
                    'de' => 'Exposure-Grain — Book Pflicht; für MTM- und VaR-Reports.',
                    'en' => 'Exposure grain — book required; for MTM and VaR reports.',
                ],
                'sql' => "CREATE TABLE curated_fct_murex_position AS\nSELECT\n  p.position_id,\n  p.book_id,\n  p.instrument_id,\n  p.position_date,\n  p.quantity,\n  p.market_value\nFROM raw_murex_position p\nWHERE p.book_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-murex',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim counterparty / trade fact', 'en' => 'Curated dim counterparty / trade fact'],
                'notes' => [
                    'de' => 'Counterparty ohne Name/LEI im Default-Mart; Trade ohne Trader-Klartext.',
                    'en' => 'Counterparty without name/LEI in the default mart; trade without trader cleartext.',
                ],
                'sql' => "CREATE TABLE curated_dim_murex_counterparty AS\nSELECT\n  counterparty_id,\n  counterparty_type\n  -- omit lei, name from default analytics dims\nFROM raw_murex_counterparty;\n\nCREATE TABLE curated_fct_murex_trade AS\nSELECT\n  t.deal_id,\n  t.trade_date,\n  t.value_date,\n  t.product_type,\n  t.notional_amount,\n  t.currency,\n  t.counterparty_id,\n  t.book_id,\n  t.status,\n  CASE WHEN t.status = 'live' THEN TRUE ELSE FALSE END AS is_live\n  -- omit trader_id from default analytics facts\nFROM raw_murex_trade t\nWHERE t.book_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-murex',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Notional Outstanding, Daily P&L, Unmatched Confirmations — Periodenfilter anpassen.',
                    'en' => 'Notional outstanding, daily P&L, unmatched confirmations — adapt period filters.',
                ],
                'sql' => "-- Notional outstanding by book\nSELECT\n  book_id,\n  SUM(notional_amount) AS notional_outstanding\nFROM curated_fct_murex_trade\nWHERE is_live = TRUE\nGROUP BY book_id;\n\n-- Daily P&L by book\nSELECT\n  book_id,\n  SUM(pnl_amount) AS daily_pnl\nFROM raw_murex_risk_result\nWHERE as_of_date = :as_of_date\nGROUP BY book_id;\n\n-- Unmatched confirmations (settlement risk)\nSELECT COUNT(*) AS unmatched_confirmations\nFROM raw_murex_confirmation_meta\nWHERE match_status <> 'matched';",
            ],
        ],
    ],

    'fis' => [
        'sqlExamples' => [
            [
                'id' => 'raw-fis-customer-account',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Customer + Account Landing', 'en' => 'RAW customer + account landing'],
                'notes' => [
                    'de' => 'Core-Banking CIF-Shape — taxId/DOB = direkte PII; keine KYC-Dokumente.',
                    'en' => 'Core-banking CIF shape — taxId/DOB = direct PII; no KYC documents.',
                ],
                'sql' => "-- Warehouse-neutral RAW from FIS Profile core-banking extract\nCREATE TABLE raw_fis_customer (\n  customer_id          VARCHAR,\n  tax_id               VARCHAR, -- direct PII\n  date_of_birth        DATE,    -- direct PII\n  full_name            VARCHAR, -- direct PII\n  address              VARCHAR, -- direct PII\n  segment              VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_fis_account (\n  account_id           VARCHAR,\n  customer_id          VARCHAR,\n  product_id           VARCHAR,\n  branch_id            VARCHAR,\n  open_date            DATE,\n  status               VARCHAR,\n  balance              NUMERIC,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-fis-transaction-payment',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Transaction + Payment Landing', 'en' => 'RAW transaction + payment landing'],
                'notes' => [
                    'de' => 'Postings ohne Statement-Text; Payment-Beneficiary ist PII; Card nur maskierte PAN.',
                    'en' => 'Postings without statement text; payment beneficiary is PII; card masked PAN only.',
                ],
                'sql' => "CREATE TABLE raw_fis_transaction (\n  transaction_id       VARCHAR,\n  account_id           VARCHAR,\n  posting_date         DATE,\n  amount               NUMERIC,\n  transaction_type     VARCHAR,\n  channel              VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no statement text / free-text memo\n);\n\nCREATE TABLE raw_fis_payment (\n  payment_id           VARCHAR,\n  account_id           VARCHAR,\n  beneficiary_account  VARCHAR, -- PII\n  amount               NUMERIC,\n  payment_type         VARCHAR,\n  status               VARCHAR,\n  value_date           DATE,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_fis_card (\n  card_id              VARCHAR,\n  account_id           VARCHAR,\n  masked_pan           VARCHAR, -- PCI scope, masked only\n  card_status          VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- never store full PAN / CVV\n);",
            ],
            [
                'id' => 'curated-fct-fis-transaction',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact fis transaction', 'en' => 'Curated fact fis transaction'],
                'notes' => [
                    'de' => 'Volumen-Grain — Account Pflicht; ohne Freitext-Memos.',
                    'en' => 'Volume grain — account required; without free-text memos.',
                ],
                'sql' => "CREATE TABLE curated_fct_fis_transaction AS\nSELECT\n  t.transaction_id,\n  t.account_id,\n  t.posting_date,\n  t.amount,\n  t.transaction_type,\n  t.channel\nFROM raw_fis_transaction t\nWHERE t.account_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-fis',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim customer / payment fact', 'en' => 'Curated dim customer / payment fact'],
                'notes' => [
                    'de' => 'Customer ohne taxId/Name; Payment ohne Beneficiary-Klartext.',
                    'en' => 'Customer without taxId/name; payment without beneficiary cleartext.',
                ],
                'sql' => "CREATE TABLE curated_dim_fis_customer AS\nSELECT\n  customer_id,\n  segment\n  -- omit tax_id, date_of_birth, full_name, address from default analytics dims\nFROM raw_fis_customer;\n\nCREATE TABLE curated_fct_fis_payment AS\nSELECT\n  p.payment_id,\n  p.account_id,\n  p.amount,\n  p.payment_type,\n  p.status,\n  p.value_date,\n  CASE WHEN p.status = 'completed' THEN TRUE ELSE FALSE END AS is_completed\n  -- omit beneficiary_account from default analytics facts\nFROM raw_fis_payment p\nWHERE p.account_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-fis',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Deposit Balance, Transaction Volume, Payment Count — Periodenfilter anpassen.',
                    'en' => 'Deposit balance, transaction volume, payment count — adapt period filters.',
                ],
                'sql' => "-- Deposit balance outstanding\nSELECT SUM(a.balance) AS deposit_balance_outstanding\nFROM raw_fis_account a\nJOIN raw_fis_product p ON p.product_id = a.product_id\nWHERE p.product_type = 'deposit'\n  AND a.status = 'active';\n\n-- Transaction volume in period\nSELECT COUNT(*) AS transaction_volume\nFROM curated_fct_fis_transaction\nWHERE posting_date BETWEEN :period_start AND :period_end;\n\n-- Completed payments in period\nSELECT COUNT(*) AS payment_count\nFROM curated_fct_fis_payment\nWHERE is_completed = TRUE\n  AND value_date BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'guidewire' => [
        'sqlExamples' => [
            [
                'id' => 'raw-guidewire-account-policy',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Account + Policy Landing', 'en' => 'RAW account + policy landing'],
                'notes' => [
                    'de' => 'PolicyCenter-Shape — insuredName/address = direkte PII; kein Policy-Dokument-Text.',
                    'en' => 'PolicyCenter shape — insuredName/address = direct PII; no policy document text.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Guidewire PolicyCenter extract (not gw DDL clone)\nCREATE TABLE raw_guidewire_account (\n  account_number       VARCHAR,\n  insured_name         VARCHAR, -- direct PII\n  address              VARCHAR, -- direct PII\n  date_of_birth        DATE,    -- direct PII\n  account_status       VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_guidewire_policy (\n  policy_number        VARCHAR,\n  account_number       VARCHAR,\n  line_of_business     VARCHAR,\n  effective_date       DATE,\n  expiration_date      DATE,\n  status               VARCHAR,\n  producer_code        VARCHAR,\n  written_premium      NUMERIC,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-guidewire-claim-transaction',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Claim + Claim Transaction Landing', 'en' => 'RAW claim + claim transaction landing'],
                'notes' => [
                    'de' => 'Claim ohne Adjuster-Notizen/Medical Docs; claimantName direkte PII.',
                    'en' => 'Claim without adjuster notes/medical docs; claimantName direct PII.',
                ],
                'sql' => "CREATE TABLE raw_guidewire_claim (\n  claim_number         VARCHAR,\n  policy_number        VARCHAR,\n  loss_date            DATE,\n  reported_date        DATE,\n  claim_status         VARCHAR,\n  line_of_business     VARCHAR,\n  claimant_name        VARCHAR, -- direct PII\n  _loaded_at           TIMESTAMP\n  -- no adjuster notes / medical documentation\n);\n\nCREATE TABLE raw_guidewire_claim_transaction (\n  claim_transaction_id VARCHAR,\n  claim_number         VARCHAR,\n  transaction_type     VARCHAR, -- reserve | payment | recovery\n  amount               NUMERIC,\n  transaction_date     DATE,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-guidewire-claim-incurred',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact guidewire claim incurred', 'en' => 'Curated fact guidewire claim incurred'],
                'notes' => [
                    'de' => 'Incurred-Grain — Policy Pflicht; ohne Claimant-Klartext.',
                    'en' => 'Incurred grain — policy required; without claimant cleartext.',
                ],
                'sql' => "CREATE TABLE curated_fct_guidewire_claim_incurred AS\nSELECT\n  ct.claim_transaction_id,\n  ct.claim_number,\n  c.policy_number,\n  c.line_of_business,\n  ct.transaction_type,\n  ct.amount,\n  ct.transaction_date\nFROM raw_guidewire_claim_transaction ct\nJOIN raw_guidewire_claim c ON c.claim_number = ct.claim_number\nWHERE c.policy_number IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-guidewire',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim account / policy fact', 'en' => 'Curated dim account / policy fact'],
                'notes' => [
                    'de' => 'Account ohne Insured-Klartext; Policy In-Force-Flag.',
                    'en' => 'Account without insured cleartext; policy in-force flag.',
                ],
                'sql' => "CREATE TABLE curated_dim_guidewire_account AS\nSELECT\n  account_number,\n  account_status\n  -- omit insured_name, address, date_of_birth from default analytics dims\nFROM raw_guidewire_account;\n\nCREATE TABLE curated_fct_guidewire_policy AS\nSELECT\n  p.policy_number,\n  p.account_number,\n  p.line_of_business,\n  p.effective_date,\n  p.expiration_date,\n  p.producer_code,\n  p.written_premium,\n  CASE WHEN p.status = 'in_force' THEN TRUE ELSE FALSE END AS is_in_force\nFROM raw_guidewire_policy p\nWHERE p.account_number IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-guidewire',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Policies In Force, Written Premium, Loss Ratio — Periodenfilter anpassen.',
                    'en' => 'Policies in force, written premium, loss ratio — adapt period filters.',
                ],
                'sql' => "-- Policies in force as of date\nSELECT COUNT(*) AS policies_in_force\nFROM curated_fct_guidewire_policy\nWHERE is_in_force = TRUE\n  AND :as_of_date BETWEEN effective_date AND expiration_date;\n\n-- Written premium in period\nSELECT SUM(written_premium) AS written_premium\nFROM curated_fct_guidewire_policy\nWHERE effective_date BETWEEN :period_start AND :period_end;\n\n-- Loss ratio by line of business\nSELECT\n  line_of_business,\n  SUM(CASE WHEN transaction_type IN ('reserve', 'payment') THEN amount ELSE 0 END)\n    / NULLIF(SUM(written_premium), 0) AS loss_ratio\nFROM curated_fct_guidewire_claim_incurred ci\nJOIN curated_fct_guidewire_policy p ON p.policy_number = ci.policy_number\nGROUP BY line_of_business;",
            ],
        ],
    ],

    'duck-creek' => [
        'sqlExamples' => [
            [
                'id' => 'raw-duckcreek-account-policytxn',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Account + Policy Transaction Landing', 'en' => 'RAW account + policy transaction landing'],
                'notes' => [
                    'de' => 'Duck Creek Policy-Shape — insuredName/address direkte PII; kein Policy-Dokument-Text.',
                    'en' => 'Duck Creek Policy shape — insuredName/address direct PII; no policy document text.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Duck Creek Policy extract (not XSD DDL clone)\nCREATE TABLE raw_duckcreek_account (\n  account_number       VARCHAR,\n  insured_name         VARCHAR, -- direct PII\n  address              VARCHAR, -- direct PII\n  date_of_birth        DATE,    -- direct PII\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_duckcreek_policy_transaction (\n  policy_transaction_id VARCHAR,\n  policy_number         VARCHAR,\n  account_number        VARCHAR,\n  transaction_type      VARCHAR, -- new_business | renewal | endorsement\n  effective_date        DATE,\n  premium_amount        NUMERIC,\n  product_id            VARCHAR,\n  status                VARCHAR,\n  _loaded_at            TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-duckcreek-claim-activity',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Claim + Claim Activity Landing', 'en' => 'RAW claim + claim activity landing'],
                'notes' => [
                    'de' => 'Claim ohne Adjuster-Notizen/Correspondence; claimantName direkte PII.',
                    'en' => 'Claim without adjuster notes/correspondence; claimantName direct PII.',
                ],
                'sql' => "CREATE TABLE raw_duckcreek_claim (\n  claim_number         VARCHAR,\n  policy_number        VARCHAR,\n  loss_date            DATE,\n  reported_date        DATE,\n  claim_status         VARCHAR,\n  line_of_business     VARCHAR,\n  claimant_name        VARCHAR, -- direct PII\n  _loaded_at           TIMESTAMP\n  -- no adjuster notes / correspondence content\n);\n\nCREATE TABLE raw_duckcreek_claim_activity (\n  claim_activity_id    VARCHAR,\n  claim_number         VARCHAR,\n  activity_type        VARCHAR, -- reserve | payment | recovery\n  amount               NUMERIC,\n  activity_date        DATE,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-duckcreek-claim-incurred',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact duck creek claim incurred', 'en' => 'Curated fact duck creek claim incurred'],
                'notes' => [
                    'de' => 'Incurred-Grain — Policy Pflicht; ohne Claimant-Klartext.',
                    'en' => 'Incurred grain — policy required; without claimant cleartext.',
                ],
                'sql' => "CREATE TABLE curated_fct_duckcreek_claim_incurred AS\nSELECT\n  ca.claim_activity_id,\n  ca.claim_number,\n  c.policy_number,\n  c.line_of_business,\n  ca.activity_type,\n  ca.amount,\n  ca.activity_date\nFROM raw_duckcreek_claim_activity ca\nJOIN raw_duckcreek_claim c ON c.claim_number = ca.claim_number\nWHERE c.policy_number IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-duckcreek',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim account / policy transaction fact', 'en' => 'Curated dim account / policy transaction fact'],
                'notes' => [
                    'de' => 'Account ohne Insured-Klartext; Policy Transaction Active-Flag.',
                    'en' => 'Account without insured cleartext; policy transaction active flag.',
                ],
                'sql' => "CREATE TABLE curated_dim_duckcreek_account AS\nSELECT\n  account_number\n  -- omit insured_name, address, date_of_birth from default analytics dims\nFROM raw_duckcreek_account;\n\nCREATE TABLE curated_fct_duckcreek_policy_transaction AS\nSELECT\n  pt.policy_transaction_id,\n  pt.policy_number,\n  pt.account_number,\n  pt.transaction_type,\n  pt.effective_date,\n  pt.premium_amount,\n  pt.product_id,\n  CASE WHEN pt.status = 'active' THEN TRUE ELSE FALSE END AS is_active\nFROM raw_duckcreek_policy_transaction pt\nWHERE pt.account_number IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-duckcreek',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Premium Written, Policies In Force, Loss Ratio — Periodenfilter anpassen.',
                    'en' => 'Premium written, policies in force, loss ratio — adapt period filters.',
                ],
                'sql' => "-- Premium written in period (new business + renewal)\nSELECT SUM(premium_amount) AS premium_written\nFROM curated_fct_duckcreek_policy_transaction\nWHERE transaction_type IN ('new_business', 'renewal')\n  AND effective_date BETWEEN :period_start AND :period_end;\n\n-- Policies in force (distinct active policy numbers)\nSELECT COUNT(DISTINCT policy_number) AS policies_in_force\nFROM curated_fct_duckcreek_policy_transaction\nWHERE is_active = TRUE;\n\n-- Loss ratio by line of business\nSELECT\n  ci.line_of_business,\n  SUM(CASE WHEN ci.activity_type IN ('reserve', 'payment') THEN ci.amount ELSE 0 END)\n    / NULLIF(SUM(pt.premium_amount), 0) AS loss_ratio\nFROM curated_fct_duckcreek_claim_incurred ci\nJOIN curated_fct_duckcreek_policy_transaction pt ON pt.policy_number = ci.policy_number\nGROUP BY ci.line_of_business;",
            ],
        ],
    ],
];
