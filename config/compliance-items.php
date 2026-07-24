<?php

/**
 * Compliance Hub framework items (detail content).
 * Learning and orientation only — not legal advice.
 */
return [
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
            'de' => "Fast jedes Analytics-, Warehouse- und AI-Setup verarbeitet personenbezogene Daten — oft als Identifiers, Logs, CRM-Exports oder Modell-Features.\n\nDie DSGVO steuert Zweckbindung, Minimierung, Transparenz, Löschung und Nachweisbarkeit. Genau das sind die Governance-Säulen PII, DSDR und Lifecycle: ohne klare Zwecke und Evidence wird jede Plattform zum Compliance-Risiko.",
            'en' => "Almost every analytics, warehouse and AI setup processes personal data — as identifiers, logs, CRM exports or model features.\n\nGDPR drives purpose limitation, minimisation, transparency, deletion and evidence. Those are the same themes as PII, DSDR and lifecycle pillars: without clear purposes and evidence, every platform becomes a compliance risk.",
        ],
        'appliesTo' => [
            'de' => "Verantwortliche und Auftragsverarbeiter mit Bezug zur EU/EWR — etwa wenn ihr Betroffenen in der EU Waren/Dienste anbietet oder Verhalten beobachtetet. Der Sitz des Unternehmens allein entscheidet nicht.\n\nAuch US-/CH-Clouds und SaaS-Vendoren können betroffen sein, sobald EU-Personen betroffen sind oder Support/Admin-Zugriff aus der EU/EWR erfolgt.",
            'en' => "Controllers and processors with an EU/EEA nexus — for example offering goods/services to people in the EU or monitoring behaviour. Company HQ alone does not decide applicability.\n\nUS/CH clouds and SaaS vendors can also be in scope once EU individuals are involved or support/admin access touches the EU/EEA.",
        ],
        'scopeNotes' => [
            'de' => [
                'Die DSGVO schützt personenbezogene Daten — nicht „alle Daten“ und nicht automatisch Geschäftsgeheimnisse.',
                'Sie ersetzt keine Branchengesetze (z. B. GoBD, NIS2, DORA) und kein ISMS wie ISO 27001.',
                'Ein SOC-2- oder C5-Report ist kein DSGVO-Konformitätsnachweis.',
                'Diese Seite ist Orientierung — kein Rechtsrat und kein Ersatz für DPIA/Legal Review.',
            ],
            'en' => [
                'GDPR protects personal data — not “all data” and not automatically trade secrets.',
                'It does not replace sector law (e.g. GoBD, NIS2, DORA) or an ISMS such as ISO 27001.',
                'A SOC 2 or C5 report is not proof of GDPR compliance.',
                'This page is orientation — not legal advice and not a substitute for DPIA/legal review.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Rechtmäßigkeit, Treu und Glauben, Transparenz',
                    'detail' => 'Jede Verarbeitung braucht eine Rechtsgrundlage und verständliche Information an Betroffene. In Plattformen heißt das: dokumentierte Zwecke, sichtbare Policies und keine „heimlichen“ Nebenverwendungen von Analytics-Daten.',
                    'ref' => 'Art. 5 Abs. 1 lit. a',
                ],
                [
                    'title' => 'Zweckbindung und Datenminimierung',
                    'detail' => 'Daten dürfen nur für festgelegte, eindeutige Zwecke erhoben und nicht beliebig „für später“ gehortet werden. Warehouse-Modelle und Feature-Stores brauchen daher Zweck-Tags und Selektion statt Full-Dump-Defaults.',
                    'ref' => 'Art. 5 Abs. 1 lit. b–c',
                ],
                [
                    'title' => 'Speicherbegrenzung',
                    'detail' => 'Personenbezogene Daten dürfen nicht länger als nötig gespeichert werden. Retention-Schedules, Archiv vs. Produktiv und automatisierte Löschjobs sind Pflichtbestandteile eines Lifecycle-Designs.',
                    'ref' => 'Art. 5 Abs. 1 lit. e',
                ],
                [
                    'title' => 'Integrität und Vertraulichkeit (TOMs)',
                    'detail' => 'Technische und organisatorische Maßnahmen müssen dem Risiko angemessen sein: Zugriffskontrolle, Verschlüsselung, Masking, Logging, Change Control. Art. 32 konkretisiert das für Security-Controls.',
                    'ref' => 'Art. 5 Abs. 1 lit. f, Art. 32',
                ],
                [
                    'title' => 'Rechenschaftspflicht',
                    'detail' => 'Ihr müsst die Einhaltung nachweisen können — nicht nur „irgendwie sicher“ sein. Evidence aus Access Reviews, Policy-Versionen, DPIAs und Audit-Logs gehört deshalb in die Plattform-Governance.',
                    'ref' => 'Art. 5 Abs. 2',
                ],
                [
                    'title' => 'Betroffenenrechte',
                    'detail' => 'Auskunft, Löschung, Einschränkung und weitere Rechte müssen operativ bedienbar sein. Das betrifft Rohzonen, Historie, Exports, Backups und nachgelagerte BI-/AI-Kopien — nicht nur die operative CRM-Tabelle.',
                    'ref' => 'Art. 12–22, Art. 17',
                ],
                [
                    'title' => 'Auftragsverarbeitung nur mit Vertrag',
                    'detail' => 'Cloud-, ETL- und SaaS-Provider, die personenbezogene Daten in eurem Auftrag verarbeiten, brauchen einen AV-Vertrag (Art. 28) mit klaren Weisungen, TOMs und Subprozessor-Regeln.',
                    'ref' => 'Art. 28',
                ],
                [
                    'title' => 'Datenschutz-Folgenabschätzung',
                    'detail' => 'Bei hohem Risiko für Betroffene ist eine DPIA Pflicht — typisch bei umfangreichem Profiling, besonderen Kategorien oder neuen AI-Use-Cases auf Personenbezug.',
                    'ref' => 'Art. 35',
                ],
            ],
            'en' => [
                [
                    'title' => 'Lawfulness, fairness and transparency',
                    'detail' => 'Every processing needs a legal basis and intelligible information for individuals. On platforms that means documented purposes, visible policies and no hidden secondary uses of analytics data.',
                    'ref' => 'Art. 5(1)(a)',
                ],
                [
                    'title' => 'Purpose limitation and data minimisation',
                    'detail' => 'Data may only be collected for specified, explicit purposes and must not be hoarded “for later”. Warehouse models and feature stores therefore need purpose tags and selective loads instead of full-dump defaults.',
                    'ref' => 'Art. 5(1)(b)–(c)',
                ],
                [
                    'title' => 'Storage limitation',
                    'detail' => 'Personal data must not be kept longer than necessary. Retention schedules, archive vs production and automated deletion jobs are core parts of lifecycle design.',
                    'ref' => 'Art. 5(1)(e)',
                ],
                [
                    'title' => 'Integrity and confidentiality (TOMs)',
                    'detail' => 'Technical and organisational measures must match the risk: access control, encryption, masking, logging, change control. Art. 32 spells this out for security controls.',
                    'ref' => 'Art. 5(1)(f), Art. 32',
                ],
                [
                    'title' => 'Accountability',
                    'detail' => 'You must be able to demonstrate compliance — not only “be somehow secure”. Evidence from access reviews, policy versions, DPIAs and audit logs belongs in platform governance.',
                    'ref' => 'Art. 5(2)',
                ],
                [
                    'title' => 'Data-subject rights',
                    'detail' => 'Access, erasure, restriction and other rights must be operable. That covers raw zones, history, exports, backups and downstream BI/AI copies — not only the operational CRM table.',
                    'ref' => 'Arts. 12–22, Art. 17',
                ],
                [
                    'title' => 'Processors only under a contract',
                    'detail' => 'Cloud, ETL and SaaS providers that process personal data on your behalf need a processor agreement (Art. 28) with clear instructions, TOMs and sub-processor rules.',
                    'ref' => 'Art. 28',
                ],
                [
                    'title' => 'Data protection impact assessment',
                    'detail' => 'For high risk to individuals a DPIA is required — typical for large-scale profiling, special categories or new AI use cases on personal data.',
                    'ref' => 'Art. 35',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'PII-Klassifikation und Masking',
                    'detail' => 'Metadaten → Policy → Runtime: Spalten und Domänen klassifizieren, Masking/Tokenisierung in Warehouse und BI durchsetzen, Break-Glass dokumentieren.',
                ],
                [
                    'title' => 'Lösch- und Sperrpfade (DSDR)',
                    'detail' => 'End-to-end Pfade über Raw, Historie, Exports und Feature Stores. Ohne Inventar der Kopien bleibt „Löschen“ eine Illusion.',
                ],
                [
                    'title' => 'Retention und Archiv trennen',
                    'detail' => 'Analytische Hot-Daten, steuerrelevante Archive und reine Logs brauchen unterschiedliche Fristen und Zugriffsmuster.',
                ],
                [
                    'title' => 'Access Reviews und Audit-Logs',
                    'detail' => 'Least Privilege, regelmäßige Reviews und nachvollziehbare Logs sind Evidence für TOMs und Accountability.',
                ],
                [
                    'title' => 'Vendor- und Cloud-Auswahl',
                    'detail' => 'AV-Vertrag, Residenz, Support-Zugriff und Subprozessoren vor dem Go-live klären — nicht erst beim Audit.',
                ],
                [
                    'title' => 'Zweck- und Legal-Basis-Metadaten',
                    'detail' => 'Datasets und Pipelines mit Zweck, Rechtsgrundlage und Owner taggen, damit Downstream-Teams nicht „einfach alles joinen“.',
                ],
            ],
            'en' => [
                [
                    'title' => 'PII classification and masking',
                    'detail' => 'Metadata → policy → runtime: classify columns and domains, enforce masking/tokenisation in warehouse and BI, document break-glass.',
                ],
                [
                    'title' => 'Deletion and restriction paths (DSDR)',
                    'detail' => 'End-to-end paths across raw, history, exports and feature stores. Without an inventory of copies, “delete” stays an illusion.',
                ],
                [
                    'title' => 'Separate retention and archive',
                    'detail' => 'Analytical hot data, tax-relevant archives and pure logs need different retention and access patterns.',
                ],
                [
                    'title' => 'Access reviews and audit logs',
                    'detail' => 'Least privilege, regular reviews and traceable logs are evidence for TOMs and accountability.',
                ],
                [
                    'title' => 'Vendor and cloud selection',
                    'detail' => 'Clarify processor terms, residency, support access and sub-processors before go-live — not only at audit time.',
                ],
                [
                    'title' => 'Purpose and legal-basis metadata',
                    'detail' => 'Tag datasets and pipelines with purpose, legal basis and owner so downstream teams do not simply join everything.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Verarbeitungsübersicht aktuell?',
                    'detail' => 'Welche personenbezogenen Datenflüsse laufen durch Warehouse, BI, ML und Exports — inklusive Schatten-Kopien.',
                ],
                [
                    'title' => 'Zweck und Rechtsgrundlage je Use-Case?',
                    'detail' => 'Jeder analytische Use-Case hat dokumentierten Zweck und passende Grundlage — nicht nur „Business Intelligence“.',
                ],
                [
                    'title' => 'Lösch-/Sperrprozess getestet?',
                    'detail' => 'Mindestens ein End-to-end Test inkl. Historie und Export-Kopien pro Jahr.',
                ],
                [
                    'title' => 'TOMs und Evidence greifbar?',
                    'detail' => 'Access Reviews, Encryption-Status, Ticket-Historie und Policy-Versionen sind auffindbar.',
                ],
                [
                    'title' => 'AV-Verträge und Subprozessoren gepflegt?',
                    'detail' => 'Kritische Cloud-/SaaS-Vendoren mit aktuellem Vertrag und Subprozessor-Liste.',
                ],
                [
                    'title' => 'DPIA-Trigger definiert?',
                    'detail' => 'Klare Kriterien, wann neue AI-/Profiling-Use-Cases eine DPIA brauchen.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Processing inventory up to date?',
                    'detail' => 'Which personal data flows run through warehouse, BI, ML and exports — including shadow copies.',
                ],
                [
                    'title' => 'Purpose and legal basis per use case?',
                    'detail' => 'Every analytics use case has a documented purpose and fitting basis — not only “business intelligence”.',
                ],
                [
                    'title' => 'Deletion/restriction process tested?',
                    'detail' => 'At least one end-to-end test including history and export copies per year.',
                ],
                [
                    'title' => 'TOMs and evidence at hand?',
                    'detail' => 'Access reviews, encryption status, ticket history and policy versions are findable.',
                ],
                [
                    'title' => 'Processor terms and sub-processors maintained?',
                    'detail' => 'Critical cloud/SaaS vendors with current contracts and sub-processor lists.',
                ],
                [
                    'title' => 'DPIA triggers defined?',
                    'detail' => 'Clear criteria for when new AI/profiling use cases need a DPIA.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => '„Anonymisiert“ ohne Prüfung',
                    'detail' => 'Pseudonyme IDs, schwache Hashing-Verfahren oder re-identifizierbare Joins sind oft weiterhin personenbezogen.',
                ],
                [
                    'title' => 'Nur Produktivsystem gelöscht',
                    'detail' => 'Raw, Staging, Backups, Notebooks und BI-Extracts vergessen — Betroffenenrechte scheitern dann still.',
                ],
                [
                    'title' => 'Residenz = Transfer gelöst',
                    'detail' => 'EU-Region allein reicht nicht, wenn Admin-/Support-Zugriff aus Drittländern möglich ist.',
                ],
                [
                    'title' => 'SOC 2 als DSGVO-Nachweis',
                    'detail' => 'Security-Attestierungen ersetzen keine Rechtsgrundlage, Transparenz oder Löschfähigkeit.',
                ],
                [
                    'title' => 'Einmalige Policy, kein Betrieb',
                    'detail' => 'PDF-Richtlinien ohne Runtime-Controls und Reviews erfüllen die Rechenschaftspflicht nicht.',
                ],
            ],
            'en' => [
                [
                    'title' => '“Anonymised” without scrutiny',
                    'detail' => 'Pseudonymous IDs, weak hashing or re-identifiable joins are often still personal data.',
                ],
                [
                    'title' => 'Only production deleted',
                    'detail' => 'Forgetting raw, staging, backups, notebooks and BI extracts — data-subject rights then fail silently.',
                ],
                [
                    'title' => 'Residency equals transfer solved',
                    'detail' => 'An EU region alone is not enough if admin/support access from third countries is possible.',
                ],
                [
                    'title' => 'SOC 2 as GDPR proof',
                    'detail' => 'Security attestations do not replace legal basis, transparency or erasure capability.',
                ],
                [
                    'title' => 'One-off policy, no operations',
                    'detail' => 'PDF policies without runtime controls and reviews do not meet accountability.',
                ],
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
            [
                'label' => [
                    'de' => 'EU-Kommission — Datenschutzregeln',
                    'en' => 'European Commission — data protection rules',
                ],
                'href' => 'https://commission.europa.eu/law/law-topic/data-protection_en',
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
            'de' => "In DE-Projekten reichen DSGVO-Prinzipien allein oft nicht. Beschäftigtendaten, besondere Kategorien und Behördenkontexte brauchen die BDSG-Leseart zusätzlich.\n\nHR-Analytics, Badge-Logs, Performance-Dashboards und interne AI-Assistenten landen schnell in genau diesem Spannungsfeld — und werden in Warehouse-Projekten gerne unterschätzt.",
            'en' => "In German projects GDPR principles alone are often not enough. Employee data, special categories and public-sector contexts need the BDSG reading on top.\n\nHR analytics, badge logs, performance dashboards and internal AI assistants quickly land in this tension — and are often underestimated in warehouse projects.",
        ],
        'appliesTo' => [
            'de' => "Öffentliche und nicht-öffentliche Stellen in Deutschland. Besonders relevant bei Beschäftigtendaten, nationalen Öffnungsklauseln und dem Zusammenspiel mit Betriebsrat/Mitbestimmung.\n\nAuch internationale Konzerne mit DE-Niederlassung oder DE-Beschäftigten müssen die nationale Schicht mitdenken.",
            'en' => "Public and non-public bodies in Germany. Especially relevant for employee data, national opening clauses and works-council/co-determination interplay.\n\nInternational groups with a German entity or German employees also need this national layer.",
        ],
        'scopeNotes' => [
            'de' => [
                'Die BDSG ersetzt die DSGVO nicht — sie ergänzt und konkretisiert sie für DE.',
                'Landesdatenschutzgesetze können für den öffentlichen Bereich zusätzlich gelten.',
                'Mitbestimmung (Betriebsrat) ist oft parallel relevant, nicht „statt Datenschutz“.',
            ],
            'en' => [
                'BDSG does not replace the GDPR — it supplements and specifies it for Germany.',
                'State data-protection acts may also apply in the public sector.',
                'Co-determination (works council) is often relevant in parallel, not “instead of privacy”.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Ergänzung, kein Ersatz der DSGVO',
                    'detail' => 'Zuerst DSGVO-Grundsätze prüfen, dann BDSG-Öffnungen und Sonderregeln. Viele Teams starten falsch herum bei § 26 und vergessen Art. 5/6.',
                ],
                [
                    'title' => 'Beschäftigtendaten',
                    'detail' => '§ 26 BDSG adressiert Verarbeitungen im Beschäftigungsverhältnis. HR-Analytics und Monitoring brauchen enge Zweckbindung und oft Mitbestimmungsabgleich.',
                    'ref' => '§ 26 BDSG',
                ],
                [
                    'title' => 'Nationale Öffnungsklauseln',
                    'detail' => 'Die DSGVO lässt Spielraum für nationale Regelungen — z. B. zu besonderen Verarbeitungssituationen. Diese Lücken muss man aktiv lesen, nicht „EU-only“ annehmen.',
                ],
                [
                    'title' => 'Aufsicht und Bußgeldrahmen im DE-Kontext',
                    'detail' => 'Zuständige Aufsichtsbehörden und Praxisleitfäden (LfDI/BfDI) prägen die Erwartung an Evidence und TOMs in deutschen Projekten.',
                ],
                [
                    'title' => 'Mitbestimmung bei Personaldaten',
                    'detail' => 'Betriebsvereinbarungen und Informationspflichten können Analytics-Use-Cases blockieren oder formen — unabhängig von der rein rechtlichen Zulässigkeit.',
                ],
                [
                    'title' => 'Besondere Kategorien vorsichtig behandeln',
                    'detail' => 'Gesundheits-, Religions- oder Gewerkschaftsdaten in DE-HR-Kontexten sind besonders sensibel; Default sollte Vermeidung oder starke Trennung sein.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Supplement, not a GDPR replacement',
                    'detail' => 'Check GDPR principles first, then BDSG openings and special rules. Many teams start wrongly at Section 26 and forget Arts. 5/6.',
                ],
                [
                    'title' => 'Employee data',
                    'detail' => 'Section 26 BDSG addresses processing in the employment relationship. HR analytics and monitoring need tight purpose limitation and often works-council alignment.',
                    'ref' => 'Section 26 BDSG',
                ],
                [
                    'title' => 'National opening clauses',
                    'detail' => 'GDPR leaves room for national rules — e.g. for special processing situations. Read those gaps actively; do not assume “EU-only”.',
                ],
                [
                    'title' => 'Supervision and fines in the DE context',
                    'detail' => 'Competent supervisory authorities and guidance (LfDI/BfDI) shape expectations for evidence and TOMs in German projects.',
                ],
                [
                    'title' => 'Co-determination for workforce data',
                    'detail' => 'Works agreements and information duties can block or shape analytics use cases — independent of pure legal permissibility.',
                ],
                [
                    'title' => 'Treat special categories carefully',
                    'detail' => 'Health, religion or trade-union data in DE HR contexts is especially sensitive; default should be avoidance or strong separation.',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'HR-Daten getrennt modellieren',
                    'detail' => 'Eigene Domäne, strengere RBAC, kürzere Retention und keine „wilden“ Joins in Enterprise-Wide-Marts.',
                ],
                [
                    'title' => 'Mitbestimmungs- und Legal-Flags',
                    'detail' => 'Metadaten markieren, welche Datasets Betriebsrats-/BDSG-relevant sind, bevor Self-Service sie findet.',
                ],
                [
                    'title' => 'DE-Aufsichts-Evidence vorbereiten',
                    'detail' => 'Verarbeitungsverzeichnis, TOMs und Zweckbeschreibungen so pflegen, dass DE-Audits sie ohne Jagd durch Confluence finden.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Model HR data separately',
                    'detail' => 'Separate domain, stricter RBAC, shorter retention and no casual joins into enterprise-wide marts.',
                ],
                [
                    'title' => 'Co-determination and legal flags',
                    'detail' => 'Metadata should flag which datasets are works-council/BDSG-relevant before self-service discovers them.',
                ],
                [
                    'title' => 'Prepare DE supervisory evidence',
                    'detail' => 'Maintain ROPA, TOMs and purpose descriptions so DE audits find them without hunting through Confluence.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Ist der Use-Case Beschäftigtendaten?',
                    'detail' => 'Wenn ja: § 26, Mitbestimmung und engere Minimierung explizit prüfen.',
                ],
                [
                    'title' => 'Gibt es eine Betriebsvereinbarung?',
                    'detail' => 'Analytics-/Monitoring-Vorhaben gegen vorhandene BV und Informationspflichten halten.',
                ],
                [
                    'title' => 'Sind besondere Kategorien ausgeschlossen?',
                    'detail' => 'Sonst starke technische Trennung und klarer Legal Sign-off.',
                ],
                [
                    'title' => 'DE- und Konzern-Policies aligned?',
                    'detail' => 'Globale GDPR-Templates reichen oft nicht für DE-HR-Spezifika.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Is the use case employee data?',
                    'detail' => 'If yes: explicitly check Section 26, co-determination and tighter minimisation.',
                ],
                [
                    'title' => 'Is there a works agreement?',
                    'detail' => 'Hold analytics/monitoring plans against existing agreements and information duties.',
                ],
                [
                    'title' => 'Are special categories excluded?',
                    'detail' => 'Otherwise strong technical separation and clear legal sign-off.',
                ],
                [
                    'title' => 'DE and group policies aligned?',
                    'detail' => 'Global GDPR templates often miss DE HR specifics.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => '„DSGVO reicht, BDSG ignorieren“',
                    'detail' => 'Besonders bei HR und öffentlichem Sektor führt das zu Lücken in Zulässigkeit und Prozess.',
                ],
                [
                    'title' => 'HR-Exports in den allgemeinen DWH-Lake',
                    'detail' => 'Einmalige „für Reporting“-Dumps werden zu Dauerproblemen für Rechte und Minimierung.',
                ],
                [
                    'title' => 'Mitbestimmung als Nachgedanke',
                    'detail' => 'Technisch fertige Dashboards scheitern spät an Betriebsrat — teurer als frühe Abstimmung.',
                ],
            ],
            'en' => [
                [
                    'title' => '“GDPR is enough, ignore BDSG”',
                    'detail' => 'Especially in HR and the public sector this creates gaps in lawfulness and process.',
                ],
                [
                    'title' => 'HR exports into the general DWH lake',
                    'detail' => 'One-off “for reporting” dumps become lasting rights and minimisation problems.',
                ],
                [
                    'title' => 'Co-determination as afterthought',
                    'detail' => 'Technically finished dashboards fail late at the works council — more expensive than early alignment.',
                ],
            ],
        ],
        'officialSources' => [
            [
                'label' => [
                    'de' => 'BDSG — Gesetze im Internet',
                    'en' => 'BDSG — Gesetze im Internet',
                ],
                'href' => 'https://www.gesetze-im-internet.de/bdsg_2018/',
            ],
            [
                'label' => [
                    'de' => 'BfDI — Bundesbeauftragter für den Datenschutz',
                    'en' => 'BfDI — Federal Commissioner for Data Protection',
                ],
                'href' => 'https://www.bfdi.bund.de/',
            ],
        ],
        'relatedPlaybooks' => [
            'pii-privacy-governance',
            'dsdr-governance',
            'access-security-governance',
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
            'de' => "Moderne Datenplattformen sind selten rein europäisch: Warehouse in einer EU-Region, Support aus Asien, Monitoring-SaaS in den USA, Modell-API in Kalifornien.\n\nKapitel V der DSGVO verlangt für jede dieser Konstellationen einen Übermittlungsmechanismus — seit Schrems II zusätzlich die Bewertung, ob das Recht des Ziellands die vertraglichen Garantien praktisch aushebelt. Für Plattform-Teams ist das keine reine Vertragsfrage: Region, Verschlüsselung, Key-Management und Admin-Zugriff entscheiden mit.",
            'en' => "Modern data platforms are rarely purely European: warehouse in an EU region, support from Asia, monitoring SaaS in the US, model API in California.\n\nChapter V of the GDPR requires a transfer mechanism for each of these constellations — and since Schrems II also an assessment of whether the destination country’s law undermines the contractual safeguards in practice. For platform teams this is not only a contract question: region, encryption, key management and admin access matter just as much.",
        ],
        'appliesTo' => [
            'de' => "Jede Übermittlung personenbezogener Daten an Empfänger außerhalb EU/EWR — inklusive Fernzugriff, Backups, Ticket-Anhängen und Sub-Auftragsverarbeitern in Drittländern.\n\nEntscheidend ist der Zugriff, nicht nur der Speicherort: Ein EU-gehostetes System mit Support-Zugriff aus einem Drittland löst denselben Prüfbedarf aus wie eine Replikation in eine US-Region.",
            'en' => "Any transfer of personal data to recipients outside the EU/EEA — including remote access, backups, ticket attachments and sub-processors in third countries.\n\nAccess is decisive, not only storage location: an EU-hosted system with support access from a third country triggers the same assessment as replication into a US region.",
        ],
        'scopeNotes' => [
            'de' => [
                'Kapitel V betrifft personenbezogene Daten — echte Aggregate ohne Re-Identifikationsrisiko fallen nicht darunter.',
                '„Übermittlung“ umfasst auch Fernzugriff, Wartung und Support, nicht nur Kopien in ein Drittland.',
                'Angemessenheitsbeschlüsse (z. B. EU-US Data Privacy Framework) können sich ändern oder gerichtlich überprüft werden — Fallback einplanen.',
                'Diese Seite ist Orientierung — Transfer Impact Assessment und Verträge gehören zu Legal/DSB.',
            ],
            'en' => [
                'Chapter V covers personal data — genuine aggregates without re-identification risk are out of scope.',
                '“Transfer” also covers remote access, maintenance and support — not only copies into a third country.',
                'Adequacy decisions (e.g. the EU-US Data Privacy Framework) can change or be challenged in court — plan a fallback.',
                'This page is orientation — transfer impact assessments and contracts belong with legal/DPO.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Kein Transfer ohne Mechanismus',
                    'detail' => 'Übermittlungen in Drittländer brauchen eine der in Kapitel V genannten Grundlagen. „Der Vendor ist groß und seriös“ ist keine.',
                    'ref' => 'Art. 44',
                ],
                [
                    'title' => 'Angemessenheitsbeschluss prüfen',
                    'detail' => 'Für Länder mit Angemessenheitsbeschluss entfallen Zusatzinstrumente. Beim EU-US Data Privacy Framework gilt das nur für zertifizierte Empfänger — Zertifizierung und abgedeckte Datenkategorien wirklich nachsehen.',
                    'ref' => 'Art. 45',
                ],
                [
                    'title' => 'Standardvertragsklauseln im richtigen Modul',
                    'detail' => 'Die SCC von 2021 haben vier Module (C2C, C2P, P2P, P2C). Das falsche Modul ist ein häufiger Formfehler — die Rollenverteilung im Datenfluss muss zum Modul passen.',
                    'ref' => 'Art. 46 Abs. 2 lit. c',
                ],
                [
                    'title' => 'Transfer Impact Assessment und Zusatzmaßnahmen',
                    'detail' => 'Nach Schrems II reicht die Unterschrift nicht: Recht und Praxis im Zielland bewerten und — wo nötig — technische Zusatzmaßnahmen ergänzen (Verschlüsselung mit EU-Schlüsselhoheit, Pseudonymisierung, Split Processing).',
                    'ref' => 'EDPB Empfehlungen 01/2020',
                ],
                [
                    'title' => 'Binding Corporate Rules für Konzernflüsse',
                    'detail' => 'Für konzerninterne Übermittlungen können genehmigte BCR der stabilere Weg sein als SCC pro Gesellschaft — Genehmigungsaufwand einplanen.',
                    'ref' => 'Art. 47',
                ],
                [
                    'title' => 'Ausnahmen sind Einzelfälle',
                    'detail' => 'Einwilligung oder Vertragserfüllung als Transfergrundlage sind eng auszulegen und nicht für regelmäßige, systematische Plattform-Flows gedacht.',
                    'ref' => 'Art. 49',
                ],
                [
                    'title' => 'Behördenanfragen aus Drittländern',
                    'detail' => 'Herausgabeverlangen ausländischer Behörden dürfen nicht ohne Rechtshilfeabkommen oder andere unionsrechtliche Grundlage erfüllt werden. Eskalationspfad und Meldewege vorab klären.',
                    'ref' => 'Art. 48',
                ],
                [
                    'title' => 'Sub-Prozessor-Kette kontrollieren',
                    'detail' => 'Auftragsverarbeiter dürfen Subprozessoren nur mit Genehmigung einsetzen und müssen gleichwertige Pflichten weitergeben. Genau hier entstehen unbemerkte Transfers.',
                    'ref' => 'Art. 28 Abs. 2 und 4',
                ],
            ],
            'en' => [
                [
                    'title' => 'No transfer without a mechanism',
                    'detail' => 'Transfers to third countries need one of the mechanisms listed in Chapter V. “The vendor is large and reputable” is not one of them.',
                    'ref' => 'Art. 44',
                ],
                [
                    'title' => 'Check the adequacy decision',
                    'detail' => 'For countries with an adequacy decision no additional instrument is needed. Under the EU-US Data Privacy Framework this only applies to certified recipients — actually verify certification status and covered data categories.',
                    'ref' => 'Art. 45',
                ],
                [
                    'title' => 'Standard contractual clauses in the right module',
                    'detail' => 'The 2021 SCCs come in four modules (C2C, C2P, P2P, P2C). Picking the wrong module is a common formal error — the roles in the data flow must match the module.',
                    'ref' => 'Art. 46(2)(c)',
                ],
                [
                    'title' => 'Transfer impact assessment and supplementary measures',
                    'detail' => 'After Schrems II a signature is not enough: assess law and practice in the destination country and, where needed, add technical supplementary measures (encryption with EU key control, pseudonymisation, split processing).',
                    'ref' => 'EDPB Recommendations 01/2020',
                ],
                [
                    'title' => 'Binding corporate rules for group flows',
                    'detail' => 'For intra-group transfers, approved BCRs can be more stable than SCCs per entity — plan for the approval effort.',
                    'ref' => 'Art. 47',
                ],
                [
                    'title' => 'Derogations are for individual cases',
                    'detail' => 'Consent or contract performance as a transfer basis must be read narrowly and are not meant for regular, systematic platform flows.',
                    'ref' => 'Art. 49',
                ],
                [
                    'title' => 'Third-country authority requests',
                    'detail' => 'Disclosure requests from foreign authorities must not be fulfilled without a mutual legal assistance treaty or another basis in Union law. Clarify escalation and notification paths in advance.',
                    'ref' => 'Art. 48',
                ],
                [
                    'title' => 'Control the sub-processor chain',
                    'detail' => 'Processors may only engage sub-processors with authorisation and must pass on equivalent obligations. This is exactly where unnoticed transfers appear.',
                    'ref' => 'Art. 28(2) and (4)',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'Region ist nicht gleich Zugriff',
                    'detail' => 'Eine EU-Region sagt nichts über Follow-the-Sun-Support, Betriebsteams oder Telemetrie. Admin- und Support-Zugriffe pro Dienst dokumentieren und wo möglich auf EU-Personal begrenzen.',
                ],
                [
                    'title' => 'Verschlüsselung mit eigener Schlüsselhoheit',
                    'detail' => 'Zusatzmaßnahmen wirken nur, wenn der Anbieter die Schlüssel nicht selbst hält: BYOK/HYOK, EU-KMS, Tokenisierung vor dem Upload. Verschlüsselung „at rest“ beim Anbieter allein ist schwach.',
                ],
                [
                    'title' => 'Transferregister je Dataset und Vendor',
                    'detail' => 'Verknüpft Datasets, Zwecke, Empfänger, Länder und Mechanismus. Ohne diese Liste ist keine TIA und kein Audit sauber führbar.',
                ],
                [
                    'title' => 'Minimieren, bevor übermittelt wird',
                    'detail' => 'Feldauswahl, Pseudonymisierung und Aggregation reduzieren das Transfer-Risiko oft stärker als jede Vertragsklausel — besonders bei Support-Exports und Debug-Datensätzen.',
                ],
                [
                    'title' => 'AI-Endpunkte und Prompt-Logs',
                    'detail' => 'Modell-APIs, Embeddings und Prompt-Logs verlassen die Region oft unbemerkt. Region, Retention und Trainingsnutzung vertraglich und technisch prüfen.',
                ],
                [
                    'title' => 'Fallback- und Exit-Pfad',
                    'detail' => 'Was passiert, wenn ein Angemessenheitsbeschluss fällt? Alternative Region, alternativer Anbieter und Migrationsaufwand grob vorplanen — nicht erst im Krisenmodus.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Region is not the same as access',
                    'detail' => 'An EU region says nothing about follow-the-sun support, operations teams or telemetry. Document admin and support access per service and restrict it to EU staff where possible.',
                ],
                [
                    'title' => 'Encryption with your own key control',
                    'detail' => 'Supplementary measures only work if the provider does not hold the keys: BYOK/HYOK, EU-based KMS, tokenisation before upload. Provider-managed encryption at rest alone is weak.',
                ],
                [
                    'title' => 'Transfer register per dataset and vendor',
                    'detail' => 'Link datasets, purposes, recipients, countries and mechanism. Without that list no TIA and no audit can be run cleanly.',
                ],
                [
                    'title' => 'Minimise before you transfer',
                    'detail' => 'Field selection, pseudonymisation and aggregation often reduce transfer risk more than any contract clause — especially for support exports and debug datasets.',
                ],
                [
                    'title' => 'AI endpoints and prompt logs',
                    'detail' => 'Model APIs, embeddings and prompt logs often leave the region unnoticed. Check region, retention and training use both contractually and technically.',
                ],
                [
                    'title' => 'Fallback and exit path',
                    'detail' => 'What happens if an adequacy decision falls? Sketch an alternative region, alternative provider and migration effort in advance — not in crisis mode.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Transferregister vollständig?',
                    'detail' => 'Inklusive Monitoring, Ticketing, CI/CD, BI-Extracts und LLM-Endpunkten — nicht nur das Warehouse.',
                ],
                [
                    'title' => 'Mechanismus je Transfer benannt?',
                    'detail' => 'Adequacy, SCC-Modul oder BCR pro Empfänger dokumentiert und aktuell.',
                ],
                [
                    'title' => 'TIA dokumentiert und datiert?',
                    'detail' => 'Mit Annahmen, Zusatzmaßnahmen und Review-Datum — nicht als einmaliges PDF ohne Owner.',
                ],
                [
                    'title' => 'Zusatzmaßnahmen technisch wirksam?',
                    'detail' => 'Schlüsselhoheit, Masking und Zugriffsgrenzen im Betrieb geprüft, nicht nur beschrieben.',
                ],
                [
                    'title' => 'DPF-Zertifizierung geprüft?',
                    'detail' => 'Empfänger im Data Privacy Framework gelistet, Status aktiv und passende Datenkategorien abgedeckt.',
                ],
                [
                    'title' => 'Fallback durchgespielt?',
                    'detail' => 'Grober Plan, welche Flows bei Wegfall des Mechanismus zuerst umgestellt oder gestoppt werden.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Transfer register complete?',
                    'detail' => 'Including monitoring, ticketing, CI/CD, BI extracts and LLM endpoints — not only the warehouse.',
                ],
                [
                    'title' => 'Mechanism named per transfer?',
                    'detail' => 'Adequacy, SCC module or BCR documented and current per recipient.',
                ],
                [
                    'title' => 'TIA documented and dated?',
                    'detail' => 'With assumptions, supplementary measures and a review date — not a one-off PDF without an owner.',
                ],
                [
                    'title' => 'Supplementary measures technically effective?',
                    'detail' => 'Key control, masking and access boundaries verified in operations, not only described.',
                ],
                [
                    'title' => 'DPF certification verified?',
                    'detail' => 'Recipient listed under the Data Privacy Framework, status active and matching data categories covered.',
                ],
                [
                    'title' => 'Fallback rehearsed?',
                    'detail' => 'A rough plan for which flows get switched or stopped first if the mechanism disappears.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => '„EU-Region reicht“',
                    'detail' => 'Speicherort ohne Zugriffskontrolle löst nichts: Remote-Support, Telemetrie und Konzern-Admins bleiben Transfers.',
                ],
                [
                    'title' => 'DPF pauschal angenommen',
                    'detail' => 'Nicht jeder US-Anbieter ist zertifiziert, und Zertifizierungen deckten nicht immer alle Datenarten oder Konzerngesellschaften ab.',
                ],
                [
                    'title' => 'SCC unterschrieben, TIA fehlt',
                    'detail' => 'Die Klauseln allein erfüllen Schrems II nicht — ohne Bewertung und Zusatzmaßnahmen bleibt eine Lücke.',
                ],
                [
                    'title' => 'Verschlüsselung ohne Schlüsselkontrolle',
                    'detail' => 'Wenn der Anbieter die Schlüssel verwaltet, hilft Verschlüsselung gegen Behördenzugriff kaum.',
                ],
                [
                    'title' => 'Schatten-Transfers',
                    'detail' => 'Notebooks, Browser-Plugins, Screenshots in Support-Tickets und private LLM-Accounts erzeugen Übermittlungen, die in keinem Register stehen.',
                ],
            ],
            'en' => [
                [
                    'title' => '“An EU region is enough”',
                    'detail' => 'Storage location without access control solves nothing: remote support, telemetry and group admins remain transfers.',
                ],
                [
                    'title' => 'DPF assumed blanket-wide',
                    'detail' => 'Not every US provider is certified, and certifications do not always cover all data types or group entities.',
                ],
                [
                    'title' => 'SCCs signed, TIA missing',
                    'detail' => 'The clauses alone do not satisfy Schrems II — without assessment and supplementary measures a gap remains.',
                ],
                [
                    'title' => 'Encryption without key control',
                    'detail' => 'If the provider manages the keys, encryption hardly helps against government access.',
                ],
                [
                    'title' => 'Shadow transfers',
                    'detail' => 'Notebooks, browser plugins, screenshots in support tickets and personal LLM accounts create transfers that appear in no register.',
                ],
            ],
        ],
        'officialSources' => [
            [
                'label' => [
                    'de' => 'EU-Kommission — Standardvertragsklauseln',
                    'en' => 'European Commission — standard contractual clauses',
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
                    'de' => 'EDPB — Empfehlungen 01/2020 (Zusatzmaßnahmen)',
                    'en' => 'EDPB — Recommendations 01/2020 (supplementary measures)',
                ],
                'href' => 'https://www.edpb.europa.eu/our-work-tools/our-documents/recommendations/recommendations-012020-measures-supplement-transfer_en',
            ],
            [
                'label' => [
                    'de' => 'Angemessenheitsbeschluss EU-US Data Privacy Framework — EUR-Lex',
                    'en' => 'EU-US Data Privacy Framework adequacy decision — EUR-Lex',
                ],
                'href' => 'https://eur-lex.europa.eu/eli/dec_impl/2023/1795/oj',
            ],
        ],
        'relatedPlaybooks' => [
            'pii-privacy-governance',
            'host-vs-cloud',
            'dsdr-governance',
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
            'de' => "ISO/IEC 27001 ist in Ausschreibungen und Vendor-Assessments die häufigste Eintrittskarte — und für Datenplattformen ein brauchbares Gerüst, um Zugriff, Betrieb und Lieferanten sauber zu ordnen.\n\nDer Kern ist nicht die Control-Liste, sondern der Managementzyklus: Kontext und Scope festlegen, Risiken bewerten, Maßnahmen auswählen und begründen, Wirksamkeit messen, nachbessern. Genau diese Denkweise fehlt vielen Plattform-Teams, die Sicherheit als Sammlung einzelner Einstellungen betreiben.",
            'en' => "ISO/IEC 27001 is the most common entry ticket in tenders and vendor assessments — and for data platforms a workable frame to order access, operations and suppliers.\n\nIts core is not the control list but the management cycle: define context and scope, assess risk, select and justify measures, measure effectiveness, improve. That way of thinking is what many platform teams miss when they treat security as a pile of individual settings.",
        ],
        'appliesTo' => [
            'de' => "Jede Organisation, die ein ISMS aufbauen oder zertifizieren will — unabhängig von Größe und Branche. Der Scope wird selbst definiert, und genau darin liegt die Kunst.\n\nFür Datenteams heißt das: Warehouse, Orchestrierung, BI und Betriebsprozesse gehören in den Scope, wenn schützenswerte Daten dort verarbeitet werden. Sonst zertifiziert man an der eigentlichen Plattform vorbei.",
            'en' => "Any organisation that wants to build or certify an ISMS — regardless of size or industry. The scope is self-defined, and that is where the craft lies.\n\nFor data teams that means warehouse, orchestration, BI and operational processes belong in scope if sensitive data is processed there. Otherwise you certify around the actual platform.",
        ],
        'scopeNotes' => [
            'de' => [
                'Zertifizierung gilt nur für den definierten Scope — ein Zertifikat sagt nichts über nicht erfasste Systeme.',
                'ISO 27001 ist Sicherheitsmanagement, kein Datenschutznachweis; dafür gibt es ISO 27701 und die DSGVO selbst.',
                'Annex A liefert Controls, ISO 27002 die Umsetzungshinweise — beides ist Auswahlmenü, nicht Pflichtprogramm.',
                'Das Zertifikat eines Cloud-Anbieters ersetzt nicht eure eigenen Controls in der geteilten Verantwortung.',
            ],
            'en' => [
                'Certification only covers the defined scope — a certificate says nothing about systems left out.',
                'ISO 27001 is security management, not privacy proof; ISO 27701 and the GDPR itself cover that.',
                'Annex A provides controls, ISO 27002 the implementation guidance — both are a menu, not a mandatory checklist.',
                'A cloud provider’s certificate does not replace your own controls under shared responsibility.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Kontext und Scope bestimmen',
                    'detail' => 'Interessierte Parteien, Anforderungen und Grenzen des ISMS festlegen. Für Plattformen: welche Umgebungen, Datenklassen und Dienstleister drin sind — und was bewusst draußen bleibt.',
                    'ref' => 'Kapitel 4',
                ],
                [
                    'title' => 'Führung und Sicherheitspolitik',
                    'detail' => 'Leitung muss Politik, Ziele und Rollen verbindlich setzen. Ohne Mandat bleiben Zugriffs- und Klassifizierungsregeln in Datenteams unverbindliche Empfehlungen.',
                    'ref' => 'Kapitel 5',
                ],
                [
                    'title' => 'Risikobeurteilung und -behandlung',
                    'detail' => 'Risiken systematisch bewerten, Optionen wählen und die Auswahl in der Erklärung zur Anwendbarkeit (SoA) begründen — inklusive bewusster Ausschlüsse.',
                    'ref' => 'Kapitel 6.1',
                ],
                [
                    'title' => 'Kompetenz, Awareness und Dokumentation',
                    'detail' => 'Menschen und Nachweise gehören zum System: Schulungen, Verantwortlichkeiten und versionierte Dokumente statt Wissen in Köpfen.',
                    'ref' => 'Kapitel 7',
                ],
                [
                    'title' => 'Betrieb und Änderungssteuerung',
                    'detail' => 'Prozesse planen, umsetzen und steuern — auch bei Änderungen. Für Datenplattformen sind Deployments, Schema-Änderungen und Zugriffsanträge die relevanten Betriebsprozesse.',
                    'ref' => 'Kapitel 8',
                ],
                [
                    'title' => 'Überwachung, internes Audit, Management Review',
                    'detail' => 'Wirksamkeit messen, intern prüfen und auf Leitungsebene bewerten. Kennzahlen wie offene Zugriffs-Findings oder Patch-Rückstand sind hier wertvoller als Prosa.',
                    'ref' => 'Kapitel 9',
                ],
                [
                    'title' => 'Abweichungen und Verbesserung',
                    'detail' => 'Findings, Korrekturmaßnahmen und Wirksamkeitskontrolle dokumentieren. Der Zyklus ist der eigentliche Wert des Standards.',
                    'ref' => 'Kapitel 10',
                ],
                [
                    'title' => 'Annex-A-Controls gezielt einsetzen',
                    'detail' => 'Die Controls sind in vier Themen gruppiert (organisatorisch, personenbezogen, physisch, technologisch). ISO 27002 liefert die Umsetzungshinweise, etwa zu Zugriffsrechten, Logging und Kryptografie.',
                    'ref' => 'Annex A, ISO/IEC 27002',
                ],
            ],
            'en' => [
                [
                    'title' => 'Determine context and scope',
                    'detail' => 'Define interested parties, requirements and ISMS boundaries. For platforms: which environments, data classes and service providers are in — and what is deliberately out.',
                    'ref' => 'Clause 4',
                ],
                [
                    'title' => 'Leadership and security policy',
                    'detail' => 'Top management must set policy, objectives and roles with authority. Without a mandate, access and classification rules stay optional advice inside data teams.',
                    'ref' => 'Clause 5',
                ],
                [
                    'title' => 'Risk assessment and treatment',
                    'detail' => 'Assess risks systematically, choose options and justify the selection in the statement of applicability (SoA) — including deliberate exclusions.',
                    'ref' => 'Clause 6.1',
                ],
                [
                    'title' => 'Competence, awareness and documented information',
                    'detail' => 'People and evidence are part of the system: training, responsibilities and versioned documents instead of knowledge in heads.',
                    'ref' => 'Clause 7',
                ],
                [
                    'title' => 'Operation and change control',
                    'detail' => 'Plan, implement and control processes — including changes. For data platforms the relevant operational processes are deployments, schema changes and access requests.',
                    'ref' => 'Clause 8',
                ],
                [
                    'title' => 'Monitoring, internal audit, management review',
                    'detail' => 'Measure effectiveness, audit internally and review at management level. Metrics such as open access findings or patch backlog are more useful here than prose.',
                    'ref' => 'Clause 9',
                ],
                [
                    'title' => 'Nonconformity and improvement',
                    'detail' => 'Document findings, corrective actions and effectiveness checks. The cycle is the standard’s real value.',
                    'ref' => 'Clause 10',
                ],
                [
                    'title' => 'Use Annex A controls deliberately',
                    'detail' => 'The controls are grouped into four themes (organisational, people, physical, technological). ISO 27002 provides implementation guidance, for example on access rights, logging and cryptography.',
                    'ref' => 'Annex A, ISO/IEC 27002',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'Asset- und Dateninventar',
                    'detail' => 'Datasets, Data Products, Service-Accounts und Integrationen als Assets führen — mit Owner und Klassifizierung. Das ist die Basis für fast jedes Control.',
                ],
                [
                    'title' => 'Zugriffsmanagement als Prozess',
                    'detail' => 'Rollenmodell, Antrag, Genehmigung, Entzug und regelmäßige Reviews — inklusive privilegierter Konten in Warehouse und BI.',
                ],
                [
                    'title' => 'Logging und Monitoring',
                    'detail' => 'Query-, Login- und Admin-Logs zentral sammeln, Aufbewahrung festlegen und Auswertungen definieren. Logs ohne Auswertung sind kein Control.',
                ],
                [
                    'title' => 'Sichere Entwicklung für Pipelines',
                    'detail' => 'Code Review, getrennte Umgebungen, Secret-Handling und Test-Daten-Regeln gelten auch für dbt-Projekte, Notebooks und Orchestrierung.',
                ],
                [
                    'title' => 'Lieferantensteuerung',
                    'detail' => 'Cloud, SaaS und Beratungspartner bewerten, vertraglich binden und periodisch nachprüfen — mit Nachweisen statt Marketingseiten.',
                ],
                [
                    'title' => 'Kryptografie und Schlüssel',
                    'detail' => 'Verschlüsselung in Transit und at Rest, Schlüsselverwaltung und Rotation dokumentieren — inklusive Ausnahmen für Legacy-Schnittstellen.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Asset and data inventory',
                    'detail' => 'Track datasets, data products, service accounts and integrations as assets — with owner and classification. That is the basis for nearly every control.',
                ],
                [
                    'title' => 'Access management as a process',
                    'detail' => 'Role model, request, approval, revocation and periodic reviews — including privileged accounts in warehouse and BI.',
                ],
                [
                    'title' => 'Logging and monitoring',
                    'detail' => 'Collect query, login and admin logs centrally, define retention and define evaluations. Logs without evaluation are not a control.',
                ],
                [
                    'title' => 'Secure development for pipelines',
                    'detail' => 'Code review, separated environments, secret handling and test-data rules also apply to dbt projects, notebooks and orchestration.',
                ],
                [
                    'title' => 'Supplier management',
                    'detail' => 'Assess, contractually bind and periodically re-check cloud, SaaS and consulting partners — with evidence instead of marketing pages.',
                ],
                [
                    'title' => 'Cryptography and keys',
                    'detail' => 'Document encryption in transit and at rest, key management and rotation — including exceptions for legacy interfaces.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Deckt der Scope die Plattform ab?',
                    'detail' => 'Warehouse, Orchestrierung, BI und relevante Cloud-Konten sind ausdrücklich genannt.',
                ],
                [
                    'title' => 'SoA mit echten Begründungen?',
                    'detail' => 'Jedes ausgeschlossene Control hat eine nachvollziehbare Begründung, kein Standardtext.',
                ],
                [
                    'title' => 'Risikoregister mit Datenrisiken?',
                    'detail' => 'Enthält plattformtypische Risiken: überbreite Rechte, Kopien in Notebooks, unklare Ownership.',
                ],
                [
                    'title' => 'Access-Review-Evidence auffindbar?',
                    'detail' => 'Letzte Reviews mit Datum, Prüfer und Ergebnis liegen greifbar vor.',
                ],
                [
                    'title' => 'Lieferantenbewertungen aktuell?',
                    'detail' => 'Kritische Anbieter haben eine dokumentierte Bewertung im laufenden Zyklus.',
                ],
                [
                    'title' => 'Internes Audit mit Plattform-Fokus?',
                    'detail' => 'Mindestens ein Audit hat Pipelines und Zugriffe konkret geprüft, nicht nur Policies gelesen.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Does the scope cover the platform?',
                    'detail' => 'Warehouse, orchestration, BI and the relevant cloud accounts are named explicitly.',
                ],
                [
                    'title' => 'SoA with real justifications?',
                    'detail' => 'Every excluded control has a traceable justification, not boilerplate.',
                ],
                [
                    'title' => 'Risk register with data risks?',
                    'detail' => 'Contains platform-typical risks: overly broad rights, copies in notebooks, unclear ownership.',
                ],
                [
                    'title' => 'Access review evidence findable?',
                    'detail' => 'Recent reviews with date, reviewer and outcome are readily available.',
                ],
                [
                    'title' => 'Supplier assessments current?',
                    'detail' => 'Critical providers have a documented assessment within the current cycle.',
                ],
                [
                    'title' => 'Internal audit with platform focus?',
                    'detail' => 'At least one audit actually examined pipelines and access, not only read policies.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => 'Scope schneidet die Plattform aus',
                    'detail' => 'Ein Zertifikat für Rechenzentrum und HQ beeindruckt Kunden, deckt aber das Analytics-Setup nicht ab.',
                ],
                [
                    'title' => 'SoA als Papierübung',
                    'detail' => 'Controls „umgesetzt“ zu markieren, ohne Runtime-Nachweis, fällt im ersten Audit oder Incident auf.',
                ],
                [
                    'title' => 'Policies ohne Durchsetzung',
                    'detail' => 'Klassifizierungsvorgaben ohne Masking, Rollen ohne Grants und Reviews ohne Entzug bleiben wirkungslos.',
                ],
                [
                    'title' => 'ISO 27001 als DSGVO-Nachweis',
                    'detail' => 'Sicherheitsmanagement liefert keine Rechtsgrundlage, keine Transparenz und keine Löschfähigkeit.',
                ],
                [
                    'title' => 'Vendor-Zertifikat als eigenes',
                    'detail' => 'Der Anbieter deckt Infrastruktur ab; Konfiguration, Rechte und Daten bleiben eure Verantwortung.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Scope cuts out the platform',
                    'detail' => 'A certificate for the data centre and HQ impresses customers but does not cover the analytics setup.',
                ],
                [
                    'title' => 'SoA as a paper exercise',
                    'detail' => 'Marking controls “implemented” without runtime evidence surfaces at the first audit or incident.',
                ],
                [
                    'title' => 'Policies without enforcement',
                    'detail' => 'Classification rules without masking, roles without grants and reviews without revocation stay ineffective.',
                ],
                [
                    'title' => 'ISO 27001 as GDPR proof',
                    'detail' => 'Security management provides no legal basis, no transparency and no erasure capability.',
                ],
                [
                    'title' => 'Vendor certificate treated as your own',
                    'detail' => 'The provider covers infrastructure; configuration, permissions and data remain your responsibility.',
                ],
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
            'de' => "Wer Cloud-Warehouses, BI-SaaS oder Reverse-ETL-Dienste einkauft, bekommt als Nachweis meist einen SOC-2-Bericht. Er ist die häufigste Währung im Vendor-Assessment.\n\nDer Wert liegt nicht im Logo, sondern im Kleingedruckten: Systembeschreibung, Prüfzeitraum, getestete Controls, gefundene Abweichungen und die Controls, die der Prüfer euch zuschreibt. Genau diese Teile werden am seltensten gelesen.",
            'en' => "If you buy cloud warehouses, BI SaaS or reverse-ETL services, the evidence you get is usually a SOC 2 report. It is the most common currency in vendor assessments.\n\nIts value is not the logo but the fine print: system description, examination period, tested controls, exceptions found and the controls the auditor assigns to you. Those parts are the ones least often read.",
        ],
        'appliesTo' => [
            'de' => "Dienstleister, die Kundendaten verarbeiten und ihren Kunden Sicherheitsnachweise liefern müssen — vor allem US-geprägte SaaS-Anbieter, zunehmend auch europäische.\n\nAls Kunde seid ihr indirekt betroffen: Ihr müsst Berichte lesen, Lücken bewerten und die euch zugewiesenen Controls tatsächlich umsetzen.",
            'en' => "Service providers that process customer data and need to give customers security assurance — primarily US-shaped SaaS vendors, increasingly European ones too.\n\nAs a customer you are indirectly affected: you have to read reports, assess gaps and actually implement the controls assigned to you.",
        ],
        'scopeNotes' => [
            'de' => [
                'SOC 2 ist eine Attestierung durch einen Prüfer, keine Zertifizierung mit Gütesiegel.',
                'Der Bericht gilt nur für das beschriebene System und den genannten Zeitraum.',
                'Die Kategorie Privacy in SOC 2 ist nicht deckungsgleich mit DSGVO-Pflichten.',
                'Berichte sind meist vertraulich — plant Fristen für NDA und Beschaffung ein.',
            ],
            'en' => [
                'SOC 2 is an attestation by an auditor, not a certification with a seal.',
                'The report only covers the described system and the stated period.',
                'The SOC 2 privacy category is not congruent with GDPR obligations.',
                'Reports are usually confidential — plan lead time for NDA and procurement.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Trust Services Criteria wählen',
                    'detail' => 'Security ist immer dabei; Availability, Confidentiality, Processing Integrity und Privacy sind optional. Fehlt eine Kategorie, wurde sie nicht geprüft.',
                ],
                [
                    'title' => 'Type I und Type II unterscheiden',
                    'detail' => 'Type I beurteilt nur die Ausgestaltung zu einem Stichtag, Type II die Wirksamkeit über einen Zeitraum. Für Vendor-Entscheidungen zählt praktisch nur Type II.',
                ],
                [
                    'title' => 'Systembeschreibung ist der Scope',
                    'detail' => 'Sie legt Dienste, Regionen, Komponenten und Grenzen fest. Wenn euer genutztes Produkt oder eure Region dort fehlt, hilft der Bericht wenig.',
                ],
                [
                    'title' => 'Abweichungen im Testteil lesen',
                    'detail' => 'Der eigentliche Informationsgehalt steckt in den Testergebnissen und Ausnahmen — nicht im Prüfungsurteil auf Seite eins.',
                ],
                [
                    'title' => 'Complementary User Entity Controls',
                    'detail' => 'Der Bericht listet Controls, die ihr selbst umsetzen müsst (MFA, Rechtevergabe, Konfiguration). Ohne diese Hausaufgaben gilt die Zusicherung des Anbieters nicht.',
                ],
                [
                    'title' => 'Subservice-Organisationen prüfen',
                    'detail' => 'Unterauftragnehmer werden per Carve-out ausgeschlossen oder inklusiv geprüft. Bei Carve-out braucht ihr die Nachweise dieser Anbieter separat.',
                ],
                [
                    'title' => 'Prüfzeitraum und Lückenzeit',
                    'detail' => 'Deckt der Zeitraum eure Nutzung ab? Für die Zeit nach Berichtsende gibt es üblicherweise ein Bridge Letter.',
                ],
                [
                    'title' => 'Bericht ist Momentaufnahme',
                    'detail' => 'Jährliche Erneuerung nachhalten und Änderungen an Architektur oder Subprozessoren neu bewerten.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Trust Services Criteria selection',
                    'detail' => 'Security is always included; availability, confidentiality, processing integrity and privacy are optional. If a category is missing, it was not examined.',
                ],
                [
                    'title' => 'Distinguish Type I and Type II',
                    'detail' => 'Type I only assesses design at a point in time, Type II operating effectiveness over a period. For vendor decisions only Type II really counts.',
                ],
                [
                    'title' => 'The system description is the scope',
                    'detail' => 'It defines services, regions, components and boundaries. If the product you use or your region is missing, the report helps little.',
                ],
                [
                    'title' => 'Read the exceptions in the testing section',
                    'detail' => 'The real information sits in the test results and exceptions — not in the opinion on page one.',
                ],
                [
                    'title' => 'Complementary user entity controls',
                    'detail' => 'The report lists controls you must implement yourself (MFA, permission management, configuration). Without that homework the provider’s assurance does not hold.',
                ],
                [
                    'title' => 'Check subservice organisations',
                    'detail' => 'Sub-service providers are either carved out or examined inclusively. With a carve-out you need those providers’ evidence separately.',
                ],
                [
                    'title' => 'Examination period and gap',
                    'detail' => 'Does the period cover your usage? For the time after the report end date a bridge letter is the usual instrument.',
                ],
                [
                    'title' => 'A report is a snapshot',
                    'detail' => 'Track annual renewal and re-assess changes in architecture or sub-processors.',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'Zugewiesene Controls umsetzen',
                    'detail' => 'CUEC-Liste in konkrete Aufgaben übersetzen: SSO/MFA erzwingen, Rollen begrenzen, Netzwerkregeln setzen, Logs aktivieren.',
                ],
                [
                    'title' => 'Vendor-Register mit Berichtsdaten',
                    'detail' => 'Anbieter, Produkt, Berichtstyp, Zeitraum, Kategorien und offene Findings an einem Ort — sonst beginnt jede Prüfung von vorn.',
                ],
                [
                    'title' => 'Kritikalität statt Gleichbehandlung',
                    'detail' => 'Das Warehouse mit PII verdient eine tiefere Prüfung als ein Diagramm-Tool ohne Kundendaten.',
                ],
                [
                    'title' => 'Eigene Controls dagegen mappen',
                    'detail' => 'Nutzt die Kriterien als Spiegel für eigene Access-, Logging- und Change-Prozesse, statt sie nur beim Anbieter zu prüfen.',
                ],
                [
                    'title' => 'Cloud-Unterbau nicht vergessen',
                    'detail' => 'Ein BI-SaaS läuft meist auf einem Hyperscaler; prüft, ob dessen Controls inklusiv oder per Carve-out behandelt sind.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Implement the assigned controls',
                    'detail' => 'Translate the CUEC list into concrete tasks: enforce SSO/MFA, limit roles, set network rules, enable logs.',
                ],
                [
                    'title' => 'Vendor register with report metadata',
                    'detail' => 'Provider, product, report type, period, categories and open findings in one place — otherwise every review starts from scratch.',
                ],
                [
                    'title' => 'Criticality instead of equal treatment',
                    'detail' => 'The warehouse holding PII deserves deeper review than a diagramming tool without customer data.',
                ],
                [
                    'title' => 'Map your own controls against it',
                    'detail' => 'Use the criteria as a mirror for your own access, logging and change processes instead of only checking the provider.',
                ],
                [
                    'title' => 'Do not forget the cloud substrate',
                    'detail' => 'A BI SaaS usually runs on a hyperscaler; check whether its controls are inclusive or carved out.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Aktueller Type-II-Bericht vorhanden?',
                    'detail' => 'Für jeden kritischen Datenanbieter, nicht nur für den größten.',
                ],
                [
                    'title' => 'Zeitraum deckt die Nutzung ab?',
                    'detail' => 'Inklusive Bridge Letter für die Lücke bis heute.',
                ],
                [
                    'title' => 'Scope enthält Produkt und Region?',
                    'detail' => 'Systembeschreibung wirklich gelesen und mit dem eigenen Setup verglichen.',
                ],
                [
                    'title' => 'Abweichungen bewertet?',
                    'detail' => 'Findings dokumentiert, Risiko akzeptiert oder kompensiert — mit Owner.',
                ],
                [
                    'title' => 'CUECs zugewiesen?',
                    'detail' => 'Jede Kundenpflicht hat ein Team und einen Umsetzungsnachweis.',
                ],
                [
                    'title' => 'Subprozessoren abgedeckt?',
                    'detail' => 'Carve-out-Anbieter separat bewertet oder bewusst akzeptiert.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Current Type II report on file?',
                    'detail' => 'For every critical data provider, not only the biggest one.',
                ],
                [
                    'title' => 'Period covers your usage?',
                    'detail' => 'Including a bridge letter for the gap up to today.',
                ],
                [
                    'title' => 'Scope includes product and region?',
                    'detail' => 'System description actually read and compared with your own setup.',
                ],
                [
                    'title' => 'Exceptions assessed?',
                    'detail' => 'Findings documented, risk accepted or compensated — with an owner.',
                ],
                [
                    'title' => 'CUECs assigned?',
                    'detail' => 'Every customer obligation has a team and evidence of implementation.',
                ],
                [
                    'title' => 'Sub-processors covered?',
                    'detail' => 'Carved-out providers assessed separately or consciously accepted.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => 'SOC 2 als DSGVO-Nachweis',
                    'detail' => 'Security-Attestierung ersetzt keine Rechtsgrundlage, keine Transferprüfung und keine Löschfähigkeit.',
                ],
                [
                    'title' => 'Type I für Type II gehalten',
                    'detail' => 'Ein Design-Nachweis zum Stichtag sagt nichts über den Betrieb über zwölf Monate.',
                ],
                [
                    'title' => 'Abgelaufener Bericht im Ordner',
                    'detail' => 'Zwei Jahre alte Berichte ohne Bridge Letter sind im Audit wertlos.',
                ],
                [
                    'title' => 'Carve-out ignoriert',
                    'detail' => 'Der Bericht endet an der Anbietergrenze; die Infrastruktur darunter bleibt ungeprüft.',
                ],
                [
                    'title' => 'CUECs nie gelesen',
                    'detail' => 'Wenn der Anbieter MFA und Rechteverwaltung euch zuweist und niemand es tut, entsteht genau dort die Lücke.',
                ],
            ],
            'en' => [
                [
                    'title' => 'SOC 2 as GDPR proof',
                    'detail' => 'A security attestation replaces neither legal basis nor transfer assessment nor erasure capability.',
                ],
                [
                    'title' => 'Type I mistaken for Type II',
                    'detail' => 'A point-in-time design assessment says nothing about operations over twelve months.',
                ],
                [
                    'title' => 'Expired report in the folder',
                    'detail' => 'Two-year-old reports without a bridge letter are worthless in an audit.',
                ],
                [
                    'title' => 'Carve-out ignored',
                    'detail' => 'The report stops at the provider boundary; the infrastructure beneath stays unexamined.',
                ],
                [
                    'title' => 'CUECs never read',
                    'detail' => 'If the provider assigns MFA and permission management to you and nobody does it, that is exactly where the gap appears.',
                ],
            ],
        ],
        'officialSources' => [
            [
                'label' => [
                    'de' => 'AICPA — SOC 2 (Audit & Assurance)',
                    'en' => 'AICPA — SOC 2 (audit & assurance)',
                ],
                'href' => 'https://www.aicpa-cima.com/topic/audit-assurance/audit-and-assurance-greater-than-soc-2',
            ],
            [
                'label' => [
                    'de' => 'AICPA — SOC Suite of Services',
                    'en' => 'AICPA — SOC suite of services',
                ],
                'href' => 'https://www.aicpa-cima.com/resources/landing/system-and-organization-controls-soc-suite-of-services',
            ],
        ],
        'relatedPlaybooks' => [
            'access-security-governance',
            'host-vs-cloud',
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
            'de' => "In deutschen Ausschreibungen und im öffentlichen Sektor ist C5 oft der entscheidende Nachweis für Cloud-Dienste — und für Datenplattformen der Bericht mit den konkretesten Aussagen zu Betrieb, Standort und Unterauftragnehmern.\n\nBesonders wertvoll sind die Umfeldparameter: Sie beschreiben Rechtsraum, Datenstandorte, Zugriffsmöglichkeiten und Subunternehmer. Wer eine Transferbewertung oder ein Hosting-Entscheidungspapier schreibt, findet hier belastbare Fakten statt Marketing.",
            'en' => "In German tenders and the public sector, C5 is often the decisive evidence for cloud services — and for data platforms the report with the most concrete statements about operations, location and sub-contractors.\n\nThe environment parameters are particularly valuable: they describe legal jurisdiction, data locations, access options and sub-contractors. Anyone writing a transfer assessment or a hosting decision paper finds solid facts here instead of marketing.",
        ],
        'appliesTo' => [
            'de' => "Cloud-Anbieter, die ihren Kunden einen belastbaren Sicherheitsnachweis liefern wollen — und Kunden im deutschen Markt, die ihn einfordern.\n\nFür Plattform-Teams ist C5 vor allem ein Einkaufs- und Architekturinstrument: Es hilft, Anbieter zu vergleichen und die eigenen Restpflichten aus der geteilten Verantwortung zu erkennen.",
            'en' => "Cloud providers that want to give customers solid security evidence — and customers in the German market who require it.\n\nFor platform teams C5 is mainly a procurement and architecture instrument: it helps compare providers and recognise your remaining duties under shared responsibility.",
        ],
        'scopeNotes' => [
            'de' => [
                'C5 ist ein Kriterienkatalog mit Prüfung nach ISAE 3000 — es gibt keine „C5-Zertifizierung“ mit Siegel.',
                'Der Katalog richtet sich an Anbieter; Kundenpflichten stehen in den ergänzenden Kundenkriterien.',
                'C5 deckt Informationssicherheit ab, nicht den Datenschutz insgesamt.',
                'Typ 1 betrachtet die Ausgestaltung, Typ 2 die Wirksamkeit über einen Zeitraum.',
            ],
            'en' => [
                'C5 is a criteria catalogue examined under ISAE 3000 — there is no “C5 certification” with a seal.',
                'The catalogue addresses providers; customer duties sit in the complementary customer criteria.',
                'C5 covers information security, not data protection as a whole.',
                'Type 1 looks at design, Type 2 at effectiveness over a period.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Kriterienkatalog über Domänen',
                    'detail' => 'C5:2020 ordnet Anforderungen in Bereiche wie Organisation der Informationssicherheit, Identitäts- und Rechteverwaltung, Kryptografie, Betrieb, Beschaffung und Notfallmanagement.',
                ],
                [
                    'title' => 'Basis- und Zusatzkriterien',
                    'detail' => 'Neben den Basiskriterien gibt es Zusatzkriterien für höheren Schutzbedarf. Prüfen, welche im Bericht wirklich adressiert sind.',
                ],
                [
                    'title' => 'Prüfung nach ISAE 3000',
                    'detail' => 'Ein unabhängiger Wirtschaftsprüfer testet die Controls und dokumentiert Feststellungen — inhaltlich vergleichbar mit SOC 2, aber mit deutschem Anforderungsrahmen.',
                ],
                [
                    'title' => 'Umfeldparameter als Pflichtlektüre',
                    'detail' => 'Rechtsraum, Gerichtsstand, Datenstandorte, Standorte des Betriebspersonals, Unterauftragnehmer und Offenlegungspflichten gegenüber Behörden werden offengelegt.',
                ],
                [
                    'title' => 'Ergänzende Kundenkriterien',
                    'detail' => 'Der Bericht benennt, was in der Verantwortung des Kunden bleibt: Konfiguration, Rechtevergabe, Verschlüsselung eigener Daten, Monitoring der eigenen Nutzung.',
                ],
                [
                    'title' => 'Anschluss an ISO 27001 und Verwandte',
                    'detail' => 'Viele Kriterien überschneiden sich mit ISO/IEC 27001, 27017 und 27018. Anbieter mit ISMS erreichen C5 leichter; Kunden können Nachweise mappen statt doppelt zu erheben.',
                ],
                [
                    'title' => 'Scope pro Dienst und Region',
                    'detail' => 'Ein Bericht gilt für benannte Dienste in benannten Regionen. Neue Services eines Anbieters sind selten automatisch abgedeckt.',
                ],
                [
                    'title' => 'Relevanz in regulierten Kontexten',
                    'detail' => 'Öffentliche Auftraggeber und regulierte Branchen nutzen C5 häufig als Mindestanforderung — auch als Baustein für NIS2- und DORA-Nachweise gegenüber Dritten.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Criteria catalogue across domains',
                    'detail' => 'C5:2020 groups requirements into areas such as organisation of information security, identity and access management, cryptography, operations, procurement and business continuity.',
                ],
                [
                    'title' => 'Basic and additional criteria',
                    'detail' => 'Alongside the basic criteria there are additional criteria for higher protection needs. Check which ones the report actually addresses.',
                ],
                [
                    'title' => 'Examination under ISAE 3000',
                    'detail' => 'An independent auditor tests the controls and documents findings — comparable to SOC 2 in nature, but with a German requirements frame.',
                ],
                [
                    'title' => 'Environment parameters as required reading',
                    'detail' => 'Jurisdiction, place of venue, data locations, locations of operating staff, sub-contractors and disclosure duties towards authorities are disclosed.',
                ],
                [
                    'title' => 'Complementary customer criteria',
                    'detail' => 'The report states what remains the customer’s responsibility: configuration, permission management, encryption of your own data, monitoring of your own usage.',
                ],
                [
                    'title' => 'Alignment with ISO 27001 and relatives',
                    'detail' => 'Many criteria overlap with ISO/IEC 27001, 27017 and 27018. Providers with an ISMS reach C5 more easily; customers can map evidence instead of collecting it twice.',
                ],
                [
                    'title' => 'Scope per service and region',
                    'detail' => 'A report covers named services in named regions. A provider’s new services are rarely covered automatically.',
                ],
                [
                    'title' => 'Relevance in regulated contexts',
                    'detail' => 'Public buyers and regulated industries often use C5 as a minimum requirement — also as a building block for NIS2 and DORA evidence about third parties.',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'Scope gegen die eigene Nutzung prüfen',
                    'detail' => 'Ist der konkrete Warehouse-, Storage- oder BI-Dienst in der genutzten Region im Bericht enthalten?',
                ],
                [
                    'title' => 'Umfeldparameter in Transferbewertungen',
                    'detail' => 'Angaben zu Betriebsstandorten, Support-Zugriff und Behördenanfragen direkt in TIA und Hosting-Entscheidung übernehmen.',
                ],
                [
                    'title' => 'Kundenkriterien in Backlog überführen',
                    'detail' => 'Aus den ergänzenden Kundenkriterien konkrete Tickets machen: Rollenmodell, Netzwerkbeschränkungen, Key-Management, Log-Auswertung.',
                ],
                [
                    'title' => 'Anbietervergleich strukturieren',
                    'detail' => 'C5-, ISO- und SOC-Nachweise in einer Matrix gegenüberstellen, statt einzelne Berichte isoliert zu lesen.',
                ],
                [
                    'title' => 'Evidence für deutsche Prüfer',
                    'detail' => 'C5-Berichte plus eigene Access- und Change-Nachweise sind im DE-Kontext eine gut akzeptierte Kombination.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Check scope against your actual usage',
                    'detail' => 'Is the specific warehouse, storage or BI service in the region you use included in the report?',
                ],
                [
                    'title' => 'Environment parameters in transfer assessments',
                    'detail' => 'Feed statements on operating locations, support access and authority requests directly into your TIA and hosting decision.',
                ],
                [
                    'title' => 'Turn customer criteria into backlog items',
                    'detail' => 'Turn complementary customer criteria into concrete tickets: role model, network restrictions, key management, log evaluation.',
                ],
                [
                    'title' => 'Structure provider comparison',
                    'detail' => 'Compare C5, ISO and SOC evidence in one matrix instead of reading individual reports in isolation.',
                ],
                [
                    'title' => 'Evidence for German auditors',
                    'detail' => 'C5 reports plus your own access and change evidence are a well-accepted combination in the German context.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Aktueller Typ-2-Bericht vorhanden?',
                    'detail' => 'Mit Prüfzeitraum, der die eigene Nutzung abdeckt.',
                ],
                [
                    'title' => 'Umfeldparameter gelesen?',
                    'detail' => 'Datenstandorte, Betriebsstandorte, Unterauftragnehmer und Rechtsraum notiert.',
                ],
                [
                    'title' => 'Zusatzkriterien im Scope?',
                    'detail' => 'Falls höherer Schutzbedarf besteht, prüfen, ob die Zusatzkriterien geprüft wurden.',
                ],
                [
                    'title' => 'Kundenkriterien zugewiesen?',
                    'detail' => 'Jede Kundenpflicht hat ein Team, ein Ticket und einen Nachweis.',
                ],
                [
                    'title' => 'Feststellungen nachverfolgt?',
                    'detail' => 'Abweichungen im Bericht bewertet und mit Kompensationen oder Risikoakzeptanz dokumentiert.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Current Type 2 report available?',
                    'detail' => 'With an examination period covering your usage.',
                ],
                [
                    'title' => 'Environment parameters read?',
                    'detail' => 'Data locations, operating locations, sub-contractors and jurisdiction noted.',
                ],
                [
                    'title' => 'Additional criteria in scope?',
                    'detail' => 'If protection needs are higher, check whether additional criteria were examined.',
                ],
                [
                    'title' => 'Customer criteria assigned?',
                    'detail' => 'Every customer duty has a team, a ticket and evidence.',
                ],
                [
                    'title' => 'Findings tracked?',
                    'detail' => 'Exceptions in the report assessed and documented with compensations or risk acceptance.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => '„C5-zertifiziert“ ohne Bericht',
                    'detail' => 'Marketingaussagen ersetzen kein Testat — immer den Prüfbericht anfordern.',
                ],
                [
                    'title' => 'Scope passt nicht zum Setup',
                    'detail' => 'Bericht für Dienst A in Region X, genutzt wird Dienst B in Region Y.',
                ],
                [
                    'title' => 'Kundenkriterien übersehen',
                    'detail' => 'Die geteilte Verantwortung wird stillschweigend beim Anbieter abgelegt — und bleibt real bei euch.',
                ],
                [
                    'title' => 'C5 als Datenschutznachweis',
                    'detail' => 'Sicherheitsprüfung ist kein Ersatz für Rechtsgrundlage, Transferbewertung oder Löschkonzept.',
                ],
            ],
            'en' => [
                [
                    'title' => '“C5 certified” without a report',
                    'detail' => 'Marketing claims do not replace an attestation — always request the actual report.',
                ],
                [
                    'title' => 'Scope does not match your setup',
                    'detail' => 'Report for service A in region X while you use service B in region Y.',
                ],
                [
                    'title' => 'Customer criteria overlooked',
                    'detail' => 'Shared responsibility is quietly assigned to the provider — and in reality stays with you.',
                ],
                [
                    'title' => 'C5 as data protection proof',
                    'detail' => 'A security examination does not replace legal basis, transfer assessment or a deletion concept.',
                ],
            ],
        ],
        'officialSources' => [
            [
                'label' => [
                    'de' => 'BSI — C5 (Kriterienkatalog Cloud Computing)',
                    'en' => 'BSI — C5 (Cloud Computing Compliance Criteria Catalogue)',
                ],
                'href' => 'https://www.bsi.bund.de/EN/Themen/Unternehmen-und-Organisationen/Informationen-und-Empfehlungen/Empfehlungen-nach-Angriffszielen/Cloud-Computing/Kriterienkatalog-C5/kriterienkatalog-c5_node.html',
            ],
            [
                'label' => [
                    'de' => 'BSI — C5:2020 Kriterienkatalog (PDF)',
                    'en' => 'BSI — C5:2020 criteria catalogue (PDF)',
                ],
                'href' => 'https://www.bsi.bund.de/SharedDocs/Downloads/EN/BSI/CloudComputing/ComplianceControlsCatalogue/2020/C5_2020.pdf?__blob=publicationFile&v=3',
            ],
        ],
        'relatedPlaybooks' => [
            'access-security-governance',
            'host-vs-cloud',
            'cloud-hosting',
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
            'de' => "Datenplattformen haben keinen Perimeter mehr: BI aus dem Homeoffice, Notebooks auf Laptops, Pipelines in der Cloud, Service-Accounts überall.\n\nZero Trust liefert dafür eine nützliche Denkfigur — jeder Zugriff wird pro Session anhand von Identität, Kontext und Policy entschieden und protokolliert. Für Warehouses heißt das: feingranulare Policies, kurzlebige Credentials und Telemetrie, die tatsächlich ausgewertet wird.",
            'en' => "Data platforms no longer have a perimeter: BI from home, notebooks on laptops, pipelines in the cloud, service accounts everywhere.\n\nZero trust offers a useful mental model — every access is decided per session based on identity, context and policy, and it is logged. For warehouses that means fine-grained policies, short-lived credentials and telemetry that is actually evaluated.",
        ],
        'appliesTo' => [
            'de' => "Freiwilliges Architekturmodell ohne Zertifizierung — anwendbar auf jede Organisation, in US-Behördenkontexten faktisch Vorgabe.\n\nFür Datenteams ist es besonders anschlussfähig, weil Warehouses ohnehin Identitäten, Rollen, Policies und Query-Logs mitbringen. Der Schritt ist die konsequente Nutzung, nicht ein neues Produkt.",
            'en' => "A voluntary architecture model without certification — applicable to any organisation, and in US federal contexts effectively expected.\n\nIt fits data teams particularly well because warehouses already bring identities, roles, policies and query logs. The step is to use them consistently, not to buy a new product.",
        ],
        'scopeNotes' => [
            'de' => [
                'Zero Trust ist ein Zielbild und Weg, kein Produkt, das man einkauft.',
                'SP 800-207 beschreibt Architekturprinzipien; SP 1800-35 liefert Umsetzungsbeispiele.',
                'Es ersetzt keine Compliance-Pflichten — es hilft, deren Security-Anforderungen zu erfüllen.',
                'Ohne Identitäts- und Asset-Inventar bleibt jede Zero-Trust-Initiative Theorie.',
            ],
            'en' => [
                'Zero trust is a target state and a journey, not a product you buy.',
                'SP 800-207 describes architecture principles; SP 1800-35 provides implementation examples.',
                'It does not replace compliance obligations — it helps meet their security requirements.',
                'Without an identity and asset inventory every zero-trust initiative stays theory.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Alle Daten und Dienste sind Ressourcen',
                    'detail' => 'Warehouse-Schemas, Dashboards, APIs, Orchestrator und Notebook-Umgebungen zählen einzeln — nicht als eine große „interne Zone“.',
                    'ref' => 'SP 800-207, Tenet 1',
                ],
                [
                    'title' => 'Kommunikation immer absichern',
                    'detail' => 'Verschlüsselung und Authentisierung gelten unabhängig vom Netzstandort. „Im internen Netz“ ist kein Sicherheitsattribut.',
                    'ref' => 'Tenet 2',
                ],
                [
                    'title' => 'Zugriff pro Session',
                    'detail' => 'Rechte werden je Session gewährt, minimal und zeitlich begrenzt. Für Datenzugriffe heißt das kurzlebige Tokens statt Dauer-Credentials.',
                    'ref' => 'Tenet 3',
                ],
                [
                    'title' => 'Dynamische Policy',
                    'detail' => 'Entscheidungen berücksichtigen Identität, Gerätezustand, Sensitivität der Daten und Verhaltenssignale — nicht nur Gruppenmitgliedschaft.',
                    'ref' => 'Tenet 4',
                ],
                [
                    'title' => 'Integrität der Assets überwachen',
                    'detail' => 'Kein Gerät und kein Service ist per se vertrauenswürdig; Zustand und Patchlevel fließen in die Entscheidung ein.',
                    'ref' => 'Tenet 5',
                ],
                [
                    'title' => 'Authentisierung und Autorisierung vor jedem Zugriff',
                    'detail' => 'Dynamisch, wiederholt und protokolliert. Policy Decision Point und Policy Enforcement Point sind die tragenden Komponenten.',
                    'ref' => 'Tenet 6',
                ],
                [
                    'title' => 'Telemetrie zur Verbesserung nutzen',
                    'detail' => 'Logs und Signale werden gesammelt, um Policies zu schärfen. Ohne Rückkopplung entsteht nur Datenhalde.',
                    'ref' => 'Tenet 7',
                ],
                [
                    'title' => 'Migration in Schritten',
                    'detail' => 'NIST beschreibt Zero Trust ausdrücklich als iterative Reise mit Koexistenz klassischer Perimeter — beginnt bei den sensibelsten Datenpfaden.',
                    'ref' => 'SP 800-207, Kapitel 7',
                ],
            ],
            'en' => [
                [
                    'title' => 'All data sources and services are resources',
                    'detail' => 'Warehouse schemas, dashboards, APIs, orchestrator and notebook environments count individually — not as one large “internal zone”.',
                    'ref' => 'SP 800-207, tenet 1',
                ],
                [
                    'title' => 'Secure all communication',
                    'detail' => 'Encryption and authentication apply regardless of network location. “On the internal network” is not a security attribute.',
                    'ref' => 'Tenet 2',
                ],
                [
                    'title' => 'Per-session access',
                    'detail' => 'Access is granted per session, minimally and time-bound. For data access that means short-lived tokens instead of permanent credentials.',
                    'ref' => 'Tenet 3',
                ],
                [
                    'title' => 'Dynamic policy',
                    'detail' => 'Decisions take identity, device posture, data sensitivity and behavioural signals into account — not just group membership.',
                    'ref' => 'Tenet 4',
                ],
                [
                    'title' => 'Monitor asset integrity',
                    'detail' => 'No device and no service is inherently trusted; posture and patch level feed the decision.',
                    'ref' => 'Tenet 5',
                ],
                [
                    'title' => 'Authenticate and authorise before every access',
                    'detail' => 'Dynamically, repeatedly and logged. Policy decision point and policy enforcement point are the load-bearing components.',
                    'ref' => 'Tenet 6',
                ],
                [
                    'title' => 'Use telemetry to improve',
                    'detail' => 'Logs and signals are collected to sharpen policies. Without a feedback loop you only build a data dump.',
                    'ref' => 'Tenet 7',
                ],
                [
                    'title' => 'Migrate in steps',
                    'detail' => 'NIST explicitly describes zero trust as an iterative journey coexisting with classic perimeters — start with the most sensitive data paths.',
                    'ref' => 'SP 800-207, section 7',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'Identitätsbasierter Datenzugriff',
                    'detail' => 'SSO/MFA für Warehouse, BI und Orchestrator; personenbezogene Identitäten statt geteilter technischer Nutzer.',
                ],
                [
                    'title' => 'Feingranulare Policies im Warehouse',
                    'detail' => 'Row Access Policies, Column Masking und Objekt-Grants als Enforcement-Punkt — nicht Filter im Dashboard.',
                ],
                [
                    'title' => 'Kurzlebige Credentials',
                    'detail' => 'Key-Pair-Rotation, OAuth-Token und Just-in-Time-Rechte statt dauerhafter Passwörter in Konfigurationsdateien.',
                ],
                [
                    'title' => 'Segmentierung für Pipelines',
                    'detail' => 'Orchestrator, Staging und Produktion getrennt, mit eigenen Rollen und Netzwerkregeln — ein kompromittierter Job soll nicht alles erreichen.',
                ],
                [
                    'title' => 'Telemetrie aus Query-Logs',
                    'detail' => 'Zugriffs- und Query-Historie in Monitoring speisen: ungewöhnliche Exports, neue Verbindungen, Rechteänderungen.',
                ],
                [
                    'title' => 'Service-Accounts als erste Klasse',
                    'detail' => 'Auch technische Identitäten brauchen Owner, Least Privilege, Rotation und Review — sie sind oft der größte blinde Fleck.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Identity-based data access',
                    'detail' => 'SSO/MFA for warehouse, BI and orchestrator; individual identities instead of shared technical users.',
                ],
                [
                    'title' => 'Fine-grained policies in the warehouse',
                    'detail' => 'Row access policies, column masking and object grants as the enforcement point — not filters in the dashboard.',
                ],
                [
                    'title' => 'Short-lived credentials',
                    'detail' => 'Key pair rotation, OAuth tokens and just-in-time rights instead of permanent passwords in config files.',
                ],
                [
                    'title' => 'Segmentation for pipelines',
                    'detail' => 'Separate orchestrator, staging and production with their own roles and network rules — a compromised job should not reach everything.',
                ],
                [
                    'title' => 'Telemetry from query logs',
                    'detail' => 'Feed access and query history into monitoring: unusual exports, new connections, permission changes.',
                ],
                [
                    'title' => 'Service accounts as first-class citizens',
                    'detail' => 'Technical identities also need owners, least privilege, rotation and review — they are often the biggest blind spot.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Inventar der Ressourcen und Identitäten?',
                    'detail' => 'Datasets, Dienste, Service-Accounts und Integrationen mit Owner erfasst.',
                ],
                [
                    'title' => 'MFA überall, auch für Admins?',
                    'detail' => 'Keine Ausnahmen für Notfallzugänge ohne Break-Glass-Prozess.',
                ],
                [
                    'title' => 'Standing Privileges reduziert?',
                    'detail' => 'Dauerhafte Adminrechte durch befristete oder genehmigungspflichtige Rechte ersetzt.',
                ],
                [
                    'title' => 'Policy-Entscheidungen protokolliert?',
                    'detail' => 'Zugriffsentscheidungen und Ablehnungen sind auswertbar, nicht nur erfolgreiche Logins.',
                ],
                [
                    'title' => 'Telemetrie wird genutzt?',
                    'detail' => 'Mindestens ein regelmäßiger Report oder Alert basiert auf Zugriffsdaten.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Inventory of resources and identities?',
                    'detail' => 'Datasets, services, service accounts and integrations captured with owners.',
                ],
                [
                    'title' => 'MFA everywhere, including admins?',
                    'detail' => 'No exceptions for emergency access without a break-glass process.',
                ],
                [
                    'title' => 'Standing privileges reduced?',
                    'detail' => 'Permanent admin rights replaced by time-bound or approval-based rights.',
                ],
                [
                    'title' => 'Policy decisions logged?',
                    'detail' => 'Access decisions and denials are analysable, not only successful logins.',
                ],
                [
                    'title' => 'Telemetry is used?',
                    'detail' => 'At least one recurring report or alert is based on access data.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => 'Zero Trust als Produkt kaufen',
                    'detail' => 'Kein Tool liefert das Modell; ohne Inventar, Policies und Prozesse bleibt es ein Label.',
                ],
                [
                    'title' => 'VPN mit neuem Namen',
                    'detail' => 'Netzzugang zu ersetzen ist ein Anfang, aber Datenzugriffe brauchen eigene, feingranulare Entscheidungen.',
                ],
                [
                    'title' => 'Service-Accounts ausgenommen',
                    'detail' => 'Technische Nutzer mit statischen Passwörtern und breiten Rechten heben das Modell praktisch auf.',
                ],
                [
                    'title' => 'Telemetrie ohne Auswertung',
                    'detail' => 'Logs zu sammeln erzeugt Kosten, aber keine Sicherheit, wenn niemand hinsieht.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Buying zero trust as a product',
                    'detail' => 'No tool delivers the model; without inventory, policies and processes it stays a label.',
                ],
                [
                    'title' => 'VPN with a new name',
                    'detail' => 'Replacing network access is a start, but data access needs its own fine-grained decisions.',
                ],
                [
                    'title' => 'Service accounts exempted',
                    'detail' => 'Technical users with static passwords and broad rights practically cancel the model.',
                ],
                [
                    'title' => 'Telemetry without evaluation',
                    'detail' => 'Collecting logs creates cost but no security if nobody looks at them.',
                ],
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
            [
                'label' => [
                    'de' => 'NIST SP 1800-35 — Implementing a Zero Trust Architecture',
                    'en' => 'NIST SP 1800-35 — Implementing a Zero Trust Architecture',
                ],
                'href' => 'https://csrc.nist.gov/pubs/sp/1800/35/final',
            ],
        ],
        'relatedPlaybooks' => [
            'access-security-governance',
            'host-vs-cloud',
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
            'de' => "Sobald Analytics-Teams Modelle für Scoring, Priorisierung, Textgenerierung oder Assistenten einsetzen, sind sie im Anwendungsbereich einer Produktregulierung — mit Dokumentations-, Daten- und Aufsichtspflichten.\n\nDer AI Act macht dabei genau das zum Thema, was in Datenplattformen ohnehin wehtut: Herkunft und Qualität der Trainingsdaten, Nachvollziehbarkeit, Logging, menschliche Aufsicht und klare Rollen. Wer Lineage und Data Quality im Griff hat, hat den halben Weg schon gemacht.",
            'en' => "As soon as analytics teams use models for scoring, prioritisation, text generation or assistants, they fall under a product regulation — with documentation, data and oversight duties.\n\nThe AI Act puts exactly those topics on the table that already hurt in data platforms: provenance and quality of training data, traceability, logging, human oversight and clear roles. If you have lineage and data quality under control, you are halfway there.",
        ],
        'appliesTo' => [
            'de' => "Anbieter, Betreiber, Importeure und Händler von KI-Systemen mit EU-Bezug — auch wenn das Modell aus einem Drittland stammt, sofern die Ausgabe in der EU genutzt wird.\n\nDie meisten Datenteams sind Betreiber (Deployer) fremder Systeme. Wer ein Modell jedoch wesentlich verändert, umbenennt oder unter eigenem Namen anbietet, kann selbst zum Anbieter werden — mit deutlich mehr Pflichten.",
            'en' => "Providers, deployers, importers and distributors of AI systems with an EU nexus — even if the model comes from a third country, as long as its output is used in the EU.\n\nMost data teams are deployers of someone else’s system. But if you substantially modify, rebrand or offer a model under your own name, you can become a provider yourself — with substantially more duties.",
        ],
        'scopeNotes' => [
            'de' => [
                'Die Pflichten gelten gestuft nach Risikoklasse — nicht jedes Modell im Warehouse ist Hochrisiko.',
                'Der AI Act ersetzt die DSGVO nicht; für personenbezogene Trainings- und Eingabedaten gilt beides.',
                'Die Anwendung erfolgt zeitlich gestaffelt — Verbote und AI-Kompetenz früher, Hochrisikopflichten später.',
                'Rolle bestimmt Pflichtenumfang: Anbieter, Betreiber, Importeur oder Händler.',
            ],
            'en' => [
                'Duties are tiered by risk class — not every model in the warehouse is high-risk.',
                'The AI Act does not replace the GDPR; for personal training and input data both apply.',
                'Application is staggered over time — prohibitions and AI literacy earlier, high-risk duties later.',
                'Your role determines the extent of duties: provider, deployer, importer or distributor.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Risikobasierter Ansatz',
                    'detail' => 'Verbotene Praktiken, Hochrisiko-Systeme, Transparenzfälle und minimales Risiko werden unterschiedlich behandelt. Erste Aufgabe ist immer die Einordnung des Use-Cases.',
                    'ref' => 'Art. 5, Art. 6, Anhang III',
                ],
                [
                    'title' => 'AI-Kompetenz im Team',
                    'detail' => 'Organisationen müssen für ausreichende Kompetenz der Personen sorgen, die KI betreiben oder nutzen — inklusive Grenzen und typischer Fehlerbilder.',
                    'ref' => 'Art. 4',
                ],
                [
                    'title' => 'Risikomanagementsystem',
                    'detail' => 'Für Hochrisiko-Systeme ist ein kontinuierlicher Risikoprozess über den Lebenszyklus vorgesehen, nicht eine Prüfung vor dem Go-live.',
                    'ref' => 'Art. 9',
                ],
                [
                    'title' => 'Daten und Data Governance',
                    'detail' => 'Trainings-, Validierungs- und Testdaten müssen relevant, repräsentativ und so weit möglich fehlerfrei sein; Herkunft, Erhebung und Bias-Prüfung sind zu dokumentieren.',
                    'ref' => 'Art. 10',
                ],
                [
                    'title' => 'Technische Dokumentation',
                    'detail' => 'Systembeschreibung, Architektur, Datenquellen, Metriken und Grenzen gehören in eine nachvollziehbare Dokumentation nach Anhang IV.',
                    'ref' => 'Art. 11, Anhang IV',
                ],
                [
                    'title' => 'Protokollierung',
                    'detail' => 'Hochrisiko-Systeme müssen Ereignisse automatisch protokollieren, damit Betrieb und Vorfälle rückverfolgbar bleiben.',
                    'ref' => 'Art. 12',
                ],
                [
                    'title' => 'Menschliche Aufsicht',
                    'detail' => 'Die Aufsicht muss wirksam sein: Eingriffsmöglichkeit, verständliche Ausgaben und Bewusstsein für Automation Bias.',
                    'ref' => 'Art. 14',
                ],
                [
                    'title' => 'Transparenz und GPAI-Pflichten',
                    'detail' => 'Chatbots, Emotionserkennung und synthetische Inhalte brauchen Kennzeichnung; Anbieter von Allzweckmodellen haben eigene Dokumentations- und Urheberrechtspflichten.',
                    'ref' => 'Art. 50, Art. 53',
                ],
            ],
            'en' => [
                [
                    'title' => 'Risk-based approach',
                    'detail' => 'Prohibited practices, high-risk systems, transparency cases and minimal risk are treated differently. The first task is always classifying the use case.',
                    'ref' => 'Art. 5, Art. 6, Annex III',
                ],
                [
                    'title' => 'AI literacy in the team',
                    'detail' => 'Organisations must ensure sufficient competence of the people operating or using AI — including its limits and typical failure modes.',
                    'ref' => 'Art. 4',
                ],
                [
                    'title' => 'Risk management system',
                    'detail' => 'High-risk systems require a continuous risk process across the lifecycle, not a single check before go-live.',
                    'ref' => 'Art. 9',
                ],
                [
                    'title' => 'Data and data governance',
                    'detail' => 'Training, validation and test data must be relevant, representative and as far as possible error-free; provenance, collection and bias examination must be documented.',
                    'ref' => 'Art. 10',
                ],
                [
                    'title' => 'Technical documentation',
                    'detail' => 'System description, architecture, data sources, metrics and limitations belong in traceable documentation per Annex IV.',
                    'ref' => 'Art. 11, Annex IV',
                ],
                [
                    'title' => 'Record-keeping',
                    'detail' => 'High-risk systems must automatically log events so that operations and incidents stay traceable.',
                    'ref' => 'Art. 12',
                ],
                [
                    'title' => 'Human oversight',
                    'detail' => 'Oversight must be effective: ability to intervene, intelligible outputs and awareness of automation bias.',
                    'ref' => 'Art. 14',
                ],
                [
                    'title' => 'Transparency and GPAI duties',
                    'detail' => 'Chatbots, emotion recognition and synthetic content need disclosure; providers of general-purpose models have their own documentation and copyright duties.',
                    'ref' => 'Art. 50, Art. 53',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'Use-Case-Register mit Rolle und Risikoklasse',
                    'detail' => 'Jeder KI-Anwendungsfall bekommt Owner, Rolle (Anbieter/Betreiber), Risikoeinordnung und Datenquellen. Ohne Register ist keine Pflicht zuordenbar.',
                ],
                [
                    'title' => 'Trainings- und Eingabedaten mit Lineage',
                    'detail' => 'Herkunft, Filter, Stichproben und Qualitätsmetriken dokumentieren — idealerweise als Metadaten am Dataset, nicht in einer Präsentation.',
                ],
                [
                    'title' => 'Protokollierung von Läufen und Prompts',
                    'detail' => 'Eingaben, Ausgaben, Modellversion und Entscheidungskontext so protokollieren, dass Nachvollziehbarkeit möglich ist — und die Logs selbst datenschutzkonform bleiben.',
                ],
                [
                    'title' => 'PII vor dem Modell trennen',
                    'detail' => 'Feature-Auswahl, Masking und Pseudonymisierung reduzieren Datenschutz- und Bias-Risiken gleichzeitig.',
                ],
                [
                    'title' => 'Aufsicht und Eskalation im Prozess',
                    'detail' => 'Wer prüft Ausgaben, wer darf abschalten, wie wird eskaliert? Diese Rollen gehören in den Betriebsplan, nicht in ein Konzeptpapier.',
                ],
                [
                    'title' => 'Vendor- und GPAI-Dokumentation einsammeln',
                    'detail' => 'Von Modell- und SaaS-Anbietern die Systemdokumentation, Nutzungsgrenzen und Trainingshinweise einfordern und aufbewahren.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Use-case register with role and risk class',
                    'detail' => 'Every AI use case gets an owner, role (provider/deployer), risk classification and data sources. Without a register no duty can be assigned.',
                ],
                [
                    'title' => 'Training and input data with lineage',
                    'detail' => 'Document provenance, filters, sampling and quality metrics — ideally as metadata on the dataset, not in a slide deck.',
                ],
                [
                    'title' => 'Logging of runs and prompts',
                    'detail' => 'Log inputs, outputs, model version and decision context so that traceability is possible — while keeping the logs themselves privacy-compliant.',
                ],
                [
                    'title' => 'Separate PII before the model',
                    'detail' => 'Feature selection, masking and pseudonymisation reduce privacy and bias risk at the same time.',
                ],
                [
                    'title' => 'Oversight and escalation in the process',
                    'detail' => 'Who reviews outputs, who may switch it off, how is it escalated? These roles belong in the operating plan, not in a concept paper.',
                ],
                [
                    'title' => 'Collect vendor and GPAI documentation',
                    'detail' => 'Request and retain system documentation, usage limits and training notes from model and SaaS providers.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'KI-Inventar vollständig?',
                    'detail' => 'Inklusive Copilot-Funktionen in BI-Tools, Skripten mit Modell-API und Pilotprojekten.',
                ],
                [
                    'title' => 'Rolle und Risikoklasse je Use-Case?',
                    'detail' => 'Dokumentiert und mit Legal abgestimmt, besonders bei HR-, Kredit- und Zugangsentscheidungen.',
                ],
                [
                    'title' => 'Datenherkunft belegbar?',
                    'detail' => 'Quellen, Rechte, Filter und Qualitätsprüfungen für Trainings- und Evaluierungsdaten nachvollziehbar.',
                ],
                [
                    'title' => 'Logs vorhanden und aufbewahrt?',
                    'detail' => 'Mit definierter Aufbewahrung und Zugriffsbeschränkung.',
                ],
                [
                    'title' => 'Menschliche Aufsicht definiert?',
                    'detail' => 'Namentlich benannte Rollen, Eingriffswege und Abschaltkriterien.',
                ],
                [
                    'title' => 'Anbieterunterlagen eingesammelt?',
                    'detail' => 'Modelldokumentation, Nutzungsbedingungen und Änderungshinweise archiviert.',
                ],
            ],
            'en' => [
                [
                    'title' => 'AI inventory complete?',
                    'detail' => 'Including copilot features in BI tools, scripts calling model APIs and pilot projects.',
                ],
                [
                    'title' => 'Role and risk class per use case?',
                    'detail' => 'Documented and aligned with legal, especially for HR, credit and access decisions.',
                ],
                [
                    'title' => 'Data provenance evidenced?',
                    'detail' => 'Sources, rights, filters and quality checks for training and evaluation data traceable.',
                ],
                [
                    'title' => 'Logs available and retained?',
                    'detail' => 'With defined retention and restricted access.',
                ],
                [
                    'title' => 'Human oversight defined?',
                    'detail' => 'Named roles, intervention paths and shutdown criteria.',
                ],
                [
                    'title' => 'Provider documentation collected?',
                    'detail' => 'Model documentation, terms of use and change notices archived.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => '„Wir nutzen nur ein fremdes Modell“',
                    'detail' => 'Auch Betreiber haben Pflichten — und wer ein System umbenennt oder wesentlich ändert, wird schnell selbst Anbieter.',
                ],
                [
                    'title' => 'DPIA mit AI-Act-Konformität verwechselt',
                    'detail' => 'Datenschutz-Folgenabschätzung und KI-Konformitätsanforderungen sind unterschiedliche Verfahren mit unterschiedlichen Inhalten.',
                ],
                [
                    'title' => 'Schatten-KI im BI-Tool',
                    'detail' => 'Eingebaute Assistenzfunktionen und Browser-Plugins erzeugen KI-Nutzung, die in keinem Inventar auftaucht.',
                ],
                [
                    'title' => 'Keine Protokollierung',
                    'detail' => 'Ohne Logs sind Vorfälle nicht rekonstruierbar und Aufsicht nicht belegbar.',
                ],
                [
                    'title' => 'Trainingsdaten ohne Rechteklärung',
                    'detail' => 'Interne Dokumente, Kundendaten und Web-Inhalte haben unterschiedliche Nutzungsgrenzen — Herkunft klären, bevor trainiert wird.',
                ],
            ],
            'en' => [
                [
                    'title' => '“We only use someone else’s model”',
                    'detail' => 'Deployers have duties too — and rebranding or substantially modifying a system can quickly make you a provider.',
                ],
                [
                    'title' => 'DPIA confused with AI Act conformity',
                    'detail' => 'A data protection impact assessment and AI conformity requirements are different procedures with different content.',
                ],
                [
                    'title' => 'Shadow AI in the BI tool',
                    'detail' => 'Built-in assistant features and browser plugins create AI usage that appears in no inventory.',
                ],
                [
                    'title' => 'No logging',
                    'detail' => 'Without logs, incidents cannot be reconstructed and oversight cannot be evidenced.',
                ],
                [
                    'title' => 'Training data without rights clarification',
                    'detail' => 'Internal documents, customer data and web content have different usage limits — clarify provenance before training.',
                ],
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
            [
                'label' => [
                    'de' => 'EU-Kommission — AI Office',
                    'en' => 'European Commission — AI Office',
                ],
                'href' => 'https://digital-strategy.ec.europa.eu/en/policies/ai-office',
            ],
        ],
        'relatedPlaybooks' => [
            'ai-gov',
            'ai-agents',
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
            'de' => "Wer KI-Governance aufbauen will, braucht mehr als eine Richtlinie: einen Weg von der Absicht über die Messung bis zum Betrieb. Genau das liefert das AI RMF in vier Funktionen.\n\nFür Datenteams ist es das pragmatischere Gegenstück zum AI Act: freiwillig, ohne Zertifizierung, aber mit konkreten Fragen zu Kontext, Metriken, Tests und Überwachung. Es passt gut als Arbeitsstruktur, um AI-Act- oder ISO-42001-Anforderungen später zu bedienen.",
            'en' => "Building AI governance takes more than a policy: it needs a path from intent through measurement to operations. That is exactly what the AI RMF provides in four functions.\n\nFor data teams it is the more pragmatic counterpart to the AI Act: voluntary, without certification, but with concrete questions on context, metrics, testing and monitoring. It works well as a working structure to serve AI Act or ISO 42001 requirements later.",
        ],
        'appliesTo' => [
            'de' => "Freiwillig für alle Organisationen, die KI entwickeln, beschaffen oder betreiben — sektor- und technologieneutral formuliert.\n\nIn US-Kontexten wird es häufig als Referenz erwartet; international eignet es sich als gemeinsame Sprache zwischen Data-, Security- und Fachteams.",
            'en' => "Voluntary for any organisation that develops, procures or operates AI — written to be sector- and technology-neutral.\n\nIn US contexts it is frequently expected as a reference; internationally it works as a shared language between data, security and business teams.",
        ],
        'scopeNotes' => [
            'de' => [
                'Das Framework ist freiwillig und nicht zertifizierbar — es erzeugt keine Rechtskonformität.',
                'Es adressiert sozio-technische Risiken, nicht nur Modellgenauigkeit.',
                'Der Generative-AI-Profile-Anhang ergänzt spezifische Risiken für Sprachmodelle.',
                'Ohne belastbare Datenqualität bleiben Messungen im Measure-Teil wenig aussagekräftig.',
            ],
            'en' => [
                'The framework is voluntary and not certifiable — it does not create legal compliance.',
                'It addresses socio-technical risk, not only model accuracy.',
                'The generative AI profile supplements specific risks for language models.',
                'Without solid data quality, measurements in the Measure function stay weak.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Govern — Rahmen und Verantwortung',
                    'detail' => 'Richtlinien, Rollen, Ressourcen und Kultur festlegen. Diese Funktion wirkt in alle anderen hinein und ist der häufigste Schwachpunkt.',
                    'ref' => 'AI RMF 1.0, Govern',
                ],
                [
                    'title' => 'Map — Kontext und Zweck verstehen',
                    'detail' => 'Einsatzzweck, Betroffene, Annahmen, Datenquellen und Grenzen erfassen. Viele KI-Fehler sind Kontextfehler, keine Modellfehler.',
                    'ref' => 'Map',
                ],
                [
                    'title' => 'Measure — Metriken und Tests',
                    'detail' => 'Trustworthiness quantifizieren, wo möglich: Genauigkeit, Robustheit, Bias, Erklärbarkeit, Datenschutz. Test, Evaluation, Verification und Validation gehören dazu.',
                    'ref' => 'Measure',
                ],
                [
                    'title' => 'Manage — Priorisieren und betreiben',
                    'detail' => 'Risiken behandeln, Restrisiken akzeptieren, überwachen und auf Vorfälle reagieren — inklusive Abschalt- und Rückfallpfaden.',
                    'ref' => 'Manage',
                ],
                [
                    'title' => 'Merkmale vertrauenswürdiger KI',
                    'detail' => 'Valid und verlässlich, sicher, resilient, nachvollziehbar und transparent, erklärbar, datenschutzfördernd, fair mit gemanagtem Bias — als Prüfraster nutzbar.',
                    'ref' => 'AI RMF, Kapitel 3',
                ],
                [
                    'title' => 'Lebenszyklus statt Projektphase',
                    'detail' => 'Risiken verschieben sich von Design über Deployment zu Betrieb und Außerbetriebnahme. Bewertungen müssen wiederholt werden.',
                ],
                [
                    'title' => 'Playbook und Profile nutzen',
                    'detail' => 'Das AI RMF Playbook liefert konkrete Vorschläge je Unterkategorie; das Generative AI Profile ergänzt GenAI-spezifische Risiken.',
                    'ref' => 'NIST AI 600-1',
                ],
            ],
            'en' => [
                [
                    'title' => 'Govern — frame and accountability',
                    'detail' => 'Set policies, roles, resources and culture. This function feeds into all others and is the most common weak spot.',
                    'ref' => 'AI RMF 1.0, Govern',
                ],
                [
                    'title' => 'Map — understand context and purpose',
                    'detail' => 'Capture intended use, affected people, assumptions, data sources and limits. Many AI failures are context failures, not model failures.',
                    'ref' => 'Map',
                ],
                [
                    'title' => 'Measure — metrics and testing',
                    'detail' => 'Quantify trustworthiness where possible: accuracy, robustness, bias, explainability, privacy. Test, evaluation, verification and validation are part of it.',
                    'ref' => 'Measure',
                ],
                [
                    'title' => 'Manage — prioritise and operate',
                    'detail' => 'Treat risks, accept residual risk, monitor and respond to incidents — including shutdown and fallback paths.',
                    'ref' => 'Manage',
                ],
                [
                    'title' => 'Characteristics of trustworthy AI',
                    'detail' => 'Valid and reliable, safe, secure and resilient, accountable and transparent, explainable, privacy-enhanced, fair with managed bias — usable as a review grid.',
                    'ref' => 'AI RMF, section 3',
                ],
                [
                    'title' => 'Lifecycle instead of project phase',
                    'detail' => 'Risks shift from design through deployment to operations and decommissioning. Assessments must be repeated.',
                ],
                [
                    'title' => 'Use the playbook and profiles',
                    'detail' => 'The AI RMF Playbook offers concrete suggestions per subcategory; the Generative AI Profile adds GenAI-specific risks.',
                    'ref' => 'NIST AI 600-1',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'Modell- und Use-Case-Register',
                    'detail' => 'Kontext, Zweck, Datenquellen, Owner und Version an einem Ort — die Map-Funktion braucht ein Inventar, kein Wiki-Fragment.',
                ],
                [
                    'title' => 'Evaluations-Harness statt Ad-hoc-Tests',
                    'detail' => 'Wiederholbare Test-Sets, definierte Metriken und Schwellenwerte, die im Deployment-Prozess geprüft werden.',
                ],
                [
                    'title' => 'Monitoring und Drift',
                    'detail' => 'Eingabeverteilungen, Ausgabequalität und Nutzungsverhalten beobachten; Schwellenwerte für Nachtraining oder Abschaltung festlegen.',
                ],
                [
                    'title' => 'Datenqualität als Voraussetzung',
                    'detail' => 'Tests auf Vollständigkeit, Aktualität und Konsistenz upstream sind Voraussetzung dafür, dass Modellmetriken überhaupt aussagekräftig sind.',
                ],
                [
                    'title' => 'Vorfall- und Rückfallpfade',
                    'detail' => 'Wer wird informiert, was wird abgeschaltet, welcher manuelle Prozess greift? Vorab beschreiben, nicht während des Vorfalls erfinden.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Model and use-case register',
                    'detail' => 'Context, purpose, data sources, owner and version in one place — the Map function needs an inventory, not a wiki fragment.',
                ],
                [
                    'title' => 'Evaluation harness instead of ad-hoc tests',
                    'detail' => 'Repeatable test sets, defined metrics and thresholds that are checked in the deployment process.',
                ],
                [
                    'title' => 'Monitoring and drift',
                    'detail' => 'Observe input distributions, output quality and usage behaviour; define thresholds for retraining or shutdown.',
                ],
                [
                    'title' => 'Data quality as a precondition',
                    'detail' => 'Upstream tests for completeness, timeliness and consistency are the precondition for model metrics to mean anything.',
                ],
                [
                    'title' => 'Incident and fallback paths',
                    'detail' => 'Who gets informed, what gets switched off, which manual process takes over? Describe it in advance instead of inventing it during the incident.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'KI-Inventar mit Kontext?',
                    'detail' => 'Zweck, Betroffene, Datenquellen und Annahmen je Use-Case dokumentiert.',
                ],
                [
                    'title' => 'Metriken und Schwellen definiert?',
                    'detail' => 'Mit Zielwerten, die eine Freigabe blockieren können — nicht nur als Reporting.',
                ],
                [
                    'title' => 'Bias- und Robustheitstests durchgeführt?',
                    'detail' => 'Ergebnisse dokumentiert, inklusive Grenzen der Aussagekraft.',
                ],
                [
                    'title' => 'Vorfallpfad geübt?',
                    'detail' => 'Mindestens ein Durchlauf inklusive Abschaltung und Kommunikation.',
                ],
                [
                    'title' => 'Rollen und Eskalation benannt?',
                    'detail' => 'Owner, fachliche Aufsicht und Entscheidungsbefugnis eindeutig.',
                ],
                [
                    'title' => 'GenAI-Risiken betrachtet?',
                    'detail' => 'Halluzination, Datenabfluss, Prompt Injection und Urheberrechtsfragen bewertet.',
                ],
            ],
            'en' => [
                [
                    'title' => 'AI inventory with context?',
                    'detail' => 'Purpose, affected people, data sources and assumptions documented per use case.',
                ],
                [
                    'title' => 'Metrics and thresholds defined?',
                    'detail' => 'With target values that can block a release — not only for reporting.',
                ],
                [
                    'title' => 'Bias and robustness tests performed?',
                    'detail' => 'Results documented, including the limits of what they show.',
                ],
                [
                    'title' => 'Incident path rehearsed?',
                    'detail' => 'At least one dry run including shutdown and communication.',
                ],
                [
                    'title' => 'Roles and escalation named?',
                    'detail' => 'Owner, business oversight and decision authority unambiguous.',
                ],
                [
                    'title' => 'GenAI risks considered?',
                    'detail' => 'Hallucination, data leakage, prompt injection and copyright questions assessed.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => 'Framework als Checkliste',
                    'detail' => 'Die Funktionen abzuhaken, ohne zu messen, erzeugt Dokumente statt Risikoreduktion.',
                ],
                [
                    'title' => 'Nur Genauigkeit gemessen',
                    'detail' => 'Robustheit, Bias, Datenschutz und Erklärbarkeit entscheiden im Betrieb oft mehr als ein Prozentpunkt Accuracy.',
                ],
                [
                    'title' => 'Kein Owner für Modelle',
                    'detail' => 'Ohne fachliche Verantwortung verwaisen Modelle und laufen weiter, obwohl der Kontext sich geändert hat.',
                ],
                [
                    'title' => 'AI RMF als Rechtsnachweis',
                    'detail' => 'Es ist freiwillig — AI-Act-Pflichten oder DSGVO-Anforderungen werden damit nicht erfüllt.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Framework as a checklist',
                    'detail' => 'Ticking off the functions without measuring produces documents instead of risk reduction.',
                ],
                [
                    'title' => 'Only accuracy measured',
                    'detail' => 'Robustness, bias, privacy and explainability often matter more in operations than a percentage point of accuracy.',
                ],
                [
                    'title' => 'No owner for models',
                    'detail' => 'Without business ownership, models are orphaned and keep running although the context has changed.',
                ],
                [
                    'title' => 'AI RMF as legal evidence',
                    'detail' => 'It is voluntary — it does not satisfy AI Act duties or GDPR requirements.',
                ],
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
            [
                'label' => [
                    'de' => 'NIST — Generative AI Profile (AI 600-1, PDF)',
                    'en' => 'NIST — Generative AI Profile (AI 600-1, PDF)',
                ],
                'href' => 'https://nvlpubs.nist.gov/nistpubs/ai/NIST.AI.600-1.pdf',
            ],
        ],
        'relatedPlaybooks' => [
            'ai-gov',
            'ai-agents',
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
            'de' => "ISO/IEC 42001 überträgt die bekannte Managementsystem-Logik auf KI: Scope, Politik, Rollen, Risiko- und Auswirkungsbewertung, Lebenszyklus-Controls, Audits, Verbesserung.\n\nFür Organisationen mit ISO 27001 ist das die günstigste Route zu belastbarer AI-Governance: gleiche Struktur, gleiche Auditlogik, erweiterte Inhalte. Zertifizierbar zu sein hilft zusätzlich in Ausschreibungen und Vendor-Fragebögen.",
            'en' => "ISO/IEC 42001 transfers the familiar management system logic to AI: scope, policy, roles, risk and impact assessment, lifecycle controls, audits, improvement.\n\nFor organisations that already run ISO 27001, this is the cheapest route to durable AI governance: same structure, same audit logic, extended content. Being certifiable also helps in tenders and vendor questionnaires.",
        ],
        'appliesTo' => [
            'de' => "Organisationen, die KI-Systeme entwickeln, bereitstellen oder nutzen und dafür ein prüfbares Managementsystem wollen — unabhängig von Größe und Branche.\n\nBesonders sinnvoll, wenn KI-Nutzung über Experimente hinausgeht und mehrere Teams, Vendoren oder Kundenzusagen betroffen sind.",
            'en' => "Organisations that develop, provide or use AI systems and want an auditable management system for it — regardless of size or industry.\n\nParticularly useful once AI usage goes beyond experiments and involves several teams, vendors or customer commitments.",
        ],
        'scopeNotes' => [
            'de' => [
                '42001 ist ein Managementsystem, keine Prüfung einzelner Modelle.',
                'Eine Zertifizierung belegt keine AI-Act-Konformität, kann aber viele Nachweise vorbereiten.',
                'Der Standard baut auf der harmonisierten Struktur auf und lässt sich mit ISO 27001 und 27701 integrieren.',
                'Der Scope entscheidet: eingebettete KI in SaaS und BI-Tools gerne mitdenken.',
            ],
            'en' => [
                '42001 is a management system, not an examination of individual models.',
                'Certification does not prove AI Act conformity but can prepare much of the evidence.',
                'The standard uses the harmonised structure and integrates with ISO 27001 and 27701.',
                'Scope decides: remember to include embedded AI in SaaS and BI tools.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'AIMS mit klarem Scope',
                    'detail' => 'Kontext, interessierte Parteien und Grenzen des KI-Managementsystems festlegen — inklusive der Frage, in welchen Rollen die Organisation auftritt.',
                    'ref' => 'Kapitel 4',
                ],
                [
                    'title' => 'KI-Politik und Ziele',
                    'detail' => 'Eine verbindliche Politik mit Zielen, Prinzipien und Verantwortlichkeiten — abgestimmt mit Sicherheits- und Datenschutzpolitik statt daneben.',
                    'ref' => 'Kapitel 5',
                ],
                [
                    'title' => 'Risikobeurteilung und Behandlung',
                    'detail' => 'KI-spezifische Risiken bewerten: Datenherkunft, Bias, Robustheit, Missbrauch, Automatisierungsgrad, Abhängigkeit von Anbietern.',
                    'ref' => 'Kapitel 6',
                ],
                [
                    'title' => 'Auswirkungsbewertung für Betroffene',
                    'detail' => 'Über klassische Organisationsrisiken hinaus verlangt der Standard die Betrachtung von Auswirkungen auf Personen und Gesellschaft.',
                ],
                [
                    'title' => 'Lebenszyklus-Controls',
                    'detail' => 'Anforderungen für Design, Daten für KI, Verifikation und Validierung, Deployment, Betrieb und Außerbetriebnahme — inklusive Dokumentation.',
                    'ref' => 'Annex A und B',
                ],
                [
                    'title' => 'Daten für KI',
                    'detail' => 'Herkunft, Qualität, Repräsentativität, Kennzeichnung und Zugriff auf Trainings- und Evaluierungsdaten sind explizite Controls.',
                ],
                [
                    'title' => 'Dritte und Lieferanten',
                    'detail' => 'Modell-APIs, Datenlieferanten und Integratoren müssen bewertet und vertraglich gebunden werden — mit klarer Rollenverteilung.',
                ],
                [
                    'title' => 'Audit, Review, Verbesserung',
                    'detail' => 'Interne Audits, Managementbewertung und Korrekturmaßnahmen halten das System lebendig — dieselbe Mechanik wie in ISO 27001.',
                    'ref' => 'Kapitel 9 und 10',
                ],
            ],
            'en' => [
                [
                    'title' => 'AIMS with a clear scope',
                    'detail' => 'Define context, interested parties and the boundaries of the AI management system — including which roles the organisation takes on.',
                    'ref' => 'Clause 4',
                ],
                [
                    'title' => 'AI policy and objectives',
                    'detail' => 'A binding policy with objectives, principles and responsibilities — aligned with security and privacy policy rather than parallel to it.',
                    'ref' => 'Clause 5',
                ],
                [
                    'title' => 'Risk assessment and treatment',
                    'detail' => 'Assess AI-specific risks: data provenance, bias, robustness, misuse, degree of automation, provider dependency.',
                    'ref' => 'Clause 6',
                ],
                [
                    'title' => 'Impact assessment for affected people',
                    'detail' => 'Beyond classic organisational risk, the standard requires considering impacts on individuals and society.',
                ],
                [
                    'title' => 'Lifecycle controls',
                    'detail' => 'Requirements for design, data for AI, verification and validation, deployment, operation and decommissioning — including documentation.',
                    'ref' => 'Annexes A and B',
                ],
                [
                    'title' => 'Data for AI',
                    'detail' => 'Provenance, quality, representativeness, labelling and access to training and evaluation data are explicit controls.',
                ],
                [
                    'title' => 'Third parties and suppliers',
                    'detail' => 'Model APIs, data suppliers and integrators must be assessed and contractually bound — with clear role allocation.',
                ],
                [
                    'title' => 'Audit, review, improvement',
                    'detail' => 'Internal audits, management review and corrective actions keep the system alive — the same mechanics as in ISO 27001.',
                    'ref' => 'Clauses 9 and 10',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'ISMS erweitern, nicht duplizieren',
                    'detail' => 'Risikoprozess, Auditzyklus und Dokumentenlenkung mitnutzen und um KI-Themen ergänzen.',
                ],
                [
                    'title' => 'Modell- und Dataset-Inventar',
                    'detail' => 'Modelle, Versionen, Trainingsdaten, Metriken und Owner nachvollziehbar führen — die Grundlage für nahezu jedes Control.',
                ],
                [
                    'title' => 'Daten-Controls in der Pipeline',
                    'detail' => 'Provenance, Qualitätstests, Labeling-Regeln und Zugriffsbeschränkungen dort umsetzen, wo die Daten entstehen.',
                ],
                [
                    'title' => 'Change- und Release-Prozess für Modelle',
                    'detail' => 'Modellwechsel wie Softwarereleases behandeln: Test, Freigabe, Rollback, Kommunikation.',
                ],
                [
                    'title' => 'Monitoring mit Rückkopplung',
                    'detail' => 'Betriebsmetriken, Nutzerfeedback und Vorfälle fließen in die nächste Risikobewertung ein.',
                ],
                [
                    'title' => 'Lieferantenbewertung für Modell-APIs',
                    'detail' => 'Region, Retention, Trainingsnutzung, Änderungsankündigungen und Exit-Optionen bewerten und dokumentieren.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Extend the ISMS, do not duplicate it',
                    'detail' => 'Reuse the risk process, audit cycle and document control and extend them with AI topics.',
                ],
                [
                    'title' => 'Model and dataset inventory',
                    'detail' => 'Track models, versions, training data, metrics and owners traceably — the basis for nearly every control.',
                ],
                [
                    'title' => 'Data controls in the pipeline',
                    'detail' => 'Implement provenance, quality tests, labelling rules and access restrictions where the data is created.',
                ],
                [
                    'title' => 'Change and release process for models',
                    'detail' => 'Treat model changes like software releases: test, approval, rollback, communication.',
                ],
                [
                    'title' => 'Monitoring with feedback',
                    'detail' => 'Operating metrics, user feedback and incidents feed into the next risk assessment.',
                ],
                [
                    'title' => 'Supplier assessment for model APIs',
                    'detail' => 'Assess and document region, retention, training use, change notifications and exit options.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Scope deckt reale KI-Nutzung ab?',
                    'detail' => 'Inklusive eingebetteter Funktionen in BI, CRM und Support-Tools.',
                ],
                [
                    'title' => 'Auswirkungsbewertungen durchgeführt?',
                    'detail' => 'Für Use-Cases mit Auswirkung auf Menschen, dokumentiert und datiert.',
                ],
                [
                    'title' => 'Datenherkunft dokumentiert?',
                    'detail' => 'Quellen, Rechte, Labeling-Verfahren und Qualitätsprüfungen belegbar.',
                ],
                [
                    'title' => 'Modelländerungen kontrolliert?',
                    'detail' => 'Versionierung, Freigabe und Rollback etabliert und benutzt.',
                ],
                [
                    'title' => 'Monitoring und Vorfallbehandlung aktiv?',
                    'detail' => 'Mit Zuständigkeiten, Schwellenwerten und dokumentierten Fällen.',
                ],
                [
                    'title' => 'Lieferantenklauseln geprüft?',
                    'detail' => 'KI-relevante Themen wie Trainingsnutzung und Änderungsankündigung enthalten.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Does scope cover real AI usage?',
                    'detail' => 'Including embedded features in BI, CRM and support tools.',
                ],
                [
                    'title' => 'Impact assessments performed?',
                    'detail' => 'For use cases affecting people, documented and dated.',
                ],
                [
                    'title' => 'Data provenance documented?',
                    'detail' => 'Sources, rights, labelling procedures and quality checks evidenced.',
                ],
                [
                    'title' => 'Model changes controlled?',
                    'detail' => 'Versioning, approval and rollback established and used.',
                ],
                [
                    'title' => 'Monitoring and incident handling active?',
                    'detail' => 'With responsibilities, thresholds and documented cases.',
                ],
                [
                    'title' => 'Supplier clauses reviewed?',
                    'detail' => 'AI-relevant topics such as training use and change notification included.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => 'Zertifikat ohne Runtime-Controls',
                    'detail' => 'Ein AIMS auf Papier ohne Inventar, Tests und Monitoring hält dem ersten Vorfall nicht stand.',
                ],
                [
                    'title' => 'Paralleles zweites Managementsystem',
                    'detail' => 'Getrennte Prozesse für Security und KI erzeugen doppelte Arbeit und widersprüchliche Aussagen.',
                ],
                [
                    'title' => 'Eingebettete KI außerhalb des Scopes',
                    'detail' => 'Assistenzfunktionen in gekauften Tools sind KI-Nutzung — auch wenn niemand sie beschafft hat.',
                ],
                [
                    'title' => '42001 mit AI-Act-Konformität gleichgesetzt',
                    'detail' => 'Der Standard hilft strukturell, ersetzt aber keine gesetzliche Einordnung und keine Konformitätsbewertung.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Certificate without runtime controls',
                    'detail' => 'An AIMS on paper without inventory, tests and monitoring does not survive the first incident.',
                ],
                [
                    'title' => 'A parallel second management system',
                    'detail' => 'Separate processes for security and AI create duplicated work and contradictory statements.',
                ],
                [
                    'title' => 'Embedded AI outside the scope',
                    'detail' => 'Assistant features in purchased tools are AI usage — even if nobody procured them.',
                ],
                [
                    'title' => '42001 equated with AI Act conformity',
                    'detail' => 'The standard helps structurally but replaces neither legal classification nor conformity assessment.',
                ],
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
            [
                'label' => [
                    'de' => 'ISO — ISO/IEC 23894 (KI-Risikomanagement)',
                    'en' => 'ISO — ISO/IEC 23894 (AI risk management)',
                ],
                'href' => 'https://www.iso.org/standard/77304.html',
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
            'de' => "Sobald steuerlich relevante Daten durch ELT-Pipelines, dbt-Modelle und BI-Extracts laufen, wird die Datenplattform Teil der Buchführungslandschaft — auch wenn niemand sie so genannt hat.\n\nDie GoBD verlangen Nachvollziehbarkeit, Unveränderbarkeit, maschinelle Auswertbarkeit und eine Verfahrensdokumentation. Für Plattform-Teams ist das die härteste Gegenkraft zur DSGVO-Löschpflicht: Beides gleichzeitig zu erfüllen erfordert bewusstes Lifecycle-Design statt Zufall.",
            'en' => "As soon as tax-relevant data flows through ELT pipelines, dbt models and BI extracts, the data platform becomes part of the accounting landscape — even if nobody called it that.\n\nGoBD require traceability, immutability, machine evaluability and process documentation. For platform teams this is the strongest counterforce to GDPR erasure duties: satisfying both at once requires deliberate lifecycle design instead of coincidence.",
        ],
        'appliesTo' => [
            'de' => "Buchführungs- und aufzeichnungspflichtige Unternehmen in Deutschland — unabhängig davon, ob die Systeme selbst betrieben oder als Cloud-Dienst genutzt werden.\n\nRelevant sind alle Systeme, in denen steuerlich relevante Daten entstehen, verarbeitet oder aufbewahrt werden: ERP, Kassensysteme, Rechnungseingang, aber eben auch Warehouse und Reporting, wenn dort steuerrelevante Auswertungen erzeugt werden.",
            'en' => "Businesses in Germany subject to bookkeeping and record-keeping duties — regardless of whether systems are self-operated or consumed as a cloud service.\n\nRelevant are all systems where tax-relevant data is created, processed or retained: ERP, point-of-sale, invoice intake — but also warehouse and reporting if tax-relevant evaluations are produced there.",
        ],
        'scopeNotes' => [
            'de' => [
                'Die GoBD sind eine Verwaltungsanweisung der Finanzverwaltung — verbindliche Fristen stehen in AO und HGB.',
                'Betroffen sind steuerlich relevante Daten, nicht jeder analytische Datensatz im Warehouse.',
                'Aufbewahrungspflichten und DSGVO-Löschpflichten können kollidieren — das muss dokumentiert entschieden werden.',
                'Diese Seite ist Orientierung — steuerliche Bewertung gehört zu Steuerberatung und Fachabteilung.',
            ],
            'en' => [
                'GoBD is administrative guidance from the tax authorities — binding retention periods sit in the Fiscal Code and Commercial Code.',
                'Only tax-relevant data is in scope, not every analytical dataset in the warehouse.',
                'Retention duties and GDPR erasure duties can collide — that must be decided in a documented way.',
                'This page is orientation — tax assessment belongs with tax advisors and the finance function.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Nachvollziehbarkeit und Nachprüfbarkeit',
                    'detail' => 'Ein Dritter muss Geschäftsvorfälle und deren Verarbeitung nachvollziehen können — von der Quelle über Transformationen bis zur Auswertung.',
                    'ref' => 'GoBD, Rz. 30 ff.',
                ],
                [
                    'title' => 'Vollständigkeit, Richtigkeit, zeitgerechte Erfassung, Ordnung',
                    'detail' => 'Die klassischen Ordnungsmäßigkeitsgrundsätze gelten auch für elektronische Prozesse — Lücken in Ladeläufen sind hier ein echtes Thema.',
                    'ref' => '§ 146 AO, § 239 HGB',
                ],
                [
                    'title' => 'Unveränderbarkeit',
                    'detail' => 'Buchungen und aufbewahrungspflichtige Daten dürfen nicht ohne Kennzeichnung verändert werden. Änderungen müssen protokolliert und der ursprüngliche Inhalt feststellbar bleiben.',
                    'ref' => '§ 146 Abs. 4 AO',
                ],
                [
                    'title' => 'Verfahrensdokumentation',
                    'detail' => 'Beschreibung der Systeme, Datenflüsse, Kontrollen und Aufbewahrung — inklusive der eingesetzten Transformationen und Berechtigungen.',
                    'ref' => 'GoBD, Rz. 151 ff.',
                ],
                [
                    'title' => 'Aufbewahrungsfristen',
                    'detail' => 'Für Bücher, Aufzeichnungen und Buchungsbelege gelten regelmäßig zehn Jahre, für sonstige Unterlagen kürzere Fristen. Fristbeginn und Sonderfälle beachten.',
                    'ref' => '§ 147 AO',
                ],
                [
                    'title' => 'Maschinelle Auswertbarkeit und Datenzugriff',
                    'detail' => 'Die Finanzverwaltung kann unmittelbaren Zugriff, mittelbaren Zugriff oder Datenträgerüberlassung verlangen (Z1/Z2/Z3). Formate müssen auswertbar bleiben, nicht nur lesbar.',
                    'ref' => '§ 147 Abs. 6 AO',
                ],
                [
                    'title' => 'Format der eingehenden Belege',
                    'detail' => 'Eingehende elektronische Belege sind grundsätzlich im Empfangsformat aufzubewahren; bei E-Rechnungen genügt regelmäßig der strukturierte Teil.',
                    'ref' => 'GoBD i. d. F. vom 14.07.2025',
                ],
                [
                    'title' => 'Auslagerung und Cloud',
                    'detail' => 'Elektronische Bücher dürfen unter Bedingungen im Ausland geführt werden; Verantwortung, Zugriff und Nachweisfähigkeit bleiben beim Steuerpflichtigen.',
                    'ref' => '§ 146 Abs. 2a und 2b AO',
                ],
            ],
            'en' => [
                [
                    'title' => 'Traceability and verifiability',
                    'detail' => 'A third party must be able to follow business transactions and their processing — from source through transformations to the report.',
                    'ref' => 'GoBD, para. 30 ff.',
                ],
                [
                    'title' => 'Completeness, accuracy, timely recording, order',
                    'detail' => 'The classic principles of proper accounting also apply to electronic processes — gaps in load runs are a genuine issue here.',
                    'ref' => 'Section 146 AO, Section 239 HGB',
                ],
                [
                    'title' => 'Immutability',
                    'detail' => 'Postings and records subject to retention must not be changed without being marked. Changes must be logged and the original content must remain determinable.',
                    'ref' => 'Section 146(4) AO',
                ],
                [
                    'title' => 'Process documentation',
                    'detail' => 'Description of systems, data flows, controls and retention — including the transformations and permissions in use.',
                    'ref' => 'GoBD, para. 151 ff.',
                ],
                [
                    'title' => 'Retention periods',
                    'detail' => 'Books, records and posting documents are generally kept for ten years, other documents for shorter periods. Watch the start of the period and special cases.',
                    'ref' => 'Section 147 AO',
                ],
                [
                    'title' => 'Machine evaluability and data access',
                    'detail' => 'Tax authorities may request direct access, indirect access or data transfer on a medium (Z1/Z2/Z3). Formats must remain evaluable, not merely readable.',
                    'ref' => 'Section 147(6) AO',
                ],
                [
                    'title' => 'Format of incoming documents',
                    'detail' => 'Incoming electronic documents must generally be retained in the format received; for e-invoices the structured part is generally sufficient.',
                    'ref' => 'GoBD as amended 14 July 2025',
                ],
                [
                    'title' => 'Outsourcing and cloud',
                    'detail' => 'Electronic books may be kept abroad under conditions; responsibility, access and the ability to provide evidence stay with the taxpayer.',
                    'ref' => 'Section 146(2a) and (2b) AO',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'Steuerrelevante Datasets kennzeichnen',
                    'detail' => 'Umsatz-, Rechnungs-, Kassen- und Stammdaten mit Metadaten markieren — nur so lassen sich Fristen und Sperren gezielt anwenden.',
                ],
                [
                    'title' => 'Unveränderbarkeit technisch abbilden',
                    'detail' => 'Append-only Layer, Time Travel, Snapshots und Änderungsprotokolle statt Überschreiben in Ladeprozessen.',
                ],
                [
                    'title' => 'Verfahrensdokumentation für Pipelines',
                    'detail' => 'Quellsysteme, Ladelogik, dbt-Transformationen, Tests, Berechtigungen und Aufbewahrung beschreiben — versioniert im Repository, nicht als loses Word-Dokument.',
                ],
                [
                    'title' => 'Retention und Löschung gemeinsam denken',
                    'detail' => 'Aufbewahrungspflichten als Legal Hold modellieren, DSGVO-Löschung auf nicht-steuerrelevante Attribute begrenzen und Entscheidungen dokumentieren.',
                ],
                [
                    'title' => 'Reproduzierbare Auswertungen',
                    'detail' => 'Versionierte Logik, dokumentierte Kennzahldefinitionen und archivierte Ergebnisse, damit ein Bericht Jahre später erklärbar bleibt.',
                ],
                [
                    'title' => 'Exportfähigkeit für die Prüfung',
                    'detail' => 'Auswertbare Exporte in gängigen Formaten inklusive Struktur- und Feldbeschreibungen vorbereiten und testen.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Label tax-relevant datasets',
                    'detail' => 'Mark revenue, invoice, point-of-sale and master data with metadata — only then can periods and holds be applied selectively.',
                ],
                [
                    'title' => 'Implement immutability technically',
                    'detail' => 'Append-only layers, time travel, snapshots and change logs instead of overwriting in load processes.',
                ],
                [
                    'title' => 'Process documentation for pipelines',
                    'detail' => 'Describe source systems, load logic, dbt transformations, tests, permissions and retention — versioned in the repository, not as a loose Word file.',
                ],
                [
                    'title' => 'Design retention and deletion together',
                    'detail' => 'Model retention duties as legal holds, restrict GDPR deletion to non-tax-relevant attributes and document the decisions.',
                ],
                [
                    'title' => 'Reproducible reports',
                    'detail' => 'Versioned logic, documented metric definitions and archived results so a report is still explainable years later.',
                ],
                [
                    'title' => 'Export capability for audits',
                    'detail' => 'Prepare and test evaluable exports in common formats including structure and field descriptions.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Steuerrelevante Daten inventarisiert?',
                    'detail' => 'Mit Owner, Quelle, Frist und Speicherort — inklusive Kopien in BI und Exports.',
                ],
                [
                    'title' => 'Verfahrensdokumentation aktuell?',
                    'detail' => 'Enthält aktuelle Pipelines, Transformationen und Berechtigungen, nicht den Stand von vor drei Jahren.',
                ],
                [
                    'title' => 'Änderungen protokolliert?',
                    'detail' => 'Korrekturen, Reloads und Backfills sind nachvollziehbar und kennzeichnen den ursprünglichen Zustand.',
                ],
                [
                    'title' => 'Fristen im Lifecycle abgebildet?',
                    'detail' => 'Archiv- und Löschjobs kennen die relevanten Fristen und Ausnahmen.',
                ],
                [
                    'title' => 'Löschprozess respektiert Legal Holds?',
                    'detail' => 'DSGVO-Löschläufe greifen nicht in aufbewahrungspflichtige Bestände ein.',
                ],
                [
                    'title' => 'Prüfungsexport getestet?',
                    'detail' => 'Mindestens ein Probeexport inklusive Beschreibung wurde erzeugt und geprüft.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Tax-relevant data inventoried?',
                    'detail' => 'With owner, source, retention period and storage location — including copies in BI and exports.',
                ],
                [
                    'title' => 'Process documentation current?',
                    'detail' => 'Contains current pipelines, transformations and permissions, not the state from three years ago.',
                ],
                [
                    'title' => 'Changes logged?',
                    'detail' => 'Corrections, reloads and backfills are traceable and identify the original state.',
                ],
                [
                    'title' => 'Retention periods implemented in the lifecycle?',
                    'detail' => 'Archive and deletion jobs know the relevant periods and exceptions.',
                ],
                [
                    'title' => 'Deletion process respects legal holds?',
                    'detail' => 'GDPR deletion runs do not touch records under retention duties.',
                ],
                [
                    'title' => 'Audit export tested?',
                    'detail' => 'At least one sample export including documentation has been produced and reviewed.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => 'Warehouse als System of Record ohne Unveränderbarkeit',
                    'detail' => 'Overwrite-Ladepattern und truncate/insert zerstören Nachvollziehbarkeit, sobald steuerrelevante Daten betroffen sind.',
                ],
                [
                    'title' => 'Löschpflicht gegen Aufbewahrung ungeklärt',
                    'detail' => 'Ohne dokumentierte Abwägung entstehen entweder unzulässige Löschungen oder unzulässige Speicherungen.',
                ],
                [
                    'title' => 'Transformationen undokumentiert',
                    'detail' => 'Wenn niemand erklären kann, wie eine Kennzahl entsteht, ist die Nachprüfbarkeit praktisch verloren.',
                ],
                [
                    'title' => 'Cloud-Standort ungeprüft',
                    'detail' => 'Aufbewahrung im Ausland ohne Prüfung der Voraussetzungen und ohne gesicherten Zugriff für die Prüfung.',
                ],
                [
                    'title' => 'Nur Produktivsystem archiviert',
                    'detail' => 'Belege in Ticketsystemen, Mail-Anhängen und BI-Extracts fallen sonst aus der Aufbewahrung heraus.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Warehouse as system of record without immutability',
                    'detail' => 'Overwrite load patterns and truncate/insert destroy traceability as soon as tax-relevant data is involved.',
                ],
                [
                    'title' => 'Erasure duty versus retention unresolved',
                    'detail' => 'Without a documented balancing you get either unlawful deletion or unlawful retention.',
                ],
                [
                    'title' => 'Transformations undocumented',
                    'detail' => 'If nobody can explain how a metric is produced, verifiability is practically lost.',
                ],
                [
                    'title' => 'Cloud location unchecked',
                    'detail' => 'Retention abroad without checking the conditions and without secured access for audits.',
                ],
                [
                    'title' => 'Only the production system archived',
                    'detail' => 'Otherwise documents in ticket systems, mail attachments and BI extracts drop out of retention.',
                ],
            ],
        ],
        'officialSources' => [
            [
                'label' => [
                    'de' => 'BMF — GoBD, 2. Änderung vom 14.07.2025 (PDF)',
                    'en' => 'German Federal Ministry of Finance — GoBD, 2nd amendment of 14 July 2025 (PDF)',
                ],
                'href' => 'https://www.bundesfinanzministerium.de/Content/DE/Downloads/BMF_Schreiben/Weitere_Steuerthemen/Abgabenordnung/2025-07-14-GoBD-2-aenderung.pdf?__blob=publicationFile&v=4',
            ],
            [
                'label' => [
                    'de' => '§ 146 AO — Ordnungsvorschriften für Buchführung',
                    'en' => 'Section 146 AO — rules for bookkeeping',
                ],
                'href' => 'https://www.gesetze-im-internet.de/ao_1977/__146.html',
            ],
            [
                'label' => [
                    'de' => '§ 147 AO — Aufbewahrung und Datenzugriff',
                    'en' => 'Section 147 AO — retention and data access',
                ],
                'href' => 'https://www.gesetze-im-internet.de/ao_1977/__147.html',
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
            'de' => "NIS2 erweitert den Kreis der betroffenen Branchen deutlich und macht Cybersicherheit zur Leitungsaufgabe mit persönlicher Verantwortung.\n\nFür Datenplattformen sind vor allem drei Punkte relevant: Verfügbarkeit und Wiederherstellbarkeit, Sicherheit der Lieferkette inklusive Cloud-Diensten und ein Meldeprozess, der unter Zeitdruck funktioniert.",
            'en' => "NIS2 significantly widens the set of affected sectors and makes cybersecurity a management duty with personal accountability.\n\nFor data platforms three points matter most: availability and recoverability, supply chain security including cloud services, and a reporting process that works under time pressure.",
        ],
        'appliesTo' => [
            'de' => "Wesentliche und wichtige Einrichtungen in den Anhängen der Richtlinie — je nach Sektor, Größe und nationaler Umsetzung.\n\nDie Richtlinie wirkt über nationale Gesetze; die konkrete Betroffenheit und Registrierungspflicht ergibt sich aus dem jeweiligen Umsetzungsrecht.",
            'en' => "Essential and important entities listed in the directive’s annexes — depending on sector, size and national transposition.\n\nThe directive works through national law; actual applicability and registration duties follow from the respective transposition act.",
        ],
        'scopeNotes' => [
            'de' => [
                'NIS2 ist eine Richtlinie — maßgeblich ist das nationale Umsetzungsgesetz.',
                'Betroffenheit hängt von Sektor und Größe ab und sollte dokumentiert festgestellt werden.',
                'Cybersicherheit nach NIS2 ersetzt keine Datenschutzpflichten aus der DSGVO.',
            ],
            'en' => [
                'NIS2 is a directive — the national transposition act is what governs.',
                'Applicability depends on sector and size and should be determined in a documented way.',
                'Cybersecurity under NIS2 does not replace data protection duties under the GDPR.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'Risikomanagementmaßnahmen',
                    'detail' => 'Gefordert ist ein Mindestkatalog: Risikoanalyse, Incident Handling, Business Continuity und Backup, Kryptografie, Zugriffskontrolle und Multi-Faktor-Authentisierung.',
                    'ref' => 'Art. 21',
                ],
                [
                    'title' => 'Sicherheit der Lieferkette',
                    'detail' => 'Sicherheit direkter Anbieter und Dienstleister ist Teil der eigenen Pflichten — für Datenteams also Cloud, SaaS und Managed Services.',
                    'ref' => 'Art. 21 Abs. 2 lit. d',
                ],
                [
                    'title' => 'Meldepflichten mit Fristen',
                    'detail' => 'Frühwarnung binnen 24 Stunden, Meldung binnen 72 Stunden und Abschlussbericht innerhalb eines Monats — gerechnet ab Kenntnis eines erheblichen Vorfalls.',
                    'ref' => 'Art. 23',
                ],
                [
                    'title' => 'Verantwortung der Leitung',
                    'detail' => 'Leitungsorgane müssen Maßnahmen genehmigen, überwachen und sich schulen lassen; Verstöße können persönliche Folgen haben.',
                    'ref' => 'Art. 20',
                ],
            ],
            'en' => [
                [
                    'title' => 'Risk management measures',
                    'detail' => 'A minimum catalogue is required: risk analysis, incident handling, business continuity and backup, cryptography, access control and multi-factor authentication.',
                    'ref' => 'Art. 21',
                ],
                [
                    'title' => 'Supply chain security',
                    'detail' => 'The security of direct suppliers and service providers is part of your own duties — for data teams that means cloud, SaaS and managed services.',
                    'ref' => 'Art. 21(2)(d)',
                ],
                [
                    'title' => 'Reporting duties with deadlines',
                    'detail' => 'Early warning within 24 hours, notification within 72 hours and a final report within one month — counted from awareness of a significant incident.',
                    'ref' => 'Art. 23',
                ],
                [
                    'title' => 'Management accountability',
                    'detail' => 'Management bodies must approve and oversee measures and undergo training; breaches can have personal consequences.',
                    'ref' => 'Art. 20',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'Backup und Wiederherstellung beweisen',
                    'detail' => 'Wiederherstellung von Warehouse, Metadaten und Orchestrierung testen — Restore-Zeit dokumentieren, nicht nur Backup-Jobs.',
                ],
                [
                    'title' => 'Lieferantensicherheit bewerten',
                    'detail' => 'Cloud- und SaaS-Anbieter mit Nachweisen (ISO 27001, C5, SOC 2) und vertraglichen Pflichten einordnen.',
                ],
                [
                    'title' => 'Erkennung und Meldekette',
                    'detail' => 'Logging und Alerting so aufsetzen, dass ein Vorfall überhaupt innerhalb von 24 Stunden erkannt und eskaliert werden kann.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Prove backup and restore',
                    'detail' => 'Test restore of warehouse, metadata and orchestration — document restore time, not only backup jobs.',
                ],
                [
                    'title' => 'Assess supplier security',
                    'detail' => 'Classify cloud and SaaS providers with evidence (ISO 27001, C5, SOC 2) and contractual duties.',
                ],
                [
                    'title' => 'Detection and reporting chain',
                    'detail' => 'Set up logging and alerting so an incident can actually be detected and escalated within 24 hours.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Betroffenheit dokumentiert?',
                    'detail' => 'Sektor, Größe und nationale Umsetzung geprüft und schriftlich festgehalten.',
                ],
                [
                    'title' => 'Meldeprozess geübt?',
                    'detail' => 'Rollen, Kontakte und Fristen sind bekannt und wurden mindestens einmal durchgespielt.',
                ],
                [
                    'title' => 'Restore getestet?',
                    'detail' => 'Mit dokumentiertem Ergebnis und realistischer Wiederherstellungszeit.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Applicability documented?',
                    'detail' => 'Sector, size and national transposition checked and recorded in writing.',
                ],
                [
                    'title' => 'Reporting process rehearsed?',
                    'detail' => 'Roles, contacts and deadlines are known and have been rehearsed at least once.',
                ],
                [
                    'title' => 'Restore tested?',
                    'detail' => 'With a documented result and a realistic recovery time.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => '„Wir sind keine kritische Infrastruktur“',
                    'detail' => 'NIS2 erfasst deutlich mehr Sektoren als die Vorgängerrichtlinie — Annahmen ohne Prüfung sind riskant.',
                ],
                [
                    'title' => 'Lieferkette ausgeblendet',
                    'detail' => 'Die Sicherheit von Cloud- und SaaS-Anbietern gehört zum eigenen Pflichtenkreis, nicht nur in deren AGB.',
                ],
                [
                    'title' => 'Meldefristen ungeübt',
                    'detail' => 'Wer erst im Vorfall nach Zuständigkeiten sucht, verliert die ersten 24 Stunden.',
                ],
            ],
            'en' => [
                [
                    'title' => '“We are not critical infrastructure”',
                    'detail' => 'NIS2 covers far more sectors than its predecessor — assumptions without a check are risky.',
                ],
                [
                    'title' => 'Supply chain ignored',
                    'detail' => 'The security of cloud and SaaS providers is part of your own duties, not just their terms.',
                ],
                [
                    'title' => 'Reporting deadlines unrehearsed',
                    'detail' => 'If you first look for responsibilities during the incident, you lose the first 24 hours.',
                ],
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
                    'de' => 'EU-Kommission — NIS2-Richtlinie',
                    'en' => 'European Commission — NIS2 directive',
                ],
                'href' => 'https://digital-strategy.ec.europa.eu/en/policies/nis2-directive',
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
            'de' => "DORA behandelt IT-Ausfälle als Aufsichtsthema: Wer im Finanzsektor Daten- und Reporting-Plattformen betreibt, muss Resilienz nachweisen — nicht nur Sicherheit.\n\nBesonders spürbar ist das beim Third-Party-Risiko: Cloud-Warehouse, BI-SaaS und Datenlieferanten landen im Informationsregister, brauchen Vertragsklauseln, Kritikalitätsbewertung und einen belastbaren Exit-Plan.",
            'en' => "DORA treats IT outages as a supervisory topic: if you run data and reporting platforms in the financial sector, you must evidence resilience — not only security.\n\nThird-party risk is where this bites hardest: cloud warehouse, BI SaaS and data suppliers end up in the register of information and need contractual clauses, criticality assessment and a credible exit plan.",
        ],
        'appliesTo' => [
            'de' => "Finanzunternehmen im Sinne der Verordnung — Banken, Versicherungen, Zahlungsdienstleister, Wertpapierfirmen und weitere — sowie benannte kritische IKT-Drittdienstleister.\n\nDienstleister ohne Finanzlizenz spüren DORA indirekt: über Verträge, Auskunftspflichten und Prüfrechte ihrer Kunden.",
            'en' => "Financial entities as defined by the regulation — banks, insurers, payment providers, investment firms and others — plus designated critical ICT third-party providers.\n\nService providers without a financial licence feel DORA indirectly: through contracts, information duties and audit rights of their customers.",
        ],
        'scopeNotes' => [
            'de' => [
                'DORA gilt unmittelbar als Verordnung und wird durch technische Standards konkretisiert.',
                'Es geht um operationelle Resilienz insgesamt, nicht nur um Informationssicherheit.',
                'Auch interne Analytics-Plattformen können relevant sein, wenn sie regulatorische Berichte stützen.',
            ],
            'en' => [
                'DORA applies directly as a regulation and is specified further by technical standards.',
                'It is about operational resilience overall, not only information security.',
                'Internal analytics platforms can also be relevant if they support regulatory reporting.',
            ],
        ],
        'keyRules' => [
            'de' => [
                [
                    'title' => 'IKT-Risikomanagementrahmen',
                    'detail' => 'Governance, Strategie, Schutzmaßnahmen, Erkennung, Reaktion und Wiederherstellung sind als zusammenhängender Rahmen zu betreiben — mit Verantwortung im Leitungsorgan.',
                    'ref' => 'Art. 5–15',
                ],
                [
                    'title' => 'Vorfallmanagement und Meldung',
                    'detail' => 'IKT-Vorfälle müssen klassifiziert und schwerwiegende Vorfälle den Aufsichtsbehörden in gestuften Fristen gemeldet werden.',
                    'ref' => 'Art. 17–23',
                ],
                [
                    'title' => 'Resilienztests',
                    'detail' => 'Regelmäßige Tests des Resilienzprogramms, für bestimmte Institute inklusive bedrohungsgeleiteter Penetrationstests.',
                    'ref' => 'Art. 24–27',
                ],
                [
                    'title' => 'Drittparteienrisiko und Informationsregister',
                    'detail' => 'Alle IKT-Dienstleistungen sind zu registrieren, kritische Funktionen zu kennzeichnen und Verträge müssen Mindestinhalte wie Prüfrechte, Weiterverlagerung und Kündigungsrechte abdecken.',
                    'ref' => 'Art. 28–30',
                ],
            ],
            'en' => [
                [
                    'title' => 'ICT risk management framework',
                    'detail' => 'Governance, strategy, protection, detection, response and recovery must run as one coherent framework — with accountability in the management body.',
                    'ref' => 'Arts. 5–15',
                ],
                [
                    'title' => 'Incident management and reporting',
                    'detail' => 'ICT incidents must be classified, and major incidents reported to supervisors within staged deadlines.',
                    'ref' => 'Arts. 17–23',
                ],
                [
                    'title' => 'Resilience testing',
                    'detail' => 'Regular testing of the resilience programme, for certain entities including threat-led penetration testing.',
                    'ref' => 'Arts. 24–27',
                ],
                [
                    'title' => 'Third-party risk and register of information',
                    'detail' => 'All ICT services must be registered, critical functions flagged, and contracts must cover minimum content such as audit rights, sub-outsourcing and termination rights.',
                    'ref' => 'Arts. 28–30',
                ],
            ],
        ],
        'platformImplications' => [
            'de' => [
                [
                    'title' => 'Informationsregister für Datendienste',
                    'detail' => 'Warehouse, ETL-SaaS, BI-Plattform, Datenlieferanten und deren Unterauftragnehmer erfassen — mit Funktion, Kritikalität und Standort.',
                ],
                [
                    'title' => 'Exit- und Konzentrationsrisiko',
                    'detail' => 'Für kritische Dienste einen realistischen Ausstiegspfad beschreiben: Datenexport, Formate, Alternativanbieter, Aufwand und Zeitbedarf.',
                ],
                [
                    'title' => 'Wiederherstellung von Reporting-Ketten',
                    'detail' => 'Nicht nur Datenbanken, sondern die gesamte Kette bis zum regulatorischen Bericht testen — inklusive Metadaten und Berechtigungen.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Register of information for data services',
                    'detail' => 'Capture warehouse, ETL SaaS, BI platform, data suppliers and their sub-contractors — with function, criticality and location.',
                ],
                [
                    'title' => 'Exit and concentration risk',
                    'detail' => 'Describe a realistic exit path for critical services: data export, formats, alternative providers, effort and time needed.',
                ],
                [
                    'title' => 'Recovery of reporting chains',
                    'detail' => 'Test not only databases but the entire chain up to the regulatory report — including metadata and permissions.',
                ],
            ],
        ],
        'checklist' => [
            'de' => [
                [
                    'title' => 'Register vollständig?',
                    'detail' => 'Alle IKT-Dienstleister der Datenplattform inklusive Weiterverlagerung erfasst.',
                ],
                [
                    'title' => 'Kritikalität und Exit bewertet?',
                    'detail' => 'Für Dienste, die kritische oder wichtige Funktionen stützen, liegt ein dokumentierter Plan vor.',
                ],
                [
                    'title' => 'Vorfallklassifizierung einsatzbereit?',
                    'detail' => 'Schwellenwerte, Rollen und Meldewege sind definiert und bekannt.',
                ],
            ],
            'en' => [
                [
                    'title' => 'Register complete?',
                    'detail' => 'All ICT providers of the data platform captured, including sub-outsourcing.',
                ],
                [
                    'title' => 'Criticality and exit assessed?',
                    'detail' => 'For services supporting critical or important functions a documented plan exists.',
                ],
                [
                    'title' => 'Incident classification ready to use?',
                    'detail' => 'Thresholds, roles and reporting channels are defined and known.',
                ],
            ],
        ],
        'commonPitfalls' => [
            'de' => [
                [
                    'title' => 'DORA als reines Security-Thema',
                    'detail' => 'Resilienz umfasst Verfügbarkeit, Wiederherstellung, Tests und Anbietersteuerung — nicht nur Schutzmaßnahmen.',
                ],
                [
                    'title' => 'Register ohne Unterauftragnehmer',
                    'detail' => 'Die Kette hinter dem BI-SaaS bleibt unsichtbar und damit unbewertet.',
                ],
                [
                    'title' => 'Exit-Plan nur auf Papier',
                    'detail' => 'Ohne getesteten Datenexport und geklärte Formate ist ein Ausstieg im Ernstfall nicht durchführbar.',
                ],
            ],
            'en' => [
                [
                    'title' => 'DORA seen as a pure security topic',
                    'detail' => 'Resilience covers availability, recovery, testing and provider management — not only protective measures.',
                ],
                [
                    'title' => 'Register without sub-contractors',
                    'detail' => 'The chain behind the BI SaaS stays invisible and therefore unassessed.',
                ],
                [
                    'title' => 'Exit plan on paper only',
                    'detail' => 'Without tested data export and clarified formats, an exit is not feasible when it matters.',
                ],
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
                    'de' => 'ESMA — Digital Operational Resilience Act (DORA)',
                    'en' => 'ESMA — Digital Operational Resilience Act (DORA)',
                ],
                'href' => 'https://www.esma.europa.eu/esmas-activities/digital-finance-and-innovation/digital-operational-resilience-act-dora',
            ],
        ],
        'relatedPlaybooks' => [
            'host-vs-cloud',
            'access-security-governance',
            'cloud-hosting',
        ],
    ],
];
