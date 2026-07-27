<?php

/**
 * Compliance Hub catalogue for /compliance.
 *
 * Learning and orientation only — not legal advice.
 * Prefer primary sources (EUR-Lex, BSI, NIST) and link into existing playbooks.
 * Regions come from TaxonomyFoundation (shared IDs).
 */
$taxonomy = require __DIR__.'/foundations/taxonomy.php';

return [
    'categories' => [
        'privacy' => ['de' => 'Privacy', 'en' => 'Privacy'],
        'security' => ['de' => 'Security & Cloud', 'en' => 'Security & cloud'],
        'ai' => ['de' => 'AI', 'en' => 'AI'],
        'retention' => ['de' => 'Aufbewahrung', 'en' => 'Retention'],
        'sector' => ['de' => 'Sektor', 'en' => 'Sector'],
    ],

    'regions' => $taxonomy['regions'],

    'types' => [
        'regulation' => ['de' => 'Verordnung / Gesetz', 'en' => 'Regulation / law'],
        'standard' => ['de' => 'Standard', 'en' => 'Standard'],
        'framework' => ['de' => 'Framework', 'en' => 'Framework'],
    ],

    'items' => require __DIR__ . '/compliance-items.php',

    /*
    |--------------------------------------------------------------------------
    | Consultant certification roadmap
    |--------------------------------------------------------------------------
    |
    | Orientation path for data/governance consultants — not career or legal advice.
    |
    */
    'roadmapPhases' => [
        'foundation' => [
            'order' => 10,
            'label' => ['de' => 'Fundament', 'en' => 'Foundation'],
            'lead' => [
                'de' => 'Gemeinsame Sprache für Daten, Metadaten und Governance — bevor Privacy- oder Security-Zertifikate greifen.',
                'en' => 'Shared language for data, metadata and governance — before privacy or security certs pay off.',
            ],
        ],
        'privacy' => [
            'order' => 20,
            'label' => ['de' => 'Privacy (EU-Fokus)', 'en' => 'Privacy (EU focus)'],
            'lead' => [
                'de' => 'Für Beratung in Europa fast unverzichtbar: Recht, Programm und Technik der Datenschutz-Praxis.',
                'en' => 'Nearly essential for consulting in Europe: law, programme and tech of privacy practice.',
            ],
        ],
        'security' => [
            'order' => 30,
            'label' => ['de' => 'Security & Risiko', 'en' => 'Security & risk'],
            'lead' => [
                'de' => 'Governance ohne Security-Glaubwürdigkeit bleibt Papier — ISMS, Access und Risiko verbinden.',
                'en' => 'Governance without security credibility stays paper — connect ISMS, access and risk.',
            ],
        ],
        'ai' => [
            'order' => 40,
            'label' => ['de' => 'AI Governance', 'en' => 'AI governance'],
            'lead' => [
                'de' => 'Aufbauend auf Privacy/Security: KI-Risiken, EU AI Act und Managementsysteme.',
                'en' => 'Built on privacy/security: AI risk, EU AI Act and management systems.',
            ],
        ],
        'platform' => [
            'order' => 50,
            'label' => ['de' => 'Plattform-Tiefe (optional)', 'en' => 'Platform depth (optional)'],
            'lead' => [
                'de' => 'Nicht Pflicht für „Governance Consultant“, aber stark für Glaubwürdigkeit in Warehouse-/Lakehouse-Projekten.',
                'en' => 'Not required for a “governance consultant”, but strong for credibility in warehouse/lakehouse projects.',
            ],
        ],
    ],

    'roadmapPriorities' => [
        'core' => ['de' => 'Kern', 'en' => 'Core'],
        'recommended' => ['de' => 'Empfohlen', 'en' => 'Recommended'],
        'niche' => ['de' => 'Nische / Vertiefung', 'en' => 'Niche / depth'],
    ],

    'roadmapFocusRegions' => [
        'eu' => ['de' => 'Europa / EU', 'en' => 'Europe / EU'],
        'de' => ['de' => 'Deutschland', 'en' => 'Germany'],
        'intl' => ['de' => 'International', 'en' => 'International'],
        'us' => ['de' => 'USA-orientiert', 'en' => 'US-oriented'],
    ],

    'certifications' => [
        [
            'id' => 'cdmp',
            'phase' => 'foundation',
            'priority' => 'core',
            'focusRegions' => ['intl', 'eu', 'de'],
            'order' => 10,
            'label' => [
                'de' => 'CDMP (DAMA)',
                'en' => 'CDMP (DAMA)',
            ],
            'issuer' => [
                'de' => 'DAMA International',
                'en' => 'DAMA International',
            ],
            'shortPurpose' => [
                'de' => 'Data-Management-Körperwissen (DMBOK): Governance, Metadaten, Qualität, Lifecycle — die gemeinsame Fachsprache. Praxisstart bei Binom: die 8 Pillars.',
                'en' => 'Data management body of knowledge (DMBOK): governance, metadata, quality, lifecycle — the shared professional language. Practical start at Binom: the 8 pillars.',
            ],
            'whyForConsultant' => [
                'de' => 'Hilft, mit Data Owners, Architekten und Stewards auf Augenhöhe zu sprechen — unabhängig vom Cloud-Vendor. DMBOK-Begriffe übersetzt ihr bei uns in operative Säulen und Artefakte.',
                'en' => 'Helps you speak peer-to-peer with data owners, architects and stewards — independent of cloud vendor. Translate DMBOK terms into operational pillars and artifacts here.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'DMBOK-Grundlagen (Data Governance, Metadata, DQ, Security, Lifecycle)',
                    'Stufen: Associate → Practitioner → Master (je nach Erfahrung)',
                    'Praxisbezug: Policies, Katalog, Ownership-Modelle — Mapping zu den 8 Pillars',
                    'Start bei Binom: Playbook „Die 8 Säulen der Data Governance“',
                ],
                'en' => [
                    'DMBOK basics (data governance, metadata, DQ, security, lifecycle)',
                    'Levels: Associate → Practitioner → Master (by experience)',
                    'Practice link: policies, catalog, ownership models — mapped to the 8 pillars',
                    'Start at Binom: playbook “The 8 Pillars of Data Governance”',
                ],
            ],
            'officialSources' => [
                [
                    'label' => ['de' => 'DAMA — CDMP', 'en' => 'DAMA — CDMP'],
                    'href' => 'https://www.dama.org/cpages/cdmp-overview',
                ],
            ],
            'relatedFrameworks' => [],
            'relatedPlaybooks' => ['eight-pillars', 'metadata-catalog-lineage', 'what-metadata-actually-is'],
        ],
        [
            'id' => 'cipp-e',
            'phase' => 'privacy',
            'priority' => 'core',
            'focusRegions' => ['eu', 'de'],
            'order' => 20,
            'label' => [
                'de' => 'CIPP/E (IAPP)',
                'en' => 'CIPP/E (IAPP)',
            ],
            'issuer' => [
                'de' => 'IAPP',
                'en' => 'IAPP',
            ],
            'shortPurpose' => [
                'de' => 'Europäisches Datenschutzrecht und -praxis — der Standard-Nachweis für Privacy-Beratung in der EU.',
                'en' => 'European privacy law and practice — the default credential for privacy consulting in the EU.',
            ],
            'whyForConsultant' => [
                'de' => 'Kunden und Legal erwarten oft CIPP/E als Signal, dass du DSGVO-Konzepte, Rechte und Transfers einordnen kannst.',
                'en' => 'Clients and legal teams often expect CIPP/E as a signal you can frame GDPR concepts, rights and transfers.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'DSGVO-Prinzipien, Rechtsgrundlagen, Betroffenenrechte',
                    'Rollen (Verantwortlicher / Auftragsverarbeiter), AV-Verträge',
                    'Internationale Transfers, Aufsicht, Bußgelder (Überblick)',
                    'IAPP-Examenvorbereitung + Berufserfahrung hilft stark',
                ],
                'en' => [
                    'GDPR principles, lawful bases, data-subject rights',
                    'Roles (controller / processor), processor agreements',
                    'International transfers, supervision, fines (overview)',
                    'IAPP exam prep + work experience helps a lot',
                ],
            ],
            'officialSources' => [
                [
                    'label' => ['de' => 'IAPP — CIPP/E', 'en' => 'IAPP — CIPP/E'],
                    'href' => 'https://iapp.org/certify/cippe/',
                ],
            ],
            'relatedFrameworks' => ['gdpr', 'bdsg', 'international-transfers'],
            'relatedPlaybooks' => ['pii-privacy-governance', 'dsdr-governance', 'host-vs-cloud'],
        ],
        [
            'id' => 'cipm',
            'phase' => 'privacy',
            'priority' => 'recommended',
            'focusRegions' => ['eu', 'de', 'intl'],
            'order' => 30,
            'label' => [
                'de' => 'CIPM (IAPP)',
                'en' => 'CIPM (IAPP)',
            ],
            'issuer' => [
                'de' => 'IAPP',
                'en' => 'IAPP',
            ],
            'shortPurpose' => [
                'de' => 'Privacy-Programm-Management: Governance, Ops, Metrics — wie man Privacy im Unternehmen betreibt.',
                'en' => 'Privacy programme management: governance, ops, metrics — how to run privacy in the organisation.',
            ],
            'whyForConsultant' => [
                'de' => 'Passt zu Operating-Model-Arbeit: Rollen, Policies, Evidence, nicht nur Gesetzeswissen.',
                'en' => 'Fits operating-model work: roles, policies, evidence — not only legal knowledge.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'Privacy-by-Design in Projekten',
                    'Inventare, DPIA-Prozesse, Vendor-Management',
                    'Metriken und Reporting an Leadership',
                ],
                'en' => [
                    'Privacy by design in projects',
                    'Inventories, DPIA processes, vendor management',
                    'Metrics and reporting to leadership',
                ],
            ],
            'officialSources' => [
                [
                    'label' => ['de' => 'IAPP — CIPM', 'en' => 'IAPP — CIPM'],
                    'href' => 'https://iapp.org/certify/cipm/',
                ],
            ],
            'relatedFrameworks' => ['gdpr'],
            'relatedPlaybooks' => ['pii-privacy-governance', 'missing-pieces-policy-access-governance'],
        ],
        [
            'id' => 'cipt',
            'phase' => 'privacy',
            'priority' => 'recommended',
            'focusRegions' => ['eu', 'intl'],
            'order' => 40,
            'label' => [
                'de' => 'CIPT (IAPP)',
                'en' => 'CIPT (IAPP)',
            ],
            'issuer' => [
                'de' => 'IAPP',
                'en' => 'IAPP',
            ],
            'shortPurpose' => [
                'de' => 'Privacy Engineering / Technology — Masking, Minimierung, Security-Controls in Systemen.',
                'en' => 'Privacy engineering / technology — masking, minimisation, security controls in systems.',
            ],
            'whyForConsultant' => [
                'de' => 'Brücke zwischen Legal und Platform: genau dort, wo dbt-Meta, Masking und Access sitzen.',
                'en' => 'Bridge between legal and platform: exactly where dbt meta, masking and access live.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'Technische Controls (Encryption, Pseudonymisierung, Access)',
                    'Data Lifecycle in Systemen',
                    'Zusammenarbeit mit Security- und Data-Teams',
                ],
                'en' => [
                    'Technical controls (encryption, pseudonymisation, access)',
                    'Data lifecycle in systems',
                    'Collaboration with security and data teams',
                ],
            ],
            'officialSources' => [
                [
                    'label' => ['de' => 'IAPP — CIPT', 'en' => 'IAPP — CIPT'],
                    'href' => 'https://iapp.org/certify/cipt/',
                ],
            ],
            'relatedFrameworks' => ['gdpr', 'iso-27001'],
            'relatedPlaybooks' => [
                'pii-privacy-governance',
                'access-security-governance',
                'propagating-pii-metadata-across-data-warehouses',
            ],
        ],
        [
            'id' => 'dsb-de',
            'phase' => 'privacy',
            'priority' => 'recommended',
            'focusRegions' => ['de'],
            'order' => 50,
            'label' => [
                'de' => 'Fachkunde Datenschutz / DSB (DE)',
                'en' => 'Data protection officer competence (Germany)',
            ],
            'issuer' => [
                'de' => 'Diverse Anbieter (z. B. TÜV, Bitkom Akademie) — keine einzige globale Marke',
                'en' => 'Various providers (e.g. TÜV, Bitkom Akademie) — not one global brand',
            ],
            'shortPurpose' => [
                'de' => 'Deutsche Erwartung an Fachkunde für Datenschutzbeauftragte und Beratung im DE-Kontext (BDSG + Praxis).',
                'en' => 'German expectation of competence for DPOs and consulting in a DE context (BDSG + practice).',
            ],
            'whyForConsultant' => [
                'de' => 'In DE-Ausschreibungen und Behördennähe oft relevant — ergänzt CIPP/E um lokale Praxis und DSB-Rolle.',
                'en' => 'Often relevant in German RFPs and public-sector work — complements CIPP/E with local practice and DPO role.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'BDSG + Beschäftigtendaten im Überblick',
                    'DSB-Aufgaben, Interessenkonflikte, Dokumentation',
                    'Anbieter und Umfang vergleichen — Qualität > Logo',
                ],
                'en' => [
                    'BDSG + employee data overview',
                    'DPO duties, conflicts of interest, documentation',
                    'Compare providers and scope — quality over logo',
                ],
            ],
            'officialSources' => [
                [
                    'label' => ['de' => 'BfDI — Datenschutzbeauftragte', 'en' => 'BfDI — data protection officers'],
                    'href' => 'https://www.bfdi.bund.de/DE/Buerger/Inhalte/Allgemein/Datenschutzbeauftragte/Datenschutzbeauftragte_node.html',
                ],
            ],
            'relatedFrameworks' => ['bdsg', 'gdpr'],
            'relatedPlaybooks' => ['pii-privacy-governance', 'dsdr-governance'],
        ],
        [
            'id' => 'iso27001-li',
            'phase' => 'security',
            'priority' => 'core',
            'focusRegions' => ['eu', 'de', 'intl'],
            'order' => 60,
            'label' => [
                'de' => 'ISO/IEC 27001 Lead Implementer (oder vergleichbar)',
                'en' => 'ISO/IEC 27001 Lead Implementer (or equivalent)',
            ],
            'issuer' => [
                'de' => 'Diverse akkreditierte Schulungsanbieter',
                'en' => 'Various accredited training providers',
            ],
            'shortPurpose' => [
                'de' => 'ISMS aufbauen und betreiben — Controls, SoA, Risiko, Audit-Logik.',
                'en' => 'Build and run an ISMS — controls, SoA, risk, audit logic.',
            ],
            'whyForConsultant' => [
                'de' => 'Übersetzt Security-Anforderungen in nachvollziehbare Controls — Anschluss an Access-, Logging- und Vendor-Themen.',
                'en' => 'Translates security needs into traceable controls — links to access, logging and vendor themes.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'ISMS-Scope, Risikoanalyse, SoA',
                    'Annex-A-Controls im Überblick (Access, Crypto, Ops)',
                    'Lead Implementer vs. Lead Auditor je nach Rolle wählen',
                ],
                'en' => [
                    'ISMS scope, risk analysis, SoA',
                    'Annex A controls overview (access, crypto, ops)',
                    'Choose Lead Implementer vs Lead Auditor by role',
                ],
            ],
            'officialSources' => [
                [
                    'label' => ['de' => 'ISO — ISO/IEC 27001', 'en' => 'ISO — ISO/IEC 27001'],
                    'href' => 'https://www.iso.org/standard/27001',
                ],
            ],
            'relatedFrameworks' => ['iso-27001', 'bsi-c5', 'soc-2'],
            'relatedPlaybooks' => ['access-security-governance', 'host-vs-cloud'],
        ],
        [
            'id' => 'cism',
            'phase' => 'security',
            'priority' => 'recommended',
            'focusRegions' => ['intl', 'eu', 'us'],
            'order' => 70,
            'label' => [
                'de' => 'CISM (ISACA)',
                'en' => 'CISM (ISACA)',
            ],
            'issuer' => [
                'de' => 'ISACA',
                'en' => 'ISACA',
            ],
            'shortPurpose' => [
                'de' => 'Information Security Management — Governance, Risiko, Programm, Incident — management-lastig.',
                'en' => 'Information security management — governance, risk, programme, incident — management-oriented.',
            ],
            'whyForConsultant' => [
                'de' => 'Stärker „Führung & Programm“ als rein technische Security — gut für Governance-Beratung.',
                'en' => 'More “leadership & programme” than pure technical security — good for governance consulting.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'Security Governance und Strategy',
                    'Information Risk Management',
                    'Security Programme + Incident Management',
                    'Erfahrung + Exam (ISACA-Voraussetzungen prüfen)',
                ],
                'en' => [
                    'Security governance and strategy',
                    'Information risk management',
                    'Security programme + incident management',
                    'Experience + exam (check ISACA prerequisites)',
                ],
            ],
            'officialSources' => [
                [
                    'label' => ['de' => 'ISACA — CISM', 'en' => 'ISACA — CISM'],
                    'href' => 'https://www.isaca.org/credentialing/cism',
                ],
            ],
            'relatedFrameworks' => ['iso-27001', 'nist-zero-trust'],
            'relatedPlaybooks' => ['access-security-governance', 'missing-pieces-policy-access-governance'],
        ],
        [
            'id' => 'crisc',
            'phase' => 'security',
            'priority' => 'niche',
            'focusRegions' => ['intl', 'eu', 'us'],
            'order' => 80,
            'label' => [
                'de' => 'CRISC (ISACA)',
                'en' => 'CRISC (ISACA)',
            ],
            'issuer' => [
                'de' => 'ISACA',
                'en' => 'ISACA',
            ],
            'shortPurpose' => [
                'de' => 'IT-Risiko und Controls — nützlich wenn Governance stark über Risiko-Frameworks läuft.',
                'en' => 'IT risk and controls — useful when governance runs heavily through risk frameworks.',
            ],
            'whyForConsultant' => [
                'de' => 'Vertiefung für Risiko-Workshops, Control-Mapping und Audit-nahe Projekte.',
                'en' => 'Depth for risk workshops, control mapping and audit-adjacent projects.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'IT Risk Identification & Assessment',
                    'Risk Response & Reporting',
                    'Information System Control Design/Implementation',
                ],
                'en' => [
                    'IT risk identification & assessment',
                    'Risk response & reporting',
                    'Information system control design/implementation',
                ],
            ],
            'officialSources' => [
                [
                    'label' => ['de' => 'ISACA — CRISC', 'en' => 'ISACA — CRISC'],
                    'href' => 'https://www.isaca.org/credentialing/crisc',
                ],
            ],
            'relatedFrameworks' => ['iso-27001', 'nis2', 'dora'],
            'relatedPlaybooks' => ['access-security-governance'],
        ],
        [
            'id' => 'aigp',
            'phase' => 'ai',
            'priority' => 'recommended',
            'focusRegions' => ['eu', 'intl', 'us'],
            'order' => 90,
            'label' => [
                'de' => 'AIGP (IAPP)',
                'en' => 'AIGP (IAPP)',
            ],
            'issuer' => [
                'de' => 'IAPP',
                'en' => 'IAPP',
            ],
            'shortPurpose' => [
                'de' => 'AI Governance Professional — Risiken, Controls und Programm für KI-Systeme.',
                'en' => 'AI Governance Professional — risks, controls and programme for AI systems.',
            ],
            'whyForConsultant' => [
                'de' => 'Passt zum EU AI Act und zu Kunden, die KI in Datenprodukten einsetzen — Anschluss an Privacy/Security.',
                'en' => 'Fits the EU AI Act and clients using AI in data products — links to privacy/security.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'AI-Risikoklassen und Governance-Strukturen',
                    'Dokumentation, Oversight, Vendor-/Model-Risiken',
                    'Idealerweise CIPP/E oder Security-Basis vorher',
                ],
                'en' => [
                    'AI risk classes and governance structures',
                    'Documentation, oversight, vendor/model risks',
                    'Ideally CIPP/E or a security base first',
                ],
            ],
            'officialSources' => [
                [
                    'label' => ['de' => 'IAPP — AIGP', 'en' => 'IAPP — AIGP'],
                    'href' => 'https://iapp.org/certify/aigp/',
                ],
            ],
            'relatedFrameworks' => ['eu-ai-act', 'nist-ai-rmf', 'iso-42001'],
            'relatedPlaybooks' => ['ai-gov', 'ai-eval', 'ai-failures'],
        ],
        [
            'id' => 'snowflake-dbt',
            'phase' => 'platform',
            'priority' => 'niche',
            'focusRegions' => ['intl', 'eu', 'de', 'us'],
            'order' => 100,
            'label' => [
                'de' => 'Snowflake / dbt Zertifizierungen (Beispiel-Stack)',
                'en' => 'Snowflake / dbt certifications (example stack)',
            ],
            'issuer' => [
                'de' => 'Snowflake / dbt Labs',
                'en' => 'Snowflake / dbt Labs',
            ],
            'shortPurpose' => [
                'de' => 'Plattform-Glaubwürdigkeit: du kannst Governance in konkreten Tools verankern (Meta, Tests, Policies).',
                'en' => 'Platform credibility: you can anchor governance in concrete tools (meta, tests, policies).',
            ],
            'whyForConsultant' => [
                'de' => 'Optional — macht dich in Delivery-Teams glaubwürdiger, ersetzt aber keine Privacy-/Security-Basis.',
                'en' => 'Optional — makes you more credible with delivery teams, but does not replace a privacy/security base.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'Je nach Kundenstack wählen (Snowflake, Databricks, Fabric, …)',
                    'Fokus auf Governance-Features (Roles, Masking, Meta, Tests)',
                    'Mit Resources-Hub und Playbooks kombinieren',
                ],
                'en' => [
                    'Choose by client stack (Snowflake, Databricks, Fabric, …)',
                    'Focus on governance features (roles, masking, meta, tests)',
                    'Combine with the Resources hub and playbooks',
                ],
            ],
            'officialSources' => [
                [
                    'label' => ['de' => 'Snowflake — Certification', 'en' => 'Snowflake — Certification'],
                    'href' => 'https://learn.snowflake.com/en/certifications/',
                ],
                [
                    'label' => ['de' => 'dbt — Certification', 'en' => 'dbt — Certification'],
                    'href' => 'https://www.getdbt.com/certifications',
                ],
            ],
            'relatedFrameworks' => [],
            'relatedPlaybooks' => [
                'metadata-driven-governance-with-dbt-meta',
                'end-to-end-governance-architecture',
            ],
        ],
    ],

    'roadmapTips' => [
        'de' => [
            'Europa-Pfad (pragmatisch): CDMP (Fundament) → CIPP/E → CIPM oder CIPT → ISO 27001 LI oder CISM → AIGP.',
            'Deutschland: CIPP/E + lokale DSB-/Fachkunde-Weiterbildung; BDSG und Behördenkontext nicht unterschätzen.',
            'Nicht alles gleichzeitig — erst Privacy- oder Security-Kern, dann AI und Plattform.',
            'Zertifikate ersetzen keine Projekt-Evidence: Policies, Controls und Playbooks zählen im Auftrag.',
        ],
        'en' => [
            'Europe path (pragmatic): CDMP (foundation) → CIPP/E → CIPM or CIPT → ISO 27001 LI or CISM → AIGP.',
            'Germany: CIPP/E plus local DPO/competence training; do not underestimate BDSG and public-sector context.',
            'Not everything at once — lock a privacy or security core first, then AI and platform.',
            'Certs do not replace project evidence: policies, controls and playbooks win the engagement.',
        ],
    ],
];
