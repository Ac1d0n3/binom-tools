<?php

/**
 * Wave 8 governance overlays — DMS / Content source-native PII, DSDR, skip guidance.
 * Merged onto catalog products by id (opentext, fabasoft, elo, docuware, box).
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'opentext' => [
        'pii' => [
            [
                'entity' => 'User (KUAF)',
                'fields' => ['Name', 'Login', 'EMail', 'DeptID'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'KUAF Stammdaten — Name/Login/E-Mail nur mit Legal-Basis; UserID als Key behalten.', 'en' => 'KUAF master data — name/login/email only with legal basis; keep UserID as key.'],
            ],
            [
                'entity' => 'Document owner reference',
                'fields' => ['OwnerID display name'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: id only; Curated: id only; Mart: aggregates only', 'en' => 'RAW: id only; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Owner-Anzeige löst auf User-PII auf — nur UserID joinen, nicht den Namen denormalisieren.', 'en' => 'Owner display resolves to user PII — join only by UserID, do not denormalize the name.'],
            ],
            [
                'entity' => 'OCR full-text extraction',
                'fields' => ['extracted document text', 'content search index body'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Volltext kann beliebige Dokumentinhalte inkl. Drittparteien-PII enthalten — nie ins Warehouse.', 'en' => 'Full text can contain arbitrary document content including third-party PII — never land it in the warehouse.'],
            ],
            [
                'entity' => 'Rendition / preview thumbnails',
                'fields' => ['preview image body', 'viewer cache'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Vorschau-Binaries sind abgeleiteter Content — kein Warehouse-Load, unabhängig vom Klassifikationsgrad.', 'en' => 'Preview binaries are derived content — no warehouse load regardless of classification.'],
            ],
            [
                'entity' => 'Permission grants (Everyone / Public)',
                'fields' => ['PermType = Public', 'group expansion'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: aggregates only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: aggregates only; Mart: aggregates only'],
                'treatment' => ['de' => 'Public-Grants können versehentlich freigegebene PII-Dokumente offenlegen — als Access-Risk-Flag auswerten.', 'en' => 'Public grants can inadvertently expose PII documents — evaluate as an access-risk flag.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'DataID, UserID, VersionNum, CategoryID, RightID.', 'en' => 'DataID, UserID, VersionNum, CategoryID, RightID.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Document, Folder, Category, Permission Grants, User — keine Content-Bodies, kein Volltext.', 'en' => 'Document, folder, category, permission grants, user — no content bodies, no full text.'],
            ],
            [
                'focus' => ['de' => 'Batch / archive export copies', 'en' => 'Batch / archive export copies'],
                'notes' => ['de' => 'RM-Archive-Exports und Backup-Kopien verdoppeln Document-Metadata und Owner-PII.', 'en' => 'RM archive exports and backup copies duplicate document metadata and owner PII.'],
            ],
            [
                'focus' => ['de' => 'Test / staging environment copies', 'en' => 'Test / staging environment copies'],
                'notes' => ['de' => 'Staging-Content-Server-Instanzen nicht mit Prod-DMS-Marts mischen.', 'en' => 'Do not mix staging Content Server instances with prod DMS marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Document body / binary content (all versions)',
                'category' => 'blob',
                'reason' => ['de' => 'Volle Dateikörper — nie ins Warehouse; Version-Metadata reicht.', 'en' => 'Full file bodies — never into the warehouse; version metadata suffices.'],
            ],
            [
                'name' => 'OCR full-text / content search index',
                'category' => 'pii',
                'reason' => ['de' => 'Volltext-PII — Metadata reicht für Storage-/Retention-KPIs.', 'en' => 'Full-text PII — metadata is enough for storage/retention KPIs.'],
            ],
            [
                'name' => 'Rendition / preview thumbnails',
                'category' => 'security',
                'reason' => ['de' => 'Abgeleitete Binaries — nie ins Warehouse.', 'en' => 'Derived binaries — never into the warehouse.'],
            ],
            [
                'name' => 'Full audit trail (DAuditNew) bulk',
                'category' => 'system',
                'reason' => ['de' => 'Technisches Audit — Volumen, wenig Mart-Nutzen; Meta/Agg bevorzugen.', 'en' => 'Technical audit — volume, little mart value; prefer meta/agg.'],
            ],
        ],
        'skip' => [
            ['name' => 'Document body / binary content (all versions)', 'reason' => ['de' => 'Nie ins Warehouse — Kosten-/Security-Risiko.', 'en' => 'Never into the warehouse — cost/security risk.']],
            ['name' => 'OCR full-text extraction (bulk)', 'reason' => ['de' => 'Kann Drittparteien-PII enthalten.', 'en' => 'Can contain third-party PII.']],
            ['name' => 'Rendition / preview thumbnails', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen.', 'en' => 'Binaries without analytics value.']],
            ['name' => 'User Name/Login/EMail cleartext in default marts', 'reason' => ['de' => 'UserID reicht für DMS-KPIs.', 'en' => 'UserID is enough for DMS KPIs.']],
            ['name' => 'Full audit trail / event log', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History.', 'en' => 'Volume — meta/agg instead of full history.']],
        ],
    ],

    'fabasoft' => [
        'pii' => [
            [
                'entity' => 'Person',
                'fields' => ['FullName', 'EMailAddress', 'OrgUnitID'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Person-Stammdaten — Name/E-Mail nur mit Legal-Basis; PersonID als Key behalten.', 'en' => 'Person master data — name/email only with legal basis; keep PersonID as key.'],
            ],
            [
                'entity' => 'Object owner reference',
                'fields' => ['ObjOwner display name'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: id only; Curated: id only; Mart: aggregates only', 'en' => 'RAW: id only; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Owner-Anzeige löst auf Person-PII auf — nur PersonID joinen.', 'en' => 'Owner display resolves to person PII — join only by PersonID.'],
            ],
            [
                'entity' => 'Full-text index (Volltextindex)',
                'fields' => ['extracted content body'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Volltext kann beliebige Objektinhalte inkl. Drittparteien-PII enthalten — nie ins Warehouse.', 'en' => 'Full text can contain arbitrary object content including third-party PII — never land it in the warehouse.'],
            ],
            [
                'entity' => 'Preview renditions',
                'fields' => ['viewer cache thumbnails'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Vorschau-Binaries sind abgeleiteter Content — kein Warehouse-Load.', 'en' => 'Preview binaries are derived content — no warehouse load.'],
            ],
            [
                'entity' => 'Permission grants (broad org unit)',
                'fields' => ['PermissionLevel = Owner at org-unit scope'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: aggregates only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: aggregates only; Mart: aggregates only'],
                'treatment' => ['de' => 'Breite Org-Unit-Grants können versehentlich PII-Objekte offenlegen — als Access-Risk-Flag auswerten.', 'en' => 'Broad org-unit grants can inadvertently expose PII objects — evaluate as an access-risk flag.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'ObjID, PersonID, VersionNumber, CategoryID, ActivityID.', 'en' => 'ObjID, PersonID, VersionNumber, CategoryID, ActivityID.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Object, Room, Filing Plan Category, Permission Grants, Person — keine Content-Bodies, kein Volltext.', 'en' => 'Object, room, filing plan category, permission grants, person — no content bodies, no full text.'],
            ],
            [
                'focus' => ['de' => 'Batch / export copies', 'en' => 'Batch / export copies'],
                'notes' => ['de' => 'Aktenplan-Exports und Backup-Kopien verdoppeln Object-Metadata und Owner-PII.', 'en' => 'Filing-plan exports and backup copies duplicate object metadata and owner PII.'],
            ],
            [
                'focus' => ['de' => 'Test / sandbox environment copies', 'en' => 'Test / sandbox environment copies'],
                'notes' => ['de' => 'Sandbox-Fabasoft-Instanzen nicht mit Prod-DMS-Marts mischen.', 'en' => 'Do not mix sandbox Fabasoft instances with prod DMS marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Content body / attachment binaries (all versions)',
                'category' => 'blob',
                'reason' => ['de' => 'Volle Inhaltskörper — nie ins Warehouse; Versions-Metadata reicht.', 'en' => 'Full content bodies — never into the warehouse; version metadata suffices.'],
            ],
            [
                'name' => 'Full-text index (Volltextindex)',
                'category' => 'pii',
                'reason' => ['de' => 'Volltext-PII — Metadata reicht für Storage-/Retention-KPIs.', 'en' => 'Full-text PII — metadata is enough for storage/retention KPIs.'],
            ],
            [
                'name' => 'Preview renditions (Viewer cache)',
                'category' => 'security',
                'reason' => ['de' => 'Abgeleitete Binaries — nie ins Warehouse.', 'en' => 'Derived binaries — never into the warehouse.'],
            ],
            [
                'name' => 'Full history / journal log (Protokoll) bulk',
                'category' => 'system',
                'reason' => ['de' => 'Technisches Protokoll — Volumen, wenig Mart-Nutzen; Meta/Agg bevorzugen.', 'en' => 'Technical journal — volume, little mart value; prefer meta/agg.'],
            ],
        ],
        'skip' => [
            ['name' => 'Content body / attachment binaries (all versions)', 'reason' => ['de' => 'Nie ins Warehouse — Kosten-/Security-Risiko.', 'en' => 'Never into the warehouse — cost/security risk.']],
            ['name' => 'Full-text index (bulk)', 'reason' => ['de' => 'Kann Drittparteien-PII enthalten.', 'en' => 'Can contain third-party PII.']],
            ['name' => 'Preview renditions (Viewer cache)', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen.', 'en' => 'Binaries without analytics value.']],
            ['name' => 'Person FullName/EMailAddress cleartext in default marts', 'reason' => ['de' => 'PersonID reicht für DMS-KPIs.', 'en' => 'PersonID is enough for DMS KPIs.']],
            ['name' => 'Full history / journal log', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History.', 'en' => 'Volume — meta/agg instead of full history.']],
        ],
    ],

    'elo' => [
        'pii' => [
            [
                'entity' => 'ELO User',
                'fields' => ['UserName', 'FullName', 'Email', 'OrgUnit'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'IDX User Stammdaten — Name/Login/E-Mail nur mit Legal-Basis; UserId als Key behalten.', 'en' => 'IDX user master data — name/login/email only with legal basis; keep UserId as key.'],
            ],
            [
                'entity' => 'Sord owner reference',
                'fields' => ['OwnerId display name'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: id only; Curated: id only; Mart: aggregates only', 'en' => 'RAW: id only; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Owner-Anzeige löst auf User-PII auf — nur UserId joinen.', 'en' => 'Owner display resolves to user PII — join only by UserId.'],
            ],
            [
                'entity' => 'Full-text index (Volltextsuche)',
                'fields' => ['extracted document text'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Volltext kann beliebige Dokumentinhalte inkl. Drittparteien-PII enthalten — nie ins Warehouse.', 'en' => 'Full text can contain arbitrary document content including third-party PII — never land it in the warehouse.'],
            ],
            [
                'entity' => 'Preview renditions (TIFF/Viewer cache)',
                'fields' => ['preview image body'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Vorschau-Binaries sind abgeleiteter Content — kein Warehouse-Load.', 'en' => 'Preview binaries are derived content — no warehouse load.'],
            ],
            [
                'entity' => 'ObjKeys metadata (person-related masks)',
                'fields' => ['KeyValue for Kunde/Ansprechpartner masks'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: aggregates only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: aggregates only; Mart: aggregates only'],
                'treatment' => ['de' => 'Index-Feld-Werte in kundenbezogenen Masken können Drittparteien-PII enthalten — pro Mask reviewen.', 'en' => 'Index field values in customer-related masks can contain third-party PII — review per mask.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'ObjId, UserId, VersionNo, MaskId.', 'en' => 'ObjId, UserId, VersionNo, MaskId.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Sord Document, Sord Folder, Mask, ACL Right, ELO User — keine Dokumentkörper, kein Volltext.', 'en' => 'Sord document, Sord folder, mask, ACL right, ELO user — no document bodies, no full text.'],
            ],
            [
                'focus' => ['de' => 'Batch / export copies', 'en' => 'Batch / export copies'],
                'notes' => ['de' => 'Archivlauf-Exports und Backup-Kopien verdoppeln Sord-Metadata und Owner-PII.', 'en' => 'Archive-run exports and backup copies duplicate Sord metadata and owner PII.'],
            ],
            [
                'focus' => ['de' => 'Test / staging environment copies', 'en' => 'Test / staging environment copies'],
                'notes' => ['de' => 'Staging-ELO-Instanzen nicht mit Prod-DMS-Marts mischen.', 'en' => 'Do not mix staging ELO instances with prod DMS marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Document body / archived file content',
                'category' => 'blob',
                'reason' => ['de' => 'Volle Dateikörper — nie ins Warehouse; Versions-Metadata reicht.', 'en' => 'Full file bodies — never into the warehouse; version metadata suffices.'],
            ],
            [
                'name' => 'Full-text index (Volltextsuche)',
                'category' => 'pii',
                'reason' => ['de' => 'Volltext-PII — Metadata reicht für Storage-/Retention-KPIs.', 'en' => 'Full-text PII — metadata is enough for storage/retention KPIs.'],
            ],
            [
                'name' => 'Preview renditions (TIFF/Viewer cache)',
                'category' => 'security',
                'reason' => ['de' => 'Abgeleitete Binaries — nie ins Warehouse.', 'en' => 'Derived binaries — never into the warehouse.'],
            ],
            [
                'name' => 'Full journal / change log (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Technisches Journal — Volumen, wenig Mart-Nutzen; Meta/Agg bevorzugen.', 'en' => 'Technical journal — volume, little mart value; prefer meta/agg.'],
            ],
        ],
        'skip' => [
            ['name' => 'Document body / archived file content', 'reason' => ['de' => 'Nie ins Warehouse — Kosten-/Security-Risiko.', 'en' => 'Never into the warehouse — cost/security risk.']],
            ['name' => 'Full-text index (bulk)', 'reason' => ['de' => 'Kann Drittparteien-PII enthalten.', 'en' => 'Can contain third-party PII.']],
            ['name' => 'Preview renditions (TIFF/Viewer cache)', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen.', 'en' => 'Binaries without analytics value.']],
            ['name' => 'ELO User Name/FullName/Email cleartext in default marts', 'reason' => ['de' => 'UserId reicht für DMS-KPIs.', 'en' => 'UserId is enough for DMS KPIs.']],
            ['name' => 'Full journal / change log', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History.', 'en' => 'Volume — meta/agg instead of full history.']],
        ],
    ],

    'docuware' => [
        'pii' => [
            [
                'entity' => 'User',
                'fields' => ['UserName', 'Email'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Organization-User-Stammdaten — Name/E-Mail nur mit Legal-Basis; UserId als Key behalten.', 'en' => 'Organization user master data — name/email only with legal basis; keep UserId as key.'],
            ],
            [
                'entity' => 'Document owner reference',
                'fields' => ['OwnerId display name'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: id only; Curated: id only; Mart: aggregates only', 'en' => 'RAW: id only; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Owner-Anzeige löst auf User-PII auf — nur UserId joinen.', 'en' => 'Owner display resolves to user PII — join only by UserId.'],
            ],
            [
                'entity' => 'Content indexing full-text (Volltext)',
                'fields' => ['extracted document text'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Volltext kann beliebige Dokumentinhalte inkl. Drittparteien-PII enthalten — nie ins Warehouse.', 'en' => 'Full text can contain arbitrary document content including third-party PII — never land it in the warehouse.'],
            ],
            [
                'entity' => 'Preview / viewer cache renditions',
                'fields' => ['preview image body'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Vorschau-Binaries sind abgeleiteter Content — kein Warehouse-Load.', 'en' => 'Preview binaries are derived content — no warehouse load.'],
            ],
            [
                'entity' => 'Index field values (customer-related cabinets)',
                'fields' => ['customer/contact name index values'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: aggregates only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: aggregates only; Mart: aggregates only'],
                'treatment' => ['de' => 'Index-Feld-Werte in kundenbezogenen Cabinets können Drittparteien-PII enthalten — pro Cabinet reviewen.', 'en' => 'Index field values in customer-related cabinets can contain third-party PII — review per cabinet.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'DWDOCID, UserId, VersionNumber, CabinetId.', 'en' => 'DWDOCID, UserId, VersionNumber, CabinetId.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Document, File Cabinet, Index Field Schema, Access Right, User — keine Dokumentkörper, kein Volltext.', 'en' => 'Document, file cabinet, index field schema, access right, user — no document bodies, no full text.'],
            ],
            [
                'focus' => ['de' => 'Batch / export copies', 'en' => 'Batch / export copies'],
                'notes' => ['de' => 'Cabinet-Exports und Backup-Kopien verdoppeln Document-Metadata und Owner-PII.', 'en' => 'Cabinet exports and backup copies duplicate document metadata and owner PII.'],
            ],
            [
                'focus' => ['de' => 'Test / staging environment copies', 'en' => 'Test / staging environment copies'],
                'notes' => ['de' => 'Staging-DocuWare-Instanzen nicht mit Prod-DMS-Marts mischen.', 'en' => 'Do not mix staging DocuWare instances with prod DMS marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Document body / attachment file content',
                'category' => 'blob',
                'reason' => ['de' => 'Volle Dateikörper — nie ins Warehouse; Revisions-Metadata reicht.', 'en' => 'Full file bodies — never into the warehouse; revision metadata suffices.'],
            ],
            [
                'name' => 'Content indexing full-text (Volltext)',
                'category' => 'pii',
                'reason' => ['de' => 'Volltext-PII — Metadata reicht für Storage-/Retention-KPIs.', 'en' => 'Full-text PII — metadata is enough for storage/retention KPIs.'],
            ],
            [
                'name' => 'Preview / viewer cache renditions',
                'category' => 'security',
                'reason' => ['de' => 'Abgeleitete Binaries — nie ins Warehouse.', 'en' => 'Derived binaries — never into the warehouse.'],
            ],
            [
                'name' => 'Full activity log (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Technisches Activity-Log — Volumen, wenig Mart-Nutzen; Meta/Agg bevorzugen.', 'en' => 'Technical activity log — volume, little mart value; prefer meta/agg.'],
            ],
        ],
        'skip' => [
            ['name' => 'Document body / attachment file content', 'reason' => ['de' => 'Nie ins Warehouse — Kosten-/Security-Risiko.', 'en' => 'Never into the warehouse — cost/security risk.']],
            ['name' => 'Content indexing full-text (bulk)', 'reason' => ['de' => 'Kann Drittparteien-PII enthalten.', 'en' => 'Can contain third-party PII.']],
            ['name' => 'Preview / viewer cache renditions', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen.', 'en' => 'Binaries without analytics value.']],
            ['name' => 'User UserName/Email cleartext in default marts', 'reason' => ['de' => 'UserId reicht für DMS-KPIs.', 'en' => 'UserId is enough for DMS KPIs.']],
            ['name' => 'Full activity log', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History.', 'en' => 'Volume — meta/agg instead of full history.']],
        ],
    ],

    'box' => [
        'pii' => [
            [
                'entity' => 'User',
                'fields' => ['name', 'login'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: id only; Mart: aggregates only', 'en' => 'RAW: restrict access; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Enterprise-User-Stammdaten (Login = E-Mail) — nur mit Legal-Basis; id als Key behalten.', 'en' => 'Enterprise user master data (login = email) — only with legal basis; keep id as key.'],
            ],
            [
                'entity' => 'File owner reference',
                'fields' => ['owned_by display name'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: id only; Curated: id only; Mart: aggregates only', 'en' => 'RAW: id only; Curated: id only; Mart: aggregates only'],
                'treatment' => ['de' => 'Owner-Anzeige löst auf User-PII auf — nur id joinen.', 'en' => 'Owner display resolves to user PII — join only by id.'],
            ],
            [
                'entity' => 'Full-text preview / content search index',
                'fields' => ['extracted file content'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Volltext kann personenbezogene/Vertragsdaten enthalten — nie ins Warehouse.', 'en' => 'Full text can contain personal/contract data — never land it in the warehouse.'],
            ],
            [
                'entity' => 'Preview renditions (thumbnails/watermark)',
                'fields' => ['preview image body'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: nie speichern; Curated: nie; Mart: nie', 'en' => 'RAW: never store; Curated: never; Mart: never'],
                'treatment' => ['de' => 'Vorschau-Binaries sind abgeleiteter Content — kein Warehouse-Load.', 'en' => 'Preview binaries are derived content — no warehouse load.'],
            ],
            [
                'entity' => 'Metadata instance values (customer-related templates)',
                'fields' => ['customer/contact name custom fields'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: aggregates only; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: aggregates only; Mart: aggregates only'],
                'treatment' => ['de' => 'Custom-Metadata-Werte können je Template Drittparteien-PII enthalten — pro Template reviewen.', 'en' => 'Custom metadata values can contain third-party PII depending on the template — review per template.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'file.id, user.id, file_version.id, folder.id.', 'en' => 'file.id, user.id, file_version.id, folder.id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'File, Folder, Collaboration, Legal Hold Assignment, User — kein File-Content, kein Volltext.', 'en' => 'File, folder, collaboration, legal hold assignment, user — no file content, no full text.'],
            ],
            [
                'focus' => ['de' => 'Batch / export copies', 'en' => 'Batch / export copies'],
                'notes' => ['de' => 'Legal-Hold-Exports und Backup-Kopien verdoppeln File-Metadata und Owner-PII.', 'en' => 'Legal-hold exports and backup copies duplicate file metadata and owner PII.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / test enterprise copies', 'en' => 'Sandbox / test enterprise copies'],
                'notes' => ['de' => 'Sandbox-/Test-Enterprise-Instanzen nicht mit Prod-DMS-Marts mischen.', 'en' => 'Do not mix sandbox/test enterprise instances with prod DMS marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'File content / binary body (all versions)',
                'category' => 'blob',
                'reason' => ['de' => 'Volle Dateikörper — nie ins Warehouse; Versions-Metadata reicht.', 'en' => 'Full file bodies — never into the warehouse; version metadata suffices.'],
            ],
            [
                'name' => 'Full-text preview / content search index',
                'category' => 'pii',
                'reason' => ['de' => 'Volltext-PII — Metadata reicht für Storage-KPIs.', 'en' => 'Full-text PII — metadata is enough for storage KPIs.'],
            ],
            [
                'name' => 'Preview renditions (thumbnails/watermark)',
                'category' => 'security',
                'reason' => ['de' => 'Abgeleitete Binaries — nie ins Warehouse.', 'en' => 'Derived binaries — never into the warehouse.'],
            ],
            [
                'name' => 'Full event stream (Box Events API bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Technischer Event-Stream — Volumen, wenig Mart-Nutzen; Meta/Agg bevorzugen.', 'en' => 'Technical event stream — volume, little mart value; prefer meta/agg.'],
            ],
        ],
        'skip' => [
            ['name' => 'File content / binary body (all versions)', 'reason' => ['de' => 'Nie ins Warehouse — Kosten-/Security-Risiko.', 'en' => 'Never into the warehouse — cost/security risk.']],
            ['name' => 'Full-text preview / content search index', 'reason' => ['de' => 'Kann personenbezogene Daten enthalten.', 'en' => 'Can contain personal data.']],
            ['name' => 'Preview renditions (thumbnails/watermark)', 'reason' => ['de' => 'Binaries ohne Analytics-Nutzen.', 'en' => 'Binaries without analytics value.']],
            ['name' => 'User name/login cleartext in default marts', 'reason' => ['de' => 'id reicht für DMS-KPIs.', 'en' => 'id is enough for DMS KPIs.']],
            ['name' => 'Full event stream (Events API bulk)', 'reason' => ['de' => 'Volumen — Meta/Agg statt Full History.', 'en' => 'Volume — meta/agg instead of full history.']],
        ],
    ],
];
