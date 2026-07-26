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
        'metrics' => ['de' => 'KPI / Metrics', 'en' => 'KPI / metrics'],
        'ai' => ['de' => 'AI / ML', 'en' => 'AI / ML'],
        'certification' => ['de' => 'Zertifizierung', 'en' => 'Certification'],
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
                        'de' => 'Metadata, Gaps, KPIs oder PII — je nach Reife und Druck.',
                        'en' => 'Metadata, gaps, KPIs, or PII — depending on maturity and pressure.',
                    ],
                    'links' => [
                        ['type' => 'path', 'id' => 'metadata-operating-model', 'label' => ['de' => 'Weiter: Metadata Operating Model', 'en' => 'Next: metadata operating model']],
                        ['type' => 'path', 'id' => 'close-the-gaps', 'label' => ['de' => 'Weiter: Gaps schließen', 'en' => 'Next: close the gaps']],
                        ['type' => 'path', 'id' => 'trusted-metrics', 'label' => ['de' => 'Weiter: Trusted Metrics', 'en' => 'Next: trusted metrics']],
                        ['type' => 'path', 'id' => 'pii-in-five-steps', 'label' => ['de' => 'Weiter: PII in 5 Schritten', 'en' => 'Next: PII in 5 steps']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'metadata-operating-model',
            'order' => 50,
            'audienceId' => 'platform',
            'sprintTemplateSlug' => 'learning-path-metadata-operating-model',
            'roleIds' => ['steward', 'architect', 'custodian'],
            'audience' => [
                'de' => 'Platform, Catalog, Stewardship',
                'en' => 'Platform, catalog, stewardship',
            ],
            'duration' => [
                'de' => '≈ 1 Woche Operating Model',
                'en' => '≈ 1 week operating model',
            ],
            'title' => [
                'de' => 'Metadata Operating Model',
                'en' => 'Metadata operating model',
            ],
            'lead' => [
                'de' => 'Von „was ist Metadata“ bis Produktbetrieb — Katalog, Lineage und Automation als Steuerungshebel.',
                'en' => 'From “what is metadata” to product ops — catalog, lineage, and automation as control levers.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Begriffe und Auftrag',
                        'en' => '1. Terms and mandate',
                    ],
                    'lead' => [
                        'de' => 'Metadata, Catalog und Lineage in einer gemeinsamen Sprache.',
                        'en' => 'Shared language for metadata, catalog, and lineage.',
                    ],
                    'links' => [
                        ['type' => 'glossary', 'id' => 'metadata', 'label' => ['de' => 'Metadata', 'en' => 'Metadata']],
                        ['type' => 'glossary', 'id' => 'data-catalog', 'label' => ['de' => 'Data Catalog', 'en' => 'Data Catalog']],
                        ['type' => 'glossary', 'id' => 'lineage', 'label' => ['de' => 'Lineage', 'en' => 'Lineage']],
                        ['type' => 'story', 'id' => 'what-metadata-actually-is', 'label' => ['de' => 'What Metadata Actually Is', 'en' => 'What metadata actually is']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Serie: MetaData Deep Dive',
                        'en' => '2. Series: MetaData deep dive',
                    ],
                    'lead' => [
                        'de' => 'Geburt, Harvest, Modell, Qualität und Automation in einer Serie.',
                        'en' => 'Birth, harvest, model, quality, and automation in one series.',
                    ],
                    'links' => [
                        ['type' => 'series', 'id' => 'metadata-deep-dive', 'label' => ['de' => 'MetaData Deep Dive', 'en' => 'MetaData deep dive']],
                        ['type' => 'story', 'id' => 'operate-metadata-as-a-product', 'label' => ['de' => 'Metadata als Produkt betreiben', 'en' => 'Operate metadata as a product']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Governance steuern',
                        'en' => '3. Steer with governance metadata',
                    ],
                    'lead' => [
                        'de' => 'Metadata, die Policies und dbt meta steuern — nicht nur beschreiben.',
                        'en' => 'Metadata that steers policies and dbt meta — not only describes.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'governance-metadata-that-controls-data', 'label' => ['de' => 'Governance Metadata', 'en' => 'Governance metadata']],
                        ['type' => 'story', 'id' => 'metadata-driven-governance-with-dbt-meta', 'label' => ['de' => 'dbt meta Governance', 'en' => 'dbt meta governance']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '4. Hub und nächste Pfade',
                        'en' => '4. Hub and next paths',
                    ],
                    'lead' => [
                        'de' => 'Entscheidung festhalten, Gaps und PII anschließen.',
                        'en' => 'Capture the decision, then connect gaps and PII.',
                    ],
                    'links' => [
                        ['type' => 'route', 'route' => 'governance.index', 'label' => ['de' => 'Governance Hub', 'en' => 'Governance hub']],
                        ['type' => 'path', 'id' => 'close-the-gaps', 'label' => ['de' => 'Weiter: Gaps schließen', 'en' => 'Next: close the gaps']],
                        ['type' => 'path', 'id' => 'pii-in-five-steps', 'label' => ['de' => 'Weiter: PII in 5 Schritten', 'en' => 'Next: PII in 5 steps']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'trusted-metrics',
            'order' => 60,
            'audienceId' => 'metrics',
            'sprintTemplateSlug' => 'learning-path-trusted-metrics',
            'roleIds' => ['steward', 'owner', 'product-owner', 'architect'],
            'audience' => [
                'de' => 'Stewards, Product Owner, BI Leads',
                'en' => 'Stewards, product owners, BI leads',
            ],
            'duration' => [
                'de' => '≈ 2–4 Tage Metric Ops',
                'en' => '≈ 2–4 days metric ops',
            ],
            'title' => [
                'de' => 'Trusted Metrics',
                'en' => 'Trusted metrics',
            ],
            'lead' => [
                'de' => 'KPI-Definition, Owner und Change-Prozess — damit Zahlen in Tools und Meetings nicht auseinanderlaufen.',
                'en' => 'KPI definition, owner, and change process — so numbers stop diverging across tools and meetings.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. KPI-Sprache',
                        'en' => '1. KPI language',
                    ],
                    'lead' => [
                        'de' => 'Definition, Owner und Decision Rights für Kennzahlen.',
                        'en' => 'Definition, owner, and decision rights for metrics.',
                    ],
                    'links' => [
                        ['type' => 'glossary', 'id' => 'kpi-governance', 'label' => ['de' => 'KPI Governance', 'en' => 'KPI Governance']],
                        ['type' => 'story', 'id' => 'kpi-metric-governance', 'label' => ['de' => 'KPI Metric Governance', 'en' => 'KPI metric governance']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Contract schreiben',
                        'en' => '2. Write the contract',
                    ],
                    'lead' => [
                        'de' => 'Grain, Formel und Steward im KPI-Contract festhalten.',
                        'en' => 'Capture grain, formula, and steward in the KPI contract.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'define-kpi', 'label' => ['de' => 'KPI definieren', 'en' => 'Define a KPI']],
                        ['type' => 'tool', 'route' => 'tools.kpi-definition', 'label' => ['de' => 'KPI Definition', 'en' => 'KPI definition']],
                        ['type' => 'tool', 'route' => 'tools.kpi-requirements-intake', 'label' => ['de' => 'KPI Requirements Intake', 'en' => 'KPI requirements intake']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Missing Piece: Trusted Metrics',
                        'en' => '3. Missing piece: trusted metrics',
                    ],
                    'lead' => [
                        'de' => 'Warum Definitionen allein nicht reichen — Betrieb und Eskalation.',
                        'en' => 'Why definitions alone are not enough — operations and escalation.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'missing-pieces-trusted-metrics', 'label' => ['de' => 'Missing Pieces: Trusted Metrics', 'en' => 'Missing pieces: trusted metrics']],
                        ['type' => 'glossary', 'id' => 'data-steward', 'label' => ['de' => 'Data Steward', 'en' => 'Data Steward']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '4. Hub und Rollen',
                        'en' => '4. Hub and roles',
                    ],
                    'lead' => [
                        'de' => 'Decision Rights und nächsten Delivery-Schritt klären.',
                        'en' => 'Clarify decision rights and the next delivery link.',
                    ],
                    'links' => [
                        ['type' => 'route', 'route' => 'roles.index', 'label' => ['de' => 'Roles Hub', 'en' => 'Roles hub']],
                        ['type' => 'route', 'route' => 'governance.index', 'label' => ['de' => 'Governance Hub', 'en' => 'Governance hub']],
                        ['type' => 'route', 'route' => 'sprint-planner.index', 'label' => ['de' => 'Sprint Planner', 'en' => 'Sprint planner']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'close-the-gaps',
            'order' => 70,
            'audienceId' => 'governance',
            'sprintTemplateSlug' => 'learning-path-close-the-gaps',
            'roleIds' => ['steward', 'owner', 'architect', 'custodian'],
            'audience' => [
                'de' => 'Governance Leads, CoE, Consultants',
                'en' => 'Governance leads, CoE, consultants',
            ],
            'duration' => [
                'de' => '≈ 3–5 Tage Gap Review',
                'en' => '≈ 3–5 days gap review',
            ],
            'title' => [
                'de' => 'Gaps schließen',
                'en' => 'Close the gaps',
            ],
            'lead' => [
                'de' => 'Die Missing Pieces als Diagnose — Ownership, Metadata, DQ, Metrics, Access und Lifecycle priorisieren.',
                'en' => 'The missing pieces as diagnosis — prioritize ownership, metadata, DQ, metrics, access, and lifecycle.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Serie: The Missing Pieces',
                        'en' => '1. Series: the missing pieces',
                    ],
                    'lead' => [
                        'de' => 'Überblick, wo Governance typischerweise stecken bleibt.',
                        'en' => 'Overview of where governance typically stalls.',
                    ],
                    'links' => [
                        ['type' => 'series', 'id' => 'missing-pieces', 'label' => ['de' => 'The Missing Pieces', 'en' => 'The missing pieces']],
                        ['type' => 'path', 'id' => 'governance-foundations', 'label' => ['de' => 'Voraussetzung: Foundations', 'en' => 'Prerequisite: foundations']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Ownership und Capacity',
                        'en' => '2. Ownership and capacity',
                    ],
                    'lead' => [
                        'de' => 'Rollen ohne Capacity bleiben Folien — Gaps benennen.',
                        'en' => 'Roles without capacity stay slides — name the gaps.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'missing-pieces-ownership-stewardship', 'label' => ['de' => 'Missing Pieces: Ownership', 'en' => 'Missing pieces: ownership']],
                        ['type' => 'story', 'id' => 'stewardship-capacity', 'label' => ['de' => 'Stewardship Capacity', 'en' => 'Stewardship capacity']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Metadata, DQ, Metrics, Access',
                        'en' => '3. Metadata, DQ, metrics, access',
                    ],
                    'lead' => [
                        'de' => 'Die operativen Missing Pieces priorisieren.',
                        'en' => 'Prioritize the operational missing pieces.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'missing-pieces-metadata-catalog-lineage', 'label' => ['de' => 'Missing Pieces: Metadata', 'en' => 'Missing pieces: metadata']],
                        ['type' => 'story', 'id' => 'missing-pieces-data-quality', 'label' => ['de' => 'Missing Pieces: DQ', 'en' => 'Missing pieces: DQ']],
                        ['type' => 'story', 'id' => 'missing-pieces-trusted-metrics', 'label' => ['de' => 'Missing Pieces: Metrics', 'en' => 'Missing pieces: metrics']],
                        ['type' => 'story', 'id' => 'missing-pieces-policy-access-governance', 'label' => ['de' => 'Missing Pieces: Access', 'en' => 'Missing pieces: access']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '4. Lifecycle und nächster Pfad',
                        'en' => '4. Lifecycle and next path',
                    ],
                    'lead' => [
                        'de' => 'Retirement und Retention mitdenken — dann einen Vertiefungspfad wählen.',
                        'en' => 'Include retirement and retention — then pick a deep-dive path.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'missing-pieces-data-lifecycle-retirement', 'label' => ['de' => 'Missing Pieces: Lifecycle', 'en' => 'Missing pieces: lifecycle']],
                        ['type' => 'path', 'id' => 'metadata-operating-model', 'label' => ['de' => 'Weiter: Metadata', 'en' => 'Next: metadata']],
                        ['type' => 'path', 'id' => 'dq-with-dbt', 'label' => ['de' => 'Weiter: DQ mit dbt', 'en' => 'Next: DQ with dbt']],
                        ['type' => 'path', 'id' => 'trusted-metrics', 'label' => ['de' => 'Weiter: Trusted Metrics', 'en' => 'Next: trusted metrics']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'ai-foundations',
            'order' => 80,
            'audienceId' => 'ai',
            'sprintTemplateSlug' => 'learning-path-ai-foundations',
            'roleIds' => ['architect', 'steward', 'custodian'],
            'audience' => [
                'de' => 'Architects, Platform, Governance',
                'en' => 'Architects, platform, governance',
            ],
            'duration' => [
                'de' => '≈ 2–3 Tage Orientierung',
                'en' => '≈ 2–3 days orientation',
            ],
            'title' => [
                'de' => 'AI Foundations',
                'en' => 'AI foundations',
            ],
            'lead' => [
                'de' => 'Grundlagen, Risiken und Governance für AI — bevor RAG und Agents auf ungepflegte Metadata treffen.',
                'en' => 'Basics, risks, and governance for AI — before RAG and agents meet unmanaged metadata.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Serie: AI Foundations',
                        'en' => '1. Series: AI foundations',
                    ],
                    'lead' => [
                        'de' => 'Basics, Models, Agents, Eval und typische Failure Modes.',
                        'en' => 'Basics, models, agents, eval, and typical failure modes.',
                    ],
                    'links' => [
                        ['type' => 'series', 'id' => 'ai-foundations', 'label' => ['de' => 'AI Foundations', 'en' => 'AI foundations']],
                        ['type' => 'story', 'id' => 'ai-basics', 'label' => ['de' => 'AI Basics', 'en' => 'AI basics']],
                        ['type' => 'story', 'id' => 'ai-failures', 'label' => ['de' => 'AI Failures', 'en' => 'AI failures']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Governance und Eval',
                        'en' => '2. Governance and eval',
                    ],
                    'lead' => [
                        'de' => 'Guardrails und Messbarkeit, bevor Produktivverkehr startet.',
                        'en' => 'Guardrails and measurability before production traffic starts.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'ai-gov', 'label' => ['de' => 'AI Governance', 'en' => 'AI governance']],
                        ['type' => 'story', 'id' => 'ai-eval', 'label' => ['de' => 'AI Eval', 'en' => 'AI eval']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Metadata für AI vorbereiten',
                        'en' => '3. Prepare metadata for AI',
                    ],
                    'lead' => [
                        'de' => 'RAG und Training brauchen pflegbare Metadata — nicht nur ein Vector Store.',
                        'en' => 'RAG and training need maintainable metadata — not just a vector store.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'prepare-metadata-for-ai-rag-and-model-training', 'label' => ['de' => 'Metadata für AI / RAG', 'en' => 'Metadata for AI / RAG']],
                        ['type' => 'path', 'id' => 'metadata-operating-model', 'label' => ['de' => 'Weiter: Metadata Operating Model', 'en' => 'Next: metadata operating model']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '4. Hub und Vendor-Lernen',
                        'en' => '4. Hub and vendor learning',
                    ],
                    'lead' => [
                        'de' => 'Entscheidung im Hub festhalten, Stack-Lernen optional ergänzen.',
                        'en' => 'Capture the decision in the hub; optionally add stack learning.',
                    ],
                    'links' => [
                        ['type' => 'route', 'route' => 'governance.index', 'label' => ['de' => 'Governance Hub', 'en' => 'Governance hub']],
                        ['type' => 'tool', 'route' => 'tools.vendor-learning-path-builder', 'label' => ['de' => 'Vendor Learning Path Builder', 'en' => 'Vendor learning path builder']],
                        ['type' => 'path', 'id' => 'cert-project-evidence', 'label' => ['de' => 'Weiter: Zertifizierung mit Projekterfolg', 'en' => 'Next: certification with project evidence']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'access-security-ops',
            'order' => 90,
            'audienceId' => 'privacy',
            'sprintTemplateSlug' => 'learning-path-access-security-ops',
            'roleIds' => ['owner', 'steward', 'custodian'],
            'audience' => [
                'de' => 'Privacy, Stewardship, Platform',
                'en' => 'Privacy, stewardship, platform',
            ],
            'duration' => [
                'de' => '≈ 2–4 Tage Access Ops',
                'en' => '≈ 2–4 days access ops',
            ],
            'title' => [
                'de' => 'Access & Security Ops',
                'en' => 'Access & security ops',
            ],
            'lead' => [
                'de' => 'Zugriffsregeln, Masking und Policy-Betrieb — damit Security nicht nur in Folien und Tickets lebt.',
                'en' => 'Access rules, masking, and policy operations — so security does not live only in slides and tickets.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Access-Governance lesen',
                        'en' => '1. Read access governance',
                    ],
                    'lead' => [
                        'de' => 'Policy, Rollen und typische Failure Modes für Zugriff.',
                        'en' => 'Policy, roles, and typical access failure modes.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'access-security-governance', 'label' => ['de' => 'Access & Security Governance', 'en' => 'Access & security governance']],
                        ['type' => 'story', 'id' => 'missing-pieces-policy-access-governance', 'label' => ['de' => 'Missing Pieces: Access', 'en' => 'Missing pieces: access']],
                        ['type' => 'glossary', 'id' => 'masking', 'label' => ['de' => 'Masking', 'en' => 'Masking']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Masking in der Praxis',
                        'en' => '2. Masking in practice',
                    ],
                    'lead' => [
                        'de' => 'Policy-gesteuertes Masking und Section Access als Betriebsmuster.',
                        'en' => 'Policy-driven masking and section access as operating patterns.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'snowflake-masking-policies-qlik-section-access', 'label' => ['de' => 'Masking & Section Access', 'en' => 'Masking & section access']],
                        ['type' => 'tool', 'route' => 'tools.pii-policy-generator', 'label' => ['de' => 'PII Policy Generator', 'en' => 'PII policy generator']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Decision Rights klären',
                        'en' => '3. Clarify decision rights',
                    ],
                    'lead' => [
                        'de' => 'Wer freigibt, wer umsetzt, wer nur informiert wird.',
                        'en' => 'Who approves, who implements, who is only informed.',
                    ],
                    'links' => [
                        ['type' => 'tool', 'route' => 'tools.stakeholder-matrix', 'label' => ['de' => 'Stakeholder & RACI Matrix', 'en' => 'Stakeholder & RACI matrix']],
                        ['type' => 'route', 'route' => 'roles.index', 'label' => ['de' => 'Roles Hub', 'en' => 'Roles hub']],
                        ['type' => 'path', 'id' => 'pii-in-five-steps', 'label' => ['de' => 'Verwandt: PII in 5 Schritten', 'en' => 'Related: PII in 5 steps']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'end-to-end-governance',
            'order' => 100,
            'audienceId' => 'governance',
            'sprintTemplateSlug' => 'learning-path-end-to-end-governance',
            'roleIds' => ['steward', 'architect', 'owner', 'custodian'],
            'audience' => [
                'de' => 'Governance Leads, Architects, Platform',
                'en' => 'Governance leads, architects, platform',
            ],
            'duration' => [
                'de' => '≈ 1 Woche End-to-End',
                'en' => '≈ 1 week end-to-end',
            ],
            'title' => [
                'de' => 'End-to-End Governance',
                'en' => 'End-to-end governance',
            ],
            'lead' => [
                'de' => 'Architektur, dbt meta, Raw-Automation und Masking als durchgehende Steuerungskette — nicht als Einzeltools.',
                'en' => 'Architecture, dbt meta, raw automation, and masking as one control chain — not isolated tools.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Zielbild der Kette',
                        'en' => '1. Target picture of the chain',
                    ],
                    'lead' => [
                        'de' => 'Wie Governance von Source bis Consumer steuert.',
                        'en' => 'How governance steers from source to consumer.',
                    ],
                    'links' => [
                        ['type' => 'series', 'id' => 'end-to-end-data-governance', 'label' => ['de' => 'End-to-End Data Governance', 'en' => 'End-to-end data governance']],
                        ['type' => 'story', 'id' => 'end-to-end-governance-architecture', 'label' => ['de' => 'E2E Governance Architecture', 'en' => 'E2E governance architecture']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Metadata steuert Runtime',
                        'en' => '2. Metadata steers runtime',
                    ],
                    'lead' => [
                        'de' => 'dbt meta und Automation als Operating Practice.',
                        'en' => 'dbt meta and automation as operating practice.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'metadata-driven-governance-with-dbt-meta', 'label' => ['de' => 'dbt meta Governance', 'en' => 'dbt meta governance']],
                        ['type' => 'story', 'id' => 'automatic-raw-generation-using-dbt-macros', 'label' => ['de' => 'Raw Generation mit dbt Macros', 'en' => 'Raw generation with dbt macros']],
                        ['type' => 'path', 'id' => 'metadata-operating-model', 'label' => ['de' => 'Verwandt: Metadata Operating Model', 'en' => 'Related: metadata operating model']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. PII entlang der Pipeline',
                        'en' => '3. PII along the pipeline',
                    ],
                    'lead' => [
                        'de' => 'Klassifikation und Masking folgen der Lineage.',
                        'en' => 'Classification and masking follow lineage.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'propagating-pii-metadata-across-data-warehouses', 'label' => ['de' => 'PII-Metadaten propagieren', 'en' => 'Propagating PII metadata']],
                        ['type' => 'path', 'id' => 'pii-in-five-steps', 'label' => ['de' => 'Weiter: PII in 5 Schritten', 'en' => 'Next: PII in 5 steps']],
                        ['type' => 'route', 'route' => 'governance.index', 'label' => ['de' => 'Governance Hub', 'en' => 'Governance hub']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'simplest-viable-stack',
            'order' => 110,
            'audienceId' => 'platform',
            'sprintTemplateSlug' => 'learning-path-simplest-viable-stack',
            'roleIds' => ['architect', 'custodian', 'product-owner'],
            'audience' => [
                'de' => 'Architecture, Platform, BI Leads',
                'en' => 'Architecture, platform, BI leads',
            ],
            'duration' => [
                'de' => '≈ 2–3 Tage Stack-Entscheidung',
                'en' => '≈ 2–3 days stack decision',
            ],
            'title' => [
                'de' => 'Simplest Viable Stack',
                'en' => 'Simplest viable stack',
            ],
            'lead' => [
                'de' => 'Die einfachste belastbare Architektur wählen — bevor Bronze/Silver/Gold oder Tool-Listen die Debatte übernehmen.',
                'en' => 'Choose the simplest solid architecture — before bronze/silver/gold or tool lists take over the debate.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Einfachheit als Designziel',
                        'en' => '1. Simplicity as a design goal',
                    ],
                    'lead' => [
                        'de' => 'Was „viable“ heißt, bevor Features und Vendors kommen.',
                        'en' => 'What “viable” means before features and vendors arrive.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'choosing-the-simplest-viable-architecture', 'label' => ['de' => 'Simplest Viable Architecture', 'en' => 'Simplest viable architecture']],
                        ['type' => 'story', 'id' => 'beyond-bronze-silver-gold', 'label' => ['de' => 'Beyond Bronze / Silver / Gold', 'en' => 'Beyond bronze / silver / gold']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Fit prüfen',
                        'en' => '2. Check fit',
                    ],
                    'lead' => [
                        'de' => 'Stack und Constraints mit Advisor und Architecture Fit absichern.',
                        'en' => 'Validate stack and constraints with advisor and architecture fit.',
                    ],
                    'links' => [
                        ['type' => 'tool', 'route' => 'tools.architecture-fit', 'label' => ['de' => 'Architecture Fit', 'en' => 'Architecture fit']],
                        ['type' => 'tool', 'route' => 'tools.governance-stack-advisor', 'label' => ['de' => 'Stack Advisor', 'en' => 'Stack advisor']],
                        ['type' => 'tool', 'route' => 'tools.decision-brief-generator', 'label' => ['de' => 'Decision Brief', 'en' => 'Decision brief']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. In Modernisierung überführen',
                        'en' => '3. Move into modernization',
                    ],
                    'lead' => [
                        'de' => 'Entscheidung festhalten und Warehouse-Pfad starten.',
                        'en' => 'Capture the decision and start the warehouse path.',
                    ],
                    'links' => [
                        ['type' => 'path', 'id' => 'modernize-warehouse', 'label' => ['de' => 'Weiter: Warehouse modernisieren', 'en' => 'Next: modernize the warehouse']],
                        ['type' => 'route', 'route' => 'governance.index', 'label' => ['de' => 'Governance Hub', 'en' => 'Governance hub']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'cert-project-evidence',
            'order' => 200,
            'audienceId' => 'certification',
            'sprintTemplateSlug' => 'governance-learning-path-certification',
            'roleIds' => ['steward', 'architect', 'custodian', 'product-owner'],
            'audience' => [
                'de' => 'Teams mit laufendem Governance-Projekt',
                'en' => 'Teams with an active governance project',
            ],
            'duration' => [
                'de' => '≈ 4 Wochen parallel zum Projekt',
                'en' => '≈ 4 weeks parallel to the project',
            ],
            'title' => [
                'de' => 'Cert + Projekt parallel',
                'en' => 'Cert + project in parallel',
            ],
            'lead' => [
                'de' => 'Wenn mehrere Zertifikate neben Delivery laufen: gemeinsamer Begleitplan für Übungen, Evidence und Transfer — ohne dass Lernen vom Projekt abkoppelt.',
                'en' => 'When several certificates run beside delivery: a shared companion plan for exercises, evidence, and transfer — so learning does not detach from the project.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Stack und Sprache',
                        'en' => '1. Stack and language',
                    ],
                    'lead' => [
                        'de' => 'Ziel-Stack, Rollen und Governance-Vokabular mit dem Projekt angleichen.',
                        'en' => 'Align target stack, roles, and governance vocabulary with the project.',
                    ],
                    'links' => [
                        ['type' => 'story', 'id' => 'end-to-end-governance-architecture', 'label' => ['de' => 'E2E Governance Architecture', 'en' => 'E2E governance architecture']],
                        ['type' => 'path', 'id' => 'governance-foundations', 'label' => ['de' => 'Foundations', 'en' => 'Foundations']],
                        ['type' => 'tool', 'route' => 'tools.vendor-learning-path-builder', 'label' => ['de' => 'Vendor Learning Path Builder', 'en' => 'Vendor learning path builder']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Konkrete Cert-Begleiter wählen',
                        'en' => '2. Pick concrete cert companions',
                    ],
                    'lead' => [
                        'de' => 'dbt, Fabric/Power BI oder andere Tracks — jeweils mit eigenem Wochenplan.',
                        'en' => 'dbt, Fabric/Power BI, or other tracks — each with its own week plan.',
                    ],
                    'links' => [
                        ['type' => 'path', 'id' => 'cert-dbt-analytics-engineer', 'label' => ['de' => 'dbt Cert Begleiter', 'en' => 'dbt cert companion']],
                        ['type' => 'path', 'id' => 'cert-fabric-power-bi', 'label' => ['de' => 'Fabric / Power BI Cert Begleiter', 'en' => 'Fabric / Power BI cert companion']],
                        ['type' => 'path', 'id' => 'dq-with-dbt', 'label' => ['de' => 'Praxis: DQ mit dbt', 'en' => 'Practice: DQ with dbt']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Evidence und Transfer',
                        'en' => '3. Evidence and transfer',
                    ],
                    'lead' => [
                        'de' => 'Exam-Termine, Übungsnachweise und Projekt-Transfer in einem Plan führen.',
                        'en' => 'Track exam dates, exercise evidence, and project transfer in one plan.',
                    ],
                    'links' => [
                        ['type' => 'route', 'route' => 'resources.index', 'label' => ['de' => 'Vendor Resources & Certificates', 'en' => 'Vendor resources & certificates']],
                        ['type' => 'route', 'route' => 'sprint-planner.index', 'label' => ['de' => 'Sprint Planner', 'en' => 'Sprint planner']],
                        ['type' => 'compliance', 'id' => 'snowflake-dbt', 'label' => ['de' => 'Compliance: Snowflake / dbt Certs', 'en' => 'Compliance: Snowflake / dbt certs']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'cert-dbt-analytics-engineer',
            'order' => 210,
            'audienceId' => 'certification',
            'sprintTemplateSlug' => 'learning-path-cert-dbt-analytics-engineer',
            'roleIds' => ['steward', 'architect'],
            'audience' => [
                'de' => 'Wer die dbt-Zertifizierung macht',
                'en' => 'People taking the dbt certification',
            ],
            'duration' => [
                'de' => '≈ 4 Wochen Cert-Begleitung',
                'en' => '≈ 4 weeks cert companion',
            ],
            'title' => [
                'de' => 'dbt Cert Begleiter',
                'en' => 'dbt cert companion',
            ],
            'lead' => [
                'de' => 'Du machst die offizielle dbt-Zertifizierung — dieser Pfad und der Sprint-Plan begleiten dich Woche für Woche: Learn → Labs → Exam → Transfer ins Projekt.',
                'en' => 'You are taking the official dbt certification — this path and sprint plan accompany you week by week: learn → labs → exam → transfer into the project.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Offiziell starten',
                        'en' => '1. Start officially',
                    ],
                    'lead' => [
                        'de' => 'Cert-Seite, Learn-Kurse und Exam-Termin festlegen — der Begleitplan übernimmt die Wochenstruktur.',
                        'en' => 'Lock cert page, Learn courses, and exam date — the companion plan owns the week structure.',
                    ],
                    'links' => [
                        ['type' => 'external', 'href' => 'https://www.getdbt.com/certifications', 'label' => ['de' => 'dbt Certifications (offiziell)', 'en' => 'dbt Certifications (official)']],
                        ['type' => 'external', 'href' => 'https://learn.getdbt.com/courses/analytics-engineering', 'label' => ['de' => 'Analytics Engineering Certificate', 'en' => 'Analytics Engineering Certificate']],
                        ['type' => 'external', 'href' => 'https://learn.getdbt.com/courses/dbt-fundamentals', 'label' => ['de' => 'dbt Fundamentals', 'en' => 'dbt Fundamentals']],
                        ['type' => 'external', 'href' => 'https://learn.getdbt.com/', 'label' => ['de' => 'dbt Learn', 'en' => 'dbt Learn']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Domains lernen',
                        'en' => '2. Study the domains',
                    ],
                    'lead' => [
                        'de' => 'Modelle, Tests, Contracts und Meta — offizielle Docs plus unsere Governance-Stories.',
                        'en' => 'Models, tests, contracts, and meta — official docs plus our governance stories.',
                    ],
                    'links' => [
                        ['type' => 'external', 'href' => 'https://docs.getdbt.com/docs/build/data-tests', 'label' => ['de' => 'Docs: Data Tests', 'en' => 'Docs: data tests']],
                        ['type' => 'external', 'href' => 'https://docs.getdbt.com/docs/collaborate/govern/model-contracts', 'label' => ['de' => 'Docs: Model Contracts', 'en' => 'Docs: model contracts']],
                        ['type' => 'external', 'href' => 'https://docs.getdbt.com/reference/resource-configs/meta', 'label' => ['de' => 'Docs: Meta Config', 'en' => 'Docs: meta config']],
                        ['type' => 'story', 'id' => 'dbt-role', 'label' => ['de' => 'Story: dbt Role', 'en' => 'Story: dbt role']],
                        ['type' => 'story', 'id' => 'metadata-driven-governance-with-dbt-meta', 'label' => ['de' => 'Story: dbt meta Governance', 'en' => 'Story: dbt meta governance']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Labs parallel zum Lernen',
                        'en' => '3. Labs beside the coursework',
                    ],
                    'lead' => [
                        'de' => 'Übungen und Generator-Artefakte als Exam- und Projekt-Evidence.',
                        'en' => 'Exercises and generator artifacts as exam and project evidence.',
                    ],
                    'links' => [
                        ['type' => 'path', 'id' => 'dq-with-dbt', 'label' => ['de' => 'Praxispfad: DQ mit dbt', 'en' => 'Practice path: DQ with dbt']],
                        ['type' => 'tool', 'route' => 'tools.dbt-dq-macro-generator', 'label' => ['de' => 'dbt DQ Macros', 'en' => 'dbt DQ macros']],
                        ['type' => 'tool', 'route' => 'tools.dbt-dq-rules-generator', 'label' => ['de' => 'dbt DQ Rules', 'en' => 'dbt DQ rules']],
                        ['type' => 'tool', 'route' => 'tools.dbt-governance-macro-generator', 'label' => ['de' => 'dbt Governance Macros', 'en' => 'dbt governance macros']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '4. Exam und Transfer',
                        'en' => '4. Exam and transfer',
                    ],
                    'lead' => [
                        'de' => 'Termin wahrnehmen, Nachweise sichern, Gelerntes zurück ins Projekt bringen.',
                        'en' => 'Sit the exam, secure evidence, bring learning back into the project.',
                    ],
                    'links' => [
                        ['type' => 'route', 'route' => 'resources.index', 'label' => ['de' => 'Resources: dbt Certs & Docs', 'en' => 'Resources: dbt certs & docs']],
                        ['type' => 'compliance', 'id' => 'snowflake-dbt', 'label' => ['de' => 'Compliance-Roadmap: dbt/Snowflake', 'en' => 'Compliance roadmap: dbt/Snowflake']],
                        ['type' => 'path', 'id' => 'cert-project-evidence', 'label' => ['de' => 'Optional: Cert + Projekt parallel', 'en' => 'Optional: cert + project in parallel']],
                    ],
                ],
            ],
        ],
        [
            'id' => 'cert-fabric-power-bi',
            'order' => 220,
            'audienceId' => 'certification',
            'sprintTemplateSlug' => 'learning-path-cert-fabric-power-bi',
            'roleIds' => ['architect', 'consumer', 'product-owner', 'custodian'],
            'audience' => [
                'de' => 'Wer DP-600 / PL-300 macht',
                'en' => 'People taking DP-600 / PL-300',
            ],
            'duration' => [
                'de' => '≈ 4 Wochen Cert-Begleitung',
                'en' => '≈ 4 weeks cert companion',
            ],
            'title' => [
                'de' => 'Fabric / Power BI Cert Begleiter',
                'en' => 'Fabric / Power BI cert companion',
            ],
            'lead' => [
                'de' => 'Du gehst auf DP-600 (Fabric Analytics Engineer) und/oder PL-300 (Power BI Data Analyst) — dieser Pfad und Sprint-Plan begleiten Lernen, Labs und Exam.',
                'en' => 'You are heading for DP-600 (Fabric Analytics Engineer) and/or PL-300 (Power BI Data Analyst) — this path and sprint plan accompany learning, labs, and the exam.',
            ],
            'steps' => [
                [
                    'title' => [
                        'de' => '1. Exam wählen und buchen',
                        'en' => '1. Pick and book the exam',
                    ],
                    'lead' => [
                        'de' => 'DP-600 und/oder PL-300 festlegen, Microsoft Learn starten, Termin setzen.',
                        'en' => 'Lock DP-600 and/or PL-300, start Microsoft Learn, set the date.',
                    ],
                    'links' => [
                        ['type' => 'external', 'href' => 'https://learn.microsoft.com/en-us/credentials/certifications/fabric-analytics-engineer-associate/', 'label' => ['de' => 'DP-600 Fabric Analytics Engineer', 'en' => 'DP-600 Fabric Analytics Engineer']],
                        ['type' => 'external', 'href' => 'https://learn.microsoft.com/en-us/credentials/certifications/power-bi-data-analyst-associate/', 'label' => ['de' => 'PL-300 Power BI Data Analyst', 'en' => 'PL-300 Power BI Data Analyst']],
                        ['type' => 'external', 'href' => 'https://learn.microsoft.com/en-us/training/paths/get-started-fabric/', 'label' => ['de' => 'Learn: Get started with Fabric', 'en' => 'Learn: get started with Fabric']],
                        ['type' => 'external', 'href' => 'https://learn.microsoft.com/en-us/training/powerplatform/power-bi', 'label' => ['de' => 'Learn: Power BI', 'en' => 'Learn: Power BI']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '2. Domains und Governance-Kontext',
                        'en' => '2. Domains and governance context',
                    ],
                    'lead' => [
                        'de' => 'Lakehouse, Semantic Layer, Workspace-Rollen — plus unsere Platform-/Access-Pfade.',
                        'en' => 'Lakehouse, semantic layer, workspace roles — plus our platform/access paths.',
                    ],
                    'links' => [
                        ['type' => 'external', 'href' => 'https://learn.microsoft.com/en-us/fabric/data-engineering/lakehouse-overview', 'label' => ['de' => 'Docs: Fabric Lakehouse', 'en' => 'Docs: Fabric lakehouse']],
                        ['type' => 'external', 'href' => 'https://learn.microsoft.com/en-us/fabric/governance/', 'label' => ['de' => 'Docs: Fabric Governance', 'en' => 'Docs: Fabric governance']],
                        ['type' => 'path', 'id' => 'modernize-warehouse', 'label' => ['de' => 'Pfad: Warehouse modernisieren', 'en' => 'Path: modernize the warehouse']],
                        ['type' => 'path', 'id' => 'access-security-ops', 'label' => ['de' => 'Pfad: Access & Security Ops', 'en' => 'Path: access & security ops']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '3. Labs und Decision Brief',
                        'en' => '3. Labs and decision brief',
                    ],
                    'lead' => [
                        'de' => 'Stack-Fit und Governance-Entscheidungen als Evidence neben dem Learn-Track.',
                        'en' => 'Stack fit and governance decisions as evidence beside the Learn track.',
                    ],
                    'links' => [
                        ['type' => 'tool', 'route' => 'tools.architecture-fit', 'label' => ['de' => 'Architecture Fit', 'en' => 'Architecture fit']],
                        ['type' => 'tool', 'route' => 'tools.governance-stack-advisor', 'label' => ['de' => 'Stack Advisor', 'en' => 'Stack advisor']],
                        ['type' => 'tool', 'route' => 'tools.decision-brief-generator', 'label' => ['de' => 'Decision Brief', 'en' => 'Decision brief']],
                        ['type' => 'path', 'id' => 'simplest-viable-stack', 'label' => ['de' => 'Pfad: Simplest Viable Stack', 'en' => 'Path: simplest viable stack']],
                    ],
                ],
                [
                    'title' => [
                        'de' => '4. Exam und Transfer',
                        'en' => '4. Exam and transfer',
                    ],
                    'lead' => [
                        'de' => 'Exam ablegen, Badge sichern, Transfer in Delivery und Hub.',
                        'en' => 'Sit the exam, secure the badge, transfer into delivery and the hub.',
                    ],
                    'links' => [
                        ['type' => 'route', 'route' => 'resources.index', 'label' => ['de' => 'Resources: Fabric & Power BI Certs', 'en' => 'Resources: Fabric & Power BI certs']],
                        ['type' => 'path', 'id' => 'cert-project-evidence', 'label' => ['de' => 'Optional: Cert + Projekt parallel', 'en' => 'Optional: cert + project in parallel']],
                        ['type' => 'route', 'route' => 'governance.index', 'label' => ['de' => 'Governance Hub', 'en' => 'Governance hub']],
                    ],
                ],
            ],
        ],
    ],
];
