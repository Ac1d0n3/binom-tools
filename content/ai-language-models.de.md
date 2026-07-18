---
title: AI-Sprachmodelle — Funktionsweise, Anbieter und Modellauswahl
description: Wie Sprachmodelle Prompts tokenisieren, Kontext verarbeiten und Antworten erzeugen – mit einem Vergleich von Anbieterfamilien, Zugriffsmodellen und Kriterien für die Auswahl.
category: Künstliche Intelligenz
tags:
  - ai
  - ai-foundations
  - language-models
  - llm
  - tokens
  - transformer
  - context-window
  - open-weight
  - self-hosted-ai
  - model-selection
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 2
seriesTitle: AI Foundations
hero: images/playbooks/ai-language-models-hero.png
---

## Sprachmodelle sind keine Wissensdatenbanken

Im ersten Teil dieser Serie ging es um das Grundprinzip von Machine Learning: Ein Modell wird anhand von Daten und einer Zielsetzung trainiert, seine Parameter werden optimiert und anschließend während der Inferenz auf neue Eingaben angewendet.

Ein Sprachmodell ist eine spezialisierte Ausprägung dieses Prinzips. Es verarbeitet Sprache nicht direkt als menschliche Bedeutung, sondern übersetzt Texte zunächst in numerische Repräsentationen. Auf dieser Basis berechnet es, welche Fortsetzung im aktuellen Kontext wahrscheinlich und passend ist.

> **Ein Sprachmodell ruft normalerweise keine fertige Antwort aus einer Datenbank ab. Es erzeugt die Ausgabe schrittweise aus dem Prompt, dem verfügbaren Kontext und seinen trainierten Parametern.**

Moderne Sprachmodelle können weit mehr als einfache Textfortsetzungen. Sie schreiben, analysieren, strukturieren, übersetzen und programmieren. Multimodale Varianten können zusätzlich Bilder, Audio, Video oder Bildschirmansichten verarbeiten. Mit Tools und APIs können sie außerdem Daten abrufen, Berechnungen ausführen und Aktionen vorbereiten.

Trotzdem bleibt das Modell selbst zunächst eine berechenbare Funktion:

```text
Eingabe + Kontext + Modellparameter
→ Wahrscheinlichkeitsberechnung
→ nächste Ausgabeeinheit
→ wachsender Kontext
→ vollständige Antwort
```

## Vom Prompt zur Antwort

Der sichtbare Ausgangspunkt ist der **Prompt**. Dazu gehören nicht nur die letzten Worte des Benutzers. Je nach Anwendung können auch Systemanweisungen, frühere Nachrichten, Dokumente, Tool-Ergebnisse und weitere Metadaten Teil der Eingabe sein.

Bevor ein Sprachmodell Text verarbeiten kann, wird dieser durch einen **Tokenizer** in kleinere Einheiten zerlegt. Diese Einheiten heißen Tokens.

Ein Token kann sein:

- ein vollständiges Wort
- ein Wortbestandteil
- ein Satzzeichen
- eine Zahl
- ein Leerzeichen oder Formatierungselement
- bei multimodalen Modellen eine andere codierte Eingabeeinheit

Tokens werden anschließend in numerische Vektoren übersetzt. Diese Repräsentationen durchlaufen die Schichten des Modells. Bei heutigen großen Sprachmodellen basiert die Architektur häufig auf dem **Transformer-Prinzip**.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-language-models-img1-de.png"
        alt="Technischer Ablauf von einem Prompt über Tokenisierung, numerische Repräsentation und Transformer-Schichten bis zur schrittweise erzeugten Antwort"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das Sprachmodell zerlegt die Eingabe in Tokens, verarbeitet deren Beziehungen im Kontext und berechnet jeweils das nächste Ausgabeelement.
    </figcaption>
</figure>

Der dargestellte Ablauf ist bewusst vereinfacht:

1. Der Prompt wird entgegengenommen.
2. Der Text wird tokenisiert.
3. Tokens werden in numerische Repräsentationen übersetzt.
4. Die Modellschichten verarbeiten Beziehungen und Kontext.
5. Das Modell berechnet eine Verteilung möglicher nächster Tokens.
6. Ein Token wird anhand der Modell- und Sampling-Konfiguration ausgewählt.
7. Das neue Token wird Teil des Kontexts.
8. Der Prozess wiederholt sich bis zum Ende der Antwort.

Das Modell sagt daher nicht nur einmal eine vollständige Antwort voraus. Es führt während der Ausgabe viele aufeinanderfolgende Inferenzschritte aus.

## Was Transformer-Schichten leisten

Transformer-Modelle verwenden sogenannte **Attention-Mechanismen**, um Beziehungen zwischen den Bestandteilen einer Eingabe zu gewichten.

Vereinfacht gefragt:

- Welche vorherigen Tokens sind für das aktuelle Token relevant?
- Welche Begriffe beziehen sich aufeinander?
- Welche Anweisung bestimmt das gewünschte Format?
- Welche Informationen aus einem Dokument sind für die Frage wichtig?
- Welche Teile des bisherigen Outputs beeinflussen die Fortsetzung?

Das bedeutet nicht, dass das Modell den Text wie ein Mensch bewusst interpretiert. Attention ist eine mathematische Gewichtung innerhalb der Modellberechnung.

Ein Prompt wie:

```text
Fasse den Vertrag zusammen und liste ausschließlich
die Kündigungsfristen als Tabelle auf.
```

enthält mehrere unterschiedliche Anforderungen:

- Aufgabe: zusammenfassen
- Einschränkung: ausschließlich Kündigungsfristen
- Ausgabeformat: Tabelle
- Informationsquelle: Vertrag

Ein leistungsfähiges Sprachmodell muss diese Bestandteile im verfügbaren Kontext miteinander verknüpfen.

## Wahrscheinlichkeiten, Sampling und reproduzierbare Ergebnisse

Nach der Verarbeitung berechnet das Modell mögliche nächste Tokens mit unterschiedlichen Scores beziehungsweise Wahrscheinlichkeiten.

Ein stark vereinfachtes Beispiel:

```text
Vielen      0,42
Gerne       0,18
Die         0,12
Nachfolgend 0,08
...
```

Die Anwendung kann die Auswahl zusätzlich beeinflussen. Typische Parameter sind:

| Parameter | Vereinfachte Wirkung |
| --- | --- |
| **Temperature** | Steuert, wie stark wahrscheinlichere oder variablere Fortsetzungen bevorzugt werden |
| **Top-p** | Beschränkt die Auswahl auf eine Wahrscheinlichkeitsmenge |
| **Maximum output tokens** | Begrenzt die Länge der Ausgabe |
| **Stop conditions** | Beenden die Ausgabe bei definierten Sequenzen |
| **Seed**, sofern unterstützt | Kann die Wiederholbarkeit bestimmter Ausgaben verbessern |
| **Structured output / schema** | Schränkt das Ausgabeformat beispielsweise auf gültiges JSON ein |

Eine niedrige Temperature macht ein System nicht automatisch fachlich korrekt. Sie reduziert vor allem die Variation bei der Auswahl. Ebenso bedeutet eine hohe Modellwahrscheinlichkeit nicht, dass eine Aussage objektiv wahr ist.

Die vertiefte Behandlung von Halluzinationen, Bias, Prompt Injection und weiteren Fehlerbildern folgt in einem späteren Teil der Serie.

## Das Kontextfenster ist der Arbeitsbereich einer Anfrage

Ein Sprachmodell verarbeitet nicht unbegrenzt viel Inhalt gleichzeitig. Jede Modellvariante besitzt ein **Kontextfenster**.

Das Kontextfenster umfasst je nach API und Anwendung unter anderem:

- Systemanweisungen
- Entwickler- oder Anwendungsanweisungen
- aktuellen Benutzer-Prompt
- relevanten Konversationsverlauf
- abgerufene Dokumente
- Tool-Definitionen
- Tool-Ergebnisse
- Metadaten und strukturierte Eingaben
- bereits erzeugte Teile der Antwort

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-language-models-img2-de.png"
        alt="Darstellung des begrenzten Kontextfensters mit Systemanweisungen, Benutzer-Prompt, Konversationsverlauf, abgerufenen Dokumenten, Tool-Ergebnissen und generierter Antwort"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Alle Bestandteile einer Anfrage teilen sich ein gemeinsames Token-Budget. Die im Schaubild verwendeten Mengen sind illustrative Beispiele und keine Spezifikation eines bestimmten Modells.
    </figcaption>
</figure>

Ein größeres Kontextfenster ermöglicht es, mehr Material in einer Anfrage bereitzustellen. Es garantiert jedoch nicht automatisch bessere Ergebnisse.

Sehr große Kontexte können:

- höhere Kosten verursachen
- die Latenz erhöhen
- irrelevante Informationen enthalten
- widersprüchliche Anweisungen mitführen
- relevante Stellen schwerer auffindbar machen
- die verfügbare Ausgabelänge reduzieren

Deshalb ist **Kontextmanagement** wichtiger als bloß möglichst viel Kontext.

Eine gute Anwendung entscheidet bewusst:

- Welche Teile des Chatverlaufs werden benötigt?
- Welche Dokumentabschnitte sind wirklich relevant?
- Welche Tool-Ergebnisse müssen vollständig übernommen werden?
- Welche Inhalte lassen sich zusammenfassen?
- Welche Anweisungen müssen dauerhaft erhalten bleiben?
- Welche Informationen dürfen aus Datenschutzgründen nicht an das Modell gehen?

## Kontextfenster ist nicht gleich Modellwissen

Zwei Arten von Wissen sollten getrennt werden.

### Wissen in den Modellparametern

Das Modell hat während seines Trainings statistische Strukturen aus Trainingsdaten gelernt. Dieses Wissen ist nicht wie eine relationale Datenbank gezielt abfragbar und besitzt keinen automatisch aktuellen Wahrheitsstatus.

### Wissen im aktuellen Kontext

Zusätzliche Informationen können zur Laufzeit mitgegeben werden:

- hochgeladene Dateien
- Suchergebnisse
- Daten aus Unternehmenssystemen
- Inhalte aus einem Datenkatalog
- Ergebnisse von APIs
- strukturierte Datensätze
- Ergebnisse einer Vektorsuche

Das Modell wird dadurch im Normalfall nicht dauerhaft neu trainiert. Es erhält lediglich zusätzlichen Kontext für die aktuelle Anfrage.

Die Verbindung eines Sprachmodells mit Unternehmenswissen über Embeddings, Retrieval und RAG ist das Thema von **Part 3** dieser Serie.

## Nicht jeder Anbieter ist ein einzelnes Modell

OpenAI, Anthropic, Google, Meta und Mistral sind Anbieter beziehungsweise Organisationen mit mehreren Modellfamilien, Varianten und Plattformangeboten.

Innerhalb einer Familie können sich Modelle erheblich unterscheiden:

- allgemeines Modell versus Reasoning-Modell
- schnelle Variante versus leistungsfähige Variante
- Textmodell versus multimodales Modell
- kleines lokales Modell versus großes Cloud-Modell
- Modell für Coding versus Modell für Echtzeit-Audio
- aktuelle Version versus ältere Version
- API-Modell versus Open-Weight-Modell

Die Aussage „Anbieter A ist besser als Anbieter B“ ist deshalb meist zu unpräzise.

Eine technisch belastbare Auswahl benennt mindestens:

```text
Anbieter
+ Modellfamilie
+ konkrete Modellversion
+ Zugriffsweg
+ Betriebsform
+ Konfiguration
+ Testdatensatz
+ Qualitätsmetriken
```

## Drei unterschiedliche Ebenen: Anbieter, Gewichte und Betrieb

Ein häufiger Fehler besteht darin, **Closed API**, **Open Weight** und **Self-Hosted** als drei vollständig getrennte Modellarten zu behandeln. Tatsächlich beschreiben sie unterschiedliche Aspekte.

### Closed API

Das Modell wird über eine API oder einen gemanagten Dienst verwendet. Der Anbieter betreibt die Modellinfrastruktur und kontrolliert die Gewichte sowie die Modellupdates.

Typische Vorteile:

- schneller Einstieg
- geringe eigene Infrastrukturverantwortung
- gemanagte Skalierung
- oft direkte Verfügbarkeit neuer Funktionen
- integrierte Sicherheits- und Enterprise-Funktionen

Typische Einschränkungen:

- Abhängigkeit vom Anbieter
- begrenzte Einsicht in Gewichte und Training
- Daten verlassen je nach Architektur die eigene Betriebsumgebung
- Preise, Limits und Modellversionen können sich ändern
- lokale Ausführung ist meist nicht möglich

### Open-Weight-Modell

Die trainierten Modellgewichte sind verfügbar und können innerhalb der jeweiligen Lizenz selbst betrieben oder durch einen Cloud-Dienst bereitgestellt werden.

Open Weight bedeutet nicht automatisch:

- vollständig Open Source
- freie Nutzung für jeden Zweck
- Veröffentlichung der Trainingsdaten
- Veröffentlichung des Trainingscodes
- uneingeschränkte kommerzielle Nutzung

Die konkrete Lizenz bleibt entscheidend.

### Self-Hosted

Self-Hosted beschreibt die Betriebsform. Ein Unternehmen betreibt das Modell in einer eigenen oder kontrollierten Umgebung.

Mögliche Umgebungen:

- eigenes Rechenzentrum
- private Cloud
- dedizierter Cloud-Account
- Edge-Gerät
- Arbeitsplatzrechner
- isolierte Forschungsumgebung

Self-Hosting ist typischerweise mit Open-Weight-Modellen möglich. Ein Anbieter kann jedoch zusätzlich selbst gehostete Enterprise-Varianten oder dedizierte Bereitstellungen anbieten.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-language-models-img3-de.png"
        alt="Vergleich von geschlossener API, Open-Weight-Modell und selbst gehostetem Modell anhand von Zugriff, Kontrolle, Infrastruktur, Anpassbarkeit, Datenschutz, Time-to-Value und Kostenmodell"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Modellzugriff und Betriebsmodell bestimmen gemeinsam, wie schnell eine Lösung startet und wie viel Kontrolle, Infrastrukturverantwortung und Anpassbarkeit beim Unternehmen liegen.
    </figcaption>
</figure>

Die reale Architektur liegt häufig zwischen den Extremen:

```text
Anwendung im eigenen Netzwerk
→ eigener AI Gateway
→ Pseudonymisierung und Policy-Prüfung
→ externer Modellanbieter
→ kontrollierte Antwortverarbeitung
```

oder:

```text
Anwendung
→ selbst gehostetes Open-Weight-Modell
→ externer API-Fallback für komplexe Aufgaben
```

Ein hybrider Ansatz kann sinnvoller sein als die pauschale Entscheidung „alles extern“ oder „alles selbst hosten“.

## Wie sich die großen Modellfamilien typischerweise unterscheiden

Die Modelllandschaft ändert sich schnell. Neue Versionen können Stärken, Kontextgrößen, Kosten und Verfügbarkeit innerhalb weniger Monate verschieben.

Das folgende Schaubild ist deshalb kein dauerhaftes Benchmark-Ranking. Es zeigt typische Profile der Anbieterfamilien zum Zeitpunkt der Veröffentlichung.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-language-models-img4-de.png"
        alt="Illustrativer Vergleich typischer Stärken der Modellfamilien von OpenAI, Anthropic, Google, Meta und Mistral nach Schreiben, Reasoning, Coding, langen Dokumenten, Multimodalität, Tool-Integration, Enterprise-Kontrollen und lokaler Bereitstellung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die Profile fassen typische Eigenschaften aktueller Modellfamilien zusammen. Sie ersetzen keine Evaluation einer konkreten Modellversion im eigenen Anwendungsfall.
    </figcaption>
</figure>

### OpenAI GPT-Familie

Typisches Profil:

- breite Generalisten für Text, Reasoning, Coding und multimodale Aufgaben
- starke API- und Tool-Integration
- gemanagte Plattform- und Enterprise-Funktionen
- verschiedene Leistungs- und Kostenklassen
- proprietärer Betrieb ohne frei verfügbare Gewichte der zentralen GPT-Familie

### Anthropic Claude-Familie

Typisches Profil:

- starke Textarbeit und Dokumentanalyse
- ausgeprägte Nutzung langer Kontexte
- Reasoning-, Coding- und Tool-Funktionen
- API-basierte und gemanagte Nutzung
- proprietäre Modellgewichte

### Google Gemini-Familie

Typisches Profil:

- multimodale Modellfamilien
- Integration von Text, Bild, Audio und weiteren Modalitäten je nach Modell
- große Kontextfenster bei ausgewählten Varianten
- Function Calling sowie integrierte Google-Tools und Agentenangebote
- Bereitstellung über Google-Dienste und APIs

### Meta Llama-Familie

Typisches Profil:

- offen verfügbare Modellgewichte unter der jeweiligen Llama-Lizenz
- breites Ökosystem aus Cloud-, Hosting- und Inferenzanbietern
- lokale oder kontrollierte Bereitstellung möglich
- unterschiedliche Größen und spezialisierte Varianten
- Infrastruktur, Sicherheitskontrollen und Produktintegration liegen beim Betreiber oder Plattformpartner

### Mistral-Modellfamilien

Typisches Profil:

- Kombination aus kommerziellen und Open-Weight-Modellen
- Modelle für allgemeine Aufgaben, Coding und spezialisierte Anwendungsfälle
- API-, Cloud- und Self-Deployment-Optionen
- lokale Bereitstellung ausgewählter Modelle
- flexible Integration, aber eigener Betriebsaufwand bei Self-Hosting

Diese Profile sind bewusst qualitativ. Einzelne Versionen können von der Familientendenz abweichen.

## Warum öffentliche Benchmarks nicht ausreichen

Benchmarks sind nützlich, aber kein vollständiger Ersatz für reale Tests.

Ein Benchmark misst typischerweise:

- eine definierte Aufgabenklasse
- einen festen Datensatz
- ein bestimmtes Auswertungsverfahren
- eine konkrete Modell- und Prompt-Konfiguration
- häufig eine begrenzte Zahl von Sprachen oder Domänen

Im produktiven Einsatz kommen weitere Faktoren hinzu:

- unternehmensspezifische Begriffe
- lange und unstrukturierte Dokumente
- unterschiedliche Benutzerformulierungen
- Tabellen, Bilder und Dateianhänge
- interne Sicherheitsregeln
- RAG und Tool-Aufrufe
- strukturierte Ausgaben
- Antwortzeit
- Kosten
- Fehlerbehandlung
- Reproduzierbarkeit
- Datenresidenz
- Verfügbarkeit und Rate Limits

Deshalb kann ein Modell mit dem besseren allgemeinen Benchmark im eigenen Prozess schlechter abschneiden.

## Das Modell nach Aufgabe auswählen, nicht nach Marke

Die Modellauswahl sollte mit dem Anwendungsfall beginnen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-language-models-img5-de.png"
        alt="Entscheidungsrahmen zur Auswahl eines Sprachmodells anhand von Aufgabe, Anforderungen, Zielkonflikten, Betriebsmodell und Tests mit realen Daten"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die richtige Wahl entsteht aus Anforderungen und messbaren Tests. Markenbekanntheit allein ist kein Auswahlkriterium.
    </figcaption>
</figure>

### 1. Aufgabe präzise definieren

Nicht:

> Wir benötigen das beste AI-Modell.

Sondern beispielsweise:

> Das System soll deutschsprachige Service-E-Mails einer von zwölf Kategorien zuordnen, eine Antwortvorlage erzeugen und unklare Fälle zur manuellen Prüfung weiterleiten.

### 2. Eingaben und Ausgaben definieren

- reine Texte oder multimodale Eingaben?
- kurze Prompts oder lange Dokumente?
- freie Antwort oder festes JSON-Schema?
- einzelne Anfrage oder langer Dialog?
- Tool-Aufrufe oder reine Textausgabe?
- deutsch, englisch oder mehrsprachig?

### 3. Nichtfunktionale Anforderungen festlegen

- maximale Antwortzeit
- erwartetes Anfragevolumen
- Kostenbudget
- Datenresidenz
- erlaubte Anbieter
- Verfügbarkeit
- Auditierbarkeit
- lokale Betriebsanforderung
- Modell- und Prompt-Versionierung
- notwendige Sicherheitskontrollen

### 4. Kandidaten auswählen

Nicht jede verfügbare Modellversion muss getestet werden. Sinnvoll ist eine Shortlist aus unterschiedlichen Profilen:

- leistungsfähiges proprietäres Modell
- schnelleres und günstigeres proprietäres Modell
- geeignetes Open-Weight-Modell
- gegebenenfalls spezialisiertes Coding-, Vision- oder Audio-Modell

### 5. Mit realen Fällen evaluieren

Der Testdatensatz sollte reale Schwierigkeiten enthalten:

- typische Fälle
- Grenzfälle
- unvollständige Informationen
- ungewöhnliche Formulierungen
- lange Eingaben
- fachliche Mehrdeutigkeiten
- unerlaubte Anforderungen
- Tool-Fehler
- strukturierte Ausgaben
- sensible Daten

### 6. Das Gesamtsystem bewerten

Nicht nur die Modellantwort zählt.

```text
Gesamtqualität
=
Modell
+ Prompt
+ Kontext
+ Retrieval
+ Tools
+ Validierung
+ Benutzeroberfläche
+ Prozesskontrollen
```

Ein kleineres Modell in einer gut gestalteten Anwendung kann ein größeres Modell in einer schlecht kontrollierten Architektur übertreffen.

## Ein praktisches Bewertungsraster

| Dimension | Beispielhafte Messung |
| --- | --- |
| **Fachliche Korrektheit** | Anteil korrekter Ergebnisse anhand eines Golden Datasets |
| **Aufgabenerfüllung** | Werden alle geforderten Schritte und Einschränkungen eingehalten? |
| **Groundedness** | Ist die Antwort durch bereitgestellte Quellen belegt? |
| **Formatqualität** | Anteil valider strukturierter Ausgaben |
| **Vollständigkeit** | Werden alle erforderlichen Informationen berücksichtigt? |
| **Latenz** | Median sowie 95. und 99. Perzentil |
| **Kosten** | Kosten pro erfolgreichem Vorgang statt nur pro Token |
| **Tool-Zuverlässigkeit** | Erfolgsrate von Function Calls und Folgeaktionen |
| **Sicherheit** | Verhalten bei Prompt Injection, Datenabfluss und unzulässigen Aufgaben |
| **Betriebsfähigkeit** | Monitoring, Versionierung, Fallback und Rollback |

Der spätere Teil **Evaluation, Costs and Operations** vertieft diese Messgrößen und den produktiven Betrieb.

## Warum Multi-Model-Architekturen sinnvoll sein können

Die Wahl muss nicht dauerhaft auf genau ein Modell fallen.

Eine Anwendung kann Aufgaben routen:

```text
Einfache Klassifikation
→ kleines schnelles Modell

Komplexe Dokumentanalyse
→ leistungsfähiges Long-Context-Modell

Sensibler interner Inhalt
→ selbst gehostetes Modell

Code-Review
→ spezialisiertes Coding-Modell

Ausfall oder Rate Limit
→ freigegebenes Fallback-Modell
```

Vorteile:

- Kosten besser steuern
- Antwortzeiten reduzieren
- sensible Aufgaben getrennt behandeln
- Abhängigkeit von einem Anbieter verringern
- spezialisierte Modelle gezielt einsetzen

Nachteile:

- höhere technische Komplexität
- mehr Evaluationen
- unterschiedliche Ausgabeformate
- zusätzliche Versionierungs- und Governance-Anforderungen
- schwerere Fehleranalyse
- mögliche Inkonsistenzen zwischen Modellen

Ein Model Router ist deshalb keine automatische Optimierung. Er benötigt Regeln, Telemetrie, Tests und klare Fallback-Strategien.

## Was Unternehmen dokumentieren sollten

Für jede produktive Modellnutzung sollten mindestens folgende Informationen nachvollziehbar sein:

| Bereich | Zu dokumentieren |
| --- | --- |
| **Use Case** | Zweck, Benutzergruppen und erlaubte Nutzung |
| **Modell** | Anbieter, Familie, konkrete Version und Endpunkt |
| **Betrieb** | API, Cloud, Self-Hosted oder Hybrid |
| **Daten** | Eingaben, Klassifikation, Speicherorte und Übertragungswege |
| **Kontext** | System-Prompt, RAG-Quellen, Tools und maximale Größen |
| **Konfiguration** | Sampling, Ausgabelimits und strukturierte Formate |
| **Evaluation** | Testdatensatz, Metriken, Schwellenwerte und letzte Freigabe |
| **Fallback** | Verhalten bei Fehler, Unsicherheit oder Nichtverfügbarkeit |
| **Verantwortung** | Owner, technische Betreuung und fachliche Freigabe |
| **Versionierung** | Modell-, Prompt-, Tool- und Datenquellenversion |

Diese Anforderungen führen später direkt zur AI Governance. Governance beginnt jedoch nicht erst mit einer Richtlinie. Sie beginnt bereits bei der technisch eindeutigen Beschreibung des Systems.

## Die Serie im Überblick

```flow linear vertical
AI Foundations — How AI Works [done]
Language Models and Providers [active]
Enterprise Data, Embeddings and RAG
AI Agents and Automation
Known Problems and Failure Modes
Evaluation, Costs and Operations
AI Governance
```

Die zentrale Erkenntnis aus Part 2 lautet:

> **Ein Sprachmodell erzeugt Antworten Token für Token innerhalb eines begrenzten Kontextfensters. Anbieterfamilien unterscheiden sich in Fähigkeiten, Zugriff, Kontrolle und Betriebsoptionen. Das beste Modell ist nicht das bekannteste, sondern das Modell, das einen klar definierten Anwendungsfall unter realen Bedingungen zuverlässig erfüllt.**

Part 3 untersucht als Nächstes, wie Sprachmodelle über Embeddings, Vektorsuche und Retrieval-Augmented Generation mit kontrolliertem Unternehmenswissen verbunden werden.

## Quellen und weiterführende Dokumentation

Die Modelllandschaft verändert sich schnell. Die folgenden offiziellen Dokumentationen sollten vor einer konkreten Auswahl erneut geprüft werden:

- [OpenAI API — Models](https://developers.openai.com/api/docs/models)
- [Anthropic — Context windows](https://docs.anthropic.com/en/docs/build-with-claude/context-windows)
- [Anthropic — Tool use](https://docs.anthropic.com/en/docs/build-with-claude/tool-use/overview)
- [Google Gemini API — Models](https://ai.google.dev/gemini-api/docs/models)
- [Google Gemini API — Long context](https://ai.google.dev/gemini-api/docs/long-context)
- [Google Gemini API — Function calling](https://ai.google.dev/gemini-api/docs/function-calling)
- [Meta — Models and libraries](https://ai.meta.com/resources/models-and-libraries/)
- [Meta — Llama 4](https://ai.meta.com/blog/llama-4-multimodal-intelligence/)
- [Mistral AI — Models](https://docs.mistral.ai/models)
- [Mistral AI — Self-Deployment](https://docs.mistral.ai/models/deployment/local-deployment)
