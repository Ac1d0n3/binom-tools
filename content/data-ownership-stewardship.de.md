---
title: Data Ownership & Stewardship
description: Ein praxisnahes Operating Model für klare Verantwortlichkeit, wirksames Stewardship und vertrauenswürdige Daten über Domänen, Plattformen und Datenprodukte hinweg.
category: Data Governance
tags:
  - data-governance
  - data-ownership
  - data-stewardship
  - accountability
  - operating-model
  - data-domains
  - data-products
order: -1
hero: images/playbooks/data-ownership-stewardship-hero.png
---

## Governance beginnt mit Verantwortung

Policies, Kataloge und Qualitätsregeln werden erst dann wirksam, wenn Menschen eindeutig für Entscheidungen verantwortlich sind. Ohne klar benanntes Ownership bleiben wichtige Fragen offen:

- Wer definiert, was ein Datenasset fachlich bedeutet?
- Wer entscheidet, ob die Datenqualität ausreichend ist?
- Wer genehmigt Zugriffe, Klassifikationen oder Ausnahmen?
- Wer priorisiert die Behebung, wenn etwas fehlschlägt?
- Wer trägt das verbleibende fachliche Risiko?

**Data Ownership & Stewardship** bildet das Operating Model hinter diesen Entscheidungen. Ownership schafft Verantwortlichkeit. Stewardship übersetzt diese Verantwortlichkeit in wiederholbare operative Praxis.

> *Governance wird operativ, wenn jedes kritische Datenasset klare Entscheidungsrechte, aktives Stewardship und einen sichtbaren Eskalationsweg besitzt.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/data-ownership-stewardship-de.png"
        alt="Operating Model für Data Ownership und Stewardship mit den Rollen Data Owner, Data Steward, Data Custodian und Data Consumer"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Klare Rollen, Entscheidungsrechte und ein operativer Review-Zyklus machen aus Governance-Richtlinien gelebte Verantwortung.
    </figcaption>
</figure>

## Ownership ist keine technische Administration

Ein häufiges Missverständnis besteht darin, Ownership automatisch dem Team zuzuweisen, das Daten speichert oder verarbeitet. Plattformteams, Datenbankadministration und Data Engineering sind unverzichtbar — technische Kontrolle erzeugt jedoch nicht automatisch fachliche Verantwortung.

Der Fachbereich entscheidet in der Regel:

- was Daten fachlich bedeuten
- welche Nutzung legitim ist
- welches Qualitätsniveau akzeptabel ist
- wie kritisch die Daten sind
- welche Risiken oder Ausnahmen akzeptiert werden können

Technische Teams setzen Kontrollen um und betreiben sie. Fachliches Ownership gibt Richtung, Prioritäten und Verantwortung vor. Gute Governance verbindet beide Seiten, anstatt Verantwortung von der einen auf die andere zu verschieben.

## Die Kernrollen

Die konkreten Jobtitel unterscheiden sich je nach Organisation. Wichtiger als die Bezeichnung sind die tatsächlichen Verantwortlichkeiten.

| Rolle | Primäre Verantwortung | Typische Aufgaben |
| --- | --- | --- |
| **Data Owner** | Geschäftswert, Risiko und Entscheidungen | Genehmigt Definitionen, Policies, Prioritäten, Zugriffsprinzipien und wesentliche Ausnahmen |
| **Data Steward** | Operative Governance und Nutzbarkeit der Daten | Pflegt Definitionen und Metadaten, koordiniert Qualität, unterstützt Klassifikation und löst Probleme |
| **Data Custodian** | Technische Umsetzung und Betrieb | Betreibt Plattformen, Pipelines und Speicher; setzt Zugriffs-, Schutz-, Aufbewahrungs- und technische Kontrollen um |
| **Data Consumer** | Verantwortungsvolle und regelkonforme Nutzung | Nutzt Daten gemäß Zweck und Policy, meldet Probleme und hält sich an freigegebene Definitionen und Kontrollen |

Je nach Größe der Organisation kann eine Person mehrere Rollen übernehmen. Das Modell sollte praktikabel bleiben. Ziel ist nicht, zusätzliche Titel zu schaffen, sondern **Verantwortung, Ausführung und Eskalation eindeutig zu machen**.

## Data Owner

Der Data Owner trägt aus fachlicher Sicht die Verantwortung für eine Datendomäne, ein Datenprodukt, ein kritisches Datenasset oder eine geschäftliche Kennzahl.

Typische Aufgaben sind:

- fachliche Definitionen und vorgesehene Nutzung genehmigen
- Prioritäten für Qualitätsverbesserungen und Problembehebung setzen
- Kritikalität und Risikostufe festlegen oder genehmigen
- Governance-Policies und Ausnahmen im eigenen Verantwortungsbereich freigeben
- akzeptable Qualitätsschwellen bestimmen
- notwendige Änderungen über Teamgrenzen hinweg unterstützen
- Konflikte entscheiden, die operativ nicht gelöst werden können
- verbleibende Geschäftsrisiken akzeptieren oder eskalieren

Ein Data Owner benötigt ausreichend Mandat, um Entscheidungen zu treffen. Ownership einer Person ohne Entscheidungsbefugnis, Kapazität oder fachlichen Kontext zuzuweisen, erzeugt nur Verantwortung auf dem Papier.

## Data Steward

Der Data Steward ist die operative Verbindung zwischen fachlicher Bedeutung, Metadaten, Qualität und technischer Umsetzung.

Typische Aufgaben sind:

- fachliche Definitionen, Beschreibungen und Glossarbegriffe pflegen
- sicherstellen, dass Ownership- und Domänenzuordnungen aktuell bleiben
- Klassifikationen wie PII, Vertraulichkeit oder Kritikalität koordinieren
- Data-Quality-Regeln gemeinsam mit Fachbereich und Technik definieren und überprüfen
- Probleme untersuchen und die Behebung koordinieren
- Entscheidungen, Ausnahmen und bekannte Einschränkungen dokumentieren
- Anwender beim Verstehen und richtigen Nutzen governter Daten unterstützen
- Entscheidungen vorbereiten, die eine Freigabe durch den Data Owner benötigen

Stewardship sollte nicht auf Katalogpflege reduziert werden. Ein guter Steward begleitet den vollständigen operativen Zyklus: **definieren, umsetzen, überwachen, lösen und verbessern**.

## Data Custodian

Der Data Custodian ist für die technische Umgebung verantwortlich, in der governte Daten gespeichert, verarbeitet und geschützt werden.

Typische Aufgaben sind:

- Datenplattformen, Pipelines, Speicher und Backups betreiben
- genehmigte Zugriffsregeln und Sicherheitskontrollen umsetzen
- Masking, Verschlüsselung, Aufbewahrung oder Löschmechanismen anwenden
- technische Metadaten und Lineage-Integrationen pflegen
- Verfügbarkeit, Performance und technische Fehler überwachen
- kontrollierte Änderungen und Releases unterstützen
- Nachweise aus Logs, Jobs und technischen Kontrollen bereitstellen

Ein Custodian kann ein Plattformteam, Data-Engineering-Team, Anwendungsteam oder Managed Service Provider sein. Die Rolle setzt technische Kontrollen um, sollte aber nicht standardmäßig ungeklärte fachliche Entscheidungen übernehmen müssen.

## Data Consumer

Auch Data Consumer sind Teil der Governance. Vertrauenswürdige Daten hängen nicht nur von Produzenten und kontrollierenden Rollen ab, sondern ebenso von verantwortungsvoller Nutzung.

Consumer sollten:

- Daten nur für genehmigte Zwecke verwenden
- Zugriffs-, Datenschutz- und Sicherheitsanforderungen einhalten
- freigegebene Definitionen für governte KPIs und Kennzahlen verwenden
- Qualitätsprobleme, unklare Bedeutungen und unerwartete Ergebnisse melden
- unkontrollierte Kopien oder Schatten-Definitionen vermeiden
- Feedback zu Nutzbarkeit, Relevanz und Vertrauen geben

Ein reifes Governance-Modell ermöglicht es Nutzern, Owner zu finden, Daten zu verstehen und Probleme zu melden — ohne Organigramme oder separate Tabellen durchsuchen zu müssen.

## Das Ownership Operating Model

Ownership sollte als wiederholbarer Prozess umgesetzt werden und nicht als einmalige Zuordnungsübung.

```text
Kritische Datenassets identifizieren
        ↓
Owner und Stewards zuweisen
        ↓
Standards und Entscheidungsrechte definieren
        ↓
Qualität, Probleme und Entscheidungen überwachen
        ↓
Überprüfen, verbessern und verankern
```

### 1. Kritische Datenassets identifizieren

Nicht jede Tabelle benötigt dieselbe Governance-Intensität. Beginnt mit Assets, die wesentlichen Geschäftswert oder ein relevantes Risiko erzeugen, zum Beispiel:

- regulatorische oder finanzielle Berichtsdaten
- Kunden-, Mitarbeiter- oder Lieferantenstammdaten
- PII und andere sensible Informationen
- Daten für Management- und Executive-KPIs
- kritische Datenprodukte und Analysemodelle
- gemeinsam genutzte Referenzdaten über mehrere Domänen hinweg

Diese Priorisierung verhindert, dass Governance zu einer endlosen Inventarisierungsübung wird.

### 2. Owner und Stewards zuweisen

Ownership sollte ausdrücklich benannt, auffindbar und von den zugewiesenen Personen akzeptiert sein. Ein Name in einer Tabelle reicht nicht aus, wenn die Person nichts von der Zuordnung weiß oder keine Entscheidungsbefugnis besitzt.

Mindestens festgelegt werden sollten:

- verantwortete Domäne, Produkt, Asset oder Kennzahl
- verantwortlicher Data Owner
- operativer Data Steward
- zuständiges technisches Team oder Custodian
- Eskalationsweg und Vertretung
- Review-Datum oder Review-Zyklus

### 3. Standards und Entscheidungsrechte definieren

Rollen werden erst dann nützlich, wenn klar ist, welche Entscheidungen bei wem liegen.

| Entscheidung | Data Owner | Data Steward | Data Custodian |
| --- | --- | --- | --- |
| Fachliche Definition freigeben | Verantwortlich | Bereitet vor und pflegt | Wird konsultiert |
| Data-Quality-Regel definieren | Genehmigt kritische Regeln | Koordiniert und pflegt | Implementiert und überwacht |
| Wesentliche Ausnahme genehmigen | Verantwortlich | Bewertet und dokumentiert | Liefert technische Auswirkungen |
| Zugriffskontrolle umsetzen | Genehmigt fachliches Prinzip | Prüft den Kontext | Verantwortlich für Umsetzung |
| Technischen Incident lösen | Wird informiert oder eskaliert | Koordiniert fachliche Auswirkungen | Verantwortlich |
| Problembehebung priorisieren | Verantwortlich | Empfiehlt und verfolgt | Schätzt und setzt um |

Die Tabelle ist nur ein Beispiel. Entscheidend ist, Entscheidungsrechte so zu dokumentieren, dass sie zum tatsächlichen Operating Model passen.

### 4. Qualität, Probleme und Entscheidungen überwachen

Ownership wird sichtbar, wenn es mit operativen Signalen verbunden ist:

- Data-Quality-Ergebnisse
- fehlgeschlagene Tests und Incidents
- offene Stewardship-Aufgaben
- Ergebnisse von Zugriffsüberprüfungen
- überfällige Metadaten-Reviews
- Policy-Ausnahmen
- Beschwerden oder Nutzerfeedback
- Änderungen mit wesentlichen Auswirkungen auf nachgelagerte Systeme

Dashboards sollten nicht nur anzeigen, dass ein Problem existiert. Sie sollten auch zeigen, wer die Entscheidung verantwortet und was der nächste Schritt ist.

### 5. Überprüfen, verbessern und verankern

Ownership verändert sich, wenn Organisationen, Produkte und Systeme verändert werden. Zuordnungen sollten regelmäßig und nach wesentlichen Ereignissen überprüft werden, zum Beispiel:

- organisatorische Umstrukturierung
- Einführung oder Ablösung eines Quellsystems
- Start eines neuen Datenprodukts
- Übergang fachlicher Verantwortung
- wesentliche regulatorische oder Policy-Änderung
- wiederholte Qualitäts- oder Kontrollfehler

Governance muss mit der tatsächlichen Organisation übereinstimmen — nicht mit dem Operating Model des Vorjahres.

## Den richtigen Ownership-Scope wählen

Ownership kann auf unterschiedlichen Ebenen zugewiesen werden. Der passende Scope hängt davon ab, wie eine Organisation Daten erzeugt und nutzt.

| Ownership-Scope | Geeignet, wenn | Typisches Risiko |
| --- | --- | --- |
| **Datendomäne** | Geschäftsfähigkeiten stabil sind und systemübergreifende Verantwortung erforderlich ist | Scope kann für operative Entscheidungen zu groß werden |
| **Datenprodukt** | Teams Daten als Produkt mit klaren Nutzern und Service-Erwartungen betreiben | Gemeinsam genutzte Quelldaten können überlappendes Ownership erzeugen |
| **Quellsystem** | Operative Systeme eine starke Anwendungsverantwortung besitzen | Nachgelagerte Bedeutung und Wiederverwendung können unklar bleiben |
| **Datenasset** | Kritische Tabellen, Modelle oder Datasets präzise Verantwortung benötigen | Es können zu viele Einzelzuordnungen entstehen |
| **KPI oder Kennzahl** | Fachliche Definitionen und Berechnungen explizite Freigaben benötigen | Abhängigkeiten zu zugrunde liegenden Daten können übersehen werden |

Ein mehrstufiges Modell ist häufig praktikabel: Ein Domain Owner trägt die übergreifende Verantwortung, während Product- oder Asset-Owner einen spezifischeren Scope übernehmen.

## Accountability ist nicht dasselbe wie Responsibility

Die Unterscheidung ist wichtig:

- **Accountability** bedeutet, für das Ergebnis einzustehen und die Befugnis zu besitzen, Entscheidungen zu treffen.
- **Responsibility** bedeutet, die Arbeit auszuführen, die für dieses Ergebnis erforderlich ist.

Ein Data Owner kann für die Qualität von Kundendaten accountable sein, ohne persönlich eine Pipeline zu reparieren. Der Data Steward koordiniert möglicherweise das Problem, während Data Engineering die technische Korrektur umsetzt.

Diese Trennung verhindert zwei typische Probleme:

1. Fachbereiche delegieren die gesamte Governance an die Technik
2. Technikteams werden für Definitionen und Risikoentscheidungen verantwortlich gemacht, die sie nicht besitzen

RACI-Matrizen können unterstützen, sollten aber das Operating Model nicht anstelle klarer Ownership-Regeln ersetzen.

## Stewardship macht Policies operativ

Policies beschreiben erwartetes Verhalten. Stewardship sorgt dafür, dass diese Erwartungen in operative Aktivitäten übersetzt werden.

Zum Beispiel:

```text
Policy:
Kritische Kundendaten müssen vollständig und geschützt sein.

Stewardship:
Kritische Felder definieren
        ↓
Fachliche Bedeutung dokumentieren
        ↓
PII klassifizieren
        ↓
Qualitätsschwellen festlegen
        ↓
Technische Kontrollen koordinieren
        ↓
Ergebnisse überwachen und Probleme lösen
```

Deshalb verbindet Stewardship häufig mehrere Governance-Säulen gleichzeitig. Ein Steward arbeitet mit Metadaten, Data Quality, Datenschutz, Zugriff, Lebenszyklus und KPI-Definitionen, ohne jede technische Umsetzung selbst zu besitzen.

## Ownership sollte in Metadaten sichtbar sein

Ownership verliert an Wert, wenn es nur in einem separaten Dokument existiert. Es sollte direkt mit govern­ten Assets verknüpft und über die Werkzeuge sichtbar werden, die Nutzer ohnehin verwenden.

Nützliche Ownership-Metadaten können enthalten:

- fachliche Domäne
- Data Owner
- Data Steward
- technischer Custodian oder verantwortliches Team
- fachliche Definition
- Kritikalität
- Sensitivitätsklassifikation
- Qualitätsstatus
- Review-Datum
- Eskalationskontakt
- genehmigte Nutzung oder bekannte Einschränkungen

Ein vereinfachtes Metadata-as-Code-Beispiel könnte so aussehen:

```yaml
data_product:
  name: customer_360
  domain: customer
  owner: customer_operations
  steward: customer_data_office
  custodian: data_platform_team
  criticality: high
  review_cycle: quarterly
  classifications:
    - pii
    - confidential
```

Das konkrete Format ist nicht entscheidend. Wichtig ist, dass Ownership-Metadaten versioniert, auffindbar und mit operativen Datenassets verbunden sind — anstatt als isolierte Liste gepflegt zu werden.

## Verbindung zu den anderen Governance-Säulen

Data Ownership & Stewardship bildet die Grundlage für die weiteren Säulen des Governance-Modells.

| Governance-Bereich | Beitrag von Ownership |
| --- | --- |
| **Metadata, Catalog & Lineage** | Genehmigt Bedeutung, pflegt Kontext und macht Verantwortlichkeit sichtbar |
| **PII & Privacy Governance** | Bestätigt Klassifikation, Schutzanforderungen und genehmigte Nutzung |
| **DSDR Governance** | Liefert verantwortliche Entscheidungen zu Scope, Ausnahmen und fachlichen Auswirkungen |
| **Data Quality Governance** | Definiert erforderliche Qualität, genehmigt Schwellenwerte und priorisiert Problembehebung |
| **KPI & Metric Governance** | Genehmigt Definitionen, Berechnungslogik und maßgebliche Versionen |
| **Access & Security Governance** | Genehmigt fachliche Zugriffsprinzipien und bewertet Ausnahmen |
| **Data Lifecycle & Retention** | Bestätigt Aufbewahrungsbedarf, Geschäftswert und Auswirkungen einer Löschung |

Ohne Ownership werden diese Aktivitäten häufig zu voneinander getrennten technischen Aufgaben. Mit Ownership werden daraus governte Geschäftsentscheidungen, die durch Technologie unterstützt werden.

## Zentral, föderiert oder domänenbasiert?

Es gibt kein einzelnes Organisationsmodell, das zu jedem Unternehmen passt.

| Modell | Merkmale | Stärke | Risiko |
| --- | --- | --- | --- |
| **Zentral** | Ein zentrales Governance-Team definiert und betreibt den Großteil der Standards | Konsistenz und Kontrolle | Begrenzte Domänenkapazität und langsamere lokale Entscheidungen |
| **Föderiert** | Zentrale Standards mit verteilten Ownern und Stewards | Balance aus Konsistenz und fachlicher Nähe | Benötigt klare Koordination und gemeinsame Werkzeuge |
| **Domänenbasiert** | Ownership ist in Fach- oder Datenproduktdomänen verankert | Starker Kontext und klare Verantwortung | Standards können ohne zentrale Leitplanken auseinanderlaufen |

Für viele größere Organisationen ist ein föderiertes Modell praktikabel:

- eine zentrale Governance-Funktion definiert Prinzipien, Rollenmuster und Mindestkontrollen
- Domänen benennen Owner und Stewards
- Plattformteams stellen wiederverwendbare technische Funktionen bereit
- Governance-Gremien lösen domänenübergreifende Konflikte und gemeinsame Definitionen

Das Modell sollte Übergaben minimieren und gleichzeitig dort unternehmensweite Konsistenz sichern, wo sie erforderlich ist.

## Ein praktikabler Minimum-Viable-Ownership-Eintrag

Eine Governance-Initiative muss nicht mit einem komplexen Workflow starten. Für jedes kritische Asset genügt zunächst ein kleiner, aber vollständiger Eintrag:

| Feld | Zweck |
| --- | --- |
| **Asset oder Produkt** | Definiert den govern­ten Scope |
| **Fachliche Domäne** | Verbindet das Asset mit dem organisatorischen Kontext |
| **Data Owner** | Benennt die verantwortliche Entscheidungsrolle |
| **Data Steward** | Benennt den operativen Governance-Kontakt |
| **Technischer Custodian** | Identifiziert das umsetzende und betreibende Team |
| **Kritikalität** | Bestimmt Governance-Intensität und Reaktionspriorität |
| **Wichtige Policies** | Verknüpft Qualitäts-, Datenschutz-, Zugriffs- oder Aufbewahrungsanforderungen |
| **Letzter Review** | Zeigt, ob die Zuordnung aktuell ist |
| **Eskalationsweg** | Verhindert, dass ungelöste Probleme liegen bleiben |

Sobald diese Grundlage zuverlässig ist, können Workflows, automatisierte Prüfungen und zusätzliche Metadaten schrittweise ergänzt werden.

## Messen, ob Ownership funktioniert

Ziel ist nicht, möglichst viele Owner zuzuweisen. Ziel ist, Entscheidungen, Reaktionszeiten und Vertrauen zu verbessern.

Nützliche Kennzahlen sind zum Beispiel:

- Anteil kritischer Assets mit akzeptiertem Owner und Steward
- Anteil der Ownership-Einträge, die innerhalb des festgelegten Zyklus überprüft wurden
- durchschnittliche Zeit, um ein Problem dem richtigen Owner zuzuordnen
- durchschnittliche Entscheidungszeit bei kritischen Datenproblemen
- Anteil hoch priorisierter Qualitätsprobleme mit benanntem Remediation Owner
- Anzahl und Alter offener Policy-Ausnahmen
- Anteil governter KPIs mit freigegebener Definition und Owner
- Nutzerfeedback dazu, ob Verantwortlichkeit leicht auffindbar ist

Kennzahlen sollten zeigen, ob das Operating Model praktisch funktioniert — nicht nur, ob Governance-Felder ausgefüllt sind.

## Ein einfaches Reifegradmodell

| Reifegrad | Typischer Zustand |
| --- | --- |
| **Unklar** | Verantwortung hängt von persönlichem Wissen und informellen Netzwerken ab |
| **Zugeordnet** | Owner und Stewards sind für ausgewählte kritische Assets benannt |
| **Operativ** | Entscheidungsrechte, Problemprozesse und Review-Zyklen sind definiert |
| **Integriert** | Ownership ist mit Metadaten, Qualität, Datenschutz, Zugriff und Workflows verbunden |
| **Messbar** | Reaktionszeiten, Qualität und Wirksamkeit des Stewardships werden überwacht |
| **Verankert** | Ownership ist Teil von Produktentwicklung, Change Management und täglichem Datenbetrieb |

Fortschritt erfordert nicht, jedem technischen Objekt einen Owner zuzuweisen. Entscheidend ist verlässliche Verantwortung für die Daten, die den größten Wert oder das höchste Risiko erzeugen.

## Typische Anti-Patterns

Ownership-Programme scheitern häufig aus vorhersehbaren Gründen:

- Owner werden ohne Zustimmung zugewiesen
- Personen ohne Entscheidungsbefugnis werden ausgewählt
- der Data Owner wird als Person behandelt, die jede Aufgabe selbst erledigen muss
- der Data Steward wird auf reine Dokumentationspflege reduziert
- Ownership wird nur auf Systemebene vergeben, während nachgelagerte Datenprodukte und KPIs unberücksichtigt bleiben
- Ownership wird in einer Tabelle gepflegt, die nicht mit Metadaten und operativen Prozessen verbunden ist
- zu viele Rollenvarianten werden ohne klare Entscheidungsrechte geschaffen
- Vollständigkeit der Zuordnungen wird gemessen, nicht deren Wirksamkeit
- Ownership bleibt nach Reorganisationen unverändert
- jedes operative Problem wird an das Senior Management eskaliert

Das Modell sollte einfach genug für die tägliche Nutzung und stark genug zur Lösung realer Entscheidungen sein.

## Von benannten Rollen zu operativer Governance

Eine praktische Umsetzung kann diesem Pfad folgen:

```text
Kritisches Datenasset
        ↓
Owner + Steward + Custodian
        ↓
Definitionen + Policies + Entscheidungsrechte
        ↓
Metadaten + Qualitätsregeln + Kontrollen
        ↓
Monitoring + Issue Workflow + Nachweise
        ↓
Review + Verbesserung
```

Ziel ist nicht zusätzliche Bürokratie. Ziel ist, Verantwortung genau dort sichtbar zu machen, wo Daten definiert, verändert, geschützt, gemessen und genutzt werden.

## Das Ergebnis

Wirksames Data Ownership & Stewardship schafft:

- **Verantwortlichkeit** — Entscheidungsrechte und Eskalationswege sind klar
- **Transparenz** — Ownership und Zuständigkeiten sind dort sichtbar, wo Daten genutzt werden
- **Qualität** — Probleme werden mit fachlichem Kontext priorisiert und gelöst
- **Compliance** — Policies, Klassifikationen und Ausnahmen besitzen eine verantwortliche Freigabe
- **Business Value** — vertrauenswürdige Daten ermöglichen schnellere und konsistentere Entscheidungen

Ownership liefert das Mandat. Stewardship schafft die operative Disziplin. Technische Teams stellen Umsetzung und Nachweise bereit. Data Consumer sorgen für verantwortungsvolle Nutzung und Feedback.

Gemeinsam machen diese Rollen aus Governance kein theoretisches Framework, sondern ein funktionierendes Operating Model.

Verwandte Übersicht: [Die 8 Säulen der Data Governance](/playbooks/eight-pillars).
