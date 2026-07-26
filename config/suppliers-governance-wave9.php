<?php

/**
 * Wave 9 governance overlays — ERP/Finance/HCM source-native PII, DSDR, skip guidance.
 * Merged onto catalog products by id (datev, sage, oracle-fusion, personio).
 *
 * Strict workforce PII default: no salary/compensation cleartext in marts; tax IDs,
 * national identifiers and bank details are hashed or access-restricted at every stage.
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'datev' => [
        'pii' => [
            [
                'entity' => 'Debitor / Kreditor (sub-ledger master)',
                'fields' => ['name', 'address', 'contact person', 'phone', 'email'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: Nummer only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: number only; Mart: aggregates only'],
                'treatment' => ['de' => 'Name/Adresse/Kontakt — direkte Identifikatoren; Debitoren-/Kreditorennummer als Key behalten.', 'en' => 'Name/address/contact — direct identifiers; keep debtor/creditor number as key.'],
            ],
            [
                'entity' => 'Steuer-ID / USt-IdNr.',
                'fields' => ['tax_id', 'ust_id_nr', 'steuernummer'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: gehasht oder eingeschränkt; Curated: gehasht; Mart: nie im Klartext', 'en' => 'RAW: hashed or restricted; Curated: hashed; Mart: never cleartext'],
                'treatment' => ['de' => 'Steuernummern nie im Klartext über RAW hinaus — hashen für Matching.', 'en' => 'Never carry tax numbers cleartext beyond RAW — hash for matching.'],
            ],
            [
                'entity' => 'Bankverbindung (IBAN/BIC)',
                'fields' => ['iban', 'bic', 'kontoinhaber'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: nie; Mart: nie', 'en' => 'RAW: restrict access; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Bankdaten nur für Zahlungsprozesse — nie in Analytics-Marts.', 'en' => 'Bank data only for payment processes — never in analytics marts.'],
            ],
            [
                'entity' => 'Buchungstext / Posting text',
                'fields' => ['posting_text', 'buchungstext'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: prüfen; Curated: redigieren falls Personenbezug; Mart: aggregates only', 'en' => 'RAW: review; Curated: redact if personal reference; Mart: aggregates only'],
                'treatment' => ['de' => 'Freitext kann Namen/Referenzen enthalten — Stichproben-Scan vor Freigabe.', 'en' => 'Free text can contain names/references — sample scan before release.'],
            ],
            [
                'entity' => 'DATEV Lohn und Gehalt (falls co-migriert)',
                'fields' => ['gehalt', 'sozialversicherungsnummer', 'lohnsteuerklasse'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: nie im Finance-Warehouse; separates Payroll-Modul mit eigener Policy', 'en' => 'RAW: never in the finance warehouse; separate payroll module with its own policy'],
                'treatment' => ['de' => 'Lohn-/Gehaltsdaten strikt vom Finance-Buchhaltungs-Warehouse trennen.', 'en' => 'Strictly separate payroll/salary data from the finance accounting warehouse.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'Debitoren-/Kreditorennummer, USt-IdNr./Steuernummer (hashed), IBAN (hashed).', 'en' => 'Debtor/creditor number, VAT ID/tax number (hashed), IBAN (hashed).'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Debitor, Kreditor, Buchungssatz, Beleg, Mandant.', 'en' => 'Debtor, creditor, booking record, document, client (Mandant).'],
            ],
            [
                'focus' => ['de' => 'DATEV-Export / DUO / Rewe-Schnittstellen', 'en' => 'DATEV export / DUO / Rewe interfaces'],
                'notes' => ['de' => 'ASCII/EXTF-Exporte und DATEV-Connect-Kopien verdoppeln Debitoren-/Kreditoren-PII.', 'en' => 'ASCII/EXTF exports and DATEV Connect copies duplicate debtor/creditor PII.'],
            ],
            [
                'focus' => ['de' => 'Mandanten-/Sandbox-Kopien', 'en' => 'Client (Mandant) / sandbox copies'],
                'notes' => ['de' => 'Test-Mandanten nicht mit Prod-Finance-Marts mischen.', 'en' => 'Do not mix test clients (Mandanten) with prod finance marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Belegbilder / OCR-Scans',
                'category' => 'system',
                'reason' => ['de' => 'Binaries — kein Analytics-Nutzen; Belegdatum/-betrag reichen.', 'en' => 'Binaries — no analytics value; document date/amount suffice.'],
            ],
            [
                'name' => 'DATEV Lohn und Gehalt (Klartext)',
                'category' => 'system',
                'reason' => ['de' => 'Separates strenges Workforce-PII-Modul — nie im Finance-Warehouse.', 'en' => 'Separate strict workforce-PII module — never in the finance warehouse.'],
            ],
            [
                'name' => 'Bankverbindungen (IBAN/BIC, bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Sensible Bankdaten — nur für Zahlungsprozesse, nicht Analytics.', 'en' => 'Sensitive bank data — only for payment processes, not analytics.'],
            ],
            [
                'name' => 'Verfahrensdokumentation-Exporte',
                'category' => 'system',
                'reason' => ['de' => 'Compliance-Archiv, kein Analytics-Kern.', 'en' => 'Compliance archive, not analytics core.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Belegbilder / Scans',
                'reason' => ['de' => 'Binaries — kein Analytics-Nutzen.', 'en' => 'Binaries — no analytics value.'],
            ],
            [
                'name' => 'Steuer-ID / USt-IdNr. Klartext in Marts',
                'reason' => ['de' => 'Debitoren-/Kreditorennummer reicht für Finance-KPIs.', 'en' => 'Debtor/creditor number is enough for finance KPIs.'],
            ],
            [
                'name' => 'IBAN/BIC Klartext',
                'reason' => ['de' => 'Nie ins Analytics-Warehouse.', 'en' => 'Never into the analytics warehouse.'],
            ],
            [
                'name' => 'DATEV Lohn und Gehalt',
                'reason' => ['de' => 'Separates Payroll-PII-Modul.', 'en' => 'Separate payroll PII module.'],
            ],
            [
                'name' => 'Test-Mandanten in Prod-Marts',
                'reason' => ['de' => 'Prod-Finance-Marts sauber halten.', 'en' => 'Keep prod finance marts clean.'],
            ],
        ],
    ],

    'sage' => [
        'pii' => [
            [
                'entity' => 'Customer / Supplier master',
                'fields' => ['name', 'contact_email', 'contact_phone', 'billing_address'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Kontaktdaten — direkte Identifikatoren; customer_id/supplier_id als Key behalten.', 'en' => 'Contact data — direct identifiers; keep customer_id/supplier_id as key.'],
            ],
            [
                'entity' => 'Supplier bank details',
                'fields' => ['bank_details', 'iban', 'account_number'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: nie; Mart: nie', 'en' => 'RAW: restrict access; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Bankdaten nur für Zahlungsläufe — nie in Analytics-Marts.', 'en' => 'Bank data only for payment runs — never in analytics marts.'],
            ],
            [
                'entity' => 'Tax registration numbers',
                'fields' => ['tax_registration_number', 'vat_number'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: gehasht oder eingeschränkt; Curated: gehasht; Mart: nie im Klartext', 'en' => 'RAW: hashed or restricted; Curated: hashed; Mart: never cleartext'],
                'treatment' => ['de' => 'Steuernummern hashen für Matching, nie im Klartext in Marts.', 'en' => 'Hash tax numbers for matching, never cleartext in marts.'],
            ],
            [
                'entity' => 'Sage HR/Payroll (falls co-installiert)',
                'fields' => ['salary', 'national_insurance_number'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: nie im ERP-Finance-Warehouse; separates Payroll-Modul', 'en' => 'RAW: never in the ERP finance warehouse; separate payroll module'],
                'treatment' => ['de' => 'Payroll-Daten strikt vom Finance/ERP-Warehouse trennen.', 'en' => 'Strictly separate payroll data from the finance/ERP warehouse.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'customer_id, supplier_id, tax registration number (hashed), bank account (hashed).', 'en' => 'customer_id, supplier_id, tax registration number (hashed), bank account (hashed).'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Customer, Supplier, Journal Entry, Sales Invoice, Purchase Invoice.', 'en' => 'Customer, supplier, journal entry, sales invoice, purchase invoice.'],
            ],
            [
                'focus' => ['de' => 'Multi-Company / Intacct-Entities', 'en' => 'Multi-company / Intacct entities'],
                'notes' => ['de' => 'Mehrere Legal Entities verdoppeln Customer-/Supplier-PII bei falschem Scope.', 'en' => 'Multiple legal entities duplicate customer/supplier PII when scoped incorrectly.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / Test-Company copies', 'en' => 'Sandbox / test-company copies'],
                'notes' => ['de' => 'Test-Companies nicht mit Prod-ERP-Marts mischen.', 'en' => 'Do not mix test companies with prod ERP marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Invoice-Anhänge / Scan-Binaries',
                'category' => 'system',
                'reason' => ['de' => 'PDF/Bild-Anhänge — kein Analytics-Nutzen.', 'en' => 'PDF/image attachments — no analytics value.'],
            ],
            [
                'name' => 'Bank-Reconciliation-Feeds (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Hohes Volumen und Bankdaten — nur abgeglichene Zeilen.', 'en' => 'High volume and bank data — reconciled lines only.'],
            ],
            [
                'name' => 'Sage HR/Payroll Klartext',
                'category' => 'system',
                'reason' => ['de' => 'Gehalt nie im ERP-Finance-Warehouse.', 'en' => 'Never salary in the ERP finance warehouse.'],
            ],
            [
                'name' => 'Audit-Trail / Change-Log-Dumps (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Volumen ohne KPI-Nutzen.', 'en' => 'Volume without KPI value.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Invoice-Scan-Anhänge',
                'reason' => ['de' => 'Binaries — kein Analytics-Nutzen.', 'en' => 'Binaries — no analytics value.'],
            ],
            [
                'name' => 'Supplier-Bankdaten Klartext',
                'reason' => ['de' => 'Nur für Zahlungsläufe, nie Analytics.', 'en' => 'Only for payment runs, never analytics.'],
            ],
            [
                'name' => 'Tax registration number Klartext in Marts',
                'reason' => ['de' => 'customer_id/supplier_id reicht für ERP-KPIs.', 'en' => 'customer_id/supplier_id is enough for ERP KPIs.'],
            ],
            [
                'name' => 'Sage HR/Payroll Klartext',
                'reason' => ['de' => 'Separates PII-Modul.', 'en' => 'Separate PII module.'],
            ],
        ],
    ],

    'oracle-fusion' => [
        'pii' => [
            [
                'entity' => 'HCM Worker (Person)',
                'fields' => ['person_id', 'display_name', 'work_email', 'home_address', 'phone'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: person_id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: person_id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Worker-PII — nur mit Legal-Basis; person_id/person_number als Key behalten.', 'en' => 'Worker PII — only with legal basis; keep person_id/person_number as key.'],
            ],
            [
                'entity' => 'National identifier',
                'fields' => ['national_identifier', 'passport_number', 'visa_details'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: gehasht oder gar nicht laden; Curated: nie; Mart: nie', 'en' => 'RAW: hashed or never loaded; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Steuer-/Sozial-ID/Pass — hashen; Zugriff strikt auf HR/Payroll.', 'en' => 'Tax/social ID/passport — hash; restrict access strictly to HR/payroll.'],
            ],
            [
                'entity' => 'Compensation / Payroll element entries',
                'fields' => ['base_salary', 'bonus_amount', 'element_entry_value'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern (Standard); Curated: nie; Mart: nie', 'en' => 'RAW: never store (default); Curated: never; Mart: never'],
                'treatment' => ['de' => 'Vergütungsbeträge nie ins Standard-Warehouse — nur Bänder/Aggregate mit Freigabe.', 'en' => 'Never land compensation amounts in the standard warehouse — bands/aggregates only with approval.'],
            ],
            [
                'entity' => 'Benefits / health data',
                'fields' => ['benefit_enrollment', 'medical_plan', 'dependent_details'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Gesundheitsbezogene Daten — besondere Kategorie; nie ins Analytics-Warehouse.', 'en' => 'Health-related data — special category; never into the analytics warehouse.'],
            ],
            [
                'entity' => 'Supplier / Customer tax registration',
                'fields' => ['tax_registration_number', 'bill_to_contact_email'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: gehasht oder eingeschränkt; Curated: gehasht; Mart: nie im Klartext', 'en' => 'RAW: hashed or restricted; Curated: hashed; Mart: never cleartext'],
                'treatment' => ['de' => 'Steuer-Registrierungsnummern hashen; Kontakt-E-Mail als PII taggen.', 'en' => 'Hash tax registration numbers; tag contact email as PII.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'person_id, person_number, national_identifier (hashed), supplier_id, customer_id, invoice_id.', 'en' => 'person_id, person_number, national_identifier (hashed), supplier_id, customer_id, invoice_id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Worker, Assignment, Supplier, Customer, GL Journal, AP/AR Invoice.', 'en' => 'Worker, assignment, supplier, customer, GL journal, AP/AR invoice.'],
            ],
            [
                'focus' => ['de' => 'BI Cloud / OTBI / FBDI exports', 'en' => 'BI Cloud / OTBI / FBDI exports'],
                'notes' => ['de' => 'OTBI-Reports und FBDI-Extrakte verdoppeln Worker- und Supplier-PII in Stages.', 'en' => 'OTBI reports and FBDI extracts duplicate worker and supplier PII across stages.'],
            ],
            [
                'focus' => ['de' => 'Multi-Pillar (ERP/HCM/CX) copies', 'en' => 'Multi-pillar (ERP/HCM/CX) copies'],
                'notes' => ['de' => 'HCM-Worker-PII nicht unkontrolliert in ERP/CX-Marts durchreichen.', 'en' => 'Do not pass HCM worker PII into ERP/CX marts uncontrolled.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Payroll Element Entries (Klartext)',
                'category' => 'system',
                'reason' => ['de' => 'Vergütungsbeträge — nie im Standard-Warehouse.', 'en' => 'Compensation amounts — never in the standard warehouse.'],
            ],
            [
                'name' => 'National Identifiers (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Steuer-/Sozial-IDs — hashen oder gar nicht laden.', 'en' => 'Tax/social IDs — hash or never load.'],
            ],
            [
                'name' => 'Benefits-Enrollment / Health-Daten',
                'category' => 'system',
                'reason' => ['de' => 'Besondere Kategorie personenbezogener Daten.', 'en' => 'Special category of personal data.'],
            ],
            [
                'name' => 'OTBI/FBDI Bulk-Extrakte mit PII-Feldern',
                'category' => 'system',
                'reason' => ['de' => 'Verdoppeln PII über Stages — Select-Allowlist statt Full-Extract.', 'en' => 'Duplicate PII across stages — select allowlist instead of full extract.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Compensation/Payroll Klartext',
                'reason' => ['de' => 'Höchste PII-Sensitivität — nie ins Warehouse.', 'en' => 'Highest PII sensitivity — never into the warehouse.'],
            ],
            [
                'name' => 'National Identifiers Klartext',
                'reason' => ['de' => 'Hashen oder nicht laden.', 'en' => 'Hash or do not load.'],
            ],
            [
                'name' => 'Benefits/Health-Daten',
                'reason' => ['de' => 'Besondere Kategorie.', 'en' => 'Special category.'],
            ],
            [
                'name' => 'HCM-Worker-PII in ERP/CX-Marts',
                'reason' => ['de' => 'Cross-Pillar-Leak verhindern.', 'en' => 'Prevent cross-pillar leak.'],
            ],
        ],
    ],

    'personio' => [
        'pii' => [
            [
                'entity' => 'Employee master',
                'fields' => ['first_name', 'last_name', 'email', 'private_address', 'phone'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: employee_id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: employee_id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Direkte Identifikatoren — employee_id/employee_number als Key behalten.', 'en' => 'Direct identifiers — keep employee_id/employee_number as key.'],
            ],
            [
                'entity' => 'Tax ID / social security number',
                'fields' => ['tax_id', 'social_security_number'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: gehasht oder eingeschränkt; Curated: gehasht; Mart: nie im Klartext', 'en' => 'RAW: hashed or restricted; Curated: hashed; Mart: never cleartext'],
                'treatment' => ['de' => 'Steuer-/Sozialversicherungs-ID hashen; Zugriff strikt auf HR/Payroll.', 'en' => 'Hash tax/social security ID; restrict access strictly to HR/payroll.'],
            ],
            [
                'entity' => 'Bank account (IBAN)',
                'fields' => ['iban', 'bic'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: nie; Mart: nie', 'en' => 'RAW: restrict access; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Bankdaten nur für Payroll-Prozesse — nie in Analytics-Marts.', 'en' => 'Bank data only for payroll processes — never in analytics marts.'],
            ],
            [
                'entity' => 'Compensation',
                'fields' => ['amount', 'compensation_type', 'effective_date'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: nie im Klartext; Mart: nie im Klartext', 'en' => 'RAW: restrict access; Curated: never cleartext; Mart: never cleartext'],
                'treatment' => ['de' => 'Gehaltsbetrag nie im Klartext über RAW hinaus — Bänder/Counts für Marts.', 'en' => 'Never carry the salary amount cleartext beyond RAW — bands/counts for marts.'],
            ],
            [
                'entity' => 'Absence reason / health data',
                'fields' => ['sick_note_details', 'diagnosis_code'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Diagnose-Details — besondere Kategorie; absence_type (sick) genügt für KPIs.', 'en' => 'Diagnosis details — special category; absence_type (sick) is sufficient for KPIs.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'employee_id, employee_number, tax_id (hashed), iban (hashed).', 'en' => 'employee_id, employee_number, tax_id (hashed), iban (hashed).'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Employee, Absence, Compensation, Document.', 'en' => 'Employee, absence, compensation, document.'],
            ],
            [
                'focus' => ['de' => 'API / Report Export copies', 'en' => 'API / report export copies'],
                'notes' => ['de' => 'Personio API-Exports und Report-Downloads verdoppeln Employee-PII.', 'en' => 'Personio API exports and report downloads duplicate employee PII.'],
            ],
            [
                'focus' => ['de' => 'Legal Entity / Standort-Kopien', 'en' => 'Legal entity / location copies'],
                'notes' => ['de' => 'Mehrere Legal Entities je Land nicht ungeprüft zusammenführen (Betriebsrat/Mitbestimmung).', 'en' => 'Do not merge multiple legal entities per country without review (works council rules).'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Compensation-Klartext in Marts',
                'category' => 'system',
                'reason' => ['de' => 'Gehaltsbeträge nie standardmäßig in Curated/Marts.', 'en' => 'Never land salary amounts in curated/marts by default.'],
            ],
            [
                'name' => 'Mitarbeiter-Dokument-Binaries',
                'category' => 'system',
                'reason' => ['de' => 'Vertrags-/Ausweis-Scans — kein Analytics-Nutzen, hohes Leak-Risiko.', 'en' => 'Contract/ID scans — no analytics value, high leak risk.'],
            ],
            [
                'name' => 'Krankheits-Diagnose-Details',
                'category' => 'system',
                'reason' => ['de' => 'Gesundheitsdaten — besondere Kategorie.', 'en' => 'Health data — special category.'],
            ],
            [
                'name' => 'IBAN / Bankverbindungen (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Sensible Bankdaten — nur Payroll, nicht Analytics.', 'en' => 'Sensitive bank data — payroll only, not analytics.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Compensation-Klartext',
                'reason' => ['de' => 'Höchste PII-Sensitivität.', 'en' => 'Highest PII sensitivity.'],
            ],
            [
                'name' => 'Dokument-Binaries',
                'reason' => ['de' => 'Kein Analytics-Nutzen.', 'en' => 'No analytics value.'],
            ],
            [
                'name' => 'Krankheits-Diagnose-Details',
                'reason' => ['de' => 'Besondere Kategorie.', 'en' => 'Special category.'],
            ],
            [
                'name' => 'IBAN Klartext',
                'reason' => ['de' => 'Nie ins Analytics-Warehouse.', 'en' => 'Never into the analytics warehouse.'],
            ],
        ],
    ],
];
