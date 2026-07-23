---
title: Help Hub — Logins & Permissions
description: Optional accounts without a database — session login, user flags, story ACL, and plan rights in the Sprint Planner.
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

## Overview

The help hub runs **without login by default**. For internal deployments you can enable optional accounts: session login, file-based users and teams, story access control, and shared sprint plans — still **without a relational database**.

This story is **part 2** of the *Governance Help Hub* series. Part 1 covers the [platform architecture](/playbooks/help-hub-platform); part 3 covers the [Sprint Planner](/playbooks/help-hub-sprint-planner).

> Accounts are a feature flag, not a requirement. Locally and in public demos the hub stays open.

## Enable accounts

In `.env`:

```env
BINOM_TOOLS_ACCOUNTS_ENABLED=true
SESSION_DRIVER=file
# Optional: self-service avatar on /account (default true)
# BINOM_TOOLS_ACCOUNTS_PROFILE_AVATAR_ENABLED=false
```

Runtime data lives under `storage/app/bn-tools/` (**not in Git**; local + in the FTP bundle `deploy-ftp/`, which is also gitignored):

| File / folder | Purpose |
| --- | --- |
| `users.json` | Users including `passwordHash` |
| `teams.json` | Teams and membership |
| `story-acl.json` | Visibility of individual playbooks |
| `plans/` | Server-side sprint plan instances |
| `user-templates/` | User-saved plan templates |
| `read-state/` | Per-user read state |

`npm run deploy:ftp` packs this data into:
- `deploy-ftp/app/SprintPlanner/bn-tools-seed/` (hydrate on boot, **missing files only**)
- `deploy-ftp/storage/app/bn-tools/` (direct server path for upload)

Server `.env`: `BINOM_TOOLS_ACCOUNTS_ENABLED=true` and `SESSION_DRIVER=file`.

## Login

- Route: `/login` (localized, e.g. `/en/login`)
- Session key: `bn_tools_account_user_id`
- Passwords as hashes only — plaintext in JSON is rejected

Set a password (user must already exist in `users.json`):

```bash
php artisan bn-tools:user-password you@example.com
```

## Invite people (add user)

Under **Account → Users → Add user** you can quickly onboard someone:

1. Enter email + display name (optional: teams, avatar).
2. Defaults: **generate temporary password**, **send invitation email**, **must change password on first login**.
3. The person receives login URL, email, and temporary password.
4. After login they are forced to `/account` until they set a new password.

Configure real SMTP in `.env` (`MAIL_MAILER`, `MAIL_HOST`, …). With `MAIL_MAILER=log` the invite is written to the Laravel log (useful locally). If sending fails, the UI shows the temporary password once so you can copy it.

## User flags

| Field | Meaning |
| --- | --- |
| `active` | Inactive accounts cannot sign in |
| `canManageUsers` | Manage users, story ACL, other users’ templates |
| `canManageTeams` | Manage teams |
| `teamIds` | Team membership (for ACL and plan viewers) |
| `mustChangePassword` | Must change password before using tools |

Manage users and teams in the Account UI (requires manage rights): compact lists under `/account/users` and `/account/teams`, with dedicated create/edit pages. Team `memberIds` and user `teamIds` stay in sync. Optional `shortName` / `colorToken` / `avatarIcon` feed Sprint Planner chips. Users can also set avatar color and icon under **Account** (`BINOM_TOOLS_ACCOUNTS_PROFILE_AVATAR_ENABLED`, default on).

## Story ACL

Playbooks are **public** by default. In `story-acl.json` you can restrict individual slugs — or use Account → Story access in the UI (list + per-story edit):

| `visibility` | Behaviour |
| --- | --- |
| `public` | Visible to everyone (including guests) |
| `restricted` | Only listed `userIds` / `teamIds` |

Story ACL applies to **playbooks and read-state**, not to repo Markdown templates under `content/sprint-plans/`.

## Sprint Planner with accounts

| Role | Rights |
| --- | --- |
| Guest (accounts on, not signed in) | Demo: plans only in `sessionStorage`, no server sync |
| Owner (`ownerUserId`) | Edit, share, delete the plan |
| Viewer (`viewerUserIds` / `viewerTeamIds`) | Read and progress per API — no delete |
| `canManageUsers` | Also user templates and admin screens |

Saving and sharing user templates requires login. Without accounts the planner stays fully local (see part 3).

## What accounts are not

| Concept | Meaning |
| --- | --- |
| Local people / teams in the Sprint Planner | Assignment and filters in the browser only — **not** login |
| Soft lock (plan password) | Hash in local storage, not real access control |
| Tool login flags | Individual tools can require login via `TOOL_*_LOGIN_REQUIRED` (default open) |

## Setup checklist

- [ ] `BINOM_TOOLS_ACCOUNTS_ENABLED=true` and `SESSION_DRIVER=file`
- [ ] Seeds / `users.json`, `teams.json`, `story-acl.json` present
- [ ] At least one admin with `canManageUsers: true`
- [ ] Password set via `php artisan bn-tools:user-password …`
- [ ] Story ACL reviewed for internal playbooks
- [ ] Sprint Planner: test login vs. guest demo

## Next

- [Part 1: Governance Help Hub](/playbooks/help-hub-platform) — architecture and content
- [Part 3: Sprint Planner](/playbooks/help-hub-sprint-planner) — templates, storage, fence syntax
