<?php

/**
 * Wave 4 governance overlays — Finance/Spend source-native PII, DSDR, skip guidance.
 * Merged onto catalog products by id (stripe, sap-concur, sap-ariba, coupa).
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'stripe' => [
        'pii' => [
            [
                'entity' => 'Customer',
                'fields' => ['id', 'email', 'name', 'phone', 'address', 'shipping'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Stripe Customer — email/name/phone nur mit Legal-Basis; customer.id als Key behalten.', 'en' => 'Stripe customer — email/name/phone only with legal basis; keep customer.id as key.'],
            ],
            [
                'entity' => 'Payment Method / Card (PCI)',
                'fields' => ['card.number (PAN)', 'cvc', 'card.exp_*', 'payment_method details'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'PAN/CVC nie ins Warehouse — Stripe Tokens/pm_*/card.last4 höchstens; PCI-Scope vermeiden.', 'en' => 'Never store PAN/CVC in the warehouse — Stripe tokens/pm_*/card.last4 at most; avoid PCI scope.'],
            ],
            [
                'entity' => 'Billing details on Charge / PaymentIntent',
                'fields' => ['billing_details.name', 'billing_details.email', 'billing_details.address', 'receipt_email'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Billing Details auf Charge/PI — oft zweite Kunden-PII-Fläche neben Customer.', 'en' => 'Billing details on charge/PI — often a second customer PII surface beside Customer.'],
            ],
            [
                'entity' => 'Radar / Dispute evidence',
                'fields' => ['radar.risk_score payloads', 'dispute.evidence.*', 'customer communication'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Radar/Dispute Evidence kann Identitäts- und Transaktionsnachweise enthalten — Default skip.', 'en' => 'Radar/dispute evidence can contain identity and transaction proofs — default skip.'],
            ],
            [
                'entity' => 'Webhook / Event payloads (bulk)',
                'fields' => ['event.data.object nested PII', 'request logs', 'delivery attempts'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: Metadata only; Mart: nie Klartext', 'en' => 'RAW: rarely load; Curated: metadata only; Mart: never cleartext'],
                'treatment' => ['de' => 'Webhook-Noise verdoppelt Customer/Charge-PII — Allowlist Events, keine Full Payloads.', 'en' => 'Webhook noise duplicates customer/charge PII — allowlist events, no full payloads.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'customer.id, charge.id, payment_intent.id, email (hashed), invoice.id, account id.', 'en' => 'customer.id, charge.id, payment_intent.id, email (hashed), invoice.id, account id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Customer, Charge, PaymentIntent, Invoice, Refund, Dispute + nested billing_details.', 'en' => 'Customer, Charge, PaymentIntent, Invoice, Refund, Dispute + nested billing_details.'],
            ],
            [
                'focus' => ['de' => 'API / Sigma / Dashboard exports', 'en' => 'API / Sigma / Dashboard exports'],
                'notes' => ['de' => 'API Lists, Sigma SQL und Dashboard CSV verdoppeln Customer-PII in Stages.', 'en' => 'API lists, Sigma SQL and Dashboard CSV duplicate customer PII into stages.'],
            ],
            [
                'focus' => ['de' => 'Webhook archives', 'en' => 'Webhook archives'],
                'notes' => ['de' => 'Gespeicherte Event-Payloads sind DSDR-Kopien — Retention und Allowlist.', 'en' => 'Stored event payloads are DSDR copies — retention and allowlist.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Webhook event payload archives (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Ops-Noise und PII-Duplikate — Metadata (type, created) reicht oft.', 'en' => 'Ops noise and PII duplicates — metadata (type, created) often enough.'],
            ],
            [
                'name' => 'Radar evidence / dispute file binaries',
                'category' => 'system',
                'reason' => ['de' => 'Evidence Files — teuer, sensibel, selten KPI-Kern.', 'en' => 'Evidence files — expensive, sensitive, rarely KPI core.'],
            ],
            [
                'name' => 'Payment Method PAN / CVC fields',
                'category' => 'system',
                'reason' => ['de' => 'PCI — nie laden; Tokens/last4 höchstens.', 'en' => 'PCI — never load; tokens/last4 at most.'],
            ],
            [
                'name' => 'Request / API log dumps (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Debug-Telemetrie — kein Finance-Mart-Kern.', 'en' => 'Debug telemetry — not finance mart core.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'PAN / CVC / full card number',
                'reason' => ['de' => 'PCI — nie speichern; Stripe Tokens nutzen.', 'en' => 'PCI — never store; use Stripe tokens.'],
            ],
            [
                'name' => 'Full webhook event bodies (bulk)',
                'reason' => ['de' => 'PII-Duplikate und Volumen — Allowlist Events.', 'en' => 'PII duplicates and volume — allowlist events.'],
            ],
            [
                'name' => 'Radar / dispute evidence cleartext',
                'reason' => ['de' => 'Default skip — nur mit Compliance-Use-Case.', 'en' => 'Default skip — only with compliance use case.'],
            ],
            [
                'name' => 'Customer email cleartext in marts',
                'reason' => ['de' => 'customer.id reicht für Payment-KPIs.', 'en' => 'customer.id is enough for payment KPIs.'],
            ],
            [
                'name' => 'Test mode / sandbox charges in prod marts',
                'reason' => ['de' => 'Prod-Finance-Marts sauber halten (livemode).', 'en' => 'Keep prod finance marts clean (livemode).'],
            ],
        ],
    ],

    'sap-concur' => [
        'pii' => [
            [
                'entity' => 'Employee / User',
                'fields' => ['EmployeeID', 'LoginID', 'EmailAddress', 'FirstName', 'LastName', 'PhoneNumber'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: EmployeeID only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: EmployeeID only; Mart: aggregates only'],
                'treatment' => ['de' => 'Concur Employee — Email/Name nur mit Legal-Basis; EmployeeID als Key.', 'en' => 'Concur employee — email/name only with legal basis; EmployeeID as key.'],
            ],
            [
                'entity' => 'Expense report / entry owner',
                'fields' => ['OwnerEmployeeID', 'ApproverEmployeeID', 'Delegate'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: EmployeeID only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: EmployeeID only; Mart: aggregates only'],
                'treatment' => ['de' => 'Owner/Approver als EmployeeID — keine Klartext-Namen in Default-Marts.', 'en' => 'Owner/approver as EmployeeID — no cleartext names in default marts.'],
            ],
            [
                'entity' => 'Receipt images / attachments',
                'fields' => ['ReceiptImageId', 'image binary', 'OCR text', 'filename'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Belegbilder können Namen, Adressen, Kartenfragmente zeigen — Default skip Binary/OCR.', 'en' => 'Receipt images can show names, addresses, card fragments — default skip binary/OCR.'],
            ],
            [
                'entity' => 'Corporate / personal card',
                'fields' => ['CardAccountNumber last4', 'cardholder name', 'PaymentType'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: last4 / token only; Mart: nie Vollnummer', 'en' => 'RAW: restrict access; Curated: last4 / token only; Mart: never full number'],
                'treatment' => ['de' => 'Nur last4/Token — keine PAN; PCI-Scope vermeiden.', 'en' => 'last4/token only — no PAN; avoid PCI scope.'],
            ],
            [
                'entity' => 'Expense comments / attendee lists',
                'fields' => ['Comment', 'Attendees', 'BusinessPurpose free text'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Freitext und Attendees können Personenbezug haben — Metadata für Spend-KPIs reicht oft.', 'en' => 'Free text and attendees can identify persons — metadata often enough for spend KPIs.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'EmployeeID, EmailAddress, ReportID, EntryID, AllocationID, Card last4 (hashed).', 'en' => 'EmployeeID, EmailAddress, ReportID, EntryID, AllocationID, card last4 (hashed).'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Employee, Expense Report, Expense Entry, Allocation, Receipt metadata.', 'en' => 'Employee, expense report, expense entry, allocation, receipt metadata.'],
            ],
            [
                'focus' => ['de' => 'API / FIS extracts', 'en' => 'API / FIS extracts'],
                'notes' => ['de' => 'Expense API und Financial Integration extracts verdoppeln Employee-PII.', 'en' => 'Expense API and Financial Integration extracts duplicate employee PII.'],
            ],
            [
                'focus' => ['de' => 'Receipt / imaging store copies', 'en' => 'Receipt / imaging store copies'],
                'notes' => ['de' => 'Image Store und OCR-Kopien sind DSDR — Retention getrennt von Spend-Facts.', 'en' => 'Image store and OCR copies are DSDR — separate retention from spend facts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Receipt image binaries / OCR text',
                'category' => 'system',
                'reason' => ['de' => 'Medien und OCR — teuer, PII-riskant; Metadata reicht für Spend.', 'en' => 'Media and OCR — expensive, PII-risky; metadata enough for spend.'],
            ],
            [
                'name' => 'Expense comment / attendee free text (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Unstrukturierte Workforce-PII — Default skip.', 'en' => 'Unstructured workforce PII — default skip.'],
            ],
            [
                'name' => 'Card PAN / full account numbers',
                'category' => 'system',
                'reason' => ['de' => 'PCI — nie laden; last4 höchstens.', 'en' => 'PCI — never load; last4 at most.'],
            ],
            [
                'name' => 'Audit / workflow history blobs (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Ops-Telemetrie — Status-Timestamps reichen oft.', 'en' => 'Ops telemetry — status timestamps often enough.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Receipt image / OCR content',
                'reason' => ['de' => 'Kosten und PII ohne KPI-Nutzen.', 'en' => 'Cost and PII without KPI value.'],
            ],
            [
                'name' => 'PAN / full card numbers',
                'reason' => ['de' => 'PCI — nur last4/Token.', 'en' => 'PCI — last4/token only.'],
            ],
            [
                'name' => 'Employee email cleartext in marts',
                'reason' => ['de' => 'EmployeeID reicht für Spend-by-Owner.', 'en' => 'EmployeeID is enough for spend-by-owner.'],
            ],
            [
                'name' => 'Comment / attendee cleartext (bulk)',
                'reason' => ['de' => 'PII — gezielt oder gar nicht.', 'en' => 'PII — selectively or not at all.'],
            ],
            [
                'name' => 'Test / sandbox company reports',
                'reason' => ['de' => 'Prod-Spend-Marts sauber halten.', 'en' => 'Keep prod spend marts clean.'],
            ],
        ],
    ],

    'sap-ariba' => [
        'pii' => [
            [
                'entity' => 'Supplier contact',
                'fields' => ['supplier contact name', 'email', 'phone', 'SM vendor id'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Supplier Contacts — email/phone taggen; Supplier System ID als Key behalten.', 'en' => 'Supplier contacts — tag email/phone; keep supplier system id as key.'],
            ],
            [
                'entity' => 'Buyer / requester identity',
                'fields' => ['requester uniqueName', 'approver', 'emailAddress', 'buyer name'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: uniqueName / userId only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: uniqueName / userId only; Mart: aggregates only'],
                'treatment' => ['de' => 'Buyer/Requester als User-Key — keine Klartext-E-Mails in Default-Marts.', 'en' => 'Buyer/requester as user key — no cleartext emails in default marts.'],
            ],
            [
                'entity' => 'Document attachments',
                'fields' => ['attachment filename', 'attachment content', 'RFX/contract files'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Anhänge können Verträge und Personenbezug enthalten — Default skip Binary.', 'en' => 'Attachments can contain contracts and personal data — default skip binary.'],
            ],
            [
                'entity' => 'Supplier master address / tax',
                'fields' => ['postal address', 'tax id fragments', 'remittance contacts'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Supplier Master Adressen/Tax — eigene Policy vs. Buyer-Workforce-PII.', 'en' => 'Supplier master address/tax — separate policy from buyer workforce PII.'],
            ],
            [
                'entity' => 'Approval / negotiation comments',
                'fields' => ['comment text', 'message board', 'RFX Q&A'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Kommentare — ungeprüfte PII; Status-Metadata reicht für Procure-KPIs.', 'en' => 'Comments — unchecked PII; status metadata enough for procure KPIs.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'Supplier System ID, ERP vendor id, PO number, invoice id, requester uniqueName, email (hashed).', 'en' => 'Supplier system id, ERP vendor id, PO number, invoice id, requester uniqueName, email (hashed).'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Supplier, Purchase Order, Invoice, Contract, Requisition + Contacts.', 'en' => 'Supplier, purchase order, invoice, contract, requisition + contacts.'],
            ],
            [
                'focus' => ['de' => 'Reporting / ITK / API exports', 'en' => 'Reporting / ITK / API exports'],
                'notes' => ['de' => 'Analytical Reporting und Integration Toolkit Exports verdoppeln Supplier-/Buyer-PII.', 'en' => 'Analytical Reporting and Integration Toolkit exports duplicate supplier/buyer PII.'],
            ],
            [
                'focus' => ['de' => 'Realm / sandbox copies', 'en' => 'Realm / sandbox copies'],
                'notes' => ['de' => 'Sandbox-Realms nicht mit Prod-Spend-Marts mischen.', 'en' => 'Do not mix sandbox realms with prod spend marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Document attachment binaries',
                'category' => 'system',
                'reason' => ['de' => 'Binaries — teuer und sensibel; Metadata reicht.', 'en' => 'Binaries — expensive and sensitive; metadata enough.'],
            ],
            [
                'name' => 'Approval / RFX comment bodies (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Freitext-PII — Status-Timestamps reichen oft.', 'en' => 'Free-text PII — status timestamps often enough.'],
            ],
            [
                'name' => 'Audit / event log archives (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Ops-Telemetrie — kein Procure-Mart-Kern.', 'en' => 'Ops telemetry — not procure mart core.'],
            ],
            [
                'name' => 'Unused custom fields (bulk sync all)',
                'category' => 'system',
                'reason' => ['de' => 'Vergrößert DSDR-Fläche und Sync-Kosten.', 'en' => 'Expands DSDR surface and sync cost.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Attachment content',
                'reason' => ['de' => 'Kosten ohne KPI-Nutzen.', 'en' => 'Cost without KPI value.'],
            ],
            [
                'name' => 'Supplier / buyer email cleartext in marts',
                'reason' => ['de' => 'System IDs reichen für Spend-Joins.', 'en' => 'System ids are enough for spend joins.'],
            ],
            [
                'name' => 'Comment / Q&A cleartext (bulk)',
                'reason' => ['de' => 'PII — gezielt oder gar nicht.', 'en' => 'PII — selectively or not at all.'],
            ],
            [
                'name' => 'Sandbox / test realm documents',
                'reason' => ['de' => 'Prod-Procure-Marts sauber halten.', 'en' => 'Keep prod procure marts clean.'],
            ],
            [
                'name' => 'Unused flex / custom fields (bulk)',
                'reason' => ['de' => 'DSDR ohne Consumer.', 'en' => 'DSDR without consumers.'],
            ],
        ],
    ],

    'coupa' => [
        'pii' => [
            [
                'entity' => 'User',
                'fields' => ['id', 'login', 'email', 'firstname', 'lastname', 'employee-number'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: user id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: user id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Coupa User — email/name nur mit Legal-Basis; id als Key.', 'en' => 'Coupa user — email/name only with legal basis; id as key.'],
            ],
            [
                'entity' => 'Supplier / supplier contact',
                'fields' => ['supplier name', 'primary-contact.email', 'phone', 'remit-to address'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Supplier Contacts — email/phone taggen; supplier id als Business-Key.', 'en' => 'Supplier contacts — tag email/phone; supplier id as business key.'],
            ],
            [
                'entity' => 'Approval comments',
                'fields' => ['approval-comment', 'note', 'reason free text'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Approval Comments — ungeprüfte PII; Approval Status Metadata reicht oft.', 'en' => 'Approval comments — unchecked PII; approval status metadata often enough.'],
            ],
            [
                'entity' => 'Attachments / invoice images',
                'fields' => ['attachment file', 'invoice image', 'filename'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Anhänge und Invoice Images — teuer und potenziell PII.', 'en' => 'Attachments and invoice images — expensive and potentially PII.'],
            ],
            [
                'entity' => 'Requester / approver on transactional docs',
                'fields' => ['created-by', 'requested-by', 'approver', 'ship-to contact'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: user id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: user id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Requester/Approver als User-Id — keine Klartext-Namen in Default-Marts.', 'en' => 'Requester/approver as user id — no cleartext names in default marts.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'user id, email, supplier id, PO number, invoice id, requisition id, employee-number.', 'en' => 'user id, email, supplier id, PO number, invoice id, requisition id, employee-number.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'User, Supplier, Purchase Order, Invoice, Requisition, Approval + Contacts.', 'en' => 'User, supplier, purchase order, invoice, requisition, approval + contacts.'],
            ],
            [
                'focus' => ['de' => 'API / CSV / data dump exports', 'en' => 'API / CSV / data dump exports'],
                'notes' => ['de' => 'REST API und Scheduled Data Dumps verdoppeln User-/Supplier-PII in Stages.', 'en' => 'REST API and scheduled data dumps duplicate user/supplier PII into stages.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / test instance copies', 'en' => 'Sandbox / test instance copies'],
                'notes' => ['de' => 'Sandbox-Invoices nicht mit Prod-Spend-Marts mischen.', 'en' => 'Do not mix sandbox invoices with prod spend marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Attachment / invoice image binaries',
                'category' => 'system',
                'reason' => ['de' => 'Binaries — teuer und sensibel.', 'en' => 'Binaries — expensive and sensitive.'],
            ],
            [
                'name' => 'Approval comment bodies (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Freitext-PII — Status Metadata reicht oft.', 'en' => 'Free-text PII — status metadata often enough.'],
            ],
            [
                'name' => 'Audit trail / activity log (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Ops-Telemetrie — kein Spend-Mart-Kern.', 'en' => 'Ops telemetry — not spend mart core.'],
            ],
            [
                'name' => 'Unused custom fields (bulk sync all)',
                'category' => 'system',
                'reason' => ['de' => 'Vergrößert DSDR-Fläche und Sync-Kosten.', 'en' => 'Expands DSDR surface and sync cost.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Attachment / invoice image content',
                'reason' => ['de' => 'Kosten ohne KPI-Nutzen.', 'en' => 'Cost without KPI value.'],
            ],
            [
                'name' => 'Approval comment cleartext (bulk)',
                'reason' => ['de' => 'PII — gezielt oder gar nicht.', 'en' => 'PII — selectively or not at all.'],
            ],
            [
                'name' => 'User / supplier email cleartext in marts',
                'reason' => ['de' => 'IDs reichen für Spend-KPIs.', 'en' => 'Ids are enough for spend KPIs.'],
            ],
            [
                'name' => 'Unused custom fields (bulk)',
                'reason' => ['de' => 'DSDR ohne Consumer.', 'en' => 'DSDR without consumers.'],
            ],
            [
                'name' => 'Sandbox / test instance documents',
                'reason' => ['de' => 'Prod-Spend-Marts sauber halten.', 'en' => 'Keep prod spend marts clean.'],
            ],
        ],
    ],
];
