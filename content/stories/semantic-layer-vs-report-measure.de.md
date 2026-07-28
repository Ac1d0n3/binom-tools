---
title: Semantic Layer vs Measure im Report
description: Wohin Kennzahlen gehören — Warehouse, semantische Schicht oder Report.
author: Thomas Lindackers
tags:
  - metric-governance
  - semantic-layer
  - business-intelligence
  - kpi-governance
  - power-bi
  - tableau
  - qlik
  - data-modeling
  - report-governance
publishedAt: 2026-07-28
category: Data Governance
order: -1
hero: images/playbooks/semantic-layer-vs-report-measure-hero.png
series: bi-governance-decisions
seriesTitle: BI-Governance-Entscheidungen
seriesPart: 1
---

Eine Kennzahl ist nicht allein deshalb governed, weil ihre Formel technisch korrekt ist. Sie ist governed, wenn Bedeutung, Grain, Berechnungsgrenze, Owner, Tests, erlaubter Kontext und Wiederverwendung explizit sind.

Die Platzierungsentscheidung lässt sich deshalb weder auf „zentral ist gut“ noch auf „lokal ist flexibel“ reduzieren. Wiederverwendbare fachliche Wahrheit darf nicht in jeden Report kopiert werden. Gleichzeitig gehört reine Darstellungslogik nicht nur wegen einer pauschalen Zentralisierungsvorgabe in physische Warehouse-Tabellen.

![Eine governte Kennzahl durchläuft eine Platzierungsentscheidung, bevor wiederverwendbare und lokale Logik getrennt werden](images/playbooks/semantic-layer-vs-report-measure-hero.png)

## Problem

Organisationen erkennen Inkonsistenzen bei Kennzahlen häufig erst, wenn bereits mehrere Reports produktiv sind.

Dieselbe Bezeichnung kann vorkommen in:

- einer Warehouse View;
- einem Power BI Semantic Model;
- einer veröffentlichten Tableau Data Source oder Workbook;
- einer Qlik Master Measure, Variable oder Diagrammexpression;
- einer Excel-Arbeitsmappe;
- einer lokalen Report-Berechnung, die aus einer anderen Anwendung kopiert wurde.

Die Formeln können ähnlich aussehen und trotzdem unterschiedliche Grains, Datumsfelder, Ausschlüsse oder Filterregeln verwenden. Umgekehrt können zwei Formeln unterschiedlich aussehen, weil jede BI Engine analytischen Kontext anders ausdrückt, obwohl beide denselben freigegebenen Kennzahlenvertrag umsetzen.

Das ist nicht nur ein Formelproblem, sondern ein Platzierungsproblem.

### Typische Anti-Patterns

**Jede Berechnung im Warehouse** erzeugt physische Spalten und Aggregate für Verhalten, das nur in einem Visualisierungskontext existiert. Das Warehouse wird an Seitenlayouts, ausgewählte Gesamtsummen, temporäre Szenarien und tool-spezifische Interaktionen gekoppelt.

**Jede Berechnung im Report** verteilt Fachregeln auf Dateien, Anwendungen und Visualisierungsobjekte. Wiederverwendung erfolgt durch Kopieren, Reconciliation wird manuell und Ownership lässt sich schwer nachweisen.

**Duplizierte „gleiche“ KPIs mit unterschiedlichen Filtern** erzeugen mehrere operative Wahrheiten unter derselben fachlichen Bezeichnung. Der Konflikt bleibt oft unsichtbar, bis zwei Reports in einem Meeting verglichen werden.

**Versteckte Fachregeln in Visualisierungsausdrücken** erschweren das Auffinden, Testen und Prüfen relevanter Ausschlüsse, Netting-Regeln oder Periodendefinitionen.

**Ein zertifiziertes Semantic Model mit ungovernten lokalen Overrides** erzeugt falsches Vertrauen. Die Zertifizierung des gemeinsamen Modells zertifiziert nicht automatisch jede lokale Berechnung, die darauf aufbaut.

### Das Report-Inventar zeigt den tatsächlichen Kennzahlenbestand

Ein Report-Inventar sollte mehr als Reporttitel und Owner enthalten. Für die Platzierungsentscheidung werden mindestens erfasst:

- Kennzahlenbezeichnung und technischer Ort der Expression;
- Report, Workbook, App, Semantic Model und Data Source;
- unterstützte Business-Frage und Entscheidung;
- Basis-Grain und dargestellter Grain;
- Filter, Selektionen und Zeitverhalten;
- referenzierte Quellfelder und gemeinsame Measures;
- Owner, Kritikalität und Zertifizierungsstatus;
- Evidenz für Kopien, Near-Duplicates und Overrides;
- Nutzung, Zielgruppe und erwartete Lebensdauer.

Das Inventar macht aus einem abstrakten Governance-Thema überprüfbare Evidenz. Es zeigt, ob eine vermeintlich lokale Measure bereits wiederverwendet wird, ob eine zertifizierte KPI inoffizielle Varianten besitzt und wo der größte Migrationsaufwand liegt.

## Entscheidung

Behandle die Platzierung einer Kennzahl als explizite Governance-Entscheidung anhand von **Wiederverwendung, Risiko, Grain, Analysekontext, Testbarkeit, Ownership und Performance**.

Die Entscheidung führt zu einem von vier Ergebnissen:

1. **Warehouse oder Data Product** für dauerhafte, wiederverwendbare Business-Event-Logik und Grain-Ausrichtung.
2. **Gemeinsame semantische Schicht** für governte analytische Regeln, die über Reports hinweg wiederverwendet werden.
3. **Report-lokale Measure** für bewusst lokale Visualisierungs- oder Analysefunktionen.
4. **Temporäres Experiment mit Review-Pflicht**, solange der künftige Scope noch nicht feststeht.

![Logik nach Wiederverwendung, Risiko und Grain platzieren](images/playbooks/semantic-layer-vs-report-measure-img1-de.png)

Keine technische Schicht ist immer richtig. Dieselbe mathematische Formel kann abhängig von ihrem Scope an unterschiedlichen Stellen korrekt aufgehoben sein.

### Warehouse oder Data Product

Platziere Logik im Warehouse, Lakehouse, Mart oder governeden Data Product, wenn sie dauerhafte Datenbedeutung definiert, bevor eine BI Engine das Ergebnis auswertet.

Typische Beispiele sind:

- Zulässigkeit von Transaktionen;
- Behandlung von Stornos, Retouren und Rückbuchungen;
- Währungsstandardisierung;
- Harmonisierung mehrerer Quellsysteme;
- historische Zuordnung von Kunden- oder Produktattributen;
- Deduplizierung und Auflösung fachlicher Schlüssel;
- wiederverwendbare Netting-Logik;
- Grain-Ausrichtung über mehrere Quellen;
- Data-Quality-Status, den alle Consumer benötigen.

Diese Schicht erzeugt stabile Fakten und Attribute, die unabhängig von einem Report getestet werden können. Sie sollte nicht jede mögliche Selected-Total-Quote, Diagrammgegenüberstellung oder temporäre Benutzersimulation materialisieren.

### Gemeinsame semantische Schicht

Platziere Logik in einem gemeinsamen Semantic Model, wenn sie wiederverwendbares analytisches Verhalten auf governeden Daten definiert.

Typische Beispiele sind:

- freigegebenes Aggregationsverhalten;
- wiederverwendbare Basis- und abgeleitete Measures;
- governte Time Intelligence;
- unterstützte Dimensionen und Hierarchien;
- gemeinsames Filterverhalten;
- fachliche Namen und Formate;
- Calculation Groups oder vergleichbare wiederverwendbare Berechnungsmuster;
- kontrollierte Security- und Analysebeziehungen;
- Metadaten für Zertifizierung und Auffindbarkeit.

Die semantische Schicht sollte keinen ungeklärten Warehouse-Grain kompensieren und keine komplexe Quellenintegration duplizieren, die upstream gehört. Ihre Aufgabe ist es, governte Fakten im Berechnungskontext der jeweiligen Analyse-Engine bereitzustellen.

### Report-lokale Measure

Eine Measure darf lokal bleiben, wenn ihre Bedeutung bewusst von einem bestimmten Report, einer Seite, einem Visual oder einer temporären Analyseinteraktion abhängt.

Geeignete Beispiele sind:

- Anteil an der aktuell ausgewählten Gesamtsumme;
- Referenzlinie oder Ranking-Kontext eines einzelnen Visuals;
- seitenspezifischer Vergleich;
- reine Darstellungsquoten;
- kurzfristige Szenarien;
- explorative Segmentierung;
- klar gekennzeichneter Prototyp ohne Anspruch auf Enterprise Truth.

Auch eine lokale Measure benötigt Owner und Status. „Lokal“ darf nicht bedeuten: undokumentiert, versehentlich dauerhaft oder berechtigt, eine zertifizierte KPI still neu zu definieren.

### Temporäres Experiment mit Review-Pflicht

Einige Measures entstehen, bevor Wiederverwendung und Kritikalität bekannt sind. Sie dürfen experimentell bleiben, wenn:

- die fachliche Hypothese noch geprüft wird;
- die erwartete Lebensdauer begrenzt ist;
- ein Owner verantwortlich ist;
- das Ergebnis klar als experimentell gekennzeichnet wird;
- die Measure aus zertifizierten Reports ausgeschlossen bleibt;
- ein Ablauf- oder Review-Datum dokumentiert ist.

Das Experiment wird zu Governance Debt, wenn es den Review-Zeitpunkt ohne Platzierungsentscheidung überlebt.

### Eine Kennzahl mit drei möglichen Orten

`Net Revenue` zeigt, warum die Ebenen nicht miteinander konkurrieren.

![Eine Kennzahl mit Verantwortlichkeiten in Warehouse, Semantic Layer und Report](images/playbooks/semantic-layer-vs-report-measure-img2-de.png)

| Schicht | Verantwortung für Net Revenue | Warum diese Logik dorthin gehört |
| --- | --- | --- |
| **Warehouse oder Data Product** | Zulässige Transaktionen, Stornos, Retouren, freigegebenes Netting und Beträge in Berichtswährung bestimmen | Diese Regeln definieren die wiederverwendbare Faktenbasis und müssen unabhängig von einem BI Tool reconciled werden können |
| **Semantic Layer** | Freigegebene Aggregation, Zeitverhalten, fachliche Bezeichnung, Format und governtes Filterverhalten bereitstellen | Diese Regeln beschreiben, wie Consumer die governte Faktenbasis analysieren |
| **Report** | Anteil an der ausgewählten Gesamtsumme, temporäres Szenario oder seitenspezifischen Vergleich berechnen | Diese Berechnungen hängen von einem konkreten Visual ab und definieren die Kernkennzahl nicht neu |

Die Grenze ist explizit:

> Dupliziere die Kernregeln für Net Revenue nicht in Reports. Erzwinge keinen rein visuellen Kontext in physischen Warehouse-Tabellen.

### Die Platzierungsdimensionen gemeinsam bewerten

Ein sinnvoller Decision Review beantwortet die folgenden Fragen.

| Dimension | Signal für zentrale Platzierung | Signal für lokale Platzierung |
| --- | --- | --- |
| **Wiederverwendung** | In mehreren governeden Produkten verwendet oder erwartet | Nur in einem Report oder Visual verwendet |
| **Kritikalität** | Executive, finanziell, regulatorisch oder operativ verbindlich | Explorativ oder reine Darstellung |
| **Stabilität der Definition** | Freigegeben und voraussichtlich stabil | Hypothese oder kurzfristige Analyse |
| **Grain** | Erfordert Kontrolle auf Quellen-, Mart- oder modellübergreifender Ebene | Arbeitet nur auf einer bereits governeden Measure |
| **Filterkontext** | Gemeinsames Filterverhalten muss konsistent sein | Verhalten ist bewusst seiten- oder visualisierungsspezifisch |
| **Testbarkeit** | Muss wiederholt und unabhängig reconciled werden | Kann innerhalb eines kontrollierten Reports validiert werden |
| **Ownership** | Benannter Owner und Freigabe sind vorhanden | Lokaler Owner genügt und Scope ist begrenzt |
| **Performance** | Vorberechnung oder gemeinsame Optimierung hilft mehreren Consumern | Zentrale Materialisierung erzeugt Kosten ohne Wiederverwendung |
| **Zertifizierung** | Muss ein vertrauenswürdiges wiederverwendbares Asset werden | Muss ausdrücklich unzertifiziert oder lokal bleiben |

Keine einzelne Zeile entscheidet allein. Eine Kennzahl in nur einem Report kann trotzdem zentral gehören, wenn sie eine regulatorische Entscheidung steuert. Eine Kennzahl in mehreren explorativen Workbooks kann vorläufig bleiben, bis ihre Bedeutung freigegeben ist.

### Tool-spezifischer Kontext ohne tool-spezifische Wahrheit

Das Platzierungsprinzip ist technologieoffen, aber jede BI Engine besitzt eine andere Implementierungsgrenze.

**Power BI** verwendet Semantic Models als wiederverwendbare Reporting-Quellen und unterstützt explizite Measures im Modell. Endorsement kann Inhalte als promoted oder certified kennzeichnen. Die Governance muss trotzdem lokale Berechnungen und nachgelagertes Report-Verhalten kontrollieren. Die Zertifizierung eines Semantic Models ist keine Erlaubnis, die KPI in jedem Report neu zu definieren.

**Tableau** kann Calculated Fields in einem Workbook oder einer Data Source speichern; veröffentlichte Data Sources können eine gemeinsame kontrollierte Basis bereitstellen. Workbook-Berechnungen sind für visuelle Analysen nützlich. Wesentliche Fachdefinitionen sollten jedoch nicht ausschließlich durch Prüfung einzelner Sheets auffindbar sein.

**Qlik Sense** unterstützt wiederverwendbare Master Measures innerhalb einer Anwendung und erlaubt ihre Nutzung in Visualisierungen und Expressions. Master Measures reduzieren Duplikate innerhalb der governeden App-Grenze. App-übergreifende Wiederverwendung, Variablen und Chart Expressions benötigen trotzdem Inventar, Ownership und Deployment-Disziplin.

Das Ziel ist keine identische Syntax. Das Ziel ist ein freigegebener Kennzahlenvertrag mit klar governeden plattformspezifischen Implementierungen.

### Wann eine lokale Measure hochgestuft werden muss

Eine lokale Measure gehört in einen Promotion Review, sobald mindestens eine dieser Bedingungen eintritt:

- Sie wird in mehr als einem governeden Report oder Data Product verwendet.
- Sie erscheint in Executive-, Finanz-, regulatorischem oder vertraglichem Reporting.
- Sie benötigt gemeinsame Grain-, Zeit- oder Filterregeln.
- Sie kann nicht unabhängig vom Report reconciled werden.
- Nutzer kopieren und verändern sie.
- Mehrere Implementierungen verwenden dieselbe Bezeichnung.
- Sie benötigt Zertifizierung, gemeinsame Tests oder formale Change Control.
- Ihr Fehler würde eine wesentliche Business-Entscheidung verändern.

![Wann eine Report-Measure zu Governance Debt wird](images/playbooks/semantic-layer-vs-report-measure-img3-de.png)

Eine Measure darf lokal bleiben, wenn sie einmaliges Visualisierungsverhalten besitzt, einen bekannten Owner hat, zeitlich begrenzt ist, explizit als explorativ gekennzeichnet wird und keinen Anspruch auf Enterprise Truth erhebt.

Promotion bedeutet nicht automatisch, alles ins Warehouse zu verschieben. Das richtige Ziel kann eine gemeinsame semantische Measure auf einer bereits governeden Warehouse-Faktenbasis sein.

## Checkliste

Nutze diese Checkliste vor Implementierung oder Freigabe einer Kennzahlenplatzierung.

### Definition und Scope

- [ ] Business-Frage und unterstützte Entscheidung sind explizit.
- [ ] Die freigegebene Kennzahlendefinition ist verlinkt.
- [ ] Population, Ausschlüsse, Zeitlogik und erlaubte Dimensionen sind dokumentiert.
- [ ] Der Basis-Grain ist präzise.
- [ ] Fachliche Kernbedeutung ist von Darstellungsverhalten getrennt.

### Wiederverwendung und Kritikalität

- [ ] Aktuelle und erwartete Consumer sind benannt.
- [ ] Das Report-Inventar wurde auf Duplikate und Near-Duplicates geprüft.
- [ ] Executive-, regulatorische, finanzielle oder operative Nutzung ist identifiziert.
- [ ] Die erwartete Lebensdauer ist dokumentiert.
- [ ] Der Anspruch auf Enterprise Truth ist explizit.

### Technische Platzierung

- [ ] Warehouse-Verantwortung ist von Semantic-Model-Verantwortung getrennt.
- [ ] Tool-spezifisches Filter- oder Selektionsverhalten ist dokumentiert.
- [ ] Performance- und Refresh-Auswirkungen wurden bewertet.
- [ ] Die Implementierung ist auf governte Quellfelder und Basis-Measures zurückführbar.
- [ ] Lokale Logik dupliziert keine Storno-, Währungs-, Historien- oder Quellenintegrationsregeln.

### Governance und Controls

- [ ] Business-, Stewardship- und technische Owner sind soweit erforderlich benannt.
- [ ] Test- und Reconciliation-Methoden sind definiert.
- [ ] Zertifizierungsbedarf und Status sind explizit.
- [ ] Erlaubte lokale Overrides sind definiert.
- [ ] Experimentelle Ausnahmen besitzen Ablauf- oder Review-Datum.
- [ ] Migrationsmaßnahmen für vorhandene Duplikate sind zugewiesen.

## Artefakt

Das zentrale Ergebnis ist eine **Metric Placement Decision**. Sie dokumentiert, warum eine Kennzahl in eine bestimmte Schicht gehört und was mit konkurrierenden Implementierungen geschehen muss.

![Die Entscheidung über die Kennzahlenplatzierung dokumentieren](images/playbooks/semantic-layer-vs-report-measure-img4-de.png)

Eine praktische Struktur kann so aussehen:

```yaml
metric_placement_decision:
  metric_id: net-revenue
  metric_name: Net Revenue
  business_question: Wie hoch ist der zulässige Umsatz nach freigegebenen Stornos und Retouren?
  approved_definition: linked-kpi-definition-id
  base_grain: sales-order-line

  calculation_components:
    warehouse_or_data_product:
      - transaction-eligibility
      - cancellation-and-return-treatment
      - currency-standardization
      - reusable-netting-logic
    semantic_layer:
      - approved-aggregation
      - time-intelligence
      - governed-filter-behavior
      - business-friendly-name-and-format
    permitted_report_local:
      - share-of-selected-total
      - temporary-scenario
      - page-specific-comparison

  consumers:
    - executive-sales-report
    - regional-sales-analysis
  reuse_expectation: multi-product
  criticality: high
  selected_home: shared-semantic-layer-on-governed-data-product

  ownership:
    business_owner: named-role
    data_steward: named-role
    implementation_owner: analytics-engineering

  controls:
    reconciliation_method: monthly-finance-reconciliation
    test_method:
      - warehouse-rule-tests
      - semantic-measure-acceptance-tests
    certification_need: required
    permitted_local_overrides:
      - presentation-only
      - no-change-to-population-grain-or-netting

  exception:
    status: none
    expiry: null

  related_assets:
    semantic_models:
      - sales-certified-model
    reports:
      - executive-sales-report
      - regional-sales-analysis

  migration_actions:
    - replace-report-local-net-revenue-copies
    - preserve-only-approved-visual-derivatives
  review_date: 2026-10-31
```

Das Artefakt erzeugt fünf sichtbare Outputs:

- die gewählte Platzierung;
- den Implementierungs-Owner;
- Migrationsmaßnahmen für Duplikate;
- den Zertifizierungskandidaten;
- das Review-Datum.

Ein Formelgenerator kann DAX, Tableau Calculations oder Qlik Expressions umsetzen, nachdem diese Entscheidung getroffen wurde. Er kann nicht entscheiden, ob die Fachdefinition freigegeben ist, ob Wiederverwendung eine Promotion rechtfertigt oder ob ein lokaler Override erlaubt ist.

## Tools

Nutze die vorhandenen Binom-Tools, um Evidenz zu sammeln und die freigegebene Entscheidung umzusetzen:

- [KPI Definition Card](/tools/kpi-definition) — erfasst Business-Frage, Definition, Grain, Formel, Owner und Agreement Status.
- [Report Inventory Canvas](/tools/report-inventory) — identifiziert doppelte Bezeichnungen, kopierte Formeln, lokale Overrides, Owner und betroffene Reports.
- [BI Python Export Toolkit](/tools/bi-python-toolkit) — extrahiert Qlik-KPI-, App-, Sheet- und BI-übergreifende Formelinventare für größere Landschaften.
- [Power BI DAX Measure Generator](/tools/powerbi-dax-generator) — erzeugt Implementierungssnippets und Dokumentation aus einer freigegebenen Measure-Definition.
- [Tableau Calculation Generator](/tools/tableau-calculation-generator) — erzeugt Calculated-Field- und LOD-Varianten, nachdem die Berechnungsgrenze entschieden wurde.
- [Qlik Set Analysis Generator](/tools/qlik-set-analysis-generator) — erzeugt kontrollierte Child Measures und Variablen aus einer freigegebenen Basis-Measure.

Die Tools unterstützen Discovery und Implementierung. Sie ersetzen weder die Metric Placement Decision noch die Freigabe durch den Owner.

## Ressourcen

- [Eine Geschäftsfrage, verschiedene BI-Engines](/playbooks/bi-tools) — vergleicht Berechnungskontext und Governance in Qlik, Power BI, Tableau, Looker, SAP Analytics Cloud und Excel.
- [Microsoft Learn — Semantic Models im Power BI Service](https://learn.microsoft.com/de-de/power-bi/connect-data/service-datasets-understand)
- [Microsoft Learn — Endorsement von Power-BI-Inhalten](https://learn.microsoft.com/de-de/power-bi/collaborate-share/service-endorsement-overview)
- [Microsoft Learn — Star Schema und Measures in Power BI](https://learn.microsoft.com/de-de/power-bi/guidance/star-schema)
- [Tableau Help — Best Practices für veröffentlichte Data Sources](https://help.tableau.com/current/pro/desktop/en-us/publish_datasources_about.htm)
- [Tableau Help — Custom Fields mit Berechnungen erstellen](https://help.tableau.com/current/pro/desktop/en-us/calculations_calculatedfields.htm)
- [Qlik Help — Measures mit Master Measures wiederverwenden](https://help.qlik.com/de-DE/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Measures/create-master-measure.htm)
- [Qlik Help — Master Measures in Expressions verwenden](https://help.qlik.com/de-DE/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Measures/use-master-measures-expressions.htm)

> **Feature-Stand:** Juli 2026. Produktnamen, Berechtigungen, Endorsement-Verhalten und Metadatenfunktionen können sich ändern. Prüfe vor der Umsetzung die Dokumentation der eingesetzten Plattformversion und des Lizenzmodells.

## Playbooks

Verwende diese Playbooks wieder, statt ihre Entscheidungen neu zu definieren:

- [Business-Logik außerhalb der BI-Apps halten](/playbooks/keeping-business-logic-outside-bi-apps) — definiert die Grenze zwischen wiederverwendbarer Fachlogik und notwendigen consumer-spezifischen Funktionen.
- [KPI-Definition, Ownership und Versionierung](/playbooks/define-kpi) — liefert den freigegebenen KPI-Vertrag, Grain, Zeitlogik, Owner und Change-Status für die Platzierungsentscheidung.
- [The Missing Pieces — Trusted Metrics](/playbooks/missing-pieces-trusted-metrics) — prüft, ob Definition, Ownership, Qualität, Lineage und Zertifizierung für vertrauenswürdige Nutzung ausreichen.

Diese Story ersetzt die Playbooks nicht. Sie verwendet deren Ergebnisse für eine engere Frage: **Wo soll diese Kennzahl implementiert und governed werden?**

## Nächster Schritt

Wähle eine duplizierte oder geschäftskritische Kennzahl aus dem Report-Inventar und erstelle ihre Metric Placement Decision, bevor Formeln umgeschrieben werden.

Die erste Umsetzung sollte die vollständige Kette nachweisen:

```text
Freigegebene KPI-Definition
→ Metric Placement Decision
→ Governte Warehouse-Faktenbasis oder Data Product
→ Gemeinsame semantische Measure, wenn erforderlich
→ Kontrollierte lokale Ableitungen
→ Reconciliation- und Zertifizierungsevidenz
```

Beginne nicht mit der Verschiebung aller Berechnungen. Beginne mit einer Kennzahl, deren aktuelle Platzierung nachweisbare Inkonsistenz, Risiko oder Wartungsaufwand erzeugt. Promote den wiederverwendbaren Kern, behalte gerechtfertigtes lokales Verhalten und weise jeder konkurrierenden Kopie eine Migrationsmaßnahme zu.

Der nächste Teil der Serie behandelt den Zertifizierungslebenszyklus für Fabric- und Power-BI-Kennzahlen, nachdem die Platzierungsgrenze entschieden wurde.
