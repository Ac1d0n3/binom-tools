---
title: Data Product Owner vs Data Owner vs Steward — wer entscheidet was
description: Ein praktisches Betriebsmodell zur Trennung von Produktlebenszyklus, Domänenverantwortung und Stewardship-Ausführung bei governten Data Products.
category: Data Governance
tags:
  - data-product-owner
  - data-owner
  - data-steward
  - decision-rights
  - data-product
  - operating-model
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
order: -1
author: Thomas Lindackers
hero: images/playbooks/data-product-owner-vs-data-owner-hero.png
series: roles-hub
seriesTitle: Rollen und Entscheidungsrechte
seriesPart: 3
publishedAt: 2026-07-19 12:00
---

# Data Product Owner vs Data Owner vs Steward — wer entscheidet was

Ein governtes Data Product braucht mehr als eine Produkt-Roadmap, einen benannten Business Owner und einen gepflegten Glossareintrag. Es braucht explizite Entscheidungsrechte.

Data Product Owner, Data Owner und Data Steward arbeiten am selben Produkt, besitzen aber nicht dieselben Entscheidungen. Der Data Product Owner steuert Lebenszyklus, Prioritäten und Consumer Value. Der Data Owner bleibt für fachliche Bedeutung, erlaubte Nutzung und Geschäftsrisiken der Domäne accountable. Der Data Steward pflegt Definitionen, Qualitätserwartungen, Klassifikationen und die Evidenz, die für einen konsistenten Betrieb dieser Entscheidungen erforderlich ist.

Die Rollen können von drei Personen oder in einem kleinen Kontext von einer Person mit mehreren Hüten ausgeübt werden. Entscheidend ist, dass die Entscheidungen unterscheidbar, überprüfbar und eindeutig zuordenbar bleiben.

![Drei klar getrennte Rollenkreise um ein governtes Data Product](images/playbooks/data-product-owner-vs-data-owner-hero.png)

## Das Ausgangsproblem: ein Produkt, mehrere Arten von Autorität

Viele Organisationen führen Data Products ein, bevor sie klären, wer welche Entscheidungen treffen darf. Ein Product Owner wird benannt, ein Data Owner existiert bereits irgendwo im Governance-Modell, und ein Steward pflegt Metadaten und Qualitätsregeln. Die Rollentitel wirken vollständig, das Betriebsmodell ist es jedoch nicht.

Die Folgen sind vorhersehbar:

- der Product Owner beansprucht Autorität über Quellbedeutung und erlaubte Nutzung;
- der Data Owner wird zu einem zeremoniellen Freigeber, der erst kurz vor dem Release kontaktiert wird;
- vom Steward wird erwartet, Geschäftsrisiken zu akzeptieren, obwohl ihm dafür die Autorität fehlt;
- die Produkt-Roadmap überschreibt Enterprise-Definitionen;
- Lebenszyklusentscheidungen werden ohne klare Domänengrenze getroffen;
- eine Person soll gleichzeitig für Produktwert, Policies, Datenqualität und technische Lieferung accountable sein.

Das sind nicht primär Kommunikationsprobleme. Es sind Probleme der Entscheidungsrechte.

Ein belastbares Modell trennt deshalb drei Entscheidungsebenen:

1. **Produktlebenszyklus und Consumer Value**
2. **Domänenverantwortung, Nutzung und Risiko**
3. **Governte Bedeutung, Qualitätserwartungen und Evidenz**

Die Rollen arbeiten über alle drei Ebenen hinweg zusammen. Accountability darf jedoch nicht in einem generischen Ownership-Begriff verschwinden.

## Das zentrale Operating-Model-Prinzip

Die einfachste Abgrenzung lautet:

- **Der Data Product Owner steuert das Produkt.**
- **Der Data Owner bleibt für die Domänenentscheidungen accountable, die im Produkt repräsentiert werden.**
- **Der Data Steward operationalisiert governte Bedeutung, Qualitätserwartungen und Evidenz.**

Diese Abgrenzung baut auf dem umfassenderen Ownership- und Stewardship-Modell aus `data-ownership-stewardship` auf. Sie ersetzt es nicht. Der zusätzliche Zweck besteht darin, zu klären, was sich ändert, wenn governte Daten als Data Product verpackt, betrieben und weiterentwickelt werden.

Ein Data Product hat Consumer, einen expliziten Zweck, einen Lebenszyklus, Service-Erwartungen, Schnittstellen und Änderungsentscheidungen. Dadurch entsteht echte Produktarbeit. Die bestehende Domänenverantwortung für Bedeutung, Nutzung und Risiko entfällt dadurch nicht.

## Drei Rollen, drei Entscheidungsebenen

![Drei Rollen, drei Entscheidungsebenen](images/playbooks/data-product-owner-vs-data-owner-img1-de.png)

### Data Product Owner

Der Data Product Owner führt Entscheidungen über das Produkt als dauerhaft betriebenen Service für Consumer.

Typische Verantwortlichkeiten sind:

- Produktzweck und Zielgruppen definieren;
- Roadmap und Prioritäten steuern;
- Discovery, Release, Änderung und Retirement koordinieren;
- Consumer-Bedarf mit Delivery-Kapazität ausbalancieren;
- Product Backlog pflegen;
- Adoption, Wert und Nutzbarkeit messen;
- Release Readiness über fachliche und technische Beteiligte hinweg koordinieren;
- sicherstellen, dass Product Contract und Dokumentation mit dem Produkt weiterentwickelt werden.

Der Data Product Owner kann Änderungen an Scope, Definitionen, Qualitätszielen oder erlaubter Nutzung vorschlagen. Daraus folgt nicht, dass er alle diese Änderungen selbst genehmigen darf.

### Data Owner

Der Data Owner bleibt für die Business-Domäne accountable, die durch das Data Product repräsentiert wird.

Typische Verantwortlichkeiten sind:

- die autoritative fachliche Bedeutung bestätigen;
- den Produktzweck genehmigen, soweit Domänenverantwortung betroffen ist;
- Grenzen erlaubter Nutzung festlegen oder sponsern;
- Geschäftsrisiken innerhalb der Policy akzeptieren oder ablehnen;
- Finanzierung, Autorität oder Eskalationsunterstützung bereitstellen;
- wesentliche Änderungen genehmigen, die Semantik, Pflichten oder Kritikalität beeinflussen;
- für Retirement accountable bleiben, wenn Consumer, Kontrollen oder regulatorische Pflichten betroffen sind.

Der Data Owner muss weder das Backlog steuern noch jeden Release koordinieren. Seine Accountability liegt bei Entscheidungen, die fachliche Autorität erfordern.

### Data Steward

Der Data Steward überführt Governance-Absicht in gepflegte operative Evidenz.

Typische Verantwortlichkeiten sind:

- Definitionen und Glossar-Ausrichtung pflegen;
- Klassifikation und Sensitivität dokumentieren;
- Qualitätserwartungen definieren oder koordinieren;
- Metadaten, Ownership-Referenzen und Freigabe-Evidenz pflegen;
- Konflikte mit Enterprise-Standards identifizieren;
- Entscheidungen für die accountable Rolle vorbereiten;
- überwachen, ob vereinbarte Kontrollen weiterhin implementiert sind;
- sicherstellen, dass Änderungen in Metadaten und Produktdokumentation nachvollzogen werden.

Der Steward liefert Fachwissen und Evidenz. Er darf nicht gezwungen werden, Geschäftsrisiken zu akzeptieren, nur weil er das Problem entdeckt oder dokumentiert hat.

## Gemeinsame Entscheidungen bedeuten keine gemeinsame Accountability

Mehrere Entscheidungen benötigen alle drei Rollen:

- den Data Product Contract;
- Release Readiness;
- Priorisierung von Qualitätsproblemen;
- wesentliche semantische Änderungen;
- Deprecation und Retirement.

Dass alle drei Rollen beitragen, erzeugt nicht automatisch drei accountable Rollen.

Für jedes Entscheidungsobjekt sollte das Operating Model festlegen:

- wer den Prozess führt;
- wer die Entscheidung genehmigt;
- wer Evidenz oder Expertise beiträgt;
- wer das Ergebnis umsetzt;
- wo die Begründung dokumentiert wird;
- wann eine Eskalation verpflichtend ist.

Das ist der praktische Unterschied zwischen Zusammenarbeit und unscharfer Accountability.

## Explizite Entscheidungsrechte über den Lebenszyklus

Die Rollen werden klarer, wenn sie konkreten Entscheidungen statt allgemeinen Verantwortungsbeschreibungen zugeordnet werden.

| Entscheidungsbereich | Data Product Owner | Data Owner | Data Steward |
|---|---|---|---|
| Produktzweck | Formuliert Consumer-Problem und Wertversprechen | Genehmigt fachlichen Zweck und Grenzen | Prüft Definitionen und Metadaten-Ausrichtung |
| Roadmap und Priorität | Accountable für Backlog und Reihenfolge | Consulted, wenn Risiko, Finanzierung oder Domänenzusagen betroffen sind | Bringt Qualitäts-, Metadaten- und Governance-Arbeit ein |
| Fachliche Bedeutung | Schlägt produktspezifische Repräsentation vor | Accountable für autoritative Business-Bedeutung | Pflegt Definitionen und identifiziert Konflikte |
| Erlaubte Nutzung | Beschreibt beabsichtigte Use Cases | Accountable für zulässige Business-Nutzung innerhalb der Policy | Dokumentiert Klassifikationen, Einschränkungen und Evidenz |
| Definitionen und Metadaten | Sichert nutzbare Produktdokumentation | Bestätigt wesentliche fachliche Bedeutung | Responsible für Pflege und Review-Evidenz |
| Qualitätserwartungen | Priorisiert produktbezogene Qualitätsanforderungen | Genehmigt wesentliche Risikoakzeptanz oder Schwellenwertänderungen | Definiert, dokumentiert und überwacht Erwartungen |
| Release | Koordiniert Readiness und Go-live | Genehmigt wesentliches Risiko oder Policy-Ausnahmen | Bestätigt Metadaten-, Evidenz- und Kontroll-Readiness |
| Consumer Support | Verantwortet Service Experience und Feedback Loop | Unterstützt fachliche Interpretation bei Bedarf | Unterstützt Definitionen und Qualitätsklärung |
| Wesentliche Änderung | Koordiniert Auswirkung, Scope und Timing | Genehmigt Änderungen an Semantik, Nutzung oder Risiko | Bewertet Auswirkungen auf Definition, Lineage, Klassifikation und Evidenz |
| Retirement | Koordiniert Deprecation und Migration | Accountable für fachliche und risikobezogene Entscheidung | Schließt Metadaten, Lineage und Evidenz korrekt ab |

Die Tabelle muss an die jeweilige Policy angepasst werden. Die Trennung sollte jedoch sichtbar bleiben.

## Die einfachste tragfähige Umsetzung

Eine praktikable Umsetzung benötigt kein großes Governance-Programm. Sie benötigt wenige explizite Betriebsartefakte.

### 1. Grenze des Data Products definieren

Das Produkt braucht eine klare Abgrenzung:

- Zweck;
- Ziel-Consumer;
- enthaltene Domänen;
- Schnittstellen oder Outputs;
- autoritative und nicht autoritative Elemente;
- Service-Erwartungen;
- bekannte Einschränkungen;
- Ownership- und Stewardship-Referenzen.

Ohne Produktgrenze wird der Product Owner schnell als Owner aller vorgelagerten Quellen behandelt, die das Produkt nutzt. Das ist falsch.

### 2. Tabelle der Entscheidungsrechte erstellen

Für jede relevante Entscheidung werden definiert:

- Entscheidungsobjekt;
- accountable Rolle;
- Prozessführung;
- erforderliche Contributors;
- ausführende Rolle;
- Ablageort der Evidenz;
- Eskalationsbedingung.

Die Tabelle kann Teil des Product Contracts, des Betriebshandbuchs oder der RACI sein. Entscheidend ist ihre operative Nutzung.

### 3. Einen versionierten Product Contract pflegen

Der Data Product Contract verbindet Produkt-, Domänen- und Stewardship-Entscheidungen.

Mindestens enthalten sein sollten:

- Produktzweck;
- Data Owner und Data Product Owner;
- Data Steward;
- Domänengrenze;
- Datendefinitionen;
- Grain und zentrale Semantik;
- erlaubte Nutzung;
- Qualitätserwartungen;
- Service Levels;
- Change Policy;
- Klassifikationen;
- Abhängigkeiten;
- Release-Status;
- Deprecation-Status;
- Freigabe-Evidenz.

Der Contract ist nicht nur Dokumentation. Er ist die gepflegte Schnittstelle zwischen Produktmanagement und Governance.

### 4. Entscheidungsschwellen verwenden

Nicht jede Entscheidung benötigt dieselbe Genehmigungsstufe.

Lokale Produktentscheidungen können häufig beim Data Product Owner bleiben, wenn sie:

- reversibel sind;
- innerhalb publizierter Standards liegen;
- geringes Risiko haben;
- Implementierungsdetails oder Consumer Experience betreffen;
- keine autoritative Domänenbedeutung verändern.

Eskalation ist erforderlich, wenn eine Entscheidung Folgendes verändert:

- autoritative Definitionen;
- erlaubte Nutzung;
- regulatorische oder vertragliche Pflichten;
- kritische Qualitätsschwellen;
- domänenübergreifende Schnittstellen;
- wesentliche Risiken;
- Retention- oder Access-Anforderungen;
- Retirement eines stark genutzten Produkts.

Entscheidungsschwellen vermeiden sowohl unkontrollierte Autonomie als auch zentrale Freigaben für alles.

### 5. Entscheidungen dort dokumentieren, wo die Arbeit erfolgt

Eine Entscheidung sollte Evidenz im relevanten Betriebsartefakt hinterlassen:

- Product Contract;
- ADR;
- Quality Exception;
- Glossar-Freigabe;
- Release Record;
- Change Request;
- Retirement Plan.

Reine Meeting-Notizen reichen nicht aus, weil sie sich nur schwer mit dem aktuellen Produktzustand verbinden lassen.

## Wer führt über den Lebenszyklus des Data Products

![Wer führt über den Lebenszyklus des Data Products](images/playbooks/data-product-owner-vs-data-owner-img2-de.png)

Ein Data Product durchläuft Discovery, Definition, Design, Build, Release, Betrieb, Änderung und Retirement. In jeder Phase gelten unterschiedliche Beziehungen.

### Discover

Der Data Product Owner führt die Discovery mit Consumern und Delivery-Teams. Ziel ist es, Problem, erwarteten Wert, potenzielle Nutzer und erforderlichen Service zu verstehen.

Der Data Owner bringt den fachlichen Zweck ein und bestätigt, ob die vorgeschlagene Nutzung legitim ist.

Der Steward identifiziert vorhandene Definitionen, Klassifikationen, Qualitätsevidenz und governte Assets, die wiederverwendet werden können.

### Define

Der Product Owner überführt das Discovery-Ergebnis in Produktscope und Backlog.

Der Data Owner genehmigt fachlichen Zweck, erlaubte Nutzung und wesentliche Business-Grenzen.

Der Steward bereitet Definitionen, Metadaten, Klassifikation und Qualitätserwartungen vor.

### Design

Der Product Owner stellt sicher, dass das Design Consumer-Bedarf und Produktlebenszyklus unterstützt.

Der Data Owner wird einbezogen, wenn Designentscheidungen fachliche Bedeutung, Risiko oder Finanzierung beeinflussen.

Der Steward sorgt dafür, dass Definitionen, Qualitätsregeln, Lineage-Anforderungen und Evidenzbedarf im Design berücksichtigt werden.

Engineering- und Platform-Rollen führen das Implementierungsdesign.

### Build

Engineering setzt Implementierung, Tests und Deployment-Vorbereitung um.

Der Product Owner steuert Scope und Priorität.

Der Steward prüft, ob Metadaten, Kontrollen und Qualitätserwartungen implementiert wurden.

Der Data Owner wird einbezogen, wenn offene Entscheidungen fachliche Autorität erfordern.

### Release

Der Product Owner führt die Release-Koordination.

Engineering bestätigt technische Readiness.

Der Steward bestätigt Metadaten, Qualitätsevidenz, Klassifikation und Kontroll-Readiness.

Der Data Owner entscheidet, ob wesentliches Geschäftsrisiko akzeptiert werden kann, der Release verschoben werden muss oder eine autorisierte Ausnahme zulässig ist.

### Operate

Der Product Owner steuert Adoption, Support, Roadmap-Feedback und Service Performance.

Der Steward überwacht Definitionen, Metadatenzustand, Qualitätsevidenz und offene Probleme.

Engineering betreibt die Runtime.

Der Data Owner bleibt für wesentliche Nutzungs- und Risikoentscheidungen accountable, sollte aber nicht in routinemäßige operative Aufgaben gezogen werden.

### Change

Der Product Owner führt Impact-Koordination und Priorisierung.

Der Steward bewertet Auswirkungen auf Semantik, Qualität, Lineage und Klassifikation.

Der Data Owner genehmigt Änderungen, die Bedeutung, erlaubte Nutzung, Pflichten oder wesentliches Risiko verändern.

### Retire

Der Product Owner koordiniert Deprecation, Kommunikation und Migration.

Der Data Owner bleibt für die Retirement-Entscheidung accountable, wenn Business-Zusagen, Risiken oder Pflichten betroffen sind.

Der Steward stellt sicher, dass Metadaten, Lineage, Glossar-Referenzen und Evidenz aktualisiert werden, statt ein irreführend aktives Asset zurückzulassen.

Die zentrale Regel lautet: **Lead**, **Approve**, **Contribute** und **Execute** sind unterschiedliche Beziehungen. Sie dürfen nicht auf ein einziges Owner-Feld reduziert werden.

## Zusammenarbeit mit angrenzenden Rollen

Die drei Rollen arbeiten nicht isoliert.

### Engineering und Platform

Engineering- oder Platform-Rollen verantworten Implementierung, Tests, Deployment und Runtime-Betrieb. Sie können technische Entscheidungen innerhalb von Guardrails treffen, dürfen aber nicht stillschweigend fachliche Bedeutung oder erlaubte Nutzung festlegen.

### Data Architect

Der Data Architect definiert oder verwendet Architekturprinzipien, Schnittstellenmuster, Modellgrenzen und domänenübergreifende Integrationsregeln. Architekturentscheidungen können die Produkt-Roadmap begrenzen, ersetzen aber weder Data Owner noch Product Owner.

### Privacy und Security

Privacy- und Security-Rollen interpretieren Policies und regulatorische Kontrollen. Für bestimmte Entscheidungen können ihnen formale Genehmigungsrechte zugeordnet sein. Der Data Owner bleibt für die Business-Nutzung accountable, während Privacy oder Security gemäß Policy für die Kontrollfreigabe accountable sein können.

### Consumer

Consumer liefern Bedarfssignale, Nutzbarkeitsfeedback und Evidenz zum Produktwert. Häufige Nutzung allein macht ihre Interpretation jedoch nicht zur autoritativen Definition.

### Governance CoE oder Enterprise Definition Authority

Eine Governance-Funktion kann Konflikte lösen, die über ein einzelnes Produkt oder eine einzelne Domäne hinausgehen. Sie sollte Standards und Eskalationsschwellen definieren, statt jede lokale Entscheidung zu genehmigen.

## Konflikte durch explizite Entscheidungsrechte lösen

![Konflikte durch explizite Entscheidungsrechte lösen](images/playbooks/data-product-owner-vs-data-owner-img3-de.png)

Konflikte sind normal. Das Operating Model versagt erst dann, wenn ihre Lösung von Titelhierarchie, informellem Einfluss oder Eskalation in letzter Minute abhängt.

Ein standardisierter Lösungsablauf ist:

1. Entscheidungsobjekt identifizieren.
2. Bestehenden Product Contract, Policy oder Standard anwenden.
3. Erforderliche Rollen konsultieren.
4. Accountable Rolle entscheiden lassen.
5. Begründung und Evidenz dokumentieren.
6. Product Contract und zugehörige Metadaten aktualisieren.

### Konflikt 1: Geschwindigkeit versus Qualität

Der Product Owner möchte releasen, weil Consumer warten. Der Steward identifiziert ein ungelöstes Qualitätsproblem.

Die richtige Frage ist nicht, ob der Steward den Product Owner blockieren darf. Entscheidend ist:

- Welche Qualitätserwartung gilt?
- Ist der Schwellenwert verpflichtend oder advisory?
- Welche Auswirkungen entstehen für Nutzer?
- Ist eine Ausnahme zulässig?
- Wer ist accountable für die Akzeptanz des Geschäftsrisikos?

Innerhalb der Policy akzeptiert der Data Owner das Risiko, lehnt den Release ab oder verlangt Nachbesserung. Der Product Owner koordiniert die daraus entstehende Roadmap-Entscheidung. Der Steward dokumentiert Problem, Evidenz und genehmigtes Ergebnis.

### Konflikt 2: lokale Produktbedeutung versus Enterprise-Definition

Ein Produktteam möchte eine lokale Definition verwenden, weil dies das Produkt vereinfacht. Der Steward erkennt einen Konflikt mit einer Enterprise-Definition.

Der Product Owner kann den Consumer-Bedarf erläutern und eine produktspezifische Repräsentation vorschlagen. Der Steward dokumentiert Konflikt und Auswirkungen. Die Enterprise Definition Authority oder der definierte Governance-Eskalationsweg entscheidet, ob:

- die Enterprise-Definition verpflichtend bleibt;
- ein produktspezifischer Begriff zulässig ist;
- die Enterprise-Definition geändert werden sollte;
- eine temporäre Ausnahme angemessen ist.

Der Product Owner darf einen Enterprise-Begriff nicht stillschweigend über die Roadmap neu definieren.

### Konflikt 3: Consumer Value versus erlaubte Nutzung

Ein neuer Use Case erzeugt klaren Consumer Value, überschreitet aber möglicherweise die genehmigte Nutzung der zugrunde liegenden Daten.

Der Product Owner beschreibt Wert und gewünschten Scope. Der Data Owner bewertet, ob die Nutzung legitim ist. Privacy, Security oder Legal prüfen anwendbare Kontrollen. Der Steward dokumentiert Klassifikation, Einschränkungen und Evidenz.

Das Ergebnis kann sein:

- Nutzung genehmigen;
- Nutzung mit Kontrollen genehmigen;
- Scope begrenzen;
- neue Einwilligung oder Autorität verlangen;
- Nutzung ablehnen.

Der Product Owner passt die Roadmap an. Consumer-Bedarf überschreibt keine Grenzen erlaubter Nutzung.

## Konkretes Beispiel: ein governtes Customer-Performance-Data-Product

Angenommen, ein Data Product stellt monatliche Customer-Performance-Kennzahlen für Sales, Finance und Service bereit.

### Produktentscheidungen

Der Data Product Owner entscheidet:

- welche Consumer Journeys priorisiert werden;
- ob ein neuer dashboard-fähiger Output ergänzt wird;
- wann der nächste Release geplant ist;
- wie Incidents und Feedback bearbeitet werden;
- wann eine alte Schnittstelle deprecated werden soll.

### Domänenentscheidungen

Der Data Owner entscheidet:

- was „aktiver Kunde“ für die autoritative Kennzahl bedeutet;
- ob das Produkt für operatives Targeting genutzt werden darf;
- ob eine Quality Exception für den Monatsabschluss akzeptabel ist;
- ob eine wesentliche Definitionsänderung eingeführt werden darf;
- ob das Produkt nach erfolgreicher Consumer-Migration retired werden kann.

### Stewardship-Entscheidungen und Ausführung

Der Data Steward:

- pflegt die Definition von „aktiver Kunde“;
- dokumentiert den Reporting Grain;
- erfasst Klassifikationen und Metadaten zur erlaubten Nutzung;
- definiert Erwartungen an Vollständigkeit und Freshness;
- dokumentiert Evidenz für eine temporäre Ausnahme;
- prüft, ob abhängige Glossar- und Lineage-Einträge aktualisiert wurden.

### Technische Ausführung

Engineering:

- implementiert Transformationen;
- testet das Produkt;
- deployt den Release;
- betreibt Pipelines und Schnittstellen;
- stellt Runtime-Evidenz bereit.

Die Rollen überschneiden sich, verschmelzen aber nicht.

## Eine Person kann mehrere Hüte tragen

![Eine Person kann mehrere Hüte tragen — die Entscheidungen müssen getrennt bleiben](images/playbooks/data-product-owner-vs-data-owner-img4-de.png)

Kleine oder frühe Teams verfügen möglicherweise nicht über Kapazität für drei getrennte Personen. Eine Person kann tätig sein als:

- Data Product Owner;
- Data Owner Delegate;
- Data Steward.

Das kann tragfähig sein, wenn das Produkt ein geringes Risiko hat, die Domäne eng begrenzt ist und die Arbeitslast beherrschbar bleibt. Erforderliche Schutzmaßnahmen sind:

- bei jeder Entscheidung den aktiven Hut benennen;
- den Entscheidungstyp dokumentieren;
- für Hochrisikoentscheidungen ein unabhängiges Review verwenden;
- Evidenz aufbewahren;
- Rollenkapazität regelmäßig prüfen;
- die Schwelle definieren, ab der eine Trennung verpflichtend wird.

Eine Person kann somit als Steward eine Quality Exception vorbereiten, als Product Owner die Roadmap-Auswirkung bewerten und als Data Owner Delegate die Genehmigung einholen. Das sind drei getrennte Handlungen, auch wenn derselbe Name dahintersteht.

## Wann Rollen getrennt werden müssen

Eine Trennung wird notwendig, wenn:

- Produkt- und Risikoanreize auseinanderlaufen;
- der Product Owner auf Geschwindigkeit optimiert wird, während der Owner langfristige Pflichten schützen muss;
- die Arbeitslast realistische Kapazität überschreitet;
- mehrere Domänen betroffen sind;
- Regulierung oder Policy unabhängiges Review verlangt;
- Audit-Evidenz eindeutige Accountability benötigt;
- eine Person Metadatenpflege und Consumer Discovery nicht mehr zuverlässig verbinden kann;
- wiederkehrende Konflikte informell gelöst werden;
- delegierte Autorität unklar ist;
- das Produkt für Betrieb oder externes Reporting kritisch wird.

Der Übergang sollte explizit erfolgen:

1. wachsenden Scope oder höheres Risiko erkennen;
2. Trennungsschwelle definieren;
3. unterschiedliche Rollen zuweisen;
4. RACI aktualisieren;
5. Product Contract aktualisieren;
6. neue Eskalationswege kommunizieren;
7. das Modell an der nächsten realen Entscheidung verifizieren.

Rollentrennung ist kein Reifegrad-Abzeichen. Sie ist eine Reaktion auf Scale, Risiko, Arbeitslast und Interessenkonflikte.

## Häufige Anti-Patterns

### Der Product Owner besitzt alle Quelldaten

Ein Data Product kann mehrere Quellen aus unterschiedlichen Domänen verwenden. Product Ownership überträgt keine Source Accountability.

### Der Data Owner ist nur zeremonieller Freigeber

Erhält der Owner erst kurz vor dem Release eine finale Anfrage ohne Kontext, Evidenz oder Entscheidungsalternativen, ist Accountability nicht operationalisiert.

### Der Steward akzeptiert Geschäftsrisiko

Der Steward kann Risiko identifizieren und dokumentieren. Die Akzeptanz gehört zu einer autorisierten accountable Rolle.

### Ein Rollentitel versteckt mehrere widersprüchliche Entscheidungen

Ein Titel wie „Data Lead“ kann Produkt-, Domänen-, Stewardship- und technische Autorität kombinieren. Der Titel ist nicht das Problem. Verdeckte Entscheidungen sind es.

### Die Roadmap überschreibt Enterprise-Definitionen

Eine Roadmap ist kein Eskalationsmechanismus. Definitionskonflikte benötigen eine explizite Authority und dokumentierte Lösung.

### Ownership existiert nur auf Asset-Ebene

Ein Owner-Feld an einer Tabelle definiert weder Domänengrenzen noch Produktautorität oder Entscheidungen zur erlaubten Nutzung. Asset-Metadaten müssen mit einem breiteren Operating Model verbunden sein.

### Jede Entscheidung benötigt zentrale Governance-Freigabe

Das erzeugt Warteschlangen, Verzögerungen und Schattenentscheidungen. Lokale Entscheidungen sollten lokal bleiben, solange sie innerhalb veröffentlichter Guardrails liegen.

### RACI wird einmal erstellt und nie gepflegt

Die RACI muss angepasst werden, wenn sich Organisation, Produktscope, Regulierung, Arbeitslast oder Platform-Verantwortung ändern.

## Entscheidungshilfe

Die folgenden Fragen helfen, wenn die accountable Rolle unklar ist:

1. Geht es primär um Consumer Value, Priorität oder Lebenszyklus?
   - Der Data Product Owner sollte führen.

2. Verändert die Entscheidung autoritative Bedeutung, erlaubte Nutzung, Business-Pflichten oder wesentliches Risiko?
   - Der Data Owner oder eine andere durch Policy definierte Authority sollte entscheiden.

3. Geht es um Definitionen, Klassifikation, Qualitätserwartungen, Metadaten oder Evidenz?
   - Der Data Steward sollte Vorbereitung und Pflege führen.

4. Geht es um Implementierung, Deployment oder Runtime-Betrieb innerhalb vereinbarter Guardrails?
   - Engineering oder Platform sollte umsetzen und kann lokal entscheiden.

5. Überschreitet die Entscheidung Domänen-, Policy- oder Enterprise-Definitionsgrenzen?
   - Der definierte Eskalationsweg ist zu verwenden.

6. Kann eine Person mehrere Rollen ausüben, ohne Interessenkonflikte zu verbergen oder Kapazität zu überschreiten?
   - Kombinierte Hüte können zulässig sein, die Entscheidungstypen müssen jedoch explizit bleiben.

## Zentrale Empfehlungen

- Rollen über Entscheidungsobjekte definieren, nicht nur über Stellenbeschreibungen.
- Data-Product-Lebenszyklus von Domänen-Accountability trennen.
- Stewardship-Ausführung von Risikoakzeptanz trennen.
- Einen versionierten Product Contract verwenden, der Zweck, Bedeutung, Nutzung, Qualität und Lebenszyklus verbindet.
- Lead, Approve, Contribute und Execute unterscheiden.
- Guardrails für lokale Entscheidungen und Eskalationsschwellen definieren.
- Begründung und Evidenz in den Betriebsartefakten des Produkts dokumentieren.
- Kombinierte Hüte nur mit expliziten Schutzmaßnahmen zulassen.
- Rollen trennen, wenn Risiko, Scale, Regulierung, Arbeitslast oder Anreize dies verlangen.
- Das Operating Model prüfen, wenn reale Entscheidungen es wiederholt umgehen.

## Übergang

Dieses Playbook klärt die Entscheidungsrechte zwischen Data Product Owner, Data Owner und Data Steward. Die nächste Roles-Hub-Story `stewardship-capacity` behandelt die daraus folgende operative Frage: Wie viel Kapazität, Coverage und Eskalationsunterstützung benötigt Stewardship, damit klar definierte Verantwortlichkeiten auch zuverlässig ausgeführt werden können?
