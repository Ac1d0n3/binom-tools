---
title: SAP Data & Analytics Stack – Überblick
description: Ein praxisnaher Überblick über SAP-Quellsysteme, Integration, Datenplattformen, Analytics-Werkzeuge und typische Architekturmuster.
category: Datenplattformen
author: Thomas Lindackers
tags:
  - sap
  - sap-bw
  - sap-bw4hana
  - sap-datasphere
  - sap-business-data-cloud
  - sap-analytics-cloud
  - datenplattform
hero: images/playbooks/sap-overview-hero.png
---

## Den einen SAP-Datenstack gibt es nicht

Wenn von **dem SAP-Stack** gesprochen wird, können sehr unterschiedliche Architekturen gemeint sein. Ein Unternehmen betreibt möglicherweise weiterhin SAP ECC, SAP BW und SAP BusinessObjects. Ein anderes setzt auf SAP S/4HANA, SAP Datasphere und SAP Analytics Cloud. Ein drittes nutzt SAP als operativen Kern, überführt die Daten aber in Snowflake, Databricks, BigQuery oder eine andere Enterprise-Datenplattform.

Die entscheidende Frage lautet deshalb nicht:

> *Welches Produkt ist die SAP-Datenplattform?*

Die bessere Frage ist:

> *Welche SAP- und Nicht-SAP-Komponenten sind in dieser Landschaft für operative Daten, Integration, semantische Modellierung, Analytics und Governance verantwortlich?*

Dieses Playbook liefert eine kompakte Orientierung. Es ist keine Produktauswahl und beschreibt nicht jedes SAP-Produkt oder jede mögliche Betriebsform.

## Die Landschaft in vier Ebenen

Eine typische SAP-Daten- und Analytics-Landschaft lässt sich als vier verbundene Ebenen verstehen:

1. **Geschäfts- und Quellsysteme** erzeugen operative Daten.
2. **Integrations- und Replikationsdienste** bewegen oder veröffentlichen diese Daten.
3. **Datenplattform-Komponenten** integrieren, modellieren und bewahren die fachliche Bedeutung.
4. **Analytics- und Nutzungslösungen** stellen Daten für Menschen, Anwendungen und APIs bereit.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/sap-overview-img1-de.png"
        alt="SAP-Daten- und Analytics-Landschaft von den Geschäfts- und Quellsystemen über Integration und Datenplattformen bis zu Analytics und Nutzung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine vereinfachte SAP-Daten- und Analytics-Landschaft. Reale Enterprise-Architekturen kombinieren häufig mehrere SAP- und Nicht-SAP-Komponenten.
    </figcaption>
</figure>

## 1. Geschäfts- und Quellsysteme

In der operativen Ebene werden Geschäftsprozesse ausgeführt und große Teile des ursprünglichen fachlichen Kontexts erzeugt.

| Komponente | Typische Rolle |
| --- | --- |
| **SAP S/4HANA** | Aktuelle ERP-Kernplattform für Finance, Einkauf, Vertrieb, Produktion, Supply Chain und weitere Geschäftsprozesse |
| **SAP ECC** | Etablierte ERP-Plattform, die weiterhin in vielen über Jahre gewachsenen Unternehmenslandschaften vorhanden ist |
| **SAP BW** | Klassisches SAP Data Warehouse und Reporting-Fundament mit häufig langjährig gewachsenen Modellen und Geschäftslogik |
| **SAP SuccessFactors** | Cloud-Anwendungen für Personal-, Talent- und Workforce-Prozesse |
| **SAP Ariba / SAP Concur** | Beschaffung, Lieferantenmanagement, Reisen und Spesen |
| **Nicht-SAP-Quellen** | CRM-Systeme, Dateien, APIs, Datenbanken, SaaS-Anwendungen, Maschinen und externe Datenanbieter |

Das Quellsystem ist nicht automatisch der beste Ort für unternehmensweite Analytics. Operative Modelle sind für Geschäftstransaktionen optimiert. Analytische Modelle benötigen dagegen häufig Harmonisierung, Historisierung, einheitliche Definitionen und die Integration mehrerer Systeme.

## 2. Integration und Replikation

Die Integrationsschicht verbindet operative Systeme mit Datenplattformen und nachgelagerten Verbrauchern. Unterschiedliche Technologien lösen unterschiedliche Integrationsprobleme.

| Komponente | Typische Rolle |
| --- | --- |
| **SAP Integration Suite** | Integration Platform as a Service für Anwendungen, Prozesse, APIs, Events sowie Cloud-, On-Premises- und Hybrid-Szenarien |
| **SAP Landscape Transformation Replication Server (SLT)** | Echtzeit- oder Near-Realtime-Replikation aus unterstützten SAP-Quellsystemen in Zielplattformen |
| **SAP Data Services** | Datenintegration, Transformation und datenqualitätsorientierte Batch-Verarbeitung, häufig in etablierten Landschaften vorhanden |
| **APIs / ETL- / ELT-Werkzeuge** | Zusätzliche Integrationswege für SAP- und Nicht-SAP-Systeme – abhängig von Zielplattform und Betriebsmodell |

Eine Integrationsarchitektur sollte klar zwischen **Anwendungsintegration**, **Datenreplikation**, **Batch-Transformation**, **virtuellem Zugriff**, **Event Streaming** und **API-basierter Nutzung** unterscheiden. Die Bereiche hängen zusammen, sind aber nicht austauschbar.

## 3. Optionen für Datenplattformen

Im Zentrum der Landschaft steht nicht ein einzelnes Produkt. SAP bietet mehrere Datenplattform-Pfade für unterschiedliche Historien, Betriebsmodelle und Modernisierungsziele.

### SAP BW und SAP BW/4HANA

SAP BW ist in vielen großen Unternehmen weiterhin ein zentrales analytisches Fundament. Dort befinden sich häufig ausgereifte Extraktionslogik, historische Daten, wiederverwendbare fachliche Definitionen, Berechtigungen und Reporting-Modelle.

**SAP BW/4HANA** ist die für SAP HANA optimierte Data-Warehouse-Generation. Die Plattform bleibt relevant, wenn Unternehmen ein governiertes Enterprise Data Warehouse, eine tiefe SAP-Integration, vorhandene BW-Kompetenzen oder einen kontrollierten Modernisierungspfad benötigen.

Typische Stärken:

- ausgereifte Enterprise-Data-Warehouse-Konzepte
- enge Integration mit SAP-Quellsystemen
- wiederverwendbare analytische Modelle und Geschäftssemantik
- etablierte Lifecycle-, Transport- und Berechtigungsprozesse
- Unterstützung komplexer historischer und unternehmensweiter Reporting-Anforderungen

Typische Aspekte, die berücksichtigt werden sollten:

- spezialisiertes Know-how und Betriebswissen
- vorhandene kundenspezifische Modelle können die Modernisierung komplex machen
- ein Cloud-Ziel beseitigt nicht automatisch Legacy-Strukturen oder duplizierte Geschäftslogik

### SAP Datasphere

**SAP Datasphere** ist SAPs Cloud-Data-Warehouse- und Business-Data-Fabric-Angebot. Es soll Daten aus SAP- und Nicht-SAP-Quellen integrieren, dabei den fachlichen Kontext erhalten und gemeinsam nutzbare semantische Modelle ermöglichen.

Typische Stärken:

- cloudbasierte Datenintegration und Modellierung
- semantische Modellierung nahe an fachlichen Definitionen
- Spaces zur Organisation von Datenverantwortung und Zugriff
- Föderations- und Replikationsoptionen
- Integration mit SAP Analytics Cloud und hybriden SAP-Landschaften

Typische Aspekte, die berücksichtigt werden sollten:

- fachliche und semantische Ownership muss weiterhin organisatorisch definiert werden
- Konnektivität und Datenbewegung müssen bewusst gestaltet werden
- eine neue Plattform beseitigt nicht automatisch doppelte Modelle oder uneinheitliche KPI-Definitionen

### SAP Business Data Cloud

**SAP Business Data Cloud (BDC)** ist nicht einfach ein weiteres Warehouse neben SAP Datasphere. Es ist SAPs vollständig verwaltetes SaaS-Angebot für Daten und Analytics, das SAP-Daten vereinheitlichen und governieren, externe Daten anbinden sowie geschäftsfertige Datenprodukte und analytische Funktionen bereitstellen soll.

SAP ordnet Funktionen wie **SAP Datasphere**, **SAP Analytics Cloud**, die **Modernisierung von Business Warehouse** und **SAP Databricks** in die übergreifende Business-Data-Cloud-Ausrichtung ein.

Typische Stärken:

- verwaltetes Cloud-Betriebsmodell
- geschäftsfertige Datenprodukte mit erhaltenem SAP-Fachkontext
- Integration von SAP- und Drittanbieterdaten
- Analytics-, Data-Engineering- und KI-Szenarien innerhalb einer breiteren verwalteten Plattformausrichtung

Typische Aspekte, die berücksichtigt werden sollten:

- Architektur, Lizenzierung und Migrationspfade hängen von der vorhandenen SAP-Landschaft ab
- bestehende Investitionen in BW, Datasphere und Analytics müssen bewertet und nicht pauschal als ersetzbar betrachtet werden
- Zielbetriebsmodell, Data Ownership und die Strategie für externe Plattformen bleiben Unternehmensentscheidungen

> **Wichtig:** SAP BW/4HANA, SAP Datasphere und SAP Business Data Cloud sind nicht drei identische Alternativen. Sie besitzen unterschiedliche Produktumfänge und Modernisierungspfade und können parallel eingesetzt werden.

## 4. Analytics und Nutzung

In der analytischen Ebene werden governierte Daten für Entscheidungen, Planung, Anwendungen und Datenprodukte nutzbar.

| Komponente | Typische Rolle |
| --- | --- |
| **SAP Analytics Cloud (SAC)** | SAPs Cloud-Lösung für Business Intelligence, Enterprise Planning und analytische Anwendungen |
| **Power BI** | Häufige Enterprise-BI-Plattform in Microsoft-orientierten Unternehmen |
| **Qlik** | Assoziative Analysen, Dashboards, governiertes Self-Service und Embedded-Analytics-Szenarien |
| **Tableau** | Visuelle Analytics und Dashboarding in heterogenen Datenlandschaften |
| **Datenprodukte / APIs** | Wiederverwendbare, governierte Datenausgaben für Anwendungen, KI, Partner und weitere Plattformen |

Die Auswahl des Frontends sollte nicht die gesamte Datenarchitektur bestimmen. Mehrere BI-Werkzeuge können SAP-Daten nutzen. Der Ort von Geschäftslogik, semantischen Definitionen, Sicherheitsregeln und zertifizierten Kennzahlen muss jedoch eindeutig festgelegt werden.

## Vier typische SAP-Architekturmuster

Die meisten realen Landschaften ähneln einem von vier groben Mustern – oder einer Kombination daraus.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/sap-overview-img2-de.png"
        alt="Vergleich klassischer, moderner Cloud-, hybrider und offener erweiterter SAP-Architekturmuster"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Vier typische Architekturmuster. Sie dienen der Orientierung und sind keine verpflichtenden Blueprints.
    </figcaption>
</figure>

### 1. Klassischer SAP-Stack

```text
SAP ERP / ECC
      ↓
    SAP BW
      ↓
SAP BusinessObjects
```

Dieses Muster ist stark SAP-zentriert und kann stabil, governiert und tief integriert sein. Es ist weiterhin verbreitet, wenn umfangreiche BW-Investitionen, ausgereifte Reporting-Modelle und langjährig etablierte Geschäftsprozesse existieren.

Herausfordernd kann es werden, wenn eine breitere Cloud-Integration, offener Datenaustausch, modernes Data Engineering, KI-Workloads oder die schnellere Einbindung von Nicht-SAP-Quellen benötigt werden.

### 2. Moderner SAP-Cloud-Stack

```text
SAP S/4HANA
      ↓
SAP Datasphere
      ↓
SAP Analytics Cloud
```

Dieses Muster betont Cloud-Services, semantische Konsistenz und die enge Integration der operativen, datenbezogenen und analytischen SAP-Ebenen.

Es kann für Unternehmen attraktiv sein, die ein stärker verwaltetes und SAP-zentriertes Cloud-Modell anstreben. Trotzdem benötigt die Architektur klare Ownership, Lifecycle-Prozesse und Entscheidungen zum Umgang mit Nicht-SAP-Daten.

### 3. Hybrider SAP-Stack

```text
SAP S/4HANA
      ↓
SAP BW/4HANA
      ↓
SAP Datasphere
      ↓
SAP Analytics Cloud
```

Hybride Architekturen sind bei langen Modernisierungsprogrammen häufig. Bestehende BW-Investitionen bleiben nutzbar, während Cloud-Funktionen schrittweise ergänzt werden.

Das Hauptrisiko ist unkontrollierte Duplizierung: Dieselbe Transformation, derselbe KPI oder dieselbe semantische Definition kann gleichzeitig in BW, Datasphere und der BI-Schicht entstehen. Klare Designregeln sind deshalb entscheidend.

### 4. Offener oder erweiterter SAP-Stack

```text
SAP-Quellen
      ↓
Integration / Replikation
      ↓
Snowflake / Databricks / BigQuery
      ↓
Power BI / Qlik / Tableau
```

In diesem Muster bleibt SAP eine wichtige Quelle für operative und fachliche Daten. Die Enterprise-Datenplattform ist jedoch nicht ausschließlich SAP-basiert.

Das kann offenes Data Engineering, domänenübergreifende Analytics und breitere Cloud-Strategien unterstützen. Gleichzeitig wird es wichtiger, den SAP-Fachkontext zu erhalten, Extraktion und Replikation kontrolliert zu gestalten und eindeutig festzulegen, wo Semantik und Governance verankert sind.

## Wie wird ein Zielmuster ausgewählt?

Den universell besten Stack gibt es nicht. Die passende Architektur hängt von der bestehenden Landschaft und dem angestrebten Betriebsmodell ab.

| Auswahlkriterium | Leitfragen |
| --- | --- |
| **Vorhandene SAP-Landschaft** | Wie viel Geschäftslogik, Historie, Berechtigungslogik und Reporting befindet sich bereits in BW, ECC oder S/4HANA? |
| **Cloud-Strategie** | Ist das Ziel SAP-zentriertes SaaS, eine Hyperscaler-Plattform, ein hybrides Modell oder eine Multi-Plattform-Architektur? |
| **Integrationsbedarf** | Geht es hauptsächlich um Replikation, APIs, Anwendungsintegration, Events, Föderation oder Batch-Verarbeitung? |
| **Analytics-Ziel** | Werden SAP Analytics Cloud, Power BI, Qlik, Tableau, Datenprodukte, KI-Workloads oder mehrere Verbraucher benötigt? |
| **Kompetenzen und Betriebsmodell** | Welche Teams können die ausgewählten Komponenten über mehrere Jahre entwickeln, betreiben und governieren? |
| **Data Gravity und Kosten** | Wo werden die Daten gespeichert, wie häufig werden sie bewegt und welche Betriebs- oder Egress-Kosten entstehen? |
| **Governance-Modell** | Wo werden Ownership, Klassifizierungen, Lineage, Datenqualitätsregeln, Zugriffsrichtlinien und zertifizierte Kennzahlen verwaltet? |

## Governance-Fragen, die früh beantwortet werden sollten

Auch eine Plattformübersicht sollte Governance nicht ausblenden. Vor einer Produktauswahl sollte geklärt werden:

- Wer verantwortet die fachliche Bedeutung von SAP-Daten, nachdem sie das Quellsystem verlassen haben?
- Welche Plattform ist führend für gemeinsam genutzte Dimensionen, KPIs und semantische Definitionen?
- Wie werden PII-Klassifizierungen und Zugriffsregeln über SAP- und Nicht-SAP-Plattformen hinweg weitergegeben?
- Ist Lineage über Extraktion, Replikation, Transformation und BI-Nutzung hinweg sichtbar?
- Welche Datenprodukte sind zertifiziert und wer genehmigt Änderungen?
- Wie werden doppelte Transformationen erkannt und zurückgebaut?
- Welche Plattform ist der Single Point of Truth für Metadaten und Governance-Entscheidungen?

Eine technisch verbundene Landschaft ist nicht automatisch eine governierte Landschaft.

## Häufige Missverständnisse

### „SAP Datasphere ersetzt sofort jedes BW-System“

Nicht zwangsläufig. Bestehende BW-Landschaften können umfangreiche Geschäftslogik, Historie und operative Prozesse enthalten. Modernisierung ist in der Regel eine Portfolio- und Migrationsentscheidung und kein einfacher Produkttausch.

### „SAP Business Data Cloud ist nur ein umbenanntes Data Warehouse“

Nein. Die Business Data Cloud steht für ein breiteres verwaltetes Daten- und Analytics-Angebot mit Datenprodukten, Governance, Analytics, Data Engineering sowie der Integration von SAP- und Drittanbieterdaten.

### „Bei einem Nicht-SAP-Warehouse geht die SAP-Semantik zwangsläufig verloren“

Das kann passieren, ist aber nicht unvermeidbar. Die Architektur muss Geschäftsbegriffe, Definitionen, Hierarchien, Einheiten, Berechtigungslogik und Lineage bei Replikation und Neumodellierung bewusst erhalten.

### „Ein einziges BI-Werkzeug macht eine semantische Schicht überflüssig“

Ein BI-Werkzeug kann Berechnungen und Modelle enthalten. Unternehmensweite Konsistenz erfordert jedoch eine bewusste Entscheidung, wo gemeinsame Semantik und zertifizierte Kennzahlen verwaltet werden.

## Praktischer Lernpfad für den Einstieg

Für eine erste Orientierung bietet sich diese Reihenfolge an:

1. **Mit der übergreifenden Ausrichtung beginnen:** [Introducing SAP Business Data Cloud](https://learning.sap.com/courses/introducing-sap-business-data-cloud)
2. **Cloud-Datenmodellierung und Semantik verstehen:** [Exploring SAP Datasphere](https://learning.sap.com/courses/exploring-sap-datasphere)
3. **Den etablierten Enterprise-Data-Warehouse-Pfad verstehen:** [Exploring SAP BW/4HANA](https://learning.sap.com/learning-journeys/exploring-sap-bw-4hana)
4. **Das SAP-Analytics-Frontend kennenlernen:** [Exploring SAP Analytics Cloud](https://learning.sap.com/courses/exploring-sap-analytics-cloud)
5. **Hybrid- und Enterprise-Integration verstehen:** [Lernressourcen zur SAP Integration Suite](https://learning.sap.com/products/business-technology-platform/integration-suite)

Weitere nützliche Ressourcen:

- [SAP Business Data Cloud – Produktübersicht](https://www.sap.com/germany/products/data-cloud.html)
- [SAP Datasphere – Lernressourcen](https://learning.sap.com/products/business-technology-platform/data-analytics/datasphere)
- [SAP BW/4HANA – Lernressourcen](https://learning.sap.com/products/business-technology-platform/data-analytics/bw4-hana)
- [SAP Analytics Cloud – Lernressourcen](https://learning.sap.com/products/business-technology-platform/data-analytics/analytics-cloud)
- [Analytics with SAP Solutions](https://learning.sap.com/courses/analytics-with-sap-solutions)

## Wichtigste Erkenntnisse

- Den **einen SAP-Datenstack gibt es nicht**.
- SAP-Architekturen kombinieren häufig operative Systeme, Integrationsdienste, Datenplattformen und mehrere Analytics-Verbraucher.
- **SAP BW/4HANA**, **SAP Datasphere** und **SAP Business Data Cloud** besitzen unterschiedliche Umfänge und können parallel eingesetzt werden.
- Klassische, Cloud-, Hybrid- und offene Architekturen sind unter den passenden Rahmenbedingungen valide Muster.
- Die Zielplattform sollte anhand der bestehenden SAP-Landschaft, Cloud-Strategie, Integrationsanforderungen, Kompetenzen, Kosten und des Governance-Modells ausgewählt werden.
- Modernisierung ist dann erfolgreich, wenn Geschäftssemantik, Historie, Sicherheit und Ownership erhalten bleiben – nicht nur, wenn Daten auf eine neuere Plattform verschoben werden.
