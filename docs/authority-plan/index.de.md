# Authority Roadmap — Governance & BI Anlaufstelle

Stand: 2026-07-29

Ziel: `governance.binom.net` wird die praktische Anlaufstelle für Data Governance und BI. Thomas Lindackers ist als Autor/Kurator des Help Hubs sichtbar (kein „öffentlicher Berater“-Framing; ggf. später SVA-Projekte — erst nach Freigabe). Nutzer finden Orientierung, Fragen, Tools und Nachweise — und haben einen Grund zu folgen.

Verwandte Docs:

- [governance-www-plan.de.md](../governance-www-plan.de.md) — Positionierung, SEO-Cluster, Hub-Architektur
- [governance-workflow-tool-plans.de.md](../governance-workflow-tool-plans.de.md) — Discovery/Tools
- [governance-search-submission.de.md](../governance-search-submission.de.md) — Search Console / Bing
- [ia-roles-paths-sprint.md](../ia-roles-paths-sprint.md) — Advisor → Roles → Paths → Sprint

## Phasen (Reihenfolge)

| Phase | Datei | Fokus | Status |
|-------|--------|--------|--------|
| A | [phase-a-authority-auffindbarkeit.de.md](phase-a-authority-auffindbarkeit.de.md) | Author-Signal, SEO-Ops, Einstiege | Code + lokale Checks erledigt · Production-Kern OK · Deploy (robots + Phase-B-Playbooks) + GSC/Bing offen |
| B | [phase-b-infos-beratung.de.md](phase-b-infos-beratung.de.md) | Advisor + vertiefende Playbooks, BI-Parität | **erledigt** ([Briefs](phase-b-story-briefs.de.md) · [Plattform](governance-stack-decisions-story-briefs.de.md) · [Source Load](source-load-decisions-story-briefs.de.md) · [BI](bi-governance-decisions-story-briefs.de.md)) |
| C | [phase-c-artefakt-tiefe.de.md](phase-c-artefakt-tiefe.de.md) | Supplier→Mart, Exports, Proof | offen · baut auf Advisor + Phase-B-Playbooks / Tools auf |
| D | [phase-d-reichweite.de.md](phase-d-reichweite.de.md) | LinkedIn, Zitate, IndexNow, Follow | offen · Cadence = Advisor + bestehende Stories/Tools |
| E | [phase-e-discussion.de.md](phase-e-discussion.de.md) | Community / Discussion | später, nur DB · Threads an Phase-B-Playbooks binden |

## Phase-B-Inventar (für C–E wiederverwenden)

| Serie / Asset | Einstiege (nicht jede Part im Hub) |
|---------------|-------------------------------------|
| `governance-platform-starting-points` | `choose-governance-platform-starting-point`, optional Fabric-Start |
| `source-load-decisions` Parts 1–9 | `which-source-to-load-first`, Salesforce, SaaS-Skip (+ Vendor-Parts Serie-UI) |
| `bi-governance-decisions` Parts 1–8 | Inventory→Trusted Metric, Semantic Layer, Formel-Generatoren |
| Warehouse Part 11 | `from-stakeholder-interview-to-table-model` |
| Tools (BI + Discovery) | Report Inventory, KPI Definition, Formel-Generatoren (PBI/Tableau/Qlik), Source Scope, Mart Brief |
| Profil | Basis-Rolle → Advisor-Vorbelegung (`preferredRole`) |

## Kernversprechen

> Data Governance & BI praktisch starten: Entscheidung klären → Fragen stellen → Artefakte erzeugen → Nachweise finden.

## Abgrenzung

- Keine Rechtsberatung, kein Vendor-Ersatz, kein „alles live integriert“.
- Keine neuen Sidebar-Hubs ohne explizite Absprache.
- DMBOK / CDMP / DCAM = Fachsprache, nicht Site-Struktur.
- Qlik Set Analysis Workbench nicht redesignen.
- Discussion erst nach Phasen A–D, nur MySQL (kein Local Storage).
- **Keine weiteren Decision Pages.** Einstieg = Governance Advisor (Fragen → Empfehlung → Tools). Phase-B-Playbooks sind Vertiefung hinter dem Advisor — nicht ein paralleler Content-Wave.
- Phase B liefert Advisor-Verdrahtung + vertiefende Playbooks (Load/Skip, Plattform-Einstieg, BI); Phase C liefert **Artefakt-Tiefe** (Supplier→Mart, Proof) — keine neuen Decision-Stories.

## Wie abarbeiten

1. Phase öffnen, Checkboxen der Reihe nach abhaken.
2. Pro Phase: „Done when“ prüfen, bevor die nächste startet.
3. Offene Punkte als Issues/Backlog notieren — Phasen nicht vermischen.
4. Content bleibt Markdown unter `content/stories/` (kein CMS in diesem Roadmap-Wave).

## Erfolg gesamt

- Unter 60 Sekunden klarer Startpunkt (Governance oder BI).
- Suche landet in Entscheidung + nächstem Artefakt, nicht Textfriedhof.
- Name auf Start, About, Playbooks, Hub, Tools sichtbar.
- Öffentliche Hubs indexierbar; Sessions/Accounts nicht.
- Follow über Nutzen (Content, Radar, Tools) — Forum optional ganz am Ende.
