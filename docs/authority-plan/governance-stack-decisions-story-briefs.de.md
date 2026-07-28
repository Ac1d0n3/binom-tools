# Serie `governance-stack-decisions` — Story-Plan

Stand: 2026-07-28  
Serie EN: **Governance Stack Decisions** · DE: **Governance-Stack-Entscheidungen**

Struktur: **Chooser → Plattform-Einstiege → dbt Control Layer → Multi-Platform**.  
Kein pairwise „A vs B“-Duell mehr (`fabric-vs-databricks-governance-start` entfernt).

Bodies schreibst du woanders. Nach Ablage: Hub Stack-Journey / Guides Decisions verdrahten (Chooser zuerst).

---

## Serien-Übersicht

| Part | Slug (Vorschlag) | Titel EN | Titel DE | Status |
|------|------------------|----------|----------|--------|
| 1 | `choose-governance-starting-point` | Fabric, Databricks, Snowflake or BigQuery — Choose the Governance Starting Point | Fabric, Databricks, Snowflake oder BigQuery — Den Governance-Einstieg auswählen | brief |
| 2 | `microsoft-fabric-governance-starting-point` | Microsoft Fabric as a Governance Starting Point | Microsoft Fabric als Governance-Einstieg | brief |
| 3 | `databricks-unity-catalog-governance-starting-point` | Databricks and Unity Catalog as a Governance Starting Point | Databricks und Unity Catalog als Governance-Einstieg | brief |
| 4 | `snowflake-governance-starting-point` | Snowflake as a Governance Starting Point | Snowflake als Governance-Einstieg | brief |
| 5 | `bigquery-governance-starting-point` | BigQuery as a Governance Starting Point | BigQuery als Governance-Einstieg | brief |
| 6 | `dbt-cross-platform-governance-control-layer` | dbt as a Cross-Platform Governance Control Layer | dbt als plattformübergreifende Governance-Kontrollschicht | brief |
| 7 | `governance-across-multiple-data-platforms` | Governance Across Multiple Data Platforms | Governance über mehrere Datenplattformen hinweg | brief |

---

## Part 1 — Chooser

| Feld | Inhalt |
|------|--------|
| **Slug** | `choose-governance-starting-point` |
| **Decision-Frage** | Nach welchen Governance-Kriterien wählt man den Einstieg — ohne Feature-Matrix und ohne Vendor-Duell? |
| **Fokus** | Ownership, Catalog, PII/Access, Lineage, Qualität, BI, Betriebsnachweise; Entscheidungsrahmen + Verweise auf Parts 2–5 |
| **Abgrenzung** | Kein Pairwise-Vergleich; keine Plattform-Tiefe (das sind Parts 2–5); kein Ersatz für `choosing-the-simplest-viable-architecture` (allgemeine Architektur) |
| **Tools** | governance-stack-advisor, architecture-fit |
| **Playbooks** | `eight-pillars`, Parts 2–5 nach Ablage |
| **Hub** | Stack Journey + Guides „Welcher Stack passt?“ (primärer SEO-/Hub-Einstieg) |

## Parts 2–5 — Plattform-Einstiege

Gemeinsames Muster pro Plattform: gleiche Hub-Regel (Problem → Entscheidung → Checkliste → Artefakt → Tools → …), Kriterien aus Part 1 anwenden, native Control Points nennen.

| Part | Fokus | Nicht nochmal schreiben |
|------|--------|-------------------------|
| 2 Fabric | Workspaces, Purview/OneLake-Anschluss, Semantic Models vs. Kennzahlen-Governance | `dq-test2` (DQ-Ops Fabric) nur verlinken |
| 3 Databricks + Unity | Unity Catalog als Einstieg; Ownership/Access/Lineage | `dq-test4` (DQ Databricks) nur verlinken |
| 4 Snowflake | Roles, Policies, Account-Struktur als Governance-Einstieg | `snowflake-masking-policies-qlik-section-access` nur verlinken |
| 5 BigQuery | Projekte/Datasets, IAM, Policy Tags als Einstieg | — |

## Part 6 — dbt Control Layer

| Feld | Inhalt |
|------|--------|
| **Decision-Frage** | Wann ist dbt die plattformübergreifende Governance-Kontrollschicht — und was bleibt plattform-nativ? |
| **Abgrenzung** | `metadata-driven-governance-with-dbt-meta` bleibt die Praxis-Story zu `meta`/schema.yml — hier nur **Rolle im Stack**, nicht Felder wiederholen |
| **Playbooks** | `metadata-driven-governance-with-dbt-meta`, Parts 1–5 |

## Part 7 — Multi-Platform

| Feld | Inhalt |
|------|--------|
| **Decision-Frage** | Wie betreibt man Governance, wenn mehrere Plattformen parallel laufen — ohne doppelte Wahrheit? |
| **Abgrenzung** | `dq-test5` (eine Regel, drei Plattformen) nur verlinken; Fokus Operating Model, Ownership, Evidence über Grenzen |
| **Playbooks** | Part 1 + 6, `eight-pillars` |

---

## Frontmatter-Vorlage

```yaml
series: governance-stack-decisions
seriesTitle: Governance Stack Decisions   # DE: Governance-Stack-Entscheidungen
seriesPart: N
author: Thomas Lindackers
```

## Schreib-Reihenfolge

1. Part 1 Chooser (Hub/SEO-Einstieg)  
2. Parts 2–5 Plattformen (Reihenfolge nach Bedarf; Fabric/Databricks oft zuerst)  
3. Part 6 dbt  
4. Part 7 Multi-Platform  

## Veraltet (nicht mehr planen)

- ~~`fabric-vs-databricks-governance-start`~~ — entfernt; ersetzt durch Chooser + Einzel-Einstiege  
- ~~pairwise Snowflake+dbt vs Fabric, Purview vs Unity als eigene Serie-Parts~~ — Catalog-Tiefe in Parts 2–3 integrieren, nicht als Duell
