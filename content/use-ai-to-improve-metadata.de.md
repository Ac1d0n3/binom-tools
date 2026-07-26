---
title: AI zur Verbesserung von Metadaten einsetzen — Vorschläge skalierbar erzeugen, ohne Wahrscheinlichkeit zur Wahrheit zu erklären
description: Ein praxisnahes Operating Model, um mit deterministischen Regeln, statistischer Erkennung und generativer AI Beschreibungen, Klassifikationen, Domains, Owner und Beziehungen vorzuschlagen und dabei Evidence, Confidence, Review, Approval und Metadatenqualität zu erhalten.
category: Data Governance
tags:
  - metadata
  - artificial-intelligence
  - ai-assisted-metadata
  - metadata-enrichment
  - data-classification
  - metadata-quality
  - data-governance
  - human-in-the-loop
  - provenance
  - confidence
  - data-lineage
  - business-glossary
  - pii-classification
  - active-metadata
  - ai-governance
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 16
seriesTitle: MetaData Deep Dive
hero: images/playbooks/use-ai-to-improve-metadata-hero.png
---

## Metadatenarbeit skaliert nicht, wenn jede Entscheidung mit einem leeren Feld beginnt

Metadatenprogramme stoßen häufig auf dasselbe operative Problem.

Die Organisation besitzt Tausende Tabellen, Spalten, Reports, Metrics, Dokumente, Models und Data Products. Technische Metadaten werden möglicherweise bereits geharvestet, aber Beschreibungen sind unvollständig, Klassifikationen inkonsistent, Business Terms fehlen, Domains sind unklar und Ownership ist veraltet.

Die übliche Reaktion besteht darin, Stewards und Engineers aufzufordern, alles manuell zu dokumentieren.

Dieser Ansatz scheitert aus vorhersehbaren Gründen:

- das Inventory wächst schneller als die Dokumentationskapazität;
- Experten schreiben Informationen neu, die bereits aus Schema, Code oder Lineage abgeleitet werden können;
- ähnliche Assets erhalten unterschiedliche Beschreibungen und Klassifikationen;
- Review Queues werden zu groß;
- Low-Risk-Verbesserungen warten hinter High-Risk-Governance-Entscheidungen;
- User verlieren Vertrauen, wenn generierte Metadaten ohne Evidence erscheinen;
- automatisierte Klassifikationen werden zu operativen Controls, bevor sie validiert wurden.

AI kann diesen Aufwand reduzieren.

Sie kann Beschreibungen entwerfen, wahrscheinliche Business Terms erkennen, Muster personenbezogener Daten identifizieren, Domains vorschlagen, Owner Candidates ranken, ähnliche Assets finden und Quality Rules empfehlen.

Ein AI-Output ist jedoch keine freigegebene Metadateninformation.

Er ist ein Vorschlag, den eine Methode mit bekannten Inputs, Annahmen und Fehlerbildern erzeugt hat.

> **AI sollte Metadatenarbeit durch nachvollziehbare Vorschläge beschleunigen. Sie darf Wahrscheinlichkeit nicht in Autorität verwandeln oder Controls umgehen, die für folgenreiche Entscheidungen erforderlich sind.**

Das Ziel ist deshalb nicht maximale Automatisierung.

Das Ziel ist weniger manueller Aufwand bei stabiler oder verbesserter Metadatenqualität, Accountability und Kontrolle.

## Das Kernprinzip: Generierung und Freigabe sind unterschiedliche Systemzustände

AI-assisted Metadata benötigt eine explizite Trennung zwischen dem, was ein System abgeleitet hat, und dem, was die Organisation als wahr akzeptiert.

Ein brauchbarer Lifecycle unterscheidet mindestens fünf Zustände:

```text
Observed
→ Generated
→ Validated
→ Proposed
→ Approved
```

Diese Zustände beantworten unterschiedliche Fragen.

`Observed` dokumentiert Source Facts wie Column Name, Data Type, Lineage Edge, Query Pattern oder Sample Profile.

`Generated` dokumentiert, was eine Rule, ein Detector oder ein Model erzeugt hat.

`Validated` bedeutet, dass das Ergebnis strukturelle, semantische, Policy- und Consistency-Checks bestanden hat.

`Proposed` bedeutet, dass der Vorschlag für einen definierten Review Process bereitsteht.

`Approved` bedeutet, dass eine accountable Person oder eine autorisierte Low-Risk-Rule den Wert für einen bestimmten Scope und eine bestimmte Version akzeptiert hat.

Der freigegebene Wert kann exakt mit dem generierten Wert übereinstimmen. Die Zustände müssen trotzdem getrennt bleiben.

Diese Trennung verhindert einen häufigen Implementierungsfehler:

```text
Model Response
→ Catalog Field überschreiben
```

Das sicherere Muster lautet:

```text
Model Response
→ Suggestion Record
→ Validation
→ risikobasierter Review
→ Approved Metadata
```

Der ursprüngliche Vorschlag bleibt auch nach Freigabe, Korrektur, Ablehnung oder Supersession verfügbar. Dadurch bleiben Provenance und spätere Evaluation möglich.

## AI als Enrichment Service einsetzen, nicht als Autorität

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/use-ai-to-improve-metadata-img1-de.png"
        alt="Schema, Sample Profiles, Lineage, Code, freigegebene Metadaten und Usage Context fließen in einen AI Enrichment Service, der Vorschläge erzeugt, die vor freigegebenen Metadaten Validation und Human Review durchlaufen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        AI Enrichment erzeugt Vorschläge aus mehreren Evidence Sources. Validation und Human Review bleiben getrennte Gates, und der Service darf nicht direkt in Approved Metadata schreiben.
    </figcaption>
</figure>

Ein Metadata Enrichment Service sollte mehrere Context Sources kombinieren.

### Schema

Schema liefert Namen, Types, Constraints, Keys, Nullability und strukturelle Beziehungen.

Beispiele:

```text
customer_email VARCHAR(320)
order_total DECIMAL(18,2)
contract_valid_from DATE
is_employee BOOLEAN
```

Schema ist wertvoll, aber mehrdeutig.

`status` kann einen Customer, Order, Contract, Payment, Pipeline Run oder Legal Case beschreiben. Ein Data Type verrät allein keine Business Meaning.

### Sample Profiles

Profiles können Muster sichtbar machen, ohne breiten Zugriff auf Raw Values zu benötigen.

Nützliche Signale sind:

- Null Ratio;
- Distinct Count;
- Value Length;
- Pattern Frequency;
- Minimum und Maximum;
- Distribution;
- Common Values;
- Uniqueness;
- Entropy;
- Date Range;
- Identifier Structure.

Profiles können Classification unterstützen. Sie belegen weder Purpose noch Legal Basis oder Ownership.

Sensitive Examples sollten möglichst minimiert, maskiert oder als abgeleitete Patterns dargestellt werden. Für grundlegendes Metadata Enrichment müssen Raw Production Values nur selten an ein generatives Model gesendet werden.

### Lineage

Lineage liefert Kontext aus Upstream Sources und Downstream Consumers.

Ein Feld namens `cust_id` lässt sich besser verstehen, wenn es aus `crm.customer.customer_id` stammt, eine governte Customer Dimension speist und in Retention- und Support-Dashboards erscheint.

Lineage kann unterstützen:

- propagierte Descriptions;
- inherited oder proposed Classifications;
- Domain Inference;
- Relationship Suggestions;
- Owner Candidates;
- impact-aware Prioritization.

Propagation muss trotzdem Transformation Logic und Target Overrides berücksichtigen. Ein abgeleitetes Aggregat erbt nicht zwangsläufig jede Source Classification unverändert.

### Code

SQL, Transformation Definitions, Semantic Models, Tests und Application Code enthalten häufig die präziseste verfügbare Implementation Evidence.

Code kann zeigen:

- Aliases;
- Calculations;
- Filters;
- Joins;
- Units;
- Time Windows;
- Null Handling;
- Comments;
- Tests;
- Source References;
- Business-Rule Names.

Generierte Metadaten sollten relevante Bedeutung zusammenfassen, statt Implementation Code ohne Einordnung zu kopieren.

### Existing Approved Metadata

Freigegebene Definitions, Glossary Terms, Classifications, Ownership Mappings und Policy Values sind hochwertiger Grounding Context.

Sie helfen dem System, etablierte Vocabulary zu verwenden und erfundene Near-Duplicate Terms zu vermeiden.

Approved Metadata sollte normalerweise stärker gewichtet werden als unreviewte Beschreibungen ähnlicher Assets.

### Usage Context

Usage zeigt, wie Assets konsumiert werden.

Nützliche Signale sind:

- Dashboards und Reports, die das Asset verwenden;
- häufige Queries;
- Certified Data Products;
- User Groups;
- Common Joins;
- Support Tickets;
- Failed Searches;
- Applied Filters;
- Export Patterns;
- letzte bedeutende Nutzung.

Usage kann Importance und wahrscheinlichen Purpose anzeigen. Sie beweist nicht, dass die aktuelle Nutzung korrekt oder erlaubt ist.

## Unterschiedliche Suggestion Types mit unterschiedlicher Evidence erzeugen

Ein einzelner Prompt sollte nicht jede Metadatenaufgabe gleich gut lösen müssen.

Jede Task benötigt einen eigenen Input Contract, ein eigenes Evaluation Set und eine eigene Approval Policy.

### Description Suggestions

Descriptions können vorgeschlagen werden aus:

```text
Name
+ Type
+ Table Purpose
+ Code
+ Lineage
+ Glossary Links
+ Usage
```

Eine brauchbare Beschreibung sollte je nach Kontext Meaning, Grain, Unit, Time Reference, Null Behavior, Limitations und Suitable Use erklären.

Das Model sollte nicht nur einen Technical Name ausschreiben:

```text
customer_status = Status des Kunden
```

Ein stärkerer Vorschlag lautet:

```text
Lifecycle State des Customer Accounts am Ende des täglichen CRM Loads.
Werte sind active, suspended, closed und pending-review.
Nicht als aktuelle Support-Eligibility-Entscheidung verwenden.
```

### Business-Term- und Synonym-Suggestions

Ein Model kann Links zu bestehenden Terms oder Synonyms vorschlagen.

Die bevorzugte Reihenfolge lautet:

```text
Approved Term matchen
→ Synonym zur Prüfung vorschlagen
→ neuen Term nur vorschlagen, wenn kein passender Term existiert
```

Similarity ist keine Equivalence.

`Revenue`, `bookings`, `billings` und `cash received` können verwandt wirken und trotzdem materiell unterschiedliche Konzepte darstellen.

### PII- und Sensitivity-Suggestions

Classification kann kombinieren:

```text
deterministische Patterns
+ Column Names
+ Type
+ Sample Profile
+ Lineage
+ Source Classifications
+ Domain Context
```

Ein erkanntes Email Pattern kann einen Vorschlag `personal-contact-data` stützen. Es kann weder Legal Usage noch Retention oder Access Policy allein bestimmen.

High-Impact Classification benötigt stärkere Evidence und Review, weil False Positives und False Negatives unterschiedliche operative Folgen haben.

### Domain Suggestions

Domain Inference kann verwenden:

- Source System;
- Schema und Path;
- Upstream- und Downstream Assets;
- Business Terms;
- Query Communities;
- Data-Product Membership;
- Existing Ownership;
- Report Usage.

Cross-Domain Assets benötigen möglicherweise mehrere Relationships statt einer erzwungenen einzelnen Domain.

### Owner Candidates

AI kann wahrscheinliche Owner ranken anhand von:

- Source Ownership;
- Code Maintainers;
- Recent Contributors;
- Data-Product Accountability;
- Dashboard Owners;
- Domain Stewardship;
- Operational Support History.

Ein Owner Candidate ist keine Owner Assignment.

Activity ist Evidence für Beteiligung, nicht automatisch für Accountability.

### Similar-Asset- und Relationship-Suggestions

Embeddings, Schema Similarity, Lineage und Usage können erkennen:

- Duplicate Datasets;
- Equivalent Fields;
- Related Metrics;
- Source-to-Product Relationships;
- Glossary Candidates;
- Replacement Assets;
- Deprecated Copies.

Das System sollte erklären, warum zwei Assets ähnlich erscheinen:

```text
gleiche Source Lineage
+ 91 % Field Overlap
+ passender Grain
+ gemeinsame Dashboard Consumers
```

Ein Similarity Score ohne Evidence ist schwer reviewbar.

### Quality-Rule-Suggestions

Profiles, Contracts, Tests und Downstream Expectations können vorgeschlagene Rules unterstützen, zum Beispiel:

- Not-Null;
- Uniqueness;
- Accepted Values;
- Format;
- Referential Integrity;
- Freshness;
- Range;
- Distribution Drift;
- Reconciliation.

Eine generierte Rule muss Expected Behavior, Scope, Severity und Evidence enthalten. Sie sollte Production nicht blockieren, nur weil ein Model sie vorgeschlagen hat.

## Deterministische Regeln, statistische Erkennung und generative AI trennen

Die Enrichment Architecture sollte drei Method Families unterscheiden.

### Deterministische Regeln

Deterministische Regeln sind geeignet, wenn die Logic explizit und wiederholbar ist.

Beispiele:

```text
Whitespace entfernen
Case normalisieren
ISO Currency Code mappen
exaktes freigegebenes Identifier Pattern erkennen
Approved Synonym Mapping anwenden
Technical Tag durch verlustfreie Umbenennung vererben
```

Ihre Stärke ist Predictability.

Ihre Grenze besteht darin, dass sie nur codierte Bedingungen abdecken.

### Statistische Erkennung

Statistische Methoden erkennen Patterns aus Values, Distributions oder Relationships.

Beispiele:

- Email-Pattern Detection;
- Identifier Likelihood;
- Anomaly Detection;
- Semantic Similarity;
- Clustering;
- Duplicate Detection;
- Distribution Comparison;
- Owner-Candidate Ranking.

Ihr Output bleibt probabilistisch, auch wenn der Algorithmus nicht generativ ist.

### Generative AI

Generative Models sind für Synthesis und Interpretation geeignet.

Beispiele:

- eine prägnante Description aus mehreren Evidence Sources entwerfen;
- erklären, warum ein Term passen könnte;
- Transformation Logic zusammenfassen;
- reviewer-facing Rationales erzeugen;
- Examples und Counterexamples vorschlagen;
- Ambiguities erkennen, die geklärt werden müssen.

Generative AI sollte keine bereits vorhandenen deterministischen Checks ersetzen.

Eine robuste Pipeline kann alle drei kombinieren:

```text
Deterministic Extraction und Normalization
→ Statistical Detection
→ Generative Synthesis
→ Deterministic Validation
→ Risk-Based Review
```

Die für jeden Vorschlag verwendete Methode muss gespeichert werden. Andernfalls kann die Organisation weder Performance bewerten noch das Ergebnis reproduzieren.

## Mit der einfachsten tragfähigen Implementierung beginnen

Die einfachste tragfähige Implementierung ist eine begrenzte Metadata Task mit messbarem Review Effort und messbarer Quality.

Ein praktikabler erster Use Case ist Description Generation für ein einzelnes governtes Data Product.

### 1. Target Scope definieren

Zum Beispiel:

```text
Description Proposals für undokumentierte Columns
im zertifizierten Customer Analytics Data Product erzeugen.
```

Ausschließen:

- berechnete Regulatory Fields;
- ungeklärte Sensitive Data;
- temporäre Staging Objects;
- Assets mit geplanter Löschung;
- Fields ohne Stable Identity.

### 2. Context Package definieren

Ein minimales Context Package kann enthalten:

```yaml
asset_id: customer_analytics.customer_daily.customer_status
asset_type: column
name: customer_status
data_type: string
parent_asset:
  name: customer_daily
  approved_description: Daily customer account snapshot
lineage:
  upstream:
    - crm.customer.status_code
  downstream:
    - customer_retention_dashboard
code_summary: Mapped from CRM status code through approved lookup table
approved_terms:
  - customer-account
  - lifecycle-status
profile:
  values:
    - active
    - suspended
    - closed
    - pending-review
  null_ratio: 0.0
usage:
  common_filters:
    - active
    - suspended
```

Raw Customer Records werden nicht benötigt.

### 3. Expected Output definieren

Structured Output statt Free Text verwenden:

```yaml
suggested_value: >
  Lifecycle State des Customer Accounts am Ende des täglichen CRM Loads.
  Werte sind active, suspended, closed und pending-review.
limitations:
  - nicht die aktuelle Support-Eligibility-Entscheidung
evidence_refs:
  - approved-term:lifecycle-status
  - lineage:crm.customer.status_code
  - code:mapping-status-code-v4
uncertainties:
  - exakte fachliche Effective Time nicht dokumentiert
```

### 4. Vor dem Review validieren

Validation kann prüfen:

- Required Fields;
- Maximum Length;
- Prohibited Content;
- Unsupported Claims;
- Approved Vocabulary;
- Evidence References;
- Target Identity;
- Duplicate Text;
- Language;
- Formatting;
- Policy Constraints.

Ein grammatisch flüssiges Ergebnis kann trotzdem scheitern, weil es mehr behauptet, als die Evidence stützt.

### 5. In einer fokussierten Queue reviewen

Ähnliche Vorschläge gruppieren.

Reviewern anzeigen:

- Current Value;
- Proposed Value;
- Differences;
- Supporting Evidence;
- Confidence;
- Validation Results;
- Downstream Impact;
- Similar Prior Decisions.

Approve, Edit-and-Approve, Reject und Defer ermöglichen.

### 6. Baseline und Ergebnis messen

Vor Deployment messen:

- durchschnittliche Manual Authoring Time;
- Review Time;
- aktuelle Completion Rate;
- Correction Rate;
- Quality Score;
- Reviewer Agreement.

Nach Deployment dieselben Metrics vergleichen.

AI ist nur nützlich, wenn Total Effort sinkt, ohne die Quality zu reduzieren.

## Jeder Vorschlag benötigt einen nachvollziehbaren Contract

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/use-ai-to-improve-metadata-img2-de.png"
        alt="Eine Proposal Card dokumentiert Suggested Value, Target, Model, Prompt Version, Evidence, Confidence, Generation Time, Status, Reviewer und Decision Reason über die Zustände Generated, Validated, Proposed, Approved, Rejected und Superseded"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein Vorschlag ist ein governter Record und keine temporäre Chat-Antwort. Methode, Evidence, Confidence, Lifecycle und Decision müssen rekonstruierbar bleiben.
    </figcaption>
</figure>

Ein Suggestion Record sollte für die evaluierte Version immutable sein.

Corrections sollten eine Decision oder eine neue Version erzeugen, statt den ursprünglichen Model Output stillschweigend zu verändern.

Ein praktikabler Contract kann so aussehen:

```yaml
suggestion_id: ms-2026-0001842
target:
  asset_id: customer_analytics.customer_daily.customer_email
  attribute: pii_category
suggested_value:
  - personal-contact-data
method:
  type: combined
  rule_version: pii-patterns-12
  detector:
    name: contact-pattern-classifier
    version: 3.4
  model:
    provider: internal-model-endpoint
    name: metadata-reasoner
    version: 2026-07
task_version: pii-classification-v6
prompt_version: pii-review-context-v4
evidence:
  - type: schema-name
    value: customer_email
  - type: profile-pattern
    value: email-format-98.7-percent
  - type: upstream-classification
    value: crm.customer.email=personal-contact-data
confidence:
  score: 0.96
  calibration_version: pii-calibration-3
generated_at: 2026-07-26T08:42:11Z
status: proposed
review:
  reviewer: null
  decided_at: null
  decision: null
  reason: null
```

### Suggested Value

Den eigentlichen Vorschlag in einem type-appropriate Format speichern.

Eine Relationship Suggestion benötigt Source, Target und Relationship Type. Eine Description benötigt Language und Text. Eine Classification kann Category, Scope und Inherited Status benötigen.

### Target Asset und Attribute

Stable Identity ist verpflichtend.

Ein Vorschlag für `email` reicht nicht aus, wenn Hunderte Fields so heißen. Environment, Platform, Asset und Version können relevant sein.

### Model und Version

Die exakte Model- oder Endpoint-Version speichern, sofern verfügbar.

Ein generisches Label wie `AI-generated` reicht für Regression Analysis nicht aus.

### Prompt oder Task Version

Die Task Definition verändert Performance häufig stärker als der Model Name.

Prompt Template, Output Schema, Context-Selection Logic und Policy Version über eine reproduzierbare Task Version speichern.

### Supporting Evidence

Evidence sollte Stable Facts referenzieren und nicht nur das generierte Rationale wiederholen.

Nützliche Evidence Types sind:

- Schema;
- Profile;
- Code;
- Lineage;
- Approved Metadata;
- Glossary;
- Usage;
- Prior Decisions;
- Policy Rules;
- Source Documents.

### Confidence

Confidence ist nur nützlich, wenn ihre Bedeutung definiert ist.

Raw Token Probability, Similarity Score und Calibrated Classification Probability sind unterschiedliche Maße.

Confidence sollte pro Task interpretiert und gegen beobachtete Outcomes evaluiert werden.

### Generated At

Timestamps helfen, stale Suggestions zu erkennen.

Ein Vorschlag auf Basis eines alten Schemas, ehemaligen Owners oder superseded Policy ist möglicherweise nicht mehr reviewbar.

### Status

Ein kontrolliertes Status Model kann enthalten:

```text
Generated
→ Validated
→ Proposed
→ Approved
→ Superseded
```

mit Rejection aus den Review States.

### Reviewer und Decision Reason

Approval ohne Reviewer oder autorisierte Automation Identity ist unvollständig.

Rejection Reasons sind besonders wertvoll, weil sie Evaluation Labels werden und fehlenden Context, mehrdeutige Vocabulary sowie systematische Model Errors sichtbar machen.

## Automation Level an das Risiko anpassen

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/use-ai-to-improve-metadata-img3-de.png"
        alt="Ein dreistufiges Risk Model ordnet Auto-Accept Formatnormalisierung und deterministische Mappings zu, Bulk Review Beschreibungen und Domain Suggestions sowie Individual Approval PII, Legal Usage, Retention, Access Policy und Training Permission"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Automation sollte Consequence, Reversibility und Evidence Strength folgen. Confidence ist ein Signal, aber nicht das einzige Approval Criterion.
    </figcaption>
</figure>

Der Approval Path sollte nach Risiko gewählt werden, nicht danach, ob AI verwendet wurde.

### Auto-Accept

Auto-Accept ist für Low-Impact-, reversible und stark begrenzte Änderungen geeignet.

Beispiele:

- Formatting Normalization;
- freigegebene Deterministic Synonym Mappings;
- Case Normalization;
- Low-Risk Technical Tags;
- Exact Identifier Extraction;
- Deduplication von Whitespace;
- Propagation durch eine verifizierte verlustfreie Umbenennung.

Auch auto-akzeptierte Änderungen benötigen Provenance und Rollback.

### Bulk Review

Bulk Review ist geeignet, wenn Vorschläge zahlreich sind, der Impact moderat ist und Patterns gemeinsam bewertet werden können.

Beispiele:

- Descriptions;
- Domain Suggestions;
- Similar-Asset Links;
- Business-Term Candidates;
- Owner Candidates;
- Low-Severity Quality-Rule Proposals.

Das Interface sollte Grouping, Filtering und Sampling unterstützen, ohne individuelle Evidence zu verstecken.

Bulk Approval darf kein Blind Approval bedeuten.

### Individual Approval

Individual Approval ist erforderlich, wenn ein Wert Legal-, Security-, Access-, Retention- oder AI-Usage-Controls aktivieren kann.

Beispiele:

- PII Classification;
- Special-Category Data Classification;
- Legal Usage;
- Retention;
- Access Policy;
- Masking Requirement;
- Deletion Policy;
- Training Permission;
- External-Model Permission.

Für diese Decisions kann Evidence aus Legal, Security, Privacy, Records Management oder vom accountable Data Owner erforderlich sein.

### Confidence ist keine Control Policy

Eine Rule wie:

```text
confidence >= 0.95 → approve
```

reicht nicht aus.

Approval sollte außerdem berücksichtigen:

- Task Risk;
- Evidence Type;
- Evidence Freshness;
- Source Authority;
- Model Calibration;
- Domain;
- Ambiguity;
- Reversibility;
- Downstream Controls;
- Disagreement zwischen Methoden;
- Change gegenüber dem Current Approved Value.

Eine Description Suggestion mit 99 % Confidence ist hinsichtlich Impact nicht mit einer Training-Permission-Suggestion mit 99 % Confidence gleichzusetzen.

## Verhindern, dass Vorschläge High-Impact Controls aktivieren

Die Grenze zwischen Descriptive Metadata und Control-Driving Metadata muss explizit bleiben.

Ein Model kann vorschlagen:

```yaml
pii_category: personal-contact-data
retention_class: customer-contract-seven-years
training_permission: prohibited
```

Diese Werte sollten nicht direkt verändern:

- Access Grants;
- Masking;
- Row-Level Security;
- Retention Jobs;
- Deletion Workflows;
- Exports;
- Model-Training Datasets;
- Deployment Gates.

Das sichere Muster lautet:

```text
AI Proposal
→ Validation
→ Required Approval
→ Approved Governance Metadata
→ Policy Engine
→ Runtime Control
→ Execution Evidence
```

Die Policy Engine sollte nur freigegebene Werte aus dem autorisierten State konsumieren.

Suggestions können trotzdem Non-Binding Actions auslösen:

- Review Task erzeugen;
- Owner benachrichtigen;
- Asset priorisieren;
- Evidence anfordern;
- Approval blockieren, bis Required Fields vorhanden sind.

Ein High-Confidence Proposal kann Review beschleunigen. Es darf nicht stillschweigend zu einer Legal- oder Security-Entscheidung werden.

## Review für Geschwindigkeit und Qualität gestalten

Human Review ist nicht automatisch sicher oder effizient.

Ein schlechtes Interface kann dazu führen, dass Reviewer flüssige Texte freigeben, ohne Evidence zu prüfen.

Ein brauchbarer Review Screen sollte zeigen:

```text
Current Approved Value
Proposed Value
Difference
Evidence
Confidence und Calibration
Validation Results
Source Freshness
Downstream Impact
Similar Prior Decisions
```

### Task-spezifische Queues verwenden

Descriptions, PII Classifications und Owner Candidates nicht in einer Queue mischen.

Unterschiedliche Tasks benötigen unterschiedliche Expertise und Decision Criteria.

### Nach Impact priorisieren

Review Order kann berücksichtigen:

- Criticality;
- Downstream Usage;
- Missing Mandatory Metadata;
- Potential Exposure;
- Number of Dependents;
- Age;
- Disagreement;
- Review Effort.

### Edit-and-Approve unterstützen

Ein korrigierter Vorschlag sollte dokumentieren:

```text
Original Proposal
→ Reviewer-Edited Value
→ Approved Value
→ Correction Category
```

Das liefert stärkeres Feedback als ein einfaches Approval Flag.

### Rejection Reasons erfassen

Ein Controlled Set mit optionalen Comments verwenden:

```yaml
reason:
  code: insufficient-evidence
  detail: Das Source Field ist eine Shared Mailbox und keine Customer Email Address
```

Nützliche Reason Categories sind:

- Wrong Meaning;
- Wrong Scope;
- Outdated Context;
- Insufficient Evidence;
- Invalid Vocabulary;
- False Positive;
- False Negative;
- Duplicate Term;
- Owner not accountable;
- Policy requires specialist review;
- Asset changed;
- Suggestion no longer relevant.

### Sampling für Bulk Decisions verwenden

Bulk Review kann kombinieren:

- Deterministic Grouping;
- Representative Samples;
- Outlier Detection;
- Confidence Bands;
- Mandatory Review von Disagreements;
- Post-Approval Quality Sampling.

Sampling reduziert Aufwand, beseitigt aber Accountability nicht.

## Konkretes Beispiel: ein Customer Field anreichern, ohne eine Policy zu aktivieren

Angenommen, ein Warehouse enthält:

```text
analytics.customer_daily.contact_email
```

Das Field besitzt weder Description noch Owner oder PII Classification.

Der Enrichment Service erhält:

```yaml
schema:
  name: contact_email
  type: varchar
profile:
  pattern: email
  pattern_match_rate: 0.994
  null_ratio: 0.18
lineage:
  upstream:
    - crm.customer.primary_email
  downstream:
    - service_case_contact_view
approved_upstream_metadata:
  description: Primary email address supplied by the customer
  pii_category:
    - personal-contact-data
usage:
  frequent_users:
    - customer-service-analytics
code:
  transformation: lower(trim(primary_email))
```

Die Deterministic Rule erkennt eine verlustfreie Normalization.

Der Statistical Detector erkennt ein Email Pattern.

Das Generative Model erzeugt eine Proposed Description:

```text
Normalisierte Primary Email Address, die vom Customer bereitgestellt und
für Customer-Service Analytics verwendet wird. Null bedeutet, dass zum
Zeitpunkt des täglichen Loads keine Primary Email Address im CRM verfügbar war.
```

Der Service erzeugt drei getrennte Suggestions:

```yaml
description:
  review_mode: bulk-review
  confidence: 0.93

pii_category:
  value: personal-contact-data
  review_mode: individual-approval
  confidence: 0.98

owner_candidate:
  value: customer-service-data-product-owner
  review_mode: bulk-review
  confidence: 0.74
```

Die Classification Suggestion aktiviert kein Masking.

Nach Approval veröffentlicht der Governance Metadata Service die Classification. Danach bewertet die Policy Engine, ob Masking für den jeweiligen Runtime Context erforderlich ist, und dokumentiert Evidence über die angewendete Policy.

Der Owner Candidate kann abgelehnt werden, weil das Team das Field konsumiert, aber nicht dessen Source verantwortet.

Dieses Beispiel zeigt, warum Suggestion Type, Evidence und Consequence getrennt werden müssen.

## Alternative Implementierungsmuster

Unterschiedliche Operating Models können dieselben Prinzipien umsetzen.

### Source-Native Assistance

AI Suggestions werden innerhalb der Source-, Transformation- oder BI-Plattform erzeugt.

Geeignet, wenn:

- Metadata Ownership lokal ist;
- Context nahe an der Source am stärksten ist;
- die Plattform ausreichenden Review und Export bietet;
- Cross-Platform Consistency noch nicht kritisch ist.

Warnung:

Lokale Suggestions können fragmentiert und zentral schwer evaluierbar werden.

### Central Enrichment Service

Ein Shared Service konsumiert Metadaten aus mehreren Systemen und schreibt Suggestion Records in eine zentrale Metadata Platform.

Geeignet, wenn:

- Common Vocabularies wichtig sind;
- Evaluation konsistent sein soll;
- Cross-Platform Lineage verfügbar ist;
- Model- und Prompt-Governance zentralisiert sind.

Warnung:

Der Service muss Source-Specific Context erhalten und darf nicht jedes Asset in einen generischen Prompt flatten.

### Federated Domain Enrichment

Eine gemeinsame Plattform stellt Contracts, Models, Metrics und Controls bereit, während Domains Context, Review und Acceptance verantworten.

Geeignet, wenn:

- Terminology nach Domain variiert;
- accountable Experten verteilt sind;
- Enterprise Standards und Local Meaning gleichzeitig wichtig sind.

Warnung:

Ohne Minimum Standards kann jede Domain inkompatible Status, Confidence Meanings und Review Practices erzeugen.

### Embedded Copilot

Ein User fordert beim Bearbeiten von Metadaten Suggestions an.

Geeignet, wenn:

- Authoring Assistance das primäre Ziel ist;
- ein Human bereits im Workflow ist;
- unmittelbarer Context verfügbar ist;
- Approval explizit erfolgt.

Warnung:

Chat History darf nicht zum einzigen Provenance Record werden. Final Suggestion und Evidence benötigen weiterhin Structured Storage.

### Batch Enrichment Pipeline

Suggestions werden zeitgesteuert oder nach Harvesting erzeugt.

Geeignet, wenn:

- der Backlog groß ist;
- Changes erkannt werden können;
- Review Queues etabliert sind;
- Cost und Throughput kontrolliert werden müssen.

Warnung:

Wiederholte Generation kann Duplicate Suggestions erzeugen, wenn Asset, Attribute, Evidence und Task Versions nicht dedupliziert werden.

## Den Feedback Loop schließen

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/use-ai-to-improve-metadata-img4-de.png"
        alt="Ein Feedback Loop verbindet Suggestion Generation, Human Decisions, Decision Reasons, Task Evaluation, Verbesserungen an Context, Rules oder Prompt, Re-Testing und Release einer neuen Version und misst Acceptance, Corrections, False Positives, Review Time, Coverage, Quality und Drift"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Human Decisions werden zu Evaluation Evidence. Abgelehnte Suggestions bleiben für Audit, Error Analysis und Regression Testing verfügbar, statt aus dem System zu verschwinden.
    </figcaption>
</figure>

Feedback ist nur nützlich, wenn es strukturiert und mit der exakten Suggestion Version verbunden ist.

Der Operating Loop lautet:

```text
Suggestions generieren
→ Human Decisions erfassen
→ Reasons und Edits erfassen
→ nach Task und Risk evaluieren
→ Context, Rules oder Prompt verbessern
→ erneut testen
→ neue Version releasen
```

### Abgelehnte Suggestions aufbewahren

Rejected Proposals sollten nicht gelöscht werden.

Sie unterstützen:

- Audit;
- False-Positive Analysis;
- Regression Testing;
- Model Comparison;
- Prompt Evaluation;
- Reviewer Calibration;
- Dispute Reconstruction;
- Drift Detection.

Retention sollte der Metadata- und AI-Evidence-Policy der Organisation folgen, besonders wenn Samples oder Sensitive Evidence beteiligt sind.

### Task-spezifische Evaluation Sets aufbauen

Ein Description Benchmark und ein PII-Classification Benchmark sollten keinen generischen Accuracy Score teilen.

Evaluation Sets sollten repräsentieren:

- Domains;
- Asset Types;
- Languages;
- Common Cases;
- Rare Cases;
- Ambiguous Cases;
- Changed Schemas;
- Conflicting Evidence;
- High-Impact Classes.

Historische Human-Approved Decisions können Evaluation initialisieren, enthalten aber möglicherweise Inkonsistenzen. Gold Sets benötigen eigenen Review und Versioning.

### Vor Release erneut testen

Ein neues Model, ein neuer Prompt, eine Rule, Context Source oder ein Threshold kann eine Task verbessern und eine andere verschlechtern.

Regression Testing sollte vergleichen:

- Current Production Version;
- Candidate Version;
- Baseline ohne AI;
- Domain Slices;
- Confidence Bands;
- High-Risk Classes;
- Known Prior Failures.

Release Criteria sollten pro Task definiert werden.

## Aufwand und Qualität gemeinsam messen

Acceptance Rate allein ist eine schwache Success Metric.

Ein Model kann eine hohe Acceptance Rate erzielen, indem es nur offensichtliche Low-Value Changes vorschlägt. Es kann gleichzeitig Hidden Work erzeugen, wenn Reviewer verbose oder schlecht belegte Outputs prüfen müssen.

Eine Balanced Scorecard enthält Folgendes.

### Acceptance Rate

```text
approved suggestions / reviewed suggestions
```

Nach Task, Domain und Confidence Band interpretieren.

### Correction Rate

```text
edit-and-approved suggestions / reviewed suggestions
```

Corrections zeigen Near-Misses und können informativer als Rejection sein.

### False-Positive Rate

Besonders wichtig für Classifications und Relationship Proposals.

Für High-Impact Tasks muss auch die False-Negative Rate gemessen werden.

### Review Time

Median und Distribution messen, nicht nur den Average.

Eine kleine Zahl schwieriger Cases kann den gesamten Effort dominieren.

### Coverage Gained

Messen, wie viele nützliche Metadata Gaps geschlossen wurden.

Coverage sollte nach Asset Criticality und Usage gewichtet werden, nicht nur nach Asset Count.

### Quality After Approval

Approved Metadata sollte gegen Quality Rules bewertet werden, zum Beispiel:

- Completeness;
- Accuracy;
- Consistency;
- Specificity;
- Evidence;
- Vocabulary Compliance;
- Freshness;
- Reviewer Agreement;
- User Usefulness.

### Drift by Domain

Performance kann sich verschlechtern, wenn Schemas, Terminology, Source Systems oder Business Processes wechseln.

Ergebnisse nach Domain, Language, Asset Type und Source messen.

### Total Effort

Eine brauchbare Outcome Metric lautet:

```text
Generation Cost
+ Validation Cost
+ Review Time
+ Correction Time
+ Operational Support
```

im Vergleich zur Manual Baseline.

Das Programm ist erfolgreich, wenn dieser Total Effort sinkt und die Quality der Approved Metadata stabil bleibt oder steigt.

## Häufige Anti-Patterns

### Direkt in Approved Fields schreiben

Dadurch verschwindet die Trennung zwischen Proposal und Truth.

### Alle Confidence Scores als vergleichbar behandeln

Similarity Score, Detector Probability und Model Self-Rating bedeuten nicht dasselbe.

### Unnötige Production Values an ein Model senden

Metadata Enrichment benötigt häufig Profiles und Patterns, nicht Raw Sensitive Records.

### Einen Prompt für jede Task verwenden

Descriptions, Classifications, Ownership und Relationships benötigen unterschiedliche Evidence und Evaluation.

### Nur auf Acceptance Rate optimieren

Reviewer können generische Texte freigeben, weil Korrektur langsamer als Approval ist.

### Rejected Suggestions löschen

Dadurch geht die Evidence verloren, die zum Verständnis und zur Verbesserung von Failure Modes benötigt wird.

### Controls aus generierten Werten automatisch aktivieren

Ein Proposal sollte Access, Masking, Retention, Deletion oder Training Permission nicht direkt verändern.

### Annehmen, dass Upstream Metadata immer propagiert

Transformations können aggregieren, tokenisieren, maskieren, kombinieren oder Meaning verändern.

### Owner aus Activity allein generieren

Der aktivste User ist nicht automatisch accountable.

### Unsupported Claims in flüssigen Descriptions verstecken

Validation muss Aussagen mit Evidence vergleichen und nicht nur Syntax prüfen.

### Volume statt Value messen

Zehntausend generierte Descriptions sind nicht nützlich, wenn sie generisch, stale oder nie reviewed sind.

## Decision Guidance

Für jede Enrichment Task eine einfache Decision Sequence verwenden.

```text
Kann der Wert deterministisch abgeleitet werden?
→ Rule verwenden und Version speichern

Benötigt die Task Pattern Detection?
→ Detector verwenden und kalibrieren

Benötigt die Task Synthesis über mehrere Evidence Sources?
→ Generative AI mit Structured Output verwenden

Kann ein falscher Wert einen folgenreichen Control aktivieren?
→ Individual Approval verlangen

Können Suggestions sicher gruppiert werden?
→ Bulk Review mit Sampling und Outlier Checks verwenden

Können Quality und Effort gegen eine Baseline gemessen werden?
→ kontrollierten Proof of Value durchführen

Kann jede Decision rekonstruiert werden?
→ für Production freigeben
```

Nicht mit dieser Frage beginnen:

```text
Welches Model soll alle unsere Metadaten generieren?
```

Sondern mit:

```text
Welche Metadata Task erzeugt vermeidbaren Aufwand?
Welche Evidence ist vorhanden?
Was passiert, wenn der Vorschlag falsch ist?
Wer ist accountable für Approval?
Wie wird Quality gemessen?
```

## Zentrale Empfehlungen

1. Jeden AI Output als versionierten Vorschlag behandeln, bis ein autorisierter Process ihn freigibt.
2. Suggestions in Schema, Profiles, Lineage, Code, Usage und Approved Metadata gründen.
3. Deterministische Rules, Statistical Detectors und Generative AI in Architecture und Evaluation trennen.
4. Model, Task, Prompt, Evidence, Confidence, Timestamp und Lifecycle State speichern.
5. Quality Metrics und Gold Sets für jede Task und jedes Risk Level separat definieren.
6. Auto-Accept nur für Low-Risk-, reversible und stark begrenzte Changes verwenden.
7. Bulk Review für skalierbares Moderate-Risk Enrichment und Individual Approval für folgenreiche Governance Values einsetzen.
8. Verhindern, dass unfreigegebene Suggestions Access-, Masking-, Retention-, Deletion- oder AI-Usage-Controls aktivieren.
9. Rejections, Corrections und Decision Reasons als Evaluation Evidence erhalten.
10. Total Effort, Coverage und Approved Quality gemeinsam messen.

## Der nächste Schritt: Metadaten wie ein Produkt betreiben

AI kann die Erstellung von Metadaten beschleunigen.

Sie schafft allein keine nachhaltige Metadata Capability.

Die Organisation benötigt weiterhin:

- klare Consumers;
- Service Levels;
- Ownership;
- Prioritization;
- Quality Objectives;
- Adoption Measures;
- Release Management;
- Support;
- Lifecycle Decisions;
- eine Roadmap.

Teil 17 wechselt deshalb vom AI-assisted Enrichment zum langfristigen Operating Model: **Metadaten wie ein Produkt betreiben**.
