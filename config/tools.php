<?php

use App\Support\ToolLinks;

return [
    'hero_pills' => [
        'dbt-ready',
        'PII Governance',
        'Data Quality',
        'SDK Examples',
        'No Gateway',
        'Reference Workflows',
        'Laravel + Vite',
    ],

    'workflows' => [
        'dbt-pii-governance' => [
            'label' => [
                'de' => 'Security & Governance Einrichtung',
                'en' => 'Security & governance setup',
            ],
            'description' => [
                'de' => 'Copy-Paste-Einrichtung: Makros, Policy, Table Gate, PII Recommend — Einstellungen werden über alle Steps geteilt.',
                'en' => 'Copy-paste setup: macros, policy, table gate, PII recommend — settings shared across steps.',
            ],
            'icon' => 'fa-shield-halved',
            'accent' => 'accent',
            'steps' => [
                'dbt-governance-macro-generator',
                'pii-policy-generator',
                'pii-unreviewed-gate-generator',
                'pii-recommend-generator',
            ],
        ],
        'dbt-dq-governance' => [
            'label' => [
                'de' => 'Datenqualität Einrichtung',
                'en' => 'Data quality setup',
            ],
            'description' => [
                'de' => 'Copy-Paste-Einrichtung: DQ-Makros, meta.dq_rules in schema.yml, History-Mart für Trends und Reports.',
                'en' => 'Copy-paste setup: DQ macros, meta.dq_rules in schema.yml, history mart for trends and reports.',
            ],
            'icon' => 'fa-table',
            'accent' => 'primary',
            'steps' => [
                'dbt-dq-macro-generator',
                'dbt-dq-rules-generator',
                'dbt-dq-history-generator',
            ],
        ],
    ],

    'nav' => [
        [
            'id' => 'dbt-governance-macro-generator',
            'route' => 'tools.dbt-governance-macro-generator',
            'label' => [
                'de' => 'PII Macro Generator',
                'en' => 'PII Macro Generator',
            ],
            'description' => [
                'de' => 'Laufzeit-Makros, Generic Tests und SETUP.md für dein dbt-Projekt.',
                'en' => 'Runtime macros, generic tests, and SETUP.md for your dbt project.',
            ],
            'example' => true,
            'accent' => 'primary',
            'workflow' => 'dbt-pii-governance',
            'workflowStep' => 1,
        ],
        [
            'id' => 'pii-policy-generator',
            'route' => 'tools.pii-policy-generator',
            'label' => [
                'de' => 'PII Policy Generator',
                'en' => 'PII Policy Generator',
            ],
            'description' => [
                'de' => 'pii_details schema.yml-Beispiel und Secure Model — Zielzustand nach Review.',
                'en' => 'pii_details schema.yml example and secure model — target state after review.',
            ],
            'example' => true,
            'accent' => 'accent',
            'workflow' => 'dbt-pii-governance',
            'workflowStep' => 2,
        ],
        [
            'id' => 'pii-unreviewed-gate-generator',
            'route' => 'tools.pii-unreviewed-gate-generator',
            'label' => [
                'de' => 'PII Table Gate',
                'en' => 'PII Table Gate',
            ],
            'description' => [
                'de' => 'Ungeprüfte Models identifizieren und für Rollen verstecken.',
                'en' => 'Identify and hide unreviewed models from roles.',
            ],
            'example' => true,
            'accent' => 'accent',
            'workflow' => 'dbt-pii-governance',
            'workflowStep' => 3,
        ],
        [
            'id' => 'pii-recommend-generator',
            'route' => 'tools.pii-recommend-generator',
            'label' => [
                'de' => 'PII Recommend Generator',
                'en' => 'PII Recommend Generator',
            ],
            'description' => [
                'de' => 'Name- und Content-Audit, Heuristik-Regeln und pii_recommend schema.yml.',
                'en' => 'Name and content audit, heuristic rules, and pii_recommend schema.yml.',
            ],
            'example' => true,
            'accent' => 'primary',
            'workflow' => 'dbt-pii-governance',
            'workflowStep' => 4,
        ],
        [
            'id' => 'dbt-dq-macro-generator',
            'route' => 'tools.dbt-dq-macro-generator',
            'label' => [
                'de' => 'DQ Macro Generator',
                'en' => 'DQ Macro Generator',
            ],
            'navLabel' => [
                'de' => 'DG Macro Generator',
                'en' => 'DG Macro Generator',
            ],
            'description' => [
                'de' => 'Laufzeit-Makros, Generic Test dq_rule und SETUP_DQ.md für dein dbt-Projekt.',
                'en' => 'Runtime macros, dq_rule generic test, and SETUP_DQ.md for your dbt project.',
            ],
            'example' => true,
            'accent' => 'primary',
            'workflow' => 'dbt-dq-governance',
            'workflowStep' => 1,
        ],
        [
            'id' => 'dbt-dq-rules-generator',
            'route' => 'tools.dbt-dq-rules-generator',
            'label' => [
                'de' => 'DQ Rules Generator',
                'en' => 'DQ Rules Generator',
            ],
            'description' => [
                'de' => 'meta.dq_rules in schema.yml — Regeln pro Spalte und Model.',
                'en' => 'meta.dq_rules in schema.yml — rules per column and model.',
            ],
            'example' => true,
            'accent' => 'accent',
            'workflow' => 'dbt-dq-governance',
            'workflowStep' => 2,
        ],
        [
            'id' => 'dbt-dq-history-generator',
            'route' => 'tools.dbt-dq-history-generator',
            'label' => [
                'de' => 'DQ History Generator',
                'en' => 'DQ History Generator',
            ],
            'description' => [
                'de' => 'dq_run_history, Report-Views und dq_collect_results Runbook.',
                'en' => 'dq_run_history, report views, and dq_collect_results runbook.',
            ],
            'example' => true,
            'accent' => 'primary',
            'workflow' => 'dbt-dq-governance',
            'workflowStep' => 3,
        ],
        [
            'id' => 'schema-yml-editor',
            'route' => 'tools.schema-yml-editor',
            'label' => [
                'de' => 'Schema YML Editor',
                'en' => 'Schema YML Editor',
            ],
            'description' => [
                'de' => 'Hilfs-Tool: einzelne schema.yml typo-sicher bearbeiten — nicht Teil der Einrichtungs-Kette.',
                'en' => 'Helper tool: edit individual schema.yml safely — not part of the setup chain.',
            ],
            'example' => true,
            'icon' => 'fa-file-lines',
            'dbt' => true,
            'accent' => 'primary',
        ],
        [
            'id' => 'governance-ai-sanitizer',
            'route' => 'tools.governance-ai-sanitizer',
            'label' => [
                'de' => 'AI Sanitizer',
                'en' => 'AI Sanitizer',
            ],
            'description' => [
                'de' => 'Referenz-Beispiel: Prompt sanitisieren, Outbound kopieren, KI-Antwort wiederherstellen.',
                'en' => 'Reference example: sanitize prompt, copy outbound, restore AI response.',
            ],
            'example' => true,
            'icon' => 'fa-microchip',
            'accent' => 'primary',
        ],
    ],

    'ecosystem' => [
        [
            'id' => 'binom-ngx',
            'title' => 'binom-ngx',
            'description' => [
                'de' => 'Angular UI Libraries, SDKs und interaktive Dokumentation.',
                'en' => 'Angular UI libraries, SDKs, and interactive documentation.',
            ],
            'meta' => [
                'de' => 'Docs & Demos',
                'en' => 'Docs & Demos',
            ],
            'icon' => 'fa-bolt',
            'accent' => 'accent',
            'featured' => true,
            'external' => true,
            'href' => ToolLinks::BINOM_NGX_DOCS,
        ],
    ],

    'links' => [
        'website' => env('BINOM_WEBSITE_URL', 'https://binom.net'),
        'binom_ngx_docs' => ToolLinks::BINOM_NGX_DOCS,
        'repository' => env('BINOM_TOOLS_REPO_URL', 'https://github.com/Ac1d0n3/binom-tools'),
    ],
];
