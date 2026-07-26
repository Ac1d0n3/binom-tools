<?php

/**
 * Wave 6 governance overlays — Banking Core source-native PII, DSDR, skip guidance.
 * Merged onto catalog products by id (temenos, avaloq, thought-machine, finastra).
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'temenos' => [
        'pii' => [
            [
                'entity' => 'Customer (CIF)',
                'fields' => ['SHORT.NAME', 'STREET', 'MAILING.LIST', 'LEGAL.ID', 'DATE.OF.BIRTH'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'CUSTOMER Stammdaten — Name/Adresse/Ausweis nur mit Legal-Basis; CUSTOMER.NO als Key behalten.', 'en' => 'CUSTOMER master data — name/address/ID only with legal basis; keep CUSTOMER.NO as key.'],
            ],
            [
                'entity' => 'Statement entry narrative',
                'fields' => ['NARRATIVE', 'NARRATIVE.2', 'ORDERING.CUST', 'BENEFICIARY'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Freitext-Felder auf Postings enthalten oft Gegenpartei-Namen — Metadata reicht für Ledger-KPIs.', 'en' => 'Free-text fields on postings often contain counterparty names — metadata is enough for ledger KPIs.'],
            ],
            [
                'entity' => 'Payment message bodies',
                'fields' => ['SWIFT MT message', 'pain.001 XML', 'payment instruction body'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Zahlungsnachrichten nie ins Warehouse — nur Status/Referenz/Amount.', 'en' => 'Never land payment messages in the warehouse — status/reference/amount only.'],
            ],
            [
                'entity' => 'KYC / identity documents',
                'fields' => ['passport scan', 'proof of address', 'LEGAL.ID', 'DATE.OF.BIRTH'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Ausweisdaten strikt Access-kontrollieren; Dokument-Binaries nie im Warehouse.', 'en' => 'Strictly access-control identity data; never store document binaries in the warehouse.'],
            ],
            [
                'entity' => 'Limit / facility collateral',
                'fields' => ['collateral description', 'guarantor details', 'LIMIT.AMOUNT'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: aggregates only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: aggregates only; Mart: aggregates only'],
                'treatment' => ['de' => 'Guarantor/Collateral-Freitext kann Drittparteien-PII enthalten — Amount/Status reicht.', 'en' => 'Guarantor/collateral free text can contain third-party PII — amount/status is enough.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'CUSTOMER.NO, ACCOUNT.NUMBER, ARRANGEMENT.ID, LEGAL.ID (hashed), MNEMONIC.', 'en' => 'CUSTOMER.NO, ACCOUNT.NUMBER, ARRANGEMENT.ID, LEGAL.ID (hashed), MNEMONIC.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Customer, Account, Arrangement, Product, Statement Entry Meta, Limit.', 'en' => 'Customer, account, arrangement, product, statement entry meta, limit.'],
            ],
            [
                'focus' => ['de' => 'Batch / EOD export copies', 'en' => 'Batch / EOD export copies'],
                'notes' => ['de' => 'End-of-Day Batch-Extracts und Report-Server-Kopien verdoppeln Customer-PII.', 'en' => 'End-of-day batch extracts and report-server copies duplicate customer PII.'],
            ],
            [
                'focus' => ['de' => 'Test / UAT environment copies', 'en' => 'Test / UAT environment copies'],
                'notes' => ['de' => 'UAT/Training-Umgebungen nicht mit Prod-Banking-Marts mischen.', 'en' => 'Do not mix UAT/training environments with prod banking marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Payment message bodies (SWIFT MT / pain.001)',
                'category' => 'system',
                'reason' => ['de' => 'Klartext-Zahlungsnachrichten — nie landen; Status/Referenz höchstens.', 'en' => 'Cleartext payment messages — never land; status/reference at most.'],
            ],
            [
                'name' => 'Statement entry narrative full text',
                'category' => 'pii',
                'reason' => ['de' => 'Freitext-PII (Gegenpartei-Namen) — Metadata reicht für Ledger-KPIs.', 'en' => 'Free-text PII (counterparty names) — metadata is enough for ledger KPIs.'],
            ],
            [
                'name' => 'KYC document scans (passport, proof of address)',
                'category' => 'security',
                'reason' => ['de' => 'Dokument-Binaries — nie ins Warehouse; Status/Expiry-Metadata reicht.', 'en' => 'Document binaries — never into the warehouse; status/expiry metadata suffices.'],
            ],
            [
                'name' => 'Full audit trail / version history (AUDIT/VERSION)',
                'category' => 'system',
                'reason' => ['de' => 'Technisches Audit — Volumen, wenig Mart-Nutzen; Meta/Agg bevorzugen.', 'en' => 'Technical audit — volume, little mart value; prefer meta/agg.'],
            ],
        ],
        'skip' => [
            ['name' => 'Payment message bodies (SWIFT/pain.001)', 'reason' => ['de' => 'Nie ins Warehouse — Security/PII-Risiko.', 'en' => 'Never into the warehouse — security/PII risk.']],
            ['name' => 'Statement entry narrative cleartext (bulk)', 'reason' => ['de' => 'Kann Gegenpartei-PII enthalten.', 'en' => 'Can contain counterparty PII.']],
            ['name' => 'KYC document scans / images', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen.', 'en' => 'Binaries without analytics value.']],
            ['name' => 'Customer name/address cleartext in default marts', 'reason' => ['de' => 'CUSTOMER.NO reicht für Banking-KPIs.', 'en' => 'CUSTOMER.NO is enough for banking KPIs.']],
            ['name' => 'Full audit trail / version history', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History.', 'en' => 'Volume — meta/agg instead of full history.']],
        ],
    ],

    'avaloq' => [
        'pii' => [
            [
                'entity' => 'Business partner',
                'fields' => ['NAME', 'ADDRESS', 'DATE_OF_BIRTH', 'TAX_ID', 'NATIONALITY'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Business Partner Stammdaten — Name/Adresse/Tax ID nur mit Legal-Basis; BP_ID als Key behalten.', 'en' => 'Business partner master data — name/address/tax ID only with legal basis; keep BP_ID as key.'],
            ],
            [
                'entity' => 'Booking text / reference',
                'fields' => ['booking text', 'counterparty reference', 'IBAN'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Booking-Freitext und IBAN können Personenbezug haben — Metadata reicht für Ledger-KPIs.', 'en' => 'Booking free text and IBAN can be personal data — metadata is enough for ledger KPIs.'],
            ],
            [
                'entity' => 'Payment message bodies',
                'fields' => ['SWIFT message', 'ISO 20022 XML', 'payment instruction body'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Zahlungsnachrichten nie ins Warehouse — nur Status/Referenz/Amount.', 'en' => 'Never land payment messages in the warehouse — status/reference/amount only.'],
            ],
            [
                'entity' => 'Document management scans',
                'fields' => ['identity document scan', 'signature card', 'contract PDF'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Dokument-Binaries nie ins Warehouse — Status/Typ-Metadata reicht.', 'en' => 'Never land document binaries in the warehouse — status/type metadata suffices.'],
            ],
            [
                'entity' => 'Relationship manager notes',
                'fields' => ['advisory notes', 'call log free text'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: drop; Mart: nie', 'en' => 'RAW: restrict access; Curated: drop; Mart: never'],
                'treatment' => ['de' => 'Freitext-Notizen enthalten oft Personenbezug — nicht in Analytics-Stages.', 'en' => 'Free-text notes often contain personal data — keep out of analytics stages.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'BP_ID, CONTRACT_ID, IBAN (hashed), TAX_ID (hashed).', 'en' => 'BP_ID, CONTRACT_ID, IBAN (hashed), TAX_ID (hashed).'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Business Partner, Contract, Product, Booking Meta, Balance, Limit.', 'en' => 'Business partner, contract, product, booking meta, balance, limit.'],
            ],
            [
                'focus' => ['de' => 'Data warehouse / reporting mart copies', 'en' => 'Data warehouse / reporting mart copies'],
                'notes' => ['de' => 'Avaloq Business Intelligence Layer und Reporting-DB verdoppeln Business-Partner-PII.', 'en' => 'Avaloq Business Intelligence layer and reporting DB duplicate business partner PII.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / migration copies', 'en' => 'Sandbox / migration copies'],
                'notes' => ['de' => 'Migrations- und Sandbox-Instanzen nicht mit Prod-Banking-Marts mischen.', 'en' => 'Do not mix migration and sandbox instances with prod banking marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Payment message bodies (SWIFT / ISO 20022)',
                'category' => 'system',
                'reason' => ['de' => 'Klartext-Zahlungsnachrichten — nie landen; Status/Referenz höchstens.', 'en' => 'Cleartext payment messages — never land; status/reference at most.'],
            ],
            [
                'name' => 'Booking text / reference full text',
                'category' => 'pii',
                'reason' => ['de' => 'Freitext-PII — Metadata reicht für Ledger-KPIs.', 'en' => 'Free-text PII — metadata is enough for ledger KPIs.'],
            ],
            [
                'name' => 'Document management scans',
                'category' => 'security',
                'reason' => ['de' => 'Dokument-Binaries — nie ins Warehouse.', 'en' => 'Document binaries — never into the warehouse.'],
            ],
            [
                'name' => 'Full change history / archive tables',
                'category' => 'system',
                'reason' => ['de' => 'Technisches Change-History — Volumen; Meta/Agg bevorzugen.', 'en' => 'Technical change history — volume; prefer meta/agg.'],
            ],
        ],
        'skip' => [
            ['name' => 'Payment message bodies (SWIFT/ISO 20022)', 'reason' => ['de' => 'Nie ins Warehouse — Security/PII-Risiko.', 'en' => 'Never into the warehouse — security/PII risk.']],
            ['name' => 'Booking text/reference cleartext (bulk)', 'reason' => ['de' => 'Kann Gegenpartei-PII enthalten.', 'en' => 'Can contain counterparty PII.']],
            ['name' => 'Document management scans', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen.', 'en' => 'Binaries without analytics value.']],
            ['name' => 'Business partner name/address cleartext in default marts', 'reason' => ['de' => 'BP_ID reicht für Banking-KPIs.', 'en' => 'BP_ID is enough for banking KPIs.']],
            ['name' => 'Relationship manager advisory notes', 'reason' => ['de' => 'Freitext-Personenbezug — nicht in Analytics.', 'en' => 'Free-text personal data — keep out of analytics.']],
        ],
    ],

    'thought-machine' => [
        'pii' => [
            [
                'entity' => 'Customer',
                'fields' => ['given_name', 'family_name', 'date_of_birth', 'contact_details.addresses', 'contact_details.emails'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Vault Customer Stammdaten — Namen/Adresse/E-Mail nur mit Legal-Basis; id als Key behalten.', 'en' => 'Vault customer master data — names/address/email only with legal basis; keep id as key.'],
            ],
            [
                'entity' => 'Posting instruction metadata',
                'fields' => ['instruction_details', 'metadata', 'client_transaction_id counterparty fields'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Instruction Metadata kann Gegenpartei-Namen enthalten — Amount/Phase reicht für Ledger-KPIs.', 'en' => 'Instruction metadata can contain counterparty names — amount/phase is enough for ledger KPIs.'],
            ],
            [
                'entity' => 'Payment device token / PAN',
                'fields' => ['card token', 'PAN', 'CVV', 'device credential'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Kartendaten/Tokens nie ins Warehouse — nur Device-Status-Metadata.', 'en' => 'Never land card data/tokens in the warehouse — device status metadata only.'],
            ],
            [
                'entity' => 'Restriction / hold reason',
                'fields' => ['restriction reason free text', 'sanctions screening details'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: reason code only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: reason code only; Mart: aggregates only'],
                'treatment' => ['de' => 'Sanctions/Hold-Details streng Access-kontrollieren; Mart nur Restriction-Type-Counts.', 'en' => 'Strictly access-control sanctions/hold details; mart only restriction-type counts.'],
            ],
            [
                'entity' => 'Webhook / event stream payload',
                'fields' => ['event payload body', 'webhook subscriber data'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: nur Facts materialisieren; Curated: aggregates; Mart: aggregates only', 'en' => 'RAW: materialize facts only; Curated: aggregates; Mart: aggregates only'],
                'treatment' => ['de' => 'Event-Payloads können Kunden-/Kontodetails duplizieren — nur relevante Felder extrahieren.', 'en' => 'Event payloads can duplicate customer/account details — extract only relevant fields.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'customer.id, account.id, client_transaction_id, product_version_id.', 'en' => 'customer.id, account.id, client_transaction_id, product_version_id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Customer, Account, Product Version, Posting Meta, Balance, Restriction.', 'en' => 'Customer, account, product version, posting meta, balance, restriction.'],
            ],
            [
                'focus' => ['de' => 'Streaming / event-driven copies', 'en' => 'Streaming / event-driven copies'],
                'notes' => ['de' => 'Kafka-Streams und Downstream-Consumer verdoppeln Customer-/Posting-PII.', 'en' => 'Kafka streams and downstream consumers duplicate customer/posting PII.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / non-prod universe copies', 'en' => 'Sandbox / non-prod universe copies'],
                'notes' => ['de' => 'Non-prod Vault Universes nicht mit Prod-Banking-Marts mischen.', 'en' => 'Do not mix non-prod Vault universes with prod banking marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Posting instruction metadata full text',
                'category' => 'pii',
                'reason' => ['de' => 'Freitext-PII (Gegenpartei-Namen) — Metadata reicht für Ledger-KPIs.', 'en' => 'Free-text PII (counterparty names) — metadata is enough for ledger KPIs.'],
            ],
            [
                'name' => 'Payment device tokens / PAN data',
                'category' => 'security',
                'reason' => ['de' => 'Kartendaten/Tokens — nie ins Warehouse.', 'en' => 'Card data/tokens — never into the warehouse.'],
            ],
            [
                'name' => 'Restriction reason free text / sanctions details',
                'category' => 'security',
                'reason' => ['de' => 'Sanctions-Details streng vertraulich — Reason Code reicht.', 'en' => 'Sanctions details strictly confidential — reason code suffices.'],
            ],
            [
                'name' => 'Full event/webhook stream dumps',
                'category' => 'system',
                'reason' => ['de' => 'Streaming-Events — Volumen; nur relevante Facts materialisieren.', 'en' => 'Streaming events — volume; materialize only relevant facts.'],
            ],
        ],
        'skip' => [
            ['name' => 'Posting instruction metadata cleartext (bulk)', 'reason' => ['de' => 'Kann Gegenpartei-PII enthalten.', 'en' => 'Can contain counterparty PII.']],
            ['name' => 'Payment device tokens / PAN data', 'reason' => ['de' => 'Nie ins Warehouse — Security-kritisch.', 'en' => 'Never into the warehouse — security critical.']],
            ['name' => 'Sanctions / restriction reason free text', 'reason' => ['de' => 'Streng vertraulich — Reason Code only.', 'en' => 'Strictly confidential — reason code only.']],
            ['name' => 'Customer name/address cleartext in default marts', 'reason' => ['de' => 'customer id reicht für Banking-KPIs.', 'en' => 'customer id is enough for banking KPIs.']],
            ['name' => 'Full event/webhook stream dumps', 'reason' => ['de' => 'Volumen — nur Facts materialisieren.', 'en' => 'Volume — materialize facts only.']],
        ],
    ],

    'finastra' => [
        'pii' => [
            [
                'entity' => 'Customer (CIF)',
                'fields' => ['ShortName', 'Address', 'TaxId', 'DateOfBirth'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'CIF Stammdaten — Name/Adresse/Tax ID nur mit Legal-Basis; CIFNumber als Key behalten.', 'en' => 'CIF master data — name/address/tax ID only with legal basis; keep CIFNumber as key.'],
            ],
            [
                'entity' => 'GL entry narrative',
                'fields' => ['narrative', 'description', 'counterparty reference'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Buchungstext enthält oft Gegenpartei-Namen — Metadata reicht für Ledger-KPIs.', 'en' => 'Posting text often contains counterparty names — metadata is enough for ledger KPIs.'],
            ],
            [
                'entity' => 'Payment message bodies',
                'fields' => ['SWIFT message', 'ISO 20022 XML', 'payment instruction body'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Zahlungsnachrichten nie ins Warehouse — nur Status/Referenz/Amount.', 'en' => 'Never land payment messages in the warehouse — status/reference/amount only.'],
            ],
            [
                'entity' => 'KYC / collateral documents',
                'fields' => ['identity document scan', 'collateral valuation report', 'TaxId'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Dokument-Binaries nie ins Warehouse — Status/Typ-Metadata reicht.', 'en' => 'Never land document binaries in the warehouse — status/type metadata suffices.'],
            ],
            [
                'entity' => 'Facility guarantor / collateral details',
                'fields' => ['guarantor name', 'guarantor address', 'collateral description'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: aggregates only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: aggregates only; Mart: aggregates only'],
                'treatment' => ['de' => 'Guarantor-Details sind Drittparteien-PII — nur FacilityLimit/Status in Marts.', 'en' => 'Guarantor details are third-party PII — only facility limit/status in marts.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'CIFNumber, AccountNumber, FacilityId, TaxId (hashed).', 'en' => 'CIFNumber, AccountNumber, FacilityId, TaxId (hashed).'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Customer, Account, Product, Facility, GL Entry Meta, Limit.', 'en' => 'Customer, account, product, facility, GL entry meta, limit.'],
            ],
            [
                'focus' => ['de' => 'Report server / EOD extract copies', 'en' => 'Report server / EOD extract copies'],
                'notes' => ['de' => 'Report-Server und End-of-Day-Extracts verdoppeln CIF-PII in mehreren Stages.', 'en' => 'Report server and end-of-day extracts duplicate CIF PII across multiple stages.'],
            ],
            [
                'focus' => ['de' => 'Test / migration environment copies', 'en' => 'Test / migration environment copies'],
                'notes' => ['de' => 'Migrations- und Test-Instanzen nicht mit Prod-Banking-Marts mischen.', 'en' => 'Do not mix migration and test instances with prod banking marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Payment message bodies (SWIFT / ISO 20022)',
                'category' => 'system',
                'reason' => ['de' => 'Klartext-Zahlungsnachrichten — nie landen; Status/Referenz höchstens.', 'en' => 'Cleartext payment messages — never land; status/reference at most.'],
            ],
            [
                'name' => 'GL entry narrative / description full text',
                'category' => 'pii',
                'reason' => ['de' => 'Freitext-PII — Metadata reicht für Ledger-KPIs.', 'en' => 'Free-text PII — metadata is enough for ledger KPIs.'],
            ],
            [
                'name' => 'KYC / collateral document scans',
                'category' => 'security',
                'reason' => ['de' => 'Dokument-Binaries — nie ins Warehouse.', 'en' => 'Document binaries — never into the warehouse.'],
            ],
            [
                'name' => 'Full audit / event log tables',
                'category' => 'system',
                'reason' => ['de' => 'Technisches Audit — Volumen; Meta/Agg bevorzugen.', 'en' => 'Technical audit — volume; prefer meta/agg.'],
            ],
        ],
        'skip' => [
            ['name' => 'Payment message bodies (SWIFT/ISO 20022)', 'reason' => ['de' => 'Nie ins Warehouse — Security/PII-Risiko.', 'en' => 'Never into the warehouse — security/PII risk.']],
            ['name' => 'GL entry narrative/description cleartext (bulk)', 'reason' => ['de' => 'Kann Gegenpartei-PII enthalten.', 'en' => 'Can contain counterparty PII.']],
            ['name' => 'KYC / collateral document scans', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen.', 'en' => 'Binaries without analytics value.']],
            ['name' => 'Customer name/address cleartext in default marts', 'reason' => ['de' => 'CIFNumber reicht für Banking-KPIs.', 'en' => 'CIFNumber is enough for banking KPIs.']],
            ['name' => 'Facility guarantor / collateral PII details', 'reason' => ['de' => 'Drittparteien-PII — Aggregates only.', 'en' => 'Third-party PII — aggregates only.']],
        ],
    ],
];
