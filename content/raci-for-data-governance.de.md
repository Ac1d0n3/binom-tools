---
title: RACI für Data Governance — Decision Rights ohne Role Sprawl
description: RACI als gepflegten Operating Contract für Datenentscheidungen einsetzen – mit genau einer accountable Rolle, klarer Ausführungsverantwortung, gezielter Konsultation und evidenzbasierter Eskalation.
category: Data Governance
tags:
  - raci
  - decision-rights
  - data-governance
  - data-owner
  - data-steward
order: -1
author: Thomas Lindackers
hero: images/playbooks/raci-for-data-governance-hero.png
series: roles-hub
seriesTitle: Rollen und Entscheidungsrechte
seriesPart: 2
---

RACI wird häufig als einfache Matrix eingeführt: Rollen stehen in den Spalten, Aktivitäten in den Zeilen und die Buchstaben R, A, C und I in den Zellen. Dieses Format lässt sich schnell erstellen. Ein belastbares RACI Operating Model ist anspruchsvoller.

In der Data Governance muss die Matrix klären, wie eine konkrete Entscheidung vorbereitet, getroffen, umgesetzt, kommuniziert und eskaliert wird. Sie muss sichtbar machen, wer die Arbeit ausführt, wer die Entscheidung und ihr Ergebnis verantwortet, wessen Expertise die Entscheidung beeinflussen kann und wer lediglich über das Resultat informiert werden muss. Richtig eingesetzt reduziert RACI Unklarheit und verhindert, dass Governance-Aufgaben zwischen Business, Data, Plattform, Architektur, Privacy und Security verloren gehen.

Falsch eingesetzt wird RACI zu einem Organigramm mit Buchstaben. Jede Rolle wird jeder Entscheidung zugeordnet, mehrere Personen werden als Accountable markiert, Konsultation ersetzt die eigentliche Entscheidung und Gremien verdecken, wer tatsächlich Ja oder Nein sagen darf.

Das Ziel ist deshalb nicht die größtmögliche Matrix. Das Ziel ist der kleinste klare Decision Contract, der im Betrieb funktioniert.

## Mit der Entscheidung beginnen, nicht mit der Abteilung

Eine RACI-Zeile sollte eine Entscheidung oder eine konkrete Arbeitseinheit beschreiben. Sie sollte nicht nur einen breiten Prozess wie „Data Governance“, „Data Quality“ oder „Metadata“ benennen. Solche Begriffe sind zu weit, um belastbare Entscheidungsrechte zuzuweisen.

Geeignete Decision Objects sind so konkret, dass die Organisation vier Fragen beantworten kann:

1. Was muss genau entschieden oder abgeschlossen werden?
2. Welche Evidenz wird vor der Entscheidung benötigt?
3. Wer besitzt die Autorität, das Ergebnis zu akzeptieren?
4. Was geschieht, wenn Arbeit, Evidenz oder Autorität fehlen?

Beispiele sind:

- Schutzmaßnahmen für ein PII-Attribut genehmigen;
- einen governten Mart oder ein Data Product freigeben;
- eine geänderte KPI-Definition genehmigen;
- eine temporäre Data-Quality-Ausnahme akzeptieren;
- ein Data Product stilllegen;
- eine inkompatible Data-Contract-Änderung genehmigen;
- einen Konflikt zwischen zwei Domain-Definitionen lösen.

Diese Entscheidungen sind nicht austauschbar. Sie können dieselben Rollen einbeziehen und dennoch unterschiedliche RACI-Zuordnungen benötigen. Ein Data Owner kann für die zulässige fachliche Nutzung accountable sein, während eine Privacy Authority eine Policy-Entscheidung innerhalb eines konkreten regulatorischen Rahmens verantwortet. Ein Data Product Owner kann für die Go-live-Entscheidung accountable sein, während Platform Operations Deployment und Support ausführt. Ein fachlicher KPI Owner kann die Bedeutung einer Kennzahl genehmigen, während ein Data Steward die Definition koordiniert und ein Engineering-Team sie implementiert.

RACI muss deshalb Entscheidungsrechte modellieren und nicht Hierarchie.

![RACI-Entscheidungsmodell mit sparsamen Rollenzuordnungen](images/playbooks/raci-for-data-governance-img1-de.png)

## Responsible und Accountable sind unterschiedliche operative Pflichten

Die Unterscheidung zwischen Responsible und Accountable ist der Kern des Modells.

### Responsible führt die Arbeit aus

Eine Responsible Rolle erledigt die Aktivitäten, die notwendig sind, um die Entscheidung vorzubereiten oder umzusetzen. Abhängig von der Entscheidung kann das Folgendes umfassen:

- Quellinformationen zusammentragen;
- eine vorgeschlagene Definition dokumentieren;
- Lineage und Auswirkungen bewerten;
- eine Masking- oder Access-Regel implementieren;
- Tests ausführen;
- ein Approval Package vorbereiten;
- eine genehmigte Änderung deployen;
- Evidenz nach der Umsetzung dokumentieren.

Mehrere Responsible Rollen sind möglich, wenn die Arbeit tatsächlich mehrere Disziplinen benötigt. Eine Entscheidung über PII-Schutz kann beispielsweise einen Data Steward für Klassifikation und Kontext sowie eine Engineering- oder Plattformrolle für die technische Umsetzung erfordern.

Jedes zusätzliche R muss jedoch mit konkreter Arbeit verbunden sein. Personen nur deshalb als Responsible einzutragen, weil sie interessiert, senior oder organisatorisch benachbart sind, erzeugt Scheinverantwortung und verschleiert den tatsächlichen Delivery Path.

### Accountable verantwortet Entscheidung und Ergebnis

Die Accountable Rolle besitzt die Autorität und Pflicht, das vorgeschlagene Ergebnis zu akzeptieren oder abzulehnen. Sie trägt außerdem die Verantwortung für die Konsequenzen der Entscheidung innerhalb ihres definierten Scopes.

Accountability umfasst:

- zu bestätigen, dass die notwendige Evidenz ausreicht;
- die finale Entscheidung zu treffen oder formal zu verantworten;
- das verbleibende Risiko innerhalb delegierter Autorität zu akzeptieren;
- Eskalationen zu lösen oder die nächste Eskalationsstufe einzubeziehen;
- sicherzustellen, dass eine genehmigte Ausnahme einen Owner und ein Ablaufdatum besitzt;
- das Ergebnis bei einer späteren Überprüfung vertreten zu können.

Accountable ist kein Synonym für die ranghöchste Person im Raum. Es ist auch nicht die Rolle, die sämtliche Arbeit ausführt. Eine Führungskraft ohne operative Entscheidungsautorität sollte nicht automatisch das A erhalten. Ein Steward, der Evidenz vorbereitet, sollte nicht automatisch das A erhalten, wenn eine Policy die finale Entscheidung einem Data Owner, einer Privacy Authority oder einem fachlichen Owner zuweist.

Jede Entscheidung sollte genau eine Accountable Rolle besitzen. Zwei oder mehr As weisen meist auf eines von drei Problemen hin:

- die Entscheidung wurde noch nicht weit genug zerlegt;
- Autoritätsgrenzen sind ungeklärt;
- die Organisation vermeidet einen klaren Owner für das Ergebnis.

Wenn zwei Autoritäten tatsächlich unterschiedliche Aspekte entscheiden, muss die Zeile geteilt werden. Beispielsweise können „fachliche Datennutzung genehmigen“ und „erforderliche Privacy-Kontrolle genehmigen“ getrennte Entscheidungen sein. Jede neue Entscheidung kann anschließend genau ein A erhalten.

![Responsible und Accountable an unterschiedlichen Entscheidungsschritten](images/playbooks/raci-for-data-governance-img2-de.png)

## Consulted und Informed müssen selektiv bleiben

Consulted und Informed sind keine schwächeren Formen von Accountability. Sie erfüllen andere Zwecke.

Eine Consulted Rolle liefert Expertise vor der Entscheidung. Konsultation ist nur dann sinnvoll, wenn der Input die Entscheidung, die Genehmigungsbedingungen oder den Umsetzungsweg materiell verändern kann. Beispiele sind:

- Privacy bewertet, ob eine geplante Nutzung zulässig ist;
- Security beurteilt die Angemessenheit einer Kontrolle;
- ein Data Architect erkennt domainübergreifende oder plattformbezogene Auswirkungen;
- ein Domain Expert validiert die fachliche Bedeutung eines KPI;
- Platform Operations bestätigt, ob ein operatives SLA unterstützt werden kann.

Die Konsultation benötigt einen definierten Punkt im Entscheidungsablauf. Es muss klar sein, was geprüft wird, bis wann Input erwartet wird und was geschieht, wenn die konsultierte Rolle nicht reagiert. Ohne diese Grenzen wird C zu einem informellen Veto oder einem unbegrenzten Wartezustand.

Eine Informed Rolle erhält die Entscheidung oder das Umsetzungsergebnis, weil nachgelagerte Arbeit, Support, Reporting oder Nutzung betroffen sind. Sie ist nicht Teil der Genehmigung. Beispiele sind Report Owner, die auf eine geänderte KPI-Definition reagieren müssen, Consumer eines stillgelegten Data Products oder Support-Teams, die Release-Datum und Betriebsinformationen benötigen.

C und I pauschal an alle zu vergeben erhöht die Governance nicht. Es erzeugt Benachrichtigungsrauschen, verlangsamt Entscheidungen und macht die Matrix unlesbar. Eine sparsame Matrix ist häufig ein Zeichen dafür, dass die Entscheidung sauber definiert wurde.

## RACI als Operating Contract behandeln

Eine belastbare RACI-Zeile enthält mehr als vier Buchstaben. Sie ist mit dem operativen Kontext verbunden, in dem die Entscheidung stattfindet.

Mindestens sollte der Decision Contract Folgendes definieren:

- **Decision Object:** die genaue Approval-, Change-, Exception- oder Retirement-Entscheidung.
- **Trigger:** das Ereignis, das die Entscheidung startet, beispielsweise eine Schema-Änderung, ein neuer Use Case, ein Release Candidate oder ein Policy Finding.
- **Cadence oder Zeiterwartung:** ob die Entscheidung ereignisgetrieben, regelmäßig oder an eine Zielreaktionszeit gebunden ist.
- **Erforderliche Evidenz:** Artefakte wie Lineage, Testergebnisse, Impact Analysis, Policy Mapping, Owner-Bestätigung oder Rollback Plan.
- **RACI-Zuordnung:** ein A, ein oder mehrere explizite Rs, wenn Arbeit erforderlich ist, sowie selektive Cs und Is.
- **Decision Record:** der Ort, an dem Ergebnis, Bedingungen, Approver und Effective Date dokumentiert werden.
- **Eskalationspfad:** das Vorgehen, wenn Autorität, Evidenz, Kapazität oder Einigung fehlen.
- **Review Trigger:** die Umstände, unter denen die RACI-Zuordnung selbst neu bewertet werden muss.

Damit wird die Matrix zu einer praktischen Schnittstelle zwischen Rollen. Gleichzeitig verhindert sie einen häufigen Governance-Fehler: Eine Rolle ist nominell accountable, besitzt aber weder die Evidenz noch die Kapazität oder Autorität, um die Entscheidung tatsächlich zu treffen.

Die Stakeholder-/RACI-Matrix im Hub kann diese Arbeit unterstützen, indem sie Entscheidungen und Rollenzuordnungen sichtbar macht. Sie ersetzt jedoch nicht die zugrunde liegende Klärung von Autorität, Evidenz und Eskalation. Ein ausgefülltes Tool beweist noch nicht, dass das Operating Model funktioniert.

## Die einfachste tragfähige Umsetzung

Eine erste Umsetzung benötigt keine unternehmensweite Matrix für jede Governance-Aktivität. Beginne mit einer kleinen Zahl konfliktbelasteter Entscheidungen, bei denen Unklarheit bereits Verzögerung, Nacharbeit oder Risiko verursacht.

Ein praktikabler Ablauf ist:

1. Drei bis fünf wiederkehrende Governance-Entscheidungen auswählen.
2. Jede Entscheidung in einem Satz mit eindeutigem Ergebnis beschreiben.
3. Die vor der Genehmigung erforderliche Evidenz definieren.
4. Die Rolle benennen, die tatsächlich akzeptieren oder ablehnen darf.
5. Rollen für Vorbereitung und Umsetzung zuweisen.
6. Nur Konsultationen ergänzen, die das Ergebnis verändern können.
7. Nur Empfänger ergänzen, die auf das Ergebnis reagieren müssen.
8. Reaktionszeiten und Eskalationspfad definieren.
9. Die RACI-Zuordnung bei der nächsten realen Entscheidung testen.
10. Sie anhand des beobachteten Verhaltens überarbeiten, nicht anhand theoretischer Organisationsbilder.

Nicht mit einer Liste aller Jobtitel beginnen und anschließend versuchen, jeder Rolle einen Buchstaben zu geben. Dieser Ansatz optimiert Repräsentation statt Entscheidungsqualität.

## Drei Governance-Entscheidungen benötigen drei unterschiedliche RACIs

Die folgenden Rollennamen sind Beispiele. Organisationen können andere Titel verwenden, solange Autorität und Arbeit explizit bleiben.

![Drei Governance-Entscheidungen mit unterschiedlichen RACI-Zuordnungen](images/playbooks/raci-for-data-governance-img3-de.png)

### Szenario 1: Genehmigung von PII-Schutz

Ein neues Attribut wird als personenbezogene Information klassifiziert und soll über ein governtes analytisches Produkt bereitgestellt werden.

Die Entscheidung muss beantworten:

- Ist die Klassifikation korrekt?
- Ist die beabsichtigte Nutzung zulässig?
- Welcher Schutz ist erforderlich?
- Wer implementiert und verifiziert die Kontrolle?
- Welche Consumer sind betroffen?

Eine tragfähige Zuordnung kann so aussehen:

- **Accountable:** der Data Owner oder eine genehmigte Privacy Authority, entsprechend Policy und delegierten Entscheidungsrechten.
- **Responsible:** der Data Steward für Klassifikationskontext und Evidenz; die technische Implementierungsrolle für Masking, Access Control oder andere Schutzmaßnahmen.
- **Consulted:** Privacy, Security und Data Architect, sofern rechtliche Interpretation, Control Design oder plattformübergreifende Auswirkungen relevant sind.
- **Informed:** betroffene Consumer und Platform Operations.

Entscheidend ist nicht, dass der Data Owner immer A sein muss. Die Policy muss festlegen, ob der Data Owner das verbleibende Risiko genehmigen darf oder ob eine Privacy Authority diese Entscheidung verantwortet. RACI muss die reale Autorität abbilden.

Zur Evidenz können Klassifikationsbegründung, beabsichtigte Nutzung, Source- und Downstream-Lineage, vorgeschlagene technische Kontrolle, Testergebnisse, Exception-Bedingungen und Effective Date gehören.

### Szenario 2: Go-live eines governten Marts oder Data Products

Ein governter Mart ist für die produktive Nutzung vorbereitet. Die Entscheidung betrifft nicht nur die Frage, ob Code deployt werden kann. Sie betrifft die Frage, ob das Produkt für seinen erklärten Zweck und seine operativen Zusagen geeignet ist.

Eine tragfähige Zuordnung kann so aussehen:

- **Accountable:** die für das Data Product accountable Rolle oder ein eindeutig benannter fachlicher Owner.
- **Responsible:** das Engineering- oder Platform-Delivery-Team, das Release, Tests und Deployment umsetzt.
- **Consulted:** Data Architect, Data Steward und Security, sofern Architekturkonformität, Metadata-Vollständigkeit oder Schutzkontrollen die Freigabe beeinflussen können.
- **Informed:** Consumer und Support-Teams.

Erforderliche Evidenz kann Data Contract, Grain, Ownership, Quality Results, Lineage, Access Model, SLA, Rollback Plan, bekannte Einschränkungen und Support Readiness umfassen.

Der Data Architect kann feststellen, dass das Release nicht einem genehmigten Pattern entspricht. Der Steward kann fehlende Definitionen oder Ownership identifizieren. Security kann eine nicht akzeptable Kontrolllücke erkennen. Diese Inputs können die Entscheidung verändern, machen die Rollen aber nicht automatisch accountable für das Produktergebnis.

### Szenario 3: Änderung einer KPI-Definition

Eine fachliche KPI-Definition ändert sich, weil sich Geschäftsregel, Population, Zeitfenster oder Ausschlusslogik verändert haben.

Eine tragfähige Zuordnung kann so aussehen:

- **Accountable:** der fachliche KPI Owner.
- **Responsible:** der Data Steward, der die governte Definition koordiniert, sowie das Implementierungsteam, das Semantic Model, Transformation oder Report-Logik anpasst.
- **Consulted:** Data Architect und betroffene Domain Experts.
- **Informed:** Report Owner und Consumer.

Zur Evidenz können alte und neue Definition, Begründung, Grain, Population, Effective Date, historische Behandlung, betroffene Reports, Validation Results und Kommunikationsplan gehören.

Der KPI Owner ist accountable für die fachliche Bedeutung. Das Implementierungsteam ist responsible für die korrekte technische Umsetzung. Der Steward stellt sicher, dass die genehmigte Definition und die zugehörigen Metadaten aktualisiert werden. Das sind getrennte Pflichten.

## Zusammenarbeit mit angrenzenden Rollen

RACI funktioniert nur, wenn die Rollengrenzen zu den angrenzenden Operating Practices passen.

### Data Owner und Data Steward

Ownership und Stewardship sollten bereits im übergeordneten Governance-Modell definiert sein. RACI ersetzt diese Definitionen nicht. Es wendet sie auf konkrete Entscheidungen an.

Der Data Owner trägt häufig Accountability für fachliche Nutzung, Priorität, Risikoakzeptanz innerhalb delegierter Autorität und Domain Outcomes. Der Data Steward übernimmt häufig Koordination, Evidenzvorbereitung, Metadata-Pflege und Issue Follow-up. Dieses Muster ist nützlich, aber nicht universell. Die endgültige Zuordnung hängt vom Decision Object und der Policy ab.

### Data Architect

Der Data Architect ist typischerweise Consulted, wenn eine Entscheidung Architecture Standards, domainübergreifende Interfaces, Data Contracts, semantische Konsistenz, Plattformgrenzen oder Reversibilität betrifft. Er kann Responsible für Architecture Analysis oder einen Architecture Decision Record sein. Accountable sollte er nur dort sein, wo die Organisation die entsprechende Architekturentscheidung explizit delegiert hat.

### Privacy und Security

Privacy und Security sollten nicht automatisch jeder Datenentscheidung hinzugefügt werden. Sie sind Consulted, wenn ihre Expertise benötigt wird, und können Accountable sein, wenn Policy oder Regulierung ihnen formale Autorität zuweist. RACI muss zwischen fachlichem Input und formaler Approval Authority unterscheiden.

### Platform Operations und Engineering

Plattform- und Engineering-Rollen sind häufig Responsible für Implementierung, Deployment, Monitoring und Rollback. Sie können zur Machbarkeit und Supportability konsultiert werden. Sie sollten nicht für fachliche Bedeutung oder zulässige Nutzung accountable gemacht werden, nur weil sie die Technologie betreiben.

### Governance Lead und CoE

Ein Governance Lead oder CoE kann Standards definieren, die Matrix moderieren, fehlende Rollen erkennen und strukturelle Lücken eskalieren. Diese Instanz sollte nicht zum Default-A für jede ungeklärte Entscheidung werden. Zentrale Governance, die sämtliche Accountability absorbiert, erzeugt einen Bottleneck und schwächt Domain Ownership.

Das vollständige Design eines Governance CoE gehört in das separate Playbook `governance-coe`.

## Häufige Anti-Patterns

### Mehrere Accountable Rollen

Mehrere As machen Eskalationen unklar und erlauben jeder Partei anzunehmen, dass eine andere das finale Ergebnis verantwortet. Die Entscheidung muss zerlegt oder die Autoritätsgrenze geklärt werden.

### Keine Responsible Rolle

Eine Entscheidung kann einen klaren Approver haben und trotzdem scheitern, weil niemand Vorbereitung oder Umsetzung übernimmt. R darf nur Rollen mit konkreten Deliverables zugewiesen werden.

### Jobtitel statt Entscheidungsrechte

„Director“, „Manager“ oder „Lead“ erklärt nicht, welche Autorität ausgeübt wird. Der Scope der Entscheidung und die delegierte Autorität hinter dem Titel müssen beschrieben werden.

### C und I für alle

Universelle Konsultation verzögert Arbeit. Universelle Information erzeugt Rauschen. Eine Rolle gehört nur in die Matrix, wenn ihr Input die Entscheidung verändern kann oder sie auf das Ergebnis reagieren muss.

### Accountability durch ein Gremium

Ein Gremium kann Evidenz prüfen oder konsultiert werden. Es sollte nicht verdecken, wer das Ergebnis akzeptieren oder ablehnen darf. Wo kollektive Genehmigung formal oder rechtlich erforderlich ist, müssen Entscheidungsregel sowie die für Abschluss des Decision Record und Eskalation verantwortliche Instanz definiert sein.

### RACI einmal erstellen und nie überarbeiten

Eine alte Matrix kann nach Reorganisation, Domain-Änderung, Plattformmigration oder regulatorischer Änderung überholte Autorität konservieren. Eine veraltete RACI ist gefährlicher als keine Matrix, weil sie falsche Sicherheit erzeugt.

### Accountable bedeutet „macht die gesamte Arbeit“

Dieses Muster überlastet seniorige Rollen und schwächt Execution Ownership. A verantwortet Entscheidung und Ergebnis; R führt die Arbeit aus.

### Konsultation ohne Frist

Eine Consulted Rolle kann eine Entscheidung unbeabsichtigt unbegrenzt blockieren. Erwartete Reaktionszeit und Eskalationspfad müssen definiert sein.

## RACI neu verhandeln, wenn sich der operative Kontext ändert

RACI ist ein gepflegter Operating Contract. Er muss sich ändern, wenn sich das Verhältnis von Arbeit, Autorität und Risiko verändert.

![Lifecycle zur Neuverhandlung einer RACI-Zuordnung](images/playbooks/raci-for-data-governance-img4-de.png)

Die Matrix sollte überprüft werden, wenn:

- sich die Organisation verändert;
- eine Domain geteilt, zusammengeführt oder übertragen wird;
- sich Plattformverantwortung ändert;
- eine neue Regulierung, Policy oder ein materielles Risiko entsteht;
- Entscheidungen wiederholt verzögert werden;
- Eskalationen regelmäßig am definierten Pfad vorbeilaufen;
- einer zugewiesenen Rolle die Kapazität fehlt;
- doppelte Approvals entstehen;
- erforderliche Aktionen wiederholt ausbleiben;
- das nominelle A keine reale Autorität besitzt;
- Consumer oder Support-Teams Informationen regelmäßig zu spät erhalten.

Die Überprüfung sollte das dokumentierte Modell mit dem tatsächlichen Verhalten vergleichen:

1. Trigger beobachten.
2. Die scheiternde Entscheidung identifizieren.
3. Prüfen, wer die Arbeit tatsächlich ausführt und wer Autorität besitzt.
4. RACI und erforderliche Evidenz aktualisieren.
5. Eskalationspfad bestätigen.
6. Den geänderten Contract kommunizieren.
7. Die Änderung bei der nächsten realen Entscheidung verifizieren.

Gemessen werden sollte, ob sich Decision Time, ungelöste Eskalationen, doppelte Approvals, fehlende Aktionen und Stakeholder Feedback verbessern. Das Ziel ist nicht Matrix-Compliance um ihrer selbst willen. Das Ziel ist ein schnellerer, klarerer und auditierbarer Entscheidungsweg.

## Entscheidungshilfe

Vor der Freigabe einer RACI-Zeile sollten folgende Tests durchgeführt werden:

- **One-A Test:** Ist genau eine Rolle für diese Entscheidung accountable?
- **Authority Test:** Kann diese Rolle tatsächlich akzeptieren, ablehnen und eskalieren?
- **Work Test:** Ist jede Responsible Rolle mit einem konkreten Deliverable verbunden?
- **Evidence Test:** Ist das Decision Package definiert?
- **Consultation Test:** Kann jedes C das Ergebnis materiell verändern?
- **Information Test:** Muss jedes I auf das Ergebnis reagieren?
- **Timing Test:** Sind Reaktionszeiten und Fristen klar?
- **Escalation Test:** Ist die nächste Autorität bekannt, wenn die Entscheidung stockt?
- **Audit Test:** Kann die Organisation später rekonstruieren, was entschieden wurde, von wem, auf welcher Evidenz und unter welcher Policy Version?
- **Change Test:** Sind Trigger für die Überarbeitung der Matrix definiert?

Eine RACI-Zeile, die diese Tests nicht besteht, sollte nicht mit weiteren Rollen erweitert werden. Sie sollte vereinfacht oder zerlegt werden.

## Zentrale Empfehlungen

1. Entscheidungen und konkrete Arbeit modellieren, nicht Abteilungen.
2. Genau eine Accountable Rolle pro Entscheidung verlangen.
3. Decision Ownership von Execution Responsibility trennen.
4. Consulted nur nutzen, wenn Input die Entscheidung verändern kann.
5. Informed nur nutzen, wenn das Ergebnis nachgelagerte Arbeit betrifft.
6. Jede Zeile mit Evidenz, Timing, Decision Record und Eskalation verbinden.
7. Mit einer kleinen Zahl wiederkehrender konfliktbelasteter Entscheidungen beginnen.
8. Die Matrix an realen Entscheidungen validieren, nicht nur in Workshops.
9. Zuordnungen neu verhandeln, wenn sich Autorität, Kapazität, Risiko oder Plattformverantwortung ändern.
10. Verhindern, dass zentrale Governance oder Gremien zum Default Owner jeder ungeklärten Entscheidung werden.

## Was als Nächstes kommt

RACI klärt, wie eine Entscheidung zwischen Rollen verläuft. Es beantwortet jedoch noch nicht, wo fachliche Accountability endet und Produktverantwortung beginnt. Die nächste Story der Serie Rollen und Entscheidungsrechte, `data-product-owner-vs-data-owner`, trennt diese beiden Rollen und zeigt, wie ihre Entscheidungssphären zusammenwirken, ohne doppelte Ownership zu erzeugen.
