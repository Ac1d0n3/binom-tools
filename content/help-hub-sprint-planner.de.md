---
title: Help Hub — Sprint Planner
description: Vorlagen vs. Instanzen, Storage-Modi und die sprint-Fence-Syntax für Autoren im Governance Help Hub.
author: Thomas Lindackers
category: Help Hub
tags:
  - help-hub
  - sprint-planner
  - markdown
  - templates
order: 2
publishedAt: 2026-05-01
series: governance-help-hub
seriesPart: 3
seriesTitle: Governance Help Hub
---

## Überblick

Der **Sprint Planner** macht Governance-Arbeit ausführbar: Markdown-Vorlagen werden zu Planinstanzen mit Aufgaben, Deliverables, Fortschritt und verknüpften Stories — ohne CMS und ohne Pflicht-Datenbank.

Diese Story ist **Teil 3** der Serie *Governance Help Hub*. Teil 1: [Plattform](/playbooks/help-hub-platform). Teil 2: [Logins & Rechte](/playbooks/help-hub-accounts).

Zwei Zielgruppen:

- **Nutzen** — Pläne starten, Fortschritt speichern, exportieren
- **Autoren** — Repo-Vorlagen unter `content/sprint-plans/` schreiben

## Navigation

| Bereich | Route (Beispiel) |
| --- | --- |
| Meine Pläne | `/sprint-planner` |
| Vorlagen | `/sprint-planner/templates` |
| Plan erstellen | `/sprint-planner/create` |
| Personen & Teams | `/sprint-planner/people` |
| Planinstanz | `/sprint-planner/{instanceId}` |

## Vorlage vs. Instanz

| | Vorlage | Instanz |
| --- | --- | --- |
| Quelle | Repo-Markdown oder User-Template | Gestarteter Arbeitsstand |
| Inhalt | Sprints, Tasks, Deliverables, Links, Stories | Fortschritt, Overrides, Notizen, Snapshot |
| Änderung | Git / Creator / User-Template-API | Browser (und optional Server) |

Eine Vorlage kann mehrfach gestartet werden. Die Instanz hält einen `templateSnapshot`, damit spätere Vorlagen-Updates laufende Pläne nicht still überschreiben.

## Storage-Modi

| Modus | Wann | Speicher |
| --- | --- | --- |
| **Lokal** | Accounts aus | `localStorage` Key `bn-tools:sprint-planner:workspace:v1` |
| **Gast-Demo** | Accounts an, nicht eingeloggt | `sessionStorage` Key `bn-tools:sprint-planner:demo:v1` (ephemeral) |
| **Eingeloggt** | Accounts an + Session | Server-Pläne unter `storage/app/bn-tools/plans/` + lokaler Cache |

Preferences (UI-Einstellungen): immer `bn-tools:sprint-planner:preferences:v1` in `localStorage`.

Workspace-Inhalt typischerweise: `people`, `teams`, `instances` (Fortschritt, Custom-Items, Soft-Lock-Hash, Owner/Viewer-Felder bei Accounts).

### Export, Import, Anhänge

- JSON-Export/Import für Backup und Gerätwechsel ohne Cloud-Sync
- Datei-Anhänge: lokal IndexedDB (`bn-tools-sprint-planner-blobs-v1`); mit Accounts Upload-API
- Soft-Lock (Plan-Passwort): nur Browser-Schutz — siehe Teil 2

Lokale „Personen“ und „Arbeiten als“ sind **keine** Authentifizierung.

## Template-Syntax für Autoren

Vorlagen liegen als Locale-Paar:

```text
content/sprint-plans/{slug}.de.md
content/sprint-plans/{slug}.en.md
```

Beispiel-Slugs im Repo: `data-reporting-first-quarter` und Stack-Varianten (`…-fq-fivetran-snowflake-qlik`, `…-powerbi`, `…-fabric-qlik-qvd`).

### Frontmatter

```yaml
---
type: sprint-plan
title: Data & Reporting – Erstes Quartal
slug: data-reporting-first-quarter
description: Kurzbeschreibung der Vorlage.
duration: 13
unit: week
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Data Platform
  - Reporting
---
```

Pflicht: `type` (`sprint-plan`), `title`, `slug`, `description`, `duration`, `unit`, `version`, `locale`.

### Sprint-Fence

Jeder Sprint ist ein Markdown-Fence namens `sprint`:

````markdown
```sprint
id: week-01
number: 1
title: Orientierung und Mandat
goal: Auftrag, Erwartungen und relevante Stakeholder verstehen.

stories:
  - slug: data-ownership-stewardship
    required: true
  - slug: eight-pillars
    required: false

links:
  - label: DQ Rules Generator
    href: /tools/dbt-dq-rules-generator

tasks:
  - id: align-management-expectations
    label: Erwartungen mit der Führung abstimmen
    assigneeType: person
    assigneeId: null
    helpText: |
      Kurze, handlungsorientierte Anleitung.
    helpLinks:
      - label: Data Ownership
        href: /playbooks/data-ownership-stewardship

deliverables:
  - id: stakeholder-list
    label: Stakeholder-Liste erstellt

fields:
  - id: management-expectations
    label: Erwartungen der Führung
    type: textarea

notes: true
```
````

**Sprint Pflicht:** `id`, `number`, `title`, `goal`.

**Häufig optional:** `description`, `tasks`, `deliverables`, `fields`, `notes`, `stories` / `linkedStories`, `links`, `flowVariant` / `flowLayout` / `flowSteps`, `estimated_effort`.

**Tasks:** `id`, `label`; optional `assigneeType` (`person`|`team`), `assigneeId`, `helpText`, `helpLinks`, `stories`, `tableColumns`.

**Fields:** Typen u. a. `text`, `textarea`, `number`, `date`, `select`, `multiselect`, `url`, `checkbox`, `person`, `team`.

### Konventionen

- DE- und EN-Datei teilen dieselben strukturellen IDs (`week-01`, Task-IDs, …)
- Status-Keys der Instanz: `{templateSlug}:{sprintId}:{task|deliverable}:{itemId}`
- `links` / `helpLinks`: externe oder App-URLs mit Label
- UI-Creator erzeugt schnelle Custom-Pläne; **versionierte Repo-Vorlagen** bleiben Markdown in Git

Parser und Validierung: `SprintFenceParser`, `SprintPlanValidator`, `SprintPlanRepository`.

## Kurz-Checkliste Autor

- [ ] `.de.md` und `.en.md` mit gleichem `slug` und gleichen IDs
- [ ] `type: sprint-plan` und Pflicht-Frontmatter
- [ ] Pro Sprint Fence mit `id` / `number` / `title` / `goal`
- [ ] Story-Slugs existieren unter `content/`
- [ ] Nach Änderung: App neu laden und Vorlage unter `/sprint-planner/templates` prüfen

## Weiterlesen

- [Teil 1: Governance Help Hub](/playbooks/help-hub-platform)
- [Teil 2: Logins & Rechte](/playbooks/help-hub-accounts)
