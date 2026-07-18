---
title: AI-Grundlagen — Wie KI wirklich funktioniert
description: Eine verständliche technische Einführung in den Unterschied zwischen klassischer Software und Machine Learning, den Trainingsprozess, Inferenz, Modellparameter und die schrittweise Erzeugung generativer Ausgaben.
category: Künstliche Intelligenz
tags:
  - ai
  - ai-foundations
  - artificial-intelligence
  - machine-learning
  - deep-learning
  - neural-networks
  - model-training
  - inference
  - generative-ai
  - model-parameters
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 1
seriesTitle: AI Foundations
hero: images/playbooks/ai-basics-hero.png
---

## KI ist keine einzelne Technologie

Wenn heute von „AI“ oder „KI“ gesprochen wird, ist häufig direkt ein Chatbot oder ein Sprachmodell gemeint. Künstliche Intelligenz ist jedoch ein wesentlich breiterer Sammelbegriff.

Dazu gehören unter anderem:

- regelbasierte Systeme
- statistische Verfahren
- Machine Learning
- neuronale Netze und Deep Learning
- Computer Vision
- Sprach- und Audiomodelle
- generative Modelle
- Systeme, die Vorhersagen, Klassifikationen oder Empfehlungen erzeugen

Nicht jedes KI-System generiert Texte. Nicht jedes Machine-Learning-Modell ist ein neuronales Netz. Und nicht jedes neuronale Netz ist ein Sprachmodell.

Die gemeinsame Idee moderner Machine-Learning-Verfahren lautet:

> **Die gewünschte Logik wird nicht vollständig als feste Regel programmiert. Ein Modell lernt relevante Muster aus Beispielen und einer definierten Zielsetzung.**

Dieses erste Playbook der Serie konzentriert sich auf genau dieses Grundprinzip. Sprachmodelle, RAG, Agenten, bekannte Fehlerbilder, Evaluation und Governance folgen in eigenen Teilen.

## Von programmierten Regeln zu gelernten Mustern

Klassische Software verarbeitet eine Eingabe anhand explizit implementierter Regeln.

Ein stark vereinfachtes Beispiel:

```text
Wenn Rechnungsbetrag > 10.000
und Lieferland ungleich Kundenland,
dann markiere die Transaktion zur Prüfung.
```

Ein Entwickler oder Fachexperte muss festlegen:

- welche Merkmale relevant sind
- wie sie kombiniert werden
- welche Grenzwerte gelten
- welches Ergebnis erzeugt wird

Beim Machine Learning wird nicht jede Entscheidungsregel einzeln vorgegeben. Stattdessen erhält ein Lernverfahren Beispiele und ein Ziel. Daraus werden mathematische Zusammenhänge abgeleitet, die später auf neue Eingaben angewendet werden können.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-basics-img1-de.png"
        alt="Vergleich zwischen klassischer Software mit expliziten Regeln und Machine Learning, das aus Beispielen und erwarteten Ergebnissen ein Modell erzeugt"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Klassische Software folgt einer programmierten Logik. Machine Learning erzeugt ein Modell, indem es Muster aus Beispielen und einer definierten Zielsetzung ableitet.
    </figcaption>
</figure>

Das bedeutet nicht, dass Machine Learning ohne Regeln auskommt. Auch ein ML-System benötigt weiterhin explizite Entscheidungen:

- Welche Daten werden verwendet?
- Was ist die Zielvariable?
- Welche Modellarchitektur wird gewählt?
- Wie wird Erfolg gemessen?
- Welche Eingaben und Ausgaben sind erlaubt?
- Wann gilt ein Ergebnis als ausreichend gut?

Der Unterschied liegt darin, **wo die fachliche Logik entsteht**. Bei klassischer Software steckt sie primär im geschriebenen Programmcode. Beim Machine Learning steckt ein wesentlicher Teil in den gelernten Modellparametern.

## Was ist ein Modell?

Ein Modell ist vereinfacht betrachtet eine parametrisierte mathematische Funktion.

Es erhält eine Eingabe und berechnet daraus eine Ausgabe:

```text
Eingabe → Modell → Ergebnis
```

Die Eingabe kann sehr unterschiedlich aussehen:

- Tabellenzeilen
- Messwerte
- Texte
- Bilder
- Audio
- Ereignisfolgen
- Kombinationen mehrerer Datentypen

Auch die Ausgabe hängt vom Zweck ab:

- Kategorie
- numerischer Wert
- Wahrscheinlichkeit oder Score
- erkannte Struktur
- Text
- Bild
- Code
- Audio

Die Struktur des Modells wird durch seine Architektur bestimmt. Sein im Training erworbenes Verhalten steckt in den **Parametern** beziehungsweise **Gewichten**.

| Begriff | Vereinfachte Bedeutung |
| --- | --- |
| **Architektur** | Aufbau des Modells und Art der möglichen Berechnungen |
| **Parameter / Gewichte** | Im Training angepasste numerische Werte |
| **Eingaberepräsentation** | Numerische Form, in die Text, Bilder oder Tabellen übersetzt werden |
| **Ziel oder Objective** | Was das Modell im Training verbessern soll |
| **Loss / Fehlermaß** | Messwert für die Abweichung zwischen erzeugtem und gewünschtem Ergebnis |
| **Optimizer** | Verfahren, das Parameter anhand des Fehlers verändert |
| **Trainiertes Modell** | Architektur plus gelernte Parameter |

Ein Modell ist deshalb weder nur eine Sammlung von Regeln noch einfach eine Datenbank mit gespeicherten Antworten. Es ist eine berechenbare Struktur, deren Verhalten durch viele numerische Parameter geprägt wird.

## Wie ein Modell entsteht

Ein typischer Trainingsprozess beginnt mit Beispielen. Das Modell verarbeitet eine Eingabe und erzeugt zunächst eine Vorhersage. Diese wird mit einem Ziel verglichen. Aus der Abweichung wird ein Fehlerwert berechnet. Anschließend werden die Parameter so verändert, dass der Fehler bei ähnlichen Beispielen künftig kleiner wird.

Dieser Ablauf wird sehr oft wiederholt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-basics-img2-de.png"
        alt="Kontinuierlicher Trainingskreislauf aus Trainingsdaten, Vorhersage, Vergleich mit dem erwarteten Ergebnis, Fehlerberechnung, Parameteranpassung und trainiertem Modell"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das Schaubild zeigt den Grundgedanken eines überwachten Lernverfahrens: vorhersagen, vergleichen, Fehler berechnen, Parameter anpassen und den Vorgang wiederholen.
    </figcaption>
</figure>

Der dargestellte Prozess entspricht dem **überwachten Lernen**. Dabei existieren Beispiele mit bekannten oder fachlich festgelegten Ergebnissen.

Beispiele:

- E-Mail → Kategorie
- Transaktion → Betrugsfall ja oder nein
- Maschinezustand → erwartete Restlaufzeit
- Bild → erkannte Objektklasse

Viele moderne generative Modelle werden zusätzlich oder primär **selbstüberwacht** trainiert. Dabei werden Lernziele direkt aus großen Datenmengen abgeleitet. Ein Sprachmodell kann beispielsweise lernen, fehlende oder nachfolgende Textbestandteile vorherzusagen. Dafür muss nicht jeder Satz manuell durch einen Menschen beschriftet werden.

Weitere Lernformen sind:

- **Unüberwachtes Lernen:** Strukturen, Cluster oder Repräsentationen ohne explizite Zielklasse erkennen
- **Reinforcement Learning:** Verhalten anhand von Belohnungen und Rückmeldungen optimieren
- **Fine-Tuning:** Ein bereits trainiertes Modell für einen engeren Zweck weiter anpassen

Die Verfahren unterscheiden sich. Das Kernprinzip bleibt: Ein Optimierungsprozess verändert Parameter, damit das Modell sein definiertes Ziel besser erfüllt.

## Was „Lernen“ technisch bedeutet

Ein Modell lernt nicht wie ein Mensch durch bewusste Einsicht. Im technischen Sinn bedeutet Lernen:

1. Eine Eingabe wird numerisch repräsentiert.
2. Das Modell berechnet daraus ein Ergebnis.
3. Eine Zielfunktion bewertet dieses Ergebnis.
4. Ein Optimierungsverfahren bestimmt, wie Parameter verändert werden sollen.
5. Die aktualisierten Parameter beeinflussen den nächsten Durchlauf.

Bei neuronalen Netzen wird der Einfluss der Parameter typischerweise mit Verfahren wie **Backpropagation** und einer Variante des **Gradientenverfahrens** berechnet.

Der Begriff „neuronales Netz“ ist biologisch inspiriert, aber das technische System ist kein digitales menschliches Gehirn. Ein künstliches Neuron ist im Wesentlichen eine mathematische Recheneinheit. Viele solcher Einheiten werden zu Schichten und größeren Architekturen verbunden.

Deep Learning bedeutet, dass ein Modell aus vielen Verarbeitungsschichten besteht und zunehmend komplexe Repräsentationen lernen kann.

Beispielsweise können bei einem Bildmodell frühe Schichten lokale Kontraste und Kanten erfassen, spätere Schichten komplexere Formen oder Objektmerkmale. Bei Sprachmodellen entstehen numerische Repräsentationen für Tokens, Beziehungen und Kontextmuster.

## Training und Inferenz sind zwei verschiedene Phasen

Ein häufiger Denkfehler besteht darin, Training und Nutzung des Modells gleichzusetzen.

Beim **Training** wird das Modell verändert.

Bei der **Inferenz** wird ein trainiertes Modell auf eine neue Eingabe angewendet. Seine Parameter bleiben dabei im normalen Betrieb unverändert.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-basics-img3-de.png"
        alt="Gegenüberstellung von Training mit historischen Beispielen und Parameteranpassung sowie Inferenz mit neuer Eingabe und berechnetem Ergebnis"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Training baut oder verändert das Modell. Inferenz verwendet das trainierte Modell, um für einen neuen Fall ein Ergebnis zu berechnen.
    </figcaption>
</figure>

| Training | Inferenz |
| --- | --- |
| verarbeitet viele Trainingsbeispiele | verarbeitet neue Eingaben |
| berechnet Fehler oder Lernsignale | berechnet ein Ergebnis |
| verändert Modellparameter | verwendet vorhandene Parameter |
| benötigt häufig hohe Rechenleistung | ist meist auf schnelle Antwortzeiten optimiert |
| findet einmalig, periodisch oder kontinuierlich statt | findet bei jeder produktiven Nutzung statt |
| erzeugt eine neue Modellversion | erzeugt eine Vorhersage oder Ausgabe |

Diese Trennung ist architektonisch relevant.

Ein Unternehmen kann ein Modell selbst trainieren, ein vorhandenes Modell weiter anpassen oder ein extern betriebenes Modell ausschließlich per Inferenz verwenden. In allen drei Fällen sieht die sichtbare Anwendung möglicherweise ähnlich aus, während Datenfluss, Kosten, Infrastruktur und Verantwortlichkeiten stark variieren.

```flowchart
Training data
Learning process
Trained model
New input
Inference
Result
```

## Ein Modell kann unterschiedliche Arten von Aufgaben erfüllen

„KI“ bezeichnet nicht nur generative Systeme. Modelle werden für verschiedene Aufgabenklassen entwickelt oder angepasst.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-basics-img4-de.png"
        alt="Ein Modell verarbeitet unterschiedliche Eingabeformen und unterstützt Klassifikation, Vorhersage, Erkennung und Generierung mit konkreten Beispielen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die Art der Aufgabe wird durch Architektur, Training, Eingaben und Nutzung der Ausgabe bestimmt. In der Praxis können generalistische Modelle mehrere Aufgaben unterstützen, während andere Modelle bewusst spezialisiert werden.
    </figcaption>
</figure>

### Klassifikation

Das Modell ordnet eine Eingabe einer vorgegebenen Kategorie zu.

Beispiele:

- Supportanfrage → Rechnung, Umzug oder Störung
- Dokument → Vertragstyp
- Produktbild → Produktklasse
- Kundenvorgang → Prioritätsstufe

### Vorhersage oder Regression

Das Modell berechnet einen numerischen Wert oder einen erwarteten zukünftigen Zustand.

Beispiele:

- erwarteter Absatz
- Lieferzeit
- Ausfallwahrscheinlichkeit
- Kundenabwanderungs-Score
- voraussichtlicher Energieverbrauch

### Erkennung

Das Modell identifiziert bestimmte Strukturen, Ereignisse oder Auffälligkeiten.

Beispiele:

- ungewöhnliche Transaktion
- Defekt in einem Produktbild
- ungewöhnliche Sensorwerte
- erkannte Entitäten in einem Dokument

### Generierung

Das Modell erzeugt neue Inhalte.

Beispiele:

- Text
- Bilder
- Programmcode
- Audio
- Zusammenfassungen
- Entwürfe und Varianten

Das Schaubild vereinfacht bewusst. Nicht jedes einzelne trainierte Modell kann automatisch alle vier Aufgaben erfüllen. Häufig wird dieselbe grundlegende Architektur für verschiedene Ziele trainiert, ein Basismodell für mehrere Aufgaben angepasst oder ein generalistisches multimodales Modell mit unterschiedlichen Eingaben und Ausgaben verwendet.

## Wahrscheinlichkeiten statt fest codierter Gewissheit

Viele Modelle erzeugen intern nicht einfach nur eine endgültige Antwort. Sie berechnen Scores oder Wahrscheinlichkeitsverteilungen.

Bei einer Klassifikation könnte ein Modell beispielsweise bewerten:

```text
Rechnung      0,74
Vertrag       0,17
Sonstiges     0,09
```

Die Anwendung kann anschließend:

- die höchste Kategorie auswählen
- einen Mindestwert verlangen
- mehrere Vorschläge anzeigen
- einen Fall zur manuellen Prüfung weiterleiten
- die Wahrscheinlichkeiten in eine weitere Berechnung einbeziehen

Bei generativen Modellen beeinflusst die Wahrscheinlichkeitsverteilung, welches nächste Ausgabeelement ausgewählt wird. Je nach Konfiguration kann die Ausgabe stärker auf das wahrscheinlichste Element fokussiert oder variabler erzeugt werden.

Damit unterscheidet sich ein Modell von einer festen Entscheidungsregel. Das Ergebnis entsteht aus gelernten Zusammenhängen, Eingabe, Kontext und Ausgabekonfiguration.

## Wie generative KI eine Ausgabe aufbaut

Generative KI erzeugt eine Ausgabe normalerweise nicht als fertigen Block aus einer gespeicherten Antwort.

Bei einem Sprachmodell beginnt der Prozess mit einer Eingabe, dem Prompt. Das Modell berechnet, welches nächste Token im aktuellen Kontext passend ist. Das ausgewählte Token wird Teil des Kontextes. Danach wird das nächste Token berechnet. Dieser Prozess wiederholt sich, bis die Ausgabe beendet wird.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-basics-img5-de.png"
        alt="Schrittweiser Aufbau einer generativen Ausgabe vom Prompt über erste Ausgabeelemente und wachsenden Kontext bis zum vollständigen Ergebnis"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Generative Ausgaben entstehen iterativ. Bei Text und Code werden typischerweise Tokens schrittweise ergänzt; andere Modellfamilien verwenden andere technische Einheiten und Erzeugungsverfahren.
    </figcaption>
</figure>

Ein **Token** ist nicht zwingend ein vollständiges Wort. Es kann ein Wort, Wortbestandteil, Satzzeichen oder anderes Textelement sein.

Ein vereinfachter Ablauf:

```text
Prompt:
„Schreibe eine kurze Antwort an einen Kunden.“

Kontext 1:
„Vielen“

Kontext 2:
„Vielen Dank“

Kontext 3:
„Vielen Dank für Ihre“

Kontext 4:
„Vielen Dank für Ihre Nachricht.“
```

Jeder neue Bestandteil verändert den Kontext für die nächste Berechnung.

Für andere Medien gilt derselbe allgemeine Gedanke einer schrittweisen Erzeugung, aber nicht zwingend derselbe technische Mechanismus:

- **Text und Code:** häufig Token für Token
- **Bilder:** beispielsweise iterative Entrauschung bei Diffusionsmodellen oder Erzeugung visueller Tokens
- **Audio:** je nach Modell Audio-Tokens, Frames, Segmente oder Samples
- **Video:** zeitliche und visuelle Repräsentationen über mehrere Erzeugungsschritte

Generative KI ist deshalb keine einfache Suchfunktion. Sie konstruiert ein neues Ergebnis aus den gelernten Modellparametern und dem aktuellen Kontext.

## Was ein trainiertes Modell enthält — und was nicht

Nach dem Training besteht ein Modell im Kern aus:

- einer definierten Architektur
- numerischen Parametern
- Konfigurationen für Ein- und Ausgabe
- häufig einer Vorverarbeitung wie Tokenizer oder Feature-Transformation
- einer konkreten Modellversion

Die Trainingsdaten selbst sind nicht automatisch als direkt abfragbare Datensätze im Modell enthalten. Das Modell verdichtet statistische Strukturen in seinen Parametern.

Diese Aussage ist jedoch nicht absolut mit „das Modell speichert niemals Inhalte“ gleichzusetzen. Große Modelle können einzelne Trainingsfragmente oder seltene Muster teilweise memorisieren. Das ist technisch und später auch für Datenschutz und Governance relevant, gehört aber nicht zum Kern dieser Grundlagenstory.

Für das mentale Modell ist entscheidend:

> **Das Modell sucht bei einer normalen Inferenz nicht einfach die passende Trainingszeile heraus. Es berechnet eine neue Ausgabe mit den im Training angepassten Parametern.**

## Dasselbe Grundmuster hinter sehr unterschiedlichen Systemen

Eine Produktempfehlung, eine Bilderkennung, ein Forecast und ein Sprachmodell wirken aus Anwendersicht sehr verschieden.

Technisch teilen sie ein gemeinsames Grundmuster:

1. Eine reale Aufgabe wird in Ein- und Ausgaben übersetzt.
2. Daten werden in numerische Repräsentationen transformiert.
3. Eine Modellarchitektur verarbeitet diese Repräsentationen.
4. Ein Trainingsziel definiert, welches Verhalten verbessert wird.
5. Ein Optimierungsverfahren passt Parameter an.
6. Das trainierte Modell wird auf neue Eingaben angewendet.
7. Die Anwendung interpretiert oder verwendet das Ergebnis.

Die Qualität eines KI-Systems hängt deshalb nicht nur von der Größe eines Modells ab. Entscheidend sind auch:

- Eignung der Aufgabe für Machine Learning
- Repräsentation der Eingaben
- Trainingsziel
- Datenabdeckung
- Modellarchitektur
- technische Integration
- Interpretation und Verwendung der Ausgabe

Diese Themen werden in den weiteren Teilen der Serie schrittweise vertieft.


