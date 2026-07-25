<?php

/**
 * Wave 3 supplier library entries — Collab / work management (full template depth).
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $collabTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    return [
        [
            'id' => 'jira',
            'domain' => 'collab',
            'order' => 110,
            'label' => ['de' => 'Jira', 'en' => 'Jira'],
            'shortPurpose' => [
                'de' => 'Issues/Projekte: Delivery-Metriken — REST/JQL-Load, PII und Cycle-Time-Measures.',
                'en' => 'Issues/projects: delivery metrics — REST/JQL load, PII and cycle-time measures.',
            ],
            'entities' => [
                [
                    'id' => 'issue',
                    'label' => ['de' => 'Issue', 'en' => 'Issue'],
                    'description' => [
                        'de' => 'Jira-Issue — Fact-Kern für Created, Resolved, Cycle Time und Open Bugs.',
                        'en' => 'Jira issue — fact core for created, resolved, cycle time and open bugs.',
                    ],
                    'grain' => ['de' => 'Ein Issue', 'en' => 'One issue'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'project',
                    'label' => ['de' => 'Project', 'en' => 'Project'],
                    'description' => [
                        'de' => 'Jira-Projekt — Dimension über project_key / id.',
                        'en' => 'Jira project — dimension via project_key / id.',
                    ],
                    'grain' => ['de' => 'Ein Projekt', 'en' => 'One project'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'sprint',
                    'label' => ['de' => 'Sprint', 'en' => 'Sprint'],
                    'description' => [
                        'de' => 'Agile Sprint — Zeitraum und Story-Points-Completed.',
                        'en' => 'Agile sprint — timebox and story points completed.',
                    ],
                    'grain' => ['de' => 'Ein Sprint', 'en' => 'One sprint'],
                    'role' => ['de' => 'Zeit-/Board-Dimension', 'en' => 'Time / board dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'board',
                    'label' => ['de' => 'Board', 'en' => 'Board'],
                    'description' => [
                        'de' => 'Board (Scrum/Kanban) — optionaler Kontext für Sprint- und Flow-KPIs.',
                        'en' => 'Board (Scrum/Kanban) — optional context for sprint and flow KPIs.',
                    ],
                    'grain' => ['de' => 'Ein Board', 'en' => 'One board'],
                    'role' => ['de' => 'Optionale Dimension', 'en' => 'Optional dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'Atlassian-User — Assignee/Reporter-Join; E-Mail und displayName sind PII.',
                        'en' => 'Atlassian user — assignee/reporter join; email and displayName are PII.',
                    ],
                    'grain' => ['de' => 'Ein User (accountId)', 'en' => 'One user (accountId)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'comment',
                    'label' => ['de' => 'Comment', 'en' => 'Comment'],
                    'description' => [
                        'de' => 'Issue-Kommentar — Freitext oft PII; Volumen und Retention prüfen.',
                        'en' => 'Issue comment — free text often PII; check volume and retention.',
                    ],
                    'grain' => ['de' => 'Ein Kommentar', 'en' => 'One comment'],
                    'role' => ['de' => 'Fact / PII-Inhalt', 'en' => 'Fact / PII content'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'worklog',
                    'label' => ['de' => 'Worklog', 'en' => 'Worklog'],
                    'description' => [
                        'de' => 'Zeiterfassung am Issue — timeSpentSeconds für Effort-Measures.',
                        'en' => 'Time logged on issue — timeSpentSeconds for effort measures.',
                    ],
                    'grain' => ['de' => 'Ein Worklog-Eintrag', 'en' => 'One worklog entry'],
                    'role' => ['de' => 'Effort-Fact', 'en' => 'Effort fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'changelog',
                    'label' => ['de' => 'Changelog', 'en' => 'Changelog'],
                    'description' => [
                        'de' => 'Feld-Historie — Status-Übergänge für Cycle Time; Bulk-History oft skip.',
                        'en' => 'Field history — status transitions for cycle time; full bulk history often skip.',
                    ],
                    'grain' => ['de' => 'Eine Changelog-Zeile', 'en' => 'One changelog item'],
                    'role' => ['de' => 'Event-Fact (selektiv)', 'en' => 'Event fact (selective)'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Issue', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'REST-Join-Key', 'en' => 'REST join key']],
                ['entity' => 'Issue', 'name' => 'key', 'role' => 'key', 'why' => ['de' => 'Business-Key (PROJ-123)', 'en' => 'Business key (PROJ-123)']],
                ['entity' => 'Issue', 'name' => 'project_key', 'role' => 'dimension', 'why' => ['de' => 'Projekt-Join', 'en' => 'Project join']],
                ['entity' => 'Issue', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Workflow-Status', 'en' => 'Workflow status']],
                ['entity' => 'Issue', 'name' => 'status_category', 'role' => 'dimension', 'why' => ['de' => 'To Do / In Progress / Done', 'en' => 'To do / in progress / done']],
                ['entity' => 'Issue', 'name' => 'issuetype', 'role' => 'dimension', 'why' => ['de' => 'Bug / Story / Task', 'en' => 'Bug / story / task']],
                ['entity' => 'Issue', 'name' => 'priority', 'role' => 'dimension', 'why' => ['de' => 'Priorität', 'en' => 'Priority']],
                ['entity' => 'Issue', 'name' => 'assignee', 'role' => 'dimension', 'why' => ['de' => 'Assignee accountId', 'en' => 'Assignee accountId']],
                ['entity' => 'Issue', 'name' => 'reporter', 'role' => 'dimension', 'why' => ['de' => 'Reporter accountId', 'en' => 'Reporter accountId']],
                ['entity' => 'Issue', 'name' => 'created', 'role' => 'measure', 'why' => ['de' => 'Erstellzeit / Perioden-Grain', 'en' => 'Created time / period grain']],
                ['entity' => 'Issue', 'name' => 'resolutiondate', 'role' => 'measure', 'why' => ['de' => 'Resolved-Zeit / Cycle Time', 'en' => 'Resolved time / cycle time']],
                ['entity' => 'Issue', 'name' => 'story_points', 'role' => 'measure', 'why' => ['de' => 'Story Points (custom field)', 'en' => 'Story points (custom field)']],
                ['entity' => 'Issue', 'name' => 'sprint_id', 'role' => 'dimension', 'why' => ['de' => 'Sprint-Join', 'en' => 'Sprint join']],
                ['entity' => 'Issue', 'name' => 'updated', 'role' => 'measure', 'why' => ['de' => 'Letzte Änderung', 'en' => 'Last update']],
                ['entity' => 'Project', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Projekt-Join', 'en' => 'Project join']],
                ['entity' => 'Project', 'name' => 'key', 'role' => 'dimension', 'why' => ['de' => 'Projekt-Key / Label', 'en' => 'Project key / label']],
                ['entity' => 'Project', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Projektname', 'en' => 'Project name']],
                ['entity' => 'Sprint', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Sprint-Join', 'en' => 'Sprint join']],
                ['entity' => 'Sprint', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Sprint-Label', 'en' => 'Sprint label']],
                ['entity' => 'Sprint', 'name' => 'startDate', 'role' => 'measure', 'why' => ['de' => 'Sprint-Start', 'en' => 'Sprint start']],
                ['entity' => 'Sprint', 'name' => 'endDate', 'role' => 'measure', 'why' => ['de' => 'Sprint-Ende', 'en' => 'Sprint end']],
                ['entity' => 'Sprint', 'name' => 'state', 'role' => 'dimension', 'why' => ['de' => 'active / closed / future', 'en' => 'active / closed / future']],
                ['entity' => 'Board', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Board-Join', 'en' => 'Board join']],
                ['entity' => 'Board', 'name' => 'type', 'role' => 'dimension', 'why' => ['de' => 'scrum / kanban', 'en' => 'scrum / kanban']],
                ['entity' => 'User', 'name' => 'accountId', 'role' => 'key', 'why' => ['de' => 'User-Join (Cloud)', 'en' => 'User join (Cloud)']],
                ['entity' => 'User', 'name' => 'emailAddress', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'User', 'name' => 'displayName', 'role' => 'pii', 'why' => ['de' => 'Anzeigename / Quasi-PII', 'en' => 'Display name / quasi-PII']],
                ['entity' => 'Comment', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Kommentar-Join', 'en' => 'Comment join']],
                ['entity' => 'Comment', 'name' => 'body', 'role' => 'pii', 'why' => ['de' => 'Freitext / PII-Risiko', 'en' => 'Free text / PII risk']],
                ['entity' => 'Worklog', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Worklog-Join', 'en' => 'Worklog join']],
                ['entity' => 'Worklog', 'name' => 'timeSpentSeconds', 'role' => 'measure', 'why' => ['de' => 'Gebuchte Zeit', 'en' => 'Time spent']],
                ['entity' => 'Changelog', 'name' => 'created', 'role' => 'measure', 'why' => ['de' => 'Übergangszeitpunkt', 'en' => 'Transition timestamp']],
                ['entity' => 'Changelog', 'name' => 'field', 'role' => 'dimension', 'why' => ['de' => 'Geändertes Feld (status…)', 'en' => 'Changed field (status…)']],
            ],
            'skipTables' => [
                [
                    'name' => 'Audit log dumps',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Org-Audit — hohes Volumen, selten Delivery-KPI-relevant.',
                        'en' => 'Org audit — high volume, rarely delivery-KPI-relevant.',
                    ],
                ],
                [
                    'name' => 'Attachment binaries',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Binärdateien — nicht für Warehouse-Analytics laden.',
                        'en' => 'Binary files — do not load for warehouse analytics.',
                    ],
                ],
                [
                    'name' => 'Full changelog history bulk',
                    'category' => 'history',
                    'reason' => [
                        'de' => 'Vollständige Feld-Historie — nur Status-Transitions selektiv laden.',
                        'en' => 'Full field history — load status transitions selectively only.',
                    ],
                ],
                [
                    'name' => 'Webhook delivery logs',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Delivery-/Retry-Logs — technisches Rauschen.',
                        'en' => 'Delivery/retry logs — technical noise.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Attachment binary content', 'reason' => ['de' => 'Kein Mart-Nutzen, Speicheraufwand', 'en' => 'No mart value, storage cost']],
                ['name' => 'Full issue description HTML dumps', 'reason' => ['de' => 'Freitext-PII und Volumen', 'en' => 'Free-text PII and volume']],
                ['name' => 'Bulk changelog (all fields)', 'reason' => ['de' => 'Nur Status-Events für Cycle Time', 'en' => 'Status events only for cycle time']],
                ['name' => 'Webhook / audit payload archives', 'reason' => ['de' => 'Nicht analytisch', 'en' => 'Not analytical']],
            ],
            'dimensions' => [
                [
                    'id' => 'project',
                    'label' => ['de' => 'Project', 'en' => 'Project'],
                    'grain' => ['de' => 'project_key', 'en' => 'project_key'],
                    'notes' => [
                        'de' => 'Über fields.project.key joinen; archivierte Projekte filtern.',
                        'en' => 'Join via fields.project.key; filter archived projects.',
                    ],
                ],
                [
                    'id' => 'issuetype',
                    'label' => ['de' => 'Issue Type', 'en' => 'Issue type'],
                    'grain' => ['de' => 'issuetype.name / id', 'en' => 'issuetype.name / id'],
                    'notes' => [
                        'de' => 'Bug vs. Story für Open-Bugs und Throughput trennen.',
                        'en' => 'Separate bug vs story for open bugs and throughput.',
                    ],
                ],
                [
                    'id' => 'status_category',
                    'label' => ['de' => 'Status Category', 'en' => 'Status category'],
                    'grain' => ['de' => 'To Do / In Progress / Done', 'en' => 'To do / in progress / done'],
                    'notes' => [
                        'de' => 'Stabiler als Status-Namen — Workflow-Varianten überleben.',
                        'en' => 'More stable than status names — survives workflow variants.',
                    ],
                ],
                [
                    'id' => 'sprint',
                    'label' => ['de' => 'Sprint', 'en' => 'Sprint'],
                    'grain' => ['de' => 'sprint_id', 'en' => 'sprint_id'],
                    'notes' => [
                        'de' => 'Aktiven Sprint vs. Closed für Story-Points-Completed klären.',
                        'en' => 'Clarify active vs closed sprint for story points completed.',
                    ],
                ],
                [
                    'id' => 'priority',
                    'label' => ['de' => 'Priority', 'en' => 'Priority'],
                    'grain' => ['de' => 'priority.id / name', 'en' => 'priority.id / name'],
                    'notes' => [
                        'de' => 'Schema-übergreifend normalisieren (Highest/High/…).',
                        'en' => 'Normalize across schemes (Highest/High/…).',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['emailAddress', 'displayName'],
                    'treatment' => [
                        'de' => 'User-E-Mail und Anzeigename — taggen, RAW einschränken; accountId als Join bevorzugen.',
                        'en' => 'User email and display name — tag, restrict RAW; prefer accountId as join.',
                    ],
                ],
                [
                    'entity' => 'Comment',
                    'fields' => ['body'],
                    'treatment' => [
                        'de' => 'Kommentar-Freitext — Quasi-/Freitext-PII; optional laden oder redigieren.',
                        'en' => 'Comment free text — quasi/free-text PII; load optionally or redact.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'issue key/id, project_key, accountId, emailAddress, sprint_id.',
                        'en' => 'issue key/id, project_key, accountId, emailAddress, sprint_id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Issue, Project, User + Export-Kopien in Warehouse/BI.',
                        'en' => 'Issue, project, user + export copies in warehouse/BI.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'issues-created',
                    'example' => true,
                    'label' => ['de' => 'Issues Created', 'en' => 'Issues created'],
                    'question' => [
                        'de' => 'Wie viele Issues wurden in der Periode angelegt?',
                        'en' => 'How many issues were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM issue WHERE created IN period',
                    'grain' => ['de' => 'Issue', 'en' => 'Issue'],
                    'dimensions' => ['project', 'issuetype', 'priority', 'status_category'],
                    'fieldsUsed' => ['Issue.created', 'Issue.project_key', 'Issue.issuetype'],
                    'sourceHints' => [
                        'de' => 'created aus REST fields.created — JQL created >= … für Inkremente.',
                        'en' => 'created from REST fields.created — JQL created >= … for increments.',
                    ],
                    'adapt' => [
                        'de' => 'Subtasks und Epics aus Intake-KPIs ggf. ausschließen.',
                        'en' => 'Optionally exclude subtasks and epics from intake KPIs.',
                    ],
                ],
                [
                    'id' => 'issues-resolved',
                    'example' => true,
                    'label' => ['de' => 'Issues Resolved', 'en' => 'Issues resolved'],
                    'question' => [
                        'de' => 'Wie viele Issues wurden in der Periode resolved?',
                        'en' => 'How many issues were resolved in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM issue WHERE resolutiondate IN period',
                    'grain' => ['de' => 'Issue', 'en' => 'Issue'],
                    'dimensions' => ['project', 'issuetype', 'sprint', 'priority'],
                    'fieldsUsed' => ['Issue.resolutiondate', 'Issue.project_key', 'Issue.issuetype'],
                    'sourceHints' => [
                        'de' => 'resolutiondate nutzen — nicht nur Status „Done“ ohne Datum.',
                        'en' => 'Use resolutiondate — not only status Done without a date.',
                    ],
                    'adapt' => [
                        'de' => 'Reopens und Resolution „Duplicate“ separat behandeln.',
                        'en' => 'Treat reopens and Duplicate resolution separately.',
                    ],
                ],
                [
                    'id' => 'cycle-time-days',
                    'example' => false,
                    'label' => ['de' => 'Cycle Time (Days)', 'en' => 'Cycle time (days)'],
                    'question' => [
                        'de' => 'Wie lange dauert es von Created bis Resolved?',
                        'en' => 'How long from created to resolved?',
                    ],
                    'formula' => 'AVG(DATEDIFF(day, created, resolutiondate)) FROM issue WHERE resolutiondate IS NOT NULL',
                    'grain' => ['de' => 'Resolved Issue', 'en' => 'Resolved issue'],
                    'dimensions' => ['project', 'issuetype', 'priority', 'sprint'],
                    'fieldsUsed' => ['Issue.created', 'Issue.resolutiondate'],
                    'sourceHints' => [
                        'de' => 'Für Lead vs. Cycle Time Status-Changelog (In Progress) optional nutzen.',
                        'en' => 'Optionally use status changelog (In Progress) for lead vs cycle time.',
                    ],
                    'adapt' => [
                        'de' => 'Median vs. Average und Outlier-Caps festlegen.',
                        'en' => 'Lock median vs average and outlier caps.',
                    ],
                ],
                [
                    'id' => 'open-bugs',
                    'example' => false,
                    'label' => ['de' => 'Open Bugs', 'en' => 'Open bugs'],
                    'question' => [
                        'de' => 'Wie viele Bugs sind aktuell noch offen?',
                        'en' => 'How many bugs are currently still open?',
                    ],
                    'formula' => "COUNT(*) FROM issue WHERE issuetype = 'Bug' AND resolutiondate IS NULL",
                    'grain' => ['de' => 'Offenes Issue', 'en' => 'Open issue'],
                    'dimensions' => ['project', 'priority', 'status_category'],
                    'fieldsUsed' => ['Issue.issuetype', 'Issue.resolutiondate', 'Issue.status_category'],
                    'sourceHints' => [
                        'de' => 'issuetype-Namen lokal mappen (Bug vs. Defect).',
                        'en' => 'Map issuetype names locally (Bug vs Defect).',
                    ],
                    'adapt' => [
                        'de' => 'Snapshot-Datum und Backlog-Filter (Won\'t Do) klären.',
                        'en' => 'Clarify snapshot date and backlog filter (Won\'t Do).',
                    ],
                ],
                [
                    'id' => 'story-points-completed',
                    'example' => false,
                    'label' => ['de' => 'Story Points Completed', 'en' => 'Story points completed'],
                    'question' => [
                        'de' => 'Wie viele Story Points wurden im Sprint abgeschlossen?',
                        'en' => 'How many story points were completed in the sprint?',
                    ],
                    'formula' => 'SUM(story_points) FROM issue WHERE sprint_id = :sprint AND status_category = Done',
                    'grain' => ['de' => 'Issue in Sprint', 'en' => 'Issue in sprint'],
                    'dimensions' => ['sprint', 'project', 'issuetype'],
                    'fieldsUsed' => ['Issue.story_points', 'Issue.sprint_id', 'Issue.status_category'],
                    'sourceHints' => [
                        'de' => 'Story-Points-Custom-Field-ID pro Site mappen (REST field id).',
                        'en' => 'Map story points custom field id per site (REST field id).',
                    ],
                    'adapt' => [
                        'de' => 'Committed vs. completed und Scope-Change-Regeln festlegen.',
                        'en' => 'Lock committed vs completed and scope-change rules.',
                    ],
                ],
            ],
            'tools' => $collabTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'confluence',
            'domain' => 'collab',
            'order' => 120,
            'label' => ['de' => 'Confluence', 'en' => 'Confluence'],
            'shortPurpose' => [
                'de' => 'Seiten/Spaces: Content-Aktivität — Cloud REST-Load, PII und Knowledge-KPIs.',
                'en' => 'Pages/spaces: content activity — Cloud REST load, PII and knowledge KPIs.',
            ],
            'entities' => [
                [
                    'id' => 'page',
                    'label' => ['de' => 'Page', 'en' => 'Page'],
                    'description' => [
                        'de' => 'Confluence-Page — Fact für Created/Updated und Content-Throughput.',
                        'en' => 'Confluence page — fact for created/updated and content throughput.',
                    ],
                    'grain' => ['de' => 'Eine Page', 'en' => 'One page'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'space',
                    'label' => ['de' => 'Space', 'en' => 'Space'],
                    'description' => [
                        'de' => 'Space — Dimension über space_key; Active Spaces KPI.',
                        'en' => 'Space — dimension via space_key; active spaces KPI.',
                    ],
                    'grain' => ['de' => 'Ein Space', 'en' => 'One space'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'Autor/Editor — accountId-Join; E-Mail ist PII.',
                        'en' => 'Author/editor — accountId join; email is PII.',
                    ],
                    'grain' => ['de' => 'Ein User', 'en' => 'One user'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'comment',
                    'label' => ['de' => 'Comment', 'en' => 'Comment'],
                    'description' => [
                        'de' => 'Inline-/Footer-Kommentar — Engagement-Fact mit Freitext-PII.',
                        'en' => 'Inline/footer comment — engagement fact with free-text PII.',
                    ],
                    'grain' => ['de' => 'Ein Kommentar', 'en' => 'One comment'],
                    'role' => ['de' => 'Fact / PII-Inhalt', 'en' => 'Fact / PII content'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'attachment_meta',
                    'label' => ['de' => 'Attachment Meta', 'en' => 'Attachment meta'],
                    'description' => [
                        'de' => 'Anhang-Metadaten (ohne Binary) — Attachment-Count.',
                        'en' => 'Attachment metadata (no binary) — attachment count.',
                    ],
                    'grain' => ['de' => 'Ein Anhang-Meta', 'en' => 'One attachment meta'],
                    'role' => ['de' => 'Fact (Meta)', 'en' => 'Fact (meta)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'label',
                    'label' => ['de' => 'Label', 'en' => 'Label'],
                    'description' => [
                        'de' => 'Labels — Content-Taxonomie und Filter-Dimension.',
                        'en' => 'Labels — content taxonomy and filter dimension.',
                    ],
                    'grain' => ['de' => 'Ein Label an Content', 'en' => 'One label on content'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'blogpost',
                    'label' => ['de' => 'Blogpost', 'en' => 'Blogpost'],
                    'description' => [
                        'de' => 'Blog-Post — content_type-Variante neben Page.',
                        'en' => 'Blog post — content_type variant alongside page.',
                    ],
                    'grain' => ['de' => 'Ein Blogpost', 'en' => 'One blogpost'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'version',
                    'label' => ['de' => 'Version', 'en' => 'Version'],
                    'description' => [
                        'de' => 'Seiten-Version — Update-Events und Autoren-Historie.',
                        'en' => 'Page version — update events and author history.',
                    ],
                    'grain' => ['de' => 'Eine Version', 'en' => 'One version'],
                    'role' => ['de' => 'Event-Fact', 'en' => 'Event fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Page', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Content-Join (Cloud REST)', 'en' => 'Content join (Cloud REST)']],
                ['entity' => 'Page', 'name' => 'title', 'role' => 'dimension', 'why' => ['de' => 'Seiten-Titel / Label', 'en' => 'Page title / label']],
                ['entity' => 'Page', 'name' => 'space_key', 'role' => 'dimension', 'why' => ['de' => 'Space-Join', 'en' => 'Space join']],
                ['entity' => 'Page', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'current / trashed / draft', 'en' => 'current / trashed / draft']],
                ['entity' => 'Page', 'name' => 'author', 'role' => 'dimension', 'why' => ['de' => 'Ersteller accountId', 'en' => 'Creator accountId']],
                ['entity' => 'Page', 'name' => 'created', 'role' => 'measure', 'why' => ['de' => 'Erstellzeit', 'en' => 'Created time']],
                ['entity' => 'Page', 'name' => 'updated', 'role' => 'measure', 'why' => ['de' => 'Letzte Änderung', 'en' => 'Last update']],
                ['entity' => 'Page', 'name' => 'version', 'role' => 'measure', 'why' => ['de' => 'Versionsnummer', 'en' => 'Version number']],
                ['entity' => 'Page', 'name' => 'body_storage', 'role' => 'pii', 'why' => ['de' => 'HTML-Body / PII-Risiko (optional)', 'en' => 'HTML body / PII risk (optional)']],
                ['entity' => 'Space', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Space-Join', 'en' => 'Space join']],
                ['entity' => 'Space', 'name' => 'key', 'role' => 'dimension', 'why' => ['de' => 'space_key / Label', 'en' => 'space_key / label']],
                ['entity' => 'Space', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Space-Name', 'en' => 'Space name']],
                ['entity' => 'Space', 'name' => 'type', 'role' => 'dimension', 'why' => ['de' => 'global / personal', 'en' => 'global / personal']],
                ['entity' => 'User', 'name' => 'accountId', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'User', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'User', 'name' => 'displayName', 'role' => 'pii', 'why' => ['de' => 'Anzeigename / Quasi-PII', 'en' => 'Display name / quasi-PII']],
                ['entity' => 'Comment', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Kommentar-Join', 'en' => 'Comment join']],
                ['entity' => 'Comment', 'name' => 'created', 'role' => 'measure', 'why' => ['de' => 'Kommentar-Zeit', 'en' => 'Comment time']],
                ['entity' => 'Comment', 'name' => 'body', 'role' => 'pii', 'why' => ['de' => 'Freitext / PII', 'en' => 'Free text / PII']],
                ['entity' => 'Attachment Meta', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Attachment-Join', 'en' => 'Attachment join']],
                ['entity' => 'Attachment Meta', 'name' => 'mediaType', 'role' => 'dimension', 'why' => ['de' => 'MIME-Typ', 'en' => 'MIME type']],
                ['entity' => 'Attachment Meta', 'name' => 'fileSize', 'role' => 'measure', 'why' => ['de' => 'Dateigröße', 'en' => 'File size']],
                ['entity' => 'Label', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Label-Name', 'en' => 'Label name']],
                ['entity' => 'Label', 'name' => 'content_id', 'role' => 'key', 'why' => ['de' => 'Content-Join', 'en' => 'Content join']],
                ['entity' => 'Blogpost', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Blog-Join', 'en' => 'Blog join']],
                ['entity' => 'Blogpost', 'name' => 'created', 'role' => 'measure', 'why' => ['de' => 'Publish-Zeit', 'en' => 'Publish time']],
                ['entity' => 'Version', 'name' => 'number', 'role' => 'key', 'why' => ['de' => 'Versions-Grain', 'en' => 'Version grain']],
                ['entity' => 'Version', 'name' => 'when', 'role' => 'measure', 'why' => ['de' => 'Versions-Zeitpunkt', 'en' => 'Version timestamp']],
                ['entity' => 'Version', 'name' => 'by', 'role' => 'dimension', 'why' => ['de' => 'Editor accountId', 'en' => 'Editor accountId']],
            ],
            'skipTables' => [
                [
                    'name' => 'Attachment binaries',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Binärinhalte — nur Metadaten für Analytics laden.',
                        'en' => 'Binary content — load metadata only for analytics.',
                    ],
                ],
                [
                    'name' => 'Full page body HTML dumps (optional load)',
                    'category' => 'content',
                    'reason' => [
                        'de' => 'Body-HTML — Volumen und PII; nur bei explizitem Use Case laden.',
                        'en' => 'Body HTML — volume and PII; load only for an explicit use case.',
                    ],
                ],
                [
                    'name' => 'Audit log dumps',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Admin-Audit — selten KPI-relevant.',
                        'en' => 'Admin audit — rarely KPI-relevant.',
                    ],
                ],
                [
                    'name' => 'Trash permanently deleted bulk',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Papierkorb-/Purge-Bulk — Retention-Thema, kein Mart-Kern.',
                        'en' => 'Trash/purge bulk — retention topic, not mart core.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Attachment binary blobs', 'reason' => ['de' => 'Kein Analytics-Nutzen', 'en' => 'No analytics value']],
                ['name' => 'Full storage/body HTML by default', 'reason' => ['de' => 'PII und Storage — optional freigeben', 'en' => 'PII and storage — approve optionally']],
                ['name' => 'Audit / admin event archives', 'reason' => ['de' => 'Technisches Rauschen', 'en' => 'Technical noise']],
                ['name' => 'Permanently deleted trash bulk', 'reason' => ['de' => 'Retention, nicht KPI', 'en' => 'Retention, not KPI']],
            ],
            'dimensions' => [
                [
                    'id' => 'space',
                    'label' => ['de' => 'Space', 'en' => 'Space'],
                    'grain' => ['de' => 'space_key', 'en' => 'space_key'],
                    'notes' => [
                        'de' => 'Personal Spaces oft aus Active-Spaces-KPIs ausschließen.',
                        'en' => 'Often exclude personal spaces from active-spaces KPIs.',
                    ],
                ],
                [
                    'id' => 'content_type',
                    'label' => ['de' => 'Content Type', 'en' => 'Content type'],
                    'grain' => ['de' => 'page / blogpost / comment', 'en' => 'page / blogpost / comment'],
                    'notes' => [
                        'de' => 'REST type-Feld für Throughput-Splits nutzen.',
                        'en' => 'Use REST type field for throughput splits.',
                    ],
                ],
                [
                    'id' => 'label',
                    'label' => ['de' => 'Label', 'en' => 'Label'],
                    'grain' => ['de' => 'label.name', 'en' => 'label.name'],
                    'notes' => [
                        'de' => 'Taxonomie vor dem Mart normalisieren (Synonyme).',
                        'en' => 'Normalize taxonomy before the mart (synonyms).',
                    ],
                ],
                [
                    'id' => 'author_dept',
                    'label' => ['de' => 'Author Department', 'en' => 'Author department'],
                    'grain' => ['de' => 'Abteilung (extern)', 'en' => 'Department (external)'],
                    'notes' => [
                        'de' => 'Abteilung ist oft extern (HR/IdP) — nicht native Confluence-Dimension.',
                        'en' => 'Department is often external (HR/IdP) — not a native Confluence dimension.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['email', 'displayName'],
                    'treatment' => [
                        'de' => 'User-E-Mail — taggen und RAW einschränken.',
                        'en' => 'User email — tag and restrict RAW.',
                    ],
                ],
                [
                    'entity' => 'Page',
                    'fields' => ['body_storage', 'title'],
                    'treatment' => [
                        'de' => 'Page Body kann PII enthalten — Default oft ohne Body laden.',
                        'en' => 'Page body may contain PII — default often load without body.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'page id, space_key, accountId, email, attachment id.',
                        'en' => 'page id, space_key, accountId, email, attachment id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Page, Space, User + Content-Exports und Search-Index-Kopien.',
                        'en' => 'Page, space, user + content exports and search index copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'pages-created',
                    'example' => true,
                    'label' => ['de' => 'Pages Created', 'en' => 'Pages created'],
                    'question' => [
                        'de' => 'Wie viele Seiten wurden in der Periode erstellt?',
                        'en' => 'How many pages were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM page WHERE created IN period AND status = current',
                    'grain' => ['de' => 'Page', 'en' => 'Page'],
                    'dimensions' => ['space', 'content_type', 'author_dept'],
                    'fieldsUsed' => ['Page.created', 'Page.space_key', 'Page.status'],
                    'sourceHints' => [
                        'de' => 'Confluence Cloud REST content?type=page&created…',
                        'en' => 'Confluence Cloud REST content?type=page&created…',
                    ],
                    'adapt' => [
                        'de' => 'Drafts und Templates aus Intake filtern.',
                        'en' => 'Filter drafts and templates from intake.',
                    ],
                ],
                [
                    'id' => 'pages-updated',
                    'example' => true,
                    'label' => ['de' => 'Pages Updated', 'en' => 'Pages updated'],
                    'question' => [
                        'de' => 'Wie viele Seiten wurden in der Periode aktualisiert?',
                        'en' => 'How many pages were updated in the period?',
                    ],
                    'formula' => 'COUNT(DISTINCT page_id) FROM version WHERE when IN period',
                    'grain' => ['de' => 'Page (distinct)', 'en' => 'Page (distinct)'],
                    'dimensions' => ['space', 'content_type', 'label'],
                    'fieldsUsed' => ['Version.when', 'Version.number', 'Page.space_key'],
                    'sourceHints' => [
                        'de' => 'Version-Historie oder updated-Timestamp — Double-Count vermeiden.',
                        'en' => 'Version history or updated timestamp — avoid double counts.',
                    ],
                    'adapt' => [
                        'de' => 'Minor vs. Major Edits und Bot-User klären.',
                        'en' => 'Clarify minor vs major edits and bot users.',
                    ],
                ],
                [
                    'id' => 'active-spaces',
                    'example' => false,
                    'label' => ['de' => 'Active Spaces', 'en' => 'Active spaces'],
                    'question' => [
                        'de' => 'Wie viele Spaces hatten in der Periode Content-Aktivität?',
                        'en' => 'How many spaces had content activity in the period?',
                    ],
                    'formula' => 'COUNT(DISTINCT space_key) FROM page WHERE updated IN period',
                    'grain' => ['de' => 'Space', 'en' => 'Space'],
                    'dimensions' => ['space', 'content_type'],
                    'fieldsUsed' => ['Page.space_key', 'Page.updated'],
                    'sourceHints' => [
                        'de' => 'Aktivität = create/update/comment — Definition festnageln.',
                        'en' => 'Activity = create/update/comment — lock the definition.',
                    ],
                    'adapt' => [
                        'de' => 'Personal Spaces und Archive-Spaces ausschließen.',
                        'en' => 'Exclude personal and archived spaces.',
                    ],
                ],
                [
                    'id' => 'comments-created',
                    'example' => false,
                    'label' => ['de' => 'Comments Created', 'en' => 'Comments created'],
                    'question' => [
                        'de' => 'Wie viele Kommentare wurden erstellt?',
                        'en' => 'How many comments were created?',
                    ],
                    'formula' => 'COUNT(*) FROM comment WHERE created IN period',
                    'grain' => ['de' => 'Comment', 'en' => 'Comment'],
                    'dimensions' => ['space', 'content_type', 'author_dept'],
                    'fieldsUsed' => ['Comment.created', 'Comment.id'],
                    'sourceHints' => [
                        'de' => 'Footer- und Inline-Comments vereinheitlichen.',
                        'en' => 'Unify footer and inline comments.',
                    ],
                    'adapt' => [
                        'de' => 'Body nur laden wenn Engagement-Text nötig ist.',
                        'en' => 'Load body only when engagement text is required.',
                    ],
                ],
                [
                    'id' => 'attachment-count',
                    'example' => false,
                    'label' => ['de' => 'Attachment Count', 'en' => 'Attachment count'],
                    'question' => [
                        'de' => 'Wie viele Anhänge (Meta) wurden hinzugefügt?',
                        'en' => 'How many attachments (meta) were added?',
                    ],
                    'formula' => 'COUNT(*) FROM attachment_meta WHERE created IN period',
                    'grain' => ['de' => 'Attachment Meta', 'en' => 'Attachment meta'],
                    'dimensions' => ['space', 'content_type'],
                    'fieldsUsed' => ['Attachment Meta.id', 'Attachment Meta.mediaType'],
                    'sourceHints' => [
                        'de' => 'Nur Metadaten — Binaries skippen.',
                        'en' => 'Metadata only — skip binaries.',
                    ],
                    'adapt' => [
                        'de' => 'Große Binaries und externe Links separat zählen.',
                        'en' => 'Count large binaries and external links separately.',
                    ],
                ],
            ],
            'tools' => $collabTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'slack',
            'domain' => 'collab',
            'order' => 130,
            'label' => ['de' => 'Slack', 'en' => 'Slack'],
            'shortPurpose' => [
                'de' => 'Channels/Messages: Collaboration-Metriken — Conversations API; Message-Text meist nicht laden.',
                'en' => 'Channels/messages: collaboration metrics — Conversations API; usually do not load message text.',
            ],
            'entities' => [
                [
                    'id' => 'message',
                    'label' => ['de' => 'Message', 'en' => 'Message'],
                    'description' => [
                        'de' => 'Channel-Message — Fact für Sent/Replies; text ist heavy PII und Default-Skip.',
                        'en' => 'Channel message — fact for sent/replies; text is heavy PII and default skip.',
                    ],
                    'grain' => ['de' => 'Eine Message (channel_id + ts)', 'en' => 'One message (channel_id + ts)'],
                    'role' => ['de' => 'Fact (Meta; Text optional)', 'en' => 'Fact (meta; text optional)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'channel',
                    'label' => ['de' => 'Channel', 'en' => 'Channel'],
                    'description' => [
                        'de' => 'Public/Private Channel — Dimension und Active-Channels KPI.',
                        'en' => 'Public/private channel — dimension and active-channels KPI.',
                    ],
                    'grain' => ['de' => 'Ein Channel', 'en' => 'One channel'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'Workspace-User — Profile mit email/phone (PII); is_bot filtern.',
                        'en' => 'Workspace user — profile with email/phone (PII); filter is_bot.',
                    ],
                    'grain' => ['de' => 'Ein User', 'en' => 'One user'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'user_group',
                    'label' => ['de' => 'User Group', 'en' => 'User group'],
                    'description' => [
                        'de' => 'User Groups (@engineers) — optionale Segment-Dimension.',
                        'en' => 'User groups (@engineers) — optional segment dimension.',
                    ],
                    'grain' => ['de' => 'Eine User Group', 'en' => 'One user group'],
                    'role' => ['de' => 'Optionale Dimension', 'en' => 'Optional dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'file_meta',
                    'label' => ['de' => 'File Meta', 'en' => 'File meta'],
                    'description' => [
                        'de' => 'Datei-Metadaten — Files Shared; Binaries skippen.',
                        'en' => 'File metadata — files shared; skip binaries.',
                    ],
                    'grain' => ['de' => 'Eine Datei (Meta)', 'en' => 'One file (meta)'],
                    'role' => ['de' => 'Fact (Meta)', 'en' => 'Fact (meta)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'reaction',
                    'label' => ['de' => 'Reaction', 'en' => 'Reaction'],
                    'description' => [
                        'de' => 'Emoji-Reactions — Engagement ohne Message-Body.',
                        'en' => 'Emoji reactions — engagement without message body.',
                    ],
                    'grain' => ['de' => 'Reaction auf Message', 'en' => 'Reaction on message'],
                    'role' => ['de' => 'Engagement-Fact', 'en' => 'Engagement fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'canvas',
                    'label' => ['de' => 'Canvas', 'en' => 'Canvas'],
                    'description' => [
                        'de' => 'Slack Canvas — optionales Content-Objekt.',
                        'en' => 'Slack canvas — optional content object.',
                    ],
                    'grain' => ['de' => 'Ein Canvas', 'en' => 'One canvas'],
                    'role' => ['de' => 'Optionaler Fact', 'en' => 'Optional fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'huddle_summary',
                    'label' => ['de' => 'Huddle Summary', 'en' => 'Huddle summary'],
                    'description' => [
                        'de' => 'Huddle-Zusammenfassung — Meta für Voice-Collab; Transcripts prüfen.',
                        'en' => 'Huddle summary — meta for voice collab; review transcripts.',
                    ],
                    'grain' => ['de' => 'Ein Huddle', 'en' => 'One huddle'],
                    'role' => ['de' => 'Optionaler Fact', 'en' => 'Optional fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Message', 'name' => 'channel_id', 'role' => 'key', 'why' => ['de' => 'Channel-Join (Conversations API)', 'en' => 'Channel join (Conversations API)']],
                ['entity' => 'Message', 'name' => 'ts', 'role' => 'key', 'why' => ['de' => 'Message-Timestamp-Key', 'en' => 'Message timestamp key']],
                ['entity' => 'Message', 'name' => 'thread_ts', 'role' => 'dimension', 'why' => ['de' => 'Thread-Parent / Replies', 'en' => 'Thread parent / replies']],
                ['entity' => 'Message', 'name' => 'user_id', 'role' => 'dimension', 'why' => ['de' => 'Autor-Join', 'en' => 'Author join']],
                ['entity' => 'Message', 'name' => 'text', 'role' => 'pii', 'why' => ['de' => 'Message-Body / heavy PII — meist skip', 'en' => 'Message body / heavy PII — usually skip']],
                ['entity' => 'Message', 'name' => 'subtype', 'role' => 'dimension', 'why' => ['de' => 'bot_message / channel_join / …', 'en' => 'bot_message / channel_join / …']],
                ['entity' => 'Message', 'name' => 'reply_count', 'role' => 'measure', 'why' => ['de' => 'Thread-Replies', 'en' => 'Thread replies']],
                ['entity' => 'Channel', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Channel-Join', 'en' => 'Channel join']],
                ['entity' => 'Channel', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Channel-Name', 'en' => 'Channel name']],
                ['entity' => 'Channel', 'name' => 'is_private', 'role' => 'dimension', 'why' => ['de' => 'Public vs. Private', 'en' => 'Public vs private']],
                ['entity' => 'Channel', 'name' => 'is_archived', 'role' => 'dimension', 'why' => ['de' => 'Archiv-Filter', 'en' => 'Archive filter']],
                ['entity' => 'Channel', 'name' => 'team_id', 'role' => 'dimension', 'why' => ['de' => 'Workspace / Team', 'en' => 'Workspace / team']],
                ['entity' => 'User', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'User', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Profil-E-Mail / PII', 'en' => 'Profile email / PII']],
                ['entity' => 'User', 'name' => 'phone', 'role' => 'pii', 'why' => ['de' => 'Telefon / PII', 'en' => 'Phone / PII']],
                ['entity' => 'User', 'name' => 'real_name', 'role' => 'pii', 'why' => ['de' => 'Anzeigename / PII', 'en' => 'Display name / PII']],
                ['entity' => 'User', 'name' => 'is_bot', 'role' => 'dimension', 'why' => ['de' => 'Bot-Filter', 'en' => 'Bot filter']],
                ['entity' => 'User', 'name' => 'team_id', 'role' => 'dimension', 'why' => ['de' => 'Workspace', 'en' => 'Workspace']],
                ['entity' => 'User Group', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Group-Join', 'en' => 'Group join']],
                ['entity' => 'User Group', 'name' => 'handle', 'role' => 'dimension', 'why' => ['de' => 'Group-Handle', 'en' => 'Group handle']],
                ['entity' => 'File Meta', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'File-Join', 'en' => 'File join']],
                ['entity' => 'File Meta', 'name' => 'filetype', 'role' => 'dimension', 'why' => ['de' => 'Dateityp', 'en' => 'File type']],
                ['entity' => 'File Meta', 'name' => 'user', 'role' => 'dimension', 'why' => ['de' => 'Uploader', 'en' => 'Uploader']],
                ['entity' => 'File Meta', 'name' => 'created', 'role' => 'measure', 'why' => ['de' => 'Upload-Zeit', 'en' => 'Upload time']],
                ['entity' => 'Reaction', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Emoji-Name', 'en' => 'Emoji name']],
                ['entity' => 'Reaction', 'name' => 'count', 'role' => 'measure', 'why' => ['de' => 'Reaction-Count', 'en' => 'Reaction count']],
                ['entity' => 'Canvas', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Canvas-Join', 'en' => 'Canvas join']],
                ['entity' => 'Huddle Summary', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Huddle-Join', 'en' => 'Huddle join']],
                ['entity' => 'Huddle Summary', 'name' => 'duration', 'role' => 'measure', 'why' => ['de' => 'Dauer', 'en' => 'Duration']],
            ],
            'skipTables' => [
                [
                    'name' => 'Message text bulk (default skip to warehouse unless approved)',
                    'category' => 'content',
                    'reason' => [
                        'de' => 'Message-Bodies werden für Analytics-KPIs üblicherweise nicht geladen — nur Meta (ts, channel, user, subtype) sofern freigegeben.',
                        'en' => 'Message bodies are usually not loaded for analytics KPIs — meta only (ts, channel, user, subtype) if approved.',
                    ],
                ],
                [
                    'name' => 'File binaries',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Datei-Binaries — nicht ins Warehouse.',
                        'en' => 'File binaries — not into the warehouse.',
                    ],
                ],
                [
                    'name' => 'DMs without legal basis',
                    'category' => 'privacy',
                    'reason' => [
                        'de' => 'Direktnachrichten — nur mit Rechtsgrundlage und Approval.',
                        'en' => 'Direct messages — only with legal basis and approval.',
                    ],
                ],
                [
                    'name' => 'Bot noise floods',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Bot-/Integrations-Floods — subtype und is_bot filtern.',
                        'en' => 'Bot/integration floods — filter subtype and is_bot.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Message body text (default for KPI analytics)', 'reason' => ['de' => 'Heavy PII — Bodies meist nicht laden', 'en' => 'Heavy PII — usually do not load bodies']],
                ['name' => 'DM / MPIMs without legal basis', 'reason' => ['de' => 'Hohes Privacy-Risiko', 'en' => 'High privacy risk']],
                ['name' => 'File binary content', 'reason' => ['de' => 'Blob-Storage, kein Mart', 'en' => 'Blob storage, not mart']],
                ['name' => 'Bot and join/leave noise events', 'reason' => ['de' => 'Verzerrt Activity-KPIs', 'en' => 'Skews activity KPIs']],
            ],
            'dimensions' => [
                [
                    'id' => 'channel_type',
                    'label' => ['de' => 'Channel Type', 'en' => 'Channel type'],
                    'grain' => ['de' => 'public / private', 'en' => 'public / private'],
                    'notes' => [
                        'de' => 'is_private und is_im/is_mpim klar trennen.',
                        'en' => 'Clearly separate is_private and is_im/is_mpim.',
                    ],
                ],
                [
                    'id' => 'team',
                    'label' => ['de' => 'Team / Workspace', 'en' => 'Team / workspace'],
                    'grain' => ['de' => 'team_id', 'en' => 'team_id'],
                    'notes' => [
                        'de' => 'Enterprise Grid: Workspace vs. Org-Ebene klären.',
                        'en' => 'Enterprise Grid: clarify workspace vs org level.',
                    ],
                ],
                [
                    'id' => 'is_bot',
                    'label' => ['de' => 'Is Bot', 'en' => 'Is bot'],
                    'grain' => ['de' => 'User.is_bot / subtype', 'en' => 'User.is_bot / subtype'],
                    'notes' => [
                        'de' => 'Humane Activity-KPIs ohne Bots berichten.',
                        'en' => 'Report human activity KPIs without bots.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Message',
                    'fields' => ['text'],
                    'treatment' => [
                        'de' => 'Heavy PII — Message-Text und DMs default nicht in RAW; nur aggregierte Meta-KPIs.',
                        'en' => 'Heavy PII — default exclude message text and DMs from RAW; aggregated meta KPIs only.',
                    ],
                ],
                [
                    'entity' => 'User',
                    'fields' => ['email', 'phone', 'real_name'],
                    'treatment' => [
                        'de' => 'Profil-E-Mail/Telefon — taggen, einschränken.',
                        'en' => 'Profile email/phone — tag, restrict.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'user_id, email, channel_id, team_id, message ts.',
                        'en' => 'user_id, email, channel_id, team_id, message ts.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'User, Channel, Message-Meta + Export- und Discovery-Kopien.',
                        'en' => 'User, channel, message meta + export and discovery copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'messages-sent',
                    'example' => true,
                    'label' => ['de' => 'Messages Sent', 'en' => 'Messages sent'],
                    'question' => [
                        'de' => 'Wie viele Messages wurden gesendet (ohne Body-Load)?',
                        'en' => 'How many messages were sent (without loading bodies)?',
                    ],
                    'formula' => 'COUNT(*) FROM message_meta WHERE ts IN period AND subtype IS NULL AND is_bot = false',
                    'grain' => ['de' => 'Message Meta', 'en' => 'Message meta'],
                    'dimensions' => ['channel_type', 'team', 'is_bot'],
                    'fieldsUsed' => ['Message.channel_id', 'Message.ts', 'Message.subtype', 'User.is_bot'],
                    'sourceHints' => [
                        'de' => 'Viele Orgs laden Message-Text nicht — Meta aus Conversations History / Analytics APIs.',
                        'en' => 'Many orgs do not land message text — meta from Conversations History / Analytics APIs.',
                    ],
                    'adapt' => [
                        'de' => 'Thread-Parents vs. alle Replies und DM-Ausschluss festlegen.',
                        'en' => 'Lock thread parents vs all replies and DM exclusion.',
                    ],
                ],
                [
                    'id' => 'active-channels',
                    'example' => true,
                    'label' => ['de' => 'Active Channels', 'en' => 'Active channels'],
                    'question' => [
                        'de' => 'Wie viele Channels hatten in der Periode Messages?',
                        'en' => 'How many channels had messages in the period?',
                    ],
                    'formula' => 'COUNT(DISTINCT channel_id) FROM message_meta WHERE ts IN period',
                    'grain' => ['de' => 'Channel', 'en' => 'Channel'],
                    'dimensions' => ['channel_type', 'team'],
                    'fieldsUsed' => ['Message.channel_id', 'Message.ts', 'Channel.is_private'],
                    'sourceHints' => [
                        'de' => 'Archivierte Channels und Shared Channels filtern.',
                        'en' => 'Filter archived and shared channels.',
                    ],
                    'adapt' => [
                        'de' => 'Mindest-Message-Schwelle für „aktiv“ definieren.',
                        'en' => 'Define a minimum message threshold for “active”.',
                    ],
                ],
                [
                    'id' => 'files-shared',
                    'example' => false,
                    'label' => ['de' => 'Files Shared', 'en' => 'Files shared'],
                    'question' => [
                        'de' => 'Wie viele Dateien wurden geteilt (Meta)?',
                        'en' => 'How many files were shared (meta)?',
                    ],
                    'formula' => 'COUNT(*) FROM file_meta WHERE created IN period',
                    'grain' => ['de' => 'File Meta', 'en' => 'File meta'],
                    'dimensions' => ['channel_type', 'team', 'is_bot'],
                    'fieldsUsed' => ['File Meta.id', 'File Meta.created', 'File Meta.filetype'],
                    'sourceHints' => [
                        'de' => 'files.list / events — Binaries nicht speichern.',
                        'en' => 'files.list / events — do not store binaries.',
                    ],
                    'adapt' => [
                        'de' => 'Externe Shares und Snippets separat zählen.',
                        'en' => 'Count external shares and snippets separately.',
                    ],
                ],
                [
                    'id' => 'reactions-count',
                    'example' => false,
                    'label' => ['de' => 'Reactions Count', 'en' => 'Reactions count'],
                    'question' => [
                        'de' => 'Wie viele Reactions wurden vergeben?',
                        'en' => 'How many reactions were given?',
                    ],
                    'formula' => 'SUM(count) FROM reaction WHERE message_ts IN period',
                    'grain' => ['de' => 'Reaction', 'en' => 'Reaction'],
                    'dimensions' => ['channel_type', 'team'],
                    'fieldsUsed' => ['Reaction.name', 'Reaction.count'],
                    'sourceHints' => [
                        'de' => 'Engagement ohne Message-Text möglich.',
                        'en' => 'Engagement possible without message text.',
                    ],
                    'adapt' => [
                        'de' => 'Unique reactors vs. total reaction events klären.',
                        'en' => 'Clarify unique reactors vs total reaction events.',
                    ],
                ],
                [
                    'id' => 'thread-replies',
                    'example' => false,
                    'label' => ['de' => 'Thread Replies', 'en' => 'Thread replies'],
                    'question' => [
                        'de' => 'Wie viele Thread-Replies gab es?',
                        'en' => 'How many thread replies were there?',
                    ],
                    'formula' => 'COUNT(*) FROM message_meta WHERE thread_ts IS NOT NULL AND ts != thread_ts AND ts IN period',
                    'grain' => ['de' => 'Reply Message Meta', 'en' => 'Reply message meta'],
                    'dimensions' => ['channel_type', 'team', 'is_bot'],
                    'fieldsUsed' => ['Message.thread_ts', 'Message.ts', 'Message.reply_count'],
                    'sourceHints' => [
                        'de' => 'Achtung: Viele Orgs sollten Message-Text nicht landen — nur Meta für Replies.',
                        'en' => 'Caution: many orgs should not land message text — meta only for replies.',
                    ],
                    'adapt' => [
                        'de' => 'reply_count am Parent vs. Reply-Rows wählen.',
                        'en' => 'Choose reply_count on parent vs reply rows.',
                    ],
                ],
            ],
            'tools' => $collabTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'microsoft-teams',
            'domain' => 'collab',
            'order' => 140,
            'label' => ['de' => 'Microsoft Teams', 'en' => 'Microsoft Teams'],
            'shortPurpose' => [
                'de' => 'Teams/Meetings: Collaboration & Calls — Graph API; Chat-Bodies und Transcripts meist skip.',
                'en' => 'Teams/meetings: collaboration & calls — Graph API; usually skip chat bodies and transcripts.',
            ],
            'entities' => [
                [
                    'id' => 'team',
                    'label' => ['de' => 'Team', 'en' => 'Team'],
                    'description' => [
                        'de' => 'Microsoft 365 Group / Team — Wurzel-Dimension für Active Teams.',
                        'en' => 'Microsoft 365 group / team — root dimension for active teams.',
                    ],
                    'grain' => ['de' => 'Ein Team', 'en' => 'One team'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'channel',
                    'label' => ['de' => 'Channel', 'en' => 'Channel'],
                    'description' => [
                        'de' => 'Team-Channel — standard / private; Message- und Meeting-Kontext.',
                        'en' => 'Team channel — standard / private; message and meeting context.',
                    ],
                    'grain' => ['de' => 'Ein Channel', 'en' => 'One channel'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'chat_message',
                    'label' => ['de' => 'Chat Message', 'en' => 'Chat message'],
                    'description' => [
                        'de' => 'Channel-/Chat-Message — Meta für Sent-KPIs; Body bulk oft skip.',
                        'en' => 'Channel/chat message — meta for sent KPIs; body bulk often skip.',
                    ],
                    'grain' => ['de' => 'Eine Message', 'en' => 'One message'],
                    'role' => ['de' => 'Fact (Meta; Body optional)', 'en' => 'Fact (meta; body optional)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'call',
                    'label' => ['de' => 'Call', 'en' => 'Call'],
                    'description' => [
                        'de' => 'Calls Record — Calls Count und Dauer; Recordings skippen.',
                        'en' => 'Calls record — calls count and duration; skip recordings.',
                    ],
                    'grain' => ['de' => 'Ein Call', 'en' => 'One call'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'online_meeting',
                    'label' => ['de' => 'Online Meeting', 'en' => 'Online meeting'],
                    'description' => [
                        'de' => 'Online Meeting — Meetings Held und Meeting Minutes.',
                        'en' => 'Online meeting — meetings held and meeting minutes.',
                    ],
                    'grain' => ['de' => 'Ein Meeting', 'en' => 'One meeting'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'Entra/Graph User — UPN/mail sind PII; from.user Join.',
                        'en' => 'Entra/Graph user — UPN/mail are PII; from.user join.',
                    ],
                    'grain' => ['de' => 'Ein User (id)', 'en' => 'One user (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'membership',
                    'label' => ['de' => 'Membership', 'en' => 'Membership'],
                    'description' => [
                        'de' => 'Team-/Channel-Membership — Reichweite und Active-User-Kontext.',
                        'en' => 'Team/channel membership — reach and active-user context.',
                    ],
                    'grain' => ['de' => 'User in Team/Channel', 'en' => 'User in team/channel'],
                    'role' => ['de' => 'Bridge', 'en' => 'Bridge'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'tab',
                    'label' => ['de' => 'Tab', 'en' => 'Tab'],
                    'description' => [
                        'de' => 'Channel-Tab — optionales Adoption-Signal (Apps/Workload).',
                        'en' => 'Channel tab — optional adoption signal (apps/workload).',
                    ],
                    'grain' => ['de' => 'Ein Tab', 'en' => 'One tab'],
                    'role' => ['de' => 'Optionale Dimension', 'en' => 'Optional dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Team', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Graph team id', 'en' => 'Graph team id']],
                ['entity' => 'Team', 'name' => 'displayName', 'role' => 'dimension', 'why' => ['de' => 'Team-Label', 'en' => 'Team label']],
                ['entity' => 'Team', 'name' => 'visibility', 'role' => 'dimension', 'why' => ['de' => 'public / private', 'en' => 'public / private']],
                ['entity' => 'Channel', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Graph channel id', 'en' => 'Graph channel id']],
                ['entity' => 'Channel', 'name' => 'displayName', 'role' => 'dimension', 'why' => ['de' => 'Channel-Name', 'en' => 'Channel name']],
                ['entity' => 'Channel', 'name' => 'membershipType', 'role' => 'dimension', 'why' => ['de' => 'standard / private / shared', 'en' => 'standard / private / shared']],
                ['entity' => 'Chat Message', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Message id', 'en' => 'Message id']],
                ['entity' => 'Chat Message', 'name' => 'createdDateTime', 'role' => 'measure', 'why' => ['de' => 'Erstellzeit / Perioden-Grain', 'en' => 'Created time / period grain']],
                ['entity' => 'Chat Message', 'name' => 'from.user', 'role' => 'dimension', 'why' => ['de' => 'Autor (Graph from.user)', 'en' => 'Author (Graph from.user)']],
                ['entity' => 'Chat Message', 'name' => 'importance', 'role' => 'dimension', 'why' => ['de' => 'normal / high / urgent', 'en' => 'normal / high / urgent']],
                ['entity' => 'Chat Message', 'name' => 'body.content', 'role' => 'pii', 'why' => ['de' => 'Chat-Inhalt / PII — bulk oft skip', 'en' => 'Chat content / PII — bulk often skip']],
                ['entity' => 'Chat Message', 'name' => 'channelIdentity', 'role' => 'dimension', 'why' => ['de' => 'Channel-Kontext', 'en' => 'Channel context']],
                ['entity' => 'Call', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Call-Join', 'en' => 'Call join']],
                ['entity' => 'Call', 'name' => 'startDateTime', 'role' => 'measure', 'why' => ['de' => 'Call-Start', 'en' => 'Call start']],
                ['entity' => 'Call', 'name' => 'endDateTime', 'role' => 'measure', 'why' => ['de' => 'Call-Ende', 'en' => 'Call end']],
                ['entity' => 'Call', 'name' => 'modalities', 'role' => 'dimension', 'why' => ['de' => 'audio / video / …', 'en' => 'audio / video / …']],
                ['entity' => 'Online Meeting', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Meeting-Join', 'en' => 'Meeting join']],
                ['entity' => 'Online Meeting', 'name' => 'startDateTime', 'role' => 'measure', 'why' => ['de' => 'Meeting-Start', 'en' => 'Meeting start']],
                ['entity' => 'Online Meeting', 'name' => 'endDateTime', 'role' => 'measure', 'why' => ['de' => 'Meeting-Ende / Minuten', 'en' => 'Meeting end / minutes']],
                ['entity' => 'Online Meeting', 'name' => 'joinWebUrl', 'role' => 'dimension', 'why' => ['de' => 'Join-URL (sensibel)', 'en' => 'Join URL (sensitive)']],
                ['entity' => 'User', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Graph user id', 'en' => 'Graph user id']],
                ['entity' => 'User', 'name' => 'userPrincipalName', 'role' => 'pii', 'why' => ['de' => 'UPN / PII', 'en' => 'UPN / PII']],
                ['entity' => 'User', 'name' => 'mail', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'User', 'name' => 'displayName', 'role' => 'pii', 'why' => ['de' => 'Anzeigename / Quasi-PII', 'en' => 'Display name / quasi-PII']],
                ['entity' => 'Membership', 'name' => 'userId', 'role' => 'key', 'why' => ['de' => 'Member-Join', 'en' => 'Member join']],
                ['entity' => 'Membership', 'name' => 'roles', 'role' => 'dimension', 'why' => ['de' => 'owner / member / guest', 'en' => 'owner / member / guest']],
                ['entity' => 'Tab', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Tab-Join', 'en' => 'Tab join']],
                ['entity' => 'Tab', 'name' => 'teamsAppId', 'role' => 'dimension', 'why' => ['de' => 'Workload / App', 'en' => 'Workload / app']],
            ],
            'skipTables' => [
                [
                    'name' => 'Chat message body bulk',
                    'category' => 'content',
                    'reason' => [
                        'de' => 'Chat-Bodies — heavy PII; für die meisten KPIs Meta reicht.',
                        'en' => 'Chat bodies — heavy PII; meta is enough for most KPIs.',
                    ],
                ],
                [
                    'name' => 'Call recordings',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Aufzeichnungen — Storage und Compliance, nicht Mart-Kern.',
                        'en' => 'Recordings — storage and compliance, not mart core.',
                    ],
                ],
                [
                    'name' => 'Meeting transcripts (unless approved)',
                    'category' => 'content',
                    'reason' => [
                        'de' => 'Transcripts — PII/sensitive; nur mit Approval laden.',
                        'en' => 'Transcripts — PII/sensitive; load only with approval.',
                    ],
                ],
                [
                    'name' => 'Graph change notification dumps',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Change-Notification-Payloads — technisches Rauschen.',
                        'en' => 'Change notification payloads — technical noise.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Chat / channel message body bulk', 'reason' => ['de' => 'PII — Meta für Sent-KPIs', 'en' => 'PII — meta for sent KPIs']],
                ['name' => 'Call / meeting recordings', 'reason' => ['de' => 'Blob + Compliance', 'en' => 'Blob + compliance']],
                ['name' => 'Meeting transcripts without approval', 'reason' => ['de' => 'Sensitive content', 'en' => 'Sensitive content']],
                ['name' => 'Graph webhook / change notification archives', 'reason' => ['de' => 'Nicht analytisch', 'en' => 'Not analytical']],
            ],
            'dimensions' => [
                [
                    'id' => 'team',
                    'label' => ['de' => 'Team', 'en' => 'Team'],
                    'grain' => ['de' => 'team id', 'en' => 'team id'],
                    'notes' => [
                        'de' => 'Archivierte Teams und Guests in Membership beachten.',
                        'en' => 'Watch archived teams and guests in membership.',
                    ],
                ],
                [
                    'id' => 'channel',
                    'label' => ['de' => 'Channel', 'en' => 'Channel'],
                    'grain' => ['de' => 'channel id', 'en' => 'channel id'],
                    'notes' => [
                        'de' => 'membershipType für Private/Shared-Splits nutzen.',
                        'en' => 'Use membershipType for private/shared splits.',
                    ],
                ],
                [
                    'id' => 'meeting_type',
                    'label' => ['de' => 'Meeting Type', 'en' => 'Meeting type'],
                    'grain' => ['de' => 'scheduled / ad-hoc / channel', 'en' => 'scheduled / ad-hoc / channel'],
                    'notes' => [
                        'de' => 'Aus Graph onlineMeeting / calendar Event ableiten.',
                        'en' => 'Derive from Graph onlineMeeting / calendar event.',
                    ],
                ],
                [
                    'id' => 'workload',
                    'label' => ['de' => 'Workload', 'en' => 'Workload'],
                    'grain' => ['de' => 'chat / meetings / calling / apps', 'en' => 'chat / meetings / calling / apps'],
                    'notes' => [
                        'de' => 'Usage reports und Tab/App-Signals kombinieren.',
                        'en' => 'Combine usage reports and tab/app signals.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Chat Message',
                    'fields' => ['body.content'],
                    'treatment' => [
                        'de' => 'Chat-Inhalt — default nicht in Warehouse; aggregierte Meta-KPIs.',
                        'en' => 'Chat content — default keep out of warehouse; aggregated meta KPIs.',
                    ],
                ],
                [
                    'entity' => 'User',
                    'fields' => ['userPrincipalName', 'mail', 'displayName'],
                    'treatment' => [
                        'de' => 'UPN/Mail — taggen, RAW einschränken; id als Join.',
                        'en' => 'UPN/mail — tag, restrict RAW; id as join.',
                    ],
                ],
                [
                    'entity' => 'Online Meeting',
                    'fields' => ['transcript', 'joinWebUrl'],
                    'treatment' => [
                        'de' => 'Transcripts und Join-URLs — nur mit Approval.',
                        'en' => 'Transcripts and join URLs — only with approval.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'user id, UPN, mail, team id, channel id, message id.',
                        'en' => 'user id, UPN, mail, team id, channel id, message id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'User, Team, Meeting + Graph/Export-Kopien und Compliance-Archives.',
                        'en' => 'User, team, meeting + Graph/export copies and compliance archives.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'messages-sent',
                    'example' => true,
                    'label' => ['de' => 'Messages Sent', 'en' => 'Messages sent'],
                    'question' => [
                        'de' => 'Wie viele Chat-/Channel-Messages wurden gesendet?',
                        'en' => 'How many chat/channel messages were sent?',
                    ],
                    'formula' => 'COUNT(*) FROM chat_message_meta WHERE createdDateTime IN period',
                    'grain' => ['de' => 'Chat Message Meta', 'en' => 'Chat message meta'],
                    'dimensions' => ['team', 'channel', 'workload'],
                    'fieldsUsed' => ['Chat Message.id', 'Chat Message.createdDateTime', 'Chat Message.from.user'],
                    'sourceHints' => [
                        'de' => 'Graph channel messages / chats — Body nicht default laden.',
                        'en' => 'Graph channel messages / chats — do not load body by default.',
                    ],
                    'adapt' => [
                        'de' => 'System-/App-Messages und importance-Filter klären.',
                        'en' => 'Clarify system/app messages and importance filters.',
                    ],
                ],
                [
                    'id' => 'active-teams',
                    'example' => true,
                    'label' => ['de' => 'Active Teams', 'en' => 'Active teams'],
                    'question' => [
                        'de' => 'Wie viele Teams hatten in der Periode Aktivität?',
                        'en' => 'How many teams had activity in the period?',
                    ],
                    'formula' => 'COUNT(DISTINCT team_id) FROM activity WHERE activity_date IN period',
                    'grain' => ['de' => 'Team', 'en' => 'Team'],
                    'dimensions' => ['team', 'workload'],
                    'fieldsUsed' => ['Team.id', 'Chat Message.createdDateTime', 'Online Meeting.startDateTime'],
                    'sourceHints' => [
                        'de' => 'Microsoft 365 Usage / Graph Reports als Alternative zu Message-Bodies.',
                        'en' => 'Microsoft 365 usage / Graph reports as alternative to message bodies.',
                    ],
                    'adapt' => [
                        'de' => 'Aktivität = Messages und/oder Meetings — Definition fixieren.',
                        'en' => 'Activity = messages and/or meetings — lock the definition.',
                    ],
                ],
                [
                    'id' => 'meetings-held',
                    'example' => false,
                    'label' => ['de' => 'Meetings Held', 'en' => 'Meetings held'],
                    'question' => [
                        'de' => 'Wie viele Online Meetings fanden statt?',
                        'en' => 'How many online meetings were held?',
                    ],
                    'formula' => 'COUNT(*) FROM online_meeting WHERE startDateTime IN period',
                    'grain' => ['de' => 'Online Meeting', 'en' => 'Online meeting'],
                    'dimensions' => ['team', 'meeting_type', 'workload'],
                    'fieldsUsed' => ['Online Meeting.id', 'Online Meeting.startDateTime'],
                    'sourceHints' => [
                        'de' => 'Graph onlineMeetings / calendar — No-Shows separat.',
                        'en' => 'Graph onlineMeetings / calendar — treat no-shows separately.',
                    ],
                    'adapt' => [
                        'de' => 'Geplant vs. abgehalten und Channel-Meetings filtern.',
                        'en' => 'Filter scheduled vs held and channel meetings.',
                    ],
                ],
                [
                    'id' => 'meeting-minutes',
                    'example' => false,
                    'label' => ['de' => 'Meeting Minutes', 'en' => 'Meeting minutes'],
                    'question' => [
                        'de' => 'Wie viele Meeting-Minuten wurden abgehalten?',
                        'en' => 'How many meeting minutes were held?',
                    ],
                    'formula' => 'SUM(DATEDIFF(minute, startDateTime, endDateTime)) FROM online_meeting WHERE startDateTime IN period',
                    'grain' => ['de' => 'Online Meeting', 'en' => 'Online meeting'],
                    'dimensions' => ['team', 'meeting_type', 'workload'],
                    'fieldsUsed' => ['Online Meeting.startDateTime', 'Online Meeting.endDateTime'],
                    'sourceHints' => [
                        'de' => 'Teilnehmer-Minuten (attendance) vs. Meeting-Dauer unterscheiden.',
                        'en' => 'Distinguish attendee-minutes (attendance) vs meeting duration.',
                    ],
                    'adapt' => [
                        'de' => 'Caps für Extremdauer und parallele Meetings setzen.',
                        'en' => 'Set caps for extreme duration and overlapping meetings.',
                    ],
                ],
                [
                    'id' => 'calls-count',
                    'example' => false,
                    'label' => ['de' => 'Calls Count', 'en' => 'Calls count'],
                    'question' => [
                        'de' => 'Wie viele Calls gab es in der Periode?',
                        'en' => 'How many calls occurred in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM call WHERE startDateTime IN period',
                    'grain' => ['de' => 'Call', 'en' => 'Call'],
                    'dimensions' => ['team', 'workload', 'meeting_type'],
                    'fieldsUsed' => ['Call.id', 'Call.startDateTime', 'Call.modalities'],
                    'sourceHints' => [
                        'de' => 'Call Records API — Recordings und Transcripts skippen.',
                        'en' => 'Call Records API — skip recordings and transcripts.',
                    ],
                    'adapt' => [
                        'de' => '1:1 vs. Gruppen-Calls und PSTN separat berichten.',
                        'en' => 'Report 1:1 vs group calls and PSTN separately.',
                    ],
                ],
            ],
            'tools' => $collabTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
