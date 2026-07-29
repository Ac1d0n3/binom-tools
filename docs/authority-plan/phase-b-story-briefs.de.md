# Phase B — Story Briefs (Decision / Long-tail)

Stand: 2026-07-29  
Status: Bodies abgelegt · Einstiege **verdrahtet** (Hub Journeys/Guides/Discovery/Formel-Tools/Trusted-Metrics-Sprint)  
Zurück: [phase-b-infos-beratung.de.md](phase-b-infos-beratung.de.md)

Keine Story-Bodies in diesem Dokument. Status `verdrahtet` = Datei live + Einstieg im Hub/Tools.

## Frontmatter-Checkliste (bei Ablage)

- `title`, `description`, `author: Thomas Lindackers`, `tags`, `publishedAt`
- **Serie:** entweder weglassen (standalone) **oder** `series` + `seriesTitle` (DE/EN je Locale-Datei) + `seriesPart`
- Abschnitte: Problem → Entscheidung → Checkliste → Artefakt → Tools → Resources → Playbooks → nächster Schritt

---

## Bereits live (Reuse — verdrahtet)

| Slug | Serie | SEO-Cluster | Hub-Verdrahtung |
|------|-------|-------------|-----------------|
| `before-building-the-first-table` | `building-modern-data-warehouse` Part 1 | Data Governance starten | Guides Decisions, Discovery, Journeys |
| `define-kpi` | standalone | KPI Definition | Guides, Discovery, BI Journey, Formel-Tools |
| `kpi-metric-governance` | `governance-pillars` | KPI | Discovery KPI-Step, Trusted Metrics Path |
| `eight-pillars` | `governance-pillars` | Governance starten / Certs | Guides Stack + `/compliance/roadmap` |
| `metadata-driven-governance-with-dbt-meta` | `end-to-end-data-governance` | dbt / Snowflake Governance | Guides Decision „Logic/Meta“ |
| `pii-privacy-governance` | `governance-pillars` | PII / DSDR | Guides, Discovery risk, Formel-Tools |
| `dsdr-governance` | (bestehend) | PII / DSDR | Discovery risk |
| `keeping-business-logic-outside-bi-apps` | `building-modern-data-warehouse` | Power BI / Tableau / Qlik | Guides, Formel-Tools, Trusted Metrics Sprint |
| `data-quality-governance` | (bestehend) | Data Quality | Discovery DQ-Step |

---

## Phase-B Decision Pages (abgelegt + verdrahtet)

### 1. `from-stakeholder-interview-to-table-model` — Status: verdrahtet

| Feld | Inhalt |
|------|--------|
| **Serie** | `building-modern-data-warehouse` · Part 11 |
| **Hub** | Discovery Steps `business-questions` + `mart` |
| **SEO-Cluster** | Data Governance starten / KPI |

### 2–3. Source-Load-Einstiege — Status: verdrahtet

Vollständiges Inventar Parts 1–9: [source-load-decisions-story-briefs.de.md](source-load-decisions-story-briefs.de.md)

| Slug | Part | Hub |
|------|------|-----|
| `salesforce-tables-for-analytics` | 1 | Journey Supplier, Guides „Welche Quelle zuerst?“, Discovery `sources` |
| `saas-exports-tables-to-skip` | 2 | wie oben |
| `which-source-to-load-first` | 3 | wie oben (primärer Chooser der Serie) |

Weitere Vendor-Parts (HubSpot, Dynamics, SAP, Workday, ServiceNow, Multi-Source) live; Navigation über Playbook-Series-UI — nicht einzeln im Hub.

### 4. Serie `governance-platform-starting-points` — Status: verdrahtet (Einstieg)

**Nicht** pairwise `fabric-vs-databricks-governance-start`.  
Datei-Name der Plan-MD bleibt historisch: [governance-stack-decisions-story-briefs.de.md](governance-stack-decisions-story-briefs.de.md)

| Part | Slug (real) | Status |
|------|-------------|--------|
| 1 | `choose-governance-platform-starting-point` | verdrahtet (Stack Journey + Guides) |
| 2 | `microsoft-fabric-governance-start` | verdrahtet (optional Guides) |
| 3 | `databricks-unity-catalog-governance-start` | abgelegt (Serie-UI) |
| 4 | `snowflake-governance-start` | abgelegt (Serie-UI) |
| 5 | `bigquery-governance-start` | abgelegt (Serie-UI) |
| 6 | `dbt-governance-control-layer` | abgelegt (Serie-UI) |
| 7 | `governance-across-multiple-data-platforms` | abgelegt (Serie-UI) |

**Serie:** `governance-platform-starting-points` · Title EN: Governance Platform Starting Points · DE: Governance-Einstiegspunkte für Datenplattformen  
Alte Brief-Slugs (`choose-governance-starting-point`, `…-starting-point`, Serie-ID `governance-stack-decisions`) sind **veraltet** — reale Dateinamen nutzen.

### 5–6. Serie `bi-governance-decisions` — Status: verdrahtet (Einstiege)

Vollständiges Inventar Parts 1–8: [bi-governance-decisions-story-briefs.de.md](bi-governance-decisions-story-briefs.de.md)

| Slug | Part | Hub / Tools |
|------|------|-------------|
| `semantic-layer-vs-report-measure` | 1 | BI Journey, Guides, Formel-Tools, KPI/Report Inventory |
| `fabric-powerbi-metric-certification` | 2 | Serie-UI |
| `from-report-inventory-to-trusted-metric` | 5 | BI Journey, Guides KPI, Report Inventory, Trusted-Metrics-Sprint |
| `when-to-use-bi-formula-generators` | 8 | BI Journey, Formel-Tools |

---

## Schreib-Reihenfolge (historisch — erledigt)

1. `from-stakeholder-interview-to-table-model`
2. Source-Load-Serie (`source-load-decisions`)
3. Plattform-Serie (`governance-platform-starting-points`)
4. BI-Serie (`bi-governance-decisions`)

## Nach Ablage (Agent / Verdrahtung) — erledigt 2026-07-29

- Discovery `sources`-Step: Salesforce + SaaS + which-source
- Hub Guides Decisions + BI / Stack / Supplier Journeys
- Formel-Tools Related-Links + KPI/Report Inventory Help
- Trusted Metrics Sprint: Story `from-report-inventory-to-trusted-metric`
- Checkboxen in [phase-b-infos-beratung.de.md](phase-b-infos-beratung.de.md)
