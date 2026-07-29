# Phase B — Infos, Hilfe & Beratung

Stand: 2026-07-29  
Status: Code-Wave + Decision-Stories verdrahtet (Einstiege Hub/Guides/Discovery/Formel-Tools)  
Zurück: [Phase A](phase-a-authority-auffindbarkeit.de.md) · Index: [index.de.md](index.de.md) · Weiter: [Phase C](phase-c-artefakt-tiefe.de.md)  
Story-Briefs: [phase-b-story-briefs.de.md](phase-b-story-briefs.de.md)

## Ziel

So viel praktische Orientierung wie möglich anbieten: Decision Pages, geschärfter Advisor, BI auf Augenhöhe mit Governance. Noch kein Forum.

## Done when

- [x] Mindestens 8 Decision/Long-tail Stories (DE+EN) live und intern verlinkt
- [x] Advisor liefert Kontext → Begründung → Tools/Certs/Gaps (nicht nur Linkliste)
- [x] BI-Einstieg klar (Journey + Links zu KPI/Tools/Playbooks) *(Hub Journey + Copy; keine neue Landing-Sektion)*
- [x] Discovery/Collect-Infos Landing erklärt die Schritte crawlbar; Sessions bleiben noindex

---

## B1 — Decision / Long-tail Pages

Vorlage: freistehende Stories unter `content/stories/` (DE+EN), Author Thomas Lindackers, Tags, interne Links. Nicht jede Story muss in einen Learning Path.

Mindestliste (Checkbox = veröffentlicht + von Hub/Playbooks verlinkt):

- [x] Welche Infos brauche ich, bevor ich ein Data Warehouse designe? → `before-building-the-first-table`
- [x] KPI-Definition: Grain, Owner, Quelle (Template-Story) → `define-kpi`
- [x] Von Stakeholder-Interview zu Tabellenmodell → `from-stakeholder-interview-to-table-model` (Discovery `business-questions` + `mart`)
- [x] Welche Salesforce-Tabellen für Analytics laden / skippen? → `salesforce-tables-for-analytics` (Journey Supplier + Guides + Discovery `sources`)
- [x] SaaS-Exporte: welche Tabellen man nicht laden sollte (generisches Muster) → `saas-exports-tables-to-skip`
- [x] Data-Governance-Zertifikate für Consultants (CDMP, CIPP/E, Platform — Zweck erklären) → `eight-pillars` + `/compliance/roadmap`
- [x] Microsoft Fabric / Databricks / Snowflake / BigQuery — Governance-Einstieg → Serie `governance-platform-starting-points` (Chooser verdrahtet; siehe [governance-stack-decisions-story-briefs.de.md](governance-stack-decisions-story-briefs.de.md))
- [x] dbt `schema.yml` / `meta` Governance-Felder (Praxis) → `metadata-driven-governance-with-dbt-meta`
- [x] PII in CRM, HR, Collaboration erkennen (Einstieg) → `pii-privacy-governance` (+ DSDR)
- [x] Business Logic außerhalb der BI-App halten (Vertiefung / Verdrahtung bestehender Story) → `keeping-business-logic-outside-bi-apps`

SEO-Cluster abdecken (mind. ein starker Einstieg pro Cluster):

- [x] Data Governance starten
- [x] KPI Definition / Requirements
- [x] Data Quality Regeln
- [x] PII / DSDR
- [x] Fabric / Databricks / dbt / Snowflake Governance *(Plattform-Serie Parts 1–7 live; Chooser Hub-Einstieg)*
- [x] Power BI / Tableau / Qlik Governance *(BI-Serie Parts 1–8 live; Einstiege Hub + Formel-Tools)*

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
- [x] Interne Links von Power BI / Tableau / Qlik Tools zu KPI-, DQ-, PII-Playbooks *(+ Semantic Layer / Formel-Generator Decision Pages)*
- [x] Learning Path oder kuratierte Playbook-Liste „Trusted Metrics / BI Governance“ prüfen und ggf. nachschärfen
- [x] Sprint-Template-Anbindung für BI-Governance-Pfad prüfen (`from-report-inventory-to-trusted-metric` in Trusted-Metrics-Sprint)
- [x] Mindestens 2 BI-Decision Pages → `semantic-layer-vs-report-measure`, `fabric-powerbi-metric-certification` (+ Serie Parts 3–8 live)

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
- [x] Business-Fragen / Report Inventory *(+ Interview→Tabellenmodell)*
- [x] KPI-Anforderungen
- [x] Quellen / Supplier Scope *(which-source / Salesforce / SaaS-Skip)*
- [x] PII / DSDR
- [x] Data Quality
- [x] Mart Design Brief *(+ Interview→Tabellenmodell)*
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

Serien-Plan-MDs (reale Slugs):

- Plattform: [governance-stack-decisions-story-briefs.de.md](governance-stack-decisions-story-briefs.de.md) → Serie `governance-platform-starting-points`
- Source Load: [source-load-decisions-story-briefs.de.md](source-load-decisions-story-briefs.de.md)
- BI: [bi-governance-decisions-story-briefs.de.md](bi-governance-decisions-story-briefs.de.md)

Hub-Einstiege (nicht jede Vendor-Part-Story):

| Bereich | Slugs |
|---------|-------|
| Stack | `choose-governance-platform-starting-point`, optional `microsoft-fabric-governance-start` |
| Source | `which-source-to-load-first`, `salesforce-tables-for-analytics`, `saas-exports-tables-to-skip` |
| Interview/Mart | `from-stakeholder-interview-to-table-model` |
| BI | `from-report-inventory-to-trusted-metric`, `semantic-layer-vs-report-measure`, `when-to-use-bi-formula-generators` |

Advisor: Session-/Demo-Reports `noindex,nofollow` gesetzt.  
Profil: Basis-Rolle (`preferredRole`) vorbelegt den Advisor — Chips bleiben frei wählbar.

**Keine weiteren Decision Pages.** Advisor = Entscheidungseinstieg; die Phase-B-Playbooks sind Vertiefung.  
Weiter: [Phase C](phase-c-artefakt-tiefe.de.md) = Supplier→Mart / Proof auf dieser Basis — keine Dubletten.
