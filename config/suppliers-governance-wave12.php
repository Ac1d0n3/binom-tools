<?php

/**
 * Wave 12 governance overlays — HR/ATS source-native PII, DSDR, skip guidance.
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'bamboohr' => [
        'pii' => [
            [
                'entity' => 'Employee master',
                'fields' => [
                    'id',
                    'work_email',
                    'personal_email',
                    'first_name',
                    'last_name',
                    'phone',
                    'address',
                ],
                'classification' => 'workforce',
                'stage' => [
                    'de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates',
                    'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates',
                ],
                'treatment' => [
                    'de' => 'Employee Master — Klartext nur mit Legal-Basis; id als Key.',
                    'en' => 'Employee master — cleartext only with legal basis; id as key.',
                ],
            ],
            [
                'entity' => 'National identifiers',
                'fields' => [
                    'national_id',
                    'tax_id',
                    'ssn',
                    'social_security',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: restriktiv/hash; Curated: hash; Mart: nie Klartext',
                    'en' => 'RAW: restrict/hash; Curated: hash; Mart: never cleartext',
                ],
                'treatment' => [
                    'de' => 'Nationale IDs hashen oder nicht laden.',
                    'en' => 'Hash national IDs or do not load.',
                ],
            ],
            [
                'entity' => 'Compensation',
                'fields' => [
                    'amount',
                    'bonus',
                    'equity',
                    'pay_rate',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: restricted; Curated: bands/flags; Mart: nie Klartext',
                    'en' => 'RAW: restricted; Curated: bands/flags; Mart: never cleartext',
                ],
                'treatment' => [
                    'de' => 'Gehalt nie in Public Marts.',
                    'en' => 'Never put salary in public marts.',
                ],
            ],
            [
                'entity' => 'Time off / sick',
                'fields' => [
                    'notes',
                    'medical_certificate',
                    'diagnosis',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: nie Notizen; Curated: type/days only; Mart: aggregates',
                    'en' => 'RAW: never notes; Curated: type/days only; Mart: aggregates',
                ],
                'treatment' => [
                    'de' => 'Keine Gesundheits-Freitexte.',
                    'en' => 'No health free text.',
                ],
            ],
            [
                'entity' => 'HR documents',
                'fields' => [
                    'contract_pdf',
                    'id_scan',
                    'attachment_body',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: nie Bodies; Curated: meta; Mart: meta',
                    'en' => 'RAW: never bodies; Curated: meta; Mart: meta',
                ],
                'treatment' => [
                    'de' => 'Nur Document Meta.',
                    'en' => 'Document meta only.',
                ],
            ],
        ],
        'dsdr' => [
            [
                'focus' => [
                    'de' => 'Match-Keys',
                    'en' => 'Match keys',
                ],
                'notes' => [
                    'de' => 'employee id, work_email hashed, external ids.',
                    'en' => 'employee id, work_email hashed, external ids.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'Primärobjekte',
                    'en' => 'Primary objects',
                ],
                'notes' => [
                    'de' => 'Employee, Employment, Time Off, Org, Compensation Meta.',
                    'en' => 'Employee, employment, time off, org, compensation meta.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'API / export copies',
                    'en' => 'API / export copies',
                ],
                'notes' => [
                    'de' => 'Exports und Webhook-Payloads verdoppeln PII — Allowlist.',
                    'en' => 'Exports and webhook payloads duplicate PII — allowlist.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'Sandbox / test tenants',
                    'en' => 'Sandbox / test tenants',
                ],
                'notes' => [
                    'de' => 'Sandbox nicht mit Prod-HR-Marts mischen.',
                    'en' => 'Do not mix sandbox with prod HR marts.',
                ],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Compensation amount cleartext in marts',
                'category' => 'security',
                'reason' => [
                    'de' => 'Gehalt/Bonus Klartext — nur Meta/Bands oder restricted RAW.',
                    'en' => 'Salary/bonus cleartext — meta/bands or restricted RAW only.',
                ],
            ],
            [
                'name' => 'National ID / SSN / tax id cleartext',
                'category' => 'security',
                'reason' => [
                    'de' => 'Nationale IDs hashen oder nicht laden.',
                    'en' => 'Hash national IDs or do not load.',
                ],
            ],
            [
                'name' => 'HR document / contract bodies',
                'category' => 'blob',
                'reason' => [
                    'de' => 'Vertrags-PDF/Bodies — nur Meta.',
                    'en' => 'Contract PDF/bodies — meta only.',
                ],
            ],
            [
                'name' => 'Medical / sick notes free text',
                'category' => 'system',
                'reason' => [
                    'de' => 'Gesundheitsdaten — nie in Analytics Default.',
                    'en' => 'Health data — never in default analytics.',
                ],
            ],
        ],
        'skip' => [
            [
                'name' => 'Compensation amount cleartext in marts',
                'reason' => [
                    'de' => 'Gehalt/Bonus Klartext — nur Meta/Bands oder restricted RAW.',
                    'en' => 'Salary/bonus cleartext — meta/bands or restricted RAW only.',
                ],
            ],
            [
                'name' => 'National ID / SSN / tax id cleartext',
                'reason' => [
                    'de' => 'Nationale IDs hashen oder nicht laden.',
                    'en' => 'Hash national IDs or do not load.',
                ],
            ],
            [
                'name' => 'HR document / contract bodies',
                'reason' => [
                    'de' => 'Vertrags-PDF/Bodies — nur Meta.',
                    'en' => 'Contract PDF/bodies — meta only.',
                ],
            ],
            [
                'name' => 'Medical / sick notes free text',
                'reason' => [
                    'de' => 'Gesundheitsdaten — nie in Analytics Default.',
                    'en' => 'Health data — never in default analytics.',
                ],
            ],
            [
                'name' => 'Sandbox employees/candidates in prod marts',
                'reason' => [
                    'de' => 'Prod-Marts sauber halten.',
                    'en' => 'Keep prod marts clean.',
                ],
            ],
        ],
    ],
    'hibob' => [
        'pii' => [
            [
                'entity' => 'Employee master',
                'fields' => [
                    'id',
                    'work_email',
                    'personal_email',
                    'first_name',
                    'last_name',
                    'phone',
                    'address',
                ],
                'classification' => 'workforce',
                'stage' => [
                    'de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates',
                    'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates',
                ],
                'treatment' => [
                    'de' => 'Employee Master — Klartext nur mit Legal-Basis; id als Key.',
                    'en' => 'Employee master — cleartext only with legal basis; id as key.',
                ],
            ],
            [
                'entity' => 'National identifiers',
                'fields' => [
                    'national_id',
                    'tax_id',
                    'ssn',
                    'social_security',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: restriktiv/hash; Curated: hash; Mart: nie Klartext',
                    'en' => 'RAW: restrict/hash; Curated: hash; Mart: never cleartext',
                ],
                'treatment' => [
                    'de' => 'Nationale IDs hashen oder nicht laden.',
                    'en' => 'Hash national IDs or do not load.',
                ],
            ],
            [
                'entity' => 'Compensation',
                'fields' => [
                    'amount',
                    'bonus',
                    'equity',
                    'pay_rate',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: restricted; Curated: bands/flags; Mart: nie Klartext',
                    'en' => 'RAW: restricted; Curated: bands/flags; Mart: never cleartext',
                ],
                'treatment' => [
                    'de' => 'Gehalt nie in Public Marts.',
                    'en' => 'Never put salary in public marts.',
                ],
            ],
            [
                'entity' => 'Time off / sick',
                'fields' => [
                    'notes',
                    'medical_certificate',
                    'diagnosis',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: nie Notizen; Curated: type/days only; Mart: aggregates',
                    'en' => 'RAW: never notes; Curated: type/days only; Mart: aggregates',
                ],
                'treatment' => [
                    'de' => 'Keine Gesundheits-Freitexte.',
                    'en' => 'No health free text.',
                ],
            ],
            [
                'entity' => 'HR documents',
                'fields' => [
                    'contract_pdf',
                    'id_scan',
                    'attachment_body',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: nie Bodies; Curated: meta; Mart: meta',
                    'en' => 'RAW: never bodies; Curated: meta; Mart: meta',
                ],
                'treatment' => [
                    'de' => 'Nur Document Meta.',
                    'en' => 'Document meta only.',
                ],
            ],
        ],
        'dsdr' => [
            [
                'focus' => [
                    'de' => 'Match-Keys',
                    'en' => 'Match keys',
                ],
                'notes' => [
                    'de' => 'employee id, work_email hashed, external ids.',
                    'en' => 'employee id, work_email hashed, external ids.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'Primärobjekte',
                    'en' => 'Primary objects',
                ],
                'notes' => [
                    'de' => 'Employee, Employment, Time Off, Org, Compensation Meta.',
                    'en' => 'Employee, employment, time off, org, compensation meta.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'API / export copies',
                    'en' => 'API / export copies',
                ],
                'notes' => [
                    'de' => 'Exports und Webhook-Payloads verdoppeln PII — Allowlist.',
                    'en' => 'Exports and webhook payloads duplicate PII — allowlist.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'Sandbox / test tenants',
                    'en' => 'Sandbox / test tenants',
                ],
                'notes' => [
                    'de' => 'Sandbox nicht mit Prod-HR-Marts mischen.',
                    'en' => 'Do not mix sandbox with prod HR marts.',
                ],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Compensation amount cleartext in marts',
                'category' => 'security',
                'reason' => [
                    'de' => 'Gehalt/Bonus Klartext — nur Meta/Bands oder restricted RAW.',
                    'en' => 'Salary/bonus cleartext — meta/bands or restricted RAW only.',
                ],
            ],
            [
                'name' => 'National ID / SSN / tax id cleartext',
                'category' => 'security',
                'reason' => [
                    'de' => 'Nationale IDs hashen oder nicht laden.',
                    'en' => 'Hash national IDs or do not load.',
                ],
            ],
            [
                'name' => 'HR document / contract bodies',
                'category' => 'blob',
                'reason' => [
                    'de' => 'Vertrags-PDF/Bodies — nur Meta.',
                    'en' => 'Contract PDF/bodies — meta only.',
                ],
            ],
            [
                'name' => 'Medical / sick notes free text',
                'category' => 'system',
                'reason' => [
                    'de' => 'Gesundheitsdaten — nie in Analytics Default.',
                    'en' => 'Health data — never in default analytics.',
                ],
            ],
        ],
        'skip' => [
            [
                'name' => 'Compensation amount cleartext in marts',
                'reason' => [
                    'de' => 'Gehalt/Bonus Klartext — nur Meta/Bands oder restricted RAW.',
                    'en' => 'Salary/bonus cleartext — meta/bands or restricted RAW only.',
                ],
            ],
            [
                'name' => 'National ID / SSN / tax id cleartext',
                'reason' => [
                    'de' => 'Nationale IDs hashen oder nicht laden.',
                    'en' => 'Hash national IDs or do not load.',
                ],
            ],
            [
                'name' => 'HR document / contract bodies',
                'reason' => [
                    'de' => 'Vertrags-PDF/Bodies — nur Meta.',
                    'en' => 'Contract PDF/bodies — meta only.',
                ],
            ],
            [
                'name' => 'Medical / sick notes free text',
                'reason' => [
                    'de' => 'Gesundheitsdaten — nie in Analytics Default.',
                    'en' => 'Health data — never in default analytics.',
                ],
            ],
            [
                'name' => 'Sandbox employees/candidates in prod marts',
                'reason' => [
                    'de' => 'Prod-Marts sauber halten.',
                    'en' => 'Keep prod marts clean.',
                ],
            ],
        ],
    ],
    'factorial' => [
        'pii' => [
            [
                'entity' => 'Employee master',
                'fields' => [
                    'id',
                    'work_email',
                    'personal_email',
                    'first_name',
                    'last_name',
                    'phone',
                    'address',
                ],
                'classification' => 'workforce',
                'stage' => [
                    'de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates',
                    'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates',
                ],
                'treatment' => [
                    'de' => 'Employee Master — Klartext nur mit Legal-Basis; id als Key.',
                    'en' => 'Employee master — cleartext only with legal basis; id as key.',
                ],
            ],
            [
                'entity' => 'National identifiers',
                'fields' => [
                    'national_id',
                    'tax_id',
                    'ssn',
                    'social_security',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: restriktiv/hash; Curated: hash; Mart: nie Klartext',
                    'en' => 'RAW: restrict/hash; Curated: hash; Mart: never cleartext',
                ],
                'treatment' => [
                    'de' => 'Nationale IDs hashen oder nicht laden.',
                    'en' => 'Hash national IDs or do not load.',
                ],
            ],
            [
                'entity' => 'Compensation',
                'fields' => [
                    'amount',
                    'bonus',
                    'equity',
                    'pay_rate',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: restricted; Curated: bands/flags; Mart: nie Klartext',
                    'en' => 'RAW: restricted; Curated: bands/flags; Mart: never cleartext',
                ],
                'treatment' => [
                    'de' => 'Gehalt nie in Public Marts.',
                    'en' => 'Never put salary in public marts.',
                ],
            ],
            [
                'entity' => 'Time off / sick',
                'fields' => [
                    'notes',
                    'medical_certificate',
                    'diagnosis',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: nie Notizen; Curated: type/days only; Mart: aggregates',
                    'en' => 'RAW: never notes; Curated: type/days only; Mart: aggregates',
                ],
                'treatment' => [
                    'de' => 'Keine Gesundheits-Freitexte.',
                    'en' => 'No health free text.',
                ],
            ],
            [
                'entity' => 'HR documents',
                'fields' => [
                    'contract_pdf',
                    'id_scan',
                    'attachment_body',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: nie Bodies; Curated: meta; Mart: meta',
                    'en' => 'RAW: never bodies; Curated: meta; Mart: meta',
                ],
                'treatment' => [
                    'de' => 'Nur Document Meta.',
                    'en' => 'Document meta only.',
                ],
            ],
        ],
        'dsdr' => [
            [
                'focus' => [
                    'de' => 'Match-Keys',
                    'en' => 'Match keys',
                ],
                'notes' => [
                    'de' => 'employee id, work_email hashed, external ids.',
                    'en' => 'employee id, work_email hashed, external ids.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'Primärobjekte',
                    'en' => 'Primary objects',
                ],
                'notes' => [
                    'de' => 'Employee, Employment, Time Off, Org, Compensation Meta.',
                    'en' => 'Employee, employment, time off, org, compensation meta.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'API / export copies',
                    'en' => 'API / export copies',
                ],
                'notes' => [
                    'de' => 'Exports und Webhook-Payloads verdoppeln PII — Allowlist.',
                    'en' => 'Exports and webhook payloads duplicate PII — allowlist.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'Sandbox / test tenants',
                    'en' => 'Sandbox / test tenants',
                ],
                'notes' => [
                    'de' => 'Sandbox nicht mit Prod-HR-Marts mischen.',
                    'en' => 'Do not mix sandbox with prod HR marts.',
                ],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Compensation amount cleartext in marts',
                'category' => 'security',
                'reason' => [
                    'de' => 'Gehalt/Bonus Klartext — nur Meta/Bands oder restricted RAW.',
                    'en' => 'Salary/bonus cleartext — meta/bands or restricted RAW only.',
                ],
            ],
            [
                'name' => 'National ID / SSN / tax id cleartext',
                'category' => 'security',
                'reason' => [
                    'de' => 'Nationale IDs hashen oder nicht laden.',
                    'en' => 'Hash national IDs or do not load.',
                ],
            ],
            [
                'name' => 'HR document / contract bodies',
                'category' => 'blob',
                'reason' => [
                    'de' => 'Vertrags-PDF/Bodies — nur Meta.',
                    'en' => 'Contract PDF/bodies — meta only.',
                ],
            ],
            [
                'name' => 'Medical / sick notes free text',
                'category' => 'system',
                'reason' => [
                    'de' => 'Gesundheitsdaten — nie in Analytics Default.',
                    'en' => 'Health data — never in default analytics.',
                ],
            ],
        ],
        'skip' => [
            [
                'name' => 'Compensation amount cleartext in marts',
                'reason' => [
                    'de' => 'Gehalt/Bonus Klartext — nur Meta/Bands oder restricted RAW.',
                    'en' => 'Salary/bonus cleartext — meta/bands or restricted RAW only.',
                ],
            ],
            [
                'name' => 'National ID / SSN / tax id cleartext',
                'reason' => [
                    'de' => 'Nationale IDs hashen oder nicht laden.',
                    'en' => 'Hash national IDs or do not load.',
                ],
            ],
            [
                'name' => 'HR document / contract bodies',
                'reason' => [
                    'de' => 'Vertrags-PDF/Bodies — nur Meta.',
                    'en' => 'Contract PDF/bodies — meta only.',
                ],
            ],
            [
                'name' => 'Medical / sick notes free text',
                'reason' => [
                    'de' => 'Gesundheitsdaten — nie in Analytics Default.',
                    'en' => 'Health data — never in default analytics.',
                ],
            ],
            [
                'name' => 'Sandbox employees/candidates in prod marts',
                'reason' => [
                    'de' => 'Prod-Marts sauber halten.',
                    'en' => 'Keep prod marts clean.',
                ],
            ],
        ],
    ],
    'greenhouse' => [
        'pii' => [
            [
                'entity' => 'Candidate',
                'fields' => [
                    'id',
                    'email',
                    'first_name',
                    'last_name',
                    'phone',
                    'address',
                ],
                'classification' => 'direct',
                'stage' => [
                    'de' => 'RAW: restriktiv; Curated: id only; Mart: aggregates',
                    'en' => 'RAW: restrict; Curated: id only; Mart: aggregates',
                ],
                'treatment' => [
                    'de' => 'Bewerber-PII — candidate id als Join.',
                    'en' => 'Applicant PII — candidate id as join.',
                ],
            ],
            [
                'entity' => 'Resume / CV',
                'fields' => [
                    'resume_file',
                    'cv_text',
                    'attachments',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: nie Bodies; Curated: parse flags; Mart: nie',
                    'en' => 'RAW: never bodies; Curated: parse flags; Mart: never',
                ],
                'treatment' => [
                    'de' => 'CV-Binaries/OCR skippen.',
                    'en' => 'Skip CV binaries/OCR.',
                ],
            ],
            [
                'entity' => 'Interview scorecards',
                'fields' => [
                    'notes',
                    'free_text_feedback',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: optional restrict; Curated: scores only; Mart: aggregates',
                    'en' => 'RAW: optional restrict; Curated: scores only; Mart: aggregates',
                ],
                'treatment' => [
                    'de' => 'Freitext-Feedback Default skip.',
                    'en' => 'Skip free-text feedback by default.',
                ],
            ],
            [
                'entity' => 'Offer compensation',
                'fields' => [
                    'salary',
                    'equity',
                    'bonus',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: restricted; Curated: status/dates; Mart: keine Beträge',
                    'en' => 'RAW: restricted; Curated: status/dates; Mart: no amounts',
                ],
                'treatment' => [
                    'de' => 'Offer Amounts nicht in Public Marts.',
                    'en' => 'Offer amounts not in public marts.',
                ],
            ],
            [
                'entity' => 'ID / passport scans',
                'fields' => [
                    'passport',
                    'national_id_scan',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: nie; Curated: nie; Mart: nie',
                    'en' => 'RAW: never; Curated: never; Mart: never',
                ],
                'treatment' => [
                    'de' => 'ID-Scans nie speichern.',
                    'en' => 'Never store ID scans.',
                ],
            ],
        ],
        'dsdr' => [
            [
                'focus' => [
                    'de' => 'Match-Keys',
                    'en' => 'Match keys',
                ],
                'notes' => [
                    'de' => 'candidate id, application id, job id, email hashed.',
                    'en' => 'candidate id, application id, job id, email hashed.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'Primärobjekte',
                    'en' => 'Primary objects',
                ],
                'notes' => [
                    'de' => 'Candidate, Application, Job, Stage, Offer Meta.',
                    'en' => 'Candidate, application, job, stage, offer meta.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'API / export copies',
                    'en' => 'API / export copies',
                ],
                'notes' => [
                    'de' => 'Exports und Webhook-Payloads verdoppeln PII — Allowlist.',
                    'en' => 'Exports and webhook payloads duplicate PII — allowlist.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'Sandbox / test tenants',
                    'en' => 'Sandbox / test tenants',
                ],
                'notes' => [
                    'de' => 'Sandbox nicht mit Prod-HR-Marts mischen.',
                    'en' => 'Do not mix sandbox with prod HR marts.',
                ],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'CV / resume file bodies',
                'category' => 'blob',
                'reason' => [
                    'de' => 'Lebenslauf-Binaries — nur Meta/Parse-Flags.',
                    'en' => 'Resume binaries — meta/parse flags only.',
                ],
            ],
            [
                'name' => 'Scorecard free-text notes',
                'category' => 'system',
                'reason' => [
                    'de' => 'Interview-Freitext oft sensibel — Default skip.',
                    'en' => 'Interview free text often sensitive — skip by default.',
                ],
            ],
            [
                'name' => 'Offer salary cleartext in marts',
                'category' => 'security',
                'reason' => [
                    'de' => 'Offer-Beträge restriktiv; nicht in Public Marts.',
                    'en' => 'Offer amounts restricted; not in public marts.',
                ],
            ],
            [
                'name' => 'Candidate national id / passport scans',
                'category' => 'security',
                'reason' => [
                    'de' => 'ID-Scans nie laden.',
                    'en' => 'Never load ID scans.',
                ],
            ],
        ],
        'skip' => [
            [
                'name' => 'CV / resume file bodies',
                'reason' => [
                    'de' => 'Lebenslauf-Binaries — nur Meta/Parse-Flags.',
                    'en' => 'Resume binaries — meta/parse flags only.',
                ],
            ],
            [
                'name' => 'Scorecard free-text notes',
                'reason' => [
                    'de' => 'Interview-Freitext oft sensibel — Default skip.',
                    'en' => 'Interview free text often sensitive — skip by default.',
                ],
            ],
            [
                'name' => 'Offer salary cleartext in marts',
                'reason' => [
                    'de' => 'Offer-Beträge restriktiv; nicht in Public Marts.',
                    'en' => 'Offer amounts restricted; not in public marts.',
                ],
            ],
            [
                'name' => 'Candidate national id / passport scans',
                'reason' => [
                    'de' => 'ID-Scans nie laden.',
                    'en' => 'Never load ID scans.',
                ],
            ],
            [
                'name' => 'Sandbox employees/candidates in prod marts',
                'reason' => [
                    'de' => 'Prod-Marts sauber halten.',
                    'en' => 'Keep prod marts clean.',
                ],
            ],
        ],
    ],
    'softgarden' => [
        'pii' => [
            [
                'entity' => 'Candidate',
                'fields' => [
                    'id',
                    'email',
                    'first_name',
                    'last_name',
                    'phone',
                    'address',
                ],
                'classification' => 'direct',
                'stage' => [
                    'de' => 'RAW: restriktiv; Curated: id only; Mart: aggregates',
                    'en' => 'RAW: restrict; Curated: id only; Mart: aggregates',
                ],
                'treatment' => [
                    'de' => 'Bewerber-PII — candidate id als Join.',
                    'en' => 'Applicant PII — candidate id as join.',
                ],
            ],
            [
                'entity' => 'Resume / CV',
                'fields' => [
                    'resume_file',
                    'cv_text',
                    'attachments',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: nie Bodies; Curated: parse flags; Mart: nie',
                    'en' => 'RAW: never bodies; Curated: parse flags; Mart: never',
                ],
                'treatment' => [
                    'de' => 'CV-Binaries/OCR skippen.',
                    'en' => 'Skip CV binaries/OCR.',
                ],
            ],
            [
                'entity' => 'Interview scorecards',
                'fields' => [
                    'notes',
                    'free_text_feedback',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: optional restrict; Curated: scores only; Mart: aggregates',
                    'en' => 'RAW: optional restrict; Curated: scores only; Mart: aggregates',
                ],
                'treatment' => [
                    'de' => 'Freitext-Feedback Default skip.',
                    'en' => 'Skip free-text feedback by default.',
                ],
            ],
            [
                'entity' => 'Offer compensation',
                'fields' => [
                    'salary',
                    'equity',
                    'bonus',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: restricted; Curated: status/dates; Mart: keine Beträge',
                    'en' => 'RAW: restricted; Curated: status/dates; Mart: no amounts',
                ],
                'treatment' => [
                    'de' => 'Offer Amounts nicht in Public Marts.',
                    'en' => 'Offer amounts not in public marts.',
                ],
            ],
            [
                'entity' => 'ID / passport scans',
                'fields' => [
                    'passport',
                    'national_id_scan',
                ],
                'classification' => 'sensitive',
                'stage' => [
                    'de' => 'RAW: nie; Curated: nie; Mart: nie',
                    'en' => 'RAW: never; Curated: never; Mart: never',
                ],
                'treatment' => [
                    'de' => 'ID-Scans nie speichern.',
                    'en' => 'Never store ID scans.',
                ],
            ],
        ],
        'dsdr' => [
            [
                'focus' => [
                    'de' => 'Match-Keys',
                    'en' => 'Match keys',
                ],
                'notes' => [
                    'de' => 'candidate id, application id, job id, email hashed.',
                    'en' => 'candidate id, application id, job id, email hashed.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'Primärobjekte',
                    'en' => 'Primary objects',
                ],
                'notes' => [
                    'de' => 'Candidate, Application, Job, Stage, Offer Meta.',
                    'en' => 'Candidate, application, job, stage, offer meta.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'API / export copies',
                    'en' => 'API / export copies',
                ],
                'notes' => [
                    'de' => 'Exports und Webhook-Payloads verdoppeln PII — Allowlist.',
                    'en' => 'Exports and webhook payloads duplicate PII — allowlist.',
                ],
            ],
            [
                'focus' => [
                    'de' => 'Sandbox / test tenants',
                    'en' => 'Sandbox / test tenants',
                ],
                'notes' => [
                    'de' => 'Sandbox nicht mit Prod-HR-Marts mischen.',
                    'en' => 'Do not mix sandbox with prod HR marts.',
                ],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'CV / resume file bodies',
                'category' => 'blob',
                'reason' => [
                    'de' => 'Lebenslauf-Binaries — nur Meta/Parse-Flags.',
                    'en' => 'Resume binaries — meta/parse flags only.',
                ],
            ],
            [
                'name' => 'Scorecard free-text notes',
                'category' => 'system',
                'reason' => [
                    'de' => 'Interview-Freitext oft sensibel — Default skip.',
                    'en' => 'Interview free text often sensitive — skip by default.',
                ],
            ],
            [
                'name' => 'Offer salary cleartext in marts',
                'category' => 'security',
                'reason' => [
                    'de' => 'Offer-Beträge restriktiv; nicht in Public Marts.',
                    'en' => 'Offer amounts restricted; not in public marts.',
                ],
            ],
            [
                'name' => 'Candidate national id / passport scans',
                'category' => 'security',
                'reason' => [
                    'de' => 'ID-Scans nie laden.',
                    'en' => 'Never load ID scans.',
                ],
            ],
        ],
        'skip' => [
            [
                'name' => 'CV / resume file bodies',
                'reason' => [
                    'de' => 'Lebenslauf-Binaries — nur Meta/Parse-Flags.',
                    'en' => 'Resume binaries — meta/parse flags only.',
                ],
            ],
            [
                'name' => 'Scorecard free-text notes',
                'reason' => [
                    'de' => 'Interview-Freitext oft sensibel — Default skip.',
                    'en' => 'Interview free text often sensitive — skip by default.',
                ],
            ],
            [
                'name' => 'Offer salary cleartext in marts',
                'reason' => [
                    'de' => 'Offer-Beträge restriktiv; nicht in Public Marts.',
                    'en' => 'Offer amounts restricted; not in public marts.',
                ],
            ],
            [
                'name' => 'Candidate national id / passport scans',
                'reason' => [
                    'de' => 'ID-Scans nie laden.',
                    'en' => 'Never load ID scans.',
                ],
            ],
            [
                'name' => 'Sandbox employees/candidates in prod marts',
                'reason' => [
                    'de' => 'Prod-Marts sauber halten.',
                    'en' => 'Keep prod marts clean.',
                ],
            ],
        ],
    ],
];
