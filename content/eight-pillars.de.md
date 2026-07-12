---
title: Die 8 Säulen der Data Governance
description: Ein praxisnahes Governance-Modell für Verantwortung, Metadaten, Datenschutz, DSDR, Datenqualität, Kennzahlen, Zugriffe und den Datenlebenszyklus.
category: Data Governance
tags:
  - data-governance
  - data-ownership
  - metadata
  - pii
  - dsdr
  - data-quality
  - kpi-governance
  - data-lifecycle
order: -1
hero: images/playbooks/eight-pillar-hero.png
---

## Governance ist kein einzelnes Tool

Data Governance wird häufig mit einem Data Catalog, einer Policy-Sammlung oder einem Freigabeprozess gleichgesetzt. In der Praxis reicht keiner dieser Bausteine allein aus. Gute Governance entsteht erst dann, wenn **Verantwortung, Wissen, Schutz, Qualität und Nutzung** zusammenwirken.

Die acht Säulen in diesem Playbook bilden deshalb kein starres Framework und keine Produktarchitektur. Sie sind ein **praxisnahes Orientierungsmodell**, mit dem sich Governance-Initiativen strukturieren, Lücken erkennen und operative Maßnahmen priorisieren lassen.

> *Data Governance ist nicht nur Kontrolle. Sie schafft die Grundlage dafür, dass Daten auffindbar, verständlich, geschützt, vertrauenswürdig und für Entscheidungen nutzbar werden.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/eight-pillar-de.png"
        alt="Die acht Säulen der Data Governance: Verantwortung, Metadaten, PII, DSDR, Datenqualität, KPI Governance, Zugriff und Datenlebenszyklus"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Acht miteinander verbundene Säulen schaffen Compliance, Vertrauen, Effizienz, Transparenz und messbaren Business Value.
    </figcaption>
</figure>

## Die acht Säulen im Überblick

| Säule | Zentrale Frage | Typisches Ergebnis |
| --- | --- | --- |
| **1. Datenverantwortung & Stewardship** | Wer ist fachlich und technisch verantwortlich? | Klare Rollen, Ownership und Eskalationswege |
| **2. Metadaten, Katalog & Datenherkunft** | Welche Daten existieren, was bedeuten sie und woher kommen sie? | Auffindbarkeit, Kontext und End-to-End-Transparenz |
| **3. PII & Datenschutz-Governance** | Welche Daten sind personenbezogen und wie müssen sie geschützt werden? | Klassifizierung, Schutzregeln und kontrollierte Verarbeitung |
| **4. DSDR Governance** | Wie werden Löschanfragen sicher, vollständig und nachweisbar umgesetzt? | Gesteuerter Lösch- oder Anonymisierungsprozess |
| **5. Data Quality Governance** | Sind die Daten korrekt, vollständig, aktuell und konsistent? | Messbare Qualitätsstandards und kontinuierliche Verbesserung |
| **6. KPI & Metric Governance** | Wie stellen wir sicher, dass Kennzahlen überall gleich verstanden werden? | Einheitliche Definitionen und vertrauenswürdige KPIs |
| **7. Zugriffs- & Sicherheits-Governance** | Wer darf welche Daten sehen oder verändern? | Rollenbasierter Zugriff, Least Privilege und Auditierbarkeit |
| **8. Datenlebenszyklus & Aufbewahrung** | Wie lange werden Daten gespeichert, archiviert oder gelöscht? | Automatisierte Retention- und Löschregeln |

## 1. Datenverantwortung & Stewardship

Governance beginnt mit Verantwortung. Ohne benannte Owner und Stewards bleiben Regeln unverbindlich, Qualitätsprobleme ungelöst und Entscheidungen zwischen Fachbereich, Data Engineering und Plattformbetrieb hängen.

Eine belastbare Ownership-Struktur definiert mindestens:

- **Data Owner** — fachliche Verantwortung, Priorisierung und Freigabe
- **Data Steward** — Definitionen, Qualität, Klassifikation und operative Pflege
- **Technical Owner** — technische Umsetzung, Betrieb und Integrationen
- **Klare Eskalationswege** — wer bei Konflikten, Risiken oder Qualitätsproblemen entscheidet

Ownership sollte möglichst direkt an Datenprodukte, Domänen, Quellen oder kritische Kennzahlen gebunden sein — nicht nur in einer separaten Governance-Tabelle stehen.

## 2. Metadaten, Katalog & Datenherkunft

Metadaten machen Daten verständlich. Ein Katalog hilft beim Auffinden, aber erst die Verbindung aus **technischen Metadaten, fachlichem Kontext und Lineage** schafft echte Transparenz.

Wichtige Bausteine sind:

- technische Metadaten zu Tabellen, Spalten, Modellen und Datentypen
- fachliche Beschreibungen, Begriffe und Definitionen
- Owner, Stewards und Domänenzuordnung
- Klassifikationen wie PII, Vertraulichkeit oder Kritikalität
- Lineage von der Quelle bis zum Datenprodukt, KPI oder Report
- Impact Analysis für geplante Änderungen

Metadaten sollten möglichst nah an der Entstehung gepflegt, versioniert und automatisiert weitergegeben werden. Wo dbt eingesetzt wird, können Beschreibungen, Tests und `meta`-Attribute einen wichtigen Teil dieser Kette bilden.

## 3. PII & Datenschutz-Governance

PII Governance beantwortet nicht nur die Frage, **ob** eine Spalte personenbezogene Daten enthält. Sie beschreibt auch, welche Schutzmaßnahmen daraus folgen und wie diese technisch durchgesetzt werden.

Ein operativer PII-Prozess umfasst typischerweise:

- personenbezogene Daten identifizieren und klassifizieren
- Schutzbedarf und Sensitivität festlegen
- Masking, Tokenisierung oder Zugriffsbeschränkungen anwenden
- Datenschutzregeln entlang der Lineage weitergeben
- Änderungen versionieren und nachvollziehbar freigeben
- unbeabsichtigtes Entfernen wichtiger Schutz-Metadaten verhindern

Der entscheidende Schritt ist die Verbindung von Klassifikation und technischer Kontrolle. Ein PII-Tag ohne Masking-, Zugriffs- oder Prozesswirkung bleibt nur Dokumentation.

## 4. DSDR Governance

In diesem Playbook steht **DSDR für Data Subject Deletion Request** — also für eine konkrete Löschanfrage einer betroffenen Person im Kontext der DSGVO.

Eine DSDR ist kein einzelnes `DELETE`-Statement. Daten können in operativen Systemen, Data Warehouses, Data Lakes, Exporten, Analysemodellen oder nachgelagerten Anwendungen liegen. Gleichzeitig können gesetzliche oder geschäftliche Aufbewahrungspflichten einer vollständigen Löschung entgegenstehen.

Ein kontrollierter DSDR-Prozess sollte daher:

1. Identität, Berechtigung und Gültigkeit der Anfrage prüfen
2. betroffene Person und Suchschlüssel eindeutig bestimmen
3. relevante Daten systemübergreifend auffinden
4. Aufbewahrungspflichten, Sperren und Ausnahmen bewerten
5. Daten löschen, anonymisieren oder die Verarbeitung einschränken
6. abhängige Systeme und Datenprodukte berücksichtigen
7. Durchführung dokumentieren und revisionssicher nachweisen

Governance schafft hier die Verbindung zwischen Datenschutz, Metadaten, Lineage, Workflows und technischer Ausführung.

## 5. Data Quality Governance

Data Quality ist mehr als eine Sammlung technischer Tests. Governance legt fest, **welche Qualität erforderlich ist**, wer Abweichungen bewertet und wie Probleme nachhaltig behoben werden.

Typische Qualitätsdimensionen sind:

| Dimension | Beispiel |
| --- | --- |
| **Vollständigkeit** | Pflichtfelder sind befüllt |
| **Gültigkeit** | Werte entsprechen Format, Wertebereich oder Referenzdaten |
| **Eindeutigkeit** | Geschäftsschlüssel sind nicht mehrfach vorhanden |
| **Konsistenz** | Werte widersprechen sich nicht zwischen Systemen |
| **Aktualität** | Daten stehen innerhalb der vereinbarten Zeit bereit |
| **Genauigkeit** | Daten bilden den tatsächlichen Sachverhalt korrekt ab |

Operativ braucht es definierte Regeln, messbare Schwellenwerte, Monitoring, Alerts, Ownership und einen Remediation-Prozess. Tests sollten nicht nur fehlschlagen, sondern eine klare Reaktion auslösen.

## 6. KPI & Metric Governance

Zwei Reports mit demselben KPI-Namen sollten nicht unterschiedliche Ergebnisse liefern. Genau hier setzt KPI & Metric Governance an.

Für kritische Kennzahlen sollten mindestens festgelegt werden:

- eindeutiger Name und fachliche Definition
- Berechnungslogik und verwendete Datenquellen
- Filter, Zeitbezug und Aggregationsregeln
- verantwortlicher Owner
- freigegebene Version und Gültigkeitszeitraum
- bekannte Einschränkungen und Qualitätsanforderungen
- Lineage bis zu den zugrunde liegenden Daten

Ein semantischer Layer oder Metric Store kann die technische Standardisierung unterstützen. Governance stellt zusätzlich sicher, dass Definitionen abgestimmt, versioniert und organisationsweit verständlich sind.

## 7. Zugriffs- & Sicherheits-Governance

Zugriff sollte nicht pauschal, sondern nach Zweck, Rolle und Schutzbedarf vergeben werden. Das Ziel ist: **die richtigen Daten für die richtigen Personen — nicht mehr und nicht länger als erforderlich**.

Wichtige Prinzipien sind:

- Role-Based oder Attribute-Based Access Control
- Least Privilege
- Trennung kritischer Aufgaben und Verantwortlichkeiten
- zeitlich begrenzte oder genehmigungspflichtige Zugriffe
- regelmäßige Rezertifizierung von Berechtigungen
- Audit Logs und Monitoring sensibler Zugriffe
- technische Durchsetzung durch Policies, Row-Level Security oder Masking

PII-Klassifikation und Access Governance sollten direkt verbunden sein. Je höher der Schutzbedarf, desto klarer müssen Freigabe, Kontrolle und Nachweis sein.

## 8. Datenlebenszyklus & Aufbewahrung

Daten sollten nicht unbegrenzt gespeichert werden, nur weil Speicher günstig ist. Retention Governance definiert, wie Daten von der Entstehung bis zur Löschung behandelt werden.

Ein vollständiger Lebenszyklus kann folgende Phasen umfassen:

```text
Create → Use → Share → Archive → Delete or Anonymize
```

Für relevante Datenklassen werden Regeln festgelegt für:

- Aufbewahrungsdauer
- Archivierung und Wiederherstellbarkeit
- Legal Holds und gesetzliche Ausnahmen
- automatische Löschung oder Anonymisierung
- Umgang mit Backups, Exporten und Replikaten
- Nachweis der erfolgreichen Ausführung

Diese Säule ergänzt DSDR: Retention steuert die reguläre, regelbasierte Aufbewahrung und Löschung; DSDR behandelt eine konkrete Löschanfrage einer betroffenen Person.

## Wie die Säulen zusammenwirken

Die Säulen sind keine getrennten Programme. Sie verstärken sich gegenseitig:

```text
Ownership
    ↓
Metadata & Lineage
    ↓
PII Classification
    ↓
Access, Retention & DSDR Controls
    ↓
Data Quality & Trusted Metrics
    ↓
Reliable Decisions and Business Value
```

Ein Beispiel: Eine Spalte wird als PII klassifiziert. Über die Lineage ist sichtbar, in welchen Modellen und Reports sie verwendet wird. Access Policies begrenzen den Zugriff, Retention-Regeln steuern die Aufbewahrung und ein DSDR-Workflow kennt alle betroffenen Systeme. Owner und Stewards sind für Freigaben und Ausnahmen verantwortlich. Genau durch diese Verbindung wird aus Metadaten operative Governance.

## Von Dokumentation zu ausführbarer Governance

Viele Initiativen starten mit Richtlinien und Excel-Listen. Das ist oft notwendig, aber nicht ausreichend. Reife Governance verschiebt Regeln schrittweise näher an die technische Ausführung.

| Reifegrad | Typisches Bild |
| --- | --- |
| **Dokumentiert** | Definitionen und Policies existieren, werden aber manuell gepflegt |
| **Zugeordnet** | Owner, Stewards, Domänen und Datenklassen sind verknüpft |
| **Messbar** | Qualität, Nutzung und Kontrollen werden überwacht |
| **Automatisiert** | Regeln erzeugen Tests, Policies, Workflows oder technische Kontrollen |
| **Nachweisbar** | Änderungen, Entscheidungen und Ausführungen sind versioniert und auditierbar |

Das Ziel ist nicht maximale Automatisierung um jeden Preis. Entscheidend ist, dass kritische Governance-Regeln **konsistent, wiederholbar und nachvollziehbar** umgesetzt werden.

## Ein mögliches Umsetzungsprinzip

In einem modernen Data Stack kann Governance als durchgängige Kette aufgebaut werden:

```text
Business Definitions
        ↓
Metadata as Code
        ↓
dbt Models, Tests & Meta
        ↓
Warehouse Policies & Access Controls
        ↓
Catalog, Lineage & Monitoring
        ↓
Governed Data Products and KPIs
```

Dabei bleibt die konkrete Tool-Auswahl offen. Entscheidend ist nicht, ob ein einzelnes Produkt alle acht Säulen abdeckt. Entscheidend ist, dass Informationen und Kontrollen zwischen den eingesetzten Systemen zuverlässig verbunden sind.

## Typische Anti-Patterns

Governance verliert Wirkung, wenn sie nur außerhalb der operativen Datenprozesse stattfindet. Häufige Muster sind:

- ein Data Catalog ohne gepflegte Ownership
- PII-Tags ohne technische Schutzwirkung
- Data-Quality-Tests ohne Alerts oder Verantwortliche
- KPIs ohne freigegebene Definition
- Berechtigungen ohne regelmäßige Überprüfung
- Retention Policies ohne automatisierte Ausführung
- DSDR-Prozesse ohne vollständige System- und Lineage-Sicht
- Richtlinien, die nicht versioniert oder auditierbar sind

Keine einzelne Säule kann diese Lücken allein schließen.

## Das eigentliche Ziel

Gute Data Governance soll Daten nicht unnötig verlangsamen. Sie soll Unsicherheit reduzieren und verlässliche Nutzung ermöglichen.

Das Ergebnis sind:

- **Compliance** — gesetzliche und regulatorische Anforderungen werden nachvollziehbar erfüllt
- **Vertrauen** — Daten und Kennzahlen sind verständlich und verlässlich
- **Effizienz** — standardisierte Prozesse reduzieren manuelle Arbeit und Wiederholungen
- **Transparenz** — Herkunft, Verantwortung, Qualität und Nutzung werden sichtbar
- **Business Value** — bessere Daten unterstützen bessere Entscheidungen und Ergebnisse

Die acht Säulen bilden dafür einen gemeinsamen Rahmen. Sie helfen, Governance nicht als isolierte Kontrollfunktion zu behandeln, sondern als verbindende Schicht zwischen **Menschen, Prozessen, Metadaten, Plattformen und Business Value**.
