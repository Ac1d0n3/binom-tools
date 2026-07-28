---
title: Metadatenqualität messen und verbessern — Metadaten wie Daten mit Erwartungen, Scores und Verbesserungsprozessen behandeln
description: Ein praxisnahes Betriebsmodell, um Vollständigkeit, Korrektheit, Konsistenz, Aktualität, Klarheit, Provenance, Coverage, Beziehungsintegrität und operative Nutzbarkeit von Metadaten zu messen, verantwortliche Verbesserungen zuzuweisen und Fortschritt über die Zeit zu verfolgen.
category: Data Governance
tags:
  - metadata
  - metadata-quality
  - metadata-governance
  - data-catalog
  - data-quality
  - metadata-scoring
  - data-stewardship
  - metadata-provenance
  - metadata-freshness
  - business-glossary
  - data-lineage
  - data-products
  - kpi-governance
  - ai-ready-metadata
  - continuous-improvement
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
seriesPart: 12
seriesTitle: MetaData Deep Dive
hero: images/playbooks/measure-and-improve-metadata-quality-hero.png
publishedAt: 2026-07-01 10:00
---

## Metadaten können vollständig wirken und trotzdem unzuverlässig sein

Ein Catalog kann Tausende Assets, Beschreibungen, Owner, Klassifikationen und Lineage-Verbindungen enthalten.

Das bedeutet nicht, dass die Metadaten vertrauenswürdig sind.

Eine Tabelle kann einen Owner besitzen, obwohl die Person das Unternehmen vor sechs Monaten verlassen hat. Ein KPI kann ausführlich definiert sein, während in einer anderen Domain eine zweite scheinbar freigegebene Definition existiert. Ein Dataset kann als `confidential` klassifiziert sein, obwohl das Review nach einer wesentlichen Schemaänderung abgelaufen ist. Ein Dashboard kann auf einen Glossary Term verweisen, der nicht mehr existiert. Eine Feldbeschreibung kann sprachlich vollständig sein und trotzdem Unit, Grain, Ausschlüsse oder vorgesehenen Einsatz verschweigen.

Der Catalog meldet, dass die Felder befüllt sind.

Der User kann trotzdem keine sichere Entscheidung treffen.

Das ist das zentrale Problem der Metadatenqualität: **Vorhandensein ist nur eine Qualitätsdimension**.

Completeness ist wichtig, beweist aber keine Correctness. Correctness beweist keine Freshness. Aktuelle Metadaten können weiterhin mehrdeutig sein. Eine klare Definition kann unbekannte Provenance besitzen. Eine technisch gültige Beziehung kann für den operativen Workflow unbrauchbar sein.

> **Metadatenqualität muss als Set expliziter und prüfbarer Erwartungen gemessen werden. Ein nutzbares Qualitätsmodell zeigt, welche Dimension fehlgeschlagen ist, warum das relevant ist, wer korrigieren muss und welche Evidenz für den Abschluss erforderlich ist.**

Damit wird Metadatenmanagement von gelegentlicher Dokumentationsarbeit zu einer operativen Disziplin.

Das Ziel ist nicht, einen perfekten Enterprise Score zu erzeugen.

Das Ziel ist, konkrete Schwächen früh genug sichtbar zu machen, damit sie korrigiert werden können, bevor sie falsche Analysen, gescheiterte Audits, unsichere Automation oder irreführende AI Answers verursachen.

## Metadaten wie Daten mit einem Quality Contract behandeln

Metadaten sollten mit vielen Prinzipien betrieben werden, die auch für governed Datasets gelten:

- definiertes Schema;
- kontrollierte Werte;
- accountable Ownership;
- Validation Rules;
- Freshness Expectations;
- Lineage und Provenance;
- Versionierung;
- Issue Management;
- Change Evidence;
- messbare Service Levels.

Der Unterschied besteht darin, dass Metadaten andere Assets beschreiben und steuern. Ein Metadatendefekt kann deshalb über einen einzelnen Datensatz hinauswirken.

Eine falsche Classification kann den falschen Schutz aktivieren. Ein veralteter Owner kann eine Eskalation verhindern. Eine defekte Lineage Edge kann Impact verstecken. Eine widersprüchliche KPI-Definition kann zwei Executive Numbers mit demselben Label erzeugen. Eine unklare Beschreibung kann einen User oder AI Assistant zum falschen Feld führen.

Der Quality Contract sollte fünf Fragen beantworten:

```text
Was wird erwartet?
Für welchen Asset-Typ?
Bei welcher Kritikalität?
Wer ist accountable?
Was passiert bei einem Fehler?
```

Ein minimaler Contract kann deklarativ beschrieben werden:

```yaml
profile: governed_data_product_critical
applies_to:
  asset_type: data_product
  criticality: critical

expectations:
  description:
    required: true
    approved: true
    max_age_days: 365
  owner:
    required: true
    reference_type: accountable_role
    active_reference_required: true
  lineage:
    upstream_required: true
    downstream_required: true
    broken_edges_allowed: 0
  classification:
    required: true
    controlled_vocabulary: sensitivity_v3
    review_max_age_days: 180
  usage_restrictions:
    required_when:
      sensitivity:
        - confidential
        - restricted

failure_policy:
  critical:
    action: block_publication
  high:
    action: create_priority_issue
  medium:
    action: create_standard_issue
```

Der Contract ist nicht das Quality Result.

Er definiert, welche Checks anwendbar sind. Das Ergebnis hält fest, ob diese Checks bestanden, fehlgeschlagen, ausgenommen oder nicht auswertbar waren.

## Mehrere Qualitätsdimensionen getrennt messen

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/measure-and-improve-metadata-quality-img1-de.png"
        alt="Acht Qualitätsdimensionen umgeben ein Metadatenprofil: Vollständigkeit, Korrektheit, Konsistenz, Aktualität, Klarheit, Provenance, Beziehungsintegrität und operative Nutzbarkeit, jeweils mit einem konkreten Fehlerbeispiel"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein Metadatenprofil kann in einer Dimension stark und in einer anderen schwach sein. Getrennte Dimensionen erhalten die diagnostische Aussage, die ein einzelner vermischter Score verstecken würde.
    </figcaption>
</figure>

Ein praktisches Modell sollte mindestens die folgenden Dimensionen unterscheiden.

### Vollständigkeit

Vollständigkeit fragt, ob erforderliche Metadaten vorhanden sind.

Beispiele:

- Owner fehlt;
- Beschreibung ist leer;
- Criticality ist nicht zugewiesen;
- Classification fehlt;
- Review Date fehlt;
- ein KPI besitzt keine Formel;
- ein Data Product besitzt keine Usage Restrictions.

Eine einfache Berechnung lautet:

```text
Vollständigkeit =
bestandene Required-Field-Checks
/
anwendbare Required-Field-Checks
```

Applicability ist entscheidend. Source Table, Dashboard und AI Training Dataset dürfen keine universelle Mandatory-Field-Liste teilen.

Ein Feld kann vollständig und falsch sein. Completeness darf deshalb niemals als gesamtes Quality Result behandelt werden.

### Korrektheit

Korrektheit fragt, ob Metadaten das reale Asset und seine freigegebene Bedeutung richtig repräsentieren.

Beispiele:

- eine Beschreibung nennt Bruttoumsatz, obwohl die Berechnung Nettoumsatz liefert;
- die Owner Reference zeigt auf die falsche Domain;
- die Classification lautet `public`, obwohl Personal Data enthalten ist;
- die Refresh Frequency lautet stündlich, obwohl die Pipeline täglich läuft;
- die KPI-Formel ignoriert Retouren, obwohl die freigegebene Definition sie verlangt.

Correctness ist schwerer zu automatisieren, weil häufig ein Vergleich mit autoritativer Evidenz erforderlich ist.

Nutzbare Evidenzquellen sind:

- Source Schema und Constraints;
- Transformation Code;
- Semantic-Model-Expressions;
- freigegebene Glossary Definitions;
- beobachtetes Runtime Behaviour;
- Steward Review;
- Reconciliation Tests;
- Policy Decisions.

Ein Wert sollte nicht als korrekt gelten, nur weil er das erwartete Format besitzt.

### Konsistenz

Konsistenz fragt, ob zusammengehörige Metadatenwerte miteinander und mit kontrollierten Standards übereinstimmen.

Beispiele:

- dasselbe Asset ist in einem System `internal` und in einem anderen `confidential`;
- zwei freigegebene Definitionen verwenden denselben KPI-Namen;
- ein Data Product gehört im Catalog zu Finance und im Contract zu Sales;
- ein Feld ist deprecated, während ein Dashboard es als certified darstellt;
- ein kontrollierter Term wird über mehrere unkontrollierte Schreibweisen referenziert.

Consistency Checks können vergleichen:

- Source- und Central Values;
- Parent- und Child-Assets;
- lokale und Enterprise Definitions;
- Environments;
- Versions;
- Classifications und Protection Policies;
- Criticality und Required Controls.

Konsistenz bedeutet nicht, legitime Domain-Unterschiede in einen universellen Wert zu zwingen. Ein Konflikt muss zunächst als Fehler, akzeptierte lokale Variante oder ungelöste Governance Decision klassifiziert werden.

### Aktualität

Aktualität fragt, ob Metadaten für ihren Zweck aktuell genug sind.

Beispiele:

- das Approval Review Date ist abgelaufen;
- die Quelle wurde vor 14 Tagen geharvestet, obwohl sich das Schema täglich ändert;
- die Owner Assignment wurde nach einer Organisationsänderung nicht verifiziert;
- Lineage repräsentiert eine ältere Model Version;
- eine Dashboard Usage Metric ist zu alt für eine Deprecation Decision.

Freshness sollte gegen eine explizite Erwartung gemessen werden:

```text
freshness_status =
current_time - last_verified_at
im Vergleich zu
allowed_age_for_attribute_and_asset
```

Nicht jedes Attribute benötigt denselben Refresh Cycle.

Technical Schema Metadata kann near-real-time oder täglich geharvestet werden müssen. Business Definitions können ein Jahr gültig bleiben und trotzdem nach einer wesentlichen Änderung ein sofortiges Review benötigen. Usage Statistics können ein Rolling Window benötigen. Approvals können rechtlich oder operativ definierte Expiry Dates besitzen.

### Eindeutigkeit

Eindeutigkeit fragt, ob ein governed Concept durch die beabsichtigte Anzahl autoritativer Records repräsentiert wird.

Beispiele:

- zwei aktive Glossary Terms beanspruchen, die Enterprise Definition von `Net Revenue` zu sein;
- ein Dashboard wird unter unterschiedlichen Identifiern mehrfach geharvestet;
- eine Owner Role existiert gleichzeitig als Free Text und als governed Reference;
- ein Data Product besitzt mehrere aktive Certification Records.

Uniqueness darf gültige Versions, Environments, Aliases oder lokale Concepts nicht entfernen.

Der Check benötigt den richtigen Identity Key:

```text
Business-Concept-Identity
Technical-Asset-Identity
Environment-Identity
Version-Identity
Local-Synonym-Identity
```

Ein Duplicate ist nicht einfach ein ähnlicher Name. Es ist ein unbeabsichtigter konkurrierender Record für dieselbe governed Identity und denselben Scope.

### Klarheit

Klarheit fragt, ob Menschen und Maschinen die Metadaten ohne Raten interpretieren können.

Beispiele:

- `Sales amount` wird als `the sales amount` beschrieben;
- eine KPI-Definition verschweigt Time Basis, Currency, Grain oder Ausschlüsse;
- ein Owner-Feld enthält eine unerklärte Team-Abkürzung;
- ein Statuswert besitzt keine Lifecycle-Bedeutung;
- eine AI-Usage-Notiz lautet `restricted`, ohne verbotene Nutzungen zu nennen.

Clarity kann über strukturierte Anforderungen bewertet werden.

Eine Beschreibung eines Financial Measure kann verlangen:

- Business Meaning;
- Calculation;
- Grain;
- Time Basis;
- Currency oder Unit;
- Inclusions;
- Exclusions;
- Behandlung von Cancellations und Corrections;
- vorgesehene Decisions;
- bekannte Limitations;
- Example.

Automatisierte Language Checks können fehlende Struktur, zirkuläre Definitionen und Placeholder Text erkennen. Für semantische Präzision bleibt Human Review erforderlich.

### Provenance

Provenance fragt, woher ein Metadatenwert stammt, wie er erzeugt wurde und welche Authority ihn stützt.

Beispiele:

- die Quelle einer Classification ist unbekannt;
- eine AI-generierte Definition besitzt keine Model-, Prompt- oder Evidence Reference;
- ein Manual Override ersetzt einen Source Value ohne Begründung;
- ein propagierter Tag zeichnet seinen Upstream Origin nicht auf;
- ein Approval Value besitzt keinen Decision Record.

Ein nutzbarer Metadatenwert sollte beantworten können:

```text
Source System
Source Object
Collection- oder Decision Method
Observed oder Inferred
Creator oder Producer
Timestamp
Version
Confidence
Approval State
Evidence Reference
```

Unbekannte Provenance macht einen Wert nicht automatisch falsch. Sie macht ihn schwerer vertrauenswürdig, verifizierbar und korrigierbar.

### Coverage

Coverage fragt, wie viel der vorgesehenen Metadatenlandschaft tatsächlich repräsentiert und bewertet wird.

Beispiele:

- 95 Prozent der Warehouse Tables werden geharvestet, aber nur 20 Prozent der Semantic Measures;
- alle Tier-1-Data-Products werden bewertet, kritische Dashboards jedoch nicht;
- Column Lineage existiert für eine Plattform und endet vor der BI Layer;
- Definitionen decken aktive Assets ab, ignorieren aber AI Training Datasets.

Coverage unterscheidet sich von Completeness.

Completeness bewertet, ob ein bekanntes Asset seine erforderlichen Attribute besitzt. Coverage bewertet, ob relevante Assets, Beziehungen und Plattformen überhaupt im Quality Scope enthalten sind.

Nutzbare Coverage Measures sind:

```text
inventarisierte Assets / erwartete Assets
bewertete Assets / inventarisierte Assets
bewertete kritische Assets / erwartete kritische Assets
beobachtete Beziehungen / erwartete Beziehungen
verbundene Plattformen / Plattformen im Scope
```

### Beziehungsintegrität

Beziehungsintegrität fragt, ob Links zwischen Metadata Objects gültig, aktuell und semantisch angemessen sind.

Beispiele:

- ein Glossary Link zeigt auf einen gelöschten Term;
- Lineage referenziert eine entfernte Column;
- ein Dashboard zeigt auf ein superseded Semantic Model;
- ein Data Product besitzt eine Owner Reference, die nicht mehr aufgelöst wird;
- ein KPI verlinkt das falsche Calculation Asset;
- eine Exception referenziert eine nicht mehr gültige Policy Version.

Relationship Checks sollten validieren:

- beide Endpoints existieren;
- der Relationship Type ist erlaubt;
- Scope und Environment passen;
- Version Rules werden eingehalten;
- Cardinality ist gültig;
- die Beziehung ist nicht abgelaufen;
- Evidenz stützt inferred Links.

Ein Graph mit vielen Edges kann trotzdem schlechte Integrity besitzen.

### Operative Nutzbarkeit

Operative Nutzbarkeit fragt, ob Metadaten die vorgesehene Aktion tatsächlich unterstützen können.

Beispiele:

- eine Classification existiert, kann aber nicht auf eine Protection Policy aufgelöst werden;
- ein Owner Name ist vorhanden, kann aber keiner verantwortlichen Queue zugeordnet werden;
- ein Retention Label besitzt kein ausführbares Policy Mapping;
- eine Definition ist lesbar, kann aber nicht über die von einer AI Application genutzte Schnittstelle abgerufen werden;
- ein Quality Tier existiert, aktiviert jedoch keine Checks;
- eine Usage Restriction ist Free Text, den kein Control auswerten kann.

Operational Usability ist der stärkste Test für Metadata Maturity.

Die Frage lautet nicht nur:

```text
Sind Metadaten vorhanden?
```

Sondern:

```text
Kann der vorgesehene Consumer sie zuverlässig nutzen?
```

Consumer können Menschen, Search, Workflows, Deployment Pipelines, Policy Engines, Observability Systems, BI Tools, RAG Systems und AI Assistants sein.

## Mit der einfachsten tragfähigen Implementierung beginnen

Eine Metadata-Quality-Initiative benötigt am ersten Tag keine Enterprise-weite Scoring Engine.

Die einfachste tragfähige Implementierung kann fünf Schritte verwenden.

### 1. Eine Asset-Klasse auswählen

Beginne mit einem Asset-Typ, der klaren Business Value und accountable Owner besitzt.

Geeignete Startpunkte sind:

- governed Data Products;
- kritische KPIs;
- certified Dashboards;
- sensitive Datasets;
- AI Training Datasets.

Nicht mit jedem geharvesteten technischen Object beginnen. Ein enger Scope macht Expectations und Remediation beherrschbar.

### 2. Zehn bis zwanzig explizite Checks definieren

Checks sollten verständlich und umsetzbar sein.

Beispiele:

```text
Owner Reference existiert
Owner Reference kann aufgelöst werden
Description ist kein Placeholder Text
Definition enthält Grain
Classification nutzt Approved Vocabulary
Classification Review ist aktuell
Upstream Lineage existiert
Glossary Links können aufgelöst werden
Approval ist gültig
Usage Restrictions existieren, wenn erforderlich
```

Jeder Check sollte festhalten:

- Check Identifier;
- anwendbares Asset Profile;
- Dimension;
- Severity;
- Evaluation Method;
- Owner der Rule;
- Remediation Route;
- Evidence Requirement.

### 3. Detaillierte Ergebnisse bewerten und speichern

Pro Check sollte ein Ergebnis gespeichert werden, nicht nur ein Final Score.

Beispiel:

```yaml
asset: data_product.customer_contact
profile: governed_data_product_critical
evaluated_at: 2026-07-25T12:00:00Z

checks:
  - id: owner_reference_resolves
    dimension: completeness
    status: passed
    evidence: identity-role/customer-data-owner
  - id: classification_review_current
    dimension: freshness
    status: failed
    observed: 224_days
    expected_max: 180_days
  - id: glossary_relationship_valid
    dimension: relationship_integrity
    status: failed
    relationship: glossary/customer-contact
    reason: target_not_found
```

Dieses Ergebnis ist diagnostizierbar. Ein Score von `82` ist es nicht.

### 4. Issues an accountable Owner routen

Ein fehlgeschlagener Check sollte einen Task erzeugen mit:

- Asset;
- failing Rule;
- Business Impact;
- Severity;
- Responsible Source;
- accountable Owner;
- Correction Location;
- Due Date oder Service Target;
- Evidence für Closure;
- Exception Path.

Die Responsible Source ist wichtig.

Eine falsche dbt-Beschreibung sollte normalerweise im Code korrigiert werden. Eine veraltete Identity Reference sollte im autoritativen Ownership System korrigiert werden. Ein Glossary Conflict sollte im governed Vocabulary Process gelöst werden. Der Central Catalog sollte nicht jeden Defect stillschweigend lokal patchen.

### 5. Nach der Korrektur erneut bewerten

Closure benötigt neue Evidenz.

Ein Task ist nicht abgeschlossen, nur weil jemand ein Feld geändert hat. Die betroffenen Metadaten müssen re-harvested oder erneut gelesen werden, die Rule muss bestehen und das Result muss auf die korrigierte Version verweisen.

## Metadaten nach Asset-Typ und Kritikalität bewerten

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/measure-and-improve-metadata-quality-img2-de.png"
        alt="Eine Scoring-Matrix vergleicht Quelltabellen, governed Data Products, KPIs, Dashboards und AI Training Datasets über Beschreibung, Owner, Lineage, Klassifikation, Aktualität, Freigabe und Nutzungsbeschränkungen; Gewichte ändern sich für niedrige, wichtige und kritische Assets"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Quality Expectations hängen von Asset-Typ und Kritikalität ab. Eine universelle Mandatory-Field-Liste erzeugt bei manchen Assets falsche Fehler und bei anderen gefährliche Lücken.
    </figcaption>
</figure>

Unterschiedliche Assets unterstützen unterschiedliche Decisions.

Eine Source Table benötigt primär technische Identity, Schema, Source Ownership, Freshness und operativen Upstream Context. Ein governed Data Product benötigt Business Definition, Accountability, Lineage, Quality Expectations, Classification, Approval und Supported Use. Ein KPI benötigt Formula, Grain, Filters, Time Basis, Owner und Authoritative Implementation. Ein Dashboard benötigt Consumer Context, Certified-Source-Links, Owner, Usage und Lifecycle State. Ein AI Training Dataset benötigt Lineage, Permitted Use, Provenance, Representativeness Context, Restrictions, Approval und Version Evidence.

Ein nutzbares Profile Matrix kann so aussehen:

| Check | Source Table | Governed Data Product | KPI | Dashboard | AI Training Dataset |
|---|---:|---:|---:|---:|---:|
| Description | erwartet | verpflichtend | verpflichtend | verpflichtend | verpflichtend |
| Owner | technisch | accountable + technisch | fachlich | Product/Report | accountable + technisch |
| Lineage | Upstream | End-to-End | Formula + Source | Semantic + Source | Source + Preparation |
| Classification | bei Sensitivity | verpflichtend | inherited + reviewed | inherited | verpflichtend |
| Freshness | Harvest und Data | Metadata und Data | Review | Usage und Content | Version und Source |
| Approval | optional | verpflichtend | verpflichtend | Certification | verpflichtend |
| Usage Restrictions | falls relevant | verpflichtend | falls relevant | Sharing/Export | verpflichtend |

Criticality verändert Gewicht und Konsequenz eines Fehlers.

### Niedrige Kritikalität

Ein exploratives Asset mit niedriger Criticality kann erlauben:

- Draft Description;
- Team Ownership statt benannter accountable Role;
- partielle Lineage;
- Warning statt Publication Block;
- längeres Review Interval.

### Wichtig

Ein wichtiges Asset kann verlangen:

- approved Description;
- accountable Owner;
- validierte Upstream Lineage;
- aktuelle Classification;
- Issue SLA;
- sichtbaren Quality Status.

### Kritisch

Ein kritisches Asset kann verlangen:

- approved Definition und Owner;
- vollständige Required Lineage;
- aktuelle Classification und Approval;
- null unresolved Mandatory Relationship Failures;
- explizite Usage Restrictions;
- Blocking Policy für ausgewählte Defects;
- Closure Evidence;
- kontrollierte Exceptions mit Expiry.

Criticality sollte sowohl Score als auch Action beeinflussen.

Ein fehlender Owner auf einer Sandbox Table kann einen Low-Priority Task erzeugen. Derselbe Fehler bei einem Regulatory Report oder AI Training Dataset kann Publication blockieren.

## Scores verwenden, ohne die Diagnose zu verstecken

Scores sind für Vergleich und Trend Analysis nützlich, wenn sie zerlegbar bleiben.

Ein Dimension Score kann berechnet werden als:

```text
Dimension Score =
Summe(Check Result × Check Weight)
/
Summe(anwendbare Check Weights)
```

Dabei kann Check Result bedeuten:

```text
passed = 1.0
warning = 0.5
failed = 0.0
not_applicable = ausgeschlossen
not_evaluated = separater Status
excepted = separat ausgewiesen
```

`not evaluated` darf nicht als bestanden gelten.

Exceptions dürfen nicht im normalen Score versteckt werden.

Ein Profile kann zeigen:

```yaml
quality:
  completeness: 100
  correctness: 72
  consistency: 88
  freshness: 45
  clarity: 92
  provenance: 60
  relationship_integrity: 80
  operational_usability: 50

status:
  mandatory_failures: 2
  warnings: 3
  exceptions: 1
  not_evaluated: 4
```

Das ist nützlicher als:

```text
Enterprise Metadata Quality Score: 76
```

Ein Blended Score kann als Navigation Aid angezeigt werden, darf aber Dimension Profile und Failed-Rule-Liste niemals ersetzen.

Mandatory Failures müssen sichtbar bleiben, selbst wenn der Weighted Average akzeptabel aussieht.

## Konkrete Metadatendefekte messen

### Mandatory Fields

Mandatory-Field-Checks sollten mehr als Non-Null Values validieren.

Ein Owner-Feld kann fehlschlagen, weil:

- es leer ist;
- es Placeholder Text enthält;
- die Reference nicht aufgelöst wird;
- die Role inaktiv ist;
- der referenzierte Owner nicht für den Asset Scope accountable ist;
- der Wert nur an einer kopierten Stelle vorhanden ist;
- die Assignment abgelaufen ist.

Eine Description kann fehlschlagen, weil:

- sie leer ist;
- sie den Technical Name wiederholt;
- sie die erforderliche Struktur nicht erfüllt;
- sie nicht approved ist;
- sie nach einer wesentlichen Änderung stale ist;
- ihre Language nicht zur governed Version passt.

### Defekte Beziehungen

Relationship Checks sollten erkennen:

- fehlende Targets;
- gelöschte Targets;
- ungültige Relationship Types;
- Cross-Environment-Links;
- Circular Ownership;
- Lineage zu obsolete Versions;
- mehrere aktive authoritative Links;
- abgelaufene Policy References;
- orphaned Reports;
- unlinked Semantic Measures.

Jeder Fehler sollte beide Endpoints und die Source benennen, die die Beziehung asserted hat.

### Veraltete Freigaben

Ein Approval kann stale sein, weil:

- das Review Date abgelaufen ist;
- sich das governed Asset wesentlich verändert hat;
- die freigebende Policy Version ersetzt wurde;
- der Approver nicht mehr die erforderliche Authority besitzt;
- sich die Source Evidence geändert hat;
- eine abhängige Classification geändert wurde;
- die unterstützende Exception abgelaufen ist.

Freshness sollte deshalb Event-aware sein.

Ein Review Interval von einem Jahr reicht nicht aus, wenn eine Schemaänderung nach zwei Wochen ein neues Personal-Data-Field einführt.

### Widersprüchliche Definitionen

Definition Conflicts sollten durch mehr als Text Similarity erkannt werden.

Ein Conflict kann umfassen:

- gleicher governed Name, unterschiedliche Formula;
- gleiche Formula, unterschiedliche Inclusion Rules;
- gleiche Description, unterschiedliche Time Basis;
- lokaler Term wird fälschlich als Enterprise Term dargestellt;
- zwei aktive authoritative Records;
- approved Definition und deployed Semantic Expression weichen ab.

Conflict Resolution sollte legitimen Local Context erhalten.

Mögliche Outcomes sind:

```text
eine Definition korrigiert
ein Record deprecated
lokale Variante beibehalten
Synonym erzeugt
Scope präzisiert
Mapping approved
Conflict bleibt unresolved
```

Unresolved Conflicts müssen sichtbar und geroutet bleiben. Sie dürfen nicht in einem Quality Score gemittelt und vergessen werden.

## Confidence und Evidenz für erkannte oder abgeleitete Metadaten verwenden

Nicht alle Metadaten werden von einer autoritativen Person oder einem System deklariert.

Classifications können detected sein. Owner können aus Repository History vorgeschlagen werden. Glossary Links können inferred sein. Descriptions können generiert werden. Lineage kann aus SQL geparst oder zur Runtime beobachtet werden.

Diese Werte können vor Final Approval nützlich sein, wenn ihr Status explizit bleibt.

Ein Proposed Value sollte festhalten:

```yaml
value: confidential
status: proposed
method: pattern_detection
confidence: 0.87
source:
  system: warehouse_scanner
  object: crm.customer.email
evidence:
  - detected_email_pattern
  - column_name_similarity
model_or_rule_version: classifier-4.2
generated_at: 2026-07-25T10:15:00Z
review_owner: role:data-privacy-steward
```

Confidence ist nicht Correctness.

Ein Wert mit `0.98` Confidence kann falsch sein. Ein Wert mit `0.65` Confidence kann trotzdem als Review Priority nützlich sein.

Confidence sollte beeinflussen:

- Review-Reihenfolge;
- Automation Threshold;
- ob der Proposal angezeigt werden darf;
- ob er propagiert werden darf;
- ob Human Approval verpflichtend ist.

Control-Driving Metadata sollte unabhängig von Detector Confidence eine Approval Policy verlangen.

Evidenz muss erhalten bleiben, damit Reviewer verstehen, warum der Vorschlag erzeugt wurde, und damit abgelehnte Proposals zukünftiges Matching verbessern können, ohne Auditability zu verlieren.

## Einen kontrollierten Metadata-Quality-Issue-Workflow betreiben

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/measure-and-improve-metadata-quality-img3-de.png"
        alt="Metadatenqualitätsprobleme laufen von Erkennung über Schweregradklassifikation, Identifikation der verantwortlichen Quelle, Owner-Zuweisung, Korrektur an der Quelle, erneute Erfassung, Validierung und Abschluss mit Evidenz; dokumentierte Ausnahmen benötigen Owner und Ablaufdatum"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Metadatendefekte sollten an der verantwortlichen Quelle korrigiert, erneut erfasst und validiert werden. Closure benötigt Evidenz; Exceptions bleiben scoped, owned und zeitlich begrenzt.
    </figcaption>
</figure>

Ein vollständiger Workflow lautet:

```text
Issue erkennen
→ Severity klassifizieren
→ Responsible Source identifizieren
→ Owner zuweisen
→ an der Quelle korrigieren
→ erneut erfassen
→ validieren
→ mit Evidenz schließen
```

### Issue erkennen

Detection kann entstehen durch:

- Automated Rule;
- fehlgeschlagene Relationship Validation;
- Schema Change;
- User Feedback;
- Failed Search;
- Audit Finding;
- AI Answer Failure;
- Incident Analysis;
- Steward Review.

### Severity klassifizieren

Severity sollte berücksichtigen:

- Asset Criticality;
- betroffene Consumer;
- regulatorischen oder vertraglichen Impact;
- Control-Driving Use;
- Wahrscheinlichkeit falscher Decisions;
- Anzahl Downstream Assets;
- Dauer;
- Verfügbarkeit eines sicheren Workarounds.

Eine fehlende Description und eine falsche Permitted-Use-Restriction sind nicht gleichwertig.

### Responsible Source identifizieren

Der Issue Record sollte benennen, wo die Korrektur hingehört:

```text
Database Catalog
Transformation Repository
Semantic Model
Business Glossary
Identity System
Policy Registry
Central Metadata Platform
Observability System
```

Der Central Quality Service kann das Problem erkennen, ohne zum Authoring System zu werden.

### Accountable Owner zuweisen

Assignment kann nutzen:

- Source-System-Owner;
- Data Product Owner;
- Data Steward;
- KPI Owner;
- Dashboard Owner;
- Policy Owner;
- Technical Owner;
- Governance Queue.

Der Assignee korrigiert oder koordiniert die Korrektur. Accountability bleibt beim governed Owner.

### An der Quelle korrigieren

Corrections sollten normalerweise dort erfolgen, wo der Wert autoritativ ist.

Local Correction verhindert, dass der nächste Harvest einen Central Patch überschreibt.

### Erneut erfassen und validieren

Die korrigierte Version muss erneut eingesammelt werden. Validation bestätigt:

- die erwartete Source wurde geändert;
- der Wert besteht jetzt;
- zugehörige Conflicts wurden gelöst;
- kein neuer Defect wurde eingeführt;
- die richtige Version ist für Consumer sichtbar.

### Mit Evidenz schließen

Closure Evidence kann enthalten:

- Source Commit;
- Approval Record;
- erfolgreichen Check Result;
- aufgelöste Relationship;
- neuen Harvest Timestamp;
- Policy Mapping;
- Steward Decision;
- Screenshot oder Run Reference, falls erforderlich.

Das Issue sollte die Zeit von Detection bis Assignment, Correction, Validation und Closure festhalten.

## Dokumentierte Ausnahmen erlauben, ohne Fehler zu normalisieren

Einige Metadatendefekte können nicht sofort korrigiert werden.

Ein Source System unterstützt möglicherweise ein Required Field nicht. Eine Fusion kann vorübergehend doppelte Definitionen hinterlassen. Ein Legacy Dashboard muss verfügbar bleiben, während der Ersatz validiert wird. Ein Policy Review kann von einer externen Decision abhängen.

Eine Exception sollte enthalten:

```yaml
exception_id: MQE-2026-014
asset: dashboard.regulatory_legacy
failed_rule: authoritative_kpi_link_required
reason: replacement_dashboard_in_validation
owner: role:finance-reporting-owner
approved_by: role:data-governance-chair
effective_from: 2026-07-01
expires_at: 2026-09-30
compensating_control: monthly_manual_reconciliation
review_frequency: monthly
```

Eine Exception ist kein Pass.

Sie sollte separat ausgewiesen, in Aging Views aufgenommen und bei Expiry erneut bewertet werden. Eine Exception ohne Owner, Scope, Reason und Expiry ist lediglich ein unmanaged Defect.

## Konkretes Beispiel: Das Metadatenprofil eines kritischen KPI verbessern

Angenommen, die Organisation veröffentlicht einen kritischen KPI namens `Net Revenue`.

Seine technische Implementierung ist ein Semantic Measure. Er wird in Executive Dashboards, Planning Exports und einem AI Assistant verwendet, der Management Questions beantwortet.

Das initiale Metadatenprofil enthält:

```yaml
name: Net Revenue
description: Revenue after deductions
owner: Finance BI
formula: SUM(invoice_amount - discount_amount)
classification: internal
approval_status: approved
reviewed_at: 2025-10-01
```

Das Profile wirkt vollständig.

Eine detaillierte Quality Evaluation findet:

1. **Correctness Failure**  
   Die freigegebene Finance Definition zieht zusätzlich Returns und Credit Notes ab. Die deployed Formula tut das nicht.

2. **Clarity Failure**  
   Die Definition nennt weder Currency Conversion, Posting Date, Ausschluss stornierter Invoices noch Behandlung später Corrections.

3. **Freshness Failure**  
   Das Review ist älter als die 180-Tage-Anforderung für Critical KPIs.

4. **Uniqueness Failure**  
   Sales Operations besitzt eine weitere aktive `Net Revenue` Definition auf Basis des Order Date.

5. **Provenance Failure**  
   Der Approval Record enthält weder Policy Version noch Evidence Reference.

6. **Relationship-Integrity Failure**  
   Ein Executive Dashboard zeigt auf ein deprecated Semantic Measure mit demselben Display Label.

7. **Operational-Usability Failure**  
   Der AI Assistant ruft beide Definitionen ohne Scope Rule ab und kann sie vermischen.

Das Dimension Profile lautet:

```yaml
completeness: 100
correctness: 40
consistency: 35
freshness: 0
uniqueness: 50
clarity: 45
provenance: 25
relationship_integrity: 60
operational_usability: 20
mandatory_failures: 4
```

Ein einzelner Completeness Score hätte Erfolg gemeldet.

Der Remediation Workflow weist zu:

- Formula Correction an den Semantic-Model-Owner;
- Definition Decision an den Finance KPI Owner;
- lokale Sales-Operations-Variante an deren Domain Steward;
- Approval Refresh an den Governance Owner;
- deprecated Dashboard Relationship an den Report Owner;
- Retrieval Scope Rule an den AI Application Owner.

Nach der Korrektur lautet das authoritative Metadata Profile:

```yaml
term_id: kpi.finance.net_revenue
display_name: Net Revenue
scope: group_financial_reporting
grain: legal_entity_day
time_basis: accounting_posting_date
currency_basis: group_reporting_currency

calculation:
  formula_reference: semantic.finance.net_revenue.v4
  includes:
    - posted_invoice_amount
  subtracts:
    - discounts
    - returns
    - credit_notes
  excludes:
    - cancelled_invoices
    - unposted_documents

ownership:
  business_owner: role:group-finance-kpi-owner
  technical_owner: team:finance-semantic-platform

approval:
  status: approved
  policy_version: KPI-GOV-3.1
  evidence_reference: decision/KPI-2026-041
  reviewed_at: 2026-07-20
  review_by: 2027-01-20

usage:
  approved_for:
    - executive_reporting
    - planning
    - governed_ai_retrieval
  not_equivalent_to:
    - kpi.sales_ops.net_revenue
```

Die lokale Sales-Operations-Definition bleibt mit anderem Scope und explizitem Mapping erhalten. Legitimate Difference bleibt erhalten, ohne beide Werte als universellen KPI darzustellen.

## Ein Implementierungsmuster passend zur Reife wählen

### Source-Native Validation

Rules laufen in Source Platform, Repository oder Semantic Layer.

Geeignet, wenn:

- ein System autoritativ ist;
- Checks nah an den Metadaten ausgedrückt werden können;
- Correction Ownership lokal ist;
- Central Aggregation nachrangig ist.

Risiko:

Cross-Platform Conflicts, Enterprise Trends und gemeinsame Criticality Models bleiben schwierig.

### Central Metadata Quality Service

Ein zentraler Service bewertet normalisierte Metadaten aus mehreren Systemen.

Geeignet, wenn:

- eine Enterprise View erforderlich ist;
- gemeinsame Rules und Reporting wichtig sind;
- Source Systems ausreichende Metadaten bereitstellen;
- dedizierte Integration Ownership existiert.

Risiko:

Der Service kann zum zweiten Authoring System werden oder stale Copies bewerten, wenn Provenance und Freshness schwach sind.

### Contract-as-Code

Quality Expectations werden mit Data Product oder Transformation Code versioniert und in CI/CD geprüft.

Geeignet, wenn:

- Engineering Workflows reif sind;
- Metadata Changes gemeinsam mit Code transportiert werden;
- Deployment Gates benötigt werden;
- Rules deklarativ beschrieben werden können.

Risiko:

Business Definitions, Approvals und Exceptions benötigen weiterhin zugängliche Governance Workflows. Code Review erzeugt nicht automatisch Business Authority.

### Federated Stewardship

Domains definieren und betreiben lokale Quality Profiles innerhalb von Enterprise Minimum Standards.

Geeignet, wenn:

- Domain Semantics abweichen;
- accountable Stewards existieren;
- Central Governance gemeinsame Dimensions und Evidence definieren kann;
- Local Correction Speed wichtig ist.

Risiko:

Scores werden unvergleichbar, wenn Domains Checks, Severity oder Applicability unkontrolliert neu definieren.

### Hybrides Betriebsmodell

Ein Central Framework definiert:

- gemeinsame Dimensions;
- Rule Schema;
- Evidence Model;
- Criticality Levels;
- Issue States;
- Exception Requirements;
- Enterprise Reporting.

Domains definieren:

- asset-spezifische Expectations;
- lokale Vocabularies;
- authoritative Sources;
- accountable Owner;
- Remediation Workflows;
- begründete lokale Thresholds.

Das ist meist das praktikabelste Enterprise Pattern.

Es erhält Vergleichbarkeit, ohne jedes Asset in dieselbe Checklist zu zwingen.

## Einen kontinuierlichen Metadaten-Verbesserungsloop betreiben

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/measure-and-improve-metadata-quality-img4-de.png"
        alt="Ein kontinuierlicher Loop führt über Messen, Priorisieren, Korrigieren, Validieren, Publizieren, Nutzung beobachten, Lernen und erneut Messen; Quality Rules, Feedback, fehlgeschlagene Suchen, AI-Antwortfehler, Audits und Schemaänderungen verbessern Templates, Automation, Ownership und Vocabulary"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Metadatenqualität verbessert sich durch wiederholte Messung, Korrektur an der Quelle, Validation und beobachtete Nutzung. Fehler werden zu Inputs für bessere Templates, Automation, Ownership und Controlled Vocabulary.
    </figcaption>
</figure>

Der Operating Loop lautet:

```text
Messen
→ Priorisieren
→ Korrigieren
→ Validieren
→ Publizieren
→ Nutzung beobachten
→ Lernen
→ Messen
```

### Messen

Anwendbare Rules bewerten und detaillierte Evidenz erhalten.

### Priorisieren

Criticality, Severity, Downstream Impact, Age und Consumer Need verwenden.

### Korrigieren

Die authoritative Source ändern, nicht nur die Central Representation.

### Validieren

Relevante Checks erneut ausführen und Relationships, Approval und Publication State bestätigen.

### Publizieren

Korrigierte Metadaten für Search, Governance, Pipelines, BI und AI Consumers bereitstellen.

### Nutzung beobachten

Beobachten, ob User das Asset finden, Definitionen korrekt auswählen, Controls aufgelöst werden und Downstream Applications die vorgesehene Version verwenden.

### Lernen

Fehler nutzen, um zu verbessern:

- Description Templates;
- Controlled Vocabularies;
- Automated Detection;
- Source Connectors;
- Ownership Assignment;
- Review Intervals;
- Default Quality Profiles;
- Training und Stewardship Guidance.

Der Loop sollte mehrere Inputs aufnehmen:

```text
Quality-Rule-Failures
User Feedback
Failed Searches
Zero-Result Searches
AI Answer Failures
Audit Findings
Schema Changes
Incidents
Deprecated Assets
Unresolved Conflicts
```

Die Outputs sollten zukünftigen manuellen Aufwand reduzieren:

```text
bessere Templates
stärkere Automation
aktualisierte Ownership
präziseres Controlled Vocabulary
frühere Validation
weniger Duplicate Concepts
präzisere Retrieval
schnellere Remediation
```

## Trends, Aging und Exceptions verfolgen

Ein Quality Dashboard sollte Action unterstützen und nicht nur Präsentation.

Nutzbare Views sind:

### Dimension Trend

Completeness, Correctness, Freshness und andere Dimensions getrennt über die Zeit zeigen.

Ein stabiler Overall Score kann verstecken, dass Completeness steigt, während Freshness sinkt.

### Status kritischer Assets

Berichten:

```text
kritische Assets mit Mandatory Failures
kritische Assets ohne Evaluation
kritische Assets mit abgelaufenem Approval
kritische Assets mit ungelösten Definition Conflicts
```

### Issue Aging

Aging Buckets verwenden, etwa:

```text
0–7 Tage
8–30 Tage
31–90 Tage
mehr als 90 Tage
```

Nach Severity, Owner, Domain, Source und Rule segmentieren.

### Time to Remediation

Messen:

- Detection bis Assignment;
- Assignment bis Correction;
- Correction bis Re-Harvest;
- Re-Harvest bis Validation;
- gesamte Time to Closure.

Damit werden Ownership Delay und Connector- oder Validation Delay getrennt.

### Reopen Rate

Eine hohe Reopen Rate weist auf oberflächliche Corrections, instabile Source Processes oder schwache Validation hin.

### Exception Aging

Verfolgen:

- aktive Exceptions;
- Exceptions nahe Expiry;
- abgelaufene Exceptions;
- wiederholt verlängerte Exceptions;
- Compensating Controls ohne Evidence.

Wiederholte Verlängerung ist ein Signal, dass die Exception zum undokumentierten Target State geworden ist.

### Coverage Trend

Zeigen, ob tatsächlich mehr von der relevanten Landschaft bewertet wird.

Ein steigender Score bei schrumpfendem oder verzerrtem Scope ist irreführend.

## Häufige Anti-Patterns vermeiden

### Ein undurchsichtiger Enterprise Score

Eine einzelne Zahl ist leicht zu präsentieren und schwer in Action zu übersetzen.

Sie versteckt failing Dimension, applicable Rules, Mandatory Failures, Exceptions und unevaluated Scope.

### Universelle Mandatory-Field-Liste

Eine Checklist für Tables, KPIs, Dashboards und AI Datasets erzeugt Noise und False Confidence.

### Non-Null bedeutet Quality

Placeholder Text, unresolved References und stale Values können alle non-null sein.

### Zentrale Korrektur von Source Defects

Ein Manual Patch im Catalog kann vom nächsten Harvest überschrieben werden und lässt die authoritative Source falsch.

### Confidence als Approval behandeln

Ein Detector Score ist Evidence für Review, keine Governance Authority.

### Exceptions als Passes zählen

Dadurch werden Risiken unsichtbar und der Druck zur Auflösung temporärer Zustände entfällt.

### Stale Assets verbessern den Score

Deprecated oder orphaned Assets sollten explizit klassifiziert werden. Sie dürfen nicht still aus dem Denominator verschwinden, um Ergebnisse zu verbessern.

### Quality ohne Remediation Ownership

Ein Dashboard, das Defects erkennt, aber nicht routen kann, wird zu einer weiteren Reporting Layer.

### Nur leicht Messbares bewerten

Completeness ist leicht zu automatisieren. Correctness, Clarity und Operational Usability benötigen bewusstere Evidenz und Review. Werden sie ignoriert, entsteht ein polierter, aber schwacher Catalog.

### Domains für breitere Coverage bestrafen

Eine Domain, die mehr Systeme verbindet, wird zunächst mehr Defects sichtbar machen. Trend, Criticality und Coverage vergleichen, nicht nur rohe Failure Counts.

## Entscheidungshilfe

Die folgenden Fragen strukturieren das Operating Model:

1. Welche Decisions hängen von den Metadaten ab?
2. Welche Asset Types liegen im Scope?
3. Welche Assets sind kritisch?
4. Welche Dimensions können automatisiert geprüft werden?
5. Welche Checks benötigen Human Judgment?
6. Was ist die authoritative Source für jeden Wert?
7. Wer besitzt die Rule?
8. Wer besitzt Remediation?
9. Welche Failures sollten Publication oder Deployment blockieren?
10. Wie werden Proposals, Exceptions und unevaluated Checks dargestellt?
11. Welche Evidence ist für Closure erforderlich?
12. Wie werden Quality Trends nach Coverage und Criticality segmentiert?
13. Welche Consumer Failures fließen in den Improvement Loop?
14. Wie werden korrigierte Metadaten an Downstream Systems publiziert?

Das sicherste Startdesign ist nicht die größte Scorecard.

Es ist der kleinste vollständige Loop, der einen wesentlichen Defect erkennt, dem richtigen Owner zuweist, an der Source korrigiert, das Ergebnis validiert und Evidence aufzeichnet.

## Wichtigste Empfehlungen

1. Metadaten wie governed Data mit expliziten Schemas, Ownern, Rules, Evidence und Lifecycle behandeln.
2. Vollständigkeit, Korrektheit, Konsistenz, Aktualität, Eindeutigkeit, Klarheit, Provenance, Coverage, Beziehungsintegrität und operative Nutzbarkeit getrennt messen.
3. Expectations nach Asset Type und Criticality definieren.
4. Detaillierte Check Results statt nur eines Blended Score speichern.
5. Mandatory Failures, Warnings, Exceptions und unevaluated Checks getrennt halten.
6. References, Authority, State und Freshness validieren statt nur Non-Null Values.
7. Controlled Identity Keys verwenden, bevor Duplicates erkannt werden.
8. Method, Confidence, Evidence, Status und Version für inferred Metadata speichern.
9. Unabhängiges Approval für Control-Driving Metadata verlangen.
10. Jeden actionable Defect an accountable Owner und Responsible Source routen.
11. Metadaten möglichst an ihrer authoritative Source korrigieren.
12. Vor Issue Closure re-harvest und revalidate durchführen.
13. Exceptions scoped, approved, owned und expiring halten.
14. Trends, Coverage, Issue Aging, Remediation Time und Reopen Rate verfolgen.
15. Failed Searches, User Feedback, AI Answer Failures, Audits und Schema Changes als Improvement Inputs nutzen.
16. Einen Enterprise Score vermeiden, der konkrete Risiken verdeckt.
17. Mit einer wertvollen Asset-Klasse und einem vollständigen Remediation Loop beginnen.

> **Metadatenqualität ist nicht der Anteil befüllter Felder. Sie ist die nachgewiesene Fähigkeit von Metadaten, für die von ihnen abhängigen Decisions und Controls korrekt, aktuell, erklärbar, verbunden und nutzbar zu bleiben.**

## Als Nächstes: Metadaten durch Automation aktivieren

Messung erzeugt Sichtbarkeit.

Sie korrigiert keine Source, routet keinen Owner, aktualisiert keinen Catalog, blockiert kein Deployment und startet kein Review von selbst.

Teil 13 untersucht deshalb, **wie Metadaten durch Automation aktiviert werden**:

- event-driven Metadata Workflows;
- Tasks und Notifications;
- Automated Synchronization;
- Policy Activation;
- Deployment und Quality Gates;
- Ownership Routing;
- Approval Orchestration;
- Closed-Loop Evidence.

Teil 12 stellt fest, ob Metadaten vertrauenswürdig sind. Teil 13 verwandelt vertrauenswürdige Metadaten und erkannte Quality Events in kontrollierte Action.
