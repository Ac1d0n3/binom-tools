# Phase A — Authority & Auffindbarkeit

Stand: 2026-07-28  
Status: **Code erledigt** · Ops (Search Console/Bing) manuell offen  
Zurück: [index.de.md](index.de.md) · Weiter: [Phase B](phase-b-infos-beratung.de.md)

## Ziel

Technisch und inhaltlich auffindbar sein; Thomas Lindackers als **Autor/Kurator** des Help Hubs sichtbar.  
Kein „öffentlicher Berater“-Framing. SVA-Projektanfragen erst nach interner Freigabe erwähnen.

## Done when

- [ ] Search Console + Bing: Property verifiziert, Sitemap eingereicht *(manuell / Ops)*
- [x] Author-Signal ohne Berater-Banner (Attribution + Meta + Schema)
- [x] Home/About: drei klare Einstiege (Governance / BI / Quelle)
- [ ] Top-20 URLs manuell geprüft *(Ops)*
- [ ] `php artisan seo:sitemap-check` grün *(Ops / Deploy)*

---

## A1 — SEO Ops (Production) — manuell

Referenz: [governance-search-submission.de.md](../governance-search-submission.de.md)

Technik im Repo:

- [x] robots.txt, Sitemap-Index, Footer HTML + XML
- [x] Legacy-Landings 301 auf Hub

Noch auf Production:

- [ ] APP_URL HTTPS
- [ ] Google Search Console + Bing
- [ ] Top-20 URL-Stichprobe
- [ ] Nach 7–14 Tagen Coverage-Backlog

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

## A4 — Interne Verdrahtung — erledigt

- [x] Hub-Explore-Nav → Playbooks, Paths, Roles, Tools, Resources, Sources, Compliance
- [x] Suppliers → Governance / KPI / PII Links

---

## A5 — Technik

- [x] OG + JSON-LD auf Kernseiten
- [x] Footer `/sitemap.xml`
- [x] Playbook Published/Updated

Lokal/Production:

```bash
php artisan seo:sitemap-check
php artisan seo:sitemap-check --http
```

---

## Nicht in Phase A

- Decision Pages → B  
- Supplier→Model → C  
- LinkedIn → D  
- Discussion → E  
- SVA-Hinweis → erst nach Freigabe

## Notizen

2026-07-28: Byline-Banner entfernt; Copy von „öffentlicher Berater“ auf Help-Hub/Autor umgestellt; SVA bewusst nicht erwähnt.
