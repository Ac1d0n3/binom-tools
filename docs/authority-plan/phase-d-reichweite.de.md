# Phase D — Reichweite & Follow

Stand: 2026-07-29  
Status: offen · Cadence-Ziele = **Advisor + bestehende Phase-B-Playbooks/Tools** (+ später Phase-C Proofs)  
Zurück: [Phase C](phase-c-artefakt-tiefe.de.md) · Index: [index.de.md](index.de.md) · Weiter: [Phase E](phase-e-discussion.de.md)

## Ziel

Auffindbarkeit außerhalb der Site: Menschen stolpern über den Namen, folgen wegen wiederkehrendem Nutzen — noch ohne Discussion-Forum.

**Keine neuen Decision Pages.** Posts führen zum Advisor und/oder zu bestehenden Playbooks/Tools.

## Done when

- [ ] Fester Publishing-Rhythmus definiert und 4 Wochen durchgehalten
- [ ] Follow-CTA (LinkedIn / binom.net) auf Kernflächen sichtbar
- [ ] Zitierblöcke / FAQs für Top-Seiten vorhanden *(Hub Advisor + Phase-B-Einstiegs-Playbooks)*
- [ ] SEO-Backlog aus Search Console/Bing wird monatlich gepflegt
- [ ] Optional: RSS oder Updates-Seite live

---

## D1 — LinkedIn-Cadence

Rhythmus-Vorschlag (anpassen, aber festlegen):

| Slot | Inhalt | Link-Ziel (konkret) |
|------|--------|---------------------|
| 1×/Woche | 1 Kernaussage / Signature-IP | `/governance` Advisor oder `eight-pillars` / Collect-Infos-8 |
| 1×/Woche | 1 Tool-Artefakt (Screenshot/Export-Beispiel) | Report Inventory, KPI Definition, Source Scope, Formel-Generator, Mart Brief |
| 1×/2 Wochen | Vertiefungs-Playbook (bestehend) | Source-Load / Plattform / BI-Serie (Rotation unten) |
| monatlich | Radar / „was sich bewegt“ | `/governance/radar` |
| nach Phase C | Proof Story | C4 Proofs |

### Playbook-Rotation (bestehende Phase-B-Slugs — keine neuen)

| Woche-Thema | Einstiegs-Slug |
|-------------|----------------|
| Plattform wählen | `choose-governance-platform-starting-point` *(danach Advisor Stack-Journey)* |
| Fabric / Databricks / Snowflake / BigQuery / dbt | jeweiliger Part der Plattform-Serie |
| Quelle zuerst / Salesforce / SaaS-Skip | `which-source-to-load-first`, `salesforce-tables-for-analytics`, `saas-exports-tables-to-skip` |
| Semantic Layer vs Report | `semantic-layer-vs-report-measure` |
| Inventory → Trusted Metric | `from-report-inventory-to-trusted-metric` |
| Formel-Generatoren | `when-to-use-bi-formula-generators` (+ Tool-Landing) |
| Interview → Tabellenmodell | `from-stakeholder-interview-to-table-model` |

- [ ] Cadence schriftlich festlegen (dieses Doc oder Kalender)
- [ ] 8 Post-Vorlagen aus Advisor-Pfad + Phase-B-Stories + Signature-IP vorbereiten
- [ ] Jeder Post: eine Entscheidung + ein nächster Schritt (Advisor oder Tool) auf der Site
- [ ] Profil/binom.net verweisen auf governance.binom.net

---

## D2 — Follow ohne Forum

- [ ] CTA „Folgen / Mehr von Thomas“: LinkedIn + binom.net auf About, Hub, Playbook-Footer
- [ ] Optional: RSS oder `/updates`-Seite für neue Playbooks (Markdown-Liste reicht) — Seed: die drei Phase-B-Serien
- [ ] Radar als „lebendiges“ Signal sichtbar halten (kuratiert)
- [ ] Kein Fake-Community-Hub; Feedback weiter über GitHub Issues bis Phase E

---

## D3 — Zitierfähigkeit & AI Search

Pro Top-Seite (Hub + Phase-B-Einstiege + 10 weitere Top-Playbooks):

Priorität:

- [ ] `/governance` (Advisor-Einstieg)
- [ ] `choose-governance-platform-starting-point`
- [ ] `which-source-to-load-first`
- [ ] `salesforce-tables-for-analytics`
- [ ] `semantic-layer-vs-report-measure`
- [ ] `from-report-inventory-to-trusted-metric`
- [ ] `when-to-use-bi-formula-generators`
- [ ] `from-stakeholder-interview-to-table-model`

Pro Seite:

- [ ] 80–120 Wörter zitierfähige Zusammenfassung oben oder im Intro
- [ ] Klare Definitionen in HTML (nicht nur JS)
- [ ] Autor + Datum + Update sichtbar
- [ ] Offizielle Quellenlinks beschriftet (`official docs`, `learning`, `certification`)

Strukturierte Daten nachziehen wo sinnvoll:

- [ ] `FAQPage` nur bei echten FAQs
- [ ] `Article` / `TechArticle` Playbooks
- [ ] `BreadcrumbList` tiefe Seiten
- [ ] `Person` konsistent
- [ ] Series-Übersichten: sinnvolle `ItemList` nur wenn kuratiert

---

## D4 — SEO-Betrieb

- [ ] Monatlich: Search Console Queries → Content-Backlog (**Phase-C Mart-Guides / Proofs / Artefakt-Doku** — **keine neuen Decision Pages**, keine B-Dubletten)
- [ ] Monatlich: Crawl-/Index-Fehler abarbeiten *(Phase-B-Slugs + Advisor-Hub in Stichprobe)*
- [ ] Optional: IndexNow bei Publish neuer Stories/Tools
- [ ] Interne Suchintentionen mit bestehenden Clustern abgleichen (WWW-Plan)
- [ ] Queries zu „Salesforce tables analytics“, „semantic layer vs measure“, „Fabric governance start“ den Live-Slugs bzw. Advisor zuordnen

---

## D5 — Externe Signale

- [ ] GitHub README: klar „Governance Help Hub“ + Live-URL + Author
- [ ] binom.net: Link/Abschnitt auf governance.binom.net
- [ ] 1 Talk / Meetup / Gastbeitrag planen (auch intern notieren reicht zuerst)
- [ ] Partner-/Community-Backlinks nur wo thematisch passt (keine Directory-Spam)
- [ ] Demo-Pfad erwähnen: Hub Advisor (+ optional Profil-Basis-Rolle) → bestehendes Playbook → Tool-Artefakt

---

## Nicht in Phase D

- Discussion/Forum → Phase E
- Große neue Tool-Suites (nur wenn SEO/Cadence es braucht)
- Neue Decision Pages oder Phase-B-Bodies nur wegen Posts umschreiben

## Notizen

2026-07-29: Cadence- und Zitat-Prioritäten auf reale Phase-B-Slugs/Tools gesetzt; Proof-Slots an Phase C gekoppelt.  
2026-07-29 (Klarstellung): Keine neuen Decision Pages — Advisor = Einstieg; Posts verlinken Advisor + bestehende Vertiefung.
