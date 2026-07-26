<?php

/**
 * Wave 11 governance overlays — Service/BPM/Healthcare source-native PII, DSDR, skip guidance.
 * Merged onto catalog products by id (freshdesk, pega, camunda, epic).
 *
 * Epic (healthcare) requires strict PHI skip/PII policy — metadata aggregates only,
 * no clinical notes/bodies in any stage.
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'freshdesk' => [
        'pii' => [
            [
                'entity' => 'Contact / requester',
                'fields' => ['id', 'email', 'phone', 'name', 'twitter_id'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Contact E-Mail/Telefon/Name — direkte Kunden-PII; contact id als Key behalten.', 'en' => 'Contact email/phone/name — direct customer PII; keep contact id as key.'],
            ],
            [
                'entity' => 'Agent / workforce identity',
                'fields' => ['id', 'email', 'name', 'mobile'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Agent-E-Mail/Name — Workforce-PII; agent id als Key.', 'en' => 'Agent email/name — workforce PII; agent id as key.'],
            ],
            [
                'entity' => 'Conversation / note body',
                'fields' => ['body', 'body_text', 'private note content'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Freitext kann Kunden-PII enthalten — Meta reicht für Response-KPIs.', 'en' => 'Free text can contain customer PII — metadata is enough for response KPIs.'],
            ],
            [
                'entity' => 'Attachments / call recordings',
                'fields' => ['attachments', 'call recording url', 'call transcript'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Attachments/Recordings nie ins Warehouse — Presence/Count höchstens.', 'en' => 'Never land attachments/recordings in the warehouse — presence/count at most.'],
            ],
            [
                'entity' => 'Satisfaction survey feedback',
                'fields' => ['feedback', 'comment'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop; Mart: never'],
                'treatment' => ['de' => 'CSAT-Freitext-Feedback — Sentiment/PII-Risiko; numerischer Score reicht.', 'en' => 'CSAT free-text feedback — sentiment/PII risk; the numeric score is enough.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'contact id, email (hashed), ticket id, company id, agent id.', 'en' => 'contact id, email (hashed), ticket id, company id, agent id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Contact, Company, Ticket, Agent, SLA Policy, Time Entry.', 'en' => 'Contact, company, ticket, agent, SLA policy, time entry.'],
            ],
            [
                'focus' => ['de' => 'REST API / helpdesk export copies', 'en' => 'REST API / helpdesk export copies'],
                'notes' => ['de' => 'API-Bulk-Exports und Reporting-Feeds verdoppeln Contact-/Conversation-PII.', 'en' => 'API bulk exports and reporting feeds duplicate contact/conversation PII.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / trial account copies', 'en' => 'Sandbox / trial account copies'],
                'notes' => ['de' => 'Sandbox-/Trial-Accounts nicht mit Prod-Support-Marts mischen.', 'en' => 'Do not mix sandbox/trial accounts with prod support marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Conversation / note body text (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Freitext-PII-Risiko — Meta/Counts reichen.', 'en' => 'Free-text PII risk — meta/counts suffice.'],
            ],
            [
                'name' => 'Ticket/conversation attachment files',
                'category' => 'system',
                'reason' => ['de' => 'Attachment-Binaries — nie landen.', 'en' => 'Attachment binaries — never land.'],
            ],
            [
                'name' => 'Call recording audio / transcripts',
                'category' => 'system',
                'reason' => ['de' => 'Hochsensible PII — nie ins Warehouse.', 'en' => 'Highly sensitive PII — never into the warehouse.'],
            ],
            [
                'name' => 'CSAT free-text feedback',
                'category' => 'system',
                'reason' => ['de' => 'Sentiment/PII-Risiko — Score reicht.', 'en' => 'Sentiment/PII risk — score suffices.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Conversation/note body cleartext',
                'reason' => ['de' => 'PII-Risiko — Meta only.', 'en' => 'PII risk — meta only.'],
            ],
            [
                'name' => 'Ticket/conversation attachments',
                'reason' => ['de' => 'Kein Default-Load von Binaries.', 'en' => 'No default load of binaries.'],
            ],
            [
                'name' => 'Call recordings / transcripts',
                'reason' => ['de' => 'Hochsensible PII — skip.', 'en' => 'Highly sensitive PII — skip.'],
            ],
            [
                'name' => 'CSAT free-text comments',
                'reason' => ['de' => 'Score-Meta reicht.', 'en' => 'Score meta suffices.'],
            ],
            [
                'name' => 'Sandbox/trial data in prod marts',
                'reason' => ['de' => 'Prod-Support-Marts sauber halten.', 'en' => 'Keep prod support marts clean.'],
            ],
        ],
    ],

    'pega' => [
        'pii' => [
            [
                'entity' => 'Operator / workforce identity',
                'fields' => ['pyUserIdentifier', 'pyEmailAddress', 'pyUserName', 'pyOrgUnit'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Operator E-Mail/Name — Workforce-PII; pyUserIdentifier als Key.', 'en' => 'Operator email/name — workforce PII; pyUserIdentifier as key.'],
            ],
            [
                'entity' => 'Work party / customer contact',
                'fields' => ['work party email', 'work party phone', 'work party address'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Work-Party-Kontaktdaten sind oft Kunden-PII — restriktiver Zugriff.', 'en' => 'Work party contact data is often customer PII — restrict access.'],
            ],
            [
                'entity' => 'Case attachments / documents',
                'fields' => ['attachment content', 'document scans', 'correspondence body'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Attachment/Document-Content nie ins Warehouse — nur Meta.', 'en' => 'Never land attachment/document content in the warehouse — meta only.'],
            ],
            [
                'entity' => 'Correspondence records',
                'fields' => ['correspondence body', 'recipient email'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Correspondence-Bodies können PII enthalten — Send-Meta reicht.', 'en' => 'Correspondence bodies can contain PII — send meta suffices.'],
            ],
            [
                'entity' => 'Case free-text label / description',
                'fields' => ['pyLabel', 'pyDescription'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: prüfen/redigieren; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: review/redact; Mart: aggregates only'],
                'treatment' => ['de' => 'Case-Label/-Description können Kundendaten enthalten — vor breitem Zugriff prüfen.', 'en' => 'Case label/description can contain customer data — review before broad access.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'case pyID/pzInsKey, operator pyUserIdentifier, assignment ID, work party id.', 'en' => 'case pyID/pzInsKey, operator pyUserIdentifier, assignment ID, work party id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Case, Case Type, Assignment, Operator, Stage, SLA Event.', 'en' => 'Case, case type, assignment, operator, stage, SLA event.'],
            ],
            [
                'focus' => ['de' => 'Case API / reporting warehouse copies', 'en' => 'Case API / reporting warehouse copies'],
                'notes' => ['de' => 'Pega Reporting Warehouse und Case API Exports verdoppeln Work-Party-PII.', 'en' => 'Pega reporting warehouse and Case API exports duplicate work party PII.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / staging environment copies', 'en' => 'Sandbox / staging environment copies'],
                'notes' => ['de' => 'Staging-Environments nicht mit Prod-Case-Marts mischen.', 'en' => 'Do not mix staging environments with prod case marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Case attachment content / document scans',
                'category' => 'system',
                'reason' => ['de' => 'Attachment-Binaries — nie landen; nur Meta.', 'en' => 'Attachment binaries — never land; meta only.'],
            ],
            [
                'name' => 'Correspondence / email body content',
                'category' => 'pii',
                'reason' => ['de' => 'Correspondence-Bodies — PII-Risiko; Send-Count reicht.', 'en' => 'Correspondence bodies — PII risk; send count suffices.'],
            ],
            [
                'name' => 'Work party free-text notes',
                'category' => 'pii',
                'reason' => ['de' => 'Freitext-Notizen — PII-Risiko, nicht Analytics-relevant.', 'en' => 'Free-text notes — PII risk, not analytics relevant.'],
            ],
            [
                'name' => 'Full pxAuditHistory payload bulk',
                'category' => 'system',
                'reason' => ['de' => 'Ultra-verbose Audit-History — auf relevante Events reduzieren.', 'en' => 'Ultra-verbose audit history — reduce to relevant events.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Case attachment/document content',
                'reason' => ['de' => 'Nie ins Warehouse — Meta reicht.', 'en' => 'Never into the warehouse — meta is enough.'],
            ],
            [
                'name' => 'Correspondence body cleartext',
                'reason' => ['de' => 'PII-Risiko — Send-Count reicht.', 'en' => 'PII risk — send count suffices.'],
            ],
            [
                'name' => 'Work party contact cleartext (bulk)',
                'reason' => ['de' => 'Kunden-PII — hashen/tokenisieren.', 'en' => 'Customer PII — hash/tokenize.'],
            ],
            [
                'name' => 'Full audit history payload bulk',
                'reason' => ['de' => 'Volumen — auf relevante Events reduzieren.', 'en' => 'Volume — reduce to relevant events.'],
            ],
            [
                'name' => 'Staging environment cases in prod marts',
                'reason' => ['de' => 'Prod-Case-Marts sauber halten.', 'en' => 'Keep prod case marts clean.'],
            ],
        ],
    ],

    'camunda' => [
        'pii' => [
            [
                'entity' => 'Task assignee / workforce identity',
                'fields' => ['assignee', 'owner', 'candidate groups'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Assignee/Owner — Workforce-PII; user id als Key.', 'en' => 'Assignee/owner — workforce PII; user id as key.'],
            ],
            [
                'entity' => 'Process variable values',
                'fields' => ['variable.value (string/json)', 'variable.value (customer data)'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Variable-Values sind Geschäftsdaten und können PII enthalten — Type/Name-Meta reicht.', 'en' => 'Variable values are business data and can contain PII — type/name meta suffices.'],
            ],
            [
                'entity' => 'Business key / correlation identifiers',
                'fields' => ['businessKey', 'correlationKey'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen falls Kunden-ID; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: hash if a customer id; Mart: aggregates only'],
                'treatment' => ['de' => 'businessKey kann Kunden-/Order-IDs enthalten — Herkunft je Prozess prüfen.', 'en' => 'businessKey may contain customer/order ids — review origin per process.'],
            ],
            [
                'entity' => 'Byte-array / file process variables',
                'fields' => ['file variables', 'byte array variables'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'File-/Byte-Array-Variablen nie ins Warehouse — Presence/Count höchstens.', 'en' => 'Never land file/byte-array variables in the warehouse — presence/count at most.'],
            ],
            [
                'entity' => 'Incident error message / stack trace',
                'fields' => ['incident.incidentMessage', 'incident.stackTrace'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: Type-Meta only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: type meta only; Mart: aggregates only'],
                'treatment' => ['de' => 'Stack Traces können interne System-/Konfigurationsdetails leaken — restriktiv behandeln.', 'en' => 'Stack traces can leak internal system/configuration detail — treat restrictively.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'process instance id, process definition key+version, task id, incident id, businessKey.', 'en' => 'process instance id, process definition key+version, task id, incident id, businessKey.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Process Definition, Process Instance, User Task, Incident, External Task.', 'en' => 'Process definition, process instance, user task, incident, external task.'],
            ],
            [
                'focus' => ['de' => 'History service / Optimize export copies', 'en' => 'History service / Optimize export copies'],
                'notes' => ['de' => 'Camunda History API und Optimize Exports verdoppeln Variable-/Assignee-PII.', 'en' => 'Camunda History API and Optimize exports duplicate variable/assignee PII.'],
            ],
            [
                'focus' => ['de' => 'Multi-tenant / staging engine copies', 'en' => 'Multi-tenant / staging engine copies'],
                'notes' => ['de' => 'Staging-Engines und Fremd-Tenants nicht mit Prod-Process-Marts mischen.', 'en' => 'Do not mix staging engines and foreign tenants with prod process marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Process variable business payloads (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Kann PII/Secrets enthalten — Type/Name-Meta reicht.', 'en' => 'May contain PII/secrets — type/name meta suffices.'],
            ],
            [
                'name' => 'Byte-array / file process variables',
                'category' => 'system',
                'reason' => ['de' => 'Binaries — nie landen.', 'en' => 'Binaries — never land.'],
            ],
            [
                'name' => 'Incident full stack traces',
                'category' => 'system',
                'reason' => ['de' => 'Interne System-Details — Type/Message-Meta reicht.', 'en' => 'Internal system detail — type/message meta suffices.'],
            ],
            [
                'name' => 'Decision (DMN) input/output full payloads',
                'category' => 'system',
                'reason' => ['de' => 'Kann Geschäftsdaten enthalten — Outcome-Meta reicht.', 'en' => 'May contain business data — outcome meta suffices.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Process variable business payloads (bulk)',
                'reason' => ['de' => 'PII-Risiko — Meta only.', 'en' => 'PII risk — meta only.'],
            ],
            [
                'name' => 'Byte-array/file variables',
                'reason' => ['de' => 'Kein Default-Load von Binaries.', 'en' => 'No default load of binaries.'],
            ],
            [
                'name' => 'Incident full stack traces',
                'reason' => ['de' => 'Debugging-Detail — nicht relevant.', 'en' => 'Debugging detail — not relevant.'],
            ],
            [
                'name' => 'DMN input/output full payloads',
                'reason' => ['de' => 'Geschäftsdaten-Risiko — Outcome-Meta reicht.', 'en' => 'Business-data risk — outcome meta suffices.'],
            ],
            [
                'name' => 'Staging engine data in prod marts',
                'reason' => ['de' => 'Prod-Process-Marts sauber halten.', 'en' => 'Keep prod process marts clean.'],
            ],
        ],
    ],

    'epic' => [
        'pii' => [
            [
                'entity' => 'Patient demographics',
                'fields' => ['mrn', 'birthDate', 'name', 'address', 'ssn', 'phone'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: strikt einschränken (PHI); Curated: tokenisiert only; Mart: aggregates only', 'en' => 'RAW: strictly restrict (PHI); Curated: tokenized only; Mart: aggregates only'],
                'treatment' => ['de' => 'PHI strict — MRN/DOB/Name/Address/SSN nie in Analytics-Marts; nur tokenisierte id.', 'en' => 'PHI strict — never land MRN/DOB/name/address/SSN in analytics marts; tokenized id only.'],
            ],
            [
                'entity' => 'Clinical notes / progress notes',
                'fields' => ['note text', 'progress note body', 'discharge summary text'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Klinische Notizen nie ins Analytics-Warehouse — höchste PHI-Sensitivität.', 'en' => 'Never land clinical notes in the analytics warehouse — highest PHI sensitivity.'],
            ],
            [
                'entity' => 'Diagnosis / procedure identifiers linked to patient',
                'fields' => ['icdCode + patientId', 'cptCode + patientId'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: strikt einschränken; Curated: k-anonyme Aggregate only; Mart: aggregates only', 'en' => 'RAW: strictly restrict; Curated: k-anonymous aggregates only; Mart: aggregates only'],
                'treatment' => ['de' => 'Code + Patient-Link ist Re-Identifikationsrisiko — nur aggregierte, k-anonyme Auswertungen.', 'en' => 'Code + patient link is a re-identification risk — only aggregated, k-anonymous analysis.'],
            ],
            [
                'entity' => 'Provider identity',
                'fields' => ['npi', 'name', 'email'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: id/NPI only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: id/NPI only; Mart: aggregates only'],
                'treatment' => ['de' => 'Provider-Name/E-Mail — Workforce-PII; NPI als stabiler, weniger sensibler Key nutzbar.', 'en' => 'Provider name/email — workforce PII; NPI usable as a stable, less sensitive key.'],
            ],
            [
                'entity' => 'Imaging / lab result content',
                'fields' => ['DICOM binaries', 'lab result narrative value'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Imaging/Lab-Content nie ins Warehouse — nur Order-/Result-Flag-Meta.', 'en' => 'Never land imaging/lab content in the warehouse — order/result-flag meta only.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'patient MRN (tokenisiert/hashed), FHIR patient id, encounter id, provider NPI.', 'en' => 'patient MRN (tokenized/hashed), FHIR patient id, encounter id, provider NPI.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Patient-Identifier (tokenisiert), Encounter, Appointment, Diagnosis/Procedure Codes, Provider.', 'en' => 'Patient identifiers (tokenized), encounter, appointment, diagnosis/procedure codes, provider.'],
            ],
            [
                'focus' => ['de' => 'HL7/FHIR interface engine copies', 'en' => 'HL7/FHIR interface engine copies'],
                'notes' => ['de' => 'Interface Engines (Rhapsody, Mirth etc.) verdoppeln PHI in Nachrichten-Queues — Retention kurz halten.', 'en' => 'Interface engines (Rhapsody, Mirth, etc.) duplicate PHI in message queues — keep retention short.'],
            ],
            [
                'focus' => ['de' => 'Reporting workbench / research extract copies', 'en' => 'Reporting workbench / research extract copies'],
                'notes' => ['de' => 'Epic Reporting Workbench Extracts und Research-Datasets brauchen eigene IRB/Governance-Freigabe.', 'en' => 'Epic Reporting Workbench extracts and research datasets require separate IRB/governance approval.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Clinical / progress notes bodies',
                'category' => 'phi',
                'reason' => ['de' => 'Strikter PHI-Skip — höchste Sensitivität, nie landen.', 'en' => 'Strict PHI skip — highest sensitivity, never land.'],
            ],
            [
                'name' => 'Imaging / DICOM binaries',
                'category' => 'phi',
                'reason' => ['de' => 'Bildgebende Binärdaten — nie im Warehouse.', 'en' => 'Imaging binary data — never in the warehouse.'],
            ],
            [
                'name' => 'Lab result narrative / free-text values',
                'category' => 'phi',
                'reason' => ['de' => 'Freitext-Befunde — nur Flag-Meta erlaubt.', 'en' => 'Free-text findings — only flag meta allowed.'],
            ],
            [
                'name' => 'Full HL7/FHIR message payload bulk',
                'category' => 'phi',
                'reason' => ['de' => 'Volle Nachrichten bündeln PHI — nur Allowlist-Felder extrahieren.', 'en' => 'Full messages bundle PHI — extract allowlisted fields only.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Clinical/progress notes bodies',
                'reason' => ['de' => 'Strikter PHI-Skip in jeder Stage.', 'en' => 'Strict PHI skip in every stage.'],
            ],
            [
                'name' => 'Direct patient identifiers in marts (MRN/DOB/name/SSN)',
                'reason' => ['de' => 'Nur tokenisierte id in Analytics-Stages.', 'en' => 'Only tokenized id in analytics stages.'],
            ],
            [
                'name' => 'Imaging / DICOM binaries',
                'reason' => ['de' => 'Nie im Warehouse.', 'en' => 'Never in the warehouse.'],
            ],
            [
                'name' => 'Lab result narrative / free text',
                'reason' => ['de' => 'PHI — nur Flag-Meta.', 'en' => 'PHI — flag meta only.'],
            ],
            [
                'name' => 'Diagnosis/procedure + patient-level export below k-anonymity threshold',
                'reason' => ['de' => 'Re-Identifikationsrisiko — nur aggregierte Kategorien mit Mindestfallzahl.', 'en' => 'Re-identification risk — only aggregated categories with a minimum case count.'],
            ],
        ],
    ],
];
