# Contributing to binom-tools

Public Governance Help Hub — single-site Laravel app. **v1.0.0**. Not a SaaS platform.

## Principles

- **1:1 parity when refactoring:** look and behaviour stay the same unless the change is an explicit product feature.
- **Dual store:** default is file storage (no DB required). MySQL is optional. Core features must never require a database.
- **Stories = Markdown** under `content/*.md` (DE/EN). No CMS / no “Add Story” UI in core.
- **No new hubs or sidebar entries** without agreement.
- **Reuse Shared UI** before copy-pasting tabs, modals, or layout toggles.
- **Qlik Set Analysis / BI workbench CSS** is hands-off unless explicitly requested.

## Where code goes

| Kind | Location |
|------|----------|
| Theme tokens / registry | `resources/css/foundations/theme/`, `resources/js/foundations/theme/` |
| Shared vocabulary IDs | `config/foundations/taxonomy.php` (+ `App\Foundations\Taxonomy`) |
| Shell / layouts | `resources/views/foundations/` (during migration: also `layouts/`, `components/tools/`) |
| Reusable UI | `resources/views/shared/ui/`, `resources/js/shared/` |
| Domain pages | `resources/views/domains/<domain>/` (during migration: existing `views/<domain>/`) |
| Domain JS/CSS | `resources/js/domains/<domain>/`, `resources/css/domains/<domain>/` |
| Playbook content | `content/*.md` only |
| Catalog data (suppliers, glossary, …) | `content/catalogs/{name}/*.json` — **never** new `config/*wave*.php` |

**Rule of thumb:** domain page → `domains/<name>/`; button/toggle/tabs/modal → `shared/ui/`; shell/theme → `foundations/`; shared IDs/labels → taxonomy; **catalog entries → JSON**.

### Catalogs (JSON)

Suppliers and Glossary load from `content/catalogs/` via `App\Catalog\CatalogJsonLoader`. Thin PHP facades remain in `config/suppliers.php` and `config/glossary.php`.

- Add/edit a supplier product → `content/catalogs/suppliers/products.json` (and overlays in `governance.json` / `quality.json` / `sql.json` if needed).
- Add/edit a glossary term → `content/catalogs/glossary/terms-core.json` or `terms-buzzwords.json`.
- **Do not** add `config/*wave*.php` files.
- Optional re-export (legacy): `php -d memory_limit=512M scripts/export-catalog-json.php` — only useful if PHP sources still exist.
- Optional MySQL cache: `php artisan bn-tools:catalog-sync` (repo JSON stays source of truth).
- Admin link checker: `php artisan bn-tools:link-check` / UI `/account/link-check` (`canManageUsers` only).

Runtime Dual Store (`BINOM_TOOLS_STORAGE_DRIVER=file|mysql`) covers users/plans/sessions/likes — **not** catalog bodies or story markdown.

## Setup

```bash
composer install
npm install
cp .env.example .env   # optional overrides
php artisan key:generate
npm run build
php artisan serve
```

### Tests

```bash
php -d memory_limit=512M artisan test
npm test                 # Vitest
npx playwright test      # optional e2e
```

### Images (WebP)

```bash
npm run sync:images
```

Playbook PNGs under `public/images/playbooks/` (and other non-icon rasters under `public/images/`) get WebP siblings via `sharp`. Prefer `<picture>` / `x-playbooks.responsive-image` with lazy loading; use `fetchpriority="high"` only for true LCP heroes. Run `npm run sync:images` after adding PNGs (CI/local). SVG stays SVG.

## Architecture map (target)

```
resources/
  views/
    foundations/     # layouts, shell chrome
    shared/ui/       # tabs (folder|underline), modal, layout-toggle, segmented
    domains/         # governance, playbooks, tools, compliance, …
  js/
    foundations/theme/
    shared/
    domains/governance/   # hub-advisor, advisor-guidance, …
  css/
    foundations/theme/    # _contract.css, blue-water.css
    shared/ui/
    domains/
config/foundations/
  taxonomy.php
```

Migration is incremental: existing paths may remain until a domain is moved; delete parallel/dead code after tests are green (**delete-after-green**).

## Advisor guidance

New cert/gap/stack recommendation rules belong in `resources/js/domains/governance/advisor-guidance.js`, with Vitest coverage — not as one-off copies inside the hub shell (`hub-advisor.js` stays the thin integrator).

## Pull requests

- Keep PRs phase-sized (release, shared UI, theme, one domain move, advisor, perf).
- Include DE+EN when adding UI copy.
- Do not leave unused modal/tab duplicates after a cutover.
