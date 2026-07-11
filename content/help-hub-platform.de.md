---
title: Governance Help Hub
description: So ist der Governance Help Hub aufgebaut — Markdown-Stories, interaktive Tools und i18n als wiederverwendbares Starter-Template.
category: Help Hub
tags:
  - help-hub
  - markdown
  - governance
order: 0
---

## Überblick

Diese Seite beschreibt, wie **binom-tools** als **Governance Help Hub** funktioniert: Stories als Markdown-Playbooks, interaktive Referenz-Tools, zweisprachige Oberfläche und ein schlanker Laravel-Stack ohne CMS.

Du kannst das Projekt klonen, eigene Stories unter `content/` ablegen und optional interaktive Tools ergänzen — ideal für Data-Governance- und BI-Teams, die Wissen versionierbar und nah am Code halten wollen.

> Kein Gateway, kein CMS: Inhalte leben in Git, Reviews laufen wie bei Application Code.

## Architektur

| Schicht | Technologie | Zweck |
| --- | --- | --- |
| Backend | Laravel 12, PHP 8.2+ | Routing, Playbook-Repository, Blade Views |
| Frontend | Vite, Vanilla JS, CSS | Shell, Suche, TOC, Theme, Locale |
| Stories | Markdown + YAML Frontmatter | Playbooks unter `content/` |
| Tools | Blade + Tool-spezifisches JS | Interaktive Referenz-Workflows |
| Assets | `public/` | Bilder, gebaute CSS/JS |

**Datenfluss Stories:** `content/*.md` → `PlaybookRepository` → Blade (`playbooks/show`) → gerendertes HTML + TOC aus Überschriften.

**Datenfluss Tools:** `config/tools.php` → Controller → Blade-Karten und Sidebar-Navigation.

## Projektstruktur

```text
binom-tools/
├── app/
│   ├── Catalog/              # Landing-Vorschau (neueste Tools & Stories)
│   ├── Http/Controllers/     # Landing, Tools, Playbooks, Legal
│   └── Playbooks/            # Parser, Renderer, Repository
├── config/tools.php          # Tool-Navigation, Hero, Ecosystem-Links
├── content/                  # Markdown-Stories (.de.md / .en.md)
├── public/images/            # Hero-Grafiken, Logos
├── resources/
│   ├── css/                  # Shell, Playbooks, Themes
│   ├── js/                   # Locale, TOC, Suche, Theme
│   └── views/                # Blade Layouts & Komponenten
├── routes/web.php
└── tests/                    # Feature- & Unit-Tests
```

Wichtige UI-Komponenten:

- `<x-tools.card>` — Karten für Tools und Landing
- `<x-playbooks.card>` — Story-Karten in Übersicht & Landing
- `<x-playbooks.toc>` — Inhaltsverzeichnis mit Scroll-Spy
- `<x-tools.sidebar>` — Navigation (Startseite, Stories, Tools)

## Story hinzufügen

1. Zwei Dateien anlegen: `content/mein-thema.de.md` und `content/mein-thema.en.md`
2. YAML-Frontmatter ausfüllen (Titel, Beschreibung, Kategorie, Tags, optional `order` und `hero`)
3. Body in Markdown schreiben — `##` und `###` erzeugen automatisch Anker und TOC-Einträge

```yaml
---
title: Meine Governance Story
description: Kurzbeschreibung für Karten und SEO.
category: Datenplattform
tags:
  - rbac
  - audit
order: 2
hero: images/playbooks/mein-thema.svg
---
```

Die Route `/playbooks/mein-thema` steht nach dem nächsten Request automatisch zur Verfügung — kein Eintrag in `routes/web.php` nötig.

### Frontmatter-Felder

| Feld | Pflicht | Beschreibung |
| --- | --- | --- |
| `title` | ja | Seitentitel |
| `description` | ja | Lead-Text auf Detail- und Kartenansicht |
| `category` | nein | Meta-Zeile (z. B. „Datenplattform“) |
| `tags` | nein | Filter-Chips in der Story-Übersicht |
| `order` | nein | Sortierung in Sidebar und Index (aufsteigend) |
| `hero` | nein | Pfad unter `public/` für Hero-Bild |

## Tool hinzufügen

1. Eintrag in `config/tools.php` → `nav` ergänzen (id, route, label, description, icon, accent)
2. Route in `routes/web.php` und Controller + View unter `resources/views/tools/…`
3. Optional: eigenes Vite-Entry für Tool-JavaScript

Tools erscheinen automatisch in Sidebar, Tools-Übersicht (`/tools`) und auf der Landing (neueste neun + Übersichtskarte).

## i18n & Theme

- UI-Strings: `resources/js/locale.js` (`data-i18n`, `data-card-id`, `data-text-de` / `data-text-en`)
- Playbook-Inhalte: getrennte Locale-Dateien (`.de.md`, `.en.md`)
- Theme: Light/Dark via `data-color-scheme` und CSS-Variablen in `resources/css/themes/`

## Lokale Entwicklung

```bash
git clone <repository-url> binom-tools
cd binom-tools
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev          # oder: npm run build
php artisan serve
```

Optional in `.env`:

```env
BINOM_TOOLS_REPO_URL=https://github.com/dein-org/dein-help-hub
```

binom-ngx Docs: fest in `config/tools.php` → `https://ngx-docs.binom.net`

## Deployment

1. `npm run build` — Vite-Assets nach `public/build/`
2. `composer install --no-dev --optimize-autoloader`
3. `php artisan config:cache` und `php artisan route:cache`
4. Webserver document root auf `public/` zeigen

Stories sind reine Dateien — Content-Updates können per Git Pull ohne DB-Migration ausgerollt werden.

## Download & Fork

Das Projekt ist als **Starter-Template** gedacht:

1. **Repository klonen** und unter eigenem Namen weiterentwickeln
2. **`content/`** durch eigene Governance-Stories ersetzen oder erweitern
3. **`config/tools.php`** und Branding (`resources/views/components/tools/brand.blade.php`) anpassen
4. Optional nur Stories nutzen — interaktive Tools können schrittweise ergänzt werden

Repository-URL (konfigurierbar): siehe `BINOM_TOOLS_REPO_URL` in `.env` bzw. `config/tools.php` → `links.repository`.

### Checkliste für einen eigenen Help Hub

- [ ] Branding (Logo, Domain, Footer)
- [ ] Mindestens eine Story in DE + EN
- [ ] Kategorien und Tags definieren
- [ ] Impressum / Legal (`/impressum`) prüfen
- [ ] CI: `php artisan test` und `npm run build` in Pipeline

## Support & Erweiterung

Typische Erweiterungen ohne Architektur-Bruch:

- PDF-Export einzelner Stories (z. B. über Browser Print CSS)
- Volltextsuche serverseitig (statt Client-Filter auf Übersichtsseiten)
- Authentifizierung für interne Playbooks (Laravel Middleware)

Fragen oder Anpassungen: über das Repository-Issue-Tracking oder euren internen Governance-Owner.
