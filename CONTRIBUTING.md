# Contributing to binom-tools

Public Governance Help Hub — single-site Laravel app. **v1.0.0**. Not a SaaS platform.

## Principles

- **1:1 parity when refactoring:** look and behaviour stay the same unless the change is an explicit product feature.
- **Dual store:** default is file storage (no DB required). MySQL is optional. Core features must never require a database.
- **Stories = Markdown** under `content/stories/*.md` (DE/EN). No CMS / no “Add Story” UI in core.
- **No new hubs or sidebar entries** without agreement.
- **Reuse Shared UI** before copy-pasting tabs, modals, or layout toggles.
- **Qlik Set Analysis / BI workbench CSS** is hands-off unless explicitly requested.

## Where code goes

| Kind | Location |
|------|----------|
| **Feature (mega-module)** | `modules/<id>/{js,css,views,script}/` + **`config.php` am Modul-Root** (optional `<key>.config.php`) |
| Theme / shell JS | `resources/js/shell/` (locale, theme, layout, consent, phone gate) |
| Shared UI | `resources/views/shared/`, `resources/js/shared/` |
| Shell layouts (Blade/CSS) | `resources/views/foundations/`, `resources/css/` (chrome only) |
| Catalog data | `content/catalogs/{name}/` |
| Shared vocabulary | `config/taxonomy.php` (+ `App\Support\Taxonomy`) |
| Laravel / globals | `config/{app,auth,database,session,storage,…}.php` only |

Blade: `view('calendar::index')`. Module config: `config('calendar')` from `modules/calendar/config.php`.

**Do not** put feature data in thick `config/*.php` files.


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
modules/<id>/
  config.php              # optional <key>.config.php
  js/ css/ views/ script/
resources/                # shell + shared only (layouts/theme)
content/stories|sprint-plans|catalogs/
config/                   # Laravel + storage + taxonomy only
```

## Advisor guidance

New cert/gap/stack recommendation rules belong in `modules/governance/js/advisor-guidance.js`, with Vitest coverage — not as one-off copies inside the hub shell (`hub-advisor.js` stays the thin integrator).

## Pull requests

- Keep PRs phase-sized (release, shared UI, theme, one domain move, advisor, perf).
- Include DE+EN when adding UI copy.
- Do not leave unused modal/tab duplicates after a cutover.
