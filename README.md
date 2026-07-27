# binom-tools

**Governance Help Hub** (v1.0.0) for data, BI, and analytics teams — Markdown playbooks, interactive reference workflows, and bilingual UI. Cloneable starter template, no CMS.

> Open-source public advisor by [Binom](https://binom.net) / Thomas Lindackers — not a commercial SaaS product. Dual store (file default, MySQL optional). Stories stay Markdown under `content/stories/`.

See [CONTRIBUTING.md](CONTRIBUTING.md) for where new code belongs (mega-modules / shell / shared). New feature checklist: [docs/new-module-checklist.de.md](docs/new-module-checklist.de.md).

## What you get

- **Stories (Playbooks)** — Markdown guides under `content/stories/` (DE/EN) with tags, TOC, and hero art
- **Calendar hub** — story publish dates, plan tasks, Feiertage + Schulferien NRW (`/calendar`)
- **Governance workflows** — interactive, copy-paste-ready tools (PII chain, data quality, Prompt Studio, AI Sanitizer, …)
- **Help hub shell** — search, theme (light/dark), locale, sidebar, overview filters
- **Local SDKs** — `@binom/sdk-governance` shipped in `packages/sdk-governance`

Live paths (unchanged technically): `/`, `/playbooks`, `/tools`, individual tool routes under `/tools/…`.

## Optional accounts

Default storage is **file-based** (no database required). Set `BINOM_TOOLS_ACCOUNTS_ENABLED=true` and keep `SESSION_DRIVER=file`. Copy example JSON from `storage/app/bn-tools/*.example.json` to `users.json` / `teams.json` / `story-acl.json`, then set passwords with:

```bash
php artisan bn-tools:user-password you@example.com
```

Passwords are stored only as `password_hash` digests. Plans, story ACL, and read-state live under `storage/app/bn-tools/` (gitignored).

Optional self-registration (admin must approve before login):

```env
BINOM_TOOLS_REGISTRATION_ENABLED=true
```

### Switch to MySQL (flip-ready)

Stories and repo sprint templates stay Markdown. Runtime data (users, teams, plans, likes/views, …) can use MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=binom_tools
DB_USERNAME=...
DB_PASSWORD=...

BINOM_TOOLS_STORAGE_DRIVER=mysql
```

```bash
php artisan migrate
php artisan bn-tools:storage-import   # optional: copy existing JSON into MySQL
php artisan calendar:holidays-sync --seed   # Feiertage + Schulferien NRW via iCal
```

### Calendar holidays (file or MySQL)

Holiday sources and imported Feiertage/Schulferien follow `BINOM_TOOLS_STORAGE_DRIVER`:

- **file (default):** JSON under `storage/app/bn-tools/calendar/` (`holiday-sources.json`, `holidays.json`) — no calendar migrations required
- **mysql:** tables `bn_calendar_holiday_sources` / `bn_calendar_holidays` (run `php artisan migrate`)

`npm run build` only builds frontend assets. To seed/update holidays on any driver:

```bash
php artisan calendar:holidays-sync --seed
```

With accounts on, the Sprint Planner stays open for guests as a **demo**: start a local plan that never syncs to the server. Sign in to save and share plans. Individual governance tools can require login via `TOOL_*_LOGIN_REQUIRED` (default `false` / open). The Calendar hub shows stories and holidays for everyone; plan tasks appear after sign-in.

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
├── content/
│   ├── stories/         # Playbooks (.de.md / .en.md)
│   ├── sprint-plans/    # Sprint Planner templates
│   └── catalogs/        # Suppliers / glossary JSON
├── packages/sdk-governance/
├── public/              # Built assets, images, prompt-studio config
├── resources/
│   ├── css/             # Shell, playbooks, themes, tool CSS
│   ├── js/              # Locale, overview, tool entrypoints
│   └── views/           # Blade layouts & components
└── routes/web.php
```

**Story flow:** `content/stories/*.md` → `PlaybookRepository` → `playbooks/show`  
**Governance cards:** `config/tools.php` → controllers → Blade cards + sidebar

## Configuration (.env)

| Key | Purpose |
| --- | --- |
| `APP_NAME` | Product name in page titles (`Story — Binom Governance`) |
| `PLAYBOOKS_SHARE_ENABLED` | Show/hide share control on stories (default `true`) |
| `TOOL_*_ENABLED` | Per-tool on/off (default `true`); disabled tools hide from nav/overview and return 404 |
| `TOOL_*_LOGIN_REQUIRED` | Per-tool login gate when accounts are enabled (default `false` / open) |
| `TOOLS_OVERVIEW_TITLE_*` | Optional governance overview H1 (defaults to Governance) |

Story **views** and **likes** are stored as JSON under `storage/app/playbook-stats/` (no database). One view per browser/day (cookie); likes toggle with a cookie.

## Adding content

1. **Story** — add `content/stories/<slug>.en.md` (+ `.de.md`), then clear playbook cache if needed  
2. **Workflow tool** — entry in `config/tools.php` → `nav`, route, controller, view under `resources/views/tools/…`, optional Vite entry in `vite.config.js`

More detail: playbook [Governance Help Hub](content/stories/help-hub-platform.en.md) / [DE](content/stories/help-hub-platform.de.md).

## Related

- Website: [binom.net](https://binom.net)
- Angular libs & docs: [ngx-docs.binom.net](https://ngx-docs.binom.net)
- Repository: configure `BINOM_TOOLS_REPO_URL` / `config/tools.php` → `links.repository`

## License

MIT — see application / package license files as applicable.
