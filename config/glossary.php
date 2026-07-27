<?php

/**
 * Glossary Hub — shared governance vocabulary with links into stories and tools.
 *
 * Readable definitions, not a PureView JSON generator.
 */
return [
  'categories' => 
  [
    'roles' => 
    [
      'de' => 'Rollen',
      'en' => 'Roles',
    ],
    'data' => 
    [
      'de' => 'Daten & Produkte',
      'en' => 'Data & products',
    ],
    'architecture' => 
    [
      'de' => 'Architektur',
      'en' => 'Architecture',
    ],
    'modeling' => 
    [
      'de' => 'Modellierung',
      'en' => 'Modeling',
    ],
    'quality' => 
    [
      'de' => 'Qualität',
      'en' => 'Quality',
    ],
    'privacy' => 
    [
      'de' => 'Privacy & Schutz',
      'en' => 'Privacy & protection',
    ],
    'security' => 
    [
      'de' => 'Zugriff & Security',
      'en' => 'Access & security',
    ],
    'metadata' => 
    [
      'de' => 'Metadaten',
      'en' => 'Metadata',
    ],
    'process' => 
    [
      'de' => 'Prozess',
      'en' => 'Process',
    ],
    'ai' => 
    [
      'de' => 'KI & ML',
      'en' => 'AI & ML',
    ],
  ],
  'terms' => 
  [
    0 => 
    [
      'id' => 'data-steward',
      'order' => 10,
      'category' => 'roles',
      'term' => 
      [
        'de' => 'Data Steward',
        'en' => 'Data Steward',
      ],
      'aliases' => 
      [
        0 => 'Steward',
        1 => 'Data Stewardship',
      ],
      'definition' => 
      [
        'de' => 'Operative Verantwortung für Definition, Qualität und Nutzung eines Datenbereichs — nicht nur Dokumentation.',
        'en' => 'Operational ownership for definition, quality, and use of a data domain — not documentation alone.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => 
          [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-architect',
          'label' => 
          [
            'de' => 'Data Architect',
            'en' => 'Data Architect',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'stewardship-capacity',
          'label' => 
          [
            'de' => 'Stewardship staffen',
            'en' => 'Staffing stewardship',
          ],
        ],
        3 => 
        [
          'type' => 'story',
          'id' => 'data-ownership-stewardship',
          'label' => 
          [
            'de' => 'Ownership & Stewardship',
            'en' => 'Ownership & stewardship',
          ],
        ],
        4 => 
        [
          'type' => 'series',
          'id' => 'roles-hub',
          'label' => 
          [
            'de' => 'Roles and Decision Rights',
            'en' => 'Roles and decision rights',
          ],
        ],
        5 => 
        [
          'type' => 'path',
          'id' => 'governance-foundations',
          'label' => 
          [
            'de' => 'Governance Foundations',
            'en' => 'Governance foundations',
          ],
        ],
        6 => 
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => ['slug' => 'steward'],
          'label' =>
          [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    1 => 
    [
      'id' => 'data-owner',
      'order' => 20,
      'category' => 'roles',
      'term' => 
      [
        'de' => 'Data Owner',
        'en' => 'Data Owner',
      ],
      'aliases' => 
      [
        0 => 'Owner',
        1 => 'Business Owner',
      ],
      'definition' => 
      [
        'de' => 'Fachliche Entscheidungsinstanz für Zweck, Zugriffsregeln und Freigaben eines Datenprodukts oder einer Domäne.',
        'en' => 'Business decision-maker for purpose, access rules, and approvals of a data product or domain.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => 
          [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-consumer',
          'label' => 
          [
            'de' => 'Data Consumer',
            'en' => 'Data Consumer',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'raci',
          'label' => 
          [
            'de' => 'RACI',
            'en' => 'RACI',
          ],
        ],
        3 => 
        [
          'type' => 'story',
          'id' => 'data-product-owner-vs-data-owner',
          'label' => 
          [
            'de' => 'Product Owner vs Owner vs Steward',
            'en' => 'Product Owner vs Owner vs Steward',
          ],
        ],
        4 => 
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => 
          [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
        5 => 
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => ['slug' => 'owner'],
          'label' =>
          [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    2 => 
    [
      'id' => 'data-architect',
      'order' => 25,
      'category' => 'roles',
      'term' => 
      [
        'de' => 'Data Architect',
        'en' => 'Data Architect',
      ],
      'aliases' => 
      [
        0 => 'Architect',
        1 => 'Analytics Architect',
        2 => 'Solution Architect (Data]',
      ],
      'definition' => 
      [
        'de' => 'Verantwortet Grain, Modellkonsistenz und Contracts — damit Domänen und Marts zusammenpassen, ohne Owner oder Steward zu ersetzen.',
        'en' => 'Owns grain, model consistency, and contracts — so domains and marts fit together without replacing owner or steward.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => 
          [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => 
          [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => 
          [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
        3 => 
        [
          'type' => 'story',
          'id' => 'data-architect-role',
          'label' => 
          [
            'de' => 'Die Rolle Data Architect',
            'en' => 'The Data Architect role',
          ],
        ],
        4 => 
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => 
          [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
        5 => 
        [
          'type' => 'path',
          'id' => 'modernize-warehouse',
          'label' => 
          [
            'de' => 'Warehouse modernisieren',
            'en' => 'Modernize the warehouse',
          ],
        ],
        6 => 
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => ['slug' => 'architect'],
          'label' =>
          [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    3 => 
    [
      'id' => 'data-custodian',
      'order' => 26,
      'category' => 'roles',
      'term' => 
      [
        'de' => 'Data Custodian',
        'en' => 'Data Custodian',
      ],
      'aliases' => 
      [
        0 => 'Custodian',
        1 => 'Technical Custodian',
      ],
      'definition' => 
      [
        'de' => 'Technische Obhut über Systeme und Speicherorte — Zugriffspflege, Backups, Laufzeit — meist Platform/IT, nicht fachliche Definition.',
        'en' => 'Technical custody of systems and storage — access upkeep, backups, runtime — usually platform/IT, not business definition.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => 
          [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => 
          [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'data-ownership-stewardship',
          'label' => 
          [
            'de' => 'Ownership & Stewardship',
            'en' => 'Ownership & stewardship',
          ],
        ],
        3 => 
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => 
          [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
        4 => 
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => ['slug' => 'custodian'],
          'label' =>
          [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    4 => 
    [
      'id' => 'data-consumer',
      'order' => 27,
      'category' => 'roles',
      'term' => 
      [
        'de' => 'Data Consumer',
        'en' => 'Data Consumer',
      ],
      'aliases' => 
      [
        0 => 'Consumer',
        1 => 'Analyst',
        2 => 'Report User',
      ],
      'definition' => 
      [
        'de' => 'Nutzt Datenprodukte für Entscheidungen oder Reports — meldet Qualitätsprobleme, entscheidet aber nicht allein über Definition und Zugriff.',
        'en' => 'Uses data products for decisions or reports — raises quality issues but does not alone decide definition and access.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => 
          [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => 
          [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'data-product-owner-vs-data-owner',
          'label' => 
          [
            'de' => 'Product Owner vs Owner vs Steward',
            'en' => 'Product Owner vs Owner vs Steward',
          ],
        ],
        3 => 
        [
          'type' => 'story',
          'id' => 'one-data-product-multiple-consumers',
          'label' => 
          [
            'de' => 'Ein Data Product, viele Consumer',
            'en' => 'One data product, many consumers',
          ],
        ],
        4 => 
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => ['slug' => 'consumer'],
          'label' =>
          [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    5 => 
    [
      'id' => 'data-product-owner',
      'order' => 28,
      'category' => 'roles',
      'term' => 
      [
        'de' => 'Data Product Owner',
        'en' => 'Data Product Owner',
      ],
      'aliases' => 
      [
        0 => 'Product Owner (Data]',
        1 => 'DPO (Data]',
      ],
      'definition' => 
      [
        'de' => 'Verantwortet Lebenszyklus, Prioritäten und Consumer-Nutzen eines Datenprodukts — getrennt vom fachlichen Domain Owner und vom Steward.',
        'en' => 'Owns product lifecycle, priorities, and consumer value — distinct from the domain Owner and the Steward.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => 
          [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => 
          [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'data-product-owner-vs-data-owner',
          'label' => 
          [
            'de' => 'Product Owner vs Owner vs Steward',
            'en' => 'Product Owner vs Owner vs Steward',
          ],
        ],
        3 => 
        [
          'type' => 'route',
          'route' => 'roles.show',
          'params' => ['slug' => 'product-owner'],
          'label' =>
          [
            'de' => 'Rolle erkunden',
            'en' => 'Explore role',
          ],
        ],
      ],
    ],
    6 => 
    [
      'id' => 'analytics-engineer',
      'order' => 29,
      'category' => 'roles',
      'term' => 
      [
        'de' => 'Analytics Engineer',
        'en' => 'Analytics Engineer',
      ],
      'aliases' => 
      [
        0 => 'AE',
        1 => 'Analytics Engineering',
      ],
      'definition' => 
      [
        'de' => 'Baut vertrauenswürdige Transforms, Tests und Docs (oft mit dbt] zwischen Plattform und BI.',
        'en' => 'Builds trusted transforms, tests, and docs (often with dbt] between platform and BI.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => 
          [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'dbt-role',
          'label' => 
          [
            'de' => 'Die Rolle dbt',
            'en' => 'The dbt role',
          ],
        ],
        3 => 
        [
          'type' => 'path',
          'id' => 'cert-dbt-analytics-engineer',
          'label' => 
          [
            'de' => 'dbt Analytics Engineer',
            'en' => 'dbt Analytics Engineer',
          ],
        ],
      ],
    ],
    7 => 
    [
      'id' => 'governance-coe',
      'order' => 31,
      'category' => 'roles',
      'term' => 
      [
        'de' => 'Governance Center of Excellence',
        'en' => 'Governance Center of Excellence',
      ],
      'aliases' => 
      [
        0 => 'CoE',
        1 => 'Data Governance CoE',
      ],
      'definition' => 
      [
        'de' => 'Zentrale Enablement-Instanz für Standards, Cadence, Tooling und domänenübergreifende Eskalation.',
        'en' => 'Central enablement for standards, cadence, tooling, and cross-domain escalation.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'governance-lead',
          'label' => 
          [
            'de' => 'Governance Lead',
            'en' => 'Governance Lead',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'operating-model',
          'label' => 
          [
            'de' => 'Operating Model',
            'en' => 'Operating Model',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'governance-coe',
          'label' => 
          [
            'de' => 'Governance CoE',
            'en' => 'Governance CoE',
          ],
        ],
        3 => 
        [
          'type' => 'path',
          'id' => 'governance-foundations',
          'label' => 
          [
            'de' => 'Governance Foundations',
            'en' => 'Governance foundations',
          ],
        ],
      ],
    ],
    8 => 
    [
      'id' => 'governance-lead',
      'order' => 32,
      'category' => 'roles',
      'term' => 
      [
        'de' => 'Governance Lead',
        'en' => 'Governance Lead',
      ],
      'aliases' => 
      [
        0 => 'DG Lead',
        1 => 'Data Governance Lead',
      ],
      'definition' => 
      [
        'de' => 'Verantwortlich für das Governance-Operating-Model und Sponsor-Evidence — nicht für jedes Ticket.',
        'en' => 'Accountable for the governance operating model and sponsor evidence — not every ticket.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'governance-coe',
          'label' => 
          [
            'de' => 'Governance Center of Excellence',
            'en' => 'Governance Center of Excellence',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'decision-rights',
          'label' => 
          [
            'de' => 'Decision Rights',
            'en' => 'Decision Rights',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'governance-coe',
          'label' => 
          [
            'de' => 'Governance CoE',
            'en' => 'Governance CoE',
          ],
        ],
        3 => 
        [
          'type' => 'route',
          'route' => 'roles.index',
          'label' => 
          [
            'de' => 'Roles Hub',
            'en' => 'Roles hub',
          ],
        ],
      ],
    ],
    9 => 
    [
      'id' => 'data-product',
      'order' => 100,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Data Product',
        'en' => 'Data Product',
      ],
      'aliases' => 
      [
        0 => 'Datenprodukt',
        1 => 'Product Thinking',
      ],
      'definition' => 
      [
        'de' => 'Konsumierbares, versioniertes Datenangebot mit klarer Zielgruppe, Verträgen (SLA/SLO], Ownership und dokumentierter Qualität.',
        'en' => 'Consumable, versioned data offering with a clear audience, contracts (SLA/SLO], ownership, and documented quality.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => 
          [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'data-product-owner-vs-data-owner',
          'label' => 
          [
            'de' => 'Product Owner vs Owner vs Steward',
            'en' => 'Product Owner vs Owner vs Steward',
          ],
        ],
        2 => 
        [
          'type' => 'series',
          'id' => 'building-modern-data-warehouse',
          'label' => 
          [
            'de' => 'Modern Data Warehouse',
            'en' => 'Modern data warehouse',
          ],
        ],
        3 => 
        [
          'type' => 'route',
          'route' => 'roles.index',
          'label' => 
          [
            'de' => 'Roles Hub',
            'en' => 'Roles hub',
          ],
        ],
      ],
    ],
    10 => 
    [
      'id' => 'grain',
      'order' => 110,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Grain',
        'en' => 'Grain',
      ],
      'aliases' => 
      [
        0 => 'Körnung',
        1 => 'Granularity',
      ],
      'definition' => 
      [
        'de' => 'Die kleinste fachliche Aussageeinheit einer Tabelle oder eines Marts (z. B. eine Bestellung, ein Tag, ein Vertrag].',
        'en' => 'The smallest business statement unit of a table or mart (e.g. one order, one day, one contract].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => 
          [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => 
          [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
      ],
    ],
    11 => 
    [
      'id' => 'semantic-layer',
      'order' => 120,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Semantic Layer',
        'en' => 'Semantic Layer',
      ],
      'aliases' => 
      [
        0 => 'Metrics Layer',
        1 => 'Semantische Schicht',
      ],
      'definition' => 
      [
        'de' => 'Governte fachliche Bedeutung und Kennzahlen oberhalb physischer Tabellen.',
        'en' => 'Governed business meaning and metrics above physical tables.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => 
          [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'kpi-governance',
          'label' => 
          [
            'de' => 'KPI Governance',
            'en' => 'KPI Governance',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'keeping-business-logic-outside-bi-apps',
          'label' => 
          [
            'de' => 'Business-Logik außerhalb der BI-Apps',
            'en' => 'Keeping business logic outside BI apps',
          ],
        ],
        3 => 
        [
          'type' => 'path',
          'id' => 'trusted-metrics',
          'label' => 
          [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted metrics',
          ],
        ],
      ],
    ],
    12 => 
    [
      'id' => 'semantic-model',
      'order' => 130,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Semantic Model',
        'en' => 'Semantic Model',
      ],
      'aliases' => 
      [
        0 => 'BI Semantic Model',
      ],
      'definition' => 
      [
        'de' => 'Toolgebundenes semantisches Artefakt (z. B. Power-BI-Modell, Qlik-App-Modell].',
        'en' => 'Tool-bound semantic artifact (e.g. Power BI model, Qlik app model].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => 
          [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'consumption-contract',
          'label' => 
          [
            'de' => 'Consumption Contract',
            'en' => 'Consumption Contract',
          ],
        ],
      ],
    ],
    13 => 
    [
      'id' => 'data-domain',
      'order' => 140,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Data Domain',
        'en' => 'Data Domain',
      ],
      'aliases' => 
      [
        0 => 'Domain',
        1 => 'Datendomäne',
      ],
      'definition' => 
      [
        'de' => 'Abgegrenzter fachlicher Bereich mit Ownership, Produkten und Decision Rights.',
        'en' => 'Bounded business area of ownership, products, and decision rights.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => 
          [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-mesh',
          'label' => 
          [
            'de' => 'Data Mesh',
            'en' => 'Data Mesh',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'federated-governance',
          'label' => 
          [
            'de' => 'Federated Governance',
            'en' => 'Federated Governance',
          ],
        ],
      ],
    ],
    14 => 
    [
      'id' => 'metric-store',
      'order' => 150,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Metric / KPI Store',
        'en' => 'Metric / KPI Store',
      ],
      'aliases' => 
      [
        0 => 'Certified KPI Core',
        1 => 'Metrics Store',
      ],
      'definition' => 
      [
        'de' => 'Autoritative Ablage zertifizierter Kennzahlendefinitionen und Implementierungen.',
        'en' => 'Authoritative store of certified metric definitions and implementations.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'kpi-governance',
          'label' => 
          [
            'de' => 'KPI Governance',
            'en' => 'KPI Governance',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'semantic-layer',
          'label' => 
          [
            'de' => 'Semantic Layer',
            'en' => 'Semantic Layer',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'kpi-metric-governance',
          'label' => 
          [
            'de' => 'KPI Metric Governance',
            'en' => 'KPI metric governance',
          ],
        ],
        3 => 
        [
          'type' => 'path',
          'id' => 'trusted-metrics',
          'label' => 
          [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted metrics',
          ],
        ],
      ],
    ],
    15 => 
    [
      'id' => 'product-certification',
      'order' => 160,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Data Product Certification',
        'en' => 'Data Product Certification',
      ],
      'aliases' => 
      [
        0 => 'Certified Data Product',
        1 => 'Zertifiziertes Datenprodukt',
      ],
      'definition' => 
      [
        'de' => 'Expliziter Vertrauensstatus: Contract, DQ und Ownership sind erfüllt.',
        'en' => 'Explicit trust status that a product meets contract, DQ, and ownership bars.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => 
          [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => 
          [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
      ],
    ],
    16 => 
    [
      'id' => 'product-versioning',
      'order' => 170,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Data Product Versioning',
        'en' => 'Data Product Versioning',
      ],
      'aliases' => 
      [
        0 => 'Versioning',
        1 => 'Produktversionierung',
      ],
      'definition' => 
      [
        'de' => 'Explizite Versionen und Kompatibilitätsregeln für Produkt-Interfaces.',
        'en' => 'Explicit versions and compatibility rules for product interfaces.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'breaking-change',
          'label' => 
          [
            'de' => 'Breaking Change',
            'en' => 'Breaking Change',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => 
          [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
      ],
    ],
    17 => 
    [
      'id' => 'breaking-change',
      'order' => 180,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Breaking Change',
        'en' => 'Breaking Change',
      ],
      'aliases' => 
      [
        0 => 'Non-Compatible Change',
        1 => 'Inkompatible Änderung',
      ],
      'definition' => 
      [
        'de' => 'Interface-/Schema-/Semantik-Änderung, die bestehende Consumer ungültig macht.',
        'en' => 'Interface, schema, or semantics change that invalidates existing consumers.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => 
          [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'product-versioning',
          'label' => 
          [
            'de' => 'Data Product Versioning',
            'en' => 'Data Product Versioning',
          ],
        ],
      ],
    ],
    18 => 
    [
      'id' => 'sla-slo',
      'order' => 190,
      'category' => 'data',
      'term' => 
      [
        'de' => 'SLA / SLO',
        'en' => 'SLA / SLO',
      ],
      'aliases' => 
      [
        0 => 'Freshness SLA',
        1 => 'Service Level',
      ],
      'definition' => 
      [
        'de' => 'Serviceversprechen (SLA] und messbare Zuverlässigkeitsziele (SLO], z. B. Freshness.',
        'en' => 'Service promises (SLA] and measurable reliability targets (SLO], e.g. freshness.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'freshness',
          'label' => 
          [
            'de' => 'Freshness',
            'en' => 'Freshness',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => 
          [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
      ],
    ],
    19 => 
    [
      'id' => 'reverse-etl',
      'order' => 195,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Reverse ETL / Activation',
        'en' => 'Reverse ETL / Activation',
      ],
      'aliases' => 
      [
        0 => 'Activation',
        1 => 'Reverse ETL',
      ],
      'definition' => 
      [
        'de' => 'Kuratierte Warehouse-Daten zurück in operative Tools pushen.',
        'en' => 'Push curated warehouse data back into operational tools.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => 
          [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => 
          [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
      ],
    ],
    20 => 
    [
      'id' => 'ci-cd-data',
      'order' => 198,
      'category' => 'data',
      'term' => 
      [
        'de' => 'Data CI/CD',
        'en' => 'Data CI/CD',
      ],
      'aliases' => 
      [
        0 => 'Analytics CI/CD',
        1 => 'CI/CD für Daten',
      ],
      'definition' => 
      [
        'de' => 'Automatisiertes Testen und Deployen von Modellen, Contracts und Quality Checks.',
        'en' => 'Automated test and deploy of models, contracts, and quality checks.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => 
          [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => 
          [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'dq-gate',
          'label' => 
          [
            'de' => 'DQ Gate',
            'en' => 'DQ Gate',
          ],
        ],
      ],
    ],
    21 => 
    [
      'id' => 'medallion-architecture',
      'order' => 200,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Medallion Architecture',
        'en' => 'Medallion Architecture',
      ],
      'aliases' => 
      [
        0 => 'Medallion',
        1 => 'Bronze-Silver-Gold',
      ],
      'definition' => 
      [
        'de' => 'Bronze/Silver/Gold als technische Zonen — nützliche Labels, aber kein vollständiges logisches Warehouse-Modell.',
        'en' => 'Bronze/Silver/Gold as technical zones — useful labels, not a full logical warehouse model.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => 
          [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => 
          [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => 
          [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
        3 => 
        [
          'type' => 'path',
          'id' => 'modernize-warehouse',
          'label' => 
          [
            'de' => 'Warehouse modernisieren',
            'en' => 'Modernize the warehouse',
          ],
        ],
      ],
    ],
    22 => 
    [
      'id' => 'landing-raw-layer',
      'order' => 210,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Landing / RAW Layer',
        'en' => 'Landing / RAW Layer',
      ],
      'aliases' => 
      [
        0 => 'Landing',
        1 => 'RAW',
        2 => 'Raw Layer',
        3 => 'Staging',
      ],
      'definition' => 
      [
        'de' => 'Ingest wie empfangen: Quellpayload und Ladeidentität mit minimaler semantischer Änderung bewahren.',
        'en' => 'Ingest as received: preserve source payload and load identity with minimal semantic change.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'conform-layer',
          'label' => 
          [
            'de' => 'Conform / Standardized Layer',
            'en' => 'Conform / Standardized Layer',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'medallion-architecture',
          'label' => 
          [
            'de' => 'Medallion Architecture',
            'en' => 'Medallion Architecture',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => 
          [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
        3 => 
        [
          'type' => 'story',
          'id' => 'automatic-raw-generation-using-dbt-macros',
          'label' => 
          [
            'de' => 'Automatische RAW-Generierung',
            'en' => 'Automatic RAW generation',
          ],
        ],
      ],
    ],
    23 => 
    [
      'id' => 'conform-layer',
      'order' => 220,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Conform / Standardized Layer',
        'en' => 'Conform / Standardized Layer',
      ],
      'aliases' => 
      [
        0 => 'Standardized',
        1 => 'Validated',
        2 => 'Conform',
      ],
      'definition' => 
      [
        'de' => 'Technische Standardisierung und Validierung vor der fachlichen Identitätsintegration.',
        'en' => 'Technical standardization and validation before business identity integration.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => 
          [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => 
          [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => 
          [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
      ],
    ],
    24 => 
    [
      'id' => 'integrated-core',
      'order' => 230,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Integrated Core',
        'en' => 'Integrated Core',
      ],
      'aliases' => 
      [
        0 => 'Core Layer',
        1 => 'Enterprise Core',
      ],
      'definition' => 
      [
        'de' => 'Geteilte fachliche Entitäten, Beziehungen und Historie über Quellen hinweg.',
        'en' => 'Shared business entities, relationships, and history across sources.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'conform-layer',
          'label' => 
          [
            'de' => 'Conform / Standardized Layer',
            'en' => 'Conform / Standardized Layer',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => 
          [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'golden-record',
          'label' => 
          [
            'de' => 'Golden Record',
            'en' => 'Golden Record',
          ],
        ],
        3 => 
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => 
          [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
      ],
    ],
    25 => 
    [
      'id' => 'mart-layer',
      'order' => 240,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Mart / Business Data Product Layer',
        'en' => 'Mart / Business Data Product Layer',
      ],
      'aliases' => 
      [
        0 => 'Data Mart',
        1 => 'Business Mart',
        2 => 'Mart',
      ],
      'definition' => 
      [
        'de' => 'Zweckgebundene Facts/Dimensions/KPI-Basen für einen definierten Consumer-Zweck.',
        'en' => 'Purpose-bound facts, dimensions, and KPI bases for a defined consumer purpose.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => 
          [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'consumption-contract',
          'label' => 
          [
            'de' => 'Consumption Contract',
            'en' => 'Consumption Contract',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => 
          [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
        3 => 
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => 
          [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
      ],
    ],
    26 => 
    [
      'id' => 'consumption-contract',
      'order' => 250,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Consumption Contract',
        'en' => 'Consumption Contract',
      ],
      'aliases' => 
      [
        0 => 'Semantic Consumption',
        1 => 'Consumption Layer',
      ],
      'definition' => 
      [
        'de' => 'Toolspezifischer Zugriff und Interpretation eines governten Produkts (Views, Semantic Models, Extracts].',
        'en' => 'Tool-specific access and interpretation of a governed product (views, semantic models, extracts].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'semantic-model',
          'label' => 
          [
            'de' => 'Semantic Model',
            'en' => 'Semantic Model',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => 
          [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'beyond-bronze-silver-gold',
          'label' => 
          [
            'de' => 'Mehr als Bronze, Silver und Gold',
            'en' => 'Beyond bronze, silver, and gold',
          ],
        ],
      ],
    ],
    27 => 
    [
      'id' => 'lakehouse',
      'order' => 260,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Lakehouse',
        'en' => 'Lakehouse',
      ],
      'aliases' => 
      [
        0 => 'Fabric Lakehouse',
        1 => 'Lakehouse Platform',
      ],
      'definition' => 
      [
        'de' => 'Offene Tabellenformate plus Warehouse-ähnliche Governance auf geteiltem Lake-Storage.',
        'en' => 'Open table formats plus warehouse-style governance on shared lake storage.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => 
          [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'microsoft-fabric',
          'label' => 
          [
            'de' => 'Microsoft Fabric',
            'en' => 'Microsoft Fabric',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'onelake',
          'label' => 
          [
            'de' => 'OneLake',
            'en' => 'OneLake',
          ],
        ],
      ],
    ],
    28 => 
    [
      'id' => 'data-mesh',
      'order' => 270,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Data Mesh',
        'en' => 'Data Mesh',
      ],
      'aliases' => 
      [
        0 => 'Mesh',
      ],
      'definition' => 
      [
        'de' => 'Domäneneigene Datenprodukte mit federierter Governance — kein Tooling-Checklist.',
        'en' => 'Domain-owned data products with federated governance, not a tooling checklist.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-domain',
          'label' => 
          [
            'de' => 'Data Domain',
            'en' => 'Data Domain',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => 
          [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'federated-governance',
          'label' => 
          [
            'de' => 'Federated Governance',
            'en' => 'Federated Governance',
          ],
        ],
      ],
    ],
    29 => 
    [
      'id' => 'simplest-viable-architecture',
      'order' => 280,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Simplest Viable Architecture',
        'en' => 'Simplest Viable Architecture',
      ],
      'aliases' => 
      [
        0 => 'SVA',
      ],
      'definition' => 
      [
        'de' => 'Geringstmögliche unnötige Komplexität, die die Anforderung noch erfüllt — nicht die wenigsten Boxen.',
        'en' => 'Least unnecessary complexity that still meets the requirement — not fewest boxes.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'choosing-the-simplest-viable-architecture',
          'label' => 
          [
            'de' => 'Einfachste tragfähige Architektur',
            'en' => 'Choosing the simplest viable architecture',
          ],
        ],
        1 => 
        [
          'type' => 'path',
          'id' => 'simplest-viable-stack',
          'label' => 
          [
            'de' => 'Simplest Viable Stack',
            'en' => 'Simplest viable stack',
          ],
        ],
      ],
    ],
    30 => 
    [
      'id' => 'modern-data-warehouse',
      'order' => 290,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Modern Data Warehouse',
        'en' => 'Modern Data Warehouse',
      ],
      'aliases' => 
      [
        0 => 'MDW',
        1 => 'Cloud Data Warehouse',
      ],
      'definition' => 
      [
        'de' => 'Governter Pfad von Quelle zu Produkten und Semantik — nicht nur ein Cloud-Vendor-Rename.',
        'en' => 'Governed path from source to products and semantics — not just a cloud vendor rename.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'series',
          'id' => 'building-modern-data-warehouse',
          'label' => 
          [
            'de' => 'Modern Data Warehouse',
            'en' => 'Modern data warehouse',
          ],
        ],
        1 => 
        [
          'type' => 'path',
          'id' => 'modernize-warehouse',
          'label' => 
          [
            'de' => 'Warehouse modernisieren',
            'en' => 'Modernize the warehouse',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'simplest-viable-architecture',
          'label' => 
          [
            'de' => 'Simplest Viable Architecture',
            'en' => 'Simplest Viable Architecture',
          ],
        ],
      ],
    ],
    31 => 
    [
      'id' => 'greenfield',
      'order' => 300,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Greenfield',
        'en' => 'Greenfield',
      ],
      'aliases' => 
      [
        0 => 'Greenfield Build',
      ],
      'definition' => 
      [
        'de' => 'Warehouse von Grund auf bauen, ohne ein Legacy-Estate zu stranglen.',
        'en' => 'Build-from-scratch warehouse without strangling a legacy estate.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'building-from-scratch',
          'label' => 
          [
            'de' => 'Von Grund auf bauen',
            'en' => 'Building from scratch',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'brownfield',
          'label' => 
          [
            'de' => 'Brownfield',
            'en' => 'Brownfield',
          ],
        ],
      ],
    ],
    32 => 
    [
      'id' => 'brownfield',
      'order' => 310,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Brownfield',
        'en' => 'Brownfield',
      ],
      'aliases' => 
      [
        0 => 'Warehouse Modernization',
      ],
      'definition' => 
      [
        'de' => 'Bestehendes Warehouse/Estate vor Ort modernisieren.',
        'en' => 'Modernize an existing warehouse or estate in place.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'modernizing-an-existing-warehouse',
          'label' => 
          [
            'de' => 'Bestehendes Warehouse modernisieren',
            'en' => 'Modernizing an existing warehouse',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'strangler-pattern',
          'label' => 
          [
            'de' => 'Strangler Pattern',
            'en' => 'Strangler Pattern',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'greenfield',
          'label' => 
          [
            'de' => 'Greenfield',
            'en' => 'Greenfield',
          ],
        ],
      ],
    ],
    33 => 
    [
      'id' => 'strangler-pattern',
      'order' => 320,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Strangler Pattern',
        'en' => 'Strangler Pattern',
      ],
      'aliases' => 
      [
        0 => 'Strangler Fig',
      ],
      'definition' => 
      [
        'de' => 'Legacy-Pfade schrittweise ersetzen, indem neuer Traffic auf den modernen Stack geroutet wird.',
        'en' => 'Incrementally replace legacy paths by routing new traffic to the modern stack.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'brownfield',
          'label' => 
          [
            'de' => 'Brownfield',
            'en' => 'Brownfield',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'modernizing-an-existing-warehouse',
          'label' => 
          [
            'de' => 'Bestehendes Warehouse modernisieren',
            'en' => 'Modernizing an existing warehouse',
          ],
        ],
      ],
    ],
    34 => 
    [
      'id' => 'vertical-slice',
      'order' => 330,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Vertical Slice',
        'en' => 'Vertical Slice',
      ],
      'aliases' => 
      [
        0 => 'Thin Slice',
      ],
      'definition' => 
      [
        'de' => 'End-to-end dünner Pfad (Quelle → Produkt → Consumer] vor horizontalem Plattform-Wildwuchs.',
        'en' => 'End-to-end thin path (source → product → consumer] before horizontal platform sprawl.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'simplest-viable-architecture',
          'label' => 
          [
            'de' => 'Simplest Viable Architecture',
            'en' => 'Simplest Viable Architecture',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'building-from-scratch',
          'label' => 
          [
            'de' => 'Von Grund auf bauen',
            'en' => 'Building from scratch',
          ],
        ],
      ],
    ],
    35 => 
    [
      'id' => 'hybrid-cloud',
      'order' => 335,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Hybrid Cloud',
        'en' => 'Hybrid Cloud',
      ],
      'aliases' => 
      [
        0 => 'Hybrid',
      ],
      'definition' => 
      [
        'de' => 'On-Prem und Cloud als bewusste Architektur — kein temporärer Defekt.',
        'en' => 'On-prem and cloud coexistence as a deliberate architecture, not a temporary defect.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'host-vs-cloud',
          'label' => 
          [
            'de' => 'Host vs Cloud',
            'en' => 'Host vs cloud',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'cloud-hosting',
          'label' => 
          [
            'de' => 'Cloud Hosting',
            'en' => 'Cloud hosting',
          ],
        ],
      ],
    ],
    36 => 
    [
      'id' => 'elt',
      'order' => 340,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'ELT',
        'en' => 'ELT',
      ],
      'aliases' => 
      [
        0 => 'Extract-Load-Transform',
      ],
      'definition' => 
      [
        'de' => 'Zuerst laden, dann in der Plattform transformieren (typisches dbt-Muster].',
        'en' => 'Load first, transform in the platform (typical dbt pattern].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => 
          [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => 
          [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
      ],
    ],
    37 => 
    [
      'id' => 'orchestration',
      'order' => 345,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Orchestration',
        'en' => 'Orchestration',
      ],
      'aliases' => 
      [
        0 => 'Pipeline Orchestration',
        1 => 'Orchestrierung',
      ],
      'definition' => 
      [
        'de' => 'Scheduling und Abhängigkeitsmanagement über Pipelines und Quality Gates.',
        'en' => 'Scheduling and dependency management across pipelines and quality gates.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'dq-gate',
          'label' => 
          [
            'de' => 'DQ Gate',
            'en' => 'DQ Gate',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => 
          [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
      ],
    ],
    38 => 
    [
      'id' => 'dbt',
      'order' => 350,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'dbt',
        'en' => 'dbt',
      ],
      'aliases' => 
      [
        0 => 'Analytics Engineering Tool',
      ],
      'definition' => 
      [
        'de' => 'SQL-first Framework für Transform, Test und Dokumentation im Warehouse/Lakehouse.',
        'en' => 'SQL-first transform, test, and documentation framework in the warehouse/lakehouse.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'dbt-meta',
          'label' => 
          [
            'de' => 'dbt meta',
            'en' => 'dbt meta',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'analytics-engineer',
          'label' => 
          [
            'de' => 'Analytics Engineer',
            'en' => 'Analytics Engineer',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'dbt-role',
          'label' => 
          [
            'de' => 'Die Rolle dbt',
            'en' => 'The dbt role',
          ],
        ],
        3 => 
        [
          'type' => 'story',
          'id' => 'metadata-driven-governance-with-dbt-meta',
          'label' => 
          [
            'de' => 'dbt meta Governance',
            'en' => 'dbt meta governance',
          ],
        ],
        4 => 
        [
          'type' => 'path',
          'id' => 'dq-with-dbt',
          'label' => 
          [
            'de' => 'DQ mit dbt',
            'en' => 'DQ with dbt',
          ],
        ],
      ],
    ],
    39 => 
    [
      'id' => 'microsoft-fabric',
      'order' => 360,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Microsoft Fabric',
        'en' => 'Microsoft Fabric',
      ],
      'aliases' => 
      [
        0 => 'Fabric',
      ],
      'definition' => 
      [
        'de' => 'Einheitliche Analytics-SaaS (OneLake, Lakehouse/Warehouse, Power BI, Purview-Touchpoints].',
        'en' => 'Unified analytics SaaS (OneLake, Lakehouse/Warehouse, Power BI, Purview touchpoints].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'onelake',
          'label' => 
          [
            'de' => 'OneLake',
            'en' => 'OneLake',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => 
          [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        2 => 
        [
          'type' => 'path',
          'id' => 'cert-fabric-power-bi',
          'label' => 
          [
            'de' => 'Fabric & Power BI',
            'en' => 'Fabric & Power BI',
          ],
        ],
      ],
    ],
    40 => 
    [
      'id' => 'onelake',
      'order' => 370,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'OneLake',
        'en' => 'OneLake',
      ],
      'aliases' => 
      [
        0 => 'One Lake',
      ],
      'definition' => 
      [
        'de' => 'Logische Lake-Storage-Ebene von Fabric für Lakehouse-/Warehouse-Assets.',
        'en' => 'Fabric’s single logical lake storage plane for Lakehouse and Warehouse assets.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'microsoft-fabric',
          'label' => 
          [
            'de' => 'Microsoft Fabric',
            'en' => 'Microsoft Fabric',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'delta-lake',
          'label' => 
          [
            'de' => 'Delta Lake',
            'en' => 'Delta Lake',
          ],
        ],
      ],
    ],
    41 => 
    [
      'id' => 'delta-lake',
      'order' => 380,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'Delta Lake',
        'en' => 'Delta Lake',
      ],
      'aliases' => 
      [
        0 => 'Delta Tables',
      ],
      'definition' => 
      [
        'de' => 'ACID-Tabellenformat, oft Grundlage von Lakehouse-Cores und DQ-Result-Stores.',
        'en' => 'ACID table format commonly underlying lakehouse cores and DQ result stores.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'lakehouse',
          'label' => 
          [
            'de' => 'Lakehouse',
            'en' => 'Lakehouse',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'onelake',
          'label' => 
          [
            'de' => 'OneLake',
            'en' => 'OneLake',
          ],
        ],
      ],
    ],
    42 => 
    [
      'id' => 'qvd',
      'order' => 390,
      'category' => 'architecture',
      'term' => 
      [
        'de' => 'QVD',
        'en' => 'QVD',
      ],
      'aliases' => 
      [
        0 => 'Qlik Data File',
      ],
      'definition' => 
      [
        'de' => 'Qliks optimiertes spaltenorientiertes Extract-Format für wiederverwendbare Load-Layer.',
        'en' => 'Qlik’s optimized columnar extract format for reusable load layers.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'consumption-contract',
          'label' => 
          [
            'de' => 'Consumption Contract',
            'en' => 'Consumption Contract',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'section-access',
          'label' => 
          [
            'de' => 'Section Access',
            'en' => 'Section Access',
          ],
        ],
      ],
    ],
    43 => 
    [
      'id' => 'dimensional-modeling',
      'order' => 400,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'Dimensional Modeling',
        'en' => 'Dimensional Modeling',
      ],
      'aliases' => 
      [
        0 => 'Dimensional Model',
        1 => 'Dimensionale Modellierung',
      ],
      'definition' => 
      [
        'de' => 'Facts und Dimensions für analytisches Grain und Wiederverwendung organisiert (Kimball-Stil].',
        'en' => 'Facts and dimensions organized for analytics grain and reuse (Kimball-style].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => 
          [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => 
          [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => 
          [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
      ],
    ],
    44 => 
    [
      'id' => 'star-schema',
      'order' => 410,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'Star Schema',
        'en' => 'Star Schema',
      ],
      'aliases' => 
      [
        0 => 'Star',
        1 => 'Sternschema',
      ],
      'definition' => 
      [
        'de' => 'Fact-Tabelle umgeben von denormalisierten Dimensions.',
        'en' => 'Fact table surrounded by denormalized dimensions.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'dimensional-modeling',
          'label' => 
          [
            'de' => 'Dimensional Modeling',
            'en' => 'Dimensional Modeling',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'mart-layer',
          'label' => 
          [
            'de' => 'Mart / Business Data Product Layer',
            'en' => 'Mart / Business Data Product Layer',
          ],
        ],
      ],
    ],
    45 => 
    [
      'id' => 'fact-table',
      'order' => 420,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'Fact Table',
        'en' => 'Fact Table',
      ],
      'aliases' => 
      [
        0 => 'Facts',
        1 => 'Faktentabelle',
      ],
      'definition' => 
      [
        'de' => 'Messbare Ereignisse/Transaktionen bei deklariertem Grain.',
        'en' => 'Measurable events or transactions at a declared grain.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => 
          [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => 
          [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'star-schema',
          'label' => 
          [
            'de' => 'Star Schema',
            'en' => 'Star Schema',
          ],
        ],
      ],
    ],
    46 => 
    [
      'id' => 'dimension-table',
      'order' => 430,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'Dimension Table',
        'en' => 'Dimension Table',
      ],
      'aliases' => 
      [
        0 => 'Dimensions',
        1 => 'Dimensionstabelle',
      ],
      'definition' => 
      [
        'de' => 'Beschreibender Kontext (Kunde, Produkt, Kalender], der an Facts gejoint wird.',
        'en' => 'Descriptive context (customer, product, calendar] joined to facts.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'fact-table',
          'label' => 
          [
            'de' => 'Fact Table',
            'en' => 'Fact Table',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'scd',
          'label' => 
          [
            'de' => 'SCD (Slowly Changing Dimension]',
            'en' => 'SCD (Slowly Changing Dimension]',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'conformed-dimension',
          'label' => 
          [
            'de' => 'Conformed Dimension',
            'en' => 'Conformed Dimension',
          ],
        ],
      ],
    ],
    47 => 
    [
      'id' => 'conformed-dimension',
      'order' => 440,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'Conformed Dimension',
        'en' => 'Conformed Dimension',
      ],
      'aliases' => 
      [
        0 => 'Shared Dimension',
        1 => 'Konforme Dimension',
      ],
      'definition' => 
      [
        'de' => 'Geteilte Dimensionsbedeutung und Keys, wiederverwendbar über Marts und Domänen.',
        'en' => 'Shared dimension meaning and keys reusable across marts and domains.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => 
          [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => 
          [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
      ],
    ],
    48 => 
    [
      'id' => 'scd',
      'order' => 450,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'SCD (Slowly Changing Dimension]',
        'en' => 'SCD (Slowly Changing Dimension]',
      ],
      'aliases' => 
      [
        0 => 'Slowly Changing Dimension',
      ],
      'definition' => 
      [
        'de' => 'Musternfamilie, wie Dimensionsattribute sich über die Zeit ändern.',
        'en' => 'Pattern family for how dimension attributes change over time.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => 
          [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => 
          [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'slowlychange-dim',
          'label' => 
          [
            'de' => 'Slowly Changing Dimensions',
            'en' => 'Slowly changing dimensions',
          ],
        ],
      ],
    ],
    49 => 
    [
      'id' => 'scd2',
      'order' => 460,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'SCD Type 2',
        'en' => 'SCD Type 2',
      ],
      'aliases' => 
      [
        0 => 'SCD2',
        1 => 'Type 2 History',
      ],
      'definition' => 
      [
        'de' => 'Attributänderungen historisieren mit Gültigkeitsdaten bzw. Versionszeilen.',
        'en' => 'Historize attribute changes with effective dating or version rows.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'scd',
          'label' => 
          [
            'de' => 'SCD (Slowly Changing Dimension]',
            'en' => 'SCD (Slowly Changing Dimension]',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'as-was-history',
          'label' => 
          [
            'de' => 'As-Was History',
            'en' => 'As-Was History',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'slowlychange-dim',
          'label' => 
          [
            'de' => 'Slowly Changing Dimensions',
            'en' => 'Slowly changing dimensions',
          ],
        ],
      ],
    ],
    50 => 
    [
      'id' => 'cdc',
      'order' => 470,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'CDC (Change Data Capture]',
        'en' => 'CDC (Change Data Capture]',
      ],
      'aliases' => 
      [
        0 => 'Change Data Capture',
      ],
      'definition' => 
      [
        'de' => 'Quell-Inserts/Updates/Deletes für inkrementelle Loads erfassen.',
        'en' => 'Capture source inserts, updates, and deletes for incremental loads.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'delta-load',
          'label' => 
          [
            'de' => 'Delta / Incremental Load',
            'en' => 'Delta / Incremental Load',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => 
          [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
      ],
    ],
    51 => 
    [
      'id' => 'delta-load',
      'order' => 480,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'Delta / Incremental Load',
        'en' => 'Delta / Incremental Load',
      ],
      'aliases' => 
      [
        0 => 'Incremental Load',
        1 => 'Delta MERGE',
      ],
      'definition' => 
      [
        'de' => 'Nur Änderungen seit dem letzten erfolgreichen Lauf verarbeiten (oft via MERGE].',
        'en' => 'Process only changes since last successful run (often via MERGE].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'cdc',
          'label' => 
          [
            'de' => 'CDC (Change Data Capture]',
            'en' => 'CDC (Change Data Capture]',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'elt',
          'label' => 
          [
            'de' => 'ELT',
            'en' => 'ELT',
          ],
        ],
      ],
    ],
    52 => 
    [
      'id' => 'surrogate-key',
      'order' => 490,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'Surrogate Key',
        'en' => 'Surrogate Key',
      ],
      'aliases' => 
      [
        0 => 'SK',
        1 => 'Surrogatschlüssel',
      ],
      'definition' => 
      [
        'de' => 'Systemgenerierter dauerhafter Key unabhängig von Natural Keys der Quelle.',
        'en' => 'System-generated durable key independent of source natural keys.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'natural-key',
          'label' => 
          [
            'de' => 'Natural Key',
            'en' => 'Natural Key',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'dimension-table',
          'label' => 
          [
            'de' => 'Dimension Table',
            'en' => 'Dimension Table',
          ],
        ],
      ],
    ],
    53 => 
    [
      'id' => 'natural-key',
      'order' => 500,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'Natural Key',
        'en' => 'Natural Key',
      ],
      'aliases' => 
      [
        0 => 'Business Key',
        1 => 'Natürlicher Schlüssel',
      ],
      'definition' => 
      [
        'de' => 'Fachlicher/Quellen-Identifier für Matching und Lineage zurück zum Ursprung.',
        'en' => 'Business or source identifier used for matching and lineage back to origin.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'surrogate-key',
          'label' => 
          [
            'de' => 'Surrogate Key',
            'en' => 'Surrogate Key',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'golden-record',
          'label' => 
          [
            'de' => 'Golden Record',
            'en' => 'Golden Record',
          ],
        ],
      ],
    ],
    54 => 
    [
      'id' => 'golden-record',
      'order' => 510,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'Golden Record',
        'en' => 'Golden Record',
      ],
      'aliases' => 
      [
        0 => 'Golden Customer',
        1 => 'Master Record',
      ],
      'definition' => 
      [
        'de' => 'Aufgelöste, governte Master-Darstellung einer Entität (z. B. Golden Customer].',
        'en' => 'Resolved, governed master representation of an entity (e.g. Golden Customer].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => 
          [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'natural-key',
          'label' => 
          [
            'de' => 'Natural Key',
            'en' => 'Natural Key',
          ],
        ],
      ],
    ],
    55 => 
    [
      'id' => 'as-was-history',
      'order' => 520,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'As-Was History',
        'en' => 'As-Was History',
      ],
      'aliases' => 
      [
        0 => 'Temporal History',
        1 => 'As-Of',
      ],
      'definition' => 
      [
        'de' => 'Punkt-in-Zeit-Sicht auf Beziehungen/Attribute, wie sie an einem vergangenen Datum waren.',
        'en' => 'Point-in-time view of relationships and attributes as they were at a past date.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'scd2',
          'label' => 
          [
            'de' => 'SCD Type 2',
            'en' => 'SCD Type 2',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'integrated-core',
          'label' => 
          [
            'de' => 'Integrated Core',
            'en' => 'Integrated Core',
          ],
        ],
      ],
    ],
    56 => 
    [
      'id' => 'schema-drift',
      'order' => 530,
      'category' => 'modeling',
      'term' => 
      [
        'de' => 'Schema Drift',
        'en' => 'Schema Drift',
      ],
      'aliases' => 
      [
        0 => 'Drift',
      ],
      'definition' => 
      [
        'de' => 'Unerwartete Quell-/Schema-Änderung, die Contracts oder Loads bricht.',
        'en' => 'Unexpected source or schema change that breaks contracts or loads.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => 
          [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'breaking-change',
          'label' => 
          [
            'de' => 'Breaking Change',
            'en' => 'Breaking Change',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'automatic-raw-generation-using-dbt-macros',
          'label' => 
          [
            'de' => 'Automatische RAW-Generierung',
            'en' => 'Automatic RAW generation',
          ],
        ],
      ],
    ],
    57 => 
    [
      'id' => 'data-quality',
      'order' => 600,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Data Quality',
        'en' => 'Data Quality',
      ],
      'aliases' => 
      [
        0 => 'DQ',
        1 => 'Datenqualität',
      ],
      'definition' => 
      [
        'de' => 'Messbare Eignung von Daten für einen Zweck — Regeln, Ownership, Monitoring und Remediation statt einmaliger Checks.',
        'en' => 'Measurable fitness of data for a purpose — rules, ownership, monitoring, and remediation instead of one-off checks.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'series',
          'id' => 'operational-data-quality',
          'label' => 
          [
            'de' => 'Operational Data Quality',
            'en' => 'Operational data quality',
          ],
        ],
        1 => 
        [
          'type' => 'path',
          'id' => 'dq-with-dbt',
          'label' => 
          [
            'de' => 'DQ mit dbt',
            'en' => 'DQ with dbt',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'data-observability',
          'label' => 
          [
            'de' => 'Data Observability',
            'en' => 'Data Observability',
          ],
        ],
        3 => 
        [
          'type' => 'glossary',
          'id' => 'fitness-for-purpose',
          'label' => 
          [
            'de' => 'Fitness for Purpose',
            'en' => 'Fitness for Purpose',
          ],
        ],
      ],
    ],
    58 => 
    [
      'id' => 'data-contract',
      'order' => 610,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Data Contract',
        'en' => 'Data Contract',
      ],
      'aliases' => 
      [
        0 => 'Datenvertrag',
        1 => 'Interface Contract',
      ],
      'definition' => 
      [
        'de' => 'Vereinbarung zwischen Produzent und Konsument über Schema, Semantik, SLAs und Breaking-Change-Regeln.',
        'en' => 'Agreement between producer and consumer on schema, semantics, SLAs, and breaking-change rules.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => 
          [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'breaking-change',
          'label' => 
          [
            'de' => 'Breaking Change',
            'en' => 'Breaking Change',
          ],
        ],
      ],
    ],
    59 => 
    [
      'id' => 'fitness-for-purpose',
      'order' => 620,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Fitness for Purpose',
        'en' => 'Fitness for Purpose',
      ],
      'aliases' => 
      [
        0 => 'Fit for Purpose',
        1 => 'Zwecktauglichkeit',
      ],
      'definition' => 
      [
        'de' => 'Qualität gemessen am expliziten Use Case — nicht absolute Perfektion.',
        'en' => 'Quality judged against an explicit use case — not absolute perfection.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'data-quality-governance',
          'label' => 
          [
            'de' => 'Data Quality Governance',
            'en' => 'Data quality governance',
          ],
        ],
      ],
    ],
    60 => 
    [
      'id' => 'data-observability',
      'order' => 630,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Data Observability',
        'en' => 'Data Observability',
      ],
      'aliases' => 
      [
        0 => 'Observability',
        1 => 'Datenobservability',
      ],
      'definition' => 
      [
        'de' => 'Unerwartete Volume-/Null-/Verteilungs-/Schema-/Freshness-Anomalien erkennen — über feste Regeln hinaus.',
        'en' => 'Detect unexpected volume, null, distribution, schema, and freshness anomalies beyond fixed rules.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'freshness',
          'label' => 
          [
            'de' => 'Freshness',
            'en' => 'Freshness',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'data-quality-governance',
          'label' => 
          [
            'de' => 'Data Quality Governance',
            'en' => 'Data quality governance',
          ],
        ],
      ],
    ],
    61 => 
    [
      'id' => 'dq-gate',
      'order' => 640,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'DQ Gate',
        'en' => 'DQ Gate',
      ],
      'aliases' => 
      [
        0 => 'Quality Gate',
        1 => 'Qualitätsgate',
      ],
      'definition' => 
      [
        'de' => 'Hard Stop bzw. Promote-Bedingung in der Pipeline basierend auf Qualitätsergebnissen.',
        'en' => 'Hard stop or promote condition in the pipeline based on quality outcomes.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'orchestration',
          'label' => 
          [
            'de' => 'Orchestration',
            'en' => 'Orchestration',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => 
          [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
      ],
    ],
    62 => 
    [
      'id' => 'rule-registry',
      'order' => 650,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Rule Registry',
        'en' => 'Rule Registry',
      ],
      'aliases' => 
      [
        0 => 'Quality Rule Catalog',
        1 => 'Regelregister',
      ],
      'definition' => 
      [
        'de' => 'Katalog von DQ-Checks, Ownern, Severity und Ausführungskontext.',
        'en' => 'Catalog of DQ checks, owners, severity, and execution context.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'remediation',
          'label' => 
          [
            'de' => 'Remediation',
            'en' => 'Remediation',
          ],
        ],
      ],
    ],
    63 => 
    [
      'id' => 'remediation',
      'order' => 660,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Remediation',
        'en' => 'Remediation',
      ],
      'aliases' => 
      [
        0 => 'Issue Remediation',
        1 => 'Behebung',
      ],
      'definition' => 
      [
        'de' => 'Owned Fix-and-Validate-Loop für Qualitäts- und Metadatenfehler.',
        'en' => 'Owned fix-and-validate loop for quality and metadata defects.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'root-cause-analysis',
          'label' => 
          [
            'de' => 'Root Cause Analysis (DQ]',
            'en' => 'Root Cause Analysis (DQ]',
          ],
        ],
      ],
    ],
    64 => 
    [
      'id' => 'freshness',
      'order' => 670,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Freshness',
        'en' => 'Freshness',
      ],
      'aliases' => 
      [
        0 => 'Timeliness',
        1 => 'Aktualität',
      ],
      'definition' => 
      [
        'de' => 'Wie aktuell Daten (oder Metadaten] gegenüber der vereinbarten Erwartung sind.',
        'en' => 'How current data (or metadata] is versus the agreed expectation.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'sla-slo',
          'label' => 
          [
            'de' => 'SLA / SLO',
            'en' => 'SLA / SLO',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-observability',
          'label' => 
          [
            'de' => 'Data Observability',
            'en' => 'Data Observability',
          ],
        ],
      ],
    ],
    65 => 
    [
      'id' => 'completeness',
      'order' => 680,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Completeness',
        'en' => 'Completeness',
      ],
      'aliases' => 
      [
        0 => 'Vollständigkeit',
      ],
      'definition' => 
      [
        'de' => 'Erforderliche Felder/Records für den deklarierten Zweck sind vorhanden.',
        'en' => 'Required fields and records present for the declared purpose.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'fitness-for-purpose',
          'label' => 
          [
            'de' => 'Fitness for Purpose',
            'en' => 'Fitness for Purpose',
          ],
        ],
      ],
    ],
    66 => 
    [
      'id' => 'consistency',
      'order' => 690,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Consistency',
        'en' => 'Consistency',
      ],
      'aliases' => 
      [
        0 => 'Konsistenz',
      ],
      'definition' => 
      [
        'de' => 'Gleiche Bedeutung und Regeln über Systeme, Layer und Replikas hinweg.',
        'en' => 'Same meaning and rules across systems, layers, and replicas.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'conformed-dimension',
          'label' => 
          [
            'de' => 'Conformed Dimension',
            'en' => 'Conformed Dimension',
          ],
        ],
      ],
    ],
    67 => 
    [
      'id' => 'accuracy',
      'order' => 700,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Accuracy',
        'en' => 'Accuracy',
      ],
      'aliases' => 
      [
        0 => 'Correctness',
        1 => 'Genauigkeit',
      ],
      'definition' => 
      [
        'de' => 'Werte repräsentieren den realen Sachverhalt für den Use Case korrekt.',
        'en' => 'Values correctly represent the real-world fact for the use case.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'fitness-for-purpose',
          'label' => 
          [
            'de' => 'Fitness for Purpose',
            'en' => 'Fitness for Purpose',
          ],
        ],
      ],
    ],
    68 => 
    [
      'id' => 'uniqueness',
      'order' => 710,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Uniqueness',
        'en' => 'Uniqueness',
      ],
      'aliases' => 
      [
        0 => 'Deduplication',
        1 => 'Eindeutigkeit',
      ],
      'definition' => 
      [
        'de' => 'Keine unerwünschten Duplikate beim deklarierten Grain/Key.',
        'en' => 'No unwanted duplicates at the declared grain or key.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'grain',
          'label' => 
          [
            'de' => 'Grain',
            'en' => 'Grain',
          ],
        ],
      ],
    ],
    69 => 
    [
      'id' => 'validity',
      'order' => 720,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Validity',
        'en' => 'Validity',
      ],
      'aliases' => 
      [
        0 => 'Gültigkeit',
      ],
      'definition' => 
      [
        'de' => 'Werte entsprechen erlaubten Formaten, Domänen und Referenzmengen.',
        'en' => 'Values conform to allowed formats, domains, and reference sets.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-quality',
          'label' => 
          [
            'de' => 'Data Quality',
            'en' => 'Data Quality',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => 
          [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
      ],
    ],
    70 => 
    [
      'id' => 'root-cause-analysis',
      'order' => 730,
      'category' => 'quality',
      'term' => 
      [
        'de' => 'Root Cause Analysis (DQ]',
        'en' => 'Root Cause Analysis (DQ]',
      ],
      'aliases' => 
      [
        0 => 'RCA',
        1 => 'Ursachenanalyse',
      ],
      'definition' => 
      [
        'de' => 'Defekt zum Ursprungssystem/-prozess zurückverfolgen statt Marts zu patchen.',
        'en' => 'Trace defect to originating system or process instead of patching marts.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'remediation',
          'label' => 
          [
            'de' => 'Remediation',
            'en' => 'Remediation',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => 
          [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
      ],
    ],
    71 => 
    [
      'id' => 'pii',
      'order' => 800,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'PII',
        'en' => 'PII',
      ],
      'aliases' => 
      [
        0 => 'Personenbezogene Daten',
        1 => 'Personal Data',
        2 => 'SPI',
      ],
      'definition' => 
      [
        'de' => 'Personenbezogene oder personenbeziehbare Daten. Brauchen Klassifikation, Masking, Zweckbindung und nachweisbare Lösch-/Sperrpfade.',
        'en' => 'Personally identifiable or linkable data. Needs classification, masking, purpose binding, and proven deletion/restriction paths.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'pii-privacy-governance',
          'label' => 
          [
            'de' => 'PII Privacy Governance',
            'en' => 'PII privacy governance',
          ],
        ],
        1 => 
        [
          'type' => 'path',
          'id' => 'pii-in-five-steps',
          'label' => 
          [
            'de' => 'PII in 5 Schritten',
            'en' => 'PII in 5 steps',
          ],
        ],
        2 => 
        [
          'type' => 'compliance',
          'id' => 'gdpr',
          'label' => 
          [
            'de' => 'DSGVO / GDPR',
            'en' => 'GDPR',
          ],
        ],
        3 => 
        [
          'type' => 'glossary',
          'id' => 'data-classification',
          'label' => 
          [
            'de' => 'Data Classification',
            'en' => 'Data Classification',
          ],
        ],
      ],
    ],
    72 => 
    [
      'id' => 'dsdr',
      'order' => 810,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'DSDR',
        'en' => 'DSDR',
      ],
      'aliases' => 
      [
        0 => 'Data Subject Deletion Request',
        1 => 'Löschanfrage',
        2 => 'Right to Erasure',
      ],
      'definition' => 
      [
        'de' => 'Prozess und technische Fähigkeit, Betroffenenrechte (Löschen/Sperren] über Systeme und Lineage hinweg auszuführen und zu belegen.',
        'en' => 'Process and technical ability to execute and evidence data-subject rights (erase/restrict] across systems and lineage.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'dsdr-governance',
          'label' => 
          [
            'de' => 'DSDR Governance',
            'en' => 'DSDR governance',
          ],
        ],
        1 => 
        [
          'type' => 'tool',
          'route' => 'tools.pii-dsdr-readiness-checker',
          'label' => 
          [
            'de' => 'PII/DSDR Readiness',
            'en' => 'PII/DSDR readiness',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => 
          [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
      ],
    ],
    73 => 
    [
      'id' => 'retention',
      'order' => 820,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'Retention',
        'en' => 'Retention',
      ],
      'aliases' => 
      [
        0 => 'Aufbewahrung',
        1 => 'Speicherbegrenzung',
        2 => 'Archive',
      ],
      'definition' => 
      [
        'de' => 'Regeln, wie lange Daten aktiv oder archiviert bleiben dürfen — getrennt von Backup und analytischen Marts.',
        'en' => 'Rules for how long data may stay active or archived — separated from backup and analytical marts.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'compliance',
          'id' => 'gdpr',
          'label' => 
          [
            'de' => 'DSGVO / GDPR',
            'en' => 'GDPR',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'dsdr',
          'label' => 
          [
            'de' => 'DSDR',
            'en' => 'DSDR',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'data-lifecycle-retention',
          'label' => 
          [
            'de' => 'Data Lifecycle & Retention',
            'en' => 'Data lifecycle & retention',
          ],
        ],
      ],
    ],
    74 => 
    [
      'id' => 'masking',
      'order' => 830,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'Masking',
        'en' => 'Masking',
      ],
      'aliases' => 
      [
        0 => 'Dynamic Masking',
      ],
      'definition' => 
      [
        'de' => 'Technik, sensible Werte für unberechtigte Rollen zu verbergen oder zu ersetzen — idealerweise policy-gesteuert und lineage-bewusst.',
        'en' => 'Technique to hide or replace sensitive values for unauthorized roles — ideally policy-driven and lineage-aware.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'snowflake-masking-policies-qlik-section-access',
          'label' => 
          [
            'de' => 'Masking & Section Access',
            'en' => 'Masking & section access',
          ],
        ],
        1 => 
        [
          'type' => 'tool',
          'route' => 'tools.pii-policy-generator',
          'label' => 
          [
            'de' => 'PII Policy Generator',
            'en' => 'PII policy generator',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'tokenization',
          'label' => 
          [
            'de' => 'Tokenization',
            'en' => 'Tokenization',
          ],
        ],
      ],
    ],
    75 => 
    [
      'id' => 'data-classification',
      'order' => 840,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'Data Classification',
        'en' => 'Data Classification',
      ],
      'aliases' => 
      [
        0 => 'Classification',
        1 => 'Klassifikation',
      ],
      'definition' => 
      [
        'de' => 'Labeling von Sensitivity/Purpose-Klasse, das Schutz und Nutzung steuert.',
        'en' => 'Labeling sensitivity or purpose class that drives protection and use.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => 
          [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'sensitivity-label',
          'label' => 
          [
            'de' => 'Sensitivity Label',
            'en' => 'Sensitivity Label',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'pii-privacy-governance',
          'label' => 
          [
            'de' => 'PII Privacy Governance',
            'en' => 'PII privacy governance',
          ],
        ],
      ],
    ],
    76 => 
    [
      'id' => 'sensitivity-label',
      'order' => 850,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'Sensitivity Label',
        'en' => 'Sensitivity Label',
      ],
      'aliases' => 
      [
        0 => 'Sensitivity',
        1 => 'Sensitivitätslabel',
      ],
      'definition' => 
      [
        'de' => 'Plattform-Label (z. B. Purview], das Policy an Assets/Spalten bindet.',
        'en' => 'Platform label (e.g. Purview] binding policy to assets or columns.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-classification',
          'label' => 
          [
            'de' => 'Data Classification',
            'en' => 'Data Classification',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'microsoft-fabric',
          'label' => 
          [
            'de' => 'Microsoft Fabric',
            'en' => 'Microsoft Fabric',
          ],
        ],
      ],
    ],
    77 => 
    [
      'id' => 'purpose-limitation',
      'order' => 860,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'Purpose Limitation',
        'en' => 'Purpose Limitation',
      ],
      'aliases' => 
      [
        0 => 'Purpose Binding',
        1 => 'Zweckbindung',
      ],
      'definition' => 
      [
        'de' => 'Nutzung nur für den vereinbarten Zweck — vor Tooling und Mart-Design.',
        'en' => 'Use only for the agreed purpose — before tooling and mart design.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => 
          [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        1 => 
        [
          'type' => 'compliance',
          'id' => 'gdpr',
          'label' => 
          [
            'de' => 'DSGVO / GDPR',
            'en' => 'GDPR',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'pii-privacy-governance',
          'label' => 
          [
            'de' => 'PII Privacy Governance',
            'en' => 'PII privacy governance',
          ],
        ],
      ],
    ],
    78 => 
    [
      'id' => 'tokenization',
      'order' => 870,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'Tokenization',
        'en' => 'Tokenization',
      ],
      'aliases' => 
      [
        0 => 'Tokenize',
        1 => 'Tokenisierung',
      ],
      'definition' => 
      [
        'de' => 'Sensible Werte durch reversible Tokens unter kontrolliertem Vaulting ersetzen.',
        'en' => 'Replace sensitive values with reversible tokens under controlled vaulting.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'masking',
          'label' => 
          [
            'de' => 'Masking',
            'en' => 'Masking',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'pseudonymization',
          'label' => 
          [
            'de' => 'Pseudonymization',
            'en' => 'Pseudonymization',
          ],
        ],
      ],
    ],
    79 => 
    [
      'id' => 'pseudonymization',
      'order' => 880,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'Pseudonymization',
        'en' => 'Pseudonymization',
      ],
      'aliases' => 
      [
        0 => 'Pseudonymisation',
        1 => 'Pseudonymisierung',
      ],
      'definition' => 
      [
        'de' => 'Identifizierbarkeit reduzieren, kontrolliertes Re-Linken unter Safeguards erlauben.',
        'en' => 'Reduce identifiability while allowing controlled re-link under safeguards.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'anonymization',
          'label' => 
          [
            'de' => 'Anonymization',
            'en' => 'Anonymization',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'masking',
          'label' => 
          [
            'de' => 'Masking',
            'en' => 'Masking',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => 
          [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
      ],
    ],
    80 => 
    [
      'id' => 'anonymization',
      'order' => 890,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'Anonymization',
        'en' => 'Anonymization',
      ],
      'aliases' => 
      [
        0 => 'Anonymisation',
        1 => 'Anonymisierung',
      ],
      'definition' => 
      [
        'de' => 'Irreversible Entfernung persönlicher Identifizierbarkeit für ein definiertes Bedrohungsmodell.',
        'en' => 'Irreversible removal of personal identifiability for a stated threat model.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'pseudonymization',
          'label' => 
          [
            'de' => 'Pseudonymization',
            'en' => 'Pseudonymization',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => 
          [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
      ],
    ],
    81 => 
    [
      'id' => 'redaction',
      'order' => 900,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'Redaction',
        'en' => 'Redaction',
      ],
      'aliases' => 
      [
        0 => 'Drop/Redact',
        1 => 'Schwärzung',
      ],
      'definition' => 
      [
        'de' => 'Hochrisiko-Felder droppen/blanken, damit sie kuratierte/Mart-Layer nie erreichen.',
        'en' => 'Drop or blank high-risk fields so they never reach curated or mart layers.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'masking',
          'label' => 
          [
            'de' => 'Masking',
            'en' => 'Masking',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'landing-raw-layer',
          'label' => 
          [
            'de' => 'Landing / RAW Layer',
            'en' => 'Landing / RAW Layer',
          ],
        ],
      ],
    ],
    82 => 
    [
      'id' => 'workforce-policy',
      'order' => 910,
      'category' => 'privacy',
      'term' => 
      [
        'de' => 'Workforce / Employee Data Policy',
        'en' => 'Workforce / Employee Data Policy',
      ],
      'aliases' => 
      [
        0 => 'Employee Data Policy',
        1 => 'Mitarbeiterdaten-Policy',
      ],
      'definition' => 
      [
        'de' => 'Eigene Handling-Regeln für Workforce-Identität vs. Kunden-PII von RAW bis Mart.',
        'en' => 'Separate handling rules for workforce identity vs customer PII in RAW→Mart.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => 
          [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'purpose-limitation',
          'label' => 
          [
            'de' => 'Purpose Limitation',
            'en' => 'Purpose Limitation',
          ],
        ],
      ],
    ],
    83 => 
    [
      'id' => 'rbac',
      'order' => 1000,
      'category' => 'security',
      'term' => 
      [
        'de' => 'RBAC',
        'en' => 'RBAC',
      ],
      'aliases' => 
      [
        0 => 'Role-Based Access Control',
      ],
      'definition' => 
      [
        'de' => 'Zugriff über Rollenmitgliedschaft.',
        'en' => 'Access by role membership.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'abac',
          'label' => 
          [
            'de' => 'ABAC',
            'en' => 'ABAC',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => 
          [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => 
          [
            'de' => 'Access & Security Governance',
            'en' => 'Access & security governance',
          ],
        ],
        3 => 
        [
          'type' => 'path',
          'id' => 'access-security-ops',
          'label' => 
          [
            'de' => 'Access & Security Ops',
            'en' => 'Access & security ops',
          ],
        ],
      ],
    ],
    84 => 
    [
      'id' => 'abac',
      'order' => 1010,
      'category' => 'security',
      'term' => 
      [
        'de' => 'ABAC',
        'en' => 'ABAC',
      ],
      'aliases' => 
      [
        0 => 'Attribute-Based Access Control',
      ],
      'definition' => 
      [
        'de' => 'Zugriff über Attribute (Clearance, Purpose, Residency usw.].',
        'en' => 'Access by attributes (clearance, purpose, residency, etc.].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => 
          [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => 
          [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => 
          [
            'de' => 'Access & Security Governance',
            'en' => 'Access & security governance',
          ],
        ],
      ],
    ],
    85 => 
    [
      'id' => 'least-privilege',
      'order' => 1020,
      'category' => 'security',
      'term' => 
      [
        'de' => 'Least Privilege',
        'en' => 'Least Privilege',
      ],
      'aliases' => 
      [
        0 => 'Least Privilege Access',
        1 => 'Minimalrechte',
      ],
      'definition' => 
      [
        'de' => 'Minimaler Zugriff für die Aufgabe — sonst Default Deny.',
        'en' => 'Minimum access needed for the job — default deny elsewhere.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => 
          [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'access-recertification',
          'label' => 
          [
            'de' => 'Access Recertification',
            'en' => 'Access Recertification',
          ],
        ],
      ],
    ],
    86 => 
    [
      'id' => 'segregation-of-duties',
      'order' => 1030,
      'category' => 'security',
      'term' => 
      [
        'de' => 'Segregation of Duties (SoD]',
        'en' => 'Segregation of Duties (SoD]',
      ],
      'aliases' => 
      [
        0 => 'SoD',
        1 => 'Funktionstrennung',
      ],
      'definition' => 
      [
        'de' => 'Kollidierende Duties trennen (z. B. Grant vs. Approve], um Missbrauchsrisiko zu senken.',
        'en' => 'Split conflicting duties (e.g. grant vs approve] to reduce abuse risk.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => 
          [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'raci',
          'label' => 
          [
            'de' => 'RACI',
            'en' => 'RACI',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => 
          [
            'de' => 'Access & Security Governance',
            'en' => 'Access & security governance',
          ],
        ],
      ],
    ],
    87 => 
    [
      'id' => 'section-access',
      'order' => 1040,
      'category' => 'security',
      'term' => 
      [
        'de' => 'Section Access',
        'en' => 'Section Access',
      ],
      'aliases' => 
      [
        0 => 'Qlik Section Access',
      ],
      'definition' => 
      [
        'de' => 'Qlik Row-Reduction Security Model in Apps.',
        'en' => 'Qlik row-reduction security model in apps.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'snowflake-masking-policies-qlik-section-access',
          'label' => 
          [
            'de' => 'Masking & Section Access',
            'en' => 'Masking & section access',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'row-level-security',
          'label' => 
          [
            'de' => 'Row-Level Security (RLS]',
            'en' => 'Row-Level Security (RLS]',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'qvd',
          'label' => 
          [
            'de' => 'QVD',
            'en' => 'QVD',
          ],
        ],
      ],
    ],
    88 => 
    [
      'id' => 'row-level-security',
      'order' => 1050,
      'category' => 'security',
      'term' => 
      [
        'de' => 'Row-Level Security (RLS]',
        'en' => 'Row-Level Security (RLS]',
      ],
      'aliases' => 
      [
        0 => 'RLS',
      ],
      'definition' => 
      [
        'de' => 'Zeilen nach User-/Rollen-Claims in Warehouse oder BI filtern.',
        'en' => 'Filter rows by user or role claims in warehouse or BI.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'section-access',
          'label' => 
          [
            'de' => 'Section Access',
            'en' => 'Section Access',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => 
          [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'access-security-governance',
          'label' => 
          [
            'de' => 'Access & Security Governance',
            'en' => 'Access & security governance',
          ],
        ],
      ],
    ],
    89 => 
    [
      'id' => 'access-recertification',
      'order' => 1060,
      'category' => 'security',
      'term' => 
      [
        'de' => 'Access Recertification',
        'en' => 'Access Recertification',
      ],
      'aliases' => 
      [
        0 => 'Access Review',
        1 => 'Attestation',
        2 => 'Zugriffszertifizierung',
      ],
      'definition' => 
      [
        'de' => 'Periodische Neu-Freigabe, dass Entitlements noch nötig sind.',
        'en' => 'Periodic re-approval that entitlements are still needed.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'least-privilege',
          'label' => 
          [
            'de' => 'Least Privilege',
            'en' => 'Least Privilege',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'iam',
          'label' => 
          [
            'de' => 'IAM',
            'en' => 'IAM',
          ],
        ],
        2 => 
        [
          'type' => 'path',
          'id' => 'access-security-ops',
          'label' => 
          [
            'de' => 'Access & Security Ops',
            'en' => 'Access & security ops',
          ],
        ],
      ],
    ],
    90 => 
    [
      'id' => 'iam',
      'order' => 1070,
      'category' => 'security',
      'term' => 
      [
        'de' => 'IAM',
        'en' => 'IAM',
      ],
      'aliases' => 
      [
        0 => 'Identity Governance',
        1 => 'Identity and Access Management',
      ],
      'definition' => 
      [
        'de' => 'Identity- and Access-Management-Rückgrat für Authentifizierung gegenüber Datensystemen.',
        'en' => 'Identity and access management backbone for authenticating subjects to data systems.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'rbac',
          'label' => 
          [
            'de' => 'RBAC',
            'en' => 'RBAC',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'access-recertification',
          'label' => 
          [
            'de' => 'Access Recertification',
            'en' => 'Access Recertification',
          ],
        ],
      ],
    ],
    91 => 
    [
      'id' => 'lineage',
      'order' => 1100,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Lineage',
        'en' => 'Lineage',
      ],
      'aliases' => 
      [
        0 => 'Data Lineage',
        1 => 'Datenherkunft',
      ],
      'definition' => 
      [
        'de' => 'Nachvollziehbare Herkunft und Transformation von Daten — von Quelle über Pipelines bis zu Reports und Löschpfaden.',
        'en' => 'Traceable origin and transformation of data — from source through pipelines to reports and deletion paths.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'propagating-pii-metadata-across-data-warehouses',
          'label' => 
          [
            'de' => 'PII-Metadaten propagieren',
            'en' => 'Propagating PII metadata',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'dsdr',
          'label' => 
          [
            'de' => 'DSDR',
            'en' => 'DSDR',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'column-lineage',
          'label' => 
          [
            'de' => 'Column Lineage',
            'en' => 'Column Lineage',
          ],
        ],
        3 => 
        [
          'type' => 'glossary',
          'id' => 'impact-analysis',
          'label' => 
          [
            'de' => 'Impact Analysis',
            'en' => 'Impact Analysis',
          ],
        ],
      ],
    ],
    92 => 
    [
      'id' => 'data-catalog',
      'order' => 1110,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Data Catalog',
        'en' => 'Data Catalog',
      ],
      'aliases' => 
      [
        0 => 'Katalog',
        1 => 'Unified Catalog',
      ],
      'definition' => 
      [
        'de' => 'Auffindbare Inventar- und Bedeutungsschicht für Assets, Begriffe, Owner und Policies — Grundlage für Discovery und Governance.',
        'en' => 'Discoverable inventory and meaning layer for assets, terms, owners, and policies — the base for discovery and governance.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'series',
          'id' => 'metadata-deep-dive',
          'label' => 
          [
            'de' => 'MetaData Deep Dive',
            'en' => 'MetaData deep dive',
          ],
        ],
        1 => 
        [
          'type' => 'tool',
          'route' => 'tools.pureview-glossary-generator',
          'label' => 
          [
            'de' => 'PureView Glossary Generator',
            'en' => 'PureView glossary generator',
          ],
        ],
        2 => 
        [
          'type' => 'path',
          'id' => 'metadata-operating-model',
          'label' => 
          [
            'de' => 'Metadata Operating Model',
            'en' => 'Metadata operating model',
          ],
        ],
        3 => 
        [
          'type' => 'glossary',
          'id' => 'business-glossary',
          'label' => 
          [
            'de' => 'Business Glossary',
            'en' => 'Business Glossary',
          ],
        ],
      ],
    ],
    93 => 
    [
      'id' => 'metadata',
      'order' => 1120,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Metadata',
        'en' => 'Metadata',
      ],
      'aliases' => 
      [
        0 => 'Metadaten',
        1 => 'Technical Metadata',
      ],
      'definition' => 
      [
        'de' => 'Daten über Daten: Schema, Bedeutung, Owner, Klassifikation, Lineage und Policies — der Steuerungshebel der Governance.',
        'en' => 'Data about data: schema, meaning, owners, classification, lineage, and policies — the control lever of governance.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'series',
          'id' => 'metadata-deep-dive',
          'label' => 
          [
            'de' => 'MetaData Deep Dive',
            'en' => 'MetaData deep dive',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'metadata-driven-governance-with-dbt-meta',
          'label' => 
          [
            'de' => 'dbt meta Governance',
            'en' => 'dbt meta governance',
          ],
        ],
        2 => 
        [
          'type' => 'path',
          'id' => 'metadata-operating-model',
          'label' => 
          [
            'de' => 'Metadata Operating Model',
            'en' => 'Metadata operating model',
          ],
        ],
        3 => 
        [
          'type' => 'glossary',
          'id' => 'active-metadata',
          'label' => 
          [
            'de' => 'Active Metadata',
            'en' => 'Active Metadata',
          ],
        ],
      ],
    ],
    94 => 
    [
      'id' => 'business-glossary',
      'order' => 1130,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Business Glossary',
        'en' => 'Business Glossary',
      ],
      'aliases' => 
      [
        0 => 'Glossary',
        1 => 'Fachglossar',
      ],
      'definition' => 
      [
        'de' => 'Abgestimmte Fachbegriffe und Definitionen — verwandt mit, aber nicht identisch zum Catalog.',
        'en' => 'Agreed business terms and definitions — related to, not identical with, the catalog.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-catalog',
          'label' => 
          [
            'de' => 'Data Catalog',
            'en' => 'Data Catalog',
          ],
        ],
        1 => 
        [
          'type' => 'tool',
          'route' => 'tools.pureview-glossary-generator',
          'label' => 
          [
            'de' => 'PureView Glossary Generator',
            'en' => 'PureView glossary generator',
          ],
        ],
        2 => 
        [
          'type' => 'route',
          'route' => 'glossary.index',
          'label' => 
          [
            'de' => 'Glossary Hub',
            'en' => 'Glossary hub',
          ],
        ],
      ],
    ],
    95 => 
    [
      'id' => 'active-metadata',
      'order' => 1140,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Active Metadata',
        'en' => 'Active Metadata',
      ],
      'aliases' => 
      [
        0 => 'Aktive Metadaten',
      ],
      'definition' => 
      [
        'de' => 'Metadaten, die Automation steuern (Policies, Quality, Routing] — nicht nur Dokumentation.',
        'en' => 'Metadata that drives automation (policies, quality, routing], not only documentation.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'metadata',
          'label' => 
          [
            'de' => 'Metadata',
            'en' => 'Metadata',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'activate-metadata-through-automation',
          'label' => 
          [
            'de' => 'Metadaten durch Automation aktivieren',
            'en' => 'Activate metadata through automation',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'governance-metadata-that-controls-data',
          'label' => 
          [
            'de' => 'Governance-Metadaten, die Daten steuern',
            'en' => 'Governance metadata that controls data',
          ],
        ],
      ],
    ],
    96 => 
    [
      'id' => 'metadata-provenance',
      'order' => 1150,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Metadata Provenance',
        'en' => 'Metadata Provenance',
      ],
      'aliases' => 
      [
        0 => 'Provenance',
        1 => 'Herkunft',
      ],
      'definition' => 
      [
        'de' => 'Wer/was eine Metadaten-Aussage authorisiert hat und aus welcher Source of Truth.',
        'en' => 'Who or what authored a metadata claim and from which source of truth.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'metadata',
          'label' => 
          [
            'de' => 'Metadata',
            'en' => 'Metadata',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'metadata-harvesting',
          'label' => 
          [
            'de' => 'Metadata Harvesting',
            'en' => 'Metadata Harvesting',
          ],
        ],
      ],
    ],
    97 => 
    [
      'id' => 'metadata-harvesting',
      'order' => 1160,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Metadata Harvesting',
        'en' => 'Metadata Harvesting',
      ],
      'aliases' => 
      [
        0 => 'Harvest',
        1 => 'Harvesting',
      ],
      'definition' => 
      [
        'de' => 'Automatisches Einsammeln technischer Metadaten aus Plattformen in den Catalog.',
        'en' => 'Automated collection of technical metadata from platforms into the catalog.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'harvest-metadata-automatically',
          'label' => 
          [
            'de' => 'Metadaten automatisch harvesten',
            'en' => 'Harvest metadata automatically',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-catalog',
          'label' => 
          [
            'de' => 'Data Catalog',
            'en' => 'Data Catalog',
          ],
        ],
      ],
    ],
    98 => 
    [
      'id' => 'impact-analysis',
      'order' => 1170,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Impact Analysis',
        'en' => 'Impact Analysis',
      ],
      'aliases' => 
      [
        0 => 'Field Impact',
        1 => 'Auswirkungsanalyse',
      ],
      'definition' => 
      [
        'de' => 'Downstream-Blast-Radius einer Feld-/Modell-/KPI-Änderung über Lineage nachverfolgen.',
        'en' => 'Trace downstream blast radius of a field, model, or KPI change via lineage.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => 
          [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'lineage-impact-and-metadata-propagation',
          'label' => 
          [
            'de' => 'Lineage, Impact und Propagation',
            'en' => 'Lineage, impact, and metadata propagation',
          ],
        ],
      ],
    ],
    99 => 
    [
      'id' => 'column-lineage',
      'order' => 1180,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Column Lineage',
        'en' => 'Column Lineage',
      ],
      'aliases' => 
      [
        0 => 'Field Lineage',
        1 => 'Spalten-Lineage',
      ],
      'definition' => 
      [
        'de' => 'Feld-level Herkunfts-/Transform-Pfad — nötig für PII-Propagation und DSDR.',
        'en' => 'Field-level origin and transform path (needed for PII propagation and DSDR].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'lineage',
          'label' => 
          [
            'de' => 'Lineage',
            'en' => 'Lineage',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => 
          [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'propagating-pii-metadata-across-data-warehouses',
          'label' => 
          [
            'de' => 'PII-Metadaten propagieren',
            'en' => 'Propagating PII metadata',
          ],
        ],
      ],
    ],
    100 => 
    [
      'id' => 'metadata-enrichment',
      'order' => 1190,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Metadata Enrichment',
        'en' => 'Metadata Enrichment',
      ],
      'aliases' => 
      [
        0 => 'Enrichment',
        1 => 'Anreicherung',
      ],
      'definition' => 
      [
        'de' => 'Technische Assets um Business-Kontext, Owner, Klassifikation und KPI-Links ergänzen.',
        'en' => 'Add business context, owners, classification, and KPI links to technical assets.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'enrich-technical-metadata-with-business-context',
          'label' => 
          [
            'de' => 'Technische Metadaten anreichern',
            'en' => 'Enrich technical metadata with business context',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'business-glossary',
          'label' => 
          [
            'de' => 'Business Glossary',
            'en' => 'Business Glossary',
          ],
        ],
      ],
    ],
    101 => 
    [
      'id' => 'ai-ready-metadata',
      'order' => 1200,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'AI-Ready Metadata',
        'en' => 'AI-Ready Metadata',
      ],
      'aliases' => 
      [
        0 => 'AI-ready',
      ],
      'definition' => 
      [
        'de' => 'Vollständige, aktuelle, zulässige Metadaten geeignet für Assistenten und RAG.',
        'en' => 'Complete, current, permitted-use metadata suitable for assistants and RAG.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'prepare-metadata-for-ai-rag-and-model-training',
          'label' => 
          [
            'de' => 'Metadaten für AI/RAG vorbereiten',
            'en' => 'Prepare metadata for AI, RAG, and model training',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => 
          [
            'de' => 'RAG (Retrieval-Augmented Generation]',
            'en' => 'RAG (Retrieval-Augmented Generation]',
          ],
        ],
        2 => 
        [
          'type' => 'path',
          'id' => 'ai-foundations',
          'label' => 
          [
            'de' => 'AI Foundations',
            'en' => 'AI foundations',
          ],
        ],
      ],
    ],
    102 => 
    [
      'id' => 'dbt-meta',
      'order' => 1210,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'dbt meta',
        'en' => 'dbt meta',
      ],
      'aliases' => 
      [
        0 => 'dbt Metadata',
        1 => 'meta',
      ],
      'definition' => 
      [
        'de' => 'Strukturierte Metadaten in dbt-YAML, die Governance-Automation steuern.',
        'en' => 'Structured metadata in dbt YAML used to drive governance automation.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'dbt',
          'label' => 
          [
            'de' => 'dbt',
            'en' => 'dbt',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'active-metadata',
          'label' => 
          [
            'de' => 'Active Metadata',
            'en' => 'Active Metadata',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'metadata-driven-governance-with-dbt-meta',
          'label' => 
          [
            'de' => 'dbt meta Governance',
            'en' => 'dbt meta governance',
          ],
        ],
      ],
    ],
    103 => 
    [
      'id' => 'centralized-vs-federated-metadata',
      'order' => 1220,
      'category' => 'metadata',
      'term' => 
      [
        'de' => 'Centralized vs Federated Metadata',
        'en' => 'Centralized vs Federated Metadata',
      ],
      'aliases' => 
      [
        0 => 'Distributed Metadata',
        1 => 'Federated Metadata',
      ],
      'definition' => 
      [
        'de' => 'Pro Capability entscheiden, was zentrale Discovery vs. domänenautorisierte Wahrheit ist.',
        'en' => 'Decide per capability what is central discovery vs domain-authored truth.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'centralized-federated-or-distributed-metadata',
          'label' => 
          [
            'de' => 'Zentral, federiert oder verteilt',
            'en' => 'Centralized, federated, or distributed metadata',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'federated-governance',
          'label' => 
          [
            'de' => 'Federated Governance',
            'en' => 'Federated Governance',
          ],
        ],
      ],
    ],
    104 => 
    [
      'id' => 'raci',
      'order' => 1300,
      'category' => 'process',
      'term' => 
      [
        'de' => 'RACI',
        'en' => 'RACI',
      ],
      'aliases' => 
      [
        0 => 'Responsible',
        1 => 'Accountable',
        2 => 'Consulted',
        3 => 'Informed',
      ],
      'definition' => 
      [
        'de' => 'Rollenmatrix für Entscheidungen: wer ausführt (R], wer verantwortet (A], wer berät (C], wer informiert wird (I].',
        'en' => 'Role matrix for decisions: who executes (R], who owns (A], who is consulted (C], who is informed (I].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => 
          [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => 
          [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => 
          [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
        3 => 
        [
          'type' => 'tool',
          'route' => 'tools.stakeholder-matrix',
          'label' => 
          [
            'de' => 'Stakeholder & RACI Matrix',
            'en' => 'Stakeholder & RACI matrix',
          ],
        ],
        4 => 
        [
          'type' => 'route',
          'route' => 'roles.index',
          'label' => 
          [
            'de' => 'Roles Hub',
            'en' => 'Roles hub',
          ],
        ],
        5 => 
        [
          'type' => 'series',
          'id' => 'roles-hub',
          'label' => 
          [
            'de' => 'Roles and Decision Rights',
            'en' => 'Roles and decision rights',
          ],
        ],
      ],
    ],
    105 => 
    [
      'id' => 'kpi-governance',
      'order' => 1310,
      'category' => 'process',
      'term' => 
      [
        'de' => 'KPI Governance',
        'en' => 'KPI Governance',
      ],
      'aliases' => 
      [
        0 => 'Metric Definition',
        1 => 'Single Source of Truth',
      ],
      'definition' => 
      [
        'de' => 'Klare Definition, Owner und Änderungsprozess für Kennzahlen — verhindert widersprüchliche Zahlen in Tools und Meetings.',
        'en' => 'Clear definition, owner, and change process for metrics — prevents conflicting numbers across tools and meetings.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'tool',
          'route' => 'tools.kpi-definition',
          'label' => 
          [
            'de' => 'KPI Definition',
            'en' => 'KPI definition',
          ],
        ],
        1 => 
        [
          'type' => 'series',
          'id' => 'governance-pillars',
          'label' => 
          [
            'de' => '8 Säulen',
            'en' => '8 pillars',
          ],
        ],
        2 => 
        [
          'type' => 'path',
          'id' => 'trusted-metrics',
          'label' => 
          [
            'de' => 'Trusted Metrics',
            'en' => 'Trusted metrics',
          ],
        ],
        3 => 
        [
          'type' => 'glossary',
          'id' => 'metric-store',
          'label' => 
          [
            'de' => 'Metric / KPI Store',
            'en' => 'Metric / KPI Store',
          ],
        ],
      ],
    ],
    106 => 
    [
      'id' => 'decision-rights',
      'order' => 1320,
      'category' => 'process',
      'term' => 
      [
        'de' => 'Decision Rights',
        'en' => 'Decision Rights',
      ],
      'aliases' => 
      [
        0 => 'Entscheidungsrechte',
      ],
      'definition' => 
      [
        'de' => 'Wer Zweck, Zugriff, Definitionen und Exceptions entscheiden darf — und auf welchem Risk Tier.',
        'en' => 'Who may decide purpose, access, definitions, and exceptions — and at what risk tier.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'raci',
          'label' => 
          [
            'de' => 'RACI',
            'en' => 'RACI',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-owner',
          'label' => 
          [
            'de' => 'Data Owner',
            'en' => 'Data Owner',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => 
          [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
        3 => 
        [
          'type' => 'series',
          'id' => 'roles-hub',
          'label' => 
          [
            'de' => 'Roles and Decision Rights',
            'en' => 'Roles and decision rights',
          ],
        ],
      ],
    ],
    107 => 
    [
      'id' => 'operating-model',
      'order' => 1330,
      'category' => 'process',
      'term' => 
      [
        'de' => 'Operating Model',
        'en' => 'Operating Model',
      ],
      'aliases' => 
      [
        0 => 'Governance Operating Model',
        1 => 'Betriebsmodell',
      ],
      'definition' => 
      [
        'de' => 'Cadence, Handoffs, Capacity und Eskalation, die Rollen real machen.',
        'en' => 'Cadence, handoffs, capacity, and escalation that make roles real.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'governance-cadence',
          'label' => 
          [
            'de' => 'Governance Cadence',
            'en' => 'Governance Cadence',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'capacity-model',
          'label' => 
          [
            'de' => 'Stewardship Capacity Model',
            'en' => 'Stewardship Capacity Model',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'escalation-path',
          'label' => 
          [
            'de' => 'Escalation Path',
            'en' => 'Escalation Path',
          ],
        ],
        3 => 
        [
          'type' => 'story',
          'id' => 'operating-and-governing-the-platform',
          'label' => 
          [
            'de' => 'Plattform betreiben und steuern',
            'en' => 'Operating and governing the platform',
          ],
        ],
      ],
    ],
    108 => 
    [
      'id' => 'escalation-path',
      'order' => 1340,
      'category' => 'process',
      'term' => 
      [
        'de' => 'Escalation Path',
        'en' => 'Escalation Path',
      ],
      'aliases' => 
      [
        0 => 'Escalation',
        1 => 'Eskalation',
      ],
      'definition' => 
      [
        'de' => 'Definierter Weg, wenn Steward/Owner/Platform innerhalb des SLA nicht lösen können.',
        'en' => 'Defined route when Steward, Owner, or Platform cannot resolve within SLA.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'operating-model',
          'label' => 
          [
            'de' => 'Operating Model',
            'en' => 'Operating Model',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'sla-slo',
          'label' => 
          [
            'de' => 'SLA / SLO',
            'en' => 'SLA / SLO',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'stewardship-capacity',
          'label' => 
          [
            'de' => 'Stewardship Capacity',
            'en' => 'Stewardship capacity',
          ],
        ],
      ],
    ],
    109 => 
    [
      'id' => 'stewardship-intake',
      'order' => 1350,
      'category' => 'process',
      'term' => 
      [
        'de' => 'Stewardship Intake',
        'en' => 'Stewardship Intake',
      ],
      'aliases' => 
      [
        0 => 'Intake Model',
        1 => 'Intake',
      ],
      'definition' => 
      [
        'de' => 'Priorisierter Eingangspfad für Definitions-, DQ- und Klassifikationsarbeit.',
        'en' => 'Prioritized entry path for definition, DQ, and classification work.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'capacity-model',
          'label' => 
          [
            'de' => 'Stewardship Capacity Model',
            'en' => 'Stewardship Capacity Model',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => 
          [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'stewardship-capacity',
          'label' => 
          [
            'de' => 'Stewardship Capacity',
            'en' => 'Stewardship capacity',
          ],
        ],
      ],
    ],
    110 => 
    [
      'id' => 'capacity-model',
      'order' => 1360,
      'category' => 'process',
      'term' => 
      [
        'de' => 'Stewardship Capacity Model',
        'en' => 'Stewardship Capacity Model',
      ],
      'aliases' => 
      [
        0 => 'Capacity',
        1 => 'Kapazitätsmodell',
      ],
      'definition' => 
      [
        'de' => 'FTE-/geschützte-Zeit-Modell, damit Stewardship finanziert ist — nicht „nebenbei“.',
        'en' => 'FTE or protected-time model so stewardship is funded, not „on the side“.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'stewardship-capacity',
          'label' => 
          [
            'de' => 'Stewardship Capacity',
            'en' => 'Stewardship capacity',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'operating-model',
          'label' => 
          [
            'de' => 'Operating Model',
            'en' => 'Operating Model',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'data-steward',
          'label' => 
          [
            'de' => 'Data Steward',
            'en' => 'Data Steward',
          ],
        ],
      ],
    ],
    111 => 
    [
      'id' => 'governance-cadence',
      'order' => 1370,
      'category' => 'process',
      'term' => 
      [
        'de' => 'Governance Cadence',
        'en' => 'Governance Cadence',
      ],
      'aliases' => 
      [
        0 => 'Cadence',
        1 => 'Governance-Rhythmus',
      ],
      'definition' => 
      [
        'de' => 'Wiederkehrende Foren/Reviews (Klassifikation, Access, KPI, Council].',
        'en' => 'Recurring forums and reviews (classification, access, KPI, council].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'operating-model',
          'label' => 
          [
            'de' => 'Operating Model',
            'en' => 'Operating Model',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'governance-coe',
          'label' => 
          [
            'de' => 'Governance Center of Excellence',
            'en' => 'Governance Center of Excellence',
          ],
        ],
      ],
    ],
    112 => 
    [
      'id' => 'data-lifecycle',
      'order' => 1380,
      'category' => 'process',
      'term' => 
      [
        'de' => 'Data Lifecycle',
        'en' => 'Data Lifecycle',
      ],
      'aliases' => 
      [
        0 => 'Lifecycle',
        1 => 'Retirement',
        2 => 'Datenlebenszyklus',
      ],
      'definition' => 
      [
        'de' => 'Create → Use → Retain/Archive → Delete/Retire mit verantwortlichen Stages.',
        'en' => 'Create → use → retain/archive → delete/retire with accountable stages.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'data-lifecycle-retention',
          'label' => 
          [
            'de' => 'Data Lifecycle & Retention',
            'en' => 'Data lifecycle & retention',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'retention',
          'label' => 
          [
            'de' => 'Retention',
            'en' => 'Retention',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'missing-pieces-data-lifecycle-retirement',
          'label' => 
          [
            'de' => 'Missing Pieces: Lifecycle',
            'en' => 'Missing pieces: lifecycle',
          ],
        ],
      ],
    ],
    113 => 
    [
      'id' => 'role-sprawl',
      'order' => 1390,
      'category' => 'process',
      'term' => 
      [
        'de' => 'Role Sprawl',
        'en' => 'Role Sprawl',
      ],
      'aliases' => 
      [
        0 => 'Role Sprawl',
      ],
      'definition' => 
      [
        'de' => 'Zu viele überlappende RACI-Hüte, die Accountability verwässern.',
        'en' => 'Too many overlapping RACI hats that dilute accountability.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'raci',
          'label' => 
          [
            'de' => 'RACI',
            'en' => 'RACI',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'decision-rights',
          'label' => 
          [
            'de' => 'Decision Rights',
            'en' => 'Decision Rights',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'raci-for-data-governance',
          'label' => 
          [
            'de' => 'RACI für Data Governance',
            'en' => 'RACI for data governance',
          ],
        ],
      ],
    ],
    114 => 
    [
      'id' => 'federated-governance',
      'order' => 1400,
      'category' => 'process',
      'term' => 
      [
        'de' => 'Federated Governance',
        'en' => 'Federated Governance',
      ],
      'aliases' => 
      [
        0 => 'Federated Model',
        1 => 'Federierte Governance',
      ],
      'definition' => 
      [
        'de' => 'Zentrale Standards plus Domänen-Ausführung (vs. rein zentral oder rein lokal].',
        'en' => 'Central standards plus domain execution (vs pure central or pure local].',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-mesh',
          'label' => 
          [
            'de' => 'Data Mesh',
            'en' => 'Data Mesh',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'governance-coe',
          'label' => 
          [
            'de' => 'Governance Center of Excellence',
            'en' => 'Governance Center of Excellence',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'centralized-vs-federated-metadata',
          'label' => 
          [
            'de' => 'Centralized vs Federated Metadata',
            'en' => 'Centralized vs Federated Metadata',
          ],
        ],
      ],
    ],
    115 => 
    [
      'id' => 'policy-as-code',
      'order' => 1410,
      'category' => 'process',
      'term' => 
      [
        'de' => 'Policy as Code',
        'en' => 'Policy as Code',
      ],
      'aliases' => 
      [
        0 => 'PaC',
        1 => 'Policy-as-Code',
      ],
      'definition' => 
      [
        'de' => 'Durchsetzbare Access-/Quality-/Privacy-Regeln in versionierten, testbaren Artefakten.',
        'en' => 'Enforceable access, quality, and privacy rules in versioned, testable artifacts.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-contract',
          'label' => 
          [
            'de' => 'Data Contract',
            'en' => 'Data Contract',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'ci-cd-data',
          'label' => 
          [
            'de' => 'Data CI/CD',
            'en' => 'Data CI/CD',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'missing-pieces-policy-access-governance',
          'label' => 
          [
            'de' => 'Missing Pieces: Policy & Access',
            'en' => 'Missing pieces: policy & access',
          ],
        ],
      ],
    ],
    116 => 
    [
      'id' => 'rag',
      'order' => 1500,
      'category' => 'ai',
      'term' => 
      [
        'de' => 'RAG (Retrieval-Augmented Generation]',
        'en' => 'RAG (Retrieval-Augmented Generation]',
      ],
      'aliases' => 
      [
        0 => 'Retrieval-Augmented Generation',
      ],
      'definition' => 
      [
        'de' => 'LLM-Antworten mit retrieved, governten Dokumenten/Daten begründen.',
        'en' => 'Ground LLM answers in retrieved governed documents or data.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'story',
          'id' => 'ai-rag',
          'label' => 
          [
            'de' => 'AI & RAG',
            'en' => 'AI & RAG',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'ai-ready-metadata',
          'label' => 
          [
            'de' => 'AI-Ready Metadata',
            'en' => 'AI-Ready Metadata',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => 
          [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        3 => 
        [
          'type' => 'path',
          'id' => 'ai-foundations',
          'label' => 
          [
            'de' => 'AI Foundations',
            'en' => 'AI foundations',
          ],
        ],
      ],
    ],
    117 => 
    [
      'id' => 'ai-guardrails',
      'order' => 1510,
      'category' => 'ai',
      'term' => 
      [
        'de' => 'AI Guardrails',
        'en' => 'AI Guardrails',
      ],
      'aliases' => 
      [
        0 => 'Guardrails',
      ],
      'definition' => 
      [
        'de' => 'Kontrollen, die Prompts, Tools und Outputs für Safety/Compliance einschränken.',
        'en' => 'Controls that constrain prompts, tools, and outputs for safety and compliance.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => 
          [
            'de' => 'RAG (Retrieval-Augmented Generation]',
            'en' => 'RAG (Retrieval-Augmented Generation]',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'human-in-the-loop',
          'label' => 
          [
            'de' => 'Human-in-the-Loop',
            'en' => 'Human-in-the-Loop',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'ai-gov',
          'label' => 
          [
            'de' => 'AI Governance',
            'en' => 'AI governance',
          ],
        ],
        3 => 
        [
          'type' => 'path',
          'id' => 'ai-foundations',
          'label' => 
          [
            'de' => 'AI Foundations',
            'en' => 'AI foundations',
          ],
        ],
      ],
    ],
    118 => 
    [
      'id' => 'prompt-injection',
      'order' => 1520,
      'category' => 'ai',
      'term' => 
      [
        'de' => 'Prompt Injection',
        'en' => 'Prompt Injection',
      ],
      'aliases' => 
      [
        0 => 'Injection',
      ],
      'definition' => 
      [
        'de' => 'Angriff, der Modellverhalten über bösartigen Inhalt in Inputs/Context hijackt.',
        'en' => 'Attack that hijacks model behavior via malicious content in inputs or context.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => 
          [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'ai-failures',
          'label' => 
          [
            'de' => 'AI Failures',
            'en' => 'AI failures',
          ],
        ],
      ],
    ],
    119 => 
    [
      'id' => 'hallucination',
      'order' => 1530,
      'category' => 'ai',
      'term' => 
      [
        'de' => 'Hallucination',
        'en' => 'Hallucination',
      ],
      'aliases' => 
      [
        0 => 'Halluzination',
      ],
      'definition' => 
      [
        'de' => 'Selbstbewusstes Modell-Output ohne Grounding in retrieved oder Training-Evidence.',
        'en' => 'Confident model output not grounded in retrieved or training evidence.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => 
          [
            'de' => 'RAG (Retrieval-Augmented Generation]',
            'en' => 'RAG (Retrieval-Augmented Generation]',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => 
          [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        2 => 
        [
          'type' => 'story',
          'id' => 'ai-failures',
          'label' => 
          [
            'de' => 'AI Failures',
            'en' => 'AI failures',
          ],
        ],
      ],
    ],
    120 => 
    [
      'id' => 'human-in-the-loop',
      'order' => 1540,
      'category' => 'ai',
      'term' => 
      [
        'de' => 'Human-in-the-Loop',
        'en' => 'Human-in-the-Loop',
      ],
      'aliases' => 
      [
        0 => 'HITL',
      ],
      'definition' => 
      [
        'de' => 'Pflicht-Human-Review/Approval für risikoreiche AI-Aktionen.',
        'en' => 'Mandatory human review or approval for high-risk AI actions.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'ai-guardrails',
          'label' => 
          [
            'de' => 'AI Guardrails',
            'en' => 'AI Guardrails',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'ai-gov',
          'label' => 
          [
            'de' => 'AI Governance',
            'en' => 'AI governance',
          ],
        ],
      ],
    ],
    121 => 
    [
      'id' => 'training-data',
      'order' => 1550,
      'category' => 'ai',
      'term' => 
      [
        'de' => 'Training Data',
        'en' => 'Training Data',
      ],
      'aliases' => 
      [
        0 => 'Trainingsdaten',
      ],
      'definition' => 
      [
        'de' => 'Datasets zum Trainieren/Fine-Tunen — brauchen Lineage, Rechte und Qualität.',
        'en' => 'Datasets used to train or fine-tune models — need lineage, rights, and quality.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'ai-ready-metadata',
          'label' => 
          [
            'de' => 'AI-Ready Metadata',
            'en' => 'AI-Ready Metadata',
          ],
        ],
        1 => 
        [
          'type' => 'story',
          'id' => 'prepare-metadata-for-ai-rag-and-model-training',
          'label' => 
          [
            'de' => 'Metadaten für AI/RAG vorbereiten',
            'en' => 'Prepare metadata for AI, RAG, and model training',
          ],
        ],
        2 => 
        [
          'type' => 'glossary',
          'id' => 'pii',
          'label' => 
          [
            'de' => 'PII',
            'en' => 'PII',
          ],
        ],
      ],
    ],
    122 => 
    [
      'id' => 'inference',
      'order' => 1560,
      'category' => 'ai',
      'term' => 
      [
        'de' => 'Inference',
        'en' => 'Inference',
      ],
      'aliases' => 
      [
        0 => 'Model Serving',
        1 => 'Inferenz',
      ],
      'definition' => 
      [
        'de' => 'Runtime-Modellausführung gegen neue Inputs.',
        'en' => 'Runtime model execution against new inputs.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'rag',
          'label' => 
          [
            'de' => 'RAG (Retrieval-Augmented Generation]',
            'en' => 'RAG (Retrieval-Augmented Generation]',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'feature-store',
          'label' => 
          [
            'de' => 'Feature Store',
            'en' => 'Feature Store',
          ],
        ],
      ],
    ],
    123 => 
    [
      'id' => 'feature-store',
      'order' => 1570,
      'category' => 'ai',
      'term' => 
      [
        'de' => 'Feature Store',
        'en' => 'Feature Store',
      ],
      'aliases' => 
      [
        0 => 'Feature Store',
      ],
      'definition' => 
      [
        'de' => 'Governte Wiederverwendung von ML-Features mit Versionierung und Serving-Contracts.',
        'en' => 'Governed reuse of ML features with versioning and serving contracts.',
      ],
      'related' => 
      [
        0 => 
        [
          'type' => 'glossary',
          'id' => 'data-product',
          'label' => 
          [
            'de' => 'Data Product',
            'en' => 'Data Product',
          ],
        ],
        1 => 
        [
          'type' => 'glossary',
          'id' => 'training-data',
          'label' => 
          [
            'de' => 'Training Data',
            'en' => 'Training Data',
          ],
        ],
      ],
    ],
  ],
];
