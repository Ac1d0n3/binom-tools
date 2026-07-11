---
title: Snowflake Governance Playbook
description: Row Access, Masking und Audit-Patterns für Snowflake-Workloads etablieren.
hero: images/playbooks/snowflake-governance.svg
category: Datenplattform
tags:
  - snowflake
  - rbac
  - masking
order: 1
---

## Überblick

Dieses Playbook beschreibt eine pragmatische Snowflake-Governance-Baseline für Analytics-Teams.

Governance muss im Alltag der Data Engineers und Analysts sichtbar sein — nicht nur in Richtlinien-PDFs, die niemand liest. Die folgenden Kapitel bauen aufeinander auf: erst das Rollenmodell, dann technische Umsetzung, abschließend die Audit-Routine.

> Governance sollte im Tagesgeschäft sichtbar sein — nicht in einem PDF versteckt.

Teams, die diese Baseline einführen, reduzieren typischerweise Break-glass-Fälle und beschleunigen Access-Reviews, weil Rollen und Policies konsistent benannt sind.

![Snowflake Governance Überblick](/images/playbooks/snowflake-governance.svg)

## Zugriffsmodell

Rollen nach Domäne und Umgebung definieren.

Ein klares Namensschema verhindert, dass Raw- und Curated-Zugriffe vermischt werden. Jede Domäne erhält eigene Rollen pro Umgebung (Dev, Staging, Prod), damit Promotion-Pfade nachvollziehbar bleiben.

| Ebene | Rollenmuster | Hinweise |
| --- | --- | --- |
| Raw | `RAW_<DOMAIN>_READ` | Nur Lesezugriff auf Landing-Schemas |
| Curated | `CUR_<DOMAIN>_READ` | Nur freigegebene Marts |
| Admin | `GOV_<DOMAIN>_ADMIN` | Break-glass mit Audit |

### Row-Access-Policies

Row-Access-Policies auf sensiblen Marts anwenden, bevor Self-Service-Zugriff freigegeben wird.

Policies sollten auf Business-Keys und Mandanten-IDs basieren — nicht auf Benutzernamen. Dokumentiere jede Policy im Data Catalog und verknüpfe sie mit dem verantwortlichen Data Owner.

Teste Policies in Staging mit repräsentativen Service-Accounts, bevor Analysten produktive Marts nutzen.

## Implementierungsbeispiel

Die folgenden Abschnitte zeigen, wie Playbooks und Code-Beispiele in dieser Plattform eingebunden werden. Das Muster lässt sich auf SQL-Runbooks, Terraform-Snippets oder Policy-YAML übertragen.

### Repository-Pattern

Playbooks werden dateibasiert über `PlaybookRepository` geladen — ohne CMS oder Datenbank.

Dateien liegen unter `content/` mit Locale-Suffix (`.de.md`, `.en.md`). Metadaten stehen im YAML-Frontmatter; der Body wird als Markdown gerendert. So bleiben Playbooks versionierbar und reviewbar wie normaler Code.

Neue Playbooks hinzufügen: Datei anlegen, Frontmatter ausfüllen, Route ist automatisch verfügbar — kein Deploy-Schritt für Content-Änderungen nötig (nach Cache-Clear bzw. neuem Build der Assets).

### Code-Highlighting

Fenced-Code-Blöcke unterstützen Titel, Sprache und Zeilen-Highlights für Review-Sessions.

Syntax-Highlighting nutzt die Binom-Prism-Themes (Light/Dark). Zeilennummern erleichtern Referenzen in Pull Requests und Audit-Gesprächen.

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

## Audit-Checkliste

Regelmäßige Audits sichern, dass Rollen und Policies nicht durch Ad-hoc-Grants aushebelt werden. Die Checkliste eignet sich für wöchentliche Ops-Runden und quartalsweise Compliance-Reviews.

### Access History

- Access History wöchentlich prüfen
- Ungewöhnliche Login-Zeiten und IP-Cluster markieren
- Abweichungen vom Rollenmodell im Ticket-System dokumentieren

### PII-Tagging

- PII-Spalten in `INFORMATION_SCHEMA`-Exports taggen
- Masking-Policy-Referenzen im Catalog pflegen
- Neue Spalten ohne Tag blockieren bis Review abgeschlossen ist

### Break-Glass

- Break-glass-Freigaben im Ticket-System dokumentieren
- Maximale Laufzeit und automatisches Revoke definieren
- Post-Incident-Review innerhalb von 48 Stunden durchführen
