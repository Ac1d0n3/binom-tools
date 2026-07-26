<?php

/**
 * Wave 8 supplier library entries — DMS / Content (full template depth).
 *
 * Emphasize document metadata, versions, ACLs and classification; do not load
 * file/binary bodies, OCR full-text dumps, renditions, or preview binaries by
 * default — metadata is sufficient for storage, retention and access KPIs.
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $dmsTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    return [
        [
            'id' => 'opentext',
            'domain' => 'dms',
            'order' => 290,
            'label' => ['de' => 'OpenText (Content Server)', 'en' => 'OpenText (Content Server)'],
            'shortPurpose' => [
                'de' => 'ECM-Kern: Document/Version/Folder, Category und Permissions — Metadata-Load, PII und DMS-Measures; keine Binärkörper oder OCR-Volltext.',
                'en' => 'ECM core: document/version/folder, category and permissions — metadata load, PII and DMS measures; no binary bodies or OCR full text.',
            ],
            'entities' => [
                [
                    'id' => 'document',
                    'label' => ['de' => 'Document', 'en' => 'Document'],
                    'description' => [
                        'de' => 'Content Server Document Node (DataID) — Name/MimeType/Category; Dateikörper nicht laden.',
                        'en' => 'Content Server document node (DataID) — name/MIME type/category; file body not loaded.',
                    ],
                    'grain' => ['de' => 'Ein Document (DataID)', 'en' => 'One document (DataID)'],
                    'role' => ['de' => 'Dimension / Fact-Anker', 'en' => 'Dimension / fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'document_version',
                    'label' => ['de' => 'Document Version', 'en' => 'Document version'],
                    'description' => [
                        'de' => 'DVersData Versionsdatensatz — VersionNum/FileSize/CreateDate je Document; keine Versions-Binaries.',
                        'en' => 'DVersData version record — version number/file size/create date per document; no version binaries.',
                    ],
                    'grain' => ['de' => 'Eine Version (DataID + VersionNum)', 'en' => 'One version (DataID + VersionNum)'],
                    'role' => ['de' => 'Ledger-Fact (high volume)', 'en' => 'Ledger fact (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'folder',
                    'label' => ['de' => 'Folder', 'en' => 'Folder'],
                    'description' => [
                        'de' => 'DTree Container-Node — hierarchische Ablagestruktur (Name/ParentID).',
                        'en' => 'DTree container node — hierarchical filing structure (name/ParentID).',
                    ],
                    'grain' => ['de' => 'Ein Folder (DataID)', 'en' => 'One folder (DataID)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'category',
                    'label' => ['de' => 'Category', 'en' => 'Category'],
                    'description' => [
                        'de' => 'LLAttrData Category/Attribute-Definition — RM-Klassifikation und Retention Schedule.',
                        'en' => 'LLAttrData category/attribute definition — RM classification and retention schedule.',
                    ],
                    'grain' => ['de' => 'Eine Category (CategoryID)', 'en' => 'One category (CategoryID)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'workflow',
                    'label' => ['de' => 'Workflow', 'en' => 'Workflow'],
                    'description' => [
                        'de' => 'WorkFlow/WorkTask-Instanz — Status und Step-Zuweisung auf einem Document.',
                        'en' => 'WorkFlow/WorkTask instance — status and step assignment on a document.',
                    ],
                    'grain' => ['de' => 'Eine Workflow-Instanz (WorkID)', 'en' => 'One workflow instance (WorkID)'],
                    'role' => ['de' => 'Process-Fact', 'en' => 'Process fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'permission',
                    'label' => ['de' => 'Permission', 'en' => 'Permission'],
                    'description' => [
                        'de' => 'DTreeAcl/Perms-Eintrag — Read/Write/Delete je Node und User oder Gruppe.',
                        'en' => 'DTreeAcl/Perms entry — read/write/delete per node and user or group.',
                    ],
                    'grain' => ['de' => 'Ein Permission Grant (NodeID + RightID)', 'en' => 'One permission grant (NodeID + RightID)'],
                    'role' => ['de' => 'Control-Fact', 'en' => 'Control fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'KUAF User-Datensatz — Name/Login/E-Mail/Department; Kern-Stammdaten mit PII.',
                        'en' => 'KUAF user record — name/login/email/department; core master data with PII.',
                    ],
                    'grain' => ['de' => 'Ein User (UserID)', 'en' => 'One user (UserID)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'workspace',
                    'label' => ['de' => 'Business Workspace', 'en' => 'Business workspace'],
                    'description' => [
                        'de' => 'Extended ECM Business Workspace — verknüpft Business-Objekt (z. B. SAP-Auftrag) mit Ablagestruktur.',
                        'en' => 'Extended ECM business workspace — links a business object (e.g. SAP order) to the filing structure.',
                    ],
                    'grain' => ['de' => 'Ein Workspace (DataID)', 'en' => 'One workspace (DataID)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Document', 'name' => 'DataID', 'role' => 'key', 'why' => ['de' => 'Document-Join', 'en' => 'Document join']],
                ['entity' => 'Document', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Dokumenttitel', 'en' => 'Document title']],
                ['entity' => 'Document', 'name' => 'MimeType', 'role' => 'dimension', 'why' => ['de' => 'Dateityp-Dim', 'en' => 'File type dim']],
                ['entity' => 'Document', 'name' => 'ParentID', 'role' => 'dimension', 'why' => ['de' => 'Folder-Rückjoin', 'en' => 'Folder back-join']],
                ['entity' => 'Document', 'name' => 'CategoryID', 'role' => 'dimension', 'why' => ['de' => 'Category-Rückjoin', 'en' => 'Category back-join']],
                ['entity' => 'Document', 'name' => 'CreateDate', 'role' => 'measure', 'why' => ['de' => 'Anlagedatum', 'en' => 'Creation date']],
                ['entity' => 'Document', 'name' => 'ModifyDate', 'role' => 'measure', 'why' => ['de' => 'Letzte Änderung', 'en' => 'Last modified']],
                ['entity' => 'Document', 'name' => 'OwnerID', 'role' => 'dimension', 'why' => ['de' => 'Owner-Rückjoin (User)', 'en' => 'Owner back-join (user)']],
                ['entity' => 'DocumentVersion', 'name' => 'DataID', 'role' => 'dimension', 'why' => ['de' => 'Document-Rückjoin', 'en' => 'Document back-join']],
                ['entity' => 'DocumentVersion', 'name' => 'VersionNum', 'role' => 'key', 'why' => ['de' => 'Versions-Join', 'en' => 'Version join']],
                ['entity' => 'DocumentVersion', 'name' => 'FileSize', 'role' => 'measure', 'why' => ['de' => 'Speichervolumen', 'en' => 'Storage volume']],
                ['entity' => 'DocumentVersion', 'name' => 'MimeType', 'role' => 'dimension', 'why' => ['de' => 'Dateityp je Version', 'en' => 'File type per version']],
                ['entity' => 'DocumentVersion', 'name' => 'CreateDate', 'role' => 'measure', 'why' => ['de' => 'Versionsdatum', 'en' => 'Version date']],
                ['entity' => 'DocumentVersion', 'name' => 'Creator', 'role' => 'dimension', 'why' => ['de' => 'Ersteller-Rückjoin', 'en' => 'Creator back-join']],
                ['entity' => 'Folder', 'name' => 'DataID', 'role' => 'key', 'why' => ['de' => 'Folder-Join', 'en' => 'Folder join']],
                ['entity' => 'Folder', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Ordnername', 'en' => 'Folder name']],
                ['entity' => 'Folder', 'name' => 'ParentID', 'role' => 'dimension', 'why' => ['de' => 'Hierarchie', 'en' => 'Hierarchy']],
                ['entity' => 'Folder', 'name' => 'SubType', 'role' => 'dimension', 'why' => ['de' => 'Ordner-/Workspace-Typ', 'en' => 'Folder/workspace type']],
                ['entity' => 'Category', 'name' => 'CategoryID', 'role' => 'key', 'why' => ['de' => 'Category-Join', 'en' => 'Category join']],
                ['entity' => 'Category', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Klassifikations-Label', 'en' => 'Classification label']],
                ['entity' => 'Category', 'name' => 'RetentionScheduleID', 'role' => 'dimension', 'why' => ['de' => 'Retention-Zuordnung', 'en' => 'Retention assignment']],
                ['entity' => 'Workflow', 'name' => 'WorkID', 'role' => 'key', 'why' => ['de' => 'Workflow-Join', 'en' => 'Workflow join']],
                ['entity' => 'Workflow', 'name' => 'DataID', 'role' => 'dimension', 'why' => ['de' => 'Document-Rückjoin', 'en' => 'Document back-join']],
                ['entity' => 'Workflow', 'name' => 'Status', 'role' => 'dimension', 'why' => ['de' => 'Workflow-Status', 'en' => 'Workflow status']],
                ['entity' => 'Workflow', 'name' => 'CurrentStep', 'role' => 'dimension', 'why' => ['de' => 'Aktueller Step', 'en' => 'Current step']],
                ['entity' => 'Workflow', 'name' => 'InitiatedDate', 'role' => 'measure', 'why' => ['de' => 'Start des Workflows', 'en' => 'Workflow start']],
                ['entity' => 'Workflow', 'name' => 'CompletedDate', 'role' => 'measure', 'why' => ['de' => 'Abschluss des Workflows', 'en' => 'Workflow completion']],
                ['entity' => 'Permission', 'name' => 'RightID', 'role' => 'key', 'why' => ['de' => 'Permission-Join', 'en' => 'Permission join']],
                ['entity' => 'Permission', 'name' => 'NodeID', 'role' => 'dimension', 'why' => ['de' => 'Node-Rückjoin', 'en' => 'Node back-join']],
                ['entity' => 'Permission', 'name' => 'UserID', 'role' => 'dimension', 'why' => ['de' => 'User-/Gruppen-Rückjoin', 'en' => 'User/group back-join']],
                ['entity' => 'Permission', 'name' => 'PermType', 'role' => 'dimension', 'why' => ['de' => 'Read/Write/Delete-Dim', 'en' => 'Read/write/delete dim']],
                ['entity' => 'User', 'name' => 'UserID', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'User', 'name' => 'Name', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'User', 'name' => 'Login', 'role' => 'pii', 'why' => ['de' => 'Login / PII', 'en' => 'Login / PII']],
                ['entity' => 'User', 'name' => 'EMail', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'User', 'name' => 'DeptID', 'role' => 'dimension', 'why' => ['de' => 'Abteilungs-Dim', 'en' => 'Department dim']],
                ['entity' => 'Workspace', 'name' => 'DataID', 'role' => 'key', 'why' => ['de' => 'Workspace-Join', 'en' => 'Workspace join']],
                ['entity' => 'Workspace', 'name' => 'WorkspaceType', 'role' => 'dimension', 'why' => ['de' => 'Workspace-Template-Dim', 'en' => 'Workspace template dim']],
                ['entity' => 'Workspace', 'name' => 'BusinessObjectID', 'role' => 'dimension', 'why' => ['de' => 'Business-Objekt-Rückjoin (z. B. SAP)', 'en' => 'Business object back-join (e.g. SAP)']],
            ],
            'skipTables' => [
                [
                    'name' => 'Document body / binary content (all versions)',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Volle Dateikörper — teuer, kein Analytics-Nutzen; Version-Metadata (Size/Mime/Creator) reicht.',
                        'en' => 'Full file bodies — expensive, no analytics value; version metadata (size/MIME/creator) suffices.',
                    ],
                ],
                [
                    'name' => 'OCR full-text extraction / content search index',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Volltext kann beliebige Dokumentinhalte inkl. PII enthalten — nicht default laden.',
                        'en' => 'Full text can contain arbitrary document content including PII — do not load by default.',
                    ],
                ],
                [
                    'name' => 'Rendition / preview thumbnails (PDF, HTML viewer cache)',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Abgeleitete Vorschau-Binaries — kein inkrementeller Analytics-Nutzen.',
                        'en' => 'Derived preview binaries — no incremental analytics value.',
                    ],
                ],
                [
                    'name' => 'Full audit trail (DAuditNew) bulk event log',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Technisches Audit — Volumen, wenig Mart-Nutzen; aggregierte Access-Counts bevorzugen.',
                        'en' => 'Technical audit — volume, little mart value; prefer aggregated access counts.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Document body / binary content (all versions)', 'reason' => ['de' => 'Kosten, kein Analytics-Nutzen', 'en' => 'Cost, no analytics value']],
                ['name' => 'OCR full-text extraction (bulk)', 'reason' => ['de' => 'Kann beliebiges PII enthalten', 'en' => 'Can contain arbitrary PII']],
                ['name' => 'Rendition / preview thumbnails', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen', 'en' => 'Binaries without analytics value']],
                ['name' => 'Full audit trail / event log', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History', 'en' => 'Volume — meta/agg instead of full history']],
            ],
            'dimensions' => [
                [
                    'id' => 'mime_type',
                    'label' => ['de' => 'MIME Type', 'en' => 'MIME type'],
                    'grain' => ['de' => 'document.MimeType', 'en' => 'document.MimeType'],
                    'notes' => ['de' => 'MIME-Type auf kleine Taxonomie (PDF/Office/Bild/Sonstiges) normalisieren.', 'en' => 'Normalize MIME type to a small taxonomy (PDF/office/image/other).'],
                ],
                [
                    'id' => 'category',
                    'label' => ['de' => 'Category', 'en' => 'Category'],
                    'grain' => ['de' => 'category.Name', 'en' => 'category.Name'],
                    'notes' => ['de' => 'RM-Klassifikation treibt Retention-KPIs — nicht mit Folder verwechseln.', 'en' => 'RM classification drives retention KPIs — do not confuse with folder.'],
                ],
                [
                    'id' => 'department',
                    'label' => ['de' => 'Department', 'en' => 'Department'],
                    'grain' => ['de' => 'user.DeptID', 'en' => 'user.DeptID'],
                    'notes' => ['de' => 'Department über HR-Referenztabelle auflösen, nicht auf Document denormalisieren.', 'en' => 'Resolve department via HR reference table, do not denormalize onto document.'],
                ],
                [
                    'id' => 'workflow_status',
                    'label' => ['de' => 'Workflow Status', 'en' => 'Workflow status'],
                    'grain' => ['de' => 'workflow.Status', 'en' => 'workflow.Status'],
                    'notes' => ['de' => 'In-Flight vs. überfällig vs. abgeschlossen unterscheiden.', 'en' => 'Distinguish in-flight vs overdue vs completed.'],
                ],
                [
                    'id' => 'permission_type',
                    'label' => ['de' => 'Permission Type', 'en' => 'Permission type'],
                    'grain' => ['de' => 'permission.PermType', 'en' => 'permission.PermType'],
                    'notes' => ['de' => 'Read/Write/Delete-Grants für Access-Review-KPIs getrennt tracken.', 'en' => 'Track read/write/delete grants separately for access-review KPIs.'],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['Name', 'Login', 'EMail'],
                    'treatment' => [
                        'de' => 'Staff-Identitätsdaten — taggen; UserID als Join bevorzugen.',
                        'en' => 'Staff identity data — tag as PII; prefer UserID as join.',
                    ],
                ],
                [
                    'entity' => 'Document',
                    'fields' => ['OwnerID display name'],
                    'treatment' => [
                        'de' => 'Owner-Anzeige löst auf User-PII auf — nur UserID joinen, Namen nicht denormalisieren.',
                        'en' => 'Owner display resolves to user PII — join by UserID only, do not denormalize the name.',
                    ],
                ],
                [
                    'entity' => 'OCR full-text / content preview',
                    'fields' => ['extracted document text'],
                    'treatment' => [
                        'de' => 'Volltext kann Drittparteien-PII aus Dokumentinhalten enthalten — nicht default laden.',
                        'en' => 'Full text can contain third-party PII from document content — do not load by default.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => ['de' => 'DataID, UserID, VersionNum, CategoryID.', 'en' => 'DataID, UserID, VersionNum, CategoryID.'],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => ['de' => 'Document, Folder, Category, Permission Grants — keine Content-Bodies, kein Volltext.', 'en' => 'Document, folder, category, permission grants — no content bodies, no full text.'],
                ],
            ],
            'measures' => [
                [
                    'id' => 'documents-created',
                    'example' => true,
                    'label' => ['de' => 'Documents Created', 'en' => 'Documents created'],
                    'question' => [
                        'de' => 'Wie viele Documents wurden in der Periode angelegt?',
                        'en' => 'How many documents were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM document WHERE CreateDate IN period',
                    'grain' => ['de' => 'Angelegtes Document', 'en' => 'Created document'],
                    'dimensions' => ['mime_type', 'category', 'department'],
                    'fieldsUsed' => ['Document.DataID', 'Document.CreateDate', 'Document.CategoryID'],
                    'sourceHints' => [
                        'de' => 'CreateDate aus DTree; System-/Temp-Folder-Content ausschließen.',
                        'en' => 'CreateDate from DTree; exclude system/temp folder content.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Category für Retention-relevante vs. transiente Documents trennen.',
                        'en' => 'Separate by category for retention-relevant vs transient documents.',
                    ],
                ],
                [
                    'id' => 'checked-out-documents',
                    'example' => true,
                    'label' => ['de' => 'Checked-out Documents', 'en' => 'Checked-out documents'],
                    'question' => [
                        'de' => 'Wie viele Documents sind aktuell ausgecheckt?',
                        'en' => 'How many documents are currently checked out?',
                    ],
                    'formula' => 'COUNT(*) FROM document WHERE ReserveUserID IS NOT NULL',
                    'grain' => ['de' => 'Ausgechecktes Document (Snapshot)', 'en' => 'Checked-out document (snapshot)'],
                    'dimensions' => ['mime_type', 'department'],
                    'fieldsUsed' => ['Document.DataID', 'Document.OwnerID'],
                    'sourceHints' => [
                        'de' => 'ReserveUserID-Feld aus DTree für Checkout-Status nutzen.',
                        'en' => 'Use the ReserveUserID field from DTree for checkout status.',
                    ],
                    'adapt' => [
                        'de' => 'Lange Checkouts (> 7 Tage) separat als Risiko flaggen.',
                        'en' => 'Separately flag long checkouts (> 7 days) as a risk.',
                    ],
                ],
                [
                    'id' => 'versions-count',
                    'example' => false,
                    'label' => ['de' => 'Versions Count', 'en' => 'Versions count'],
                    'question' => [
                        'de' => 'Wie viele neue Versionen wurden in der Periode erstellt?',
                        'en' => 'How many new versions were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM document_version WHERE CreateDate IN period',
                    'grain' => ['de' => 'Document Version', 'en' => 'Document version'],
                    'dimensions' => ['mime_type'],
                    'fieldsUsed' => ['DocumentVersion.DataID', 'DocumentVersion.VersionNum', 'DocumentVersion.CreateDate'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Tages-Aggregate je Category nutzen.',
                        'en' => 'Use daily aggregates per category at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Auto-generierte Rendition-Versionen aus Counts ausschließen.',
                        'en' => 'Exclude auto-generated rendition versions from counts.',
                    ],
                ],
                [
                    'id' => 'storage-volume-by-category',
                    'example' => false,
                    'label' => ['de' => 'Storage Volume by Category', 'en' => 'Storage volume by category'],
                    'question' => [
                        'de' => 'Wie viel Speichervolumen liegt je Category?',
                        'en' => 'How much storage volume sits in each category?',
                    ],
                    'formula' => 'SUM(FileSize) FROM document_version GROUP BY CategoryID',
                    'grain' => ['de' => 'Document Version (letzte je Document)', 'en' => 'Document version (latest per document)'],
                    'dimensions' => ['category'],
                    'fieldsUsed' => ['DocumentVersion.FileSize', 'Document.CategoryID'],
                    'sourceHints' => [
                        'de' => 'Nur letzte Version je Document zählen, nicht alle historischen Versionen summieren.',
                        'en' => 'Count only the latest version per document, not the sum of all historical versions.',
                    ],
                    'adapt' => [
                        'de' => 'Große Media-/Video-Kategorien separat ausweisen.',
                        'en' => 'Report large media/video categories separately.',
                    ],
                ],
                [
                    'id' => 'permission-grants-per-document',
                    'example' => false,
                    'label' => ['de' => 'Permission Grants per Document', 'en' => 'Permission grants per document'],
                    'question' => [
                        'de' => 'Wie viele aktive Permission Grants hat ein Document im Schnitt?',
                        'en' => 'How many active permission grants does the average document have?',
                    ],
                    'formula' => 'AVG(grant_count) FROM permission GROUP BY NodeID',
                    'grain' => ['de' => 'Document (Permission-Snapshot)', 'en' => 'Document (permission snapshot)'],
                    'dimensions' => ['permission_type', 'department'],
                    'fieldsUsed' => ['Permission.NodeID', 'Permission.UserID', 'Permission.PermType'],
                    'sourceHints' => [
                        'de' => 'Gruppen- vs. User-Grants unterscheiden — Gruppen-Expansion verzerrt Counts.',
                        'en' => 'Distinguish group vs user grants — group expansion distorts counts.',
                    ],
                    'adapt' => [
                        'de' => 'Public/Everyone-Grants separat als Risiko-Flag auswerten.',
                        'en' => 'Evaluate public/everyone grants separately as a risk flag.',
                    ],
                ],
            ],
            'tools' => $dmsTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'fabasoft',
            'domain' => 'dms',
            'order' => 300,
            'label' => ['de' => 'Fabasoft', 'en' => 'Fabasoft'],
            'shortPurpose' => [
                'de' => 'ECM-Kern: Object/Content Version/Room, Aktenplan und Berechtigungen — Metadata-Load, PII und DMS-Measures; keine Binärkörper oder Volltext.',
                'en' => 'ECM core: object/content version/room, filing plan and permissions — metadata load, PII and DMS measures; no binary bodies or full text.',
            ],
            'entities' => [
                [
                    'id' => 'object',
                    'label' => ['de' => 'Object (COO)', 'en' => 'Object (COO)'],
                    'description' => [
                        'de' => 'Fabasoft Component Object (Dokument) — ObjName/ObjSubject/ObjClass; Inhaltskörper nicht laden.',
                        'en' => 'Fabasoft component object (document) — ObjName/ObjSubject/ObjClass; content body not loaded.',
                    ],
                    'grain' => ['de' => 'Ein Object (ObjID / COO-Adresse)', 'en' => 'One object (ObjID / COO address)'],
                    'role' => ['de' => 'Dimension / Fact-Anker', 'en' => 'Dimension / fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'content_version',
                    'label' => ['de' => 'Content Version', 'en' => 'Content version'],
                    'description' => [
                        'de' => 'Objekt-Version/Revision — VersionNumber/ContentSize/ContentType je Object.',
                        'en' => 'Object version/revision — version number/content size/content type per object.',
                    ],
                    'grain' => ['de' => 'Eine Version (ObjID + VersionNumber)', 'en' => 'One version (ObjID + VersionNumber)'],
                    'role' => ['de' => 'Ledger-Fact (high volume)', 'en' => 'Ledger fact (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'room',
                    'label' => ['de' => 'Room', 'en' => 'Room'],
                    'description' => [
                        'de' => 'Ablagestruktur / Team Room Container — hierarchisches RoomName/ParentRoomID.',
                        'en' => 'Filing structure / team room container — hierarchical RoomName/ParentRoomID.',
                    ],
                    'grain' => ['de' => 'Ein Room (RoomID)', 'en' => 'One room (RoomID)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'filing_plan_category',
                    'label' => ['de' => 'Filing Plan Category', 'en' => 'Filing plan category'],
                    'description' => [
                        'de' => 'Aktenplan-Eintrag — Klassifikation und RetentionPeriod für Records Management.',
                        'en' => 'Filing plan entry — classification and retention period for records management.',
                    ],
                    'grain' => ['de' => 'Eine Category (CategoryID)', 'en' => 'One category (CategoryID)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'activity',
                    'label' => ['de' => 'Activity', 'en' => 'Activity'],
                    'description' => [
                        'de' => 'Workflow-Aktivität — Status/AssignedTo/DueDate-Routing auf einem Object.',
                        'en' => 'Workflow activity — status/assigned-to/due-date routing on an object.',
                    ],
                    'grain' => ['de' => 'Eine Activity (ActivityID)', 'en' => 'One activity (ActivityID)'],
                    'role' => ['de' => 'Process-Fact', 'en' => 'Process fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'permission',
                    'label' => ['de' => 'Permission', 'en' => 'Permission'],
                    'description' => [
                        'de' => 'Berechtigungs-Eintrag — PermissionLevel je Object und Person oder Org-Unit.',
                        'en' => 'Permission entry — permission level per object and person or org unit.',
                    ],
                    'grain' => ['de' => 'Ein Permission Grant (ObjID + PersonID)', 'en' => 'One permission grant (ObjID + PersonID)'],
                    'role' => ['de' => 'Control-Fact', 'en' => 'Control fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'person',
                    'label' => ['de' => 'Person', 'en' => 'Person'],
                    'description' => [
                        'de' => 'Person-Objekt — FullName/EMailAddress/OrganizationalUnit; Kern-Stammdaten mit PII.',
                        'en' => 'Person object — full name/email address/organizational unit; core master data with PII.',
                    ],
                    'grain' => ['de' => 'Eine Person (PersonID)', 'en' => 'One person (PersonID)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'organizational_unit',
                    'label' => ['de' => 'Organizational Unit', 'en' => 'Organizational unit'],
                    'description' => [
                        'de' => 'Org-Einheit — Abteilungs-/Team-Hierarchie, kein Personen-PII.',
                        'en' => 'Org unit — department/team hierarchy, no person-level PII.',
                    ],
                    'grain' => ['de' => 'Eine Org-Unit (OrgUnitID)', 'en' => 'One org unit (OrgUnitID)'],
                    'role' => ['de' => 'Staff-Dimension', 'en' => 'Staff dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Object', 'name' => 'ObjID', 'role' => 'key', 'why' => ['de' => 'Object-Join', 'en' => 'Object join']],
                ['entity' => 'Object', 'name' => 'ObjName', 'role' => 'dimension', 'why' => ['de' => 'Objektname', 'en' => 'Object name']],
                ['entity' => 'Object', 'name' => 'ObjSubject', 'role' => 'dimension', 'why' => ['de' => 'Betreff/Titel', 'en' => 'Subject/title']],
                ['entity' => 'Object', 'name' => 'ObjClass', 'role' => 'dimension', 'why' => ['de' => 'Objektklasse', 'en' => 'Object class']],
                ['entity' => 'Object', 'name' => 'RoomID', 'role' => 'dimension', 'why' => ['de' => 'Room-Rückjoin', 'en' => 'Room back-join']],
                ['entity' => 'Object', 'name' => 'CategoryID', 'role' => 'dimension', 'why' => ['de' => 'Aktenplan-Rückjoin', 'en' => 'Filing plan back-join']],
                ['entity' => 'Object', 'name' => 'ObjCreatedAt', 'role' => 'measure', 'why' => ['de' => 'Anlagedatum', 'en' => 'Creation date']],
                ['entity' => 'Object', 'name' => 'ObjModifiedAt', 'role' => 'measure', 'why' => ['de' => 'Letzte Änderung', 'en' => 'Last modified']],
                ['entity' => 'Object', 'name' => 'ObjOwner', 'role' => 'dimension', 'why' => ['de' => 'Owner-Rückjoin (Person)', 'en' => 'Owner back-join (person)']],
                ['entity' => 'ContentVersion', 'name' => 'ObjID', 'role' => 'dimension', 'why' => ['de' => 'Object-Rückjoin', 'en' => 'Object back-join']],
                ['entity' => 'ContentVersion', 'name' => 'VersionNumber', 'role' => 'key', 'why' => ['de' => 'Versions-Join', 'en' => 'Version join']],
                ['entity' => 'ContentVersion', 'name' => 'ContentSize', 'role' => 'measure', 'why' => ['de' => 'Speichervolumen', 'en' => 'Storage volume']],
                ['entity' => 'ContentVersion', 'name' => 'ContentType', 'role' => 'dimension', 'why' => ['de' => 'Inhaltstyp', 'en' => 'Content type']],
                ['entity' => 'ContentVersion', 'name' => 'CreatedBy', 'role' => 'dimension', 'why' => ['de' => 'Ersteller-Rückjoin', 'en' => 'Creator back-join']],
                ['entity' => 'ContentVersion', 'name' => 'CreatedAt', 'role' => 'measure', 'why' => ['de' => 'Versionsdatum', 'en' => 'Version date']],
                ['entity' => 'Room', 'name' => 'RoomID', 'role' => 'key', 'why' => ['de' => 'Room-Join', 'en' => 'Room join']],
                ['entity' => 'Room', 'name' => 'RoomName', 'role' => 'dimension', 'why' => ['de' => 'Room-Label', 'en' => 'Room label']],
                ['entity' => 'Room', 'name' => 'ParentRoomID', 'role' => 'dimension', 'why' => ['de' => 'Hierarchie', 'en' => 'Hierarchy']],
                ['entity' => 'FilingPlanCategory', 'name' => 'CategoryID', 'role' => 'key', 'why' => ['de' => 'Aktenplan-Join', 'en' => 'Filing plan join']],
                ['entity' => 'FilingPlanCategory', 'name' => 'CategoryName', 'role' => 'dimension', 'why' => ['de' => 'Klassifikations-Label', 'en' => 'Classification label']],
                ['entity' => 'FilingPlanCategory', 'name' => 'RetentionPeriod', 'role' => 'dimension', 'why' => ['de' => 'Aufbewahrungsfrist', 'en' => 'Retention period']],
                ['entity' => 'Activity', 'name' => 'ActivityID', 'role' => 'key', 'why' => ['de' => 'Activity-Join', 'en' => 'Activity join']],
                ['entity' => 'Activity', 'name' => 'ObjID', 'role' => 'dimension', 'why' => ['de' => 'Object-Rückjoin', 'en' => 'Object back-join']],
                ['entity' => 'Activity', 'name' => 'Status', 'role' => 'dimension', 'why' => ['de' => 'Activity-Status', 'en' => 'Activity status']],
                ['entity' => 'Activity', 'name' => 'AssignedTo', 'role' => 'dimension', 'why' => ['de' => 'Zuständiger-Rückjoin', 'en' => 'Assignee back-join']],
                ['entity' => 'Activity', 'name' => 'DueDate', 'role' => 'measure', 'why' => ['de' => 'Fälligkeitsdatum', 'en' => 'Due date']],
                ['entity' => 'Permission', 'name' => 'PermissionID', 'role' => 'key', 'why' => ['de' => 'Permission-Join', 'en' => 'Permission join']],
                ['entity' => 'Permission', 'name' => 'ObjID', 'role' => 'dimension', 'why' => ['de' => 'Object-Rückjoin', 'en' => 'Object back-join']],
                ['entity' => 'Permission', 'name' => 'PersonID', 'role' => 'dimension', 'why' => ['de' => 'Person-Rückjoin', 'en' => 'Person back-join']],
                ['entity' => 'Permission', 'name' => 'PermissionLevel', 'role' => 'dimension', 'why' => ['de' => 'Read/Write/Owner-Dim', 'en' => 'Read/write/owner dim']],
                ['entity' => 'Person', 'name' => 'PersonID', 'role' => 'key', 'why' => ['de' => 'Person-Join', 'en' => 'Person join']],
                ['entity' => 'Person', 'name' => 'FullName', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'Person', 'name' => 'EMailAddress', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'Person', 'name' => 'OrgUnitID', 'role' => 'dimension', 'why' => ['de' => 'Org-Unit-Rückjoin', 'en' => 'Org unit back-join']],
                ['entity' => 'OrganizationalUnit', 'name' => 'OrgUnitID', 'role' => 'key', 'why' => ['de' => 'Org-Unit-Join', 'en' => 'Org unit join']],
                ['entity' => 'OrganizationalUnit', 'name' => 'OrgUnitName', 'role' => 'dimension', 'why' => ['de' => 'Org-Unit-Label', 'en' => 'Org unit label']],
                ['entity' => 'OrganizationalUnit', 'name' => 'ParentOrgUnitID', 'role' => 'dimension', 'why' => ['de' => 'Hierarchie', 'en' => 'Hierarchy']],
            ],
            'skipTables' => [
                [
                    'name' => 'Content body / attachment binaries (all versions)',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Volle Inhaltskörper — teuer, kein Analytics-Nutzen; Versions-Metadata reicht.',
                        'en' => 'Full content bodies — expensive, no analytics value; version metadata suffices.',
                    ],
                ],
                [
                    'name' => 'Full-text index (Volltextindex)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Volltext kann beliebige Objektinhalte inkl. PII enthalten — nicht default laden.',
                        'en' => 'Full text can contain arbitrary object content including PII — do not load by default.',
                    ],
                ],
                [
                    'name' => 'Preview renditions (Viewer cache thumbnails)',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Abgeleitete Vorschau-Binaries — kein inkrementeller Analytics-Nutzen.',
                        'en' => 'Derived preview binaries — no incremental analytics value.',
                    ],
                ],
                [
                    'name' => 'Full history / journal log (Protokoll) bulk',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Technisches Protokoll — Volumen, wenig Mart-Nutzen; aggregierte Activity-Counts bevorzugen.',
                        'en' => 'Technical journal — volume, little mart value; prefer aggregated activity counts.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Content body / attachment binaries (all versions)', 'reason' => ['de' => 'Kosten, kein Analytics-Nutzen', 'en' => 'Cost, no analytics value']],
                ['name' => 'Full-text index (bulk)', 'reason' => ['de' => 'Kann beliebiges PII enthalten', 'en' => 'Can contain arbitrary PII']],
                ['name' => 'Preview renditions (Viewer cache)', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen', 'en' => 'Binaries without analytics value']],
                ['name' => 'Full history / journal log', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History', 'en' => 'Volume — meta/agg instead of full history']],
            ],
            'dimensions' => [
                [
                    'id' => 'content_type',
                    'label' => ['de' => 'Content Type', 'en' => 'Content type'],
                    'grain' => ['de' => 'content_version.ContentType', 'en' => 'content_version.ContentType'],
                    'notes' => ['de' => 'Content-Type auf kleine Taxonomie normalisieren.', 'en' => 'Normalize content type to a small taxonomy.'],
                ],
                [
                    'id' => 'filing_plan_category',
                    'label' => ['de' => 'Filing Plan Category', 'en' => 'Filing plan category'],
                    'grain' => ['de' => 'object.CategoryID', 'en' => 'object.CategoryID'],
                    'notes' => ['de' => 'Aktenplan treibt Retention-KPIs — nicht mit Room verwechseln.', 'en' => 'Filing plan drives retention KPIs — do not confuse with room.'],
                ],
                [
                    'id' => 'org_unit',
                    'label' => ['de' => 'Org Unit', 'en' => 'Org unit'],
                    'grain' => ['de' => 'person.OrgUnitID', 'en' => 'person.OrgUnitID'],
                    'notes' => ['de' => 'Org-Hierarchie über organizational_unit-Referenz auflösen, nicht denormalisieren.', 'en' => 'Resolve org hierarchy via organizational_unit reference, do not denormalize.'],
                ],
                [
                    'id' => 'activity_status',
                    'label' => ['de' => 'Activity Status', 'en' => 'Activity status'],
                    'grain' => ['de' => 'activity.Status', 'en' => 'activity.Status'],
                    'notes' => ['de' => 'Offen vs. überfällig vs. abgeschlossen unterscheiden.', 'en' => 'Distinguish open vs overdue vs completed.'],
                ],
                [
                    'id' => 'permission_level',
                    'label' => ['de' => 'Permission Level', 'en' => 'Permission level'],
                    'grain' => ['de' => 'permission.PermissionLevel', 'en' => 'permission.PermissionLevel'],
                    'notes' => ['de' => 'Read/Write/Owner-Grants für Access-Reviews getrennt tracken.', 'en' => 'Track read/write/owner grants separately for access reviews.'],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Person',
                    'fields' => ['FullName', 'EMailAddress'],
                    'treatment' => [
                        'de' => 'Staff-Identitätsdaten — taggen; PersonID als Join bevorzugen.',
                        'en' => 'Staff identity data — tag as PII; prefer PersonID as join.',
                    ],
                ],
                [
                    'entity' => 'Object',
                    'fields' => ['ObjOwner display name'],
                    'treatment' => [
                        'de' => 'Owner-Anzeige löst auf Person-PII auf — nur PersonID joinen.',
                        'en' => 'Owner display resolves to person PII — join by PersonID only.',
                    ],
                ],
                [
                    'entity' => 'Full-text index / content preview',
                    'fields' => ['extracted content body'],
                    'treatment' => [
                        'de' => 'Volltext kann Drittparteien-PII aus Objektinhalten enthalten — nicht default laden.',
                        'en' => 'Full text can contain third-party PII from object content — do not load by default.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => ['de' => 'ObjID, PersonID, VersionNumber, CategoryID.', 'en' => 'ObjID, PersonID, VersionNumber, CategoryID.'],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => ['de' => 'Object, Room, Filing Plan Category, Permission Grants — keine Content-Bodies, kein Volltext.', 'en' => 'Object, room, filing plan category, permission grants — no content bodies, no full text.'],
                ],
            ],
            'measures' => [
                [
                    'id' => 'objects-created',
                    'example' => true,
                    'label' => ['de' => 'Objects Created', 'en' => 'Objects created'],
                    'question' => [
                        'de' => 'Wie viele Objects wurden in der Periode angelegt?',
                        'en' => 'How many objects were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM object WHERE ObjCreatedAt IN period',
                    'grain' => ['de' => 'Angelegtes Object', 'en' => 'Created object'],
                    'dimensions' => ['content_type', 'filing_plan_category', 'org_unit'],
                    'fieldsUsed' => ['Object.ObjID', 'Object.ObjCreatedAt', 'Object.CategoryID'],
                    'sourceHints' => [
                        'de' => 'ObjCreatedAt aus COO-Objekt; System-/Temp-Rooms ausschließen.',
                        'en' => 'ObjCreatedAt from the COO object; exclude system/temp rooms.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Filing Plan Category für Retention-relevante vs. transiente Objects trennen.',
                        'en' => 'Separate by filing plan category for retention-relevant vs transient objects.',
                    ],
                ],
                [
                    'id' => 'active-checkouts',
                    'example' => true,
                    'label' => ['de' => 'Active Checkouts', 'en' => 'Active checkouts'],
                    'question' => [
                        'de' => 'Wie viele Objects sind aktuell ausgecheckt?',
                        'en' => 'How many objects are currently checked out?',
                    ],
                    'formula' => 'COUNT(*) FROM object WHERE CheckedOutBy IS NOT NULL',
                    'grain' => ['de' => 'Ausgechecktes Object (Snapshot)', 'en' => 'Checked-out object (snapshot)'],
                    'dimensions' => ['content_type', 'org_unit'],
                    'fieldsUsed' => ['Object.ObjID', 'Object.ObjOwner'],
                    'sourceHints' => [
                        'de' => 'CheckedOutBy-Flag für Checkout-Status nutzen.',
                        'en' => 'Use the CheckedOutBy flag for checkout status.',
                    ],
                    'adapt' => [
                        'de' => 'Lange Checkouts (> 7 Tage) separat als Risiko flaggen.',
                        'en' => 'Separately flag long checkouts (> 7 days) as a risk.',
                    ],
                ],
                [
                    'id' => 'versions-count',
                    'example' => false,
                    'label' => ['de' => 'Versions Count', 'en' => 'Versions count'],
                    'question' => [
                        'de' => 'Wie viele neue Versionen wurden in der Periode erstellt?',
                        'en' => 'How many new versions were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM content_version WHERE CreatedAt IN period',
                    'grain' => ['de' => 'Content Version', 'en' => 'Content version'],
                    'dimensions' => ['content_type'],
                    'fieldsUsed' => ['ContentVersion.ObjID', 'ContentVersion.VersionNumber', 'ContentVersion.CreatedAt'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Tages-Aggregate je Category nutzen.',
                        'en' => 'Use daily aggregates per category at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Auto-generierte Rendition-Versionen aus Counts ausschließen.',
                        'en' => 'Exclude auto-generated rendition versions from counts.',
                    ],
                ],
                [
                    'id' => 'storage-volume-by-category',
                    'example' => false,
                    'label' => ['de' => 'Storage Volume by Category', 'en' => 'Storage volume by category'],
                    'question' => [
                        'de' => 'Wie viel Speichervolumen liegt je Filing Plan Category?',
                        'en' => 'How much storage volume sits in each filing plan category?',
                    ],
                    'formula' => 'SUM(ContentSize) FROM content_version JOIN object ON object.ObjID = content_version.ObjID GROUP BY object.CategoryID',
                    'grain' => ['de' => 'Content Version (letzte je Object)', 'en' => 'Content version (latest per object)'],
                    'dimensions' => ['filing_plan_category'],
                    'fieldsUsed' => ['ContentVersion.ContentSize', 'Object.CategoryID'],
                    'sourceHints' => [
                        'de' => 'Nur letzte Version je Object zählen.',
                        'en' => 'Count only the latest version per object.',
                    ],
                    'adapt' => [
                        'de' => 'Große Media-Kategorien separat ausweisen.',
                        'en' => 'Report large media categories separately.',
                    ],
                ],
                [
                    'id' => 'average-activity-cycle-time',
                    'example' => false,
                    'label' => ['de' => 'Average Activity Cycle Time', 'en' => 'Average activity cycle time'],
                    'question' => [
                        'de' => 'Wie lange dauert eine Activity im Schnitt bis Completion?',
                        'en' => 'How long does an activity take on average until completion?',
                    ],
                    'formula' => "AVG(completed_at - assigned_at) FROM activity WHERE Status = 'completed'",
                    'grain' => ['de' => 'Activity', 'en' => 'Activity'],
                    'dimensions' => ['activity_status', 'org_unit'],
                    'fieldsUsed' => ['Activity.ActivityID', 'Activity.DueDate', 'Activity.Status'],
                    'sourceHints' => [
                        'de' => 'Nur abgeschlossene Activities zählen, offene separat tracken.',
                        'en' => 'Count only completed activities; track open ones separately.',
                    ],
                    'adapt' => [
                        'de' => 'Überfällige Activities (DueDate < heute, Status offen) separat als Backlog-KPI.',
                        'en' => 'Track overdue activities (DueDate < today, status open) separately as a backlog KPI.',
                    ],
                ],
            ],
            'tools' => $dmsTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'elo',
            'domain' => 'dms',
            'order' => 310,
            'label' => ['de' => 'ELO (ECM Suite)', 'en' => 'ELO (ECM Suite)'],
            'shortPurpose' => [
                'de' => 'ECM-Kern: Sord/Version/Mask, ObjKeys und Rights — Metadata-Load, PII und DMS-Measures; keine Binärkörper oder Volltext.',
                'en' => 'ECM core: Sord/version/mask, ObjKeys and rights — metadata load, PII and DMS measures; no binary bodies or full text.',
            ],
            'entities' => [
                [
                    'id' => 'sord_document',
                    'label' => ['de' => 'Sord Document', 'en' => 'Sord document'],
                    'description' => [
                        'de' => 'ELO Sord-Objekt (Type=Document) — Name/MaskName; Dokumentkörper nicht laden.',
                        'en' => 'ELO Sord object (type=document) — name/MaskName; document body not loaded.',
                    ],
                    'grain' => ['de' => 'Ein Sord (ObjId)', 'en' => 'One Sord (ObjId)'],
                    'role' => ['de' => 'Dimension / Fact-Anker', 'en' => 'Dimension / fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'sord_version',
                    'label' => ['de' => 'Sord Version', 'en' => 'Sord version'],
                    'description' => [
                        'de' => 'Sord-Versionshistorie — VersionNo/EditDate/FileSize je Sord.',
                        'en' => 'Sord version history — VersionNo/EditDate/FileSize per Sord.',
                    ],
                    'grain' => ['de' => 'Eine Version (ObjId + VersionNo)', 'en' => 'One version (ObjId + VersionNo)'],
                    'role' => ['de' => 'Ledger-Fact (high volume)', 'en' => 'Ledger fact (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'sord_folder',
                    'label' => ['de' => 'Sord Folder', 'en' => 'Sord folder'],
                    'description' => [
                        'de' => 'Sord-Objekt (Type=Folder) — hierarchische Archivstruktur.',
                        'en' => 'Sord object (type=folder) — hierarchical archive structure.',
                    ],
                    'grain' => ['de' => 'Ein Folder (ObjId)', 'en' => 'One folder (ObjId)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'mask',
                    'label' => ['de' => 'Mask', 'en' => 'Mask'],
                    'description' => [
                        'de' => 'Maske (Metadata-Template) — Feldschema je Dokumenttyp.',
                        'en' => 'Mask (metadata template) — field schema per document type.',
                    ],
                    'grain' => ['de' => 'Eine Mask (MaskId)', 'en' => 'One mask (MaskId)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'objkeys_metadata',
                    'label' => ['de' => 'ObjKeys Metadata', 'en' => 'ObjKeys metadata'],
                    'description' => [
                        'de' => 'ObjKeys Index-Feld-Werte (Key/Value) an einem Sord, gebunden an eine Mask.',
                        'en' => 'ObjKeys index field values (key/value) on a Sord, bound to a mask.',
                    ],
                    'grain' => ['de' => 'Ein Index-Feld-Wert (ObjId + KeyName)', 'en' => 'One index field value (ObjId + KeyName)'],
                    'role' => ['de' => 'Metadata-Fact', 'en' => 'Metadata fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'workflow_flow',
                    'label' => ['de' => 'Workflow Flow', 'en' => 'Workflow flow'],
                    'description' => [
                        'de' => 'ELO Flow-Instanz — Status/CurrentStep-Routing auf einem Sord.',
                        'en' => 'ELO flow instance — status/current-step routing on a Sord.',
                    ],
                    'grain' => ['de' => 'Eine Flow-Instanz (FlowId)', 'en' => 'One flow instance (FlowId)'],
                    'role' => ['de' => 'Process-Fact', 'en' => 'Process fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'acl_right',
                    'label' => ['de' => 'ACL Right', 'en' => 'ACL right'],
                    'description' => [
                        'de' => 'ELO Rights-Eintrag — AccessLevel je Sord und User oder Gruppe.',
                        'en' => 'ELO rights entry — access level per Sord and user or group.',
                    ],
                    'grain' => ['de' => 'Ein Right Grant (ObjId + UserId)', 'en' => 'One right grant (ObjId + UserId)'],
                    'role' => ['de' => 'Control-Fact', 'en' => 'Control fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'elo_user',
                    'label' => ['de' => 'ELO User', 'en' => 'ELO user'],
                    'description' => [
                        'de' => 'IDX User-Datensatz — UserName/FullName/Email/OrgUnit; Kern-Stammdaten mit PII.',
                        'en' => 'IDX user record — UserName/FullName/email/OrgUnit; core master data with PII.',
                    ],
                    'grain' => ['de' => 'Ein User (UserId)', 'en' => 'One user (UserId)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'SordDocument', 'name' => 'ObjId', 'role' => 'key', 'why' => ['de' => 'Sord-Join', 'en' => 'Sord join']],
                ['entity' => 'SordDocument', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Dokumentname', 'en' => 'Document name']],
                ['entity' => 'SordDocument', 'name' => 'MaskName', 'role' => 'dimension', 'why' => ['de' => 'Mask-Rückjoin', 'en' => 'Mask back-join']],
                ['entity' => 'SordDocument', 'name' => 'ParentId', 'role' => 'dimension', 'why' => ['de' => 'Folder-Rückjoin', 'en' => 'Folder back-join']],
                ['entity' => 'SordDocument', 'name' => 'CreateDate', 'role' => 'measure', 'why' => ['de' => 'Anlagedatum', 'en' => 'Creation date']],
                ['entity' => 'SordDocument', 'name' => 'OwnerId', 'role' => 'dimension', 'why' => ['de' => 'Owner-Rückjoin (User)', 'en' => 'Owner back-join (user)']],
                ['entity' => 'SordVersion', 'name' => 'ObjId', 'role' => 'dimension', 'why' => ['de' => 'Sord-Rückjoin', 'en' => 'Sord back-join']],
                ['entity' => 'SordVersion', 'name' => 'VersionNo', 'role' => 'key', 'why' => ['de' => 'Versions-Join', 'en' => 'Version join']],
                ['entity' => 'SordVersion', 'name' => 'FileSize', 'role' => 'measure', 'why' => ['de' => 'Speichervolumen', 'en' => 'Storage volume']],
                ['entity' => 'SordVersion', 'name' => 'EditDate', 'role' => 'measure', 'why' => ['de' => 'Versionsdatum', 'en' => 'Version date']],
                ['entity' => 'SordVersion', 'name' => 'Editor', 'role' => 'dimension', 'why' => ['de' => 'Bearbeiter-Rückjoin', 'en' => 'Editor back-join']],
                ['entity' => 'SordFolder', 'name' => 'ObjId', 'role' => 'key', 'why' => ['de' => 'Folder-Join', 'en' => 'Folder join']],
                ['entity' => 'SordFolder', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Ordnername', 'en' => 'Folder name']],
                ['entity' => 'SordFolder', 'name' => 'ParentId', 'role' => 'dimension', 'why' => ['de' => 'Hierarchie', 'en' => 'Hierarchy']],
                ['entity' => 'Mask', 'name' => 'MaskId', 'role' => 'key', 'why' => ['de' => 'Mask-Join', 'en' => 'Mask join']],
                ['entity' => 'Mask', 'name' => 'MaskName', 'role' => 'dimension', 'why' => ['de' => 'Mask-Label', 'en' => 'Mask label']],
                ['entity' => 'Mask', 'name' => 'FieldCount', 'role' => 'dimension', 'why' => ['de' => 'Feldanzahl-Dim', 'en' => 'Field count dim']],
                ['entity' => 'ObjKeysMetadata', 'name' => 'ObjId', 'role' => 'dimension', 'why' => ['de' => 'Sord-Rückjoin', 'en' => 'Sord back-join']],
                ['entity' => 'ObjKeysMetadata', 'name' => 'KeyName', 'role' => 'key', 'why' => ['de' => 'Index-Feld-Name', 'en' => 'Index field name']],
                ['entity' => 'ObjKeysMetadata', 'name' => 'KeyValue', 'role' => 'dimension', 'why' => ['de' => 'Index-Feld-Wert', 'en' => 'Index field value']],
                ['entity' => 'ObjKeysMetadata', 'name' => 'MaskId', 'role' => 'dimension', 'why' => ['de' => 'Mask-Rückjoin', 'en' => 'Mask back-join']],
                ['entity' => 'WorkflowFlow', 'name' => 'FlowId', 'role' => 'key', 'why' => ['de' => 'Flow-Join', 'en' => 'Flow join']],
                ['entity' => 'WorkflowFlow', 'name' => 'ObjId', 'role' => 'dimension', 'why' => ['de' => 'Sord-Rückjoin', 'en' => 'Sord back-join']],
                ['entity' => 'WorkflowFlow', 'name' => 'Status', 'role' => 'dimension', 'why' => ['de' => 'Flow-Status', 'en' => 'Flow status']],
                ['entity' => 'WorkflowFlow', 'name' => 'CurrentStep', 'role' => 'dimension', 'why' => ['de' => 'Aktueller Step', 'en' => 'Current step']],
                ['entity' => 'WorkflowFlow', 'name' => 'StartDate', 'role' => 'measure', 'why' => ['de' => 'Start des Flows', 'en' => 'Flow start']],
                ['entity' => 'AclRight', 'name' => 'RightId', 'role' => 'key', 'why' => ['de' => 'Right-Join', 'en' => 'Right join']],
                ['entity' => 'AclRight', 'name' => 'ObjId', 'role' => 'dimension', 'why' => ['de' => 'Sord-Rückjoin', 'en' => 'Sord back-join']],
                ['entity' => 'AclRight', 'name' => 'UserId', 'role' => 'dimension', 'why' => ['de' => 'User-/Gruppen-Rückjoin', 'en' => 'User/group back-join']],
                ['entity' => 'AclRight', 'name' => 'AccessLevel', 'role' => 'dimension', 'why' => ['de' => 'Read/Write/Delete-Dim', 'en' => 'Read/write/delete dim']],
                ['entity' => 'EloUser', 'name' => 'UserId', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'EloUser', 'name' => 'UserName', 'role' => 'pii', 'why' => ['de' => 'Login / PII', 'en' => 'Login / PII']],
                ['entity' => 'EloUser', 'name' => 'FullName', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'EloUser', 'name' => 'Email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'EloUser', 'name' => 'OrgUnit', 'role' => 'dimension', 'why' => ['de' => 'Org-Unit-Dim', 'en' => 'Org unit dim']],
            ],
            'skipTables' => [
                [
                    'name' => 'Document body / archived file content',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Volle Dateikörper — teuer, kein Analytics-Nutzen; Versions-Metadata reicht.',
                        'en' => 'Full file bodies — expensive, no analytics value; version metadata suffices.',
                    ],
                ],
                [
                    'name' => 'Full-text index (Volltextsuche)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Volltext kann beliebige Dokumentinhalte inkl. PII enthalten — nicht default laden.',
                        'en' => 'Full text can contain arbitrary document content including PII — do not load by default.',
                    ],
                ],
                [
                    'name' => 'Preview renditions (TIFF/Viewer cache)',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Abgeleitete Vorschau-Binaries — kein inkrementeller Analytics-Nutzen.',
                        'en' => 'Derived preview binaries — no incremental analytics value.',
                    ],
                ],
                [
                    'name' => 'Full journal / change log (bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Technisches Journal — Volumen, wenig Mart-Nutzen; aggregierte Access-Counts bevorzugen.',
                        'en' => 'Technical journal — volume, little mart value; prefer aggregated access counts.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Document body / archived file content', 'reason' => ['de' => 'Kosten, kein Analytics-Nutzen', 'en' => 'Cost, no analytics value']],
                ['name' => 'Full-text index (bulk)', 'reason' => ['de' => 'Kann beliebiges PII enthalten', 'en' => 'Can contain arbitrary PII']],
                ['name' => 'Preview renditions (Viewer cache)', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen', 'en' => 'Binaries without analytics value']],
                ['name' => 'Full journal / change log', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History', 'en' => 'Volume — meta/agg instead of full history']],
            ],
            'dimensions' => [
                [
                    'id' => 'mask',
                    'label' => ['de' => 'Mask', 'en' => 'Mask'],
                    'grain' => ['de' => 'sord_document.MaskName', 'en' => 'sord_document.MaskName'],
                    'notes' => ['de' => 'Mask legt erwartete Index-Felder fest — Dokumente über Masken hinweg nicht mischen.', 'en' => 'Mask defines expected index fields — do not mix documents across masks.'],
                ],
                [
                    'id' => 'org_unit',
                    'label' => ['de' => 'Org Unit', 'en' => 'Org unit'],
                    'grain' => ['de' => 'elo_user.OrgUnit', 'en' => 'elo_user.OrgUnit'],
                    'notes' => ['de' => 'Org-Hierarchie über HR-Referenztabelle auflösen.', 'en' => 'Resolve org hierarchy via HR reference table.'],
                ],
                [
                    'id' => 'workflow_status',
                    'label' => ['de' => 'Workflow Status', 'en' => 'Workflow status'],
                    'grain' => ['de' => 'workflow_flow.Status', 'en' => 'workflow_flow.Status'],
                    'notes' => ['de' => 'In-Flight vs. überfällig vs. abgeschlossen unterscheiden.', 'en' => 'Distinguish in-flight vs overdue vs completed.'],
                ],
                [
                    'id' => 'access_level',
                    'label' => ['de' => 'Access Level', 'en' => 'Access level'],
                    'grain' => ['de' => 'acl_right.AccessLevel', 'en' => 'acl_right.AccessLevel'],
                    'notes' => ['de' => 'Read/Write/Delete/Admin-Grants für Access-Reviews getrennt tracken.', 'en' => 'Track read/write/delete/admin grants separately for access reviews.'],
                ],
                [
                    'id' => 'key_name',
                    'label' => ['de' => 'Key Name', 'en' => 'Key name'],
                    'grain' => ['de' => 'objkeys_metadata.KeyName', 'en' => 'objkeys_metadata.KeyName'],
                    'notes' => ['de' => 'Index-Feldnamen unterscheiden sich je Mask — vor Aggregation über Mask-Schema normalisieren.', 'en' => 'Index field names differ per mask — normalize via mask schema before aggregation.'],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'EloUser',
                    'fields' => ['UserName', 'FullName', 'Email'],
                    'treatment' => [
                        'de' => 'Staff-Identitätsdaten — taggen; UserId als Join bevorzugen.',
                        'en' => 'Staff identity data — tag as PII; prefer UserId as join.',
                    ],
                ],
                [
                    'entity' => 'SordDocument',
                    'fields' => ['OwnerId display name'],
                    'treatment' => [
                        'de' => 'Owner-Anzeige löst auf User-PII auf — nur UserId joinen.',
                        'en' => 'Owner display resolves to user PII — join by UserId only.',
                    ],
                ],
                [
                    'entity' => 'ObjKeysMetadata',
                    'fields' => ['KeyValue for person-related masks (e.g. Kunde, Ansprechpartner)'],
                    'treatment' => [
                        'de' => 'Index-Feld-Werte können je Mask Gegenpartei-Namen/PII enthalten — pro Mask vor dem Laden reviewen.',
                        'en' => 'Index field values can contain counterparty names/PII depending on the mask — review per mask before loading.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => ['de' => 'ObjId, UserId, VersionNo, MaskId.', 'en' => 'ObjId, UserId, VersionNo, MaskId.'],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => ['de' => 'Sord Document, Sord Folder, Mask, ACL Right Grants — keine Dokumentkörper, kein Volltext.', 'en' => 'Sord document, Sord folder, mask, ACL right grants — no document bodies, no full text.'],
                ],
            ],
            'measures' => [
                [
                    'id' => 'documents-created',
                    'example' => true,
                    'label' => ['de' => 'Documents Created', 'en' => 'Documents created'],
                    'question' => [
                        'de' => 'Wie viele Sord-Dokumente wurden in der Periode angelegt?',
                        'en' => 'How many Sord documents were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM sord_document WHERE CreateDate IN period',
                    'grain' => ['de' => 'Angelegtes Sord-Dokument', 'en' => 'Created Sord document'],
                    'dimensions' => ['mask', 'org_unit'],
                    'fieldsUsed' => ['SordDocument.ObjId', 'SordDocument.CreateDate', 'SordDocument.MaskName'],
                    'sourceHints' => [
                        'de' => 'CreateDate aus dem Sord-Kopf; System-/Papierkorb-Ordner ausschließen.',
                        'en' => 'CreateDate from the Sord header; exclude system/trash folders.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Mask für Rechnungen vs. Verträge vs. sonstige Dokumente trennen.',
                        'en' => 'Separate by mask for invoices vs contracts vs other documents.',
                    ],
                ],
                [
                    'id' => 'checked-out-documents',
                    'example' => true,
                    'label' => ['de' => 'Checked-out Documents', 'en' => 'Checked-out documents'],
                    'question' => [
                        'de' => 'Wie viele Sord-Dokumente sind aktuell gesperrt (Checkout)?',
                        'en' => 'How many Sord documents are currently locked (checked out)?',
                    ],
                    'formula' => 'COUNT(*) FROM sord_document WHERE LockedBy IS NOT NULL',
                    'grain' => ['de' => 'Gesperrtes Sord-Dokument (Snapshot)', 'en' => 'Locked Sord document (snapshot)'],
                    'dimensions' => ['mask', 'org_unit'],
                    'fieldsUsed' => ['SordDocument.ObjId', 'SordDocument.OwnerId'],
                    'sourceHints' => [
                        'de' => 'LockedBy-Flag für Checkout-Status nutzen.',
                        'en' => 'Use the LockedBy flag for checkout status.',
                    ],
                    'adapt' => [
                        'de' => 'Lange Sperren (> 7 Tage) separat als Risiko flaggen.',
                        'en' => 'Separately flag long locks (> 7 days) as a risk.',
                    ],
                ],
                [
                    'id' => 'versions-count',
                    'example' => false,
                    'label' => ['de' => 'Versions Count', 'en' => 'Versions count'],
                    'question' => [
                        'de' => 'Wie viele neue Versionen wurden in der Periode erstellt?',
                        'en' => 'How many new versions were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM sord_version WHERE EditDate IN period',
                    'grain' => ['de' => 'Sord Version', 'en' => 'Sord version'],
                    'dimensions' => ['mask'],
                    'fieldsUsed' => ['SordVersion.ObjId', 'SordVersion.VersionNo', 'SordVersion.EditDate'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Tages-Aggregate je Mask nutzen.',
                        'en' => 'Use daily aggregates per mask at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Auto-generierte Rendition-Versionen aus Counts ausschließen.',
                        'en' => 'Exclude auto-generated rendition versions from counts.',
                    ],
                ],
                [
                    'id' => 'storage-volume-by-mask',
                    'example' => false,
                    'label' => ['de' => 'Storage Volume by Mask', 'en' => 'Storage volume by mask'],
                    'question' => [
                        'de' => 'Wie viel Speichervolumen liegt je Mask?',
                        'en' => 'How much storage volume sits in each mask?',
                    ],
                    'formula' => 'SUM(FileSize) FROM sord_version JOIN sord_document ON sord_document.ObjId = sord_version.ObjId GROUP BY sord_document.MaskName',
                    'grain' => ['de' => 'Sord Version (letzte je Dokument)', 'en' => 'Sord version (latest per document)'],
                    'dimensions' => ['mask'],
                    'fieldsUsed' => ['SordVersion.FileSize', 'SordDocument.MaskName'],
                    'sourceHints' => [
                        'de' => 'Nur letzte Version je Dokument zählen.',
                        'en' => 'Count only the latest version per document.',
                    ],
                    'adapt' => [
                        'de' => 'Große Scan-/Bild-Masken separat ausweisen.',
                        'en' => 'Report large scan/image masks separately.',
                    ],
                ],
                [
                    'id' => 'rights-grants-per-document',
                    'example' => false,
                    'label' => ['de' => 'Rights Grants per Document', 'en' => 'Rights grants per document'],
                    'question' => [
                        'de' => 'Wie viele aktive Rights Grants hat ein Dokument im Schnitt?',
                        'en' => 'How many active rights grants does the average document have?',
                    ],
                    'formula' => 'AVG(grant_count) FROM acl_right GROUP BY ObjId',
                    'grain' => ['de' => 'Sord-Dokument (Rights-Snapshot)', 'en' => 'Sord document (rights snapshot)'],
                    'dimensions' => ['access_level', 'org_unit'],
                    'fieldsUsed' => ['AclRight.ObjId', 'AclRight.UserId', 'AclRight.AccessLevel'],
                    'sourceHints' => [
                        'de' => 'Gruppen- vs. Einzel-Rechte unterscheiden — Gruppen-Expansion verzerrt Counts.',
                        'en' => 'Distinguish group vs individual rights — group expansion distorts counts.',
                    ],
                    'adapt' => [
                        'de' => '"Jeder"/Public-Grants separat als Risiko-Flag auswerten.',
                        'en' => 'Evaluate "everyone"/public grants separately as a risk flag.',
                    ],
                ],
            ],
            'tools' => $dmsTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'docuware',
            'domain' => 'dms',
            'order' => 320,
            'label' => ['de' => 'DocuWare', 'en' => 'DocuWare'],
            'shortPurpose' => [
                'de' => 'DMS-Kern: Document/Revision/File Cabinet, Index Fields und Access Rights — Metadata-Load, PII und DMS-Measures; keine Binärkörper oder Volltext.',
                'en' => 'DMS core: document/revision/file cabinet, index fields and access rights — metadata load, PII and DMS measures; no binary bodies or full text.',
            ],
            'entities' => [
                [
                    'id' => 'document',
                    'label' => ['de' => 'Document', 'en' => 'Document'],
                    'description' => [
                        'de' => 'DocuWare Document (DWDOCID) in einem File Cabinet — DocumentTitle/ContentType; Inhalt nicht laden.',
                        'en' => 'DocuWare document (DWDOCID) in a file cabinet — document title/content type; content not loaded.',
                    ],
                    'grain' => ['de' => 'Ein Document (DWDOCID)', 'en' => 'One document (DWDOCID)'],
                    'role' => ['de' => 'Dimension / Fact-Anker', 'en' => 'Dimension / fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'revision',
                    'label' => ['de' => 'Revision', 'en' => 'Revision'],
                    'description' => [
                        'de' => 'Revisionshistorie — VersionNumber/RevisionDate/FileSize je Document.',
                        'en' => 'Revision history — version number/revision date/file size per document.',
                    ],
                    'grain' => ['de' => 'Eine Revision (DWDOCID + VersionNumber)', 'en' => 'One revision (DWDOCID + VersionNumber)'],
                    'role' => ['de' => 'Ledger-Fact (high volume)', 'en' => 'Ledger fact (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'file_cabinet',
                    'label' => ['de' => 'File Cabinet', 'en' => 'File cabinet'],
                    'description' => [
                        'de' => 'Aktenschrank-Container — CabinetName und Storage-Dialog-Konfiguration.',
                        'en' => 'File cabinet container — cabinet name and storage dialog configuration.',
                    ],
                    'grain' => ['de' => 'Ein File Cabinet (CabinetId)', 'en' => 'One file cabinet (CabinetId)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'index_field',
                    'label' => ['de' => 'Index Field', 'en' => 'Index field'],
                    'description' => [
                        'de' => 'Index-Feld-Schema je File Cabinet (Feldname/Typ), nicht die Feldwerte.',
                        'en' => 'Index field schema per file cabinet (field name/type), not field values.',
                    ],
                    'grain' => ['de' => 'Ein Index Field (FieldId)', 'en' => 'One index field (FieldId)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'workflow_task',
                    'label' => ['de' => 'Workflow Task', 'en' => 'Workflow task'],
                    'description' => [
                        'de' => 'DocuWare Workflow Manager Task-Instanz — TaskStatus/AssignedTo/DueDate.',
                        'en' => 'DocuWare Workflow Manager task instance — task status/assigned-to/due date.',
                    ],
                    'grain' => ['de' => 'Ein Task (TaskId)', 'en' => 'One task (TaskId)'],
                    'role' => ['de' => 'Process-Fact', 'en' => 'Process fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'access_right',
                    'label' => ['de' => 'Access Right', 'en' => 'Access right'],
                    'description' => [
                        'de' => 'Rights/Roles-Eintrag — RightsLevel je File Cabinet und User.',
                        'en' => 'Rights/roles entry — rights level per file cabinet and user.',
                    ],
                    'grain' => ['de' => 'Ein Right Grant (CabinetId + UserId)', 'en' => 'One right grant (CabinetId + UserId)'],
                    'role' => ['de' => 'Control-Fact', 'en' => 'Control fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'stamp',
                    'label' => ['de' => 'Stamp', 'en' => 'Stamp'],
                    'description' => [
                        'de' => 'Elektronischer Stempel (z. B. Freigabe/Ablehnung), angewendet auf ein Document.',
                        'en' => 'Electronic stamp instance (e.g. approval/rejection) applied to a document.',
                    ],
                    'grain' => ['de' => 'Eine Stempel-Anwendung (StampId)', 'en' => 'One stamp application (StampId)'],
                    'role' => ['de' => 'Approval-Fact', 'en' => 'Approval fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'DocuWare Organization User — UserName/Email/OrganizationId; Kern-Stammdaten mit PII.',
                        'en' => 'DocuWare organization user — username/email/organization ID; core master data with PII.',
                    ],
                    'grain' => ['de' => 'Ein User (UserId)', 'en' => 'One user (UserId)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'Document', 'name' => 'DWDOCID', 'role' => 'key', 'why' => ['de' => 'Document-Join', 'en' => 'Document join']],
                ['entity' => 'Document', 'name' => 'CabinetId', 'role' => 'dimension', 'why' => ['de' => 'Cabinet-Rückjoin', 'en' => 'Cabinet back-join']],
                ['entity' => 'Document', 'name' => 'ContentType', 'role' => 'dimension', 'why' => ['de' => 'Inhaltstyp', 'en' => 'Content type']],
                ['entity' => 'Document', 'name' => 'DocumentTitle', 'role' => 'dimension', 'why' => ['de' => 'Dokumenttitel', 'en' => 'Document title']],
                ['entity' => 'Document', 'name' => 'OwnerId', 'role' => 'dimension', 'why' => ['de' => 'Owner-Rückjoin (User)', 'en' => 'Owner back-join (user)']],
                ['entity' => 'Document', 'name' => 'StoreDate', 'role' => 'measure', 'why' => ['de' => 'Speicherdatum', 'en' => 'Store date']],
                ['entity' => 'Document', 'name' => 'ModifiedDate', 'role' => 'measure', 'why' => ['de' => 'Letzte Änderung', 'en' => 'Last modified']],
                ['entity' => 'Revision', 'name' => 'DWDOCID', 'role' => 'dimension', 'why' => ['de' => 'Document-Rückjoin', 'en' => 'Document back-join']],
                ['entity' => 'Revision', 'name' => 'VersionNumber', 'role' => 'key', 'why' => ['de' => 'Revisions-Join', 'en' => 'Revision join']],
                ['entity' => 'Revision', 'name' => 'FileSize', 'role' => 'measure', 'why' => ['de' => 'Speichervolumen', 'en' => 'Storage volume']],
                ['entity' => 'Revision', 'name' => 'RevisionDate', 'role' => 'measure', 'why' => ['de' => 'Revisionsdatum', 'en' => 'Revision date']],
                ['entity' => 'Revision', 'name' => 'RevisedBy', 'role' => 'dimension', 'why' => ['de' => 'Bearbeiter-Rückjoin', 'en' => 'Editor back-join']],
                ['entity' => 'FileCabinet', 'name' => 'CabinetId', 'role' => 'key', 'why' => ['de' => 'Cabinet-Join', 'en' => 'Cabinet join']],
                ['entity' => 'FileCabinet', 'name' => 'CabinetName', 'role' => 'dimension', 'why' => ['de' => 'Cabinet-Label', 'en' => 'Cabinet label']],
                ['entity' => 'FileCabinet', 'name' => 'StorageDialogId', 'role' => 'dimension', 'why' => ['de' => 'Storage-Dialog-Rückjoin', 'en' => 'Storage dialog back-join']],
                ['entity' => 'IndexField', 'name' => 'FieldId', 'role' => 'key', 'why' => ['de' => 'Feld-Join', 'en' => 'Field join']],
                ['entity' => 'IndexField', 'name' => 'CabinetId', 'role' => 'dimension', 'why' => ['de' => 'Cabinet-Rückjoin', 'en' => 'Cabinet back-join']],
                ['entity' => 'IndexField', 'name' => 'FieldName', 'role' => 'dimension', 'why' => ['de' => 'Feldname', 'en' => 'Field name']],
                ['entity' => 'IndexField', 'name' => 'FieldType', 'role' => 'dimension', 'why' => ['de' => 'Feldtyp', 'en' => 'Field type']],
                ['entity' => 'WorkflowTask', 'name' => 'TaskId', 'role' => 'key', 'why' => ['de' => 'Task-Join', 'en' => 'Task join']],
                ['entity' => 'WorkflowTask', 'name' => 'DWDOCID', 'role' => 'dimension', 'why' => ['de' => 'Document-Rückjoin', 'en' => 'Document back-join']],
                ['entity' => 'WorkflowTask', 'name' => 'TaskStatus', 'role' => 'dimension', 'why' => ['de' => 'Task-Status', 'en' => 'Task status']],
                ['entity' => 'WorkflowTask', 'name' => 'AssignedTo', 'role' => 'dimension', 'why' => ['de' => 'Zuständiger-Rückjoin', 'en' => 'Assignee back-join']],
                ['entity' => 'WorkflowTask', 'name' => 'DueDate', 'role' => 'measure', 'why' => ['de' => 'Fälligkeitsdatum', 'en' => 'Due date']],
                ['entity' => 'AccessRight', 'name' => 'RightId', 'role' => 'key', 'why' => ['de' => 'Right-Join', 'en' => 'Right join']],
                ['entity' => 'AccessRight', 'name' => 'CabinetId', 'role' => 'dimension', 'why' => ['de' => 'Cabinet-Rückjoin', 'en' => 'Cabinet back-join']],
                ['entity' => 'AccessRight', 'name' => 'UserId', 'role' => 'dimension', 'why' => ['de' => 'User-Rückjoin', 'en' => 'User back-join']],
                ['entity' => 'AccessRight', 'name' => 'RightsLevel', 'role' => 'dimension', 'why' => ['de' => 'Read/Write/Delete-Dim', 'en' => 'Read/write/delete dim']],
                ['entity' => 'Stamp', 'name' => 'StampId', 'role' => 'key', 'why' => ['de' => 'Stamp-Join', 'en' => 'Stamp join']],
                ['entity' => 'Stamp', 'name' => 'DWDOCID', 'role' => 'dimension', 'why' => ['de' => 'Document-Rückjoin', 'en' => 'Document back-join']],
                ['entity' => 'Stamp', 'name' => 'StampType', 'role' => 'dimension', 'why' => ['de' => 'Approval/Rejection-Dim', 'en' => 'Approval/rejection dim']],
                ['entity' => 'Stamp', 'name' => 'AppliedBy', 'role' => 'dimension', 'why' => ['de' => 'Anwender-Rückjoin', 'en' => 'Applier back-join']],
                ['entity' => 'Stamp', 'name' => 'AppliedDate', 'role' => 'measure', 'why' => ['de' => 'Anwendungsdatum', 'en' => 'Applied date']],
                ['entity' => 'User', 'name' => 'UserId', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'User', 'name' => 'UserName', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'User', 'name' => 'Email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'User', 'name' => 'OrganizationId', 'role' => 'dimension', 'why' => ['de' => 'Organisations-Dim', 'en' => 'Organization dim']],
            ],
            'skipTables' => [
                [
                    'name' => 'Document body / attachment file content',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Volle Dateikörper aller Revisionen — teuer, kein Analytics-Nutzen; Revisions-Metadata reicht.',
                        'en' => 'Full file bodies for all revisions — expensive, no analytics value; revision metadata suffices.',
                    ],
                ],
                [
                    'name' => 'Content indexing full-text (Volltext)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Volltext kann beliebige Dokumentinhalte inkl. PII enthalten — nicht default laden.',
                        'en' => 'Full text can contain arbitrary document content including PII — do not load by default.',
                    ],
                ],
                [
                    'name' => 'Preview / viewer cache renditions',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Abgeleitete Vorschau-Binaries — kein inkrementeller Analytics-Nutzen.',
                        'en' => 'Derived preview binaries — no incremental analytics value.',
                    ],
                ],
                [
                    'name' => 'Full activity log (bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Technisches Activity-Log — Volumen, wenig Mart-Nutzen; aggregierte Task-Counts bevorzugen.',
                        'en' => 'Technical activity log — volume, little mart value; prefer aggregated task counts.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Document body / attachment file content', 'reason' => ['de' => 'Kosten, kein Analytics-Nutzen', 'en' => 'Cost, no analytics value']],
                ['name' => 'Content indexing full-text (bulk)', 'reason' => ['de' => 'Kann beliebiges PII enthalten', 'en' => 'Can contain arbitrary PII']],
                ['name' => 'Preview / viewer cache renditions', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen', 'en' => 'Binaries without analytics value']],
                ['name' => 'Full activity log', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History', 'en' => 'Volume — meta/agg instead of full history']],
            ],
            'dimensions' => [
                [
                    'id' => 'content_type',
                    'label' => ['de' => 'Content Type', 'en' => 'Content type'],
                    'grain' => ['de' => 'document.ContentType', 'en' => 'document.ContentType'],
                    'notes' => ['de' => 'Content-Type auf kleine Taxonomie normalisieren.', 'en' => 'Normalize content type to a small taxonomy.'],
                ],
                [
                    'id' => 'file_cabinet',
                    'label' => ['de' => 'File Cabinet', 'en' => 'File cabinet'],
                    'grain' => ['de' => 'document.CabinetId', 'en' => 'document.CabinetId'],
                    'notes' => ['de' => 'Cabinets mit unterschiedlichen Index-/Retention-Schemas nicht mischen.', 'en' => 'Do not mix cabinets with different index/retention schemas in one report.'],
                ],
                [
                    'id' => 'task_status',
                    'label' => ['de' => 'Task Status', 'en' => 'Task status'],
                    'grain' => ['de' => 'workflow_task.TaskStatus', 'en' => 'workflow_task.TaskStatus'],
                    'notes' => ['de' => 'Offen vs. überfällig vs. abgeschlossen unterscheiden.', 'en' => 'Distinguish open vs overdue vs completed.'],
                ],
                [
                    'id' => 'rights_level',
                    'label' => ['de' => 'Rights Level', 'en' => 'Rights level'],
                    'grain' => ['de' => 'access_right.RightsLevel', 'en' => 'access_right.RightsLevel'],
                    'notes' => ['de' => 'Read/Write/Delete-Grants für Access-Reviews getrennt tracken.', 'en' => 'Track read/write/delete grants separately for access reviews.'],
                ],
                [
                    'id' => 'stamp_type',
                    'label' => ['de' => 'Stamp Type', 'en' => 'Stamp type'],
                    'grain' => ['de' => 'stamp.StampType', 'en' => 'stamp.StampType'],
                    'notes' => ['de' => 'Approval- vs. Rejection- vs. Review-Stempel treiben unterschiedliche Approval-KPIs.', 'en' => 'Approval vs rejection vs review stamps drive different approval KPIs.'],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['UserName', 'Email'],
                    'treatment' => [
                        'de' => 'Staff-Identitätsdaten — taggen; UserId als Join bevorzugen.',
                        'en' => 'Staff identity data — tag as PII; prefer UserId as join.',
                    ],
                ],
                [
                    'entity' => 'Document',
                    'fields' => ['OwnerId display name'],
                    'treatment' => [
                        'de' => 'Owner-Anzeige löst auf User-PII auf — nur UserId joinen.',
                        'en' => 'Owner display resolves to user PII — join by UserId only.',
                    ],
                ],
                [
                    'entity' => 'IndexField values (not schema)',
                    'fields' => ['customer/contact name index values'],
                    'treatment' => [
                        'de' => 'Index-Feld-Werte können je Cabinet Gegenpartei-Namen/PII enthalten — pro Cabinet vor dem Laden reviewen.',
                        'en' => 'Index field values can contain counterparty names/PII depending on the cabinet — review per cabinet before loading values.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => ['de' => 'DWDOCID, UserId, VersionNumber, CabinetId.', 'en' => 'DWDOCID, UserId, VersionNumber, CabinetId.'],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => ['de' => 'Document, File Cabinet, Index Field Schema, Access Right Grants — keine Dokumentkörper, kein Volltext.', 'en' => 'Document, file cabinet, index field schema, access right grants — no document bodies, no full text.'],
                ],
            ],
            'measures' => [
                [
                    'id' => 'documents-stored',
                    'example' => true,
                    'label' => ['de' => 'Documents Stored', 'en' => 'Documents stored'],
                    'question' => [
                        'de' => 'Wie viele Documents wurden in der Periode gespeichert?',
                        'en' => 'How many documents were stored in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM document WHERE StoreDate IN period',
                    'grain' => ['de' => 'Gespeichertes Document', 'en' => 'Stored document'],
                    'dimensions' => ['content_type', 'file_cabinet'],
                    'fieldsUsed' => ['Document.DWDOCID', 'Document.StoreDate', 'Document.CabinetId'],
                    'sourceHints' => [
                        'de' => 'StoreDate aus dem Document-Header; Test-/Sandbox-Cabinets ausschließen.',
                        'en' => 'StoreDate from the document header; exclude test/sandbox cabinets.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Cabinet für Rechnungen vs. Verträge vs. HR-Dokumente trennen.',
                        'en' => 'Separate by cabinet for invoices vs contracts vs HR documents.',
                    ],
                ],
                [
                    'id' => 'open-workflow-tasks',
                    'example' => true,
                    'label' => ['de' => 'Open Workflow Tasks', 'en' => 'Open workflow tasks'],
                    'question' => [
                        'de' => 'Wie viele Workflow Tasks sind aktuell offen?',
                        'en' => 'How many workflow tasks are currently open?',
                    ],
                    'formula' => "COUNT(*) FROM workflow_task WHERE TaskStatus = 'open'",
                    'grain' => ['de' => 'Offener Workflow Task (Snapshot)', 'en' => 'Open workflow task (snapshot)'],
                    'dimensions' => ['task_status', 'file_cabinet'],
                    'fieldsUsed' => ['WorkflowTask.TaskId', 'WorkflowTask.TaskStatus', 'WorkflowTask.DueDate'],
                    'sourceHints' => [
                        'de' => 'TaskStatus-Werte je Workflow-Definition prüfen.',
                        'en' => 'Check TaskStatus values per workflow definition.',
                    ],
                    'adapt' => [
                        'de' => 'Überfällige Tasks (DueDate < heute) separat als Backlog-KPI.',
                        'en' => 'Track overdue tasks (DueDate < today) separately as a backlog KPI.',
                    ],
                ],
                [
                    'id' => 'revisions-count',
                    'example' => false,
                    'label' => ['de' => 'Revisions Count', 'en' => 'Revisions count'],
                    'question' => [
                        'de' => 'Wie viele neue Revisionen wurden in der Periode erstellt?',
                        'en' => 'How many new revisions were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM revision WHERE RevisionDate IN period',
                    'grain' => ['de' => 'Revision', 'en' => 'Revision'],
                    'dimensions' => ['content_type'],
                    'fieldsUsed' => ['Revision.DWDOCID', 'Revision.VersionNumber', 'Revision.RevisionDate'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Tages-Aggregate je Cabinet nutzen.',
                        'en' => 'Use daily aggregates per cabinet at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Auto-generierte OCR-Revisionen aus Counts ausschließen.',
                        'en' => 'Exclude auto-generated OCR revisions from counts.',
                    ],
                ],
                [
                    'id' => 'storage-volume-by-cabinet',
                    'example' => false,
                    'label' => ['de' => 'Storage Volume by Cabinet', 'en' => 'Storage volume by cabinet'],
                    'question' => [
                        'de' => 'Wie viel Speichervolumen liegt je File Cabinet?',
                        'en' => 'How much storage volume sits in each file cabinet?',
                    ],
                    'formula' => 'SUM(FileSize) FROM revision JOIN document ON document.DWDOCID = revision.DWDOCID GROUP BY document.CabinetId',
                    'grain' => ['de' => 'Revision (letzte je Document)', 'en' => 'Revision (latest per document)'],
                    'dimensions' => ['file_cabinet'],
                    'fieldsUsed' => ['Revision.FileSize', 'Document.CabinetId'],
                    'sourceHints' => [
                        'de' => 'Nur letzte Revision je Document zählen.',
                        'en' => 'Count only the latest revision per document.',
                    ],
                    'adapt' => [
                        'de' => 'Große Scan-Cabinets separat ausweisen.',
                        'en' => 'Report large scan cabinets separately.',
                    ],
                ],
                [
                    'id' => 'approval-stamp-rate',
                    'example' => false,
                    'label' => ['de' => 'Approval Stamp Rate', 'en' => 'Approval stamp rate'],
                    'question' => [
                        'de' => 'Welcher Anteil gestempelter Documents ist "approved"?',
                        'en' => 'What share of stamped documents is "approved"?',
                    ],
                    'formula' => "COUNT(*) FILTER (WHERE StampType = 'approved') / COUNT(*) FROM stamp",
                    'grain' => ['de' => 'Stempel-Anwendung', 'en' => 'Stamp application'],
                    'dimensions' => ['stamp_type', 'file_cabinet'],
                    'fieldsUsed' => ['Stamp.StampId', 'Stamp.StampType', 'Stamp.DWDOCID'],
                    'sourceHints' => [
                        'de' => 'StampType-Werte je Workflow-Konfiguration prüfen.',
                        'en' => 'Check StampType values per workflow configuration.',
                    ],
                    'adapt' => [
                        'de' => 'Mehrfach-Stempel je Document (Re-Review) korrekt de-duplizieren.',
                        'en' => 'Correctly de-duplicate multiple stamps per document (re-review).',
                    ],
                ],
            ],
            'tools' => $dmsTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'box',
            'domain' => 'dms',
            'order' => 330,
            'label' => ['de' => 'Box', 'en' => 'Box'],
            'shortPurpose' => [
                'de' => 'Content-Plattform: File/Version/Folder, Metadata und Collaboration — Metadata-Load, PII und DMS-Measures; keine Binärkörper oder Volltext.',
                'en' => 'Content platform: file/version/folder, metadata and collaboration — metadata load, PII and DMS measures; no binary bodies or full text.',
            ],
            'entities' => [
                [
                    'id' => 'file',
                    'label' => ['de' => 'File', 'en' => 'File'],
                    'description' => [
                        'de' => 'Box File-Objekt — Name/Size/ParentFolder; Dateiinhalt nicht laden.',
                        'en' => 'Box file object — name/size/parent folder; file content not loaded.',
                    ],
                    'grain' => ['de' => 'Ein File (id)', 'en' => 'One file (id)'],
                    'role' => ['de' => 'Dimension / Fact-Anker', 'en' => 'Dimension / fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'file_version',
                    'label' => ['de' => 'File Version', 'en' => 'File version'],
                    'description' => [
                        'de' => 'Versionshistorie — sha1/size/created_at je File.',
                        'en' => 'Version history — sha1/size/created_at per file.',
                    ],
                    'grain' => ['de' => 'Eine Version (id)', 'en' => 'One version (id)'],
                    'role' => ['de' => 'Ledger-Fact (high volume)', 'en' => 'Ledger fact (high volume)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'folder',
                    'label' => ['de' => 'Folder', 'en' => 'Folder'],
                    'description' => [
                        'de' => 'Box Folder-Objekt — hierarchisches Name/ParentId.',
                        'en' => 'Box folder object — hierarchical name/ParentId.',
                    ],
                    'grain' => ['de' => 'Ein Folder (id)', 'en' => 'One folder (id)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'metadata_instance',
                    'label' => ['de' => 'Metadata Instance', 'en' => 'Metadata instance'],
                    'description' => [
                        'de' => 'Metadata-Template-Instanz an einem File (schema-gebundene Key/Value-Paare).',
                        'en' => 'Metadata template instance attached to a file (schema-bound key/value pairs).',
                    ],
                    'grain' => ['de' => 'Eine Metadata Instance (id)', 'en' => 'One metadata instance (id)'],
                    'role' => ['de' => 'Metadata-Fact', 'en' => 'Metadata fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'collaboration',
                    'label' => ['de' => 'Collaboration', 'en' => 'Collaboration'],
                    'description' => [
                        'de' => 'Collaboration Grant (ACL) — Role je File/Folder und User oder Gruppe.',
                        'en' => 'Collaboration grant (ACL) — role per file/folder and user or group.',
                    ],
                    'grain' => ['de' => 'Eine Collaboration (id)', 'en' => 'One collaboration (id)'],
                    'role' => ['de' => 'Control-Fact', 'en' => 'Control fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'task',
                    'label' => ['de' => 'Task', 'en' => 'Task'],
                    'description' => [
                        'de' => 'Box Task (Review-/Approval-Workflow) auf einem File — Status/DueAt/Assignees.',
                        'en' => 'Box task (review/approval workflow) on a file — status/due-at/assignees.',
                    ],
                    'grain' => ['de' => 'Ein Task (id)', 'en' => 'One task (id)'],
                    'role' => ['de' => 'Process-Fact', 'en' => 'Process fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'Box Enterprise User — Name/Login/JobTitle; Kern-Stammdaten mit PII.',
                        'en' => 'Box enterprise user — name/login/job title; core master data with PII.',
                    ],
                    'grain' => ['de' => 'Ein User (id)', 'en' => 'One user (id)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'legal_hold_assignment',
                    'label' => ['de' => 'Legal Hold Assignment', 'en' => 'Legal hold assignment'],
                    'description' => [
                        'de' => 'Legal-Hold-Policy-Zuweisung auf ein File oder Folder — nur Status, kein gehaltener Content.',
                        'en' => 'Legal hold policy assignment on a file or folder — status only, no held content.',
                    ],
                    'grain' => ['de' => 'Eine Zuweisung (id)', 'en' => 'One assignment (id)'],
                    'role' => ['de' => 'Compliance-Fact', 'en' => 'Compliance fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'File', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'File-Join', 'en' => 'File join']],
                ['entity' => 'File', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Dateiname', 'en' => 'File name']],
                ['entity' => 'File', 'name' => 'size', 'role' => 'measure', 'why' => ['de' => 'Speichervolumen', 'en' => 'Storage volume']],
                ['entity' => 'File', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Anlagedatum', 'en' => 'Creation date']],
                ['entity' => 'File', 'name' => 'modified_at', 'role' => 'measure', 'why' => ['de' => 'Letzte Änderung', 'en' => 'Last modified']],
                ['entity' => 'File', 'name' => 'owned_by', 'role' => 'dimension', 'why' => ['de' => 'Owner-Rückjoin (User)', 'en' => 'Owner back-join (user)']],
                ['entity' => 'File', 'name' => 'parent_folder_id', 'role' => 'dimension', 'why' => ['de' => 'Folder-Rückjoin', 'en' => 'Folder back-join']],
                ['entity' => 'FileVersion', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Versions-Join', 'en' => 'Version join']],
                ['entity' => 'FileVersion', 'name' => 'file_id', 'role' => 'dimension', 'why' => ['de' => 'File-Rückjoin', 'en' => 'File back-join']],
                ['entity' => 'FileVersion', 'name' => 'sha1', 'role' => 'dimension', 'why' => ['de' => 'Content-Hash-Dim', 'en' => 'Content hash dim']],
                ['entity' => 'FileVersion', 'name' => 'size', 'role' => 'measure', 'why' => ['de' => 'Speichervolumen je Version', 'en' => 'Storage volume per version']],
                ['entity' => 'FileVersion', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Versionsdatum', 'en' => 'Version date']],
                ['entity' => 'FileVersion', 'name' => 'modified_by', 'role' => 'dimension', 'why' => ['de' => 'Bearbeiter-Rückjoin', 'en' => 'Editor back-join']],
                ['entity' => 'Folder', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Folder-Join', 'en' => 'Folder join']],
                ['entity' => 'Folder', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Ordnername', 'en' => 'Folder name']],
                ['entity' => 'Folder', 'name' => 'parent_id', 'role' => 'dimension', 'why' => ['de' => 'Hierarchie', 'en' => 'Hierarchy']],
                ['entity' => 'MetadataInstance', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Metadata-Join', 'en' => 'Metadata join']],
                ['entity' => 'MetadataInstance', 'name' => 'file_id', 'role' => 'dimension', 'why' => ['de' => 'File-Rückjoin', 'en' => 'File back-join']],
                ['entity' => 'MetadataInstance', 'name' => 'template_key', 'role' => 'dimension', 'why' => ['de' => 'Template-Dim', 'en' => 'Template dim']],
                ['entity' => 'MetadataInstance', 'name' => 'scope', 'role' => 'dimension', 'why' => ['de' => 'Enterprise/Global-Scope', 'en' => 'Enterprise/global scope']],
                ['entity' => 'Collaboration', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Collaboration-Join', 'en' => 'Collaboration join']],
                ['entity' => 'Collaboration', 'name' => 'item_id', 'role' => 'dimension', 'why' => ['de' => 'File-/Folder-Rückjoin', 'en' => 'File/folder back-join']],
                ['entity' => 'Collaboration', 'name' => 'accessible_by_user_id', 'role' => 'dimension', 'why' => ['de' => 'User-/Gruppen-Rückjoin', 'en' => 'User/group back-join']],
                ['entity' => 'Collaboration', 'name' => 'role', 'role' => 'dimension', 'why' => ['de' => 'Viewer/Editor/Co-Owner-Dim', 'en' => 'Viewer/editor/co-owner dim']],
                ['entity' => 'Task', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Task-Join', 'en' => 'Task join']],
                ['entity' => 'Task', 'name' => 'item_id', 'role' => 'dimension', 'why' => ['de' => 'File-Rückjoin', 'en' => 'File back-join']],
                ['entity' => 'Task', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Task-Status', 'en' => 'Task status']],
                ['entity' => 'Task', 'name' => 'due_at', 'role' => 'measure', 'why' => ['de' => 'Fälligkeitsdatum', 'en' => 'Due date']],
                ['entity' => 'User', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'User', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'User', 'name' => 'login', 'role' => 'pii', 'why' => ['de' => 'Login (E-Mail) / PII', 'en' => 'Login (email) / PII']],
                ['entity' => 'User', 'name' => 'job_title', 'role' => 'dimension', 'why' => ['de' => 'Rollen-Dim', 'en' => 'Role dim']],
                ['entity' => 'LegalHoldAssignment', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Assignment-Join', 'en' => 'Assignment join']],
                ['entity' => 'LegalHoldAssignment', 'name' => 'item_id', 'role' => 'dimension', 'why' => ['de' => 'File-/Folder-Rückjoin', 'en' => 'File/folder back-join']],
                ['entity' => 'LegalHoldAssignment', 'name' => 'policy_id', 'role' => 'dimension', 'why' => ['de' => 'Policy-Rückjoin', 'en' => 'Policy back-join']],
                ['entity' => 'LegalHoldAssignment', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Active/Released-Dim', 'en' => 'Active/released dim']],
            ],
            'skipTables' => [
                [
                    'name' => 'File content / binary body (all versions)',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Volle Dateikörper — teuer, kein Analytics-Nutzen; Versions-Metadata (Size/sha1/Owner) reicht.',
                        'en' => 'Full file bodies — expensive, no analytics value; version metadata (size/sha1/owner) suffices.',
                    ],
                ],
                [
                    'name' => 'Full-text preview / content search index',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Volltext kann personenbezogene/Vertragsdaten enthalten — nicht default laden.',
                        'en' => 'Full text can contain personal/contract data — do not load by default.',
                    ],
                ],
                [
                    'name' => 'Preview renditions (thumbnails, watermarked preview)',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Abgeleitete Vorschau-Binaries — kein inkrementeller Analytics-Nutzen.',
                        'en' => 'Derived preview binaries — no incremental analytics value.',
                    ],
                ],
                [
                    'name' => 'Full event stream (Box Events API bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Technischer Event-Stream — Volumen, wenig Mart-Nutzen; aggregierte Activity-Counts bevorzugen.',
                        'en' => 'Technical event stream — volume, little mart value; prefer aggregated activity counts.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'File content / binary body (all versions)', 'reason' => ['de' => 'Kosten, kein Analytics-Nutzen', 'en' => 'Cost, no analytics value']],
                ['name' => 'Full-text preview / content search index', 'reason' => ['de' => 'Kann personenbezogene Daten enthalten', 'en' => 'Can contain personal data']],
                ['name' => 'Preview renditions (thumbnails/watermark)', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen', 'en' => 'Binaries without analytics value']],
                ['name' => 'Full event stream (Events API bulk)', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History', 'en' => 'Volume — meta/agg instead of full history']],
            ],
            'dimensions' => [
                [
                    'id' => 'metadata_template',
                    'label' => ['de' => 'Metadata Template', 'en' => 'Metadata template'],
                    'grain' => ['de' => 'metadata_instance.template_key', 'en' => 'metadata_instance.template_key'],
                    'notes' => ['de' => 'Templates unterscheiden sich je Scope (Enterprise vs. Global) — Schemas nicht mischen.', 'en' => 'Templates differ per scope (enterprise vs global) — do not mix schemas in one report.'],
                ],
                [
                    'id' => 'collaboration_role',
                    'label' => ['de' => 'Collaboration Role', 'en' => 'Collaboration role'],
                    'grain' => ['de' => 'collaboration.role', 'en' => 'collaboration.role'],
                    'notes' => ['de' => 'Viewer/Editor/Co-Owner-Grants für Access-Reviews getrennt tracken.', 'en' => 'Track viewer/editor/co-owner grants separately for access reviews.'],
                ],
                [
                    'id' => 'task_status',
                    'label' => ['de' => 'Task Status', 'en' => 'Task status'],
                    'grain' => ['de' => 'task.status', 'en' => 'task.status'],
                    'notes' => ['de' => 'Pending vs. abgeschlossen vs. überfällig unterscheiden.', 'en' => 'Distinguish pending vs completed vs overdue.'],
                ],
                [
                    'id' => 'legal_hold_status',
                    'label' => ['de' => 'Legal Hold Status', 'en' => 'Legal hold status'],
                    'grain' => ['de' => 'legal_hold_assignment.status', 'en' => 'legal_hold_assignment.status'],
                    'notes' => ['de' => 'Active vs. Released Holds treiben unterschiedliche Compliance-KPIs.', 'en' => 'Active vs released holds drive different compliance KPIs.'],
                ],
                [
                    'id' => 'job_title',
                    'label' => ['de' => 'Job Title', 'en' => 'Job title'],
                    'grain' => ['de' => 'user.job_title', 'en' => 'user.job_title'],
                    'notes' => ['de' => 'Freitext-Titel auf eine kleine Rollen-Taxonomie normalisieren.', 'en' => 'Normalize free-text titles into a small role taxonomy for reporting.'],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['name', 'login'],
                    'treatment' => [
                        'de' => 'Staff-Identitätsdaten (Login = E-Mail) — taggen; id als Join bevorzugen.',
                        'en' => 'Staff identity data (login = email) — tag as PII; prefer id as join.',
                    ],
                ],
                [
                    'entity' => 'File',
                    'fields' => ['owned_by display name'],
                    'treatment' => [
                        'de' => 'Owner-Anzeige löst auf User-PII auf — nur id joinen.',
                        'en' => 'Owner display resolves to user PII — join by id only.',
                    ],
                ],
                [
                    'entity' => 'Metadata instance values',
                    'fields' => ['customer/contact name custom fields'],
                    'treatment' => [
                        'de' => 'Custom-Metadata-Werte können je Template Gegenpartei-PII enthalten — pro Template vor dem Laden reviewen.',
                        'en' => 'Custom metadata field values can contain counterparty PII depending on the template — review per template before loading values.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => ['de' => 'file.id, user.id, file_version.id, folder.id.', 'en' => 'file.id, user.id, file_version.id, folder.id.'],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => ['de' => 'File, Folder, Collaboration Grants, Legal Hold Assignment Status — kein File-Content, kein Volltext.', 'en' => 'File, folder, collaboration grants, legal hold assignment status — no file content, no full text.'],
                ],
            ],
            'measures' => [
                [
                    'id' => 'files-uploaded',
                    'example' => true,
                    'label' => ['de' => 'Files Uploaded', 'en' => 'Files uploaded'],
                    'question' => [
                        'de' => 'Wie viele Files wurden in der Periode hochgeladen?',
                        'en' => 'How many files were uploaded in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM file WHERE created_at IN period',
                    'grain' => ['de' => 'Hochgeladenes File', 'en' => 'Uploaded file'],
                    'dimensions' => ['metadata_template', 'job_title'],
                    'fieldsUsed' => ['File.id', 'File.created_at', 'File.parent_folder_id'],
                    'sourceHints' => [
                        'de' => 'created_at aus dem File-Objekt; Trash-/Temp-Folder ausschließen.',
                        'en' => 'created_at from the file object; exclude trash/temp folders.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Metadata Template für Verträge vs. sonstige Files trennen.',
                        'en' => 'Separate by metadata template for contracts vs other files.',
                    ],
                ],
                [
                    'id' => 'active-legal-holds',
                    'example' => true,
                    'label' => ['de' => 'Active Legal Holds', 'en' => 'Active legal holds'],
                    'question' => [
                        'de' => 'Wie viele Files stehen aktuell unter Legal Hold?',
                        'en' => 'How many files are currently under legal hold?',
                    ],
                    'formula' => "COUNT(*) FROM legal_hold_assignment WHERE status = 'active'",
                    'grain' => ['de' => 'Aktiver Legal Hold (Snapshot)', 'en' => 'Active legal hold (snapshot)'],
                    'dimensions' => ['legal_hold_status'],
                    'fieldsUsed' => ['LegalHoldAssignment.item_id', 'LegalHoldAssignment.status', 'LegalHoldAssignment.policy_id'],
                    'sourceHints' => [
                        'de' => 'status-Werte je Policy-Konfiguration prüfen.',
                        'en' => 'Check status values per policy configuration.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Policy für verschiedene Litigation-Fälle trennen.',
                        'en' => 'Separate by policy for different litigation cases.',
                    ],
                ],
                [
                    'id' => 'versions-count',
                    'example' => false,
                    'label' => ['de' => 'Versions Count', 'en' => 'Versions count'],
                    'question' => [
                        'de' => 'Wie viele neue File Versions wurden in der Periode erstellt?',
                        'en' => 'How many new file versions were created in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM file_version WHERE created_at IN period',
                    'grain' => ['de' => 'File Version', 'en' => 'File version'],
                    'dimensions' => ['metadata_template'],
                    'fieldsUsed' => ['FileVersion.file_id', 'FileVersion.id', 'FileVersion.created_at'],
                    'sourceHints' => [
                        'de' => 'Bei High Volume Tages-Aggregate je Folder nutzen.',
                        'en' => 'Use daily aggregates per folder at high volume.',
                    ],
                    'adapt' => [
                        'de' => 'Auto-Sync-Versionen (Desktop-Client) separat kennzeichnen.',
                        'en' => 'Separately flag auto-sync versions (desktop client).',
                    ],
                ],
                [
                    'id' => 'storage-volume-by-template',
                    'example' => false,
                    'label' => ['de' => 'Storage Volume by Template', 'en' => 'Storage volume by template'],
                    'question' => [
                        'de' => 'Wie viel Speichervolumen liegt je Metadata Template?',
                        'en' => 'How much storage volume sits in each metadata template?',
                    ],
                    'formula' => 'SUM(size) FROM file_version JOIN metadata_instance ON metadata_instance.file_id = file_version.file_id GROUP BY metadata_instance.template_key',
                    'grain' => ['de' => 'File Version (letzte je File)', 'en' => 'File version (latest per file)'],
                    'dimensions' => ['metadata_template'],
                    'fieldsUsed' => ['FileVersion.size', 'MetadataInstance.template_key'],
                    'sourceHints' => [
                        'de' => 'Nur letzte Version je File zählen.',
                        'en' => 'Count only the latest version per file.',
                    ],
                    'adapt' => [
                        'de' => 'Große Media-Templates (Video/Design) separat ausweisen.',
                        'en' => 'Report large media templates (video/design) separately.',
                    ],
                ],
                [
                    'id' => 'collaboration-grants-per-file',
                    'example' => false,
                    'label' => ['de' => 'Collaboration Grants per File', 'en' => 'Collaboration grants per file'],
                    'question' => [
                        'de' => 'Wie viele aktive Collaboration Grants hat ein File im Schnitt?',
                        'en' => 'How many active collaboration grants does the average file have?',
                    ],
                    'formula' => 'AVG(grant_count) FROM collaboration GROUP BY item_id',
                    'grain' => ['de' => 'File (Collaboration-Snapshot)', 'en' => 'File (collaboration snapshot)'],
                    'dimensions' => ['collaboration_role', 'job_title'],
                    'fieldsUsed' => ['Collaboration.item_id', 'Collaboration.accessible_by_user_id', 'Collaboration.role'],
                    'sourceHints' => [
                        'de' => 'Gruppen- vs. Einzel-Collaborations unterscheiden — Gruppen-Expansion verzerrt Counts.',
                        'en' => 'Distinguish group vs individual collaborations — group expansion distorts counts.',
                    ],
                    'adapt' => [
                        'de' => '"Company"/externe Co-Owner-Grants separat als Risiko-Flag auswerten.',
                        'en' => 'Evaluate "company"/external co-owner grants separately as a risk flag.',
                    ],
                ],
            ],
            'tools' => $dmsTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
