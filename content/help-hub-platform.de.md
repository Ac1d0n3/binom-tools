---
title: Governance Help Hub
description: So ist der Governance Help Hub aufgebaut — Markdown-Stories, interaktive Tools und i18n als wiederverwendbares Starter-Template.
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

## Überblick

Diese Seite beschreibt, wie **binom-tools** als **Governance Help Hub** funktioniert: Stories als Markdown-Playbooks, interaktive Referenz-Tools, zweisprachige Oberfläche und ein schlanker Laravel-Stack ohne CMS.

Du kannst das Projekt klonen, eigene Stories unter `content/` ablegen und optional interaktive Tools ergänzen — ideal für Data-Governance- und BI-Teams, die Wissen versionierbar und nah am Code halten wollen.

Diese Story ist **Teil 1** der Serie *Governance Help Hub*. Teil 2 behandelt optionale [Logins und Rechte](/playbooks/help-hub-accounts), Teil 3 den [Sprint Planner](/playbooks/help-hub-sprint-planner) (Vorlagen, Storage, Fence-Syntax).

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
│   ├── Accounts/             # Optionale Logins, ACL, Plan-Store (siehe Teil 2)
│   ├── Catalog/              # Landing-Vorschau (neueste Tools & Stories)
│   ├── Http/Controllers/     # Landing, Tools, Playbooks, Sprint Planner, Legal
│   ├── Playbooks/            # Parser, Renderer, Repository
│   └── SprintPlanner/        # Vorlagen-Parser, Seeds (siehe Teil 3)
├── config/tools.php          # Tool-Navigation, Hero, Ecosystem-Links
├── content/                  # Markdown-Stories (.de.md / .en.md)
├── content/sprint-plans/     # Sprint-Planner-Vorlagen
├── public/images/            # Hero-Grafiken, Logos
├── resources/
│   ├── css/                  # Shell, Playbooks, Themes
│   ├── js/                   # Locale, TOC, Suche, Theme, Sprint Planner
│   └── views/                # Blade Layouts & Komponenten
├── routes/web.php
└── tests/                    # Feature- & Unit-Tests
```

Wichtige UI-Komponenten:

- `<x-tools.card>` — Karten für Tools und Landing
- `<x-playbooks.card>` — Story-Karten in Übersicht & Landing
- `<x-playbooks.toc>` — Inhaltsverzeichnis mit Scroll-Spy
- `<x-tools.sidebar>` — Navigation (Startseite, Stories, Tools, Sprint Planner)

## Story hinzufügen

1. Zwei Dateien anlegen: `content/mein-thema.de.md` und `content/mein-thema.en.md`
2. YAML-Frontmatter ausfüllen (Titel, Beschreibung, Kategorie, Tags, optional `order` und `hero`)
3. Body in Markdown schreiben — `##` und `###` erzeugen automatisch Anker und TOC-Einträge

**Blockquote (Attribution in der letzten Zeile):**

```markdown
> Governance ist kein Tool-Problem, sondern ein Verantwortungsproblem.
> — Thomas Lindackers
```

**Eingebettetes Video (lädt nach Cookie-Einwilligung für externe Medien):**

````markdown
```video Demo-Titel
https://www.youtube.com/watch?v=dQw4w9WgXcQ
```
````

**Lokales Video** (Datei unter `public/videos/playbooks/`, offline speicherbar):

````markdown
```video Intro
/videos/playbooks/intro.mp4
```
````

**Offline lesen:** Auf der Story-Seite **Offline speichern** tippen (einzeln) oder auf `/playbooks` **Alle offline speichern**. Es wird nichts im Hintergrund geladen. Externe Embeds brauchen Netz; lokale Videos und Bilder werden mit heruntergeladen.

**Simple Flow Chart (Chevron — gleicher Stil wie Tool-Workflows):**

````markdown
```flowchart
Governance-Richtlinie
dbt-Projekt
Warehouse [active]
BI-Report
```

```flow linear
Metadaten sammeln
Daten profilieren
Katalog veröffentlichen
```
````

Optionale Marker: `[active]` oder `[done]` in der Schrittzeile für Fortschritt.

**Wann welches Layout**

| Fence | Einsatz |
|-------|---------|
| `flowchart` / `flow chevron` | Kurze Prozessketten (Tool-Chevrons; bei wenig Platz vertikal gestapelt) |
| `flow linear` | Horizontale Boxen mit →-Pfeilen |
| `flow linear vertical` | Längere Stacks: Label / ↓ / Label (ohne äußere Box) |

```yaml
---
title: Meine Governance Story
description: Kurzbeschreibung für Karten und SEO.
publishedAt: 2026-07-16
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
| `publishedAt` | **ja für Sortierung** | Redaktionelles Publish-Datum (`YYYY-MM-DD` oder `YYYY-MM-DD HH:MM`). Steuert Neueste/Älteste — nicht die Datei-mtime |
| `category` | nein | Meta-Zeile (z. B. „Datenplattform“) |
| `tags` | nein | Filter-Chips in der Story-Übersicht |
| `order` | nein | Sortierung in Sidebar und Index (aufsteigend) |
| `hero` | nein | Pfad unter `public/` für Hero-Bild |
| `series` | nein | Serien-ID — gruppiert Stories in der **Serien**-Ansicht und Serien-Navigation |
| `seriesPart` | nein | Teilnummer innerhalb der Serie (1, 2, 3 …) |
| `seriesTitle` | nein | Anzeigename der Serie (pro Locale-Datei) |

Stories mit gleicher `series`-ID erscheinen auf `/playbooks` (Ansicht **Serien**) als eine Serien-Karte mit Gesamt-Lesezeit und Hero von Teil 1. Auf der Detailseite zeigt der Serien-Block **Teil X von Y** und Links zu allen Teilen.

Pager-Verhalten für Serien-Mitglieder: `PLAYBOOKS_SERIES_PAGER=both|series|global` in `.env` (Default `both`).

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
- Optionale Authentifizierung und Story-ACL — siehe [Teil 2: Logins & Rechte](/playbooks/help-hub-accounts)
- Ausführbare Arbeitspläne — siehe [Teil 3: Sprint Planner](/playbooks/help-hub-sprint-planner)

Fragen oder Anpassungen: über das Repository-Issue-Tracking oder euren internen Governance-Owner.
