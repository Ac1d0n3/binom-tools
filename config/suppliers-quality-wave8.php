<?php

/**
 * Wave 8 quality overlays — DMS/Content source-native DQ, MDM, metadata guides.
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'opentext' => [
        'quality' => [
            'dq' => [
                [
                    'id' => 'ot-doc-no-owner',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'OpenText: Dokument ohne Owner',
                        'en' => 'OpenText: document without owner',
                    ],
                    'problem' => [
                        'de' => 'Dokumente ohne Owner verzerren ACL- und Ownership-KPIs.',
                        'en' => 'Documents without owner distort ACL and ownership KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Create-Policy: Owner Pflicht; Sync orphan cleanup.',
                        'en' => 'Create policy: owner required; sync orphan cleanup.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Admins: Owner bei Create setzen.',
                        'en' => 'Admins: Set owner on create.',
                    ],
                    'checks' => [
                        'de' => 'document.owner_id IS NULL',
                        'en' => 'document.owner_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Owner zuweisen oder archivieren.',
                        'en' => 'Assign owner or archive.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine; Unknown-owner dim.',
                        'en' => 'Quarantine; Unknown-owner dim.',
                    ],
                ],
                [
                    'id' => 'ot-version-orphan',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Version ohne Parent-Dokument',
                        'en' => 'Version without parent document',
                    ],
                    'problem' => [
                        'de' => 'Versionen ohne Dokument-Join brechen Storage- und Version-Counts.',
                        'en' => 'Versions without document join break storage and version counts.',
                    ],
                    'prevent' => [
                        'de' => 'Extract immer Parent-ID mitladen.',
                        'en' => 'Always load parent id in extract.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Version Natural Key inkl. Parent.',
                        'en' => 'Data: Version natural key includes parent.',
                    ],
                    'checks' => [
                        'de' => 'version.document_id IS NULL OR document_id NOT IN dim_document',
                        'en' => 'version.document_id IS NULL OR document_id NOT IN dim_document',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract; Tombstones für gelöschte Docs.',
                        'en' => 'Re-extract; tombstones for deleted docs.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine orphan versions.',
                        'en' => 'Quarantine orphan versions.',
                    ],
                ],
                [
                    'id' => 'ot-acl-without-principal',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'ACL ohne Principal',
                        'en' => 'ACL without principal',
                    ],
                    'problem' => [
                        'de' => 'Permission Grants ohne User/Group sind toter Security-Ballast.',
                        'en' => 'Permission grants without user/group are dead security ballast.',
                    ],
                    'prevent' => [
                        'de' => 'ACL Write: Principal Pflicht.',
                        'en' => 'ACL write: principal required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Security: Keine leeren ACL-Zeilen.',
                        'en' => 'Security: No empty ACL rows.',
                    ],
                    'checks' => [
                        'de' => 'permission.principal_id IS NULL',
                        'en' => 'permission.principal_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'ACL bereinigen.',
                        'en' => 'Clean ACLs.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Flag orphan_acl; exclude from coverage KPIs.',
                        'en' => 'Flag orphan_acl; exclude from coverage KPIs.',
                    ],
                ],
                [
                    'id' => 'ot-folder-path-null',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Dokument ohne Folder/Cabinet-Pfad',
                        'en' => 'Document without folder/cabinet path',
                    ],
                    'problem' => [
                        'de' => 'Dokumente ohne Ablage-Pfad verzerren Taxonomy-Rollups.',
                        'en' => 'Documents without filing path distort taxonomy rollups.',
                    ],
                    'prevent' => [
                        'de' => 'Default folder/cabinet bei Create.',
                        'en' => 'Default folder/cabinet on create.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Records: Ablage-Pfad ist Pflicht-Dim.',
                        'en' => 'Records: Filing path is a required dim.',
                    ],
                    'checks' => [
                        'de' => 'document.folder_id IS NULL',
                        'en' => 'document.folder_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'In Default-Ablage verschieben.',
                        'en' => 'Move into default filing location.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown-folder dim.',
                        'en' => 'Unknown-folder dim.',
                    ],
                ],
                [
                    'id' => 'ot-mime-missing',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Dokument ohne MIME/Content-Type',
                        'en' => 'Document without MIME/content type',
                    ],
                    'problem' => [
                        'de' => 'Fehlender Content-Type bricht Storage-by-Type KPIs.',
                        'en' => 'Missing content type breaks storage-by-type KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Upload: MIME detecten; Allowlist.',
                        'en' => 'Upload: detect MIME; allowlist.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Platform: MIME bei Ingest speichern.',
                        'en' => 'Platform: Persist MIME on ingest.',
                    ],
                    'checks' => [
                        'de' => 'document.mime_type IS NULL AND status = \'active\'',
                        'en' => 'document.mime_type IS NULL AND status = \'active\'',
                    ],
                    'fixInSource' => [
                        'de' => 'MIME nachziehen.',
                        'en' => 'Backfill MIME.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown mime bucket.',
                        'en' => 'Unknown mime bucket.',
                    ],
                ],
                [
                    'id' => 'ot-duplicate-doc-key',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Doppelte Document-ID im Extract',
                        'en' => 'Duplicate document id in extract',
                    ],
                    'problem' => [
                        'de' => 'Doppelte Keys verzerren Document Counts.',
                        'en' => 'Duplicate keys distort document counts.',
                    ],
                    'prevent' => [
                        'de' => 'Idempotent load; Pagination prüfen.',
                        'en' => 'Idempotent load; check pagination.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Natural Key = document_id.',
                        'en' => 'Data: Natural key = document_id.',
                    ],
                    'checks' => [
                        'de' => 'COUNT(*) GROUP BY document_id HAVING COUNT > 1',
                        'en' => 'COUNT(*) GROUP BY document_id HAVING COUNT > 1',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract.',
                        'en' => 'Re-extract.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Dedup by latest _loaded_at.',
                        'en' => 'Dedup by latest _loaded_at.',
                    ],
                ],
            ],
            'mdm' => [
                [
                    'id' => 'ot-doc-dup',
                    'entity' => 'Document',
                    'title' => [
                        'de' => 'Dokument-Duplikate / Rename',
                        'en' => 'Document duplicates / rename',
                    ],
                    'matchKeys' => [
                        'document_id',
                        'external_id',
                        'name+folder_path',
                        'content_hash_meta',
                    ],
                    'preventInSource' => [
                        'de' => 'Stable object id; track renames.',
                        'en' => 'Stable object id; track renames.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden document_id; name SCD2.',
                        'en' => 'Golden document_id; name SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktives Dokument mit neuester Version gewinnt.',
                        'en' => 'Active document with latest version wins.',
                    ],
                ],
                [
                    'id' => 'ot-user-dup',
                    'entity' => 'User',
                    'title' => [
                        'de' => 'User-Duplikate',
                        'en' => 'User duplicates',
                    ],
                    'matchKeys' => [
                        'user_id',
                        'login',
                        'email (hashed)',
                        'external_idp_id',
                    ],
                    'preventInSource' => [
                        'de' => 'IdP as source of truth.',
                        'en' => 'IdP as source of truth.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden user id; login SCD2.',
                        'en' => 'Golden user id; login SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktiver User mit neuester Activity gewinnt.',
                        'en' => 'Active user with latest activity wins.',
                    ],
                ],
            ],
            'productSettings' => [
                [
                    'id' => 'ot-no-body-extract',
                    'area' => [
                        'de' => 'Extract / Integration',
                        'en' => 'Extract / Integration',
                    ],
                    'setting' => [
                        'de' => 'Nur Metadata/ACL/Versions-Meta — keine File Bodies',
                        'en' => 'Metadata/ACL/version meta only — no file bodies',
                    ],
                    'why' => [
                        'de' => 'Analytics ohne Content-Leak und Volumenexplosion.',
                        'en' => 'Analytics without content leak and volume explosion.',
                    ],
                    'how' => [
                        'de' => 'Allowlist fields; binary endpoints nie in Warehouse.',
                        'en' => 'Allowlist fields; never land binary endpoints in warehouse.',
                    ],
                ],
                [
                    'id' => 'ot-acl-export',
                    'area' => [
                        'de' => 'Security / Permissions',
                        'en' => 'Security / Permissions',
                    ],
                    'setting' => [
                        'de' => 'ACL Export als Principal-IDs',
                        'en' => 'ACL export as principal ids',
                    ],
                    'why' => [
                        'de' => 'Access KPIs ohne Klartext-Mails in Marts.',
                        'en' => 'Access KPIs without cleartext mails in marts.',
                    ],
                    'how' => [
                        'de' => 'Principal id + role; email nur RAW restricted.',
                        'en' => 'Principal id + role; email RAW restricted only.',
                    ],
                ],
                [
                    'id' => 'ot-retention',
                    'area' => [
                        'de' => 'Records / Retention',
                        'en' => 'Records / Retention',
                    ],
                    'setting' => [
                        'de' => 'Retention Labels und Legal Hold Metadata tracken',
                        'en' => 'Track retention labels and legal hold metadata',
                    ],
                    'why' => [
                        'de' => 'Compliance KPIs ohne Content-Bodies.',
                        'en' => 'Compliance KPIs without content bodies.',
                    ],
                    'how' => [
                        'de' => 'Hold/Retention flags + dates; no binary.',
                        'en' => 'Hold/retention flags + dates; no binary.',
                    ],
                ],
            ],
            'metadata' => [
                [
                    'id' => 'ot-object-model',
                    'kind' => [
                        'de' => 'Object / Document model',
                        'en' => 'Object / document model',
                    ],
                    'where' => [
                        'de' => 'Content Server / OTDS metadata APIs',
                        'en' => 'Content Server / OTDS metadata APIs',
                    ],
                    'how' => [
                        'de' => 'Entities/Properties inventarisieren; Select-Allowlist.',
                        'en' => 'Inventory entities/properties; select allowlist.',
                    ],
                    'useFor' => [
                        'de' => 'Extract design, PII tags, Join-Keys.',
                        'en' => 'Extract design, PII tags, join keys.',
                    ],
                    'watchouts' => [
                        'de' => 'Nie File/OCR Bodies selektieren.',
                        'en' => 'Never select file/OCR bodies.',
                    ],
                ],
                [
                    'id' => 'ot-acl-model',
                    'kind' => [
                        'de' => 'ACL / Permission model',
                        'en' => 'ACL / permission model',
                    ],
                    'where' => [
                        'de' => 'OpenText permissions / ACLs',
                        'en' => 'OpenText permissions / ACLs',
                    ],
                    'how' => [
                        'de' => 'Principal, Role, Object Scope dokumentieren.',
                        'en' => 'Document principal, role, object scope.',
                    ],
                    'useFor' => [
                        'de' => 'Access facts, orphan-ACL DQ.',
                        'en' => 'Access facts, orphan-ACL DQ.',
                    ],
                    'watchouts' => [
                        'de' => 'Inherited ACLs — SCD2 empfohlen.',
                        'en' => 'Inherited ACLs — SCD2 recommended.',
                    ],
                ],
                [
                    'id' => 'ot-version-meta',
                    'kind' => [
                        'de' => 'Version metadata',
                        'en' => 'Version metadata',
                    ],
                    'where' => [
                        'de' => 'OpenText version history (meta only)',
                        'en' => 'OpenText version history (meta only)',
                    ],
                    'how' => [
                        'de' => 'Version number, size, author id, timestamps.',
                        'en' => 'Version number, size, author id, timestamps.',
                    ],
                    'useFor' => [
                        'de' => 'Version counts, storage volume KPIs.',
                        'en' => 'Version counts, storage volume KPIs.',
                    ],
                    'watchouts' => [
                        'de' => 'Keine Binary Streams.',
                        'en' => 'No binary streams.',
                    ],
                ],
                [
                    'id' => 'ot-taxonomy',
                    'kind' => [
                        'de' => 'Folder / Category taxonomy',
                        'en' => 'Folder / category taxonomy',
                    ],
                    'where' => [
                        'de' => 'OpenText folders / categories / cabinets',
                        'en' => 'OpenText folders / categories / cabinets',
                    ],
                    'how' => [
                        'de' => 'Hierarchy und Codes exportieren.',
                        'en' => 'Export hierarchy and codes.',
                    ],
                    'useFor' => [
                        'de' => 'Taxonomy dims, path rollups.',
                        'en' => 'Taxonomy dims, path rollups.',
                    ],
                    'watchouts' => [
                        'de' => 'Renames — SCD2.',
                        'en' => 'Renames — SCD2.',
                    ],
                ],
            ],
        ],
        'tools' => [
            'dbt-dq-rules-generator',
            'dbt-dq-macro-generator',
            'dbt-dq-history-generator',
            'schema-yml-editor',
        ],
    ],
    'fabasoft' => [
        'quality' => [
            'dq' => [
                [
                    'id' => 'faba-doc-no-owner',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Fabasoft: Dokument ohne Owner',
                        'en' => 'Fabasoft: document without owner',
                    ],
                    'problem' => [
                        'de' => 'Dokumente ohne Owner verzerren ACL- und Ownership-KPIs.',
                        'en' => 'Documents without owner distort ACL and ownership KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Create-Policy: Owner Pflicht; Sync orphan cleanup.',
                        'en' => 'Create policy: owner required; sync orphan cleanup.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Admins: Owner bei Create setzen.',
                        'en' => 'Admins: Set owner on create.',
                    ],
                    'checks' => [
                        'de' => 'object.owner_id IS NULL',
                        'en' => 'object.owner_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Owner zuweisen oder archivieren.',
                        'en' => 'Assign owner or archive.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine; Unknown-owner dim.',
                        'en' => 'Quarantine; Unknown-owner dim.',
                    ],
                ],
                [
                    'id' => 'faba-version-orphan',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Version ohne Parent-Dokument',
                        'en' => 'Version without parent document',
                    ],
                    'problem' => [
                        'de' => 'Versionen ohne Dokument-Join brechen Storage- und Version-Counts.',
                        'en' => 'Versions without document join break storage and version counts.',
                    ],
                    'prevent' => [
                        'de' => 'Extract immer Parent-ID mitladen.',
                        'en' => 'Always load parent id in extract.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Version Natural Key inkl. Parent.',
                        'en' => 'Data: Version natural key includes parent.',
                    ],
                    'checks' => [
                        'de' => 'content_version.object_id IS NULL OR object_id NOT IN dim_object',
                        'en' => 'content_version.object_id IS NULL OR object_id NOT IN dim_object',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract; Tombstones für gelöschte Docs.',
                        'en' => 'Re-extract; tombstones for deleted docs.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine orphan versions.',
                        'en' => 'Quarantine orphan versions.',
                    ],
                ],
                [
                    'id' => 'faba-acl-without-principal',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'ACL ohne Principal',
                        'en' => 'ACL without principal',
                    ],
                    'problem' => [
                        'de' => 'Permission Grants ohne User/Group sind toter Security-Ballast.',
                        'en' => 'Permission grants without user/group are dead security ballast.',
                    ],
                    'prevent' => [
                        'de' => 'ACL Write: Principal Pflicht.',
                        'en' => 'ACL write: principal required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Security: Keine leeren ACL-Zeilen.',
                        'en' => 'Security: No empty ACL rows.',
                    ],
                    'checks' => [
                        'de' => 'permission.principal_id IS NULL',
                        'en' => 'permission.principal_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'ACL bereinigen.',
                        'en' => 'Clean ACLs.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Flag orphan_acl; exclude from coverage KPIs.',
                        'en' => 'Flag orphan_acl; exclude from coverage KPIs.',
                    ],
                ],
                [
                    'id' => 'faba-folder-path-null',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Dokument ohne Folder/Cabinet-Pfad',
                        'en' => 'Document without folder/cabinet path',
                    ],
                    'problem' => [
                        'de' => 'Dokumente ohne Ablage-Pfad verzerren Taxonomy-Rollups.',
                        'en' => 'Documents without filing path distort taxonomy rollups.',
                    ],
                    'prevent' => [
                        'de' => 'Default folder/cabinet bei Create.',
                        'en' => 'Default folder/cabinet on create.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Records: Ablage-Pfad ist Pflicht-Dim.',
                        'en' => 'Records: Filing path is a required dim.',
                    ],
                    'checks' => [
                        'de' => 'object.room_id IS NULL',
                        'en' => 'object.room_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'In Default-Ablage verschieben.',
                        'en' => 'Move into default filing location.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown-folder dim.',
                        'en' => 'Unknown-folder dim.',
                    ],
                ],
                [
                    'id' => 'faba-mime-missing',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Dokument ohne MIME/Content-Type',
                        'en' => 'Document without MIME/content type',
                    ],
                    'problem' => [
                        'de' => 'Fehlender Content-Type bricht Storage-by-Type KPIs.',
                        'en' => 'Missing content type breaks storage-by-type KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Upload: MIME detecten; Allowlist.',
                        'en' => 'Upload: detect MIME; allowlist.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Platform: MIME bei Ingest speichern.',
                        'en' => 'Platform: Persist MIME on ingest.',
                    ],
                    'checks' => [
                        'de' => 'object.mime_type IS NULL AND status = \'active\'',
                        'en' => 'object.mime_type IS NULL AND status = \'active\'',
                    ],
                    'fixInSource' => [
                        'de' => 'MIME nachziehen.',
                        'en' => 'Backfill MIME.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown mime bucket.',
                        'en' => 'Unknown mime bucket.',
                    ],
                ],
                [
                    'id' => 'faba-duplicate-doc-key',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Doppelte Document-ID im Extract',
                        'en' => 'Duplicate document id in extract',
                    ],
                    'problem' => [
                        'de' => 'Doppelte Keys verzerren Document Counts.',
                        'en' => 'Duplicate keys distort document counts.',
                    ],
                    'prevent' => [
                        'de' => 'Idempotent load; Pagination prüfen.',
                        'en' => 'Idempotent load; check pagination.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Natural Key = object_id.',
                        'en' => 'Data: Natural key = object_id.',
                    ],
                    'checks' => [
                        'de' => 'COUNT(*) GROUP BY object_id HAVING COUNT > 1',
                        'en' => 'COUNT(*) GROUP BY object_id HAVING COUNT > 1',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract.',
                        'en' => 'Re-extract.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Dedup by latest _loaded_at.',
                        'en' => 'Dedup by latest _loaded_at.',
                    ],
                ],
            ],
            'mdm' => [
                [
                    'id' => 'faba-doc-dup',
                    'entity' => 'Object',
                    'title' => [
                        'de' => 'Dokument-Duplikate / Rename',
                        'en' => 'Document duplicates / rename',
                    ],
                    'matchKeys' => [
                        'object_id',
                        'external_id',
                        'name+folder_path',
                        'content_hash_meta',
                    ],
                    'preventInSource' => [
                        'de' => 'Stable object id; track renames.',
                        'en' => 'Stable object id; track renames.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden object_id; name SCD2.',
                        'en' => 'Golden object_id; name SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktives Dokument mit neuester Version gewinnt.',
                        'en' => 'Active document with latest version wins.',
                    ],
                ],
                [
                    'id' => 'faba-user-dup',
                    'entity' => 'User',
                    'title' => [
                        'de' => 'User-Duplikate',
                        'en' => 'User duplicates',
                    ],
                    'matchKeys' => [
                        'user_id',
                        'login',
                        'email (hashed)',
                        'external_idp_id',
                    ],
                    'preventInSource' => [
                        'de' => 'IdP as source of truth.',
                        'en' => 'IdP as source of truth.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden user id; login SCD2.',
                        'en' => 'Golden user id; login SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktiver User mit neuester Activity gewinnt.',
                        'en' => 'Active user with latest activity wins.',
                    ],
                ],
            ],
            'productSettings' => [
                [
                    'id' => 'faba-no-body-extract',
                    'area' => [
                        'de' => 'Extract / Integration',
                        'en' => 'Extract / Integration',
                    ],
                    'setting' => [
                        'de' => 'Nur Metadata/ACL/Versions-Meta — keine File Bodies',
                        'en' => 'Metadata/ACL/version meta only — no file bodies',
                    ],
                    'why' => [
                        'de' => 'Analytics ohne Content-Leak und Volumenexplosion.',
                        'en' => 'Analytics without content leak and volume explosion.',
                    ],
                    'how' => [
                        'de' => 'Allowlist fields; binary endpoints nie in Warehouse.',
                        'en' => 'Allowlist fields; never land binary endpoints in warehouse.',
                    ],
                ],
                [
                    'id' => 'faba-acl-export',
                    'area' => [
                        'de' => 'Security / Permissions',
                        'en' => 'Security / Permissions',
                    ],
                    'setting' => [
                        'de' => 'ACL Export als Principal-IDs',
                        'en' => 'ACL export as principal ids',
                    ],
                    'why' => [
                        'de' => 'Access KPIs ohne Klartext-Mails in Marts.',
                        'en' => 'Access KPIs without cleartext mails in marts.',
                    ],
                    'how' => [
                        'de' => 'Principal id + role; email nur RAW restricted.',
                        'en' => 'Principal id + role; email RAW restricted only.',
                    ],
                ],
                [
                    'id' => 'faba-retention',
                    'area' => [
                        'de' => 'Records / Retention',
                        'en' => 'Records / Retention',
                    ],
                    'setting' => [
                        'de' => 'Retention Labels und Legal Hold Metadata tracken',
                        'en' => 'Track retention labels and legal hold metadata',
                    ],
                    'why' => [
                        'de' => 'Compliance KPIs ohne Content-Bodies.',
                        'en' => 'Compliance KPIs without content bodies.',
                    ],
                    'how' => [
                        'de' => 'Hold/Retention flags + dates; no binary.',
                        'en' => 'Hold/retention flags + dates; no binary.',
                    ],
                ],
            ],
            'metadata' => [
                [
                    'id' => 'faba-object-model',
                    'kind' => [
                        'de' => 'Object / Document model',
                        'en' => 'Object / document model',
                    ],
                    'where' => [
                        'de' => 'Fabasoft Cloud / Folio object model',
                        'en' => 'Fabasoft Cloud / Folio object model',
                    ],
                    'how' => [
                        'de' => 'Entities/Properties inventarisieren; Select-Allowlist.',
                        'en' => 'Inventory entities/properties; select allowlist.',
                    ],
                    'useFor' => [
                        'de' => 'Extract design, PII tags, Join-Keys.',
                        'en' => 'Extract design, PII tags, join keys.',
                    ],
                    'watchouts' => [
                        'de' => 'Nie File/OCR Bodies selektieren.',
                        'en' => 'Never select file/OCR bodies.',
                    ],
                ],
                [
                    'id' => 'faba-acl-model',
                    'kind' => [
                        'de' => 'ACL / Permission model',
                        'en' => 'ACL / permission model',
                    ],
                    'where' => [
                        'de' => 'Fabasoft permissions / ACLs',
                        'en' => 'Fabasoft permissions / ACLs',
                    ],
                    'how' => [
                        'de' => 'Principal, Role, Object Scope dokumentieren.',
                        'en' => 'Document principal, role, object scope.',
                    ],
                    'useFor' => [
                        'de' => 'Access facts, orphan-ACL DQ.',
                        'en' => 'Access facts, orphan-ACL DQ.',
                    ],
                    'watchouts' => [
                        'de' => 'Inherited ACLs — SCD2 empfohlen.',
                        'en' => 'Inherited ACLs — SCD2 recommended.',
                    ],
                ],
                [
                    'id' => 'faba-version-meta',
                    'kind' => [
                        'de' => 'Version metadata',
                        'en' => 'Version metadata',
                    ],
                    'where' => [
                        'de' => 'Fabasoft version history (meta only)',
                        'en' => 'Fabasoft version history (meta only)',
                    ],
                    'how' => [
                        'de' => 'Version number, size, author id, timestamps.',
                        'en' => 'Version number, size, author id, timestamps.',
                    ],
                    'useFor' => [
                        'de' => 'Version counts, storage volume KPIs.',
                        'en' => 'Version counts, storage volume KPIs.',
                    ],
                    'watchouts' => [
                        'de' => 'Keine Binary Streams.',
                        'en' => 'No binary streams.',
                    ],
                ],
                [
                    'id' => 'faba-taxonomy',
                    'kind' => [
                        'de' => 'Folder / Category taxonomy',
                        'en' => 'Folder / category taxonomy',
                    ],
                    'where' => [
                        'de' => 'Fabasoft folders / categories / cabinets',
                        'en' => 'Fabasoft folders / categories / cabinets',
                    ],
                    'how' => [
                        'de' => 'Hierarchy und Codes exportieren.',
                        'en' => 'Export hierarchy and codes.',
                    ],
                    'useFor' => [
                        'de' => 'Taxonomy dims, path rollups.',
                        'en' => 'Taxonomy dims, path rollups.',
                    ],
                    'watchouts' => [
                        'de' => 'Renames — SCD2.',
                        'en' => 'Renames — SCD2.',
                    ],
                ],
            ],
        ],
        'tools' => [
            'dbt-dq-rules-generator',
            'dbt-dq-macro-generator',
            'dbt-dq-history-generator',
            'schema-yml-editor',
        ],
    ],
    'elo' => [
        'quality' => [
            'dq' => [
                [
                    'id' => 'elo-doc-no-owner',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'ELO: Dokument ohne Owner',
                        'en' => 'ELO: document without owner',
                    ],
                    'problem' => [
                        'de' => 'Dokumente ohne Owner verzerren ACL- und Ownership-KPIs.',
                        'en' => 'Documents without owner distort ACL and ownership KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Create-Policy: Owner Pflicht; Sync orphan cleanup.',
                        'en' => 'Create policy: owner required; sync orphan cleanup.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Admins: Owner bei Create setzen.',
                        'en' => 'Admins: Set owner on create.',
                    ],
                    'checks' => [
                        'de' => 'sord_document.owner_id IS NULL',
                        'en' => 'sord_document.owner_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Owner zuweisen oder archivieren.',
                        'en' => 'Assign owner or archive.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine; Unknown-owner dim.',
                        'en' => 'Quarantine; Unknown-owner dim.',
                    ],
                ],
                [
                    'id' => 'elo-version-orphan',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Version ohne Parent-Dokument',
                        'en' => 'Version without parent document',
                    ],
                    'problem' => [
                        'de' => 'Versionen ohne Dokument-Join brechen Storage- und Version-Counts.',
                        'en' => 'Versions without document join break storage and version counts.',
                    ],
                    'prevent' => [
                        'de' => 'Extract immer Parent-ID mitladen.',
                        'en' => 'Always load parent id in extract.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Version Natural Key inkl. Parent.',
                        'en' => 'Data: Version natural key includes parent.',
                    ],
                    'checks' => [
                        'de' => 'sord_version.obj_id IS NULL OR obj_id NOT IN dim_sord_document',
                        'en' => 'sord_version.obj_id IS NULL OR obj_id NOT IN dim_sord_document',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract; Tombstones für gelöschte Docs.',
                        'en' => 'Re-extract; tombstones for deleted docs.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine orphan versions.',
                        'en' => 'Quarantine orphan versions.',
                    ],
                ],
                [
                    'id' => 'elo-acl-without-principal',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'ACL ohne Principal',
                        'en' => 'ACL without principal',
                    ],
                    'problem' => [
                        'de' => 'Permission Grants ohne User/Group sind toter Security-Ballast.',
                        'en' => 'Permission grants without user/group are dead security ballast.',
                    ],
                    'prevent' => [
                        'de' => 'ACL Write: Principal Pflicht.',
                        'en' => 'ACL write: principal required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Security: Keine leeren ACL-Zeilen.',
                        'en' => 'Security: No empty ACL rows.',
                    ],
                    'checks' => [
                        'de' => 'permission.principal_id IS NULL',
                        'en' => 'permission.principal_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'ACL bereinigen.',
                        'en' => 'Clean ACLs.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Flag orphan_acl; exclude from coverage KPIs.',
                        'en' => 'Flag orphan_acl; exclude from coverage KPIs.',
                    ],
                ],
                [
                    'id' => 'elo-folder-path-null',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Dokument ohne Folder/Cabinet-Pfad',
                        'en' => 'Document without folder/cabinet path',
                    ],
                    'problem' => [
                        'de' => 'Dokumente ohne Ablage-Pfad verzerren Taxonomy-Rollups.',
                        'en' => 'Documents without filing path distort taxonomy rollups.',
                    ],
                    'prevent' => [
                        'de' => 'Default folder/cabinet bei Create.',
                        'en' => 'Default folder/cabinet on create.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Records: Ablage-Pfad ist Pflicht-Dim.',
                        'en' => 'Records: Filing path is a required dim.',
                    ],
                    'checks' => [
                        'de' => 'sord_document.sord_folder_id IS NULL',
                        'en' => 'sord_document.sord_folder_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'In Default-Ablage verschieben.',
                        'en' => 'Move into default filing location.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown-folder dim.',
                        'en' => 'Unknown-folder dim.',
                    ],
                ],
                [
                    'id' => 'elo-mime-missing',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Dokument ohne MIME/Content-Type',
                        'en' => 'Document without MIME/content type',
                    ],
                    'problem' => [
                        'de' => 'Fehlender Content-Type bricht Storage-by-Type KPIs.',
                        'en' => 'Missing content type breaks storage-by-type KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Upload: MIME detecten; Allowlist.',
                        'en' => 'Upload: detect MIME; allowlist.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Platform: MIME bei Ingest speichern.',
                        'en' => 'Platform: Persist MIME on ingest.',
                    ],
                    'checks' => [
                        'de' => 'sord_document.mime_type IS NULL AND status = \'active\'',
                        'en' => 'sord_document.mime_type IS NULL AND status = \'active\'',
                    ],
                    'fixInSource' => [
                        'de' => 'MIME nachziehen.',
                        'en' => 'Backfill MIME.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown mime bucket.',
                        'en' => 'Unknown mime bucket.',
                    ],
                ],
                [
                    'id' => 'elo-duplicate-doc-key',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Doppelte Document-ID im Extract',
                        'en' => 'Duplicate document id in extract',
                    ],
                    'problem' => [
                        'de' => 'Doppelte Keys verzerren Document Counts.',
                        'en' => 'Duplicate keys distort document counts.',
                    ],
                    'prevent' => [
                        'de' => 'Idempotent load; Pagination prüfen.',
                        'en' => 'Idempotent load; check pagination.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Natural Key = obj_id.',
                        'en' => 'Data: Natural key = obj_id.',
                    ],
                    'checks' => [
                        'de' => 'COUNT(*) GROUP BY obj_id HAVING COUNT > 1',
                        'en' => 'COUNT(*) GROUP BY obj_id HAVING COUNT > 1',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract.',
                        'en' => 'Re-extract.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Dedup by latest _loaded_at.',
                        'en' => 'Dedup by latest _loaded_at.',
                    ],
                ],
            ],
            'mdm' => [
                [
                    'id' => 'elo-doc-dup',
                    'entity' => 'Sord document',
                    'title' => [
                        'de' => 'Dokument-Duplikate / Rename',
                        'en' => 'Document duplicates / rename',
                    ],
                    'matchKeys' => [
                        'obj_id',
                        'external_id',
                        'name+folder_path',
                        'content_hash_meta',
                    ],
                    'preventInSource' => [
                        'de' => 'Stable object id; track renames.',
                        'en' => 'Stable object id; track renames.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden obj_id; name SCD2.',
                        'en' => 'Golden obj_id; name SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktives Dokument mit neuester Version gewinnt.',
                        'en' => 'Active document with latest version wins.',
                    ],
                ],
                [
                    'id' => 'elo-user-dup',
                    'entity' => 'User',
                    'title' => [
                        'de' => 'User-Duplikate',
                        'en' => 'User duplicates',
                    ],
                    'matchKeys' => [
                        'user_id',
                        'login',
                        'email (hashed)',
                        'external_idp_id',
                    ],
                    'preventInSource' => [
                        'de' => 'IdP as source of truth.',
                        'en' => 'IdP as source of truth.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden user id; login SCD2.',
                        'en' => 'Golden user id; login SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktiver User mit neuester Activity gewinnt.',
                        'en' => 'Active user with latest activity wins.',
                    ],
                ],
            ],
            'productSettings' => [
                [
                    'id' => 'elo-no-body-extract',
                    'area' => [
                        'de' => 'Extract / Integration',
                        'en' => 'Extract / Integration',
                    ],
                    'setting' => [
                        'de' => 'Nur Metadata/ACL/Versions-Meta — keine File Bodies',
                        'en' => 'Metadata/ACL/version meta only — no file bodies',
                    ],
                    'why' => [
                        'de' => 'Analytics ohne Content-Leak und Volumenexplosion.',
                        'en' => 'Analytics without content leak and volume explosion.',
                    ],
                    'how' => [
                        'de' => 'Allowlist fields; binary endpoints nie in Warehouse.',
                        'en' => 'Allowlist fields; never land binary endpoints in warehouse.',
                    ],
                ],
                [
                    'id' => 'elo-acl-export',
                    'area' => [
                        'de' => 'Security / Permissions',
                        'en' => 'Security / Permissions',
                    ],
                    'setting' => [
                        'de' => 'ACL Export als Principal-IDs',
                        'en' => 'ACL export as principal ids',
                    ],
                    'why' => [
                        'de' => 'Access KPIs ohne Klartext-Mails in Marts.',
                        'en' => 'Access KPIs without cleartext mails in marts.',
                    ],
                    'how' => [
                        'de' => 'Principal id + role; email nur RAW restricted.',
                        'en' => 'Principal id + role; email RAW restricted only.',
                    ],
                ],
                [
                    'id' => 'elo-retention',
                    'area' => [
                        'de' => 'Records / Retention',
                        'en' => 'Records / Retention',
                    ],
                    'setting' => [
                        'de' => 'Retention Labels und Legal Hold Metadata tracken',
                        'en' => 'Track retention labels and legal hold metadata',
                    ],
                    'why' => [
                        'de' => 'Compliance KPIs ohne Content-Bodies.',
                        'en' => 'Compliance KPIs without content bodies.',
                    ],
                    'how' => [
                        'de' => 'Hold/Retention flags + dates; no binary.',
                        'en' => 'Hold/retention flags + dates; no binary.',
                    ],
                ],
            ],
            'metadata' => [
                [
                    'id' => 'elo-object-model',
                    'kind' => [
                        'de' => 'Object / Document model',
                        'en' => 'Object / document model',
                    ],
                    'where' => [
                        'de' => 'ELO Indexserver / IX API',
                        'en' => 'ELO Indexserver / IX API',
                    ],
                    'how' => [
                        'de' => 'Entities/Properties inventarisieren; Select-Allowlist.',
                        'en' => 'Inventory entities/properties; select allowlist.',
                    ],
                    'useFor' => [
                        'de' => 'Extract design, PII tags, Join-Keys.',
                        'en' => 'Extract design, PII tags, join keys.',
                    ],
                    'watchouts' => [
                        'de' => 'Nie File/OCR Bodies selektieren.',
                        'en' => 'Never select file/OCR bodies.',
                    ],
                ],
                [
                    'id' => 'elo-acl-model',
                    'kind' => [
                        'de' => 'ACL / Permission model',
                        'en' => 'ACL / permission model',
                    ],
                    'where' => [
                        'de' => 'ELO permissions / ACLs',
                        'en' => 'ELO permissions / ACLs',
                    ],
                    'how' => [
                        'de' => 'Principal, Role, Object Scope dokumentieren.',
                        'en' => 'Document principal, role, object scope.',
                    ],
                    'useFor' => [
                        'de' => 'Access facts, orphan-ACL DQ.',
                        'en' => 'Access facts, orphan-ACL DQ.',
                    ],
                    'watchouts' => [
                        'de' => 'Inherited ACLs — SCD2 empfohlen.',
                        'en' => 'Inherited ACLs — SCD2 recommended.',
                    ],
                ],
                [
                    'id' => 'elo-version-meta',
                    'kind' => [
                        'de' => 'Version metadata',
                        'en' => 'Version metadata',
                    ],
                    'where' => [
                        'de' => 'ELO version history (meta only)',
                        'en' => 'ELO version history (meta only)',
                    ],
                    'how' => [
                        'de' => 'Version number, size, author id, timestamps.',
                        'en' => 'Version number, size, author id, timestamps.',
                    ],
                    'useFor' => [
                        'de' => 'Version counts, storage volume KPIs.',
                        'en' => 'Version counts, storage volume KPIs.',
                    ],
                    'watchouts' => [
                        'de' => 'Keine Binary Streams.',
                        'en' => 'No binary streams.',
                    ],
                ],
                [
                    'id' => 'elo-taxonomy',
                    'kind' => [
                        'de' => 'Folder / Category taxonomy',
                        'en' => 'Folder / category taxonomy',
                    ],
                    'where' => [
                        'de' => 'ELO folders / categories / cabinets',
                        'en' => 'ELO folders / categories / cabinets',
                    ],
                    'how' => [
                        'de' => 'Hierarchy und Codes exportieren.',
                        'en' => 'Export hierarchy and codes.',
                    ],
                    'useFor' => [
                        'de' => 'Taxonomy dims, path rollups.',
                        'en' => 'Taxonomy dims, path rollups.',
                    ],
                    'watchouts' => [
                        'de' => 'Renames — SCD2.',
                        'en' => 'Renames — SCD2.',
                    ],
                ],
            ],
        ],
        'tools' => [
            'dbt-dq-rules-generator',
            'dbt-dq-macro-generator',
            'dbt-dq-history-generator',
            'schema-yml-editor',
        ],
    ],
    'docuware' => [
        'quality' => [
            'dq' => [
                [
                    'id' => 'dw-doc-no-owner',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'DocuWare: Dokument ohne Owner',
                        'en' => 'DocuWare: document without owner',
                    ],
                    'problem' => [
                        'de' => 'Dokumente ohne Owner verzerren ACL- und Ownership-KPIs.',
                        'en' => 'Documents without owner distort ACL and ownership KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Create-Policy: Owner Pflicht; Sync orphan cleanup.',
                        'en' => 'Create policy: owner required; sync orphan cleanup.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Admins: Owner bei Create setzen.',
                        'en' => 'Admins: Set owner on create.',
                    ],
                    'checks' => [
                        'de' => 'document.owner_id IS NULL',
                        'en' => 'document.owner_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Owner zuweisen oder archivieren.',
                        'en' => 'Assign owner or archive.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine; Unknown-owner dim.',
                        'en' => 'Quarantine; Unknown-owner dim.',
                    ],
                ],
                [
                    'id' => 'dw-version-orphan',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Version ohne Parent-Dokument',
                        'en' => 'Version without parent document',
                    ],
                    'problem' => [
                        'de' => 'Versionen ohne Dokument-Join brechen Storage- und Version-Counts.',
                        'en' => 'Versions without document join break storage and version counts.',
                    ],
                    'prevent' => [
                        'de' => 'Extract immer Parent-ID mitladen.',
                        'en' => 'Always load parent id in extract.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Version Natural Key inkl. Parent.',
                        'en' => 'Data: Version natural key includes parent.',
                    ],
                    'checks' => [
                        'de' => 'revision.document_id IS NULL OR document_id NOT IN dim_document',
                        'en' => 'revision.document_id IS NULL OR document_id NOT IN dim_document',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract; Tombstones für gelöschte Docs.',
                        'en' => 'Re-extract; tombstones for deleted docs.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine orphan versions.',
                        'en' => 'Quarantine orphan versions.',
                    ],
                ],
                [
                    'id' => 'dw-acl-without-principal',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'ACL ohne Principal',
                        'en' => 'ACL without principal',
                    ],
                    'problem' => [
                        'de' => 'Permission Grants ohne User/Group sind toter Security-Ballast.',
                        'en' => 'Permission grants without user/group are dead security ballast.',
                    ],
                    'prevent' => [
                        'de' => 'ACL Write: Principal Pflicht.',
                        'en' => 'ACL write: principal required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Security: Keine leeren ACL-Zeilen.',
                        'en' => 'Security: No empty ACL rows.',
                    ],
                    'checks' => [
                        'de' => 'permission.principal_id IS NULL',
                        'en' => 'permission.principal_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'ACL bereinigen.',
                        'en' => 'Clean ACLs.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Flag orphan_acl; exclude from coverage KPIs.',
                        'en' => 'Flag orphan_acl; exclude from coverage KPIs.',
                    ],
                ],
                [
                    'id' => 'dw-folder-path-null',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Dokument ohne Folder/Cabinet-Pfad',
                        'en' => 'Document without folder/cabinet path',
                    ],
                    'problem' => [
                        'de' => 'Dokumente ohne Ablage-Pfad verzerren Taxonomy-Rollups.',
                        'en' => 'Documents without filing path distort taxonomy rollups.',
                    ],
                    'prevent' => [
                        'de' => 'Default folder/cabinet bei Create.',
                        'en' => 'Default folder/cabinet on create.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Records: Ablage-Pfad ist Pflicht-Dim.',
                        'en' => 'Records: Filing path is a required dim.',
                    ],
                    'checks' => [
                        'de' => 'document.file_cabinet_id IS NULL',
                        'en' => 'document.file_cabinet_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'In Default-Ablage verschieben.',
                        'en' => 'Move into default filing location.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown-folder dim.',
                        'en' => 'Unknown-folder dim.',
                    ],
                ],
                [
                    'id' => 'dw-mime-missing',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Dokument ohne MIME/Content-Type',
                        'en' => 'Document without MIME/content type',
                    ],
                    'problem' => [
                        'de' => 'Fehlender Content-Type bricht Storage-by-Type KPIs.',
                        'en' => 'Missing content type breaks storage-by-type KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Upload: MIME detecten; Allowlist.',
                        'en' => 'Upload: detect MIME; allowlist.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Platform: MIME bei Ingest speichern.',
                        'en' => 'Platform: Persist MIME on ingest.',
                    ],
                    'checks' => [
                        'de' => 'document.mime_type IS NULL AND status = \'active\'',
                        'en' => 'document.mime_type IS NULL AND status = \'active\'',
                    ],
                    'fixInSource' => [
                        'de' => 'MIME nachziehen.',
                        'en' => 'Backfill MIME.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown mime bucket.',
                        'en' => 'Unknown mime bucket.',
                    ],
                ],
                [
                    'id' => 'dw-duplicate-doc-key',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Doppelte Document-ID im Extract',
                        'en' => 'Duplicate document id in extract',
                    ],
                    'problem' => [
                        'de' => 'Doppelte Keys verzerren Document Counts.',
                        'en' => 'Duplicate keys distort document counts.',
                    ],
                    'prevent' => [
                        'de' => 'Idempotent load; Pagination prüfen.',
                        'en' => 'Idempotent load; check pagination.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Natural Key = document_id.',
                        'en' => 'Data: Natural key = document_id.',
                    ],
                    'checks' => [
                        'de' => 'COUNT(*) GROUP BY document_id HAVING COUNT > 1',
                        'en' => 'COUNT(*) GROUP BY document_id HAVING COUNT > 1',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract.',
                        'en' => 'Re-extract.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Dedup by latest _loaded_at.',
                        'en' => 'Dedup by latest _loaded_at.',
                    ],
                ],
            ],
            'mdm' => [
                [
                    'id' => 'dw-doc-dup',
                    'entity' => 'Document',
                    'title' => [
                        'de' => 'Dokument-Duplikate / Rename',
                        'en' => 'Document duplicates / rename',
                    ],
                    'matchKeys' => [
                        'document_id',
                        'external_id',
                        'name+folder_path',
                        'content_hash_meta',
                    ],
                    'preventInSource' => [
                        'de' => 'Stable object id; track renames.',
                        'en' => 'Stable object id; track renames.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden document_id; name SCD2.',
                        'en' => 'Golden document_id; name SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktives Dokument mit neuester Version gewinnt.',
                        'en' => 'Active document with latest version wins.',
                    ],
                ],
                [
                    'id' => 'dw-user-dup',
                    'entity' => 'User',
                    'title' => [
                        'de' => 'User-Duplikate',
                        'en' => 'User duplicates',
                    ],
                    'matchKeys' => [
                        'user_id',
                        'login',
                        'email (hashed)',
                        'external_idp_id',
                    ],
                    'preventInSource' => [
                        'de' => 'IdP as source of truth.',
                        'en' => 'IdP as source of truth.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden user id; login SCD2.',
                        'en' => 'Golden user id; login SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktiver User mit neuester Activity gewinnt.',
                        'en' => 'Active user with latest activity wins.',
                    ],
                ],
            ],
            'productSettings' => [
                [
                    'id' => 'dw-no-body-extract',
                    'area' => [
                        'de' => 'Extract / Integration',
                        'en' => 'Extract / Integration',
                    ],
                    'setting' => [
                        'de' => 'Nur Metadata/ACL/Versions-Meta — keine File Bodies',
                        'en' => 'Metadata/ACL/version meta only — no file bodies',
                    ],
                    'why' => [
                        'de' => 'Analytics ohne Content-Leak und Volumenexplosion.',
                        'en' => 'Analytics without content leak and volume explosion.',
                    ],
                    'how' => [
                        'de' => 'Allowlist fields; binary endpoints nie in Warehouse.',
                        'en' => 'Allowlist fields; never land binary endpoints in warehouse.',
                    ],
                ],
                [
                    'id' => 'dw-acl-export',
                    'area' => [
                        'de' => 'Security / Permissions',
                        'en' => 'Security / Permissions',
                    ],
                    'setting' => [
                        'de' => 'ACL Export als Principal-IDs',
                        'en' => 'ACL export as principal ids',
                    ],
                    'why' => [
                        'de' => 'Access KPIs ohne Klartext-Mails in Marts.',
                        'en' => 'Access KPIs without cleartext mails in marts.',
                    ],
                    'how' => [
                        'de' => 'Principal id + role; email nur RAW restricted.',
                        'en' => 'Principal id + role; email RAW restricted only.',
                    ],
                ],
                [
                    'id' => 'dw-retention',
                    'area' => [
                        'de' => 'Records / Retention',
                        'en' => 'Records / Retention',
                    ],
                    'setting' => [
                        'de' => 'Retention Labels und Legal Hold Metadata tracken',
                        'en' => 'Track retention labels and legal hold metadata',
                    ],
                    'why' => [
                        'de' => 'Compliance KPIs ohne Content-Bodies.',
                        'en' => 'Compliance KPIs without content bodies.',
                    ],
                    'how' => [
                        'de' => 'Hold/Retention flags + dates; no binary.',
                        'en' => 'Hold/retention flags + dates; no binary.',
                    ],
                ],
            ],
            'metadata' => [
                [
                    'id' => 'dw-object-model',
                    'kind' => [
                        'de' => 'Object / Document model',
                        'en' => 'Object / document model',
                    ],
                    'where' => [
                        'de' => 'DocuWare Platform REST API',
                        'en' => 'DocuWare Platform REST API',
                    ],
                    'how' => [
                        'de' => 'Entities/Properties inventarisieren; Select-Allowlist.',
                        'en' => 'Inventory entities/properties; select allowlist.',
                    ],
                    'useFor' => [
                        'de' => 'Extract design, PII tags, Join-Keys.',
                        'en' => 'Extract design, PII tags, join keys.',
                    ],
                    'watchouts' => [
                        'de' => 'Nie File/OCR Bodies selektieren.',
                        'en' => 'Never select file/OCR bodies.',
                    ],
                ],
                [
                    'id' => 'dw-acl-model',
                    'kind' => [
                        'de' => 'ACL / Permission model',
                        'en' => 'ACL / permission model',
                    ],
                    'where' => [
                        'de' => 'DocuWare permissions / ACLs',
                        'en' => 'DocuWare permissions / ACLs',
                    ],
                    'how' => [
                        'de' => 'Principal, Role, Object Scope dokumentieren.',
                        'en' => 'Document principal, role, object scope.',
                    ],
                    'useFor' => [
                        'de' => 'Access facts, orphan-ACL DQ.',
                        'en' => 'Access facts, orphan-ACL DQ.',
                    ],
                    'watchouts' => [
                        'de' => 'Inherited ACLs — SCD2 empfohlen.',
                        'en' => 'Inherited ACLs — SCD2 recommended.',
                    ],
                ],
                [
                    'id' => 'dw-version-meta',
                    'kind' => [
                        'de' => 'Version metadata',
                        'en' => 'Version metadata',
                    ],
                    'where' => [
                        'de' => 'DocuWare version history (meta only)',
                        'en' => 'DocuWare version history (meta only)',
                    ],
                    'how' => [
                        'de' => 'Version number, size, author id, timestamps.',
                        'en' => 'Version number, size, author id, timestamps.',
                    ],
                    'useFor' => [
                        'de' => 'Version counts, storage volume KPIs.',
                        'en' => 'Version counts, storage volume KPIs.',
                    ],
                    'watchouts' => [
                        'de' => 'Keine Binary Streams.',
                        'en' => 'No binary streams.',
                    ],
                ],
                [
                    'id' => 'dw-taxonomy',
                    'kind' => [
                        'de' => 'Folder / Category taxonomy',
                        'en' => 'Folder / category taxonomy',
                    ],
                    'where' => [
                        'de' => 'DocuWare folders / categories / cabinets',
                        'en' => 'DocuWare folders / categories / cabinets',
                    ],
                    'how' => [
                        'de' => 'Hierarchy und Codes exportieren.',
                        'en' => 'Export hierarchy and codes.',
                    ],
                    'useFor' => [
                        'de' => 'Taxonomy dims, path rollups.',
                        'en' => 'Taxonomy dims, path rollups.',
                    ],
                    'watchouts' => [
                        'de' => 'Renames — SCD2.',
                        'en' => 'Renames — SCD2.',
                    ],
                ],
            ],
        ],
        'tools' => [
            'dbt-dq-rules-generator',
            'dbt-dq-macro-generator',
            'dbt-dq-history-generator',
            'schema-yml-editor',
        ],
    ],
    'box' => [
        'quality' => [
            'dq' => [
                [
                    'id' => 'box-doc-no-owner',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Box: Dokument ohne Owner',
                        'en' => 'Box: document without owner',
                    ],
                    'problem' => [
                        'de' => 'Dokumente ohne Owner verzerren ACL- und Ownership-KPIs.',
                        'en' => 'Documents without owner distort ACL and ownership KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Create-Policy: Owner Pflicht; Sync orphan cleanup.',
                        'en' => 'Create policy: owner required; sync orphan cleanup.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Admins: Owner bei Create setzen.',
                        'en' => 'Admins: Set owner on create.',
                    ],
                    'checks' => [
                        'de' => 'file.owner_id IS NULL',
                        'en' => 'file.owner_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'Owner zuweisen oder archivieren.',
                        'en' => 'Assign owner or archive.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine; Unknown-owner dim.',
                        'en' => 'Quarantine; Unknown-owner dim.',
                    ],
                ],
                [
                    'id' => 'box-version-orphan',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'Version ohne Parent-Dokument',
                        'en' => 'Version without parent document',
                    ],
                    'problem' => [
                        'de' => 'Versionen ohne Dokument-Join brechen Storage- und Version-Counts.',
                        'en' => 'Versions without document join break storage and version counts.',
                    ],
                    'prevent' => [
                        'de' => 'Extract immer Parent-ID mitladen.',
                        'en' => 'Always load parent id in extract.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Version Natural Key inkl. Parent.',
                        'en' => 'Data: Version natural key includes parent.',
                    ],
                    'checks' => [
                        'de' => 'file_version.file_id IS NULL OR file_id NOT IN dim_file',
                        'en' => 'file_version.file_id IS NULL OR file_id NOT IN dim_file',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract; Tombstones für gelöschte Docs.',
                        'en' => 'Re-extract; tombstones for deleted docs.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Quarantine orphan versions.',
                        'en' => 'Quarantine orphan versions.',
                    ],
                ],
                [
                    'id' => 'box-acl-without-principal',
                    'priority' => 'high',
                    'title' => [
                        'de' => 'ACL ohne Principal',
                        'en' => 'ACL without principal',
                    ],
                    'problem' => [
                        'de' => 'Permission Grants ohne User/Group sind toter Security-Ballast.',
                        'en' => 'Permission grants without user/group are dead security ballast.',
                    ],
                    'prevent' => [
                        'de' => 'ACL Write: Principal Pflicht.',
                        'en' => 'ACL write: principal required.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Security: Keine leeren ACL-Zeilen.',
                        'en' => 'Security: No empty ACL rows.',
                    ],
                    'checks' => [
                        'de' => 'permission.principal_id IS NULL',
                        'en' => 'permission.principal_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'ACL bereinigen.',
                        'en' => 'Clean ACLs.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Flag orphan_acl; exclude from coverage KPIs.',
                        'en' => 'Flag orphan_acl; exclude from coverage KPIs.',
                    ],
                ],
                [
                    'id' => 'box-folder-path-null',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Dokument ohne Folder/Cabinet-Pfad',
                        'en' => 'Document without folder/cabinet path',
                    ],
                    'problem' => [
                        'de' => 'Dokumente ohne Ablage-Pfad verzerren Taxonomy-Rollups.',
                        'en' => 'Documents without filing path distort taxonomy rollups.',
                    ],
                    'prevent' => [
                        'de' => 'Default folder/cabinet bei Create.',
                        'en' => 'Default folder/cabinet on create.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Records: Ablage-Pfad ist Pflicht-Dim.',
                        'en' => 'Records: Filing path is a required dim.',
                    ],
                    'checks' => [
                        'de' => 'file.folder_id IS NULL',
                        'en' => 'file.folder_id IS NULL',
                    ],
                    'fixInSource' => [
                        'de' => 'In Default-Ablage verschieben.',
                        'en' => 'Move into default filing location.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown-folder dim.',
                        'en' => 'Unknown-folder dim.',
                    ],
                ],
                [
                    'id' => 'box-mime-missing',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Dokument ohne MIME/Content-Type',
                        'en' => 'Document without MIME/content type',
                    ],
                    'problem' => [
                        'de' => 'Fehlender Content-Type bricht Storage-by-Type KPIs.',
                        'en' => 'Missing content type breaks storage-by-type KPIs.',
                    ],
                    'prevent' => [
                        'de' => 'Upload: MIME detecten; Allowlist.',
                        'en' => 'Upload: detect MIME; allowlist.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Platform: MIME bei Ingest speichern.',
                        'en' => 'Platform: Persist MIME on ingest.',
                    ],
                    'checks' => [
                        'de' => 'file.mime_type IS NULL AND status = \'active\'',
                        'en' => 'file.mime_type IS NULL AND status = \'active\'',
                    ],
                    'fixInSource' => [
                        'de' => 'MIME nachziehen.',
                        'en' => 'Backfill MIME.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Unknown mime bucket.',
                        'en' => 'Unknown mime bucket.',
                    ],
                ],
                [
                    'id' => 'box-duplicate-doc-key',
                    'priority' => 'medium',
                    'title' => [
                        'de' => 'Doppelte Document-ID im Extract',
                        'en' => 'Duplicate document id in extract',
                    ],
                    'problem' => [
                        'de' => 'Doppelte Keys verzerren Document Counts.',
                        'en' => 'Duplicate keys distort document counts.',
                    ],
                    'prevent' => [
                        'de' => 'Idempotent load; Pagination prüfen.',
                        'en' => 'Idempotent load; check pagination.',
                    ],
                    'staffNeedToKnow' => [
                        'de' => 'Data: Natural Key = file_id.',
                        'en' => 'Data: Natural key = file_id.',
                    ],
                    'checks' => [
                        'de' => 'COUNT(*) GROUP BY file_id HAVING COUNT > 1',
                        'en' => 'COUNT(*) GROUP BY file_id HAVING COUNT > 1',
                    ],
                    'fixInSource' => [
                        'de' => 'Re-extract.',
                        'en' => 'Re-extract.',
                    ],
                    'fixInWarehouse' => [
                        'de' => 'Dedup by latest _loaded_at.',
                        'en' => 'Dedup by latest _loaded_at.',
                    ],
                ],
            ],
            'mdm' => [
                [
                    'id' => 'box-doc-dup',
                    'entity' => 'File',
                    'title' => [
                        'de' => 'Dokument-Duplikate / Rename',
                        'en' => 'Document duplicates / rename',
                    ],
                    'matchKeys' => [
                        'file_id',
                        'external_id',
                        'name+folder_path',
                        'content_hash_meta',
                    ],
                    'preventInSource' => [
                        'de' => 'Stable object id; track renames.',
                        'en' => 'Stable object id; track renames.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden file_id; name SCD2.',
                        'en' => 'Golden file_id; name SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktives Dokument mit neuester Version gewinnt.',
                        'en' => 'Active document with latest version wins.',
                    ],
                ],
                [
                    'id' => 'box-user-dup',
                    'entity' => 'User',
                    'title' => [
                        'de' => 'User-Duplikate',
                        'en' => 'User duplicates',
                    ],
                    'matchKeys' => [
                        'user_id',
                        'login',
                        'email (hashed)',
                        'external_idp_id',
                    ],
                    'preventInSource' => [
                        'de' => 'IdP as source of truth.',
                        'en' => 'IdP as source of truth.',
                    ],
                    'resolveInWarehouse' => [
                        'de' => 'Golden user id; login SCD2.',
                        'en' => 'Golden user id; login SCD2.',
                    ],
                    'survivorship' => [
                        'de' => 'Aktiver User mit neuester Activity gewinnt.',
                        'en' => 'Active user with latest activity wins.',
                    ],
                ],
            ],
            'productSettings' => [
                [
                    'id' => 'box-no-body-extract',
                    'area' => [
                        'de' => 'Extract / Integration',
                        'en' => 'Extract / Integration',
                    ],
                    'setting' => [
                        'de' => 'Nur Metadata/ACL/Versions-Meta — keine File Bodies',
                        'en' => 'Metadata/ACL/version meta only — no file bodies',
                    ],
                    'why' => [
                        'de' => 'Analytics ohne Content-Leak und Volumenexplosion.',
                        'en' => 'Analytics without content leak and volume explosion.',
                    ],
                    'how' => [
                        'de' => 'Allowlist fields; binary endpoints nie in Warehouse.',
                        'en' => 'Allowlist fields; never land binary endpoints in warehouse.',
                    ],
                ],
                [
                    'id' => 'box-acl-export',
                    'area' => [
                        'de' => 'Security / Permissions',
                        'en' => 'Security / Permissions',
                    ],
                    'setting' => [
                        'de' => 'ACL Export als Principal-IDs',
                        'en' => 'ACL export as principal ids',
                    ],
                    'why' => [
                        'de' => 'Access KPIs ohne Klartext-Mails in Marts.',
                        'en' => 'Access KPIs without cleartext mails in marts.',
                    ],
                    'how' => [
                        'de' => 'Principal id + role; email nur RAW restricted.',
                        'en' => 'Principal id + role; email RAW restricted only.',
                    ],
                ],
                [
                    'id' => 'box-retention',
                    'area' => [
                        'de' => 'Records / Retention',
                        'en' => 'Records / Retention',
                    ],
                    'setting' => [
                        'de' => 'Retention Labels und Legal Hold Metadata tracken',
                        'en' => 'Track retention labels and legal hold metadata',
                    ],
                    'why' => [
                        'de' => 'Compliance KPIs ohne Content-Bodies.',
                        'en' => 'Compliance KPIs without content bodies.',
                    ],
                    'how' => [
                        'de' => 'Hold/Retention flags + dates; no binary.',
                        'en' => 'Hold/retention flags + dates; no binary.',
                    ],
                ],
            ],
            'metadata' => [
                [
                    'id' => 'box-object-model',
                    'kind' => [
                        'de' => 'Object / Document model',
                        'en' => 'Object / document model',
                    ],
                    'where' => [
                        'de' => 'Box Content API / Events API',
                        'en' => 'Box Content API / Events API',
                    ],
                    'how' => [
                        'de' => 'Entities/Properties inventarisieren; Select-Allowlist.',
                        'en' => 'Inventory entities/properties; select allowlist.',
                    ],
                    'useFor' => [
                        'de' => 'Extract design, PII tags, Join-Keys.',
                        'en' => 'Extract design, PII tags, join keys.',
                    ],
                    'watchouts' => [
                        'de' => 'Nie File/OCR Bodies selektieren.',
                        'en' => 'Never select file/OCR bodies.',
                    ],
                ],
                [
                    'id' => 'box-acl-model',
                    'kind' => [
                        'de' => 'ACL / Permission model',
                        'en' => 'ACL / permission model',
                    ],
                    'where' => [
                        'de' => 'Box permissions / ACLs',
                        'en' => 'Box permissions / ACLs',
                    ],
                    'how' => [
                        'de' => 'Principal, Role, Object Scope dokumentieren.',
                        'en' => 'Document principal, role, object scope.',
                    ],
                    'useFor' => [
                        'de' => 'Access facts, orphan-ACL DQ.',
                        'en' => 'Access facts, orphan-ACL DQ.',
                    ],
                    'watchouts' => [
                        'de' => 'Inherited ACLs — SCD2 empfohlen.',
                        'en' => 'Inherited ACLs — SCD2 recommended.',
                    ],
                ],
                [
                    'id' => 'box-version-meta',
                    'kind' => [
                        'de' => 'Version metadata',
                        'en' => 'Version metadata',
                    ],
                    'where' => [
                        'de' => 'Box version history (meta only)',
                        'en' => 'Box version history (meta only)',
                    ],
                    'how' => [
                        'de' => 'Version number, size, author id, timestamps.',
                        'en' => 'Version number, size, author id, timestamps.',
                    ],
                    'useFor' => [
                        'de' => 'Version counts, storage volume KPIs.',
                        'en' => 'Version counts, storage volume KPIs.',
                    ],
                    'watchouts' => [
                        'de' => 'Keine Binary Streams.',
                        'en' => 'No binary streams.',
                    ],
                ],
                [
                    'id' => 'box-taxonomy',
                    'kind' => [
                        'de' => 'Folder / Category taxonomy',
                        'en' => 'Folder / category taxonomy',
                    ],
                    'where' => [
                        'de' => 'Box folders / categories / cabinets',
                        'en' => 'Box folders / categories / cabinets',
                    ],
                    'how' => [
                        'de' => 'Hierarchy und Codes exportieren.',
                        'en' => 'Export hierarchy and codes.',
                    ],
                    'useFor' => [
                        'de' => 'Taxonomy dims, path rollups.',
                        'en' => 'Taxonomy dims, path rollups.',
                    ],
                    'watchouts' => [
                        'de' => 'Renames — SCD2.',
                        'en' => 'Renames — SCD2.',
                    ],
                ],
            ],
        ],
        'tools' => [
            'dbt-dq-rules-generator',
            'dbt-dq-macro-generator',
            'dbt-dq-history-generator',
            'schema-yml-editor',
        ],
    ],
];
