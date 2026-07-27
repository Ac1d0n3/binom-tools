# Story gaps — Roles Hub

Stand: 2026-07-26

Arbeitsliste für **neue** Playbooks, die woanders geschrieben werden.  
Keine Story-Bodies in diesem Dokument — nur Briefs.

Nach Ablage als `content/stories/{slug}.en.md` + `content/stories/{slug}.de.md` im Hub verdrahten (Roles-/Path-/Glossary-Config).

---

## Bereits vorhanden (nicht neu schreiben)

| Slug | Rolle / Thema | Series |
|------|---------------|--------|
| `data-ownership-stewardship` | Data Owner, Steward, Custodian, Consumer + Decision Rights | `governance-pillars` |
| `missing-pieces-ownership-stewardship` | Warum Katalog-Rollen passiv bleiben; Capacity, Escalation | `missing-pieces` |
| `define-kpi` | Steward × Architect am KPI-Contract (Abschnitt) | — |
| `operating-and-governing-the-platform` | Platform-Ops inkl. RACI-artiger Matrix / Architect (Abschnitt) | `building-modern-data-warehouse` |
| `eight-pillars` | Ownership-Säule im Überblick | `governance-pillars` |
| `kpi-metric-governance` | KPI Operating Model / Decision Rights | `governance-pillars` |

---

## P1 — für den Roles Hub nötig

### 1. `data-architect-role`

| Feld | Inhalt |
|------|--------|
| **Titel EN** | The Data Architect Role — Grain, Contracts and Architectural Consistency |
| **Titel DE** | Die Rolle Data Architect — Grain, Contracts und architektonische Konsistenz |
| **Priorität** | P1 |
| **Zielrolle** | Data Architect (auch relevant für Platform / Governance Lead) |
| **Series-Vorschlag** | `governance-pillars` oder standalone |
| **Thema / Fokus** | • Abgrenzung zu Data Owner und Data Steward<br>• Grain und Modellkonsistenz als Kernauftrag<br>• Data Contracts / Interface-Stabilität<br>• Mitwirkung an Stack- und Mart-Entscheidungen<br>• Zusammenarbeit mit Stewardship und Platform Ops<br>• Typische Anti-Patterns (Architect als Bottleneck, Papier-Architektur) |
| **Abgrenzung** | Keine Wiederholung des Owner/Steward-Operating-Models aus `data-ownership-stewardship`. KPI-Contract nur anreißen — Detail bleibt in `define-kpi` / `kpi-metric-governance`. Kein reines Warehouse-How-to (dafür `building-modern-data-warehouse`). |
| **Hub-Verdrahtung** | Roles: `architect` · Glossary: `data-architect` · Paths: `modernize-warehouse`, `governance-foundations` · Tool: `tools.architecture-fit` / Stack Advisor |

### 2. `raci-for-data-governance`

| Feld | Inhalt |
|------|--------|
| **Titel EN** | RACI for Data Governance — Decision Rights Without Role Sprawl |
| **Titel DE** | RACI für Data Governance — Decision Rights ohne Role Sprawl |
| **Priorität** | P1 |
| **Zielrolle** | Governance Lead, Owner, Steward, CoE |
| **Series-Vorschlag** | `governance-pillars` oder `missing-pieces` |
| **Thema / Fokus** | • RACI als Operating Practice, nicht als Folienübung<br>• R vs A bei Datenentscheidungen (was „Accountable“ wirklich heißt)<br>• Wann C/I reichen — Role Sprawl vermeiden<br>• Cadence: wann Rollen neu verhandeln<br>• Verbindung zur Stakeholder-/RACI-Matrix im Hub<br>• Beispiele: PII-Freigabe, Mart-Go-Live, KPI-Änderung |
| **Abgrenzung** | Keine zweite Ownership-Story. Keine vollständige CoE-Organisation (→ `governance-coe`). Tool-Bedienung der Matrix nur kurz; Praxis im Tool `stakeholder-matrix`. |
| **Hub-Verdrahtung** | Roles: alle · Glossary: `raci` · Paths: `governance-foundations` · Tool: `tools.stakeholder-matrix` |

---

## P2 — stark empfohlen, Hub-v1 nicht blockierend

### 3. `governance-coe`

| Feld | Inhalt |
|------|--------|
| **Titel EN** | Building a Data Governance Center of Excellence |
| **Titel DE** | Ein Data-Governance-Center of Excellence aufbauen |
| **Priorität** | P2 |
| **Zielrolle** | CoE / Governance Lead / Leadership |
| **Series-Vorschlag** | standalone oder `missing-pieces` |
| **Thema / Fokus** | • Auftrag und Grenzen eines Governance CoE<br>• Council-Cadence und Escalation Paths<br>• Zentral vs. federiert (Verweis, nicht Doppelung zu Metadata-Org-Stories)<br>• Skills, Staffing, Priorisierung von Domains<br>• Evidence und Reporting an Sponsor |
| **Abgrenzung** | Nicht dasselbe wie RACI-Mechanik (`raci-for-data-governance`). Metadata-Org-Modelle bleiben in `centralized-federated-or-distributed-metadata`. |
| **Hub-Verdrahtung** | Roles: governance lead · Paths: `governance-foundations` · Glossary: später optional |

### 4. `data-product-owner-vs-data-owner`

| Feld | Inhalt |
|------|--------|
| **Titel EN** | Data Product Owner vs Data Owner vs Steward — Who Decides What |
| **Titel DE** | Data Product Owner vs Data Owner vs Steward — wer entscheidet was |
| **Priorität** | P2 |
| **Zielrolle** | Product Owner, Data Owner, Steward |
| **Series-Vorschlag** | `governance-pillars` oder standalone |
| **Thema / Fokus** | • Drei Rollen, drei Entscheidungsebenen<br>• Data Product Lifecycle vs. Domain Ownership<br>• Konflikte und Eskalation<br>• Wann eine Person mehrere Hüte trägt — und wann nicht |
| **Abgrenzung** | Baut auf `data-ownership-stewardship` auf; kein Ersatz dafür. Data-Product-Begriff bleibt an Glossary `data-product` und Warehouse-Serie. |
| **Hub-Verdrahtung** | Roles: `owner`, `steward`, optional `product-owner` · Glossary: `data-owner`, `data-steward`, `data-product` |

### 5. `stewardship-capacity`

| Feld | Inhalt |
|------|--------|
| **Titel EN** | Staffing Stewardship — Capacity Models for Domain-Embedded Roles |
| **Titel DE** | Stewardship staffen — Capacity-Modelle für domain-eingebettete Rollen |
| **Priorität** | P2 |
| **Zielrolle** | Steward, Governance Lead, Management |
| **Series-Vorschlag** | `missing-pieces` (passt zu Ownership Missing Piece) |
| **Thema / Fokus** | • FTE-/Anteil-Modelle vs. „nebenbei“<br>• Scope cut: was ein Steward realistisch trägt<br>• Intake und Priorisierung von Stewardship-Arbeit<br>• Messung ohne Vanity Metrics<br>• Verbindung zu Missing Pieces Ownership |
| **Abgrenzung** | Vertieft Capacity aus `missing-pieces-ownership-stewardship`, ersetzt die Story nicht. Kein reines HR-/Karriere-Handbuch. |
| **Hub-Verdrahtung** | Roles: `steward` · Paths: `governance-foundations` · Story-Related: `missing-pieces-ownership-stewardship` |

---

## Schreib-Reihenfolge (Empfehlung)

1. `data-architect-role`  
2. `raci-for-data-governance`  
3. `data-product-owner-vs-data-owner` (wenn Product-Thinking im Hub wächst)  
4. `stewardship-capacity`  
5. `governance-coe`

---

## Nach dem Schreiben / Status

| Slug | Status |
|------|--------|
| `data-architect-role` | **fertig** — in Roles Hub / Glossary / Path verdrahtet |
| `raci-for-data-governance` | **fertig** — verdrahtet |
| `data-product-owner-vs-data-owner` | **fertig** — verdrahtet (+ Persona Data Product Owner) |
| `stewardship-capacity` | **fertig** — verdrahtet |
| `governance-coe` | **fertig** — in Learning Path Governance Foundations verdrahtet |

Serie: `roles-hub` (*Roles and Decision Rights*).

Config-Anker: [`config/roles.php`](../config/roles.php), [`config/glossary.php`](../config/glossary.php), [`config/learning-paths.php`](../config/learning-paths.php).
