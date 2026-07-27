# Resources layout — Foundations / Shared / Domains

Stand: 2026-07-27 (v1.0.0 wave)

## Target

| Layer | Path | Responsibility |
|-------|------|----------------|
| ThemeFoundation | `resources/css/foundations/theme/`, `resources/js/foundations/theme/` | Token contract, theme registry, blue-water light/dark |
| TaxonomyFoundation | `config/foundations/taxonomy.php` | Shared IDs: regions, audiences, orgContexts, platforms |
| Shell | `resources/views/foundations/` | Layouts, header/sidebar chrome |
| Shared UI | `resources/views/components/shared/ui/` (+ mirror `views/shared/ui/`), `resources/js/shared/` | Tabs (folder\|underline), modal, layout-toggle |
| Domains | `resources/views/domains/<id>/`, `js/domains/`, `css/domains/` | Feature pages |

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

Keep **existing CSS class names on the DOM**. Delete parallel/dead code after tests are green (**delete-after-green**).

## Out of scope for this map

- PHP `platform/core` SaaS modules
- Taxonomy CMS / DB
- Story upload UI
- BI workbench modal redesign
