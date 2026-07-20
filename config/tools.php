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
        'lakehouse-dq-patterns' => [
            'label' => [
                'de' => 'Lakehouse DQ Patterns',
                'en' => 'Lakehouse DQ patterns',
            ],
            'description' => [
                'de' => 'Copy-Paste-Patterns für Fabric und Databricks: Checks, Delta/SCD-Snippets und Governance-Runbooks.',
                'en' => 'Copy-paste patterns for Fabric and Databricks: checks, Delta/SCD snippets, and governance runbooks.',
            ],
            'icon' => 'fa-layer-group',
            'accent' => 'accent',
            'steps' => [
                'fabric-dq-pattern-generator',
                'databricks-dq-pattern-generator',
            ],
        ],
        'discovery-assessment' => [
            'label' => [
                'de' => 'Discovery & Assessment',
                'en' => 'Discovery & assessment',
            ],
            'description' => [
                'de' => 'Inventare, Matrizen und Checklisten — standalone nutzbar, Export/Download, keine Speicherung im Tool.',
                'en' => 'Inventories, matrices, and checklists — works standalone, export/download, nothing stored in the tool.',
            ],
            'icon' => 'fa-compass',
            'accent' => 'primary',
            'steps' => [
                'stakeholder-matrix',
                'report-inventory',
                'kpi-definition',
                'architecture-fit',
                'impact-effort',
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
            'id' => 'fabric-dq-pattern-generator',
            'route' => 'tools.fabric-dq-pattern-generator',
            'label' => [
                'de' => 'Fabric DQ Pattern Generator',
                'en' => 'Fabric DQ Pattern Generator',
            ],
            'description' => [
                'de' => 'SQL- und Notebook-Patterns für Fabric Lakehouse/Warehouse: DQ-Regeln, Delta Loads, SCD2 und Pipeline-Gates.',
                'en' => 'SQL and notebook patterns for Fabric Lakehouse/Warehouse: DQ rules, Delta loads, SCD2, and pipeline gates.',
            ],
            'example' => true,
            'icon' => 'fa-layer-group',
            'accent' => 'accent',
            'for' => ['Fabric'],
            'workflow' => 'lakehouse-dq-patterns',
            'workflowStep' => 1,
        ],
        [
            'id' => 'databricks-dq-pattern-generator',
            'route' => 'tools.databricks-dq-pattern-generator',
            'label' => [
                'de' => 'Databricks DQ Pattern Generator',
                'en' => 'Databricks DQ Pattern Generator',
            ],
            'description' => [
                'de' => 'DLT Expectations, Delta MERGE/SCD2 und Unity-Catalog-Governance-Patterns für Databricks.',
                'en' => 'DLT expectations, Delta MERGE/SCD2, and Unity Catalog governance patterns for Databricks.',
            ],
            'example' => true,
            'icon' => 'fa-database',
            'accent' => 'primary',
            'for' => ['Databricks'],
            'workflow' => 'lakehouse-dq-patterns',
            'workflowStep' => 2,
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
            'id' => 'meta-export-generator',
            'route' => 'tools.meta-export-generator',
            'label' => [
                'de' => 'Meta Export Generator',
                'en' => 'Meta Export Generator',
            ],
            'description' => [
                'de' => 'Copy-Paste-SQL/Scripts für Schemas, Tabellen, Spalten und Access-Meta — 10 Plattformen, kein Live-Connect.',
                'en' => 'Copy-paste SQL/scripts for schemas, tables, columns and access meta — 10 platforms, no live connect.',
            ],
            'example' => true,
            'icon' => 'fa-database',
            'accent' => 'accent',
        ],
        [
            'id' => 'stakeholder-matrix',
            'route' => 'tools.stakeholder-matrix',
            'label' => [
                'de' => 'Stakeholder & RACI Matrix',
                'en' => 'Stakeholder & RACI Matrix',
            ],
            'description' => [
                'de' => 'Stakeholder mit Einfluss, Interesse und optionaler RACI — Export für Mandat und Owner-Matrix.',
                'en' => 'Stakeholders with influence, interest, and optional RACI — export for mandate and owner matrix.',
            ],
            'example' => true,
            'icon' => 'fa-users',
            'accent' => 'primary',
            'workflow' => 'discovery-assessment',
            'workflowStep' => 1,
        ],
        [
            'id' => 'report-inventory',
            'route' => 'tools.report-inventory',
            'label' => [
                'de' => 'Report Inventory Canvas',
                'en' => 'Report Inventory Canvas',
            ],
            'description' => [
                'de' => 'Reports und Dashboards inventarisieren — Owner, Tool, Rhythmus, Geschäftsfrage.',
                'en' => 'Inventory reports and dashboards — owner, tool, cadence, business question.',
            ],
            'example' => true,
            'icon' => 'fa-chart-pie',
            'accent' => 'accent',
            'workflow' => 'discovery-assessment',
            'workflowStep' => 2,
        ],
        [
            'id' => 'kpi-definition',
            'route' => 'tools.kpi-definition',
            'label' => [
                'de' => 'KPI Definition Card',
                'en' => 'KPI Definition Card',
            ],
            'description' => [
                'de' => 'KPI-Inventar mit Formel, Grain, Owner und Abstimmungsstatus.',
                'en' => 'KPI inventory with formula, grain, owner, and agreement status.',
            ],
            'example' => true,
            'icon' => 'fa-gauge-high',
            'accent' => 'primary',
            'workflow' => 'discovery-assessment',
            'workflowStep' => 3,
        ],
        [
            'id' => 'architecture-fit',
            'route' => 'tools.architecture-fit',
            'label' => [
                'de' => 'Architecture Fit Checklist',
                'en' => 'Architecture Fit Checklist',
            ],
            'description' => [
                'de' => 'Architekturdiagnose und Engpass-Liste entlang Quellen bis Consumption.',
                'en' => 'Architecture diagnosis and bottleneck list from sources to consumption.',
            ],
            'example' => true,
            'icon' => 'fa-sitemap',
            'accent' => 'accent',
            'workflow' => 'discovery-assessment',
            'workflowStep' => 4,
        ],
        [
            'id' => 'impact-effort',
            'route' => 'tools.impact-effort',
            'label' => [
                'de' => 'Impact–Effort Prioritizer',
                'en' => 'Impact–Effort Prioritizer',
            ],
            'description' => [
                'de' => 'Initiativen scoren, 2×2-Matrix sehen und Pilot-Kandidaten markieren.',
                'en' => 'Score initiatives, view a 2×2 matrix, and mark the pilot candidate.',
            ],
            'example' => true,
            'icon' => 'fa-bullseye',
            'accent' => 'primary',
            'workflow' => 'discovery-assessment',
            'workflowStep' => 5,
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
    | Per-tool enable flags (TOOL_{ID}_ENABLED, default true)
    |--------------------------------------------------------------------------
    */
    'enabled' => [
        'dbt-governance-macro-generator' => filter_var(env('TOOL_DBT_GOVERNANCE_MACRO_GENERATOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'pii-policy-generator' => filter_var(env('TOOL_PII_POLICY_GENERATOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'pii-unreviewed-gate-generator' => filter_var(env('TOOL_PII_UNREVIEWED_GATE_GENERATOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'pii-recommend-generator' => filter_var(env('TOOL_PII_RECOMMEND_GENERATOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'dbt-dq-macro-generator' => filter_var(env('TOOL_DBT_DQ_MACRO_GENERATOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'dbt-dq-rules-generator' => filter_var(env('TOOL_DBT_DQ_RULES_GENERATOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'dbt-dq-history-generator' => filter_var(env('TOOL_DBT_DQ_HISTORY_GENERATOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'fabric-dq-pattern-generator' => filter_var(env('TOOL_FABRIC_DQ_PATTERN_GENERATOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'databricks-dq-pattern-generator' => filter_var(env('TOOL_DATABRICKS_DQ_PATTERN_GENERATOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'schema-yml-editor' => filter_var(env('TOOL_SCHEMA_YML_EDITOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'meta-export-generator' => filter_var(env('TOOL_META_EXPORT_GENERATOR_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'stakeholder-matrix' => filter_var(env('TOOL_STAKEHOLDER_MATRIX_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'report-inventory' => filter_var(env('TOOL_REPORT_INVENTORY_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'kpi-definition' => filter_var(env('TOOL_KPI_DEFINITION_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'architecture-fit' => filter_var(env('TOOL_ARCHITECTURE_FIT_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'impact-effort' => filter_var(env('TOOL_IMPACT_EFFORT_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'prompt-studio' => filter_var(env('TOOL_PROMPT_STUDIO_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'governance-ai-sanitizer' => filter_var(env('TOOL_GOVERNANCE_AI_SANITIZER_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
    ],

    /*
    |--------------------------------------------------------------------------
    | Per-tool login requirement (TOOL_{ID}_LOGIN_REQUIRED, default false)
    |--------------------------------------------------------------------------
    |
    | Only enforced when BINOM_TOOLS_ACCOUNTS_ENABLED=true.
    |
    */
    'login_required' => [
        'dbt-governance-macro-generator' => filter_var(env('TOOL_DBT_GOVERNANCE_MACRO_GENERATOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'pii-policy-generator' => filter_var(env('TOOL_PII_POLICY_GENERATOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'pii-unreviewed-gate-generator' => filter_var(env('TOOL_PII_UNREVIEWED_GATE_GENERATOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'pii-recommend-generator' => filter_var(env('TOOL_PII_RECOMMEND_GENERATOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'dbt-dq-macro-generator' => filter_var(env('TOOL_DBT_DQ_MACRO_GENERATOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'dbt-dq-rules-generator' => filter_var(env('TOOL_DBT_DQ_RULES_GENERATOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'dbt-dq-history-generator' => filter_var(env('TOOL_DBT_DQ_HISTORY_GENERATOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'fabric-dq-pattern-generator' => filter_var(env('TOOL_FABRIC_DQ_PATTERN_GENERATOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'databricks-dq-pattern-generator' => filter_var(env('TOOL_DATABRICKS_DQ_PATTERN_GENERATOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'schema-yml-editor' => filter_var(env('TOOL_SCHEMA_YML_EDITOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'meta-export-generator' => filter_var(env('TOOL_META_EXPORT_GENERATOR_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'stakeholder-matrix' => filter_var(env('TOOL_STAKEHOLDER_MATRIX_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'report-inventory' => filter_var(env('TOOL_REPORT_INVENTORY_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'kpi-definition' => filter_var(env('TOOL_KPI_DEFINITION_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'architecture-fit' => filter_var(env('TOOL_ARCHITECTURE_FIT_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'impact-effort' => filter_var(env('TOOL_IMPACT_EFFORT_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'prompt-studio' => filter_var(env('TOOL_PROMPT_STUDIO_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
        'governance-ai-sanitizer' => filter_var(env('TOOL_GOVERNANCE_AI_SANITIZER_LOGIN_REQUIRED', false), FILTER_VALIDATE_BOOLEAN),
    ],

    /*
    |--------------------------------------------------------------------------
    | Governance overview page header ( /tools )
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'show_title' => env('TOOLS_OVERVIEW_SHOW_TITLE', false),
        'show_lead' => env('TOOLS_OVERVIEW_SHOW_LEAD', false),
        // Optional overrides; when empty, Blade uses data-i18n keys (tools.overviewTitle / Lead).
        'title_de' => env('TOOLS_OVERVIEW_TITLE_DE'),
        'title_en' => env('TOOLS_OVERVIEW_TITLE_EN'),
        'lead_de' => env('TOOLS_OVERVIEW_LEAD_DE'),
        'lead_en' => env('TOOLS_OVERVIEW_LEAD_EN'),
    ],
];
