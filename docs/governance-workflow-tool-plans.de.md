# Governance Workflow und Tool Plaene

Stand: 2026-07-26

Ziel: neue Tools sollen nicht einfach weitere Generatoren sein, sondern die vorhandenen Bereiche verbinden: Resources sagen "welche Plattform", Suppliers sagen "welche Quelle", Discovery-Tools sammeln Anforderungen, Generatoren erzeugen Umsetzungshilfen.

Alle neuen Hubs und Tools müssen SEO-ready geplant werden: öffentliche Landingpages sind indexierbar, individuelle Nutzer-Eingaben oder exportierte Arbeitsstände bleiben nicht indexierbar.

## Bestehende Staerken

Schon vorhanden:

- Vendor Resources für Stacks, Help, Governance, Learning, Certifications und Compliance.
- Supplier Library für Quellsysteme, Kernobjekte, Felder, PII/DSDR, Skip-Hinweise und Standard-KPIs.
- Discovery & Assessment Workflow mit Stakeholder Matrix, Report Inventory, KPI Definition, BI Python Toolkit, Architecture Fit und Impact-Effort.
- Governance Generatoren für dbt, PII, DQ, Fabric, Databricks, PureView und AI Sanitizer.

Der größte Hebel ist deshalb ein verbindender Workflow, der aus Workshop-Informationen eine Entscheidung und danach ein Tabellen-/Mart-Design vorbereitet.

## Neuer Workflow: Collect Infos Workflow

Arbeitsname: Governance Discovery Canvas

Zweck:

Ein geführter Ablauf, der die Informationen einsammelt, die man für eine Stack-, Source-, KPI- und Tabellenmodell-Entscheidung braucht.

Schritte:

1. Stakeholder erfassen
   - Sponsor, Data Owner, Steward, Consumer, Security, Privacy, Platform, BI Owner.
   - Output: RACI und Interviewliste.

2. Business-Fragen sammeln
   - Welche Entscheidungen sollen besser werden?
   - Welche Reports existieren?
   - Welche KPIs sind kritisch?
   - Output: priorisierte Frageliste.

3. KPI-Anforderungen strukturieren
   - Name, Definition, Formel, Grain, Zeitlogik, Filter, Dimensionen, Owner, Akzeptanzbeispiel.
   - Output: KPI Cards.

4. Quellen zuordnen
   - Supplier/Product, relevante Entitäten, System Owner, Zugriff, Datenfrequenz.
   - Output: Source Scope.

5. Risiko erfassen
   - PII, besondere Kategorien, Freitext, Anhange, Workforce Data, DSDR-Suchkeys, Retention.
   - Output: PII/DSDR Review Sheet.

6. Datenqualität definieren
   - Pflichtfelder, Business Keys, Freshness, Referenzen, erlaubte Werte, Duplikate.
   - Output: DQ Rule Backlog.

7. Tabellen- und Mart-Design vorbereiten
   - Grain, Facts, Dimensions, Slowly Changing Dimensions, History-Bedarf, Semantik.
   - Output: Mart Design Brief.

8. Entscheidung vorbereiten
   - Impact, Effort, Risiken, offene Fragen, Pilot-Kandidat.
   - Output: Decision Brief.

Nächste Umsetzung:

- Als erstes als Tool-Hub mit Stepper bauen, der bestehende Tools verlinkt.
- Danach gemeinsame Exportstruktur einführen: `governance-discovery.md`, `kpi-cards.csv`, `source-scope.csv`, `dq-backlog.csv`.
- Später optional Speicherung im Account/Sprint-Planner.

Aktualisierte Produktlogik:

- Der Governance Hub ist der neutrale Workspace und verbindet Hubs, Resources, Suppliers, Advisor, Sessions und Reports.
- Jedes Governance Tool muss standalone nutzbar sein, aber dieselbe Session-Struktur lesen und schreiben können.
- Wenn ein Tool aus einem Plan geöffnet wird, zeigt es "Im Plan speichern" und "Zurück zum Plan". Gespeichert wird strukturiert plus kurze Notiz; der Nutzer entscheidet, ob die Plan-Aufgabe erledigt ist.
- Wenn ein Tool aus einem Generator oder aus der Governance Session geöffnet wird, zeigt es "Im Generator speichern" oder "In Session speichern" und führt zurück in den jeweiligen Kontext.
- Ergebnisse dürfen nicht nur Links sein: jedes Tool braucht Eingaben, Ergebnisansicht, Validierung, Report-Baustein und eine Save-Aktion.
- Change Requests sind für spätere Änderungen approval-pflichtig; das ist per `GOVERNANCE_CHANGE_APPROVAL_REQUIRED=false` für lokale Demo-Setups abschaltbar.

Dual-Store-Anforderung:

- Runtime-Speicherung bleibt über Store-Klassen gekapselt, nicht direkt in Views oder JavaScript.
- File Store und MySQL Store nutzen dieselbe normalisierte Session-Payload.
- Governance Sessions werden unter `governance-sessions/*.json` abgelegt, wenn File Store aktiv ist.
- MySQL nutzt `bn_governance_sessions` mit `payload`, `validation_summary` und `report_snapshot` als JSON-Spalten.
- `bn-tools:storage-import` importiert bestehende File-Sessions in den DB Store.
- Deploy-/Seed-Snapshots müssen Governance Sessions mitnehmen, damit File- und DB-Umstellung nachvollziehbar bleibt.

Data Quality Integration:

- Data Quality ist kein isolierter zweiter Berater, sondern ein Entscheidungspfad im Governance Advisor.
- Der DQ-Pfad sammelt Ziel, Schicht, Fehlerklassen, betroffene Quellen/KPIs/Reports und vorgeschlagene Regeln.
- DQ-Ergebnisse werden im Report sichtbar und in den Sprint Planner als eigener Sprint "Datenqualität und Modell" übernommen.
- Passende bestehende Generatoren: dbt DQ Rules, dbt DQ Macro, dbt DQ History, schema.yml Editor und Mart Design Brief.
- Spätere Tool-Editoren sollen DQ-Regeln gegen Source Scope, KPI Grain und PII/Access Angaben validieren.

SEO-Anforderung:

- Die Landingpage erklärt ohne Login, welches Problem der Workflow löst.
- Die acht Schritte sind als crawlbarer HTML-Inhalt vorhanden.
- Jeder Schritt verlinkt auf vorhandene Tools und relevante Playbooks.
- Beispiel-Outputs als statische Demo anzeigen, damit Suchmaschinen den Nutzen verstehen.
- Nutzer-Arbeitsstände, lokale Storage-Daten und Export-URLs nicht indexieren.

## SEO-Standard für jedes neue Tool

Jedes Tool bekommt neben der eigentlichen Interaktion eine kleine, hochwertige Such-Landingpage-Struktur:

- eindeutiger SEO-Title
- eindeutige Meta Description
- Canonical URL
- H1 mit konkreter Aufgabe
- 120-200 Woerter Einstiegstext
- "Wann nutzen?" Abschnitt
- "Welche Inputs brauche ich?" Abschnitt
- "Was kommt heraus?" Abschnitt
- "Nächste Tools" interne Links
- "Passende Resources/Suppliers/Playbooks" interne Links
- FAQ mit echten Fragen, falls sinnvoll
- strukturierte Daten für Breadcrumbs und optional WebApplication/FAQ
- keine Indexierung von temporaeren Ergebniszustaenden

Naming-Regel:

- Tool-Namen dürfen fachlich sein, aber SEO-Titel brauchen die Suchintention.
- Beispiel: "KPI Requirements Intake Form" intern, SEO-Title "KPI Anforderungen sammeln - Vorlage für Grain, Owner, Formel und Datenquelle".

Interne Link-Regel:

- Jedes Discovery-Tool linkt zur Governance-Hub-Seite.
- Jedes Supplier-nahe Tool linkt zur passenden Supplier Library.
- Jedes Vendor-nahe Tool linkt zu Resources.
- Jedes technische Generator-Tool linkt zu mindestens einem Playbook.
- Jede Tool-Seite bietet einen klaren nächsten Schritt statt Sackgasse.

## Tool-Idee 1: KPI Requirements Intake Form

Zielgruppe:

Fachbereich, Product Owner, Analysten, BI Owner.

Problem:

KPIs werden oft als Name oder Dashboard-Wunsch geliefert, aber ohne Grain, Zeitbezug, Filterlogik, Owner oder Akzeptanzbeispiel.

Eingaben:

- KPI Name
- Geschäftsfrage
- Entscheidung, die mit dem KPI getroffen wird
- Formel in Worten
- Beispielrechnung
- Grain: pro Kunde, Auftrag, Ticket, Mitarbeiter, Tag, Monat usw.
- Zeitlogik: Buchungsdatum, Ereignisdatum, Closing Date, Gültigkeit
- Dimensionen
- Filter und Ausschlüsse
- Quelle/Supplier
- Owner und Approver
- Kritikalität

Ausgaben:

- KPI Definition Card
- offene Fragen für Workshop
- empfohlene Fact/Dimension Kandidaten
- DQ-Regeln aus der KPI-Logik
- Link zu Power BI DAX, Tableau Calculation oder Qlik Set Analysis Generator

SEO:

- Hauptkeyword: KPI Anforderungen sammeln.
- Nebenkeywords: KPI Definition Vorlage, KPI Steckbrief, KPI Grain, KPI Owner, BI Requirements.
- Indexierbare Demo: eine Beispiel-KPI-Karte mit Umsatz, Ticket SLA oder Headcount.
- FAQ: Was ist ein KPI Grain? Wer ist KPI Owner? Welche Felder braucht eine KPI Definition?

Priorität: sehr hoch.

## Tool-Idee 2: Source Scope Builder

Zielgruppe:

Data Engineer, Architect, Governance Lead.

Problem:

Vor dem Laden eines SaaS-Systems fehlt oft die klare Antwort: Welche Objekte brauchen wir wirklich, was lassen wir weg, wo steckt PII?

Eingaben:

- Supplier/Product aus der Supplier Library
- Ziel-KPIs
- gewünschte Dimensionen
- gewünschte Historisierung
- geplante Refresh-Frequenz
- Risiko-Level

Ausgaben:

- Load Scope: must-have, optional, skip
- PII/DSDR Watchlist
- erste Tabellenliste für RAW/Bronze
- Curated Modell-Kandidaten
- "Nicht laden ohne Freigabe" Liste

SEO:

- Hauptkeyword: SaaS Datenquelle für Analytics vorbereiten.
- Nebenkeywords: Salesforce Tabellen laden, Supplier Discovery, Source Scope, PII Felder erkennen.
- Indexierbare Demo: Salesforce oder HubSpot Source Scope mit Must-have/Skip/PII.
- Interne Links: Supplier Detailseite, PII/DSDR Checker, Mart Design Brief.

Priorität: sehr hoch, weil es Suppliers direkt nützlich macht.

## Tool-Idee 3: Mart Design Brief Generator

Zielgruppe:

Analytics Engineer, Data Modeler, BI Engineer.

Problem:

Zwischen KPI-Karte und Tabellenmodell fehlt eine kleine Brücke: Fact, Dimension, Grain, Historie, Semantik.

Eingaben:

- KPI Cards
- Source Scope
- Business Grain
- Dimensionen
- SCD-/History-Bedarf
- BI Tool
- Warehouse/Lakehouse Ziel

Ausgaben:

- Fact Table Kandidat
- Dimensionsliste
- Grain Statement
- Measures und Berechnungsort
- DQ Tests
- Governance Meta Felder
- dbt `schema.yml` Starter oder Markdown Brief

SEO:

- Hauptkeyword: Tabellenmodell aus KPI Anforderungen erstellen.
- Nebenkeywords: Fact Table Design, Dimension Design, Mart Design Vorlage, Analytics Engineering Brief.
- Indexierbare Demo: KPI zu Fact/Dimension Mapping.
- Interne Links: KPI Intake, Source Scope, dbt schema.yml, DQ Rules.

Priorität: hoch.

## Tool-Idee 4: Governance Stack Advisor

Zielgruppe:

Entscheider, Architekten, Consultants.

Problem:

Vendor Resources enthalten viele gute Links, aber ein Suchender braucht erst eine Empfehlung, welcher Pfad passt.

Eingaben:

- Cloud Präferenz
- SaaS vs Open Source
- Datenresidenz
- BI Präferenz
- Governance-Reife
- Security/Compliance Druck
- Team-Skills

Ausgaben:

- empfohlene Stack-Optionen
- passende Vendor Resource Karten
- passende Zertifizierungslinks
- Tool-Workflow
- Risiken und offene Entscheidungen

SEO:

- Hauptkeyword: Data Governance Tool Stack vergleichen.
- Nebenkeywords: Microsoft Fabric vs Databricks Governance, Modern Data Stack Governance, Data Catalog Auswahl.
- Indexierbare Demo: Stack-Vergleich mit 3 typischen Ausgangslagen.
- Wichtig: Ergebnisfilter nicht als unendliche URL-Varianten indexieren.

Priorität: hoch für SEO und Positionierung.

## Tool-Idee 5: PII/DSDR Readiness Checker

Zielgruppe:

Privacy, Governance, Data Platform.

Problem:

Viele Projekte kennen PII-Felder, aber nicht die Suchkeys, Kopien, Freitexte, Exporte und Retention-Folgen.

Eingaben:

- Supplier/Product
- Personenarten: Kunde, Mitarbeiter, Lieferant, Patient, Student
- Identifier: Email, Phone, Account Id, Employee Id, External Id
- Datenkopien: RAW, Curated, Mart, BI Extract, Activation
- Freitext/Anhänge
- Retention/Löschlogik

Ausgaben:

- DSDR Suchpfad
- PII Risiko-Heatmap
- Tabellen, die nicht ungeprüft geladen werden sollten
- Review-Aufgaben
- Weiterleitung zu PII Policy, PII Recommend und AI Sanitizer

SEO:

- Hauptkeyword: PII Governance Checkliste.
- Nebenkeywords: DSDR Suchpfad, DSAR Datenquellen, personenbezogene Daten im Warehouse, PII Felder erkennen.
- Indexierbare Demo: CRM/HR/Collaboration PII Watchlist.
- Disclaimer: keine Rechtsberatung, fachliche Daten-Governance-Vorbereitung.

Priorität: hoch.

## Tool-Idee 6: Vendor Learning Path Builder

Zielgruppe:

Einsteiger, Consultants, Teams.

Problem:

Zertifikate und Learning Links sind vorhanden, aber nicht in Lernpfade übersetzt.

Eingaben:

- Rolle: Consultant, Data Engineer, Analytics Engineer, Steward, Security/Privacy
- Stack: Fabric, Databricks, Snowflake, dbt, GCP, AWS, BI
- Erfahrungslevel

Ausgaben:

- 30-Tage Lernpfad
- offizielle Help/Learning/Certification Links
- Binom Playbooks
- praktische Übungen mit vorhandenen Tools

SEO:

- Hauptkeyword: Data Governance Zertifikate.
- Nebenkeywords: Fabric Zertifizierung, Databricks Zertifizierung, Snowflake SnowPro, dbt Certification, Data Governance Consultant Lernpfad.
- Jede Zertifikatsliste braucht "zuletzt geprüft" Datum.
- Nur offizielle Zertifizierungsseiten als Primärlinks.

Priorität: mittel bis hoch, stark für SEO.

## Tool-Idee 7: Supplier KPI Pack Generator

Zielgruppe:

BI Consultant, Fachbereich, Analytics Engineer.

Problem:

Supplier Library hat Standard-KPIs, aber ein Nutzer will ein konkretes Paket für sein System.

Eingaben:

- Supplier/Product
- Domain
- Zielrolle
- Top-KPIs auswählen
- BI Tool: Power BI, Tableau, Qlik

Ausgaben:

- KPI Pack Markdown
- CSV für KPI Inventory
- BI-spezifische Generator-Weiterleitung
- Source Fields und Dimensionen
- offene Definitionen

SEO:

- Hauptkeyword: Supplier KPI Vorlage.
- Nebenkeywords je Supplier, z.B. Salesforce KPI Vorlage, HubSpot KPI Definition, Workday KPI Datenmodell.
- Indexierbare Supplier-Beispiele priorisieren, nicht alle Kombinationen automatisch indexieren.

Priorität: mittel.

## Tool-Idee 8: Governance Evidence Pack

Zielgruppe:

Governance Lead, Auditor, Security.

Problem:

Viele Governance-Artefakte existieren als verstreute Screenshots, Tickets und Tabellen.

Eingaben:

- Controls
- Owner
- Evidence Type
- Quelle/System
- Aktualitätsintervall
- Status

Ausgaben:

- Evidence Register
- Audit-Fragen
- fehlende Nachweise
- Mapping zu Compliance-Bereichen

SEO:

- Hauptkeyword: Governance Evidence Register.
- Nebenkeywords: Audit Nachweise Data Governance, Compliance Evidence, Access Review Nachweis.
- Gut als Compliance-Hub-Erweiterung, weniger als erster SEO-Hebel.

Priorität: mittel, später stark für Compliance-Hub.

## Tool-Idee 9: Supplier Quality Rule Builder

Zielgruppe:

Data Quality Lead, Engineer.

Problem:

Supplier-spezifische Quality-Hinweise könnten direkt in DQ-Regeln übersetzt werden.

Eingaben:

- Supplier/Product
- Entität
- Quality-Probleme aus Catalog/Quality Overlay
- Plattform: dbt, Fabric, Databricks

Ausgaben:

- DQ Rule Backlog
- dbt meta.dq_rules
- Fabric/Databricks Pattern Links
- Test-Priorität

SEO:

- Hauptkeyword: Data Quality Regeln Vorlage.
- Nebenkeywords: dbt DQ Rules, Fabric DQ Checks, Databricks Expectations, Pflichtfelder Business Keys Freshness.
- Indexierbare Demo mit 5 Regeltypen.

Priorität: mittel.

## Tool-Idee 10: Decision Brief Generator

Zielgruppe:

Sponsor, Architekturboard, Projektleitung.

Problem:

Nach Workshops bleiben viele Details, aber keine klare Entscheidungsvorlage.

Eingaben:

- Stakeholder Matrix
- KPI Cards
- Source Scope
- Architecture Fit
- Impact/Effort
- Risiken und Annahmen

Ausgaben:

- 1-Seiten Decision Brief
- Pilot-Vorschlag
- offene Entscheidungen
- Scope für ersten Sprint
- Link zum Sprint Planner

SEO:

- Hauptkeyword: Data Governance Entscheidungsvorlage.
- Nebenkeywords: Decision Brief Data Platform, Governance Pilot Scope, Data Project Entscheidungsvorlage.
- Indexierbare Demo eines anonymen Decision Briefs.

Priorität: hoch als Abschluss des Collect Infos Workflows.

## Empfohlene Reihenfolge

1. Governance Discovery Canvas als verbindender Hub.
2. KPI Requirements Intake Form.
3. Source Scope Builder.
4. Mart Design Brief Generator.
5. Decision Brief Generator.
6. Governance Stack Advisor.
7. PII/DSDR Readiness Checker.
8. Vendor Learning Path Builder.
9. Supplier KPI Pack Generator.
10. Supplier Quality Rule Builder.
11. Governance Evidence Pack.

## Gemeinsames Datenmodell für später

Damit die Tools gut zusammenspielen, sollten sie mittelfristig dieselben Felder verstehen:

- `stakeholders`
- `business_questions`
- `kpis`
- `sources`
- `entities`
- `fields`
- `pii_findings`
- `dq_rules`
- `architecture_constraints`
- `decisions`
- `open_questions`

Exportformate:

- Markdown für Workshop-Dokumente.
- CSV für Listen und Excel/Sheets.
- JSON für Tool-zu-Tool Übergabe.
- Optional Sprint-Planner Transfer für Umsetzung.

## SEO Umsetzung als eigenes Arbeitspaket

Vor der Umsetzung einzelner Tools sollte ein gemeinsames SEO-Fundament entstehen:

1. Meta-Helper für Title, Description, Canonical und Open Graph.
2. Sitemap-Generator für statische Seiten, Playbooks, Suppliers, Resources und Tools.
3. robots.txt mit Sitemap-Direktive.
4. Breadcrumb-Komponente plus strukturierte Daten.
5. Author/Person-Signal für Thomas Lindackers auf Playbooks und Governance-Hubs.
6. FAQ-Komponente für echte Frage-Antwort-Blöcke.
7. `noindex`-Strategie für Login, Account, API, Filter- und Ergebniszustaende.
8. SEO-Testliste für jede neue Seite.

SEO-Testliste:

- Hat die Seite genau eine H1?
- Ist der Title eindeutig und unter Kontrolle?
- Ist die Meta Description handgeschrieben?
- Ist die Canonical URL korrekt?
- Ist die Seite ohne Login und ohne JS-Grundfunktion crawlbar?
- Gibt es interne Links zu nächsten Schritten?
- Ist die Seite in der Sitemap, falls indexierbar?
- Ist die Seite aus der Sitemap ausgeschlossen, falls nicht indexierbar?
- Gibt es sichtbaren Autor/Stand bei fachlichen Inhalten?
- Gibt es keine leeren Demo-Zustaende als Hauptinhalt?

## Was wir nicht zuerst bauen sollten

- Live Vendor API Integrationen.
- Account-pflichtige Formulare.
- Vollautomatische Modellierung ohne Review.
- Rechtsverbindliche Zertifikats- oder Compliance-Aussagen.
- Zu viele Einzelseiten ohne geführten Pfad.
- Automatisch tausende Kombinationsseiten erzeugen, die wie Duplicate Content wirken.
- Indexierbare Ergebnis-URLs mit privaten oder zufaelligen Eingaben.

Der Charme liegt darin, dass binom-tools wie ein guter Erstberater wirkt: Fragen stellen, blinde Flecken zeigen, offizielle Ressourcen verlinken und am Ende ein umsetzbares Artefakt liefern.
