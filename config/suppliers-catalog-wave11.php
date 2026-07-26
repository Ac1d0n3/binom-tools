<?php

/**
 * Wave 11 supplier library entries — Service / BPM / Healthcare (full template depth).
 *
 * Emphasize metadata facts; do not load conversation/case-attachment bodies, process variable
 * payloads with business data, or clinical notes/bodies by default. Epic (healthcare) requires
 * strict PHI skip/PII policy — metadata aggregates only.
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $wave11Tools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    return [
        [
            'id' => 'freshdesk',
            'domain' => 'service',
            'order' => 420,
            'label' => ['de' => 'Freshdesk', 'en' => 'Freshdesk'],
            'shortPurpose' => [
                'de' => 'Customer Support: Ticket/Contact/Agent-Meta, SLA — REST-Load; keine Conversation-Bodies oder Attachments.',
                'en' => 'Customer support: ticket/contact/agent meta, SLA — REST load; no conversation bodies or attachments.',
            ],
            'entities' => [
                [
                    'id' => 'contact',
                    'label' => ['de' => 'Contact', 'en' => 'Contact'],
                    'description' => [
                        'de' => 'Requester/Contact — email, phone, name; Kunden-PII.',
                        'en' => 'Requester/contact — email, phone, name; customer PII.',
                    ],
                    'grain' => ['de' => 'Ein Contact (id)', 'en' => 'One contact (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'company',
                    'label' => ['de' => 'Company', 'en' => 'Company'],
                    'description' => [
                        'de' => 'Company/Account — name, domains; B2B-Dimension.',
                        'en' => 'Company/account — name, domains; B2B dimension.',
                    ],
                    'grain' => ['de' => 'Eine Company (id)', 'en' => 'One company (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'ticket',
                    'label' => ['de' => 'Ticket', 'en' => 'Ticket'],
                    'description' => [
                        'de' => 'Support Ticket — status, priority, type, SLA-Timestamps; Fact-Anker.',
                        'en' => 'Support ticket — status, priority, type, SLA timestamps; fact anchor.',
                    ],
                    'grain' => ['de' => 'Ein Ticket (id)', 'en' => 'One ticket (id)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'conversation',
                    'label' => ['de' => 'Conversation', 'en' => 'Conversation'],
                    'description' => [
                        'de' => 'Reply/Note auf einem Ticket — body Text; nur Meta, kein Body default.',
                        'en' => 'Reply/note on a ticket — body text; meta only, no body by default.',
                    ],
                    'grain' => ['de' => 'Eine Conversation (id)', 'en' => 'One conversation (id)'],
                    'role' => ['de' => 'Meta-Fact (kein Body)', 'en' => 'Meta fact (no body)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'agent',
                    'label' => ['de' => 'Agent', 'en' => 'Agent'],
                    'description' => [
                        'de' => 'Support Agent — email, name; Workforce-PII.',
                        'en' => 'Support agent — email, name; workforce PII.',
                    ],
                    'grain' => ['de' => 'Ein Agent (id)', 'en' => 'One agent (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'group',
                    'label' => ['de' => 'Group', 'en' => 'Group'],
                    'description' => [
                        'de' => 'Agent Group — Team-Zuordnung für Routing/Reporting.',
                        'en' => 'Agent group — team assignment for routing/reporting.',
                    ],
                    'grain' => ['de' => 'Eine Group (id)', 'en' => 'One group (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'sla_policy',
                    'label' => ['de' => 'SLA Policy', 'en' => 'SLA policy'],
                    'description' => [
                        'de' => 'SLA Policy — Targets für Response/Resolution je Priorität.',
                        'en' => 'SLA policy — targets for response/resolution per priority.',
                    ],
                    'grain' => ['de' => 'Eine SLA Policy (id)', 'en' => 'One SLA policy (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'time_entry',
                    'label' => ['de' => 'Time Entry', 'en' => 'Time entry'],
                    'description' => [
                        'de' => 'Timesheet Entry auf Ticket — timeSpent, billable-Flag.',
                        'en' => 'Timesheet entry on a ticket — timeSpent, billable flag.',
                    ],
                    'grain' => ['de' => 'Ein Time Entry (id)', 'en' => 'One time entry (id)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Contact', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Contact-Join', 'en' => 'Contact join']],
                ['entity' => 'Contact', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'Contact', 'name' => 'phone', 'role' => 'pii', 'why' => ['de' => 'Telefon / PII', 'en' => 'Phone / PII']],
                ['entity' => 'Contact', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'Contact', 'name' => 'company_id', 'role' => 'dimension', 'why' => ['de' => 'Company-Rückjoin', 'en' => 'Company back-join']],
                ['entity' => 'Company', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Company-Join', 'en' => 'Company join']],
                ['entity' => 'Company', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Firmenname', 'en' => 'Company name']],
                ['entity' => 'Ticket', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Ticket-Join', 'en' => 'Ticket join']],
                ['entity' => 'Ticket', 'name' => 'subject', 'role' => 'pii', 'why' => ['de' => 'Betreff kann PII enthalten', 'en' => 'Subject may contain PII']],
                ['entity' => 'Ticket', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'open / pending / resolved / closed', 'en' => 'open / pending / resolved / closed']],
                ['entity' => 'Ticket', 'name' => 'priority', 'role' => 'dimension', 'why' => ['de' => 'low / medium / high / urgent', 'en' => 'low / medium / high / urgent']],
                ['entity' => 'Ticket', 'name' => 'type', 'role' => 'dimension', 'why' => ['de' => 'Question / Incident / Problem …', 'en' => 'Question / Incident / Problem …']],
                ['entity' => 'Ticket', 'name' => 'requester_id', 'role' => 'dimension', 'why' => ['de' => 'Contact-Rückjoin', 'en' => 'Contact back-join']],
                ['entity' => 'Ticket', 'name' => 'responder_id', 'role' => 'dimension', 'why' => ['de' => 'Agent-Rückjoin', 'en' => 'Agent back-join']],
                ['entity' => 'Ticket', 'name' => 'group_id', 'role' => 'dimension', 'why' => ['de' => 'Group-Rückjoin', 'en' => 'Group back-join']],
                ['entity' => 'Ticket', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Ticket-Erstellung', 'en' => 'Ticket created']],
                ['entity' => 'Ticket', 'name' => 'fr_escalated', 'role' => 'dimension', 'why' => ['de' => 'First-Response-SLA verletzt?', 'en' => 'First-response SLA breached?']],
                ['entity' => 'Ticket', 'name' => 'resolved_at', 'role' => 'measure', 'why' => ['de' => 'Resolution-Zeitpunkt', 'en' => 'Resolution timestamp']],
                ['entity' => 'Conversation', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Conversation-Join (Meta)', 'en' => 'Conversation join (meta)']],
                ['entity' => 'Conversation', 'name' => 'body_text', 'role' => 'pii', 'why' => ['de' => 'Freitext / PII-Risiko', 'en' => 'Free text / PII risk']],
                ['entity' => 'Conversation', 'name' => 'ticket_id', 'role' => 'dimension', 'why' => ['de' => 'Ticket-Rückjoin', 'en' => 'Ticket back-join']],
                ['entity' => 'Conversation', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Reply-Zeitpunkt', 'en' => 'Reply timestamp']],
                ['entity' => 'Agent', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Agent-Join', 'en' => 'Agent join']],
                ['entity' => 'Agent', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'Group', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Group-Join', 'en' => 'Group join']],
                ['entity' => 'Group', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Team-Name', 'en' => 'Team name']],
                ['entity' => 'SLAPolicy', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'SLA-Join', 'en' => 'SLA join']],
                ['entity' => 'SLAPolicy', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'SLA-Name', 'en' => 'SLA name']],
                ['entity' => 'TimeEntry', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Time-Entry-Join', 'en' => 'Time entry join']],
                ['entity' => 'TimeEntry', 'name' => 'time_spent', 'role' => 'measure', 'why' => ['de' => 'Aufgewendete Zeit', 'en' => 'Time spent']],
            ],
            'skipTables' => [
                [
                    'name' => 'Conversation / note body text',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Freitext-Support-Inhalte — nie default; Meta (Count/Timestamp) reicht.',
                        'en' => 'Free-text support content — never by default; meta (count/timestamp) suffices.',
                    ],
                ],
                [
                    'name' => 'Ticket / conversation attachment files',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Attachment-Binaries — nie laden; nur Presence/Count.',
                        'en' => 'Attachment binaries — never load; presence/count only.',
                    ],
                ],
                [
                    'name' => 'Call recording transcripts',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Voice-Transkripte — hochsensible PII, nicht für Analytics-Warehouse.',
                        'en' => 'Voice transcripts — highly sensitive PII, not for the analytics warehouse.',
                    ],
                ],
                [
                    'name' => 'Satisfaction survey free-text comments',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'CSAT-Freitext kann PII/Sentiment enthalten — Score-Meta reicht.',
                        'en' => 'CSAT free text can contain PII/sentiment — score meta suffices.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Conversation/note body text', 'reason' => ['de' => 'PII-Risiko — Meta reicht', 'en' => 'PII risk — meta suffices']],
                ['name' => 'Ticket/conversation attachments', 'reason' => ['de' => 'Kein Default-Load von Binaries', 'en' => 'No default load of binaries']],
                ['name' => 'Call recording transcripts', 'reason' => ['de' => 'Hochsensible PII — skip', 'en' => 'Highly sensitive PII — skip']],
                ['name' => 'CSAT free-text comments', 'reason' => ['de' => 'PII-Risiko — Score-Meta reicht', 'en' => 'PII risk — score meta suffices']],
            ],
            'dimensions' => [
                [
                    'id' => 'status',
                    'label' => ['de' => 'Status', 'en' => 'Status'],
                    'grain' => ['de' => 'ticket.status', 'en' => 'ticket.status'],
                    'notes' => [
                        'de' => 'Open/Pending vs. Resolved/Closed für Backlog-KPIs trennen.',
                        'en' => 'Separate open/pending vs resolved/closed for backlog KPIs.',
                    ],
                ],
                [
                    'id' => 'priority',
                    'label' => ['de' => 'Priority', 'en' => 'Priority'],
                    'grain' => ['de' => 'ticket.priority', 'en' => 'ticket.priority'],
                    'notes' => [
                        'de' => 'Priority-SLA-Targets über sla_policy verknüpfen.',
                        'en' => 'Link priority SLA targets via sla_policy.',
                    ],
                ],
                [
                    'id' => 'group',
                    'label' => ['de' => 'Group', 'en' => 'Group'],
                    'grain' => ['de' => 'group.id / name', 'en' => 'group.id / name'],
                    'notes' => [
                        'de' => 'Team-Level-Rollups für Workload und SLA-Compliance.',
                        'en' => 'Team-level rollups for workload and SLA compliance.',
                    ],
                ],
                [
                    'id' => 'sla_policy',
                    'label' => ['de' => 'SLA Policy', 'en' => 'SLA policy'],
                    'grain' => ['de' => 'sla_policy.id / name', 'en' => 'sla_policy.id / name'],
                    'notes' => [
                        'de' => 'SLA-Policy-Changes versionieren für historische Vergleichbarkeit.',
                        'en' => 'Version SLA policy changes for historical comparability.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Contact',
                    'fields' => ['email', 'phone', 'name'],
                    'treatment' => [
                        'de' => 'Kunden-E-Mail/Telefon/Name — PII; contact id als Join bevorzugen.',
                        'en' => 'Customer email/phone/name — PII; prefer contact id as join.',
                    ],
                ],
                [
                    'entity' => 'Agent',
                    'fields' => ['email'],
                    'treatment' => [
                        'de' => 'Agent-E-Mail — Workforce-PII; agent id für Ownership-KPIs bevorzugen.',
                        'en' => 'Agent email — workforce PII; prefer agent id for ownership KPIs.',
                    ],
                ],
                [
                    'entity' => 'Conversation',
                    'fields' => ['body_text'],
                    'treatment' => [
                        'de' => 'Conversation-Body ist Freitext und kann PII enthalten — Default drop, nur Meta.',
                        'en' => 'Conversation body is free text and may contain PII — default drop, meta only.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'contact id/email (hashed), ticket id, company id, agent id.',
                        'en' => 'contact id/email (hashed), ticket id, company id, agent id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Contact, Company, Ticket, Agent, SLA Policy — keine Conversation-Bodies.',
                        'en' => 'Contact, company, ticket, agent, SLA policy — no conversation bodies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'tickets-created',
                    'example' => true,
                    'label' => ['de' => 'Tickets Created', 'en' => 'Tickets created'],
                    'question' => [
                        'de' => 'Wie viele Tickets wurden in der Periode erstellt?',
                        'en' => 'How many tickets were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM ticket WHERE created_at IN period',
                    'grain' => ['de' => 'Created Ticket', 'en' => 'Created ticket'],
                    'dimensions' => ['status', 'priority', 'group'],
                    'fieldsUsed' => ['Ticket.id', 'Ticket.created_at', 'Ticket.group_id'],
                    'sourceHints' => [
                        'de' => 'Spam/Deleted Tickets ausschließen.',
                        'en' => 'Exclude spam/deleted tickets.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Channel (email/portal/phone/chat) segmentieren.',
                        'en' => 'Segment by channel (email/portal/phone/chat).',
                    ],
                ],
                [
                    'id' => 'tickets-resolved',
                    'example' => true,
                    'label' => ['de' => 'Tickets Resolved', 'en' => 'Tickets resolved'],
                    'question' => [
                        'de' => 'Wie viele Tickets wurden in der Periode gelöst?',
                        'en' => 'How many tickets were resolved in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM ticket WHERE resolved_at IN period',
                    'grain' => ['de' => 'Resolved Ticket', 'en' => 'Resolved ticket'],
                    'dimensions' => ['status', 'priority', 'group'],
                    'fieldsUsed' => ['Ticket.resolved_at', 'Ticket.status', 'Ticket.group_id'],
                    'sourceHints' => [
                        'de' => 'Resolved vs. Closed unterscheiden — Reopen-Fälle beachten.',
                        'en' => 'Distinguish resolved vs closed — account for reopened cases.',
                    ],
                    'adapt' => [
                        'de' => 'Reopened Tickets separat aus Resolution-Count ausschließen.',
                        'en' => 'Exclude reopened tickets separately from the resolution count.',
                    ],
                ],
                [
                    'id' => 'first-response-time-avg',
                    'example' => false,
                    'label' => ['de' => 'Average First Response Time', 'en' => 'Average first response time'],
                    'question' => [
                        'de' => 'Wie lang ist die durchschnittliche First-Response-Zeit?',
                        'en' => 'What is the average first-response time?',
                    ],
                    'formula' => 'AVG(first_response_at - created_at) FROM ticket WHERE created_at IN period',
                    'grain' => ['de' => 'Ticket (aggregiert)', 'en' => 'Ticket (aggregated)'],
                    'dimensions' => ['priority', 'group', 'sla_policy'],
                    'fieldsUsed' => ['Ticket.created_at', 'Ticket.fr_escalated', 'Ticket.group_id'],
                    'sourceHints' => [
                        'de' => 'Business Hours vs. Calendar Hours in Definition klären.',
                        'en' => 'Clarify business hours vs calendar hours in the definition.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Priority getrennt ausweisen für SLA-Reporting.',
                        'en' => 'Break out by priority for SLA reporting.',
                    ],
                ],
                [
                    'id' => 'csat-score',
                    'example' => false,
                    'label' => ['de' => 'CSAT Score', 'en' => 'CSAT score'],
                    'question' => [
                        'de' => 'Wie hoch ist der durchschnittliche CSAT Score in der Periode?',
                        'en' => 'What is the average CSAT score in the period?',
                    ],
                    'formula' => 'AVG(rating) FROM satisfaction_rating WHERE created_at IN period',
                    'grain' => ['de' => 'Rating (aggregiert)', 'en' => 'Rating (aggregated)'],
                    'dimensions' => ['group', 'priority'],
                    'fieldsUsed' => ['Ticket.id', 'Ticket.group_id'],
                    'sourceHints' => [
                        'de' => 'Nur numerischen Score verwenden; Freitext-Feedback nicht laden.',
                        'en' => 'Use only the numeric score; do not load free-text feedback.',
                    ],
                    'adapt' => [
                        'de' => 'Response-Rate der Survey separat als Coverage-KPI ausweisen.',
                        'en' => 'Report the survey response rate separately as a coverage KPI.',
                    ],
                ],
                [
                    'id' => 'sla-breaches',
                    'example' => false,
                    'label' => ['de' => 'SLA Breaches', 'en' => 'SLA breaches'],
                    'question' => [
                        'de' => 'Wie viele Tickets haben in der Periode eine SLA verletzt?',
                        'en' => 'How many tickets breached an SLA in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM ticket WHERE fr_escalated = true AND created_at IN period',
                    'grain' => ['de' => 'SLA-verletztes Ticket', 'en' => 'SLA-breached ticket'],
                    'dimensions' => ['priority', 'group', 'sla_policy'],
                    'fieldsUsed' => ['Ticket.fr_escalated', 'Ticket.created_at', 'Ticket.group_id'],
                    'sourceHints' => [
                        'de' => 'First-Response- vs. Resolution-SLA-Breach getrennt tracken.',
                        'en' => 'Track first-response vs resolution SLA breach separately.',
                    ],
                    'adapt' => [
                        'de' => 'Breach-Rate = breaches / tickets je Priority.',
                        'en' => 'Breach rate = breaches / tickets per priority.',
                    ],
                ],
            ],
            'tools' => $wave11Tools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'pega',
            'domain' => 'bpm',
            'order' => 430,
            'label' => ['de' => 'Pega', 'en' => 'Pega'],
            'shortPurpose' => [
                'de' => 'Case Management & BPM: Case/Assignment/Operator-Meta — Case API-Load; keine Attachment- oder Correspondence-Bodies.',
                'en' => 'Case management & BPM: case/assignment/operator meta — Case API load; no attachment or correspondence bodies.',
            ],
            'entities' => [
                [
                    'id' => 'case',
                    'label' => ['de' => 'Case', 'en' => 'Case'],
                    'description' => [
                        'de' => 'Pega Case (pyID) — Status, Case Type, Urgency; Fact-Anker.',
                        'en' => 'Pega case (pyID) — status, case type, urgency; fact anchor.',
                    ],
                    'grain' => ['de' => 'Ein Case (pyID / handle)', 'en' => 'One case (pyID / handle)'],
                    'role' => ['de' => 'Fact / Anker', 'en' => 'Fact / anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'case_type',
                    'label' => ['de' => 'Case Type', 'en' => 'Case type'],
                    'description' => [
                        'de' => 'Case Type (pyClass) — Definition/Kategorie der Cases.',
                        'en' => 'Case type (pyClass) — definition/category of cases.',
                    ],
                    'grain' => ['de' => 'Ein Case Type (className)', 'en' => 'One case type (className)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'assignment',
                    'label' => ['de' => 'Assignment', 'en' => 'Assignment'],
                    'description' => [
                        'de' => 'Work Assignment — pxTaskLabel, assignee, deadline; Fact.',
                        'en' => 'Work assignment — pxTaskLabel, assignee, deadline; fact.',
                    ],
                    'grain' => ['de' => 'Ein Assignment (ID)', 'en' => 'One assignment (ID)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'operator',
                    'label' => ['de' => 'Operator', 'en' => 'Operator'],
                    'description' => [
                        'de' => 'Operator (pyUserName) — email, org unit; Workforce-PII.',
                        'en' => 'Operator (pyUserName) — email, org unit; workforce PII.',
                    ],
                    'grain' => ['de' => 'Ein Operator (pzInsKey)', 'en' => 'One operator (pzInsKey)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'stage',
                    'label' => ['de' => 'Stage', 'en' => 'Stage'],
                    'description' => [
                        'de' => 'Case Stage — Name, Sequenz; Flow-Dimension.',
                        'en' => 'Case stage — name, sequence; flow dimension.',
                    ],
                    'grain' => ['de' => 'Eine Stage (id @ case type)', 'en' => 'One stage (id @ case type)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'work_party',
                    'label' => ['de' => 'Work Party', 'en' => 'Work party'],
                    'description' => [
                        'de' => 'Work Party (Customer/Requestor) — Rolle am Case; kann PII sein.',
                        'en' => 'Work party (customer/requestor) — role on the case; may be PII.',
                    ],
                    'grain' => ['de' => 'Eine Work Party (case_id, role)', 'en' => 'One work party (case_id, role)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'sla_event',
                    'label' => ['de' => 'SLA Event', 'en' => 'SLA event'],
                    'description' => [
                        'de' => 'Service Level Event — goal/deadline passed; Compliance-Fact.',
                        'en' => 'Service level event — goal/deadline passed; compliance fact.',
                    ],
                    'grain' => ['de' => 'Ein SLA Event (id)', 'en' => 'One SLA event (id)'],
                    'role' => ['de' => 'Governance-Fact', 'en' => 'Governance fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'attachment_meta',
                    'label' => ['de' => 'Attachment Meta', 'en' => 'Attachment meta'],
                    'description' => [
                        'de' => 'Case Attachment Meta — Typ, Timestamp; Content nicht laden.',
                        'en' => 'Case attachment meta — type, timestamp; do not load content.',
                    ],
                    'grain' => ['de' => 'Ein Attachment (id) Meta', 'en' => 'One attachment (id) meta'],
                    'role' => ['de' => 'Meta-Fact', 'en' => 'Meta fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Case', 'name' => 'pyID', 'role' => 'key', 'why' => ['de' => 'Case-Join (Business-ID)', 'en' => 'Case join (business id)']],
                ['entity' => 'Case', 'name' => 'pzInsKey', 'role' => 'key', 'why' => ['de' => 'Case-Join (Instance-Key)', 'en' => 'Case join (instance key)']],
                ['entity' => 'Case', 'name' => 'pyStatusWork', 'role' => 'dimension', 'why' => ['de' => 'Case-Status (Open-Pending, Resolved-Completed …)', 'en' => 'Case status (Open-Pending, Resolved-Completed …)']],
                ['entity' => 'Case', 'name' => 'pyClass', 'role' => 'dimension', 'why' => ['de' => 'Case-Type-Rückjoin', 'en' => 'Case type back-join']],
                ['entity' => 'Case', 'name' => 'pyUrgency', 'role' => 'dimension', 'why' => ['de' => 'Priorisierungs-Score', 'en' => 'Prioritization score']],
                ['entity' => 'Case', 'name' => 'pxCreateDateTime', 'role' => 'measure', 'why' => ['de' => 'Case-Erstellung', 'en' => 'Case created']],
                ['entity' => 'Case', 'name' => 'pxResolvedTimestamp', 'role' => 'measure', 'why' => ['de' => 'Resolution-Zeitpunkt', 'en' => 'Resolution timestamp']],
                ['entity' => 'CaseType', 'name' => 'className', 'role' => 'key', 'why' => ['de' => 'Case-Type-Join', 'en' => 'Case type join']],
                ['entity' => 'CaseType', 'name' => 'label', 'role' => 'dimension', 'why' => ['de' => 'Case-Type-Bezeichnung', 'en' => 'Case type label']],
                ['entity' => 'Assignment', 'name' => 'ID', 'role' => 'key', 'why' => ['de' => 'Assignment-Join', 'en' => 'Assignment join']],
                ['entity' => 'Assignment', 'name' => 'pyCaseID', 'role' => 'dimension', 'why' => ['de' => 'Case-Rückjoin', 'en' => 'Case back-join']],
                ['entity' => 'Assignment', 'name' => 'pxTaskLabel', 'role' => 'dimension', 'why' => ['de' => 'Assignment-Bezeichnung', 'en' => 'Assignment label']],
                ['entity' => 'Assignment', 'name' => 'urgency', 'role' => 'dimension', 'why' => ['de' => 'Assignment-Urgency', 'en' => 'Assignment urgency']],
                ['entity' => 'Assignment', 'name' => 'pyAssignedOperatorID', 'role' => 'dimension', 'why' => ['de' => 'Operator-Rückjoin', 'en' => 'Operator back-join']],
                ['entity' => 'Assignment', 'name' => 'urgentIn', 'role' => 'measure', 'why' => ['de' => 'Zeit bis Deadline', 'en' => 'Time to deadline']],
                ['entity' => 'Operator', 'name' => 'pyUserIdentifier', 'role' => 'key', 'why' => ['de' => 'Operator-Join', 'en' => 'Operator join']],
                ['entity' => 'Operator', 'name' => 'pyEmailAddress', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'Operator', 'name' => 'pyUserName', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'Operator', 'name' => 'pyOrgUnit', 'role' => 'dimension', 'why' => ['de' => 'Org-Unit-Dim', 'en' => 'Org unit dim']],
                ['entity' => 'Stage', 'name' => 'pyStageID', 'role' => 'key', 'why' => ['de' => 'Stage-Join', 'en' => 'Stage join']],
                ['entity' => 'Stage', 'name' => 'pyStageLabel', 'role' => 'dimension', 'why' => ['de' => 'Stage-Bezeichnung', 'en' => 'Stage label']],
                ['entity' => 'WorkParty', 'name' => 'case_id', 'role' => 'key', 'why' => ['de' => 'Case-Rückjoin', 'en' => 'Case back-join']],
                ['entity' => 'WorkParty', 'name' => 'role', 'role' => 'dimension', 'why' => ['de' => 'Customer / Requestor / Approver', 'en' => 'Customer / requestor / approver']],
                ['entity' => 'WorkParty', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Kontakt-E-Mail / PII', 'en' => 'Contact email / PII']],
                ['entity' => 'SLAEvent', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'SLA-Event-Join', 'en' => 'SLA event join']],
                ['entity' => 'SLAEvent', 'name' => 'goalPassed', 'role' => 'dimension', 'why' => ['de' => 'Goal-Deadline verletzt?', 'en' => 'Goal deadline breached?']],
                ['entity' => 'SLAEvent', 'name' => 'deadlinePassed', 'role' => 'dimension', 'why' => ['de' => 'Deadline verletzt?', 'en' => 'Deadline breached?']],
                ['entity' => 'AttachmentMeta', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Attachment-Join (Meta)', 'en' => 'Attachment join (meta)']],
                ['entity' => 'AttachmentMeta', 'name' => 'category', 'role' => 'dimension', 'why' => ['de' => 'File / URL / Correspondence', 'en' => 'File / URL / Correspondence']],
            ],
            'skipTables' => [
                [
                    'name' => 'Case attachment content / documents',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Attachment-Binaries — nie default laden; nur Meta (Typ/Timestamp).',
                        'en' => 'Attachment binaries — never load by default; meta only (type/timestamp).',
                    ],
                ],
                [
                    'name' => 'Correspondence / email body content',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Correspondence-Bodies können PII enthalten — Send-Count reicht.',
                        'en' => 'Correspondence bodies can contain PII — send count suffices.',
                    ],
                ],
                [
                    'name' => 'Work party free-text notes',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Freitext-Notizen zu Work Parties — PII-Risiko, nicht Analytics-relevant.',
                        'en' => 'Free-text notes on work parties — PII risk, not analytics relevant.',
                    ],
                ],
                [
                    'name' => 'Full audit history payload bulk',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'pxAuditHistory Full-Payload — hohes Volumen; auf relevante Events reduzieren.',
                        'en' => 'pxAuditHistory full payload — high volume; reduce to relevant events.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Case attachment content/documents', 'reason' => ['de' => 'Kein Default-Load von Binaries', 'en' => 'No default load of binaries']],
                ['name' => 'Correspondence/email body content', 'reason' => ['de' => 'PII-Risiko — Count reicht', 'en' => 'PII risk — count suffices']],
                ['name' => 'Work party free-text notes', 'reason' => ['de' => 'PII-Risiko — nicht relevant', 'en' => 'PII risk — not relevant']],
                ['name' => 'Full audit history payload bulk', 'reason' => ['de' => 'Volumen — auf Events reduzieren', 'en' => 'Volume — reduce to events']],
            ],
            'dimensions' => [
                [
                    'id' => 'case_type',
                    'label' => ['de' => 'Case Type', 'en' => 'Case type'],
                    'grain' => ['de' => 'case.pyClass', 'en' => 'case.pyClass'],
                    'notes' => [
                        'de' => 'Primärer Slice für Case-Volume und Cycle-Time.',
                        'en' => 'Primary slice for case volume and cycle time.',
                    ],
                ],
                [
                    'id' => 'stage',
                    'label' => ['de' => 'Stage', 'en' => 'Stage'],
                    'grain' => ['de' => 'stage.pyStageLabel', 'en' => 'stage.pyStageLabel'],
                    'notes' => [
                        'de' => 'Stage-Namen sind Case-Type-spezifisch — vor Rollup mappen.',
                        'en' => 'Stage names are case-type-specific — map before rollup.',
                    ],
                ],
                [
                    'id' => 'urgency',
                    'label' => ['de' => 'Urgency', 'en' => 'Urgency'],
                    'grain' => ['de' => 'case.pyUrgency / assignment.urgency', 'en' => 'case.pyUrgency / assignment.urgency'],
                    'notes' => [
                        'de' => 'Case- vs. Assignment-Urgency nicht vermischen.',
                        'en' => 'Do not mix case vs assignment urgency.',
                    ],
                ],
                [
                    'id' => 'operator',
                    'label' => ['de' => 'Operator / Team', 'en' => 'Operator / team'],
                    'grain' => ['de' => 'operator.pyOrgUnit', 'en' => 'operator.pyOrgUnit'],
                    'notes' => [
                        'de' => 'Org-Unit für Team-Level-Workload-Rollups.',
                        'en' => 'Org unit for team-level workload rollups.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Operator',
                    'fields' => ['pyEmailAddress', 'pyUserName'],
                    'treatment' => [
                        'de' => 'Operator-E-Mail/Name — Workforce-PII; pyUserIdentifier als Join bevorzugen.',
                        'en' => 'Operator email/name — workforce PII; prefer pyUserIdentifier as join.',
                    ],
                ],
                [
                    'entity' => 'WorkParty',
                    'fields' => ['email'],
                    'treatment' => [
                        'de' => 'Work-Party-Kontaktdaten sind oft Kunden-PII — restriktiv behandeln.',
                        'en' => 'Work party contact data is often customer PII — treat restrictively.',
                    ],
                ],
                [
                    'entity' => 'Case',
                    'fields' => ['pyLabel'],
                    'treatment' => [
                        'de' => 'Case-Label/Description kann PII enthalten — Free-Text vor Export prüfen.',
                        'en' => 'Case label/description may contain PII — review free text before export.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'case pyID/pzInsKey, operator pyUserIdentifier, assignment ID.',
                        'en' => 'case pyID/pzInsKey, operator pyUserIdentifier, assignment ID.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Case, Assignment, Operator, Stage, SLA Event — keine Attachment-Bodies.',
                        'en' => 'Case, assignment, operator, stage, SLA event — no attachment bodies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'cases-created',
                    'example' => true,
                    'label' => ['de' => 'Cases Created', 'en' => 'Cases created'],
                    'question' => [
                        'de' => 'Wie viele Cases wurden in der Periode erstellt?',
                        'en' => 'How many cases were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM case WHERE pxCreateDateTime IN period',
                    'grain' => ['de' => 'Created Case', 'en' => 'Created case'],
                    'dimensions' => ['case_type', 'operator'],
                    'fieldsUsed' => ['Case.pyID', 'Case.pxCreateDateTime', 'Case.pyClass'],
                    'sourceHints' => [
                        'de' => 'Sub-Cases separat von Top-Level-Cases zählen.',
                        'en' => 'Count sub-cases separately from top-level cases.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Channel (Portal/API/Manual Create) segmentieren.',
                        'en' => 'Segment by channel (portal/API/manual create).',
                    ],
                ],
                [
                    'id' => 'cases-resolved',
                    'example' => true,
                    'label' => ['de' => 'Cases Resolved', 'en' => 'Cases resolved'],
                    'question' => [
                        'de' => 'Wie viele Cases wurden in der Periode abgeschlossen?',
                        'en' => 'How many cases were resolved in the period?',
                    ],
                    'formula' => "COUNT(*) FROM case WHERE pyStatusWork LIKE 'Resolved-%' AND pxResolvedTimestamp IN period",
                    'grain' => ['de' => 'Resolved Case', 'en' => 'Resolved case'],
                    'dimensions' => ['case_type', 'operator'],
                    'fieldsUsed' => ['Case.pyStatusWork', 'Case.pxResolvedTimestamp', 'Case.pyClass'],
                    'sourceHints' => [
                        'de' => 'Resolved-Completed vs. Resolved-Rejected getrennt behandeln.',
                        'en' => 'Handle resolved-completed vs resolved-rejected separately.',
                    ],
                    'adapt' => [
                        'de' => 'Rejected/Withdrawn Cases aus positivem Resolution-Count ausschließen.',
                        'en' => 'Exclude rejected/withdrawn cases from a positive resolution count.',
                    ],
                ],
                [
                    'id' => 'average-cycle-time',
                    'example' => false,
                    'label' => ['de' => 'Average Cycle Time', 'en' => 'Average cycle time'],
                    'question' => [
                        'de' => 'Wie lang ist die durchschnittliche Case-Cycle-Time?',
                        'en' => 'What is the average case cycle time?',
                    ],
                    'formula' => "AVG(pxResolvedTimestamp - pxCreateDateTime) FROM case WHERE pyStatusWork LIKE 'Resolved-%'",
                    'grain' => ['de' => 'Case (aggregiert)', 'en' => 'Case (aggregated)'],
                    'dimensions' => ['case_type', 'urgency'],
                    'fieldsUsed' => ['Case.pxCreateDateTime', 'Case.pxResolvedTimestamp', 'Case.pyClass'],
                    'sourceHints' => [
                        'de' => 'Business-Hours-Kalender berücksichtigen, wenn SLA es vorsieht.',
                        'en' => 'Account for a business-hours calendar where the SLA requires it.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Stage-Übergängen aufschlüsseln für Bottleneck-Analyse.',
                        'en' => 'Break down by stage transitions for bottleneck analysis.',
                    ],
                ],
                [
                    'id' => 'sla-breaches',
                    'example' => false,
                    'label' => ['de' => 'SLA Breaches', 'en' => 'SLA breaches'],
                    'question' => [
                        'de' => 'Wie viele SLA-Deadlines wurden in der Periode verletzt?',
                        'en' => 'How many SLA deadlines were breached in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM sla_event WHERE deadlinePassed = true AND created_at IN period',
                    'grain' => ['de' => 'SLA Event (verletzt)', 'en' => 'SLA event (breached)'],
                    'dimensions' => ['case_type', 'urgency', 'operator'],
                    'fieldsUsed' => ['SLAEvent.deadlinePassed', 'SLAEvent.goalPassed', 'Case.pyClass'],
                    'sourceHints' => [
                        'de' => 'Goal- vs. Deadline-Passed getrennt tracken für Frühwarnung vs. Verletzung.',
                        'en' => 'Track goal-passed vs deadline-passed separately for early warning vs breach.',
                    ],
                    'adapt' => [
                        'de' => 'Breach-Rate = breaches / cases je Case Type.',
                        'en' => 'Breach rate = breaches / cases per case type.',
                    ],
                ],
                [
                    'id' => 'assignments-open',
                    'example' => false,
                    'label' => ['de' => 'Assignments Open', 'en' => 'Assignments open'],
                    'question' => [
                        'de' => 'Wie viele Assignments sind aktuell offen (Snapshot)?',
                        'en' => 'How many assignments are currently open (snapshot)?',
                    ],
                    'formula' => 'COUNT(*) FROM assignment WHERE status = \'Open\'',
                    'grain' => ['de' => 'Open Assignment', 'en' => 'Open assignment'],
                    'dimensions' => ['case_type', 'operator'],
                    'fieldsUsed' => ['Assignment.ID', 'Assignment.pyCaseID', 'Assignment.pyAssignedOperatorID'],
                    'sourceHints' => [
                        'de' => 'Worklist vs. Workbasket Assignments getrennt zählen.',
                        'en' => 'Count worklist vs workbasket assignments separately.',
                    ],
                    'adapt' => [
                        'de' => 'Nur overdue Assignments (urgentIn < 0) als Backlog-Risk-KPI.',
                        'en' => 'Only overdue assignments (urgentIn < 0) as a backlog-risk KPI.',
                    ],
                ],
            ],
            'tools' => $wave11Tools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'camunda',
            'domain' => 'bpm',
            'order' => 440,
            'label' => ['de' => 'Camunda', 'en' => 'Camunda'],
            'shortPurpose' => [
                'de' => 'Process Automation: Process Instance/Task/Incident-Meta — REST-API-Load; Variable-Payloads vorsichtig, keine Byte-Array-Dumps.',
                'en' => 'Process automation: process instance/task/incident meta — REST API load; handle variable payloads carefully, no byte-array dumps.',
            ],
            'entities' => [
                [
                    'id' => 'process_definition',
                    'label' => ['de' => 'Process Definition', 'en' => 'Process definition'],
                    'description' => [
                        'de' => 'BPMN Process Definition — key, version, name; Dimension.',
                        'en' => 'BPMN process definition — key, version, name; dimension.',
                    ],
                    'grain' => ['de' => 'Eine Process Definition (id)', 'en' => 'One process definition (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'process_instance',
                    'label' => ['de' => 'Process Instance', 'en' => 'Process instance'],
                    'description' => [
                        'de' => 'Process Instance — startTime/endTime, state; Fact-Anker.',
                        'en' => 'Process instance — startTime/endTime, state; fact anchor.',
                    ],
                    'grain' => ['de' => 'Eine Process Instance (id)', 'en' => 'One process instance (id)'],
                    'role' => ['de' => 'Fact / Anker', 'en' => 'Fact / anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'user_task',
                    'label' => ['de' => 'User Task', 'en' => 'User task'],
                    'description' => [
                        'de' => 'User Task — taskDefinitionKey, assignee, dueDate; Fact.',
                        'en' => 'User task — taskDefinitionKey, assignee, dueDate; fact.',
                    ],
                    'grain' => ['de' => 'Ein Task (id)', 'en' => 'One task (id)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'task_assignee',
                    'label' => ['de' => 'Task Assignee', 'en' => 'Task assignee'],
                    'description' => [
                        'de' => 'User/Group Assignee — Workforce-PII für Task-Ownership.',
                        'en' => 'User/group assignee — workforce PII for task ownership.',
                    ],
                    'grain' => ['de' => 'Ein Assignee (id)', 'en' => 'One assignee (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'incident',
                    'label' => ['de' => 'Incident', 'en' => 'Incident'],
                    'description' => [
                        'de' => 'Process Incident — incidentType, message; Full Stacktraces skip.',
                        'en' => 'Process incident — incidentType, message; skip full stack traces.',
                    ],
                    'grain' => ['de' => 'Ein Incident (id)', 'en' => 'One incident (id)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'process_variable',
                    'label' => ['de' => 'Process Variable', 'en' => 'Process variable'],
                    'description' => [
                        'de' => 'Process Variable Meta — name, type; Value kann Business-/PII-Daten enthalten.',
                        'en' => 'Process variable meta — name, type; value may contain business/PII data.',
                    ],
                    'grain' => ['de' => 'Eine Variable (id)', 'en' => 'One variable (id)'],
                    'role' => ['de' => 'Meta-Fact (sensibel)', 'en' => 'Meta fact (sensitive)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'decision_definition',
                    'label' => ['de' => 'Decision Definition', 'en' => 'Decision definition'],
                    'description' => [
                        'de' => 'DMN Decision Definition — key, version; Dimension.',
                        'en' => 'DMN decision definition — key, version; dimension.',
                    ],
                    'grain' => ['de' => 'Eine Decision Definition (id)', 'en' => 'One decision definition (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'external_task',
                    'label' => ['de' => 'External Task', 'en' => 'External task'],
                    'description' => [
                        'de' => 'External Task (Service Worker) — topicName, retries; Fact.',
                        'en' => 'External task (service worker) — topicName, retries; fact.',
                    ],
                    'grain' => ['de' => 'Ein External Task (id)', 'en' => 'One external task (id)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'ProcessDefinition', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Definition-Join', 'en' => 'Definition join']],
                ['entity' => 'ProcessDefinition', 'name' => 'key', 'role' => 'dimension', 'why' => ['de' => 'Process-Key (stabil über Versionen)', 'en' => 'Process key (stable across versions)']],
                ['entity' => 'ProcessDefinition', 'name' => 'version', 'role' => 'dimension', 'why' => ['de' => 'Deployment-Version', 'en' => 'Deployment version']],
                ['entity' => 'ProcessInstance', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Instance-Join', 'en' => 'Instance join']],
                ['entity' => 'ProcessInstance', 'name' => 'processDefinitionId', 'role' => 'dimension', 'why' => ['de' => 'Definition-Rückjoin', 'en' => 'Definition back-join']],
                ['entity' => 'ProcessInstance', 'name' => 'startTime', 'role' => 'measure', 'why' => ['de' => 'Instance-Start', 'en' => 'Instance start']],
                ['entity' => 'ProcessInstance', 'name' => 'endTime', 'role' => 'measure', 'why' => ['de' => 'Instance-Ende', 'en' => 'Instance end']],
                ['entity' => 'ProcessInstance', 'name' => 'state', 'role' => 'dimension', 'why' => ['de' => 'ACTIVE / COMPLETED / EXTERNALLY_TERMINATED', 'en' => 'ACTIVE / COMPLETED / EXTERNALLY_TERMINATED']],
                ['entity' => 'ProcessInstance', 'name' => 'businessKey', 'role' => 'dimension', 'why' => ['de' => 'Fachlicher Korrelations-Key', 'en' => 'Business correlation key']],
                ['entity' => 'UserTask', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Task-Join', 'en' => 'Task join']],
                ['entity' => 'UserTask', 'name' => 'taskDefinitionKey', 'role' => 'dimension', 'why' => ['de' => 'Task-Typ im BPMN', 'en' => 'Task type in BPMN']],
                ['entity' => 'UserTask', 'name' => 'assignee', 'role' => 'pii', 'why' => ['de' => 'Zugewiesener User / PII', 'en' => 'Assigned user / PII']],
                ['entity' => 'UserTask', 'name' => 'created', 'role' => 'measure', 'why' => ['de' => 'Task-Erstellung', 'en' => 'Task created']],
                ['entity' => 'UserTask', 'name' => 'due', 'role' => 'measure', 'why' => ['de' => 'Fälligkeitsdatum', 'en' => 'Due date']],
                ['entity' => 'UserTask', 'name' => 'processInstanceId', 'role' => 'dimension', 'why' => ['de' => 'Process-Instance-Rückjoin', 'en' => 'Process instance back-join']],
                ['entity' => 'TaskAssignee', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'User/Group-Join', 'en' => 'User/group join']],
                ['entity' => 'TaskAssignee', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'Incident', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Incident-Join', 'en' => 'Incident join']],
                ['entity' => 'Incident', 'name' => 'incidentType', 'role' => 'dimension', 'why' => ['de' => 'failedJob / failedExternalTask …', 'en' => 'failedJob / failedExternalTask …']],
                ['entity' => 'Incident', 'name' => 'incidentTimestamp', 'role' => 'measure', 'why' => ['de' => 'Incident-Zeitpunkt', 'en' => 'Incident timestamp']],
                ['entity' => 'Incident', 'name' => 'processInstanceId', 'role' => 'dimension', 'why' => ['de' => 'Process-Instance-Rückjoin', 'en' => 'Process instance back-join']],
                ['entity' => 'ProcessVariable', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Variable-Join', 'en' => 'Variable join']],
                ['entity' => 'ProcessVariable', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Variablenname', 'en' => 'Variable name']],
                ['entity' => 'ProcessVariable', 'name' => 'type', 'role' => 'dimension', 'why' => ['de' => 'String / Integer / Json / Bytes', 'en' => 'String / Integer / Json / Bytes']],
                ['entity' => 'ProcessVariable', 'name' => 'value', 'role' => 'pii', 'why' => ['de' => 'Business-Value — kann PII enthalten', 'en' => 'Business value — may contain PII']],
                ['entity' => 'DecisionDefinition', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Decision-Join', 'en' => 'Decision join']],
                ['entity' => 'DecisionDefinition', 'name' => 'key', 'role' => 'dimension', 'why' => ['de' => 'Decision-Key', 'en' => 'Decision key']],
                ['entity' => 'ExternalTask', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'External-Task-Join', 'en' => 'External task join']],
                ['entity' => 'ExternalTask', 'name' => 'topicName', 'role' => 'dimension', 'why' => ['de' => 'Service-Worker-Topic', 'en' => 'Service worker topic']],
                ['entity' => 'ExternalTask', 'name' => 'retries', 'role' => 'measure', 'why' => ['de' => 'Verbleibende Retries', 'en' => 'Remaining retries']],
            ],
            'skipTables' => [
                [
                    'name' => 'Process variable payloads with business data (bulk)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Variable-Values können PII/Secrets enthalten — Typ/Name-Meta reicht.',
                        'en' => 'Variable values can contain PII/secrets — type/name meta suffices.',
                    ],
                ],
                [
                    'name' => 'History detail byte-array / file variables',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Byte-Array-/File-Variablen — nie landen; nur Presence/Count.',
                        'en' => 'Byte-array/file variables — never land; presence/count only.',
                    ],
                ],
                [
                    'name' => 'Incident full stack trace dumps',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Stacktraces sind technisches Debugging-Detail — Incident-Type/Message-Meta reicht.',
                        'en' => 'Stack traces are technical debugging detail — incident type/message meta suffices.',
                    ],
                ],
                [
                    'name' => 'Decision input/output full payloads',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'DMN-Input/Output kann Geschäftsdaten enthalten — Decision-Outcome-Meta reicht.',
                        'en' => 'DMN input/output can contain business data — decision-outcome meta suffices.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Process variable business payloads (bulk)', 'reason' => ['de' => 'PII-Risiko — Meta reicht', 'en' => 'PII risk — meta suffices']],
                ['name' => 'Byte-array/file variables', 'reason' => ['de' => 'Kein Default-Load von Binaries', 'en' => 'No default load of binaries']],
                ['name' => 'Incident full stack traces', 'reason' => ['de' => 'Debugging-Detail — nicht Analytics-relevant', 'en' => 'Debugging detail — not analytics relevant']],
                ['name' => 'Decision input/output full payloads', 'reason' => ['de' => 'Geschäftsdaten-Risiko — Outcome-Meta reicht', 'en' => 'Business-data risk — outcome meta suffices']],
            ],
            'dimensions' => [
                [
                    'id' => 'process_key',
                    'label' => ['de' => 'Process Key', 'en' => 'Process key'],
                    'grain' => ['de' => 'process_definition.key', 'en' => 'process_definition.key'],
                    'notes' => [
                        'de' => 'Key ist stabil über Versionen — für Trendvergleiche bevorzugen.',
                        'en' => 'Key is stable across versions — prefer it for trend comparisons.',
                    ],
                ],
                [
                    'id' => 'task_definition_key',
                    'label' => ['de' => 'Task Definition Key', 'en' => 'Task definition key'],
                    'grain' => ['de' => 'user_task.taskDefinitionKey', 'en' => 'user_task.taskDefinitionKey'],
                    'notes' => [
                        'de' => 'Task-Typ im BPMN-Diagramm für Bottleneck-Analyse.',
                        'en' => 'Task type in the BPMN diagram for bottleneck analysis.',
                    ],
                ],
                [
                    'id' => 'incident_type',
                    'label' => ['de' => 'Incident Type', 'en' => 'Incident type'],
                    'grain' => ['de' => 'incident.incidentType', 'en' => 'incident.incidentType'],
                    'notes' => [
                        'de' => 'failedJob vs. failedExternalTask getrennt für Root-Cause.',
                        'en' => 'Separate failedJob vs failedExternalTask for root cause.',
                    ],
                ],
                [
                    'id' => 'tenant',
                    'label' => ['de' => 'Tenant', 'en' => 'Tenant'],
                    'grain' => ['de' => 'process_instance.tenantId', 'en' => 'process_instance.tenantId'],
                    'notes' => [
                        'de' => 'Bei Multi-Tenant-Deployments Pflicht-Slice.',
                        'en' => 'Mandatory slice for multi-tenant deployments.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'UserTask',
                    'fields' => ['assignee', 'owner'],
                    'treatment' => [
                        'de' => 'Task Assignee/Owner — Workforce-PII; user id als Join bevorzugen.',
                        'en' => 'Task assignee/owner — workforce PII; prefer user id as join.',
                    ],
                ],
                [
                    'entity' => 'ProcessVariable',
                    'fields' => ['value'],
                    'treatment' => [
                        'de' => 'Variable-Values können Geschäfts-PII enthalten — Schema pro Prozess reviewen.',
                        'en' => 'Variable values can contain business PII — review schema per process.',
                    ],
                ],
                [
                    'entity' => 'ProcessInstance',
                    'fields' => ['businessKey'],
                    'treatment' => [
                        'de' => 'businessKey kann Kunden-/Case-Identifikatoren enthalten — Zugriff prüfen.',
                        'en' => 'businessKey may contain customer/case identifiers — review access.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'process instance id, process definition key+version, task id, incident id.',
                        'en' => 'process instance id, process definition key+version, task id, incident id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Process Definition, Process Instance, User Task, Incident — keine Variable-Bulk-Payloads.',
                        'en' => 'Process definition, process instance, user task, incident — no bulk variable payloads.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'process-instances-started',
                    'example' => true,
                    'label' => ['de' => 'Process Instances Started', 'en' => 'Process instances started'],
                    'question' => [
                        'de' => 'Wie viele Process Instances wurden in der Periode gestartet?',
                        'en' => 'How many process instances were started in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM process_instance WHERE startTime IN period',
                    'grain' => ['de' => 'Started Process Instance', 'en' => 'Started process instance'],
                    'dimensions' => ['process_key', 'tenant'],
                    'fieldsUsed' => ['ProcessInstance.id', 'ProcessInstance.startTime', 'ProcessInstance.processDefinitionId'],
                    'sourceHints' => [
                        'de' => 'Nur Top-Level-Instances zählen; Call-Activities separat.',
                        'en' => 'Count only top-level instances; call activities separately.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Trigger (message start event vs. API start) segmentieren.',
                        'en' => 'Segment by trigger (message start event vs API start).',
                    ],
                ],
                [
                    'id' => 'process-instances-completed',
                    'example' => true,
                    'label' => ['de' => 'Process Instances Completed', 'en' => 'Process instances completed'],
                    'question' => [
                        'de' => 'Wie viele Process Instances wurden in der Periode abgeschlossen?',
                        'en' => 'How many process instances were completed in the period?',
                    ],
                    'formula' => "COUNT(*) FROM process_instance WHERE state = 'COMPLETED' AND endTime IN period",
                    'grain' => ['de' => 'Completed Process Instance', 'en' => 'Completed process instance'],
                    'dimensions' => ['process_key', 'tenant'],
                    'fieldsUsed' => ['ProcessInstance.state', 'ProcessInstance.endTime', 'ProcessInstance.processDefinitionId'],
                    'sourceHints' => [
                        'de' => 'EXTERNALLY_TERMINATED getrennt von COMPLETED behandeln.',
                        'en' => 'Treat EXTERNALLY_TERMINATED separately from COMPLETED.',
                    ],
                    'adapt' => [
                        'de' => 'Completion Rate = completed / started je Process Key.',
                        'en' => 'Completion rate = completed / started per process key.',
                    ],
                ],
                [
                    'id' => 'tasks-completed',
                    'example' => false,
                    'label' => ['de' => 'Tasks Completed', 'en' => 'Tasks completed'],
                    'question' => [
                        'de' => 'Wie viele User Tasks wurden in der Periode abgeschlossen?',
                        'en' => 'How many user tasks were completed in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM user_task_history WHERE endTime IN period',
                    'grain' => ['de' => 'Completed Task', 'en' => 'Completed task'],
                    'dimensions' => ['task_definition_key', 'process_key'],
                    'fieldsUsed' => ['UserTask.id', 'UserTask.taskDefinitionKey', 'UserTask.processInstanceId'],
                    'sourceHints' => [
                        'de' => 'History-API für abgeschlossene Tasks nutzen, nicht nur aktive.',
                        'en' => 'Use the history API for completed tasks, not just active ones.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Assignee-Team für Workload-Verteilung aufschlüsseln.',
                        'en' => 'Break down by assignee team for workload distribution.',
                    ],
                ],
                [
                    'id' => 'incidents-open',
                    'example' => false,
                    'label' => ['de' => 'Incidents Open', 'en' => 'Incidents open'],
                    'question' => [
                        'de' => 'Wie viele Incidents sind aktuell offen (Snapshot)?',
                        'en' => 'How many incidents are currently open (snapshot)?',
                    ],
                    'formula' => 'COUNT(*) FROM incident WHERE resolved = false',
                    'grain' => ['de' => 'Open Incident', 'en' => 'Open incident'],
                    'dimensions' => ['incident_type', 'process_key'],
                    'fieldsUsed' => ['Incident.id', 'Incident.incidentType', 'Incident.processInstanceId'],
                    'sourceHints' => [
                        'de' => 'Retries-exhausted-Failures separat von transienten Retries zählen.',
                        'en' => 'Count retries-exhausted failures separately from transient retries.',
                    ],
                    'adapt' => [
                        'de' => 'Mean-Time-to-Resolve für Incidents als Folge-KPI.',
                        'en' => 'Mean time to resolve for incidents as a follow-up KPI.',
                    ],
                ],
                [
                    'id' => 'average-cycle-time',
                    'example' => false,
                    'label' => ['de' => 'Average Process Cycle Time', 'en' => 'Average process cycle time'],
                    'question' => [
                        'de' => 'Wie lang ist die durchschnittliche Laufzeit abgeschlossener Process Instances?',
                        'en' => 'What is the average runtime of completed process instances?',
                    ],
                    'formula' => "AVG(endTime - startTime) FROM process_instance WHERE state = 'COMPLETED'",
                    'grain' => ['de' => 'Process Instance (aggregiert)', 'en' => 'Process instance (aggregated)'],
                    'dimensions' => ['process_key', 'tenant'],
                    'fieldsUsed' => ['ProcessInstance.startTime', 'ProcessInstance.endTime', 'ProcessInstance.state'],
                    'sourceHints' => [
                        'de' => 'Version-Wechsel des Process Key können Cycle-Time-Sprünge erklären.',
                        'en' => 'Process key version changes can explain cycle-time jumps.',
                    ],
                    'adapt' => [
                        'de' => 'Wartezeit (Task) vs. Systemzeit (Service Task) separat ausweisen.',
                        'en' => 'Report wait time (task) vs system time (service task) separately.',
                    ],
                ],
            ],
            'tools' => $wave11Tools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'epic',
            'domain' => 'healthcare',
            'order' => 450,
            'label' => ['de' => 'Epic', 'en' => 'Epic'],
            'shortPurpose' => [
                'de' => 'Healthcare EHR: Encounter/Appointment/Code-Meta-Aggregate — FHIR-Load; strikte PHI-Skip/PII-Policy, keine klinischen Notizen/Bodies.',
                'en' => 'Healthcare EHR: encounter/appointment/code metadata aggregates — FHIR load; strict PHI skip/PII policy, no clinical notes/bodies.',
            ],
            'entities' => [
                [
                    'id' => 'patient',
                    'label' => ['de' => 'Patient', 'en' => 'Patient'],
                    'description' => [
                        'de' => 'Patient — MRN, DOB, Name; strikte PHI, nur tokenisierte Identifiers in Marts.',
                        'en' => 'Patient — MRN, DOB, name; strict PHI, only tokenized identifiers in marts.',
                    ],
                    'grain' => ['de' => 'Ein Patient (MRN / FHIR id)', 'en' => 'One patient (MRN / FHIR id)'],
                    'role' => ['de' => 'Dimension (PHI strict)', 'en' => 'Dimension (PHI strict)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'encounter',
                    'label' => ['de' => 'Encounter', 'en' => 'Encounter'],
                    'description' => [
                        'de' => 'Encounter Meta — type, admit/discharge, department; kein klinischer Notiztext.',
                        'en' => 'Encounter meta — type, admit/discharge, department; no clinical note text.',
                    ],
                    'grain' => ['de' => 'Ein Encounter (id)', 'en' => 'One encounter (id)'],
                    'role' => ['de' => 'Fact / Anker', 'en' => 'Fact / anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'appointment',
                    'label' => ['de' => 'Appointment', 'en' => 'Appointment'],
                    'description' => [
                        'de' => 'Appointment Meta — scheduled time, status; kein Freitext-Grund.',
                        'en' => 'Appointment meta — scheduled time, status; no free-text reason.',
                    ],
                    'grain' => ['de' => 'Ein Appointment (id)', 'en' => 'One appointment (id)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'department',
                    'label' => ['de' => 'Department', 'en' => 'Department'],
                    'description' => [
                        'de' => 'Department/Location — Name, Specialty; Dimension.',
                        'en' => 'Department/location — name, specialty; dimension.',
                    ],
                    'grain' => ['de' => 'Ein Department (id)', 'en' => 'One department (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'provider',
                    'label' => ['de' => 'Provider', 'en' => 'Provider'],
                    'description' => [
                        'de' => 'Provider — NPI, Name, Specialty; Workforce-PII.',
                        'en' => 'Provider — NPI, name, specialty; workforce PII.',
                    ],
                    'grain' => ['de' => 'Ein Provider (NPI / id)', 'en' => 'One provider (NPI / id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'diagnosis_code',
                    'label' => ['de' => 'Diagnosis Code', 'en' => 'Diagnosis code'],
                    'description' => [
                        'de' => 'Diagnosis Code (ICD) je Encounter — Code only, keine Freitext-Beschreibung im Mart.',
                        'en' => 'Diagnosis code (ICD) per encounter — code only, no free-text description in the mart.',
                    ],
                    'grain' => ['de' => 'Ein Diagnosis Code Eintrag (encounter_id, code)', 'en' => 'One diagnosis code entry (encounter_id, code)'],
                    'role' => ['de' => 'Fact (Code-Meta)', 'en' => 'Fact (code meta)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'procedure_code',
                    'label' => ['de' => 'Procedure Code', 'en' => 'Procedure code'],
                    'description' => [
                        'de' => 'Procedure Code (CPT) je Encounter — Code only.',
                        'en' => 'Procedure code (CPT) per encounter — code only.',
                    ],
                    'grain' => ['de' => 'Ein Procedure Code Eintrag (encounter_id, code)', 'en' => 'One procedure code entry (encounter_id, code)'],
                    'role' => ['de' => 'Fact (Code-Meta)', 'en' => 'Fact (code meta)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'lab_result_meta',
                    'label' => ['de' => 'Lab Result Meta', 'en' => 'Lab result meta'],
                    'description' => [
                        'de' => 'Lab Result Meta — testCode, resultFlag; kein Freitext-Kommentar/Wert-Narrativ.',
                        'en' => 'Lab result meta — testCode, resultFlag; no free-text comment/narrative value.',
                    ],
                    'grain' => ['de' => 'Ein Lab Result Meta (id)', 'en' => 'One lab result meta (id)'],
                    'role' => ['de' => 'Fact (sensibel)', 'en' => 'Fact (sensitive)'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Patient', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'FHIR Patient-Join (tokenisiert)', 'en' => 'FHIR patient join (tokenized)']],
                ['entity' => 'Patient', 'name' => 'mrn', 'role' => 'pii', 'why' => ['de' => 'Medical Record Number — PHI', 'en' => 'Medical record number — PHI']],
                ['entity' => 'Patient', 'name' => 'birthDate', 'role' => 'pii', 'why' => ['de' => 'Geburtsdatum — PHI', 'en' => 'Date of birth — PHI']],
                ['entity' => 'Patient', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'Name — PHI', 'en' => 'Name — PHI']],
                ['entity' => 'Patient', 'name' => 'gender', 'role' => 'dimension', 'why' => ['de' => 'Demografie-Dim (aggregiert nutzen)', 'en' => 'Demographic dim (use in aggregate)']],
                ['entity' => 'Encounter', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Encounter-Join', 'en' => 'Encounter join']],
                ['entity' => 'Encounter', 'name' => 'patientId', 'role' => 'dimension', 'why' => ['de' => 'Patient-Rückjoin (tokenisiert)', 'en' => 'Patient back-join (tokenized)']],
                ['entity' => 'Encounter', 'name' => 'class', 'role' => 'dimension', 'why' => ['de' => 'inpatient / outpatient / emergency', 'en' => 'inpatient / outpatient / emergency']],
                ['entity' => 'Encounter', 'name' => 'admitDateTime', 'role' => 'measure', 'why' => ['de' => 'Aufnahmezeitpunkt', 'en' => 'Admit timestamp']],
                ['entity' => 'Encounter', 'name' => 'dischargeDateTime', 'role' => 'measure', 'why' => ['de' => 'Entlassungszeitpunkt', 'en' => 'Discharge timestamp']],
                ['entity' => 'Encounter', 'name' => 'departmentId', 'role' => 'dimension', 'why' => ['de' => 'Department-Rückjoin', 'en' => 'Department back-join']],
                ['entity' => 'Encounter', 'name' => 'providerId', 'role' => 'dimension', 'why' => ['de' => 'Provider-Rückjoin', 'en' => 'Provider back-join']],
                ['entity' => 'Encounter', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'planned / in-progress / finished', 'en' => 'planned / in-progress / finished']],
                ['entity' => 'Appointment', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Appointment-Join', 'en' => 'Appointment join']],
                ['entity' => 'Appointment', 'name' => 'patientId', 'role' => 'dimension', 'why' => ['de' => 'Patient-Rückjoin (tokenisiert)', 'en' => 'Patient back-join (tokenized)']],
                ['entity' => 'Appointment', 'name' => 'start', 'role' => 'measure', 'why' => ['de' => 'Geplanter Termin', 'en' => 'Scheduled time']],
                ['entity' => 'Appointment', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'booked / arrived / no-show / cancelled', 'en' => 'booked / arrived / no-show / cancelled']],
                ['entity' => 'Appointment', 'name' => 'departmentId', 'role' => 'dimension', 'why' => ['de' => 'Department-Rückjoin', 'en' => 'Department back-join']],
                ['entity' => 'Department', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Department-Join', 'en' => 'Department join']],
                ['entity' => 'Department', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Department-Name', 'en' => 'Department name']],
                ['entity' => 'Department', 'name' => 'specialty', 'role' => 'dimension', 'why' => ['de' => 'Fachrichtung', 'en' => 'Specialty']],
                ['entity' => 'Provider', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Provider-Join', 'en' => 'Provider join']],
                ['entity' => 'Provider', 'name' => 'npi', 'role' => 'dimension', 'why' => ['de' => 'National Provider Identifier', 'en' => 'National provider identifier']],
                ['entity' => 'Provider', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'Provider-Name / PII', 'en' => 'Provider name / PII']],
                ['entity' => 'Provider', 'name' => 'specialty', 'role' => 'dimension', 'why' => ['de' => 'Fachrichtung', 'en' => 'Specialty']],
                ['entity' => 'DiagnosisCode', 'name' => 'encounterId', 'role' => 'key', 'why' => ['de' => 'Encounter-Rückjoin', 'en' => 'Encounter back-join']],
                ['entity' => 'DiagnosisCode', 'name' => 'icdCode', 'role' => 'dimension', 'why' => ['de' => 'ICD-10-Code (nicht Freitext)', 'en' => 'ICD-10 code (not free text)']],
                ['entity' => 'ProcedureCode', 'name' => 'encounterId', 'role' => 'key', 'why' => ['de' => 'Encounter-Rückjoin', 'en' => 'Encounter back-join']],
                ['entity' => 'ProcedureCode', 'name' => 'cptCode', 'role' => 'dimension', 'why' => ['de' => 'CPT-Code (nicht Freitext)', 'en' => 'CPT code (not free text)']],
                ['entity' => 'LabResultMeta', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Lab-Result-Join', 'en' => 'Lab result join']],
                ['entity' => 'LabResultMeta', 'name' => 'testCode', 'role' => 'dimension', 'why' => ['de' => 'LOINC-Testcode', 'en' => 'LOINC test code']],
                ['entity' => 'LabResultMeta', 'name' => 'resultFlag', 'role' => 'dimension', 'why' => ['de' => 'normal / abnormal / critical (kein Wert)', 'en' => 'normal / abnormal / critical (no value)']],
            ],
            'skipTables' => [
                [
                    'name' => 'Clinical / progress notes bodies',
                    'category' => 'phi',
                    'reason' => [
                        'de' => 'Klinische Notizen — strikter PHI-Skip; nie ins Analytics-Warehouse.',
                        'en' => 'Clinical notes — strict PHI skip; never into the analytics warehouse.',
                    ],
                ],
                [
                    'name' => 'Lab result narrative / free-text values',
                    'category' => 'phi',
                    'reason' => [
                        'de' => 'Freitext-Befunde — nur resultFlag-Meta, nie Wert-Narrativ.',
                        'en' => 'Free-text findings — only resultFlag meta, never the narrative value.',
                    ],
                ],
                [
                    'name' => 'Imaging / DICOM binaries',
                    'category' => 'phi',
                    'reason' => [
                        'de' => 'Bildgebende Binärdaten — nie im Warehouse; Order-Meta reicht.',
                        'en' => 'Imaging binary data — never in the warehouse; order meta suffices.',
                    ],
                ],
                [
                    'name' => 'Full HL7/FHIR message payload bulk',
                    'category' => 'phi',
                    'reason' => [
                        'de' => 'Volle Nachrichten-Payloads enthalten PHI-Bündel — nur Allowlist-Felder extrahieren.',
                        'en' => 'Full message payloads bundle PHI — extract allowlisted fields only.',
                    ],
                ],
                [
                    'name' => 'Patient portal messages content',
                    'category' => 'phi',
                    'reason' => [
                        'de' => 'Portal-Nachrichten-Content — PHI-Freitext, nie laden.',
                        'en' => 'Portal message content — PHI free text, never load.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Clinical/progress notes bodies', 'reason' => ['de' => 'Strikter PHI-Skip', 'en' => 'Strict PHI skip']],
                ['name' => 'Lab result narrative/free text', 'reason' => ['de' => 'PHI — nur Flag-Meta', 'en' => 'PHI — flag meta only']],
                ['name' => 'Imaging / DICOM binaries', 'reason' => ['de' => 'Nie im Warehouse', 'en' => 'Never in the warehouse']],
                ['name' => 'Full HL7/FHIR message payload bulk', 'reason' => ['de' => 'PHI-Bündel — Allowlist only', 'en' => 'PHI bundle — allowlist only']],
                ['name' => 'Patient portal message content', 'reason' => ['de' => 'PHI-Freitext — skip', 'en' => 'PHI free text — skip']],
            ],
            'dimensions' => [
                [
                    'id' => 'department',
                    'label' => ['de' => 'Department', 'en' => 'Department'],
                    'grain' => ['de' => 'department.id / name', 'en' => 'department.id / name'],
                    'notes' => [
                        'de' => 'Primärer Slice für Volume- und Utilization-KPIs.',
                        'en' => 'Primary slice for volume and utilization KPIs.',
                    ],
                ],
                [
                    'id' => 'encounter_type',
                    'label' => ['de' => 'Encounter Type', 'en' => 'Encounter type'],
                    'grain' => ['de' => 'encounter.class', 'en' => 'encounter.class'],
                    'notes' => [
                        'de' => 'Inpatient/Outpatient/Emergency getrennt für LOS und Volume.',
                        'en' => 'Separate inpatient/outpatient/emergency for LOS and volume.',
                    ],
                ],
                [
                    'id' => 'provider_specialty',
                    'label' => ['de' => 'Provider Specialty', 'en' => 'Provider specialty'],
                    'grain' => ['de' => 'provider.specialty', 'en' => 'provider.specialty'],
                    'notes' => [
                        'de' => 'Fachrichtungs-Rollups für Utilization und Workload.',
                        'en' => 'Specialty rollups for utilization and workload.',
                    ],
                ],
                [
                    'id' => 'diagnosis_category',
                    'label' => ['de' => 'Diagnosis Category', 'en' => 'Diagnosis category'],
                    'grain' => ['de' => 'ICD-Code-Kategorie (Kapitel-Ebene)', 'en' => 'ICD code category (chapter level)'],
                    'notes' => [
                        'de' => 'Nur aggregierte Kategorie-Ebene in Default-Marts — k-Anonymität beachten.',
                        'en' => 'Only aggregated category level in default marts — observe k-anonymity.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Patient',
                    'fields' => ['mrn', 'birthDate', 'name'],
                    'treatment' => [
                        'de' => 'MRN/DOB/Name sind PHI — strikter Zugriff, tokenisierte id in allen Analytics-Stages.',
                        'en' => 'MRN/DOB/name are PHI — strict access, tokenized id in all analytics stages.',
                    ],
                ],
                [
                    'entity' => 'Provider',
                    'fields' => ['name', 'npi'],
                    'treatment' => [
                        'de' => 'Provider-Name — Workforce-PII; NPI als stabiler Key für Utilization-KPIs.',
                        'en' => 'Provider name — workforce PII; NPI as a stable key for utilization KPIs.',
                    ],
                ],
                [
                    'entity' => 'Encounter',
                    'fields' => ['patientId'],
                    'treatment' => [
                        'de' => 'Encounter-zu-Patient-Link ist PHI-Fläche — nur aggregiert oder tokenisiert exportieren.',
                        'en' => 'Encounter-to-patient link is a PHI surface — export only aggregated or tokenized.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'patient MRN (tokenisiert/hashed), encounter id, FHIR id, provider NPI.',
                        'en' => 'patient MRN (tokenized/hashed), encounter id, FHIR id, provider NPI.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Patient-Identifier (tokenisiert), Encounter Meta, Appointment Meta, Diagnosis/Procedure Codes — nie Notizen/klinische Narrative.',
                        'en' => 'Patient identifiers (tokenized), encounter meta, appointment meta, diagnosis/procedure codes — never notes/clinical narrative.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'encounters-count',
                    'example' => true,
                    'label' => ['de' => 'Encounters Count', 'en' => 'Encounters count'],
                    'question' => [
                        'de' => 'Wie viele Encounters gab es in der Periode?',
                        'en' => 'How many encounters occurred in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM encounter WHERE admitDateTime IN period',
                    'grain' => ['de' => 'Encounter', 'en' => 'Encounter'],
                    'dimensions' => ['department', 'encounter_type'],
                    'fieldsUsed' => ['Encounter.id', 'Encounter.admitDateTime', 'Encounter.departmentId', 'Encounter.class'],
                    'sourceHints' => [
                        'de' => 'Nur abgeschlossene/valide Encounters (status=finished) für Reporting-KPIs.',
                        'en' => 'Only completed/valid encounters (status=finished) for reporting KPIs.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Encounter-Class (inpatient/outpatient/emergency) segmentieren.',
                        'en' => 'Segment by encounter class (inpatient/outpatient/emergency).',
                    ],
                ],
                [
                    'id' => 'appointments-completed',
                    'example' => true,
                    'label' => ['de' => 'Appointments Completed', 'en' => 'Appointments completed'],
                    'question' => [
                        'de' => 'Wie viele Appointments wurden in der Periode wahrgenommen?',
                        'en' => 'How many appointments were attended in the period?',
                    ],
                    'formula' => "COUNT(*) FROM appointment WHERE status = 'arrived' AND start IN period",
                    'grain' => ['de' => 'Attended Appointment', 'en' => 'Attended appointment'],
                    'dimensions' => ['department', 'provider_specialty'],
                    'fieldsUsed' => ['Appointment.id', 'Appointment.status', 'Appointment.start', 'Appointment.departmentId'],
                    'sourceHints' => [
                        'de' => 'Arrived vs. Fulfilled Status je Epic-Konfiguration klären.',
                        'en' => 'Clarify arrived vs fulfilled status per Epic configuration.',
                    ],
                    'adapt' => [
                        'de' => 'Telehealth vs. In-Person getrennt ausweisen, wenn verfügbar.',
                        'en' => 'Report telehealth vs in-person separately when available.',
                    ],
                ],
                [
                    'id' => 'no-show-rate',
                    'example' => false,
                    'label' => ['de' => 'No-Show Rate', 'en' => 'No-show rate'],
                    'question' => [
                        'de' => 'Welcher Anteil der Appointments endete als No-Show?',
                        'en' => 'What share of appointments ended as a no-show?',
                    ],
                    'formula' => "COUNT(*) FILTER (WHERE status = 'noshow') / COUNT(*) FROM appointment WHERE start IN period",
                    'grain' => ['de' => 'Appointment (aggregiert)', 'en' => 'Appointment (aggregated)'],
                    'dimensions' => ['department', 'provider_specialty'],
                    'fieldsUsed' => ['Appointment.status', 'Appointment.start', 'Appointment.departmentId'],
                    'sourceHints' => [
                        'de' => 'Cancelled vor Termin nicht mit No-Show vermischen.',
                        'en' => 'Do not mix cancelled-before-appointment with no-show.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Wochentag/Zeitfenster für Scheduling-Optimierung aufschlüsseln.',
                        'en' => 'Break down by weekday/time slot for scheduling optimization.',
                    ],
                ],
                [
                    'id' => 'average-length-of-stay',
                    'example' => false,
                    'label' => ['de' => 'Average Length of Stay', 'en' => 'Average length of stay'],
                    'question' => [
                        'de' => 'Wie hoch ist die durchschnittliche Verweildauer bei Inpatient-Encounters?',
                        'en' => 'What is the average length of stay for inpatient encounters?',
                    ],
                    'formula' => "AVG(dischargeDateTime - admitDateTime) FROM encounter WHERE class = 'inpatient' AND dischargeDateTime IN period",
                    'grain' => ['de' => 'Inpatient Encounter (aggregiert)', 'en' => 'Inpatient encounter (aggregated)'],
                    'dimensions' => ['department'],
                    'fieldsUsed' => ['Encounter.admitDateTime', 'Encounter.dischargeDateTime', 'Encounter.class', 'Encounter.departmentId'],
                    'sourceHints' => [
                        'de' => 'Nur aggregierte LOS-Werte in Marts — keine Einzel-Encounter-Exports mit Patient-Link.',
                        'en' => 'Only aggregated LOS values in marts — no individual encounter exports with patient link.',
                    ],
                    'adapt' => [
                        'de' => 'Ausreißer (LOS > 30 Tage) getrennt behandeln, um Mittelwert nicht zu verzerren.',
                        'en' => 'Handle outliers (LOS > 30 days) separately to avoid skewing the average.',
                    ],
                ],
                [
                    'id' => 'diagnosis-code-frequency',
                    'example' => false,
                    'label' => ['de' => 'Diagnosis Code Frequency (Aggregate)', 'en' => 'Diagnosis code frequency (aggregate)'],
                    'question' => [
                        'de' => 'Wie häufig traten Diagnosis Codes in der Periode auf (aggregiert)?',
                        'en' => 'How frequently did diagnosis codes occur in the period (aggregated)?',
                    ],
                    'formula' => 'COUNT(*) FROM diagnosis_code dc JOIN encounter e ON e.id = dc.encounterId WHERE e.admitDateTime IN period GROUP BY dc.icdCode HAVING COUNT(*) >= :k_anonymity_threshold',
                    'grain' => ['de' => 'Diagnosis Code (aggregiert, k-anonym)', 'en' => 'Diagnosis code (aggregated, k-anonymous)'],
                    'dimensions' => ['department', 'diagnosis_category'],
                    'fieldsUsed' => ['DiagnosisCode.icdCode', 'DiagnosisCode.encounterId', 'Encounter.admitDateTime'],
                    'sourceHints' => [
                        'de' => 'Nur Kategorien mit Mindestfallzahl (k-Anonymität) ausweisen, um Re-Identifikation zu vermeiden.',
                        'en' => 'Only report categories with a minimum case count (k-anonymity) to avoid re-identification.',
                    ],
                    'adapt' => [
                        'de' => 'Auf ICD-Kapitel-Ebene statt Volldiagnose aggregieren, wenn Fallzahlen niedrig sind.',
                        'en' => 'Aggregate to ICD chapter level instead of full diagnosis when case counts are low.',
                    ],
                ],
            ],
            'tools' => $wave11Tools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
