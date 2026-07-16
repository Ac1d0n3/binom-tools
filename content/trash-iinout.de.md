---
title: Trash In, Trash Out — Wenn KI Annahmen als Fakten übernimmt
description: Warum ungelöste Quellfehler, versteckte Schätzwerte und unvollständige Daten zum KI-Risiko werden — und wie Governance verhindert, dass Unsicherheit als Wahrheit erscheint.
category: Data Governance
tags:
  - ai-governance
  - data-quality
  - source-data
  - training-data
  - hallucinations
  - machine-learning
  - rag
  - data-lineage
  - trusted-ai
  - data-governance
order: -1
author: Thomas Lindackers
hero: images/playbooks/trash-iinout-hero.png
---

## KI beginnt nicht mit Intelligenz — sondern mit Daten

Unternehmen verbinden KI inzwischen mit fast allen Bereichen ihrer Datenlandschaft:

- Data Warehouses und Lakehouses
- Dokumenten und Wissensdatenbanken
- CRM-, ERP- und Ticketsystemen
- operativen Anwendungen und APIs
- Reports, Kennzahlen und semantischen Modellen
- E-Mails, Verträgen und Supporthistorien

Die zugrunde liegenden Daten sind nur selten eine direkte Abbildung der Realität.

Sie wurden meistens bereits:

- korrigiert
- standardisiert
- zusammengeführt
- gefiltert
- angereichert
- gemappt
- aggregiert
- imputiert
- geschätzt

Die meisten dieser Transformationen sind notwendig. Eine Datenplattform muss Formate harmonisieren, Systeme integrieren und Informationen für einen bestimmten Zweck aufbereiten.

Das Risiko beginnt dort, wo das Ergebnis vollständig und präzise aussieht, während seine **Unsicherheit verschwunden ist**.

Ein fehlender Wert ist sichtbar unbekannt. Ein geschätzter Wert kann sinnvoll sein, solange er eindeutig als Schätzung gekennzeichnet bleibt. Wird eine Schätzung jedoch wie eine beobachtete Tatsache gespeichert, kopiert und präsentiert, behandeln nachgelagerte Konsumenten eine Annahme möglicherweise als Wahrheit.

KI kann dieses Problem verstärken.

> **KI kann ableiten, was möglicherweise fehlt. Sie kann nicht wissen, ob diese Ableitung wahr ist.**

## Unternehmensdaten gelangen auf unterschiedlichen Wegen in KI

„Die KI wird mit Unternehmensdaten trainiert“ wird häufig als Sammelbegriff verwendet. Technisch existieren jedoch mehrere unterschiedliche Muster.

### Modelltraining

Ein Modell wird mit Daten trainiert, um Muster zu erkennen und Vorhersagen zu erzeugen. Datenfehler, fehlende Abdeckung und versteckte Annahmen können das erlernte Verhalten direkt beeinflussen.

### Fine-Tuning und Feature Engineering

Ein vorhandenes Modell wird mit unternehmensspezifischen Beispielen angepasst oder strukturierte Features werden für ein Machine-Learning-Modell erstellt. Qualität und Repräsentativität dieser Eingaben prägen die späteren Vorhersagen.

### Retrieval-Augmented Generation

Ein Sprachmodell ruft Dokumente, Datensätze oder Wissensfragmente zur Laufzeit ab. Das Basismodell wird dabei nicht neu trainiert. Unvollständige, veraltete oder widersprüchliche Unternehmensinhalte können trotzdem zu irreführenden Antworten führen.

### Agenten, Analytics und Entscheidungsunterstützung

KI-Systeme fragen Datenbanken ab, rufen APIs auf, interpretieren KPIs oder empfehlen Aktionen. Das Modell kann technisch funktionieren, während die zugrunde liegenden Geschäftsdaten, Definitionen oder Zugriffswege schwach bleiben.

Der Mechanismus unterscheidet sich. Das Governance-Prinzip bleibt gleich:

> **Qualität, Bedeutung und Herkunft der Eingaben begrenzen die Vertrauenswürdigkeit der Ausgaben.**

## Die gefährliche Umwandlung: unbekannt → geschätzt → Fakt

Datenpipelines müssen Lücken häufig schließen, damit Reports, Prozesse oder Modelle weiterarbeiten können.

Eine typische Kette sieht so aus:

1. Ein Pflichtwert fehlt in der Quelle.
2. Eine Transformation setzt einen Standard-, Mapping- oder Schätzwert ein.
3. Der korrigierte Datensatz wird in eine kuratierte Tabelle übernommen.
4. Die Kennzeichnung der Schätzung wird entfernt oder nie angelegt.
5. Der Wert wird in Features, Kennzahlen, Retrieval oder Modelleingaben weiterverwendet.
6. Die KI liefert eine Antwort, ohne sichtbar zu machen, dass ein Teil ihrer Grundlage nur abgeleitet wurde.

Der Downstream-Datensatz kann technisch vollständiger sein. Er ist dadurch nicht automatisch genauer.

Diese Unterscheidung ist entscheidend:

- **Beobachtet** bedeutet, dass der Wert aus einem Ereignis oder autoritativen System übernommen wurde.
- **Korrigiert** bedeutet, dass ein bekannter Fehler nach einer definierten Regel repariert wurde.
- **Abgeleitet** bedeutet, dass der Wert aus anderen Informationen berechnet wurde.
- **Geschätzt oder imputiert** bedeutet, dass der Wert wegen fehlender Informationen erschlossen wurde.
- **Synthetisch** bedeutet, dass der Wert generiert und nicht beobachtet wurde.
- **Unbekannt** bedeutet, dass keine verlässliche Evidenz vorhanden ist.

Diese Zustände dürfen nicht unbemerkt zu einem generischen „bereinigten“ Wert zusammenfallen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/trash-iinout-img1-de.png"
        alt="Quelldaten mit Lücken, Fehlern, Duplikaten, inkonsistenten Definitionen, veralteten Datensätzen, Schätzwerten und Verzerrungen werden für KI aufbereitet und können wertvolle Ergebnisse oder Halluzinationen, irreführende Kennzahlen und schlechte Entscheidungen verstärken"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Aufbereitung kann Daten verbessern. Ungelöste Lücken, versteckte Schätzwerte und Quellfehler verschwinden jedoch nicht dadurch, dass der Datensatz von KI genutzt wird.
    </figcaption>
</figure>

## Datenqualitätsprobleme werden auf unterschiedliche Weise zu KI-Problemen

| Eingabeproblem | Mögliche Auswirkung auf KI | Governance-Frage |
| --- | --- | --- |
| **Fehlende Werte** | Das Modell ersetzt sie durch Muster, Standardwerte oder benachbarte Informationen | Ist die Lücke sichtbar, erklärt und für diesen Zweck akzeptabel? |
| **Versteckte Schätzwerte** | Annahmen werden wie beobachtete Evidenz behandelt | Lassen sich beobachtete, abgeleitete und geschätzte Werte unterscheiden? |
| **Inkonsistente Definitionen** | Unterschiedliche Bedeutungen werden in einer Antwort oder einem Feature vermischt | Welche Definition ist verbindlich und wo ist sie dokumentiert? |
| **Veraltete Datensätze** | Empfehlungen beruhen auf einem nicht mehr aktuellen Zustand | Welche Aktualität ist erforderlich und wie wird sie überwacht? |
| **Duplikate** | Bestimmte Ereignisse oder Gruppen werden unbeabsichtigt übergewichtet | Welche Entitäts- und Deduplizierungsregeln gelten? |
| **Fehlende Abdeckung** | Das Modell lernt hauptsächlich aus verfügbaren, erfolgreichen oder leicht erfassbaren Fällen | Welche Personengruppen, Szenarien oder Fehlerfälle fehlen? |
| **Unterbrochene Lineage** | Eine fehlerhafte Ausgabe kann nicht bis zur Ursache zurückverfolgt werden | Welche Quelle, Transformation und Dataset-Version lieferten die Evidenz? |
| **Unkontrollierte Korrekturen** | Mehrere Teams reparieren denselben Fehler unterschiedlich | Wer verantwortet die Regel und die Ursachenbehebung? |

Nicht jede Lücke muss gefüllt werden. Manchmal ist der genaueste Wert weiterhin **unbekannt**.

Für KI kann es sicherer sein, diese Unsicherheit zu erhalten, als künstliche Vollständigkeit zu erzeugen.

## Halluzination ist ein zusätzliches Risiko — kein anderes Wort für schlechte Daten

Schlechte Datenqualität und Halluzinationen hängen zusammen, sind aber nicht dasselbe Problem.

Schwacher, widersprüchlicher oder unvollständiger Kontext kann die Wahrscheinlichkeit einer unbelegten oder irreführenden Antwort erhöhen. Ein generatives Modell kann jedoch auch dann falsche oder erfundene Inhalte erzeugen, wenn die verfügbaren Daten korrekt sind.

Mögliche Ursachen sind:

- der probabilistische Generierungsprozess
- mehrdeutige Anweisungen
- Abruf des falschen Kontextes
- fehlender oder abgeschnittener Kontext
- widersprüchliche Quellen
- schwaches Grounding
- ungeeignetes Modellverhalten für die Aufgabe
- fehlende Evaluation und Ausgabekontrollen

Bessere Daten sind deshalb notwendig, aber nicht ausreichend.

Eine saubere Tabelle macht aus einem Sprachmodell keine Datenbank. Eine dokumentierte Wissensbasis garantiert nicht, dass jede generierte Antwort auf ihr basiert. Retrieval-Augmented Generation kann bestimmte Risiken reduzieren, beseitigt unbelegte Generierung aber nicht vollständig.

Die Gefahr besteht häufig nicht darin, dass eine Antwort offensichtlich falsch aussieht.

Sie besteht darin, dass sie schlüssig, flüssig und überzeugend klingt.

> **Plausibilität ist kein Beleg. Selbstsicherheit ist keine Korrektheit.**

Eine governed KI-Anwendung benötigt deshalb Kontrollen für beides:

1. Qualität und Herkunft ihrer Daten und
2. Zuverlässigkeit und erlaubte Nutzung ihrer erzeugten Ausgabe.

## Schätzwerte sind kein Trash — unsichtbare Schätzwerte sind das Risiko

Geschäftsdaten werden nie vollkommen vollständig sein.

Forecasts, Allokationen, Imputationen und Näherungswerte sind legitime Bestandteile vieler Prozesse. Ein Planungsmodell ohne Schätzwerte wäre häufig nicht nutzbar. Historische Datensätze können selbst nach Verbesserung des Quellprozesses unvollständig bleiben.

Das Ziel besteht daher nicht darin, geschätzte Werte zu verbieten.

Ihr Status muss steuerbar bleiben.

Ein geschätzter oder imputierter Wert sollte mindestens folgende Metadaten besitzen:

| Metadatum | Zweck |
| --- | --- |
| **Wertstatus** | Beobachtet, korrigiert, abgeleitet, geschätzt, synthetisch oder unbekannt |
| **Methode** | Verwendete Regel, Modell, Interpolation, Standardwert oder manuelle Bewertung |
| **Grund** | Warum der ursprüngliche Wert nicht verfügbar oder ungültig war |
| **Konfidenz oder Unsicherheit** | Wie stark die Schätzung belastbar ist, sofern sinnvoll |
| **Quellnachweis** | Eingaben, aus denen der Wert erzeugt wurde |
| **Owner** | Rolle, die Methode und erlaubte Nutzung verantwortet |
| **Erlaubte Nutzung** | Prozesse, Reports oder Modelle, für die der Wert freigegeben ist |
| **Review-Datum** | Zeitpunkt, zu dem Annahme oder Methode erneut bewertet werden müssen |
| **Lineage** | Wo der Wert erzeugt und wo er verwendet wird |

Damit wird eine zentrale Unterscheidung möglich:

> **Eine governed Schätzung ist eine explizite fachliche Annahme. Eine ungekennzeichnete Schätzung wird zum versteckten Fakt.**

## Governance muss die vollständige KI-Kette abdecken

AI Governance darf nicht erst beim Modell beginnen.

Der vollständige Weg benötigt Kontrolle.

### 1. Governance von Quelle und Entstehungsprozess

Die Organisation muss wissen:

- welcher Prozess die Daten erzeugt
- welches System autoritativ ist
- wer die Quelle verantwortet
- welche Felder verpflichtend sind
- welche Fehler wiederholt auftreten
- welche Probleme am Entstehungsort behoben werden können

Eine Downstream-Korrektur kann den Betrieb vorübergehend schützen. Sie darf die ursprüngliche Ursache nicht unsichtbar machen.

Das passende Playbook [The Missing Pieces – Part 1: Data Quality](/playbooks/missing-pieces-data-quality) beschreibt den notwendigen Feedback Loop von Erkennung und Eindämmung über die Ursachenbehebung bis zur Validierung und zum kontrollierten Rückbau temporärer Workarounds.

### 2. Data-Product-Governance

Bevor Daten für KI freigegeben werden, müssen Teams Folgendes kennen:

- Verwendungszweck
- kritische Qualitätsdimensionen
- Qualitätsregeln und Schwellenwerte
- Anforderungen an Aktualität
- bekannte Einschränkungen
- fehlende Abdeckung
- Schätz- und Imputationslogik
- Zertifizierungsstatus
- Owner, Steward und technische Verantwortung
- Lineage und Version

Das umfassendere Betriebsmodell beschreibt [Data Quality Governance](/playbooks/data-quality-governance): Qualität muss mit Verwendungszweck, messbaren Regeln, Verantwortung, Monitoring und kontinuierlicher Verbesserung verbunden werden.

### 3. Modell- und Retrieval-Governance

Die KI-Schicht benötigt eigene Kontrollen:

- Modell- und Konfigurationsversion
- Version der Trainings-, Fine-Tuning- oder Evaluationsdaten
- Retrieval-Quellen und Ranking-Logik
- Version von Prompt und Systemanweisung
- erlaubte und verbotene Anwendungsfälle
- Evaluationsszenarien
- bekannte Einschränkungen
- Ausgabeschwellen und Fallback-Verhalten
- Änderungs- und Releasehistorie

Ein Modellupdate, eine neue Embedding-Version, ein veränderter Suchindex oder eine Prompt-Anpassung kann das Verhalten verändern, obwohl die zugrunde liegenden Geschäftsdaten gleich geblieben sind.

### 4. Anwendungs- und Output-Governance

Die konsumierende Anwendung sollte festlegen:

- wann Quellnachweise angezeigt werden müssen
- wann das System eine Antwort ablehnen muss
- wann eine menschliche Prüfung verpflichtend ist
- welche Entscheidungen niemals automatisiert werden dürfen
- wie Nutzerfeedback erfasst wird
- wie schädliche oder falsche Ausgaben eskaliert werden
- welche Prompts, Quellen und Ergebnisse protokolliert werden
- wie personenbezogene und vertrauliche Daten geschützt werden

Die Frage lautet nicht nur, ob das Modell eine Antwort erzeugen kann.

Sie lautet, ob die Anwendung nachweisen kann, **warum diese Antwort für diesen Zweck verwendet werden darf**.

## Ein praktisches Minimum vor der Verbindung von KI und Unternehmensdaten

### Die Entscheidung vor dem Modell definieren

Klären:

- Welche Entscheidung oder Aktion soll die Ausgabe unterstützen?
- Wer bleibt verantwortlich?
- Welche Auswirkung haben ein False Positive, False Negative oder eine erfundene Antwort?
- Ist die Aufgabe informierend, beratend oder autonom?

### Den Evidenzweg inventarisieren

Dokumentieren:

- Quellsysteme
- Transformationen
- fachliche Definitionen
- Schätzungen und Korrekturen
- Retrieval-Indizes
- Modell- und Prompt-Versionen
- konsumierende Anwendungen

### Unsicherheit erhalten

Null-Indikatoren, Schätzkennzeichen, Ausnahmezustände oder Qualitätswarnungen dürfen nicht nur deshalb entfernt werden, weil die Eingabe dadurch einfacher zu verarbeiten ist.

### Fakt und Annahme trennen

Soweit möglich, getrennte Felder oder Metadaten bereitstellen für:

- tatsächlichen Wert
- geschätzten Wert
- Schätzmethode
- Quellzeitpunkt
- Qualitätsstatus

### Reale Fehlerszenarien testen

Die Evaluation sollte nicht nur ideale Beispiele enthalten:

- unvollständige Datensätze
- widersprüchliche Quellen
- veraltete Dokumente
- ungewöhnliche Kategorien
- Grenzfälle
- fehlende Evidenz
- angreifende oder mehrdeutige Prompts
- Fälle, in denen Nicht-Antworten das korrekte Verhalten ist

### Nachvollziehbare Ausgaben verlangen

Bei faktenbasierten Unternehmensantworten sollte die Anwendung die relevante Quelle, den Datensatz, das Dokument oder die Kennzahlendefinition sichtbar machen können.

### Ablehnung und Eskalation definieren

Ein vertrauenswürdiges System benötigt einen kontrollierten Weg zu Aussagen wie:

- unzureichende Evidenz
- Widerspruch zwischen Quellen
- Daten nicht aktuell
- Qualitätsschwelle unterschritten
- menschliche Prüfung erforderlich

### Nach dem Release überwachen

Beobachten:

- Anteil belegter Antworten
- unbelegte Aussagen
- Retrieval-Fehler
- Verletzungen von Qualitätsregeln
- Nutzerkorrekturen
- wiederkehrende Fehlermuster
- Veränderungen nach Modell-, Prompt- oder Datenupdates

## Was vermieden werden sollte

### Eine kuratierte Tabelle als verifizierte Wahrheit behandeln

Eine Gold- oder Business-Schicht kann technisch sauber sein und trotzdem Schätzungen, geerbte Verzerrungen oder ungelöste Quellfehler enthalten.

### Unsicherheit bei der Aufbereitung entfernen

Wer Schätzkennzeichen, Qualitätsstatus oder Quellreferenzen entfernt, macht Daten leichter konsumierbar, aber schwerer vertrauenswürdig.

### Annehmen, dass RAG Halluzinationen beseitigt

Retrieval liefert Kontext. Das Modell kann weiterhin falschen Kontext auswählen, ihn ignorieren, fehlerhaft kombinieren oder über die vorhandene Evidenz hinaus generieren.

### KI Quelldaten ohne Auditierbarkeit reparieren lassen

KI-gestützte Korrektur kann sinnvoll sein. Jede Änderung benötigt jedoch Nachvollziehbarkeit, Review-Regeln und eine klare Trennung von beobachteten Werten.

### Nur durchschnittliche Performance messen

Ein hoher Durchschnitt kann schwere Fehler in seltenen, kritischen oder schwach repräsentierten Szenarien verdecken.

### Für jeden Anwendungsfall dieselben Kontrollen verwenden

Ein Assistent für Textentwürfe und eine autonome Zahlungsentscheidung benötigen nicht dieselbe Evidenz, Prüfung und Governance.

### Flüssige Sprache als Beweis interpretieren

Die Qualität der Formulierung sagt wenig über die Qualität der zugrunde liegenden Evidenz aus.

## Die eigentliche Bedeutung von Trash In, Trash Out

„Trash In, Trash Out“ sollte nicht als Aussage verstanden werden, dass jeder unvollkommene Datensatz unbrauchbar ist.

Es ist eine Warnung vor Skalierung.

KI kann mehr Daten verarbeiten, mehr Signale verbinden und mehr Ausgaben erzeugen, als ein menschliches Team manuell bewältigen könnte. Das schafft Mehrwert. Es bedeutet zugleich, dass ungelöste Fehler, versteckte Annahmen und fehlender Kontext schneller vervielfältigt und überzeugender präsentiert werden können.

Governance verändert das Ergebnis, indem die Kette sichtbar bleibt:

- Quellqualität wird gemessen
- Lücken bleiben erkennbar
- Schätzwerte bleiben Schätzwerte
- Transformationen bleiben nachvollziehbar
- Verantwortung ist eindeutig
- Modelle werden für ihren vorgesehenen Zweck evaluiert
- Ausgaben zeigen Evidenz und Einschränkungen
- Fehler gelangen zurück zum Source-, Data-Product-, Modell- oder Application-Owner

Das Ziel sind weder perfekte Daten noch risikofreie KI.

Das Ziel ist ein System, das zwischen **Fakt, Ableitung, Schätzung und Unsicherheit** unterscheiden kann.

> **Bessere Modelle ersetzen keine besseren Quellen.  
> Bessere Aufbereitung ersetzt keine Transparenz.  
> Bessere Antworten ersetzen keine Evidenz.**

## Verwandte Playbooks

- [The Missing Pieces – Part 1: Data Quality](/playbooks/missing-pieces-data-quality) — warum Downstream-Korrekturen einen Feedback Loop zur Quelle benötigen
- [Data Quality Governance](/playbooks/data-quality-governance) — das Betriebsmodell für zweckbezogene Qualität, Regeln, Verantwortung, Monitoring und Verbesserung

## Weiterführende Ressourcen

- [NIST AI Risk Management Framework](https://www.nist.gov/itl/ai-risk-management-framework)
- [NIST AI 600-1: Generative Artificial Intelligence Profile](https://www.nist.gov/publications/artificial-intelligence-risk-management-framework-generative-artificial-intelligence)
- [NIST AI RMF Playbook](https://airc.nist.gov/airmf-resources/playbook/)
- [Datasheets for Datasets](https://arxiv.org/abs/1803.09010)
- [Model Cards for Model Reporting](https://arxiv.org/abs/1810.03993)
