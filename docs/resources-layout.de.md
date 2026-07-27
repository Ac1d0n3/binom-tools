# Resources layout — Foundations / Shared / Domains

Stand: 2026-07-27 (v1.0.0 wave)

## Target

| Layer | Path | Responsibility |
|-------|------|----------------|
| ThemeFoundation | `resources/css/foundations/theme/`, `resources/js/foundations/theme/` | Token contract, theme registry, blue-water light/dark |
| TaxonomyFoundation | `config/foundations/taxonomy.php` (+ `App\Foundations\Taxonomy`) | Shared IDs: regions, audiences, orgContexts, platforms |
| Shell | `resources/views/foundations/` | Layouts, header/sidebar chrome |
| Shared UI | `resources/views/components/shared/ui/` (+ mirror `views/shared/ui/`), `resources/js/shared/` | Tabs (folder\|underline), modal, layout-toggle |
| Domains | `resources/views/domains/<id>/`, `js/domains/`, `css/domains/` | Feature pages |
| Content | `content/stories/`, `content/sprint-plans/`, `content/catalogs/` | Markup + catalog JSON (file source of truth) |

## Why not one feature folder (PHP + JS + Views)?

Laravel stays **by type** (`app/`, `resources/views/`, `resources/js/`, `config/`). We **mirror layer names** across those trees (`foundations/`, `shared/`, `domains/<id>/`) so search stays predictable without breaking Vite, Blade, or Composer defaults.

Full feature colocation (everything under e.g. `modules/governance/`) is **out of scope** unless we explicitly decide to leave Laravel conventions.

`config/foundations/` is only Taxonomy **data**. Runtime helpers live in `app/Foundations/`; theme/shell stay under `resources/*/foundations/`.

## Content tree

```text
content/
  stories/           # Playbook Markdown (.de.md / .en.md) — config playbooks.content_path
  sprint-plans/      # Sprint Planner templates
  catalogs/          # suppliers/, glossary/ JSON
```

Do not dump new story files into the `content/` root.

## Move status (this wave)

| Item | Status |
|------|--------|
| Theme CSS/JS | Foundations + re-export from `themes/blue-water.css` / `theme.js` |
| Shell layout | `views/foundations/layouts/tools.blade.php` (canonical); old `layouts/tools` removed |
| Shell JS | `js/foundations/shell/shell-layout.js` + re-export `shell-layout.js` |
| Governance views | `views/domains/governance/` |
| Governance JS | `js/domains/governance/` + thin re-exports under `js/governance/` |
| Shared tabs / layout-toggle / modal | Blade under `components/shared/ui/`; JS `shared/tabs.js`, `shared/modal.js` |
| Taxonomy | `config/foundations/taxonomy.php` |
| Catalog JSON | `content/catalogs/suppliers/`, `content/catalogs/glossary/` (+ thin `config/*.php` facades) |
| Stories | `content/stories/` (was flat `content/*.md`) |

Keep **existing CSS class names on the DOM**. Delete parallel/dead code after tests are green (**delete-after-green**).

## Catalogs

Repo JSON under `content/catalogs/` is the source of truth (cloneable without DB). `CatalogJsonLoader` merges supplier facets; glossary merges core + buzzword term lists. Wave PHP files are gone — do not reintroduce them.

Optional later: `bn-tools:catalog-sync` mirrors JSON into `bn_catalog_documents` when MySQL storage is enabled (cache/override only).

## Out of scope for this map

- PHP `platform/core` SaaS modules
- Taxonomy CMS / DB story editor
- Story upload UI
- Feature-module colocation (PHP/JS/Views in one folder)
- BI workbench modal redesign
- Catalog live-edit admin CMS
