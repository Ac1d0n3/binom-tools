<?php

/**
 * Wave 9 supplier library entries — ERP / Finance / HCM (full template depth).
 *
 * Emphasize vouchers/master data (GL, AP/AR, debtors/creditors) and employee master data.
 * Workforce PII is strict by default: no salary/compensation cleartext in marts, tax IDs
 * and national identifiers hashed or access-restricted.
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $erpFinanceHcmTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    return [
        [
            'id' => 'datev',
            'domain' => 'finance',
            'order' => 340,
            'label' => ['de' => 'DATEV', 'en' => 'DATEV'],
            'shortPurpose' => [
                'de' => 'DACH-Buchhaltung/Steuer: Buchungssätze, Debitoren/Kreditoren, Kontenrahmen — Belege als Meta; Bankdaten und Steuer-IDs strikt schützen.',
                'en' => 'DACH accounting/tax: booking records, debtors/creditors, chart of accounts — documents as meta; strictly protect bank data and tax IDs.',
            ],
            'entities' => [
                [
                    'id' => 'booking_record',
                    'label' => ['de' => 'Buchungssatz', 'en' => 'Booking record'],
                    'description' => [
                        'de' => 'DATEV-Buchungssatz (Beleg/Position) — Kern-Voucher-Fact für GL-Analytics.',
                        'en' => 'DATEV booking record (document/line) — core voucher fact for GL analytics.',
                    ],
                    'grain' => ['de' => 'Eine Buchungszeile (Beleg + Position)', 'en' => 'One booking line (document + position)'],
                    'role' => ['de' => 'Fact-Anker', 'en' => 'Fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'account',
                    'label' => ['de' => 'Sachkonto', 'en' => 'Account'],
                    'description' => [
                        'de' => 'Sachkonto aus SKR03/SKR04-Kontenrahmen — GL-Dimension.',
                        'en' => 'General ledger account from the SKR03/SKR04 chart of accounts — GL dimension.',
                    ],
                    'grain' => ['de' => 'Ein Sachkonto (Kontonummer)', 'en' => 'One account (account number)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'debtor',
                    'label' => ['de' => 'Debitor', 'en' => 'Debtor'],
                    'description' => [
                        'de' => 'Kunden-Personenkonto — Name/Adresse/Bank sind PII; Steuer-ID sensibel.',
                        'en' => 'Customer sub-ledger account — name/address/bank are PII; tax ID is sensitive.',
                    ],
                    'grain' => ['de' => 'Ein Debitor (Debitorennummer)', 'en' => 'One debtor (debtor number)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'creditor',
                    'label' => ['de' => 'Kreditor', 'en' => 'Creditor'],
                    'description' => [
                        'de' => 'Lieferanten-Personenkonto — analog Debitor; Bankdaten strikt schützen.',
                        'en' => 'Vendor sub-ledger account — analogous to debtor; strictly protect bank data.',
                    ],
                    'grain' => ['de' => 'Ein Kreditor (Kreditorennummer)', 'en' => 'One creditor (creditor number)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'cost_center',
                    'label' => ['de' => 'Kostenstelle', 'en' => 'Cost center'],
                    'description' => [
                        'de' => 'Kostenstelle/-träger — Buchungssatz-Dimension für Kostenanalysen.',
                        'en' => 'Cost center/unit — booking record dimension for cost analysis.',
                    ],
                    'grain' => ['de' => 'Eine Kostenstelle', 'en' => 'One cost center'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'tax_key',
                    'label' => ['de' => 'Steuerschlüssel', 'en' => 'Tax key'],
                    'description' => [
                        'de' => 'Steuerschlüssel/BU-Schlüssel — Zuordnung zu USt-Sätzen.',
                        'en' => 'Tax key / automatic posting key — mapping to VAT rates.',
                    ],
                    'grain' => ['de' => 'Ein Steuerschlüssel', 'en' => 'One tax key'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'document',
                    'label' => ['de' => 'Beleg', 'en' => 'Document (voucher)'],
                    'description' => [
                        'de' => 'Beleg-Metadaten (Nummer, Datum, Betrag); Scans/Bilder nicht laden.',
                        'en' => 'Document/voucher metadata (number, date, amount); do not load scans/images.',
                    ],
                    'grain' => ['de' => 'Ein Beleg', 'en' => 'One document/voucher'],
                    'role' => ['de' => 'Fact (Meta)', 'en' => 'Fact (meta)'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'BookingRecord', 'name' => 'beleg_feld1', 'role' => 'key', 'why' => ['de' => 'Belegfeld 1 / Join-Key', 'en' => 'Document field 1 / join key']],
                ['entity' => 'BookingRecord', 'name' => 'booking_date', 'role' => 'measure', 'why' => ['de' => 'Perioden-Grain', 'en' => 'Period grain']],
                ['entity' => 'BookingRecord', 'name' => 'account_number', 'role' => 'dimension', 'why' => ['de' => 'Konto-Join', 'en' => 'Account join']],
                ['entity' => 'BookingRecord', 'name' => 'contra_account_number', 'role' => 'dimension', 'why' => ['de' => 'Gegenkonto', 'en' => 'Contra account']],
                ['entity' => 'BookingRecord', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Buchungsbetrag', 'en' => 'Booking amount']],
                ['entity' => 'BookingRecord', 'name' => 'tax_key', 'role' => 'dimension', 'why' => ['de' => 'Steuerschlüssel-Join', 'en' => 'Tax key join']],
                ['entity' => 'BookingRecord', 'name' => 'cost_center', 'role' => 'dimension', 'why' => ['de' => 'Kostenstellen-Join', 'en' => 'Cost center join']],
                ['entity' => 'BookingRecord', 'name' => 'posting_text', 'role' => 'dimension', 'why' => ['de' => 'Buchungstext (kann Personenbezug enthalten)', 'en' => 'Posting text (may contain personal references)']],
                ['entity' => 'Account', 'name' => 'account_number', 'role' => 'key', 'why' => ['de' => 'Konto-Join', 'en' => 'Account join']],
                ['entity' => 'Account', 'name' => 'account_name', 'role' => 'dimension', 'why' => ['de' => 'Kontobezeichnung', 'en' => 'Account name']],
                ['entity' => 'Account', 'name' => 'account_type', 'role' => 'dimension', 'why' => ['de' => 'Sachkonto vs. Personenkonto', 'en' => 'GL account vs sub-ledger account']],
                ['entity' => 'Account', 'name' => 'skr_type', 'role' => 'dimension', 'why' => ['de' => 'SKR03 / SKR04', 'en' => 'SKR03 / SKR04']],
                ['entity' => 'Debtor', 'name' => 'debtor_number', 'role' => 'key', 'why' => ['de' => 'Debitoren-Join', 'en' => 'Debtor join']],
                ['entity' => 'Debtor', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'Debtor', 'name' => 'address', 'role' => 'pii', 'why' => ['de' => 'Adresse / PII', 'en' => 'Address / PII']],
                ['entity' => 'Debtor', 'name' => 'tax_id', 'role' => 'pii', 'why' => ['de' => 'USt-IdNr./Steuernummer — hashen', 'en' => 'VAT ID/tax number — hash']],
                ['entity' => 'Debtor', 'name' => 'iban', 'role' => 'pii', 'why' => ['de' => 'Bankverbindung — sensibel', 'en' => 'Bank account — sensitive']],
                ['entity' => 'Creditor', 'name' => 'creditor_number', 'role' => 'key', 'why' => ['de' => 'Kreditoren-Join', 'en' => 'Creditor join']],
                ['entity' => 'Creditor', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'Creditor', 'name' => 'address', 'role' => 'pii', 'why' => ['de' => 'Adresse / PII', 'en' => 'Address / PII']],
                ['entity' => 'Creditor', 'name' => 'tax_id', 'role' => 'pii', 'why' => ['de' => 'USt-IdNr./Steuernummer — hashen', 'en' => 'VAT ID/tax number — hash']],
                ['entity' => 'Creditor', 'name' => 'iban', 'role' => 'pii', 'why' => ['de' => 'Bankverbindung — sensibel', 'en' => 'Bank account — sensitive']],
                ['entity' => 'CostCenter', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Kostenstellen-Join', 'en' => 'Cost center join']],
                ['entity' => 'CostCenter', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Kostenstellenbezeichnung', 'en' => 'Cost center name']],
                ['entity' => 'TaxKey', 'name' => 'code', 'role' => 'key', 'why' => ['de' => 'Steuerschlüssel-Code', 'en' => 'Tax key code']],
                ['entity' => 'TaxKey', 'name' => 'rate', 'role' => 'measure', 'why' => ['de' => 'Steuersatz', 'en' => 'Tax rate']],
                ['entity' => 'TaxKey', 'name' => 'description', 'role' => 'dimension', 'why' => ['de' => 'Beschreibung', 'en' => 'Description']],
                ['entity' => 'Document', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Beleg-Join', 'en' => 'Document join']],
                ['entity' => 'Document', 'name' => 'document_date', 'role' => 'measure', 'why' => ['de' => 'Belegdatum', 'en' => 'Document date']],
                ['entity' => 'Document', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Belegbetrag', 'en' => 'Document amount']],
                ['entity' => 'Document', 'name' => 'document_type', 'role' => 'dimension', 'why' => ['de' => 'Rechnung / Quittung / Gutschrift', 'en' => 'Invoice / receipt / credit note']],
            ],
            'skipTables' => [
                [
                    'name' => 'Belegbilder / gescannte Dokumente',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Scan-Binaries — kein Analytics-Nutzen; Belegdatum/-betrag reichen als Meta.',
                        'en' => 'Scan binaries — no analytics value; document date/amount suffice as meta.',
                    ],
                ],
                [
                    'name' => 'DATEV Lohn und Gehalt (Payroll)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Separates strenges Workforce-PII-Modul — Gehalt nie im Finance-Warehouse.',
                        'en' => 'Separate strict workforce-PII module — never land salary in the finance warehouse.',
                    ],
                ],
                [
                    'name' => 'Volle Bankauszug-Transaktionsdumps',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Hohes Volumen und Bankgeheimnis — nur gebuchte Zeilen laden.',
                        'en' => 'High volume and banking secrecy — load booked lines only.',
                    ],
                ],
                [
                    'name' => 'Verfahrensdokumentation-Exporte',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Compliance-Archiv, kein Analytics-Kern.',
                        'en' => 'Compliance archive, not analytics core.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Belegbilder / Scans', 'reason' => ['de' => 'Binaries — Meta reicht', 'en' => 'Binaries — meta suffices']],
                ['name' => 'DATEV Lohn und Gehalt', 'reason' => ['de' => 'Separates Payroll-PII-Modul', 'en' => 'Separate payroll PII module']],
                ['name' => 'Volle Bankauszug-Dumps', 'reason' => ['de' => 'Volumen + Bankgeheimnis', 'en' => 'Volume + banking secrecy']],
                ['name' => 'Verfahrensdokumentation-Exporte', 'reason' => ['de' => 'Compliance-Archiv', 'en' => 'Compliance archive']],
            ],
            'dimensions' => [
                [
                    'id' => 'cost_center',
                    'label' => ['de' => 'Kostenstelle', 'en' => 'Cost center'],
                    'grain' => ['de' => 'booking_record.cost_center', 'en' => 'booking_record.cost_center'],
                    'notes' => [
                        'de' => 'Nur befüllt wenn Kostenstellenrechnung aktiv ist.',
                        'en' => 'Only populated when cost center accounting is active.',
                    ],
                ],
                [
                    'id' => 'account_type',
                    'label' => ['de' => 'Kontoart', 'en' => 'Account type'],
                    'grain' => ['de' => 'account.account_type', 'en' => 'account.account_type'],
                    'notes' => [
                        'de' => 'Sachkonto vs. Personenkonto (Debitor/Kreditor) nicht vermischen.',
                        'en' => 'Do not mix GL accounts with sub-ledger (debtor/creditor) accounts.',
                    ],
                ],
                [
                    'id' => 'tax_rate',
                    'label' => ['de' => 'Steuersatz', 'en' => 'Tax rate'],
                    'grain' => ['de' => 'tax_key.rate', 'en' => 'tax_key.rate'],
                    'notes' => [
                        'de' => 'BU-Schlüssel je Land/Zeitraum ändert sich — SCD2 empfohlen.',
                        'en' => 'Automatic posting keys change by country/period — SCD2 recommended.',
                    ],
                ],
                [
                    'id' => 'client',
                    'label' => ['de' => 'Mandant', 'en' => 'Client (Mandant)'],
                    'grain' => ['de' => 'Mandantennummer', 'en' => 'Client number'],
                    'notes' => [
                        'de' => 'Bei Multi-Mandanten-Setups als Tenant-Slice führen.',
                        'en' => 'Carry as a tenant slice for multi-client setups.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Debtor',
                    'fields' => ['name', 'address'],
                    'treatment' => [
                        'de' => 'Direkte Identifikatoren — Debitorennummer als Join bevorzugen.',
                        'en' => 'Direct identifiers — prefer debtor number as join.',
                    ],
                ],
                [
                    'entity' => 'Creditor',
                    'fields' => ['name', 'address'],
                    'treatment' => [
                        'de' => 'Direkte Identifikatoren — Kreditorennummer als Join bevorzugen.',
                        'en' => 'Direct identifiers — prefer creditor number as join.',
                    ],
                ],
                [
                    'entity' => 'Debtor / Creditor',
                    'fields' => ['tax_id', 'iban'],
                    'treatment' => [
                        'de' => 'Steuer-ID und IBAN — hashen oder Zugriff strikt einschränken.',
                        'en' => 'Tax ID and IBAN — hash or strictly restrict access.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'Debitoren-/Kreditorennummer, Steuernummer/USt-IdNr. (hashed), IBAN (hashed).',
                        'en' => 'Debtor/creditor number, tax number/VAT ID (hashed), IBAN (hashed).',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Debitor, Kreditor, Buchungssatz, Beleg — DATEV-Export + Warehouse-Kopien.',
                        'en' => 'Debtor, creditor, booking record, document — DATEV export + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'revenue-booked',
                    'example' => true,
                    'label' => ['de' => 'Umsatz gebucht', 'en' => 'Revenue booked'],
                    'question' => [
                        'de' => 'Wie viel Umsatz wurde in der Periode auf Erlöskonten gebucht?',
                        'en' => 'How much revenue was booked to revenue accounts in the period?',
                    ],
                    'formula' => "SUM(amount) FROM booking_record JOIN account ON account_number WHERE account.account_type = 'Erlöskonto' AND booking_date IN period",
                    'grain' => ['de' => 'Buchungszeile', 'en' => 'Booking line'],
                    'dimensions' => ['cost_center', 'account_type', 'client'],
                    'fieldsUsed' => ['BookingRecord.amount', 'BookingRecord.account_number', 'BookingRecord.booking_date', 'Account.account_type'],
                    'sourceHints' => [
                        'de' => 'Erlöskonten über SKR03/SKR04-Kontenrahmen-Range identifizieren.',
                        'en' => 'Identify revenue accounts via the SKR03/SKR04 chart-of-accounts range.',
                    ],
                    'adapt' => [
                        'de' => 'Storno-Buchungen und Periodenabgrenzung (Rechnungs- vs. Buchungsdatum) klären.',
                        'en' => 'Clarify reversal postings and period cutoff (invoice vs booking date).',
                    ],
                ],
                [
                    'id' => 'open-items-debtors',
                    'example' => true,
                    'label' => ['de' => 'Offene Posten Debitoren', 'en' => 'Open items (debtors)'],
                    'question' => [
                        'de' => 'Wie hoch sind die offenen Forderungen gegenüber Debitoren?',
                        'en' => 'How high are open receivables against debtors?',
                    ],
                    'formula' => 'SUM(amount) FROM booking_record WHERE account_number IN (debtor accounts) AND NOT cleared',
                    'grain' => ['de' => 'Offener Buchungsposten', 'en' => 'Open booking item'],
                    'dimensions' => ['client'],
                    'fieldsUsed' => ['BookingRecord.amount', 'BookingRecord.account_number', 'Debtor.debtor_number'],
                    'sourceHints' => [
                        'de' => 'OPOS-Kennzeichen (ausgeglichen/offen) aus DATEV-Export nutzen.',
                        'en' => 'Use the OPOS flag (cleared/open) from the DATEV export.',
                    ],
                    'adapt' => [
                        'de' => 'Fälligkeits-Buckets (0-30/31-60/61-90+) für Mahnwesen ergänzen.',
                        'en' => 'Add due-date buckets (0-30/31-60/61-90+) for dunning.',
                    ],
                ],
                [
                    'id' => 'expense-by-cost-center',
                    'example' => false,
                    'label' => ['de' => 'Aufwand je Kostenstelle', 'en' => 'Expense by cost center'],
                    'question' => [
                        'de' => 'Wie viel Aufwand entfällt je Kostenstelle in der Periode?',
                        'en' => 'How much expense falls on each cost center in the period?',
                    ],
                    'formula' => "SUM(amount) FROM booking_record JOIN account ON account_number WHERE account.account_type = 'Aufwandskonto' AND booking_date IN period GROUP BY cost_center",
                    'grain' => ['de' => 'Buchungszeile je Kostenstelle', 'en' => 'Booking line per cost center'],
                    'dimensions' => ['cost_center', 'client'],
                    'fieldsUsed' => ['BookingRecord.amount', 'BookingRecord.cost_center', 'BookingRecord.booking_date', 'Account.account_type'],
                    'sourceHints' => [
                        'de' => 'Nur befüllt wenn Kostenstellenrechnung im Mandanten aktiv ist.',
                        'en' => 'Only populated when cost center accounting is active for the client.',
                    ],
                    'adapt' => [
                        'de' => 'Umlagen (interne Verrechnung) separat ausweisen.',
                        'en' => 'Report internal allocations separately.',
                    ],
                ],
                [
                    'id' => 'vat-liability',
                    'example' => false,
                    'label' => ['de' => 'Umsatzsteuer-Zahllast', 'en' => 'VAT liability'],
                    'question' => [
                        'de' => 'Wie hoch ist die Umsatzsteuer-Zahllast der Periode?',
                        'en' => 'What is the VAT liability for the period?',
                    ],
                    'formula' => 'SUM(amount * tax_key.rate) FROM booking_record JOIN tax_key ON tax_key WHERE booking_date IN period',
                    'grain' => ['de' => 'Buchungszeile je Steuerschlüssel', 'en' => 'Booking line per tax key'],
                    'dimensions' => ['tax_rate', 'client'],
                    'fieldsUsed' => ['BookingRecord.amount', 'BookingRecord.tax_key', 'TaxKey.rate', 'BookingRecord.booking_date'],
                    'sourceHints' => [
                        'de' => 'Vorsteuer vs. Umsatzsteuer BU-Schlüssel sauber trennen.',
                        'en' => 'Cleanly separate input-VAT vs output-VAT posting keys.',
                    ],
                    'adapt' => [
                        'de' => 'Reverse-Charge und innergemeinschaftliche Lieferungen gesondert behandeln.',
                        'en' => 'Handle reverse-charge and intra-community supplies separately.',
                    ],
                ],
                [
                    'id' => 'ap-days-payable',
                    'example' => false,
                    'label' => ['de' => 'Days Payable Outstanding', 'en' => 'Days payable outstanding'],
                    'question' => [
                        'de' => 'Wie viele Tage dauert es im Schnitt, Kreditoren zu bezahlen?',
                        'en' => 'On average, how many days does it take to pay creditors?',
                    ],
                    'formula' => 'AVG(payment_date - document.document_date) FROM booking_record JOIN document WHERE account_number IN (creditor accounts)',
                    'grain' => ['de' => 'Bezahlter Beleg', 'en' => 'Paid document'],
                    'dimensions' => ['client'],
                    'fieldsUsed' => ['Document.document_date', 'BookingRecord.booking_date', 'Creditor.creditor_number'],
                    'sourceHints' => [
                        'de' => 'Zahlungsdatum aus Ausgleichsbuchung, nicht Rechnungsdatum.',
                        'en' => 'Payment date from the clearing posting, not the invoice date.',
                    ],
                    'adapt' => [
                        'de' => 'Skonto-Zahlungen separat tracken.',
                        'en' => 'Track early-payment discounts separately.',
                    ],
                ],
            ],
            'tools' => $erpFinanceHcmTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'sage',
            'domain' => 'erp',
            'order' => 350,
            'label' => ['de' => 'Sage', 'en' => 'Sage'],
            'shortPurpose' => [
                'de' => 'Accounting & ERP: General Ledger, Journal-Vouchers, Customer/Supplier-Stammdaten — Invoice-Facts; Bankdaten und Kontakt-PII schützen.',
                'en' => 'Accounting & ERP: general ledger, journal vouchers, customer/supplier master data — invoice facts; protect bank data and contact PII.',
            ],
            'entities' => [
                [
                    'id' => 'gl_account',
                    'label' => ['de' => 'General Ledger Account', 'en' => 'General ledger account'],
                    'description' => [
                        'de' => 'GL-Konto (Chart of Accounts) — GL-Dimension für Journal Entries.',
                        'en' => 'GL account (chart of accounts) — GL dimension for journal entries.',
                    ],
                    'grain' => ['de' => 'Ein GL-Konto (account_code)', 'en' => 'One GL account (account_code)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'journal_entry',
                    'label' => ['de' => 'Journal Entry', 'en' => 'Journal entry'],
                    'description' => [
                        'de' => 'Buchungssatz (Voucher) — Debit/Credit-Zeile; Kern-Fact.',
                        'en' => 'Booking record (voucher) — debit/credit line; core fact.',
                    ],
                    'grain' => ['de' => 'Eine Journal-Zeile', 'en' => 'One journal line'],
                    'role' => ['de' => 'Fact-Anker', 'en' => 'Fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'customer',
                    'label' => ['de' => 'Customer', 'en' => 'Customer'],
                    'description' => [
                        'de' => 'AR-Stammdaten — Billing-Kontakt ist PII.',
                        'en' => 'AR master data — billing contact is PII.',
                    ],
                    'grain' => ['de' => 'Ein Customer (customer_id)', 'en' => 'One customer (customer_id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'supplier',
                    'label' => ['de' => 'Supplier', 'en' => 'Supplier'],
                    'description' => [
                        'de' => 'AP-Stammdaten — Kontakt und Bankdaten sensibel.',
                        'en' => 'AP master data — contact and bank data are sensitive.',
                    ],
                    'grain' => ['de' => 'Ein Supplier (supplier_id)', 'en' => 'One supplier (supplier_id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'sales_invoice',
                    'label' => ['de' => 'Sales Invoice', 'en' => 'Sales invoice'],
                    'description' => [
                        'de' => 'Verkaufsrechnung — AR-Fact für Umsatz/DSO.',
                        'en' => 'Sales invoice — AR fact for revenue/DSO.',
                    ],
                    'grain' => ['de' => 'Eine Sales Invoice', 'en' => 'One sales invoice'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'purchase_invoice',
                    'label' => ['de' => 'Purchase Invoice', 'en' => 'Purchase invoice'],
                    'description' => [
                        'de' => 'Einkaufsrechnung — AP-Fact für Verbindlichkeiten.',
                        'en' => 'Purchase invoice — AP fact for payables.',
                    ],
                    'grain' => ['de' => 'Eine Purchase Invoice', 'en' => 'One purchase invoice'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'tax_code',
                    'label' => ['de' => 'Tax Code', 'en' => 'Tax code'],
                    'description' => [
                        'de' => 'Steuercode/-satz — Journal- und Invoice-Dimension.',
                        'en' => 'Tax code/rate — journal and invoice dimension.',
                    ],
                    'grain' => ['de' => 'Ein Tax Code', 'en' => 'One tax code'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'GlAccount', 'name' => 'account_code', 'role' => 'key', 'why' => ['de' => 'Konto-Join', 'en' => 'Account join']],
                ['entity' => 'GlAccount', 'name' => 'account_name', 'role' => 'dimension', 'why' => ['de' => 'Kontobezeichnung', 'en' => 'Account name']],
                ['entity' => 'GlAccount', 'name' => 'account_type', 'role' => 'dimension', 'why' => ['de' => 'Asset/Liability/Revenue/Expense', 'en' => 'Asset/liability/revenue/expense']],
                ['entity' => 'JournalEntry', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Journal-Join', 'en' => 'Journal join']],
                ['entity' => 'JournalEntry', 'name' => 'entry_date', 'role' => 'measure', 'why' => ['de' => 'Perioden-Grain', 'en' => 'Period grain']],
                ['entity' => 'JournalEntry', 'name' => 'account_code', 'role' => 'dimension', 'why' => ['de' => 'GL-Konto-Join', 'en' => 'GL account join']],
                ['entity' => 'JournalEntry', 'name' => 'debit_amount', 'role' => 'measure', 'why' => ['de' => 'Soll-Betrag', 'en' => 'Debit amount']],
                ['entity' => 'JournalEntry', 'name' => 'credit_amount', 'role' => 'measure', 'why' => ['de' => 'Haben-Betrag', 'en' => 'Credit amount']],
                ['entity' => 'JournalEntry', 'name' => 'cost_center', 'role' => 'dimension', 'why' => ['de' => 'Kostenstelle', 'en' => 'Cost center']],
                ['entity' => 'JournalEntry', 'name' => 'tax_code', 'role' => 'dimension', 'why' => ['de' => 'Tax-Code-Join', 'en' => 'Tax code join']],
                ['entity' => 'Customer', 'name' => 'customer_id', 'role' => 'key', 'why' => ['de' => 'Customer-Join', 'en' => 'Customer join']],
                ['entity' => 'Customer', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Firmenname', 'en' => 'Company name']],
                ['entity' => 'Customer', 'name' => 'contact_email', 'role' => 'pii', 'why' => ['de' => 'Billing-Kontakt / PII', 'en' => 'Billing contact / PII']],
                ['entity' => 'Customer', 'name' => 'billing_address', 'role' => 'pii', 'why' => ['de' => 'Adresse / PII', 'en' => 'Address / PII']],
                ['entity' => 'Customer', 'name' => 'credit_limit', 'role' => 'measure', 'why' => ['de' => 'Kreditlimit', 'en' => 'Credit limit']],
                ['entity' => 'Supplier', 'name' => 'supplier_id', 'role' => 'key', 'why' => ['de' => 'Supplier-Join', 'en' => 'Supplier join']],
                ['entity' => 'Supplier', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Firmenname', 'en' => 'Company name']],
                ['entity' => 'Supplier', 'name' => 'contact_email', 'role' => 'pii', 'why' => ['de' => 'Kontakt / PII', 'en' => 'Contact / PII']],
                ['entity' => 'Supplier', 'name' => 'bank_details', 'role' => 'pii', 'why' => ['de' => 'Bankverbindung — sensibel', 'en' => 'Bank details — sensitive']],
                ['entity' => 'SalesInvoice', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Invoice-Join', 'en' => 'Invoice join']],
                ['entity' => 'SalesInvoice', 'name' => 'customer_id', 'role' => 'dimension', 'why' => ['de' => 'Customer-Join', 'en' => 'Customer join']],
                ['entity' => 'SalesInvoice', 'name' => 'invoice_date', 'role' => 'measure', 'why' => ['de' => 'Rechnungsdatum', 'en' => 'Invoice date']],
                ['entity' => 'SalesInvoice', 'name' => 'due_date', 'role' => 'measure', 'why' => ['de' => 'Fälligkeit / DSO', 'en' => 'Due date / DSO']],
                ['entity' => 'SalesInvoice', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Rechnungsbetrag', 'en' => 'Invoice amount']],
                ['entity' => 'SalesInvoice', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'open / paid / overdue', 'en' => 'open / paid / overdue']],
                ['entity' => 'PurchaseInvoice', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Invoice-Join', 'en' => 'Invoice join']],
                ['entity' => 'PurchaseInvoice', 'name' => 'supplier_id', 'role' => 'dimension', 'why' => ['de' => 'Supplier-Join', 'en' => 'Supplier join']],
                ['entity' => 'PurchaseInvoice', 'name' => 'invoice_date', 'role' => 'measure', 'why' => ['de' => 'Rechnungsdatum', 'en' => 'Invoice date']],
                ['entity' => 'PurchaseInvoice', 'name' => 'due_date', 'role' => 'measure', 'why' => ['de' => 'Fälligkeit', 'en' => 'Due date']],
                ['entity' => 'PurchaseInvoice', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Rechnungsbetrag', 'en' => 'Invoice amount']],
                ['entity' => 'PurchaseInvoice', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'open / paid / overdue', 'en' => 'open / paid / overdue']],
                ['entity' => 'TaxCode', 'name' => 'code', 'role' => 'key', 'why' => ['de' => 'Tax-Code-Join', 'en' => 'Tax code join']],
                ['entity' => 'TaxCode', 'name' => 'rate', 'role' => 'measure', 'why' => ['de' => 'Steuersatz', 'en' => 'Tax rate']],
            ],
            'skipTables' => [
                [
                    'name' => 'Invoice-Anhänge / Scan-Binaries',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'PDF/Bild-Anhänge — kein Analytics-Nutzen; Invoice-Meta reicht.',
                        'en' => 'PDF/image attachments — no analytics value; invoice meta suffices.',
                    ],
                ],
                [
                    'name' => 'Volle Bank-Reconciliation-Feeds',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Hohes Volumen und Bankdaten — nur abgeglichene Journal-Zeilen laden.',
                        'en' => 'High volume and bank data — load reconciled journal lines only.',
                    ],
                ],
                [
                    'name' => 'Payroll-Modul (Sage HR/Payroll) Klartext',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Gehalt/Compensation nie im ERP-Finance-Warehouse.',
                        'en' => 'Never land salary/compensation in the ERP finance warehouse.',
                    ],
                ],
                [
                    'name' => 'Audit-Trail / Change-Log-Dumps (bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Vollständige Change-Logs — Volumen ohne KPI-Nutzen.',
                        'en' => 'Full change logs — volume without KPI value.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Invoice-Scan-Anhänge', 'reason' => ['de' => 'Binaries — Meta reicht', 'en' => 'Binaries — meta suffices']],
                ['name' => 'Volle Bank-Reconciliation-Feeds', 'reason' => ['de' => 'Volumen + Bankdaten', 'en' => 'Volume + bank data']],
                ['name' => 'Payroll-Modul Klartext', 'reason' => ['de' => 'Separates PII-Modul', 'en' => 'Separate PII module']],
                ['name' => 'Audit-Trail-Dumps (bulk)', 'reason' => ['de' => 'Volumen ohne KPI-Nutzen', 'en' => 'Volume without KPI value']],
            ],
            'dimensions' => [
                [
                    'id' => 'account_type',
                    'label' => ['de' => 'Account Type', 'en' => 'Account type'],
                    'grain' => ['de' => 'gl_account.account_type', 'en' => 'gl_account.account_type'],
                    'notes' => [
                        'de' => 'Asset/Liability/Revenue/Expense für GL-Rollups.',
                        'en' => 'Asset/liability/revenue/expense for GL rollups.',
                    ],
                ],
                [
                    'id' => 'cost_center',
                    'label' => ['de' => 'Cost Center', 'en' => 'Cost center'],
                    'grain' => ['de' => 'journal_entry.cost_center', 'en' => 'journal_entry.cost_center'],
                    'notes' => [
                        'de' => 'Nur befüllt wenn Cost-Center-Accounting aktiv ist.',
                        'en' => 'Only populated when cost center accounting is active.',
                    ],
                ],
                [
                    'id' => 'tax_code',
                    'label' => ['de' => 'Tax Code', 'en' => 'Tax code'],
                    'grain' => ['de' => 'tax_code.code', 'en' => 'tax_code.code'],
                    'notes' => [
                        'de' => 'Länder-/Zeitraum-abhängige Codes — SCD2 empfohlen.',
                        'en' => 'Country/period-dependent codes — SCD2 recommended.',
                    ],
                ],
                [
                    'id' => 'invoice_status',
                    'label' => ['de' => 'Invoice Status', 'en' => 'Invoice status'],
                    'grain' => ['de' => 'sales_invoice.status / purchase_invoice.status', 'en' => 'sales_invoice.status / purchase_invoice.status'],
                    'notes' => [
                        'de' => 'open/paid/overdue nicht mit journal_entry-Status vermischen.',
                        'en' => 'Do not mix open/paid/overdue with journal_entry status.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Customer',
                    'fields' => ['contact_email', 'billing_address'],
                    'treatment' => [
                        'de' => 'Billing-Kontakt — PII taggen; customer_id als Join bevorzugen.',
                        'en' => 'Billing contact — tag PII; prefer customer_id as join.',
                    ],
                ],
                [
                    'entity' => 'Supplier',
                    'fields' => ['contact_email', 'bank_details'],
                    'treatment' => [
                        'de' => 'Kontakt und Bankdaten — sensibel; hashen oder Zugriff einschränken.',
                        'en' => 'Contact and bank data — sensitive; hash or restrict access.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'customer_id, supplier_id, tax registration number (hashed), bank account (hashed).',
                        'en' => 'customer_id, supplier_id, tax registration number (hashed), bank account (hashed).',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Customer, Supplier, Journal Entry, Sales/Purchase Invoice + Warehouse-Kopien.',
                        'en' => 'Customer, supplier, journal entry, sales/purchase invoice + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'revenue-invoiced',
                    'example' => true,
                    'label' => ['de' => 'Revenue Invoiced', 'en' => 'Revenue invoiced'],
                    'question' => [
                        'de' => 'Wie viel Umsatz wurde in der Periode fakturiert?',
                        'en' => 'How much revenue was invoiced in the period?',
                    ],
                    'formula' => 'SUM(amount) FROM sales_invoice WHERE invoice_date IN period',
                    'grain' => ['de' => 'Sales Invoice', 'en' => 'Sales invoice'],
                    'dimensions' => ['invoice_status'],
                    'fieldsUsed' => ['SalesInvoice.amount', 'SalesInvoice.invoice_date', 'SalesInvoice.customer_id'],
                    'sourceHints' => [
                        'de' => 'Credit Notes als negative Beträge oder separat behandeln.',
                        'en' => 'Handle credit notes as negative amounts or separately.',
                    ],
                    'adapt' => [
                        'de' => 'Multi-Currency-Umrechnung zum Buchungskurs klären.',
                        'en' => 'Clarify multi-currency conversion at the posting rate.',
                    ],
                ],
                [
                    'id' => 'ar-outstanding',
                    'example' => true,
                    'label' => ['de' => 'AR Outstanding', 'en' => 'AR outstanding'],
                    'question' => [
                        'de' => 'Wie hoch sind die offenen Forderungen?',
                        'en' => 'How high are outstanding receivables?',
                    ],
                    'formula' => "SUM(amount) FROM sales_invoice WHERE status != 'paid'",
                    'grain' => ['de' => 'Offene Sales Invoice', 'en' => 'Open sales invoice'],
                    'dimensions' => ['invoice_status'],
                    'fieldsUsed' => ['SalesInvoice.amount', 'SalesInvoice.status', 'SalesInvoice.due_date'],
                    'sourceHints' => [
                        'de' => 'Fälligkeits-Buckets über due_date bilden.',
                        'en' => 'Build due-date buckets via due_date.',
                    ],
                    'adapt' => [
                        'de' => 'Wertberichtigte/uneinbringliche Forderungen separat ausweisen.',
                        'en' => 'Report written-off/uncollectible receivables separately.',
                    ],
                ],
                [
                    'id' => 'ap-outstanding',
                    'example' => false,
                    'label' => ['de' => 'AP Outstanding', 'en' => 'AP outstanding'],
                    'question' => [
                        'de' => 'Wie hoch sind die offenen Verbindlichkeiten?',
                        'en' => 'How high are outstanding payables?',
                    ],
                    'formula' => "SUM(amount) FROM purchase_invoice WHERE status != 'paid'",
                    'grain' => ['de' => 'Offene Purchase Invoice', 'en' => 'Open purchase invoice'],
                    'dimensions' => ['invoice_status'],
                    'fieldsUsed' => ['PurchaseInvoice.amount', 'PurchaseInvoice.status', 'PurchaseInvoice.due_date'],
                    'sourceHints' => [
                        'de' => 'Skonto-Fenster (due_date - discount period) für Cash-Planung.',
                        'en' => 'Early-payment discount window (due_date - discount period) for cash planning.',
                    ],
                    'adapt' => [
                        'de' => 'Intercompany-Verbindlichkeiten separat kennzeichnen.',
                        'en' => 'Flag intercompany payables separately.',
                    ],
                ],
                [
                    'id' => 'opex-by-cost-center',
                    'example' => false,
                    'label' => ['de' => 'Opex je Cost Center', 'en' => 'Opex by cost center'],
                    'question' => [
                        'de' => 'Wie verteilt sich der operative Aufwand je Cost Center?',
                        'en' => 'How is operating expense distributed by cost center?',
                    ],
                    'formula' => "SUM(debit_amount - credit_amount) FROM journal_entry JOIN gl_account ON account_code WHERE gl_account.account_type = 'expense' AND entry_date IN period GROUP BY cost_center",
                    'grain' => ['de' => 'Journal-Zeile je Cost Center', 'en' => 'Journal line per cost center'],
                    'dimensions' => ['cost_center', 'account_type'],
                    'fieldsUsed' => ['JournalEntry.debit_amount', 'JournalEntry.credit_amount', 'JournalEntry.cost_center', 'GlAccount.account_type'],
                    'sourceHints' => [
                        'de' => 'Nur befüllt wenn Cost-Center-Dimension aktiviert ist.',
                        'en' => 'Only populated when the cost center dimension is enabled.',
                    ],
                    'adapt' => [
                        'de' => 'Allokations-/Umlage-Journale getrennt ausweisen.',
                        'en' => 'Report allocation journals separately.',
                    ],
                ],
                [
                    'id' => 'on-time-payment-rate',
                    'example' => false,
                    'label' => ['de' => 'On-time Payment Rate', 'en' => 'On-time payment rate'],
                    'question' => [
                        'de' => 'Welcher Anteil der Purchase Invoices wurde pünktlich bezahlt?',
                        'en' => 'What share of purchase invoices were paid on time?',
                    ],
                    'formula' => "COUNT(paid_date <= due_date) / COUNT(status = 'paid')",
                    'grain' => ['de' => 'Bezahlte Purchase Invoice', 'en' => 'Paid purchase invoice'],
                    'dimensions' => ['invoice_status'],
                    'fieldsUsed' => ['PurchaseInvoice.due_date', 'PurchaseInvoice.status', 'PurchaseInvoice.supplier_id'],
                    'sourceHints' => [
                        'de' => 'Zahlungsdatum aus Journal-Ausgleichsbuchung ableiten.',
                        'en' => 'Derive payment date from the journal clearing posting.',
                    ],
                    'adapt' => [
                        'de' => 'Frühzahlungen mit Skonto separat als Best-Case markieren.',
                        'en' => 'Mark early payments with discount separately as best-case.',
                    ],
                ],
            ],
            'tools' => $erpFinanceHcmTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'oracle-fusion',
            'domain' => 'erp',
            'order' => 360,
            'label' => ['de' => 'Oracle Fusion Cloud', 'en' => 'Oracle Fusion Cloud'],
            'shortPurpose' => [
                'de' => 'Oracle Fusion Cloud ERP/HCM/CX: GL-Journals, AP/AR-Vouchers, Supplier/Customer und Worker-Stammdaten — Compensation nie im Klartext.',
                'en' => 'Oracle Fusion Cloud ERP/HCM/CX: GL journals, AP/AR vouchers, supplier/customer and worker master data — never compensation cleartext.',
            ],
            'entities' => [
                [
                    'id' => 'gl_journal',
                    'label' => ['de' => 'GL Journal', 'en' => 'GL journal'],
                    'description' => [
                        'de' => 'General-Ledger-Journal-Zeile — Voucher-Fact über Ledger/Business Unit.',
                        'en' => 'General ledger journal line — voucher fact across ledger/business unit.',
                    ],
                    'grain' => ['de' => 'Eine Journal-Zeile', 'en' => 'One journal line'],
                    'role' => ['de' => 'Fact-Anker', 'en' => 'Fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'supplier',
                    'label' => ['de' => 'Supplier', 'en' => 'Supplier'],
                    'description' => [
                        'de' => 'AP-Lieferantenstammdaten — Steuer-Registrierung sensibel.',
                        'en' => 'AP supplier master data — tax registration is sensitive.',
                    ],
                    'grain' => ['de' => 'Ein Supplier (supplier_id)', 'en' => 'One supplier (supplier_id)'],
                    'role' => ['de' => 'Dimension (PII-adjacent)', 'en' => 'Dimension (PII-adjacent)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'customer',
                    'label' => ['de' => 'Customer', 'en' => 'Customer'],
                    'description' => [
                        'de' => 'AR-Kundenstammdaten — Bill-to-Kontakt ist PII.',
                        'en' => 'AR customer master data — bill-to contact is PII.',
                    ],
                    'grain' => ['de' => 'Ein Customer (customer_id)', 'en' => 'One customer (customer_id)'],
                    'role' => ['de' => 'Dimension (PII-adjacent)', 'en' => 'Dimension (PII-adjacent)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'ap_invoice',
                    'label' => ['de' => 'AP Invoice', 'en' => 'AP invoice'],
                    'description' => [
                        'de' => 'Payables-Rechnung — Verbindlichkeiten-Fact.',
                        'en' => 'Payables invoice — payables fact.',
                    ],
                    'grain' => ['de' => 'Eine AP Invoice', 'en' => 'One AP invoice'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'ar_invoice',
                    'label' => ['de' => 'AR Invoice', 'en' => 'AR invoice'],
                    'description' => [
                        'de' => 'Receivables-Rechnung — Umsatz-/DSO-Fact.',
                        'en' => 'Receivables invoice — revenue/DSO fact.',
                    ],
                    'grain' => ['de' => 'Eine AR Invoice', 'en' => 'One AR invoice'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'worker',
                    'label' => ['de' => 'Worker', 'en' => 'Worker'],
                    'description' => [
                        'de' => 'HCM Person/Worker (Employee Master) — strenges Workforce-PII; national_identifier hashen.',
                        'en' => 'HCM person/worker (employee master) — strict workforce PII; hash national_identifier.',
                    ],
                    'grain' => ['de' => 'Ein Worker (person_id)', 'en' => 'One worker (person_id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'assignment',
                    'label' => ['de' => 'Assignment', 'en' => 'Assignment'],
                    'description' => [
                        'de' => 'Job Assignment — Position/Grade; nur salary_basis-Referenz, kein Gehaltsbetrag.',
                        'en' => 'Job assignment — position/grade; salary_basis reference only, no salary amount.',
                    ],
                    'grain' => ['de' => 'Ein Assignment (assignment_id)', 'en' => 'One assignment (assignment_id)'],
                    'role' => ['de' => 'HCM-Fact', 'en' => 'HCM fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'GlJournal', 'name' => 'journal_id', 'role' => 'key', 'why' => ['de' => 'Journal-Join', 'en' => 'Journal join']],
                ['entity' => 'GlJournal', 'name' => 'ledger', 'role' => 'dimension', 'why' => ['de' => 'Ledger-Dimension', 'en' => 'Ledger dimension']],
                ['entity' => 'GlJournal', 'name' => 'accounting_period', 'role' => 'measure', 'why' => ['de' => 'Perioden-Grain', 'en' => 'Period grain']],
                ['entity' => 'GlJournal', 'name' => 'account_combination', 'role' => 'dimension', 'why' => ['de' => 'Kontenkombination', 'en' => 'Account combination']],
                ['entity' => 'GlJournal', 'name' => 'entered_dr', 'role' => 'measure', 'why' => ['de' => 'Soll-Betrag', 'en' => 'Debit amount']],
                ['entity' => 'GlJournal', 'name' => 'entered_cr', 'role' => 'measure', 'why' => ['de' => 'Haben-Betrag', 'en' => 'Credit amount']],
                ['entity' => 'GlJournal', 'name' => 'business_unit', 'role' => 'dimension', 'why' => ['de' => 'Business-Unit-Join', 'en' => 'Business unit join']],
                ['entity' => 'Supplier', 'name' => 'supplier_id', 'role' => 'key', 'why' => ['de' => 'Supplier-Join', 'en' => 'Supplier join']],
                ['entity' => 'Supplier', 'name' => 'supplier_name', 'role' => 'dimension', 'why' => ['de' => 'Firmenname', 'en' => 'Company name']],
                ['entity' => 'Supplier', 'name' => 'tax_registration_number', 'role' => 'pii', 'why' => ['de' => 'Steuer-ID — hashen', 'en' => 'Tax ID — hash']],
                ['entity' => 'Customer', 'name' => 'customer_id', 'role' => 'key', 'why' => ['de' => 'Customer-Join', 'en' => 'Customer join']],
                ['entity' => 'Customer', 'name' => 'customer_name', 'role' => 'dimension', 'why' => ['de' => 'Firmenname', 'en' => 'Company name']],
                ['entity' => 'Customer', 'name' => 'bill_to_contact_email', 'role' => 'pii', 'why' => ['de' => 'Bill-to-Kontakt / PII', 'en' => 'Bill-to contact / PII']],
                ['entity' => 'ApInvoice', 'name' => 'invoice_id', 'role' => 'key', 'why' => ['de' => 'Invoice-Join', 'en' => 'Invoice join']],
                ['entity' => 'ApInvoice', 'name' => 'supplier_id', 'role' => 'dimension', 'why' => ['de' => 'Supplier-Join', 'en' => 'Supplier join']],
                ['entity' => 'ApInvoice', 'name' => 'invoice_date', 'role' => 'measure', 'why' => ['de' => 'Rechnungsdatum', 'en' => 'Invoice date']],
                ['entity' => 'ApInvoice', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Rechnungsbetrag', 'en' => 'Invoice amount']],
                ['entity' => 'ApInvoice', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'open / paid', 'en' => 'open / paid']],
                ['entity' => 'ArInvoice', 'name' => 'invoice_id', 'role' => 'key', 'why' => ['de' => 'Invoice-Join', 'en' => 'Invoice join']],
                ['entity' => 'ArInvoice', 'name' => 'customer_id', 'role' => 'dimension', 'why' => ['de' => 'Customer-Join', 'en' => 'Customer join']],
                ['entity' => 'ArInvoice', 'name' => 'invoice_date', 'role' => 'measure', 'why' => ['de' => 'Rechnungsdatum', 'en' => 'Invoice date']],
                ['entity' => 'ArInvoice', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Rechnungsbetrag', 'en' => 'Invoice amount']],
                ['entity' => 'ArInvoice', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'open / paid', 'en' => 'open / paid']],
                ['entity' => 'Worker', 'name' => 'person_id', 'role' => 'key', 'why' => ['de' => 'Worker-Join', 'en' => 'Worker join']],
                ['entity' => 'Worker', 'name' => 'person_number', 'role' => 'dimension', 'why' => ['de' => 'Stabiler Business-Key', 'en' => 'Stable business key']],
                ['entity' => 'Worker', 'name' => 'display_name', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'Worker', 'name' => 'work_email', 'role' => 'pii', 'why' => ['de' => 'Workforce-PII', 'en' => 'Workforce PII']],
                ['entity' => 'Worker', 'name' => 'national_identifier', 'role' => 'pii', 'why' => ['de' => 'Steuer-/Sozial-ID — hashen', 'en' => 'Tax/social ID — hash']],
                ['entity' => 'Worker', 'name' => 'hire_date', 'role' => 'measure', 'why' => ['de' => 'Headcount / Tenure', 'en' => 'Headcount / tenure']],
                ['entity' => 'Worker', 'name' => 'business_unit', 'role' => 'dimension', 'why' => ['de' => 'Business-Unit-Join', 'en' => 'Business unit join']],
                ['entity' => 'Worker', 'name' => 'department', 'role' => 'dimension', 'why' => ['de' => 'Abteilung', 'en' => 'Department']],
                ['entity' => 'Assignment', 'name' => 'assignment_id', 'role' => 'key', 'why' => ['de' => 'Assignment-Join', 'en' => 'Assignment join']],
                ['entity' => 'Assignment', 'name' => 'person_id', 'role' => 'dimension', 'why' => ['de' => 'Worker-Join', 'en' => 'Worker join']],
                ['entity' => 'Assignment', 'name' => 'position', 'role' => 'dimension', 'why' => ['de' => 'Position', 'en' => 'Position']],
                ['entity' => 'Assignment', 'name' => 'grade', 'role' => 'dimension', 'why' => ['de' => 'Grade', 'en' => 'Grade']],
                ['entity' => 'Assignment', 'name' => 'salary_basis', 'role' => 'dimension', 'why' => ['de' => 'Referenz — nie Gehaltsbetrag', 'en' => 'Reference only — never the salary amount']],
                ['entity' => 'Assignment', 'name' => 'assignment_status', 'role' => 'dimension', 'why' => ['de' => 'active / inactive / terminated', 'en' => 'active / inactive / terminated']],
            ],
            'skipTables' => [
                [
                    'name' => 'Compensation / Payroll Element Entries (Klartext)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Gehalts-/Vergütungsbeträge nie im Standard-Warehouse laden.',
                        'en' => 'Never load compensation/salary amounts into the standard warehouse.',
                    ],
                ],
                [
                    'name' => 'National Identifiers (bulk export)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Steuer-/Sozialversicherungs-IDs — hashen oder gar nicht laden.',
                        'en' => 'Tax/social security IDs — hash or do not load at all.',
                    ],
                ],
                [
                    'name' => 'Benefits-Enrollment / Health-Daten',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Gesundheitsbezogene Daten — besondere Kategorie personenbezogener Daten.',
                        'en' => 'Health-related data — special category of personal data.',
                    ],
                ],
                [
                    'name' => 'Bankverbindungen (AP/AR + Payroll, bulk)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Bankdaten — sensibel; nur wo geschäftskritisch und beschränkt.',
                        'en' => 'Bank data — sensitive; only where business-critical and restricted.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Compensation/Payroll Klartext', 'reason' => ['de' => 'Höchste PII-Sensitivität', 'en' => 'Highest PII sensitivity']],
                ['name' => 'National Identifiers (bulk)', 'reason' => ['de' => 'Hashen oder nicht laden', 'en' => 'Hash or do not load']],
                ['name' => 'Benefits/Health-Daten', 'reason' => ['de' => 'Besondere Kategorie', 'en' => 'Special category']],
                ['name' => 'Bankverbindungen (bulk)', 'reason' => ['de' => 'Sensibel — restriktiv', 'en' => 'Sensitive — restrictive']],
            ],
            'dimensions' => [
                [
                    'id' => 'business_unit',
                    'label' => ['de' => 'Business Unit', 'en' => 'Business unit'],
                    'grain' => ['de' => 'gl_journal.business_unit / worker.business_unit', 'en' => 'gl_journal.business_unit / worker.business_unit'],
                    'notes' => [
                        'de' => 'Gemeinsame Dimension über ERP- und HCM-Fakten.',
                        'en' => 'Shared dimension across ERP and HCM facts.',
                    ],
                ],
                [
                    'id' => 'department',
                    'label' => ['de' => 'Department', 'en' => 'Department'],
                    'grain' => ['de' => 'worker.department', 'en' => 'worker.department'],
                    'notes' => [
                        'de' => 'HCM-Org-Dimension für Headcount-Rollups.',
                        'en' => 'HCM org dimension for headcount rollups.',
                    ],
                ],
                [
                    'id' => 'invoice_status',
                    'label' => ['de' => 'Invoice Status', 'en' => 'Invoice status'],
                    'grain' => ['de' => 'ap_invoice.status / ar_invoice.status', 'en' => 'ap_invoice.status / ar_invoice.status'],
                    'notes' => [
                        'de' => 'AP- und AR-Status getrennt auswerten.',
                        'en' => 'Evaluate AP and AR status separately.',
                    ],
                ],
                [
                    'id' => 'grade',
                    'label' => ['de' => 'Grade', 'en' => 'Grade'],
                    'grain' => ['de' => 'assignment.grade', 'en' => 'assignment.grade'],
                    'notes' => [
                        'de' => 'Für Headcount-Schnitte — nie mit Gehaltsbetrag verknüpfen ohne Freigabe.',
                        'en' => 'For headcount slices — never join to salary amount without approval.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Worker',
                    'fields' => ['display_name', 'work_email'],
                    'treatment' => [
                        'de' => 'Direkte Identifikatoren — person_id/person_number als Join bevorzugen.',
                        'en' => 'Direct identifiers — prefer person_id/person_number as join.',
                    ],
                ],
                [
                    'entity' => 'Worker',
                    'fields' => ['national_identifier'],
                    'treatment' => [
                        'de' => 'Steuer-/Sozial-ID — hashen; Zugriff strikt auf HR/Payroll beschränken.',
                        'en' => 'Tax/social ID — hash; restrict access strictly to HR/payroll.',
                    ],
                ],
                [
                    'entity' => 'Supplier / Customer',
                    'fields' => ['tax_registration_number', 'bill_to_contact_email'],
                    'treatment' => [
                        'de' => 'Steuer-ID hashen; Kontakt-E-Mail als PII taggen.',
                        'en' => 'Hash tax ID; tag contact email as PII.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'person_number, national_identifier (hashed), supplier_id, customer_id, invoice_id.',
                        'en' => 'person_number, national_identifier (hashed), supplier_id, customer_id, invoice_id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Worker, Assignment, Supplier, Customer, GL Journal, AP/AR Invoice + Warehouse-Kopien.',
                        'en' => 'Worker, assignment, supplier, customer, GL journal, AP/AR invoice + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'gl-revenue-booked',
                    'example' => true,
                    'label' => ['de' => 'GL Revenue Booked', 'en' => 'GL revenue booked'],
                    'question' => [
                        'de' => 'Wie viel Umsatz wurde in der Periode auf Revenue-Konten gebucht?',
                        'en' => 'How much revenue was booked to revenue accounts in the period?',
                    ],
                    'formula' => "SUM(entered_cr - entered_dr) FROM gl_journal WHERE account_combination LIKE 'revenue%' AND accounting_period IN period",
                    'grain' => ['de' => 'Journal-Zeile', 'en' => 'Journal line'],
                    'dimensions' => ['business_unit'],
                    'fieldsUsed' => ['GlJournal.entered_cr', 'GlJournal.entered_dr', 'GlJournal.account_combination', 'GlJournal.accounting_period'],
                    'sourceHints' => [
                        'de' => 'Revenue-Range aus Chart of Accounts je Ledger prüfen.',
                        'en' => 'Verify the revenue range from the chart of accounts per ledger.',
                    ],
                    'adapt' => [
                        'de' => 'Intercompany-Eliminierungen vor Konsolidierung berücksichtigen.',
                        'en' => 'Account for intercompany eliminations before consolidation.',
                    ],
                ],
                [
                    'id' => 'ap-liability-outstanding',
                    'example' => true,
                    'label' => ['de' => 'AP Liability Outstanding', 'en' => 'AP liability outstanding'],
                    'question' => [
                        'de' => 'Wie hoch sind die offenen AP-Verbindlichkeiten?',
                        'en' => 'How high are outstanding AP liabilities?',
                    ],
                    'formula' => "SUM(amount) FROM ap_invoice WHERE status != 'paid'",
                    'grain' => ['de' => 'Offene AP Invoice', 'en' => 'Open AP invoice'],
                    'dimensions' => ['invoice_status'],
                    'fieldsUsed' => ['ApInvoice.amount', 'ApInvoice.status', 'ApInvoice.supplier_id'],
                    'sourceHints' => [
                        'de' => 'Hold-Status (Matching Hold) separat behandeln.',
                        'en' => 'Handle hold status (matching hold) separately.',
                    ],
                    'adapt' => [
                        'de' => 'Multi-Ledger-Konsolidierung je Business Unit klären.',
                        'en' => 'Clarify multi-ledger consolidation per business unit.',
                    ],
                ],
                [
                    'id' => 'ar-outstanding-fusion',
                    'example' => false,
                    'label' => ['de' => 'AR Outstanding', 'en' => 'AR outstanding'],
                    'question' => [
                        'de' => 'Wie hoch sind die offenen AR-Forderungen?',
                        'en' => 'How high are outstanding AR receivables?',
                    ],
                    'formula' => "SUM(amount) FROM ar_invoice WHERE status != 'paid'",
                    'grain' => ['de' => 'Offene AR Invoice', 'en' => 'Open AR invoice'],
                    'dimensions' => ['invoice_status'],
                    'fieldsUsed' => ['ArInvoice.amount', 'ArInvoice.status', 'ArInvoice.customer_id'],
                    'sourceHints' => [
                        'de' => 'Dispute-Status vor DSO-Berechnung herausrechnen.',
                        'en' => 'Exclude disputed status before computing DSO.',
                    ],
                    'adapt' => [
                        'de' => 'Fälligkeits-Buckets für Collections-Reporting ergänzen.',
                        'en' => 'Add due-date buckets for collections reporting.',
                    ],
                ],
                [
                    'id' => 'headcount-active',
                    'example' => false,
                    'label' => ['de' => 'Headcount (Active)', 'en' => 'Headcount (active)'],
                    'question' => [
                        'de' => 'Wie viele aktive Worker gibt es (Snapshot)?',
                        'en' => 'How many active workers are there (snapshot)?',
                    ],
                    'formula' => "COUNT(*) FROM worker JOIN assignment ON person_id WHERE assignment.assignment_status = 'active'",
                    'grain' => ['de' => 'Aktiver Worker', 'en' => 'Active worker'],
                    'dimensions' => ['business_unit', 'department', 'grade'],
                    'fieldsUsed' => ['Worker.person_id', 'Assignment.assignment_status', 'Worker.department', 'Worker.business_unit'],
                    'sourceHints' => [
                        'de' => 'Nur primäre Assignment je Worker zählen (Multi-Assignment vermeiden).',
                        'en' => 'Count only the primary assignment per worker (avoid multi-assignment double count).',
                    ],
                    'adapt' => [
                        'de' => 'Contingent Worker separat von Employee-Headcount ausweisen.',
                        'en' => 'Report contingent workers separately from employee headcount.',
                    ],
                ],
                [
                    'id' => 'new-hires-count',
                    'example' => false,
                    'label' => ['de' => 'New Hires', 'en' => 'New hires'],
                    'question' => [
                        'de' => 'Wie viele Worker wurden in der Periode eingestellt?',
                        'en' => 'How many workers were hired in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM worker WHERE hire_date IN period',
                    'grain' => ['de' => 'Neu eingestellter Worker', 'en' => 'Newly hired worker'],
                    'dimensions' => ['business_unit', 'department'],
                    'fieldsUsed' => ['Worker.hire_date', 'Worker.department', 'Worker.business_unit'],
                    'sourceHints' => [
                        'de' => 'Rehires (erneute Einstellung derselben person_number) separat kennzeichnen.',
                        'en' => 'Flag rehires (same person_number rehired) separately.',
                    ],
                    'adapt' => [
                        'de' => 'Internal Transfers nicht als New Hire zählen.',
                        'en' => 'Do not count internal transfers as new hires.',
                    ],
                ],
            ],
            'tools' => $erpFinanceHcmTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'personio',
            'domain' => 'hcm',
            'order' => 370,
            'label' => ['de' => 'Personio', 'en' => 'Personio'],
            'shortPurpose' => [
                'de' => 'HR/People Ops (EU): Employee Master, Abwesenheiten, Compensation — strenges Workforce-PII; Gehalt nie im Klartext in Marts.',
                'en' => 'HR/people ops (EU): employee master, absences, compensation — strict workforce PII; never salary cleartext in marts.',
            ],
            'entities' => [
                [
                    'id' => 'employee',
                    'label' => ['de' => 'Employee', 'en' => 'Employee'],
                    'description' => [
                        'de' => 'Mitarbeiter-Stammdaten — Name/E-Mail/Steuer-ID sind PII.',
                        'en' => 'Employee master data — name/email/tax ID are PII.',
                    ],
                    'grain' => ['de' => 'Ein Employee (employee_id)', 'en' => 'One employee (employee_id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'position',
                    'label' => ['de' => 'Position', 'en' => 'Position'],
                    'description' => [
                        'de' => 'Position/Department — Org-Dimension für Headcount-Rollups.',
                        'en' => 'Position/department — org dimension for headcount rollups.',
                    ],
                    'grain' => ['de' => 'Eine Position', 'en' => 'One position'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'absence',
                    'label' => ['de' => 'Absence', 'en' => 'Absence'],
                    'description' => [
                        'de' => 'Abwesenheit (Urlaub/Krank/Elternzeit) — Diagnose-Details nie laden.',
                        'en' => 'Absence (vacation/sick/parental) — never load diagnosis details.',
                    ],
                    'grain' => ['de' => 'Eine Abwesenheit', 'en' => 'One absence'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'absence_balance',
                    'label' => ['de' => 'Absence Balance', 'en' => 'Absence balance'],
                    'description' => [
                        'de' => 'Urlaubskonto/-anspruch je Periode — Snapshot-Fact.',
                        'en' => 'Vacation account/entitlement per period — snapshot fact.',
                    ],
                    'grain' => ['de' => 'Ein Balance-Snapshot', 'en' => 'One balance snapshot'],
                    'role' => ['de' => 'Snapshot-Fact', 'en' => 'Snapshot fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'attendance',
                    'label' => ['de' => 'Attendance', 'en' => 'Attendance'],
                    'description' => [
                        'de' => 'Zeiterfassung — Arbeitszeit/Pausen; Mitbestimmungspflichtig.',
                        'en' => 'Time tracking — working hours/breaks; subject to works council rules.',
                    ],
                    'grain' => ['de' => 'Ein Zeiterfassungs-Tag', 'en' => 'One time-tracking day'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'compensation',
                    'label' => ['de' => 'Compensation', 'en' => 'Compensation'],
                    'description' => [
                        'de' => 'Gehalt/Compensation — Klartext-Betrag standardmäßig nicht in Marts laden.',
                        'en' => 'Salary/compensation — do not load cleartext amount into marts by default.',
                    ],
                    'grain' => ['de' => 'Eine Compensation-Zeile', 'en' => 'One compensation line'],
                    'role' => ['de' => 'Fact (streng PII)', 'en' => 'Fact (strict PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'document',
                    'label' => ['de' => 'Document', 'en' => 'Document'],
                    'description' => [
                        'de' => 'Mitarbeiter-Dokument (Vertrag etc.) — nur Meta, kein Content.',
                        'en' => 'Employee document (contract, etc.) — meta only, no content.',
                    ],
                    'grain' => ['de' => 'Ein Dokument (Meta)', 'en' => 'One document (meta)'],
                    'role' => ['de' => 'Meta-Fact', 'en' => 'Meta fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Employee', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Employee-Join', 'en' => 'Employee join']],
                ['entity' => 'Employee', 'name' => 'employee_number', 'role' => 'dimension', 'why' => ['de' => 'Stabiler Business-Key', 'en' => 'Stable business key']],
                ['entity' => 'Employee', 'name' => 'first_name', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'Employee', 'name' => 'last_name', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'Employee', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Workforce-PII', 'en' => 'Workforce PII']],
                ['entity' => 'Employee', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'active / inactive / onboarding', 'en' => 'active / inactive / onboarding']],
                ['entity' => 'Employee', 'name' => 'hire_date', 'role' => 'measure', 'why' => ['de' => 'Headcount / Tenure', 'en' => 'Headcount / tenure']],
                ['entity' => 'Employee', 'name' => 'termination_date', 'role' => 'measure', 'why' => ['de' => 'Turnover', 'en' => 'Turnover']],
                ['entity' => 'Employee', 'name' => 'tax_id', 'role' => 'pii', 'why' => ['de' => 'Steuer-/Sozialversicherungs-ID — hashen', 'en' => 'Tax/social security ID — hash']],
                ['entity' => 'Employee', 'name' => 'iban', 'role' => 'pii', 'why' => ['de' => 'Bankverbindung — sensibel', 'en' => 'Bank account — sensitive']],
                ['entity' => 'Position', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Position-Join', 'en' => 'Position join']],
                ['entity' => 'Position', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Positionsbezeichnung', 'en' => 'Position name']],
                ['entity' => 'Position', 'name' => 'department', 'role' => 'dimension', 'why' => ['de' => 'Abteilung', 'en' => 'Department']],
                ['entity' => 'Absence', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Absence-Join', 'en' => 'Absence join']],
                ['entity' => 'Absence', 'name' => 'employee_id', 'role' => 'dimension', 'why' => ['de' => 'Employee-Join', 'en' => 'Employee join']],
                ['entity' => 'Absence', 'name' => 'absence_type', 'role' => 'dimension', 'why' => ['de' => 'vacation / sick / parental', 'en' => 'vacation / sick / parental']],
                ['entity' => 'Absence', 'name' => 'start_date', 'role' => 'measure', 'why' => ['de' => 'Beginn', 'en' => 'Start']],
                ['entity' => 'Absence', 'name' => 'end_date', 'role' => 'measure', 'why' => ['de' => 'Ende', 'en' => 'End']],
                ['entity' => 'Absence', 'name' => 'days_count', 'role' => 'measure', 'why' => ['de' => 'Abwesenheitstage', 'en' => 'Absence days']],
                ['entity' => 'Absence', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'requested / approved / rejected', 'en' => 'requested / approved / rejected']],
                ['entity' => 'AbsenceBalance', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Balance-Join', 'en' => 'Balance join']],
                ['entity' => 'AbsenceBalance', 'name' => 'employee_id', 'role' => 'dimension', 'why' => ['de' => 'Employee-Join', 'en' => 'Employee join']],
                ['entity' => 'AbsenceBalance', 'name' => 'balance_days', 'role' => 'measure', 'why' => ['de' => 'Verbleibender Anspruch', 'en' => 'Remaining entitlement']],
                ['entity' => 'Attendance', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Attendance-Join', 'en' => 'Attendance join']],
                ['entity' => 'Attendance', 'name' => 'employee_id', 'role' => 'dimension', 'why' => ['de' => 'Employee-Join', 'en' => 'Employee join']],
                ['entity' => 'Attendance', 'name' => 'hours_worked', 'role' => 'measure', 'why' => ['de' => 'Arbeitsstunden', 'en' => 'Hours worked']],
                ['entity' => 'Compensation', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Compensation-Join', 'en' => 'Compensation join']],
                ['entity' => 'Compensation', 'name' => 'employee_id', 'role' => 'dimension', 'why' => ['de' => 'Employee-Join', 'en' => 'Employee join']],
                ['entity' => 'Compensation', 'name' => 'amount', 'role' => 'pii', 'why' => ['de' => 'Gehaltsbetrag — Klartext standardmäßig nicht in Marts', 'en' => 'Salary amount — no cleartext in marts by default']],
                ['entity' => 'Compensation', 'name' => 'compensation_type', 'role' => 'dimension', 'why' => ['de' => 'fixed / variable / bonus', 'en' => 'fixed / variable / bonus']],
                ['entity' => 'Compensation', 'name' => 'effective_date', 'role' => 'measure', 'why' => ['de' => 'Wirksamkeitsdatum', 'en' => 'Effective date']],
                ['entity' => 'Document', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Document-Join', 'en' => 'Document join']],
                ['entity' => 'Document', 'name' => 'employee_id', 'role' => 'dimension', 'why' => ['de' => 'Employee-Join', 'en' => 'Employee join']],
                ['entity' => 'Document', 'name' => 'category', 'role' => 'dimension', 'why' => ['de' => 'Vertrag / Zeugnis / Sonstiges', 'en' => 'Contract / reference / other']],
            ],
            'skipTables' => [
                [
                    'name' => 'Compensation-Klartext in Marts',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Gehaltsbeträge nie standardmäßig in Curated/Marts — nur RAW mit striktem Zugriff.',
                        'en' => 'Never land salary amounts in curated/marts by default — RAW only with strict access.',
                    ],
                ],
                [
                    'name' => 'Mitarbeiter-Dokument-Binaries (Verträge, Ausweis-Scans)',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Dokumentinhalte — kein Analytics-Nutzen, hohes Leak-Risiko.',
                        'en' => 'Document content — no analytics value, high leak risk.',
                    ],
                ],
                [
                    'name' => 'Krankheits-Diagnose-Details',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Gesundheitsdaten — besondere Kategorie; nur absence_type genügt.',
                        'en' => 'Health data — special category; absence_type alone is sufficient.',
                    ],
                ],
                [
                    'name' => 'IBAN / Bankverbindungen (bulk)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Bankdaten — sensibel, nicht analytics-relevant.',
                        'en' => 'Bank data — sensitive, not analytics-relevant.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Compensation-Klartext in Marts', 'reason' => ['de' => 'Höchste PII-Sensitivität', 'en' => 'Highest PII sensitivity']],
                ['name' => 'Dokument-Binaries', 'reason' => ['de' => 'Kein Analytics-Nutzen', 'en' => 'No analytics value']],
                ['name' => 'Krankheits-Diagnose-Details', 'reason' => ['de' => 'Besondere Kategorie', 'en' => 'Special category']],
                ['name' => 'IBAN / Bankverbindungen (bulk)', 'reason' => ['de' => 'Sensibel', 'en' => 'Sensitive']],
            ],
            'dimensions' => [
                [
                    'id' => 'department',
                    'label' => ['de' => 'Department', 'en' => 'Department'],
                    'grain' => ['de' => 'position.department', 'en' => 'position.department'],
                    'notes' => [
                        'de' => 'Primärer Org-Slice für Headcount und Absence-KPIs.',
                        'en' => 'Primary org slice for headcount and absence KPIs.',
                    ],
                ],
                [
                    'id' => 'absence_type',
                    'label' => ['de' => 'Absence Type', 'en' => 'Absence type'],
                    'grain' => ['de' => 'absence.absence_type', 'en' => 'absence.absence_type'],
                    'notes' => [
                        'de' => 'Krankheitsgründe nicht weiter aufschlüsseln (Gesundheitsdaten).',
                        'en' => 'Do not further break down sickness reasons (health data).',
                    ],
                ],
                [
                    'id' => 'employee_status',
                    'label' => ['de' => 'Employee Status', 'en' => 'Employee status'],
                    'grain' => ['de' => 'employee.status', 'en' => 'employee.status'],
                    'notes' => [
                        'de' => 'active/inactive/onboarding für Headcount-Definitionen.',
                        'en' => 'active/inactive/onboarding for headcount definitions.',
                    ],
                ],
                [
                    'id' => 'compensation_type',
                    'label' => ['de' => 'Compensation Type', 'en' => 'Compensation type'],
                    'grain' => ['de' => 'compensation.compensation_type', 'en' => 'compensation.compensation_type'],
                    'notes' => [
                        'de' => 'Für Counts/Existenz-Checks nutzen — nie mit Klartext-Betrag im Mart verknüpfen.',
                        'en' => 'Use for counts/existence checks — never join to cleartext amount in the mart.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Employee',
                    'fields' => ['first_name', 'last_name', 'email'],
                    'treatment' => [
                        'de' => 'Direkte Identifikatoren — employee_id/employee_number als Join bevorzugen.',
                        'en' => 'Direct identifiers — prefer employee_id/employee_number as join.',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'fields' => ['tax_id', 'iban'],
                    'treatment' => [
                        'de' => 'Steuer-ID und IBAN — hashen; Zugriff strikt auf HR/Payroll.',
                        'en' => 'Tax ID and IBAN — hash; restrict access strictly to HR/payroll.',
                    ],
                ],
                [
                    'entity' => 'Compensation',
                    'fields' => ['amount'],
                    'treatment' => [
                        'de' => 'Gehaltsbetrag nie im Klartext in Marts — nur aggregierte Bänder oder Counts.',
                        'en' => 'Never salary amount cleartext in marts — aggregated bands or counts only.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'employee_id, employee_number, tax_id (hashed), iban (hashed).',
                        'en' => 'employee_id, employee_number, tax_id (hashed), iban (hashed).',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Employee, Absence, Compensation, Document — Personio-Export + Warehouse-Kopien.',
                        'en' => 'Employee, absence, compensation, document — Personio export + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'headcount-active',
                    'example' => true,
                    'label' => ['de' => 'Headcount (Active)', 'en' => 'Headcount (active)'],
                    'question' => [
                        'de' => 'Wie viele aktive Employees gibt es (Snapshot)?',
                        'en' => 'How many active employees are there (snapshot)?',
                    ],
                    'formula' => "COUNT(*) FROM employee WHERE status = 'active'",
                    'grain' => ['de' => 'Aktiver Employee', 'en' => 'Active employee'],
                    'dimensions' => ['department', 'employee_status'],
                    'fieldsUsed' => ['Employee.id', 'Employee.status', 'Employee.department'],
                    'sourceHints' => [
                        'de' => 'Onboarding-Status je nach Definition ein- oder ausschließen.',
                        'en' => 'Include or exclude onboarding status depending on the definition.',
                    ],
                    'adapt' => [
                        'de' => 'Working-Student/Freelancer separat vom Core-Headcount ausweisen.',
                        'en' => 'Report working students/freelancers separately from core headcount.',
                    ],
                ],
                [
                    'id' => 'new-hires',
                    'example' => true,
                    'label' => ['de' => 'New Hires', 'en' => 'New hires'],
                    'question' => [
                        'de' => 'Wie viele Employees wurden in der Periode eingestellt?',
                        'en' => 'How many employees were hired in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM employee WHERE hire_date IN period',
                    'grain' => ['de' => 'Neu eingestellter Employee', 'en' => 'Newly hired employee'],
                    'dimensions' => ['department'],
                    'fieldsUsed' => ['Employee.hire_date', 'Employee.department'],
                    'sourceHints' => [
                        'de' => 'Rehires separat kennzeichnen (gleiche employee_number erneut aktiv).',
                        'en' => 'Flag rehires separately (same employee_number active again).',
                    ],
                    'adapt' => [
                        'de' => 'Interne Wechsel (Department-Move) nicht als New Hire zählen.',
                        'en' => 'Do not count internal moves (department change) as new hires.',
                    ],
                ],
                [
                    'id' => 'turnover-rate',
                    'example' => false,
                    'label' => ['de' => 'Turnover Rate', 'en' => 'Turnover rate'],
                    'question' => [
                        'de' => 'Welcher Anteil der Belegschaft hat die Firma in der Periode verlassen?',
                        'en' => 'What share of the workforce left the company in the period?',
                    ],
                    'formula' => 'COUNT(termination_date IN period) / AVG(headcount_active over period)',
                    'grain' => ['de' => 'Terminierter Employee', 'en' => 'Terminated employee'],
                    'dimensions' => ['department'],
                    'fieldsUsed' => ['Employee.termination_date', 'Employee.status', 'Employee.department'],
                    'sourceHints' => [
                        'de' => 'Voluntary vs. involuntary Termination unterscheiden, falls verfügbar.',
                        'en' => 'Distinguish voluntary vs involuntary termination when available.',
                    ],
                    'adapt' => [
                        'de' => 'Probezeit-Terminierungen ggf. separat ausweisen.',
                        'en' => 'Optionally report probation-period terminations separately.',
                    ],
                ],
                [
                    'id' => 'absence-days-total',
                    'example' => false,
                    'label' => ['de' => 'Absence Days Total', 'en' => 'Absence days total'],
                    'question' => [
                        'de' => 'Wie viele Abwesenheitstage wurden in der Periode genommen?',
                        'en' => 'How many absence days were taken in the period?',
                    ],
                    'formula' => "SUM(days_count) FROM absence WHERE status = 'approved' AND start_date IN period",
                    'grain' => ['de' => 'Genehmigte Abwesenheit', 'en' => 'Approved absence'],
                    'dimensions' => ['department', 'absence_type'],
                    'fieldsUsed' => ['Absence.days_count', 'Absence.status', 'Absence.start_date', 'Absence.absence_type'],
                    'sourceHints' => [
                        'de' => 'Nur approved Status zählen; requested/rejected ausschließen.',
                        'en' => 'Count only approved status; exclude requested/rejected.',
                    ],
                    'adapt' => [
                        'de' => 'Urlaub vs. Krankheit getrennt ausweisen für Workforce-Planung.',
                        'en' => 'Report vacation vs sick leave separately for workforce planning.',
                    ],
                ],
                [
                    'id' => 'avg-tenure-years',
                    'example' => false,
                    'label' => ['de' => 'Average Tenure (Years)', 'en' => 'Average tenure (years)'],
                    'question' => [
                        'de' => 'Wie lange sind aktive Employees im Schnitt im Unternehmen?',
                        'en' => 'On average, how long have active employees been with the company?',
                    ],
                    'formula' => "AVG(CURRENT_DATE - hire_date) FROM employee WHERE status = 'active'",
                    'grain' => ['de' => 'Aktiver Employee', 'en' => 'Active employee'],
                    'dimensions' => ['department'],
                    'fieldsUsed' => ['Employee.hire_date', 'Employee.status', 'Employee.department'],
                    'sourceHints' => [
                        'de' => 'Bei Rehire ursprüngliches vs. letztes hire_date festlegen.',
                        'en' => 'For rehires, lock original vs latest hire_date.',
                    ],
                    'adapt' => [
                        'de' => 'Median-Tenure ergänzen, da Verteilung oft schief ist.',
                        'en' => 'Add median tenure since the distribution is often skewed.',
                    ],
                ],
            ],
            'tools' => $erpFinanceHcmTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
