<?php

/**
 * Wave 10 governance overlays — Workplace/Collab/Learning/Marketing source-native PII, DSDR, skip guidance.
 * Merged onto catalog products by id (exchange, monday, moodle, adobe-experience-cloud).
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'exchange' => [
        'pii' => [
            [
                'entity' => 'Mailbox',
                'fields' => ['id', 'primarySmtpAddress', 'displayName', 'aliasAddresses'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Mailbox — primarySmtpAddress/displayName nur mit Legal-Basis; mailbox id als Key behalten.', 'en' => 'Mailbox — primarySmtpAddress/displayName only with legal basis; keep mailbox id as key.'],
            ],
            [
                'entity' => 'Mail item headers',
                'fields' => ['from.emailAddress', 'toRecipients', 'ccRecipients', 'subject'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: hashed / counts only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: hashed / counts only; Mart: aggregates only'],
                'treatment' => ['de' => 'From/To/Subject sind PII — für Reach-Analytics hashen; Body nie laden.', 'en' => 'From/to/subject are PII — hash for reach analytics; never load body.'],
            ],
            [
                'entity' => 'Calendar event attendees',
                'fields' => ['organizer.emailAddress', 'attendees', 'location'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: counts only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: counts only; Mart: aggregates only'],
                'treatment' => ['de' => 'Attendees/Organizer als ids behandeln; Location/Subject können sensitiv sein.', 'en' => 'Treat attendees/organizer as ids; location/subject may be sensitive.'],
            ],
            [
                'entity' => 'Mail content / attachments',
                'fields' => ['body', 'bodyPreview', 'attachments'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Mail-Bodies/Attachments nie ins Warehouse — Meta only für alle Stages.', 'en' => 'Never land mail bodies/attachments in the warehouse — meta only for all stages.'],
            ],
            [
                'entity' => 'Mobile device identifiers',
                'fields' => ['deviceId', 'deviceOS', 'deviceUserAgent', 'deviceAccessState'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: aggregiert; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: aggregated; Mart: aggregates only'],
                'treatment' => ['de' => 'Device Meta kann Mailbox-Owner re-identifizieren — restriktiv behandeln.', 'en' => 'Device meta can re-identify the mailbox owner — treat restrictively.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'mailbox id, primarySmtpAddress (hashed), internetMessageId, calendar event id, deviceId.', 'en' => 'mailbox id, primarySmtpAddress (hashed), internetMessageId, calendar event id, deviceId.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Mailbox, Mail Item Meta, Calendar Event Meta, Distribution Group, Mobile Device.', 'en' => 'Mailbox, mail item meta, calendar event meta, distribution group, mobile device.'],
            ],
            [
                'focus' => ['de' => 'Graph / Compliance Center / Journaling copies', 'en' => 'Graph / Compliance Center / journaling copies'],
                'notes' => ['de' => 'Graph Mail API, Purview eDiscovery und Journaling verdoppeln Mail-PII in mehreren Stages.', 'en' => 'Graph mail API, Purview eDiscovery and journaling duplicate mail PII into several stages.'],
            ],
            [
                'focus' => ['de' => 'Archive / litigation hold copies', 'en' => 'Archive / litigation hold copies'],
                'notes' => ['de' => 'Litigation-Hold-Mailboxen getrennt von aktiven Analytics-Marts halten.', 'en' => 'Keep litigation-hold mailboxes separate from active analytics marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Mail body / bodyPreview / attachments',
                'category' => 'system',
                'reason' => ['de' => 'Mail-Content — nie landen; nur Header-Meta.', 'en' => 'Mail content — never land; header meta only.'],
            ],
            [
                'name' => 'Calendar event body / attachments',
                'category' => 'system',
                'reason' => ['de' => 'Event-Content — oft vertraulich; nur Zeit/Organizer-Meta.', 'en' => 'Event content — often confidential; time/organizer meta only.'],
            ],
            [
                'name' => 'Journaling / eDiscovery export payloads',
                'category' => 'system',
                'reason' => ['de' => 'Legal-Hold-Exports — getrennt von Analytics-RAW.', 'en' => 'Legal-hold exports — separate from analytics RAW.'],
            ],
            [
                'name' => 'Message trace IP / routing detail (bulk)',
                'category' => 'security',
                'reason' => ['de' => 'Routing-Metadaten in Bulk — Security-sensibel, kurze Retention.', 'en' => 'Bulk routing metadata — security-sensitive, short retention.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Mail body/attachments in any stage',
                'reason' => ['de' => 'Nie ins Warehouse — Meta reicht für alle KPIs.', 'en' => 'Never into the warehouse — meta is enough for all KPIs.'],
            ],
            [
                'name' => 'From/To/Subject cleartext in marts',
                'reason' => ['de' => 'mailbox id / hashed values reichen für Reach-KPIs.', 'en' => 'mailbox id / hashed values are enough for reach KPIs.'],
            ],
            [
                'name' => 'Calendar attendee/organizer cleartext (bulk)',
                'reason' => ['de' => 'Workforce-PII — Counts statt Klartext.', 'en' => 'Workforce PII — counts instead of cleartext.'],
            ],
            [
                'name' => 'eDiscovery / litigation hold exports',
                'reason' => ['de' => 'Legal — nicht in Default Analytics Stages.', 'en' => 'Legal — not in default analytics stages.'],
            ],
            [
                'name' => 'Mobile device identifiers in public marts',
                'reason' => ['de' => 'Re-Identifikationsrisiko — restriktiv halten.', 'en' => 'Re-identification risk — keep restricted.'],
            ],
        ],
    ],

    'monday' => [
        'pii' => [
            [
                'entity' => 'User / account member',
                'fields' => ['id', 'email', 'name', 'phone'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'monday User — email/phone nur mit Legal-Basis; id als Key.', 'en' => 'monday user — email/phone only with legal basis; id as key.'],
            ],
            [
                'entity' => 'Update / comment body',
                'fields' => ['body', 'text_body'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Update-Freitext kann PII enthalten — Meta reicht für Activity-KPIs.', 'en' => 'Update free text can contain PII — metadata is enough for activity KPIs.'],
            ],
            [
                'entity' => 'Person / email column values',
                'fields' => ['column_value (type=people)', 'column_value (type=email)'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: ids only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: ids only; Mart: aggregates only'],
                'treatment' => ['de' => 'Person/Email-Columns als user ids behandeln, nicht als Klartext-Kontakt.', 'en' => 'Treat person/email columns as user ids, not as cleartext contact info.'],
            ],
            [
                'entity' => 'File column attachments',
                'fields' => ['assets', 'file column values'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'File-Attachments nie ins Warehouse — Presence/Count höchstens.', 'en' => 'Never land file attachments in the warehouse — presence/count at most.'],
            ],
            [
                'entity' => 'Automation / webhook configuration',
                'fields' => ['webhook url', 'integration secrets', 'api tokens'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Automation-Secrets nie landen — reine Config-Referenz falls nötig.', 'en' => 'Never land automation secrets — plain config reference only if needed.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'workspace id, board id, item id, user id (hashed email), update id.', 'en' => 'workspace id, board id, item id, user id (hashed email), update id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Workspace, Board, Item, Column Value Meta, User.', 'en' => 'Workspace, board, item, column value meta, user.'],
            ],
            [
                'focus' => ['de' => 'GraphQL API / webhook exports', 'en' => 'GraphQL API / webhook exports'],
                'notes' => ['de' => 'GraphQL Bulk-Exports und Webhook-Payloads verdoppeln Update-/Person-PII.', 'en' => 'GraphQL bulk exports and webhook payloads duplicate update/person PII.'],
            ],
            [
                'focus' => ['de' => 'Template / sandbox board copies', 'en' => 'Template / sandbox board copies'],
                'notes' => ['de' => 'Template-Boards nicht mit Prod-Delivery-Marts mischen.', 'en' => 'Do not mix template boards with prod delivery marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Update / comment body text (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Freitext-PII-Risiko — Meta/Counts reichen.', 'en' => 'Free-text PII risk — meta/counts suffice.'],
            ],
            [
                'name' => 'File column asset binaries',
                'category' => 'system',
                'reason' => ['de' => 'File-Binaries — nie landen; Presence/Count höchstens.', 'en' => 'File binaries — never land; presence/count at most.'],
            ],
            [
                'name' => 'Webhook / automation secrets',
                'category' => 'security',
                'reason' => ['de' => 'Integration-Secrets — nie speichern.', 'en' => 'Integration secrets — never store.'],
            ],
            [
                'name' => 'Full activity log payload bulk',
                'category' => 'system',
                'reason' => ['de' => 'Ultra-verbose Activity-Log — sampeln/aggregieren.', 'en' => 'Ultra-verbose activity log — sample/aggregate.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Update/comment body cleartext (bulk)',
                'reason' => ['de' => 'PII-Risiko — Meta only.', 'en' => 'PII risk — meta only.'],
            ],
            [
                'name' => 'File column asset binaries',
                'reason' => ['de' => 'Kein Default-Load von Binaries.', 'en' => 'No default load of binaries.'],
            ],
            [
                'name' => 'Webhook URLs / integration tokens',
                'reason' => ['de' => 'Security — nie speichern.', 'en' => 'Security — never store.'],
            ],
            [
                'name' => 'Person/email column cleartext in marts',
                'reason' => ['de' => 'user id reicht für Ownership-KPIs.', 'en' => 'user id is enough for ownership KPIs.'],
            ],
            [
                'name' => 'Template/sandbox boards in prod marts',
                'reason' => ['de' => 'Prod-Delivery-Marts sauber halten.', 'en' => 'Keep prod delivery marts clean.'],
            ],
        ],
    ],

    'moodle' => [
        'pii' => [
            [
                'entity' => 'User / learner profile',
                'fields' => ['id', 'email', 'firstname', 'lastname', 'idnumber', 'phone1'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Learner/Instructor E-Mail und Name — direkte PII; id als Key behalten.', 'en' => 'Learner/instructor email and name — direct PII; keep id as key.'],
            ],
            [
                'entity' => 'Enrollment / cohort membership',
                'fields' => ['userid', 'courseid', 'enrol method', 'cohort membership'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: ids only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: ids only; Mart: aggregates only'],
                'treatment' => ['de' => 'Enrollment ist Bildungsdatensatz — Need-to-know-Zugriff, keine öffentlichen Einzeldaten.', 'en' => 'Enrollment is an educational record — need-to-know access, no public individual-level data.'],
            ],
            [
                'entity' => 'Grades',
                'fields' => ['grade_grades.finalgrade', 'grade_grades.feedback'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: strikt einschränken; Curated: aggregiert only; Mart: aggregates only', 'en' => 'RAW: strictly restrict; Curated: aggregated only; Mart: aggregates only'],
                'treatment' => ['de' => 'Noten und Feedback sind hoch-sensible Bildungsdaten — nie in Default-Marts einzeln.', 'en' => 'Grades and feedback are highly sensitive educational records — never individually in default marts.'],
            ],
            [
                'entity' => 'Assignment submission content',
                'fields' => ['submission files', 'onlinetext content'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Submission-Content nie ins Warehouse — reine Abgabe-Meta.', 'en' => 'Never land submission content in the warehouse — submission meta only.'],
            ],
            [
                'entity' => 'Forum / message content',
                'fields' => ['forum_posts.message', 'messages.smallmessage'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Forum-/Message-Content kann PII enthalten — Count/Meta reicht für Engagement.', 'en' => 'Forum/message content can contain PII — count/meta is enough for engagement.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'user id/idnumber, email (hashed), course id, enrollment (userid+courseid).', 'en' => 'user id/idnumber, email (hashed), course id, enrollment (userid+courseid).'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'User, Course, Enrollment, Grade Item/Grade, Course Module.', 'en' => 'User, course, enrollment, grade item/grade, course module.'],
            ],
            [
                'focus' => ['de' => 'Web Services / backup / logstore copies', 'en' => 'Web services / backup / logstore copies'],
                'notes' => ['de' => 'Moodle Backups und logstore_standard_log verdoppeln Learner-PII über Snapshots.', 'en' => 'Moodle backups and logstore_standard_log duplicate learner PII across snapshots.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / test course copies', 'en' => 'Sandbox / test course copies'],
                'notes' => ['de' => 'Test-Kurse nicht mit Prod-Learning-Marts mischen.', 'en' => 'Do not mix test courses with prod learning marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Assignment submission files / onlinetext content',
                'category' => 'system',
                'reason' => ['de' => 'Submission-Content — nie landen; nur Abgabe-Meta.', 'en' => 'Submission content — never land; meta only.'],
            ],
            [
                'name' => 'Forum post message content',
                'category' => 'pii',
                'reason' => ['de' => 'Freitext-PII-Risiko — Count/Meta reicht.', 'en' => 'Free-text PII risk — count/meta suffices.'],
            ],
            [
                'name' => 'Quiz question bank content / answers',
                'category' => 'system',
                'reason' => ['de' => 'Prüfungsmaterial — nicht Analytics-relevant.', 'en' => 'Exam material — not analytics relevant.'],
            ],
            [
                'name' => 'Full logstore_standard_log dumps (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Ultra-hohes Volumen — sampeln/aggregieren.', 'en' => 'Very high volume — sample/aggregate.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Submission content (files/onlinetext)',
                'reason' => ['de' => 'Nie ins Warehouse — Meta reicht.', 'en' => 'Never into the warehouse — meta is enough.'],
            ],
            [
                'name' => 'Individual grade values in public marts',
                'reason' => ['de' => 'Aggregation statt Einzelnoten für breite Zugriffsebenen.', 'en' => 'Aggregation instead of individual grades for broad access levels.'],
            ],
            [
                'name' => 'Forum/message content cleartext',
                'reason' => ['de' => 'PII-Risiko — Meta only.', 'en' => 'PII risk — meta only.'],
            ],
            [
                'name' => 'Quiz question/answer content',
                'reason' => ['de' => 'Prüfungsmaterial — skip.', 'en' => 'Exam material — skip.'],
            ],
            [
                'name' => 'Test/sandbox course data in prod marts',
                'reason' => ['de' => 'Prod-Learning-Marts sauber halten.', 'en' => 'Keep prod learning marts clean.'],
            ],
        ],
    ],

    'adobe-experience-cloud' => [
        'pii' => [
            [
                'entity' => 'Profile / identity graph',
                'fields' => ['identityMap', 'person.emailAddress', 'person.phoneNumber', 'homeAddress'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Profile-Identity und E-Mail — direkte PII; profile id/ECID als Join, E-Mail hashen.', 'en' => 'Profile identity and email — direct PII; profile id/ECID as join, hash email.'],
            ],
            [
                'entity' => 'Experience event / clickstream',
                'fields' => ['_id_profile', 'device.*', 'environment.ipAddress', 'web.webPageDetails.URL'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: aggregiert / hashed; Mart: keine Klartext-IPs', 'en' => 'RAW: restrict access; Curated: aggregated / hashed; Mart: no cleartext IPs'],
                'treatment' => ['de' => 'Event-zu-Profile-Link und IP sind Security-/Privacy-sensibel — Aggregation bevorzugen.', 'en' => 'Event-to-profile link and IP are security/privacy sensitive — prefer aggregation.'],
            ],
            [
                'entity' => 'Consent records',
                'fields' => ['profile_id', 'purpose', 'status', 'timestamp'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Pflichtfeld für Nutzung; Curated: ids only; Mart: aggregates only', 'en' => 'RAW: required field for use; Curated: ids only; Mart: aggregates only'],
                'treatment' => ['de' => 'Consent muss vor jeder Profile-Nutzung geprüft werden — nie Downstream ohne Purpose-Check.', 'en' => 'Consent must be checked before any profile use — never downstream without a purpose check.'],
            ],
            [
                'entity' => 'Campaign creative content',
                'fields' => ['email HTML body', 'push notification body', 'asset bindings'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Creative-Content nie ins Analytics-Warehouse — Campaign-Meta reicht.', 'en' => 'Never land creative content in the analytics warehouse — campaign meta suffices.'],
            ],
            [
                'entity' => 'Device / cookie identity graph',
                'fields' => ['deviceId', 'cookieId', 'IDFA/AAID', 'household graph links'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash; Mart: no cleartext'],
                'treatment' => ['de' => 'Device/Cookie-Graph ist hochauflösende PII — hashen und Retention kurz halten.', 'en' => 'Device/cookie graph is high-resolution PII — hash and keep retention short.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'profile id / ECID, email (hashed), segment id, campaign id, consent purpose id.', 'en' => 'profile id / ECID, email (hashed), segment id, campaign id, consent purpose id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Profile, Experience Event, Segment Membership, Campaign, Consent.', 'en' => 'Profile, experience event, segment membership, campaign, consent.'],
            ],
            [
                'focus' => ['de' => 'AEP dataset / export / activation copies', 'en' => 'AEP dataset / export / activation copies'],
                'notes' => ['de' => 'Dataset Exports und Activation Destinations verdoppeln Profile-PII in mehreren Systemen.', 'en' => 'Dataset exports and activation destinations duplicate profile PII across multiple systems.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / non-prod AEP copies', 'en' => 'Sandbox / non-prod AEP copies'],
                'notes' => ['de' => 'AEP Sandboxes nicht mit Prod-Marketing-Marts mischen.', 'en' => 'Do not mix AEP sandboxes with prod marketing marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Raw clickstream event payload bulk',
                'category' => 'system',
                'reason' => ['de' => 'Rohes Event-Volumen — sampeln/aggregieren.', 'en' => 'Raw event volume — sample/aggregate.'],
            ],
            [
                'name' => 'Campaign creative content (HTML/assets)',
                'category' => 'system',
                'reason' => ['de' => 'Creative-Bodies — nie landen; Campaign-Meta reicht.', 'en' => 'Creative bodies — never land; campaign meta suffices.'],
            ],
            [
                'name' => 'Device / cookie identity graph raw exports',
                'category' => 'pii',
                'reason' => ['de' => 'Hochauflösende PII — nicht für Default-Marts.', 'en' => 'High-resolution PII — not for default marts.'],
            ],
            [
                'name' => 'Profile attribute bulk exports without consent filter',
                'category' => 'pii',
                'reason' => ['de' => 'Verstoß-Risiko gegen Consent-Purpose — nie ohne Filter exportieren.', 'en' => 'Risk of violating consent purpose — never export without a filter.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Raw clickstream event bulk',
                'reason' => ['de' => 'High volume — sample/aggregate.', 'en' => 'High volume — sample/aggregate.'],
            ],
            [
                'name' => 'Email/identity cleartext in marts',
                'reason' => ['de' => 'profile id / hashed email reicht für Reach-KPIs.', 'en' => 'profile id / hashed email is enough for reach KPIs.'],
            ],
            [
                'name' => 'Campaign creative content bodies',
                'reason' => ['de' => 'Kein Default-Load von Creatives.', 'en' => 'No default load of creatives.'],
            ],
            [
                'name' => 'Device/cookie graph cleartext (bulk)',
                'reason' => ['de' => 'Hochauflösende PII — hashen oder skip.', 'en' => 'High-resolution PII — hash or skip.'],
            ],
            [
                'name' => 'Profile exports without consent filter',
                'reason' => ['de' => 'Compliance-Risiko — nie ohne Purpose-Check.', 'en' => 'Compliance risk — never without a purpose check.'],
            ],
        ],
    ],
];
