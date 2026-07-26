<?php

/**
 * Learning Paths Hub — guided journeys by role/goal.
 *
 * Reuses stories, series, tools, glossary, and compliance as building blocks.
 */
return [
    'audiences' => [
        'privacy' => ['de' => 'Privacy / DPO', 'en' => 'Privacy / DPO'],
        'engineering' => ['de' => 'Analytics Engineering', 'en' => 'Analytics engineering'],
        'platform' => ['de' => 'Platform / Warehouse', 'en' => 'Platform / warehouse'],
        'governance' => ['de' => 'Governance Lead', 'en' => 'Governance lead'],
    ],

    'paths' => [
        [
            'id' => 'pii-in-five-steps',
            'order' => 10,
            'audienceId' => 'privacy',
            'sprintTemplateSlug' => 'learning-path-pii-in-five-steps',
            'roleIds' => ['steward', 'custodian', 'consumer'],
            'audience' => [
                'de' => 'Privacy, Stewardship, Plattform',
                'en' => 'Privacy, stewardship, platform',
            ],
            'duration' => [
                'de' => '≈ 1–2 Tage Orientierung',
                'en' => '≈ 1–2 days orientation',
            ],
            'title' => [
                'de' => 'PII in 5 Schritten',
                'en' => 'PII in 5 steps',
            ],
            'lead' => [
                'de' => 'Von Klassifikation über Masking bis DSDR — der kürzeste belastbare Einstieg in personenbezogene Daten.',
                'en' => 'From classification and masking to DSDR — the shortest solid entry into personal data governance.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Begriffe klären',
                        'en' => '1. Align on terms',
                    ],
                    'lead' => [
                        'de' => 'PII, DSDR, Retention und Masking in einer gemeinsamen Sprache.',
                        'en' => 'Shared language for PII, DSDR, retention, and masking.',
                    ],
                    'links' => [
                        ['type' => 'glossary', 'id' => 'pii', 'label' => ['de' => 'PII', 'en' => 'PII']],
                        ['type' => 'glossary', 'id' => 'dsdr', 'label' => ['de' => 'DSDR', 'en' => 'DSDR']],
                        ['type' => 'glossary', 'id' => 'masking', 'label' => ['de' => 'Masking', 'en' => 'Masking']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Story: Privacy Governance',
                        'en' => '2. Story: privacy governance',
                    ],
                    'lead' => [
                        'de' => 'Warum Klassifikation und Zweckbindung vor Tooling kommen.',
                        'en' => 'Why classification and purpose binding come before tooling.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'pii-privacy-governance', 'label' => ['de' => 'PII Privacy Governance', 'en' => 'PII privacy governance']],
                        ['type' => 'compliance', 'id' => 'gdpr', 'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Löschpfade denken',
                        'en' => '3. Design deletion paths',
                    ],
                    'lead' => [
                        'de' => 'DSDR braucht Lineage — sonst endet die Anfrage im Ticket-Chaos.',
                        'en' => 'DSDR needs lineage — otherwise requests die in ticket chaos.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'dsdr-governance', 'label' => ['de' => 'DSDR Governance', 'en' => 'DSDR governance']],
                        ['type' => 'glossary', 'id' => 'lineage', 'label' => ['de' => 'Lineage', 'en' => 'Lineage']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '4. Readiness prüfen',
                        'en' => '4. Check readiness',
                    ],
                    'lead' => [
                        'de' => 'Gaps sichtbar machen, bevor Policies in Prod landen.',
                        'en' => 'Surface gaps before policies hit production.',
                    ],
                    'links' => [
                        ['type' => 'tool', 'route' => 'tools.pii-dsdr-readiness-checker', 'label' => ['de' => 'PII/DSDR Readiness', 'en' => 'PII/DSDR readiness']],
                        ['type' => 'tool', 'route' => 'tools.pii-policy-generator', 'label' => ['de' => 'PII Policy Generator', 'en' => 'PII policy generator']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '5. In den Hub einordnen',
                        'en' => '5. Anchor in the hub',
                    ],
                    'lead' => [
                        'de' => 'Entscheidung, Stack und nächste Schritte im Governance Advisor festhalten.',
                        'en' => 'Capture decision, stack, and next steps in the Governance Advisor.',
                    ],
                    'links' => [
                        ['type' => 'route', 'route' => 'governance.index', 'label' => ['de' => 'Governance Hub', 'en' => 'Governance hub']],
                        ['type' => 'tool', 'route' => 'tools.vendor-learning-path-builder', 'label' => ['de' => 'Vendor Learning Path Builder', 'en' => 'Vendor learning path builder']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'dq-with-dbt',
            'order' => 20,
            'audienceId' => 'engineering',
            'sprintTemplateSlug' => 'learning-path-dq-with-dbt',
            'roleIds' => ['steward', 'architect'],
            'audience' => [
                'de' => 'Analytics Engineers, DQ-Owner',
                'en' => 'Analytics engineers, DQ owners',
            ],
            'duration' => [
                'de' => '≈ 2–3 Tage Praxis',
                'en' => '≈ 2–3 days practice',
            ],
            'title' => [
                'de' => 'DQ mit dbt',
                'en' => 'DQ with dbt',
            ],
            'lead' => [
                'de' => 'Von Tests und History bis zum Cockpit — Data Quality als Betriebsdisziplin, nicht als einmaliges Audit.',
                'en' => 'From tests and history to a cockpit — data quality as an operating discipline, not a one-off audit.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. DQ-Begriff und Ownership',
                        'en' => '1. DQ language and ownership',
                    ],
                    'lead' => [
                        'de' => 'Fitness for purpose, Steward und Contract vor dem ersten Test.',
                        'en' => 'Fitness for purpose, steward, and contract before the first test.',
                    ],
                    'links' => [
                        ['type' => 'glossary', 'id' => 'data-quality', 'label' => ['de' => 'Data Quality', 'en' => 'Data Quality']],
                        ['type' => 'glossary', 'id' => 'data-contract', 'label' => ['de' => 'Data Contract', 'en' => 'Data Contract']],
                        ['type' => 'story', 'id' => 'data-quality-governance', 'label' => ['de' => 'Data Quality Governance', 'en' => 'Data quality governance']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Serie: Operational Data Quality',
                        'en' => '2. Series: operational data quality',
                    ],
                    'lead' => [
                        'de' => 'KPIs, Plattform-Patterns und Remediation in einer durchgehenden Serie.',
                        'en' => 'KPIs, platform patterns, and remediation in one continuous series.',
                    ],
                    'links' => [
                        ['type' => 'series', 'id' => 'operational-data-quality', 'label' => ['de' => 'Operational Data Quality', 'en' => 'Operational data quality']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. dbt Artefakte erzeugen',
                        'en' => '3. Generate dbt artifacts',
                    ],
                    'lead' => [
                        'de' => 'Macros, Rules und History als copy-paste Startpunkte.',
                        'en' => 'Macros, rules, and history as copy-paste starting points.',
                    ],
                    'links' => [
                        ['type' => 'tool', 'route' => 'tools.dbt-dq-macro-generator', 'label' => ['de' => 'dbt DQ Macros', 'en' => 'dbt DQ macros']],
                        ['type' => 'tool', 'route' => 'tools.dbt-dq-rules-generator', 'label' => ['de' => 'dbt DQ Rules', 'en' => 'dbt DQ rules']],
                        ['type' => 'tool', 'route' => 'tools.dbt-dq-history-generator', 'label' => ['de' => 'dbt DQ History', 'en' => 'dbt DQ history']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '4. In den Betrieb überführen',
                        'en' => '4. Move into operations',
                    ],
                    'lead' => [
                        'de' => 'Sprint-Vorlagen und Advisor nutzen, damit DQ nicht in der Pipeline stecken bleibt.',
                        'en' => 'Use sprint templates and the advisor so DQ does not stall in the pipeline.',
                    ],
                    'links' => [
                        ['type' => 'route', 'route' => 'sprint-planner.index', 'label' => ['de' => 'Sprint Planner', 'en' => 'Sprint planner']],
                        ['type' => 'route', 'route' => 'governance.index', 'label' => ['de' => 'Governance Hub', 'en' => 'Governance hub']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'modernize-warehouse',
            'order' => 30,
            'audienceId' => 'platform',
            'sprintTemplateSlug' => 'learning-path-modernize-warehouse',
            'roleIds' => ['architect', 'custodian'],
            'audience' => [
                'de' => 'Platform, Architecture, BI Leads',
                'en' => 'Platform, architecture, BI leads',
            ],
            'duration' => [
                'de' => '≈ 1 Woche Lesepfad',
                'en' => '≈ 1 week reading path',
            ],
            'title' => [
                'de' => 'Warehouse modernisieren',
                'en' => 'Modernize the warehouse',
            ],
            'lead' => [
                'de' => 'Modern Data Warehouse mit Grain, Products und Governance — ohne Greenfield-Romantik.',
                'en' => 'Modern data warehouse with grain, products, and governance — without greenfield romanticism.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Zielbild und Grain',
                        'en' => '1. Target picture and grain',
                    ],
                    'lead' => [
                        'de' => 'Data Product und Grain klären, bevor Stack-Debatten starten.',
                        'en' => 'Clarify data product and grain before stack debates begin.',
                    ],
                    'links' => [
                        ['type' => 'glossary', 'id' => 'data-product', 'label' => ['de' => 'Data Product', 'en' => 'Data Product']],
                        ['type' => 'glossary', 'id' => 'grain', 'label' => ['de' => 'Grain', 'en' => 'Grain']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Serie: Modern Data Warehouse',
                        'en' => '2. Series: modern data warehouse',
                    ],
                    'lead' => [
                        'de' => 'Schichten, Marts und Betriebsmodell in zehn Teilen.',
                        'en' => 'Layers, marts, and operating model across ten parts.',
                    ],
                    'links' => [
                        ['type' => 'series', 'id' => 'building-modern-data-warehouse', 'label' => ['de' => 'Building a Modern Data Warehouse', 'en' => 'Building a modern data warehouse']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Quellen und Supplier-Muster',
                        'en' => '3. Sources and supplier patterns',
                    ],
                    'lead' => [
                        'de' => 'Kerndimensionen und PII-Hinweise aus der Supplier Library übernehmen.',
                        'en' => 'Reuse core dimensions and PII hints from the Supplier Library.',
                    ],
                    'links' => [
                        ['type' => 'route', 'route' => 'suppliers.index', 'label' => ['de' => 'Supplier Library', 'en' => 'Supplier library']],
                        ['type' => 'tool', 'route' => 'tools.governance-stack-advisor', 'label' => ['de' => 'Stack Advisor', 'en' => 'Stack advisor']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '4. End-to-End Governance anbinden',
                        'en' => '4. Attach end-to-end governance',
                    ],
                    'lead' => [
                        'de' => 'Warehouse ohne Ownership und Metadata bleibt ein teures Dateisystem.',
                        'en' => 'A warehouse without ownership and metadata stays an expensive filesystem.',
                    ],
                    'links' => [
                        ['type' => 'series', 'id' => 'end-to-end-data-governance', 'label' => ['de' => 'End-to-End Data Governance', 'en' => 'End-to-end data governance']],
                        ['type' => 'route', 'route' => 'governance.index', 'label' => ['de' => 'Governance Hub', 'en' => 'Governance hub']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'governance-foundations',
            'order' => 40,
            'audienceId' => 'governance',
            'sprintTemplateSlug' => 'learning-path-governance-foundations',
            'roleIds' => ['steward', 'owner', 'product-owner', 'architect', 'custodian', 'consumer'],
            'audience' => [
                'de' => 'Governance Leads, CoE, Consultants',
                'en' => 'Governance leads, CoE, consultants',
            ],
            'duration' => [
                'de' => '≈ 3–5 Tage Fundament',
                'en' => '≈ 3–5 days foundation',
            ],
            'title' => [
                'de' => 'Governance Foundations',
                'en' => 'Governance foundations',
            ],
            'lead' => [
                'de' => 'Säulen, Rollen und der Hub als Arbeitsfläche — bevor Zertifikate und Frameworks greifen.',
                'en' => 'Pillars, roles, and the hub as a workbench — before certificates and frameworks kick in.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Gemeinsame Sprache',
                        'en' => '1. Shared language',
                    ],
                    'lead' => [
                        'de' => 'Owner, Steward, Architect, Catalog und RACI als Einstieg.',
                        'en' => 'Owner, steward, architect, catalog, and RACI as the entry set.',
                    ],
                    'links' => [
                        ['type' => 'glossary', 'id' => 'data-owner', 'label' => ['de' => 'Data Owner', 'en' => 'Data Owner']],
                        ['type' => 'glossary', 'id' => 'data-steward', 'label' => ['de' => 'Data Steward', 'en' => 'Data Steward']],
                        ['type' => 'glossary', 'id' => 'data-architect', 'label' => ['de' => 'Data Architect', 'en' => 'Data Architect']],
                        ['type' => 'glossary', 'id' => 'raci', 'label' => ['de' => 'RACI', 'en' => 'RACI']],
                        ['type' => 'route', 'route' => 'roles.index', 'label' => ['de' => 'Roles Hub', 'en' => 'Roles hub']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Rollen & Decision Rights',
                        'en' => '2. Roles and decision rights',
                    ],
                    'lead' => [
                        'de' => 'Serie Roles Hub: Architect, RACI, Product Owner, Stewardship Capacity, CoE.',
                        'en' => 'Roles Hub series: architect, RACI, product owner, stewardship capacity, CoE.',
                    ],
                    'links' => [
                        ['type' => 'series', 'id' => 'roles-hub', 'label' => ['de' => 'Roles and Decision Rights', 'en' => 'Roles and decision rights']],
                        ['type' => 'story', 'id' => 'data-architect-role', 'label' => ['de' => 'Data Architect Role', 'en' => 'Data Architect role']],
                        ['type' => 'story', 'id' => 'raci-for-data-governance', 'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance']],
                        ['type' => 'story', 'id' => 'data-product-owner-vs-data-owner', 'label' => ['de' => 'Product Owner vs Owner vs Steward', 'en' => 'Product Owner vs Owner vs Steward']],
                        ['type' => 'story', 'id' => 'stewardship-capacity', 'label' => ['de' => 'Stewardship Capacity', 'en' => 'Stewardship capacity']],
                        ['type' => 'story', 'id' => 'governance-coe', 'label' => ['de' => 'Governance CoE', 'en' => 'Governance CoE']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Acht Säulen lesen',
                        'en' => '3. Read the eight pillars',
                    ],
                    'lead' => [
                        'de' => 'Das mentale Modell hinter Ownership, Metadata, PII, DQ und Lifecycle.',
                        'en' => 'The mental model behind ownership, metadata, PII, DQ, and lifecycle.',
                    ],
                    'links' => [
                        ['type' => 'series', 'id' => 'governance-pillars', 'label' => ['de' => '8 Säulen der Data Governance', 'en' => '8 pillars of data governance']],
                        ['type' => 'story', 'id' => 'eight-pillars', 'label' => ['de' => 'Overview: Eight Pillars', 'en' => 'Overview: eight pillars']],
                        ['type' => 'story', 'id' => 'data-ownership-stewardship', 'label' => ['de' => 'Ownership & Stewardship', 'en' => 'Ownership & stewardship']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '4. Hub & Radar nutzen',
                        'en' => '4. Use hub & radar',
                    ],
                    'lead' => [
                        'de' => 'Entscheidungen führen, News beobachten, Compliance verorten.',
                        'en' => 'Drive decisions, watch news, place compliance.',
                    ],
                    'links' => [
                        ['type' => 'route', 'route' => 'governance.index', 'label' => ['de' => 'Governance Hub', 'en' => 'Governance hub']],
                        ['type' => 'route', 'route' => 'governance.radar', 'label' => ['de' => 'Radar', 'en' => 'Radar']],
                        ['type' => 'route', 'route' => 'compliance.index', 'label' => ['de' => 'Compliance', 'en' => 'Compliance']],
                        ['type' => 'tool', 'route' => 'tools.stakeholder-matrix', 'label' => ['de' => 'Stakeholder & RACI Matrix', 'en' => 'Stakeholder & RACI matrix']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '5. Vertiefung wählen',
                        'en' => '5. Choose a deep dive',
                    ],
                    'lead' => [
                        'de' => 'Metadata Deep Dive, Missing Pieces oder PII-Pfad — je nach Reife.',
                        'en' => 'Metadata deep dive, missing pieces, or PII path — depending on maturity.',
                    ],
                    'links' => [
                        ['type' => 'series', 'id' => 'metadata-deep-dive', 'label' => ['de' => 'MetaData Deep Dive', 'en' => 'MetaData deep dive']],
                        ['type' => 'series', 'id' => 'missing-pieces', 'label' => ['de' => 'The Missing Pieces', 'en' => 'The missing pieces']],
                        ['type' => 'path', 'id' => 'pii-in-five-steps', 'label' => ['de' => 'Weiter: PII in 5 Schritten', 'en' => 'Next: PII in 5 steps']],
                    ],
                ],
            ],
        ],
    ],
];
