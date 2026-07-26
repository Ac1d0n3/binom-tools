<?php

/**
 * Wave 12 warehouse SQL examples — HR/ATS source-native (Landing RAW + Curated).
 * Metadata KPIs only — no CV/contract bodies or compensation cleartext in default curated.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'bamboohr' => [
        'sqlExamples' => [
            [
                'id' => 'raw-bamboohr-employee',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Employee + Employment Landing',
                    'en' => 'RAW employee + employment landing',
                ],
                'notes' => [
                    'de' => 'Workforce-PII in RAW; keine Gehaltsklartext-Spalten in Default Extract.',
                    'en' => 'Workforce PII in RAW; no salary cleartext columns in default extract.',
                ],
                'sql' => '-- Warehouse-neutral RAW from BambooHR HRIS extract
CREATE TABLE raw_bamboohr_employee (
  employee_id          VARCHAR,
  work_email           VARCHAR, -- workforce PII
  first_name           VARCHAR, -- PII
  last_name            VARCHAR, -- PII
  status               VARCHAR,
  hire_date            DATE,
  termination_date     DATE,
  department_id        VARCHAR,
  location_id          VARCHAR,
  _loaded_at           TIMESTAMP
  -- omit national_id cleartext; omit salary
);

CREATE TABLE raw_bamboohr_employment (
  employment_id        VARCHAR,
  employee_id          VARCHAR,
  job_title            VARCHAR,
  employment_type      VARCHAR,
  fte                  DECIMAL(6,3),
  effective_on         DATE,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'raw-bamboohr-timeoff-manager',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Time Off + Manager Landing',
                    'en' => 'RAW time off + manager landing',
                ],
                'notes' => [
                    'de' => 'Absence Meta ohne Medical Notes; Manager als ids.',
                    'en' => 'Absence meta without medical notes; manager as ids.',
                ],
                'sql' => 'CREATE TABLE raw_bamboohr_time_off (
  time_off_id          VARCHAR,
  employee_id          VARCHAR,
  type                 VARCHAR,
  status               VARCHAR,
  days                 DECIMAL(8,2),
  start_date           DATE,
  end_date             DATE,
  _loaded_at           TIMESTAMP
  -- no medical notes
);

CREATE TABLE raw_bamboohr_manager (
  employee_id          VARCHAR,
  manager_id           VARCHAR,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'curated-fct-bamboohr-employee',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated dim employee',
                    'en' => 'Curated dim employee',
                ],
                'notes' => [
                    'de' => 'Ohne E-Mail/Name Klartext.',
                    'en' => 'Without email/name cleartext.',
                ],
                'sql' => 'CREATE TABLE curated_dim_bamboohr_employee AS
SELECT
  employee_id,
  status,
  hire_date,
  termination_date,
  department_id,
  location_id
  -- omit work_email, first_name, last_name
FROM raw_bamboohr_employee;',
            ],
            [
                'id' => 'curated-fct-bamboohr-timeoff',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated fact time off',
                    'en' => 'Curated fact time off',
                ],
                'notes' => [
                    'de' => 'Approved Absences Grain.',
                    'en' => 'Approved absences grain.',
                ],
                'sql' => 'CREATE TABLE curated_fct_bamboohr_time_off AS
SELECT
  t.time_off_id,
  t.employee_id,
  t.type,
  t.status,
  t.days,
  t.start_date,
  t.end_date
FROM raw_bamboohr_time_off t
WHERE t.employee_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-measure-bamboohr',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated measure SELECTs',
                    'en' => 'Curated measure SELECTs',
                ],
                'notes' => [
                    'de' => 'Headcount, Hires, Terminations, Time Off Days.',
                    'en' => 'Headcount, hires, terminations, time-off days.',
                ],
                'sql' => '-- Active headcount
SELECT COUNT(*) AS active_headcount
FROM curated_dim_bamboohr_employee
WHERE status = \'active\';

-- Hires in period
SELECT COUNT(*) AS hires
FROM curated_dim_bamboohr_employee
WHERE hire_date BETWEEN :period_start AND :period_end;

-- Approved time-off days
SELECT COALESCE(SUM(days),0) AS time_off_days
FROM curated_fct_bamboohr_time_off
WHERE status = \'approved\'
  AND start_date BETWEEN :period_start AND :period_end;',
            ],
        ],
    ],
    'hibob' => [
        'sqlExamples' => [
            [
                'id' => 'raw-hibob-employee',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Employee + Employment Landing',
                    'en' => 'RAW employee + employment landing',
                ],
                'notes' => [
                    'de' => 'Workforce-PII in RAW; keine Gehaltsklartext-Spalten in Default Extract.',
                    'en' => 'Workforce PII in RAW; no salary cleartext columns in default extract.',
                ],
                'sql' => '-- Warehouse-neutral RAW from HiBob HRIS extract
CREATE TABLE raw_hibob_employee (
  employee_id          VARCHAR,
  work_email           VARCHAR, -- workforce PII
  first_name           VARCHAR, -- PII
  last_name            VARCHAR, -- PII
  status               VARCHAR,
  hire_date            DATE,
  termination_date     DATE,
  department_id        VARCHAR,
  location_id          VARCHAR,
  _loaded_at           TIMESTAMP
  -- omit national_id cleartext; omit salary
);

CREATE TABLE raw_hibob_employment (
  employment_id        VARCHAR,
  employee_id          VARCHAR,
  job_title            VARCHAR,
  employment_type      VARCHAR,
  fte                  DECIMAL(6,3),
  effective_on         DATE,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'raw-hibob-timeoff-manager',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Time Off + Manager Landing',
                    'en' => 'RAW time off + manager landing',
                ],
                'notes' => [
                    'de' => 'Absence Meta ohne Medical Notes; Manager als ids.',
                    'en' => 'Absence meta without medical notes; manager as ids.',
                ],
                'sql' => 'CREATE TABLE raw_hibob_time_off (
  time_off_id          VARCHAR,
  employee_id          VARCHAR,
  type                 VARCHAR,
  status               VARCHAR,
  days                 DECIMAL(8,2),
  start_date           DATE,
  end_date             DATE,
  _loaded_at           TIMESTAMP
  -- no medical notes
);

CREATE TABLE raw_hibob_manager (
  employee_id          VARCHAR,
  manager_id           VARCHAR,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'curated-fct-hibob-employee',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated dim employee',
                    'en' => 'Curated dim employee',
                ],
                'notes' => [
                    'de' => 'Ohne E-Mail/Name Klartext.',
                    'en' => 'Without email/name cleartext.',
                ],
                'sql' => 'CREATE TABLE curated_dim_hibob_employee AS
SELECT
  employee_id,
  status,
  hire_date,
  termination_date,
  department_id,
  location_id
  -- omit work_email, first_name, last_name
FROM raw_hibob_employee;',
            ],
            [
                'id' => 'curated-fct-hibob-timeoff',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated fact time off',
                    'en' => 'Curated fact time off',
                ],
                'notes' => [
                    'de' => 'Approved Absences Grain.',
                    'en' => 'Approved absences grain.',
                ],
                'sql' => 'CREATE TABLE curated_fct_hibob_time_off AS
SELECT
  t.time_off_id,
  t.employee_id,
  t.type,
  t.status,
  t.days,
  t.start_date,
  t.end_date
FROM raw_hibob_time_off t
WHERE t.employee_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-measure-hibob',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated measure SELECTs',
                    'en' => 'Curated measure SELECTs',
                ],
                'notes' => [
                    'de' => 'Headcount, Hires, Terminations, Time Off Days.',
                    'en' => 'Headcount, hires, terminations, time-off days.',
                ],
                'sql' => '-- Active headcount
SELECT COUNT(*) AS active_headcount
FROM curated_dim_hibob_employee
WHERE status = \'active\';

-- Hires in period
SELECT COUNT(*) AS hires
FROM curated_dim_hibob_employee
WHERE hire_date BETWEEN :period_start AND :period_end;

-- Approved time-off days
SELECT COALESCE(SUM(days),0) AS time_off_days
FROM curated_fct_hibob_time_off
WHERE status = \'approved\'
  AND start_date BETWEEN :period_start AND :period_end;',
            ],
        ],
    ],
    'factorial' => [
        'sqlExamples' => [
            [
                'id' => 'raw-factorial-employee',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Employee + Employment Landing',
                    'en' => 'RAW employee + employment landing',
                ],
                'notes' => [
                    'de' => 'Workforce-PII in RAW; keine Gehaltsklartext-Spalten in Default Extract.',
                    'en' => 'Workforce PII in RAW; no salary cleartext columns in default extract.',
                ],
                'sql' => '-- Warehouse-neutral RAW from Factorial HRIS extract
CREATE TABLE raw_factorial_employee (
  employee_id          VARCHAR,
  work_email           VARCHAR, -- workforce PII
  first_name           VARCHAR, -- PII
  last_name            VARCHAR, -- PII
  status               VARCHAR,
  hire_date            DATE,
  termination_date     DATE,
  department_id        VARCHAR,
  location_id          VARCHAR,
  _loaded_at           TIMESTAMP
  -- omit national_id cleartext; omit salary
);

CREATE TABLE raw_factorial_employment (
  employment_id        VARCHAR,
  employee_id          VARCHAR,
  job_title            VARCHAR,
  employment_type      VARCHAR,
  fte                  DECIMAL(6,3),
  effective_on         DATE,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'raw-factorial-timeoff-manager',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Time Off + Manager Landing',
                    'en' => 'RAW time off + manager landing',
                ],
                'notes' => [
                    'de' => 'Absence Meta ohne Medical Notes; Manager als ids.',
                    'en' => 'Absence meta without medical notes; manager as ids.',
                ],
                'sql' => 'CREATE TABLE raw_factorial_time_off (
  time_off_id          VARCHAR,
  employee_id          VARCHAR,
  type                 VARCHAR,
  status               VARCHAR,
  days                 DECIMAL(8,2),
  start_date           DATE,
  end_date             DATE,
  _loaded_at           TIMESTAMP
  -- no medical notes
);

CREATE TABLE raw_factorial_manager (
  employee_id          VARCHAR,
  manager_id           VARCHAR,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'curated-fct-factorial-employee',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated dim employee',
                    'en' => 'Curated dim employee',
                ],
                'notes' => [
                    'de' => 'Ohne E-Mail/Name Klartext.',
                    'en' => 'Without email/name cleartext.',
                ],
                'sql' => 'CREATE TABLE curated_dim_factorial_employee AS
SELECT
  employee_id,
  status,
  hire_date,
  termination_date,
  department_id,
  location_id
  -- omit work_email, first_name, last_name
FROM raw_factorial_employee;',
            ],
            [
                'id' => 'curated-fct-factorial-timeoff',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated fact time off',
                    'en' => 'Curated fact time off',
                ],
                'notes' => [
                    'de' => 'Approved Absences Grain.',
                    'en' => 'Approved absences grain.',
                ],
                'sql' => 'CREATE TABLE curated_fct_factorial_time_off AS
SELECT
  t.time_off_id,
  t.employee_id,
  t.type,
  t.status,
  t.days,
  t.start_date,
  t.end_date
FROM raw_factorial_time_off t
WHERE t.employee_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-measure-factorial',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated measure SELECTs',
                    'en' => 'Curated measure SELECTs',
                ],
                'notes' => [
                    'de' => 'Headcount, Hires, Terminations, Time Off Days.',
                    'en' => 'Headcount, hires, terminations, time-off days.',
                ],
                'sql' => '-- Active headcount
SELECT COUNT(*) AS active_headcount
FROM curated_dim_factorial_employee
WHERE status = \'active\';

-- Hires in period
SELECT COUNT(*) AS hires
FROM curated_dim_factorial_employee
WHERE hire_date BETWEEN :period_start AND :period_end;

-- Approved time-off days
SELECT COALESCE(SUM(days),0) AS time_off_days
FROM curated_fct_factorial_time_off
WHERE status = \'approved\'
  AND start_date BETWEEN :period_start AND :period_end;',
            ],
        ],
    ],
    'greenhouse' => [
        'sqlExamples' => [
            [
                'id' => 'raw-greenhouse-candidate-app',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Candidate + Application Landing',
                    'en' => 'RAW candidate + application landing',
                ],
                'notes' => [
                    'de' => 'Bewerber-PII in RAW; keine CV Bodies.',
                    'en' => 'Applicant PII in RAW; no CV bodies.',
                ],
                'sql' => '-- Warehouse-neutral RAW from Greenhouse ATS extract
CREATE TABLE raw_greenhouse_candidate (
  candidate_id         VARCHAR,
  email                VARCHAR, -- applicant PII
  first_name           VARCHAR, -- PII
  last_name            VARCHAR, -- PII
  phone                VARCHAR, -- PII
  _loaded_at           TIMESTAMP
  -- no resume body
);

CREATE TABLE raw_greenhouse_application (
  application_id       VARCHAR,
  candidate_id         VARCHAR,
  job_id               VARCHAR,
  stage_id             VARCHAR,
  status               VARCHAR,
  applied_at           TIMESTAMP,
  hired_at             TIMESTAMP,
  source_id            VARCHAR,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'raw-greenhouse-job-offer',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Job + Offer Meta Landing',
                    'en' => 'RAW job + offer meta landing',
                ],
                'notes' => [
                    'de' => 'Offer ohne Salary Klartext in Default Extract.',
                    'en' => 'Offer without salary cleartext in default extract.',
                ],
                'sql' => 'CREATE TABLE raw_greenhouse_job (
  job_id               VARCHAR,
  title                VARCHAR,
  department           VARCHAR,
  status               VARCHAR,
  opened_at            TIMESTAMP,
  _loaded_at           TIMESTAMP
);

CREATE TABLE raw_greenhouse_offer (
  offer_id             VARCHAR,
  application_id       VARCHAR,
  status               VARCHAR,
  sent_at              TIMESTAMP,
  decided_at           TIMESTAMP,
  _loaded_at           TIMESTAMP
  -- omit salary cleartext from default landing
);',
            ],
            [
                'id' => 'curated-fct-greenhouse-application',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated fact application',
                    'en' => 'Curated fact application',
                ],
                'notes' => [
                    'de' => 'Pipeline Grain — Candidate/Job Pflicht.',
                    'en' => 'Pipeline grain — candidate/job required.',
                ],
                'sql' => 'CREATE TABLE curated_fct_greenhouse_application AS
SELECT
  a.application_id,
  a.candidate_id,
  a.job_id,
  a.stage_id,
  a.status,
  a.applied_at,
  a.hired_at,
  a.source_id
FROM raw_greenhouse_application a
WHERE a.candidate_id IS NOT NULL
  AND a.job_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-dims-greenhouse',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated dims job / candidate',
                    'en' => 'Curated dims job / candidate',
                ],
                'notes' => [
                    'de' => 'Candidate ohne E-Mail/Name.',
                    'en' => 'Candidate without email/name.',
                ],
                'sql' => 'CREATE TABLE curated_dim_greenhouse_job AS
SELECT job_id, title, department, status, opened_at
FROM raw_greenhouse_job;

CREATE TABLE curated_dim_greenhouse_candidate AS
SELECT candidate_id
  -- omit email, names, phone
FROM raw_greenhouse_candidate;',
            ],
            [
                'id' => 'curated-measure-greenhouse',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated measure SELECTs',
                    'en' => 'Curated measure SELECTs',
                ],
                'notes' => [
                    'de' => 'Applications, Hires, Time-to-Hire, Open Jobs.',
                    'en' => 'Applications, hires, time-to-hire, open jobs.',
                ],
                'sql' => '-- Applications in period
SELECT COUNT(*) AS applications
FROM curated_fct_greenhouse_application
WHERE applied_at BETWEEN :period_start AND :period_end;

-- Hires in period
SELECT COUNT(*) AS hires
FROM curated_fct_greenhouse_application
WHERE status = \'hired\'
  AND hired_at BETWEEN :period_start AND :period_end;

-- Avg time to hire (days)
SELECT AVG(EXTRACT(EPOCH FROM (hired_at - applied_at))/86400.0) AS avg_time_to_hire_days
FROM curated_fct_greenhouse_application
WHERE status = \'hired\'
  AND hired_at IS NOT NULL;

-- Open jobs
SELECT COUNT(*) AS open_jobs
FROM curated_dim_greenhouse_job
WHERE status = \'open\';',
            ],
        ],
    ],
    'softgarden' => [
        'sqlExamples' => [
            [
                'id' => 'raw-softgarden-candidate-app',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Candidate + Application Landing',
                    'en' => 'RAW candidate + application landing',
                ],
                'notes' => [
                    'de' => 'Bewerber-PII in RAW; keine CV Bodies.',
                    'en' => 'Applicant PII in RAW; no CV bodies.',
                ],
                'sql' => '-- Warehouse-neutral RAW from softgarden ATS extract
CREATE TABLE raw_softgarden_candidate (
  candidate_id         VARCHAR,
  email                VARCHAR, -- applicant PII
  first_name           VARCHAR, -- PII
  last_name            VARCHAR, -- PII
  phone                VARCHAR, -- PII
  _loaded_at           TIMESTAMP
  -- no resume body
);

CREATE TABLE raw_softgarden_application (
  application_id       VARCHAR,
  candidate_id         VARCHAR,
  job_id               VARCHAR,
  stage_id             VARCHAR,
  status               VARCHAR,
  applied_at           TIMESTAMP,
  hired_at             TIMESTAMP,
  source_id            VARCHAR,
  _loaded_at           TIMESTAMP
);',
            ],
            [
                'id' => 'raw-softgarden-job-offer',
                'stage' => 'raw',
                'title' => [
                    'de' => 'RAW Job + Offer Meta Landing',
                    'en' => 'RAW job + offer meta landing',
                ],
                'notes' => [
                    'de' => 'Offer ohne Salary Klartext in Default Extract.',
                    'en' => 'Offer without salary cleartext in default extract.',
                ],
                'sql' => 'CREATE TABLE raw_softgarden_job (
  job_id               VARCHAR,
  title                VARCHAR,
  department           VARCHAR,
  status               VARCHAR,
  opened_at            TIMESTAMP,
  _loaded_at           TIMESTAMP
);

CREATE TABLE raw_softgarden_offer (
  offer_id             VARCHAR,
  application_id       VARCHAR,
  status               VARCHAR,
  sent_at              TIMESTAMP,
  decided_at           TIMESTAMP,
  _loaded_at           TIMESTAMP
  -- omit salary cleartext from default landing
);',
            ],
            [
                'id' => 'curated-fct-softgarden-application',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated fact application',
                    'en' => 'Curated fact application',
                ],
                'notes' => [
                    'de' => 'Pipeline Grain — Candidate/Job Pflicht.',
                    'en' => 'Pipeline grain — candidate/job required.',
                ],
                'sql' => 'CREATE TABLE curated_fct_softgarden_application AS
SELECT
  a.application_id,
  a.candidate_id,
  a.job_id,
  a.stage_id,
  a.status,
  a.applied_at,
  a.hired_at,
  a.source_id
FROM raw_softgarden_application a
WHERE a.candidate_id IS NOT NULL
  AND a.job_id IS NOT NULL;',
            ],
            [
                'id' => 'curated-dims-softgarden',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated dims job / candidate',
                    'en' => 'Curated dims job / candidate',
                ],
                'notes' => [
                    'de' => 'Candidate ohne E-Mail/Name.',
                    'en' => 'Candidate without email/name.',
                ],
                'sql' => 'CREATE TABLE curated_dim_softgarden_job AS
SELECT job_id, title, department, status, opened_at
FROM raw_softgarden_job;

CREATE TABLE curated_dim_softgarden_candidate AS
SELECT candidate_id
  -- omit email, names, phone
FROM raw_softgarden_candidate;',
            ],
            [
                'id' => 'curated-measure-softgarden',
                'stage' => 'curated',
                'title' => [
                    'de' => 'Curated measure SELECTs',
                    'en' => 'Curated measure SELECTs',
                ],
                'notes' => [
                    'de' => 'Applications, Hires, Time-to-Hire, Open Jobs.',
                    'en' => 'Applications, hires, time-to-hire, open jobs.',
                ],
                'sql' => '-- Applications in period
SELECT COUNT(*) AS applications
FROM curated_fct_softgarden_application
WHERE applied_at BETWEEN :period_start AND :period_end;

-- Hires in period
SELECT COUNT(*) AS hires
FROM curated_fct_softgarden_application
WHERE status = \'hired\'
  AND hired_at BETWEEN :period_start AND :period_end;

-- Avg time to hire (days)
SELECT AVG(EXTRACT(EPOCH FROM (hired_at - applied_at))/86400.0) AS avg_time_to_hire_days
FROM curated_fct_softgarden_application
WHERE status = \'hired\'
  AND hired_at IS NOT NULL;

-- Open jobs
SELECT COUNT(*) AS open_jobs
FROM curated_dim_softgarden_job
WHERE status = \'open\';',
            ],
        ],
    ],
];
