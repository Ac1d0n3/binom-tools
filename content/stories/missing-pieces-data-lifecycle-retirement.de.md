---
title: "The Missing Pieces – Teil 6: Datenlebenszyklus & Stilllegung"
description: "Warum das Erstellen und Aufbewahren von Daten oft leichter ist als die Entscheidung, wann sie archiviert, stillgelegt oder gelöscht werden sollten – und wie Lifecycle Governance Zweck, Ownership, Abhängigkeiten, Aufbewahrung, Nachweise und kontinuierliche Überprüfung verbinden kann."
author: Thomas Lindackers
category: Data Governance
tags:
  - the-missing-pieces
  - datenlebenszyklus
  - data-lifecycle
  - aufbewahrung
  - data-retention
  - stilllegung
  - datenloeschung
  - records-management
  - datenminimierung
  - data-governance
order: -1
hero: images/playbooks/mp-life-hero.png
publishedAt: 2026-07-13
series: missing-pieces
seriesPart: 6
seriesTitle: The Missing Pieces
---

## Daten zu erstellen ist einfach. Sie stillzulegen ist eine Entscheidung.

Moderne Datenplattformen machen es immer einfacher, Daten zu ingestieren, zu kopieren, zu transformieren, dauerhaft zu speichern und zu teilen.

Ein einzelner operativer Datensatz kann später vorkommen in:

- einer Quellanwendung,
- einer Landing- oder RAW-Schicht,
- einer standardisierten oder konformierten Schicht,
- einem oder mehreren Data Marts,
- einem semantischen Modell,
- Reports und Dashboards,
- Extrakten und Anwendungscaches,
- Excel-Dateien,
- Entwicklungs- und Testumgebungen,
- Backups, Snapshots und Replikaten.

Viele dieser Repräsentationen sind sinnvoll.

Sie können Historisierung, Reproduzierbarkeit, Performance, Isolation, Wiederherstellung, regulatorische Anforderungen und unterschiedliche fachliche Zwecke unterstützen.

Die offene Governance-Frage lautet deshalb nicht:

> *Warum haben wir Kopien?*

Sondern:

> **Bleiben Zweck, Ownership, Klassifizierung und Lifecycle-Absicht mit jeder relevanten Repräsentation der Daten verbunden?**

Günstiger Speicher beseitigt nicht die Notwendigkeit zu verstehen, warum Daten aufbewahrt werden.

Automatisierung entscheidet nicht, ob ein Datensatz weiterhin Wert erzeugt.

Eine Aufbewahrungsfrist beweist nicht, dass alle Downstream-Kopien abgedeckt sind.

Ein Löschbefehl bedeutet nicht zwangsläufig, dass jede wiederherstellbare oder abgeleitete Repräsentation verschwunden ist.

Lifecycle Governance beginnt, wenn die Erzeugung von Daten als Beginn einer Verantwortung betrachtet wird – nicht als Ende einer Bereitstellungsaufgabe.

## Lifecycle Governance ist mehr als Aufbewahrung

Aufbewahrung ist nur ein Teil des Lebenszyklus.

Ein vollständiges Lifecycle-Modell berücksichtigt auch, warum Daten entstehen, wie sie geschützt werden, wer sie nutzt, wie sich ihr Wert verändert und was geschehen soll, wenn der ursprüngliche Zweck endet.

| Lifecycle-Phase | Zentrale Governance-Fragen |
| --- | --- |
| Erstellen / Erfassen | Warum werden die Daten benötigt? Ist die Erhebung verhältnismäßig? Wer ist verantwortlich? Wie werden sie klassifiziert? |
| Speichern / Verwalten | Wo werden sie gespeichert? Wie bleiben Qualität und Konsistenz erhalten? Welche Kontrollen schützen sie? Welche Kopien existieren? |
| Nutzen / Teilen | Wer darf die Daten für welchen Zweck verwenden? Wie wird Nutzung überwacht? Welche neuen Produkte oder Ableitungen entstehen? |
| Archivieren / Aufbewahren | Welcher rechtliche, regulatorische, vertragliche oder geschäftliche Grund erfordert Aufbewahrung? Wie lange? In welcher Speicherklasse? |
| Stilllegen / Löschen | Werden die Daten noch benötigt? Sind aktive Abhängigkeiten geklärt? Können sie gelöscht, anonymisiert oder aus der Nutzung genommen werden? |
| Prüfen / Verbessern | Sind Richtlinien noch angemessen? Altern Ausnahmen? Wirken Lifecycle-Kontrollen wie beabsichtigt? |

Der Lebenszyklus ist nicht nur ein Speicherthema.

Er verbindet:

- fachlichen Zweck,
- Data Ownership,
- Datenschutz und rechtliche Verpflichtungen,
- Architektur und Lineage,
- Sicherheitskontrollen,
- Datenqualität,
- Records Management,
- Kosten und Nachhaltigkeit,
- operative Resilienz,
- Nachweise und Auditierbarkeit.

Eine technisch korrekte Speicherregel kann trotzdem unvollständig sein, wenn sie den fachlichen Zweck oder Downstream-Abhängigkeiten nicht berücksichtigt.

Ein gut dokumentierter Aufbewahrungsplan kann trotzdem unwirksam sein, wenn er nicht in Plattformverhalten übersetzt wird.

> **Lifecycle Governance verbindet den Grund, warum Daten existieren, mit der Frage, wie lange ihre Aufbewahrung nützlich, erforderlich und begründbar bleibt.**

## Der Lebenszyklus ist selten eine einfache gerade Linie

Daten bewegen sich nicht nur einmal von der Erzeugung bis zur Löschung.

Sie werden kopiert, transformiert, aggregiert, angereichert und wiederverwendet.

Ein Kundendatensatz kann sich entwickeln zu:

```flow linear vertical
Quelldatensatz
RAW-Event
konformierte Kundentabelle
Sales Mart
Kundenkennzahl
Dashboard
Excel-Export
Machine-Learning-Feature
archivierter Snapshot
```

Der Lebenszyklus jeder Repräsentation kann unterschiedlich sein.

Das RAW-Event kann für Audit und Replay benötigt werden.

Die konformierte Tabelle kann aktiv von mehreren Data Products genutzt werden.

Das Dashboard kann durch eine neue Version ersetzt worden sein.

Der Excel-Export kann zu einer unkontrollierten lokalen Kopie geworden sein.

Die aggregierte Kennzahl enthält möglicherweise keine personenbezogenen Daten mehr.

Das Modell-Feature kann eine eigene Aufbewahrungsdauer für Reproduzierbarkeit benötigen.

Ein einzelner Retention-Wert an der Quelle beantwortet deshalb nicht automatisch jede Downstream-Frage.

Das stärkere Modell verbindet Lifecycle-Entscheidungen mit:

- Datentyp,
- Zweck,
- Sensitivität,
- rechtlicher oder vertraglicher Verpflichtung,
- geschäftlichem Wert,
- Nutzung,
- Lineage,
- Abhängigkeit,
- Umgebung,
- Speichertechnologie,
- Wiederherstellungsanforderungen,
- Ownership.

## Aufbewahrung, Archivierung, Stilllegung und Löschung sind unterschiedliche Entscheidungen

Diese Begriffe werden häufig gemeinsam verwendet, beschreiben aber unterschiedliche Maßnahmen.

| Begriff | Bedeutung | Typisches Ergebnis |
| --- | --- | --- |
| Aufbewahrung / Retention | Daten für einen definierten Zeitraum oder bis zu einem Ereignis behalten | Daten bleiben unter kontrollierten Bedingungen verfügbar |
| Archivierung | Daten aus der aktiven Nutzung verschieben und für einen begründeten zukünftigen Bedarf erhalten | kostengünstigere oder stärker eingeschränkte Speicherung |
| Legal Hold | normale Löschung aussetzen, weil Beweise oder Unterlagen erhalten bleiben müssen | Aufbewahrung läuft weiter, bis der Hold aufgehoben wird |
| Stilllegung / Retirement | aktive Unterstützung oder Nutzung eines Datenassets, Modells, Reports oder Interfaces beenden | Konsumenten migrieren, Abhängigkeiten werden entfernt, das Asset wird deprecated |
| Löschung | Daten gemäß Richtlinie aus einer aktiven oder aufbewahrten Umgebung entfernen | Daten werden innerhalb des definierten technischen Scopes gelöscht oder unzugänglich gemacht |
| Anonymisierung | die Möglichkeit zur Identifikation von Personen unumkehrbar entfernen | Daten können für einen anderen begründeten Zweck nutzbar bleiben |
| Decommissioning | den technischen Service, die Pipeline, den Speicher oder die Anwendung hinter dem Asset entfernen | Infrastruktur und operative Verantwortlichkeiten werden geschlossen |

Ein Report kann stillgelegt werden, ohne seine zugrunde liegenden Daten zu löschen.

Eine Tabelle kann gelöscht werden, während Backup-Kopien einem anderen Lifecycle unterliegen.

Ein Datensatz kann archiviert sein und trotzdem Zugriffskontrollen und Ownership benötigen.

Ein Quellsystem kann abgeschaltet werden, während historische Unterlagen weiter aufbewahrt werden.

Die Unterscheidung ist wichtig, weil unterschiedliche Personen für die Entscheidungen verantwortlich sein können.

## Die Entscheidung folgt Zweck, Verpflichtung, Wert und Risiko

Es gibt keine universelle Aufbewahrungsfrist für alle Daten.

Angemessene Fristen hängen unter anderem ab von:

- anwendbarem Recht und Regulierung,
- vertraglichen Verpflichtungen,
- gesetzlichen oder finanziellen Aufbewahrungspflichten,
- Rechtsstreitigkeiten und Legal Holds,
- Anforderungen an operative Wiederherstellung,
- Produkt- und Kundenverpflichtungen,
- Forschungs- oder historischem Wert,
- Sicherheits- und Datenschutzrisiko,
- fachlichem Zweck,
- Datensensitivität,
- erwarteter Wiederverwendung,
- technischen Abhängigkeiten,
- Kosten und Umweltwirkung.

Für personenbezogene Daten sind Zweckbindung, Datenminimierung und Speicherbegrenzung besonders relevant.

Daten sollten nicht unbegrenzt aufbewahrt werden, nur weil sie irgendwann nützlich sein könnten.

Gleichzeitig kann eine zu frühe Löschung rechtliche, operative oder beweisbezogene Risiken erzeugen.

Das Ziel ist nicht maximale Löschung.

Das Ziel ist begründete Aufbewahrung.

> **Die richtigen Daten – für die richtige Zeit – aus einem klaren Grund aufbewahren und die Entscheidung erklären können.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-life-img1-de.png"
        alt="Modell für Datenlebenszyklus und Stilllegung mit Erstellen, Speichern, Nutzen, Archivieren, Löschen und kontinuierlicher Überprüfung sowie Governance-Grundsätzen, Risiken und Ergebnissen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Lifecycle Governance beginnt bei der Erstellung und bleibt über Nutzung, Aufbewahrung, Stilllegung, Löschung und Review aktiv.
    </figcaption>
</figure>

## Eine Richtlinie erreicht selten automatisch jede Kopie

Nehmen wir Kundenkontaktdaten.

Die ursprüngliche Richtlinie kann definieren:

- freigegebene Zwecke,
- Sensitivität,
- Zugriffsbeschränkungen,
- Aufbewahrungsdauer,
- Löschanforderungen.

Dieselben Informationen können später jedoch vorkommen in:

- CRM-Datensätzen,
- Change-Data-Capture-Streams,
- RAW-Dateien im Data Lake,
- Warehouse-Tabellen,
- dbt-Modellen,
- semantischen Modellen,
- BI-Extrakten,
- Report-Caches,
- Excel-Exporten,
- Anwendungsdatenbanken,
- Support-Tickets,
- Testumgebungen,
- Backups und Snapshots.

Eine Lifecycle-Richtlinie ist nur so vollständig wie ihr Scope.

Wichtige Fragen sind:

- Welche Repräsentationen sind maßgeblich?
- Welche sind temporär?
- Welche sind abgeleitet?
- Welche enthalten dieselben personenbezogenen oder vertraulichen Informationen?
- Welche besitzen einen eigenen geschäftlichen oder rechtlichen Wert?
- Welche werden durch automatische Lifecycle-Regeln abgedeckt?
- Welche benötigen einen anwendungsspezifischen Löschprozess?
- Welche sind durch einen Legal Hold geschützt?
- Welche Kopien bleiben nach einer Löschung wiederherstellbar?
- Welche Downstream-Produkte müssen aktualisiert oder neu aufgebaut werden?

Hier werden Metadaten und Lineage zu Lifecycle-Kontrollen.

Technische Lineage kann Abhängigkeiten identifizieren.

Fachlicher Kontext erklärt, ob die Abhängigkeit noch relevant ist.

Ownership zeigt, wer eine Änderung freigeben kann.

Policy definiert, was geschehen muss.

Monitoring prüft, ob es tatsächlich geschehen ist.

## Stilllegung ist ein Impact-Management-Prozess

Ein ungenutztes Objekt zu löschen kann einfach sein.

Ein etabliertes Datenasset stillzulegen kann ein Change-Management-Prozess sein.

Eine Tabelle, ein Modell, eine API, ein Report oder ein Data Product kann Konsumenten besitzen, die aus reiner Speichernutzung nicht sichtbar werden.

Ein praktikabler Stilllegungs-Lifecycle kann folgende Schritte enthalten:

1. **Kandidaten identifizieren**  
   Geringe Nutzung, Duplikation, Ersatz, veralteter Zweck, nicht mehr unterstützte Technologie, abgelaufene Aufbewahrung oder übermäßiges Risiko können eine Prüfung auslösen.

2. **Ownership bestätigen**  
   Business Owner, Steward und technischen Owner für die Entscheidung identifizieren.

3. **Wert und Verpflichtung bewerten**  
   Prüfen, ob das Asset weiterhin einen fachlichen Zweck, eine rechtliche Anforderung, einen Recovery-Bedarf oder einen historischen Nachweis unterstützt.

4. **Lineage und Abhängigkeiten analysieren**  
   Pipelines, Reports, Anwendungen, Modelle, Exporte und Nutzer identifizieren, die vom Asset abhängen.

5. **Nutzung prüfen**  
   Query-Aktivität, Report-Nutzung, API-Aufrufe und Stakeholder-Feedback verbinden. Nicht beobachtete Nutzung ist ein Hinweis – aber kein automatischer Beweis für Wertlosigkeit.

6. **Holds und Ausnahmen prüfen**  
   Sicherstellen, dass Legal Holds, Untersuchungen, Verträge oder genehmigte Ausnahmen die Stilllegung nicht verhindern.

7. **Maßnahme auswählen**  
   Behalten, optimieren, archivieren, anonymisieren, konsolidieren, deprecaten, stilllegen oder löschen.

8. **Kommunizieren und migrieren**  
   Konsumenten informieren, bei Bedarf Ersatz bereitstellen und einen Übergangszeitraum definieren.

9. **Vor dem Entfernen deprecaten**  
   Das Asset kennzeichnen, neue Nutzung stoppen und die geplante Stilllegung sichtbar machen.

10. **Technische Änderung umsetzen**  
    Pipelines deaktivieren, Zugriffe entziehen, Schedules entfernen, Daten archivieren, Objekte löschen und Dokumentation aktualisieren.

11. **Ergebnis verifizieren**  
    Bestätigen, dass Abhängigkeiten gelöst, erwartete Löschungen erfolgt und notwendige Nachweise vorhanden sind.

12. **Lernen und verbessern**  
    Das Ergebnis nutzen, um Lifecycle-Regeln, Metadaten, Ownership und Stilllegungsmuster zu verbessern.

Der Prozess sollte verhältnismäßig sein.

Eine temporäre Staging-Tabelle benötigt nicht dieselbe Sorgfalt wie ein reguliertes Data Product für das Management-Reporting.

## Löschung kann in modernen Plattformen mehrere technische Zustände besitzen

„Gelöscht“ ist nicht immer ein einzelner Zustand.

Abhängig von Plattform und Konfiguration können Daten zeitweise wiederherstellbar bleiben durch:

- Soft Delete,
- Versionshistorie,
- Time Travel,
- Snapshots,
- Transaktionslogs,
- Replikation,
- Disaster-Recovery-Kopien,
- Objektversionierung,
- Backup-Aufbewahrung,
- Fail-safe- oder Recovery-Zeiträume.

Das bedeutet nicht, dass die Plattform falsch arbeitet.

Recovery-Funktionen sind wertvoll.

Sie schützen vor versehentlicher Löschung, Korruption und operativen Ausfällen.

Die Governance-Anforderung besteht darin, die Zustände zu verstehen.

Für jede Plattform sollte bekannt sein:

- wann Daten aus normalen Abfragen verschwinden,
- ob sie wiederherstellbar bleiben,
- wer sie wiederherstellen kann,
- wie lange Recovery möglich ist,
- ob Lifecycle-Regeln auch auf Versionen wirken,
- wann physische Dateien entfernt werden,
- wie Backups behandelt werden,
- ob Löschung an Replikate propagiert wird,
- welcher Nachweis den Abschluss bestätigt.

Beispielsweise können Tabellenlöschung, Entfernung nicht mehr referenzierter Dateien und Ablauf historischer Versionen getrennte Vorgänge sein.

Ebenso kann die Löschung eines aktiven Cloud-Objekts mit Soft-Delete-Zeiträumen, Objektversionen oder Retention Locks interagieren.

Die relevante Definition von Löschung muss deshalb explizit sein.

> **Eine Lifecycle-Kontrolle sollte nicht nur die beabsichtigte Aktion beschreiben, sondern auch den technischen Endzustand, der den Abschluss belegt.**

## Auch abgeleitete Daten benötigen Lifecycle Governance

Lifecycle-Programme konzentrieren sich häufig auf Quelldatensätze und Storage Buckets.

Geschäftlicher Wert entsteht jedoch oft in abgeleiteten Assets:

- transformierten Tabellen,
- konformierten Dimensionen,
- aggregierten Fakten,
- Features,
- Kennzahlen,
- semantischen Modellen,
- Reports,
- Exporten,
- Notebooks,
- Trainingsdatensätzen,
- Modellergebnissen.

Abgeleitete Daten können ein anderes Risikoprofil als ihre Quelle besitzen.

Aggregation kann Sensitivität reduzieren.

Das Verknüpfen von Datensätzen kann Sensitivität erhöhen.

Ein Report kann Daten sichtbar machen, die im Warehouse maskiert sind.

Ein Trainingsdatensatz kann Werte bewahren, die sich in der Quelle inzwischen verändert haben.

Ein lokaler Export kann bestehen bleiben, nachdem der zentrale Datensatz stillgelegt wurde.

Wichtige Fragen sind:

- Enthält das abgeleitete Asset weiterhin regulierte oder vertrauliche Informationen?
- Können Personen oder Geschäftsvorgänge rekonstruiert werden?
- Erfordert Löschung an der Quelle eine Downstream-Aktualisierung oder Neuverarbeitung?
- Besitzt das abgeleitete Asset eine eigenständige Aufbewahrungspflicht?
- Wer verantwortet den Lifecycle der Kennzahl, des Modells oder Reports?
- Ist das Asset weiterhin zertifiziert oder unterstützt?
- Kann das Asset stillgelegt werden, ohne seine Quelldaten zu löschen?

Lifecycle Governance sollte Bedeutung und Risiko folgen – nicht nur physischem Speicher.

## Aufbewahrungsregeln sollten ausführbar und erklärbar sein

Ein Aufbewahrungsplan beginnt häufig als Policy-Tabelle.

Zum Beispiel:

| Datenklasse | Fachlicher Zweck | Retention-Trigger | Zeitraum oder Ereignis | Maßnahme | Owner |
| --- | --- | --- | --- | --- | --- |
| Kundenvertragsdaten | Nachweis der Vertragsbeziehung | Vertragsende | definierter rechtlicher und vertraglicher Zeitraum | archivieren, danach löschen | Legal / Business Owner |
| Betriebsprotokolle | Sicherheit, Fehleranalyse und Betrieb | Log-Erstellung | risikobasierter operativer Zeitraum | Speicherklasse wechseln, danach löschen | Platform Owner |
| Analytics-Exporte | temporäre Analyse und Reporting | Export-Erstellung | kurzer, zweckbezogener Zeitraum | löschen oder aktualisieren | Report Owner |
| Trainingsdaten | Modellentwicklung und Reproduzierbarkeit | Modellfreigabe oder Dataset-Approval | definierter Model-Governance-Zeitraum | archivieren, anonymisieren oder löschen | Model Owner |
| Veralteter Data Mart | Legacy-Reporting | Ersatz zertifiziert | Übergangszeitraum | stilllegen und entfernen | Data Product Owner |

Die Richtlinie wird operativ, wenn die benötigten Metadaten verfügbar und mit technischer Ausführung verbunden sind.

Nützliche Metadaten können sein:

- Retention-Klasse,
- Retention-Trigger,
- Aufbewahren-bis-Datum,
- Review-Datum,
- Legal-Hold-Status,
- Löschmaßnahme,
- Archivspeicherklasse,
- Owner,
- Steward,
- System of Record,
- geografischer oder vertraglicher Scope,
- Sensitivität,
- Downstream-Abhängigkeiten,
- Löschbestätigung,
- Ausnahmestatus.

Automatisierung kann dann unterstützen bei:

- Wechseln von Speicherklassen,
- Ablaufbenachrichtigungen,
- Review-Workflows,
- Löschjobs,
- Deprecation-Warnungen,
- Entzug von Zugriffen,
- Nachweiserfassung,
- Eskalation von Ausnahmen.

Automatisierung sollte notwendige Beurteilung nicht entfernen.

Sie sollte manuelle Wiederholung reduzieren, nachdem das Entscheidungsmodell klar definiert wurde.

## Relevante Lifecycle-Kennzahlen

Gespeicherte Terabytes allein zeigen nicht, ob Lifecycle Governance funktioniert.

Mögliche Kennzahlen sind:

| Kennzahl | Mögliche Aussage |
| --- | --- |
| Altersverteilung der Daten | wie viel Datenvolumen in welchen Altersklassen liegt |
| Aktive Nutzungsrate | welche Assets genutzt werden und welche geprüft werden sollten |
| Anteil unbesitzter Assets | wie viele Daten keine verantwortliche Ownership besitzen |
| Retention-Klassifizierungsabdeckung | ob Assets einer Lifecycle-Regel zugeordnet sind |
| Abdeckung dokumentierter Zwecke | ob der Grund für Erfassung und Nutzung sichtbar ist |
| Retention Compliance | ob Daten gemäß freigegebener Richtlinie aufbewahrt werden |
| Lösch-Compliance | ob löschberechtigte Daten im erwarteten Zeitraum entfernt werden |
| Archivierungs-Erfolgsrate | ob inaktive Daten die vorgesehene Speicherklasse erreichen |
| Genauigkeit von Legal Holds | ob gehaltene Daten korrekt bewahrt und wieder freigegeben werden |
| Alter von Ausnahmen | ob temporäre Lifecycle-Ausnahmen zu lange offen bleiben |
| Durchlaufzeit der Stilllegung | wie lange es von der Entscheidung bis zum vollständigen Abschluss dauert |
| Verwaiste Assets | Assets ohne aktive Owner, Konsumenten oder unterstützten Zweck |
| Anteil doppelter oder redundanter Assets | wo überlappende Daten unnötige Kosten oder Risiken erzeugen können |
| Auflösungsrate von Abhängigkeiten | ob Konsumenten vor der Stilllegung migriert werden |
| Transparenz von Recovery-Zeiträumen | ob Wiederherstellbarkeit und physische Löschung dokumentiert sind |
| Vollständigkeit von Lösch-Nachweisen | ob der Abschluss belegt werden kann |
| Speicherkosten je Lifecycle-Stufe | ob aktive und inaktive Daten angemessene Speicherklassen nutzen |
| Abschluss von Policy-Reviews | ob Aufbewahrungsregeln aktuell gehalten werden |

Kennzahlen benötigen Interpretation.

Geringe Nutzung bedeutet nicht automatisch geringen Wert.

Alte Daten sind nicht automatisch wertlos.

Ein hohes Löschvolumen ist nicht automatisch gute Governance.

Das Ziel ist ein begründeter, kontrollierter und erklärbarer Lebenszyklus.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-life-img2-de.png"
        alt="Datenlebenszyklus in der Praxis mit Lifecycle-Phasen, Governance-Kontrollen, relevanten Kennzahlen und einer beispielhaften Aufbewahrungsrichtlinien-Matrix"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Lifecycle Governance wird messbar, wenn Richtlinien, Ownership, Sicherheit, Lineage, Monitoring und Stilllegungskontrollen über alle Phasen zusammenwirken.
    </figcaption>
</figure>

> **Hinweis:** Die im Schaubild verwendeten Aufbewahrungsfristen sind ausschließlich Beispiele. Sie sind keine allgemeingültigen rechtlichen Empfehlungen. Tatsächliche Fristen müssen aus anwendbarem Recht, Regulierung, Verträgen, Zweck, Risiko und freigegebener Unternehmensrichtlinie abgeleitet werden.

## Häufige Anti-Patterns

### Alles „für alle Fälle“ aufbewahren

Daten bleiben ohne aktuellen Zweck, Owner, Review-Datum oder objektive Begründung erhalten.

### Retention existiert nur in einer Tabelle

Ein Aufbewahrungsplan ist freigegeben, aber nicht mit Assets, Plattformen, Triggern oder Lösch-Workflows verbunden.

### Eine Frist für alle Daten

Unterschiedliche Zwecke, Sensitivitäten und Verpflichtungen werden in eine einzige Standardfrist gezwungen.

### Nur die Primärtabelle löschen

Backups, Versionen, Replikate, Exporte, Reports und Downstream-Ableitungen liegen außerhalb des Lösch-Scopes.

### Archivieren ohne Ownership

Daten wandern in eine günstigere Speicherklasse, aber niemand bleibt für Zugriff, Qualität, Legal Holds oder spätere Löschung verantwortlich.

### Dauerhafter Legal Hold

Ein Hold wird korrekt gesetzt, aber nach Ende des Grundes nie überprüft oder aufgehoben.

### Stilllegung ohne Abhängigkeitsanalyse

Eine Tabelle oder API wird entfernt, bevor Konsumenten, Reports oder Anwendungen migriert wurden.

### „Keine Queries“ bedeutet „kein Wert“

Technische Telemetrie wird als einziger Nachweis genutzt und rechtliche, saisonale, Recovery- oder seltene fachliche Bedürfnisse werden ignoriert.

### Deprecation ohne Termin

Ein altes Asset wird als veraltet gekennzeichnet, bleibt aber unbegrenzt verfügbar und kann neue Abhängigkeiten erzeugen.

### Lifecycle nur für personenbezogene Daten

Vertrauliche, finanzielle, operative, vertragliche und technisch sensible Daten erhalten keine vergleichbare Lifecycle-Aufmerksamkeit.

### Lifecycle nur für Storage

Tabellen werden gesteuert, Kennzahlen, Reports, Notebooks, Exporte, Features und Modelle bleiben jedoch unberücksichtigt.

### Kosten als einziges Ziel

Speicherkosten werden optimiert, ohne Nachweise, Wiederherstellbarkeit, Geschäftswert, Risiko oder Compliance zu berücksichtigen.

### Löschung ohne Nachweis

Jobs laufen, aber kein Nachweis bestätigt Scope, Ergebnis, Ausnahmen oder wiederherstellbare Zustände.

## Ein praktikables Lifecycle Operating Model

Ein möglicher Ansatz:

1. **Lifecycle-Klassen in Fachsprache definieren**  
   Zweck, Sensitivität, Trigger, Aufbewahrungslogik, Archivverhalten, Löschmaßnahme und Ausnahmen beschreiben.

2. **Klassen mit Datenassets verbinden**  
   Lifecycle-Kontext auf Quellen, Tabellen, Data Products, Reports, Dateien, Modelle und wichtige Ableitungen anwenden.

3. **Verantwortliche Rollen zuweisen**  
   Business Owner, Steward, technischen Owner, Records- oder Legal-Kontakt und Kontrollverantwortliche benennen.

4. **Lifecycle-Trigger erfassen**  
   Beispiele sind Erstellungsdatum, Vertragsende, Fallabschluss, Austritt eines Mitarbeitenden, Projektende oder Zertifizierung eines Ersatzes.

5. **Abhängigkeiten und Kopien abbilden**  
   Lineage, Katalogmetadaten, Plattforminventar und Wissen der Stakeholder verbinden.

6. **Vorhersehbare Maßnahmen automatisieren**  
   Speicherklassen wechseln, Review-Aufgaben erzeugen, temporäre Daten auslaufen lassen, Assets deprecaten und freigegebene Löschmuster ausführen.

7. **Holds und Ausnahmen schützen**  
   Normale Löschung verhindern und gleichzeitig Owner, Grund, Scope und Review-Datum bewahren.

8. **Nutzung, Alter und Policy-Status überwachen**  
   Inaktive, alte, unbesitzte, doppelte, abgelaufene und richtlinienwidrige Assets erkennen.

9. **Verhältnismäßig prüfen**  
   Menschliche Bewertung auf hochwertige, sensible, regulierte, breit genutzte oder unklare Assets konzentrieren.

10. **Stilllegung als kontrollierten Change-Prozess durchführen**  
    Ankündigen, deprecaten, migrieren, deaktivieren und entfernen – mit angemessener Validierung.

11. **Technischen Abschluss verifizieren**  
    Soft Delete, Versionshistorie, Backups, Replikation und physische Entfernung verstehen.

12. **Angemessene Nachweise erhalten**  
    Policy, Entscheidung, Owner, Maßnahme, Ergebnis und Ausnahmen dokumentieren, ohne unnötige neue Aufbewahrung zu erzeugen.

13. **Die Richtlinie selbst überprüfen**  
    Regulierung, Produkte, Architekturen und Geschäftsmodelle verändern sich. Lifecycle-Regeln müssen sich mit ihnen verändern.

## Praktische Fragen für Teams

Für einen wichtigen Datensatz, ein Data Product, einen Report oder eine Plattform:

1. **Warum wurden diese Daten erfasst oder erstellt?**
2. **Ist dieser Zweck noch aktiv und dokumentiert?**
3. **Wer verantwortet die Lifecycle-Entscheidung?**
4. **Welche Klassifizierung und Retention-Regel gelten?**
5. **Welches Ereignis startet die Aufbewahrungsfrist?**
6. **Besteht eine rechtliche, regulatorische, vertragliche oder geschäftliche Pflicht zur Aufbewahrung?**
7. **Welche Quell-, Ableitungs-, Export-, Replikat- und Backup-Kopien existieren?**
8. **Welche Downstream-Assets und Nutzer hängen davon ab?**
9. **Wie häufig werden die Daten genutzt und ist die Nutzungstelemetrie vollständig?**
10. **Kann Archivierung den Bedarf mit weniger Zugriff, Kosten oder Risiko erfüllen?**
11. **Können Daten aggregiert oder anonymisiert werden, statt sie identifizierbar aufzubewahren?**
12. **Sind Legal Holds und Ausnahmen sichtbar, verantwortet und überprüft?**
13. **Was bedeutet „gelöscht“ auf jeder Plattform?**
14. **Wie lange können gelöschte Daten noch wiederhergestellt werden?**
15. **Propagiert Löschung an Replikate, Versionen, Backups und Downstream-Produkte?**
16. **Wie werden Konsumenten vor einer Stilllegung informiert?**
17. **Ist bei Bedarf ein Ersatz verfügbar und zertifiziert?**
18. **Welcher Nachweis bestätigt den erfolgreichen Abschluss der Stilllegung oder Löschung?**
19. **Wann wird die Lifecycle-Regel selbst überprüft?**
20. **Bewahren wir die richtigen Daten – für die richtige Zeit – aus den richtigen Gründen auf?**

Diese Fragen verlangen kein universelles Lifecycle-Tool.

Sie prüfen, ob Zweck, Policy und operative Realität verbunden bleiben.

## Fazit

Data Lifecycle Governance ist nicht primär eine Übung zur Optimierung von Speicherkosten.

Sie ist die Disziplin, Daten während ihrer gesamten Existenz nützlich, geschützt, verständlich und begründbar zu halten.

Moderne Plattformen bieten bereits leistungsfähige Funktionen für:

- Lifecycle-Regeln,
- Wechsel von Speicherklassen,
- Retention Labels,
- Archivierung,
- Versionshistorie,
- Wiederherstellung,
- Löschung,
- Audit und Monitoring.

Das möglicherweise fehlende Teil ist das End-to-End Operating Model.

Daten lassen sich leicht erzeugen.

Kopien lassen sich leicht erstellen.

Neue Modelle und Reports lassen sich leicht veröffentlichen.

Stilllegung benötigt eine Entscheidung.

Löschung benötigt Sicherheit.

Diese Sicherheit benötigt Kontext:

- Zweck,
- Ownership,
- Policy,
- Lineage,
- Abhängigkeiten,
- rechtliche Verpflichtungen,
- technische Zustände,
- Nachweise.

Die Lifecycle-Kette lautet:

```flow linear vertical
Zweck
Erzeugung
Klassifizierung
Ownership
kontrollierte Nutzung
Monitoring
Aufbewahrung oder Archivierung
Stilllegungsentscheidung
Auflösung von Abhängigkeiten
Löschung oder Erhaltung
Nachweis
Review
```

Die zentrale Frage lautet deshalb:

> **Bewahren wir die richtigen Daten – für die richtige Zeit – aus den richtigen Gründen auf?**

## The Missing Pieces – was die Serie verbindet

Dieser letzte Teil schließt eine Kette über das vollständige Data-Governance-Operating-Model:

1. **Data Quality**  
   Probleme erkennen, Auswirkungen begrenzen und den Kreislauf zurück zur Ursache schließen.

2. **Trusted Metrics**  
   Fachliche Definitionen über technische und analytische Ebenen verständlich und konsistent halten.

3. **Ownership & Stewardship**  
   Zugewiesene Rollen in handlungsfähige Verantwortung, Entscheidungswege und messbare Ergebnisse verwandeln.

4. **Metadata, Catalog & Lineage**  
   Technische Sichtbarkeit mit fachlicher Bedeutung und nutzbarem Kontext verbinden.

5. **Policy & Access Governance**  
   Dokumentierte Absicht in gesteuerte Identitäten, operative Kontrollen, Reviews und Nachweise übersetzen.

6. **Data Lifecycle & Retirement**  
   Nicht nur steuern, wie Daten entstehen und genutzt werden, sondern auch, warum sie aufbewahrt, wann sie stillgelegt und wie Endzustände verifiziert werden.

Das gemeinsame Thema ist nicht, dass moderne Governance-Plattformen keine Fähigkeiten besitzen.

Wert entsteht, wenn diese Fähigkeiten in einem verständlichen Operating Model verbunden werden.

> **Governance ist am stärksten, wenn Menschen Daten verstehen, Verantwortung wahrnehmen, Regeln vertrauen und den vollständigen Lebenszyklus von der Quelle bis zur Entscheidung – und schließlich bis zur Stilllegung – nachvollziehen können.**

## Weiterführende Ressourcen

- [NIST Research Data Framework (RDaF)](https://www.nist.gov/programs-projects/research-data-framework-rdaf)
- [NIST SP 1500-18r2: NIST Research Data Framework](https://nvlpubs.nist.gov/nistpubs/SpecialPublications/1500-18/NIST.SP.1500-18r2.html)
- [EUR-Lex: Verordnung (EU) 2016/679 – Datenschutz-Grundverordnung](https://eur-lex.europa.eu/eli/reg/2016/679/oj/deu)
- [Microsoft Learn: Informationen zur Datenlebenszyklusverwaltung in Microsoft Purview](https://learn.microsoft.com/de-de/purview/data-lifecycle-management)
- [Microsoft Learn: Aufbewahrungsrichtlinien und Aufbewahrungsbezeichnungen](https://learn.microsoft.com/de-de/purview/retention)
- [AWS Well-Architected Analytics Lens: Implement data retention processes](https://docs.aws.amazon.com/wellarchitected/latest/analytics-lens/best-practice-15.4-implement-data-retention-processes-to-remove-redundant-data-from-your-analytics-environment..html)
- [AWS Dokumentation: Managing the lifecycle of Amazon S3 objects](https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-lifecycle-mgmt.html)
- [AWS Well-Architected Security Pillar: Define scalable data lifecycle management](https://docs.aws.amazon.com/wellarchitected/latest/security-pillar/sec_data_classification_lifecycle_management.html)
- [Google Cloud Dokumentation: Options for controlling data lifecycles](https://cloud.google.com/storage/docs/control-data-lifecycles)
- [Google Cloud Dokumentation: Object Lifecycle Management](https://cloud.google.com/storage/docs/lifecycle)
- [Databricks Dokumentation: Remove unused data files with VACUUM](https://docs.databricks.com/aws/en/delta/vacuum)
- [Databricks Dokumentation: Work with table history](https://docs.databricks.com/aws/en/delta/history)
