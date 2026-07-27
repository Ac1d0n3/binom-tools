# Neues Feature / Modul anlegen

Stand: 2026-07-27

Kurzfassung: **Alles für ein Feature liegt unter `modules/<id>/`**. Shell und Shared UI bleiben in `resources/`. Dicke Daten gehören in `content/catalogs/`, nicht in `config/`.

Siehe auch: [resources-layout.de.md](./resources-layout.de.md), `.cursor/rules/mega-modules.mdc`.

## Vor dem Start klären

- [ ] Gehört es in ein **bestehendes** Modul (`tools`, `governance`, …) oder braucht es ein **neues** `modules/<id>/`?
- [ ] Braucht es einen **neuen Sidebar-/Hub-Eintrag**? → nur mit explizitem Go
- [ ] Shared UI vorhanden? (`resources/js/shared/`, Shell-Locale) — nicht kopieren
- [ ] Qlik / BI-Workbench? → hands-off ohne expliziten Auftrag

## Verzeichnis

```text
modules/<id>/
  config.php                 # optional, thin
  <key>.config.php           # optional
  js/
  css/
  views/
  script/
    Controllers/
```

## Pflicht-Wiring

| Schritt | Wo |
|---------|-----|
| 1. Ordner anlegen | `modules/<id>/…` |
| 2. PSR-4 | `composer.json`: `App\<Domain>\` → `modules/<id>/script/`, Controllers → `…/script/Controllers/` |
| 3. `composer dump-autoload` | lokal |
| 4. Blade | `view('<id>::page')` — Namespace kommt von `ModulesServiceProvider` |
| 5. Config | nur Modul-Root; `config('<id>')` / `config('<key>')` |
| 6. Vite | Entry in `vite.config.js` → `modules/<id>/js|css/…` |
| 7. Routes | auf Modul-Controller zeigen |
| 8. DE+EN Copy | UI-Strings / Stories falls nötig |
| 9. Tests | PHPUnit und/oder Vitest bei Logik |
| 10. Build | `npm run build` |

FTP/Deploy: gesamter `modules/`-Tree wird gepackt — **keine** Feature-Datei-Checklisten im Packer pflegen.

## Wohin **nicht**

| Falsch | Richtig |
|--------|---------|
| `resources/js/<feature>/` | `modules/<id>/js/` |
| `resources/views/domains/…` | `modules/<id>/views/` |
| `config/<feature>.php` (dick) | `modules/<id>/config.php` + `content/catalogs/` |
| `resources/*/domains/` Spiegel | Mega-Modul |
| Root-Re-Exports unter `resources/js/` | direkt `shell/` / `shared/` importieren |

## Shell vs Shared vs Modul (JS)

| Bedarf | Ort |
|--------|-----|
| Locale, Theme, Layout, Consent, Phone-Gate | `resources/js/shell/` (+ ggf. `app.js`) |
| Modal, Tabs, Overview-Filter (mehrere Module) | `resources/js/shared/` |
| Feature-Verhalten | `modules/<id>/js/` |

## Nach dem Merge / vor „fertig“

- [ ] Keine toten Dateien unter altem `resources/js|css|views/…`-Pfad
- [ ] Keine dicken Arrays neu in `config/`
- [ ] `@vite` / Manifest stimmt mit `vite.config.js` überein
- [ ] Bei Hub-Mobile: Skill `binom-hub-mobile` beachten
