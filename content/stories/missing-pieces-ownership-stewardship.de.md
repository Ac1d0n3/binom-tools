---
title: "Die fehlenden Bausteine – Teil 3: Data Ownership & Stewardship"
description: "Warum zugewiesene Rollen nicht automatisch zu wirksamer Verantwortung führen – und wie klare Zuständigkeiten, Entscheidungswege und nutzbare Prozesse Ownership und Stewardship handlungsfähig machen."
author: Thomas Lindackers
category: Data Governance
tags:
  - the-missing-pieces
  - data-ownership
  - data-stewardship
  - accountability
  - operating-model
  - data-governance
  - data-products
  - governance-usability
order: -1
hero: images/playbooks/mp-ownership-hero.png
publishedAt: 2026-07-13
series: missing-pieces
seriesPart: 3
seriesTitle: Die fehlenden Bausteine
---

## Eine zugewiesene Rolle ist ein wichtiger Anfang – aber noch kein Betriebsmodell

Moderne Governance-Plattformen können Verantwortlichkeiten sichtbar machen. Data Owner, Data Stewards, Data Product Owner, Custodians und weitere Rollen lassen sich Domains, Datenprodukten, Kennzahlen, Glossarbegriffen oder technischen Assets zuordnen.

Das ist wichtig. Ohne sichtbare Zuständigkeit bleibt häufig bereits die erste Frage unbeantwortet:

> *Wer ist die richtige Ansprechperson für dieses Datenobjekt?*

Doch eine weitere Frage ist mindestens ebenso relevant:

> **Kann die zugewiesene Person im Alltag tatsächlich entscheiden, priorisieren, eskalieren und Veränderungen anstoßen?**

Zwischen einem Namen im Katalog und einer wirksamen Governance-Praxis liegt ein Operating Model.

Dieses Playbook stellt deshalb nicht infrage, ob Ownership und Stewardship benötigt werden. Es untersucht, welche Bedingungen aus einer dokumentierten Rolle eine handlungsfähige Verantwortung machen.

## Ownership, Stewardship und Accountability sind nicht dasselbe

Die Begriffe werden in Organisationen unterschiedlich verwendet. Es gibt kein universelles Rollenmodell, das für jedes Unternehmen gleich aussehen muss.

Trotzdem hilft eine klare Trennung der Verantwortungsarten:

| Rolle | Typischer Fokus | Typische Leitfrage |
| --- | --- | --- |
| Business Data Owner | Geschäftswert, Zweck, Priorisierung, Richtlinien und fachliche Entscheidungen | Welche Bedeutung und welchen Stellenwert hat dieses Datenprodukt für den Fachbereich? |
| Data Steward | Definitionen, Qualität, Kontext, Transparenz, Abstimmung und tägliche Governance-Prozesse | Sind Daten und Metadaten verständlich, nutzbar und innerhalb der vereinbarten Regeln gepflegt? |
| Data Product Owner | Produktvision, Nutzerbedarf, Roadmap, Servicequalität und Lebenszyklus | Liefert das Datenprodukt den erwarteten Nutzen für seine Verbraucher? |
| System Owner / Data Custodian | Plattform, Anwendung, technische Kontrollen, Betrieb und Zugriff | Wie wird die technische Verfügbarkeit, Sicherheit und Umsetzung gewährleistet? |
| Data / Analytics Team | Datenmodelle, Pipelines, Tests, Reports und technische Lösungen | Wie werden fachliche Anforderungen technisch zuverlässig umgesetzt? |
| Business User / Data Consumer | Nutzung, fachliches Feedback und Meldung von Problemen | Kann ich die Daten verstehen, richtig einsetzen und Abweichungen melden? |

Die konkreten Namen können abweichen. Entscheidend ist, dass Zweck, Entscheidungsrechte und Übergaben verständlich sind.

Ein Data Steward muss nicht jede Pipeline reparieren. Ein Data Owner muss nicht jede Metadatenbeschreibung selbst pflegen. Ein Platform Team sollte nicht allein entscheiden müssen, welche fachliche Definition richtig ist.

Governance wird wirksam, wenn Rollen zusammenarbeiten – nicht wenn eine einzelne Rolle alle offenen Aufgaben übernehmen soll.

## Warum zugewiesene Ownership passiv bleiben kann

Eine formale Zuweisung kann vollständig und korrekt sein, während der operative Weg trotzdem unklar bleibt.

Ein mögliches Muster sieht so aus:

```flow linear vertical
Data Asset / KPI / Report
Owner zugewiesen
Steward zugewiesen
Problem erkannt
unklarer Entscheidungs- oder Eskalationsweg
lokale Korrektur oder langsame Bearbeitung
```

Das bedeutet nicht automatisch, dass die zugewiesenen Personen ihre Rolle nicht ernst nehmen. Häufig fehlen Rahmenbedingungen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-ownership-img1-de.png"
        alt="Darstellung, warum dokumentierte Ownership ohne klare Entscheidungsrechte, Zeit, Scope und Eskalationswege passiv bleiben kann"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine Rolle im Katalog schafft Sichtbarkeit. Wirksam wird sie, wenn Verantwortliche einen klaren Handlungsweg und die nötigen Rahmenbedingungen besitzen.
    </figcaption>
</figure>

Typische Hindernisse können sein:

### 1. Kein klarer Scope

Der Name eines Owners ist hinterlegt, aber nicht der Umfang der Verantwortung.

Gehört dazu:

- die fachliche Definition?
- die Datenqualität an der Quelle?
- der Zugriff?
- die Priorisierung technischer Änderungen?
- die Verwendung in Reports?
- die Freigabe einer Kennzahl?
- die Kommunikation bei Abweichungen?

Ohne Scope kann dieselbe Rolle gleichzeitig zu weit und zu eng interpretiert werden.

### 2. Verantwortung ohne Zeit und Kapazität

Stewardship wird häufig zusätzlich zu einer bestehenden Fach- oder IT-Rolle übernommen. Das kann sinnvoll sein, weil die fachliche Nähe erhalten bleibt.

Problematisch wird es, wenn Aufgaben zwar zugewiesen werden, aber kein realistischer Zeitrahmen, keine Priorisierung und keine Vertretung existieren.

Dann konkurriert Governance mit operativen Tagesaufgaben – und verliert häufig dort, wo keine unmittelbare Eskalation entsteht.

### 3. Verantwortung ohne Entscheidungsrechte

Eine Person kann für die Qualität einer Definition verantwortlich gemacht werden, ohne eine Änderung genehmigen, ablehnen oder priorisieren zu dürfen.

Dann wird aus Ownership möglicherweise nur Koordination.

Nicht jede Rolle benötigt vollständige Entscheidungsfreiheit. Aber es sollte klar sein:

- Welche Entscheidungen darf die Rolle selbst treffen?
- Welche Entscheidungen benötigen Zustimmung?
- Wer entscheidet bei Konflikten?
- Welche Leitplanken gelten?

### 4. Kein sichtbarer Eskalationspfad

Ein Steward erkennt ein wiederkehrendes Problem, kann es aber nicht dauerhaft lösen.

Vielleicht liegt die Ursache in einem Quellsystem, einem Geschäftsprozess, einer externen Schnittstelle oder einer anderen Domäne.

Ohne Eskalationsweg entstehen leicht lokale Workarounds. Das Problem wird technisch abgefangen, während die fachliche Entscheidung offen bleibt.

### 5. Zu technischer Kontext

Ein technischer Asset-Name, komplexe Lineage und Dutzende Metadatenfelder helfen einem Business Steward nicht automatisch bei der fachlichen Entscheidung.

Technische Details sind notwendig. Sie sollten jedoch nicht die einzige Einstiegsebene sein.

Business User und Stewards benötigen zuerst verständlichen Kontext:

- Was bedeutet das Datenobjekt?
- Für welchen Prozess wird es verwendet?
- Welche Auswirkung hat das Problem?
- Welche Kennzahlen und Entscheidungen sind betroffen?
- Wer muss eingebunden werden?

### 6. Zu aufwendige oder getrennte Workflows

Wenn ein Problem nur über lange Formulare, mehrere Systeme oder schwer auffindbare Governance-Seiten erfasst werden kann, sinkt die Beteiligung.

Das ist kein Argument gegen Kontrolle. Es ist ein Designproblem:

> **Governance sollte den richtigen Weg einfacher machen als den Umweg.**

## Ein Katalog kann Verantwortung abbilden – aber Organisation nicht ersetzen

Governance-Produkte unterstützen Rollen, Berechtigungen, Glossare, Aufgaben und Workflows. Viele Plattformen unterscheiden bereits zwischen Data Ownern, Data Stewards, Domain-Verantwortlichen und technischen Rollen.

Diese Funktionen sind wertvoll. Sie schaffen Struktur, Auffindbarkeit und Nachvollziehbarkeit.

Sie können jedoch nicht allein festlegen:

- wie viel Zeit eine Person für Stewardship erhält,
- welche fachlichen Entscheidungen sie treffen darf,
- welches Team eine Änderung finanziert,
- wie Konflikte zwischen zwei Fachbereichen gelöst werden,
- welche Priorität ein Governance-Problem gegenüber anderen Anforderungen besitzt,
- wer bei Abwesenheit oder Rollenwechsel übernimmt.

Das Tool bildet das Operating Model ab. Es ersetzt nicht dessen organisatorische Vereinbarung.

Die entscheidende Frage ist deshalb nicht:

> *Unterstützt unser Katalog Ownership?*

Sondern:

> **Ist die im Katalog sichtbare Ownership mit einem realen Entscheidungs- und Arbeitsprozess verbunden?**

## Accountability braucht einen Handlungsweg

Der englische Begriff *Accountability* wird häufig mit Verantwortung übersetzt. Im Governance-Kontext ist die Unterscheidung hilfreich:

- **Responsibility** beschreibt, wer eine Aufgabe ausführt oder betreut.
- **Accountability** beschreibt, wer für eine Entscheidung oder ein Ergebnis einsteht.

Eine Person kann beispielsweise für die Pflege eines Glossarbegriffs verantwortlich sein, während der Business Owner für dessen fachliche Freigabe accountable bleibt.

Das verhindert zwei Extreme:

1. Der Steward wird zum alleinigen Verantwortlichen für alle Datenprobleme.
2. Der Owner bleibt nur ein Name für formale Freigaben und nimmt nicht am Entscheidungsprozess teil.

Eine klare Aufteilung kann so aussehen:

| Aktivität | Business Owner | Data Steward | Data / Analytics Team | IT / Platform Team |
| --- | --- | --- | --- | --- |
| Fachliche Bedeutung festlegen | entscheidet / genehmigt | vorbereitet und koordiniert | berät zur Umsetzbarkeit | informiert |
| Qualitätsregel definieren | priorisiert nach Business Impact | koordiniert und dokumentiert | implementiert und testet | unterstützt technisch |
| Datenproblem bewerten | entscheidet bei hohem Business Impact | analysiert Kontext und koordiniert | untersucht Daten und Pipeline | untersucht Systemursachen |
| Änderung an der Quelle | priorisiert fachlich | verfolgt und dokumentiert | unterstützt Datenfolgen | implementiert oder koordiniert Systemänderung |
| Metadaten und Glossar pflegen | bestätigt wesentliche Definitionen | pflegt und kuratiert | ergänzt technische Details | ergänzt Plattformkontext |
| Ergebnis prüfen | bestätigt fachlichen Nutzen | koordiniert Review | validiert Daten | bestätigt technische Umsetzung |

Diese Tabelle ist kein universelles RACI-Modell. Sie zeigt nur, dass Governance-Aufgaben meist mehrere Rollen verbinden.

## Ein einfacher Lifecycle macht Ownership sichtbar

Ownership wird leichter greifbar, wenn Probleme, Entscheidungen und Änderungen einen nachvollziehbaren Status besitzen.

Ein möglicher Lifecycle:

```flow linear vertical
New
In Review
Assigned
In Progress
Resolved
Reviewed
Continuously Improved
```

Dabei sollten nicht nur technische Tickets sichtbar sein. Auch der fachliche Kontext gehört dazu.

| Status | Zentrale Frage |
| --- | --- |
| New | Was wurde gemeldet und welche Auswirkung wird vermutet? |
| In Review | Ist der Kontext vollständig und welche Datenobjekte sind betroffen? |
| Assigned | Wer übernimmt fachliche und technische Verantwortung? |
| In Progress | Welche Maßnahme wird umgesetzt und bis wann? |
| Resolved | Wurde das Problem behoben oder eine Entscheidung getroffen? |
| Reviewed | Ist das Ergebnis fachlich bestätigt und verständlich kommuniziert? |
| Continuously Improved | Welche Erkenntnisse verändern Regeln, Prozesse oder Verantwortlichkeiten? |

Der Lifecycle sollte nicht für jedes kleine Anliegen gleich schwergewichtig sein. Ein einfacher Glossarhinweis benötigt keinen Governance-Ausschuss.

Die Tiefe des Prozesses sollte sich an Risiko, Reichweite und Business Impact orientieren.

## Wie Ownership und Stewardship handlungsfähig werden

Ein wirksames Zielmodell verbindet Assets, Rollen, Feedback, Entscheidungen und kontinuierliche Verbesserung.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-ownership-img2-de.png"
        alt="Zielmodell für wirksame Ownership und Stewardship mit klaren Rollen, Feedback, Priorisierung, Entscheidung, Statusverfolgung und kontinuierlicher Verbesserung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ownership wird handlungsfähig, wenn Scope, Kapazität, Entscheidungsrechte, Eskalation, verständlicher Kontext und nutzbare Werkzeuge zusammenwirken.
    </figcaption>
</figure>

Ein mögliches Vorgehen besteht aus acht Schritten:

1. **Datenobjekt und Geschäftskontext sichtbar machen**  
   Zweck, Verbraucher, Kritikalität und relevante Richtlinien werden verständlich beschrieben.

2. **Business Owner zuweisen**  
   Eine Person oder ein klar definiertes Gremium übernimmt Verantwortung für Wert, Zweck, Priorisierung und wesentliche fachliche Entscheidungen.

3. **Data Steward zuweisen**  
   Der Steward koordiniert Definitionen, Qualität, Metadaten, Feedback und den täglichen Governance-Prozess.

4. **Probleme und Feedback einfach erfassen**  
   Nutzer können Fragen und Abweichungen mit ausreichendem Business-Kontext melden, ohne zuerst die technische Architektur verstehen zu müssen.

5. **Priorisieren und eskalieren**  
   Auswirkung, Risiko, Reichweite und Richtlinien bestimmen den Bearbeitungsweg.

6. **Entscheiden und beheben**  
   Owner und Steward treffen die fachliche Entscheidung oder arbeiten mit Data-, Analytics- und IT-Teams an der Umsetzung.

7. **Status verfolgen und kommunizieren**  
   Betroffene Nutzer erkennen, wer handelt, welcher Status gilt und welche Auswirkungen zu erwarten sind.

8. **Prüfen und verbessern**  
   Ergebnisse werden validiert. Regeln, Prozesse, Rollen oder Schulungsinhalte werden bei Bedarf angepasst.

## Stewardship braucht Enabler – nicht nur Aufgaben

Eine Rolle wird nicht durch eine lange Aufgabenliste wirksam. Sie benötigt passende Rahmenbedingungen.

### Klarer Verantwortungsbereich

Für jedes relevante Datenobjekt sollte erkennbar sein:

- was owned wird,
- was stewarded wird,
- welche Entscheidungen erwartet werden,
- welche Verantwortungen ausdrücklich nicht dazugehören.

### Zeit und realistische Kapazität

Stewardship benötigt einen vereinbarten Arbeitsanteil oder zumindest eine realistische Priorisierung.

Dabei muss nicht jede Organisation Vollzeit-Stewards einsetzen. Häufig ist fachlich verankertes Stewardship sinnvoller. Die Kapazität sollte jedoch zur erwarteten Aufgabenmenge passen.

### Definierte Entscheidungsrechte

Entscheidungsrechte können nach Risiko gestaffelt werden:

- kleine redaktionelle Änderungen durch den Steward,
- fachliche Definitionen mit Freigabe des Owners,
- bereichsübergreifende Konflikte durch ein Governance Council,
- technische Umsetzung durch zuständige Produkt- oder Plattformteams.

### Sichtbarer Eskalationsweg

Eine Eskalation ist kein Scheitern. Sie ist ein geplanter Weg für Themen, die eine einzelne Rolle nicht lösen kann.

### Nutzbare Werkzeuge

Werkzeuge sollten die tägliche Arbeit unterstützen:

- persönliche Aufgabenansicht,
- klare Prioritäten,
- verständliche Formulare,
- einfache Suche,
- Business-Kontext vor technischen Details,
- Benachrichtigungen ohne Informationsüberlastung,
- nachvollziehbarer Status,
- direkte Feedback-Möglichkeit.

### Kontext und Anleitung

Definitionen, Richtlinien, Beispiele und Entscheidungshilfen sollten dort sichtbar sein, wo eine Aufgabe bearbeitet wird.

### Zusammenarbeit

Data Governance verbindet Fachbereich, Data Teams, IT, Security, Privacy und weitere Funktionen. Übergaben sollten explizit sein.

### Regelmäßige Reviews

Owner und Steward ändern sich. Datenprodukte werden ersetzt. Definitionen verlieren ihre Relevanz.

Regelmäßige Reviews halten Rollen, Regeln und Metadaten aktuell.

## Business-Nähe ist kein Komfortmerkmal

Governance wird häufig über technische Objekte aufgebaut, weil Tabellen, Spalten, Pipelines und Reports automatisiert erfasst werden können.

Für viele fachliche Aufgaben reicht dieser Blick nicht aus.

Ein Business Steward benötigt möglicherweise nicht zuerst:

`prod_dwh.conformed_customer_v4.customer_status_cd`

Sondern:

**Customer Status**  
*Zeigt den aktuell gültigen fachlichen Status eines Kunden im aktiven Vertragsprozess.*

Danach können technische Details, Lineage, Qualitätsregeln und betroffene Reports folgen.

Die Reihenfolge beeinflusst die Nutzung.

Ein Governance-System kann technisch vollständig sein und trotzdem wenig Beteiligung erzeugen, wenn seine Nutzer keinen verständlichen Einstieg finden.

Deshalb gilt:

> **Business context should be the entry point; technical metadata should provide the evidence behind it.**

## Nicht jede Governance-Aufgabe gehört in ein zentrales Tool

Ein weiterer möglicher Fehler wäre, jede fachliche Interaktion in ein separates Governance-Portal zu verschieben.

Stewards und Owner arbeiten bereits in Fachanwendungen, Ticket-Systemen, Collaboration-Tools, BI-Plattformen und Produkt-Backlogs.

Ein praktikables Modell kann Governance in bestehende Arbeitsabläufe integrieren:

- Feedback direkt aus einem Report erfassen,
- Data-Quality-Issues mit einem bestehenden Ticket verknüpfen,
- Freigaben über bekannte Benachrichtigungskanäle bereitstellen,
- den Governance-Status im Datenprodukt oder BI-Katalog anzeigen,
- technische Details im Governance-System halten, aber die relevante Aktion dort anbieten, wo Nutzer arbeiten.

Das zentrale Governance-System bleibt der nachvollziehbare Bezugspunkt. Die Interaktion muss jedoch nicht vollständig dort stattfinden.

## Wie lässt sich aktive Ownership messen?

Eine hohe Anzahl zugewiesener Owner beweist noch keine wirksame Governance.

Neben Coverage können operative Kennzahlen betrachtet werden:

| Kennzahl | Mögliche Aussage |
| --- | --- |
| Ownership Coverage | Für wie viele kritische Assets ist ein aktueller Owner sichtbar? |
| Stewardship Coverage | Für wie viele relevante Domains oder Datenprodukte ist ein Steward aktiv zugewiesen? |
| Acceptance Rate | Wurden neue oder geänderte Verantwortlichkeiten bestätigt? |
| Time to Assignment | Wie schnell erhält ein Problem einen fachlichen und technischen Ansprechpartner? |
| Time to Decision | Wie lange dauert eine notwendige fachliche Entscheidung? |
| Issue Aging | Wie viele offene Themen überschreiten die vereinbarte Frist? |
| Escalation Effectiveness | Führen Eskalationen zu einer Entscheidung oder bleiben sie offen? |
| Reopen Rate | Wie häufig werden vermeintlich gelöste Probleme erneut geöffnet? |
| Review Completion | Werden Definitionen, Rollen und kritische Datenprodukte regelmäßig überprüft? |
| User Feedback | Finden Nutzer die richtige Ansprechperson und verstehen sie den Status? |

Auch diese Kennzahlen müssen im Kontext betrachtet werden. Eine niedrige Anzahl von Issues kann gute Qualität bedeuten – oder eine zu hohe Hürde für Feedback.

Das Ziel ist nicht maximale Aktivität. Das Ziel ist nachvollziehbare und wirksame Verantwortung.

## Häufige Anti-Patterns

### Der Owner als reine Freigabeinstanz

Der Owner wird nur kontaktiert, wenn ein Formular genehmigt werden muss. Fachliche Priorisierung und Zielbild bleiben außerhalb des Prozesses.

### Der Steward als universeller Problemlöser

Jede offene Frage landet beim Steward – unabhängig davon, ob sie fachlich, technisch, regulatorisch oder organisatorisch ist.

### Das Governance-Team als Flaschenhals

Ein zentrales Team muss jede Definition und jede Änderung selbst bearbeiten. Die Organisation skaliert nicht mit dem Datenumfang.

### Verantwortlichkeit ohne Bestätigung

Personen werden automatisch zugewiesen, wissen aber nicht, dass sie verantwortlich sind oder welche Erwartungen gelten.

### Technische Vollständigkeit ohne fachliche Nutzbarkeit

Alle Tabellen sind gescannt und Lineage ist vorhanden, aber Nutzer verstehen weder Zweck noch Bedeutung der Daten.

### Ein Prozess für jedes Risiko

Eine kleine Beschreibungsänderung durchläuft denselben Prozess wie eine kritische regulatorische Entscheidung.

## Balance: Governance darf nicht selbst zum Hindernis werden

Mehr Ownership bedeutet nicht automatisch mehr Freigaben.

Ein zu schweres Modell kann neue Engpässe erzeugen:

- jede Änderung benötigt mehrere Genehmigungen,
- lokale Teams warten auf zentrale Entscheidungen,
- Stewards verwalten Aufgaben statt Datenverständnis,
- Business User umgehen Prozesse, weil sie zu langsam sind.

Ein gutes Operating Model nutzt abgestufte Leitplanken:

| Situation | Möglicher Governance-Ansatz |
| --- | --- |
| Korrektur eines Tippfehlers in einer Beschreibung | Steward darf direkt ändern |
| Neue fachliche Definition mit begrenztem Scope | Steward koordiniert, Owner genehmigt |
| Änderung einer unternehmensweit genutzten KPI | formaler Impact Check und Freigabe |
| Kritisches Datenqualitätsproblem | priorisierter Issue-Prozess mit Eskalation |
| Experimentelles Datenprodukt | leichte Governance mit klarer Draft-Kennzeichnung |
| Regulatorisch relevante Änderung | dokumentierte Prüfung mit erforderlichen Kontrollfunktionen |

Governance sollte proportional sein.

## Praktische Leitfragen für Teams

Für jedes kritische Datenprodukt, jede Kennzahl oder Governance-Domain können folgende Fragen helfen:

1. **Ist klar, was der Owner tatsächlich besitzt und entscheidet?**
2. **Ist klar, welche täglichen Aufgaben beim Data Steward liegen?**
3. **Haben beide Rollen ihre Verantwortung bestätigt?**
4. **Sind Zeit, Vertretung und realistische Kapazität berücksichtigt?**
5. **Welche Entscheidungen dürfen direkt getroffen werden?**
6. **Wie sieht der Eskalationsweg bei Konflikten oder Source-Problemen aus?**
7. **Können Business User Probleme und Fragen einfach melden?**
8. **Ist der fachliche Kontext verständlich, bevor technische Details angezeigt werden?**
9. **Sind Status, Priorität und nächste Aktion sichtbar?**
10. **Wer prüft regelmäßig, ob Rollen und Verantwortungen noch aktuell sind?**

Diese Fragen ersetzen kein Governance-Tool. Sie machen sichtbar, welche organisatorischen Verbindungen das Tool abbilden sollte.

## Fazit

Data Ownership und Data Stewardship sind keine fehlenden Konzepte. Sie sind in Governance-Frameworks und Plattformen längst etabliert.

Die mögliche Lücke liegt zwischen **Zuweisung** und **Handlung**.

Ein Name im Katalog schafft Sichtbarkeit. Wirksame Verantwortung benötigt zusätzlich:

```flow linear vertical
klaren Scope
Zeit und Kapazität
Entscheidungsrechte
Eskalationswege
verständlichen Kontext
nutzbare Prozesse
kontinuierliches Review
```

Die zentrale Frage lautet deshalb:

> **Ist Ownership ein aktives Operating Model – oder nur ein Feld im Datenkatalog?**

Vielleicht ist die wichtigste Aufgabe von Governance nicht, immer mehr Verantwortlichkeiten zuzuweisen.

Vielleicht ist sie, Menschen so auszustatten, dass sie Verantwortung tatsächlich übernehmen und gemeinsam in Ergebnisse übersetzen können.

## Weiterführende Ressourcen

- [Microsoft Learn: Data Governance Roles and Permissions in Microsoft Purview](https://learn.microsoft.com/en-us/purview/data-governance-roles-permissions)
- [Microsoft Learn: Data governance with Microsoft Purview](https://learn.microsoft.com/en-us/purview/data-governance-overview)
- [Microsoft Learn: Glossary terms in Microsoft Purview Unified Catalog](https://learn.microsoft.com/en-us/purview/unified-catalog-glossary-terms)
- [Microsoft Learn: Get started with data governance in Microsoft Purview](https://learn.microsoft.com/en-us/purview/data-governance-get-started)
- [AWS Prescriptive Guidance: Roles and responsibilities for a data mesh](https://docs.aws.amazon.com/prescriptive-guidance/latest/strategy-data-mesh/roles-responsibilities.html)
- [AWS Cloud Adoption Framework: Data governance](https://docs.aws.amazon.com/whitepapers/latest/aws-caf-governance-perspective/data-governance.html)
- [Collibra Product Resource Center: About Stewardship](https://productresources.collibra.com/docs/collibra/latest/Content/Stewardship/to_stewardship.htm)
- [Collibra Product Resource Center: Data Catalog workflows](https://productresources.collibra.com/docs/collibra/latest/Content/Catalog/CatalogWorkflows/ref_catalog-workflows.htm)
- [Collibra Product Resource Center: Issue roles](https://productresources.collibra.com/docs/collibra/latest/Content/DataHelpdesk/co_issue-roles.htm)
- [IBM: What is Data Stewardship?](https://www.ibm.com/think/topics/data-stewardship)
- [IBM: What is Data Governance?](https://www.ibm.com/think/topics/data-governance)
