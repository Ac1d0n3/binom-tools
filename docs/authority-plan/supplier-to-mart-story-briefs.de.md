# Serie `supplier-to-mart` — Story-Plan (Phase C)

Stand: 2026-07-29  
Serie EN: **Supplier to Mart** · DE: **Von Quelle zum Mart**

Baut auf [`source-load-decisions`](source-load-decisions-story-briefs.de.md) auf (Load/Skip) — vertieft Grain → Fact/Dim → Artefakte.  
Zurück: [phase-c-artefakt-tiefe.de.md](phase-c-artefakt-tiefe.de.md)

---

## Serien-Übersicht

| Part | Slug | Titel EN | Titel DE | Catalog-ID | Phase-B Load/Skip | Status |
|------|------|----------|----------|------------|-------------------|--------|
| 1 | `salesforce-to-mart` | Salesforce → Mart: Grain, Facts and KPI Cards | Salesforce → Mart: Grain, Fakten und KPI-Karten | `salesforce` | `salesforce-tables-for-analytics` | live |
| 2 | `hubspot-to-mart` | HubSpot → Mart: Deals Grain to Pilot Mart | HubSpot → Mart: Deal-Grain zum Pilot-Mart | `hubspot` | `hubspot-tables-for-analytics` | live |
| 3 | `sap-s4-to-mart` | SAP S/4 → Mart: A Narrow Sales / Order Slice | SAP S/4 → Mart: klarer Sales-/Auftrags-Ausschnitt | `sap-s4hana` | `sap-s4-tables-for-analytics` | live |
| 4 | `workday-to-mart` | Workday → Mart: Workforce Headcount Snapshot | Workday → Mart: Workforce-Headcount-Snapshot | `workday` | `workday-tables-for-analytics` | live |
| 5 | `dynamics-365-to-mart` | Dynamics 365 → Mart: Opportunity Fact Candidates | Dynamics 365 → Mart: Opportunity-Fakten-Kandidaten | `dynamics365` | `dynamics-365-tables-for-analytics` | live |

Stretch (nicht Done-when): ServiceNow, SharePoint.

---

## Pro-Part Outline

1. Intro (eigener Text; kein Tabellen-Dump)
2. Kernobjekte → Grain → Fact/Dim-Kandidaten
3. PII/Skip kurz → Link Phase-B Playbook
4. Standard-KPIs → `/playbooks/define-kpi` + KPI Definition / Intake
5. Artefakt-Dateinamen: `source-scope.csv`, `kpi-cards.csv`, `mart-design-brief.md`, ggf. `dq-backlog.csv`
6. Tools: Source Scope, PII Recommend, Mart Design Brief, KPI Definition
7. Links: `/suppliers/{id}`, Phase-B-Slug, Advisor (`/governance/berater`), `from-stakeholder-interview-to-table-model`

Frontmatter:

```yaml
series: supplier-to-mart
seriesTitle: Von Quelle zum Mart   # EN-Datei: Supplier to Mart
seriesPart: N
author: Thomas Lindackers
publishedAt: 2026-07-29
```

---

## Artefakt-Standards (C2)

| Artefakt | Dateiname |
|----------|-----------|
| Discovery | `governance-discovery.md` |
| KPI Cards | `kpi-cards.csv` (+ JSON) |
| Source Scope | `source-scope.csv` / `.md` |
| DQ Backlog | `dq-backlog.csv` |
| Mart Design Brief | `mart-design-brief.md` |
| Decision Brief | `decision-brief.md` |

Doku-Story: `which-artifacts-you-get`

---

## Signature-IP (C3) — Fixtexte

- **Decision Ladder:** Orientierung → Fragen → Tools → Nachweise; Einstieg = Governance Advisor.
- **Collect Infos 8:** die acht Discovery-Canvas-Schritte als benanntes Gerüst.
- **Evidence Loop:** Entscheidung → Control → Nachweis (`missing-pieces-trusted-metrics`).
- **Platform Starting Point / Source Load First:** je 1 Satz aus bestehenden Serien.

---

## Proof Stories (C4)

| Slug | Pfad |
|------|------|
| `proof-stakeholder-to-kpi-cards` | Interview→Modell + KPI + Mart Brief |
| `proof-saas-source-to-pilot-mart` | Source-first / Salesforce / SaaS-Skip + Scope + PII + `salesforce-to-mart` |
| `proof-bi-chaos-to-trusted-metrics` | Inventory→Trusted Metric + Semantic Layer + Formel-Hinweis |

Struktur: Ausgangslage → Schritte → Artefakte → Lernerfolg → Disclaimer.
