---
title: Lineage, Impact Analysis und Metadatenvererbung — Nachvollziehen, wie sich Daten und Kontext durch jede Transformation verändern
description: Eine praxisnahe Architektur, die System-, Dataset-, Column-, Process-, KPI-, Report- und AI-Lineage mit transformationsbewusster Metadatenvererbung, Konfliktlösung, Impact Analysis und auditierbarer Evidenz verbindet.
category: Data Governance
tags:
  - metadata
  - data-lineage
  - column-lineage
  - impact-analysis
  - metadata-propagation
  - metadata-governance
  - data-catalog
  - data-products
  - semantic-layer
  - data-quality
  - data-classification
  - data-retention
  - incident-response
  - ai-governance
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 10
seriesTitle: MetaData Deep Dive
hero: images/playbooks/lineage-impact-and-metadata-propagation-hero.png
publishedAt: 2026-06-29 10:00
---

## Lineage wird erst wertvoll, wenn sie Konsequenzen erklärt

Viele Metadatenplattformen können Pfeile zwischen Assets zeichnen.

Eine Source Table speist eine Transformation. Die Transformation erzeugt ein governed Dataset. Ein Semantic Model konsumiert dieses Dataset. Ein Dashboard und ein AI Feature hängen vom Semantic Model ab.

Der Graph kann vollständig aussehen und trotzdem nicht die Fragen beantworten, die bei Änderungen, Incidents oder Governance-Entscheidungen relevant sind:

- Welche konkreten Columns haben zum Target beigetragen?
- Wurde die Beziehung zur Laufzeit beobachtet, im Code deklariert oder manuell ergänzt?
- Hat eine Transformation den Wert erhalten, umbenannt, aggregiert, maskiert oder fachlich neu interpretiert?
- Dürfen Sensitivität, Ownership, Retention, Quality Rules oder Beschreibungen vererbt werden?
- Welche nachgelagerten Objekte sind technisch abhängig?
- Welche davon sind fachlich kritisch?
- Wo enthält die Lineage Lücken, unsichere Mappings oder ungelöste Konflikte?
- Welcher Owner muss eine Änderung vor dem Deployment freigeben?

Eine schwache Implementierung behandelt jeden Pfeil gleich. Sie nimmt an, dass Konnektivität allein ausreicht, um Kontext zu vererben und Auswirkungen zu bewerten.

Diese Annahme ist unsicher.

Eine direkte Projektion von `customer.email` nach `customer_email` erhält wesentlich mehr Bedeutung als eine Aggregation wie `COUNT(DISTINCT customer_id)`. Ein maskierter Wert kann weiterhin sensitiv sein, obwohl die ursprünglichen Zeichen nicht mehr sichtbar sind. Ein Hash kann Matching ermöglichen und gleichzeitig für Kommunikation ungeeignet sein. Ein Join kann mehrere Owner, Retention-Verpflichtungen und Quality Expectations zusammenführen. Ein semantischer KPI kann Filter und Ausschlüsse einführen, die in der physischen Source nicht existieren.

> **Lineage erklärt, wie Assets verbunden sind. Metadatenvererbung nutzt diese Verbindungen zusammen mit Transformationssemantik, Autorität und expliziten Regeln, um zu bestimmen, welcher Kontext übernommen werden darf.**

Diese Trennung macht aus Lineage ein operatives Steuerungssystem statt eines Diagramms.

## Lineage über den vollständigen Consumption Path modellieren

Lineage sollte nicht an der Warehouse Table enden.

Ein entscheidungsrelevantes Lineage-Modell verbindet den Weg vom operativen System bis zum finalen Consumer:

```text
Source System
→ Ingestion Pipeline
→ Raw Dataset
→ Transformation Model
→ Governed Data Product
→ Semantic Model
→ Report, API and AI Consumer
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/lineage-impact-and-metadata-propagation-img1-de.png"
        alt="End-to-End-Lineage vom Quellsystem über Ingestion, Raw Data, Transformation, governed Data Product und Semantic Model bis zu Report-, API- und AI-Consumern mit vorwärtsgerichtetem Data Flow und rückwärtsgerichteten Impact-Analysis-Pfaden"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Nutzbare Lineage verbindet technische Bewegung, Transformationslogik, governte Produkte und consumernahe Semantik. Reverse Traversal macht aus demselben Graph eine Impact Analysis.
    </figcaption>
</figure>

Unterschiedliche Fragen benötigen unterschiedliche Drill Levels.

### System Lineage

System Lineage verbindet Plattformen und große Betriebsgrenzen:

```text
CRM
→ Integration Platform
→ Cloud Warehouse
→ BI Platform
→ AI Service
```

Sie ist für Architektur, Ownership und den groben Incident Scope nützlich. Für Änderungen auf Feldebene ist sie nicht präzise genug.

### Dataset- und Table-Lineage

Dataset Lineage identifiziert, welche gespeicherten oder logischen Datenbestände voneinander abhängen:

```text
crm.customer
→ raw.crm_customer
→ core.dim_customer
→ product.customer_360
```

Diese Ebene unterstützt Deprecation Planning, Pipeline Troubleshooting und Data-Product-Ownership.

### Column Lineage

Column Lineage identifiziert Input Columns, Expressions und Target Columns einer Ableitung:

```text
crm.customer.email
→ LOWER(TRIM(email))
→ core.dim_customer.email_normalized
```

Diese Ebene ist für Classification Propagation, die Bewertung von Quality Rules, Schema Change Analysis und AI Feature Traceability notwendig.

### Process Lineage

Process Lineage repräsentiert den ausführbaren Schritt, der Daten erzeugt oder bewegt hat:

```text
source extract
→ ingestion task
→ SQL model
→ semantic refresh
→ report publication
```

Der Process Node sollte Referenzen auf Code, Deployment, Runtime Evidence und das verantwortliche Team behalten. Ohne Process Lineage kann der Graph zeigen, dass zwei Datasets verbunden sind, aber verbergen, wie die Verbindung implementiert wurde.

### KPI- und Semantic-Lineage

Ein KPI ist nicht nur eine weitere Column.

Ein Measure kann verbinden:

- mehrere Source Measures;
- Dimensions und Relationships;
- Filter Context;
- Time Windows;
- Currency Conversion;
- Exclusions;
- Aggregation Behaviour;
- Restatement Rules.

Lineage sollte den KPI deshalb sowohl mit seinen physischen Inputs als auch mit seiner semantischen Definition verbinden.

### Report- und API-Lineage

Reports, Dashboards, Extracts und APIs sind operative Consumer. Sie sollten als First-Class Assets modelliert werden, weil sie reale Abhängigkeiten, Service Commitments und User Impact erzeugen.

Ein Feld, das im Warehouse technisch ungenutzt wirkt, kann weiterhin in einem Export, API Contract oder einer Workbook Calculation enthalten sein.

### AI Lineage

AI Lineage sollte verbinden:

```text
source data
→ training or retrieval dataset
→ feature or embedding
→ experiment or index build
→ model or retrieval component
→ deployment
→ evaluation and output
```

Für AI-Nutzung muss Lineage zusätzlich Purpose, Time Window, Feature Derivation, Training Snapshot, Approval State und Permitted Use erhalten. Zu wissen, dass ein Model ein Dataset konsumiert hat, reicht weder für Reproducibility noch für Governance.

## Beobachtete, deklarierte und kuratierte Lineage trennen

Lineage besitzt mehrere Evidenztypen. Sie sollten verbunden, aber nie stillschweigend zusammengezogen werden.

### Observed Query Lineage

Observed Lineage wird aus ausgeführten Operationen abgeleitet:

- SQL Query History;
- Runtime Events;
- Pipeline Runs;
- Read- und Write-Operationen;
- API Calls;
- Stream Producer- und Consumer-Aktivität.

Ihre Stärke ist operative Evidenz. Sie beweist, dass eine Beziehung in einem bestimmten Environment und Time Window tatsächlich aufgetreten ist.

Ihre Grenzen sind:

- fehlende Historie nach Ablauf der Retention;
- unvollständiges Parsing von Dynamic SQL;
- versteckte Bewegung außerhalb überwachter Systeme;
- temporäre Queries, die nicht zur dauerhaften Architektur werden sollten;
- Runtime Paths, die sich zwischen erfolgreichen und fehlgeschlagenen Ausführungen unterscheiden.

Observed Lineage sollte erhalten:

```text
observation time
execution identifier
environment
query or operation reference
parser version
coverage scope
confidence
```

### Declared Code Lineage

Declared Lineage wird aus implementierten Definitionen abgeleitet:

- Transformation Code;
- Model Manifests;
- Pipeline Specifications;
- Notebook Dependencies;
- Semantic-Model Expressions;
- Infrastructure Configuration;
- Data Contracts.

Ihre Stärke ist Design Intent und Expression-Level-Kontext.

Ihre Grenzen sind:

- Code, der nie deployed wurde;
- Branches, die von Production abweichen;
- generierte Logik, die schwer zu parsen ist;
- Macros oder Stored Procedures, die Dependencies verbergen;
- Configuration, die nicht mehr dem Runtime Behaviour entspricht.

Declared Lineage sollte eine Code Revision, Environment und Deployment State enthalten.

### Manually Curated Lineage

Curated Lineage wird von einer Person ergänzt, wenn automatisierte Evidenz fehlt oder nicht ausreicht.

Sie kann repräsentieren:

- einen externen File Transfer;
- einen manuellen Spreadsheet-Prozess;
- ein fachliches Mapping;
- ein Legacy Interface;
- eine semantische Beziehung, die nicht parsebar ist;
- eine freigegebene Ausnahme;
- eine temporäre Migrationsbrücke.

Curated Lineage ist in vielen Enterprises notwendig. Sie wird gefährlich, wenn sie nicht von beobachteter Evidenz unterscheidbar ist.

Jede kuratierte Edge sollte enthalten:

```text
author
reason
evidence
scope
review date
approval status
expiry or revalidation date
```

Eine manuell behauptete Beziehung ohne Review sollte nicht dieselbe Confidence erhalten wie ein wiederholt beobachteter Production Path.

## Lineage als typisierte, versionierte Evidenz behandeln

Eine Lineage Edge sollte mehr sein als:

```text
Asset A → Asset B
```

Eine nutzbare Edge kann so repräsentiert werden:

```yaml
source: core.customer.email
target: product.customer_contact.email_normalized
relationship: derives
process: model.customer_contact
transformationType: rename_and_normalize
expression: lower(trim(email))
evidenceType: declared_code
environment: production
validFrom: 2026-07-01T08:00:00Z
observedAt: 2026-07-25T02:14:31Z
confidence: 0.98
coverage: column
status: active
```

Das konkrete Schema kann abweichen. Die benötigten Konzepte sollten es nicht.

Wichtige Attribute sind:

- Source- und Target-Identity;
- Relationship Type;
- Process oder Transformation;
- Expression oder Rule Reference;
- Evidence Type;
- Environment;
- Validity Period;
- Observation Time;
- Confidence;
- Coverage Level;
- Review- und Approval State.

Versionierung ist wichtig, weil Impact Analysis zeitabhängig ist.

Ein Report kann letzten Monat von `customer_status` abhängig gewesen sein und heute `customer_lifecycle_state` verwenden. Eine Incident Investigation muss den Graph rekonstruieren, der zum Fehlerzeitpunkt existierte, nicht nur den aktuellen Graph.

## Propagation benötigt Transformationssemantik

Lineage zeigt, dass ein Output von einem Input abhängt. Sie bestimmt nicht, welche Metadatenattribute nach der Transformation weiterhin gültig sind.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/lineage-impact-and-metadata-propagation-img2-de.png"
        alt="Regelmatrix mit Propagation Outcomes für Description, Sensitivity, PII Category, Owner, Retention, Quality Rule, Unit und Allowed Usage über Projection, Rename, Cast, Concatenation, Join, Union, Aggregation, Masking, Hashing und Custom Calculation"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Lineage identifiziert mögliche Vererbungswege. Transformationssemantik bestimmt, ob Metadaten propagiert, transformiert, neu berechnet, blockiert oder zur Prüfung weitergeleitet werden.
    </figcaption>
</figure>

Eine Propagation Engine sollte mindestens die folgenden Transformation Types unterscheiden.

### Direct Projection

```sql
SELECT email
FROM customer
```

Eine direkte Projektion erhält normalerweise:

- Description;
- Sensitivity;
- PII Category;
- Unit;
- Allowed Usage;
- wertbezogene Quality Expectations.

Ownership und Retention können sich trotzdem ändern, wenn das Target zu einem anderen Data Product oder rechtlichen Processing Context gehört.

### Rename

```sql
SELECT email AS customer_email
FROM customer
```

Ein Rename erhält normalerweise die Bedeutung des Wertes. Die Target Description sollte den neuen Namen berücksichtigen und kann kontextbezogene Formulierung benötigen. Classification und Value Semantics propagieren in der Regel.

### Cast

```sql
SELECT CAST(customer_id AS VARCHAR)
```

Ein Cast erhält Identität nur, wenn die Konvertierung verlustfrei ist und die Interpretation nicht verändert.

Beispiele mit Review-Bedarf:

- Timestamp zu Date;
- Decimal zu Integer;
- Local Time zu UTC ohne Timezone Evidence;
- Numeric Code zu formatiertem Text;
- Free Text, das in eine strukturierte Kategorie geparst wird.

Der Data Type verändert sich und Quality Rules müssen möglicherweise neu berechnet werden.

### Concatenation

```sql
SELECT first_name || ' ' || last_name AS full_name
```

Der Output übernimmt Sensitivity aus beiden Inputs, aber die Description muss transformiert werden. Quality Rules der einzelnen Felder gelten nicht automatisch für den zusammengesetzten String.

Concatenation kann neue Sensitivität erzeugen. Die Kombination aus Postal Code, Birth Date und Gender kann das Re-Identification Risk erhöhen, obwohl jedes Attribut einzeln niedriger klassifiziert war.

### Join

Ein Join kombiniert Row Populations und Business Contexts.

Propagation muss berücksichtigen:

- Join Keys;
- Cardinality;
- Unmatched Rows;
- Duplicate Creation;
- Target Grain;
- Source Ownership;
- kombinierten Policy Scope.

Descriptions sollten nicht ungeprüft aus einer Input Table kopiert werden. Dataset-Level-Ownership gehört normalerweise zum Target Data Product, während Source Provenance sichtbar bleibt.

### Union

Eine Union kombiniert Records mit nominell kompatiblen Strukturen.

Vor der Vererbung sollten geprüft werden:

- äquivalente Bedeutung;
- kompatible Units;
- kompatible Code Lists;
- abgestimmte Classifications;
- gemeinsame Retention Obligations;
- Definition der Target Population.

Ein Feld `status` kann in zwei Systemen inkompatible Lifecycle Semantics enthalten. Dieselbe Column Position beweist nicht dieselbe Bedeutung.

### Aggregation

```sql
SELECT region, COUNT(DISTINCT customer_id) AS active_customers
```

Aggregation benötigt normalerweise:

- eine neue Description;
- neu berechnete Unit und Grain;
- neue Quality Expectations;
- Target Ownership;
- Review von Sensitivity und Disclosure Risk.

Eine Raw-PII-Classification sollte nicht blind auf einen Count propagieren. Kleine Gruppen oder seltene Kategorien können trotzdem Confidentiality Risk erzeugen. Das Ergebnis kann eine andere Classification benötigen statt gar keiner.

### Masking

Masking verändert Sichtbarkeit, nicht zwingend Sensitivität.

```text
thomas@example.com
→ t*****@example.com
```

Der maskierte Wert kann weiterhin Personal Data sein. Er kann weiterhin Identification, Correlation oder Contact-Domain-Inference ermöglichen.

Propagation sollte unterscheiden:

- reversible Masking;
- partial Masking;
- Tokenization;
- irreversible Redaction;
- dynamic Presentation Masking.

Allowed Usage muss explizit bewertet werden.

### Hashing

Hashing kann direkte Lesbarkeit reduzieren und Linkability erhalten.

Ein deterministischer Hash kann weiterhin Personal Data sein, wenn er über Records abgeglichen oder aus einem begrenzten Input Space neu berechnet werden kann.

Die Target Description muss erklären:

- Algorithm Class;
- Salt- oder Key-Handling;
- Determinism;
- Collision Expectations;
- vorgesehenen Matching Use;
- verbotene Reversal- oder Lookup-Nutzung.

Sensitivity darf nicht automatisch auf public oder anonymous herabgestuft werden.

### Custom Calculation und Derivation

Eine Custom Expression kann ein vollständig neues Konzept erzeugen:

```sql
CASE
    WHEN last_order_date >= current_date - 90
     AND open_balance = 0
    THEN 'active'
    ELSE 'inactive'
END AS customer_status
```

Output Description, Owner, Quality Rules und Allowed Use müssen für das abgeleitete Konzept definiert werden. Source Metadata ist unterstützende Provenance, aber keine vollständige Target Definition.

## Propagation Rules je Metadatenattribut definieren

Transformation Type ist nur eine Achse. Jedes Metadatenattribut benötigt eine eigene Policy.

### Descriptions

Eine Description darf propagieren, wenn Wert und Bedeutung erhalten bleiben.

Sie sollte transformiert oder neu geschrieben werden, wenn:

- der Name sich wesentlich verändert;
- sich der Grain verändert;
- mehrere Inputs kombiniert werden;
- eine Business Rule angewendet wird;
- ein Code gemappt wird;
- eine Aggregation entsteht;
- das Target einen engeren Approved Purpose besitzt.

Eine kopierte Description, die die Transformation ignoriert, ist schlechter als keine Description, weil sie falsches Vertrauen erzeugt.

### Sensitivity und PII Category

Sensitivity sollte grundsätzlich konservativ propagieren.

Beispielregeln:

```text
direct value preservation
→ retain classification

combination of sensitive inputs
→ retain or increase classification

masking or hashing
→ reassess, do not automatically remove

aggregation
→ recalculate disclosure risk

approved irreversible anonymization
→ classification may change with evidence
```

Das System sollte ursprüngliche Source Classifications erhalten, auch wenn das Target eine andere freigegebene Classification erhält.

### Ownership

Technical oder Business Ownership sollte normalerweise nicht als einzelner Wert vererbt werden.

Ein nutzbares Modell trennt:

- Source Owner;
- Transformation Owner;
- Data Product Owner;
- Semantic Owner;
- Consumer Owner;
- Policy Owner;
- Steward.

Das Target Asset sollte einen eigenen accountable Owner besitzen und gleichzeitig Upstream Ownership für Eskalation behalten.

### Retention

Retention hängt von Legal Purpose, Operational Need, Contractual Obligation und Target Processing Context ab.

Ein Target kann nicht sicher die kürzeste oder längste Source Retention übernehmen, ohne eine Policy anzuwenden.

Typische Regeln:

- direkte Copies bleiben an Source Obligations gebunden;
- Derived Products erhalten eine freigegebene Retention Policy;
- Joins nutzen bis zum Review die strengste anwendbare Obligation;
- Aggregates können nur dann länger gespeichert werden, wenn Re-Identification und Purpose Constraints geklärt sind;
- temporäre Processing Assets erhalten explizite Deletion Schedules.

### Quality Rules

Einige Quality Rules propagieren, andere müssen neu berechnet werden.

Beispiele:

- `email must match approved format` kann durch ein Rename propagieren;
- `customer_id must be unique` kann nach einem Join mit Row Duplication ungültig werden;
- `sales_amount must be non-negative` kann nach Currency Conversion weiter gelten, benötigt aber unit-bezogene Thresholds;
- `status must use source code list` sollte nach Mapping auf Enterprise Lifecycle States nicht propagieren.

Ein Target sollte festhalten, ob eine Rule inherited, transformed, newly defined oder intentionally not applicable ist.

### Units und Formats

Units benötigen explizite semantische Behandlung.

```text
EUR
→ converted to USD
```

ist kein Rename.

Das Target benötigt Conversion Rate Source, Effective Time, Rounding und Unit. Percentages, Ratios, Durations und Timestamps erfordern eine vergleichbare Behandlung.

### Allowed Usage

Permitted Use ist kein rein technisches Attribut.

Ein Feld, das für Service Operations freigegeben ist, kann für Marketing, Model Training oder Automated Decision-Making ungeeignet sein. Propagation muss Purpose und Consumer Context bewerten.

Ein kopiertes Allowed-Use-Tag ohne Purpose Validation kann unzulässige Downstream Usage ermöglichen.

## Mehrere Inputs, Overrides und Konflikte auflösen

Ein Derived Field kann Metadaten aus mehreren Inputs erhalten.

Beispiel:

```text
email        — confidential PII
phone        — confidential PII
customer_id  — internal identifier
```

Diese Werte tragen zu einem abgeleiteten `contact_key` bei.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/lineage-impact-and-metadata-propagation-img3-de.png"
        alt="Drei klassifizierte Input Columns fließen durch einen Resolver aus Column Lineage, Transformation Rule, Approved Target Override und Conflict Policy zu resolved, proposed oder unresolved Metadata"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Mehrere Inputs benötigen explizite Präzedenz. Freigegebene Target Decisions und Transformation Rules stehen über propagierten Vorschlägen, während ungelöste Konflikte für Review sichtbar bleiben.
    </figcaption>
</figure>

Der Resolver sollte nicht willkürlich einen Input auswählen.

Eine praktikable Priorität ist:

```text
Approved Target Override
> Approved Transformation Rule
> Propagated Source Metadata
> Detection Proposal
```

### Approved Target Override

Ein Target Override ist eine explizite, geprüfte Entscheidung für das Target Asset.

Beispiele:

- der Derived Key ist confidential, weil er weiterhin linkable ist;
- Retention wird für einen temporären Matching Process auf 30 Tage begrenzt;
- Marketing Use ist verboten;
- Ownership gehört zum Customer Identity Data Product.

Ein Override sollte Approver, Reason, Scope, Effective Date und Review Date enthalten.

### Approved Transformation Rule

Eine wiederverwendbare Transformation Rule kann wiederkehrende Fälle auflösen.

Beispiel:

```text
deterministic hash of direct identifier
→ classification remains confidential
→ allowed use limited to matching
→ description must include algorithm policy reference
```

Rules sollten versioniert sein. Eine Rule Change kann eine Neubewertung aller Targets auslösen, die diese Rule genutzt haben.

### Propagated Source Metadata

Source Metadata liefert Candidate Values.

Wenn mehrere Inputs übereinstimmen, kann das Ergebnis automatisiert resolved werden. Bei Abweichungen sollte das System eine freigegebene Conflict Policy anwenden oder einen Review Task erzeugen.

### Detection Proposal

Profiling oder AI-basierte Classification kann Metadaten vorschlagen. Sie sollte Proposal bleiben, bis das Approval Model eine automatische Annahme für dieses Attribut und diesen Confidence Threshold erlaubt.

Detection darf Approved Target Metadata nicht stillschweigend überschreiben.

### Resolved, Proposed und Unresolved States

Das Ergebnis sollte explizit sein:

- `Resolved metadata` — durch Rules und Authority gestützt;
- `Proposed metadata` — plausibel, aber noch nicht freigegeben;
- `Unresolved — review required` — Konflikt, fehlende Evidenz oder nicht unterstützte Transformation.

Ein Catalog, der ungelöste Zustände hinter einem sauberen Einzelwert verbirgt, verliert die für Governance notwendige Evidenz.

## Confidence, Gaps und widersprüchliche Evidenz abbilden

Lineage Completeness ist selten binär.

Ein Graph kann enthalten:

- vollständig beobachtete Production Paths;
- deklarierte, aber nicht beobachtete Paths;
- geparste Table Lineage ohne Column Mappings;
- manuelle Relationships vor Review;
- externe Transfers ohne technischen Connector;
- Dynamic Logic, die ein Parser nicht auflösen konnte;
- widersprüchliche Source- und Target-Classifications.

Diese Zustände sollten direkt repräsentiert werden.

### Confidence

Confidence sollte die Qualität des konkreten Claims beschreiben, nicht den allgemeinen Ruf der Source Platform.

Mögliche Faktoren:

- Evidence Type;
- Parser Support;
- Runtime Frequency;
- Environment Match;
- Code Revision Match;
- Freshness;
- Manual Approval;
- unresolved Dynamic Logic;
- Agreement zwischen mehreren Sources.

Ein präziser Score wie `0.93` sollte nur angezeigt werden, wenn das Scoring Model definiert und erklärbar ist. Kategorien wie `verified`, `high`, `medium`, `low` und `unknown` können ehrlicher sein.

### Coverage

Coverage sollte angeben, welche Ebene vollständig ist:

```text
system
dataset
table
column
expression
process
consumer
```

Ein Path kann auf Table Level vollständig und auf Column Level unvollständig sein.

### Gaps

Ein Gap sollte ein First-Class Object oder Status sein.

Festzuhalten sind:

- wo der Path endet;
- warum er endet;
- erwartete Source of Evidence;
- Responsible Owner;
- Business Impact;
- Review Date;
- Accepted Risk oder Remediation Plan.

### Conflicts

Widersprüchliche Lineage oder Metadata sollten bis zur Auflösung sichtbar bleiben.

Beispiele:

- Code deklariert eine Source, Runtime beobachtet eine andere;
- zwei Parser erzeugen unterschiedliche Column Mappings;
- ein Target Override reduziert Sensitivity ohne Anonymization Evidence;
- eine Curated Edge widerspricht dem deployed Model;
- ein Report Owner erklärt ein Feld für ungenutzt, während Usage Telemetry aktive Consumption zeigt.

Conflict Resolution benötigt Evidenz und Accountability, nicht stille Priorität nach Connector-Reihenfolge.

## Lineage für Impact Analysis nutzen

Impact Analysis ist Reverse Graph Traversal, ergänzt um Business Context.

Ausgangspunkt ist eine Proposed Change:

```text
Rename or Remove customer_status
```

Danach werden Downstream Dependencies traversiert.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/lineage-impact-and-metadata-propagation-img4-de.png"
        alt="Impact-Analysis-Workflow für Rename oder Removal von customer_status über Transformation Models, Tests, Data Products, Semantic Measures, Dashboards, Exports, AI Features und Policies bis zu Notification, Testing und Approval"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Technical Dependency identifiziert, was brechen kann. Business Criticality bestimmt Priorität, Freigabe, Kommunikation und Deployment Controls.
    </figcaption>
</figure>

Ein praktikabler Workflow ist:

```text
Detect Change
→ Traverse Dependents
→ Classify Impact
→ Notify Owners
→ Test
→ Approve or Block
→ Record Evidence
```

### Detect Change

Changes können entstehen durch:

- Schema Comparison;
- Code Pull Request;
- Contract Update;
- Semantic-Model Revision;
- Policy Change;
- Source-System Release;
- Deprecation Request.

Das Change Object sollte beschreiben, was sich wo, wann und warum verändert.

### Traverse Dependents

Der Lineage Graph wird traversiert über:

- Transformation Models;
- Tests;
- Data Products;
- Semantic Measures;
- Dashboards;
- Exports;
- APIs;
- AI Features;
- Policies;
- Documentation;
- Quality Rules.

Traversal sollte Environment und Version berücksichtigen. Development-only Dependencies dürfen nicht mit Production Impact vermischt werden.

### Technical Impact klassifizieren

Technical Impact umfasst:

- Compilation Failure;
- Missing Field;
- Changed Data Type;
- Changed Grain;
- Broken Join;
- Failed Quality Test;
- API Contract Violation;
- Model Feature Mismatch;
- Policy-Binding Failure.

### Business Criticality klassifizieren

Business Criticality umfasst:

- Regulatory Reporting;
- Executive KPI;
- Customer-Facing Service;
- Financial Close;
- Operational Decision;
- Automated Action;
- Model Risk;
- Contractual Export;
- Internal Convenience.

Eine technisch direkte Dependency kann geringe Criticality besitzen. Eine indirekte semantische Dependency kann hochkritisch sein.

### Owner benachrichtigen

Notifications sollten an die Owner der betroffenen Assets geroutet werden, nicht an eine generische Distribution List.

Jede Notification sollte enthalten:

- Proposed Change;
- affected Object;
- Relationship Path;
- Impact Classification;
- Required Action;
- Deadline;
- Evidence Link;
- Approval- oder Block-Status.

### Testen und entscheiden

Tests können umfassen:

- Compile- und Deployment Validation;
- Schema Compatibility;
- Data-Quality Regression;
- Semantic Measure Comparison;
- Report Rendering;
- API Contract Tests;
- AI Feature- und Evaluation Checks;
- Policy Enforcement Checks.

Die Entscheidung sollte lauten:

```text
approve
approve with migration
block
defer
accept documented risk
```

Evidenz und Approver sollten erhalten bleiben.

## Lineage bei Incidents verwenden

Lineage ist nach einem Fehler ebenso wertvoll.

Angenommen, ein Source-System Release ändert `customer_status` von:

```text
A / I / P
```

zu:

```text
ACTIVE / INACTIVE / PENDING
```

Die Pipeline lädt weiterhin, weil der Field Type Text bleibt. Der technische Job ist erfolgreich, aber das semantische Mapping erkennt die neuen Werte nicht.

Ein Lineage-fähiger Incident Workflow kann:

1. das erste veränderte Asset und den Zeitpunkt lokalisieren;
2. die Transformation identifizieren, die die alten Codes erwartet;
3. Downstream Data Products und Semantic Measures finden;
4. Dashboards, Exports und AI Features identifizieren, die betroffene Daten konsumiert haben;
5. das betroffene Time Window schätzen;
6. accountable Owner benachrichtigen;
7. betroffene Outputs pausieren oder kennzeichnen;
8. korrigierte Transformations erneut ausführen;
9. Recovery Evidence dokumentieren.

Ohne Lineage untersucht jedes Team sein eigenes System und rekonstruiert den Path manuell. Das verlängert Recovery Time und lässt den Scope unsicher.

Incident Lineage sollte zeitbezogen sein. Der aktuelle Graph kann sich von dem Graph unterscheiden, der den fehlerhaften Output erzeugt hat.

## Die einfachste tragfähige Implementierung

Eine nutzbare erste Implementierung benötigt keinen perfekten Enterprise Graph.

Mit einem kritischen Path und fünf Fähigkeiten beginnen.

### 1. Stable Asset Identities

Stable Identities erzeugen für:

- Systems;
- Datasets und Tables;
- Columns;
- Processes;
- Data Products;
- Semantic Objects;
- Reports und APIs;
- AI Datasets und Features.

Native Identifiers und Environment bleiben erhalten.

### 2. Typed Lineage Edges

Festhalten:

```text
reads
writes
derives
renames
joins
unions
aggregates
masks
hashes
publishes
consumes
```

Eine generische `related_to` Edge reicht für Propagation oder Impact Analysis nicht aus.

### 3. Evidence und Confidence

Evidence Type, Timestamp, Code Revision, Execution Reference, Coverage und Confidence speichern.

### 4. Kleines Set von Propagation Policies

Approved Rules implementieren für:

- Direct Projection;
- Rename;
- Join;
- Aggregation;
- Masking;
- Hashing;
- Custom Calculation.

Zuerst die wichtigsten Metadatenattribute abdecken:

- Sensitivity;
- Description;
- Owner;
- Retention;
- Quality Rule;
- Allowed Usage.

### 5. Reverse-Traversal-Workflow

Einen kontrollierten Change Workflow unterstützen:

```text
proposed source change
→ downstream traversal
→ owner notification
→ test evidence
→ approval
```

Damit entsteht operativer Nutzen, bevor jedes System angebunden ist.

## Alternative Implementierungsmuster

### Source-Native Lineage mit Central Index

Source Platforms behalten ihre detaillierte Lineage. Ein Central Index speichert normalisierte Identities, hochwertige Edges und References zur Source Evidence.

Geeignet, wenn:

- Source Lineage stark ist;
- detaillierte Graphs teuer zu kopieren sind;
- Freshness relevant ist;
- Central Discovery benötigt wird.

Risiko:

- Cross-System Traversal hängt von zuverlässiger Identity Resolution ab;
- Source APIs und Retention können Gaps erzeugen.

### Central Lineage Graph

Eine dedizierte Plattform speichert normalisierte Lineage über mehrere Systeme und unterstützt Traversal, Propagation und Workflow.

Geeignet, wenn:

- Cross-Platform Impact Analysis kritisch ist;
- mehrere Sources nur partielle Lineage liefern;
- ein Shared Control Plane benötigt wird.

Risiko:

- der Central Graph kann veralten;
- Source-spezifische Semantics können abgeflacht werden;
- Connector Ownership wird zu einer dauerhaften Betriebsverantwortung.

### Code-First Lineage

Transformation Manifests, SQL Parsing und Repository Metadata bilden die primäre Lineage Source.

Geeignet, wenn:

- Transformationen überwiegend code-definiert sind;
- Deployment kontrolliert ist;
- Runtime Movement begrenzt und beobachtbar ist.

Risiko:

- manuelle Processes, Runtime-generated SQL und BI Calculations bleiben unsichtbar.

### Runtime-First Lineage

Query History, Execution Events und Access Logs bilden die primäre Evidenz.

Geeignet, wenn:

- Workloads dynamisch sind;
- deployed Behaviour wichtiger ist als declared Intent;
- Runtime Instrumentation zuverlässig ist.

Risiko:

- ungenutzte, aber valide Dependencies können verschwinden;
- Retention Limits können Historie löschen;
- observed Execution erklärt nicht immer Business Semantics.

### Hybrid Evidence Graph

Declared, Observed und Curated Lineage existieren als getrennte Evidenztypen und werden abgeglichen.

Das ist normalerweise das stärkste Enterprise Pattern. Es ist zugleich operativ anspruchsvoller, weil Conflicts, Versions und Confidence explizit verwaltet werden müssen.

## Konkretes Beispiel: Customer Contact Key

Angenommen, es existieren drei Source Fields:

```text
crm.customer.email
crm.customer.phone
erp.debtor.customer_id
```

Ein Matching Model erzeugt:

```text
customer_contact_key =
SHA-256(
    lower(trim(email))
    || '|'
    || normalize_phone(phone)
    || '|'
    || customer_id
)
```

Der Lineage Graph erfasst:

- drei Source Columns;
- einen Transformation Process;
- Normalization Expressions;
- Concatenation;
- Hashing;
- eine Target Column;
- einen Downstream Matching Service;
- ein AI Feature, das den Key für Entity Resolution verwendet.

Eine naïve Propagation Engine könnte die PII Classification entfernen, weil der Output gehasht ist.

Ein governed Resolver bewertet:

```text
source classifications
+ concatenation rule
+ deterministic hash rule
+ target purpose
+ approved override
```

Er entscheidet:

```text
classification: confidential
PII category: pseudonymous identifier
owner: Customer Identity Data Product
allowed usage: entity matching only
retention: 30 days in temporary matching zone
description: deterministic composite key for approved identity matching
quality rule: non-null only when all required inputs are valid
```

Das Ergebnis behält Provenance zu den ursprünglichen Fields.

Später ändert sich die Phone-Normalization-Logic. Impact Analysis identifiziert:

- den Target Key;
- Duplicate-Detection Tests;
- den Matching Service;
- einen AI Feature Store;
- ein Customer-Merge-Dashboard;
- eine Policy, die Cross-Domain Matching einschränkt.

Die Änderung ist technisch klein, aber fachlich kritisch, weil sie Identity Resolution verändern kann. Der Graph unterstützt eine kontrollierte Entscheidung statt eines ungeprüften Deployments.

## Häufige Anti-Patterns

### Pfeile ohne Evidenz

Ein Graph zeigt Connections, aber nicht, ob sie observed, declared oder guessed sind.

Ergebnis: User können Trust nicht bewerten und Widersprüche nicht untersuchen.

### Alles nachgelagert propagieren

Jede Classification, jeder Owner, jede Description und jede Rule wird über jede Edge kopiert.

Ergebnis: Metadata wird intern widersprüchlich und semantisch falsch.

### Sensitivity nach Masking oder Hashing entfernen

Das System setzt unreadable mit anonymous gleich.

Ergebnis: linkable oder reversible Data wird zu niedrig klassifiziert.

### Lineage am Warehouse beenden

Reports, Semantic Measures, Exports und AI Features werden ausgeschlossen.

Ergebnis: Impact Analysis verfehlt die Assets, die Business Decisions am nächsten sind.

### Ein Owner für den gesamten Path

Source, Pipeline, Data Product, Semantic Model und Report erhalten einen geerbten Owner.

Ergebnis: Accountability wird ungenau und Eskalation scheitert.

### Current-State-Only Graph

Historical Edges werden überschrieben.

Ergebnis: Incidents und frühere Entscheidungen können nicht rekonstruiert werden.

### Gaps und Conflicts verbergen

Der Catalog zeigt eine saubere Antwort, obwohl Evidenz widersprüchlich ist.

Ergebnis: ungelöstes Risiko wird zu falscher Gewissheit.

### Technical Dependency mit Business Impact gleichsetzen

Jedes Downstream Object erhält dieselbe Priorität.

Ergebnis: Teams werden mit Noise überlastet, während kritische Dependencies nicht unterschieden werden.

## Entscheidungshilfe

Folgende Fragen helfen bei Scope und Architektur:

```text
Welche Entscheidungen soll Lineage unterstützen?
Welche Asset Levels werden benötigt?
Welche Evidence Types sind verfügbar?
Wo ist Column-Level-Semantik notwendig?
Welche Metadatenattribute dürfen propagieren?
Welche Transformationen benötigen Review?
Wie werden Conflicts und Gaps repräsentiert?
Wie lange muss Historical Lineage verfügbar bleiben?
Welche Owner müssen auf Impact Findings reagieren?
Welche Tests können Approval Evidence erzeugen?
```

Priorität erhalten Lineage Paths mit hohem Entscheidungsrisiko:

- regulated Data;
- Executive- und Financial-KPIs;
- Customer-Facing APIs;
- kritische Operational Processes;
- sensitive Data Products;
- AI Training- und Inference Features;
- Contractual Exports.

Nicht mit jeder Table im Enterprise beginnen. Mit den Paths beginnen, bei denen fehlerhafte Änderungen messbare Kosten oder Risiken erzeugen.

## Zentrale Empfehlungen

1. System-, Dataset-, Table-, Column-, Process-, KPI-, Report-, API- und AI-Lineage als verbundene, aber unterschiedliche Asset Levels modellieren.
2. Observed, Declared und Curated Lineage als getrennte Evidence Types erhalten.
3. Transformationssemantik auf der Lineage Edge oder am verknüpften Process speichern.
4. Propagation Rules je Transformation Type und Metadatenattribut definieren.
5. Sensitivity konservativ propagieren und Masking, Hashing sowie Aggregation neu bewerten.
6. Target Assets eigene Entscheidungen für Ownership, Retention und Approved Use geben.
7. Explizite Präzedenz für Target Overrides, Transformation Rules, Source Metadata und Detection Proposals verwenden.
8. Confidence, Coverage, Gaps und unresolved Conflicts direkt repräsentieren.
9. Technical Dependency und Business Criticality in der Impact Analysis trennen.
10. Lineage versionieren und die für Approvals, Incidents und Change Decisions verwendete Evidenz erhalten.

## Von verbundenen Metadaten zu durchsetzbarer Governance

Lineage und Propagation erklären, wo Daten herkommen, wie sie verändert wurden und welche Downstream Assets davon abhängen.

Der nächste Schritt nutzt diesen Kontext als Control Metadata.

Policies, Classifications, Approvals, Retention Obligations, Access Conditions und Usage Restrictions dürfen keine passiven Labels in einem Catalog bleiben. Sie müssen Plattformen, Workflows und Entscheidungen beeinflussen.

Teil 11 wechselt deshalb von beschreibenden Metadaten zu **Governance-Metadaten, die Daten tatsächlich steuern**.
