---
title: Data Quality Governance
description: Ein praxisnahes Betriebsmodell für verlässliche, vollständige, konsistente, aktuelle und zweckgeeignete Daten — mit klaren Verantwortlichkeiten, messbaren Regeln und kontinuierlicher Verbesserung.
category: Data Governance
tags:
  - data-governance
  - data-quality
  - data-observability
  - data-testing
  - data-stewardship
  - quality-rules
  - monitoring
  - data-products
order: -1
series: governance-pillars
seriesPart: 6
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/quality-gov-hero.png
---

## Vertrauen entsteht nicht durch vorhandene Daten, sondern durch verlässliche Daten

Daten sind nicht automatisch vertrauenswürdig, nur weil sie technisch verfügbar sind.

Ein Report kann pünktlich geladen werden und trotzdem falsche Werte enthalten. Ein Datenprodukt kann vollständig dokumentiert sein und dennoch wichtige Datensätze verlieren. Eine Pipeline kann ohne technischen Fehler durchlaufen, obwohl fachliche Regeln verletzt wurden.

**Data Quality Governance** schafft deshalb einen verbindlichen Rahmen für die Frage:

***Welche Qualität benötigen Daten für ihren konkreten Zweck — und wie wird diese Qualität messbar, steuerbar und dauerhaft verbessert?***

Typische Probleme in gewachsenen Datenlandschaften sind:

- Qualitätsregeln existieren nur im Kopf einzelner Fachpersonen
- technische Tests prüfen Strukturen, aber nicht fachliche Bedeutung
- jede Plattform verwendet andere Schwellenwerte
- Fehler werden erst im Report oder beim Management sichtbar
- Datenqualität wird zentral gemessen, aber niemand fühlt sich verantwortlich
- bekannte Probleme bleiben dauerhaft offen
- Qualitätskennzahlen werden veröffentlicht, ohne Konsequenzen auszulösen
- Datenprodukte besitzen kein definiertes Qualitätsniveau
- schlechte Daten werden downstream vervielfältigt
- Ursache und Auswirkung sind wegen fehlender Lineage schwer erkennbar

> *Data Quality Governance verbindet fachliche Erwartungen, technische Regeln, Ownership, Monitoring und Verbesserung zu einem kontinuierlichen Betriebsmodell.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/quality-gov-de.png"
        alt="Data Quality Governance mit Qualitätsdimensionen, Management-Zyklus, Rollen, Qualitätsaktivitäten, Kennzahlen und Reifegrad"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Data Quality Governance macht Qualität zu einer gemeinsam verantworteten und kontinuierlich gesteuerten Eigenschaft von Datenprodukten.
    </figcaption>
</figure>

## Datenqualität ist immer zweckgebunden

Es gibt keine universell „gute“ Datenqualität.

Ein Datensatz kann für eine grobe Trendanalyse geeignet, für eine regulatorische Meldung aber unzureichend sein. Eine Adresse kann für regionale Aggregationen ausreichen, aber nicht für eine Vertragszustellung.

Datenqualität sollte daher immer im Kontext bewertet werden:

```text
Daten
  +
Verwendungszweck
  +
Qualitätserwartung
  +
Messbare Regel
  =
Bewertbare Eignung
```

Die zentrale Frage lautet nicht:

*„Sind die Daten perfekt?“*

Sondern:

***„Sind die Daten für den vereinbarten Zweck ausreichend verlässlich?“***

## Die wichtigsten Dimensionen der Datenqualität

| Dimension | Leitfrage | Beispiel |
| --- | --- | --- |
| **Genauigkeit** | Bilden die Daten die reale Welt korrekt ab? | Der Kundenstatus entspricht dem tatsächlichen Vertragsstatus |
| **Vollständigkeit** | Sind alle erforderlichen Werte vorhanden? | Jede aktive Bestellung besitzt eine Kundennummer |
| **Konsistenz** | Stimmen Daten über Systeme und Zeit hinweg überein? | Kundensegment ist in CRM und Warehouse identisch |
| **Aktualität** | Sind Daten rechtzeitig verfügbar und auf dem erwarteten Stand? | Tagesumsatz ist bis 07:00 Uhr vollständig geladen |
| **Validität** | Entsprechen Werte Format, Wertebereich und Geschäftsregel? | Enddatum liegt nicht vor dem Startdatum |
| **Eindeutigkeit** | Existieren keine unzulässigen Duplikate? | Eine Kundennummer identifiziert genau einen Kunden |
| **Integrität** | Sind Beziehungen und technische Abhängigkeiten korrekt? | Jede Bestellung verweist auf einen vorhandenen Kunden |
| **Relevanz** | Sind die Daten für den vorgesehenen Zweck geeignet? | Das KPI-Modell enthält die benötigte Granularität |
| **Konformität** | Entsprechen Daten vereinbarten Standards und Definitionen? | Ländercodes verwenden konsistent ISO-Codes |
| **Nachvollziehbarkeit** | Sind Herkunft, Transformation und Verantwortlichkeit erkennbar? | KPI-Wert lässt sich bis zur Quelle zurückverfolgen |

Nicht jedes Asset benötigt alle Dimensionen in gleicher Gewichtung. Kritische Datenprodukte sollten ein explizites Qualitätsprofil erhalten.

## Vom Qualitätsziel zur messbaren Regel

Eine gute Qualitätsregel ist nicht nur verständlich, sondern auch ausführbar und verantwortbar.

Beispiel:

```text
Geschäftserwartung:
Jede aktive Bestellung muss einem gültigen Kunden zugeordnet sein.

Messbare Regel:
customer_id IS NOT NULL
AND customer_id EXISTS IN customer_master

Schwellenwert:
99,95 % bestanden

Owner:
Order Data Owner

Steward:
Sales Data Steward

Reaktion:
Warnung ab 99,95 %
Incident unter 99,50 %
```

Eine vollständige Regelbeschreibung kann enthalten:

| Metadatum | Beispiel |
| --- | --- |
| **Regelname** | `active_order_has_valid_customer` |
| **Beschreibung** | Aktive Bestellungen benötigen einen gültigen Kunden |
| **Dimension** | Integrität |
| **Betroffenes Asset** | `conform.orders` |
| **Geschäftliche Kritikalität** | Hoch |
| **Schwellenwert** | 99,95 % |
| **Messfrequenz** | Nach jedem Load |
| **Data Owner** | Sales Domain Owner |
| **Data Steward** | Order Data Steward |
| **Technischer Owner** | Data Platform Team |
| **Fehleraktion** | Incident + Blockierung der Zertifizierung |
| **Ausnahmeprozess** | Genehmigte Ausnahme mit Ablaufdatum |
| **Letzter Review** | 2026-07-01 |

Damit wird aus einer allgemeinen Erwartung ein steuerbarer Governance-Kontrollpunkt.

## Das Data Quality Operating Model

Ein belastbares Qualitätsmodell folgt einem kontinuierlichen Zyklus.

```text
1. Qualitätsziele und Verantwortlichkeiten definieren
        ↓
2. Daten profilieren und Regeln implementieren
        ↓
3. Qualität messen und überwachen
        ↓
4. Ursachen und Auswirkungen analysieren
        ↓
5. Korrektur- und Präventionsmaßnahmen umsetzen
        ↓
6. Ergebnisse validieren und Standards verbessern
        ↺
```

## 1. Qualitätsziele und Verantwortlichkeiten definieren

Nicht jede Tabelle benötigt dieselbe Governance-Tiefe.

Der Einstieg sollte risikobasiert erfolgen:

- regulatorisch relevante Daten
- finanzielle Kennzahlen
- kritische operative Prozesse
- stark genutzte Datenprodukte
- sensible oder personenbezogene Daten
- zentrale Stammdaten
- Daten mit vielen Downstream-Abhängigkeiten
- Daten mit wiederkehrenden Problemen

Für jedes priorisierte Asset sollte geklärt werden:

- Welcher Business-Prozess hängt davon ab?
- Welche Nutzer und Entscheidungen sind betroffen?
- Welche Qualitätsdimensionen sind kritisch?
- Welche Mindestqualität wird erwartet?
- Wer entscheidet über Schwellenwerte?
- Wer reagiert auf Verstöße?
- Welche Konsequenz hat ein Fehler?
- Darf das Asset bei Unterschreitung weiter genutzt werden?

## 2. Daten profilieren und Regeln implementieren

Data Profiling schafft ein objektives Ausgangsbild.

Typische Profiling-Ergebnisse sind:

- Null-Anteile
- Werteverteilungen
- minimale und maximale Werte
- Formatmuster
- Kardinalität
- Duplikate
- Ausreißer
- häufigste Werte
- neue oder verschwundene Kategorien
- historische Veränderungen
- Referenzverletzungen

Profiling erkennt Auffälligkeiten, definiert aber noch keine fachliche Qualität.

Beispiel:

Ein Feld besitzt 0 % Nullwerte. Technisch wirkt es vollständig. Wenn jedoch überall der Platzhalter `UNKNOWN` steht, ist die fachliche Vollständigkeit trotzdem schlecht.

Deshalb sollte Profiling mit fachlichen Regeln kombiniert werden.

## 3. Qualität messen und überwachen

Qualitätsmessung sollte möglichst nah an der Datenentstehung beginnen.

Ein mögliches Kontrollmodell:

```text
Source
  ↓
Ingestion Checks
  ↓
RAW Structural Tests
  ↓
Conform Business Rules
  ↓
Analytics Reconciliation
  ↓
BI / KPI Validation
```

Typische Kontrollarten sind:

| Kontrollart | Beispiel |
| --- | --- |
| **Schema-Test** | Pflichtspalten existieren und besitzen erwartete Datentypen |
| **Null-Test** | Kritische Schlüssel sind vollständig |
| **Unique-Test** | Geschäftsschlüssel sind eindeutig |
| **Referenztest** | Fremdschlüssel verweisen auf gültige Datensätze |
| **Wertebereich** | Prozentwerte liegen zwischen 0 und 100 |
| **Geschäftsregel** | Stornierte Bestellungen besitzen ein Stornodatum |
| **Volumenprüfung** | Datensatzanzahl weicht nicht unerwartet vom Normalbereich ab |
| **Freshness-Test** | Daten wurden innerhalb des erwarteten Zeitfensters aktualisiert |
| **Reconciliation** | Summen stimmen zwischen Quelle und Ziel innerhalb der Toleranz überein |
| **Anomalieerkennung** | Verteilungen oder Muster weichen signifikant vom Normalzustand ab |
| **Drift-Erkennung** | Schema, Werte oder Kategorien verändern sich unerwartet |
| **KPI-Test** | Berechnete Kennzahl entspricht der freigegebenen Definition |

Nicht jede Regel muss eine Pipeline blockieren.

Es können unterschiedliche Reaktionsstufen gelten:

```text
INFO
→ sichtbar machen

WARNING
→ Owner informieren und beobachten

ERROR
→ Incident auslösen und Zertifizierung entziehen

CRITICAL
→ Veröffentlichung oder Pipeline blockieren
```

## 4. Ursachen und Auswirkungen analysieren

Ein Qualitätsproblem sollte nicht nur lokal betrachtet werden.

Beispiel:

```text
CRM source
   ↓
customer_email contains invalid values
   ↓
RAW customer
   ↓
CONFORM customer
   ↓
Customer 360
   ↓
Campaign audience
   ↓
Delivery failures and incorrect KPI
```

Lineage hilft bei zwei Fragen:

- Wo ist der Fehler entstanden?
- Welche Datenprodukte, Reports und Entscheidungen sind betroffen?

Root-Cause-Analyse sollte unterscheiden zwischen:

- fehlerhafter Erfassung an der Quelle
- unvollständiger Schnittstelle
- falscher Transformation
- fehlender oder veralteter Referenz
- unklarer fachlicher Definition
- technischem Ladefehler
- verspäteter Datenlieferung
- unbeabsichtigter Schemaänderung
- fehlerhafter manueller Pflege
- ungeeignetem Qualitätsziel

Die Ursache sollte möglichst dort behoben werden, wo sie entsteht. Dauerhafte Bereinigung im Reporting verschiebt das Problem nur.

## 5. Korrektur- und Präventionsmaßnahmen umsetzen

Maßnahmen können auf mehreren Ebenen liegen.

| Ebene | Typische Maßnahme |
| --- | --- |
| **Quelle** | Pflichtfeld, Eingabevalidierung, Prozessanpassung |
| **Schnittstelle** | Vertrag, Schema, Retry, Fehlerkanal |
| **Pipeline** | Transformationslogik korrigieren |
| **Datenmodell** | Geschäftsregel oder Referenz sauber abbilden |
| **Governance** | Definition, Owner oder Schwellenwert klären |
| **Datenbereinigung** | Werte korrigieren, standardisieren oder deduplizieren |
| **Monitoring** | zusätzliche Regel oder Anomalieerkennung einführen |
| **Training** | Datenpflege und Qualitätsverantwortung verbessern |

Jede Maßnahme sollte mindestens besitzen:

- verantwortliche Rolle
- Priorität
- Zieltermin
- erwartete Wirkung
- Validierungskriterium
- Status
- dokumentierte Entscheidung

## 6. Ergebnisse validieren und Standards verbessern

Ein geschlossenes Ticket beweist noch keine nachhaltige Verbesserung.

Nach einer Korrektur sollte geprüft werden:

- Ist die Regel wieder bestanden?
- Ist die Ursache wirklich entfernt?
- Tritt der Fehler erneut auf?
- Haben sich Downstream-Daten korrigiert?
- Sind Reports und KPIs wieder vertrauenswürdig?
- Muss der Schwellenwert angepasst werden?
- Fehlt eine zusätzliche Präventionsregel?
- Soll die Lösung als Standard für andere Domänen übernommen werden?

Data Quality Governance ist deshalb immer ein Lern- und Verbesserungsprozess.

## Rollen und Verantwortlichkeiten

| Rolle | Typische Verantwortung |
| --- | --- |
| **Data Owner** | Legt Qualitätsziele, Kritikalität und akzeptable Risiken fest |
| **Data Steward** | Pflegt Regeln, Definitionen, Issues und fachliche Bewertungen |
| **Data Quality Lead** | Entwickelt Standards, Methoden, Scorecards und Governance-Prozesse |
| **Data Engineer** | Implementiert Tests, Monitoring und technische Korrekturen |
| **System Owner** | Verbessert Qualität an operativen Quellen und Schnittstellen |
| **Data Analyst** | Meldet Auffälligkeiten und bewertet Auswirkungen auf Analysen |
| **Data Product Owner** | Verantwortet die Qualitätszusage des Datenprodukts |
| **Data Consumer** | Nutzt Daten gemäß Zertifizierungsstatus und meldet Probleme |

Data Quality darf nicht vollständig an ein zentrales Team delegiert werden.

Ein zentrales Team kann Standards und Plattformen bereitstellen. Die fachliche Verantwortung bleibt in der Domäne.

## Quality by Design

Qualität sollte bereits bei der Entwicklung berücksichtigt werden.

Ein Datenprodukt sollte vor der Veröffentlichung beantworten können:

- Welche fachliche Definition gilt?
- Wer ist Owner und Steward?
- Welche Qualitätsdimensionen sind kritisch?
- Welche Regeln werden automatisch geprüft?
- Welche Schwellenwerte gelten?
- Welche Freshness wird zugesagt?
- Was passiert bei einem Verstoß?
- Wie werden Nutzer informiert?
- Welche bekannten Einschränkungen existieren?
- Wie wird die Qualität kontinuierlich überwacht?

Ein mögliches Data Contract Fragment:

```yaml
data_product: customer_360
owner: customer-domain
quality:
  freshness:
    max_delay_minutes: 60
  completeness:
    customer_id:
      threshold: 1.0
    email:
      threshold: 0.98
  uniqueness:
    customer_id:
      threshold: 1.0
  incident_policy:
    critical_breach: block_certification
```

## Datenqualität entlang der Pipeline

Eine gute Regel liegt an der Stelle, an der sie den größten Erkenntniswert liefert.

| Schicht | Typische Qualitätskontrollen |
| --- | --- |
| **Source** | Eingabevalidierung, Pflichtfelder, Referenzen |
| **Ingestion** | Schema, Volumen, Lieferstatus, Duplikate |
| **RAW** | Struktur, technische Vollständigkeit, Ladeintegrität |
| **Conform** | Standardisierung, Referenzen, Geschäftsregeln |
| **Analytics** | Aggregationen, Berechnungen, fachliche Konsistenz |
| **Semantic / BI** | KPI-Definitionen, Filterlogik, Reconciliation |
| **Data Product** | SLA, Freshness, Zertifizierungsstatus, Nutzerhinweise |

Nicht jede Regel sollte erst im letzten Layer laufen. Je früher ein Problem erkannt wird, desto kleiner sind Auswirkung und Nacharbeit.

## Data Quality und Observability

Data Observability ergänzt klassische Qualitätsregeln.

Während definierte Regeln bekannte Erwartungen prüfen, sucht Observability zusätzlich nach unerwarteten Veränderungen.

Beispiele:

- plötzliches Absinken des Datenvolumens
- ungewöhnliche Nullraten
- neue Kategorien
- veränderte Werteverteilungen
- Schema-Drift
- verspätete Loads
- unerwartete Abhängigkeiten
- wiederkehrende Fehlercluster

Die Kombination ist besonders wirksam:

```text
Deterministische Regeln
        +
Profiling
        +
Anomalieerkennung
        +
Lineage
        +
Incident Management
        =
Operative Data Quality
```

## Issue Management

Qualitätsprobleme benötigen einen definierten Workflow.

Ein mögliches Modell:

```text
Detected
  ↓
Triaged
  ↓
Assigned
  ↓
Root Cause Identified
  ↓
Remediation in Progress
  ↓
Validated
  ↓
Closed
```

Pflichtinformationen können sein:

- Issue-ID
- betroffenes Asset
- Qualitätsdimension
- Schweregrad
- erkannte Regel
- Zeitpunkt der Erkennung
- Auswirkung
- betroffene Datenprodukte
- Root Cause
- Owner
- Zieltermin
- Korrekturmaßnahme
- Validierung
- Wiederholungsstatus

Issues sollten nicht nur nach technischer Dringlichkeit, sondern auch nach Business Impact priorisiert werden.

## Zertifizierung und Vertrauensstatus

Ein Datenprodukt kann einen sichtbaren Vertrauensstatus erhalten.

Beispiel:

| Status | Bedeutung |
| --- | --- |
| **Draft** | Noch nicht vollständig geprüft |
| **Observed** | Monitoring aktiv, aber keine formale Zusage |
| **Certified** | Definition, Owner, Regeln und Schwellenwerte geprüft |
| **Degraded** | Qualitätsziel aktuell unterschritten |
| **Restricted** | Nutzung nur unter dokumentierten Bedingungen |
| **Deprecated** | Nicht mehr für neue Nutzung vorgesehen |

Ein Zertifizierungsstatus sollte automatisch oder halbautomatisch auf Qualitätsereignisse reagieren.

Beispiel:

```text
Critical Rule Failed
        ↓
Status: Certified → Degraded
        ↓
Owner + Consumers notified
        ↓
Issue created
        ↓
Validation passed
        ↓
Status restored
```

## Data Quality Governance messen

Sinnvolle Kennzahlen sind:

- Data Quality Score je Datenprodukt oder Domäne
- Rule Pass Rate
- Anzahl aktiver Regeln
- Anteil kritischer Assets mit definierten Qualitätszielen
- Anteil zertifizierter Datenprodukte
- Anteil vollständiger Owner- und Steward-Zuordnungen
- Freshness-Erfüllungsquote
- Vollständigkeitsquote
- Anzahl Issues nach Schweregrad
- Mean Time to Detect (MTTD)
- Mean Time to Acknowledge (MTTA)
- Mean Time to Resolve (MTTR)
- Wiederholungsrate
- Anteil an der Quelle behobener Probleme
- Anzahl überfälliger Issues
- Anteil automatisiert validierter Regeln
- Anzahl manueller Ausnahmen
- Anteil von Qualitätsproblemen mit dokumentierter Root Cause
- Anzahl degradierter Datenprodukte
- Nutzervertrauen oder Zufriedenheit mit kritischen Datenprodukten

Ein einzelner Gesamtscore kann hilfreich sein, darf aber Details nicht verdecken.

## Beispiel für einen Quality Score

```text
Completeness:  98 %
Accuracy:      95 %
Freshness:    100 %
Consistency:   92 %

Gewichtung:
Completeness  30 %
Accuracy      35 %
Freshness     20 %
Consistency   15 %

Gesamtscore:
96,2 %
```

Der Score sollte transparent berechnet werden. Ein hoher Durchschnitt darf kritische Einzelverletzungen nicht verstecken.

Deshalb können harte Regeln unabhängig vom Score gelten:

```text
Gesamtscore >= 95 %
UND
keine kritische Regel verletzt
UND
Freshness-SLA erfüllt
=
Certified
```

## Ein einfaches Reifegradmodell

| Reifegrad | Typischer Zustand |
| --- | --- |
| **Reaktiv** | Probleme werden erst durch Nutzer oder Reports entdeckt |
| **Gemessen** | Erste Regeln und manuelle Scorecards existieren |
| **Standardisiert** | Gemeinsame Dimensionen, Regeltypen und Rollen sind definiert |
| **Automatisiert** | Tests und Monitoring laufen in Pipelines und Plattformen |
| **Metadatenbasiert** | Regeln, Owner, Kritikalität und Lineage sind integriert |
| **Produktorientiert** | Datenprodukte besitzen explizite Qualitätszusagen und Zertifizierung |
| **Proaktiv** | Observability und Anomalieerkennung identifizieren Probleme früh |
| **Eingebettet** | Quality by Design ist Bestandteil jedes Datenprodukts und jeder Änderung |

## Typische Anti-Patterns

Data-Quality-Initiativen scheitern häufig aus bekannten Gründen:

- Datenqualität wird erst im Reporting geprüft
- technische Tests werden mit fachlicher Qualität verwechselt
- Regeln besitzen keinen Owner
- Schwellenwerte werden ohne Business-Kontext festgelegt
- jede Domäne verwendet eigene Dimensionen und Begriffe
- Fehler werden bereinigt, aber nicht an der Ursache behoben
- ein zentraler Score versteckt kritische Probleme
- Warnungen werden dauerhaft ignoriert
- Issues besitzen keinen Zieltermin
- Datenqualität ist Aufgabe eines kleinen zentralen Teams
- zu viele Regeln erzeugen Alarmmüdigkeit
- Tests laufen nur nachts und nicht bei Änderungen
- bekannte Ausnahmen bleiben ohne Ablaufdatum bestehen
- Lineage wird nicht für Impact-Analyse genutzt
- Qualitätsstatus ist für Data Consumer nicht sichtbar
- schlechte Datenprodukte bleiben zertifiziert
- Erfolg wird an der Anzahl der Tests statt an reduzierten Business-Problemen gemessen

Ein bestandener Test ist kein Selbstzweck. Entscheidend ist, ob Daten verlässlich genug für ihren vereinbarten Zweck sind.

## Verbindung zu den anderen Governance-Säulen

| Säule | Verbindung |
| --- | --- |
| **Data Ownership & Stewardship** | Owner und Stewards entscheiden Qualitätsziele, Prioritäten und Maßnahmen |
| **Metadata, Catalog & Lineage** | Regeln, Scores, Verantwortlichkeiten und Auswirkungen werden auffindbar |
| **PII & Privacy Governance** | PII-Klassifikationen und Schutzmetadaten benötigen eigene Qualitätskontrollen |
| **DSDR Governance** | Identitätsschlüssel, Löschstatus und Validierung müssen zuverlässig sein |
| **KPI & Metric Governance** | Verlässliche Kennzahlen benötigen kontrollierte Eingangsdaten und definierte Regeln |
| **Access & Security Governance** | Qualitätsstatus und sensible Fehlerdaten benötigen kontrollierte Sichtbarkeit |
| **Data Lifecycle & Retention** | Historisierung, Archivierung und Löschung beeinflussen Vollständigkeit und Konsistenz |

Data Quality Governance ist damit keine isolierte Testdisziplin. Sie verbindet fachliche Erwartungen mit operativer Datenverantwortung.

## Praktisches Zielbild

```text
Business Expectation
        ↓
Data Owner + Steward
        ↓
Quality Rule + Threshold
        ↓
Automated Test + Monitoring
        ↓
Score + Certification Status
        ↓
Issue + Root Cause + Remediation
        ↓
Validation + Continuous Improvement
        ↓
Trusted Data Products
```

## Das Ergebnis

Wirksame Data Quality Governance schafft:

- **Vertrauen** — Nutzer verstehen, welche Daten verlässlich und zertifiziert sind
- **Transparenz** — Regeln, Scores, Verantwortlichkeiten und Probleme werden sichtbar
- **Kontrolle** — Qualität wird messbar und mit klaren Reaktionen verbunden
- **Effizienz** — Probleme werden früher erkannt und Nacharbeit reduziert
- **Verantwortlichkeit** — fachliche und technische Rollen arbeiten mit klaren Zuständigkeiten
- **Risikoreduktion** — falsche Entscheidungen, fehlerhafte Reports und regulatorische Probleme werden reduziert
- **Business Value** — Datenprodukte werden verlässlicher und schneller nutzbar

Hohe Datenqualität entsteht nicht durch möglichst viele Tests.

Sie entsteht durch die Verbindung von **klaren Erwartungen, fachlicher Verantwortung, automatisierter Messung und konsequenter Verbesserung**.

Verwandte Übersicht: [Die 8 Säulen der Data Governance](/playbooks/eight-pillars).

Vorherige Säule: [DSDR Governance](/playbooks/dsdr-governance).

Nächste Säule: [KPI & Metric Governance](/playbooks/kpi-metric-governance).
