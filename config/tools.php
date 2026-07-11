<?php

use App\Support\ToolLinks;

return [
    'hero_pills' => [
        'dbt-ready',
        'PII Governance',
        'SDK Examples',
        'No Gateway',
        'Reference Workflows',
        'Laravel + Vite',
    ],

    'workflows' => [
        'dbt-pii-governance' => [
            'label' => [
                'de' => 'dbt PII Governance Einrichtung',
                'en' => 'dbt PII Governance setup',
            ],
            'description' => [
                'de' => 'Copy-Paste-Einrichtung: Makros, Policy, Table Gate, PII Recommend — Einstellungen werden über alle Steps geteilt.',
                'en' => 'Copy-paste setup: macros, policy, table gate, PII recommend — settings shared across steps.',
            ],
            'steps' => [
                'dbt-governance-macro-generator',
                'pii-policy-generator',
                'pii-unreviewed-gate-generator',
                'pii-recommend-generator',
            ],
        ],
    ],

    'nav' => [
        [
            'id' => 'dbt-governance-macro-generator',
            'route' => 'tools.dbt-governance-macro-generator',
            'label' => [
                'de' => 'Governance Macro Generator',
                'en' => 'Governance Macro Generator',
            ],
            'description' => [
                'de' => 'Schritt 1/4: Laufzeit-Makros, Generic Tests und SETUP.md für dein dbt-Projekt.',
                'en' => 'Step 1/4: Runtime macros, generic tests, and SETUP.md for your dbt project.',
            ],
            'example' => true,
            'icon' => 'fa-gears',
            'accent' => 'primary',
            'workflow' => 'dbt-pii-governance',
            'workflowStep' => 1,
        ],
        [
            'id' => 'pii-policy-generator',
            'route' => 'tools.pii-policy-generator',
            'label' => [
                'de' => 'DBT Policy Generator',
                'en' => 'DBT Policy Generator',
            ],
            'description' => [
                'de' => 'Schritt 2/4: pii_details schema.yml-Beispiel und Secure Model — Zielzustand nach Review.',
                'en' => 'Step 2/4: pii_details schema.yml example and secure model — target state after review.',
            ],
            'example' => true,
            'icon' => 'fa-file-shield',
            'accent' => 'accent',
            'workflow' => 'dbt-pii-governance',
            'workflowStep' => 2,
        ],
        [
            'id' => 'pii-unreviewed-gate-generator',
            'route' => 'tools.pii-unreviewed-gate-generator',
            'label' => [
                'de' => 'Unreviewed Table Gate Generator',
                'en' => 'Unreviewed Table Gate Generator',
            ],
            'description' => [
                'de' => 'Schritt 3/4: Ungeprüfte Models identifizieren und für Rollen verstecken.',
                'en' => 'Step 3/4: Identify and hide unreviewed models from roles.',
            ],
            'example' => true,
            'icon' => 'fa-door-closed',
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
                'de' => 'Schritt 4/4: Name- und Content-Audit, Heuristik-Regeln und pii_recommend schema.yml.',
                'en' => 'Step 4/4: Name and content audit, heuristic rules, and pii_recommend schema.yml.',
            ],
            'example' => true,
            'icon' => 'fa-magnifying-glass-chart',
            'accent' => 'primary',
            'workflow' => 'dbt-pii-governance',
            'workflowStep' => 4,
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
            'accent' => 'primary',
        ],
        [
            'id' => 'governance-ai-sanitizer',
            'route' => 'tools.governance-ai-sanitizer',
            'label' => [
                'de' => 'Governance AI Sanitizer',
                'en' => 'Governance AI Sanitizer',
            ],
            'description' => [
                'de' => 'Referenz-Beispiel: Prompt sanitisieren, Outbound kopieren, KI-Antwort wiederherstellen.',
                'en' => 'Reference example: sanitize prompt, copy outbound, restore AI response.',
            ],
            'example' => true,
            'icon' => 'fa-shield-halved',
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
