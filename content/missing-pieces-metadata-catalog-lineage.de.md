---
title: "Die fehlenden Bausteine – Teil 4: Metadaten, Katalog & Lineage"
description: "Warum technische Sichtbarkeit nicht automatisch zu fachlichem Verständnis führt – und wie Kataloge, Glossare, Kontext und Stewardship die Lücke schließen können."
author: Thomas Lindackers
category: Data Governance
tags:
  - the-missing-pieces
  - metadata-management
  - data-catalog
  - data-lineage
  - business-glossary
  - data-discovery
  - business-context
  - data-stewardship
  - data-governance
order: -1
hero: images/playbooks/mp-meta-hero.png
series: missing-pieces
seriesPart: 4
seriesTitle: Die fehlenden Bausteine
---

## Technische Sichtbarkeit ist wertvoll – aber nicht dasselbe wie Verständnis

Moderne Datenplattformen können sehr viele Informationen über Daten erfassen und sichtbar machen:

- Datenbanken, Schemas, Tabellen und Spalten,
- Dateien, APIs und Streaming-Quellen,
- Pipelines, Jobs und Transformationen,
- Abhängigkeiten zwischen Upstream- und Downstream-Assets,
- Klassifizierungen und Sensitivity Labels,
- Qualitätsergebnisse, Nutzungssignale und zugewiesene Verantwortlichkeiten,
- Reports, Dashboards, Modelle und Datenprodukte.

Diese Sichtbarkeit ist ein großer Fortschritt gegenüber undokumentierten Datenlandschaften und isoliertem Wissen in einzelnen Teams.

Sie hilft bei Fragen wie:

> *Woher kommt dieses Feld?*

> *Welche Reports hängen von dieser Tabelle ab?*

> *Was könnte durch eine Änderung an diesem Modell betroffen sein?*

> *Wohin fließen sensible Informationen?*

Business User beginnen jedoch häufig mit anderen Fragen:

> *Was bedeutet diese Zahl?*

> *Welche Definition ist für meinen Anwendungsfall freigegeben?*

> *Warum existieren diese Daten?*

> *Kann ich sie für diese Entscheidung verwenden?*

> *Wer kann sie erklären oder eine Änderung freigeben?*

Der Unterschied ist wichtig:

> **Metadaten können Daten sichtbar machen. Kontext macht sie verständlich.**

Dieses Playbook stellt den Nutzen von Katalogen, automatischem Metadata Harvesting oder Lineage nicht infrage. Es betrachtet die Lücke zwischen technischer Transparenz und gemeinsamem fachlichem Verständnis.

## Metadaten, Katalog und Lineage lösen unterschiedliche Aufgaben

Die Begriffe hängen zusammen, sind aber nicht austauschbar.

| Fähigkeit | Hauptzweck | Typische Fragen |
| --- | --- | --- |
| Metadata Management | Informationen über Datenobjekte erfassen, strukturieren, anreichern und aktuell halten | Was ist dieses Asset, wie ist es aufgebaut und welche Informationen sind darüber bekannt? |
| Data Catalog | gesteuerte Datenobjekte auffindbar, verständlich und wiederverwendbar machen | Welche Daten existieren, welches Asset ist relevant und wie kann ich es nutzen? |
| Business Glossary | gemeinsame Fachsprache und abgestimmte Definitionen etablieren | Was bedeuten Customer, Revenue, Active Contract oder Churn in dieser Organisation? |
| Technische Lineage | technische Flüsse, Abhängigkeiten und Transformationen darstellen | Woher kommen die Daten und was ist von einer Änderung betroffen? |
| Fachliche Lineage | den Weg von Fachbegriffen und Quellen zu Datenprodukten, Kennzahlen und Reports verständlich zusammenfassen | Wie bewegt sich fachliche Bedeutung vom Ursprung bis zur Entscheidung? |
| Data-Quality-Kontext | Erwartungen, Ergebnisse, Probleme und Eignung für einen Zweck sichtbar machen | Sind die Daten für diesen Zweck geeignet und welche Einschränkungen sind bekannt? |
| Ownership & Stewardship | Assets und Definitionen mit verantwortlichen Personen und Prozessen verbinden | Wer pflegt den Kontext, entscheidet über die Bedeutung und koordiniert Probleme? |

Eine reife Governance Experience verbindet diese Fähigkeiten.

Ein Katalog ohne fachliche Bedeutung kann zu einem durchsuchbaren Inventar technischer Objekte werden.

Ein Glossar ohne Verbindungen zu realen Assets kann zu einem Wörterbuch werden, das vom Arbeitsalltag getrennt ist.

Lineage ohne Kontext kann zu einem detaillierten Graphen werden, der jeden Pfad zeigt, aber wenig über den Zweck erklärt.

Der Nutzen entsteht durch die Beziehungen zwischen diesen Elementen.

## Die Lücke zwischen technischer Sichtbarkeit und fachlichem Verständnis

Technische Metadaten lassen sich häufig leichter automatisieren, weil Systeme Schemas, Objektnamen, Datentypen, Jobs und Ausführungsbeziehungen bereitstellen können.

Fachliche Bedeutung funktioniert anders. Sie hängt von Unternehmenssprache, Prozesswissen, beabsichtigter Nutzung, Richtlinien, Interpretation und teilweise auch von Abstimmungen zwischen Domains ab.

Ein Scanner kann in der Regel erkennen, dass eine Spalte existiert.

Er kann nicht immer zuverlässig bestimmen:

- was das Feld fachlich bedeutet,
- ob sein Name irreführend ist,
- welche Definition freigegeben wurde,
- warum eine Transformationsregel existiert,
- welche Ausnahmen akzeptiert sind,
- ob eine KPI für eine bestimmte Entscheidung geeignet ist,
- welcher Geschäftsprozess die Daten erzeugt oder verwendet,
- wer Unklarheiten zwischen Fachbereichen auflösen soll.

Das macht automatisches Harvesting nicht weniger wichtig. Automatisierung ist für Skalierung notwendig.

Es bedeutet, dass Automatisierung und Stewardship unterschiedliche Stärken besitzen:

> **Maschinen können Struktur und technische Evidenz skalierbar erfassen. Menschen müssen weiterhin Bedeutung, Zweck und Verantwortung vereinbaren.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-meta-img1-de.png"
        alt="Darstellung des Unterschieds zwischen technischer Metadaten-Sichtbarkeit und dem fachlichen Kontext, der für Verständnis und vertrauenswürdige Entscheidungen benötigt wird"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Technische Sichtbarkeit erklärt, woher Daten kommen und wie sie sich bewegen. Fachliches Verständnis ergänzt Bedeutung, Zweck, Ownership, Auswirkungen und Richtlinienkontext.
    </figcaption>
</figure>

## Mehr Metadaten bedeuten nicht automatisch leichtere Auffindbarkeit

Ein Katalog kann Millionen Assets enthalten und Nutzer trotzdem im Unklaren lassen, wo sie beginnen sollen.

Suchergebnisse können enthalten:

- mehrere physische Tabellen mit ähnlichen Namen,
- historische Versionen,
- Staging- und Intermediate-Modelle,
- verschiedene Domain Views,
- zertifizierte und nicht zertifizierte Datensätze,
- reportspezifische Extrakte,
- technische Objekte, die für den Betrieb wichtig, aber nicht für die direkte fachliche Nutzung gedacht sind.

Alle diese Assets können korrekt katalogisiert sein.

Die verbleibende Herausforderung ist die Navigation.

Ein Nutzer, der nach *Umsatz aktiver Kunden* sucht, weiß möglicherweise nicht, ob er hier beginnen soll:

`raw.crm_account`

`stg_salesforce_account`

`conformed_customer`

`mart_revenue_customer_month`

`semantic_sales_model`

oder bei einem freigegebenen Report.

Ein nützlicher Katalog benötigt deshalb mehr als vollständige Indizierung. Er benötigt sinnvolle Einstiegspunkte.

Mögliche fachliche Einstiegspunkte sind:

- Business Domains,
- Datenprodukte,
- Critical Data Elements,
- Fachbegriffe,
- Trusted Metrics,
- zertifizierte semantische Modelle,
- freigegebene Reports,
- häufige Geschäftsfragen,
- Prozess- oder Capability Maps.

Die technischen Assets bleiben als Evidenz und Implementierungsdetail verfügbar. Sie müssen nicht das Erste sein, was jeder Nutzer sieht.

## Der Katalog sollte bei der Auswahl helfen – nicht nur beim Finden

Discovery besitzt mindestens drei Ebenen:

1. **Auffindbarkeit** – Kann der Nutzer relevante Assets finden?
2. **Verständlichkeit** – Kann er Zweck, Bedeutung und Einschränkungen verstehen?
3. **Entscheidungsunterstützung** – Kann er beurteilen, welches Asset für die beabsichtigte Nutzung geeignet ist?

Ein Suchergebnis ist noch keine vertrauenswürdige Auswahl.

Für eine Auswahl benötigen Nutzer möglicherweise folgenden Kontext:

| Kontext | Beispiel |
| --- | --- |
| Fachlicher Zweck | Monatliches Umsatzreporting für aktive Abonnements |
| Definition | Netto-Wiederholungsumsatz nach freigegebenen Ausschlüssen |
| Granularität | Ein Datensatz pro Kunde, Vertrag und Monat |
| Zeitlogik | Kalendermonat, nur gebuchte Transaktionen |
| Scope | EMEA-Direktgeschäft; Partnerumsatz ausgeschlossen |
| Qualitätsstatus | Zertifiziert; Freshness-Ziel erfüllt; eine bekannte Einschränkung |
| Owner | Commercial Finance Data Owner |
| Steward | Revenue Data Steward |
| Hauptnutzer | Finance Dashboard, Forecast-Prozess, Management Report |
| Nutzungshinweis | Für monatliches Reporting freigegeben; nicht für Echtzeitprozesse vorgesehen |
| Verknüpfte Assets | semantisches Modell, KPI-Definition, Quellsysteme und Reports |

Das Ziel ist nicht, das längstmögliche Metadatenformular zu erzeugen.

Das Ziel ist, den minimal notwendigen Kontext für eine sichere Entscheidung bereitzustellen.

## Technische Lineage ist Evidenz – fachlicher Kontext ist Interpretation

Technische Lineage ist sehr wertvoll für Impact Analysis, Ursachenanalyse, Change Planning und die Nachverfolgung sensibler Daten.

Sie kann einen Pfad wie diesen zeigen:

```flow linear vertical
Quellsystem
Ingestion
Raw Layer
Transformation
Data Mart
Semantic Model
Dashboard
```

Dieser Pfad beantwortet wichtige Fragen:

- Welche Upstream-Assets tragen zum Ergebnis bei?
- Welche Downstream-Objekte könnten von einer Änderung betroffen sein?
- Wo wird ein Feld transformiert?
- Welche Reports nutzen das Ergebnis?

Der Pfad allein erklärt jedoch möglicherweise nicht:

- warum eine Quelle als maßgeblich gilt,
- welche Geschäftsregel angewendet wird,
- warum ein Datensatz ausgeschlossen wird,
- welche Definition die Kennzahl repräsentiert,
- ob ein Dashboard für eine bestimmte Entscheidung freigegeben ist,
- welches Team die fachliche Bedeutung verantwortet,
- welche bekannten Einschränkungen die Interpretation beeinflussen.

Das ist keine Schwäche von Lineage. Es ist die Grenze zwischen technischer Nachvollziehbarkeit und fachlicher Erklärung.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-meta-img2-de.png"
        alt="Vergleich zwischen technischer Lineage mit Strukturen und Abhängigkeiten und fachlichem Verständnis mit Bedeutung, Zweck, Verantwortung und Auswirkungen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Lineage zeigt den Weg. Fachbegriffe, reichhaltige Metadaten, Kontext-Mapping, Stewardship und kontinuierliche Pflege erklären, was dieser Weg bedeutet.
    </figcaption>
</figure>

## Technische und fachliche Lineage sollten sich ergänzen

Technische Lineage ist häufig detailliert und systemorientiert.

Fachliche Lineage ist meist selektiver und ergebnisorientierter.

| Technische Lineage | Fachliche Lineage |
| --- | --- |
| Tabellen, Spalten, Dateien und Jobs | Fachkonzepte, Datenprodukte, Kennzahlen und Reports |
| detaillierte Transformationspfade | verständliche End-to-End-Zusammenfassung |
| Fokus auf Implementierung und Abhängigkeiten | Fokus auf Zweck, Bedeutung und Auswirkungen |
| hilfreich für Engineers, Analysts, Architekten und Auditoren | hilfreich für Owner, Stewards, Business User und Entscheider |
| weitgehend automatisiert erfassbar | benötigt häufig Pflege und fachliches Mapping |
| unterstützt technische Impact Analysis | unterstützt fachliches Wirkungsverständnis |

Keine der beiden Sichten sollte die andere ersetzen.

Eine vereinfachte fachliche Lineage ohne technische Evidenz kann zu abstrakt werden.

Ein vollständiger technischer Graph ohne Business View kann für viele Nutzer zu komplex werden.

Ein nützliches Modell ermöglicht den Wechsel zwischen den Ebenen:

```flow linear vertical
Business Term
Trusted Metric
Datenprodukt
Semantic Model
Data Mart
Transformationen
Quellfelder
```

Der Nutzer kann bei der Bedeutung beginnen und bei Bedarf in technische Evidenz hineinzoomen.

## Fachliche Bedeutung sollte verknüpft – nicht in jedes Asset kopiert werden

Ein häufiges Wartungsproblem entsteht, wenn dieselbe Definition in Dutzende Tabellen, Spalten, Reports und Dokumente kopiert wird.

Die kopierten Texte entwickeln sich mit der Zeit auseinander.

Ein stärkeres Muster ist, wiederverwendbare Konzepte zentral zu steuern und mit relevanten Assets zu verknüpfen.

Beispiel:

**Business Term: Aktiver Kunde**

Definition: Ein Kunde mit mindestens einem aktiven, abrechenbaren Vertrag am Reporting-Stichtag.

Verknüpft mit:

- Customer-Status-Feld,
- Conformed Customer Model,
- Active-Customer-KPI,
- Customer Semantic Model,
- Commercial Dashboard,
- Retention Policy oder Qualitätsregel, sofern relevant.

Das zentrale Konzept kann besitzen:

- einen Owner,
- einen Steward,
- einen Freigabestatus,
- eine Änderungshistorie,
- Beispiele,
- Synonyme,
- verwandte Begriffe,
- Richtlinienverweise.

Die technischen Assets können lokale Implementierungsdetails ergänzen, ohne das Konzept unabhängig neu zu definieren.

Das reduziert Duplikate und hält Kontext gleichzeitig nah an der Nutzung.

## Nicht jedes Metadatenfeld benötigt denselben Governance-Aufwand

Eine häufige Reaktion auf fehlenden Kontext ist, weitere Pflichtfelder einzuführen.

Das kann die Vollständigkeit erhöhen, aber auch die Beteiligung senken, wenn jedes Asset denselben Umfang manueller Dokumentation erfordert.

Governance sollte proportional zu Wert, Risiko und Reichweite sein.

Ein mögliches gestuftes Modell:

| Asset-Typ oder Tier | Sinnvolle Metadatentiefe |
| --- | --- |
| Raw- oder technisches Intermediate-Asset | automatisierte technische Metadaten, Klassifizierung, Owner der Plattform oder Pipeline, Lineage soweit verfügbar |
| Wiederverwendbarer Domain-Datensatz | Zweck, Domain, Owner, Steward, Granularität, Freshness, Qualitätsstatus, Nutzungshinweise und verknüpfte Fachbegriffe |
| Kritisches Datenprodukt | vollständiger Business-Kontext, Nutzer, Service-Erwartungen, Qualitätsregeln, Richtlinien, Lifecycle und End-to-End-Lineage |
| Unternehmensweite KPI | freigegebene Definition, Berechnungslogik, Filter, Zeitlogik, Owner, Steward, Zertifizierung, Änderungshistorie und verknüpfte Reports |
| Regulatorische oder risikoreiche Daten | detaillierte Klassifizierung, Richtlinie, Zugriff, Aufbewahrung, Evidenz, Kontrollverantwortung und Review-Zyklus |
| Experimentelles Asset | schlanke Metadaten, klarer Draft-Status, Ersteller, Zweck und Ablauf- oder Review-Datum |

Damit werden zwei Extreme vermieden:

- jedes temporäre technische Objekt manuell zu dokumentieren,
- kritische Business Assets nur mit technischen Metadaten auszustatten.

## Automatisierte Metadaten und menschliche Pflege sollten gemeinsam gedacht werden

Automatisierung kann beitragen:

- Schema- und Spaltenerkennung,
- Datentypen und technische Eigenschaften,
- Klassifizierungen,
- Lineage aus unterstützten Systemen,
- Job- und Pipeline-Beziehungen,
- Nutzungsstatistiken,
- Freshness- und Ausführungsmetadaten,
- Ergebnisse von Qualitätstests,
- Erkennung von Änderungen.

Menschliche Pflege ist besonders wichtig für:

- fachliche Definitionen,
- Zweck und beabsichtigte Nutzung,
- Beispiele und Ausnahmen,
- Kritikalität,
- Freigabe und Zertifizierung,
- Ownership-Entscheidungen,
- fachliche Auswirkungen,
- bekannte Einschränkungen,
- domänenübergreifende Interpretation.

Das Ziel sollte weder sein, Menschen durch Scanning zu ersetzen, noch alles manuell zu dokumentieren.

Ein praktikables Modell lautet:

> **Automatisiert, was Systeme wissen. Pflegt gemeinsam, was der Fachbereich vereinbaren muss.**

## Metadatenqualität ist ein eigenes Governance-Thema

Auch Metadaten können unvollständig, veraltet, inkonsistent oder schwer vertrauenswürdig sein.

Typische Fragen zur Metadatenqualität sind:

- Ist der Owner noch in dieser Rolle?
- Ist die Beschreibung aktuell?
- Deckt die Lineage den vollständigen Pfad oder nur eine Plattform ab?
- Ist die Zertifizierung noch gültig?
- Sind Glossarbegriffe mit den richtigen Assets verbunden?
- Wird eine abgelöste Tabelle noch als empfohlene Quelle angezeigt?
- Sind bekannte Einschränkungen sichtbar?
- Entspricht die dokumentierte Granularität den tatsächlichen Daten?

Ein Katalog sollte Metadaten deshalb nicht nur speichern. Er sollte die Gesundheit der Metadaten sichtbar machen.

Mögliche Kennzahlen sind:

| Kennzahl | Mögliche Aussage |
| --- | --- |
| Description Coverage | ob relevante Assets verständliche Beschreibungen besitzen |
| Business-Term Linkage | ob technische Assets mit gesteuerten Fachkonzepten verbunden sind |
| Ownership Coverage | ob kritische Assets aktuelle Ansprechpartner besitzen |
| Stewardship Acceptance | ob zugewiesene Verantwortlichkeiten bestätigt wurden |
| Lineage Coverage | wie viel des benötigten End-to-End-Pfads sichtbar ist |
| Certification Freshness | ob Freigaben im erwarteten Zeitraum überprüft wurden |
| Stale Metadata Rate | wie viele Beschreibungen, Owner oder Verknüpfungen möglicherweise veraltet sind |
| Deprecated Asset Usage | ob Nutzer weiterhin von abgelösten oder nicht empfohlenen Assets abhängen |
| Search Success | ob Nutzer ohne wiederholte Supportanfragen ein passendes Asset finden |
| User Feedback | ob Beschreibungen und Kontext als verständlich und hilfreich wahrgenommen werden |

Coverage-Werte benötigen Kontext.

Ein Katalog kann eine hohe Metadatenvollständigkeit erreichen, während Nutzer trotzdem Schwierigkeiten haben, das richtige Asset auszuwählen.

Das Ergebnis ist wichtiger als die Anzahl ausgefüllter Felder.

## Die letzte Meile der Lineage ist relevant

Viele Datenpfade enden nicht an einer Warehouse-Tabelle.

Sie laufen weiter:

```flow linear vertical
Semantic Model
BI Measure
Report
Export
Spreadsheet
Präsentation
Geschäftsentscheidung
```

Nicht jeder Downstream-Schritt lässt sich über jedes Tool automatisch erfassen.

Daraus entstehen praktische Fragen:

- Umfasst Lineage die semantische Ebene?
- Sind Report-Level-Measures sichtbar?
- Können Nutzer erkennen, welches zertifizierte Datenprodukt ein Dashboard verwendet?
- Werden Exporte und lokale Berechnungen als gesteuerte Ergebnisse oder persönliche Analyse behandelt?
- Kann ein wichtiges Spreadsheet als Business Asset registriert werden, sobald es operativ relevant wird?
- Ist der Entscheidungskontext mit den verwendeten Daten verbunden?

Das Ziel ist nicht, jede private Berechnung mit einem Enterprise-Prozess zu steuern.

Das Ziel ist zu erkennen, wann lokale Nutzung gemeinsam, wiederkehrend oder geschäftskritisch wird.

Ab diesem Punkt sollten Kontext und Verantwortlichkeit der Bedeutung des Ergebnisses folgen.

## Ein Katalog sollte unterschiedliche Nutzer mit unterschiedlichen Sichten unterstützen

Derselbe Metadaten-Graph dient verschiedenen Anforderungen.

| Nutzer | Wahrscheinliche Einstiegsfragen |
| --- | --- |
| Business User | Welche Daten sollte ich verwenden und was bedeuten sie? |
| Data Steward | Welche Definitionen, Probleme und Metadaten benötigen Aufmerksamkeit? |
| Data Owner | Welche kritischen Assets, Risiken und Entscheidungen liegen in meinem Verantwortungsbereich? |
| Analyst | Welcher Datensatz ist geeignet, in welcher Granularität und mit welchen Einschränkungen? |
| Data Engineer | Woher kommen die Daten und was hängt von dieser Änderung ab? |
| Architekt | Wie hängen Domains, Plattformen, Produkte und Schnittstellen zusammen? |
| Security / Privacy | Wo befinden sich sensible Daten und wohin fließen sie? |
| Auditor | Welche Kontrollen, Freigaben, Verantwortlichen und Nachweise gelten? |

Eine Oberfläche muss nicht alles gleichzeitig zeigen.

Progressive Disclosure kann helfen:

1. Mit Business-Name, Zweck, Status und Owner beginnen.
2. Qualität, Freshness, Nutzungshinweise und verwandte Assets zeigen.
3. Lineage und technische Implementierungsdetails bei Bedarf öffnen.
4. Richtlinien-, Audit- und Betriebsevidenz für Spezialrollen bereitstellen.

So bleibt technische Tiefe erhalten, ohne der einzige Einstiegspunkt zu sein.

## Stewardship hält Bedeutung mit der Realität verbunden

Kataloge und Lineage sind keine einmaligen Einführungsprojekte.

Fachdefinitionen ändern sich. Systeme werden ersetzt. Kennzahlen werden überarbeitet. Reports werden abgelöst. Neue regulatorische Anforderungen erzeugen neue Klassifizierungen und Kontrollen.

Ohne Stewardship kann der Katalog langsam zu einem historischen Abbild dessen werden, was früher einmal richtig war.

Stewardship kann Kontinuität unterstützen durch:

- Review kritischer Definitionen,
- Validierung von Ownership und Ansprechpartnern,
- Verknüpfung neuer Assets mit bestehenden Konzepten,
- Auflösung doppelter oder widersprüchlicher Begriffe,
- Prüfung von Zertifizierung und Nutzungshinweisen,
- Dokumentation bekannter Einschränkungen,
- Koordination von Nutzerfeedback,
- eindeutige Kennzeichnung abgelöster Assets,
- Prüfung, ob Lineage und Kontext nach Änderungen noch vollständig sind.

Der Steward sollte nicht jedes technische Detail manuell pflegen müssen.

Die Rolle schützt Bedeutung, Qualität und Nutzbarkeit, während Automatisierung einen großen Teil der technischen Evidenz aktuell hält.

## Häufige Anti-Patterns

### Der Katalog als technisches Inventar

Millionen Assets werden gescannt, aber Business User können keine freigegebenen Einstiegspunkte erkennen.

### Das Glossar als separates Wörterbuch

Definitionen existieren, sind aber nicht mit Datensätzen, Kennzahlen, Reports oder Prozessen verbunden.

### Lineage als Wand aus Knoten

Der Graph ist technisch vollständig, aber zu detailliert, um Auswirkungen für Nicht-Spezialisten zu erklären.

### Pflichtmetadaten ohne klaren Nutzen

Nutzer füllen Felder aus, weil der Workflow es verlangt, aber die Informationen helfen weder bei Discovery noch bei Entscheidungen oder Kontrollen.

### Definitionen überall kopieren

Dieselbe fachliche Bedeutung wird unabhängig in Modellen, Reports, Katalogfeldern und Dokumenten gepflegt.

### Zertifizierung ohne Review

Ein Asset bleibt als vertrauenswürdig markiert, obwohl sich Ownership, Logik oder Nutzung verändert haben.

### Automatisierte Vollständigkeit ohne fachliche Validierung

Beschreibungen und Klassifizierungen werden skalierbar erzeugt, aber bei hohem Business Impact nicht überprüft.

### Business-Kontext ohne technische Evidenz

Eine ansprechende Datenproduktseite existiert, aber Nutzer können Quelle, Transformation oder betroffene Downstream-Assets nicht nachvollziehen.

## Ein praktikables Modell für Metadaten, die Verständnis erzeugen

Ein möglicher Ansatz:

1. **Fachliche Einstiegspunkte identifizieren**  
   Mit Domains, Datenprodukten, kritischen Kennzahlen, Reports und Fachbegriffen beginnen, nach denen Nutzer tatsächlich suchen.

2. **Technische Metadaten automatisiert erfassen**  
   Unterstützte Plattformen, Schemas, Pipelines, Modelle, Klassifizierungen und Lineage scannen.

3. **Fachkonzepte mit technischen Assets verbinden**  
   Glossarbegriffe, Prozesse, Capabilities, KPIs und Richtlinien mit den Assets verknüpfen, die sie umsetzen.

4. **Den minimal nützlichen Business-Kontext ergänzen**  
   Zweck, Scope, Granularität, beabsichtigte Nutzung, Owner, Steward, Qualitätsstatus und bekannte Einschränkungen beschreiben.

5. **Verständliche Lineage-Sichten bereitstellen**  
   Sowohl detaillierte technische Lineage als auch vereinfachte fachliche Pfade anbieten.

6. **Vertrauenswürdige Auswahl sichtbar machen**  
   Zertifizierung, Lifecycle-Status und Nutzungshinweise verwenden, um empfohlene Assets von Drafts, Legacy-Objekten und technischen Zwischenstufen zu unterscheiden.

7. **Feedback und Stewardship integrieren**  
   Nutzern ermöglichen, Fragen zu stellen, Unklarheiten zu melden und Verbesserungen dort vorzuschlagen, wo sie Daten verwenden.

8. **Metadaten als Teil von Changes aktualisieren**  
   Kontext, Verknüpfungen, Ownership und Zertifizierung anpassen, wenn Datenprodukte, Pipelines oder Reports verändert werden.

9. **Ergebnisse statt nur Vollständigkeit messen**  
   Prüfen, ob Nutzer Daten mit weniger Supportaufwand finden, verstehen und korrekt verwenden.

## Praktische Fragen für Teams

Für ein kritisches Datenprodukt, eine Kennzahl oder einen Report:

1. **Kann ein Business User das Asset finden, ohne technische Objektnamen zu kennen?**
2. **Ist der Zweck in Fachsprache verständlich?**
3. **Ist die Definition mit gesteuerten Fachbegriffen verknüpft?**
4. **Sind Owner und Steward sichtbar und aktuell?**
5. **Können Nutzer beabsichtigte Nutzung, Granularität, Scope und bekannte Einschränkungen erkennen?**
6. **Sind Qualitäts- und Freshness-Status verständlich?**
7. **Reicht die Lineage von relevanten Quellen bis zum Nutzungspunkt?**
8. **Können Nutzer zwischen fachlicher und technischer Lineage wechseln?**
9. **Sind freigegebene, experimentelle, Legacy- und abgelöste Assets klar unterscheidbar?**
10. **Wird wiederverwendete fachliche Bedeutung zentral verknüpft, statt immer wieder kopiert?**
11. **Können Nutzer Feedback geben, ohne ihren normalen Arbeitsablauf zu verlassen?**
12. **Werden Metadaten überprüft, wenn sich das zugrunde liegende Datenprodukt verändert?**

Diese Fragen verlangen nicht, dass jede Organisation dasselbe Katalogmodell einführt.

Sie helfen zu prüfen, ob Metadaten tatsächlich Verständnis und Nutzung unterstützen.

## Fazit

Metadaten, Kataloge und Lineage sind keine fehlenden Fähigkeiten. Moderne Plattformen bieten bereits leistungsfähige Möglichkeiten, Assets zu finden, technische Beziehungen zu erfassen, Kontext anzureichern und Datenflüsse nachzuvollziehen.

Die mögliche Lücke entsteht, wenn technische Sichtbarkeit als Endergebnis betrachtet wird.

Eine vollständige Karte erzeugt nicht automatisch eine gemeinsame Sprache.

Ein detaillierter Lineage-Graph erklärt nicht automatisch die fachliche Bedeutung.

Ein durchsuchbarer Katalog sagt Nutzern nicht automatisch, welches Asset für ihre Entscheidung geeignet ist.

Die Brücke benötigt:

```flow linear vertical
technische Metadaten
Fachbegriffe
Zweck und Kontext
Ownership
Qualität und Richtlinien
verständliche Lineage
kontinuierliche Stewardship
```

Die zentrale Frage lautet deshalb:

> **Haben wir mehr Metadaten – oder mehr Verständnis?**

Vielleicht besteht das Ziel von Metadata Governance nicht darin, alles gleich tief zu dokumentieren.

Vielleicht besteht es darin, die wichtigsten Daten so verständlich zu machen, dass Menschen sie finden, bewerten, korrekt nutzen und wissen, wer helfen kann, wenn die Bedeutung unklar ist.

## Weiterführende Ressourcen

- [Microsoft Learn: Microsoft Purview Data Governance Glossar](https://learn.microsoft.com/en-us/purview/data-governance-glossary)
- [Microsoft Learn: Microsoft Purview Data Map](https://learn.microsoft.com/en-us/purview/data-map)
- [Microsoft Learn: Datenobjekte im Microsoft Purview Unified Catalog suchen und verwalten](https://learn.microsoft.com/en-us/purview/unified-catalog-data-assets-search)
- [Microsoft Learn: Data Lineage in Microsoft Purview](https://learn.microsoft.com/en-us/purview/data-gov-classic-lineage)
- [Databricks Documentation: Lineage in Unity Catalog](https://docs.databricks.com/aws/en/data-governance/unity-catalog/data-lineage)
- [Databricks Documentation: External Lineage in Unity Catalog](https://docs.databricks.com/aws/en/data-governance/unity-catalog/external-lineage)
- [Databricks Documentation: What is Unity Catalog?](https://docs.databricks.com/aws/en/data-governance/unity-catalog/)
- [Collibra Product Resource Center: Business Glossary](https://productresources.collibra.com/docs/collibra/latest/Content/BusinessGlossary/to_business-glossary.htm)
- [Collibra Product Resource Center: About Data Catalog](https://productresources.collibra.com/docs/collibra/latest/Content/Catalog/to_catalog.htm)
- [Collibra Product Resource Center: About Collibra Data Lineage](https://productresources.collibra.com/docs/collibra/latest/Content/CollibraDataLineage/co_collibra-data-lineage.htm)
- [AWS Documentation: Amazon DataZone Concepts](https://docs.aws.amazon.com/datazone/latest/userguide/datazone-concepts.html)
- [AWS Documentation: Business Glossary in Amazon DataZone erstellen und pflegen](https://docs.aws.amazon.com/datazone/latest/userguide/create-maintain-business-glossary.html)
