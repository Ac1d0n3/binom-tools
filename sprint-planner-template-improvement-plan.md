# Sprint Planner Template Improvement Plan

Stand: 2026-07-19

Dieser Report ist ein Arbeitsplan fuer die Verbesserung der vorhandenen Sprint-Planner-Templates. Er veraendert keine Planstruktur und keine bestehenden IDs.

## Zielbild

Die Templates sollen Aufgaben besser fuehren:

- Jede Aufgabe erklaert Zweck, Vorgehen, Ergebnis und typische Stolperfallen.
- Deliverables beschreiben klare Abnahmekriterien statt nur eine Ablage.
- Interne Playbooks werden als `stories` gepflegt, damit sie als Story-Karten in der Hilfe erscheinen.
- `helpLinks` enthalten externe URLs und interne `/tools/...`-Pfade; Playbook-Pfade bleiben draussen.
- Sinnvolle Tabellen und Felder geben Struktur vor, ohne echte Planwerte vorwegzunehmen.
- DE/EN bleiben strukturell identisch.

## Aktueller Befund

Alle Templates werden vom bestehenden Planner-Parser ohne Fehler geladen.

| Template | Sprints | Tasks | Deliverables | Fields | Table Items | Lokalisierte Hilfetexte | Kurze Hilfetexte | Links gesamt | Interne Links | Externe Links |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---:|
| `change-tests` | 3 | 6 | 3 | 5 | 6 | 18 | 18 | 10 | 4 | 6 |
| `database-model` | 4 | 8 | 4 | 5 | 8 | 24 | 24 | 15 | 6 | 9 |
| `planning-month` | 4 | 8 | 4 | 5 | 5 | 24 | 24 | 12 | 3 | 9 |
| `planning-quarter-lite` | 13 | 26 | 13 | 5 | 5 | 78 | 78 | 11 | 3 | 8 |
| `report-kpi-analysis` | 4 | 8 | 4 | 6 | 7 | 24 | 24 | 12 | 4 | 8 |
| `data-reporting-first-quarter` | 13 | 26 | 26 | 14 | 3 | 104 | 31 | 26 | 15 | 11 |
| `data-reporting-fq-fabric-qlik-qvd` | 13 | 26 | 26 | 14 | 3 | 104 | 31 | 67 | 67 | 0 |
| `data-reporting-fq-fivetran-snowflake-powerbi` | 13 | 26 | 26 | 14 | 3 | 104 | 31 | 67 | 67 | 0 |
| `data-reporting-fq-fivetran-snowflake-qlik` | 13 | 26 | 26 | 14 | 3 | 104 | 31 | 67 | 67 | 0 |

Interpretation:

- Es gibt keine fehlenden Hilfetexte, aber viele sind eher knapp.
- Alle vorhandenen Links sind intern (`/tools/...` oder `/playbooks/...`).
- Der aktuelle Code erlaubt externe URLs und interne `/tools/...`-Pfade in `helpLinks`; Playbook-Pfade gehoeren in `stories`.
- Kleine Templates haben keine `fields` und keine Tabellen. Das ist nicht falsch, aber an einigen Stellen verschenkt es Fuehrung.
- Die grossen 13-Wochen-Templates sind bereits deutlich reifer, brauchen aber Link-Bereinigung und gezielte Textschaerfung.

## Leitregeln fuer die Umsetzung

1. IDs bleiben stabil.
2. DE/EN bekommen dieselbe Struktur.
3. `helpLinks` fuer externe URLs und interne `/tools/...` verwenden.
4. Interne Playbooks ueber `stories` referenzieren.
5. Interne Tools duerfen in `helpLinks` bleiben, weil das Hilfe-Panel sie erkennt und mit Plan-Return-Kontext oeffnet.
6. Felder duerfen keine Instanzdaten vorfuellen. Sie sollen nur bessere Platzhalter, Optionen oder Eingabeformate liefern.
7. Hilfetexte sollen handlungsorientiert bleiben, nicht essayartig werden.

## Vorgeschlagene Prioritaet

### Phase 1: Kleine Templates als Qualitaetsstandard

Start mit:

- `planning-month` - begonnen/als erstes Muster verbessert
- `report-kpi-analysis` - verbessert
- `database-model` - verbessert
- `change-tests` - verbessert
- `planning-quarter-lite` - verbessert

Warum:

- wenig Umfang
- schnell sichtbare Verbesserung
- gutes Muster fuer Felder, Tabellen, HelpText-Stil und Link-Regeln

Konkrete Verbesserungen:

- kurze Hilfetexte auf 3 bis 5 handlungsorientierte Saetze erweitern
- Deliverables mit Mindestinhalt und Abnahmekriterium versehen
- Tabellen ergaenzen, wo Daten gesammelt werden:
  - Reports: `Report`, `Owner`, `KPI`, `Quelle`, `Nutzung`, `Problem`
  - Datenmodell: `Tabelle`, `Quelle`, `Grain`, `Owner`, `Risiko`
  - Tests: `Aenderung`, `Risiko`, `Test`, `Owner`, `Entscheidung`
  - Monatsplanung: `Prioritaet`, `Owner`, `Ergebnis`, `Risiko`, `Naechster Schritt`
- sinnvolle Sprint-Felder ergaenzen:
  - Fokus / Zielbild
  - wichtigste Risiken
  - offene Entscheidungen
  - Review-Ergebnis

### Phase 2: First-Quarter-Basis-Template

Template:

- `data-reporting-first-quarter`

Konkrete Verbesserungen:

- interne Playbook-Links aus `helpLinks` in `stories` ueberfuehren, sofern dort noch nicht vorhanden
- interne Tool-Links in der Hilfe behalten
- externe Quellen ergaenzen, z. B. offizielle Produktdocs, dbt Docs, Microsoft Learn, Snowflake Docs, Fivetran Docs, Qlik Docs, Datenschutz-/Governance-Referenzen
- Deliverable-Hilfen weniger generisch formulieren
- vorhandene 14 Felder pruefen und ggf. mit besseren Typen/Platzhaltern versehen
- mehr `table`-geeignete Items identifizieren

### Phase 3: Stack-Varianten synchronisieren

Templates:

- `data-reporting-fq-fivetran-snowflake-qlik`
- `data-reporting-fq-fivetran-snowflake-powerbi`
- `data-reporting-fq-fabric-qlik-qvd`

Vorgehen:

- Basisverbesserungen aus `data-reporting-first-quarter` uebernehmen
- nur stack-spezifische Texte und externe Quellen unterscheiden
- Struktur mit Basis und zwischen DE/EN abgleichen

### Phase 4: Validator/Test-Erweiterung

Empfohlene Tests:

- Warnung oder Test fuer Playbook-`href` in `helpLinks` und Sprint-`links`
- Test, dass interne `/tools/...`-Links weiterhin erlaubt sind
- Test, dass DE/EN nicht nur IDs, sondern auch Link-Hrefs strukturell gleich halten
- Test, dass `tableColumns` korrekt als `table.columns` im Client-Modell ankommen
- optionaler Quality-Test fuer sehr kurze Hilfetexte nur als Warnung, nicht als harter Fehler

## Produktentscheidung

Stories und Tools gehoeren in den Hilfe-Kontext:

- Stories stehen in `stories` und erscheinen als eigene Story-Karten mit Lesestatus.
- Tools stehen in `helpLinks` als `/tools/...` und koennen den Plan-Return-Kontext nutzen.
- Externe Quellen stehen ebenfalls in `helpLinks`.
- Interne `/playbooks/...`-Pfade sollen nicht in `helpLinks` stehen, weil sie sonst die Story-Funktion umgehen.

## Empfohlener naechster Schritt

Naechster sinnvoller Schritt:

1. `planning-month` kurz im Browser pruefen.
2. Muster bestaetigen oder nachjustieren.
3. Danach `report-kpi-analysis` mit demselben Vorgehen bearbeiten.
4. Tests laufen lassen.

Wenn das Muster passt, laesst es sich auf die uebrigen kleinen Templates und danach auf die grossen Templates uebertragen.

## Fortschritt

### `planning-month`

Erledigt:

- interne Playbook-Links aus `helpLinks` entfernt
- interne Tool-Links in `helpLinks` behalten
- externe Quellen fuer Sprint Planning, Backlog, Sprint, Review und Retrospective ergaenzt
- Hilfetexte in DE/EN handlungsorientierter formuliert
- 5 strukturierende Tabellen ergaenzt
- 5 Sprint-Felder ergaenzt
- `w1-plan-week` Minuten zwischen DE/EN auf 60 Minuten vereinheitlicht
- Parser-Check ohne Fehler/Warnungen
- Sprint-Planner Unit/Feature-Tests bestanden

### `report-kpi-analysis`

Erledigt:

- interne Playbook-Links aus `helpLinks` entfernt und als `stories` gepflegt
- interne Tool-Links in `helpLinks` behalten
- externe Quellen fuer Dashboard-Design, KPI-Visualisierung, Power-BI-Reports und Acceptance Criteria ergaenzt
- Hilfetexte in DE/EN handlungsorientierter formuliert
- 7 strukturierende Tabellen ergaenzt
- 6 Sprint-Felder ergaenzt
- `w2-defs` und `w3-mock` Minuten zwischen DE/EN vereinheitlicht
- Parser-Check ohne Fehler/Warnungen
- Sprint-Planner Unit/Feature-Tests bestanden

### `database-model`

Erledigt:

- interne Playbook-Links aus `helpLinks` entfernt und als `stories` gepflegt
- interne Tool-Links in `helpLinks` behalten
- externe Quellen fuer Star Schema, Data Modeling, Constraints, Snowflake-Objekte und dbt-Projektpraxis ergaenzt
- Hilfetexte in DE/EN handlungsorientierter formuliert
- 8 strukturierende Tabellen ergaenzt
- 5 Sprint-Felder ergaenzt
- `w1-scope`, `w2-entities`, `w2-rels` und `w3-types` Minuten zwischen DE/EN vereinheitlicht
- Parser-Check ohne Fehler/Warnungen
- Sprint-Planner Unit/Feature-Tests bestanden

### `change-tests`

Erledigt:

- interne Playbook-Links aus `helpLinks` entfernt und als `stories` gepflegt
- interne Tool-Links in `helpLinks` behalten
- externe Quellen fuer CI, Status Checks, Acceptance Criteria, Power-BI/Fabric-Publishing und dbt Data-Quality-Testing ergaenzt
- Hilfetexte in DE/EN handlungsorientierter formuliert
- 6 strukturierende Tabellen ergaenzt
- 5 Sprint-Felder ergaenzt
- `w1-impact` Minuten zwischen DE/EN auf 120 Minuten vereinheitlicht
- Parser-Check ohne Fehler/Warnungen
- Sprint-Planner Unit/Feature-Tests bestanden

### `planning-quarter-lite`

Erledigt:

- interne Playbook-Links aus `helpLinks` entfernt und als `stories` gepflegt
- interne Tool-Links in `helpLinks` behalten
- externe Quellen fuer Sprint Planning, Backlog, Dependency Mapping, Team Health, Risk Assessment, Review und Retrospective ergaenzt
- Kickoff, Dependency Map, Mid-Quarter Check, Risk Burn-down und Quarter Close gezielt geschaerft
- 5 strukturierende Tabellen ergaenzt
- 5 Sprint-Felder ergaenzt
- Wochenplanungs-Minuten zwischen DE/EN auf 60 Minuten vereinheitlicht
- Parser-Check ohne Fehler/Warnungen
- Sprint-Planner Unit/Feature-Tests bestanden

### `data-reporting-first-quarter`

Erledigt:

- erster Basis-Durchgang fuer das grosse 13-Wochen-Template
- interne Playbook-Links aus `helpLinks` entfernt; Stories bleiben ueber `stories`/`linkedStories` im Hilfe-Kontext
- interne Tool-Links in `helpLinks` behalten
- externe Quellen fuer Mandat, Stakeholder, Report-Inventar, KPI, DQ, PII, Architektur, Priorisierung, Pilot und Validierung ergaenzt
- generische Deliverable-Texte in DE/EN durch bessere Abnahmeformel ersetzt
- Parser-Check ohne Fehler/Warnungen
- Sprint-Planner Unit/Feature-Tests bestanden
