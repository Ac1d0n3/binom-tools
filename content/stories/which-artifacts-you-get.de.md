---
title: "Welche Artefakte entstehen?"
description: "Die gemeinsamen Dateinamen, die Hub, Tools und Playbooks für Discovery, KPI-Karten, Source Scope, DQ-Backlog, Mart Design Brief und Decision Brief verwenden — damit Ergebnisse wiedererkennbar bleiben."
author: Thomas Lindackers
tags:
  - Artefakte
  - Governance Discovery
  - KPI Cards
  - Source Scope
  - Data Governance
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/which-artifacts-you-get-hero.png

---

Jedes Tool im Hub erzeugt am Ende ein Dokument: eine KPI-Karte, einen Source Scope, einen DQ-Backlog-Eintrag, ein Mart-Design-Brief oder eine Entscheidungsvorlage. Diese Dokumente tragen bewusst über alle Tools, Playbooks und Supplier-Guides hinweg dieselben Dateinamen. Wer einmal gelernt hat, wonach er sucht, findet dasselbe Artefakt wieder — egal ob der Weg über den Governance Advisor, ein Supplier-Playbook oder ein einzelnes Tool begann.

Dieser Guide ist kein weiteres Entscheidungs-Playbook. Er beantwortet nur eine Frage: Welche Datei bekomme ich, und wo verwende ich sie weiter?

## Warum feste Dateinamen zählen

Ohne eine gemeinsame Namenskonvention entsteht schnell Beliebigkeit: eine Person nennt ihre KPI-Liste `kpis_v2_final.csv`, eine andere `metrics-export.xlsx`. Beide Dateien können denselben Inhalt haben, sind aber weder auffindbar noch automatisiert weiterverarbeitbar, und niemand kann auf den ersten Blick sagen, ob sie zusammengehören.

Die Artefakt-Standards lösen das, indem sie den Dateinamen selbst zum Vertrag machen. `kpi-cards.csv` bedeutet in jedem Tool, jedem Playbook und jeder Dokumentation dasselbe: eine Liste freigegebener oder in Arbeit befindlicher KPI-Definitionen mit Formel, Grain, Zeitlogik, Dimensionen und Owner. Das gilt unabhängig davon, ob die Datei aus der KPI Definition Card, einem Supplier-to-Mart-Guide oder einem manuell gepflegten Register stammt.

## Die Standard-Artefakte

| Artefakt | Dateiname | Erzeugt von | Zweck |
|---|---|---|---|
| Discovery-Überblick | `governance-discovery.md` | [Governance Discovery Canvas](/governance/discovery-canvas) | Zusammenfassung eines Discovery-Gesprächs: Ausgangslage, Stakeholder, offene Fragen und nächste Schritte in einem Dokument. |
| KPI-Karten | `kpi-cards.csv` (+ JSON) | [KPI Definition](/tools/kpi-definition) | Eine Zeile pro Kennzahl mit Formel, Grain, Zeitlogik, Filtern, Dimensionen und Owner. |
| Source Scope | `source-scope.csv` / `.md` / `.json` | [Source Scope Builder](/tools/source-scope-builder) | Freigegebene, zurückgestellte und ausgeschlossene Quellobjekte mit Begründung und Review Trigger. |
| DQ-Backlog | `dq-backlog.csv` / `.json` | [dbt DQ Rules Generator](/tools/dbt-dq-rules-generator) | Data-Quality-Regelkandidaten und offene Qualitätsprobleme, die vor dem Mart-Build geklärt werden müssen. |
| Mart Design Brief | `mart-design-brief.md` | [Mart Design Brief Generator](/tools/mart-design-brief-generator) | Business Event, Grain, Fact-/Dimension-Kandidaten, Historienverhalten und Scope-Out für einen konkreten Mart. |
| Decision Brief | `decision-brief.md` | [Decision Brief Generator](/tools/decision-brief-generator) | Kontext, empfohlene Option, Pilot-Scope, Risiken und offene Fragen — verdichtet für Sponsor oder Architekturboard. |

Jedes dieser Artefakte kann für sich stehen. In der Praxis bauen sie meist aufeinander auf: Ein `governance-discovery.md` aus einem ersten Gespräch führt zu `kpi-cards.csv` und `source-scope.csv`, diese wiederum zu einem `mart-design-brief.md`, und der `dq-backlog.csv` begleitet den Build. Ein `decision-brief.md` fasst diese Artefakte für eine Freigabeentscheidung zusammen.

## Dual-Store: Der Payload bleibt normalisiert

Diese Artefakte lassen sich sowohl als Datei herunterladen als auch — wo ein Konto vorhanden ist — im Governance-Workspace speichern. Beide Wege verwenden denselben normalisierten Payload; es gibt keine zweite, abweichende Ansicht-Logik nur für den Dateiexport. Der Dateiname ist damit kein zufälliges Exportdetail, sondern die sichtbare Seite desselben Datenmodells, das auch in der Datenbank liegt. Das hält Export, gespeicherten Workspace-Stand und Dokumentation konsistent, ohne dass Inhalte doppelt gepflegt werden müssen.

## Wo der Einstieg liegt

Diese Seite erklärt Dateinamen, nicht Entscheidungen. Wer noch nicht weiß, ob eine KPI-Karte, ein Source Scope oder zuerst ein Discovery-Gespräch der richtige nächste Schritt ist, sollte beim [Governance Advisor](/governance/berater) einsteigen. Der Advisor führt durch die Ausgangslage und verweist von dort auf das passende Tool — diese Seite hilft danach, das Ergebnis wiederzuerkennen und weiterzuverwenden.

## Tools

- [Governance Discovery Canvas](/governance/discovery-canvas) — Discovery-Gespräche als `governance-discovery.md` festhalten.
- [KPI Definition](/tools/kpi-definition) — Kennzahlen als `kpi-cards.csv` erfassen und versionieren.
- [Source Scope Builder](/tools/source-scope-builder) — Quellobjekte, Felder und Beziehungen als `source-scope.csv` / `.md` dokumentieren.
- [dbt DQ Rules Generator](/tools/dbt-dq-rules-generator) — Regelkandidaten und offene Probleme als `dq-backlog.csv` sammeln.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — Grain, Fakten, Dimensionen und Scope-Out als `mart-design-brief.md` freigeben.
- [Decision Brief Generator](/tools/decision-brief-generator) — Discovery-Ergebnisse als `decision-brief.md` für eine Freigabeentscheidung verdichten.

## Verwandte Playbooks

- [Vom Stakeholder-Interview zum Tabellenmodell](/playbooks/from-stakeholder-interview-to-table-model) — wie aus Interview-Evidenz ein Mart Design Brief entsteht.
- [Welche Quelle zuerst laden?](/playbooks/which-source-to-load-first) — wie ein Source Scope in die Portfolio-Entscheidung einfließt.
- [Salesforce → Mart: Grain, Fakten und KPI-Karten](/playbooks/salesforce-to-mart) — ein Beispiel, in dem alle Artefakte dieser Seite in einem konkreten Guide zusammenkommen.

## Nächster Schritt

Öffne das Tool, das zu deinem aktuellen Schritt passt, und lade das Ergebnis unter dem hier dokumentierten Dateinamen herunter oder speichere es im Workspace. Verlinke es anschließend in Ticket, Sprint oder Freigabeprozess unter genau diesem Namen — so bleibt für jeden im Team nachvollziehbar, welches Artefakt gemeint ist, ohne dass Dateinamen jedes Mal neu erfunden werden.
