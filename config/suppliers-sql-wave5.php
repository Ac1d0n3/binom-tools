<?php

/**
 * Wave 5 warehouse SQL examples — Workplace/Identity source-native (Landing RAW + Curated).
 * Warehouse-neutral dialect — not Graph DDL / GitHub API schema / SharePoint Search / Admin SDK dumps.
 * Curated facts = activity/metadata KPIs — NOT file/mail/source bodies or secrets.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'entra-id' => [
        'sqlExamples' => [
            [
                'id' => 'raw-entra-user-group',
                'stage' => 'raw',
                'title' => ['de' => 'RAW User + Group Landing', 'en' => 'RAW user + group landing'],
                'notes' => [
                    'de' => 'Graph Directory Shape — UPN/mail = Workforce-PII; keine Secrets/Certificates.',
                    'en' => 'Graph directory shape — UPN/mail = workforce PII; no secrets/certificates.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Microsoft Graph directory extract\nCREATE TABLE raw_entra_user (\n  user_id              VARCHAR,\n  user_principal_name  VARCHAR, -- workforce PII\n  mail                 VARCHAR, -- workforce PII\n  display_name         VARCHAR,\n  account_enabled      BOOLEAN,\n  user_type            VARCHAR, -- Member / Guest\n  created_at           TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_entra_group (\n  group_id             VARCHAR,\n  display_name         VARCHAR,\n  mail_enabled         BOOLEAN,\n  security_enabled     BOOLEAN,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-entra-signin-membership',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Sign-in + Membership Landing', 'en' => 'RAW sign-in + membership landing'],
                'notes' => [
                    'de' => 'Sign-in Logs restriktiv; Membership als ids — kein Secret Material.',
                    'en' => 'Sign-in logs restricted; membership as ids — no secret material.',
                ],
                'sql' => "CREATE TABLE raw_entra_signin (\n  signin_id            VARCHAR,\n  user_id              VARCHAR,\n  app_display_name     VARCHAR,\n  status_error_code    INT,\n  conditional_access   VARCHAR,\n  created_at           TIMESTAMP,\n  ip_address           VARCHAR, -- security-sensitive\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_entra_group_membership (\n  group_id             VARCHAR,\n  user_id              VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_entra_app_credential_meta (\n  application_id       VARCHAR,\n  app_id               VARCHAR,\n  key_id               VARCHAR,\n  credential_type      VARCHAR, -- password | certificate metadata only\n  end_at               TIMESTAMP, -- expiry metadata — never secret value\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-entra-signin',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact entra sign-in', 'en' => 'Curated fact entra sign-in'],
                'notes' => [
                    'de' => 'Activity-KPI Grain — ohne IP/UPN Klartext; Success Flag.',
                    'en' => 'Activity KPI grain — no IP/UPN cleartext; success flag.',
                ],
                'sql' => "CREATE TABLE curated_fct_entra_signin AS\nSELECT\n  s.signin_id,\n  s.user_id,\n  s.app_display_name,\n  s.status_error_code,\n  s.conditional_access,\n  s.created_at,\n  CASE WHEN s.status_error_code = 0 THEN TRUE ELSE FALSE END AS is_success\n  -- omit ip_address from default analytics facts\nFROM raw_entra_signin s\nWHERE s.user_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-entra',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim user / membership fact', 'en' => 'Curated dim user / membership fact'],
                'notes' => [
                    'de' => 'User ohne UPN/mail; Membership für Disabled-in-Groups DQ.',
                    'en' => 'User without UPN/mail; membership for disabled-in-groups DQ.',
                ],
                'sql' => "CREATE TABLE curated_dim_entra_user AS\nSELECT\n  user_id,\n  account_enabled,\n  user_type,\n  created_at\n  -- omit user_principal_name, mail, display_name from default analytics dims\nFROM raw_entra_user;\n\nCREATE TABLE curated_fct_entra_group_membership AS\nSELECT\n  m.group_id,\n  m.user_id,\n  u.account_enabled,\n  CASE WHEN u.account_enabled = FALSE THEN TRUE ELSE FALSE END AS is_disabled_member\nFROM raw_entra_group_membership m\nJOIN raw_entra_user u ON u.user_id = m.user_id;",
            ],
            [
                'id' => 'curated-measure-entra',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Sign-in Success Rate, Disabled Memberships, Credential Expiry — Periodenfilter anpassen.',
                    'en' => 'Sign-in success rate, disabled memberships, credential expiry — adapt period filters.',
                ],
                'sql' => "-- Sign-in success rate in period\nSELECT\n  AVG(CASE WHEN is_success THEN 1.0 ELSE 0.0 END) AS signin_success_rate\nFROM curated_fct_entra_signin\nWHERE created_at BETWEEN :period_start AND :period_end;\n\n-- Disabled users still in groups\nSELECT COUNT(DISTINCT user_id) AS disabled_users_in_groups\nFROM curated_fct_entra_group_membership\nWHERE is_disabled_member = TRUE;\n\n-- App credentials expiring in 30 days (metadata only)\nSELECT COUNT(*) AS credentials_expiring_30d\nFROM raw_entra_app_credential_meta\nWHERE end_at BETWEEN CURRENT_TIMESTAMP AND CURRENT_TIMESTAMP + INTERVAL '30 days';",
            ],
        ],
    ],

    'github' => [
        'sqlExamples' => [
            [
                'id' => 'raw-github-repo-pr',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Repository + PR Landing', 'en' => 'RAW repository + PR landing'],
                'notes' => [
                    'de' => 'REST/GraphQL Shape — keine Patches/Source Blobs; Author als login/id.',
                    'en' => 'REST/GraphQL shape — no patches/source blobs; author as login/id.',
                ],
                'sql' => "-- Warehouse-neutral RAW from GitHub API extract (not API DDL clone)\nCREATE TABLE raw_github_repository (\n  repo_id              BIGINT,\n  node_id              VARCHAR,\n  full_name            VARCHAR,\n  owner_login          VARCHAR,\n  visibility           VARCHAR,\n  default_branch       VARCHAR,\n  archived             BOOLEAN,\n  created_at           TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_github_pull_request (\n  pr_id                BIGINT,\n  repo_id              BIGINT,\n  number               INT,\n  state                VARCHAR,\n  author_user_id       BIGINT,\n  author_login         VARCHAR,\n  created_at           TIMESTAMP,\n  merged_at            TIMESTAMP,\n  merge_commit_sha     VARCHAR,\n  merged               BOOLEAN,\n  _loaded_at           TIMESTAMP\n  -- no body, no patch, no diff\n);",
            ],
            [
                'id' => 'raw-github-workflow-user',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Workflow Run + User Landing', 'en' => 'RAW workflow run + user landing'],
                'notes' => [
                    'de' => 'Actions Metadata only — keine Logs/Artifacts; email = PII in RAW.',
                    'en' => 'Actions metadata only — no logs/artifacts; email = PII in RAW.',
                ],
                'sql' => "CREATE TABLE raw_github_workflow_run (\n  run_id               BIGINT,\n  repo_id              BIGINT,\n  workflow_name        VARCHAR,\n  status               VARCHAR,\n  conclusion           VARCHAR,\n  run_started_at       TIMESTAMP,\n  run_updated_at       TIMESTAMP,\n  _loaded_at           TIMESTAMP\n  -- no log/artifact binaries\n);\n\nCREATE TABLE raw_github_user (\n  user_id              BIGINT,\n  login                VARCHAR,\n  email                VARCHAR, -- workforce / author PII\n  type                 VARCHAR,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_github_secret_alert_meta (\n  alert_number         INT,\n  repo_id              BIGINT,\n  state                VARCHAR,\n  secret_type          VARCHAR,\n  created_at           TIMESTAMP,\n  resolved_at          TIMESTAMP,\n  _loaded_at           TIMESTAMP\n  -- never store secret value\n);",
            ],
            [
                'id' => 'curated-fct-github-pr',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact github pull request', 'en' => 'Curated fact github pull request'],
                'notes' => [
                    'de' => 'Delivery-Grain — Repo Pflicht; ohne Body/Patch; Cycle-Time Felder.',
                    'en' => 'Delivery grain — repo required; no body/patch; cycle-time fields.',
                ],
                'sql' => "CREATE TABLE curated_fct_github_pull_request AS\nSELECT\n  p.pr_id,\n  p.repo_id,\n  p.number,\n  p.state,\n  p.author_user_id,\n  p.author_login,\n  p.created_at,\n  p.merged_at,\n  p.merge_commit_sha,\n  p.merged,\n  CASE WHEN p.merged THEN TRUE ELSE FALSE END AS is_merged\nFROM raw_github_pull_request p\nWHERE p.repo_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-github',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim repo / workflow fact', 'en' => 'Curated dim repo / workflow fact'],
                'notes' => [
                    'de' => 'Repo Dim; Workflow Fact ohne Logs; User ohne email.',
                    'en' => 'Repo dim; workflow fact without logs; user without email.',
                ],
                'sql' => "CREATE TABLE curated_dim_github_repository AS\nSELECT\n  repo_id,\n  full_name,\n  owner_login,\n  visibility,\n  default_branch,\n  archived,\n  created_at\nFROM raw_github_repository;\n\nCREATE TABLE curated_dim_github_user AS\nSELECT\n  user_id,\n  login,\n  type\n  -- omit email from default analytics dims\nFROM raw_github_user;\n\nCREATE TABLE curated_fct_github_workflow_run AS\nSELECT\n  r.run_id,\n  r.repo_id,\n  r.workflow_name,\n  r.status,\n  r.conclusion,\n  r.run_started_at,\n  r.run_updated_at,\n  CASE WHEN r.conclusion = 'success' THEN TRUE ELSE FALSE END AS is_success\nFROM raw_github_workflow_run r\nWHERE r.repo_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-github',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Merged PRs, CI Success Rate, Open Secret Alerts — Periodenfilter anpassen.',
                    'en' => 'Merged PRs, CI success rate, open secret alerts — adapt period filters.',
                ],
                'sql' => "-- Merged PRs in period\nSELECT COUNT(*) AS merged_prs\nFROM curated_fct_github_pull_request\nWHERE is_merged = TRUE\n  AND merged_at BETWEEN :period_start AND :period_end;\n\n-- CI success rate in period\nSELECT\n  AVG(CASE WHEN is_success THEN 1.0 ELSE 0.0 END) AS ci_success_rate\nFROM curated_fct_github_workflow_run\nWHERE run_started_at BETWEEN :period_start AND :period_end;\n\n-- Open secret scanning alerts (metadata only — no secret values)\nSELECT COUNT(*) AS open_secret_alerts\nFROM raw_github_secret_alert_meta\nWHERE state = 'open'\n  AND repo_id IS NOT NULL;",
            ],
        ],
    ],

    'sharepoint' => [
        'sqlExamples' => [
            [
                'id' => 'raw-spo-site-drive',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Site + Drive Landing', 'en' => 'RAW site + drive landing'],
                'notes' => [
                    'de' => 'Graph Sites/Drives — Metadata only; kein Document Content.',
                    'en' => 'Graph sites/drives — metadata only; no document content.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Microsoft Graph SharePoint extract\nCREATE TABLE raw_spo_site (\n  site_id              VARCHAR,\n  site_name            VARCHAR,\n  web_url              VARCHAR,\n  created_at           TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_spo_drive (\n  drive_id             VARCHAR,\n  site_id              VARCHAR,\n  drive_name           VARCHAR,\n  drive_type           VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-sharepoint-item-sharing',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Item Metadata + Sharing Landing', 'en' => 'RAW item metadata + sharing landing'],
                'notes' => [
                    'de' => 'Item Metadata vs Content; Sharing als Typ/Scope — keine Link-URLs/Tokens.',
                    'en' => 'Item metadata vs content; sharing as type/scope — no link URLs/tokens.',
                ],
                'sql' => "CREATE TABLE raw_sharepoint_item (\n  item_id              VARCHAR,\n  drive_id             VARCHAR,\n  site_id              VARCHAR,\n  item_name            VARCHAR,\n  created_by_user_id   VARCHAR, -- workforce key\n  last_modified_at     TIMESTAMP,\n  sensitivity_label_id VARCHAR,\n  size_bytes           BIGINT,\n  _loaded_at           TIMESTAMP\n  -- no file/page content binary\n);\n\nCREATE TABLE raw_sharepoint_sharing (\n  permission_id        VARCHAR,\n  item_id              VARCHAR,\n  site_id              VARCHAR,\n  link_scope           VARCHAR, -- anonymous | organization | users\n  link_type            VARCHAR, -- view | edit\n  expiry_at            TIMESTAMP,\n  _loaded_at           TIMESTAMP\n  -- never store sharing link URL / token\n);",
            ],
            [
                'id' => 'curated-fct-sharepoint-item-activity',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact sharepoint item activity', 'en' => 'Curated fact sharepoint item activity'],
                'notes' => [
                    'de' => 'Activity/Metadata Grain — Site Pflicht; ohne Content Bodies.',
                    'en' => 'Activity/metadata grain — site required; no content bodies.',
                ],
                'sql' => "CREATE TABLE curated_fct_sharepoint_item_activity AS\nSELECT\n  i.item_id,\n  i.drive_id,\n  i.site_id,\n  i.created_by_user_id,\n  i.last_modified_at,\n  i.sensitivity_label_id,\n  i.size_bytes,\n  CASE WHEN i.sensitivity_label_id IS NOT NULL THEN TRUE ELSE FALSE END AS has_sensitivity_label\nFROM raw_sharepoint_item i\nWHERE i.site_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-sharepoint',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim site / sharing fact', 'en' => 'Curated dim site / sharing fact'],
                'notes' => [
                    'de' => 'Site Dim; Sharing Risk Flags ohne Link-URLs.',
                    'en' => 'Site dim; sharing risk flags without link URLs.',
                ],
                'sql' => "CREATE TABLE curated_dim_sharepoint_site AS\nSELECT\n  site_id,\n  site_name,\n  created_at\n  -- web_url optional / restricted\nFROM raw_spo_site;\n\nCREATE TABLE curated_fct_sharepoint_sharing AS\nSELECT\n  s.permission_id,\n  s.item_id,\n  s.site_id,\n  s.link_scope,\n  s.link_type,\n  s.expiry_at,\n  CASE\n    WHEN s.link_scope = 'anonymous'\n     AND (s.expiry_at IS NULL OR s.expiry_at < CURRENT_TIMESTAMP)\n    THEN TRUE\n    ELSE FALSE\n  END AS anon_sharing_risk\nFROM raw_sharepoint_sharing s\nWHERE s.site_id IS NOT NULL OR s.item_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-sharepoint',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Active Items, Label Coverage, Anon Sharing Risk — Periodenfilter anpassen.',
                    'en' => 'Active items, label coverage, anon sharing risk — adapt period filters.',
                ],
                'sql' => "-- Items modified in period\nSELECT COUNT(*) AS items_modified\nFROM curated_fct_sharepoint_item_activity\nWHERE last_modified_at BETWEEN :period_start AND :period_end;\n\n-- Sensitivity label coverage\nSELECT\n  AVG(CASE WHEN has_sensitivity_label THEN 1.0 ELSE 0.0 END) AS label_coverage\nFROM curated_fct_sharepoint_item_activity;\n\n-- Anonymous sharing risk count (no URLs stored)\nSELECT COUNT(*) AS anon_sharing_risk_count\nFROM curated_fct_sharepoint_sharing\nWHERE anon_sharing_risk = TRUE;",
            ],
        ],
    ],

    'google-workspace' => [
        'sqlExamples' => [
            [
                'id' => 'raw-gws-user-ou',
                'stage' => 'raw',
                'title' => ['de' => 'RAW User + Org Unit Landing', 'en' => 'RAW user + org unit landing'],
                'notes' => [
                    'de' => 'Admin SDK Directory Shape — primaryEmail = Workforce-PII; keine Mail/Drive Bodies.',
                    'en' => 'Admin SDK Directory shape — primaryEmail = workforce PII; no mail/drive bodies.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Google Admin SDK Directory extract\nCREATE TABLE raw_gws_user (\n  user_id              VARCHAR,\n  primary_email        VARCHAR, -- workforce PII\n  org_unit_path        VARCHAR,\n  suspended            BOOLEAN,\n  created_at           TIMESTAMP,\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_gws_org_unit (\n  org_unit_id          VARCHAR,\n  org_unit_path        VARCHAR,\n  parent_org_unit_path VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-gws-login-drive-activity',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Login + Drive Activity Landing', 'en' => 'RAW login + Drive activity landing'],
                'notes' => [
                    'de' => 'Reports/Drive Activity Metadata — Login restriktiv; keine File/Mail Bodies.',
                    'en' => 'Reports/Drive Activity metadata — login restricted; no file/mail bodies.',
                ],
                'sql' => "CREATE TABLE raw_gws_login_activity (\n  event_id             VARCHAR,\n  user_id              VARCHAR,\n  login_type           VARCHAR,\n  is_suspicious        BOOLEAN,\n  created_at           TIMESTAMP,\n  ip_address           VARCHAR, -- security-sensitive\n  _loaded_at           TIMESTAMP\n);\n\nCREATE TABLE raw_gws_drive_activity (\n  activity_id          VARCHAR,\n  actor_user_id        VARCHAR,\n  file_id              VARCHAR,\n  activity_type        VARCHAR, -- create | edit | share | ...\n  created_at           TIMESTAMP,\n  _loaded_at           TIMESTAMP\n  -- no drive file content / export blob\n);\n\nCREATE TABLE raw_gws_group_membership (\n  group_id             VARCHAR,\n  user_id              VARCHAR,\n  role                 VARCHAR,\n  _loaded_at           TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-gws-login',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact gws login', 'en' => 'Curated fact gws login'],
                'notes' => [
                    'de' => 'Login Activity KPI — ohne IP/Email Klartext.',
                    'en' => 'Login activity KPI — no IP/email cleartext.',
                ],
                'sql' => "CREATE TABLE curated_fct_gws_login AS\nSELECT\n  l.event_id,\n  l.user_id,\n  l.login_type,\n  l.is_suspicious,\n  l.created_at\n  -- omit ip_address from default analytics facts\nFROM raw_gws_login_activity l\nWHERE l.user_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-gws',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim user / drive activity fact', 'en' => 'Curated dim user / drive activity fact'],
                'notes' => [
                    'de' => 'User ohne Email; Drive Activity ohne Bodies; Suspended-in-Groups Signal.',
                    'en' => 'User without email; Drive activity without bodies; suspended-in-groups signal.',
                ],
                'sql' => "CREATE TABLE curated_dim_gws_user AS\nSELECT\n  user_id,\n  org_unit_path,\n  suspended,\n  created_at\n  -- omit primary_email from default analytics dims\nFROM raw_gws_user;\n\nCREATE TABLE curated_fct_gws_drive_activity AS\nSELECT\n  a.activity_id,\n  a.actor_user_id,\n  a.file_id,\n  a.activity_type,\n  a.created_at\nFROM raw_gws_drive_activity a\nWHERE a.file_id IS NOT NULL;\n\nCREATE TABLE curated_fct_gws_group_membership AS\nSELECT\n  m.group_id,\n  m.user_id,\n  m.role,\n  u.suspended,\n  CASE WHEN u.suspended = TRUE THEN TRUE ELSE FALSE END AS is_suspended_member\nFROM raw_gws_group_membership m\nJOIN raw_gws_user u ON u.user_id = m.user_id;",
            ],
            [
                'id' => 'curated-measure-gws',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Logins, Drive Edits, Users without OU, Suspended in Groups — Periodenfilter anpassen.',
                    'en' => 'Logins, Drive edits, users without OU, suspended in groups — adapt period filters.',
                ],
                'sql' => "-- Login events in period\nSELECT COUNT(*) AS login_events\nFROM curated_fct_gws_login\nWHERE created_at BETWEEN :period_start AND :period_end;\n\n-- Drive edit/create activities in period\nSELECT COUNT(*) AS drive_edit_events\nFROM curated_fct_gws_drive_activity\nWHERE activity_type IN ('edit', 'create')\n  AND created_at BETWEEN :period_start AND :period_end;\n\n-- Active users without org unit\nSELECT COUNT(*) AS users_without_ou\nFROM curated_dim_gws_user\nWHERE suspended = FALSE\n  AND (org_unit_path IS NULL OR org_unit_path = '');\n\n-- Suspended users still in groups\nSELECT COUNT(DISTINCT user_id) AS suspended_users_in_groups\nFROM curated_fct_gws_group_membership\nWHERE is_suspended_member = TRUE;",
            ],
        ],
    ],
];
