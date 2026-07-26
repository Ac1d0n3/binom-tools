<?php

/**
 * Wave 12 quality overlays — HR/ATS source-native DQ, MDM, metadata guides.
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'bamboohr' => [
        'quality' => [
            'dq' => [
                [
                    'id' => 'bamboohr-employee-no-email',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Employee ohne Work E-Mail',
                        'en' => 'Employee without work email',
                    ],
                    'problem' => [
                        'de' => 'Fehlende Work E-Mail bricht Identity Joins.',
                        'en' => 'Missing work email breaks identity joins.',
                    ],
                    'prevent' => [
                        'de' => 'Provisioning: Work E-Mail Pflicht.',
                        'en' => 'Provisioning: work email required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR: Kein Create ohne Work E-Mail.',
                        'en' => 'HR: No create without work email.',
                    ],
                    'checks' => [
                        'de' => 'work_email IS NULL AND status = \'active\'',
                        'en' => 'work_email IS NULL AND status = \'active\'',
                    ],
                    'fixInSource' => [
                        'de' => 'E-Mail nachziehen.',
                        'en' => 'Backfill email.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine.',
                        'en' => 'Quarantine.',
                    ],
                ],
                [
                    'id' => 'bamboohr-orphan-manager',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Manager-ID ohne Employee',
                        'en' => 'Manager id without employee',
                    ],
                    'problem' => [
                        'de' => 'Orphan Manager Edges verzerren Span of Control.',
                        'en' => 'Orphan manager edges distort span of control.',
                    ],
                    'prevent' => [
                        'de' => 'Referenz-Integrität im HRIS.',
                        'en' => 'Referential integrity in HRIS.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR Ops: Manager muss aktiver Employee sein.',
                        'en' => 'HR ops: Manager must be an active employee.',
                    ],
                    'checks' => [
                        'de' => 'manager_id NOT IN dim_employee',
                        'en' => 'manager_id NOT IN dim_employee',
                    ],
                    'fixInSource' => [
                        'de' => 'Manager korrigieren.',
                        'en' => 'Fix manager.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown-manager dim.',
                        'en' => 'Unknown-manager dim.',
                    ],
                ],
                [
                    'id' => 'bamboohr-active-no-department',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Aktiver Employee ohne Department',
                        'en' => 'Active employee without department',
                    ],
                    'problem' => [
                        'de' => 'Fehlendes Department bricht Org-Rollups.',
                        'en' => 'Missing department breaks org rollups.',
                    ],
                    'prevent' => [
                        'de' => 'Default Department bei Hire.',
                        'en' => 'Default department on hire.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR: Department Pflicht für Active.',
                        'en' => 'HR: Department required for active.',
                    ],
                    'checks' => [
                        'de' => 'status=\'active\' AND department_id IS NULL',
                        'en' => 'status=\'active\' AND department_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Department setzen.',
                        'en' => 'Set department.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown department.',
                        'en' => 'Unknown department.',
                    ],
                ],
                [
                    'id' => 'bamboohr-termination-without-date',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Terminated ohne termination_date',
                        'en' => 'Terminated without termination_date',
                    ],
                    'problem' => [
                        'de' => 'Status terminated ohne Datum bricht Turnover-KPIs.',
                        'en' => 'Terminated without date breaks turnover KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Termination Workflow: Datum Pflicht.',
                        'en' => 'Termination workflow: date required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR: Kein Terminate ohne Datum.',
                        'en' => 'HR: No terminate without date.',
                    ],
                    'checks' => [
                        'de' => 'status=\'terminated\' AND termination_date IS NULL',
                        'en' => 'status=\'terminated\' AND termination_date IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Datum nachziehen.',
                        'en' => 'Backfill date.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Flag missing_termination_date.',
                        'en' => 'Flag missing_termination_date.',
                    ],
                ],
                [
                    'id' => 'bamboohr-comp-cleartext-mart',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Compensation Klartext in Analytics Mart',
                        'en' => 'Compensation cleartext in analytics mart',
                    ],
                    'problem' => [
                        'de' => 'Gehalt in Public Marts ist Policy-Verstoß.',
                        'en' => 'Salary in public marts is a policy violation.',
                    ],
                    'prevent' => [
                        'de' => 'Extract Allowlist; separate restricted schema.',
                        'en' => 'Extract allowlist; separate restricted schema.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Nie amount in default marts.',
                        'en' => 'Data: Never amount in default marts.',
                    ],
                    'checks' => [
                        'de' => 'column_exists(mart, \'amount\') OR column_exists(mart, \'salary\')',
                        'en' => 'column_exists(mart, \'amount\') OR column_exists(mart, \'salary\')',
                    ],
                    'fixInSource' => [
                        'de' => 'N/A — Warehouse Policy.',
                        'en' => 'N/A — warehouse policy.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Drop amount columns from public marts.',
                        'en' => 'Drop amount columns from public marts.',
                    ],
                ],
                [
                    'id' => 'bamboohr-timeoff-orphan',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Time Off ohne Employee',
                        'en' => 'Time off without employee',
                    ],
                    'problem' => [
                        'de' => 'Orphan Absences verzerren Absence KPIs.',
                        'en' => 'Orphan absences distort absence KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'API: employee_id immer joinen.',
                        'en' => 'API: always join employee_id.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Time Off braucht Employee.',
                        'en' => 'Data: Time off needs employee.',
                    ],
                    'checks' => [
                        'de' => 'time_off.employee_id IS NULL OR NOT IN dim_employee',
                        'en' => 'time_off.employee_id IS NULL OR NOT IN dim_employee',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract.',
                        'en' => 'Re-extract.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine.',
                        'en' => 'Quarantine.',
                    ],
                ],
            ],
            'mdm' => [
                [
                    'id' => 'bamboohr-employee-dup',
                    'entity' => 'Employee',
                    'title' => [
                        'de' => 'Employee Duplikate / Rehire',
                        'en' => 'Employee duplicates / rehire',
                    ],
                    'matchKeys' => [
                        'id',
                        'work_email hashed',
                        'national_id hashed',
                        'external_id',
                    ],
                    'preventInSource' => [
                        'de' => 'Rehire statt neu anlegen.',
                        'en' => 'Rehire instead of recreate.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden employee id; email-hash map.',
                        'en' => 'Golden employee id; email-hash map.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktiver Employee mit neuester Hire Activity gewinnt.',
                        'en' => 'Active employee with latest hire activity wins.',
                    ],
                ],
                [
                    'id' => 'bamboohr-dept-map',
                    'entity' => 'Department',
                    'title' => [
                        'de' => 'Department Rename / Drift',
                        'en' => 'Department rename / drift',
                    ],
                    'matchKeys' => [
                        'department id',
                        'name',
                        'parent_id',
                    ],
                    'preventInSource' => [
                        'de' => 'Controlled renames.',
                        'en' => 'Controlled renames.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Canonical department id; SCD2 name.',
                        'en' => 'Canonical department id; SCD2 name.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktueller Name gewinnt.',
                        'en' => 'Current name wins.',
                    ],
                ],
            ],
            'productSettings' => [
                [
                    'id' => 'bamboohr-no-sensitive-bodies',
                    'area' => [
                        'de' => 'Extract / Integration',
                        'en' => 'Extract / Integration',
                    ],
                    'setting' => [
                        'de' => 'Allowlist: Meta/IDs — keine Bodies/Compensation Klartext in Default',
                        'en' => 'Allowlist: meta/ids — no bodies/compensation cleartext by default',
                    ],
                    'why' => [
                        'de' => 'Analytics ohne Policy-Bruch.',
                        'en' => 'Analytics without policy breach.',
                    ],
                    'how' => [
                        'de' => 'Field allowlist; restricted schema für sensitive.',
                        'en' => 'Field allowlist; restricted schema for sensitive.',
                    ],
                ],
                [
                    'id' => 'bamboohr-retention',
                    'area' => [
                        'de' => 'Privacy / Retention',
                        'en' => 'Privacy / Retention',
                    ],
                    'setting' => [
                        'de' => 'Retention für Bewerber-/Employee-PII definieren',
                        'en' => 'Define retention for applicant/employee PII',
                    ],
                    'why' => [
                        'de' => 'DSGVO/Löschpflichten steuerbar.',
                        'en' => 'GDPR/erasure controllable.',
                    ],
                    'how' => [
                        'de' => 'TTL + legal hold flags; hard delete playbooks.',
                        'en' => 'TTL + legal hold flags; hard delete playbooks.',
                    ],
                ],
                [
                    'id' => 'bamboohr-sandbox-split',
                    'area' => [
                        'de' => 'Tenants',
                        'en' => 'Tenants',
                    ],
                    'setting' => [
                        'de' => 'Sandbox/Test strikt von Prod-Marts trennen',
                        'en' => 'Strictly separate sandbox/test from prod marts',
                    ],
                    'why' => [
                        'de' => 'Keine Fake-Headcount/Pipeline in Prod KPIs.',
                        'en' => 'No fake headcount/pipeline in prod KPIs.',
                    ],
                    'how' => [
                        'de' => 'Tenant id filter; separate warehouses.',
                        'en' => 'Tenant id filter; separate warehouses.',
                    ],
                ],
            ],
            'metadata' => [
                [
                    'id' => 'bamboohr-api-model',
                    'kind' => [
                        'de' => 'API object model',
                        'en' => 'API object model',
                    ],
                    'where' => [
                        'de' => 'BambooHR API docs',
                        'en' => 'BambooHR API docs',
                    ],
                    'how' => [
                        'de' => 'Entities/Fields inventarisieren; PII Allowlist.',
                        'en' => 'Inventory entities/fields; PII allowlist.',
                    ],
                    'useFor' => [
                        'de' => 'Extract design, join keys, PII tags.',
                        'en' => 'Extract design, join keys, PII tags.',
                    ],
                    'watchouts' => [
                        'de' => 'Compensation/CV/Notes nie Default-Select.',
                        'en' => 'Never default-select compensation/CV/notes.',
                    ],
                ],
                [
                    'id' => 'bamboohr-org-or-pipeline',
                    'kind' => [
                        'de' => 'Org hierarchy',
                        'en' => 'Org hierarchy',
                    ],
                    'where' => [
                        'de' => 'BambooHR admin config',
                        'en' => 'BambooHR admin config',
                    ],
                    'how' => [
                        'de' => 'Struktur und Codes dokumentieren.',
                        'en' => 'Document structure and codes.',
                    ],
                    'useFor' => [
                        'de' => 'Dims und DQ.',
                        'en' => 'Dims and DQ.',
                    ],
                    'watchouts' => [
                        'de' => 'Renames — SCD2.',
                        'en' => 'Renames — SCD2.',
                    ],
                ],
                [
                    'id' => 'bamboohr-webhooks',
                    'kind' => [
                        'de' => 'Webhooks / events',
                        'en' => 'Webhooks / events',
                    ],
                    'where' => [
                        'de' => 'BambooHR webhooks',
                        'en' => 'BambooHR webhooks',
                    ],
                    'how' => [
                        'de' => 'Event Types Allowlist; Payload minimize.',
                        'en' => 'Allowlist event types; minimize payload.',
                    ],
                    'useFor' => [
                        'de' => 'Near-real-time facts.',
                        'en' => 'Near-real-time facts.',
                    ],
                    'watchouts' => [
                        'de' => 'Webhook Payloads können volle PII enthalten.',
                        'en' => 'Webhook payloads can contain full PII.',
                    ],
                ],
                [
                    'id' => 'bamboohr-exports',
                    'kind' => [
                        'de' => 'CSV / BI exports',
                        'en' => 'CSV / BI exports',
                    ],
                    'where' => [
                        'de' => 'BambooHR reports / exports',
                        'en' => 'BambooHR reports / exports',
                    ],
                    'how' => [
                        'de' => 'Export-Templates ohne sensitive Spalten.',
                        'en' => 'Export templates without sensitive columns.',
                    ],
                    'useFor' => [
                        'de' => 'Shadow-IT erkennen; offizielle Extract bevorzugen.',
                        'en' => 'Detect shadow IT; prefer official extract.',
                    ],
                    'watchouts' => [
                        'de' => 'Exports sind zweite PII-Kopie.',
                        'en' => 'Exports are a second PII copy.',
                    ],
                ],
            ],
        ],
        'tools' => [
            'dbt-dq-rules-generator',
            'dbt-dq-macro-generator',
            'dbt-dq-history-generator',
            'schema-yml-editor',
        ],
    ],
    'hibob' => [
        'quality' => [
            'dq' => [
                [
                    'id' => 'hibob-employee-no-email',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Employee ohne Work E-Mail',
                        'en' => 'Employee without work email',
                    ],
                    'problem' => [
                        'de' => 'Fehlende Work E-Mail bricht Identity Joins.',
                        'en' => 'Missing work email breaks identity joins.',
                    ],
                    'prevent' => [
                        'de' => 'Provisioning: Work E-Mail Pflicht.',
                        'en' => 'Provisioning: work email required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR: Kein Create ohne Work E-Mail.',
                        'en' => 'HR: No create without work email.',
                    ],
                    'checks' => [
                        'de' => 'work_email IS NULL AND status = \'active\'',
                        'en' => 'work_email IS NULL AND status = \'active\'',
                    ],
                    'fixInSource' => [
                        'de' => 'E-Mail nachziehen.',
                        'en' => 'Backfill email.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine.',
                        'en' => 'Quarantine.',
                    ],
                ],
                [
                    'id' => 'hibob-orphan-manager',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Manager-ID ohne Employee',
                        'en' => 'Manager id without employee',
                    ],
                    'problem' => [
                        'de' => 'Orphan Manager Edges verzerren Span of Control.',
                        'en' => 'Orphan manager edges distort span of control.',
                    ],
                    'prevent' => [
                        'de' => 'Referenz-Integrität im HRIS.',
                        'en' => 'Referential integrity in HRIS.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR Ops: Manager muss aktiver Employee sein.',
                        'en' => 'HR ops: Manager must be an active employee.',
                    ],
                    'checks' => [
                        'de' => 'manager_id NOT IN dim_employee',
                        'en' => 'manager_id NOT IN dim_employee',
                    ],
                    'fixInSource' => [
                        'de' => 'Manager korrigieren.',
                        'en' => 'Fix manager.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown-manager dim.',
                        'en' => 'Unknown-manager dim.',
                    ],
                ],
                [
                    'id' => 'hibob-active-no-department',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Aktiver Employee ohne Department',
                        'en' => 'Active employee without department',
                    ],
                    'problem' => [
                        'de' => 'Fehlendes Department bricht Org-Rollups.',
                        'en' => 'Missing department breaks org rollups.',
                    ],
                    'prevent' => [
                        'de' => 'Default Department bei Hire.',
                        'en' => 'Default department on hire.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR: Department Pflicht für Active.',
                        'en' => 'HR: Department required for active.',
                    ],
                    'checks' => [
                        'de' => 'status=\'active\' AND department_id IS NULL',
                        'en' => 'status=\'active\' AND department_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Department setzen.',
                        'en' => 'Set department.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown department.',
                        'en' => 'Unknown department.',
                    ],
                ],
                [
                    'id' => 'hibob-termination-without-date',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Terminated ohne termination_date',
                        'en' => 'Terminated without termination_date',
                    ],
                    'problem' => [
                        'de' => 'Status terminated ohne Datum bricht Turnover-KPIs.',
                        'en' => 'Terminated without date breaks turnover KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Termination Workflow: Datum Pflicht.',
                        'en' => 'Termination workflow: date required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR: Kein Terminate ohne Datum.',
                        'en' => 'HR: No terminate without date.',
                    ],
                    'checks' => [
                        'de' => 'status=\'terminated\' AND termination_date IS NULL',
                        'en' => 'status=\'terminated\' AND termination_date IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Datum nachziehen.',
                        'en' => 'Backfill date.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Flag missing_termination_date.',
                        'en' => 'Flag missing_termination_date.',
                    ],
                ],
                [
                    'id' => 'hibob-comp-cleartext-mart',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Compensation Klartext in Analytics Mart',
                        'en' => 'Compensation cleartext in analytics mart',
                    ],
                    'problem' => [
                        'de' => 'Gehalt in Public Marts ist Policy-Verstoß.',
                        'en' => 'Salary in public marts is a policy violation.',
                    ],
                    'prevent' => [
                        'de' => 'Extract Allowlist; separate restricted schema.',
                        'en' => 'Extract allowlist; separate restricted schema.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Nie amount in default marts.',
                        'en' => 'Data: Never amount in default marts.',
                    ],
                    'checks' => [
                        'de' => 'column_exists(mart, \'amount\') OR column_exists(mart, \'salary\')',
                        'en' => 'column_exists(mart, \'amount\') OR column_exists(mart, \'salary\')',
                    ],
                    'fixInSource' => [
                        'de' => 'N/A — Warehouse Policy.',
                        'en' => 'N/A — warehouse policy.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Drop amount columns from public marts.',
                        'en' => 'Drop amount columns from public marts.',
                    ],
                ],
                [
                    'id' => 'hibob-timeoff-orphan',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Time Off ohne Employee',
                        'en' => 'Time off without employee',
                    ],
                    'problem' => [
                        'de' => 'Orphan Absences verzerren Absence KPIs.',
                        'en' => 'Orphan absences distort absence KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'API: employee_id immer joinen.',
                        'en' => 'API: always join employee_id.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Time Off braucht Employee.',
                        'en' => 'Data: Time off needs employee.',
                    ],
                    'checks' => [
                        'de' => 'time_off.employee_id IS NULL OR NOT IN dim_employee',
                        'en' => 'time_off.employee_id IS NULL OR NOT IN dim_employee',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract.',
                        'en' => 'Re-extract.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine.',
                        'en' => 'Quarantine.',
                    ],
                ],
            ],
            'mdm' => [
                [
                    'id' => 'hibob-employee-dup',
                    'entity' => 'Employee',
                    'title' => [
                        'de' => 'Employee Duplikate / Rehire',
                        'en' => 'Employee duplicates / rehire',
                    ],
                    'matchKeys' => [
                        'id',
                        'work_email hashed',
                        'national_id hashed',
                        'external_id',
                    ],
                    'preventInSource' => [
                        'de' => 'Rehire statt neu anlegen.',
                        'en' => 'Rehire instead of recreate.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden employee id; email-hash map.',
                        'en' => 'Golden employee id; email-hash map.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktiver Employee mit neuester Hire Activity gewinnt.',
                        'en' => 'Active employee with latest hire activity wins.',
                    ],
                ],
                [
                    'id' => 'hibob-dept-map',
                    'entity' => 'Department',
                    'title' => [
                        'de' => 'Department Rename / Drift',
                        'en' => 'Department rename / drift',
                    ],
                    'matchKeys' => [
                        'department id',
                        'name',
                        'parent_id',
                    ],
                    'preventInSource' => [
                        'de' => 'Controlled renames.',
                        'en' => 'Controlled renames.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Canonical department id; SCD2 name.',
                        'en' => 'Canonical department id; SCD2 name.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktueller Name gewinnt.',
                        'en' => 'Current name wins.',
                    ],
                ],
            ],
            'productSettings' => [
                [
                    'id' => 'hibob-no-sensitive-bodies',
                    'area' => [
                        'de' => 'Extract / Integration',
                        'en' => 'Extract / Integration',
                    ],
                    'setting' => [
                        'de' => 'Allowlist: Meta/IDs — keine Bodies/Compensation Klartext in Default',
                        'en' => 'Allowlist: meta/ids — no bodies/compensation cleartext by default',
                    ],
                    'why' => [
                        'de' => 'Analytics ohne Policy-Bruch.',
                        'en' => 'Analytics without policy breach.',
                    ],
                    'how' => [
                        'de' => 'Field allowlist; restricted schema für sensitive.',
                        'en' => 'Field allowlist; restricted schema for sensitive.',
                    ],
                ],
                [
                    'id' => 'hibob-retention',
                    'area' => [
                        'de' => 'Privacy / Retention',
                        'en' => 'Privacy / Retention',
                    ],
                    'setting' => [
                        'de' => 'Retention für Bewerber-/Employee-PII definieren',
                        'en' => 'Define retention for applicant/employee PII',
                    ],
                    'why' => [
                        'de' => 'DSGVO/Löschpflichten steuerbar.',
                        'en' => 'GDPR/erasure controllable.',
                    ],
                    'how' => [
                        'de' => 'TTL + legal hold flags; hard delete playbooks.',
                        'en' => 'TTL + legal hold flags; hard delete playbooks.',
                    ],
                ],
                [
                    'id' => 'hibob-sandbox-split',
                    'area' => [
                        'de' => 'Tenants',
                        'en' => 'Tenants',
                    ],
                    'setting' => [
                        'de' => 'Sandbox/Test strikt von Prod-Marts trennen',
                        'en' => 'Strictly separate sandbox/test from prod marts',
                    ],
                    'why' => [
                        'de' => 'Keine Fake-Headcount/Pipeline in Prod KPIs.',
                        'en' => 'No fake headcount/pipeline in prod KPIs.',
                    ],
                    'how' => [
                        'de' => 'Tenant id filter; separate warehouses.',
                        'en' => 'Tenant id filter; separate warehouses.',
                    ],
                ],
            ],
            'metadata' => [
                [
                    'id' => 'hibob-api-model',
                    'kind' => [
                        'de' => 'API object model',
                        'en' => 'API object model',
                    ],
                    'where' => [
                        'de' => 'HiBob API docs',
                        'en' => 'HiBob API docs',
                    ],
                    'how' => [
                        'de' => 'Entities/Fields inventarisieren; PII Allowlist.',
                        'en' => 'Inventory entities/fields; PII allowlist.',
                    ],
                    'useFor' => [
                        'de' => 'Extract design, join keys, PII tags.',
                        'en' => 'Extract design, join keys, PII tags.',
                    ],
                    'watchouts' => [
                        'de' => 'Compensation/CV/Notes nie Default-Select.',
                        'en' => 'Never default-select compensation/CV/notes.',
                    ],
                ],
                [
                    'id' => 'hibob-org-or-pipeline',
                    'kind' => [
                        'de' => 'Org hierarchy',
                        'en' => 'Org hierarchy',
                    ],
                    'where' => [
                        'de' => 'HiBob admin config',
                        'en' => 'HiBob admin config',
                    ],
                    'how' => [
                        'de' => 'Struktur und Codes dokumentieren.',
                        'en' => 'Document structure and codes.',
                    ],
                    'useFor' => [
                        'de' => 'Dims und DQ.',
                        'en' => 'Dims and DQ.',
                    ],
                    'watchouts' => [
                        'de' => 'Renames — SCD2.',
                        'en' => 'Renames — SCD2.',
                    ],
                ],
                [
                    'id' => 'hibob-webhooks',
                    'kind' => [
                        'de' => 'Webhooks / events',
                        'en' => 'Webhooks / events',
                    ],
                    'where' => [
                        'de' => 'HiBob webhooks',
                        'en' => 'HiBob webhooks',
                    ],
                    'how' => [
                        'de' => 'Event Types Allowlist; Payload minimize.',
                        'en' => 'Allowlist event types; minimize payload.',
                    ],
                    'useFor' => [
                        'de' => 'Near-real-time facts.',
                        'en' => 'Near-real-time facts.',
                    ],
                    'watchouts' => [
                        'de' => 'Webhook Payloads können volle PII enthalten.',
                        'en' => 'Webhook payloads can contain full PII.',
                    ],
                ],
                [
                    'id' => 'hibob-exports',
                    'kind' => [
                        'de' => 'CSV / BI exports',
                        'en' => 'CSV / BI exports',
                    ],
                    'where' => [
                        'de' => 'HiBob reports / exports',
                        'en' => 'HiBob reports / exports',
                    ],
                    'how' => [
                        'de' => 'Export-Templates ohne sensitive Spalten.',
                        'en' => 'Export templates without sensitive columns.',
                    ],
                    'useFor' => [
                        'de' => 'Shadow-IT erkennen; offizielle Extract bevorzugen.',
                        'en' => 'Detect shadow IT; prefer official extract.',
                    ],
                    'watchouts' => [
                        'de' => 'Exports sind zweite PII-Kopie.',
                        'en' => 'Exports are a second PII copy.',
                    ],
                ],
            ],
        ],
        'tools' => [
            'dbt-dq-rules-generator',
            'dbt-dq-macro-generator',
            'dbt-dq-history-generator',
            'schema-yml-editor',
        ],
    ],
    'factorial' => [
        'quality' => [
            'dq' => [
                [
                    'id' => 'factorial-employee-no-email',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Employee ohne Work E-Mail',
                        'en' => 'Employee without work email',
                    ],
                    'problem' => [
                        'de' => 'Fehlende Work E-Mail bricht Identity Joins.',
                        'en' => 'Missing work email breaks identity joins.',
                    ],
                    'prevent' => [
                        'de' => 'Provisioning: Work E-Mail Pflicht.',
                        'en' => 'Provisioning: work email required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR: Kein Create ohne Work E-Mail.',
                        'en' => 'HR: No create without work email.',
                    ],
                    'checks' => [
                        'de' => 'work_email IS NULL AND status = \'active\'',
                        'en' => 'work_email IS NULL AND status = \'active\'',
                    ],
                    'fixInSource' => [
                        'de' => 'E-Mail nachziehen.',
                        'en' => 'Backfill email.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine.',
                        'en' => 'Quarantine.',
                    ],
                ],
                [
                    'id' => 'factorial-orphan-manager',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Manager-ID ohne Employee',
                        'en' => 'Manager id without employee',
                    ],
                    'problem' => [
                        'de' => 'Orphan Manager Edges verzerren Span of Control.',
                        'en' => 'Orphan manager edges distort span of control.',
                    ],
                    'prevent' => [
                        'de' => 'Referenz-Integrität im HRIS.',
                        'en' => 'Referential integrity in HRIS.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR Ops: Manager muss aktiver Employee sein.',
                        'en' => 'HR ops: Manager must be an active employee.',
                    ],
                    'checks' => [
                        'de' => 'manager_id NOT IN dim_employee',
                        'en' => 'manager_id NOT IN dim_employee',
                    ],
                    'fixInSource' => [
                        'de' => 'Manager korrigieren.',
                        'en' => 'Fix manager.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown-manager dim.',
                        'en' => 'Unknown-manager dim.',
                    ],
                ],
                [
                    'id' => 'factorial-active-no-department',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Aktiver Employee ohne Department',
                        'en' => 'Active employee without department',
                    ],
                    'problem' => [
                        'de' => 'Fehlendes Department bricht Org-Rollups.',
                        'en' => 'Missing department breaks org rollups.',
                    ],
                    'prevent' => [
                        'de' => 'Default Department bei Hire.',
                        'en' => 'Default department on hire.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR: Department Pflicht für Active.',
                        'en' => 'HR: Department required for active.',
                    ],
                    'checks' => [
                        'de' => 'status=\'active\' AND department_id IS NULL',
                        'en' => 'status=\'active\' AND department_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Department setzen.',
                        'en' => 'Set department.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown department.',
                        'en' => 'Unknown department.',
                    ],
                ],
                [
                    'id' => 'factorial-termination-without-date',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Terminated ohne termination_date',
                        'en' => 'Terminated without termination_date',
                    ],
                    'problem' => [
                        'de' => 'Status terminated ohne Datum bricht Turnover-KPIs.',
                        'en' => 'Terminated without date breaks turnover KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Termination Workflow: Datum Pflicht.',
                        'en' => 'Termination workflow: date required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'HR: Kein Terminate ohne Datum.',
                        'en' => 'HR: No terminate without date.',
                    ],
                    'checks' => [
                        'de' => 'status=\'terminated\' AND termination_date IS NULL',
                        'en' => 'status=\'terminated\' AND termination_date IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Datum nachziehen.',
                        'en' => 'Backfill date.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Flag missing_termination_date.',
                        'en' => 'Flag missing_termination_date.',
                    ],
                ],
                [
                    'id' => 'factorial-comp-cleartext-mart',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Compensation Klartext in Analytics Mart',
                        'en' => 'Compensation cleartext in analytics mart',
                    ],
                    'problem' => [
                        'de' => 'Gehalt in Public Marts ist Policy-Verstoß.',
                        'en' => 'Salary in public marts is a policy violation.',
                    ],
                    'prevent' => [
                        'de' => 'Extract Allowlist; separate restricted schema.',
                        'en' => 'Extract allowlist; separate restricted schema.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Nie amount in default marts.',
                        'en' => 'Data: Never amount in default marts.',
                    ],
                    'checks' => [
                        'de' => 'column_exists(mart, \'amount\') OR column_exists(mart, \'salary\')',
                        'en' => 'column_exists(mart, \'amount\') OR column_exists(mart, \'salary\')',
                    ],
                    'fixInSource' => [
                        'de' => 'N/A — Warehouse Policy.',
                        'en' => 'N/A — warehouse policy.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Drop amount columns from public marts.',
                        'en' => 'Drop amount columns from public marts.',
                    ],
                ],
                [
                    'id' => 'factorial-timeoff-orphan',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Time Off ohne Employee',
                        'en' => 'Time off without employee',
                    ],
                    'problem' => [
                        'de' => 'Orphan Absences verzerren Absence KPIs.',
                        'en' => 'Orphan absences distort absence KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'API: employee_id immer joinen.',
                        'en' => 'API: always join employee_id.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Time Off braucht Employee.',
                        'en' => 'Data: Time off needs employee.',
                    ],
                    'checks' => [
                        'de' => 'time_off.employee_id IS NULL OR NOT IN dim_employee',
                        'en' => 'time_off.employee_id IS NULL OR NOT IN dim_employee',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract.',
                        'en' => 'Re-extract.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine.',
                        'en' => 'Quarantine.',
                    ],
                ],
            ],
            'mdm' => [
                [
                    'id' => 'factorial-employee-dup',
                    'entity' => 'Employee',
                    'title' => [
                        'de' => 'Employee Duplikate / Rehire',
                        'en' => 'Employee duplicates / rehire',
                    ],
                    'matchKeys' => [
                        'id',
                        'work_email hashed',
                        'national_id hashed',
                        'external_id',
                    ],
                    'preventInSource' => [
                        'de' => 'Rehire statt neu anlegen.',
                        'en' => 'Rehire instead of recreate.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden employee id; email-hash map.',
                        'en' => 'Golden employee id; email-hash map.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktiver Employee mit neuester Hire Activity gewinnt.',
                        'en' => 'Active employee with latest hire activity wins.',
                    ],
                ],
                [
                    'id' => 'factorial-dept-map',
                    'entity' => 'Department',
                    'title' => [
                        'de' => 'Department Rename / Drift',
                        'en' => 'Department rename / drift',
                    ],
                    'matchKeys' => [
                        'department id',
                        'name',
                        'parent_id',
                    ],
                    'preventInSource' => [
                        'de' => 'Controlled renames.',
                        'en' => 'Controlled renames.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Canonical department id; SCD2 name.',
                        'en' => 'Canonical department id; SCD2 name.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktueller Name gewinnt.',
                        'en' => 'Current name wins.',
                    ],
                ],
            ],
            'productSettings' => [
                [
                    'id' => 'factorial-no-sensitive-bodies',
                    'area' => [
                        'de' => 'Extract / Integration',
                        'en' => 'Extract / Integration',
                    ],
                    'setting' => [
                        'de' => 'Allowlist: Meta/IDs — keine Bodies/Compensation Klartext in Default',
                        'en' => 'Allowlist: meta/ids — no bodies/compensation cleartext by default',
                    ],
                    'why' => [
                        'de' => 'Analytics ohne Policy-Bruch.',
                        'en' => 'Analytics without policy breach.',
                    ],
                    'how' => [
                        'de' => 'Field allowlist; restricted schema für sensitive.',
                        'en' => 'Field allowlist; restricted schema for sensitive.',
                    ],
                ],
                [
                    'id' => 'factorial-retention',
                    'area' => [
                        'de' => 'Privacy / Retention',
                        'en' => 'Privacy / Retention',
                    ],
                    'setting' => [
                        'de' => 'Retention für Bewerber-/Employee-PII definieren',
                        'en' => 'Define retention for applicant/employee PII',
                    ],
                    'why' => [
                        'de' => 'DSGVO/Löschpflichten steuerbar.',
                        'en' => 'GDPR/erasure controllable.',
                    ],
                    'how' => [
                        'de' => 'TTL + legal hold flags; hard delete playbooks.',
                        'en' => 'TTL + legal hold flags; hard delete playbooks.',
                    ],
                ],
                [
                    'id' => 'factorial-sandbox-split',
                    'area' => [
                        'de' => 'Tenants',
                        'en' => 'Tenants',
                    ],
                    'setting' => [
                        'de' => 'Sandbox/Test strikt von Prod-Marts trennen',
                        'en' => 'Strictly separate sandbox/test from prod marts',
                    ],
                    'why' => [
                        'de' => 'Keine Fake-Headcount/Pipeline in Prod KPIs.',
                        'en' => 'No fake headcount/pipeline in prod KPIs.',
                    ],
                    'how' => [
                        'de' => 'Tenant id filter; separate warehouses.',
                        'en' => 'Tenant id filter; separate warehouses.',
                    ],
                ],
            ],
            'metadata' => [
                [
                    'id' => 'factorial-api-model',
                    'kind' => [
                        'de' => 'API object model',
                        'en' => 'API object model',
                    ],
                    'where' => [
                        'de' => 'Factorial API docs',
                        'en' => 'Factorial API docs',
                    ],
                    'how' => [
                        'de' => 'Entities/Fields inventarisieren; PII Allowlist.',
                        'en' => 'Inventory entities/fields; PII allowlist.',
                    ],
                    'useFor' => [
                        'de' => 'Extract design, join keys, PII tags.',
                        'en' => 'Extract design, join keys, PII tags.',
                    ],
                    'watchouts' => [
                        'de' => 'Compensation/CV/Notes nie Default-Select.',
                        'en' => 'Never default-select compensation/CV/notes.',
                    ],
                ],
                [
                    'id' => 'factorial-org-or-pipeline',
                    'kind' => [
                        'de' => 'Org hierarchy',
                        'en' => 'Org hierarchy',
                    ],
                    'where' => [
                        'de' => 'Factorial admin config',
                        'en' => 'Factorial admin config',
                    ],
                    'how' => [
                        'de' => 'Struktur und Codes dokumentieren.',
                        'en' => 'Document structure and codes.',
                    ],
                    'useFor' => [
                        'de' => 'Dims und DQ.',
                        'en' => 'Dims and DQ.',
                    ],
                    'watchouts' => [
                        'de' => 'Renames — SCD2.',
                        'en' => 'Renames — SCD2.',
                    ],
                ],
                [
                    'id' => 'factorial-webhooks',
                    'kind' => [
                        'de' => 'Webhooks / events',
                        'en' => 'Webhooks / events',
                    ],
                    'where' => [
                        'de' => 'Factorial webhooks',
                        'en' => 'Factorial webhooks',
                    ],
                    'how' => [
                        'de' => 'Event Types Allowlist; Payload minimize.',
                        'en' => 'Allowlist event types; minimize payload.',
                    ],
                    'useFor' => [
                        'de' => 'Near-real-time facts.',
                        'en' => 'Near-real-time facts.',
                    ],
                    'watchouts' => [
                        'de' => 'Webhook Payloads können volle PII enthalten.',
                        'en' => 'Webhook payloads can contain full PII.',
                    ],
                ],
                [
                    'id' => 'factorial-exports',
                    'kind' => [
                        'de' => 'CSV / BI exports',
                        'en' => 'CSV / BI exports',
                    ],
                    'where' => [
                        'de' => 'Factorial reports / exports',
                        'en' => 'Factorial reports / exports',
                    ],
                    'how' => [
                        'de' => 'Export-Templates ohne sensitive Spalten.',
                        'en' => 'Export templates without sensitive columns.',
                    ],
                    'useFor' => [
                        'de' => 'Shadow-IT erkennen; offizielle Extract bevorzugen.',
                        'en' => 'Detect shadow IT; prefer official extract.',
                    ],
                    'watchouts' => [
                        'de' => 'Exports sind zweite PII-Kopie.',
                        'en' => 'Exports are a second PII copy.',
                    ],
                ],
            ],
        ],
        'tools' => [
            'dbt-dq-rules-generator',
            'dbt-dq-macro-generator',
            'dbt-dq-history-generator',
            'schema-yml-editor',
        ],
    ],
    'greenhouse' => [
        'quality' => [
            'dq' => [
                [
                    'id' => 'greenhouse-app-no-candidate',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Application ohne Candidate',
                        'en' => 'Application without candidate',
                    ],
                    'problem' => [
                        'de' => 'Orphan Applications brechen Pipeline KPIs.',
                        'en' => 'Orphan applications break pipeline KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'API Extract: candidate immer mitladen.',
                        'en' => 'API extract: always load candidate.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'TA Ops: Application braucht Candidate.',
                        'en' => 'TA ops: Application needs candidate.',
                    ],
                    'checks' => [
                        'de' => 'application.candidate_id IS NULL OR NOT IN dim_candidate',
                        'en' => 'application.candidate_id IS NULL OR NOT IN dim_candidate',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract.',
                        'en' => 'Re-extract.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine.',
                        'en' => 'Quarantine.',
                    ],
                ],
                [
                    'id' => 'greenhouse-app-no-job',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Application ohne Job',
                        'en' => 'Application without job',
                    ],
                    'problem' => [
                        'de' => 'Applications ohne Job verzerren Funnel je Stelle.',
                        'en' => 'Applications without job distort per-job funnel.',
                    ],
                    'prevent' => [
                        'de' => 'Job Pflicht bei Apply.',
                        'en' => 'Job required on apply.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Recruiting: Keine Job-losen Apps in Prod.',
                        'en' => 'Recruiting: No job-less apps in prod.',
                    ],
                    'checks' => [
                        'de' => 'application.job_id IS NULL',
                        'en' => 'application.job_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Job zuweisen oder archivieren.',
                        'en' => 'Assign job or archive.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown job dim.',
                        'en' => 'Unknown job dim.',
                    ],
                ],
                [
                    'id' => 'greenhouse-hired-no-hired-at',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Hired ohne hired_at',
                        'en' => 'Hired without hired_at',
                    ],
                    'problem' => [
                        'de' => 'Hire Status ohne Timestamp bricht Time-to-Hire.',
                        'en' => 'Hire status without timestamp breaks time-to-hire.',
                    ],
                    'prevent' => [
                        'de' => 'Hire Workflow: hired_at Pflicht.',
                        'en' => 'Hire workflow: hired_at required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'TA: Hire Event datieren.',
                        'en' => 'TA: Date the hire event.',
                    ],
                    'checks' => [
                        'de' => 'status=\'hired\' AND hired_at IS NULL',
                        'en' => 'status=\'hired\' AND hired_at IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Datum nachziehen.',
                        'en' => 'Backfill date.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Flag missing_hired_at.',
                        'en' => 'Flag missing_hired_at.',
                    ],
                ],
                [
                    'id' => 'greenhouse-duplicate-candidate-email',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Doppelte Candidate E-Mails',
                        'en' => 'Duplicate candidate emails',
                    ],
                    'problem' => [
                        'de' => 'Duplikate verzerren Unique Candidate Counts.',
                        'en' => 'Duplicates distort unique candidate counts.',
                    ],
                    'prevent' => [
                        'de' => 'Dedup Policy im ATS.',
                        'en' => 'Dedup policy in ATS.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'TA: Vor Create suchen.',
                        'en' => 'TA: Search before create.',
                    ],
                    'checks' => [
                        'de' => 'COUNT(*) GROUP BY lower(email) HAVING COUNT>1',
                        'en' => 'COUNT(*) GROUP BY lower(email) HAVING COUNT>1',
                    ],
                    'fixInSource' => [
                        'de' => 'Merge Candidates.',
                        'en' => 'Merge candidates.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Golden candidate via email hash.',
                        'en' => 'Golden candidate via email hash.',
                    ],
                ],
                [
                    'id' => 'greenhouse-offer-salary-in-public-mart',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Offer Salary in Public Mart',
                        'en' => 'Offer salary in public mart',
                    ],
                    'problem' => [
                        'de' => 'Offer Beträge in Public Marts sind Policy-Verstoß.',
                        'en' => 'Offer amounts in public marts are a policy violation.',
                    ],
                    'prevent' => [
                        'de' => 'Allowlist; restricted schema.',
                        'en' => 'Allowlist; restricted schema.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Keine salary Spalte in default marts.',
                        'en' => 'Data: No salary column in default marts.',
                    ],
                    'checks' => [
                        'de' => 'column_exists(public_mart,\'salary\')',
                        'en' => 'column_exists(public_mart,\'salary\')',
                    ],
                    'fixInSource' => [
                        'de' => 'N/A — Warehouse Policy.',
                        'en' => 'N/A — warehouse policy.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Remove salary from public marts.',
                        'en' => 'Remove salary from public marts.',
                    ],
                ],
                [
                    'id' => 'greenhouse-stage-unknown',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Application Stage unbekannt',
                        'en' => 'Application stage unknown',
                    ],
                    'problem' => [
                        'de' => 'Unbekannte Stages brechen Funnel-Order.',
                        'en' => 'Unknown stages break funnel order.',
                    ],
                    'prevent' => [
                        'de' => 'Stage Catalog pflegen.',
                        'en' => 'Maintain stage catalog.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'TA Ops: Keine Ad-hoc Stages.',
                        'en' => 'TA ops: No ad-hoc stages.',
                    ],
                    'checks' => [
                        'de' => 'stage_id NOT IN dim_stage',
                        'en' => 'stage_id NOT IN dim_stage',
                    ],
                    'fixInSource' => [
                        'de' => 'Stage map fixen.',
                        'en' => 'Fix stage map.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown stage bucket.',
                        'en' => 'Unknown stage bucket.',
                    ],
                ],
            ],
            'mdm' => [
                [
                    'id' => 'greenhouse-candidate-dup',
                    'entity' => 'Candidate',
                    'title' => [
                        'de' => 'Candidate Duplikate',
                        'en' => 'Candidate duplicates',
                    ],
                    'matchKeys' => [
                        'id',
                        'email hashed',
                        'phone hashed',
                    ],
                    'preventInSource' => [
                        'de' => 'Dedup before create.',
                        'en' => 'Dedup before create.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden candidate id; email-hash map.',
                        'en' => 'Golden candidate id; email-hash map.',
                    ],
                    'survivorship' => [
                        'de' => 'Neueste Application Activity gewinnt.',
                        'en' => 'Latest application activity wins.',
                    ],
                ],
                [
                    'id' => 'greenhouse-job-map',
                    'entity' => 'Job',
                    'title' => [
                        'de' => 'Job / Requisition Mapping',
                        'en' => 'Job / requisition mapping',
                    ],
                    'matchKeys' => [
                        'job id',
                        'requisition_number',
                        'title+department',
                    ],
                    'preventInSource' => [
                        'de' => 'Stable job id; track reopen.',
                        'en' => 'Stable job id; track reopen.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Canonical job id; title SCD2.',
                        'en' => 'Canonical job id; title SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Open Job mit aktueller Title gewinnt.',
                        'en' => 'Open job with current title wins.',
                    ],
                ],
            ],
            'productSettings' => [
                [
                    'id' => 'greenhouse-no-sensitive-bodies',
                    'area' => [
                        'de' => 'Extract / Integration',
                        'en' => 'Extract / Integration',
                    ],
                    'setting' => [
                        'de' => 'Allowlist: Meta/IDs — keine Bodies/Compensation Klartext in Default',
                        'en' => 'Allowlist: meta/ids — no bodies/compensation cleartext by default',
                    ],
                    'why' => [
                        'de' => 'Analytics ohne Policy-Bruch.',
                        'en' => 'Analytics without policy breach.',
                    ],
                    'how' => [
                        'de' => 'Field allowlist; restricted schema für sensitive.',
                        'en' => 'Field allowlist; restricted schema for sensitive.',
                    ],
                ],
                [
                    'id' => 'greenhouse-retention',
                    'area' => [
                        'de' => 'Privacy / Retention',
                        'en' => 'Privacy / Retention',
                    ],
                    'setting' => [
                        'de' => 'Retention für Bewerber-/Employee-PII definieren',
                        'en' => 'Define retention for applicant/employee PII',
                    ],
                    'why' => [
                        'de' => 'DSGVO/Löschpflichten steuerbar.',
                        'en' => 'GDPR/erasure controllable.',
                    ],
                    'how' => [
                        'de' => 'TTL + legal hold flags; hard delete playbooks.',
                        'en' => 'TTL + legal hold flags; hard delete playbooks.',
                    ],
                ],
                [
                    'id' => 'greenhouse-sandbox-split',
                    'area' => [
                        'de' => 'Tenants',
                        'en' => 'Tenants',
                    ],
                    'setting' => [
                        'de' => 'Sandbox/Test strikt von Prod-Marts trennen',
                        'en' => 'Strictly separate sandbox/test from prod marts',
                    ],
                    'why' => [
                        'de' => 'Keine Fake-Headcount/Pipeline in Prod KPIs.',
                        'en' => 'No fake headcount/pipeline in prod KPIs.',
                    ],
                    'how' => [
                        'de' => 'Tenant id filter; separate warehouses.',
                        'en' => 'Tenant id filter; separate warehouses.',
                    ],
                ],
            ],
            'metadata' => [
                [
                    'id' => 'greenhouse-api-model',
                    'kind' => [
                        'de' => 'API object model',
                        'en' => 'API object model',
                    ],
                    'where' => [
                        'de' => 'Greenhouse API docs',
                        'en' => 'Greenhouse API docs',
                    ],
                    'how' => [
                        'de' => 'Entities/Fields inventarisieren; PII Allowlist.',
                        'en' => 'Inventory entities/fields; PII allowlist.',
                    ],
                    'useFor' => [
                        'de' => 'Extract design, join keys, PII tags.',
                        'en' => 'Extract design, join keys, PII tags.',
                    ],
                    'watchouts' => [
                        'de' => 'Compensation/CV/Notes nie Default-Select.',
                        'en' => 'Never default-select compensation/CV/notes.',
                    ],
                ],
                [
                    'id' => 'greenhouse-org-or-pipeline',
                    'kind' => [
                        'de' => 'Pipeline stages',
                        'en' => 'Pipeline stages',
                    ],
                    'where' => [
                        'de' => 'Greenhouse admin config',
                        'en' => 'Greenhouse admin config',
                    ],
                    'how' => [
                        'de' => 'Struktur und Codes dokumentieren.',
                        'en' => 'Document structure and codes.',
                    ],
                    'useFor' => [
                        'de' => 'Dims und DQ.',
                        'en' => 'Dims and DQ.',
                    ],
                    'watchouts' => [
                        'de' => 'Renames — SCD2.',
                        'en' => 'Renames — SCD2.',
                    ],
                ],
                [
                    'id' => 'greenhouse-webhooks',
                    'kind' => [
                        'de' => 'Webhooks / events',
                        'en' => 'Webhooks / events',
                    ],
                    'where' => [
                        'de' => 'Greenhouse webhooks',
                        'en' => 'Greenhouse webhooks',
                    ],
                    'how' => [
                        'de' => 'Event Types Allowlist; Payload minimize.',
                        'en' => 'Allowlist event types; minimize payload.',
                    ],
                    'useFor' => [
                        'de' => 'Near-real-time facts.',
                        'en' => 'Near-real-time facts.',
                    ],
                    'watchouts' => [
                        'de' => 'Webhook Payloads können volle PII enthalten.',
                        'en' => 'Webhook payloads can contain full PII.',
                    ],
                ],
                [
                    'id' => 'greenhouse-exports',
                    'kind' => [
                        'de' => 'CSV / BI exports',
                        'en' => 'CSV / BI exports',
                    ],
                    'where' => [
                        'de' => 'Greenhouse reports / exports',
                        'en' => 'Greenhouse reports / exports',
                    ],
                    'how' => [
                        'de' => 'Export-Templates ohne sensitive Spalten.',
                        'en' => 'Export templates without sensitive columns.',
                    ],
                    'useFor' => [
                        'de' => 'Shadow-IT erkennen; offizielle Extract bevorzugen.',
                        'en' => 'Detect shadow IT; prefer official extract.',
                    ],
                    'watchouts' => [
                        'de' => 'Exports sind zweite PII-Kopie.',
                        'en' => 'Exports are a second PII copy.',
                    ],
                ],
            ],
        ],
        'tools' => [
            'dbt-dq-rules-generator',
            'dbt-dq-macro-generator',
            'dbt-dq-history-generator',
            'schema-yml-editor',
        ],
    ],
    'softgarden' => [
        'quality' => [
            'dq' => [
                [
                    'id' => 'softgarden-app-no-candidate',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Application ohne Candidate',
                        'en' => 'Application without candidate',
                    ],
                    'problem' => [
                        'de' => 'Orphan Applications brechen Pipeline KPIs.',
                        'en' => 'Orphan applications break pipeline KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'API Extract: candidate immer mitladen.',
                        'en' => 'API extract: always load candidate.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'TA Ops: Application braucht Candidate.',
                        'en' => 'TA ops: Application needs candidate.',
                    ],
                    'checks' => [
                        'de' => 'application.candidate_id IS NULL OR NOT IN dim_candidate',
                        'en' => 'application.candidate_id IS NULL OR NOT IN dim_candidate',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract.',
                        'en' => 'Re-extract.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine.',
                        'en' => 'Quarantine.',
                    ],
                ],
                [
                    'id' => 'softgarden-app-no-job',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Application ohne Job',
                        'en' => 'Application without job',
                    ],
                    'problem' => [
                        'de' => 'Applications ohne Job verzerren Funnel je Stelle.',
                        'en' => 'Applications without job distort per-job funnel.',
                    ],
                    'prevent' => [
                        'de' => 'Job Pflicht bei Apply.',
                        'en' => 'Job required on apply.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Recruiting: Keine Job-losen Apps in Prod.',
                        'en' => 'Recruiting: No job-less apps in prod.',
                    ],
                    'checks' => [
                        'de' => 'application.job_id IS NULL',
                        'en' => 'application.job_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Job zuweisen oder archivieren.',
                        'en' => 'Assign job or archive.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown job dim.',
                        'en' => 'Unknown job dim.',
                    ],
                ],
                [
                    'id' => 'softgarden-hired-no-hired-at',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Hired ohne hired_at',
                        'en' => 'Hired without hired_at',
                    ],
                    'problem' => [
                        'de' => 'Hire Status ohne Timestamp bricht Time-to-Hire.',
                        'en' => 'Hire status without timestamp breaks time-to-hire.',
                    ],
                    'prevent' => [
                        'de' => 'Hire Workflow: hired_at Pflicht.',
                        'en' => 'Hire workflow: hired_at required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'TA: Hire Event datieren.',
                        'en' => 'TA: Date the hire event.',
                    ],
                    'checks' => [
                        'de' => 'status=\'hired\' AND hired_at IS NULL',
                        'en' => 'status=\'hired\' AND hired_at IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Datum nachziehen.',
                        'en' => 'Backfill date.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Flag missing_hired_at.',
                        'en' => 'Flag missing_hired_at.',
                    ],
                ],
                [
                    'id' => 'softgarden-duplicate-candidate-email',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Doppelte Candidate E-Mails',
                        'en' => 'Duplicate candidate emails',
                    ],
                    'problem' => [
                        'de' => 'Duplikate verzerren Unique Candidate Counts.',
                        'en' => 'Duplicates distort unique candidate counts.',
                    ],
                    'prevent' => [
                        'de' => 'Dedup Policy im ATS.',
                        'en' => 'Dedup policy in ATS.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'TA: Vor Create suchen.',
                        'en' => 'TA: Search before create.',
                    ],
                    'checks' => [
                        'de' => 'COUNT(*) GROUP BY lower(email) HAVING COUNT>1',
                        'en' => 'COUNT(*) GROUP BY lower(email) HAVING COUNT>1',
                    ],
                    'fixInSource' => [
                        'de' => 'Merge Candidates.',
                        'en' => 'Merge candidates.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Golden candidate via email hash.',
                        'en' => 'Golden candidate via email hash.',
                    ],
                ],
                [
                    'id' => 'softgarden-offer-salary-in-public-mart',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Offer Salary in Public Mart',
                        'en' => 'Offer salary in public mart',
                    ],
                    'problem' => [
                        'de' => 'Offer Beträge in Public Marts sind Policy-Verstoß.',
                        'en' => 'Offer amounts in public marts are a policy violation.',
                    ],
                    'prevent' => [
                        'de' => 'Allowlist; restricted schema.',
                        'en' => 'Allowlist; restricted schema.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Keine salary Spalte in default marts.',
                        'en' => 'Data: No salary column in default marts.',
                    ],
                    'checks' => [
                        'de' => 'column_exists(public_mart,\'salary\')',
                        'en' => 'column_exists(public_mart,\'salary\')',
                    ],
                    'fixInSource' => [
                        'de' => 'N/A — Warehouse Policy.',
                        'en' => 'N/A — warehouse policy.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Remove salary from public marts.',
                        'en' => 'Remove salary from public marts.',
                    ],
                ],
                [
                    'id' => 'softgarden-stage-unknown',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Application Stage unbekannt',
                        'en' => 'Application stage unknown',
                    ],
                    'problem' => [
                        'de' => 'Unbekannte Stages brechen Funnel-Order.',
                        'en' => 'Unknown stages break funnel order.',
                    ],
                    'prevent' => [
                        'de' => 'Stage Catalog pflegen.',
                        'en' => 'Maintain stage catalog.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'TA Ops: Keine Ad-hoc Stages.',
                        'en' => 'TA ops: No ad-hoc stages.',
                    ],
                    'checks' => [
                        'de' => 'stage_id NOT IN dim_stage',
                        'en' => 'stage_id NOT IN dim_stage',
                    ],
                    'fixInSource' => [
                        'de' => 'Stage map fixen.',
                        'en' => 'Fix stage map.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown stage bucket.',
                        'en' => 'Unknown stage bucket.',
                    ],
                ],
            ],
            'mdm' => [
                [
                    'id' => 'softgarden-candidate-dup',
                    'entity' => 'Candidate',
                    'title' => [
                        'de' => 'Candidate Duplikate',
                        'en' => 'Candidate duplicates',
                    ],
                    'matchKeys' => [
                        'id',
                        'email hashed',
                        'phone hashed',
                    ],
                    'preventInSource' => [
                        'de' => 'Dedup before create.',
                        'en' => 'Dedup before create.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden candidate id; email-hash map.',
                        'en' => 'Golden candidate id; email-hash map.',
                    ],
                    'survivorship' => [
                        'de' => 'Neueste Application Activity gewinnt.',
                        'en' => 'Latest application activity wins.',
                    ],
                ],
                [
                    'id' => 'softgarden-job-map',
                    'entity' => 'Job',
                    'title' => [
                        'de' => 'Job / Requisition Mapping',
                        'en' => 'Job / requisition mapping',
                    ],
                    'matchKeys' => [
                        'job id',
                        'requisition_number',
                        'title+department',
                    ],
                    'preventInSource' => [
                        'de' => 'Stable job id; track reopen.',
                        'en' => 'Stable job id; track reopen.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Canonical job id; title SCD2.',
                        'en' => 'Canonical job id; title SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Open Job mit aktueller Title gewinnt.',
                        'en' => 'Open job with current title wins.',
                    ],
                ],
            ],
            'productSettings' => [
                [
                    'id' => 'softgarden-no-sensitive-bodies',
                    'area' => [
                        'de' => 'Extract / Integration',
                        'en' => 'Extract / Integration',
                    ],
                    'setting' => [
                        'de' => 'Allowlist: Meta/IDs — keine Bodies/Compensation Klartext in Default',
                        'en' => 'Allowlist: meta/ids — no bodies/compensation cleartext by default',
                    ],
                    'why' => [
                        'de' => 'Analytics ohne Policy-Bruch.',
                        'en' => 'Analytics without policy breach.',
                    ],
                    'how' => [
                        'de' => 'Field allowlist; restricted schema für sensitive.',
                        'en' => 'Field allowlist; restricted schema for sensitive.',
                    ],
                ],
                [
                    'id' => 'softgarden-retention',
                    'area' => [
                        'de' => 'Privacy / Retention',
                        'en' => 'Privacy / Retention',
                    ],
                    'setting' => [
                        'de' => 'Retention für Bewerber-/Employee-PII definieren',
                        'en' => 'Define retention for applicant/employee PII',
                    ],
                    'why' => [
                        'de' => 'DSGVO/Löschpflichten steuerbar.',
                        'en' => 'GDPR/erasure controllable.',
                    ],
                    'how' => [
                        'de' => 'TTL + legal hold flags; hard delete playbooks.',
                        'en' => 'TTL + legal hold flags; hard delete playbooks.',
                    ],
                ],
                [
                    'id' => 'softgarden-sandbox-split',
                    'area' => [
                        'de' => 'Tenants',
                        'en' => 'Tenants',
                    ],
                    'setting' => [
                        'de' => 'Sandbox/Test strikt von Prod-Marts trennen',
                        'en' => 'Strictly separate sandbox/test from prod marts',
                    ],
                    'why' => [
                        'de' => 'Keine Fake-Headcount/Pipeline in Prod KPIs.',
                        'en' => 'No fake headcount/pipeline in prod KPIs.',
                    ],
                    'how' => [
                        'de' => 'Tenant id filter; separate warehouses.',
                        'en' => 'Tenant id filter; separate warehouses.',
                    ],
                ],
            ],
            'metadata' => [
                [
                    'id' => 'softgarden-api-model',
                    'kind' => [
                        'de' => 'API object model',
                        'en' => 'API object model',
                    ],
                    'where' => [
                        'de' => 'softgarden API docs',
                        'en' => 'softgarden API docs',
                    ],
                    'how' => [
                        'de' => 'Entities/Fields inventarisieren; PII Allowlist.',
                        'en' => 'Inventory entities/fields; PII allowlist.',
                    ],
                    'useFor' => [
                        'de' => 'Extract design, join keys, PII tags.',
                        'en' => 'Extract design, join keys, PII tags.',
                    ],
                    'watchouts' => [
                        'de' => 'Compensation/CV/Notes nie Default-Select.',
                        'en' => 'Never default-select compensation/CV/notes.',
                    ],
                ],
                [
                    'id' => 'softgarden-org-or-pipeline',
                    'kind' => [
                        'de' => 'Pipeline stages',
                        'en' => 'Pipeline stages',
                    ],
                    'where' => [
                        'de' => 'softgarden admin config',
                        'en' => 'softgarden admin config',
                    ],
                    'how' => [
                        'de' => 'Struktur und Codes dokumentieren.',
                        'en' => 'Document structure and codes.',
                    ],
                    'useFor' => [
                        'de' => 'Dims und DQ.',
                        'en' => 'Dims and DQ.',
                    ],
                    'watchouts' => [
                        'de' => 'Renames — SCD2.',
                        'en' => 'Renames — SCD2.',
                    ],
                ],
                [
                    'id' => 'softgarden-webhooks',
                    'kind' => [
                        'de' => 'Webhooks / events',
                        'en' => 'Webhooks / events',
                    ],
                    'where' => [
                        'de' => 'softgarden webhooks',
                        'en' => 'softgarden webhooks',
                    ],
                    'how' => [
                        'de' => 'Event Types Allowlist; Payload minimize.',
                        'en' => 'Allowlist event types; minimize payload.',
                    ],
                    'useFor' => [
                        'de' => 'Near-real-time facts.',
                        'en' => 'Near-real-time facts.',
                    ],
                    'watchouts' => [
                        'de' => 'Webhook Payloads können volle PII enthalten.',
                        'en' => 'Webhook payloads can contain full PII.',
                    ],
                ],
                [
                    'id' => 'softgarden-exports',
                    'kind' => [
                        'de' => 'CSV / BI exports',
                        'en' => 'CSV / BI exports',
                    ],
                    'where' => [
                        'de' => 'softgarden reports / exports',
                        'en' => 'softgarden reports / exports',
                    ],
                    'how' => [
                        'de' => 'Export-Templates ohne sensitive Spalten.',
                        'en' => 'Export templates without sensitive columns.',
                    ],
                    'useFor' => [
                        'de' => 'Shadow-IT erkennen; offizielle Extract bevorzugen.',
                        'en' => 'Detect shadow IT; prefer official extract.',
                    ],
                    'watchouts' => [
                        'de' => 'Exports sind zweite PII-Kopie.',
                        'en' => 'Exports are a second PII copy.',
                    ],
                ],
            ],
        ],
        'tools' => [
            'dbt-dq-rules-generator',
            'dbt-dq-macro-generator',
            'dbt-dq-history-generator',
            'schema-yml-editor',
        ],
    ],
];
