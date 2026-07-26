<?php

namespace App\Governance;

class GovernanceDemoWorkspace
{
    /**
     * @return array<string, mixed>
     */
    public function session(): array
    {
        $payload = $this->payload();

        return [
            'id' => 'demo_finance_governance',
            'ownerUserId' => 'demo',
            'title' => 'Demo: Finance Governance Discovery',
            'companyName' => 'Acme GmbH',
            'projectName' => 'Management Reporting 2026',
            'scenario' => 'extend',
            'status' => 'decision_ready',
            'currentStep' => 'report',
            'payload' => $payload,
            'validationSummary' => [
                'score' => 96,
                'state' => 'decision_ready',
                'warnings' => [
                    'Cutover-Regel für Monatsabschluss noch fachlich bestätigen.',
                ],
            ],
            'reportSnapshot' => [
                'generatedAt' => '2026-07-26T12:00:00+02:00',
                'advisor' => $payload['advisor'],
                'dataQuality' => $payload['dataQuality'],
                'recommendations' => $payload['recommendations'],
                'validation' => [
                    'score' => 96,
                    'state' => 'decision_ready',
                    'warnings' => [
                        'Cutover-Regel für Monatsabschluss noch fachlich bestätigen.',
                    ],
                ],
            ],
            'archivedAt' => null,
            'createdAt' => '2026-07-26T12:00:00+02:00',
            'updatedAt' => '2026-07-26T12:30:00+02:00',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function workspace(): array
    {
        return [
            'mainPlan' => [
                'title' => 'Finance Mart Governance Implementation',
                'template' => 'Governance Discovery Session',
                'status' => 'aktiv',
                'progress' => 62,
                'currentSprint' => 'Datenqualität und Modell',
                'summary' => 'Hauptplan für Scope, Risiko, KPI/Mart-Design, DQ-Gates, Decision Brief und Change Request.',
                'sprints' => [
                    ['title' => 'Session prüfen', 'status' => 'erledigt', 'done' => 2, 'total' => 2],
                    ['title' => 'Scope und Risiko', 'status' => 'erledigt', 'done' => 2, 'total' => 2],
                    ['title' => 'Datenqualität und Modell', 'status' => 'läuft', 'done' => 1, 'total' => 2],
                    ['title' => 'Entscheidung und Change', 'status' => 'offen', 'done' => 0, 'total' => 2],
                ],
                'openDecisions' => ['finale Storno-Logik', 'PII-Maskierung für BI Viewer', 'Cutover-Regel Monatsabschluss'],
            ],
            'learningPlan' => [
                'title' => 'dbt + Fabric Enablement & Certification',
                'template' => 'Paralleler Lernplan',
                'status' => 'parallel',
                'progress' => 45,
                'summary' => 'Läuft neben dem Hauptplan: Team-Skills, offizielle Lernpfade, Übungen und Zertifikatsnachweise werden projektbezogen geplant.',
                'tracks' => [
                    ['title' => 'Fabric Grundlagen und Workspace', 'status' => 'erledigt'],
                    ['title' => 'dbt Modeling, Tests und Dokumentation', 'status' => 'läuft'],
                    ['title' => 'Data Quality Gates und Monitoring', 'status' => 'offen'],
                    ['title' => 'DP-600 / PL-300 Zertifikatsvorbereitung', 'status' => 'offen'],
                ],
                'certificates' => ['Microsoft DP-600', 'Microsoft PL-300', 'dbt Fundamentals', 'Databricks Data Engineer Associate'],
            ],
            'kpiCards' => $this->payload()['kpis'],
            'toolRuns' => [
                ['id' => 'kpi-requirements-intake', 'title' => 'KPI Requirements Intake', 'output' => '3 KPI Cards mit Formel, Grain, Owner, Akzeptanzbeispiel und DQ-Kandidaten.'],
                ['id' => 'source-scope-builder', 'title' => 'Source Scope Builder', 'output' => 'SAP S/4HANA Scope mit Must-have, Optional, Skip, PII, DSDR Keys und Ownern.'],
                ['id' => 'mart-design-brief-generator', 'title' => 'Mart Design Brief', 'output' => 'Fact-Kandidat finance_revenue_mart, Dimensionen, Historie und Quality Gates.'],
                ['id' => 'pii-dsdr-readiness-checker', 'title' => 'PII/DSDR Readiness Checker', 'output' => 'Identifier, Kopien, Retention, Maskierung und Rollenprüfung vor Umsetzung.'],
                ['id' => 'decision-brief-generator', 'title' => 'Decision Brief', 'output' => 'Entscheidungsvorlage mit Empfehlung, Risiken, offenen Fragen und nächstem Sprint.'],
                ['id' => 'vendor-learning-path-builder', 'title' => 'Vendor Learning Path Builder', 'output' => 'Lernplan mit Fabric, dbt, Power BI, DQ-Übungen und Zertifikatszielen.'],
            ],
        ];
    }

    /**
     * @return array{note: string, fields: list<string>}|null
     */
    public function toolPrefill(string $toolId): ?array
    {
        return match ($toolId) {
            'kpi-requirements-intake' => [
                'note' => 'Demo aus Finance Governance Workspace: KPI wurde mit Finance Owner und BI Lead vorab abgestimmt.',
                'fields' => [
                    'Net Revenue',
                    'Welche Umsatzentwicklung soll im Monatsabschluss wirklich entschieden werden?',
                    'Priorisierung von Umsatzmaßnahmen und Abstimmung im Monatsabschluss.',
                    'Rechnungsbetrag minus Gutschriften, Stornos und interne Umbuchungen.',
                    'Firma, Kunde, Rechnungsmonat.',
                    'Buchungsdatum, Monatsabschluss M+3, Stornos rückwirkend im Ursprungsmonat.',
                    'Firma, Kunde, Region, Produktgruppe, Zeitraum.',
                    'Finance Owner entscheidet, BI Lead setzt um, Datenschutz prüft PII-Felder.',
                    'Beleg 4711 ergibt nach Gutschrift 1.250 EUR Net Revenue im Monat 2026-01.',
                ],
            ],
            'source-scope-builder' => [
                'note' => 'Demo-Scope für SAP S/4HANA Finance als Grundlage für Load, DQ und Mart Design.',
                'fields' => [
                    'SAP S/4HANA Finance',
                    'Finance Mart für Umsatz, offene Forderungen und Monatsabschluss-Cockpit.',
                    'Fakturabelege, Kunden, Buchungskreis, offene Posten.',
                    'Kundenaufträge, Kostenstellen, Zahlungsbedingungen.',
                    'Anhänge, lange Freitextnotizen, historische Testmandanten.',
                    'E-Mail Rechnungskontakt, Ansprechpartnername, Telefonnummer Debitor.',
                    'customer_id, contact_email.',
                    'täglich 06:00, initial 3 Jahre Historie, BI-Refresh spätestens 08:00.',
                    'Finance Owner, SAP Platform Owner, Datenschutz Review.',
                ],
            ],
            'mart-design-brief-generator' => [
                'note' => 'Demo-Mart für stabilisiertes Management Reporting mit klarer Grain-Entscheidung.',
                'fields' => [
                    'finance_revenue_mart',
                    'Eine Zeile pro Firma, Kunde, Rechnungsmonat und Produktgruppe.',
                    'Net Revenue, Offene Forderungen, Invoice Count.',
                    'Firma, Kunde, Region, Produktgruppe, Zeitraum, Buchungskreis.',
                    'SAP Faktura, FI-AR offene Posten, Kundenstamm.',
                    'SCD2 für Kundenregion, monatlicher Snapshot für offene Posten.',
                    'not null business keys, freshness < 24h, revenue reconciliation, invoice count plausibility.',
                    'Analytics Engineering Lead + Finance Owner.',
                ],
            ],
            'governance-stack-advisor' => [
                'note' => 'Demo-Entscheidung: vorhandene Microsoft-Umgebung ergänzen statt Plattform komplett neu aufbauen.',
                'fields' => [
                    'Microsoft Fabric + Purview + Power BI',
                    'Bestehende Microsoft-Lizenzen, Power BI Nutzung, EU Residency und Finance Stakeholder sind vorhanden.',
                    'Skill-Lücke bei Lakehouse-Betrieb, unklare Catalog-Ownership, Kostenkontrolle noch offen.',
                    'Fabric Engineering, Data Modeling, Purview Governance, dbt Tests.',
                    'DP-600, PL-300, Purview Lernpfad, dbt Fundamentals.',
                    'Wer betreibt Pipelines? Wer genehmigt PII? Welche DQ-Gates blockieren Releases?',
                    'Finance Mart mit SAP S/4HANA, 3 KPIs und 1 Power BI Dashboard.',
                ],
            ],
            'pii-dsdr-readiness-checker' => [
                'note' => 'Demo-Prüfung für Kundendaten im Finance Mart vor BI-Freigabe.',
                'fields' => [
                    'customer_contact_extract',
                    'Kunde und Rechnungskontakt.',
                    'customer_id, contact_email, phone.',
                    'E-Mail, Name, Telefonnummer, Freitextnotiz im Debitorenstamm.',
                    'Raw Lakehouse, Curated View, Finance Mart, Power BI Dataset, Excel Export.',
                    'customer_id + contact_email für Suche über Raw, Mart und BI.',
                    '24 Monate im Mart, Raw nach 90 Tagen löschen, Exporte nach 30 Tagen prüfen.',
                    'Maskierung in BI, Row-Level Security, Export blockieren, Owner-Approval für Zugriff.',
                ],
            ],
            'decision-brief-generator' => [
                'note' => 'Demo-Entscheidungsvorlage aus Session, KPI Cards, Scope, DQ und PII Review.',
                'fields' => [
                    'Fabric Finance Mart für ersten Pilot freigeben.',
                    'Vorhandene SAP-Quelle, kritische Finance Reports und DQ-Probleme im Monatsabschluss.',
                    'Pilot mit SAP S/4HANA Finance, Fabric Lakehouse und Power BI starten.',
                    '3 KPIs, 4 Quellobjekte, 1 Dashboard, kein Full ERP Load.',
                    'PII-Masking offen, Owner-Zeit knapp, Storno-Logik nicht final.',
                    'Wer genehmigt KPI-Definition? Welche DQ-Gates sind release-blocking?',
                    'Source Scope finalisieren, DQ-Regeln anlegen, Mart Design Brief abstimmen.',
                ],
            ],
            'vendor-learning-path-builder' => [
                'note' => 'Demo-Lernplan parallel zum Hauptplan, damit Umsetzung und Zertifizierung Hand in Hand laufen.',
                'fields' => [
                    'Analytics Engineer',
                    'Microsoft Fabric + dbt + Power BI',
                    'Woche 1: Grundlagen, Workspace, Lakehouse, Governance-Begriffe.',
                    'Woche 2: Pipelines, SQL, DQ-Regeln und erste KPI.',
                    'Woche 3: Security, PII, Catalog und Monitoring.',
                    'Woche 4: Review, Zertifikatsvorbereitung und Demo-Projekt.',
                    'DP-600, PL-300, dbt Fundamentals, Databricks Data Engineer Associate.',
                    'KPI Card bauen, Source Scope prüfen, DQ Rule Generator nutzen, Report im Governance Workspace erklären.',
                ],
            ],
            default => null,
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return [
            'advisor' => [
                'scenario' => 'extend',
                'goal' => 'dq',
                'domain' => 'erp',
                'platform' => 'fabric',
                'dqMode' => 'report_stabilization',
                'dqLayer' => 'bi',
                'dqIssues' => ['freshness', 'business_rule', 'completeness'],
            ],
            'kpis' => [
                [
                    'name' => 'Net Revenue',
                    'formula' => 'Rechnungsbetrag minus Gutschriften, Stornos und interne Umbuchungen.',
                    'grain' => 'Firma, Kunde, Rechnungsmonat',
                    'owner' => 'Finance Owner',
                    'source' => 'SAP S/4HANA Faktura',
                    'status' => 'agreed',
                ],
                [
                    'name' => 'Offene Forderungen',
                    'formula' => 'Offene Posten gruppiert nach Fälligkeitsklasse und Buchungskreis.',
                    'grain' => 'Firma, Kunde, Beleg, Tag',
                    'owner' => 'Debitoren Lead',
                    'source' => 'SAP FI-AR',
                    'status' => 'review',
                ],
                [
                    'name' => 'Invoice Count',
                    'formula' => 'Anzahl freigegebener Rechnungsbelege ohne Storno- und Testbelege.',
                    'grain' => 'Firma, Kunde, Rechnungsmonat',
                    'owner' => 'Accounting Lead',
                    'source' => 'SAP S/4HANA Faktura',
                    'status' => 'draft',
                ],
            ],
            'sourceScope' => [
                'supplier' => 'SAP S/4HANA',
                'mustHave' => ['Fakturabelege', 'Kunden', 'Buchungskreis', 'Offene Posten'],
                'optional' => ['Kundenaufträge', 'Kostenstellen', 'Zahlungsbedingungen'],
                'skip' => ['Anhänge', 'lange Freitextnotizen', 'historische Testmandanten'],
                'owners' => ['Finance Owner', 'Platform Owner', 'Datenschutz Review'],
            ],
            'pii' => [
                'fields' => ['Name Rechnungskontakt', 'E-Mail Rechnungskontakt', 'Telefonnummer Debitor'],
                'dsdrKeys' => ['customer_id', 'contact_email'],
                'controls' => ['Maskierung in BI-Extraktionen', 'Retention-Review vor Raw-Load', 'Rollenprüfung für Finance Viewer'],
            ],
            'dataQuality' => [
                'mode' => 'report_stabilization',
                'layer' => 'bi',
                'issueTypes' => ['freshness', 'business_rule', 'completeness'],
                'affectedSources' => ['SAP S/4HANA Faktura', 'SAP FI-AR Offene Posten'],
                'affectedKpis' => ['Net Revenue', 'Offene Forderungen'],
                'affectedReports' => ['Executive Finance Dashboard', 'Monatsabschluss Cockpit'],
                'proposedRules' => [
                    'billing_date darf nicht leer sein',
                    'invoice_amount muss nach Storno-Mapping >= 0 sein',
                    'Dashboard-Refresh darf maximal 24h alt sein',
                    'Jeder offene Posten braucht Buchungskreis und Kunde',
                ],
                'validationFindings' => ['zwei Reports nutzen unterschiedliche Umsatzfilter', 'Refresh-Zeitpunkt wird aktuell nicht dokumentiert'],
                'decisionStatus' => 'decision_ready',
            ],
            'decisionBrief' => [
                'recommendation' => 'Bestehenden Fabric Finance Mart stabilisieren, bevor eine weitere ERP-Quelle angebunden wird.',
                'openQuestions' => ['finale Storno-Logik', 'Owner-Freigabe für PII-Maskierung', 'Cutover-Regel für Monatsabschluss'],
                'nextSprint' => ['Source-Scope-Review', 'DQ-Regeln implementieren', 'Decision-Brief-Freigabe'],
            ],
            'recommendations' => [
                [
                    'title' => 'KPI Requirements Intake',
                    'group' => 'tool',
                    'reason' => 'macht aus Finance-Wünschen belastbare KPI-Karten mit Formel, Grain und Owner',
                    'url' => locale_route('tools.kpi-requirements-intake'),
                ],
                [
                    'title' => 'Source Scope Builder',
                    'group' => 'tool',
                    'reason' => 'klärt Must-have, Skip, PII, Owner und offene Review-Fragen für SAP',
                    'url' => locale_route('tools.source-scope-builder'),
                ],
                [
                    'title' => 'Fabric DQ Rule Generator',
                    'group' => 'tool',
                    'reason' => 'übersetzt die Fehlerklassen in konkrete DQ-Regeln und Checks',
                    'url' => locale_route('tools.fabric-dq-rule-generator'),
                ],
                [
                    'title' => 'Supplier Library: SAP S/4HANA',
                    'group' => 'supplier',
                    'reason' => 'liefert Kernobjekte, PII-Hinweise und typische Finance-Loads',
                    'url' => locale_route('suppliers.show', ['slug' => 'sap-s4hana']),
                ],
                [
                    'title' => 'Vendor Resources & Zertifikate',
                    'group' => 'resources',
                    'reason' => 'sammelt offizielle Doku, Lernpfade und Nachweise für Fabric und Governance',
                    'url' => locale_route('resources.index'),
                ],
            ],
        ];
    }
}
