---
title: Governance-Metadaten, die Daten tatsächlich steuern — Ownership, Klassifikation und Lebenszyklusentscheidungen in durchsetzbare Kontrollen überführen
description: Eine praxisnahe Architektur, die freigegebene Ownership-, Sensitivity-, Permitted-Use-, Retention-, Quality- und Approval-Metadaten mit Masking, Access, Deletion, Quality Gates und Deployment Controls verbindet und auditierbare Evidenz zurückführt.
category: Data Governance
tags:
  - metadata
  - governance-metadata
  - control-metadata
  - data-governance
  - data-classification
  - data-access
  - data-masking
  - data-retention
  - data-deletion
  - data-quality
  - data-contracts
  - policy-as-code
  - ci-cd
  - ai-governance
  - audit-evidence
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 11
seriesTitle: MetaData Deep Dive
hero: images/playbooks/governance-metadata-that-controls-data-hero.png
---

## Governance-Metadaten erzeugen erst Wert, wenn sie Verhalten verändern

Viele Organisationen besitzen Owner, Sensitivity Labels, Retention Classes und Policy-Dokumente.

Sie können zeigen, dass ein Dataset als `confidential` markiert ist, ein Data Owner zugewiesen wurde und eine Aufbewahrungsdauer diskutiert wurde. Der Catalog wirkt governed. Die Runtime Platform kann dasselbe unmaskierte Feld trotzdem jedem Analysten zeigen, uneingeschränkte Exports erlauben, Kopien unbegrenzt behalten und ein neues Model deployen, obwohl verpflichtende Metadaten fehlen.

Das Problem ist nicht zwingend ein Mangel an Metadaten.

Das Problem ist, dass die Metadaten passiv bleiben.

Ein Label im Catalog schützt keine Daten. Eine Retention Class löscht nichts. Ein Owner-Feld schafft keine Accountability, wenn niemand benachrichtigt wird. Eine AI-Usage-Restriction bleibt wirkungslos, wenn Training Pipelines sie nicht auswerten. Ein Approval Status besitzt keinen Wert, wenn Deployment nicht zwischen `proposed` und `approved` unterscheidet.

> **Governance-Metadaten werden operativ, wenn freigegebene Attribute mit durchsetzbaren Controls, Runtime Verification und auditierbarer Evidenz verbunden werden.**

Dafür ist eine strikte Trennung notwendig zwischen:

```text
Metadaten, die eine Governance-Entscheidung beschreiben
und
Metadaten, die autorisiert sind, einen Control zu steuern
```

Die erste Gruppe hilft Menschen, das Asset zu verstehen. Die zweite Gruppe verändert, was Systeme tun dürfen.

## Mit einem praktischen Governance-Metadatenvertrag beginnen

Ein Governance-Metadatenmodell sollte nicht als große Sammlung optionaler Catalog-Felder starten.

Es sollte als Contract beginnen, der festlegt, welche Entscheidungen für ein governed Asset erforderlich sind, welche Vokabulare gültig sind, wer sie freigeben darf und welche Controls sie konsumieren.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/governance-metadata-that-controls-data-img1-de.png"
        alt="Ein zentraler Governance-Metadatenvertrag verbindet Ownership, Klassifikation, Schutz, Lifecycle, Qualität und Kritikalität sowie Approval-Metadaten rund um ein governed Asset"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein nutzbares Governance-Schema verbindet Accountability, Classification, Protection, Lifecycle, Quality und Approval in einem versionierten Contract, statt die Entscheidungen auf unverbundene Felder zu verteilen.
    </figcaption>
</figure>

Ein minimaler Contract umfasst normalerweise sechs Gruppen.

### Ownership

Ownership-Metadaten erklären, wer für Bedeutung, Betrieb und Governance accountable ist.

Nutzbare Attribute sind:

```yaml
data_owner: role-or-person-reference
steward: role-or-person-reference
technical_owner: team-reference
domain: controlled-domain-code
```

Diese Rollen sind nicht austauschbar.

Der `data_owner` ist für fachliche Nutzung und Risiko des Assets accountable. Der `steward` pflegt Definitionen, Classifications, Quality Expectations und Review Workflows. Der `technical_owner` betreibt Pipeline, Model oder Plattformkomponente. Die `domain` ordnet das Asset einer verantwortlichen fachlichen Grenze zu.

Ein reiferes Modell kann ergänzen:

- Policy Owner;
- Data Product Owner;
- Semantic Owner;
- Source-System-Owner;
- Incident Contact;
- Escalation Group.

Der Contract sollte möglichst stabile Referenzen statt unkontrolliertem Free Text verwenden. Eine Rollen- oder Teamreferenz übersteht Personalwechsel besser als ein kopierter Name mit E-Mail-Adresse.

### Classification

Classification-Metadaten halten fest, welche Art von Daten vorhanden ist und wie sensitiv sie sind.

Typische Attribute sind:

```yaml
pii: true
pii_category: contact_data
sensitivity: confidential
classification_status: approved
```

Diese Felder beantworten unterschiedliche Fragen.

- `pii` zeigt, ob Personal Data enthalten ist.
- `pii_category` identifiziert die relevante Kategorie, etwa Direct Identifier, Contact Data, Location Data oder Pseudonymous Identifier.
- `sensitivity` beschreibt das Handling Level.
- `classification_status` trennt proposed, reviewed, approved, rejected und expired Decisions.

Ein einzelnes Label wie `sensitive` reicht selten aus. Es erklärt weder, was sensitiv ist, noch ob die Classification freigegeben wurde oder welcher Schutz erforderlich ist.

### Protection

Protection-Metadaten verbinden Classification und Purpose mit durchsetzbaren Safeguards.

Nutzbare Attribute sind:

```yaml
masking_policy: pii_email_partial
row_access_domain: customer_service_eu
allowed_usage:
  - service_operations
  - customer_support
```

Protection ist nicht nur eine Kopie der Sensitivity.

Zwei confidential Fields können unterschiedliche Controls benötigen. Eine E-Mail-Adresse kann in Analytics teilweise maskiert werden, während ein gesundheitsbezogenes Attribut vollständig ausgeschlossen wird. Ein Dataset kann für eine operative Domain zugänglich und gleichzeitig für Export verboten sein. Ein pseudonymer Key kann für Entity Matching freigegeben und für Kommunikation oder Advertising verboten sein.

Der Contract sollte die erforderliche Control Intent ausdrücken, ohne jedes plattformspezifische Implementierungsdetail einzubetten.

Beispiel:

```text
masking_policy: pii_email_partial
```

ist besser als eine einzelne vendor-spezifische SQL Expression als Business Policy zu speichern. Die Implementierung kann den Policy Identifier auf den nativen Control der Target Platform mappen.

### Lifecycle

Lifecycle-Metadaten definieren, wie lange Daten gültig, gespeichert und verfügbar bleiben.

Typische Attribute sind:

```yaml
retention_class: customer_contact_24m
deletion_rule: delete_after_purpose_end
source_of_record: crm.customer
```

Die Retention Class sollte eine governte Policy referenzieren, nicht nur eine Anzahl Tage.

Eine nutzbare Policy kann enthalten:

- Retention Trigger;
- Retention Duration;
- Legal-Hold-Verhalten;
- Archive Rules;
- Deletion- oder Anonymization-Action;
- Downstream Propagation;
- Exception Process;
- Review Cycle.

`source_of_record` identifiziert die autoritative operative Quelle. Dadurch wird verhindert, dass eine Derived Copy wie ein unabhängiger permanenter Record behandelt wird.

Lifecycle-Metadaten sollten außerdem unterscheiden:

```text
fachliche Gültigkeit
technische Retention
rechtliche Retention
Backup Retention
Lebensdauer temporärer Verarbeitung
```

Diese Konzepte hängen zusammen, sind aber nicht identisch.

### Quality und Criticality

Quality-Metadaten zeigen, welche Controls erforderlich sind, bevor ein Asset als vertrauenswürdig gilt oder deployed werden darf.

Nutzbare Attribute sind:

```yaml
quality_tier: tier_1
criticality: high
required_controls:
  - freshness
  - completeness
  - uniqueness
  - referential_integrity
```

`quality_tier` sollte ein standardisiertes Set von Expectations referenzieren. `criticality` beschreibt die Konsequenz eines Fehlers. `required_controls` identifiziert die verpflichtenden Checks.

Ein Tier-1-Finanzdataset kann verlangen:

- definierten Owner;
- freigegebene Definition;
- erfolgreiche Reconciliation;
- Freshness Threshold;
- Uniqueness- und Completeness-Checks;
- Change Approval;
- Incident Response Target;
- Deployment Evidence.

Ein exploratives Dataset mit niedriger Criticality kann leichtere Anforderungen erhalten.

Criticality sollte die Stärke des Controls beeinflussen und nicht nur ein weiteres Badge im Catalog erzeugen.

### Approval

Approval-Metadaten bestimmen, ob ein Wert als Active Control Metadata verwendet werden darf.

Typische Attribute sind:

```yaml
review_status: approved
approved_by: governance-role-reference
effective_date: 2026-07-01
policy_version: GOV-DATA-3.4
```

Ein vollständiger Approval Record sollte normalerweise enthalten:

- Decision Status;
- Approver;
- Approval Authority;
- Decision Timestamp;
- Effective Date;
- Expiry oder Review Date;
- Policy Version;
- Evidence Reference;
- Scope und Environment;
- Reason oder Decision Note;
- Exception Identifier, falls vorhanden.

Die wichtigste Trennung lautet:

```text
Metadatenwert
≠
freigegebene Control-Entscheidung
```

Ein Classification Detector kann `confidential` vorschlagen. Ein Steward kann die Classification bestätigen. Ein Policy Owner kann die Protection Rule freigeben. Erst der finale Approved State sollte einen verpflichtenden Runtime Control aktivieren.

## Documentation Metadata von Control Metadata trennen

Governance-Metadaten besitzen zwei operative Klassen.

Descriptive Metadata unterstützt Verständnis. Control-Driving Metadata verändert Plattformverhalten.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/governance-metadata-that-controls-data-img2-de.png"
        alt="Zweispaltiger Vergleich von beschreibenden Metadaten und steuernden Metadaten mit einem Gate von vorgeschlagenen Metadaten über Governance Validation und Human Approval zu aktiven Control-Metadaten"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Beschreibungen und Vorschläge können bereits vor Freigabe nützlich sein. Metadaten, die Masking, Retention, Access oder Deployment Controls aktivieren, benötigen explizite Validation, Authority und State.
    </figcaption>
</figure>

### Descriptive Governance Metadata

Beschreibende Felder umfassen:

- Business Definition;
- Synonym;
- Example;
- Owner Display Name;
- Known Limitation;
- Business Rationale;
- Recommended Use;
- Steward Comment.

Diese Werte sind wichtig. Sie verbessern Discovery, Interpretation und Accountability.

Sie können häufig einen Proposal Workflow tolerieren. Ein AI Assistant kann eine Beschreibung vorschlagen. Ein Domain Expert kann sie präzisieren. Der Catalog kann den Vorschlag mit sichtbarer Confidence und Status anzeigen.

Das operative Risiko eines unvollkommenen Drafts bleibt normalerweise begrenzt, solange er eindeutig als unapproved markiert ist.

### Control-Driving Metadata

Steuernde Felder umfassen:

- Sensitivity;
- Masking Policy;
- Row-Access Domain;
- Retention Class;
- Deletion Rule;
- Permitted Use;
- Quality Tier;
- Approval Status;
- Policy Version;
- Deployment Requirement.

Diese Werte können:

- Access reduzieren oder erweitern;
- Personal Data zeigen oder verbergen;
- einen Export erlauben oder verbieten;
- Deletion starten;
- ein Deployment blockieren;
- AI Training erlauben oder verbieten;
- bestimmen, ob ein Dataset certified ist.

Unreviewed Suggestions dürfen diese Controls nicht direkt aktivieren.

Der Activation Path sollte explizit sein:

```text
Proposed Metadata
→ Governance Validation
→ Human Approval
→ Active Control Metadata
```

Automation kann jede Stufe unterstützen. Sie kann Patterns erkennen, Vocabularies validieren, Conflicts identifizieren, Tasks routen und Evidenz vorbereiten. Sie sollte Authority nicht stillschweigend ersetzen.

### State gehört zum Wert

Ein control-driving Attribute sollte nicht als isolierter Scalar gespeichert werden.

Statt:

```yaml
sensitivity: confidential
```

ist ein reichhaltigeres Object sinnvoll:

```yaml
sensitivity:
  value: confidential
  status: approved
  source: classification-review-1842
  approvedBy: role:data-privacy-reviewer
  effectiveFrom: 2026-07-01
  reviewBy: 2027-07-01
  policyVersion: GOV-CLASS-2.1
```

Die Implementierung kann abweichen. Das Prinzip bleibt: Value, Authority, State, Version und Validity gehören zusammen.

## Legal Basis und Permitted Use präzise, aber minimiert modellieren

Governance-Metadaten müssen häufig festhalten, warum Daten verarbeitet und für welche Zwecke sie genutzt werden dürfen.

Das darf nicht dazu führen, sensitive Case Files, vollständige Legal Opinions oder Identitätsdetails in einen breiten Catalog zu kopieren.

Ein sichereres Design speichert kontrollierte Referenzen.

Beispiel:

```yaml
processing_basis:
  basis_code: contract_performance
  jurisdiction: EU
  policy_reference: PRIV-12
  decision_reference: LEGAL-2026-044
  approved_purposes:
    - customer_service
    - contract_fulfilment
  prohibited_purposes:
    - targeted_advertising
    - general_model_training
```

Der Catalog benötigt nicht die vollständige Legal Analysis. Er benötigt genug Metadaten, um:

- die freigegebene Basis zu identifizieren;
- den Permitted Purpose zu begrenzen;
- Fragen zum accountable Owner zu routen;
- Controls mit der richtigen Policy Version zu verbinden;
- zu belegen, welche Decision zu einem bestimmten Zeitpunkt aktiv war.

Sensitive Evidence kann im Legal-, Privacy- oder Case-Management-System bleiben. Der Metadata Layer speichert Referenz, Decision State und minimale operative Fakten.

Folgende Inhalte sollten nicht ohne klare Notwendigkeit und Schutz in allgemeine Governance-Metadaten gelangen:

- detaillierte Identity Documents;
- Health- oder Disciplinary Records;
- vollständige Legal Opinions;
- individuelle Access Histories;
- Case Narratives;
- Special-Category Samples;
- unnötige Namen betroffener Personen.

Governance-Metadaten sollten Exposure reduzieren und nicht ein weiteres sensibles Repository schaffen.

## Freigegebene Metadaten mit Runtime Controls verbinden

Der zentrale Wert von Governance-Metadaten entsteht durch die Verbindung freigegebener Decisions mit technischer Enforcement.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/governance-metadata-that-controls-data-img3-de.png"
        alt="Ein freigegebener Metadatenvertrag steuert Warehouse Masking, Row-Level Access, BI Reduction, Export Restrictions, Retention- und Deletion-Jobs, Quality Thresholds, CI/CD Gates und AI Restrictions; Runtime Evidence fließt als geschlossener Governance Loop zurück"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Governance ist ein Closed Loop: Freigegebene Metadaten konfigurieren Controls, und Runtime Evidence belegt, ob Controls angewendet, bestanden, verweigert, abgeschlossen oder durch eine Exception übersteuert wurden.
    </figcaption>
</figure>

Der Contract kann mehrere Control Families steuern.

### Warehouse Masking

Eine Masking Policy kann auf eine native Masking-Implementierung gemappt werden.

Beispiel:

```text
sensitivity: confidential
pii_category: contact_data
masking_policy: pii_email_partial
```

Der Activation Service löst die Target Platform auf und wendet den zugehörigen Control an oder verifiziert ihn.

Metadaten dürfen nicht behaupten, dass Daten geschützt sind, nur weil das Policy-Feld befüllt ist. Das System muss festhalten, ob der Runtime Control tatsächlich angewendet wurde.

### Row-Level Access

Eine Row-Access Domain kann einschränken, welche Groups welche Records sehen dürfen.

Beispiel:

```yaml
row_access_domain: customer_service_region
row_access_key: service_region_code
```

Das Identity- und Policy-System sollte die aktuelle User-to-Group-Beziehung auflösen. Der Metadata Contract definiert Policy Binding und geschützte Dimension.

Schnell veränderliche Entitlements sollten nicht als statische Listen in den Catalog kopiert werden.

### BI Application Reduction

BI Applications können Reduction, Section Access, Object Restrictions oder Semantic-Model-Filter entsprechend einer freigegebenen Policy anwenden.

Der Control sollte am governed Dataset oder Semantic Object befestigt werden, statt in jedem Dashboard manuell neu gebaut zu werden.

Lokale BI Logic benötigt trotzdem Verification, weil Report-Level Calculations und Extracts die Upstream Intent umgehen können.

### Export Restrictions

Eine Allowed-Use-Entscheidung kann verhindern:

- Download nach Spreadsheet;
- unapproved API Extraction;
- External Sharing;
- Erstellung öffentlicher Links;
- unmanaged File Delivery;
- Nutzung in einem General-Purpose Notebook.

Ein Dataset, das interaktiv abgefragt werden darf, kann trotzdem für Bulk Export gesperrt sein.

Das Control Model sollte Export als eigene Action abbilden und nicht annehmen, dass Read Access automatisch jede Form der Weiterverwendung einschließt.

### Retention- und Deletion-Jobs

Eine Retention Class sollte auf eine ausführbare Lifecycle Policy auflösen.

Der Job kann:

- Rows nach einem definierten Trigger löschen;
- ausgewählte Identifier anonymisieren;
- temporäre Staging Data entfernen;
- Snapshots auslaufen lassen;
- Derived Extracts bereinigen;
- eine Legal-Hold-Exception erzeugen;
- Downstream Completion verifizieren.

Deletion muss Lineage berücksichtigen. Die Source zu löschen und uneingeschränkte Derived Copies zu behalten, erfüllt die Lifecycle Obligation nicht.

### Data Quality Thresholds

Quality Tiers können verpflichtende Checks und Thresholds aktivieren.

Beispiel:

```yaml
quality_tier: tier_1
required_controls:
  - freshness_max_2h
  - customer_id_not_null
  - customer_id_unique
  - status_code_valid
```

Der Contract referenziert die Expectations. Die Execution Engine liefert Test Evidence zurück.

Ein fehlgeschlagenes Quality Gate kann abhängig von Criticality und Policy Publication, Certification oder Deployment blockieren.

### CI/CD Deployment Gates

Governance-Metadaten können in Pull Requests und Deployment Pipelines validiert werden.

Ein Model, das PII ohne freigegebene Protection Rule einführt, sollte vor Production fehlschlagen. Ein Dataset mit hoher Criticality ohne Owner sollte nicht deployed werden. Ein AI Feature mit prohibited Usage sollte nicht in einen unrestricted Feature Store publiziert werden.

### AI Usage Restrictions

Allowed-Use-Metadaten sollten geprüft werden, bevor Daten in folgende Assets gelangen:

- Training Datasets;
- Retrieval Indexes;
- Embedding Pipelines;
- Feature Stores;
- Prompt Context;
- Evaluation Sets;
- External Model Services.

Ein allgemeines Flag `AI allowed` ist zu grob.

Der Contract sollte mindestens unterscheiden:

```text
training
fine-tuning
retrieval
inference context
evaluation
human-assisted analytics
automated decision support
```

Eine Freigabe für einen Purpose impliziert keine Freigabe für jede AI-Nutzung.

## Den Loop mit Runtime Evidence schließen

Control Activation ohne Verification erzeugt optimistische Governance.

Das System sollte Evidenz erfassen wie:

```text
policy applied
test passed
access denied
export blocked
deletion completed
control drift detected
exception approved
deployment blocked
```

Jeder Evidence Record sollte verknüpfen:

- governed Asset;
- Metadata Contract Version;
- Policy Version;
- Control Instance;
- Environment;
- Execution- oder Event Time;
- Outcome;
- Evidence Source;
- Responsible System;
- Exception, falls relevant.

Dadurch entsteht ein Closed Loop:

```text
Decision
→ Approved Metadata
→ Runtime Control
→ Runtime Evidence
→ Review and Improvement
```

Der Loop unterstützt Fragen wie:

- War die erforderliche Masking Policy tatsächlich aktiv?
- Welche Policy Version wurde angewendet?
- Wurde der Deletion Job über alle relevanten Copies abgeschlossen?
- Wurde Access wegen der vorgesehenen Rule verweigert?
- Hat ein Deployment einen verpflichtenden Control umgangen?
- Ist eine freigegebene Exception noch gültig?
- Ist seit dem letzten Review Control Drift entstanden?

Evidenz sollte normalerweise in dem System verbleiben, das sie am besten speichern kann. Die zentrale Metadata Platform kann normalisierte Summaries und References halten, statt jedes hochvolumige Audit Event zu kopieren.

## Metadaten vor dem Deployment validieren

Governance Controls werden zuverlässiger, wenn Validation vor der Runtime stattfindet.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/governance-metadata-that-controls-data-img4-de.png"
        alt="Pull-Request-Workflow von Metadata- oder Model-Change über Parsing, Governance-Rule-Validation, Pass oder Fail, Human Review, Deployment und Runtime Verification mit verpflichtenden Failure-Beispielen, die Deployment stoppen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Automated Validation prüft Struktur und Mandatory Rules. Human Approval bleibt eine getrennte Decision, und Runtime Verification bestätigt, dass der deployed Control dem freigegebenen Contract entspricht.
    </figcaption>
</figure>

Ein praktikabler Workflow ist:

```text
Change Metadata or Model
→ Parse Metadata
→ Apply Governance Rules
→ Pass / Fail
→ Human Review
→ Deploy
→ Verify Runtime Control
```

### Metadaten parsen

Die Pipeline liest Metadaten aus der freigegebenen Source:

- Model Configuration;
- Data Contract;
- Repository File;
- Catalog API;
- Policy Registry;
- Schema Manifest;
- Deployment Descriptor.

Die geparste Representation sollte deterministisch und versioniert sein.

### Governance Rules anwenden

Rules können validieren:

- Required Fields;
- Controlled Vocabularies;
- Allowed Combinations;
- Approval State;
- Policy References;
- Environment Scope;
- Protection Requirements;
- Lifecycle Requirements;
- AI-Use Restrictions;
- Quality-Tier Requirements.

Beispiele verpflichtender Checks:

```text
required owner missing
invalid sensitivity
PII without approved protection
unknown retention class
prohibited AI usage
unreviewed metadata drives a control
```

### Pass oder Fail

Ein Mandatory Rule Failure muss das Deployment stoppen.

Warnings können für Bedingungen mit geringerem Risiko geeignet sein. Die Unterscheidung muss durch Policy und nicht durch Pipeline Convenience definiert werden.

Beispiel:

```text
missing optional description example
→ warning

PII classification without masking or access policy
→ fail
```

### Human Review

Automated Validation kann beweisen, dass Required Fields und Combinations gültig sind. Sie kann nicht jede fachliche, Privacy- oder Legal-Entscheidung treffen.

Human Approval bleibt getrennt.

Reviewer sollten sehen:

- Proposed Change;
- affected Assets;
- Previous Approved Version;
- Policy Checks;
- Lineage Impact;
- Runtime Targets;
- Evidence;
- requested Exceptions.

### Deploy

Deployment wendet die freigegebene Version auf das Target Environment an.

Der Deployment Record sollte erhalten:

- Contract Version;
- Commit oder Release Identifier;
- Approver;
- Deployment Time;
- Target Environment;
- Applied Control References.

### Runtime Control verifizieren

Nach Deployment sollte das System bestätigen, dass Runtime State und Approved Intent übereinstimmen.

Eine erfolgreiche API Response reicht nicht, wenn der Control nicht am vorgesehenen Asset gebunden wurde.

Verification kann prüfen:

- Policy Attachment;
- Effective Privileges;
- Masking Result;
- Row-Filter Behaviour;
- Quality Execution;
- Deletion Schedule;
- Export Denial;
- AI Pipeline Eligibility.

## Controlled Vocabularies und Kombinationen validieren

Free-Text-Governance-Felder erzeugen inkonsistente Controls.

Beispiele:

```text
Confidential
confidential
conf.
sensitive
high sensitivity
internal confidential
```

können keine Policy zuverlässig steuern.

Stabile Controlled Identifiers sind besser:

```yaml
sensitivity: confidential
retention_class: customer_contact_24m
quality_tier: tier_1
allowed_usage:
  - customer_service
```

Das Display Label kann übersetzt werden. Der Identifier sollte stabil bleiben.

Validation sollte einzelne Values und Kombinationen prüfen.

Beispiele:

```text
pii: true
+ sensitivity: public
→ invalid unless an approved special rule exists

retention_class: permanent
+ temporary_processing: true
→ conflict

allowed_usage: general_model_training
+ prohibited_usage: general_model_training
→ invalid

review_status: approved
+ approved_by: null
→ invalid
```

Auch Vocabularies benötigen Governance.

Jeder Term sollte enthalten:

- Stable Identifier;
- Definition;
- Owner;
- Allowed Scope;
- Valid Combinations;
- Effective Date;
- Deprecated State;
- Replacement Value;
- Policy References.

Ein Code darf nicht stillschweigend wiederverwendet werden, nachdem sich seine Bedeutung verändert hat.

## Dynamische Entitlements in Identity- und Policy-Systemen halten

Governance-Metadaten sollten identifizieren, welche Policy gilt. Sie sollten kein statischer Ersatz für Identity Management werden.

Listen wie:

```text
allowed_users:
  - alice@example.com
  - bob@example.com
  - carol@example.com
```

gehören bei häufigen Änderungen nicht in einen Catalog.

Das passende Pattern lautet:

```text
Governed Asset
→ Policy Identifier
→ Role or Group Requirement
→ Identity and Policy System
→ Current Entitlement Decision
```

Die Metadata Platform kann zeigen:

- Required Role;
- Access Domain;
- Policy Reference;
- Request Workflow;
- Approval Owner;
- Latest Verification State.

Das Identity System bleibt autoritativ für:

- Current Users;
- Group Membership;
- Role Assignment;
- Temporary Access;
- Termination;
- Authentication Context;
- Conditional Access;
- Access Decision Logs.

Diese Trennung verhindert stale Entitlement Copies und reduziert die Menge an Identity Data im Catalog.

## Ein Implementation Pattern passend zur operativen Reife wählen

Mehrere Patterns können Governance-Metadaten mit Controls verbinden.

### Source-Native Enforcement

Die Source Platform speichert und erzwingt Classification, Masking, Row Access und Lifecycle Rules.

Geeignet, wenn:

- der Großteil governed Data in einer Platform konzentriert ist;
- Native Controls den erforderlichen Scope abdecken;
- Central Discovery zweitrangig ist;
- Policy Mapping lokal handhabbar bleibt.

Risiko:

Cross-Platform Consistency, Shared Vocabulary und Enterprise Evidence bleiben begrenzt.

### Central Policy Registry mit Platform Adapters

Ein Central Registry speichert freigegebene Policy Identifiers und Metadata Contracts. Adapters übersetzen sie in Native Controls.

Geeignet, wenn:

- mehrere Platforms konsistente Intent benötigen;
- Local Enforcement nativ bleiben soll;
- Central Policy und Approval erforderlich sind;
- dedizierte Integration Ownership existiert.

Risiko:

Adapters, Version Mappings, Rollback und Drift Detection werden zu operativen Verantwortlichkeiten.

### Contract-as-Code

Governance-Metadaten werden mit Transformation- oder Data-Product-Code gespeichert und in CI/CD validiert.

Geeignet, wenn:

- Engineering Workflows reif sind;
- Review und Deployment code-driven sind;
- Policy Fields deklarativ repräsentiert werden können;
- Production Changes reproduzierbar sein müssen.

Risiko:

Business- und Governance-User benötigen nutzbare Review Interfaces. Code Ownership ersetzt keine Business Approval.

### Workflow-Driven Activation

Ein Governance Workflow gibt Metadaten frei und löst kontrollierte Platform Changes aus.

Geeignet, wenn:

- Approval komplex ist;
- Exceptions und Evidence wichtig sind;
- mehrere Rollen beteiligt sind;
- nicht jedes Target über Code verwaltet wird.

Risiko:

Workflow Status kann vom Runtime State abweichen, wenn Verification nicht automatisiert wird.

### Hybrid Active-Governance Pattern

Contracts können nah an Source oder Code authored, durch einen Governance Process approved, über Platform Adapters aktiviert und mit Runtime Evidence verifiziert werden.

Dies ist häufig das stärkste Enterprise Pattern.

Es ist zugleich am anspruchsvollsten. Es benötigt klare Authority, Stable Identifiers, versionierte Policies, Connector Ownership, Deployment Discipline und Control Monitoring.

## Konkretes Beispiel: governte Customer Contact Data

Ein Data Product stellt bereit:

```text
customer_id
email
phone
service_region
customer_status
last_contact_date
```

Der Proposed Metadata Contract enthält:

```yaml
asset: product.customer_contact
domain: customer_service
data_owner: role:customer-service-data-owner
steward: role:customer-data-steward
technical_owner: team:customer-data-platform

classification:
  pii: true
  pii_category:
    - direct_identifier
    - contact_data
  sensitivity: confidential
  status: approved

protection:
  masking_policy: pii_contact_partial
  row_access_domain: customer_service_region
  allowed_usage:
    - customer_service
    - complaint_resolution
  prohibited_usage:
    - targeted_advertising
    - general_model_training

lifecycle:
  retention_class: customer_contact_24m
  deletion_rule: delete_after_purpose_end
  source_of_record: crm.customer

quality:
  quality_tier: tier_1
  criticality: high
  required_controls:
    - customer_id_unique
    - email_format_valid
    - service_region_not_null
    - freshness_max_2h

approval:
  review_status: approved
  approved_by: role:customer-data-governance-board
  effective_date: 2026-07-01
  policy_version: GOV-CUSTOMER-4.2
```

Der Control Layer übersetzt den Contract in:

- Partial Masking für E-Mail und Telefon;
- regionale Row Filtering;
- deaktivierten Bulk Export für unrestricted Roles;
- 24-monatige Lifecycle Processing;
- Tier-1-Quality-Tests;
- ein Deployment Gate für fehlende Protection;
- Ablehnung allgemeiner Model-Training-Requests.

Runtime Evidence liefert zurück:

```text
masking policy applied
row policy active
quality checks passed
export test denied
retention job scheduled
AI training eligibility denied
```

Später schlägt ein Team vor, die Daten für ein Customer-Churn-Model zu verwenden.

Der Request verändert nicht die Source Classification. Er verändert den Intended Use.

Der Workflow bewertet:

- ob Model Training innerhalb des Approved Purpose liegt;
- ob ein reduziertes Feature Set ausreicht;
- ob Direct Contact Fields ausgeschlossen werden können;
- welche Retention für Training Snapshots gilt;
- ob Outputs neues Profiling Risk erzeugen;
- wer die Exception oder revised Policy freigeben muss.

Das Ergebnis kann lauten:

```text
general model training remains prohibited
approved churn experiment may use selected non-contact features
training snapshot retained for 90 days
direct identifiers excluded
model purpose and evaluation registered
```

Governance-Metadaten ermöglichen eine präzise Decision. Sie reduzieren die Diskussion nicht auf `AI allowed: yes/no`.

## Häufige Anti-Patterns

### Catalog Labels ohne Enforcement

Assets sind klassifiziert, aber kein Masking-, Access-, Retention- oder Deployment-System konsumiert die Values.

Ergebnis:

Governance ist sichtbar, aber nicht operativ.

### AI Suggestions aktivieren Controls

Ein Classifier erkennt PII und wendet direkt Masking oder Deletion an.

Ergebnis:

False Positives stören Access und False Negatives exponieren Data ohne accountable Approval.

### Ein Feld repräsentiert jeden State

Der Catalog speichert `sensitivity: confidential` ohne Proposal, Approval, Effective Date oder Policy Version.

Ergebnis:

Niemand kann belegen, ob der Wert aktuell oder autorisiert ist.

### Free-Text Policy Values

Teams erfassen beliebige Sensitivity-, Retention- und Allowed-Use-Labels.

Ergebnis:

Automation kann Values nicht konsistent mappen und äquivalente Decisions fragmentieren.

### Statische Entitlement Copies

User- und Group-Listen werden in die Metadata Platform kopiert.

Ergebnis:

Access Metadata wird stale und erweitert Identity Exposure.

### Legal Detail wird in den Catalog kopiert

Vollständige Legal Reasoning, Case Documents oder sensitive Identity Data werden als Metadaten gespeichert.

Ergebnis:

Die Governance Platform wird zu einem weiteren High-Risk Repository.

### Control Activation ohne Verification

Ein Workflow meldet Erfolg, nachdem er einen Platform Request gesendet hat.

Ergebnis:

Die freigegebene Policy kann am falschen Runtime Asset gebunden sein.

### Retention ohne Downstream Lineage

Die Source wird gelöscht, aber Derived Tables, Exports und AI Snapshots bleiben bestehen.

Ergebnis:

Lifecycle Obligations werden nur teilweise ausgeführt.

### Deployment Validation ersetzt Approval

Die Pipeline prüft, ob ein Feld einen gültigen Value enthält, und behandelt ihn als approved.

Ergebnis:

Schema Validity wird mit Governance Authority verwechselt.

### Human Approval ersetzt Automation

Jede technische Validation wird manuell durchgeführt.

Ergebnis:

Reviews werden langsam, inkonsistent und nicht skalierbar.

## Entscheidungshilfe

Diese Fragen helfen beim Design von Governance-Metadaten:

```text
Welche Decisions müssen Runtime Behaviour verändern?
Welche Metadatenattribute sind descriptive?
Welche Attributes sind autorisiert, Controls zu steuern?
Wer darf jedes Attribute vorschlagen, prüfen und freigeben?
Welche Vocabularies und Kombinationen sind gültig?
Wo ist jede Policy autoritativ?
Welche Platform erzwingt welchen Control?
Wie wird der freigegebene Value in Native Implementation übersetzt?
Wie wird Runtime Success verifiziert?
Wo wird Evidence gespeichert?
Wie werden Exceptions versioniert und beendet?
Welche Changes müssen Deployment blockieren?
Welche Entitlements müssen in Identity Systems bleiben?
Welche Legal Details können referenziert statt kopiert werden?
```

Mit einem High-Value Control Path beginnen.

Beispiele:

- Approved PII Classification zu Masking;
- Retention Class zu Deletion Job;
- Quality Tier zu Deployment Gate;
- Allowed Use zu AI-Pipeline-Eligibility;
- Row-Access Domain zu Native Policy Binding.

Zuerst den vollständigen Loop belegen, bevor weitere Metadatenfelder ergänzt werden.

## Zentrale Empfehlungen

1. Ownership, Classification, Protection, Lifecycle, Quality, Criticality und Approval als einen versionierten Governance Contract modellieren.
2. Descriptive Metadata und Control-Driving Metadata trennen.
3. Proposal, Validation, Approval, Effective Date und Policy Version mit jedem control-driving Value speichern.
4. Stabile Controlled Identifiers statt Free-Text-Policy-Labels verwenden.
5. Platform-neutral Policy Intent über governte Mappings in Native Runtime Controls übersetzen.
6. Dynamische User- und Group-Entitlements in Identity- und Policy-Systemen halten.
7. Legal- und Privacy-Referenzen mit minimalen operativen Fakten speichern, statt sensibles Case Material zu kopieren.
8. Required Fields, Vocabularies und Combinations vor Deployment validieren.
9. Automated Validation von accountable Human Approval trennen.
10. Deployment blockieren, wenn verpflichtende Governance Rules fehlschlagen.
11. Runtime State nach Activation verifizieren und Evidence mit der Contract Version verbinden.
12. Lineage nutzen, um Lifecycle-, Protection- und Allowed-Use-Decisions über Derived Assets anzuwenden.
13. Exceptions als versionierte, begrenzte und auslaufende Decisions behandeln.
14. Mit einem vollständigen Control Loop für ein reales Risiko beginnen, nicht mit einem großen optionalen Metadata Form.

> **Governance-Metadaten sind erfolgreich, wenn sie vor Deployment konsistente Decisions, zur Runtime durchsetzbares Verhalten und nach Execution belastbare Evidenz erzeugen.**

## Als Nächstes: Metadatenqualität messen und verbessern

Control-Driving Metadata erzeugt eine neue Abhängigkeit.

Masking, Access, Deletion, Quality Gates und AI Restrictions sind nur dann vertrauenswürdig, wenn die Metadaten, die sie konfigurieren, vollständig, aktuell, valide, konsistent und freigegeben sind.

Teil 12 untersucht deshalb, **wie Metadatenqualität gemessen und verbessert wird**:

- welche Quality Dimensions für Metadaten gelten;
- wie Completeness von Usefulness unterschieden wird;
- wie Freshness, Validity und Consistency gemessen werden;
- wie Ownership- und Approval-Qualität bewertet werden kann;
- wie unresolved Conflicts und stale Values sichtbar werden;
- wie Improvement Work priorisiert wird.

Governance-Metadaten machen aus Context eine Action. Metadata-Quality-Management entscheidet, ob diese Actions auf vertrauenswürdigen Inputs basieren.
