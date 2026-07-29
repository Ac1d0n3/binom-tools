# Serie `source-load-decisions` — Story-Plan

Stand: 2026-07-29  
Serie EN: **Source Load Decisions** · DE: **Lade-Entscheidungen für Quellsysteme**

Struktur: Vendor-Load/Skip-Muster → Quelle-zuerst-Chooser → Multi-Source Authority.  
Zurück: [phase-b-story-briefs.de.md](phase-b-story-briefs.de.md)

---

## Serien-Übersicht (reale Slugs)

| Part | Slug | Titel EN | Titel DE | Status |
|------|------|----------|----------|--------|
| 1 | `salesforce-tables-for-analytics` | Which Salesforce Tables to Load for Analytics — and Which to Skip | Welche Salesforce-Tabellen für Analytics laden — und welche skippen | live · Hub verdrahtet |
| 2 | `saas-exports-tables-to-skip` | SaaS Exports: Tables You Should Not Load | SaaS-Exporte: Tabellen, die man nicht laden sollte | live · Hub verdrahtet |
| 3 | `which-source-to-load-first` | Which Source Should Load First? | Welche Quelle zuerst laden? | live · Hub verdrahtet |
| 4 | `hubspot-tables-for-analytics` | Which HubSpot Tables to Load — and Which to Skip | Welche HubSpot-Tabellen laden — und welche skippen | live · Serie-UI |
| 5 | `dynamics-365-tables-for-analytics` | Which Dynamics 365 Tables to Load — and Which to Skip | Welche Dynamics-365-Tabellen laden — und welche skippen | live · Serie-UI |
| 6 | `sap-s4-tables-for-analytics` | Which SAP S/4 Tables to Load for Analytics — and Which to Skip | Welche SAP-S/4-Tabellen für Analytics laden — und welche skippen | live · Serie-UI |
| 7 | `workday-tables-for-analytics` | Which Workday Objects to Load — and Which to Skip | Welche Workday-Objekte laden — und welche skippen | live · Serie-UI |
| 8 | `servicenow-tables-for-analytics` | Which ServiceNow Tables to Load — and Which to Skip | Welche ServiceNow-Tabellen laden — und welche skippen | live · Serie-UI |
| 9 | `multi-source-entity-authority` | Same Entity, Two Systems — Which Source Is Authoritative? | Dieselbe Entity, zwei Systeme — welche Quelle ist autoritativ? | live · Serie-UI |

---

## Rollen / Leser

| Rolle | Nutzen |
|-------|--------|
| Architect / Owner | Scope must-have / optional / skip vor dem ersten Load |
| Steward / Custodian | PII/DSDR-Risiken und Review-Fragen früh sichtbar |
| Product Owner | KPI-Kandidaten aus Kernobjekten, ohne Warehouse-Vollbau |

---

## Hub-Verdrahtung (Einstiege)

Nur Parts 1–3 im Hub (nicht jede Vendor-Part):

- Journey `supplier`
- Guides Decision „Welche Quelle zuerst?“
- Discovery Canvas Step `sources`

Slugs: `which-source-to-load-first`, `salesforce-tables-for-analytics`, `saas-exports-tables-to-skip`

Tools: Source Scope Builder, Meta Export, Supplier Library, PII Recommend.

---

## Frontmatter (Ist)

```yaml
series: source-load-decisions
seriesTitle: Source Load Decisions   # DE-Datei: lokalisiert
seriesPart: N
author: Thomas Lindackers
```

---

## Phase-C-Abgrenzung

- Keine vollständigen Supplier→Mart Guides (Tabellenkataloge, Mapping-Templates, dbt-Modell-Deep-Dives) → Phase C
- Diese Serie entscheidet **Load/Skip/Authority**, nicht die Mart-Implementierung
- Interview→Grain bleibt `from-stakeholder-interview-to-table-model` (Warehouse-Serie Part 11)
