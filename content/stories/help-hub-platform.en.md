---
title: Governance Help Hub
description: How the governance help hub is built — Markdown stories, interactive tools, and i18n as a reusable starter template.
author: Thomas Lindackers
category: Help Hub
tags:
  - help-hub
  - markdown
  - governance
order: 0
publishedAt: 2026-05-01
series: governance-help-hub
seriesPart: 1
seriesTitle: Governance Help Hub
---

## Overview

This page explains how **binom-tools** works as a **governance help hub**: stories as Markdown playbooks, interactive reference tools, a bilingual UI, and a lean Laravel stack without a CMS.

Clone the project, add your own stories under `content/`, and optionally extend interactive tools — ideal for data governance and BI teams who want knowledge versioned close to code.

This story is **part 1** of the *Governance Help Hub* series. Part 2 covers optional [logins and permissions](/playbooks/help-hub-accounts); part 3 covers the [Sprint Planner](/playbooks/help-hub-sprint-planner) (templates, storage, fence syntax).

> No gateway, no CMS: content lives in Git; reviews work like application code.

## Architecture

| Layer | Technology | Purpose |
| --- | --- | --- |
| Backend | Laravel 12, PHP 8.2+ | Routing, playbook repository, Blade views |
| Frontend | Vite, vanilla JS, CSS | Shell, search, TOC, theme, locale |
| Stories | Markdown + YAML frontmatter | Playbooks under `content/` |
| Tools | Blade + tool-specific JS | Interactive reference workflows |
| Assets | `public/` | Images, built CSS/JS |

**Story data flow:** `content/*.md` → `PlaybookRepository` → Blade (`playbooks/show`) → rendered HTML + TOC from headings.

**Tool data flow:** `config/tools.php` → controller → Blade cards and sidebar navigation.

## Project structure

```text
binom-tools/
├── app/
│   ├── Accounts/             # Optional logins, ACL, plan store (see part 2)
│   ├── Catalog/              # Landing preview (latest tools & stories)
│   ├── Http/Controllers/     # Landing, tools, playbooks, sprint planner, legal
│   ├── Playbooks/            # Parser, renderer, repository
│   └── SprintPlanner/        # Template parser, seeds (see part 3)
├── config/tools.php          # Tool nav, hero, ecosystem links
├── content/                  # Markdown stories (.de.md / .en.md)
├── content/sprint-plans/     # Sprint Planner templates
├── public/images/            # Hero artwork, logos
├── resources/
│   ├── css/                  # Shell, playbooks, themes
│   ├── js/                   # Locale, TOC, search, theme, sprint planner
│   └── views/                # Blade layouts & components
├── routes/web.php
└── tests/                    # Feature & unit tests
```

Key UI components:

- `<x-tools.card>` — cards for tools and landing
- `<x-playbooks.card>` — story cards on overview & landing
- `<x-playbooks.toc>` — table of contents with scroll spy
- `<x-tools.sidebar>` — navigation (home, stories, tools, sprint planner)

## Add a story

1. Create two files: `content/my-topic.de.md` and `content/my-topic.en.md`
2. Fill YAML frontmatter (title, description, category, tags, optional `order` and `hero`)
3. Write the body in Markdown — `##` and `###` automatically create anchors and TOC entries

**Blockquote (attribution on the last line):**

```markdown
> Governance is not a tool problem — it is an accountability problem.
> — Thomas Lindackers
```

**Embedded video (loads after cookie consent for external media):**

````markdown
```video Demo title
https://www.youtube.com/watch?v=dQw4w9WgXcQ
```
````

**Local video** (file under `public/videos/playbooks/`, included in offline packs):

````markdown
```video Intro
/videos/playbooks/intro.mp4
```
````

**Read offline:** On a story page tap **Save offline** (single story) or on `/playbooks` tap **Save all offline**. Nothing is downloaded in the background. External embeds need a network; local videos and images are included in the download.

**Simple flow chart (chevron — same style as Tools workflows):**

````markdown
```flowchart
Governance policy
dbt project
Warehouse [active]
BI report
```

```flow linear
Collect metadata
Profile data
Publish catalog
```
````

Optional markers: `[active]` or `[done]` on a step line highlight progress.

**When to use which layout**

| Fence | Use for |
|-------|---------|
| `flowchart` / `flow chevron` | Short process chains (Tools-style chevrons; stacks vertically when narrow) |
| `flow linear` | Horizontal boxes with → arrows |
| `flow linear vertical` | Longer stacks: Label / ↓ / Label (no outer box) |

```yaml
---
title: My governance story
description: Short description for cards and SEO.
publishedAt: 2026-07-16
category: Data platform
tags:
  - rbac
  - audit
order: 2
hero: images/playbooks/my-topic.svg
---
```

The route `/playbooks/my-topic` is available on the next request — no entry in `routes/web.php` required.

### Frontmatter fields

| Field | Required | Description |
| --- | --- | --- |
| `title` | yes | Page title |
| `description` | yes | Lead text on detail and card views |
| `publishedAt` | **yes for sorting** | Editorial publish date (`YYYY-MM-DD` or `YYYY-MM-DD HH:MM`). Drives Newest/Oldest — not file mtime |
| `category` | no | Meta line (e.g. “Data platform”) |
| `tags` | no | Filter chips on story overview |
| `order` | no | Sort order in sidebar and index (ascending) |
| `hero` | no | Path under `public/` for hero image |
| `series` | no | Series ID — groups stories on the overview **Series** tab and detail series nav |
| `seriesPart` | no | Part number within the series (1, 2, 3 …) |
| `seriesTitle` | no | Display name of the series (per locale file) |

Stories with the same `series` value appear as one series card on `/playbooks` (Series view) with combined reading time and the part-1 hero image. On the detail page, a series navigation block shows **Part X of Y** and links to all parts.

Pager behaviour for series members: `PLAYBOOKS_SERIES_PAGER=both|series|global` in `.env` (default `both`).

## Add a tool

1. Add an entry to `config/tools.php` → `nav` (id, route, label, description, icon, accent)
2. Add a route in `routes/web.php` plus controller and view under `resources/views/tools/…`
3. Optionally: dedicated Vite entry for tool JavaScript

Tools appear automatically in the sidebar, tools overview (`/tools`), and on the landing page (latest nine + overview card).

## i18n & theme

- UI strings: `resources/js/locale.js` (`data-i18n`, `data-card-id`, `data-text-de` / `data-text-en`)
- Playbook content: separate locale files (`.de.md`, `.en.md`)
- Theme: light/dark via `data-color-scheme` and CSS variables in `resources/css/themes/`

## Local development

```bash
git clone <repository-url> binom-tools
cd binom-tools
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev          # or: npm run build
php artisan serve
```

Optional in `.env`:

```env
BINOM_TOOLS_REPO_URL=https://github.com/your-org/your-help-hub
```

binom-ngx docs URL is fixed in `config/tools.php` → `https://ngx-docs.binom.net`

## Deployment

1. `npm run build` — Vite assets to `public/build/`
2. `composer install --no-dev --optimize-autoloader`
3. `php artisan config:cache` and `php artisan route:cache`
4. Point the web server document root to `public/`

Stories are plain files — content updates can ship via Git pull without database migrations.

## Download & fork

The project is intended as a **starter template**:

1. **Clone the repository** and continue under your own name
2. Replace or extend **`content/`** with your governance stories
3. Adjust **`config/tools.php`** and branding (`resources/views/components/tools/brand.blade.php`)
4. Optionally use stories only — add interactive tools incrementally

Repository URL (configurable): see `BINOM_TOOLS_REPO_URL` in `.env` or `config/tools.php` → `links.repository`.

### Checklist for your own help hub

- [ ] Branding (logo, domain, footer)
- [ ] At least one story in DE + EN
- [ ] Define categories and tags
- [ ] Review legal notice (`/impressum`)
- [ ] CI: `php artisan test` and `npm run build` in pipeline

## Support & extensions

Typical extensions without breaking the architecture:

- PDF export for individual stories (e.g. browser print CSS)
- Server-side full-text search (instead of client filters on overview pages)
- Optional authentication and story ACL — see [part 2: Logins & permissions](/playbooks/help-hub-accounts)
- Executable work plans — see [part 3: Sprint Planner](/playbooks/help-hub-sprint-planner)

Questions or customizations: use repository issue tracking or your internal governance owner.
