<?php

/**
 * Glossary Hub — shared governance vocabulary with links into stories and tools.
 *
 * Readable definitions, not a PureView JSON generator.
 */
return [
    'categories' => [
        'roles' => ['de' => 'Rollen', 'en' => 'Roles'],
        'data' => ['de' => 'Daten & Produkte', 'en' => 'Data & products'],
        'quality' => ['de' => 'Qualität', 'en' => 'Quality'],
        'privacy' => ['de' => 'Privacy & Schutz', 'en' => 'Privacy & protection'],
        'metadata' => ['de' => 'Metadaten', 'en' => 'Metadata'],
        'process' => ['de' => 'Prozess', 'en' => 'Process'],
    ],

    'terms' => [
        [
            'id' => 'data-steward',
            'order' => 10,
            'category' => 'roles',
            'term' => ['de' => 'Data Steward', 'en' => 'Data Steward'],
            'aliases' => ['Steward', 'Data Stewardship'],
            'definition' => [
                'de' => 'Operative Verantwortung für Definition, Qualität und Nutzung eines Datenbereichs — nicht nur Dokumentation.',
                'en' => 'Operational ownership for definition, quality, and use of a data domain — not documentation alone.',
            ],
            'related' => [
                ['type' => 'glossary', 'id' => 'data-owner', 'label' => ['de' => 'Data Owner', 'en' => 'Data Owner']],
                ['type' => 'glossary', 'id' => 'data-architect', 'label' => ['de' => 'Data Architect', 'en' => 'Data Architect']],
                ['type' => 'story', 'id' => 'stewardship-capacity', 'label' => ['de' => 'Stewardship staffen', 'en' => 'Staffing stewardship']],
                ['type' => 'story', 'id' => 'data-ownership-stewardship', 'label' => ['de' => 'Ownership & Stewardship', 'en' => 'Ownership & stewardship']],
                ['type' => 'series', 'id' => 'roles-hub', 'label' => ['de' => 'Roles and Decision Rights', 'en' => 'Roles and decision rights']],
                ['type' => 'path', 'id' => 'governance-foundations', 'label' => ['de' => 'Governance Foundations', 'en' => 'Governance foundations']],
                ['type' => 'route', 'route' => 'roles.index', 'label' => ['de' => 'Roles Hub', 'en' => 'Roles hub']],
            ],
        ],
        [
            'id' => 'data-owner',
            'order' => 20,
            'category' => 'roles',
            'term' => ['de' => 'Data Owner', 'en' => 'Data Owner'],
            'aliases' => ['Owner', 'Business Owner'],
            'definition' => [
                'de' => 'Fachliche Entscheidungsinstanz für Zweck, Zugriffsregeln und Freigaben eines Datenprodukts oder einer Domäne.',
                'en' => 'Business decision-maker for purpose, access rules, and approvals of a data product or domain.',
            ],
            'related' => [
                ['type' => 'glossary', 'id' => 'data-steward', 'label' => ['de' => 'Data Steward', 'en' => 'Data Steward']],
                ['type' => 'glossary', 'id' => 'data-consumer', 'label' => ['de' => 'Data Consumer', 'en' => 'Data Consumer']],
                ['type' => 'glossary', 'id' => 'raci', 'label' => ['de' => 'RACI', 'en' => 'RACI']],
                ['type' => 'story', 'id' => 'data-product-owner-vs-data-owner', 'label' => ['de' => 'Product Owner vs Owner vs Steward', 'en' => 'Product Owner vs Owner vs Steward']],
                ['type' => 'story', 'id' => 'raci-for-data-governance', 'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance']],
                ['type' => 'route', 'route' => 'roles.index', 'label' => ['de' => 'Roles Hub', 'en' => 'Roles hub']],
            ],
        ],
        [
            'id' => 'data-architect',
            'order' => 25,
            'category' => 'roles',
            'term' => ['de' => 'Data Architect', 'en' => 'Data Architect'],
            'aliases' => ['Architect', 'Analytics Architect', 'Solution Architect (Data)'],
            'definition' => [
                'de' => 'Verantwortet Grain, Modellkonsistenz und Contracts — damit Domänen und Marts zusammenpassen, ohne Owner oder Steward zu ersetzen.',
                'en' => 'Owns grain, model consistency, and contracts — so domains and marts fit together without replacing owner or steward.',
            ],
            'related' => [
                ['type' => 'glossary', 'id' => 'grain', 'label' => ['de' => 'Grain', 'en' => 'Grain']],
                ['type' => 'glossary', 'id' => 'data-contract', 'label' => ['de' => 'Data Contract', 'en' => 'Data Contract']],
                ['type' => 'glossary', 'id' => 'data-steward', 'label' => ['de' => 'Data Steward', 'en' => 'Data Steward']],
                ['type' => 'story', 'id' => 'data-architect-role', 'label' => ['de' => 'Die Rolle Data Architect', 'en' => 'The Data Architect role']],
                ['type' => 'story', 'id' => 'raci-for-data-governance', 'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance']],
                ['type' => 'path', 'id' => 'modernize-warehouse', 'label' => ['de' => 'Warehouse modernisieren', 'en' => 'Modernize the warehouse']],
                ['type' => 'route', 'route' => 'roles.index', 'label' => ['de' => 'Roles Hub', 'en' => 'Roles hub']],
            ],
        ],
        [
            'id' => 'data-custodian',
            'order' => 26,
            'category' => 'roles',
            'term' => ['de' => 'Data Custodian', 'en' => 'Data Custodian'],
            'aliases' => ['Custodian', 'Technical Custodian'],
            'definition' => [
                'de' => 'Technische Obhut über Systeme und Speicherorte — Zugriffspflege, Backups, Laufzeit — meist Platform/IT, nicht fachliche Definition.',
                'en' => 'Technical custody of systems and storage — access upkeep, backups, runtime — usually platform/IT, not business definition.',
            ],
            'related' => [
                ['type' => 'glossary', 'id' => 'data-steward', 'label' => ['de' => 'Data Steward', 'en' => 'Data Steward']],
                ['type' => 'glossary', 'id' => 'data-owner', 'label' => ['de' => 'Data Owner', 'en' => 'Data Owner']],
                ['type' => 'story', 'id' => 'data-ownership-stewardship', 'label' => ['de' => 'Ownership & Stewardship', 'en' => 'Ownership & stewardship']],
                ['type' => 'story', 'id' => 'raci-for-data-governance', 'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance']],
                ['type' => 'route', 'route' => 'roles.index', 'label' => ['de' => 'Roles Hub', 'en' => 'Roles hub']],
            ],
        ],
        [
            'id' => 'data-consumer',
            'order' => 27,
            'category' => 'roles',
            'term' => ['de' => 'Data Consumer', 'en' => 'Data Consumer'],
            'aliases' => ['Consumer', 'Analyst', 'Report User'],
            'definition' => [
                'de' => 'Nutzt Datenprodukte für Entscheidungen oder Reports — meldet Qualitätsprobleme, entscheidet aber nicht allein über Definition und Zugriff.',
                'en' => 'Uses data products for decisions or reports — raises quality issues but does not alone decide definition and access.',
            ],
            'related' => [
                ['type' => 'glossary', 'id' => 'data-product', 'label' => ['de' => 'Data Product', 'en' => 'Data Product']],
                ['type' => 'glossary', 'id' => 'data-owner', 'label' => ['de' => 'Data Owner', 'en' => 'Data Owner']],
                ['type' => 'story', 'id' => 'data-product-owner-vs-data-owner', 'label' => ['de' => 'Product Owner vs Owner vs Steward', 'en' => 'Product Owner vs Owner vs Steward']],
                ['type' => 'story', 'id' => 'one-data-product-multiple-consumers', 'label' => ['de' => 'Ein Data Product, viele Consumer', 'en' => 'One data product, many consumers']],
                ['type' => 'route', 'route' => 'roles.index', 'label' => ['de' => 'Roles Hub', 'en' => 'Roles hub']],
            ],
        ],
        [
            'id' => 'data-product',
            'order' => 30,
            'category' => 'data',
            'term' => ['de' => 'Data Product', 'en' => 'Data Product'],
            'aliases' => ['Datenprodukt', 'Product Thinking'],
            'definition' => [
                'de' => 'Konsumierbares, versioniertes Datenangebot mit klarer Zielgruppe, Verträgen (SLA/SLO), Ownership und dokumentierter Qualität.',
                'en' => 'Consumable, versioned data offering with a clear audience, contracts (SLA/SLO), ownership, and documented quality.',
            ],
            'related' => [
                ['type' => 'glossary', 'id' => 'grain', 'label' => ['de' => 'Grain', 'en' => 'Grain']],
                ['type' => 'story', 'id' => 'data-product-owner-vs-data-owner', 'label' => ['de' => 'Product Owner vs Owner vs Steward', 'en' => 'Product Owner vs Owner vs Steward']],
                ['type' => 'series', 'id' => 'building-modern-data-warehouse', 'label' => ['de' => 'Modern Data Warehouse', 'en' => 'Modern data warehouse']],
                ['type' => 'route', 'route' => 'roles.index', 'label' => ['de' => 'Roles Hub', 'en' => 'Roles hub']],
            ],
        ],
        [
            'id' => 'grain',
            'order' => 40,
            'category' => 'data',
            'term' => ['de' => 'Grain', 'en' => 'Grain'],
            'aliases' => ['Körnung', 'Granularity'],
            'definition' => [
                'de' => 'Die kleinste fachliche Aussageeinheit einer Tabelle oder eines Marts (z. B. eine Bestellung, ein Tag, ein Vertrag).',
                'en' => 'The smallest business statement unit of a table or mart (e.g. one order, one day, one contract).',
            ],
            'related' => [
                ['type' => 'glossary', 'id' => 'data-product', 'label' => ['de' => 'Data Product', 'en' => 'Data Product']],
                ['type' => 'route', 'route' => 'suppliers.index', 'label' => ['de' => 'Supplier Library', 'en' => 'Supplier library']],
            ],
        ],
        [
            'id' => 'lineage',
            'order' => 50,
            'category' => 'metadata',
            'term' => ['de' => 'Lineage', 'en' => 'Lineage'],
            'aliases' => ['Data Lineage', 'Datenherkunft', 'Column Lineage'],
            'definition' => [
                'de' => 'Nachvollziehbare Herkunft und Transformation von Daten — von Quelle über Pipelines bis zu Reports und Löschpfaden.',
                'en' => 'Traceable origin and transformation of data — from source through pipelines to reports and deletion paths.',
            ],
            'related' => [
                ['type' => 'story', 'id' => 'propagating-pii-metadata-across-data-warehouses', 'label' => ['de' => 'PII-Metadaten propagieren', 'en' => 'Propagating PII metadata']],
                ['type' => 'glossary', 'id' => 'dsdr', 'label' => ['de' => 'DSDR', 'en' => 'DSDR']],
            ],
        ],
        [
            'id' => 'data-catalog',
            'order' => 60,
            'category' => 'metadata',
            'term' => ['de' => 'Data Catalog', 'en' => 'Data Catalog'],
            'aliases' => ['Katalog', 'Business Glossary', 'Unified Catalog'],
            'definition' => [
                'de' => 'Auffindbare Inventar- und Bedeutungsschicht für Assets, Begriffe, Owner und Policies — Grundlage für Discovery und Governance.',
                'en' => 'Discoverable inventory and meaning layer for assets, terms, owners, and policies — the base for discovery and governance.',
            ],
            'related' => [
                ['type' => 'series', 'id' => 'metadata-deep-dive', 'label' => ['de' => 'MetaData Deep Dive', 'en' => 'MetaData deep dive']],
                ['type' => 'tool', 'route' => 'tools.pureview-glossary-generator', 'label' => ['de' => 'PureView Glossary Generator', 'en' => 'PureView glossary generator']],
            ],
        ],
        [
            'id' => 'pii',
            'order' => 70,
            'category' => 'privacy',
            'term' => ['de' => 'PII', 'en' => 'PII'],
            'aliases' => ['Personenbezogene Daten', 'Personal Data', 'SPI'],
            'definition' => [
                'de' => 'Personenbezogene oder personenbeziehbare Daten. Brauchen Klassifikation, Masking, Zweckbindung und nachweisbare Lösch-/Sperrpfade.',
                'en' => 'Personally identifiable or linkable data. Needs classification, masking, purpose binding, and proven deletion/restriction paths.',
            ],
            'related' => [
                ['type' => 'story', 'id' => 'pii-privacy-governance', 'label' => ['de' => 'PII Privacy Governance', 'en' => 'PII privacy governance']],
                ['type' => 'path', 'id' => 'pii-in-five-steps', 'label' => ['de' => 'PII in 5 Schritten', 'en' => 'PII in 5 steps']],
                ['type' => 'compliance', 'id' => 'gdpr', 'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR']],
            ],
        ],
        [
            'id' => 'dsdr',
            'order' => 80,
            'category' => 'privacy',
            'term' => ['de' => 'DSDR', 'en' => 'DSDR'],
            'aliases' => ['Data Subject Deletion Request', 'Löschanfrage', 'Right to Erasure'],
            'definition' => [
                'de' => 'Prozess und technische Fähigkeit, Betroffenenrechte (Löschen/Sperren) über Systeme und Lineage hinweg auszuführen und zu belegen.',
                'en' => 'Process and technical ability to execute and evidence data-subject rights (erase/restrict) across systems and lineage.',
            ],
            'related' => [
                ['type' => 'story', 'id' => 'dsdr-governance', 'label' => ['de' => 'DSDR Governance', 'en' => 'DSDR governance']],
                ['type' => 'tool', 'route' => 'tools.pii-dsdr-readiness-checker', 'label' => ['de' => 'PII/DSDR Readiness', 'en' => 'PII/DSDR readiness']],
                ['type' => 'glossary', 'id' => 'lineage', 'label' => ['de' => 'Lineage', 'en' => 'Lineage']],
            ],
        ],
        [
            'id' => 'data-quality',
            'order' => 90,
            'category' => 'quality',
            'term' => ['de' => 'Data Quality', 'en' => 'Data Quality'],
            'aliases' => ['DQ', 'Datenqualität', 'Observability'],
            'definition' => [
                'de' => 'Messbare Eignung von Daten für einen Zweck — Regeln, Ownership, Monitoring und Remediation statt einmaliger Checks.',
                'en' => 'Measurable fitness of data for a purpose — rules, ownership, monitoring, and remediation instead of one-off checks.',
            ],
            'related' => [
                ['type' => 'series', 'id' => 'operational-data-quality', 'label' => ['de' => 'Operational Data Quality', 'en' => 'Operational data quality']],
                ['type' => 'path', 'id' => 'dq-with-dbt', 'label' => ['de' => 'DQ mit dbt', 'en' => 'DQ with dbt']],
            ],
        ],
        [
            'id' => 'retention',
            'order' => 100,
            'category' => 'privacy',
            'term' => ['de' => 'Retention', 'en' => 'Retention'],
            'aliases' => ['Aufbewahrung', 'Speicherbegrenzung', 'Archive'],
            'definition' => [
                'de' => 'Regeln, wie lange Daten aktiv oder archiviert bleiben dürfen — getrennt von Backup und analytischen Marts.',
                'en' => 'Rules for how long data may stay active or archived — separated from backup and analytical marts.',
            ],
            'related' => [
                ['type' => 'compliance', 'id' => 'gdpr', 'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR']],
                ['type' => 'glossary', 'id' => 'dsdr', 'label' => ['de' => 'DSDR', 'en' => 'DSDR']],
            ],
        ],
        [
            'id' => 'metadata',
            'order' => 110,
            'category' => 'metadata',
            'term' => ['de' => 'Metadata', 'en' => 'Metadata'],
            'aliases' => ['Metadaten', 'Active Metadata', 'Technical Metadata'],
            'definition' => [
                'de' => 'Daten über Daten: Schema, Bedeutung, Owner, Klassifikation, Lineage und Policies — der Steuerungshebel der Governance.',
                'en' => 'Data about data: schema, meaning, owners, classification, lineage, and policies — the control lever of governance.',
            ],
            'related' => [
                ['type' => 'series', 'id' => 'metadata-deep-dive', 'label' => ['de' => 'MetaData Deep Dive', 'en' => 'MetaData deep dive']],
                ['type' => 'story', 'id' => 'metadata-driven-governance-with-dbt-meta', 'label' => ['de' => 'dbt meta Governance', 'en' => 'dbt meta governance']],
            ],
        ],
        [
            'id' => 'raci',
            'order' => 120,
            'category' => 'process',
            'term' => ['de' => 'RACI', 'en' => 'RACI'],
            'aliases' => ['Responsible', 'Accountable', 'Consulted', 'Informed'],
            'definition' => [
                'de' => 'Rollenmatrix für Entscheidungen: wer ausführt (R), wer verantwortet (A), wer berät (C), wer informiert wird (I).',
                'en' => 'Role matrix for decisions: who executes (R), who owns (A), who is consulted (C), who is informed (I).',
            ],
            'related' => [
                ['type' => 'glossary', 'id' => 'data-owner', 'label' => ['de' => 'Data Owner', 'en' => 'Data Owner']],
                ['type' => 'glossary', 'id' => 'data-steward', 'label' => ['de' => 'Data Steward', 'en' => 'Data Steward']],
                ['type' => 'story', 'id' => 'raci-for-data-governance', 'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance']],
                ['type' => 'tool', 'route' => 'tools.stakeholder-matrix', 'label' => ['de' => 'Stakeholder & RACI Matrix', 'en' => 'Stakeholder & RACI matrix']],
                ['type' => 'route', 'route' => 'roles.index', 'label' => ['de' => 'Roles Hub', 'en' => 'Roles hub']],
                ['type' => 'series', 'id' => 'roles-hub', 'label' => ['de' => 'Roles and Decision Rights', 'en' => 'Roles and decision rights']],
            ],
        ],
        [
            'id' => 'data-contract',
            'order' => 130,
            'category' => 'quality',
            'term' => ['de' => 'Data Contract', 'en' => 'Data Contract'],
            'aliases' => ['Datenvertrag', 'Interface Contract'],
            'definition' => [
                'de' => 'Vereinbarung zwischen Produzent und Konsument über Schema, Semantik, SLAs und Breaking-Change-Regeln.',
                'en' => 'Agreement between producer and consumer on schema, semantics, SLAs, and breaking-change rules.',
            ],
            'related' => [
                ['type' => 'glossary', 'id' => 'data-product', 'label' => ['de' => 'Data Product', 'en' => 'Data Product']],
                ['type' => 'glossary', 'id' => 'data-quality', 'label' => ['de' => 'Data Quality', 'en' => 'Data Quality']],
            ],
        ],
        [
            'id' => 'masking',
            'order' => 140,
            'category' => 'privacy',
            'term' => ['de' => 'Masking', 'en' => 'Masking'],
            'aliases' => ['Dynamic Masking', 'Anonymisierung', 'Pseudonymisierung'],
            'definition' => [
                'de' => 'Technik, sensible Werte für unberechtigte Rollen zu verbergen oder zu ersetzen — idealerweise policy-gesteuert und lineage-bewusst.',
                'en' => 'Technique to hide or replace sensitive values for unauthorized roles — ideally policy-driven and lineage-aware.',
            ],
            'related' => [
                ['type' => 'story', 'id' => 'snowflake-masking-policies-qlik-section-access', 'label' => ['de' => 'Masking & Section Access', 'en' => 'Masking & section access']],
                ['type' => 'tool', 'route' => 'tools.pii-policy-generator', 'label' => ['de' => 'PII Policy Generator', 'en' => 'PII policy generator']],
            ],
        ],
        [
            'id' => 'kpi-governance',
            'order' => 150,
            'category' => 'process',
            'term' => ['de' => 'KPI Governance', 'en' => 'KPI Governance'],
            'aliases' => ['Metric Definition', 'Single Source of Truth'],
            'definition' => [
                'de' => 'Klare Definition, Owner und Änderungsprozess für Kennzahlen — verhindert widersprüchliche Zahlen in Tools und Meetings.',
                'en' => 'Clear definition, owner, and change process for metrics — prevents conflicting numbers across tools and meetings.',
            ],
            'related' => [
                ['type' => 'tool', 'route' => 'tools.kpi-definition', 'label' => ['de' => 'KPI Definition', 'en' => 'KPI definition']],
                ['type' => 'series', 'id' => 'governance-pillars', 'label' => ['de' => '8 Säulen', 'en' => '8 pillars']],
            ],
        ],
    ],
];
