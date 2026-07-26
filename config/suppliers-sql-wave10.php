<?php

/**
 * Wave 10 warehouse SQL examples — Workplace/Collab/Learning/Marketing source-native (Landing RAW + Curated).
 * Warehouse-neutral dialect — not Graph DDL / GraphQL schema / Moodle DB schema / AEP dataset schema.
 * Curated facts = activity/metadata KPIs — NOT mail/file/submission/creative bodies or secrets.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'exchange' => [
        'sqlExamples' => [
            [
                'id' => 'raw-exchange-mailbox-mailitem',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Mailbox + Mail Item Landing', 'en' => 'RAW mailbox + mail item landing'],
                'notes' => [
                    'de' => 'Graph Mail Shape — from/to = Workforce-PII; keine Bodies/Attachments.',
                    'en' => 'Graph mail shape — from/to = workforce PII; no bodies/attachments.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Microsoft Graph mail extract\nCREATE TABLE raw_exchange_mailbox (\n  mailbox_id           VARCHAR,\n  primary_smtp_address VARCHAR, -- workforce PII\n  display_name         VARCHAR, -- workforce PII\n  recipient_type       VARCHAR, -- UserMailbox | SharedMailbox | RoomMailbox\n  when_created         TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_exchange_mail_item_meta (\n  message_id           VARCHAR,\n  mailbox_id           VARCHAR,\n  from_email           VARCHAR, -- PII\n  received_at          TIMESTAMP,\n  has_attachments      BOOLEAN,\n  size_bytes           INT,\n  folder               VARCHAR,\n  _loaded_at           TIMESTAMP\n  -- no body, no bodyPreview, no attachment content\n);",
            ],
            [
                'id' => 'raw-exchange-calendar-device',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Calendar Event + Mobile Device Landing', 'en' => 'RAW calendar event + mobile device landing'],
                'notes' => [
                    'de' => 'Event Meta — subject/attendees sensibel; Device Meta ohne Content.',
                    'en' => 'Event meta — subject/attendees sensitive; device meta without content.',
                ],
                'sql' => "CREATE TABLE raw_exchange_calendar_event (\n  event_id             VARCHAR,\n  mailbox_id           VARCHAR,\n  organizer_email      VARCHAR, -- PII\n  subject              VARCHAR, -- PII, often sensitive\n  start_at             TIMESTAMP,\n  end_at               TIMESTAMP,\n  is_cancelled         BOOLEAN,\n  attendee_count       INT,\n  _loaded_at           TIMESTAMP\n  -- no event body/description\n);\n\nCREATE TABLE raw_exchange_mobile_device (\n  device_id            VARCHAR,\n  mailbox_id           VARCHAR,\n  device_type          VARCHAR,\n  last_sync_at         TIMESTAMP,\n  access_state         VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-exchange-mailitem',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact exchange mail item', 'en' => 'Curated fact exchange mail item'],
                'notes' => [
                    'de' => 'Volume-Grain — ohne From/To Klartext; nur Meta-Flags.',
                    'en' => 'Volume grain — no from/to cleartext; meta flags only.',
                ],
                'sql' => "CREATE TABLE curated_fct_exchange_mail_item AS\nSELECT\n  m.message_id,\n  m.mailbox_id,\n  m.received_at,\n  m.has_attachments,\n  m.size_bytes,\n  m.folder\n  -- omit from_email from default analytics facts\nFROM raw_exchange_mail_item_meta m\nWHERE m.mailbox_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-exchange',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim mailbox / calendar fact', 'en' => 'Curated dim mailbox / calendar fact'],
                'notes' => [
                    'de' => 'Mailbox ohne primarySmtpAddress; Calendar Event ohne Subject.',
                    'en' => 'Mailbox without primarySmtpAddress; calendar event without subject.',
                ],
                'sql' => "CREATE TABLE curated_dim_exchange_mailbox AS\nSELECT\n  mailbox_id,\n  recipient_type,\n  when_created\n  -- omit primary_smtp_address, display_name from default analytics dims\nFROM raw_exchange_mailbox;\n\nCREATE TABLE curated_fct_exchange_calendar_event AS\nSELECT\n  e.event_id,\n  e.mailbox_id,\n  e.start_at,\n  e.end_at,\n  e.is_cancelled,\n  e.attendee_count\n  -- omit organizer_email, subject from default analytics facts\nFROM raw_exchange_calendar_event e\nWHERE e.mailbox_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-exchange',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Active Mailboxes, Emails Sent, Meetings Scheduled — Periodenfilter anpassen.',
                    'en' => 'Active mailboxes, emails sent, meetings scheduled — adapt period filters.',
                ],
                'sql' => "-- Active user mailboxes (snapshot)\nSELECT COUNT(*) AS mailboxes_active\nFROM curated_dim_exchange_mailbox\nWHERE recipient_type = 'UserMailbox';\n\n-- Emails sent in period (meta count)\nSELECT COUNT(*) AS emails_sent\nFROM curated_fct_exchange_mail_item\nWHERE folder = 'SentItems'\n  AND received_at BETWEEN :period_start AND :period_end;\n\n-- Meetings scheduled in period\nSELECT COUNT(*) AS meetings_scheduled\nFROM curated_fct_exchange_calendar_event\nWHERE is_cancelled = FALSE\n  AND start_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'monday' => [
        'sqlExamples' => [
            [
                'id' => 'raw-monday-board-item',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Board + Item Landing', 'en' => 'RAW board + item landing'],
                'notes' => [
                    'de' => 'GraphQL Shape — Item name kann sensitiv sein; kein File-Column-Content.',
                    'en' => 'GraphQL shape — item name may be sensitive; no file-column content.',
                ],
                'sql' => "-- Warehouse-neutral RAW from monday.com GraphQL extract\nCREATE TABLE raw_monday_board (\n  board_id             VARCHAR,\n  workspace_id         VARCHAR,\n  name                 VARCHAR,\n  board_kind           VARCHAR, -- public | private | share\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_monday_item (\n  item_id              VARCHAR,\n  board_id             VARCHAR,\n  group_id             VARCHAR,\n  name                 VARCHAR, -- meta, may be sensitive\n  state                VARCHAR, -- active | archived | deleted\n  creator_id           VARCHAR,\n  created_at           TIMESTAMP,\n  updated_at           TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-monday-column-update',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Column Value + Update Landing', 'en' => 'RAW column value + update landing'],
                'notes' => [
                    'de' => 'Column Value Meta — text kann PII enthalten; Update Body restriktiv.',
                    'en' => 'Column value meta — text may contain PII; update body restricted.',
                ],
                'sql' => "CREATE TABLE raw_monday_column_value (\n  item_id              VARCHAR,\n  column_id            VARCHAR,\n  type                 VARCHAR, -- status | date | people | text | ...\n  text                 VARCHAR, -- display value, review for PII\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_monday_update (\n  update_id            VARCHAR,\n  item_id              VARCHAR,\n  creator_id           VARCHAR,\n  body                 VARCHAR, -- free text, PII risk\n  created_at           TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-monday-item',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact monday item', 'en' => 'Curated fact monday item'],
                'notes' => [
                    'de' => 'Work-Grain — Board Pflicht; ohne Item-Name-Klartext optional.',
                    'en' => 'Work grain — board required; item name cleartext optional.',
                ],
                'sql' => "CREATE TABLE curated_fct_monday_item AS\nSELECT\n  i.item_id,\n  i.board_id,\n  i.group_id,\n  i.state,\n  i.creator_id,\n  i.created_at,\n  i.updated_at\n  -- omit item name from default analytics facts if treated as sensitive\nFROM raw_monday_item i\nWHERE i.board_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-monday',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim board / status fact', 'en' => 'Curated dim board / status fact'],
                'notes' => [
                    'de' => 'Board Dim; Status-Mapping über column_value.', 
                    'en' => 'Board dim; status mapping via column_value.',
                ],
                'sql' => "CREATE TABLE curated_dim_monday_board AS\nSELECT\n  board_id,\n  workspace_id,\n  name,\n  board_kind\nFROM raw_monday_board;\n\nCREATE TABLE curated_fct_monday_status AS\nSELECT\n  cv.item_id,\n  cv.column_id,\n  cv.text AS status_label,\n  CASE WHEN cv.text = 'Done' THEN TRUE ELSE FALSE END AS is_done\nFROM raw_monday_column_value cv\nWHERE cv.type = 'status';",
            ],
            [
                'id' => 'curated-measure-monday',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Items Created, Items Completed, Updates Count — Periodenfilter anpassen.',
                    'en' => 'Items created, items completed, updates count — adapt period filters.',
                ],
                'sql' => "-- Items created in period\nSELECT COUNT(*) AS items_created\nFROM curated_fct_monday_item\nWHERE created_at BETWEEN :period_start AND :period_end;\n\n-- Items completed in period\nSELECT COUNT(*) AS items_completed\nFROM curated_fct_monday_status\nWHERE is_done = TRUE;\n\n-- Updates (meta) created in period\nSELECT COUNT(*) AS updates_count\nFROM raw_monday_update\nWHERE created_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'moodle' => [
        'sqlExamples' => [
            [
                'id' => 'raw-moodle-user-course',
                'stage' => 'raw',
                'title' => ['de' => 'RAW User + Course Landing', 'en' => 'RAW user + course landing'],
                'notes' => [
                    'de' => 'Web Services Shape — email/fullname = PII; keine Submission-Inhalte.',
                    'en' => 'Web services shape — email/fullname = PII; no submission content.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Moodle web services extract\nCREATE TABLE raw_moodle_user (\n  user_id              VARCHAR,\n  email                VARCHAR, -- PII\n  fullname             VARCHAR, -- PII\n  suspended            BOOLEAN,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_moodle_course (\n  course_id            VARCHAR,\n  fullname             VARCHAR,\n  category_id          VARCHAR,\n  start_date           TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-moodle-enrollment-grade',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Enrollment + Grade Landing', 'en' => 'RAW enrollment + grade landing'],
                'notes' => [
                    'de' => 'Enrollment/Grade sind Bildungsdatensätze — restriktiver Zugriff; keine Submission-Files.',
                    'en' => 'Enrollment/grade are educational records — restricted access; no submission files.',
                ],
                'sql' => "CREATE TABLE raw_moodle_enrollment (\n  user_id              VARCHAR,\n  course_id            VARCHAR,\n  status               VARCHAR, -- active | suspended\n  time_start           TIMESTAMP,\n  time_end             TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_moodle_grade_grade (\n  user_id              VARCHAR,\n  item_id              VARCHAR,\n  final_grade          NUMERIC, -- sensitive educational record\n  time_modified        TIMESTAMP,\n  _loaded_at           TIMESTAMP\n  -- no feedback text\n);\n\nCREATE TABLE raw_moodle_quiz_attempt (\n  attempt_id           VARCHAR,\n  user_id              VARCHAR,\n  quiz_id              VARCHAR,\n  state                VARCHAR,\n  sum_grades           NUMERIC,\n  time_finish          TIMESTAMP,\n  _loaded_at           TIMESTAMP\n  -- no question/answer content\n);",
            ],
            [
                'id' => 'curated-fct-moodle-enrollment',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact moodle enrollment', 'en' => 'Curated fact moodle enrollment'],
                'notes' => [
                    'de' => 'Enrollment-Grain — Course Pflicht; Grade-Aggregate statt Einzelnoten.',
                    'en' => 'Enrollment grain — course required; grade aggregates instead of individual grades.',
                ],
                'sql' => "CREATE TABLE curated_fct_moodle_enrollment AS\nSELECT\n  e.user_id,\n  e.course_id,\n  e.status,\n  e.time_start,\n  e.time_end,\n  u.suspended\nFROM raw_moodle_enrollment e\nJOIN raw_moodle_user u ON u.user_id = e.user_id\nWHERE e.course_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-moodle',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim course / grade aggregate', 'en' => 'Curated dim course / grade aggregate'],
                'notes' => [
                    'de' => 'Course Dim; Grade Aggregat je Item statt Einzelwerte in breiten Marts.',
                    'en' => 'Course dim; grade aggregate per item instead of individual values in broad marts.',
                ],
                'sql' => "CREATE TABLE curated_dim_moodle_course AS\nSELECT\n  course_id,\n  fullname,\n  category_id,\n  start_date\nFROM raw_moodle_course;\n\nCREATE TABLE curated_agg_moodle_grade_item AS\nSELECT\n  item_id,\n  AVG(final_grade) AS avg_final_grade,\n  COUNT(*) AS grades_count\nFROM raw_moodle_grade_grade\nGROUP BY item_id;",
            ],
            [
                'id' => 'curated-measure-moodle',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Active Enrollments, Quiz Attempts, Active Learners — Periodenfilter anpassen.',
                    'en' => 'Active enrollments, quiz attempts, active learners — adapt period filters.',
                ],
                'sql' => "-- Active enrollments (snapshot)\nSELECT COUNT(*) AS active_enrollments\nFROM curated_fct_moodle_enrollment\nWHERE status = 'active';\n\n-- Quiz attempts finished in period\nSELECT COUNT(*) AS quiz_attempts_finished\nFROM raw_moodle_quiz_attempt\nWHERE state = 'finished'\n  AND time_finish BETWEEN :period_start AND :period_end;\n\n-- Average grade per item (aggregate only)\nSELECT item_id, avg_final_grade\nFROM curated_agg_moodle_grade_item\nWHERE item_id = :item;",
            ],
        ],
    ],

    'adobe-experience-cloud' => [
        'sqlExamples' => [
            [
                'id' => 'raw-aec-profile-event',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Profile + Experience Event Landing', 'en' => 'RAW profile + experience event landing'],
                'notes' => [
                    'de' => 'AEP Shape — email = direkte PII; Event Bulk hochvolumig.',
                    'en' => 'AEP shape — email = direct PII; event bulk is high volume.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Adobe Experience Platform extract\nCREATE TABLE raw_aec_profile (\n  profile_id           VARCHAR,\n  email_hash           VARCHAR, -- hashed at ingestion, never cleartext\n  consent_status_rollup VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_aec_experience_event (\n  event_id             VARCHAR,\n  profile_id           VARCHAR,\n  event_type           VARCHAR,\n  event_ts             TIMESTAMP,\n  _loaded_at           TIMESTAMP\n  -- no raw device/cookie graph fields in default select\n);",
            ],
            [
                'id' => 'raw-aec-segment-consent',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Segment Membership + Consent Landing', 'en' => 'RAW segment membership + consent landing'],
                'notes' => [
                    'de' => 'Consent ist Pflichtfeld vor jeder Aktivierung; Membership ohne Creative-Content.',
                    'en' => 'Consent is a required field before any activation; membership without creative content.',
                ],
                'sql' => "CREATE TABLE raw_aec_segment_membership (\n  profile_id           VARCHAR,\n  segment_id           VARCHAR,\n  status               VARCHAR, -- realized | exited\n  qualified_at         TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_aec_consent (\n  profile_id           VARCHAR,\n  purpose              VARCHAR, -- marketing | personalization | analytics\n  status               VARCHAR, -- opted-in | opted-out\n  consent_ts           TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-aec-event',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact aec experience event', 'en' => 'Curated fact aec experience event'],
                'notes' => [
                    'de' => 'Activity-Grain — Profile Pflicht; keine Device/IP-Klartextfelder.',
                    'en' => 'Activity grain — profile required; no device/IP cleartext fields.',
                ],
                'sql' => "CREATE TABLE curated_fct_aec_event AS\nSELECT\n  e.event_id,\n  e.profile_id,\n  e.event_type,\n  e.event_ts\nFROM raw_aec_experience_event e\nWHERE e.profile_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-aec',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim profile / consent fact', 'en' => 'Curated dim profile / consent fact'],
                'notes' => [
                    'de' => 'Profile ohne Email-Klartext; Consent-Fact als Aktivierungs-Gate.',
                    'en' => 'Profile without email cleartext; consent fact as activation gate.',
                ],
                'sql' => "CREATE TABLE curated_dim_aec_profile AS\nSELECT\n  profile_id,\n  consent_status_rollup\n  -- omit email_hash from broad-access dims; restrict to privacy-ops schema\nFROM raw_aec_profile;\n\nCREATE TABLE curated_fct_aec_consent AS\nSELECT\n  c.profile_id,\n  c.purpose,\n  c.status,\n  c.consent_ts,\n  CASE WHEN c.purpose = 'marketing' AND c.status = 'opted-in' THEN TRUE ELSE FALSE END AS marketing_consented\nFROM raw_aec_consent c;",
            ],
            [
                'id' => 'curated-measure-aec',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Active Profiles, Events Count, Consented Profiles Rate — Periodenfilter anpassen.',
                    'en' => 'Active profiles, events count, consented profiles rate — adapt period filters.',
                ],
                'sql' => "-- Active profiles in period\nSELECT COUNT(DISTINCT profile_id) AS active_profiles\nFROM curated_fct_aec_event\nWHERE event_ts BETWEEN :period_start AND :period_end;\n\n-- Events count in period\nSELECT COUNT(*) AS events_count\nFROM curated_fct_aec_event\nWHERE event_ts BETWEEN :period_start AND :period_end;\n\n-- Marketing consent rate\nSELECT\n  AVG(CASE WHEN marketing_consented THEN 1.0 ELSE 0.0 END) AS consented_profiles_rate\nFROM curated_fct_aec_consent;",
            ],
        ],
    ],
];
