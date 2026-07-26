<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GovernanceAdvisoryToolController extends Controller
{
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
            'kpi-requirements-intake' => 'Dieser Report entscheidet, ob eine KPI fachlich belastbar genug ist: Business-Frage, Formel, Grain, Owner, Dimensionen und Akzeptanzbeispiel müssen zusammenpassen, bevor ein Measure oder Mart gebaut wird.',
            'source-scope-builder' => 'Dieser Report entscheidet, welche Quellobjekte wirklich geladen werden: Must-have, optional, skip, PII/DSDR und Owner werden als Load-Scope und Review-Basis dokumentiert.',
            'mart-design-brief-generator' => 'Dieser Report entscheidet, ob aus KPI und Source Scope ein sauberes Mart-Design ableitbar ist: Fact-Kandidat, Dimensionen, Grain, Historie, DQ-Gates und Owner werden zusammen bewertet.',
            'governance-stack-advisor' => 'Dieser Report entscheidet, welcher Stack zur Ausgangslage passt: Zielbild, vorhandene Plattform, Team-Skills, Betrieb, Residency, Governance-Reife und Zertifikate werden zur Shortlist verdichtet.',
            'pii-dsdr-readiness-checker' => 'Dieser Report entscheidet, ob eine Datenquelle verantwortbar verarbeitet werden kann: Identifier, Freitext, Kopien, Retention, DSDR-Suchpfade und Access Controls werden als Gate geprüft.',
            'decision-brief-generator' => 'Dieser Report verdichtet die Discovery in eine entscheidbare Vorlage: Kontext, Option, Annahmen, Risiken, offene Fragen, Pilot-Scope und nächste Plan-Aufgaben werden zusammengeführt.',
            'vendor-learning-path-builder' => 'Dieser Report entscheidet, welche Doku, Lernpfade und Zertifikate für Rolle, Stack und Projektziel relevant sind, damit Lernen direkt an Umsetzung und Governance-Nachweise gekoppelt bleibt.',
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
                'title' => ['de' => 'KPI Requirements Intake Form', 'en' => 'KPI Requirements Intake Form'],
                'lead' => [
                    'de' => 'Sammelt KPI-Anforderungen so, dass daraus Definition, Grain, Owner, Source-Felder und erste Mart-Fragen entstehen.',
                    'en' => 'Collect KPI requirements so definition, grain, owner, source fields, and first mart questions become clear.',
                ],
                'icon' => 'fa-gauge-high',
                'question' => ['de' => 'Welche KPI wollen wir wirklich entscheiden?', 'en' => 'Which KPI are we really deciding with?'],
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
                'title' => ['de' => 'Source Scope Builder', 'en' => 'Source Scope Builder'],
                'lead' => [
                    'de' => 'Bereitet eine Quellanbindung vor: must-have Objekte, optionale Tabellen, Skip-Ballast, PII/DSDR und KPI-Nutzen.',
                    'en' => 'Prepare a source load: must-have objects, optional tables, skip ballast, PII/DSDR, and KPI usefulness.',
                ],
                'icon' => 'fa-database',
                'question' => ['de' => 'Was laden wir aus dieser Quelle und was bewusst nicht?', 'en' => 'What do we load from this source, and what do we deliberately skip?'],
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
                'title' => ['de' => 'Mart Design Brief Generator', 'en' => 'Mart Design Brief Generator'],
                'lead' => [
                    'de' => 'Übersetzt KPI Cards und Source Scope in ein kompaktes Briefing für Facts, Dimensions, Grain, History und Tests.',
                    'en' => 'Translate KPI cards and source scope into a compact brief for facts, dimensions, grain, history, and tests.',
                ],
                'icon' => 'fa-table-columns',
                'question' => ['de' => 'Welche Tabellenstruktur trägt diese KPI sauber?', 'en' => 'Which table structure carries this KPI cleanly?'],
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
                'title' => ['de' => 'Governance Stack Advisor', 'en' => 'Governance Stack Advisor'],
                'lead' => [
                    'de' => 'Führt von Cloud-, BI-, Catalog-, Residency- und Skill-Fragen zu einer belastbaren Stack-Shortlist.',
                    'en' => 'Move from cloud, BI, catalog, residency, and skill questions to a defensible stack shortlist.',
                ],
                'icon' => 'fa-layer-group',
                'question' => ['de' => 'Welcher Governance Stack passt zu Ziel, Team und Risiko?', 'en' => 'Which governance stack fits the goal, team, and risk?'],
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
                'title' => ['de' => 'PII/DSDR Readiness Checker', 'en' => 'PII/DSDR Readiness Checker'],
                'lead' => [
                    'de' => 'Prüft vor dem Load Personenbezug, Freitext, Kopien, DSDR-Suchkeys, Retention und Governance Gates.',
                    'en' => 'Check personal data, free text, copies, DSDR search keys, retention, and governance gates before loading.',
                ],
                'icon' => 'fa-shield-halved',
                'question' => ['de' => 'Können wir diese Daten verantwortbar laden und wiederfinden?', 'en' => 'Can we load and find this data responsibly?'],
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
                'title' => ['de' => 'Decision Brief Generator', 'en' => 'Decision Brief Generator'],
                'lead' => [
                    'de' => 'Verdichtet Discovery-Ergebnisse zu einer kompakten Entscheidungsvorlage für Sponsor, Architekturboard oder ersten Sprint.',
                    'en' => 'Condense discovery results into a compact decision brief for sponsor, architecture board, or first sprint.',
                ],
                'icon' => 'fa-file-signature',
                'question' => ['de' => 'Welche Entscheidung treffen wir jetzt und was bleibt offen?', 'en' => 'Which decision do we make now, and what remains open?'],
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
                'title' => ['de' => 'Vendor Learning Path Builder', 'en' => 'Vendor Learning Path Builder'],
                'lead' => [
                    'de' => 'Ordnet offizielle Lernpfade, Zertifikate, Playbooks und Übungen nach Rolle und Stack.',
                    'en' => 'Organize official learning paths, certifications, playbooks, and exercises by role and stack.',
                ],
                'icon' => 'fa-graduation-cap',
                'question' => ['de' => 'Was sollte ich für diese Rolle und diesen Stack lernen?', 'en' => 'What should I learn for this role and stack?'],
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
