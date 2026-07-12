---
title: DSDR Governance
description: Ein praxisnahes Betriebsmodell, um Data Subject Deletion Requests über Systeme, Pipelines und Datenprodukte hinweg nachvollziehbar, fristgerecht und kontrolliert umzusetzen.
category: Data Governance
tags:
  - data-governance
  - dsdr
  - deletion-request
  - gdpr
  - privacy
  - data-deletion
  - retention
  - lineage
order: -1
publishedAt: 2026-06-05
series: governance-pillars
seriesPart: 5
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/dsdr-gov-hero.png
---

## Löschung ist kein Knopfdruck, sondern ein Governance-Prozess

Ein **Data Subject Deletion Request (DSDR)** wirkt auf den ersten Blick einfach: Eine betroffene Person möchte, dass personenbezogene Daten gelöscht werden. In der Praxis ist das jedoch selten auf eine einzelne Tabelle oder ein einzelnes System begrenzt.

Personenbezogene Daten können sich gleichzeitig befinden in:

- operativen Quellsystemen
- CRM-, ERP- und HR-Anwendungen
- Data Warehouses und Lakehouses
- Roh-, Conform- und Analytics-Schichten
- Dateien, Exporten und Backups
- BI-Datasets, Reports und API-Ausleitungen
- Test-, Sandbox- und Entwicklungsumgebungen
- nachgelagerten Dritt- oder Fachsystemen

Organisationen scheitern bei DSDR-Prozessen selten an fehlendem guten Willen. Meist fehlen **Sichtbarkeit, klare Zuständigkeiten, verlässliche Metadaten und ein kontrollierter Ablauf**.

**DSDR Governance** übersetzt daher datenschutzrechtliche Anforderungen in ein operatives Steuerungsmodell für Identifikation, Bewertung, Löschung, Nachweis und kontinuierliche Verbesserung.

> *Eine Löschanfrage ist erst dann sauber umgesetzt, wenn relevante Daten gefunden, korrekt bewertet, kontrolliert gelöscht oder berechtigt ausgenommen, nachvollziehbar dokumentiert und innerhalb definierter Fristen beantwortet wurden.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dsdr-gov-de.png"
        alt="DSDR Governance mit Rechten der betroffenen Person, Governance-Rahmenwerk, Offenlegung und Transparenz, Rollen und Kennzahlen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        DSDR Governance verbindet Antragserfassung, Identitätsprüfung, Datensuche, Löschung, Offenlegung und dokumentierten Nachweis zu einem Ende-zu-Ende-Prozess.
    </figcaption>
</figure>

## Was DSDR Governance umfasst

DSDR Governance fokussiert sich auf die kontrollierte Bearbeitung von Löschanfragen betroffener Personen. Sie berührt dabei mehrere Datenschutzrechte und organisatorische Pflichten.

Typische Bestandteile sind:

- Annahme und formale Erfassung der Anfrage
- Prüfung von Identität und Berechtigung
- Zuordnung zur betroffenen Person über Systeme hinweg
- Bewertung gesetzlicher, vertraglicher oder fachlicher Aufbewahrungspflichten
- Löschung, Sperrung, Anonymisierung oder begründete Teilablehnung
- Berücksichtigung von Ableitungen, Kopien und Downstream-Nutzung
- Dokumentation aller Schritte und Entscheidungen
- fristgerechte Rückmeldung an die betroffene Person
- Auditierbarkeit und kontinuierliche Verbesserung des Gesamtprozesses

DSDR Governance ist damit nicht nur ein Privacy-Thema. Sie liegt an der Schnittstelle von **Datenschutz, Metadaten, Ownership, Lineage, Zugriff, Lifecycle und operativem Datenmanagement**.

## Warum DSDR-Prozesse schwierig sind

Löschung wird komplex, sobald Daten über viele Schichten repliziert oder transformiert werden.

Typische Probleme sind:

| Problem | Auswirkung |
| --- | --- |
| **Verteilte Systemlandschaft** | Relevante Daten liegen in vielen Anwendungen und Speichern |
| **Fehlende eindeutige Zuordnung** | Eine Person kann nicht zuverlässig über alle Systeme gefunden werden |
| **Mangelnde Lineage** | Downstream-Objekte und Ableitungen bleiben unberücksichtigt |
| **Unklare Ownership** | Niemand entscheidet sauber über Ausnahmen oder Priorität |
| **Aufbewahrungspflichten** | Nicht alles darf sofort gelöscht werden |
| **Backups und Snapshots** | Daten bleiben technisch vorhanden, obwohl produktive Daten gelöscht wurden |
| **Manuelle Einzelfallarbeit** | Prozesse sind langsam, fehleranfällig und kaum skalierbar |
| **Fehlende Nachweise** | Die Organisation kann die korrekte Bearbeitung nicht belegen |

Ein gutes DSDR-Betriebsmodell muss diese Realität explizit berücksichtigen.

## Die Kernprinzipien

Ein wirksamer DSDR-Prozess sollte sich an einigen Grundprinzipien orientieren:

### Transparenz
Anfragen, Status, Zuständigkeiten und Entscheidungen müssen nachvollziehbar sein.

### Verhältnismäßigkeit und Rechtmäßigkeit
Nicht jede Löschanfrage führt zu sofortiger Voll-Löschung. Gesetzliche, vertragliche oder legitime Aufbewahrungsgründe müssen bewertet werden.

### Datenminimierung
Je weniger unnötige personenbezogene Daten repliziert werden, desto leichter wird DSDR überhaupt handhabbar.

### Nachvollziehbarkeit
Alle Schritte müssen dokumentiert, prüfbar und wiederholbar sein.

### Fristenorientierung
Anfragen dürfen nicht im operativen Tagesgeschäft untergehen. Fristen, Eskalationen und SLA-ähnliche Mechanismen sind essenziell.

### Ende-zu-Ende-Denken
Nicht nur das Quellsystem zählt. Auch Warehouses, Reports, Exporte, Modelle und APIs müssen berücksichtigt werden.

## Das DSDR Operating Model

Ein praxistauglicher Ablauf kann so aussehen:

```text
Anfrage erfassen
        ↓
Identität prüfen
        ↓
Daten finden und zuordnen
        ↓
Rechtlich/fachlich bewerten
        ↓
Löschen, sperren oder begründet ausnehmen
        ↓
Antwort bereitstellen und Nachweis sichern
        ↓
Protokollieren, überwachen und verbessern
```

### 1. Anfrage erfassen

Anfragen sollten über einen definierten Kanal eingehen und strukturiert erfasst werden.

Wichtige Informationen sind zum Beispiel:

- Identität bzw. Identifikatoren der betroffenen Person
- Anfrageart und Eingangsdatum
- relevante Systeme oder Geschäftsbeziehung, falls bekannt
- Kommunikationsweg und Rückkanal
- Friststart und Bearbeitungsstatus
- zugeordnete verantwortliche Stelle

Eine saubere Intake-Stufe verhindert, dass Anfragen als E-Mail, Ticket oder Excel-Eintrag „versickern“.

### 2. Identität prüfen

Vor der Bearbeitung muss sichergestellt werden, dass die Anfrage wirklich von der betroffenen Person oder einer berechtigten Vertretung stammt.

Die Intensität der Prüfung hängt vom Risiko und vom Kanal ab. Ziel ist ein angemessenes Gleichgewicht zwischen Datenschutz und Benutzerfreundlichkeit.

Typische Maßnahmen:

- Abgleich bekannter Identifikatoren
- Verifizierung über bestehende Kunden- oder Mitarbeiterkanäle
- dokumentierte Identitätsnachweise, wenn erforderlich
- gesonderte Behandlung unklarer oder unvollständiger Anfragen

### 3. Daten finden und zuordnen

Dies ist häufig der kritischste Schritt.

Um Daten systemübergreifend zu finden, helfen insbesondere:

- ein Dateninventar mit relevanten Systemen und Domänen
- verlässliche PII-Klassifikationen
- eindeutige oder ableitbare Personen-Schlüssel
- Data Catalogs mit Verantwortlichkeiten
- Lineage-Informationen zwischen Quelle, Modell, Report und Export
- bekannte Downstream-Abhängigkeiten
- Such- und Matching-Regeln für verschiedene Identifikatoren

Beispiele für relevante Identifikatoren können sein:

- Kundennummer
- Personalnummer
- E-Mail-Adresse
- Vertragsnummer
- Login-ID
- CRM- oder ERP-Schlüssel
- pseudonymisierte Mapping-Keys

Ohne gute Zuordnung wird DSDR schnell zum Suchspiel mit unvollständigem Ergebnis.

### 4. Rechtlich und fachlich bewerten

Nicht alle Daten dürfen sofort gelöscht werden.

Die Bewertung kann folgende Ergebnisse haben:

| Bewertung | Bedeutung |
| --- | --- |
| **Löschen** | Die Daten dürfen und sollen entfernt werden |
| **Anonymisieren** | Personenbezug kann wirksam entfernt werden |
| **Sperren / Verarbeitung einschränken** | Nutzung wird begrenzt, vollständige Löschung ist aktuell nicht zulässig |
| **Teilweise ausnehmen** | Einzelne Datenbestände unterliegen Aufbewahrungspflichten |
| **Ablehnen** | Die Anfrage ist unberechtigt oder rechtlich nicht umsetzbar |

Bewertet werden müssen z. B.:

- gesetzliche Aufbewahrungspflichten
- steuer- oder handelsrechtliche Vorgaben
- offene Verträge, Forderungen oder rechtliche Ansprüche
- Sicherheits- und Audit-Anforderungen
- technische Unterscheidung zwischen produktiver Nutzung und Backup-Wiederherstellung

Ein sauberer Prozess dokumentiert nicht nur das Ergebnis, sondern auch die Begründung.

### 5. Löschen, sperren oder ausnehmen

Die operative Umsetzung sollte je nach Plattform standardisiert erfolgen.

Betroffene Bereiche können sein:

- Quellsystem-Datensätze
- Tabellen, Dateien und Objektspeicher
- Staging- und RAW-Schichten
- Conform- und Analytics-Modelle
- Materialisierungen, Snapshots und Exporte
- BI-Extrakte oder semantische Modelle
- Downstream-Datenprodukte
- Test- und Dev-Kopien

Mögliche Maßnahmen:

- physische Löschung
- logische Löschung mit Sperrstatus
- irreversible Anonymisierung
- selektive Attributentfernung
- Löschkennzeichen für nachgelagerte Verarbeitung
- dokumentierte Ausnahme wegen Retention

Wichtig ist Konsistenz: Wenn eine Person im operativen System gelöscht wird, aber in abgeleiteten Analytics-Objekten erhalten bleibt, ist die Anfrage oft nicht wirklich abgeschlossen.

### 6. Antwort bereitstellen und Nachweis sichern

Die Organisation sollte die betroffene Person verständlich und fristgerecht informieren.

Die Antwort kann umfassen:

- Bestätigung der Bearbeitung
- Umfang der gelöschten Daten
- begründete Ausnahmen oder Teilablehnungen
- Hinweise auf Aufbewahrungspflichten
- verbleibende Speicherorte, soweit rechtlich oder prozessual relevant
- Kontaktmöglichkeit für Rückfragen

Parallel dazu muss ein belastbarer interner Nachweis bestehen, z. B.:

- Bearbeitungsprotokoll
- Entscheidungspfad
- betroffene Systeme
- Ausführungsstatus je System
- verantwortliche Rollen
- Zeitstempel und Fristeinhaltung

## Rollen und Verantwortlichkeiten

DSDR Governance funktioniert nur mit klarer Rollentrennung.

| Rolle | Typische Verantwortung |
| --- | --- |
| **Privacy / Datenschutz** | definiert Leitplanken, bewertet Sonderfälle, unterstützt bei rechtlichen Fragen |
| **Data Owner** | entscheidet für den eigenen Datenbereich über Löschbarkeit, Ausnahmen und Prioritäten |
| **Data Steward** | kennt Datenbedeutung, Metadaten, Klassifikationen und fachliche Nutzung |
| **Data Engineering / Platform Team** | setzt Löschpfade, Workflows, technische Regeln und Monitoring um |
| **Security / IT** | unterstützt bei Zugriff, Nachweis, technischen Kontrollen und Infrastrukturthemen |
| **Fachbereich** | bestätigt geschäftliche Relevanz, Abhängigkeiten oder Retention-Kontext |
| **Service / Request Management** | steuert Intake, Status, Kommunikation und Fristen |

Die Rollennamen können variieren. Entscheidend ist, dass Entscheidung, Pflege und technische Umsetzung nicht implizit oder zufällig erfolgen.

## DSDR und Metadaten

Ein DSDR-Prozess wird deutlich robuster, wenn zentrale Metadaten vorhanden sind.

Hilfreiche Metadaten sind zum Beispiel:

| Metadatum | Beispiel |
| --- | --- |
| **PII-Status** | `true` |
| **DSDR-Relevanz** | `true` |
| **Primärer Personen-Key** | `customer_id` |
| **Alternative Identifikatoren** | `email`, `contract_id` |
| **Retention-Klasse** | `customer_contract` |
| **Owner** | `Customer Domain Owner` |
| **Steward** | `CRM Data Steward` |
| **Downstream-Abhängigkeiten** | `analytics.customer_360` |
| **Löschmethode** | `hard_delete` / `anonymize` / `restrict` |
| **Review-Datum** | `2026-07-01` |

Metadaten reduzieren die manuelle Einzelfallarbeit und verbessern die Nachvollziehbarkeit von Entscheidungen.

## DSDR und Lineage

Lineage ist für Löschprozesse besonders wertvoll.

Beispiel:

```text
CRM.customer
        ↓
RAW.customer
        ↓
CONFORM.customer_master
        ↓
ANALYTICS.customer_value_segment
        ↓
BI Dataset / Dashboard / API Export
```

Wenn nur das Quellsystem betrachtet wird, bleiben Downstream-Nutzungen leicht unsichtbar.

Lineage hilft zu beantworten:

- Welche Modelle hängen von einem personenbezogenen Quellfeld ab?
- Welche Reports oder APIs nutzen das resultierende Attribut?
- Wo müssen Löschung, Neuberechnung oder Sperrung ausgelöst werden?
- Welche Ableitungen könnten weiterhin Rückschlüsse auf die Person zulassen?

DSDR Governance ohne Lineage bleibt meist lückenhaft.

## Retention und Ausnahmen sauber behandeln

Ein häufiger Fehler ist die Annahme, jede Löschanfrage müsse überall zur sofortigen physischen Löschung führen.

In der Praxis braucht es klare Regeln für Fälle wie:

- gesetzliche Aufbewahrungspflichten
- steuerlich relevante Dokumente
- Rechnungen und Vertragsnachweise
- offene Rechtsstreitigkeiten oder Ansprüche
- sicherheitsrelevante Protokolle
- technische Backups mit eingeschränkter Zugriffssituation

Wichtig ist nicht nur die Ausnahme selbst, sondern ihre **klare Dokumentation**:

- warum die Ausnahme gilt
- welche Daten betroffen sind
- wie lange sie gilt
- welche Nutzung weiterhin erlaubt oder gesperrt ist
- wann ein neuer Review erforderlich ist

## Backups, Snapshots und nicht-produktive Umgebungen

DSDR scheitert oft an Randbereichen der Architektur.

Besonders kritisch sind:

- Datenbank-Backups
- Snapshots und Clone-Funktionen
- Dateiexporte
- Test- und Entwicklungsumgebungen
- persönliche Analysten-Extrakte
- Sandbox-Kopien

Nicht immer ist dort eine unmittelbare selektive Löschung technisch sinnvoll oder möglich. Dann braucht es ein definiertes Modell, etwa:

- keine produktive Nutzung dieser Kopien
- streng eingeschränkter Zugriff
- definierte maximale Aufbewahrungsdauer
- Löschung bei Wiederherstellung oder Rebuild
- dokumentierte Ausnahme mit Risikoabwägung

## Welche Teile sollten automatisiert werden?

Geeignete Automatisierungen sind zum Beispiel:

- Intake-Workflows mit Status und SLA-Steuerung
- Routing an zuständige Owner und Teams
- Systemsuche anhand definierter Identifikatoren
- Abgleich gegen DSDR-relevante Assets im Katalog
- Generierung von Work Items je System oder Domäne
- Nachverfolgung des Umsetzungsstatus
- Eskalationen bei Fristüberschreitung
- dokumentierte Entscheidungsvorlagen für Ausnahmen
- Audit-Logs für Bearbeitung und Ausführung
- Rebuild oder Refresh nach Löschung in Downstream-Schichten

Automatisierung reduziert Zeit und Fehlerquote, ersetzt aber keine fachliche und rechtliche Bewertung in Grenzfällen.

## Welche Kennzahlen helfen?

Nützliche Steuerungsgrößen sind z. B.:

- Anzahl eingegangener DSDR-Anfragen
- Anteil fristgerecht abgeschlossener Anfragen
- durchschnittliche Bearbeitungszeit
- Anzahl offener oder überfälliger Anfragen
- Anteil automatisiert angestoßener Systemschritte
- Anzahl Systeme pro Anfrage im Durchschnitt
- Anteil Anfragen mit dokumentierter Teil-Ausnahme
- Anzahl fehlgeschlagener oder unvollständiger Löschaktionen
- Anzahl Datenassets ohne bekannten DSDR-Owner
- Anteil DSDR-relevanter Assets mit Retention-Regel
- Anzahl Anfragen mit notwendigen Nacharbeiten in Downstream-Systemen

Kennzahlen sollten zeigen, ob der Prozess **wirksam, skalierbar und nachweisbar** ist — nicht nur, wie viele Tickets bearbeitet wurden.

## Ein einfaches Reifegradmodell

| Reifegrad | Typischer Zustand |
| --- | --- |
| **Ad hoc** | Löschanfragen werden manuell und uneinheitlich bearbeitet |
| **Definiert** | Es gibt einen formalen Prozess und benannte Kontaktstellen |
| **Inventarisiert** | relevante Systeme, PII-Assets und Zuständigkeiten sind bekannt |
| **Gesteuert** | Workflow, Fristen und dokumentierte Entscheidungen sind etabliert |
| **Integriert** | Katalog, Lineage, Retention und technische Umsetzungswege greifen zusammen |
| **Überwacht** | SLA, Ausnahmen, Wirksamkeit und Risiken werden aktiv gemessen |
| **Eingebettet** | DSDR ist Teil des normalen Datenbetriebs und der Architekturentscheidungen |

## Typische Anti-Patterns

DSDR-Initiativen werden oft geschwächt durch:

- Löschanfragen nur per E-Mail ohne Tracking
- keine verlässliche Identitätsprüfung
- Dateninventar ohne DSDR-Relevanz oder Verantwortlichkeit
- nur Quellsysteme werden betrachtet, nicht Downstream-Nutzung
- fehlende Unterscheidung zwischen Löschung, Sperrung und Ausnahme
- Retention-Regeln sind unbekannt oder nirgends dokumentiert
- Test- und Dev-Daten werden ignoriert
- Backups werden gar nicht im Modell berücksichtigt
- Fachbereiche und Owner sind nicht eingebunden
- Entscheidungen werden nicht begründet oder versioniert
- Erfolg wird nur über Fristeinhaltung statt über Vollständigkeit und Nachweis gemessen

Eine fristgerechte, aber unvollständige Löschung ist kein guter Prozess.

## Verbindung zu den anderen Governance-Säulen

| Säule | Verbindung |
| --- | --- |
| **Data Ownership & Stewardship** | Owner und Stewards entscheiden über Datenbedeutung, Ausnahmen und Priorität |
| **Metadata, Catalog & Lineage** | helfen beim Auffinden, Zuordnen und Nachverfolgen relevanter Daten |
| **PII & Privacy Governance** | liefert Klassifikationen und Schutzkontext für personenbezogene Daten |
| **Data Quality Governance** | korrekte Identifikatoren und Metadaten verbessern DSDR-Zuordnung |
| **KPI & Metric Governance** | personenbezogene Ableitungen in Kennzahlen müssen ggf. neu berechnet werden |
| **Access & Security Governance** | Zugriffe, Protokollierung und kontrollierte Ausnahmebehandlung werden unterstützt |
| **Data Lifecycle & Retention** | definiert, wann Daten gelöscht werden dürfen, müssen oder ausgenommen sind |

DSDR Governance ist damit ein Querschnittsprozess, der mehrere Governance-Säulen praktisch zusammenführt.

## Das Zielbild

Ein robustes DSDR-Modell kann so aussehen:

```text
Betroffene Person / Anfrageportal
        ↓
Intake + Identitätsprüfung
        ↓
Katalog + PII-Metadaten + Owner + Lineage
        ↓
Bewertung von Löschbarkeit und Retention
        ↓
Systemübergreifende Ausführung + Nachverfolgung
        ↓
Antwort + Nachweis + Audit
        ↓
Review + Prozessverbesserung
```

Damit wird aus einer einzelnen Löschanfrage ein steuerbarer und auditierbarer Governance-Ablauf.

## Das Ergebnis

Wirksame DSDR Governance schafft:

- **Vertrauen** — betroffene Personen können ihre Rechte nachvollziehbar ausüben
- **Compliance** — die Organisation reagiert kontrolliert und fristgerecht
- **Transparenz** — relevante Daten, Systeme und Ausnahmen werden sichtbar
- **Kontrolle** — Entscheidungen und Löschpfade werden standardisiert und dokumentiert
- **Effizienz** — wiederholbare Workflows reduzieren manuelle Einzelfallarbeit
- **Business Value** — geringeres Risiko, bessere Nachweisfähigkeit und sauberere Datenprozesse

DSDR ist kein Randprozess für Ausnahmefälle. In datenintensiven Organisationen ist es ein wesentlicher Teil wirksamer Data Governance.

Verwandte Übersicht: [Die 8 Säulen der Data Governance](/playbooks/eight-pillars).

Vorherige Säule: [PII & Privacy Governance](/playbooks/pii-privacy-governance).

Nächste Säule: [Data Quality Governance](/playbooks/data-quality-governance).
