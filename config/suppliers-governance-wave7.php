<?php

/**
 * Wave 7 governance overlays — Markets & Insurance source-native PII, DSDR, skip guidance.
 * Merged onto catalog products by id (murex, fis, guidewire, duck-creek).
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'murex' => [
        'pii' => [
            [
                'entity' => 'Counterparty / legal entity',
                'fields' => ['counterpartyId', 'lei', 'name', 'address'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Legal-Basis nötig; Curated: counterpartyId only; Mart: aggregates only', 'en' => 'RAW: legal basis required; Curated: counterpartyId only; Mart: aggregates only'],
                'treatment' => ['de' => 'Legal-Entity-Name/LEI/Adresse — geschäftssensibel; counterpartyId als Join behalten.', 'en' => 'Legal entity name/LEI/address — business-sensitive; keep counterpartyId as join.'],
            ],
            [
                'entity' => 'Trader / front-office user',
                'fields' => ['traderId', 'salesId'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Trader/Sales-Kennung — Workforce-PII; Zugriff auf Front-Office-Rollen beschränken.', 'en' => 'Trader/sales identifier — workforce PII; restrict access to front-office roles.'],
            ],
            [
                'entity' => 'Trade economic terms',
                'fields' => ['notionalAmount', 'price', 'spread', 'counterpartyId'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: aggregiert je Book/Product; Mart: nur Aggregate', 'en' => 'RAW: restrict access; Curated: aggregated by book/product; Mart: aggregates only'],
                'treatment' => ['de' => 'Deal-Economics sind Insider-relevant — wie PII behandeln; Row-Level-Zugriff nur mit klarer Rolle.', 'en' => 'Deal economics are insider-relevant — treat like PII; row-level access only with a clear role.'],
            ],
            [
                'entity' => 'Settlement standing instructions (SSI)',
                'fields' => ['beneficiaryBankAccount', 'settlementInstructions'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'SSI/Bankverbindungen nie ins Warehouse — Fraud-Risiko; bleiben im Settlement-System.', 'en' => 'Never land SSI/bank details in the warehouse — fraud risk; keep in the settlement system.'],
            ],
            [
                'entity' => 'Voice / chat surveillance data',
                'fields' => ['turret recording', 'chat transcript', 'voice recording'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Surveillance-Daten gehören ins Compliance-Recording-System, nicht ins Analytics-Warehouse.', 'en' => 'Surveillance data belongs in the compliance recording system, not the analytics warehouse.'],
            ],
            [
                'entity' => 'Licensed market data feeds',
                'fields' => ['vendor quote', 'curve snapshot'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Lizenzvertrag prüfen; Curated: EOD-Snapshot only; Mart: derived rates only', 'en' => 'RAW: check licensing terms; Curated: EOD snapshot only; Mart: derived rates only'],
                'treatment' => ['de' => 'Marktdaten-Feeds unterliegen oft Redistribution-Verboten — Vendor-Vertrag vor Warehouse-Nutzung prüfen.', 'en' => 'Market data feeds often carry redistribution restrictions — check the vendor contract before warehouse use.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'dealId, counterpartyId, LEI (hashed), bookId, instrumentId/ISIN.', 'en' => 'dealId, counterpartyId, LEI (hashed), bookId, instrumentId/ISIN.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Trade, Position, Counterparty, Book, Risk Result.', 'en' => 'Trade, position, counterparty, book, risk result.'],
            ],
            [
                'focus' => ['de' => 'Datamart / MxML export copies', 'en' => 'Datamart / MxML export copies'],
                'notes' => ['de' => 'Murex Datamart-Extrakte und MxML-Exports verdoppeln Trade- und Counterparty-Daten.', 'en' => 'Murex Datamart extracts and MxML exports duplicate trade and counterparty data.'],
            ],
            [
                'focus' => ['de' => 'UAT / sandbox trading environment copies', 'en' => 'UAT / sandbox trading environment copies'],
                'notes' => ['de' => 'Test-/UAT-Umgebungen nicht mit Prod-Risk-Marts mischen.', 'en' => 'Do not mix test/UAT environments with prod risk marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Settlement standing instructions / beneficiary bank details',
                'category' => 'system',
                'reason' => ['de' => 'SSI/Bankverbindungen — Fraud-Risiko; nie ins Warehouse.', 'en' => 'SSI/bank details — fraud risk; never into the warehouse.'],
            ],
            [
                'name' => 'Turret / chat surveillance recordings',
                'category' => 'system',
                'reason' => ['de' => 'Compliance-Recording-System — kein Analytics-Kern.', 'en' => 'Compliance recording system — not analytics core.'],
            ],
            [
                'name' => 'Raw FpML/SWIFT confirmation payloads (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Volumen und Redundanz zum Match-Status.', 'en' => 'Volume and redundant with the match status.'],
            ],
            [
                'name' => 'Full intraday tick-by-tick market data',
                'category' => 'system',
                'reason' => ['de' => 'Hohes Volumen und Lizenz-Restriktionen — EOD-Snapshots reichen.', 'en' => 'High volume and licensing restrictions — EOD snapshots suffice.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Settlement standing instructions / bank details',
                'reason' => ['de' => 'Fraud-Risiko — nie ins Warehouse.', 'en' => 'Fraud risk — never into the warehouse.'],
            ],
            [
                'name' => 'Trade economic terms cleartext in public marts',
                'reason' => ['de' => 'Insider-relevant — Aggregates statt Row-Level.', 'en' => 'Insider-relevant — aggregates instead of row-level.'],
            ],
            [
                'name' => 'Voice/chat surveillance recordings',
                'reason' => ['de' => 'Compliance-System, nicht Analytics.', 'en' => 'Compliance system, not analytics.'],
            ],
            [
                'name' => 'Raw confirmation payloads (bulk)',
                'reason' => ['de' => 'Volumen — Match-Status reicht.', 'en' => 'Volume — match status suffices.'],
            ],
            [
                'name' => 'Intraday tick history in prod marts',
                'reason' => ['de' => 'Volumen/Lizenz — EOD bevorzugen.', 'en' => 'Volume/licensing — prefer EOD.'],
            ],
        ],
    ],

    'fis' => [
        'pii' => [
            [
                'entity' => 'Customer / party (CIF)',
                'fields' => ['customerId', 'taxId', 'fullName', 'dateOfBirth', 'address', 'phone'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Legal-Basis/KYC-Policy; Curated: customerId only; Mart: aggregates only', 'en' => 'RAW: legal basis/KYC policy; Curated: customerId only; Mart: aggregates only'],
                'treatment' => ['de' => 'Customer Information File — Tax-ID/Name/Adresse nur mit Legal-Basis; customerId als Key behalten.', 'en' => 'Customer information file — tax ID/name/address only with legal basis; keep customerId as key.'],
            ],
            [
                'entity' => 'Card / PAN data',
                'fields' => ['pan', 'cvv', 'expiryDate', 'magneticStripe'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie Klartext-PAN/CVV; Curated: nie; Mart: nie', 'en' => 'RAW: never cleartext PAN/CVV; Curated: never; Mart: never'],
                'treatment' => ['de' => 'PCI-DSS-Scope — nur tokenisierte/maskierte PAN, nie CVV oder Magnetstreifen.', 'en' => 'PCI-DSS scope — tokenized/masked PAN only, never CVV or magnetic stripe.'],
            ],
            [
                'entity' => 'Payment beneficiary details',
                'fields' => ['beneficiaryAccount', 'beneficiaryName', 'iban'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Begünstigten-IBAN/Name sind PII — für AML/Sanction-Screening getrennt vom Analytics-Mart halten.', 'en' => 'Beneficiary IBAN/name are PII — keep separate from the analytics mart for AML/sanction screening.'],
            ],
            [
                'entity' => 'KYC identity documents',
                'fields' => ['idDocumentScan', 'proofOfAddress', 'sanctionScreeningResult'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Identitätsdokumente und Screening-Resultate bleiben im KYC-System — nicht ins Warehouse.', 'en' => 'Identity documents and screening results stay in the KYC system — not into the warehouse.'],
            ],
            [
                'entity' => 'Transaction / posting narrative',
                'fields' => ['memo', 'freeTextReference'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Freitext-Memos können PII/Krankheitsdetails enthalten (z. B. Medical Payments) — Metadata reicht für KPIs.', 'en' => 'Free-text memos can contain PII/health detail (e.g. medical payments) — metadata enough for KPIs.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'customerId, taxId (hashed), accountId, cardId (tokenized).', 'en' => 'customerId, taxId (hashed), accountId, cardId (tokenized).'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Customer, Account, Card, Loan, Payment.', 'en' => 'Customer, account, card, loan, payment.'],
            ],
            [
                'focus' => ['de' => 'Core-banking / Payments Hub export copies', 'en' => 'Core-banking / Payments Hub export copies'],
                'notes' => ['de' => 'Batch-Extracts und Payments-Hub-Logs verdoppeln Customer- und Beneficiary-PII.', 'en' => 'Batch extracts and Payments Hub logs duplicate customer and beneficiary PII.'],
            ],
            [
                'focus' => ['de' => 'Test / UAT core-banking copies', 'en' => 'Test / UAT core-banking copies'],
                'notes' => ['de' => 'Test-Instanzen nicht mit Prod-Banking-Marts mischen.', 'en' => 'Do not mix test instances with prod banking marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Card PAN / CVV / magnetic stripe data',
                'category' => 'system',
                'reason' => ['de' => 'PCI-DSS-Scope — nie speichern.', 'en' => 'PCI-DSS scope — never store.'],
            ],
            [
                'name' => 'KYC identity documents / sanction screening payloads',
                'category' => 'system',
                'reason' => ['de' => 'Hochsensibel — bleibt im KYC-System.', 'en' => 'Highly sensitive — stays in the KYC system.'],
            ],
            [
                'name' => 'Full account statement text/PDF exports',
                'category' => 'system',
                'reason' => ['de' => 'Unstrukturiert und groß — strukturierte Postings reichen.', 'en' => 'Unstructured and large — structured postings suffice.'],
            ],
            [
                'name' => 'Transaction free-text memos (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Können PII/Health-Detail enthalten — Metadata reicht.', 'en' => 'Can contain PII/health detail — metadata is enough.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Card PAN / CVV cleartext',
                'reason' => ['de' => 'PCI-DSS — nie speichern.', 'en' => 'PCI-DSS — never store.'],
            ],
            [
                'name' => 'KYC documents / sanction screening results',
                'reason' => ['de' => 'Bleibt im KYC-System.', 'en' => 'Stays in the KYC system.'],
            ],
            [
                'name' => 'Beneficiary IBAN/name cleartext in marts',
                'reason' => ['de' => 'PII — hashen/tokenisieren.', 'en' => 'PII — hash/tokenize.'],
            ],
            [
                'name' => 'Transaction free-text memos',
                'reason' => ['de' => 'Kann Health-/PII-Detail enthalten.', 'en' => 'Can contain health/PII detail.'],
            ],
            [
                'name' => 'Test/UAT customers in prod marts',
                'reason' => ['de' => 'Prod-Banking-Marts sauber halten.', 'en' => 'Keep prod banking marts clean.'],
            ],
        ],
    ],

    'guidewire' => [
        'pii' => [
            [
                'entity' => 'Account / insured party',
                'fields' => ['accountNumber', 'insuredName', 'address', 'dateOfBirth'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Legal-Basis; Curated: accountNumber only; Mart: aggregates only', 'en' => 'RAW: legal basis; Curated: accountNumber only; Mart: aggregates only'],
                'treatment' => ['de' => 'Insured-Stammdaten — direkte Identifikatoren; accountNumber als Join behalten.', 'en' => 'Insured master data — direct identifiers; keep accountNumber as join.'],
            ],
            [
                'entity' => 'Claimant / injured party',
                'fields' => ['claimantName', 'claimantAddress', 'claimantDateOfBirth'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: claim id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: claim id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Claimant-Identität oft mit Schaden-/Verletzungsdetails verknüpft — strikt einschränken.', 'en' => 'Claimant identity often linked to loss/injury detail — restrict strictly.'],
            ],
            [
                'entity' => 'Claim narrative / medical detail',
                'fields' => ['adjusterNotes', 'medicalRecords', 'injuryDescription'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Besondere Kategorie Gesundheitsdaten — nie ins Analytics-Warehouse, bleibt in ClaimCenter.', 'en' => 'Special-category health data — never into the analytics warehouse, stays in ClaimCenter.'],
            ],
            [
                'entity' => 'Driver / vehicle identifiers (auto LOB)',
                'fields' => ['driverLicenseNumber', 'vin'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash; Mart: no cleartext'],
                'treatment' => ['de' => 'Führerscheinnummer ist direkter Identifikator — hashen für Fraud-Matching statt Klartext-Mart.', 'en' => 'Driver license number is a direct identifier — hash for fraud matching instead of a cleartext mart.'],
            ],
            [
                'entity' => 'Producer / agency contact',
                'fields' => ['producerContactEmail', 'producerContactPhone'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: producerCode only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: producerCode only; Mart: aggregates only'],
                'treatment' => ['de' => 'Producer-Kontaktdaten — Workforce-artige PII; producerCode als Join bevorzugen.', 'en' => 'Producer contact data — workforce-style PII; prefer producerCode as join.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'accountNumber, policyNumber, claimNumber, PublicID.', 'en' => 'accountNumber, policyNumber, claimNumber, PublicID.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Account, Policy, Claim, ClaimTransaction, BillingInvoice.', 'en' => 'Account, policy, claim, claim transaction, billing invoice.'],
            ],
            [
                'focus' => ['de' => 'PolicyCenter / ClaimCenter / BillingCenter export copies', 'en' => 'PolicyCenter / ClaimCenter / BillingCenter export copies'],
                'notes' => ['de' => 'Cross-Center-Datamarts und REST-Exporte verdoppeln Insured- und Claimant-PII.', 'en' => 'Cross-center datamarts and REST exports duplicate insured and claimant PII.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / UAT Guidewire Cloud copies', 'en' => 'Sandbox / UAT Guidewire Cloud copies'],
                'notes' => ['de' => 'UAT-Tenants nicht mit Prod-Insurance-Marts mischen.', 'en' => 'Do not mix UAT tenants with prod insurance marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Claim adjuster notes / activity narrative (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Medical/Legal-Detail — hochsensibel, bleibt in ClaimCenter.', 'en' => 'Medical/legal detail — highly sensitive, stays in ClaimCenter.'],
            ],
            [
                'name' => 'Medical bills / injury documentation attachments',
                'category' => 'system',
                'reason' => ['de' => 'Besondere Kategorie Gesundheitsdaten — nie im Warehouse.', 'en' => 'Special-category health data — never in the warehouse.'],
            ],
            [
                'name' => 'Policy binder / declaration PDF content',
                'category' => 'system',
                'reason' => ['de' => 'Unstrukturiert — strukturierte Policy-Felder reichen.', 'en' => 'Unstructured — structured policy fields suffice.'],
            ],
            [
                'name' => 'SIU fraud investigation case files',
                'category' => 'system',
                'reason' => ['de' => 'Legal-Hold-sensibel — getrennt von Analytics.', 'en' => 'Legal-hold sensitive — kept separate from analytics.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Claim adjuster / activity narrative',
                'reason' => ['de' => 'Medical/Legal-Detail.', 'en' => 'Medical/legal detail.'],
            ],
            [
                'name' => 'Medical documentation attachments',
                'reason' => ['de' => 'Besondere Kategorie Gesundheitsdaten.', 'en' => 'Special-category health data.'],
            ],
            [
                'name' => 'Claimant/driver identifiers cleartext in marts',
                'reason' => ['de' => 'Hashen statt Klartext.', 'en' => 'Hash instead of cleartext.'],
            ],
            [
                'name' => 'Policy binder / document content',
                'reason' => ['de' => 'Unstrukturiert — strukturierte Felder reichen.', 'en' => 'Unstructured — structured fields suffice.'],
            ],
            [
                'name' => 'SIU fraud investigation files',
                'reason' => ['de' => 'Legal Hold — nicht in Analytics.', 'en' => 'Legal hold — not in analytics.'],
            ],
        ],
    ],

    'duck-creek' => [
        'pii' => [
            [
                'entity' => 'Account / insured party',
                'fields' => ['accountNumber', 'insuredName', 'address', 'dateOfBirth'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Legal-Basis; Curated: accountNumber only; Mart: aggregates only', 'en' => 'RAW: legal basis; Curated: accountNumber only; Mart: aggregates only'],
                'treatment' => ['de' => 'Insured-Stammdaten — direkte Identifikatoren; accountNumber als Join behalten.', 'en' => 'Insured master data — direct identifiers; keep accountNumber as join.'],
            ],
            [
                'entity' => 'Claimant / injured party',
                'fields' => ['claimantName', 'claimantAddress'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: claim id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: claim id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Claimant-Identität oft mit Schaden-/Verletzungsdetails verknüpft — strikt einschränken.', 'en' => 'Claimant identity often linked to loss/injury detail — restrict strictly.'],
            ],
            [
                'entity' => 'Claim narrative / correspondence',
                'fields' => ['adjusterNotes', 'correspondenceContent', 'injuryDescription'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Adjuster-Notizen/Korrespondenz können medizinische Details enthalten — nie ins Warehouse.', 'en' => 'Adjuster notes/correspondence can contain medical detail — never into the warehouse.'],
            ],
            [
                'entity' => 'Underwriting application data',
                'fields' => ['applicationDocument', 'inspectionReport'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Underwriting-Dokumente enthalten oft Identitäts-/Objektdetails — bleiben im Quellsystem.', 'en' => 'Underwriting documents often contain identity/property detail — stay in the source system.'],
            ],
            [
                'entity' => 'Producer / agency contact',
                'fields' => ['producerContactEmail', 'producerContactPhone'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: producerCode only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: producerCode only; Mart: aggregates only'],
                'treatment' => ['de' => 'Producer-Kontaktdaten — Workforce-artige PII; producerCode als Join bevorzugen.', 'en' => 'Producer contact data — workforce-style PII; prefer producerCode as join.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'accountNumber, policyNumber, claimNumber.', 'en' => 'accountNumber, policyNumber, claimNumber.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Account, PolicyTransaction, Claim, ClaimActivity, BillingTransaction.', 'en' => 'Account, policy transaction, claim, claim activity, billing transaction.'],
            ],
            [
                'focus' => ['de' => 'Insights / OnDemand export copies', 'en' => 'Insights / OnDemand export copies'],
                'notes' => ['de' => 'Duck Creek Insights-Datamarts und OnDemand-Extrakte verdoppeln Insured- und Claimant-PII.', 'en' => 'Duck Creek Insights datamarts and OnDemand extracts duplicate insured and claimant PII.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / UAT tenant copies', 'en' => 'Sandbox / UAT tenant copies'],
                'notes' => ['de' => 'UAT-Tenants nicht mit Prod-Insurance-Marts mischen.', 'en' => 'Do not mix UAT tenants with prod insurance marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Claim adjuster notes / correspondence content (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Medical/Legal-Detail — hochsensibel, bleibt im Quellsystem.', 'en' => 'Medical/legal detail — highly sensitive, stays in the source system.'],
            ],
            [
                'name' => 'Underwriting document attachments',
                'category' => 'system',
                'reason' => ['de' => 'Identitäts-/Objektdetails — hochsensibel.', 'en' => 'Identity/property detail — highly sensitive.'],
            ],
            [
                'name' => 'Policy print / document template content',
                'category' => 'system',
                'reason' => ['de' => 'Unstrukturiert — strukturierte Policy-Felder reichen.', 'en' => 'Unstructured — structured policy fields suffice.'],
            ],
            [
                'name' => 'Legal / SIU investigation notes',
                'category' => 'system',
                'reason' => ['de' => 'Legal-Hold-sensibel — getrennt von Analytics.', 'en' => 'Legal-hold sensitive — kept separate from analytics.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Claim adjuster notes / correspondence',
                'reason' => ['de' => 'Medical/Legal-Detail.', 'en' => 'Medical/legal detail.'],
            ],
            [
                'name' => 'Underwriting document attachments',
                'reason' => ['de' => 'Identitäts-/Objektdetails.', 'en' => 'Identity/property detail.'],
            ],
            [
                'name' => 'Claimant identifiers cleartext in marts',
                'reason' => ['de' => 'Hashen statt Klartext.', 'en' => 'Hash instead of cleartext.'],
            ],
            [
                'name' => 'Policy print / document templates',
                'reason' => ['de' => 'Unstrukturiert — strukturierte Felder reichen.', 'en' => 'Unstructured — structured fields suffice.'],
            ],
            [
                'name' => 'Legal / SIU investigation notes',
                'reason' => ['de' => 'Legal Hold — nicht in Analytics.', 'en' => 'Legal hold — not in analytics.'],
            ],
        ],
    ],
];
