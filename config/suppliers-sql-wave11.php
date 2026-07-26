<?php

/**
 * Wave 11 warehouse SQL examples — Service/BPM/Healthcare source-native (Landing RAW + Curated).
 * Warehouse-neutral dialect — not Freshdesk REST DDL / Pega Case schema / Camunda History schema / Epic FHIR/Clarity schema.
 * Curated facts = activity/metadata KPIs — NOT conversation/case-attachment/variable/clinical-note bodies.
 * Epic examples use tokenized patient identifiers only — never raw MRN/DOB/name/SSN.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'freshdesk' => [
        'sqlExamples' => [
            [
                'id' => 'raw-freshdesk-contact-ticket',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Contact + Ticket Landing', 'en' => 'RAW contact + ticket landing'],
                'notes' => [
                    'de' => 'REST Shape — email/phone = direkte PII; keine Conversation-Bodies.',
                    'en' => 'REST shape — email/phone = direct PII; no conversation bodies.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Freshdesk REST API extract\nCREATE TABLE raw_freshdesk_contact (\n  contact_id           BIGINT,\n  email                VARCHAR, -- PII\n  phone                VARCHAR, -- PII\n  name                 VARCHAR, -- PII\n  company_id           BIGINT,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_freshdesk_ticket (\n  ticket_id            BIGINT,\n  requester_id         BIGINT,\n  responder_id         BIGINT,\n  group_id             BIGINT,\n  status               VARCHAR,\n  priority             VARCHAR,\n  type                 VARCHAR,\n  created_at           TIMESTAMP,\n  resolved_at          TIMESTAMP,\n  fr_escalated         BOOLEAN,\n  sla_policy_id        BIGINT,\n  _loaded_at           TIMESTAMP\n  -- no subject/description body\n);",
            ],
            [
                'id' => 'raw-freshdesk-conversation-agent',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Conversation Meta + Agent Landing', 'en' => 'RAW conversation meta + agent landing'],
                'notes' => [
                    'de' => 'Conversation ohne Body; Agent-E-Mail = Workforce-PII.',
                    'en' => 'Conversation without body; agent email = workforce PII.',
                ],
                'sql' => "CREATE TABLE raw_freshdesk_conversation_meta (\n  conversation_id      BIGINT,\n  ticket_id            BIGINT,\n  created_at           TIMESTAMP,\n  user_id              BIGINT,\n  private              BOOLEAN,\n  _loaded_at           TIMESTAMP\n  -- no body/body_text\n);\n\nCREATE TABLE raw_freshdesk_agent (\n  agent_id             BIGINT,\n  email                VARCHAR, -- workforce PII\n  active               BOOLEAN,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-freshdesk-ticket',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact freshdesk ticket', 'en' => 'Curated fact freshdesk ticket'],
                'notes' => [
                    'de' => 'Service-Grain — Requester Pflicht; ohne Subject-Klartext.',
                    'en' => 'Service grain — requester required; no subject cleartext.',
                ],
                'sql' => "CREATE TABLE curated_fct_freshdesk_ticket AS\nSELECT\n  t.ticket_id,\n  t.requester_id,\n  t.responder_id,\n  t.group_id,\n  t.status,\n  t.priority,\n  t.type,\n  t.created_at,\n  t.resolved_at,\n  t.fr_escalated,\n  t.sla_policy_id\nFROM raw_freshdesk_ticket t\nWHERE t.requester_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-freshdesk',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim contact / agent workload fact', 'en' => 'Curated dim contact / agent workload fact'],
                'notes' => [
                    'de' => 'Contact ohne Email/Phone; Agent-Workload aus Ticket-Join.',
                    'en' => 'Contact without email/phone; agent workload from ticket join.',
                ],
                'sql' => "CREATE TABLE curated_dim_freshdesk_contact AS\nSELECT\n  contact_id,\n  company_id\n  -- omit email, phone, name from default analytics dims\nFROM raw_freshdesk_contact;\n\nCREATE TABLE curated_fct_freshdesk_agent_workload AS\nSELECT\n  a.agent_id,\n  a.active,\n  COUNT(t.ticket_id) FILTER (WHERE t.status IN ('open', 'pending')) AS open_ticket_count\nFROM raw_freshdesk_agent a\nLEFT JOIN raw_freshdesk_ticket t ON t.responder_id = a.agent_id\nGROUP BY a.agent_id, a.active;",
            ],
            [
                'id' => 'curated-measure-freshdesk',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Tickets Created/Resolved, SLA Breaches — Periodenfilter anpassen.',
                    'en' => 'Tickets created/resolved, SLA breaches — adapt period filters.',
                ],
                'sql' => "-- Tickets created in period\nSELECT COUNT(*) AS tickets_created\nFROM curated_fct_freshdesk_ticket\nWHERE created_at BETWEEN :period_start AND :period_end;\n\n-- Tickets resolved in period\nSELECT COUNT(*) AS tickets_resolved\nFROM curated_fct_freshdesk_ticket\nWHERE resolved_at BETWEEN :period_start AND :period_end;\n\n-- SLA breaches (first-response escalated) in period\nSELECT COUNT(*) AS sla_breaches\nFROM curated_fct_freshdesk_ticket\nWHERE fr_escalated = TRUE\n  AND created_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'pega' => [
        'sqlExamples' => [
            [
                'id' => 'raw-pega-case-assignment',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Case + Assignment Landing', 'en' => 'RAW case + assignment landing'],
                'notes' => [
                    'de' => 'Case API Shape — kein Attachment-/Correspondence-Content.',
                    'en' => 'Case API shape — no attachment/correspondence content.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Pega Case Management API extract\nCREATE TABLE raw_pega_case (\n  py_id                VARCHAR,\n  pz_ins_key           VARCHAR,\n  py_class             VARCHAR,\n  py_status_work       VARCHAR,\n  py_urgency           INT,\n  px_create_datetime   TIMESTAMP,\n  px_resolved_ts       TIMESTAMP,\n  _loaded_at           TIMESTAMP\n  -- no pyLabel/pyDescription free text without review\n);\n\nCREATE TABLE raw_pega_assignment (\n  assignment_id        VARCHAR,\n  py_case_id           VARCHAR,\n  px_task_label        VARCHAR,\n  urgency              INT,\n  assigned_operator_id VARCHAR,\n  status               VARCHAR,\n  urgent_in            INTERVAL,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-pega-operator-sla',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Operator + SLA Event Landing', 'en' => 'RAW operator + SLA event landing'],
                'notes' => [
                    'de' => 'Operator-E-Mail = Workforce-PII; SLA Event ohne Correspondence-Body.',
                    'en' => 'Operator email = workforce PII; SLA event without correspondence body.',
                ],
                'sql' => "CREATE TABLE raw_pega_operator (\n  py_user_identifier   VARCHAR,\n  py_email_address     VARCHAR, -- workforce PII\n  py_user_name         VARCHAR, -- workforce PII\n  py_org_unit          VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_pega_sla_event (\n  sla_event_id         VARCHAR,\n  case_id              VARCHAR,\n  goal_passed          BOOLEAN,\n  deadline_passed      BOOLEAN,\n  created_at           TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-pega-case',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact pega case', 'en' => 'Curated fact pega case'],
                'notes' => [
                    'de' => 'Case-Grain — pzInsKey als Natural Key; Cycle-Time-Felder.',
                    'en' => 'Case grain — pzInsKey as natural key; cycle-time fields.',
                ],
                'sql' => "CREATE TABLE curated_fct_pega_case AS\nSELECT\n  c.pz_ins_key,\n  c.py_id,\n  c.py_class,\n  c.py_status_work,\n  c.py_urgency,\n  c.px_create_datetime,\n  c.px_resolved_ts,\n  CASE WHEN c.py_status_work LIKE 'Resolved-%' THEN TRUE ELSE FALSE END AS is_resolved\nFROM raw_pega_case c;",
            ],
            [
                'id' => 'curated-dims-pega',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim operator / SLA breach fact', 'en' => 'Curated dim operator / SLA breach fact'],
                'notes' => [
                    'de' => 'Operator ohne Email/Name; SLA-Breach-Fact aus SLA Event.',
                    'en' => 'Operator without email/name; SLA breach fact from SLA event.',
                ],
                'sql' => "CREATE TABLE curated_dim_pega_operator AS\nSELECT\n  py_user_identifier,\n  py_org_unit\n  -- omit py_email_address, py_user_name from default analytics dims\nFROM raw_pega_operator;\n\nCREATE TABLE curated_fct_pega_sla_breach AS\nSELECT\n  s.sla_event_id,\n  s.case_id,\n  s.goal_passed,\n  s.deadline_passed,\n  s.created_at\nFROM raw_pega_sla_event s\nWHERE s.case_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-pega',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Cases Created/Resolved, SLA Breaches, Cycle Time — Periodenfilter anpassen.',
                    'en' => 'Cases created/resolved, SLA breaches, cycle time — adapt period filters.',
                ],
                'sql' => "-- Cases created in period\nSELECT COUNT(*) AS cases_created\nFROM curated_fct_pega_case\nWHERE px_create_datetime BETWEEN :period_start AND :period_end;\n\n-- Cases resolved in period\nSELECT COUNT(*) AS cases_resolved\nFROM curated_fct_pega_case\nWHERE is_resolved = TRUE\n  AND px_resolved_ts BETWEEN :period_start AND :period_end;\n\n-- SLA deadline breaches in period\nSELECT COUNT(*) AS sla_breaches\nFROM curated_fct_pega_sla_breach\nWHERE deadline_passed = TRUE\n  AND created_at BETWEEN :period_start AND :period_end;\n\n-- Average cycle time (resolved cases)\nSELECT AVG(px_resolved_ts - px_create_datetime) AS avg_cycle_time\nFROM curated_fct_pega_case\nWHERE is_resolved = TRUE;",
            ],
        ],
    ],

    'camunda' => [
        'sqlExamples' => [
            [
                'id' => 'raw-camunda-definition-instance',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Process Definition + Instance Landing', 'en' => 'RAW process definition + instance landing'],
                'notes' => [
                    'de' => 'REST History API Shape — kein Variable-Value-Bulk.',
                    'en' => 'REST History API shape — no variable value bulk.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Camunda REST History API extract\nCREATE TABLE raw_camunda_process_definition (\n  id                   VARCHAR,\n  process_key          VARCHAR,\n  version              INT,\n  name                 VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_camunda_process_instance (\n  id                   VARCHAR,\n  process_definition_id VARCHAR,\n  business_key         VARCHAR,\n  start_time           TIMESTAMP,\n  end_time             TIMESTAMP,\n  state                VARCHAR,\n  tenant_id            VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-camunda-task-incident',
                'stage' => 'raw',
                'title' => ['de' => 'RAW User Task + Incident Landing', 'en' => 'RAW user task + incident landing'],
                'notes' => [
                    'de' => 'Assignee = Workforce-PII; Incident ohne vollen Stacktrace.',
                    'en' => 'Assignee = workforce PII; incident without full stack trace.',
                ],
                'sql' => "CREATE TABLE raw_camunda_user_task (\n  id                   VARCHAR,\n  process_instance_id  VARCHAR,\n  task_definition_key  VARCHAR,\n  assignee             VARCHAR, -- workforce PII\n  created              TIMESTAMP,\n  due                  TIMESTAMP,\n  end_time             TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_camunda_incident (\n  id                   VARCHAR,\n  process_instance_id  VARCHAR,\n  incident_type        VARCHAR,\n  incident_timestamp   TIMESTAMP,\n  resolved             BOOLEAN,\n  _loaded_at           TIMESTAMP\n  -- no full stack trace / incidentMessage bulk\n);",
            ],
            [
                'id' => 'curated-fct-camunda-instance',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact camunda process instance', 'en' => 'Curated fact camunda process instance'],
                'notes' => [
                    'de' => 'Process-Grain — Definition Pflicht; businessKey restriktiv behandeln.',
                    'en' => 'Process grain — definition required; treat businessKey restrictively.',
                ],
                'sql' => "CREATE TABLE curated_fct_camunda_process_instance AS\nSELECT\n  pi.id,\n  pi.process_definition_id,\n  pi.start_time,\n  pi.end_time,\n  pi.state,\n  pi.tenant_id\n  -- business_key restricted to a privacy-reviewed schema, omitted from broad marts\nFROM raw_camunda_process_instance pi\nWHERE pi.process_definition_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-camunda',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim process definition / task fact', 'en' => 'Curated dim process definition / task fact'],
                'notes' => [
                    'de' => 'Definition Dim; Task-Fact ohne Assignee-Klartext.',
                    'en' => 'Definition dim; task fact without assignee cleartext.',
                ],
                'sql' => "CREATE TABLE curated_dim_camunda_process_definition AS\nSELECT\n  id,\n  process_key,\n  version,\n  name\nFROM raw_camunda_process_definition;\n\nCREATE TABLE curated_fct_camunda_user_task AS\nSELECT\n  t.id,\n  t.process_instance_id,\n  t.task_definition_key,\n  t.created,\n  t.due,\n  t.end_time,\n  CASE WHEN t.due < CURRENT_TIMESTAMP AND t.end_time IS NULL THEN TRUE ELSE FALSE END AS is_overdue\n  -- omit assignee from default analytics facts\nFROM raw_camunda_user_task t;",
            ],
            [
                'id' => 'curated-measure-camunda',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Instances Started/Completed, Tasks Completed, Incidents Open — Periodenfilter anpassen.',
                    'en' => 'Instances started/completed, tasks completed, incidents open — adapt period filters.',
                ],
                'sql' => "-- Process instances started in period\nSELECT COUNT(*) AS process_instances_started\nFROM curated_fct_camunda_process_instance\nWHERE start_time BETWEEN :period_start AND :period_end;\n\n-- Process instances completed in period\nSELECT COUNT(*) AS process_instances_completed\nFROM curated_fct_camunda_process_instance\nWHERE state = 'COMPLETED'\n  AND end_time BETWEEN :period_start AND :period_end;\n\n-- Tasks completed in period\nSELECT COUNT(*) AS tasks_completed\nFROM curated_fct_camunda_user_task\nWHERE end_time BETWEEN :period_start AND :period_end;\n\n-- Incidents currently open\nSELECT COUNT(*) AS incidents_open\nFROM raw_camunda_incident\nWHERE resolved = FALSE;",
            ],
        ],
    ],

    'epic' => [
        'sqlExamples' => [
            [
                'id' => 'raw-epic-patient-encounter',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Patient (Tokenized) + Encounter Landing', 'en' => 'RAW patient (tokenized) + encounter landing'],
                'notes' => [
                    'de' => 'FHIR Shape — Patient nur tokenisiert; kein Klartext-MRN/DOB/Name; keine Notes.',
                    'en' => 'FHIR shape — patient tokenized only; no cleartext MRN/DOB/name; no notes.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Epic FHIR extract; patient identifiers tokenized before landing\nCREATE TABLE raw_epic_patient (\n  patient_token        VARCHAR, -- tokenized surrogate, never raw MRN\n  gender               VARCHAR,\n  birth_year           INT, -- year only, not full DOB, to reduce re-identification risk\n  _loaded_at           TIMESTAMP\n  -- no mrn, birth_date, name, address, ssn in any RAW column\n);\n\nCREATE TABLE raw_epic_encounter (\n  encounter_id         VARCHAR,\n  patient_token        VARCHAR, -- tokenized\n  class                VARCHAR, -- inpatient | outpatient | emergency\n  admit_at             TIMESTAMP,\n  discharge_at         TIMESTAMP,\n  department_id        VARCHAR,\n  provider_id          VARCHAR,\n  status               VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no clinical notes / progress note text\n);",
            ],
            [
                'id' => 'raw-epic-appointment-codes',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Appointment + Diagnosis/Procedure Code Landing', 'en' => 'RAW appointment + diagnosis/procedure code landing'],
                'notes' => [
                    'de' => 'Appointment ohne Freitext-Grund; Codes only, keine Beschreibungstexte.',
                    'en' => 'Appointment without free-text reason; codes only, no description text.',
                ],
                'sql' => "CREATE TABLE raw_epic_appointment (\n  appointment_id       VARCHAR,\n  patient_token        VARCHAR, -- tokenized\n  department_id        VARCHAR,\n  scheduled_at         TIMESTAMP,\n  status               VARCHAR, -- booked | arrived | noshow | cancelled\n  _loaded_at           TIMESTAMP\n  -- no free-text appointment reason\n);\n\nCREATE TABLE raw_epic_diagnosis_code (\n  encounter_id         VARCHAR,\n  icd_code             VARCHAR, -- code only, not description text\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_epic_procedure_code (\n  encounter_id         VARCHAR,\n  cpt_code             VARCHAR, -- code only, not description text\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-epic-encounter',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact epic encounter', 'en' => 'Curated fact epic encounter'],
                'notes' => [
                    'de' => 'Encounter-Grain — nur tokenisierte Patient-Referenz; keine klinischen Inhalte.',
                    'en' => 'Encounter grain — only tokenized patient reference; no clinical content.',
                ],
                'sql' => "CREATE TABLE curated_fct_epic_encounter AS\nSELECT\n  e.encounter_id,\n  e.patient_token,\n  e.class,\n  e.admit_at,\n  e.discharge_at,\n  e.department_id,\n  e.provider_id,\n  e.status\nFROM raw_epic_encounter e\nWHERE e.patient_token IS NOT NULL;",
            ],
            [
                'id' => 'curated-agg-epic-diagnosis',
                'stage' => 'curated',
                'title' => ['de' => 'Curated k-anonymous diagnosis aggregate', 'en' => 'Curated k-anonymous diagnosis aggregate'],
                'notes' => [
                    'de' => 'Nur aggregierte Zellen mit Mindestfallzahl (k-Anonymität) — keine Patient-Level-Exports.',
                    'en' => 'Only aggregated cells with a minimum case count (k-anonymity) — no patient-level exports.',
                ],
                'sql' => "CREATE TABLE curated_agg_epic_diagnosis_frequency AS\nSELECT\n  dc.icd_code,\n  e.department_id,\n  COUNT(*) AS case_count\nFROM raw_epic_diagnosis_code dc\nJOIN raw_epic_encounter e ON e.encounter_id = dc.encounter_id\nGROUP BY dc.icd_code, e.department_id\nHAVING COUNT(*) >= :k_anonymity_threshold;",
            ],
            [
                'id' => 'curated-measure-epic',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Encounters, No-Show Rate, Average LOS — Periodenfilter anpassen; alle Ergebnisse aggregiert.',
                    'en' => 'Encounters, no-show rate, average LOS — adapt period filters; all results aggregated.',
                ],
                'sql' => "-- Encounters in period\nSELECT COUNT(*) AS encounters_count\nFROM curated_fct_epic_encounter\nWHERE admit_at BETWEEN :period_start AND :period_end;\n\n-- No-show rate in period\nSELECT\n  COUNT(*) FILTER (WHERE status = 'noshow')::FLOAT / COUNT(*) AS no_show_rate\nFROM raw_epic_appointment\nWHERE scheduled_at BETWEEN :period_start AND :period_end;\n\n-- Average length of stay for inpatient encounters (aggregate only)\nSELECT AVG(discharge_at - admit_at) AS avg_length_of_stay\nFROM curated_fct_epic_encounter\nWHERE class = 'inpatient'\n  AND discharge_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],
];
