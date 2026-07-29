# Governance Search Submission

Stand: 2026-07-29

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

### Lokal — geprüft 2026-07-29

- [x] `seo:sitemap-check` OK (2006 indexierbare URLs in Groups)
- [x] `seo:sitemap-check --http` OK (`robots.txt` + alle Sitemap-Gruppen 200)
- [x] Lokales `robots.txt` enthält `Allow: /`, Disallows (account/login/api/sessions) und `Sitemap:`
- [x] Phase-B-Einstiegs-Playbooks lokal 200 (z. B. Chooser)

---

## Production-Stichprobe — 2026-07-29

Host: `https://governance.binom.net`

### Grün (Kern / SEO-Index / Legacy-301 / Tools)

| URL | Ergebnis |
|-----|----------|
| `/`, `/governance`, `/governance/radar`, `/about` | 200 |
| `/resources`, `/suppliers`, `/playbooks`, `/compliance` | 200 |
| `/tools` | 301 → `/tools/` (200) |
| `/sitemap.xml`, `/sitemap-pages.xml`, `/sitemap-playbooks.xml` | 200 |
| `/governance/berater` | 301 → `?tab=advisor` |
| `/governance/stacks` | 301 → `?tab=guides#stacks` |
| `/governance/kpi-requirements` | 301 → `?tab=guides#kpi` |
| `/governance/supplier-discovery` | 301 → `?tab=guides#supplier` |
| `/governance/discovery-canvas` | 301 → `?tab=canvas` |
| `/tools/report-inventory`, `kpi-definition`, PBI/Tableau/Qlik Generatoren | 200 |
| `/playbooks/eight-pillars` (Referenz, älter) | 200 |

### Blocker bis Deploy (Code/Content lokal grün, Production noch alt)

| Check | Production | Lokal / Soll |
|-------|------------|--------------|
| `/robots.txt` | nur `User-agent: *` + leeres `Disallow:` — **kein** `Sitemap:`, keine Account/API-Disallows | volles Robots aus `RobotsController` |
| Phase-B-Playbooks (Chooser, Source-Load, BI, Interview) | **404** | 200 |
| `sitemap-playbooks.xml` enthält neue Phase-B-Slugs | nein (nur ältere wie `eight-pillars`) | ja nach Deploy |

**Nächster Schritt Ops:** aktuellen Stand (inkl. `content/stories/` Phase B + `RobotsController`) auf Production deployen, dann Stichprobe wiederholen und erst danach Sitemap in GSC/Bing einreichen.

Nach Deploy erneut prüfen:

```bash
curl -sS https://governance.binom.net/robots.txt
curl -sS -o /dev/null -w '%{http_code}\n' https://governance.binom.net/playbooks/choose-governance-platform-starting-point
```

---

## Einreichung (manuell — du)

Erst sinnvoll, wenn Production-robots korrekt ist und gewünschte Playbooks 200 liefern.

1. **Google Search Console**
   - Property für `https://governance.binom.net` verifizieren
   - Sitemap einreichen: `https://governance.binom.net/sitemap.xml`
   - Indexierungsfehler beobachten

2. **Bing Webmaster Tools**
   - Site verifizieren
   - Dieselbe Sitemap einreichen
   - Site Scan nutzen

3. **robots.txt** (nach Deploy)
   - `https://governance.binom.net/robots.txt` muss `Allow: /`, Disallow für Account/API/Sessions und `Sitemap:` mit absoluter HTTPS-URL zeigen

4. **Manuelle URL-Stichprobe (erste 20+)** — Kern bereits 2026-07-29 OK; Phase-B-Playbooks nach Deploy nachziehen

   - `/`, `/governance`, `/governance/radar`
   - Legacy-Landings nur als **301**: `/governance/berater`, `/governance/stacks`, …
   - `/resources`, `/suppliers`, `/playbooks`, `/tools`, `/compliance`, `/about`
   - Phase-B-Einstiege: Chooser, which-source, Salesforce, semantic-layer, inventory→metric, formula-generators, interview
   - Tools: Report Inventory, KPI Definition, Formel-Generatoren
   - Footer-Link „Sitemap“ öffnet `/sitemap.xml`

5. **Nach 7–14 Tagen**
   - Coverage/Indexing Reports in ein SEO-Backlog übernehmen
   - Optional IndexNow für spätere Content-Updates

---

## Nicht einreichen / nicht indexieren

- `/account/*`, `/login`, `/register`
- `/api/*`
- `/governance/sessions*`
- interne Such-Filterzustände (`/search?q=…`)
- Redirect-only Governance-Landings (nur Hub + Radar indexieren)

## Entscheidungseinstieg (Klarstellung)

Keine neuen Decision Pages für SEO/Cadence. Einstieg = Governance Advisor; Phase-B-Playbooks sind Vertiefung (nach Deploy indexierbar).
