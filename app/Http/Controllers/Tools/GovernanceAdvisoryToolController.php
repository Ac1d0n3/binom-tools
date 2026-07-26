<?php

namespace App\Http\Controllers\Tools;

use App\Governance\GovernanceDemoWorkspace;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GovernanceAdvisoryToolController extends Controller
{
    public function __construct(private readonly GovernanceDemoWorkspace $demo) {}

    public function show(Request $request): View
    {
        $toolId = $request->route('toolId');
        if (! is_string($toolId) || ! isset($this->tools()[$toolId])) {
            throw new NotFoundHttpException;
        }

        return view('tools.governance-advisory.show', [
            'toolId' => $toolId,
            'tool' => $this->tools()[$toolId],
            'templateGuides' => $this->templateGuides()[$toolId] ?? [],
            'reportSummary' => $this->reportSummaries()[$toolId] ?? '',
            'inputExplanations' => $this->inputExplanations(),
            'outputExplanations' => $this->outputExplanations(),
            'fieldLabels' => $this->fieldLabels(),
            'demoPrefill' => $request->query('demo') === 'finance' ? $this->demo->toolPrefill($toolId) : null,
        ]);
    }

    /**
     * @return array<string, list<array{placeholder: string, help: string}>>
     */
    private function templateGuides(): array
    {
        return [
            'kpi-requirements-intake' => [
                ['placeholder' => 'Net Revenue', 'help' => 'Name der Kennzahl, wie Business und Reports sie verwenden.'],
                ['placeholder' => 'Welche Umsatzentwicklung soll monatlich entschieden werden?', 'help' => 'Die konkrete Geschäftsfrage, die diese KPI beantworten soll.'],
                ['placeholder' => 'Priorisierung von Umsatzmaßnahmen und Monatsabschluss-Review', 'help' => 'Welche Entscheidung wird mit dieser KPI besser oder schneller getroffen?'],
                ['placeholder' => 'Rechnungsbetrag minus Gutschriften und Stornos', 'help' => 'Fachliche Rechenlogik in Worten, inklusive Ausschlüssen und Sonderfällen.'],
                ['placeholder' => 'Firma, Kunde, Rechnungsmonat', 'help' => 'Ebene, auf der die KPI eindeutig ist. Das steuert Fact-Grain und Aggregation.'],
                ['placeholder' => 'Buchungsdatum; Monatsabschluss M+3; Stornos rückwirkend', 'help' => 'Datum, Periodenlogik, Aktualisierung und Rückwirkungsregeln.'],
                ['placeholder' => 'Firma, Kunde, Region, Produktgruppe', 'help' => 'Dimensionen, nach denen die KPI später geschnitten werden muss.'],
                ['placeholder' => 'Finance Owner entscheidet, BI Lead setzt um', 'help' => 'Wer definiert, wer genehmigt und wer Konflikte lösen darf.'],
                ['placeholder' => 'Beleg 4711 ergibt 1.250 EUR Net Revenue im Monat 2026-01', 'help' => 'Konkretes Beispiel, mit dem Fachbereich und Engineering dieselbe Logik prüfen können.'],
            ],
            'source-scope-builder' => [
                ['placeholder' => 'SAP S/4HANA Finance', 'help' => 'System, Supplier oder Datenprodukt, aus dem geladen werden soll.'],
                ['placeholder' => 'Finance Mart für Umsatz und offene Forderungen', 'help' => 'Wofür die Quelle benötigt wird, nicht nur technische Herkunft.'],
                ['placeholder' => 'Fakturabelege, Kunden, Buchungskreis, offene Posten', 'help' => 'Objekte ohne die der Use Case nicht funktioniert.'],
                ['placeholder' => 'Kundenaufträge, Kostenstellen', 'help' => 'Nützlich, aber nicht blockierend für den ersten Scope.'],
                ['placeholder' => 'Anhänge, lange Freitexte, Altdaten vor 2023', 'help' => 'Bewusst nicht laden, mit Grund. Das reduziert Risiko und Aufwand.'],
                ['placeholder' => 'E-Mail Rechnungskontakt, Ansprechpartnername', 'help' => 'Personenbezogene Felder oder Felder mit möglichem Personenbezug.'],
                ['placeholder' => 'customer_id, contact_email, employee_id', 'help' => 'Schlüssel, mit denen Auskunft/Löschung über alle Kopien möglich bleibt.'],
                ['placeholder' => 'täglich 06:00, initial 3 Jahre Historie', 'help' => 'Refresh, Historie und Latenzanforderung für Load-Design und DQ.'],
                ['placeholder' => 'Finance Owner, SAP Platform Owner', 'help' => 'Fachlicher und technischer Owner für Freigabe und Betrieb.'],
            ],
            'mart-design-brief-generator' => [
                ['placeholder' => 'finance_revenue_mart', 'help' => 'Sprechender Mart-Name für Zweck, Domain und späteres Ownership.'],
                ['placeholder' => 'Eine Zeile pro Firma, Kunde, Rechnungsmonat', 'help' => 'Primärer Fact-Grain. Alle Measures müssen dazu passen.'],
                ['placeholder' => 'Net Revenue, Open Receivables, Invoice Count', 'help' => 'Measures, die im Mart berechnet oder bereitgestellt werden.'],
                ['placeholder' => 'Firma, Kunde, Region, Produktgruppe, Zeitraum', 'help' => 'Dimensionen und Attribute für Analyse und Berechtigungen.'],
                ['placeholder' => 'SAP Faktura, FI-AR offene Posten, Kundenstamm', 'help' => 'Quellobjekte, die das Mart-Design tragen.'],
                ['placeholder' => 'SCD2 für Kundenregion, Snapshot monatlich für offene Posten', 'help' => 'Wie Historie und Änderungen fachlich sichtbar bleiben.'],
                ['placeholder' => 'not null business keys, freshness < 24h, revenue reconciliation', 'help' => 'Tests und Gates, die Build, Release oder Report-Freigabe blockieren dürfen.'],
                ['placeholder' => 'Analytics Engineering Lead + Finance Owner', 'help' => 'Owner für Datenmodell, fachliche Definition und Änderung.'],
            ],
            'governance-stack-advisor' => [
                ['placeholder' => 'Microsoft Fabric + Purview + Power BI', 'help' => 'Stack-Option oder Shortlist-Kandidat.'],
                ['placeholder' => 'Bestehende Microsoft-Lizenzen, Power BI Nutzung, EU Residency', 'help' => 'Warum diese Option zur Organisation und Ausgangslage passt.'],
                ['placeholder' => 'Skill-Lücke bei Lakehouse-Betrieb, unklare Catalog-Ownership', 'help' => 'Risiken, die vor Entscheidung oder Pilot geklärt werden müssen.'],
                ['placeholder' => 'Fabric Engineering, Data Modeling, Purview Governance', 'help' => 'Fähigkeiten, die intern oder extern gebraucht werden.'],
                ['placeholder' => 'DP-600, PL-300, Purview Lernpfad', 'help' => 'Zertifikate oder offizielle Lernpfade als Nachweis und Lernziel.'],
                ['placeholder' => 'Wer betreibt Pipelines? Wer genehmigt PII? Wie wird Kostenkontrolle gemacht?', 'help' => 'Offene Architektur- und Betriebsentscheidungen.'],
                ['placeholder' => 'Finance Mart mit SAP S/4HANA, 2 KPIs, 1 Power BI Dashboard', 'help' => 'Kleiner Scope, mit dem die Stack-Entscheidung realistisch geprüft wird.'],
            ],
            'pii-dsdr-readiness-checker' => [
                ['placeholder' => 'customer_contact_extract', 'help' => 'Datensatz, Tabelle, View oder Export, der geprüft wird.'],
                ['placeholder' => 'Kunde, Mitarbeiter, Bewerber, Lieferantenkontakt', 'help' => 'Welche Personengruppe ist betroffen? Das steuert Risiko und Rechte.'],
                ['placeholder' => 'customer_id, email, phone, employee_id', 'help' => 'Direkte oder indirekte Identifier.'],
                ['placeholder' => 'E-Mail, Name, Telefonnummer, Freitextnotiz', 'help' => 'Felder mit Schutzbedarf oder sensiblen Inhalten.'],
                ['placeholder' => 'Raw Lakehouse, Curated View, Power BI Dataset, Excel Export', 'help' => 'Alle Kopien, in denen die Daten später wiedergefunden werden müssen.'],
                ['placeholder' => 'customer_id + email für Suche über Raw, Mart und BI', 'help' => 'Suchpfad für Auskunft/Löschung über technische Schichten hinweg.'],
                ['placeholder' => '24 Monate im Mart, Raw nach 90 Tagen löschen', 'help' => 'Aufbewahrung und Löschlogik je Schicht.'],
                ['placeholder' => 'Maskierung, Row-Level Security, Export blockieren, Owner-Approval', 'help' => 'Technische und organisatorische Kontrollen vor dem Load.'],
            ],
            'decision-brief-generator' => [
                ['placeholder' => 'Fabric Finance Mart für ersten Pilot freigeben', 'help' => 'Die konkrete Entscheidung, die Sponsor oder Architekturboard treffen soll.'],
                ['placeholder' => 'Vorhandene SAP-Quelle, kritische Finance Reports, DQ-Probleme im Monatsabschluss', 'help' => 'Ausgangslage und warum jetzt entschieden werden muss.'],
                ['placeholder' => 'Pilot mit SAP S/4HANA Finance, Fabric Lakehouse und Power BI starten', 'help' => 'Empfohlene Option mit klarem Warum.'],
                ['placeholder' => '2 KPIs, 3 Quellobjekte, 1 Dashboard, kein Full ERP Load', 'help' => 'Was ist im ersten Schritt enthalten und was bewusst nicht?'],
                ['placeholder' => 'PII-Masking offen, Owner-Zeit knapp, Storno-Logik nicht final', 'help' => 'Risiken, die Entscheidung oder Umsetzung beeinflussen.'],
                ['placeholder' => 'Wer genehmigt KPI-Definition? Welche DQ-Gates sind release-blocking?', 'help' => 'Fragen, die noch vor Build oder Go-live beantwortet werden müssen.'],
                ['placeholder' => 'Source Scope finalisieren, DQ-Regeln anlegen, Mart Design Brief abstimmen', 'help' => 'Konkrete nächste Aufgaben für den Plan.'],
            ],
            'vendor-learning-path-builder' => [
                ['placeholder' => 'Analytics Engineer', 'help' => 'Rolle, für die Lernen und Nachweise geplant werden.'],
                ['placeholder' => 'Microsoft Fabric + dbt + Power BI', 'help' => 'Technologie-Stack, der im Projekt wirklich genutzt wird.'],
                ['placeholder' => 'Woche 1: Grundlagen, Workspace, Lakehouse, Governance-Begriffe', 'help' => 'Erste Woche: Orientierung, Setup und gemeinsame Sprache.'],
                ['placeholder' => 'Woche 2: Pipelines, SQL, DQ-Regeln, erste KPI', 'help' => 'Zweite Woche: praktische Projektaufgaben.'],
                ['placeholder' => 'Woche 3: Security, PII, Catalog, Monitoring', 'help' => 'Dritte Woche: Governance und Betriebsfähigkeit.'],
                ['placeholder' => 'Woche 4: Review, Zertifikatsvorbereitung, Demo-Projekt', 'help' => 'Vierte Woche: Nachweis, Wiederholung und Transfer in echte Arbeit.'],
                ['placeholder' => 'DP-600, PL-300, Databricks Data Engineer Associate', 'help' => 'Offizielle Zertifikate oder Lernpfade, die zum Stack passen.'],
                ['placeholder' => 'KPI Card bauen, Source Scope prüfen, DQ Rule Generator nutzen', 'help' => 'Konkrete Übungen in binom-tools oder Projektumgebung.'],
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function reportSummaries(): array
    {
        return [
            'kpi-requirements-intake' => 'Dieser Report prüft, ob eine KPI als Entscheidungsgrundlage taugt: Geschäftsfrage, Formel, Grain, Owner, Dimensionen und Akzeptanzbeispiel müssen zusammenpassen, bevor ein Measure oder Mart gebaut wird.',
            'source-scope-builder' => 'Dieser Report klärt den ersten Load-Scope: Must-have-Objekte, optionale Tabellen, bewusste Ausschlüsse, PII/DSDR und Owner werden als Review-Basis dokumentiert.',
            'mart-design-brief-generator' => 'Dieser Report prüft, ob aus KPI und Source Scope ein sauberes Mart-Design ableitbar ist: Fact-Kandidat, Dimensionen, Grain, Historie, DQ-Gates und Owner werden zusammen bewertet.',
            'governance-stack-advisor' => 'Dieser Report verdichtet Zielbild, vorhandene Plattform, Team-Skills, Betrieb, Residency, Governance-Reife und Zertifikate zu einer begründeten Stack-Shortlist.',
            'pii-dsdr-readiness-checker' => 'Dieser Report prüft, ob eine Datenquelle verantwortbar verarbeitet werden kann: Identifier, Freitext, Kopien, Retention, DSDR-Suchpfade und Zugriffskontrollen werden als Gate bewertet.',
            'decision-brief-generator' => 'Dieser Report verdichtet die Discovery in eine entscheidbare Vorlage: Kontext, Option, Annahmen, Risiken, offene Fragen, Pilot-Scope und nächste Plan-Aufgaben werden zusammengeführt.',
            'vendor-learning-path-builder' => 'Dieser Report ordnet Doku, Lernpfade, Zertifikate und Praxisaufgaben passend zu Rolle, Stack und Projektziel, damit Lernen direkt mit Umsetzung und Governance-Nachweisen verbunden bleibt.',
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function fieldLabels(): array
    {
        return [
            'KPI' => ['de' => 'KPI', 'en' => 'KPI'],
            'Business question' => ['de' => 'Geschäftsfrage', 'en' => 'Business question'],
            'KPI name' => ['de' => 'KPI-Name', 'en' => 'KPI name'],
            'Decision supported' => ['de' => 'Unterstützte Entscheidung', 'en' => 'Decision supported'],
            'Formula' => ['de' => 'Formel', 'en' => 'Formula'],
            'Formula in words' => ['de' => 'Formel in Worten', 'en' => 'Formula in words'],
            'Grain' => ['de' => 'Grain / fachliche Ebene', 'en' => 'Grain'],
            'Time logic' => ['de' => 'Zeitlogik', 'en' => 'Time logic'],
            'Dimensions' => ['de' => 'Dimensionen', 'en' => 'Dimensions'],
            'Owner / approver' => ['de' => 'Owner / Freigabe', 'en' => 'Owner / approver'],
            'Acceptance example' => ['de' => 'Akzeptanzbeispiel', 'en' => 'Acceptance example'],
            'Supplier' => ['de' => 'Quelle / Supplier', 'en' => 'Supplier'],
            'Target KPIs' => ['de' => 'Ziel-KPIs', 'en' => 'Target KPIs'],
            'Required dimensions' => ['de' => 'Benötigte Dimensionen', 'en' => 'Required dimensions'],
            'Refresh cadence' => ['de' => 'Aktualisierungstakt', 'en' => 'Refresh cadence'],
            'History need' => ['de' => 'Historienbedarf', 'en' => 'History need'],
            'PII risk' => ['de' => 'PII-Risiko', 'en' => 'PII risk'],
            'System owner' => ['de' => 'System-Owner', 'en' => 'System owner'],
            'Use case' => ['de' => 'Use Case', 'en' => 'Use case'],
            'Must-have entities' => ['de' => 'Pflichtobjekte', 'en' => 'Must-have entities'],
            'Optional entities' => ['de' => 'Optionale Objekte', 'en' => 'Optional entities'],
            'Skip entities' => ['de' => 'Bewusst ausgeschlossene Objekte', 'en' => 'Skip entities'],
            'PII fields' => ['de' => 'PII-Felder', 'en' => 'PII fields'],
            'DSDR keys' => ['de' => 'DSDR-Suchschlüssel', 'en' => 'DSDR keys'],
            'Refresh' => ['de' => 'Aktualisierung', 'en' => 'Refresh'],
            'Owner' => ['de' => 'Owner', 'en' => 'Owner'],
            'Mart name' => ['de' => 'Mart-Name', 'en' => 'Mart name'],
            'KPI cards' => ['de' => 'KPI-Karten', 'en' => 'KPI cards'],
            'Source scope' => ['de' => 'Quellen-Scope', 'en' => 'Source scope'],
            'Business grain' => ['de' => 'Fachlicher Grain', 'en' => 'Business grain'],
            'SCD/history need' => ['de' => 'SCD-/Historienbedarf', 'en' => 'SCD/history need'],
            'Target platform' => ['de' => 'Zielplattform', 'en' => 'Target platform'],
            'BI tool' => ['de' => 'BI-Tool', 'en' => 'BI tool'],
            'Primary fact grain' => ['de' => 'Primärer Fact-Grain', 'en' => 'Primary fact grain'],
            'Measures' => ['de' => 'Measures', 'en' => 'Measures'],
            'Source entities' => ['de' => 'Quellobjekte', 'en' => 'Source entities'],
            'History strategy' => ['de' => 'Historisierungsstrategie', 'en' => 'History strategy'],
            'DQ gates' => ['de' => 'DQ-Gates', 'en' => 'DQ gates'],
            'Candidate stack' => ['de' => 'Stack-Kandidat', 'en' => 'Candidate stack'],
            'Cloud preference' => ['de' => 'Cloud-Präferenz', 'en' => 'Cloud preference'],
            'Data residency' => ['de' => 'Data Residency', 'en' => 'Data residency'],
            'Catalog maturity' => ['de' => 'Catalog-Reife', 'en' => 'Catalog maturity'],
            'Security pressure' => ['de' => 'Security-/Compliance-Druck', 'en' => 'Security pressure'],
            'Team skills' => ['de' => 'Team-Skills', 'en' => 'Team skills'],
            'Budget model' => ['de' => 'Budgetmodell', 'en' => 'Budget model'],
            'Why it fits' => ['de' => 'Warum passend', 'en' => 'Why it fits'],
            'Risks' => ['de' => 'Risiken', 'en' => 'Risks'],
            'Required skills' => ['de' => 'Benötigte Skills', 'en' => 'Required skills'],
            'Certifications' => ['de' => 'Zertifikate', 'en' => 'Certifications'],
            'Open decisions' => ['de' => 'Offene Entscheidungen', 'en' => 'Open decisions'],
            'Pilot scope' => ['de' => 'Pilot-Scope', 'en' => 'Pilot scope'],
            'Dataset' => ['de' => 'Datensatz', 'en' => 'Dataset'],
            'Person type' => ['de' => 'Personengruppe', 'en' => 'Person type'],
            'Identifiers' => ['de' => 'Identifier', 'en' => 'Identifiers'],
            'Free text' => ['de' => 'Freitext', 'en' => 'Free text'],
            'Attachments' => ['de' => 'Anhänge', 'en' => 'Attachments'],
            'Warehouse copies' => ['de' => 'Warehouse-Kopien', 'en' => 'Warehouse copies'],
            'BI extracts' => ['de' => 'BI-Exporte', 'en' => 'BI extracts'],
            'Sensitive fields' => ['de' => 'Schutzbedürftige Felder', 'en' => 'Sensitive fields'],
            'Copies' => ['de' => 'Kopien', 'en' => 'Copies'],
            'Retention' => ['de' => 'Retention / Aufbewahrung', 'en' => 'Retention'],
            'Controls' => ['de' => 'Kontrollen', 'en' => 'Controls'],
            'Decision' => ['de' => 'Entscheidung', 'en' => 'Decision'],
            'Stakeholders' => ['de' => 'Stakeholder', 'en' => 'Stakeholders'],
            'Risk backlog' => ['de' => 'Risiko-Backlog', 'en' => 'Risk backlog'],
            'Architecture fit' => ['de' => 'Architektur-Fit', 'en' => 'Architecture fit'],
            'Impact/effort' => ['de' => 'Impact/Effort', 'en' => 'Impact/effort'],
            'Assumptions' => ['de' => 'Annahmen', 'en' => 'Assumptions'],
            'Context' => ['de' => 'Kontext', 'en' => 'Context'],
            'Recommended option' => ['de' => 'Empfohlene Option', 'en' => 'Recommended option'],
            'Open questions' => ['de' => 'Offene Fragen', 'en' => 'Open questions'],
            'Next sprint' => ['de' => 'Nächster Sprint', 'en' => 'Next sprint'],
            'Role' => ['de' => 'Rolle', 'en' => 'Role'],
            'Stack' => ['de' => 'Stack', 'en' => 'Stack'],
            'Experience level' => ['de' => 'Erfahrungsstand', 'en' => 'Experience level'],
            'Target certification' => ['de' => 'Ziel-Zertifizierung', 'en' => 'Target certification'],
            'Project goal' => ['de' => 'Projektziel', 'en' => 'Project goal'],
            'Available time' => ['de' => 'Verfügbare Lernzeit', 'en' => 'Available time'],
            'Week 1' => ['de' => 'Woche 1', 'en' => 'Week 1'],
            'Week 2' => ['de' => 'Woche 2', 'en' => 'Week 2'],
            'Week 3' => ['de' => 'Woche 3', 'en' => 'Week 3'],
            'Week 4' => ['de' => 'Woche 4', 'en' => 'Week 4'],
            'Official certifications' => ['de' => 'Offizielle Zertifikate', 'en' => 'Official certifications'],
            'Practice tasks' => ['de' => 'Praxisaufgaben', 'en' => 'Practice tasks'],
            'KPI Card' => ['de' => 'KPI-Karte', 'en' => 'KPI Card'],
            'Fact/dimension hints' => ['de' => 'Fact-/Dimension-Hinweise', 'en' => 'Fact/dimension hints'],
            'DQ rule candidates' => ['de' => 'DQ-Regelkandidaten', 'en' => 'DQ rule candidates'],
            'Must-have load scope' => ['de' => 'Pflicht-Scope für den Load', 'en' => 'Must-have load scope'],
            'Optional scope' => ['de' => 'Optionaler Scope', 'en' => 'Optional scope'],
            'Skip list' => ['de' => 'Skip-Liste', 'en' => 'Skip list'],
            'PII/DSDR watchlist' => ['de' => 'PII/DSDR-Watchlist', 'en' => 'PII/DSDR watchlist'],
            'Review questions' => ['de' => 'Review-Fragen', 'en' => 'Review questions'],
            'Fact candidate' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
            'Dimension candidates' => ['de' => 'Dimensionskandidaten', 'en' => 'Dimension candidates'],
            'Grain statement' => ['de' => 'Grain-Aussage', 'en' => 'Grain statement'],
            'Measure location' => ['de' => 'Ort der Measure-Logik', 'en' => 'Measure location'],
            'DQ tests' => ['de' => 'DQ-Tests', 'en' => 'DQ tests'],
            'schema.yml hints' => ['de' => 'schema.yml-Hinweise', 'en' => 'schema.yml hints'],
            'Stack shortlist' => ['de' => 'Stack-Shortlist', 'en' => 'Stack shortlist'],
            'Decision risks' => ['de' => 'Entscheidungsrisiken', 'en' => 'Decision risks'],
            'Learning path' => ['de' => 'Lernpfad', 'en' => 'Learning path'],
            'Certification links' => ['de' => 'Zertifikatslinks', 'en' => 'Certification links'],
            'Next tools' => ['de' => 'Nächste Tools', 'en' => 'Next tools'],
            'PII watchlist' => ['de' => 'PII-Watchlist', 'en' => 'PII watchlist'],
            'DSDR search path' => ['de' => 'DSDR-Suchpfad', 'en' => 'DSDR search path'],
            'Risk heatmap' => ['de' => 'Risiko-Heatmap', 'en' => 'Risk heatmap'],
            'Policy questions' => ['de' => 'Policy-Fragen', 'en' => 'Policy questions'],
            'Gate checklist' => ['de' => 'Gate-Checkliste', 'en' => 'Gate checklist'],
            'One-page decision brief' => ['de' => 'Einseitige Entscheidungsvorlage', 'en' => 'One-page decision brief'],
            'First sprint candidates' => ['de' => 'Kandidaten für den ersten Sprint', 'en' => 'First sprint candidates'],
            'Risk notes' => ['de' => 'Risikohinweise', 'en' => 'Risk notes'],
            '30-day learning path' => ['de' => '30-Tage-Lernpfad', 'en' => '30-day learning path'],
            'Official links' => ['de' => 'Offizielle Links', 'en' => 'Official links'],
            'Practice tools' => ['de' => 'Praxis-Tools', 'en' => 'Practice tools'],
            'Playbook reading list' => ['de' => 'Playbook-Leseliste', 'en' => 'Playbook reading list'],
            'Certification notes' => ['de' => 'Zertifikatshinweise', 'en' => 'Certification notes'],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function inputExplanations(): array
    {
        return [
            'Acceptance example' => 'Ein konkreter Beispieldatensatz, mit dem Fachbereich und Engineering dieselbe Regel prüfen.',
            'Attachments' => 'Dateien, Bilder oder Exporte, die PII, Verträge oder operative Risiken enthalten können.',
            'BI extracts' => 'Reports, Datasets, Excel-Exporte oder Abos, in denen Daten weiterverteilt werden.',
            'BI tool' => 'Das Zielwerkzeug beeinflusst Semantic Layer, Security, Measures und Freigabeprozess.',
            'Budget model' => 'Kosten- und Lizenzmodell, damit die Stack-Entscheidung betrieblich tragfähig bleibt.',
            'Business grain' => 'Die fachliche Ebene, auf der eine Kennzahl eindeutig ist und sauber aggregiert werden kann.',
            'Business question' => 'Die Frage, die später im Report beantwortet werden soll, nicht nur der Name einer Kennzahl.',
            'Catalog maturity' => 'Wie weit Glossar, Ownership, Lineage, Klassifikation und Policies schon nutzbar sind.',
            'Cloud preference' => 'Vorgaben zu Cloud, Tenant, Region, Netzwerk, Betrieb und vorhandenen Lizenzen.',
            'Data residency' => 'Regionale oder rechtliche Vorgaben für Speicherung, Verarbeitung und Zugriff.',
            'Dimensions' => 'Analyseachsen wie Kunde, Region, Produkt, Zeit oder Organisation.',
            'Formula in words' => 'Fachliche Rechenlogik inklusive Ausschlüssen, Stornos, Filtern und Sonderfällen.',
            'Free text' => 'Unstrukturierte Felder mit möglichem Personenbezug, sensiblen Inhalten oder Löschrisiken.',
            'History need' => 'Wie weit Historie, Snapshots oder SCD-Logik für Entscheidungen benötigt werden.',
            'Identifiers' => 'Schlüssel und Felder, mit denen Personen oder Entitäten wiedergefunden werden.',
            'KPI cards' => 'Bereits abgestimmte KPI-Definitionen, aus denen Mart-Grain und Measures entstehen.',
            'KPI name' => 'Der Name, den Fachbereich, BI und Dokumentation später identisch verwenden.',
            'Owner' => 'Person oder Rolle, die Definition, Freigabe und spätere Änderungen verantwortet.',
            'Person type' => 'Betroffene Gruppe wie Kunde, Mitarbeiter, Bewerber oder Lieferantenkontakt.',
            'PII risk' => 'Personenbezug, Freitext, Löschpflicht, Zugriff oder Weitergabe als frühes Gate.',
            'Required dimensions' => 'Dimensionen, ohne die KPI, Filter oder Berechtigungen nicht funktionieren.',
            'Refresh cadence' => 'Aktualität, Ladefrequenz und Latenz, die Load-Design und DQ-Regeln steuern.',
            'Retention' => 'Aufbewahrung und Löschung je Schicht, Export und Report.',
            'SCD/history need' => 'Ob Änderungen historisiert, überschrieben oder als Snapshot gespeichert werden.',
            'Security pressure' => 'Regulatorik, PII, interne Freigaben, Rollenmodell und Audit-Anforderungen.',
            'Source scope' => 'Welche Quellobjekte den ersten belastbaren Mart oder Report wirklich tragen.',
            'Supplier' => 'System, Anbieter oder Datenprodukt, aus dem Informationen kommen.',
            'System owner' => 'Technischer und fachlicher Ansprechpartner für Zugriff, Betrieb und Freigabe.',
            'Target KPIs' => 'Kennzahlen, die mit dieser Quelle oder diesem Mart entschieden werden sollen.',
            'Target platform' => 'Plattform, auf der Modell, Tests, Security und Betrieb umgesetzt werden.',
            'Team skills' => 'Fähigkeiten, die intern vorhanden sind oder durch Partner/Lernen ergänzt werden müssen.',
            'Warehouse copies' => 'Raw, Curated, Mart, Semantic Layer und Exporte, in denen Daten erneut auftauchen.',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function outputExplanations(): array
    {
        return [
            'Certification links' => 'Lern- und Nachweispfade, die zur Stack-Entscheidung und Rolle passen.',
            'Decision risks' => 'Punkte, die vor Pilot, Umsetzung oder Freigabe geklärt werden müssen.',
            'Dimension candidates' => 'Tabellen oder Attribute, die als Dimensionen in Frage kommen.',
            'DQ rule candidates' => 'Erste Regeln für Vollständigkeit, Aktualität, Wertebereich oder Business-Logik.',
            'DQ tests' => 'Konkrete Checks, die Build, Release oder Report-Freigabe absichern.',
            'Fact candidate' => 'Vorschlag für die zentrale Faktentabelle mit passendem Grain.',
            'Fact/dimension hints' => 'Hinweise, wie KPI und Quellen später modelliert werden können.',
            'Grain statement' => 'Ein Satz, der eindeutig beschreibt, was eine Zeile bedeutet.',
            'KPI Card' => 'Saubere Kennzahlendefinition mit Zweck, Formel, Grain, Owner und Beispiel.',
            'Learning path' => 'Reihenfolge aus Doku, Übungen, Zertifikaten und Projektaufgaben.',
            'Measure location' => 'Entscheidung, ob Logik in Quelle, Transformation, Mart oder BI liegt.',
            'Must-have load scope' => 'Objekte, ohne die der erste Use Case nicht sinnvoll funktioniert.',
            'Next tools' => 'Passende Folgewerkzeuge, um Lücken weiter zu bearbeiten.',
            'Open questions' => 'Klärpunkte für Stakeholder, Datenschutz, Security, Architektur oder Betrieb.',
            'Optional scope' => 'Hilfreiche Objekte, die nicht den ersten Release blockieren.',
            'PII/DSDR watchlist' => 'Felder, Keys und Kopien, die für Auskunft, Löschung und Zugriff relevant sind.',
            'Review questions' => 'Konkrete Fragen für Owner-Review, Architekturboard oder Fachabnahme.',
            'schema.yml hints' => 'Hinweise für dbt- oder Modell-Dokumentation und Tests.',
            'Skip list' => 'Bewusst ausgeschlossene Objekte mit Grund, um Risiko und Aufwand zu senken.',
            'Stack shortlist' => 'Vergleichbare Optionen mit Begründung, Risiko und nächster Validierung.',
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function tools(): array
    {
        return [
            'kpi-requirements-intake' => [
                'title' => ['de' => 'KPI-Anforderungen erfassen', 'en' => 'KPI Requirements Intake Form'],
                'lead' => [
                    'de' => 'Sammelt KPI-Anforderungen so, dass daraus Definition, Grain, Owner, Source-Felder und erste Mart-Fragen entstehen.',
                    'en' => 'Collect KPI requirements so definition, grain, owner, source fields, and first mart questions become clear.',
                ],
                'icon' => 'fa-gauge-high',
                'question' => ['de' => 'Welche fachliche Entscheidung soll diese KPI unterstützen?', 'en' => 'Which business decision should this KPI support?'],
                'helps' => [
                    'Geschäftsfrage, Entscheidung und KPI-Zweck klären.',
                    'Formel, Grain, Zeitlogik, Filter und Dimensionen erfassen.',
                    'Owner, Approver und Akzeptanzbeispiel sichtbar machen.',
                    'Offene Definitionsfragen markieren, bevor BI gebaut wird.',
                ],
                'inputs' => ['Business question', 'KPI name', 'Formula in words', 'Grain', 'Time logic', 'Dimensions', 'Owner', 'Acceptance example'],
                'outputs' => ['KPI Card', 'Open questions', 'Fact/dimension hints', 'DQ rule candidates'],
                'template' => [
                    'KPI',
                    'Business question',
                    'Decision supported',
                    'Formula',
                    'Grain',
                    'Time logic',
                    'Dimensions',
                    'Owner / approver',
                    'Acceptance example',
                ],
                'links' => [
                    ['route' => 'tools.kpi-definition', 'label' => 'Open KPI Definition Card'],
                    ['route' => 'tools.report-inventory', 'label' => 'Check Report Inventory'],
                ],
            ],
            'source-scope-builder' => [
                'title' => ['de' => 'Quellen-Scope festlegen', 'en' => 'Source Scope Builder'],
                'lead' => [
                    'de' => 'Bereitet eine Quellanbindung vor: Pflichtobjekte, optionale Tabellen, bewusste Ausschlüsse, PII/DSDR und KPI-Nutzen.',
                    'en' => 'Prepare a source load: must-have objects, optional tables, skip ballast, PII/DSDR, and KPI usefulness.',
                ],
                'icon' => 'fa-database',
                'question' => ['de' => 'Welche Daten brauchen wir aus dieser Quelle, und was bleibt bewusst draußen?', 'en' => 'Which data do we need from this source, and what do we deliberately leave out?'],
                'helps' => [
                    'Supplier auswählen und Kernobjekte gegen KPI-Zweck prüfen.',
                    'PII, Freitext, Anhänge und DSDR-Suchkeys früh markieren.',
                    'RAW/Bronze Scope von Curated/Mart Scope trennen.',
                    'Skip-Entscheidungen mit Grund dokumentieren.',
                ],
                'inputs' => ['Supplier', 'Target KPIs', 'Required dimensions', 'Refresh cadence', 'History need', 'PII risk', 'System owner'],
                'outputs' => ['Must-have load scope', 'Optional scope', 'Skip list', 'PII/DSDR watchlist', 'Review questions'],
                'template' => ['Supplier', 'Use case', 'Must-have entities', 'Optional entities', 'Skip entities', 'PII fields', 'DSDR keys', 'Refresh', 'Owner'],
                'links' => [
                    ['route' => 'suppliers.index', 'label' => 'Open Supplier Library'],
                    ['route' => 'tools.meta-export-generator', 'label' => 'Generate metadata export'],
                ],
            ],
            'mart-design-brief-generator' => [
                'title' => ['de' => 'Mart Design Brief erstellen', 'en' => 'Mart Design Brief Generator'],
                'lead' => [
                    'de' => 'Übersetzt KPI-Karten und Source Scope in ein kompaktes Briefing für Facts, Dimensionen, Grain, Historie und Tests.',
                    'en' => 'Translate KPI cards and source scope into a compact brief for facts, dimensions, grain, history, and tests.',
                ],
                'icon' => 'fa-table-columns',
                'question' => ['de' => 'Welche Mart-Struktur bildet diese KPI belastbar ab?', 'en' => 'Which mart structure represents this KPI reliably?'],
                'helps' => [
                    'Fact-Kandidat, Dimensionen und Grain aus KPI-Anforderungen ableiten.',
                    'History/SCD-Bedarf und Snapshot-Fragen markieren.',
                    'Governance-Meta und DQ Tests als Teil des Designs behandeln.',
                    'Analytics Engineering Brief statt Bauchgefühl erzeugen.',
                ],
                'inputs' => ['KPI cards', 'Source scope', 'Business grain', 'Dimensions', 'SCD/history need', 'Target platform', 'BI tool'],
                'outputs' => ['Fact candidate', 'Dimension candidates', 'Grain statement', 'Measure location', 'DQ tests', 'schema.yml hints'],
                'template' => ['Mart name', 'Primary fact grain', 'Measures', 'Dimensions', 'Source entities', 'History strategy', 'DQ gates', 'Owner'],
                'links' => [
                    ['route' => 'tools.kpi-requirements-intake', 'label' => 'Start KPI Intake'],
                    ['route' => 'tools.dbt-dq-rules-generator', 'label' => 'Generate DQ rules'],
                ],
            ],
            'governance-stack-advisor' => [
                'title' => ['de' => 'Governance-Stack auswählen', 'en' => 'Governance Stack Advisor'],
                'lead' => [
                    'de' => 'Führt von Cloud-, BI-, Catalog-, Residency- und Skill-Fragen zu einer belastbaren Stack-Shortlist.',
                    'en' => 'Move from cloud, BI, catalog, residency, and skill questions to a defensible stack shortlist.',
                ],
                'icon' => 'fa-layer-group',
                'question' => ['de' => 'Welcher Governance-Stack passt zu Zielbild, Team und Risiko?', 'en' => 'Which governance stack fits the target architecture, team, and risk?'],
                'helps' => [
                    'Fabric, Databricks, Snowflake/dbt, GCP, SAP und Open Source vergleichbar machen.',
                    'Residency, Zertifikate, BI-Präferenz und Catalog-Reife einordnen.',
                    'Team-Skills und Betriebsmodell als Entscheidungsfaktor nutzen.',
                    'Shortlist und offene Architekturfragen dokumentieren.',
                ],
                'inputs' => ['Cloud preference', 'Data residency', 'BI tool', 'Catalog maturity', 'Security pressure', 'Team skills', 'Budget model'],
                'outputs' => ['Stack shortlist', 'Decision risks', 'Learning path', 'Certification links', 'Next tools'],
                'template' => ['Candidate stack', 'Why it fits', 'Risks', 'Required skills', 'Certifications', 'Open decisions', 'Pilot scope'],
                'links' => [
                    ['route' => 'resources.index', 'label' => 'Open Vendor Resources'],
                    ['route' => 'tools.architecture-fit', 'label' => 'Run Architecture Fit'],
                ],
            ],
            'pii-dsdr-readiness-checker' => [
                'title' => ['de' => 'PII/DSDR Readiness prüfen', 'en' => 'PII/DSDR Readiness Checker'],
                'lead' => [
                    'de' => 'Prüft vor dem Load Personenbezug, Freitext, Kopien, DSDR-Suchkeys, Retention und Governance Gates.',
                    'en' => 'Check personal data, free text, copies, DSDR search keys, retention, and governance gates before loading.',
                ],
                'icon' => 'fa-shield-halved',
                'question' => ['de' => 'Können wir diese Daten verantwortbar laden, schützen und wiederfinden?', 'en' => 'Can we load, protect, and find this data responsibly?'],
                'helps' => [
                    'Direkte Identifier, Quasi-Identifier und Workforce Data markieren.',
                    'DSDR-Suchpfade über RAW, Curated, Mart, BI und Activation sichtbar machen.',
                    'Freitext/Anhänge und Retention als Review-Gates behandeln.',
                    'Policy- und Masking-Entscheidungen vorbereiten.',
                ],
                'inputs' => ['Supplier', 'Person type', 'Identifiers', 'Free text', 'Attachments', 'Warehouse copies', 'BI extracts', 'Retention'],
                'outputs' => ['PII watchlist', 'DSDR search path', 'Risk heatmap', 'Policy questions', 'Gate checklist'],
                'template' => ['Dataset', 'Person type', 'Identifiers', 'Sensitive fields', 'Copies', 'DSDR keys', 'Retention', 'Controls'],
                'links' => [
                    ['route' => 'tools.pii-policy-generator', 'label' => 'Generate PII policy'],
                    ['route' => 'tools.pii-recommend-generator', 'label' => 'Run PII Recommend'],
                ],
            ],
            'decision-brief-generator' => [
                'title' => ['de' => 'Entscheidungsvorlage erstellen', 'en' => 'Decision Brief Generator'],
                'lead' => [
                    'de' => 'Verdichtet Discovery-Ergebnisse zu einer kompakten Entscheidungsvorlage für Sponsor, Architekturboard oder ersten Sprint.',
                    'en' => 'Condense discovery results into a compact decision brief for sponsor, architecture board, or first sprint.',
                ],
                'icon' => 'fa-file-signature',
                'question' => ['de' => 'Welche Entscheidung kann jetzt getroffen werden, und was bleibt offen?', 'en' => 'Which decision can be made now, and what remains open?'],
                'helps' => [
                    'Stakeholder, KPI, Source Scope, Risiken und Impact/Effort zusammenführen.',
                    'Pilot-Scope und Nicht-Ziele sauber abgrenzen.',
                    'Offene Entscheidungen und Annahmen sichtbar machen.',
                    'Sprint Planner oder Projektstart mit einem Briefing füttern.',
                ],
                'inputs' => ['Stakeholders', 'KPI cards', 'Source scope', 'Risk backlog', 'Architecture fit', 'Impact/effort', 'Assumptions'],
                'outputs' => ['One-page decision brief', 'Pilot scope', 'Open decisions', 'First sprint candidates', 'Risk notes'],
                'template' => ['Decision', 'Context', 'Recommended option', 'Pilot scope', 'Risks', 'Open questions', 'Next sprint'],
                'links' => [
                    ['route' => 'tools.impact-effort', 'label' => 'Prioritize impact/effort'],
                    ['route' => 'sprint-planner.index', 'label' => 'Open Sprint Planner'],
                ],
            ],
            'vendor-learning-path-builder' => [
                'title' => ['de' => 'Lern- und Zertifizierungspfad planen', 'en' => 'Vendor Learning Path Builder'],
                'lead' => [
                    'de' => 'Ordnet offizielle Lernpfade, Zertifikate, Playbooks und Übungen nach Rolle und Stack.',
                    'en' => 'Organize official learning paths, certifications, playbooks, and exercises by role and stack.',
                ],
                'icon' => 'fa-graduation-cap',
                'question' => ['de' => 'Welcher Lern- und Zertifizierungspfad passt zu Rolle und Stack?', 'en' => 'Which learning and certification path fits the role and stack?'],
                'helps' => [
                    'Consultant, Engineer, Steward, BI und Privacy/Security Rollen unterscheiden.',
                    'Offizielle Zertifikatslinks mit praktischen Binom-Übungen verbinden.',
                    '30-Tage Lernpfad als realistische Reihenfolge formulieren.',
                    'Zertifikate als Glaubwürdigkeits- und Projektargument einordnen.',
                ],
                'inputs' => ['Role', 'Stack', 'Experience level', 'Target certification', 'Project goal', 'Available time'],
                'outputs' => ['30-day learning path', 'Official links', 'Practice tools', 'Playbook reading list', 'Certification notes'],
                'template' => ['Role', 'Stack', 'Week 1', 'Week 2', 'Week 3', 'Week 4', 'Official certifications', 'Practice tasks'],
                'links' => [
                    ['route' => 'resources.index', 'label' => 'Open certification resources'],
                    ['route' => 'compliance.roadmap', 'label' => 'Open certification roadmap'],
                ],
            ],
        ];
    }
}
