---
title: Access & Security Governance
description: Ein praxisnahes Betriebsmodell für nachvollziehbaren, rollen- und policy-basierten Datenzugriff — mit klaren Verantwortlichkeiten, Least Privilege, kontinuierlichen Reviews und auditierbaren Kontrollen.
category: Data Governance
tags:
  - data-governance
  - access-governance
  - security-governance
  - iam
  - rbac
  - abac
  - least-privilege
  - segregation-of-duties
  - data-security
  - audit
order: -1
publishedAt: 2026-06-08
series: governance-pillars
seriesPart: 8
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/access-gov-hero.png
---

## Der richtige Zugriff. Für die richtigen Personen. Zur richtigen Zeit.

Datenzugriff ist notwendig, damit Menschen arbeiten, Analysen erstellen und Entscheidungen treffen können.

Gleichzeitig entsteht eines der größten Governance-Risiken, wenn Berechtigungen:

- historisch wachsen
- direkt an einzelne Nutzer vergeben werden
- nach Rollenwechseln bestehen bleiben
- über mehrere Plattformen unterschiedlich geregelt sind
- nicht regelmäßig überprüft werden
- sensible Daten ohne fachliche Freigabe sichtbar machen
- technische Administratorrechte mit fachlicher Datennutzung vermischen
- Service Accounts ohne klare Ownership besitzen
- Self-Service-Werkzeuge Schutzregeln umgehen
- Ausnahmen dauerhaft bestehen bleiben

**Access & Security Governance** verbindet Identitäten, fachliche Verantwortung, Datenklassifikation, technische Richtlinien, Monitoring und regelmäßige Rezertifizierung zu einem kontrollierten Lifecycle.

> *Zugriff ist dann gut geregelt, wenn er nachvollziehbar, zweckgebunden, minimal erforderlich, zeitlich angemessen und kontinuierlich überprüfbar ist.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/access-gov-de.png"
        alt="Access und Security Governance mit Identifikation, Klassifikation, Autorisierung, Durchsetzung, Monitoring, Review und Entzug von Berechtigungen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Access & Security Governance verbindet Identitäten, Rollen, Policies, technische Kontrollen und kontinuierliche Reviews zu einem nachvollziehbaren Zugriffsmodell.
    </figcaption>
</figure>

## Security kontrolliert Systeme — Governance kontrolliert Entscheidungen

Security und Governance überschneiden sich, sind aber nicht identisch.

Security beantwortet unter anderem:

- Wie wird eine Identität authentifiziert?
- Wie werden Daten verschlüsselt?
- Wie werden Angriffe erkannt?
- Wie werden Systeme technisch geschützt?

Access Governance ergänzt:

- Wer darf welche Daten sehen?
- Auf welcher fachlichen Grundlage?
- Für welchen Zweck?
- Wer genehmigt den Zugriff?
- Wie lange gilt die Berechtigung?
- Welche Schutzklasse gilt?
- Welche Konflikte entstehen durch kombinierte Rollen?
- Wie wird regelmäßig bestätigt, dass der Zugriff weiterhin notwendig ist?
- Wie wird die Berechtigung kontrolliert entzogen?

Ein technischer Login beweist nur, dass eine Identität bekannt ist. Er beweist nicht, dass jeder danach verfügbare Datenzugriff fachlich gerechtfertigt ist.

## Das Access & Security Operating Model

Ein belastbarer Lifecycle kann in sieben Schritten aufgebaut werden.

```text
1. Identitäten, Rollen, Systeme und Datenassets erfassen
        ↓
2. Daten, Risiken und Zugriffsstufen klassifizieren
        ↓
3. Zugriff fachlich genehmigen
        ↓
4. Berechtigung policy-basiert bereitstellen
        ↓
5. Nutzung, Anomalien und Ausnahmen überwachen
        ↓
6. Zugriffe regelmäßig rezertifizieren
        ↓
7. Veraltete oder riskante Berechtigungen entziehen
        ↺
```

Dieser Lifecycle gilt nicht nur für menschliche Nutzer, sondern auch für:

- Service Accounts
- technische Benutzer
- Pipelines
- APIs
- Anwendungen
- Bots
- Automatisierungen
- externe Partner
- temporäre Projektkonten

## 1. Identitäten, Rollen, Systeme und Datenassets erfassen

Governance benötigt Transparenz darüber, wer oder was Zugriff besitzt.

Ein Access-Inventar kann enthalten:

| Metadatum | Beispiel |
| --- | --- |
| **Identity** | `thomas.lindackers@example.org` |
| **Identity Type** | Employee |
| **Business Role** | Finance Analyst |
| **Technical Role** | `SNOWFLAKE_FINANCE_ANALYST` |
| **Data Domain** | Finance |
| **System** | Snowflake |
| **Environment** | Production |
| **Access Level** | Read |
| **Sensitivity Scope** | Confidential |
| **Access Owner** | Finance Data Owner |
| **Approval Date** | 2026-07-01 |
| **Expiry Date** | 2026-12-31 |
| **Last Review** | 2026-07-10 |
| **Justification** | Monthly financial reporting |

Ohne diese Verbindung zwischen fachlicher Rolle und technischer Berechtigung bleiben Reviews schwer verständlich.

## 2. Daten und Zugriffsstufen klassifizieren

Nicht jedes Asset benötigt dasselbe Zugriffsniveau.

Ein mögliches Modell:

| Schutzklasse | Typische Daten | Grundprinzip |
| --- | --- | --- |
| **Public** | veröffentlichte Informationen | breit zugänglich |
| **Internal** | allgemeine interne Daten | Mitarbeitenden-Zugriff |
| **Confidential** | interne Geschäfts- und Kundendaten | rollen- und zweckgebunden |
| **Restricted** | sensible PII, Finanz- oder Personaldaten | streng begrenzter Zugriff |
| **Highly Restricted** | besonders kritische oder regulatorische Daten | explizite Freigabe, starke Kontrolle |

Die Schutzklasse sollte technische Regeln beeinflussen.

Beispiel:

```text
PII Category: direct_identifier
        +
Sensitivity: restricted
        ↓
Required Controls:
- role-based access
- dynamic masking
- audit logging
- quarterly review
- explicit Data Owner approval
```

Damit werden PII- und Sensitivitätsmetadaten zu operativen Zugriffskontrollen.

## 3. Zugriff fachlich genehmigen

Ein guter Zugriffsantrag beantwortet:

- Wer beantragt?
- Welche Rolle oder Anwendung benötigt Zugriff?
- Auf welches Datenprodukt oder Asset?
- Mit welchem Zugriffslevel?
- Für welchen Zweck?
- Wie lange?
- Welche Sensitivitätsklasse ist betroffen?
- Welche Schulung oder Verpflichtung ist erforderlich?
- Wer genehmigt?
- Besteht ein Konflikt mit anderen Berechtigungen?

Beispiel:

```yaml
requestor: thomas.lindackers@example.org
business_role: Finance Analyst
asset: finance.certified_revenue
access_level: read
purpose: Monthly management reporting
duration: 180 days
approval:
  - manager
  - data_owner
controls:
  - no_export
  - masked_personal_data
```

Zugriff sollte möglichst rollenbasiert beantragt werden.

Direkte Einzelberechtigungen erschweren:

- Transparenz
- Wiederverwendung
- Rezertifizierung
- Rollenwechsel
- Entzug
- Audit

## RBAC, ABAC und Policy-based Access

### Role-Based Access Control — RBAC

RBAC ordnet Berechtigungen Rollen zu.

```text
User
  ↓
Business Role
  ↓
Technical Role
  ↓
Permissions
  ↓
Data Asset
```

Vorteile:

- gut verständlich
- wiederverwendbar
- einfacher zu genehmigen
- leichter zu rezertifizieren

Risiken:

- zu viele Rollen
- Rollen werden historisch immer größer
- Sonderfälle führen zu Role Explosion
- fachliche und technische Rollen werden vermischt

### Attribute-Based Access Control — ABAC

ABAC berücksichtigt Eigenschaften.

Beispiele:

- Land
- Organisationseinheit
- Vertragsart
- Sensitivitätsstufe
- Projektzugehörigkeit
- Beschäftigungsstatus
- Datenregion
- Zweck
- Gerät oder Netzwerk

Beispiel:

```text
Allow access when:

user.department = "Finance"
AND data.domain = "Finance"
AND data.sensitivity <= user.clearance
AND request.purpose = "Management Reporting"
```

ABAC ermöglicht feinere Regeln, erhöht aber Komplexität und Erklärungsbedarf.

### Policy-based Access

Policies verbinden fachliche Anforderungen mit technischen Kontrollen.

Beispiel:

```text
Policy:
Restricted PII may only be used
for approved operational purposes.

Enforcement:
- approved role required
- dynamic masking by default
- unmask only with explicit entitlement
- export disabled
- all access logged
```

In der Praxis werden RBAC, ABAC und Policies häufig kombiniert.

## 4. Zugriff technisch durchsetzen

Technische Zugriffskontrollen können auf mehreren Ebenen wirken.

| Ebene | Typische Kontrolle |
| --- | --- |
| **Identity Provider** | Single Sign-On, MFA, Gruppen |
| **Cloud / Platform** | Rollen, Policies, Service Principals |
| **Warehouse / Lakehouse** | Grants, Roles, Row Access, Column Policies |
| **dbt / Transformation** | kontrollierte Deployments, getrennte Umgebungen |
| **Semantic Layer** | Objekt-, Zeilen- oder Metrikzugriff |
| **Qlik** | Section Access, App- und Stream-Berechtigungen |
| **Power BI** | Workspace-Rollen, Row-Level Security, Object-Level Security |
| **Tableau** | Site-, Projekt-, Workbook- und Row-Level-Regeln |
| **Excel** | kontrollierte Datenverbindungen, Berechtigungen auf Quelldaten |
| **API** | Scopes, Claims, Tokens, Rate Limits |
| **Files / Exports** | Verschlüsselung, Ablaufdatum, Download-Regeln |

Die beste technische Kontrolle hängt vom Verwendungszweck ab.

Ein häufiger Fehler ist, nur den Plattformzugriff zu steuern.

Beispiel:

```text
User has access to BI workspace
        ↓
Can open report
        ↓
Can export underlying data
        ↓
Sensitive detail becomes available outside governed layer
```

Governance muss deshalb auch Export, Download, API-Nutzung und lokale Weiterverarbeitung berücksichtigen.

## Zugriff auf Zeilen, Spalten und Werte

### Row-Level Security

Beispiel:

```text
Regional Manager
        ↓
sees only Region = "West"
```

### Column-Level Security

Beispiel:

```text
Standard Analyst
        ↓
cannot see salary or email columns
```

### Dynamic Data Masking

Beispiel:

```text
email = t***@example.org
```

### Purpose-based Access

Beispiel:

```text
Customer Support
        ↓
may access contact data
only for active support cases
```

Je sensibler die Daten, desto wichtiger ist die Kombination aus Rolle, Zweck, Kontext und Monitoring.

## Least Privilege

**Least Privilege** bedeutet:

Eine Identität erhält nur die Berechtigungen, die für den aktuellen Zweck erforderlich sind.

Das gilt für:

- Datenzugriff
- administrative Rechte
- technische Deployments
- Service Accounts
- API Scopes
- Exportrechte
- Masking-Ausnahmen
- Produktionszugriff

Least Privilege ist kein einmaliger Zustand.

Berechtigungen verändern sich durch:

- Rollenwechsel
- Projektende
- Teamwechsel
- neue Aufgaben
- temporäre Vertretung
- Systemmigration
- organisatorische Änderungen

Deshalb benötigt Least Privilege regelmäßige Reviews und automatisierten Entzug.

## Segregation of Duties — Funktionstrennung

Bestimmte Berechtigungen sollten nicht kombiniert werden.

Beispiele:

| Konflikt | Risiko |
| --- | --- |
| Daten ändern + Änderung selbst freigeben | fehlende unabhängige Kontrolle |
| Nutzer anlegen + Rechte genehmigen | Umgehung des Freigabeprozesses |
| KPI berechnen + KPI ohne Review zertifizieren | unkontrollierte Kennzahl |
| Datenzugriff + Audit-Logs löschen | Nachweis kann manipuliert werden |
| Pipeline entwickeln + Produktion ohne Review deployen | unkontrollierte Änderung |
| Masking Policy ändern + unmaskten Zugriff vergeben | Schutz kann selbst umgangen werden |

Ein SoD-Modell sollte definieren:

- Konfliktregel
- betroffene Rollen
- Risikostufe
- zulässige Ausnahme
- erforderliche Kompensation
- Ablaufdatum
- Review-Verantwortung

## Privileged Access Management

Administrative und privilegierte Zugriffe benötigen zusätzliche Kontrollen.

Beispiele:

- Warehouse Administrator
- Cloud Administrator
- Security Administrator
- Database Owner
- Platform Root Account
- Break-glass Account

Geeignete Maßnahmen:

- MFA
- zeitlich begrenzte Aktivierung
- Just-in-Time Access
- Vier-Augen-Freigabe
- Session Logging
- getrennte Admin-Identität
- kein täglicher Betrieb mit Admin-Konto
- automatische Deaktivierung
- regelmäßige Rezertifizierung

Ein Administrator darf technisch häufig mehr, als fachlich erforderlich ist.

Governance muss deshalb Nutzung und Zweck nachvollziehbar machen.

## Service Accounts und technische Identitäten

Technische Identitäten sind häufig weniger sichtbar als menschliche Nutzer.

Ein Service Account sollte mindestens besitzen:

| Metadatum | Beispiel |
| --- | --- |
| **Name** | `svc_dbt_prod` |
| **Typ** | Service Account |
| **Owner** | Data Platform Team |
| **Zweck** | dbt Production Deployment |
| **Umgebung** | Production |
| **Berechtigungen** | Create/Replace in governed schemas |
| **Secret Owner** | Platform Security |
| **Rotation** | alle 90 Tage |
| **Letzte Nutzung** | 2026-07-11 |
| **Ablaufdatum** | 2027-01-01 |
| **Notfallprozess** | IAM Escalation |

Anti-Patterns sind:

- gemeinsam genutzte technische Benutzer
- unbekannter Owner
- dauerhaft gültige Secrets
- produktive Adminrechte
- keine Nutzungsprotokolle
- Service Accounts bleiben nach Projektende aktiv

## Access Reviews und Rezertifizierung

Ein Review sollte nicht nur fragen:

*„Benötigt der Nutzer noch Zugriff?“*

Sondern:

- Ist die fachliche Rolle noch aktuell?
- Wird der Zugriff tatsächlich genutzt?
- Ist das Zugriffslevel angemessen?
- Gibt es direkte Einzelberechtigungen?
- Bestehen SoD-Konflikte?
- Ist die Schutzklasse unverändert?
- Gibt es neue technische Alternativen?
- Ist die Ausnahme noch notwendig?
- Existiert der Nutzer oder Service Account noch?
- Muss Zugriff reduziert oder entzogen werden?

Review-Frequenzen können risikobasiert sein:

| Zugriff | Beispielhafte Frequenz |
| --- | --- |
| Standard Internal | jährlich |
| Confidential | halbjährlich |
| Restricted PII | quartalsweise |
| Privileged Access | monatlich oder quartalsweise |
| Temporärer Zugriff | automatisch zum Ablaufdatum |

## Joiner, Mover, Leaver

Der Identity Lifecycle beeinflusst den Datenzugriff direkt.

### Joiner

- Basisrolle aus Funktion ableiten
- notwendige Schulungen prüfen
- Zugriff nach Policy bereitstellen

### Mover

- alte Berechtigungen überprüfen
- neue Rolle zuweisen
- kumulierte Rechte vermeiden
- SoD-Konflikte neu bewerten

### Leaver

- Zugriff zeitnah entziehen
- Sessions und Tokens sperren
- technische Schlüssel rotieren
- Eigentum an Reports, Apps und Datenprodukten übertragen
- offene Freigaben neu zuweisen

Der Mover-Prozess ist häufig das größte Risiko.

Berechtigungen werden hinzugefügt, aber alte Rechte nicht entfernt.

## Monitoring, Logging und Audit

Monitoring sollte erkennen:

- ungewöhnlich große Datenabfragen
- Zugriff außerhalb üblicher Zeiten
- neue privilegierte Berechtigungen
- wiederholte fehlgeschlagene Zugriffe
- unmaskte Nutzung sensibler Daten
- Exporte großer Datenmengen
- Zugriff auf nicht verwendete Systeme
- Nutzung veralteter Konten
- direkte Berechtigungsvergabe
- Änderungen an Rollen und Policies
- Zugriff nach Rollenwechsel oder Austritt
- Umgehung kontrollierter Semantic Layers

Ein Event allein beweist keinen Missbrauch.

Monitoring sollte Kontext liefern:

```text
Identity
  +
Role
  +
Data Sensitivity
  +
Purpose
  +
Volume
  +
Time
  +
Historical Behavior
  =
Risk Signal
```

## Zugriff im Modern Data Stack

Ein durchgängiges Modell kann so aussehen:

```text
Identity Provider
        ↓
Business Role
        ↓
Platform Role
        ↓
Warehouse / Lakehouse Policy
        ↓
Semantic Layer
        ↓
Qlik / Power BI / Tableau / Excel
        ↓
Export / API / Data Product
        ↓
Monitoring + Review
```

Die Zugriffskette sollte möglichst konsistent bleiben.

Probleme entstehen, wenn jede Schicht eigene, nicht abgestimmte Regeln besitzt.

Beispiel:

```text
Warehouse:
PII is masked

BI Extract:
PII was exported unmasked

Excel:
Local copy remains accessible indefinitely
```

Governance muss deshalb die gesamte Nutzungskette betrachten.

## Access Governance für Self-Service

Self-Service ist wichtig, erhöht aber die Zahl möglicher Zugriffspfade.

Governance sollte ermöglichen:

- zertifizierte Datenprodukte
- kontrollierte Datenräume
- rollenbasierte Self-Service-Berechtigungen
- sichtbare Schutzklassen
- standardisierte Exportregeln
- nachvollziehbare Freigaben
- automatische Ablaufdaten
- sichere Sandbox-Modelle
- Monitoring ohne unnötige Blockade

Das Ziel ist nicht maximale Einschränkung.

Das Ziel ist kontrollierte und nachvollziehbare Nutzung.

## Access-Metadaten

Ein Datenkatalog kann Access-Metadaten enthalten:

| Metadatum | Bedeutung |
| --- | --- |
| **Access Owner** | fachlich verantwortliche Freigaberolle |
| **Default Access Level** | Standardzugriff |
| **Sensitivity** | Schutzklasse |
| **Allowed Roles** | freigegebene Rollen |
| **Restricted Attributes** | besonders geschützte Felder |
| **Masking Policy** | technische Schutzregel |
| **Export Policy** | Download erlaubt, eingeschränkt oder verboten |
| **Review Frequency** | Prüfintervall |
| **Retention of Logs** | Aufbewahrung der Zugriffsprotokolle |
| **SoD Rules** | bekannte Rollenkonflikte |
| **Approval Workflow** | erforderliche Freigabeschritte |
| **Emergency Access** | Break-glass-Verfahren |

Damit wird Zugriff Teil der Asset-Beschreibung.

## Access & Security Governance messen

Nützliche Kennzahlen sind:

- Anteil kritischer Assets mit Access Owner
- Anteil sensibler Assets mit dokumentierter Zugriffspolicy
- Anteil Nutzer mit rollenbasierter Berechtigung
- Anzahl direkter Nutzerfreigaben
- Anzahl privilegierter Konten
- Anzahl veralteter oder verwaister Konten
- Anzahl Service Accounts ohne Owner
- Anteil zeitlich begrenzter Zugriffe
- Abschlussquote von Access Reviews
- Anzahl überfälliger Reviews
- Zeit bis zur Bereitstellung eines genehmigten Zugriffs
- Zeit bis zum Entzug nach Rollenwechsel
- Zeit bis zum Entzug nach Austritt
- Anzahl SoD-Konflikte
- Anzahl genehmigter Ausnahmen
- Anzahl abgelaufener Ausnahmen
- Anzahl fehlgeschlagener oder verdächtiger Zugriffe
- Anzahl unmaskter Zugriffe auf sensible Daten
- Anzahl großer Datenexporte
- Audit Findings im Berechtigungsumfeld
- Anteil kontrollierter gegenüber direkter Zugriffswege

Kennzahlen sollten nicht nur Security Events messen.

Sie sollten zeigen, ob der Berechtigungsprozess aktuell, nachvollziehbar und wirksam ist.

## Ein einfaches Reifegradmodell

| Reifegrad | Typischer Zustand |
| --- | --- |
| **Ad hoc** | Berechtigungen werden manuell und direkt vergeben |
| **Dokumentiert** | Rollen und erste Genehmigungsprozesse sind beschrieben |
| **Rollenbasiert** | Standardrollen und Owner sind etabliert |
| **Policy-basiert** | Schutzklassen, Zweck und Attribute steuern Zugriff |
| **Automatisiert** | Provisionierung, Ablauf und Entzug sind workflow-gesteuert |
| **Integriert** | Identität, Datenklassifikation und Plattformkontrollen sind verbunden |
| **Überwacht** | Nutzung, Konflikte und Ausnahmen werden kontinuierlich analysiert |
| **Adaptive Governance** | Zugriff reagiert risikobasiert auf Kontext und Veränderungen |

## Typische Anti-Patterns

- jeder Nutzer erhält individuelle Berechtigungen
- Berechtigungen werden hinzugefügt, aber selten entfernt
- Rollen sind historisch gewachsen und zu breit
- Business-Rollen und technische Rollen sind nicht verbunden
- Data Owner sind nicht in Freigaben eingebunden
- Reviews bestehen nur aus großen Excel-Listen
- Review-Verantwortliche verstehen technische Rollennamen nicht
- Service Accounts besitzen keinen Owner
- Secrets laufen nie ab
- Adminrechte werden für den täglichen Betrieb verwendet
- Shared Accounts verhindern Verantwortlichkeit
- Self-Service-Tools umgehen Maskierung oder Semantic Layer
- Exporte werden nicht berücksichtigt
- sensible Daten sind im Warehouse geschützt, aber im BI-Extrakt offen
- Ausnahmen besitzen kein Ablaufdatum
- SoD-Konflikte werden nicht erkannt
- Logs existieren, werden aber nicht ausgewertet
- Rollenwechsel führen nur zu zusätzlichen Berechtigungen
- Zertifizierung wird als einmalige Prüfung verstanden
- Erfolg wird an schnellen Freigaben statt an angemessenem Zugriff gemessen

Ein genehmigter Zugriff bleibt nicht automatisch dauerhaft angemessen.

Access Governance ist ein Lifecycle.

## Verbindung zu den anderen Governance-Säulen

| Säule | Verbindung |
| --- | --- |
| **Data Ownership & Stewardship** | Owner und Stewards entscheiden fachliche Zugriffsregeln und Ausnahmen |
| **Metadata, Catalog & Lineage** | Schutzklassen, Owner und Zugriffspfade werden sichtbar |
| **PII & Privacy Governance** | PII-Kategorien steuern Maskierung, Freigabe und Monitoring |
| **DSDR Governance** | Zugriff auf Fälle, Identitätsdaten und Nachweise muss streng kontrolliert sein |
| **Data Quality Governance** | Qualitätsstatus und sensible Fehlerdaten benötigen differenzierte Sichtbarkeit |
| **KPI & Metric Governance** | Detailmetriken und sensitive Dimensionen werden rollenbasiert bereitgestellt |
| **Data Lifecycle & Retention** | Ablauf, Löschung und Archivierung gelten auch für Berechtigungen und Logs |

Access & Security Governance ist damit die operative Verbindung zwischen Identität, fachlicher Verantwortung, Datenschutz und technischer Durchsetzung.

## Praktisches Zielbild

```text
Identity
        ↓
Business Role
        ↓
Access Request + Purpose
        ↓
Data Owner Approval
        ↓
RBAC / ABAC / Policy
        ↓
Warehouse + Semantic + BI Enforcement
        ↓
Monitoring + Logging
        ↓
Review + Revoke
```

## Das Ergebnis

Wirksame Access & Security Governance schafft:

- **Kontrolle** — Berechtigungen folgen klaren Regeln
- **Verantwortlichkeit** — fachliche und technische Rollen sind nachvollziehbar
- **Transparenz** — Zugriff, Nutzung und Ausnahmen werden sichtbar
- **Sicherheit** — sensible Daten werden angemessen geschützt
- **Compliance** — Freigaben, Reviews und Entscheidungen sind auditierbar
- **Effizienz** — standardisierte Rollen und Workflows reduzieren manuelle Einzelarbeit
- **Flexibilität** — Self-Service bleibt möglich, ohne Schutzprinzipien aufzugeben
- **Risikoreduktion** — übermäßige, veraltete und unkontrollierte Berechtigungen werden reduziert

Die entscheidende Frage lautet nicht:

*„Kann sich der Nutzer anmelden?“*

Sondern:

***„Besitzt diese Identität genau den Zugriff, den sie für ihren aktuellen Zweck benötigt — nicht mehr und nicht länger?“***

Verwandte Übersicht: [Die 8 Säulen der Data Governance](/playbooks/eight-pillars).

Vorherige Säule: [KPI & Metric Governance](/playbooks/kpi-metric-governance).

Nächste Säule: [Data Lifecycle & Retention](/playbooks/data-lifecycle-retention).
