<?php

/**
 * Wave 3 warehouse SQL examples — Collab source-native (Landing RAW + Curated).
 * Warehouse-neutral dialect — not Jira JQL / Confluence CQL / Slack API / Graph DDL.
 *
 * @return array<string, array{sqlExamples: list<array<string, mixed>>}>
 */

return [
    'jira' => [
        'sqlExamples' => [
            [
                'id' => 'raw-jira-issue',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Issue Landing', 'en' => 'RAW issue landing'],
                'notes' => [
                    'de' => 'REST/JQL-Shape — assignee/reporter als accountId; Description Body default skip.',
                    'en' => 'REST/JQL shape — assignee/reporter as accountId; skip description body by default.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Jira REST extract (not JQL)\nCREATE TABLE raw_jira_issue (\n  issue_id         VARCHAR,\n  issue_key        VARCHAR,\n  project_key      VARCHAR,\n  issue_type       VARCHAR,\n  status           VARCHAR,\n  status_category  VARCHAR,\n  priority         VARCHAR,\n  assignee_id      VARCHAR, -- workforce key\n  reporter_id      VARCHAR, -- workforce key\n  created_at       TIMESTAMP,\n  updated_at       TIMESTAMP,\n  resolved_at      TIMESTAMP,\n  resolution       VARCHAR,\n  story_points     DECIMAL(10,2),\n  sprint_id        VARCHAR,\n  _loaded_at       TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-jira-project-user',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Project + User Landing', 'en' => 'RAW project + user landing'],
                'notes' => [
                    'de' => 'emailAddress = Workforce-PII — RAW restriktiv; Curated ohne Klartext.',
                    'en' => 'emailAddress = workforce PII — keep RAW restricted; no cleartext in Curated.',
                ],
                'sql' => "CREATE TABLE raw_jira_project (\n  project_id       VARCHAR,\n  project_key      VARCHAR,\n  project_name     VARCHAR,\n  project_type     VARCHAR,\n  lead_account_id  VARCHAR,\n  _loaded_at       TIMESTAMP\n);\n\nCREATE TABLE raw_jira_user (\n  account_id       VARCHAR,\n  display_name     VARCHAR,\n  email_address    VARCHAR, -- workforce PII\n  active           BOOLEAN,\n  _loaded_at       TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-jira-issue',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact jira issue', 'en' => 'Curated fact jira issue'],
                'notes' => [
                    'de' => 'Delivery-Grain — keine Description/Comment Bodies; Keys + Status + Dates.',
                    'en' => 'Delivery grain — no description/comment bodies; keys + status + dates.',
                ],
                'sql' => "CREATE TABLE curated_fct_jira_issue AS\nSELECT\n  i.issue_id,\n  i.issue_key,\n  i.project_key,\n  i.issue_type,\n  i.status,\n  i.status_category,\n  i.priority,\n  i.assignee_id,\n  i.reporter_id,\n  i.created_at,\n  i.resolved_at,\n  i.resolution,\n  i.story_points,\n  i.sprint_id,\n  CASE WHEN i.status_category = 'Done' THEN TRUE ELSE FALSE END AS is_done\nFROM raw_jira_issue i\nWHERE i.project_key IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-jira',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim project / user', 'en' => 'Curated dim project / user'],
                'notes' => [
                    'de' => 'User ohne email in Default-Marts; project für Portfolio-Schnitte.',
                    'en' => 'User without email in default marts; project for portfolio slices.',
                ],
                'sql' => "CREATE TABLE curated_dim_jira_project AS\nSELECT\n  project_id,\n  project_key,\n  project_name,\n  project_type,\n  lead_account_id\nFROM raw_jira_project;\n\nCREATE TABLE curated_dim_jira_user AS\nSELECT\n  account_id,\n  display_name,\n  active\n  -- omit email_address from default analytics dims\nFROM raw_jira_user;",
            ],
            [
                'id' => 'curated-measure-jira',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Created, Resolved Throughput, Open Backlog — Periodenfilter anpassen.',
                    'en' => 'Created, resolved throughput, open backlog — adapt period filters.',
                ],
                'sql' => "-- Issues created in period\nSELECT COUNT(*) AS issues_created\nFROM curated_fct_jira_issue\nWHERE created_at BETWEEN :period_start AND :period_end;\n\n-- Issues resolved in period\nSELECT COUNT(*) AS issues_resolved\nFROM curated_fct_jira_issue\nWHERE is_done = TRUE\n  AND resolved_at BETWEEN :period_start AND :period_end;\n\n-- Open backlog\nSELECT COUNT(*) AS open_backlog\nFROM curated_fct_jira_issue\nWHERE is_done = FALSE;",
            ],
        ],
    ],

    'confluence' => [
        'sqlExamples' => [
            [
                'id' => 'raw-confluence-page',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Page Landing', 'en' => 'RAW page landing'],
                'notes' => [
                    'de' => 'REST Content-Shape — Metadata only; body.storage default nicht laden.',
                    'en' => 'REST content shape — metadata only; do not load body.storage by default.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Confluence REST extract\nCREATE TABLE raw_confluence_page (\n  content_id       VARCHAR,\n  space_id         VARCHAR,\n  space_key        VARCHAR,\n  title            VARCHAR,\n  status           VARCHAR, -- current, archived, draft, trashed\n  author_id        VARCHAR, -- workforce key\n  last_modifier_id VARCHAR,\n  created_at       TIMESTAMP,\n  last_modified_at TIMESTAMP,\n  version_number   INT,\n  parent_id        VARCHAR,\n  _loaded_at       TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-confluence-space-label',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Space + Label Landing', 'en' => 'RAW space + label landing'],
                'notes' => [
                    'de' => 'Space Status und Labels für Inventory; Permissions separat und restriktiv.',
                    'en' => 'Space status and labels for inventory; keep permissions separate and restricted.',
                ],
                'sql' => "CREATE TABLE raw_confluence_space (\n  space_id         VARCHAR,\n  space_key        VARCHAR,\n  space_name       VARCHAR,\n  space_type       VARCHAR,\n  status           VARCHAR, -- current, archived\n  owner_id         VARCHAR,\n  _loaded_at       TIMESTAMP\n);\n\nCREATE TABLE raw_confluence_label (\n  content_id       VARCHAR,\n  label_name       VARCHAR,\n  _loaded_at       TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-confluence-page',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact confluence page', 'en' => 'Curated fact confluence page'],
                'notes' => [
                    'de' => 'Knowledge Inventory Grain — ohne Page Body Klartext.',
                    'en' => 'Knowledge inventory grain — no page body cleartext.',
                ],
                'sql' => "CREATE TABLE curated_fct_confluence_page AS\nSELECT\n  p.content_id,\n  p.space_id,\n  p.space_key,\n  p.title,\n  p.status,\n  p.author_id,\n  p.last_modifier_id,\n  p.created_at,\n  p.last_modified_at,\n  p.version_number,\n  CASE WHEN p.status = 'current' THEN TRUE ELSE FALSE END AS is_current\nFROM raw_confluence_page p\nWHERE p.space_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-confluence',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim space / label', 'en' => 'Curated dim space / label'],
                'notes' => [
                    'de' => 'Space ohne Permission-Principals; Labels als Topic-Dim.',
                    'en' => 'Space without permission principals; labels as topic dim.',
                ],
                'sql' => "CREATE TABLE curated_dim_confluence_space AS\nSELECT\n  space_id,\n  space_key,\n  space_name,\n  space_type,\n  status,\n  owner_id\nFROM raw_confluence_space;\n\nCREATE TABLE curated_dim_confluence_label AS\nSELECT DISTINCT\n  label_name\nFROM raw_confluence_label\nWHERE label_name IS NOT NULL;",
            ],
            [
                'id' => 'curated-measure-confluence',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Current Pages, Created, Edited — archived Spaces filtern.',
                    'en' => 'Current pages, created, edited — filter archived spaces.',
                ],
                'sql' => "-- Current pages in active spaces\nSELECT COUNT(*) AS current_pages\nFROM curated_fct_confluence_page p\nJOIN curated_dim_confluence_space s ON s.space_id = p.space_id\nWHERE p.is_current = TRUE\n  AND s.status = 'current';\n\n-- Pages created in period\nSELECT COUNT(*) AS pages_created\nFROM curated_fct_confluence_page\nWHERE created_at BETWEEN :period_start AND :period_end;\n\n-- Pages edited in period\nSELECT COUNT(*) AS pages_edited\nFROM curated_fct_confluence_page\nWHERE last_modified_at BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'slack' => [
        'sqlExamples' => [
            [
                'id' => 'raw-slack-channel',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Channel Landing', 'en' => 'RAW channel landing'],
                'notes' => [
                    'de' => 'Conversations Metadata — keine Message Bodies; purpose für Inventory-DQ.',
                    'en' => 'Conversations metadata — no message bodies; purpose for inventory DQ.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Slack Conversations API (metadata only)\nCREATE TABLE raw_slack_channel (\n  channel_id       VARCHAR,\n  team_id          VARCHAR,\n  name             VARCHAR,\n  purpose          VARCHAR,\n  topic            VARCHAR,\n  creator_id       VARCHAR, -- workforce key\n  is_archived      BOOLEAN,\n  is_private       BOOLEAN,\n  num_members      INT,\n  created_at       TIMESTAMP,\n  _loaded_at       TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-slack-user-activity',
                'stage' => 'raw',
                'title' => ['de' => 'RAW User + Channel Activity Landing', 'en' => 'RAW user + channel activity landing'],
                'notes' => [
                    'de' => 'Activity Facts ohne Text — Admin Analytics / counts; email = PII in RAW.',
                    'en' => 'Activity facts without text — Admin Analytics / counts; email = PII in RAW.',
                ],
                'sql' => "CREATE TABLE raw_slack_user (\n  user_id          VARCHAR,\n  team_id          VARCHAR,\n  display_name     VARCHAR,\n  email            VARCHAR, -- workforce PII\n  is_deleted       BOOLEAN,\n  is_bot           BOOLEAN,\n  _loaded_at       TIMESTAMP\n);\n\n-- Prefer aggregates over message text extracts\nCREATE TABLE raw_slack_channel_activity_daily (\n  activity_date    DATE,\n  channel_id       VARCHAR,\n  team_id          VARCHAR,\n  message_count    INT, -- aggregate only — no message text\n  active_user_count INT,\n  _loaded_at       TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-slack-channel-activity',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact slack channel activity', 'en' => 'Curated fact slack channel activity'],
                'notes' => [
                    'de' => 'message_count Aggregates — kein Klartext in curated_fct_slack_*.',
                    'en' => 'message_count aggregates — no cleartext in curated_fct_slack_*.',
                ],
                'sql' => "CREATE TABLE curated_fct_slack_channel_activity AS\nSELECT\n  a.activity_date,\n  a.channel_id,\n  a.team_id,\n  a.message_count,\n  a.active_user_count,\n  c.name AS channel_name,\n  c.is_archived,\n  c.is_private,\n  c.num_members\nFROM raw_slack_channel_activity_daily a\nLEFT JOIN raw_slack_channel c ON c.channel_id = a.channel_id\nWHERE COALESCE(c.is_private, FALSE) = FALSE; -- public channel activity only by default",
            ],
            [
                'id' => 'curated-dims-slack',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim channel / user', 'en' => 'Curated dim channel / user'],
                'notes' => [
                    'de' => 'Channel Inventory; User ohne email in Default-Marts.',
                    'en' => 'Channel inventory; user without email in default marts.',
                ],
                'sql' => "CREATE TABLE curated_dim_slack_channel AS\nSELECT\n  channel_id,\n  team_id,\n  name,\n  purpose,\n  creator_id,\n  is_archived,\n  is_private,\n  num_members,\n  created_at\nFROM raw_slack_channel;\n\nCREATE TABLE curated_dim_slack_user AS\nSELECT\n  user_id,\n  team_id,\n  display_name,\n  is_deleted,\n  is_bot\n  -- omit email from default analytics dims\nFROM raw_slack_user;",
            ],
            [
                'id' => 'curated-measure-slack',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Active Channels, Message Volume, Active Users — Retention/TTL beachten.',
                    'en' => 'Active channels, message volume, active users — respect retention/TTL.',
                ],
                'sql' => "-- Active (non-archived) public channels\nSELECT COUNT(*) AS active_channels\nFROM curated_dim_slack_channel\nWHERE is_archived = FALSE\n  AND is_private = FALSE;\n\n-- Message volume in period (aggregates only)\nSELECT SUM(message_count) AS message_volume\nFROM curated_fct_slack_channel_activity\nWHERE activity_date BETWEEN :period_start AND :period_end;\n\n-- Active users in period (max daily actives — adapt grain as needed)\nSELECT SUM(active_user_count) AS active_user_events\nFROM curated_fct_slack_channel_activity\nWHERE activity_date BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],

    'microsoft-teams' => [
        'sqlExamples' => [
            [
                'id' => 'raw-teams-team-channel',
                'stage' => 'raw',
                'title' => ['de' => 'RAW Team + Channel Landing', 'en' => 'RAW team + channel landing'],
                'notes' => [
                    'de' => 'Graph Metadata — teamId/channelId Keys; keine Message Bodies.',
                    'en' => 'Graph metadata — teamId/channelId keys; no message bodies.',
                ],
                'sql' => "-- Warehouse-neutral RAW from Microsoft Graph extract (metadata only)\nCREATE TABLE raw_teams_team (\n  team_id          VARCHAR,\n  display_name     VARCHAR,\n  description      VARCHAR,\n  is_archived      BOOLEAN,\n  visibility       VARCHAR,\n  created_at       TIMESTAMP,\n  _loaded_at       TIMESTAMP\n);\n\nCREATE TABLE raw_teams_channel (\n  channel_id       VARCHAR,\n  team_id          VARCHAR,\n  display_name     VARCHAR,\n  membership_type  VARCHAR, -- standard, private, shared\n  created_at       TIMESTAMP,\n  _loaded_at       TIMESTAMP\n);",
            ],
            [
                'id' => 'raw-teams-user-activity',
                'stage' => 'raw',
                'title' => ['de' => 'RAW User + Channel Activity Landing', 'en' => 'RAW user + channel activity landing'],
                'notes' => [
                    'de' => 'Activity ohne body.content — UPN/mail = PII in RAW; curated nur Aggregates.',
                    'en' => 'Activity without body.content — UPN/mail = PII in RAW; curated aggregates only.',
                ],
                'sql' => "CREATE TABLE raw_teams_user (\n  user_id          VARCHAR,\n  display_name     VARCHAR,\n  user_principal_name VARCHAR, -- workforce PII\n  mail             VARCHAR, -- workforce PII\n  account_enabled  BOOLEAN,\n  _loaded_at       TIMESTAMP\n);\n\n-- Prefer aggregates over chat/channel message text\nCREATE TABLE raw_teams_channel_activity_daily (\n  activity_date    DATE,\n  team_id          VARCHAR,\n  channel_id       VARCHAR,\n  message_count    INT, -- aggregate only — no message text\n  active_user_count INT,\n  _loaded_at       TIMESTAMP\n);",
            ],
            [
                'id' => 'curated-fct-teams-channel-activity',
                'stage' => 'curated',
                'title' => ['de' => 'Curated fact teams channel activity', 'en' => 'Curated fact teams channel activity'],
                'notes' => [
                    'de' => 'message_count Aggregates mit Team/Channel-Kontext — kein Klartext.',
                    'en' => 'message_count aggregates with team/channel context — no cleartext.',
                ],
                'sql' => "CREATE TABLE curated_fct_teams_channel_activity AS\nSELECT\n  a.activity_date,\n  a.team_id,\n  a.channel_id,\n  a.message_count,\n  a.active_user_count,\n  t.display_name AS team_name,\n  t.is_archived,\n  c.display_name AS channel_name,\n  c.membership_type\nFROM raw_teams_channel_activity_daily a\nJOIN raw_teams_team t ON t.team_id = a.team_id\nJOIN raw_teams_channel c ON c.channel_id = a.channel_id AND c.team_id = a.team_id\nWHERE a.team_id IS NOT NULL\n  AND a.channel_id IS NOT NULL;",
            ],
            [
                'id' => 'curated-dims-teams',
                'stage' => 'curated',
                'title' => ['de' => 'Curated dim team / user', 'en' => 'Curated dim team / user'],
                'notes' => [
                    'de' => 'Team Inventory; User ohne UPN/mail in Default-Marts.',
                    'en' => 'Team inventory; user without UPN/mail in default marts.',
                ],
                'sql' => "CREATE TABLE curated_dim_teams_team AS\nSELECT\n  team_id,\n  display_name,\n  is_archived,\n  visibility,\n  created_at\nFROM raw_teams_team;\n\nCREATE TABLE curated_dim_teams_user AS\nSELECT\n  user_id,\n  display_name,\n  account_enabled\n  -- omit user_principal_name, mail from default analytics dims\nFROM raw_teams_user;",
            ],
            [
                'id' => 'curated-measure-teams',
                'stage' => 'curated',
                'title' => ['de' => 'Curated measure SELECTs', 'en' => 'Curated measure SELECTs'],
                'notes' => [
                    'de' => 'Active Teams, Message Volume, Active Users — Purview Retention beachten.',
                    'en' => 'Active teams, message volume, active users — respect Purview retention.',
                ],
                'sql' => "-- Active (non-archived) teams\nSELECT COUNT(*) AS active_teams\nFROM curated_dim_teams_team\nWHERE is_archived = FALSE;\n\n-- Channel message volume in period (aggregates only)\nSELECT SUM(message_count) AS message_volume\nFROM curated_fct_teams_channel_activity\nWHERE activity_date BETWEEN :period_start AND :period_end;\n\n-- Active user events in period\nSELECT SUM(active_user_count) AS active_user_events\nFROM curated_fct_teams_channel_activity\nWHERE activity_date BETWEEN :period_start AND :period_end;",
            ],
        ],
    ],
];
