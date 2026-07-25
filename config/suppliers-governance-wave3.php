<?php

/**
 * Wave 3 governance overlays — Collab source-native PII, DSDR, skip guidance.
 * Merged onto catalog products by id (jira, confluence, slack, microsoft-teams).
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'jira' => [
        'pii' => [
            [
                'entity' => 'User / Atlassian account',
                'fields' => ['accountId', 'displayName', 'emailAddress', 'avatarUrls'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Jira-Nutzer — emailAddress nur mit Legal-Basis; accountId als Key behalten.', 'en' => 'Jira users — emailAddress only with legal basis; keep accountId as key.'],
            ],
            [
                'entity' => 'Issue reporter / assignee',
                'fields' => ['reporter', 'assignee', 'creator', 'watches'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: accountId only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: accountId only; Mart: aggregates only'],
                'treatment' => ['de' => 'Reporter/Assignee als accountId — keine Klartext-Namen in Default-Marts.', 'en' => 'Reporter/assignee as accountId — no cleartext names in default marts.'],
            ],
            [
                'entity' => 'Issue fields (custom)',
                'fields' => ['customfield_* email/phone', 'Customer Request Type contact fields'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Custom Fields und JSM-Kontakte inventarisieren — oft zweite PII-Fläche.', 'en' => 'Inventory custom fields and JSM contacts — often a second PII surface.'],
            ],
            [
                'entity' => 'Issue description / comments',
                'fields' => ['description', 'comment.body', 'worklog.comment'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Freitext kann Personenbezug haben — Default skip für Analytics.', 'en' => 'Free text can identify persons — default skip for analytics.'],
            ],
            [
                'entity' => 'Attachments / media',
                'fields' => ['attachment.filename', 'attachment.content', 'thumbnail'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Anhänge und Dateinamen — teuer und potenziell PII.', 'en' => 'Attachments and filenames — expensive and potentially PII.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'accountId, emailAddress, issue key, project key, custom external id fields.', 'en' => 'accountId, emailAddress, issue key, project key, custom external id fields.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Issue, User, Project, Comment, Worklog + Custom Fields.', 'en' => 'Issue, user, project, comment, worklog + custom fields.'],
            ],
            [
                'focus' => ['de' => 'REST / JQL exports', 'en' => 'REST / JQL exports'],
                'notes' => ['de' => 'JQL-Exports und /rest/api/3/* verdoppeln User- und Issue-PII in Stages.', 'en' => 'JQL exports and /rest/api/3/* duplicate user and issue PII into stages.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / site copies', 'en' => 'Sandbox / site copies'],
                'notes' => ['de' => 'Sandbox-Issues nicht mit Prod-Delivery-Marts mischen.', 'en' => 'Do not mix sandbox issues with prod delivery marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Issue description / ADF body (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Freitext-PII und Volumen — nur mit Use Case.', 'en' => 'Free-text PII and volume — only with a use case.'],
            ],
            [
                'name' => 'Comment / worklog text bodies',
                'category' => 'system',
                'reason' => ['de' => 'Unstrukturierte PII — Metadata (author, created) reicht oft.', 'en' => 'Unstructured PII — metadata (author, created) often enough.'],
            ],
            [
                'name' => 'Attachment binaries',
                'category' => 'system',
                'reason' => ['de' => 'Binaries — teuer, selten KPI-relevant.', 'en' => 'Binaries — expensive, rarely KPI-relevant.'],
            ],
            [
                'name' => 'Audit log / webhook delivery history (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Ops-Telemetrie — kein Delivery-Mart-Kern.', 'en' => 'Ops telemetry — not delivery mart core.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Issue description / comment cleartext (bulk)',
                'reason' => ['de' => 'PII — gezielt oder gar nicht.', 'en' => 'PII — selectively or not at all.'],
            ],
            [
                'name' => 'Unused custom fields (bulk sync all)',
                'reason' => ['de' => 'Vergrößert DSDR-Fläche und Sync-Kosten.', 'en' => 'Expands DSDR surface and sync cost.'],
            ],
            [
                'name' => 'Attachment content',
                'reason' => ['de' => 'Kosten ohne KPI-Nutzen.', 'en' => 'Cost without KPI value.'],
            ],
            [
                'name' => 'User email cleartext in marts',
                'reason' => ['de' => 'accountId reicht für Assignee-KPIs.', 'en' => 'accountId is enough for assignee KPIs.'],
            ],
            [
                'name' => 'Sandbox / test project issues',
                'reason' => ['de' => 'Prod-Delivery-Marts sauber halten.', 'en' => 'Keep prod delivery marts clean.'],
            ],
        ],
    ],

    'confluence' => [
        'pii' => [
            [
                'entity' => 'User / Atlassian account',
                'fields' => ['accountId', 'displayName', 'email', 'profilePicture'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Confluence-Nutzer — email nur mit Legal-Basis; accountId als Key.', 'en' => 'Confluence users — email only with legal basis; accountId as key.'],
            ],
            [
                'entity' => 'Page author / lastModifier',
                'fields' => ['authorId', 'ownerId', 'lastModifiedBy', 'version.authorId'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: accountId only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: accountId only; Mart: aggregates only'],
                'treatment' => ['de' => 'Autoren als accountId — keine Klartext-Namen in Default-Marts.', 'en' => 'Authors as accountId — no cleartext names in default marts.'],
            ],
            [
                'entity' => 'Page / blog body',
                'fields' => ['body.storage', 'body.atlas_doc_format', 'excerpt'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Seitenkörper können Personenbezug und Geheimnisse enthalten — Default skip.', 'en' => 'Page bodies can contain personal data and secrets — default skip.'],
            ],
            [
                'entity' => 'Comments / inline comments',
                'fields' => ['comment.body', 'inlineComment.text'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Kommentare — ungeprüfte PII; Metadata reicht für Activity-KPIs.', 'en' => 'Comments — unchecked PII; metadata enough for activity KPIs.'],
            ],
            [
                'entity' => 'Space permissions / members',
                'fields' => ['space permissions principals', 'group memberships', 'guest access emails'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: aggregates only; Mart: never cleartext', 'en' => 'RAW: workforce policy; Curated: aggregates only; Mart: never cleartext'],
                'treatment' => ['de' => 'Permissions zeigen Org-Struktur — eng zugreifen; nicht in öffentlichen Marts.', 'en' => 'Permissions reveal org structure — tight access; not in public marts.'],
            ],
            [
                'entity' => 'Attachments / labels with PII',
                'fields' => ['attachment.title', 'attachment.downloadLink', 'label names with emails'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Dateinamen und Labels können E-Mails/Namen tragen — inventarisieren.', 'en' => 'Filenames and labels can carry emails/names — inventory them.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'accountId, email, contentId, spaceId, pageId, external content properties.', 'en' => 'accountId, email, contentId, spaceId, pageId, external content properties.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Page, Blogpost, Space, Comment, Attachment + Content Properties.', 'en' => 'Page, blogpost, space, comment, attachment + content properties.'],
            ],
            [
                'focus' => ['de' => 'REST content exports', 'en' => 'REST content exports'],
                'notes' => ['de' => 'Content REST und Space-Exports kopieren Bodies und Autoren-PII.', 'en' => 'Content REST and space exports copy bodies and author PII.'],
            ],
            [
                'focus' => ['de' => 'Personal / restricted spaces', 'en' => 'Personal / restricted spaces'],
                'notes' => ['de' => 'Persönliche Spaces und Restricted Pages — eigene Retention und Zugriff.', 'en' => 'Personal spaces and restricted pages — separate retention and access.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Page / blog body storage (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Freitext und Secrets — Metadata für Knowledge-KPIs reicht oft.', 'en' => 'Free text and secrets — metadata often enough for knowledge KPIs.'],
            ],
            [
                'name' => 'Comment bodies',
                'category' => 'system',
                'reason' => ['de' => 'Unstrukturierte PII — Activity ohne Text.', 'en' => 'Unstructured PII — activity without text.'],
            ],
            [
                'name' => 'Attachment binaries',
                'category' => 'system',
                'reason' => ['de' => 'Binaries — teuer und sensibel.', 'en' => 'Binaries — expensive and sensitive.'],
            ],
            [
                'name' => 'Trash / historical version blobs (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Historische Bodies — Retention und Volumen.', 'en' => 'Historical bodies — retention and volume.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Page body cleartext in marts',
                'reason' => ['de' => 'Default skip — nur mit dokumentiertem Use Case.', 'en' => 'Default skip — only with documented use case.'],
            ],
            [
                'name' => 'Personal space content (bulk)',
                'reason' => ['de' => 'Private Workforce-PII.', 'en' => 'Private workforce PII.'],
            ],
            [
                'name' => 'Attachment content',
                'reason' => ['de' => 'Kosten ohne KPI-Nutzen.', 'en' => 'Cost without KPI value.'],
            ],
            [
                'name' => 'Unused content properties (bulk)',
                'reason' => ['de' => 'DSDR ohne Consumer.', 'en' => 'DSDR without consumers.'],
            ],
            [
                'name' => 'Sandbox / draft test pages',
                'reason' => ['de' => 'Prod-Knowledge-Marts sauber halten.', 'en' => 'Keep prod knowledge marts clean.'],
            ],
        ],
    ],

    'slack' => [
        'pii' => [
            [
                'entity' => 'User profile',
                'fields' => ['id', 'name', 'real_name', 'profile.email', 'profile.phone', 'profile.image_*'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: user_id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: user_id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Slack-User — email/phone nur mit Legal-Basis; user_id als Key.', 'en' => 'Slack users — email/phone only with legal basis; user_id as key.'],
            ],
            [
                'entity' => 'Message text / files',
                'fields' => ['text', 'blocks', 'attachments', 'file content'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: nie Klartext; Mart: nie', 'en' => 'RAW: rarely load; Curated: never cleartext; Mart: never'],
                'treatment' => ['de' => 'Message Bodies default nicht ins Warehouse — Activity-Facts ohne Text.', 'en' => 'Do not load message bodies into the warehouse by default — activity facts without text.'],
            ],
            [
                'entity' => 'DMs / MPIM',
                'fields' => ['im channel members', 'mpim members', 'dm message text'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: drop; Mart: nie', 'en' => 'RAW: restrict access; Curated: drop; Mart: never'],
                'treatment' => ['de' => 'DMs sind hochsensibel — Default out of scope für Analytics.', 'en' => 'DMs are highly sensitive — default out of scope for analytics.'],
            ],
            [
                'entity' => 'Channel membership',
                'fields' => ['members', 'user_id lists', 'guest invites'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: aggregates / hashed; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: aggregates / hashed; Mart: aggregates only'],
                'treatment' => ['de' => 'Membership zeigt Org-Netzwerke — eng zugreifen.', 'en' => 'Membership reveals org networks — tight access.'],
            ],
            [
                'entity' => 'Enterprise Grid discovery / SCIM',
                'fields' => ['SCIM emails', 'org user directories', 'IdP attributes'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate; Mart: aggregates only'],
                'treatment' => ['de' => 'Grid/SCIM-Verzeichnisse — eigene Retention vs. Channel-Facts.', 'en' => 'Grid/SCIM directories — separate retention vs channel facts.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'user_id, team_id, channel_id, email (hashed), enterprise_id.', 'en' => 'user_id, team_id, channel_id, email (hashed), enterprise_id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'User, Channel, Membership, Message metadata (ohne Text), Files metadata.', 'en' => 'User, channel, membership, message metadata (no text), files metadata.'],
            ],
            [
                'focus' => ['de' => 'Conversations / Admin analytics exports', 'en' => 'Conversations / Admin analytics exports'],
                'notes' => ['de' => 'conversations.history und Discovery Exports verdoppeln Message-PII — Avoid.', 'en' => 'conversations.history and Discovery exports duplicate message PII — avoid.'],
            ],
            [
                'focus' => ['de' => 'Retention / legal hold copies', 'en' => 'Retention / legal hold copies'],
                'notes' => ['de' => 'Compliance-Exports sind DSDR-Kopien — nicht in Analytics-Marts mischen.', 'en' => 'Compliance exports are DSDR copies — do not mix into analytics marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Message text / blocks (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Klartext-Messages — Default nie in Warehouse-Marts.', 'en' => 'Cleartext messages — default never in warehouse marts.'],
            ],
            [
                'name' => 'DM / MPIM history',
                'category' => 'system',
                'reason' => ['de' => 'Hochsensible Private Chats — out of scope.', 'en' => 'Highly sensitive private chats — out of scope.'],
            ],
            [
                'name' => 'File binaries / snippet content',
                'category' => 'system',
                'reason' => ['de' => 'Binaries und Code-Snippets — teuer und PII-riskant.', 'en' => 'Binaries and snippets — expensive and PII-risky.'],
            ],
            [
                'name' => 'Discovery / legal hold full export',
                'category' => 'system',
                'reason' => ['de' => 'Compliance-Only — nicht für Business-KPIs.', 'en' => 'Compliance-only — not for business KPIs.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Message body cleartext in curated marts',
                'reason' => ['de' => 'Nur message_count / activity aggregates.', 'en' => 'Only message_count / activity aggregates.'],
            ],
            [
                'name' => 'DM / private channel content',
                'reason' => ['de' => 'Nie ohne Legal/Compliance-Freigabe.', 'en' => 'Never without legal/compliance approval.'],
            ],
            [
                'name' => 'User email / phone cleartext',
                'reason' => ['de' => 'user_id reicht für Activity-KPIs.', 'en' => 'user_id is enough for activity KPIs.'],
            ],
            [
                'name' => 'Unused custom profile fields (bulk)',
                'reason' => ['de' => 'DSDR-Fläche.', 'en' => 'DSDR surface.'],
            ],
            [
                'name' => 'Bot / test workspace noise channels',
                'reason' => ['de' => 'Prod-Activity-Marts sauber halten.', 'en' => 'Keep prod activity marts clean.'],
            ],
        ],
    ],

    'microsoft-teams' => [
        'pii' => [
            [
                'entity' => 'User / Entra ID',
                'fields' => ['id', 'userPrincipalName', 'mail', 'displayName', 'mobilePhone'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: user_id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: user_id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Teams/Entra-User — UPN/mail nur mit Legal-Basis; id als Key.', 'en' => 'Teams/Entra users — UPN/mail only with legal basis; id as key.'],
            ],
            [
                'entity' => 'Chat / channel message body',
                'fields' => ['body.content', 'attachments', 'mentions'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: nie Klartext; Mart: nie', 'en' => 'RAW: rarely load; Curated: never cleartext; Mart: never'],
                'treatment' => ['de' => 'Message Bodies default nicht speichern — Activity-Facts ohne Text.', 'en' => 'Do not store message bodies by default — activity facts without text.'],
            ],
            [
                'entity' => '1:1 / group chat',
                'fields' => ['chat members', 'chat message body', 'chatTopic'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: drop; Mart: nie', 'en' => 'RAW: restrict access; Curated: drop; Mart: never'],
                'treatment' => ['de' => 'Private Chats — Default out of scope für Analytics.', 'en' => 'Private chats — default out of scope for analytics.'],
            ],
            [
                'entity' => 'Team membership / owners',
                'fields' => ['members', 'owners', 'guest users'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: aggregates / hashed; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: aggregates / hashed; Mart: aggregates only'],
                'treatment' => ['de' => 'Membership zeigt Org-Struktur — eng zugreifen.', 'en' => 'Membership reveals org structure — tight access.'],
            ],
            [
                'entity' => 'Purview / retention exports',
                'fields' => ['eDiscovery content', 'retention labeled items', 'compliance copies'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: nie in Analytics; Mart: nie', 'en' => 'RAW: rarely load; Curated: never in analytics; Mart: never'],
                'treatment' => ['de' => 'Purview-Exports sind Compliance — nicht mit Activity-Marts mischen.', 'en' => 'Purview exports are compliance — do not mix with activity marts.'],
            ],
            [
                'entity' => 'Meeting recordings / transcripts',
                'fields' => ['recording content', 'transcript text', 'attendance reports with names'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Aufnahmen/Transkripte — Default skip; Attendance nur aggregiert.', 'en' => 'Recordings/transcripts — default skip; attendance aggregates only.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'user id, userPrincipalName, teamId, channelId, chatId, tenantId.', 'en' => 'user id, userPrincipalName, teamId, channelId, chatId, tenantId.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Team, Channel, Membership, Message metadata (ohne Body), Meeting metadata.', 'en' => 'Team, channel, membership, message metadata (no body), meeting metadata.'],
            ],
            [
                'focus' => ['de' => 'Microsoft Graph exports', 'en' => 'Microsoft Graph exports'],
                'notes' => ['de' => 'Graph messages/chats und Change Notifications können Bodies liefern — Allowlist.', 'en' => 'Graph messages/chats and change notifications can deliver bodies — allowlist.'],
            ],
            [
                'focus' => ['de' => 'Purview / eDiscovery copies', 'en' => 'Purview / eDiscovery copies'],
                'notes' => ['de' => 'Compliance-Kopien strikt von Analytics-Stages trennen.', 'en' => 'Strictly separate compliance copies from analytics stages.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Chat / channel message bodies (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Klartext — Default nie in Warehouse-Marts.', 'en' => 'Cleartext — default never in warehouse marts.'],
            ],
            [
                'name' => '1:1 and group chat history',
                'category' => 'system',
                'reason' => ['de' => 'Private Chats — out of scope für Analytics.', 'en' => 'Private chats — out of scope for analytics.'],
            ],
            [
                'name' => 'Meeting recordings / transcript blobs',
                'category' => 'system',
                'reason' => ['de' => 'Medien und Freitext — teuer und sensibel.', 'en' => 'Media and free text — expensive and sensitive.'],
            ],
            [
                'name' => 'Purview eDiscovery export payloads',
                'category' => 'system',
                'reason' => ['de' => 'Compliance-Only — nicht Business-KPI.', 'en' => 'Compliance-only — not business KPI.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Message body cleartext in curated marts',
                'reason' => ['de' => 'Nur message_count / activity aggregates.', 'en' => 'Only message_count / activity aggregates.'],
            ],
            [
                'name' => 'Private chat content',
                'reason' => ['de' => 'Nie ohne Legal/Compliance-Freigabe.', 'en' => 'Never without legal/compliance approval.'],
            ],
            [
                'name' => 'UPN / mail cleartext in marts',
                'reason' => ['de' => 'user id reicht für Activity-KPIs.', 'en' => 'user id is enough for activity KPIs.'],
            ],
            [
                'name' => 'Meeting transcript / recording content',
                'reason' => ['de' => 'Default skip.', 'en' => 'Default skip.'],
            ],
            [
                'name' => 'Test teams / guest spam channels',
                'reason' => ['de' => 'Prod-Activity-Marts sauber halten.', 'en' => 'Keep prod activity marts clean.'],
            ],
        ],
    ],
];
