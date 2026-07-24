<?php

/**
 * Compliance Hub catalogue for /compliance.
 *
 * Learning and orientation only — not legal advice.
 * Prefer primary sources (EUR-Lex, BSI, NIST) and link into existing playbooks.
 */
return [
    'categories' => [
        'privacy' => ['de' => 'Privacy', 'en' => 'Privacy'],
        'security' => ['de' => 'Security & Cloud', 'en' => 'Security & cloud'],
        'ai' => ['de' => 'AI', 'en' => 'AI'],
        'retention' => ['de' => 'Aufbewahrung', 'en' => 'Retention'],
        'sector' => ['de' => 'Sektor', 'en' => 'Sector'],
    ],

    'regions' => [
        'eu' => ['de' => 'EU', 'en' => 'EU'],
        'de' => ['de' => 'Deutschland', 'en' => 'Germany'],
        'us' => ['de' => 'USA', 'en' => 'US'],
        'intl' => ['de' => 'International', 'en' => 'International'],
    ],

    'types' => [
        'regulation' => ['de' => 'Verordnung / Gesetz', 'en' => 'Regulation / law'],
        'standard' => ['de' => 'Standard', 'en' => 'Standard'],
        'framework' => ['de' => 'Framework', 'en' => 'Framework'],
    ],

    'items' => [
        [
            'id' => 'gdpr',
            'category' => 'privacy',
            'region' => 'eu',
            'type' => 'regulation',
            'depth' => 'full',
            'order' => 10,
            'label' => [
                'de' => 'DSGVO / GDPR',
                'en' => 'GDPR / DSGVO',
            ],
            'shortPurpose' => [
                'de' => 'EU-Grundverordnung zum Schutz personenbezogener Daten — Rechte der Betroffenen und Pflichten der Verantwortlichen.',
                'en' => 'EU baseline regulation for personal data — data-subject rights and controller obligations.',
            ],
            'whyItMatters' => [
                'de' => 'Fast jedes Analytics-, Warehouse- und AI-Setup verarbeitet personenbezogene Daten. Die DSGVO steuert Zweckbindung, Minimierung, Transparenz, Löschung und Nachweisbarkeit — genau die Themen eurer Governance-Säulen PII, DSDR und Lifecycle.',
                'en' => 'Almost every analytics, warehouse and AI setup processes personal data. GDPR drives purpose limitation, minimisation, transparency, deletion and evidence — the same themes as your PII, DSDR and lifecycle pillars.',
            ],
            'appliesTo' => [
                'de' => 'Verantwortliche und Auftragsverarbeiter mit Bezug zur EU/EWR (Angebot an Betroffene oder Beobachtung von Verhalten) — unabhängig vom Sitz des Unternehmens.',
                'en' => 'Controllers and processors with an EU/EEA nexus (offering goods/services to data subjects or monitoring behaviour) — regardless of where the company is based.',
            ],
            'keyRules' => [
                'de' => [
                    'Rechtmäßigkeit, Treu und Glauben, Transparenz (Art. 5 Abs. 1 lit. a)',
                    'Zweckbindung und Datenminimierung (Art. 5 Abs. 1 lit. b–c)',
                    'Speicherbegrenzung — nicht länger als nötig (Art. 5 Abs. 1 lit. e)',
                    'Integrität und Vertraulichkeit — TOMs (Art. 5 Abs. 1 lit. f, Art. 32)',
                    'Rechenschaftspflicht — nachweisbare Controls (Art. 5 Abs. 2)',
                    'Betroffenenrechte inkl. Auskunft und Löschung (Art. 12–22, Art. 17)',
                    'Auftragsverarbeitung nur mit Vertrag (Art. 28)',
                    'Datenschutz-Folgenabschätzung bei hohem Risiko (Art. 35)',
                ],
                'en' => [
                    'Lawfulness, fairness and transparency (Art. 5(1)(a))',
                    'Purpose limitation and data minimisation (Art. 5(1)(b)–(c))',
                    'Storage limitation — no longer than necessary (Art. 5(1)(e))',
                    'Integrity and confidentiality — technical/organisational measures (Art. 5(1)(f), Art. 32)',
                    'Accountability — demonstrable controls (Art. 5(2))',
                    'Data-subject rights including access and erasure (Arts. 12–22, Art. 17)',
                    'Processors only under a contract (Art. 28)',
                    'DPIA for high-risk processing (Art. 35)',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'PII-Klassifikation und Masking in Warehouse/BI (Meta → Policy → Runtime).',
                    'Lösch- und Sperrpfade (DSDR) über Rohdaten, Historie und Exports hinweg.',
                    'Retention-Schedules und Archiv vs. produktiv trennen.',
                    'Access Reviews, Least Privilege und Audit-Logs als Evidence.',
                    'Vendor-/Cloud-Auswahl inkl. AV-Vertrag und Residenz.',
                ],
                'en' => [
                    'PII classification and masking in warehouse/BI (meta → policy → runtime).',
                    'Deletion and restriction paths (DSDR) across raw, history and exports.',
                    'Retention schedules; separate archive vs production.',
                    'Access reviews, least privilege and audit logs as evidence.',
                    'Vendor/cloud selection including processor terms and residency.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'Verordnung (EU) 2016/679 — EUR-Lex',
                        'en' => 'Regulation (EU) 2016/679 — EUR-Lex',
                    ],
                    'href' => 'https://eur-lex.europa.eu/eli/reg/2016/679/oj',
                ],
                [
                    'label' => [
                        'de' => 'EDPB — Guidelines & Recommendations',
                        'en' => 'EDPB — Guidelines & recommendations',
                    ],
                    'href' => 'https://www.edpb.europa.eu/our-work-tools/general-guidance/guidelines-recommendations-best-practices_en',
                ],
            ],
            'relatedPlaybooks' => [
                'pii-privacy-governance',
                'dsdr-governance',
                'data-lifecycle-retention',
                'access-security-governance',
                'host-vs-cloud',
            ],
        ],
        [
            'id' => 'bdsg',
            'category' => 'privacy',
            'region' => 'de',
            'type' => 'regulation',
            'depth' => 'full',
            'order' => 20,
            'label' => [
                'de' => 'BDSG',
                'en' => 'BDSG (German Federal Data Protection Act)',
            ],
            'shortPurpose' => [
                'de' => 'Deutsches Ergänzungsgesetz zur DSGVO — Öffnungsklauseln, Beschäftigtendaten und nationale Besonderheiten.',
                'en' => 'German companion act to the GDPR — opening clauses, employee data and national specifics.',
            ],
            'whyItMatters' => [
                'de' => 'In DE-Projekten reichen DSGVO-Prinzipien allein oft nicht: Beschäftigtendaten, besondere Kategorien und Behördenkontexte brauchen die BDSG-Leseart zusätzlich zur DSGVO.',
                'en' => 'In German projects GDPR principles alone are often not enough: employee data, special categories and public-sector contexts need the BDSG reading on top of the GDPR.',
            ],
            'appliesTo' => [
                'de' => 'Öffentliche und nicht-öffentliche Stellen in Deutschland; relevant vor allem bei Beschäftigtendaten und nationalen Öffnungsklauseln.',
                'en' => 'Public and non-public bodies in Germany; especially relevant for employee data and national opening clauses.',
            ],
            'keyRules' => [
                'de' => [
                    'BDSG ergänzt die DSGVO — nicht ersetzt sie',
                    'Besonderheiten bei Beschäftigtendaten (§ 26 BDSG)',
                    'Nationale Regelungen zu besonderen Verarbeitungssituationen nutzen Öffnungsklauseln',
                    'Aufsicht und Bußgeldrahmen im deutschen Kontext beachten',
                    'Mit Betriebsrat / Mitbestimmung abstimmen, wo Personaldaten betroffen sind',
                ],
                'en' => [
                    'BDSG supplements the GDPR — it does not replace it',
                    'Special rules for employee data (Section 26 BDSG)',
                    'National rules for specific processing situations use GDPR opening clauses',
                    'Supervisory and fine context in Germany still matters',
                    'Align with works council / co-determination where HR data is involved',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'HR-, CRM- und Support-Quellen explizit als Beschäftigten-/Kunden-PII kennzeichnen.',
                    'Löschkonzepte mit Betriebsvereinbarungen und gesetzlichen Fristen abstimmen.',
                    'Deutsche Aufsichtspraxis in Evidence- und Policy-Texten berücksichtigen.',
                ],
                'en' => [
                    'Label HR, CRM and support sources explicitly as employee/customer PII.',
                    'Align deletion designs with works agreements and statutory retention.',
                    'Reflect German supervisory practice in evidence and policy wording.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'BDSG — gesetze-im-internet.de',
                        'en' => 'BDSG — gesetze-im-internet.de',
                    ],
                    'href' => 'https://www.gesetze-im-internet.de/bdsg_2018/',
                ],
                [
                    'label' => [
                        'de' => 'BfDI — Bundesbeauftragte für den Datenschutz',
                        'en' => 'BfDI — Federal Commissioner for Data Protection',
                    ],
                    'href' => 'https://www.bfdi.bund.de/',
                ],
            ],
            'relatedPlaybooks' => [
                'pii-privacy-governance',
                'dsdr-governance',
                'data-lifecycle-retention',
            ],
        ],
        [
            'id' => 'international-transfers',
            'category' => 'privacy',
            'region' => 'eu',
            'type' => 'regulation',
            'depth' => 'full',
            'order' => 30,
            'label' => [
                'de' => 'Internationale Transfers (Schrems II / SCC)',
                'en' => 'International transfers (Schrems II / SCCs)',
            ],
            'shortPurpose' => [
                'de' => 'Regeln für die Übermittlung personenbezogener Daten in Drittländer — Angemessenheit, Standardvertragsklauseln und Zusatzmaßnahmen.',
                'en' => 'Rules for transferring personal data to third countries — adequacy, standard contractual clauses and supplementary measures.',
            ],
            'whyItMatters' => [
                'de' => 'Cloud-Region, Support-Zugriff und SaaS-Subprozessoren entscheiden, ob Daten die EU verlassen. Transfer-Mechanismen sind oft der Engpass bei Hosting- und Vendor-Entscheidungen.',
                'en' => 'Cloud region, support access and SaaS subprocessors decide whether data leaves the EU. Transfer mechanisms are often the bottleneck in hosting and vendor decisions.',
            ],
            'appliesTo' => [
                'de' => 'Jedes Setup mit Drittland-Empfängern: US-Cloud, globale SaaS, Offshore-Support, globale Identity-Provider.',
                'en' => 'Any setup with third-country recipients: US cloud, global SaaS, offshore support, global identity providers.',
            ],
            'keyRules' => [
                'de' => [
                    'Grundsatz: Übermittlung nur mit geeignetem Mechanismus (Kapitel V DSGVO)',
                    'Angemessenheitsbeschluss wo vorhanden nutzen',
                    'Sonst typischerweise EU-Standardvertragsklauseln (SCCs)',
                    'Transfer Impact Assessment und ggf. Zusatzmaßnahmen (Schrems-II-Logik)',
                    'Subprozessoren und Support-Zugriffe transparent halten',
                    'Residenz allein ersetzt keinen Transfer-Mechanismus, wenn Zugriff aus dem Drittland möglich ist',
                ],
                'en' => [
                    'Principle: transfer only with an appropriate Chapter V mechanism',
                    'Use an adequacy decision where one exists',
                    'Otherwise typically EU Standard Contractual Clauses (SCCs)',
                    'Transfer impact assessment and supplementary measures where needed (Schrems II logic)',
                    'Keep subprocessors and support access transparent',
                    'Residency alone is not enough if third-country access remains possible',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'Cloud- vs. Self-Hosted-Entscheidung mit Transfer-Risiko bewerten.',
                    'Region, Encryption Keys und Admin-Zugriff in Architecture Fit dokumentieren.',
                    'Vendor-Compliance-Seiten und AV-/SCC-Status in Resources nachverfolgen.',
                ],
                'en' => [
                    'Evaluate cloud vs self-hosted with transfer risk in mind.',
                    'Document region, encryption keys and admin access in architecture fit.',
                    'Track vendor compliance pages and SCC/processor status in Resources.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'EU-Kommission — Standardvertragsklauseln',
                        'en' => 'European Commission — Standard Contractual Clauses',
                    ],
                    'href' => 'https://commission.europa.eu/law/law-topic/data-protection/international-dimension-data-protection/standard-contractual-clauses-scc_en',
                ],
                [
                    'label' => [
                        'de' => 'EuGH — Schrems II (C-311/18)',
                        'en' => 'CJEU — Schrems II (C-311/18)',
                    ],
                    'href' => 'https://curia.europa.eu/juris/documents.jsf?num=C-311/18',
                ],
                [
                    'label' => [
                        'de' => 'EDPB — Recommendations 01/2020 (Supplementary Measures)',
                        'en' => 'EDPB — Recommendations 01/2020 (supplementary measures)',
                    ],
                    'href' => 'https://www.edpb.europa.eu/our-work-tools/our-documents/recommendations/recommendations-012020-measures-supplement-transfer_en',
                ],
            ],
            'relatedPlaybooks' => [
                'host-vs-cloud',
                'pii-privacy-governance',
                'cloud-hosting',
            ],
        ],
        [
            'id' => 'iso-27001',
            'category' => 'security',
            'region' => 'intl',
            'type' => 'standard',
            'depth' => 'full',
            'order' => 40,
            'label' => [
                'de' => 'ISO/IEC 27001',
                'en' => 'ISO/IEC 27001',
            ],
            'shortPurpose' => [
                'de' => 'Internationaler Standard für ein Informationssicherheits-Managementsystem (ISMS) — Risiken, Controls und kontinuierliche Verbesserung.',
                'en' => 'International standard for an information security management system (ISMS) — risk, controls and continual improvement.',
            ],
            'whyItMatters' => [
                'de' => 'ISO 27001 ist die gemeinsame Sprache für Security-Nachweise gegenüber Kunden und Auditoren. Für Data Platforms übersetzt sie sich in Access, Logging, Change und Vendor-Management.',
                'en' => 'ISO 27001 is the shared language for security evidence with customers and auditors. For data platforms it maps to access, logging, change and vendor management.',
            ],
            'appliesTo' => [
                'de' => 'Organisationen, die ein zertifizierbares ISMS aufbauen oder von Vendoren ein ISMS erwarten.',
                'en' => 'Organisations building a certifiable ISMS or expecting one from vendors.',
            ],
            'keyRules' => [
                'de' => [
                    'ISMS-Scope und Kontext der Organisation festlegen',
                    'Informationssicherheits-Risikobewertung und Behandlung',
                    'Statement of Applicability (SoA) für Controls',
                    'Leadership, Rollen und Kompetenz',
                    'Betrieb, Monitoring, interne Audits, Management Review',
                    'Kontinuierliche Verbesserung (PDCA)',
                ],
                'en' => [
                    'Define ISMS scope and organisational context',
                    'Information security risk assessment and treatment',
                    'Statement of Applicability (SoA) for controls',
                    'Leadership, roles and competence',
                    'Operation, monitoring, internal audits, management review',
                    'Continual improvement (PDCA)',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'Access Governance und Privileged Access als Controls führen.',
                    'Change-/Release-Nachweise für dbt, Warehouse und BI sammeln.',
                    'Vendor-ISO-Zertifikate in Resources prüfen — Scope lesen, nicht nur Logo.',
                ],
                'en' => [
                    'Treat access governance and privileged access as named controls.',
                    'Collect change/release evidence for dbt, warehouse and BI.',
                    'Check vendor ISO certificates in Resources — read the scope, not just the logo.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'ISO — ISO/IEC 27001 (Produktseite)',
                        'en' => 'ISO — ISO/IEC 27001 (product page)',
                    ],
                    'href' => 'https://www.iso.org/standard/27001',
                ],
                [
                    'label' => [
                        'de' => 'ISO — ISO/IEC 27002 (Controls)',
                        'en' => 'ISO — ISO/IEC 27002 (controls)',
                    ],
                    'href' => 'https://www.iso.org/standard/75652.html',
                ],
            ],
            'relatedPlaybooks' => [
                'access-security-governance',
                'missing-pieces-policy-access-governance',
                'host-vs-cloud',
            ],
        ],
        [
            'id' => 'soc-2',
            'category' => 'security',
            'region' => 'us',
            'type' => 'framework',
            'depth' => 'full',
            'order' => 50,
            'label' => [
                'de' => 'SOC 2',
                'en' => 'SOC 2',
            ],
            'shortPurpose' => [
                'de' => 'AICPA-Prüfbericht zu Trust Services Criteria (Security, Availability, Confidentiality, Processing Integrity, Privacy) — typisch für SaaS-Vendoren.',
                'en' => 'AICPA attestation report on Trust Services Criteria (security, availability, confidentiality, processing integrity, privacy) — common for SaaS vendors.',
            ],
            'whyItMatters' => [
                'de' => 'Viele Cloud- und SaaS-Anbieter liefern SOC-2-Reports statt ISO. Für Einkauf und Architektur ist entscheidend: Type I vs II, Scope und welche Criteria abgedeckt sind.',
                'en' => 'Many cloud and SaaS vendors provide SOC 2 reports instead of ISO. For procurement and architecture what matters is Type I vs II, scope and which criteria are covered.',
            ],
            'appliesTo' => [
                'de' => 'Service-Organisationen (oft US/global SaaS) und deren Kunden, die Vendor-Assurance brauchen.',
                'en' => 'Service organisations (often US/global SaaS) and their customers needing vendor assurance.',
            ],
            'keyRules' => [
                'de' => [
                    'Trust Services Criteria — Security ist fast immer in Scope',
                    'Type I: Design zu einem Zeitpunkt; Type II: Wirksamkeit über einen Zeitraum',
                    'Report-Scope und Systembeschreibung genau lesen',
                    'Ausnahmen und Complementary User Entity Controls (CUECs) beachten',
                    'SOC 2 ist kein Datenschutzgesetz — Privacy-Criteria ≠ DSGVO-Konformität',
                ],
                'en' => [
                    'Trust Services Criteria — security is almost always in scope',
                    'Type I: design at a point in time; Type II: operating effectiveness over a period',
                    'Read report scope and system description carefully',
                    'Watch exceptions and complementary user entity controls (CUECs)',
                    'SOC 2 is not a privacy law — privacy criteria ≠ GDPR compliance',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'Vendor-SOC-2 in Due Diligence und Resources-Chips nutzen.',
                    'Eigene CUECs (z. B. IAM, MFA) als Teil der Plattform-Controls führen.',
                    'Nicht mit BSI C5 oder DSGVO verwechseln — komplementär, nicht identisch.',
                ],
                'en' => [
                    'Use vendor SOC 2 in due diligence and Resources chips.',
                    'Track your own CUECs (e.g. IAM, MFA) as platform controls.',
                    'Do not confuse with BSI C5 or GDPR — complementary, not identical.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'AICPA — SOC for Service Organizations',
                        'en' => 'AICPA — SOC for Service Organizations',
                    ],
                    'href' => 'https://www.aicpa-cima.com/topic/audit-assurance/audit-and-assurance-greater-than-soc-2',
                ],
            ],
            'relatedPlaybooks' => [
                'host-vs-cloud',
                'access-security-governance',
                'cloud-hosting',
            ],
        ],
        [
            'id' => 'bsi-c5',
            'category' => 'security',
            'region' => 'de',
            'type' => 'framework',
            'depth' => 'full',
            'order' => 60,
            'label' => [
                'de' => 'BSI C5',
                'en' => 'BSI C5',
            ],
            'shortPurpose' => [
                'de' => 'Cloud Computing Compliance Criteria Catalogue des BSI — Anforderungskatalog und Prüfstandard für Cloud-Anbieter, besonders relevant im DE-/Behördenkontext.',
                'en' => 'BSI Cloud Computing Compliance Criteria Catalogue — requirements and attestation baseline for cloud providers, especially relevant in German/public-sector contexts.',
            ],
            'whyItMatters' => [
                'de' => 'Für deutsche Behörden und viele regulierte Kunden ist C5 das erwartete Cloud-Assurance-Label. Es ergänzt ISO/SOC und ist stärker auf Cloud-Betrieb und Nachweise ausgelegt.',
                'en' => 'For German public bodies and many regulated customers, C5 is the expected cloud assurance label. It complements ISO/SOC and focuses more on cloud operations and evidence.',
            ],
            'appliesTo' => [
                'de' => 'Cloud-Anbieter und deren Kunden (besonders öffentliche Hand / kritische Nutzung in DE).',
                'en' => 'Cloud providers and their customers (especially German public sector / critical use).',
            ],
            'keyRules' => [
                'de' => [
                    'Katalog deckt Organisation, Personal, physische Sicherheit, Betrieb, Identity, Kryptografie, Nachvollziehbarkeit u. a. ab',
                    'Attestierung durch unabhängige Prüfer',
                    'Unterschiede zwischen Basis- und Zusatzanforderungen beachten',
                    'Kunden-Verantwortlichkeiten (Shared Responsibility) explizit machen',
                    'C5-Nachweis ≠ automatische DSGVO-Konformität',
                ],
                'en' => [
                    'Catalogue covers organisation, people, physical security, operations, identity, cryptography, auditability and more',
                    'Attestation by independent auditors',
                    'Distinguish baseline vs additional requirements',
                    'Make customer responsibilities (shared responsibility) explicit',
                    'C5 attestation ≠ automatic GDPR compliance',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'Bei Cloud-Auswahl C5-Attest und Region prüfen (siehe Resources).',
                    'Shared-Responsibility-Matrix für IAM, Keys und Logging schreiben.',
                    'Self-Hosted-Optionen gegen C5-Anforderungen spiegeln, wenn Behördenkunden im Scope sind.',
                ],
                'en' => [
                    'When choosing cloud, check C5 attestation and region (see Resources).',
                    'Write a shared-responsibility matrix for IAM, keys and logging.',
                    'Mirror C5 expectations for self-hosted options when public-sector customers are in scope.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'BSI — C5 (Cloud Computing Compliance Criteria Catalogue)',
                        'en' => 'BSI — C5 (Cloud Computing Compliance Criteria Catalogue)',
                    ],
                    'href' => 'https://www.bsi.bund.de/EN/Topics/CloudComputing/Compliance_Criteria_Catalogue/Compliance_Criteria_Catalogue_node.html',
                ],
            ],
            'relatedPlaybooks' => [
                'host-vs-cloud',
                'cloud-hosting',
                'access-security-governance',
            ],
        ],
        [
            'id' => 'nist-zero-trust',
            'category' => 'security',
            'region' => 'us',
            'type' => 'framework',
            'depth' => 'full',
            'order' => 70,
            'label' => [
                'de' => 'NIST Zero Trust (SP 800-207)',
                'en' => 'NIST Zero Trust (SP 800-207)',
            ],
            'shortPurpose' => [
                'de' => 'Architekturmodell: nie implizit vertrauen, immer verifizieren — Identität, Gerät, Session und Kontext statt Netzperimeter.',
                'en' => 'Architecture model: never trust by default, always verify — identity, device, session and context instead of network perimeter.',
            ],
            'whyItMatters' => [
                'de' => 'Data Platforms haben oft flache Warehouse-Rechte und lange Sessions. Zero Trust übersetzt sich in starke Identity, Segmentierung, Continuous Authorization und Least Privilege auf Datenobjekte.',
                'en' => 'Data platforms often have flat warehouse grants and long-lived sessions. Zero Trust translates to strong identity, segmentation, continuous authorisation and least privilege on data objects.',
            ],
            'appliesTo' => [
                'de' => 'Organisationen, die Access- und Netzwerk-Architektur modernisieren — unabhängig von Zertifizierungen.',
                'en' => 'Organisations modernising access and network architecture — independent of certifications.',
            ],
            'keyRules' => [
                'de' => [
                    'Alle Ressourcen als potenziell kompromittiert behandeln',
                    'Zugriff über Policy Decision / Enforcement Points steuern',
                    'Identität und Kontext kontinuierlich bewerten',
                    'Least Privilege und Just-in-Time wo möglich',
                    'Sichtbarkeit und Telemetrie für Entscheidungen nutzen',
                ],
                'en' => [
                    'Treat all resources as potentially compromised',
                    'Drive access through policy decision / enforcement points',
                    'Continuously evaluate identity and context',
                    'Least privilege and just-in-time where feasible',
                    'Use visibility and telemetry for decisions',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'Warehouse Roles, Row/Column Security und BI Section Access als Enforcement Points.',
                    'Service Accounts und Break-Glass eng führen.',
                    'Passt zu Policy → Metadata → Runtime in der E2E-Governance-Serie.',
                ],
                'en' => [
                    'Treat warehouse roles, row/column security and BI section access as enforcement points.',
                    'Keep service accounts and break-glass tightly controlled.',
                    'Aligns with policy → metadata → runtime in the end-to-end governance series.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'NIST SP 800-207 — Zero Trust Architecture',
                        'en' => 'NIST SP 800-207 — Zero Trust Architecture',
                    ],
                    'href' => 'https://csrc.nist.gov/pubs/sp/800/207/final',
                ],
            ],
            'relatedPlaybooks' => [
                'access-security-governance',
                'missing-pieces-policy-access-governance',
                'end-to-end-governance-architecture',
            ],
        ],
        [
            'id' => 'eu-ai-act',
            'category' => 'ai',
            'region' => 'eu',
            'type' => 'regulation',
            'depth' => 'full',
            'order' => 80,
            'label' => [
                'de' => 'EU AI Act',
                'en' => 'EU AI Act',
            ],
            'shortPurpose' => [
                'de' => 'EU-Verordnung für KI-Systeme — risikobasierte Pflichten von verbotenen Praktiken bis Hochrisiko- und Transparenzanforderungen.',
                'en' => 'EU regulation for AI systems — risk-based duties from prohibited practices to high-risk and transparency obligations.',
            ],
            'whyItMatters' => [
                'de' => 'Sobald Modelle in Produkten, Entscheidungen oder Automatisierung landen, braucht ihr Klassifikation, Dokumentation und Governance — nicht nur Prompt-Qualität.',
                'en' => 'Once models land in products, decisions or automation, you need classification, documentation and governance — not just prompt quality.',
            ],
            'appliesTo' => [
                'de' => 'Anbieter, Betreiber, Importeure und Händler von KI-Systemen mit EU-Bezug; gestaffelte Anwendungszeiten beachten.',
                'en' => 'Providers, deployers, importers and distributors of AI systems with an EU nexus; watch phased application dates.',
            ],
            'keyRules' => [
                'de' => [
                    'Risikobasierter Ansatz: verboten / Hochrisiko / begrenztes Risiko / minimal',
                    'Hochrisiko: Qualitätsmanagement, Daten-Governance, Dokumentation, menschliche Aufsicht',
                    'Transparenzpflichten (z. B. Kennzeichnung bestimmter KI-Interaktionen)',
                    'GPAI-/Foundation-Model-Pflichten je nach Einstufung',
                    'Marktüberwachung und Durchsetzung auf EU-/Mitgliedstaatsebene',
                ],
                'en' => [
                    'Risk-based approach: prohibited / high-risk / limited risk / minimal',
                    'High-risk: quality management, data governance, documentation, human oversight',
                    'Transparency duties (e.g. labelling certain AI interactions)',
                    'GPAI / foundation-model duties depending on classification',
                    'Market surveillance and enforcement at EU / member-state level',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'Use-Case-Inventar und Risikoklasse vor dem Bau führen.',
                    'Training-/Eval-Daten, PII und Prompt-Logs wie produktive Daten governen.',
                    'Menschliche Aufsicht und Rollback in Betriebsmodellen verankern.',
                ],
                'en' => [
                    'Keep a use-case inventory and risk class before building.',
                    'Govern training/eval data, PII and prompt logs like production data.',
                    'Embed human oversight and rollback in operating models.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'Verordnung (EU) 2024/1689 — EUR-Lex',
                        'en' => 'Regulation (EU) 2024/1689 — EUR-Lex',
                    ],
                    'href' => 'https://eur-lex.europa.eu/eli/reg/2024/1689/oj',
                ],
                [
                    'label' => [
                        'de' => 'EU-Kommission — AI Act',
                        'en' => 'European Commission — AI Act',
                    ],
                    'href' => 'https://digital-strategy.ec.europa.eu/en/policies/regulatory-framework-ai',
                ],
            ],
            'relatedPlaybooks' => [
                'ai-gov',
                'ai-eval',
                'ai-failures',
                'pii-privacy-governance',
            ],
        ],
        [
            'id' => 'nist-ai-rmf',
            'category' => 'ai',
            'region' => 'us',
            'type' => 'framework',
            'depth' => 'full',
            'order' => 90,
            'label' => [
                'de' => 'NIST AI RMF',
                'en' => 'NIST AI RMF',
            ],
            'shortPurpose' => [
                'de' => 'Freiwilliges Risikomanagement-Framework für KI: Govern, Map, Measure, Manage — praxisnah und international anschlussfähig.',
                'en' => 'Voluntary AI risk management framework: Govern, Map, Measure, Manage — practical and internationally usable.',
            ],
            'whyItMatters' => [
                'de' => 'Der AI Act sagt „was“ oft rechtlich; NIST AI RMF hilft beim „wie“ im Betrieb — ideal als Operating-Model-Companion neben der EU-Verordnung.',
                'en' => 'The AI Act often states the legal “what”; NIST AI RMF helps with the operational “how” — a strong operating-model companion beside the EU regulation.',
            ],
            'appliesTo' => [
                'de' => 'Organisationen, die KI-Risiken managen wollen — unabhängig von Zertifizierungspflicht.',
                'en' => 'Organisations managing AI risk — independent of certification mandates.',
            ],
            'keyRules' => [
                'de' => [
                    'Govern: Policies, Rollen, Kultur',
                    'Map: Kontext, Akteure, Risiken und Impacts',
                    'Measure: Metriken, Evaluation, Monitoring',
                    'Manage: Priorisieren, Mitigieren, Kommunizieren',
                    'Iterativ und lebenszyklusübergreifend anwenden',
                ],
                'en' => [
                    'Govern: policies, roles, culture',
                    'Map: context, actors, risks and impacts',
                    'Measure: metrics, evaluation, monitoring',
                    'Manage: prioritise, mitigate, communicate',
                    'Apply iteratively across the lifecycle',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'Eval-Suites und Failure-Modes an Measure/Manage koppeln.',
                    'Governance-Rollen (Owner, Steward, Reviewer) für AI-Use-Cases festlegen.',
                    'Mit EU AI Act parallel mappen, nicht ersetzen.',
                ],
                'en' => [
                    'Tie eval suites and failure modes to Measure/Manage.',
                    'Define governance roles (owner, steward, reviewer) for AI use cases.',
                    'Map alongside the EU AI Act — do not treat as a substitute.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'NIST — AI Risk Management Framework',
                        'en' => 'NIST — AI Risk Management Framework',
                    ],
                    'href' => 'https://www.nist.gov/itl/ai-risk-management-framework',
                ],
                [
                    'label' => [
                        'de' => 'NIST — AI RMF 1.0 (PDF)',
                        'en' => 'NIST — AI RMF 1.0 (PDF)',
                    ],
                    'href' => 'https://nvlpubs.nist.gov/nistpubs/ai/NIST.AI.100-1.pdf',
                ],
            ],
            'relatedPlaybooks' => [
                'ai-gov',
                'ai-eval',
                'ai-basics',
            ],
        ],
        [
            'id' => 'iso-42001',
            'category' => 'ai',
            'region' => 'intl',
            'type' => 'standard',
            'depth' => 'full',
            'order' => 100,
            'label' => [
                'de' => 'ISO/IEC 42001',
                'en' => 'ISO/IEC 42001',
            ],
            'shortPurpose' => [
                'de' => 'Managementsystem-Standard für künstliche Intelligenz (AIMS) — analog zu ISO 27001, aber für AI-Governance.',
                'en' => 'Management system standard for artificial intelligence (AIMS) — analogous to ISO 27001, but for AI governance.',
            ],
            'whyItMatters' => [
                'de' => 'Wenn ihr AI dauerhaft betreibt, braucht ihr wiederholbare Prozesse — nicht nur Einzel-Policies. ISO 42001 strukturiert das Managementsystem.',
                'en' => 'If you run AI continuously, you need repeatable processes — not one-off policies. ISO 42001 structures that management system.',
            ],
            'appliesTo' => [
                'de' => 'Organisationen, die ein AI-Managementsystem aufbauen oder zertifizieren wollen.',
                'en' => 'Organisations building or seeking certification for an AI management system.',
            ],
            'keyRules' => [
                'de' => [
                    'AIMS-Scope, Policy und Ziele festlegen',
                    'Risiken und Chancen für AI-Systeme behandeln',
                    'Rollen, Kompetenz und Awareness',
                    'Lebenszyklus-Controls für Entwicklung und Betrieb',
                    'Performance Evaluation und Verbesserung',
                ],
                'en' => [
                    'Define AIMS scope, policy and objectives',
                    'Treat risks and opportunities for AI systems',
                    'Roles, competence and awareness',
                    'Lifecycle controls for development and operations',
                    'Performance evaluation and improvement',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'AI-Gov-Playbook und Prompt-/Eval-Tools in ein Managementsystem einbetten.',
                    'Mit ISO 27001-Controls verzahnen (Access, Logging, Change).',
                    'Zertifizierung ist optional — der Nutzen liegt im Betriebsmodell.',
                ],
                'en' => [
                    'Embed the AI gov playbook and prompt/eval tools into a management system.',
                    'Interlock with ISO 27001 controls (access, logging, change).',
                    'Certification is optional — the value is the operating model.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'ISO — ISO/IEC 42001 (Produktseite)',
                        'en' => 'ISO — ISO/IEC 42001 (product page)',
                    ],
                    'href' => 'https://www.iso.org/standard/81230.html',
                ],
            ],
            'relatedPlaybooks' => [
                'ai-gov',
                'ai-agents',
                'access-security-governance',
            ],
        ],
        [
            'id' => 'gobd',
            'category' => 'retention',
            'region' => 'de',
            'type' => 'regulation',
            'depth' => 'full',
            'order' => 110,
            'label' => [
                'de' => 'GoBD',
                'en' => 'GoBD',
            ],
            'shortPurpose' => [
                'de' => 'Grundsätze zur ordnungsmäßigen Führung und Aufbewahrung von Büchern, Aufzeichnungen und Unterlagen in elektronischer Form — DE-Steuer-/Handelskontext.',
                'en' => 'Principles for proper keeping and retention of books, records and documents in electronic form — German tax/commercial context.',
            ],
            'whyItMatters' => [
                'de' => 'Lifecycle und Retention sind nicht nur Privacy: Steuer- und Handelsrecht fordern Nachvollziehbarkeit und Aufbewahrungsfristen. Das kollidiert oft mit Löschwünschen — deshalb braucht ihr klare Policies.',
                'en' => 'Lifecycle and retention are not only privacy: tax and commercial law demand traceability and retention periods. That often conflicts with deletion requests — so you need clear policies.',
            ],
            'appliesTo' => [
                'de' => 'Unternehmen mit Buchführungs- und Aufbewahrungspflichten in Deutschland (steuerlich/handelsrechtlich relevant).',
                'en' => 'Companies with bookkeeping and retention duties in Germany (tax/commercial relevance).',
            ],
            'keyRules' => [
                'de' => [
                    'Nachvollziehbarkeit und Nachprüfbarkeit der Buchführung',
                    'Vollständigkeit, Richtigkeit, zeitgerechte Buchungen',
                    'Unveränderbarkeit bzw. Protokollierung von Änderungen',
                    'Aufbewahrung elektronischer Unterlagen über gesetzliche Fristen',
                    'Verfahrensdokumentation für IT-gestützte Prozesse',
                ],
                'en' => [
                    'Traceability and auditability of bookkeeping',
                    'Completeness, accuracy, timely postings',
                    'Immutability or logging of changes',
                    'Retention of electronic records for statutory periods',
                    'Process documentation for IT-supported procedures',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'Retention-Klassen: steuerrelevant vs. nur analytisch vs. PII.',
                    'Archive/WORM-ähnliche Strategien wo Fristen Löschung blockieren.',
                    'DSDR: Löschung vs. Aufbewahrungspflicht explizit entscheiden und dokumentieren.',
                ],
                'en' => [
                    'Retention classes: tax-relevant vs analytics-only vs PII.',
                    'Archive / WORM-like strategies where statutes block deletion.',
                    'DSDR: decide and document deletion vs mandatory retention explicitly.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'BMF — GoBD (Schreiben)',
                        'en' => 'German Federal Ministry of Finance — GoBD guidance',
                    ],
                    'href' => 'https://www.bundesfinanzministerium.de/Content/DE/Downloads/BMF_Schreiben/Weitere_Steuerthemen/Abgabenordnung/2019-11-28-GoBD.html',
                ],
            ],
            'relatedPlaybooks' => [
                'data-lifecycle-retention',
                'dsdr-governance',
                'missing-pieces-data-lifecycle-retirement',
            ],
        ],
        [
            'id' => 'nis2',
            'category' => 'sector',
            'region' => 'eu',
            'type' => 'regulation',
            'depth' => 'short',
            'order' => 120,
            'label' => [
                'de' => 'NIS2',
                'en' => 'NIS2',
            ],
            'shortPurpose' => [
                'de' => 'EU-Richtlinie zur Cybersicherheit wesentlicher und wichtiger Einrichtungen — Risikomanagement, Meldepflichten, Lieferkette.',
                'en' => 'EU directive on cybersecurity for essential and important entities — risk management, incident reporting, supply chain.',
            ],
            'whyItMatters' => [
                'de' => 'Wenn euer Unternehmen (oder eure Kunden) unter NIS2 fallen, werden Security-Controls und Incident-Prozesse zur Pflicht — Data Platforms sind oft kritische Systeme.',
                'en' => 'If your company (or customers) fall under NIS2, security controls and incident processes become mandatory — data platforms are often critical systems.',
            ],
            'appliesTo' => [
                'de' => 'Wesentliche und wichtige Einrichtungen in definierten Sektoren (Energie, Transport, Gesundheit, digitale Infrastruktur, …) — nationale Umsetzung prüfen.',
                'en' => 'Essential and important entities in defined sectors (energy, transport, health, digital infrastructure, …) — check national transposition.',
            ],
            'keyRules' => [
                'de' => [
                    'Risikomanagement-Maßnahmen für Netz- und Informationssysteme',
                    'Incident-Meldung in kurzen Fristen',
                    'Lieferketten- und Vendor-Risiken einbeziehen',
                    'Governance und Accountability auf Führungsebene',
                ],
                'en' => [
                    'Risk-management measures for network and information systems',
                    'Incident reporting within short deadlines',
                    'Include supply-chain and vendor risk',
                    'Governance and accountability at leadership level',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'Logging, Backup, Privileged Access und Incident-Runbooks priorisieren.',
                    'Vendor-/Cloud-Abhängigkeiten inventarisieren.',
                ],
                'en' => [
                    'Prioritise logging, backup, privileged access and incident runbooks.',
                    'Inventory vendor/cloud dependencies.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'Richtlinie (EU) 2022/2555 — EUR-Lex',
                        'en' => 'Directive (EU) 2022/2555 — EUR-Lex',
                    ],
                    'href' => 'https://eur-lex.europa.eu/eli/dir/2022/2555/oj',
                ],
                [
                    'label' => [
                        'de' => 'ENISA — NIS2',
                        'en' => 'ENISA — NIS2',
                    ],
                    'href' => 'https://www.enisa.europa.eu/topics/cybersecurity-policy/nis-directive-new',
                ],
            ],
            'relatedPlaybooks' => [
                'access-security-governance',
                'host-vs-cloud',
            ],
        ],
        [
            'id' => 'dora',
            'category' => 'sector',
            'region' => 'eu',
            'type' => 'regulation',
            'depth' => 'short',
            'order' => 130,
            'label' => [
                'de' => 'DORA',
                'en' => 'DORA',
            ],
            'shortPurpose' => [
                'de' => 'Digital Operational Resilience Act — EU-Regeln für IKT-Risiken im Finanzsektor (Banken, Versicherungen, kritische ICT-Provider).',
                'en' => 'Digital Operational Resilience Act — EU rules for ICT risk in the financial sector (banks, insurers, critical ICT providers).',
            ],
            'whyItMatters' => [
                'de' => 'Finanzkunden verlangen oft DORA-fähige Betriebs- und Vendor-Nachweise. Analytics- und Cloud-Setups sind Teil der IKT-Landschaft.',
                'en' => 'Financial customers often require DORA-ready operations and vendor evidence. Analytics and cloud setups are part of the ICT landscape.',
            ],
            'appliesTo' => [
                'de' => 'Finanzunternehmen und kritische IKT-Drittdienstleister im EU-Finanzsektor.',
                'en' => 'Financial entities and critical ICT third-party providers in the EU financial sector.',
            ],
            'keyRules' => [
                'de' => [
                    'IKT-Risikomanagement und Governance',
                    'Incident-Klassifikation und Meldepflichten',
                    'Resilienz-Tests (inkl. Threat-Led Testing wo relevant)',
                    'Steuerung von IKT-Drittparteien und Konzentrationsrisiko',
                ],
                'en' => [
                    'ICT risk management and governance',
                    'Incident classification and reporting',
                    'Resilience testing (including threat-led testing where relevant)',
                    'ICT third-party oversight and concentration risk',
                ],
            ],
            'platformImplications' => [
                'de' => [
                    'Exit-/Portability-Pläne für kritische Cloud- und SaaS-Abhängigkeiten.',
                    'Monitoring und Incident-Kommunikation mit Fachbereichen üben.',
                ],
                'en' => [
                    'Exit/portability plans for critical cloud and SaaS dependencies.',
                    'Rehearse monitoring and incident communication with business teams.',
                ],
            ],
            'officialSources' => [
                [
                    'label' => [
                        'de' => 'Verordnung (EU) 2022/2554 — EUR-Lex',
                        'en' => 'Regulation (EU) 2022/2554 — EUR-Lex',
                    ],
                    'href' => 'https://eur-lex.europa.eu/eli/reg/2022/2554/oj',
                ],
                [
                    'label' => [
                        'de' => 'ESAs — DORA Overview',
                        'en' => 'ESAs — DORA overview',
                    ],
                    'href' => 'https://www.eba.europa.eu/activities/single-rulebook/regulatory-activities/digital-operational-resilience-dora',
                ],
            ],
            'relatedPlaybooks' => [
                'host-vs-cloud',
                'access-security-governance',
                'cloud-hosting',
            ],
        ],
    ],

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
                'de' => 'Data-Management-Körperwissen (DMBOK): Governance, Metadaten, Qualität, Lifecycle — die gemeinsame Fachsprache.',
                'en' => 'Data management body of knowledge (DMBOK): governance, metadata, quality, lifecycle — the shared professional language.',
            ],
            'whyForConsultant' => [
                'de' => 'Hilft, mit Data Owners, Architekten und Stewards auf Augenhöhe zu sprechen — unabhängig vom Cloud-Vendor.',
                'en' => 'Helps you speak peer-to-peer with data owners, architects and stewards — independent of cloud vendor.',
            ],
            'whatYouNeed' => [
                'de' => [
                    'DMBOK-Grundlagen (Data Governance, Metadata, DQ, Security, Lifecycle)',
                    'Stufen: Associate → Practitioner → Master (je nach Erfahrung)',
                    'Praxisbezug: Policies, Katalog, Ownership-Modelle',
                ],
                'en' => [
                    'DMBOK basics (data governance, metadata, DQ, security, lifecycle)',
                    'Levels: Associate → Practitioner → Master (by experience)',
                    'Practice link: policies, catalog, ownership models',
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
