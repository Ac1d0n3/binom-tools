<?php

/**
 * Wave 10 supplier library entries — Workplace / Collab / Learning / Marketing (full template depth).
 *
 * Emphasize metadata facts; do not load mail/file bodies, board attachments, LMS submission content,
 * or marketing creative bodies by default. Marketing profiles require consent awareness.
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $wave10Tools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    return [
        [
            'id' => 'exchange',
            'domain' => 'workplace',
            'order' => 380,
            'label' => ['de' => 'Microsoft Exchange', 'en' => 'Microsoft Exchange'],
            'shortPurpose' => [
                'de' => 'Mail/Calendar Meta: Mailbox, Message- und Event-Meta, Distribution Groups — Graph-Load; keine Mail-Bodies oder Attachments.',
                'en' => 'Mail/calendar meta: mailbox, message and event meta, distribution groups — Graph load; no mail bodies or attachments.',
            ],
            'entities' => [
                [
                    'id' => 'mailbox',
                    'label' => ['de' => 'Mailbox', 'en' => 'Mailbox'],
                    'description' => [
                        'de' => 'Exchange Mailbox — primarySmtpAddress, Typ; Fact-Anker für Mail-Aktivität.',
                        'en' => 'Exchange mailbox — primarySmtpAddress, type; fact anchor for mail activity.',
                    ],
                    'grain' => ['de' => 'Eine Mailbox (id)', 'en' => 'One mailbox (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'mail_item_meta',
                    'label' => ['de' => 'Mail Item Meta', 'en' => 'Mail item meta'],
                    'description' => [
                        'de' => 'Message Meta — from/to, receivedDateTime, size; Body/Attachments nie laden.',
                        'en' => 'Message meta — from/to, receivedDateTime, size; never load body/attachments.',
                    ],
                    'grain' => ['de' => 'Eine Message (id) Meta', 'en' => 'One message (id) meta'],
                    'role' => ['de' => 'Mail-Meta-Fact (kein Body)', 'en' => 'Mail meta fact (no body)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'calendar_event',
                    'label' => ['de' => 'Calendar Event', 'en' => 'Calendar event'],
                    'description' => [
                        'de' => 'Event Meta — start/end, organizer, attendees; Subject/Body können sensitiv sein.',
                        'en' => 'Event meta — start/end, organizer, attendees; subject/body may be sensitive.',
                    ],
                    'grain' => ['de' => 'Ein Calendar Event (id)', 'en' => 'One calendar event (id)'],
                    'role' => ['de' => 'Meta-Fact', 'en' => 'Meta fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'distribution_group',
                    'label' => ['de' => 'Distribution Group', 'en' => 'Distribution group'],
                    'description' => [
                        'de' => 'Verteilerliste/M365 Group — Membership-Join für Mail-Reach.',
                        'en' => 'Distribution list/M365 group — membership join for mail reach.',
                    ],
                    'grain' => ['de' => 'Eine Group (id)', 'en' => 'One group (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'mail_flow_rule',
                    'label' => ['de' => 'Mail Flow Rule', 'en' => 'Mail flow rule'],
                    'description' => [
                        'de' => 'Transport Rule — Name, Priorität, Match-Count für Compliance-Analytics.',
                        'en' => 'Transport rule — name, priority, match count for compliance analytics.',
                    ],
                    'grain' => ['de' => 'Eine Mail Flow Rule (id)', 'en' => 'One mail flow rule (id)'],
                    'role' => ['de' => 'Governance-Dimension', 'en' => 'Governance dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'mobile_device',
                    'label' => ['de' => 'Mobile Device', 'en' => 'Mobile device'],
                    'description' => [
                        'de' => 'ActiveSync/Managed Device — Sync-Status und Mailbox-Join.',
                        'en' => 'ActiveSync/managed device — sync status and mailbox join.',
                    ],
                    'grain' => ['de' => 'Ein Device (deviceId)', 'en' => 'One device (deviceId)'],
                    'role' => ['de' => 'Device-Dimension', 'en' => 'Device dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'retention_tag',
                    'label' => ['de' => 'Retention Tag', 'en' => 'Retention tag'],
                    'description' => [
                        'de' => 'Retention/Compliance Tag — Policy-Zuordnung für Mailbox-Governance.',
                        'en' => 'Retention/compliance tag — policy assignment for mailbox governance.',
                    ],
                    'grain' => ['de' => 'Ein Retention Tag (id)', 'en' => 'One retention tag (id)'],
                    'role' => ['de' => 'Governance-Dimension', 'en' => 'Governance dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Mailbox', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Mailbox-Join (Graph id)', 'en' => 'Mailbox join (Graph id)']],
                ['entity' => 'Mailbox', 'name' => 'primarySmtpAddress', 'role' => 'pii', 'why' => ['de' => 'Primäre E-Mail / PII', 'en' => 'Primary email / PII']],
                ['entity' => 'Mailbox', 'name' => 'displayName', 'role' => 'pii', 'why' => ['de' => 'Anzeigename / PII', 'en' => 'Display name / PII']],
                ['entity' => 'Mailbox', 'name' => 'recipientTypeDetails', 'role' => 'dimension', 'why' => ['de' => 'UserMailbox / SharedMailbox / RoomMailbox', 'en' => 'UserMailbox / SharedMailbox / RoomMailbox']],
                ['entity' => 'Mailbox', 'name' => 'whenCreated', 'role' => 'measure', 'why' => ['de' => 'Mailbox-Erstellung', 'en' => 'Mailbox creation']],
                ['entity' => 'MailItemMeta', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Message-Join (Meta)', 'en' => 'Message join (meta)']],
                ['entity' => 'MailItemMeta', 'name' => 'from.emailAddress', 'role' => 'pii', 'why' => ['de' => 'Absender / PII', 'en' => 'Sender / PII']],
                ['entity' => 'MailItemMeta', 'name' => 'toRecipients', 'role' => 'pii', 'why' => ['de' => 'Empfänger / PII', 'en' => 'Recipients / PII']],
                ['entity' => 'MailItemMeta', 'name' => 'receivedDateTime', 'role' => 'measure', 'why' => ['de' => 'Empfangszeitpunkt', 'en' => 'Received timestamp']],
                ['entity' => 'MailItemMeta', 'name' => 'hasAttachments', 'role' => 'dimension', 'why' => ['de' => 'Attachment-Flag (nicht Content)', 'en' => 'Attachment flag (not content)']],
                ['entity' => 'MailItemMeta', 'name' => 'size', 'role' => 'measure', 'why' => ['de' => 'Nachrichtengröße (Bytes)', 'en' => 'Message size (bytes)']],
                ['entity' => 'CalendarEvent', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Event-Join', 'en' => 'Event join']],
                ['entity' => 'CalendarEvent', 'name' => 'subject', 'role' => 'pii', 'why' => ['de' => 'Betreff oft sensitiv', 'en' => 'Subject often sensitive']],
                ['entity' => 'CalendarEvent', 'name' => 'organizer.emailAddress', 'role' => 'pii', 'why' => ['de' => 'Organizer / PII', 'en' => 'Organizer / PII']],
                ['entity' => 'CalendarEvent', 'name' => 'attendees', 'role' => 'pii', 'why' => ['de' => 'Teilnehmerliste / PII', 'en' => 'Attendee list / PII']],
                ['entity' => 'CalendarEvent', 'name' => 'start.dateTime', 'role' => 'measure', 'why' => ['de' => 'Event-Start', 'en' => 'Event start']],
                ['entity' => 'CalendarEvent', 'name' => 'end.dateTime', 'role' => 'measure', 'why' => ['de' => 'Event-Ende / Dauer', 'en' => 'Event end / duration']],
                ['entity' => 'CalendarEvent', 'name' => 'isCancelled', 'role' => 'dimension', 'why' => ['de' => 'Abgesagt-Flag', 'en' => 'Cancelled flag']],
                ['entity' => 'DistributionGroup', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Group-Join', 'en' => 'Group join']],
                ['entity' => 'DistributionGroup', 'name' => 'displayName', 'role' => 'dimension', 'why' => ['de' => 'Gruppenname', 'en' => 'Group display name']],
                ['entity' => 'DistributionGroup', 'name' => 'memberCount', 'role' => 'measure', 'why' => ['de' => 'Mitglieder-Count', 'en' => 'Member count']],
                ['entity' => 'MailFlowRule', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Rule-Join', 'en' => 'Rule join']],
                ['entity' => 'MailFlowRule', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Regelname', 'en' => 'Rule name']],
                ['entity' => 'MailFlowRule', 'name' => 'priority', 'role' => 'dimension', 'why' => ['de' => 'Auswertungsreihenfolge', 'en' => 'Evaluation order']],
                ['entity' => 'MobileDevice', 'name' => 'deviceId', 'role' => 'key', 'why' => ['de' => 'Device-Join', 'en' => 'Device join']],
                ['entity' => 'MobileDevice', 'name' => 'deviceType', 'role' => 'dimension', 'why' => ['de' => 'iOS / Android / Outlook Mobile', 'en' => 'iOS / Android / Outlook Mobile']],
                ['entity' => 'MobileDevice', 'name' => 'lastSyncAttemptTime', 'role' => 'measure', 'why' => ['de' => 'Letzter Sync', 'en' => 'Last sync']],
                ['entity' => 'RetentionTag', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Tag-Join', 'en' => 'Tag join']],
                ['entity' => 'RetentionTag', 'name' => 'retentionAction', 'role' => 'dimension', 'why' => ['de' => 'Delete / MoveToArchive / Keep', 'en' => 'Delete / MoveToArchive / Keep']],
            ],
            'skipTables' => [
                [
                    'name' => 'Mail body / attachment content',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Mail-Bodies und Attachments — nie default laden; nur Meta (from/to/dates/size).',
                        'en' => 'Mail bodies and attachments — never load by default; meta only (from/to/dates/size).',
                    ],
                ],
                [
                    'name' => 'Calendar event body / description',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Event-Bodies können vertraulich sein — Meta (start/end/organizer) reicht.',
                        'en' => 'Event bodies can be confidential — meta (start/end/organizer) suffices.',
                    ],
                ],
                [
                    'name' => 'Journaling / eDiscovery export payloads',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Legal-Hold-Exports — getrennt von Analytics; nie Default-RAW-Load.',
                        'en' => 'Legal-hold exports — separate from analytics; never default RAW load.',
                    ],
                ],
                [
                    'name' => 'Message trace full header dumps',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Volle Header/Routing-Traces — Volumen, wenig Mart-Nutzen über Meta hinaus.',
                        'en' => 'Full header/routing traces — volume, little mart value beyond meta.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Mail body & attachment content', 'reason' => ['de' => 'Kein Default-Load von Bodies', 'en' => 'No default load of bodies']],
                ['name' => 'Calendar event body/description', 'reason' => ['de' => 'Oft vertraulich — Meta reicht', 'en' => 'Often confidential — meta suffices']],
                ['name' => 'eDiscovery / journaling exports', 'reason' => ['de' => 'Legal-Hold — nicht Analytics-RAW', 'en' => 'Legal hold — not analytics RAW']],
                ['name' => 'Full message trace header dumps', 'reason' => ['de' => 'Volumen, wenig Nutzen', 'en' => 'Volume, little value']],
            ],
            'dimensions' => [
                [
                    'id' => 'mailbox_type',
                    'label' => ['de' => 'Mailbox Type', 'en' => 'Mailbox type'],
                    'grain' => ['de' => 'mailbox.recipientTypeDetails', 'en' => 'mailbox.recipientTypeDetails'],
                    'notes' => [
                        'de' => 'User- vs. Shared- vs. Room-Mailbox getrennt auswerten.',
                        'en' => 'Evaluate user vs shared vs room mailboxes separately.',
                    ],
                ],
                [
                    'id' => 'department',
                    'label' => ['de' => 'Department', 'en' => 'Department'],
                    'grain' => ['de' => 'Directory-Join (Entra department)', 'en' => 'Directory join (Entra department)'],
                    'notes' => [
                        'de' => 'Department kommt aus Directory-Join, nicht aus Exchange selbst.',
                        'en' => 'Department comes from a directory join, not from Exchange itself.',
                    ],
                ],
                [
                    'id' => 'event_type',
                    'label' => ['de' => 'Event Type', 'en' => 'Event type'],
                    'grain' => ['de' => 'calendar_event.isCancelled / recurrence', 'en' => 'calendar_event.isCancelled / recurrence'],
                    'notes' => [
                        'de' => 'Single vs. recurring vs. cancelled Events unterscheiden.',
                        'en' => 'Distinguish single vs recurring vs cancelled events.',
                    ],
                ],
                [
                    'id' => 'device_type',
                    'label' => ['de' => 'Device Type', 'en' => 'Device type'],
                    'grain' => ['de' => 'mobile_device.deviceType', 'en' => 'mobile_device.deviceType'],
                    'notes' => [
                        'de' => 'Für Compliance-Reports zu Mobile-Device-Coverage.',
                        'en' => 'For compliance reports on mobile device coverage.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Mailbox',
                    'fields' => ['primarySmtpAddress', 'displayName'],
                    'treatment' => [
                        'de' => 'Mailbox-E-Mail und Name — PII taggen; mailbox id als Join bevorzugen.',
                        'en' => 'Mailbox email and name — tag as PII; prefer mailbox id as join.',
                    ],
                ],
                [
                    'entity' => 'MailItemMeta',
                    'fields' => ['from.emailAddress', 'toRecipients'],
                    'treatment' => [
                        'de' => 'From/To sind PII — für Reach-Analytics hashen oder auf Counts reduzieren.',
                        'en' => 'From/to are PII — hash or reduce to counts for reach analytics.',
                    ],
                ],
                [
                    'entity' => 'CalendarEvent',
                    'fields' => ['subject', 'attendees'],
                    'treatment' => [
                        'de' => 'Subject/Attendees können sensitiv sein — redigieren oder weglassen.',
                        'en' => 'Subject/attendees may be sensitive — redact or omit.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'mailbox id, primarySmtpAddress, internetMessageId, event id, deviceId.',
                        'en' => 'mailbox id, primarySmtpAddress, internetMessageId, event id, deviceId.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Mailbox, Mail Item Meta, Calendar Event Meta, Distribution Group — keine Bodies.',
                        'en' => 'Mailbox, mail item meta, calendar event meta, distribution group — no bodies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'mailboxes-active',
                    'example' => true,
                    'label' => ['de' => 'Mailboxes Active', 'en' => 'Mailboxes active'],
                    'question' => [
                        'de' => 'Wie viele User-Mailboxes sind aktiv (Snapshot)?',
                        'en' => 'How many user mailboxes are active (snapshot)?',
                    ],
                    'formula' => "COUNT(*) FROM mailbox WHERE recipientTypeDetails = 'UserMailbox'",
                    'grain' => ['de' => 'Active User Mailbox', 'en' => 'Active user mailbox'],
                    'dimensions' => ['mailbox_type', 'department'],
                    'fieldsUsed' => ['Mailbox.id', 'Mailbox.recipientTypeDetails'],
                    'sourceHints' => [
                        'de' => 'UserMailbox filtern; Shared/Room getrennt zählen.',
                        'en' => 'Filter UserMailbox; count shared/room separately.',
                    ],
                    'adapt' => [
                        'de' => 'Optional: nur Mailboxes mit Mail-Aktivität in der Periode.',
                        'en' => 'Optional: only mailboxes with mail activity in the period.',
                    ],
                ],
                [
                    'id' => 'emails-sent',
                    'example' => true,
                    'label' => ['de' => 'Emails Sent (Meta Count)', 'en' => 'Emails sent (meta count)'],
                    'question' => [
                        'de' => 'Wie viele Mails wurden in der Periode versendet (Meta-Count)?',
                        'en' => 'How many emails were sent in the period (meta count)?',
                    ],
                    'formula' => "COUNT(*) FROM mail_item_meta WHERE folder = 'SentItems' AND sentDateTime IN period",
                    'grain' => ['de' => 'Gesendete Message (Meta)', 'en' => 'Sent message (meta)'],
                    'dimensions' => ['mailbox_type', 'department'],
                    'fieldsUsed' => ['MailItemMeta.id', 'MailItemMeta.receivedDateTime', 'MailItemMeta.size'],
                    'sourceHints' => [
                        'de' => 'Nur Meta-Zählung; kein Body-Read nötig für Volume-KPI.',
                        'en' => 'Meta count only; no body read needed for volume KPI.',
                    ],
                    'adapt' => [
                        'de' => 'Interne vs. externe Mails über Domain-Vergleich trennen.',
                        'en' => 'Separate internal vs external mail via domain comparison.',
                    ],
                ],
                [
                    'id' => 'meetings-scheduled',
                    'example' => false,
                    'label' => ['de' => 'Meetings Scheduled', 'en' => 'Meetings scheduled'],
                    'question' => [
                        'de' => 'Wie viele Calendar Events wurden in der Periode erstellt?',
                        'en' => 'How many calendar events were created in the period?',
                    ],
                    'formula' => "COUNT(*) FROM calendar_event WHERE isCancelled = false AND createdDateTime IN period",
                    'grain' => ['de' => 'Calendar Event', 'en' => 'Calendar event'],
                    'dimensions' => ['event_type', 'department'],
                    'fieldsUsed' => ['CalendarEvent.id', 'CalendarEvent.start.dateTime', 'CalendarEvent.isCancelled'],
                    'sourceHints' => [
                        'de' => 'Recurring Master vs. Instances im Extract klären.',
                        'en' => 'Clarify recurring master vs instances in the extract.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Meetings mit >1 Attendee als „Meeting“ vs. Focus-Time.',
                        'en' => 'Only meetings with >1 attendee as “meeting” vs focus time.',
                    ],
                ],
                [
                    'id' => 'mail-flow-rule-hits',
                    'example' => false,
                    'label' => ['de' => 'Mail Flow Rule Hits', 'en' => 'Mail flow rule hits'],
                    'question' => [
                        'de' => 'Wie oft griffen Transport Rules in der Periode?',
                        'en' => 'How often did transport rules match in the period?',
                    ],
                    'formula' => 'SUM(match_count) FROM mail_flow_rule_stats WHERE period_date IN period',
                    'grain' => ['de' => 'Rule-Match (Agg)', 'en' => 'Rule match (agg)'],
                    'dimensions' => ['department'],
                    'fieldsUsed' => ['MailFlowRule.id', 'MailFlowRule.name', 'MailFlowRule.priority'],
                    'sourceHints' => [
                        'de' => 'Rule Stats aus Exchange Reporting, nicht aus Message-Bodies ableiten.',
                        'en' => 'Rule stats from Exchange reporting, not derived from message bodies.',
                    ],
                    'adapt' => [
                        'de' => 'DLP-Rules separat für Compliance-Dashboards.',
                        'en' => 'Track DLP rules separately for compliance dashboards.',
                    ],
                ],
                [
                    'id' => 'distribution-groups-count',
                    'example' => false,
                    'label' => ['de' => 'Distribution Groups Count', 'en' => 'Distribution groups count'],
                    'question' => [
                        'de' => 'Wie viele Distribution Groups existieren (Snapshot)?',
                        'en' => 'How many distribution groups exist (snapshot)?',
                    ],
                    'formula' => 'COUNT(*) FROM distribution_group',
                    'grain' => ['de' => 'Distribution Group', 'en' => 'Distribution group'],
                    'dimensions' => ['department'],
                    'fieldsUsed' => ['DistributionGroup.id', 'DistributionGroup.displayName', 'DistributionGroup.memberCount'],
                    'sourceHints' => [
                        'de' => 'Dynamic vs. Static Groups markieren.',
                        'en' => 'Mark dynamic vs static groups.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Groups mit memberCount > 0 als „active groups“.',
                        'en' => 'Only groups with memberCount > 0 as “active groups”.',
                    ],
                ],
            ],
            'tools' => $wave10Tools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'monday',
            'domain' => 'collab',
            'order' => 390,
            'label' => ['de' => 'monday.com', 'en' => 'monday.com'],
            'shortPurpose' => [
                'de' => 'Work OS: Board/Item/Column-Meta, Updates-Counts — GraphQL-Load; keine Update-Bodies oder File-Column-Content.',
                'en' => 'Work OS: board/item/column meta, updates counts — GraphQL load; no update bodies or file-column content.',
            ],
            'entities' => [
                [
                    'id' => 'workspace',
                    'label' => ['de' => 'Workspace', 'en' => 'Workspace'],
                    'description' => [
                        'de' => 'Workspace — Container für Boards; Tenant-Slice.',
                        'en' => 'Workspace — container for boards; tenant slice.',
                    ],
                    'grain' => ['de' => 'Ein Workspace (id)', 'en' => 'One workspace (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'board',
                    'label' => ['de' => 'Board', 'en' => 'Board'],
                    'description' => [
                        'de' => 'Board — Name, Kind (public/private/share); Fact-Anker.',
                        'en' => 'Board — name, kind (public/private/share); fact anchor.',
                    ],
                    'grain' => ['de' => 'Ein Board (id)', 'en' => 'One board (id)'],
                    'role' => ['de' => 'Dimension / Anker', 'en' => 'Dimension / anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'group',
                    'label' => ['de' => 'Group', 'en' => 'Group'],
                    'description' => [
                        'de' => 'Board-Section (Group) — Titel, Farbe; Item-Grouping.',
                        'en' => 'Board section (group) — title, color; item grouping.',
                    ],
                    'grain' => ['de' => 'Eine Group (id @ Board)', 'en' => 'One group (id @ board)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'item',
                    'label' => ['de' => 'Item', 'en' => 'Item'],
                    'description' => [
                        'de' => 'Board Item — name, state, createdAt; Kernobjekt der Work-Fact.',
                        'en' => 'Board item — name, state, createdAt; core object of the work fact.',
                    ],
                    'grain' => ['de' => 'Ein Item (id)', 'en' => 'One item (id)'],
                    'role' => ['de' => 'Work-Fact', 'en' => 'Work fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'column_value',
                    'label' => ['de' => 'Column Value', 'en' => 'Column value'],
                    'description' => [
                        'de' => 'Column Value Meta — status/date/person Werte; Free-Text-Columns selektiv.',
                        'en' => 'Column value meta — status/date/person values; free-text columns selective.',
                    ],
                    'grain' => ['de' => 'Ein Column Value (item_id, column_id)', 'en' => 'One column value (item_id, column_id)'],
                    'role' => ['de' => 'Meta-Fact', 'en' => 'Meta fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'update',
                    'label' => ['de' => 'Update', 'en' => 'Update'],
                    'description' => [
                        'de' => 'Item Update/Comment — body Text; nur Counts/Meta, kein Body default.',
                        'en' => 'Item update/comment — body text; counts/meta only, no body by default.',
                    ],
                    'grain' => ['de' => 'Ein Update (id)', 'en' => 'One update (id)'],
                    'role' => ['de' => 'Activity-Fact (Meta)', 'en' => 'Activity fact (meta)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'monday User — email, name; Workforce-PII.',
                        'en' => 'monday user — email, name; workforce PII.',
                    ],
                    'grain' => ['de' => 'Ein User (id)', 'en' => 'One user (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'activity_log',
                    'label' => ['de' => 'Activity Log', 'en' => 'Activity log'],
                    'description' => [
                        'de' => 'Board Activity Log — event_type, actor; High Volume aggregieren.',
                        'en' => 'Board activity log — event_type, actor; aggregate at high volume.',
                    ],
                    'grain' => ['de' => 'Ein Activity Event', 'en' => 'One activity event'],
                    'role' => ['de' => 'Governance-Fact', 'en' => 'Governance fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Workspace', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Workspace-Join', 'en' => 'Workspace join']],
                ['entity' => 'Workspace', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Workspace-Name', 'en' => 'Workspace name']],
                ['entity' => 'Board', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Board-Join', 'en' => 'Board join']],
                ['entity' => 'Board', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Board-Name', 'en' => 'Board name']],
                ['entity' => 'Board', 'name' => 'board_kind', 'role' => 'dimension', 'why' => ['de' => 'public / private / share', 'en' => 'public / private / share']],
                ['entity' => 'Board', 'name' => 'workspace_id', 'role' => 'dimension', 'why' => ['de' => 'Workspace-Rückjoin', 'en' => 'Workspace back-join']],
                ['entity' => 'Group', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Group-Join', 'en' => 'Group join']],
                ['entity' => 'Group', 'name' => 'title', 'role' => 'dimension', 'why' => ['de' => 'Section-Titel (z. B. Status)', 'en' => 'Section title (e.g. status)']],
                ['entity' => 'Item', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Item-Join', 'en' => 'Item join']],
                ['entity' => 'Item', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Item-Titel (Meta; kann sensitiv sein)', 'en' => 'Item title (meta; may be sensitive)']],
                ['entity' => 'Item', 'name' => 'state', 'role' => 'dimension', 'why' => ['de' => 'active / archived / deleted', 'en' => 'active / archived / deleted']],
                ['entity' => 'Item', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Item-Erstellung', 'en' => 'Item created']],
                ['entity' => 'Item', 'name' => 'updated_at', 'role' => 'measure', 'why' => ['de' => 'Letzte Item-Änderung', 'en' => 'Last item change']],
                ['entity' => 'Item', 'name' => 'creator_id', 'role' => 'dimension', 'why' => ['de' => 'Creator-Join (User)', 'en' => 'Creator join (user)']],
                ['entity' => 'ColumnValue', 'name' => 'item_id', 'role' => 'key', 'why' => ['de' => 'Item-Rückjoin', 'en' => 'Item back-join']],
                ['entity' => 'ColumnValue', 'name' => 'column_id', 'role' => 'key', 'why' => ['de' => 'Column-Join', 'en' => 'Column join']],
                ['entity' => 'ColumnValue', 'name' => 'type', 'role' => 'dimension', 'why' => ['de' => 'status / date / people / text …', 'en' => 'status / date / people / text …']],
                ['entity' => 'ColumnValue', 'name' => 'text', 'role' => 'dimension', 'why' => ['de' => 'Anzeige-Wert (bei Free-Text ggf. sensitiv)', 'en' => 'Display value (may be sensitive for free text)']],
                ['entity' => 'Update', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Update-Join', 'en' => 'Update join']],
                ['entity' => 'Update', 'name' => 'body', 'role' => 'pii', 'why' => ['de' => 'Freitext / kann PII enthalten', 'en' => 'Free text / may contain PII']],
                ['entity' => 'Update', 'name' => 'creator_id', 'role' => 'dimension', 'why' => ['de' => 'Author-Join', 'en' => 'Author join']],
                ['entity' => 'Update', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Update-Zeitpunkt', 'en' => 'Update timestamp']],
                ['entity' => 'User', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'User', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'User', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'User', 'name' => 'enabled', 'role' => 'dimension', 'why' => ['de' => 'Aktiv vs. deaktiviert', 'en' => 'Active vs deactivated']],
                ['entity' => 'ActivityLog', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Event-Join', 'en' => 'Event join']],
                ['entity' => 'ActivityLog', 'name' => 'event', 'role' => 'dimension', 'why' => ['de' => 'Event-Typ (create_item, change_column_value …)', 'en' => 'Event type (create_item, change_column_value …)']],
                ['entity' => 'ActivityLog', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Event-Zeitpunkt', 'en' => 'Event timestamp']],
            ],
            'skipTables' => [
                [
                    'name' => 'Update / comment body text (bulk)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Freitext-Updates können PII enthalten — Meta/Counts reichen für Activity-KPIs.',
                        'en' => 'Free-text updates can contain PII — meta/counts suffice for activity KPIs.',
                    ],
                ],
                [
                    'name' => 'File column attachments',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'File-Column-Binaries — nie default laden; nur Count/Presence.',
                        'en' => 'File-column binaries — never load by default; count/presence only.',
                    ],
                ],
                [
                    'name' => 'Automation recipe payloads (webhook secrets)',
                    'category' => 'security',
                    'reason' => [
                        'de' => 'Automation-Configs können Webhook-URLs/Secrets enthalten — nie speichern.',
                        'en' => 'Automation configs can contain webhook URLs/secrets — never store.',
                    ],
                ],
                [
                    'name' => 'Full activity log payload bulk',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Ultra-verbose Activity-Log — sampeln/aggregieren; Event-Type-Meta bevorzugen.',
                        'en' => 'Ultra-verbose activity log — sample/aggregate; prefer event-type meta.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Update/comment body text (bulk)', 'reason' => ['de' => 'PII-Risiko — Meta reicht', 'en' => 'PII risk — meta suffices']],
                ['name' => 'File column attachments', 'reason' => ['de' => 'Kein Default-Load von Binaries', 'en' => 'No default load of binaries']],
                ['name' => 'Automation webhook secrets', 'reason' => ['de' => 'Security — nie speichern', 'en' => 'Security — never store']],
                ['name' => 'Full activity log dumps', 'reason' => ['de' => 'Volumen — Meta/Agg bevorzugen', 'en' => 'Volume — prefer meta/agg']],
            ],
            'dimensions' => [
                [
                    'id' => 'workspace',
                    'label' => ['de' => 'Workspace', 'en' => 'Workspace'],
                    'grain' => ['de' => 'workspace.id / name', 'en' => 'workspace.id / name'],
                    'notes' => [
                        'de' => 'Primärer Tenant-Slice für Boards.',
                        'en' => 'Primary tenant slice for boards.',
                    ],
                ],
                [
                    'id' => 'board',
                    'label' => ['de' => 'Board', 'en' => 'Board'],
                    'grain' => ['de' => 'board.id / name', 'en' => 'board.id / name'],
                    'notes' => [
                        'de' => 'Board-Ebene für Item- und Update-Counts.',
                        'en' => 'Board level for item and update counts.',
                    ],
                ],
                [
                    'id' => 'status',
                    'label' => ['de' => 'Status', 'en' => 'Status'],
                    'grain' => ['de' => 'column_value (type=status).text', 'en' => 'column_value (type=status).text'],
                    'notes' => [
                        'de' => 'Status-Labels sind Board-spezifisch — vor Rollup mappen.',
                        'en' => 'Status labels are board-specific — map before rollup.',
                    ],
                ],
                [
                    'id' => 'column_type',
                    'label' => ['de' => 'Column Type', 'en' => 'Column type'],
                    'grain' => ['de' => 'column_value.type', 'en' => 'column_value.type'],
                    'notes' => [
                        'de' => 'status/date/people/text-Columns getrennt behandeln.',
                        'en' => 'Handle status/date/people/text columns separately.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['email', 'name'],
                    'treatment' => [
                        'de' => 'User-E-Mail/Name — Workforce-PII; id als Join bevorzugen.',
                        'en' => 'User email/name — workforce PII; prefer id as join.',
                    ],
                ],
                [
                    'entity' => 'Update',
                    'fields' => ['body'],
                    'treatment' => [
                        'de' => 'Update-Body ist Freitext und kann PII enthalten — Default drop, nur Meta.',
                        'en' => 'Update body is free text and may contain PII — default drop, meta only.',
                    ],
                ],
                [
                    'entity' => 'ColumnValue',
                    'fields' => ['text'],
                    'treatment' => [
                        'de' => 'Person-/Text-Columns können PII enthalten — Schema prüfen, selektiv laden.',
                        'en' => 'Person/text columns can contain PII — review schema, load selectively.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'workspace id, board id, item id, user id, update id.',
                        'en' => 'workspace id, board id, item id, user id, update id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Board, Item, Column Value Meta, User — keine Update-Bodies/Attachments.',
                        'en' => 'Board, item, column value meta, user — no update bodies/attachments.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'items-created',
                    'example' => true,
                    'label' => ['de' => 'Items Created', 'en' => 'Items created'],
                    'question' => [
                        'de' => 'Wie viele Items wurden in der Periode erstellt?',
                        'en' => 'How many items were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM item WHERE created_at IN period',
                    'grain' => ['de' => 'Created Item', 'en' => 'Created item'],
                    'dimensions' => ['workspace', 'board'],
                    'fieldsUsed' => ['Item.id', 'Item.created_at', 'Board.id'],
                    'sourceHints' => [
                        'de' => 'state=active filtern; Templates/Duplicated Boards ausschließen.',
                        'en' => 'Filter state=active; exclude templates/duplicated boards.',
                    ],
                    'adapt' => [
                        'de' => 'Subitems optional getrennt von Top-Level-Items zählen.',
                        'en' => 'Optionally count subitems separately from top-level items.',
                    ],
                ],
                [
                    'id' => 'items-completed',
                    'example' => true,
                    'label' => ['de' => 'Items Completed', 'en' => 'Items completed'],
                    'question' => [
                        'de' => 'Wie viele Items erreichten in der Periode Status „Done“?',
                        'en' => 'How many items reached “Done” status in the period?',
                    ],
                    'formula' => "COUNT(*) FROM column_value WHERE type = 'status' AND text = 'Done' AND updated_at IN period",
                    'grain' => ['de' => 'Item mit Status Done', 'en' => 'Item with status done'],
                    'dimensions' => ['workspace', 'board', 'status'],
                    'fieldsUsed' => ['ColumnValue.item_id', 'ColumnValue.text', 'ColumnValue.type'],
                    'sourceHints' => [
                        'de' => '„Done“-Label ist Board-spezifisch — Mapping-Tabelle je Board pflegen.',
                        'en' => '“Done” label is board-specific — maintain a mapping table per board.',
                    ],
                    'adapt' => [
                        'de' => 'Mehrere „done-like“ Labels (Done, Shipped, Closed) zusammenfassen.',
                        'en' => 'Combine multiple “done-like” labels (Done, Shipped, Closed).',
                    ],
                ],
                [
                    'id' => 'boards-active',
                    'example' => false,
                    'label' => ['de' => 'Boards Active', 'en' => 'Boards active'],
                    'question' => [
                        'de' => 'Wie viele Boards hatten in der Periode Item-Aktivität?',
                        'en' => 'How many boards had item activity in the period?',
                    ],
                    'formula' => 'COUNT(DISTINCT board_id) FROM item WHERE updated_at IN period',
                    'grain' => ['de' => 'Board mit Aktivität', 'en' => 'Board with activity'],
                    'dimensions' => ['workspace'],
                    'fieldsUsed' => ['Item.updated_at', 'Board.id', 'Board.workspace_id'],
                    'sourceHints' => [
                        'de' => 'Aktivität über item.updated_at oder activity_log — Definition locken.',
                        'en' => 'Activity via item.updated_at or activity_log — lock definition.',
                    ],
                    'adapt' => [
                        'de' => 'Template-/Archived-Boards optional ausschließen.',
                        'en' => 'Optionally exclude template/archived boards.',
                    ],
                ],
                [
                    'id' => 'updates-count',
                    'example' => false,
                    'label' => ['de' => 'Updates Count', 'en' => 'Updates count'],
                    'question' => [
                        'de' => 'Wie viele Updates/Comments wurden in der Periode erstellt?',
                        'en' => 'How many updates/comments were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM update WHERE created_at IN period',
                    'grain' => ['de' => 'Update (Meta)', 'en' => 'Update (meta)'],
                    'dimensions' => ['workspace', 'board'],
                    'fieldsUsed' => ['Update.id', 'Update.created_at', 'Update.creator_id'],
                    'sourceHints' => [
                        'de' => 'Nur Meta-Count; Body nicht für KPI nötig.',
                        'en' => 'Meta count only; body not needed for the KPI.',
                    ],
                    'adapt' => [
                        'de' => 'Reply-Updates vs. Top-Level-Updates trennen.',
                        'en' => 'Separate reply updates vs top-level updates.',
                    ],
                ],
                [
                    'id' => 'overdue-items',
                    'example' => false,
                    'label' => ['de' => 'Overdue Items', 'en' => 'Overdue items'],
                    'question' => [
                        'de' => 'Wie viele Items haben ein Datum in der Vergangenheit ohne Done-Status?',
                        'en' => 'How many items have a past date without done status?',
                    ],
                    'formula' => "COUNT(*) FROM item i JOIN column_value cv ON cv.item_id = i.id WHERE cv.type = 'date' AND cv.date_value < CURRENT_DATE AND i.status <> 'Done'",
                    'grain' => ['de' => 'Overdue Item', 'en' => 'Overdue item'],
                    'dimensions' => ['workspace', 'board', 'status'],
                    'fieldsUsed' => ['ColumnValue.item_id', 'ColumnValue.type', 'ColumnValue.text', 'Item.state'],
                    'sourceHints' => [
                        'de' => 'Date-Column-ID je Board unterscheiden; Timeline-Columns separat behandeln.',
                        'en' => 'Date column id differs per board; handle timeline columns separately.',
                    ],
                    'adapt' => [
                        'de' => 'Archived Items aus Overdue-Berechnung ausschließen.',
                        'en' => 'Exclude archived items from the overdue calculation.',
                    ],
                ],
            ],
            'tools' => $wave10Tools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'moodle',
            'domain' => 'learning',
            'order' => 400,
            'label' => ['de' => 'Moodle', 'en' => 'Moodle'],
            'shortPurpose' => [
                'de' => 'LMS: User/Course/Enrollment/Grade-Meta — Web-Services-Load; Enrollments/Grades vorsichtig behandeln, keine Submission-Inhalte.',
                'en' => 'LMS: user/course/enrollment/grade meta — web services load; handle enrollments/grades carefully, no submission content.',
            ],
            'entities' => [
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'Moodle User — email, fullname; Learner/Instructor-PII.',
                        'en' => 'Moodle user — email, fullname; learner/instructor PII.',
                    ],
                    'grain' => ['de' => 'Ein User (id)', 'en' => 'One user (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'course',
                    'label' => ['de' => 'Course', 'en' => 'Course'],
                    'description' => [
                        'de' => 'Course — fullname, category, startdate; Fact-Anker.',
                        'en' => 'Course — fullname, category, startdate; fact anchor.',
                    ],
                    'grain' => ['de' => 'Ein Course (id)', 'en' => 'One course (id)'],
                    'role' => ['de' => 'Dimension / Anker', 'en' => 'Dimension / anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'enrollment',
                    'label' => ['de' => 'Enrollment', 'en' => 'Enrollment'],
                    'description' => [
                        'de' => 'Course Enrollment — status, timestart/timeend; Bildungsdaten, vorsichtig behandeln.',
                        'en' => 'Course enrollment — status, timestart/timeend; educational record, handle carefully.',
                    ],
                    'grain' => ['de' => 'Eine Enrollment (user_id, course_id)', 'en' => 'One enrollment (user_id, course_id)'],
                    'role' => ['de' => 'Fact (sensibel)', 'en' => 'Fact (sensitive)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'course_module',
                    'label' => ['de' => 'Course Module', 'en' => 'Course module'],
                    'description' => [
                        'de' => 'Activity/Resource Module — modname, completion-Flag.',
                        'en' => 'Activity/resource module — modname, completion flag.',
                    ],
                    'grain' => ['de' => 'Ein Module (id)', 'en' => 'One module (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'grade_item',
                    'label' => ['de' => 'Grade Item', 'en' => 'Grade item'],
                    'description' => [
                        'de' => 'Grade Item — itemname, grademax; Struktur für Grade-Facts.',
                        'en' => 'Grade item — itemname, grademax; structure for grade facts.',
                    ],
                    'grain' => ['de' => 'Ein Grade Item (id)', 'en' => 'One grade item (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'grade_grade',
                    'label' => ['de' => 'Grade Grade', 'en' => 'Grade grade'],
                    'description' => [
                        'de' => 'Individuelle Grade — finalgrade je User/Item; sensibler Bildungsdatensatz.',
                        'en' => 'Individual grade — finalgrade per user/item; sensitive educational record.',
                    ],
                    'grain' => ['de' => 'Eine Grade (user_id, item_id)', 'en' => 'One grade (user_id, item_id)'],
                    'role' => ['de' => 'Fact (sensibel)', 'en' => 'Fact (sensitive)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'quiz_attempt',
                    'label' => ['de' => 'Quiz Attempt', 'en' => 'Quiz attempt'],
                    'description' => [
                        'de' => 'Quiz Attempt Meta — state, timefinish, sumgrades; keine Antworttexte.',
                        'en' => 'Quiz attempt meta — state, timefinish, sumgrades; no answer text.',
                    ],
                    'grain' => ['de' => 'Ein Attempt (id)', 'en' => 'One attempt (id)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'forum_post',
                    'label' => ['de' => 'Forum Post', 'en' => 'Forum post'],
                    'description' => [
                        'de' => 'Forum Post Meta — Count/Timestamps; Message-Content nicht laden.',
                        'en' => 'Forum post meta — count/timestamps; do not load message content.',
                    ],
                    'grain' => ['de' => 'Ein Post (id) Meta', 'en' => 'One post (id) meta'],
                    'role' => ['de' => 'Engagement-Fact (Meta)', 'en' => 'Engagement fact (meta)'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'User', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'User', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'User', 'name' => 'fullname', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'User', 'name' => 'suspended', 'role' => 'dimension', 'why' => ['de' => 'Aktiv vs. suspended', 'en' => 'Active vs suspended']],
                ['entity' => 'Course', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Course-Join', 'en' => 'Course join']],
                ['entity' => 'Course', 'name' => 'fullname', 'role' => 'dimension', 'why' => ['de' => 'Kursname', 'en' => 'Course name']],
                ['entity' => 'Course', 'name' => 'category', 'role' => 'dimension', 'why' => ['de' => 'Kurskategorie', 'en' => 'Course category']],
                ['entity' => 'Course', 'name' => 'startdate', 'role' => 'measure', 'why' => ['de' => 'Kursstart', 'en' => 'Course start']],
                ['entity' => 'Enrollment', 'name' => 'user_id', 'role' => 'key', 'why' => ['de' => 'User-Rückjoin', 'en' => 'User back-join']],
                ['entity' => 'Enrollment', 'name' => 'course_id', 'role' => 'key', 'why' => ['de' => 'Course-Rückjoin', 'en' => 'Course back-join']],
                ['entity' => 'Enrollment', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'active / suspended', 'en' => 'active / suspended']],
                ['entity' => 'Enrollment', 'name' => 'timestart', 'role' => 'measure', 'why' => ['de' => 'Enrollment-Start', 'en' => 'Enrollment start']],
                ['entity' => 'Enrollment', 'name' => 'timeend', 'role' => 'measure', 'why' => ['de' => 'Enrollment-Ende', 'en' => 'Enrollment end']],
                ['entity' => 'CourseModule', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Module-Join', 'en' => 'Module join']],
                ['entity' => 'CourseModule', 'name' => 'modname', 'role' => 'dimension', 'why' => ['de' => 'quiz / assign / forum / resource …', 'en' => 'quiz / assign / forum / resource …']],
                ['entity' => 'CourseModule', 'name' => 'completion', 'role' => 'dimension', 'why' => ['de' => 'Completion-Tracking aktiv?', 'en' => 'Completion tracking enabled?']],
                ['entity' => 'GradeItem', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Grade-Item-Join', 'en' => 'Grade item join']],
                ['entity' => 'GradeItem', 'name' => 'itemname', 'role' => 'dimension', 'why' => ['de' => 'Bezeichnung des Grade Items', 'en' => 'Grade item name']],
                ['entity' => 'GradeItem', 'name' => 'grademax', 'role' => 'dimension', 'why' => ['de' => 'Max-Punktzahl', 'en' => 'Max score']],
                ['entity' => 'GradeGrade', 'name' => 'user_id', 'role' => 'key', 'why' => ['de' => 'User-Rückjoin (sensibel)', 'en' => 'User back-join (sensitive)']],
                ['entity' => 'GradeGrade', 'name' => 'itemid', 'role' => 'key', 'why' => ['de' => 'Grade-Item-Rückjoin', 'en' => 'Grade item back-join']],
                ['entity' => 'GradeGrade', 'name' => 'finalgrade', 'role' => 'pii', 'why' => ['de' => 'Note — Bildungsdatensatz / PII', 'en' => 'Grade — educational record / PII']],
                ['entity' => 'GradeGrade', 'name' => 'timemodified', 'role' => 'measure', 'why' => ['de' => 'Letzte Bewertung', 'en' => 'Last graded']],
                ['entity' => 'QuizAttempt', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Attempt-Join', 'en' => 'Attempt join']],
                ['entity' => 'QuizAttempt', 'name' => 'state', 'role' => 'dimension', 'why' => ['de' => 'inprogress / finished / abandoned', 'en' => 'inprogress / finished / abandoned']],
                ['entity' => 'QuizAttempt', 'name' => 'sumgrades', 'role' => 'measure', 'why' => ['de' => 'Erzielte Punktzahl', 'en' => 'Achieved score']],
                ['entity' => 'QuizAttempt', 'name' => 'timefinish', 'role' => 'measure', 'why' => ['de' => 'Abschlusszeitpunkt', 'en' => 'Completion timestamp']],
                ['entity' => 'ForumPost', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Post-Join (Meta)', 'en' => 'Post join (meta)']],
                ['entity' => 'ForumPost', 'name' => 'created', 'role' => 'measure', 'why' => ['de' => 'Post-Zeitpunkt', 'en' => 'Post timestamp']],
            ],
            'skipTables' => [
                [
                    'name' => 'Assignment submission file content',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Submission-Files — nie default laden; nur Abgabe-Meta (Zeit, Status).',
                        'en' => 'Submission files — never load by default; submission meta only (time, status).',
                    ],
                ],
                [
                    'name' => 'Forum post message content',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Forum-Bodies können PII enthalten — nur Post-Counts/Timestamps.',
                        'en' => 'Forum bodies can contain PII — post counts/timestamps only.',
                    ],
                ],
                [
                    'name' => 'Quiz question content / answer text',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Fragen/Antworttexte — Prüfungsmaterial, nicht für Analytics-Warehouse.',
                        'en' => 'Question/answer text — exam material, not for the analytics warehouse.',
                    ],
                ],
                [
                    'name' => 'Full logstore_standard_log dumps (high volume)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Roher Event-Log — sehr hohes Volumen; sampeln oder auf Meta-Events reduzieren.',
                        'en' => 'Raw event log — very high volume; sample or reduce to meta events.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Assignment submission file content', 'reason' => ['de' => 'Kein Default-Load von Submission-Files', 'en' => 'No default load of submission files']],
                ['name' => 'Forum post message content', 'reason' => ['de' => 'PII-Risiko — Meta reicht', 'en' => 'PII risk — meta suffices']],
                ['name' => 'Quiz question/answer text', 'reason' => ['de' => 'Prüfungsmaterial — nicht Warehouse-relevant', 'en' => 'Exam material — not warehouse relevant']],
                ['name' => 'Full logstore_standard_log dumps', 'reason' => ['de' => 'Volumen — sampeln/aggregieren', 'en' => 'Volume — sample/aggregate']],
            ],
            'dimensions' => [
                [
                    'id' => 'course',
                    'label' => ['de' => 'Course', 'en' => 'Course'],
                    'grain' => ['de' => 'course.id / fullname', 'en' => 'course.id / fullname'],
                    'notes' => [
                        'de' => 'Primärer Slice für Enrollment- und Grade-KPIs.',
                        'en' => 'Primary slice for enrollment and grade KPIs.',
                    ],
                ],
                [
                    'id' => 'category',
                    'label' => ['de' => 'Course Category', 'en' => 'Course category'],
                    'grain' => ['de' => 'course.category', 'en' => 'course.category'],
                    'notes' => [
                        'de' => 'Kategorie-Baum für Rollups auf Fakultäts-/Programmebene.',
                        'en' => 'Category tree for rollups at faculty/program level.',
                    ],
                ],
                [
                    'id' => 'activity_type',
                    'label' => ['de' => 'Activity Type', 'en' => 'Activity type'],
                    'grain' => ['de' => 'course_module.modname', 'en' => 'course_module.modname'],
                    'notes' => [
                        'de' => 'quiz/assign/forum getrennt für Engagement-Analysen.',
                        'en' => 'Separate quiz/assign/forum for engagement analysis.',
                    ],
                ],
                [
                    'id' => 'grade_item',
                    'label' => ['de' => 'Grade Item', 'en' => 'Grade item'],
                    'grain' => ['de' => 'grade_item.id / itemname', 'en' => 'grade_item.id / itemname'],
                    'notes' => [
                        'de' => 'Course-Total vs. einzelne Assessment-Items unterscheiden.',
                        'en' => 'Distinguish course total vs individual assessment items.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['email', 'fullname'],
                    'treatment' => [
                        'de' => 'Learner/Instructor E-Mail und Name — PII; id als Join bevorzugen.',
                        'en' => 'Learner/instructor email and name — PII; prefer id as join.',
                    ],
                ],
                [
                    'entity' => 'GradeGrade',
                    'fields' => ['finalgrade'],
                    'treatment' => [
                        'de' => 'Noten sind Bildungsdatensätze — restriktiver Zugriff, Aggregation statt Einzelwerte in Default-Marts.',
                        'en' => 'Grades are educational records — restrict access, prefer aggregation over individual values in default marts.',
                    ],
                ],
                [
                    'entity' => 'Enrollment',
                    'fields' => ['user_id', 'course_id'],
                    'treatment' => [
                        'de' => 'Enrollment-Zuordnung ist Bildungsdatensatz — Zugriff nach Need-to-know.',
                        'en' => 'Enrollment mapping is an educational record — access on a need-to-know basis.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'user id/idnumber, course id, enrollment (user_id+course_id), grade_item id.',
                        'en' => 'user id/idnumber, course id, enrollment (user_id+course_id), grade_item id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'User, Course, Enrollment, Grade Item/Grade — keine Submission-Inhalte.',
                        'en' => 'User, course, enrollment, grade item/grade — no submission content.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'active-enrollments',
                    'example' => true,
                    'label' => ['de' => 'Active Enrollments', 'en' => 'Active enrollments'],
                    'question' => [
                        'de' => 'Wie viele aktive Enrollments gibt es (Snapshot)?',
                        'en' => 'How many active enrollments are there (snapshot)?',
                    ],
                    'formula' => "COUNT(*) FROM enrollment WHERE status = 'active'",
                    'grain' => ['de' => 'Active Enrollment', 'en' => 'Active enrollment'],
                    'dimensions' => ['course', 'category'],
                    'fieldsUsed' => ['Enrollment.user_id', 'Enrollment.course_id', 'Enrollment.status'],
                    'sourceHints' => [
                        'de' => 'status=active; suspended getrennt zählen.',
                        'en' => 'status=active; count suspended separately.',
                    ],
                    'adapt' => [
                        'de' => 'Selbst-Enrollment vs. Manual/Cohort-Enrollment methode trennen.',
                        'en' => 'Separate self-enrollment vs manual/cohort enrollment method.',
                    ],
                ],
                [
                    'id' => 'course-completions',
                    'example' => true,
                    'label' => ['de' => 'Course Completions', 'en' => 'Course completions'],
                    'question' => [
                        'de' => 'Wie viele Course Completions wurden in der Periode erreicht?',
                        'en' => 'How many course completions were reached in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM course_completion WHERE timecompleted IN period',
                    'grain' => ['de' => 'Completed Enrollment', 'en' => 'Completed enrollment'],
                    'dimensions' => ['course', 'category'],
                    'fieldsUsed' => ['Enrollment.user_id', 'Enrollment.course_id', 'CourseModule.completion'],
                    'sourceHints' => [
                        'de' => 'Completion-Tracking muss im Kurs aktiviert sein — sonst NULL.',
                        'en' => 'Completion tracking must be enabled in the course — otherwise NULL.',
                    ],
                    'adapt' => [
                        'de' => 'Completion Rate = completions / active enrollments je Kurs.',
                        'en' => 'Completion rate = completions / active enrollments per course.',
                    ],
                ],
                [
                    'id' => 'average-grade',
                    'example' => false,
                    'label' => ['de' => 'Average Grade', 'en' => 'Average grade'],
                    'question' => [
                        'de' => 'Wie hoch ist die durchschnittliche finale Note je Grade Item?',
                        'en' => 'What is the average final grade per grade item?',
                    ],
                    'formula' => 'AVG(finalgrade) FROM grade_grade WHERE itemid = :item AND timemodified IN period',
                    'grain' => ['de' => 'Grade Item (aggregiert)', 'en' => 'Grade item (aggregated)'],
                    'dimensions' => ['course', 'grade_item'],
                    'fieldsUsed' => ['GradeGrade.finalgrade', 'GradeGrade.itemid', 'GradeItem.grademax'],
                    'sourceHints' => [
                        'de' => 'Nur Aggregate in Default-Marts; Einzelnoten restriktiv.',
                        'en' => 'Aggregates only in default marts; restrict individual grades.',
                    ],
                    'adapt' => [
                        'de' => 'Normalisierung auf grademax für Vergleichbarkeit über Items.',
                        'en' => 'Normalize by grademax for comparability across items.',
                    ],
                ],
                [
                    'id' => 'quiz-attempts-count',
                    'example' => false,
                    'label' => ['de' => 'Quiz Attempts Count', 'en' => 'Quiz attempts count'],
                    'question' => [
                        'de' => 'Wie viele Quiz Attempts wurden in der Periode abgeschlossen?',
                        'en' => 'How many quiz attempts were completed in the period?',
                    ],
                    'formula' => "COUNT(*) FROM quiz_attempt WHERE state = 'finished' AND timefinish IN period",
                    'grain' => ['de' => 'Finished Quiz Attempt', 'en' => 'Finished quiz attempt'],
                    'dimensions' => ['course', 'activity_type'],
                    'fieldsUsed' => ['QuizAttempt.state', 'QuizAttempt.timefinish', 'QuizAttempt.sumgrades'],
                    'sourceHints' => [
                        'de' => 'Abandoned Attempts ausschließen; Retakes über attempt-Nummer sichtbar.',
                        'en' => 'Exclude abandoned attempts; retakes visible via attempt number.',
                    ],
                    'adapt' => [
                        'de' => 'Nur letzter Attempt je User/Quiz für Pass-Rate-Definitionen.',
                        'en' => 'Only last attempt per user/quiz for pass-rate definitions.',
                    ],
                ],
                [
                    'id' => 'active-learners',
                    'example' => false,
                    'label' => ['de' => 'Active Learners', 'en' => 'Active learners'],
                    'question' => [
                        'de' => 'Wie viele Learner hatten in der Periode Aktivität (Login/Modul-Zugriff)?',
                        'en' => 'How many learners had activity in the period (login/module access)?',
                    ],
                    'formula' => 'COUNT(DISTINCT user_id) FROM user WHERE lastaccess IN period',
                    'grain' => ['de' => 'Active Learner', 'en' => 'Active learner'],
                    'dimensions' => ['course', 'category'],
                    'fieldsUsed' => ['User.id', 'User.suspended'],
                    'sourceHints' => [
                        'de' => 'Course-Level lastaccess (user_lastaccess) statt Site-lastaccess für Course-KPIs.',
                        'en' => 'Use course-level lastaccess (user_lastaccess) rather than site lastaccess for course KPIs.',
                    ],
                    'adapt' => [
                        'de' => 'Instructor-Accounts aus Learner-Aktivität ausschließen.',
                        'en' => 'Exclude instructor accounts from learner activity.',
                    ],
                ],
            ],
            'tools' => $wave10Tools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'adobe-experience-cloud',
            'domain' => 'marketing',
            'order' => 410,
            'label' => ['de' => 'Adobe Experience Cloud', 'en' => 'Adobe Experience Cloud'],
            'shortPurpose' => [
                'de' => 'Marketing: Profile/Events/Segmente mit Consent — AEP-Load; keine Klartext-E-Mails oder Creative-Bodies in Default-Marts.',
                'en' => 'Marketing: profiles/events/segments with consent — AEP load; no cleartext emails or creative bodies in default marts.',
            ],
            'entities' => [
                [
                    'id' => 'profile',
                    'label' => ['de' => 'Profile', 'en' => 'Profile'],
                    'description' => [
                        'de' => 'Real-Time CDP Profile — identityMap, Consent-Status; zentrale PII-Fläche.',
                        'en' => 'Real-Time CDP profile — identityMap, consent status; central PII surface.',
                    ],
                    'grain' => ['de' => 'Ein Profile (id)', 'en' => 'One profile (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'experience_event',
                    'label' => ['de' => 'Experience Event', 'en' => 'Experience event'],
                    'description' => [
                        'de' => 'Clickstream/Marketing Event — eventType, timestamp; High Volume.',
                        'en' => 'Clickstream/marketing event — eventType, timestamp; high volume.',
                    ],
                    'grain' => ['de' => 'Ein Event (id)', 'en' => 'One event (id)'],
                    'role' => ['de' => 'Fact (high volume)', 'en' => 'Fact (high volume)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'segment',
                    'label' => ['de' => 'Segment', 'en' => 'Segment'],
                    'description' => [
                        'de' => 'Audience Segment — Name, Definition; Dimension für Targeting.',
                        'en' => 'Audience segment — name, definition; dimension for targeting.',
                    ],
                    'grain' => ['de' => 'Ein Segment (id)', 'en' => 'One segment (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'segment_membership',
                    'label' => ['de' => 'Segment Membership', 'en' => 'Segment membership'],
                    'description' => [
                        'de' => 'Qualification Event — Profile in/out Segment; Fact für Reach.',
                        'en' => 'Qualification event — profile in/out segment; fact for reach.',
                    ],
                    'grain' => ['de' => 'Eine Membership (profile_id, segment_id)', 'en' => 'One membership (profile_id, segment_id)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'campaign',
                    'label' => ['de' => 'Campaign', 'en' => 'Campaign'],
                    'description' => [
                        'de' => 'Campaign/Message — Name, Kanal, Status; Dimension.',
                        'en' => 'Campaign/message — name, channel, status; dimension.',
                    ],
                    'grain' => ['de' => 'Eine Campaign (id)', 'en' => 'One campaign (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'consent',
                    'label' => ['de' => 'Consent', 'en' => 'Consent'],
                    'description' => [
                        'de' => 'Consent Record — purpose, status, timestamp; Governance-kritisch.',
                        'en' => 'Consent record — purpose, status, timestamp; governance-critical.',
                    ],
                    'grain' => ['de' => 'Ein Consent Record (profile_id, purpose)', 'en' => 'One consent record (profile_id, purpose)'],
                    'role' => ['de' => 'Governance-Fact', 'en' => 'Governance fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'dataset',
                    'label' => ['de' => 'Dataset', 'en' => 'Dataset'],
                    'description' => [
                        'de' => 'AEP Dataset — Schema-Zuordnung für Ingestion.',
                        'en' => 'AEP dataset — schema mapping for ingestion.',
                    ],
                    'grain' => ['de' => 'Ein Dataset (id)', 'en' => 'One dataset (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'journey',
                    'label' => ['de' => 'Journey', 'en' => 'Journey'],
                    'description' => [
                        'de' => 'Journey Orchestration — Name, Status; Flow-Dimension.',
                        'en' => 'Journey orchestration — name, status; flow dimension.',
                    ],
                    'grain' => ['de' => 'Eine Journey (id)', 'en' => 'One journey (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Profile', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Profile-Join (ECID/CRM-ID)', 'en' => 'Profile join (ECID/CRM ID)']],
                ['entity' => 'Profile', 'name' => 'identityMap', 'role' => 'pii', 'why' => ['de' => 'Identity-Graph / PII', 'en' => 'Identity graph / PII']],
                ['entity' => 'Profile', 'name' => 'person.emailAddress', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'Profile', 'name' => 'consentStatus', 'role' => 'dimension', 'why' => ['de' => 'Consent-Status-Rollup', 'en' => 'Consent status rollup']],
                ['entity' => 'ExperienceEvent', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Event-Join', 'en' => 'Event join']],
                ['entity' => 'ExperienceEvent', 'name' => 'eventType', 'role' => 'dimension', 'why' => ['de' => 'web.webpagedetails.pageViews etc.', 'en' => 'web.webpagedetails.pageViews etc.']],
                ['entity' => 'ExperienceEvent', 'name' => 'timestamp', 'role' => 'measure', 'why' => ['de' => 'Event-Zeitpunkt', 'en' => 'Event timestamp']],
                ['entity' => 'ExperienceEvent', 'name' => '_id_profile', 'role' => 'dimension', 'why' => ['de' => 'Profile-Rückjoin', 'en' => 'Profile back-join']],
                ['entity' => 'Segment', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Segment-Join', 'en' => 'Segment join']],
                ['entity' => 'Segment', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Segment-Name', 'en' => 'Segment name']],
                ['entity' => 'SegmentMembership', 'name' => 'profile_id', 'role' => 'key', 'why' => ['de' => 'Profile-Rückjoin', 'en' => 'Profile back-join']],
                ['entity' => 'SegmentMembership', 'name' => 'segment_id', 'role' => 'key', 'why' => ['de' => 'Segment-Rückjoin', 'en' => 'Segment back-join']],
                ['entity' => 'SegmentMembership', 'name' => 'qualified_at', 'role' => 'measure', 'why' => ['de' => 'Qualifikationszeitpunkt', 'en' => 'Qualification timestamp']],
                ['entity' => 'Campaign', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Campaign-Join', 'en' => 'Campaign join']],
                ['entity' => 'Campaign', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Campaign-Name', 'en' => 'Campaign name']],
                ['entity' => 'Campaign', 'name' => 'channel', 'role' => 'dimension', 'why' => ['de' => 'email / push / in-app', 'en' => 'email / push / in-app']],
                ['entity' => 'Consent', 'name' => 'profile_id', 'role' => 'key', 'why' => ['de' => 'Profile-Rückjoin', 'en' => 'Profile back-join']],
                ['entity' => 'Consent', 'name' => 'purpose', 'role' => 'dimension', 'why' => ['de' => 'marketing / personalization / analytics', 'en' => 'marketing / personalization / analytics']],
                ['entity' => 'Consent', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'opted-in / opted-out', 'en' => 'opted-in / opted-out']],
                ['entity' => 'Consent', 'name' => 'timestamp', 'role' => 'measure', 'why' => ['de' => 'Consent-Zeitpunkt', 'en' => 'Consent timestamp']],
                ['entity' => 'Dataset', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Dataset-Join', 'en' => 'Dataset join']],
                ['entity' => 'Dataset', 'name' => 'schemaRef.id', 'role' => 'dimension', 'why' => ['de' => 'XDM-Schema-Referenz', 'en' => 'XDM schema reference']],
                ['entity' => 'Journey', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Journey-Join', 'en' => 'Journey join']],
                ['entity' => 'Journey', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'draft / published / disabled', 'en' => 'draft / published / disabled']],
            ],
            'skipTables' => [
                [
                    'name' => 'Raw clickstream event payload bulk',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Rohes Event-Volumen — sampeln/aggregieren statt Full-History in RAW.',
                        'en' => 'Raw event volume — sample/aggregate instead of full history in RAW.',
                    ],
                ],
                [
                    'name' => 'Email / push creative content bodies',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Creative-Bodies (HTML/Assets) — nie Analytics-Warehouse; Meta reicht.',
                        'en' => 'Creative bodies (HTML/assets) — never in the analytics warehouse; meta suffices.',
                    ],
                ],
                [
                    'name' => 'Identity graph raw device/cookie exports',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Device-Graph-Rohdaten — hochauflösende PII, nicht für Default-Marts.',
                        'en' => 'Raw device-graph data — high-resolution PII, not for default marts.',
                    ],
                ],
                [
                    'name' => 'Profile attribute bulk export without consent filter',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Profile-Exports ohne Consent-Filter riskieren Verstoß gegen Consent-Purpose.',
                        'en' => 'Profile exports without a consent filter risk violating consent purpose.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Raw clickstream event bulk', 'reason' => ['de' => 'High volume — sample/aggregate', 'en' => 'High volume — sample/aggregate']],
                ['name' => 'Email/push creative content bodies', 'reason' => ['de' => 'Kein Default-Load von Creatives', 'en' => 'No default load of creatives']],
                ['name' => 'Identity graph raw device/cookie exports', 'reason' => ['de' => 'Hochauflösende PII — skip', 'en' => 'High-resolution PII — skip']],
                ['name' => 'Profile exports without consent filter', 'reason' => ['de' => 'Consent-Verstoß-Risiko', 'en' => 'Consent violation risk']],
            ],
            'dimensions' => [
                [
                    'id' => 'channel',
                    'label' => ['de' => 'Channel', 'en' => 'Channel'],
                    'grain' => ['de' => 'campaign.channel', 'en' => 'campaign.channel'],
                    'notes' => [
                        'de' => 'email/push/in-app für Reach- und Engagement-Splits.',
                        'en' => 'email/push/in-app for reach and engagement splits.',
                    ],
                ],
                [
                    'id' => 'segment',
                    'label' => ['de' => 'Segment', 'en' => 'Segment'],
                    'grain' => ['de' => 'segment.id / name', 'en' => 'segment.id / name'],
                    'notes' => [
                        'de' => 'Segment-Definitionen ändern sich — SCD2 empfohlen.',
                        'en' => 'Segment definitions change — SCD2 recommended.',
                    ],
                ],
                [
                    'id' => 'consent_status',
                    'label' => ['de' => 'Consent Status', 'en' => 'Consent status'],
                    'grain' => ['de' => 'consent.status je purpose', 'en' => 'consent.status per purpose'],
                    'notes' => [
                        'de' => 'Consent ist purpose-spezifisch — nicht auf einen globalen Flag reduzieren.',
                        'en' => 'Consent is purpose-specific — do not reduce to a single global flag.',
                    ],
                ],
                [
                    'id' => 'campaign_type',
                    'label' => ['de' => 'Campaign Type', 'en' => 'Campaign type'],
                    'grain' => ['de' => 'campaign.name / category', 'en' => 'campaign.name / category'],
                    'notes' => [
                        'de' => 'Trigger- vs. Batch-Campaigns getrennt auswerten.',
                        'en' => 'Evaluate trigger vs batch campaigns separately.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Profile',
                    'fields' => ['identityMap', 'person.emailAddress'],
                    'treatment' => [
                        'de' => 'Identity-Graph und E-Mail — PII; ECID/Profile-id als Join bevorzugen, E-Mail hashen.',
                        'en' => 'Identity graph and email — PII; prefer ECID/profile id as join, hash email.',
                    ],
                ],
                [
                    'entity' => 'ExperienceEvent',
                    'fields' => ['_id_profile', 'device.*'],
                    'treatment' => [
                        'de' => 'Event-zu-Profile-Link ist PII-Fläche — Aggregation vor Export bevorzugen.',
                        'en' => 'Event-to-profile link is a PII surface — prefer aggregation before export.',
                    ],
                ],
                [
                    'entity' => 'Consent',
                    'fields' => ['profile_id'],
                    'treatment' => [
                        'de' => 'Consent Records sind Pflicht-Governance-Daten — nie ohne Consent-Check nutzen.',
                        'en' => 'Consent records are mandatory governance data — never use without a consent check.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'profile id / ECID, email (hashed), segment id, campaign id.',
                        'en' => 'profile id / ECID, email (hashed), segment id, campaign id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Profile, Segment Membership, Campaign, Consent — keine Creative-Bodies.',
                        'en' => 'Profile, segment membership, campaign, consent — no creative bodies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'active-profiles',
                    'example' => true,
                    'label' => ['de' => 'Active Profiles', 'en' => 'Active profiles'],
                    'question' => [
                        'de' => 'Wie viele Profile hatten in der Periode mindestens ein Event?',
                        'en' => 'How many profiles had at least one event in the period?',
                    ],
                    'formula' => 'COUNT(DISTINCT _id_profile) FROM experience_event WHERE timestamp IN period',
                    'grain' => ['de' => 'Active Profile', 'en' => 'Active profile'],
                    'dimensions' => ['channel', 'consent_status'],
                    'fieldsUsed' => ['ExperienceEvent._id_profile', 'ExperienceEvent.timestamp'],
                    'sourceHints' => [
                        'de' => 'Nur Profile mit gültigem Consent für die entsprechende Nutzung zählen.',
                        'en' => 'Only count profiles with valid consent for the relevant use.',
                    ],
                    'adapt' => [
                        'de' => 'Anonyme vs. bekannte Profile (mit Identity) trennen.',
                        'en' => 'Separate anonymous vs known profiles (with identity).',
                    ],
                ],
                [
                    'id' => 'events-count',
                    'example' => true,
                    'label' => ['de' => 'Events Count', 'en' => 'Events count'],
                    'question' => [
                        'de' => 'Wie viele Experience Events gab es in der Periode?',
                        'en' => 'How many experience events occurred in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM experience_event WHERE timestamp IN period',
                    'grain' => ['de' => 'Experience Event', 'en' => 'Experience event'],
                    'dimensions' => ['channel'],
                    'fieldsUsed' => ['ExperienceEvent.id', 'ExperienceEvent.eventType', 'ExperienceEvent.timestamp'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Aggregat-Tabellen (Tages-Rollups) nutzen.',
                        'en' => 'Use aggregate tables (daily rollups) at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Nach eventType (pageView, purchase, emailOpen) segmentieren.',
                        'en' => 'Segment by eventType (pageView, purchase, emailOpen).',
                    ],
                ],
                [
                    'id' => 'segment-membership-count',
                    'example' => false,
                    'label' => ['de' => 'Segment Membership Count', 'en' => 'Segment membership count'],
                    'question' => [
                        'de' => 'Wie viele Profile qualifizieren sich aktuell für ein Segment?',
                        'en' => 'How many profiles currently qualify for a segment?',
                    ],
                    'formula' => 'COUNT(DISTINCT profile_id) FROM segment_membership WHERE segment_id = :segment AND status = \'realized\'',
                    'grain' => ['de' => 'Qualifiziertes Profile je Segment', 'en' => 'Qualified profile per segment'],
                    'dimensions' => ['segment', 'consent_status'],
                    'fieldsUsed' => ['SegmentMembership.profile_id', 'SegmentMembership.segment_id', 'SegmentMembership.qualified_at'],
                    'sourceHints' => [
                        'de' => 'Segment-Snapshot vs. Streaming-Qualification je Segment-Typ beachten.',
                        'en' => 'Consider segment snapshot vs streaming qualification per segment type.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Profile mit Marketing-Consent für aktivierbare Segmentgröße.',
                        'en' => 'Only profiles with marketing consent for the activatable segment size.',
                    ],
                ],
                [
                    'id' => 'consented-profiles-rate',
                    'example' => false,
                    'label' => ['de' => 'Consented Profiles Rate', 'en' => 'Consented profiles rate'],
                    'question' => [
                        'de' => 'Welcher Anteil der Profile hat Marketing-Consent erteilt?',
                        'en' => 'What share of profiles has granted marketing consent?',
                    ],
                    'formula' => "COUNT(*) FILTER (WHERE status = 'opted-in' AND purpose = 'marketing') / COUNT(DISTINCT profile_id) FROM consent",
                    'grain' => ['de' => 'Consent Rate (aggregiert)', 'en' => 'Consent rate (aggregated)'],
                    'dimensions' => ['consent_status', 'channel'],
                    'fieldsUsed' => ['Consent.profile_id', 'Consent.purpose', 'Consent.status'],
                    'sourceHints' => [
                        'de' => 'Purpose-Filter (marketing) nicht mit anderen Consent-Zwecken vermischen.',
                        'en' => 'Do not mix the purpose filter (marketing) with other consent purposes.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Region/Rechtsraum (GDPR vs. CCPA) aufschlüsseln.',
                        'en' => 'Break down by region/jurisdiction (GDPR vs CCPA).',
                    ],
                ],
                [
                    'id' => 'campaign-sends',
                    'example' => false,
                    'label' => ['de' => 'Campaign Sends', 'en' => 'Campaign sends'],
                    'question' => [
                        'de' => 'Wie viele Campaign-Sends gab es in der Periode?',
                        'en' => 'How many campaign sends occurred in the period?',
                    ],
                    'formula' => "COUNT(*) FROM experience_event WHERE eventType = 'directMarketing.emailSent' AND timestamp IN period",
                    'grain' => ['de' => 'Sent Message Event', 'en' => 'Sent message event'],
                    'dimensions' => ['channel', 'campaign_type'],
                    'fieldsUsed' => ['ExperienceEvent.eventType', 'ExperienceEvent.timestamp', 'Campaign.id'],
                    'sourceHints' => [
                        'de' => 'Nur Sends an consented Profile zählen — Compliance-Filter vorschalten.',
                        'en' => 'Only count sends to consented profiles — apply a compliance filter first.',
                    ],
                    'adapt' => [
                        'de' => 'Delivered vs. Sent unterscheiden, wenn Bounce-Events verfügbar sind.',
                        'en' => 'Distinguish delivered vs sent when bounce events are available.',
                    ],
                ],
            ],
            'tools' => $wave10Tools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
