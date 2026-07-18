# binom-tools

**Governance Help Hub** for data, BI, and analytics teams — Markdown playbooks, interactive reference workflows, and bilingual UI. Cloneable starter template, no CMS.

> Open-source hobby project by [Binom](https://binom.net) — not a commercial product.

## What you get

- **Stories (Playbooks)** — Markdown guides under `content/` (DE/EN) with tags, TOC, and hero art
- **Governance workflows** — interactive, copy-paste-ready tools (PII chain, data quality, Prompt Studio, AI Sanitizer, …)
- **Help hub shell** — search, theme (light/dark), locale, sidebar, overview filters
- **Local SDKs** — `@binom/sdk-governance` shipped in `packages/sdk-governance`

Live paths (unchanged technically): `/`, `/playbooks`, `/tools`, individual tool routes under `/tools/…`.

## Stack

| Layer | Tech |
| --- | --- |
| Backend | Laravel 13, PHP 8.3+ |
| Frontend | Vite 8, vanilla JS, CSS (Tailwind 4 via Vite) |
| Content | Markdown + YAML frontmatter |
| Tests | PHPUnit, Vitest, Playwright |

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate

npm install
npm run build:local   # or: npm run htaccess:local && npm run build
php artisan serve
```

For Vite HMR during UI work:

```bash
composer run dev
# or: php artisan serve  +  npm run dev
```

Local MAMP-style base path: use `public/.htaccess.local` via `npm run build:local`.

## Useful scripts

| Command | Purpose |
| --- | --- |
| `npm run build` | Sync playbook images, Vite build, rewrite asset URLs |
| `npm run build:local` | Local `.htaccess` + build |
| `npm run deploy:ftp` | Pack FTP deploy bundle |
| `npm test` / `npm run test:e2e` | Vitest / Playwright |
| `php artisan test` | PHPUnit |

## Project layout

```text
binom-tools/
├── app/                 # Controllers, playbooks, catalog, support
├── config/tools.php     # Nav, workflows, ecosystem links
├── content/             # Playbooks (.de.md / .en.md)
├── packages/sdk-governance/
├── public/              # Built assets, images, prompt-studio config
├── resources/
│   ├── css/             # Shell, playbooks, themes, tool CSS
│   ├── js/              # Locale, overview, tool entrypoints
│   └── views/           # Blade layouts & components
└── routes/web.php
```

**Story flow:** `content/*.md` → `PlaybookRepository` → `playbooks/show`  
**Governance cards:** `config/tools.php` → controllers → Blade cards + sidebar

## Adding content

1. **Story** — add `content/<slug>.en.md` (+ `.de.md`), then clear playbook cache if needed  
2. **Workflow tool** — entry in `config/tools.php` → `nav`, route, controller, view under `resources/views/tools/…`, optional Vite entry in `vite.config.js`

More detail: playbook [Governance Help Hub](content/help-hub-platform.en.md) / [DE](content/help-hub-platform.de.md).

## Related

- Website: [binom.net](https://binom.net)
- Angular libs & docs: [ngx-docs.binom.net](https://ngx-docs.binom.net)
- Repository: configure `BINOM_TOOLS_REPO_URL` / `config/tools.php` → `links.repository`

## License

MIT — see application / package license files as applicable.
