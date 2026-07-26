# Governance Workflow und Tool Plaene

Stand: 2026-07-26

Ziel: neue Tools sollen nicht einfach weitere Generatoren sein, sondern die vorhandenen Bereiche verbinden: Resources sagen "welche Plattform", Suppliers sagen "welche Quelle", Discovery-Tools sammeln Anforderungen, Generatoren erzeugen Umsetzungshilfen.

Alle neuen Hubs und Tools muessen SEO-ready geplant werden: oeffentliche Landingpages sind indexierbar, individuelle Nutzer-Eingaben oder exportierte Arbeitsstaende bleiben nicht indexierbar.

## Bestehende Staerken

Schon vorhanden:

- Vendor Resources fuer Stacks, Help, Governance, Learning, Certifications und Compliance.
- Supplier Library fuer Quellsysteme, Kernobjekte, Felder, PII/DSDR, Skip-Hinweise und Standard-KPIs.
- Discovery & Assessment Workflow mit Stakeholder Matrix, Report Inventory, KPI Definition, BI Python Toolkit, Architecture Fit und Impact-Effort.
- Governance Generatoren fuer dbt, PII, DQ, Fabric, Databricks, PureView und AI Sanitizer.

Der groesste Hebel ist deshalb ein verbindender Workflow, der aus Workshop-Informationen eine Entscheidung und danach ein Tabellen-/Mart-Design vorbereitet.

## Neuer Workflow: Collect Infos Workflow

Arbeitsname: Governance Discovery Canvas

Zweck:

Ein gefuehrter Ablauf, der die Informationen einsammelt, die man fuer eine Stack-, Source-, KPI- und Tabellenmodell-Entscheidung braucht.

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
   - Supplier/Product, relevante Entitaeten, System Owner, Zugriff, Datenfrequenz.
   - Output: Source Scope.

5. Risiko erfassen
   - PII, besondere Kategorien, Freitext, Anhange, Workforce Data, DSDR-Suchkeys, Retention.
   - Output: PII/DSDR Review Sheet.

6. Datenqualitaet definieren
   - Pflichtfelder, Business Keys, Freshness, Referenzen, erlaubte Werte, Duplikate.
   - Output: DQ Rule Backlog.

7. Tabellen- und Mart-Design vorbereiten
   - Grain, Facts, Dimensions, Slowly Changing Dimensions, History-Bedarf, Semantik.
   - Output: Mart Design Brief.

8. Entscheidung vorbereiten
   - Impact, Effort, Risiken, offene Fragen, Pilot-Kandidat.
   - Output: Decision Brief.

Naechste Umsetzung:

- Als erstes als Tool-Hub mit Stepper bauen, der bestehende Tools verlinkt.
- Danach gemeinsame Exportstruktur einfuehren: `governance-discovery.md`, `kpi-cards.csv`, `source-scope.csv`, `dq-backlog.csv`.
- Spaeter optional Speicherung im Account/Sprint-Planner.

SEO-Anforderung:

- Die Landingpage erklaert ohne Login, welches Problem der Workflow loest.
- Die acht Schritte sind als crawlbarer HTML-Inhalt vorhanden.
- Jeder Schritt verlinkt auf vorhandene Tools und relevante Playbooks.
- Beispiel-Outputs als statische Demo anzeigen, damit Suchmaschinen den Nutzen verstehen.
- Nutzer-Arbeitsstaende, lokale Storage-Daten und Export-URLs nicht indexieren.

## SEO-Standard fuer jedes neue Tool

Jedes Tool bekommt neben der eigentlichen Interaktion eine kleine, hochwertige Such-Landingpage-Struktur:

- eindeutiger SEO-Title
- eindeutige Meta Description
- Canonical URL
- H1 mit konkreter Aufgabe
- 120-200 Woerter Einstiegstext
- "Wann nutzen?" Abschnitt
- "Welche Inputs brauche ich?" Abschnitt
- "Was kommt heraus?" Abschnitt
- "Naechste Tools" interne Links
- "Passende Resources/Suppliers/Playbooks" interne Links
- FAQ mit echten Fragen, falls sinnvoll
- strukturierte Daten fuer Breadcrumbs und optional WebApplication/FAQ
- keine Indexierung von temporaeren Ergebniszustaenden

Naming-Regel:

- Tool-Namen duerfen fachlich sein, aber SEO-Titel brauchen die Suchintention.
- Beispiel: "KPI Requirements Intake Form" intern, SEO-Title "KPI Anforderungen sammeln - Vorlage fuer Grain, Owner, Formel und Datenquelle".

Interne Link-Regel:

- Jedes Discovery-Tool linkt zur Governance-Hub-Seite.
- Jedes Supplier-nahe Tool linkt zur passenden Supplier Library.
- Jedes Vendor-nahe Tool linkt zu Resources.
- Jedes technische Generator-Tool linkt zu mindestens einem Playbook.
- Jede Tool-Seite bietet einen klaren naechsten Schritt statt Sackgasse.

## Tool-Idee 1: KPI Requirements Intake Form

Zielgruppe:

Fachbereich, Product Owner, Analysten, BI Owner.

Problem:

KPIs werden oft als Name oder Dashboard-Wunsch geliefert, aber ohne Grain, Zeitbezug, Filterlogik, Owner oder Akzeptanzbeispiel.

Eingaben:

- KPI Name
- Geschaeftsfrage
- Entscheidung, die mit dem KPI getroffen wird
- Formel in Worten
- Beispielrechnung
- Grain: pro Kunde, Auftrag, Ticket, Mitarbeiter, Tag, Monat usw.
- Zeitlogik: Buchungsdatum, Ereignisdatum, Closing Date, Gueltigkeit
- Dimensionen
- Filter und Ausschluesse
- Quelle/Supplier
- Owner und Approver
- Kritikalitaet

Ausgaben:

- KPI Definition Card
- offene Fragen fuer Workshop
- empfohlene Fact/Dimension Kandidaten
- DQ-Regeln aus der KPI-Logik
- Link zu Power BI DAX, Tableau Calculation oder Qlik Set Analysis Generator

SEO:

- Hauptkeyword: KPI Anforderungen sammeln.
- Nebenkeywords: KPI Definition Vorlage, KPI Steckbrief, KPI Grain, KPI Owner, BI Requirements.
- Indexierbare Demo: eine Beispiel-KPI-Karte mit Umsatz, Ticket SLA oder Headcount.
- FAQ: Was ist ein KPI Grain? Wer ist KPI Owner? Welche Felder braucht eine KPI Definition?

Prioritaet: sehr hoch.

## Tool-Idee 2: Source Scope Builder

Zielgruppe:

Data Engineer, Architect, Governance Lead.

Problem:

Vor dem Laden eines SaaS-Systems fehlt oft die klare Antwort: Welche Objekte brauchen wir wirklich, was lassen wir weg, wo steckt PII?

Eingaben:

- Supplier/Product aus der Supplier Library
- Ziel-KPIs
- gewuenschte Dimensionen
- gewuenschte Historisierung
- geplante Refresh-Frequenz
- Risiko-Level

Ausgaben:

- Load Scope: must-have, optional, skip
- PII/DSDR Watchlist
- erste Tabellenliste fuer RAW/Bronze
- Curated Modell-Kandidaten
- "Nicht laden ohne Freigabe" Liste

SEO:

- Hauptkeyword: SaaS Datenquelle fuer Analytics vorbereiten.
- Nebenkeywords: Salesforce Tabellen laden, Supplier Discovery, Source Scope, PII Felder erkennen.
- Indexierbare Demo: Salesforce oder HubSpot Source Scope mit Must-have/Skip/PII.
- Interne Links: Supplier Detailseite, PII/DSDR Checker, Mart Design Brief.

Prioritaet: sehr hoch, weil es Suppliers direkt nuetzlich macht.

## Tool-Idee 3: Mart Design Brief Generator

Zielgruppe:

Analytics Engineer, Data Modeler, BI Engineer.

Problem:

Zwischen KPI-Karte und Tabellenmodell fehlt eine kleine Bruecke: Fact, Dimension, Grain, Historie, Semantik.

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

Prioritaet: hoch.

## Tool-Idee 4: Governance Stack Advisor

Zielgruppe:

Entscheider, Architekten, Consultants.

Problem:

Vendor Resources enthalten viele gute Links, aber ein Suchender braucht erst eine Empfehlung, welcher Pfad passt.

Eingaben:

- Cloud Praeferenz
- SaaS vs Open Source
- Datenresidenz
- BI Praeferenz
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

Prioritaet: hoch fuer SEO und Positionierung.

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
- Freitext/Anhaenge
- Retention/Loeschlogik

Ausgaben:

- DSDR Suchpfad
- PII Risiko-Heatmap
- Tabellen, die nicht ungeprueft geladen werden sollten
- Review-Aufgaben
- Weiterleitung zu PII Policy, PII Recommend und AI Sanitizer

SEO:

- Hauptkeyword: PII Governance Checkliste.
- Nebenkeywords: DSDR Suchpfad, DSAR Datenquellen, personenbezogene Daten im Warehouse, PII Felder erkennen.
- Indexierbare Demo: CRM/HR/Collaboration PII Watchlist.
- Disclaimer: keine Rechtsberatung, fachliche Daten-Governance-Vorbereitung.

Prioritaet: hoch.

## Tool-Idee 6: Vendor Learning Path Builder

Zielgruppe:

Einsteiger, Consultants, Teams.

Problem:

Zertifikate und Learning Links sind vorhanden, aber nicht in Lernpfade uebersetzt.

Eingaben:

- Rolle: Consultant, Data Engineer, Analytics Engineer, Steward, Security/Privacy
- Stack: Fabric, Databricks, Snowflake, dbt, GCP, AWS, BI
- Erfahrungslevel

Ausgaben:

- 30-Tage Lernpfad
- offizielle Help/Learning/Certification Links
- Binom Playbooks
- praktische Uebungen mit vorhandenen Tools

SEO:

- Hauptkeyword: Data Governance Zertifikate.
- Nebenkeywords: Fabric Zertifizierung, Databricks Zertifizierung, Snowflake SnowPro, dbt Certification, Data Governance Consultant Lernpfad.
- Jede Zertifikatsliste braucht "zuletzt geprueft" Datum.
- Nur offizielle Zertifizierungsseiten als Primaerlinks.

Prioritaet: mittel bis hoch, stark fuer SEO.

## Tool-Idee 7: Supplier KPI Pack Generator

Zielgruppe:

BI Consultant, Fachbereich, Analytics Engineer.

Problem:

Supplier Library hat Standard-KPIs, aber ein Nutzer will ein konkretes Paket fuer sein System.

Eingaben:

- Supplier/Product
- Domain
- Zielrolle
- Top-KPIs auswaehlen
- BI Tool: Power BI, Tableau, Qlik

Ausgaben:

- KPI Pack Markdown
- CSV fuer KPI Inventory
- BI-spezifische Generator-Weiterleitung
- Source Fields und Dimensionen
- offene Definitionen

SEO:

- Hauptkeyword: Supplier KPI Vorlage.
- Nebenkeywords je Supplier, z.B. Salesforce KPI Vorlage, HubSpot KPI Definition, Workday KPI Datenmodell.
- Indexierbare Supplier-Beispiele priorisieren, nicht alle Kombinationen automatisch indexieren.

Prioritaet: mittel.

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
- Aktualitaetsintervall
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

Prioritaet: mittel, spaeter stark fuer Compliance-Hub.

## Tool-Idee 9: Supplier Quality Rule Builder

Zielgruppe:

Data Quality Lead, Engineer.

Problem:

Supplier-spezifische Quality-Hinweise koennten direkt in DQ-Regeln uebersetzt werden.

Eingaben:

- Supplier/Product
- Entitaet
- Quality-Probleme aus Catalog/Quality Overlay
- Plattform: dbt, Fabric, Databricks

Ausgaben:

- DQ Rule Backlog
- dbt meta.dq_rules
- Fabric/Databricks Pattern Links
- Test-Prioritaet

SEO:

- Hauptkeyword: Data Quality Regeln Vorlage.
- Nebenkeywords: dbt DQ Rules, Fabric DQ Checks, Databricks Expectations, Pflichtfelder Business Keys Freshness.
- Indexierbare Demo mit 5 Regeltypen.

Prioritaet: mittel.

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
- Scope fuer ersten Sprint
- Link zum Sprint Planner

SEO:

- Hauptkeyword: Data Governance Entscheidungsvorlage.
- Nebenkeywords: Decision Brief Data Platform, Governance Pilot Scope, Data Project Entscheidungsvorlage.
- Indexierbare Demo eines anonymen Decision Briefs.

Prioritaet: hoch als Abschluss des Collect Infos Workflows.

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

## Gemeinsames Datenmodell fuer spaeter

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

- Markdown fuer Workshop-Dokumente.
- CSV fuer Listen und Excel/Sheets.
- JSON fuer Tool-zu-Tool Uebergabe.
- Optional Sprint-Planner Transfer fuer Umsetzung.

## SEO Umsetzung als eigenes Arbeitspaket

Vor der Umsetzung einzelner Tools sollte ein gemeinsames SEO-Fundament entstehen:

1. Meta-Helper fuer Title, Description, Canonical und Open Graph.
2. Sitemap-Generator fuer statische Seiten, Playbooks, Suppliers, Resources und Tools.
3. robots.txt mit Sitemap-Direktive.
4. Breadcrumb-Komponente plus strukturierte Daten.
5. Author/Person-Signal fuer Thomas Lindackers auf Playbooks und Governance-Hubs.
6. FAQ-Komponente fuer echte Frage-Antwort-Bloecke.
7. `noindex`-Strategie fuer Login, Account, API, Filter- und Ergebniszustaende.
8. SEO-Testliste fuer jede neue Seite.

SEO-Testliste:

- Hat die Seite genau eine H1?
- Ist der Title eindeutig und unter Kontrolle?
- Ist die Meta Description handgeschrieben?
- Ist die Canonical URL korrekt?
- Ist die Seite ohne Login und ohne JS-Grundfunktion crawlbar?
- Gibt es interne Links zu naechsten Schritten?
- Ist die Seite in der Sitemap, falls indexierbar?
- Ist die Seite aus der Sitemap ausgeschlossen, falls nicht indexierbar?
- Gibt es sichtbaren Autor/Stand bei fachlichen Inhalten?
- Gibt es keine leeren Demo-Zustaende als Hauptinhalt?

## Was wir nicht zuerst bauen sollten

- Live Vendor API Integrationen.
- Account-pflichtige Formulare.
- Vollautomatische Modellierung ohne Review.
- Rechtsverbindliche Zertifikats- oder Compliance-Aussagen.
- Zu viele Einzelseiten ohne gefuehrten Pfad.
- Automatisch tausende Kombinationsseiten erzeugen, die wie Duplicate Content wirken.
- Indexierbare Ergebnis-URLs mit privaten oder zufaelligen Eingaben.

Der Charme liegt darin, dass binom-tools wie ein guter Erstberater wirkt: Fragen stellen, blinde Flecken zeigen, offizielle Ressourcen verlinken und am Ende ein umsetzbares Artefakt liefern.
