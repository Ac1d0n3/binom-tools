---
title: "Dieselbe Entity, zwei Systeme — welche Quelle ist autoritativ?"
description: "Autorität nach Entity, Attribut, Event und Zeitkontext vergeben, Identity Matching von Survivorship und Publishing trennen und Provenance sowie governte Ausnahmen über mehrere Quellen erhalten."
author: Thomas Lindackers
tags:
  - Multi-source Governance
  - Data Authority
  - Identity Resolution
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/multi-source-entity-authority-hero.png
series: source-load-decisions
seriesTitle: Lade-Entscheidungen für Quellsysteme
seriesPart: 9
---

Wenn derselbe Customer, Supplier, Worker, Product oder Account in mehreren Systemen existiert, ist „das Master-System wählen“ meist zu grob. CRM, Billing, Service, Identity und Consent können unterschiedliche Attribute, Events und Zeitkontexte besitzen.

Die governte Entscheidung vergibt Authority deshalb auf der Ebene, auf der die Bedeutung tatsächlich variiert: Entity Identity, Attribute Group, Business Event, Effective Period und Analytical Use.

## Problem

Zwei Records mit demselben Namen stellen nicht automatisch dieselbe Entity dar. Zwei Systeme mit demselben Attribut besitzen nicht automatisch dieselbe Autorität. Ein CRM kann die Sales Relationship besitzen, ein ERP Legal Account und Invoice Balance, eine Service Platform den Support State und eine Consent Platform die Communication Preference.

![Gleicher Name bedeutet nicht gleiche Autorität](images/playbooks/multi-source-entity-authority-img1-de.png)

Breite Aussagen erzeugen versteckte Konflikte:

- „CRM ist der Customer Master“ ignoriert Legal-, Billing-, Service- und Consent-Fakten.
- „Der neueste Timestamp gewinnt“ verwechselt technische Freshness mit Business Authority.
- „Ein Golden Record“ kann legitime kontextuelle Unterschiede verdecken.
- automatisches Matching kann unterschiedliche Entities zusammenführen.
- Last-write-wins kann einen korrigierten autoritativen Wert durch eine spätere Replik überschreiben.
- Downstream-Teams erfinden unterschiedliche Precedence Rules und publizieren widersprüchliche Kennzahlen.

Authority beantwortet für jeden relevanten Fakt vier Fragen:

1. Welches System erzeugt den Fakt?
2. Welche Rolle oder welches System darf ihn korrigieren?
3. Welcher Zeitkontext gilt?
4. Welche Downstream-Entscheidungen dürfen ihn nutzen?

### Vier Source Roles unterscheiden

- **System of Entry:** wo Nutzer oder Prozess den Wert erstmals erfassen.
- **System of Record:** wo die Organisation den accountable Operational State pflegt.
- **System of Reference:** governte Quelle zur Standardisierung oder Anreicherung anderer Systeme.
- **Analytical Trusted Source:** freigegebene Repräsentation für einen analytischen Kontext.

Diese Rollen können identisch sein, müssen es aber nicht.

## Decision

### 1. Entity und Matching Boundary definieren

Beschreibe Business Meaning, enthaltene Populationen und ausgeschlossene Subtypen. Definiere Identity Keys, Crosswalk Keys und Matching Scope, bevor entschieden wird, welche Attribute überleben.

Identity Resolution beantwortet nur, ob Records dieselbe Business Entity repräsentieren. Sie darf nicht stillschweigend festlegen, welcher Wert vertraut wird.

### 2. Authority nach Attribut, Event und Zeit vergeben

![Autorität nach Attribut, Event und Zeit vergeben](images/playbooks/multi-source-entity-authority-img2-de.png)

Erstelle eine Authority Matrix für beispielsweise:

- Legal Customer Identity;
- Preferred Contact Details;
- Consent und Communication Preference;
- Sales Ownership;
- Active Contract Status;
- Invoice Balance;
- Service Entitlement;
- Support-Case State.

Dokumentiere je Zeile Source of Entry, Authoritative Source, Analytical Trusted Source, Effective Date, Freshness Expectation, Conflict Rule, Fallback und Owner.

Authority hängt außerdem vom Zeitkontext ab:

- Current Operational State;
- Historically Effective State;
- Event State at Transaction Time;
- Corrected Restatement.

„Latest Record Wins“ ist nur valide, wenn es für dieses Attribut und diesen Zeitkontext explizit freigegeben wurde.

### 3. Match, Survive und Publish trennen

![Overlap mit Match, Survivorship und Lineage auflösen](images/playbooks/multi-source-entity-authority-img3-de.png)

Nutze einen governten Flow:

```text
Source Records
→ Standardize Keys
→ Match und Identity Resolution
→ Confidence- und Exception-Gate
→ Attribute Survivorship
→ Conformed Entity oder Source-Specific Views
→ Downstream Products
```

Die drei Entscheidungen sind getrennt:

1. **Match:** Stellen die Records dieselbe Entity dar?
2. **Survive:** Welcher Wert wird je Attribut und Zeit vertraut?
3. **Publish:** Erhalten Consumer eine Conformed Entity oder mehrere Contextual Views?

Hohe Match Confidence autorisiert nicht automatisch Survivorship. Eine Conformed Entity ist nur sinnvoll, wenn gemeinsame Identität und Attributregeln stabil sind. Source-Specific Views bleiben erhalten, wenn Bedeutungen legitim abweichen oder Conflict Resolution kontextabhängig bleibt.

### 4. Conflict-, Fallback- und Correction-Regeln definieren

Für jedes governte Attribut oder Event werden festgelegt:

- Precedence zwischen Quellen;
- wann ein Fallback erlaubt ist;
- maximal tolerierte Latency;
- Behandlung von Null, Stale und Invalid Values;
- Propagation von Corrections und Restatements;
- Aufbewahrung ungelöster Konflikte;
- Owner für Exceptions.

Verlierende Werte werden nicht gelöscht. Provenance bleibt erhalten, damit die Entscheidung erklärbar und reversibel ist.

### 5. Identity Exceptions governen

Pflicht-Controls sind:

- Source-to-Conformed Crosswalk Keys;
- Match Confidence und Reason Codes;
- Manual-Review Threshold;
- Merge- und Split-History;
- Unresolved-Duplicate Queue;
- False-Positive Remediation;
- Downstream Impact Analysis vor Identity Changes;
- SLA und Escalation Owner für Steward Review.

Mehrdeutige Matches bleiben ungelöst, statt erzwungen in eine Entity überführt zu werden.

### 6. Provenance und Effective Dating erhalten

Jeder publizierte Wert enthält genügend Evidenz für:

- beitragende Quelle und Source Key;
- Source Extraction und Event Time;
- Authority-Rule-Version;
- Effective Start und End;
- Conflict- oder Fallback-Status;
- Correction- und Review-History.

Ohne Provenance wird Survivorship zu einem irreversiblen Überschreiben.

### 7. Downstream Migration kontrollieren

Eine Änderung der Authority Rule kann Identifier, Dimensions, Filter und historische Kennzahlen verändern. Behandle sie als Consumer-Contract-Migration. Identifiziere betroffene Data Products, vergleiche alte und neue Ergebnisse, kommuniziere das Effective Date und erhalte die vorherige Regel lange genug für Reconciliation.

## Checklist

### Entity und Identity

- [ ] Die Entity besitzt Business Definition und explizite Population.
- [ ] Identity Keys und Matching Scope sind freigegeben.
- [ ] Crosswalk Keys erhalten jede Source Identity.
- [ ] Match Confidence und Review Thresholds sind definiert.
- [ ] Merge-, Split- und Unresolved-Duplicate-Verhalten ist dokumentiert.

### Authority und Survivorship

- [ ] Authority ist je Attribut oder Event vergeben.
- [ ] Current-, Historical-, Transaction-Time- und Restated-Kontexte sind getrennt.
- [ ] Precedence-, Fallback- und Latency-Regeln sind explizit.
- [ ] Null-, Stale-, Invalid- und Conflict-Values besitzen Regeln.
- [ ] Business Owner und Steward genehmigen die Matrix.

### Publishing und Lineage

- [ ] Die Wahl zwischen Conformed Entity und Contextual Views ist explizit.
- [ ] Provenance bleibt je publiziertem Wert erhalten.
- [ ] Authority-Rule-Versionen und Effective Dates sind abfragbar.
- [ ] Corrections propagieren in betroffene Produkte.
- [ ] Consumer Migration und Reconciliation sind geplant.

### Exceptions

- [ ] Eine Exception Queue existiert.
- [ ] Steward SLA und Escalation Owner sind benannt.
- [ ] Temporäre Fallbacks besitzen Expiry und Evidence.
- [ ] False-Positive Merges sind reversibel.
- [ ] Review Trigger decken Source-, Policy- und Prozessänderungen ab.

## Artifact

Erstelle einen governten Authority Record mit vier verknüpften Bereichen.

![Die Entity-Authority-Entscheidung dokumentieren](images/playbooks/multi-source-entity-authority-img4-de.png)

### Entity Definition

- Entity Name und Business Meaning;
- Identity Keys und Matching Scope;
- Included Populations;
- Excluded oder Separate Entity Types.

### Authority Matrix

| Attribut oder Event | Source of Entry | Authoritative Source | Analytical Trusted Source | Zeitkontext | Precedence oder Fallback | Owner |
|---|---|---|---|---|---|---|
| Legal Identity | CRM oder Onboarding | Billing / ERP | Conformed Customer View | Effective-Dated | ERP außer freigegebener Correction | Customer Data Owner |
| Consent | Consent Platform | Consent Platform | Consent-Controlled Consumer View | Event und Current | Kein Fallback ohne Freigabe | Privacy Owner |
| Invoice Balance | ERP | ERP | Finance Fact | Posting Period | Kein CRM Override | Finance Data Owner |
| Support State | Service Platform | Service Platform | Service Fact | Event und Current | Source-Specific | Service Owner |

### Exception Rules

Dokumentiere Match-Confidence Threshold, Conflict Type, Steward Queue, Review SLA, Escalation Owner und jeden temporären Fallback mit Expiry.

### Downstream Contract

Dokumentiere Conformed Key, Provenance Fields, Freshness- und Quality Controls, betroffene Products, Change Plan und Review Trigger.

Erforderliche Outputs sind freigegebene Authority Matrix, Crosswalk- und Match Policy, Survivorship Rules, Exception Queue, Lineage Requirement und Consumer-Migration-Actions. Matching- oder Formula-Tools können die Entscheidung implementieren, aber keine Business Authority vergeben.

## Tools

Nutze den [Source Scope Builder](/tools/source-scope-builder), um den Beitrag jeder Quelle und konkurrierende Repräsentationen zu dokumentieren. Nutze den [Metadata Export Generator](/tools/meta-export-generator), um Authority, Precedence, Provenance und Review Metadata in wiederverwendbare Contracts zu publizieren.

## Resources

- Data Dictionaries und Source Contracts der Quellsysteme.
- Identity Crosswalk und Duplicate-Management Records.
- Data-Ownership- und Stewardship-Decision-Rights-Modell.
- Consumer Inventory und Lineage Graph.
- Correction-, Merge-, Split- und Incident History.

## Playbooks

- [Vor dem Bau der ersten Tabelle](/playbooks/before-building-the-first-table) — Entscheidung, Grain und Acceptance Criteria vor dem Conformed Product definieren.
- [Data Ownership and Stewardship](/playbooks/data-ownership-stewardship) — Authority, Conflict Resolution, Approval und Escalation Rights vergeben.

## Next step

Gib Entity Definition und Authority Matrix frei, bevor Matching-, Survivorship- oder Golden-Record-Logik gebaut wird. Implementiere die Regeln anschließend als versionierte Metadata mit Lineage und Exception Workflow. Damit schließt die Source-Load-Decisions-Serie die Verbindung zwischen Source Onboarding und Cross-Source Authority.
