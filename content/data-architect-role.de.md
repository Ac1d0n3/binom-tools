---
title: Die Rolle Data Architect — Grain, Contracts und architektonische Konsistenz
description: Wie Data Architects Konsistenz über Grain, Modellgrenzen, Data Contracts, Plattformentscheidungen und Änderungen schaffen, ohne Business Ownership, Stewardship oder Betrieb zu ersetzen.
category: Data Governance
tags:
  - data-governance
  - data-architecture
  - data-architect
  - decision-rights
  - data-contract
  - grain
  - architecture-decision-record
  - data-product
  - semantic-model
  - platform-operations
order: -1
author: Thomas Lindackers
series: roles-hub
seriesPart: 1
seriesTitle: Rollen und Entscheidungsrechte
hero: images/playbooks/data-architect-role-hero.png
---

## Architektur scheitert an den Übergängen

Viele Dateninitiativen verfügen über kompetente Engineers, leistungsfähige Plattformen und dokumentierte Governance-Rollen. Trotzdem entstehen inkonsistente Ergebnisse.

Das Problem zeigt sich häufig zwischen den Komponenten:

- Eine Quelle liefert ein bestimmtes Business Event, während eine Transformation den Grain unbemerkt verändert.
- Ein Data Product verspricht tägliche Stabilität, während sein Schema ohne Kompatibilitätsregeln geändert wird.
- Ein semantisches Modell kombiniert Facts mit unterschiedlichem Detaillierungsgrad.
- Ein Plattformteam optimiert die Laufzeitimplementierung, ohne den Consumer Contract zu kennen.
- Ein Fachbereich genehmigt einen KPI, aber niemand verantwortet die architektonischen Konsequenzen seines Modells.
- Ein zentraler Architect prüft jedes Detail und wird zum Delivery-Bottleneck.

Das sind keine isolierten Modellierungsfehler. Es sind Fehler in den architektonischen Entscheidungsrechten.

> **Ein Data Architect ist für die architektonische Kohärenz über Grain, Modellgrenzen, Schnittstellen und Änderungen verantwortlich. Die Rolle ersetzt weder Business Ownership noch Stewardship oder Plattformbetrieb.**

Der Architect verbindet damit Entscheidungen, die sonst lokal und voneinander getrennt bleiben würden. Das Ziel ist nicht die zentrale Kontrolle jeder Implementierungsentscheidung. Das Ziel ist ein Datenpfad, dessen Komponenten kompatibel, erklärbar und änderbar bleiben.

## Die Rolle wird durch Konsistenz definiert — nicht durch ein Tool

Ein Data Architect ist nicht die Person, die zuerst ein Warehouse-Produkt auswählt und anschließend jedes Problem in dieses Produkt einpasst.

Die Rolle beginnt mit Fragen wie:

- Welches Business Event wird dargestellt?
- Was bedeutet eine einzelne Zeile?
- Welche Modellgrenze muss stabil bleiben?
- Welche Schnittstelle wird von anderen Teams konsumiert?
- Welche Änderungen sind kompatibel?
- Wo muss fachliche Bedeutung genehmigt werden?
- Welche Laufzeitbedingung verändert das Design wesentlich?
- Welche Entscheidung darf lokal bleiben und welche besitzt Enterprise-Auswirkung?

Die Antworten prägen Source Integration, Transformationsgrenzen, Data Products, Marts, semantische Modelle, APIs und Deployment-Patterns.

Technologie ist wichtig, wird aber gegen erklärte Anforderungen bewertet. Ein Tool kann eine Architektur implementieren. Es kann weder den fachlichen Grain definieren noch Risiko akzeptieren oder verbindliche Bedeutung festlegen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/data-architect-role-img1-de.png"
        alt="Entscheidungsgrenzen zwischen Data Owner, Data Steward, Data Architect und Platform Operations rund um gemeinsame Entscheidungen zu Data Product, Änderung, Release und Ausnahme"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der Data Architect verantwortet architektonische Konsistenz. Fachliche Accountability, Governance-Freigabe und Laufzeitbetrieb verbleiben bei den jeweils zuständigen Rollen.
    </figcaption>
</figure>

## Vier Rollen, vier unterschiedliche Verantwortungsbereiche

Eine gemeinsame Dateninitiative benötigt Zusammenarbeit. Zusammenarbeit bedeutet jedoch nicht, dass Verantwortlichkeiten austauschbar werden.

### Data Owner

Der Data Owner bleibt für das Business Outcome und die zulässige Datennutzung accountable.

Typische Entscheidungen betreffen:

- fachliche Priorität und Finanzierung;
- zulässige Nutzung;
- Risikoakzeptanz;
- Ownership für das Ergebnis;
- Eskalation, wenn Business Value und Kontrollanforderungen miteinander kollidieren.

Der Architect kann Konsequenzen erläutern, sollte aber kein fachliches Risiko im Namen des Owners akzeptieren.

### Data Steward

Der Data Steward pflegt und validiert die governte Bedeutung.

Typische Verantwortlichkeiten sind:

- fachliche Definitionen;
- Klassifikation;
- Qualitätsanforderungen;
- kontrolliertes Vokabular;
- Freigabenachweise;
- Koordination von Korrektur und Review.

Der Architect stellt sicher, dass diese Entscheidungen über Modelle und Schnittstellen hinweg konsistent abgebildet werden können. Der Steward entscheidet, ob Bedeutung, Klassifikation oder Qualitätsanforderung korrekt sind.

### Data Architect

Der Data Architect ist für Entscheidungen verantwortlich, die strukturelle Kohärenz beeinflussen.

Typische Verantwortlichkeiten sind:

- erklärter Grain;
- Keys und Zeitsemantik;
- Modell- und Domänengrenzen;
- Integrationspatterns;
- Data Contracts und Schnittstellenkompatibilität;
- wiederverwendbare Architekturstandards;
- Impact-Analyse struktureller Änderungen;
- Schwellenwerte für Architecture Reviews;
- Architecture Decision Records;
- kontrollierte Ausnahmen.

Das bedeutet nicht, dass der Architect jede Tabelle entwirft. Es bedeutet, dass Teams klare Guardrails besitzen und wesentliche Abweichungen sichtbar und reviewbar werden.

### Platform Operations

Platform Operations verantwortet die zuverlässige Ausführung.

Typische Verantwortlichkeiten sind:

- Deployment-Mechanismen;
- Laufzeitzuverlässigkeit;
- Observability;
- Incident Handling;
- operative Standards;
- Kapazitäts- und Performance-Grenzen;
- Backup, Recovery und Lifecycle-Ausführung.

Der Architect muss innerhalb realer Implementierungs- und Betriebsbedingungen entwerfen. Platform Operations sollte die architektonische Absicht nicht aus Deployment-Tickets ableiten müssen.

## Grain ist der erste Architekturvertrag

Teams beginnen häufig mit Spalten, Schemas oder Source Extracts. Die wichtigste Entscheidung wird oft erst später und implizit getroffen:

> Was repräsentiert eine einzelne Zeile?

Diese Frage definiert den Grain.

Eine geeignete Grain-Aussage ist präzise:

```text
Eine Zeile repräsentiert eine fakturierte Auftragsposition
für eine juristische Einheit
in der Transaktionswährung der Quelle
zum Buchungszeitpunkt.
```

Diese Aussage bestimmt:

- Eindeutigkeitsregeln;
- Primary und Business Keys;
- Join-Verhalten;
- sichere Aggregation;
- verspätet eintreffende Änderungen;
- historische Korrekturen;
- Beziehungen zu Dimensions;
- Erwartungen der Consumer.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/data-architect-role-img2-de.png"
        alt="Architekturabfolge vom Business Event und erklärten Grain über Keys, Facts, vertragliche Schnittstelle und semantisches Modell bis zu den Consumern einschließlich Fehlerpfad für gemischten Grain"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Grain muss erklärt werden, bevor ein Schema zur Schnittstelle wird. Gemischter oder undefinierter Grain erzeugt Downstream doppelte Kennzahlen, mehrdeutige Joins und inkonsistente KPIs.
    </figcaption>
</figure>

### Gemischter Grain darf nicht in einem Modell versteckt werden

Betrachte ein Modell mit:

- einer Zeile je Auftragsposition für Umsatzwerte;
- einer Zeile je Lieferung für den Lieferstatus;
- einer Zeile je Auftrag für Kundenattribute;
- einer Zeile je Rechnung für den Zahlungsstatus.

Werden diese Ebenen ohne explizite Modellierung kombiniert, wirkt die Tabelle bequem, kann aber nicht sicher aggregiert werden. Ein Semantic Layer kann die Duplizierung vorübergehend verbergen, entfernt den strukturellen Fehler jedoch nicht.

Der Architect sollte eine der folgenden Lösungen verlangen:

- getrennte Facts mit expliziten Beziehungen;
- ein erklärtes Aggregat auf höherer Ebene;
- eine Bridge oder Allokationsregel mit dokumentierter Semantik;
- eine zweckgebundene Schnittstelle, die gültige Measures eindeutig begrenzt;
- ein Redesign, bevor das Modell zu einem geteilten Contract wird.

Die Entscheidung ist architektonisch, weil sie jeden nachgelagerten Consumer betrifft und nicht nur die aktuelle Implementierung.

## Ein Architekturvertrag über den gesamten Datenpfad

Ein Data Contract wird häufig als Schema-Vereinbarung an der Quellgrenze behandelt. Das ist sinnvoll, aber unvollständig.

Ein governter Datenpfad enthält mehrere verbundene Contracts:

1. **Source Contract** — Identifikatoren, Quellschema und Änderungserwartungen.
2. **Transformation Contract** — Grain, Ableitung und Qualitätsprüfungen.
3. **Data Product Contract** — Zweck, Owner, Serviceerwartungen und zulässige Nutzung.
4. **Semantic Contract** — Entitäten, Measures, Dimensions und Zeitlogik.
5. **Consumption Contract** — API oder Modell, Filter, Refresh und Kompatibilität.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/data-architect-role-img3-de.png"
        alt="Fünf verbundene Contracts über Source, Transformation, Data Product, Semantic und Consumption Layer mit gemeinsamer Version, Ownership, Freigabe, Kompatibilität und Nachweis"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der Data Architect richtet strukturelle Contracts über den gesamten Pfad aus. Data Owner und Stewards genehmigen weiterhin fachliche Bedeutung, zulässige Nutzung, Klassifikationen und Governance-Entscheidungen.
    </figcaption>
</figure>

Jeder Contract sollte fünf Kontrolldimensionen sichtbar machen:

- **Version** — welcher Zustand verwendet wird;
- **Owner** — wer für die betreffende Entscheidung accountable ist;
- **Freigabe** — welche Entscheidungen explizite Zustimmung benötigen;
- **Kompatibilität** — worauf sich Downstream Consumer verlassen dürfen;
- **Nachweis** — wie Implementierung und Verifikation belegt werden.

Die Contracts müssen nicht in einem einzigen Tool gespeichert werden. Sie müssen jedoch verbunden bleiben.

Beispielsweise kann eine Schema Registry physische Kompatibilitätsregeln halten, Transformationscode Grain und Tests beschreiben, ein Catalog Ownership und freigegebene Bedeutung zeigen, ein semantisches Modell Measures definieren und eine API-Spezifikation das Consumption-Verhalten festlegen. Die architektonische Verantwortung besteht darin, Widersprüche zwischen diesen Darstellungen zu verhindern.

## Der Beitrag des Architects zu zentralen technischen Entscheidungen

Der Data Architect sollte weder jede technische Entscheidung dominieren noch nach der Erstellung eines Target-State-Diagramms verschwinden.

### Stack-Entscheidungen

Der Architect definiert den Bewertungskontext:

- Workload- und Latenzanforderungen;
- Integrationsgrenzen;
- erforderliche Kontrollen;
- Interoperabilität;
- Operating Model;
- Portabilität und Lock-in-Risiko;
- Kostentreiber;
- Fähigkeiten des Teams;
- erwartete Änderungsgeschwindigkeit.

Die finale Produktauswahl kann gemeinsam mit Engineering, Platform Leadership, Security, Procurement und Business Sponsors erfolgen. Der Architect stellt sicher, dass die Entscheidung auf Anforderungen und nicht auf Feature-Begeisterung basiert.

### Integrationsentscheidungen

Der Architect entscheidet oder definiert Guardrails für:

- Batch-, Streaming- oder Event-getriebene Integration;
- Grenzen der Source Extraction;
- kanonische oder quellnahe Modelle;
- Identity- und Key-Management;
- Schema Evolution;
- Replay- und Korrekturverhalten;
- domänenübergreifende Schnittstellen.

### Mart- und Semantic-Entscheidungen

Der Architect legt fest, wo wiederverwendbare Business-Strukturen hingehören und wo zweckgebundene Modelle angemessen sind.

Dazu gehören Fragen wie:

- Handelt es sich um ein governtes Data Product oder einen lokalen analytischen Mart?
- Welcher Grain ist autoritativ?
- Welche Dimensions sind conformed?
- Welche Zeitlogik wird geteilt?
- Welche Measures benötigen einen governten KPI Contract?
- Welche Logik darf consumer-spezifisch bleiben?

Die detaillierte KPI Governance gehört in die spezialisierten KPI Playbooks. Die Rolle des Architects besteht hier darin, sicherzustellen, dass das Modell eine freigegebene Definition unterstützen kann, ohne inkompatible Logik zu duplizieren.

### Deployment-Entscheidungen

Der Architect trägt architektonische Release-Bedingungen bei:

- Contract Validation;
- Kompatibilitätsprüfungen;
- Migrations- und Rollback-Strategie;
- Consumer Impact Analysis;
- Parallel Validation, wenn erforderlich;
- Nachweis, dass das implementierte Modell dem freigegebenen Design entspricht.

Platform Operations verantwortet Deployment-Mechanismus und Laufzeitausführung. Der Architect definiert die strukturellen Bedingungen, unter denen die Änderung sicher ist.

## Die einfachste tragfähige Architekturpraxis

Ein großes Architecture Board ist keine Mindestanforderung.

Eine kleinere Organisation kann mit sechs Artefakten beginnen:

1. **Grain Statement** für jedes geteilte Fact-Modell oder analytische Interface.
2. **Interface Contract** mit Schema, Semantik, Version und Kompatibilitätsregel.
3. **Veröffentlichte Standards** für häufige Modell- und Integrationspatterns.
4. **Architecture Decision Record** für wesentliche oder nicht offensichtliche Entscheidungen.
5. **Review-Schwellenwerte**, die festlegen, wann Architektur zwingend beteiligt wird.
6. **Exception Record** mit Owner, Begründung, Risiko, Ablaufdatum und Remediation-Pfad.

Diese Artefakte sollten nahe an der Delivery liegen. Sie können in Version Control, einer Dokumentationsplattform oder einem Governance Repository gespeichert werden, müssen aber gemeinsam mit der Implementierung reviewbar sein.

### Ein minimales ADR

Ein Architecture Decision Record kann kurz bleiben:

```yaml
title: Shipment Fact vom Order-Line Fact trennen
status: accepted
decision_owner: data-architect
business_approver: sales-data-owner
governance_reviewer: sales-data-steward
context: Shipment Events besitzen einen anderen Grain als Order Lines.
decision: Eigenes Shipment Fact erstellen und über Order-Line Allocation verbinden.
consequences:
  - Delivery Measures dürfen nicht direkt mit Order-Line Measures gejoint werden
  - das semantische Modell benötigt eine explizite Beziehung
  - historische Shipment-Korrekturen bleiben nachvollziehbar
review_date: 2027-01-31
```

Das ADR dokumentiert, warum eine Entscheidung getroffen wurde. Es ersetzt weder Code noch Tests, Contracts oder fachliche Freigabe.

## Zusammenarbeit sollte eine implementierbare Entscheidung erzeugen

### Mit dem Data Owner

Der Architect übersetzt fachliche Prioritäten und akzeptierte Risiken in strukturelle Konsequenzen.

Beispiele:

- Eine Near-Real-Time-Anforderung kann einen anderen Integrationspfad erfordern.
- Ein domänenübergreifendes Data Product kann stabile Enterprise-Identifikatoren benötigen.
- Eine akzeptierte temporäre Qualitätsgrenze kann einen sichtbaren Consumer-Hinweis verlangen.
- Eine Einschränkung der zulässigen Nutzung kann Interface- und Access-Design beeinflussen.

Der Owner genehmigt den fachlichen Trade-off. Der Architect stellt sicher, dass das technische Design ihn abbildet.

### Mit dem Data Steward

Steward und Architect treffen sich an der Grenze zwischen Bedeutung und Struktur.

Gemeinsam klären sie:

- ob ein Feld das beabsichtigte Business Concept repräsentiert;
- ob eine Klassifikation auch für abgeleitete Attribute gilt;
- welche Qualitätsanforderung verpflichtend ist;
- welche Definitionen freigegeben sind;
- wie Änderungen Catalog, Lineage und Consumer-Dokumentation beeinflussen;
- welche Nachweise vor dem Release erforderlich sind.

Ein Steward sollte kein Modell genehmigen müssen, dessen Grain oder Ableitung nicht erklärt werden kann.

### Mit Platform Operations

Der Architect benötigt operative Fakten frühzeitig:

- unterstützte Deployment-Patterns;
- Service Limits;
- Observability-Fähigkeiten;
- Recovery Objectives;
- Runtime Security Controls;
- Kosten- und Kapazitätsverhalten;
- Wartungsfenster;
- Plattform-Lifecycle-Bedingungen.

Platform Operations benötigt im Gegenzug explizite Contracts und Non-Functional Expectations. Reliability lässt sich nicht aus einem Architekturdiagramm ableiten.

## Konkretes Beispiel: Änderung des Sales Interface

Ein Sales Data Product veröffentlicht ein `order_line` Interface, das von Finance, Operations und mehreren BI-Modellen genutzt wird.

Das Domain Team schlägt vor, den Shipment Status direkt in die bestehende Tabelle aufzunehmen.

Die Anforderung wirkt klein. Die architektonische Analyse zeigt jedoch:

- Eine Auftragsposition kann mehrere Shipments besitzen.
- Shipment Events können nach der Fakturierung eintreffen.
- Korrekturen können die Shipment-Historie nachträglich verändern.
- Finance konsumiert die Tabelle auf Invoice-Posting-Grain.
- Operations benötigt den jeweils aktuellen Shipment State.
- Der bestehende Contract verspricht eine Zeile je fakturierter Auftragsposition.

Der Data Architect entscheidet nicht, ob Shipment Status fachlich wichtig ist. Das ist eine Business-Entscheidung.

Der Architect entscheidet jedoch, dass wiederholte Shipment-Datensätze den bestehenden Grain und Contract brechen würden.

Eine kontrollierte Lösung kann so aussehen:

1. den bestehenden `order_line` Contract beibehalten;
2. ein separates `shipment_event` Fact erstellen;
3. für operative Consumer optional eine Current-Shipment-Projektion veröffentlichen;
4. Keys und zeitliches Verhalten explizit definieren;
5. die Entscheidung in einem ADR dokumentieren;
6. Definitionen und Qualitätsanforderungen durch den Data Steward freigeben lassen;
7. Priorität und zulässige Nutzung durch den Data Owner bestätigen lassen;
8. Refresh-, Observability- und Recovery-Anforderungen durch Platform Operations validieren lassen;
9. betroffene semantische Modelle vor dem Release testen.

Damit bleibt die architektonische Konsistenz erhalten, ohne das Domain Team an der Lieferung von Mehrwert zu hindern.

## Architektur skalieren, ohne ein Bottleneck zu erzeugen

Architektur wird wirkungslos, wenn jede Entscheidung eine zentrale Genehmigung benötigt.

Die Alternative ist nicht uneingeschränkte Autonomie. Sie ist delegierte Entscheidung innerhalb expliziter Guardrails.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/data-architect-role-img4-de.png"
        alt="Entscheidungsworkflow vom Team Proposal über veröffentlichte Standards, lokale Entscheidung oder Architecture Review, ADR, Implementierung, Verifikation und aktualisierte Standards"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Teams sollten innerhalb veröffentlichter Guardrails lokal entscheiden. Architecture Review bleibt Entscheidungen mit Enterprise-, Cross-Domain-, Kompatibilitäts-, Kosten- oder Risikowirkung vorbehalten.
    </figcaption>
</figure>

### Entscheidungen, die normalerweise Architecture Review benötigen

- ein neues Enterprise Pattern;
- eine domänenübergreifende Schnittstelle;
- eine inkompatible Contract-Änderung;
- eine neue Plattformkategorie;
- eine kostenintensive oder risikoreiche Ausnahme;
- eine Änderung gemeinsamer Identity-, Grain- oder Zeitsemantik;
- eine Entscheidung, die nicht sicher rückgängig gemacht werden kann.

### Entscheidungen, die normalerweise lokal bleiben können

- Implementierungsdetails innerhalb eines freigegebenen Patterns;
- eine freigegebene Pattern-Variante;
- reversible Entscheidungen mit niedrigem Risiko;
- lokale Optimierungen ohne Änderung eines geteilten Contracts;
- Naming- oder Packaging-Entscheidungen, die durch Standards abgedeckt sind.

Der zentrale Mechanismus ist Eskalation anhand von Schwellenwerten, nicht Eskalation aus Gewohnheit.

## Häufige Anti-Patterns

### Der Architect als verpflichtendes Approval-Bottleneck

Jedes Team wartet auf eine zentrale Person. Queues wachsen, Entscheidungen wandern in informelle Kanäle und Teams umgehen Architektur, um lieferfähig zu bleiben.

**Korrektur:** Guardrails veröffentlichen, Review-Schwellenwerte definieren und reversible Entscheidungen delegieren.

### Paper Architecture ohne Verbindung zur Implementierung

Der Target State ist dokumentiert, aber Code, Interfaces und Deployments werden nicht dagegen verifiziert.

**Korrektur:** ADRs, Contracts, Tests und Release Evidence mit der Delivery verbinden.

### Tool-first Architecture

Eine Plattform wird ausgewählt, bevor Grain, Consumer, Kontrollen und Betriebsbedingungen verstanden sind.

**Korrektur:** Zuerst den Entscheidungskontext definieren und Produkte dagegen bewerten.

### Gemischter Grain in einem Modell

Bequemlichkeit wird struktureller Korrektheit vorgezogen. Consumer erhalten doppelte Kennzahlen und mehrdeutige Joins.

**Korrektur:** Grain erklären, Facts trennen oder einen expliziten Allokations- und Aggregationsvertrag definieren.

### Schnittstellenänderung ohne Consumer Impact Analysis

Eine technisch gültige Schemaänderung bricht Reports, semantische Modelle, APIs oder Downstream-Transformationen.

**Korrektur:** Interfaces versionieren, Kompatibilität klassifizieren und Consumer vor dem Release identifizieren.

### Architektur-Ownership wird mit Business Accountability verwechselt

Der Architect soll Bedeutung, zulässige Nutzung oder fachliches Risiko genehmigen.

**Korrektur:** Business-Entscheidungen beim Data Owner und governte Bedeutung im Stewardship belassen. Architektur dokumentiert und implementiert deren strukturelle Konsequenzen.

## Entscheiden, beraten oder delegieren

Der Data Architect sollte drei Entscheidungsmodi verwenden.

| Modus | Verwendung | Verantwortung des Architects |
| --- | --- | --- |
| **Entscheiden** | Die Wahl beeinflusst Enterprise Patterns, geteilten Grain, Cross-Domain Interfaces, Kompatibilität oder wesentliches Architekturrisiko. | Die architektonische Entscheidung treffen oder moderieren, dokumentieren und Konsequenzen definieren. |
| **Beraten** | Die accountable Entscheidung liegt beim Data Owner, Steward, Platform Operations oder einer anderen Spezialistenrolle, besitzt aber architektonische Auswirkungen. | Optionen, Constraints, Abhängigkeiten und Auswirkungen erklären. |
| **Innerhalb von Guardrails delegieren** | Die Wahl ist lokal, reversibel und durch veröffentlichte Standards abgedeckt. | Standards bereitstellen, für Eskalation verfügbar bleiben und Ergebnisse über Nachweise verifizieren. |

Eine reife Architekturfunktion maximiert nicht die Zahl zentral getroffener Entscheidungen. Sie maximiert die Zahl korrekter Entscheidungen, die in der Organisation sicher getroffen werden können.

## Zentrale Empfehlungen

1. Die Rolle Data Architect über Accountability für Kohärenz definieren, nicht über Ownership einer bestimmten Plattform.
2. Grain erklären, bevor geteilte Schemas, Facts oder semantische Modelle implementiert werden.
3. Source, Transformation, Data Product, Semantic und Consumption Contracts als einen verbundenen Architekturpfad behandeln.
4. Business Risk und zulässige Nutzung beim Data Owner belassen.
5. Definitionen, Klassifikationen, Qualitätsanforderungen und Freigabenachweise im Stewardship belassen.
6. Laufzeitzuverlässigkeit, Deployment und Observability bei Platform Operations belassen.
7. ADRs für wesentliche Entscheidungen verwenden, nicht für jedes Implementierungsdetail.
8. Standards und Review-Schwellenwerte veröffentlichen, damit Domain Teams autonom handeln können.
9. Für inkompatible Interface-Änderungen eine Impact-Analyse verlangen.
10. Verifizieren, dass implementierte Modelle und Releases der architektonischen Entscheidung entsprechen.

## Als Nächstes: gemeinsame Verantwortlichkeiten mit RACI explizit machen

Diese Story definiert die Entscheidungsgrenze des Data Architects. Die nächste Story im Roles Hub, `raci-for-data-governance`, überführt die Zusammenarbeit zwischen Ownern, Stewards, Architects, Platform Operations und Domain Teams in explizite Verantwortungszuordnungen.

Das Ziel ist keine große Verantwortungsmatrix für jede Aktivität. Es geht darum zu zeigen, wo Accountability eindeutig bei einer Rolle liegt, wo Beiträge geteilt werden und wo Eskalation unmissverständlich sein muss.
