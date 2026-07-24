---
title: Wo Metadaten entstehen — Erkennen, welches System welchen Teil der Wahrheit kennt
description: Ein praxisnaher Leitfaden zur Entstehung technischer, fachlicher, operativer, Governance-, Nutzungs- und AI-Metadaten über den gesamten Datenlebenszyklus hinweg — mit erhaltener Provenance, Autorität und Verantwortung.
category: Data Governance
tags:
  - metadata
  - metadata-governance
  - data-catalog
  - data-lineage
  - data-observability
  - data-quality
  - semantic-layer
  - identity-governance
  - data-provenance
  - active-metadata
  - ai-governance
  - data-products
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 2
seriesTitle: MetaData Deep Dive
hero: images/playbooks/where-metadata-is-born-hero.png
---

## Metadaten beginnen nicht im Katalog

Ein zentraler Datenkatalog wird häufig als der Ort betrachtet, an dem Metadaten entstehen. Tatsächlich ist er meist der Ort, an dem Metadaten aus anderen Systemen gesammelt, normalisiert, verbunden und dargestellt werden.

Der ursprüngliche Kontext entsteht wesentlich früher und an vielen verschiedenen Stellen:

- eine Fachanwendung kennt den Grund für einen Status und die zulässigen Prozessübergänge
- eine Datenbank kennt physische Struktur, Constraints und Berechtigungen
- ein Ingestion-Prozess weiß, wann und wie Daten extrahiert wurden
- eine Orchestrierungsplattform kennt Zeitpläne, Abhängigkeiten und Fehler
- Transformationscode kennt den Weg von Quellfeldern zu abgeleiteten Modellen
- ein semantisches Modell kennt Measures, Dimensions, Hierarchien und Aggregationsverhalten
- eine BI-Anwendung weiß, welche Filter, Bookmarks und Reports tatsächlich genutzt werden
- ein Identity-System kennt Rollen und Gruppen für den Zugriff
- Observability-Prozesse wissen, ob aktuelle Daten verspätet, unvollständig oder gedriftet sind
- eine AI- oder Data-Science-Plattform kennt verwendete Datasets, Features, Experimente und Modelle

Keines dieser Systeme kennt von sich aus die vollständige Wahrheit.

Ein Quellsystem kann erklären, dass `ORDER_STATUS = C` „vom Kunden vor der Erfüllung storniert“ bedeutet. Es weiß möglicherweise nicht, dass dieses Feld später umbenannt, mit einem Lieferstatus kombiniert und in einer zertifizierten Kennzahl für offene Aufträge verwendet wird. Eine BI-Anwendung kann wissen, dass Tausende Nutzer diese Kennzahl täglich verwenden. Sie kennt möglicherweise nicht die fachlich zulässigen Statusübergänge im Quellsystem. Ein zentraler Katalog kann beide Perspektiven verbinden, sollte aber keine davon erfinden.

> **Metadaten entstehen überall dort, wo Bedeutung, Struktur, Bewegung, Transformation, Kontrolle oder Nutzung sichtbar werden. Eine vertrauenswürdige Metadatenplattform erhält diesen Ursprung, statt ihn durch eine anonyme zentrale Kopie zu ersetzen.**

Damit verändert sich die zentrale Frage von „Wo dokumentieren wir alles?“ zu:

> „Welches System oder welche verantwortliche Rolle kennt diesen konkreten Teil der Wahrheit am besten, und wie erhalten wir dieses Wissen über den gesamten Lifecycle?“

## Metadaten entstehen über den gesamten Datenlebenszyklus

Metadatenerzeugung ist kein einzelnes Ereignis. Sie ist eine Folge von Deklarationen, technischer Generierung und Laufzeitbeobachtungen, die von der operativen Transaktion bis zum finalen Report, zur Anwendung oder zum AI-Modell reicht.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/where-metadata-is-born-img1-de.png"
        alt="Metadaten entstehen in Quellsystemen, Ingestion, Storage, Transformation, semantischen Modellen, BI-Anwendungen sowie AI- und Data-Science-Plattformen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Jede Stufe des Lifecycles erzeugt einen anderen Teil des Metadatenkontexts. Eine zentrale Ebene wird dann wertvoll, wenn sie diese Beiträge verbindet, ohne deren Ursprung zu verdecken.
    </figcaption>
</figure>

### Quellsysteme erzeugen ursprünglichen fachlichen und strukturellen Kontext

In operativen Quellsystemen finden Geschäftsereignisse statt. Sie kennen häufig Kontext, den keine nachgelagerte Plattform zuverlässig rekonstruieren kann.

Typische quellnative Metadaten sind:

- Objekt- und Feldnamen der Anwendung
- Quellbezeichnungen und Hilfetexte
- zulässige Werte und Prozesszustände
- Keys, Constraints und Beziehungen
- Transaktionszeitpunkte
- Verantwortung als Source of Record
- Workflow- oder Statusübergangsregeln
- Audit-Felder
- durch Nutzer gepflegte Klassifikationen oder Kategorien
- lokale Ownership-Informationen

Für einen Auftragsprozess kann die Quellanwendung beispielsweise wissen, dass:

- ein Auftrag mehrere Auftragspositionen enthalten kann
- eine Position unabhängig storniert werden kann
- ein gewünschtes Lieferdatum nach Auftragserfassung geändert werden darf
- ein bestimmter Status erst nach Kreditfreigabe zulässig ist
- ein manueller Override einen Begründungscode benötigt
- der Nettowert in Belegwährung gespeichert wird

Ein nachgelagerter Scanner kann Namen, Datentypen und Werteverteilungen erkennen. Er kann die vollständige Prozessbedeutung hinter diesen Feldern nicht zuverlässig ableiten.

Quellmetadaten sind nicht automatisch perfekt. Labels können kryptisch, Hilfetexte veraltet und Geschäftslogik nur in Anwendungscode oder individuellem Wissen vorhanden sein. Die Quelle ist autoritativ für das, was sie tatsächlich speichert und durchsetzt, aber nicht zwangsläufig für jede darauf aufbauende Enterprise-Definition.

### Ingestion erzeugt Bewegungs- und Extraktionsmetadaten

Ingestion ist der erste große Übergabepunkt. Daten verlassen ihren operativen Kontext und werden zu einer transportierten Repräsentation.

Der Ingestion-Prozess kann unter anderem erzeugen:

- Start- und Endzeit der Extraktion
- Extraktionsmethode
- Full- oder Incremental-Load-Modus
- Quellverbindung und Quellobjekt
- Source-to-Target-Mapping
- Watermark oder Change Token
- erfasste Schemaversion
- Zeilen- und Dateianzahlen
- Ladestatus
- Retry-Informationen
- abgewiesene Datensätze
- Landing-Speicherort
- Ingestion-Run-ID
- technischer Service Account

Diese Werte erklären, wie Daten angekommen sind. Sie sind entscheidend, wenn eine nachgelagerte Tabelle weniger Datensätze als erwartet enthält, ein Load verspätet ist oder Quellfelder unter anderen Namen erscheinen.

Eine Landing-Tabelle kann physisch korrekt sein und trotzdem keinen Extraktionskontext besitzen. Ohne Run-ID, Source Mapping und Extraktionszeitpunkt lässt sich möglicherweise nicht bestimmen, ob die Tabelle den aktuellen Quellstand, einen unvollständigen Batch oder einen erneut eingespielten historischen Lauf repräsentiert.

### Storage erzeugt physische Metadaten

Datenbanken, Warehouses, Lakehouses und File Stores wissen, wie Daten repräsentiert und optimiert werden.

Typische Storage-Metadaten sind:

- Identität von Datenbank, Katalog, Schema, Tabelle und Feld
- physische Datentypen
- Nullability
- Keys und Constraints
- Dateiformat
- Partitionierung und Clustering
- Statistiken
- Objektgröße
- Datensatzanzahl
- Speicherort
- Grants und Rollen
- Erstellungs- und Änderungszeitpunkt
- Retention- oder Time-Travel-Konfiguration
- physische Abhängigkeiten wie Views

Storage-Metadaten lassen sich häufig besonders einfach automatisiert erfassen, weil sie strukturiert und maschinenlesbar vorliegen. Dadurch werden sie aber nicht automatisch zum wichtigsten Kontext.

Ein Warehouse kann zeigen, dass `NET_AMOUNT` ein Dezimalfeld in einer partitionierten Tabelle ist. Es weiß möglicherweise nicht, ob der Betrag stornierte Aufträge einschließt, welcher Business Owner die Definition freigegeben hat oder ob das Feld für kundenbezogene AI-Nutzung geeignet ist.

### Orchestrierung erzeugt Prozess- und Laufzeitmetadaten

Orchestrierungsplattformen wissen, wann Arbeit ausgeführt werden soll, wovon sie abhängt und was während der Ausführung geschehen ist.

Sie erzeugen Metadaten wie:

- Zeitplan und Trigger
- Upstream- und Downstream-Task-Abhängigkeiten
- Run-ID
- Start, Ende und Dauer der Ausführung
- Success-, Failure- und Retry-Status
- Parameter und Umgebung
- Deployment- oder Codeversion
- verarbeitete Datensatzanzahl
- Alert- und Incident-Referenzen
- verantwortliches technisches Team
- Service-Level-Erwartung

Diese Metadaten ersetzen keine Data Lineage. Eine Task-Abhängigkeit kann zeigen, dass Job B nach Job A läuft. Sie beweist nicht automatisch, welche Felder aus welchen Quellfeldern abgeleitet wurden.

Orchestrierungsmetadaten erklären den operativen Ablauf. Transformationsmetadaten erklären die Datenableitung. Beides wird benötigt.

### Transformation erzeugt abgeleitete Bedeutung und Lineage

Transformationscode ist der Ort, an dem quellnahe Daten standardisiert, verbunden, gefiltert, historisiert, aggregiert oder auf andere Weise interpretiert werden.

Diese Ebene kennt:

- Transformationsausdrücke
- Modellabhängigkeiten
- Quellreferenzen
- umbenannte und abgeleitete Felder
- Filter und Ausschlüsse
- Joins
- Implementierung von Geschäftsregeln
- Tests und Erwartungen
- Modellbeschreibungen
- Materialisierungsverhalten
- versionierte Änderungshistorie
- Model Contracts
- Feld-Lineage, soweit sie ableitbar ist

Eine Transformation kann `open_order_value` beispielsweise so definieren:

```text
order_line_net_amount
wenn order_status nicht in ('cancelled', 'completed')
und delivery_status <> 'fully_delivered'
umgerechnet in Reporting-Währung mit dem freigegebenen Tageskurs
```

Diese Logik gehört nicht in den Datenbankkatalog der Quelle. Sie entsteht in der Transformationsschicht und sollte dort dokumentiert und versioniert werden.

Die Transformationsschicht muss zugleich die Beziehung zur Quelle erhalten. Die Umbenennung von `NETWR` zu `net_amount` verbessert die Lesbarkeit. Ursprüngliches Feld, Quellobjekt und Transformationsregel müssen trotzdem nachvollziehbar bleiben.

### Die semantische Ebene erzeugt analytisches Verhalten

Ein semantisches Modell ergänzt Kontext, der in Quelltabellen oder Transformationsmodellen nicht natürlich vorhanden ist.

Es kann definieren:

- Measures und berechnete Kennzahlen
- Dimensions
- Hierarchien
- Anzeigenamen
- Zahlen- und Datumsformate
- Einheiten und Währungen
- Aggregationsverhalten
- Time-Intelligence-Regeln
- Default-Filter
- gültige analytische Beziehungen
- zertifizierte gegenüber lokalen Berechnungen
- semantische Synonyme

Eine Warehouse-Spalte kann `net_amount_reporting_currency` enthalten. Die semantische Ebene kann daraus Folgendes definieren:

- `Open Order Value`
- additiv nach Kunde, Produkt und Vertriebsorganisation
- bewertet gegen das aktuelle Reporting-Datum
- ohne stornierte und abgeschlossene Positionen
- formatiert in der gewählten Reporting-Währung
- zertifiziert für operatives Sales Reporting

Diese KPI-Darstellung und ihr analytisches Verhalten entstehen in der semantischen Ebene. Eine zentrale Plattform sollte das Measure mit Warehouse-Feldern und Quelllogik verbinden, ohne vorzugeben, das Quellsystem hätte bereits die finale Kennzahl erzeugt.

### BI und Anwendungen erzeugen Nutzungsmetadaten

Reports, Dashboards und analytische Anwendungen zeigen, wie governte Daten tatsächlich genutzt werden.

Sie erzeugen oder liefern:

- Report Ownership
- Report- und Sheet-Struktur
- Abhängigkeiten von Visualisierungen
- Filter und Selektionen
- Bookmarks
- lokal berechnete Felder
- Refresh-Verhalten
- Exportaktivitäten
- Nutzer- und Gruppenzugriff
- Abfragehäufigkeit
- aktive Nutzer
- letzten Zugriff
- Adoption-Trends
- kritische Geschäftsprozesse
- Subscriptions und Alerts

Nutzungsmetadaten liefern Evidenz über Relevanz und Impact. Ein Feld in einem experimentellen Einzelreport besitzt ein anderes Änderungsrisiko als ein Measure in Executive Reporting, Sales Operations und Customer Service.

Diese Informationen bleiben häufig in der BI-Plattform eingeschlossen. Werden sie nicht in den gemeinsamen Metadatenkontext zurückgeführt, ist zwar Upstream-Lineage sichtbar, nicht aber die geschäftliche Konsequenz einer Änderung.

### Identity-, Access- und Governance-Systeme erzeugen Kontrollmetadaten

Identity- und Governance-Systeme wissen, wer entscheiden und wer zugreifen darf.

Relevante Metadaten sind:

- Nutzer, Gruppen und Rollen
- Rollenmitgliedschaften
- Freigabeworkflows
- Access Requests
- Policy-Zuordnungen
- Entitlement-Entscheidungen
- zulässige Zwecke
- Separation-of-Duty-Regeln
- Ausnahmen und Ablaufdaten
- Review-Evidenz
- Zertifizierungsstatus
- Zugriffsereignisse

Das Warehouse kann zeigen, dass eine Rolle `SELECT`-Zugriff besitzt. Eine Identity-Plattform kann wissen, warum ein Nutzer diese Rolle erhalten hat, wer sie freigegeben hat und wann die Berechtigung abläuft. Ein Governance-Workflow kann wissen, ob das Dataset für einen bestimmten Use Case freigegeben ist.

Diese Perspektiven müssen verbunden werden. Ein technischer Grant ohne fachlichen Freigabekontext ist unvollständig. Eine Freigabe ohne Evidenz der tatsächlichen technischen Umsetzung ist ebenfalls unvollständig.

### Observability erzeugt aktuelle Evidenz

Data Observability, Quality Monitoring und operative Telemetrie erzeugen Metadaten, die erst zur Laufzeit existieren.

Beispiele sind:

- Freshness
- Volumen- und Row-Count-Trends
- Schema Drift
- Verteilungsänderungen
- fehlgeschlagene Qualitätsregeln
- Anomalieereignisse
- Laufzeit und Latenz
- Kosten- und Workload-Verhalten
- Incident-Status
- betroffene Downstream-Datenobjekte
- aktuelle Einhaltung von Service Levels

Keine statische Dokumentation kann diese Evidenz ersetzen. Eine Beschreibung kann korrekt und ein Owner benannt sein, während der letzte Load vier Stunden verspätet ist und ein Schlüsselfeld plötzlich zu 40 Prozent NULL enthält.

Beobachtete Metadaten sind zeitgebunden. Sie müssen Zeitstempel, Scope, Version des Tests oder Monitors und die bewertete Objektversion erhalten.

### AI- und Data-Science-Plattformen erzeugen Modell- und Experimentmetadaten

AI- und Data-Science-Arbeit erzeugt eine weitere Kontextebene:

- für Training, Evaluation oder Retrieval verwendete Datasets
- Feature-Definitionen
- Feature-Generierungscode
- Experimentparameter
- Modellversionen
- Prompt- oder Retrieval-Abhängigkeiten
- Training- und Evaluation-Runs
- Lineage von Quelldaten bis zum Modellergebnis
- Qualitäts- und Performance-Metriken
- Freigaben
- Deployment-Status
- bekannte Einschränkungen
- Modell-Consumer

Ein Model Registry kann eine Modellversion und ein Evaluationsergebnis beschreiben. Es weiß möglicherweise nicht, ob die ursprünglichen Kundendaten für diesen Trainingszweck freigegeben waren, solange Governance- und Quellmetadaten nicht verbunden sind.

Dasselbe gilt für RAG. Ein Vektorindex kann wissen, welche Chunks eingebettet wurden. Vertrauen erfordert eine Verbindung zum ursprünglichen Dokument oder Datensatz, zu dessen Version, Owner, Access Policy, Sensitivität, Sprache, Gültigkeitszeitraum und Löschstatus.

## Jedes System kennt einen anderen Teil der Wahrheit

Ziel ist nicht, eine Plattform zum universellen Metadata Owner zu erklären. Autorität muss nach Wissensdomäne zugeordnet werden.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/where-metadata-is-born-img2-de.png"
        alt="Verantwortungsübersicht für Metadatenwissen in Fachquellen, Pipelines, Transformation, Konsum, Identity und Governance"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine zentrale Plattform verbindet ursprüngliche fachliche Bedeutung, operative Bewegung, abgeleitete Logik, Nutzungsverhalten und Governance-Entscheidungen. Sie darf keinen dieser Bereiche stillschweigend erfinden.
    </figcaption>
</figure>

### Fachliche Quellen kennen ursprüngliche Bedeutung und gültige Prozesszustände

Quellanwendung und verantwortliche fachliche Rollen sollten autoritativ bleiben für:

- ursprüngliche Transaktionsbedeutung
- gültige Zustände und Übergänge
- Quellsystem-Identifikatoren
- Verantwortung als Source of Record
- operative Prozessregeln

Diese Autorität kann in Anwendungskonfiguration, Code, Quelldokumentation oder verantwortlichem menschlichem Wissen liegen. Das Metadatenprogramm muss erkennen, welche dieser Varianten tatsächlich verlässlich ist.

### Pipelines und Orchestrierung kennen Bewegung und Ausführung

Ingestion und Orchestrierung sollten autoritativ bleiben für:

- Art der Datenbewegung
- Zeitpunkt der Bewegung
- erzeugenden Run
- ausgeführte Abhängigkeiten
- Fehler und Retries
- deployte technische Version

Ein Katalog kann diese Fakten übernehmen, sollte aber die ursprüngliche Run- und Systemreferenz erhalten.

### Transformation kennt abgeleitete Logik

Transformations-Repositories und Ausführungsartefakte sollten autoritativ bleiben für:

- abgeleitete Felder
- Filter
- Joins
- Berechnungen
- Modellabhängigkeiten
- Tests
- Contracts
- Codeversionen

Eine manuell in einen Katalog kopierte Formel wird zu einer zweiten, potenziell inkonsistenten Implementierung. Besser ist es, die Katalogdarstellung mit der versionierten Transformationsdefinition zu verbinden.

### Konsumsysteme kennen Darstellung und Verhalten

Semantische und BI-Plattformen sollten autoritativ bleiben für:

- für Nutzer dargestellte Measures und Dimensions
- semantische Beziehungen
- Anzeigeverhalten
- lokale Berechnungen
- Report Ownership
- Nutzung und Adoption

Eine zentrale Plattform kann lokale Logik markieren, die vom governten Modell abweicht. Sie darf die Evidenz, dass diese lokale Logik existiert, aber nicht entfernen.

### Identity und Governance kennen Entscheidungen und Berechtigungen

Identity-, Access- und Governance-Prozesse sollten autoritativ bleiben für:

- Rollen und Gruppenmitgliedschaften
- Freigaben
- Policy-Zuordnungen
- zulässige Nutzung
- Ausnahmen
- Review-Status
- Zertifizierungsentscheidungen

Die zentrale Metadatenebene verbindet diese Entscheidungen mit den relevanten Datenobjekten, Prozessen und Consumern.

## Ursprung, Autorität und Speicherort sind unterschiedliche Konzepte

Ein Metadatenwert kann zentral gespeichert sein, ohne dadurch zentral autoritativ zu werden.

Ein zentrales Profil kann beispielsweise enthalten:

```yaml
asset_id: sales.order_line
metadata:
  business_definition:
    value: Aktive und historische Auftragsposition des Order-Management-Prozesses.
    origin_type: declared
    authoritative_source: business_glossary.sales.order_line
    accountable_role: Sales Operations Data Owner
    status: approved
    effective_from: 2026-06-01

  physical_data_type:
    value: DECIMAL(18,2)
    origin_type: generated
    authoritative_source: warehouse.prod.sales_order_line.net_amount
    observed_at: 2026-07-24T17:20:00Z

  last_successful_refresh:
    value: 2026-07-24T16:55:12Z
    origin_type: observed
    authoritative_source: orchestration.run.184294
    observed_at: 2026-07-24T16:55:12Z

  open_order_measure:
    value: semantic.sales.open_order_value
    origin_type: declared
    authoritative_source: semantic_model.sales.metrics
    code_version: 8f42c1a
```

Das zentrale System speichert eine verbundene Repräsentation. Die Autorität bleibt trotzdem verteilt.

Ein importierter Metadatenwert sollte mindestens Folgendes erhalten:

- stabilen Asset Identifier
- Metadatenattribut
- Wert
- Ursprungstyp
- Quellsystem
- Quellobjekt oder Datensatzreferenz
- verantwortlichen Owner oder Prozess
- Erfassungsmethode
- Erfassungszeitpunkt
- Gültigkeits- oder Bewertungszeitpunkt
- Version
- Status
- gegebenenfalls Confidence
- bei Import angewandte Transformation oder Normalisierung

Ohne diese Angaben kann Zentralisierung Konflikte verdecken und eine falsche Sicherheit erzeugen.

## Automatisch erzeugte und menschlich gelieferte Metadaten müssen verbunden werden

Automatisierung kann große Mengen von Metadaten erfassen. Sie ersetzt keinen verantworteten fachlichen Kontext.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/where-metadata-is-born-img3-de.png"
        alt="Automatisch erzeugte und menschlich gelieferte Metadaten werden zu einem gemeinsamen Metadatenprofil verbunden"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Technisches Harvesting reduziert manuellen Aufwand. Fachdefinition, vorgesehene Nutzung, Ownership, Rechtsgrundlage, Ausnahmen und Freigaben benötigen weiterhin verantworteten Input.
    </figcaption>
</figure>

### Metadaten, die meist generiert oder beobachtet werden

Gute Kandidaten für automatisierte Erfassung sind:

- Schemas und Datentypen
- physische Keys und Constraints
- Objektstandorte
- Query- oder Code-Lineage
- Pipeline-Runs
- Laufzeit und Freshness
- Schema Drift
- Qualitätsergebnisse
- Zugriffsereignisse
- Nutzungshäufigkeit
- Modell- und Experiment-Runs
- erkannte Muster sensibler Daten

Auch hier besitzt Automatisierung Grenzen.

Erkannte PII ist zunächst ein Vorschlag, solange Erkennungsregel und Kontext keine autoritative Klassifikation rechtfertigen. Geparste Lineage kann unvollständig sein, wenn dynamisches SQL, generierter Code, externe Prozeduren oder undurchsichtige Anwendungen beteiligt sind. Nutzungslogs können zeigen, dass ein Objekt abgefragt wurde, aber nicht, ob das Ergebnis eine Geschäftsentscheidung beeinflusst hat.

### Metadaten, die normalerweise verantworteten menschlichen Input benötigen

Menschlich gepflegte Metadaten umfassen häufig:

- fachliche Definition
- vorgesehene Nutzung
- Scope und Ausschlüsse
- verantwortlichen Owner
- Data Steward
- Source-of-Record-Entscheidung
- KPI-Interpretation
- Rechtsgrundlage
- freigegebene Sensitivität
- Policy-Ausnahme
- freigegebene Aufbewahrungsklasse
- Freigabe- und Zertifizierungsstatus
- bekannte Einschränkungen

Das bedeutet nicht, dass jedes Feld manuell in einen Katalog eingegeben werden muss. Menschliche Entscheidungen können in versionierten Dateien, Workflow-Systemen, Domänenregistern oder Quellanwendungen gepflegt werden. Entscheidend ist, dass der Wert eine verantwortete Entscheidung und keinen automatisierten Guess repräsentiert.

### Konflikte müssen sichtbar bleiben

Zentrale Systeme erhalten häufig unterschiedliche Werte für dasselbe Attribut.

Beispiel:

```text
Quellbezeichnung: Customer segment
Transformationsbeschreibung: Commercial customer grouping
Semantisches Label: Account tier
Business-Glossary-Begriff: Customer value class
```

Die Plattform sollte nicht stillschweigend ein Label auswählen und alle anderen verwerfen.

Ein brauchbares Auflösungsmodell unterscheidet:

- ursprüngliche Quellbezeichnungen
- technische Modellnamen
- freigegebenen Fachbegriff
- Consumer-orientierten Anzeigenamen
- Synonyme
- veraltete Begriffe
- ungelöste Konflikte

Das Ergebnis ist ein verbundenes Vokabular und kein flach überschriebenes Textfeld.

## Die einfachste sinnvolle Umsetzung

Ein Metadatenprogramm kann ohne Enterprise-Graph oder umfangreichen Katalog-Rollout beginnen.

Die einfachste sinnvolle Umsetzung ist ein ursprungsbewusstes Metadatenregister für ein End-to-End-Datenprodukt.

Wähle einen fachlichen Output, beispielsweise `Open Order Reporting`, und identifiziere:

1. die operativen Quellobjekte
2. die Ingestion-Jobs
3. die Landing- und Warehouse-Objekte
4. die Transformationsmodelle
5. die semantischen Measures
6. die Reports und Anwendungen
7. die Zugriffsrollen
8. die Quality- und Observability-Prüfungen
9. mögliche AI- oder Data-Science-Consumer

Für jede Komponente werden mindestens folgende Informationen erfasst:

| Bereich | Mindestinformation |
| --- | --- |
| **Identität** | stabiler Identifier, System, Objekttyp und Speicherort |
| **Ursprung** | System oder Rolle, die den Metadatenwert erzeugt hat |
| **Autorität** | Quelle, die für den Wert autoritativ bleibt |
| **Beziehung** | Upstream- und Downstream-Identifier |
| **Version** | Schema-, Code-, Modell- oder Policy-Version |
| **Zeit** | Erfassungszeit, Gültigkeitszeit oder Beobachtungszeit |
| **Verantwortung** | Business Owner, Steward oder technischer Owner |
| **Status** | vorgeschlagen, geprüft, freigegeben, veraltet oder fehlgeschlagen |

Das Register kann zunächst umgesetzt werden mit:

- Datenbankkommentaren für quellnahe technische Beschreibungen
- versioniertem YAML oder JSON für governte Deklarationen
- Transformationsmanifesten oder geparstem Code für Lineage
- Orchestrierungslogs für Run-Evidenz
- exportierten BI-Metadaten für Measures und Reports
- Datensätzen aus Access-Systemen für Rollen und Freigaben
- einem kleinen zentralen Index, der stabile Identifier verbindet

Das erste Ziel ist keine vollständige Automatisierung. Es soll nachgewiesen werden, dass ein fachlicher Output systemübergreifend verfolgt werden kann, ohne seinen Ursprung zu verlieren.

## End-to-End-Beispiel: von der Auftragserfassung zum Open-Order-Report

Betrachten wir eine Organisation, die eine zertifizierte Kennzahl `Open Order Value` benötigt.

### 1. Das Order-Management-System erfasst die Transaktion

Die Quellanwendung erzeugt eine Auftragsposition mit:

- `order_id`
- `order_line_id`
- `customer_id`
- `product_id`
- `order_status`
- `delivery_status`
- `requested_delivery_date`
- `net_amount`
- `currency_code`

Die Quelle weiß, dass `order_status = CANCELLED` bedeutet, dass der Kunde oder ein interner Prozess die Position storniert hat. Sie kennt außerdem die gültigen Übergänge und den Nutzer, der die Änderung ausgeführt hat.

Hier entstehende Metadaten:

- Quellidentifikatoren
- Quellbezeichnungen
- zulässige Statuswerte
- Transaktionsbedeutung
- Audit-Felder
- Source-of-Record-Verantwortung

### 2. Ingestion extrahiert geänderte Datensätze

Ein inkrementeller Prozess extrahiert Datensätze, die seit der vorherigen Watermark verändert wurden.

Hier entstehende Metadaten:

- Quellverbindung
- Extraktionszeitpunkt
- Watermark
- Source-to-Target-Mapping
- Run-ID
- Anzahl extrahierter Datensätze
- Ladestatus
- abgewiesene Datensätze

Der Prozess muss Primary Key und Extraktionskontext der Quelle erhalten. Andernfalls lassen sich doppelte oder fehlende Datensätze später nicht zuverlässig diagnostizieren.

### 3. Storage erfasst Landing- und Warehouse-Strukturen

Landing- und Warehouse-Ebenen erzeugen physische Tabellen und Dateien.

Hier entstehende Metadaten:

- Schema
- physische Datentypen
- Nullability
- Partitionen
- Statistiken
- Grants
- Objektversionen
- Speicherort

Das Warehouse kann den Feldnamen von `NETWR` zu `net_amount` standardisieren. Der ursprüngliche Name sollte über Source-to-Target-Mapping erhalten bleiben.

### 4. Transformation erzeugt das governte Auftragsmodell

Transformationslogik verbindet Währungskurse, normalisiert Statuswerte und berechnet einen Wert in Reporting-Währung.

Hier entstehende Metadaten:

- Transformationslogik
- Abhängigkeiten
- Filter
- Joins
- Modelltests
- abgeleitete Felder
- Feld-Lineage
- Codeversion
- Modellbeschreibung

Die Transformation kann beispielsweise erzeugen:

```text
is_open_order =
  order_status not in ('CANCELLED', 'COMPLETED')
  and delivery_status <> 'FULLY_DELIVERED'
```

Diese Regel ist nicht quellnativ. Sie ist eine governte Interpretation, die im Transformationscode umgesetzt wird.

### 5. Das semantische Modell definiert die Kennzahl

Die semantische Ebene definiert:

```text
Open Order Value =
SUM(net_amount_reporting_currency)
WHERE is_open_order = true
```

Hier entstehende Metadaten:

- Measure-Identität
- Anzeigename
- Format
- Aggregationsverhalten
- unterstützte Dimensions
- Default-Zeitkontext
- Zertifizierungsstatus
- semantischer Owner

### 6. BI-Anwendungen zeigen die tatsächliche Nutzung

Ein Dashboard stellt die Kennzahl nach Kunde, Vertriebsorganisation, Produktgruppe und gewünschtem Liefermonat dar.

Hier entstehende Metadaten:

- Report Ownership
- Abhängigkeiten von Visualisierungen
- Filter
- Bookmarks
- Subscriptions
- aktive Nutzer
- Exportaktivitäten
- lokale Berechnungen
- Adoption

Erzeugt ein Report eine eigene Formel für `Open Order Value`, statt das zertifizierte Measure zu verwenden, ist auch diese Abweichung Metadateninformation und muss sichtbar sein.

### 7. Identity und Governance kontrollieren den Zugriff

Die Organisation beschränkt kundenbezogene Details auf autorisierte Sales-Rollen, während aggregierte Ergebnisse breiter verfügbar sind.

Hier entstehende Metadaten:

- Rollen
- Gruppenmitgliedschaften
- Policy-Zuordnung
- Access Approval
- Ablaufdatum von Ausnahmen
- Review-Evidenz
- Zugriffsereignisse

### 8. Observability misst die aktuelle Verlässlichkeit

Monitoring bestätigt, ob Quelle, Ingestion und Transformation erfolgreich abgeschlossen wurden.

Hier entstehende Metadaten:

- letzter Refresh
- Freshness gegen Erwartung
- verarbeitetes Volumen
- fehlgeschlagene Qualitätsregeln
- Schema Drift
- Incident-Status
- betroffene Reports

### 9. AI oder Data Science verwendet den governten Kontext erneut

Ein Forecasting-Modell nutzt Open-Order-Historie und gewünschte Lieferdaten.

Hier entstehende Metadaten:

- Feature-Definitionen
- Version des Trainingsdatasets
- Transformations-Lineage
- Experimentparameter
- Modellversion
- Evaluationsergebnis
- Deployment-Status
- zulässige Nutzung

Das Modell muss auf dieselben governten Auftragsdefinitionen verweisen. Andernfalls kann die AI-Plattform ein technisch ähnliches Feld mit anderer fachlicher Bedeutung verwenden.

## Metadaten gehen am häufigsten an Übergabepunkten verloren

Das größte Risiko besteht nicht darin, dass Metadaten nie vorhanden waren. Häufig waren sie in einem System vorhanden und verschwanden bei der Übergabe.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/where-metadata-is-born-img4-de.png"
        alt="Metadatenverluste von der Quellbezeichnung über extrahiertes Feld, umbenanntes Modellfeld und semantisches Measure bis zur Dashboard-Kennzahl sowie ein korrigiertes Muster mit erhaltener Provenance"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Kontext geht häufig bei Extraktion, Umbenennung, Transformation und Darstellung verloren. Erhalt von Ursprung, Mapping, Transformationsnachweis und Consumer-Verbindung bewahrt die End-to-End-Bedeutung.
    </figcaption>
</figure>

### Quellbeschreibungen werden nicht exportiert

Ein Connector extrahiert Tabellen und Felder, ignoriert aber Quellbezeichnungen, Wertbeschreibungen oder Hilfetexte der Anwendung.

Ergebnis:

- die physischen Daten kommen an
- das fachliche Vokabular bleibt zurück
- Downstream-Teams bauen Beschreibungen manuell neu auf
- Quell- und Warehouse-Terminologie entwickeln sich auseinander

### Die Begründung der Transformation fehlt

Code zeigt einen Filter oder eine Berechnung, aber nicht den Grund dafür.

Ergebnis:

- Lineage erklärt, was wovon abhängt
- sie erklärt nicht, warum die Regel existiert
- spätere Teams können eine bewusste Geschäftsregel nicht von einem Workaround unterscheiden

### Umbenennung verdeckt das ursprüngliche Feld

Ein Quellfeld wird zur besseren Lesbarkeit umbenannt, ohne den ursprünglichen Identifier zu erhalten.

Ergebnis:

- Downstream-Nutzer erhalten einen klareren Namen
- Source Impact Analysis wird schwieriger
- Änderungen im Quellsystem lassen sich nicht zuverlässig zuordnen

### KPI-Logik ist von Basisfeldern getrennt

Ein semantisches Measure oder eine BI-Formel ist nicht mit Transformationsfeldern und Quellobjekten verbunden.

Ergebnis:

- die KPI wirkt dokumentiert
- Reproduzierbarkeit und Impact-Pfad bleiben unklar
- mehrere lokale Varianten können unbemerkt entstehen

### Nutzungsmetadaten werden nicht zurückgeführt

Reports und Anwendungen nutzen ein Feld, aber Warehouse und Katalog erhalten diese Information nicht.

Ergebnis:

- ungenutzte und kritische Datenobjekte sehen identisch aus
- Änderungsrisiken werden unterschätzt
- Deprecation-Entscheidungen erfolgen ohne Consumer-Evidenz

### Korrigiertes Übergabemuster

Jede Übergabe sollte vier Elemente erhalten:

```text
Ursprung erhalten
+ Mapping erfassen
+ Transformation dokumentieren
+ Consumer verbinden
```

Das bedeutet:

- ursprünglichen Source Identifier erhalten
- explizites Source-to-Target-Mapping anlegen
- Transformationslogik und Begründung dokumentieren
- finales Measure, Report, Anwendung oder Modell verbinden
- Laufzeit- und Nutzungsevidenz in das gemeinsame Profil zurückführen

## Alternative Betriebsmodelle

Unterschiedliche Umgebungen benötigen unterschiedliche Grade der Zentralisierung.

### Quellnahe Metadaten mit gemeinsamen Konventionen

Metadaten bleiben in Datenbanken, Code-Repositories, Pipelines und BI-Modellen.

Geeignet, wenn:

- die Plattformlandschaft klein ist
- Engineering-Disziplin stark ausgeprägt ist
- versionierte Dokumentation bereits etabliert ist
- systemübergreifende Discovery begrenzt, aber beherrschbar ist

Erforderliche Schutzmaßnahmen:

- stabile Identifier
- gemeinsame Metadatenfelder
- Exportfähigkeit
- klare Ownership
- keine undokumentierten lokalen Ausnahmen

### Zentrales Harvesting und Indexing

Eine zentrale Plattform erfasst Metadaten periodisch aus verbundenen Systemen.

Geeignet, wenn:

- Discovery über mehrere Plattformen wichtig ist
- die Organisation systemübergreifende Lineage benötigt
- mehrere Tools nutzbare APIs oder Exporte bereitstellen
- Governance-Teams eine gemeinsame Sicht benötigen

Erforderliche Schutzmaßnahmen:

- Provenance-Felder
- Synchronisationsstatus
- Konfliktbehandlung
- quellenspezifische Erweiterungen
- Sichtbarkeit veralteter oder fehlgeschlagener Harvesting-Läufe

### Event-getriebene Metadatenaktualisierung

Systeme veröffentlichen Metadatenänderungen, sobald Schemas, Modelle, Policies oder Runs verändert werden.

Geeignet, wenn:

- Metadaten sehr aktuell bleiben müssen
- die Plattform zuverlässige Events unterstützt
- operative Automatisierung von Metadaten abhängt
- periodisches Harvesting zu langsam ist

Erforderliche Schutzmaßnahmen:

- idempotente Verarbeitung
- versionierte Events
- Replay-Fähigkeit
- Regeln für Event-Reihenfolge
- Dead-Letter- und Reconciliation-Prozesse

### Föderierte Ownership mit gemeinsamem Modell

Domänen bleiben für Bedeutung und Freigaben verantwortlich, während ein zentrales Team Standards und Verbindungen definiert.

Geeignet, wenn:

- fachliches Wissen verteilt ist
- ein zentrales Dokumentationsteam nicht skalieren kann
- Domänen ihre Datenprodukte verantworten
- Enterprise Policies trotzdem Konsistenz verlangen

Erforderliche Schutzmaßnahmen:

- verpflichtende Mindestmetadaten
- Domain Contracts
- gemeinsame Identifier
- Eskalations- und Konfliktlösungsprozess
- messbare Metadatenqualität

## Typische Anti-Patterns

### Den Katalog als Originalquelle jedes Wertes behandeln

Der Katalog wird zu einer parallelen manuellen Datenbank. Definitionen, Schemas, Owner und Policies driften von den Systemen weg, die sie tatsächlich umsetzen.

### Nur Storage-Metadaten erfassen

Die Organisation erhält ein detailliertes Inventar von Tabellen und Feldern, verpasst aber Prozessbedeutung, Transformationsbegründung, BI-Nutzung, Zugriffsentscheidungen und AI-Abhängigkeiten.

### Alle Metadaten in ein Beschreibungsfeld drücken

Quellbezeichnungen, Fachdefinitionen, technische Hinweise und Consumer-Anzeigenamen werden in einem Textfeld vermischt. Autorität und Verwendungszweck werden unklar.

### Werte ohne Provenance kopieren

Ein zentrales Feld enthält `Confidential`, aber niemand kann erkennen, ob der Wert aus einem Scanner, einem Quell-Tag, einer Steward-Freigabe oder einem Import-Default stammt.

### Erkannt mit freigegeben gleichsetzen

Ein Vorschlag eines Klassifikators wird ohne Review oder Confidence-Behandlung direkt für Maskierung, Retention oder AI-Berechtigung verwendet.

### Nur zur Laufzeit verfügbare Metadaten ignorieren

Der Katalog zeigt Owner und Definitionen, aber keine aktuelle Evidenz zu Freshness, Qualität, Fehlern oder Zugriffen.

### Lineage dokumentieren, aber nicht die Begründung

Die Organisation kann ein Feld durch Modelle verfolgen, aber nicht erklären, warum Filter, Joins oder Geschäftsregeln angewendet wurden.

### Consumer-seitige Logik ignorieren

Lokale BI-Berechnungen, Notebook-Transformationen und Anwendungsregeln fehlen in der Lineage, obwohl sie die an Nutzer gelieferte Bedeutung verändern.

### Verantwortung statt Sichtbarkeit zentralisieren

Ein zentrales Governance-Team soll die fachliche Bedeutung jeder Domäne pflegen. Das Ergebnis sind langsame Aktualisierungen, oberflächliche Definitionen und schwache Accountability.

## Entscheidungshilfe

Die passende Strategie für den Metadatenursprung hängt vom verwalteten Attribut ab.

| Metadatenfrage | Beste primäre Autorität | Geeignete zentrale Rolle |
| --- | --- | --- |
| Was bedeutet die ursprüngliche Transaktion? | Quellanwendung und Business Process Owner | verbinden, indexieren und sichtbar machen |
| Welches Schema existiert aktuell? | Quell- oder Storage-Katalog | erfassen und Versionen vergleichen |
| Wie wurden Daten bewegt? | Ingestion und Orchestrierung | Runs mit Datenobjekten verbinden |
| Wie wurde ein Feld abgeleitet? | Transformationscode und Modellartefakte | parsen, verbinden und darstellen |
| Was zeigt eine KPI? | governte semantische Definition und Business Owner | Formel, Lineage und Freigabe verbinden |
| Wer darf darauf zugreifen? | Identity-, Policy- und Access-Systeme | Entscheidungen mit Assets und Nutzung verbinden |
| Ist das Datenobjekt aktuell vertrauenswürdig? | Quality- und Observability-Evidenz | aggregieren und gegen Erwartungen bewerten |
| Wie wird es genutzt? | BI-, Anwendungs-, Query- und Access-Telemetrie | Consumer-Beziehungen Upstream zurückführen |
| Welche Daten haben ein Modell trainiert? | Data-Science- und Model-Management-Artefakte | Datasets, Features, Versionen und Freigaben verbinden |

Für jedes Metadatenfeld sollten vier Fragen beantwortet werden:

1. **Wo wird dieser Wert ursprünglich erzeugt?**
2. **Welches System oder welche Rolle kann ihn korrekt halten?**
3. **Wie kann er exportiert, referenziert oder beobachtet werden?**
4. **Welche Provenance muss nach der Zentralisierung sichtbar bleiben?**

Die Antwort kann sich selbst innerhalb desselben Datenobjekts von Attribut zu Attribut unterscheiden.

## Wichtigste Empfehlungen

1. Metadatenerzeugung als Lifecycle und nicht als Katalogpflege behandeln.
2. Die ursprüngliche Autorität für fachliche Bedeutung, physische Struktur, Bewegung, Transformation, Nutzung, Zugriff und Laufzeitevidenz getrennt bestimmen.
3. Stabile Identifier über Quelle, Ingestion, Storage, Transformation, Semantik, BI und AI hinweg erhalten.
4. Metadaten mit Quellsystem, Quellreferenz, Ursprungstyp, Version, Zeitstempel, Status und Verantwortung importieren.
5. Generierte, erkannte, beobachtete und menschlich freigegebene Werte unterscheidbar halten.
6. Automatisierung für Schemas, Lineage, Runs, Freshness, Nutzung und technische Evidenz einsetzen, aber nicht als Ersatz für verantwortete Definitionen und Freigaben.
7. Source-to-Target-Mappings erhalten, wenn Felder extrahiert oder umbenannt werden.
8. Transformationslogik und fachliche Begründung dokumentieren.
9. Consumer- und Nutzungsmetadaten Upstream zurückführen, damit Impact und Kritikalität sichtbar bleiben.
10. Eine zentrale Plattform soll Wahrheiten aus anderen Systemen verbinden und fehlende Wahrheit nicht stillschweigend erfinden.
11. Mit einem End-to-End-Datenprodukt beginnen und die vollständige Provenance-Kette nachweisen, bevor skaliert wird.
12. Nicht nur Metadatenvollständigkeit messen, sondern auch Ursprungsabdeckung, Synchronisationsstatus und ungelöste Konflikte.

## Der nächste Schritt: Metadaten möglichst nah an der Quelle halten

Das Verständnis, wo Metadaten entstehen, führt direkt zur nächsten Architekturentscheidung: Wo sollte jeder Metadatenwert gepflegt werden?

Eine zentrale Plattform ist nützlich für Discovery, Beziehungen, Governance-Workflows und Enterprise Impact Analysis. Sie wird unzuverlässig, wenn jede Definition, Klassifikation und technische Information manuell in diese Plattform kopiert und dort gepflegt werden muss.

Der nächste Teil, **Metadaten gehören möglichst nah an die Quelle**, zeigt, wie Metadaten in der Nähe von Code, Anwendung, Modell, Policy oder Team bleiben können, die sie korrekt halten — und trotzdem zentral erfasst und verbunden werden.
