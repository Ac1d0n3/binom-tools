<?php

use App\Support\ToolLinks;

return [
    'version' => env('BINOM_TOOLS_VERSION', '0.1.0'),
    'beta' => filter_var(env('BINOM_TOOLS_BETA', true), FILTER_VALIDATE_BOOL),

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
        'ai-prompt-workflow' => [
            'label' => [
                'de' => 'AI Prompt Workflow',
                'en' => 'AI prompt workflow',
            ],
            'description' => [
                'de' => 'Prompt erstellen, PII anonymisieren, sicher ans LLM senden.',
                'en' => 'Build prompt, anonymize PII, send safely to LLM.',
            ],
            'icon' => 'fa-microchip',
            'accent' => 'primary',
            'steps' => [
                'prompt-studio',
                'governance-ai-sanitizer',
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
            'id' => 'prompt-studio',
            'route' => 'tools.prompt-studio',
            'label' => [
                'de' => 'Prompt Studio',
                'en' => 'Prompt Studio',
            ],
            'description' => [
                'de' => 'Professionelle Prompts für ChatGPT, Claude, Gemini, Suno, Midjourney und mehr — config-getrieben.',
                'en' => 'Professional prompts for ChatGPT, Claude, Gemini, Suno, Midjourney and more — config-driven.',
            ],
            'example' => true,
            'icon' => 'fa-wand-magic-sparkles',
            'accent' => 'primary',
            'workflow' => 'ai-prompt-workflow',
            'workflowStep' => 1,
        ],
        [
            'id' => 'governance-ai-sanitizer',
            'route' => 'tools.governance-ai-sanitizer',
            'label' => [
                'de' => 'AI Sanitizer',
                'en' => 'AI Sanitizer',
            ],
            'description' => [
                'de' => 'Prompt sanitisieren, Outbound kopieren, KI-Antwort wiederherstellen.',
                'en' => 'Sanitize prompt, copy outbound, restore AI response.',
            ],
            'example' => true,
            'icon' => 'fa-microchip',
            'accent' => 'primary',
            'workflow' => 'ai-prompt-workflow',
            'workflowStep' => 2,
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

    /*
    |--------------------------------------------------------------------------
    | Tools overview page header ( /tools )
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'show_title' => env('TOOLS_OVERVIEW_SHOW_TITLE', false),
        'show_lead' => env('TOOLS_OVERVIEW_SHOW_LEAD', false),
        'title_de' => env('TOOLS_OVERVIEW_TITLE_DE'),
        'title_en' => env('TOOLS_OVERVIEW_TITLE_EN'),
        'lead_de' => env('TOOLS_OVERVIEW_LEAD_DE'),
        'lead_en' => env('TOOLS_OVERVIEW_LEAD_EN'),
    ],
];
