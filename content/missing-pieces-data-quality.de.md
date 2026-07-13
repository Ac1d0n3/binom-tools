---
title: "The Missing Pieces – Part 1: Data Quality"
description: "Warum korrigierte Daten downstream nicht automatisch bedeuten, dass die Ursache an der Quelle behoben wurde – und wie Governance den Kreislauf schließen kann."
author: Thomas Lindackers
category: Data Governance
tags:
  - the-missing-pieces
  - data-quality
  - root-cause-analysis
  - data-governance
  - data-stewardship
  - dbt
  - data-pipelines
order: -1
hero: images/playbooks/mp-dq-hero.png
series: missing-pieces
seriesPart: 1
seriesTitle: The Missing Pieces
---

## Daten können besser werden, während die Ursache unverändert bleibt

Moderne Datenplattformen sind sehr gut darin geworden, Daten zu replizieren, zu prüfen, zu standardisieren und schrittweise für analytische Nutzung aufzubereiten. Mehrstufige Architekturen wie Bronze, Silver und Gold verfolgen genau dieses Ziel: Rohdaten werden aufgenommen, anschließend validiert und bereinigt und schließlich fachlich nutzbar bereitgestellt.

Das ist grundsätzlich sinnvoll. Unterschiedliche Schichten schaffen Nachvollziehbarkeit, Wiederholbarkeit, Isolation und klare Verantwortungsbereiche innerhalb einer Datenplattform.

Trotzdem bleibt eine wichtige Governance-Frage häufig unbeantwortet:

> **Verbessern wir den Prozess, der fehlerhafte Daten erzeugt – oder werden wir nur immer besser darin, seine Fehler downstream zu kompensieren?**

Eine Transformation kann einen falschen Wert korrigieren. Sie kann fehlende Daten ergänzen, ungültige Statuswerte ummappen oder Ausnahmen abfangen. Dadurch steigt die Qualität des nachgelagerten Datensatzes.

Der Geschäftsprozess, die Eingabemaske, die Schnittstelle oder das Quellsystem, das den Fehler erzeugt hat, kann dabei jedoch unverändert bleiben.

Genau diese Unterscheidung ist der Ausgangspunkt dieses Playbooks.

## Transformation ist nicht das Problem

Transformationen sind ein unverzichtbarer Bestandteil moderner Datenarchitekturen. Viele Regeln gehören dauerhaft in die Datenplattform, weil sie keinen Fehler reparieren, sondern Daten für einen neuen Zweck nutzbar machen.

| Art der Transformation | Beispiel | Langfristig sinnvoll? |
| --- | --- | --- |
| Technische Standardisierung | Datumsformate, Zeitzonen oder Datentypen vereinheitlichen | Ja |
| Integration | Kunden- oder Produktdaten aus mehreren Systemen zusammenführen | Ja |
| Harmonisierung | Unterschiedliche Codes auf ein gemeinsames fachliches Modell abbilden | Ja |
| Fachliche Modellierung | Umsatz, Marge, Churn oder andere Kennzahlen berechnen | Ja |
| Historisierung | Zeitabhängige Zustände und Veränderungen nachvollziehbar machen | Ja |
| Kompensation eines vermeidbaren Source-Fehlers | Fehlende Pflichtwerte dauerhaft künstlich ergänzen | Kritisch |
| Dauerhafter Workaround | Ungültige Statuswerte in jeder neuen Schicht erneut korrigieren | Nein, wenn die Ursache behebbar ist |

Die relevante Frage ist daher nicht:

> *Sollten wir weniger transformieren?*

Sondern:

> **Erzeugt diese Transformation neuen fachlichen Wert – oder kompensiert sie dauerhaft ein Problem, das an seiner Entstehungsstelle behoben werden sollte?**

## Wenn aus einem temporären Fix eine dauerhafte Abhängigkeit wird

Ein Downstream-Fix kann vollkommen richtig sein. Ein Bericht, eine Abrechnung oder ein regulatorischer Prozess kann nicht immer warten, bis ein operatives System angepasst wurde. Eine Transformationsregel kann die Auswirkungen eines Fehlers schnell begrenzen und den Geschäftsbetrieb schützen.

Das Problem entsteht, wenn der temporäre Charakter verloren geht.

Ein typisches Muster sieht so aus:

Source Error  
→ temporäre Mapping-Regel  
→ zusätzliche Ausnahme im Staging  
→ weitere Harmonisierung im Conformed Layer  
→ Sonderlogik im Business Layer  
→ lokale Korrektur im BI-Report  
→ zusätzlicher Excel-Workaround

Der ursprüngliche Fehler ist für die Nutzer möglicherweise nicht mehr sichtbar. Gleichzeitig bleibt die Quelle unverändert und produziert weiterhin dieselbe Abweichung.

Mit jeder zusätzlichen Schicht entstehen neue Abhängigkeiten:

- dieselbe fachliche Annahme wird an mehreren Stellen implementiert,
- Änderungen müssen mehrfach nachvollzogen werden,
- unterschiedliche Teams können unterschiedliche Korrekturen entwickeln,
- die ursprüngliche Ursache wird im Datenfluss immer schwerer erkennbar,
- neue Data Products übernehmen möglicherweise nur einen Teil der vorhandenen Reparaturlogik.

So kann eine erfolgreiche kurzfristige Maßnahme langfristig zu technischer und fachlicher Schuld werden.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-dq-img1-de.png"
        alt="Wie dauerhafte Downstream-Korrekturen zusätzliche Komplexität und Abhängigkeiten erzeugen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein Fehler kann downstream mehrfach korrigiert werden, während seine Ursache im Quellsystem unverändert bleibt.
    </figcaption>
</figure>

## Erkennen ist nicht dasselbe wie Beheben

Data-Quality-Werkzeuge und Tests sind wichtig, weil sie Probleme sichtbar und messbar machen.

Microsoft Purview beschreibt Data Quality als die Bewertung und Überwachung von Datenbeständen, um gezielte Verbesserungsmaßnahmen zu ermöglichen. dbt Data Tests prüfen definierte Annahmen über Quellen und Modelle und liefern die Datensätze zurück, die eine Erwartung verletzen. Source Freshness überwacht zusätzlich, ob Quelldaten innerhalb eines erwarteten Zeitfensters aktualisiert wurden.

Diese Funktionen beantworten wichtige Fragen:

- Sind Pflichtfelder vollständig?
- Sind Schlüssel eindeutig?
- Liegen Werte innerhalb eines gültigen Bereichs?
- Stimmen Beziehungen zwischen Datensätzen?
- Sind Daten aktuell genug?
- Wo und seit wann tritt eine Abweichung auf?

Sie beantworten jedoch nicht automatisch:

- Warum erzeugt der Prozess den Fehler?
- Wer besitzt den verursachenden Geschäftsprozess?
- Muss die Eingabevalidierung, Schnittstelle oder Anwendung angepasst werden?
- Bis wann soll die Ursache behoben sein?
- Wann kann die temporäre Transformationsregel wieder entfernt werden?

Das ist keine Schwäche einzelner Produkte. Es ist die Grenze zwischen technischer Erkennung und organisatorischer Ursachenbehebung.

## Der fehlende Feedback Loop

Nachhaltige Datenqualität benötigt einen geschlossenen Kreislauf zwischen Datenplattform und Entstehungsprozess.

Ein mögliches Zielmodell besteht aus sieben Schritten:

1. **Erkennen**  
   Das Problem wird über Profiling, Regeln, Tests, Monitoring oder Nutzerfeedback sichtbar.

2. **Auswirkung eindämmen**  
   Eine temporäre Transformation, Quarantäne-Regel oder fachliche Ausnahme schützt nachgelagerte Prozesse.

3. **Verantwortung zuweisen**  
   Quellsystem, Geschäftsprozess und verantwortlicher Owner werden identifiziert.

4. **Ursache analysieren**  
   Es wird geprüft, warum der Fehler entsteht: fehlende Validierung, unklare Prozessregel, Schnittstellenproblem, Bedienfehler oder falsche Systemlogik.

5. **An der Quelle beheben**  
   Prozess, Anwendung, Schnittstelle oder Validierung werden so angepasst, dass der Fehler nicht weiter erzeugt wird.

6. **Validieren und überwachen**  
   Die Verbesserung wird nachgewiesen und über einen definierten Zeitraum beobachtet.

7. **Temporären Fix entfernen**  
   Nicht mehr benötigte Reparaturlogik wird kontrolliert aus der Pipeline entfernt.

Der letzte Schritt ist besonders wichtig. Bleibt eine alte Korrektur nach dem Source Fix bestehen, kann sie selbst neue Fehler erzeugen oder zukünftige Daten falsch interpretieren.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-dq-img2-de.png"
        alt="Geschlossener Governance-Kreislauf von der Erkennung bis zur Ursachenbehebung und Entfernung des temporären Fixes"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Data Quality wird nachhaltiger, wenn Erkennung, Eindämmung, Ownership, Ursachenbehebung und kontrollierter Rückbau als ein gemeinsamer Lebenszyklus gesteuert werden.
    </figcaption>
</figure>

## Welche Rolle spielt der Data Steward?

Der Data Steward sollte nicht automatisch jede technische oder fachliche Ursache selbst beheben. Er ist auch nicht zwingend der Owner des Quellsystems.

Seine zentrale Aufgabe kann darin bestehen, den Qualitätsprozess über Systemgrenzen hinweg steuerbar zu machen:

- Qualitätsproblem und Business Impact dokumentieren,
- betroffene Datenprodukte und Verbraucher identifizieren,
- Source Owner und Process Owner einbinden,
- temporäre Korrektur transparent machen,
- Status, Priorität und Zieltermin nachverfolgen,
- Validierung der Source-Korrektur koordinieren,
- Entscheidung über den Rückbau des Workarounds nachvollziehbar machen.

Damit wird Stewardship mehr als die Pflege eines Katalogeintrags. Es wird zur Verbindung zwischen technischer Beobachtung und fachlicher Verantwortung.

Die eigentliche Änderung bleibt dort, wo sie hingehört:

- Der **Process Owner** verantwortet den Geschäftsprozess.
- Der **System Owner** verantwortet die Anwendung oder Schnittstelle.
- Das **Data Team** schützt die Datenprodukte und setzt notwendige temporäre Kontrollen um.
- Der **Data Steward** koordiniert Definition, Transparenz, Eskalation und Nachverfolgung.

## Temporäre Korrekturen brauchen einen Lebenszyklus

Ein Fix sollte nicht nur aus SQL, einer Mapping-Tabelle oder einer zusätzlichen Business Rule bestehen. Er benötigt Governance-Metadaten.

Mindestens folgende Informationen sollten nachvollziehbar sein:

| Information | Leitfrage |
| --- | --- |
| Ursache | Welcher Prozess oder welches System erzeugt den Fehler? |
| Business Impact | Welche Berichte, Kennzahlen, Prozesse oder Entscheidungen sind betroffen? |
| Verantwortlichkeit | Wer besitzt Quelle und Ursachenbehebung? |
| Temporäre Maßnahme | Wo und wie wird die Auswirkung aktuell begrenzt? |
| Gültigkeit | Ab wann gilt der Fix und wann wird er überprüft? |
| Zielzustand | Welche Änderung an der Quelle soll den Fehler dauerhaft verhindern? |
| Validierung | Woran erkennen wir, dass die Ursache tatsächlich behoben ist? |
| Rückbau | Welche Regel, welches Mapping oder welche Ausnahme kann danach entfernt werden? |

Ein Ablaufdatum muss nicht bedeuten, dass eine Regel automatisch gelöscht wird. Es kann zunächst ein verpflichtendes Review auslösen.

Der wesentliche Punkt ist: **Ein temporärer Fix darf nicht unsichtbar zu einem dauerhaften Bestandteil der Architektur werden.**

## Nicht jeder Fehler kann sofort an der Quelle gelöst werden

Auch ein geschlossenes Governance-Modell braucht Pragmatismus.

Eine Source-Korrektur kann aus guten Gründen Zeit benötigen:

- ein externes SaaS-System lässt sich nur eingeschränkt anpassen,
- eine Lieferantenschnittstelle liegt außerhalb der eigenen Kontrolle,
- eine Legacy-Anwendung wird erst später ersetzt,
- historische Daten bleiben trotz Prozessverbesserung fehlerhaft,
- regulatorische oder operative Fristen erfordern eine sofortige Absicherung.

In solchen Fällen kann eine dauerhafte oder langfristige Transformation die wirtschaftlich sinnvollste Lösung sein.

Entscheidend ist, dass diese Entscheidung bewusst getroffen wird:

> **Nicht jeder Downstream-Fix ist schlechte Architektur. Problematisch wird er, wenn niemand mehr weiß, warum er existiert, wer ihn verantwortet und ob er noch benötigt wird.**

## Praktische Leitfragen für Teams

Bei jeder neuen Data-Quality-Regel oder Reparaturtransformation können fünf Fragen helfen:

1. **Ist das eine fachliche Transformation oder die Reparatur eines vermeidbaren Fehlers?**
2. **Kann die Ursache im Quellsystem oder Geschäftsprozess beeinflusst werden?**
3. **Wer besitzt die Ursachenbehebung – nicht nur den Downstream-Fix?**
4. **Hat die temporäre Korrektur ein Review-Datum und ein definiertes Zielbild?**
5. **Wie wird nachgewiesen, dass der Fix später entfernt oder vereinfacht werden kann?**

Diese Fragen ersetzen keine Data-Quality-Plattform. Sie ergänzen sie um Verantwortung und Lebenszyklus.

## Fazit

Moderne Datenplattformen können Datenqualität innerhalb einer Pipeline deutlich verbessern. Tests, Profiling, Monitoring und Transformationen sind dafür unverzichtbar.

Doch eine hochwertige Gold-Tabelle beweist nicht automatisch, dass der erzeugende Geschäftsprozess hochwertige Daten liefert.

Die fehlende Komponente ist häufig nicht noch ein weiterer Test oder eine weitere Transformationsschicht. Es ist der geschlossene Feedback Loop:

> **Detect the issue. Contain the impact. Assign ownership. Fix the source. Validate the result. Remove the workaround.**

Die entscheidende Frage lautet deshalb:

> **Verbessern wir die Systeme, die Daten erzeugen – oder kompensieren wir nur immer effizienter ihre wiederkehrenden Probleme?**

## Weiterführende Ressourcen

- [Databricks: What is the medallion lakehouse architecture?](https://docs.databricks.com/aws/en/lakehouse/medallion)
- [dbt Developer Hub: Add data tests to your DAG](https://docs.getdbt.com/docs/build/data-tests)
- [dbt Developer Hub: Source freshness](https://docs.getdbt.com/docs/deploy/source-freshness)
- [Microsoft Learn: Overview of data quality in Microsoft Purview Unified Catalog](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality)
- [Microsoft Learn: Data quality actions in Unified Catalog](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality-actions)
- [IBM: What is data quality?](https://www.ibm.com/think/topics/data-quality)
- [IBM: What is root cause analysis?](https://www.ibm.com/think/topics/root-cause-analysis)
