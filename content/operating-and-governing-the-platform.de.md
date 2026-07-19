---
title: Die Datenplattform betreiben und steuern
description: Wie ein modernes Data Warehouse als dauerhaftes Produkt mit klarer Ownership, kontrollierter Dev-/Test-/Produktionsüberführung, Monitoring, Datenqualität, Lineage, Kostensteuerung, Incident Handling, Versionierung und Stilllegung betrieben wird.
category: Datenarchitektur
tags:
  - data-architecture
  - modern-data-warehouse
  - data-operations
  - platform-governance
  - data-products
  - dev-test-prod
  - ci-cd
  - deployment
  - observability
  - monitoring
  - lineage
  - data-quality
  - incident-management
  - cost-management
  - versioning
  - deprecation
  - retirement
  - qlik-sense
  - power-bi
  - excel
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - sql
  - on-premises
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 10
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start10-hero.png
---

## Ein Warehouse ist mit dem ersten produktiven Bericht nicht fertig

Ein Warehouse kann am Tag der Veröffentlichung technisch korrekt sein und wenige Monate später trotzdem unzuverlässig werden.

Quellen ändern sich. Fachliche Definitionen entwickeln sich weiter. Pipelines schlagen fehl. Datenmengen wachsen. Kosten verändern sich. Owner wechseln ihre Rolle. Consumer erzeugen Abhängigkeiten, die während des ursprünglichen Projekts nicht sichtbar waren. Alte Views bleiben aktiv, weil niemand weiß, ob sie noch verwendet werden. Ein KPI erhält eine neue Definition, während eine Qlik-Anwendung, ein Power-BI-Modell und mehrere Excel-Arbeitsmappen weiterhin die vorherige Logik verwenden.

Der technische Aufbau ist deshalb nur ein Teil der Architektur.

Part 9, [Eine Architektur – mehrere Plattformen](/playbooks/platform-examples), hat gezeigt, dass dieselben logischen Warehouse-Verantwortlichkeiten mit einer vorhandenen SQL-Plattform, Microsoft Fabric, Snowflake, Databricks oder bewusst gewählten Hybridkombinationen umgesetzt werden können. Dieser letzte Part behandelt, was jede Umsetzung nach dem Aufbau benötigt: ein Betriebsmodell.

Eine nachhaltige Datenplattform muss folgende Fragen beantworten können:

```text
Wer verantwortet die fachliche Bedeutung?
Wer betreibt den technischen Service?
Welche Version ist in Produktion?
Welche Tests müssen vor der Veröffentlichung bestehen?
Wie aktuell sind die Daten?
Welche Consumer hängen vom Produkt ab?
Was passiert, wenn ein Lauf fehlschlägt?
Wie wird eine Änderung freigegeben und ausgerollt?
Wie kann ein Release zurückgesetzt oder korrigiert werden?
Was kostet die Plattform?
Wann wird eine alte Schnittstelle abgekündigt und stillgelegt?
```

Hängen diese Antworten vom Gedächtnis einer einzelnen Person ab, ist die Plattform nicht governt.

> **Ein Warehouse ist ein Produkt mit Lebenszyklus, Consumern und Serviceerwartungen — kein einmaliges Projekt, das nach dem Go-live endet.**

## Architekturprinzip: explizite Verträge betreiben

Betrieb und Governance werden häufig als getrennte Disziplinen behandelt.

Betrieb wird mit Zeitplänen, Logs, Alerts und Support verbunden. Governance wird mit Definitionen, Ownership, Richtlinien und Freigaben verbunden. In der Praxis treffen sich beide am Datenprodukt.

Ein Produkt kann nicht zuverlässig betrieben werden, wenn seine erwartete Bedeutung und sein Service Level unbekannt sind. Eine Definition kann nicht wirksam governt werden, wenn niemand feststellen kann, ob ihre Implementierung gelaufen ist, die Quality Gates bestanden hat und bei den Consumern angekommen ist.

Ein praktikabler Betriebsvertrag enthält mindestens:

| Vertragselement | Erforderliche Entscheidung |
| --- | --- |
| Fachlicher Owner | Wer trägt die Verantwortung für Bedeutung, fachliche Abnahme und Priorität? |
| Data Steward | Wer pflegt Definitionen, Qualitätserwartungen, Klassifizierungen und Issue-Nachverfolgung? |
| Technischer Owner | Wer baut und ändert Pipelines, Modelle und Tests? |
| Plattform-Owner | Wer betreibt Umgebungen, Zugriffe, Zeitplanung, Monitoring, Recovery und Capacity? |
| Produktgranularität | Was repräsentiert eine Zeile und welche Schlüssel definieren sie? |
| Aktualitätsziel | Wann muss das Produkt verfügbar sein und wie spät darf es sein? |
| Quality Gates | Welche Prüfungen blockieren die Veröffentlichung und welche erzeugen nur Warnungen? |
| Veröffentlichungsregel | Wie wird eine Version als freigegeben und sicher nutzbar markiert? |
| Consumer-Vertrag | Welche Tabellen, Views, Felder, Semantikmodelle, QVDs oder APIs werden unterstützt? |
| Incident-Pfad | Wer wird alarmiert, wer entscheidet, wer kommuniziert und wer behebt? |
| Recovery-Ziel | Reicht ein Retry, ist ein Rollback möglich und wie viele Daten können rekonstruiert werden? |
| Kostenerwartung | Welche Grenzen für Capacity, Compute, Speicherung und Datentransfer sind akzeptabel? |
| Versionsrichtlinie | Was gilt als kompatible Änderung und was benötigt eine neue Produktversion? |
| Stilllegungsrichtlinie | Wie werden inaktive Assets erkannt, abgekündigt, archiviert und entfernt? |

Der Vertrag setzt keine dedizierte Governance-Suite voraus. Ein kleines Team kann ihn in versioniertem Markdown, einem Ticketsystem, einem Katalog, einem Repository und einigen Control-Tabellen pflegen.

Das erforderliche Ergebnis ist kein bestimmtes Tool. Es ist der Nachweis, dass Verantwortlichkeiten und Entscheidungen existieren.

## Von der Entwicklung zur Produktion

Eine Produktionsumgebung sollte nicht der Ort sein, an dem eine ungeprüfte Geschäftsregel erstmals getestet wird.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start10-img1-de.png"
        alt="Kontrollierter Weg von der Entwicklung über Test und Staging bis zur Produktion und zu governten Consumern, unterstützt durch Governance und Observability"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Entwicklung, Validierung und produktive Veröffentlichung sind getrennte Verantwortlichkeiten. Die Umsetzung kann manuell oder automatisiert sein, aber Code, Modelle, Tests, Freigaben, Deployment und Recovery müssen einen kontrollierten Release-Pfad bilden.
    </figcaption>
</figure>

### Dev, Test und Produktion sind Verantwortlichkeiten

Umgebungstrennung benötigt nicht immer drei große, vollständig unabhängige Plattformen.

Eine kleine Umsetzung kann Folgendes verwenden:

```text
Einen Datenbankserver
Getrennte DEV-, TEST- und PROD-Datenbanken oder -Schemas
Ein versioniertes Repository
Einen Scheduler mit getrennten Jobs
Eingeschränkte Produktionsberechtigungen
Eine dokumentierte Release-Checkliste
```

Eine größere Umsetzung kann Folgendes verwenden:

```text
Getrennte Workspaces, Accounts oder Subscriptions
Infrastruktur- und Plattformkonfiguration als Code
Pull Requests und verpflichtende Reviews
Automatisierte Build- und Teststufen
Umgebungsspezifische Parameter und Secrets
Deployment-Freigaben
Validierung nach dem Deployment
Automatisierter Rollback oder kontrollierter Roll-forward
```

Beides kann valide sein.

Die Mindestanforderung lautet, dass Entwicklungsarbeit Produktion nicht unbemerkt verändern kann und dass ein Release identifiziert, geprüft, reproduziert und unterstützt werden kann.

### Ein Release besteht aus mehr als Code

Ein vollständiges Datenprodukt-Release kann enthalten:

- Transformationscode;
- Schema- oder Tabellenänderungen;
- Qualitätsregeln und erwartete Schwellenwerte;
- Pipeline- oder Jobdefinitionen;
- Umgebungsparameter;
- Zugriffsänderungen;
- Änderungen am Semantikmodell;
- notwendige Änderungen an Qlik-, Power-BI- oder Excel-Consumern;
- Dokumentation und Lineage-Metadaten;
- Release Notes;
- Migrationsanweisungen;
- Rollback- oder Roll-forward-Anweisungen;
- Consumer-Kommunikation;
- eine Deprecation-Mitteilung für ersetzte Schnittstellen.

Nur SQL oder ein Notebook auszurollen, während Vertrag, Tests und Consumer unverwaltet bleiben, erzeugt unvollständige Releases.

### Ein einfacher manueller Release-Pfad

Ein kleines Team kann mit einer disziplinierten Checkliste sicher arbeiten:

```flow linear vertical
Entwickler schließt die Änderung in DEV ab
Peer reviewt Code und fachliche Auswirkung
Testdaten werden geladen
Automatisierte und manuelle Prüfungen laufen
Data Owner akzeptiert das geänderte fachliche Verhalten
Release-Paket und Version werden dokumentiert
Backup oder Recovery Point der Produktion wird bestätigt
Änderung wird im freigegebenen Zeitfenster ausgerollt
Post-Deployment-Prüfungen validieren Daten und Consumer
Release wird als erfolgreich markiert oder Recovery wird gestartet
```

Der Prozess ist manuell, aber nicht informell.

### Ein automatisierter CI/CD-Pfad

Automatisierung wird wertvoll, wenn Änderungen häufig sind, viele Mitwirkende parallel arbeiten oder das Recovery-Risiko hoch ist.

Ein erweiterter Pfad kann enthalten:

```flow linear vertical
Branch oder Pull Request
Statische Prüfungen und Kompilierung
Unit- und Datentests
Temporäre oder isolierte Testumgebung
Integrations- und Abstimmungstests
Freigabe-Gate
Deployment nach Test
User Acceptance und Release-Freigabe
Deployment nach Produktion
Smoke Tests und Veröffentlichung
Monitoring mit Rollback oder Roll-forward
```

Automatisierung sollte wiederholbares Risiko reduzieren. Sie darf Verantwortung nicht verbergen.

Eine grüne Pipeline beweist nicht, dass der geänderte KPI fachlich korrekt ist. Technische Validierung und fachliche Abnahme bleiben unterschiedliche Kontrollen.

### Rollback und Roll-forward

Daten-Releases lassen sich schwerer zurücksetzen als Anwendungs-Releases, weil Daten möglicherweise bereits transformiert, veröffentlicht und konsumiert wurden.

Eine Release-Strategie sollte unterscheiden:

| Situation | Bevorzugte Reaktion |
| --- | --- |
| Codefehler vor Veröffentlichung | Release stoppen und das zuletzt veröffentlichte Produkt erhalten |
| Fehlgeschlagenes Quality Gate | Neues Ergebnis quarantänisieren und nach Möglichkeit die vorherige gültige Veröffentlichung verfügbar halten |
| Falsche Transformation bei reproduzierbaren Quelldaten | Code korrigieren und betroffene Partition oder Produktversion neu aufbauen |
| Destruktive Schemaänderung | Backup wiederherstellen, aus Raw rekonstruieren oder die vorherige kompatible Schnittstelle aktivieren |
| Korrekte KPI-Änderung, aber Consumer sind noch nicht bereit | Alte und neue Version für einen definierten Übergangszeitraum parallel betreiben |
| Consumer-spezifischer Fehler | Qlik-App, Power-BI-Modell oder Excel-Vorlage korrigieren, ohne das gemeinsame Produkt unnötig zu verändern |

Das sicherste Design trennt **Build-Status** und **Veröffentlichungsstatus**. Ein abgeschlossener Ladevorgang ist nicht automatisch ein veröffentlichtes Datenprodukt.

## Wer verantwortet was?

Governance scheitert, wenn Ownership als allgemeine Gruppe statt als konkretes Entscheidungsrecht formuliert wird.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start10-img2-de.png"
        alt="RACI-orientierte Rollenübersicht für fachliche Definitionen, Qualität, Zugriff, Pipelines, Monitoring, Dokumentation und Lebenszyklus"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Fachliche und technische Rollen arbeiten zusammen, aber Verantwortlichkeit muss explizit bleiben. Die konkrete RACI-Zuordnung kann je Organisation variieren; jedes kritische Ergebnis benötigt dennoch einen verantwortlichen Owner.
    </figcaption>
</figure>

Das Schaubild ist ein Referenzmuster und kein universelles Organigramm.

Ein praktikables Verantwortungsmodell ist:

| Rolle | Primäre Verantwortung |
| --- | --- |
| Data Owner | Fachliche Bedeutung, Priorität, zulässige Nutzung, Qualitätserwartung und Freigabe wesentlicher Änderungen |
| Data Steward | Definitionen, Glossar, Klassifizierungen, Qualitätsregeln, Issue-Triage und Consumer-Kommunikation |
| Data Architect | Granularität, Grenzen, Integrationsmuster, gemeinsame Modelle, Historie, Verträge und Architekturkonformität |
| Data Engineer | Ingestion, Transformationen, Tests, technische Metadaten und reproduzierbare Builds |
| BI Developer | Consumer-Modell, tool-spezifische Semantik, Visualisierungsverhalten, Performance und User Experience |
| Platform Owner / Data Ops | Umgebungen, Identity-Integration, Zeitplanung, Monitoring, Capacity, Recovery und Betriebsstandards |
| Security- oder Privacy-Rolle | Zugriffsrichtlinie, Kontrollen für sensible Daten, Review-Anforderungen und Nachweise |
| Business Consumer | Korrekte Nutzung, Validierungsfeedback, Adoption und Meldung wesentlicher Fehler |

In einem kleinen Team können mehrere Rollen von derselben Person ausgefüllt werden. Die Entscheidungen entfallen dadurch nicht.

Eine Person kann für ein Produkt Data Architect, Data Engineer und Data Ops sein. Der Sales Director kann Data Owner sein und ein Finance Analyst als Steward agieren. Das Modell bleibt gültig, wenn die Verantwortlichkeiten explizit sind.

### Ownership ist nicht Aufgabenausführung

Der Data Owner muss keinen SQL-Test schreiben. Der Data Engineer wird nicht zum Owner der fachlichen Definition, nur weil er sie implementiert hat.

Eine hilfreiche Unterscheidung lautet:

```text
Accountable
Verantwortet das Ergebnis und akzeptiert die Entscheidung

Responsible
Führt die Arbeit aus

Consulted
Liefert erforderliche Expertise oder Freigabeinput

Informed
Muss das Ergebnis oder die Änderungsinformation erhalten
```

Für ein konkretes Ergebnis sollte möglichst nur eine Rolle accountable sein.

„Data Team“ oder „Business“ ist meist zu unpräzise, um betrieblich nützlich zu sein.

### Ownership muss Abwesenheit und Eskalation einschließen

Ein Betriebsmodell definiert außerdem:

- eine Vertretung oder Supportgruppe;
- den Eskalationspfad;
- die erwartete Reaktionszeit;
- den Entscheider während eines Incidents;
- wer eine Veröffentlichung stoppen darf;
- wer einen Emergency Fix freigeben darf;
- wer Consumer informiert;
- wer das Issue nach der Validierung schließt.

Ownership, die nur während normaler Bürozeiten existiert, reicht für ein Produkt mit strengeren Verfügbarkeitsanforderungen nicht aus.

## Qualität, Lineage und Observability

Nur zu überwachen, ob ein Job `SUCCESS` zurückgibt, erzeugt falsche Sicherheit.

Eine Pipeline kann erfolgreich sein, obwohl sie null Zeilen lädt, die Daten von gestern dupliziert, ein veraltetes Mapping anwendet, eine Quellpartition übersieht oder einen ungültigen KPI veröffentlicht.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start10-img3-de.png"
        alt="Qualität, Lineage und Observability als verbundene Kontrollen über Datenregeln, Transformationen, Produkte, Consumer, Aktualität, Performance, Kosten und Incidents"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Technischer Zustand, fachliche Qualität und transparente Abhängigkeiten müssen gemeinsam beobachtet werden. Der Jobstatus allein beweist nicht, ob ein Datenprodukt aktuell, korrekt, wirtschaftlich und sicher nutzbar ist.
    </figcaption>
</figure>

### Technisches Monitoring

Technische Signale umfassen:

- Pipeline- und Jobstatus;
- Ausführungsdauer;
- Retries und fehlgeschlagene Tasks;
- Quellenkonnektivität;
- Zeilen- und Dateianzahlen;
- Schemaänderungen;
- Datenaktualität;
- Datenvolumen;
- Abfrageperformance;
- Speicherwachstum;
- Compute- oder Capacity-Auslastung;
- Kosten und Ressourcenverbrauch;
- Authentifizierungs- und Autorisierungsfehler.

Diese Signale beantworten, ob das System wie vorgesehen arbeitet.

### Fachliches Monitoring und Datenqualität

Fachliche Signale umfassen:

- Vollständigkeit von Pflichtschlüsseln;
- Eindeutigkeit auf der definierten Granularität;
- gültige Referenz- und Dimensionszuordnungen;
- zulässige Wertebereiche;
- Abstimmung mit Quell-Kontrollsummen;
- KPI-Bewegungen außerhalb erwarteter Bereiche;
- fehlerhafte Datensätze nach Regel und Schweregrad;
- Quality Score nach Datenprodukt;
- Ownership und Behebungsstatus der Regeln;
- SLA-Erfüllung;
- Veröffentlichungsstatus.

Ein Quality Score kann für Priorisierung nützlich sein, darf die zugrunde liegenden Regeln aber nicht verbergen. Ein Score von 98 Prozent ist bedeutungslos, wenn die fehlgeschlagenen zwei Prozent alle umsatzstarken Kunden oder den vollständigen aktuellen Geschäftstag enthalten.

### Lineage

Lineage sollte mindestens folgenden Pfad sichtbar machen:

```text
Quelle
Ingestion
Raw- oder Staging-Objekt
Transformation
Core-Tabelle
Datenprodukt
Semantik- oder Consumer-Modell
Bericht, Anwendung oder Extrakt
```

Eine minimale Umsetzung kann versionierte Abhängigkeitsdokumentation und generierte Metadaten aus SQL, Skripten oder Pipeline-Definitionen verwenden.

Eine größere Umsetzung kann Tabellen- und Spalten-Lineage automatisiert erfassen, mit einem Katalog kombinieren und für Impact-Analysen nutzen.

Das Ziel ist nicht der komplexeste Graph. Das Ziel ist, folgende Fragen zu beantworten:

```text
Was hat diesen Wert erzeugt?
Welche Quelle und Regel haben dazu beigetragen?
Welche Produkte hängen von diesem Objekt ab?
Welche Consumer werden von einer Änderung betroffen?
Wer verantwortet jede Abhängigkeit?
```

### Observability ohne Aktion ist nur Reporting

Jedes kritische Signal benötigt:

- einen erwarteten Bereich oder ein Serviceziel;
- einen Owner;
- einen Schweregrad;
- einen Alert-Pfad;
- ein Runbook;
- eine Reaktionserwartung;
- einen Lösungsnachweis;
- eine Überprüfung wiederkehrender Ursachen.

Ein Dashboard, das drei Wochen rot bleibt, ist kein Betriebsprozess.

### Incident Handling

Eine praktikable Incident-Sequenz lautet:

```flow linear vertical
Erkennen
Schweregrad klassifizieren
Consumer schützen
Betroffene Produkte und Versionen identifizieren
Owner und Supportrollen informieren
Ursache in Quelle, Pipeline, Regel oder Plattform diagnostizieren
Wiederherstellen, korrigieren oder neu veröffentlichen
Fachliches Ergebnis validieren
Lösung kommunizieren
Ursache und Präventionsmaßnahme dokumentieren
```

Das Team sollte unterscheiden zwischen:

- Plattform-Incident;
- Quellsystem-Incident;
- Datenqualitäts-Incident;
- Semantik- oder Reportingfehler;
- Security Incident;
- erwarteter fachlicher Anomalie.

Reaktion und Owner sind nicht immer identisch.

## Konkretes Beispiel: das Sales Data Product betreiben

Angenommen, das governte Produkt bleibt **Sales by Customer**, das in dieser Serie durchgehend verwendet wurde.

Sein Betriebsvertrag kann so aussehen:

| Vertragselement | Beispiel |
| --- | --- |
| Produkt | Sales by Customer |
| Granularität | Eine Zeile je gebuchter Auftragsposition und Business-Datum |
| Data Owner | Head of Sales Controlling |
| Data Steward | Sales Analytics Lead |
| Technischer Owner | Data-Engineering-Team |
| Plattform-Owner | Data Platform Operations |
| Zeitplan | Täglich nach ERP-Abschluss |
| Freshness SLA | Veröffentlichung an Geschäftstagen bis 06:30 Uhr |
| Kritische Quality Gates | Quellabstimmung, gültiger Kunde, gültiges Produkt, gültiger Wechselkurs, eindeutige Auftragsposition |
| Warning-Regeln | Fehlendes optionales Segment, verspätetes beschreibendes Attribut, ungewöhnliche regionale Abweichung |
| Veröffentlichungsobjekt | Governte Sales-Faktentabelle, Kundendimension und unterstützte Consumption Views |
| Consumer | Qlik-Sales-App, Power-BI-Managementmodell und kontrollierter Excel-Extrakt |
| Support | Operations-Triage, Qualitätsprüfung durch Steward und Owner-Entscheidung bei wesentlichen fachlichen Ausnahmen |
| Aufbewahrung | Raw- und Produkthistorie entsprechend der freigegebenen Richtlinie |
| Produktversion | Vertragsversion plus Release-ID |
| Stilllegungsregel | Consumer-Inventar, Ankündigungsfrist, Parallelversion und verifizierte Stilllegung |

### Täglicher Veröffentlichungsablauf

```flow linear vertical
ERP-Abschluss wird bestätigt
Sales-Quelldaten werden aufgenommen
Raw-Kontrollsummen werden gespeichert
Standardisierung und Integration laufen
Sales- und Kundenmodelle werden aufgebaut
Kritische Qualitäts- und Abstimmungsprüfungen laufen
Fehlerhafte Datensätze und Nachweise werden persistiert
Veröffentlichungsentscheidung wird ausgewertet
Freigegebene Version wird für Consumer bereitgestellt
Aktualität, Nutzung, Qualität und Kosten werden überwacht
```

Der Veröffentlichungsschritt sollte Folgendes protokollieren:

```text
product_name
product_version
release_id
business_date
build_started_at
build_completed_at
published_at
source_control_total
product_control_total
critical_rule_status
warning_count
record_count
publication_status
approved_by
```

Das kann als Control-Tabelle, Katalogeintrag, Release-Datensatz oder gleichwertige Plattformmetadaten umgesetzt werden.

### Was passiert, wenn das Produkt fehlschlägt?

#### Fehlender Wechselkurs

Fehlt für fünf Sales-Positionen der erforderliche EUR-Wechselkurs, darf das Team nicht stillschweigend null veröffentlichen oder einen beliebigen früheren Wert wiederverwenden.

Mögliche Richtlinie:

```text
Kritische Regel schlägt fehl
Betroffene Datensätze werden persistiert
Produktveröffentlichung wird blockiert
Data Steward validiert das Referenzdatenproblem
Finance oder der Referenzdaten-Owner liefert den freigegebenen Kurs
Betroffene Partition wird neu aufgebaut
Abstimmung und Quality Gates laufen erneut
Produkt wird mit dokumentierter Verspätung veröffentlicht
Consumer erhalten einen SLA-Hinweis
```

#### Optionales Kundensegment fehlt

Ist das Segment nur beschreibend und für den zentralen Sales-KPI nicht erforderlich, kann das Produkt mit Warnung veröffentlicht werden:

```text
Produkt wird pünktlich veröffentlicht
Betroffene Datensätze erhalten einen expliziten Qualitätsstatus
Steward verantwortet die Behebung
Consumer-Dokumentation zeigt die Einschränkung
Trend und offenes Issue bleiben sichtbar
```

Der Schweregrad richtet sich nach der fachlichen Auswirkung und nicht nur nach technischer Bequemlichkeit.

#### Pipeline schlägt nach einer gültigen vorherigen Veröffentlichung fehl

Bleibt die letzte erfolgreiche Version für das vorherige Business-Datum gültig, können Consumer sie mit einer Freshness-Warnung weiterverwenden. Sie durch eine unvollständige aktuelle Version zu ersetzen, würde das Vertrauen verringern.

Der Produktvertrag entscheidet, ob veraltet-aber-gültig besser ist als aktuell-aber-unvollständig.

## Einen KPI versionieren, ohne jeden Consumer zu brechen

Angenommen, die vorhandene Definition lautet:

```text
Nettoumsatz v1
Bruttobetrag
abzüglich freigegebenem Positionsrabatt
ohne stornierte Positionen
Umrechnung nach EUR anhand des Business-Datums
```

Finance genehmigt anschließend eine neue Definition:

```text
Nettoumsatz v2
Nettoumsatz v1
abzüglich zugeordneter nachträglicher Rückvergütung
```

Die bestehende Spalte über Nacht zu ändern, würde jeden historischen Vergleich unbemerkt verändern.

Eine kontrollierte Änderung kann Folgendes verwenden:

```text
sales_net_revenue_v1
sales_net_revenue_v2
net_revenue_definition_version
valid_from
valid_to
release_id
```

Der Übergang kann so erfolgen:

1. v2 definieren und freigeben;
2. v2 parallel zu v1 aufbauen;
3. beide Versionen abstimmen und die erwartete Differenz dokumentieren;
4. v2 an Test-Consumer veröffentlichen;
5. Abhängigkeiten in Qlik, Power BI und Excel aktualisieren;
6. beide Versionen für einen vereinbarten Zeitraum parallel betreiben;
7. Deprecation-Datum für v1 kommunizieren;
8. unterstützte Standardverträge auf v2 umstellen;
9. prüfen, dass kein aktiver Consumer noch v1 verwendet;
10. Definition archivieren und alte Schnittstelle stilllegen.

### Auswirkungen auf Qlik, Power BI und Excel

| Consumer | Kontrollierte Änderung |
| --- | --- |
| Qlik | v2-Feld oder governte View ergänzen, Master Measure aktualisieren, Set Analysis validieren und v1 während des Übergangs erhalten |
| Power BI | Freigegebenes Measure und Modellmetadaten ergänzen oder aktualisieren, Filterkontext validieren, Release Notes veröffentlichen und v1 bei benötigter Kompatibilität erhalten |
| Excel | Zertifizierte View, Verbindung zum Semantikmodell oder Power-Query-Vertrag aktualisieren; Ersatzvorlage bereitstellen, wenn Formeln von der alten Spalte abhängen |
| API oder Anwendung | Versioniertes Feld oder Endpoint einführen und den vorherigen Vertrag bis zum angekündigten Stilllegungsdatum erhalten |

Die gemeinsame fachliche Definition darf nicht in jedem Consumer unabhängig neu implementiert werden.

Consumer-Modelle übersetzen das governte Produkt in tool-spezifisches Verhalten. Sie verantworten nicht die autoritative Zuordnung der Rückvergütung.

## Eine View oder ein QVD abkündigen

Eine View oder ein QVD sollte nicht unbegrenzt in Produktion bleiben, nur weil seine Entfernung riskant ist.

Ein kontrollierter Stilllegungsprozess lautet:

```flow linear vertical
Asset und Owner identifizieren
Aktive Consumer messen oder inventarisieren
Vertragliche und regulatorische Aufbewahrung klassifizieren
Ersatz und Stilllegungsdatum ankündigen
Mapping und Migrationshinweise bereitstellen
Alten und neuen Vertrag parallel betreiben
Bei weiterer Nutzung warnen
Migration mit Consumer-Ownern bestätigen
Neuen Zugriff auf altes Asset entziehen
Erforderlichen Code und Metadaten archivieren
Zeitpläne, Berechtigungen und Speicherung entfernen
Finalen Stilllegungsnachweis dokumentieren
```

Bei einem QVD müssen außerdem geprüft werden:

- Generator-Anwendungen;
- Reload-Ketten;
- Binary- oder Dateiabhängigkeiten;
- nachgelagerte Qlik-Anwendungen;
- extern bereitgestellte Kopien;
- Namenskonventionen, die die tatsächliche Nutzung verbergen;
- Aufbewahrungs- und Backupkopien.

Nur die sichtbare Datei zu löschen, während ein Generator sie erneut erstellt, ist keine Stilllegung.

## Der Lebenszyklus des Datenprodukts

Ein Datenprodukt sollte einen definierten Weg von der Idee bis zur Stilllegung besitzen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start10-img4-de.png"
        alt="Lebenszyklus eines Datenprodukts von Entdeckung und Design über Aufbau, Veröffentlichung, Betrieb und Verbesserung bis zur kontrollierten Stilllegung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Governance ist in jeder Lebenszyklusphase vorhanden. Ownership, Qualität, Sicherheit, Lineage, Dokumentation, Observability und Kostensteuerung sind keine Aktivitäten, die erst nach dem Release ergänzt werden.
    </figcaption>
</figure>

### Idee und Discovery

Zu klären sind:

- fachliche Entscheidung;
- erwarteter Nutzen;
- Consumer;
- Datenquellen;
- Granularität;
- Ownership;
- Aktualität;
- Qualität;
- Sicherheit;
- Aufbewahrung;
- erwarteter Lebenszyklus.

Eine Idee ohne plausiblen Owner oder Consumer ist nicht bereit für Plattforminvestition.

### Design

Zu definieren sind:

- logisches Modell;
- Schlüssel und Historie;
- Produktvertrag;
- Zugriffsmodell;
- Teststrategie;
- Umgebungspfad;
- Release-Methode;
- Monitoring;
- Recovery;
- Kostenerwartung;
- Stilllegungskriterien.

### Build und Test

Der einfachste vollständige vertikale Slice umfasst:

- Ingestion;
- Transformation;
- Produktmodell;
- Qualitätsnachweise;
- Metadaten;
- Consumer-Vertrag;
- Deployment;
- Monitoring.

### Release

Ein Release benötigt:

- freigegebene Version;
- bestandene Gates;
- vorbereiteten Zugriff;
- Dokumentation;
- Release Notes;
- Consumer-Kommunikation;
- Recovery-Plan;
- Veröffentlichungsnachweis.

### Betrieb

Zu überwachen sind:

- SLA und Aktualität;
- Fehler;
- Qualität;
- Nutzung;
- Performance;
- Sicherheit;
- Capacity und Kosten;
- Incidents;
- ungelöste Risiken.

### Änderung

Änderungen können sein:

```text
Kompatible Erweiterung
Technische Optimierung
Quellenmigration
Änderung einer Qualitätsregel
Sicherheitsänderung
Semantische Änderung
Breaking Contract Change
Consumer-spezifische Änderung
```

Der Änderungstyp bestimmt Test-, Freigabe- und Versionierungspfad.

### Deprecation und Stilllegung

Ein Produkt wird zum Stilllegungskandidaten, wenn:

- kein aktiver Consumer verbleibt;
- ein Ersatz vollständig übernommen wurde;
- sein fachlicher Zweck entfallen ist;
- Quelle oder Vertrag nicht mehr existieren;
- Kosten im Verhältnis zum Nutzen zu hoch sind;
- Risiko oder Compliance die Entfernung verlangen;
- Ownership nicht nachhaltig sichergestellt werden kann.

Stilllegung ist ein geplanter Lebenszyklusstatus und keine ungeplante Löschung.

## Den Produktvertrag versionieren

Ein einfaches Versionsmodell kann unterscheiden:

| Änderungstyp | Beispiel | Empfohlene Behandlung |
| --- | --- | --- |
| Patch | Performanceoptimierung bei unverändertem Ergebnis | Gleiche Vertragsversion, neue Release-ID |
| Minor | Neues optionales Feld oder rückwärtskompatibles Aggregat | Kompatible Produktversion erhöhen |
| Major | Geänderte Granularität, KPI-Bedeutung, Schlüssel, Pflichtfeld oder Entfernung | Neue Hauptversion und gesteuerte Consumer-Migration |
| Emergency Correction | Wesentlicher Fehler in einem veröffentlichten Ergebnis | Incident-Datensatz, korrigiertes Release und expliziter Consumer-Hinweis |

Die genaue Nummerierungskonvention ist weniger wichtig als konsistentes Verhalten.

Eine Version muss Folgendes identifizieren:

```text
Fachliche Definition
Schema oder Schnittstelle
Qualitätsrichtlinie
Release-Artefakt
Gültigkeitsdatum
Consumer-Supportstatus
Deprecation-Status
```

Ein Git-Tag allein sagt einem fachlichen Consumer nicht, welche KPI-Definition gültig war. Eine Katalogbeschreibung allein reproduziert die ausgerollte Implementierung nicht. Beide Perspektiven werden benötigt.

## Technologieoffene Umsetzungsoptionen

Dieselben Betriebsprinzipien können mit unterschiedlichen technischen Mitteln umgesetzt werden.

| Kontext | Minimale Umsetzung | Mögliche Erweiterung |
| --- | --- | --- |
| Klassisches SQL- oder On-Premises-Warehouse | Getrennte Schemas oder Datenbanken, Repository, Scheduler, SQL-Tests, Control-Tabellen, Logs und Checkliste | Automatisiertes Datenbank-Deployment, generierte Lineage, zentrales Monitoring, Infrastrukturautomatisierung |
| Microsoft Fabric | Getrennte Workspaces oder kontrollierte Stufen, versionierte Items, Monitoring der Pipeline-Läufe, Berechtigungen und Release-Checkliste | Git-Integration und Deployment Pipelines für unterstützte Items, Workspace Monitoring, automatisierte Validierung und Capacity-Analyse |
| Snowflake | Getrennte Datenbanken, Schemas und Rollen, versioniertes SQL, Task-Historie, Qualitätstabellen und Release-Datensätze | Automatisierte Migrationen, Task Graphs, Alerts, Resource Monitors, Nutzungsanalyse und Katalogintegration |
| Databricks | Getrennte Catalogs, Schemas oder Workspaces, versionierte Notebooks beziehungsweise Code, Jobs, Tests und Veröffentlichungstabellen | Declarative Automation Bundles, automatisiertes CI/CD, System Tables, Unity-Catalog-Lineage und zentrale Observability |
| dbt mit unterstütztem Warehouse | Modelle, Tests, Dokumentation und versioniertes Projekt | Pull-Request-CI, zustandsbasierte Auswahl, Deployment Jobs und generierte Metadaten |
| Qlik | Kontrollierte Entwicklungs- und Produktions-Apps, Reload Tasks, governte QVD- oder View-Verträge und Reload-Monitoring | Automatisierte Promotion, API-basierte Prüfungen, Abhängigkeitsinventar und zentrale Betriebsdashboards |
| Power BI | Kontrollierte Semantikmodelle und Berichte, dokumentiertes Release und Workspace-Berechtigungen | Deployment Pipeline oder automatisierte Promotion, sofern verfügbar, Modellvalidierung und Nutzungsmonitoring |
| Excel | Zertifizierte Vorlage, governte Verbindung und kontrollierte Verteilung | Automatisierte Refresh-Validierung, Workbook-Inventar und Migration auf stabile Semantik- oder SQL-Verträge |

Keine Zeile dieser Tabelle ist ein Pflicht-Stack.

Ein kleines Team kann SQL-Jobs, Git, Testabfragen, eine Veröffentlichungstabelle und ein Betriebsdashboard kombinieren. Eine große Organisation kann zentrales CI/CD, Katalog, Lineage, Alert-Routing, Kostenkontrollen und Incident-Automatisierung benötigen.

Das Betriebsmodell sollte wachsen, weil Risiko und Skalierung es erfordern und nicht, weil jede Plattformfunktion existiert.

## Minimum-Betrieb und erweiterter Betrieb

Betriebliche Reife sollte schrittweise wachsen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start10-img5-de.png"
        alt="Vergleich von Minimum-Betrieb und erweitertem Betrieb für Monitoring, Datenqualität, Lineage, Zuverlässigkeit, Sicherheit und Incident Management"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Minimum-Betrieb kann diszipliniert, zuverlässig und für ein kleines Team angemessen sein. Erweiterte Automatisierung sollte dort ergänzt werden, wo Release-Frequenz, Skalierung, Regulierung, Abhängigkeiten oder Recovery-Risiko sie rechtfertigen.
    </figcaption>
</figure>

### Minimal tragfähiger Betrieb

Ein kleines Team sollte mindestens besitzen:

```text
Benannter Owner und Vertretung
Getrennte Entwicklung und Produktion
Versionierte Transformationen
Dokumentierte Release-Checkliste
Kritische Datenqualitäts-Gates
Persistente Lade- und Veröffentlichungsnachweise
Freshness- und Fehler-Alerts
Consumer-Inventar
Incident-Kontakt und Runbook
Backup- oder Rekonstruktionspfad
Zugriffsreview
Deprecation- und Stilllegungsprozess
```

Das ist ohne dediziertes Observability-Produkt oder unternehmensweite CI/CD-Plattform möglich.

### Erweiterter Betrieb

Erweiterte Fähigkeiten können umfassen:

- automatisierte Bereitstellung von Umgebungen;
- Pull-Request-Validierung;
- temporäre Testumgebungen;
- automatisierte Data-Contract-Prüfungen;
- Spalten-Lineage;
- Anomalieerkennung;
- zentrales Alert-Routing;
- Service-Level-Objective-Reporting;
- automatisierter Rollback oder Self-Healing;
- Policy-as-Code;
- kontinuierliche Zugriffsprüfung;
- Capacity Forecasting;
- Chargeback oder Showback;
- automatisierte Stilllegungskandidaten anhand der Nutzung;
- plattformübergreifende Incident-Korrelation.

Jede Fähigkeit bringt eigene Kosten, Konfiguration und Supportanforderungen mit.

### Wann schafft mehr Automatisierung Mehrwert?

| Signal | Wahrscheinliche Konsequenz |
| --- | --- |
| Viele Mitwirkende ändern gemeinsame Modelle | Stärkeres Source Control, Review und automatisierte Test-Gates |
| Häufige Produktions-Releases | Wiederholbare Deployment-Automatisierung und Smoke Tests |
| Viele nachgelagerte Consumer | Bessere Lineage, Verträge und Deprecation-Steuerung |
| Strenges Verfügbarkeitsziel | On-call-Ownership, Recovery-Automatisierung und getestete Continuity |
| Regulierte oder sensible Daten | Stärkere Nachweise, Funktionstrennung, Zugriffsreviews und Auditierbarkeit |
| Variable Cloud-Kosten | Capacity-Dashboards, Budgets, Workload-Steuerung und Optimierung |
| Wiederkehrende Daten-Incidents | Ursachenanalyse, Observability und präventive Kontrollen |
| Mehrere Plattformen | Gemeinsame Produktverträge und plattformübergreifendes Monitoring |
| Kleiner stabiler Workload mit wenigen Änderungen | Prozess einfach, dokumentiert und zuverlässig halten |

Das Ziel ist nicht maximale Reife in jeder Kategorie. Das Ziel ist ausreichende Kontrolle für das tatsächliche Risiko.

## Typische Anti-Patterns

### Eine Umgebung für alles

Entwickler testen direkt auf Produktionsobjekten und deployen durch Überschreiben. Das Team kann den vorherigen Zustand nicht reproduzieren und nicht bestimmen, welche Änderung den Fehler verursacht hat.

### Jobstatus als einziges Qualitätssignal

Die Pipeline ist erfolgreich, aber das aktuelle Business-Datum fehlt. Technischer Abschluss wird mit fachlicher Korrektheit des Produkts verwechselt.

### Das Dashboard ist die Kontrolle

Ein rotes Dashboard weist keine Ownership zu, startet keine Aktion und erhält keinen Nachweis. Monitoring ist sichtbar, aber nichts ändert sich.

### Neustarten, bis alles grün ist

Der fehlgeschlagene Job wird wiederholt gestartet, ohne zu verstehen, ob bereits Teildaten geschrieben wurden. Der abschließende grüne Status verbirgt doppelte oder inkonsistente Ergebnisse.

### Ownership durch Komitee

„Business und IT verantworten es gemeinsam“ lässt niemanden autorisiert zurück, eine Qualitätsausnahme zu akzeptieren, ein Release zu verschieben oder eine Schnittstelle stillzulegen.

### Validierung erst in Produktion

Das vollständige Datenvolumen, Berechtigungen und Abhängigkeiten werden erstmals nach dem Deployment geprüft. Test und Release werden zum selben Ereignis.

### Statische Lineage als Dokumentationstheater

Während des Projekts wird ein Diagramm erstellt und anschließend nie aktualisiert. Bei Modelländerungen kann es keine Impact-Fragen beantworten.

### Dauerhafter Parallelbetrieb

Alte und neue Views, QVDs, Modelle und Berichte laufen unbegrenzt parallel. Consumer wählen Versionen unabhängig und Abstimmung wird zur Daueraufgabe.

### KPI-Änderung ohne Vertragsversion

Eine Spalte behält denselben Namen, obwohl sich ihre Bedeutung ändert. Historische Vergleiche und Consumer-Verhalten ändern sich unbemerkt.

### Tool-spezifische Korrekturen werden gemeinsame Logik

Ein Fehler wird in einem Qlik-Skript, einem DAX-Measure oder einer Excel-Formel korrigiert, während andere Consumer inkonsistent bleiben.

### Automatisierung vor dem Betriebsmodell

Das Team baut eine komplexe CI/CD-Strecke ohne definierte Owner, Quality Gates, Release-Entscheidungen oder Supportverantwortung. Der Prozess wird zu automatisierter Unklarheit.

### Keine Stilllegung

Ungenutzte Pipelines, Tabellen, Views, QVDs, Semantikmodelle und Berichte verbrauchen weiterhin Geld, Zugriffsrechte und operative Aufmerksamkeit.

## Entscheidungshilfe

Verwende folgende Fragen für jedes Datenprodukt.

| Frage | Minimal akzeptable Antwort |
| --- | --- |
| Wer verantwortet das fachliche Ergebnis? | Benannte accountable Rolle |
| Wer betreibt den technischen Service? | Benanntes Team und Supportpfad |
| Wie ist Produktion getrennt? | Explizite Grenze und eingeschränkter Änderungspfad |
| Was muss vor Veröffentlichung bestehen? | Dokumentierte kritische Qualitäts- und Abstimmungs-Gates |
| Wie wird ein Release identifiziert? | Produktversion und Release-ID |
| Wie wird der vorherige gültige Zustand geschützt? | Backup, Rekonstruktion, Rollback oder kontrollierter Roll-forward |
| Wie wird Aktualität gemessen? | Zeitstempel, Ziel und Alert-Schwelle |
| Wie wird Auswirkung ermittelt? | Abhängigkeits- oder Consumer-Inventar mit Ownership |
| Wie werden Consumer informiert? | Release- und Incident-Kommunikationskanal |
| Wie werden Kosten beobachtet? | Regelmäßiges Kostenreview je Produkt oder Workload |
| Wie wird Zugriff geprüft? | Owner, Rhythmus und Nachweis |
| Wie wird eine alte Schnittstelle entfernt? | Deprecation-Datum, Migrationspfad und verifizierte Stilllegung |
| Kann das Team die gewählte Automatisierung unterstützen? | Kompetenzen, Ownership und Betriebsbudget |
| Ist ein weiteres Tool notwendig? | Ein konkretes Risiko-, Skalierungs- oder Effizienzproblem wird gelöst |

Kann das Team diese Fragen nicht beantworten, löst ein weiteres Monitoring- oder Governance-Produkt die grundlegende Betriebslücke nicht.

## Wichtigste Empfehlungen

1. Behandle jedes produktive Datenprodukt als betriebenen Service mit Owner, Consumern und Serviceerwartungen.
2. Trenne Entwicklung, Validierung und Produktion auch dann als Verantwortlichkeiten, wenn sie Infrastruktur teilen.
3. Versioniere Transformationscode, Produktverträge, Tests und Release-Nachweise gemeinsam.
4. Setze einen erfolgreichen Job nicht mit einem erfolgreich veröffentlichten Datenprodukt gleich.
5. Persistiere Qualitätsergebnisse, Abstimmungsnachweise und Veröffentlichungsstatus.
6. Definiere, welche Qualitätsregeln die Veröffentlichung blockieren und welche Warnungen erzeugen.
7. Gib jedem kritischen Ergebnis einen accountable Owner und einen definierten Eskalationspfad.
8. Überwache technischen Zustand, fachliche Qualität, Freshness, Nutzung und Kosten gemeinsam.
9. Richte Lineage auf Impact-Analyse und Nachvollziehbarkeit statt auf dekorative Diagramme aus.
10. Schütze die letzte gültige Veröffentlichung, wenn ein neuer Build fehlschlägt.
11. Nutze Rollback, wo er sicher ist, und Roll-forward, wo Datenrekonstruktion zuverlässiger ist.
12. Führe KPI- und Schemaänderungen über explizite Produktversionen ein.
13. Betreibe Breaking Versions nur für einen definierten Migrationszeitraum parallel.
14. Kommuniziere Release Notes, Incidents, Deprecations und Stilllegungsdaten an Consumer-Owner.
15. Halte gemeinsame Geschäftsregeln außerhalb einzelner Qlik-, Power-BI- und Excel-Artefakte.
16. Erlaube Consumer-spezifische Logik nur dort, wo sie tatsächlich zur Consumer Experience gehört.
17. Beginne mit Checklisten, Control-Tabellen, Tests und klarer Ownership, bevor erweiterte Betriebstechnologie beschafft wird.
18. Ergänze CI/CD, automatisierte Lineage und Observability, wenn Skalierung, Release-Frequenz oder Risiko sie rechtfertigen.
19. Prüfe Zugriffe, Kosten, Capacity, ungelöste Incidents und Nutzung regelmäßig.
20. Lege ungenutzte Pipelines, Views, QVDs, Modelle und Berichte bewusst still.
21. Teste Recovery und Continuity, statt anzunehmen, dass Backups oder Raw-Daten ausreichen.
22. Bewerte das Betriebsmodell neu, wenn das Produkt kritischer wird, weitere Consumer erhält oder sein Service Level verändert wird.

## Abschluss der Serie

Diese Serie begann mit [Bevor die erste Tabelle entsteht](/playbooks/before-building-the-first-table): Ausgangspunkt sind fachliche Entscheidung, KPI, Granularität, Quellen, Qualität und Ownership.

Anschließend wurden Verantwortlichkeiten präziser als Bronze, Silver und Gold getrennt, die einfachste tragfähige Architektur ausgewählt, Greenfield- und Brownfield-Umsetzungen behandelt, gemeinsame Geschäftslogik aus BI-Anwendungen herausgeführt, ein governtes Produkt für mehrere Consumer definiert, Transformationsoptionen verglichen und eine Architektur auf mehrere Plattformen abgebildet.

Der letzte Schritt ist der kontinuierliche Betrieb:

```flow linear vertical
Fachliches Ergebnis definieren
Explizite Verantwortlichkeiten gestalten
Einfachstes vollständiges Produkt bauen
Über kontrollierte Verträge veröffentlichen
Qualität, Sicherheit, Lineage, Kosten und Incidents betreiben
Über versionierte Releases verändern
Stilllegen, wenn Nutzen oder Vertrag enden
```

Ein modernes Warehouse wird nicht durch die Anzahl der Tools im Stack definiert.

Es wird dadurch definiert, ob die Organisation die Datenprodukte, von denen Entscheidungen abhängen, erklären, reproduzieren, vertrauen, verändern und schließlich stilllegen kann.
