---
title: Vom Stakeholder-Interview zum Tabellenmodell
description: Interview-Evidenz wird vor dem Bau eines physischen Marts in explizite Entscheidungen zu Business Event, Grain, Fakten, Dimensionen, Historie, Ownership und Scope übersetzt.
author: Thomas Lindackers
tags:
  - data-modeling
  - dimensional-modeling
  - grain
  - stakeholder-interviews
  - mart-design
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
publishedAt: 2026-07-28
category: Data Governance
order: -1
hero: images/playbooks/from-stakeholder-interview-to-table-model-hero.png
series: building-modern-data-warehouse
seriesTitle: Ein modernes Data Warehouse aufbauen
seriesPart: 11
---

Ein Stakeholder-Interview liefert Evidenz, Begriffe, Erwartungen und offene Fragen. Es liefert kein Tabellenmodell. Die Modellierungsarbeit beginnt erst, wenn diese Aussagen in explizite und überprüfbare Entscheidungen zu Geschäftsprozess, Business Event, Grain, Kennzahlen, Dimensionen, Historie, Ownership und Scope übersetzt werden.

## Problem

Stakeholder formulieren Anforderungen selten in Modellierungsbegriffen. Sie verlangen „Umsatz nach Kunde“, „die aktuelle Pipeline“, „alle Aktivitäten“, „eine Zeile pro Kunde“ oder „dieselben Zahlen wie im Management-Report“. Jede Aussage kann wertvolle Evidenz enthalten, ist aber noch nicht präzise genug, um eine Faktentabelle zu definieren.

Dasselbe Substantiv kann unterschiedliche Analyseobjekte bezeichnen. „Kunde“ kann das vertragliche Konto, den Rechnungsempfänger, eine juristische Einheit, einen Interessenten oder eine Konzernmutter meinen. „Umsatz“ kann gebuchten, fakturierten, realisierten, erwarteten oder wahrscheinlichkeitsgewichteten Umsatz bezeichnen. „Aktuell“ kann den neuesten Quellzustand, den Zustand zum Ausführungszeitpunkt eines Reports oder den Zustand an einem ausgewählten historischen Datum meinen.

Ein Report-Inventar zeigt häufig, dass diese Mehrdeutigkeiten bereits produktiv existieren. Zwei Reports verwenden dieselbe Bezeichnung, aber unterschiedliche Filter. Mehrere Reports berechnen denselben KPI verschieden. Ein Report mischt Transaktionsdaten mit dem neuesten Status, während ein anderer historische Snapshots rekonstruiert. Wer diese Strukturen in einen neuen Mart kopiert, übernimmt den Konflikt, statt ihn zu entscheiden.

Typische Fehlmuster sind vorhersehbar:

- Begriffe aus Interviews werden direkt in Tabellen- und Spaltennamen kopiert.
- Dimensionen werden ausgewählt, bevor der Grain feststeht.
- Mehrere Geschäftsprozesse werden in einer Faktentabelle vermischt.
- „Eine Zeile pro Kunde“ wird ohne Zeitsemantik akzeptiert.
- Schwierige Anforderungen verschwinden ohne dokumentierte Entscheidung.
- Das Layout eines bestehenden Reports wird als Zielmodell behandelt.
- KPI-Formeln werden implementiert, bevor Ownership und Freigabe geklärt sind.

Das Ergebnis kann technisch vollständig wirken und gleichzeitig semantisch instabil bleiben. Kennzahlen werden mehrdeutig, Historie lässt sich nicht erklären und spätere Änderungen öffnen Entscheidungen erneut, die nie dokumentiert wurden.

![Interview-Aussagen in Modellierungsentscheidungen übersetzen](images/playbooks/from-stakeholder-interview-to-table-model-img1-de.png)

## Entscheidung

Behandle jede Interview-Aussage als Evidenz, die eine kontrollierte Entscheidungskette durchlaufen muss:

```text
Stakeholder-Aussage
→ Klärung
→ Modellierungsentscheidung
→ dokumentierte Evidenz und Freigabe
```

Die Kernregel ist einfach: Identifiziere zuerst das Business Event und formuliere einen präzisen Grain-Satz, bevor Fakten oder Dimensionen ausgewählt werden.

Ein brauchbarer Grain-Satz nennt das Ereignis, das beteiligte Objekt und die Zeitsemantik. Zum Beispiel:

> Eine Zeile pro Opportunity-Position und Snapshot-Datum.

Diese Aussage unterscheidet sich wesentlich von „eine Zeile pro Opportunity“ oder „eine Zeile pro Kunde“. Sie definiert einen periodischen Snapshot auf Positionsebene. Damit bestimmt sie, welche Kennzahlen gemeinsam vorkommen dürfen, welche Dimensionen gültig sind und wie historische Analysen funktionieren.

Aus diesem Grain lassen sich Kandidaten gezielt bewerten:

- **Additive Fakten:** Menge oder erwarteter Umsatz, sofern die Summierung über die vorgesehenen Dimensionen gültig ist.
- **Semi-additive Fakten:** Bestände oder Pipeline-Werte, die über einige Dimensionen, aber nicht über die Zeit summiert werden dürfen.
- **Statusattribute:** Phase, Freigabestatus oder Risikokategorie, wenn sie den Zustand der Zeile statt einer Ereignismenge beschreiben.
- **Dimensionen:** Account, Opportunity, Produkt, Owner, Phase und Datum, wenn sie wiederverwendbaren analytischen Kontext liefern.
- **Degenerate Dimensions:** Geschäftskennungen wie eine Opportunity-Nummer, die zum Faktenereignis gehören, aber keine eigene beschreibende Dimension benötigen.
- **Filter und Regeln:** Einschlusskriterien, Statusrestriktionen, Währungsregeln und Zeitfenster-Semantik.
- **Explizite Ausschlüsse:** Aktivitäten, Freitextnotizen, nicht zugehörige Servicefälle oder andere Daten, die die freigegebene Entscheidung nicht unterstützen.

Transaktions-Grain und Snapshot-Grain dürfen nicht allein deshalb in einer Faktentabelle vermischt werden, weil beide dasselbe Geschäftsobjekt betreffen. Eine Transaktion dokumentiert, dass etwas geschehen ist. Ein Snapshot dokumentiert einen Zustand zu einem definierten Zeitpunkt. Die Vermischung erzeugt Kennzahlen, die nicht mehr konsistent interpretierbar sind.

![Grain vor Fakten und Dimensionen definieren](images/playbooks/from-stakeholder-interview-to-table-model-img2-de.png)

### Evidenz, Interpretation und Entscheidung trennen

Für jede relevante Interview-Aussage werden drei Ebenen getrennt dokumentiert:

1. **Evidenz:** Was hat der Stakeholder gesagt, welcher Report wurde gezeigt, welche KPI Card existiert und welches Quellverhalten wurde beobachtet?
2. **Interpretation:** Was glaubt das Team, dass die Aussage bedeutet, und welche Mehrdeutigkeit besteht weiterhin?
3. **Entscheidung:** Welcher Geschäftsprozess, welches Business Event, welcher Grain, welche Kennzahl, Dimension, Historienbehandlung oder Scope-Out wurde freigegeben?

Diese Trennung verhindert, dass eine Interpretation als Stakeholder-Fakt dargestellt wird. Sie ermöglicht außerdem eine spätere Überprüfung, wenn ein Report, Owner oder Quellsystem der ursprünglichen Annahme widerspricht.

### KPI-Semantik mit dem Ziel-Grain verbinden

Eine KPI Card muss mehr als eine Formel definieren. Sie sollte klären:

- fachliche Bedeutung und unterstützte Entscheidung
- Zähler, Nenner und Aggregationsverhalten
- Filter und Ausschlüsse
- Gültigkeitsdatum und Zeitsemantik
- Währung, Einheit und Umrechnungsregeln
- Ziel-Grain und erlaubte Drill-Pfade
- Calculation Owner und freigebender Data Owner
- Qualitätserwartungen und bekannte Einschränkungen

Ein KPI kann nur sicher implementiert werden, wenn seine Berechnung mit dem Mart-Grain kompatibel ist. Eine monatliche Conversion Rate, ein Snapshot auf Positionsebene und ein transaktionales Ereignis können getrennte Strukturen benötigen, obwohl sie auf demselben Dashboard erscheinen.

### Rollen als Entscheidungsgrenzen nutzen

Stakeholder Matrix und RACI ersetzen die Modellierung nicht. Sie legen fest, wer Evidenz liefern, Terminologie definieren, fachliche Entscheidungen treffen, das Modell entwerfen und das Ergebnis freigeben darf.

- Der **Data Owner** genehmigt fachliche Bedeutung, erlaubte Nutzung, wesentlichen Scope und die Akzeptanz verbleibender Risiken.
- Der **Data Steward** pflegt Terminologie, Definitionen, Klassifikationen und semantische Konsistenz.
- Der **Data Architect** übersetzt freigegebene Semantik in Entscheidungen zu Grain, Fakten, Dimensionen, Historie und Contracts.
- Quell- und Plattformverantwortliche bestätigen technische Evidenz, Machbarkeit und operative Einschränkungen.
- Consumer validieren, dass der resultierende Mart die beabsichtigte Entscheidung unterstützt, ohne jede report-spezifische Präferenz zu übernehmen.

Die detaillierten Rollenmechaniken bleiben in den verlinkten RACI- und Rollen-Playbooks. Diese Story nutzt sie, um die Modellentscheidung überprüfbar zu machen.

## Checkliste

Nutze diese Checkliste vor der Freigabe eines Mart-Designs:

### Evidenz

- [ ] Interview-Aussagen sind getrennt von Interpretationen gespeichert.
- [ ] Das Report-Inventar identifiziert doppelte, widersprüchliche und report-spezifische Anforderungen.
- [ ] Vorhandene KPI Cards und Definitionen sind verlinkt.
- [ ] Quell-Evidenz ist benannt und nachvollziehbar.
- [ ] Offene Fragen besitzen einen Owner und ein erforderliches Entscheidungsdatum oder einen Trigger.

### Business Event und Grain

- [ ] Ein Geschäftsprozess ist benannt.
- [ ] Das Business Event ist explizit.
- [ ] Der Grain ist als ein präziser Satz formuliert.
- [ ] Die Zeitsemantik ist explizit: Transaktionszeit, Gültigkeitszeit, Snapshot-Datum oder aktueller Zustand.
- [ ] Der Grain ist mit den vorgesehenen KPI-Berechnungen kompatibel.
- [ ] Transaktions- und Snapshot-Prozesse werden nicht stillschweigend vermischt.

### Fakten und Dimensionen

- [ ] Jedes Fakt besitzt eine definierte Bedeutung, Einheit und ein Aggregationsverhalten.
- [ ] Statusattribute werden nicht fälschlich als additive Kennzahlen behandelt.
- [ ] Dimensionen beschreiben den deklarierten Grain.
- [ ] Conformed Dimensions und Schlüssel sind identifiziert, wenn Mart-übergreifende Wiederverwendung erforderlich ist.
- [ ] Degenerate Dimensions werden bewusst für Geschäftskennungen verwendet.
- [ ] Das Historienverhalten für Fakten und Dimensionen ist spezifiziert.

### Governance und Scope

- [ ] Data Owner, Steward, Architect und Freigebende sind benannt.
- [ ] PII-, Zugriffs- und Permitted-Use-Anforderungen sind dokumentiert.
- [ ] Qualitätsprüfungen sind mit fachlichen Akzeptanzkriterien verbunden.
- [ ] Annahmen sind sichtbar.
- [ ] Zurückgestellte und ausgeschlossene Anforderungen enthalten Begründung, Evidenz, Owner und Review-Trigger.
- [ ] Keine schwierige Anforderung wurde stillschweigend entfernt.

## Artefakt

Das primäre Ergebnis ist ein **Mart Design Brief** (`mart-design-brief.md`). Er bildet den überprüfbaren Contract zwischen Interview-Evidenz und physischer Tabellenerstellung. KPI-Karten exportierst du als `kpi-cards.csv`; den freigegebenen Scope als `source-scope.csv`.

![Von KPI Card und RACI zum Mart Design Brief](images/playbooks/from-stakeholder-interview-to-table-model-img3-de.png)

Ein brauchbarer Mart Design Brief enthält:

```yaml
mart:
  name: sales_pipeline_snapshot
  purpose: Entscheidungen zu Pipeline-Wert, Coverage und Phasenbewegung unterstützen
  consumers:
    - sales-management
    - account-management

business_process:
  name: opportunity-pipeline-monitoring
  event: opportunity-line-state-at-snapshot
  grain: Eine Zeile pro Opportunity-Position und Snapshot-Datum

facts:
  - name: quantity
    aggregation: additive
    owner: sales-operations
  - name: expected_revenue
    aggregation: additive-within-currency
    owner: finance-and-sales-operations
  - name: probability_weighted_revenue
    aggregation: derived
    calculation_owner: sales-operations

dimensions:
  - account
  - opportunity
  - product
  - owner
  - stage
  - snapshot_date

history:
  pattern: periodic-snapshot
  cadence: daily
  late_arriving_policy: documented-before-build

quality:
  - opportunity_line_identifier_is_present
  - snapshot_date_is_present
  - probability_is_within_valid_range
  - expected_revenue_currency_is_known

security:
  pii: assessed
  access: role-and-region-policy

scope:
  included:
    - opportunity-line-state
    - approved-pipeline-measures
  deferred:
    - activity-engagement-score
  excluded:
    - free-text-notes
    - unrelated-service-cases

assumptions:
  - source-stage-values-use-approved-mapping

approvals:
  data_owner: named-person-or-role
  steward: named-person-or-role
  architect: named-person-or-role
```

Die konkrete Form kann abweichen, der Brief muss jedoch dieselben Entscheidungsfragen beantworten. Er sollte freigegeben sein, bevor physische Tabellen, Orchestrierung oder Report-Migration beginnen.

### Scope-Out als Governance-Entscheidung dokumentieren

Scope-Out ist kein Backlog-Friedhof. Jede angefragte Position gehört in eine von drei Spuren:

- **Jetzt bauen:** für die erste freigegebene Entscheidung erforderlich, mit dem Grain kompatibel, durch bekannte Quelle und Owner unterstützt und anhand von Akzeptanzkriterien testbar.
- **Zurückstellen:** valider Bedarf, dem Quelle, Owner, Definition oder unmittelbare Priorität fehlen, mit benanntem Trigger für die erneute Bewertung.
- **Ausschließen:** nicht unterstützt, doppelt, von geringem Wert, Grain-inkompatibel oder im Verhältnis zu PII, Risiko oder Kosten nicht gerechtfertigt.

Für jede zurückgestellte oder ausgeschlossene Position werden Begründung, Decision Owner, Evidenz, Review-Trigger und betroffene Consumer dokumentiert.

![Scope-Out explizit halten](images/playbooks/from-stakeholder-interview-to-table-model-img4-de.png)

## Tools

Nutze die vorhandenen Tools, um Evidenz zu sammeln und zu strukturieren:

- [Stakeholder Matrix](/tools/stakeholder-matrix) — identifiziert Evidenzgeber, Decision Owner, Reviewer und betroffene Consumer.
- [Report Inventory](/tools/report-inventory) — vergleicht bestehende Reports, Formeln, Filter, Grains und Widersprüche.
- [KPI Requirements Intake](/tools/kpi-requirements-intake) — erfasst KPI-Bedeutung, Filter, Zeitsemantik, Ownership und Freigabe.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — überführt freigegebene Entscheidungen in einen überprüfbaren Mart Contract.

Diese Tools unterstützen den Entscheidungsprozess. Sie ersetzen weder die Freigabe durch den Owner noch das Architektururteil.

## Ressourcen

Halte folgende Evidenz am Mart Design Brief verfügbar:

- Interview-Notizen mit Quelle, Datum und Teilnehmerrolle
- verlinkte Report-Beispiele und Screenshots
- Einträge aus dem Report-Inventar mit markierten Berechnungskonflikten
- freigegebene KPI Cards
- Beispiele für Quellfelder und relevante Werteverteilungen
- Terminologieentscheidungen und offene Synonyme
- Annahmen, offene Fragen und Scope-Out-Register
- Freigabenachweis und Entscheidungshistorie

Ein Model Review sollte jedes wesentliche Feld und jede Berechnung auf Evidenz und eine explizite Entscheidung zurückführen können.

## Playbooks

Verwende diese vorhandenen Playbooks als Entscheidungsinput:

- [Vor dem Bau der ersten Tabelle](/playbooks/before-building-the-first-table) — nutzt die grundlegende Sequenz zur Bestätigung von Business-Frage, Prozess und Entscheidung vor der Implementierung.
- [KPI definieren](/playbooks/define-kpi) — legt KPI-Semantik, Filter, Zeitverhalten, Ownership und Akzeptanz fest.
- [RACI für Data Governance](/playbooks/raci-for-data-governance) — weist Verantwortung, Accountability, Consultation und Informationspflichten für die Entscheidung zu.
- [Rolle des Data Architect](/playbooks/data-architect-role) — bewahrt die Grenze zwischen fachlicher Freigabe, Stewardship, Architektur und Plattformausführung.

Dieser Artikel ersetzt diese Playbooks nicht. Er verbindet ihre Ergebnisse mit einem spezifischen Deliverable: einer überprüfbaren Entscheidung über das Tabellenmodell.

## Nächster Schritt

Gib den Mart Design Brief frei, bevor der physische Mart erstellt wird. Der nächste Implementierungsschritt sollte das freigegebene Business Event, den Grain, Fakten, Dimensionen, Historie, Controls und Scope als Constraints verwenden — und diese Entscheidungen nicht implizit in SQL, dbt-Modellen, semantischen Schichten oder Dashboard-Logik neu öffnen.

Ein Design ist baubereit, wenn Reviewer fünf Fragen ohne Codeinspektion beantworten können:

1. Welche Entscheidung unterstützt der Mart?
2. Was repräsentiert exakt eine Zeile?
3. Welche Kennzahlen sind auf diesem Grain gültig?
4. Wer verantwortet Bedeutung und Freigabe?
5. Was ist bewusst nicht enthalten, und warum?

Wenn diese Antworten explizit sind, wird das Tabellenmodell zu einem Governance Contract statt zu einer undokumentierten Interpretation eines Interviews.
