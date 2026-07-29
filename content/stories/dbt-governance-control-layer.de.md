---
title: "dbt als plattformübergreifende Governance-Kontrollschicht"
description: "Nutze dbt, um Transformation Contracts, Metadata, Tests, Lineage und Deployment Evidence plattformübergreifend umzusetzen, ohne das Transformation Repository zur Authority für Business Ownership, Access, Permitted Use oder Retention zu machen."
author: Thomas Lindackers
tags:
  - data-governance
  - dbt
  - transformation-governance
  - data-contracts
  - data-quality
  - lineage
  - operating-model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/dbt-governance-control-layer-hero.png
series: governance-platform-starting-points
seriesTitle: "Governance-Einstiegspunkte für Datenplattformen"
seriesPart: 6
---

![dbt als plattformübergreifende Governance-Kontrollschicht](images/playbooks/dbt-governance-control-layer-hero.png)

## Problem

dbt kann einen konsistenten Transformation Workflow über Warehouses und Lakehouse SQL Engines hinweg bereitstellen. Sources, Models, Contracts, Tests, Documentation, Lineage, Pull Requests, Deployments und Artifacts lassen sich in einer versionierten Project Structure ausdrücken. Dadurch ist dbt eine plausible plattformübergreifende Control Layer für Transformation Governance.

Damit wird dbt nicht zur Authority für jede Governance-Entscheidung.

Das Transformation Repository kann deklarieren, dass ein Model einen Owner, eine PII Classification, einen Allowed-Usage-Wert oder ein Quality Tier besitzt. Es beweist nicht, dass die verantwortliche Business Role diese Werte genehmigt hat, solange kein externer Workflow, keine Identity und kein Decision Record mit der Deklaration verbunden sind.

Die relevante Frage lautet deshalb nicht:

> Können wir Governance Metadata in YAML schreiben?

Sondern:

> Kann dbt freigegebene Transformation Controls implementieren und belegen, während klare Authority Boundaries für Business Purpose, Ownership, Access, Privacy, Retention und Metric Certification erhalten bleiben?

Vier Fehlmuster sind häufig.

### YAML wird zum ungeprüften Shadow Catalog

Developers ergänzen Descriptions, Owners und Classifications, weil die Felder verfügbar sind. Das Repository wirkt vollständig, aber Werte widersprechen dem Business Glossary, Privacy Inventory oder Platform Catalog. Metadata ohne Authority, Provenance und Review State erzeugt einen weiteren Catalog statt einer Governance Control Layer.

### Test Success wird zur Definition von Quality

dbt Data Tests können Bedingungen für Models und Sources prüfen, und fehlerhafte Datensätze können gespeichert werden. Bestehende Tests beweisen nicht, dass die gewählten Rules die fachliche Quality Expectation repräsentieren, dass Thresholds freigegeben wurden oder dass ein Incident Owner reagieren wird.

### Pull-Request Approval wird mit Business Approval verwechselt

Ein Code Reviewer kann SQL, Naming, Performance und Deployment Risk prüfen. Dieses Review genehmigt nicht automatisch einen neuen Business Purpose, Sensitive-Data-Use, eine Retention-Änderung oder eine Certified Metric Definition.

### Transformation Lineage wird mit End-to-End Lineage verwechselt

dbt Artifacts repräsentieren den Project Graph und zugehörige Resources. Ingestion außerhalb von dbt, Platform Policies, Extracts, Semantic Models, Reports, APIs und AI Consumers können außerhalb bleiben. Der Handoff zu externen Catalogs und Evidence Systems muss explizit entworfen werden.

![Was dbt kontrollieren kann – und was es nicht entscheiden darf](images/playbooks/dbt-governance-control-layer-img1-de.png)

Die Grenze muss sichtbar bleiben:

| dbt kann implementieren oder belegen | dbt kann nicht ownen |
|---|---|
| Source- und Model Contracts | Business Purpose |
| Data Tests und Stored Failures | Verantwortliche Data Ownership |
| Transformation Lineage | Permitted Use |
| Metadata Fields und Documentation | Finale PII-Freigabe |
| Version Control und Pull Requests | Platform Access Enforcement |
| Build- und Deployment Artifacts | Retention- und Deletion-Entscheidungen |
| Source Freshness Checks | Exception Approval |
| Semantic Definitions, wenn genutzt | Business Metric Certification |

## Entscheidung

Nutze dbt als plattformübergreifende Governance Control Layer, wenn SQL Transformation über eine oder mehrere Analytics Platforms wesentlich ist und die Organisation ein wiederholbares Contract-, Metadata-, Test-, Review- und Evidence Pattern benötigt, ohne Business Authority im Transformation Tool zu zentralisieren.

Eine positive Entscheidung setzt normalerweise voraus:

- ein relevantes Volumen gemeinsamer SQL Transformation Logic;
- unterstützte Adapter und Deployment Environments für die Zielplattformen;
- einen versionierten Development- und Release-Prozess;
- ein autoritatives Metadata Schema, das außerhalb einzelner Projects vereinbart ist;
- benannte Data Owner und Stewards für die Freigabe promoteter Metadata;
- einen klaren Catalog- und Lineage-Publication-Pfad;
- gespeicherte Quality Failures und einen Incident Workflow;
- Trennung von Code Review und Business Approval;
- explizite Handoffs für Access, Permitted Use, Retention und Semantic Certification;
- einen Migration Plan für duplizierte Transformationen in BI Tools, Stored Procedures oder lokalen Scripts.

Führe dbt nicht als verpflichtende Schicht für jeden Workload ein. Native SQL, Stored Procedures, Notebooks, Streaming Engines, Low-Code Tools und plattformspezifische Pipelines bleiben valide, wenn sie besser zum Workload passen und über äquivalente Contracts und Evidence governed werden.

### 1. Den Transformation Governance Contract definieren

Die Control Layer benötigt einen expliziten Contract, den alle beteiligten Projects implementieren.

Mindestens werden definiert:

- unterstützte Platforms und Adapter;
- governeder Source- und Model Scope;
- Naming und stabile Identifiers;
- Source Declaration Requirements;
- Model Contract Requirements;
- verpflichtende Model- und Column-Metadata;
- Ownership- und Stewardship-References;
- Classification- und Permitted-Use-References;
- Data-Test-Kategorien und Thresholds;
- Stored-Failure-Rules;
- Review- und Approval States;
- Deployment Environments und Version Evidence;
- Catalog-, Lineage- und Semantic-Handoffs;
- Incident- und Exception-Workflow;
- Deprecation- und Recertification-Trigger.

Der Contract unterscheidet autoritative Werte von lokalen Implementation Values. Eine Model Description kann für abgeleitete Transformation Logic autoritativ sein. Sie darf nicht stillschweigend den Enterprise Business Term oder eine Privacy Decision überschreiben.

### 2. Contracts für die Form nutzen, nicht für jedes Governance-Versprechen

Ein dbt Model Contract definiert die Form des zurückgegebenen Datasets und kann unterstützte Column Names und Data Types durchsetzen. Das ist ein wertvoller Implementation Control, aber enger als ein vollständiger Data Product Contract.

Der breitere Contract benötigt weiterhin:

- Business Purpose und Consumer Scope;
- freigegebenen Grain und Semantics;
- Source Authority;
- Permitted Use;
- Classification und Policy Linkage;
- Freshness und Service Expectation;
- Quality Thresholds;
- Retention und Deletion Rule;
- Incident Owner;
- Publication- und Deprecation-Status.

Model Contracts sind deshalb eine ausführbare Komponente des breiteren Transformation Governance Contract.

### 3. Den vollständigen Evidence Lifecycle verbinden

Der Lifecycle muss explizit sein:

```text
Approved Requirement
→ Source Declaration
→ Model Contract
→ Transformation
→ Data Tests
→ Stored Failure Evidence
→ Technical Review
→ Accountable Approval
→ Deployment
→ Catalog- und Consumer-Publication
→ Change und Recertification
```

![Transformation Contract und Evidence Flow](images/playbooks/dbt-governance-control-layer-img2-de.png)

Verpflichtende Metadata enthält mindestens:

- Owner- und Steward-Reference;
- Domain oder Product;
- Grain;
- PII- oder Sensitivity Classification;
- Criticality;
- Quality Tier;
- Allowed-Usage-Reference;
- Policy References;
- Lifecycle State;
- Review Date.

Das Repository muss außerdem Build Evidence bewahren. dbt Artifacts wie `manifest.json`, `run_results.json`, `catalog.json`, `sources.json` und das Semantic Manifest liefern unterschiedliche Evidenztypen. Speichere Artifact Version, dbt Version, Environment, Commit, Invocation und Deployment Identifier, die für die Reproduktion eines Production State erforderlich sind.

### 4. Failures als operative Evidenz speichern

Ein Dashboard mit einem roten Test reicht nicht aus. Für materielle Controls müssen fehlerhafte Datensätze oder ein freigegebenes aggregiertes Evidence Set mit Kontext aufbewahrt werden.

Das Operating Pattern definiert:

- welche Tests Publication blockieren;
- welche Tests warnen, aber Publication erlauben;
- Failure Thresholds;
- Failure-Storage-Location und Retention;
- Sensitive-Data-Handling in Failure Tables;
- Incident Routing;
- Product-Health-Status;
- Consumer Communication;
- Exception Owner und Expiry;
- Re-Test- und Closure-Evidence.

`store_failures` kann fehlerhafte Datensätze persistieren, ersetzt aber standardmäßig vorherige Failures desselben Tests. Langfristige Incident Evidence kann deshalb einen zusätzlichen Append- oder Snapshot-Prozess benötigen. Failure Storage ist ein Implementation Mechanism und nicht der vollständige Incident Record.

### 5. Metadata durch Declared, Validated und Approved States promoten

Metadata sollte drei sichtbare States durchlaufen.

![Metadata von YAML in den Governance Workflow promoten](images/playbooks/dbt-governance-control-layer-img3-de.png)

#### Declared

Der Developer liefert Model Descriptions, Column Descriptions, Tests und Implementation Metadata. Die Werte sind nützlich, aber noch nicht automatisch autoritativ.

#### Validated

Automatisierte Checks prüfen Required Fields, Controlled Vocabularies, References, Contract Completeness und Consistency. Catalog Synchronization und Steward Review vergleichen die Deklaration mit autoritativen Quellen.

#### Approved

Die verantwortliche Rolle genehmigt Business-relevante Werte, Policy Linkage, Effective Date, Version und Recertification Trigger. Der Approved State wird an Subscribers publiziert oder über einen stabilen Identifier verknüpft.

Ein praktischer Metadata Record kann so aussehen:

```yaml
models:
  - name: fct_sales_order_line
    description: Governed sales-order-line transformation model.
    config:
      contract:
        enforced: true
      meta:
        product_id: sales-order-lines
        data_owner_ref: owner:sales-operations
        steward_ref: steward:sales-data
        grain: one-row-per-order-line
        pii_classification_ref: classification:customer-indirect
        allowed_usage_ref: policy:commercial-analytics
        quality_tier: tier-1
        review_status: approved
        policy_version: "2026-07"
        review_date: "2026-10-29"
```

Die References zeigen auf autoritative Records. Langer Policy Text sollte nicht in jedes Project kopiert werden.

### 6. Technical Review von Accountable Approval trennen

Der Pull-Request Workflow braucht unterscheidbare Approval Gates.

| Gate | Primärer Fokus |
|---|---|
| Engineering Review | SQL Correctness, Naming, Performance, Maintainability und Tests |
| Data Steward Review | Definitions, Metadata Completeness, Classification und Consistency |
| Data Owner Approval | Purpose, Permitted Use, Criticality, Quality Expectation und Exception Acceptance |
| Security- oder Privacy Approval | Sensitive-Data Controls, Policy Conditions und Exceptions |
| Platform Approval | Deployment, Identity, Runtime, Permissions und Operational Readiness |
| Semantic Approval | Metric Definition, Aggregation Behavior, Certification und Consumer Impact |

Nicht jede Änderung benötigt jedes Gate. Der Contract definiert Schwellen. Eine Korrektur einer Description benötigt möglicherweise nur Technical Review; ein Grain Change oder neuer PII Use erfordert Accountable Recertification.

### 7. Catalog-, Access- und Semantic-Handoffs entwerfen

Das dbt Project publiziert oder verlinkt die Evidenz, für die es am besten geeignet ist:

- Model- und Column-Identifiers;
- Transformation Logic und Dependencies;
- Tests und Results;
- Source Freshness;
- Deployment Version;
- Descriptions der Derived Logic;
- Exposures, Groups und Semantic Definitions, wenn genutzt.

Es übergibt Entscheidungen, die es nicht durchsetzt:

- Identity und Platform Privileges;
- Row- und Column-Policies;
- Retention- und Deletion-Controls;
- Legal- oder Privacy Approval;
- Enterprise-Glossary-Authority;
- Certified Metric Approval;
- Downstream Export- und Consumption Controls.

Das Ziel ist synchronisierte Authority und nicht ein Tool, das vorgibt, jedes Feld zu ownen.

## Checkliste

### Scope und Architektur

- Sind unterstützte Platforms, Adapter und Environments explizit?
- Löst dbt gemeinsame Transformation-Governance-Probleme statt nur Default-Tooling zu werden?
- Ist duplizierte Transformation Logic inventarisiert und priorisiert?
- Sind Non-dbt Workloads durch äquivalente Controls abgedeckt?

### Metadata und Authority

- Ist das autoritative Metadata Schema definiert?
- Hat jedes Feld Source Authority, Approver und Review State?
- Werden YAML Values mit Business Glossary und Platform Catalogs synchronisiert?
- Werden stabile Identifiers statt Name-only Matching genutzt?

### Contracts, Tests und Evidence

- Werden Model Contracts dort eingesetzt, wo sie durchsetzbaren Wert liefern?
- Sind Data Tests mit freigegebenen Quality Expectations verbunden?
- Werden Failure Records sicher gespeichert und an einen Owner geroutet?
- Werden Artifacts mit Commit-, Environment- und Deployment-Kontext aufbewahrt?

### Review und Approval

- Sind Code Review und Business Approval getrennt?
- Sind Change Thresholds und Recertification Trigger definiert?
- Sind Exceptions zeitlich begrenzt und mit Evidence verknüpft?
- Kann ein Release blockiert werden, wenn verpflichtende Governance Evidence fehlt?

### Handoffs und Lifecycle

- Ist Catalog- und Lineage-Publication automatisiert oder operativ owned?
- Werden Access-, Policy-, Retention- und Semantic-Entscheidungen an die richtige Authority übergeben?
- Können Consumers Model Status, Quality und Deprecation State sehen?
- Wird die Control Layer bei Änderungen an Platforms, Adapters oder Responsibilities überprüft?

## Artefakt

Dokumentiere die Entscheidung mit dem Tool [Governance Starting-Point Decision](/tools/governance-starting-point-decision?product=dbt).

Fülle die dbt Decision Card in einem Workshop aus. Approved, Conditional oder Not Approved wird nicht vorab eingetragen.

Die Karte dokumentiert:

1. **Decision Context** – Transformation-Governance-Problem, Business Outcome, unterstützte Platforms, Source-/Model-Scope und Decision Owner.
2. **Control-Layer Design** – Metadata Schema, Contracts, Test Strategy, Failure Evidence, Pull-Request Approvals, Deployment Evidence, Catalog-/Lineage-Handoff, Access-/Policy-Handoff, Semantic-Handoff und Migration duplizierter Logic.
3. **Validation, Gaps and Exceptions** – Evidence Location, Proof-of-Value-Tests, ungelöste Gaps, Dependencies, Assumptions und zeitlich begrenzte Exceptions.
4. **Decision Record** – Status, Approved Pattern, Location des Transformation Governance Contract, Alternative, Blocker, No-Regret Next Step, Implementation Owner und Review Date.

Das Artefakt zeigt, dass dbt Controls implementiert und belegt, ohne zur Authority für Ownership, Permitted Use, Access Approval oder Retention zu werden.

## Tools

- dbt Control-Layer Decision Card
- Transformation Governance Contract
- Metadata Authority Matrix
- Required Metadata Validator
- Data-Test and Threshold Register
- Stored-Failure and Incident Pattern
- Pull-Request Approval Matrix
- Artifact Retention Register
- Catalog and Lineage Handoff Map
- Transformation Duplication Retirement Backlog

## Ressourcen

### Contracts, Tests und Failures

- [Model contracts](https://docs.getdbt.com/docs/mesh/govern/model-contracts)
- [Add data tests to your DAG](https://docs.getdbt.com/docs/build/data-tests)
- [`store_failures`](https://docs.getdbt.com/reference/resource-configs/store_failures)
- [Sources and source freshness](https://docs.getdbt.com/docs/build/sources)

### Metadata und Artifacts

- [`meta` configuration](https://docs.getdbt.com/reference/resource-configs/meta)
- [About dbt artifacts](https://docs.getdbt.com/reference/artifacts/dbt-artifacts)
- [Manifest JSON file](https://docs.getdbt.com/reference/artifacts/manifest-json)
- [Run results JSON file](https://docs.getdbt.com/reference/artifacts/run-results-json)
- [Catalog JSON file](https://docs.getdbt.com/reference/artifacts/catalog-json)
- [Sources JSON file](https://docs.getdbt.com/reference/artifacts/sources-json)

### Semantic Handoff

- [dbt Semantic Layer](https://docs.getdbt.com/docs/use-dbt-semantic-layer/dbt-sl)
- [Semantic manifest](https://docs.getdbt.com/reference/artifacts/sl-manifest)

## Playbooks

- [Governance-Einstiegspunkte für Datenplattformen](/series/governance-platform-starting-points)
- [Metadata nah an der Quelle halten](/stories/keep-metadata-close-to-the-source)
- [Den Data Product Contract definieren](/playbooks/data-product-contract)
- [Operational Data Quality](/series/operational-data-quality)
- [Governance Architecture](/series/governance-architecture)

## Nächster Schritt

Wähle einen Transformation Path, der aktuell duplizierte SQL Logic, BI Logic oder mehrere Platforms umfasst, und fülle die dbt Control-Layer Decision Card aus.

Führe anschließend einen Proof of Value durch:

1. autoritatives Metadata Schema definieren;
2. eine Source und ein governed Model deklarieren;
3. die Model Shape dort durchsetzen, wo es sinnvoll ist;
4. Owner-, Grain-, Classification-, Permitted-Use- und Quality-References anhängen;
5. Tests ausführen und Failures speichern;
6. einen Failure an den Incident Owner routen;
7. Code Review von Accountable Approval trennen;
8. Artifacts mit Commit- und Deployment Identity aufbewahren;
9. Lineage und Metadata in den gewählten Catalog publizieren;
10. Access-, Retention- und Semantic-Handoffs verifizieren;
11. eine duplizierte Transformation migrieren;
12. Approved, Conditional oder Not Approved dokumentieren und Review Date setzen.

Der No-Regret Next Step ist kein unternehmensweites dbt-Mandat. Er besteht darin zu beweisen, dass ein freigegebener Transformation Contract konsistent implementiert, operativ beobachtet und mit den Authorities außerhalb des Repository verbunden werden kann.
