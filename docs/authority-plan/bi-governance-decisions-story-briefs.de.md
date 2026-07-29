# Serie `bi-governance-decisions` — Story-Plan

Stand: 2026-07-29  
Serie EN: **BI Governance Decisions** · DE: **BI-Governance-Entscheidungen**

Struktur: Semantic Layer vs Report → Vendor-Certification / Master Items → Inventory→Trusted Metric → Self-Service / Excel / Formel-Generatoren.  
Zurück: [phase-b-story-briefs.de.md](phase-b-story-briefs.de.md)

---

## Serien-Übersicht (reale Slugs)

| Part | Slug | Titel EN | Titel DE | Status |
|------|------|----------|----------|--------|
| 1 | `semantic-layer-vs-report-measure` | Semantic Layer vs Measure in the Report | Semantic Layer vs Measure im Report | live · Hub + Formel-Tools |
| 2 | `fabric-powerbi-metric-certification` | Metric Certification in Fabric and Power BI | Metrik-Zertifizierung in Fabric und Power BI | live · Serie-UI |
| 3 | `tableau-metric-certification` | Metric Certification in Tableau | Metrik-Zertifizierung in Tableau | live · Serie-UI |
| 4 | `qlik-master-items-metric-governance` | Master Items and Metric Governance in Qlik | Master Items und Kennzahlen-Governance in Qlik | live · Serie-UI |
| 5 | `from-report-inventory-to-trusted-metric` | From Report Inventory to Trusted Metric | Vom Report Inventory zur vertrauenswürdigen Kennzahl | live · Hub + Sprint |
| 6 | `self-service-vs-governed-metrics` | Self-Service Metrics vs Governed Metrics | Self-Service-Kennzahlen vs. governed Metrics | live · Serie-UI |
| 7 | `excel-shadow-bi-and-metric-drift` | Excel Shadow BI and Metric Drift | Excel-Schatten-BI und Kennzahlen-Drift | live · Serie-UI |
| 8 | `when-to-use-bi-formula-generators` | When BI Formula Generators Help Governance | Wann BI-Formel-Generatoren der Governance helfen | live · Hub + Formel-Tools |

---

## Bestehende Stories (verlinken, nicht ersetzen)

| Slug | Rolle |
|------|--------|
| `define-kpi` | Grain, Owner, Contract-Praxis |
| `keeping-business-logic-outside-bi-apps` | Logik außerhalb BI-App |
| `missing-pieces-trusted-metrics` | Evidence / Operating Gaps |
| `kpi-metric-governance` | KPI-Governance-Vokabular / Rights |
| Learning Path `trusted-metrics` | Kuratierter Pfad |

---

## Hub- / Tool-Verdrahtung (Einstiege)

| Ort | Slugs |
|-----|-------|
| Journey `bi` | `from-report-inventory-to-trusted-metric`, `semantic-layer-vs-report-measure`, `when-to-use-bi-formula-generators` |
| Guides Decisions (KPI / Logic) | Inventory→Trusted Metric, Semantic Layer vs Report |
| Power BI / Tableau / Qlik Help-Links | Semantic + Formel-Generator Decision |
| Report Inventory / KPI Definition Help | Inventory→Trusted Metric, Semantic Layer |
| Trusted Metrics Sprint (Week 2) | Story `from-report-inventory-to-trusted-metric` |

Vendor-Certification-Parts (2–4) und Self-Service/Excel (6–7) bleiben Serie-UI — kein Extra-Hub-Slot.

---

## Frontmatter (Ist)

```yaml
series: bi-governance-decisions
seriesTitle: BI Governance Decisions   # DE-Datei: lokalisiert
seriesPart: N
author: Thomas Lindackers
```

---

## Phase-C-Abgrenzung

- Keine Produkt-Handbücher für DAX/LOD/Set Analysis (Formel-Tools bleiben Copy/Paste-Hilfe)
- Kein Qlik-Workbench-Redesign
- Tiefe Certification-Ops / Metric Store Implementierung → später / Phase C Artefakt-Tiefe
