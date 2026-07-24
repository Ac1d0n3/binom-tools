---
title: End-to-End-Governance-Architektur
description: Eine praxisnahe Architektur, die Richtlinien, Ownership, Metadaten, dbt-Transformation, Snowflake-Schutz und governte Qlik-Nutzung über den vollständigen Datenlebenszyklus verbindet.
category: Data Governance
tags:
  - data-governance
  - governance-architektur
  - dbt
  - snowflake
  - qlik
  - metadaten
  - lineage
  - masking
  - row-level-security
  - data-quality
products:
  - snowflake
  - dbt
  - qlik
order: -1
author: Thomas Lindackers
series: end-to-end-data-governance
seriesPart: 1
seriesTitle: End-to-End Data Governance
hero: images/playbooks/end-to-end-governance-architecture-hero.png
---

## Governance ist eine Architektur — kein Katalog neben der Plattform

Viele Governance-Initiativen beginnen mit einem Katalog, einer Klassifikationstabelle oder einem Richtliniendokument.

Diese Elemente sind nützlich. Keines davon erzeugt jedoch allein eine End-to-End-Governance.

Governance wird erst operativ, wenn eine fachliche Entscheidung durch die technische Plattform verfolgt werden kann:

```text
Fachliche Richtlinie
→ freigegebene Metadaten
→ implementierte Transformation
→ Laufzeitkontrolle
→ operativer Nachweis
→ Governance-Review
```

Endet die Kette nach der Dokumentation, kann die Plattform wissen, dass eine Spalte personenbezogene Daten enthält, ohne diese tatsächlich zu schützen.

Beginnt die Kette erst bei der Laufzeitsicherheit, kann eine Spalte maskiert sein, ohne den fachlichen Grund, die Ownership oder den Freigabestatus hinter dieser Kontrolle zu erhalten.

> **End-to-End-Governance verbindet Entscheidungen, Metadaten, Code, Schutz, Zugriff und Nachweise zu einem kontrollierten System.**

Dieser Artikel definiert dieses System. Die folgenden Parts vertiefen einzelne Mechanismen.

## Mit einer klaren Verantwortungsgrenze beginnen

Die erste Architekturentscheidung ist einfach, aber wichtig:

> **Die Ingestion lädt Daten in die Plattform. dbt transformiert Daten, die bereits dort vorhanden sind.**

Ein typischer Ablauf lautet:

```text
Quellsysteme
→ Ingestion oder Replikation
→ Landing-Tabellen
→ dbt-Transformation
→ governte Warehouse-Layer
→ governte Nutzung
```

Der Ingestion-Mechanismus kann ein Managed Connector, ein CDC-Service, eine Orchestrierungspipeline, ein File Loader oder ein eigener Prozess sein.

Zu seinen Verantwortlichkeiten gehören:

- Verbindung zu Quellsystemen;
- Extraktion und Laden von Datensätzen;
- Erhalt technischer Liefermetadaten;
- Wiederholungen und Checkpoints;
- Erkennung fehlgeschlagener oder verspäteter Loads;
- Erstellung oder Aktualisierung physischer Landing-Objekte.

dbt beginnt, nachdem diese physischen Landing-Objekte existieren.

Zu den dbt-Verantwortlichkeiten gehören:

- Deklaration von Sources;
- Transformation quellsystemnaher Daten;
- Tests und Contracts;
- Lineage;
- Dokumentation und Artefakte;
- Materialisierung governter Modelle;
- Bereitstellung von Metadaten für nachgelagerte Automatisierung.

Werden beide Themen in einer gemeinsamen Prozessbox zusammengefasst, werden Ownership und Fehleranalyse unnötig unklar.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/end-to-end-governance-architecture-img1-de.png"
        alt="End-to-End-Governance-Architektur mit getrennter Ingestion, governten dbt-Warehouse-Layern, Qlik-Anwendungen und einem durchgehenden Governance-Band für Ownership, Metadaten, Qualität, Schutz und Nachweise"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die Daten bewegen sich durch die Plattform. Governance-Entscheidungen werden an mehreren expliziten Kontrollpunkten umgesetzt. Ingestion und dbt bleiben getrennte Verantwortlichkeiten.
    </figcaption>
</figure>

## Die Architektur besteht aus drei verbundenen Ebenen

Eine governte Plattform wird verständlicher, wenn sie in drei Ebenen aufgeteilt wird.

### 1. Data Plane

Die Data Plane enthält die physischen und logischen Datenprodukte:

```text
Landing
→ RAW
→ Conform
→ Analytics
→ Anwendungen
```

Jeder Layer besitzt eine andere Verantwortung.

| Layer | Primäre Verantwortung |
| --- | --- |
| Landing | Gelieferte Quellstruktur und Ingestion-Metadaten erhalten |
| RAW | Quellsystemnahe Daten als kontrollierte Transformationsressourcen bereitstellen |
| Conform | Gemeinsame Entitäten standardisieren, harmonisieren und integrieren |
| Analytics | Kuratierte Fakten, Dimensionen, Kennzahlen und Use-Case-Modelle bereitstellen |
| Anwendungen | Governte Daten für Nutzer und operative Prozesse verfügbar machen |

Die Bezeichnungen können je Plattform abweichen. Entscheidend ist die Trennung der Verantwortlichkeiten.

### 2. Metadata Plane

Die Metadata Plane beschreibt die Data Plane.

Sie enthält unter anderem:

- physische Schemas und Datentypen;
- Source- und Modellbeschreibungen;
- Ownership und Domäne;
- Spaltenklassifikation;
- Sensitivität und Aufbewahrung;
- Lineage und Abhängigkeiten;
- Data-Quality-Ergebnisse;
- Freshness und Betriebsstatus;
- generierte Dokumentation;
- Warehouse-Tags und Policy-Referenzen.

Metadaten können über dbt-YAML, dbt-Artefakte, Warehouse-Tags, Kataloge, Berechtigungstabellen und operative Systeme verteilt sein.

Die Architektur benötigt deshalb einen expliziten Vertrag, der festlegt, welches System für welche Metadatenart führend ist.

### 3. Control Plane

Die Control Plane überführt Entscheidungen in erzwungenes Verhalten.

Sie umfasst:

- Richtlinien und Standards;
- Freigabeprozesse;
- CI-Validierung;
- Datentests und Contracts;
- Deployment-Kontrollen;
- Masking Policies;
- Row Access Policies;
- Rollen und Entitlements;
- Qlik Section Access;
- Audit- und Incident-Prozesse.

Ein Katalog kann eine Kontrolle beschreiben. Die Control Plane setzt sie um und überprüft sie.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/end-to-end-governance-architecture-img2-de.png"
        alt="Drei ausgerichtete Architekturebenen für Data Plane, Metadata Plane und Control Plane mit vertikalen Verbindungen zwischen Modellen, Metadaten und Governance-Kontrollen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Daten, Metadaten und Kontrollen folgen unterschiedlichen Pfaden. Governance funktioniert, wenn diese Pfade an definierten Kontrollpunkten verbunden werden, ohne sie zu einem gemeinsamen Prozess zu vermischen.
    </figcaption>
</figure>

## Governance-Entscheidungen benötigen einen technischen Vertrag

Eine Richtlinie wie „Die E-Mail-Adresse des Kunden ist vertrauliche personenbezogene Information“ ist für Automatisierung zu abstrakt.

Die Plattform benötigt eine maschinenlesbare Darstellung:

```yaml
columns:
  - name: email
    config:
      meta:
        pii: true
        pii_category: email
        sensitivity: confidential
        classification_status: approved
        masking_policy: email_mask
        data_owner: customer_operations
        steward: data_governance
```

Diese Metadaten schützen die Spalte nicht selbstständig.

Sie bilden einen technischen Vertrag, der:

- in Git geprüft;
- in CI validiert;
- in dbt-Artefakte kompiliert;
- in der Dokumentation angezeigt;
- durch Generierungs- und Propagierungslogik verwendet;
- auf Warehouse-Tags und Policies abgebildet;
- mit der Laufzeitimplementierung verglichen;
- als Review-Nachweis genutzt werden kann.

Part 2 definiert diesen Vertrag im Detail.

## Klassifikation und Durchsetzung sind unterschiedliche Verantwortlichkeiten

Eine Klassifikation beantwortet:

```text
Welche Art von Daten ist das?
Wie sensibel sind sie?
Wer verantwortet die Entscheidung?
Welche Behandlung wurde freigegeben?
```

Die Durchsetzung beantwortet:

```text
Was darf die aktuelle Identität sehen?
Welche Zeilen sind erlaubt?
Welche Werte müssen maskiert werden?
Welche Anwendung darf die Daten nutzen?
```

Beides muss verbunden werden, darf aber nicht verwechselt werden.

Eine belastbare Architektur trennt mindestens zwei Schutzdimensionen.

### Spaltenschutz

Der Spaltenschutz steuert die Darstellung sensibler Werte.

Beispiele:

- vollständige Maskierung;
- Teilmaskierung;
- Hashing;
- Tokenisierung;
- Ersetzung durch `null`;
- rollenabhängiger Klartext.

Snowflake Dynamic Data Masking wendet Masking Policies zur Abfragezeit auf Spalten an. Tag-basiertes Masking kann Object Tags und Masking Policies verbinden, sodass markierte Spalten entsprechend der gültigen Policy und des Datentyps geschützt werden.

### Zeilenbasierter Zugriff

Zeilenbasierter Zugriff entscheidet, welche Datensätze sichtbar sind.

Beispiele:

- Region;
- Gesellschaft;
- Abteilung;
- Kundenportfolio;
- Mandant;
- Vertragsbereich.

Snowflake Row Access Policies können diese Filterung zentral im Warehouse erzwingen.

Qlik Section Access kann innerhalb einer Anwendung eine dynamische Datenreduktion anwenden, indem Benutzerberechtigungen mit Reduktionsfeldern im Datenmodell verbunden werden.

Beide Kontrollen können sich ergänzen:

```text
Warehouse Policy
→ schützt das governte Datenprodukt zentral

Qlik Section Access
→ begrenzt die Anwendungszeilen für Nutzer und Use Case
```

Qlik darf nicht der einzige Ort sein, an dem sensible Daten geschützt werden. Ein Nutzer oder Service mit direktem Warehouse-Zugriff würde eine rein anwendungsseitige Kontrolle umgehen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/end-to-end-governance-architecture-img3-de.png"
        alt="Kontrollierter Ablauf von fachlicher Klassifikation und Steward-Freigabe über dbt-Metadaten und Validierung bis zu Warehouse-Masking, Row Access, Qlik Section Access und Audit-Nachweisen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine Klassifikation wird erst wirksam, wenn freigegebene Metadaten in technische Kontrollen übersetzt werden. Spaltenmaskierung und zeilenbasierter Zugriff bleiben getrennte Schutzdimensionen.
    </figcaption>
</figure>

## Governance-Verantwortlichkeiten nach Rolle

Governance scheitert, wenn jedes Team davon ausgeht, dass ein anderes Team die endgültige Entscheidung trifft.

Ein praktikables Ownership-Modell unterscheidet folgende Rollen.

### Data Owner

Der Data Owner genehmigt die fachliche Nutzung der Daten.

Typische Entscheidungen:

- erlaubte Zwecke;
- Domänenverantwortung;
- Sensitivität;
- Aufbewahrung;
- externe Weitergabe;
- akzeptables fachliches Risiko.

### Data Steward

Der Steward pflegt die Klassifikation und stellt die konsistente Verwendung des Governance-Vokabulars sicher.

Typische Verantwortlichkeiten:

- Beschreibungen und Glossar-Abgleich;
- PII-Kategorie;
- Klassifikationsstatus;
- Review-Datum;
- Koordination von Issues;
- Freigabenachweise.

### Data Engineering oder Platform Engineering

Engineering implementiert das governte Datenprodukt.

Typische Verantwortlichkeiten:

- Source-Deklarationen;
- Transformationen;
- Tests und Contracts;
- Metadatenintegration;
- Deployment;
- Lineage;
- technische Policy-Anbindung;
- Drift-Erkennung.

Engineering kann eine wahrscheinliche PII-Spalte erkennen, darf die Klassifikation aber nicht stillschweigend freigeben.

### Security und Privacy

Security und Privacy definieren Schutzstandards und genehmigen risikoreiche Kontrollen.

Typische Verantwortlichkeiten:

- Maskierungsmuster;
- Policy-Administration;
- Rollendesign;
- Funktionstrennung;
- Datenschutzanforderungen;
- Access Reviews;
- Incident Handling.

### BI- oder Application Owner

Der Application Owner implementiert nutzungsspezifische Zugriffskontrollen.

Typische Verantwortlichkeiten:

- App-Zugriff;
- Section Access;
- Reduktionsfelder;
- freigegebene Exporte;
- Visualisierungsverhalten;
- Nutzerkommunikation;
- Anwendungsmonitoring.

Die Übergaben zwischen diesen Rollen müssen sichtbar sein. Ein Feld kann gleichzeitig einen fachlichen Owner, einen technischen Owner und einen Application Owner besitzen.

## Qualität ist Teil der Governance

Governance beschränkt sich nicht auf PII und Berechtigungen.

Ein Datenprodukt ist nicht vertrauenswürdig, wenn seine Ownership dokumentiert ist, seine Werte aber verspätet, unvollständig oder strukturell ungültig sind.

Die Architektur sollte Governance-Metadaten deshalb verbinden mit:

- Source Freshness;
- Not-null- und Unique-Erwartungen;
- referenzieller Integrität;
- erlaubten Werten;
- Volumenanomalien;
- Contract-Prüfungen;
- Abstimmungen;
- fachlicher Regelvalidierung;
- Incident-Schweregrad;
- Quality Ownership.

Nicht jeder Test ist gleich wichtig.

Ein praktikables Modell weist Quality Tier oder Kritikalität zu:

```yaml
config:
  meta:
    quality_tier: critical
    criticality: tier_1
    technical_owner: data_platform
```

CI und Orchestrierung können für kritische Modelle strengere Gates anwenden als für explorative Modelle.

## Lineage liefert Impact — aber keine automatische Verantwortlichkeit

Lineage beantwortet Fragen wie:

- Welche Quelle speist diese Kennzahl?
- Welche Modelle hängen von dieser Spalte ab?
- Welche Anwendungen könnten von einer Änderung betroffen sein?
- Wohin muss eine Klassifikation propagiert werden?
- Welche Kontrollen sollten downstream vorhanden sein?

Lineage entscheidet nicht, ob eine nachgelagerte Transformation die Klassifikation erhält oder verändert.

Beispiel:

```text
email
→ lower(email)
```

bleibt üblicherweise personenbezogen.

Dagegen kann:

```text
email
→ count(distinct email)
```

ein nicht personenbezogenes Aggregat erzeugen — abhängig von Granularität, Filtern und Offenlegungsrisiko.

Part 4 behandelt genau dieses Propagierungs- und Auflösungsproblem.

## Nachweise schließen den Governance-Regelkreis

Eine Policy-Implementierung muss Nachweise erzeugen.

Nützliche Nachweise sind:

- freigegebene Pull Requests;
- Ergebnisse der Metadatenvalidierung;
- dbt-Testergebnisse;
- Manifest- und Katalog-Artefakte;
- Lineage-Snapshots;
- Policy-Referenzen;
- Access Reviews;
- Query- und Zugriffslogs;
- Schema-Drift-Ereignisse;
- Incidents und Ausnahmen;
- Review-Daten.

Die Nachweise sollten drei Fragen beantworten:

```text
Was wurde freigegeben?
Was wurde implementiert?
Was ist zur Laufzeit geschehen?
```

Können diese Antworten nicht verbunden werden, existiert Dokumentation, aber keine auditierbare Governance.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/end-to-end-governance-architecture-img4-de.png"
        alt="Geschlossener Governance-Regelkreis von der Definition über Implementierung, Validierung, Durchsetzung und Beobachtung zurück zur Verbesserung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Governance wird als Regelkreis betrieben. Laufzeitnachweise und Incidents müssen Richtlinien, Metadaten, Modelle und Kontrollen verbessern.
    </figcaption>
</figure>

## Eine minimal tragfähige Governance-Architektur

Eine erste Umsetzung benötigt nicht jede Enterprise-Funktion.

Sie benötigt einen vollständigen Kontrollpfad.

Eine minimal tragfähige Architektur kann enthalten:

1. **Definiertes Governance-Vokabular**
   - Owner;
   - Domäne;
   - PII;
   - Sensitivität;
   - Klassifikationsstatus;
   - Aufbewahrungsklasse;
   - Schutz-Policy.

2. **Versionierte Metadaten**
   - dbt-YAML;
   - Code Review;
   - explizite Freigabeverantwortliche.

3. **Automatisierte Validierung**
   - Pflichtfelder;
   - erlaubte Werte;
   - PII-Policy-Prüfungen;
   - Prüfung ungeprüfter Spalten.

4. **Governte Transformationen**
   - Source-Deklarationen;
   - Tests;
   - Lineage;
   - klare Layer-Verantwortlichkeiten.

5. **Laufzeitkontrollen**
   - Warehouse-Rollen;
   - Masking;
   - zeilenbasierter Zugriff;
   - Anwendungsberechtigungen.

6. **Operative Nachweise**
   - Build-Ergebnisse;
   - Policy-Referenzen;
   - Access Review;
   - Incident-Prozess.

Das ist stärker als ein großer Katalog ohne Verbindung zu Delivery und Enforcement.

## Häufige Architekturfehler

### Governance-Metadaten existieren nur im Katalog

Code und Laufzeitplattform können sie nicht zuverlässig konsumieren.

### Metadaten existieren nur in dbt

Fachbereiche und Stewards können sie nicht wirksam prüfen und steuern.

### PII wird automatisch erkannt und als freigegeben behandelt

Erkennung ist ein Hinweis für das Review, keine Governance-Entscheidung.

### Zugriff wird ausschließlich in Qlik umgesetzt

Warehouse-Zugriffe außerhalb der Anwendung bleiben unzureichend geschützt.

### Jede Regel ist direkt in SQL eingebettet

Policy-Pflege wird dupliziert und schwer auditierbar.

### Metadaten werden ohne Transformationsverständnis kopiert

Abgeleitete Spalten übernehmen falsche Klassifikationen oder verlieren sensible Lineage.

### Ingestion, Transformation und Governance werden als eine Tool-Verantwortung betrachtet

Fehler, Ownership und Kontrollgrenzen werden unklar.

## Die zentrale Erkenntnis

> **Eine governte Datenplattform ist keine Abfolge von Tools. Sie ist ein verbundenes System aus Entscheidungen, Metadatenverträgen, Transformationen, Laufzeitkontrollen und Nachweisen.**

Part 2, [Metadatengetriebene Governance mit dbt meta](/playbooks/metadata-driven-governance-with-dbt-meta), überführt den Architekturvertrag in ein konkretes, versioniertes Metadatenmodell in dbt.

## Quellen und weiterführende Dokumentation

- [dbt — meta configuration](https://docs.getdbt.com/reference/resource-configs/meta)
- [dbt — manifest JSON](https://docs.getdbt.com/reference/artifacts/manifest-json)
- [dbt — about dbt artifacts](https://docs.getdbt.com/reference/artifacts/dbt-artifacts)
- [Snowflake — Dynamic Data Masking](https://docs.snowflake.com/en/user-guide/security-column-ddm-intro)
- [Snowflake — Tag-based masking policies](https://docs.snowflake.com/en/user-guide/tag-based-masking-policies)
- [Snowflake — Row access policies](https://docs.snowflake.com/en/user-guide/security-row-intro)
- [Qlik Sense — Managing data security with Section Access](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Scripting/Security/manage-security-with-section-access.htm)
