<?php

/**
 * Wave 12 supplier library entries — HR / ATS (full template depth).
 *
 * HRIS + recruiting: employee/candidate master, pipeline/org facts.
 * Strict workforce/applicant PII — no salary/CV/medical bodies in default marts.
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $hcmTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    $products = [
        [
            'id' => 'bamboohr',
            'domain' => 'hcm',
            'order' => 460,
            'label' => [
                'de' => 'BambooHR',
                'en' => 'BambooHR',
            ],
            'shortPurpose' => [
                'de' => 'HRIS: Employee/Employment, Abwesenheiten, Org — API-Load, strenges Workforce-PII; Gehalt nie Klartext in Marts.',
                'en' => 'HRIS: employee/employment, time off, org — API load, strict workforce PII; never cleartext salary in marts.',
            ],
            'entities' => [
                [
                    'id' => 'employee',
                    'label' => [
                        'de' => 'Employee',
                        'en' => 'Employee',
                    ],
                    'description' => [
                        'de' => 'BambooHR Employee — Master; E-Mail/Name = Workforce-PII.',
                        'en' => 'BambooHR employee — master; email/name = workforce PII.',
                    ],
                    'grain' => [
                        'de' => 'Ein Employee (id)',
                        'en' => 'One employee (id)',
                    ],
                    'role' => [
                        'de' => 'Dimension (PII)',
                        'en' => 'Dimension (PII)',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'employment',
                    'label' => [
                        'de' => 'Employment',
                        'en' => 'Employment',
                    ],
                    'description' => [
                        'de' => 'Anstellung / Job Info — Status, Start/End, Department.',
                        'en' => 'Employment / job info — status, start/end, department.',
                    ],
                    'grain' => [
                        'de' => 'Eine Employment-Zeile',
                        'en' => 'One employment row',
                    ],
                    'role' => [
                        'de' => 'Status-Fact',
                        'en' => 'Status fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'department',
                    'label' => [
                        'de' => 'Department',
                        'en' => 'Department',
                    ],
                    'description' => [
                        'de' => 'Org-Einheit / Team-Struktur.',
                        'en' => 'Org unit / team structure.',
                    ],
                    'grain' => [
                        'de' => 'Ein Department',
                        'en' => 'One department',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'location',
                    'label' => [
                        'de' => 'Location',
                        'en' => 'Location',
                    ],
                    'description' => [
                        'de' => 'Arbeitsort / Site.',
                        'en' => 'Work location / site.',
                    ],
                    'grain' => [
                        'de' => 'Eine Location',
                        'en' => 'One location',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'optional',
                ],
                [
                    'id' => 'time_off',
                    'label' => [
                        'de' => 'Time Off',
                        'en' => 'Time off',
                    ],
                    'description' => [
                        'de' => 'Abwesenheit — Typ, Status, Tage; keine medizinischen Notizen.',
                        'en' => 'Time off — type, status, days; no medical notes.',
                    ],
                    'grain' => [
                        'de' => 'Ein Time-off Request',
                        'en' => 'One time-off request',
                    ],
                    'role' => [
                        'de' => 'Absence-Fact',
                        'en' => 'Absence fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'compensation_meta',
                    'label' => [
                        'de' => 'Compensation Meta',
                        'en' => 'Compensation meta',
                    ],
                    'description' => [
                        'de' => 'Compensation Metadata — Band/Currency/Effective; Beträge nie Klartext in Marts.',
                        'en' => 'Compensation metadata — band/currency/effective; never cleartext amounts in marts.',
                    ],
                    'grain' => [
                        'de' => 'Eine Compensation Zeile (meta)',
                        'en' => 'One compensation row (meta)',
                    ],
                    'role' => [
                        'de' => 'Sensitive Fact',
                        'en' => 'Sensitive fact',
                    ],
                    'load' => 'optional',
                ],
                [
                    'id' => 'manager',
                    'label' => [
                        'de' => 'Manager Edge',
                        'en' => 'Manager edge',
                    ],
                    'description' => [
                        'de' => 'Manager-Hierarchie als Employee-IDs.',
                        'en' => 'Manager hierarchy as employee ids.',
                    ],
                    'grain' => [
                        'de' => 'Eine Manager-Zuordnung',
                        'en' => 'One manager assignment',
                    ],
                    'role' => [
                        'de' => 'Org-Fact',
                        'en' => 'Org fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'document_meta',
                    'label' => [
                        'de' => 'HR Document Meta',
                        'en' => 'HR document meta',
                    ],
                    'description' => [
                        'de' => 'Dokument-Metadaten — keine Vertrags-Bodies.',
                        'en' => 'Document metadata — no contract bodies.',
                    ],
                    'grain' => [
                        'de' => 'Ein HR Document (meta)',
                        'en' => 'One HR document (meta)',
                    ],
                    'role' => [
                        'de' => 'Meta-Fact',
                        'en' => 'Meta fact',
                    ],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                [
                    'entity' => 'Employee',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Employee Join-Key',
                        'en' => 'Employee join key',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'work_email',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Work E-Mail / Workforce-PII',
                        'en' => 'Work email / workforce PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'personal_email',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Private E-Mail / PII',
                        'en' => 'Personal email / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'first_name',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Vorname / PII',
                        'en' => 'First name / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'last_name',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Nachname / PII',
                        'en' => 'Last name / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'birth_date',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Geburtsdatum / PII',
                        'en' => 'Birth date / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'national_id',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Nationale ID — hashen/restriktieren',
                        'en' => 'National ID — hash/restrict',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Active / Inactive / Terminated',
                        'en' => 'Active / inactive / terminated',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'hire_date',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Eintrittsdatum',
                        'en' => 'Hire date',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'termination_date',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Austrittsdatum',
                        'en' => 'Termination date',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'employee_id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'job_title',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Job Title',
                        'en' => 'Job title',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'employment_type',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Full-time / Part-time / Contractor',
                        'en' => 'Full-time / part-time / contractor',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'fte',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'FTE-Faktor',
                        'en' => 'FTE factor',
                    ],
                ],
                [
                    'entity' => 'Department',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Department Join',
                        'en' => 'Department join',
                    ],
                ],
                [
                    'entity' => 'Department',
                    'name' => 'name',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Department Name',
                        'en' => 'Department name',
                    ],
                ],
                [
                    'entity' => 'Location',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Location Join',
                        'en' => 'Location join',
                    ],
                ],
                [
                    'entity' => 'Location',
                    'name' => 'country',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Land',
                        'en' => 'Country',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Time-off Request Key',
                        'en' => 'Time-off request key',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'employee_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'type',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Vacation / Sick / Other',
                        'en' => 'Vacation / sick / other',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Approved / Pending / Denied',
                        'en' => 'Approved / pending / denied',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'days',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Abwesenheitstage',
                        'en' => 'Absence days',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'start_date',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Start',
                        'en' => 'Start',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'employee_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'currency',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Currency',
                        'en' => 'Currency',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'effective_on',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Effective Date',
                        'en' => 'Effective date',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'amount',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Gehalt — nie Klartext in Default-Marts',
                        'en' => 'Salary — never cleartext in default marts',
                    ],
                ],
                [
                    'entity' => 'Manager',
                    'name' => 'employee_id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Employee',
                        'en' => 'Employee',
                    ],
                ],
                [
                    'entity' => 'Manager',
                    'name' => 'manager_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Manager Employee-ID',
                        'en' => 'Manager employee id',
                    ],
                ],
                [
                    'entity' => 'DocumentMeta',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Document Meta Key',
                        'en' => 'Document meta key',
                    ],
                ],
                [
                    'entity' => 'DocumentMeta',
                    'name' => 'employee_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'DocumentMeta',
                    'name' => 'document_type',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Contract / ID / Other (meta)',
                        'en' => 'Contract / ID / other (meta)',
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
            ],
            'dimensions' => [
                [
                    'id' => 'department',
                    'label' => [
                        'de' => 'Department',
                        'en' => 'Department',
                    ],
                    'grain' => [
                        'de' => 'department.id',
                        'en' => 'department.id',
                    ],
                    'notes' => [
                        'de' => 'Org-Rollups; Renames SCD2.',
                        'en' => 'Org rollups; renames SCD2.',
                    ],
                ],
                [
                    'id' => 'employment_status',
                    'label' => [
                        'de' => 'Employment Status',
                        'en' => 'Employment status',
                    ],
                    'grain' => [
                        'de' => 'employee.status',
                        'en' => 'employee.status',
                    ],
                    'notes' => [
                        'de' => 'Active vs Terminated klar trennen.',
                        'en' => 'Clearly separate active vs terminated.',
                    ],
                ],
                [
                    'id' => 'employment_type',
                    'label' => [
                        'de' => 'Employment Type',
                        'en' => 'Employment type',
                    ],
                    'grain' => [
                        'de' => 'employment.employment_type',
                        'en' => 'employment.employment_type',
                    ],
                    'notes' => [
                        'de' => 'Contractor in Headcount-Definition klären.',
                        'en' => 'Clarify contractor in headcount definition.',
                    ],
                ],
                [
                    'id' => 'location_country',
                    'label' => [
                        'de' => 'Country',
                        'en' => 'Country',
                    ],
                    'grain' => [
                        'de' => 'location.country',
                        'en' => 'location.country',
                    ],
                    'notes' => [
                        'de' => 'ISO Country bevorzugen.',
                        'en' => 'Prefer ISO country.',
                    ],
                ],
                [
                    'id' => 'time_off_type',
                    'label' => [
                        'de' => 'Time Off Type',
                        'en' => 'Time-off type',
                    ],
                    'grain' => [
                        'de' => 'time_off.type',
                        'en' => 'time_off.type',
                    ],
                    'notes' => [
                        'de' => 'Sick ohne Diagnose-/Notizfelder.',
                        'en' => 'Sick without diagnosis/note fields.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Employee',
                    'fields' => [
                        'work_email',
                        'personal_email',
                        'first_name',
                        'last_name',
                    ],
                    'treatment' => [
                        'de' => 'Workforce-PII taggen; employee id als Join.',
                        'en' => 'Tag workforce PII; prefer employee id join.',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'fields' => [
                        'birth_date',
                        'national_id',
                    ],
                    'treatment' => [
                        'de' => 'Hashen/restriktieren; nationale IDs nicht in Marts.',
                        'en' => 'Hash/restrict; no national IDs in marts.',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'fields' => [
                        'amount',
                    ],
                    'treatment' => [
                        'de' => 'Gehalt nie Klartext in Default-Marts.',
                        'en' => 'Never cleartext salary in default marts.',
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
                        'de' => 'employee id, work_email (hashed), external HRIS id.',
                        'en' => 'employee id, work_email (hashed), external HRIS id.',
                    ],
                ],
                [
                    'focus' => [
                        'de' => 'Primärobjekte',
                        'en' => 'Primary objects',
                    ],
                    'notes' => [
                        'de' => 'Employee, Employment, Department, Time Off, Manager + Warehouse-Kopien.',
                        'en' => 'Employee, employment, department, time off, manager + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'headcount-active',
                    'example' => true,
                    'label' => [
                        'de' => 'Active Headcount',
                        'en' => 'Active headcount',
                    ],
                    'question' => [
                        'de' => 'Wie viele aktive Employees (Snapshot)?',
                        'en' => 'How many active employees (snapshot)?',
                    ],
                    'formula' => 'COUNT(*) FROM employee WHERE status = \'active\'',
                    'grain' => [
                        'de' => 'Aktiver Employee',
                        'en' => 'Active employee',
                    ],
                    'dimensions' => [
                        'department',
                        'employment_status',
                        'employment_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Employee.id',
                        'Employee.status',
                        'Department.id',
                    ],
                    'sourceHints' => [
                        'de' => 'status=active; Contractor-Policy locken.',
                        'en' => 'status=active; lock contractor policy.',
                    ],
                    'adapt' => [
                        'de' => 'FTE-gewichtet als Variante.',
                        'en' => 'FTE-weighted variant.',
                    ],
                ],
                [
                    'id' => 'hires-in-period',
                    'example' => true,
                    'label' => [
                        'de' => 'Hires',
                        'en' => 'Hires',
                    ],
                    'question' => [
                        'de' => 'Wie viele Einstellungen in der Periode?',
                        'en' => 'How many hires in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM employee WHERE hire_date IN period',
                    'grain' => [
                        'de' => 'Hire Event',
                        'en' => 'Hire event',
                    ],
                    'dimensions' => [
                        'department',
                        'employment_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Employee.id',
                        'Employee.hire_date',
                    ],
                    'sourceHints' => [
                        'de' => 'hire_date Pflicht; Rehires separat.',
                        'en' => 'hire_date required; treat rehires separately.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Primary Employment zählen.',
                        'en' => 'Count primary employment only.',
                    ],
                ],
                [
                    'id' => 'terminations-in-period',
                    'example' => false,
                    'label' => [
                        'de' => 'Terminations',
                        'en' => 'Terminations',
                    ],
                    'question' => [
                        'de' => 'Wie viele Austritte in der Periode?',
                        'en' => 'How many terminations in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM employee WHERE termination_date IN period',
                    'grain' => [
                        'de' => 'Termination Event',
                        'en' => 'Termination event',
                    ],
                    'dimensions' => [
                        'department',
                        'employment_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Employee.termination_date',
                        'Employee.id',
                    ],
                    'sourceHints' => [
                        'de' => 'Soft-deleted vs terminated klären.',
                        'en' => 'Clarify soft-deleted vs terminated.',
                    ],
                    'adapt' => [
                        'de' => 'Voluntary vs involuntary wenn Feld existiert.',
                        'en' => 'Voluntary vs involuntary if field exists.',
                    ],
                ],
                [
                    'id' => 'time-off-days',
                    'example' => false,
                    'label' => [
                        'de' => 'Time Off Days',
                        'en' => 'Time-off days',
                    ],
                    'question' => [
                        'de' => 'Wie viele genehmigte Abwesenheitstage in der Periode?',
                        'en' => 'How many approved absence days in the period?',
                    ],
                    'formula' => 'SUM(days) FROM time_off WHERE status = \'approved\' AND start_date IN period',
                    'grain' => [
                        'de' => 'Approved Time-off Day',
                        'en' => 'Approved time-off day',
                    ],
                    'dimensions' => [
                        'department',
                        'time_off_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'TimeOff.days',
                        'TimeOff.status',
                        'TimeOff.type',
                    ],
                    'sourceHints' => [
                        'de' => 'Keine Medical Notes laden.',
                        'en' => 'Do not load medical notes.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Vacation vs alle Types.',
                        'en' => 'Vacation-only vs all types.',
                    ],
                ],
                [
                    'id' => 'span-of-control',
                    'example' => false,
                    'label' => [
                        'de' => 'Avg Span of Control',
                        'en' => 'Avg span of control',
                    ],
                    'question' => [
                        'de' => 'Wie groß ist die durchschnittliche Führungsspanne?',
                        'en' => 'What is the average span of control?',
                    ],
                    'formula' => 'AVG(direct_reports) FROM manager_edge WHERE manager is active',
                    'grain' => [
                        'de' => 'Manager mit Direct Reports',
                        'en' => 'Manager with direct reports',
                    ],
                    'dimensions' => [
                        'department',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Manager.manager_id',
                        'Manager.employee_id',
                    ],
                    'sourceHints' => [
                        'de' => 'Nur aktive Direct Reports.',
                        'en' => 'Active direct reports only.',
                    ],
                    'adapt' => [
                        'de' => 'Max Span als Governance-KPI.',
                        'en' => 'Max span as governance KPI.',
                    ],
                ],
            ],
            'tools' => [
                'kpi-definition',
                'pii-recommend-generator',
                'pii-policy-generator',
                'schema-yml-editor',
            ],
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
        [
            'id' => 'hibob',
            'domain' => 'hcm',
            'order' => 470,
            'label' => [
                'de' => 'HiBob',
                'en' => 'HiBob',
            ],
            'shortPurpose' => [
                'de' => 'HRIS: Employee/Employment, Abwesenheiten, Org — API-Load, strenges Workforce-PII; Gehalt nie Klartext in Marts.',
                'en' => 'HRIS: employee/employment, time off, org — API load, strict workforce PII; never cleartext salary in marts.',
            ],
            'entities' => [
                [
                    'id' => 'employee',
                    'label' => [
                        'de' => 'Employee',
                        'en' => 'Employee',
                    ],
                    'description' => [
                        'de' => 'HiBob Employee — Master; E-Mail/Name = Workforce-PII.',
                        'en' => 'HiBob employee — master; email/name = workforce PII.',
                    ],
                    'grain' => [
                        'de' => 'Ein Employee (id)',
                        'en' => 'One employee (id)',
                    ],
                    'role' => [
                        'de' => 'Dimension (PII)',
                        'en' => 'Dimension (PII)',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'employment',
                    'label' => [
                        'de' => 'Employment',
                        'en' => 'Employment',
                    ],
                    'description' => [
                        'de' => 'Anstellung / Job Info — Status, Start/End, Department.',
                        'en' => 'Employment / job info — status, start/end, department.',
                    ],
                    'grain' => [
                        'de' => 'Eine Employment-Zeile',
                        'en' => 'One employment row',
                    ],
                    'role' => [
                        'de' => 'Status-Fact',
                        'en' => 'Status fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'department',
                    'label' => [
                        'de' => 'Department',
                        'en' => 'Department',
                    ],
                    'description' => [
                        'de' => 'Org-Einheit / Team-Struktur.',
                        'en' => 'Org unit / team structure.',
                    ],
                    'grain' => [
                        'de' => 'Ein Department',
                        'en' => 'One department',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'location',
                    'label' => [
                        'de' => 'Location',
                        'en' => 'Location',
                    ],
                    'description' => [
                        'de' => 'Arbeitsort / Site.',
                        'en' => 'Work location / site.',
                    ],
                    'grain' => [
                        'de' => 'Eine Location',
                        'en' => 'One location',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'optional',
                ],
                [
                    'id' => 'time_off',
                    'label' => [
                        'de' => 'Time Off',
                        'en' => 'Time off',
                    ],
                    'description' => [
                        'de' => 'Abwesenheit — Typ, Status, Tage; keine medizinischen Notizen.',
                        'en' => 'Time off — type, status, days; no medical notes.',
                    ],
                    'grain' => [
                        'de' => 'Ein Time-off Request',
                        'en' => 'One time-off request',
                    ],
                    'role' => [
                        'de' => 'Absence-Fact',
                        'en' => 'Absence fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'compensation_meta',
                    'label' => [
                        'de' => 'Compensation Meta',
                        'en' => 'Compensation meta',
                    ],
                    'description' => [
                        'de' => 'Compensation Metadata — Band/Currency/Effective; Beträge nie Klartext in Marts.',
                        'en' => 'Compensation metadata — band/currency/effective; never cleartext amounts in marts.',
                    ],
                    'grain' => [
                        'de' => 'Eine Compensation Zeile (meta)',
                        'en' => 'One compensation row (meta)',
                    ],
                    'role' => [
                        'de' => 'Sensitive Fact',
                        'en' => 'Sensitive fact',
                    ],
                    'load' => 'optional',
                ],
                [
                    'id' => 'manager',
                    'label' => [
                        'de' => 'Manager Edge',
                        'en' => 'Manager edge',
                    ],
                    'description' => [
                        'de' => 'Manager-Hierarchie als Employee-IDs.',
                        'en' => 'Manager hierarchy as employee ids.',
                    ],
                    'grain' => [
                        'de' => 'Eine Manager-Zuordnung',
                        'en' => 'One manager assignment',
                    ],
                    'role' => [
                        'de' => 'Org-Fact',
                        'en' => 'Org fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'document_meta',
                    'label' => [
                        'de' => 'HR Document Meta',
                        'en' => 'HR document meta',
                    ],
                    'description' => [
                        'de' => 'Dokument-Metadaten — keine Vertrags-Bodies.',
                        'en' => 'Document metadata — no contract bodies.',
                    ],
                    'grain' => [
                        'de' => 'Ein HR Document (meta)',
                        'en' => 'One HR document (meta)',
                    ],
                    'role' => [
                        'de' => 'Meta-Fact',
                        'en' => 'Meta fact',
                    ],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                [
                    'entity' => 'Employee',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Employee Join-Key',
                        'en' => 'Employee join key',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'work_email',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Work E-Mail / Workforce-PII',
                        'en' => 'Work email / workforce PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'personal_email',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Private E-Mail / PII',
                        'en' => 'Personal email / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'first_name',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Vorname / PII',
                        'en' => 'First name / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'last_name',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Nachname / PII',
                        'en' => 'Last name / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'birth_date',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Geburtsdatum / PII',
                        'en' => 'Birth date / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'national_id',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Nationale ID — hashen/restriktieren',
                        'en' => 'National ID — hash/restrict',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Active / Inactive / Terminated',
                        'en' => 'Active / inactive / terminated',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'hire_date',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Eintrittsdatum',
                        'en' => 'Hire date',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'termination_date',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Austrittsdatum',
                        'en' => 'Termination date',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'employee_id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'job_title',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Job Title',
                        'en' => 'Job title',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'employment_type',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Full-time / Part-time / Contractor',
                        'en' => 'Full-time / part-time / contractor',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'fte',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'FTE-Faktor',
                        'en' => 'FTE factor',
                    ],
                ],
                [
                    'entity' => 'Department',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Department Join',
                        'en' => 'Department join',
                    ],
                ],
                [
                    'entity' => 'Department',
                    'name' => 'name',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Department Name',
                        'en' => 'Department name',
                    ],
                ],
                [
                    'entity' => 'Location',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Location Join',
                        'en' => 'Location join',
                    ],
                ],
                [
                    'entity' => 'Location',
                    'name' => 'country',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Land',
                        'en' => 'Country',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Time-off Request Key',
                        'en' => 'Time-off request key',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'employee_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'type',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Vacation / Sick / Other',
                        'en' => 'Vacation / sick / other',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Approved / Pending / Denied',
                        'en' => 'Approved / pending / denied',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'days',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Abwesenheitstage',
                        'en' => 'Absence days',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'start_date',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Start',
                        'en' => 'Start',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'employee_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'currency',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Currency',
                        'en' => 'Currency',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'effective_on',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Effective Date',
                        'en' => 'Effective date',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'amount',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Gehalt — nie Klartext in Default-Marts',
                        'en' => 'Salary — never cleartext in default marts',
                    ],
                ],
                [
                    'entity' => 'Manager',
                    'name' => 'employee_id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Employee',
                        'en' => 'Employee',
                    ],
                ],
                [
                    'entity' => 'Manager',
                    'name' => 'manager_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Manager Employee-ID',
                        'en' => 'Manager employee id',
                    ],
                ],
                [
                    'entity' => 'DocumentMeta',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Document Meta Key',
                        'en' => 'Document meta key',
                    ],
                ],
                [
                    'entity' => 'DocumentMeta',
                    'name' => 'employee_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'DocumentMeta',
                    'name' => 'document_type',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Contract / ID / Other (meta)',
                        'en' => 'Contract / ID / other (meta)',
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
            ],
            'dimensions' => [
                [
                    'id' => 'department',
                    'label' => [
                        'de' => 'Department',
                        'en' => 'Department',
                    ],
                    'grain' => [
                        'de' => 'department.id',
                        'en' => 'department.id',
                    ],
                    'notes' => [
                        'de' => 'Org-Rollups; Renames SCD2.',
                        'en' => 'Org rollups; renames SCD2.',
                    ],
                ],
                [
                    'id' => 'employment_status',
                    'label' => [
                        'de' => 'Employment Status',
                        'en' => 'Employment status',
                    ],
                    'grain' => [
                        'de' => 'employee.status',
                        'en' => 'employee.status',
                    ],
                    'notes' => [
                        'de' => 'Active vs Terminated klar trennen.',
                        'en' => 'Clearly separate active vs terminated.',
                    ],
                ],
                [
                    'id' => 'employment_type',
                    'label' => [
                        'de' => 'Employment Type',
                        'en' => 'Employment type',
                    ],
                    'grain' => [
                        'de' => 'employment.employment_type',
                        'en' => 'employment.employment_type',
                    ],
                    'notes' => [
                        'de' => 'Contractor in Headcount-Definition klären.',
                        'en' => 'Clarify contractor in headcount definition.',
                    ],
                ],
                [
                    'id' => 'location_country',
                    'label' => [
                        'de' => 'Country',
                        'en' => 'Country',
                    ],
                    'grain' => [
                        'de' => 'location.country',
                        'en' => 'location.country',
                    ],
                    'notes' => [
                        'de' => 'ISO Country bevorzugen.',
                        'en' => 'Prefer ISO country.',
                    ],
                ],
                [
                    'id' => 'time_off_type',
                    'label' => [
                        'de' => 'Time Off Type',
                        'en' => 'Time-off type',
                    ],
                    'grain' => [
                        'de' => 'time_off.type',
                        'en' => 'time_off.type',
                    ],
                    'notes' => [
                        'de' => 'Sick ohne Diagnose-/Notizfelder.',
                        'en' => 'Sick without diagnosis/note fields.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Employee',
                    'fields' => [
                        'work_email',
                        'personal_email',
                        'first_name',
                        'last_name',
                    ],
                    'treatment' => [
                        'de' => 'Workforce-PII taggen; employee id als Join.',
                        'en' => 'Tag workforce PII; prefer employee id join.',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'fields' => [
                        'birth_date',
                        'national_id',
                    ],
                    'treatment' => [
                        'de' => 'Hashen/restriktieren; nationale IDs nicht in Marts.',
                        'en' => 'Hash/restrict; no national IDs in marts.',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'fields' => [
                        'amount',
                    ],
                    'treatment' => [
                        'de' => 'Gehalt nie Klartext in Default-Marts.',
                        'en' => 'Never cleartext salary in default marts.',
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
                        'de' => 'employee id, work_email (hashed), external HRIS id.',
                        'en' => 'employee id, work_email (hashed), external HRIS id.',
                    ],
                ],
                [
                    'focus' => [
                        'de' => 'Primärobjekte',
                        'en' => 'Primary objects',
                    ],
                    'notes' => [
                        'de' => 'Employee, Employment, Department, Time Off, Manager + Warehouse-Kopien.',
                        'en' => 'Employee, employment, department, time off, manager + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'headcount-active',
                    'example' => true,
                    'label' => [
                        'de' => 'Active Headcount',
                        'en' => 'Active headcount',
                    ],
                    'question' => [
                        'de' => 'Wie viele aktive Employees (Snapshot)?',
                        'en' => 'How many active employees (snapshot)?',
                    ],
                    'formula' => 'COUNT(*) FROM employee WHERE status = \'active\'',
                    'grain' => [
                        'de' => 'Aktiver Employee',
                        'en' => 'Active employee',
                    ],
                    'dimensions' => [
                        'department',
                        'employment_status',
                        'employment_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Employee.id',
                        'Employee.status',
                        'Department.id',
                    ],
                    'sourceHints' => [
                        'de' => 'status=active; Contractor-Policy locken.',
                        'en' => 'status=active; lock contractor policy.',
                    ],
                    'adapt' => [
                        'de' => 'FTE-gewichtet als Variante.',
                        'en' => 'FTE-weighted variant.',
                    ],
                ],
                [
                    'id' => 'hires-in-period',
                    'example' => true,
                    'label' => [
                        'de' => 'Hires',
                        'en' => 'Hires',
                    ],
                    'question' => [
                        'de' => 'Wie viele Einstellungen in der Periode?',
                        'en' => 'How many hires in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM employee WHERE hire_date IN period',
                    'grain' => [
                        'de' => 'Hire Event',
                        'en' => 'Hire event',
                    ],
                    'dimensions' => [
                        'department',
                        'employment_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Employee.id',
                        'Employee.hire_date',
                    ],
                    'sourceHints' => [
                        'de' => 'hire_date Pflicht; Rehires separat.',
                        'en' => 'hire_date required; treat rehires separately.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Primary Employment zählen.',
                        'en' => 'Count primary employment only.',
                    ],
                ],
                [
                    'id' => 'terminations-in-period',
                    'example' => false,
                    'label' => [
                        'de' => 'Terminations',
                        'en' => 'Terminations',
                    ],
                    'question' => [
                        'de' => 'Wie viele Austritte in der Periode?',
                        'en' => 'How many terminations in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM employee WHERE termination_date IN period',
                    'grain' => [
                        'de' => 'Termination Event',
                        'en' => 'Termination event',
                    ],
                    'dimensions' => [
                        'department',
                        'employment_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Employee.termination_date',
                        'Employee.id',
                    ],
                    'sourceHints' => [
                        'de' => 'Soft-deleted vs terminated klären.',
                        'en' => 'Clarify soft-deleted vs terminated.',
                    ],
                    'adapt' => [
                        'de' => 'Voluntary vs involuntary wenn Feld existiert.',
                        'en' => 'Voluntary vs involuntary if field exists.',
                    ],
                ],
                [
                    'id' => 'time-off-days',
                    'example' => false,
                    'label' => [
                        'de' => 'Time Off Days',
                        'en' => 'Time-off days',
                    ],
                    'question' => [
                        'de' => 'Wie viele genehmigte Abwesenheitstage in der Periode?',
                        'en' => 'How many approved absence days in the period?',
                    ],
                    'formula' => 'SUM(days) FROM time_off WHERE status = \'approved\' AND start_date IN period',
                    'grain' => [
                        'de' => 'Approved Time-off Day',
                        'en' => 'Approved time-off day',
                    ],
                    'dimensions' => [
                        'department',
                        'time_off_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'TimeOff.days',
                        'TimeOff.status',
                        'TimeOff.type',
                    ],
                    'sourceHints' => [
                        'de' => 'Keine Medical Notes laden.',
                        'en' => 'Do not load medical notes.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Vacation vs alle Types.',
                        'en' => 'Vacation-only vs all types.',
                    ],
                ],
                [
                    'id' => 'span-of-control',
                    'example' => false,
                    'label' => [
                        'de' => 'Avg Span of Control',
                        'en' => 'Avg span of control',
                    ],
                    'question' => [
                        'de' => 'Wie groß ist die durchschnittliche Führungsspanne?',
                        'en' => 'What is the average span of control?',
                    ],
                    'formula' => 'AVG(direct_reports) FROM manager_edge WHERE manager is active',
                    'grain' => [
                        'de' => 'Manager mit Direct Reports',
                        'en' => 'Manager with direct reports',
                    ],
                    'dimensions' => [
                        'department',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Manager.manager_id',
                        'Manager.employee_id',
                    ],
                    'sourceHints' => [
                        'de' => 'Nur aktive Direct Reports.',
                        'en' => 'Active direct reports only.',
                    ],
                    'adapt' => [
                        'de' => 'Max Span als Governance-KPI.',
                        'en' => 'Max span as governance KPI.',
                    ],
                ],
            ],
            'tools' => [
                'kpi-definition',
                'pii-recommend-generator',
                'pii-policy-generator',
                'schema-yml-editor',
            ],
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
        [
            'id' => 'factorial',
            'domain' => 'hcm',
            'order' => 480,
            'label' => [
                'de' => 'Factorial',
                'en' => 'Factorial',
            ],
            'shortPurpose' => [
                'de' => 'HRIS: Employee/Employment, Abwesenheiten, Org — API-Load, strenges Workforce-PII; Gehalt nie Klartext in Marts.',
                'en' => 'HRIS: employee/employment, time off, org — API load, strict workforce PII; never cleartext salary in marts.',
            ],
            'entities' => [
                [
                    'id' => 'employee',
                    'label' => [
                        'de' => 'Employee',
                        'en' => 'Employee',
                    ],
                    'description' => [
                        'de' => 'Factorial Employee — Master; E-Mail/Name = Workforce-PII.',
                        'en' => 'Factorial employee — master; email/name = workforce PII.',
                    ],
                    'grain' => [
                        'de' => 'Ein Employee (id)',
                        'en' => 'One employee (id)',
                    ],
                    'role' => [
                        'de' => 'Dimension (PII)',
                        'en' => 'Dimension (PII)',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'employment',
                    'label' => [
                        'de' => 'Employment',
                        'en' => 'Employment',
                    ],
                    'description' => [
                        'de' => 'Anstellung / Job Info — Status, Start/End, Department.',
                        'en' => 'Employment / job info — status, start/end, department.',
                    ],
                    'grain' => [
                        'de' => 'Eine Employment-Zeile',
                        'en' => 'One employment row',
                    ],
                    'role' => [
                        'de' => 'Status-Fact',
                        'en' => 'Status fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'department',
                    'label' => [
                        'de' => 'Department',
                        'en' => 'Department',
                    ],
                    'description' => [
                        'de' => 'Org-Einheit / Team-Struktur.',
                        'en' => 'Org unit / team structure.',
                    ],
                    'grain' => [
                        'de' => 'Ein Department',
                        'en' => 'One department',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'location',
                    'label' => [
                        'de' => 'Location',
                        'en' => 'Location',
                    ],
                    'description' => [
                        'de' => 'Arbeitsort / Site.',
                        'en' => 'Work location / site.',
                    ],
                    'grain' => [
                        'de' => 'Eine Location',
                        'en' => 'One location',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'optional',
                ],
                [
                    'id' => 'time_off',
                    'label' => [
                        'de' => 'Time Off',
                        'en' => 'Time off',
                    ],
                    'description' => [
                        'de' => 'Abwesenheit — Typ, Status, Tage; keine medizinischen Notizen.',
                        'en' => 'Time off — type, status, days; no medical notes.',
                    ],
                    'grain' => [
                        'de' => 'Ein Time-off Request',
                        'en' => 'One time-off request',
                    ],
                    'role' => [
                        'de' => 'Absence-Fact',
                        'en' => 'Absence fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'compensation_meta',
                    'label' => [
                        'de' => 'Compensation Meta',
                        'en' => 'Compensation meta',
                    ],
                    'description' => [
                        'de' => 'Compensation Metadata — Band/Currency/Effective; Beträge nie Klartext in Marts.',
                        'en' => 'Compensation metadata — band/currency/effective; never cleartext amounts in marts.',
                    ],
                    'grain' => [
                        'de' => 'Eine Compensation Zeile (meta)',
                        'en' => 'One compensation row (meta)',
                    ],
                    'role' => [
                        'de' => 'Sensitive Fact',
                        'en' => 'Sensitive fact',
                    ],
                    'load' => 'optional',
                ],
                [
                    'id' => 'manager',
                    'label' => [
                        'de' => 'Manager Edge',
                        'en' => 'Manager edge',
                    ],
                    'description' => [
                        'de' => 'Manager-Hierarchie als Employee-IDs.',
                        'en' => 'Manager hierarchy as employee ids.',
                    ],
                    'grain' => [
                        'de' => 'Eine Manager-Zuordnung',
                        'en' => 'One manager assignment',
                    ],
                    'role' => [
                        'de' => 'Org-Fact',
                        'en' => 'Org fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'document_meta',
                    'label' => [
                        'de' => 'HR Document Meta',
                        'en' => 'HR document meta',
                    ],
                    'description' => [
                        'de' => 'Dokument-Metadaten — keine Vertrags-Bodies.',
                        'en' => 'Document metadata — no contract bodies.',
                    ],
                    'grain' => [
                        'de' => 'Ein HR Document (meta)',
                        'en' => 'One HR document (meta)',
                    ],
                    'role' => [
                        'de' => 'Meta-Fact',
                        'en' => 'Meta fact',
                    ],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                [
                    'entity' => 'Employee',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Employee Join-Key',
                        'en' => 'Employee join key',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'work_email',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Work E-Mail / Workforce-PII',
                        'en' => 'Work email / workforce PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'personal_email',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Private E-Mail / PII',
                        'en' => 'Personal email / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'first_name',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Vorname / PII',
                        'en' => 'First name / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'last_name',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Nachname / PII',
                        'en' => 'Last name / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'birth_date',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Geburtsdatum / PII',
                        'en' => 'Birth date / PII',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'national_id',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Nationale ID — hashen/restriktieren',
                        'en' => 'National ID — hash/restrict',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Active / Inactive / Terminated',
                        'en' => 'Active / inactive / terminated',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'hire_date',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Eintrittsdatum',
                        'en' => 'Hire date',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'name' => 'termination_date',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Austrittsdatum',
                        'en' => 'Termination date',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'employee_id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'job_title',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Job Title',
                        'en' => 'Job title',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'employment_type',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Full-time / Part-time / Contractor',
                        'en' => 'Full-time / part-time / contractor',
                    ],
                ],
                [
                    'entity' => 'Employment',
                    'name' => 'fte',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'FTE-Faktor',
                        'en' => 'FTE factor',
                    ],
                ],
                [
                    'entity' => 'Department',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Department Join',
                        'en' => 'Department join',
                    ],
                ],
                [
                    'entity' => 'Department',
                    'name' => 'name',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Department Name',
                        'en' => 'Department name',
                    ],
                ],
                [
                    'entity' => 'Location',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Location Join',
                        'en' => 'Location join',
                    ],
                ],
                [
                    'entity' => 'Location',
                    'name' => 'country',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Land',
                        'en' => 'Country',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Time-off Request Key',
                        'en' => 'Time-off request key',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'employee_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'type',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Vacation / Sick / Other',
                        'en' => 'Vacation / sick / other',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Approved / Pending / Denied',
                        'en' => 'Approved / pending / denied',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'days',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Abwesenheitstage',
                        'en' => 'Absence days',
                    ],
                ],
                [
                    'entity' => 'TimeOff',
                    'name' => 'start_date',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Start',
                        'en' => 'Start',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'employee_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'currency',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Currency',
                        'en' => 'Currency',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'effective_on',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Effective Date',
                        'en' => 'Effective date',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'name' => 'amount',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Gehalt — nie Klartext in Default-Marts',
                        'en' => 'Salary — never cleartext in default marts',
                    ],
                ],
                [
                    'entity' => 'Manager',
                    'name' => 'employee_id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Employee',
                        'en' => 'Employee',
                    ],
                ],
                [
                    'entity' => 'Manager',
                    'name' => 'manager_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Manager Employee-ID',
                        'en' => 'Manager employee id',
                    ],
                ],
                [
                    'entity' => 'DocumentMeta',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Document Meta Key',
                        'en' => 'Document meta key',
                    ],
                ],
                [
                    'entity' => 'DocumentMeta',
                    'name' => 'employee_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Employee Join',
                        'en' => 'Employee join',
                    ],
                ],
                [
                    'entity' => 'DocumentMeta',
                    'name' => 'document_type',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Contract / ID / Other (meta)',
                        'en' => 'Contract / ID / other (meta)',
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
            ],
            'dimensions' => [
                [
                    'id' => 'department',
                    'label' => [
                        'de' => 'Department',
                        'en' => 'Department',
                    ],
                    'grain' => [
                        'de' => 'department.id',
                        'en' => 'department.id',
                    ],
                    'notes' => [
                        'de' => 'Org-Rollups; Renames SCD2.',
                        'en' => 'Org rollups; renames SCD2.',
                    ],
                ],
                [
                    'id' => 'employment_status',
                    'label' => [
                        'de' => 'Employment Status',
                        'en' => 'Employment status',
                    ],
                    'grain' => [
                        'de' => 'employee.status',
                        'en' => 'employee.status',
                    ],
                    'notes' => [
                        'de' => 'Active vs Terminated klar trennen.',
                        'en' => 'Clearly separate active vs terminated.',
                    ],
                ],
                [
                    'id' => 'employment_type',
                    'label' => [
                        'de' => 'Employment Type',
                        'en' => 'Employment type',
                    ],
                    'grain' => [
                        'de' => 'employment.employment_type',
                        'en' => 'employment.employment_type',
                    ],
                    'notes' => [
                        'de' => 'Contractor in Headcount-Definition klären.',
                        'en' => 'Clarify contractor in headcount definition.',
                    ],
                ],
                [
                    'id' => 'location_country',
                    'label' => [
                        'de' => 'Country',
                        'en' => 'Country',
                    ],
                    'grain' => [
                        'de' => 'location.country',
                        'en' => 'location.country',
                    ],
                    'notes' => [
                        'de' => 'ISO Country bevorzugen.',
                        'en' => 'Prefer ISO country.',
                    ],
                ],
                [
                    'id' => 'time_off_type',
                    'label' => [
                        'de' => 'Time Off Type',
                        'en' => 'Time-off type',
                    ],
                    'grain' => [
                        'de' => 'time_off.type',
                        'en' => 'time_off.type',
                    ],
                    'notes' => [
                        'de' => 'Sick ohne Diagnose-/Notizfelder.',
                        'en' => 'Sick without diagnosis/note fields.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Employee',
                    'fields' => [
                        'work_email',
                        'personal_email',
                        'first_name',
                        'last_name',
                    ],
                    'treatment' => [
                        'de' => 'Workforce-PII taggen; employee id als Join.',
                        'en' => 'Tag workforce PII; prefer employee id join.',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'fields' => [
                        'birth_date',
                        'national_id',
                    ],
                    'treatment' => [
                        'de' => 'Hashen/restriktieren; nationale IDs nicht in Marts.',
                        'en' => 'Hash/restrict; no national IDs in marts.',
                    ],
                ],
                [
                    'entity' => 'CompensationMeta',
                    'fields' => [
                        'amount',
                    ],
                    'treatment' => [
                        'de' => 'Gehalt nie Klartext in Default-Marts.',
                        'en' => 'Never cleartext salary in default marts.',
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
                        'de' => 'employee id, work_email (hashed), external HRIS id.',
                        'en' => 'employee id, work_email (hashed), external HRIS id.',
                    ],
                ],
                [
                    'focus' => [
                        'de' => 'Primärobjekte',
                        'en' => 'Primary objects',
                    ],
                    'notes' => [
                        'de' => 'Employee, Employment, Department, Time Off, Manager + Warehouse-Kopien.',
                        'en' => 'Employee, employment, department, time off, manager + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'headcount-active',
                    'example' => true,
                    'label' => [
                        'de' => 'Active Headcount',
                        'en' => 'Active headcount',
                    ],
                    'question' => [
                        'de' => 'Wie viele aktive Employees (Snapshot)?',
                        'en' => 'How many active employees (snapshot)?',
                    ],
                    'formula' => 'COUNT(*) FROM employee WHERE status = \'active\'',
                    'grain' => [
                        'de' => 'Aktiver Employee',
                        'en' => 'Active employee',
                    ],
                    'dimensions' => [
                        'department',
                        'employment_status',
                        'employment_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Employee.id',
                        'Employee.status',
                        'Department.id',
                    ],
                    'sourceHints' => [
                        'de' => 'status=active; Contractor-Policy locken.',
                        'en' => 'status=active; lock contractor policy.',
                    ],
                    'adapt' => [
                        'de' => 'FTE-gewichtet als Variante.',
                        'en' => 'FTE-weighted variant.',
                    ],
                ],
                [
                    'id' => 'hires-in-period',
                    'example' => true,
                    'label' => [
                        'de' => 'Hires',
                        'en' => 'Hires',
                    ],
                    'question' => [
                        'de' => 'Wie viele Einstellungen in der Periode?',
                        'en' => 'How many hires in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM employee WHERE hire_date IN period',
                    'grain' => [
                        'de' => 'Hire Event',
                        'en' => 'Hire event',
                    ],
                    'dimensions' => [
                        'department',
                        'employment_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Employee.id',
                        'Employee.hire_date',
                    ],
                    'sourceHints' => [
                        'de' => 'hire_date Pflicht; Rehires separat.',
                        'en' => 'hire_date required; treat rehires separately.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Primary Employment zählen.',
                        'en' => 'Count primary employment only.',
                    ],
                ],
                [
                    'id' => 'terminations-in-period',
                    'example' => false,
                    'label' => [
                        'de' => 'Terminations',
                        'en' => 'Terminations',
                    ],
                    'question' => [
                        'de' => 'Wie viele Austritte in der Periode?',
                        'en' => 'How many terminations in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM employee WHERE termination_date IN period',
                    'grain' => [
                        'de' => 'Termination Event',
                        'en' => 'Termination event',
                    ],
                    'dimensions' => [
                        'department',
                        'employment_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Employee.termination_date',
                        'Employee.id',
                    ],
                    'sourceHints' => [
                        'de' => 'Soft-deleted vs terminated klären.',
                        'en' => 'Clarify soft-deleted vs terminated.',
                    ],
                    'adapt' => [
                        'de' => 'Voluntary vs involuntary wenn Feld existiert.',
                        'en' => 'Voluntary vs involuntary if field exists.',
                    ],
                ],
                [
                    'id' => 'time-off-days',
                    'example' => false,
                    'label' => [
                        'de' => 'Time Off Days',
                        'en' => 'Time-off days',
                    ],
                    'question' => [
                        'de' => 'Wie viele genehmigte Abwesenheitstage in der Periode?',
                        'en' => 'How many approved absence days in the period?',
                    ],
                    'formula' => 'SUM(days) FROM time_off WHERE status = \'approved\' AND start_date IN period',
                    'grain' => [
                        'de' => 'Approved Time-off Day',
                        'en' => 'Approved time-off day',
                    ],
                    'dimensions' => [
                        'department',
                        'time_off_type',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'TimeOff.days',
                        'TimeOff.status',
                        'TimeOff.type',
                    ],
                    'sourceHints' => [
                        'de' => 'Keine Medical Notes laden.',
                        'en' => 'Do not load medical notes.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Vacation vs alle Types.',
                        'en' => 'Vacation-only vs all types.',
                    ],
                ],
                [
                    'id' => 'span-of-control',
                    'example' => false,
                    'label' => [
                        'de' => 'Avg Span of Control',
                        'en' => 'Avg span of control',
                    ],
                    'question' => [
                        'de' => 'Wie groß ist die durchschnittliche Führungsspanne?',
                        'en' => 'What is the average span of control?',
                    ],
                    'formula' => 'AVG(direct_reports) FROM manager_edge WHERE manager is active',
                    'grain' => [
                        'de' => 'Manager mit Direct Reports',
                        'en' => 'Manager with direct reports',
                    ],
                    'dimensions' => [
                        'department',
                        'location_country',
                    ],
                    'fieldsUsed' => [
                        'Manager.manager_id',
                        'Manager.employee_id',
                    ],
                    'sourceHints' => [
                        'de' => 'Nur aktive Direct Reports.',
                        'en' => 'Active direct reports only.',
                    ],
                    'adapt' => [
                        'de' => 'Max Span als Governance-KPI.',
                        'en' => 'Max span as governance KPI.',
                    ],
                ],
            ],
            'tools' => [
                'kpi-definition',
                'pii-recommend-generator',
                'pii-policy-generator',
                'schema-yml-editor',
            ],
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
        [
            'id' => 'greenhouse',
            'domain' => 'hcm',
            'order' => 490,
            'label' => [
                'de' => 'Greenhouse',
                'en' => 'Greenhouse',
            ],
            'shortPurpose' => [
                'de' => 'ATS: Candidate/Application/Job Pipeline — API-Load, Bewerber-PII streng; keine CV/Scorecard-Bodies als Default.',
                'en' => 'ATS: candidate/application/job pipeline — API load, strict applicant PII; no CV/scorecard bodies by default.',
            ],
            'entities' => [
                [
                    'id' => 'candidate',
                    'label' => [
                        'de' => 'Candidate',
                        'en' => 'Candidate',
                    ],
                    'description' => [
                        'de' => 'Greenhouse Candidate — Name/E-Mail = Bewerber-PII.',
                        'en' => 'Greenhouse candidate — name/email = applicant PII.',
                    ],
                    'grain' => [
                        'de' => 'Ein Candidate (id)',
                        'en' => 'One candidate (id)',
                    ],
                    'role' => [
                        'de' => 'Dimension (PII)',
                        'en' => 'Dimension (PII)',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'application',
                    'label' => [
                        'de' => 'Application',
                        'en' => 'Application',
                    ],
                    'description' => [
                        'de' => 'Bewerbung — Stage, Status, Job-Join.',
                        'en' => 'Application — stage, status, job join.',
                    ],
                    'grain' => [
                        'de' => 'Eine Application',
                        'en' => 'One application',
                    ],
                    'role' => [
                        'de' => 'Pipeline-Fact',
                        'en' => 'Pipeline fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'job',
                    'label' => [
                        'de' => 'Job / Requisition',
                        'en' => 'Job / requisition',
                    ],
                    'description' => [
                        'de' => 'Stelle / Req — Department, Location, Status.',
                        'en' => 'Job / req — department, location, status.',
                    ],
                    'grain' => [
                        'de' => 'Ein Job',
                        'en' => 'One job',
                    ],
                    'role' => [
                        'de' => 'Dimension / Fact-Anker',
                        'en' => 'Dimension / fact anchor',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'stage',
                    'label' => [
                        'de' => 'Pipeline Stage',
                        'en' => 'Pipeline stage',
                    ],
                    'description' => [
                        'de' => 'Stage im Recruiting-Funnel.',
                        'en' => 'Stage in recruiting funnel.',
                    ],
                    'grain' => [
                        'de' => 'Eine Stage',
                        'en' => 'One stage',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'interview',
                    'label' => [
                        'de' => 'Interview Meta',
                        'en' => 'Interview meta',
                    ],
                    'description' => [
                        'de' => 'Interview-Termin Meta — keine Scorecard-Freitexte als Default.',
                        'en' => 'Interview schedule meta — no scorecard free text by default.',
                    ],
                    'grain' => [
                        'de' => 'Ein Interview',
                        'en' => 'One interview',
                    ],
                    'role' => [
                        'de' => 'Process-Fact',
                        'en' => 'Process fact',
                    ],
                    'load' => 'optional',
                ],
                [
                    'id' => 'offer',
                    'label' => [
                        'de' => 'Offer Meta',
                        'en' => 'Offer meta',
                    ],
                    'description' => [
                        'de' => 'Offer Metadata — Status/Dates; Compensation restriktiv.',
                        'en' => 'Offer metadata — status/dates; compensation restricted.',
                    ],
                    'grain' => [
                        'de' => 'Ein Offer',
                        'en' => 'One offer',
                    ],
                    'role' => [
                        'de' => 'Sensitive Fact',
                        'en' => 'Sensitive fact',
                    ],
                    'load' => 'optional',
                ],
                [
                    'id' => 'recruiter',
                    'label' => [
                        'de' => 'Recruiter / User',
                        'en' => 'Recruiter / user',
                    ],
                    'description' => [
                        'de' => 'Recruiter/Hiring Manager als User-IDs.',
                        'en' => 'Recruiter/hiring manager as user ids.',
                    ],
                    'grain' => [
                        'de' => 'Ein User',
                        'en' => 'One user',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'source',
                    'label' => [
                        'de' => 'Source',
                        'en' => 'Source',
                    ],
                    'description' => [
                        'de' => 'Bewerbungsquelle / Channel.',
                        'en' => 'Application source / channel.',
                    ],
                    'grain' => [
                        'de' => 'Eine Source',
                        'en' => 'One source',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                [
                    'entity' => 'Candidate',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Candidate Join',
                        'en' => 'Candidate join',
                    ],
                ],
                [
                    'entity' => 'Candidate',
                    'name' => 'email',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'E-Mail / Bewerber-PII',
                        'en' => 'Email / applicant PII',
                    ],
                ],
                [
                    'entity' => 'Candidate',
                    'name' => 'first_name',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Vorname / PII',
                        'en' => 'First name / PII',
                    ],
                ],
                [
                    'entity' => 'Candidate',
                    'name' => 'last_name',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Nachname / PII',
                        'en' => 'Last name / PII',
                    ],
                ],
                [
                    'entity' => 'Candidate',
                    'name' => 'phone',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Telefon / PII',
                        'en' => 'Phone / PII',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Application Key',
                        'en' => 'Application key',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'candidate_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Candidate Join',
                        'en' => 'Candidate join',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'job_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Job Join',
                        'en' => 'Job join',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Active / Hired / Rejected',
                        'en' => 'Active / hired / rejected',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'stage_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Pipeline Stage',
                        'en' => 'Pipeline stage',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'applied_at',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Bewerbungsdatum',
                        'en' => 'Applied at',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'hired_at',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Hire Timestamp',
                        'en' => 'Hire timestamp',
                    ],
                ],
                [
                    'entity' => 'Job',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Job Join',
                        'en' => 'Job join',
                    ],
                ],
                [
                    'entity' => 'Job',
                    'name' => 'title',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Job Title',
                        'en' => 'Job title',
                    ],
                ],
                [
                    'entity' => 'Job',
                    'name' => 'department',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Department',
                        'en' => 'Department',
                    ],
                ],
                [
                    'entity' => 'Job',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Open / Closed / Draft',
                        'en' => 'Open / closed / draft',
                    ],
                ],
                [
                    'entity' => 'Job',
                    'name' => 'opened_at',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Opened At',
                        'en' => 'Opened at',
                    ],
                ],
                [
                    'entity' => 'Stage',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Stage Key',
                        'en' => 'Stage key',
                    ],
                ],
                [
                    'entity' => 'Stage',
                    'name' => 'name',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Stage Name',
                        'en' => 'Stage name',
                    ],
                ],
                [
                    'entity' => 'Interview',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Interview Key',
                        'en' => 'Interview key',
                    ],
                ],
                [
                    'entity' => 'Interview',
                    'name' => 'application_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Application Join',
                        'en' => 'Application join',
                    ],
                ],
                [
                    'entity' => 'Interview',
                    'name' => 'scheduled_at',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Termin',
                        'en' => 'Scheduled at',
                    ],
                ],
                [
                    'entity' => 'Offer',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Offer Key',
                        'en' => 'Offer key',
                    ],
                ],
                [
                    'entity' => 'Offer',
                    'name' => 'application_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Application Join',
                        'en' => 'Application join',
                    ],
                ],
                [
                    'entity' => 'Offer',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Sent / Accepted / Declined',
                        'en' => 'Sent / accepted / declined',
                    ],
                ],
                [
                    'entity' => 'Offer',
                    'name' => 'salary',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Offer Amount — restriktiv',
                        'en' => 'Offer amount — restricted',
                    ],
                ],
                [
                    'entity' => 'Recruiter',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'User Join',
                        'en' => 'User join',
                    ],
                ],
                [
                    'entity' => 'Recruiter',
                    'name' => 'email',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Recruiter E-Mail / PII',
                        'en' => 'Recruiter email / PII',
                    ],
                ],
                [
                    'entity' => 'Source',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Source Key',
                        'en' => 'Source key',
                    ],
                ],
                [
                    'entity' => 'Source',
                    'name' => 'name',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Source Channel',
                        'en' => 'Source channel',
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
            ],
            'dimensions' => [
                [
                    'id' => 'job_department',
                    'label' => [
                        'de' => 'Department',
                        'en' => 'Department',
                    ],
                    'grain' => [
                        'de' => 'job.department',
                        'en' => 'job.department',
                    ],
                    'notes' => [
                        'de' => 'Against HRIS Department map wenn möglich.',
                        'en' => 'Map to HRIS department when possible.',
                    ],
                ],
                [
                    'id' => 'pipeline_stage',
                    'label' => [
                        'de' => 'Pipeline Stage',
                        'en' => 'Pipeline stage',
                    ],
                    'grain' => [
                        'de' => 'stage.name / id',
                        'en' => 'stage.name / id',
                    ],
                    'notes' => [
                        'de' => 'Stage-Order für Funnel festlegen.',
                        'en' => 'Lock stage order for funnel.',
                    ],
                ],
                [
                    'id' => 'application_status',
                    'label' => [
                        'de' => 'Application Status',
                        'en' => 'Application status',
                    ],
                    'grain' => [
                        'de' => 'application.status',
                        'en' => 'application.status',
                    ],
                    'notes' => [
                        'de' => 'Hired/Rejected Definition locken.',
                        'en' => 'Lock hired/rejected definition.',
                    ],
                ],
                [
                    'id' => 'source',
                    'label' => [
                        'de' => 'Source',
                        'en' => 'Source',
                    ],
                    'grain' => [
                        'de' => 'source.name',
                        'en' => 'source.name',
                    ],
                    'notes' => [
                        'de' => 'Source Taxonomy normalisieren.',
                        'en' => 'Normalize source taxonomy.',
                    ],
                ],
                [
                    'id' => 'job_status',
                    'label' => [
                        'de' => 'Job Status',
                        'en' => 'Job status',
                    ],
                    'grain' => [
                        'de' => 'job.status',
                        'en' => 'job.status',
                    ],
                    'notes' => [
                        'de' => 'Open Jobs für Time-to-Fill.',
                        'en' => 'Open jobs for time-to-fill.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Candidate',
                    'fields' => [
                        'email',
                        'first_name',
                        'last_name',
                        'phone',
                    ],
                    'treatment' => [
                        'de' => 'Bewerber-PII; candidate id als Join.',
                        'en' => 'Applicant PII; prefer candidate id join.',
                    ],
                ],
                [
                    'entity' => 'Offer',
                    'fields' => [
                        'salary',
                    ],
                    'treatment' => [
                        'de' => 'Offer Amount restriktiv.',
                        'en' => 'Restrict offer amount.',
                    ],
                ],
                [
                    'entity' => 'Recruiter',
                    'fields' => [
                        'email',
                    ],
                    'treatment' => [
                        'de' => 'Workforce-PII; user id bevorzugen.',
                        'en' => 'Workforce PII; prefer user id.',
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
                        'de' => 'candidate id, application id, job id, email (hashed).',
                        'en' => 'candidate id, application id, job id, email (hashed).',
                    ],
                ],
                [
                    'focus' => [
                        'de' => 'Primärobjekte',
                        'en' => 'Primary objects',
                    ],
                    'notes' => [
                        'de' => 'Candidate, Application, Job, Stage, Offer Meta + Warehouse-Kopien.',
                        'en' => 'Candidate, application, job, stage, offer meta + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'applications-in-period',
                    'example' => true,
                    'label' => [
                        'de' => 'Applications',
                        'en' => 'Applications',
                    ],
                    'question' => [
                        'de' => 'Wie viele Bewerbungen in der Periode?',
                        'en' => 'How many applications in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM application WHERE applied_at IN period',
                    'grain' => [
                        'de' => 'Application',
                        'en' => 'Application',
                    ],
                    'dimensions' => [
                        'job_department',
                        'pipeline_stage',
                        'source',
                        'job_status',
                    ],
                    'fieldsUsed' => [
                        'Application.id',
                        'Application.applied_at',
                        'Job.department',
                    ],
                    'sourceHints' => [
                        'de' => 'applied_at Pflicht; Duplikate dedupen.',
                        'en' => 'applied_at required; dedupe duplicates.',
                    ],
                    'adapt' => [
                        'de' => 'Unique Candidates vs Applications.',
                        'en' => 'Unique candidates vs applications.',
                    ],
                ],
                [
                    'id' => 'hires-from-ats',
                    'example' => true,
                    'label' => [
                        'de' => 'ATS Hires',
                        'en' => 'ATS hires',
                    ],
                    'question' => [
                        'de' => 'Wie viele Hires aus dem ATS in der Periode?',
                        'en' => 'How many ATS hires in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM application WHERE status = \'hired\' AND hired_at IN period',
                    'grain' => [
                        'de' => 'Hired Application',
                        'en' => 'Hired application',
                    ],
                    'dimensions' => [
                        'job_department',
                        'source',
                        'job_status',
                    ],
                    'fieldsUsed' => [
                        'Application.status',
                        'Application.hired_at',
                    ],
                    'sourceHints' => [
                        'de' => 'hired_at vs offer accepted klären.',
                        'en' => 'Clarify hired_at vs offer accepted.',
                    ],
                    'adapt' => [
                        'de' => 'Nur External Hires.',
                        'en' => 'External hires only.',
                    ],
                ],
                [
                    'id' => 'time-to-hire',
                    'example' => false,
                    'label' => [
                        'de' => 'Avg Time to Hire',
                        'en' => 'Avg time to hire',
                    ],
                    'question' => [
                        'de' => 'Wie lange dauert es durchschnittlich von Apply bis Hire?',
                        'en' => 'What is average time from apply to hire?',
                    ],
                    'formula' => 'AVG(hired_at - applied_at) FROM application WHERE hired',
                    'grain' => [
                        'de' => 'Hired Application',
                        'en' => 'Hired application',
                    ],
                    'dimensions' => [
                        'job_department',
                        'source',
                    ],
                    'fieldsUsed' => [
                        'Application.applied_at',
                        'Application.hired_at',
                    ],
                    'sourceHints' => [
                        'de' => 'Outlier (Reopens) filtern.',
                        'en' => 'Filter reopen outliers.',
                    ],
                    'adapt' => [
                        'de' => 'Median statt Average.',
                        'en' => 'Median instead of average.',
                    ],
                ],
                [
                    'id' => 'open-jobs',
                    'example' => false,
                    'label' => [
                        'de' => 'Open Jobs',
                        'en' => 'Open jobs',
                    ],
                    'question' => [
                        'de' => 'Wie viele offene Stellen gibt es (Snapshot)?',
                        'en' => 'How many open jobs are there (snapshot)?',
                    ],
                    'formula' => 'COUNT(*) FROM job WHERE status = \'open\'',
                    'grain' => [
                        'de' => 'Open Job',
                        'en' => 'Open job',
                    ],
                    'dimensions' => [
                        'job_department',
                        'job_status',
                    ],
                    'fieldsUsed' => [
                        'Job.id',
                        'Job.status',
                    ],
                    'sourceHints' => [
                        'de' => 'Draft vs Open trennen.',
                        'en' => 'Separate draft vs open.',
                    ],
                    'adapt' => [
                        'de' => 'Nur approved reqs.',
                        'en' => 'Approved reqs only.',
                    ],
                ],
                [
                    'id' => 'offer-accept-rate',
                    'example' => false,
                    'label' => [
                        'de' => 'Offer Accept Rate',
                        'en' => 'Offer accept rate',
                    ],
                    'question' => [
                        'de' => 'Wie hoch ist die Offer-Annahmequote?',
                        'en' => 'What is the offer acceptance rate?',
                    ],
                    'formula' => 'accepted_offers / sent_offers',
                    'grain' => [
                        'de' => 'Offer',
                        'en' => 'Offer',
                    ],
                    'dimensions' => [
                        'job_department',
                        'source',
                    ],
                    'fieldsUsed' => [
                        'Offer.status',
                        'Offer.id',
                    ],
                    'sourceHints' => [
                        'de' => 'Compensation fields nicht in Public Marts.',
                        'en' => 'Do not put compensation fields in public marts.',
                    ],
                    'adapt' => [
                        'de' => 'Nur External Offers.',
                        'en' => 'External offers only.',
                    ],
                ],
            ],
            'tools' => [
                'kpi-definition',
                'pii-recommend-generator',
                'pii-policy-generator',
                'schema-yml-editor',
            ],
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
        [
            'id' => 'softgarden',
            'domain' => 'hcm',
            'order' => 500,
            'label' => [
                'de' => 'softgarden',
                'en' => 'softgarden',
            ],
            'shortPurpose' => [
                'de' => 'ATS: Candidate/Application/Job Pipeline — API-Load, Bewerber-PII streng; keine CV/Scorecard-Bodies als Default.',
                'en' => 'ATS: candidate/application/job pipeline — API load, strict applicant PII; no CV/scorecard bodies by default.',
            ],
            'entities' => [
                [
                    'id' => 'candidate',
                    'label' => [
                        'de' => 'Candidate',
                        'en' => 'Candidate',
                    ],
                    'description' => [
                        'de' => 'softgarden Candidate — Name/E-Mail = Bewerber-PII.',
                        'en' => 'softgarden candidate — name/email = applicant PII.',
                    ],
                    'grain' => [
                        'de' => 'Ein Candidate (id)',
                        'en' => 'One candidate (id)',
                    ],
                    'role' => [
                        'de' => 'Dimension (PII)',
                        'en' => 'Dimension (PII)',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'application',
                    'label' => [
                        'de' => 'Application',
                        'en' => 'Application',
                    ],
                    'description' => [
                        'de' => 'Bewerbung — Stage, Status, Job-Join.',
                        'en' => 'Application — stage, status, job join.',
                    ],
                    'grain' => [
                        'de' => 'Eine Application',
                        'en' => 'One application',
                    ],
                    'role' => [
                        'de' => 'Pipeline-Fact',
                        'en' => 'Pipeline fact',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'job',
                    'label' => [
                        'de' => 'Job / Requisition',
                        'en' => 'Job / requisition',
                    ],
                    'description' => [
                        'de' => 'Stelle / Req — Department, Location, Status.',
                        'en' => 'Job / req — department, location, status.',
                    ],
                    'grain' => [
                        'de' => 'Ein Job',
                        'en' => 'One job',
                    ],
                    'role' => [
                        'de' => 'Dimension / Fact-Anker',
                        'en' => 'Dimension / fact anchor',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'stage',
                    'label' => [
                        'de' => 'Pipeline Stage',
                        'en' => 'Pipeline stage',
                    ],
                    'description' => [
                        'de' => 'Stage im Recruiting-Funnel.',
                        'en' => 'Stage in recruiting funnel.',
                    ],
                    'grain' => [
                        'de' => 'Eine Stage',
                        'en' => 'One stage',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'interview',
                    'label' => [
                        'de' => 'Interview Meta',
                        'en' => 'Interview meta',
                    ],
                    'description' => [
                        'de' => 'Interview-Termin Meta — keine Scorecard-Freitexte als Default.',
                        'en' => 'Interview schedule meta — no scorecard free text by default.',
                    ],
                    'grain' => [
                        'de' => 'Ein Interview',
                        'en' => 'One interview',
                    ],
                    'role' => [
                        'de' => 'Process-Fact',
                        'en' => 'Process fact',
                    ],
                    'load' => 'optional',
                ],
                [
                    'id' => 'offer',
                    'label' => [
                        'de' => 'Offer Meta',
                        'en' => 'Offer meta',
                    ],
                    'description' => [
                        'de' => 'Offer Metadata — Status/Dates; Compensation restriktiv.',
                        'en' => 'Offer metadata — status/dates; compensation restricted.',
                    ],
                    'grain' => [
                        'de' => 'Ein Offer',
                        'en' => 'One offer',
                    ],
                    'role' => [
                        'de' => 'Sensitive Fact',
                        'en' => 'Sensitive fact',
                    ],
                    'load' => 'optional',
                ],
                [
                    'id' => 'recruiter',
                    'label' => [
                        'de' => 'Recruiter / User',
                        'en' => 'Recruiter / user',
                    ],
                    'description' => [
                        'de' => 'Recruiter/Hiring Manager als User-IDs.',
                        'en' => 'Recruiter/hiring manager as user ids.',
                    ],
                    'grain' => [
                        'de' => 'Ein User',
                        'en' => 'One user',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'required',
                ],
                [
                    'id' => 'source',
                    'label' => [
                        'de' => 'Source',
                        'en' => 'Source',
                    ],
                    'description' => [
                        'de' => 'Bewerbungsquelle / Channel.',
                        'en' => 'Application source / channel.',
                    ],
                    'grain' => [
                        'de' => 'Eine Source',
                        'en' => 'One source',
                    ],
                    'role' => [
                        'de' => 'Dimension',
                        'en' => 'Dimension',
                    ],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                [
                    'entity' => 'Candidate',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Candidate Join',
                        'en' => 'Candidate join',
                    ],
                ],
                [
                    'entity' => 'Candidate',
                    'name' => 'email',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'E-Mail / Bewerber-PII',
                        'en' => 'Email / applicant PII',
                    ],
                ],
                [
                    'entity' => 'Candidate',
                    'name' => 'first_name',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Vorname / PII',
                        'en' => 'First name / PII',
                    ],
                ],
                [
                    'entity' => 'Candidate',
                    'name' => 'last_name',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Nachname / PII',
                        'en' => 'Last name / PII',
                    ],
                ],
                [
                    'entity' => 'Candidate',
                    'name' => 'phone',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Telefon / PII',
                        'en' => 'Phone / PII',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Application Key',
                        'en' => 'Application key',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'candidate_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Candidate Join',
                        'en' => 'Candidate join',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'job_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Job Join',
                        'en' => 'Job join',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Active / Hired / Rejected',
                        'en' => 'Active / hired / rejected',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'stage_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Pipeline Stage',
                        'en' => 'Pipeline stage',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'applied_at',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Bewerbungsdatum',
                        'en' => 'Applied at',
                    ],
                ],
                [
                    'entity' => 'Application',
                    'name' => 'hired_at',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Hire Timestamp',
                        'en' => 'Hire timestamp',
                    ],
                ],
                [
                    'entity' => 'Job',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Job Join',
                        'en' => 'Job join',
                    ],
                ],
                [
                    'entity' => 'Job',
                    'name' => 'title',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Job Title',
                        'en' => 'Job title',
                    ],
                ],
                [
                    'entity' => 'Job',
                    'name' => 'department',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Department',
                        'en' => 'Department',
                    ],
                ],
                [
                    'entity' => 'Job',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Open / Closed / Draft',
                        'en' => 'Open / closed / draft',
                    ],
                ],
                [
                    'entity' => 'Job',
                    'name' => 'opened_at',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Opened At',
                        'en' => 'Opened at',
                    ],
                ],
                [
                    'entity' => 'Stage',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Stage Key',
                        'en' => 'Stage key',
                    ],
                ],
                [
                    'entity' => 'Stage',
                    'name' => 'name',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Stage Name',
                        'en' => 'Stage name',
                    ],
                ],
                [
                    'entity' => 'Interview',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Interview Key',
                        'en' => 'Interview key',
                    ],
                ],
                [
                    'entity' => 'Interview',
                    'name' => 'application_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Application Join',
                        'en' => 'Application join',
                    ],
                ],
                [
                    'entity' => 'Interview',
                    'name' => 'scheduled_at',
                    'role' => 'measure',
                    'why' => [
                        'de' => 'Termin',
                        'en' => 'Scheduled at',
                    ],
                ],
                [
                    'entity' => 'Offer',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Offer Key',
                        'en' => 'Offer key',
                    ],
                ],
                [
                    'entity' => 'Offer',
                    'name' => 'application_id',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Application Join',
                        'en' => 'Application join',
                    ],
                ],
                [
                    'entity' => 'Offer',
                    'name' => 'status',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Sent / Accepted / Declined',
                        'en' => 'Sent / accepted / declined',
                    ],
                ],
                [
                    'entity' => 'Offer',
                    'name' => 'salary',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Offer Amount — restriktiv',
                        'en' => 'Offer amount — restricted',
                    ],
                ],
                [
                    'entity' => 'Recruiter',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'User Join',
                        'en' => 'User join',
                    ],
                ],
                [
                    'entity' => 'Recruiter',
                    'name' => 'email',
                    'role' => 'pii',
                    'why' => [
                        'de' => 'Recruiter E-Mail / PII',
                        'en' => 'Recruiter email / PII',
                    ],
                ],
                [
                    'entity' => 'Source',
                    'name' => 'id',
                    'role' => 'key',
                    'why' => [
                        'de' => 'Source Key',
                        'en' => 'Source key',
                    ],
                ],
                [
                    'entity' => 'Source',
                    'name' => 'name',
                    'role' => 'dimension',
                    'why' => [
                        'de' => 'Source Channel',
                        'en' => 'Source channel',
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
            ],
            'dimensions' => [
                [
                    'id' => 'job_department',
                    'label' => [
                        'de' => 'Department',
                        'en' => 'Department',
                    ],
                    'grain' => [
                        'de' => 'job.department',
                        'en' => 'job.department',
                    ],
                    'notes' => [
                        'de' => 'Against HRIS Department map wenn möglich.',
                        'en' => 'Map to HRIS department when possible.',
                    ],
                ],
                [
                    'id' => 'pipeline_stage',
                    'label' => [
                        'de' => 'Pipeline Stage',
                        'en' => 'Pipeline stage',
                    ],
                    'grain' => [
                        'de' => 'stage.name / id',
                        'en' => 'stage.name / id',
                    ],
                    'notes' => [
                        'de' => 'Stage-Order für Funnel festlegen.',
                        'en' => 'Lock stage order for funnel.',
                    ],
                ],
                [
                    'id' => 'application_status',
                    'label' => [
                        'de' => 'Application Status',
                        'en' => 'Application status',
                    ],
                    'grain' => [
                        'de' => 'application.status',
                        'en' => 'application.status',
                    ],
                    'notes' => [
                        'de' => 'Hired/Rejected Definition locken.',
                        'en' => 'Lock hired/rejected definition.',
                    ],
                ],
                [
                    'id' => 'source',
                    'label' => [
                        'de' => 'Source',
                        'en' => 'Source',
                    ],
                    'grain' => [
                        'de' => 'source.name',
                        'en' => 'source.name',
                    ],
                    'notes' => [
                        'de' => 'Source Taxonomy normalisieren.',
                        'en' => 'Normalize source taxonomy.',
                    ],
                ],
                [
                    'id' => 'job_status',
                    'label' => [
                        'de' => 'Job Status',
                        'en' => 'Job status',
                    ],
                    'grain' => [
                        'de' => 'job.status',
                        'en' => 'job.status',
                    ],
                    'notes' => [
                        'de' => 'Open Jobs für Time-to-Fill.',
                        'en' => 'Open jobs for time-to-fill.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Candidate',
                    'fields' => [
                        'email',
                        'first_name',
                        'last_name',
                        'phone',
                    ],
                    'treatment' => [
                        'de' => 'Bewerber-PII; candidate id als Join.',
                        'en' => 'Applicant PII; prefer candidate id join.',
                    ],
                ],
                [
                    'entity' => 'Offer',
                    'fields' => [
                        'salary',
                    ],
                    'treatment' => [
                        'de' => 'Offer Amount restriktiv.',
                        'en' => 'Restrict offer amount.',
                    ],
                ],
                [
                    'entity' => 'Recruiter',
                    'fields' => [
                        'email',
                    ],
                    'treatment' => [
                        'de' => 'Workforce-PII; user id bevorzugen.',
                        'en' => 'Workforce PII; prefer user id.',
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
                        'de' => 'candidate id, application id, job id, email (hashed).',
                        'en' => 'candidate id, application id, job id, email (hashed).',
                    ],
                ],
                [
                    'focus' => [
                        'de' => 'Primärobjekte',
                        'en' => 'Primary objects',
                    ],
                    'notes' => [
                        'de' => 'Candidate, Application, Job, Stage, Offer Meta + Warehouse-Kopien.',
                        'en' => 'Candidate, application, job, stage, offer meta + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'applications-in-period',
                    'example' => true,
                    'label' => [
                        'de' => 'Applications',
                        'en' => 'Applications',
                    ],
                    'question' => [
                        'de' => 'Wie viele Bewerbungen in der Periode?',
                        'en' => 'How many applications in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM application WHERE applied_at IN period',
                    'grain' => [
                        'de' => 'Application',
                        'en' => 'Application',
                    ],
                    'dimensions' => [
                        'job_department',
                        'pipeline_stage',
                        'source',
                        'job_status',
                    ],
                    'fieldsUsed' => [
                        'Application.id',
                        'Application.applied_at',
                        'Job.department',
                    ],
                    'sourceHints' => [
                        'de' => 'applied_at Pflicht; Duplikate dedupen.',
                        'en' => 'applied_at required; dedupe duplicates.',
                    ],
                    'adapt' => [
                        'de' => 'Unique Candidates vs Applications.',
                        'en' => 'Unique candidates vs applications.',
                    ],
                ],
                [
                    'id' => 'hires-from-ats',
                    'example' => true,
                    'label' => [
                        'de' => 'ATS Hires',
                        'en' => 'ATS hires',
                    ],
                    'question' => [
                        'de' => 'Wie viele Hires aus dem ATS in der Periode?',
                        'en' => 'How many ATS hires in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM application WHERE status = \'hired\' AND hired_at IN period',
                    'grain' => [
                        'de' => 'Hired Application',
                        'en' => 'Hired application',
                    ],
                    'dimensions' => [
                        'job_department',
                        'source',
                        'job_status',
                    ],
                    'fieldsUsed' => [
                        'Application.status',
                        'Application.hired_at',
                    ],
                    'sourceHints' => [
                        'de' => 'hired_at vs offer accepted klären.',
                        'en' => 'Clarify hired_at vs offer accepted.',
                    ],
                    'adapt' => [
                        'de' => 'Nur External Hires.',
                        'en' => 'External hires only.',
                    ],
                ],
                [
                    'id' => 'time-to-hire',
                    'example' => false,
                    'label' => [
                        'de' => 'Avg Time to Hire',
                        'en' => 'Avg time to hire',
                    ],
                    'question' => [
                        'de' => 'Wie lange dauert es durchschnittlich von Apply bis Hire?',
                        'en' => 'What is average time from apply to hire?',
                    ],
                    'formula' => 'AVG(hired_at - applied_at) FROM application WHERE hired',
                    'grain' => [
                        'de' => 'Hired Application',
                        'en' => 'Hired application',
                    ],
                    'dimensions' => [
                        'job_department',
                        'source',
                    ],
                    'fieldsUsed' => [
                        'Application.applied_at',
                        'Application.hired_at',
                    ],
                    'sourceHints' => [
                        'de' => 'Outlier (Reopens) filtern.',
                        'en' => 'Filter reopen outliers.',
                    ],
                    'adapt' => [
                        'de' => 'Median statt Average.',
                        'en' => 'Median instead of average.',
                    ],
                ],
                [
                    'id' => 'open-jobs',
                    'example' => false,
                    'label' => [
                        'de' => 'Open Jobs',
                        'en' => 'Open jobs',
                    ],
                    'question' => [
                        'de' => 'Wie viele offene Stellen gibt es (Snapshot)?',
                        'en' => 'How many open jobs are there (snapshot)?',
                    ],
                    'formula' => 'COUNT(*) FROM job WHERE status = \'open\'',
                    'grain' => [
                        'de' => 'Open Job',
                        'en' => 'Open job',
                    ],
                    'dimensions' => [
                        'job_department',
                        'job_status',
                    ],
                    'fieldsUsed' => [
                        'Job.id',
                        'Job.status',
                    ],
                    'sourceHints' => [
                        'de' => 'Draft vs Open trennen.',
                        'en' => 'Separate draft vs open.',
                    ],
                    'adapt' => [
                        'de' => 'Nur approved reqs.',
                        'en' => 'Approved reqs only.',
                    ],
                ],
                [
                    'id' => 'offer-accept-rate',
                    'example' => false,
                    'label' => [
                        'de' => 'Offer Accept Rate',
                        'en' => 'Offer accept rate',
                    ],
                    'question' => [
                        'de' => 'Wie hoch ist die Offer-Annahmequote?',
                        'en' => 'What is the offer acceptance rate?',
                    ],
                    'formula' => 'accepted_offers / sent_offers',
                    'grain' => [
                        'de' => 'Offer',
                        'en' => 'Offer',
                    ],
                    'dimensions' => [
                        'job_department',
                        'source',
                    ],
                    'fieldsUsed' => [
                        'Offer.status',
                        'Offer.id',
                    ],
                    'sourceHints' => [
                        'de' => 'Compensation fields nicht in Public Marts.',
                        'en' => 'Do not put compensation fields in public marts.',
                    ],
                    'adapt' => [
                        'de' => 'Nur External Offers.',
                        'en' => 'External offers only.',
                    ],
                ],
            ],
            'tools' => [
                'kpi-definition',
                'pii-recommend-generator',
                'pii-policy-generator',
                'schema-yml-editor',
            ],
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];

    foreach ($products as &$product) {
        $product['tools'] = $hcmTools;
        $product['relatedPlaybooks'] = $relatedPlaybooks;
    }
    unset($product);

    return $products;
};
