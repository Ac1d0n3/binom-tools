---
title: Help Hub — Sprint Planner
description: Templates vs. instances, storage modes, and sprint fence syntax for authors in the Governance Help Hub.
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

## Overview

The **Sprint Planner** makes governance work executable: Markdown templates become plan instances with tasks, deliverables, progress, and linked stories — no CMS and no required database.

This story is **part 3** of the *Governance Help Hub* series. Part 1: [platform](/playbooks/help-hub-platform). Part 2: [logins & permissions](/playbooks/help-hub-accounts).

Two audiences:

- **Users** — start plans, save progress, export
- **Authors** — write repo templates under `content/sprint-plans/`

## Navigation

| Area | Route (example) |
| --- | --- |
| My plans | `/sprint-planner` |
| Templates | `/sprint-planner/templates` |
| Create plan | `/sprint-planner/create` |
| People & teams | `/sprint-planner/people` |
| Plan instance | `/sprint-planner/{instanceId}` |

## Template vs. instance

| | Template | Instance |
| --- | --- | --- |
| Source | Repo Markdown or user template | Started working copy |
| Content | Sprints, tasks, deliverables, links, stories | Progress, overrides, notes, snapshot |
| Changed via | Git / creator / user-template API | Browser (and optionally server) |

The same template can be started multiple times. Instances keep a `templateSnapshot` so later template edits do not silently overwrite running plans.

## Storage modes

| Mode | When | Storage |
| --- | --- | --- |
| **Local** | Accounts off | `localStorage` key `bn-tools:sprint-planner:workspace:v1` |
| **Guest demo** | Accounts on, not signed in | `sessionStorage` key `bn-tools:sprint-planner:demo:v1` (ephemeral) |
| **Signed in** | Accounts on + session | Server plans under `storage/app/bn-tools/plans/` + local cache |

Preferences (UI settings): always `bn-tools:sprint-planner:preferences:v1` in `localStorage`.

Typical workspace contents: `people`, `teams`, `instances` (progress, custom items, soft-lock hash, owner/viewer fields when accounts are on).

### Export, import, attachments

- JSON export/import for backup and device switch without cloud sync
- File attachments: local IndexedDB (`bn-tools-sprint-planner-blobs-v1`); with accounts, upload API
- Soft lock (plan password): browser-only protection — see part 2

Local “people” and “work as” are **not** authentication.

## Template syntax for authors

Templates are locale pairs:

```text
content/sprint-plans/{slug}.de.md
content/sprint-plans/{slug}.en.md
```

Example slugs in the repo: `data-reporting-first-quarter` and stack variants (`…-fq-fivetran-snowflake-qlik`, `…-powerbi`, `…-fabric-qlik-qvd`).

### Frontmatter

```yaml
---
type: sprint-plan
title: Data & Reporting – First Quarter
slug: data-reporting-first-quarter
description: Short template description.
duration: 13
unit: week
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Data Platform
  - Reporting
---
```

Required: `type` (`sprint-plan`), `title`, `slug`, `description`, `duration`, `unit`, `version`, `locale`.

### Sprint fence

Each sprint is a Markdown fence named `sprint`:

````markdown
```sprint
id: week-01
number: 1
title: Orientation and mandate
goal: Understand the brief, expectations, and relevant stakeholders.

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
    label: Align expectations with leadership
    assigneeType: person
    assigneeId: null
    helpText: |
      Short, action-oriented guidance.
    helpLinks:
      - label: Data Ownership
        href: /playbooks/data-ownership-stewardship

deliverables:
  - id: stakeholder-list
    label: Stakeholder list created

fields:
  - id: management-expectations
    label: Leadership expectations
    type: textarea

notes: true
```
````

**Sprint required:** `id`, `number`, `title`, `goal`.

**Common optional:** `description`, `tasks`, `deliverables`, `fields`, `notes`, `stories` / `linkedStories`, `links`, `flowVariant` / `flowLayout` / `flowSteps`, `estimated_effort`.

**Tasks:** `id`, `label`; optional `assigneeType` (`person`|`team`), `assigneeId`, `helpText`, `helpLinks`, `stories`, `tableColumns`.

**Fields:** types include `text`, `textarea`, `number`, `date`, `select`, `multiselect`, `url`, `checkbox`, `person`, `team`.

### Conventions

- DE and EN files share the same structural IDs (`week-01`, task IDs, …)
- Instance status keys: `{templateSlug}:{sprintId}:{task|deliverable}:{itemId}`
- `links` / `helpLinks`: external or app URLs with a label
- The UI creator is for quick custom plans; **versioned repo templates** stay as Markdown in Git

Parsing and validation: `SprintFenceParser`, `SprintPlanValidator`, `SprintPlanRepository`.

## Author checklist

- [ ] `.de.md` and `.en.md` with the same `slug` and the same IDs
- [ ] `type: sprint-plan` and required frontmatter
- [ ] Per sprint: fence with `id` / `number` / `title` / `goal`
- [ ] Story slugs exist under `content/`
- [ ] After changes: reload the app and check the template under `/sprint-planner/templates`

## Next

- [Part 1: Governance Help Hub](/playbooks/help-hub-platform)
- [Part 2: Logins & permissions](/playbooks/help-hub-accounts)
