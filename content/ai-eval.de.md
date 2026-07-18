---
title: AI evaluieren und betreiben – Qualität, Kosten und Produktionsbetrieb
description: Wie AI-Systeme mit reproduzierbaren Testfällen, mehrstufiger Evaluation, versionierten Konfigurationen und kontinuierlichem Produktionsmonitoring messbar und kontrollierbar werden.
category: Künstliche Intelligenz
tags:
  - ai
  - ai-foundations
  - evaluation
  - llm-evaluation
  - rag-evaluation
  - agent-evaluation
  - observability
  - monitoring
  - ai-operations
  - mlops
  - cost-management
  - ai-governance
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 6
seriesTitle: AI Foundations
hero: images/playbooks/ai-eval-hero.png
---

## Vom überzeugenden Prototyp zum messbaren System

Ein AI-Prototyp kann innerhalb weniger Stunden beeindruckende Ergebnisse liefern. Einige vorbereitete Fragen funktionieren, eine Demo sieht flüssig aus und das Modell erzeugt Antworten, die fachlich plausibel wirken.

Damit ist jedoch noch nicht belegt, dass das System:

- reale Benutzerfragen zuverlässig abdeckt,
- die richtigen Unternehmensquellen findet,
- Vorgaben und Ausgabeformate einhält,
- bei schwierigen Fällen kontrolliert reagiert,
- Tools mit korrekten Parametern aufruft,
- innerhalb akzeptabler Zeit antwortet,
- wirtschaftlich betrieben werden kann,
- Modell- oder Datenänderungen ohne Regression übersteht.

Subjektive Eindrücke reichen dafür nicht aus.

> **Ein AI-System ist erst produktionsreif, wenn Qualität, Risiken, Kosten und Verhalten messbar, versioniert und überwachbar sind.**

Evaluation ist deshalb keine abschließende Prüfung kurz vor dem Go-live. Sie ist eine technische und fachliche Disziplin, die den gesamten Lebenszyklus begleitet:

```flow linear vertical
Use Case und Erfolgskriterien definieren
Komponenten isoliert testen
System mit realistischen Fällen evaluieren
Workflows und Fehlerpfade prüfen
Kontrolliert produktiv einsetzen
Produktionsverhalten kontinuierlich beobachten
Erkenntnisse in Daten, Prompts, Modelle und Prozesse zurückführen
```

Die Aufgabe besteht nicht darin, eine einzige globale AI-Qualitätszahl zu erzeugen. Entscheidend ist, die relevanten Eigenschaften des konkreten Systems getrennt zu messen und anschließend zu einer belastbaren Freigabeentscheidung zusammenzuführen.

## Nicht nur das Modell evaluieren

Ein produktives AI-System besteht selten nur aus einem Sprachmodell. Die Ausgabe entsteht aus einer Kette von Komponenten:

```text
Benutzereingabe
→ Prompt und Kontextaufbereitung
→ Retrieval
→ Sprachmodell
→ Tool-Auswahl und Aktionen
→ Validierung
→ Geschäftsprozess
```

Jede dieser Stufen kann eine gute oder schlechte Systemleistung verursachen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img1-de.png"
        alt="Vollständige AI-Systemkette von Benutzereingabe und Prompt über Retrieval, Sprachmodell und Tools bis zum Geschäftsergebnis mit separaten Bewertungsdimensionen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das gesamte System muss evaluiert werden. Eine hohe Modellqualität kompensiert kein schlechtes Retrieval, fehlerhafte Tool-Aufrufe oder einen ungeeigneten Geschäftsprozess.
    </figcaption>
</figure>

### Benutzereingabe

Die Evaluation muss prüfen, ob die Testfälle die reale Nutzung ausreichend abbilden.

Fragen:

- Sind häufige Standardfälle enthalten?
- Gibt es mehrdeutige, unvollständige und fehlerhafte Eingaben?
- Werden unterschiedliche Sprachen und Formulierungen berücksichtigt?
- Sind seltene, aber kritische Fälle vertreten?
- Enthält der Testbestand Missbrauchs- und Angriffsszenarien?
- Entspricht die Verteilung ungefähr dem späteren Produktionsaufkommen?

Ein Testbestand mit ausschließlich klar formulierten Idealfragen überschätzt die reale Systemqualität.

### Prompt und Kontextaufbereitung

Hier wird gemessen, ob Aufgabe, Regeln und Ausgabeformat eingehalten werden.

Mögliche Kriterien:

- korrekte Rollen- und Aufgabeninterpretation,
- Einhaltung des geforderten Schemas,
- Nutzung bereitgestellter Evidenz,
- korrekte Behandlung fehlender Informationen,
- Beachtung fachlicher Regeln,
- keine Verarbeitung externer Inhalte als übergeordnete Anweisung.

### Retrieval

Bei RAG-Systemen muss Retrieval separat von der Antwortqualität betrachtet werden.

Wichtige Fragen:

- Wurde die benötigte Quelle gefunden?
- War sie unter den ersten Treffern?
- Wurde die gültige Version ausgewählt?
- Waren Zugriffsfilter korrekt?
- Wurden relevante Metadaten berücksichtigt?
- Wurden zu viele irrelevante oder doppelte Chunks geliefert?

Beispielhafte Retrieval-Metriken:

| Metrik | Aussage |
| --- | --- |
| **Recall at k** | Anteil der Fälle, in denen die notwendige Quelle unter den ersten k Treffern lag |
| **Precision at k** | Anteil relevanter Treffer unter den ersten k Treffern |
| **Mean Reciprocal Rank** | Wie früh der erste relevante Treffer erschien |
| **Source Coverage** | Ob alle für die Antwort notwendigen Quellen enthalten waren |
| **Version Correctness** | Ob die gültige Dokumentversion genutzt wurde |
| **Access Correctness** | Ob nur für den Benutzer zulässige Inhalte geliefert wurden |

Eine falsche Antwort kann durch ein gutes Modell auf Basis eines falschen Dokuments entstehen. Ohne separate Retrieval-Auswertung würde die eigentliche Ursache verborgen bleiben.

### Sprachmodell

Die Modellantwort kann nach mehreren Dimensionen bewertet werden:

- Korrektheit,
- Vollständigkeit,
- Relevanz,
- Groundedness,
- Konsistenz,
- Stil und Verständlichkeit,
- Einhaltung des Ausgabeformats,
- angemessene Unsicherheit,
- sichere Ablehnung bei unzulässigen Aufgaben.

### Tools und Aktionen

Bei Agenten muss nicht nur die endgültige Antwort, sondern der gesamte Ablauf bewertet werden.

Mögliche Metriken:

- richtige Tool-Auswahl,
- gültige Parameter,
- erfolgreiche Aufrufrate,
- Anzahl notwendiger Schritte,
- unnötige oder doppelte Aufrufe,
- Retry-Rate,
- Abbruchquote,
- Recovery-Erfolg,
- Einhaltung von Kosten- und Schrittbudgets.

### Validierung

Auch ein Guardrail oder Validator benötigt eigene Evaluation.

Zu messen ist:

- Wie viele echte Fehler wurden erkannt?
- Wie viele Fehler wurden übersehen?
- Wie viele korrekte Ergebnisse wurden fälschlich blockiert?
- Wie stabil ist die Prüfung bei unterschiedlichen Formulierungen?
- Kann der Validator durch manipulierte Inhalte beeinflusst werden?

### Geschäftsergebnis

Am Ende zählt nicht nur die Antwort, sondern die Wirkung im Prozess.

Beispiele:

- Vorgang erfolgreich abgeschlossen,
- Nacharbeit erforderlich,
- Fall eskaliert,
- Bearbeitungszeit reduziert,
- falsche Entscheidung verhindert,
- Benutzer übernimmt oder korrigiert den Vorschlag,
- wirtschaftlicher Nutzen pro erfolgreichem Vorgang.

> **Ein kleineres Modell in einer guten Architektur kann ein größeres Modell in einem schlechten Gesamtsystem übertreffen.**

## Evaluation benötigt mehrere Ebenen

Eine einzige große End-to-End-Testphase ist teuer und liefert oft unklare Fehlerursachen. Sinnvoller ist ein stufenweiser Aufbau.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img2-de.png"
        alt="Evaluierungspyramide mit Komponententests, Offline-Evaluation, Szenario- und Workflow-Tests, kontrolliertem Produktivbetrieb und kontinuierlichem Produktionsmonitoring"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Evaluation wird schrittweise von schnellen Komponententests bis zur kontinuierlichen Beobachtung realer Geschäftsprozesse aufgebaut.
    </figcaption>
</figure>

## Ebene 1: Komponententests

Komponententests prüfen kleine, deterministisch beschreibbare Einheiten.

Beispiele:

- Parser liest Tabellen korrekt,
- Chunking erhält Überschriften und Abschnitte,
- Metadatenfilter werden angewendet,
- Tool-Schema akzeptiert nur gültige Parameter,
- Policy-Regel blockiert unzulässige Aktionen,
- strukturierte Modellausgabe entspricht dem JSON-Schema,
- Berechnungstool liefert den erwarteten Wert.

Vorteile:

- schnell,
- günstig,
- gut automatisierbar,
- klare Fehlerlokalisierung,
- geeignet für jeden Build und Pull Request.

Komponententests sagen jedoch wenig darüber aus, ob der vollständige Use Case funktioniert.

## Ebene 2: Offline-Evaluation

Offline-Evaluation führt das vollständige System gegen einen versionierten Testbestand aus, ohne reale Benutzer oder Produktivsysteme zu beeinflussen.

Typische Bestandteile:

- Golden Dataset,
- erwartete Ergebnisse,
- erwartete Quellen,
- Sicherheitsfälle,
- Grenzfälle,
- Kosten- und Latenzbudgets,
- Vergleich mehrerer Konfigurationen.

Offline-Evaluation eignet sich für:

- Modellvergleich,
- Prompt-Regression,
- RAG-Optimierung,
- Tool-Routing,
- Prüfung neuer Policies,
- Go-/No-Go-Gates vor einem Release.

## Ebene 3: Szenario- und Workflow-Tests

Einzelne Frage-Antwort-Paare reichen bei agentischen Systemen nicht aus. Szenariotests prüfen komplette Aufgaben.

Beispiel:

```flow linear vertical
Benutzer meldet einen Abrechnungsfehler
Agent ermittelt Kunden- und Vertragskontext
Agent ruft relevante Rechnungsdaten ab
Agent prüft Geschäftsregeln
Agent erstellt Korrekturvorschlag
Mensch genehmigt die Änderung
System protokolliert Entscheidung und Ausführung
```

Zu testen sind nicht nur Erfolgspfade, sondern auch:

- Quelle nicht erreichbar,
- Tool liefert unvollständiges Ergebnis,
- Benutzer besitzt keine Berechtigung,
- Informationen widersprechen sich,
- Modell schlägt eine riskante Aktion vor,
- Freigabe wird abgelehnt,
- Aktion schlägt nach einem Teilschritt fehl,
- Rollback oder Kompensation ist erforderlich.

## Ebene 4: Kontrollierter Produktivbetrieb

Ein System kann offline gut abschneiden und dennoch unter realen Bedingungen versagen. Reale Sprache, Datenverteilung, Last, Benutzerverhalten und Systemabhängigkeiten sind schwer vollständig zu simulieren.

Kontrollierter Produktivbetrieb kann umfassen:

- begrenzte Nutzergruppe,
- interner Pilot,
- Schattenbetrieb,
- A/B-Test,
- Feature Flag,
- manuelle Prüfung,
- begrenzte Autonomie,
- schrittweiser Rollout.

Wichtig ist, dass Umfang und mögliche Wirkung begrenzt bleiben.

## Ebene 5: Kontinuierliches Produktionsmonitoring

Nach dem Rollout verändert sich die Umgebung:

- Benutzer stellen neue Fragen,
- Daten und Richtlinien ändern sich,
- Modelle und APIs erhalten neue Versionen,
- Dokumentbestände wachsen,
- Retrieval-Verteilungen verschieben sich,
- Tools und Abhängigkeiten ändern ihr Verhalten.

Evaluation endet daher nicht mit der Freigabe. Produktionsdaten werden zur Grundlage für neue Testfälle und Regressionstests.

```flowchart
Produktionsfall erfassen
Auffälligkeit klassifizieren
Testfall erstellen
Ursache korrigieren
Regression testen
Kontrolliert veröffentlichen
```

## Was ist ein gutes Golden Dataset?

Ein Golden Dataset ist kein beliebiger Export historischer Anfragen. Es ist ein kuratierter, versionierter und fachlich verantworteter Testbestand.

Ein guter Testfall kann enthalten:

| Bestandteil | Zweck |
| --- | --- |
| **Input** | Frage, Aufgabe oder Gesprächsverlauf |
| **Kontext** | relevante Benutzer-, Mandanten- oder Prozessinformationen |
| **Erwartete Evidenz** | Quellen oder Datensätze, die gefunden werden müssen |
| **Erwartetes Verhalten** | zulässige Antwort oder notwendiger Prozessschritt |
| **Erwartete Fakten** | überprüfbare Aussagen |
| **Erlaubte Tools** | Funktionen, die genutzt werden dürfen |
| **Verbotene Aktionen** | explizit unzulässiges Verhalten |
| **Erfolgskriterien** | fachliche, technische und sicherheitsbezogene Bedingungen |
| **Tags** | Sprache, Risiko, Kategorie, Region, Schwierigkeit |
| **Owner** | fachlich verantwortliche Person oder Rolle |

### Wo Testfälle herkommen

Ein belastbarer Testbestand kombiniert mehrere Quellen:

- fachlich definierte Normalfälle,
- reale anonymisierte Produktionsanfragen,
- bekannte Fehlermuster,
- Support- und Incident-Fälle,
- Grenzfälle,
- gezielt erzeugte Angriffs- und Missbrauchsfälle,
- Anforderungen aus Policies und regulatorischen Vorgaben.

Produktionsdaten sind wertvoll, dürfen aber nicht ungeprüft übernommen werden. Sie können personenbezogene Daten, falsche Entscheidungen oder verzerrte Nutzungsmuster enthalten.

### Der Testbestand muss wachsen

Neue Fehler sollten möglichst in einen reproduzierbaren Testfall überführt werden.

```flow linear vertical
Fehler tritt im Betrieb auf
Ursache wird analysiert
Repräsentativer Testfall wird ergänzt
Korrektur wird implementiert
Alle bisherigen Tests werden erneut ausgeführt
```

So entsteht mit der Zeit eine Regression-Suite, die das reale Systemwissen abbildet.

## Reproduzierbarkeit benötigt Versionierung

Ein Ergebnis ist nur vergleichbar, wenn bekannt ist, welche Systemkonfiguration es erzeugt hat.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img3-de.png"
        alt="Reproduzierbarer Testfall mit versionierter Systemkonfiguration, messbarem Ergebnis und Vergleich mehrerer Systemversionen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Nicht nur die Frage, sondern auch Modell, Prompt, Retrieval-Index, Tools, Policies und Laufzeitkonfiguration müssen versioniert werden.
    </figcaption>
</figure>

Mindestens folgende Artefakte sollten einem Evaluationslauf zugeordnet werden:

```text
Test-Dataset-Version
Modell und Modellversion
System-Prompt-Version
Prompt-Template-Version
Embedding-Modell
Retrieval-Index oder Snapshot
Chunking-Konfiguration
Reranker-Version
Tool-Versionen
Policy- und Guardrail-Versionen
Sampling-Parameter
Laufzeitkonfiguration
Code-Commit
Zeitpunkt und Umgebung
```

Ohne diese Informationen bleibt unklar, warum sich ein Ergebnis verändert hat.

### Beispiel

Version B erzielt eine bessere Korrektheitsrate als Version A. Gleichzeitig wurde jedoch:

- das Modell gewechselt,
- ein neuer System-Prompt eingeführt,
- der Index neu aufgebaut,
- das Chunking verändert und
- ein zusätzlicher Validator aktiviert.

Die Verbesserung kann nicht eindeutig zugeordnet werden.

Ein kontrollierter Vergleich verändert deshalb möglichst wenige Faktoren gleichzeitig oder dokumentiert sie vollständig.

## Welche Bewertungsmethoden gibt es?

Nicht jede Qualitätsdimension benötigt dieselbe Bewertungsmethode.

### Deterministische Prüfung

Geeignet für klar überprüfbare Bedingungen:

- gültiges JSON,
- Pflichtfeld vorhanden,
- Zahl innerhalb eines Bereichs,
- Tool-Aufruf erlaubt,
- Quelle gehört zum zulässigen Mandanten,
- Antwort enthält keine verbotenen Datenklasse,
- Aktion wurde genau einmal ausgeführt.

Deterministische Prüfungen sind schnell und reproduzierbar. Wo sie möglich sind, sollten sie bevorzugt werden.

### Vergleich mit Referenzwerten

Geeignet für:

- Klassifikation,
- Extraktion,
- bekannte Berechnungen,
- definierte Entitäten,
- erwartete Quellen,
- fachlich eindeutige Antworten.

Nicht jede generative Aufgabe besitzt jedoch genau eine richtige Formulierung.

### Regel- oder Rubrik-basierte Bewertung

Eine Rubrik beschreibt mehrere Kriterien mit klaren Bewertungsstufen.

Beispiel:

| Kriterium | 0 Punkte | 1 Punkt | 2 Punkte |
| --- | --- | --- | --- |
| Korrektheit | zentrale Aussage falsch | teilweise korrekt | vollständig korrekt |
| Quellenbezug | keine Evidenz | teilweise gestützt | vollständig belegt |
| Vollständigkeit | wesentliche Aspekte fehlen | kleinere Lücken | alle erforderlichen Aspekte |
| Handlungsfähigkeit | unbrauchbar | mit Nacharbeit nutzbar | direkt nutzbar |

Rubriken können von Menschen oder einem Bewertungsmodell angewendet werden.

### LLM-as-a-Judge

Ein Modell bewertet die Ausgabe eines anderen Systems.

Vorteile:

- skalierbar,
- flexibel,
- auch für offene Texte nutzbar,
- detaillierte Kriterien möglich.

Risiken:

- eigene Halluzinationen,
- Präferenz für bestimmte Formulierungen,
- Positions- oder Längenbias,
- instabile Bewertung,
- Abhängigkeit vom Judge-Modell,
- unbemerkte Veränderungen des Bewertungsmodells.

LLM-Judges sollten deshalb:

- gegen menschliche Bewertungen kalibriert,
- versioniert,
- mit klaren Rubriken geführt,
- regelmäßig erneut geprüft und
- nicht als einzige Kontrollinstanz für Hochrisikoentscheidungen verwendet

werden.

### Menschliche Evaluation

Menschen bleiben wichtig, wenn:

- mehrere fachlich vertretbare Antworten existieren,
- Kontextwissen schwer formalisiert werden kann,
- Auswirkungen hoch sind,
- neue Fehlermuster untersucht werden,
- Benutzererfahrung und Verständlichkeit bewertet werden.

Menschliche Bewertung ist ebenfalls nicht automatisch objektiv. Notwendig sind:

- klare Rubriken,
- Schulung,
- mehrere Reviewer bei kritischen Fällen,
- Messung der Übereinstimmung,
- dokumentierte Entscheidung bei Uneinigkeit.

## Metriken müssen zum Use Case passen

Eine universelle AI-Metrik existiert nicht.

### Fragebeantwortung mit RAG

Mögliche Dimensionen:

- Retrieval Recall,
- Quellenabdeckung,
- Groundedness,
- faktische Korrektheit,
- Zitiergenauigkeit,
- Vollständigkeit,
- Abstain-Rate bei fehlender Evidenz.

### Dokumentenextraktion

- Feldgenauigkeit,
- Precision und Recall,
- Tabellenstruktur,
- Formatvalidität,
- Anteil notwendiger manueller Korrekturen.

### Klassifikation

- Precision,
- Recall,
- F1,
- Confusion Matrix,
- Qualität je Klasse,
- Qualität bei seltenen Fällen.

### Agenten

- Task Completion Rate,
- korrekte Tool-Auswahl,
- Anzahl Schritte,
- doppelte Aktionen,
- Recovery-Erfolg,
- Kosten je erfolgreichem Lauf,
- menschliche Eingriffe,
- Policy-Verstöße.

### Codegenerierung

- bestandene Tests,
- Sicherheitsprüfung,
- statische Analyse,
- Wartbarkeit,
- notwendige Nacharbeit,
- Ausführungszeit.

### Zusammenfassungen

- Faktenabdeckung,
- keine neuen unbelegten Aussagen,
- Erhalt wichtiger Einschränkungen,
- angemessene Kürze,
- Lesbarkeit.

> **Die Metrik muss das gewünschte Verhalten messen – nicht nur das, was sich leicht zählen lässt.**

## Aggregationen können Fehler verdecken

Ein Durchschnittswert kann gut aussehen, obwohl wichtige Teilgruppen schlecht funktionieren.

Beispiel:

| Segment | Erfolgsrate |
| --- | ---: |
| Häufige Standardfragen | 94 % |
| Deutsche Anfragen | 91 % |
| Englische Anfragen | 95 % |
| Seltene Vertragsfälle | 62 % |
| Hochrisikoaktionen | 71 % |

Die Gesamtquote könnte weiterhin akzeptabel wirken, obwohl gerade die kritischen Fälle unzureichend sind.

Evaluation sollte daher segmentiert werden nach:

- Use Case,
- Sprache,
- Region,
- Kundengruppe,
- Datenquelle,
- Schwierigkeitsgrad,
- Risikoklasse,
- Modellroute,
- Tool,
- Dokumentversion,
- Eingabelänge.

Für kritische Segmente können eigene Mindestwerte gelten.

## Release Gates statt Bauchgefühl

Ein Go-live sollte auf definierten Freigabekriterien basieren.

Beispiel:

```text
Retrieval Recall at 5 ≥ vereinbarter Mindestwert
Keine kritischen Zugriffsverletzungen
Korrektheit in Hochrisikofällen ≥ Mindestwert
Keine unzulässigen Schreibaktionen
p95-Latenz innerhalb des Budgets
Kosten je erfolgreichem Vorgang innerhalb des Zielbereichs
Alle kritischen Regressionstests bestanden
Fachlicher Owner hat Freigabe erteilt
```

Die konkreten Schwellenwerte hängen vom Use Case ab. Eine interne Suchhilfe benötigt andere Grenzen als ein Agent, der Bestellungen, Zahlungen oder Vertragsänderungen auslösen kann.

### Regression darf nicht durch Durchschnittsverbesserung verdeckt werden

Eine neue Version kann insgesamt besser sein und gleichzeitig in einer kritischen Kategorie schlechter werden.

Daher sollten Release Gates enthalten:

- globale Mindestwerte,
- segmentierte Mindestwerte,
- keine neuen kritischen Fehler,
- Begrenzung maximal zulässiger Regression,
- Sicherheits- und Policy-Gates,
- Kosten- und Latenzbudgets.

## Qualität, Latenz und Kosten stehen im Zielkonflikt

Das leistungsfähigste Modell für jede Anfrage ist selten die beste Systemarchitektur.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img4-de.png"
        alt="Vergleich eines großen Modells für alle Aufgaben, eines kleinen Modells für alle Aufgaben und einer gerouteten hybriden Architektur hinsichtlich Qualität, Latenz und Kosten"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine geroutete Architektur kann einfache, komplexe, wissensbasierte, deterministische und risikoreiche Aufgaben über unterschiedliche Pfade verarbeiten.
    </figcaption>
</figure>

### Großes Modell für alle Aufgaben

Mögliche Vorteile:

- hohe allgemeine Leistungsfähigkeit,
- weniger Routing-Logik,
- einfacher Prototyp.

Nachteile:

- höhere Kosten,
- häufig höhere Latenz,
- unnötige Kapazität für einfache Aufgaben,
- stärkere Abhängigkeit von einem Modell.

### Kleines Modell für alle Aufgaben

Mögliche Vorteile:

- geringe Kosten,
- schnelle Antworten,
- hohe Skalierbarkeit.

Nachteile:

- Qualitätsverlust bei komplexen Aufgaben,
- schwächere Reasoning-Fähigkeit,
- höhere Eskalations- oder Korrekturrate.

### Geroutete oder hybride Architektur

Beispiel:

```flow linear vertical
Anfrage klassifizieren
Schwierigkeit, Aktualität und Risiko bestimmen
Geeigneten Modell- oder Tool-Pfad wählen
Ergebnis validieren
Bei Unsicherheit eskalieren
```

Mögliche Routen:

| Aufgabentyp | Geeigneter Pfad |
| --- | --- |
| einfache Klassifikation | kleines schnelles Modell |
| komplexe Argumentation | leistungsfähigeres Modell |
| aktuelles Unternehmenswissen | RAG |
| exakte Berechnung | deterministisches Tool |
| strukturierte Datenabfrage | kontrollierte Query- oder API-Schicht |
| Hochrisikoaktion | menschliche Freigabe |

### Kosten pro Token sind nicht die wichtigste Geschäftsmetrik

Ein günstiger Lauf ist nicht wirtschaftlich, wenn er häufig:

- falsche Ergebnisse erzeugt,
- wiederholt werden muss,
- manuelle Nacharbeit verursacht,
- unnötige Tool-Aufrufe auslöst,
- Benutzer zur Eskalation zwingt.

Aussagekräftiger sind beispielsweise:

```text
Kosten pro erfolgreichem Geschäftsvorgang
Kosten pro korrekt beantworteter Anfrage
Kosten inklusive Wiederholungen und Nacharbeit
Kosten pro vermiedener manueller Bearbeitung
Kosten pro akzeptierter Empfehlung
```

## Latenz richtig messen

Eine einzige durchschnittliche Antwortzeit reicht nicht.

Wichtige Messwerte:

- Time to First Token,
- Zeit bis zur vollständigen Antwort,
- Retrieval-Latenz,
- Modell-Latenz,
- Tool-Latenz,
- End-to-End-Latenz,
- p50, p95 und p99,
- Timeout-Rate.

Die Benutzerwahrnehmung hängt außerdem vom Use Case ab. Eine lange, komplexe Analyse darf länger dauern als eine interaktive Suchvorschlagsfunktion.

Latenz kann reduziert werden durch:

- kleinere Modelle für einfache Aufgaben,
- kürzere Prompts,
- weniger unnötigen Kontext,
- parallele unabhängige Tool-Aufrufe,
- Streaming,
- Caching,
- Vorberechnung,
- schnellere Retrieval- und Reranking-Schichten,
- asynchrone Verarbeitung für nicht interaktive Aufgaben.

Jede Optimierung muss erneut gegen Qualität und Kosten evaluiert werden.

## Der Produktionsbetrieb ist ein Regelkreis

AI Operations verbindet klassische Softwarebeobachtung mit Modell-, Daten-, Qualitäts- und Sicherheitsmetriken.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img5-de.png"
        alt="AI-Produktionsbetrieb als geschlossener Operations-Kreislauf mit Bereitstellen, Beobachten, Bewerten, Änderungen erkennen, Untersuchen, Verbessern, Validieren und Freigeben"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Produktionsbetrieb ist kein einmaliges Deployment. Beobachtung, Evaluation, Ursachenanalyse, Verbesserung und kontrollierte Freigabe bilden einen dauerhaften Kreislauf.
    </figcaption>
</figure>

Der Kreislauf besteht aus:

```flowchart
Bereitstellen
Beobachten
Bewerten
Änderungen erkennen
Untersuchen
Verbessern
Validieren
Freigeben
```

### Bereitstellen

Eine freigegebene Kombination aus Modell, Prompt, Daten, Tools und Policies wird kontrolliert ausgerollt.

### Beobachten

Traces, Logs, Kosten, Latenzen, Retrieval-Ergebnisse, Tool-Aufrufe und Feedback werden erfasst.

### Bewerten

Produktionsfälle werden über deterministische Regeln, Stichproben, menschliche Reviews oder automatische Scorer bewertet.

### Änderungen erkennen

Auffälligkeiten können sein:

- Qualitätsabfall,
- Drift,
- neue Fehlermuster,
- Kostenanstieg,
- höhere Latenz,
- ungewöhnliche Tool-Nutzung,
- mehr Policy-Verstöße,
- steigende Eskalationsrate.

### Untersuchen

Traces und Versionen helfen, die Ursache zu lokalisieren:

- Quelle,
- Index,
- Prompt,
- Modell,
- Tool,
- Policy,
- Benutzeroberfläche,
- Geschäftsprozess.

### Verbessern

Mögliche Maßnahmen:

- Daten korrigieren,
- Testbestand erweitern,
- Prompt ändern,
- Retrieval optimieren,
- anderes Modell routen,
- Tool-Schnittstelle einschränken,
- Freigabeprozess anpassen.

### Validieren

Die Änderung wird offline und in relevanten Szenarien erneut geprüft. Kritische Regressionen müssen ausgeschlossen werden.

### Freigeben

Die neue Version wird schrittweise veröffentlicht. Bei Auffälligkeiten muss ein kontrollierter Rückweg vorhanden sein.

## Was sollte im Betrieb überwacht werden?

Monitoring bedeutet nicht nur CPU, Speicher und Verfügbarkeit. Ein AI-System benötigt mehrere Beobachtungsebenen.

| Bereich | Beispielhafte Metriken |
| --- | --- |
| **Qualität** | Korrektheit, Vollständigkeit, Relevanz, Groundedness |
| **Retrieval** | Recall, Precision, Versionstreue, Zugriffsfehler |
| **Modell** | Formatfehler, Abstain-Rate, Tokenverbrauch, Modellroute |
| **Agent** | Schritte, Tool-Aufrufe, Loops, Abbrüche, Recovery |
| **Tools** | Erfolgsquote, Latenz, Retries, Duplikate |
| **Sicherheit** | Prompt Injection, Policy Stops, Datenabfluss, unzulässige Ziele |
| **Kosten** | Kosten pro Anfrage und pro erfolgreichem Vorgang |
| **Benutzer** | Bewertungen, Korrekturen, Akzeptanz, Eskalationen |
| **Geschäft** | Automatisierungsgrad, Nacharbeit, Durchlaufzeit, Erfolgsquote |
| **System** | Fehlerquote, Timeouts, Durchsatz, Verfügbarkeit |

### Schwellenwerte und Trends

Ein einzelner Grenzwert reicht häufig nicht.

Beispiel:

```text
Warnung:
p95-Latenz über Zielwert für 15 Minuten

Kritisch:
p95-Latenz über Maximalwert oder Timeout-Rate über Grenzwert

Trend:
Latenz steigt über sieben Tage kontinuierlich
```

Trends können ein Problem früher zeigen als ein harter Grenzwert.

### Korrelation statt isolierter Metriken

Ein Kostenanstieg kann durch unterschiedliche Ursachen entstehen:

- längere Prompts,
- mehr Retrieval-Chunks,
- häufiger Einsatz eines großen Modells,
- Tool-Retries,
- Endlosschleifen,
- mehr Benutzeranfragen,
- schlechtere Qualität und Wiederholungen.

Traces sollten die Verbindung zwischen Anfrage, Modellroute, Tokens, Tools, Latenz, Ergebnis und Bewertung erhalten.

## Tracing und Datenschutz

Traces sind für Fehleranalyse und Evaluation wertvoll, können aber sensible Inhalte enthalten:

- Benutzerprompts,
- Unternehmensdokumente,
- Tool-Parameter,
- personenbezogene Daten,
- Modellantworten,
- interne Systemanweisungen.

Daher sind erforderlich:

- Datenminimierung,
- Maskierung,
- Zugriffskontrolle,
- getrennte Umgebungen,
- Aufbewahrungsregeln,
- Auditierbarkeit,
- dokumentierte Nutzung für Evaluation,
- kontrollierter Export an externe Evaluationsdienste.

Nicht jede vollständige Eingabe muss dauerhaft gespeichert werden. Je nach Risiko können Hashes, Referenzen, strukturierte Metadaten oder redigierte Samples ausreichen.

## Produktionsfeedback in Evaluation überführen

Benutzerfeedback ist wichtig, aber nicht automatisch ein Qualitätslabel.

Ein Daumen nach unten kann bedeuten:

- Antwort war falsch,
- Antwort war korrekt, aber unverständlich,
- erwartete Funktion fehlt,
- Benutzer lehnt die Policy ab,
- Antwort dauerte zu lange,
- Ergebnis passte nicht zur persönlichen Erwartung.

Feedback sollte deshalb kategorisiert werden.

Beispiel:

| Feedbacktyp | Nachgelagerte Aktion |
| --- | --- |
| falscher Fakt | Quellen- und Antwortprüfung |
| Quelle fehlt | Retrieval-Test ergänzen |
| unverständliche Antwort | Stil- oder UX-Evaluation |
| falsches Tool | Agenten-Trace untersuchen |
| zu langsam | Latenzanalyse |
| Policy blockiert legitimen Fall | Policy-Review |
| unsicheres Verhalten | Incident-Prozess |

## Reaktionen auf Auffälligkeiten

Nicht jede Abweichung erfordert dieselbe Maßnahme.

Mögliche Reaktionen:

```flowchart
Weiterführen
Zurückrollen
Tool deaktivieren
Modell wechseln
Autonomie reduzieren
Vorfall eskalieren
```

### Weiterführen

Die Abweichung liegt innerhalb des erwarteten Bereichs oder wurde als unkritisch bewertet.

### Zurückrollen

Eine neue Version verursacht Regressionen. Die vorherige, freigegebene Konfiguration wird wiederhergestellt.

### Tool deaktivieren

Eine Integration liefert falsche Ergebnisse oder verhält sich unsicher. Der übrige Dienst kann mit eingeschränkter Funktion weiterlaufen.

### Modell wechseln

Ein alternatives freigegebenes Modell übernimmt bestimmte Routen.

### Autonomie reduzieren

Schreibaktionen werden vorübergehend blockiert oder benötigen zusätzliche menschliche Freigabe.

### Vorfall eskalieren

Sicherheits-, Datenschutz-, Compliance- oder erhebliche Geschäftsrisiken lösen den Incident-Prozess aus.

## Ownership für Evaluation und Betrieb

Evaluation ist keine Aufgabe einer einzelnen Rolle.

| Rolle | Typische Verantwortung |
| --- | --- |
| **Business Owner** | Nutzen, Risikoakzeptanz und Erfolgskriterien |
| **Domain Expert** | Golden Dataset, Rubriken und fachliche Prüfung |
| **AI / ML Engineer** | Modell-, Prompt- und Evaluationsimplementierung |
| **Data Engineer** | Quellen, Pipelines, Index und Datenqualität |
| **Software Engineer** | Tools, APIs, Validierung und Zuverlässigkeit |
| **Security / Privacy** | Angriffsfälle, Datenflüsse und Sicherheitskontrollen |
| **Governance** | Policies, Dokumentation, Freigaben und Nachvollziehbarkeit |
| **Operations** | Monitoring, Alerting, Incident und Rollback |
| **End User** | Nutzungserfahrung und reales Feedback |

Eine Metrik benötigt einen Owner, einen Zielwert und eine definierte Reaktion.

## Was dokumentiert werden sollte

| Artefakt | Inhalt |
| --- | --- |
| **Evaluation Plan** | Ziele, Umfang, Metriken und Verantwortliche |
| **Evaluation Dataset** | Testfälle, Erwartungen, Tags und Herkunft |
| **Scoring Specification** | Regeln, Rubriken, Judges und Schwellenwerte |
| **System Version** | Modell, Prompt, Daten, Tools und Policies |
| **Evaluation Run** | Ergebnisse, Fehler, Kosten und Latenzen |
| **Release Decision** | Go/No-Go, Abweichungen und akzeptierte Risiken |
| **Monitoring Plan** | Signale, Grenzwerte, Trends und Alerts |
| **Runbook** | Reaktion auf Regressionen, Ausfälle und Sicherheitsvorfälle |
| **Incident History** | Ursache, Wirkung und Korrekturmaßnahmen |
| **Change History** | Versionen und Freigaben |

## Ein pragmatischer Start

Eine Organisation benötigt nicht sofort eine vollständige Evaluationsplattform.

Ein sinnvoller erster Aufbau:

```flow linear vertical
20–50 repräsentative Testfälle definieren
Kritische Fehler und Muss-Kriterien festlegen
Deterministische Prüfungen automatisieren
Eine einfache fachliche Rubrik ergänzen
Modell, Prompt und Datenversion protokollieren
Evaluation in den Release-Prozess integrieren
Produktionsfeedback als neue Testfälle zurückführen
```

Danach kann der Umfang schrittweise wachsen:

- mehr Segmente,
- Agenten-Traces,
- automatische Judges,
- kontinuierliches Sampling,
- Kosten- und Latenzbudgets,
- Dashboarding,
- Release Gates,
- kontrollierte Experimente.

## Die zentrale Erkenntnis

> **AI-Qualität ist keine feste Eigenschaft eines Modells. Sie ist das messbare Ergebnis eines vollständigen, versionierten und betriebenen Systems.**

Ein verlässlicherer Produktionsbetrieb entsteht, wenn:

- reale und kritische Fälle im Testbestand enthalten sind,
- Komponenten und End-to-End-Prozesse getrennt bewertet werden,
- jede relevante Konfiguration versioniert ist,
- Qualität, Latenz, Kosten und Risiko gemeinsam betrachtet werden,
- Releases an definierte Gates gebunden sind,
- Produktionssignale zu neuen Tests und Verbesserungen führen,
- Rollback, Eskalation und reduzierte Autonomie vorbereitet sind.

## Die Serie im Überblick

```flow linear vertical
AI Foundations — How AI Works [done]
Language Models and Providers [done]
Enterprise Data, Embeddings and RAG [done]
AI Agents and Automation [done]
Known Problems and Failure Modes [done]
Evaluation, Costs and Operations [active]
AI Governance
```

Part 7 führt die technischen und betrieblichen Erkenntnisse der Serie zu einem vollständigen **AI-Governance-Modell** zusammen: Rollen, Policies, Risikoklassen, Freigaben, Lifecycle, Nachvollziehbarkeit und laufende Kontrolle.

## Quellen und weiterführende Dokumentation

- [OpenAI — Evals API Reference](https://platform.openai.com/docs/api-reference/evals)
- [OpenAI — Evals Framework](https://github.com/openai/evals)
- [OpenAI — Optimizing Latency with API Models](https://help.openai.com/en/articles/6901266)
- [Anthropic — Using the Evaluation Tool](https://docs.anthropic.com/en/docs/test-and-evaluate/eval-tool)
- [Anthropic — Reducing Latency](https://docs.anthropic.com/en/docs/test-and-evaluate/strengthen-guardrails/reduce-latency)
- [Google Cloud — Gen AI Evaluation Service Quickstart](https://docs.cloud.google.com/vertex-ai/generative-ai/docs/models/evaluation-quickstart)
- [MLflow — LLM and Agent Evaluation](https://mlflow.org/docs/latest/genai/eval-monitor/index.html)
- [MLflow — Evaluation Quickstart](https://mlflow.org/docs/latest/genai/eval-monitor/quickstart/)
- [OpenTelemetry — Semantic Conventions](https://opentelemetry.io/docs/specs/semconv/)
- [NIST — AI Risk Management Framework](https://www.nist.gov/itl/ai-risk-management-framework)
- [NIST — Generative AI Profile](https://www.nist.gov/publications/artificial-intelligence-risk-management-framework-generative-artificial-intelligence)
- [NIST — AI RMF Playbook](https://airc.nist.gov/airmf-resources/playbook/)
