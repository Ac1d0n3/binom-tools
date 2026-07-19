---
title: Help Hub — Logins & Rechte
description: Optionale Accounts ohne Datenbank — Session-Login, User-Flags, Story-ACL und Plan-Rechte im Sprint Planner.
author: Thomas Lindackers
category: Help Hub
tags:
  - help-hub
  - accounts
  - acl
  - login
order: 1
publishedAt: 2026-05-01
series: governance-help-hub
seriesPart: 2
seriesTitle: Governance Help Hub
---

## Überblick

Der Help Hub läuft standardmäßig **ohne Login**. Für interne Deployments kannst du optionale Accounts aktivieren: Session-Login, dateibasierte Benutzer und Teams, Story-Zugriffskontrolle und geteilte Sprint-Pläne — weiterhin **ohne relationale Datenbank**.

Diese Story ist **Teil 2** der Serie *Governance Help Hub*. Teil 1 beschreibt die [Plattform-Architektur](/playbooks/help-hub-platform), Teil 3 den [Sprint Planner](/playbooks/help-hub-sprint-planner).

> Accounts sind ein Feature-Flag, kein Pflichtbestandteil. Lokal und in öffentlichen Demos bleibt der Hub offen.

## Aktivieren

In `.env`:

```env
BINOM_TOOLS_ACCOUNTS_ENABLED=true
SESSION_DRIVER=file
# Optional: self-service avatar on /account (default true)
# BINOM_TOOLS_ACCOUNTS_PROFILE_AVATAR_ENABLED=false
```

Laufzeitdaten liegen unter `storage/app/bn-tools/` (gitignored):

| Datei / Ordner | Zweck |
| --- | --- |
| `users.json` | Benutzer inkl. `passwordHash` |
| `teams.json` | Teams und Mitgliederzuordnung |
| `story-acl.json` | Sichtbarkeit einzelner Playbooks |
| `plans/` | Server-seitige Sprint-Plan-Instanzen |
| `user-templates/` | Von Nutzern gespeicherte Planvorlagen |

Beim Start kopiert `BnToolsSeedStore` fehlende Dateien aus Seeds unter `app/SprintPlanner/bn-tools-seed/` (analog zu Playbook-Stats-Seeds). Beim FTP-Deploy werden lokale Seeds gespiegelt.

## Login

- Route: `/login` (lokalisiert z. B. `/de/login`)
- Session-Key: `bn_tools_account_user_id`
- Passwörter nur als Hash — Klartext in JSON ist verboten

Passwort setzen (Benutzer muss bereits in `users.json` existieren):

```bash
php artisan bn-tools:user-password you@example.com
```

## Benutzer-Flags

| Feld | Bedeutung |
| --- | --- |
| `active` | Inaktiver Account kann sich nicht anmelden |
| `canManageUsers` | Benutzer verwalten, Story-ACL, User-Templates anderer |
| `canManageTeams` | Teams verwalten |
| `teamIds` | Zugehörigkeit zu Teams (für ACL und Plan-Viewer) |

Verwaltung in der UI unter Account → Users / Teams (nur mit Manage-Rechten): kompakte Listen unter `/account/users` und `/account/teams`, mit eigenen Create-/Edit-Seiten. Team-`memberIds` und User-`teamIds` bleiben synchron. Optionale `shortName` / `colorToken` / `avatarIcon` steuern Chips im Sprint Planner. Nutzer können Avatar-Farbe und Icon auch unter **Konto** selbst setzen (`BINOM_TOOLS_ACCOUNTS_PROFILE_AVATAR_ENABLED`, Default an).

## Story-ACL

Playbooks sind standardmäßig **öffentlich**. In `story-acl.json` kannst du einzelne Slugs einschränken — oder in der UI unter Account → Story-Zugriff (Liste + Einzelbearbeitung):

| `visibility` | Verhalten |
| --- | --- |
| `public` | Für alle sichtbar (auch Gäste) |
| `restricted` | Nur gelistete `userIds` / `teamIds` |

Story-ACL betrifft **Playbooks und Read-State**, nicht die Repo-Markdown-Vorlagen unter `content/sprint-plans/`.

## Sprint Planner mit Accounts

| Rolle | Rechte |
| --- | --- |
| Gast (Accounts an, nicht eingeloggt) | Demo: Pläne nur in `sessionStorage`, kein Server-Sync |
| Owner (`ownerUserId`) | Plan bearbeiten, teilen, löschen |
| Viewer (`viewerUserIds` / `viewerTeamIds`) | Lesen und Fortschritt je nach API — kein Löschen |
| `canManageUsers` | Zusätzlich User-Templates und Admin-Flächen |

User-Templates speichern und teilen setzt Login voraus. Ohne Accounts bleibt der Planner rein lokal (siehe Teil 3).

## Was Accounts nicht sind

| Konzept | Bedeutung |
| --- | --- |
| Lokale Personen / Teams im Sprint Planner | Nur Zuordnung und Filter im Browser — **kein** Login |
| Soft-Lock (Plan-Passwort) | Hash im lokalen Storage, keine echte Zugriffskontrolle |
| Tool-Login-Flags | Einzelne Tools können per `TOOL_*_LOGIN_REQUIRED` Login verlangen (Default offen) |

## Setup-Checkliste

- [ ] `BINOM_TOOLS_ACCOUNTS_ENABLED=true` und `SESSION_DRIVER=file`
- [ ] Seeds / `users.json`, `teams.json`, `story-acl.json` vorhanden
- [ ] Mindestens ein Admin mit `canManageUsers: true`
- [ ] Passwort per `php artisan bn-tools:user-password …` gesetzt
- [ ] Story-ACL für interne Playbooks geprüft
- [ ] Sprint Planner: Login testen vs. Gast-Demo

## Weiterlesen

- [Teil 1: Governance Help Hub](/playbooks/help-hub-platform) — Architektur und Content
- [Teil 3: Sprint Planner](/playbooks/help-hub-sprint-planner) — Vorlagen, Storage, Fence-Syntax
