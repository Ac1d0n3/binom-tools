# IA — Roles, Learning Paths, Advisor, Sprint

Stand: 2026-07-27

## Journey

```text
Advisor (situation) → Roles (who decides) → Learning Path (what next) → Sprint template (do the work)
```

| Surface | Job | Route |
|---------|-----|-------|
| Governance Advisor | Triage by situation; recommend hubs/tools | `/governance` |
| Roles Hub | Decision rights / personas | `/roles` |
| Learning Paths | Guided journeys by goal | `/learning-paths` |
| Sprint Planner | Cloneable execution plans | `/sprint-planner` |

Roles and Paths stay **separate hubs**. They are cross-linked, not merged. The Advisor recommends their **index** routes (not nested UI).

## Landing / Sidebar order

- Landing hub cards: … → Learning Paths → Roles → Glossary → …
- Sidebar hubs: … → Learning Paths → Roles → Glossary → Sprint Planner → …

## Cross-wiring

- Roles → Path via `pathId` in `config/roles.php`
- Paths → Roles via `roleIds` in `config/learning-paths.php` (Related roles on path show)
- Paths → Sprint via `sprintTemplateSlug` → `/sprint-planner/templates?start={slug}`

## Learning path → sprint template map

| Path ID | Sprint template slug |
|---------|----------------------|
| `pii-in-five-steps` | `learning-path-pii-in-five-steps` |
| `dq-with-dbt` | `learning-path-dq-with-dbt` |
| `modernize-warehouse` | `learning-path-modernize-warehouse` |
| `governance-foundations` | `learning-path-governance-foundations` |
| `metadata-operating-model` | `learning-path-metadata-operating-model` |
| `trusted-metrics` | `learning-path-trusted-metrics` |
| `close-the-gaps` | `learning-path-close-the-gaps` |
| `ai-foundations` | `learning-path-ai-foundations` |
| `access-security-ops` | `learning-path-access-security-ops` |
| `end-to-end-governance` | `learning-path-end-to-end-governance` |
| `simplest-viable-stack` | `learning-path-simplest-viable-stack` |
| `cert-project-evidence` | `governance-learning-path-certification` |
| `cert-dbt-analytics-engineer` | `learning-path-cert-dbt-analytics-engineer` |
| `cert-fabric-power-bi` | `learning-path-cert-fabric-power-bi` |

Keep the catalog curated (**≤ 20 paths**). Index uses audience filter only — no search field.

Certification paths are **companions for people taking a specific cert** (e.g. dbt, DP-600/PL-300): official Learn/Docs remain the curriculum; the learning path + sprint plan provide week-by-week cadence (enroll → study → labs → exam → project transfer).

`cert-project-evidence` is the optional umbrella when several certs run in parallel with delivery.

## Advisor hubs added

In `$advisorLinks['hubs']` / `hubItems`:

- `learningPaths` → learning-paths index
- `roles` → roles index
- `sprintPlanner` → sprint-planner templates

Boosted under scenario `help` (and lightly under `new`).
