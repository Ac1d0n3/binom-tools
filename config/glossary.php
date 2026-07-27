<?php

/**
 * Glossary Hub — shared governance vocabulary with links into stories and tools.
 *
 * Readable definitions, not a PureView JSON generator.
 */
$config = [
  'categories' => [
    'roles' => [
      'de' => 'Rollen',
      'en' => 'Roles',
    ],
    'data' => [
      'de' => 'Daten & Produkte',
      'en' => 'Data & products',
    ],
    'architecture' => [
      'de' => 'Architektur',
      'en' => 'Architecture',
    ],
    'modeling' => [
      'de' => 'Modellierung',
      'en' => 'Modeling',
    ],
    'bi' => [
      'de' => 'BI & Reporting',
      'en' => 'BI & Reporting',
    ],
    'quality' => [
      'de' => 'Qualität',
      'en' => 'Quality',
    ],
    'privacy' => [
      'de' => 'Privacy & Schutz',
      'en' => 'Privacy & protection',
    ],
    'security' => [
      'de' => 'Zugriff & Security',
      'en' => 'Access & security',
    ],
    'metadata' => [
      'de' => 'Metadaten',
      'en' => 'Metadata',
    ],
    'process' => [
      'de' => 'Prozess',
      'en' => 'Process',
    ],
    'ai' => [
      'de' => 'KI & ML',
      'en' => 'AI & ML',
    ],
  ],
  'terms' => [
    [
      'id' => 'data-steward',
      'order' => 10,
      'category' => 'roles',
      'term' => [
        'de' => 'Data Steward',
        'en' => 'Data Steward',
      ],
      'aliases' => [
        'Steward',
        'Data Stewardship',
      ],
      'definition' => [
        'de' => 'Operative Verantwortung für Definition, Qualität und Nutzung eines Datenbereichs — nicht nur Dokumentation.',
        'en' => 'Operational ownership for definition, quality, and use of a data domain — not documentation alone.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-architect',
          'label' => [
            'de' => 'Data Architect',
            'en' => 'Data Architect',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'stewardship-capacity',
          'label' => [
            'de' => 'Stewardship staffen',
            'en' => 'Staffing stewardship',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-ownership-stewardship',
          'label' => [
            'de' => 'Ownership & Stewardship',
            'en' => 'Ownership & stewardship',
          ],
        ],
        [
          'type' => 'series',
          'id' => 'roles-hub',
          'label' => [
            'de' => 'Roles and Decision Rights',
            'en' => 'Roles and decision rights',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'governance-foundations',
          'label' => [
            'de' => 'Governance Foundations',
            'en' => 'Governance foundations',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => [
            'slug' => 'steward',
          ],
          'label' => [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-owner',
      'order' => 20,
      'category' => 'roles',
      'term' => [
        'de' => 'Data Owner',
        'en' => 'Data Owner',
      ],
      'aliases' => [
        'Owner',
        'Business Owner',
      ],
      'definition' => [
        'de' => 'Fachliche Entscheidungsinstanz für Zweck, Zugriffsregeln und Freigaben eines Datenprodukts oder einer Domäne.',
        'en' => 'Business decision-maker for purpose, access rules, and approvals of a data product or domain.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-consumer',
          'label' => [
            'de' => 'Data Consumer',
            'en' => 'Data Consumer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'raci',
          'label' => [
            'de' => 'RACI',
            'en' => 'RACI',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-product-owner-vs-data-owner',
          'label' => [
            'de' => 'Product Owner vs Owner vs Steward',
            'en' => 'Product Owner vs Owner vs Steward',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => [
            'slug' => 'owner',
          ],
          'label' => [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-architect',
      'order' => 25,
      'category' => 'roles',
      'term' => [
        'de' => 'Data Architect',
        'en' => 'Data Architect',
      ],
      'aliases' => [
        'Architect',
        'Analytics Architect',
        'Solution Architect (Data]',
      ],
      'definition' => [
        'de' => 'Verantwortet Grain, Modellkonsistenz und Contracts — damit Domänen und Marts zusammenpassen, ohne Owner oder Steward zu ersetzen.',
        'en' => 'Owns grain, model consistency, and contracts — so domains and marts fit together without replacing owner or steward.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-architect-role',
          'label' => [
            'de' => 'Die Rolle Data Architect',
            'en' => 'The Data Architect role',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'modernize-warehouse',
          'label' => [
            'de' => 'Warehouse modernisieren',
            'en' => 'Modernize the warehouse',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => [
            'slug' => 'architect',
          ],
          'label' => [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-custodian',
      'order' => 26,
      'category' => 'roles',
      'term' => [
        'de' => 'Data Custodian',
        'en' => 'Data Custodian',
      ],
      'aliases' => [
        'Custodian',
        'Technical Custodian',
      ],
      'definition' => [
        'de' => 'Technische Obhut über Systeme und Speicherorte — Zugriffspflege, Backups, Laufzeit — meist Platform/IT, nicht fachliche Definition.',
        'en' => 'Technical custody of systems and storage — access upkeep, backups, runtime — usually platform/IT, not business definition.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-ownership-stewardship',
          'label' => [
            'de' => 'Ownership & Stewardship',
            'en' => 'Ownership & stewardship',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => [
            'slug' => 'custodian',
          ],
          'label' => [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-consumer',
      'order' => 27,
      'category' => 'roles',
      'term' => [
        'de' => 'Data Consumer',
        'en' => 'Data Consumer',
      ],
      'aliases' => [
        'Consumer',
        'Analyst',
        'Report User',
      ],
      'definition' => [
        'de' => 'Nutzt Datenprodukte für Entscheidungen oder Reports — meldet Qualitätsprobleme, entscheidet aber nicht allein über Definition und Zugriff.',
        'en' => 'Uses data products for decisions or reports — raises quality issues but does not alone decide definition and access.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-product-owner-vs-data-owner',
          'label' => [
            'de' => 'Product Owner vs Owner vs Steward',
            'en' => 'Product Owner vs Owner vs Steward',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'one-data-product-multiple-consumers',
          'label' => [
            'de' => 'Ein Data Product, viele Consumer',
            'en' => 'One data product, many consumers',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => [
            'slug' => 'consumer',
          ],
          'label' => [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-product-owner',
      'order' => 28,
      'category' => 'roles',
      'term' => [
        'de' => 'Data Product Owner',
        'en' => 'Data Product Owner',
      ],
      'aliases' => [
        'Product Owner (Data]',
        'DPO (Data]',
      ],
      'definition' => [
        'de' => 'Verantwortet Lebenszyklus, Prioritäten und Consumer-Nutzen eines Datenprodukts — getrennt vom fachlichen Domain Owner und vom Steward.',
        'en' => 'Owns product lifecycle, priorities, and consumer value — distinct from the domain Owner and the Steward.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-product-owner-vs-data-owner',
          'label' => [
            'de' => 'Product Owner vs Owner vs Steward',
            'en' => 'Product Owner vs Owner vs Steward',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => [
            'slug' => 'product-owner',
          ],
          'label' => [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    [
      'id' => 'analytics-engineer',
      'order' => 29,
      'category' => 'roles',
      'term' => [
        'de' => 'Analytics Engineer',
        'en' => 'Analytics Engineer',
      ],
      'aliases' => [
        'AE',
        'Analytics Engineering',
      ],
      'definition' => [
        'de' => 'Baut vertrauenswürdige Transforms, Tests und Docs (oft mit dbt] zwischen Plattform und BI.',
        'en' => 'Builds trusted transforms, tests, and docs (often with dbt] between platform and BI.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'dbt-role',
          'label' => [
            'de' => 'Die Rolle dbt',
            'en' => 'The dbt role',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'cert-dbt-analytics-engineer',
          'label' => [
            'de' => 'dbt Analytics Engineer',
            'en' => 'dbt Analytics Engineer',
          ],
        ],
      ],
    ],
    [
      'id' => 'governance-coe',
      'order' => 31,
      'category' => 'roles',
      'term' => [
        'de' => 'Governance Center of Excellence',
        'en' => 'Governance Center of Excellence',
      ],
      'aliases' => [
        'CoE',
        'Data Governance CoE',
      ],
      'definition' => [
        'de' => 'Zentrale Enablement-Instanz für Standards, Cadence, Tooling und domänenübergreifende Eskalation.',
        'en' => 'Central enablement for standards, cadence, tooling, and cross-domain escalation.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'governance-lead',
          'label' => [
            'de' => 'Governance Lead',
            'en' => 'Governance Lead',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'operating-model',
          'label' => [
            'de' => 'Operating Model',
            'en' => 'Operating Model',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance CoE',
            'en' => 'Governance CoE',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'governance-foundations',
          'label' => [
            'de' => 'Governance Foundations',
            'en' => 'Governance foundations',
          ],
        ],
      ],
    ],
    [
      'id' => 'governance-lead',
      'order' => 32,
      'category' => 'roles',
      'term' => [
        'de' => 'Governance Lead',
        'en' => 'Governance Lead',
      ],
      'aliases' => [
        'DG Lead',
        'Data Governance Lead',
      ],
      'definition' => [
        'de' => 'Verantwortlich für das Governance-Operating-Model und Sponsor-Evidence — nicht für jedes Ticket.',
        'en' => 'Accountable for the governance operating model and sponsor evidence — not every ticket.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance Center of Excellence',
            'en' => 'Governance Center of Excellence',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'decision-rights',
          'label' => [
            'de' => 'Decision Rights',
            'en' => 'Decision Rights',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance CoE',
            'en' => 'Governance CoE',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'roles.index',
          'label' => [
            'de' => 'Roles Hub',
            'en' => 'Roles hub',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-product',
      'order' => 100,
      'category' => 'data',
      'term' => [
        'de' => 'Data Product',
        'en' => 'Data Product',
      ],
      'aliases' => [
        'Datenprodukt',
        'Product Thinking',
      ],
      'definition' => [
        'de' => 'Konsumierbares, versioniertes Datenangebot mit klarer Zielgruppe, Verträgen (SLA/SLO], Ownership und dokumentierter Qualität.',
        'en' => 'Consumable, versioned data offering with a clear audience, contracts (SLA/SLO], ownership, and documented quality.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-product-owner-vs-data-owner',
          'label' => [
            'de' => 'Product Owner vs Owner vs Steward',
            'en' => 'Product Owner vs Owner vs Steward',
          ],
        ],
        [
          'type' => 'series',
          'id' => 'building-modern-data-warehouse',
          'label' => [
            'de' => 'Modern Data Warehouse',
            'en' => 'Modern data warehouse',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'roles.index',
          'label' => [
            'de' => 'Roles Hub',
            'en' => 'Roles hub',
          ],
        ],
      ],
    ],
    [
      'id' => 'grain',
      'order' => 110,
      'category' => 'data',
      'term' => [
        'de' => 'Grain',
        'en' => 'Grain',
      ],
      'aliases' => [
        'Körnung',
        'Granularity',
      ],
      'definition' => [
        'de' => 'Die kleinste fachliche Aussageeinheit einer Tabelle oder eines Marts (z. B. eine Bestellung, ein Tag, ein Vertrag].',
        'en' => 'The smallest business statement unit of a table or mart (e.g. one order, one day, one contract].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
      ],
    ],
    [
      'id' => 'semantic-layer',
      'order' => 120,
      'category' => 'data',
      'term' => [
        'de' => 'Semantic Layer',
        'en' => 'Semantic Layer',
      ],
      'aliases' => [
        'Metrics Layer',
        'Semantische Schicht',
      ],
      'definition' => [
        'de' => 'Governte fachliche Bedeutung und Kennzahlen oberhalb physischer Tabellen.',
        'en' => 'Governed business meaning and metrics above physical tables.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-governance',
          'label' => [
            'de' => 'KPI Governance',
            'en' => 'KPI Governance',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'keeping-business-logic-outside-bi-apps',
          'label' => [
            'de' => 'Business-Logik außerhalb der BI-Apps',
            'en' => 'Keeping business logic outside BI apps',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted metrics',
          ],
        ],
      ],
    ],
    [
      'id' => 'semantic-model',
      'order' => 130,
      'category' => 'data',
      'term' => [
        'de' => 'Semantic Model',
        'en' => 'Semantic Model',
      ],
      'aliases' => [
        'BI Semantic Model',
      ],
      'definition' => [
        'de' => 'Toolgebundenes semantisches Artefakt (z. B. Power-BI-Modell, Qlik-App-Modell].',
        'en' => 'Tool-bound semantic artifact (e.g. Power BI model, Qlik app model].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'consumption-contract',
          'label' => [
            'de' => 'Consumption Contract',
            'en' => 'Consumption Contract',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-domain',
      'order' => 140,
      'category' => 'data',
      'term' => [
        'de' => 'Data Domain',
        'en' => 'Data Domain',
      ],
      'aliases' => [
        'Domain',
        'Datendomäne',
      ],
      'definition' => [
        'de' => 'Abgegrenzter fachlicher Bereich mit Ownership, Produkten und Decision Rights.',
        'en' => 'Bounded business area of ownership, products, and decision rights.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-mesh',
          'label' => [
            'de' => 'Data Mesh',
            'en' => 'Data Mesh',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'federated-governance',
          'label' => [
            'de' => 'Federated Governance',
            'en' => 'Federated Governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'metric-store',
      'order' => 150,
      'category' => 'data',
      'term' => [
        'de' => 'Metric / KPI Store',
        'en' => 'Metric / KPI Store',
      ],
      'aliases' => [
        'Certified KPI Core',
        'Metrics Store',
      ],
      'definition' => [
        'de' => 'Autoritative Ablage zertifizierter Kennzahlendefinitionen und Implementierungen.',
        'en' => 'Authoritative store of certified metric definitions and implementations.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'kpi-governance',
          'label' => [
            'de' => 'KPI Governance',
            'en' => 'KPI Governance',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'kpi-metric-governance',
          'label' => [
            'de' => 'KPI Metric Governance',
            'en' => 'KPI metric governance',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted metrics',
          ],
        ],
      ],
    ],
    [
      'id' => 'product-certification',
      'order' => 160,
      'category' => 'data',
      'term' => [
        'de' => 'Data Product Certification',
        'en' => 'Data Product Certification',
      ],
      'aliases' => [
        'Certified Data Product',
        'Zertifiziertes Datenprodukt',
      ],
      'definition' => [
        'de' => 'Expliziter Vertrauensstatus: Contract, DQ und Ownership sind erfüllt.',
        'en' => 'Explicit trust status that a product meets contract, DQ, and ownership bars.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
      ],
    ],
    [
      'id' => 'product-versioning',
      'order' => 170,
      'category' => 'data',
      'term' => [
        'de' => 'Data Product Versioning',
        'en' => 'Data Product Versioning',
      ],
      'aliases' => [
        'Versioning',
        'Produktversionierung',
      ],
      'definition' => [
        'de' => 'Explizite Versionen und Kompatibilitätsregeln für Produkt-Interfaces.',
        'en' => 'Explicit versions and compatibility rules for product interfaces.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'breaking-change',
          'label' => [
            'de' => 'Breaking Change',
            'en' => 'Breaking Change',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
      ],
    ],
    [
      'id' => 'breaking-change',
      'order' => 180,
      'category' => 'data',
      'term' => [
        'de' => 'Breaking Change',
        'en' => 'Breaking Change',
      ],
      'aliases' => [
        'Non-Compatible Change',
        'Inkompatible Änderung',
      ],
      'definition' => [
        'de' => 'Interface-/Schema-/Semantik-Änderung, die bestehende Consumer ungültig macht.',
        'en' => 'Interface, schema, or semantics change that invalidates existing consumers.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'product-versioning',
          'label' => [
            'de' => 'Data Product Versioning',
            'en' => 'Data Product Versioning',
          ],
        ],
      ],
    ],
    [
      'id' => 'sla-slo',
      'order' => 190,
      'category' => 'data',
      'term' => [
        'de' => 'SLA / SLO',
        'en' => 'SLA / SLO',
      ],
      'aliases' => [
        'Freshness SLA',
        'Service Level',
      ],
      'definition' => [
        'de' => 'Serviceversprechen (SLA] und messbare Zuverlässigkeitsziele (SLO], z. B. Freshness.',
        'en' => 'Service promises (SLA] and measurable reliability targets (SLO], e.g. freshness.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'freshness',
          'label' => [
            'de' => 'Freshness',
            'en' => 'Freshness',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
      ],
    ],
    [
      'id' => 'reverse-etl',
      'order' => 195,
      'category' => 'data',
      'term' => [
        'de' => 'Reverse ETL / Activation',
        'en' => 'Reverse ETL / Activation',
      ],
      'aliases' => [
        'Activation',
        'Reverse ETL',
      ],
      'definition' => [
        'de' => 'Kuratierte Warehouse-Daten zurück in operative Tools pushen.',
        'en' => 'Push curated warehouse data back into operational tools.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
      ],
    ],
    [
      'id' => 'ci-cd-data',
      'order' => 198,
      'category' => 'data',
      'term' => [
        'de' => 'Data CI/CD',
        'en' => 'Data CI/CD',
      ],
      'aliases' => [
        'Analytics CI/CD',
        'CI/CD für Daten',
      ],
      'definition' => [
        'de' => 'Automatisiertes Testen und Deployen von Modellen, Contracts und Quality Checks.',
        'en' => 'Automated test and deploy of models, contracts, and quality checks.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dq-gate',
          'label' => [
            'de' => 'DQ Gate',
            'en' => 'DQ Gate',
          ],
        ],
      ],
    ],
    [
      'id' => 'medallion-architecture',
      'order' => 200,
      'category' => 'architecture',
      'term' => [
        'de' => 'Medallion Architecture',
        'en' => 'Medallion Architecture',
      ],
      'aliases' => [
        'Medallion',
        'Bronze-Silver-Gold',
      ],
      'definition' => [
        'de' => 'Bronze/Silver/Gold als technische Zonen — nützliche Labels, aber kein vollständiges logisches Warehouse-Modell.',
        'en' => 'Bronze/Silver/Gold as technical zones — useful labels, not a full logical warehouse model.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'modernize-warehouse',
          'label' => [
            'de' => 'Warehouse modernisieren',
            'en' => 'Modernize the warehouse',
          ],
        ],
      ],
    ],
    [
      'id' => 'landing-raw-layer',
      'order' => 210,
      'category' => 'architecture',
      'term' => [
        'de' => 'Landing / RAW Layer',
        'en' => 'Landing / RAW Layer',
      ],
      'aliases' => [
        'Landing',
        'RAW',
        'Raw Layer',
        'Staging',
      ],
      'definition' => [
        'de' => 'Ingest wie empfangen: Quellpayload und Ladeidentität mit minimaler semantischer Änderung bewahren.',
        'en' => 'Ingest as received: preserve source payload and load identity with minimal semantic change.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'conform-layer',
          'label' => [
            'de' => 'Conform / Standardized Layer',
            'en' => 'Conform / Standardized Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'medallion-architecture',
          'label' => [
            'de' => 'Medallion Architecture',
            'en' => 'Medallion Architecture',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'automatic-raw-generation-using-dbt-macros',
          'label' => [
            'de' => 'Automatische RAW-Generierung',
            'en' => 'Automatic RAW generation',
          ],
        ],
      ],
    ],
    [
      'id' => 'conform-layer',
      'order' => 220,
      'category' => 'architecture',
      'term' => [
        'de' => 'Conform / Standardized Layer',
        'en' => 'Conform / Standardized Layer',
      ],
      'aliases' => [
        'Standardized',
        'Validated',
        'Conform',
      ],
      'definition' => [
        'de' => 'Technische Standardisierung und Validierung vor der fachlichen Identitätsintegration.',
        'en' => 'Technical standardization and validation before business identity integration.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
      ],
    ],
    [
      'id' => 'integrated-core',
      'order' => 230,
      'category' => 'architecture',
      'term' => [
        'de' => 'Integrated Core',
        'en' => 'Integrated Core',
      ],
      'aliases' => [
        'Core Layer',
        'Enterprise Core',
      ],
      'definition' => [
        'de' => 'Geteilte fachliche Entitäten, Beziehungen und Historie über Quellen hinweg.',
        'en' => 'Shared business entities, relationships, and history across sources.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'conform-layer',
          'label' => [
            'de' => 'Conform / Standardized Layer',
            'en' => 'Conform / Standardized Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'golden-record',
          'label' => [
            'de' => 'Golden Record',
            'en' => 'Golden Record',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
      ],
    ],
    [
      'id' => 'mart-layer',
      'order' => 240,
      'category' => 'architecture',
      'term' => [
        'de' => 'Mart / Business Data Product Layer',
        'en' => 'Mart / Business Data Product Layer',
      ],
      'aliases' => [
        'Data Mart',
        'Business Mart',
        'Mart',
      ],
      'definition' => [
        'de' => 'Zweckgebundene Facts/Dimensions/KPI-Basen für einen definierten Consumer-Zweck.',
        'en' => 'Purpose-bound facts, dimensions, and KPI bases for a defined consumer purpose.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'consumption-contract',
          'label' => [
            'de' => 'Consumption Contract',
            'en' => 'Consumption Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
      ],
    ],
    [
      'id' => 'consumption-contract',
      'order' => 250,
      'category' => 'architecture',
      'term' => [
        'de' => 'Consumption Contract',
        'en' => 'Consumption Contract',
      ],
      'aliases' => [
        'Semantic Consumption',
        'Consumption Layer',
      ],
      'definition' => [
        'de' => 'Toolspezifischer Zugriff und Interpretation eines governten Produkts (Views, Semantic Models, Extracts].',
        'en' => 'Tool-specific access and interpretation of a governed product (views, semantic models, extracts].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
      ],
    ],
    [
      'id' => 'lakehouse',
      'order' => 260,
      'category' => 'architecture',
      'term' => [
        'de' => 'Lakehouse',
        'en' => 'Lakehouse',
      ],
      'aliases' => [
        'Fabric Lakehouse',
        'Lakehouse Platform',
      ],
      'definition' => [
        'de' => 'Offene Tabellenformate plus Warehouse-ähnliche Governance auf geteiltem Lake-Storage.',
        'en' => 'Open table formats plus warehouse-style governance on shared lake storage.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'microsoft-fabric',
          'label' => [
            'de' => 'Microsoft Fabric',
            'en' => 'Microsoft Fabric',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'onelake',
          'label' => [
            'de' => 'OneLake',
            'en' => 'OneLake',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-mesh',
      'order' => 270,
      'category' => 'architecture',
      'term' => [
        'de' => 'Data Mesh',
        'en' => 'Data Mesh',
      ],
      'aliases' => [
        'Mesh',
      ],
      'definition' => [
        'de' => 'Domäneneigene Datenprodukte mit federierter Governance — kein Tooling-Checklist.',
        'en' => 'Domain-owned data products with federated governance, not a tooling checklist.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-domain',
          'label' => [
            'de' => 'Data Domain',
            'en' => 'Data Domain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'federated-governance',
          'label' => [
            'de' => 'Federated Governance',
            'en' => 'Federated Governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'simplest-viable-architecture',
      'order' => 280,
      'category' => 'architecture',
      'term' => [
        'de' => 'Simplest Viable Architecture',
        'en' => 'Simplest Viable Architecture',
      ],
      'aliases' => [
        'SVA',
      ],
      'definition' => [
        'de' => 'Geringstmögliche unnötige Komplexität, die die Anforderung noch erfüllt — nicht die wenigsten Boxen.',
        'en' => 'Least unnecessary complexity that still meets the requirement — not fewest boxes.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'choosing-the-simplest-viable-architecture',
          'label' => [
            'de' => 'Einfachste tragfähige Architektur',
            'en' => 'Choosing the simplest viable architecture',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'simplest-viable-stack',
          'label' => [
            'de' => 'Simplest Viable Stack',
            'en' => 'Simplest viable stack',
          ],
        ],
      ],
    ],
    [
      'id' => 'modern-data-warehouse',
      'order' => 290,
      'category' => 'architecture',
      'term' => [
        'de' => 'Modern Data Warehouse',
        'en' => 'Modern Data Warehouse',
      ],
      'aliases' => [
        'MDW',
        'Cloud Data Warehouse',
      ],
      'definition' => [
        'de' => 'Governter Pfad von Quelle zu Produkten und Semantik — nicht nur ein Cloud-Vendor-Rename.',
        'en' => 'Governed path from source to products and semantics — not just a cloud vendor rename.',
      ],
      'related' => [
        [
          'type' => 'series',
          'id' => 'building-modern-data-warehouse',
          'label' => [
            'de' => 'Modern Data Warehouse',
            'en' => 'Modern data warehouse',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'modernize-warehouse',
          'label' => [
            'de' => 'Warehouse modernisieren',
            'en' => 'Modernize the warehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'simplest-viable-architecture',
          'label' => [
            'de' => 'Simplest Viable Architecture',
            'en' => 'Simplest Viable Architecture',
          ],
        ],
      ],
    ],
    [
      'id' => 'greenfield',
      'order' => 300,
      'category' => 'architecture',
      'term' => [
        'de' => 'Greenfield',
        'en' => 'Greenfield',
      ],
      'aliases' => [
        'Greenfield Build',
      ],
      'definition' => [
        'de' => 'Warehouse von Grund auf bauen, ohne ein Legacy-Estate zu stranglen.',
        'en' => 'Build-from-scratch warehouse without strangling a legacy estate.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'building-from-scratch',
          'label' => [
            'de' => 'Von Grund auf bauen',
            'en' => 'Building from scratch',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'brownfield',
          'label' => [
            'de' => 'Brownfield',
            'en' => 'Brownfield',
          ],
        ],
      ],
    ],
    [
      'id' => 'brownfield',
      'order' => 310,
      'category' => 'architecture',
      'term' => [
        'de' => 'Brownfield',
        'en' => 'Brownfield',
      ],
      'aliases' => [
        'Warehouse Modernization',
      ],
      'definition' => [
        'de' => 'Bestehendes Warehouse/Estate vor Ort modernisieren.',
        'en' => 'Modernize an existing warehouse or estate in place.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'modernizing-an-existing-warehouse',
          'label' => [
            'de' => 'Bestehendes Warehouse modernisieren',
            'en' => 'Modernizing an existing warehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'strangler-pattern',
          'label' => [
            'de' => 'Strangler Pattern',
            'en' => 'Strangler Pattern',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'greenfield',
          'label' => [
            'de' => 'Greenfield',
            'en' => 'Greenfield',
          ],
        ],
      ],
    ],
    [
      'id' => 'strangler-pattern',
      'order' => 320,
      'category' => 'architecture',
      'term' => [
        'de' => 'Strangler Pattern',
        'en' => 'Strangler Pattern',
      ],
      'aliases' => [
        'Strangler Fig',
      ],
      'definition' => [
        'de' => 'Legacy-Pfade schrittweise ersetzen, indem neuer Traffic auf den modernen Stack geroutet wird.',
        'en' => 'Incrementally replace legacy paths by routing new traffic to the modern stack.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'brownfield',
          'label' => [
            'de' => 'Brownfield',
            'en' => 'Brownfield',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'modernizing-an-existing-warehouse',
          'label' => [
            'de' => 'Bestehendes Warehouse modernisieren',
            'en' => 'Modernizing an existing warehouse',
          ],
        ],
      ],
    ],
    [
      'id' => 'vertical-slice',
      'order' => 330,
      'category' => 'architecture',
      'term' => [
        'de' => 'Vertical Slice',
        'en' => 'Vertical Slice',
      ],
      'aliases' => [
        'Thin Slice',
      ],
      'definition' => [
        'de' => 'End-to-end dünner Pfad (Quelle → Produkt → Consumer] vor horizontalem Plattform-Wildwuchs.',
        'en' => 'End-to-end thin path (source → product → consumer] before horizontal platform sprawl.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'simplest-viable-architecture',
          'label' => [
            'de' => 'Simplest Viable Architecture',
            'en' => 'Simplest Viable Architecture',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'building-from-scratch',
          'label' => [
            'de' => 'Von Grund auf bauen',
            'en' => 'Building from scratch',
          ],
        ],
      ],
    ],
    [
      'id' => 'hybrid-cloud',
      'order' => 335,
      'category' => 'architecture',
      'term' => [
        'de' => 'Hybrid Cloud',
        'en' => 'Hybrid Cloud',
      ],
      'aliases' => [
        'Hybrid',
      ],
      'definition' => [
        'de' => 'On-Prem und Cloud als bewusste Architektur — kein temporärer Defekt.',
        'en' => 'On-prem and cloud coexistence as a deliberate architecture, not a temporary defect.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'host-vs-cloud',
          'label' => [
            'de' => 'Host vs Cloud',
            'en' => 'Host vs cloud',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'cloud-hosting',
          'label' => [
            'de' => 'Cloud Hosting',
            'en' => 'Cloud hosting',
          ],
        ],
      ],
    ],
    [
      'id' => 'elt',
      'order' => 340,
      'category' => 'architecture',
      'term' => [
        'de' => 'ELT',
        'en' => 'ELT',
      ],
      'aliases' => [
        'Extract-Load-Transform',
      ],
      'definition' => [
        'de' => 'Zuerst laden, dann in der Plattform transformieren (typisches dbt-Muster].',
        'en' => 'Load first, transform in the platform (typical dbt pattern].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
      ],
    ],
    [
      'id' => 'orchestration',
      'order' => 345,
      'category' => 'architecture',
      'term' => [
        'de' => 'Orchestration',
        'en' => 'Orchestration',
      ],
      'aliases' => [
        'Pipeline Orchestration',
        'Orchestrierung',
      ],
      'definition' => [
        'de' => 'Scheduling und Abhängigkeitsmanagement über Pipelines und Quality Gates.',
        'en' => 'Scheduling and dependency management across pipelines and quality gates.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dq-gate',
          'label' => [
            'de' => 'DQ Gate',
            'en' => 'DQ Gate',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
      ],
    ],
    [
      'id' => 'dbt',
      'order' => 350,
      'category' => 'architecture',
      'term' => [
        'de' => 'dbt',
        'en' => 'dbt',
      ],
      'aliases' => [
        'Analytics Engineering Tool',
      ],
      'definition' => [
        'de' => 'SQL-first Framework für Transform, Test und Dokumentation im Warehouse/Lakehouse.',
        'en' => 'SQL-first transform, test, and documentation framework in the warehouse/lakehouse.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt-meta',
          'label' => [
            'de' => 'dbt meta',
            'en' => 'dbt meta',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'analytics-engineer',
          'label' => [
            'de' => 'Analytics Engineer',
            'en' => 'Analytics Engineer',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'dbt-role',
          'label' => [
            'de' => 'Die Rolle dbt',
            'en' => 'The dbt role',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'metadata-driven-governance-with-dbt-meta',
          'label' => [
            'de' => 'dbt meta Governance',
            'en' => 'dbt meta governance',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'dq-with-dbt',
          'label' => [
            'de' => 'DQ mit dbt',
            'en' => 'DQ with dbt',
          ],
        ],
      ],
    ],
    [
      'id' => 'microsoft-fabric',
      'order' => 360,
      'category' => 'architecture',
      'term' => [
        'de' => 'Microsoft Fabric',
        'en' => 'Microsoft Fabric',
      ],
      'aliases' => [
        'Fabric',
      ],
      'definition' => [
        'de' => 'Einheitliche Analytics-SaaS (OneLake, Lakehouse/Warehouse, Power BI, Purview-Touchpoints].',
        'en' => 'Unified analytics SaaS (OneLake, Lakehouse/Warehouse, Power BI, Purview touchpoints].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'onelake',
          'label' => [
            'de' => 'OneLake',
            'en' => 'OneLake',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'cert-fabric-power-bi',
          'label' => [
            'de' => 'Fabric & Power BI',
            'en' => 'Fabric & Power BI',
          ],
        ],
      ],
    ],
    [
      'id' => 'onelake',
      'order' => 370,
      'category' => 'architecture',
      'term' => [
        'de' => 'OneLake',
        'en' => 'OneLake',
      ],
      'aliases' => [
        'One Lake',
      ],
      'definition' => [
        'de' => 'Logische Lake-Storage-Ebene von Fabric für Lakehouse-/Warehouse-Assets.',
        'en' => 'Fabric’s single logical lake storage plane for Lakehouse and Warehouse assets.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'microsoft-fabric',
          'label' => [
            'de' => 'Microsoft Fabric',
            'en' => 'Microsoft Fabric',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
      ],
    ],
    [
      'id' => 'delta-lake',
      'order' => 380,
      'category' => 'architecture',
      'term' => [
        'de' => 'Delta Lake',
        'en' => 'Delta Lake',
      ],
      'aliases' => [
        'Delta Tables',
      ],
      'definition' => [
        'de' => 'ACID-Tabellenformat, oft Grundlage von Lakehouse-Cores und DQ-Result-Stores.',
        'en' => 'ACID table format commonly underlying lakehouse cores and DQ result stores.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'onelake',
          'label' => [
            'de' => 'OneLake',
            'en' => 'OneLake',
          ],
        ],
      ],
    ],
    [
      'id' => 'qvd',
      'order' => 390,
      'category' => 'architecture',
      'term' => [
        'de' => 'QVD',
        'en' => 'QVD',
      ],
      'aliases' => [
        'Qlik Data File',
      ],
      'definition' => [
        'de' => 'Qliks optimiertes spaltenorientiertes Extract-Format für wiederverwendbare Load-Layer.',
        'en' => 'Qlik’s optimized columnar extract format for reusable load layers.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'consumption-contract',
          'label' => [
            'de' => 'Consumption Contract',
            'en' => 'Consumption Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'section-access',
          'label' => [
            'de' => 'Section Access',
            'en' => 'Section Access',
          ],
        ],
      ],
    ],
    [
      'id' => 'dimensional-modeling',
      'order' => 400,
      'category' => 'modeling',
      'term' => [
        'de' => 'Dimensional Modeling',
        'en' => 'Dimensional Modeling',
      ],
      'aliases' => [
        'Dimensional Model',
        'Dimensionale Modellierung',
      ],
      'definition' => [
        'de' => 'Facts und Dimensions für analytisches Grain und Wiederverwendung organisiert (Kimball-Stil].',
        'en' => 'Facts and dimensions organized for analytics grain and reuse (Kimball-style].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
      ],
    ],
    [
      'id' => 'star-schema',
      'order' => 410,
      'category' => 'modeling',
      'term' => [
        'de' => 'Star Schema',
        'en' => 'Star Schema',
      ],
      'aliases' => [
        'Star',
        'Sternschema',
      ],
      'definition' => [
        'de' => 'Fact-Tabelle umgeben von denormalisierten Dimensions.',
        'en' => 'Fact table surrounded by denormalized dimensions.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
      ],
    ],
    [
      'id' => 'fact-table',
      'order' => 420,
      'category' => 'modeling',
      'term' => [
        'de' => 'Fact Table',
        'en' => 'Fact Table',
      ],
      'aliases' => [
        'Facts',
        'Faktentabelle',
      ],
      'definition' => [
        'de' => 'Messbare Ereignisse/Transaktionen bei deklariertem Grain.',
        'en' => 'Measurable events or transactions at a declared grain.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
      ],
    ],
    [
      'id' => 'dimension-table',
      'order' => 430,
      'category' => 'modeling',
      'term' => [
        'de' => 'Dimension Table',
        'en' => 'Dimension Table',
      ],
      'aliases' => [
        'Dimensions',
        'Dimensionstabelle',
      ],
      'definition' => [
        'de' => 'Beschreibender Kontext (Kunde, Produkt, Kalender], der an Facts gejoint wird.',
        'en' => 'Descriptive context (customer, product, calendar] joined to facts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scd',
          'label' => [
            'de' => 'SCD (Slowly Changing Dimension]',
            'en' => 'SCD (Slowly Changing Dimension]',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'conformed-dimension',
          'label' => [
            'de' => 'Conformed Dimension',
            'en' => 'Conformed Dimension',
          ],
        ],
      ],
    ],
    [
      'id' => 'conformed-dimension',
      'order' => 440,
      'category' => 'modeling',
      'term' => [
        'de' => 'Conformed Dimension',
        'en' => 'Conformed Dimension',
      ],
      'aliases' => [
        'Shared Dimension',
        'Konforme Dimension',
      ],
      'definition' => [
        'de' => 'Geteilte Dimensionsbedeutung und Keys, wiederverwendbar über Marts und Domänen.',
        'en' => 'Shared dimension meaning and keys reusable across marts and domains.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
      ],
    ],
    [
      'id' => 'scd',
      'order' => 450,
      'category' => 'modeling',
      'term' => [
        'de' => 'SCD (Slowly Changing Dimension]',
        'en' => 'SCD (Slowly Changing Dimension]',
      ],
      'aliases' => [
        'Slowly Changing Dimension',
      ],
      'definition' => [
        'de' => 'Musternfamilie, wie Dimensionsattribute sich über die Zeit ändern.',
        'en' => 'Pattern family for how dimension attributes change over time.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'slowlychange-dim',
          'label' => [
            'de' => 'Slowly Changing Dimensions',
            'en' => 'Slowly changing dimensions',
          ],
        ],
      ],
    ],
    [
      'id' => 'scd2',
      'order' => 460,
      'category' => 'modeling',
      'term' => [
        'de' => 'SCD Type 2',
        'en' => 'SCD Type 2',
      ],
      'aliases' => [
        'SCD2',
        'Type 2 History',
      ],
      'definition' => [
        'de' => 'Attributänderungen historisieren mit Gültigkeitsdaten bzw. Versionszeilen.',
        'en' => 'Historize attribute changes with effective dating or version rows.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'scd',
          'label' => [
            'de' => 'SCD (Slowly Changing Dimension]',
            'en' => 'SCD (Slowly Changing Dimension]',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'as-was-history',
          'label' => [
            'de' => 'As-Was History',
            'en' => 'As-Was History',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'slowlychange-dim',
          'label' => [
            'de' => 'Slowly Changing Dimensions',
            'en' => 'Slowly changing dimensions',
          ],
        ],
      ],
    ],
    [
      'id' => 'cdc',
      'order' => 470,
      'category' => 'modeling',
      'term' => [
        'de' => 'CDC (Change Data Capture]',
        'en' => 'CDC (Change Data Capture]',
      ],
      'aliases' => [
        'Change Data Capture',
      ],
      'definition' => [
        'de' => 'Quell-Inserts/Updates/Deletes für inkrementelle Loads erfassen.',
        'en' => 'Capture source inserts, updates, and deletes for incremental loads.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'delta-load',
          'label' => [
            'de' => 'Delta / Incremental Load',
            'en' => 'Delta / Incremental Load',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
      ],
    ],
    [
      'id' => 'delta-load',
      'order' => 480,
      'category' => 'modeling',
      'term' => [
        'de' => 'Delta / Incremental Load',
        'en' => 'Delta / Incremental Load',
      ],
      'aliases' => [
        'Incremental Load',
        'Delta MERGE',
      ],
      'definition' => [
        'de' => 'Nur Änderungen seit dem letzten erfolgreichen Lauf verarbeiten (oft via MERGE].',
        'en' => 'Process only changes since last successful run (often via MERGE].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'cdc',
          'label' => [
            'de' => 'CDC (Change Data Capture]',
            'en' => 'CDC (Change Data Capture]',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'elt',
          'label' => [
            'de' => 'ELT',
            'en' => 'ELT',
          ],
        ],
      ],
    ],
    [
      'id' => 'surrogate-key',
      'order' => 490,
      'category' => 'modeling',
      'term' => [
        'de' => 'Surrogate Key',
        'en' => 'Surrogate Key',
      ],
      'aliases' => [
        'SK',
        'Surrogatschlüssel',
      ],
      'definition' => [
        'de' => 'Systemgenerierter dauerhafter Key unabhängig von Natural Keys der Quelle.',
        'en' => 'System-generated durable key independent of source natural keys.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'natural-key',
          'label' => [
            'de' => 'Natural Key',
            'en' => 'Natural Key',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
      ],
    ],
    [
      'id' => 'natural-key',
      'order' => 500,
      'category' => 'modeling',
      'term' => [
        'de' => 'Natural Key',
        'en' => 'Natural Key',
      ],
      'aliases' => [
        'Business Key',
        'Natürlicher Schlüssel',
      ],
      'definition' => [
        'de' => 'Fachlicher/Quellen-Identifier für Matching und Lineage zurück zum Ursprung.',
        'en' => 'Business or source identifier used for matching and lineage back to origin.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'surrogate-key',
          'label' => [
            'de' => 'Surrogate Key',
            'en' => 'Surrogate Key',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'golden-record',
          'label' => [
            'de' => 'Golden Record',
            'en' => 'Golden Record',
          ],
        ],
      ],
    ],
    [
      'id' => 'golden-record',
      'order' => 510,
      'category' => 'modeling',
      'term' => [
        'de' => 'Golden Record',
        'en' => 'Golden Record',
      ],
      'aliases' => [
        'Golden Customer',
        'Master Record',
      ],
      'definition' => [
        'de' => 'Aufgelöste, governte Master-Darstellung einer Entität (z. B. Golden Customer].',
        'en' => 'Resolved, governed master representation of an entity (e.g. Golden Customer].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'natural-key',
          'label' => [
            'de' => 'Natural Key',
            'en' => 'Natural Key',
          ],
        ],
      ],
    ],
    [
      'id' => 'as-was-history',
      'order' => 520,
      'category' => 'modeling',
      'term' => [
        'de' => 'As-Was History',
        'en' => 'As-Was History',
      ],
      'aliases' => [
        'Temporal History',
        'As-Of',
      ],
      'definition' => [
        'de' => 'Punkt-in-Zeit-Sicht auf Beziehungen/Attribute, wie sie an einem vergangenen Datum waren.',
        'en' => 'Point-in-time view of relationships and attributes as they were at a past date.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
      ],
    ],
    [
      'id' => 'schema-drift',
      'order' => 530,
      'category' => 'modeling',
      'term' => [
        'de' => 'Schema Drift',
        'en' => 'Schema Drift',
      ],
      'aliases' => [
        'Drift',
      ],
      'definition' => [
        'de' => 'Unerwartete Quell-/Schema-Änderung, die Contracts oder Loads bricht.',
        'en' => 'Unexpected source or schema change that breaks contracts or loads.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'breaking-change',
          'label' => [
            'de' => 'Breaking Change',
            'en' => 'Breaking Change',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'automatic-raw-generation-using-dbt-macros',
          'label' => [
            'de' => 'Automatische RAW-Generierung',
            'en' => 'Automatic RAW generation',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-quality',
      'order' => 600,
      'category' => 'quality',
      'term' => [
        'de' => 'Data Quality',
        'en' => 'Data Quality',
      ],
      'aliases' => [
        'DQ',
        'Datenqualität',
      ],
      'definition' => [
        'de' => 'Messbare Eignung von Daten für einen Zweck — Regeln, Ownership, Monitoring und Remediation statt einmaliger Checks.',
        'en' => 'Measurable fitness of data for a purpose — rules, ownership, monitoring, and remediation instead of one-off checks.',
      ],
      'related' => [
        [
          'type' => 'series',
          'id' => 'operational-data-quality',
          'label' => [
            'de' => 'Operational Data Quality',
            'en' => 'Operational data quality',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'dq-with-dbt',
          'label' => [
            'de' => 'DQ mit dbt',
            'en' => 'DQ with dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-observability',
          'label' => [
            'de' => 'Data Observability',
            'en' => 'Data Observability',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'fitness-for-purpose',
          'label' => [
            'de' => 'Fitness for Purpose',
            'en' => 'Fitness for Purpose',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-contract',
      'order' => 610,
      'category' => 'quality',
      'term' => [
        'de' => 'Data Contract',
        'en' => 'Data Contract',
      ],
      'aliases' => [
        'Datenvertrag',
        'Interface Contract',
      ],
      'definition' => [
        'de' => 'Vereinbarung zwischen Produzent und Konsument über Schema, Semantik, SLAs und Breaking-Change-Regeln.',
        'en' => 'Agreement between producer and consumer on schema, semantics, SLAs, and breaking-change rules.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'breaking-change',
          'label' => [
            'de' => 'Breaking Change',
            'en' => 'Breaking Change',
          ],
        ],
      ],
    ],
    [
      'id' => 'fitness-for-purpose',
      'order' => 620,
      'category' => 'quality',
      'term' => [
        'de' => 'Fitness for Purpose',
        'en' => 'Fitness for Purpose',
      ],
      'aliases' => [
        'Fit for Purpose',
        'Zwecktauglichkeit',
      ],
      'definition' => [
        'de' => 'Qualität gemessen am expliziten Use Case — nicht absolute Perfektion.',
        'en' => 'Quality judged against an explicit use case — not absolute perfection.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-quality-governance',
          'label' => [
            'de' => 'Data Quality Governance',
            'en' => 'Data quality governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-observability',
      'order' => 630,
      'category' => 'quality',
      'term' => [
        'de' => 'Data Observability',
        'en' => 'Data Observability',
      ],
      'aliases' => [
        'Observability',
        'Datenobservability',
      ],
      'definition' => [
        'de' => 'Unerwartete Volume-/Null-/Verteilungs-/Schema-/Freshness-Anomalien erkennen — über feste Regeln hinaus.',
        'en' => 'Detect unexpected volume, null, distribution, schema, and freshness anomalies beyond fixed rules.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'freshness',
          'label' => [
            'de' => 'Freshness',
            'en' => 'Freshness',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-quality-governance',
          'label' => [
            'de' => 'Data Quality Governance',
            'en' => 'Data quality governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'dq-gate',
      'order' => 640,
      'category' => 'quality',
      'term' => [
        'de' => 'DQ Gate',
        'en' => 'DQ Gate',
      ],
      'aliases' => [
        'Quality Gate',
        'Qualitätsgate',
      ],
      'definition' => [
        'de' => 'Hard Stop bzw. Promote-Bedingung in der Pipeline basierend auf Qualitätsergebnissen.',
        'en' => 'Hard stop or promote condition in the pipeline based on quality outcomes.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'orchestration',
          'label' => [
            'de' => 'Orchestration',
            'en' => 'Orchestration',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
      ],
    ],
    [
      'id' => 'rule-registry',
      'order' => 650,
      'category' => 'quality',
      'term' => [
        'de' => 'Rule Registry',
        'en' => 'Rule Registry',
      ],
      'aliases' => [
        'Quality Rule Catalog',
        'Regelregister',
      ],
      'definition' => [
        'de' => 'Katalog von DQ-Checks, Ownern, Severity und Ausführungskontext.',
        'en' => 'Catalog of DQ checks, owners, severity, and execution context.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'remediation',
          'label' => [
            'de' => 'Remediation',
            'en' => 'Remediation',
          ],
        ],
      ],
    ],
    [
      'id' => 'remediation',
      'order' => 660,
      'category' => 'quality',
      'term' => [
        'de' => 'Remediation',
        'en' => 'Remediation',
      ],
      'aliases' => [
        'Issue Remediation',
        'Behebung',
      ],
      'definition' => [
        'de' => 'Owned Fix-and-Validate-Loop für Qualitäts- und Metadatenfehler.',
        'en' => 'Owned fix-and-validate loop for quality and metadata defects.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'root-cause-analysis',
          'label' => [
            'de' => 'Root Cause Analysis (DQ]',
            'en' => 'Root Cause Analysis (DQ]',
          ],
        ],
      ],
    ],
    [
      'id' => 'freshness',
      'order' => 670,
      'category' => 'quality',
      'term' => [
        'de' => 'Freshness',
        'en' => 'Freshness',
      ],
      'aliases' => [
        'Timeliness',
        'Aktualität',
      ],
      'definition' => [
        'de' => 'Wie aktuell Daten (oder Metadaten] gegenüber der vereinbarten Erwartung sind.',
        'en' => 'How current data (or metadata] is versus the agreed expectation.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'sla-slo',
          'label' => [
            'de' => 'SLA / SLO',
            'en' => 'SLA / SLO',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-observability',
          'label' => [
            'de' => 'Data Observability',
            'en' => 'Data Observability',
          ],
        ],
      ],
    ],
    [
      'id' => 'completeness',
      'order' => 680,
      'category' => 'quality',
      'term' => [
        'de' => 'Completeness',
        'en' => 'Completeness',
      ],
      'aliases' => [
        'Vollständigkeit',
      ],
      'definition' => [
        'de' => 'Erforderliche Felder/Records für den deklarierten Zweck sind vorhanden.',
        'en' => 'Required fields and records present for the declared purpose.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'fitness-for-purpose',
          'label' => [
            'de' => 'Fitness for Purpose',
            'en' => 'Fitness for Purpose',
          ],
        ],
      ],
    ],
    [
      'id' => 'consistency',
      'order' => 690,
      'category' => 'quality',
      'term' => [
        'de' => 'Consistency',
        'en' => 'Consistency',
      ],
      'aliases' => [
        'Konsistenz',
      ],
      'definition' => [
        'de' => 'Gleiche Bedeutung und Regeln über Systeme, Layer und Replikas hinweg.',
        'en' => 'Same meaning and rules across systems, layers, and replicas.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'conformed-dimension',
          'label' => [
            'de' => 'Conformed Dimension',
            'en' => 'Conformed Dimension',
          ],
        ],
      ],
    ],
    [
      'id' => 'accuracy',
      'order' => 700,
      'category' => 'quality',
      'term' => [
        'de' => 'Accuracy',
        'en' => 'Accuracy',
      ],
      'aliases' => [
        'Correctness',
        'Genauigkeit',
      ],
      'definition' => [
        'de' => 'Werte repräsentieren den realen Sachverhalt für den Use Case korrekt.',
        'en' => 'Values correctly represent the real-world fact for the use case.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'fitness-for-purpose',
          'label' => [
            'de' => 'Fitness for Purpose',
            'en' => 'Fitness for Purpose',
          ],
        ],
      ],
    ],
    [
      'id' => 'uniqueness',
      'order' => 710,
      'category' => 'quality',
      'term' => [
        'de' => 'Uniqueness',
        'en' => 'Uniqueness',
      ],
      'aliases' => [
        'Deduplication',
        'Eindeutigkeit',
      ],
      'definition' => [
        'de' => 'Keine unerwünschten Duplikate beim deklarierten Grain/Key.',
        'en' => 'No unwanted duplicates at the declared grain or key.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
      ],
    ],
    [
      'id' => 'validity',
      'order' => 720,
      'category' => 'quality',
      'term' => [
        'de' => 'Validity',
        'en' => 'Validity',
      ],
      'aliases' => [
        'Gültigkeit',
      ],
      'definition' => [
        'de' => 'Werte entsprechen erlaubten Formaten, Domänen und Referenzmengen.',
        'en' => 'Values conform to allowed formats, domains, and reference sets.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
      ],
    ],
    [
      'id' => 'root-cause-analysis',
      'order' => 730,
      'category' => 'quality',
      'term' => [
        'de' => 'Root Cause Analysis (DQ]',
        'en' => 'Root Cause Analysis (DQ]',
      ],
      'aliases' => [
        'RCA',
        'Ursachenanalyse',
      ],
      'definition' => [
        'de' => 'Defekt zum Ursprungssystem/-prozess zurückverfolgen statt Marts zu patchen.',
        'en' => 'Trace defect to originating system or process instead of patching marts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'remediation',
          'label' => [
            'de' => 'Remediation',
            'en' => 'Remediation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
      ],
    ],
    [
      'id' => 'pii',
      'order' => 800,
      'category' => 'privacy',
      'term' => [
        'de' => 'PII',
        'en' => 'PII',
      ],
      'aliases' => [
        'Personenbezogene Daten',
        'Personal Data',
        'SPI',
      ],
      'definition' => [
        'de' => 'Personenbezogene oder personenbeziehbare Daten. Brauchen Klassifikation, Masking, Zweckbindung und nachweisbare Lösch-/Sperrpfade.',
        'en' => 'Personally identifiable or linkable data. Needs classification, masking, purpose binding, and proven deletion/restriction paths.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'pii-privacy-governance',
          'label' => [
            'de' => 'PII Privacy Governance',
            'en' => 'PII privacy governance',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'pii-in-five-steps',
          'label' => [
            'de' => 'PII in 5 Schritten',
            'en' => 'PII in 5 steps',
          ],
        ],
        [
          'type' => 'compliance',
          'id' => 'gdpr',
          'label' => [
            'de' => 'DSGVO / GDPR',
            'en' => 'GDPR',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-classification',
          'label' => [
            'de' => 'Data Classification',
            'en' => 'Data Classification',
          ],
        ],
      ],
    ],
    [
      'id' => 'dsdr',
      'order' => 810,
      'category' => 'privacy',
      'term' => [
        'de' => 'DSDR',
        'en' => 'DSDR',
      ],
      'aliases' => [
        'Data Subject Deletion Request',
        'Löschanfrage',
        'Right to Erasure',
      ],
      'definition' => [
        'de' => 'Prozess und technische Fähigkeit, Betroffenenrechte (Löschen/Sperren] über Systeme und Lineage hinweg auszuführen und zu belegen.',
        'en' => 'Process and technical ability to execute and evidence data-subject rights (erase/restrict] across systems and lineage.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'dsdr-governance',
          'label' => [
            'de' => 'DSDR Governance',
            'en' => 'DSDR governance',
          ],
        ],
        [
          'type' => 'tool',
          'route' => 'tools.pii-dsdr-readiness-checker',
          'label' => [
            'de' => 'PII/DSDR Readiness',
            'en' => 'PII/DSDR readiness',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
      ],
    ],
    [
      'id' => 'retention',
      'order' => 820,
      'category' => 'privacy',
      'term' => [
        'de' => 'Retention',
        'en' => 'Retention',
      ],
      'aliases' => [
        'Aufbewahrung',
        'Speicherbegrenzung',
        'Archive',
      ],
      'definition' => [
        'de' => 'Regeln, wie lange Daten aktiv oder archiviert bleiben dürfen — getrennt von Backup und analytischen Marts.',
        'en' => 'Rules for how long data may stay active or archived — separated from backup and analytical marts.',
      ],
      'related' => [
        [
          'type' => 'compliance',
          'id' => 'gdpr',
          'label' => [
            'de' => 'DSGVO / GDPR',
            'en' => 'GDPR',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dsdr',
          'label' => [
            'de' => 'DSDR',
            'en' => 'DSDR',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-lifecycle-retention',
          'label' => [
            'de' => 'Data Lifecycle & Retention',
            'en' => 'Data lifecycle & retention',
          ],
        ],
      ],
    ],
    [
      'id' => 'masking',
      'order' => 830,
      'category' => 'privacy',
      'term' => [
        'de' => 'Masking',
        'en' => 'Masking',
      ],
      'aliases' => [
        'Dynamic Masking',
      ],
      'definition' => [
        'de' => 'Technik, sensible Werte für unberechtigte Rollen zu verbergen oder zu ersetzen — idealerweise policy-gesteuert und lineage-bewusst.',
        'en' => 'Technique to hide or replace sensitive values for unauthorized roles — ideally policy-driven and lineage-aware.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'snowflake-masking-policies-qlik-section-access',
          'label' => [
            'de' => 'Masking & Section Access',
            'en' => 'Masking & section access',
          ],
        ],
        [
          'type' => 'tool',
          'route' => 'tools.pii-policy-generator',
          'label' => [
            'de' => 'PII Policy Generator',
            'en' => 'PII policy generator',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'tokenization',
          'label' => [
            'de' => 'Tokenization',
            'en' => 'Tokenization',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-classification',
      'order' => 840,
      'category' => 'privacy',
      'term' => [
        'de' => 'Data Classification',
        'en' => 'Data Classification',
      ],
      'aliases' => [
        'Classification',
        'Klassifikation',
      ],
      'definition' => [
        'de' => 'Labeling von Sensitivity/Purpose-Klasse, das Schutz und Nutzung steuert.',
        'en' => 'Labeling sensitivity or purpose class that drives protection and use.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'sensitivity-label',
          'label' => [
            'de' => 'Sensitivity Label',
            'en' => 'Sensitivity Label',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'pii-privacy-governance',
          'label' => [
            'de' => 'PII Privacy Governance',
            'en' => 'PII privacy governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'sensitivity-label',
      'order' => 850,
      'category' => 'privacy',
      'term' => [
        'de' => 'Sensitivity Label',
        'en' => 'Sensitivity Label',
      ],
      'aliases' => [
        'Sensitivity',
        'Sensitivitätslabel',
      ],
      'definition' => [
        'de' => 'Plattform-Label (z. B. Purview], das Policy an Assets/Spalten bindet.',
        'en' => 'Platform label (e.g. Purview] binding policy to assets or columns.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-classification',
          'label' => [
            'de' => 'Data Classification',
            'en' => 'Data Classification',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'microsoft-fabric',
          'label' => [
            'de' => 'Microsoft Fabric',
            'en' => 'Microsoft Fabric',
          ],
        ],
      ],
    ],
    [
      'id' => 'purpose-limitation',
      'order' => 860,
      'category' => 'privacy',
      'term' => [
        'de' => 'Purpose Limitation',
        'en' => 'Purpose Limitation',
      ],
      'aliases' => [
        'Purpose Binding',
        'Zweckbindung',
      ],
      'definition' => [
        'de' => 'Nutzung nur für den vereinbarten Zweck — vor Tooling und Mart-Design.',
        'en' => 'Use only for the agreed purpose — before tooling and mart design.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'compliance',
          'id' => 'gdpr',
          'label' => [
            'de' => 'DSGVO / GDPR',
            'en' => 'GDPR',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'pii-privacy-governance',
          'label' => [
            'de' => 'PII Privacy Governance',
            'en' => 'PII privacy governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'tokenization',
      'order' => 870,
      'category' => 'privacy',
      'term' => [
        'de' => 'Tokenization',
        'en' => 'Tokenization',
      ],
      'aliases' => [
        'Tokenize',
        'Tokenisierung',
      ],
      'definition' => [
        'de' => 'Sensible Werte durch reversible Tokens unter kontrolliertem Vaulting ersetzen.',
        'en' => 'Replace sensitive values with reversible tokens under controlled vaulting.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'masking',
          'label' => [
            'de' => 'Masking',
            'en' => 'Masking',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pseudonymization',
          'label' => [
            'de' => 'Pseudonymization',
            'en' => 'Pseudonymization',
          ],
        ],
      ],
    ],
    [
      'id' => 'pseudonymization',
      'order' => 880,
      'category' => 'privacy',
      'term' => [
        'de' => 'Pseudonymization',
        'en' => 'Pseudonymization',
      ],
      'aliases' => [
        'Pseudonymisation',
        'Pseudonymisierung',
      ],
      'definition' => [
        'de' => 'Identifizierbarkeit reduzieren, kontrolliertes Re-Linken unter Safeguards erlauben.',
        'en' => 'Reduce identifiability while allowing controlled re-link under safeguards.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'anonymization',
          'label' => [
            'de' => 'Anonymization',
            'en' => 'Anonymization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'masking',
          'label' => [
            'de' => 'Masking',
            'en' => 'Masking',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
      ],
    ],
    [
      'id' => 'anonymization',
      'order' => 890,
      'category' => 'privacy',
      'term' => [
        'de' => 'Anonymization',
        'en' => 'Anonymization',
      ],
      'aliases' => [
        'Anonymisation',
        'Anonymisierung',
      ],
      'definition' => [
        'de' => 'Irreversible Entfernung persönlicher Identifizierbarkeit für ein definiertes Bedrohungsmodell.',
        'en' => 'Irreversible removal of personal identifiability for a stated threat model.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'pseudonymization',
          'label' => [
            'de' => 'Pseudonymization',
            'en' => 'Pseudonymization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
      ],
    ],
    [
      'id' => 'redaction',
      'order' => 900,
      'category' => 'privacy',
      'term' => [
        'de' => 'Redaction',
        'en' => 'Redaction',
      ],
      'aliases' => [
        'Drop/Redact',
        'Schwärzung',
      ],
      'definition' => [
        'de' => 'Hochrisiko-Felder droppen/blanken, damit sie kuratierte/Mart-Layer nie erreichen.',
        'en' => 'Drop or blank high-risk fields so they never reach curated or mart layers.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'masking',
          'label' => [
            'de' => 'Masking',
            'en' => 'Masking',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
      ],
    ],
    [
      'id' => 'workforce-policy',
      'order' => 910,
      'category' => 'privacy',
      'term' => [
        'de' => 'Workforce / Employee Data Policy',
        'en' => 'Workforce / Employee Data Policy',
      ],
      'aliases' => [
        'Employee Data Policy',
        'Mitarbeiterdaten-Policy',
      ],
      'definition' => [
        'de' => 'Eigene Handling-Regeln für Workforce-Identität vs. Kunden-PII von RAW bis Mart.',
        'en' => 'Separate handling rules for workforce identity vs customer PII in RAW→Mart.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'purpose-limitation',
          'label' => [
            'de' => 'Purpose Limitation',
            'en' => 'Purpose Limitation',
          ],
        ],
      ],
    ],
    [
      'id' => 'rbac',
      'order' => 1000,
      'category' => 'security',
      'term' => [
        'de' => 'RBAC',
        'en' => 'RBAC',
      ],
      'aliases' => [
        'Role-Based Access Control',
      ],
      'definition' => [
        'de' => 'Zugriff über Rollenmitgliedschaft.',
        'en' => 'Access by role membership.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'abac',
          'label' => [
            'de' => 'ABAC',
            'en' => 'ABAC',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => [
            'de' => 'Access & Security Governance',
            'en' => 'Access & security governance',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'access-security-ops',
          'label' => [
            'de' => 'Access & Security Ops',
            'en' => 'Access & security ops',
          ],
        ],
      ],
    ],
    [
      'id' => 'abac',
      'order' => 1010,
      'category' => 'security',
      'term' => [
        'de' => 'ABAC',
        'en' => 'ABAC',
      ],
      'aliases' => [
        'Attribute-Based Access Control',
      ],
      'definition' => [
        'de' => 'Zugriff über Attribute (Clearance, Purpose, Residency usw.].',
        'en' => 'Access by attributes (clearance, purpose, residency, etc.].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => [
            'de' => 'Access & Security Governance',
            'en' => 'Access & security governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'least-privilege',
      'order' => 1020,
      'category' => 'security',
      'term' => [
        'de' => 'Least Privilege',
        'en' => 'Least Privilege',
      ],
      'aliases' => [
        'Least Privilege Access',
        'Minimalrechte',
      ],
      'definition' => [
        'de' => 'Minimaler Zugriff für die Aufgabe — sonst Default Deny.',
        'en' => 'Minimum access needed for the job — default deny elsewhere.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'access-recertification',
          'label' => [
            'de' => 'Access Recertification',
            'en' => 'Access Recertification',
          ],
        ],
      ],
    ],
    [
      'id' => 'segregation-of-duties',
      'order' => 1030,
      'category' => 'security',
      'term' => [
        'de' => 'Segregation of Duties (SoD]',
        'en' => 'Segregation of Duties (SoD]',
      ],
      'aliases' => [
        'SoD',
        'Funktionstrennung',
      ],
      'definition' => [
        'de' => 'Kollidierende Duties trennen (z. B. Grant vs. Approve], um Missbrauchsrisiko zu senken.',
        'en' => 'Split conflicting duties (e.g. grant vs approve] to reduce abuse risk.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'raci',
          'label' => [
            'de' => 'RACI',
            'en' => 'RACI',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => [
            'de' => 'Access & Security Governance',
            'en' => 'Access & security governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'section-access',
      'order' => 1040,
      'category' => 'security',
      'term' => [
        'de' => 'Section Access',
        'en' => 'Section Access',
      ],
      'aliases' => [
        'Qlik Section Access',
      ],
      'definition' => [
        'de' => 'Qlik Row-Reduction Security Model in Apps.',
        'en' => 'Qlik row-reduction security model in apps.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'snowflake-masking-policies-qlik-section-access',
          'label' => [
            'de' => 'Masking & Section Access',
            'en' => 'Masking & section access',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'row-level-security',
          'label' => [
            'de' => 'Row-Level Security (RLS]',
            'en' => 'Row-Level Security (RLS]',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'qvd',
          'label' => [
            'de' => 'QVD',
            'en' => 'QVD',
          ],
        ],
      ],
    ],
    [
      'id' => 'row-level-security',
      'order' => 1050,
      'category' => 'security',
      'term' => [
        'de' => 'Row-Level Security (RLS]',
        'en' => 'Row-Level Security (RLS]',
      ],
      'aliases' => [
        'RLS',
      ],
      'definition' => [
        'de' => 'Zeilen nach User-/Rollen-Claims in Warehouse oder BI filtern.',
        'en' => 'Filter rows by user or role claims in warehouse or BI.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'section-access',
          'label' => [
            'de' => 'Section Access',
            'en' => 'Section Access',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => [
            'de' => 'Access & Security Governance',
            'en' => 'Access & security governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'access-recertification',
      'order' => 1060,
      'category' => 'security',
      'term' => [
        'de' => 'Access Recertification',
        'en' => 'Access Recertification',
      ],
      'aliases' => [
        'Access Review',
        'Attestation',
        'Zugriffszertifizierung',
      ],
      'definition' => [
        'de' => 'Periodische Neu-Freigabe, dass Entitlements noch nötig sind.',
        'en' => 'Periodic re-approval that entitlements are still needed.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'iam',
          'label' => [
            'de' => 'IAM',
            'en' => 'IAM',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'access-security-ops',
          'label' => [
            'de' => 'Access & Security Ops',
            'en' => 'Access & security ops',
          ],
        ],
      ],
    ],
    [
      'id' => 'iam',
      'order' => 1070,
      'category' => 'security',
      'term' => [
        'de' => 'IAM',
        'en' => 'IAM',
      ],
      'aliases' => [
        'Identity Governance',
        'Identity and Access Management',
      ],
      'definition' => [
        'de' => 'Identity- and Access-Management-Rückgrat für Authentifizierung gegenüber Datensystemen.',
        'en' => 'Identity and access management backbone for authenticating subjects to data systems.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'access-recertification',
          'label' => [
            'de' => 'Access Recertification',
            'en' => 'Access Recertification',
          ],
        ],
      ],
    ],
    [
      'id' => 'lineage',
      'order' => 1100,
      'category' => 'metadata',
      'term' => [
        'de' => 'Lineage',
        'en' => 'Lineage',
      ],
      'aliases' => [
        'Data Lineage',
        'Datenherkunft',
      ],
      'definition' => [
        'de' => 'Nachvollziehbare Herkunft und Transformation von Daten — von Quelle über Pipelines bis zu Reports und Löschpfaden.',
        'en' => 'Traceable origin and transformation of data — from source through pipelines to reports and deletion paths.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'propagating-pii-metadata-across-data-warehouses',
          'label' => [
            'de' => 'PII-Metadaten propagieren',
            'en' => 'Propagating PII metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dsdr',
          'label' => [
            'de' => 'DSDR',
            'en' => 'DSDR',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'column-lineage',
          'label' => [
            'de' => 'Column Lineage',
            'en' => 'Column Lineage',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'impact-analysis',
          'label' => [
            'de' => 'Impact Analysis',
            'en' => 'Impact Analysis',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-catalog',
      'order' => 1110,
      'category' => 'metadata',
      'term' => [
        'de' => 'Data Catalog',
        'en' => 'Data Catalog',
      ],
      'aliases' => [
        'Katalog',
        'Unified Catalog',
      ],
      'definition' => [
        'de' => 'Auffindbare Inventar- und Bedeutungsschicht für Assets, Begriffe, Owner und Policies — Grundlage für Discovery und Governance.',
        'en' => 'Discoverable inventory and meaning layer for assets, terms, owners, and policies — the base for discovery and governance.',
      ],
      'related' => [
        [
          'type' => 'series',
          'id' => 'metadata-deep-dive',
          'label' => [
            'de' => 'MetaData Deep Dive',
            'en' => 'MetaData deep dive',
          ],
        ],
        [
          'type' => 'tool',
          'route' => 'tools.pureview-glossary-generator',
          'label' => [
            'de' => 'PureView Glossary Generator',
            'en' => 'PureView glossary generator',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'metadata-operating-model',
          'label' => [
            'de' => 'Metadata Operating Model',
            'en' => 'Metadata operating model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'business-glossary',
          'label' => [
            'de' => 'Business Glossary',
            'en' => 'Business Glossary',
          ],
        ],
      ],
    ],
    [
      'id' => 'metadata',
      'order' => 1120,
      'category' => 'metadata',
      'term' => [
        'de' => 'Metadata',
        'en' => 'Metadata',
      ],
      'aliases' => [
        'Metadaten',
        'Technical Metadata',
      ],
      'definition' => [
        'de' => 'Daten über Daten: Schema, Bedeutung, Owner, Klassifikation, Lineage und Policies — der Steuerungshebel der Governance.',
        'en' => 'Data about data: schema, meaning, owners, classification, lineage, and policies — the control lever of governance.',
      ],
      'related' => [
        [
          'type' => 'series',
          'id' => 'metadata-deep-dive',
          'label' => [
            'de' => 'MetaData Deep Dive',
            'en' => 'MetaData deep dive',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'metadata-driven-governance-with-dbt-meta',
          'label' => [
            'de' => 'dbt meta Governance',
            'en' => 'dbt meta governance',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'metadata-operating-model',
          'label' => [
            'de' => 'Metadata Operating Model',
            'en' => 'Metadata operating model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'active-metadata',
          'label' => [
            'de' => 'Active Metadata',
            'en' => 'Active Metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'business-glossary',
      'order' => 1130,
      'category' => 'metadata',
      'term' => [
        'de' => 'Business Glossary',
        'en' => 'Business Glossary',
      ],
      'aliases' => [
        'Glossary',
        'Fachglossar',
      ],
      'definition' => [
        'de' => 'Abgestimmte Fachbegriffe und Definitionen — verwandt mit, aber nicht identisch zum Catalog.',
        'en' => 'Agreed business terms and definitions — related to, not identical with, the catalog.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-catalog',
          'label' => [
            'de' => 'Data Catalog',
            'en' => 'Data Catalog',
          ],
        ],
        [
          'type' => 'tool',
          'route' => 'tools.pureview-glossary-generator',
          'label' => [
            'de' => 'PureView Glossary Generator',
            'en' => 'PureView glossary generator',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'glossary.index',
          'label' => [
            'de' => 'Glossary Hub',
            'en' => 'Glossary hub',
          ],
        ],
      ],
    ],
    [
      'id' => 'active-metadata',
      'order' => 1140,
      'category' => 'metadata',
      'term' => [
        'de' => 'Active Metadata',
        'en' => 'Active Metadata',
      ],
      'aliases' => [
        'Aktive Metadaten',
      ],
      'definition' => [
        'de' => 'Metadaten, die Automation steuern (Policies, Quality, Routing] — nicht nur Dokumentation.',
        'en' => 'Metadata that drives automation (policies, quality, routing], not only documentation.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'metadata',
          'label' => [
            'de' => 'Metadata',
            'en' => 'Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'activate-metadata-through-automation',
          'label' => [
            'de' => 'Metadaten durch Automation aktivieren',
            'en' => 'Activate metadata through automation',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'governance-metadata-that-controls-data',
          'label' => [
            'de' => 'Governance-Metadaten, die Daten steuern',
            'en' => 'Governance metadata that controls data',
          ],
        ],
      ],
    ],
    [
      'id' => 'metadata-provenance',
      'order' => 1150,
      'category' => 'metadata',
      'term' => [
        'de' => 'Metadata Provenance',
        'en' => 'Metadata Provenance',
      ],
      'aliases' => [
        'Provenance',
        'Herkunft',
      ],
      'definition' => [
        'de' => 'Wer/was eine Metadaten-Aussage authorisiert hat und aus welcher Source of Truth.',
        'en' => 'Who or what authored a metadata claim and from which source of truth.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'metadata',
          'label' => [
            'de' => 'Metadata',
            'en' => 'Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metadata-harvesting',
          'label' => [
            'de' => 'Metadata Harvesting',
            'en' => 'Metadata Harvesting',
          ],
        ],
      ],
    ],
    [
      'id' => 'metadata-harvesting',
      'order' => 1160,
      'category' => 'metadata',
      'term' => [
        'de' => 'Metadata Harvesting',
        'en' => 'Metadata Harvesting',
      ],
      'aliases' => [
        'Harvest',
        'Harvesting',
      ],
      'definition' => [
        'de' => 'Automatisches Einsammeln technischer Metadaten aus Plattformen in den Catalog.',
        'en' => 'Automated collection of technical metadata from platforms into the catalog.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'harvest-metadata-automatically',
          'label' => [
            'de' => 'Metadaten automatisch harvesten',
            'en' => 'Harvest metadata automatically',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-catalog',
          'label' => [
            'de' => 'Data Catalog',
            'en' => 'Data Catalog',
          ],
        ],
      ],
    ],
    [
      'id' => 'impact-analysis',
      'order' => 1170,
      'category' => 'metadata',
      'term' => [
        'de' => 'Impact Analysis',
        'en' => 'Impact Analysis',
      ],
      'aliases' => [
        'Field Impact',
        'Auswirkungsanalyse',
      ],
      'definition' => [
        'de' => 'Downstream-Blast-Radius einer Feld-/Modell-/KPI-Änderung über Lineage nachverfolgen.',
        'en' => 'Trace downstream blast radius of a field, model, or KPI change via lineage.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'lineage-impact-and-metadata-propagation',
          'label' => [
            'de' => 'Lineage, Impact und Propagation',
            'en' => 'Lineage, impact, and metadata propagation',
          ],
        ],
      ],
    ],
    [
      'id' => 'column-lineage',
      'order' => 1180,
      'category' => 'metadata',
      'term' => [
        'de' => 'Column Lineage',
        'en' => 'Column Lineage',
      ],
      'aliases' => [
        'Field Lineage',
        'Spalten-Lineage',
      ],
      'definition' => [
        'de' => 'Feld-level Herkunfts-/Transform-Pfad — nötig für PII-Propagation und DSDR.',
        'en' => 'Field-level origin and transform path (needed for PII propagation and DSDR].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'propagating-pii-metadata-across-data-warehouses',
          'label' => [
            'de' => 'PII-Metadaten propagieren',
            'en' => 'Propagating PII metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'metadata-enrichment',
      'order' => 1190,
      'category' => 'metadata',
      'term' => [
        'de' => 'Metadata Enrichment',
        'en' => 'Metadata Enrichment',
      ],
      'aliases' => [
        'Enrichment',
        'Anreicherung',
      ],
      'definition' => [
        'de' => 'Technische Assets um Business-Kontext, Owner, Klassifikation und KPI-Links ergänzen.',
        'en' => 'Add business context, owners, classification, and KPI links to technical assets.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'enrich-technical-metadata-with-business-context',
          'label' => [
            'de' => 'Technische Metadaten anreichern',
            'en' => 'Enrich technical metadata with business context',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'business-glossary',
          'label' => [
            'de' => 'Business Glossary',
            'en' => 'Business Glossary',
          ],
        ],
      ],
    ],
    [
      'id' => 'ai-ready-metadata',
      'order' => 1200,
      'category' => 'metadata',
      'term' => [
        'de' => 'AI-Ready Metadata',
        'en' => 'AI-Ready Metadata',
      ],
      'aliases' => [
        'AI-ready',
      ],
      'definition' => [
        'de' => 'Vollständige, aktuelle, zulässige Metadaten geeignet für Assistenten und RAG.',
        'en' => 'Complete, current, permitted-use metadata suitable for assistants and RAG.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'prepare-metadata-for-ai-rag-and-model-training',
          'label' => [
            'de' => 'Metadaten für AI/RAG vorbereiten',
            'en' => 'Prepare metadata for AI, RAG, and model training',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation]',
            'en' => 'RAG (Retrieval-Augmented Generation]',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'ai-foundations',
          'label' => [
            'de' => 'AI Foundations',
            'en' => 'AI foundations',
          ],
        ],
      ],
    ],
    [
      'id' => 'dbt-meta',
      'order' => 1210,
      'category' => 'metadata',
      'term' => [
        'de' => 'dbt meta',
        'en' => 'dbt meta',
      ],
      'aliases' => [
        'dbt Metadata',
        'meta',
      ],
      'definition' => [
        'de' => 'Strukturierte Metadaten in dbt-YAML, die Governance-Automation steuern.',
        'en' => 'Structured metadata in dbt YAML used to drive governance automation.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'active-metadata',
          'label' => [
            'de' => 'Active Metadata',
            'en' => 'Active Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'metadata-driven-governance-with-dbt-meta',
          'label' => [
            'de' => 'dbt meta Governance',
            'en' => 'dbt meta governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'centralized-vs-federated-metadata',
      'order' => 1220,
      'category' => 'metadata',
      'term' => [
        'de' => 'Centralized vs Federated Metadata',
        'en' => 'Centralized vs Federated Metadata',
      ],
      'aliases' => [
        'Distributed Metadata',
        'Federated Metadata',
      ],
      'definition' => [
        'de' => 'Pro Capability entscheiden, was zentrale Discovery vs. domänenautorisierte Wahrheit ist.',
        'en' => 'Decide per capability what is central discovery vs domain-authored truth.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'centralized-federated-or-distributed-metadata',
          'label' => [
            'de' => 'Zentral, federiert oder verteilt',
            'en' => 'Centralized, federated, or distributed metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'federated-governance',
          'label' => [
            'de' => 'Federated Governance',
            'en' => 'Federated Governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'raci',
      'order' => 1300,
      'category' => 'process',
      'term' => [
        'de' => 'RACI',
        'en' => 'RACI',
      ],
      'aliases' => [
        'Responsible',
        'Accountable',
        'Consulted',
        'Informed',
      ],
      'definition' => [
        'de' => 'Rollenmatrix für Entscheidungen: wer ausführt (R], wer verantwortet (A], wer berät (C], wer informiert wird (I].',
        'en' => 'Role matrix for decisions: who executes (R], who owns (A], who is consulted (C], who is informed (I].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
        [
          'type' => 'tool',
          'route' => 'tools.stakeholder-matrix',
          'label' => [
            'de' => 'Stakeholder & RACI Matrix',
            'en' => 'Stakeholder & RACI matrix',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'roles.index',
          'label' => [
            'de' => 'Roles Hub',
            'en' => 'Roles hub',
          ],
        ],
        [
          'type' => 'series',
          'id' => 'roles-hub',
          'label' => [
            'de' => 'Roles and Decision Rights',
            'en' => 'Roles and decision rights',
          ],
        ],
      ],
    ],
    [
      'id' => 'kpi-governance',
      'order' => 1310,
      'category' => 'process',
      'term' => [
        'de' => 'KPI Governance',
        'en' => 'KPI Governance',
      ],
      'aliases' => [
        'Metric Definition',
        'Single Source of Truth',
      ],
      'definition' => [
        'de' => 'Klare Definition, Owner und Änderungsprozess für Kennzahlen — verhindert widersprüchliche Zahlen in Tools und Meetings.',
        'en' => 'Clear definition, owner, and change process for metrics — prevents conflicting numbers across tools and meetings.',
      ],
      'related' => [
        [
          'type' => 'tool',
          'route' => 'tools.kpi-definition',
          'label' => [
            'de' => 'KPI Definition',
            'en' => 'KPI definition',
          ],
        ],
        [
          'type' => 'series',
          'id' => 'governance-pillars',
          'label' => [
            'de' => '8 Säulen',
            'en' => '8 pillars',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted metrics',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metric-store',
          'label' => [
            'de' => 'Metric / KPI Store',
            'en' => 'Metric / KPI Store',
          ],
        ],
      ],
    ],
    [
      'id' => 'decision-rights',
      'order' => 1320,
      'category' => 'process',
      'term' => [
        'de' => 'Decision Rights',
        'en' => 'Decision Rights',
      ],
      'aliases' => [
        'Entscheidungsrechte',
      ],
      'definition' => [
        'de' => 'Wer Zweck, Zugriff, Definitionen und Exceptions entscheiden darf — und auf welchem Risk Tier.',
        'en' => 'Who may decide purpose, access, definitions, and exceptions — and at what risk tier.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'raci',
          'label' => [
            'de' => 'RACI',
            'en' => 'RACI',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
        [
          'type' => 'series',
          'id' => 'roles-hub',
          'label' => [
            'de' => 'Roles and Decision Rights',
            'en' => 'Roles and decision rights',
          ],
        ],
      ],
    ],
    [
      'id' => 'operating-model',
      'order' => 1330,
      'category' => 'process',
      'term' => [
        'de' => 'Operating Model',
        'en' => 'Operating Model',
      ],
      'aliases' => [
        'Governance Operating Model',
        'Betriebsmodell',
      ],
      'definition' => [
        'de' => 'Cadence, Handoffs, Capacity und Eskalation, die Rollen real machen.',
        'en' => 'Cadence, handoffs, capacity, and escalation that make roles real.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'governance-cadence',
          'label' => [
            'de' => 'Governance Cadence',
            'en' => 'Governance Cadence',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'capacity-model',
          'label' => [
            'de' => 'Stewardship Capacity Model',
            'en' => 'Stewardship Capacity Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'escalation-path',
          'label' => [
            'de' => 'Escalation Path',
            'en' => 'Escalation Path',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operating-and-governing-the-platform',
          'label' => [
            'de' => 'Plattform betreiben und steuern',
            'en' => 'Operating and governing the platform',
          ],
        ],
      ],
    ],
    [
      'id' => 'escalation-path',
      'order' => 1340,
      'category' => 'process',
      'term' => [
        'de' => 'Escalation Path',
        'en' => 'Escalation Path',
      ],
      'aliases' => [
        'Escalation',
        'Eskalation',
      ],
      'definition' => [
        'de' => 'Definierter Weg, wenn Steward/Owner/Platform innerhalb des SLA nicht lösen können.',
        'en' => 'Defined route when Steward, Owner, or Platform cannot resolve within SLA.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'operating-model',
          'label' => [
            'de' => 'Operating Model',
            'en' => 'Operating Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'sla-slo',
          'label' => [
            'de' => 'SLA / SLO',
            'en' => 'SLA / SLO',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'stewardship-capacity',
          'label' => [
            'de' => 'Stewardship Capacity',
            'en' => 'Stewardship capacity',
          ],
        ],
      ],
    ],
    [
      'id' => 'stewardship-intake',
      'order' => 1350,
      'category' => 'process',
      'term' => [
        'de' => 'Stewardship Intake',
        'en' => 'Stewardship Intake',
      ],
      'aliases' => [
        'Intake Model',
        'Intake',
      ],
      'definition' => [
        'de' => 'Priorisierter Eingangspfad für Definitions-, DQ- und Klassifikationsarbeit.',
        'en' => 'Prioritized entry path for definition, DQ, and classification work.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'capacity-model',
          'label' => [
            'de' => 'Stewardship Capacity Model',
            'en' => 'Stewardship Capacity Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'stewardship-capacity',
          'label' => [
            'de' => 'Stewardship Capacity',
            'en' => 'Stewardship capacity',
          ],
        ],
      ],
    ],
    [
      'id' => 'capacity-model',
      'order' => 1360,
      'category' => 'process',
      'term' => [
        'de' => 'Stewardship Capacity Model',
        'en' => 'Stewardship Capacity Model',
      ],
      'aliases' => [
        'Capacity',
        'Kapazitätsmodell',
      ],
      'definition' => [
        'de' => 'FTE-/geschützte-Zeit-Modell, damit Stewardship finanziert ist — nicht „nebenbei“.',
        'en' => 'FTE or protected-time model so stewardship is funded, not „on the side“.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'stewardship-capacity',
          'label' => [
            'de' => 'Stewardship Capacity',
            'en' => 'Stewardship capacity',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'operating-model',
          'label' => [
            'de' => 'Operating Model',
            'en' => 'Operating Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
      ],
    ],
    [
      'id' => 'governance-cadence',
      'order' => 1370,
      'category' => 'process',
      'term' => [
        'de' => 'Governance Cadence',
        'en' => 'Governance Cadence',
      ],
      'aliases' => [
        'Cadence',
        'Governance-Rhythmus',
      ],
      'definition' => [
        'de' => 'Wiederkehrende Foren/Reviews (Klassifikation, Access, KPI, Council].',
        'en' => 'Recurring forums and reviews (classification, access, KPI, council].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'operating-model',
          'label' => [
            'de' => 'Operating Model',
            'en' => 'Operating Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance Center of Excellence',
            'en' => 'Governance Center of Excellence',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-lifecycle',
      'order' => 1380,
      'category' => 'process',
      'term' => [
        'de' => 'Data Lifecycle',
        'en' => 'Data Lifecycle',
      ],
      'aliases' => [
        'Lifecycle',
        'Retirement',
        'Datenlebenszyklus',
      ],
      'definition' => [
        'de' => 'Create → Use → Retain/Archive → Delete/Retire mit verantwortlichen Stages.',
        'en' => 'Create → use → retain/archive → delete/retire with accountable stages.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'data-lifecycle-retention',
          'label' => [
            'de' => 'Data Lifecycle & Retention',
            'en' => 'Data lifecycle & retention',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'retention',
          'label' => [
            'de' => 'Retention',
            'en' => 'Retention',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'missing-pieces-data-lifecycle-retirement',
          'label' => [
            'de' => 'Missing Pieces: Lifecycle',
            'en' => 'Missing pieces: lifecycle',
          ],
        ],
      ],
    ],
    [
      'id' => 'role-sprawl',
      'order' => 1390,
      'category' => 'process',
      'term' => [
        'de' => 'Role Sprawl',
        'en' => 'Role Sprawl',
      ],
      'aliases' => [
        'Role Sprawl',
      ],
      'definition' => [
        'de' => 'Zu viele überlappende RACI-Hüte, die Accountability verwässern.',
        'en' => 'Too many overlapping RACI hats that dilute accountability.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'raci',
          'label' => [
            'de' => 'RACI',
            'en' => 'RACI',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'decision-rights',
          'label' => [
            'de' => 'Decision Rights',
            'en' => 'Decision Rights',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'federated-governance',
      'order' => 1400,
      'category' => 'process',
      'term' => [
        'de' => 'Federated Governance',
        'en' => 'Federated Governance',
      ],
      'aliases' => [
        'Federated Model',
        'Federierte Governance',
      ],
      'definition' => [
        'de' => 'Zentrale Standards plus Domänen-Ausführung (vs. rein zentral oder rein lokal].',
        'en' => 'Central standards plus domain execution (vs pure central or pure local].',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-mesh',
          'label' => [
            'de' => 'Data Mesh',
            'en' => 'Data Mesh',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance Center of Excellence',
            'en' => 'Governance Center of Excellence',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'centralized-vs-federated-metadata',
          'label' => [
            'de' => 'Centralized vs Federated Metadata',
            'en' => 'Centralized vs Federated Metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'policy-as-code',
      'order' => 1410,
      'category' => 'process',
      'term' => [
        'de' => 'Policy as Code',
        'en' => 'Policy as Code',
      ],
      'aliases' => [
        'PaC',
        'Policy-as-Code',
      ],
      'definition' => [
        'de' => 'Durchsetzbare Access-/Quality-/Privacy-Regeln in versionierten, testbaren Artefakten.',
        'en' => 'Enforceable access, quality, and privacy rules in versioned, testable artifacts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'missing-pieces-policy-access-governance',
          'label' => [
            'de' => 'Missing Pieces: Policy & Access',
            'en' => 'Missing pieces: policy & access',
          ],
        ],
      ],
    ],
    [
      'id' => 'rag',
      'order' => 1500,
      'category' => 'ai',
      'term' => [
        'de' => 'RAG (Retrieval-Augmented Generation]',
        'en' => 'RAG (Retrieval-Augmented Generation]',
      ],
      'aliases' => [
        'Retrieval-Augmented Generation',
      ],
      'definition' => [
        'de' => 'LLM-Antworten mit retrieved, governten Dokumenten/Daten begründen.',
        'en' => 'Ground LLM answers in retrieved governed documents or data.',
      ],
      'related' => [
        [
          'type' => 'story',
          'id' => 'ai-rag',
          'label' => [
            'de' => 'AI & RAG',
            'en' => 'AI & RAG',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ai-ready-metadata',
          'label' => [
            'de' => 'AI-Ready Metadata',
            'en' => 'AI-Ready Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'ai-foundations',
          'label' => [
            'de' => 'AI Foundations',
            'en' => 'AI foundations',
          ],
        ],
      ],
    ],
    [
      'id' => 'ai-guardrails',
      'order' => 1510,
      'category' => 'ai',
      'term' => [
        'de' => 'AI Guardrails',
        'en' => 'AI Guardrails',
      ],
      'aliases' => [
        'Guardrails',
      ],
      'definition' => [
        'de' => 'Kontrollen, die Prompts, Tools und Outputs für Safety/Compliance einschränken.',
        'en' => 'Controls that constrain prompts, tools, and outputs for safety and compliance.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation]',
            'en' => 'RAG (Retrieval-Augmented Generation]',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'human-in-the-loop',
          'label' => [
            'de' => 'Human-in-the-Loop',
            'en' => 'Human-in-the-Loop',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-gov',
          'label' => [
            'de' => 'AI Governance',
            'en' => 'AI governance',
          ],
        ],
        [
          'type' => 'path',
          'id' => 'ai-foundations',
          'label' => [
            'de' => 'AI Foundations',
            'en' => 'AI foundations',
          ],
        ],
      ],
    ],
    [
      'id' => 'prompt-injection',
      'order' => 1520,
      'category' => 'ai',
      'term' => [
        'de' => 'Prompt Injection',
        'en' => 'Prompt Injection',
      ],
      'aliases' => [
        'Injection',
      ],
      'definition' => [
        'de' => 'Angriff, der Modellverhalten über bösartigen Inhalt in Inputs/Context hijackt.',
        'en' => 'Attack that hijacks model behavior via malicious content in inputs or context.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-failures',
          'label' => [
            'de' => 'AI Failures',
            'en' => 'AI failures',
          ],
        ],
      ],
    ],
    [
      'id' => 'hallucination',
      'order' => 1530,
      'category' => 'ai',
      'term' => [
        'de' => 'Hallucination',
        'en' => 'Hallucination',
      ],
      'aliases' => [
        'Halluzination',
      ],
      'definition' => [
        'de' => 'Selbstbewusstes Modell-Output ohne Grounding in retrieved oder Training-Evidence.',
        'en' => 'Confident model output not grounded in retrieved or training evidence.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation]',
            'en' => 'RAG (Retrieval-Augmented Generation]',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-failures',
          'label' => [
            'de' => 'AI Failures',
            'en' => 'AI failures',
          ],
        ],
      ],
    ],
    [
      'id' => 'human-in-the-loop',
      'order' => 1540,
      'category' => 'ai',
      'term' => [
        'de' => 'Human-in-the-Loop',
        'en' => 'Human-in-the-Loop',
      ],
      'aliases' => [
        'HITL',
      ],
      'definition' => [
        'de' => 'Pflicht-Human-Review/Approval für risikoreiche AI-Aktionen.',
        'en' => 'Mandatory human review or approval for high-risk AI actions.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-gov',
          'label' => [
            'de' => 'AI Governance',
            'en' => 'AI governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'training-data',
      'order' => 1550,
      'category' => 'ai',
      'term' => [
        'de' => 'Training Data',
        'en' => 'Training Data',
      ],
      'aliases' => [
        'Trainingsdaten',
      ],
      'definition' => [
        'de' => 'Datasets zum Trainieren/Fine-Tunen — brauchen Lineage, Rechte und Qualität.',
        'en' => 'Datasets used to train or fine-tune models — need lineage, rights, and quality.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'ai-ready-metadata',
          'label' => [
            'de' => 'AI-Ready Metadata',
            'en' => 'AI-Ready Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'prepare-metadata-for-ai-rag-and-model-training',
          'label' => [
            'de' => 'Metadaten für AI/RAG vorbereiten',
            'en' => 'Prepare metadata for AI, RAG, and model training',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
      ],
    ],
    [
      'id' => 'inference',
      'order' => 1560,
      'category' => 'ai',
      'term' => [
        'de' => 'Inference',
        'en' => 'Inference',
      ],
      'aliases' => [
        'Model Serving',
        'Inferenz',
      ],
      'definition' => [
        'de' => 'Runtime-Modellausführung gegen neue Inputs.',
        'en' => 'Runtime model execution against new inputs.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation]',
            'en' => 'RAG (Retrieval-Augmented Generation]',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'feature-store',
          'label' => [
            'de' => 'Feature Store',
            'en' => 'Feature Store',
          ],
        ],
      ],
    ],
    [
      'id' => 'feature-store',
      'order' => 1570,
      'category' => 'ai',
      'term' => [
        'de' => 'Feature Store',
        'en' => 'Feature Store',
      ],
      'aliases' => [
        'Feature Store',
      ],
      'definition' => [
        'de' => 'Governte Wiederverwendung von ML-Features mit Versionierung und Serving-Contracts.',
        'en' => 'Governed reuse of ML features with versioning and serving contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'training-data',
          'label' => [
            'de' => 'Training Data',
            'en' => 'Training Data',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-engineer',
      'order' => 34,
      'category' => 'roles',
      'term' => [
        'de' => 'Data Engineer',
        'en' => 'Data Engineer',
      ],
      'aliases' => [
        'Dateningenieur',
        'Pipeline Engineer',
      ],
      'definition' => [
        'de' => 'Baut und betreibt Pipelines, Speicherschichten und Integrationsmuster — liefert belastbare Inputs für Stewardship und Analytics.',
        'en' => 'Builds and runs pipelines, storage layers, and integration patterns — delivering reliable inputs for stewardship and analytics.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'analytics-engineer',
          'label' => [
            'de' => 'Analytics Engineer',
            'en' => 'Analytics Engineer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-architect',
          'label' => [
            'de' => 'Data Architect',
            'en' => 'Data Architect',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operating-and-governing-the-platform',
          'label' => [
            'de' => 'Plattform betreiben und steuern',
            'en' => 'Operating and governing the platform',
          ],
        ],
      ],
    ],
    [
      'id' => 'bi-developer',
      'order' => 36,
      'category' => 'roles',
      'term' => [
        'de' => 'BI Developer',
        'en' => 'BI Developer',
      ],
      'aliases' => [
        'Report Developer',
        'Dashboard Developer',
        'BI-Entwickler',
      ],
      'definition' => [
        'de' => 'Setzt Semantik und Visualisierung in BI-Tools um — idealerweise auf zertifizierten Datasets, nicht mit eigener Business-Logik im Report.',
        'en' => 'Implements semantics and visualization in BI tools — ideally on certified datasets, not by inventing business logic inside reports.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'self-service-bi',
          'label' => [
            'de' => 'Self-Service BI',
            'en' => 'Self-Service BI',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'bi-tools',
          'label' => [
            'de' => 'BI-Tools',
            'en' => 'BI tools',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'keeping-business-logic-outside-bi-apps',
          'label' => [
            'de' => 'Business-Logik außerhalb von BI-Apps halten',
            'en' => 'Keep business logic outside BI apps',
          ],
        ],
      ],
    ],
    [
      'id' => 'technical-owner',
      'order' => 38,
      'category' => 'roles',
      'term' => [
        'de' => 'Technical Owner',
        'en' => 'Technical Owner',
      ],
      'aliases' => [
        'Tech Owner',
        'Technischer Owner',
      ],
      'definition' => [
        'de' => 'Verantwortet technische Betriebsfähigkeit eines Data Products oder einer Pipeline — Incidents, Deployments, Tooling — parallel zum fachlichen Data Owner.',
        'en' => 'Owns technical operability of a data product or pipeline — incidents, deployments, tooling — alongside the business Data Owner.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-custodian',
          'label' => [
            'de' => 'Data Custodian',
            'en' => 'Data Custodian',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-ownership-stewardship',
          'label' => [
            'de' => 'Ownership & Stewardship',
            'en' => 'Ownership & stewardship',
          ],
        ],
      ],
    ],
    [
      'id' => 'executive-sponsor',
      'order' => 40,
      'category' => 'roles',
      'term' => [
        'de' => 'Executive Sponsor',
        'en' => 'Executive Sponsor',
      ],
      'aliases' => [
        'Sponsor',
        'Mandate Holder',
        'Auftraggeber',
      ],
      'definition' => [
        'de' => 'Gibt Mandat, Priorität und Eskalationsmacht für Governance- oder Plattform-Initiativen — ohne operatives Day-to-Day Stewardship.',
        'en' => 'Provides mandate, priority, and escalation power for governance or platform initiatives — without day-to-day stewardship work.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'governance-lead',
          'label' => [
            'de' => 'Governance Lead',
            'en' => 'Governance Lead',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance Center of Excellence',
            'en' => 'Governance Center of Excellence',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance CoE',
            'en' => 'Governance CoE',
          ],
        ],
      ],
    ],
    [
      'id' => 'trusted-metrics',
      'order' => 199,
      'category' => 'data',
      'term' => [
        'de' => 'Trusted Metrics',
        'en' => 'Trusted Metrics',
      ],
      'aliases' => [
        'Trusted KPIs',
        'Vertrauenswürdige Kennzahlen',
      ],
      'definition' => [
        'de' => 'Kennzahlen mit klarem Contract, Owner, Grain und Versionierung — so dass Reports dieselbe Wahrheit konsumieren.',
        'en' => 'Metrics with a clear contract, owner, grain, and versioning — so reports consume the same truth.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'kpi-contract',
          'label' => [
            'de' => 'KPI Contract',
            'en' => 'KPI Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metric-store',
          'label' => [
            'de' => 'Metric / KPI Store',
            'en' => 'Metric / KPI Store',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-governance',
          'label' => [
            'de' => 'KPI Governance',
            'en' => 'KPI Governance',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'missing-pieces-trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics (Missing Pieces)',
            'en' => 'Trusted metrics (missing pieces)',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'kpi-metric-governance',
          'label' => [
            'de' => 'KPI- & Metric-Governance',
            'en' => 'KPI & metric governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'kpi-contract',
      'order' => 201,
      'category' => 'data',
      'term' => [
        'de' => 'KPI Contract',
        'en' => 'KPI Contract',
      ],
      'aliases' => [
        'Metric Contract',
        'Kennzahlen-Contract',
      ],
      'definition' => [
        'de' => 'Vereinbarung zu Definition, Grain, Filtern, Owner und Breaking-Change-Regeln einer Kennzahl — vor dem Dashboard.',
        'en' => 'Agreement on definition, grain, filters, owner, and breaking-change rules for a metric — before the dashboard.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted Metrics',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'define-kpi',
          'label' => [
            'de' => 'KPI definieren',
            'en' => 'Define a KPI',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-lake',
      'order' => 392,
      'category' => 'architecture',
      'term' => [
        'de' => 'Data Lake',
        'en' => 'Data Lake',
      ],
      'aliases' => [
        'Lake',
        'Object-Store Lake',
      ],
      'definition' => [
        'de' => 'Speicher für roh und semi-strukturierte Daten in großem Maßstab — ohne Warehouse-Semantik; oft Vorstufe oder Teil eines Lakehouse.',
        'en' => 'Storage for raw and semi-structured data at scale — without warehouse semantics; often a precursor or part of a lakehouse.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'choosing-the-simplest-viable-architecture',
          'label' => [
            'de' => 'Einfachst sinnvolle Architektur',
            'en' => 'Simplest viable architecture',
          ],
        ],
      ],
    ],
    [
      'id' => 'system-of-record',
      'order' => 394,
      'category' => 'architecture',
      'term' => [
        'de' => 'System of Record',
        'en' => 'System of Record',
      ],
      'aliases' => [
        'Source of Record',
        'Authoritative Source',
        'Führendes System',
      ],
      'definition' => [
        'de' => 'Das führende operative System für eine Entität oder Attribute — nicht dasselbe wie die analytische Single Source of Truth.',
        'en' => 'The authoritative operational system for an entity or attributes — not the same as an analytical single source of truth.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'golden-record',
          'label' => [
            'de' => 'Golden Record',
            'en' => 'Golden Record',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-domain',
          'label' => [
            'de' => 'Data Domain',
            'en' => 'Data Domain',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'where-metadata-is-born',
          'label' => [
            'de' => 'Wo Metadaten entstehen',
            'en' => 'Where metadata is born',
          ],
        ],
      ],
    ],
    [
      'id' => 'logical-architecture',
      'order' => 396,
      'category' => 'architecture',
      'term' => [
        'de' => 'Logical Architecture',
        'en' => 'Logical Architecture',
      ],
      'aliases' => [
        'Logische Architektur',
        'Logical Design',
      ],
      'definition' => [
        'de' => 'Schichten, Verträge und Verantwortlichkeiten unabhängig vom konkreten Tooling — Grundlage vor physischer Stack-Wahl.',
        'en' => 'Layers, contracts, and responsibilities independent of specific tooling — the foundation before physical stack choice.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'simplest-viable-architecture',
          'label' => [
            'de' => 'Simplest Viable Architecture',
            'en' => 'Simplest Viable Architecture',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'medallion-architecture',
          'label' => [
            'de' => 'Medallion Architecture',
            'en' => 'Medallion Architecture',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => [
            'de' => 'Jenseits von Bronze-Silver-Gold',
            'en' => 'Beyond bronze-silver-gold',
          ],
        ],
      ],
    ],
    [
      'id' => 'near-real-time',
      'order' => 398,
      'category' => 'architecture',
      'term' => [
        'de' => 'Near Real-Time',
        'en' => 'Near Real-Time',
      ],
      'aliases' => [
        'NRT',
        'Near-Realtime',
        'Fast Real-Time',
      ],
      'definition' => [
        'de' => 'Latenz in Sekunden bis wenigen Minuten — teurer und komplexer als Batch; nur wo Entscheidungen das wirklich brauchen.',
        'en' => 'Latency of seconds to a few minutes — costlier and more complex than batch; only where decisions truly need it.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'batch-processing',
          'label' => [
            'de' => 'Batch Processing',
            'en' => 'Batch Processing',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'orchestration',
          'label' => [
            'de' => 'Orchestration',
            'en' => 'Orchestration',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'choosing-the-simplest-viable-architecture',
          'label' => [
            'de' => 'Einfachst sinnvolle Architektur',
            'en' => 'Simplest viable architecture',
          ],
        ],
      ],
    ],
    [
      'id' => 'batch-processing',
      'order' => 399,
      'category' => 'architecture',
      'term' => [
        'de' => 'Batch Processing',
        'en' => 'Batch Processing',
      ],
      'aliases' => [
        'Batch',
        'Scheduled Load',
        'Batchverarbeitung',
      ],
      'definition' => [
        'de' => 'Geplante, periodische Verarbeitung großer Datenmengen — Standard für die meisten Warehouse- und Mart-Loads.',
        'en' => 'Scheduled, periodic processing of large volumes — the default for most warehouse and mart loads.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'near-real-time',
          'label' => [
            'de' => 'Near Real-Time',
            'en' => 'Near Real-Time',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'elt',
          'label' => [
            'de' => 'ELT',
            'en' => 'ELT',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'orchestration',
          'label' => [
            'de' => 'Orchestration',
            'en' => 'Orchestration',
          ],
        ],
      ],
    ],
    [
      'id' => 'referential-integrity',
      'order' => 532,
      'category' => 'modeling',
      'term' => [
        'de' => 'Referential Integrity',
        'en' => 'Referential Integrity',
      ],
      'aliases' => [
        'RI',
        'Referentielle Integrität',
        'FK Integrity',
      ],
      'definition' => [
        'de' => 'Garantie, dass Foreign Keys auf existierende Parents zeigen — zentral für Facts, Dimensions und DQ-Gates.',
        'en' => 'Guarantee that foreign keys point to existing parents — central for facts, dimensions, and DQ gates.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
      ],
    ],
    [
      'id' => 'bridge-table',
      'order' => 534,
      'category' => 'modeling',
      'term' => [
        'de' => 'Bridge Table',
        'en' => 'Bridge Table',
      ],
      'aliases' => [
        'Bridge',
        'Many-to-Many Bridge',
        'Brückentabelle',
      ],
      'definition' => [
        'de' => 'Hilfstabelle für Many-to-Many zwischen Facts und Dimensions (oder Dimensionen untereinander), ohne Grain zu zerstören.',
        'en' => 'Helper table for many-to-many between facts and dimensions (or among dimensions) without breaking grain.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
      ],
    ],
    [
      'id' => 'dax',
      'order' => 540,
      'category' => 'bi',
      'term' => [
        'de' => 'DAX',
        'en' => 'DAX',
      ],
      'aliases' => [
        'Data Analysis Expressions',
      ],
      'definition' => [
        'de' => 'Formel- und Query-Sprache für Power BI / Analysis Services — Measures und berechnete Spalten im semantischen Modell.',
        'en' => 'Formula and query language for Power BI / Analysis Services — measures and calculated columns in the semantic model.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'filter-context',
          'label' => [
            'de' => 'Filter Context',
            'en' => 'Filter Context',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'master-measure',
          'label' => [
            'de' => 'Master Measure',
            'en' => 'Master Measure',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'bi-tools',
          'label' => [
            'de' => 'BI-Tools',
            'en' => 'BI tools',
          ],
        ],
      ],
    ],
    [
      'id' => 'set-analysis',
      'order' => 544,
      'category' => 'bi',
      'term' => [
        'de' => 'Set Analysis',
        'en' => 'Set Analysis',
      ],
      'aliases' => [
        'Qlik Set Analysis',
        'Set Expression',
      ],
      'definition' => [
        'de' => 'Qlik-Syntax, um Aggregationen unabhängig (oder gezielt abhängig) vom aktuellen Selektionszustand zu berechnen.',
        'en' => 'Qlik syntax to compute aggregations independently of — or deliberately against — the current selection state.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'filter-context',
          'label' => [
            'de' => 'Filter Context',
            'en' => 'Filter Context',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'bi-tools',
          'label' => [
            'de' => 'BI-Tools',
            'en' => 'BI tools',
          ],
        ],
      ],
    ],
    [
      'id' => 'lookml',
      'order' => 548,
      'category' => 'bi',
      'term' => [
        'de' => 'LookML',
        'en' => 'LookML',
      ],
      'aliases' => [
        'Looker Modeling Language',
      ],
      'definition' => [
        'de' => 'Lookers modellierende Sprache für Views, Explores und Measures — Business-Logik als Code statt in jedem Report.',
        'en' => 'Looker’s modeling language for views, explores, and measures — business logic as code instead of inside every report.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'master-measure',
          'label' => [
            'de' => 'Master Measure',
            'en' => 'Master Measure',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'bi-tools',
          'label' => [
            'de' => 'BI-Tools',
            'en' => 'BI tools',
          ],
        ],
      ],
    ],
    [
      'id' => 'lod-expression',
      'order' => 552,
      'category' => 'bi',
      'term' => [
        'de' => 'LOD Expression',
        'en' => 'LOD Expression',
      ],
      'aliases' => [
        'Level of Detail',
        'Tableau LOD',
        'FIXED/INCLUDE/EXCLUDE',
      ],
      'definition' => [
        'de' => 'Tableau-Ausdrücke, die die Aggregationsebene unabhängig von der aktuellen Viz-Grain steuern.',
        'en' => 'Tableau expressions that control aggregation level independently of the current viz grain.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'filter-context',
          'label' => [
            'de' => 'Filter Context',
            'en' => 'Filter Context',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'bi-tools',
          'label' => [
            'de' => 'BI-Tools',
            'en' => 'BI tools',
          ],
        ],
      ],
    ],
    [
      'id' => 'filter-context',
      'order' => 556,
      'category' => 'bi',
      'term' => [
        'de' => 'Filter Context',
        'en' => 'Filter Context',
      ],
      'aliases' => [
        'Calculation Context',
        'Evaluationskontext',
        'Filterkontext',
      ],
      'definition' => [
        'de' => 'Die Menge aktiver Filter/Selektionen, unter der eine Measure-Formel ausgewertet wird — Quelle vieler „falscher“ KPI-Zahlen.',
        'en' => 'The set of active filters/selections under which a measure formula evaluates — the source of many “wrong” KPI numbers.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dax',
          'label' => [
            'de' => 'DAX',
            'en' => 'DAX',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'set-analysis',
          'label' => [
            'de' => 'Set Analysis',
            'en' => 'Set Analysis',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'master-measure',
          'label' => [
            'de' => 'Master Measure',
            'en' => 'Master Measure',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'keeping-business-logic-outside-bi-apps',
          'label' => [
            'de' => 'Business-Logik außerhalb von BI-Apps halten',
            'en' => 'Keep business logic outside BI apps',
          ],
        ],
      ],
    ],
    [
      'id' => 'master-measure',
      'order' => 560,
      'category' => 'bi',
      'term' => [
        'de' => 'Master Measure',
        'en' => 'Master Measure',
      ],
      'aliases' => [
        'Master Item',
        'Shared Measure',
        'Zentrale Measure',
      ],
      'definition' => [
        'de' => 'Wiederverwendbare, versionierte Measure-Definition im semantischen Layer — nicht pro Report neu erfunden.',
        'en' => 'Reusable, versioned measure definition in the semantic layer — not reinvented per report.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted Metrics',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metric-store',
          'label' => [
            'de' => 'Metric / KPI Store',
            'en' => 'Metric / KPI Store',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'kpi-metric-governance',
          'label' => [
            'de' => 'KPI- & Metric-Governance',
            'en' => 'KPI & metric governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'calculation-group',
      'order' => 564,
      'category' => 'bi',
      'term' => [
        'de' => 'Calculation Group',
        'en' => 'Calculation Group',
      ],
      'aliases' => [
        'Calc Group',
        'Presentation Measure',
        'Time Intelligence Group',
      ],
      'definition' => [
        'de' => 'Tabular/Power-BI-Muster, um Time-Intelligence oder Präsentationsvarianten zentral statt als Measure-Explosion zu modellieren.',
        'en' => 'Tabular/Power BI pattern to model time intelligence or presentation variants centrally instead of measure explosion.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dax',
          'label' => [
            'de' => 'DAX',
            'en' => 'DAX',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => [
            'de' => 'Jenseits von Bronze-Silver-Gold',
            'en' => 'Beyond bronze-silver-gold',
          ],
        ],
      ],
    ],
    [
      'id' => 'self-service-bi',
      'order' => 568,
      'category' => 'bi',
      'term' => [
        'de' => 'Self-Service BI',
        'en' => 'Self-Service BI',
      ],
      'aliases' => [
        'Self Service Analytics',
        'Citizen BI',
      ],
      'definition' => [
        'de' => 'Fachbereiche erstellen Reports selbst — funktioniert nur mit governed Semantik und klaren Konsum-Verträgen, sonst Shadow-IT.',
        'en' => 'Business users build reports themselves — works only with governed semantics and clear consumption contracts, otherwise shadow IT.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'certified-dataset',
          'label' => [
            'de' => 'Certified Dataset',
            'en' => 'Certified Dataset',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'thin-consumer-interface',
          'label' => [
            'de' => 'Thin Consumer Interface',
            'en' => 'Thin Consumer Interface',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'bi-tools',
          'label' => [
            'de' => 'BI-Tools',
            'en' => 'BI tools',
          ],
        ],
      ],
    ],
    [
      'id' => 'embedded-analytics',
      'order' => 572,
      'category' => 'bi',
      'term' => [
        'de' => 'Embedded Analytics',
        'en' => 'Embedded Analytics',
      ],
      'aliases' => [
        'Embedded BI',
        'In-App Analytics',
      ],
      'definition' => [
        'de' => 'Analysen und Visuals eingebettet in operative Apps — dieselben Contracts und Rechte wie im Standalone-BI.',
        'en' => 'Analytics and visuals embedded in operational apps — same contracts and entitlements as standalone BI.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'consumption-contract',
          'label' => [
            'de' => 'Consumption Contract',
            'en' => 'Consumption Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'row-level-security',
          'label' => [
            'de' => 'Row-Level Security (RLS)',
            'en' => 'Row-Level Security (RLS)',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'bi-tools',
          'label' => [
            'de' => 'BI-Tools',
            'en' => 'BI tools',
          ],
        ],
      ],
    ],
    [
      'id' => 'metric-lineage',
      'order' => 576,
      'category' => 'bi',
      'term' => [
        'de' => 'Metric Lineage',
        'en' => 'Metric Lineage',
      ],
      'aliases' => [
        'KPI Lineage',
        'Measure Lineage',
        'Kennzahlen-Lineage',
      ],
      'definition' => [
        'de' => 'Nachvollziehbarkeit von Measure/KPI zurück zu Quellen, Transformationen und Owners — Pflicht für Trusted Metrics.',
        'en' => 'Traceability from measure/KPI back to sources, transformations, and owners — mandatory for trusted metrics.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted Metrics',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'column-lineage',
          'label' => [
            'de' => 'Column Lineage',
            'en' => 'Column Lineage',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'kpi-metric-governance',
          'label' => [
            'de' => 'KPI- & Metric-Governance',
            'en' => 'KPI & metric governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'certified-dataset',
      'order' => 580,
      'category' => 'bi',
      'term' => [
        'de' => 'Certified Dataset',
        'en' => 'Certified Dataset',
      ],
      'aliases' => [
        'Shared Dataset',
        'Promoted Dataset',
        'Zertifiziertes Dataset',
      ],
      'definition' => [
        'de' => 'Freigegebenes, geprüftes semantisches Dataset für Self-Service — Kennzeichnung ersetzt nicht Owner und Contract.',
        'en' => 'Approved, reviewed semantic dataset for self-service — a badge does not replace owner and contract.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'product-certification',
          'label' => [
            'de' => 'Data Product Certification',
            'en' => 'Data Product Certification',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'self-service-bi',
          'label' => [
            'de' => 'Self-Service BI',
            'en' => 'Self-Service BI',
          ],
        ],
      ],
    ],
    [
      'id' => 'directquery',
      'order' => 584,
      'category' => 'bi',
      'term' => [
        'de' => 'DirectQuery',
        'en' => 'DirectQuery',
      ],
      'aliases' => [
        'Live Connection',
        'Import Mode',
        'Composite Model',
      ],
      'definition' => [
        'de' => 'BI-Abfragemodus gegen die Quelle statt Import — Freshness vs. Performance und Governance-Trade-offs bewusst wählen.',
        'en' => 'BI query mode against the source instead of import — choose freshness vs. performance and governance trade-offs deliberately.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'freshness',
          'label' => [
            'de' => 'Freshness',
            'en' => 'Freshness',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'bi-tools',
          'label' => [
            'de' => 'BI-Tools',
            'en' => 'BI tools',
          ],
        ],
      ],
    ],
    [
      'id' => 'thin-consumer-interface',
      'order' => 588,
      'category' => 'bi',
      'term' => [
        'de' => 'Thin Consumer Interface',
        'en' => 'Thin Consumer Interface',
      ],
      'aliases' => [
        'Thin Report',
        'Thin Dashboard',
        'Dünne Konsumentenschicht',
      ],
      'definition' => [
        'de' => 'Reports enthalten kaum eigene Business-Logik — sie konsumieren Measures und Verträge aus dem semantischen Layer.',
        'en' => 'Reports hold little business logic of their own — they consume measures and contracts from the semantic layer.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'consumption-contract',
          'label' => [
            'de' => 'Consumption Contract',
            'en' => 'Consumption Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'master-measure',
          'label' => [
            'de' => 'Master Measure',
            'en' => 'Master Measure',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'keeping-business-logic-outside-bi-apps',
          'label' => [
            'de' => 'Business-Logik außerhalb von BI-Apps halten',
            'en' => 'Keep business logic outside BI apps',
          ],
        ],
      ],
    ],
    [
      'id' => 'quality-by-design',
      'order' => 740,
      'category' => 'quality',
      'term' => [
        'de' => 'Quality by Design',
        'en' => 'Quality by Design',
      ],
      'aliases' => [
        'QbD',
        'Built-in Quality',
        'Qualität by Design',
      ],
      'definition' => [
        'de' => 'Qualität in Contracts, Models und Pipelines einbauen — nicht erst am Dashboard per Stichprobe „finden“.',
        'en' => 'Bake quality into contracts, models, and pipelines — not “find” it later by sampling dashboards.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dq-gate',
          'label' => [
            'de' => 'DQ Gate',
            'en' => 'DQ Gate',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-quality-governance',
          'label' => [
            'de' => 'Data-Quality-Governance',
            'en' => 'Data quality governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'anomaly-detection',
      'order' => 750,
      'category' => 'quality',
      'term' => [
        'de' => 'Anomaly Detection',
        'en' => 'Anomaly Detection',
      ],
      'aliases' => [
        'Outlier Detection',
        'Anomalieerkennung',
      ],
      'definition' => [
        'de' => 'Automatisches Erkennen unerwarteter Muster in Volumen, Distributions oder Metric-Werten — Ergänzung zu regelbasierten Tests.',
        'en' => 'Automatic detection of unexpected patterns in volume, distributions, or metric values — complements rule-based tests.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-observability',
          'label' => [
            'de' => 'Data Observability',
            'en' => 'Data Observability',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'freshness',
          'label' => [
            'de' => 'Freshness',
            'en' => 'Freshness',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-quality-governance',
          'label' => [
            'de' => 'Data-Quality-Governance',
            'en' => 'Data quality governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-profiling',
      'order' => 760,
      'category' => 'quality',
      'term' => [
        'de' => 'Data Profiling',
        'en' => 'Data Profiling',
      ],
      'aliases' => [
        'Profiling',
        'Column Profiling',
        'Datenprofiling',
      ],
      'definition' => [
        'de' => 'Statistische Bestandsaufnahme von Spalten (Nulls, Kardinalität, Patterns) — Basis für Rules und Contracts.',
        'en' => 'Statistical inventory of columns (nulls, cardinality, patterns) — basis for rules and contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'schema-drift',
          'label' => [
            'de' => 'Schema Drift',
            'en' => 'Schema Drift',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'rule-registry',
          'label' => [
            'de' => 'Rule Registry',
            'en' => 'Rule Registry',
          ],
        ],
      ],
    ],
    [
      'id' => 'contract-as-code',
      'order' => 770,
      'category' => 'quality',
      'term' => [
        'de' => 'Contract as Code',
        'en' => 'Contract as Code',
      ],
      'aliases' => [
        'Contracts as Code',
        'Assertion as Code',
      ],
      'definition' => [
        'de' => 'Data Contracts und Assertions versioniert im Repo und in CI geprüft — nicht nur als Wiki-Absatz.',
        'en' => 'Data contracts and assertions versioned in the repo and checked in CI — not only as a wiki paragraph.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'policy-as-code',
          'label' => [
            'de' => 'Policy as Code',
            'en' => 'Policy as Code',
          ],
        ],
      ],
    ],
    [
      'id' => 'technical-metadata',
      'order' => 1230,
      'category' => 'metadata',
      'term' => [
        'de' => 'Technical Metadata',
        'en' => 'Technical Metadata',
      ],
      'aliases' => [
        'Tech Metadata',
        'Technische Metadaten',
        'Schema Metadata',
      ],
      'definition' => [
        'de' => 'Schema, Typen, Jobs, Speichersettings — was Systeme über Datenstrukturen und Pipelines wissen.',
        'en' => 'Schema, types, jobs, storage settings — what systems know about data structures and pipelines.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'metadata',
          'label' => [
            'de' => 'Metadata',
            'en' => 'Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'business-metadata',
          'label' => [
            'de' => 'Business Metadata',
            'en' => 'Business Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'what-metadata-actually-is',
          'label' => [
            'de' => 'Was Metadaten wirklich sind',
            'en' => 'What metadata actually is',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'where-metadata-is-born',
          'label' => [
            'de' => 'Wo Metadaten entstehen',
            'en' => 'Where metadata is born',
          ],
        ],
      ],
    ],
    [
      'id' => 'business-metadata',
      'order' => 1240,
      'category' => 'metadata',
      'term' => [
        'de' => 'Business Metadata',
        'en' => 'Business Metadata',
      ],
      'aliases' => [
        'Fachliche Metadaten',
        'Business Context',
      ],
      'definition' => [
        'de' => 'Fachliche Bedeutung, Owner, Glossary-Terms und Nutzungshinweise — macht technische Assets verständlich.',
        'en' => 'Business meaning, owners, glossary terms, and usage hints — makes technical assets understandable.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'business-glossary',
          'label' => [
            'de' => 'Business Glossary',
            'en' => 'Business Glossary',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'technical-metadata',
          'label' => [
            'de' => 'Technical Metadata',
            'en' => 'Technical Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'enrich-technical-metadata-with-business-context',
          'label' => [
            'de' => 'Technische Metadaten mit Business-Kontext anreichern',
            'en' => 'Enrich technical metadata with business context',
          ],
        ],
      ],
    ],
    [
      'id' => 'operational-metadata',
      'order' => 1250,
      'category' => 'metadata',
      'term' => [
        'de' => 'Operational Metadata',
        'en' => 'Operational Metadata',
      ],
      'aliases' => [
        'Ops Metadata',
        'Run Metadata',
        'Betriebliche Metadaten',
      ],
      'definition' => [
        'de' => 'Laufzeiten, Status, Incidents, SLAs — Metadaten aus dem Betrieb von Pipelines und Jobs.',
        'en' => 'Runtimes, status, incidents, SLAs — metadata from operating pipelines and jobs.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'metadata',
          'label' => [
            'de' => 'Metadata',
            'en' => 'Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'sla-slo',
          'label' => [
            'de' => 'SLA / SLO',
            'en' => 'SLA / SLO',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'where-metadata-is-born',
          'label' => [
            'de' => 'Wo Metadaten entstehen',
            'en' => 'Where metadata is born',
          ],
        ],
      ],
    ],
    [
      'id' => 'usage-metadata',
      'order' => 1260,
      'category' => 'metadata',
      'term' => [
        'de' => 'Usage Metadata',
        'en' => 'Usage Metadata',
      ],
      'aliases' => [
        'Access Metadata',
        'Nutzungsmetadaten',
        'Query Logs',
      ],
      'definition' => [
        'de' => 'Wer welche Assets wie oft nutzt — Grundlage für Priorisierung, Zertifizierung und Aufräumen.',
        'en' => 'Who uses which assets how often — basis for prioritization, certification, and cleanup.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'metadata',
          'label' => [
            'de' => 'Metadata',
            'en' => 'Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'product-certification',
          'label' => [
            'de' => 'Data Product Certification',
            'en' => 'Data Product Certification',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'what-metadata-actually-is',
          'label' => [
            'de' => 'Was Metadaten wirklich sind',
            'en' => 'What metadata actually is',
          ],
        ],
      ],
    ],
    [
      'id' => 'declared-metadata',
      'order' => 1270,
      'category' => 'metadata',
      'term' => [
        'de' => 'Declared Metadata',
        'en' => 'Declared Metadata',
      ],
      'aliases' => [
        'Declared',
        'Deklarierte Metadaten',
        'Documented Metadata',
      ],
      'definition' => [
        'de' => 'Explizit dokumentierte oder im Code deklarierte Metadaten (z. B. dbt meta, Glossary) — Absicht, nicht Beobachtung.',
        'en' => 'Explicitly documented or code-declared metadata (e.g. dbt meta, glossary) — intent, not observation.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'detected-metadata',
          'label' => [
            'de' => 'Detected Metadata',
            'en' => 'Detected Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dbt-meta',
          'label' => [
            'de' => 'dbt meta',
            'en' => 'dbt meta',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'what-metadata-actually-is',
          'label' => [
            'de' => 'Was Metadaten wirklich sind',
            'en' => 'What metadata actually is',
          ],
        ],
      ],
    ],
    [
      'id' => 'detected-metadata',
      'order' => 1280,
      'category' => 'metadata',
      'term' => [
        'de' => 'Detected Metadata',
        'en' => 'Detected Metadata',
      ],
      'aliases' => [
        'Inferred Metadata',
        'Observed Metadata',
        'Erkannte Metadaten',
      ],
      'definition' => [
        'de' => 'Aus Systemen geharvestete oder inferierte Metadaten — Schema, Lineage, Usage — ergänzt Declared Metadata.',
        'en' => 'Harvested or inferred metadata from systems — schema, lineage, usage — complements declared metadata.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'declared-metadata',
          'label' => [
            'de' => 'Declared Metadata',
            'en' => 'Declared Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metadata-harvesting',
          'label' => [
            'de' => 'Metadata Harvesting',
            'en' => 'Metadata Harvesting',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'harvest-metadata-automatically',
          'label' => [
            'de' => 'Metadaten automatisch harvesten',
            'en' => 'Harvest metadata automatically',
          ],
        ],
      ],
    ],
    [
      'id' => 'control-driving-metadata',
      'order' => 1290,
      'category' => 'metadata',
      'term' => [
        'de' => 'Control-Driving Metadata',
        'en' => 'Control-Driving Metadata',
      ],
      'aliases' => [
        'Control Metadata',
        'Governance Metadata',
        'Steuernde Metadaten',
      ],
      'definition' => [
        'de' => 'Metadaten, die Policies, Masking, Routing oder Gates aktiv steuern — nicht nur beschreiben.',
        'en' => 'Metadata that actively drives policies, masking, routing, or gates — not merely describes.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'active-metadata',
          'label' => [
            'de' => 'Active Metadata',
            'en' => 'Active Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'policy-as-code',
          'label' => [
            'de' => 'Policy as Code',
            'en' => 'Policy as Code',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'governance-metadata-that-controls-data',
          'label' => [
            'de' => 'Governance-Metadaten, die Daten steuern',
            'en' => 'Governance metadata that controls data',
          ],
        ],
      ],
    ],
    [
      'id' => 'openlineage',
      'order' => 1225,
      'category' => 'metadata',
      'term' => [
        'de' => 'OpenLineage',
        'en' => 'OpenLineage',
      ],
      'aliases' => [
        'Open Lineage',
        'Lineage Standard',
      ],
      'definition' => [
        'de' => 'Offener Standard für Job- und Dataset-Lineage-Events — interoperabel über Orchestratoren und Catalogs.',
        'en' => 'Open standard for job and dataset lineage events — interoperable across orchestrators and catalogs.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'column-lineage',
          'label' => [
            'de' => 'Column Lineage',
            'en' => 'Column Lineage',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'metadata-tools-and-product-categories',
          'label' => [
            'de' => 'Metadaten-Tools und Produktkategorien',
            'en' => 'Metadata tools and product categories',
          ],
        ],
      ],
    ],
    [
      'id' => 'llm',
      'order' => 1580,
      'category' => 'ai',
      'term' => [
        'de' => 'LLM',
        'en' => 'LLM',
      ],
      'aliases' => [
        'Large Language Model',
        'Großes Sprachmodell',
      ],
      'definition' => [
        'de' => 'Großes Sprachmodell für Text/Code — braucht Guardrails, Grounding und klare Rechte an Kontextquellen.',
        'en' => 'Large language model for text/code — needs guardrails, grounding, and clear rights on context sources.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation)',
            'en' => 'RAG (Retrieval-Augmented Generation)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'hallucination',
          'label' => [
            'de' => 'Hallucination',
            'en' => 'Hallucination',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-language-models',
          'label' => [
            'de' => 'Sprachmodelle',
            'en' => 'Language models',
          ],
        ],
      ],
    ],
    [
      'id' => 'embedding',
      'order' => 1590,
      'category' => 'ai',
      'term' => [
        'de' => 'Embedding',
        'en' => 'Embedding',
      ],
      'aliases' => [
        'Vector Embedding',
        'Text Embedding',
      ],
      'definition' => [
        'de' => 'Vektorrepräsentation von Text oder Features für Similarity-Suche — Basis für RAG und semantische Suche.',
        'en' => 'Vector representation of text or features for similarity search — foundation for RAG and semantic search.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'vector-store',
          'label' => [
            'de' => 'Vector Store',
            'en' => 'Vector Store',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation)',
            'en' => 'RAG (Retrieval-Augmented Generation)',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'prepare-metadata-for-ai-rag-and-model-training',
          'label' => [
            'de' => 'Metadaten für AI/RAG vorbereiten',
            'en' => 'Prepare metadata for AI/RAG',
          ],
        ],
      ],
    ],
    [
      'id' => 'vector-store',
      'order' => 1600,
      'category' => 'ai',
      'term' => [
        'de' => 'Vector Store',
        'en' => 'Vector Store',
      ],
      'aliases' => [
        'Vector Database',
        'Embedding Store',
        'Vektordatenbank',
      ],
      'definition' => [
        'de' => 'Speicher und Index für Embeddings mit Similarity-Retrieval — oft an RAG-Pipelines gekoppelt.',
        'en' => 'Storage and index for embeddings with similarity retrieval — often coupled to RAG pipelines.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'embedding',
          'label' => [
            'de' => 'Embedding',
            'en' => 'Embedding',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation)',
            'en' => 'RAG (Retrieval-Augmented Generation)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ai-ready-metadata',
          'label' => [
            'de' => 'AI-Ready Metadata',
            'en' => 'AI-Ready Metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'cdo',
      'order' => 42,
      'category' => 'roles',
      'term' => [
        'de' => 'CDO',
        'en' => 'CDO',
      ],
      'aliases' => [
        'Chief Data Officer',
        'Chief Data & Analytics Officer',
      ],
      'definition' => [
        'de' => 'Führungsrolle für Datenstrategie, Mandate und unternehmensweite Priorisierung — nicht das operative Stewardship selbst.',
        'en' => 'Leadership role for data strategy, mandate, and enterprise prioritization — not day-to-day stewardship itself.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'executive-sponsor',
          'label' => [
            'de' => 'Executive Sponsor',
            'en' => 'Executive Sponsor',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'governance-lead',
          'label' => [
            'de' => 'Governance Lead',
            'en' => 'Governance Lead',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance CoE',
            'en' => 'Governance CoE',
          ],
        ],
      ],
    ],
    [
      'id' => 'platform-ops',
      'order' => 44,
      'category' => 'roles',
      'term' => [
        'de' => 'Platform Ops',
        'en' => 'Platform Ops',
      ],
      'aliases' => [
        'Platform Operations',
        'Data Platform Ops',
        'Plattformbetrieb',
      ],
      'definition' => [
        'de' => 'Betrieb von Orchestrierung, Environments und Plattform-SLAs — hält die Pipeline-Maschine am Laufen.',
        'en' => 'Operates orchestration, environments, and platform SLAs — keeps the pipeline machinery running.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'technical-owner',
          'label' => [
            'de' => 'Technical Owner',
            'en' => 'Technical Owner',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'orchestration',
          'label' => [
            'de' => 'Orchestration',
            'en' => 'Orchestration',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operating-and-governing-the-platform',
          'label' => [
            'de' => 'Plattform betreiben und steuern',
            'en' => 'Operating and governing the platform',
          ],
        ],
      ],
    ],
    [
      'id' => 'citizen-developer',
      'order' => 46,
      'category' => 'roles',
      'term' => [
        'de' => 'Citizen Developer',
        'en' => 'Citizen Developer',
      ],
      'aliases' => [
        'Power User',
        'Citizen Analyst',
      ],
      'definition' => [
        'de' => 'Fachanwender, der Reports/Automationen selbst baut — braucht governed Semantik, sonst entsteht Shadow IT.',
        'en' => 'Business user who builds reports/automations themselves — needs governed semantics, or shadow IT follows.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'self-service-bi',
          'label' => [
            'de' => 'Self-Service BI',
            'en' => 'Self-Service BI',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'shadow-it',
          'label' => [
            'de' => 'Shadow IT',
            'en' => 'Shadow IT',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'certified-dataset',
          'label' => [
            'de' => 'Certified Dataset',
            'en' => 'Certified Dataset',
          ],
        ],
      ],
    ],
    [
      'id' => 'single-source-of-truth',
      'order' => 203,
      'category' => 'data',
      'term' => [
        'de' => 'Single Source of Truth',
        'en' => 'Single Source of Truth',
      ],
      'aliases' => [
        'SSOT',
        'Source of Truth',
        'Eine Wahrheit',
      ],
      'definition' => [
        'de' => 'Vereinbarter analytischer Bezugspunkt für eine Kennzahl oder Entität — nicht dasselbe wie das operative System of Record.',
        'en' => 'Agreed analytical reference for a metric or entity — not the same as the operational system of record.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'system-of-record',
          'label' => [
            'de' => 'System of Record',
            'en' => 'System of Record',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted Metrics',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'golden-record',
          'label' => [
            'de' => 'Golden Record',
            'en' => 'Golden Record',
          ],
        ],
      ],
    ],
    [
      'id' => 'master-data',
      'order' => 205,
      'category' => 'data',
      'term' => [
        'de' => 'Master Data',
        'en' => 'Master Data',
      ],
      'aliases' => [
        'Stammdaten',
        'Master Data Management',
        'MDM',
      ],
      'definition' => [
        'de' => 'Gemeinsame Kernentitäten (Kunde, Produkt, Ort) mit Ownership und Matching — Grundlage für Golden Records.',
        'en' => 'Shared core entities (customer, product, location) with ownership and matching — foundation for golden records.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'golden-record',
          'label' => [
            'de' => 'Golden Record',
            'en' => 'Golden Record',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'reference-data',
          'label' => [
            'de' => 'Reference Data',
            'en' => 'Reference Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-domain',
          'label' => [
            'de' => 'Data Domain',
            'en' => 'Data Domain',
          ],
        ],
      ],
    ],
    [
      'id' => 'reference-data',
      'order' => 207,
      'category' => 'data',
      'term' => [
        'de' => 'Reference Data',
        'en' => 'Reference Data',
      ],
      'aliases' => [
        'Ref Data',
        'Lookup Data',
        'Referenzdaten',
        'Code Lists',
      ],
      'definition' => [
        'de' => 'Kontrollierte Wertelisten und Codes (Land, Währung, Status) — klein, stabil, oft unterschätzt in der Governance.',
        'en' => 'Controlled value lists and codes (country, currency, status) — small, stable, often underestimated in governance.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'master-data',
          'label' => [
            'de' => 'Master Data',
            'en' => 'Master Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'conformed-dimension',
          'label' => [
            'de' => 'Conformed Dimension',
            'en' => 'Conformed Dimension',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'validity',
          'label' => [
            'de' => 'Validity',
            'en' => 'Validity',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-literacy',
      'order' => 209,
      'category' => 'data',
      'term' => [
        'de' => 'Data Literacy',
        'en' => 'Data Literacy',
      ],
      'aliases' => [
        'Datenkompetenz',
        'Analytics Literacy',
      ],
      'definition' => [
        'de' => 'Fähigkeit, Daten zu lesen, hinterfragen und sinnvoll zu nutzen — Voraussetzung für Self-Service ohne Chaos.',
        'en' => 'Ability to read, question, and use data meaningfully — prerequisite for self-service without chaos.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'self-service-bi',
          'label' => [
            'de' => 'Self-Service BI',
            'en' => 'Self-Service BI',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted Metrics',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'citizen-developer',
          'label' => [
            'de' => 'Citizen Developer',
            'en' => 'Citizen Developer',
          ],
        ],
      ],
    ],
    [
      'id' => 'deprecation',
      'order' => 211,
      'category' => 'data',
      'term' => [
        'de' => 'Deprecation',
        'en' => 'Deprecation',
      ],
      'aliases' => [
        'Retirement',
        'Sunset',
        'Abkündigung',
      ],
      'definition' => [
        'de' => 'Geplante Außerbetriebnahme eines Data Products oder einer Measure — inkl. Frist, Ersatz und Consumer-Kommunikation.',
        'en' => 'Planned retirement of a data product or measure — including deadline, replacement, and consumer communication.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'product-versioning',
          'label' => [
            'de' => 'Data Product Versioning',
            'en' => 'Data Product Versioning',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'breaking-change',
          'label' => [
            'de' => 'Breaking Change',
            'en' => 'Breaking Change',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-lifecycle',
          'label' => [
            'de' => 'Data Lifecycle',
            'en' => 'Data Lifecycle',
          ],
        ],
      ],
    ],
    [
      'id' => 'etl',
      'order' => 385,
      'category' => 'architecture',
      'term' => [
        'de' => 'ETL',
        'en' => 'ETL',
      ],
      'aliases' => [
        'Extract Transform Load',
        'Extract-Transform-Load',
      ],
      'definition' => [
        'de' => 'Transformieren vor dem Laden — klassisches Muster; heute oft durch ELT im Warehouse ersetzt oder hybrid genutzt.',
        'en' => 'Transform before load — classic pattern; often replaced or hybridized by ELT in the warehouse today.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'elt',
          'label' => [
            'de' => 'ELT',
            'en' => 'ELT',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'orchestration',
          'label' => [
            'de' => 'Orchestration',
            'en' => 'Orchestration',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'transformation-options',
          'label' => [
            'de' => 'Transformationsoptionen',
            'en' => 'Transformation options',
          ],
        ],
      ],
    ],
    [
      'id' => 'streaming',
      'order' => 397,
      'category' => 'architecture',
      'term' => [
        'de' => 'Streaming',
        'en' => 'Streaming',
      ],
      'aliases' => [
        'Event Streaming',
        'Stream Processing',
        'Echtzeit-Stream',
      ],
      'definition' => [
        'de' => 'Kontinuierliche Event-/Record-Verarbeitung — nur wo Latenz und Kosten die Komplexität rechtfertigen.',
        'en' => 'Continuous event/record processing — only where latency and cost justify the complexity.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'near-real-time',
          'label' => [
            'de' => 'Near Real-Time',
            'en' => 'Near Real-Time',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'batch-processing',
          'label' => [
            'de' => 'Batch Processing',
            'en' => 'Batch Processing',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'choosing-the-simplest-viable-architecture',
          'label' => [
            'de' => 'Einfachst sinnvolle Architektur',
            'en' => 'Simplest viable architecture',
          ],
        ],
      ],
    ],
    [
      'id' => 'ods',
      'order' => 391,
      'category' => 'architecture',
      'term' => [
        'de' => 'ODS',
        'en' => 'ODS',
      ],
      'aliases' => [
        'Operational Data Store',
        'Operativer Datenspeicher',
      ],
      'definition' => [
        'de' => 'Integrierte operative Zwischenlage nahe an Quellsystemen — oft Vorstufe zum Warehouse, nicht Ersatz für Marts.',
        'en' => 'Integrated operational staging close to source systems — often a precursor to the warehouse, not a mart replacement.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'modern-data-warehouse',
          'label' => [
            'de' => 'Modern Data Warehouse',
            'en' => 'Modern Data Warehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'system-of-record',
          'label' => [
            'de' => 'System of Record',
            'en' => 'System of Record',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-vault',
      'order' => 393,
      'category' => 'architecture',
      'term' => [
        'de' => 'Data Vault',
        'en' => 'Data Vault',
      ],
      'aliases' => [
        'Data Vault 2.0',
        'Hub-Link-Satellite',
      ],
      'definition' => [
        'de' => 'Modellierungsmuster mit Hubs, Links und Satellites für auditierbare Historisierung — Alternative/Ergänzung zu dimensionalem Core.',
        'en' => 'Modeling pattern with hubs, links, and satellites for auditable historization — alternative/complement to a dimensional core.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'as-was-history',
          'label' => [
            'de' => 'As-Was History',
            'en' => 'As-Was History',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-fabric',
      'order' => 395,
      'category' => 'architecture',
      'term' => [
        'de' => 'Data Fabric',
        'en' => 'Data Fabric',
      ],
      'aliases' => [
        'Enterprise Data Fabric',
      ],
      'definition' => [
        'de' => 'Architekturansatz mit Metadaten, Virtualisierung und Integration über verteilte Quellen — oft Marketing-Überbegriff neben Mesh/Lakehouse.',
        'en' => 'Architecture approach with metadata, virtualization, and integration across distributed sources — often a marketing umbrella beside mesh/lakehouse.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-mesh',
          'label' => [
            'de' => 'Data Mesh',
            'en' => 'Data Mesh',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'active-metadata',
          'label' => [
            'de' => 'Active Metadata',
            'en' => 'Active Metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'iceberg',
      'order' => 389,
      'category' => 'architecture',
      'term' => [
        'de' => 'Apache Iceberg',
        'en' => 'Apache Iceberg',
      ],
      'aliases' => [
        'Iceberg',
        'Iceberg Table Format',
      ],
      'definition' => [
        'de' => 'Offenes Table Format für Lakehouses — ACID, Schema-Evolution und Time Travel auf Object Storage.',
        'en' => 'Open table format for lakehouses — ACID, schema evolution, and time travel on object storage.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'parquet',
          'label' => [
            'de' => 'Parquet',
            'en' => 'Parquet',
          ],
        ],
      ],
    ],
    [
      'id' => 'parquet',
      'order' => 388,
      'category' => 'architecture',
      'term' => [
        'de' => 'Parquet',
        'en' => 'Parquet',
      ],
      'aliases' => [
        'Apache Parquet',
        'Columnar File Format',
      ],
      'definition' => [
        'de' => 'Spaltenorientiertes Dateiformat für analytische Workloads — Standard in Lakes und vielen Warehouse-Exports.',
        'en' => 'Columnar file format for analytical workloads — standard in lakes and many warehouse exports.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-lake',
          'label' => [
            'de' => 'Data Lake',
            'en' => 'Data Lake',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
      ],
    ],
    [
      'id' => 'cutover',
      'order' => 387,
      'category' => 'architecture',
      'term' => [
        'de' => 'Cutover',
        'en' => 'Cutover',
      ],
      'aliases' => [
        'Go-Live Switch',
        'Umschaltung',
        'Parallel Run End',
      ],
      'definition' => [
        'de' => 'Moment, an dem Consumer vom Alt- auf den Neu-Stack wechseln — braucht Parallel Run, Rollback und klare Contracts.',
        'en' => 'Moment consumers switch from old to new stack — needs parallel run, rollback, and clear contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'strangler-pattern',
          'label' => [
            'de' => 'Strangler Pattern',
            'en' => 'Strangler Pattern',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'brownfield',
          'label' => [
            'de' => 'Brownfield',
            'en' => 'Brownfield',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'modernizing-an-existing-warehouse',
          'label' => [
            'de' => 'Bestehendes Warehouse modernisieren',
            'en' => 'Modernizing an existing warehouse',
          ],
        ],
      ],
    ],
    [
      'id' => 'snowflake-schema',
      'order' => 536,
      'category' => 'modeling',
      'term' => [
        'de' => 'Snowflake Schema',
        'en' => 'Snowflake Schema',
      ],
      'aliases' => [
        'Snowflaking',
        'Normalisierte Dimensionen',
      ],
      'definition' => [
        'de' => 'Dimensionale Variante mit normalisierten Dimensionstabellen — spart Redundanz, kostet oft Join-Komplexität vs. Star.',
        'en' => 'Dimensional variant with normalized dimension tables — saves redundancy, often costs join complexity vs. star.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
      ],
    ],
    [
      'id' => 'junk-dimension',
      'order' => 538,
      'category' => 'modeling',
      'term' => [
        'de' => 'Junk Dimension',
        'en' => 'Junk Dimension',
      ],
      'aliases' => [
        'Junk Dim',
        'Flags Dimension',
      ],
      'definition' => [
        'de' => 'Bündelt niedrigkardinale Flags/Codes in einer Dimension — hält die Fact-Tabelle schlank.',
        'en' => 'Bundles low-cardinality flags/codes into one dimension — keeps the fact table lean.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
      ],
    ],
    [
      'id' => 'degenerate-dimension',
      'order' => 539,
      'category' => 'modeling',
      'term' => [
        'de' => 'Degenerate Dimension',
        'en' => 'Degenerate Dimension',
      ],
      'aliases' => [
        'Degenerate Dim',
        'Transaction Number Dimension',
      ],
      'definition' => [
        'de' => 'Dimensionsattribut, das nur in der Fact-Tabelle lebt (z. B. Belegnummer) — ohne eigene Dimensionstabelle.',
        'en' => 'Dimensional attribute that lives only on the fact table (e.g. invoice number) — without its own dimension table.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
      ],
    ],
    [
      'id' => 'headless-bi',
      'order' => 592,
      'category' => 'bi',
      'term' => [
        'de' => 'Headless BI',
        'en' => 'Headless BI',
      ],
      'aliases' => [
        'Headless Metrics',
        'API-first BI',
      ],
      'definition' => [
        'de' => 'Semantik und Metrics als API/Service — Visualisierung ist austauschbar, die Definition bleibt zentral.',
        'en' => 'Semantics and metrics as an API/service — visualization is swappable, definitions stay central.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metric-store',
          'label' => [
            'de' => 'Metric / KPI Store',
            'en' => 'Metric / KPI Store',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'thin-consumer-interface',
          'label' => [
            'de' => 'Thin Consumer Interface',
            'en' => 'Thin Consumer Interface',
          ],
        ],
      ],
    ],
    [
      'id' => 'olap',
      'order' => 594,
      'category' => 'bi',
      'term' => [
        'de' => 'OLAP',
        'en' => 'OLAP',
      ],
      'aliases' => [
        'Online Analytical Processing',
        'Cube',
        'Multidimensional',
      ],
      'definition' => [
        'de' => 'Analytische Mehrdimensionalität (Slice/Dice, Hierarchien) — heute oft als Tabular/Columnar-Engine statt klassischer Cubes.',
        'en' => 'Analytical multidimensionality (slice/dice, hierarchies) — today often as tabular/columnar engines instead of classic cubes.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'tabular-model',
          'label' => [
            'de' => 'Tabular Model',
            'en' => 'Tabular Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
      ],
    ],
    [
      'id' => 'tabular-model',
      'order' => 596,
      'category' => 'bi',
      'term' => [
        'de' => 'Tabular Model',
        'en' => 'Tabular Model',
      ],
      'aliases' => [
        'Analysis Services Tabular',
        'Tabular Semantic Model',
      ],
      'definition' => [
        'de' => 'Spaltenbasiertes semantisches Modell (z. B. Power BI / AAS) mit Relationships, Measures und oft Import/DirectQuery.',
        'en' => 'Columnar semantic model (e.g. Power BI / AAS) with relationships, measures, and often import/DirectQuery.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dax',
          'label' => [
            'de' => 'DAX',
            'en' => 'DAX',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'calculation-group',
          'label' => [
            'de' => 'Calculation Group',
            'en' => 'Calculation Group',
          ],
        ],
      ],
    ],
    [
      'id' => 'shadow-it',
      'order' => 598,
      'category' => 'bi',
      'term' => [
        'de' => 'Shadow IT',
        'en' => 'Shadow IT',
      ],
      'aliases' => [
        'Shadow Analytics',
        'Schatten-IT',
        'Spreadsheet Hell',
      ],
      'definition' => [
        'de' => 'Ungoverned Reports, Exports und Pipelines außerhalb der Plattform — Symptom fehlender Semantik und Delivery.',
        'en' => 'Ungoverned reports, exports, and pipelines outside the platform — symptom of missing semantics and delivery.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'self-service-bi',
          'label' => [
            'de' => 'Self-Service BI',
            'en' => 'Self-Service BI',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'certified-dataset',
          'label' => [
            'de' => 'Certified Dataset',
            'en' => 'Certified Dataset',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'citizen-developer',
          'label' => [
            'de' => 'Citizen Developer',
            'en' => 'Citizen Developer',
          ],
        ],
      ],
    ],
    [
      'id' => 'timeliness',
      'order' => 780,
      'category' => 'quality',
      'term' => [
        'de' => 'Timeliness',
        'en' => 'Timeliness',
      ],
      'aliases' => [
        'Aktualität',
        'Latency Fit',
      ],
      'definition' => [
        'de' => 'DQ-Dimension: Daten sind rechtzeitig für die Entscheidung da — eng verwandt mit Freshness, aber am Use Case gemessen.',
        'en' => 'DQ dimension: data arrives in time for the decision — related to freshness, but judged against the use case.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'freshness',
          'label' => [
            'de' => 'Freshness',
            'en' => 'Freshness',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'fitness-for-purpose',
          'label' => [
            'de' => 'Fitness for Purpose',
            'en' => 'Fitness for Purpose',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'sla-slo',
          'label' => [
            'de' => 'SLA / SLO',
            'en' => 'SLA / SLO',
          ],
        ],
      ],
    ],
    [
      'id' => 'dataops',
      'order' => 790,
      'category' => 'quality',
      'term' => [
        'de' => 'DataOps',
        'en' => 'DataOps',
      ],
      'aliases' => [
        'Data Operations',
        'Analytics Ops',
      ],
      'definition' => [
        'de' => 'Praktiken aus DevOps für Datenprodukte: CI/CD, Observability, kurze Feedback-Schleifen zwischen Prod und Consume.',
        'en' => 'DevOps practices for data products: CI/CD, observability, short feedback loops between produce and consume.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-observability',
          'label' => [
            'de' => 'Data Observability',
            'en' => 'Data Observability',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'orchestration',
          'label' => [
            'de' => 'Orchestration',
            'en' => 'Orchestration',
          ],
        ],
      ],
    ],
    [
      'id' => 'incident-runbook',
      'order' => 785,
      'category' => 'quality',
      'term' => [
        'de' => 'Incident Runbook',
        'en' => 'Incident Runbook',
      ],
      'aliases' => [
        'Runbook',
        'Playbook Incident',
        'Störungs-Runbook',
      ],
      'definition' => [
        'de' => 'Schritt-für-Schritt-Anleitung bei Pipeline-/DQ-Incidents — Owner, Checks, Eskalation, Kommunikation.',
        'en' => 'Step-by-step guide for pipeline/DQ incidents — owners, checks, escalation, communication.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'root-cause-analysis',
          'label' => [
            'de' => 'Root Cause Analysis (DQ)',
            'en' => 'Root Cause Analysis (DQ)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'remediation',
          'label' => [
            'de' => 'Remediation',
            'en' => 'Remediation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'escalation-path',
          'label' => [
            'de' => 'Escalation Path',
            'en' => 'Escalation Path',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operating-and-governing-the-platform',
          'label' => [
            'de' => 'Plattform betreiben und steuern',
            'en' => 'Operating and governing the platform',
          ],
        ],
      ],
    ],
    [
      'id' => 'gdpr',
      'order' => 912,
      'category' => 'privacy',
      'term' => [
        'de' => 'GDPR / DSGVO',
        'en' => 'GDPR',
      ],
      'aliases' => [
        'DSGVO',
        'General Data Protection Regulation',
        'EU GDPR',
      ],
      'definition' => [
        'de' => 'EU-Datenschutzrahmen mit Zweckbindung, Betroffenenrechten und Nachweispflichten — treibt Classification, Retention und DSDR.',
        'en' => 'EU privacy framework with purpose limitation, data-subject rights, and accountability — drives classification, retention, and DSDR.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dsdr',
          'label' => [
            'de' => 'DSDR',
            'en' => 'DSDR',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'purpose-limitation',
          'label' => [
            'de' => 'Purpose Limitation',
            'en' => 'Purpose Limitation',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'pii-privacy-governance',
          'label' => [
            'de' => 'PII- & Privacy-Governance',
            'en' => 'PII & privacy governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'legal-hold',
      'order' => 914,
      'category' => 'privacy',
      'term' => [
        'de' => 'Legal Hold',
        'en' => 'Legal Hold',
      ],
      'aliases' => [
        'Litigation Hold',
        'Aufbewahrungssperre',
      ],
      'definition' => [
        'de' => 'Sperre gegen Löschung/Änderung wegen Rechtsstreit oder Investigation — überschreibt normale Retention.',
        'en' => 'Freeze against deletion/change due to litigation or investigation — overrides normal retention.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'retention',
          'label' => [
            'de' => 'Retention',
            'en' => 'Retention',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-lifecycle',
          'label' => [
            'de' => 'Data Lifecycle',
            'en' => 'Data Lifecycle',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-lifecycle-retention',
          'label' => [
            'de' => 'Datenlebenszyklus & Retention',
            'en' => 'Data lifecycle & retention',
          ],
        ],
      ],
    ],
    [
      'id' => 'dynamic-masking',
      'order' => 916,
      'category' => 'privacy',
      'term' => [
        'de' => 'Dynamic Masking',
        'en' => 'Dynamic Masking',
      ],
      'aliases' => [
        'Dynamic Data Masking',
        'DDM',
        'Dynamische Maskierung',
      ],
      'definition' => [
        'de' => 'Maskierung zur Query-Zeit je nach Rolle — Rohdaten bleiben gespeichert, Sichtbarkeit wird gesteuert.',
        'en' => 'Masking at query time by role — raw data stays stored, visibility is controlled.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'masking',
          'label' => [
            'de' => 'Masking',
            'en' => 'Masking',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'row-level-security',
          'label' => [
            'de' => 'Row-Level Security (RLS)',
            'en' => 'Row-Level Security (RLS)',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'snowflake-masking-policies-qlik-section-access',
          'label' => [
            'de' => 'Snowflake Masking & Qlik Section Access',
            'en' => 'Snowflake masking & Qlik Section Access',
          ],
        ],
      ],
    ],
    [
      'id' => 'column-level-security',
      'order' => 1072,
      'category' => 'security',
      'term' => [
        'de' => 'Column-Level Security',
        'en' => 'Column-Level Security',
      ],
      'aliases' => [
        'CLS',
        'Column Security',
        'Spaltenrechte',
      ],
      'definition' => [
        'de' => 'Zugriffskontrolle auf Spaltenebene — ergänzt RLS, wenn ganze Attribute für Rollen unsichtbar sein müssen.',
        'en' => 'Access control at column grain — complements RLS when entire attributes must be invisible to roles.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'row-level-security',
          'label' => [
            'de' => 'Row-Level Security (RLS)',
            'en' => 'Row-Level Security (RLS)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'masking',
          'label' => [
            'de' => 'Masking',
            'en' => 'Masking',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => [
            'de' => 'Zugriffs- & Security-Governance',
            'en' => 'Access & security governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'encryption',
      'order' => 1074,
      'category' => 'security',
      'term' => [
        'de' => 'Encryption',
        'en' => 'Encryption',
      ],
      'aliases' => [
        'At-Rest Encryption',
        'In-Transit Encryption',
        'Verschlüsselung',
      ],
      'definition' => [
        'de' => 'Schutz von Daten in Transit und at Rest — Basis-Hygiene, ersetzt weder Masking noch RBAC.',
        'en' => 'Protection of data in transit and at rest — baseline hygiene; replaces neither masking nor RBAC.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'masking',
          'label' => [
            'de' => 'Masking',
            'en' => 'Masking',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'tokenization',
          'label' => [
            'de' => 'Tokenization',
            'en' => 'Tokenization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'iam',
          'label' => [
            'de' => 'IAM',
            'en' => 'IAM',
          ],
        ],
      ],
    ],
    [
      'id' => 'entitlement',
      'order' => 1076,
      'category' => 'security',
      'term' => [
        'de' => 'Entitlement',
        'en' => 'Entitlement',
      ],
      'aliases' => [
        'Access Entitlement',
        'Berechtigung',
        'Entitlements',
      ],
      'definition' => [
        'de' => 'Konkretes Zugriffsrecht einer Identität auf ein Asset — Gegenstand von Recertification und Least Privilege.',
        'en' => 'Concrete access right of an identity to an asset — subject of recertification and least privilege.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'access-recertification',
          'label' => [
            'de' => 'Access Recertification',
            'en' => 'Access Recertification',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'iam',
          'label' => [
            'de' => 'IAM',
            'en' => 'IAM',
          ],
        ],
      ],
    ],
    [
      'id' => 'metadata-graph',
      'order' => 1302,
      'category' => 'metadata',
      'term' => [
        'de' => 'Metadata Graph',
        'en' => 'Metadata Graph',
      ],
      'aliases' => [
        'Knowledge Graph Metadata',
        'Metadaten-Graph',
      ],
      'definition' => [
        'de' => 'Vernetzte Darstellung von Assets, Owners, Lineage und Policies — Grundlage für Impact und Active Metadata.',
        'en' => 'Connected view of assets, owners, lineage, and policies — foundation for impact analysis and active metadata.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'active-metadata',
          'label' => [
            'de' => 'Active Metadata',
            'en' => 'Active Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'where-metadata-is-born',
          'label' => [
            'de' => 'Wo Metadaten entstehen',
            'en' => 'Where metadata is born',
          ],
        ],
      ],
    ],
    [
      'id' => 'business-lineage',
      'order' => 1304,
      'category' => 'metadata',
      'term' => [
        'de' => 'Business Lineage',
        'en' => 'Business Lineage',
      ],
      'aliases' => [
        'Fachliche Lineage',
        'Business Data Lineage',
      ],
      'definition' => [
        'de' => 'Lineage in Fachbegriffen (KPI ← Mart ← Domain) — verständlich für Owner und Steward, nicht nur für Engineers.',
        'en' => 'Lineage in business terms (KPI ← mart ← domain) — understandable for owners and stewards, not only engineers.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'technical-lineage',
          'label' => [
            'de' => 'Technical Lineage',
            'en' => 'Technical Lineage',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metric-lineage',
          'label' => [
            'de' => 'Metric Lineage',
            'en' => 'Metric Lineage',
          ],
        ],
      ],
    ],
    [
      'id' => 'technical-lineage',
      'order' => 1306,
      'category' => 'metadata',
      'term' => [
        'de' => 'Technical Lineage',
        'en' => 'Technical Lineage',
      ],
      'aliases' => [
        'Tech Lineage',
        'Job Lineage',
        'Technische Lineage',
      ],
      'definition' => [
        'de' => 'Lineage auf Tabellen-/Spalten-/Job-Ebene aus Harvesting und Runtime — präzise für Impact und Debugging.',
        'en' => 'Lineage at table/column/job grain from harvesting and runtime — precise for impact and debugging.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'column-lineage',
          'label' => [
            'de' => 'Column Lineage',
            'en' => 'Column Lineage',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'business-lineage',
          'label' => [
            'de' => 'Business Lineage',
            'en' => 'Business Lineage',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'openlineage',
          'label' => [
            'de' => 'OpenLineage',
            'en' => 'OpenLineage',
          ],
        ],
      ],
    ],
    [
      'id' => 'enterprise-vocabulary',
      'order' => 1308,
      'category' => 'metadata',
      'term' => [
        'de' => 'Enterprise Vocabulary',
        'en' => 'Enterprise Vocabulary',
      ],
      'aliases' => [
        'Business Vocabulary',
        'Unternehmensvokabular',
        'Canonical Terms',
      ],
      'definition' => [
        'de' => 'Gemeinsame Fachsprache über Domains hinweg — Mapping von lokalen Labels auf kanonische Glossary-Terms.',
        'en' => 'Shared business language across domains — mapping local labels to canonical glossary terms.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'business-glossary',
          'label' => [
            'de' => 'Business Glossary',
            'en' => 'Business Glossary',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'business-metadata',
          'label' => [
            'de' => 'Business Metadata',
            'en' => 'Business Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'enrich-technical-metadata-with-business-context',
          'label' => [
            'de' => 'Technische Metadaten mit Business-Kontext anreichern',
            'en' => 'Enrich technical metadata with business context',
          ],
        ],
      ],
    ],
    [
      'id' => 'metadata-product',
      'order' => 1311,
      'category' => 'metadata',
      'term' => [
        'de' => 'Metadata Product',
        'en' => 'Metadata Product',
      ],
      'aliases' => [
        'Metadata as a Product',
        'Metadatenprodukt',
      ],
      'definition' => [
        'de' => 'Metadaten mit Product Thinking: Owner, SLA, Versionierung und Consumer — nicht nur ein Katalog-Dump.',
        'en' => 'Metadata with product thinking: owner, SLA, versioning, and consumers — not just a catalog dump.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operate-metadata-as-a-product',
          'label' => [
            'de' => 'Metadaten als Produkt betreiben',
            'en' => 'Operate metadata as a product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'active-metadata',
          'label' => [
            'de' => 'Active Metadata',
            'en' => 'Active Metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'schema-registry',
      'order' => 1312,
      'category' => 'metadata',
      'term' => [
        'de' => 'Schema Registry',
        'en' => 'Schema Registry',
      ],
      'aliases' => [
        'Avro Schema Registry',
        'Event Schema Registry',
      ],
      'definition' => [
        'de' => 'Zentrale Verwaltung und Evolution von Event-/Message-Schemas — Compatibility-Checks vor Breaking Changes.',
        'en' => 'Central management and evolution of event/message schemas — compatibility checks before breaking changes.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'schema-drift',
          'label' => [
            'de' => 'Schema Drift',
            'en' => 'Schema Drift',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'metadata-tools-and-product-categories',
          'label' => [
            'de' => 'Metadaten-Tools und Produktkategorien',
            'en' => 'Metadata tools and product categories',
          ],
        ],
      ],
    ],
    [
      'id' => 'event-driven-metadata',
      'order' => 1314,
      'category' => 'metadata',
      'term' => [
        'de' => 'Event-Driven Metadata',
        'en' => 'Event-Driven Metadata',
      ],
      'aliases' => [
        'Metadata Events',
        'Push Metadata Updates',
      ],
      'definition' => [
        'de' => 'Metadaten-Updates als Events aus Jobs/Catalogs — statt nur nächtlichem Full Harvest.',
        'en' => 'Metadata updates as events from jobs/catalogs — instead of only nightly full harvests.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'metadata-harvesting',
          'label' => [
            'de' => 'Metadata Harvesting',
            'en' => 'Metadata Harvesting',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'active-metadata',
          'label' => [
            'de' => 'Active Metadata',
            'en' => 'Active Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'where-metadata-is-born',
          'label' => [
            'de' => 'Wo Metadaten entstehen',
            'en' => 'Where metadata is born',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-technical-debt',
      'order' => 1412,
      'category' => 'process',
      'term' => [
        'de' => 'Data Technical Debt',
        'en' => 'Data Technical Debt',
      ],
      'aliases' => [
        'Data Debt',
        'Technical Debt',
        'Datenschulden',
      ],
      'definition' => [
        'de' => 'Aufgelaufene Kompromisse in Pipelines, Models und Reports — Zinsen als Incidents, Shadow IT und langsame Changes.',
        'en' => 'Accumulated compromises in pipelines, models, and reports — interest paid as incidents, shadow IT, and slow change.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'shadow-it',
          'label' => [
            'de' => 'Shadow IT',
            'en' => 'Shadow IT',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'breaking-change',
          'label' => [
            'de' => 'Breaking Change',
            'en' => 'Breaking Change',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'simplest-viable-architecture',
          'label' => [
            'de' => 'Simplest Viable Architecture',
            'en' => 'Simplest Viable Architecture',
          ],
        ],
      ],
    ],
    [
      'id' => 'dbt-macro',
      'order' => 381,
      'category' => 'architecture',
      'term' => [
        'de' => 'dbt Macro',
        'en' => 'dbt Macro',
      ],
      'aliases' => [
        'Macro',
        'Jinja Macro',
      ],
      'definition' => [
        'de' => 'Wiederverwendbare Jinja-Logik in dbt — z. B. für RAW-Generierung, Tests und Naming-Konventionen.',
        'en' => 'Reusable Jinja logic in dbt — e.g. for RAW generation, tests, and naming conventions.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'automatic-raw-generation-using-dbt-macros',
          'label' => [
            'de' => 'Automatische RAW-Generierung mit dbt Macros',
            'en' => 'Automatic RAW generation with dbt macros',
          ],
        ],
      ],
    ],
    [
      'id' => 'materialization',
      'order' => 382,
      'category' => 'architecture',
      'term' => [
        'de' => 'Materialization',
        'en' => 'Materialization',
      ],
      'aliases' => [
        'dbt Materialization',
        'Table/View/Incremental',
        'Materialisierung',
      ],
      'definition' => [
        'de' => 'Wie dbt ein Model baut (view, table, incremental, ephemeral) — steuert Kosten, Latenz und Downstream-Contracts.',
        'en' => 'How dbt builds a model (view, table, incremental, ephemeral) — drives cost, latency, and downstream contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'incremental-model',
          'label' => [
            'de' => 'Incremental Model',
            'en' => 'Incremental Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ephemeral-model',
          'label' => [
            'de' => 'Ephemeral Model',
            'en' => 'Ephemeral Model',
          ],
        ],
      ],
    ],
    [
      'id' => 'incremental-model',
      'order' => 383,
      'category' => 'architecture',
      'term' => [
        'de' => 'Incremental Model',
        'en' => 'Incremental Model',
      ],
      'aliases' => [
        'dbt Incremental',
        'Incremental Materialization',
      ],
      'definition' => [
        'de' => 'dbt-Materialisierung, die nur neue/geänderte Rows verarbeitet — braucht klare Unique Keys und Late-Arrival-Strategie.',
        'en' => 'dbt materialization that processes only new/changed rows — needs clear unique keys and a late-arrival strategy.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'materialization',
          'label' => [
            'de' => 'Materialization',
            'en' => 'Materialization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'delta-load',
          'label' => [
            'de' => 'Delta / Incremental Load',
            'en' => 'Delta / Incremental Load',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cdc',
          'label' => [
            'de' => 'CDC (Change Data Capture)',
            'en' => 'CDC (Change Data Capture)',
          ],
        ],
      ],
    ],
    [
      'id' => 'ephemeral-model',
      'order' => 384,
      'category' => 'architecture',
      'term' => [
        'de' => 'Ephemeral Model',
        'en' => 'Ephemeral Model',
      ],
      'aliases' => [
        'dbt Ephemeral',
        'CTE Model',
      ],
      'definition' => [
        'de' => 'dbt-Model ohne persistiertes Objekt — wird als CTE in Downstream-Models inlined.',
        'en' => 'dbt model without a persisted object — inlined as a CTE into downstream models.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'materialization',
          'label' => [
            'de' => 'Materialization',
            'en' => 'Materialization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
      ],
    ],
    [
      'id' => 'transformation-as-code',
      'order' => 386,
      'category' => 'architecture',
      'term' => [
        'de' => 'Transformation as Code',
        'en' => 'Transformation as Code',
      ],
      'aliases' => [
        'Analytics as Code',
        'SQL in Git',
      ],
      'definition' => [
        'de' => 'Transformationen versioniert im Repo mit Review und CI — statt Klick-ETL und undokumentierter Jobs.',
        'en' => 'Transformations versioned in the repo with review and CI — instead of click-ETL and undocumented jobs.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'analytics-engineer',
          'label' => [
            'de' => 'Analytics Engineer',
            'en' => 'Analytics Engineer',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'dbt-role',
          'label' => [
            'de' => 'Die dbt-Rolle',
            'en' => 'The dbt role',
          ],
        ],
      ],
    ],
    [
      'id' => 'ai-agent',
      'order' => 1610,
      'category' => 'ai',
      'term' => [
        'de' => 'AI Agent',
        'en' => 'AI Agent',
      ],
      'aliases' => [
        'Agent',
        'Autonomous Agent',
        'KI-Agent',
      ],
      'definition' => [
        'de' => 'LLM-gesteuerte Einheit, die Tools/Schritte plant und ausführt — braucht Guardrails und Human-in-the-Loop.',
        'en' => 'LLM-driven unit that plans and executes tools/steps — needs guardrails and human-in-the-loop.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'llm',
          'label' => [
            'de' => 'LLM',
            'en' => 'LLM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'human-in-the-loop',
          'label' => [
            'de' => 'Human-in-the-Loop',
            'en' => 'Human-in-the-Loop',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-agents',
          'label' => [
            'de' => 'KI-Agenten',
            'en' => 'AI agents',
          ],
        ],
      ],
    ],
    [
      'id' => 'fine-tuning',
      'order' => 1620,
      'category' => 'ai',
      'term' => [
        'de' => 'Fine-Tuning',
        'en' => 'Fine-Tuning',
      ],
      'aliases' => [
        'Model Fine-Tuning',
        'Finetuning',
      ],
      'definition' => [
        'de' => 'Nachtrainieren eines Basismodells auf eigenen Daten — Rechte, Lineage und Evaluierung nicht vergessen.',
        'en' => 'Further training a base model on your data — don’t forget rights, lineage, and evaluation.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'training-data',
          'label' => [
            'de' => 'Training Data',
            'en' => 'Training Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'llm',
          'label' => [
            'de' => 'LLM',
            'en' => 'LLM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'model-registry',
          'label' => [
            'de' => 'Model Registry',
            'en' => 'Model Registry',
          ],
        ],
      ],
    ],
    [
      'id' => 'model-registry',
      'order' => 1630,
      'category' => 'ai',
      'term' => [
        'de' => 'Model Registry',
        'en' => 'Model Registry',
      ],
      'aliases' => [
        'ML Model Registry',
        'Modellregister',
      ],
      'definition' => [
        'de' => 'Versionierte Ablage von ML-Modellen inkl. Metadaten, Stages und Freigaben — Analogon zum Data Product Catalog.',
        'en' => 'Versioned store of ML models with metadata, stages, and approvals — analogous to a data product catalog.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'feature-store',
          'label' => [
            'de' => 'Feature Store',
            'en' => 'Feature Store',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'training-data',
          'label' => [
            'de' => 'Training Data',
            'en' => 'Training Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mlops',
          'label' => [
            'de' => 'MLOps',
            'en' => 'MLOps',
          ],
        ],
      ],
    ],
    [
      'id' => 'mlops',
      'order' => 1640,
      'category' => 'ai',
      'term' => [
        'de' => 'MLOps',
        'en' => 'MLOps',
      ],
      'aliases' => [
        'Machine Learning Ops',
        'ML Operations',
      ],
      'definition' => [
        'de' => 'Betrieb und Delivery von ML-Modellen: CI/CD, Monitoring, Retraining und Governance entlang des Model Lifecycle.',
        'en' => 'Operating and delivering ML models: CI/CD, monitoring, retraining, and governance along the model lifecycle.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dataops',
          'label' => [
            'de' => 'DataOps',
            'en' => 'DataOps',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'model-registry',
          'label' => [
            'de' => 'Model Registry',
            'en' => 'Model Registry',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'feature-store',
          'label' => [
            'de' => 'Feature Store',
            'en' => 'Feature Store',
          ],
        ],
      ],
    ],
    [
      'id' => 'stakeholder',
      'order' => 48,
      'category' => 'roles',
      'term' => [
        'de' => 'Stakeholder',
        'en' => 'Stakeholder',
      ],
      'aliases' => [
        'Interessenträger',
        'Stakeholder Gruppe',
      ],
      'definition' => [
        'de' => 'Person oder Gruppe mit Interesse an Datenentscheidungen — braucht klare R/A/C/I, sonst Role Sprawl.',
        'en' => 'Person or group with a stake in data decisions — needs clear R/A/C/I, or role sprawl follows.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'raci',
          'label' => [
            'de' => 'RACI',
            'en' => 'RACI',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'decision-rights',
          'label' => [
            'de' => 'Decision Rights',
            'en' => 'Decision Rights',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'mandate',
      'order' => 50,
      'category' => 'roles',
      'term' => [
        'de' => 'Mandate',
        'en' => 'Mandate',
      ],
      'aliases' => [
        'Auftrag',
        'Governance Mandate',
      ],
      'definition' => [
        'de' => 'Formale Ermächtigung einer Rolle oder eines CoE, Entscheidungen und Policies durchzusetzen.',
        'en' => 'Formal authorization for a role or CoE to drive decisions and enforce policies.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'executive-sponsor',
          'label' => [
            'de' => 'Executive Sponsor',
            'en' => 'Executive Sponsor',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance Center of Excellence',
            'en' => 'Governance Center of Excellence',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cdo',
          'label' => [
            'de' => 'CDO',
            'en' => 'CDO',
          ],
        ],
      ],
    ],
    [
      'id' => 'accountability',
      'order' => 1414,
      'category' => 'process',
      'term' => [
        'de' => 'Accountability',
        'en' => 'Accountability',
      ],
      'aliases' => [
        'Rechenschaftspflicht',
        'Accountable',
        'A in RACI',
      ],
      'definition' => [
        'de' => 'Wer final für Ergebnis und Risiko haftet — in RACI das „A“, nicht dasselbe wie ausführendes „R“.',
        'en' => 'Who finally owns outcome and risk — the “A” in RACI, not the same as doing the work (“R”).',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'raci',
          'label' => [
            'de' => 'RACI',
            'en' => 'RACI',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'kpi-operating-model',
      'order' => 1416,
      'category' => 'process',
      'term' => [
        'de' => 'KPI Operating Model',
        'en' => 'KPI Operating Model',
      ],
      'aliases' => [
        'Metric Operating Model',
        'Kennzahlen-Operating-Model',
      ],
      'definition' => [
        'de' => 'Cadence, Rollen und Change-Prozess rund um KPIs — von Definition über Certification bis Breaking Changes.',
        'en' => 'Cadence, roles, and change process around KPIs — from definition through certification to breaking changes.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'kpi-governance',
          'label' => [
            'de' => 'KPI Governance',
            'en' => 'KPI Governance',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-contract',
          'label' => [
            'de' => 'KPI Contract',
            'en' => 'KPI Contract',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'kpi-metric-governance',
          'label' => [
            'de' => 'KPI- & Metric-Governance',
            'en' => 'KPI & metric governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-product-lifecycle',
      'order' => 213,
      'category' => 'data',
      'term' => [
        'de' => 'Data Product Lifecycle',
        'en' => 'Data Product Lifecycle',
      ],
      'aliases' => [
        'Product Lifecycle',
        'Datenprodukt-Lebenszyklus',
      ],
      'definition' => [
        'de' => 'Phasen von Intake über Build, Operate, Versionierung bis Deprecation eines Data Products.',
        'en' => 'Phases from intake through build, operate, versioning, to deprecation of a data product.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'deprecation',
          'label' => [
            'de' => 'Deprecation',
            'en' => 'Deprecation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-lifecycle',
          'label' => [
            'de' => 'Data Lifecycle',
            'en' => 'Data Lifecycle',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'missing-pieces-data-lifecycle-retirement',
          'label' => [
            'de' => 'Datenlebenszyklus & Retirement (Missing Pieces)',
            'en' => 'Data lifecycle & retirement (missing pieces)',
          ],
        ],
      ],
    ],
    [
      'id' => 'subject-area',
      'order' => 215,
      'category' => 'data',
      'term' => [
        'de' => 'Subject Area',
        'en' => 'Subject Area',
      ],
      'aliases' => [
        'Themengebiet',
        'Business Subject Area',
      ],
      'definition' => [
        'de' => 'Fachlicher Themenblock (z. B. Finance, Customer) — oft Scope für Domains, Marts und Stewardship.',
        'en' => 'Business topic block (e.g. finance, customer) — often the scope for domains, marts, and stewardship.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-domain',
          'label' => [
            'de' => 'Data Domain',
            'en' => 'Data Domain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
      ],
    ],
    [
      'id' => 'producer-consumer',
      'order' => 217,
      'category' => 'data',
      'term' => [
        'de' => 'Producer / Consumer',
        'en' => 'Producer / Consumer',
      ],
      'aliases' => [
        'Data Producer',
        'Data Consumer Pair',
        'Produzent/Konsument',
      ],
      'definition' => [
        'de' => 'Beziehung zwischen dem, der ein Data Product liefert, und dem, der es konsumiert — Contracts und SLAs klären beide Seiten.',
        'en' => 'Relationship between who delivers a data product and who consumes it — contracts and SLAs clarify both sides.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-consumer',
          'label' => [
            'de' => 'Data Consumer',
            'en' => 'Data Consumer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'consumption-contract',
          'label' => [
            'de' => 'Consumption Contract',
            'en' => 'Consumption Contract',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'one-data-product-multiple-consumers',
          'label' => [
            'de' => 'Ein Data Product, viele Consumer',
            'en' => 'One data product, multiple consumers',
          ],
        ],
      ],
    ],
    [
      'id' => 'mvp',
      'order' => 219,
      'category' => 'data',
      'term' => [
        'de' => 'MVP',
        'en' => 'MVP',
      ],
      'aliases' => [
        'Minimum Viable Product',
        'Minimum Viable Data Product',
      ],
      'definition' => [
        'de' => 'Kleinster nutzbarer Slice eines Data Products — Vertical Slice statt Big Bang, mit messbarem Outcome.',
        'en' => 'Smallest usable slice of a data product — vertical slice instead of big bang, with a measurable outcome.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'vertical-slice',
          'label' => [
            'de' => 'Vertical Slice',
            'en' => 'Vertical Slice',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'simplest-viable-architecture',
          'label' => [
            'de' => 'Simplest Viable Architecture',
            'en' => 'Simplest Viable Architecture',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'building-from-scratch',
          'label' => [
            'de' => 'Von null aufbauen',
            'en' => 'Building from scratch',
          ],
        ],
      ],
    ],
    [
      'id' => 'spark',
      'order' => 371,
      'category' => 'architecture',
      'term' => [
        'de' => 'Apache Spark',
        'en' => 'Apache Spark',
      ],
      'aliases' => [
        'Spark',
        'Spark SQL',
        'PySpark',
      ],
      'definition' => [
        'de' => 'Verteiltes Compute für Batch/Streaming auf Lakes und Warehouses — oft hinter Notebooks und Lakehouse-Engines.',
        'en' => 'Distributed compute for batch/streaming on lakes and warehouses — often behind notebooks and lakehouse engines.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'notebook',
          'label' => [
            'de' => 'Notebook',
            'en' => 'Notebook',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'choosing-the-simplest-viable-architecture',
          'label' => [
            'de' => 'Einfachst sinnvolle Architektur',
            'en' => 'Simplest viable architecture',
          ],
        ],
      ],
    ],
    [
      'id' => 'notebook',
      'order' => 372,
      'category' => 'architecture',
      'term' => [
        'de' => 'Notebook',
        'en' => 'Notebook',
      ],
      'aliases' => [
        'Data Notebook',
        'Jupyter',
        'Fabric Notebook',
      ],
      'definition' => [
        'de' => 'Interaktive Compute-Oberfläche für Exploration und Jobs — produktiv nur mit Versionierung, Tests und Ownership.',
        'en' => 'Interactive compute surface for exploration and jobs — production-ready only with versioning, tests, and ownership.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'spark',
          'label' => [
            'de' => 'Apache Spark',
            'en' => 'Apache Spark',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'transformation-as-code',
          'label' => [
            'de' => 'Transformation as Code',
            'en' => 'Transformation as Code',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
      ],
    ],
    [
      'id' => 'sql-warehouse',
      'order' => 373,
      'category' => 'architecture',
      'term' => [
        'de' => 'SQL Warehouse',
        'en' => 'SQL Warehouse',
      ],
      'aliases' => [
        'SQL Endpoint',
        'Warehouse Endpoint',
        'Serverless SQL',
      ],
      'definition' => [
        'de' => 'SQL-Compute-Endpunkt auf Lake-/Warehouse-Daten — trennt Storage und elastisches Query-Compute.',
        'en' => 'SQL compute endpoint on lake/warehouse data — separates storage from elastic query compute.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'microsoft-fabric',
          'label' => [
            'de' => 'Microsoft Fabric',
            'en' => 'Microsoft Fabric',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
      ],
    ],
    [
      'id' => 'warehouse-native-transformation',
      'order' => 374,
      'category' => 'architecture',
      'term' => [
        'de' => 'Warehouse-Native Transformation',
        'en' => 'Warehouse-Native Transformation',
      ],
      'aliases' => [
        'In-Warehouse Transform',
        'Pushdown Transformation',
      ],
      'definition' => [
        'de' => 'Transformationen laufen im Warehouse/Lakehouse (ELT), nicht in einem separaten ETL-Server.',
        'en' => 'Transformations run inside the warehouse/lakehouse (ELT), not on a separate ETL server.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'elt',
          'label' => [
            'de' => 'ELT',
            'en' => 'ELT',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'transformation-options',
          'label' => [
            'de' => 'Transformationsoptionen',
            'en' => 'Transformation options',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'dbt-role',
          'label' => [
            'de' => 'Die dbt-Rolle',
            'en' => 'The dbt role',
          ],
        ],
      ],
    ],
    [
      'id' => 'parallel-run',
      'order' => 375,
      'category' => 'architecture',
      'term' => [
        'de' => 'Parallel Run',
        'en' => 'Parallel Run',
      ],
      'aliases' => [
        'Dual Run',
        'Shadow Run',
        'Parallelbetrieb',
      ],
      'definition' => [
        'de' => 'Alt- und Neu-Pipeline gleichzeitig — Vergleich vor Cutover, um Regressionen und Trust-Lücken zu finden.',
        'en' => 'Old and new pipeline running together — compare before cutover to catch regressions and trust gaps.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'cutover',
          'label' => [
            'de' => 'Cutover',
            'en' => 'Cutover',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'strangler-pattern',
          'label' => [
            'de' => 'Strangler Pattern',
            'en' => 'Strangler Pattern',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'modernizing-an-existing-warehouse',
          'label' => [
            'de' => 'Bestehendes Warehouse modernisieren',
            'en' => 'Modernizing an existing warehouse',
          ],
        ],
      ],
    ],
    [
      'id' => 'finops',
      'order' => 376,
      'category' => 'architecture',
      'term' => [
        'de' => 'FinOps',
        'en' => 'FinOps',
      ],
      'aliases' => [
        'Cloud FinOps',
        'Cloud Cost Ops',
      ],
      'definition' => [
        'de' => 'Kostenverantwortung für Cloud-/Compute-Nutzung — Tags, Budgets und Idle-Cleanup gehören zur Plattform-Governance.',
        'en' => 'Cost accountability for cloud/compute use — tags, budgets, and idle cleanup belong in platform governance.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'platform-ops',
          'label' => [
            'de' => 'Platform Ops',
            'en' => 'Platform Ops',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'orchestration',
          'label' => [
            'de' => 'Orchestration',
            'en' => 'Orchestration',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operating-and-governing-the-platform',
          'label' => [
            'de' => 'Plattform betreiben und steuern',
            'en' => 'Operating and governing the platform',
          ],
        ],
      ],
    ],
    [
      'id' => 'dbt-seed',
      'order' => 377,
      'category' => 'architecture',
      'term' => [
        'de' => 'dbt Seed',
        'en' => 'dbt Seed',
      ],
      'aliases' => [
        'Seed',
        'CSV Seed',
      ],
      'definition' => [
        'de' => 'Versionierte CSV-/Flat-Daten in dbt — typisch für Reference Data und kleine Lookups.',
        'en' => 'Versioned CSV/flat data in dbt — typical for reference data and small lookups.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'reference-data',
          'label' => [
            'de' => 'Reference Data',
            'en' => 'Reference Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'materialization',
          'label' => [
            'de' => 'Materialization',
            'en' => 'Materialization',
          ],
        ],
      ],
    ],
    [
      'id' => 'dbt-snapshot',
      'order' => 378,
      'category' => 'architecture',
      'term' => [
        'de' => 'dbt Snapshot',
        'en' => 'dbt Snapshot',
      ],
      'aliases' => [
        'Snapshot',
        'SCD Snapshot',
      ],
      'definition' => [
        'de' => 'dbt-Mechanismus für historisierte Snapshots von Quelltabellen — verwandt mit SCD2/As-Was.',
        'en' => 'dbt mechanism for historized snapshots of source tables — related to SCD2/as-was history.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'as-was-history',
          'label' => [
            'de' => 'As-Was History',
            'en' => 'As-Was History',
          ],
        ],
      ],
    ],
    [
      'id' => 'dbt-exposure',
      'order' => 379,
      'category' => 'architecture',
      'term' => [
        'de' => 'dbt Exposure',
        'en' => 'dbt Exposure',
      ],
      'aliases' => [
        'Exposure',
        'Downstream Exposure',
      ],
      'definition' => [
        'de' => 'Deklarierte Downstream-Nutzung (Dashboard, ML, App) in dbt — macht Impact und Ownership sichtbar.',
        'en' => 'Declared downstream use (dashboard, ML, app) in dbt — makes impact and ownership visible.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'impact-analysis',
          'label' => [
            'de' => 'Impact Analysis',
            'en' => 'Impact Analysis',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
      ],
    ],
    [
      'id' => 'jinja',
      'order' => 401,
      'category' => 'architecture',
      'term' => [
        'de' => 'Jinja',
        'en' => 'Jinja',
      ],
      'aliases' => [
        'Jinja2',
        'dbt Jinja',
      ],
      'definition' => [
        'de' => 'Templating-Sprache hinter dbt Macros und Models — mächtig, aber ohne Standards schnell unlesbar.',
        'en' => 'Templating language behind dbt macros and models — powerful, but unreadable without standards.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dbt-macro',
          'label' => [
            'de' => 'dbt Macro',
            'en' => 'dbt Macro',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'automatic-raw-generation-using-dbt-macros',
          'label' => [
            'de' => 'Automatische RAW-Generierung mit dbt Macros',
            'en' => 'Automatic RAW generation with dbt macros',
          ],
        ],
      ],
    ],
    [
      'id' => 'scd-type-1',
      'order' => 541,
      'category' => 'modeling',
      'term' => [
        'de' => 'SCD Type 1',
        'en' => 'SCD Type 1',
      ],
      'aliases' => [
        'Type 1 SCD',
        'Overwrite History',
      ],
      'definition' => [
        'de' => 'Dimensionsänderung überschreibt den aktuellen Wert — keine Historie, einfach und oft falsch für Audit.',
        'en' => 'Dimension change overwrites the current value — no history; simple and often wrong for audit.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'scd',
          'label' => [
            'de' => 'SCD (Slowly Changing Dimension)',
            'en' => 'SCD (Slowly Changing Dimension)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'slowlychange-dim',
          'label' => [
            'de' => 'Slowly Changing Dimensions',
            'en' => 'Slowly changing dimensions',
          ],
        ],
      ],
    ],
    [
      'id' => 'scd-type-3',
      'order' => 542,
      'category' => 'modeling',
      'term' => [
        'de' => 'SCD Type 3',
        'en' => 'SCD Type 3',
      ],
      'aliases' => [
        'Type 3 SCD',
        'Previous Value Column',
      ],
      'definition' => [
        'de' => 'Speichert begrenzte Vorversionen in Extra-Spalten — selten; meist besser SCD2 oder As-Was.',
        'en' => 'Stores limited prior versions in extra columns — rare; usually SCD2 or as-was is better.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'scd',
          'label' => [
            'de' => 'SCD (Slowly Changing Dimension)',
            'en' => 'SCD (Slowly Changing Dimension)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'as-was-history',
          'label' => [
            'de' => 'As-Was History',
            'en' => 'As-Was History',
          ],
        ],
      ],
    ],
    [
      'id' => 'role-playing-dimension',
      'order' => 543,
      'category' => 'modeling',
      'term' => [
        'de' => 'Role-Playing Dimension',
        'en' => 'Role-Playing Dimension',
      ],
      'aliases' => [
        'Role Playing Dim',
        'Date Role Playing',
      ],
      'definition' => [
        'de' => 'Dieselbe Dimension mehrfach in unterschiedlichen Rollen (Order Date, Ship Date) — ohne doppelte physische Tabellen.',
        'en' => 'Same dimension used multiple times in different roles (order date, ship date) — without duplicating physical tables.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'conformed-dimension',
          'label' => [
            'de' => 'Conformed Dimension',
            'en' => 'Conformed Dimension',
          ],
        ],
      ],
    ],
    [
      'id' => 'factless-fact',
      'order' => 545,
      'category' => 'modeling',
      'term' => [
        'de' => 'Factless Fact',
        'en' => 'Factless Fact Table',
      ],
      'aliases' => [
        'Factless Fact Table',
        'Coverage Fact',
        'Event Fact ohne Measure',
      ],
      'definition' => [
        'de' => 'Fact-Tabelle ohne numerische Measures — erfasst Ereignisse oder Coverage (z. B. Anwesenheit, Promotion-Coverage).',
        'en' => 'Fact table without numeric measures — captures events or coverage (e.g. attendance, promotion coverage).',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
      ],
    ],
    [
      'id' => 'late-arriving',
      'order' => 546,
      'category' => 'modeling',
      'term' => [
        'de' => 'Late-Arriving Data',
        'en' => 'Late-Arriving Data',
      ],
      'aliases' => [
        'Late Arriving Facts',
        'Late Arriving Dimensions',
        'Verspätete Daten',
      ],
      'definition' => [
        'de' => 'Facts oder Dimensionen kommen nach dem erwarteten Load-Fenster — Incremental/SCD-Strategien müssen das aushalten.',
        'en' => 'Facts or dimensions arrive after the expected load window — incremental/SCD strategies must tolerate it.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'incremental-model',
          'label' => [
            'de' => 'Incremental Model',
            'en' => 'Incremental Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cdc',
          'label' => [
            'de' => 'CDC (Change Data Capture)',
            'en' => 'CDC (Change Data Capture)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
      ],
    ],
    [
      'id' => 'matching',
      'order' => 547,
      'category' => 'modeling',
      'term' => [
        'de' => 'Matching',
        'en' => 'Matching',
      ],
      'aliases' => [
        'Entity Matching',
        'Record Matching',
        'Dedup Matching',
      ],
      'definition' => [
        'de' => 'Entscheidung, welche Quellrecords dieselbe Entität sind — Voraussetzung für Golden Record und MDM.',
        'en' => 'Decision of which source records are the same entity — prerequisite for golden record and MDM.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'golden-record',
          'label' => [
            'de' => 'Golden Record',
            'en' => 'Golden Record',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'master-data',
          'label' => [
            'de' => 'Master Data',
            'en' => 'Master Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'uniqueness',
          'label' => [
            'de' => 'Uniqueness',
            'en' => 'Uniqueness',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => [
            'de' => 'Jenseits von Bronze-Silver-Gold',
            'en' => 'Beyond bronze-silver-gold',
          ],
        ],
      ],
    ],
    [
      'id' => 'historized-entity',
      'order' => 549,
      'category' => 'modeling',
      'term' => [
        'de' => 'Historized Entity',
        'en' => 'Historized Entity',
      ],
      'aliases' => [
        'Historisierte Entität',
        'Temporal Entity',
      ],
      'definition' => [
        'de' => 'Entität mit gültiger Zeitachse (Valid From/To) — Kern des Integrated Core und As-Was-Reporting.',
        'en' => 'Entity with a validity timeline (valid from/to) — core of the integrated core and as-was reporting.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'as-was-history',
          'label' => [
            'de' => 'As-Was History',
            'en' => 'As-Was History',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
      ],
    ],
    [
      'id' => 'bus-matrix',
      'order' => 550,
      'category' => 'modeling',
      'term' => [
        'de' => 'Bus Matrix',
        'en' => 'Bus Matrix',
      ],
      'aliases' => [
        'Kimball Bus Matrix',
        'Conformed Bus',
      ],
      'definition' => [
        'de' => 'Matrix aus Business Processes × Conformed Dimensions — Planungsinstrument für Marts und Shared Dimensions.',
        'en' => 'Matrix of business processes × conformed dimensions — planning tool for marts and shared dimensions.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'conformed-dimension',
          'label' => [
            'de' => 'Conformed Dimension',
            'en' => 'Conformed Dimension',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
      ],
    ],
    [
      'id' => 'time-intelligence',
      'order' => 599,
      'category' => 'bi',
      'term' => [
        'de' => 'Time Intelligence',
        'en' => 'Time Intelligence',
      ],
      'aliases' => [
        'YTD/MTD',
        'Period Comparisons',
        'Zeitintelligenz',
      ],
      'definition' => [
        'de' => 'Periodenvergleiche und YTD/MTD-Logik — zentral im Semantic Layer, nicht als Copy-Paste in jedem Report.',
        'en' => 'Period comparisons and YTD/MTD logic — central in the semantic layer, not copy-pasted into every report.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'calculation-group',
          'label' => [
            'de' => 'Calculation Group',
            'en' => 'Calculation Group',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'master-measure',
          'label' => [
            'de' => 'Master Measure',
            'en' => 'Master Measure',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dax',
          'label' => [
            'de' => 'DAX',
            'en' => 'DAX',
          ],
        ],
      ],
    ],
    [
      'id' => 'composite-model',
      'order' => 601,
      'category' => 'bi',
      'term' => [
        'de' => 'Composite Model',
        'en' => 'Composite Model',
      ],
      'aliases' => [
        'Mixed Storage Mode',
        'DirectQuery + Import',
      ],
      'definition' => [
        'de' => 'Semantisches Modell mit gemischten Storage Modes — Flexibilität mit höherer Governance- und Performance-Komplexität.',
        'en' => 'Semantic model with mixed storage modes — flexibility with higher governance and performance complexity.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'directquery',
          'label' => [
            'de' => 'DirectQuery',
            'en' => 'DirectQuery',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'tabular-model',
          'label' => [
            'de' => 'Tabular Model',
            'en' => 'Tabular Model',
          ],
        ],
      ],
    ],
    [
      'id' => 'master-dimension',
      'order' => 602,
      'category' => 'bi',
      'term' => [
        'de' => 'Master Dimension',
        'en' => 'Master Dimension',
      ],
      'aliases' => [
        'Shared Dimension',
        'Master Item Dimension',
      ],
      'definition' => [
        'de' => 'Wiederverwendbare Dimension/Hierarchie im Semantic Layer — analog zur Master Measure für Attribute.',
        'en' => 'Reusable dimension/hierarchy in the semantic layer — analogous to a master measure for attributes.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'master-measure',
          'label' => [
            'de' => 'Master Measure',
            'en' => 'Master Measure',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'conformed-dimension',
          'label' => [
            'de' => 'Conformed Dimension',
            'en' => 'Conformed Dimension',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
      ],
    ],
    [
      'id' => 'metric-layers',
      'order' => 603,
      'category' => 'bi',
      'term' => [
        'de' => 'Metric Layers',
        'en' => 'Metric Layers',
      ],
      'aliases' => [
        'Three Layers of Metric Logic',
        'Kennzahlen-Schichten',
      ],
      'definition' => [
        'de' => 'Trennung von Rohmaß, Business Measure und Presentation Measure — verhindert Logik in jedem Dashboard.',
        'en' => 'Separation of raw measure, business measure, and presentation measure — prevents logic in every dashboard.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'master-measure',
          'label' => [
            'de' => 'Master Measure',
            'en' => 'Master Measure',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'thin-consumer-interface',
          'label' => [
            'de' => 'Thin Consumer Interface',
            'en' => 'Thin Consumer Interface',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'kpi-metric-governance',
          'label' => [
            'de' => 'KPI- & Metric-Governance',
            'en' => 'KPI & metric governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'governed-self-service',
      'order' => 604,
      'category' => 'bi',
      'term' => [
        'de' => 'Governed Self-Service',
        'en' => 'Governed Self-Service',
      ],
      'aliases' => [
        'Guided Self-Service',
        'Governed Analytics',
      ],
      'definition' => [
        'de' => 'Self-Service auf zertifizierten Datasets und klaren Contracts — Freiheit mit Leitplanken statt Shadow IT.',
        'en' => 'Self-service on certified datasets and clear contracts — freedom with guardrails instead of shadow IT.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'self-service-bi',
          'label' => [
            'de' => 'Self-Service BI',
            'en' => 'Self-Service BI',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'certified-dataset',
          'label' => [
            'de' => 'Certified Dataset',
            'en' => 'Certified Dataset',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'shadow-it',
          'label' => [
            'de' => 'Shadow IT',
            'en' => 'Shadow IT',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'bi-tools',
          'label' => [
            'de' => 'BI-Tools',
            'en' => 'BI tools',
          ],
        ],
      ],
    ],
    [
      'id' => 'quality-score',
      'order' => 792,
      'category' => 'quality',
      'term' => [
        'de' => 'Quality Score',
        'en' => 'Quality Score',
      ],
      'aliases' => [
        'DQ Score',
        'Data Quality Score',
      ],
      'definition' => [
        'de' => 'Aggregierte Kennzahl aus DQ-Regeln/Dimensionen — nützlich als Trend, gefährlich als alleinige Wahrheit.',
        'en' => 'Aggregated score from DQ rules/dimensions — useful as a trend, dangerous as the only truth.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'rule-registry',
          'label' => [
            'de' => 'Rule Registry',
            'en' => 'Rule Registry',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-quality-governance',
          'label' => [
            'de' => 'Data-Quality-Governance',
            'en' => 'Data quality governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'assertion',
      'order' => 794,
      'category' => 'quality',
      'term' => [
        'de' => 'Assertion',
        'en' => 'Assertion',
      ],
      'aliases' => [
        'Data Assertion',
        'Test Assertion',
        'Expect',
      ],
      'definition' => [
        'de' => 'Maschinenprüfbare Erwartung an Daten (Row Counts, Ranges, Referenzen) — Baustein von Contract-as-Code.',
        'en' => 'Machine-checkable expectation on data (row counts, ranges, references) — building block of contract-as-code.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'contract-as-code',
          'label' => [
            'de' => 'Contract as Code',
            'en' => 'Contract as Code',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dq-gate',
          'label' => [
            'de' => 'DQ Gate',
            'en' => 'DQ Gate',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
      ],
    ],
    [
      'id' => 'on-call',
      'order' => 796,
      'category' => 'quality',
      'term' => [
        'de' => 'On-Call',
        'en' => 'On-Call',
      ],
      'aliases' => [
        'Pager Duty',
        'Bereitschaft',
        'Incident On-Call',
      ],
      'definition' => [
        'de' => 'Rotierende Verantwortung für Incidents außerhalb der Bürozeiten — braucht Runbooks und klare Escalation.',
        'en' => 'Rotating responsibility for off-hours incidents — needs runbooks and clear escalation.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'incident-runbook',
          'label' => [
            'de' => 'Incident Runbook',
            'en' => 'Incident Runbook',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'escalation-path',
          'label' => [
            'de' => 'Escalation Path',
            'en' => 'Escalation Path',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'platform-ops',
          'label' => [
            'de' => 'Platform Ops',
            'en' => 'Platform Ops',
          ],
        ],
      ],
    ],
    [
      'id' => 'hashing',
      'order' => 918,
      'category' => 'privacy',
      'term' => [
        'de' => 'Hashing',
        'en' => 'Hashing',
      ],
      'aliases' => [
        'One-Way Hash',
        'Hashing PII',
        'Hashwert',
      ],
      'definition' => [
        'de' => 'Einweg-Transformation von Identifiers — oft für Joins ohne Klartext-PII, mit Collision- und Rainbow-Risiken.',
        'en' => 'One-way transform of identifiers — often for joins without plaintext PII, with collision and rainbow risks.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'tokenization',
          'label' => [
            'de' => 'Tokenization',
            'en' => 'Tokenization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pseudonymization',
          'label' => [
            'de' => 'Pseudonymization',
            'en' => 'Pseudonymization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-minimization',
      'order' => 920,
      'category' => 'privacy',
      'term' => [
        'de' => 'Data Minimization',
        'en' => 'Data Minimization',
      ],
      'aliases' => [
        'Datenminimierung',
        'Need-to-Know Data',
      ],
      'definition' => [
        'de' => 'Nur notwendige Attribute und Rows speichern/teilen — eng mit Purpose Limitation und Least Privilege.',
        'en' => 'Store/share only necessary attributes and rows — tightly linked to purpose limitation and least privilege.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'purpose-limitation',
          'label' => [
            'de' => 'Purpose Limitation',
            'en' => 'Purpose Limitation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'pii-privacy-governance',
          'label' => [
            'de' => 'PII- & Privacy-Governance',
            'en' => 'PII & privacy governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'service-principal',
      'order' => 1078,
      'category' => 'security',
      'term' => [
        'de' => 'Service Principal',
        'en' => 'Service Principal',
      ],
      'aliases' => [
        'App Identity',
        'Workload Identity',
        'Service Account',
      ],
      'definition' => [
        'de' => 'Nicht-menschliche Identität für Pipelines und Apps — eigene Entitlements, Rotation und Recertification.',
        'en' => 'Non-human identity for pipelines and apps — own entitlements, rotation, and recertification.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'iam',
          'label' => [
            'de' => 'IAM',
            'en' => 'IAM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'entitlement',
          'label' => [
            'de' => 'Entitlement',
            'en' => 'Entitlement',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'access-recertification',
          'label' => [
            'de' => 'Access Recertification',
            'en' => 'Access Recertification',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => [
            'de' => 'Zugriffs- & Security-Governance',
            'en' => 'Access & security governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'static-metadata',
      'order' => 1316,
      'category' => 'metadata',
      'term' => [
        'de' => 'Static Metadata',
        'en' => 'Static Metadata',
      ],
      'aliases' => [
        'Statische Metadaten',
        'Slow-Changing Metadata',
      ],
      'definition' => [
        'de' => 'Selten ändernde Metadaten (Owner, Glossary, Classification) — oft declared, nicht jede Minute geharvestet.',
        'en' => 'Slow-changing metadata (owner, glossary, classification) — often declared, not harvested every minute.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dynamic-metadata',
          'label' => [
            'de' => 'Dynamic Metadata',
            'en' => 'Dynamic Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'declared-metadata',
          'label' => [
            'de' => 'Declared Metadata',
            'en' => 'Declared Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'what-metadata-actually-is',
          'label' => [
            'de' => 'Was Metadaten wirklich sind',
            'en' => 'What metadata actually is',
          ],
        ],
      ],
    ],
    [
      'id' => 'dynamic-metadata',
      'order' => 1318,
      'category' => 'metadata',
      'term' => [
        'de' => 'Dynamic Metadata',
        'en' => 'Dynamic Metadata',
      ],
      'aliases' => [
        'Dynamische Metadaten',
        'Runtime Metadata',
      ],
      'definition' => [
        'de' => 'Häufig wechselnde Metadaten (Freshness, Usage, Job Status) — typisch detected/observed aus Runtime.',
        'en' => 'Frequently changing metadata (freshness, usage, job status) — typically detected/observed from runtime.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'static-metadata',
          'label' => [
            'de' => 'Static Metadata',
            'en' => 'Static Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'detected-metadata',
          'label' => [
            'de' => 'Detected Metadata',
            'en' => 'Detected Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'operational-metadata',
          'label' => [
            'de' => 'Operational Metadata',
            'en' => 'Operational Metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'source-local-metadata',
      'order' => 1321,
      'category' => 'metadata',
      'term' => [
        'de' => 'Source-Local Metadata',
        'en' => 'Source-Local Metadata',
      ],
      'aliases' => [
        'Native Metadata',
        'Tool-Local Metadata',
      ],
      'definition' => [
        'de' => 'Metadaten, die im Quelltool entstehen und dort gepflegt werden — Harvesting holt sie in den Graph.',
        'en' => 'Metadata that originates and is maintained in the source tool — harvesting pulls it into the graph.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'metadata-harvesting',
          'label' => [
            'de' => 'Metadata Harvesting',
            'en' => 'Metadata Harvesting',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'keep-metadata-close-to-the-source',
          'label' => [
            'de' => 'Metadaten nah an der Quelle halten',
            'en' => 'Keep metadata close to the source',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'native-metadata-across-the-data-stack',
          'label' => [
            'de' => 'Native Metadaten im Data Stack',
            'en' => 'Native metadata across the data stack',
          ],
        ],
      ],
    ],
    [
      'id' => 'descriptive-metadata',
      'order' => 1322,
      'category' => 'metadata',
      'term' => [
        'de' => 'Descriptive Metadata',
        'en' => 'Descriptive Metadata',
      ],
      'aliases' => [
        'Beschreibende Metadaten',
        'Documentation Metadata',
      ],
      'definition' => [
        'de' => 'Metadaten, die erklären und finden helfen — im Gegensatz zu control-driving Metadata, die Systeme steuern.',
        'en' => 'Metadata that explains and helps discovery — unlike control-driving metadata that steers systems.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'control-driving-metadata',
          'label' => [
            'de' => 'Control-Driving Metadata',
            'en' => 'Control-Driving Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'business-metadata',
          'label' => [
            'de' => 'Business Metadata',
            'en' => 'Business Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'governance-metadata-that-controls-data',
          'label' => [
            'de' => 'Governance-Metadaten, die Daten steuern',
            'en' => 'Governance metadata that controls data',
          ],
        ],
      ],
    ],
    [
      'id' => 'distributed-metadata',
      'order' => 1324,
      'category' => 'metadata',
      'term' => [
        'de' => 'Distributed Metadata',
        'en' => 'Distributed Metadata',
      ],
      'aliases' => [
        'Verteilte Metadaten',
        'Multi-Catalog Metadata',
      ],
      'definition' => [
        'de' => 'Metadaten über viele Tools/Domains ohne zwingendes Single Catalog — braucht Federierung und klare Source of Truth je Aspekt.',
        'en' => 'Metadata across many tools/domains without a mandatory single catalog — needs federation and a clear source of truth per aspect.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'centralized-vs-federated-metadata',
          'label' => [
            'de' => 'Centralized vs Federated Metadata',
            'en' => 'Centralized vs Federated Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metadata-graph',
          'label' => [
            'de' => 'Metadata Graph',
            'en' => 'Metadata Graph',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'centralized-federated-or-distributed-metadata',
          'label' => [
            'de' => 'Zentral, föderiert oder verteilt',
            'en' => 'Centralized, federated, or distributed metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'bounded-context',
      'order' => 1326,
      'category' => 'metadata',
      'term' => [
        'de' => 'Bounded Context',
        'en' => 'Bounded Context',
      ],
      'aliases' => [
        'DDD Bounded Context',
        'Fachlicher Kontext',
      ],
      'definition' => [
        'de' => 'Abgegrenzter Bedeutungsraum für Begriffe und Modelle — verhindert, dass „Kunde“ überall dasselbe heißen muss.',
        'en' => 'Bounded meaning space for terms and models — prevents “customer” meaning the same thing everywhere.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-domain',
          'label' => [
            'de' => 'Data Domain',
            'en' => 'Data Domain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'enterprise-vocabulary',
          'label' => [
            'de' => 'Enterprise Vocabulary',
            'en' => 'Enterprise Vocabulary',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'business-glossary',
          'label' => [
            'de' => 'Business Glossary',
            'en' => 'Business Glossary',
          ],
        ],
      ],
    ],
    [
      'id' => 'ai-eval',
      'order' => 1650,
      'category' => 'ai',
      'term' => [
        'de' => 'AI Evaluation',
        'en' => 'AI Evaluation',
      ],
      'aliases' => [
        'Model Eval',
        'LLM Eval',
        'Eval Harness',
      ],
      'definition' => [
        'de' => 'Systematisches Messen von Qualität, Safety und Regressionen von Modellen/Agents — vor und nach Deploy.',
        'en' => 'Systematic measurement of quality, safety, and regressions for models/agents — before and after deploy.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'hallucination',
          'label' => [
            'de' => 'Hallucination',
            'en' => 'Hallucination',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-eval',
          'label' => [
            'de' => 'AI-Evaluation',
            'en' => 'AI evaluation',
          ],
        ],
      ],
    ],
    [
      'id' => 'prompt-engineering',
      'order' => 1660,
      'category' => 'ai',
      'term' => [
        'de' => 'Prompt Engineering',
        'en' => 'Prompt Engineering',
      ],
      'aliases' => [
        'Prompt Design',
        'Prompting',
      ],
      'definition' => [
        'de' => 'Gestalten von Prompts und System-Instructions — ersetzt weder Grounding noch Guardrails und Rechteprüfung.',
        'en' => 'Crafting prompts and system instructions — replaces neither grounding nor guardrails and rights checks.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'llm',
          'label' => [
            'de' => 'LLM',
            'en' => 'LLM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation)',
            'en' => 'RAG (Retrieval-Augmented Generation)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'prompt-injection',
          'label' => [
            'de' => 'Prompt Injection',
            'en' => 'Prompt Injection',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-language-models',
          'label' => [
            'de' => 'Sprachmodelle',
            'en' => 'Language models',
          ],
        ],
      ],
    ],
    [
      'id' => 'backfill',
      'order' => 402,
      'category' => 'architecture',
      'term' => [
        'de' => 'Backfill',
        'en' => 'Backfill',
      ],
      'aliases' => [
        'Historical Backfill',
        'Catch-up Load',
        'Nachladen',
      ],
      'definition' => [
        'de' => 'Nachträgliches Laden historischer Perioden — braucht Idempotenz, Watermarks und klare Cutover-Regeln.',
        'en' => 'Loading historical periods after the fact — needs idempotency, watermarks, and clear cutover rules.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'idempotent',
          'label' => [
            'de' => 'Idempotent Load',
            'en' => 'Idempotent Load',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'watermark',
          'label' => [
            'de' => 'Watermark',
            'en' => 'Watermark',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'full-load',
          'label' => [
            'de' => 'Full Load',
            'en' => 'Full Load',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operating-and-governing-the-platform',
          'label' => [
            'de' => 'Plattform betreiben und steuern',
            'en' => 'Operating and governing the platform',
          ],
        ],
      ],
    ],
    [
      'id' => 'watermark',
      'order' => 403,
      'category' => 'architecture',
      'term' => [
        'de' => 'Watermark',
        'en' => 'Watermark',
      ],
      'aliases' => [
        'High-Water Mark',
        'Incremental Cursor',
        'Wasserstand',
      ],
      'definition' => [
        'de' => 'Fortschrittsmarke für Incremental Loads (Zeitstempel/ID) — falsch gesetzt erzeugt Lücken oder Duplikate.',
        'en' => 'Progress marker for incremental loads (timestamp/id) — set wrong, you get gaps or duplicates.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'incremental-model',
          'label' => [
            'de' => 'Incremental Model',
            'en' => 'Incremental Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'delta-load',
          'label' => [
            'de' => 'Delta / Incremental Load',
            'en' => 'Delta / Incremental Load',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cdc',
          'label' => [
            'de' => 'CDC (Change Data Capture)',
            'en' => 'CDC (Change Data Capture)',
          ],
        ],
      ],
    ],
    [
      'id' => 'idempotent',
      'order' => 404,
      'category' => 'architecture',
      'term' => [
        'de' => 'Idempotent Load',
        'en' => 'Idempotent Load',
      ],
      'aliases' => [
        'Idempotency',
        'Idempotent Pipeline',
        'Idempotent',
      ],
      'definition' => [
        'de' => 'Erneutes Ausführen desselben Jobs ändert das Ergebnis nicht unkontrolliert — Pflicht für Retries und Backfills.',
        'en' => 'Re-running the same job does not change the result uncontrollably — mandatory for retries and backfills.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'backfill',
          'label' => [
            'de' => 'Backfill',
            'en' => 'Backfill',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'orchestration',
          'label' => [
            'de' => 'Orchestration',
            'en' => 'Orchestration',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
      ],
    ],
    [
      'id' => 'full-load',
      'order' => 405,
      'category' => 'architecture',
      'term' => [
        'de' => 'Full Load',
        'en' => 'Full Load',
      ],
      'aliases' => [
        'Initial Load',
        'Reload',
        'Vollast',
      ],
      'definition' => [
        'de' => 'Komplettes Neuladen einer Tabelle/Partition — einfach, teuer; oft nur für Bootstrap oder Repair.',
        'en' => 'Full reload of a table/partition — simple, expensive; often only for bootstrap or repair.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'delta-load',
          'label' => [
            'de' => 'Delta / Incremental Load',
            'en' => 'Delta / Incremental Load',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'backfill',
          'label' => [
            'de' => 'Backfill',
            'en' => 'Backfill',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
      ],
    ],
    [
      'id' => 'soft-delete',
      'order' => 406,
      'category' => 'architecture',
      'term' => [
        'de' => 'Soft Delete',
        'en' => 'Soft Delete',
      ],
      'aliases' => [
        'Logical Delete',
        'Is Deleted Flag',
        'Weiches Löschen',
      ],
      'definition' => [
        'de' => 'Datensatz als gelöscht markieren statt physisch entfernen — erleichtert Audit und CDC, kompliziert Queries.',
        'en' => 'Mark a record deleted instead of physically removing it — helps audit and CDC, complicates queries.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'cdc',
          'label' => [
            'de' => 'CDC (Change Data Capture)',
            'en' => 'CDC (Change Data Capture)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'retention',
          'label' => [
            'de' => 'Retention',
            'en' => 'Retention',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dsdr',
          'label' => [
            'de' => 'DSDR',
            'en' => 'DSDR',
          ],
        ],
      ],
    ],
    [
      'id' => 'time-travel',
      'order' => 407,
      'category' => 'architecture',
      'term' => [
        'de' => 'Time Travel',
        'en' => 'Time Travel',
      ],
      'aliases' => [
        'Table Time Travel',
        'As-Of Query',
      ],
      'definition' => [
        'de' => 'Abfrage eines früheren Tabellenstands (Lakehouse/Warehouse) — nützlich für Audit und Rollback, kostet Retention.',
        'en' => 'Querying a prior table version (lakehouse/warehouse) — useful for audit and rollback, costs retention.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'iceberg',
          'label' => [
            'de' => 'Apache Iceberg',
            'en' => 'Apache Iceberg',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'as-was-history',
          'label' => [
            'de' => 'As-Was History',
            'en' => 'As-Was History',
          ],
        ],
      ],
    ],
    [
      'id' => 'zero-copy-clone',
      'order' => 408,
      'category' => 'architecture',
      'term' => [
        'de' => 'Zero-Copy Clone',
        'en' => 'Zero-Copy Clone',
      ],
      'aliases' => [
        'Shallow Clone',
        'Clone',
        'Nullkopie-Klon',
      ],
      'definition' => [
        'de' => 'Klon einer Tabelle/DB ohne Datenverdopplung — ideal für Dev/Test, solange Lifecycle und Rechte klar sind.',
        'en' => 'Clone of a table/DB without duplicating data — ideal for dev/test when lifecycle and rights are clear.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'time-travel',
          'label' => [
            'de' => 'Time Travel',
            'en' => 'Time Travel',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operating-and-governing-the-platform',
          'label' => [
            'de' => 'Plattform betreiben und steuern',
            'en' => 'Operating and governing the platform',
          ],
        ],
      ],
    ],
    [
      'id' => 'partition-pruning',
      'order' => 409,
      'category' => 'architecture',
      'term' => [
        'de' => 'Partition Pruning',
        'en' => 'Partition Pruning',
      ],
      'aliases' => [
        'Partition Elimination',
        'Partition Cut',
      ],
      'definition' => [
        'de' => 'Query liest nur relevante Partitionen — setzt saubere Partition Keys und Filter in Contracts voraus.',
        'en' => 'Query reads only relevant partitions — requires clean partition keys and filters in contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'predicate-pushdown',
          'label' => [
            'de' => 'Predicate Pushdown',
            'en' => 'Predicate Pushdown',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'parquet',
          'label' => [
            'de' => 'Parquet',
            'en' => 'Parquet',
          ],
        ],
      ],
    ],
    [
      'id' => 'predicate-pushdown',
      'order' => 411,
      'category' => 'architecture',
      'term' => [
        'de' => 'Predicate Pushdown',
        'en' => 'Predicate Pushdown',
      ],
      'aliases' => [
        'Filter Pushdown',
        'Pushdown Predicates',
      ],
      'definition' => [
        'de' => 'Filter werden möglichst nah an Storage/Engine ausgeführt — weniger I/O, schnellere Marts und BI.',
        'en' => 'Filters execute as close to storage/engine as possible — less I/O, faster marts and BI.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'partition-pruning',
          'label' => [
            'de' => 'Partition Pruning',
            'en' => 'Partition Pruning',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'directquery',
          'label' => [
            'de' => 'DirectQuery',
            'en' => 'DirectQuery',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'sql-warehouse',
          'label' => [
            'de' => 'SQL Warehouse',
            'en' => 'SQL Warehouse',
          ],
        ],
      ],
    ],
    [
      'id' => 'compaction',
      'order' => 412,
      'category' => 'architecture',
      'term' => [
        'de' => 'Compaction',
        'en' => 'Compaction',
      ],
      'aliases' => [
        'File Compaction',
        'Optimize',
        'Vacuum',
      ],
      'definition' => [
        'de' => 'Zusammenführen kleiner Lake-Dateien und Aufräumen — hält Scan-Kosten und Time Travel im Rahmen.',
        'en' => 'Merging small lake files and cleanup — keeps scan cost and time travel in check.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'iceberg',
          'label' => [
            'de' => 'Apache Iceberg',
            'en' => 'Apache Iceberg',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'finops',
          'label' => [
            'de' => 'FinOps',
            'en' => 'FinOps',
          ],
        ],
      ],
    ],
    [
      'id' => 'hudi',
      'order' => 413,
      'category' => 'architecture',
      'term' => [
        'de' => 'Apache Hudi',
        'en' => 'Apache Hudi',
      ],
      'aliases' => [
        'Hudi',
        'Hudi Table Format',
      ],
      'definition' => [
        'de' => 'Lakehouse Table Format mit Upserts/Incremental Processing — Alternative/Ergänzung zu Delta und Iceberg.',
        'en' => 'Lakehouse table format with upserts/incremental processing — alternative/complement to Delta and Iceberg.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'iceberg',
          'label' => [
            'de' => 'Apache Iceberg',
            'en' => 'Apache Iceberg',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
      ],
    ],
    [
      'id' => 'avro',
      'order' => 414,
      'category' => 'architecture',
      'term' => [
        'de' => 'Avro',
        'en' => 'Avro',
      ],
      'aliases' => [
        'Apache Avro',
        'Avro Schema',
      ],
      'definition' => [
        'de' => 'Row-orientiertes Serialisierungsformat mit Schema — oft in Streaming und Schema Registries.',
        'en' => 'Row-oriented serialization format with schema — common in streaming and schema registries.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'schema-registry',
          'label' => [
            'de' => 'Schema Registry',
            'en' => 'Schema Registry',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'schema-evolution',
          'label' => [
            'de' => 'Schema Evolution',
            'en' => 'Schema Evolution',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'streaming',
          'label' => [
            'de' => 'Streaming',
            'en' => 'Streaming',
          ],
        ],
      ],
    ],
    [
      'id' => 'schema-evolution',
      'order' => 415,
      'category' => 'architecture',
      'term' => [
        'de' => 'Schema Evolution',
        'en' => 'Schema Evolution',
      ],
      'aliases' => [
        'Compatible Schema Change',
        'Schema Compatibility',
      ],
      'definition' => [
        'de' => 'Geplante Änderung von Schemas ohne unkontrollierte Breaks — Compatibility-Regeln und Contracts gehören dazu.',
        'en' => 'Planned schema change without uncontrolled breaks — needs compatibility rules and contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'schema-drift',
          'label' => [
            'de' => 'Schema Drift',
            'en' => 'Schema Drift',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'breaking-change',
          'label' => [
            'de' => 'Breaking Change',
            'en' => 'Breaking Change',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'schema-registry',
          'label' => [
            'de' => 'Schema Registry',
            'en' => 'Schema Registry',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
      ],
    ],
    [
      'id' => 'workflow-orchestrator',
      'order' => 416,
      'category' => 'architecture',
      'term' => [
        'de' => 'Workflow Orchestrator',
        'en' => 'Workflow Orchestrator',
      ],
      'aliases' => [
        'Airflow',
        'Dagster',
        'Prefect',
        'Orchestrator',
      ],
      'definition' => [
        'de' => 'System für Scheduling, Dependencies und Retries von Data Jobs — nicht dasselbe wie Transformation (dbt).',
        'en' => 'System for scheduling, dependencies, and retries of data jobs — not the same as transformation (dbt).',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'orchestration',
          'label' => [
            'de' => 'Orchestration',
            'en' => 'Orchestration',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'idempotent',
          'label' => [
            'de' => 'Idempotent Load',
            'en' => 'Idempotent Load',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operating-and-governing-the-platform',
          'label' => [
            'de' => 'Plattform betreiben und steuern',
            'en' => 'Operating and governing the platform',
          ],
        ],
      ],
    ],
    [
      'id' => 'ingestion-tool',
      'order' => 417,
      'category' => 'architecture',
      'term' => [
        'de' => 'Ingestion Tool',
        'en' => 'Ingestion Tool',
      ],
      'aliases' => [
        'Fivetran',
        'Airbyte',
        'ELT Ingestion',
        'Ingest Connector',
      ],
      'definition' => [
        'de' => 'Connector-/ELT-Werkzeug zum Laden aus SaaS/DBs in Landing — ersetzt weder Modellierung noch Contracts.',
        'en' => 'Connector/ELT tool loading from SaaS/DBs into landing — replaces neither modeling nor contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'elt',
          'label' => [
            'de' => 'ELT',
            'en' => 'ELT',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cdc',
          'label' => [
            'de' => 'CDC (Change Data Capture)',
            'en' => 'CDC (Change Data Capture)',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'choosing-the-simplest-viable-architecture',
          'label' => [
            'de' => 'Einfachst sinnvolle Architektur',
            'en' => 'Simplest viable architecture',
          ],
        ],
      ],
    ],
    [
      'id' => 'additive',
      'order' => 551,
      'category' => 'modeling',
      'term' => [
        'de' => 'Additive Measure',
        'en' => 'Additive Measure',
      ],
      'aliases' => [
        'Additive Fact',
        'Fully Additive',
      ],
      'definition' => [
        'de' => 'Measure, die über alle Dimensionen summierbar ist (z. B. Umsatz) — Grundlage sauberer Aggregationen.',
        'en' => 'Measure that can be summed across all dimensions (e.g. revenue) — foundation of clean aggregations.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semi-additive',
          'label' => [
            'de' => 'Semi-Additive Measure',
            'en' => 'Semi-Additive Measure',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'non-additive',
          'label' => [
            'de' => 'Non-Additive Measure',
            'en' => 'Non-Additive Measure',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
      ],
    ],
    [
      'id' => 'semi-additive',
      'order' => 553,
      'category' => 'modeling',
      'term' => [
        'de' => 'Semi-Additive Measure',
        'en' => 'Semi-Additive Measure',
      ],
      'aliases' => [
        'Semi Additive',
        'Balance Measure',
      ],
      'definition' => [
        'de' => 'Über manche Dimensionen summierbar, über Zeit oft nicht (Bestand) — braucht Snapshot-Logik.',
        'en' => 'Summable across some dimensions, often not across time (balances) — needs snapshot logic.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'additive',
          'label' => [
            'de' => 'Additive Measure',
            'en' => 'Additive Measure',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'periodic-snapshot',
          'label' => [
            'de' => 'Periodic Snapshot Fact',
            'en' => 'Periodic Snapshot Fact',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'time-intelligence',
          'label' => [
            'de' => 'Time Intelligence',
            'en' => 'Time Intelligence',
          ],
        ],
      ],
    ],
    [
      'id' => 'non-additive',
      'order' => 554,
      'category' => 'modeling',
      'term' => [
        'de' => 'Non-Additive Measure',
        'en' => 'Non-Additive Measure',
      ],
      'aliases' => [
        'Non Additive',
        'Ratio Measure',
      ],
      'definition' => [
        'de' => 'Nicht sinnvoll summierbar (Ratios, Distinct Counts) — Aggregation muss neu berechnet werden.',
        'en' => 'Not meaningfully summable (ratios, distinct counts) — aggregation must be recalculated.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'additive',
          'label' => [
            'de' => 'Additive Measure',
            'en' => 'Additive Measure',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'master-measure',
          'label' => [
            'de' => 'Master Measure',
            'en' => 'Master Measure',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
      ],
    ],
    [
      'id' => 'transaction-fact',
      'order' => 555,
      'category' => 'modeling',
      'term' => [
        'de' => 'Transaction Fact',
        'en' => 'Transaction Fact',
      ],
      'aliases' => [
        'Transactional Fact',
        'Event Fact',
      ],
      'definition' => [
        'de' => 'Fact pro Geschäftsereignis (Order Line) — feines Grain, additive Measures.',
        'en' => 'Fact per business event (order line) — fine grain, additive measures.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'periodic-snapshot',
          'label' => [
            'de' => 'Periodic Snapshot Fact',
            'en' => 'Periodic Snapshot Fact',
          ],
        ],
      ],
    ],
    [
      'id' => 'periodic-snapshot',
      'order' => 557,
      'category' => 'modeling',
      'term' => [
        'de' => 'Periodic Snapshot Fact',
        'en' => 'Periodic Snapshot Fact',
      ],
      'aliases' => [
        'Snapshot Fact',
        'Daily Snapshot',
      ],
      'definition' => [
        'de' => 'Fact mit regelmäßigem Statusbild (Tag/Monat) — typisch für Bestände und semi-additive Measures.',
        'en' => 'Fact with a regular status picture (day/month) — typical for balances and semi-additive measures.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'semi-additive',
          'label' => [
            'de' => 'Semi-Additive Measure',
            'en' => 'Semi-Additive Measure',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'transaction-fact',
          'label' => [
            'de' => 'Transaction Fact',
            'en' => 'Transaction Fact',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'accumulating-snapshot',
          'label' => [
            'de' => 'Accumulating Snapshot Fact',
            'en' => 'Accumulating Snapshot Fact',
          ],
        ],
      ],
    ],
    [
      'id' => 'accumulating-snapshot',
      'order' => 558,
      'category' => 'modeling',
      'term' => [
        'de' => 'Accumulating Snapshot Fact',
        'en' => 'Accumulating Snapshot Fact',
      ],
      'aliases' => [
        'Pipeline Fact',
        'Lifecycle Fact',
      ],
      'definition' => [
        'de' => 'Fact, der einen Prozess über Meilensteine fortschreibt (Antrag → Freigabe → Close) — Zeilen werden aktualisiert.',
        'en' => 'Fact that progresses a process across milestones (apply → approve → close) — rows are updated.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'transaction-fact',
          'label' => [
            'de' => 'Transaction Fact',
            'en' => 'Transaction Fact',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'periodic-snapshot',
          'label' => [
            'de' => 'Periodic Snapshot Fact',
            'en' => 'Periodic Snapshot Fact',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
      ],
    ],
    [
      'id' => 'kimball',
      'order' => 559,
      'category' => 'modeling',
      'term' => [
        'de' => 'Kimball',
        'en' => 'Kimball',
      ],
      'aliases' => [
        'Kimball Method',
        'Dimensional Kimball',
      ],
      'definition' => [
        'de' => 'Dimensionaler Ansatz mit Bus Matrix und Conformed Dimensions — Bottom-up Marts ums Business Process.',
        'en' => 'Dimensional approach with bus matrix and conformed dimensions — bottom-up marts around business process.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'bus-matrix',
          'label' => [
            'de' => 'Bus Matrix',
            'en' => 'Bus Matrix',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'inmon',
          'label' => [
            'de' => 'Inmon',
            'en' => 'Inmon',
          ],
        ],
      ],
    ],
    [
      'id' => 'inmon',
      'order' => 561,
      'category' => 'modeling',
      'term' => [
        'de' => 'Inmon',
        'en' => 'Inmon',
      ],
      'aliases' => [
        'CIF',
        'Corporate Information Factory',
        'Normalized EDW',
      ],
      'definition' => [
        'de' => 'Top-down Enterprise DW mit normalisiertem Core und abhängigen Marts — Kontrast zum Kimball-Bus.',
        'en' => 'Top-down enterprise DW with normalized core and dependent marts — contrast to the Kimball bus.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'kimball',
          'label' => [
            'de' => 'Kimball',
            'en' => 'Kimball',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'modern-data-warehouse',
          'label' => [
            'de' => 'Modern Data Warehouse',
            'en' => 'Modern Data Warehouse',
          ],
        ],
      ],
    ],
    [
      'id' => 'canonical-model',
      'order' => 562,
      'category' => 'modeling',
      'term' => [
        'de' => 'Canonical Model',
        'en' => 'Canonical Model',
      ],
      'aliases' => [
        'Canonical Data Model',
        'Kanonisches Modell',
      ],
      'definition' => [
        'de' => 'Gemeinsames Integrationsmodell über Systeme hinweg — reduziert Point-to-Point, braucht starke Ownership.',
        'en' => 'Shared integration model across systems — reduces point-to-point, needs strong ownership.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'conformed-dimension',
          'label' => [
            'de' => 'Conformed Dimension',
            'en' => 'Conformed Dimension',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'enterprise-vocabulary',
          'label' => [
            'de' => 'Enterprise Vocabulary',
            'en' => 'Enterprise Vocabulary',
          ],
        ],
      ],
    ],
    [
      'id' => 'many-to-many',
      'order' => 563,
      'category' => 'modeling',
      'term' => [
        'de' => 'Many-to-Many',
        'en' => 'Many-to-Many',
      ],
      'aliases' => [
        'M:N',
        'Many to Many Relationship',
      ],
      'definition' => [
        'de' => 'Beziehung, in der beide Seiten mehrere Partner haben — in Dimensional Models oft über Bridge Tables.',
        'en' => 'Relationship where both sides have multiple partners — in dimensional models often via bridge tables.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'bridge-table',
          'label' => [
            'de' => 'Bridge Table',
            'en' => 'Bridge Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cardinality',
          'label' => [
            'de' => 'Cardinality',
            'en' => 'Cardinality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
      ],
    ],
    [
      'id' => 'cardinality',
      'order' => 565,
      'category' => 'modeling',
      'term' => [
        'de' => 'Cardinality',
        'en' => 'Cardinality',
      ],
      'aliases' => [
        'Relationship Cardinality',
        'Kardinalität',
      ],
      'definition' => [
        'de' => 'Wieviele Partner eine Beziehung hat (1:1, 1:n, n:m) — steuert Joins, Grain und BI-Filterpfade.',
        'en' => 'How many partners a relationship has (1:1, 1:n, n:m) — drives joins, grain, and BI filter paths.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'many-to-many',
          'label' => [
            'de' => 'Many-to-Many',
            'en' => 'Many-to-Many',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ambiguous-path',
          'label' => [
            'de' => 'Ambiguous Path',
            'en' => 'Ambiguous Path',
          ],
        ],
      ],
    ],
    [
      'id' => 'vanity-metric',
      'order' => 605,
      'category' => 'bi',
      'term' => [
        'de' => 'Vanity Metric',
        'en' => 'Vanity Metric',
      ],
      'aliases' => [
        'Eitelkeitskennzahl',
        'Feel-Good Metric',
      ],
      'definition' => [
        'de' => 'Kennzahl, die gut aussieht, aber keine Entscheidung ändert — Symptom fehlender KPI Contracts.',
        'en' => 'Metric that looks good but changes no decision — symptom of missing KPI contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'north-star-metric',
          'label' => [
            'de' => 'North Star Metric',
            'en' => 'North Star Metric',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-contract',
          'label' => [
            'de' => 'KPI Contract',
            'en' => 'KPI Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted Metrics',
          ],
        ],
      ],
    ],
    [
      'id' => 'north-star-metric',
      'order' => 606,
      'category' => 'bi',
      'term' => [
        'de' => 'North Star Metric',
        'en' => 'North Star Metric',
      ],
      'aliases' => [
        'NSM',
        'Leitkennzahl',
      ],
      'definition' => [
        'de' => 'Eine zentrale Outcome-Kennzahl für Produkt/Organisation — braucht Grain, Owner und Supporting Metrics.',
        'en' => 'One central outcome metric for product/org — needs grain, owner, and supporting metrics.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'vanity-metric',
          'label' => [
            'de' => 'Vanity Metric',
            'en' => 'Vanity Metric',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-governance',
          'label' => [
            'de' => 'KPI Governance',
            'en' => 'KPI Governance',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'define-kpi',
          'label' => [
            'de' => 'KPI definieren',
            'en' => 'Define a KPI',
          ],
        ],
      ],
    ],
    [
      'id' => 'leading-indicator',
      'order' => 607,
      'category' => 'bi',
      'term' => [
        'de' => 'Leading Indicator',
        'en' => 'Leading Indicator',
      ],
      'aliases' => [
        'Frühindikator',
        'Predictive KPI',
      ],
      'definition' => [
        'de' => 'Kennzahl, die Outcomes vorhersagt (Pipeline, Activation) — ergänzt Lagging Indicators.',
        'en' => 'Metric that predicts outcomes (pipeline, activation) — complements lagging indicators.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lagging-indicator',
          'label' => [
            'de' => 'Lagging Indicator',
            'en' => 'Lagging Indicator',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-contract',
          'label' => [
            'de' => 'KPI Contract',
            'en' => 'KPI Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted Metrics',
          ],
        ],
      ],
    ],
    [
      'id' => 'lagging-indicator',
      'order' => 608,
      'category' => 'bi',
      'term' => [
        'de' => 'Lagging Indicator',
        'en' => 'Lagging Indicator',
      ],
      'aliases' => [
        'Spätindikator',
        'Outcome KPI',
      ],
      'definition' => [
        'de' => 'Kennzahl, die Ergebnisse nachträglich misst (Revenue, Churn) — wichtig, aber allein zu spät für Steuerung.',
        'en' => 'Metric that measures outcomes after the fact (revenue, churn) — important, but alone too late to steer.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'leading-indicator',
          'label' => [
            'de' => 'Leading Indicator',
            'en' => 'Leading Indicator',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'north-star-metric',
          'label' => [
            'de' => 'North Star Metric',
            'en' => 'North Star Metric',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-governance',
          'label' => [
            'de' => 'KPI Governance',
            'en' => 'KPI Governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'metric-proliferation',
      'order' => 609,
      'category' => 'bi',
      'term' => [
        'de' => 'Metric Proliferation',
        'en' => 'Metric Proliferation',
      ],
      'aliases' => [
        'KPI Sprawl',
        'Kennzahlen-Wildwuchs',
      ],
      'definition' => [
        'de' => 'Unkontrolliertes Wachstum ähnlicher Measures — Trust sinkt; Certification und Deprecation helfen.',
        'en' => 'Uncontrolled growth of similar measures — trust drops; certification and deprecation help.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'master-measure',
          'label' => [
            'de' => 'Master Measure',
            'en' => 'Master Measure',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'deprecation',
          'label' => [
            'de' => 'Deprecation',
            'en' => 'Deprecation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'shadow-it',
          'label' => [
            'de' => 'Shadow IT',
            'en' => 'Shadow IT',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'missing-pieces-trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics (Missing Pieces)',
            'en' => 'Trusted metrics (missing pieces)',
          ],
        ],
      ],
    ],
    [
      'id' => 'dashboard-sprawl',
      'order' => 611,
      'category' => 'bi',
      'term' => [
        'de' => 'Dashboard Sprawl',
        'en' => 'Dashboard Sprawl',
      ],
      'aliases' => [
        'Report Sprawl',
        'Dashboard Wildwuchs',
      ],
      'definition' => [
        'de' => 'Zu viele ähnliche Dashboards ohne Owner und Dataset-Vertrag — klassisches Symptom von Self-Service ohne Leitplanken.',
        'en' => 'Too many similar dashboards without owner and dataset contract — classic symptom of self-service without guardrails.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'governed-self-service',
          'label' => [
            'de' => 'Governed Self-Service',
            'en' => 'Governed Self-Service',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'certified-dataset',
          'label' => [
            'de' => 'Certified Dataset',
            'en' => 'Certified Dataset',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metric-proliferation',
          'label' => [
            'de' => 'Metric Proliferation',
            'en' => 'Metric Proliferation',
          ],
        ],
      ],
    ],
    [
      'id' => 'drill-down',
      'order' => 612,
      'category' => 'bi',
      'term' => [
        'de' => 'Drill-Down',
        'en' => 'Drill-Down',
      ],
      'aliases' => [
        'Drill Down',
        'Hierarchie-Drill',
      ],
      'definition' => [
        'de' => 'Von grober zu feiner Hierarchiestufe navigieren — setzt Conformed Hierarchies und klares Grain voraus.',
        'en' => 'Navigate from coarse to fine hierarchy level — needs conformed hierarchies and clear grain.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'drill-through',
          'label' => [
            'de' => 'Drill-Through',
            'en' => 'Drill-Through',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
      ],
    ],
    [
      'id' => 'drill-through',
      'order' => 613,
      'category' => 'bi',
      'term' => [
        'de' => 'Drill-Through',
        'en' => 'Drill-Through',
      ],
      'aliases' => [
        'Drill Through',
        'Detail Jump',
      ],
      'definition' => [
        'de' => 'Sprung von Aggregat zu Detailzeilen oder anderem Report — Lineage und Rechte müssen mitwandern.',
        'en' => 'Jump from aggregate to detail rows or another report — lineage and entitlements must travel with it.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'drill-down',
          'label' => [
            'de' => 'Drill-Down',
            'en' => 'Drill-Down',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'row-level-security',
          'label' => [
            'de' => 'Row-Level Security (RLS)',
            'en' => 'Row-Level Security (RLS)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metric-lineage',
          'label' => [
            'de' => 'Metric Lineage',
            'en' => 'Metric Lineage',
          ],
        ],
      ],
    ],
    [
      'id' => 'ambiguous-path',
      'order' => 614,
      'category' => 'bi',
      'term' => [
        'de' => 'Ambiguous Path',
        'en' => 'Ambiguous Path',
      ],
      'aliases' => [
        'Ambiguous Relationship',
        'Mehrdeutiger Beziehungspfad',
      ],
      'definition' => [
        'de' => 'Mehrere Filterpfade zwischen Tabellen im Semantic Model — führt zu falschen Aggregaten, wenn nicht aufgelöst.',
        'en' => 'Multiple filter paths between tables in the semantic model — yields wrong aggregates if unresolved.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'cardinality',
          'label' => [
            'de' => 'Cardinality',
            'en' => 'Cardinality',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'filter-context',
          'label' => [
            'de' => 'Filter Context',
            'en' => 'Filter Context',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'bi-tools',
          'label' => [
            'de' => 'BI-Tools',
            'en' => 'BI tools',
          ],
        ],
      ],
    ],
    [
      'id' => 'bidirectional-filter',
      'order' => 615,
      'category' => 'bi',
      'term' => [
        'de' => 'Bidirectional Filter',
        'en' => 'Bidirectional Filter',
      ],
      'aliases' => [
        'Both Directions Filter',
        'Cross Filter Both',
      ],
      'definition' => [
        'de' => 'Filter fließen in beide Richtungen einer Relationship — mächtig und riskant für Ambiguous Paths.',
        'en' => 'Filters flow both ways on a relationship — powerful and risky for ambiguous paths.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'ambiguous-path',
          'label' => [
            'de' => 'Ambiguous Path',
            'en' => 'Ambiguous Path',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'filter-context',
          'label' => [
            'de' => 'Filter Context',
            'en' => 'Filter Context',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'tabular-model',
          'label' => [
            'de' => 'Tabular Model',
            'en' => 'Tabular Model',
          ],
        ],
      ],
    ],
    [
      'id' => 'operational-reporting',
      'order' => 616,
      'category' => 'bi',
      'term' => [
        'de' => 'Operational Reporting',
        'en' => 'Operational Reporting',
      ],
      'aliases' => [
        'Ops Reporting',
        'Operatives Reporting',
      ],
      'definition' => [
        'de' => 'Tagesaktuelles Reporting nahe am Prozess — oft andere Latency und Grain als analytische Marts.',
        'en' => 'Day-to-day reporting close to the process — often different latency and grain than analytical marts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'analytical-reporting',
          'label' => [
            'de' => 'Analytical Reporting',
            'en' => 'Analytical Reporting',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'near-real-time',
          'label' => [
            'de' => 'Near Real-Time',
            'en' => 'Near Real-Time',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ods',
          'label' => [
            'de' => 'ODS',
            'en' => 'ODS',
          ],
        ],
      ],
    ],
    [
      'id' => 'analytical-reporting',
      'order' => 617,
      'category' => 'bi',
      'term' => [
        'de' => 'Analytical Reporting',
        'en' => 'Analytical Reporting',
      ],
      'aliases' => [
        'Analytics Reporting',
        'Analytisches Reporting',
      ],
      'definition' => [
        'de' => 'Auswertung über integrierte, historisierte Modelle — typisch Mart/Semantic Layer statt Roh-ODS.',
        'en' => 'Analysis over integrated, historized models — typically mart/semantic layer rather than raw ODS.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'operational-reporting',
          'label' => [
            'de' => 'Operational Reporting',
            'en' => 'Operational Reporting',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
      ],
    ],
    [
      'id' => 'consent',
      'order' => 921,
      'category' => 'privacy',
      'term' => [
        'de' => 'Consent',
        'en' => 'Consent',
      ],
      'aliases' => [
        'Einwilligung',
        'Opt-In',
      ],
      'definition' => [
        'de' => 'Freiwillige, informierte Einwilligung zur Verarbeitung — eine von mehreren Rechtsgrundlagen, nicht die einzige.',
        'en' => 'Freely given, informed permission to process — one lawful basis among others, not the only one.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lawful-basis',
          'label' => [
            'de' => 'Lawful Basis',
            'en' => 'Lawful Basis',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'purpose-limitation',
          'label' => [
            'de' => 'Purpose Limitation',
            'en' => 'Purpose Limitation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'gdpr',
          'label' => [
            'de' => 'GDPR',
            'en' => 'GDPR',
          ],
        ],
      ],
    ],
    [
      'id' => 'lawful-basis',
      'order' => 922,
      'category' => 'privacy',
      'term' => [
        'de' => 'Lawful Basis',
        'en' => 'Lawful Basis',
      ],
      'aliases' => [
        'Rechtsgrundlage',
        'Legal Basis',
      ],
      'definition' => [
        'de' => 'Rechtliche Grundlage der Verarbeitung (Consent, Vertrag, berechtigtes Interesse …) — muss zu Purpose passen.',
        'en' => 'Legal ground for processing (consent, contract, legitimate interest…) — must fit the purpose.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'consent',
          'label' => [
            'de' => 'Consent',
            'en' => 'Consent',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'purpose-limitation',
          'label' => [
            'de' => 'Purpose Limitation',
            'en' => 'Purpose Limitation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'gdpr',
          'label' => [
            'de' => 'GDPR',
            'en' => 'GDPR',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'pii-privacy-governance',
          'label' => [
            'de' => 'PII- & Privacy-Governance',
            'en' => 'PII & privacy governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'controller',
      'order' => 923,
      'category' => 'privacy',
      'term' => [
        'de' => 'Controller',
        'en' => 'Controller',
      ],
      'aliases' => [
        'Data Controller',
        'Verantwortlicher',
      ],
      'definition' => [
        'de' => 'Stelle, die Zwecke und Mittel der Verarbeitung festlegt — trägt Accountability gegenüber Betroffenen.',
        'en' => 'Party that determines purposes and means of processing — carries accountability to data subjects.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'processor',
          'label' => [
            'de' => 'Processor',
            'en' => 'Processor',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'accountability',
          'label' => [
            'de' => 'Accountability',
            'en' => 'Accountability',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'gdpr',
          'label' => [
            'de' => 'GDPR',
            'en' => 'GDPR',
          ],
        ],
      ],
    ],
    [
      'id' => 'processor',
      'order' => 924,
      'category' => 'privacy',
      'term' => [
        'de' => 'Processor',
        'en' => 'Processor',
      ],
      'aliases' => [
        'Data Processor',
        'Auftragsverarbeiter',
      ],
      'definition' => [
        'de' => 'Verarbeitet personenbezogene Daten im Auftrag des Controllers — braucht Vertrag und nachgewiesene Controls.',
        'en' => 'Processes personal data on behalf of the controller — needs a contract and evidenced controls.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'controller',
          'label' => [
            'de' => 'Controller',
            'en' => 'Controller',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'gdpr',
          'label' => [
            'de' => 'GDPR',
            'en' => 'GDPR',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dsdr',
          'label' => [
            'de' => 'DSDR',
            'en' => 'DSDR',
          ],
        ],
      ],
    ],
    [
      'id' => 'special-category',
      'order' => 925,
      'category' => 'privacy',
      'term' => [
        'de' => 'Special Category Data',
        'en' => 'Special Category Data',
      ],
      'aliases' => [
        'Sensitive Personal Data',
        'besondere Kategorien',
        'Art. 9',
      ],
      'definition' => [
        'de' => 'Besonders schützenswerte Daten (Gesundheit, Biometrie …) — strengere Voraussetzungen als normales PII.',
        'en' => 'Especially protected data (health, biometrics…) — stricter requirements than ordinary PII.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-classification',
          'label' => [
            'de' => 'Data Classification',
            'en' => 'Data Classification',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'gdpr',
          'label' => [
            'de' => 'GDPR',
            'en' => 'GDPR',
          ],
        ],
      ],
    ],
    [
      'id' => 'audit-log',
      'order' => 1079,
      'category' => 'security',
      'term' => [
        'de' => 'Audit Log',
        'en' => 'Audit Log',
      ],
      'aliases' => [
        'Access Log',
        'Audit Trail',
        'Prüfprotokoll',
      ],
      'definition' => [
        'de' => 'Nachweis wer wann worauf zugegriffen oder geändert hat — Basis für Recertification und Incidents.',
        'en' => 'Evidence of who accessed or changed what when — basis for recertification and incidents.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'access-recertification',
          'label' => [
            'de' => 'Access Recertification',
            'en' => 'Access Recertification',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'iam',
          'label' => [
            'de' => 'IAM',
            'en' => 'IAM',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => [
            'de' => 'Zugriffs- & Security-Governance',
            'en' => 'Access & security governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'sso',
      'order' => 1080,
      'category' => 'security',
      'term' => [
        'de' => 'SSO',
        'en' => 'SSO',
      ],
      'aliases' => [
        'Single Sign-On',
        'Federated Login',
      ],
      'definition' => [
        'de' => 'Ein Login für viele Apps — vereinfacht IAM, zentralisiert aber auch das Risiko.',
        'en' => 'One login for many apps — simplifies IAM, but also centralizes risk.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'iam',
          'label' => [
            'de' => 'IAM',
            'en' => 'IAM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mfa',
          'label' => [
            'de' => 'MFA',
            'en' => 'MFA',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scim',
          'label' => [
            'de' => 'SCIM',
            'en' => 'SCIM',
          ],
        ],
      ],
    ],
    [
      'id' => 'mfa',
      'order' => 1081,
      'category' => 'security',
      'term' => [
        'de' => 'MFA',
        'en' => 'MFA',
      ],
      'aliases' => [
        'Multi-Factor Authentication',
        '2FA',
        'Mehrfaktor',
      ],
      'definition' => [
        'de' => 'Mehrere Authentisierungsfaktoren — Mindeststandard für privilegierte und PII-Zugriffe.',
        'en' => 'Multiple authentication factors — baseline for privileged and PII access.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'sso',
          'label' => [
            'de' => 'SSO',
            'en' => 'SSO',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'iam',
          'label' => [
            'de' => 'IAM',
            'en' => 'IAM',
          ],
        ],
      ],
    ],
    [
      'id' => 'scim',
      'order' => 1082,
      'category' => 'security',
      'term' => [
        'de' => 'SCIM',
        'en' => 'SCIM',
      ],
      'aliases' => [
        'System for Cross-domain Identity Management',
        'User Provisioning',
      ],
      'definition' => [
        'de' => 'Standard für automatisches Provisioning/Deprovisioning von Identitäten in Apps.',
        'en' => 'Standard for automated provisioning/deprovisioning of identities into apps.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'iam',
          'label' => [
            'de' => 'IAM',
            'en' => 'IAM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'sso',
          'label' => [
            'de' => 'SSO',
            'en' => 'SSO',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'access-recertification',
          'label' => [
            'de' => 'Access Recertification',
            'en' => 'Access Recertification',
          ],
        ],
      ],
    ],
    [
      'id' => 'adr',
      'order' => 1417,
      'category' => 'process',
      'term' => [
        'de' => 'ADR',
        'en' => 'ADR',
      ],
      'aliases' => [
        'Architecture Decision Record',
        'Architekturentscheidung',
      ],
      'definition' => [
        'de' => 'Kurzer Datensatz einer Architekturentscheidung inkl. Kontext und Konsequenzen — verhindert Tribal Knowledge.',
        'en' => 'Short record of an architecture decision including context and consequences — prevents tribal knowledge.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'logical-architecture',
          'label' => [
            'de' => 'Logical Architecture',
            'en' => 'Logical Architecture',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-architect',
          'label' => [
            'de' => 'Data Architect',
            'en' => 'Data Architect',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'data-architect-role',
          'label' => [
            'de' => 'Die Rolle Data Architect',
            'en' => 'The data architect role',
          ],
        ],
      ],
    ],
    [
      'id' => 'tribal-knowledge',
      'order' => 1418,
      'category' => 'process',
      'term' => [
        'de' => 'Tribal Knowledge',
        'en' => 'Tribal Knowledge',
      ],
      'aliases' => [
        'Bus Factor Knowledge',
        'Kopfwissen',
        'Hero Knowledge',
      ],
      'definition' => [
        'de' => 'Kritisches Wissen nur in Köpfen Weniger — Bus Factor Risiko; Metadaten und ADRs sind Gegenmittel.',
        'en' => 'Critical knowledge only in a few heads — bus-factor risk; metadata and ADRs are antidotes.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'adr',
          'label' => [
            'de' => 'ADR',
            'en' => 'ADR',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'business-metadata',
          'label' => [
            'de' => 'Business Metadata',
            'en' => 'Business Metadata',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operate-metadata-as-a-product',
          'label' => [
            'de' => 'Metadaten als Produkt betreiben',
            'en' => 'Operate metadata as a product',
          ],
        ],
      ],
    ],
    [
      'id' => 'two-version-problem',
      'order' => 1419,
      'category' => 'process',
      'term' => [
        'de' => 'Two-Version Problem',
        'en' => 'Two-Version Problem',
      ],
      'aliases' => [
        'Multiple Sources of Truth',
        'Zwei Wahrheiten',
      ],
      'definition' => [
        'de' => 'Zwei „offizielle“ Zahlen für dieselbe Frage — typisch ohne Metric Store und Decision Rights.',
        'en' => 'Two “official” numbers for the same question — typical without a metric store and decision rights.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'single-source-of-truth',
          'label' => [
            'de' => 'Single Source of Truth',
            'en' => 'Single Source of Truth',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted Metrics',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'decision-rights',
          'label' => [
            'de' => 'Decision Rights',
            'en' => 'Decision Rights',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'missing-pieces-trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics (Missing Pieces)',
            'en' => 'Trusted metrics (missing pieces)',
          ],
        ],
      ],
    ],
    [
      'id' => 'protobuf',
      'order' => 418,
      'category' => 'architecture',
      'term' => [
        'de' => 'Protocol Buffers',
        'en' => 'Protocol Buffers',
      ],
      'aliases' => [
        'Protobuf',
        'Proto',
        'gRPC Schema',
      ],
      'definition' => [
        'de' => 'Kompaktes Binary-Schema-Format — häufig in APIs und Event-Streams mit strikter Evolution.',
        'en' => 'Compact binary schema format — common in APIs and event streams with strict evolution.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'avro',
          'label' => [
            'de' => 'Avro',
            'en' => 'Avro',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'schema-registry',
          'label' => [
            'de' => 'Schema Registry',
            'en' => 'Schema Registry',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'schema-evolution',
          'label' => [
            'de' => 'Schema Evolution',
            'en' => 'Schema Evolution',
          ],
        ],
      ],
    ],
    [
      'id' => 'orc',
      'order' => 419,
      'category' => 'architecture',
      'term' => [
        'de' => 'ORC',
        'en' => 'ORC',
      ],
      'aliases' => [
        'Optimized Row Columnar',
        'Apache ORC',
      ],
      'definition' => [
        'de' => 'Spaltenformat für analytische Workloads — Alternative zu Parquet, stark in Hive-Ökosystemen.',
        'en' => 'Columnar format for analytical workloads — Parquet alternative, strong in Hive ecosystems.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'parquet',
          'label' => [
            'de' => 'Parquet',
            'en' => 'Parquet',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-lake',
          'label' => [
            'de' => 'Data Lake',
            'en' => 'Data Lake',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'predicate-pushdown',
          'label' => [
            'de' => 'Predicate Pushdown',
            'en' => 'Predicate Pushdown',
          ],
        ],
      ],
    ],
    [
      'id' => 'apache-arrow',
      'order' => 421,
      'category' => 'architecture',
      'term' => [
        'de' => 'Apache Arrow',
        'en' => 'Apache Arrow',
      ],
      'aliases' => [
        'Arrow',
        'Arrow Flight',
      ],
      'definition' => [
        'de' => 'In-Memory-Säulenformat für schnellen Austausch zwischen Engines — Bridge zwischen Spark, DuckDB, BI.',
        'en' => 'In-memory columnar format for fast exchange between engines — bridge across Spark, DuckDB, BI.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'duckdb',
          'label' => [
            'de' => 'DuckDB',
            'en' => 'DuckDB',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'spark',
          'label' => [
            'de' => 'Apache Spark',
            'en' => 'Apache Spark',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'parquet',
          'label' => [
            'de' => 'Parquet',
            'en' => 'Parquet',
          ],
        ],
      ],
    ],
    [
      'id' => 'duckdb',
      'order' => 422,
      'category' => 'architecture',
      'term' => [
        'de' => 'DuckDB',
        'en' => 'DuckDB',
      ],
      'aliases' => [
        'Duck DB',
        'Embedded Analytics DB',
      ],
      'definition' => [
        'de' => 'Eingebettete analytische DB für lokale/edge SQL-Workloads — gut für Exploration, nicht automatisch Enterprise Hub.',
        'en' => 'Embedded analytical DB for local/edge SQL workloads — great for exploration, not automatically an enterprise hub.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'apache-arrow',
          'label' => [
            'de' => 'Apache Arrow',
            'en' => 'Apache Arrow',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'sql-warehouse',
          'label' => [
            'de' => 'SQL Warehouse',
            'en' => 'SQL Warehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'parquet',
          'label' => [
            'de' => 'Parquet',
            'en' => 'Parquet',
          ],
        ],
      ],
    ],
    [
      'id' => 'log-based-cdc',
      'order' => 423,
      'category' => 'architecture',
      'term' => [
        'de' => 'Log-Based CDC',
        'en' => 'Log-Based CDC',
      ],
      'aliases' => [
        'Redo Log CDC',
        'Binlog CDC',
        'WAL CDC',
      ],
      'definition' => [
        'de' => 'Change Data Capture aus Datenbank-Logs — geringer Quell-Impact, braucht Rechte und Lag-Monitoring.',
        'en' => 'Change data capture from database logs — low source impact, needs privileges and lag monitoring.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'cdc',
          'label' => [
            'de' => 'CDC (Change Data Capture)',
            'en' => 'CDC (Change Data Capture)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'watermark',
          'label' => [
            'de' => 'Watermark',
            'en' => 'Watermark',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ingestion-tool',
          'label' => [
            'de' => 'Ingestion Tool',
            'en' => 'Ingestion Tool',
          ],
        ],
      ],
    ],
    [
      'id' => 'exactly-once',
      'order' => 424,
      'category' => 'architecture',
      'term' => [
        'de' => 'Exactly-Once',
        'en' => 'Exactly-Once',
      ],
      'aliases' => [
        'Exactly Once Delivery',
        'EOS',
      ],
      'definition' => [
        'de' => 'Verarbeitungsversprechen ohne Duplikate und ohne Verlust — teuer; oft reicht effektive Exactly-Once via Idempotenz.',
        'en' => 'Processing promise with no duplicates and no loss — expensive; often effective exactly-once via idempotency is enough.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'at-least-once',
          'label' => [
            'de' => 'At-Least-Once',
            'en' => 'At-Least-Once',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'idempotent',
          'label' => [
            'de' => 'Idempotent Load',
            'en' => 'Idempotent Load',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'streaming',
          'label' => [
            'de' => 'Streaming',
            'en' => 'Streaming',
          ],
        ],
      ],
    ],
    [
      'id' => 'at-least-once',
      'order' => 425,
      'category' => 'architecture',
      'term' => [
        'de' => 'At-Least-Once',
        'en' => 'At-Least-Once',
      ],
      'aliases' => [
        'At Least Once Delivery',
        'ALO',
      ],
      'definition' => [
        'de' => 'Jedes Event kommt mindestens einmal — Duplikate möglich; Downstream muss idempotent sein.',
        'en' => 'Every event arrives at least once — duplicates possible; downstream must be idempotent.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'exactly-once',
          'label' => [
            'de' => 'Exactly-Once',
            'en' => 'Exactly-Once',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'idempotent',
          'label' => [
            'de' => 'Idempotent Load',
            'en' => 'Idempotent Load',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'streaming',
          'label' => [
            'de' => 'Streaming',
            'en' => 'Streaming',
          ],
        ],
      ],
    ],
    [
      'id' => 'replay',
      'order' => 426,
      'category' => 'architecture',
      'term' => [
        'de' => 'Replay',
        'en' => 'Replay',
      ],
      'aliases' => [
        'Event Replay',
        'Stream Replay',
        'Neuabspielen',
      ],
      'definition' => [
        'de' => 'Erneutes Verarbeiten historischer Events/Loads — braucht Idempotenz und klare Watermarks.',
        'en' => 'Reprocessing historical events/loads — needs idempotency and clear watermarks.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'backfill',
          'label' => [
            'de' => 'Backfill',
            'en' => 'Backfill',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'idempotent',
          'label' => [
            'de' => 'Idempotent Load',
            'en' => 'Idempotent Load',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'streaming',
          'label' => [
            'de' => 'Streaming',
            'en' => 'Streaming',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-share',
      'order' => 427,
      'category' => 'architecture',
      'term' => [
        'de' => 'Data Share',
        'en' => 'Data Share',
      ],
      'aliases' => [
        'Delta Sharing',
        'Secure Data Share',
        'Datashare',
      ],
      'definition' => [
        'de' => 'Governter Austausch von Datasets über Grenzen hinweg — ohne unkontrollierte Exports.',
        'en' => 'Governed exchange of datasets across boundaries — without uncontrolled exports.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'consumption-contract',
          'label' => [
            'de' => 'Consumption Contract',
            'en' => 'Consumption Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'clean-room',
          'label' => [
            'de' => 'Clean Room',
            'en' => 'Clean Room',
          ],
        ],
      ],
    ],
    [
      'id' => 'clean-room',
      'order' => 428,
      'category' => 'architecture',
      'term' => [
        'de' => 'Clean Room',
        'en' => 'Clean Room',
      ],
      'aliases' => [
        'Data Clean Room',
        'Privacy Clean Room',
      ],
      'definition' => [
        'de' => 'Kontrollierte Umgebung für gemeinsame Analyse ohne Rohdaten-Austausch — Privacy by Design.',
        'en' => 'Controlled environment for joint analysis without raw data exchange — privacy by design.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-share',
          'label' => [
            'de' => 'Data Share',
            'en' => 'Data Share',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'differential-privacy',
          'label' => [
            'de' => 'Differential Privacy',
            'en' => 'Differential Privacy',
          ],
        ],
      ],
    ],
    [
      'id' => 'blue-green',
      'order' => 429,
      'category' => 'architecture',
      'term' => [
        'de' => 'Blue/Green Deployment',
        'en' => 'Blue/Green Deployment',
      ],
      'aliases' => [
        'Blue Green',
        'Blue-Green',
      ],
      'definition' => [
        'de' => 'Zwei parallele Environments; Switch erst nach Validierung — verwandt mit Parallel Run/Cutover.',
        'en' => 'Two parallel environments; switch only after validation — related to parallel run/cutover.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'parallel-run',
          'label' => [
            'de' => 'Parallel Run',
            'en' => 'Parallel Run',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cutover',
          'label' => [
            'de' => 'Cutover',
            'en' => 'Cutover',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'canary',
          'label' => [
            'de' => 'Canary Release',
            'en' => 'Canary Release',
          ],
        ],
      ],
    ],
    [
      'id' => 'canary',
      'order' => 431,
      'category' => 'architecture',
      'term' => [
        'de' => 'Canary Release',
        'en' => 'Canary Release',
      ],
      'aliases' => [
        'Canary Deploy',
        'Canary',
      ],
      'definition' => [
        'de' => 'Neues Release zuerst für einen kleinen Traffic-/User-Anteil — Risiken begrenzen vor Full Cutover.',
        'en' => 'New release first for a small traffic/user share — limit risk before full cutover.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'blue-green',
          'label' => [
            'de' => 'Blue/Green Deployment',
            'en' => 'Blue/Green Deployment',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'feature-flag',
          'label' => [
            'de' => 'Feature Flag',
            'en' => 'Feature Flag',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cutover',
          'label' => [
            'de' => 'Cutover',
            'en' => 'Cutover',
          ],
        ],
      ],
    ],
    [
      'id' => 'feature-flag',
      'order' => 432,
      'category' => 'architecture',
      'term' => [
        'de' => 'Feature Flag',
        'en' => 'Feature Flag',
      ],
      'aliases' => [
        'Feature Toggle',
        'Kill Switch',
      ],
      'definition' => [
        'de' => 'Runtime-Schalter für Features/Pipelines — entkoppelt Deploy von Release, braucht Governance.',
        'en' => 'Runtime switch for features/pipelines — decouples deploy from release, needs governance.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'canary',
          'label' => [
            'de' => 'Canary Release',
            'en' => 'Canary Release',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'platform-ops',
          'label' => [
            'de' => 'Platform Ops',
            'en' => 'Platform Ops',
          ],
        ],
      ],
    ],
    [
      'id' => 'outbox',
      'order' => 433,
      'category' => 'architecture',
      'term' => [
        'de' => 'Outbox Pattern',
        'en' => 'Outbox Pattern',
      ],
      'aliases' => [
        'Transactional Outbox',
        'Outbox',
      ],
      'definition' => [
        'de' => 'Events reliable aus derselben Transaction wie State-Change publizieren — gegen Message-Verlust.',
        'en' => 'Publish events reliably from the same transaction as the state change — against message loss.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'streaming',
          'label' => [
            'de' => 'Streaming',
            'en' => 'Streaming',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'idempotent',
          'label' => [
            'de' => 'Idempotent Load',
            'en' => 'Idempotent Load',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'exactly-once',
          'label' => [
            'de' => 'Exactly-Once',
            'en' => 'Exactly-Once',
          ],
        ],
      ],
    ],
    [
      'id' => 'infrastructure-as-code',
      'order' => 434,
      'category' => 'architecture',
      'term' => [
        'de' => 'Infrastructure as Code',
        'en' => 'Infrastructure as Code',
      ],
      'aliases' => [
        'IaC',
        'Terraform',
        'Bicep',
        'CloudFormation',
      ],
      'definition' => [
        'de' => 'Infra versioniert und reviewbar im Repo — Grundlage für reproduzierbare Data Platforms.',
        'en' => 'Infra versioned and reviewable in the repo — foundation for reproducible data platforms.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'gitops',
          'label' => [
            'de' => 'GitOps',
            'en' => 'GitOps',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'platform-engineering',
          'label' => [
            'de' => 'Platform Engineering',
            'en' => 'Platform Engineering',
          ],
        ],
      ],
    ],
    [
      'id' => 'gitops',
      'order' => 435,
      'category' => 'architecture',
      'term' => [
        'de' => 'GitOps',
        'en' => 'GitOps',
      ],
      'aliases' => [
        'Git Ops',
        'Declarative Delivery',
      ],
      'definition' => [
        'de' => 'Git als Source of Truth für Deploy-State — Pull-basiert, auditierbar, gut für Platform Standards.',
        'en' => 'Git as source of truth for deploy state — pull-based, auditable, good for platform standards.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'infrastructure-as-code',
          'label' => [
            'de' => 'Infrastructure as Code',
            'en' => 'Infrastructure as Code',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'golden-path',
          'label' => [
            'de' => 'Golden Path',
            'en' => 'Golden Path',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-mart',
      'order' => 221,
      'category' => 'data',
      'term' => [
        'de' => 'Data Mart',
        'en' => 'Data Mart',
      ],
      'aliases' => [
        'Mart',
        'Subject Mart',
        'Fachlicher Mart',
      ],
      'definition' => [
        'de' => 'Fachlich zugeschnittenes analytisches Dataset — oft die Business-Data-Product-Schicht.',
        'en' => 'Business-scoped analytical dataset — often the business data product layer.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'subject-area',
          'label' => [
            'de' => 'Subject Area',
            'en' => 'Subject Area',
          ],
        ],
      ],
    ],
    [
      'id' => 'dark-data',
      'order' => 222,
      'category' => 'data',
      'term' => [
        'de' => 'Dark Data',
        'en' => 'Dark Data',
      ],
      'aliases' => [
        'Unused Data',
        'Dunkle Daten',
      ],
      'definition' => [
        'de' => 'Gespeicherte, aber ungenutzte/ undokumentierte Daten — Kosten, Risiko und ROT-Kandidat.',
        'en' => 'Stored but unused/undocumented data — cost, risk, and ROT candidate.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rot',
          'label' => [
            'de' => 'ROT',
            'en' => 'ROT',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'usage-metadata',
          'label' => [
            'de' => 'Usage Metadata',
            'en' => 'Usage Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-lifecycle',
          'label' => [
            'de' => 'Data Lifecycle',
            'en' => 'Data Lifecycle',
          ],
        ],
      ],
    ],
    [
      'id' => 'rot',
      'order' => 223,
      'category' => 'data',
      'term' => [
        'de' => 'ROT',
        'en' => 'ROT',
      ],
      'aliases' => [
        'Redundant Obsolete Trivial',
        'ROT Data',
      ],
      'definition' => [
        'de' => 'Redundante, veraltete oder triviale Daten — Aufräumen spart Kosten und Privacy-Fläche.',
        'en' => 'Redundant, obsolete, or trivial data — cleanup saves cost and privacy surface.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dark-data',
          'label' => [
            'de' => 'Dark Data',
            'en' => 'Dark Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'retention',
          'label' => [
            'de' => 'Retention',
            'en' => 'Retention',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'deprecation',
          'label' => [
            'de' => 'Deprecation',
            'en' => 'Deprecation',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-strategy',
      'order' => 224,
      'category' => 'data',
      'term' => [
        'de' => 'Data Strategy',
        'en' => 'Data Strategy',
      ],
      'aliases' => [
        'Datenstrategie',
        'Analytics Strategy',
      ],
      'definition' => [
        'de' => 'Zielbild und Prioritäten für Datenprodukte, Fähigkeiten und Governance — nicht die Tool-Liste.',
        'en' => 'Target picture and priorities for data products, capabilities, and governance — not the tool list.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-roadmap',
          'label' => [
            'de' => 'Data Roadmap',
            'en' => 'Data Roadmap',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cdo',
          'label' => [
            'de' => 'CDO',
            'en' => 'CDO',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'simplest-viable-architecture',
          'label' => [
            'de' => 'Simplest Viable Architecture',
            'en' => 'Simplest Viable Architecture',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-roadmap',
      'order' => 225,
      'category' => 'data',
      'term' => [
        'de' => 'Data Roadmap',
        'en' => 'Data Roadmap',
      ],
      'aliases' => [
        'Analytics Roadmap',
        'Daten-Roadmap',
      ],
      'definition' => [
        'de' => 'Zeitliche Reihenfolge von Outcomes und Data Products — an Value Streams statt an Tools gekoppelt.',
        'en' => 'Timed sequence of outcomes and data products — tied to value streams, not tools.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-strategy',
          'label' => [
            'de' => 'Data Strategy',
            'en' => 'Data Strategy',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'value-stream',
          'label' => [
            'de' => 'Value Stream',
            'en' => 'Value Stream',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mvp',
          'label' => [
            'de' => 'MVP',
            'en' => 'MVP',
          ],
        ],
      ],
    ],
    [
      'id' => 'sparsity',
      'order' => 566,
      'category' => 'modeling',
      'term' => [
        'de' => 'Sparsity',
        'en' => 'Sparsity',
      ],
      'aliases' => [
        'Sparse Data',
        'Sparse Fact',
        'Sparsamkeit',
      ],
      'definition' => [
        'de' => 'Viele Kombinationen ohne Events — beeinflusst Storage, Aggregationen und BI-Performance.',
        'en' => 'Many combinations without events — affects storage, aggregations, and BI performance.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cardinality',
          'label' => [
            'de' => 'Cardinality',
            'en' => 'Cardinality',
          ],
        ],
      ],
    ],
    [
      'id' => 'outrigger-dimension',
      'order' => 567,
      'category' => 'modeling',
      'term' => [
        'de' => 'Outrigger Dimension',
        'en' => 'Outrigger Dimension',
      ],
      'aliases' => [
        'Outrigger',
        'Secondary Dimension',
      ],
      'definition' => [
        'de' => 'Dimension, die an einer anderen Dimension hängt statt direkt an der Fact — sparsam einsetzen.',
        'en' => 'Dimension hanging off another dimension instead of the fact — use sparingly.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'snowflake-schema',
          'label' => [
            'de' => 'Snowflake Schema',
            'en' => 'Snowflake Schema',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
      ],
    ],
    [
      'id' => 'mini-dimension',
      'order' => 569,
      'category' => 'modeling',
      'term' => [
        'de' => 'Mini-Dimension',
        'en' => 'Mini-Dimension',
      ],
      'aliases' => [
        'Mini Dimension',
        'Rapidly Changing Attributes',
      ],
      'definition' => [
        'de' => 'Kleine Dimension für schnell wechselnde Attribute — entlastet große SCD2-Dimensionen.',
        'en' => 'Small dimension for rapidly changing attributes — relieves large SCD2 dimensions.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'junk-dimension',
          'label' => [
            'de' => 'Junk Dimension',
            'en' => 'Junk Dimension',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
      ],
    ],
    [
      'id' => 'dashboard',
      'order' => 618,
      'category' => 'bi',
      'term' => [
        'de' => 'Dashboard',
        'en' => 'Dashboard',
      ],
      'aliases' => [
        'Cockpit',
        'Management Dashboard',
      ],
      'definition' => [
        'de' => 'Zusammenstellung von Visuals für Monitoring/Steuerung — sollte Thin Consumer auf Trusted Metrics sein.',
        'en' => 'Composition of visuals for monitoring/steering — should be a thin consumer on trusted metrics.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'thin-consumer-interface',
          'label' => [
            'de' => 'Thin Consumer Interface',
            'en' => 'Thin Consumer Interface',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scorecard',
          'label' => [
            'de' => 'Scorecard',
            'en' => 'Scorecard',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dashboard-sprawl',
          'label' => [
            'de' => 'Dashboard Sprawl',
            'en' => 'Dashboard Sprawl',
          ],
        ],
      ],
    ],
    [
      'id' => 'scorecard',
      'order' => 619,
      'category' => 'bi',
      'term' => [
        'de' => 'Scorecard',
        'en' => 'Scorecard',
      ],
      'aliases' => [
        'KPI Scorecard',
        'Balanced Scorecard',
      ],
      'definition' => [
        'de' => 'Kompakte KPI-Übersicht mit Targets/Status — braucht Contracts, sonst bunte Ampeln ohne Trust.',
        'en' => 'Compact KPI overview with targets/status — needs contracts, or you get colorful lights without trust.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dashboard',
          'label' => [
            'de' => 'Dashboard',
            'en' => 'Dashboard',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-contract',
          'label' => [
            'de' => 'KPI Contract',
            'en' => 'KPI Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted Metrics',
          ],
        ],
      ],
    ],
    [
      'id' => 'okr',
      'order' => 621,
      'category' => 'bi',
      'term' => [
        'de' => 'OKR',
        'en' => 'OKR',
      ],
      'aliases' => [
        'Objectives and Key Results',
        'Ziele und Key Results',
      ],
      'definition' => [
        'de' => 'Zielsystem aus Objectives und messbaren Key Results — Key Results sind oft KPIs mit Owner und Grain.',
        'en' => 'Goal system of objectives and measurable key results — key results are often KPIs with owner and grain.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'north-star-metric',
          'label' => [
            'de' => 'North Star Metric',
            'en' => 'North Star Metric',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-governance',
          'label' => [
            'de' => 'KPI Governance',
            'en' => 'KPI Governance',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'leading-indicator',
          'label' => [
            'de' => 'Leading Indicator',
            'en' => 'Leading Indicator',
          ],
        ],
      ],
    ],
    [
      'id' => 'cohort',
      'order' => 622,
      'category' => 'bi',
      'term' => [
        'de' => 'Cohort',
        'en' => 'Cohort',
      ],
      'aliases' => [
        'Kohorte',
        'Cohort Analysis',
      ],
      'definition' => [
        'de' => 'Gruppe mit gemeinsamem Startmerkmal (Signup-Monat) — Analysen brauchen stabiles Grain und Historie.',
        'en' => 'Group sharing a start trait (signup month) — analyses need stable grain and history.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'as-was-history',
          'label' => [
            'de' => 'As-Was History',
            'en' => 'As-Was History',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'analytical-reporting',
          'label' => [
            'de' => 'Analytical Reporting',
            'en' => 'Analytical Reporting',
          ],
        ],
      ],
    ],
    [
      'id' => 'attribution',
      'order' => 623,
      'category' => 'bi',
      'term' => [
        'de' => 'Attribution',
        'en' => 'Attribution',
      ],
      'aliases' => [
        'Marketing Attribution',
        'Credit Assignment',
      ],
      'definition' => [
        'de' => 'Zuordnung von Outcomes zu Touchpoints/Ursachen — stark modell- und definitionssensitiv.',
        'en' => 'Assigning outcomes to touchpoints/causes — highly sensitive to model and definition.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'kpi-contract',
          'label' => [
            'de' => 'KPI Contract',
            'en' => 'KPI Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metric-layers',
          'label' => [
            'de' => 'Metric Layers',
            'en' => 'Metric Layers',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'filter-context',
          'label' => [
            'de' => 'Filter Context',
            'en' => 'Filter Context',
          ],
        ],
      ],
    ],
    [
      'id' => 'workspace',
      'order' => 624,
      'category' => 'bi',
      'term' => [
        'de' => 'Workspace',
        'en' => 'Workspace',
      ],
      'aliases' => [
        'App Workspace',
        'BI Workspace',
        'Arbeitsbereich',
      ],
      'definition' => [
        'de' => 'Kollaborations- und Publish-Grenze in BI-Plattformen — Rechte, Promotion und Certification hängen daran.',
        'en' => 'Collaboration and publish boundary in BI platforms — entitlements, promotion, and certification hang off it.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'promotion',
          'label' => [
            'de' => 'Promotion',
            'en' => 'Promotion',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'certified-dataset',
          'label' => [
            'de' => 'Certified Dataset',
            'en' => 'Certified Dataset',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dataset',
          'label' => [
            'de' => 'Dataset',
            'en' => 'Dataset',
          ],
        ],
      ],
    ],
    [
      'id' => 'dataset',
      'order' => 625,
      'category' => 'bi',
      'term' => [
        'de' => 'Dataset',
        'en' => 'Dataset',
      ],
      'aliases' => [
        'Semantic Dataset',
        'Shared Dataset',
        'BI Dataset',
      ],
      'definition' => [
        'de' => 'Wiederverwendbares semantisches Modell/Paket für Reports — Idealpunkt für Certification und Contracts.',
        'en' => 'Reusable semantic model/package for reports — ideal point for certification and contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'certified-dataset',
          'label' => [
            'de' => 'Certified Dataset',
            'en' => 'Certified Dataset',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'workspace',
          'label' => [
            'de' => 'Workspace',
            'en' => 'Workspace',
          ],
        ],
      ],
    ],
    [
      'id' => 'promotion',
      'order' => 626,
      'category' => 'bi',
      'term' => [
        'de' => 'Promotion',
        'en' => 'Promotion',
      ],
      'aliases' => [
        'Content Promotion',
        'Dev to Prod Promote',
      ],
      'definition' => [
        'de' => 'Überführen von Content/Datasets von Dev/Test nach Prod — braucht Checks, Owner und Rollback.',
        'en' => 'Moving content/datasets from dev/test to prod — needs checks, owner, and rollback.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'workspace',
          'label' => [
            'de' => 'Workspace',
            'en' => 'Workspace',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'product-certification',
          'label' => [
            'de' => 'Data Product Certification',
            'en' => 'Data Product Certification',
          ],
        ],
      ],
    ],
    [
      'id' => 'excel-export',
      'order' => 627,
      'category' => 'bi',
      'term' => [
        'de' => 'Excel Export',
        'en' => 'Excel Export',
      ],
      'aliases' => [
        'CSV Export',
        'Spreadsheet Export',
        'Excel-Hölle',
      ],
      'definition' => [
        'de' => 'Export aus governed Surfaces in Spreadsheets — oft Start von Shadow IT und Two-Version Problem.',
        'en' => 'Export from governed surfaces into spreadsheets — often the start of shadow IT and the two-version problem.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'shadow-it',
          'label' => [
            'de' => 'Shadow IT',
            'en' => 'Shadow IT',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'two-version-problem',
          'label' => [
            'de' => 'Two-Version Problem',
            'en' => 'Two-Version Problem',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'governed-self-service',
          'label' => [
            'de' => 'Governed Self-Service',
            'en' => 'Governed Self-Service',
          ],
        ],
      ],
    ],
    [
      'id' => 'manual-reconciliation',
      'order' => 628,
      'category' => 'bi',
      'term' => [
        'de' => 'Manual Reconciliation',
        'en' => 'Manual Reconciliation',
      ],
      'aliases' => [
        'Abgleich per Hand',
        'Spreadsheet Reconciliation',
      ],
      'definition' => [
        'de' => 'Zahlen manuell zwischen Systemen abgleichen — Symptom fehlender Contracts, Grain und Lineage.',
        'en' => 'Manually reconciling numbers across systems — symptom of missing contracts, grain, and lineage.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'two-version-problem',
          'label' => [
            'de' => 'Two-Version Problem',
            'en' => 'Two-Version Problem',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metric-lineage',
          'label' => [
            'de' => 'Metric Lineage',
            'en' => 'Metric Lineage',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-contract',
          'label' => [
            'de' => 'KPI Contract',
            'en' => 'KPI Contract',
          ],
        ],
      ],
    ],
    [
      'id' => 'mttr',
      'order' => 797,
      'category' => 'quality',
      'term' => [
        'de' => 'MTTR',
        'en' => 'MTTR',
      ],
      'aliases' => [
        'Mean Time To Recovery',
        'Mean Time To Repair',
      ],
      'definition' => [
        'de' => 'Durchschnittliche Zeit bis zur Wiederherstellung nach Incidents — Runbooks und On-Call senken sie.',
        'en' => 'Average time to recover after incidents — runbooks and on-call reduce it.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'incident-runbook',
          'label' => [
            'de' => 'Incident Runbook',
            'en' => 'Incident Runbook',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'on-call',
          'label' => [
            'de' => 'On-Call',
            'en' => 'On-Call',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'error-budget',
          'label' => [
            'de' => 'Error Budget',
            'en' => 'Error Budget',
          ],
        ],
      ],
    ],
    [
      'id' => 'error-budget',
      'order' => 798,
      'category' => 'quality',
      'term' => [
        'de' => 'Error Budget',
        'en' => 'Error Budget',
      ],
      'aliases' => [
        'SLO Error Budget',
        'Zuverlässigkeitsbudget',
      ],
      'definition' => [
        'de' => 'Erlaubte Unzuverlässigkeit unter dem SLO — steuert Change-Tempo vs. Stabilität.',
        'en' => 'Allowed unreliability under the SLO — steers change pace vs. stability.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'sla-slo',
          'label' => [
            'de' => 'SLA / SLO',
            'en' => 'SLA / SLO',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mttr',
          'label' => [
            'de' => 'MTTR',
            'en' => 'MTTR',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'sre',
          'label' => [
            'de' => 'SRE',
            'en' => 'SRE',
          ],
        ],
      ],
    ],
    [
      'id' => 'toil',
      'order' => 799,
      'category' => 'quality',
      'term' => [
        'de' => 'Toil',
        'en' => 'Toil',
      ],
      'aliases' => [
        'Manual Toil',
        'Operativer Ballast',
      ],
      'definition' => [
        'de' => 'Manuelle, repetitive Ops-Arbeit ohne dauerhaften Mehrwert — Automation und Golden Paths reduzieren sie.',
        'en' => 'Manual, repetitive ops work without lasting value — automation and golden paths reduce it.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'sre',
          'label' => [
            'de' => 'SRE',
            'en' => 'SRE',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'golden-path',
          'label' => [
            'de' => 'Golden Path',
            'en' => 'Golden Path',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'platform-engineering',
          'label' => [
            'de' => 'Platform Engineering',
            'en' => 'Platform Engineering',
          ],
        ],
      ],
    ],
    [
      'id' => 'orphan-pipeline',
      'order' => 801,
      'category' => 'quality',
      'term' => [
        'de' => 'Orphan Pipeline',
        'en' => 'Orphan Pipeline',
      ],
      'aliases' => [
        'Zombie Pipeline',
        'Ownerless Job',
      ],
      'definition' => [
        'de' => 'Pipeline ohne klaren Owner/Consumer — kostet Geld und erzeugt stille Incidents.',
        'en' => 'Pipeline without clear owner/consumer — costs money and creates silent incidents.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'usage-metadata',
          'label' => [
            'de' => 'Usage Metadata',
            'en' => 'Usage Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'technical-owner',
          'label' => [
            'de' => 'Technical Owner',
            'en' => 'Technical Owner',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'deprecation',
          'label' => [
            'de' => 'Deprecation',
            'en' => 'Deprecation',
          ],
        ],
      ],
    ],
    [
      'id' => 'synthetic-data',
      'order' => 926,
      'category' => 'privacy',
      'term' => [
        'de' => 'Synthetic Data',
        'en' => 'Synthetic Data',
      ],
      'aliases' => [
        'Synthetische Daten',
        'Fake Data for Dev',
      ],
      'definition' => [
        'de' => 'Künstlich erzeugte Daten für Test/Training — reduziert PII-Risiko, braucht Realismus-Checks.',
        'en' => 'Artificially generated data for test/training — reduces PII risk, needs realism checks.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'anonymization',
          'label' => [
            'de' => 'Anonymization',
            'en' => 'Anonymization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'differential-privacy',
          'label' => [
            'de' => 'Differential Privacy',
            'en' => 'Differential Privacy',
          ],
        ],
      ],
    ],
    [
      'id' => 'differential-privacy',
      'order' => 927,
      'category' => 'privacy',
      'term' => [
        'de' => 'Differential Privacy',
        'en' => 'Differential Privacy',
      ],
      'aliases' => [
        'DP',
        'Differenzielle Privatsphäre',
      ],
      'definition' => [
        'de' => 'Mathematischer Privacy-Schutz durch kontrolliertes Rauschen — stark, aber Utility-Trade-off.',
        'en' => 'Mathematical privacy protection via controlled noise — strong, but utility trade-off.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'synthetic-data',
          'label' => [
            'de' => 'Synthetic Data',
            'en' => 'Synthetic Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'anonymization',
          'label' => [
            'de' => 'Anonymization',
            'en' => 'Anonymization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'clean-room',
          'label' => [
            'de' => 'Clean Room',
            'en' => 'Clean Room',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-residency',
      'order' => 928,
      'category' => 'privacy',
      'term' => [
        'de' => 'Data Residency',
        'en' => 'Data Residency',
      ],
      'aliases' => [
        'Data Sovereignty',
        'Speicherort-Anforderung',
        'Residency',
      ],
      'definition' => [
        'de' => 'Vorgabe, wo Daten physisch/juristisch liegen dürfen — treibt Cloud-Region und Sharing-Design.',
        'en' => 'Requirement for where data may physically/legally reside — drives cloud region and sharing design.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'gdpr',
          'label' => [
            'de' => 'GDPR',
            'en' => 'GDPR',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'hybrid-cloud',
          'label' => [
            'de' => 'Hybrid Cloud',
            'en' => 'Hybrid Cloud',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-share',
          'label' => [
            'de' => 'Data Share',
            'en' => 'Data Share',
          ],
        ],
      ],
    ],
    [
      'id' => 'pii-scan',
      'order' => 929,
      'category' => 'privacy',
      'term' => [
        'de' => 'PII Scan',
        'en' => 'PII Scan',
      ],
      'aliases' => [
        'PII Discovery',
        'Sensitive Data Discovery',
      ],
      'definition' => [
        'de' => 'Automatische Suche nach personenbezogenen Mustern in Schemas/Inhalten — Start für Classification.',
        'en' => 'Automated search for personal patterns in schemas/content — starting point for classification.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-classification',
          'label' => [
            'de' => 'Data Classification',
            'en' => 'Data Classification',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'propagating-pii-metadata-across-data-warehouses',
          'label' => [
            'de' => 'PII-Metadaten propagieren',
            'en' => 'Propagating PII metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'tombstone',
      'order' => 930,
      'category' => 'privacy',
      'term' => [
        'de' => 'Tombstone',
        'en' => 'Tombstone',
      ],
      'aliases' => [
        'Delete Marker',
        'Lösch-Marker',
      ],
      'definition' => [
        'de' => 'Marker, dass ein Record gelöscht/unterdrückt ist — relevant für CDC, DSDR und Soft Deletes.',
        'en' => 'Marker that a record is deleted/suppressed — relevant for CDC, DSDR, and soft deletes.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'soft-delete',
          'label' => [
            'de' => 'Soft Delete',
            'en' => 'Soft Delete',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dsdr',
          'label' => [
            'de' => 'DSDR',
            'en' => 'DSDR',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'cdc',
          'label' => [
            'de' => 'CDC (Change Data Capture)',
            'en' => 'CDC (Change Data Capture)',
          ],
        ],
      ],
    ],
    [
      'id' => 'zero-trust',
      'order' => 1083,
      'category' => 'security',
      'term' => [
        'de' => 'Zero Trust',
        'en' => 'Zero Trust',
      ],
      'aliases' => [
        'Never Trust Always Verify',
        'Zero-Trust Architecture',
      ],
      'definition' => [
        'de' => 'Kein implizites Vertrauen durch Netzwerkzone — kontinuierliche Verifikation von Identität und Context.',
        'en' => 'No implicit trust from network zone — continuous verification of identity and context.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mfa',
          'label' => [
            'de' => 'MFA',
            'en' => 'MFA',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'iam',
          'label' => [
            'de' => 'IAM',
            'en' => 'IAM',
          ],
        ],
      ],
    ],
    [
      'id' => 'secrets-management',
      'order' => 1084,
      'category' => 'security',
      'term' => [
        'de' => 'Secrets Management',
        'en' => 'Secrets Management',
      ],
      'aliases' => [
        'Secret Store',
        'Credential Vault',
        'Geheimnisverwaltung',
      ],
      'definition' => [
        'de' => 'Zentrale, rotierbare Ablage für Keys/Tokens — keine Secrets in Repos oder Notebooks.',
        'en' => 'Central, rotatable store for keys/tokens — no secrets in repos or notebooks.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'key-vault',
          'label' => [
            'de' => 'Key Vault',
            'en' => 'Key Vault',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'service-principal',
          'label' => [
            'de' => 'Service Principal',
            'en' => 'Service Principal',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'encryption',
          'label' => [
            'de' => 'Encryption',
            'en' => 'Encryption',
          ],
        ],
      ],
    ],
    [
      'id' => 'key-vault',
      'order' => 1085,
      'category' => 'security',
      'term' => [
        'de' => 'Key Vault',
        'en' => 'Key Vault',
      ],
      'aliases' => [
        'Azure Key Vault',
        'KMS',
        'HSM-backed Vault',
      ],
      'definition' => [
        'de' => 'Managed Service für Keys und Secrets — oft Anker für Encryption und BYOK.',
        'en' => 'Managed service for keys and secrets — often the anchor for encryption and BYOK.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'secrets-management',
          'label' => [
            'de' => 'Secrets Management',
            'en' => 'Secrets Management',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'byok',
          'label' => [
            'de' => 'BYOK',
            'en' => 'BYOK',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'encryption',
          'label' => [
            'de' => 'Encryption',
            'en' => 'Encryption',
          ],
        ],
      ],
    ],
    [
      'id' => 'byok',
      'order' => 1086,
      'category' => 'security',
      'term' => [
        'de' => 'BYOK',
        'en' => 'BYOK',
      ],
      'aliases' => [
        'Bring Your Own Key',
        'Customer-Managed Key',
        'CMK',
      ],
      'definition' => [
        'de' => 'Kunde kontrolliert Encryption Keys — erhöht Kontrolle und Compliance-Anforderungen.',
        'en' => 'Customer controls encryption keys — increases control and compliance requirements.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'key-vault',
          'label' => [
            'de' => 'Key Vault',
            'en' => 'Key Vault',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'encryption',
          'label' => [
            'de' => 'Encryption',
            'en' => 'Encryption',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-residency',
          'label' => [
            'de' => 'Data Residency',
            'en' => 'Data Residency',
          ],
        ],
      ],
    ],
    [
      'id' => 'row-access-policy',
      'order' => 1087,
      'category' => 'security',
      'term' => [
        'de' => 'Row Access Policy',
        'en' => 'Row Access Policy',
      ],
      'aliases' => [
        'Snowflake Row Access Policy',
        'Table RLS Policy',
      ],
      'definition' => [
        'de' => 'Policy-Objekt, das Zeilenzugriff im Warehouse steuert — Ergänzung zu App-seitigem RLS.',
        'en' => 'Policy object controlling row access in the warehouse — complements app-side RLS.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'row-level-security',
          'label' => [
            'de' => 'Row-Level Security (RLS)',
            'en' => 'Row-Level Security (RLS)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dynamic-masking',
          'label' => [
            'de' => 'Dynamic Masking',
            'en' => 'Dynamic Masking',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'snowflake-masking-policies-qlik-section-access',
          'label' => [
            'de' => 'Snowflake Masking & Qlik Section Access',
            'en' => 'Snowflake masking & Qlik Section Access',
          ],
        ],
      ],
    ],
    [
      'id' => 'object-tag',
      'order' => 1088,
      'category' => 'security',
      'term' => [
        'de' => 'Object Tag',
        'en' => 'Object Tag',
      ],
      'aliases' => [
        'Data Tag',
        'Classification Tag',
        'Governance Tag',
      ],
      'definition' => [
        'de' => 'Metadaten-Tag an Tabellen/Spalten für Classification und Policy Binding — Active Metadata in Action.',
        'en' => 'Metadata tag on tables/columns for classification and policy binding — active metadata in action.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-classification',
          'label' => [
            'de' => 'Data Classification',
            'en' => 'Data Classification',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'sensitivity-label',
          'label' => [
            'de' => 'Sensitivity Label',
            'en' => 'Sensitivity Label',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'control-driving-metadata',
          'label' => [
            'de' => 'Control-Driving Metadata',
            'en' => 'Control-Driving Metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'sre',
      'order' => 1420,
      'category' => 'process',
      'term' => [
        'de' => 'SRE',
        'en' => 'SRE',
      ],
      'aliases' => [
        'Site Reliability Engineering',
        'Reliability Engineering',
      ],
      'definition' => [
        'de' => 'Praxis, Reliability mit Engineering zu betreiben — SLOs, Error Budgets, weniger Toil.',
        'en' => 'Practice of running reliability with engineering — SLOs, error budgets, less toil.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'error-budget',
          'label' => [
            'de' => 'Error Budget',
            'en' => 'Error Budget',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'toil',
          'label' => [
            'de' => 'Toil',
            'en' => 'Toil',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'platform-engineering',
          'label' => [
            'de' => 'Platform Engineering',
            'en' => 'Platform Engineering',
          ],
        ],
      ],
    ],
    [
      'id' => 'platform-engineering',
      'order' => 1421,
      'category' => 'process',
      'term' => [
        'de' => 'Platform Engineering',
        'en' => 'Platform Engineering',
      ],
      'aliases' => [
        'Internal Developer Platform',
        'Platform Team',
      ],
      'definition' => [
        'de' => 'Baut Self-Service-Plattform-Produkte für Teams — Golden Paths statt Ticket-getriebenem Ops.',
        'en' => 'Builds self-service platform products for teams — golden paths instead of ticket-driven ops.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'golden-path',
          'label' => [
            'de' => 'Golden Path',
            'en' => 'Golden Path',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'platform-ops',
          'label' => [
            'de' => 'Platform Ops',
            'en' => 'Platform Ops',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'operating-and-governing-the-platform',
          'label' => [
            'de' => 'Plattform betreiben und steuern',
            'en' => 'Operating and governing the platform',
          ],
        ],
      ],
    ],
    [
      'id' => 'golden-path',
      'order' => 1422,
      'category' => 'process',
      'term' => [
        'de' => 'Golden Path',
        'en' => 'Golden Path',
      ],
      'aliases' => [
        'Paved Road',
        'Preferred Path',
      ],
      'definition' => [
        'de' => 'Unterstützter Standardweg (Templates, CI, Patterns) — schnell und compliant zugleich.',
        'en' => 'Supported standard path (templates, CI, patterns) — fast and compliant at once.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'platform-engineering',
          'label' => [
            'de' => 'Platform Engineering',
            'en' => 'Platform Engineering',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'toil',
          'label' => [
            'de' => 'Toil',
            'en' => 'Toil',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
      ],
    ],
    [
      'id' => 'value-stream',
      'order' => 1423,
      'category' => 'process',
      'term' => [
        'de' => 'Value Stream',
        'en' => 'Value Stream',
      ],
      'aliases' => [
        'Wertstrom',
        'Value Stream Mapping',
      ],
      'definition' => [
        'de' => 'Ende-zu-Ende-Fluss vom Bedarf bis zum Outcome — hilft, Data Products und Roadmaps zu priorisieren.',
        'en' => 'End-to-end flow from need to outcome — helps prioritize data products and roadmaps.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-roadmap',
          'label' => [
            'de' => 'Data Roadmap',
            'en' => 'Data Roadmap',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lead-time',
          'label' => [
            'de' => 'Lead Time',
            'en' => 'Lead Time',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'product-thinking',
          'label' => [
            'de' => 'Product Thinking',
            'en' => 'Product Thinking',
          ],
        ],
      ],
    ],
    [
      'id' => 'lead-time',
      'order' => 1424,
      'category' => 'process',
      'term' => [
        'de' => 'Lead Time',
        'en' => 'Lead Time',
      ],
      'aliases' => [
        'Durchlaufzeit',
        'Request to Prod',
      ],
      'definition' => [
        'de' => 'Zeit von Anfrage bis lieferfähig in Prod — zentrale Flow-Metrik neben Cycle Time.',
        'en' => 'Time from request to production-ready — core flow metric beside cycle time.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'cycle-time',
          'label' => [
            'de' => 'Cycle Time',
            'en' => 'Cycle Time',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'wip',
          'label' => [
            'de' => 'WIP',
            'en' => 'WIP',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'value-stream',
          'label' => [
            'de' => 'Value Stream',
            'en' => 'Value Stream',
          ],
        ],
      ],
    ],
    [
      'id' => 'cycle-time',
      'order' => 1425,
      'category' => 'process',
      'term' => [
        'de' => 'Cycle Time',
        'en' => 'Cycle Time',
      ],
      'aliases' => [
        'Zykluszeit',
        'Start to Finish',
      ],
      'definition' => [
        'de' => 'Zeit aktiver Arbeit an einem Item — sinkt mit kleineren Batches und weniger WIP.',
        'en' => 'Time of active work on an item — drops with smaller batches and less WIP.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lead-time',
          'label' => [
            'de' => 'Lead Time',
            'en' => 'Lead Time',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'wip',
          'label' => [
            'de' => 'WIP',
            'en' => 'WIP',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mvp',
          'label' => [
            'de' => 'MVP',
            'en' => 'MVP',
          ],
        ],
      ],
    ],
    [
      'id' => 'wip',
      'order' => 1426,
      'category' => 'process',
      'term' => [
        'de' => 'WIP',
        'en' => 'WIP',
      ],
      'aliases' => [
        'Work in Progress',
        'WIP Limit',
      ],
      'definition' => [
        'de' => 'Gleichzeitig offene Arbeit — hohe WIP verlängert Lead Time und erzeugt Halbfertiges ohne Outcome.',
        'en' => 'Work open at the same time — high WIP lengthens lead time and creates half-done work without outcome.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'cycle-time',
          'label' => [
            'de' => 'Cycle Time',
            'en' => 'Cycle Time',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lead-time',
          'label' => [
            'de' => 'Lead Time',
            'en' => 'Lead Time',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'value-stream',
          'label' => [
            'de' => 'Value Stream',
            'en' => 'Value Stream',
          ],
        ],
      ],
    ],
    [
      'id' => 'product-thinking',
      'order' => 1427,
      'category' => 'process',
      'term' => [
        'de' => 'Product Thinking',
        'en' => 'Product Thinking',
      ],
      'aliases' => [
        'Product Mindset',
        'Outcome over Output',
      ],
      'definition' => [
        'de' => 'Fokus auf Nutzer-Outcomes, Lifecycle und Ownership — statt reiner Ticket-/Report-Produktion.',
        'en' => 'Focus on user outcomes, lifecycle, and ownership — instead of pure ticket/report production.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-product-owner',
          'label' => [
            'de' => 'Data Product Owner',
            'en' => 'Data Product Owner',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metadata-product',
          'label' => [
            'de' => 'Metadata Product',
            'en' => 'Metadata Product',
          ],
        ],
      ],
    ],
    [
      'id' => 'community-of-practice',
      'order' => 1428,
      'category' => 'process',
      'term' => [
        'de' => 'Community of Practice',
        'en' => 'Community of Practice',
      ],
      'aliases' => [
        'CoP',
        'Guild',
        'Practitioners Community',
      ],
      'definition' => [
        'de' => 'Netzwerk zum Teilen von Standards und Lernen über Teams hinweg — ergänzt CoE, ersetzt es nicht.',
        'en' => 'Network for sharing standards and learning across teams — complements a CoE, does not replace it.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance Center of Excellence',
            'en' => 'Governance Center of Excellence',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-literacy',
          'label' => [
            'de' => 'Data Literacy',
            'en' => 'Data Literacy',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance CoE',
            'en' => 'Governance CoE',
          ],
        ],
      ],
    ],
    [
      'id' => 'bus-factor',
      'order' => 1429,
      'category' => 'process',
      'term' => [
        'de' => 'Bus Factor',
        'en' => 'Bus Factor',
      ],
      'aliases' => [
        'Truck Factor',
        'Key-Person Risk',
      ],
      'definition' => [
        'de' => 'Wieviele Personen ausfallen können, bevor Wissen/System kippt — niedrig bei Tribal Knowledge.',
        'en' => 'How many people can be hit by a bus before knowledge/system fails — low with tribal knowledge.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'tribal-knowledge',
          'label' => [
            'de' => 'Tribal Knowledge',
            'en' => 'Tribal Knowledge',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'adr',
          'label' => [
            'de' => 'ADR',
            'en' => 'ADR',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'business-metadata',
          'label' => [
            'de' => 'Business Metadata',
            'en' => 'Business Metadata',
          ],
        ],
      ],
    ],
    [
      'id' => 'cqrs',
      'order' => 436,
      'category' => 'architecture',
      'term' => [
        'de' => 'CQRS',
        'en' => 'CQRS',
      ],
      'aliases' => [
        'Command Query Responsibility Segregation',
      ],
      'definition' => [
        'de' => 'Trennung von Schreib- und Lesemodellen — nützlich bei unterschiedlichen Write/Read-Lasten, erhöht Komplexität.',
        'en' => 'Separation of write and read models — useful under different write/read loads, increases complexity.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'saga',
          'label' => [
            'de' => 'Saga',
            'en' => 'Saga',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'outbox',
          'label' => [
            'de' => 'Outbox Pattern',
            'en' => 'Outbox Pattern',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'simplest-viable-architecture',
          'label' => [
            'de' => 'Simplest Viable Architecture',
            'en' => 'Simplest Viable Architecture',
          ],
        ],
      ],
    ],
    [
      'id' => 'saga',
      'order' => 437,
      'category' => 'architecture',
      'term' => [
        'de' => 'Saga',
        'en' => 'Saga',
      ],
      'aliases' => [
        'Saga Pattern',
        'Distributed Transaction Saga',
      ],
      'definition' => [
        'de' => 'Sequenz lokaler Transaktionen mit Kompensation statt verteilter 2PC — für Multi-Service-Flows.',
        'en' => 'Sequence of local transactions with compensation instead of distributed 2PC — for multi-service flows.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'cqrs',
          'label' => [
            'de' => 'CQRS',
            'en' => 'CQRS',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'outbox',
          'label' => [
            'de' => 'Outbox Pattern',
            'en' => 'Outbox Pattern',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'idempotent',
          'label' => [
            'de' => 'Idempotent Load',
            'en' => 'Idempotent Load',
          ],
        ],
      ],
    ],
    [
      'id' => 'polyglot-persistence',
      'order' => 438,
      'category' => 'architecture',
      'term' => [
        'de' => 'Polyglot Persistence',
        'en' => 'Polyglot Persistence',
      ],
      'aliases' => [
        'Polyglot Data',
        'Multi-Store Architecture',
      ],
      'definition' => [
        'de' => 'Mehrere Speichertechnologien je Use Case — braucht klare Ownership und Integrationsverträge.',
        'en' => 'Multiple storage technologies per use case — needs clear ownership and integration contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'logical-architecture',
          'label' => [
            'de' => 'Logical Architecture',
            'en' => 'Logical Architecture',
          ],
        ],
      ],
    ],
    [
      'id' => 'change-data-feed',
      'order' => 439,
      'category' => 'architecture',
      'term' => [
        'de' => 'Change Data Feed',
        'en' => 'Change Data Feed',
      ],
      'aliases' => [
        'CDF',
        'Table Change Feed',
      ],
      'definition' => [
        'de' => 'Tabellen-Change-Stream aus Lakehouse-Formaten — Downstream liest Inserts/Updates/Deletes inkrementell.',
        'en' => 'Table change stream from lakehouse formats — downstream reads inserts/updates/deletes incrementally.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'cdc',
          'label' => [
            'de' => 'CDC (Change Data Capture)',
            'en' => 'CDC (Change Data Capture)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'log-based-cdc',
          'label' => [
            'de' => 'Log-Based CDC',
            'en' => 'Log-Based CDC',
          ],
        ],
      ],
    ],
    [
      'id' => 'z-order',
      'order' => 441,
      'category' => 'architecture',
      'term' => [
        'de' => 'Z-Order',
        'en' => 'Z-Order',
      ],
      'aliases' => [
        'Z-Ordering',
        'Multi-dimensional Clustering',
      ],
      'definition' => [
        'de' => 'Datei-Clustering nach mehreren Spalten — verbessert Skip/Pruning bei gemischten Filtern.',
        'en' => 'File clustering by multiple columns — improves skip/pruning for mixed filters.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'liquid-clustering',
          'label' => [
            'de' => 'Liquid Clustering',
            'en' => 'Liquid Clustering',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'partition-pruning',
          'label' => [
            'de' => 'Partition Pruning',
            'en' => 'Partition Pruning',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
      ],
    ],
    [
      'id' => 'liquid-clustering',
      'order' => 442,
      'category' => 'architecture',
      'term' => [
        'de' => 'Liquid Clustering',
        'en' => 'Liquid Clustering',
      ],
      'aliases' => [
        'Auto Clustering',
        'Liquid Cluster',
      ],
      'definition' => [
        'de' => 'Flexible Clustering-Strategie in modernen Lakehouses — weniger starre Partitionierung.',
        'en' => 'Flexible clustering strategy in modern lakehouses — less rigid partitioning.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'z-order',
          'label' => [
            'de' => 'Z-Order',
            'en' => 'Z-Order',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'compaction',
          'label' => [
            'de' => 'Compaction',
            'en' => 'Compaction',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
      ],
    ],
    [
      'id' => 'vacuum',
      'order' => 443,
      'category' => 'architecture',
      'term' => [
        'de' => 'Vacuum',
        'en' => 'Vacuum',
      ],
      'aliases' => [
        'Table Vacuum',
        'File Cleanup',
      ],
      'definition' => [
        'de' => 'Aufräumen nicht mehr referenzierter Dateien nach Time Travel Retention — Kosten- und Compliance-Hebel.',
        'en' => 'Cleanup of unreferenced files after time-travel retention — cost and compliance lever.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'time-travel',
          'label' => [
            'de' => 'Time Travel',
            'en' => 'Time Travel',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'compaction',
          'label' => [
            'de' => 'Compaction',
            'en' => 'Compaction',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'finops',
          'label' => [
            'de' => 'FinOps',
            'en' => 'FinOps',
          ],
        ],
      ],
    ],
    [
      'id' => 'shallow-clone',
      'order' => 444,
      'category' => 'architecture',
      'term' => [
        'de' => 'Shallow Clone',
        'en' => 'Shallow Clone',
      ],
      'aliases' => [
        'Metadata Clone',
        'Shallow Copy',
      ],
      'definition' => [
        'de' => 'Klon, der Metadaten teilt und Daten nicht kopiert — schnell, aber lifecycle-gekoppelt.',
        'en' => 'Clone that shares metadata and does not copy data — fast, but lifecycle-coupled.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'zero-copy-clone',
          'label' => [
            'de' => 'Zero-Copy Clone',
            'en' => 'Zero-Copy Clone',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'deep-clone',
          'label' => [
            'de' => 'Deep Clone',
            'en' => 'Deep Clone',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'time-travel',
          'label' => [
            'de' => 'Time Travel',
            'en' => 'Time Travel',
          ],
        ],
      ],
    ],
    [
      'id' => 'deep-clone',
      'order' => 445,
      'category' => 'architecture',
      'term' => [
        'de' => 'Deep Clone',
        'en' => 'Deep Clone',
      ],
      'aliases' => [
        'Full Clone',
        'Independent Clone',
      ],
      'definition' => [
        'de' => 'Klon mit unabhängiger Datenkopie — teurer, aber entkoppelt von Source-Lifecycle.',
        'en' => 'Clone with an independent data copy — costlier, but decoupled from source lifecycle.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'shallow-clone',
          'label' => [
            'de' => 'Shallow Clone',
            'en' => 'Shallow Clone',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'zero-copy-clone',
          'label' => [
            'de' => 'Zero-Copy Clone',
            'en' => 'Zero-Copy Clone',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-share',
          'label' => [
            'de' => 'Data Share',
            'en' => 'Data Share',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-marketplace',
      'order' => 446,
      'category' => 'architecture',
      'term' => [
        'de' => 'Data Marketplace',
        'en' => 'Data Marketplace',
      ],
      'aliases' => [
        'Marketplace',
        'Internal Data Market',
        'Data Exchange',
      ],
      'definition' => [
        'de' => 'Katalog/Marktplatz zum Finden und Beziehen von Data Products — braucht Certification und Contracts.',
        'en' => 'Catalog/marketplace to find and consume data products — needs certification and contracts.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-catalog',
          'label' => [
            'de' => 'Data Catalog',
            'en' => 'Data Catalog',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'product-certification',
          'label' => [
            'de' => 'Data Product Certification',
            'en' => 'Data Product Certification',
          ],
        ],
      ],
    ],
    [
      'id' => 'late-arriving-dimension',
      'order' => 570,
      'category' => 'modeling',
      'term' => [
        'de' => 'Late-Arriving Dimension',
        'en' => 'Late-Arriving Dimension',
      ],
      'aliases' => [
        'Early Arriving Fact',
        'LAD',
      ],
      'definition' => [
        'de' => 'Fact kommt vor der Dimension — braucht Infered/Unknown Members und späteres Matching.',
        'en' => 'Fact arrives before the dimension — needs inferred/unknown members and later matching.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'late-arriving',
          'label' => [
            'de' => 'Late-Arriving Data',
            'en' => 'Late-Arriving Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'matching',
          'label' => [
            'de' => 'Matching',
            'en' => 'Matching',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'surrogate-key',
          'label' => [
            'de' => 'Surrogate Key',
            'en' => 'Surrogate Key',
          ],
        ],
      ],
    ],
    [
      'id' => 'multi-valued-dimension',
      'order' => 571,
      'category' => 'modeling',
      'term' => [
        'de' => 'Multi-Valued Dimension',
        'en' => 'Multi-Valued Dimension',
      ],
      'aliases' => [
        'Multi Valued Dim',
        'Bridge for Multi-Value',
      ],
      'definition' => [
        'de' => 'Ein Fact hat mehrere Dimensionswerte gleichzeitig — typisch über Bridge/Weight Factors gelöst.',
        'en' => 'One fact has multiple dimension values at once — typically solved via bridge/weight factors.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'bridge-table',
          'label' => [
            'de' => 'Bridge Table',
            'en' => 'Bridge Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'many-to-many',
          'label' => [
            'de' => 'Many-to-Many',
            'en' => 'Many-to-Many',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
      ],
    ],
    [
      'id' => 'scd-type-6',
      'order' => 573,
      'category' => 'modeling',
      'term' => [
        'de' => 'SCD Type 6',
        'en' => 'SCD Type 6',
      ],
      'aliases' => [
        'Hybrid SCD',
        'Type 1+2+3',
        'SCD6',
      ],
      'definition' => [
        'de' => 'Hybrid aus Type 1/2/3 — aktuelle und historische Sicht parallel, modellseitig anspruchsvoll.',
        'en' => 'Hybrid of types 1/2/3 — current and historical views in parallel, modeling-heavy.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scd-type-1',
          'label' => [
            'de' => 'SCD Type 1',
            'en' => 'SCD Type 1',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scd-type-3',
          'label' => [
            'de' => 'SCD Type 3',
            'en' => 'SCD Type 3',
          ],
        ],
      ],
    ],
    [
      'id' => 'slowly-changing-type-0',
      'order' => 574,
      'category' => 'modeling',
      'term' => [
        'de' => 'SCD Type 0',
        'en' => 'SCD Type 0',
      ],
      'aliases' => [
        'Type 0 SCD',
        'Retain Original',
      ],
      'definition' => [
        'de' => 'Attribut bleibt unverändert (Originalwert behalten) — selten, klar dokumentieren.',
        'en' => 'Attribute stays unchanged (retain original) — rare; document clearly.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'scd',
          'label' => [
            'de' => 'SCD (Slowly Changing Dimension)',
            'en' => 'SCD (Slowly Changing Dimension)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'scd-type-1',
          'label' => [
            'de' => 'SCD Type 1',
            'en' => 'SCD Type 1',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'natural-key',
          'label' => [
            'de' => 'Natural Key',
            'en' => 'Natural Key',
          ],
        ],
      ],
    ],
    [
      'id' => 'heterogeneous-dimension',
      'order' => 575,
      'category' => 'modeling',
      'term' => [
        'de' => 'Heterogeneous Dimension',
        'en' => 'Heterogeneous Dimension',
      ],
      'aliases' => [
        'Mixed Dimension',
        'Supertype Dimension',
      ],
      'definition' => [
        'de' => 'Eine Dimensionstabelle für unterschiedliche Entity-Typen mit gemeinsamen Keys — vorsichtig einsetzen.',
        'en' => 'One dimension table for different entity types with shared keys — use carefully.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'conformed-dimension',
          'label' => [
            'de' => 'Conformed Dimension',
            'en' => 'Conformed Dimension',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
      ],
    ],
    [
      'id' => 'stale-metric',
      'order' => 629,
      'category' => 'bi',
      'term' => [
        'de' => 'Stale Metric',
        'en' => 'Stale Metric',
      ],
      'aliases' => [
        'Outdated KPI',
        'Veraltete Kennzahl',
      ],
      'definition' => [
        'de' => 'Kennzahl mit veralteter Definition oder ohne Owner — Trust-Killer trotz „offizieller“ Optik.',
        'en' => 'Metric with outdated definition or no owner — trust killer despite looking “official”.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'metric-proliferation',
          'label' => [
            'de' => 'Metric Proliferation',
            'en' => 'Metric Proliferation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'deprecation',
          'label' => [
            'de' => 'Deprecation',
            'en' => 'Deprecation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'trusted-metrics',
          'label' => [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted Metrics',
          ],
        ],
      ],
    ],
    [
      'id' => 'conflicting-metric',
      'order' => 631,
      'category' => 'bi',
      'term' => [
        'de' => 'Conflicting Metric',
        'en' => 'Conflicting Metric',
      ],
      'aliases' => [
        'KPI Conflict',
        'Widersprüchliche Kennzahl',
      ],
      'definition' => [
        'de' => 'Zwei Measures beantworten dieselbe Frage unterschiedlich — Two-Version Problem in Zahlenform.',
        'en' => 'Two measures answer the same question differently — the two-version problem in numbers.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'two-version-problem',
          'label' => [
            'de' => 'Two-Version Problem',
            'en' => 'Two-Version Problem',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'kpi-contract',
          'label' => [
            'de' => 'KPI Contract',
            'en' => 'KPI Contract',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'master-measure',
          'label' => [
            'de' => 'Master Measure',
            'en' => 'Master Measure',
          ],
        ],
      ],
    ],
    [
      'id' => 'orphan-report',
      'order' => 632,
      'category' => 'bi',
      'term' => [
        'de' => 'Orphan Report',
        'en' => 'Orphan Report',
      ],
      'aliases' => [
        'Ownerless Report',
        'Unused Report',
      ],
      'definition' => [
        'de' => 'Report ohne Owner oder Nutzung — aufräumen, archivieren oder deprecaten.',
        'en' => 'Report without owner or usage — clean up, archive, or deprecate.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dashboard-sprawl',
          'label' => [
            'de' => 'Dashboard Sprawl',
            'en' => 'Dashboard Sprawl',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'usage-metadata',
          'label' => [
            'de' => 'Usage Metadata',
            'en' => 'Usage Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'unused-dataset',
          'label' => [
            'de' => 'Unused Dataset',
            'en' => 'Unused Dataset',
          ],
        ],
      ],
    ],
    [
      'id' => 'unused-dataset',
      'order' => 633,
      'category' => 'bi',
      'term' => [
        'de' => 'Unused Dataset',
        'en' => 'Unused Dataset',
      ],
      'aliases' => [
        'Orphan Dataset',
        'Idle Dataset',
      ],
      'definition' => [
        'de' => 'Dataset ohne Consumer — Kosten und Risiko ohne Nutzen; Usage Metadata macht es sichtbar.',
        'en' => 'Dataset without consumers — cost and risk without value; usage metadata makes it visible.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'orphan-report',
          'label' => [
            'de' => 'Orphan Report',
            'en' => 'Orphan Report',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'usage-metadata',
          'label' => [
            'de' => 'Usage Metadata',
            'en' => 'Usage Metadata',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'deprecation',
          'label' => [
            'de' => 'Deprecation',
            'en' => 'Deprecation',
          ],
        ],
      ],
    ],
    [
      'id' => 'spreadsheet-hell',
      'order' => 634,
      'category' => 'bi',
      'term' => [
        'de' => 'Spreadsheet Hell',
        'en' => 'Spreadsheet Hell',
      ],
      'aliases' => [
        'Excel Hell',
        'Sheet Sprawl',
      ],
      'definition' => [
        'de' => 'Kritische Logik und Wahrheiten nur in Spreadsheets — Endstadium von Shadow IT und Excel Export.',
        'en' => 'Critical logic and truths only in spreadsheets — end state of shadow IT and Excel export.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'excel-export',
          'label' => [
            'de' => 'Excel Export',
            'en' => 'Excel Export',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'shadow-it',
          'label' => [
            'de' => 'Shadow IT',
            'en' => 'Shadow IT',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'governed-self-service',
          'label' => [
            'de' => 'Governed Self-Service',
            'en' => 'Governed Self-Service',
          ],
        ],
      ],
    ],
    [
      'id' => 'domain-ownership',
      'order' => 226,
      'category' => 'data',
      'term' => [
        'de' => 'Domain Ownership',
        'en' => 'Domain Ownership',
      ],
      'aliases' => [
        'Domain-Owned Data',
        'Fachliche Ownership',
      ],
      'definition' => [
        'de' => 'Ownership folgt der Domain, nicht dem zentralen Team allein — Kern von Mesh und Federated Governance.',
        'en' => 'Ownership follows the domain, not only a central team — core of mesh and federated governance.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-domain',
          'label' => [
            'de' => 'Data Domain',
            'en' => 'Data Domain',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-mesh',
          'label' => [
            'de' => 'Data Mesh',
            'en' => 'Data Mesh',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'federated-governance',
          'label' => [
            'de' => 'Federated Governance',
            'en' => 'Federated Governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'first-party-data',
      'order' => 227,
      'category' => 'data',
      'term' => [
        'de' => 'First-Party Data',
        'en' => 'First-Party Data',
      ],
      'aliases' => [
        '1st Party Data',
        'Own Customer Data',
      ],
      'definition' => [
        'de' => 'Direkt vom Unternehmen erhobene Kundendaten — oft wertvollster, aber stark regulationspflichtiger Bestand.',
        'en' => 'Customer data collected directly by the company — often most valuable, but heavily regulated.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'third-party-data',
          'label' => [
            'de' => 'Third-Party Data',
            'en' => 'Third-Party Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'zero-party-data',
          'label' => [
            'de' => 'Zero-Party Data',
            'en' => 'Zero-Party Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
      ],
    ],
    [
      'id' => 'third-party-data',
      'order' => 228,
      'category' => 'data',
      'term' => [
        'de' => 'Third-Party Data',
        'en' => 'Third-Party Data',
      ],
      'aliases' => [
        '3rd Party Data',
        'External Data',
      ],
      'definition' => [
        'de' => 'Daten von externen Anbietern — Contracts, Provenance und Zweckbindung besonders prüfen.',
        'en' => 'Data from external providers — scrutinize contracts, provenance, and purpose limitation.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'first-party-data',
          'label' => [
            'de' => 'First-Party Data',
            'en' => 'First-Party Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'metadata-provenance',
          'label' => [
            'de' => 'Metadata Provenance',
            'en' => 'Metadata Provenance',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'lawful-basis',
          'label' => [
            'de' => 'Lawful Basis',
            'en' => 'Lawful Basis',
          ],
        ],
      ],
    ],
    [
      'id' => 'zero-party-data',
      'order' => 229,
      'category' => 'data',
      'term' => [
        'de' => 'Zero-Party Data',
        'en' => 'Zero-Party Data',
      ],
      'aliases' => [
        '0-Party Data',
        'Intent Data Shared by User',
      ],
      'definition' => [
        'de' => 'Vom Nutzer bewusst geteilte Präferenzen/Intent — Consent und Purpose klar halten.',
        'en' => 'Preferences/intent consciously shared by the user — keep consent and purpose clear.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'consent',
          'label' => [
            'de' => 'Consent',
            'en' => 'Consent',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'first-party-data',
          'label' => [
            'de' => 'First-Party Data',
            'en' => 'First-Party Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'purpose-limitation',
          'label' => [
            'de' => 'Purpose Limitation',
            'en' => 'Purpose Limitation',
          ],
        ],
      ],
    ],
    [
      'id' => 'crown-jewel',
      'order' => 231,
      'category' => 'data',
      'term' => [
        'de' => 'Crown Jewel Data',
        'en' => 'Crown Jewel Data',
      ],
      'aliases' => [
        'Crown Jewels',
        'Kronjuwelen-Daten',
      ],
      'definition' => [
        'de' => 'Höchstwertige/höchstsensible Assets — maximale Controls, Monitoring und Ownership.',
        'en' => 'Highest-value/highest-sensitivity assets — maximum controls, monitoring, and ownership.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-classification',
          'label' => [
            'de' => 'Data Classification',
            'en' => 'Data Classification',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'special-category',
          'label' => [
            'de' => 'Special Category Data',
            'en' => 'Special Category Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
      ],
    ],
    [
      'id' => 'k-anonymity',
      'order' => 931,
      'category' => 'privacy',
      'term' => [
        'de' => 'k-Anonymity',
        'en' => 'k-Anonymity',
      ],
      'aliases' => [
        'K Anonymity',
        'k-Anonymität',
      ],
      'definition' => [
        'de' => 'Jedes Quasi-Identifier-Profil kommt mindestens k-mal vor — klassischer, begrenzter Anonymisierungsansatz.',
        'en' => 'Each quasi-identifier profile appears at least k times — classic, limited anonymization approach.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'anonymization',
          'label' => [
            'de' => 'Anonymization',
            'en' => 'Anonymization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'differential-privacy',
          'label' => [
            'de' => 'Differential Privacy',
            'en' => 'Differential Privacy',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'synthetic-data',
          'label' => [
            'de' => 'Synthetic Data',
            'en' => 'Synthetic Data',
          ],
        ],
      ],
    ],
    [
      'id' => 'format-preserving-encryption',
      'order' => 932,
      'category' => 'privacy',
      'term' => [
        'de' => 'Format-Preserving Encryption',
        'en' => 'Format-Preserving Encryption',
      ],
      'aliases' => [
        'FPE',
        'Format Preserving',
      ],
      'definition' => [
        'de' => 'Verschlüsselung unter Erhalt von Format/Länge — für Legacy-Felder, ersetzt keine Access Policies.',
        'en' => 'Encryption that preserves format/length — for legacy fields; does not replace access policies.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'encryption',
          'label' => [
            'de' => 'Encryption',
            'en' => 'Encryption',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'tokenization',
          'label' => [
            'de' => 'Tokenization',
            'en' => 'Tokenization',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'masking',
          'label' => [
            'de' => 'Masking',
            'en' => 'Masking',
          ],
        ],
      ],
    ],
    [
      'id' => 'homomorphic-encryption',
      'order' => 933,
      'category' => 'privacy',
      'term' => [
        'de' => 'Homomorphic Encryption',
        'en' => 'Homomorphic Encryption',
      ],
      'aliases' => [
        'FHE',
        'Homomorphic',
      ],
      'definition' => [
        'de' => 'Rechnen auf verschlüsselten Daten ohne Entschlüsselung — mächtig, heute oft noch teuer/komplex.',
        'en' => 'Compute on encrypted data without decrypting — powerful, still often costly/complex today.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'encryption',
          'label' => [
            'de' => 'Encryption',
            'en' => 'Encryption',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'clean-room',
          'label' => [
            'de' => 'Clean Room',
            'en' => 'Clean Room',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'differential-privacy',
          'label' => [
            'de' => 'Differential Privacy',
            'en' => 'Differential Privacy',
          ],
        ],
      ],
    ],
    [
      'id' => 'encryption-at-rest',
      'order' => 934,
      'category' => 'privacy',
      'term' => [
        'de' => 'Encryption at Rest',
        'en' => 'Encryption at Rest',
      ],
      'aliases' => [
        'At-Rest Encryption',
        'Ruheverschlüsselung',
      ],
      'definition' => [
        'de' => 'Verschlüsselung gespeicherter Daten — Basis-Hygiene, schützt nicht vor berechtigten Abfragen.',
        'en' => 'Encryption of stored data — baseline hygiene; does not protect against authorized queries.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'encryption-in-transit',
          'label' => [
            'de' => 'Encryption in Transit',
            'en' => 'Encryption in Transit',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'encryption',
          'label' => [
            'de' => 'Encryption',
            'en' => 'Encryption',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'byok',
          'label' => [
            'de' => 'BYOK',
            'en' => 'BYOK',
          ],
        ],
      ],
    ],
    [
      'id' => 'encryption-in-transit',
      'order' => 935,
      'category' => 'privacy',
      'term' => [
        'de' => 'Encryption in Transit',
        'en' => 'Encryption in Transit',
      ],
      'aliases' => [
        'TLS',
        'In-Transit Encryption',
      ],
      'definition' => [
        'de' => 'Verschlüsselung auf dem Transportweg — Standard für alle Pipeline- und API-Pfade.',
        'en' => 'Encryption on the wire — standard for all pipeline and API paths.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'encryption-at-rest',
          'label' => [
            'de' => 'Encryption at Rest',
            'en' => 'Encryption at Rest',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'encryption',
          'label' => [
            'de' => 'Encryption',
            'en' => 'Encryption',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'zero-trust',
          'label' => [
            'de' => 'Zero Trust',
            'en' => 'Zero Trust',
          ],
        ],
      ],
    ],
    [
      'id' => 'health-data',
      'order' => 936,
      'category' => 'privacy',
      'term' => [
        'de' => 'Health Data',
        'en' => 'Health Data',
      ],
      'aliases' => [
        'Gesundheitsdaten',
        'Medical Data',
        'PHI',
      ],
      'definition' => [
        'de' => 'Gesundheitsbezogene Personenangaben — meist Special Category / besonders schützenswert.',
        'en' => 'Health-related personal data — usually special category / especially protected.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'special-category',
          'label' => [
            'de' => 'Special Category Data',
            'en' => 'Special Category Data',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'workforce-policy',
          'label' => [
            'de' => 'Workforce / Employee Data Policy',
            'en' => 'Workforce / Employee Data Policy',
          ],
        ],
      ],
    ],
    [
      'id' => 'employee-data',
      'order' => 937,
      'category' => 'privacy',
      'term' => [
        'de' => 'Employee Data',
        'en' => 'Employee Data',
      ],
      'aliases' => [
        'HR Data',
        'Workforce Data',
        'Mitarbeiterdaten',
      ],
      'definition' => [
        'de' => 'Personenbezogene Beschäftigtendaten — eigene Policies, Mitbestimmung und Purpose Limits.',
        'en' => 'Personal employee data — own policies, co-determination, and purpose limits.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'workforce-policy',
          'label' => [
            'de' => 'Workforce / Employee Data Policy',
            'en' => 'Workforce / Employee Data Policy',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'purpose-limitation',
          'label' => [
            'de' => 'Purpose Limitation',
            'en' => 'Purpose Limitation',
          ],
        ],
      ],
    ],
    [
      'id' => 'jit-access',
      'order' => 1089,
      'category' => 'security',
      'term' => [
        'de' => 'Just-in-Time Access',
        'en' => 'Just-in-Time Access',
      ],
      'aliases' => [
        'JIT Access',
        'Time-Bound Access',
      ],
      'definition' => [
        'de' => 'Zeitlich begrenzte Rechte erst bei Bedarf — reduziert stehende Privilegien.',
        'en' => 'Time-bound rights only when needed — reduces standing privileges.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pam',
          'label' => [
            'de' => 'PAM',
            'en' => 'PAM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'break-glass',
          'label' => [
            'de' => 'Break-Glass Access',
            'en' => 'Break-Glass Access',
          ],
        ],
      ],
    ],
    [
      'id' => 'pam',
      'order' => 1090,
      'category' => 'security',
      'term' => [
        'de' => 'PAM',
        'en' => 'PAM',
      ],
      'aliases' => [
        'Privileged Access Management',
        'Privileged Account Management',
      ],
      'definition' => [
        'de' => 'Management hochprivilegierter Zugänge — Vaulting, Session Control, Recording.',
        'en' => 'Management of highly privileged access — vaulting, session control, recording.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'jit-access',
          'label' => [
            'de' => 'Just-in-Time Access',
            'en' => 'Just-in-Time Access',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'secrets-management',
          'label' => [
            'de' => 'Secrets Management',
            'en' => 'Secrets Management',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'audit-log',
          'label' => [
            'de' => 'Audit Log',
            'en' => 'Audit Log',
          ],
        ],
      ],
    ],
    [
      'id' => 'break-glass',
      'order' => 1091,
      'category' => 'security',
      'term' => [
        'de' => 'Break-Glass Access',
        'en' => 'Break-Glass Access',
      ],
      'aliases' => [
        'Emergency Access',
        'Break Glass',
      ],
      'definition' => [
        'de' => 'Notfallzugriff mit starkem Audit — Ausnahme, kein Dauerzustand.',
        'en' => 'Emergency access with strong audit — exception, not a steady state.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'jit-access',
          'label' => [
            'de' => 'Just-in-Time Access',
            'en' => 'Just-in-Time Access',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'pam',
          'label' => [
            'de' => 'PAM',
            'en' => 'PAM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'audit-log',
          'label' => [
            'de' => 'Audit Log',
            'en' => 'Audit Log',
          ],
        ],
      ],
    ],
    [
      'id' => 'column-masking-policy',
      'order' => 1092,
      'category' => 'security',
      'term' => [
        'de' => 'Column Masking Policy',
        'en' => 'Column Masking Policy',
      ],
      'aliases' => [
        'Masking Policy',
        'Tag-Based Masking',
      ],
      'definition' => [
        'de' => 'Policy, die Spaltenwerte rollenbasiert maskiert — oft an Tags/Classification gebunden.',
        'en' => 'Policy that role-masks column values — often bound to tags/classification.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dynamic-masking',
          'label' => [
            'de' => 'Dynamic Masking',
            'en' => 'Dynamic Masking',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'object-tag',
          'label' => [
            'de' => 'Object Tag',
            'en' => 'Object Tag',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'row-access-policy',
          'label' => [
            'de' => 'Row Access Policy',
            'en' => 'Row Access Policy',
          ],
        ],
      ],
    ],
    [
      'id' => 'tag-based-access',
      'order' => 1093,
      'category' => 'security',
      'term' => [
        'de' => 'Tag-Based Access',
        'en' => 'Tag-Based Access',
      ],
      'aliases' => [
        'Attribute Tag Access',
        'Classification-Based Access',
      ],
      'definition' => [
        'de' => 'Zugriff über Classification-/Object-Tags statt nur Objektnamen — skaliert besser in großen Estates.',
        'en' => 'Access via classification/object tags instead of object names alone — scales better in large estates.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'object-tag',
          'label' => [
            'de' => 'Object Tag',
            'en' => 'Object Tag',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'abac',
          'label' => [
            'de' => 'ABAC',
            'en' => 'ABAC',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-classification',
          'label' => [
            'de' => 'Data Classification',
            'en' => 'Data Classification',
          ],
        ],
      ],
    ],
    [
      'id' => 'ticket-driven',
      'order' => 1430,
      'category' => 'process',
      'term' => [
        'de' => 'Ticket-Driven Delivery',
        'en' => 'Ticket-Driven Delivery',
      ],
      'aliases' => [
        'Request-Driven',
        'Ticket Queue Delivery',
      ],
      'definition' => [
        'de' => 'Arbeit nur über Ticket-Warteschlangen — typischer Antipode zu Product Thinking und Golden Paths.',
        'en' => 'Work only via ticket queues — typical opposite of product thinking and golden paths.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'product-driven',
          'label' => [
            'de' => 'Product-Driven Delivery',
            'en' => 'Product-Driven Delivery',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'product-thinking',
          'label' => [
            'de' => 'Product Thinking',
            'en' => 'Product Thinking',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'golden-path',
          'label' => [
            'de' => 'Golden Path',
            'en' => 'Golden Path',
          ],
        ],
      ],
    ],
    [
      'id' => 'product-driven',
      'order' => 1431,
      'category' => 'process',
      'term' => [
        'de' => 'Product-Driven Delivery',
        'en' => 'Product-Driven Delivery',
      ],
      'aliases' => [
        'Product-Led Data',
        'Outcome-Driven Delivery',
      ],
      'definition' => [
        'de' => 'Lieferung über Produkte, Outcomes und Roadmaps — statt endloser Ad-hoc-Tickets.',
        'en' => 'Delivery via products, outcomes, and roadmaps — instead of endless ad-hoc tickets.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'ticket-driven',
          'label' => [
            'de' => 'Ticket-Driven Delivery',
            'en' => 'Ticket-Driven Delivery',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-roadmap',
          'label' => [
            'de' => 'Data Roadmap',
            'en' => 'Data Roadmap',
          ],
        ],
      ],
    ],
    [
      'id' => 'hero-culture',
      'order' => 1432,
      'category' => 'process',
      'term' => [
        'de' => 'Hero Culture',
        'en' => 'Hero Culture',
      ],
      'aliases' => [
        'Firefighter Culture',
        'Heldenkultur',
      ],
      'definition' => [
        'de' => 'Systeme laufen nur durch individuelle Heldentaten — Bus Factor und Burnout statt Reliability.',
        'en' => 'Systems run only via individual heroics — bus factor and burnout instead of reliability.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'bus-factor',
          'label' => [
            'de' => 'Bus Factor',
            'en' => 'Bus Factor',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'tribal-knowledge',
          'label' => [
            'de' => 'Tribal Knowledge',
            'en' => 'Tribal Knowledge',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'sre',
          'label' => [
            'de' => 'SRE',
            'en' => 'SRE',
          ],
        ],
      ],
    ],
    [
      'id' => 'hub-and-spoke',
      'order' => 1433,
      'category' => 'process',
      'term' => [
        'de' => 'Hub and Spoke',
        'en' => 'Hub and Spoke',
      ],
      'aliases' => [
        'Hub-and-Spoke',
        'Center and Domains',
      ],
      'definition' => [
        'de' => 'Zentrales Hub plus Domain-Spokes — Organisationsmuster für Governance und Plattform.',
        'en' => 'Central hub plus domain spokes — org pattern for governance and platform.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'governance-coe',
          'label' => [
            'de' => 'Governance Center of Excellence',
            'en' => 'Governance Center of Excellence',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'federated-governance',
          'label' => [
            'de' => 'Federated Governance',
            'en' => 'Federated Governance',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'domain-ownership',
          'label' => [
            'de' => 'Domain Ownership',
            'en' => 'Domain Ownership',
          ],
        ],
      ],
    ],
    [
      'id' => 'inner-source',
      'order' => 1434,
      'category' => 'process',
      'term' => [
        'de' => 'Inner Source',
        'en' => 'Inner Source',
      ],
      'aliases' => [
        'Innersource',
        'Internal Open Source',
      ],
      'definition' => [
        'de' => 'Open-Source-Praktiken intern — PRs, Shared Ownership, sichtbare Standards.',
        'en' => 'Open-source practices internally — PRs, shared ownership, visible standards.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'community-of-practice',
          'label' => [
            'de' => 'Community of Practice',
            'en' => 'Community of Practice',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'gitops',
          'label' => [
            'de' => 'GitOps',
            'en' => 'GitOps',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'transformation-as-code',
          'label' => [
            'de' => 'Transformation as Code',
            'en' => 'Transformation as Code',
          ],
        ],
      ],
    ],
    [
      'id' => 'trunk-based',
      'order' => 1435,
      'category' => 'process',
      'term' => [
        'de' => 'Trunk-Based Development',
        'en' => 'Trunk-Based Development',
      ],
      'aliases' => [
        'Trunk Based',
        'Mainline Development',
      ],
      'definition' => [
        'de' => 'Kurze Branches, häufiges Mergen in Trunk — passt zu CI/CD und kleinen Batches.',
        'en' => 'Short branches, frequent merges to trunk — fits CI/CD and small batches.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'feature-flag',
          'label' => [
            'de' => 'Feature Flag',
            'en' => 'Feature Flag',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'wip',
          'label' => [
            'de' => 'WIP',
            'en' => 'WIP',
          ],
        ],
      ],
    ],
    [
      'id' => 'grounding',
      'order' => 1661,
      'category' => 'ai',
      'term' => [
        'de' => 'Grounding',
        'en' => 'Grounding',
      ],
      'aliases' => [
        'Grounded Generation',
        'Evidence Grounding',
      ],
      'definition' => [
        'de' => 'Modellantworten an retrieved/verifizierte Evidenz binden — Kern von RAG gegen Hallucination.',
        'en' => 'Bind model answers to retrieved/verified evidence — core of RAG against hallucination.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation)',
            'en' => 'RAG (Retrieval-Augmented Generation)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'hallucination',
          'label' => [
            'de' => 'Hallucination',
            'en' => 'Hallucination',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'chunking',
          'label' => [
            'de' => 'Chunking',
            'en' => 'Chunking',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-rag',
          'label' => [
            'de' => 'RAG',
            'en' => 'RAG',
          ],
        ],
      ],
    ],
    [
      'id' => 'chunking',
      'order' => 1662,
      'category' => 'ai',
      'term' => [
        'de' => 'Chunking',
        'en' => 'Chunking',
      ],
      'aliases' => [
        'Document Chunking',
        'Text Splitting',
      ],
      'definition' => [
        'de' => 'Zerlegen von Dokumenten in Retrieval-Einheiten — Chunk-Größe steuert Recall und Noise.',
        'en' => 'Splitting documents into retrieval units — chunk size steers recall and noise.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation)',
            'en' => 'RAG (Retrieval-Augmented Generation)',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'embedding',
          'label' => [
            'de' => 'Embedding',
            'en' => 'Embedding',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'vector-search',
          'label' => [
            'de' => 'Vector Search',
            'en' => 'Vector Search',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'prepare-metadata-for-ai-rag-and-model-training',
          'label' => [
            'de' => 'Metadaten für AI/RAG vorbereiten',
            'en' => 'Prepare metadata for AI/RAG',
          ],
        ],
      ],
    ],
    [
      'id' => 'vector-search',
      'order' => 1663,
      'category' => 'ai',
      'term' => [
        'de' => 'Vector Search',
        'en' => 'Vector Search',
      ],
      'aliases' => [
        'Semantic Search',
        'ANN Search',
        'Similarity Search',
      ],
      'definition' => [
        'de' => 'Ähnlichkeitssuche über Embeddings — Basis für RAG und Discovery in Metadata/Content.',
        'en' => 'Similarity search over embeddings — basis for RAG and discovery in metadata/content.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'vector-store',
          'label' => [
            'de' => 'Vector Store',
            'en' => 'Vector Store',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'embedding',
          'label' => [
            'de' => 'Embedding',
            'en' => 'Embedding',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation)',
            'en' => 'RAG (Retrieval-Augmented Generation)',
          ],
        ],
      ],
    ],
    [
      'id' => 'context-window',
      'order' => 1664,
      'category' => 'ai',
      'term' => [
        'de' => 'Context Window',
        'en' => 'Context Window',
      ],
      'aliases' => [
        'Context Length',
        'Token Window',
      ],
      'definition' => [
        'de' => 'Maximale Token-Menge, die ein Modell auf einmal sieht — limitiert Prompt, History und retrieved Chunks.',
        'en' => 'Max tokens a model sees at once — limits prompt, history, and retrieved chunks.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'llm',
          'label' => [
            'de' => 'LLM',
            'en' => 'LLM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'chunking',
          'label' => [
            'de' => 'Chunking',
            'en' => 'Chunking',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => [
            'de' => 'RAG (Retrieval-Augmented Generation)',
            'en' => 'RAG (Retrieval-Augmented Generation)',
          ],
        ],
      ],
    ],
    [
      'id' => 'tool-calling',
      'order' => 1665,
      'category' => 'ai',
      'term' => [
        'de' => 'Tool Calling',
        'en' => 'Tool Calling',
      ],
      'aliases' => [
        'Function Calling',
        'Tool Use',
      ],
      'definition' => [
        'de' => 'LLM wählt und ruft Tools/APIs auf — braucht Guardrails, Auth und Observability.',
        'en' => 'LLM selects and calls tools/APIs — needs guardrails, auth, and observability.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'ai-agent',
          'label' => [
            'de' => 'AI Agent',
            'en' => 'AI Agent',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'human-in-the-loop',
          'label' => [
            'de' => 'Human-in-the-Loop',
            'en' => 'Human-in-the-Loop',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-agents',
          'label' => [
            'de' => 'KI-Agenten',
            'en' => 'AI agents',
          ],
        ],
      ],
    ],
    [
      'id' => 'model-card',
      'order' => 1666,
      'category' => 'ai',
      'term' => [
        'de' => 'Model Card',
        'en' => 'Model Card',
      ],
      'aliases' => [
        'Model Documentation',
        'Model Factsheet',
      ],
      'definition' => [
        'de' => 'Standardisierte Doku zu Zweck, Daten, Limits und Risiken eines Modells — Governance-Artefakt.',
        'en' => 'Standardized docs on purpose, data, limits, and risks of a model — governance artifact.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'model-registry',
          'label' => [
            'de' => 'Model Registry',
            'en' => 'Model Registry',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ai-eval',
          'label' => [
            'de' => 'AI Evaluation',
            'en' => 'AI Evaluation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'training-data',
          'label' => [
            'de' => 'Training Data',
            'en' => 'Training Data',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'ai-gov',
          'label' => [
            'de' => 'AI-Governance',
            'en' => 'AI governance',
          ],
        ],
      ],
    ],
    [
      'id' => 'data-drift',
      'order' => 1667,
      'category' => 'ai',
      'term' => [
        'de' => 'Data Drift',
        'en' => 'Data Drift',
      ],
      'aliases' => [
        'Feature Drift',
        'Input Drift',
      ],
      'definition' => [
        'de' => 'Veränderung der Input-Verteilungen gegenüber Training — Monitoring und Retraining nötig.',
        'en' => 'Shift in input distributions vs. training — needs monitoring and retraining.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'concept-drift',
          'label' => [
            'de' => 'Concept Drift',
            'en' => 'Concept Drift',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mlops',
          'label' => [
            'de' => 'MLOps',
            'en' => 'MLOps',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'data-observability',
          'label' => [
            'de' => 'Data Observability',
            'en' => 'Data Observability',
          ],
        ],
      ],
    ],
    [
      'id' => 'concept-drift',
      'order' => 1668,
      'category' => 'ai',
      'term' => [
        'de' => 'Concept Drift',
        'en' => 'Concept Drift',
      ],
      'aliases' => [
        'Label Drift',
        'Relationship Drift',
      ],
      'definition' => [
        'de' => 'Änderung der Beziehung Input→Target — Model Performance sinkt trotz „gleicher“ Features.',
        'en' => 'Change in the input→target relationship — model performance drops despite “same” features.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'data-drift',
          'label' => [
            'de' => 'Data Drift',
            'en' => 'Data Drift',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ai-eval',
          'label' => [
            'de' => 'AI Evaluation',
            'en' => 'AI Evaluation',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'mlops',
          'label' => [
            'de' => 'MLOps',
            'en' => 'MLOps',
          ],
        ],
      ],
    ],
    [
      'id' => 'lora',
      'order' => 1669,
      'category' => 'ai',
      'term' => [
        'de' => 'LoRA',
        'en' => 'LoRA',
      ],
      'aliases' => [
        'Low-Rank Adaptation',
        'QLoRA',
      ],
      'definition' => [
        'de' => 'Parameter-effizientes Fine-Tuning — kleinere Adapter statt Full Retrain.',
        'en' => 'Parameter-efficient fine-tuning — smaller adapters instead of full retrain.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'fine-tuning',
          'label' => [
            'de' => 'Fine-Tuning',
            'en' => 'Fine-Tuning',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'llm',
          'label' => [
            'de' => 'LLM',
            'en' => 'LLM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'model-registry',
          'label' => [
            'de' => 'Model Registry',
            'en' => 'Model Registry',
          ],
        ],
      ],
    ],
    [
      'id' => 'structured-output',
      'order' => 1670,
      'category' => 'ai',
      'term' => [
        'de' => 'Structured Output',
        'en' => 'Structured Output',
      ],
      'aliases' => [
        'JSON Mode',
        'Schema-Constrained Output',
      ],
      'definition' => [
        'de' => 'Modellantwort in festem Schema (JSON etc.) — erleichtert Automation und Validierung.',
        'en' => 'Model answer in a fixed schema (JSON etc.) — eases automation and validation.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'llm',
          'label' => [
            'de' => 'LLM',
            'en' => 'LLM',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'tool-calling',
          'label' => [
            'de' => 'Tool Calling',
            'en' => 'Tool Calling',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
      ],
    ],
    [
      'id' => 'dmbok',
      'order' => 1680,
      'category' => 'process',
      'term' => [
        'de' => 'DMBOK',
        'en' => 'DMBOK',
      ],
      'aliases' => [
        'DAMA-DMBOK',
        'Data Management Body of Knowledge',
        'DAMA',
      ],
      'definition' => [
        'de' => 'Data Management Body of Knowledge (DAMA): gemeinsamer Fachkanon zu Governance, Metadaten, Qualität, Security und Lifecycle — bei Binom Anschluss über die 8 Pillars, kein Kapitel-Nachbau.',
        'en' => 'Data Management Body of Knowledge (DAMA): shared professional canon for governance, metadata, quality, security, and lifecycle — at Binom bridged via the 8 pillars, not a chapter-by-chapter clone.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'cdmp',
          'label' => [
            'de' => 'CDMP',
            'en' => 'CDMP',
          ],
        ],
        [
          'type' => 'glossary',
          'id' => 'dcam',
          'label' => [
            'de' => 'DCAM',
            'en' => 'DCAM',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'eight-pillars',
          'label' => [
            'de' => 'Die 8 Säulen der Data Governance',
            'en' => 'The 8 Pillars of Data Governance',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'compliance.roadmap',
          'label' => [
            'de' => 'Zertifizierungs-Roadmap',
            'en' => 'Certification roadmap',
          ],
        ],
      ],
    ],
    [
      'id' => 'cdmp',
      'order' => 1690,
      'category' => 'process',
      'term' => [
        'de' => 'CDMP',
        'en' => 'CDMP',
      ],
      'aliases' => [
        'Certified Data Management Professional',
        'DAMA CDMP',
      ],
      'definition' => [
        'de' => 'Zertifizierung von DAMA International zum DMBOK-Körperwissen — gemeinsame Fachsprache; Praxisstart bei Binom über Pillars, Hub und Tools.',
        'en' => 'DAMA International certification for DMBOK body knowledge — shared professional language; practical start at Binom via pillars, hub, and tools.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dmbok',
          'label' => [
            'de' => 'DMBOK',
            'en' => 'DMBOK',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'eight-pillars',
          'label' => [
            'de' => 'Die 8 Säulen der Data Governance',
            'en' => 'The 8 Pillars of Data Governance',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'compliance.roadmap',
          'label' => [
            'de' => 'Zertifizierungs-Roadmap',
            'en' => 'Certification roadmap',
          ],
        ],
      ],
    ],
    [
      'id' => 'dcam',
      'order' => 1700,
      'category' => 'process',
      'term' => [
        'de' => 'DCAM',
        'en' => 'DCAM',
      ],
      'aliases' => [
        'Data Management Capability Assessment Model',
      ],
      'definition' => [
        'de' => 'Reifegrad-/Assessment-Modell für Data-Management-Programme — misst Fähigkeit; Binom-Pillars und Advisor helfen Lücken und Artefakte zu finden, ersetzen aber kein DCAM-Assessment.',
        'en' => 'Maturity/assessment model for data management programs — measures capability; Binom pillars and advisor help find gaps and artifacts, but do not replace a DCAM assessment.',
      ],
      'related' => [
        [
          'type' => 'glossary',
          'id' => 'dmbok',
          'label' => [
            'de' => 'DMBOK',
            'en' => 'DMBOK',
          ],
        ],
        [
          'type' => 'story',
          'id' => 'eight-pillars',
          'label' => [
            'de' => 'Die 8 Säulen der Data Governance',
            'en' => 'The 8 Pillars of Data Governance',
          ],
        ],
        [
          'type' => 'route',
          'route' => 'governance.index',
          'label' => [
            'de' => 'Governance Hub',
            'en' => 'Governance Hub',
          ],
        ],
      ],
    ],
  ],
];

$waveFiles = [
    __DIR__.'/glossary-buzzwords-wave2.php',
    __DIR__.'/glossary-buzzwords-wave3.php',
    __DIR__.'/glossary-buzzwords-wave4.php',
    __DIR__.'/glossary-buzzwords-wave5.php',
    __DIR__.'/glossary-buzzwords-wave6.php',
    __DIR__.'/glossary-buzzwords-wave7.php',
];

/** @var list<array<string, mixed>> $existingTerms */
$existingTerms = is_array($config['terms'] ?? null) ? $config['terms'] : [];
foreach ($waveFiles as $waveFile) {
    if (! is_file($waveFile)) {
        continue;
    }
    $wave = require $waveFile;
    if (is_array($wave) && $wave !== []) {
        $existingTerms = array_merge($existingTerms, array_values($wave));
    }
}
$config['terms'] = $existingTerms;

return $config;