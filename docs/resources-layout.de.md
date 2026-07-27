# Mega-modules layout

Stand: 2026-07-27

## Target

```text
modules/<id>/
  config.php              # → config('<id>')  (Env + thin CatalogJsonLoader facades)
  <key>.config.php        # → config('<key>') optional (e.g. governance-radar.config.php)
  js/
  css/
  views/                  # view('<id>::…')
  script/                 # Domain PHP + Controllers/
```

### Shell / Shared (kein Feature-Code)

```text
resources/js/
  app.js
  shell/                  # locale, theme, shell-layout, consent, sidenav, …
  shared/                 # modal, tabs, overview-filter*
resources/views/
  foundations/            # Layout-Chrome only
  shared/  components/
resources/css/            # app + shell / tools-shell chrome
```

Catalog bodies: `content/catalogs/`.  
`config/` = Laravel framework + `storage.php` + `taxonomy.php` — **no** domain dumps, **no** `config/foundations/` folder.

## Wiring

| Concern | Mechanism |
|---------|-----------|
| PHP | Composer PSR-4 → `modules/<id>/script/` |
| Blade | `ModulesServiceProvider` namespaces |
| Module config | `ModulesServiceProvider::register()` loads `config.php` / `*.config.php` |
| Vite | `modules/<id>/js|css/…` |
| FTP | whole `modules/` tree |

## New work

Checkliste: [new-module-checklist.de.md](./new-module-checklist.de.md).  
Cursor: `.cursor/rules/mega-modules.mdc`, `resources-js.mdc`, `config-thin.mdc`.

## Not this

- `resources/*/domains/` mirrors
- Feature trees under `resources/js/` or `resources/css/`
- Thick catalogs in `config/*.php`
- Extra `config/foundations/` for a single taxonomy file
- Root JS re-export stubs instead of `shell/` / `shared/`
