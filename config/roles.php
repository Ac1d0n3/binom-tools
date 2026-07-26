<?php

/**
 * Roles Hub — destination cards for governance personas.
 *
 * storyPreferred: future slugs from docs/story-gaps-roles.md
 * storyFallback: existing content until preferred exists
 */
return [
    'personas' => [
        'all' => ['de' => 'Alle Rollen', 'en' => 'All roles'],
        'steward' => ['de' => 'Data Steward', 'en' => 'Data Steward'],
        'owner' => ['de' => 'Data Owner', 'en' => 'Data Owner'],
        'architect' => ['de' => 'Data Architect', 'en' => 'Data Architect'],
        'custodian' => ['de' => 'Data Custodian', 'en' => 'Data Custodian'],
        'consumer' => ['de' => 'Data Consumer', 'en' => 'Data Consumer'],
    ],

    'roles' => [
        [
            'id' => 'steward',
            'order' => 10,
            'persona' => 'steward',
            'title' => ['de' => 'Data Steward', 'en' => 'Data Steward'],
            'lead' => [
                'de' => 'Operative Qualität, Definition und Nutzung in der Domäne — der Motor hinter Katalog und DQ.',
                'en' => 'Operational quality, definition, and use in the domain — the engine behind catalog and DQ.',
            ],
            'glossaryId' => 'data-steward',
            'pathId' => 'governance-foundations',
            'toolRoute' => 'tools.stakeholder-matrix',
            'storyPreferred' => 'stewardship-capacity',
            'storyFallback' => 'data-ownership-stewardship',
            'extraStories' => [
                ['preferred' => null, 'fallback' => 'missing-pieces-ownership-stewardship', 'label' => ['de' => 'Missing Pieces: Ownership', 'en' => 'Missing pieces: ownership']],
            ],
        ],
        [
            'id' => 'owner',
            'order' => 20,
            'persona' => 'owner',
            'title' => ['de' => 'Data Owner', 'en' => 'Data Owner'],
            'lead' => [
                'de' => 'Zweck, Zugriffsregeln und Freigaben — fachliche Accountable-Rolle ohne jeden Ticket-Detail.',
                'en' => 'Purpose, access rules, and approvals — business accountable role without every ticket detail.',
            ],
            'glossaryId' => 'data-owner',
            'pathId' => 'governance-foundations',
            'toolRoute' => 'tools.stakeholder-matrix',
            'storyPreferred' => 'data-product-owner-vs-data-owner',
            'storyFallback' => 'data-ownership-stewardship',
            'extraStories' => [
                ['preferred' => null, 'fallback' => 'kpi-metric-governance', 'label' => ['de' => 'KPI Governance', 'en' => 'KPI governance']],
            ],
        ],
        [
            'id' => 'architect',
            'order' => 30,
            'persona' => 'architect',
            'title' => ['de' => 'Data Architect', 'en' => 'Data Architect'],
            'lead' => [
                'de' => 'Grain, Contracts und Modellkonsistenz — damit Domänen und Marts zusammenpassen.',
                'en' => 'Grain, contracts, and model consistency — so domains and marts fit together.',
            ],
            'glossaryId' => 'data-architect',
            'pathId' => 'modernize-warehouse',
            'toolRoute' => 'tools.architecture-fit',
            'storyPreferred' => 'data-architect-role',
            'storyFallback' => 'operating-and-governing-the-platform',
            'extraStories' => [
                ['preferred' => null, 'fallback' => 'define-kpi', 'label' => ['de' => 'KPI Definition', 'en' => 'KPI definition']],
            ],
        ],
        [
            'id' => 'custodian',
            'order' => 40,
            'persona' => 'custodian',
            'title' => ['de' => 'Data Custodian', 'en' => 'Data Custodian'],
            'lead' => [
                'de' => 'Technische Obhut über Systeme und Speicher — Laufzeit, Zugriffspflege, Backup.',
                'en' => 'Technical custody of systems and storage — runtime, access upkeep, backup.',
            ],
            'glossaryId' => 'data-custodian',
            'pathId' => 'modernize-warehouse',
            'toolRoute' => 'tools.architecture-fit',
            'storyPreferred' => null,
            'storyFallback' => 'data-ownership-stewardship',
            'extraStories' => [
                ['preferred' => null, 'fallback' => 'operating-and-governing-the-platform', 'label' => ['de' => 'Platform betreiben', 'en' => 'Operating the platform']],
            ],
        ],
        [
            'id' => 'consumer',
            'order' => 50,
            'persona' => 'consumer',
            'title' => ['de' => 'Data Consumer', 'en' => 'Data Consumer'],
            'lead' => [
                'de' => 'Nutzt Produkte und Reports — meldet Gaps, entscheidet nicht allein über Definition und Zugriff.',
                'en' => 'Uses products and reports — raises gaps, does not alone decide definition and access.',
            ],
            'glossaryId' => 'data-consumer',
            'pathId' => 'governance-foundations',
            'toolRoute' => 'tools.report-inventory',
            'storyPreferred' => null,
            'storyFallback' => 'data-ownership-stewardship',
            'extraStories' => [
                ['preferred' => null, 'fallback' => 'one-data-product-multiple-consumers', 'label' => ['de' => 'Ein Data Product, viele Consumer', 'en' => 'One data product, many consumers']],
            ],
        ],
    ],
];
