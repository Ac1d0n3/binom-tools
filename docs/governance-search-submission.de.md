# Governance Search Submission

Stand: 2026-07-27

Ops-Checkliste nach technischem SEO (Sitemap, robots, Canonicals, Hub-Content).

## Voraussetzung

- Production `APP_URL` zeigt auf `https://governance.binom.net` (HTTPS).
- Footer enthält Link auf `/sitemap.xml` (`footer.sitemap`).
- Governance-Landings (`/berater`, `/stacks`, …) sind **301** auf den Hub und **nicht** in der Sitemap.
- Lokal prüfen:

```bash
php artisan seo:sitemap-check
php artisan seo:sitemap-check --http
```

## Einreichung

1. **Google Search Console**
   - Property für `https://governance.binom.net` verifizieren
   - Sitemap einreichen: `https://governance.binom.net/sitemap.xml`
   - Indexierungsfehler beobachten

2. **Bing Webmaster Tools**
   - Site verifizieren
   - Dieselbe Sitemap einreichen
   - Site Scan nutzen

3. **robots.txt**
   - `https://governance.binom.net/robots.txt` muss `Allow: /`, Disallow für Account/API/Sessions und `Sitemap:` mit absoluter URL zeigen

4. **Manuelle URL-Stichprobe (erste 20)**
   - `/`, `/governance`, `/governance/radar`
   - Legacy-Landings nur als **301** prüfen: `/governance/berater`, `/governance/stacks`, `/governance/kpi-requirements`, `/governance/supplier-discovery`, `/governance/discovery-canvas`
   - `/resources`, `/suppliers`, `/playbooks`, `/tools`, `/compliance`, `/about`
   - 3–5 Top-Playbooks und Top-Tools
   - Footer-Link „Sitemap“ öffnet `/sitemap.xml`

5. **Nach 7–14 Tagen**
   - Coverage/Indexing Reports in ein SEO-Backlog übernehmen
   - Optional IndexNow für spätere Content-Updates

## Nicht einreichen / nicht indexieren

- `/account/*`, `/login`, `/register`
- `/api/*`
- `/governance/sessions*`
- interne Such-Filterzustände (`/search?q=…`)
- Redirect-only Governance-Landings (nur Hub + Radar indexieren)
