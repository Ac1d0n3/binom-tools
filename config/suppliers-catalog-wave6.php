<?php

/**
 * Wave 6 supplier library entries — Banking Core (full template depth).
 *
 * Emphasize accounts, customers, products and postings; do not load cleartext
 * transaction/payment payloads (SWIFT/pain.001 bodies, free-text narratives) by default.
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $bankingTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    return [
        [
            'id' => 'temenos',
            'domain' => 'banking',
            'order' => 210,
            'label' => ['de' => 'Temenos (T24 / Transact)', 'en' => 'Temenos (T24 / Transact)'],
            'shortPurpose' => [
                'de' => 'Core Banking: Customer/Account/Arrangement, Postings — T24/Transact-Load, PII und Banking-Measures; keine Klartext-Zahlungspayloads.',
                'en' => 'Core banking: customer/account/arrangement, postings — T24/Transact load, PII and banking measures; no cleartext payment payloads by default.',
            ],
            'entities' => [
                [
                    'id' => 'customer',
                    'label' => ['de' => 'Customer', 'en' => 'Customer'],
                    'description' => [
                        'de' => 'T24 CUSTOMER (CIF) — Mnemonic, Adresse, Sector; Kern-Stammdaten mit PII.',
                        'en' => 'T24 CUSTOMER (CIF) — mnemonic, address, sector; core master data with PII.',
                    ],
                    'grain' => ['de' => 'Ein Customer (CUSTOMER.NO)', 'en' => 'One customer (CUSTOMER.NO)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'account',
                    'label' => ['de' => 'Account', 'en' => 'Account'],
                    'description' => [
                        'de' => 'T24 ACCOUNT — Category, Currency, Working Balance; Fact-Anker für Postings.',
                        'en' => 'T24 ACCOUNT — category, currency, working balance; fact anchor for postings.',
                    ],
                    'grain' => ['de' => 'Ein Account (ACCOUNT.NUMBER)', 'en' => 'One account (ACCOUNT.NUMBER)'],
                    'role' => ['de' => 'Dimension / Fact-Anker', 'en' => 'Dimension / fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'arrangement',
                    'label' => ['de' => 'Arrangement', 'en' => 'Arrangement'],
                    'description' => [
                        'de' => 'AA.ARRANGEMENT — Deposit/Loan-Instanz unter Arrangement Architecture; Customer/Product-Join.',
                        'en' => 'AA.ARRANGEMENT — deposit/loan instance under Arrangement Architecture; customer/product join.',
                    ],
                    'grain' => ['de' => 'Ein Arrangement (ARRANGEMENT.ID)', 'en' => 'One arrangement (ARRANGEMENT.ID)'],
                    'role' => ['de' => 'Produkt-Fact', 'en' => 'Product fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'product',
                    'label' => ['de' => 'Product', 'en' => 'Product'],
                    'description' => [
                        'de' => 'AA.PRODUCT — Produktkatalog (Lending, Deposits, Current Accounts).',
                        'en' => 'AA.PRODUCT — product catalog (lending, deposits, current accounts).',
                    ],
                    'grain' => ['de' => 'Ein Product (PRODUCT.ID)', 'en' => 'One product (PRODUCT.ID)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'statement_entry',
                    'label' => ['de' => 'Statement Entry', 'en' => 'Statement entry'],
                    'description' => [
                        'de' => 'STMT.ENTRY — Ledger-Posting; Betrag/Value Date; Narrative-Freitext nicht default laden.',
                        'en' => 'STMT.ENTRY — ledger posting; amount/value date; free-text narrative not loaded by default.',
                    ],
                    'grain' => ['de' => 'Ein Statement Entry (composite ID)', 'en' => 'One statement entry (composite ID)'],
                    'role' => ['de' => 'Ledger-Fact (high volume)', 'en' => 'Ledger fact (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'limit',
                    'label' => ['de' => 'Limit', 'en' => 'Limit'],
                    'description' => [
                        'de' => 'LIMIT — Kreditlimit je Customer/Line; Expiry und Utilization.',
                        'en' => 'LIMIT — credit limit per customer/line; expiry and utilization.',
                    ],
                    'grain' => ['de' => 'Ein Limit (LIMIT.REFERENCE)', 'en' => 'One limit (LIMIT.REFERENCE)'],
                    'role' => ['de' => 'Credit-Fact', 'en' => 'Credit fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'charge',
                    'label' => ['de' => 'Charge', 'en' => 'Charge'],
                    'description' => [
                        'de' => 'FT.COMMISSION.TYPE / CHARGE — Gebührenereignis je Account.',
                        'en' => 'FT.COMMISSION.TYPE / CHARGE — fee event per account.',
                    ],
                    'grain' => ['de' => 'Ein Charge Event (CHARGE.CODE @ Account)', 'en' => 'One charge event (CHARGE.CODE @ account)'],
                    'role' => ['de' => 'Fee-Fact', 'en' => 'Fee fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'account_officer',
                    'label' => ['de' => 'Account Officer', 'en' => 'Account officer'],
                    'description' => [
                        'de' => 'SPF.USER / Department — Relationship-Manager-Dimension, kein Customer-PII.',
                        'en' => 'SPF.USER / department — relationship manager dimension, not customer PII.',
                    ],
                    'grain' => ['de' => 'Ein Officer (USER.ID)', 'en' => 'One officer (USER.ID)'],
                    'role' => ['de' => 'Staff-Dimension', 'en' => 'Staff dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Customer', 'name' => 'ID', 'role' => 'key', 'why' => ['de' => 'CUSTOMER.NO / Join-Key', 'en' => 'CUSTOMER.NO / join key']],
                ['entity' => 'Customer', 'name' => 'MNEMONIC', 'role' => 'dimension', 'why' => ['de' => 'Kurzcode für Suche/Reports', 'en' => 'Short code for search/reports']],
                ['entity' => 'Customer', 'name' => 'SHORT.NAME', 'role' => 'pii', 'why' => ['de' => 'Kundenname / PII', 'en' => 'Customer name / PII']],
                ['entity' => 'Customer', 'name' => 'STREET', 'role' => 'pii', 'why' => ['de' => 'Adresse / PII', 'en' => 'Address / PII']],
                ['entity' => 'Customer', 'name' => 'SECTOR', 'role' => 'dimension', 'why' => ['de' => 'Kundensektor-Dim', 'en' => 'Customer sector dim']],
                ['entity' => 'Customer', 'name' => 'LEGAL.ID', 'role' => 'pii', 'why' => ['de' => 'Steuer-/Ausweis-ID / PII', 'en' => 'Tax/national ID / PII']],
                ['entity' => 'Customer', 'name' => 'DATE.OF.BIRTH', 'role' => 'pii', 'why' => ['de' => 'Geburtsdatum / PII', 'en' => 'Date of birth / PII']],
                ['entity' => 'Customer', 'name' => 'CUSTOMER.STATUS1', 'role' => 'dimension', 'why' => ['de' => 'Kunden-Risikostatus', 'en' => 'Customer risk status']],
                ['entity' => 'Account', 'name' => 'ACCOUNT.NUMBER', 'role' => 'key', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
                ['entity' => 'Account', 'name' => 'CUSTOMER', 'role' => 'dimension', 'why' => ['de' => 'Customer-Rückjoin', 'en' => 'Customer back-join']],
                ['entity' => 'Account', 'name' => 'CATEGORY', 'role' => 'dimension', 'why' => ['de' => 'Account-Kategorie (Current, Savings …)', 'en' => 'Account category (current, savings …)']],
                ['entity' => 'Account', 'name' => 'CURRENCY', 'role' => 'dimension', 'why' => ['de' => 'Kontowährung', 'en' => 'Account currency']],
                ['entity' => 'Account', 'name' => 'OPENING.DATE', 'role' => 'measure', 'why' => ['de' => 'Konto-Eröffnungsdatum', 'en' => 'Account opening date']],
                ['entity' => 'Account', 'name' => 'WORKING.BALANCE', 'role' => 'measure', 'why' => ['de' => 'Aktueller Saldo', 'en' => 'Current balance']],
                ['entity' => 'Arrangement', 'name' => 'ARRANGEMENT.ID', 'role' => 'key', 'why' => ['de' => 'Arrangement-Join', 'en' => 'Arrangement join']],
                ['entity' => 'Arrangement', 'name' => 'CUSTOMER', 'role' => 'dimension', 'why' => ['de' => 'Customer-Rückjoin', 'en' => 'Customer back-join']],
                ['entity' => 'Arrangement', 'name' => 'PRODUCT', 'role' => 'dimension', 'why' => ['de' => 'Product-Rückjoin', 'en' => 'Product back-join']],
                ['entity' => 'Arrangement', 'name' => 'START.DATE', 'role' => 'measure', 'why' => ['de' => 'Vertragsbeginn', 'en' => 'Contract start']],
                ['entity' => 'Arrangement', 'name' => 'STATUS', 'role' => 'dimension', 'why' => ['de' => 'AA.STATUS: CURRENT / EXPIRED', 'en' => 'AA.STATUS: CURRENT / EXPIRED']],
                ['entity' => 'Product', 'name' => 'PRODUCT.ID', 'role' => 'key', 'why' => ['de' => 'Product-Join', 'en' => 'Product join']],
                ['entity' => 'Product', 'name' => 'PRODUCT.LINE', 'role' => 'dimension', 'why' => ['de' => 'Lending / Deposits / Current Accounts', 'en' => 'Lending / deposits / current accounts']],
                ['entity' => 'StatementEntry', 'name' => 'ID', 'role' => 'key', 'why' => ['de' => 'Statement-Entry-Join', 'en' => 'Statement entry join']],
                ['entity' => 'StatementEntry', 'name' => 'ACCOUNT.NUMBER', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'StatementEntry', 'name' => 'VALUE.DATE', 'role' => 'measure', 'why' => ['de' => 'Wertstellungsdatum', 'en' => 'Value date']],
                ['entity' => 'StatementEntry', 'name' => 'AMOUNT.LCY', 'role' => 'measure', 'why' => ['de' => 'Betrag in Local Currency', 'en' => 'Amount in local currency']],
                ['entity' => 'StatementEntry', 'name' => 'TRANS.CODE', 'role' => 'dimension', 'why' => ['de' => 'Transaktionstyp-Code', 'en' => 'Transaction type code']],
                ['entity' => 'Limit', 'name' => 'LIMIT.REFERENCE', 'role' => 'key', 'why' => ['de' => 'Limit-Join', 'en' => 'Limit join']],
                ['entity' => 'Limit', 'name' => 'LIMIT.AMOUNT', 'role' => 'measure', 'why' => ['de' => 'Limitbetrag', 'en' => 'Limit amount']],
                ['entity' => 'Charge', 'name' => 'CHARGE.CODE', 'role' => 'key', 'why' => ['de' => 'Charge-Join', 'en' => 'Charge join']],
                ['entity' => 'Charge', 'name' => 'AMOUNT', 'role' => 'measure', 'why' => ['de' => 'Gebührenbetrag', 'en' => 'Charge amount']],
                ['entity' => 'AccountOfficer', 'name' => 'USER.ID', 'role' => 'key', 'why' => ['de' => 'Officer-Join', 'en' => 'Officer join']],
                ['entity' => 'AccountOfficer', 'name' => 'DEPARTMENT.CODE', 'role' => 'dimension', 'why' => ['de' => 'Abteilungs-Dim', 'en' => 'Department dim']],
            ],
            'skipTables' => [
                [
                    'name' => 'Payment message bodies (SWIFT MT / pain.001 XML)',
                    'category' => 'payment',
                    'reason' => [
                        'de' => 'Klartext-Zahlungsnachrichten — Security- und PII-Risiko; nur Status/Referenz laden.',
                        'en' => 'Cleartext payment messages — security and PII risk; load status/reference only.',
                    ],
                ],
                [
                    'name' => 'Statement entry narrative free text (bulk)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Narrative kann Gegenpartei-Namen/PII enthalten — nicht default laden.',
                        'en' => 'Narrative can contain counterparty names/PII — do not load by default.',
                    ],
                ],
                [
                    'name' => 'KYC document scans / images',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Dokument-Binaries — kein Analytics-Nutzen; Status/Expiry reicht.',
                        'en' => 'Document binaries — no analytics value; status/expiry suffices.',
                    ],
                ],
                [
                    'name' => 'Full audit trail / version history (bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'AUDIT/VERSION Records — Volumen, technisches Rauschen; Meta/Agg bevorzugen.',
                        'en' => 'AUDIT/VERSION records — volume, technical noise; prefer meta/agg.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Payment message bodies (SWIFT/pain.001)', 'reason' => ['de' => 'Klartext-Payloads — nie laden', 'en' => 'Cleartext payloads — never load']],
                ['name' => 'Statement entry narrative bulk', 'reason' => ['de' => 'PII-Risiko im Freitext', 'en' => 'PII risk in free text']],
                ['name' => 'KYC document scans', 'reason' => ['de' => 'Binaries ohne KPI-Nutzen', 'en' => 'Binaries without KPI value']],
                ['name' => 'Full audit trail / version history', 'reason' => ['de' => 'Volumen — Meta/Agg', 'en' => 'Volume — meta/agg']],
            ],
            'dimensions' => [
                [
                    'id' => 'currency',
                    'label' => ['de' => 'Currency', 'en' => 'Currency'],
                    'grain' => ['de' => 'account.CURRENCY', 'en' => 'account.CURRENCY'],
                    'notes' => ['de' => 'ISO-Code bevorzugen; Legacy T24-Codes mappen.', 'en' => 'Prefer ISO code; map legacy T24 codes.'],
                ],
                [
                    'id' => 'account_category',
                    'label' => ['de' => 'Account Category', 'en' => 'Account category'],
                    'grain' => ['de' => 'account.CATEGORY', 'en' => 'account.CATEGORY'],
                    'notes' => ['de' => 'Numerische Category-Codes über Reference Table auflösen.', 'en' => 'Resolve numeric category codes via reference table.'],
                ],
                [
                    'id' => 'product_line',
                    'label' => ['de' => 'Product Line', 'en' => 'Product line'],
                    'grain' => ['de' => 'product.PRODUCT.LINE', 'en' => 'product.PRODUCT.LINE'],
                    'notes' => ['de' => 'Lending vs. Deposits vs. Current Accounts nicht mischen.', 'en' => 'Do not mix lending vs deposits vs current accounts.'],
                ],
                [
                    'id' => 'sector',
                    'label' => ['de' => 'Sector', 'en' => 'Sector'],
                    'grain' => ['de' => 'customer.SECTOR', 'en' => 'customer.SECTOR'],
                    'notes' => ['de' => 'Retail vs. Corporate Segmentierung.', 'en' => 'Retail vs corporate segmentation.'],
                ],
                [
                    'id' => 'officer_department',
                    'label' => ['de' => 'Officer Department', 'en' => 'Officer department'],
                    'grain' => ['de' => 'account_officer.DEPARTMENT.CODE', 'en' => 'account_officer.DEPARTMENT.CODE'],
                    'notes' => ['de' => 'Branch/Department für Relationship-Manager-Slices.', 'en' => 'Branch/department for relationship manager slices.'],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Customer',
                    'fields' => ['SHORT.NAME', 'STREET'],
                    'treatment' => [
                        'de' => 'Name und Adresse — taggen; CUSTOMER.NO als Join bevorzugen.',
                        'en' => 'Name and address — tag as PII; prefer CUSTOMER.NO as join.',
                    ],
                ],
                [
                    'entity' => 'Customer',
                    'fields' => ['LEGAL.ID', 'DATE.OF.BIRTH'],
                    'treatment' => [
                        'de' => 'Ausweis-ID/Geburtsdatum — strikter Zugriff und Retention.',
                        'en' => 'National ID/date of birth — strict access and retention.',
                    ],
                ],
                [
                    'entity' => 'StatementEntry',
                    'fields' => ['narrative counterparty text'],
                    'treatment' => [
                        'de' => 'Narrative kann Namen enthalten — nicht default laden oder redigieren.',
                        'en' => 'Narrative can contain names — do not load by default or redact.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'CUSTOMER.NO, ACCOUNT.NUMBER, ARRANGEMENT.ID, LEGAL.ID (hashed).',
                        'en' => 'CUSTOMER.NO, ACCOUNT.NUMBER, ARRANGEMENT.ID, LEGAL.ID (hashed).',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Customer, Account, Arrangement, Statement Entry Meta — keine Payment Bodies.',
                        'en' => 'Customer, account, arrangement, statement entry meta — no payment bodies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'accounts-opened',
                    'example' => true,
                    'label' => ['de' => 'Accounts Opened', 'en' => 'Accounts opened'],
                    'question' => [
                        'de' => 'Wie viele Accounts wurden in der Periode eröffnet?',
                        'en' => 'How many accounts were opened in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM account WHERE OPENING.DATE IN period',
                    'grain' => ['de' => 'Opened Account', 'en' => 'Opened account'],
                    'dimensions' => ['currency', 'account_category', 'sector'],
                    'fieldsUsed' => ['Account.ACCOUNT.NUMBER', 'Account.OPENING.DATE', 'Account.CATEGORY'],
                    'sourceHints' => [
                        'de' => 'OPENING.DATE aus ACCOUNT; interne Test-/Suspense-Kategorien ausschließen.',
                        'en' => 'OPENING.DATE from ACCOUNT; exclude internal test/suspense categories.',
                    ],
                    'adapt' => [
                        'de' => 'Retail vs. Corporate über CUSTOMER.SECTOR trennen.',
                        'en' => 'Separate retail vs corporate via CUSTOMER.SECTOR.',
                    ],
                ],
                [
                    'id' => 'active-accounts',
                    'example' => true,
                    'label' => ['de' => 'Active Accounts', 'en' => 'Active accounts'],
                    'question' => [
                        'de' => 'Wie viele Accounts sind aktuell aktiv (nicht geschlossen)?',
                        'en' => 'How many accounts are currently active (not closed)?',
                    ],
                    'formula' => "COUNT(*) FROM account WHERE close_date IS NULL",
                    'grain' => ['de' => 'Active Account (Snapshot)', 'en' => 'Active account (snapshot)'],
                    'dimensions' => ['currency', 'account_category', 'sector'],
                    'fieldsUsed' => ['Account.ACCOUNT.NUMBER', 'Account.CATEGORY', 'Account.CUSTOMER'],
                    'sourceHints' => [
                        'de' => 'T24 hat kein einheitliches ACCOUNT.STATUS-Feld — close_date/derived Flag nutzen.',
                        'en' => 'T24 has no uniform ACCOUNT.STATUS field — use close_date/derived flag.',
                    ],
                    'adapt' => [
                        'de' => 'Dormant Accounts (keine Postings > 12 Monate) separat flaggen.',
                        'en' => 'Separately flag dormant accounts (no postings > 12 months).',
                    ],
                ],
                [
                    'id' => 'postings-count',
                    'example' => false,
                    'label' => ['de' => 'Postings Count', 'en' => 'Postings count'],
                    'question' => [
                        'de' => 'Wie viele Statement Entries (Postings) gab es in der Periode?',
                        'en' => 'How many statement entries (postings) occurred in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM statement_entry WHERE VALUE.DATE IN period',
                    'grain' => ['de' => 'Statement Entry', 'en' => 'Statement entry'],
                    'dimensions' => ['currency', 'account_category'],
                    'fieldsUsed' => ['StatementEntry.ID', 'StatementEntry.VALUE.DATE', 'StatementEntry.ACCOUNT.NUMBER'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Tages-Aggregate je Account/Trans-Code nutzen.',
                        'en' => 'Use daily aggregates per account/trans code at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Reversal-Postings (TRANS.CODE) separat aus Counts nehmen.',
                        'en' => 'Exclude reversal postings (TRANS.CODE) from counts separately.',
                    ],
                ],
                [
                    'id' => 'average-account-balance',
                    'example' => false,
                    'label' => ['de' => 'Average Account Balance', 'en' => 'Average account balance'],
                    'question' => [
                        'de' => 'Wie hoch ist der durchschnittliche Kontosaldo je Category?',
                        'en' => 'What is the average account balance per category?',
                    ],
                    'formula' => 'AVG(WORKING.BALANCE) FROM account GROUP BY CATEGORY',
                    'grain' => ['de' => 'Account (Snapshot)', 'en' => 'Account (snapshot)'],
                    'dimensions' => ['currency', 'account_category'],
                    'fieldsUsed' => ['Account.WORKING.BALANCE', 'Account.CATEGORY', 'Account.CURRENCY'],
                    'sourceHints' => [
                        'de' => 'Multi-Currency: FCY-Beträge vor AVG in Base Currency konvertieren.',
                        'en' => 'Multi-currency: convert FCY amounts to base currency before AVG.',
                    ],
                    'adapt' => [
                        'de' => 'Negative Balances (Overdraft) getrennt ausweisen.',
                        'en' => 'Report negative balances (overdraft) separately.',
                    ],
                ],
                [
                    'id' => 'limit-utilization',
                    'example' => false,
                    'label' => ['de' => 'Limit Utilization', 'en' => 'Limit utilization'],
                    'question' => [
                        'de' => 'Wie hoch ist die Ausnutzung der Kreditlimits?',
                        'en' => 'What is the utilization of credit limits?',
                    ],
                    'formula' => 'SUM(used_amount) / SUM(LIMIT.AMOUNT) FROM limit',
                    'grain' => ['de' => 'Limit (Snapshot)', 'en' => 'Limit (snapshot)'],
                    'dimensions' => ['sector', 'officer_department'],
                    'fieldsUsed' => ['Limit.LIMIT.REFERENCE', 'Limit.LIMIT.AMOUNT'],
                    'sourceHints' => [
                        'de' => 'used_amount aus verknüpften Account-Overdrafts/Arrangement Balances ableiten.',
                        'en' => 'Derive used_amount from linked account overdrafts/arrangement balances.',
                    ],
                    'adapt' => [
                        'de' => 'Expired Limits (EXPIRY.DATE < today) aus Nenner ausschließen.',
                        'en' => 'Exclude expired limits (EXPIRY.DATE < today) from denominator.',
                    ],
                ],
            ],
            'tools' => $bankingTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'avaloq',
            'domain' => 'banking',
            'order' => 220,
            'label' => ['de' => 'Avaloq', 'en' => 'Avaloq'],
            'shortPurpose' => [
                'de' => 'Core Banking: Business Partner/Contract/Product, Bookings — Avaloq-Load, PII und Banking-Measures; keine Klartext-Zahlungspayloads.',
                'en' => 'Core banking: business partner/contract/product, bookings — Avaloq load, PII and banking measures; no cleartext payment payloads by default.',
            ],
            'entities' => [
                [
                    'id' => 'business_partner',
                    'label' => ['de' => 'Business Partner', 'en' => 'Business partner'],
                    'description' => [
                        'de' => 'Avaloq Business Partner — Name, Adresse, Segment; Kern-Stammdaten mit PII.',
                        'en' => 'Avaloq business partner — name, address, segment; core master data with PII.',
                    ],
                    'grain' => ['de' => 'Ein Business Partner (BP_ID)', 'en' => 'One business partner (BP_ID)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'contract',
                    'label' => ['de' => 'Contract', 'en' => 'Contract'],
                    'description' => [
                        'de' => 'Avaloq Contract — zentrales Objekt für Accounts/Deposits/Loans; IBAN-Join.',
                        'en' => 'Avaloq contract — central object for accounts/deposits/loans; IBAN join.',
                    ],
                    'grain' => ['de' => 'Ein Contract (CONTRACT_ID)', 'en' => 'One contract (CONTRACT_ID)'],
                    'role' => ['de' => 'Dimension / Fact-Anker', 'en' => 'Dimension / fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'product',
                    'label' => ['de' => 'Product', 'en' => 'Product'],
                    'description' => [
                        'de' => 'Avaloq Product Catalog / Contract Type — Produktlinie und Beschreibung.',
                        'en' => 'Avaloq product catalog / contract type — product line and description.',
                    ],
                    'grain' => ['de' => 'Ein Product (PRODUCT_ID)', 'en' => 'One product (PRODUCT_ID)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'booking',
                    'label' => ['de' => 'Booking', 'en' => 'Booking'],
                    'description' => [
                        'de' => 'Avaloq Booking — Posting-Fact; Value/Booking Date, Amount; kein Freitext-Payload.',
                        'en' => 'Avaloq booking — posting fact; value/booking date, amount; no free-text payload.',
                    ],
                    'grain' => ['de' => 'Ein Booking (BOOKING_ID)', 'en' => 'One booking (BOOKING_ID)'],
                    'role' => ['de' => 'Ledger-Fact (high volume)', 'en' => 'Ledger fact (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'balance',
                    'label' => ['de' => 'Balance', 'en' => 'Balance'],
                    'description' => [
                        'de' => 'Contract Balance Snapshot — Balance Date, Amount je Contract.',
                        'en' => 'Contract balance snapshot — balance date, amount per contract.',
                    ],
                    'grain' => ['de' => 'Eine Balance (BALANCE_ID)', 'en' => 'One balance (BALANCE_ID)'],
                    'role' => ['de' => 'Snapshot-Fact', 'en' => 'Snapshot fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'limit',
                    'label' => ['de' => 'Limit', 'en' => 'Limit'],
                    'description' => [
                        'de' => 'Avaloq Limit — Kreditlinie je Business Partner; Expiry und Utilization.',
                        'en' => 'Avaloq limit — credit line per business partner; expiry and utilization.',
                    ],
                    'grain' => ['de' => 'Ein Limit (LIMIT_ID)', 'en' => 'One limit (LIMIT_ID)'],
                    'role' => ['de' => 'Credit-Fact', 'en' => 'Credit fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'charge',
                    'label' => ['de' => 'Charge', 'en' => 'Charge'],
                    'description' => [
                        'de' => 'Avaloq Charge — Gebührenereignis je Contract.',
                        'en' => 'Avaloq charge — fee event per contract.',
                    ],
                    'grain' => ['de' => 'Ein Charge Event (CHARGE_ID)', 'en' => 'One charge event (CHARGE_ID)'],
                    'role' => ['de' => 'Fee-Fact', 'en' => 'Fee fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'relationship_manager',
                    'label' => ['de' => 'Relationship Manager', 'en' => 'Relationship manager'],
                    'description' => [
                        'de' => 'RM/Business Unit — Staff-Dimension, kein Business-Partner-PII.',
                        'en' => 'RM/business unit — staff dimension, not business partner PII.',
                    ],
                    'grain' => ['de' => 'Ein RM (RM_ID)', 'en' => 'One RM (RM_ID)'],
                    'role' => ['de' => 'Staff-Dimension', 'en' => 'Staff dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'BusinessPartner', 'name' => 'BP_ID', 'role' => 'key', 'why' => ['de' => 'Business-Partner-Join', 'en' => 'Business partner join']],
                ['entity' => 'BusinessPartner', 'name' => 'NAME', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'BusinessPartner', 'name' => 'ADDRESS', 'role' => 'pii', 'why' => ['de' => 'Adresse / PII', 'en' => 'Address / PII']],
                ['entity' => 'BusinessPartner', 'name' => 'DATE_OF_BIRTH', 'role' => 'pii', 'why' => ['de' => 'Geburtsdatum / PII', 'en' => 'Date of birth / PII']],
                ['entity' => 'BusinessPartner', 'name' => 'TAX_ID', 'role' => 'pii', 'why' => ['de' => 'Steuer-ID / PII', 'en' => 'Tax ID / PII']],
                ['entity' => 'BusinessPartner', 'name' => 'SEGMENT', 'role' => 'dimension', 'why' => ['de' => 'Kundensegment', 'en' => 'Customer segment']],
                ['entity' => 'BusinessPartner', 'name' => 'NATIONALITY', 'role' => 'dimension', 'why' => ['de' => 'Nationalität-Dim', 'en' => 'Nationality dim']],
                ['entity' => 'Contract', 'name' => 'CONTRACT_ID', 'role' => 'key', 'why' => ['de' => 'Contract-Join', 'en' => 'Contract join']],
                ['entity' => 'Contract', 'name' => 'BP_ID', 'role' => 'dimension', 'why' => ['de' => 'Business-Partner-Rückjoin', 'en' => 'Business partner back-join']],
                ['entity' => 'Contract', 'name' => 'CONTRACT_TYPE', 'role' => 'dimension', 'why' => ['de' => 'Account / Deposit / Loan', 'en' => 'Account / deposit / loan']],
                ['entity' => 'Contract', 'name' => 'CURRENCY', 'role' => 'dimension', 'why' => ['de' => 'Contract-Währung', 'en' => 'Contract currency']],
                ['entity' => 'Contract', 'name' => 'OPENING_DATE', 'role' => 'measure', 'why' => ['de' => 'Eröffnungsdatum', 'en' => 'Opening date']],
                ['entity' => 'Contract', 'name' => 'STATUS', 'role' => 'dimension', 'why' => ['de' => 'Contract-Status (active/closed)', 'en' => 'Contract status (active/closed)']],
                ['entity' => 'Product', 'name' => 'PRODUCT_ID', 'role' => 'key', 'why' => ['de' => 'Product-Join', 'en' => 'Product join']],
                ['entity' => 'Product', 'name' => 'PRODUCT_LINE', 'role' => 'dimension', 'why' => ['de' => 'Produktlinie', 'en' => 'Product line']],
                ['entity' => 'Booking', 'name' => 'BOOKING_ID', 'role' => 'key', 'why' => ['de' => 'Booking-Join', 'en' => 'Booking join']],
                ['entity' => 'Booking', 'name' => 'CONTRACT_ID', 'role' => 'dimension', 'why' => ['de' => 'Contract-Rückjoin', 'en' => 'Contract back-join']],
                ['entity' => 'Booking', 'name' => 'VALUE_DATE', 'role' => 'measure', 'why' => ['de' => 'Wertstellungsdatum', 'en' => 'Value date']],
                ['entity' => 'Booking', 'name' => 'AMOUNT', 'role' => 'measure', 'why' => ['de' => 'Booking-Betrag', 'en' => 'Booking amount']],
                ['entity' => 'Booking', 'name' => 'BOOKING_TYPE', 'role' => 'dimension', 'why' => ['de' => 'Booking-Typ-Code', 'en' => 'Booking type code']],
                ['entity' => 'Balance', 'name' => 'BALANCE_ID', 'role' => 'key', 'why' => ['de' => 'Balance-Join', 'en' => 'Balance join']],
                ['entity' => 'Balance', 'name' => 'CONTRACT_ID', 'role' => 'dimension', 'why' => ['de' => 'Contract-Rückjoin', 'en' => 'Contract back-join']],
                ['entity' => 'Balance', 'name' => 'BALANCE_AMOUNT', 'role' => 'measure', 'why' => ['de' => 'Saldo zum Balance Date', 'en' => 'Balance as of balance date']],
                ['entity' => 'Limit', 'name' => 'LIMIT_ID', 'role' => 'key', 'why' => ['de' => 'Limit-Join', 'en' => 'Limit join']],
                ['entity' => 'Limit', 'name' => 'BP_ID', 'role' => 'dimension', 'why' => ['de' => 'Business-Partner-Rückjoin', 'en' => 'Business partner back-join']],
                ['entity' => 'Limit', 'name' => 'LIMIT_AMOUNT', 'role' => 'measure', 'why' => ['de' => 'Limitbetrag', 'en' => 'Limit amount']],
                ['entity' => 'Charge', 'name' => 'CHARGE_ID', 'role' => 'key', 'why' => ['de' => 'Charge-Join', 'en' => 'Charge join']],
                ['entity' => 'Charge', 'name' => 'CONTRACT_ID', 'role' => 'dimension', 'why' => ['de' => 'Contract-Rückjoin', 'en' => 'Contract back-join']],
                ['entity' => 'Charge', 'name' => 'AMOUNT', 'role' => 'measure', 'why' => ['de' => 'Gebührenbetrag', 'en' => 'Charge amount']],
                ['entity' => 'RelationshipManager', 'name' => 'RM_ID', 'role' => 'key', 'why' => ['de' => 'RM-Join', 'en' => 'RM join']],
                ['entity' => 'RelationshipManager', 'name' => 'BUSINESS_UNIT_ID', 'role' => 'dimension', 'why' => ['de' => 'Business-Unit-Dim', 'en' => 'Business unit dim']],
            ],
            'skipTables' => [
                [
                    'name' => 'Payment message bodies (SWIFT / ISO 20022 XML)',
                    'category' => 'payment',
                    'reason' => [
                        'de' => 'Klartext-Zahlungsnachrichten — Security- und PII-Risiko; nur Status/Referenz laden.',
                        'en' => 'Cleartext payment messages — security and PII risk; load status/reference only.',
                    ],
                ],
                [
                    'name' => 'Booking text / reason free text (bulk)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Freitext kann Gegenpartei-Namen/PII enthalten — nicht default laden.',
                        'en' => 'Free text can contain counterparty names/PII — do not load by default.',
                    ],
                ],
                [
                    'name' => 'Document management scans / images',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Dokument-Binaries — kein Analytics-Nutzen; Status/Expiry reicht.',
                        'en' => 'Document binaries — no analytics value; status/expiry suffices.',
                    ],
                ],
                [
                    'name' => 'Full change history / archive tables (bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Change-History-Tabellen — Volumen, technisches Rauschen; Meta/Agg bevorzugen.',
                        'en' => 'Change-history tables — volume, technical noise; prefer meta/agg.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Payment message bodies (SWIFT/ISO 20022)', 'reason' => ['de' => 'Klartext-Payloads — nie laden', 'en' => 'Cleartext payloads — never load']],
                ['name' => 'Booking text/reason free text bulk', 'reason' => ['de' => 'PII-Risiko im Freitext', 'en' => 'PII risk in free text']],
                ['name' => 'Document management scans', 'reason' => ['de' => 'Binaries ohne KPI-Nutzen', 'en' => 'Binaries without KPI value']],
                ['name' => 'Full change history / archive tables', 'reason' => ['de' => 'Volumen — Meta/Agg', 'en' => 'Volume — meta/agg']],
            ],
            'dimensions' => [
                [
                    'id' => 'currency',
                    'label' => ['de' => 'Currency', 'en' => 'Currency'],
                    'grain' => ['de' => 'contract.CURRENCY', 'en' => 'contract.CURRENCY'],
                    'notes' => ['de' => 'ISO-Code bevorzugen.', 'en' => 'Prefer ISO code.'],
                ],
                [
                    'id' => 'contract_type',
                    'label' => ['de' => 'Contract Type', 'en' => 'Contract type'],
                    'grain' => ['de' => 'contract.CONTRACT_TYPE', 'en' => 'contract.CONTRACT_TYPE'],
                    'notes' => ['de' => 'Account vs. Deposit vs. Loan nicht mischen.', 'en' => 'Do not mix account vs deposit vs loan.'],
                ],
                [
                    'id' => 'product_line',
                    'label' => ['de' => 'Product Line', 'en' => 'Product line'],
                    'grain' => ['de' => 'product.PRODUCT_LINE', 'en' => 'product.PRODUCT_LINE'],
                    'notes' => ['de' => 'Für Produkt-Mix-Reports.', 'en' => 'For product-mix reports.'],
                ],
                [
                    'id' => 'segment',
                    'label' => ['de' => 'Segment', 'en' => 'Segment'],
                    'grain' => ['de' => 'business_partner.SEGMENT', 'en' => 'business_partner.SEGMENT'],
                    'notes' => ['de' => 'Retail vs. Private Banking vs. Corporate.', 'en' => 'Retail vs private banking vs corporate.'],
                ],
                [
                    'id' => 'business_unit',
                    'label' => ['de' => 'Business Unit', 'en' => 'Business unit'],
                    'grain' => ['de' => 'relationship_manager.BUSINESS_UNIT_ID', 'en' => 'relationship_manager.BUSINESS_UNIT_ID'],
                    'notes' => ['de' => 'Für RM/Branch-Slices.', 'en' => 'For RM/branch slices.'],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'BusinessPartner',
                    'fields' => ['NAME', 'ADDRESS'],
                    'treatment' => [
                        'de' => 'Name und Adresse — taggen; BP_ID als Join bevorzugen.',
                        'en' => 'Name and address — tag as PII; prefer BP_ID as join.',
                    ],
                ],
                [
                    'entity' => 'BusinessPartner',
                    'fields' => ['TAX_ID', 'DATE_OF_BIRTH'],
                    'treatment' => [
                        'de' => 'Steuer-ID/Geburtsdatum — strikter Zugriff und Retention.',
                        'en' => 'Tax ID/date of birth — strict access and retention.',
                    ],
                ],
                [
                    'entity' => 'Booking',
                    'fields' => ['booking text / counterparty reference'],
                    'treatment' => [
                        'de' => 'Booking-Text kann Namen enthalten — nicht default laden oder redigieren.',
                        'en' => 'Booking text can contain names — do not load by default or redact.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'BP_ID, CONTRACT_ID, IBAN (hashed), TAX_ID (hashed).',
                        'en' => 'BP_ID, CONTRACT_ID, IBAN (hashed), TAX_ID (hashed).',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Business Partner, Contract, Booking Meta, Balance — keine Payment Bodies.',
                        'en' => 'Business partner, contract, booking meta, balance — no payment bodies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'contracts-opened',
                    'example' => true,
                    'label' => ['de' => 'Contracts Opened', 'en' => 'Contracts opened'],
                    'question' => [
                        'de' => 'Wie viele Contracts wurden in der Periode eröffnet?',
                        'en' => 'How many contracts were opened in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM contract WHERE OPENING_DATE IN period',
                    'grain' => ['de' => 'Opened Contract', 'en' => 'Opened contract'],
                    'dimensions' => ['currency', 'contract_type', 'segment'],
                    'fieldsUsed' => ['Contract.CONTRACT_ID', 'Contract.OPENING_DATE', 'Contract.CONTRACT_TYPE'],
                    'sourceHints' => [
                        'de' => 'OPENING_DATE aus CONTRACT; interne Test-Contracts ausschließen.',
                        'en' => 'OPENING_DATE from CONTRACT; exclude internal test contracts.',
                    ],
                    'adapt' => [
                        'de' => 'Retail vs. Private Banking über SEGMENT trennen.',
                        'en' => 'Separate retail vs private banking via SEGMENT.',
                    ],
                ],
                [
                    'id' => 'active-contracts',
                    'example' => true,
                    'label' => ['de' => 'Active Contracts', 'en' => 'Active contracts'],
                    'question' => [
                        'de' => 'Wie viele Contracts sind aktuell aktiv?',
                        'en' => 'How many contracts are currently active?',
                    ],
                    'formula' => "COUNT(*) FROM contract WHERE STATUS = 'active'",
                    'grain' => ['de' => 'Active Contract (Snapshot)', 'en' => 'Active contract (snapshot)'],
                    'dimensions' => ['currency', 'contract_type', 'segment'],
                    'fieldsUsed' => ['Contract.CONTRACT_ID', 'Contract.STATUS', 'Contract.BP_ID'],
                    'sourceHints' => [
                        'de' => 'STATUS-Werte je Deployment prüfen; Closed vs. Dormant unterscheiden.',
                        'en' => 'Check STATUS values per deployment; distinguish closed vs dormant.',
                    ],
                    'adapt' => [
                        'de' => 'Dormant Contracts (keine Bookings > 12 Monate) separat flaggen.',
                        'en' => 'Separately flag dormant contracts (no bookings > 12 months).',
                    ],
                ],
                [
                    'id' => 'bookings-count',
                    'example' => false,
                    'label' => ['de' => 'Bookings Count', 'en' => 'Bookings count'],
                    'question' => [
                        'de' => 'Wie viele Bookings gab es in der Periode?',
                        'en' => 'How many bookings occurred in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM booking WHERE VALUE_DATE IN period',
                    'grain' => ['de' => 'Booking', 'en' => 'Booking'],
                    'dimensions' => ['currency', 'contract_type'],
                    'fieldsUsed' => ['Booking.BOOKING_ID', 'Booking.VALUE_DATE', 'Booking.CONTRACT_ID'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Tages-Aggregate je Contract/Booking-Type nutzen.',
                        'en' => 'Use daily aggregates per contract/booking type at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Reversal-Bookings (BOOKING_TYPE) separat aus Counts nehmen.',
                        'en' => 'Exclude reversal bookings (BOOKING_TYPE) from counts separately.',
                    ],
                ],
                [
                    'id' => 'average-contract-balance',
                    'example' => false,
                    'label' => ['de' => 'Average Contract Balance', 'en' => 'Average contract balance'],
                    'question' => [
                        'de' => 'Wie hoch ist der durchschnittliche Contract-Saldo je Type?',
                        'en' => 'What is the average contract balance per type?',
                    ],
                    'formula' => 'AVG(BALANCE_AMOUNT) FROM balance JOIN contract ON contract.CONTRACT_ID = balance.CONTRACT_ID GROUP BY CONTRACT_TYPE',
                    'grain' => ['de' => 'Contract (letztes Balance Snapshot)', 'en' => 'Contract (latest balance snapshot)'],
                    'dimensions' => ['currency', 'contract_type'],
                    'fieldsUsed' => ['Balance.BALANCE_AMOUNT', 'Balance.CONTRACT_ID', 'Contract.CONTRACT_TYPE'],
                    'sourceHints' => [
                        'de' => 'Nur letztes Balance Snapshot je Contract und Periode verwenden.',
                        'en' => 'Use only the latest balance snapshot per contract and period.',
                    ],
                    'adapt' => [
                        'de' => 'Negative Balances (Overdraft) getrennt ausweisen.',
                        'en' => 'Report negative balances (overdraft) separately.',
                    ],
                ],
                [
                    'id' => 'limit-utilization',
                    'example' => false,
                    'label' => ['de' => 'Limit Utilization', 'en' => 'Limit utilization'],
                    'question' => [
                        'de' => 'Wie hoch ist die Ausnutzung der Kreditlimits?',
                        'en' => 'What is the utilization of credit limits?',
                    ],
                    'formula' => 'SUM(used_amount) / SUM(LIMIT_AMOUNT) FROM limit',
                    'grain' => ['de' => 'Limit (Snapshot)', 'en' => 'Limit (snapshot)'],
                    'dimensions' => ['segment', 'business_unit'],
                    'fieldsUsed' => ['Limit.LIMIT_ID', 'Limit.LIMIT_AMOUNT', 'Limit.BP_ID'],
                    'sourceHints' => [
                        'de' => 'used_amount aus verknüpften Contract-Overdrafts/Balances ableiten.',
                        'en' => 'Derive used_amount from linked contract overdrafts/balances.',
                    ],
                    'adapt' => [
                        'de' => 'Expired Limits aus Nenner ausschließen.',
                        'en' => 'Exclude expired limits from denominator.',
                    ],
                ],
            ],
            'tools' => $bankingTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'thought-machine',
            'domain' => 'banking',
            'order' => 230,
            'label' => ['de' => 'Thought Machine (Vault)', 'en' => 'Thought Machine (Vault)'],
            'shortPurpose' => [
                'de' => 'Cloud Core Banking: Customer/Account/Product Version, Postings API — Vault-Load, PII und Banking-Measures; keine Klartext-Payloads.',
                'en' => 'Cloud core banking: customer/account/product version, Postings API — Vault load, PII and banking measures; no cleartext payloads by default.',
            ],
            'entities' => [
                [
                    'id' => 'customer',
                    'label' => ['de' => 'Customer', 'en' => 'Customer'],
                    'description' => [
                        'de' => 'Vault Core Customer — given/family name, contact details; PII-Stammdaten.',
                        'en' => 'Vault Core customer — given/family name, contact details; PII master data.',
                    ],
                    'grain' => ['de' => 'Ein Customer (id)', 'en' => 'One customer (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'account',
                    'label' => ['de' => 'Account', 'en' => 'Account'],
                    'description' => [
                        'de' => 'Vault Account — Product Version, Status, Stakeholder; Fact-Anker für Postings.',
                        'en' => 'Vault account — product version, status, stakeholder; fact anchor for postings.',
                    ],
                    'grain' => ['de' => 'Ein Account (id)', 'en' => 'One account (id)'],
                    'role' => ['de' => 'Dimension / Fact-Anker', 'en' => 'Dimension / fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'product_version',
                    'label' => ['de' => 'Product Version', 'en' => 'Product version'],
                    'description' => [
                        'de' => 'Smart Contract Product Version — Product-Katalog und Versionierung.',
                        'en' => 'Smart contract product version — product catalog and versioning.',
                    ],
                    'grain' => ['de' => 'Eine Product Version (id)', 'en' => 'One product version (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'posting_instruction_batch',
                    'label' => ['de' => 'Posting Instruction Batch', 'en' => 'Posting instruction batch'],
                    'description' => [
                        'de' => 'Vault PIB — Batch-Header für Postings; Value/Insertion Timestamp.',
                        'en' => 'Vault PIB — batch header for postings; value/insertion timestamp.',
                    ],
                    'grain' => ['de' => 'Ein PIB (id)', 'en' => 'One PIB (id)'],
                    'role' => ['de' => 'Ledger-Fact-Header (high volume)', 'en' => 'Ledger fact header (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'posting',
                    'label' => ['de' => 'Posting', 'en' => 'Posting'],
                    'description' => [
                        'de' => 'Committed Posting Line — Amount, Denomination, Phase je Account.',
                        'en' => 'Committed posting line — amount, denomination, phase per account.',
                    ],
                    'grain' => ['de' => 'Ein Committed Posting', 'en' => 'One committed posting'],
                    'role' => ['de' => 'Ledger-Fact (high volume)', 'en' => 'Ledger fact (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'balance',
                    'label' => ['de' => 'Balance', 'en' => 'Balance'],
                    'description' => [
                        'de' => 'Vault Balance Output — Total Debit/Credit je Account und Balance Definition.',
                        'en' => 'Vault balance output — total debit/credit per account and balance definition.',
                    ],
                    'grain' => ['de' => 'Eine Balance (id)', 'en' => 'One balance (id)'],
                    'role' => ['de' => 'Snapshot-Fact', 'en' => 'Snapshot fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'restriction',
                    'label' => ['de' => 'Restriction', 'en' => 'Restriction'],
                    'description' => [
                        'de' => 'Account Restriction (Hold/Block) — Restriction Definition Version je Account.',
                        'en' => 'Account restriction (hold/block) — restriction definition version per account.',
                    ],
                    'grain' => ['de' => 'Eine Restriction (id)', 'en' => 'One restriction (id)'],
                    'role' => ['de' => 'Control-Fact', 'en' => 'Control fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'payment_device',
                    'label' => ['de' => 'Payment Device', 'en' => 'Payment device'],
                    'description' => [
                        'de' => 'Payment Device (Karte/Token) — Status-Dimension; Token-Werte nicht laden.',
                        'en' => 'Payment device (card/token) — status dimension; do not load token values.',
                    ],
                    'grain' => ['de' => 'Ein Payment Device (id)', 'en' => 'One payment device (id)'],
                    'role' => ['de' => 'Device-Dimension', 'en' => 'Device dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Customer', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Customer-Join', 'en' => 'Customer join']],
                ['entity' => 'Customer', 'name' => 'given_name', 'role' => 'pii', 'why' => ['de' => 'Vorname / PII', 'en' => 'Given name / PII']],
                ['entity' => 'Customer', 'name' => 'family_name', 'role' => 'pii', 'why' => ['de' => 'Nachname / PII', 'en' => 'Family name / PII']],
                ['entity' => 'Customer', 'name' => 'date_of_birth', 'role' => 'pii', 'why' => ['de' => 'Geburtsdatum / PII', 'en' => 'Date of birth / PII']],
                ['entity' => 'Customer', 'name' => 'contact_details.addresses', 'role' => 'pii', 'why' => ['de' => 'Adresse / PII', 'en' => 'Address / PII']],
                ['entity' => 'Customer', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Customer-Status', 'en' => 'Customer status']],
                ['entity' => 'Account', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
                ['entity' => 'Account', 'name' => 'customer_id', 'role' => 'dimension', 'why' => ['de' => 'Customer-Rückjoin', 'en' => 'Customer back-join']],
                ['entity' => 'Account', 'name' => 'product_version_id', 'role' => 'dimension', 'why' => ['de' => 'Product-Version-Rückjoin', 'en' => 'Product version back-join']],
                ['entity' => 'Account', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'ACCOUNT_STATUS_OPEN / _CLOSED', 'en' => 'ACCOUNT_STATUS_OPEN / _CLOSED']],
                ['entity' => 'Account', 'name' => 'opening_timestamp', 'role' => 'measure', 'why' => ['de' => 'Eröffnungszeitpunkt', 'en' => 'Opening timestamp']],
                ['entity' => 'ProductVersion', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Product-Version-Join', 'en' => 'Product version join']],
                ['entity' => 'ProductVersion', 'name' => 'product_id', 'role' => 'dimension', 'why' => ['de' => 'Product-Katalog-Join', 'en' => 'Product catalog join']],
                ['entity' => 'ProductVersion', 'name' => 'display_name', 'role' => 'dimension', 'why' => ['de' => 'Produktname', 'en' => 'Product name']],
                ['entity' => 'PostingInstructionBatch', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'PIB-Join', 'en' => 'PIB join']],
                ['entity' => 'PostingInstructionBatch', 'name' => 'value_timestamp', 'role' => 'measure', 'why' => ['de' => 'Wertstellungszeitpunkt', 'en' => 'Value timestamp']],
                ['entity' => 'PostingInstructionBatch', 'name' => 'insertion_timestamp', 'role' => 'measure', 'why' => ['de' => 'Ledger-Insertion-Zeit', 'en' => 'Ledger insertion time']],
                ['entity' => 'PostingInstructionBatch', 'name' => 'client_batch_id', 'role' => 'dimension', 'why' => ['de' => 'Client-Batch-Referenz', 'en' => 'Client batch reference']],
                ['entity' => 'Posting', 'name' => 'client_transaction_id', 'role' => 'key', 'why' => ['de' => 'Posting-Join', 'en' => 'Posting join']],
                ['entity' => 'Posting', 'name' => 'account_id', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'Posting', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Posting-Betrag', 'en' => 'Posting amount']],
                ['entity' => 'Posting', 'name' => 'denomination', 'role' => 'dimension', 'why' => ['de' => 'Währung', 'en' => 'Denomination / currency']],
                ['entity' => 'Posting', 'name' => 'phase', 'role' => 'dimension', 'why' => ['de' => 'committed / pending_outgoing / pending_incoming', 'en' => 'committed / pending_outgoing / pending_incoming']],
                ['entity' => 'Balance', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Balance-Join', 'en' => 'Balance join']],
                ['entity' => 'Balance', 'name' => 'account_id', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'Balance', 'name' => 'total_debit', 'role' => 'measure', 'why' => ['de' => 'Summe Debit', 'en' => 'Total debit']],
                ['entity' => 'Balance', 'name' => 'total_credit', 'role' => 'measure', 'why' => ['de' => 'Summe Credit', 'en' => 'Total credit']],
                ['entity' => 'Restriction', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Restriction-Join', 'en' => 'Restriction join']],
                ['entity' => 'Restriction', 'name' => 'account_id', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'Restriction', 'name' => 'restriction_definition_version_id', 'role' => 'dimension', 'why' => ['de' => 'Restriction-Typ', 'en' => 'Restriction type']],
                ['entity' => 'PaymentDevice', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Device-Join', 'en' => 'Device join']],
                ['entity' => 'PaymentDevice', 'name' => 'account_id', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'PaymentDevice', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Device-Status', 'en' => 'Device status']],
            ],
            'skipTables' => [
                [
                    'name' => 'Posting instruction metadata free text (bulk)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Instruction-Details/Metadata können Gegenpartei-Namen/PII enthalten.',
                        'en' => 'Instruction details/metadata can contain counterparty names/PII.',
                    ],
                ],
                [
                    'name' => 'Payment device tokens / PAN data',
                    'category' => 'security',
                    'reason' => [
                        'de' => 'Kartendaten/Tokens — nie landen; nur Status-Metadata.',
                        'en' => 'Card data/tokens — never land; status metadata only.',
                    ],
                ],
                [
                    'name' => 'Smart Contract source code (product library)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Contract-Code — kein Analytics-Kern; Product Version Metadata reicht.',
                        'en' => 'Contract code — not analytics core; product version metadata suffices.',
                    ],
                ],
                [
                    'name' => 'Full event/webhook stream dumps (bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Streaming-Events — Volumen; nur relevante Facts materialisieren.',
                        'en' => 'Streaming events — volume; materialize only relevant facts.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Posting instruction metadata free text bulk', 'reason' => ['de' => 'PII-Risiko im Freitext', 'en' => 'PII risk in free text']],
                ['name' => 'Payment device tokens / PAN data', 'reason' => ['de' => 'Security — nie laden', 'en' => 'Security — never load']],
                ['name' => 'Smart Contract source code', 'reason' => ['de' => 'Code, kein Analytics-Kern', 'en' => 'Code, not analytics core']],
                ['name' => 'Full event/webhook stream dumps', 'reason' => ['de' => 'Volumen — nur Facts materialisieren', 'en' => 'Volume — materialize facts only']],
            ],
            'dimensions' => [
                [
                    'id' => 'denomination',
                    'label' => ['de' => 'Denomination', 'en' => 'Denomination'],
                    'grain' => ['de' => 'posting.denomination', 'en' => 'posting.denomination'],
                    'notes' => ['de' => 'ISO-Währungscode je Ledger.', 'en' => 'ISO currency code per ledger.'],
                ],
                [
                    'id' => 'product_version',
                    'label' => ['de' => 'Product Version', 'en' => 'Product version'],
                    'grain' => ['de' => 'account.product_version_id', 'en' => 'account.product_version_id'],
                    'notes' => ['de' => 'Versionierte Smart Contracts nicht mit Product-ID verwechseln.', 'en' => 'Do not confuse versioned smart contracts with product id.'],
                ],
                [
                    'id' => 'account_status',
                    'label' => ['de' => 'Account Status', 'en' => 'Account status'],
                    'grain' => ['de' => 'account.status', 'en' => 'account.status'],
                    'notes' => ['de' => 'OPEN vs. CLOSED vs. PENDING_CLOSURE unterscheiden.', 'en' => 'Distinguish OPEN vs CLOSED vs PENDING_CLOSURE.'],
                ],
                [
                    'id' => 'restriction_type',
                    'label' => ['de' => 'Restriction Type', 'en' => 'Restriction type'],
                    'grain' => ['de' => 'restriction.restriction_definition_version_id', 'en' => 'restriction.restriction_definition_version_id'],
                    'notes' => ['de' => 'Hold vs. Block vs. Sanctions-Restriction trennen.', 'en' => 'Separate hold vs block vs sanctions restriction.'],
                ],
                [
                    'id' => 'phase',
                    'label' => ['de' => 'Phase', 'en' => 'Phase'],
                    'grain' => ['de' => 'posting.phase', 'en' => 'posting.phase'],
                    'notes' => ['de' => 'Committed vs. pending Postings nicht vermischen.', 'en' => 'Do not mix committed vs pending postings.'],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Customer',
                    'fields' => ['given_name', 'family_name'],
                    'treatment' => [
                        'de' => 'Namen — taggen; customer id als Join bevorzugen.',
                        'en' => 'Names — tag as PII; prefer customer id as join.',
                    ],
                ],
                [
                    'entity' => 'Customer',
                    'fields' => ['date_of_birth', 'contact_details.addresses'],
                    'treatment' => [
                        'de' => 'Geburtsdatum/Adresse — strikter Zugriff und Retention.',
                        'en' => 'Date of birth/address — strict access and retention.',
                    ],
                ],
                [
                    'entity' => 'Posting',
                    'fields' => ['instruction_details', 'metadata'],
                    'treatment' => [
                        'de' => 'Instruction Metadata kann Namen enthalten — nicht default laden oder redigieren.',
                        'en' => 'Instruction metadata can contain names — do not load by default or redact.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'customer.id, account.id, client_transaction_id, product_version_id.',
                        'en' => 'customer.id, account.id, client_transaction_id, product_version_id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Customer, Account, Posting Meta, Balance — keine Instruction-Freitexte/Tokens.',
                        'en' => 'Customer, account, posting meta, balance — no instruction free text/tokens.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'accounts-opened',
                    'example' => true,
                    'label' => ['de' => 'Accounts Opened', 'en' => 'Accounts opened'],
                    'question' => [
                        'de' => 'Wie viele Accounts wurden in der Periode eröffnet?',
                        'en' => 'How many accounts were opened in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM account WHERE opening_timestamp IN period',
                    'grain' => ['de' => 'Opened Account', 'en' => 'Opened account'],
                    'dimensions' => ['denomination', 'product_version', 'account_status'],
                    'fieldsUsed' => ['Account.id', 'Account.opening_timestamp', 'Account.product_version_id'],
                    'sourceHints' => [
                        'de' => 'opening_timestamp aus Account; Test-/Shadow-Accounts ausschließen.',
                        'en' => 'opening_timestamp from account; exclude test/shadow accounts.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Product Version für neue vs. Legacy Contracts trennen.',
                        'en' => 'Separate by product version for new vs legacy contracts.',
                    ],
                ],
                [
                    'id' => 'active-accounts',
                    'example' => true,
                    'label' => ['de' => 'Active Accounts', 'en' => 'Active accounts'],
                    'question' => [
                        'de' => 'Wie viele Accounts sind aktuell im Status OPEN?',
                        'en' => 'How many accounts currently have status OPEN?',
                    ],
                    'formula' => "COUNT(*) FROM account WHERE status = 'ACCOUNT_STATUS_OPEN'",
                    'grain' => ['de' => 'Active Account (Snapshot)', 'en' => 'Active account (snapshot)'],
                    'dimensions' => ['denomination', 'product_version', 'account_status'],
                    'fieldsUsed' => ['Account.id', 'Account.status', 'Account.customer_id'],
                    'sourceHints' => [
                        'de' => 'Enum-Wert ACCOUNT_STATUS_OPEN exakt matchen; Pending Closure separat.',
                        'en' => 'Match enum value ACCOUNT_STATUS_OPEN exactly; treat pending closure separately.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Accounts ohne aktive Sanctions-Restriction als "healthy active" Variante.',
                        'en' => 'Variant: only accounts without active sanctions restriction as "healthy active".',
                    ],
                ],
                [
                    'id' => 'postings-count',
                    'example' => false,
                    'label' => ['de' => 'Postings Count', 'en' => 'Postings count'],
                    'question' => [
                        'de' => 'Wie viele Committed Postings gab es in der Periode?',
                        'en' => 'How many committed postings occurred in the period?',
                    ],
                    'formula' => "COUNT(*) FROM posting WHERE phase = 'committed' AND value_timestamp IN period",
                    'grain' => ['de' => 'Committed Posting', 'en' => 'Committed posting'],
                    'dimensions' => ['denomination', 'phase'],
                    'fieldsUsed' => ['Posting.client_transaction_id', 'PostingInstructionBatch.value_timestamp', 'Posting.account_id'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Aggregat je Account/Denomination/Tag nutzen.',
                        'en' => 'Use aggregate per account/denomination/day at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Pending Postings separat als Forecast-Signal auswerten.',
                        'en' => 'Evaluate pending postings separately as a forecast signal.',
                    ],
                ],
                [
                    'id' => 'average-account-balance',
                    'example' => false,
                    'label' => ['de' => 'Average Account Balance', 'en' => 'Average account balance'],
                    'question' => [
                        'de' => 'Wie hoch ist der durchschnittliche Account-Saldo je Product Version?',
                        'en' => 'What is the average account balance per product version?',
                    ],
                    'formula' => 'AVG(total_credit - total_debit) FROM balance JOIN account ON account.id = balance.account_id GROUP BY product_version_id',
                    'grain' => ['de' => 'Account (letzter Balance Output)', 'en' => 'Account (latest balance output)'],
                    'dimensions' => ['denomination', 'product_version'],
                    'fieldsUsed' => ['Balance.total_debit', 'Balance.total_credit', 'Balance.account_id', 'Account.product_version_id'],
                    'sourceHints' => [
                        'de' => 'Nur den aktuellen Live-Balance-Output je Balance Definition verwenden.',
                        'en' => 'Use only the current live balance output per balance definition.',
                    ],
                    'adapt' => [
                        'de' => 'Overdrawn Balances (negativ) getrennt ausweisen.',
                        'en' => 'Report overdrawn balances (negative) separately.',
                    ],
                ],
                [
                    'id' => 'restrictions-active',
                    'example' => false,
                    'label' => ['de' => 'Restrictions Active', 'en' => 'Restrictions active'],
                    'question' => [
                        'de' => 'Wie viele Accounts haben aktuell eine aktive Restriction?',
                        'en' => 'How many accounts currently have an active restriction?',
                    ],
                    'formula' => 'COUNT(DISTINCT account_id) FROM restriction WHERE effective_timestamp <= now()',
                    'grain' => ['de' => 'Account mit aktiver Restriction', 'en' => 'Account with active restriction'],
                    'dimensions' => ['restriction_type', 'account_status'],
                    'fieldsUsed' => ['Restriction.account_id', 'Restriction.restriction_definition_version_id'],
                    'sourceHints' => [
                        'de' => 'Restriction Type (Hold/Block/Sanctions) als Dim für Risk-Slices.',
                        'en' => 'Restriction type (hold/block/sanctions) as dim for risk slices.',
                    ],
                    'adapt' => [
                        'de' => 'Sanctions-Restrictions separat priorisiert auswerten.',
                        'en' => 'Evaluate sanctions restrictions with separate priority.',
                    ],
                ],
            ],
            'tools' => $bankingTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'finastra',
            'domain' => 'banking',
            'order' => 240,
            'label' => ['de' => 'Finastra (Fusion)', 'en' => 'Finastra (Fusion)'],
            'shortPurpose' => [
                'de' => 'Core Banking: CIF/Account/Facility, GL Entries — Finastra-Load, PII und Banking-Measures; keine Klartext-Zahlungspayloads.',
                'en' => 'Core banking: CIF/account/facility, GL entries — Finastra load, PII and banking measures; no cleartext payment payloads by default.',
            ],
            'entities' => [
                [
                    'id' => 'customer',
                    'label' => ['de' => 'Customer (CIF)', 'en' => 'Customer (CIF)'],
                    'description' => [
                        'de' => 'Customer Information File — ShortName, Address, TaxId; Kern-Stammdaten mit PII.',
                        'en' => 'Customer information file — short name, address, tax ID; core master data with PII.',
                    ],
                    'grain' => ['de' => 'Ein Customer (CIFNumber)', 'en' => 'One customer (CIFNumber)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'account',
                    'label' => ['de' => 'Account (DDA)', 'en' => 'Account (DDA)'],
                    'description' => [
                        'de' => 'Demand Deposit Account — ProductCode, Currency, Balance; Fact-Anker für GL Entries.',
                        'en' => 'Demand deposit account — product code, currency, balance; fact anchor for GL entries.',
                    ],
                    'grain' => ['de' => 'Ein Account (AccountNumber)', 'en' => 'One account (AccountNumber)'],
                    'role' => ['de' => 'Dimension / Fact-Anker', 'en' => 'Dimension / fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'product',
                    'label' => ['de' => 'Product', 'en' => 'Product'],
                    'description' => [
                        'de' => 'Product Definition — ProductLine (Current, Savings, Lending).',
                        'en' => 'Product definition — product line (current, savings, lending).',
                    ],
                    'grain' => ['de' => 'Ein Product (ProductCode)', 'en' => 'One product (ProductCode)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'facility',
                    'label' => ['de' => 'Facility', 'en' => 'Facility'],
                    'description' => [
                        'de' => 'Loan Facility — Start-/Maturity Date, FacilityLimit je Customer.',
                        'en' => 'Loan facility — start/maturity date, facility limit per customer.',
                    ],
                    'grain' => ['de' => 'Eine Facility (FacilityId)', 'en' => 'One facility (FacilityId)'],
                    'role' => ['de' => 'Produkt-Fact', 'en' => 'Product fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'gl_entry',
                    'label' => ['de' => 'GL Entry', 'en' => 'GL entry'],
                    'description' => [
                        'de' => 'General Ledger Entry — Posting/Value Date, Amount, Dr/Cr Indicator; kein Freitext-Payload.',
                        'en' => 'General ledger entry — posting/value date, amount, dr/cr indicator; no free-text payload.',
                    ],
                    'grain' => ['de' => 'Ein GL Entry (EntryId)', 'en' => 'One GL entry (EntryId)'],
                    'role' => ['de' => 'Ledger-Fact (high volume)', 'en' => 'Ledger fact (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'limit',
                    'label' => ['de' => 'Limit', 'en' => 'Limit'],
                    'description' => [
                        'de' => 'Credit Limit — LimitAmount, Expiry je Customer.',
                        'en' => 'Credit limit — limit amount, expiry per customer.',
                    ],
                    'grain' => ['de' => 'Ein Limit (LimitId)', 'en' => 'One limit (LimitId)'],
                    'role' => ['de' => 'Credit-Fact', 'en' => 'Credit fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'charge',
                    'label' => ['de' => 'Charge', 'en' => 'Charge'],
                    'description' => [
                        'de' => 'Charge/Fee Event je Account.',
                        'en' => 'Charge/fee event per account.',
                    ],
                    'grain' => ['de' => 'Ein Charge Event (ChargeId)', 'en' => 'One charge event (ChargeId)'],
                    'role' => ['de' => 'Fee-Fact', 'en' => 'Fee fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'relationship_officer',
                    'label' => ['de' => 'Relationship Officer', 'en' => 'Relationship officer'],
                    'description' => [
                        'de' => 'RM/Branch — Staff-Dimension, kein Customer-PII.',
                        'en' => 'RM/branch — staff dimension, not customer PII.',
                    ],
                    'grain' => ['de' => 'Ein Officer (RelationshipManagerId)', 'en' => 'One officer (RelationshipManagerId)'],
                    'role' => ['de' => 'Staff-Dimension', 'en' => 'Staff dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Customer', 'name' => 'CIFNumber', 'role' => 'key', 'why' => ['de' => 'CIF-Join', 'en' => 'CIF join']],
                ['entity' => 'Customer', 'name' => 'ShortName', 'role' => 'pii', 'why' => ['de' => 'Kundenname / PII', 'en' => 'Customer name / PII']],
                ['entity' => 'Customer', 'name' => 'Address', 'role' => 'pii', 'why' => ['de' => 'Adresse / PII', 'en' => 'Address / PII']],
                ['entity' => 'Customer', 'name' => 'TaxId', 'role' => 'pii', 'why' => ['de' => 'Steuer-ID / PII', 'en' => 'Tax ID / PII']],
                ['entity' => 'Customer', 'name' => 'DateOfBirth', 'role' => 'pii', 'why' => ['de' => 'Geburtsdatum / PII', 'en' => 'Date of birth / PII']],
                ['entity' => 'Account', 'name' => 'AccountNumber', 'role' => 'key', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
                ['entity' => 'Account', 'name' => 'CIFNumber', 'role' => 'dimension', 'why' => ['de' => 'Customer-Rückjoin', 'en' => 'Customer back-join']],
                ['entity' => 'Account', 'name' => 'ProductCode', 'role' => 'dimension', 'why' => ['de' => 'Product-Rückjoin', 'en' => 'Product back-join']],
                ['entity' => 'Account', 'name' => 'Currency', 'role' => 'dimension', 'why' => ['de' => 'Kontowährung', 'en' => 'Account currency']],
                ['entity' => 'Account', 'name' => 'OpenDate', 'role' => 'measure', 'why' => ['de' => 'Konto-Eröffnungsdatum', 'en' => 'Account open date']],
                ['entity' => 'Account', 'name' => 'Balance', 'role' => 'measure', 'why' => ['de' => 'Aktueller Saldo', 'en' => 'Current balance']],
                ['entity' => 'Product', 'name' => 'ProductCode', 'role' => 'key', 'why' => ['de' => 'Product-Join', 'en' => 'Product join']],
                ['entity' => 'Product', 'name' => 'ProductLine', 'role' => 'dimension', 'why' => ['de' => 'Produktlinie', 'en' => 'Product line']],
                ['entity' => 'Facility', 'name' => 'FacilityId', 'role' => 'key', 'why' => ['de' => 'Facility-Join', 'en' => 'Facility join']],
                ['entity' => 'Facility', 'name' => 'CIFNumber', 'role' => 'dimension', 'why' => ['de' => 'Customer-Rückjoin', 'en' => 'Customer back-join']],
                ['entity' => 'Facility', 'name' => 'ProductCode', 'role' => 'dimension', 'why' => ['de' => 'Product-Rückjoin', 'en' => 'Product back-join']],
                ['entity' => 'Facility', 'name' => 'StartDate', 'role' => 'measure', 'why' => ['de' => 'Facility-Beginn', 'en' => 'Facility start']],
                ['entity' => 'Facility', 'name' => 'FacilityLimit', 'role' => 'measure', 'why' => ['de' => 'Kreditrahmen', 'en' => 'Facility limit']],
                ['entity' => 'GLEntry', 'name' => 'EntryId', 'role' => 'key', 'why' => ['de' => 'GL-Entry-Join', 'en' => 'GL entry join']],
                ['entity' => 'GLEntry', 'name' => 'AccountNumber', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'GLEntry', 'name' => 'PostingDate', 'role' => 'measure', 'why' => ['de' => 'Buchungsdatum', 'en' => 'Posting date']],
                ['entity' => 'GLEntry', 'name' => 'Amount', 'role' => 'measure', 'why' => ['de' => 'Buchungsbetrag', 'en' => 'Posting amount']],
                ['entity' => 'GLEntry', 'name' => 'DrCrIndicator', 'role' => 'dimension', 'why' => ['de' => 'Debit / Credit', 'en' => 'Debit / credit']],
                ['entity' => 'Limit', 'name' => 'LimitId', 'role' => 'key', 'why' => ['de' => 'Limit-Join', 'en' => 'Limit join']],
                ['entity' => 'Limit', 'name' => 'LimitAmount', 'role' => 'measure', 'why' => ['de' => 'Limitbetrag', 'en' => 'Limit amount']],
                ['entity' => 'Limit', 'name' => 'ExpiryDate', 'role' => 'measure', 'why' => ['de' => 'Ablaufdatum', 'en' => 'Expiry date']],
                ['entity' => 'Charge', 'name' => 'ChargeId', 'role' => 'key', 'why' => ['de' => 'Charge-Join', 'en' => 'Charge join']],
                ['entity' => 'Charge', 'name' => 'AccountNumber', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'Charge', 'name' => 'Amount', 'role' => 'measure', 'why' => ['de' => 'Gebührenbetrag', 'en' => 'Charge amount']],
                ['entity' => 'RelationshipOfficer', 'name' => 'RelationshipManagerId', 'role' => 'key', 'why' => ['de' => 'RM-Join', 'en' => 'RM join']],
                ['entity' => 'RelationshipOfficer', 'name' => 'BranchCode', 'role' => 'dimension', 'why' => ['de' => 'Branch-Dim', 'en' => 'Branch dim']],
            ],
            'skipTables' => [
                [
                    'name' => 'Payment message bodies (SWIFT / ISO 20022 XML)',
                    'category' => 'payment',
                    'reason' => [
                        'de' => 'Klartext-Zahlungsnachrichten — Security- und PII-Risiko; nur Status/Referenz laden.',
                        'en' => 'Cleartext payment messages — security and PII risk; load status/reference only.',
                    ],
                ],
                [
                    'name' => 'GL entry narrative / description free text (bulk)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Freitext kann Gegenpartei-Namen/PII enthalten — nicht default laden.',
                        'en' => 'Free text can contain counterparty names/PII — do not load by default.',
                    ],
                ],
                [
                    'name' => 'KYC / collateral document scans',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Dokument-Binaries — kein Analytics-Nutzen; Status/Expiry reicht.',
                        'en' => 'Document binaries — no analytics value; status/expiry suffices.',
                    ],
                ],
                [
                    'name' => 'Full audit / event log tables (bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Audit/Event-Tabellen — Volumen, technisches Rauschen; Meta/Agg bevorzugen.',
                        'en' => 'Audit/event tables — volume, technical noise; prefer meta/agg.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Payment message bodies (SWIFT/ISO 20022)', 'reason' => ['de' => 'Klartext-Payloads — nie laden', 'en' => 'Cleartext payloads — never load']],
                ['name' => 'GL entry narrative/description bulk', 'reason' => ['de' => 'PII-Risiko im Freitext', 'en' => 'PII risk in free text']],
                ['name' => 'KYC / collateral document scans', 'reason' => ['de' => 'Binaries ohne KPI-Nutzen', 'en' => 'Binaries without KPI value']],
                ['name' => 'Full audit / event log tables', 'reason' => ['de' => 'Volumen — Meta/Agg', 'en' => 'Volume — meta/agg']],
            ],
            'dimensions' => [
                [
                    'id' => 'currency',
                    'label' => ['de' => 'Currency', 'en' => 'Currency'],
                    'grain' => ['de' => 'account.Currency', 'en' => 'account.Currency'],
                    'notes' => ['de' => 'ISO-Code bevorzugen.', 'en' => 'Prefer ISO code.'],
                ],
                [
                    'id' => 'product_line',
                    'label' => ['de' => 'Product Line', 'en' => 'Product line'],
                    'grain' => ['de' => 'product.ProductLine', 'en' => 'product.ProductLine'],
                    'notes' => ['de' => 'Current vs. Savings vs. Lending nicht mischen.', 'en' => 'Do not mix current vs savings vs lending.'],
                ],
                [
                    'id' => 'dr_cr_indicator',
                    'label' => ['de' => 'Dr/Cr Indicator', 'en' => 'Dr/Cr indicator'],
                    'grain' => ['de' => 'gl_entry.DrCrIndicator', 'en' => 'gl_entry.DrCrIndicator'],
                    'notes' => ['de' => 'Debit und Credit getrennt aggregieren.', 'en' => 'Aggregate debit and credit separately.'],
                ],
                [
                    'id' => 'branch',
                    'label' => ['de' => 'Branch', 'en' => 'Branch'],
                    'grain' => ['de' => 'relationship_officer.BranchCode', 'en' => 'relationship_officer.BranchCode'],
                    'notes' => ['de' => 'Für Branch-Performance-Slices.', 'en' => 'For branch performance slices.'],
                ],
                [
                    'id' => 'facility_status',
                    'label' => ['de' => 'Facility Status', 'en' => 'Facility status'],
                    'grain' => ['de' => 'facility.StartDate / MaturityDate (derived)', 'en' => 'facility.StartDate / MaturityDate (derived)'],
                    'notes' => ['de' => 'Active vs. Matured Facilities aus Datumsfeldern ableiten.', 'en' => 'Derive active vs matured facilities from date fields.'],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Customer',
                    'fields' => ['ShortName', 'Address'],
                    'treatment' => [
                        'de' => 'Name und Adresse — taggen; CIFNumber als Join bevorzugen.',
                        'en' => 'Name and address — tag as PII; prefer CIFNumber as join.',
                    ],
                ],
                [
                    'entity' => 'Customer',
                    'fields' => ['TaxId', 'DateOfBirth'],
                    'treatment' => [
                        'de' => 'Steuer-ID/Geburtsdatum — strikter Zugriff und Retention.',
                        'en' => 'Tax ID/date of birth — strict access and retention.',
                    ],
                ],
                [
                    'entity' => 'GLEntry',
                    'fields' => ['narrative / description text'],
                    'treatment' => [
                        'de' => 'Buchungstext kann Namen enthalten — nicht default laden oder redigieren.',
                        'en' => 'Posting text can contain names — do not load by default or redact.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'CIFNumber, AccountNumber, FacilityId, TaxId (hashed).',
                        'en' => 'CIFNumber, AccountNumber, FacilityId, TaxId (hashed).',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Customer, Account, Facility, GL Entry Meta — keine Payment Bodies.',
                        'en' => 'Customer, account, facility, GL entry meta — no payment bodies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'accounts-opened',
                    'example' => true,
                    'label' => ['de' => 'Accounts Opened', 'en' => 'Accounts opened'],
                    'question' => [
                        'de' => 'Wie viele Accounts wurden in der Periode eröffnet?',
                        'en' => 'How many accounts were opened in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM account WHERE OpenDate IN period',
                    'grain' => ['de' => 'Opened Account', 'en' => 'Opened account'],
                    'dimensions' => ['currency', 'product_line', 'branch'],
                    'fieldsUsed' => ['Account.AccountNumber', 'Account.OpenDate', 'Account.ProductCode'],
                    'sourceHints' => [
                        'de' => 'OpenDate aus Account; interne Test-/Suspense-Accounts ausschließen.',
                        'en' => 'OpenDate from account; exclude internal test/suspense accounts.',
                    ],
                    'adapt' => [
                        'de' => 'Retail vs. Corporate CIF-Segment trennen.',
                        'en' => 'Separate retail vs corporate CIF segment.',
                    ],
                ],
                [
                    'id' => 'active-accounts',
                    'example' => true,
                    'label' => ['de' => 'Active Accounts', 'en' => 'Active accounts'],
                    'question' => [
                        'de' => 'Wie viele Accounts sind aktuell aktiv (nicht geschlossen)?',
                        'en' => 'How many accounts are currently active (not closed)?',
                    ],
                    'formula' => 'COUNT(*) FROM account WHERE close_date IS NULL',
                    'grain' => ['de' => 'Active Account (Snapshot)', 'en' => 'Active account (snapshot)'],
                    'dimensions' => ['currency', 'product_line', 'branch'],
                    'fieldsUsed' => ['Account.AccountNumber', 'Account.ProductCode', 'Account.CIFNumber'],
                    'sourceHints' => [
                        'de' => 'Fusion hat kein einheitliches Status-Feld je Deployment — close_date/derived Flag nutzen.',
                        'en' => 'Fusion has no uniform status field per deployment — use close_date/derived flag.',
                    ],
                    'adapt' => [
                        'de' => 'Dormant Accounts (keine GL Entries > 12 Monate) separat flaggen.',
                        'en' => 'Separately flag dormant accounts (no GL entries > 12 months).',
                    ],
                ],
                [
                    'id' => 'gl-entries-count',
                    'example' => false,
                    'label' => ['de' => 'GL Entries Count', 'en' => 'GL entries count'],
                    'question' => [
                        'de' => 'Wie viele GL Entries gab es in der Periode?',
                        'en' => 'How many GL entries occurred in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM gl_entry WHERE PostingDate IN period',
                    'grain' => ['de' => 'GL Entry', 'en' => 'GL entry'],
                    'dimensions' => ['currency', 'dr_cr_indicator'],
                    'fieldsUsed' => ['GLEntry.EntryId', 'GLEntry.PostingDate', 'GLEntry.AccountNumber'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Tages-Aggregate je Account/DrCr nutzen.',
                        'en' => 'Use daily aggregates per account/Dr-Cr at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Reversal-Entries separat aus Counts nehmen.',
                        'en' => 'Exclude reversal entries from counts separately.',
                    ],
                ],
                [
                    'id' => 'average-account-balance',
                    'example' => false,
                    'label' => ['de' => 'Average Account Balance', 'en' => 'Average account balance'],
                    'question' => [
                        'de' => 'Wie hoch ist der durchschnittliche Kontosaldo je ProductLine?',
                        'en' => 'What is the average account balance per product line?',
                    ],
                    'formula' => 'AVG(Balance) FROM account JOIN product ON product.ProductCode = account.ProductCode GROUP BY ProductLine',
                    'grain' => ['de' => 'Account (Snapshot)', 'en' => 'Account (snapshot)'],
                    'dimensions' => ['currency', 'product_line'],
                    'fieldsUsed' => ['Account.Balance', 'Account.ProductCode', 'Product.ProductLine'],
                    'sourceHints' => [
                        'de' => 'Multi-Currency Accounts vor AVG in Base Currency konvertieren.',
                        'en' => 'Convert multi-currency accounts to base currency before AVG.',
                    ],
                    'adapt' => [
                        'de' => 'Negative Balances (Overdraft) getrennt ausweisen.',
                        'en' => 'Report negative balances (overdraft) separately.',
                    ],
                ],
                [
                    'id' => 'facility-utilization',
                    'example' => false,
                    'label' => ['de' => 'Facility Utilization', 'en' => 'Facility utilization'],
                    'question' => [
                        'de' => 'Wie hoch ist die Ausnutzung der Loan Facilities?',
                        'en' => 'What is the utilization of loan facilities?',
                    ],
                    'formula' => 'SUM(drawn_amount) / SUM(FacilityLimit) FROM facility',
                    'grain' => ['de' => 'Facility (Snapshot)', 'en' => 'Facility (snapshot)'],
                    'dimensions' => ['branch', 'facility_status'],
                    'fieldsUsed' => ['Facility.FacilityId', 'Facility.FacilityLimit', 'Facility.CIFNumber'],
                    'sourceHints' => [
                        'de' => 'drawn_amount aus verknüpften Loan-Account-Balances ableiten.',
                        'en' => 'Derive drawn_amount from linked loan account balances.',
                    ],
                    'adapt' => [
                        'de' => 'Matured Facilities (MaturityDate < today) aus Nenner ausschließen.',
                        'en' => 'Exclude matured facilities (MaturityDate < today) from denominator.',
                    ],
                ],
            ],
            'tools' => $bankingTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
