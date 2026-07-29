---
title: Help Hub — Logins & Rechte
description: Optionale Accounts — File- oder MySQL-Storage, Session-Login, Registrierung mit Freigabe, Story-ACL und Plan-Rechte.
author: Thomas Lindackers
category: Help Hub
tags:
  - help-hub
  - accounts
  - acl
  - login
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - powerbi
order: 1
publishedAt: 2026-05-01
series: governance-help-hub
seriesPart: 2
hero: images/playbooks/help-hub-accounts-hero.png
seriesTitle: Governance Help Hub
---

## Überblick

Der Help Hub läuft standardmäßig **ohne Login**. Für interne Deployments kannst du optionale Accounts aktivieren: Session-Login, Benutzer und Teams, Story-Zugriffskontrolle und geteilte Sprint-Pläne.

**Standard-Storage ist dateibasiert** (JSON unter `storage/app/bn-tools/`) — keine Datenbank nötig. Runtime-Daten lassen sich später auf MySQL umstellen, ohne Markdown-Stories oder Repo-Sprint-Vorlagen zu ändern.

Diese Story ist **Teil 2** der Serie *Governance Help Hub*. Teil 1 beschreibt die [Plattform-Architektur](/playbooks/help-hub-platform), Teil 3 den [Sprint Planner](/playbooks/help-hub-sprint-planner).

> Accounts sind ein Feature-Flag, kein Pflichtbestandteil. Lokal und in öffentlichen Demos bleibt der Hub offen.

## Aktivieren

In `.env`:

```env
BINOM_TOOLS_ACCOUNTS_ENABLED=true
SESSION_DRIVER=file
BINOM_TOOLS_STORAGE_DRIVER=file
# Optional: self-service avatar on /account (default true)
# BINOM_TOOLS_ACCOUNTS_PROFILE_AVATAR_ENABLED=false
# Optional: Self-Registration (Admin-Freigabe nötig)
# BINOM_TOOLS_REGISTRATION_ENABLED=true
```

### File-Storage (Default)

Laufzeitdaten liegen unter `storage/app/bn-tools/` (**nicht in Git**; lokal + im FTP-Bundle `deploy-ftp/`, das ebenfalls gitignored ist):

| Datei / Ordner | Zweck |
| --- | --- |
| `users.json` | Benutzer inkl. `passwordHash` |
| `teams.json` | Teams und Mitgliederzuordnung |
| `story-acl.json` | Sichtbarkeit einzelner Playbooks |
| `plans/` | Server-seitige Sprint-Plan-Instanzen |
| `user-templates/` | Von Nutzern gespeicherte Planvorlagen |
| `read-state/` | Lesestatus je Nutzer |

`npm run deploy:ftp` packt diese Daten nach:
- `deploy-ftp/app/SprintPlanner/bn-tools-seed/` (Hydrate beim App-Start, **nur fehlende** Dateien)
- `deploy-ftp/storage/app/bn-tools/` (direkter Server-Pfad zum Hochladen)

Server-`.env`: `BINOM_TOOLS_ACCOUNTS_ENABLED=true` und `SESSION_DRIVER=file`.

### MySQL-Storage (optionaler Switch)

Stories und Repo-Vorlagen bleiben Markup. Runtime-Daten nach MySQL:

1. `DB_CONNECTION=mysql` und `DB_*` setzen
2. `php artisan migrate`
3. Optional: `php artisan bn-tools:storage-import` (bestehende JSON-Daten)
4. `BINOM_TOOLS_STORAGE_DRIVER=mysql`

Tabellen nutzen das Präfix `bn_*` (`bn_users`, `bn_plans`, `bn_playbook_stats`, …).

## Login

- Route: `/login` (lokalisiert z. B. `/de/login`)
- Session-Key: `bn_tools_account_user_id`
- Passwörter nur als Hash — Klartext in JSON ist verboten

Passwort setzen (Benutzer muss bereits existieren):

```bash
php artisan bn-tools:user-password you@example.com
```

## Self-Registration (optional)

Mit `BINOM_TOOLS_REGISTRATION_ENABLED=true` (und Accounts an) können Besucher `/register` öffnen. Neue Konten sind **inaktiv** mit `pendingApproval`, bis ein Admin sie unter **Konto → Benutzer** freigibt. Ablehnen löscht die Registrierung. Funktioniert mit File- und MySQL-Storage.

## Leute einladen (Benutzer hinzufügen)

Unter **Konto → Benutzer → Benutzer hinzufügen** kannst du jemanden schnell anlegen:

1. E-Mail + Anzeigename (optional: Teams, Avatar).
2. Standard: **temporäres Passwort erzeugen**, **Einladung per E-Mail**, **Passwortwechsel beim ersten Login**.
3. Die Person bekommt Login-URL, E-Mail und temporäres Passwort.
4. Nach dem Login muss unter `/account` zuerst ein neues Passwort gesetzt werden.

SMTP in `.env` konfigurieren (`MAIL_MAILER`, `MAIL_HOST`, …). Mit `MAIL_MAILER=log` landet die Einladung im Laravel-Log (lokal praktisch). Schlägt der Versand fehl, zeigt die UI das temporäre Passwort einmal zum Kopieren.

## Benutzer-Flags

| Feld | Bedeutung |
| --- | --- |
| `active` | Inaktiver Account kann sich nicht anmelden |
| `canManageUsers` | Benutzer verwalten, Story-ACL, User-Templates anderer |
| `canManageTeams` | Teams verwalten |
| `teamIds` | Zugehörigkeit zu Teams (für ACL und Plan-Viewer) |
| `mustChangePassword` | Muss Passwort ändern, bevor Tools genutzt werden |

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

- [ ] `BINOM_TOOLS_ACCOUNTS_ENABLED=true` und `SESSION_DRIVER=file` (bei File-Storage)
- [ ] `BINOM_TOOLS_STORAGE_DRIVER=file` (Default) — oder MySQL-Switch nach migrate/import
- [ ] Optional: `BINOM_TOOLS_REGISTRATION_ENABLED=true`
- [ ] Seeds / `users.json`, `teams.json`, `story-acl.json` vorhanden (File) bzw. importiert (MySQL)
- [ ] Mindestens ein Admin mit `canManageUsers: true`
- [ ] Passwort per `php artisan bn-tools:user-password …` gesetzt
- [ ] Story-ACL für interne Playbooks geprüft
- [ ] Sprint Planner: Login testen vs. Gast-Demo

## Weiterlesen

- [Teil 1: Governance Help Hub](/playbooks/help-hub-platform) — Architektur und Content
- [Teil 3: Sprint Planner](/playbooks/help-hub-sprint-planner) — Vorlagen, Storage, Fence-Syntax
