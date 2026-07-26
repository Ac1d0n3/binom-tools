<?php

/**
 * Roles Hub — destination cards for governance personas.
 *
 * storyPreferred is used when the playbook exists; otherwise storyFallback.
 * See docs/story-gaps-roles.md for the story briefs.
 */
return [
    'personas' => [
        'all' => ['de' => 'Alle Rollen', 'en' => 'All roles'],
        'steward' => ['de' => 'Data Steward', 'en' => 'Data Steward'],
        'owner' => ['de' => 'Data Owner', 'en' => 'Data Owner'],
        'product-owner' => ['de' => 'Data Product Owner', 'en' => 'Data Product Owner'],
        'architect' => ['de' => 'Data Architect', 'en' => 'Data Architect'],
        'custodian' => ['de' => 'Data Custodian', 'en' => 'Data Custodian'],
        'consumer' => ['de' => 'Data Consumer', 'en' => 'Data Consumer'],
    ],

    'roles' => [
        [
            'id' => 'steward',
            'order' => 10,
            'persona' => 'steward',
            'icon' => 'fa-clipboard-check',
            'title' => ['de' => 'Data Steward', 'en' => 'Data Steward'],
            'focus' => [
                'de' => ['Definitionen', 'DQ-Gates', 'Katalogpflege'],
                'en' => ['Definitions', 'DQ gates', 'Catalog care'],
            ],
            'lead' => [
                'de' => 'Hält Bedeutungen, Qualitätsregeln und Nutzung in der Domäne arbeitsfähig — der operative Motor hinter Katalog, Contracts und DQ. Eskaliert an den Owner, entscheidet nicht allein über Zweck und Zugriff.',
                'en' => 'Keeps meanings, quality rules, and use workable in the domain — the operational engine behind catalog, contracts, and DQ. Escalates to the owner; does not alone decide purpose and access.',
            ],
            'glossaryId' => 'data-steward',
            'pathId' => 'governance-foundations',
            'toolRoute' => 'tools.stakeholder-matrix',
            'storyPreferred' => 'stewardship-capacity',
            'storyFallback' => 'data-ownership-stewardship',
            'storyLabel' => [
                'de' => 'Stewardship staffen — Capacity',
                'en' => 'Staffing stewardship — capacity',
            ],
            'extraStories' => [
                [
                    'preferred' => null,
                    'fallback' => 'data-ownership-stewardship',
                    'label' => ['de' => 'Ownership & Stewardship', 'en' => 'Ownership & stewardship'],
                ],
                [
                    'preferred' => null,
                    'fallback' => 'missing-pieces-ownership-stewardship',
                    'label' => ['de' => 'Missing Pieces: Ownership', 'en' => 'Missing pieces: ownership'],
                ],
                [
                    'preferred' => 'raci-for-data-governance',
                    'fallback' => null,
                    'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance'],
                ],
            ],
        ],
        [
            'id' => 'owner',
            'order' => 20,
            'persona' => 'owner',
            'icon' => 'fa-building-user',
            'title' => ['de' => 'Data Owner', 'en' => 'Data Owner'],
            'focus' => [
                'de' => ['Zweck', 'Zugriff', 'Freigaben'],
                'en' => ['Purpose', 'Access', 'Approvals'],
            ],
            'lead' => [
                'de' => 'Fachlich accountable für Zweckbindung, Zugriffsregeln und Freigaben in der Domäne. Setzt Decision Rights, ohne jedes Ticket zu bearbeiten — Steward und Custodian liefern die operative und technische Umsetzung.',
                'en' => 'Business-accountable for purpose, access rules, and approvals in the domain. Sets decision rights without working every ticket — steward and custodian deliver operational and technical execution.',
            ],
            'glossaryId' => 'data-owner',
            'pathId' => 'governance-foundations',
            'toolRoute' => 'tools.stakeholder-matrix',
            'storyPreferred' => 'data-product-owner-vs-data-owner',
            'storyFallback' => 'data-ownership-stewardship',
            'storyLabel' => [
                'de' => 'Product Owner vs Owner vs Steward',
                'en' => 'Product Owner vs Owner vs Steward',
            ],
            'extraStories' => [
                [
                    'preferred' => null,
                    'fallback' => 'data-ownership-stewardship',
                    'label' => ['de' => 'Ownership & Stewardship', 'en' => 'Ownership & stewardship'],
                ],
                [
                    'preferred' => 'raci-for-data-governance',
                    'fallback' => null,
                    'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance'],
                ],
                [
                    'preferred' => null,
                    'fallback' => 'kpi-metric-governance',
                    'label' => ['de' => 'KPI Governance', 'en' => 'KPI governance'],
                ],
            ],
        ],
        [
            'id' => 'product-owner',
            'order' => 25,
            'persona' => 'product-owner',
            'icon' => 'fa-cubes',
            'title' => ['de' => 'Data Product Owner', 'en' => 'Data Product Owner'],
            'focus' => [
                'de' => ['Lifecycle', 'Prioritäten', 'Consumer-Value'],
                'en' => ['Lifecycle', 'Priorities', 'Consumer value'],
            ],
            'lead' => [
                'de' => 'Priorisiert Scope, Roadmap und Consumer-Nutzen eines Data Products. Getrennt von Domain-Ownership (Zweck/Zugriff) und Stewardship (Definition/Qualität) — vermeidet, dass drei Hüte stillschweigend zusammenfallen.',
                'en' => 'Prioritizes scope, roadmap, and consumer value of a data product. Distinct from domain ownership (purpose/access) and stewardship (definition/quality) — avoids three hats collapsing into one person by default.',
            ],
            'glossaryId' => 'data-product',
            'pathId' => 'governance-foundations',
            'toolRoute' => 'tools.stakeholder-matrix',
            'storyPreferred' => 'data-product-owner-vs-data-owner',
            'storyFallback' => 'one-data-product-multiple-consumers',
            'storyLabel' => [
                'de' => 'Product Owner vs Owner vs Steward',
                'en' => 'Product Owner vs Owner vs Steward',
            ],
            'extraStories' => [
                [
                    'preferred' => null,
                    'fallback' => 'one-data-product-multiple-consumers',
                    'label' => ['de' => 'Ein Data Product, viele Consumer', 'en' => 'One data product, many consumers'],
                ],
                [
                    'preferred' => 'raci-for-data-governance',
                    'fallback' => null,
                    'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance'],
                ],
            ],
        ],
        [
            'id' => 'architect',
            'order' => 30,
            'persona' => 'architect',
            'icon' => 'fa-diagram-project',
            'title' => ['de' => 'Data Architect', 'en' => 'Data Architect'],
            'focus' => [
                'de' => ['Grain', 'Contracts', 'Modellkonsistenz'],
                'en' => ['Grain', 'Contracts', 'Model consistency'],
            ],
            'lead' => [
                'de' => 'Sichert Grain, Interface-Stabilität und architektonische Konsistenz über Domänen und Marts. Arbeitet mit Steward und Platform zusammen — ohne zum Bottleneck für jedes Ticket zu werden.',
                'en' => 'Secures grain, interface stability, and architectural consistency across domains and marts. Works with steward and platform — without becoming the bottleneck for every ticket.',
            ],
            'glossaryId' => 'data-architect',
            'pathId' => 'modernize-warehouse',
            'toolRoute' => 'tools.architecture-fit',
            'storyPreferred' => 'data-architect-role',
            'storyFallback' => 'operating-and-governing-the-platform',
            'storyLabel' => [
                'de' => 'Die Rolle Data Architect',
                'en' => 'The Data Architect role',
            ],
            'extraStories' => [
                [
                    'preferred' => 'raci-for-data-governance',
                    'fallback' => null,
                    'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance'],
                ],
                [
                    'preferred' => null,
                    'fallback' => 'operating-and-governing-the-platform',
                    'label' => ['de' => 'Platform betreiben', 'en' => 'Operating the platform'],
                ],
                [
                    'preferred' => null,
                    'fallback' => 'define-kpi',
                    'label' => ['de' => 'KPI Definition', 'en' => 'KPI definition'],
                ],
            ],
        ],
        [
            'id' => 'custodian',
            'order' => 40,
            'persona' => 'custodian',
            'icon' => 'fa-server',
            'title' => ['de' => 'Data Custodian', 'en' => 'Data Custodian'],
            'focus' => [
                'de' => ['Systeme', 'Speicher', 'Laufzeit'],
                'en' => ['Systems', 'Storage', 'Runtime'],
            ],
            'lead' => [
                'de' => 'Technische Obhut über Plattform, Speicher und Zugriffsmechanismen — Laufzeit, Backup, Rechteumsetzung. Führt Policy aus, setzt sie nicht fachlich — das bleibt Owner und Steward.',
                'en' => 'Technical custody of platform, storage, and access mechanisms — runtime, backup, rights enforcement. Executes policy; does not set business purpose — that stays with owner and steward.',
            ],
            'glossaryId' => 'data-custodian',
            'pathId' => 'modernize-warehouse',
            'toolRoute' => 'tools.architecture-fit',
            'storyPreferred' => null,
            'storyFallback' => 'data-ownership-stewardship',
            'storyLabel' => [
                'de' => 'Ownership & Stewardship',
                'en' => 'Ownership & stewardship',
            ],
            'extraStories' => [
                [
                    'preferred' => null,
                    'fallback' => 'operating-and-governing-the-platform',
                    'label' => ['de' => 'Platform betreiben', 'en' => 'Operating the platform'],
                ],
                [
                    'preferred' => 'raci-for-data-governance',
                    'fallback' => null,
                    'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance'],
                ],
            ],
        ],
        [
            'id' => 'consumer',
            'order' => 50,
            'persona' => 'consumer',
            'icon' => 'fa-chart-line',
            'title' => ['de' => 'Data Consumer', 'en' => 'Data Consumer'],
            'focus' => [
                'de' => ['Nutzen', 'Feedback', 'Gap-Meldung'],
                'en' => ['Use', 'Feedback', 'Gap signals'],
            ],
            'lead' => [
                'de' => 'Nutzt Data Products und Reports für Entscheidungen. Meldet Gaps und Qualitätsprobleme — entscheidet nicht allein über Definition, Zugriff oder Produkt-Roadmap, sondern speist Steward und Product Owner.',
                'en' => 'Uses data products and reports for decisions. Raises gaps and quality issues — does not alone decide definition, access, or product roadmap, but feeds steward and product owner.',
            ],
            'glossaryId' => 'data-consumer',
            'pathId' => 'governance-foundations',
            'toolRoute' => 'tools.report-inventory',
            'storyPreferred' => null,
            'storyFallback' => 'data-ownership-stewardship',
            'storyLabel' => [
                'de' => 'Ownership & Stewardship',
                'en' => 'Ownership & stewardship',
            ],
            'extraStories' => [
                [
                    'preferred' => null,
                    'fallback' => 'one-data-product-multiple-consumers',
                    'label' => ['de' => 'Ein Data Product, viele Consumer', 'en' => 'One data product, many consumers'],
                ],
                [
                    'preferred' => 'data-product-owner-vs-data-owner',
                    'fallback' => null,
                    'label' => ['de' => 'Product Owner vs Owner vs Steward', 'en' => 'Product Owner vs Owner vs Steward'],
                ],
            ],
        ],
    ],
];
