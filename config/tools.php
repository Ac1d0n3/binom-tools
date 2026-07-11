<?php

return [
    'hero_pills' => [
        'dbt-ready',
        'PII Governance',
        'SDK Examples',
        'No Gateway',
        'Reference Workflows',
        'Laravel + Vite',
    ],

    'nav' => [
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
        [
            'id' => 'pii-policy-generator',
            'route' => 'tools.pii-policy-generator',
            'label' => [
                'de' => 'DBT Policy Generator',
                'en' => 'DBT Policy Generator',
            ],
            'description' => [
                'de' => 'Referenz-Beispiel: dbt schema.yml, Macro und Policy aus Spalten mit meta.pii_details generieren.',
                'en' => 'Reference example: generate dbt schema.yml, macro, and policy from columns with meta.pii_details.',
            ],
            'example' => true,
            'icon' => 'fa-file-shield',
            'accent' => 'accent',
        ],
        [
            'id' => 'schema-yml-editor',
            'route' => 'tools.schema-yml-editor',
            'label' => [
                'de' => 'Schema YML Editor',
                'en' => 'Schema YML Editor',
            ],
            'description' => [
                'de' => 'Referenz-Beispiel: schema.yml typo-sicher per Formular bearbeiten — synchron mit DBT Policy Generator.',
                'en' => 'Reference example: edit schema.yml via form without YAML typos — synced with DBT Policy Generator.',
            ],
            'example' => true,
            'icon' => 'fa-file-lines',
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
            'href_key' => 'binom_ngx_docs',
        ],
    ],

    'links' => [
        'website' => env('BINOM_WEBSITE_URL', 'https://binom.net'),
        'binom_ngx_docs' => env('BINOM_NGX_DOCS_URL', 'http://localhost:4200'),
        'repository' => env('BINOM_TOOLS_REPO_URL', 'https://github.com/binom/binom-tools'),
    ],
];
