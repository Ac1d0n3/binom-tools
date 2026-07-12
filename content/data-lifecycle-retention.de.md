---
title: Data Lifecycle & Retention
description: Ein praxisnahes Betriebsmodell für den kontrollierten Umgang mit Daten von der Erzeugung bis zur sicheren Löschung — mit klaren Aufbewahrungsregeln, Ownership, Kostensteuerung und nachweisbaren Kontrollen.
category: Data Governance
tags:
  - data-governance
  - data-lifecycle
  - retention
  - archiving
  - data-deletion
  - data-classification
  - storage-optimization
  - compliance
  - records-management
order: -1
publishedAt: 2026-06-09
series: governance-pillars
seriesPart: 9
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/life-gov-hero.png
---

## Daten benötigen einen geregelten Lebenszyklus

Daten werden häufig sehr bewusst erzeugt oder übernommen — aber deutlich seltener kontrolliert archiviert, überprüft oder gelöscht.

Dadurch entstehen typische Probleme:

- operative Systeme enthalten historische Daten ohne aktuellen Nutzen
- Daten werden mehrfach repliziert und unterschiedlich lange aufbewahrt
- Analytics-Plattformen wachsen ohne klare Archivierungsstrategie
- Aufbewahrungsfristen existieren nur in Richtlinien, nicht in technischen Metadaten
- alte Datenprodukte bleiben aktiv, obwohl sie niemand mehr benötigt
- Backups, Exporte und lokale Kopien folgen anderen Regeln als produktive Systeme
- gesetzliche Aufbewahrung und Datenschutzanforderungen werden getrennt betrachtet
- Löschungen finden statt, ohne Downstream-Systeme einzubeziehen
- Daten bleiben „vorsichtshalber“ unbegrenzt erhalten
- Kosten für Storage, Compute und Betrieb steigen dauerhaft
- Datenqualität und Interpretierbarkeit sinken mit wachsender Historie
- niemand ist für die Entscheidung verantwortlich, wann Daten ihren Wert verlieren

**Data Lifecycle & Retention Governance** verbindet Geschäftsbedarf, rechtliche Anforderungen, Datenklassifikation, technische Umsetzung, Kostensteuerung und kontrollierte Löschung zu einem durchgängigen Betriebsmodell.

> *Gute Lifecycle Governance hält nicht möglichst viele Daten möglichst lange. Sie hält die richtigen Daten so lange wie notwendig — und entfernt sie kontrolliert, sobald ihr Zweck endet.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/life-gov-de.png"
        alt="Datenlebenszyklus von Erzeugung und aktiver Nutzung über Inaktivität, Archivierung und Prüfung bis zur sicheren Löschung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Data Lifecycle & Retention Governance steuert Daten von der Erzeugung über Nutzung und Archivierung bis zur kontrollierten Löschung.
    </figcaption>
</figure>

## Der Datenlebenszyklus

Ein praktischer Lebenszyklus kann sechs Phasen unterscheiden:

```text
1. Erzeugen / Erhalten
        ↓
2. Aktiv nutzen
        ↓
3. Inaktiv verwalten
        ↓
4. Archivieren
        ↓
5. Prüfen und bewerten
        ↓
6. Löschen oder dauerhaft anonymisieren
```

Diese Phasen sind keine rein technischen Speicherklassen.

Jede Phase verändert:

- erlaubte Nutzung
- Zugriff
- Schutzbedarf
- Verfügbarkeit
- Performance
- Kosten
- Aufbewahrung
- Monitoring
- Verantwortlichkeit

## 1. Erzeugen oder erhalten

Daten entstehen oder werden übernommen durch:

- operative Anwendungen
- Kunden- und Mitarbeiterprozesse
- Sensoren und Maschinen
- Dateien und Formulare
- APIs
- Datenlieferanten
- Partner
- externe Plattformen
- manuelle Erfassung
- analytische Ableitungen

Bereits bei der Entstehung sollten wichtige Fragen beantwortet werden:

- Warum werden die Daten benötigt?
- Welcher Zweck gilt?
- Wer ist Data Owner?
- Welche Klassifikation gilt?
- Enthalten die Daten PII?
- Welche Qualitätsanforderungen bestehen?
- Welche Aufbewahrungsklasse gilt?
- Welche Downstream-Nutzung ist erlaubt?
- Muss der Datensatz später vollständig reproduzierbar sein?
- Welche Lösch- oder Archivierungslogik wird benötigt?

Lifecycle Governance beginnt daher nicht beim Archiv, sondern beim Design.

## 2. Aktive Nutzung

Aktive Daten unterstützen laufende Prozesse, Analysen und Entscheidungen.

Typische Merkmale:

- häufige Nutzung
- hohe Verfügbarkeitsanforderung
- kurze Zugriffszeiten
- regelmäßige Aktualisierung
- operative oder analytische Relevanz
- breite Einbindung in Datenprodukte und Reports

Aktive Daten benötigen häufig:

- performanten Storage
- aktuelle Qualitätskontrollen
- klare Access Policies
- Monitoring und Freshness
- bekannte Owner
- dokumentierte Lineage
- definierte Wiederherstellungsziele

Aktiv bedeutet aber nicht automatisch dauerhaft aufbewahrungswürdig.

## 3. Inaktive Daten

Daten werden inaktiv, wenn sie nicht mehr regelmäßig benötigt werden, aber weiterhin einen möglichen fachlichen, rechtlichen oder historischen Wert besitzen.

Beispiele:

- abgeschlossene Kundenfälle
- beendete Projekte
- alte operative Transaktionen
- selten verwendete Detailhistorie
- abgelöste Datenprodukte
- ehemalige Mitarbeiterdaten
- nicht mehr aktuelle Referenzstände

Inaktive Daten sollten nicht einfach unsichtbar im produktiven System verbleiben.

Zu prüfen sind:

- Wird der Zugriff noch benötigt?
- Ist die aktuelle Speicherklasse wirtschaftlich?
- Kann das Datenprodukt heruntergestuft werden?
- Muss die Datenqualität weiterhin aktiv überwacht werden?
- Sind alle Downstream-Verwendungen bekannt?
- Gibt es laufende Aufbewahrungspflichten?
- Ist eine Archivierung sinnvoll?

## 4. Archivieren

Archivierung reduziert operative Last und erhält gleichzeitig notwendige Informationen.

Ein Archiv sollte nicht nur billiger Speicher sein.

Es benötigt:

- klare Scope-Regeln
- nachvollziehbare Übertragung
- Integritätsprüfung
- Zugriffskontrolle
- Verschlüsselung
- dokumentierte Aufbewahrung
- Wiederauffindbarkeit
- geregelte Wiederherstellung
- Auditierbarkeit
- definierte Löschung am Fristende

Typische Archivierungsstrategien:

| Strategie | Einsatz |
| --- | --- |
| **Cold Storage** | selten benötigte Daten mit niedriger Zugriffsfrequenz |
| **Historische Partition** | Zeitbasierte Trennung innerhalb derselben Plattform |
| **Read-only Archive** | unveränderbare historische Daten |
| **Object Storage** | kostengünstige Langzeitaufbewahrung |
| **Records Archive** | dokumentations- oder nachweispflichtige Inhalte |
| **Snapshot Archive** | reproduzierbare historische Zustände |
| **Aggregiertes Archiv** | Detaildaten entfernen, historische Kennzahlen erhalten |

Die Archivierungsstrategie sollte zum Wiederherstellungs- und Nutzungsbedarf passen.

## 5. Prüfen und bewerten

Retention darf nicht nur einmal definiert und dann vergessen werden.

Regelmäßige Reviews beantworten:

- Wird der ursprüngliche Zweck noch erfüllt?
- Wird das Asset noch genutzt?
- Besteht weiterhin eine rechtliche Aufbewahrungspflicht?
- Ist die Aufbewahrung wirtschaftlich sinnvoll?
- Sind Daten redundant?
- Kann Granularität reduziert werden?
- Können Daten anonymisiert werden?
- Gibt es ein Legal Hold?
- Ist die Klassifikation noch korrekt?
- Sind Owner und Verantwortlichkeiten noch aktuell?
- Kann das Asset stillgelegt werden?

Ein Review kann mehrere Ergebnisse haben:

```text
Weiter aktiv nutzen
        oder
Inaktiv stellen
        oder
Archivieren
        oder
Retention verlängern
        oder
Anonymisieren
        oder
Löschen
```

## 6. Löschen oder anonymisieren

Löschung ist der kontrollierte Endpunkt des Lebenszyklus.

Sie sollte:

- genehmigt
- technisch definiert
- nachvollziehbar
- vollständig
- wiederholbar
- validiert
- dokumentiert

sein.

Mögliche Maßnahmen:

| Maßnahme | Bedeutung |
| --- | --- |
| **Physische Löschung** | Datensatz wird technisch entfernt |
| **Sichere Löschung** | Daten werden entsprechend der Speichertechnologie irreversibel entfernt |
| **Anonymisierung** | Personenbezug wird dauerhaft entfernt |
| **Aggregation** | Detaildaten werden gelöscht, zusammengefasste Historie bleibt |
| **Pseudonymisierung** | Identifikatoren werden ersetzt; Personenbezug kann weiter bestehen |
| **Logische Löschung** | Datensatz wird als gelöscht markiert, bleibt technisch vorhanden |
| **Zeitversetzte Löschung** | Löschung erfolgt nach Ablauf einer definierten Frist |
| **Tombstone / Suppression** | Marker verhindert erneute Aufnahme gelöschter Daten |

Nicht jede Maßnahme erfüllt denselben Zweck.

Logische Löschung ist nicht automatisch Datenschutz-Löschung. Pseudonymisierung ist nicht automatisch Anonymisierung.

## Retention ist eine fachliche Entscheidung

Aufbewahrungsregeln sollten nicht allein durch Infrastruktur oder Datenbankadministration festgelegt werden.

Beteiligt sind typischerweise:

| Rolle | Verantwortung |
| --- | --- |
| **Data Owner** | bewertet fachlichen Zweck, Nutzen und erforderliche Aufbewahrung |
| **Data Steward** | pflegt Retention-Metadaten, Klassifikation und Reviews |
| **Legal / Compliance** | definiert gesetzliche und regulatorische Leitplanken |
| **Privacy** | bewertet Datenminimierung, Löschung und Datenschutzanforderungen |
| **Records Management** | steuert dokumentations- und nachweispflichtige Inhalte |
| **Platform / Infrastructure** | implementiert Speicherklassen, Archivierung und technische Löschung |
| **Data Engineering** | setzt Lifecycle-Regeln in Pipelines und Modellen um |
| **Security** | schützt Archive, Backups und Löschprozesse |
| **Data Product Owner** | entscheidet über Stilllegung, Migration und Nutzerkommunikation |

## Retention-Klassen

Ein standardisiertes Modell kann Retention-Klassen verwenden.

| Klasse | Beispiel | Typische Regel |
| --- | --- | --- |
| **Operational Short** | technische Staging-Daten | 7–30 Tage |
| **Operational Standard** | laufende Geschäftsvorfälle | abhängig vom Prozess |
| **Analytical Active** | aktive Datenprodukte | solange fachlich benötigt |
| **Historical** | abgeschlossene historische Daten | mehrjährige Aufbewahrung |
| **Regulated** | gesetzlich relevante Dokumente | fest definierte Frist |
| **PII Limited** | personenbezogene Daten ohne dauerhaften Zweck | so kurz wie möglich |
| **Legal Hold** | rechtlich gesperrte Daten | bis zur formalen Freigabe |
| **Archive** | Langzeitnachweis | kontrollierte Archivfrist |
| **Ephemeral** | temporäre Berechnungsdaten | Stunden oder Tage |

Die genaue Dauer hängt von Rechtsraum, Datenart, Vertrag und Geschäftszweck ab.

Eine Retention-Klasse sollte deshalb nicht nur eine Zahl enthalten.

## Retention-Policy als Metadatenobjekt

Beispiel:

```yaml
retention_policy:
  id: customer-contract-standard
  scope:
    domain: customer
    asset_types:
      - contract
      - invoice
  trigger:
    event: contract_closed
  active_period:
    duration: P1Y
  archive_period:
    duration: P9Y
  final_action: secure_delete
  legal_hold_supported: true
  owner: legal-records
  steward: customer-data-steward
  review_cycle: annual
```

Wichtige Bestandteile:

- Scope
- Start- oder Trigger-Ereignis
- aktive Phase
- Archivierungsphase
- finale Maßnahme
- Ausnahmen
- Legal Hold
- Owner
- Review-Zyklus
- technische Umsetzung

## Der Trigger ist oft wichtiger als die Dauer

Eine Regel wie „10 Jahre aufbewahren“ ist unvollständig.

Die entscheidende Frage ist:

***Ab welchem Ereignis beginnt die Frist?***

Mögliche Trigger:

- Erzeugungsdatum
- letzter Geschäftsvorfall
- Vertragsende
- Kontoauflösung
- Fallabschluss
- Rechnungsdatum
- Projektende
- letzter Login
- Widerruf einer Einwilligung
- Austritt eines Mitarbeiters
- Abschluss eines Rechtsverfahrens

Beispiel:

```text
Retention:
10 Jahre

Trigger:
Ende des Kalenderjahres,
in dem der Vertrag abgeschlossen wurde
```

Ohne korrektes Trigger-Ereignis kann dieselbe Frist unterschiedlich berechnet werden.

## Lifecycle-Metadaten

Ein Data Catalog kann enthalten:

| Metadatum | Beispiel |
| --- | --- |
| **Lifecycle Status** | Active |
| **Retention Class** | customer-contract-standard |
| **Retention Trigger** | contract_closed |
| **Retention Start** | 2026-07-01 |
| **Retention End** | 2036-12-31 |
| **Final Action** | secure_delete |
| **Archive Tier** | cold-storage |
| **Data Owner** | Customer Domain Owner |
| **Data Steward** | Contract Data Steward |
| **Legal Hold** | false |
| **Review Date** | 2027-07-01 |
| **Last Accessed** | 2026-07-10 |
| **Deletion Method** | platform_workflow_v2 |
| **Validation Rule** | no records after execution |

Damit wird Lifecycle zu einer messbaren Eigenschaft eines Datenassets.

## Daten entlang der Plattform-Layer steuern

Ein moderner Data Stack enthält mehrere Kopien.

Beispiel:

```text
Source
  ↓
Landing
  ↓
RAW
  ↓
CONFORM
  ↓
ANALYTICS
  ↓
Semantic Layer
  ↓
BI Extract
  ↓
Excel Export
```

Retention muss beantworten:

- Gilt dieselbe Frist in allen Schichten?
- Können Staging-Daten deutlich früher gelöscht werden?
- Müssen analytische Detaildaten genauso lange bestehen wie Quellbelege?
- Kann eine aggregierte Historie länger erhalten bleiben?
- Wie werden Exporte und Extrakte behandelt?
- Wie wird verhindert, dass ein Reload gelöschte Daten erneut erzeugt?

Ein häufiger Fehler ist:

```text
Quelle gelöscht
        ↓
Warehouse-Kopie bleibt bestehen
        ↓
BI-Extrakt bleibt bestehen
        ↓
Excel-Export bleibt unbegrenzt erhalten
```

Lifecycle Governance muss die gesamte Datenkette betrachten.

## Unterschiedliche Regeln pro Layer

Beispiel:

| Layer | Retention |
| --- | --- |
| **Landing** | 7 Tage |
| **RAW** | 90 Tage |
| **Conform** | 3 Jahre |
| **Analytics Detail** | 2 Jahre |
| **Analytics Aggregate** | 10 Jahre |
| **BI Extract** | 30 Tage |
| **Local Export** | 14 Tage |
| **Archive** | gemäß Fach- und Rechtsregel |

Eine solche Differenzierung reduziert Kosten und Risiko.

## Lifecycle und dbt

dbt kann Lifecycle-Regeln technisch unterstützen, zum Beispiel durch:

- zeitbasierte Modelle
- inkrementelle Bereinigung
- Retention-Metadaten in `meta`
- automatische Archivierungsmodelle
- Tests auf abgelaufene Daten
- Generierung von Lösch- oder Archivierungs-SQL
- Dokumentation von Lifecycle-Status
- Propagation von Retention-Klassen

Beispiel:

```yaml
models:
  - name: customer_events
    meta:
      lifecycle:
        status: active
        retention_class: customer-events-3y
        final_action: anonymize
        owner: customer-domain
```

Ein Macro kann daraus technische Aktionen vorbereiten.

Wichtig bleibt:

dbt ist ein Umsetzungswerkzeug. Die fachliche Retention-Entscheidung benötigt Governance.

## Backups und Restore

Backups folgen häufig eigenen Lifecycle-Regeln.

Zu definieren sind:

- Backup-Frequenz
- Aufbewahrungsdauer
- Verschlüsselung
- Zugriff
- Unveränderbarkeit
- Wiederherstellung
- Löschung
- Behandlung bereits gelöschter Datensätze nach Restore

Ein zentraler Kontrollpunkt:

```text
Restore
        ↓
Reapply valid deletion and suppression rules
        ↓
Validate restored environment
        ↓
Release for use
```

Sonst können bereits gelöschte Daten wieder in produktive Systeme gelangen.

## Legal Hold

Ein Legal Hold setzt reguläre Löschung vorübergehend aus.

Ein kontrollierter Prozess benötigt:

- eindeutigen Scope
- verantwortliche Stelle
- Startdatum
- Begründung
- betroffene Assets
- technische Sperre
- Review-Termin
- Freigabe zur Aufhebung
- dokumentierten Abschluss

Legal Hold darf nicht bedeuten, dass Daten unbegrenzt und ohne Review bestehen bleiben.

## Datenwert und Kosten

Lifecycle Governance ist nicht nur Compliance.

Sie unterstützt auch Kostensteuerung.

Kosten entstehen durch:

- Storage
- Replikation
- Backups
- Compute
- Datenübertragung
- Monitoring
- Katalogisierung
- Security
- Support
- Migration
- Wiederherstellung

Ein mögliches Bewertungsmodell:

```text
Business Value
        +
Legal Need
        +
Operational Need
        +
Analytical Need
        -
Storage Cost
        -
Security Risk
        -
Maintenance Cost
        =
Retention Decision
```

Nicht jeder alte Datensatz besitzt denselben Wert.

## Data Products stilllegen

Auch Datenprodukte benötigen einen Lifecycle.

Ein möglicher Status:

| Status | Bedeutung |
| --- | --- |
| **Draft** | in Entwicklung |
| **Active** | produktiv und unterstützt |
| **Limited** | nur eingeschränkt weiterentwickelt |
| **Deprecated** | Ablösung angekündigt |
| **Archived** | nicht mehr aktiv, historisch verfügbar |
| **Retired** | vollständig außer Betrieb |

Vor Stilllegung sollten geprüft werden:

- Nutzer
- Downstream-Abhängigkeiten
- Reports
- APIs
- geplante Jobs
- Dokumentation
- Owner
- Ersatzprodukt
- Migrationsfrist
- Archivbedarf
- Löschregel

## Retention und Data Quality

Lifecycle beeinflusst Datenqualität.

Beispiele:

- gelöschte Historie verändert Trendvergleiche
- Archivierung reduziert aktuelle Verfügbarkeit
- Teilhistorien erzeugen inkonsistente Reports
- fehlende Trigger-Daten verhindern korrekte Fristberechnung
- alte Stammdaten verlieren fachliche Relevanz
- historische Daten benötigen weiterhin Interpretierbarkeit

Qualitätsregeln können prüfen:

- Retention End Date vorhanden
- gültige Retention-Klasse
- Owner vorhanden
- abgelaufene Daten identifiziert
- keine aktive Nutzung archivierter Assets
- keine Daten nach finalem Löschdatum
- Legal Hold vollständig dokumentiert

## Monitoring

Lifecycle Monitoring kann erfassen:

- Datenvolumen je Lifecycle-Phase
- Assets ohne Retention-Klasse
- Assets ohne Owner
- abgelaufene Aufbewahrungsfristen
- offene Löschaufgaben
- fehlgeschlagene Archivierungen
- fehlgeschlagene Löschungen
- Legal Holds ohne Review
- ungenutzte aktive Datenprodukte
- selten genutzte Hot-Storage-Daten
- Archive ohne Wiederherstellungstest
- unkontrollierte Exporte
- lokale Kopien ohne Ablaufdatum
- Daten mit widersprüchlichen Regeln

## Data Lifecycle & Retention messen

Nützliche Kennzahlen:

- Anteil kritischer Assets mit Retention-Klasse
- Anteil Assets mit Owner und Steward
- Anteil Retention-Regeln mit dokumentiertem Trigger
- Anzahl abgelaufener Datensätze
- Zeit von Fristablauf bis technischer Löschung
- Anteil automatisierter Archivierungsprozesse
- Anteil automatisierter Löschprozesse
- Anzahl fehlgeschlagener Lifecycle-Jobs
- Datenvolumen nach Lifecycle-Phase
- Storage-Kosten nach Datenklasse
- Anteil selten genutzter Daten im teuren Storage
- Anzahl aktiver Legal Holds
- Anzahl überfälliger Legal-Hold-Reviews
- Anteil erfolgreicher Restore-Tests
- Anzahl wiederhergestellter, erneut zu löschender Datensätze
- Anzahl veralteter Datenprodukte
- Zeit von Deprecation bis Retirement
- Anteil gelöschter oder anonymisierter PII nach Fristende
- Anzahl Ausnahmen ohne Ablaufdatum
- Anzahl Assets mit widersprüchlicher Retention

## Ein einfaches Reifegradmodell

| Reifegrad | Typischer Zustand |
| --- | --- |
| **Unbegrenzt** | Daten werden ohne klare Regeln dauerhaft aufbewahrt |
| **Dokumentiert** | erste Aufbewahrungsregeln existieren in Richtlinien |
| **Klassifiziert** | Assets besitzen Retention-Klassen und Owner |
| **Technisch umgesetzt** | Archivierung und Löschung sind pro Plattform definiert |
| **Automatisiert** | Lifecycle-Aktionen laufen metadaten- und workflow-gesteuert |
| **Integriert** | Regeln gelten über Quellen, Plattformen, BI und Exporte hinweg |
| **Überwacht** | Fristen, Kosten, Ausnahmen und Löschqualität werden gemessen |
| **Wertorientiert** | Datenwert, Risiko und Kosten steuern den Lifecycle kontinuierlich |

## Typische Anti-Patterns

- alle Daten werden unbegrenzt aufbewahrt
- Retention steht nur in einer PDF-Richtlinie
- Fristen besitzen keinen definierten Trigger
- jede Plattform verwendet eigene Regeln
- Archive besitzen keinen Owner
- „Archiviert“ bedeutet nur „in einen anderen Bucket kopiert“
- Löschungen werden nicht validiert
- Quellsysteme löschen, Analytics-Kopien bleiben bestehen
- Exporte und Excel-Dateien werden ignoriert
- Backups können gelöschte Daten wieder einspielen
- Legal Holds besitzen kein End- oder Review-Datum
- alte Datenprodukte bleiben aktiv
- Kosten werden nur technisch, nicht fachlich bewertet
- Storage wird optimiert, aber fachlicher Kontext geht verloren
- PII wird länger gespeichert als notwendig
- Daten werden gelöscht, obwohl wichtige Historie noch benötigt wird
- Retention-Klassen sind zu generisch
- Ausnahmen besitzen keinen Owner
- Erfolg wird nur an gelöschtem Datenvolumen gemessen

Lifecycle Governance bedeutet weder „alles behalten“ noch „alles löschen“.

Sie schafft kontrollierte Entscheidungen über Wert, Risiko, Nutzung und Zeit.

## Verbindung zu den anderen Governance-Säulen

| Säule | Verbindung |
| --- | --- |
| **Data Ownership & Stewardship** | Owner und Stewards entscheiden Wert, Zweck, Fristen und Ausnahmen |
| **Metadata, Catalog & Lineage** | Lifecycle-Status, Trigger, Fristen und Downstream-Abhängigkeiten werden sichtbar |
| **PII & Privacy Governance** | Datenminimierung und Privacy by Design begrenzen unnötige Aufbewahrung |
| **DSDR Governance** | Löschanfragen nutzen Retention-Regeln und technische Löschmechanismen |
| **Data Quality Governance** | Lifecycle-Status und Retention-Metadaten benötigen eigene Qualitätskontrollen |
| **KPI & Metric Governance** | Historische Vergleichbarkeit hängt von Aufbewahrung und Reproduzierbarkeit ab |
| **Access & Security Governance** | Zugriff auf aktive, archivierte und gesperrte Daten wird unterschiedlich gesteuert |

Data Lifecycle & Retention bildet den zeitlichen Rahmen für alle anderen Governance-Säulen.

## Praktisches Zielbild

```text
Data Created
        ↓
Classified + Owned
        ↓
Active Use
        ↓
Usage and Value Monitoring
        ↓
Inactive / Archive
        ↓
Retention Review
        ↓
Delete / Anonymize / Retain with Justification
        ↓
Validation + Audit Evidence
```

## Das Ergebnis

Wirksame Data Lifecycle & Retention Governance schafft:

- **Compliance** — Aufbewahrung und Löschung folgen nachvollziehbaren Regeln
- **Datenschutz** — personenbezogene Daten bleiben nicht länger als notwendig erhalten
- **Transparenz** — Lifecycle-Status, Fristen und Verantwortlichkeiten sind sichtbar
- **Kostenkontrolle** — Storage und Betrieb werden nach Nutzung und Wert optimiert
- **Risikoreduktion** — unnötige Datenbestände und unkontrollierte Kopien werden reduziert
- **Nachvollziehbarkeit** — Archivierung, Ausnahmen und Löschung sind auditierbar
- **Effizienz** — standardisierte Metadaten und Automatisierung reduzieren manuelle Arbeit
- **Business Value** — relevante Historie bleibt nutzbar, ohne die Plattform unbegrenzt wachsen zu lassen

Die entscheidende Frage lautet nicht:

*„Wie lange können wir diese Daten technisch speichern?“*

Sondern:

***„Wie lange benötigen wir diese Daten fachlich und rechtlich — und welche kontrollierte Aktion folgt danach?“***

Verwandte Übersicht: [Die 8 Säulen der Data Governance](/playbooks/eight-pillars).

Vorherige Säule: [Access & Security Governance](/playbooks/access-security-governance).
