# Phase B — Story Briefs (Decision / Long-tail)

Stand: 2026-07-28  
Status: briefs bereit — Bodies schreibst du woanders und legst sie als `content/stories/{slug}.{de,en}.md` ab.  
Zurück: [phase-b-infos-beratung.de.md](phase-b-infos-beratung.de.md)

Keine Story-Bodies in diesem Dokument. Nach Ablage: Hub / Discovery / Tools / Paths verdrahten und Status auf `verdrahtet` setzen.

## Frontmatter-Checkliste (bei Ablage)

- `title`, `description`, `author: Thomas Lindackers`, `tags`, `publishedAt`
- **Serie:** entweder weglassen (standalone) **oder** `series` + `seriesTitle` (DE/EN je Locale-Datei) + `seriesPart`
- Abschnitte: Problem → Entscheidung → Checkliste → Artefakt → Tools → Resources → Playbooks → nächster Schritt

---

## Bereits live (Reuse — verdrahten, nicht neu schreiben)

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

## Neu zu schreiben (P1)

### 1. `from-stakeholder-interview-to-table-model`

| Feld | Inhalt |
|------|--------|
| **Titel EN** | From Stakeholder Interview to Table Model |
| **Titel DE** | Vom Stakeholder-Interview zum Tabellenmodell |
| **Priorität** | P1 |
| **Decision-Frage** | Welche Interview-Aussagen werden zu Grain, Facts und Dimensions — und was bleibt Scope-out? |
| **Serie** | `building-modern-data-warehouse` · `seriesPart: 11` (nächste freie Part nach 10) · Title EN: Building a Modern Data Warehouse · DE: Ein modernes Data Warehouse aufbauen |
| **Fokus** | Interview → Entscheidung → Grain → Fact/Dim-Kandidaten → Mart Design Brief; Verbindung Stakeholder/RACI und KPI Card |
| **Abgrenzung** | Kein Ersatz für `before-building-the-first-table` oder `data-architect-role`; kein Salesforce-Load-Detail |
| **Tools** | stakeholder-matrix, report-inventory, kpi-requirements-intake, mart-design-brief-generator |
| **Playbooks** | `raci-for-data-governance`, `define-kpi`, `before-building-the-first-table` |
| **SEO-Cluster** | Data Governance starten / KPI |
| **Status** | brief |

### 2. `salesforce-tables-for-analytics`

| Feld | Inhalt |
|------|--------|
| **Titel EN** | Which Salesforce Tables to Load for Analytics — and Which to Skip |
| **Titel DE** | Welche Salesforce-Tabellen für Analytics laden — und welche skippen |
| **Priorität** | P1 |
| **Decision-Frage** | Welche Salesforce-Objekte sind must-have für Analytics, welche optional, welche skip? |
| **Serie** | **neu** `source-load-decisions` · Part 1 · Title EN: Source Load Decisions · DE: Lade-Entscheidungen für Quellsysteme |
| **Fokus** | Account/Opportunity/… Muster; PII-Hinweise; Skip-Begründung; Source Scope Artefakt |
| **Abgrenzung** | Kein vollständiger Salesforce-Guide (Phase C); generisches SaaS-Muster → eigene Story |
| **Tools** | source-scope-builder, suppliers (Salesforce), pii-dsdr-readiness-checker |
| **Playbooks** | `before-building-the-first-table`, `pii-privacy-governance` |
| **SEO-Cluster** | Data Governance starten / Supplier |
| **Status** | brief |

### 3. `saas-exports-tables-to-skip`

| Feld | Inhalt |
|------|--------|
| **Titel EN** | SaaS Exports: Tables You Should Not Load |
| **Titel DE** | SaaS-Exporte: Tabellen, die man nicht laden sollte |
| **Priorität** | P1 |
| **Decision-Frage** | Welche Export-Tabellen sind Audit-/System-/Duplikat-Rauschen und gehören nicht ins Warehouse? |
| **Serie** | `source-load-decisions` · Part 2 · Title wie oben |
| **Fokus** | Generisches Skip-Muster (history dumps, audit logs, UI caches, free-text blobs); Entscheidungscheckliste |
| **Abgrenzung** | Kein Vendor-Deep-Dive; Salesforce-Spezifika bleiben in `salesforce-tables-for-analytics` |
| **Tools** | source-scope-builder, meta-export-generator |
| **Playbooks** | `before-building-the-first-table` |
| **SEO-Cluster** | Data Governance starten |
| **Status** | brief |

### 4. Serie `governance-stack-decisions` (statt Fabric-vs-Databricks-Duell)

**Nicht** pairwise `fabric-vs-databricks-governance-start`. Stattdessen Chooser + Plattform-Einstiege + dbt + Multi-Platform.

Vollständiger Plan: [governance-stack-decisions-story-briefs.de.md](governance-stack-decisions-story-briefs.de.md)

| Part | Slug | Titel (kurz) | Status |
|------|------|--------------|--------|
| 1 | `choose-governance-starting-point` | Fabric/Databricks/Snowflake/BigQuery — Governance-Einstieg wählen | brief |
| 2 | `microsoft-fabric-governance-starting-point` | Microsoft Fabric als Governance-Einstieg | brief |
| 3 | `databricks-unity-catalog-governance-starting-point` | Databricks + Unity Catalog als Governance-Einstieg | brief |
| 4 | `snowflake-governance-starting-point` | Snowflake als Governance-Einstieg | brief |
| 5 | `bigquery-governance-starting-point` | BigQuery als Governance-Einstieg | brief |
| 6 | `dbt-cross-platform-governance-control-layer` | dbt als plattformübergreifende Kontrollschicht | brief |
| 7 | `governance-across-multiple-data-platforms` | Governance über mehrere Plattformen | brief |

**Serie:** `governance-stack-decisions` · Title EN: Governance Stack Decisions · DE: Governance-Stack-Entscheidungen  
**Abgrenzung:** keine Dublette zu `metadata-driven-governance-with-dbt-meta`, `dq-test2/4/5`, `snowflake-masking-policies-qlik-section-access`, `choosing-the-simplest-viable-architecture` — jeweils nur verlinken.  
**SEO-Cluster:** Fabric / Databricks / dbt / Snowflake (+ BigQuery)

### 5. `semantic-layer-vs-report-measure` (B3)

| Feld | Inhalt |
|------|--------|
| **Titel EN** | Semantic Layer vs Measure in the Report |
| **Titel DE** | Semantic Layer vs Measure im Report |
| **Priorität** | P1 (BI Decision) |
| **Decision-Frage** | Wann gehört die Kennzahl in den Semantic Layer / Warehouse — und wann darf sie im Report bleiben? |
| **Serie** | **neu** `bi-governance-decisions` · Part 1 · Title EN: BI Governance Decisions · DE: BI-Governance-Entscheidungen |
| **Fokus** | Duplikat-Risiko, Certification, Grain, Wiederverwendung; Link Trusted Metrics |
| **Abgrenzung** | Vertieft `keeping-business-logic-outside-bi-apps`, ersetzt sie nicht; kein DAX-/LOD-Tutorial |
| **Tools** | kpi-definition, powerbi-dax / tableau / qlik generators, report-inventory |
| **Playbooks** | `keeping-business-logic-outside-bi-apps`, `define-kpi`, `missing-pieces-trusted-metrics` |
| **Paths** | `trusted-metrics` |
| **SEO-Cluster** | Power BI / Tableau / Qlik |
| **Status** | brief |

### 6. `fabric-powerbi-metric-certification` (B3)

| Feld | Inhalt |
|------|--------|
| **Titel EN** | Metric Certification in Fabric and Power BI |
| **Titel DE** | Metrik-Zertifizierung in Fabric und Power BI |
| **Priorität** | P1 (BI Decision) |
| **Decision-Frage** | Was bedeutet „certified / endorsed“ für eine Kennzahl — und welche Nachweise braucht es? |
| **Serie** | `bi-governance-decisions` · Part 2 · Title wie oben |
| **Fokus** | Certification-Stufen, Owner, Evidence, Cadence; Verbindung Compliance/Platform Certs nur als Hinweis |
| **Abgrenzung** | Kein Microsoft-Produkt-Handbuch; CDMP/CIPP bleiben in `eight-pillars` / Compliance Roadmap |
| **Tools** | kpi-definition, learning-paths trusted-metrics |
| **Playbooks** | `kpi-metric-governance`, `missing-pieces-trusted-metrics` |
| **SEO-Cluster** | Power BI / Fabric |
| **Status** | brief |

---

## Schreib-Reihenfolge (Empfehlung)

1. `from-stakeholder-interview-to-table-model`
2. `salesforce-tables-for-analytics` + `saas-exports-tables-to-skip` (Serie `source-load-decisions`)
3. Serie `governance-stack-decisions` — zuerst Chooser (`choose-governance-starting-point`), dann Plattform-Parts (siehe [governance-stack-decisions-story-briefs.de.md](governance-stack-decisions-story-briefs.de.md))
4. `semantic-layer-vs-report-measure` + `fabric-powerbi-metric-certification` (Serie `bi-governance-decisions`)

## Nach Ablage (Agent / Verdrahtung)

- Discovery `sources`-Step: Salesforce + SaaS-Slugs
- Hub Guides Decisions + BI Journey
- Formel-Tools Related-Links
- Learning Path `trusted-metrics` / Sprint helpLinks
- Checkboxen in [phase-b-infos-beratung.de.md](phase-b-infos-beratung.de.md)
