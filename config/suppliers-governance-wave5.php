<?php

/**
 * Wave 5 governance overlays — Workplace/Identity source-native PII, DSDR, skip guidance.
 * Merged onto catalog products by id (entra-id, github, sharepoint, google-workspace).
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'entra-id' => [
        'pii' => [
            [
                'entity' => 'User / directory object',
                'fields' => ['id', 'userPrincipalName', 'mail', 'displayName', 'givenName', 'surname', 'mobilePhone', 'businessPhones'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Entra User — UPN/mail/phone nur mit Legal-Basis; object id als Key behalten.', 'en' => 'Entra user — UPN/mail/phone only with legal basis; keep object id as key.'],
            ],
            [
                'entity' => 'Sign-in / authentication logs',
                'fields' => ['userPrincipalName', 'ipAddress', 'location', 'deviceDetail', 'clientAppUsed', 'conditionalAccessStatus'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: aggregiert / hashed; Mart: keine Klartext-IPs/UPNs', 'en' => 'RAW: restrict access; Curated: aggregated / hashed; Mart: no cleartext IPs/UPNs'],
                'treatment' => ['de' => 'Sign-in Logs sind Security-sensibel — Default Restrict; Retention kurz halten.', 'en' => 'Sign-in logs are security-sensitive — default restrict; keep retention short.'],
            ],
            [
                'entity' => 'Audit logs / directory changes',
                'fields' => ['initiatedBy.user', 'targetResources', 'modifiedProperties', 'activityDisplayName'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: actor/target ids only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: actor/target ids only; Mart: aggregates only'],
                'treatment' => ['de' => 'Audit — Actor/Target als object ids; keine Klartext-Namen in Default-Marts.', 'en' => 'Audit — actor/target as object ids; no cleartext names in default marts.'],
            ],
            [
                'entity' => 'App registration / credentials',
                'fields' => ['passwordCredentials', 'keyCredentials', 'client secrets', 'certificates'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Secrets/Certificates nie ins Warehouse — nur Metadata (expiry, keyId) wenn nötig.', 'en' => 'Never land secrets/certificates in the warehouse — metadata (expiry, keyId) only if needed.'],
            ],
            [
                'entity' => 'Group membership / role assignments',
                'fields' => ['group members', 'directoryRole members', 'appRoleAssignedTo'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: ids only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: ids only; Mart: aggregates only'],
                'treatment' => ['de' => 'Memberships als object ids — Access Reviews brauchen keine Klartext-Mails.', 'en' => 'Memberships as object ids — access reviews do not need cleartext mail.'],
            ],
            [
                'entity' => 'Guest / B2B invitation',
                'fields' => ['invitedUserEmailAddress', 'invitedUserDisplayName', 'inviteRedeemUrl'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Guest Invite Emails — oft externe Personen; Redeem URLs nie speichern.', 'en' => 'Guest invite emails — often external persons; never store redeem URLs.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'object id, userPrincipalName (hashed), mail (hashed), employeeId, onPremisesSamAccountName.', 'en' => 'object id, userPrincipalName (hashed), mail (hashed), employeeId, onPremisesSamAccountName.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'User, Group, Application, ServicePrincipal, DirectoryRole, Sign-in, Audit.', 'en' => 'User, group, application, service principal, directory role, sign-in, audit.'],
            ],
            [
                'focus' => ['de' => 'Graph / Export / Log Analytics copies', 'en' => 'Graph / Export / Log Analytics copies'],
                'notes' => ['de' => 'Graph Delta, Diagnostic Settings und Log Analytics verdoppeln Directory-PII.', 'en' => 'Graph delta, diagnostic settings and Log Analytics duplicate directory PII.'],
            ],
            [
                'focus' => ['de' => 'Tenant / sandbox copies', 'en' => 'Tenant / sandbox copies'],
                'notes' => ['de' => 'Sandbox-Tenants nicht mit Prod-Identity-Marts mischen.', 'en' => 'Do not mix sandbox tenants with prod identity marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'passwordCredentials / keyCredentials secrets',
                'category' => 'system',
                'reason' => ['de' => 'Secrets/Certificates — nie landen; nur Expiry-Metadata.', 'en' => 'Secrets/certificates — never land; expiry metadata only.'],
            ],
            [
                'name' => 'Sign-in log full payloads (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Security-sensibel und Volumen — Aggregates/Allowlist Fields.', 'en' => 'Security-sensitive and bulky — aggregates/allowlist fields.'],
            ],
            [
                'name' => 'Invite redeem URLs / invitation messages',
                'category' => 'system',
                'reason' => ['de' => 'Tokenisierte Invite Links — nie speichern.', 'en' => 'Tokenized invite links — never store.'],
            ],
            [
                'name' => 'Token / refresh / session dumps',
                'category' => 'system',
                'reason' => ['de' => 'Auth Tokens — kein Analytics-Kern.', 'en' => 'Auth tokens — not analytics core.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Client secrets / certificates / private keys',
                'reason' => ['de' => 'Nie ins Warehouse — Credential Hygiene in Entra.', 'en' => 'Never into the warehouse — credential hygiene in Entra.'],
            ],
            [
                'name' => 'UPN / mail / phone cleartext in marts',
                'reason' => ['de' => 'object id reicht für Identity-KPIs.', 'en' => 'object id is enough for identity KPIs.'],
            ],
            [
                'name' => 'Full sign-in IP / device cleartext (bulk)',
                'reason' => ['de' => 'Security-PII — aggregiert oder hashed.', 'en' => 'Security PII — aggregated or hashed.'],
            ],
            [
                'name' => 'Guest invite redeem URLs',
                'reason' => ['de' => 'Token-Leak-Risiko — nie speichern.', 'en' => 'Token leak risk — never store.'],
            ],
            [
                'name' => 'Sandbox / test tenant users in prod marts',
                'reason' => ['de' => 'Prod-Identity-Marts sauber halten.', 'en' => 'Keep prod identity marts clean.'],
            ],
        ],
    ],

    'github' => [
        'pii' => [
            [
                'entity' => 'User / org member',
                'fields' => ['login', 'id', 'email', 'name', 'avatar_url'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: login/id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: login/id only; Mart: aggregates only'],
                'treatment' => ['de' => 'GitHub User — email nur mit Legal-Basis; id/login als Key.', 'en' => 'GitHub user — email only with legal basis; id/login as key.'],
            ],
            [
                'entity' => 'Commit / PR author',
                'fields' => ['author.email', 'committer.email', 'author.name', 'user.login'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: login/id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: login/id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Author Emails aus Commits — oft persönliche Mails; hashen oder drop in Curated.', 'en' => 'Author emails from commits — often personal mail; hash or drop in curated.'],
            ],
            [
                'entity' => 'Issue / PR body & review comments',
                'fields' => ['body', 'review_comment.body', 'discussion comments'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Freitext kann Secrets und Personenbezug enthalten — Metadata reicht für Delivery-KPIs.', 'en' => 'Free text can contain secrets and personal data — metadata enough for delivery KPIs.'],
            ],
            [
                'entity' => 'Source code / patches / diffs',
                'fields' => ['file contents', 'patch', 'diff', 'blob'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Source Code/Patches nie ins Warehouse — Repo bleibt Source of Truth.', 'en' => 'Never land source code/patches in the warehouse — repo stays source of truth.'],
            ],
            [
                'entity' => 'Secret scanning / code scanning alerts',
                'fields' => ['secret', 'secret_type', 'html_url', 'push_protection bypass'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie Secret-Values; Curated: Counts/Status only; Mart: aggregates', 'en' => 'RAW: never secret values; Curated: counts/status only; Mart: aggregates'],
                'treatment' => ['de' => 'Secret Scanning Payloads — nie den Secret-Wert speichern; Alert Metadata höchstens.', 'en' => 'Secret scanning payloads — never store the secret value; alert metadata at most.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'user id, login, email (hashed), org id, repo id, PR number, commit sha.', 'en' => 'user id, login, email (hashed), org id, repo id, PR number, commit sha.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'User, Organization, Repository, Pull Request, Issue, Workflow Run, Audit Event.', 'en' => 'User, organization, repository, pull request, issue, workflow run, audit event.'],
            ],
            [
                'focus' => ['de' => 'REST / GraphQL / Audit log exports', 'en' => 'REST / GraphQL / Audit log exports'],
                'notes' => ['de' => 'API Exports und Org Audit Log verdoppeln Author-Emails und Membership-PII.', 'en' => 'API exports and org audit log duplicate author emails and membership PII.'],
            ],
            [
                'focus' => ['de' => 'Fork / sandbox org copies', 'en' => 'Fork / sandbox org copies'],
                'notes' => ['de' => 'Sandbox-Orgs und Forks nicht mit Prod-Delivery-Marts mischen.', 'en' => 'Do not mix sandbox orgs and forks with prod delivery marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Source blobs / file contents / patches',
                'category' => 'system',
                'reason' => ['de' => 'Code nie landen — Volumen, IP und Secret-Risiko.', 'en' => 'Never land code — volume, IP and secret risk.'],
            ],
            [
                'name' => 'Secret scanning secret values',
                'category' => 'system',
                'reason' => ['de' => 'Secret Values — nie speichern; Alert Status höchstens.', 'en' => 'Secret values — never store; alert status at most.'],
            ],
            [
                'name' => 'PR / issue / review comment bodies (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Freitext-PII und potenzielle Secrets — Metadata reicht.', 'en' => 'Free-text PII and potential secrets — metadata enough.'],
            ],
            [
                'name' => 'Actions logs / artifact binaries (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'CI Logs/Artifacts — teuer; Run Metadata reicht für KPIs.', 'en' => 'CI logs/artifacts — expensive; run metadata enough for KPIs.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Source code / patch / diff content',
                'reason' => ['de' => 'Repo bleibt Source of Truth — kein Warehouse-Clone.', 'en' => 'Repo stays source of truth — no warehouse clone.'],
            ],
            [
                'name' => 'Secret scanning secret values',
                'reason' => ['de' => 'Nie speichern — Alert Counts/Status only.', 'en' => 'Never store — alert counts/status only.'],
            ],
            [
                'name' => 'Author email cleartext in marts',
                'reason' => ['de' => 'login/id reicht für Contributor-KPIs.', 'en' => 'login/id is enough for contributor KPIs.'],
            ],
            [
                'name' => 'PR / issue body cleartext (bulk)',
                'reason' => ['de' => 'PII/Secrets — gezielt oder gar nicht.', 'en' => 'PII/secrets — selectively or not at all.'],
            ],
            [
                'name' => 'Actions artifact binaries',
                'reason' => ['de' => 'Kosten ohne KPI-Nutzen.', 'en' => 'Cost without KPI value.'],
            ],
        ],
    ],

    'sharepoint' => [
        'pii' => [
            [
                'entity' => 'Site / drive item metadata',
                'fields' => ['createdBy', 'lastModifiedBy', 'webUrl', 'name', 'listItem fields'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: user ids only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: user ids only; Mart: aggregates only'],
                'treatment' => ['de' => 'Document Metadata — Author als user id; Dateinamen können PII enthalten.', 'en' => 'Document metadata — author as user id; filenames can contain PII.'],
            ],
            [
                'entity' => 'File / page content',
                'fields' => ['driveItem content', 'file binary', 'page canvas body', 'version content'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Document Content nie landen — Metadata vs Content strikt trennen.', 'en' => 'Never land document content — strictly separate metadata vs content.'],
            ],
            [
                'entity' => 'Sharing links / permissions',
                'fields' => ['sharingLink', 'permission grants', 'sharedWith identities', 'anonymous links'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: counts/types only; Mart: keine Link-URLs', 'en' => 'RAW: restrict access; Curated: counts/types only; Mart: no link URLs'],
                'treatment' => ['de' => 'Sharing Links können Token enthalten — nie volle URLs; Typ/Scope aggregieren.', 'en' => 'Sharing links can contain tokens — never full URLs; aggregate type/scope.'],
            ],
            [
                'entity' => 'Sensitivity / Purview labels',
                'fields' => ['sensitivityLabel', 'retentionLabel', 'label assignment actor'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Label-IDs ok; Curated: Label-Name/Policy; Mart: aggregates', 'en' => 'RAW: label ids ok; Curated: label name/policy; Mart: aggregates'],
                'treatment' => ['de' => 'Sensitivity Labels sind Governance-Signale — Content darunter nie mitladen.', 'en' => 'Sensitivity labels are governance signals — never load underlying content.'],
            ],
            [
                'entity' => 'List columns with personal data',
                'fields' => ['Person/Group columns', 'email columns', 'custom PII fields'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Site Columns inventarisieren — Person-Felder oft zweite PII-Fläche.', 'en' => 'Inventory site columns — person fields are often a second PII surface.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'site id, drive id, item id, listItem id, user id, sensitivity label id.', 'en' => 'site id, drive id, item id, listItem id, user id, sensitivity label id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Site, Drive, DriveItem metadata, List, Permission, Sensitivity Label.', 'en' => 'Site, drive, driveItem metadata, list, permission, sensitivity label.'],
            ],
            [
                'focus' => ['de' => 'Graph / Search / Export copies', 'en' => 'Graph / Search / Export copies'],
                'notes' => ['de' => 'Graph Sites/Drives und Search Exports verdoppeln Author- und Sharing-PII.', 'en' => 'Graph sites/drives and Search exports duplicate author and sharing PII.'],
            ],
            [
                'focus' => ['de' => 'Tenant / geo copies', 'en' => 'Tenant / geo copies'],
                'notes' => ['de' => 'Multi-Geo und Archive Sites Retention getrennt von Content-KPIs halten.', 'en' => 'Keep multi-geo and archive site retention separate from content KPIs.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'DriveItem / page content binaries',
                'category' => 'system',
                'reason' => ['de' => 'Content nie landen — Metadata für Activity-KPIs reicht.', 'en' => 'Never land content — metadata enough for activity KPIs.'],
            ],
            [
                'name' => 'Sharing link full URLs / tokens',
                'category' => 'system',
                'reason' => ['de' => 'Tokenisierte Links — Leak-Risiko; Typ/Count höchstens.', 'en' => 'Tokenized links — leak risk; type/count at most.'],
            ],
            [
                'name' => 'File version content streams',
                'category' => 'system',
                'reason' => ['de' => 'Version Binaries — teuer und sensibel.', 'en' => 'Version binaries — expensive and sensitive.'],
            ],
            [
                'name' => 'Search indexed document bodies',
                'category' => 'system',
                'reason' => ['de' => 'Search Bodies verdoppeln Content-PII — Schema/Counts only.', 'en' => 'Search bodies duplicate content PII — schema/counts only.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Document / page file content',
                'reason' => ['de' => 'Metadata vs Content — Content nie im Warehouse.', 'en' => 'Metadata vs content — never content in the warehouse.'],
            ],
            [
                'name' => 'Sharing link URLs / tokens',
                'reason' => ['de' => 'Nie speichern — Sharing Type/Scope aggregieren.', 'en' => 'Never store — aggregate sharing type/scope.'],
            ],
            [
                'name' => 'Author email / displayName cleartext in marts',
                'reason' => ['de' => 'user id reicht für Collaboration-KPIs.', 'en' => 'user id is enough for collaboration KPIs.'],
            ],
            [
                'name' => 'Person column cleartext (bulk)',
                'reason' => ['de' => 'PII — Allowlist Columns oder drop.', 'en' => 'PII — allowlist columns or drop.'],
            ],
            [
                'name' => 'Version content streams',
                'reason' => ['de' => 'Kosten und Sensitivity ohne KPI-Nutzen.', 'en' => 'Cost and sensitivity without KPI value.'],
            ],
        ],
    ],

    'google-workspace' => [
        'pii' => [
            [
                'entity' => 'Admin SDK Directory user',
                'fields' => ['id', 'primaryEmail', 'name', 'phones', 'organizations', 'recoveryEmail'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: id only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Workspace User — primaryEmail/phones nur mit Legal-Basis; id als Key.', 'en' => 'Workspace user — primaryEmail/phones only with legal basis; id as key.'],
            ],
            [
                'entity' => 'Drive / Gmail content bodies',
                'fields' => ['Drive file content', 'Gmail message body', 'attachment data'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Drive/Gmail Bodies skip — nur Activity/Metadata für KPIs.', 'en' => 'Skip Drive/Gmail bodies — activity/metadata only for KPIs.'],
            ],
            [
                'entity' => 'Login / Reports activity',
                'fields' => ['actor.email', 'ipAddress', 'login_type', 'device'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: aggregiert / hashed; Mart: keine Klartext-IPs/Emails', 'en' => 'RAW: restrict access; Curated: aggregated / hashed; Mart: no cleartext IPs/emails'],
                'treatment' => ['de' => 'Login Activity ist Security-sensibel — Restrict; kurze Retention.', 'en' => 'Login activity is security-sensitive — restrict; short retention.'],
            ],
            [
                'entity' => 'Drive activity / sharing',
                'fields' => ['actor', 'target', 'permissionChange', 'sharedWith'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: ids only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: ids only; Mart: aggregates only'],
                'treatment' => ['de' => 'Drive Activity — Actor/Target als ids; keine File Bodies.', 'en' => 'Drive activity — actor/target as ids; no file bodies.'],
            ],
            [
                'entity' => 'Org unit / group membership',
                'fields' => ['orgUnitPath', 'group members', 'member emails'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: ids / OU path; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: ids / OU path; Mart: aggregates only'],
                'treatment' => ['de' => 'OU und Groups für MDM — Member Emails nicht in Default-Marts.', 'en' => 'OU and groups for MDM — member emails not in default marts.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'user id, primaryEmail (hashed), orgUnitPath, group id, file id, customer id.', 'en' => 'user id, primaryEmail (hashed), orgUnitPath, group id, file id, customer id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'User, OrgUnit, Group, Drive Activity metadata, Login/Reports events.', 'en' => 'User, org unit, group, Drive activity metadata, login/Reports events.'],
            ],
            [
                'focus' => ['de' => 'Admin SDK / Reports / Drive Activity exports', 'en' => 'Admin SDK / Reports / Drive Activity exports'],
                'notes' => ['de' => 'Directory, Reports API und Drive Activity verdoppeln Workforce-PII in Stages.', 'en' => 'Directory, Reports API and Drive Activity duplicate workforce PII into stages.'],
            ],
            [
                'focus' => ['de' => 'Test domain / sandbox copies', 'en' => 'Test domain / sandbox copies'],
                'notes' => ['de' => 'Test-Domains nicht mit Prod-Workspace-Marts mischen.', 'en' => 'Do not mix test domains with prod Workspace marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Gmail message / attachment bodies',
                'category' => 'system',
                'reason' => ['de' => 'Mail Bodies — nie landen; Metadata/Counts höchstens.', 'en' => 'Mail bodies — never land; metadata/counts at most.'],
            ],
            [
                'name' => 'Drive file content / export blobs',
                'category' => 'system',
                'reason' => ['de' => 'File Content — teuer und sensibel; Activity Metadata reicht.', 'en' => 'File content — expensive and sensitive; activity metadata enough.'],
            ],
            [
                'name' => 'Login report IP cleartext archives (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Security-PII — aggregieren oder hashen.', 'en' => 'Security PII — aggregate or hash.'],
            ],
            [
                'name' => 'Vault / eDiscovery export payloads',
                'category' => 'system',
                'reason' => ['de' => 'Legal Holds — getrennt von Analytics; nie Default-Load.', 'en' => 'Legal holds — separate from analytics; never default load.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Drive / Gmail content bodies',
                'reason' => ['de' => 'Bodies skip — nur Activity/Metadata KPIs.', 'en' => 'Skip bodies — activity/metadata KPIs only.'],
            ],
            [
                'name' => 'primaryEmail / phone cleartext in marts',
                'reason' => ['de' => 'user id reicht für Directory-KPIs.', 'en' => 'user id is enough for directory KPIs.'],
            ],
            [
                'name' => 'Login IP / device cleartext (bulk)',
                'reason' => ['de' => 'Security-PII — aggregiert oder hashed.', 'en' => 'Security PII — aggregated or hashed.'],
            ],
            [
                'name' => 'Vault / eDiscovery payloads',
                'reason' => ['de' => 'Legal — nicht in Default Analytics Stages.', 'en' => 'Legal — not in default analytics stages.'],
            ],
            [
                'name' => 'Test domain users in prod marts',
                'reason' => ['de' => 'Prod-Workspace-Marts sauber halten.', 'en' => 'Keep prod Workspace marts clean.'],
            ],
        ],
    ],
];
