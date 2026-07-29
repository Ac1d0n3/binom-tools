# Phase C — Artefakt-Tiefe & Proof

Stand: 2026-07-29  
Status: offen · **baut auf Phase B auf** (bestehende Playbooks / Advisor-Links ≠ Mart-Guides)  
Zurück: [Phase B](phase-b-infos-beratung.de.md) · Index: [index.de.md](index.de.md) · Weiter: [Phase D](phase-d-reichweite.de.md)

## Ziel

Einzigartigen Mehrwert liefern, den Vendor-Blogs nicht haben: von Quelle/Workshop zu konkreten Artefakten und nachvollziehbaren Beispielen.

**Einstieg für Entscheidungen bleibt der Advisor** — Phase C schreibt keine neuen Decision-Stories. Bestehende Phase-B-Playbooks nur verlinken.

## Done when

- [ ] Mindestens 5 Supplier-to-Mart Mini-Guides live *(Tiefe — nicht nur Load/Skip aus B)*
- [ ] Gemeinsame Export-/Artefakt-Namen dokumentiert und in Tools erwähnt
- [ ] 2–3 anonymisierte Proof-/Workshop-Stories
- [ ] Jede behandelte Supplier-Seite: Intro + nächster Tool-Schritt + interne Links *(inkl. Link zur Phase-B Load/Skip-Story + Advisor/Journey)*

---

## Ausgangslage nach Phase B (nicht nochmal schreiben)

Load/Skip- und Authority-Playbooks sind live in Serie [`source-load-decisions`](source-load-decisions-story-briefs.de.md). Der Advisor/Journey bleibt der Entscheidungseinstieg. Phase C **vertieft** zu Grain → Mart → Artefakt und verdichtet Supplier-Library-Seiten.

| Supplier / Thema | Phase B (Playbook-Vertiefung) | Phase C (noch offen) |
|------------------|-------------------------------|----------------------|
| Salesforce | `salesforce-tables-for-analytics` | Mini-Guide → Mart + Library-Intro |
| HubSpot | `hubspot-tables-for-analytics` | Mini-Guide → Mart |
| Dynamics 365 | `dynamics-365-tables-for-analytics` | Mini-Guide → Mart |
| SAP S/4 | `sap-s4-tables-for-analytics` | Mini-Guide → Mart (klarer Ausschnitt) |
| Workday | `workday-tables-for-analytics` | Mini-Guide → Mart |
| ServiceNow | `servicenow-tables-for-analytics` | Mini-Guide → Mart |
| Multi-Source Authority | `multi-source-entity-authority` | nur verlinken; kein zweites Authority-Essay |
| SaaS Skip-Muster | `saas-exports-tables-to-skip` | in Proof 2 zitieren |
| Quelle zuerst | `which-source-to-load-first` | Hub/Journey/Advisor bleibt Einstieg |
| Interview → Modell | `from-stakeholder-interview-to-table-model` | Proof 1 / Mart-Pfad nutzen |
| SharePoint / Collaboration | — | ggf. neuer Mini-Guide (kein Decision-Page-Wave) |
| Finance (konkreter Einstieg) | — | ggf. neuer Mini-Guide |

Tools, die C-Guides **verlinken** sollen (bereits da):

- Source Scope Builder, Meta Export, PII/DSDR Readiness, PII Recommend  
- KPI Requirements Intake, KPI Definition, Report Inventory  
- Mart Design Brief, Decision Brief  
- Formel-Tools nur als nächster Schritt nach Grain/Owner (`when-to-use-bi-formula-generators`, `semantic-layer-vs-report-measure`)

---

## C1 — Supplier → Model Mini-Guides

Pro Guide (Story oder Supplier-Detail-Abschnitt) — **über** Phase-B Load/Skip hinaus:

- Entitäten / Kernobjekte → **Grain** und Fact/Dim-Kandidaten
- PII / Skip *(kurz; Detail in Phase-B-Playbook verlinken)*
- Standard-KPIs → Link `define-kpi` / KPI Tools
- Nächste Binom-Tools + Artefakt-Namen (C2)
- Link zurück zur Supplier Library **und** zum Phase-B-Playbook / Advisor-Journey

Priorisierte Lieferungen:

- [ ] Salesforce → Mart (baut auf `salesforce-tables-for-analytics`)
- [ ] HubSpot → Mart
- [ ] SAP S/4 Ausschnitt → Mart
- [ ] Workday → Mart **oder** ServiceNow → Mart (eins zuerst)
- [ ] Dynamics 365 → Mart **oder** Finance-/Collaboration-Einstieg (Lücke schließen)
- [ ] SharePoint / Collaboration — nur wenn Katalog-Inhalt trägt

SEO:

- [ ] Eigener Intro-Text, nicht nur Tabellen-Dump
- [ ] FAQ nur wenn echte Fragen
- [ ] `lastmod` / Update-Datum pflegen
- [ ] Keine Dublette zum Phase-B-Playbook (Cross-Link statt Copy-Paste); **keine neuen Decision Pages**

---

## C2 — Artefakt-Standards

Einheitliche Namen (in UI, Exports, Playbooks gleich):

- [ ] `governance-discovery.md` (Session-Überblick)
- [ ] `kpi-cards.csv` / JSON
- [ ] `source-scope.csv` / JSON
- [ ] `dq-backlog.csv` / JSON
- [ ] Mart Design Brief
- [ ] Decision Brief

Umsetzung:

- [ ] Kurze Doku-Story oder Hub-Abschnitt „Welche Artefakte entstehen?“
- [ ] Discovery/Tools erwähnen denselben Artefakt-Namen *(Discovery Steps + Report Inventory / KPI / Source Scope bereits verdrahtet — Namen angleichen)*
- [ ] Dual-Store: Payload bleibt normalisiert (File + MySQL), keine View-Logik-Duplikate
- [ ] Phase-B-Stories in „Artefakt“-Abschnitt auf dieselben Dateinamen prüfen

---

## C3 — Signature-IP (zitierfähig)

Kurze, benannte Modelle — auf About/Home/Playbooks wiederverwendbar:

- [ ] Decision Ladder (Orientierung → Fragen → Tools → Nachweise) benennen und 1 Absatz fixieren — **Advisor als Einstieg nennen**
- [ ] Collect Infos 8 (Schritte) als benanntes Gerüst *(Hub Canvas bereits crawlbar — Text fixieren)*
- [ ] Evidence Loop (Entscheidung → Control → Nachweis) 1 Absatz — anschließen an `missing-pieces-trusted-metrics` / BI-Serie
- [ ] 8 Pillars als Praxisgerüst sichtbar halten (nicht durch DMBOK ersetzen)
- [ ] Optional: „Platform Starting Point“ (1 Satz) aus Chooser-Serie; „Source Load First“ (1 Satz) aus Source-Load-Serie

Jeder Block:

- [ ] DE + EN
- [ ] Auf mindestens einer Hub-Seite zitiert
- [ ] Für Phase D (LinkedIn) als Copy-Vorlage nutzbar

---

## C4 — Proof Stories

Anonymisiert, konkret, ohne Kundennamen. Bestehende Playbooks / Advisor-Pfade **verlinken**, nicht ersetzen — und keine neuen Decision-Stories:

- [ ] Proof 1: Stakeholder-Fragen → KPI-Karten — Pfad `from-stakeholder-interview-to-table-model` + KPI Intake/Definition + Mart Brief
- [ ] Proof 2: SaaS-Quelle → Scope/Skip/PII → Pilot-Mart — `which-source-to-load-first` / Salesforce oder SaaS-Skip + Source Scope + PII Tools
- [ ] Proof 3: BI-Chaos → Trusted Metrics — `from-report-inventory-to-trusted-metric` + `semantic-layer-vs-report-measure` + Formel-Tool + `missing-pieces-trusted-metrics`

Je Story:

- [ ] Ausgangslage → Schritte → Artefakte → Lernerfolg
- [ ] Links zu Tools/Paths (`trusted-metrics`) und Advisor wo sinnvoll
- [ ] Disclaimer: Beispiel, keine Rechts-/Vendor-Beratung

---

## C5 — Cert- & Lernpfad-Feinschliff

- [ ] Official Cert-Links spotrufen (Fabric, Databricks, Snowflake, dbt, GCP, AWS, Tableau)
- [ ] Pro Link: „Wofür nützlich?“ in Resources oder Path — Querverweis Plattform-Serie Parts 2–6
- [ ] Aktualisierungsdatum sichtbar
- [ ] Rollen-Mapping: Consultant / Platform / Analytics / Privacy (WWW-Plan)
- [ ] Advisor-Basis-Rolle (`preferredRole` im Profil) bei Rollen-/Path-Empfehlungen nicht doppelt erklären — nur nutzen

---

## Nicht in Phase C

- Distribution/LinkedIn → Phase D
- Discussion → Phase E
- Neue Sidebar-Hubs
- Neue Decision Pages / pairwise Stack-Duells / erneutes Schreiben der Phase-B-Serien

## Notizen

2026-07-29: Abgrenzung zu Phase B dokumentiert; Supplier-Tabelle und Proof-Pfade auf reale Slugs/Tools umgestellt.  
2026-07-29 (Klarstellung): Keine neuen Decision Pages — Advisor = Entscheidungseinstieg; B-Playbooks = Vertiefung.
