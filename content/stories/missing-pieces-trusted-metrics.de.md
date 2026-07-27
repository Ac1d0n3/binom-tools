---
title: "Die fehlenden Bausteine – Teil 2: Vertrauenswürdige Kennzahlen"
description: "Warum zentralisierte Daten nicht automatisch zu konsistenten Geschäftskennzahlen führen – und wie gemeinsame Definitionen, sichtbare Ownership und verständliche Governance Vertrauen stärken können."
author: Thomas Lindackers
category: Data Governance
tags:
  - the-missing-pieces
  - trusted-metrics
  - kpi-governance
  - semantic-layer
  - business-glossary
  - data-stewardship
  - self-service-bi
  - data-governance
order: -1
hero: images/playbooks/mp-metrics-hero.png
publishedAt: 2026-07-13
series: missing-pieces
seriesPart: 2
seriesTitle: Die fehlenden Bausteine
---
## Zentrale Daten bedeuten nicht automatisch eine zentrale Wahrheit

Unternehmen investieren viel in zentrale Datenplattformen, wiederverwendbare Datenprodukte, zertifizierte Datensätze und gemeinsame Governance-Standards. Das Ziel ist nachvollziehbar: Fachbereiche sollen mit konsistenten und vertrauenswürdigen Informationen arbeiten können.

Gleichzeitig entsteht fachliche Bedeutung heute an vielen Stellen:

```flow linear vertical
Source Data
Transformation / dbt Model
SQL View / Data Mart
Semantic Model
BI Measure
Report Expression
Anwendungslogik
Excel-Formel
```

Jede dieser Ebenen kann einen legitimen Zweck erfüllen. Eine Kennzahl im Data Mart kann Wiederverwendung ermöglichen. Ein semantisches Modell kann Geschäftsbegriffe und Berechnungen für verschiedene Reports bereitstellen. Eine Report-Kennzahl kann einen lokalen Analysebedarf abdecken. Excel kann für Fachanwender der schnellste und vertrauteste Weg sein, Daten weiterzuverarbeiten.

Die Governance-Frage lautet daher nicht:

> *Welche dieser Ebenen ist falsch?*

Sondern:

> **Wie verhindern wir, dass dieselbe fachliche Kennzahl unabhängig an mehreren Stellen neu definiert wird?**

Denn zentralisierte Daten lösen nicht automatisch das Problem verteilter Geschäftslogik.

## Warum Kennzahlen an mehreren Stellen entstehen

Nicht jede Kennzahl lässt sich einmal definieren und für immer unverändert verwenden. Geschäftsmodelle, Produkte, Verträge, Marktbedingungen und regulatorische Anforderungen verändern sich. Unterschiedliche Nutzergruppen benötigen außerdem unterschiedliche Detailstufen und Analysekontexte.

Deshalb existieren mehrere sinnvolle Orte für Berechnungen:

| Ebene | Typischer Zweck | Governance-Frage |
| --- | --- | --- |
| Transformation / dbt Model | stabile, wiederverwendbare Geschäftslogik und vorbereitete Fakten | Ist die Definition fachlich abgestimmt und versioniert? |
| SQL View / Data Mart | domänenspezifische Sichten und performante Bereitstellung | Entsteht hier eine neue KPI-Variante oder nur eine neue Sicht? |
| Semantic Model | gemeinsame Begriffe, Beziehungen, Measures und Filterkontext | Wird die Kennzahl kanalübergreifend wiederverwendet? |
| BI Measure | interaktive Berechnung für Reports und Dashboards | Ist sie lokal oder unternehmensweit relevant? |
| Report Expression | spezieller Kontext für eine Visualisierung oder Analyse | Verändert die lokale Logik die Bedeutung der Kennzahl? |
| Anwendung | fachlicher Workflow, Simulation oder operative Entscheidung | Ist die Berechnung dokumentiert und mit dem zentralen Modell abgestimmt? |
| Excel | flexible Analyse, Ergänzung, Forecast oder operative Arbeit | Wann wird eine lokale Formel geschäftskritisch? |

Das Ziel kann deshalb nicht sein, jede Berechnung technisch an genau einen Ort zu zwingen. Eine zu starre Zentralisierung kann Self-Service blockieren und neue Anforderungen unnötig verlangsamen.

Die bessere Leitlinie ist:

> **Define shared meaning centrally. Allow local analysis without silently redefining the shared meaning.**

## Wenn aus einer Kennzahl viele technisch plausible Versionen werden

Ein Beispiel: Das Unternehmen verwendet die Kennzahl **Revenue**.

Auf den ersten Blick klingt die Definition einfach. In der Praxis können jedoch viele Fragen entstehen:

- Brutto- oder Nettoumsatz?
- Rechnungsdatum, Buchungsdatum oder Leistungszeitraum?
- Stornierungen sofort oder rückwirkend berücksichtigen?
- Einmalige Umsätze und wiederkehrende Umsätze gemeinsam oder getrennt?
- Nur aktive Kunden?
- Welche Währung und welcher Wechselkurs?
- Welche organisatorische Zuordnung gilt bei rückwirkenden Änderungen?
- Sind interne Umsätze enthalten?

Wenn diese Entscheidungen an mehreren Stellen unabhängig getroffen werden, können mehrere Ergebnisse entstehen, obwohl jede einzelne Berechnung technisch korrekt ausgeführt wird.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-metrics-img1-de.png"
        alt="Darstellung, wie dieselbe KPI über Transformation, Data Mart, semantisches Modell, BI, Anwendung und Excel in unterschiedliche Versionen aufgeteilt werden kann"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Viele Ebenen können fachlich sinnvoll sein. Vertrauen wird jedoch schwächer, wenn dieselbe Definition an mehreren Stellen unabhängig neu implementiert wird.
    </figcaption>
</figure>

Das bekannte Meeting-Szenario mit mehreren Personen und mehreren Zahlen ist deshalb kein Beweis dafür, dass eine Plattform versagt hat. Es ist vielmehr ein mögliches Symptom dafür, dass die Berechnungen unterschiedliche Definitionen, Filter, Zeiträume oder Kontexte verwenden.

Die Zahlen können jeweils korrekt sein – aber möglicherweise beantworten sie nicht dieselbe Frage.

## Technisch gültig ist nicht automatisch fachlich vergleichbar

Ein häufiger Reflex lautet: *Es muss eine einzige Zahl geben.*

Das ist nicht immer richtig.

Finance kann Umsatz nach Rechnungslegung betrachten. Sales kann gebuchten Vertragswert betrachten. Customer Success kann wiederkehrenden Umsatz aktiver Kunden analysieren. Alle drei Kennzahlen können legitim sein.

Das Governance-Problem entsteht erst, wenn unterschiedliche Kennzahlen denselben Namen tragen oder ihr Kontext nicht sichtbar ist.

Vertrauen benötigt deshalb mehr als technische Konsistenz. Nutzer müssen erkennen können:

- **Was** wird gemessen?
- **Warum** wird es gemessen?
- **Welche Regeln** gelten?
- **Welcher Zeitraum** und welcher Filterkontext werden verwendet?
- **Welche Datenquellen** fließen ein?
- **Wer** verantwortet die fachliche Definition?
- **Für welchen Zweck** ist die Kennzahl freigegeben?
- **Welche Varianten** existieren bewusst?

Eine gute Governance-Lösung muss nicht jede Variante verhindern. Sie muss Varianten verständlich und vergleichbar machen.

## Semantic Layers adressieren genau diese Lücke

Die zunehmende Bedeutung semantischer Ebenen zeigt, dass zentrale Speicherung allein nicht ausreicht. Fachliche Definitionen müssen ebenfalls wiederverwendbar werden.

Die dbt Semantic Layer ist beispielsweise darauf ausgelegt, kritische Geschäftskennzahlen zentral zu definieren und über verschiedene nachgelagerte Werkzeuge verfügbar zu machen. Microsoft beschreibt Managed Self-Service BI als ein Modell, bei dem viele Report-Ersteller zentrale, gemeinsam genutzte Semantic Models wiederverwenden. Qlik Master Measures kombinieren eine Expression mit Name, Beschreibung und Tags, damit Kennzahlen innerhalb einer Anwendung wiederverwendet werden können.

Diese Ansätze lösen nicht jedes Governance-Problem. Sie zeigen aber eine gemeinsame Richtung:

> **Business logic should be reusable as a governed product – not repeatedly rebuilt as local code.**

Wichtig ist dabei die Balance. Ein zentrales semantisches Modell darf nicht zu einem monolithischen Objekt werden, das jede denkbare Fragestellung abbilden muss. Auch Microsoft weist bei Managed Self-Service BI darauf hin, dass weder ein einziges Modell für alle Unternehmensdaten noch ein neues Modell für jeden einzelnen Report sinnvoll ist.

Das Ziel ist nicht maximale Zentralisierung, sondern kontrollierte Wiederverwendung.

## Excel ist nicht das Problem

Excel wird in Governance-Diskussionen häufig als Gegenpol zur zentralen Datenplattform dargestellt. Diese Sicht ist zu einfach.

Excel erfüllt reale Anforderungen:

- Fachanwender kennen das Werkzeug.
- Analysen lassen sich schnell anpassen.
- Daten können ergänzt, kommentiert und geplant werden.
- Ad-hoc-Fragen benötigen nicht immer einen neuen produktiven Report.
- Viele operative Prozesse basieren weiterhin auf Tabellenmodellen.

Das Governance-Risiko entsteht nicht durch die Datei selbst. Es entsteht, wenn geschäftskritische Logik außerhalb sichtbarer Ownership, Dokumentation und Validierung wächst.

Ein möglicher Weg ist, Excel weiterhin als Analyseoberfläche zu nutzen, aber die Daten und Kernkennzahlen aus einem gemeinsamen semantischen Modell zu beziehen. Microsoft unterstützt beispielsweise Live-Verbindungen von Excel zu Power-BI-Semantikmodellen. Dadurch können Anwender in ihrer vertrauten Oberfläche arbeiten und dennoch eine gemeinsame Datenbasis nutzen.

Trotzdem bleibt eine Grenze:

```flow linear vertical
Trusted Semantic Model
Excel Pivot / Connected Table
lokale Formel
manuelle Anpassung
neue Datei oder Kopie
geschäftliche Entscheidung
```

Ab welchem Schritt wird aus persönlicher Analyse eine neue fachliche Definition?

Die entscheidende Frage lautet daher nicht:

> *Wie verhindern wir Excel?*

Sondern:

> **Wie erkennen wir, wann lokale Logik wiederverwendet, dokumentiert oder in ein gemeinsames Modell überführt werden sollte?**

## „Trusted“ ist mehr als ein Badge

Zertifizierungen und Endorsements sind sinnvoll. Sie helfen Nutzern, hochwertige und offiziell freigegebene Inhalte zu finden. Power BI unterstützt beispielsweise die Kennzeichnungen *Promoted* und *Certified*, um vertrauenswürdige Inhalte sichtbarer zu machen.

Ein Badge allein beantwortet jedoch nicht alle Fragen:

- Ist die Kennzahl fachlich eindeutig definiert?
- Ist der Owner sichtbar und erreichbar?
- Sind Filter, Ausschlüsse und Zeitlogik verständlich?
- Gilt die Zertifizierung nur für das Semantic Model oder auch für lokale Report-Änderungen?
- Wird die Kennzahl in einer Anwendung oder Excel-Datei weiter angepasst?
- Wann wurde die Definition zuletzt geprüft?

Deshalb sollte „Trusted“ nicht nur ein Status sein, sondern ein nachvollziehbares Versprechen:

> **A trusted metric is understandable, owned, validated, reusable and transparent about its context.**

Vertrauen entsteht nicht dadurch, dass jede Person dieselbe Zahl sehen muss. Es entsteht dadurch, dass jede Person verstehen kann, warum eine Zahl gilt – und ob sie mit einer anderen Zahl vergleichbar ist.

## Governance muss für die Menschen funktionieren, die sie pflegen

KPI Governance wird häufig als technisches Metadatenproblem behandelt. Dann stehen Tabellen, Spalten, Lineage, SQL-Ausdrücke und Plattformobjekte im Mittelpunkt.

Business User und Data Stewards denken jedoch oft anders:

- *Was bedeutet die Kennzahl?*
- *Wann darf ich sie verwenden?*
- *Welche Variante gilt für meinen Prozess?*
- *Wer entscheidet über eine Änderung?*
- *Warum unterscheidet sich mein Ergebnis vom Finance-Report?*

Wenn die Beantwortung dieser Fragen tiefes Wissen über technische Lineage, Datenbankschemas oder Tool-spezifische Begriffe voraussetzt, bleibt Governance auf Spezialisten beschränkt.

Forschung zu Data Catalogs beschreibt genau diese Herausforderung: Kataloge können Metadaten leicht erfassbar, aber schwer auffindbar machen – oder umgekehrt. Unterschiedliche Nutzergruppen besitzen unterschiedliche Kenntnisse und benötigen verständliche mentale Modelle.

Daraus folgt eine praktische Designregel:

> **Governance participation should be easier than bypassing governance.**

Ein Data Steward sollte nicht erst SQL lesen müssen, um eine KPI zu verstehen. Eine fachliche Definition sollte in Business-Sprache sichtbar sein. Technische Details bleiben wichtig, sollten aber als vertiefende Ebene verfügbar sein – nicht als Einstiegshürde.

## Rollen rund um eine vertrauenswürdige Kennzahl

Eine einzelne Rolle kann nicht alle Aufgaben übernehmen. Je nach Organisation können die Bezeichnungen variieren, aber die Verantwortungen sollten sichtbar sein.

| Rolle | Mögliche Verantwortung |
| --- | --- |
| Business Owner / Metric Owner | fachliche Bedeutung, Zweck, Freigabe und Priorisierung |
| Data Steward | Definition, Kontext, Abstimmung, Qualität, Änderungsprozess und Transparenz |
| Data Product Owner | Bereitstellung und Lebenszyklus des zugrunde liegenden Datenprodukts |
| Analytics Engineer / BI Developer | technische Implementierung und Tests der Berechnungslogik |
| Report Owner | korrekte Verwendung, lokale Erweiterungen und Kommunikationskontext |
| Business User | Nutzung, Feedback und Meldung von Unklarheiten oder neuen Anforderungen |

Der Data Steward muss nicht jede DAX-, SQL-, Qlik- oder Excel-Formel selbst entwickeln. Seine Rolle kann darin bestehen, die fachliche Definition und den Änderungsprozess über technische Grenzen hinweg sichtbar zu halten.

## Ein mögliches Zielbild für Trusted Metrics

Ein nachhaltiges Modell kann aus sieben Schritten bestehen:

1. **Fachlich definieren**  
   Die Kennzahl wird in verständlicher Geschäftssprache beschrieben – inklusive Zweck, Scope, Zeitbezug und Varianten.

2. **Ownership zuweisen**  
   Ein verantwortlicher Business Owner und ein Data Steward sind sichtbar.

3. **Zentral implementieren**  
   Berechnungslogik, Filter und Geschäftsregeln werden an einer geeigneten gemeinsamen Stelle gepflegt.

4. **Validieren und zertifizieren**  
   Datenqualität, Berechnung, Vergleichswerte und fachliche Abnahme werden geprüft.

5. **Über eine semantische Ebene bereitstellen**  
   Reports, Anwendungen und Analysewerkzeuge erhalten konsistenten Zugriff.

6. **Über mehrere Kanäle wiederverwenden**  
   BI, Anwendungen und Excel dürfen unterschiedliche Darstellungen erzeugen, ohne die Kernbedeutung neu zu erfinden.

7. **Nutzung und Feedback überwachen**  
   Fragen, Abweichungen und neue Anforderungen fließen in einen kontrollierten Verbesserungsprozess zurück.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-metrics-img2-de.png"
        alt="Zielmodell für vertrauenswürdige Kennzahlen mit fachlicher Definition, Ownership, zentraler Berechnung, Zertifizierung, semantischer Ebene und Wiederverwendung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Vertrauen entsteht durch gemeinsame Definition, sichtbare Verantwortung, verständlichen Kontext und Wiederverwendung – nicht allein durch ein technisches Label.
    </figcaption>
</figure>

## Wiederverwendung darf Flexibilität nicht verhindern

Ein gemeinsames Modell ist nur dann erfolgreich, wenn es tatsächlich genutzt wird.

Zu wenig Governance kann zu vielen unabhängigen KPI-Versionen führen. Zu viel Zentralisierung kann dazu führen, dass Fachbereiche die Plattform umgehen, weil jede Änderung zu langsam oder zu kompliziert wird.

Dazwischen liegt ein kontrolliertes Self-Service-Modell:

| Bedarf | Sinnvoller Weg |
| --- | --- |
| Bestehende Kennzahl anders visualisieren | gleiche zentrale Kennzahl wiederverwenden |
| Lokale Analyse mit anderem Filter | zentralen Metric Context nutzen und Filter transparent machen |
| Neue fachliche Variante | neue, klar benannte Variante mit Owner und Scope definieren |
| Experimentelle Kennzahl | als Draft oder lokal gekennzeichnet testen |
| Regelmäßig genutzte lokale Formel | Review und mögliche Überführung in das gemeinsame Modell |
| Geschäftsprozess-kritische Kennzahl | validieren, dokumentieren, versionieren und zertifizieren |

Governance sollte also nicht nur kontrollieren. Sie sollte einen einfachen Weg anbieten, aus einer guten lokalen Idee eine gemeinsam nutzbare Definition zu machen.

## Ein einfacher Lifecycle für Kennzahlen

Eine Kennzahl kann ähnlich wie ein Datenprodukt einen Lebenszyklus besitzen:

```flow linear vertical
Draft
Review
Approved
Certified
Monitored
Changed / Versioned
Deprecated
```

Dabei sollten Änderungen nicht unsichtbar erfolgen.

Eine neue Definition kann Auswirkungen auf Reports, Anwendungen, Forecasts oder Verträge haben. Deshalb sind folgende Informationen hilfreich:

| Metadatum | Leitfrage |
| --- | --- |
| Business Definition | Was bedeutet die Kennzahl in verständlicher Sprache? |
| Formel / Logik | Wie wird sie berechnet? |
| Scope | Für welche Region, Produkte, Prozesse oder Zeiträume gilt sie? |
| Owner | Wer entscheidet fachlich? |
| Steward | Wer koordiniert Qualität, Dokumentation und Änderungen? |
| Status | Draft, approved, certified oder deprecated? |
| Version | Welche Definition galt zu welchem Zeitpunkt? |
| Quellen | Welche Datenprodukte und Felder werden verwendet? |
| Verbraucher | Welche Reports, Apps oder Dateien nutzen die Kennzahl? |
| Feedback | Wo können Nutzer Fragen oder Abweichungen melden? |

## Praktische Leitfragen für Teams

Bei jeder neuen KPI, Measure oder Report-Berechnung können folgende Fragen helfen:

1. **Existiert bereits eine fachlich vergleichbare Kennzahl?**
2. **Wird eine neue Definition benötigt – oder nur eine neue Darstellung?**
3. **Wo lebt die verbindliche Geschäftslogik?**
4. **Welche lokalen Erweiterungen sind erlaubt und wie werden sie sichtbar?**
5. **Ist die Definition für Business User verständlich, ohne technischen Code lesen zu müssen?**
6. **Sind Owner, Steward, Status und Änderungsverlauf sichtbar?**
7. **Können BI, Anwendungen und Excel dieselbe Kernkennzahl wiederverwenden?**
8. **Wie wird eine häufig genutzte lokale Formel in das gemeinsame Modell zurückgeführt?**

Diese Fragen ersetzen keine Semantic Layer und keinen Data Catalog. Sie verbinden technische Wiederverwendung mit fachlicher Verantwortung.

## Fazit

Moderne Datenplattformen können Daten zentral speichern, transformieren und bereitstellen. Das allein garantiert jedoch noch keine konsistenten Geschäftsantworten.

Fachliche Bedeutung kann in Pipelines, Views, semantischen Modellen, BI-Measures, Anwendungen und Excel weiterentwickelt werden. Diese Flexibilität ist wertvoll. Sie wird erst dann zum Risiko, wenn dieselbe Kennzahl unabhängig neu definiert wird und Kontext, Ownership oder Vergleichbarkeit verloren gehen.

Die fehlende Komponente ist deshalb nicht zwingend ein weiteres Tool. Häufig fehlt die Verbindung zwischen:

```flowchart
gemeinsamer Definition
sichtbarer Verantwortung
verständlichem Kontext
technischer Wiederverwendung
kontinuierlichem Feedback
```

Die entscheidende Frage lautet:

> **Wenn mehrere technisch gültige Werkzeuge dieselbe KPI unterschiedlich berechnen können – wo lebt dann die vertrauenswürdige Kennzahl?**

Vielleicht lautet die bessere Antwort nicht *in einem einzelnen Tool*, sondern:

> **Sie lebt in einer gemeinsam verstandenen, verantworteten und wiederverwendbaren Definition.**

## Weiterführende Ressourcen

- [dbt Developer Hub: dbt Semantic Layer](https://docs.getdbt.com/docs/use-dbt-semantic-layer/dbt-sl)
- [dbt Developer Hub: Metric properties](https://docs.getdbt.com/reference/metric-properties)
- [Microsoft Learn: Managed self-service BI](https://learn.microsoft.com/en-us/power-bi/guidance/powerbi-implementation-planning-usage-scenario-managed-self-service-bi)
- [Microsoft Learn: Promote and certify Power BI content with endorsement](https://learn.microsoft.com/en-us/power-bi/collaborate-share/service-endorsement-overview)
- [Microsoft Learn: Power BI semantic model experience in Excel](https://learn.microsoft.com/en-us/power-bi/collaborate-share/office-integration/service-connect-excel-power-bi-datasets)
- [Microsoft Learn: Semantic models across workspaces](https://learn.microsoft.com/en-us/power-bi/connect-data/service-datasets-across-workspaces)
- [Qlik Help: Reusing measures with master measures](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Measures/create-master-measure.htm)
- [Qlik Help: Using master measures in expressions](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Measures/use-master-measures-expressions.htm)
- [Microsoft Learn: Glossary terms in Microsoft Purview Unified Catalog](https://learn.microsoft.com/en-us/purview/unified-catalog-glossary-terms)
- [Research: Comprehensive and Comprehensible Data Catalogs](https://arxiv.org/abs/2103.07532)
