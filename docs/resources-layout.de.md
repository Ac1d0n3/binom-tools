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

Shared shell/theme: `resources/{views,js,css}/foundations|shared` (chrome only).  
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

## Not this

- `resources/*/domains/` mirrors
- Thick catalogs in `config/*.php`
- Extra `config/foundations/` for a single taxonomy file
