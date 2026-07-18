---
title: Unternehmenswissen für AI — Embeddings, Vektorsuche und RAG
description: Wie Unternehmensinhalte durch Chunking, Metadaten und Embeddings durchsuchbar werden und wie Retrieval-Augmented Generation daraus kontrollierten Kontext für Sprachmodelle erzeugt.
category: Künstliche Intelligenz
tags:
  - ai
  - ai-foundations
  - enterprise-data
  - embeddings
  - vector-search
  - rag
  - retrieval
  - hybrid-search
  - metadata
  - access-control
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 3
seriesTitle: AI Foundations
hero: images/playbooks/ai-rag-hero.png
---

## Das Modell kennt Ihr Unternehmen nicht automatisch

Ein Sprachmodell kann Sprache verarbeiten, Inhalte strukturieren und aus seinem Training allgemeine Muster ableiten. Es kennt jedoch nicht automatisch die aktuellen Richtlinien, Verträge, Tickets, Datenmodelle oder operativen Vorgänge eines Unternehmens.

Selbst ein sehr leistungsfähiges Modell besitzt zunächst nur:

- gelernte Modellparameter
- allgemeine Sprach- und Strukturmuster
- Wissen aus dem Zeitraum und Umfang seines Trainings
- Fähigkeiten, die durch sein Training und seine Modellarchitektur geprägt wurden

Unternehmenswissen liegt dagegen typischerweise in vielen getrennten Systemen:

- Dokumentenplattformen
- Datenbanken und Data Warehouses
- Datenkatalogen
- Richtlinien und Arbeitsanweisungen
- Ticketsystemen
- Wikis und Wissensdatenbanken
- Fachanwendungen
- APIs und operativen Systemen

Diese Inhalte ändern sich unabhängig vom Modell. Eine neue Richtlinie, ein aktualisierter Produktkatalog oder ein gestern gelöstes Supportticket wird nicht automatisch Bestandteil der Modellparameter.

> **Unternehmenswissen muss zur Laufzeit kontrolliert abgerufen und als Kontext für die aktuelle Anfrage bereitgestellt werden.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-rag-img1-de.png"
        alt="Vergleich zwischen dem allgemeinen Wissen eines Sprachmodells und aktuellem Unternehmenswissen, das durch Retrieval und Kontext zur Laufzeit bereitgestellt wird"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das Modell besitzt allgemeine gelernte Fähigkeiten. Unternehmensspezifische Informationen werden erst durch Retrieval gesucht und in den Prompt-Kontext eingebracht.
    </figcaption>
</figure>

Dieser Unterschied ist die Grundlage von **Retrieval-Augmented Generation**, kurz **RAG**.

## Was RAG tatsächlich bedeutet

RAG kombiniert zwei getrennte Fähigkeiten:

1. **Retrieval:** Relevante Informationen werden aus einem kontrollierten Wissensbestand gesucht.
2. **Generation:** Ein Sprachmodell verwendet die gefundenen Informationen als zusätzlichen Kontext für seine Antwort.

```flowchart
Frage
Suche
Relevante Evidenz
Modellkontext
Generierte Antwort
```

Das Sprachmodell beantwortet die Frage dabei nicht direkt aus einer Vektordatenbank. Die Suche liefert Dokumentabschnitte, Datensätze oder andere Evidenz. Diese Inhalte werden gemeinsam mit der Frage und den Anweisungen an das Modell übergeben.

Ein vereinfachter Prompt könnte intern so aufgebaut sein:

```text
Systemanweisung:
Beantworte die Frage ausschließlich anhand der bereitgestellten Quellen.
Nenne die verwendeten Quellen. Weise auf fehlende Evidenz hin.

Benutzerfrage:
Welche Regeln gelten für Remote-Arbeit?

Abgerufene Evidenz:
[Quelle A, Abschnitt 3]
[Quelle B, Abschnitt 7]

Aufgabe:
Erzeuge eine präzise Antwort mit Quellenangaben.
```

Das Modell wird dadurch im Normalfall nicht dauerhaft neu trainiert. Die abgerufenen Inhalte gelten nur für die aktuelle Anfrage und belegen Platz im Kontextfenster.

## Embeddings übersetzen Inhalte in einen Suchraum

Klassische Suchverfahren vergleichen hauptsächlich Begriffe, Wortformen und Zeichenfolgen. Das funktioniert gut bei eindeutigen Namen, IDs und Fachbegriffen. Es reicht jedoch nicht immer aus, wenn Frage und Dokument denselben Sachverhalt unterschiedlich formulieren.

Beispiel:

```text
Frage:
Dürfen Beschäftigte von zu Hause arbeiten?

Dokument:
Berechtigte Mitarbeitende können ihre Tätigkeit im Rahmen
der mobilen Arbeitsvereinbarung außerhalb des Standorts ausüben.
```

Eine reine Stichwortsuche muss passende Begriffe oder Synonyme kennen. Eine semantische Suche versucht dagegen, die inhaltliche Nähe beider Texte zu erkennen.

Dafür werden **Embeddings** verwendet. Ein Embedding-Modell wandelt Text oder andere Inhalte in einen numerischen Vektor um.

```text
Textabschnitt
→ Embedding-Modell
→ [0.21, -0.41, 0.73, ...]
```

Diese Zahlen sind keine lesbare Zusammenfassung des Inhalts. Sie bilden eine Position in einem hochdimensionalen Raum. Inhalte mit ähnlicher Bedeutung sollen dort näher beieinander liegen als inhaltlich unähnliche Inhalte.

Typischer Ablauf:

```flowchart
Dokumentabschnitt
Embedding
Vektor
Index
Ähnlichkeitssuche
```

Wichtig ist:

- Ein Embedding ist keine Faktenprüfung.
- Ein hoher Ähnlichkeitswert garantiert keine fachliche Relevanz.
- Unterschiedliche Embedding-Modelle erzeugen unterschiedliche Vektorräume.
- Dokumente und Suchanfragen müssen mit kompatiblen Modellen und Konfigurationen verarbeitet werden.
- Bei einem Wechsel des Embedding-Modells kann eine Neuindexierung erforderlich sein.

Embeddings machen Inhalte semantisch auffindbar. Sie ersetzen weder Metadaten noch Berechtigungen noch klassische Suche.

## Von Dokumenten zu durchsuchbarem Wissen

Eine produktive RAG-Lösung beginnt nicht bei der Frage des Benutzers. Sie beginnt mit der Vorbereitung der Quellen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-rag-img2-de.png"
        alt="Pipeline von Dokumenten und Daten über Extraktion, Bereinigung, Chunking, Metadaten und Embeddings bis zum Vektorindex"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        RAG-Qualität entsteht bereits bei der Inhaltsvorbereitung. Chunks, Metadaten, Embeddings und Indexierung bestimmen, welche Evidenz später gefunden werden kann.
    </figcaption>
</figure>

### 1. Quellen anbinden

Mögliche Quellen sind:

- PDF-, Office- und Textdokumente
- Wiki-Seiten
- Tabellen und Kalkulationen
- Datenbankabfragen
- Tickets und Vorgänge
- Datenkataloge
- Richtlinienarchive
- Webseiten
- APIs
- strukturierte Exporte

Nicht jede Quelle sollte identisch verarbeitet werden. Ein Vertrag besitzt andere Strukturen als ein Supportticket, eine Datentabelle oder ein Datenkatalogeintrag.

### 2. Inhalte extrahieren

Die relevante Information muss aus dem Quellformat gelesen werden.

Dazu können gehören:

- Parser für PDF und Office-Dateien
- Konnektoren zu Plattformen und APIs
- Tabellen- und Layout-Erkennung
- OCR bei gescannten Dokumenten
- Extraktion von Überschriften, Abschnitten und Listen
- Übernahme bestehender Quellmetadaten

Das Ziel ist nicht nur „möglichst viel Text“. Die ursprüngliche Dokumentstruktur sollte soweit sinnvoll erhalten bleiben.

### 3. Bereinigen und strukturieren

Extrahierte Inhalte können technische Artefakte enthalten:

- wiederholte Kopf- und Fußzeilen
- Navigationsbestandteile
- unvollständige Tabellen
- fehlerhafte Zeilenumbrüche
- doppelte Inhalte
- Zeichencodierungsfehler
- veraltete oder ungültige Versionen

Die Bereinigung entscheidet mit darüber, ob später brauchbare Chunks entstehen.

### 4. Inhalte in Chunks teilen

Große Dokumente werden typischerweise in kleinere Einheiten zerlegt. Diese Einheiten werden häufig **Chunks** genannt.

Ein guter Chunk sollte:

- fachlich zusammenhängend sein
- ausreichend Kontext enthalten
- nicht unnötig groß sein
- auf eine nachvollziehbare Quelle zurückführen
- möglichst keine zentrale Aussage in der Mitte trennen

Mögliche Strategien:

| Strategie | Eignung |
| --- | --- |
| Feste Zeichen- oder Tokenlänge | Einfach, aber ohne Verständnis der Dokumentstruktur |
| Absätze | Gut bei sauber strukturiertem Fließtext |
| Überschriften und Abschnitte | Gut für Richtlinien, Handbücher und Dokumentationen |
| Tabellenzeilen oder Datengruppen | Geeignet für strukturierte Inhalte |
| Semantisches Chunking | Gruppiert Inhalte anhand inhaltlicher Übergänge |
| Parent-Child-Chunking | Sucht kleine Einheiten, liefert aber größeren Ursprungskontext |

Es gibt keine universell richtige Chunk-Größe. Zu kleine Chunks verlieren Kontext. Zu große Chunks verbrauchen mehr Tokens und können irrelevante Inhalte in den Modellkontext bringen.

### 5. Metadaten ergänzen

Metadaten sind für Enterprise RAG mindestens so wichtig wie Embeddings.

Beispielhafte Felder:

- Dokument-ID
- Titel
- Quellsystem
- Abteilung
- Owner
- Sprache
- Dokumenttyp
- Version
- Klassifikation
- Gültigkeitszeitraum
- Erstellungs- und Änderungsdatum
- Mandant
- Land oder Region
- Zugriffsgruppen
- ursprüngliche URL oder Dateiposition

Metadaten unterstützen:

- Filterung
- Zugriffskontrolle
- Quellenangaben
- Versionierung
- Lifecycle Management
- fachliche Eingrenzung
- Fehlersuche

Eine semantisch ähnliche, aber abgelaufene Richtlinie darf nicht automatisch vor der aktuellen Version erscheinen.

### 6. Embeddings erzeugen

Für jeden suchbaren Chunk wird eine Vektorrepräsentation erzeugt. Je nach Architektur können zusätzlich erzeugt werden:

- dense embeddings für semantische Suche
- sparse representations für tokenbasierte Suche
- Dokumentzusammenfassungen
- Schlüsselbegriffe
- Fragen, die der Chunk beantworten kann
- zusätzliche Ranking-Merkmale

### 7. Indexieren und speichern

Ein Suchindex enthält typischerweise nicht nur den Vektor.

```text
Chunk-ID
Originaltext
Embedding
Dokument-ID
Metadaten
Berechtigungsinformationen
Quellposition
Versionsinformationen
```

Originaldokumente können getrennt vom Suchindex gespeichert bleiben. Der Index verweist dann auf die Quelle und hält nur die für Retrieval benötigten Einheiten.

## Die Frage wird ebenfalls repräsentiert

Bei einer Anfrage wird nicht einfach der vollständige Dokumentbestand an das Sprachmodell geschickt.

Die Frage wird analysiert und häufig ebenfalls in ein Embedding umgewandelt:

```text
Benutzerfrage
→ Query Embedding
→ Ähnlichkeitssuche
→ passende Chunks
```

Eine produktive Lösung kann die ursprüngliche Frage zusätzlich:

- sprachlich normalisieren
- in Teilfragen zerlegen
- um fehlenden Konversationskontext ergänzen
- mit Synonymen oder Fachbegriffen erweitern
- nach Intention und Entität klassifizieren
- an unterschiedliche Datenquellen routen

Diese Schritte werden häufig als **Query Understanding**, **Query Rewriting** oder **Query Planning** bezeichnet.

## Von der Frage zur fundierten Antwort

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-rag-img3-de.png"
        alt="RAG-Ablauf von der Benutzerfrage über Query Embedding, Vektorsuche, Metadaten- und Zugriffsfilter, Chunk-Auswahl und Prompt-Aufbau bis zur Antwort mit Quellen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Retrieval stellt Evidenz bereit. Das Sprachmodell verarbeitet Frage, Evidenz und Anweisungen anschließend als gemeinsamen Modellkontext.
    </figcaption>
</figure>

Der Ablauf lässt sich in sieben Schritte zerlegen.

### 1. Benutzerfrage erfassen

Neben dem Text der Frage können relevant sein:

- Identität des Benutzers
- Rolle und Gruppenmitgliedschaften
- Sprache
- aktuelle Anwendung
- Mandant
- bisheriger Dialog
- gewünschtes Antwortformat

### 2. Query Embedding erzeugen

Das Embedding bildet die semantische Bedeutung der Suchanfrage im gleichen oder kompatiblen Vektorraum wie die Dokument-Chunks ab.

### 3. Suchindex durchsuchen

Eine reine Vektorsuche ist nicht immer ausreichend. Produktnummern, Abkürzungen, Personennamen oder neue interne Begriffe lassen sich häufig besser durch Stichwortsuche finden.

Deshalb wird in Enterprise-Systemen oft **hybride Suche** eingesetzt:

```text
Semantische Vektorsuche
+
Stichwort- oder Volltextsuche
+
optionale strukturierte Datenabfrage
=
kombinierte Treffermenge
```

### 4. Metadaten und Berechtigungen anwenden

Filter können sowohl vor als auch während des Retrievals eingesetzt werden:

```text
classification = internal
department = finance
valid_from <= today
valid_to >= today
country = DE
access_group contains current_user_group
```

Berechtigungen dürfen nicht erst nach der Antwort geprüft werden. Nicht autorisierte Inhalte sollten möglichst gar nicht in die Treffermenge oder den Modellkontext gelangen.

### 5. Relevante Chunks auswählen und ranken

Die erste Suche liefert Kandidaten. Anschließend können die Ergebnisse:

- dedupliziert
- nach Aktualität bewertet
- fachlich diversifiziert
- anhand eines Rerankers neu sortiert
- auf Widersprüche geprüft
- auf ein Token-Budget begrenzt

werden.

Ein hoher Vektor-Score allein reicht für diese Entscheidung häufig nicht aus.

### 6. Angereicherten Prompt aufbauen

Der Modellkontext besteht typischerweise aus:

```text
System- und Sicherheitsanweisungen
+
Benutzerfrage
+
abgerufene Evidenz
+
Quellenmetadaten
+
gewünschtes Ausgabeformat
```

Das Kontextfenster bleibt begrenzt. Die Anwendung muss daher entscheiden, welche Treffer vollständig, gekürzt oder zusammengefasst übernommen werden.

### 7. Antwort mit Quellen erzeugen

Das Modell erzeugt die Antwort anhand des bereitgestellten Kontexts. Quellen können auf verschiedene Weise ausgegeben werden:

- Dokumenttitel
- direkte URL
- Abschnitt oder Seitenzahl
- Chunk-ID
- Datensatzreferenz
- Zeitstempel
- Quellversion

Eine Quellenangabe macht eine Antwort nachvollziehbarer. Sie garantiert jedoch nicht automatisch, dass jeder Satz korrekt aus der Quelle abgeleitet wurde. Verifikation und Evaluation bleiben erforderlich.

## RAG ist mehr als eine Vektordatenbank

Eine Demo kann aus einer Datei, einem Vektorindex und einem Sprachmodell bestehen. Eine produktive Unternehmenslösung benötigt weitere Schichten.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-rag-img4-de.png"
        alt="Produktive RAG-Architektur mit Anfrageverständnis, Identität, Zugriffskontrolle, hybrider Suche, Metadatenfilterung, Ranking, Kontextaufbau, Sprachmodell, Quellen und unterstützenden Betriebsfunktionen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Retrieval, Sicherheit, Metadaten, Ranking, Kontextaufbau und Validierung müssen als kontrolliertes Gesamtsystem zusammenarbeiten.
    </figcaption>
</figure>

### Anfrageverständnis

Die Anwendung muss verstehen, welche Art von Information gesucht wird und welche Quelle geeignet ist.

Beispiel:

```text
„Wie hoch war der Umsatz gestern?“
```

Diese Frage gehört wahrscheinlich nicht in eine statische Dokumentensuche. Sie benötigt eine autorisierte Abfrage gegen ein aktuelles operatives System oder Data Warehouse.

Dagegen eignet sich:

```text
„Welche Definition verwendet das Unternehmen für Nettoumsatz?“
```

eher für einen Datenkatalog, eine KPI-Dokumentation oder eine Governance-Richtlinie.

### Identität und Zugriffskontrolle

Eine RAG-Anwendung benötigt die Identität des Benutzers und muss Berechtigungen bis auf die relevante Datenebene durchsetzen.

Mögliche Kontrollen:

- Single Sign-on
- Rollen und Gruppen
- Mandantentrennung
- Dokument- und Zeilenberechtigungen
- Datenklassifikation
- Maskierung
- regionale Einschränkungen
- Zweckbindung

Das Modell darf die Zugriffskontrolle nicht selbst „interpretieren“. Die Anwendung und Retrieval-Schicht müssen sie technisch durchsetzen.

### Hybrides Retrieval

Semantische und lexikalische Suche ergänzen sich.

Semantische Suche ist stark bei:

- ähnlicher Bedeutung
- natürlichen Fragen
- mehrsprachigen Formulierungen
- konzeptioneller Nähe

Stichwortsuche ist stark bei:

- IDs
- Produktnummern
- Eigennamen
- Abkürzungen
- neuen internen Begriffen
- exakten Formulierungen

### Ranking und Relevanz

Eine produktive Suche kann mehrere Stufen verwenden:

```flow linear vertical
Schnelle Kandidatensuche
Zusammenführen mehrerer Suchwege
Metadaten- und Berechtigungsfilter
Reranking
Kontextauswahl
```

### Kontextaufbau

Die Kontextschicht muss:

- Dubletten vermeiden
- Quellgrenzen sichtbar halten
- Tokenlimits beachten
- aktuelle Versionen bevorzugen
- Systemanweisungen schützen
- widersprüchliche Quellen handhaben
- Zitationsinformationen erhalten

### Monitoring und Evaluation

Zu beobachten sind nicht nur Modellantworten, sondern der gesamte Retrieval-Prozess:

- Suchanfrage
- ausgewählte Quellen
- Retrieval-Scores
- angewandte Filter
- Antwortlatenz
- Tokenverbrauch
- Quellenabdeckung
- Benutzerfeedback
- Fehlermeldungen
- Modell-, Prompt- und Indexversion

### Dokumenten- und Index-Lifecycle

Ein RAG-System benötigt geregelte Prozesse für:

- neue Inhalte
- Änderungen
- Löschung
- Archivierung
- Aufbewahrungsfristen
- Re-Embedding
- Neuindexierung
- Versionierung
- Rollback

Wird ein Dokument gelöscht oder verliert ein Benutzer die Berechtigung, muss diese Änderung auch im Suchindex wirksam werden.

## RAG ist nicht für jede Datenquelle der richtige Zugriff

Embedding-basiertes Retrieval eignet sich besonders für unstrukturierte oder semistrukturierte Wissensbestände.

Bei aktuellen, transaktionalen oder exakt zu berechnenden Daten ist häufig ein Tool- oder API-Aufruf geeigneter.

| Frage | Geeigneter Zugriff |
| --- | --- |
| Was besagt unsere Reisekostenrichtlinie? | RAG über kontrollierte Dokumente |
| Welche KPI-Definition gilt für Net Revenue? | RAG über Katalog und Governance-Dokumentation |
| Wie hoch ist der heutige Umsatz? | Autorisierte Datenbank- oder BI-Abfrage |
| Wie lautet der Status von Auftrag 4711? | API oder operatives System |
| Welche Incidents ähneln diesem Fehler? | Hybride Suche über Tickets |
| Storniere den Auftrag | Kontrollierte Tool-Aktion, nicht nur RAG |

Eine moderne AI-Anwendung kann RAG und Tools kombinieren:

```flowchart
Frage verstehen
Wissenssuche
Live-Daten abrufen
Ergebnisse validieren
Antwort erzeugen
Aktion freigeben
```

## Langer Kontext, RAG und Fine-Tuning sind unterschiedliche Werkzeuge

Diese drei Ansätze werden häufig vermischt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-rag-img5-de.png"
        alt="Vergleich von langem Kontext, Retrieval-Augmented Generation und Fine-Tuning nach Zweck, Wissensbereitstellung, Aktualisierung, Stärke, Begrenzung und Unternehmenseinsatz"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Langer Kontext, RAG und Fine-Tuning ergänzen sich. Sie lösen unterschiedliche Probleme und können in einer Anwendung kombiniert werden.
    </figcaption>
</figure>

### Langer Kontext

Beim Long-Context-Ansatz werden ausgewählte Inhalte direkt mit der Anfrage an das Modell geschickt.

Geeignet für:

- wenige Dokumente
- einmalige Analysen
- klar begrenzte Aufgaben
- schnelle Prototypen
- direkten Dokumentvergleich

Vorteile:

- einfache Architektur
- kein Retrieval-Index erforderlich
- vollständiger Inhalt kann direkt verarbeitet werden

Grenzen:

- hoher Tokenverbrauch
- steigende Latenz
- begrenzte Kontextkapazität
- irrelevante Inhalte können die Verarbeitung erschweren
- Berechtigungen und Quellenmanagement bleiben trotzdem erforderlich

### RAG

RAG sucht bei jeder Anfrage relevante Inhalte aus einem größeren Wissensbestand.

Geeignet für:

- häufig aktualisierte Unternehmensdokumente
- viele Quellen
- Knowledge Assistants
- Support- und Richtlinienwissen
- Antworten mit Quellen

Vorteile:

- selektiver Kontext
- Wissen kann unabhängig vom Modell aktualisiert werden
- bessere Nachvollziehbarkeit durch Quellen
- Zugriffskontrolle und Metadatenfilter sind integrierbar

Grenzen:

- zusätzliche Retrieval-Infrastruktur
- Qualität hängt von Quellen, Chunking und Ranking ab
- Index und Berechtigungen müssen aktuell gehalten werden
- komplexere Evaluation

### Fine-Tuning

Beim Fine-Tuning werden Modellparameter durch zusätzliche Trainingsbeispiele angepasst.

Geeignet für:

- konsistente Formate
- wiederkehrenden Stil
- spezialisierte Klassifikationsmuster
- domänenspezifisches Antwortverhalten
- häufig wiederkehrende Aufgaben

Fine-Tuning ist nicht der bevorzugte Weg, um täglich veränderliche Fakten in ein Modell einzubauen. Aktuelles Wissen lässt sich über RAG oder Tools leichter austauschen und kontrollieren.

## Die Ansätze können kombiniert werden

Eine Anwendung kann alle drei Methoden verwenden:

```flow linear vertical
Fine-Tuned Model für konsistentes Verhalten
RAG für aktuelle kontrollierte Evidenz
Long Context für die ausgewählten Quelldokumente
Tool Calls für Live-Daten und Aktionen
```

Beispiel:

Ein Vertragsassistent verwendet ein auf juristische Ausgabeformate optimiertes Modell. RAG sucht relevante Richtlinien und Vertragsklauseln. Der vollständige aktuelle Vertrag wird zusätzlich im Kontext bereitgestellt. Ein Tool liest autorisierte Stammdaten aus dem Vertragssystem.

Die Architektur folgt damit nicht der Frage „Welche Technologie gewinnt?“, sondern:

> **Welche Information benötigt die Aufgabe, wie aktuell muss sie sein, wie wird sie autorisiert und wie soll das Modell damit arbeiten?**

## Ein technisches Zielbild für Enterprise RAG

```flow linear vertical
Governed enterprise sources
Extraction and normalization
Chunking and metadata enrichment
Embeddings and search indexes
Identity-aware hybrid retrieval
Ranking and context assembly
Language model generation
Citations, validation and feedback
Monitoring, lifecycle and versioning
```

Die eigentliche Modellanfrage ist nur ein kleiner Teil dieses Flows.

## Was für einen RAG-Use-Case dokumentiert werden sollte

| Bereich | Zu dokumentieren |
| --- | --- |
| **Use Case** | Zweck, Benutzergruppen und erlaubte Fragen |
| **Quellen** | Systeme, Owner, Datenklassifikation und Aktualisierungsfrequenz |
| **Ingestion** | Extraktionsverfahren, Bereinigung und Fehlerbehandlung |
| **Chunking** | Strategie, Größen, Überlappung und Parent-Child-Beziehungen |
| **Metadaten** | Pflichtfelder, Filter und Quellreferenzen |
| **Embeddings** | Anbieter, Modell, Version und Vektordimension |
| **Index** | Technologie, Partitionierung und Aktualisierungsverfahren |
| **Retrieval** | Vektor-, Stichwort- und strukturierte Suche |
| **Ranking** | Scores, Reranker, Schwellenwerte und Top-k |
| **Access** | Identität, Gruppen, Dokument- und Datenberechtigungen |
| **Prompt** | Anweisungen, Kontextformat und Quellenregeln |
| **Modell** | Anbieter, Modellversion und Konfiguration |
| **Evaluation** | Retrieval-Relevanz, Groundedness und Antwortqualität |
| **Lifecycle** | Re-Embedding, Löschung, Aufbewahrung und Rollback |
| **Monitoring** | Latenz, Kosten, Fehler, Quellen und Benutzerfeedback |

## Die zentrale Erkenntnis

> **RAG verbindet ein Sprachmodell mit kontrolliertem Unternehmenswissen. Embeddings und Vektorsuche helfen, semantisch relevante Inhalte zu finden. Erst Chunking, Metadaten, Zugriffskontrolle, Ranking, Kontextaufbau und Lifecycle Management machen daraus eine belastbare Unternehmensarchitektur.**

RAG repariert keine schlechten Quellen. Es macht fehlende Ownership nicht überflüssig und darf Berechtigungen nicht umgehen. Es kann jedoch eine kontrollierte Brücke zwischen Sprachmodellen und aktuellem Unternehmenswissen bilden.

## Die Serie im Überblick

```flow linear vertical
AI Foundations — How AI Works [done]
Language Models and Providers [done]
Enterprise Data, Embeddings and RAG [active]
AI Agents and Automation
Known Problems and Failure Modes
Evaluation, Costs and Operations
AI Governance
```

Part 4 betrachtet als Nächstes **AI Agents and Automation**: Wie Modelle Tools auswählen, Workflows planen, Aktionen ausführen und warum ein Agent mehr Kontrollmechanismen benötigt als ein normaler Chat.

## Quellen und weiterführende Dokumentation

- [OpenAI API — Vector Embeddings](https://developers.openai.com/api/docs/guides/embeddings)
- [OpenAI API — Retrieval](https://developers.openai.com/api/docs/guides/retrieval)
- [OpenAI API — Model Optimization and Fine-Tuning](https://developers.openai.com/api/docs/guides/model-optimization)
- [Microsoft Learn — Retrieval-Augmented Generation in Azure AI Search](https://learn.microsoft.com/en-us/azure/search/retrieval-augmented-generation-overview)
- [Microsoft Learn — RAG Evaluators](https://learn.microsoft.com/en-us/azure/foundry/concepts/evaluation-evaluators/rag-evaluators)
- [Google Cloud — Embeddings APIs for Search and RAG](https://docs.cloud.google.com/generative-ai-app-builder/docs/builder-apis)
- [Google Cloud — Hybrid Search](https://docs.cloud.google.com/gemini-enterprise-agent-platform/build/vector-search/about-hybrid-search)
- [Google Cloud — Vector Database Choices in RAG Engine](https://docs.cloud.google.com/gemini-enterprise-agent-platform/build/rag-engine/vector-db-choices)
- [Anthropic — Glossary: Context Window, Fine-Tuning and RAG](https://platform.claude.com/docs/en/about-claude/glossary)
