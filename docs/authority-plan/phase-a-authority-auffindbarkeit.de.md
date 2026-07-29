# Phase A — Authority & Auffindbarkeit

Stand: 2026-07-29  
Status: **Code + lokale SEO-Checks erledigt** · Production-Stichprobe Kern grün · **Deploy nötig** für aktuelles robots + Phase-B-Playbooks · GSC/Bing manuell offen  
Zurück: [index.de.md](index.de.md) · Weiter: [Phase B](phase-b-infos-beratung.de.md) *(erledigt)* · [Phase C](phase-c-artefakt-tiefe.de.md)

## Ziel

Technisch und inhaltlich auffindbar sein; Thomas Lindackers als **Autor/Kurator** des Help Hubs sichtbar.  
Kein „öffentlicher Berater“-Framing. SVA-Projektanfragen erst nach interner Freigabe erwähnen.

## Done when

- [ ] Search Console + Bing: Property verifiziert, Sitemap eingereicht *(manuell / du — nach Production-Deploy)*
- [x] Author-Signal ohne Berater-Banner (Attribution + Meta + Schema)
- [x] Home/About: drei klare Einstiege (Governance / BI / Quelle)
- [x] Top-URL-Stichprobe Kern auf Production *(2026-07-29)* — Phase-B-Playbooks **404 bis Deploy** (siehe Ops-Doc)
- [x] `php artisan seo:sitemap-check` (+ `--http`) lokal grün *(2026-07-29)*
- [ ] Production `robots.txt` = aktueller `RobotsController` (Sitemap + Disallows) *(nach Deploy erneut prüfen)*

Ops-Protokoll: [governance-search-submission.de.md](../governance-search-submission.de.md)

---

## A1 — SEO Ops (Production)

Referenz: [governance-search-submission.de.md](../governance-search-submission.de.md)

Technik im Repo:

- [x] robots.txt, Sitemap-Index, Footer HTML + XML *(Code; lokal verifiziert)*
- [x] Legacy-Landings 301 auf Hub *(Production verifiziert)*

Noch auf Production / manuell:

- [x] Host erreichbar HTTPS (`governance.binom.net`) — Kernseiten 200 *(2026-07-29)*
- [ ] Deploy: aktueller Code + `content/stories` (Phase B), damit robots + neue Playbooks live sind
- [ ] Google Search Console + Bing (Property + Sitemap)
- [ ] Phase-B-Playbooks in Top-URL-Stichprobe nach Deploy auf 200
- [ ] Nach 7–14 Tagen Coverage-Backlog

### Top-URL-Stichprobe

| Cluster | Status 2026-07-29 |
|---------|-------------------|
| Kern Hub / About / Resources / Suppliers / Playbooks-Index / Tools / Compliance / Radar | Production 200 |
| Legacy 301 → Hub-Tabs | Production 301 OK |
| Sitemap-Index + Gruppen | Production 200 |
| Formel-/KPI-Tools | Production 200 |
| Phase-B-Einstiegs-Playbooks | Production **404** (lokal 200) — Deploy |
| robots.txt vollständig | Production **veraltet** (lokal OK) — Deploy |

---

## A2 — Author-Signal — angepasst

**Kein Byline-Banner** auf öffentlichen Seiten.

Autor bleibt sichtbar als:

- Hero-Attribution „Von / By Thomas Lindackers“
- Playbook Author-Meta + Schema.org Person
- About / Meta / OG als Help-Hub-Autor

| Fläche | Status |
|--------|--------|
| Governance Hub | Explore-Nav + „Von Thomas Lindackers“ im Lead |
| About | Help-Hub-Copy, kein Banner |
| Playbook Show | Author-Meta + published/updated |
| Tools Landing | nur Hero-Attribution |
| Resources / Suppliers / Compliance | kuratiert / Next-Links, kein Banner |

SVA: **noch nicht** nennen — erst nach Freigabe.

---

## A3 — Einstiege Home / About — erledigt

- [x] Clone = Primary
- [x] Ghost-CTAs: Governance Hub, BI-Metriken (`trusted-metrics`), Quelle (`/suppliers`)
- [x] About: Warum Governance + binom.net + Hub-CTA

---

## A4 — Interne Verdrahtung — erledigt (+ Phase B)

- [x] Hub-Explore-Nav → Playbooks, Paths, Roles, Tools, Resources, Sources, Compliance
- [x] Suppliers → Governance / KPI / PII Links
- [x] Phase B: Stack-/Supplier-/BI-Journeys, Guides, Discovery, Formel-Tools → Playbook-Vertiefung *(Advisor = Entscheidungseinstieg; keine weiteren Decision Pages)*

---

## A5 — Technik

- [x] OG + JSON-LD auf Kernseiten
- [x] Footer `/sitemap.xml`
- [x] Playbook Published/Updated
- [x] Lokal: `seo:sitemap-check` / `--http`

```bash
php artisan seo:sitemap-check
php artisan seo:sitemap-check --http
```

---

## Nicht in Phase A

- Decision Pages als paralleler Wave → **abgelehnt**; Advisor = Einstieg, B-Playbooks = Vertiefung  
- Supplier→Model → C  
- LinkedIn → D  
- Discussion → E  
- SVA-Hinweis → erst nach Freigabe

## Notizen

2026-07-28: Byline-Banner entfernt; Help-Hub/Autor-Copy; SVA bewusst nicht erwähnt.  
2026-07-29: Top-URL-Liste um Phase-B-Serien + Formel-Tools; lokale Sitemap-Checks grün; Production-Kern OK; **Deploy-Gap** robots + neue Playbooks dokumentiert; Decision-Klarstellung (Advisor).
