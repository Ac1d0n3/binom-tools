<?php

/**
 * Wave 5 supplier library entries — Workplace / Identity (full template depth).
 *
 * Emphasize metadata facts; do not load file/mail/chat bodies or credential secrets by default.
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $workplaceTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    return [
        [
            'id' => 'entra-id',
            'domain' => 'workplace',
            'order' => 190,
            'label' => ['de' => 'Microsoft Entra ID', 'en' => 'Microsoft Entra ID'],
            'shortPurpose' => [
                'de' => 'Workforce Identity: User/Group/App, Sign-in- und Audit-Meta — Graph/Entra-Load, PII und Identity-Measures.',
                'en' => 'Workforce identity: user/group/app, sign-in and audit meta — Graph/Entra load, PII and identity measures.',
            ],
            'entities' => [
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'Entra User — Dimension; UPN, mail, mobile, employeeId sind Workforce-PII.',
                        'en' => 'Entra user — dimension; UPN, mail, mobile, employeeId are workforce PII.',
                    ],
                    'grain' => ['de' => 'Ein User (id / objectId)', 'en' => 'One user (id / objectId)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'group',
                    'label' => ['de' => 'Group', 'en' => 'Group'],
                    'description' => [
                        'de' => 'Security/M365 Group — Membership-Join und Access-Dimension.',
                        'en' => 'Security/M365 group — membership join and access dimension.',
                    ],
                    'grain' => ['de' => 'Eine Group (id)', 'en' => 'One group (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'directory_role',
                    'label' => ['de' => 'Directory Role', 'en' => 'Directory role'],
                    'description' => [
                        'de' => 'Directory Role / Role Assignment — Privileged-Access-Fact.',
                        'en' => 'Directory role / role assignment — privileged-access fact.',
                    ],
                    'grain' => ['de' => 'Eine Role Assignment', 'en' => 'One role assignment'],
                    'role' => ['de' => 'Access-Fact', 'en' => 'Access fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'application',
                    'label' => ['de' => 'Application', 'en' => 'Application'],
                    'description' => [
                        'de' => 'App Registration — appId, displayName; Secrets nicht laden.',
                        'en' => 'App registration — appId, displayName; do not load secrets.',
                    ],
                    'grain' => ['de' => 'Eine Application (appId / id)', 'en' => 'One application (appId / id)'],
                    'role' => ['de' => 'App-Dimension', 'en' => 'App dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'service_principal',
                    'label' => ['de' => 'Service Principal', 'en' => 'Service principal'],
                    'description' => [
                        'de' => 'Enterprise App / SP — App-Typ und Sign-in-Join.',
                        'en' => 'Enterprise app / SP — app type and sign-in join.',
                    ],
                    'grain' => ['de' => 'Ein Service Principal (id)', 'en' => 'One service principal (id)'],
                    'role' => ['de' => 'App-Dimension', 'en' => 'App dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'sign_in_log',
                    'label' => ['de' => 'Sign-in Log (agg)', 'en' => 'Sign-in log (agg)'],
                    'description' => [
                        'de' => 'Sign-in Meta/Aggregate — Counts und Status; volle Dumps sampeln/aggregieren.',
                        'en' => 'Sign-in meta/aggregate — counts and status; sample/aggregate full dumps.',
                    ],
                    'grain' => ['de' => 'Ein Sign-in Event oder Tages-Agg', 'en' => 'One sign-in event or daily agg'],
                    'role' => ['de' => 'Auth-Fact (high volume)', 'en' => 'Auth fact (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'audit_log',
                    'label' => ['de' => 'Audit Log (meta)', 'en' => 'Audit log (meta)'],
                    'description' => [
                        'de' => 'Directory Audit — activity, actor, target; kein Ultra-Verbose Dump.',
                        'en' => 'Directory audit — activity, actor, target; no ultra-verbose dump.',
                    ],
                    'grain' => ['de' => 'Ein Audit Event (meta)', 'en' => 'One audit event (meta)'],
                    'role' => ['de' => 'Governance-Fact', 'en' => 'Governance fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'device',
                    'label' => ['de' => 'Device', 'en' => 'Device'],
                    'description' => [
                        'de' => 'Registered/Joined Device — Compliance und Ownership-Join.',
                        'en' => 'Registered/joined device — compliance and ownership join.',
                    ],
                    'grain' => ['de' => 'Ein Device (deviceId)', 'en' => 'One device (deviceId)'],
                    'role' => ['de' => 'Device-Dimension', 'en' => 'Device dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'User', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Graph objectId / Join-Key', 'en' => 'Graph objectId / join key']],
                ['entity' => 'User', 'name' => 'userPrincipalName', 'role' => 'pii', 'why' => ['de' => 'UPN / Workforce-PII', 'en' => 'UPN / workforce PII']],
                ['entity' => 'User', 'name' => 'mail', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'User', 'name' => 'mobilePhone', 'role' => 'pii', 'why' => ['de' => 'Mobile / PII', 'en' => 'Mobile / PII']],
                ['entity' => 'User', 'name' => 'employeeId', 'role' => 'pii', 'why' => ['de' => 'Mitarbeiter-ID / PII', 'en' => 'Employee ID / PII']],
                ['entity' => 'User', 'name' => 'accountEnabled', 'role' => 'dimension', 'why' => ['de' => 'Aktiv vs. disabled', 'en' => 'Active vs disabled']],
                ['entity' => 'User', 'name' => 'createdDateTime', 'role' => 'measure', 'why' => ['de' => 'Provisioning-Zeitpunkt', 'en' => 'Provisioning timestamp']],
                ['entity' => 'User', 'name' => 'department', 'role' => 'dimension', 'why' => ['de' => 'Abteilung / Org-Dim', 'en' => 'Department / org dim']],
                ['entity' => 'User', 'name' => 'jobTitle', 'role' => 'dimension', 'why' => ['de' => 'Job Title / Dim', 'en' => 'Job title / dim']],
                ['entity' => 'User', 'name' => 'country', 'role' => 'dimension', 'why' => ['de' => 'Land / Country-Dim', 'en' => 'Country / country dim']],
                ['entity' => 'Group', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Group-Join', 'en' => 'Group join']],
                ['entity' => 'Group', 'name' => 'displayName', 'role' => 'dimension', 'why' => ['de' => 'Gruppenname', 'en' => 'Group display name']],
                ['entity' => 'Group', 'name' => 'securityEnabled', 'role' => 'dimension', 'why' => ['de' => 'Security vs. Distribution', 'en' => 'Security vs distribution']],
                ['entity' => 'DirectoryRole', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Role / Assignment-Key', 'en' => 'Role / assignment key']],
                ['entity' => 'DirectoryRole', 'name' => 'displayName', 'role' => 'dimension', 'why' => ['de' => 'Rollenname (Global Admin …)', 'en' => 'Role name (Global Admin …)']],
                ['entity' => 'DirectoryRole', 'name' => 'principalId', 'role' => 'dimension', 'why' => ['de' => 'User/SP-Join auf Assignment', 'en' => 'User/SP join on assignment']],
                ['entity' => 'Application', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Application objectId', 'en' => 'Application objectId']],
                ['entity' => 'Application', 'name' => 'appId', 'role' => 'key', 'why' => ['de' => 'Client/App-ID (GUID)', 'en' => 'Client/app ID (GUID)']],
                ['entity' => 'Application', 'name' => 'displayName', 'role' => 'dimension', 'why' => ['de' => 'App-Name', 'en' => 'App name']],
                ['entity' => 'Application', 'name' => 'createdDateTime', 'role' => 'measure', 'why' => ['de' => 'Registrierungszeitpunkt', 'en' => 'Registration timestamp']],
                ['entity' => 'ServicePrincipal', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'SP objectId', 'en' => 'SP objectId']],
                ['entity' => 'ServicePrincipal', 'name' => 'appId', 'role' => 'dimension', 'why' => ['de' => 'Application-Rückjoin', 'en' => 'Application back-join']],
                ['entity' => 'ServicePrincipal', 'name' => 'servicePrincipalType', 'role' => 'dimension', 'why' => ['de' => 'App-Typ (Application / ManagedIdentity …)', 'en' => 'App type (Application / ManagedIdentity …)']],
                ['entity' => 'SignInLog', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Sign-in Event-ID (oder Agg-Key)', 'en' => 'Sign-in event ID (or agg key)']],
                ['entity' => 'SignInLog', 'name' => 'createdDateTime', 'role' => 'measure', 'why' => ['de' => 'Sign-in-Zeit / Perioden-Grain', 'en' => 'Sign-in time / period grain']],
                ['entity' => 'SignInLog', 'name' => 'status.errorCode', 'role' => 'dimension', 'why' => ['de' => '0 = success; sonst Failure-Code', 'en' => '0 = success; else failure code']],
                ['entity' => 'SignInLog', 'name' => 'userId', 'role' => 'dimension', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'SignInLog', 'name' => 'appId', 'role' => 'dimension', 'why' => ['de' => 'App/SP-Join', 'en' => 'App/SP join']],
                ['entity' => 'AuditLog', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Audit Event-ID', 'en' => 'Audit event ID']],
                ['entity' => 'AuditLog', 'name' => 'activityDisplayName', 'role' => 'dimension', 'why' => ['de' => 'Aktivitätstyp', 'en' => 'Activity type']],
                ['entity' => 'AuditLog', 'name' => 'activityDateTime', 'role' => 'measure', 'why' => ['de' => 'Audit-Zeitpunkt', 'en' => 'Audit timestamp']],
                ['entity' => 'Device', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Device-Join', 'en' => 'Device join']],
                ['entity' => 'Device', 'name' => 'operatingSystem', 'role' => 'dimension', 'why' => ['de' => 'OS / Device-Dim', 'en' => 'OS / device dim']],
                ['entity' => 'Device', 'name' => 'accountEnabled', 'role' => 'dimension', 'why' => ['de' => 'Device enabled', 'en' => 'Device enabled']],
            ],
            'skipTables' => [
                [
                    'name' => 'Full sign-in log dumps (high volume)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Rohe Sign-in-Dumps — sampeln oder Tages-Aggregate; nicht Full-History in RAW.',
                        'en' => 'Raw sign-in dumps — sample or daily aggregates; not full history in RAW.',
                    ],
                ],
                [
                    'name' => 'Refresh token stores',
                    'category' => 'security',
                    'reason' => [
                        'de' => 'Refresh-/Session-Token-Stores — Secrets, kein Analytics-Nutzen.',
                        'en' => 'Refresh/session token stores — secrets, no analytics value.',
                    ],
                ],
                [
                    'name' => 'Credential secrets (app passwords, certificates)',
                    'category' => 'security',
                    'reason' => [
                        'de' => 'Client Secrets, Zertifikat-Blobs, Passwords — nie in Warehouse.',
                        'en' => 'Client secrets, certificate blobs, passwords — never into the warehouse.',
                    ],
                ],
                [
                    'name' => 'B2C raw journey / user-flow bulk',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'B2C Journey-Rohdaten — Volumen und oft Consumer-PII; Meta/Agg bevorzugen.',
                        'en' => 'B2C journey raw data — volume and often consumer PII; prefer meta/agg.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Full sign-in log dumps', 'reason' => ['de' => 'High volume — sample/aggregate', 'en' => 'High volume — sample/aggregate']],
                ['name' => 'Refresh token stores', 'reason' => ['de' => 'Credential/Session-Secrets', 'en' => 'Credential/session secrets']],
                ['name' => 'App credential secrets & cert blobs', 'reason' => ['de' => 'Security — nie laden', 'en' => 'Security — never load']],
                ['name' => 'B2C raw user-flow journeys bulk', 'reason' => ['de' => 'Volumen + Consumer-PII', 'en' => 'Volume + consumer PII']],
            ],
            'dimensions' => [
                [
                    'id' => 'department',
                    'label' => ['de' => 'Department', 'en' => 'Department'],
                    'grain' => ['de' => 'user.department', 'en' => 'user.department'],
                    'notes' => [
                        'de' => 'HR-/Graph-Department; Nullen und Free-Text-Varianten normalisieren.',
                        'en' => 'HR/Graph department; normalize nulls and free-text variants.',
                    ],
                ],
                [
                    'id' => 'job_title',
                    'label' => ['de' => 'Job Title', 'en' => 'Job title'],
                    'grain' => ['de' => 'user.jobTitle', 'en' => 'user.jobTitle'],
                    'notes' => [
                        'de' => 'Für Privileged-Access-Schnitte; oft dirty — Mapping-Tabelle erwägen.',
                        'en' => 'For privileged-access slices; often dirty — consider a mapping table.',
                    ],
                ],
                [
                    'id' => 'account_enabled',
                    'label' => ['de' => 'Account Enabled', 'en' => 'Account enabled'],
                    'grain' => ['de' => 'user.accountEnabled (bool)', 'en' => 'user.accountEnabled (bool)'],
                    'notes' => [
                        'de' => 'Active-Users vs. Disabled-Users klar trennen.',
                        'en' => 'Clearly separate active vs disabled users.',
                    ],
                ],
                [
                    'id' => 'app_type',
                    'label' => ['de' => 'App Type', 'en' => 'App type'],
                    'grain' => ['de' => 'servicePrincipalType / application category', 'en' => 'servicePrincipalType / application category'],
                    'notes' => [
                        'de' => 'Application vs. ManagedIdentity vs. Legacy für Sign-in- und App-Counts.',
                        'en' => 'Application vs ManagedIdentity vs legacy for sign-in and app counts.',
                    ],
                ],
                [
                    'id' => 'country',
                    'label' => ['de' => 'Country', 'en' => 'Country'],
                    'grain' => ['de' => 'user.country / usageLocation', 'en' => 'user.country / usageLocation'],
                    'notes' => [
                        'de' => 'usageLocation vs. country bewusst wählen (Lizenz vs. Adresse).',
                        'en' => 'Consciously choose usageLocation vs country (license vs address).',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['userPrincipalName', 'mail'],
                    'treatment' => [
                        'de' => 'UPN und Mail — Workforce-PII taggen; objectId als Join bevorzugen.',
                        'en' => 'UPN and mail — tag as workforce PII; prefer objectId as join.',
                    ],
                ],
                [
                    'entity' => 'User',
                    'fields' => ['mobilePhone', 'businessPhones'],
                    'treatment' => [
                        'de' => 'Telefonnummern — PII; hashen/redigieren oder nicht laden.',
                        'en' => 'Phone numbers — PII; hash/redact or do not load.',
                    ],
                ],
                [
                    'entity' => 'User',
                    'fields' => ['employeeId'],
                    'treatment' => [
                        'de' => 'employeeId — Identifikator; Zugriff und Retention strikt.',
                        'en' => 'employeeId — identifier; strict access and retention.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'objectId, appId, userPrincipalName, employeeId, deviceId.',
                        'en' => 'objectId, appId, userPrincipalName, employeeId, deviceId.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'User, Group, Application, ServicePrincipal, Sign-in/Audit-Meta + Warehouse-Kopien.',
                        'en' => 'User, group, application, service principal, sign-in/audit meta + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'active-users',
                    'example' => true,
                    'label' => ['de' => 'Active Users', 'en' => 'Active users'],
                    'question' => [
                        'de' => 'Wie viele enabled Users gibt es (Snapshot oder Periode)?',
                        'en' => 'How many enabled users are there (snapshot or period)?',
                    ],
                    'formula' => "COUNT(*) FROM user WHERE accountEnabled = true",
                    'grain' => ['de' => 'Enabled User', 'en' => 'Enabled user'],
                    'dimensions' => ['department', 'job_title', 'account_enabled', 'country'],
                    'fieldsUsed' => ['User.id', 'User.accountEnabled', 'User.department', 'User.country'],
                    'sourceHints' => [
                        'de' => 'accountEnabled=true; Guest vs. Member (userType) in der Definition klären.',
                        'en' => 'accountEnabled=true; clarify guest vs member (userType) in the definition.',
                    ],
                    'adapt' => [
                        'de' => 'Optional: nur Users mit Sign-in in der Periode (activity-based).',
                        'en' => 'Optional: only users with a sign-in in the period (activity-based).',
                    ],
                ],
                [
                    'id' => 'disabled-users',
                    'example' => true,
                    'label' => ['de' => 'Disabled Users', 'en' => 'Disabled users'],
                    'question' => [
                        'de' => 'Wie viele Accounts sind deaktiviert?',
                        'en' => 'How many accounts are disabled?',
                    ],
                    'formula' => "COUNT(*) FROM user WHERE accountEnabled = false",
                    'grain' => ['de' => 'Disabled User', 'en' => 'Disabled user'],
                    'dimensions' => ['department', 'job_title', 'country'],
                    'fieldsUsed' => ['User.id', 'User.accountEnabled', 'User.department'],
                    'sourceHints' => [
                        'de' => 'Gegenstück zu active-users; Soft-Delete (deletedDateTime) separat behandeln.',
                        'en' => 'Counterpart to active-users; treat soft-delete (deletedDateTime) separately.',
                    ],
                    'adapt' => [
                        'de' => 'Stale disabled (lange inaktiv) vs. frisch deaktiviert trennen.',
                        'en' => 'Separate stale disabled (long inactive) vs freshly disabled.',
                    ],
                ],
                [
                    'id' => 'sign-ins-count',
                    'example' => false,
                    'label' => ['de' => 'Sign-ins Count', 'en' => 'Sign-ins count'],
                    'question' => [
                        'de' => 'Wie viele Sign-ins gab es in der Periode?',
                        'en' => 'How many sign-ins occurred in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM sign_in_log WHERE createdDateTime IN period',
                    'grain' => ['de' => 'Sign-in Event (oder Tages-Agg)', 'en' => 'Sign-in event (or daily agg)'],
                    'dimensions' => ['department', 'app_type', 'country'],
                    'fieldsUsed' => ['SignInLog.id', 'SignInLog.createdDateTime', 'SignInLog.userId', 'SignInLog.appId'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Aggregat-Tabelle nutzen; Interactive vs. Non-interactive filtern.',
                        'en' => 'Use aggregate table at high volume; filter interactive vs non-interactive.',
                    ],
                    'adapt' => [
                        'de' => 'Successful-only (errorCode=0) vs. alle Attempts festlegen.',
                        'en' => 'Lock successful-only (errorCode=0) vs all attempts.',
                    ],
                ],
                [
                    'id' => 'failed-sign-ins',
                    'example' => false,
                    'label' => ['de' => 'Failed Sign-ins', 'en' => 'Failed sign-ins'],
                    'question' => [
                        'de' => 'Wie viele fehlgeschlagene Sign-ins gab es in der Periode?',
                        'en' => 'How many failed sign-ins occurred in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM sign_in_log WHERE status.errorCode <> 0 AND createdDateTime IN period',
                    'grain' => ['de' => 'Failed Sign-in', 'en' => 'Failed sign-in'],
                    'dimensions' => ['department', 'app_type', 'country'],
                    'fieldsUsed' => ['SignInLog.status.errorCode', 'SignInLog.createdDateTime', 'SignInLog.userId'],
                    'sourceHints' => [
                        'de' => 'errorCode != 0; conditionalAccess-Status optional als Dim.',
                        'en' => 'errorCode != 0; optional conditionalAccess status as dim.',
                    ],
                    'adapt' => [
                        'de' => 'Brute-force / MFA-deny Codes getrennt tracken.',
                        'en' => 'Track brute-force / MFA-deny codes separately.',
                    ],
                ],
                [
                    'id' => 'apps-registered',
                    'example' => false,
                    'label' => ['de' => 'Apps Registered', 'en' => 'Apps registered'],
                    'question' => [
                        'de' => 'Wie viele Applications sind registriert (oder neu in der Periode)?',
                        'en' => 'How many applications are registered (or new in the period)?',
                    ],
                    'formula' => 'COUNT(*) FROM application WHERE createdDateTime IN period OR snapshot',
                    'grain' => ['de' => 'Application', 'en' => 'Application'],
                    'dimensions' => ['app_type', 'country'],
                    'fieldsUsed' => ['Application.id', 'Application.appId', 'Application.createdDateTime'],
                    'sourceHints' => [
                        'de' => 'App Registration vs. Service Principal nicht doppelzählen — Definition locken.',
                        'en' => 'Do not double-count app registration vs service principal — lock definition.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Enterprise Apps mit Sign-in-Aktivität als „active apps“ Variante.',
                        'en' => 'Variant: only enterprise apps with sign-in activity as “active apps”.',
                    ],
                ],
            ],
            'tools' => $workplaceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'github',
            'domain' => 'workplace',
            'order' => 200,
            'label' => ['de' => 'GitHub', 'en' => 'GitHub'],
            'shortPurpose' => [
                'de' => 'Dev Platform: Repo/PR/Issue/Workflow-Meta — API-Load, PII und Delivery-Measures; kein Source-Code-Dump.',
                'en' => 'Dev platform: repo/PR/issue/workflow meta — API load, PII and delivery measures; no source-code dump.',
            ],
            'entities' => [
                [
                    'id' => 'repository',
                    'label' => ['de' => 'Repository', 'en' => 'Repository'],
                    'description' => [
                        'de' => 'GitHub Repository — full_name, visibility; Prefer Meta, nicht Blob/Tree.',
                        'en' => 'GitHub repository — full_name, visibility; prefer meta, not blob/tree.',
                    ],
                    'grain' => ['de' => 'Ein Repository (id / full_name)', 'en' => 'One repository (id / full_name)'],
                    'role' => ['de' => 'Dimension / Fact-Anker', 'en' => 'Dimension / fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'GitHub User — login; email nur wenn exponiert (PII).',
                        'en' => 'GitHub user — login; email only if exposed (PII).',
                    ],
                    'grain' => ['de' => 'Ein User (id / login)', 'en' => 'One user (id / login)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'organization',
                    'label' => ['de' => 'Organization', 'en' => 'Organization'],
                    'description' => [
                        'de' => 'Org — Tenant-Dimension für Repos und Teams.',
                        'en' => 'Org — tenant dimension for repos and teams.',
                    ],
                    'grain' => ['de' => 'Eine Organization (login)', 'en' => 'One organization (login)'],
                    'role' => ['de' => 'Org-Dimension', 'en' => 'Org dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'pull_request',
                    'label' => ['de' => 'Pull Request', 'en' => 'Pull request'],
                    'description' => [
                        'de' => 'PR — state, merged_at, additions/deletions; Diff-Bodies nicht laden.',
                        'en' => 'PR — state, merged_at, additions/deletions; do not load diff bodies.',
                    ],
                    'grain' => ['de' => 'Ein Pull Request (number @ repo)', 'en' => 'One pull request (number @ repo)'],
                    'role' => ['de' => 'Delivery-Fact', 'en' => 'Delivery fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'issue',
                    'label' => ['de' => 'Issue', 'en' => 'Issue'],
                    'description' => [
                        'de' => 'Issue — state, opened/closed; Body-Text optional/redacted.',
                        'en' => 'Issue — state, opened/closed; body text optional/redacted.',
                    ],
                    'grain' => ['de' => 'Ein Issue (number @ repo)', 'en' => 'One issue (number @ repo)'],
                    'role' => ['de' => 'Work-Fact', 'en' => 'Work fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'commit_meta',
                    'label' => ['de' => 'Commit Meta', 'en' => 'Commit meta'],
                    'description' => [
                        'de' => 'Commit-SHA, Author, Stats — Patch/Diff-Bodies skippen.',
                        'en' => 'Commit SHA, author, stats — skip patch/diff bodies.',
                    ],
                    'grain' => ['de' => 'Ein Commit (sha)', 'en' => 'One commit (sha)'],
                    'role' => ['de' => 'Meta-Fact', 'en' => 'Meta fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'workflow_run',
                    'label' => ['de' => 'Workflow Run', 'en' => 'Workflow run'],
                    'description' => [
                        'de' => 'Actions Run — conclusion, workflow; Logs bulk nicht laden.',
                        'en' => 'Actions run — conclusion, workflow; do not load log bulk.',
                    ],
                    'grain' => ['de' => 'Ein Workflow Run (id)', 'en' => 'One workflow run (id)'],
                    'role' => ['de' => 'CI-Fact', 'en' => 'CI fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'team',
                    'label' => ['de' => 'Team', 'en' => 'Team'],
                    'description' => [
                        'de' => 'Org Team — Membership und Repo-Permissions-Join.',
                        'en' => 'Org team — membership and repo permissions join.',
                    ],
                    'grain' => ['de' => 'Ein Team (slug @ org)', 'en' => 'One team (slug @ org)'],
                    'role' => ['de' => 'Access-Dimension', 'en' => 'Access dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Repository', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Repo-Join (numeric id)', 'en' => 'Repo join (numeric id)']],
                ['entity' => 'Repository', 'name' => 'full_name', 'role' => 'dimension', 'why' => ['de' => 'owner/name — Repo-Dim', 'en' => 'owner/name — repo dim']],
                ['entity' => 'Repository', 'name' => 'private', 'role' => 'dimension', 'why' => ['de' => 'Visibility private/public', 'en' => 'Visibility private/public']],
                ['entity' => 'Repository', 'name' => 'default_branch', 'role' => 'dimension', 'why' => ['de' => 'Default Branch', 'en' => 'Default branch']],
                ['entity' => 'Repository', 'name' => 'pushed_at', 'role' => 'measure', 'why' => ['de' => 'Letzte Push-Aktivität', 'en' => 'Last push activity']],
                ['entity' => 'User', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'User', 'name' => 'login', 'role' => 'dimension', 'why' => ['de' => 'GitHub Handle / Author-Dim', 'en' => 'GitHub handle / author dim']],
                ['entity' => 'User', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'E-Mail falls exponiert / PII', 'en' => 'Email if exposed / PII']],
                ['entity' => 'Organization', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Org-Join', 'en' => 'Org join']],
                ['entity' => 'Organization', 'name' => 'login', 'role' => 'dimension', 'why' => ['de' => 'Org-Slug / Org-Dim', 'en' => 'Org slug / org dim']],
                ['entity' => 'PullRequest', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'PR node/id', 'en' => 'PR node/id']],
                ['entity' => 'PullRequest', 'name' => 'number', 'role' => 'key', 'why' => ['de' => 'PR-Nummer @ Repo', 'en' => 'PR number @ repo']],
                ['entity' => 'PullRequest', 'name' => 'state', 'role' => 'dimension', 'why' => ['de' => 'open / closed', 'en' => 'open / closed']],
                ['entity' => 'PullRequest', 'name' => 'merged_at', 'role' => 'measure', 'why' => ['de' => 'Merge-Zeitpunkt', 'en' => 'Merge timestamp']],
                ['entity' => 'PullRequest', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Opened-Zeitpunkt', 'en' => 'Opened timestamp']],
                ['entity' => 'PullRequest', 'name' => 'additions', 'role' => 'measure', 'why' => ['de' => 'LOC additions (Meta)', 'en' => 'LOC additions (meta)']],
                ['entity' => 'PullRequest', 'name' => 'deletions', 'role' => 'measure', 'why' => ['de' => 'LOC deletions (Meta)', 'en' => 'LOC deletions (meta)']],
                ['entity' => 'PullRequest', 'name' => 'user.login', 'role' => 'dimension', 'why' => ['de' => 'Author', 'en' => 'Author']],
                ['entity' => 'Issue', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Issue-Join', 'en' => 'Issue join']],
                ['entity' => 'Issue', 'name' => 'number', 'role' => 'key', 'why' => ['de' => 'Issue-Nummer @ Repo', 'en' => 'Issue number @ repo']],
                ['entity' => 'Issue', 'name' => 'state', 'role' => 'dimension', 'why' => ['de' => 'open / closed', 'en' => 'open / closed']],
                ['entity' => 'Issue', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Opened-Zeitpunkt', 'en' => 'Opened timestamp']],
                ['entity' => 'CommitMeta', 'name' => 'sha', 'role' => 'key', 'why' => ['de' => 'Commit-SHA', 'en' => 'Commit SHA']],
                ['entity' => 'CommitMeta', 'name' => 'author.email', 'role' => 'pii', 'why' => ['de' => 'Commit Author E-Mail / PII', 'en' => 'Commit author email / PII']],
                ['entity' => 'CommitMeta', 'name' => 'author.login', 'role' => 'dimension', 'why' => ['de' => 'Author Handle', 'en' => 'Author handle']],
                ['entity' => 'CommitMeta', 'name' => 'stats.additions', 'role' => 'measure', 'why' => ['de' => 'Commit additions (Meta)', 'en' => 'Commit additions (meta)']],
                ['entity' => 'WorkflowRun', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Run-Join', 'en' => 'Run join']],
                ['entity' => 'WorkflowRun', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Workflow-Name', 'en' => 'Workflow name']],
                ['entity' => 'WorkflowRun', 'name' => 'conclusion', 'role' => 'dimension', 'why' => ['de' => 'success / failure / cancelled', 'en' => 'success / failure / cancelled']],
                ['entity' => 'WorkflowRun', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'queued / in_progress / completed', 'en' => 'queued / in_progress / completed']],
                ['entity' => 'WorkflowRun', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Run-Start', 'en' => 'Run start']],
                ['entity' => 'Team', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Team-Join', 'en' => 'Team join']],
                ['entity' => 'Team', 'name' => 'slug', 'role' => 'dimension', 'why' => ['de' => 'Team-Slug', 'en' => 'Team slug']],
                ['entity' => 'Team', 'name' => 'organization.login', 'role' => 'dimension', 'why' => ['de' => 'Org-Rückjoin', 'en' => 'Org back-join']],
            ],
            'skipTables' => [
                [
                    'name' => 'Commit patch / diff bodies',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Patch/Diff-Bodies — Source-Code; nur Meta/Stats laden.',
                        'en' => 'Patch/diff bodies — source code; load meta/stats only.',
                    ],
                ],
                [
                    'name' => 'Actions logs bulk',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Workflow-Log-Streams — hohes Volumen, wenig Mart-Nutzen.',
                        'en' => 'Workflow log streams — high volume, little mart value.',
                    ],
                ],
                [
                    'name' => 'Secret scanning alert details',
                    'category' => 'security',
                    'reason' => [
                        'de' => 'Secret-Scanning-Details können Secrets enthalten — Counts/Status max.',
                        'en' => 'Secret-scanning details may contain secrets — counts/status at most.',
                    ],
                ],
                [
                    'name' => 'Gist content',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Gist-Inhalte — oft Code/PII; nicht default-load.',
                        'en' => 'Gist content — often code/PII; do not default-load.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Commit patch/diff bodies', 'reason' => ['de' => 'Source-Code — Meta bevorzugen', 'en' => 'Source code — prefer metadata']],
                ['name' => 'Actions logs bulk', 'reason' => ['de' => 'Volumen, technisches Rauschen', 'en' => 'Volume, technical noise']],
                ['name' => 'Secret scanning alert details', 'reason' => ['de' => 'Security — Leak-Risiko', 'en' => 'Security — leak risk']],
                ['name' => 'Gist content dumps', 'reason' => ['de' => 'Code/PII — skip', 'en' => 'Code/PII — skip']],
            ],
            'dimensions' => [
                [
                    'id' => 'org',
                    'label' => ['de' => 'Organization', 'en' => 'Organization'],
                    'grain' => ['de' => 'organization.login', 'en' => 'organization.login'],
                    'notes' => [
                        'de' => 'Org als Tenant-Slice für Repos, Teams und Runs.',
                        'en' => 'Org as tenant slice for repos, teams and runs.',
                    ],
                ],
                [
                    'id' => 'repo',
                    'label' => ['de' => 'Repository', 'en' => 'Repository'],
                    'grain' => ['de' => 'repository.full_name', 'en' => 'repository.full_name'],
                    'notes' => [
                        'de' => 'full_name stabiler als umbenannte id-only Joins in Reports.',
                        'en' => 'full_name more stable than rename-fragile id-only joins in reports.',
                    ],
                ],
                [
                    'id' => 'author',
                    'label' => ['de' => 'Author', 'en' => 'Author'],
                    'grain' => ['de' => 'user.login / commit author', 'en' => 'user.login / commit author'],
                    'notes' => [
                        'de' => 'login bevorzugen; Author-E-Mail ist PII.',
                        'en' => 'Prefer login; author email is PII.',
                    ],
                ],
                [
                    'id' => 'workflow',
                    'label' => ['de' => 'Workflow', 'en' => 'Workflow'],
                    'grain' => ['de' => 'workflow_run.name / workflow_id', 'en' => 'workflow_run.name / workflow_id'],
                    'notes' => [
                        'de' => 'Für Failure-Rates und CI-Health.',
                        'en' => 'For failure rates and CI health.',
                    ],
                ],
                [
                    'id' => 'state',
                    'label' => ['de' => 'State', 'en' => 'State'],
                    'grain' => ['de' => 'PR/Issue state; run conclusion', 'en' => 'PR/Issue state; run conclusion'],
                    'notes' => [
                        'de' => 'open/closed vs. success/failure nicht vermischen.',
                        'en' => 'Do not mix open/closed with success/failure.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['email'],
                    'treatment' => [
                        'de' => 'User-E-Mail nur wenn API exponiert — taggen; login als Join bevorzugen.',
                        'en' => 'User email only if API exposes it — tag; prefer login as join.',
                    ],
                ],
                [
                    'entity' => 'CommitMeta',
                    'fields' => ['author.email', 'committer.email'],
                    'treatment' => [
                        'de' => 'Commit Author/Committer E-Mail — PII; hashen oder weglassen.',
                        'en' => 'Commit author/committer email — PII; hash or omit.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'repo id/full_name, user login, org login, PR/issue number, commit sha, run id.',
                        'en' => 'repo id/full_name, user login, org login, PR/issue number, commit sha, run id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Repository, PullRequest, Issue, WorkflowRun, User/Org Meta — kein Source-Code.',
                        'en' => 'Repository, pull request, issue, workflow run, user/org meta — no source code.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'prs-merged',
                    'example' => true,
                    'label' => ['de' => 'PRs Merged', 'en' => 'PRs merged'],
                    'question' => [
                        'de' => 'Wie viele Pull Requests wurden in der Periode gemerged?',
                        'en' => 'How many pull requests were merged in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM pull_request WHERE merged_at IS NOT NULL AND merged_at IN period',
                    'grain' => ['de' => 'Merged Pull Request', 'en' => 'Merged pull request'],
                    'dimensions' => ['org', 'repo', 'author', 'state'],
                    'fieldsUsed' => ['PullRequest.merged_at', 'PullRequest.state', 'PullRequest.user.login', 'Repository.full_name'],
                    'sourceHints' => [
                        'de' => 'merged_at gesetzt = merged; closed ohne merge separat zählen.',
                        'en' => 'merged_at set = merged; count closed-without-merge separately.',
                    ],
                    'adapt' => [
                        'de' => 'Bot-Authors (dependabot) optional ausschließen.',
                        'en' => 'Optionally exclude bot authors (dependabot).',
                    ],
                ],
                [
                    'id' => 'prs-opened',
                    'example' => true,
                    'label' => ['de' => 'PRs Opened', 'en' => 'PRs opened'],
                    'question' => [
                        'de' => 'Wie viele Pull Requests wurden in der Periode geöffnet?',
                        'en' => 'How many pull requests were opened in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM pull_request WHERE created_at IN period',
                    'grain' => ['de' => 'Opened Pull Request', 'en' => 'Opened pull request'],
                    'dimensions' => ['org', 'repo', 'author', 'state'],
                    'fieldsUsed' => ['PullRequest.created_at', 'PullRequest.user.login', 'Repository.full_name'],
                    'sourceHints' => [
                        'de' => 'created_at; Draft-PRs (draft=true) in Definition klären.',
                        'en' => 'created_at; clarify draft PRs (draft=true) in the definition.',
                    ],
                    'adapt' => [
                        'de' => 'Nur default_branch-target PRs als Delivery-Proxy.',
                        'en' => 'Only default_branch-target PRs as delivery proxy.',
                    ],
                ],
                [
                    'id' => 'issues-opened',
                    'example' => false,
                    'label' => ['de' => 'Issues Opened', 'en' => 'Issues opened'],
                    'question' => [
                        'de' => 'Wie viele Issues wurden in der Periode eröffnet?',
                        'en' => 'How many issues were opened in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM issue WHERE created_at IN period AND pull_request IS NULL',
                    'grain' => ['de' => 'Opened Issue', 'en' => 'Opened issue'],
                    'dimensions' => ['org', 'repo', 'author', 'state'],
                    'fieldsUsed' => ['Issue.created_at', 'Issue.state', 'Repository.full_name'],
                    'sourceHints' => [
                        'de' => 'GitHub Issues API kann PRs mixen — pull_request-Feld filtern.',
                        'en' => 'GitHub Issues API may mix PRs — filter pull_request field.',
                    ],
                    'adapt' => [
                        'de' => 'Bug vs. Feature über Labels als zusätzliche Dim.',
                        'en' => 'Bug vs feature via labels as extra dim.',
                    ],
                ],
                [
                    'id' => 'workflow-failures',
                    'example' => false,
                    'label' => ['de' => 'Workflow Failures', 'en' => 'Workflow failures'],
                    'question' => [
                        'de' => 'Wie viele Workflow Runs sind in der Periode fehlgeschlagen?',
                        'en' => 'How many workflow runs failed in the period?',
                    ],
                    'formula' => "COUNT(*) FROM workflow_run WHERE conclusion = 'failure' AND created_at IN period",
                    'grain' => ['de' => 'Failed Workflow Run', 'en' => 'Failed workflow run'],
                    'dimensions' => ['org', 'repo', 'workflow', 'state'],
                    'fieldsUsed' => ['WorkflowRun.conclusion', 'WorkflowRun.name', 'WorkflowRun.created_at', 'Repository.full_name'],
                    'sourceHints' => [
                        'de' => 'conclusion=failure; cancelled/timed_out separat.',
                        'en' => 'conclusion=failure; cancelled/timed_out separately.',
                    ],
                    'adapt' => [
                        'de' => 'Failure-Rate = failures / completed runs.',
                        'en' => 'Failure rate = failures / completed runs.',
                    ],
                ],
                [
                    'id' => 'active-repos',
                    'example' => false,
                    'label' => ['de' => 'Active Repos', 'en' => 'Active repos'],
                    'question' => [
                        'de' => 'Wie viele Repos hatten in der Periode Push-/PR-Aktivität?',
                        'en' => 'How many repos had push/PR activity in the period?',
                    ],
                    'formula' => 'COUNT(DISTINCT repository.id) WHERE pushed_at IN period OR pr.created_at IN period',
                    'grain' => ['de' => 'Repository mit Aktivität', 'en' => 'Repository with activity'],
                    'dimensions' => ['org', 'repo'],
                    'fieldsUsed' => ['Repository.id', 'Repository.full_name', 'Repository.pushed_at', 'PullRequest.created_at'],
                    'sourceHints' => [
                        'de' => 'Aktivität über pushed_at und/oder PR/Issue Events — Definition locken.',
                        'en' => 'Activity via pushed_at and/or PR/issue events — lock definition.',
                    ],
                    'adapt' => [
                        'de' => 'Archived=false und Forks optional ausschließen.',
                        'en' => 'Optionally exclude archived=false and forks.',
                    ],
                ],
            ],
            'tools' => $workplaceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'sharepoint',
            'domain' => 'workplace',
            'order' => 210,
            'label' => ['de' => 'SharePoint', 'en' => 'SharePoint'],
            'shortPurpose' => [
                'de' => 'Collaboration: Site/Drive/List-Meta, Sharing — Graph-Load; keine File-Binaries.',
                'en' => 'Collaboration: site/drive/list meta, sharing — Graph load; no file binaries.',
            ],
            'entities' => [
                [
                    'id' => 'site',
                    'label' => ['de' => 'Site', 'en' => 'Site'],
                    'description' => [
                        'de' => 'SharePoint Site — id, webUrl, name; Fact-Anker für Aktivität.',
                        'en' => 'SharePoint site — id, webUrl, name; fact anchor for activity.',
                    ],
                    'grain' => ['de' => 'Eine Site (id)', 'en' => 'One site (id)'],
                    'role' => ['de' => 'Dimension / Anker', 'en' => 'Dimension / anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'drive',
                    'label' => ['de' => 'Drive', 'en' => 'Drive'],
                    'description' => [
                        'de' => 'Document Library Drive — Site-Join; Binaries nicht laden.',
                        'en' => 'Document library drive — site join; do not load binaries.',
                    ],
                    'grain' => ['de' => 'Ein Drive (id)', 'en' => 'One drive (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'drive_item_meta',
                    'label' => ['de' => 'Drive Item Meta', 'en' => 'Drive item meta'],
                    'description' => [
                        'de' => 'File/Folder Meta — name, mime, lastModified; Content-Stream skip.',
                        'en' => 'File/folder meta — name, mime, lastModified; skip content stream.',
                    ],
                    'grain' => ['de' => 'Ein DriveItem (id)', 'en' => 'One driveItem (id)'],
                    'role' => ['de' => 'Content-Meta-Fact', 'en' => 'Content meta fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'list',
                    'label' => ['de' => 'List', 'en' => 'List'],
                    'description' => [
                        'de' => 'SharePoint List — Schema/Counts; keine Free-Text-Field-Dumps.',
                        'en' => 'SharePoint list — schema/counts; no free-text field dumps.',
                    ],
                    'grain' => ['de' => 'Eine List (id)', 'en' => 'One list (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'list_item_meta',
                    'label' => ['de' => 'List Item Meta', 'en' => 'List item meta'],
                    'description' => [
                        'de' => 'List Item Meta — id, contentType, modified; Free-Text-Felder selektiv.',
                        'en' => 'List item meta — id, contentType, modified; free-text fields selective.',
                    ],
                    'grain' => ['de' => 'Ein List Item (id)', 'en' => 'One list item (id)'],
                    'role' => ['de' => 'Meta-Fact', 'en' => 'Meta fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'Site/Drive User — mail ist PII; Graph user-Join.',
                        'en' => 'Site/drive user — mail is PII; Graph user join.',
                    ],
                    'grain' => ['de' => 'Ein User (id)', 'en' => 'One user (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'permission',
                    'label' => ['de' => 'Permission', 'en' => 'Permission'],
                    'description' => [
                        'de' => 'Item/Site Permission — Roles und Principals für Sharing-Analytics.',
                        'en' => 'Item/site permission — roles and principals for sharing analytics.',
                    ],
                    'grain' => ['de' => 'Eine Permission (id)', 'en' => 'One permission (id)'],
                    'role' => ['de' => 'Access-Fact', 'en' => 'Access fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'share_link',
                    'label' => ['de' => 'Share Link', 'en' => 'Share link'],
                    'description' => [
                        'de' => 'Sharing Link — scope, type, expiry; Targets können PII sein.',
                        'en' => 'Sharing link — scope, type, expiry; targets may be PII.',
                    ],
                    'grain' => ['de' => 'Ein Share Link (id)', 'en' => 'One share link (id)'],
                    'role' => ['de' => 'Sharing-Fact', 'en' => 'Sharing fact'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'Site', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Site-Join (Graph id)', 'en' => 'Site join (Graph id)']],
                ['entity' => 'Site', 'name' => 'webUrl', 'role' => 'dimension', 'why' => ['de' => 'Site-URL', 'en' => 'Site URL']],
                ['entity' => 'Site', 'name' => 'displayName', 'role' => 'dimension', 'why' => ['de' => 'Site-Name', 'en' => 'Site name']],
                ['entity' => 'Site', 'name' => 'createdDateTime', 'role' => 'measure', 'why' => ['de' => 'Site-Erstellung', 'en' => 'Site created']],
                ['entity' => 'Site', 'name' => 'lastModifiedDateTime', 'role' => 'measure', 'why' => ['de' => 'Letzte Site-Änderung', 'en' => 'Last site modification']],
                ['entity' => 'Drive', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Drive-Join', 'en' => 'Drive join']],
                ['entity' => 'Drive', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Library-Name', 'en' => 'Library name']],
                ['entity' => 'Drive', 'name' => 'driveType', 'role' => 'dimension', 'why' => ['de' => 'documentLibrary / personal …', 'en' => 'documentLibrary / personal …']],
                ['entity' => 'DriveItem', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'DriveItem-Join', 'en' => 'DriveItem join']],
                ['entity' => 'DriveItem', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Dateiname (Meta)', 'en' => 'File name (meta)']],
                ['entity' => 'DriveItem', 'name' => 'file.mimeType', 'role' => 'dimension', 'why' => ['de' => 'MIME / Content-Type', 'en' => 'MIME / content type']],
                ['entity' => 'DriveItem', 'name' => 'size', 'role' => 'measure', 'why' => ['de' => 'Größe in Bytes (Meta)', 'en' => 'Size in bytes (meta)']],
                ['entity' => 'DriveItem', 'name' => 'createdDateTime', 'role' => 'measure', 'why' => ['de' => 'File created', 'en' => 'File created']],
                ['entity' => 'DriveItem', 'name' => 'lastModifiedDateTime', 'role' => 'measure', 'why' => ['de' => 'File modified', 'en' => 'File modified']],
                ['entity' => 'DriveItem', 'name' => 'createdBy.user.id', 'role' => 'dimension', 'why' => ['de' => 'Creator-Join', 'en' => 'Creator join']],
                ['entity' => 'List', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'List-Join', 'en' => 'List join']],
                ['entity' => 'List', 'name' => 'displayName', 'role' => 'dimension', 'why' => ['de' => 'List-Name', 'en' => 'List name']],
                ['entity' => 'List', 'name' => 'list.template', 'role' => 'dimension', 'why' => ['de' => 'List-Template / Typ', 'en' => 'List template / type']],
                ['entity' => 'ListItem', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'List Item-Join', 'en' => 'List item join']],
                ['entity' => 'ListItem', 'name' => 'contentType.name', 'role' => 'dimension', 'why' => ['de' => 'Content Type', 'en' => 'Content type']],
                ['entity' => 'ListItem', 'name' => 'lastModifiedDateTime', 'role' => 'measure', 'why' => ['de' => 'Item modified', 'en' => 'Item modified']],
                ['entity' => 'User', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'User', 'name' => 'mail', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'User', 'name' => 'displayName', 'role' => 'pii', 'why' => ['de' => 'Anzeigename / PII', 'en' => 'Display name / PII']],
                ['entity' => 'Permission', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Permission-Join', 'en' => 'Permission join']],
                ['entity' => 'Permission', 'name' => 'roles', 'role' => 'dimension', 'why' => ['de' => 'read / write / owner', 'en' => 'read / write / owner']],
                ['entity' => 'Permission', 'name' => 'grantedToIdentities', 'role' => 'pii', 'why' => ['de' => 'Sharing Targets können PII sein', 'en' => 'Sharing targets may be PII']],
                ['entity' => 'ShareLink', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Share-Link-Join', 'en' => 'Share link join']],
                ['entity' => 'ShareLink', 'name' => 'link.scope', 'role' => 'dimension', 'why' => ['de' => 'anonymous / organization / users', 'en' => 'anonymous / organization / users']],
                ['entity' => 'ShareLink', 'name' => 'link.type', 'role' => 'dimension', 'why' => ['de' => 'view / edit', 'en' => 'view / edit']],
                ['entity' => 'ShareLink', 'name' => 'expirationDateTime', 'role' => 'measure', 'why' => ['de' => 'Link-Expiry', 'en' => 'Link expiry']],
                ['entity' => 'DriveItem', 'name' => 'sensitivityLabel', 'role' => 'dimension', 'why' => ['de' => 'Sensitivity Label (falls vorhanden)', 'en' => 'Sensitivity label (if present)']],
            ],
            'skipTables' => [
                [
                    'name' => 'File content binaries',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'DriveItem Content-Streams — nie default; nur Meta (mime, size, dates).',
                        'en' => 'DriveItem content streams — never by default; meta only (mime, size, dates).',
                    ],
                ],
                [
                    'name' => 'Version history blobs',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Versions-Binaries — Speicheraufwand; Versions-Counts reichen.',
                        'en' => 'Version binaries — storage cost; version counts suffice.',
                    ],
                ],
                [
                    'name' => 'Full list item field dumps (free text)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Freitext-Felder oft PII/vertraulich — selektiv oder hashen.',
                        'en' => 'Free-text fields often PII/confidential — selective or hash.',
                    ],
                ],
                [
                    'name' => 'Audit ultra-verbose dumps',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Ultra-verbose Audit — sampeln/aggregieren; Meta-Events bevorzugen.',
                        'en' => 'Ultra-verbose audit — sample/aggregate; prefer meta events.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'File content binaries', 'reason' => ['de' => 'Kein Default-Load von File Bodies', 'en' => 'No default load of file bodies']],
                ['name' => 'Version history blobs', 'reason' => ['de' => 'Speicher — Counts statt Blobs', 'en' => 'Storage — counts instead of blobs']],
                ['name' => 'Full list item free-text dumps', 'reason' => ['de' => 'PII/vertraulich — selektiv', 'en' => 'PII/confidential — selective']],
                ['name' => 'Audit ultra-verbose dumps', 'reason' => ['de' => 'Volumen — Meta/Agg', 'en' => 'Volume — meta/agg']],
            ],
            'dimensions' => [
                [
                    'id' => 'site',
                    'label' => ['de' => 'Site', 'en' => 'Site'],
                    'grain' => ['de' => 'site.id / webUrl', 'en' => 'site.id / webUrl'],
                    'notes' => [
                        'de' => 'Primärer Slice für Aktivität und Sharing.',
                        'en' => 'Primary slice for activity and sharing.',
                    ],
                ],
                [
                    'id' => 'drive',
                    'label' => ['de' => 'Drive', 'en' => 'Drive'],
                    'grain' => ['de' => 'drive.id / name', 'en' => 'drive.id / name'],
                    'notes' => [
                        'de' => 'Library-Ebene unter Site; personal vs. documentLibrary trennen.',
                        'en' => 'Library level under site; separate personal vs documentLibrary.',
                    ],
                ],
                [
                    'id' => 'content_type',
                    'label' => ['de' => 'Content Type', 'en' => 'Content type'],
                    'grain' => ['de' => 'listItem.contentType / file.mimeType', 'en' => 'listItem.contentType / file.mimeType'],
                    'notes' => [
                        'de' => 'MIME und List Content Types nicht vermischen ohne Mapping.',
                        'en' => 'Do not mix MIME and list content types without mapping.',
                    ],
                ],
                [
                    'id' => 'sensitivity',
                    'label' => ['de' => 'Sensitivity', 'en' => 'Sensitivity'],
                    'grain' => ['de' => 'sensitivityLabel (notes)', 'en' => 'sensitivityLabel (notes)'],
                    'notes' => [
                        'de' => 'MIP/Sensitivity Labels wenn verfügbar; sonst Proxy über Share-Link-Scope.',
                        'en' => 'MIP/sensitivity labels when available; else proxy via share-link scope.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['mail', 'displayName'],
                    'treatment' => [
                        'de' => 'User Mail/Name — PII taggen; id als Join bevorzugen.',
                        'en' => 'User mail/name — tag PII; prefer id as join.',
                    ],
                ],
                [
                    'entity' => 'ListItem',
                    'fields' => ['fields.*'],
                    'treatment' => [
                        'de' => 'List-Felder können PII enthalten — Schema prüfen, Free-Text redigieren.',
                        'en' => 'List fields may contain PII — review schema, redact free text.',
                    ],
                ],
                [
                    'entity' => 'Permission',
                    'fields' => ['grantedToIdentities', 'grantedToV2'],
                    'treatment' => [
                        'de' => 'Sharing Targets (E-Mail/UPN) — PII; Counts ohne Klartext möglich.',
                        'en' => 'Sharing targets (email/UPN) — PII; counts without plaintext possible.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'site id, drive id, driveItem id, list/item id, user id, share link id.',
                        'en' => 'site id, drive id, driveItem id, list/item id, user id, share link id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Site, Drive, DriveItem Meta, List Meta, ShareLink — keine File Bodies.',
                        'en' => 'Site, drive, driveItem meta, list meta, share link — no file bodies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'sites-active',
                    'example' => true,
                    'label' => ['de' => 'Sites Active', 'en' => 'Sites active'],
                    'question' => [
                        'de' => 'Wie viele Sites hatten in der Periode Änderungen?',
                        'en' => 'How many sites had modifications in the period?',
                    ],
                    'formula' => 'COUNT(DISTINCT site.id) WHERE lastModifiedDateTime IN period OR driveItem.lastModifiedDateTime IN period',
                    'grain' => ['de' => 'Site mit Aktivität', 'en' => 'Site with activity'],
                    'dimensions' => ['site', 'drive', 'sensitivity'],
                    'fieldsUsed' => ['Site.id', 'Site.lastModifiedDateTime', 'DriveItem.lastModifiedDateTime'],
                    'sourceHints' => [
                        'de' => 'Aktivität über Site- oder Item-lastModified — Definition locken.',
                        'en' => 'Activity via site or item lastModified — lock definition.',
                    ],
                    'adapt' => [
                        'de' => 'OneDrive-personal Sites optional ausschließen.',
                        'en' => 'Optionally exclude OneDrive personal sites.',
                    ],
                ],
                [
                    'id' => 'files-created',
                    'example' => true,
                    'label' => ['de' => 'Files Created', 'en' => 'Files created'],
                    'question' => [
                        'de' => 'Wie viele Dateien wurden in der Periode erstellt?',
                        'en' => 'How many files were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM drive_item_meta WHERE file IS NOT NULL AND createdDateTime IN period',
                    'grain' => ['de' => 'DriveItem (file)', 'en' => 'DriveItem (file)'],
                    'dimensions' => ['site', 'drive', 'content_type', 'sensitivity'],
                    'fieldsUsed' => ['DriveItem.id', 'DriveItem.createdDateTime', 'DriveItem.file.mimeType'],
                    'sourceHints' => [
                        'de' => 'file-Facet gesetzt = Datei (Folders ausschließen).',
                        'en' => 'file facet set = file (exclude folders).',
                    ],
                    'adapt' => [
                        'de' => 'Uploads vs. Office-Create ggf. über createdBy App trennen.',
                        'en' => 'Optionally separate uploads vs Office create via createdBy app.',
                    ],
                ],
                [
                    'id' => 'files-modified',
                    'example' => false,
                    'label' => ['de' => 'Files Modified', 'en' => 'Files modified'],
                    'question' => [
                        'de' => 'Wie viele Dateien wurden in der Periode geändert?',
                        'en' => 'How many files were modified in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM drive_item_meta WHERE file IS NOT NULL AND lastModifiedDateTime IN period',
                    'grain' => ['de' => 'DriveItem (file) modified', 'en' => 'DriveItem (file) modified'],
                    'dimensions' => ['site', 'drive', 'content_type'],
                    'fieldsUsed' => ['DriveItem.id', 'DriveItem.lastModifiedDateTime', 'DriveItem.file.mimeType'],
                    'sourceHints' => [
                        'de' => 'lastModifiedDateTime; Create-only Events nicht doppelzählen wenn created=modified.',
                        'en' => 'lastModifiedDateTime; avoid double-counting create-only when created=modified.',
                    ],
                    'adapt' => [
                        'de' => 'Distinct files vs. modification events festlegen.',
                        'en' => 'Lock distinct files vs modification events.',
                    ],
                ],
                [
                    'id' => 'sharing-links-active',
                    'example' => false,
                    'label' => ['de' => 'Sharing Links Active', 'en' => 'Sharing links active'],
                    'question' => [
                        'de' => 'Wie viele Sharing Links sind aktiv (nicht abgelaufen)?',
                        'en' => 'How many sharing links are active (not expired)?',
                    ],
                    'formula' => 'COUNT(*) FROM share_link WHERE expirationDateTime IS NULL OR expirationDateTime > now()',
                    'grain' => ['de' => 'Active Share Link', 'en' => 'Active share link'],
                    'dimensions' => ['site', 'drive', 'sensitivity'],
                    'fieldsUsed' => ['ShareLink.id', 'ShareLink.link.scope', 'ShareLink.expirationDateTime'],
                    'sourceHints' => [
                        'de' => 'Scope anonymous vs. organization für Risk-Slices.',
                        'en' => 'Scope anonymous vs organization for risk slices.',
                    ],
                    'adapt' => [
                        'de' => 'Nur external/anonymous Links als Risk-KPI Variante.',
                        'en' => 'Variant: only external/anonymous links as risk KPI.',
                    ],
                ],
                [
                    'id' => 'lists-count',
                    'example' => false,
                    'label' => ['de' => 'Lists Count', 'en' => 'Lists count'],
                    'question' => [
                        'de' => 'Wie viele Lists existieren (Snapshot oder neu in Periode)?',
                        'en' => 'How many lists exist (snapshot or new in period)?',
                    ],
                    'formula' => 'COUNT(*) FROM list',
                    'grain' => ['de' => 'List', 'en' => 'List'],
                    'dimensions' => ['site', 'content_type'],
                    'fieldsUsed' => ['List.id', 'List.displayName', 'List.list.template', 'Site.id'],
                    'sourceHints' => [
                        'de' => 'System-Lists (z. B. Form Templates) ggf. ausschließen.',
                        'en' => 'Possibly exclude system lists (e.g. form templates).',
                    ],
                    'adapt' => [
                        'de' => 'Nur Lists mit Item-Aktivität in der Periode.',
                        'en' => 'Only lists with item activity in the period.',
                    ],
                ],
            ],
            'tools' => $workplaceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'google-workspace',
            'domain' => 'workplace',
            'order' => 220,
            'label' => ['de' => 'Google Workspace', 'en' => 'Google Workspace'],
            'shortPurpose' => [
                'de' => 'Workspace: User/OU, Drive/Calendar/Gmail-Meta, Login Reports — Admin/Reports API; keine Mail/File Bodies.',
                'en' => 'Workspace: user/OU, Drive/Calendar/Gmail meta, login reports — Admin/Reports API; no mail/file bodies.',
            ],
            'entities' => [
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'Directory User — primaryEmail, name, orgUnit; Workforce-PII.',
                        'en' => 'Directory user — primaryEmail, name, orgUnit; workforce PII.',
                    ],
                    'grain' => ['de' => 'Ein User (id / primaryEmail)', 'en' => 'One user (id / primaryEmail)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'group',
                    'label' => ['de' => 'Group', 'en' => 'Group'],
                    'description' => [
                        'de' => 'Workspace Group — email, Members-Count; Membership-Join.',
                        'en' => 'Workspace group — email, members count; membership join.',
                    ],
                    'grain' => ['de' => 'Eine Group (id / email)', 'en' => 'One group (id / email)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'drive_file_meta',
                    'label' => ['de' => 'Drive File Meta', 'en' => 'Drive file meta'],
                    'description' => [
                        'de' => 'Drive File Meta — mimeType, createdTime; Content nicht laden.',
                        'en' => 'Drive file meta — mimeType, createdTime; do not load content.',
                    ],
                    'grain' => ['de' => 'Eine Drive File (id)', 'en' => 'One Drive file (id)'],
                    'role' => ['de' => 'Content-Meta-Fact', 'en' => 'Content meta fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'calendar_event_meta',
                    'label' => ['de' => 'Calendar Event Meta', 'en' => 'Calendar event meta'],
                    'description' => [
                        'de' => 'Event Meta — start/end, eventType; Titles können sensitiv sein.',
                        'en' => 'Event meta — start/end, eventType; titles may be sensitive.',
                    ],
                    'grain' => ['de' => 'Ein Calendar Event (id)', 'en' => 'One calendar event (id)'],
                    'role' => ['de' => 'Meta-Fact', 'en' => 'Meta fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'gmail_meta',
                    'label' => ['de' => 'Gmail Meta', 'en' => 'Gmail meta'],
                    'description' => [
                        'de' => 'Gmail Message Meta — ids, labels, dates; Body/Attachments nie default.',
                        'en' => 'Gmail message meta — ids, labels, dates; never body/attachments by default.',
                    ],
                    'grain' => ['de' => 'Eine Message (id) Meta', 'en' => 'One message (id) meta'],
                    'role' => ['de' => 'Mail-Meta (kein Body)', 'en' => 'Mail meta (no body)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'login_activity',
                    'label' => ['de' => 'Login Activity', 'en' => 'Login activity'],
                    'description' => [
                        'de' => 'Reports API Login — success/failure, timestamp; High Volume sampeln.',
                        'en' => 'Reports API login — success/failure, timestamp; sample at high volume.',
                    ],
                    'grain' => ['de' => 'Ein Login Event (oder Tages-Agg)', 'en' => 'One login event (or daily agg)'],
                    'role' => ['de' => 'Auth-Fact', 'en' => 'Auth fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'chrome_device',
                    'label' => ['de' => 'Chrome Device', 'en' => 'Chrome device'],
                    'description' => [
                        'de' => 'ChromeOS Device — serial, status, OU-Join.',
                        'en' => 'ChromeOS device — serial, status, OU join.',
                    ],
                    'grain' => ['de' => 'Ein Chrome Device (deviceId)', 'en' => 'One Chrome device (deviceId)'],
                    'role' => ['de' => 'Device-Dimension', 'en' => 'Device dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'org_unit',
                    'label' => ['de' => 'Org Unit', 'en' => 'Org unit'],
                    'description' => [
                        'de' => 'Directory OU — Pfad/Hierarchy für User- und Policy-Dim.',
                        'en' => 'Directory OU — path/hierarchy for user and policy dim.',
                    ],
                    'grain' => ['de' => 'Eine Org Unit (orgUnitPath)', 'en' => 'One org unit (orgUnitPath)'],
                    'role' => ['de' => 'Org-Dimension', 'en' => 'Org dimension'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'User', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Directory User-ID', 'en' => 'Directory user ID']],
                ['entity' => 'User', 'name' => 'primaryEmail', 'role' => 'pii', 'why' => ['de' => 'Primäre E-Mail / PII', 'en' => 'Primary email / PII']],
                ['entity' => 'User', 'name' => 'name.fullName', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'User', 'name' => 'suspended', 'role' => 'dimension', 'why' => ['de' => 'Suspended vs. active', 'en' => 'Suspended vs active']],
                ['entity' => 'User', 'name' => 'orgUnitPath', 'role' => 'dimension', 'why' => ['de' => 'OU-Pfad / Org-Dim', 'en' => 'OU path / org dim']],
                ['entity' => 'User', 'name' => 'creationTime', 'role' => 'measure', 'why' => ['de' => 'User erstellt', 'en' => 'User created']],
                ['entity' => 'User', 'name' => 'organizations[0].department', 'role' => 'dimension', 'why' => ['de' => 'Department (Directory)', 'en' => 'Department (directory)']],
                ['entity' => 'Group', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Group-Join', 'en' => 'Group join']],
                ['entity' => 'Group', 'name' => 'email', 'role' => 'dimension', 'why' => ['de' => 'Group-E-Mail', 'en' => 'Group email']],
                ['entity' => 'Group', 'name' => 'directMembersCount', 'role' => 'measure', 'why' => ['de' => 'Mitglieder-Count', 'en' => 'Members count']],
                ['entity' => 'DriveFile', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Drive File-Join', 'en' => 'Drive file join']],
                ['entity' => 'DriveFile', 'name' => 'mimeType', 'role' => 'dimension', 'why' => ['de' => 'File Type / MIME', 'en' => 'File type / MIME']],
                ['entity' => 'DriveFile', 'name' => 'createdTime', 'role' => 'measure', 'why' => ['de' => 'File created', 'en' => 'File created']],
                ['entity' => 'DriveFile', 'name' => 'modifiedTime', 'role' => 'measure', 'why' => ['de' => 'File modified', 'en' => 'File modified']],
                ['entity' => 'DriveFile', 'name' => 'owners[].emailAddress', 'role' => 'pii', 'why' => ['de' => 'Owner E-Mail / PII', 'en' => 'Owner email / PII']],
                ['entity' => 'DriveFile', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Dateiname (Meta; kann sensitiv sein)', 'en' => 'File name (meta; may be sensitive)']],
                ['entity' => 'CalendarEvent', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Event-Join', 'en' => 'Event join']],
                ['entity' => 'CalendarEvent', 'name' => 'summary', 'role' => 'pii', 'why' => ['de' => 'Titel oft sensitiv', 'en' => 'Title often sensitive']],
                ['entity' => 'CalendarEvent', 'name' => 'eventType', 'role' => 'dimension', 'why' => ['de' => 'default / outOfOffice / focusTime …', 'en' => 'default / outOfOffice / focusTime …']],
                ['entity' => 'CalendarEvent', 'name' => 'start.dateTime', 'role' => 'measure', 'why' => ['de' => 'Event-Start', 'en' => 'Event start']],
                ['entity' => 'CalendarEvent', 'name' => 'end.dateTime', 'role' => 'measure', 'why' => ['de' => 'Event-Ende / Dauer', 'en' => 'Event end / duration']],
                ['entity' => 'GmailMeta', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Message-ID (Meta)', 'en' => 'Message ID (meta)']],
                ['entity' => 'GmailMeta', 'name' => 'threadId', 'role' => 'dimension', 'why' => ['de' => 'Thread-Join', 'en' => 'Thread join']],
                ['entity' => 'GmailMeta', 'name' => 'internalDate', 'role' => 'measure', 'why' => ['de' => 'Message-Zeit', 'en' => 'Message time']],
                ['entity' => 'GmailMeta', 'name' => 'labelIds', 'role' => 'dimension', 'why' => ['de' => 'INBOX/SENT/… Labels', 'en' => 'INBOX/SENT/… labels']],
                ['entity' => 'LoginActivity', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Login Event-ID / Agg-Key', 'en' => 'Login event ID / agg key']],
                ['entity' => 'LoginActivity', 'name' => 'time', 'role' => 'measure', 'why' => ['de' => 'Login-Zeitpunkt', 'en' => 'Login timestamp']],
                ['entity' => 'LoginActivity', 'name' => 'actor.email', 'role' => 'pii', 'why' => ['de' => 'Actor E-Mail / PII', 'en' => 'Actor email / PII']],
                ['entity' => 'LoginActivity', 'name' => 'eventName', 'role' => 'dimension', 'why' => ['de' => 'login_success / login_failure …', 'en' => 'login_success / login_failure …']],
                ['entity' => 'ChromeDevice', 'name' => 'deviceId', 'role' => 'key', 'why' => ['de' => 'Chrome Device-Join', 'en' => 'Chrome device join']],
                ['entity' => 'ChromeDevice', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'ACTIVE / DISABLED …', 'en' => 'ACTIVE / DISABLED …']],
                ['entity' => 'ChromeDevice', 'name' => 'orgUnitPath', 'role' => 'dimension', 'why' => ['de' => 'Device OU', 'en' => 'Device OU']],
                ['entity' => 'OrgUnit', 'name' => 'orgUnitId', 'role' => 'key', 'why' => ['de' => 'OU-Join', 'en' => 'OU join']],
                ['entity' => 'OrgUnit', 'name' => 'orgUnitPath', 'role' => 'dimension', 'why' => ['de' => 'OU-Pfad', 'en' => 'OU path']],
                ['entity' => 'OrgUnit', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'OU-Name', 'en' => 'OU name']],
            ],
            'skipTables' => [
                [
                    'name' => 'Gmail / Drive file bodies',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Mail-Bodies und Drive-Content — nie default; nur Meta.',
                        'en' => 'Mail bodies and Drive content — never by default; meta only.',
                    ],
                ],
                [
                    'name' => 'Meet recordings',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Meet-Recordings — Binaries/PII; Minutes aus Reports, nicht Medien.',
                        'en' => 'Meet recordings — binaries/PII; minutes from reports, not media.',
                    ],
                ],
                [
                    'name' => 'Chat messages bulk',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Chat-Nachrichten bulk — Inhalt sensitiv; Counts/Meta max.',
                        'en' => 'Chat messages bulk — content sensitive; counts/meta at most.',
                    ],
                ],
                [
                    'name' => 'Vault export dumps',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Vault-Exports — Legal Holds / Full Content; nicht Analytics-RAW.',
                        'en' => 'Vault exports — legal holds / full content; not analytics RAW.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Gmail and Drive file bodies', 'reason' => ['de' => 'Kein Default-Load von Bodies', 'en' => 'No default load of bodies']],
                ['name' => 'Meet recordings', 'reason' => ['de' => 'Medien/PII — Reports statt Recordings', 'en' => 'Media/PII — reports not recordings']],
                ['name' => 'Chat messages bulk', 'reason' => ['de' => 'Inhalt sensitiv — skip', 'en' => 'Content sensitive — skip']],
                ['name' => 'Vault export dumps', 'reason' => ['de' => 'Legal/Full Content — nicht RAW', 'en' => 'Legal/full content — not RAW']],
            ],
            'dimensions' => [
                [
                    'id' => 'org_unit',
                    'label' => ['de' => 'Org Unit', 'en' => 'Org unit'],
                    'grain' => ['de' => 'orgUnitPath', 'en' => 'orgUnitPath'],
                    'notes' => [
                        'de' => 'Primärer Directory-Slice; Parent-Pfad für Rollups nutzen.',
                        'en' => 'Primary directory slice; use parent path for rollups.',
                    ],
                ],
                [
                    'id' => 'department',
                    'label' => ['de' => 'Department', 'en' => 'Department'],
                    'grain' => ['de' => 'organizations[].department', 'en' => 'organizations[].department'],
                    'notes' => [
                        'de' => 'Directory Department; oft unvollständig — mit OU kombinieren.',
                        'en' => 'Directory department; often incomplete — combine with OU.',
                    ],
                ],
                [
                    'id' => 'file_type',
                    'label' => ['de' => 'File Type', 'en' => 'File type'],
                    'grain' => ['de' => 'drive_file.mimeType', 'en' => 'drive_file.mimeType'],
                    'notes' => [
                        'de' => 'Google Docs MIME vs. uploaded binaries gruppieren.',
                        'en' => 'Group Google Docs MIME vs uploaded binaries.',
                    ],
                ],
                [
                    'id' => 'event_type',
                    'label' => ['de' => 'Event Type', 'en' => 'Event type'],
                    'grain' => ['de' => 'calendar.eventType / login eventName', 'en' => 'calendar.eventType / login eventName'],
                    'notes' => [
                        'de' => 'Calendar eventType und Login eventName nicht vermischen.',
                        'en' => 'Do not mix calendar eventType and login eventName.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['primaryEmail', 'name.fullName', 'name.givenName', 'name.familyName'],
                    'treatment' => [
                        'de' => 'E-Mail und Namen — Workforce-PII; id als Join bevorzugen.',
                        'en' => 'Email and names — workforce PII; prefer id as join.',
                    ],
                ],
                [
                    'entity' => 'CalendarEvent',
                    'fields' => ['summary', 'description', 'attendees[].email'],
                    'treatment' => [
                        'de' => 'Titel/Beschreibung/Attendees können sensitiv sein — redigieren oder weglassen.',
                        'en' => 'Titles/descriptions/attendees may be sensitive — redact or omit.',
                    ],
                ],
                [
                    'entity' => 'DriveFile',
                    'fields' => ['owners[].emailAddress', 'name'],
                    'treatment' => [
                        'de' => 'Owner-E-Mail PII; Dateinamen können vertraulich sein.',
                        'en' => 'Owner email PII; file names may be confidential.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'user id/primaryEmail, group id, file id, event id, orgUnitPath, deviceId.',
                        'en' => 'user id/primaryEmail, group id, file id, event id, orgUnitPath, deviceId.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'User, Group, OrgUnit, Drive/Calendar/Gmail Meta, Login Reports — keine Bodies.',
                        'en' => 'User, group, org unit, Drive/Calendar/Gmail meta, login reports — no bodies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'active-users',
                    'example' => true,
                    'label' => ['de' => 'Active Users', 'en' => 'Active users'],
                    'question' => [
                        'de' => 'Wie viele nicht-suspendierte Users gibt es (Snapshot)?',
                        'en' => 'How many non-suspended users are there (snapshot)?',
                    ],
                    'formula' => 'COUNT(*) FROM user WHERE suspended = false',
                    'grain' => ['de' => 'Active Directory User', 'en' => 'Active directory user'],
                    'dimensions' => ['org_unit', 'department'],
                    'fieldsUsed' => ['User.id', 'User.suspended', 'User.orgUnitPath', 'User.organizations[0].department'],
                    'sourceHints' => [
                        'de' => 'suspended=false; Archived/Deleted Users separat.',
                        'en' => 'suspended=false; treat archived/deleted users separately.',
                    ],
                    'adapt' => [
                        'de' => 'Activity-based: Users mit Login in der Periode (Reports API).',
                        'en' => 'Activity-based: users with login in period (Reports API).',
                    ],
                ],
                [
                    'id' => 'drive-files-created',
                    'example' => true,
                    'label' => ['de' => 'Drive Files Created', 'en' => 'Drive files created'],
                    'question' => [
                        'de' => 'Wie viele Drive-Dateien wurden in der Periode erstellt?',
                        'en' => 'How many Drive files were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM drive_file_meta WHERE createdTime IN period',
                    'grain' => ['de' => 'Drive File Meta', 'en' => 'Drive file meta'],
                    'dimensions' => ['org_unit', 'department', 'file_type'],
                    'fieldsUsed' => ['DriveFile.id', 'DriveFile.createdTime', 'DriveFile.mimeType'],
                    'sourceHints' => [
                        'de' => 'Drive API Meta oder Reports drive usage — Content nie laden.',
                        'en' => 'Drive API meta or Reports drive usage — never load content.',
                    ],
                    'adapt' => [
                        'de' => 'Shared drives vs. My Drive trennen.',
                        'en' => 'Separate shared drives vs My Drive.',
                    ],
                ],
                [
                    'id' => 'meet-minutes',
                    'example' => false,
                    'label' => ['de' => 'Meet Minutes', 'en' => 'Meet minutes'],
                    'question' => [
                        'de' => 'Wie viele Meet-Minuten wurden in der Periode verbraucht (Reports)?',
                        'en' => 'How many Meet minutes were consumed in the period (Reports)?',
                    ],
                    'formula' => 'SUM(meet_minutes) FROM reports_meet_usage WHERE date IN period',
                    'grain' => ['de' => 'Meet Usage (Reports)', 'en' => 'Meet usage (Reports)'],
                    'dimensions' => ['org_unit', 'department', 'event_type'],
                    'fieldsUsed' => ['LoginActivity.time', 'User.orgUnitPath'],
                    'sourceHints' => [
                        'de' => 'Über Admin Reports / Meet usage — nicht aus Recordings ableiten.',
                        'en' => 'Via Admin Reports / Meet usage — do not derive from recordings.',
                    ],
                    'adapt' => [
                        'de' => 'Falls Reports-Feld fehlt: Calendar Meet-Events Dauer als Proxy (unsicher).',
                        'en' => 'If Reports field missing: calendar Meet event duration as proxy (weak).',
                    ],
                ],
                [
                    'id' => 'login-failures',
                    'example' => false,
                    'label' => ['de' => 'Login Failures', 'en' => 'Login failures'],
                    'question' => [
                        'de' => 'Wie viele fehlgeschlagene Logins gab es in der Periode?',
                        'en' => 'How many failed logins occurred in the period?',
                    ],
                    'formula' => "COUNT(*) FROM login_activity WHERE eventName = 'login_failure' AND time IN period",
                    'grain' => ['de' => 'Failed Login Event', 'en' => 'Failed login event'],
                    'dimensions' => ['org_unit', 'department', 'event_type'],
                    'fieldsUsed' => ['LoginActivity.eventName', 'LoginActivity.time', 'LoginActivity.actor.email'],
                    'sourceHints' => [
                        'de' => 'Reports API login events; High Volume aggregieren.',
                        'en' => 'Reports API login events; aggregate at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Suspicious login vs. plain failure Codes trennen wenn verfügbar.',
                        'en' => 'Separate suspicious login vs plain failure when available.',
                    ],
                ],
                [
                    'id' => 'groups-count',
                    'example' => false,
                    'label' => ['de' => 'Groups Count', 'en' => 'Groups count'],
                    'question' => [
                        'de' => 'Wie viele Groups existieren im Directory?',
                        'en' => 'How many groups exist in the directory?',
                    ],
                    'formula' => 'COUNT(*) FROM group',
                    'grain' => ['de' => 'Group', 'en' => 'Group'],
                    'dimensions' => ['org_unit', 'department'],
                    'fieldsUsed' => ['Group.id', 'Group.email', 'Group.directMembersCount'],
                    'sourceHints' => [
                        'de' => 'Directory Groups API; dynamische vs. Security Groups in Definition.',
                        'en' => 'Directory Groups API; clarify dynamic vs security groups in definition.',
                    ],
                    'adapt' => [
                        'de' => 'Nur Groups mit Members > 0 oder mit Mail-Traffic.',
                        'en' => 'Only groups with members > 0 or with mail traffic.',
                    ],
                ],
            ],
            'tools' => $workplaceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
