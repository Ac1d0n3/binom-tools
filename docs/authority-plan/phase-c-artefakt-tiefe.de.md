# Phase C — Artefakt-Tiefe & Proof

Stand: 2026-07-29  
Status: **erledigt** · baut auf Phase B auf (bestehende Playbooks / Advisor-Links ≠ Mart-Guides)  
Zurück: [Phase B](phase-b-infos-beratung.de.md) · Index: [index.de.md](index.de.md) · Weiter: [Phase D](phase-d-reichweite.de.md)

## Ziel

Einzigartigen Mehrwert liefern, den Vendor-Blogs nicht haben: von Quelle/Workshop zu konkreten Artefakten und nachvollziehbaren Beispielen.

**Einstieg für Entscheidungen bleibt der Advisor** — Phase C schreibt keine neuen Decision-Stories. Bestehende Phase-B-Playbooks nur verlinken.

## Done when

- [x] Mindestens 5 Supplier-to-Mart Mini-Guides live *(Tiefe — nicht nur Load/Skip aus B)*
- [x] Gemeinsame Export-/Artefakt-Namen dokumentiert und in Tools erwähnt
- [x] 2–3 anonymisierte Proof-/Workshop-Stories
- [x] Jede behandelte Supplier-Seite: Intro + nächster Tool-Schritt + interne Links *(inkl. Link zur Phase-B Load/Skip-Story + Advisor/Journey)*

---

## Ausgangslage nach Phase B (nicht nochmal schreiben)

Load/Skip- und Authority-Playbooks sind live in Serie [`source-load-decisions`](source-load-decisions-story-briefs.de.md). Der Advisor/Journey bleibt der Entscheidungseinstieg. Phase C **vertieft** zu Grain → Mart → Artefakt und verdichtet Supplier-Library-Seiten.

Briefs: [`supplier-to-mart-story-briefs.de.md`](supplier-to-mart-story-briefs.de.md)

| Supplier / Thema | Phase B (Playbook-Vertiefung) | Phase C |
|------------------|-------------------------------|---------|
| Salesforce | `salesforce-tables-for-analytics` | `salesforce-to-mart` live |
| HubSpot | `hubspot-tables-for-analytics` | `hubspot-to-mart` live |
| Dynamics 365 | `dynamics-365-tables-for-analytics` | `dynamics-365-to-mart` live |
| SAP S/4 | `sap-s4-tables-for-analytics` | `sap-s4-to-mart` live |
| Workday | `workday-tables-for-analytics` | `workday-to-mart` live |
| ServiceNow | `servicenow-tables-for-analytics` | Stretch |
| Multi-Source Authority | `multi-source-entity-authority` | nur verlinken |
| SaaS Skip-Muster | `saas-exports-tables-to-skip` | in Proof 2 zitiert |
| Quelle zuerst | `which-source-to-load-first` | Hub/Journey/Advisor bleibt Einstieg |
| Interview → Modell | `from-stakeholder-interview-to-table-model` | Proof 1 |
| SharePoint / Collaboration | — | Stretch |
| Finance (konkreter Einstieg) | — | Stretch |

Tools, die C-Guides **verlinken** sollen (bereits da):

- Source Scope Builder, Meta Export, PII/DSDR Readiness, PII Recommend  
- KPI Requirements Intake, KPI Definition, Report Inventory  
- Mart Design Brief, Decision Brief  
- Formel-Tools nur als nächster Schritt nach Grain/Owner (`when-to-use-bi-formula-generators`, `semantic-layer-vs-report-measure`)

---

## C1 — Supplier → Model Mini-Guides

Priorisierte Lieferungen:

- [x] Salesforce → Mart (`salesforce-to-mart`)
- [x] HubSpot → Mart
- [x] SAP S/4 Ausschnitt → Mart
- [x] Workday → Mart
- [x] Dynamics 365 → Mart
- [ ] SharePoint / Collaboration — Stretch
- [ ] ServiceNow — Stretch

SEO:

- [x] Eigener Intro-Text, nicht nur Tabellen-Dump
- [x] FAQ nur wenn echte Fragen
- [x] `lastmod` / Update-Datum pflegen (`publishedAt: 2026-07-29`)
- [x] Keine Dublette zum Phase-B-Playbook (Cross-Link statt Copy-Paste); **keine neuen Decision Pages**

---

## C2 — Artefakt-Standards

Einheitliche Namen (in UI, Exports, Playbooks gleich):

- [x] `governance-discovery.md` (Session-Überblick)
- [x] `kpi-cards.csv` / JSON
- [x] `source-scope.csv` / JSON
- [x] `dq-backlog.csv` / JSON
- [x] Mart Design Brief → `mart-design-brief.md`
- [x] Decision Brief → `decision-brief.md`

Umsetzung:

- [x] Kurze Doku-Story „Welche Artefakte entstehen?“ (`which-artifacts-you-get`)
- [x] Discovery/Tools erwähnen denselben Artefakt-Namen
- [x] Dual-Store: Payload bleibt normalisiert (File + MySQL), keine View-Logik-Duplikate
- [x] Phase-B-Stories in „Artefakt“-Abschnitt auf dieselben Dateinamen prüfen

---

## C3 — Signature-IP (zitierfähig)

- [x] Decision Ladder — About + Hub Help
- [x] Collect Infos 8 — Discovery Canvas Eyebrow + About
- [x] Evidence Loop — About + Hub Help
- [x] 8 Pillars sichtbar halten
- [x] Platform Starting Point / Source Load First (1 Satz) auf About

Jeder Block:

- [x] DE + EN
- [x] Auf mindestens einer Hub-Seite zitiert
- [x] Für Phase D (LinkedIn) als Copy-Vorlage nutzbar

---

## C4 — Proof Stories

- [x] Proof 1: `proof-stakeholder-to-kpi-cards`
- [x] Proof 2: `proof-saas-source-to-pilot-mart`
- [x] Proof 3: `proof-bi-chaos-to-trusted-metrics`

---

## C5 — Cert- & Lernpfad-Feinschliff

- [x] Official Cert-Links spotrufen (Fabric, Databricks, Snowflake, dbt, GCP, AWS, Tableau)
- [x] Pro Link: „Wofür nützlich?“ in Resources
- [x] Aktualisierungsdatum sichtbar (`lastVerified` in vendor-resources meta)
- [x] Rollen-Mapping: Consultant / Platform / Analytics / Privacy (Learning Paths Lead)
- [x] Advisor-Basis-Rolle (`preferredRole`) bei Rollen-/Path-Empfehlungen nicht doppelt erklären — nur nutzen

---

## Nicht in Phase C

- Distribution/LinkedIn → Phase D
- Discussion → Phase E
- Neue Sidebar-Hubs
- Neue Decision Pages / pairwise Stack-Duells / erneutes Schreiben der Phase-B-Serien

## Notizen

2026-07-29: Abgrenzung zu Phase B dokumentiert; Supplier-Tabelle und Proof-Pfade auf reale Slugs/Tools umgestellt.  
2026-07-29 (Klarstellung): Keine neuen Decision Pages — Advisor = Entscheidungseinstieg; B-Playbooks = Vertiefung.  
2026-07-29: Phase C umgesetzt (Mini-Guides, Artefakt-Namen, Signature-IP, Proofs, Cert-Feinschliff).
