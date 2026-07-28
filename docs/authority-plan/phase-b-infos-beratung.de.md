# Phase B — Infos, Hilfe & Beratung

Stand: 2026-07-28  
Status: Code-Wave weitgehend erledigt · neue Story-Bodies ausstehend (siehe Briefs)  
Zurück: [Phase A](phase-a-authority-auffindbarkeit.de.md) · Index: [index.de.md](index.de.md) · Weiter: [Phase C](phase-c-artefakt-tiefe.de.md)  
Story-Briefs: [phase-b-story-briefs.de.md](phase-b-story-briefs.de.md)

## Ziel

So viel praktische Orientierung wie möglich anbieten: Decision Pages, geschärfter Advisor, BI auf Augenhöhe mit Governance. Noch kein Forum.

## Done when

- [x] Mindestens 8 Decision/Long-tail Stories (DE+EN) live und intern verlinkt *(Reuse verdrahtet; neue Slugs = Briefs)*
- [x] Advisor liefert Kontext → Begründung → Tools/Certs/Gaps (nicht nur Linkliste)
- [x] BI-Einstieg klar (Journey + Links zu KPI/Tools/Playbooks) *(Hub Journey + Copy; keine neue Landing-Sektion)*
- [x] Discovery/Collect-Infos Landing erklärt die Schritte crawlbar; Sessions bleiben noindex

---

## B1 — Decision / Long-tail Pages

Vorlage: freistehende Stories unter `content/stories/` (DE+EN), Author Thomas Lindackers, Tags, interne Links. Nicht jede Story muss in einen Learning Path.

Mindestliste (Checkbox = veröffentlicht + von Hub/Playbooks verlinkt):

- [x] Welche Infos brauche ich, bevor ich ein Data Warehouse designe? → `before-building-the-first-table`
- [x] KPI-Definition: Grain, Owner, Quelle (Template-Story) → `define-kpi`
- [ ] Von Stakeholder-Interview zu Tabellenmodell → Brief `from-stakeholder-interview-to-table-model`
- [ ] Welche Salesforce-Tabellen für Analytics laden / skippen? → Brief `salesforce-tables-for-analytics`
- [ ] SaaS-Exporte: welche Tabellen man nicht laden sollte (generisches Muster) → Brief `saas-exports-tables-to-skip`
- [x] Data-Governance-Zertifikate für Consultants (CDMP, CIPP/E, Platform — Zweck erklären) → `eight-pillars` + `/compliance/roadmap`
- [ ] Microsoft Fabric / Databricks / Snowflake / BigQuery — Governance-Einstieg (Serie `governance-stack-decisions`, siehe [governance-stack-decisions-story-briefs.de.md](governance-stack-decisions-story-briefs.de.md))
- [x] dbt `schema.yml` / `meta` Governance-Felder (Praxis) → `metadata-driven-governance-with-dbt-meta`
- [x] PII in CRM, HR, Collaboration erkennen (Einstieg) → `pii-privacy-governance` (+ DSDR)
- [x] Business Logic außerhalb der BI-App halten (Vertiefung / Verdrahtung bestehender Story) → `keeping-business-logic-outside-bi-apps`

SEO-Cluster abdecken (mind. ein starker Einstieg pro Cluster):

- [x] Data Governance starten
- [x] KPI Definition / Requirements
- [x] Data Quality Regeln
- [x] PII / DSDR
- [x] Fabric / Databricks / dbt / Snowflake Governance *(dbt live via `metadata-driven-governance-with-dbt-meta`; Plattform-Chooser/Einstiege = Serie offen)*
- [x] Power BI / Tableau / Qlik Governance *(Tool-Leads + Logic-Story; BI-Decision Briefs offen)*

Hub-Regel pro Seite: Problem → Entscheidung → Checkliste → Artefakt → Tools → Resources → Playbooks → nächster Schritt.

---

## B2 — Advisor schärfen

Bestehendes Formular / Hub — kein neuer Sidebar-Hub.

- [x] Kontext-Frage: `startup | midmarket | enterprise | bank-finance | public-sector`
- [x] Optional Regulierungsdruck: `low | gdpr-heavy | regulated`
- [x] Ergebnisblock „Nachweise & Zertifikate“ (rollen-/kontextabhängig)
- [x] Ergebnisblock „Lücken & Brücken“ (z. B. Stack ohne Catalog → Metadata-Pfad)
- [x] Stack-Empfehlung mit Begründung + Link `/governance` Stacks + 2–3 Start-Tools
- [x] DQ bleibt integrierter Entscheidungspfad (nicht zweiter Berater)
- [x] Ergebnis-URLs / Session-Reports nicht indexieren

Referenz-Ideen: Advisor-Backlog aus früheren Sessions; Config unter `modules/governance/`.

---

## B3 — BI-Parität

Governance ist stark; BI soll denselben Dramaturgie-Bogen bekommen.

Journey (öffentlich erklären + verlinken):

```text
Report Inventory → KPI Definition → Grain/Owner → Layer/Mart → BI-Formel-Tool → Evidence
```

- [x] Auf Home oder Tools-Landing: BI-Einstieg *(Hub Journey + `home.biLead`; keine neue visuelle Sektion ohne Design-Freigabe)*
- [x] Interne Links von Power BI / Tableau / Qlik Tools zu KPI-, DQ-, PII-Playbooks
- [x] Learning Path oder kuratierte Playbook-Liste „Trusted Metrics / BI Governance“ prüfen und ggf. nachschärfen
- [x] Sprint-Template-Anbindung für BI-Governance-Pfad prüfen (`ia-roles-paths-sprint.md`)
- [ ] Mindestens 2 BI-Decision Pages (z. B. Semantic Layer vs Measure in Report; Certification Fabric/PBI) → Briefs

Tools (Landing-Texte SEO-fähig — Problem erklären ohne Interaktion):

- [x] Power BI DAX Generator
- [x] Tableau Calculation Generator
- [x] Qlik Set Analysis Generator (nur Copy/Links — kein Workbench-Redesign)
- [x] KPI Definition / Report Inventory / BI Python Toolkit

---

## B4 — Discovery / Collect Infos (öffentlich erklären)

Referenz: [governance-workflow-tool-plans.de.md](../governance-workflow-tool-plans.de.md)

- [x] Landing (Hub/Canvas): 8 Schritte als crawlbares HTML
- [x] Jeder Schritt verlinkt bestehende Tools + relevante Playbooks
- [x] Export-Hinweis (Markdown/CSV/JSON) auf der Landing erwähnt
- [x] Individuelle Sessions / Reports: `noindex`, nicht in Sitemap
- [x] Demo vs Login-Verhalten in UI-Text klar

Schritte (Inhalt vorhanden/verlinkt?):

- [x] Stakeholder / RACI
- [x] Business-Fragen / Report Inventory
- [x] KPI-Anforderungen
- [x] Quellen / Supplier Scope *(Salesforce/SaaS-Stories nach Ablage nachziehen)*
- [x] PII / DSDR
- [x] Data Quality
- [x] Mart Design Brief
- [x] Decision Brief / Impact-Effort

---

## B5 — Roles / Paths Content-Lücken (optional in B, blockiert C nicht)

Referenz: [story-gaps-roles.md](../story-gaps-roles.md)

- [x] Fehlende P1 Role-Stories prüfen (`data-architect-role`, `raci-for-data-governance` — falls schon live: nur Verdrahtung)
- [x] Paths ↔ Roles ↔ Sprint-Template Cross-Links spot-checken *(Trusted Metrics Sprint + RolesStoryWiringTest)*

---

## Nicht in Phase B

- Tiefe Supplier-to-Mart Guides → Phase C
- LinkedIn-Cadence / RSS → Phase D
- Discussion → Phase E

## Notizen

Story-Briefs & Serien: [phase-b-story-briefs.de.md](phase-b-story-briefs.de.md)

Neue Slugs (Bodies ausstehend):

- `from-stakeholder-interview-to-table-model` — Serie `building-modern-data-warehouse` Part 11
- `salesforce-tables-for-analytics` — Serie `source-load-decisions` Part 1
- `saas-exports-tables-to-skip` — Serie `source-load-decisions` Part 2
- Serie `governance-stack-decisions` (Chooser + Fabric/Databricks/Snowflake/BigQuery + dbt + Multi-Platform) — Plan: [governance-stack-decisions-story-briefs.de.md](governance-stack-decisions-story-briefs.de.md); ~~`fabric-vs-databricks-governance-start`~~ entfernt
- `semantic-layer-vs-report-measure` — Serie `bi-governance-decisions` Part 1
- `fabric-powerbi-metric-certification` — Serie `bi-governance-decisions` Part 2

Advisor: Session-/Demo-Reports `noindex,nofollow` gesetzt.
