---
title: Snowflake Governance Playbook
description: Establish row access, masking, and audit patterns for Snowflake workloads.
hero: images/playbooks/snowflake-governance.svg
category: Data Platform
tags:
  - snowflake
  - rbac
  - masking
order: 1
---

## Overview

This playbook walks through a pragmatic Snowflake governance baseline for analytics teams.

Governance must be visible in the daily work of data engineers and analysts — not buried in policy PDFs that nobody reads. The following chapters build on each other: role model first, then technical implementation, finally the audit routine.

> Governance should be visible in daily workflows — not buried in a PDF policy deck.

Teams adopting this baseline typically reduce break-glass incidents and speed up access reviews because roles and policies follow consistent naming.

![Snowflake governance overview](/images/playbooks/snowflake-governance.svg)

## Access model

Define roles by domain and environment.

A clear naming scheme prevents mixing raw and curated access. Each domain gets dedicated roles per environment (dev, staging, prod) so promotion paths stay traceable.

| Layer | Role pattern | Notes |
| --- | --- | --- |
| Raw | `RAW_<DOMAIN>_READ` | Read-only on landing schemas |
| Curated | `CUR_<DOMAIN>_READ` | Approved marts only |
| Admin | `GOV_<DOMAIN>_ADMIN` | Break-glass with audit |

### Row access policies

Apply row access policies on sensitive marts before opening self-service access.

Policies should be based on business keys and tenant IDs — not usernames. Document each policy in the data catalog and link it to the responsible data owner.

Test policies in staging with representative service accounts before analysts use production marts.

## Implementation snippet

The following sections show how playbooks and code examples are embedded on this platform. The pattern applies to SQL runbooks, Terraform snippets, or policy YAML as well.

### Repository pattern

Playbooks are loaded from markdown files via `PlaybookRepository` — no CMS or database required.

Files live under `content/` with locale suffixes (`.de.md`, `.en.md`). Metadata sits in YAML frontmatter; the body is rendered as markdown. Playbooks stay versionable and reviewable like regular code.

To add playbooks: create a file, fill frontmatter, the route is available automatically — no deploy step for content-only changes (after asset rebuild if needed).

### Code highlighting

Fenced code blocks support titles, language tags, and line highlights for review sessions.

Syntax highlighting uses Binom Prism themes (light/dark). Line numbers make references in pull requests and audit conversations easier.

```php title="PlaybookRepository.php" {3-6}
final class PlaybookRepository
{
    public function find(string $slug): ?Playbook
    {
        $path = $this->contentPath($slug);
        if (! is_file($path)) {
            return null;
        }
        return $this->buildPlaybook($path, $slug);
    }
}
```

## Audit checklist

Regular audits ensure roles and policies are not undermined by ad-hoc grants. Use this checklist for weekly ops rounds and quarterly compliance reviews.

### Access history

- Enable access history review weekly
- Flag unusual login times and IP clusters
- Document deviations from the role model in the ticket system

### PII tagging

- Tag PII columns in `INFORMATION_SCHEMA` exports
- Maintain masking policy references in the catalog
- Block new columns without tags until review is complete

### Break-glass

- Document break-glass approvals in ticket system
- Define maximum duration and automatic revoke
- Run post-incident review within 48 hours
