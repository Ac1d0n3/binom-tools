---
title: "Fabric, Databricks, Snowflake oder BigQuery — Den Governance-Einstieg auswählen"
description: "Wähle den Governance-Einstieg anhand der bestehenden Landschaft, des ersten governeden Use Cases, des Operating Models und verpflichtender Controls statt über einen Feature-Vergleich."
author: Thomas Lindackers
tags:
  - data-governance
  - plattformauswahl
  - microsoft-fabric
  - databricks
  - snowflake
  - bigquery
  - operating-model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/choose-governance-platform-starting-point-hero.png
series: governance-platform-starting-points
seriesTitle: "Governance-Einstiegspunkte für Datenplattformen"
seriesPart: 1
---

![Fabric, Databricks, Snowflake oder BigQuery — Governance-Einstieg](images/playbooks/choose-governance-platform-starting-point-hero.png)

## Problem

Eine Plattformentscheidung wird unzuverlässig, wenn sie mit Produktnamen beginnt. Fabric, Databricks, Snowflake und BigQuery können governed Data Delivery unterstützen, starten jedoch aus unterschiedlichen Architekturannahmen, Cloud-Kontexten, Nutzungsmustern und Operating Models. Eine Feature-Liste zeigt nicht, ob eine Organisation Ownership etablieren, Business-Metadaten pflegen, sensible Daten schützen, Zugriffe betreiben, Lineage- und Qualitätsnachweise erzeugen und eine vertrauenswürdige Nutzung sicherstellen kann.

Die eigentliche Frage lautet nicht:

> Welche Plattform besitzt die längste Liste an Governance-Funktionen?

Sondern:

> Welcher Einstiegspunkt kann das erste wertvolle Data Product mit der geringsten validierten organisatorischen Reibung governeden?

Diese Unterscheidung ist entscheidend. Der technisch stärkste Kandidat kann trotzdem der falsche Einstieg sein. Eine Plattform kann Änderungen an Identity, neue Skills, einen weiteren Katalog, zusätzliche Lizenzen, regionale Ausnahmen, Netzwerkanpassungen oder Betriebsverantwortung erfordern, die die Organisation noch nicht tragen kann. Umgekehrt kann der bestehende Stack die verpflichtenden Controls bereits erfüllen und lediglich klarere Ownership-, Metadaten- und Qualitätsprozesse benötigen.

Der Vergleich muss daher fünf Kandidaten enthalten:

- Microsoft Fabric
- Databricks
- Snowflake
- BigQuery
- keine neue Plattform

Der fünfte Kandidat ist kein Ausweg aus einer Entscheidung. Er ist ein gültiges Ergebnis, wenn die bestehende Landschaft die erforderlichen Controls erfüllt, der erste governed Use Case keine Migration rechtfertigt oder die Evidenz für einen Plattformwechsel noch unvollständig ist.

![Mit dem Kontext beginnen, nicht mit Produktnamen](images/playbooks/choose-governance-platform-starting-point-img1-de.png)

Der Ausgangskontext muss dokumentiert werden, bevor ein Produkt bewertet wird:

- bestehende Cloud- und Plattformlandschaft
- dominante BI- und Consumption-Schicht
- Bedarf für Engineering, Warehouse, Streaming und Machine Learning
- Reifegrad von Ownership und Stewardship
- Identity- und Access-Modell
- Betriebsmodell für Katalog und Metadaten
- PII-, Residency- und Netzwerkvorgaben
- Delivery- und Betriebskapazität
- Kostentransparenz und Kostenverantwortung

Produktnamen dürfen erst einfließen, nachdem verpflichtende Controls, organisatorische Passung und erforderliche Evidenz definiert wurden.

## Entscheidung

Wähle den Plattform-Einstieg, der für den ersten governeden Use Case verantwortliche Ownership, Auffindbarkeit, den Schutz sensibler Daten, kontrollierten Zugriff, Lineage, Qualitätsnachweise und vertrauenswürdige Nutzung etablieren kann. Behandle das Ergebnis als validierten Startpunkt und nicht als dauerhafte unternehmensweite Standardisierung.

### 1. Den minimalen Governance-Vertrag definieren

Vor dem Plattformvergleich muss feststehen, was das erste governed Data Product nachweisen soll.

Der Vertrag sollte mindestens enthalten:

- den verantwortlichen Data Owner und den operativen Steward
- die Business-Frage und die freigegebene Definition
- die autoritative Quelle und den Ziel-Grain
- die zulässigen Konsumenten und Verwendungszwecke
- die Identity-Gruppen und die Zugriffsentscheidung
- die PII- oder Sensitivitätsklassifizierung
- erforderliches Masking, Row Filtering oder andere Schutzmaßnahmen
- Lineage-Evidenz von der Quelle bis zur Nutzung
- Qualitätsregeln, Schwellenwerte und Incident Owner
- den Publikations-, Zertifizierungs- und Deprecation-Prozess
- das nach der Umsetzung verantwortliche Betriebsteam

Dieser Vertrag trennt eine reale Governance-Anforderung von dem allgemeinen Wunsch nach einer neuen Plattform.

### 2. Einen ersten governeden Use Case auswählen

Eine mehrjährige Plattformvision ist kein geeigneter erster Test. Wähle ein abgegrenztes Data Product, das wertvoll genug ist, um Governance-Lücken sichtbar zu machen, aber klein genug für einen kontrollierten Proof of Value bleibt.

Ein geeigneter erster Use Case besitzt:

- eine benannte Entscheidung oder einen benannten Konsumenten
- einen klaren Owner
- bekannte Quellsysteme
- einen stabilen initialen Grain
- mindestens ein sensibles oder kontrolliertes Attribut
- messbare Qualitätserwartungen
- einen sichtbaren Consumption-Pfad
- einen realistischen operativen Owner

Der Proof of Value muss die vollständige Control-Kette prüfen, nicht nur Ingestion oder Query Performance.

![Den ersten governeden Use Case dem Startpunkt zuordnen](images/playbooks/choose-governance-platform-starting-point-img2-de.png)

### 3. Plattformsignale als Hypothesen verwenden

Die folgenden Signale grenzen die Untersuchung ein. Sie wählen keinen Gewinner automatisch aus.

#### Fabric

Fabric ist eine plausible Starthypothese, wenn die Landschaft bereits Microsoft-zentriert ist, Power BI oder Semantic-Model Delivery dominiert, Entra-basierte Identity etabliert ist und das erste governed Data Product von einem eng integrierten Analytics- und Consumption-Pfad profitieren kann.

Relevante Evidenz:

- Zuordnung von Fabric Items, Workspaces und Domains zu verantwortlicher Ownership
- Unterstützung von Discovery, Lineage und Information Protection durch die Microsoft-Purview-Integration
- Einbindung von Sensitivity Labels, Endorsements und Certification in den Publikationsprozess
- Lizenzierung und regionale Verfügbarkeit der benötigten Fabric- und Purview-Funktionen
- Fähigkeit der Organisation, Capacity, Tenant Settings, Workspaces und Domain Delegation konsistent zu betreiben

Die Hypothese wird schwächer, wenn primär Cross-Cloud Engineering, umfangreiche Nicht-Microsoft-Verarbeitung oder ein Operating Model benötigt wird, das Fabric- und Purview-Verantwortung nicht koordinieren kann.

#### Databricks

Databricks ist eine plausible Starthypothese, wenn der erste Use Case Engineering-lastig, Lakehouse-orientiert, Streaming-intensiv oder eng mit Machine Learning und AI verbunden ist. Unity Catalog bildet den Governance Control Plane für governed Data- und AI-Assets innerhalb des Databricks Operating Models.

Relevante Evidenz:

- Architektur von Unity-Catalog-Metastore und Workspaces
- Ownership und Privilege Inheritance
- Identity Federation und Gruppenmanagement
- Abdeckung von Tabellen, Volumes, Modellen und weiteren securable Objects
- Runtime Lineage und Audit-Evidenz
- erforderliche Klassifizierung, Tags, Policies und Quality Monitoring
- Cloud-spezifische Netzwerk-, Storage- und regionale Vorgaben
- Engineering-Kapazität für den gemeinsamen Betrieb von Pipelines, Compute und Governance

Die Hypothese wird schwächer, wenn die Organisation hauptsächlich ein governed SQL Warehouse und einen Semantic-Delivery-Pfad benötigt, aber nicht über die Kapazität zum Betrieb einer breiteren Lakehouse-Umgebung verfügt.

#### Snowflake

Snowflake ist eine plausible Starthypothese, wenn der erste governed Use Case auf SQL Analytics, governeder Warehouse-Nutzung, Data Sharing oder einem Cross-Cloud Data Service basiert. Snowflake Horizon Catalog und native Policy Objects können Discovery, Lineage, Klassifizierung und Schutz innerhalb der Snowflake-Landschaft unterstützen.

Relevante Evidenz:

- Account-, Database-, Schema- und Role-Design
- Ownership und Rollenhierarchie
- Tags, Klassifizierung und Policy-Zuordnung
- Masking, Row Access und weitere Data-Protection-Anforderungen
- Lineage- und Access-History-Evidenz
- Secure Sharing und Consumer Boundaries
- editionsabhängige Governance-Funktionen
- Kostenverantwortung für Warehouses, Storage und Consumption

Die Hypothese wird schwächer, wenn das erste Data Product überwiegend komplexes Streaming, Notebook-zentriertes Engineering oder ML Operations außerhalb des vorgesehenen Snowflake Delivery Models benötigt.

#### BigQuery

BigQuery ist eine plausible Starthypothese, wenn die Landschaft GCP-nativ ist und der erste Use Case von serverlosen SQL Analytics, Google Cloud IAM und einem Managed-Analytics-Betriebsmodell profitiert. Knowledge Catalog, ehemals Dataplex Universal Catalog, stellt Katalog-, Glossar-, Metadaten-, Lineage- und Data-Quality-Funktionen für die Google-Cloud-Datenlandschaft bereit.

Relevante Evidenz:

- Ownership von Projects, Datasets und Tabellen
- IAM- und Gruppendesign
- Row-Level Access Policies
- Schutz auf Spaltenebene über Policy Tags oder aktuelle Data-Governance-Tags
- Anforderungen an Data Masking
- Abdeckung von Metadaten, Glossar und Lineage im Knowledge Catalog
- Deployment automatischer Data-Quality-Prüfungen und Alert Ownership
- regionale Kompatibilität von BigQuery-, Policy- und Katalogressourcen
- Kostenverantwortung für Slots, Reservations oder On-Demand-Nutzung

Die Hypothese wird schwächer, wenn die Organisation GCP Identity, Projects, Networking und regionale Ressourcenabhängigkeiten nicht als Teil des governeden Data Products betreiben kann.

#### Bestehender Stack

Der bestehende Stack ist der bevorzugte Einstiegspunkt, wenn er dieselben verpflichtenden Controls ohne neue Plattform nachweisen kann. Dafür können Prozess- und Metadatenverbesserungen statt einer Migration erforderlich sein.

Erforderliche Evidenz:

- benannte Owner und Stewards
- freigegebene Business-Definition und Grain
- durchsuchbare Metadaten oder kontrolliertes Inventory
- durchsetzbarer Zugriff und Schutz
- Lineage oder reproduzierbare Source-to-Consumer-Evidenz
- ausführbare Qualitätsprüfungen und Incident Ownership
- Regeln für vertrauenswürdige Publikation und Deprecation
- vertretbare Betriebs- und Supportkosten

Eine Entscheidung gegen eine neue Plattform ist nur gültig, wenn diese Controls nachgewiesen sind. Sie ist keine Erlaubnis, Governance implizit zu lassen.

### 4. Einstiegspunkt und Standardisierung trennen

Die Entscheidung muss festhalten:

- wo das erste governed Data Product startet
- welche Controls nachgewiesen wurden
- welche Lücken bestehen bleiben
- welche Koexistenz akzeptiert wird
- was eine breitere Standardisierung auslösen würde
- wodurch die Entscheidung ungültig würde

So wird verhindert, dass ein Proof of Value ohne erneute Prüfung zu einem Enterprise-Mandat wird.

## Checkliste

Nutze für jeden Kandidaten dieselben Evidenzkategorien.

![Governance-Fit mit derselben Evidenz vergleichen](images/playbooks/choose-governance-platform-starting-point-img3-de.png)

### Ownership und Stewardship

- Ist jedes governed Data Product einem verantwortlichen Data Owner zugeordnet?
- Können operative Stewardship-Aufgaben zugewiesen und gemessen werden?
- Bleiben Plattformadministration und fachliche Verantwortung getrennt?
- Ist eine Eskalation definiert, wenn Ownership fehlt oder umstritten ist?

### Katalog und Business-Metadaten

- Können Konsumenten das Data Product über freigegebene Business-Sprache finden?
- Sind Definition, Grain, Owner, Klassifizierung, Freshness und erlaubte Nutzung sichtbar?
- Können Metadaten über einen Betriebsworkflow statt durch einmalige Dokumentation gepflegt werden?
- Kann der Katalog bei Bedarf Assets außerhalb der Kandidatenplattform abbilden?

### Identity und Access

- Integriert sich der Kandidat mit dem autoritativen Identity Provider?
- Sind Ownership und Lifecycle der Gruppen kontrolliert?
- Kann Zugriff auf der erforderlichen Objekt-, Zeilen- und Spaltenebene ausgedrückt werden?
- Sind privilegierte Administration, Break-Glass Access und Audit-Evidenz abgedeckt?

### PII-Klassifizierung und Schutz

- Können sensible Attribute konsistent klassifiziert werden?
- Können Masking, Filtering oder andere Policies zur Query-Zeit durchgesetzt werden?
- Bleibt der Schutz in Extracts, Shares, Semantic Models und Downstream Tools wirksam?
- Welche Funktionen erfordern eine höhere Edition, ein Zusatzprodukt oder einen regionalen Service?

### Lineage und Source Evidence

- Wird Lineage für die benötigten Verarbeitungspfade automatisch erfasst?
- Umfasst sie relevante Tabellen, Spalten, Jobs, Notebooks, Semantic Models und Reports?
- Können Ausnahmen oder nicht unterstützte Transformationen dokumentiert werden?
- Reicht die Evidenz für Impact Analysis und Incident Investigation aus?

### Qualität und Incident Ownership

- Können Qualitätsregeln versioniert und ausgeführt werden?
- Werden Fehler als Evidenz gespeichert und nicht nur visualisiert?
- Wird ein Incident an einen benannten Owner geroutet?
- Können Konsumenten erkennen, ob ein Data Product gesund, eingeschränkt oder deprecated ist?

### Consumption- und Workload-Fit

- Passt die Plattform zur dominanten BI- und Semantic-Schicht?
- Unterstützt sie die erforderlichen SQL-, Engineering-, Streaming- und ML-Workloads?
- Sind governed Sharing und externe Konsumenten abgedeckt?
- Erfordert der Use Case ein weiteres Tool, das Metadaten oder Control Ownership aufteilt?

### Cloud-, Residency- und Netzwerk-Fit

- Sind alle benötigten Services in der Zielregion verfügbar?
- Können Data Residency und Network Isolation erfüllt werden?
- Sind Cross-Region-Abhängigkeiten für Metadaten, Policies, Replikation oder Egress verstanden?
- Passt das Zieldesign zur bestehenden Cloud Landing Zone?

### Betriebskapazität

- Wer betreibt Plattform, Katalog, Identity, Policies, Qualität und Incidents?
- Sind die benötigten Skills intern verfügbar?
- Ist das Supportmodell klar?
- Kann die Organisation die Controls nach dem Projekt weiter betreiben?

### Kostenverantwortung

- Sind Kosten für Plattform, Capacity, Compute, Storage, Katalog, Governance und Netzwerk sichtbar?
- Sind Editionen und Add-ons berücksichtigt?
- Ist ein Cost Owner benannt?
- Können Koexistenz- und Migrationskosten mit dem bestehenden Stack verglichen werden?

Ein Kandidat muss eine offene Frage bleiben, solange Evidenz fehlt. Annahmen dürfen nicht in Scores umgewandelt werden.

## Artefakt

Dokumentiere das Ergebnis in einer **Platform-Starting-Point-Entscheidung**. Das Artefakt muss durch fachliche Ownership, Architektur, Security, Platform Operations und Delivery reviewbar sein.

![Die Entscheidung zum Plattform-Einstieg dokumentieren](images/playbooks/choose-governance-platform-starting-point-img4-de.png)

### Pflichtfelder

| Feld | Erforderliche Entscheidungsevidenz |
|---|---|
| Erster governed Use Case | Benanntes Data Product, Konsumenten, Business-Entscheidung und Scope |
| Bestehender Kontext | Cloud, Plattformen, BI, Identity, Katalog, Skills und Constraints |
| Kandidat | Fabric, Databricks, Snowflake, BigQuery oder bestehender Stack |
| Stärken in diesem Kontext | Evidenz mit Bezug zum ersten Use Case |
| Governance-Lücken | Fehlende Ownership-, Metadaten-, Access-, Lineage-, Quality- oder Lifecycle-Controls |
| Operating-Model-Abhängigkeiten | Rollen, Teams, Support und Decision Rights |
| Skill- und Kapazitätslücke | Delivery- und langfristige Betriebskapazität |
| Migration oder Koexistenz | Datenbewegung, doppelte Controls, Übergang und Stilllegung |
| Lizenz- und Regionalfragen | Edition, Add-on, Preview, API und Location Dependencies |
| Proof-of-Value-Test | Nachzuweisende Controls und Acceptance Evidence |
| Decision Owner | Verantwortlicher Approver |
| No-Regret Next Step | Sinnvolle Maßnahme, selbst wenn sich der bevorzugte Kandidat ändert |

### Erforderliche Outputs

- bevorzugter Einstiegspunkt
- bedingte Alternative
- ungelöste Blocker
- Validierungsplan
- explizite Non-Goals
- Review-Datum

### Entscheidungsregel

Lehne einen Feature Beauty Contest ab. Genehmige einen Kandidaten nur, wenn er für den definierten Kontext und den ersten governeden Use Case einen besseren Governance-Fit nachweist.

Eine belastbare Entscheidung kann deshalb lauten:

> Wir starten in der bestehenden Microsoft-Landschaft und validieren Fabric für das erste governed Semantic Data Product. Databricks bleibt die bedingte Alternative, wenn Engineering- und Streaming-Anforderungen das validierte Fabric Operating Model überschreiten. Keine der beiden Plattformen wird standardisiert, bevor Ownership, PII-Schutz, Lineage, Qualitätsnachweise, Support und Kostenverantwortung nachgewiesen sind.

Dasselbe Muster kann jeden der fünf Kandidaten auswählen. Der Wert liegt in der Evidenz und den Bedingungen, nicht im Produktnamen.

## Tools

Tools dienen der Sammlung von Evidenz und nicht der Erzeugung eines universellen Scores.

### Entscheidungs- und Operating-Model-Tools

- Canvas für den ersten governeden Use Case
- Ownership- und Stewardship-RACI
- Checkliste verpflichtender Controls
- Plattform-Evidenzmatrix
- PII- und Access-Entscheidungsprotokoll
- Lineage Coverage Map
- Quality-Rule- und Incident-Register
- Skill- und Betriebskapazitätsbewertung
- Lizenz- und Regionalvalidierungslog
- Proof-of-Value-Acceptance-Record
- Platform-Starting-Point-Entscheidung

### Zu validierende Control Surfaces

- **Fabric:** Fabric Governance, Domains, Workspaces, Endorsements, Sensitivity Labels und Microsoft-Purview-Integration
- **Databricks:** Unity Catalog, Account- und Workspace-Administration, Privileges, Lineage, Audit und governed Data-/AI-Assets
- **Snowflake:** Horizon Catalog, Rollenhierarchie, Tags, Classification, Masking Policies, Row Access Policies, Lineage und Access History
- **BigQuery:** IAM, Datasets, Row-Level Access Policies, Column-Level Controls, Data Masking und Knowledge Catalog
- **Bestehender Stack:** vorhandene Katalog-, IAM-, Metadaten-, Quality-, Lineage- und Publication-Controls

Die Verfügbarkeit eines Tools ist kein Implementierungsnachweis. Jeder Control muss im Zielkontext konfiguriert, betrieben und getestet werden.

## Ressourcen

Die aktuelle Produktdokumentation muss zum Implementierungszeitpunkt erneut geprüft werden, da sich Bezeichnungen, Lizenzierung, APIs, Previews, regionale Verfügbarkeit und Einschränkungen ändern können.

- [Microsoft-Fabric-Governance-Dokumentation](https://learn.microsoft.com/de-de/fabric/governance/)
- [Microsoft Purview zur Governance von Microsoft Fabric verwenden](https://learn.microsoft.com/de-de/fabric/governance/microsoft-purview-fabric)
- [Governance und Compliance in Microsoft Fabric](https://learn.microsoft.com/de-de/fabric/governance/governance-compliance-overview)
- [Databricks: Data and AI Governance mit Unity Catalog](https://docs.databricks.com/aws/en/data-governance/)
- [Databricks: Was ist Unity Catalog?](https://docs.databricks.com/aws/en/data-governance/unity-catalog/)
- [Snowflake Horizon Catalog](https://docs.snowflake.com/de/user-guide/snowflake-horizon)
- [Data Governance in Snowflake](https://docs.snowflake.com/de/guides-overview-govern)
- [Google Cloud Knowledge Catalog](https://docs.cloud.google.com/dataplex/docs/introduction?hl=de)
- [Automatische Datenqualität im Knowledge Catalog](https://docs.cloud.google.com/dataplex/docs/auto-data-quality-overview?hl=de)
- [BigQuery-Sicherheit auf Zeilenebene](https://docs.cloud.google.com/bigquery/docs/row-level-security-intro?hl=de)
- [BigQuery-Zugriffssteuerung auf Spaltenebene](https://docs.cloud.google.com/bigquery/docs/column-level-security-intro?hl=de)

## Playbooks

Nutze diese Story zusammen mit den folgenden Entscheidungs- und Implementierungs-Playbooks:

- [Fabric vs Databricks — Den Governance-Einstieg auswählen](/stories/fabric-vs-databricks-governance-start)
- [Nicht mit der Plattform beginnen](/stories/do-not-start-with-the-platform)
- [Den ersten governeden Vertical Slice bauen](/stories/build-first-governed-vertical-slice)
- [Den Data Product Contract definieren](/playbooks/data-product-contract)
- [Ownership vor Tooling definieren](/playbooks/data-ownership)
- [Operational Data Quality](/series/operational-data-quality)
- [Governance Architecture](/series/governance-architecture)

Die bestehende Fabric-versus-Databricks-Seite bleibt eine eigenständige verwandte Entscheidungsseite. Sie wird innerhalb dieser Serie nicht als weiterer wiederholter Plattformvergleich dupliziert.

## Nächster Schritt

Wähle einen ersten governeden Use Case und vervollständige das Entscheidungsartefakt, bevor ein Procurement- oder Standardisierungsprozess geöffnet wird.

Die unmittelbaren No-Regret-Maßnahmen sind:

1. verantwortlichen Owner und Steward benennen
2. Business-Frage, Grain und Konsumenten definieren
3. verpflichtende Identity-, PII-, Access-, Lineage- und Quality-Controls dokumentieren
4. für alle fünf Kandidaten dieselbe Evidenz sammeln
5. Lizenz-, API-, Preview- und Regionalfragen identifizieren
6. einen Proof of Value über die vollständige Governance-Kette durchführen
7. bevorzugten Einstiegspunkt, bedingte Alternative und Blocker dokumentieren
8. vor einer breiteren Standardisierung ein Review-Datum setzen

Die nächste Story der Serie kann anschließend die ausgewählte Plattform als konkreten Governance-Einstieg bewerten und nicht als abstrakten Produktgewinner.
