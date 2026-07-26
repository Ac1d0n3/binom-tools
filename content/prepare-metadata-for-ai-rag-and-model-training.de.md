---
title: Metadaten für AI, RAG und Modelltraining vorbereiten — Vertrauenswürdigen und berechtigungsbewussten Kontext für Retrieval und Lernen schaffen
description: Eine praxisnahe Architektur, um Dokumente, Datasets, Chunks, Features und Models mit Bedeutung, Provenance, Qualität, zeitlicher Gültigkeit, Berechtigungen, Lineage und Evidence so vorzubereiten, dass AI-Systeme freigegebenen Kontext finden, bewerten, zitieren und für Training verwenden können.
category: Data Governance
tags:
  - metadata
  - ai-ready-metadata
  - artificial-intelligence
  - rag
  - retrieval-augmented-generation
  - model-training
  - training-data
  - feature-metadata
  - data-lineage
  - data-quality
  - data-provenance
  - data-classification
  - permission-aware-retrieval
  - ai-governance
  - explainable-ai
  - semantic-search
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 15
seriesTitle: MetaData Deep Dive
hero: images/playbooks/prepare-metadata-for-ai-rag-and-model-training-hero.png
---

## AI-Systeme scheitern, wenn Kontext verfügbar, aber nicht vertrauenswürdig ist

Organisationen beginnen eine AI-Initiative häufig mit dem Sammeln von Text.

Policies, Handbücher, Tickets, Reports, Verträge, Data Dictionaries, Dashboards, Datasets und Source-Code-Dokumentation werden in einen Suchindex oder Object Store kopiert. Dokumente werden in Chunks zerlegt, Embeddings werden erzeugt und ein Model erhält die Passagen, die einer User-Frage semantisch ähnlich erscheinen.

Die erste Demonstration kann beeindruckend sein.

Das System findet relevante Begriffe und erzeugt eine flüssige Antwort. Möglicherweise nennt es sogar einen Dokumenttitel als Quelle.

Die operativen Fragen entstehen später:

- War das gefundene Dokument zum Zeitpunkt der Frage noch gültig?
- Handelte es sich um eine freigegebene Policy oder um einen veralteten Draft?
- Durfte der User den Quellinhalt überhaupt sehen?
- Basierte die Antwort auf der autoritativen Definition oder auf einer bequem erreichbaren Kopie?
- Welche Business Domain und welche Jurisdiction galten?
- Beschrieb die Quelle den aktuellen Prozess oder einen historischen Zustand?
- War das Dataset für Retrieval geeignet, aber für Model Training gesperrt?
- Hatte ein Quality Incident die Quelle vorübergehend unzuverlässig gemacht?
- Kann das System erklären, warum eine Quelle ausgewählt und eine andere ausgeschlossen wurde?
- Lässt sich später exakt rekonstruieren, welcher Kontext für eine Antwort oder einen Training Run verwendet wurde?

Eine Vektorrepräsentation kann diese Fragen nicht allein beantworten.

Semantic Similarity zeigt, dass zwei Inhalte möglicherweise zusammengehören. Sie beweist nicht, dass der Inhalt freigegeben, aktuell, vollständig, erlaubt, autoritativ oder für den vorgesehenen Zweck geeignet ist.

Dasselbe Problem besteht beim Modelltraining. Ein Verzeichnis voller Dateien oder eine große Tabelle kann technisch verfügbar sein und gleichzeitig Duplicate Records, abgelaufene Labels, verbotene Daten, Target Leakage, unklare Sampling-Regeln, unbekannte Provenance oder Inhalte enthalten, deren ursprünglicher Erhebungszweck kein Training erlaubt.

> **AI-ready Metadata ist die Control Layer, die entscheidet, welcher Kontext gefunden werden darf, wie Candidate Sources bewertet werden, welche Evidence eine Antwort begleiten muss und ob Daten in einen Training-, Validation- oder Evaluation-Workflow gelangen dürfen.**

AI Readiness benötigt deshalb mehr als Beschreibungen.

Sie benötigt strukturierte Bedeutung, Beziehungen, Quality Evidence, zeitliche Gültigkeit, Sensitivity, Permitted Use, Approval und Provenance.

## Das Kernprinzip: Kontextauswahl ist eine governte Entscheidung

Ein AI-System sollte nicht jedes indexierte Objekt als gleichwertige Quelle behandeln.

Ein brauchbares Entscheidungsmodell trennt vier Fragen:

```text
Darf diese Quelle überhaupt berücksichtigt werden?
Ist sie für Frage oder Trainingszweck relevant?
Ist sie für diesen Zweck und Zeitpunkt vertrauenswürdig?
Kann die Nutzung erklärt und rekonstruiert werden?
```

Daraus entsteht eine kontrollierte Context-Selection-Pipeline:

```text
Identity und Meaning
+ zeitliche Gültigkeit
+ Permission und Allowed Usage
+ Quality und Authority
+ Lineage und Evidence
→ freigegebene Candidate Set
→ gerankter Kontext
→ zitierte Nutzung
→ dokumentiertes Ergebnis
```

Die Reihenfolge ist entscheidend.

Permission, rechtliche Einschränkungen, Allowed Usage und verpflichtende Validity Rules sind Hard Gates. Eine verbotene Quelle darf nicht lediglich niedriger gerankt werden. Sie muss außerhalb der Candidate Set und außerhalb von Prompt oder Training Package bleiben.

Relevance, Freshness, Authority und Quality können anschließend die verbleibenden Candidates bewerten.

Diese Trennung verhindert ein häufiges Designproblem:

```text
Alles abrufen
→ das Model anweisen, eingeschränkte oder schwache Inhalte zu ignorieren
```

Zu diesem Zeitpunkt ist der Inhalt bereits in den Model Context gelangt.

Das sicherere Muster lautet:

```text
Metadaten und Entitlement prüfen
→ verbotene Quellen ausschließen
→ nur berechtigte Inhalte abrufen
→ nachvollziehbaren Kontext zusammenstellen
```

## Um jedes Asset ein AI-ready Metadata Package aufbauen

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/prepare-metadata-for-ai-rag-and-model-training-img1-de.png"
        alt="Ein AI-ready Metadata Package umgibt ein Data- oder Document-Asset mit Metadaten zu Bedeutung, Struktur, Vertrauen, Zeit, Berechtigung, Retrieval und Evidence und zeigt, dass eine reine Textbeschreibung nur ein Bestandteil ist"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein AI-ready Asset verbindet semantische, strukturelle, Governance-, zeitliche, Retrieval- und Evidence-Metadaten. Eine Beschreibung ist wertvoll, ersetzt aber weder Permission, Provenance, Version, Quality noch Lineage.
    </figcaption>
</figure>

Das Package muss nicht physisch in einer einzelnen Tabelle oder einem einzelnen Produkt liegen.

Es ist ein logischer Contract, der aus autoritativen Systemen zusammengestellt wird.

### Bedeutung

Meaning Metadata hilft Menschen und Maschinen zu verstehen, was ein Asset repräsentiert.

Nützliche Attribute sind:

```yaml
title: recognized-revenue-policy
definition: Regeln zur Umsatzrealisierung von Abonnements nach erfüllten Leistungsverpflichtungen
synonyms:
  - revenue recognition
  - recognized sales
domain: finance
business_terms:
  - recognized-revenue
  - performance-obligation
language: de
jurisdiction: global-with-local-addenda
```

Titel und Beschreibung sind nur der Anfang.

Synonyms verbessern Keyword- und Semantic Retrieval. Die Domain begrenzt den Suchraum. Controlled Business Terms verbinden Dokumente, Datasets, Metrics und Policies, die unterschiedliche technische Namen verwenden. Language, Geography, Product Line und Jurisdiction verhindern, dass ein global ähnliches Dokument fälschlich als lokal gültig behandelt wird.

Examples und Counterexamples sind besonders hilfreich bei mehrdeutigen Konzepten:

```yaml
examples:
  - jährliches Abonnement wird monatlich nach Leistungserbringung realisiert
counterexamples:
  - unterzeichneter Auftrag ohne erfüllte Leistungsverpflichtung
limitations:
  - deckt Hardware-Umsatz nicht ab
```

### Struktur

Structure Metadata erklärt, wie ein Asset aufgebaut ist und wie seine Bestandteile zusammenhängen.

Für strukturierte Daten umfasst dies:

- Schema;
- Field Types;
- Keys;
- Grain;
- Units;
- Relationships;
- Partitioning;
- erwartete Wertebereiche;
- Null Semantics.

Für Dokumente umfasst dies:

- Document Hierarchy;
- Section Hierarchy;
- Seiten- oder Absatzreferenzen;
- Tabellen und Abbildungen;
- Überschriften;
- Sprache;
- Attachments;
- Parent- und Child-Dokumente.

Für einen Chunk umfasst dies:

```yaml
chunk_id: policy-2026-04-section-7-chunk-03
parent_document_id: policy-2026-04
section_path:
  - Revenue Recognition
  - Contract Modifications
sequence: 3
page_start: 18
page_end: 19
character_start: 9421
character_end: 12804
chunking_strategy: heading-aware-v2
```

Ohne Hierarchie kann eine gefundene Passage den Scope verlieren, der erst durch Überschrift, vorherigen Absatz, Tabellenhinweis oder Appendix definiert wird.

### Vertrauen

Trust Metadata beschreibt, warum ein Asset eine AI-Entscheidung beeinflussen sollte oder nicht.

Nützliche Attribute sind:

```yaml
owner: finance-policy-office
steward: revenue-accounting-team
source_system: controlled-policy-repository
provenance_status: verified
certification: approved
quality_tier: critical
quality_status: passed
quality_checked_at: 2026-07-25T06:00:00Z
known_issues: []
```

Trust ist kein einzelner Score.

Ownership, Approval, Provenance und Quality liefern unterschiedliche Evidence. Eine Quelle kann autoritativ sein und gleichzeitig vorübergehend eine Freshness Expectation verletzen. Eine andere Quelle kann aktuell, aber nur proposed sein. Eine dritte kann technisch korrekt, jedoch außerhalb des angefragten Business Scope liegen.

Das System sollte diese Unterschiede erhalten, statt sie in einem dekorativen Badge zu verstecken.

### Zeit

AI-Systeme müssen wissen, wann Informationen gelten.

Nützliche Attribute sind:

```yaml
valid_from: 2026-04-01
valid_to: null
published_at: 2026-03-15T09:00:00Z
observed_at: 2026-07-25T06:00:00Z
last_refreshed_at: 2026-07-25T06:00:00Z
freshness_objective: PT24H
version: 4.2
supersedes: policy-2025-11
status: effective
```

`Published at`, `observed at`, `valid from` und `last refreshed at` sind nicht austauschbar.

Eine Policy kann vor ihrem Effective Date veröffentlicht werden. Ein Dataset kann heute aktualisiert werden und trotzdem Transaktionen des Vormonats beschreiben. Ein historisches Dokument kann alt, aber für eine Frage zur Vergangenheit korrekt sein. Die neueste Quelle ist daher nicht automatisch die richtige Quelle.

### Berechtigung

Permission Metadata definiert, wer ein Asset für welchen Zweck nutzen darf.

Nützliche Attribute sind:

```yaml
sensitivity: confidential
contains_personal_data: true
pii_categories:
  - customer-identifier
allowed_usage:
  - internal-answering
  - approved-analytics
rag_suitability: approved-with-entitlement
training_permission: prohibited
fine_tuning_permission: prohibited
external_model_usage: prohibited
required_entitlements:
  - finance-policy-reader
retention_class: regulated-seven-years
```

Access und Allowed Usage sind unterschiedliche Entscheidungen.

Ein User darf ein Dokument möglicherweise lesen, während die Organisation dessen Nutzung für Model Training trotzdem verbietet. Ein Dataset kann für aggregierte Analytics freigegeben sein, aber nicht für personenbezogene Antworten. Ein öffentliches Dokument kann Retrieval erlauben und dennoch urheberrechtliche oder vertragliche Einschränkungen für Training oder Weiterverteilung besitzen.

Der Metadata Contract muss den vorgesehenen Zweck ausdrücken, statt sich auf ein generisches `accessible`-Flag zu verlassen.

### Retrieval

Retrieval Metadata verbessert Candidate Discovery und Ranking.

Nützliche Attribute sind:

```yaml
keywords:
  - revenue
  - recognition
  - subscription
search_boost_terms:
  - performance obligation
embedding_profile: multilingual-general-v3
chunk_hierarchy: section-aware
source_priority: 90
retrieval_domains:
  - finance
  - accounting
supported_question_types:
  - policy interpretation
  - effective-date lookup
excluded_question_types:
  - legal advice
```

Retrieval Metadata kann außerdem enthalten:

- Preferred Aliases;
- Sprachvarianten;
- Document Type;
- Asset Type;
- Geographic Scope;
- Semantic Concepts;
- Chunk Relationships;
- Source Ranking Rules;
- Retrieval Exclusions;
- erwarteten Answer Type.

Embeddings sind ein Retrieval Signal. Keyword Search, Graph Traversal, Metadata Filters und Structured Queries bleiben wichtig, weil exakte Identifier, Datumswerte, Policy Codes und Field Names durch reine Semantic Similarity häufig schlecht abgedeckt werden.

### Evidence

Evidence Metadata macht Nutzung erklärbar.

Nützliche Attribute sind:

```yaml
lineage:
  - source: approved-policy-repository/revenue-recognition-v4.2
    transformation: pdf-to-structured-document-v2
    target: knowledge-index/policy-2026-04
citations:
  - type: source-document
    locator: pages-18-19
approval_evidence: approval-78421
quality_evidence: validation-run-2026-07-25-0600
limitations:
  - lokale steuerliche Behandlung benötigt jurisdictional addendum
```

Evidence sollte zwei Formen der Rekonstruktion unterstützen:

```text
Warum war diese Quelle berechtigt und ausgewählt?
Welche exakte Version und Passage beeinflusste das Ergebnis?
```

Dafür werden stabile Identifier und versionierte Referenzen benötigt, nicht nur ein Display Title.

## Unterschiedliche AI-Assets benötigen unterschiedliche Metadata Profiles

Ein generisches `asset`-Schema ist für gemeinsame Felder sinnvoll. Für jeden AI Use Case reicht es nicht aus.

Dokumente, Chunks, Datasets, Features und Models benötigen type-specific Metadata.

### Document Metadata

Ein Document Profile sollte normalerweise enthalten:

- stabile Document Identity;
- Titel und Aliases;
- Document Type;
- authoring organization;
- Owner und Steward;
- Language;
- Domain und Jurisdiction;
- Status und Approval;
- Version und Supersession;
- Valid-from- und Valid-to-Dates;
- Sensitivity und Permitted Use;
- Source Location;
- Checksum;
- Extraction Status;
- bekannte Limitations.

### Chunk Metadata

Ein Chunk Profile sollte enthalten:

- stabile Chunk Identity;
- Parent Document und Parent Section;
- exakten Locator;
- Sequence;
- Chunking Strategy und Version;
- inherited und overridden Classifications;
- inherited Validity;
- Embedding Profile;
- Retrieval Keywords;
- Local Summary;
- Surrounding-Context References;
- Extraction Confidence.

Chunk Metadata darf Document Metadata nicht stillschweigend ersetzen.

Ein Chunk erbt Default Context aus dem Dokument, kann jedoch restriktivere Regeln benötigen. Eine Tabelle mit personenbezogenen Daten in einem ansonsten internen Handbuch kann eine höhere Sensitivity als das Parent Document besitzen.

### Dataset Metadata

Ein Dataset Profile sollte enthalten:

- Business Purpose;
- Population;
- Grain;
- Time Window;
- Collection Method;
- Source Systems;
- Inclusion- und Exclusion-Rules;
- Sampling;
- Labels und Label Provenance;
- Quality Results;
- Representativeness Limitations;
- Sensitivity;
- Permitted Use;
- Retention;
- Version;
- Lineage;
- Approval State.

### Feature Metadata

Ein Feature Profile sollte enthalten:

- Business Meaning;
- Derivation Logic;
- Source Lineage;
- Calculation Time;
- Availability Time;
- Null Handling;
- Expected Distribution;
- Stability;
- Leakage Risk;
- Beziehung zu Protected Attributes;
- Online- und Offline-Consistency;
- Version;
- Owner;
- freigegebene Model Scopes.

### Model Metadata

Ein Model Profile sollte enthalten:

- Model Identity und Version;
- Intended Purpose;
- Training Dataset Versions;
- Feature- oder Input Contract;
- Evaluation Datasets;
- Metrics und Thresholds;
- Known Limitations;
- Deployment Scope;
- Prohibited Uses;
- Approval Status;
- Monitoring Expectations;
- Rollback Reference;
- Lineage zu Outputs und Downstream Consumers.

Der gemeinsame Metadata Graph sollte diese Profile verbinden, ohne so zu tun, als seien sie identisch.

## Retrieval Metadata von Training Dataset Metadata trennen

RAG und Model Training verwenden Informationen auf unterschiedliche Weise.

RAG wählt Kontext zur Query Time. Der Source Content bleibt außerhalb der Model Weights und kann für zukünftige Queries gefiltert, zitiert, aktualisiert oder entzogen werden.

Training verändert Model Parameters oder ein task-specific Model Artifact. Der genaue Einfluss eines einzelnen Training Items lässt sich später deutlich schwerer isolieren oder entfernen.

Die Metadata Requirements überschneiden sich daher, sind aber nicht austauschbar.

### Retrieval Metadata fragt

```text
Darf dieser User diese Quelle jetzt abrufen?
Ist die Quelle für diese Frage relevant?
Ist sie für den angefragten Zeitpunkt gültig?
Wie sollte sie gerankt werden?
Welche exakte Passage muss zitiert werden?
```

### Training Metadata fragt

```text
Wurde dieser Inhalt für Training erhoben und freigegeben?
Welche Population und welches Time Window repräsentiert das Dataset?
Welche Transformations-, Label- und Sampling-Regeln wurden angewendet?
Kann der Training Run reproduziert werden?
Welche Model Version verwendete diese Dataset Version?
Welche Limitations und Prohibited Uses folgen aus den Daten?
```

Ein praktikabler Contract hält separate Attribute vor:

```yaml
rag:
  suitability: approved
  allowed_audiences:
    - finance-employees
  citation_required: true
  temporal_filter_required: true

training:
  permission: prohibited
  reason: contractual-use-restriction
  reviewed_by: legal-data-governance
  reviewed_at: 2026-05-10
```

Training Permission darf nicht aus Retrievability abgeleitet werden.

Retrievability darf nicht aus der Aufnahme in ein internes Training Dataset abgeleitet werden.

## Mit der einfachsten tragfähigen Implementierung beginnen

Die einfachste tragfähige Implementierung ist kein unternehmensweiter Knowledge Graph mit jedem denkbaren AI Asset.

Sie ist ein governter Use Case mit klaren Source Boundaries.

Eine praktikable erste Implementierung kann acht Schritte nutzen.

### 1. Eine Question Domain definieren

Einen begrenzten Use Case auswählen, zum Beispiel:

```text
Interne Fragen zu freigegebenen Finance Policies beantworten.
```

Definieren:

- vorgesehene User;
- erlaubte Question Types;
- autoritative Source Repositories;
- ausgeschlossene Repositories;
- erwartete Freshness;
- Citation Requirement;
- Escalation Path bei Source Conflicts.

### 2. Autoritative Quellen inventarisieren

Für jedes Repository oder Dataset erfassen:

```yaml
source_id: finance-policy-repository
source_owner: finance-policy-office
authority_scope:
  - global-finance-policy
content_types:
  - approved-policy
  - approved-procedure
excluded_states:
  - draft
  - expired
  - withdrawn
interface: repository-api
freshness_objective: PT1H
permission_source: corporate-identity-groups
```

Source Authority benötigt einen Scope.

Ein Legal Repository kann autoritativ für Contract Templates, aber nicht für Accounting Policy sein. Ein BI Catalog kann autoritativ für Metric Definitions, aber nicht für Operational Procedures sein. Ein Ticketing System kann wertvolle Beispiele enthalten, aber keine freigegebene Policy.

### 3. Einen minimalen AI Metadata Contract definieren

Mit Feldern beginnen, die System Behaviour verändern:

```yaml
asset_id: required
asset_type: required
domain: required
status: required
version: required
valid_from: required
valid_to: optional
owner: required
source_authority: required
sensitivity: required
allowed_usage: required
rag_suitability: required
training_permission: required
required_entitlements: required
quality_status: required
source_locator: required
```

Descriptions, Synonyms und Examples verbessern Retrieval. Permission, Validity und Source Identity schützen die Control Boundary. Beide Gruppen sind notwendig.

### 4. Raw, Normalized und Approved States erhalten

Originale Source Metadata und Extraction Result aufbewahren.

Danach Identifier, Dates, Asset Types und Classifications normalisieren.

Abschließend Approved Governance Values anwenden.

```text
Raw Source Metadata
→ Normalized AI Metadata
→ Approved Retrieval- oder Training-Profile
```

Source Evidence darf nicht überschrieben werden, wenn ein Steward einen Wert korrigiert oder anreichert.

### 5. Permission vor Content Retrieval erzwingen

User- oder Service Identity auflösen, bevor Source Content geladen wird.

Prüfen:

- Repository Entitlement;
- Row- oder Document Scope;
- Sensitivity Rules;
- Allowed-Usage Rules;
- Jurisdiction Restrictions;
- aktuellen Exception Status.

Nur berechtigte Identifier dürfen an die Retrieval Engine übergeben werden.

### 6. Retrieval Methods kombinieren

Die Methoden einsetzen, die zur Frage passen:

```text
Metadata Filtering
+ Exact Keyword- oder Identifier-Search
+ Semantic Search
+ Relationship Traversal
+ Structured Data Query
```

Ein Policy Code kann Exact Search benötigen. Eine breite konzeptionelle Frage kann von Semantic Retrieval profitieren. Eine Frage zu Downstream Impact kann Lineage Traversal erfordern. Eine numerische Antwort kann eine governte Query statt Document Chunks benötigen.

### 7. Citations aus stabiler Evidence erzeugen

Der Context Builder sollte erhalten:

- Asset ID;
- Version;
- Chunk ID;
- Document Locator;
- Source System;
- Retrieval Timestamp;
- Ranking Reasons;
- angewandte Permission Decision;
- Quality State.

Die Antwort sollte die Quelle zitieren, die tatsächlich zum Kontext beigetragen hat, nicht lediglich die Startseite des Repositories.

### 8. Die Entscheidung protokollieren, ohne unnötigen Content zu kopieren

Genug Metadata speichern, um die Entscheidung rekonstruieren zu können:

```yaml
question_id: q-2026-07-25-00418
user_scope_hash: 62f...
retrieval_policy_version: rag-policy-3.1
candidate_count: 42
eligible_count: 7
selected_chunks:
  - chunk_id: policy-2026-04-section-7-chunk-03
    rank: 1
    reasons:
      - semantic-match
      - approved-source
      - temporally-valid
      - quality-passed
excluded_reason_counts:
  permission: 12
  expired: 8
  draft: 6
  wrong-domain: 9
```

Vollständige sensitive Prompts und Inhalte sollten nicht geloggt werden, wenn Identifier und Hashes als operative Evidence ausreichen.

## Vertrauenswürdigen Kontext für RAG vor der Generation zusammenstellen

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/prepare-metadata-for-ai-rag-and-model-training-img2-de.png"
        alt="Ein RAG Workflow führt von User-Frage über Intent- und Domain-Erkennung, Metadata Filter, Berechtigungsprüfung, Source Ranking, Chunks und Daten abrufen, Kontext mit Zitaten erstellen bis zur Antwortgenerierung, während abgelehnte Quellen außerhalb des Prompt Context bleiben"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Trusted RAG filtert Candidate Sources nach Domain, Asset Type, Validity, Quality, Sensitivity und Entitlement, bevor Content in den Prompt gelangt. Abgelehnte Quellen bleiben außerhalb des Model Context.
    </figcaption>
</figure>

Ein belastbarer RAG Path trennt Query Understanding, Policy Evaluation, Retrieval und Generation.

### User Question

Question, User Identity, Session Purpose und angefragten Zeitpunkt erfassen.

Eine Frage wie:

```text
Welche Revenue-Recognition-Regel galt im November 2025 für Contract Modifications?
```

enthält ein explizites historisches Datum. Ein System, das ausschließlich das neueste Dokument bevorzugt, kann die falsche Policy auswählen.

### Intent- und Domain-Erkennung

Ermitteln:

- Question Type;
- Business Domain;
- relevante Asset Types;
- Entities;
- Dates;
- Geographic oder Legal Scope;
- gewünschte Detailtiefe.

Intent Detection kann Model-assisted sein, sollte aber beobachtbar und korrigierbar bleiben.

### Metadata Filter

Deterministische Filter anwenden, soweit möglich:

- Domain;
- Asset Type;
- Language;
- Jurisdiction;
- Approval Status;
- Temporal Validity;
- Minimum Quality State;
- Retrieval Suitability.

### Permission Check

Aktuelles Entitlement gegen das autoritative Access System auflösen.

Ein kopiertes Permission Label in einem Vector Index kann veralten. Kritische Permission Decisions sollten aktuelle Identity- und Policy Evidence oder einen kontrollierten Cache mit expliziter Freshness und definiertem Failure Behaviour verwenden.

Bei sensitiven Inhalten muss das System geschlossen fehlschlagen, wenn das Entitlement Result nicht verfügbar ist.

### Source Ranking

Nur berechtigte Sources ranken.

Nützliche Signale sind:

- Semantic Relevance;
- Approved Definition;
- Data Quality;
- Freshness;
- Source Authority;
- Temporal Fit;
- Exact Identifier Match;
- Domain Match;
- Citation Quality;
- Known Limitations.

### Chunks und Daten abrufen

Den kleinsten Kontext laden, der weiterhin verständlich bleibt.

Das kann umfassen:

- einen Document Chunk plus Überschrift;
- eine Tabelle plus zugehörigen Hinweis;
- eine Policy Section plus Version Header;
- eine Metric Definition plus Grain und Filters;
- ein Structured Query Result plus Data Timestamp;
- einen Lineage Path plus betroffene Assets.

### Kontext mit Citations erstellen

Context Assembly sollte Grenzen zwischen Sources erhalten.

Widersprüchliche Aussagen dürfen nicht zu einem unmarkierten Absatz verschmolzen werden.

Ein brauchbarer Context Envelope kann so aussehen:

```yaml
source_id: policy-2025-11
version: 3.8
status: superseded
valid_from: 2025-01-01
valid_to: 2026-03-31
locator: section-7.2-pages-16-17
authority: approved-global-finance-policy
content: ...
```

### Answer generieren

Die Generation Instruction sollte das Model verpflichten:

- aus bereitgestellter Evidence zu antworten;
- materielle Aussagen zu zitieren;
- Conflicts offenzulegen;
- anzugeben, wenn keine freigegebene Quelle ausreicht;
- aktuelle und historische Regeln zu trennen;
- Permission oder Policy nicht über die Evidence hinaus abzuleiten.

Eine Refusal oder Escalation kann das richtige Ergebnis sein.

## Abgelehnte Quellen außerhalb des Prompts halten

Eine abgelehnte Quelle sollte nicht mit einer Anweisung wie `diese Quelle nicht verwenden` an das Model übergeben werden.

Dieses Muster erzeugt mehrere Risiken:

- sensitive Inhalte gelangen in die Inference Boundary;
- das Model kann den Inhalt trotzdem nutzen;
- Prompt-Injection-Anweisungen bleiben sichtbar;
- Logs oder Traces können verbotenen Inhalt erfassen;
- Downstream Tools können ihn erhalten;
- die Organisation kann nicht glaubwürdig behaupten, die Quelle sei ausgeschlossen worden.

Der richtige Control Point liegt vor Content Retrieval oder vor Context Assembly.

Explizite Exclusion Reasons verwenden:

```yaml
candidate_id: draft-policy-2026-08
eligible: false
reasons:
  - status-not-approved
  - valid-from-in-future
  - user-not-entitled
```

Exclusion Evidence ist nützlich, obwohl der Content nie geladen wird.

## Konkretes RAG-Beispiel: eine historische Policy-Frage beantworten

Angenommen, vier Candidate Sources existieren:

```text
A. Freigegebene Policy v3.8 — gültig während 2025
B. Freigegebene Policy v4.2 — aktuell seit April 2026
C. Draft Interpretation Note — erstellt im Juli 2026
D. Help-Desk-Artikel — aktualisiert im Mai 2026
```

Der User fragt:

```text
Wie wurden Contract Modifications im November 2025 behandelt?
```

Eine reine Semantic Search kann B, C und D höher als A ranken, weil dort neuere Terminologie und explizitere Beispiele enthalten sind.

Der metadata-aware Process bewertet:

```text
angefragter Zeitpunkt: November 2025
erforderliche Domain: Finance
erforderlicher Asset Type: Approved Policy oder Approved Procedure
User Entitlement: finance-policy-reader
Minimum Quality: passed
Citation Required: true
```

Das Ergebnis lautet:

- A wird Primary Context, weil die Policy im November 2025 freigegeben und gültig war;
- B kann nur Supporting Context werden, wenn die Antwort erklärt, dass sie ab April 2026 gilt;
- C wird als Draft ausgeschlossen;
- D wird für Policy Interpretation ausgeschlossen oder nur als nicht autoritatives Beispiel verwendet.

Die Antwort kann dann formulieren:

```text
Im November 2025 galt Policy v3.8.
Die aktuelle Policy unterscheidet sich seit April 2026.
```

Das System sollte die exakte Section in v3.8 zitieren und die spätere Änderung offenlegen, wenn sie relevant ist.

Dieses Beispiel zeigt, warum Freshness und Authority nicht auf ein einzelnes Sortierfeld reduziert werden dürfen.

## Training Datasets, Features und Models als verbundene Profile governieren

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/prepare-metadata-for-ai-rag-and-model-training-img3-de.png"
        alt="Drei verbundene Profile beschreiben Metadaten für Training Dataset, Feature und Model und sind über versionierte Lineage und Freigabe miteinander verbunden"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Training Datasets, Features und Models benötigen unterschiedliche Profile, die über versionierte Lineage und Approval verbunden sind. Dadurch bleiben Purpose, Derivation, Evaluation und Deployment Scope rekonstruierbar.
    </figcaption>
</figure>

Training Governance scheitert, wenn nur das fertige Model registriert wird.

Eine Model Card kann fehlende Dataset Lineage nicht nachträglich rekonstruieren. Ein Dataset Catalog Entry kann Feature Leakage nicht erklären, wenn Derivation und Availability Time fehlen. Ein freigegebenes Dataset genehmigt nicht automatisch jeden Model Purpose.

Die drei Profile müssen verbunden bleiben.

### Training Dataset Profile

Ein brauchbarer Training Dataset Contract kann enthalten:

```yaml
dataset_id: churn-training-2026q2
version: 2.1
purpose: predict-voluntary-customer-churn
population: active-consumer-contracts
observation_window:
  start: 2024-01-01
  end: 2026-03-31
prediction_horizon: P90D
label:
  name: churn-within-90-days
  derivation_version: label-rule-4
sampling:
  method: stratified
  seed: 48117
  exclusions:
    - fraud-investigation
    - employee-accounts
permitted_use:
  - churn-model-development
prohibited_use:
  - credit-decision
approval_status: approved
```

Das Profile sollte außerdem Quality, Representativeness, Known Gaps, Sensitivity, Retention, Licensing oder Contractual Restrictions und Lineage zu Raw Sources dokumentieren.

### Feature Profile

Ein brauchbarer Feature Contract kann enthalten:

```yaml
feature_id: support-contact-count-30d
version: 3
meaning: Anzahl abgeschlossener Customer-Support-Kontakte in den vorherigen 30 Tagen
derivation: count(completed_contacts)
event_time_field: contact_closed_at
availability_delay: PT2H
null_handling: zero-after-source-completeness-check
source_lineage:
  - support.case
leakage_risk: reviewed
stability_status: monitored
approved_model_scopes:
  - churn-prediction
```

`Event Time` und `Availability Time` sind kritisch.

Ein Feature kann historisch in einem Warehouse vorhanden, zum Zeitpunkt einer realen Prediction jedoch noch nicht verfügbar gewesen sein. Wird es für Training verwendet, entsteht Leakage.

### Model Profile

Ein brauchbarer Model Contract kann enthalten:

```yaml
model_id: customer-churn-classifier
version: 7.3
intended_purpose: prioritize-retention-outreach
training_dataset: churn-training-2026q2@2.1
feature_contract: churn-feature-set@5.0
evaluation_dataset: churn-holdout-2026q2@1.0
approval_status: approved
deployment_scope:
  - germany-consumer-subscriptions
prohibited_uses:
  - automated-contract-termination
  - creditworthiness-assessment
limitations:
  - performance-degrades-for-contracts-younger-than-60-days
monitoring_profile: churn-monitoring@3
```

Das Model Profile sollte auf Evaluation Evidence verweisen, statt nur Headline Metrics zu kopieren.

## Versionierte Lineage muss Daten, Features, Models und Decisions verbinden

Ein Training Lineage Graph sollte beantworten:

```text
Welche Raw Sources erzeugten diese Dataset Version?
Welche Transformation Version erzeugte jedes Feature?
Welche Labels und Sampling Rules wurden angewendet?
Welcher Model Run verwendete das Dataset?
Welches Evaluation Result begründete die Freigabe?
Wo ist das Model deployed?
Welche Downstream Decisions oder Products verwenden seinen Output?
```

Nützliche Lineage Edges sind:

```text
Source Dataset Version
→ Transformed Dataset Version
→ Training Dataset Version
→ Feature Set Version
→ Training Run
→ Model Version
→ Evaluation Run
→ Approval Decision
→ Deployment
→ Monitored Outputs
```

Lineage ohne Versions ist unzureichend.

Wenn sich `customer_status` nach dem Training verändert, beweist eine Edge zur aktuellen Tabelle nicht, welches Schema und welche Werte das Model verwendete.

Approval sollte ebenfalls version-scoped sein.

```text
Dataset v2.1 freigegeben für Churn Prediction
```

ist etwas anderes als:

```text
Alle zukünftigen Versionen dieses Datasets sind für jedes Model freigegeben
```

Die zweite Aussage ist selten belastbar.

## Quellen nach Trust, Relevance und Time bewerten

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/prepare-metadata-for-ai-rag-and-model-training-img4-de.png"
        alt="Candidate Sources werden anhand von semantischer Relevanz, freigegebener Definition, Datenqualität, Aktualität, Quellenautorität, User-Berechtigung und zeitlicher Passung bewertet und als Primärkontext, unterstützender Kontext, ausgeschlossen oder Konflikt mit Offenlegungspflicht eingeordnet"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Source Selection verwendet zunächst harte Permission- und Validity-Gates und danach ein Multi-Signal Ranking. Die neueste Quelle ist nicht automatisch die autoritativste, und ungelöste Konflikte müssen sichtbar bleiben.
    </figcaption>
</figure>

Ein einzelner Relevance Score verbirgt wichtige Entscheidungen.

Zuerst Hard Gates anwenden:

```text
Approved Usage
AND User Permission
AND Sensitivity Policy
AND Required Validity
AND Minimum Quality State
```

Danach berechtigte Candidates ranken.

Ein konzeptionelles Ranking Model kann ausgedrückt werden als:

```text
Rank =
  Semantic Relevance
+ Exact Concept Match
+ Approved-Definition Weight
+ Source-Authority Weight
+ Quality Evidence
+ Freshness Fit
+ Temporal Fit
+ Citation Precision
- Known-Limitation Penalty
- Unresolved-Conflict Penalty
```

Die Formel darf keine Scheingenauigkeit erzeugen.

Ihr Zweck ist, Signale explizit und testbar zu machen.

### Primary Context

Primary Context ist die stärkste autoritative Evidence für Frage und Zeitpunkt.

### Supporting Context

Supporting Context ergänzt Examples, Implementation Detail, Local Guidance oder Corroboration, ohne die Primary Source zu ersetzen.

### Excluded

Excluded Sources verletzen ein Hard Gate oder sind irrelevant.

### Conflict Requiring Disclosure

Ein Conflict besteht, wenn zwei berechtigte Quellen materiell unterschiedliche Aussagen machen und Metadata Authority oder Scope nicht auflösen können.

Sie dürfen nicht stillschweigend gemittelt oder verschmolzen werden.

Die Antwort sollte den Conflict benennen, beide Sources zitieren und bei Bedarf eskalieren.

## Lineage und Quality Evidence für Source Ranking nutzen

Lineage kann Trust stärken oder schwächen.

Eine Dashboard Description, die aus einem governten Semantic Model kopiert wurde, kann nützlich sein. Ein Screenshot in einem Ticket ist möglicherweise weniger autoritativ. Ein manuell hochgeladenes Spreadsheet kann dieselben Werte wie ein Certified Dataset enthalten, aber keine aktuelle Refresh Evidence besitzen.

Source Lineage kann unterscheiden:

```text
Original Approved Source
Controlled Derivative
Cached Copy
Manual Extract
Unverified Duplicate
```

Quality Evidence ergänzt den operativen Zustand:

- Freshness passed oder failed;
- Required Tests passed oder failed;
- Schema Drift detected;
- Reconciliation completed;
- Incident open;
- Certification expired;
- Known Limitation acknowledged.

Eine Source sollte keinen permanent hohen Rank behalten, wenn ihre Quality Evidence veraltet.

Nützliche Attribute sind:

```yaml
quality_evidence_id: dq-run-2026-07-25-0600
quality_state: passed
evidence_valid_until: 2026-07-26T06:00:00Z
open_incidents: []
certification_valid_until: 2026-09-30
```

## Citations und Explainability bewusst entwerfen

Citations sind kein Formatting Feature, das nach Generation ergänzt wird.

Sie hängen von Ingestion- und Metadata Design ab.

Eine verlässliche Citation benötigt:

- Stable Asset ID;
- Version;
- Exact Source Locator;
- Source Title;
- Authoritative System;
- Access-safe Display Link;
- Retrieval Timestamp;
- Content Checksum oder vergleichbare Integrity Reference.

Für Structured Data kann die Citation benötigen:

- Dataset und Version;
- Query oder Metric Definition;
- Time Range;
- Freshness Timestamp;
- Applied Filters;
- Aggregation Grain.

Eine Antwort wie:

```text
Der Umsatz betrug 4,2 Mio. €.
```

wird nicht dadurch erklärt, dass sie auf die Startseite eines Dashboards verlinkt.

Ein brauchbarer Evidence Record beschreibt:

```yaml
metric: recognized-revenue
semantic_model_version: finance-metrics@12.4
time_range: 2026-06-01/2026-06-30
filters:
  legal_entity: DE01
query_executed_at: 2026-07-25T08:14:22Z
data_freshness: 2026-07-25T06:00:00Z
result: 4200000
currency: EUR
```

Explainability sollte die Selection abdecken, nicht nur die Generation.

Das System sollte erklären können:

```text
Diese Quelle wurde ausgewählt, weil sie freigegeben,
für das angefragte Datum gültig,
innerhalb des User Entitlements
und durch aktuelle Quality Checks bestätigt war.
```

## Temporal Validity und Version durch die gesamte Pipeline erhalten

Time Metadata geht bei Indexing häufig verloren.

Ein Document Repository besitzt eine Version History. Der Ingestion Process extrahiert nur den neuesten Text. Der Vector Index speichert eine Kopie. Die alte Version wird gelöscht. Historische Fragen können nicht mehr korrekt beantwortet werden.

Ein sichereres Design hält versionierte Identities vor:

```text
Logical Asset: revenue-recognition-policy
Versioned Asset: revenue-recognition-policy@3.8
Versioned Asset: revenue-recognition-policy@4.2
```

Jede Version trägt:

- Publication Time;
- Effective Interval;
- Supersession Relationship;
- Approval State;
- Checksum;
- Source Locator;
- Extraction Version;
- Index Status.

Der Retrieval Request sollte einen Temporal Intent enthalten:

```yaml
temporal_intent:
  type: valid-at
  timestamp: 2025-11-15
```

Wenn kein Datum angegeben wird, kann die Policy aktuell gültige Sources bevorzugen. Die Annahme sollte trotzdem im Retrieval Trace sichtbar bleiben.

## Verhindern, dass sensitive oder nicht freigegebene Inhalte in Prompts gelangen

Security für AI Context benötigt mehr als Access Control auf Index-Ebene.

Der vollständige Pfad muss betrachtet werden:

```text
Source
→ Ingestion
→ Parsing
→ Chunking
→ Embedding
→ Index
→ Retrieval
→ Prompt Assembly
→ Model
→ Logs und Traces
→ Answer Cache
```

Sensitive Content kann auf jeder Stufe leaken.

Controls sollten enthalten:

### Source Admission

Nur freigegebene Repositories und Datasets dürfen in die Pipeline gelangen.

### Classification Propagation

Document- und Dataset-Classifications müssen auf Chunks, Embeddings, Derived Summaries und Caches propagiert werden, sofern keine freigegebene Regel sie ändert.

### Least-Privilege Ingestion

Die Ingestion Identity darf nur auf benötigte Sources und Fields zugreifen.

### Entitlement-aware Retrieval

User- und Service-Entitlements müssen angewendet werden, bevor Content zurückgegeben wird.

### Purpose Control

`Allowed Usage` und `Training Permission` müssen getrennt von Read Access bewertet werden.

### Prompt-Boundary Control

Der Context Builder darf nur berechtigten Content erhalten.

### Safe Logging

Logs sollten Raw Sensitive Prompts, Context und Responses nicht speichern, sofern dies nicht explizit erforderlich und geschützt ist.

### Revocation und Deletion

Das System benötigt einen Prozess, um Content aus Indexes, Caches, Evaluation Sets und zukünftigen Training Runs zu entfernen oder zu deaktivieren, wenn sich Permissions ändern.

### Prompt-Injection Handling

Retrieved Content muss als untrusted Data behandelt werden, nicht als System Instruction. Provenance und Source Type können beeinflussen, wie Content isoliert und interpretiert wird. Metadata ersetzt jedoch keine Runtime Prompt-Injection Defenses.

## Alternative Architecture Patterns

Keine einzelne AI Metadata Architecture passt für jede Organisation.

### Source-native Retrieval

Der AI Service fragt eine governte Source direkt ab.

Geeignet, wenn:

- der Use Case eng begrenzt ist;
- eine Plattform autoritativ ist;
- Native Permissions stark sind;
- kein Cross-System Ranking benötigt wird.

Vorteile:

- geringe Duplication;
- aktuelle Permissions;
- einfache Ownership;
- tiefe Native Metadata.

Warnung:

- mehrere Domains lassen sich schwer kombinieren;
- begrenzte Cross-Source Lineage;
- plattformspezifisches Search- und Citation-Verhalten.

### Central Metadata Index mit Distributed Content

Ein zentraler Service speichert Searchable Metadata und Source References, während Content bis zum Retrieval in den Source Systems bleibt.

Geeignet, wenn:

- Enterprise Discovery benötigt wird;
- Content nicht breit kopiert werden soll;
- Sources zuverlässige APIs bereitstellen;
- Permission zur Runtime geprüft werden kann.

Vorteile:

- kleinerer Sensitive-Data Footprint;
- zentraler Ranking Context;
- verteilte Source Authority.

Warnung:

- Runtime Latency und Source Availability sind relevant;
- Identity Mapping muss zuverlässig sein;
- Citations benötigen Stable Source Locators.

### Central Knowledge Index

Freigegebener Content und Metadata werden in einen kontrollierten Retrieval Index kopiert.

Geeignet, wenn:

- Low-Latency Retrieval erforderlich ist;
- Source APIs schwach sind;
- Version History erhalten werden muss;
- ein begrenzter Corpus governbar ist.

Vorteile:

- planbare Performance;
- kontrolliertes Chunking und Indexing;
- reproduzierbare Versions;
- konsistentes Retrieval Interface.

Warnung:

- Permissions und Deletions können veralten;
- kopierter Content erzeugt einen zweiten kontrollierten Store;
- Synchronization und Evidence sind verpflichtend.

### Federated Retrieval

Mehrere Domain Retrieval Services liefern autorisierte Evidence an einen Orchestrator.

Geeignet, wenn:

- Domains starke Local Ownership besitzen;
- Content nicht zentralisiert werden kann;
- unterschiedliche Asset Types spezialisiertes Retrieval benötigen;
- Enterprise Questions mehrere Domains übergreifen.

Vorteile:

- lokale Authority und Controls;
- Specialized Retrieval;
- weniger Central Copying.

Warnung:

- Ranking über mehrere Services ist schwierig;
- Latency und Partial Failure müssen behandelt werden;
- gemeinsame Identity-, Citation- und Evidence-Contracts sind erforderlich.

### Curated Knowledge Products

Eine Domain veröffentlicht ein governtes Package speziell für AI Consumption.

Das Package kann enthalten:

```text
Approved Documents
+ Governed Datasets
+ Semantic Definitions
+ Retrieval Metadata
+ Permissions
+ Quality Evidence
+ Citation Contract
```

Geeignet, wenn:

- hochwertige Domains planbare Answers benötigen;
- Source Complexity vor Consumers verborgen werden soll;
- die Domain ein Service Level verantworten kann.

Warnung:

- Curation kann manuell und stale werden;
- das Package muss mit Source Changes und Lineage verbunden bleiben.

### Training Registry verbunden mit Feature- und Model-Governance

Training Datasets, Feature Sets, Model Runs und Approvals werden in verbundenen Registries oder Metadata Services versioniert.

Geeignet, wenn:

- Models wiederholt trainiert werden;
- mehrere Teams Features oder Datasets wiederverwenden;
- Regulatory oder Audit Reconstruction benötigt wird;
- Deployment von Approved Evidence abhängt.

Warnung:

- Namen ohne Immutable Versions und Lineage zu registrieren erzeugt Inventory, nicht Reproducibility.

## Häufige Anti-Patterns

### Zuerst alles indexieren

Ein breiter Crawl erzeugt Coverage, importiert aber auch Drafts, Duplicates, Expired Documents, Sensitive Data und unklare Authority.

Admission- und Exclusion-Rules müssen vor dem Indexing definiert werden.

### Embeddings als Metadaten behandeln

Embeddings kodieren statistische Ähnlichkeit. Sie ersetzen Owner, Approval, Validity, Permission, Quality, Source Authority oder Permitted Use nicht.

### Permissions nur auf Document Level speichern

Ein Dokument kann Sections, Tables oder Attachments mit unterschiedlichen Restrictions enthalten. Derived Chunks und Summaries benötigen inherited oder overridden Classifications.

### Source Access Lists einmal kopieren

Permissions ändern sich. Eine statische Kopie ohne Freshness, Reconciliation und Failure Behaviour wird unsicher.

### Ein einziges `approved`-Flag verwenden

Approval für Publication, Retrieval, Analytics, Training, External Sharing und Production Deployment sind unterschiedliche Entscheidungen.

### Die neueste Quelle bevorzugen

Neu kann Draft, Local Guidance oder Unapproved Interpretation bedeuten. Effective Time und Authority müssen bewertet werden.

### Einen generischen Quality Score verwenden

Ein hoher Score kann eine fehlgeschlagene Mandatory Rule verbergen. Quality Dimensions und Hard Failures müssen erhalten bleiben.

### Parent Document beim Chunking verlieren

Ein Chunk ohne Heading, Version, Document Status und Locator ist schwer zu interpretieren und zu zitieren.

### Das Model Source Conflicts still auflösen lassen

Conflicts sind Governance Information. Sie müssen erhalten und offengelegt werden.

### Nur das fertige Model registrieren

Ohne Dataset-, Feature-, Code-, Evaluation- und Approval-Lineage kann das Model nicht effektiv reproduziert oder governt werden.

### Training Permission aus Internal Access ableiten

Internal Access beweist weder Consent, Contractual Permission, Copyright Suitability noch Approved Purpose für Training.

### Jeden Prompt und Context standardmäßig loggen

Detaillierte Logs können einen zweiten sensitiven Corpus erzeugen. Nur die für Operation, Testing und Audit erforderliche Evidence sollte gespeichert werden.

## Entscheidungshilfe

Vor Auswahl eines Implementation Patterns oder Produkts sollten folgende Fragen beantwortet werden.

### Scope

- Welche Questions, Decisions oder Model Purposes sind im Scope?
- Welche Domains und Jurisdictions gelten?
- Welche Asset Types müssen enthalten sein?
- Welcher Content ist explizit ausgeschlossen?

### Authority

- Welches System ist für jedes Metadata Attribute autoritativ?
- Wie werden Duplicates und Derivatives identifiziert?
- Was macht eine Source primary und eine andere supporting?
- Wer löst Conflicts?

### Zeit

- Müssen historische Fragen beantwortet werden?
- Wie werden Effective Intervals modelliert?
- Wie schnell müssen Source Changes das AI-System erreichen?
- Was geschieht, wenn Freshness Evidence fehlt?

### Permission

- Welche Identity wird zur Retrieval Time verwendet?
- Werden Permissions auf Document-, Row-, Field- oder Chunk-Level bewertet?
- Wie werden Allowed Usage und Training Permission repräsentiert?
- Schlägt das System bei Sensitive Content geschlossen fehl?

### Trust

- Welche Approval- und Quality States sind verpflichtend?
- Kann Certification ablaufen?
- Wie beeinflussen Incidents Ranking oder Eligibility?
- Ist Source Lineage verfügbar und versioniert?

### Evidence

- Kann jede Antwort exakte Source und Version zitieren?
- Kann ein Training Run rekonstruiert werden?
- Werden Ranking- und Exclusion-Reasons dokumentiert?
- Kann Sensitive Evidence erhalten werden, ohne Full Content zu kopieren?

### Operations

- Wer verantwortet Ingestion, Metadata Quality und Policy Rules?
- Wie werden Deletions und Revocations propagiert?
- Wie werden Extraction-, Chunking- und Embedding-Changes versioniert?
- Wie werden False Retrievals, Stale Context und Permission Failures gemessen?

## Zentrale Empfehlungen

1. AI Context Selection als governte Entscheidung behandeln, nicht als reine Similarity Search.
2. Ein logisches Metadata Package für Bedeutung, Struktur, Trust, Time, Permission, Retrieval und Evidence aufbauen.
3. Document-, Chunk-, Dataset-, Feature- und Model-Profiles type-specific halten.
4. Stable Identifiers, Versions und Provenance von Source bis Answer oder Model erhalten.
5. RAG Suitability, Allowed Usage, Training Permission und External-Model Usage trennen.
6. Permission und verpflichtende Validity Rules vor Content Retrieval anwenden.
7. Abgelehnte Sources außerhalb des Prompt Context halten.
8. Metadata Filters, Exact Search, Semantic Search, Graph Traversal und Governed Queries kombinieren.
9. Nur berechtigte Sources anhand von Relevance, Authority, Quality, Freshness und Temporal Fit ranken.
10. Nicht annehmen, dass die neueste Quelle die autoritativste Quelle ist.
11. Conflicts erhalten und offenlegen, statt sie still zusammenzuführen.
12. Parent Document, Section, Locator und Version an jedem Chunk erhalten.
13. Sensitivity und Usage Restrictions auf Chunks, Embeddings, Summaries und Caches propagieren.
14. Lineage und aktuelle Quality Evidence nutzen, um Source Trust zu stärken oder zu reduzieren.
15. Citations aus stabiler Source Evidence erzeugen, nicht allein aus Display Titles.
16. Chunking-, Extraction-, Embedding- und Ranking-Policies versionieren.
17. Training Datasets, Features, Model Runs, Evaluations, Approvals und Deployments über versionierte Lineage verbinden.
18. Event Time und Availability Time dokumentieren, um Feature Leakage zu erkennen.
19. Approvals auf konkrete Asset Version und Intended Purpose begrenzen.
20. Mit einer begrenzten Domain und einem messbaren Question Set beginnen.
21. Permission Failures, Stale Metadata, Conflicting Sources, Historical Questions und Revocation vor Production testen.
22. Genug Metadata zur Rekonstruktion von Decisions loggen, ohne eine unnötige Kopie sensitiver Inhalte zu erzeugen.
23. Product-specific Metadata Filters, Permission Propagation, Citation Payloads, APIs, Limits und Licensing in einem Proof of Value verifizieren.
24. Den Corpus erst erweitern, wenn Admission, Exclusion, Ranking und Evidence Controls zuverlässig funktionieren.

> **AI wird vertrauenswürdiger, wenn das System nicht nur nachweisen kann, was es beantwortet hat, sondern warum dieser Kontext für diesen User, Purpose und Zeitpunkt berechtigt, autoritativ und gültig war.**

## Als Nächstes: AI zur Verbesserung von Metadaten einsetzen

Die Vorbereitung von Metadaten für AI erzeugt eine strukturierte Control Layer:

```text
Meaning
+ Relationships
+ Provenance
+ Quality
+ Validity
+ Permission
+ Evidence
```

Dieselbe Grundlage kann auch in die Gegenrichtung verwendet werden.

AI kann Descriptions vorschlagen, Synonyms erkennen, Classifications identifizieren, Business Terms zuordnen, Owner vorschlagen, Lineage zusammenfassen, Conflicts finden und fehlende Metadaten priorisieren.

Diese Proposals sind nur nützlich, wenn State und Evidence explizit bleiben.

Part 16 untersucht deshalb, **wie AI Metadaten verbessern kann**, ohne generierte Vorschläge zu einer ungeprüften zweiten Wahrheit zu machen.

Dabei werden getrennt:

- detected von declared Metadata;
- inferred von approved Values;
- Proposal Confidence von Authority;
- Automated Enrichment von Human Accountability;
- hilfreiche Assistance von unkontrollierter Metadata Generation.
