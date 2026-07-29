# Serie `governance-platform-starting-points` — Story-Plan

Stand: 2026-07-29  
Serie EN: **Governance Platform Starting Points** · DE: **Governance-Einstiegspunkte für Datenplattformen**  
Plan-Dateiname historisch: `governance-stack-decisions-story-briefs.de.md` (Inhalt spiegelt **reale** Serie/Slugs).

Struktur: **Chooser → Plattform-Einstiege → dbt Control Layer → Multi-Platform**.  
Kein pairwise „A vs B“-Duell (`fabric-vs-databricks-governance-start` entfernt).

Zurück: [phase-b-story-briefs.de.md](phase-b-story-briefs.de.md)

---

## Serien-Übersicht (reale Slugs)

| Part | Slug | Titel EN | Titel DE | Status |
|------|------|----------|----------|--------|
| 1 | `choose-governance-platform-starting-point` | Fabric, Databricks, Snowflake or BigQuery — Choose the Governance Starting Point | Fabric, Databricks, Snowflake oder BigQuery — Den Governance-Einstieg auswählen | live · Hub verdrahtet |
| 2 | `microsoft-fabric-governance-start` | Microsoft Fabric as a Governance Starting Point | Microsoft Fabric als Governance-Einstieg | live · Guides optional |
| 3 | `databricks-unity-catalog-governance-start` | Databricks and Unity Catalog as a Governance Starting Point | Databricks und Unity Catalog als Governance-Einstieg | live · Serie-UI |
| 4 | `snowflake-governance-start` | Snowflake as a Governance Starting Point | Snowflake als Governance-Einstieg | live · Serie-UI |
| 5 | `bigquery-governance-start` | BigQuery as a Governance Starting Point | BigQuery als Governance-Einstieg | live · Serie-UI |
| 6 | `dbt-governance-control-layer` | dbt as a Cross-Platform Governance Control Layer | dbt als plattformübergreifende Governance-Kontrollschicht | live · Serie-UI |
| 7 | `governance-across-multiple-data-platforms` | Governance Across Multiple Data Platforms | Governance über mehrere Datenplattformen hinweg | live · Serie-UI |

---

## Hub-Verdrahtung (Einstiege)

- Stack Journey: Chooser `choose-governance-platform-starting-point`
- Guides Decision „Welcher Stack passt?“: Chooser + optional Fabric `microsoft-fabric-governance-start`
- Weitere Parts nur über Playbook-Series-Navigation

---

## Part 1 — Chooser

| Feld | Inhalt |
|------|--------|
| **Slug** | `choose-governance-platform-starting-point` |
| **Decision-Frage** | Nach welchen Governance-Kriterien wählt man den Einstieg — ohne Feature-Matrix und ohne Vendor-Duell? |
| **Fokus** | Ownership, Catalog, PII/Access, Lineage, Qualität, BI, Betriebsnachweise; Entscheidungsrahmen + Verweise auf Parts 2–5 |
| **Abgrenzung** | Kein Pairwise-Vergleich; keine Plattform-Tiefe (Parts 2–5); kein Ersatz für `choosing-the-simplest-viable-architecture` |
| **Tools** | governance-stack-advisor, architecture-fit |
| **Playbooks** | `eight-pillars`, Parts 2–7 |

## Parts 2–5 — Plattform-Einstiege

| Part | Fokus | Nicht nochmal schreiben |
|------|--------|-------------------------|
| 2 Fabric | Workspaces, Purview/OneLake-Anschluss, Semantic Models vs. Kennzahlen-Governance | `dq-test2` nur verlinken |
| 3 Databricks + Unity | Unity Catalog als Einstieg; Ownership/Access/Lineage | `dq-test4` nur verlinken |
| 4 Snowflake | Roles, Policies, Account-Struktur | `snowflake-masking-policies-qlik-section-access` nur verlinken |
| 5 BigQuery | Projekte/Datasets, IAM, Policy Tags | — |

## Part 6 — dbt Control Layer

| Feld | Inhalt |
|------|--------|
| **Slug** | `dbt-governance-control-layer` *(nicht `dbt-cross-platform-governance-control-layer`)* |
| **Decision-Frage** | Wann ist dbt die plattformübergreifende Governance-Kontrollschicht — und was bleibt plattform-nativ? |
| **Abgrenzung** | `metadata-driven-governance-with-dbt-meta` bleibt Praxis zu `meta`/schema.yml — hier nur **Rolle im Stack** |
| **Playbooks** | `metadata-driven-governance-with-dbt-meta`, Parts 1–5 |

## Part 7 — Multi-Platform

| Feld | Inhalt |
|------|--------|
| **Decision-Frage** | Wie betreibt man Governance, wenn mehrere Plattformen parallel laufen — ohne doppelte Wahrheit? |
| **Abgrenzung** | `dq-test5` nur verlinken; Fokus Operating Model, Ownership, Evidence über Grenzen |
| **Playbooks** | Part 1 + 6, `eight-pillars` |

---

## Frontmatter (Ist)

```yaml
series: governance-platform-starting-points
seriesTitle: "Governance Platform Starting Points"  # DE-Datei: lokalisiert
seriesPart: N
author: Thomas Lindackers
```

## Veraltete Brief-Namen (nicht verwenden)

- Serie-ID ~~`governance-stack-decisions`~~ → `governance-platform-starting-points`
- ~~`choose-governance-starting-point`~~ → `choose-governance-platform-starting-point`
- ~~`microsoft-fabric-governance-starting-point`~~ → `microsoft-fabric-governance-start` (analog Databricks/Snowflake/BigQuery)
- ~~`dbt-cross-platform-governance-control-layer`~~ → `dbt-governance-control-layer`
- ~~`fabric-vs-databricks-governance-start`~~ entfernt

## Phase-C-Abgrenzung

Tiefe Supplier→Mart / Catalog-Ops Guides bleiben Phase C — diese Serie liefert nur Governance-**Einstiege**.
