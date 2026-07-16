---
title: Cloud-Hosting-Modelle für Datenplattformen
description: Ein praxisnaher Überblick über On-Premises, IaaS, Managed Platforms, PaaS, SaaS, Serverless und hybride Enterprise-Datenarchitekturen – einschließlich der Einordnung der Big-5-Plattformen und des SAP-Stacks.
author: Thomas Lindackers
category: Datenplattformen
tags:
  - cloud
  - hosting-modelle
  - on-premises
  - iaas
  - paas
  - saas
  - serverless
  - hybrid-cloud
  - datenplattform
  - sap
  - big-five
hero: images/playbooks/cloud-hosting-hero.png
---

## Cloud ist kein einzelnes Betriebsmodell

Wenn Unternehmen von einer **Cloud-Datenplattform** sprechen, meinen sie häufig sehr unterschiedliche Dinge. Bei einer Lösung betreibt der Cloud-Anbieter nur Rechenzentrum, Netzwerk und Virtualisierung. Bei einer anderen übernimmt der Plattformanbieter zusätzlich Betriebssystem, Runtime, Patches, Skalierung und große Teile des Plattformbetriebs. Bei einem SaaS-Angebot ist sogar die Anwendung selbst vollständig verwaltet.

Diese Unterschiede sind für Datenplattformen besonders wichtig. Sie bestimmen unter anderem:

- welche technischen Schichten intern betrieben werden müssen
- wo Konfiguration und individuelle Architekturentscheidungen möglich sind
- wie Identitäten, Netzwerke und Zugriffe eingebunden werden
- wer Plattformverfügbarkeit, Patches und Upgrades verantwortet
- wo Datenmodelle, Pipelines, Metadaten und Governance verankert werden
- wie On-Premises-, SAP- und Cloud-Systeme gemeinsam betrieben werden

Die zentrale Frage lautet daher nicht nur:

> *Läuft die Plattform in der Cloud?*

Sondern:

> *Welche Schichten betreibt der Anbieter, welche Schichten verantwortet das Unternehmen – und wo bleiben Daten-, Sicherheits- und Governance-Verantwortung verankert?*

Dieses Playbook führt die **Big-5-Datenplattformen** und den **SAP Data & Analytics Stack** aus den vorherigen Übersichten in ein gemeinsames Hosting- und Betriebsmodell zusammen.

## Vier Hosting-Modelle und ihre Verantwortungsverteilung

Die Grenzen zwischen On-Premises, IaaS, PaaS, Managed Platform, SaaS und Serverless werden in Produktmarketing häufig unscharf verwendet. Für die Architektur ist jedoch entscheidend, welche Schichten tatsächlich vom Anbieter betrieben werden.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/cloud-hosting-img1-de.png"
        alt="Vergleich der Verantwortungsverteilung bei On-Premises, IaaS, Managed Platform beziehungsweise PaaS sowie SaaS und Serverless"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Vereinfachte Verantwortungsverteilung über vier typische Hosting-Modelle. Die exakte Abgrenzung hängt immer vom konkreten Produkt, Tarif und Bereitstellungsmodell ab.
    </figcaption>
</figure>

Die Darstellung trennt bewusst zwischen **technischem Plattformbetrieb** und der **Verantwortung für Daten und Nutzung**. Je stärker eine Lösung verwaltet wird, desto mehr technische Betriebsaufgaben wandern zum Anbieter. Datenverantwortung, Zugriffskonzepte, fachliche Qualität und Governance bleiben jedoch beim Unternehmen.

## 1. On-Premises

Bei einem klassischen On-Premises-Modell betreibt das Unternehmen die gesamte technische Kette selbst oder lässt sie durch einen beauftragten Infrastrukturpartner betreiben.

Typische Verantwortungsbereiche:

- Rechenzentrum oder eigene Infrastruktur
- Netzwerk, Firewalls und Segmentierung
- Betriebssysteme und Basissoftware
- Runtime, Datenbank- oder Compute-Engine
- Installation und Betrieb der Datenplattform
- Upgrades, Patches und technische Wartung
- Backup, Recovery, Monitoring und Kapazitätsplanung
- Datenmodelle, Pipelines, Sicherheit und Governance

Typische Beispiele:

- SAP BW oder SAP BW/4HANA im eigenen Rechenzentrum
- SAP S/4HANA On-Premises
- selbst betriebene Hadoop- oder Spark-Plattformen
- selbst installierte Datenbanken und Data Warehouses

On-Premises bedeutet maximale technische Kontrolle, aber auch maximale Betriebsverantwortung. Das Unternehmen kann Netzwerkzonen, Betriebssysteme, Wartungsfenster und Plattformkonfigurationen sehr detailliert steuern. Gleichzeitig muss es die dafür erforderlichen Kompetenzen, Prozesse und Betriebsstrukturen dauerhaft bereitstellen.

> **Wichtig:** On-Premises ist nicht automatisch ungeeignet oder veraltet. Für bestimmte regulatorische, technische oder stark integrierte Workloads kann es weiterhin ein bewusstes Zielmodell sein.

## 2. Infrastructure as a Service – IaaS

Bei IaaS stellt der Cloud-Anbieter die physische Infrastruktur, Rechenleistung, Speicher, Netzwerkgrundlagen und Virtualisierung bereit. Das Unternehmen betreibt darauf seine eigenen Betriebssysteme, Plattformkomponenten und Anwendungen.

Typische Anbieterleistungen:

- Rechenzentren
- physische Server und Speicher
- grundlegende Cloud-Netzwerkinfrastruktur
- Virtualisierung und Ressourcenbereitstellung

Typische Unternehmensverantwortung:

- Betriebssysteme und deren Härtung
- Plattforminstallation und Runtime
- Datenbanken, Cluster und Middleware
- Patches oberhalb der Infrastruktur
- Netzwerkkonfiguration innerhalb der eigenen Cloud-Umgebung
- Identitäten, Rollen und Zugriffsregeln
- Datenmodelle, Pipelines und Governance

Ein selbst aufgebautes Spark-, Hadoop- oder Datenbank-Cluster auf virtuellen Maschinen ist ein typisches IaaS-Muster. Die Hardware muss nicht mehr selbst betrieben werden, die eigentliche Datenplattform bleibt jedoch weitgehend interne Verantwortung.

IaaS ist besonders dann relevant, wenn bestehende Plattformsoftware in die Cloud verlagert werden soll, ohne das gesamte technische Betriebsmodell sofort zu verändern.

## 3. Managed Platform und Platform as a Service – PaaS

Bei einer Managed Platform oder PaaS übernimmt der Anbieter größere Teile des Plattformbetriebs. Dazu können Betriebssystem, Runtime, Skalierung, Patches, Hochverfügbarkeit und bestimmte Plattformdienste gehören.

Das Unternehmen konzentriert sich stärker auf:

- Plattformkonfiguration
- Datenaufnahme und Integration
- Datenmodelle und Transformationen
- fachliche Datenprodukte
- Identitäten, Berechtigungen und Richtlinien
- Datenqualität, Metadaten und Governance

Typische Beispiele aus der Datenwelt:

- Databricks auf AWS, Azure oder Google Cloud
- Amazon Redshift
- Azure Synapse Analytics
- weitere verwaltete Datenbank-, Warehouse- oder Lakehouse-Dienste

Diese Kategorie ist breit. Zwei verwaltete Plattformen können sehr unterschiedliche Betriebsgrenzen besitzen. Bei Databricks hängt die Verantwortungsverteilung beispielsweise davon ab, ob klassische Compute-Ressourcen in der Cloud-Umgebung des Kunden oder serverlose Compute-Varianten eingesetzt werden. Auch Azure Synapse und Amazon Redshift bieten innerhalb derselben Produktfamilie unterschiedliche Compute- und Bereitstellungsmodelle.

> **Architekturregel:** Die Produktbezeichnung allein reicht nicht. Entscheidend ist die konkrete Kombination aus Control Plane, Compute Plane, Storage, Netzwerk, Identität und Betriebsmodell.

## 4. SaaS und Serverless

Bei SaaS stellt der Anbieter eine vollständig verwaltete Plattform oder Anwendung bereit. Das Unternehmen konfiguriert und nutzt den Dienst, betreibt aber normalerweise keine Server, Betriebssysteme oder Plattform-Runtimes.

Bei Serverless werden zusätzlich viele Entscheidungen über Compute-Bereitstellung, Skalierung und Ressourcenmanagement abstrahiert. „Serverless“ bedeutet nicht, dass keine Server existieren. Es bedeutet, dass der Kunde sie nicht direkt bereitstellen und betreiben muss.

Typische Beispiele:

- Snowflake als vollständig verwalteter Cloud-Datendienst
- Google BigQuery als serverlose Analytics- und Warehouse-Plattform
- Microsoft Fabric als integrierte SaaS-Analytics-Plattform
- SAP Datasphere als vollständig verwaltete Cloud-Datenumgebung
- SAP Business Data Cloud als umfassendes verwaltetes SaaS-Angebot
- SAP Analytics Cloud als SaaS-Lösung für Analytics und Planung

Auch bei SaaS bleiben zentrale Aufgaben beim Unternehmen:

- Datenklassifikation und Schutzbedarf
- Identitäts- und Berechtigungskonzepte
- fachliche Datenmodelle und Kennzahlen
- Pipeline- und Datenproduktlogik
- Data Ownership und Stewardship
- Datenqualität und Freigabeprozesse
- Aufbewahrung, Löschung und regulatorische Anforderungen
- Überwachung der Nutzung und Governance-Kontrollen

Die Plattform wird verwaltet. Die Verantwortung für die **richtige Nutzung der Plattform und die Bedeutung der Daten** wird nicht ausgelagert.

## Shared Responsibility: Verantwortung verschiebt sich, sie verschwindet nicht

Cloud-Anbieter beschreiben dieses Prinzip als **Shared Responsibility Model**. Die genaue Aufteilung unterscheidet sich je nach Anbieter und Dienst, folgt aber einem gemeinsamen Grundmuster:

- Der Anbieter schützt und betreibt die von ihm kontrollierte Infrastruktur und die verwalteten Plattformkomponenten.
- Das Unternehmen verantwortet seine Daten, Identitäten, Zugriffe, Konfigurationen, Workloads und die fachlich korrekte Nutzung.
- Je höher der Abstraktionsgrad, desto mehr technische Betriebsaufgaben übernimmt der Anbieter.
- Governance, Datenschutz, Ownership und fachliche Qualität bleiben dennoch Unternehmensverantwortung.

Für Datenplattformen ist die letzte Aussage entscheidend. Ein automatisch gepatchtes Warehouse kennt nicht automatisch den fachlich richtigen Owner. Eine serverlose Query Engine entscheidet nicht, welche KPI-Definition zertifiziert ist. Ein SaaS-Katalog löst nicht automatisch widersprüchliche Datenklassifikationen.

## Wo die großen Datenplattformen typischerweise einzuordnen sind

Die Big-5-Plattformen und SAP-Produkte liegen nicht alle auf derselben Hosting-Ebene. Einige sind klar SaaS- oder Serverless-orientiert. Andere kombinieren verwaltete Control Planes mit Compute- und Storage-Komponenten in der Cloud-Umgebung des Kunden. Wieder andere existieren in mehreren Bereitstellungsmodellen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/cloud-hosting-img2-de.png"
        alt="Einordnung wichtiger Datenplattformen in On-Premises und Private Cloud, Managed Cloud Platform, SaaS und Managed Service sowie Serverless und SaaS"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Typische Hosting-Positionen wichtiger Datenplattformen. Die Darstellung ist eine Orientierung und keine starre Produkttaxonomie, da mehrere Plattformen unterschiedliche Betriebsvarianten abdecken.
    </figcaption>
</figure>

### On-Premises und Private Cloud

#### SAP BW/4HANA

SAP BW/4HANA ist ein HANA-optimiertes Enterprise Data Warehouse und kann in On-Premises- und hybriden Szenarien eingesetzt werden. In vielen Unternehmen bleibt es ein zentrales System für historische Daten, harmonisierte Geschäftslogik, Berechtigungen und Reporting.

Der technische Betrieb kann im eigenen Rechenzentrum, in einer Private-Cloud-Umgebung oder über einen Betriebsdienstleister erfolgen. Die konkrete Verantwortungsverteilung hängt vom jeweiligen Betriebsvertrag ab.

#### SAP S/4HANA

SAP S/4HANA ist keine reine Datenplattform, sondern die operative ERP-Kernplattform. Sie ist in verschiedenen Bereitstellungsmodellen verfügbar. Im Schaubild steht sie stellvertretend für umfangreiche operative SAP-Systeme, die häufig im eigenen oder privaten Betriebsmodell verbleiben und Daten an analytische Plattformen liefern.

#### Self-managed Hadoop oder Spark

Selbst betriebene Hadoop- oder Spark-Plattformen zeigen, dass Cloud-Infrastruktur allein noch keine Managed Platform erzeugt. Werden Cluster, Runtime, Patches, Skalierung und Betriebsprozesse selbst verantwortet, bleibt die Plattform aus Betriebssicht weitgehend kundengesteuert – auch wenn die Server in einer Public Cloud laufen.

### Managed Cloud Platforms

#### Databricks

Databricks ist eine verwaltete Lakehouse- und Data-Intelligence-Plattform auf AWS, Azure und Google Cloud. Die Architektur unterscheidet zwischen von Databricks verwalteten Plattformdiensten und den Bereichen, in denen Daten und Compute verarbeitet werden.

Je nach Cloud und Compute-Modell können klassische Ressourcen in der Cloud-Umgebung des Kunden oder serverlose Compute-Ressourcen in einer von Databricks verwalteten Ebene betrieben werden. Databricks passt deshalb nicht dauerhaft in nur eine einzige Hosting-Kategorie.

#### Amazon Redshift

Amazon Redshift ist ein verwaltetes Data Warehouse auf AWS. AWS übernimmt wesentliche Aufgaben wie Bereitstellung, Betrieb, Skalierung, Backups sowie Patches der Warehouse-Engine. Zusätzlich existiert mit Redshift Serverless eine stärker abstrahierte Variante, bei der Warehouse-Kapazität automatisch bereitgestellt und skaliert wird.

#### Azure Synapse Analytics

Azure Synapse Analytics ist eine verwaltete Analytics-Plattform, die Data Warehousing, SQL, Spark, Pipelines und weitere Azure-Dienste verbindet. Sie bietet sowohl dedizierte als auch serverlose Ressourcenmodelle. Die konkrete Einordnung hängt deshalb vom verwendeten Synapse-Dienst ab.

### SaaS und Managed Services

#### Snowflake

Snowflake wird vollständig auf Public-Cloud-Infrastruktur betrieben und als verwalteter Dienst bereitgestellt. Infrastruktur, Plattformsoftware, Wartung und Upgrades werden durch Snowflake verwaltet. Das Unternehmen steuert unter anderem Daten, Rollen, Sicherheitsrichtlinien, Warehouses, Modelle und Workloads.

Snowflake kann auf verschiedenen Hyperscalern und in unterschiedlichen Regionen bereitgestellt werden, ist aber keine lokal installierbare On-Premises-Plattform.

#### SAP Datasphere

SAP Datasphere ist eine vollständig verwaltete Cloud-Datenumgebung und ein zentraler Bestandteil der SAP-Business-Data-Cloud-Ausrichtung. Die Plattform verbindet SAP- und Nicht-SAP-Daten, unterstützt Integration, semantische Modellierung und Datenprodukte und soll den fachlichen Kontext über hybride und Multi-Cloud-Landschaften hinweg erhalten.

#### SAP Business Data Cloud

SAP Business Data Cloud ist ein umfassendes verwaltetes SaaS-Angebot für die Vereinheitlichung und Governance von SAP- und Drittanbieterdaten. Es verbindet Business Data Fabric, Datenprodukte, Analytics, BW-Modernisierung sowie Data-Engineering- und KI-Funktionen in einer übergreifenden Plattformausrichtung.

### Serverless und SaaS

#### Google BigQuery

BigQuery ist eine serverlose Analytics- und Data-Warehouse-Plattform. Infrastruktur, Ressourcenbereitstellung und große Teile der Skalierung werden durch Google abstrahiert. Teams können sich dadurch stärker auf Daten, SQL, Modelle, Workloads und Governance konzentrieren.

#### Microsoft Fabric

Microsoft Fabric ist eine integrierte SaaS-Analytics-Plattform mit gemeinsamen Diensten für Datenaufnahme, Engineering, Data Science, Echtzeitverarbeitung, Data Warehouse und Power BI. OneLake dient als gemeinsame logische Datenbasis über die Fabric-Workloads hinweg.

#### SAP Analytics Cloud

SAP Analytics Cloud ist eine SaaS-Lösung für Business Intelligence, Analytics und Enterprise Planning. Sie ist in dieser Übersicht bewusst der Nutzungs- und Analytics-Ebene zugeordnet und nicht als Enterprise Data Warehouse eingeordnet.

## Warum die Einordnung nicht absolut ist

Die vier Kategorien sind nützlich, aber keine harten Produktgrenzen.

| Plattform | Warum die Einordnung variieren kann |
| --- | --- |
| **Databricks** | Klassische und serverlose Compute-Modelle besitzen unterschiedliche Betriebsgrenzen |
| **Amazon Redshift** | Provisionierte Cluster und Redshift Serverless abstrahieren unterschiedliche Betriebsaufgaben |
| **Azure Synapse** | Dedizierte SQL-Pools, serverlose SQL-Pools, Spark und Pipelines besitzen unterschiedliche Ressourcenmodelle |
| **SAP S/4HANA** | On-Premises, Private-Cloud- und Cloud-Varianten verändern die Betriebsverantwortung |
| **SAP BW/4HANA** | Eigenbetrieb, Private Cloud und Managed Operations sind möglich |
| **Snowflake** | Vollständig verwalteter Dienst, aber mit kundenseitiger Verantwortung für Daten, Rollen, Policies und Workloads |
| **Microsoft Fabric** | SaaS-Plattform, innerhalb derer unterschiedliche Workloads und Kapazitätsmodelle genutzt werden |

Ein Architekturdiagramm sollte deshalb nicht nur den Produktnamen zeigen. Es sollte zusätzlich dokumentieren:

- Cloud und Region
- Tenant-, Account- und Subscription-Grenzen
- Control Plane und Compute Plane
- Speicherort und Datenresidenz
- Netzwerkpfade und Private Connectivity
- Identitätsprovider und Rollenmodell
- Verantwortlichkeit für Patches, Verfügbarkeit und Recovery
- Ownership von Datenmodellen, Pipelines und Governance

## Drei typische Enterprise-Hosting-Architekturen

In der Praxis entstehen meist keine reinen Produktlandschaften. Unternehmen kombinieren operative Systeme, Datenplattformen, Integrationsdienste, semantische Schichten und BI-Werkzeuge über mehrere Betriebsmodelle hinweg.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/cloud-hosting-img3-de.png"
        alt="Vergleich einer On-Premises- beziehungsweise Private-Cloud-Architektur, einer Cloud-Native-Architektur und einer hybriden Enterprise-Architektur"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Drei typische Zielarchitekturen. Viele reale Landschaften sind dauerhaft hybrid und kombinieren mehrere Plattform- und Hosting-Modelle.
    </figcaption>
</figure>

### 1. On-Premises oder Private Cloud

```flow linear vertical
Enterprise-Anwendungen
SAP S/4HANA / ERP / CRM
SAP BW/4HANA
Data Warehouse
Data Marts / Semantische Schicht
SAP BusinessObjects / Qlik / weitere BI-Werkzeuge
```

Dieses Muster ist häufig in langjährig gewachsenen SAP- und Enterprise-Landschaften zu finden. Es kann eine hohe Kontrolle über Netzwerk, Wartungsfenster, technische Änderungen und Datenhaltung ermöglichen.

Typische Stärken:

- enge Integration mit vorhandenen Enterprise-Systemen
- etablierte Betriebs-, Transport- und Berechtigungsprozesse
- kontrollierte technische Änderungszyklen
- Nutzung vorhandener Plattformkompetenz und Geschäftslogik

Typische Herausforderungen:

- hoher eigener Betriebsanteil
- spezialisierte Plattformkenntnisse
- längere technische Lifecycle- und Upgrade-Projekte
- neue Cloud- und SaaS-Quellen müssen gezielt integriert werden

Sicherheit und Governance werden überwiegend über interne Richtlinien, Netzwerkzonen, Plattformberechtigungen und etablierte Kontrollprozesse umgesetzt.

### 2. Cloud-Native

```flow linear vertical
Cloud-Quellen
Ingestion Layer
Snowflake / Databricks / BigQuery / Redshift / Fabric
dbt oder plattformeigene Transformation
Power BI / Tableau / Qlik / weitere Analytics-Werkzeuge
```

Dieses Muster nutzt verwaltete Cloud-Dienste als primäre Daten- und Analytics-Plattform. Die Big 5 stehen hier nicht für eine gemeinsame technische Architektur, sondern für fünf verbreitete Plattformökosysteme mit unterschiedlichen Betriebsmodellen.

Typische Stärken:

- schnelle Bereitstellung verwalteter Plattformfunktionen
- elastische oder serverlose Ressourcenmodelle
- breite Integration mit Cloud-Diensten und SaaS-Quellen
- moderne Data-Engineering-, Analytics- und KI-Workloads
- Infrastructure-as-Code und automatisierte Plattformbereitstellung

Typische Herausforderungen:

- Identitäts-, Netzwerk- und Governance-Design über mehrere Cloud-Dienste
- klare Trennung zwischen Plattformkonfiguration und Datenproduktverantwortung
- Vermeidung unkontrollierter Workspaces, Projekte und Schattenplattformen
- konsistente Metadaten, Lineage und Zugriffsrichtlinien über mehrere Werkzeuge

Cloud-native bedeutet nicht automatisch, dass alle Komponenten vom selben Anbieter stammen. Ingestion, Datenplattform, Transformation, Governance und BI können weiterhin unterschiedliche Produkte sein.

### 3. Hybrides Enterprise-Modell

```text
SAP S/4HANA / Legacy-Systeme     Cloud- und SaaS-Quellen
              ↓                            ↓
          Integration / Replikation / CDC / APIs
                         ↓
              SAP Datasphere
                    +
      Snowflake / Databricks / BigQuery / weitere Plattform
                         ↓
          Semantische Schicht / Datenprodukte
                         ↓
     SAP Analytics Cloud / Power BI / Qlik / Anwendungen
```

Dieses Muster verbindet vorhandene SAP- und On-Premises-Systeme mit modernen Cloud-Datenplattformen. Es ist nicht nur ein Übergangszustand. Für viele große Unternehmen ist Hybrid ein langfristiges Zielmodell, weil operative Kernsysteme, regulatorische Anforderungen, spezialisierte Plattformen und Cloud-Innovation gleichzeitig berücksichtigt werden müssen.

Typische Stärken:

- vorhandene SAP- und Legacy-Investitionen bleiben nutzbar
- schrittweise Modernisierung statt Big-Bang-Migration
- Auswahl der passenden Plattform für unterschiedliche Workloads
- Kombination von SAP-Semantik mit offenen Cloud- und Data-Engineering-Werkzeugen

Typische Herausforderungen:

- doppelte Datenhaltung und unklare führende Systeme
- komplexe Replikations- und Synchronisationspfade
- verteilte Identitäts-, Netzwerk- und Sicherheitsmodelle
- Semantik und KPIs können in mehreren Plattformen dupliziert werden
- End-to-End-Lineage und einheitliche Governance werden anspruchsvoller

Der wichtigste Architekturbaustein ist deshalb die im Schaubild gezeigte **einheitliche Sicherheits- und Governance-Schicht**. Richtlinien müssen nicht zwingend in einem einzigen Produkt umgesetzt werden. Sie müssen aber über die beteiligten Umgebungen konsistent definiert, technisch durchgesetzt und nachvollziehbar überwacht werden.

## Hybrid ist nicht automatisch ein Migrationsfehler

Viele Architekturmodelle stellen Hybrid nur als Zwischenphase zwischen On-Premises und Cloud dar. Das greift für Enterprise-Datenlandschaften zu kurz.

Hybrid kann dauerhaft sinnvoll sein, wenn:

- operative SAP-Systeme langfristig im eigenen oder privaten Betriebsmodell verbleiben
- bestimmte Daten aus regulatorischen oder technischen Gründen lokal verarbeitet werden
- einzelne Workloads spezialisierte Cloud-Plattformen benötigen
- mehrere Regionen oder Cloud-Anbieter Teil der Unternehmensstrategie sind
- bestehende BW- oder Data-Warehouse-Modelle kontrolliert weitergenutzt werden
- Datenprodukte über Plattformgrenzen hinweg bereitgestellt werden

Die entscheidende Frage ist nicht, ob eine Landschaft hybrid ist. Entscheidend ist, ob die Übergänge zwischen den Umgebungen **bewusst gestaltet und governiert** sind.

## Auswahlkriterien für ein Hosting-Modell

Ein Hosting-Modell sollte nicht nur anhand eines Produktnamens ausgewählt werden. Für jede Plattform und jeden Workload sollten die folgenden Fragen beantwortet werden.

| Kriterium | Leitfragen |
| --- | --- |
| **Kontrolle und Anpassbarkeit** | Welche technischen Schichten müssen selbst konfigurierbar sein? Werden spezielle Runtime-, Netzwerk- oder Erweiterungsanforderungen benötigt? |
| **Betriebsmodell** | Welche Aufgaben sollen intern verbleiben, welche darf ein Anbieter übernehmen? Welche Teams verantworten Plattform, Datenprodukte und Support? |
| **Datenresidenz** | In welchen Ländern und Regionen dürfen Daten gespeichert, verarbeitet und gesichert werden? |
| **Integration** | Welche SAP-, On-Premises-, SaaS-, Datei-, Streaming- und API-Quellen müssen verbunden werden? |
| **Latenz und Datenbewegung** | Welche Daten müssen nahe an Quellsystemen oder Verbrauchern verarbeitet werden? Welche Replikations- und Synchronisationsmuster sind erforderlich? |
| **Identität und Zugriff** | Wie werden SSO, Gruppen, Service Principals, Rollen, technische Konten und privilegierte Zugriffe gesteuert? |
| **Netzwerk und Isolation** | Sind Private Endpoints, VPC/VNet-Integration, IP-Beschränkungen, Firewalls oder getrennte Sicherheitszonen erforderlich? |
| **Resilienz** | Wer verantwortet Backups, Recovery, Multi-Region-Design, Wiederanlauf und Notfalltests? |
| **Governance** | Wo werden Ownership, Klassifikation, Lineage, Datenqualität, Richtlinien, Freigaben und zertifizierte Datenprodukte verwaltet? |
| **Kompetenzen** | Welche Plattform- und Cloud-Kompetenzen sind intern vorhanden und welche müssen dauerhaft aufgebaut werden? |

## Governance bleibt in jedem Modell notwendig

Hosting und Governance beantworten unterschiedliche Fragen:

- **Hosting** beschreibt, wo eine Plattform läuft und wer technische Schichten betreibt.
- **Governance** beschreibt, wer Entscheidungen über Daten treffen darf, welche Regeln gelten und wie deren Einhaltung nachgewiesen wird.

| Governance-Bereich | On-Premises | Managed Platform | SaaS / Serverless | Hybrid |
| --- | --- | --- | --- | --- |
| **Data Ownership** | intern | intern | intern | über Systemgrenzen hinweg intern koordinieren |
| **Klassifikation** | intern implementieren | Plattformfunktionen konfigurieren | SaaS-Funktionen konfigurieren | Taxonomie und Propagation vereinheitlichen |
| **Zugriff** | Plattform- und Infrastrukturrollen | Cloud-IAM plus Plattformrollen | Tenant-, Workspace- und Datenrollen | Identitäten und Policies föderieren |
| **Datenqualität** | eigene Regeln und Ausführung | verwaltete Engines, eigene Regeln | Plattformfunktionen, eigene Erwartungen | Regeln über Replikation und Datenprodukte hinweg abstimmen |
| **Lineage** | selbst erfassen oder Katalog integrieren | Plattform- und Katalog-Metadaten verbinden | Anbieter-Metadaten nutzen und erweitern | End-to-End-Lineage über mehrere Systeme herstellen |
| **Retention und Löschung** | vollständig selbst umsetzen | Speicher- und Plattformfunktionen konfigurieren | SaaS-Funktionen und Richtlinien konfigurieren | Aufbewahrung und Löschung über Kopien hinweg koordinieren |

Ein höherer Managed-Service-Anteil reduziert den technischen Plattformbetrieb. Er ersetzt keine fachliche Verantwortungsstruktur.

## Häufige Missverständnisse

### „Cloud bedeutet automatisch SaaS“

Nein. Eine selbst betriebene Datenbank auf virtuellen Cloud-Servern ist IaaS. Ein verwaltetes Warehouse ist PaaS oder Managed Service. Eine vollständig integrierte Analytics-Anwendung kann SaaS sein. Alle drei laufen in der Cloud, besitzen aber unterschiedliche Verantwortungsgrenzen.

### „Serverless bedeutet, dass es keine Server gibt“

Nein. Server und Compute-Ressourcen existieren weiterhin. Bereitstellung, Skalierung und technische Verwaltung werden jedoch stärker durch den Anbieter abstrahiert.

### „Bei SaaS ist der Anbieter für alle Sicherheits- und Governance-Themen verantwortlich“

Nein. Der Anbieter schützt und betreibt die von ihm kontrollierte Plattform. Das Unternehmen bleibt verantwortlich für Daten, Identitäten, Rollen, Konfigurationen, Freigaben, Datenschutz und fachliche Nutzung.

### „Hybrid ist nur eine schlechte Zwischenlösung“

Nicht zwangsläufig. Hybrid kann ein bewusstes dauerhaftes Zielmodell sein, wenn unterschiedliche Workloads, Plattformen und regulatorische Anforderungen kombiniert werden müssen.

### „Alle Big-5-Plattformen besitzen dasselbe Hosting-Modell“

Nein. BigQuery und Fabric sind stark serverless beziehungsweise SaaS-orientiert. Snowflake ist ein vollständig verwalteter Cloud-Dienst. Databricks kombiniert verwaltete Plattformdienste mit unterschiedlichen Compute-Modellen. Redshift unterstützt provisionierte und serverlose Varianten.

### „SAP und die Big 5 sind direkte Alternativen“

Nur teilweise. SAP S/4HANA ist primär eine operative Business-Plattform. SAP BW/4HANA, Datasphere und Business Data Cloud besitzen unterschiedliche analytische und datenbezogene Rollen. In vielen Unternehmen werden SAP-Komponenten mit Snowflake, Databricks, BigQuery, Redshift oder Fabric kombiniert.

### „Ein einheitlicher Cloud-Anbieter erzeugt automatisch ein einheitliches Governance-Modell“

Nein. Auch innerhalb eines Hyperscalers können unterschiedliche Dienste, Accounts, Workspaces, Rollenmodelle und Metadatenstrukturen entstehen. Governance muss bewusst über diese Grenzen hinweg gestaltet werden.

## Praktischer Lernpfad für den Einstieg

Für eine strukturierte Einführung bietet sich diese Reihenfolge an:

1. **Shared Responsibility verstehen:** [Microsoft – Gemeinsame Verantwortung in der Cloud](https://learn.microsoft.com/de-de/azure/security/fundamentals/shared-responsibility)
2. **Das Modell mit einem zweiten Anbieter vergleichen:** [AWS Shared Responsibility Model](https://docs.aws.amazon.com/wellarchitected/latest/security-pillar/shared-responsibility.html)
3. **Google Clouds Perspektive ergänzen:** [Shared Responsibilities and Shared Fate on Google Cloud](https://docs.cloud.google.com/architecture/framework/security/shared-responsibility-shared-fate)
4. **Eine vollständig verwaltete Datenplattform verstehen:** [Snowflake – Key Concepts and Architecture](https://docs.snowflake.com/en/user-guide/intro-key-concepts)
5. **Control Plane und Compute Plane verstehen:** [Databricks – High-Level Architecture](https://docs.databricks.com/aws/en/getting-started/high-level-architecture)
6. **Serverless Analytics einordnen:** [Google BigQuery Overview](https://docs.cloud.google.com/bigquery/docs/introduction)
7. **Verwaltetes und serverloses Warehouse vergleichen:** [Amazon Redshift Overview](https://docs.aws.amazon.com/redshift/latest/mgmt/overview.html) und [Amazon Redshift Serverless](https://docs.aws.amazon.com/redshift/latest/mgmt/serverless-whatis.html)
8. **Eine integrierte SaaS-Analytics-Plattform kennenlernen:** [Microsoft Fabric Overview](https://learn.microsoft.com/de-de/fabric/fundamentals/microsoft-fabric-overview)
9. **SAPs Cloud-Datenebene verstehen:** [SAP Datasphere](https://www.sap.com/products/data-cloud/datasphere.html)
10. **SAPs übergreifende Daten- und Analytics-Ausrichtung einordnen:** [SAP Business Data Cloud](https://www.sap.com/germany/products/data-cloud/what-is-sap-business-data-cloud.html)

Nützliche zusätzliche Ressourcen:

- [Azure Synapse Analytics – Überblick](https://learn.microsoft.com/en-us/azure/synapse-analytics/overview-what-is)
- [SAP BW/4HANA für On-Premises- und Hybrid-Szenarien](https://help.sap.com/docs/sap-btp-guidance-framework/integration-architecture-guide/using-sap-bw-4hana)
- [SAP Analytics Cloud – Einstieg](https://learning.sap.com/courses/exploring-sap-analytics-cloud-de)
- [SAP Datasphere – Dokumentation](https://help.sap.com/docs/SAP_DATASPHERE?locale=de-DE)
- [Microsoft Fabric – Dokumentation](https://learn.microsoft.com/de-de/fabric/)

## Wichtigste Erkenntnisse

- **Cloud ist kein einzelnes Betriebsmodell.** IaaS, Managed Platforms, PaaS, SaaS und Serverless besitzen unterschiedliche Verantwortungsgrenzen.
- Je stärker eine Plattform verwaltet wird, desto mehr technische Betriebsaufgaben übernimmt der Anbieter.
- **Daten, Identitäten, Zugriffe, fachliche Modelle, Qualität und Governance bleiben Unternehmensverantwortung.**
- Die Big-5-Plattformen liegen nicht alle in derselben Hosting-Kategorie und einige decken mehrere Betriebsmodelle ab.
- SAP BW/4HANA, SAP Datasphere, SAP Business Data Cloud und SAP Analytics Cloud erfüllen unterschiedliche Rollen und können parallel mit Big-5-Plattformen eingesetzt werden.
- On-Premises, Cloud-native und Hybrid sind keine Reifestufen mit einem automatisch richtigen Endpunkt. Sie sind unterschiedliche Architekturmodelle.
- Hybride Landschaften sind in großen Unternehmen häufig dauerhaft sinnvoll – vorausgesetzt, Integration, Semantik, Sicherheit und Governance werden über Plattformgrenzen hinweg konsistent gestaltet.
- Die richtige Architektur beginnt nicht mit einem Produktnamen, sondern mit einer klaren Aufteilung von Verantwortlichkeiten.
