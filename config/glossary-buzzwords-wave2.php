<?php

/**
 * Glossary buzzword wave 2 — quiz-friendly terms (distinct defs, clear categories).
 *
 * @return list<array<string, mixed>>
 */
return [
    [
        'id' => 'chief-data-officer',
        'order' => 51,
        'category' => 'roles',
        'term' => ['de' => 'Chief Data Officer', 'en' => 'Chief Data Officer'],
        'aliases' => ['CDO', 'Chief Data & Analytics Officer'],
        'definition' => [
            'de' => 'Führungskraft für Datenstrategie, Governance und Wertschöpfung — setzt Prioritäten und Entscheidungsrechte, schreibt nicht jedes KPI selbst.',
            'en' => 'Executive for data strategy, governance, and value — sets priorities and decision rights, does not write every KPI personally.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'data-owner', 'label' => ['de' => 'Data Owner', 'en' => 'Data Owner']],
            ['type' => 'glossary', 'id' => 'governance-lead', 'label' => ['de' => 'Governance Lead', 'en' => 'Governance Lead']],
        ],
    ],
    [
        'id' => 'analytics-translator',
        'order' => 52,
        'category' => 'roles',
        'term' => ['de' => 'Analytics Translator', 'en' => 'Analytics Translator'],
        'aliases' => ['Business Translator', 'Insight Translator'],
        'definition' => [
            'de' => 'Übersetzt Fachfragen in analytische Problemstellungen und Ergebnisse zurück in Entscheidungen — Brücke zwischen Business und Analytics.',
            'en' => 'Translates business questions into analytical problems and results back into decisions — bridge between business and analytics.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'data-consumer', 'label' => ['de' => 'Data Consumer', 'en' => 'Data Consumer']],
            ['type' => 'glossary', 'id' => 'data-product-owner', 'label' => ['de' => 'Data Product Owner', 'en' => 'Data Product Owner']],
        ],
    ],
    [
        'id' => 'ml-engineer',
        'order' => 53,
        'category' => 'roles',
        'term' => ['de' => 'ML Engineer', 'en' => 'ML Engineer'],
        'aliases' => ['Machine Learning Engineer', 'MLOps Engineer'],
        'definition' => [
            'de' => 'Bringt Modelle in Produktion: Serving, Monitoring, Retraining-Pipelines — nicht nur Notebook-Experimente.',
            'en' => 'Puts models into production: serving, monitoring, retraining pipelines — not notebook experiments alone.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'mlops', 'label' => ['de' => 'MLOps', 'en' => 'MLOps']],
            ['type' => 'glossary', 'id' => 'model-registry', 'label' => ['de' => 'Model Registry', 'en' => 'Model Registry']],
            ['type' => 'glossary', 'id' => 'data-drift', 'label' => ['de' => 'Data Drift', 'en' => 'Data Drift']],
        ],
    ],
    [
        'id' => 'data-as-a-product',
        'order' => 240,
        'category' => 'data',
        'term' => ['de' => 'Data as a Product', 'en' => 'Data as a Product'],
        'aliases' => ['Daten als Produkt', 'Product Thinking for Data'],
        'definition' => [
            'de' => 'Datensätze werden wie Produkte geführt: klarer Owner, SLA, Versionierung und Nutzerversprechen — kein Nebenprodukt der Ops.',
            'en' => 'Datasets are run like products: clear owner, SLA, versioning, and user promise — not an ops by-product.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'data-product', 'label' => ['de' => 'Data Product', 'en' => 'Data Product']],
            ['type' => 'glossary', 'id' => 'data-contract', 'label' => ['de' => 'Data Contract', 'en' => 'Data Contract']],
        ],
    ],
    [
        'id' => 'data-sla',
        'order' => 241,
        'category' => 'data',
        'term' => ['de' => 'Data SLA', 'en' => 'Data SLA'],
        'aliases' => ['Daten-SLA', 'Dataset SLA'],
        'definition' => [
            'de' => 'Messbare Zusicherung für ein Datenprodukt (Frischheit, Verfügbarkeit, Korrektheit) — ohne SLA bleibt „wichtig“ nur Meinung.',
            'en' => 'Measurable promise for a data product (freshness, availability, correctness) — without an SLA, “important” stays opinion.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'sla-slo', 'label' => ['de' => 'SLA / SLO', 'en' => 'SLA / SLO']],
            ['type' => 'glossary', 'id' => 'freshness', 'label' => ['de' => 'Freshness', 'en' => 'Freshness']],
            ['type' => 'glossary', 'id' => 'data-product', 'label' => ['de' => 'Data Product', 'en' => 'Data Product']],
        ],
    ],
    [
        'id' => 'dataset-versioning',
        'order' => 242,
        'category' => 'data',
        'term' => ['de' => 'Dataset Versioning', 'en' => 'Dataset Versioning'],
        'aliases' => ['Datenversionierung', 'Dataset Revisions'],
        'definition' => [
            'de' => 'Nachvollziehbare Versionen eines Datensatzes (Schema + Inhalt) — ermöglicht Reproduzierbarkeit und Rollback.',
            'en' => 'Traceable versions of a dataset (schema + content) — enables reproducibility and rollback.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'time-travel', 'label' => ['de' => 'Time Travel', 'en' => 'Time Travel']],
            ['type' => 'glossary', 'id' => 'data-contract', 'label' => ['de' => 'Data Contract', 'en' => 'Data Contract']],
        ],
    ],
    [
        'id' => 'semantic-contract',
        'order' => 243,
        'category' => 'data',
        'term' => ['de' => 'Semantic Contract', 'en' => 'Semantic Contract'],
        'aliases' => ['Semantikvertrag', 'Metric Contract'],
        'definition' => [
            'de' => 'Verbindliche Vereinbarung über Bedeutung und Berechnung von Metriken/Entitäten — nicht nur Spaltentypen.',
            'en' => 'Binding agreement on meaning and calculation of metrics/entities — not column types alone.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'data-contract', 'label' => ['de' => 'Data Contract', 'en' => 'Data Contract']],
            ['type' => 'glossary', 'id' => 'semantic-layer', 'label' => ['de' => 'Semantic Layer', 'en' => 'Semantic Layer']],
            ['type' => 'glossary', 'id' => 'business-glossary', 'label' => ['de' => 'Business Glossary', 'en' => 'Business Glossary']],
        ],
    ],
    [
        'id' => 'zero-etl',
        'order' => 450,
        'category' => 'architecture',
        'term' => ['de' => 'Zero-ETL', 'en' => 'Zero-ETL'],
        'aliases' => ['Zero ETL Integration', 'Near-Zero ETL'],
        'definition' => [
            'de' => 'Architekturversprechen: Analyse nah an der Quelle ohne klassische Batch-ETL-Kopien — Governance und Latenz bleiben trotzdem Thema.',
            'en' => 'Architecture promise: analytics close to the source without classic batch ETL copies — governance and latency still matter.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'etl', 'label' => ['de' => 'ETL', 'en' => 'ETL']],
            ['type' => 'glossary', 'id' => 'cdc', 'label' => ['de' => 'CDC', 'en' => 'CDC']],
            ['type' => 'glossary', 'id' => 'lakehouse', 'label' => ['de' => 'Lakehouse', 'en' => 'Lakehouse']],
        ],
    ],
    [
        'id' => 'data-virtualization',
        'order' => 451,
        'category' => 'architecture',
        'term' => ['de' => 'Data Virtualization', 'en' => 'Data Virtualization'],
        'aliases' => ['Datenvirtualisierung', 'Logical Data Warehouse'],
        'definition' => [
            'de' => 'Abfragen über verteilte Quellen ohne physische Konsolidierung — schnell integriert, oft teuer bei Performance und Lineage.',
            'en' => 'Queries across distributed sources without physical consolidation — fast to integrate, often costly for performance and lineage.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'data-mesh', 'label' => ['de' => 'Data Mesh', 'en' => 'Data Mesh']],
            ['type' => 'glossary', 'id' => 'semantic-layer', 'label' => ['de' => 'Semantic Layer', 'en' => 'Semantic Layer']],
        ],
    ],
    [
        'id' => 'lambda-architecture',
        'order' => 452,
        'category' => 'architecture',
        'term' => ['de' => 'Lambda Architecture', 'en' => 'Lambda Architecture'],
        'aliases' => ['Lambda-Architektur'],
        'definition' => [
            'de' => 'Parallele Batch- und Speed-Layer für Korrektheit plus Niedriglatenz — doppelte Logik ist der klassische Preis.',
            'en' => 'Parallel batch and speed layers for correctness plus low latency — duplicated logic is the classic cost.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'kappa-architecture', 'label' => ['de' => 'Kappa Architecture', 'en' => 'Kappa Architecture']],
            ['type' => 'glossary', 'id' => 'streaming', 'label' => ['de' => 'Streaming', 'en' => 'Streaming']],
        ],
    ],
    [
        'id' => 'kappa-architecture',
        'order' => 453,
        'category' => 'architecture',
        'term' => ['de' => 'Kappa Architecture', 'en' => 'Kappa Architecture'],
        'aliases' => ['Kappa-Architektur'],
        'definition' => [
            'de' => 'Eine Stream-Pipeline für Realtime und Replay statt getrennter Batch/Speed-Layer — vereinfacht Code, braucht robustes Event-Log.',
            'en' => 'One stream pipeline for realtime and replay instead of separate batch/speed layers — simplifies code, needs a robust event log.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'lambda-architecture', 'label' => ['de' => 'Lambda Architecture', 'en' => 'Lambda Architecture']],
            ['type' => 'glossary', 'id' => 'event-sourcing', 'label' => ['de' => 'Event Sourcing', 'en' => 'Event Sourcing']],
        ],
    ],
    [
        'id' => 'event-sourcing',
        'order' => 454,
        'category' => 'architecture',
        'term' => ['de' => 'Event Sourcing', 'en' => 'Event Sourcing'],
        'aliases' => ['Event-Sourcing'],
        'definition' => [
            'de' => 'Zustand entsteht aus einer unveränderlichen Ereignisfolge — Audit-stark, aber Replay und Schema-Evolution sind anspruchsvoll.',
            'en' => 'State is derived from an immutable event sequence — strong for audit, but replay and schema evolution are demanding.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'cqrs', 'label' => ['de' => 'CQRS', 'en' => 'CQRS']],
            ['type' => 'glossary', 'id' => 'kappa-architecture', 'label' => ['de' => 'Kappa Architecture', 'en' => 'Kappa Architecture']],
        ],
    ],
    [
        'id' => 'slowly-changing-dimension',
        'order' => 580,
        'category' => 'modeling',
        'term' => ['de' => 'Slowly Changing Dimension', 'en' => 'Slowly Changing Dimension'],
        'aliases' => ['SCD', 'Langsam ändernde Dimension'],
        'definition' => [
            'de' => 'Muster für Dimensionsänderungen über Zeit (Typ 1 überschreibt, Typ 2 historisiert) — ohne Strategie lügt jede Trendanalyse.',
            'en' => 'Pattern for dimension changes over time (Type 1 overwrites, Type 2 historizes) — without a strategy every trend analysis lies.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'scd2', 'label' => ['de' => 'SCD Type 2', 'en' => 'SCD Type 2']],
            ['type' => 'glossary', 'id' => 'scd-type-1', 'label' => ['de' => 'SCD Type 1', 'en' => 'SCD Type 1']],
            ['type' => 'glossary', 'id' => 'kimball', 'label' => ['de' => 'Kimball', 'en' => 'Kimball']],
        ],
    ],
    [
        'id' => 'field-parameter',
        'order' => 640,
        'category' => 'bi',
        'term' => ['de' => 'Field Parameter', 'en' => 'Field Parameter'],
        'aliases' => ['Feldparameter', 'Power BI Field Parameter'],
        'definition' => [
            'de' => 'BI-Steuerung, mit der Nutzer Felder/Metriken in Visuals umschalten — Governance muss erlaubte Dimensionen begrenzen.',
            'en' => 'BI control that lets users switch fields/metrics in visuals — governance must constrain allowed dimensions.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'calculation-group', 'label' => ['de' => 'Calculation Group', 'en' => 'Calculation Group']],
            ['type' => 'glossary', 'id' => 'self-service-bi', 'label' => ['de' => 'Self-Service BI', 'en' => 'Self-Service BI']],
        ],
    ],
    [
        'id' => 'dual-storage-mode',
        'order' => 641,
        'category' => 'bi',
        'term' => ['de' => 'Dual Storage Mode', 'en' => 'Dual Storage Mode'],
        'aliases' => ['Dual Mode', 'Hybrid Tables'],
        'definition' => [
            'de' => 'Tabelle kann Import und DirectQuery zugleich nutzen — flexibel, aber schwerer zu erklären und zu debuggen.',
            'en' => 'A table can use import and DirectQuery together — flexible, but harder to explain and debug.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'import-mode', 'label' => ['de' => 'Import Mode', 'en' => 'Import Mode']],
            ['type' => 'glossary', 'id' => 'live-connection', 'label' => ['de' => 'Live Connection', 'en' => 'Live Connection']],
            ['type' => 'glossary', 'id' => 'composite-model', 'label' => ['de' => 'Composite Model', 'en' => 'Composite Model']],
        ],
    ],
    [
        'id' => 'incremental-refresh',
        'order' => 642,
        'category' => 'bi',
        'term' => ['de' => 'Incremental Refresh', 'en' => 'Incremental Refresh'],
        'aliases' => ['Inkrementeller Refresh', 'Incremental Load (BI)'],
        'definition' => [
            'de' => 'Lädt nur neue/geänderte Partitionen statt Full Reload — spart Zeit, braucht stabile Schlüssel und Archive-Politik.',
            'en' => 'Loads only new/changed partitions instead of a full reload — saves time, needs stable keys and archive policy.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'import-mode', 'label' => ['de' => 'Import Mode', 'en' => 'Import Mode']],
            ['type' => 'glossary', 'id' => 'backfill', 'label' => ['de' => 'Backfill', 'en' => 'Backfill']],
        ],
    ],
    [
        'id' => 'live-connection',
        'order' => 643,
        'category' => 'bi',
        'term' => ['de' => 'Live Connection', 'en' => 'Live Connection'],
        'aliases' => ['Live-Verbindung', 'Direct Live'],
        'definition' => [
            'de' => 'Report trifft das semantische Modell live — immer aktuelle Daten, aber Performance und Modell-Governance sitzen zentral.',
            'en' => 'The report hits the semantic model live — always current data, but performance and model governance sit centrally.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'import-mode', 'label' => ['de' => 'Import Mode', 'en' => 'Import Mode']],
            ['type' => 'glossary', 'id' => 'semantic-layer', 'label' => ['de' => 'Semantic Layer', 'en' => 'Semantic Layer']],
            ['type' => 'glossary', 'id' => 'headless-bi', 'label' => ['de' => 'Headless BI', 'en' => 'Headless BI']],
        ],
    ],
    [
        'id' => 'import-mode',
        'order' => 644,
        'category' => 'bi',
        'term' => ['de' => 'Import Mode', 'en' => 'Import Mode'],
        'aliases' => ['Import-Modus', 'Cached Dataset'],
        'definition' => [
            'de' => 'Daten werden in das BI-Modell geladen und dort gecacht — schnell für User, Refresh-Fenster und Speicher werden kritisch.',
            'en' => 'Data is loaded into the BI model and cached there — fast for users, refresh windows and memory become critical.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'live-connection', 'label' => ['de' => 'Live Connection', 'en' => 'Live Connection']],
            ['type' => 'glossary', 'id' => 'incremental-refresh', 'label' => ['de' => 'Incremental Refresh', 'en' => 'Incremental Refresh']],
        ],
    ],
    [
        'id' => 'quarantine-zone',
        'order' => 810,
        'category' => 'quality',
        'term' => ['de' => 'Quarantine Zone', 'en' => 'Quarantine Zone'],
        'aliases' => ['Quarantänezone', 'Bad Data Quarantine'],
        'definition' => [
            'de' => 'Isolierter Ablageort für fehlgeschlagene Records — Pipeline läuft weiter, fehlerhafte Daten erreichen keine Consumer.',
            'en' => 'Isolated holding area for failed records — pipeline continues, bad data does not reach consumers.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'data-contract', 'label' => ['de' => 'Data Contract', 'en' => 'Data Contract']],
            ['type' => 'glossary', 'id' => 'completeness', 'label' => ['de' => 'Completeness', 'en' => 'Completeness']],
        ],
    ],
    [
        'id' => 'reconciliation-check',
        'order' => 811,
        'category' => 'quality',
        'term' => ['de' => 'Reconciliation Check', 'en' => 'Reconciliation Check'],
        'aliases' => ['Abstimmung', 'Source-Target Reconciliation'],
        'definition' => [
            'de' => 'Vergleicht Summen/Counts zwischen Quelle und Ziel — findet stille Verluste, die Row-Tests übersehen.',
            'en' => 'Compares sums/counts between source and target — finds silent loss that row tests miss.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'completeness', 'label' => ['de' => 'Completeness', 'en' => 'Completeness']],
            ['type' => 'glossary', 'id' => 'accuracy', 'label' => ['de' => 'Accuracy', 'en' => 'Accuracy']],
        ],
    ],
    [
        'id' => 'data-reliability',
        'order' => 812,
        'category' => 'quality',
        'term' => ['de' => 'Data Reliability', 'en' => 'Data Reliability'],
        'aliases' => ['Datenverlässlichkeit', 'Reliable Data'],
        'definition' => [
            'de' => 'Gesamteindruck, dass Daten pünktlich, vollständig und vertrauenswürdig ankommen — mehr als einzelne DQ-Checks.',
            'en' => 'Overall sense that data arrives on time, complete, and trustworthy — more than single DQ checks.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'data-observability', 'label' => ['de' => 'Data Observability', 'en' => 'Data Observability']],
            ['type' => 'glossary', 'id' => 'data-sla', 'label' => ['de' => 'Data SLA', 'en' => 'Data SLA']],
        ],
    ],
    [
        'id' => 'volume-anomaly',
        'order' => 813,
        'category' => 'quality',
        'term' => ['de' => 'Volume Anomaly', 'en' => 'Volume Anomaly'],
        'aliases' => ['Volumenanomalie', 'Row Count Spike'],
        'definition' => [
            'de' => 'Unerwarteter Sprung oder Einbruch der Satzanzahl — oft erstes Signal für kaputte Upstream-Jobs.',
            'en' => 'Unexpected jump or drop in row count — often the first signal of broken upstream jobs.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'anomaly-detection', 'label' => ['de' => 'Anomaly Detection', 'en' => 'Anomaly Detection']],
            ['type' => 'glossary', 'id' => 'freshness', 'label' => ['de' => 'Freshness', 'en' => 'Freshness']],
        ],
    ],
    [
        'id' => 'data-clean-room',
        'order' => 940,
        'category' => 'privacy',
        'term' => ['de' => 'Data Clean Room', 'en' => 'Data Clean Room'],
        'aliases' => ['Clean Room', 'Privacy Clean Room'],
        'definition' => [
            'de' => 'Kontrollierte Umgebung für gemeinsame Analysen ohne Rohdaten-Austausch — Queries ja, Datensätze kopieren nein.',
            'en' => 'Controlled environment for joint analytics without raw data exchange — queries yes, copying datasets no.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'differential-privacy', 'label' => ['de' => 'Differential Privacy', 'en' => 'Differential Privacy']],
            ['type' => 'glossary', 'id' => 'pii', 'label' => ['de' => 'PII', 'en' => 'PII']],
        ],
    ],
    [
        'id' => 'retention-schedule',
        'order' => 941,
        'category' => 'privacy',
        'term' => ['de' => 'Retention Schedule', 'en' => 'Retention Schedule'],
        'aliases' => ['Aufbewahrungsplan', 'Data Retention Policy'],
        'definition' => [
            'de' => 'Regelt, wie lange welche Datenklassen gehalten und wann gelöscht/archiviert werden — ohne Plan wächst Compliance-Risiko.',
            'en' => 'Defines how long each data class is kept and when it is deleted/archived — without a plan compliance risk grows.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'right-to-erasure', 'label' => ['de' => 'Right to Erasure', 'en' => 'Right to Erasure']],
            ['type' => 'glossary', 'id' => 'gdpr', 'label' => ['de' => 'GDPR', 'en' => 'GDPR']],
        ],
    ],
    [
        'id' => 'right-to-erasure',
        'order' => 942,
        'category' => 'privacy',
        'term' => ['de' => 'Right to Erasure', 'en' => 'Right to Erasure'],
        'aliases' => ['Recht auf Löschung', 'Right to be Forgotten'],
        'definition' => [
            'de' => 'Anspruch, personenbezogene Daten löschen zu lassen — braucht Lineage bis Backup und Downstream-Kopien.',
            'en' => 'Right to have personal data erased — needs lineage through backups and downstream copies.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'gdpr', 'label' => ['de' => 'GDPR', 'en' => 'GDPR']],
            ['type' => 'glossary', 'id' => 'retention-schedule', 'label' => ['de' => 'Retention Schedule', 'en' => 'Retention Schedule']],
            ['type' => 'glossary', 'id' => 'lineage', 'label' => ['de' => 'Lineage', 'en' => 'Lineage']],
        ],
    ],
    [
        'id' => 'data-dictionary',
        'order' => 1330,
        'category' => 'metadata',
        'term' => ['de' => 'Data Dictionary', 'en' => 'Data Dictionary'],
        'aliases' => ['Datendictionary', 'Technical Dictionary'],
        'definition' => [
            'de' => 'Technischer Katalog von Tabellen/Spalten/Typen — ergänzt das Business Glossary, ersetzt es nicht.',
            'en' => 'Technical catalog of tables/columns/types — complements the business glossary, does not replace it.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'business-glossary', 'label' => ['de' => 'Business Glossary', 'en' => 'Business Glossary']],
            ['type' => 'glossary', 'id' => 'technical-metadata', 'label' => ['de' => 'Technical Metadata', 'en' => 'Technical Metadata']],
            ['type' => 'glossary', 'id' => 'data-catalog', 'label' => ['de' => 'Data Catalog', 'en' => 'Data Catalog']],
        ],
    ],
    [
        'id' => 'passive-metadata',
        'order' => 1331,
        'category' => 'metadata',
        'term' => ['de' => 'Passive Metadata', 'en' => 'Passive Metadata'],
        'aliases' => ['Passive Metadaten', 'Harvested Metadata'],
        'definition' => [
            'de' => 'Automatisch eingesammelte Schemas/Stats ohne aktive Pflege — gut zum Entdecken, schwach für verbindliche Semantik.',
            'en' => 'Automatically harvested schemas/stats without active curation — good for discovery, weak for binding semantics.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'active-metadata', 'label' => ['de' => 'Active Metadata', 'en' => 'Active Metadata']],
            ['type' => 'glossary', 'id' => 'data-catalog', 'label' => ['de' => 'Data Catalog', 'en' => 'Data Catalog']],
        ],
    ],
    [
        'id' => 'canary-release',
        'order' => 1440,
        'category' => 'process',
        'term' => ['de' => 'Canary Release', 'en' => 'Canary Release'],
        'aliases' => ['Canary Deploy', 'Canary Rollout'],
        'definition' => [
            'de' => 'Neue Version zuerst für einen kleinen Traffic-Anteil — Fehler begrenzt, Monitoring entscheidet über Weiterrollout.',
            'en' => 'New version first for a small traffic share — limits blast radius; monitoring decides further rollout.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'blue-green', 'label' => ['de' => 'Blue-Green', 'en' => 'Blue-Green']],
            ['type' => 'glossary', 'id' => 'feature-flag', 'label' => ['de' => 'Feature Flag', 'en' => 'Feature Flag']],
        ],
    ],
    [
        'id' => 'shadow-deployment',
        'order' => 1441,
        'category' => 'process',
        'term' => ['de' => 'Shadow Deployment', 'en' => 'Shadow Deployment'],
        'aliases' => ['Shadow Mode', 'Dark Launch'],
        'definition' => [
            'de' => 'Neue Pipeline/Modell läuft parallel ohne User-Impact — vergleicht Ergebnisse, schaltet erst später um.',
            'en' => 'New pipeline/model runs in parallel without user impact — compares results, switches later.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'parallel-run', 'label' => ['de' => 'Parallel Run', 'en' => 'Parallel Run']],
            ['type' => 'glossary', 'id' => 'canary-release', 'label' => ['de' => 'Canary Release', 'en' => 'Canary Release']],
        ],
    ],
    [
        'id' => 'chaos-engineering',
        'order' => 1442,
        'category' => 'process',
        'term' => ['de' => 'Chaos Engineering', 'en' => 'Chaos Engineering'],
        'aliases' => ['Chaos Testing', 'Failure Injection'],
        'definition' => [
            'de' => 'Gezieltes Injizieren von Fehlern in Produktion/Staging, um Resilienz zu beweisen — nicht blindes Kaputtschlagen.',
            'en' => 'Deliberately injecting failures in production/staging to prove resilience — not random breakage.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'circuit-breaker', 'label' => ['de' => 'Circuit Breaker', 'en' => 'Circuit Breaker']],
            ['type' => 'glossary', 'id' => 'error-budget', 'label' => ['de' => 'Error Budget', 'en' => 'Error Budget']],
        ],
    ],
    [
        'id' => 'circuit-breaker',
        'order' => 1443,
        'category' => 'process',
        'term' => ['de' => 'Circuit Breaker', 'en' => 'Circuit Breaker'],
        'aliases' => ['Schutzschalter', 'Fail-Fast Breaker'],
        'definition' => [
            'de' => 'Stoppt Aufrufe gegen einen kranken Downstream kurzzeitig — verhindert Kaskaden, braucht klare Recovery-Regeln.',
            'en' => 'Temporarily stops calls to an unhealthy downstream — prevents cascades, needs clear recovery rules.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'chaos-engineering', 'label' => ['de' => 'Chaos Engineering', 'en' => 'Chaos Engineering']],
            ['type' => 'glossary', 'id' => 'error-budget', 'label' => ['de' => 'Error Budget', 'en' => 'Error Budget']],
        ],
    ],
    [
        'id' => 'value-stream-mapping',
        'order' => 1444,
        'category' => 'process',
        'term' => ['de' => 'Value Stream Mapping', 'en' => 'Value Stream Mapping'],
        'aliases' => ['Wertstromanalyse', 'VSM'],
        'definition' => [
            'de' => 'Visualisiert End-to-End-Fluss von Idee bis Wert — macht Warteschlangen und Handoffs in Data/BI sichtbar.',
            'en' => 'Visualizes end-to-end flow from idea to value — makes queues and handoffs in data/BI visible.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'toil', 'label' => ['de' => 'Toil', 'en' => 'Toil']],
            ['type' => 'glossary', 'id' => 'golden-path', 'label' => ['de' => 'Golden Path', 'en' => 'Golden Path']],
        ],
    ],
    [
        'id' => 'model-context-protocol',
        'order' => 1680,
        'category' => 'ai',
        'term' => ['de' => 'Model Context Protocol', 'en' => 'Model Context Protocol'],
        'aliases' => ['MCP', 'Context Protocol'],
        'definition' => [
            'de' => 'Offenes Protokoll, damit Assistenten Tools/Datenquellen standardisiert anbinden — Governance muss erlaubte Tools whitelisten.',
            'en' => 'Open protocol so assistants connect tools/data sources in a standard way — governance must whitelist allowed tools.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'tool-calling', 'label' => ['de' => 'Tool Calling', 'en' => 'Tool Calling']],
            ['type' => 'glossary', 'id' => 'ai-guardrails', 'label' => ['de' => 'AI Guardrails', 'en' => 'AI Guardrails']],
        ],
    ],
    [
        'id' => 'agentic-workflow',
        'order' => 1681,
        'category' => 'ai',
        'term' => ['de' => 'Agentic Workflow', 'en' => 'Agentic Workflow'],
        'aliases' => ['Agentic AI', 'Multi-Agent Workflow'],
        'definition' => [
            'de' => 'LLM plant und führt Schritte mit Tools aus — braucht Guardrails, Observability und klare Abbruchkriterien.',
            'en' => 'An LLM plans and executes steps with tools — needs guardrails, observability, and clear stop criteria.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'tool-calling', 'label' => ['de' => 'Tool Calling', 'en' => 'Tool Calling']],
            ['type' => 'glossary', 'id' => 'model-context-protocol', 'label' => ['de' => 'MCP', 'en' => 'MCP']],
            ['type' => 'glossary', 'id' => 'ai-guardrails', 'label' => ['de' => 'AI Guardrails', 'en' => 'AI Guardrails']],
        ],
    ],
    [
        'id' => 'reranker',
        'order' => 1682,
        'category' => 'ai',
        'term' => ['de' => 'Reranker', 'en' => 'Reranker'],
        'aliases' => ['Cross-Encoder Rerank', 'Re-Ranking'],
        'definition' => [
            'de' => 'Zweite Stufe nach Retrieval: bewertet Kandidaten neu für Relevanz — teurer als reine Vector Search, oft präziser.',
            'en' => 'Second stage after retrieval: re-scores candidates for relevance — costlier than pure vector search, often more precise.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'rag', 'label' => ['de' => 'RAG', 'en' => 'RAG']],
            ['type' => 'glossary', 'id' => 'vector-search', 'label' => ['de' => 'Vector Search', 'en' => 'Vector Search']],
            ['type' => 'glossary', 'id' => 'embedding', 'label' => ['de' => 'Embedding', 'en' => 'Embedding']],
        ],
    ],
    [
        'id' => 'chain-of-thought',
        'order' => 1683,
        'category' => 'ai',
        'term' => ['de' => 'Chain of Thought', 'en' => 'Chain of Thought'],
        'aliases' => ['CoT', 'Denkschritte'],
        'definition' => [
            'de' => 'Prompt-Technik, bei der das Modell Zwischenschritte ausgibt — kann Reasoning verbessern, leakiert aber Denkspuren.',
            'en' => 'Prompt technique where the model emits intermediate steps — can improve reasoning, but leaks thinking traces.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'few-shot-prompting', 'label' => ['de' => 'Few-Shot Prompting', 'en' => 'Few-Shot Prompting']],
            ['type' => 'glossary', 'id' => 'hallucination', 'label' => ['de' => 'Hallucination', 'en' => 'Hallucination']],
        ],
    ],
    [
        'id' => 'rlhf',
        'order' => 1684,
        'category' => 'ai',
        'term' => ['de' => 'RLHF', 'en' => 'RLHF'],
        'aliases' => ['Reinforcement Learning from Human Feedback', 'Human Preference Tuning'],
        'definition' => [
            'de' => 'Feinabstimmung mit menschlichem Feedback/Belohnung — steuert Verhalten, ersetzt keine Domain-Guardrails.',
            'en' => 'Fine-tuning with human feedback/reward — steers behavior, does not replace domain guardrails.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'fine-tuning', 'label' => ['de' => 'Fine-Tuning', 'en' => 'Fine-Tuning']],
            ['type' => 'glossary', 'id' => 'ai-guardrails', 'label' => ['de' => 'AI Guardrails', 'en' => 'AI Guardrails']],
        ],
    ],
    [
        'id' => 'few-shot-prompting',
        'order' => 1685,
        'category' => 'ai',
        'term' => ['de' => 'Few-Shot Prompting', 'en' => 'Few-Shot Prompting'],
        'aliases' => ['Few-Shot', 'In-Context Examples'],
        'definition' => [
            'de' => 'Prompt enthält wenige Beispiele des gewünschten Formats — steuert Output ohne Fine-Tuning, verbraucht Context Window.',
            'en' => 'Prompt includes a few examples of the desired format — steers output without fine-tuning, consumes context window.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'context-window', 'label' => ['de' => 'Context Window', 'en' => 'Context Window']],
            ['type' => 'glossary', 'id' => 'chain-of-thought', 'label' => ['de' => 'Chain of Thought', 'en' => 'Chain of Thought']],
        ],
    ],
    [
        'id' => 'jailbreak',
        'order' => 1686,
        'category' => 'ai',
        'term' => ['de' => 'Jailbreak', 'en' => 'Jailbreak'],
        'aliases' => ['Prompt Jailbreak', 'Safety Bypass'],
        'definition' => [
            'de' => 'Versuch, Sicherheits-/Policy-Grenzen eines Modells zu umgehen — Guardrails und Monitoring müssen dagegen halten.',
            'en' => 'Attempt to bypass a model’s safety/policy boundaries — guardrails and monitoring must resist it.',
        ],
        'related' => [
            ['type' => 'glossary', 'id' => 'prompt-injection', 'label' => ['de' => 'Prompt Injection', 'en' => 'Prompt Injection']],
            ['type' => 'glossary', 'id' => 'ai-guardrails', 'label' => ['de' => 'AI Guardrails', 'en' => 'AI Guardrails']],
        ],
    ],
];
