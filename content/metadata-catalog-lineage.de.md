---
title: Metadata, Catalog & Lineage
description: Ein praxisnahes Operating Model für verständliche Metadaten, auffindbare Datenassets und nachvollziehbare Datenflüsse von der Quelle bis zum Business Use Case.
category: Data Governance
tags:
  - data-governance
  - metadata
  - data-catalog
  - data-lineage
  - business-glossary
  - impact-analysis
  - data-discovery
order: -1
publishedAt: 2026-06-03
series: governance-pillars
seriesPart: 3
seriesTitle: Die 8 Säulen der Data Governance
hero: images/playbooks/metadata-lineage-hero.png
---

## Daten brauchen Kontext

Daten können technisch korrekt gespeichert und verarbeitet werden — und trotzdem unverständlich, schwer auffindbar oder riskant zu ändern sein.

Typische Fragen bleiben dann unbeantwortet:

- Welche Datenassets existieren überhaupt?
- Was bedeutet eine Tabelle, Spalte, Kennzahl oder ein Datenprodukt fachlich?
- Wer ist verantwortlich?
- Welche Daten sind vertrauenswürdig und für welchen Zweck freigegeben?
- Woher stammen die Daten?
- Welche Transformationen wurden angewendet?
- Welche Reports, Modelle oder Prozesse sind von einer Änderung betroffen?

**Metadata, Catalog & Lineage** schafft den gemeinsamen Kontext, der aus technischen Datenbeständen verständliche und vertrauenswürdige Datenassets macht.

> *Ein Katalog macht Daten auffindbar. Metadaten machen sie verständlich. Lineage macht ihre Herkunft, Abhängigkeiten und Auswirkungen nachvollziehbar.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-lineage-de.png"
        alt="Operating Model für Metadaten, Data Catalog und Data Lineage mit Kernfähigkeiten, Prozessschritten und Governance-Nutzen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Metadaten, Katalog und Lineage verbinden technische Strukturen mit fachlichem Kontext, Ownership, Qualität und nachvollziehbaren Datenflüssen.
    </figcaption>
</figure>

## Metadaten sind mehr als technische Eigenschaften

Metadaten werden häufig auf Tabellen-, Spalten- und Datentypinformationen reduziert. Diese technischen Informationen sind wichtig, beantworten aber nur einen Teil der relevanten Fragen.

Ein belastbares Governance-Modell verbindet mehrere Metadatenarten:

| Metadatenart | Typische Inhalte | Zentrale Frage |
| --- | --- | --- |
| **Technische Metadaten** | Tabellen, Spalten, Datentypen, Modelle, Dateien, Jobs, APIs | Wie ist das Datenasset technisch aufgebaut? |
| **Fachliche Metadaten** | Definitionen, Glossarbegriffe, Domänen, Nutzungskontext | Was bedeuten die Daten? |
| **Governance-Metadaten** | Owner, Stewards, PII, Kritikalität, Policies, Aufbewahrung | Wer ist verantwortlich und welche Regeln gelten? |
| **Operative Metadaten** | Laufzeiten, Aktualität, Fehler, Nutzung, Volumen, SLAs | Wie verhält sich das Datenasset im Betrieb? |
| **Qualitätsmetadaten** | Regeln, Testergebnisse, Schwellenwerte, Incidents | Kann den Daten vertraut werden? |
| **Semantische Metadaten** | KPIs, Metriken, Dimensionen, Berechnungslogik | Wie werden Daten fachlich ausgewertet? |

Der Wert entsteht nicht durch möglichst viele Felder, sondern durch die Verbindung dieser Informationen zu einem nutzbaren Kontext.

## Die drei Kernfähigkeiten

### Metadata Management

Metadata Management definiert, erfasst, standardisiert und pflegt den Kontext rund um Datenassets.

Typische Aufgaben sind:

- technische Metadaten automatisiert aus Plattformen und Pipelines erfassen
- fachliche Beschreibungen und Definitionen ergänzen
- Begriffe, Tags und Klassifikationen standardisieren
- Owner, Stewards und Domänen verknüpfen
- PII-, Vertraulichkeits- und Kritikalitätskennzeichnungen pflegen
- Qualitäts-, Zugriffs- und Aufbewahrungsregeln zuordnen
- Änderungen versionieren und überprüfbar machen

Metadaten sollten möglichst nah an ihrer Entstehung gepflegt werden. Technische Informationen gehören in automatisierte Integrationen; fachliche Definitionen benötigen verantwortliche Rollen und einen geregelten Review-Prozess.

### Data Catalog

Ein Data Catalog ist die nutzbare Oberfläche auf Metadaten. Er hilft Menschen und Systemen, Datenassets zu finden, zu verstehen, zu bewerten und korrekt zu verwenden.

Ein guter Katalog unterstützt mindestens:

- Suche nach Namen, Beschreibungen, Tags und fachlichen Begriffen
- Navigation über Domänen, Datenprodukte und Abhängigkeiten
- sichtbare Owner- und Steward-Zuordnung
- fachliche und technische Definitionen
- Qualitätsstatus, Zertifizierung und bekannte Einschränkungen
- Klassifikationen und Schutzbedarf
- Lineage und Impact Analysis
- Verknüpfungen zu Dokumentation, Code, Modellen und Reports

Ein Katalog ist kein Selbstzweck und keine Ablage für automatisch importierte Tabellen. Er wird wertvoll, wenn Nutzer schneller eine belastbare Antwort auf die Frage erhalten: **Kann ich dieses Datenasset für meinen Zweck verstehen und verwenden?**

### Data Lineage

Data Lineage zeigt, wie Daten von einer Quelle durch Transformationen, Modelle und Datenprodukte bis zu Reports, APIs oder Geschäftsprozessen fließen.

Lineage unterstützt:

- Herkunft und Verarbeitung nachvollziehen
- Abhängigkeiten zwischen Quellen, Modellen und Reports erkennen
- Auswirkungen geplanter Änderungen bewerten
- Root-Cause-Analysen bei Qualitätsproblemen beschleunigen
- PII- und Governance-Metadaten entlang der Datenkette verstehen
- Audit- und Compliance-Nachweise verbessern
- Vertrauen in Kennzahlen und Datenprodukte erhöhen

Lineage ist dann besonders wertvoll, wenn sie nicht nur technische Knoten zeigt, sondern fachlichen Kontext, Ownership, Qualität und Kritikalität einbezieht.

## Der Data Consumer gehört zum Modell

Metadaten und Kataloge werden nicht für Governance-Teams gebaut, sondern für Menschen, die Daten finden, verstehen, verändern oder verwenden müssen.

Data Consumer sollten in der Lage sein:

- vertrauenswürdige Datenprodukte schnell zu finden
- Definitionen und Nutzungskontext zu verstehen
- Owner und Stewards zu identifizieren
- Qualität, Aktualität und bekannte Einschränkungen zu sehen
- Lineage für Interpretation und Rückfragen zu nutzen
- Unklarheiten, Lücken oder Fehler direkt zu melden

Ein Katalog mit vollständiger technischer Erfassung, aber geringer Nutzung, löst das eigentliche Problem nicht. Nutzbarkeit und Adoption sind daher Teil der Governance-Wirkung.

## Das Metadata Operating Model

Metadata Governance sollte als kontinuierlicher Prozess umgesetzt werden — nicht als einmaliges Katalogprojekt.

```text
Metadaten aus Systemen und Pipelines erfassen
        ↓
Assets im Katalog organisieren und klassifizieren
        ↓
Lineage über Quellen, Modelle und Reports abbilden
        ↓
Ownership, Qualität und fachlichen Kontext prüfen
        ↓
Nutzen, verbessern und kontinuierlich steuern
```

### 1. Metadaten erfassen

Technische Metadaten sollten möglichst automatisiert aus den relevanten Plattformen übernommen werden.

Typische Quellen sind:

- operative Anwendungen und Datenbanken
- Data Warehouses und Lakehouses
- ETL- und ELT-Plattformen
- dbt-Projekte
- Orchestrierungswerkzeuge
- BI- und Reporting-Plattformen
- APIs, Dateien und Messaging-Systeme
- Datenqualitäts- und Observability-Werkzeuge

Automatisierte Erfassung reduziert manuelle Arbeit und hält technische Strukturen aktuell. Sie ersetzt aber nicht die fachliche Anreicherung.

### 2. Organisieren und klassifizieren

Erfasste Assets benötigen eine verständliche Struktur.

Wichtige Ordnungsmerkmale sind:

- fachliche Domäne
- Datenprodukt oder Use Case
- Quelle und Zielplattform
- Asset-Typ
- Kritikalität
- Vertraulichkeit
- PII- oder Datenschutzklasse
- Lebenszyklusstatus
- Zertifizierungs- oder Vertrauensstatus

Klassifikationen sollten möglichst standardisiert und wiederverwendbar sein. Freitext allein führt schnell zu Dubletten und uneinheitlichen Bedeutungen.

### 3. Lineage abbilden

Lineage sollte die für Entscheidungen relevante Kette sichtbar machen.

Je nach Reifegrad kann sie verschiedene Ebenen umfassen:

| Ebene | Beispiel |
| --- | --- |
| **System Lineage** | CRM → Warehouse → BI |
| **Dataset Lineage** | Quelltabelle → RAW-Modell → Business-Modell → Report |
| **Column Lineage** | `customer_email` → Transformationsspalte → Analysemodell |
| **Metric Lineage** | Quellfelder → Berechnung → KPI → Dashboard |
| **Process Lineage** | Geschäftsprozess → Anwendung → Datenprodukt → Entscheidung |

Nicht jede Organisation benötigt sofort vollständige Column-Level-Lineage. Der Scope sollte sich an Risiko, Kritikalität und konkreten Use Cases orientieren.

### 4. Kontext und Verantwortung prüfen

Automatisiert importierte Assets sind häufig technisch vollständig, aber fachlich unzureichend beschrieben.

Owner und Stewards sollten deshalb regelmäßig prüfen:

- Ist die fachliche Definition verständlich und aktuell?
- Ist der richtige Owner zugeordnet?
- Sind PII, Kritikalität und Vertraulichkeit korrekt klassifiziert?
- Sind relevante Qualitätsregeln und SLAs verknüpft?
- Ist die Lineage vollständig genug für Analyse und Change Management?
- Gibt es Dubletten oder widersprüchliche Definitionen?
- Ist das Asset aktiv, veraltet oder abgekündigt?

### 5. Nutzen und kontinuierlich verbessern

Metadatenqualität entsteht nicht durch einen einmaligen Bereinigungszyklus. Sie muss Teil der täglichen Datenarbeit werden.

Dazu gehören:

- Metadaten in Entwicklungs- und Freigabeprozesse integrieren
- fehlende Pflichtinformationen automatisiert erkennen
- Änderungen an kritischen Definitionen überprüfen
- Nutzung und Suchverhalten auswerten
- veraltete Assets kennzeichnen oder ausblenden
- Feedback von Data Consumern einarbeiten
- Metadaten- und Lineage-Lücken priorisiert schließen

## Business Glossary und Data Catalog sind nicht dasselbe

Ein Business Glossary definiert fachliche Begriffe. Ein Data Catalog beschreibt konkrete Datenassets. Beide sollten miteinander verbunden sein.

| Business Glossary | Data Catalog |
| --- | --- |
| Definiert Begriffe wie „Aktiver Kunde“ oder „Nettoumsatz“ | Zeigt Tabellen, Spalten, Modelle, Reports und Datenprodukte |
| Schafft gemeinsame fachliche Sprache | Macht konkrete Daten auffindbar und verständlich |
| Besitzt fachliche Owner und Freigaben | Verknüpft technische und fachliche Metadaten |
| Kann unabhängig von einer Plattform gelten | Bezieht sich auf reale Assets in der Datenlandschaft |

Ein Glossarbegriff kann mit mehreren Datenassets verbunden sein. Umgekehrt kann ein Datenasset mehrere fachliche Begriffe unterstützen.

## Lineage für Impact Analysis

Eine der wertvollsten Anwendungen von Lineage ist die Bewertung geplanter Änderungen.

Vor einer Änderung an Quelle, Modell oder KPI sollten Teams beantworten können:

- Welche nachgelagerten Modelle sind betroffen?
- Welche Reports oder Datenprodukte verwenden die Daten?
- Welche kritischen KPIs hängen davon ab?
- Werden PII-, Zugriffs- oder Aufbewahrungsregeln beeinflusst?
- Welche Owner und Stewards müssen eingebunden werden?
- Welche Tests und Freigaben sind erforderlich?

Ein praktikabler Change-Prozess kann so aussehen:

```text
Geplante Änderung
        ↓
Lineage und Abhängigkeiten analysieren
        ↓
Kritische Assets, KPIs und Policies identifizieren
        ↓
Owner und betroffene Teams einbinden
        ↓
Tests, Migration und Kommunikation planen
        ↓
Änderung durchführen und Lineage aktualisieren
```

Lineage reduziert Risiken nicht automatisch. Sie liefert die Transparenz, um Risiken früh zu erkennen und kontrolliert zu behandeln.

## Metadata as Code

In modernen Data Stacks kann ein Teil der Metadaten direkt mit dem Code versioniert werden.

Bei dbt können zum Beispiel genutzt werden:

- Modell- und Spaltenbeschreibungen
- Tests
- `meta`-Attribute
- Tags
- Owner-Informationen
- Exposures
- Sources
- Verträge und Constraints

Beispiel:

```yaml
models:
  - name: customer_master
    description: Governed customer master used by analytics products.
    meta:
      domain: customer
      owner: customer-data-office
      criticality: high
      contains_pii: true
    columns:
      - name: customer_id
        description: Stable business identifier for a customer.
        tests:
          - not_null
          - unique
```

Der Vorteil liegt in Versionierung, Review und Nähe zur technischen Umsetzung. Nicht jede fachliche Information gehört jedoch zwingend in YAML. Ein Katalog kann weiterhin die zentrale Such- und Nutzungsoberfläche bilden.

## Zentral gepflegt oder föderiert?

Metadaten benötigen gemeinsame Standards, aber fachlicher Kontext entsteht in den Domänen.

Für viele Organisationen ist ein föderiertes Modell geeignet:

- ein zentrales Governance-Team definiert Mindestfelder, Taxonomien und Qualitätsregeln
- Plattformteams automatisieren technische Erfassung und Lineage
- Domänen pflegen Definitionen, Ownership und fachlichen Kontext
- Data Stewards prüfen Qualität und koordinieren Lücken
- Data Consumer geben Feedback zur Verständlichkeit und Nutzbarkeit

Zu starke Zentralisierung erzeugt Engpässe. Vollständige Dezentralisierung erzeugt uneinheitliche Begriffe und Klassifikationen. Das Ziel ist gemeinsame Struktur mit verteilter fachlicher Verantwortung.

## Ein praktikabler Minimum-Viable-Metadata-Eintrag

Nicht jedes Asset benötigt sofort eine vollständige Dokumentation. Für kritische Datenassets sollte aber mindestens Folgendes vorhanden sein:

| Feld | Zweck |
| --- | --- |
| **Name und Asset-Typ** | Identifiziert das Datenobjekt |
| **Fachliche Beschreibung** | Erklärt Bedeutung und Nutzung |
| **Domäne oder Datenprodukt** | Ordnet das Asset organisatorisch ein |
| **Data Owner** | Benennt die verantwortliche Entscheidungsrolle |
| **Data Steward** | Benennt den operativen Kontakt |
| **Quelle und wichtigste Ziele** | Schafft grundlegende Herkunftstransparenz |
| **Kritikalität** | Bestimmt Governance-Intensität |
| **PII- und Schutzklassifikation** | Verknüpft Datenschutz und Zugriff |
| **Qualitäts- oder Vertrauensstatus** | Unterstützt die Nutzungsentscheidung |
| **Letzter Review** | Zeigt Aktualität des fachlichen Kontexts |

Diese Grundlage kann später um vollständige Lineage, Policies, SLAs, Nutzung, Datenverträge und automatisierte Kontrollen erweitert werden.

## Metadatenqualität messen

Nicht die Anzahl importierter Tabellen entscheidet über den Erfolg, sondern die Nutzbarkeit und Verlässlichkeit des Kontexts.

Nützliche Kennzahlen sind zum Beispiel:

- Anteil kritischer Assets mit fachlicher Beschreibung
- Anteil kritischer Assets mit akzeptiertem Owner und Steward
- Anteil klassifizierter PII- und vertraulicher Daten
- Anteil kritischer Datenprodukte mit End-to-End-Lineage
- Vollständigkeit definierter Pflichtmetadaten
- Aktualität von Ownership- und Review-Informationen
- Anzahl veralteter oder verwaister Assets
- durchschnittliche Zeit zum Finden eines geeigneten Datenassets
- Suchanfragen ohne relevantes Ergebnis
- Katalognutzung durch aktive Consumer und Domänen
- durchschnittliche Zeit für Impact Analysis und Root-Cause-Analyse

Diese Kennzahlen sollten nicht nur Dokumentationsvollständigkeit messen, sondern zeigen, ob Metadaten reale Arbeitsabläufe verbessern.

## Ein einfaches Reifegradmodell

| Reifegrad | Typischer Zustand |
| --- | --- |
| **Fragmentiert** | Wissen liegt in Köpfen, Tickets, Wikis und einzelnen Dateien |
| **Erfasst** | Technische Metadaten werden zentral gesammelt |
| **Beschrieben** | Kritische Assets besitzen Definitionen, Ownership und Klassifikationen |
| **Verbunden** | Katalog, Glossar, Qualität und Lineage sind verknüpft |
| **Operativ** | Metadaten steuern Impact Analysis, Freigaben, Policies und Workflows |
| **Messbar** | Qualität, Nutzung, Aktualität und Governance-Wirkung werden überwacht |
| **Eingebettet** | Metadaten entstehen als Teil von Entwicklung, Betrieb und Datenprodukt-Lifecycle |

Das Ziel ist nicht maximale Dokumentation. Ziel ist verlässlicher Kontext für Daten, die hohen Wert, hohe Nutzung oder relevantes Risiko besitzen.

## Typische Anti-Patterns

Metadata-, Catalog- und Lineage-Initiativen scheitern häufig aus vorhersehbaren Gründen:

- der Katalog wird als reines Tool-Projekt behandelt
- Millionen technische Objekte werden importiert, aber nicht priorisiert
- fachliche Definitionen fehlen oder bestehen nur aus wiederholten Spaltennamen
- Owner werden angezeigt, ohne dass Verantwortlichkeiten akzeptiert wurden
- Metadaten werden zentral gepflegt, obwohl der fachliche Kontext in den Domänen liegt
- Lineage endet am Warehouse und ignoriert BI, KPIs oder nachgelagerte Nutzung
- automatische Lineage wird ungeprüft als vollständig betrachtet
- Katalog, Glossar und technische Dokumentation verwenden widersprüchliche Begriffe
- PII- und Governance-Tags haben keine Wirkung auf Kontrollen oder Workflows
- veraltete Assets bleiben sichtbar und werden weiterverwendet
- Erfolg wird an der Anzahl katalogisierter Objekte statt an Nutzung und Zeitersparnis gemessen
- Nutzer müssen für jede Frage ein separates Tool öffnen, ohne Kontext in ihren Arbeitsabläufen zu erhalten

Ein guter Katalog reduziert Unsicherheit. Ein schlechter Katalog verschiebt sie nur in eine neue Oberfläche.

## Verbindung zu den anderen Governance-Säulen

Metadata, Catalog & Lineage ist die verbindende Schicht zwischen vielen Governance-Disziplinen:

| Säule | Verbindung |
| --- | --- |
| **Data Ownership & Stewardship** | Owner und Stewards werden direkt mit Datenassets verknüpft |
| **PII & Privacy Governance** | Klassifikationen werden sichtbar und entlang der Lineage nachvollziehbar |
| **DSDR Governance** | Betroffene Systeme, Modelle und Datenprodukte können systemübergreifend identifiziert werden |
| **Data Quality Governance** | Regeln, Ergebnisse und Incidents werden mit Assets und Verantwortlichen verbunden |
| **KPI & Metric Governance** | Definitionen und Metric Lineage schaffen einheitliche Bedeutung |
| **Access & Security Governance** | Schutzklassen und Policies können aus Metadaten abgeleitet werden |
| **Data Lifecycle & Retention** | Aufbewahrungs- und Löschregeln werden mit Datenklassen und Assets verknüpft |

Metadaten sind damit nicht nur Dokumentation. Sie können zur Steuerungsschicht für operative Governance werden.

## Von Sichtbarkeit zu operativer Governance

Eine praktische Umsetzung kann diesem Pfad folgen:

```text
Systeme + Pipelines + Modelle + Reports
        ↓
Automatisierte technische Metadaten
        ↓
Fachliche Definitionen + Ownership + Klassifikationen
        ↓
Katalog + Suche + Lineage + Impact Analysis
        ↓
Qualität + Zugriff + Datenschutz + Retention + Workflows
        ↓
Vertrauenswürdige Nutzung + kontinuierliche Verbesserung
```

Der Katalog ist die sichtbare Oberfläche. Der eigentliche Wert entsteht durch die Verbindung von Kontext, Verantwortung, Datenflüssen und operativen Kontrollen.

## Das Ergebnis

Wirksames Metadata, Catalog & Lineage schafft:

- **Transparenz** — Daten, Bedeutung, Herkunft und Abhängigkeiten werden sichtbar
- **Vertrauen** — gemeinsame Definitionen, Ownership und Qualitätskontext reduzieren Unsicherheit
- **Impact Analysis** — Änderungen und Probleme können schneller und sicherer bewertet werden
- **Compliance** — Klassifikationen, Herkunft und Kontrollen werden nachvollziehbarer
- **Effizienz** — weniger Suchaufwand, weniger doppelte Arbeit und schnellere Problemlösung
- **Business Value** — nutzbare Daten werden schneller gefunden und besser verstanden

Metadaten liefern den Kontext. Der Katalog macht ihn zugänglich. Lineage verbindet die Datenkette und zeigt Auswirkungen.

Gemeinsam schaffen sie die Transparenz, die Governance benötigt, um nicht nur dokumentiert, sondern praktisch wirksam zu werden.
