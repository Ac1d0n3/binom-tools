---
title: "Welche Salesforce-Tabellen für Analytics laden — und welche skippen"
description: "Welche Quelltabellen laden — und welche nicht — aus Grain und Risiko."
author: Thomas Lindackers
tags:
  - Salesforce
  - Source Scope
  - Analytics Engineering
  - Data Governance
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
publishedAt: 2026-07-28
category: Data Governance
order: -1
hero: images/playbooks/salesforce-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Lade-Entscheidungen für Quellsysteme
seriesPart: 1
---

Eine Salesforce-Organisation kann Hunderte Standardobjekte, Custom Objects, Objekte aus Managed Packages und technische Hilfsobjekte bereitstellen. Dieser Katalog ist ein Inventar, aber noch keine Analytics-Anforderung. In den Ladeumfang gehören nur die Objekte und Felder, die eine definierte Business-Frage auf einem definierten Grain beantworten — mit expliziten Entscheidungen zu Beziehungen, Historie, personenbezogenen Daten, Freitext und Löschverhalten.

Das Ziel ist nicht, die eine richtige Salesforce-Objektliste zu finden. Das Ziel ist ein prüfbarer **Source Scope** für ein konkretes Analytics-Produkt, der dokumentiert, warum jedes Objekt eingeschlossen, zurückgestellt oder ausgeschlossen wird.

## Problem

Ein objektgetriebener Ansatz beginnt häufig mit einem Connector, einem Exportkatalog oder einem Metadaten-Scan. Weil Objekte verfügbar sind, werden sie „für später“ ausgewählt. So entsteht schnell Datenvolumen, aber nicht automatisch verwertbare Information.

Die daraus entstehenden Probleme sind strukturell:

- Niemand kann erklären, welche analytische Frage ein Objekt unterstützt.
- Tabellen mit unterschiedlichen Grains werden ohne definiertes Beziehungsmodell verbunden.
- Aktuelle Datensatzwerte werden behandelt, als würden sie historische Zustände abbilden.
- Notizen, Aktivitäten, Dateien und Beschreibungen bringen unkontrollierten Freitext und personenbezogene Daten in die Plattform.
- Custom Objects werden ignoriert, obwohl in ihnen möglicherweise der eigentliche Geschäftsprozess umgesetzt ist.
- Standardnamen werden vertraut, obwohl ihre konfigurierte Business-Bedeutung abweicht.
- Gelöschte oder zusammengeführte Datensätze verschwinden ohne explizite Downstream-Regel aus Kennzahlen.
- Jeder spätere Consumer erbt eine große, schlecht governte Source-Schicht.

![Mit Business-Fragen starten, nicht mit der Salesforce-Objektliste](images/playbooks/salesforce-tables-for-analytics-img1-de.png)

### Der Objektkatalog definiert nicht das Analytics-Produkt

Dieselbe Salesforce-Organisation kann für unterschiedliche Analytics-Produkte unterschiedliche Source Scopes benötigen:

- Ein Produkt für **Pipeline nach Stage und Owner** benötigt möglicherweise `Opportunity`, die relevanten Account- und Owner-Referenzen, governte Stage-Semantik und ausgewählte Datumsfelder. Produktpositionen sind nicht erforderlich, solange die Kennzahl nicht auf Positions- oder Produkt-Grain liegt.
- Ein Produkt für **Bookings nach Produkt** benötigt häufig die kommerzielle Beziehung von `Opportunity` über `OpportunityLineItem` und `PricebookEntry` zu `Product2` sowie die organisationsspezifische Definition von gebucht, gewonnen, storniert und wirksamem Datum.
- Ein Produkt für **Lead Conversion** kann `Lead` und die bei der Konvertierung erzeugten Datensätze oder Schlüssel benötigen. `Campaign` und `CampaignMember` bleiben konditional, weil Attribution vom konfigurierten Prozess und nicht allein von der Objektverfügbarkeit abhängt.
- Ein Produkt für **Account-Aktivität** benötigt `Task`, `Event` oder eine andere Aktivitätsquelle nur dann, wenn Entscheidung, Ereignistyp, Akteur, Zeitraum und Ziel-Grain definiert sind.

Diese Beispiele sind Muster. Sie sind keine universelle Salesforce-Ladeliste.

### Drei Zeitbedeutungen müssen getrennt werden

Salesforce-Quelldaten können unterschiedliche zeitliche Perspektiven unterstützen, diese dürfen jedoch nicht implizit vermischt werden:

1. **Current State** fragt, wie ein Datensatz jetzt aussieht.
2. **Event- oder Feldhistorie** fragt, welche aufgezeichnete Änderung wann stattgefunden hat.
3. **Snapshot-Historie** fragt, wie der vollständige relevante Zustand zu wiederkehrenden Zeitpunkten aussah.

Ein veränderbarer Current-State-Datensatz kann nicht jeden früheren Zustand rekonstruieren. Ein History-Objekt oder eine Audit-Quelle hilft nur für die Felder und Zeiträume, die tatsächlich aufgezeichnet wurden. Ein Snapshot ist eine eigenständige analytische Designentscheidung und muss in der Datenplattform bewusst erzeugt und aufbewahrt werden.

### Freitext ist eine eigene Scope-Entscheidung

Felder und Objekte mit Notizen, Beschreibungen, E-Mails, Kommentaren, Aktivitäten oder Dateien dürfen nicht allein deshalb in den Scope gelangen, weil sie mit einem benötigten Business-Datensatz verknüpft sind. `Task`, `Event`, `EmailMessage`, `ContentNote`, `ContentDocument`, `ContentVersion`, `ContentDocumentLink` und ältere Attachment-Strukturen können große Volumina, vertrauliche Inhalte, personenbezogene Daten und Binärdateien einbringen.

Für jede Freitext- oder Datei-Quelle braucht es einen konkreten Use Case, eine Feld-Allowlist, Zugriffsregeln, Retention und eine dokumentierte Entscheidung, ob Inhalt, Metadaten oder lediglich ein abgeleitetes Signal benötigt werden.

## Decision

Leite den Salesforce-Ladeumfang aus **Geschäftsprozess und Ziel-Grain** ab, nicht aus dem vollständigen Objektkatalog. Jedes eingeschlossene Objekt benötigt einen analytischen Zweck. Jedes zurückgestellte oder ausgeschlossene Objekt benötigt eine Begründung und einen Review Trigger.

Nutze dafür die folgende Entscheidungsreihenfolge.

### 1. Die analytische Entscheidung formulieren

Beschreibe die Frage als Entscheidung, nicht als Dashboard-Titel.

Schwach:

> Ein Salesforce Sales Dashboard bauen.

Stärker:

> Die Vertriebsleitung entscheidet, wo sie im laufenden Quartal eingreifen muss, indem sie offene Pipeline, Stage Age, erwartetes Abschlussdatum und verantwortlichen Owner auf Opportunity-Grain vergleicht.

Die stärkere Formulierung benennt Nutzer, Handlung, Zeitraum und analytischen Grain. Gleichzeitig zeigt sie, dass Produktpositionen, Aktivitäten, Notizen oder Cases nicht automatisch erforderlich sind.

### 2. Prozess, Event und Ziel-Grain festlegen

Vor der Objektauswahl müssen feststehen:

- der beobachtete Geschäftsprozess;
- das Ereignis, das einen Fakt erzeugt oder verändert;
- der Zeilen-Grain jeder vorgesehenen Faktentabelle;
- die Dimensionen, die diesen Fakt interpretierbar machen;
- die Zeitbedeutung: Current State, Event-Historie oder Snapshot;
- die Kennzahlen und Entscheidungen, die das Modell unterstützen muss.

Mögliche Ziel-Grains sind eine Zeile pro Opportunity, Opportunity-Position, Lead-Conversion-Event, Case-Status-Event oder Account-Tag. „Eine Zeile pro Salesforce-Datensatz“ ist kein ausreichender analytischer Grain, weil damit lediglich die Quellstruktur wiederholt wird.

### 3. Die erforderlichen Business-Beziehungen verfolgen

Beginne mit dem zentralen Fakt und ergänze nur Beziehungen, die benötigte Bedeutung liefern.

Für einen produktbezogenen Sales-Fakt ist ein häufiges Muster:

```text
Account
  └─ Opportunity
       └─ OpportunityLineItem
            └─ PricebookEntry
                 └─ Product2
```

Diese Beziehung ist ein Beispiel. Deine Organisation kann stattdessen Custom Products, Subscriptions, Quotes, Orders, Revenue Schedules, Objekte aus Managed Packages oder eigene Junction Objects verwenden.

Für jede Beziehung werden dokumentiert:

- analytischer Zweck;
- Kardinalität und Optionalität;
- Auswirkung auf den Ziel-Grain;
- Business Key und Salesforce-Identifier;
- aktuelle gegenüber historischer Bedeutung;
- Umgang mit Duplikaten und verwaisten Datensätzen;
- PII-Klassifikation;
- ob die Beziehung autoritativ oder lediglich technisch bequem ist.

Lade eine Beziehungstabelle nicht nur deshalb, weil sie existiert. Ein Junction Object ohne Ziel-Grain und Allokationsregel kann aus einem Business-Ereignis doppelte Fakten oder ein ungelöstes Many-to-many-Modell erzeugen.

![Die Business-Beziehung laden, nicht jede Tabelle](images/playbooks/salesforce-tables-for-analytics-img3-de.png)

### 4. Jedes Objekt klassifizieren

Nutze vier Entscheidungszustände statt einer binären Laden-oder-Ignorieren-Auswahl.

#### Must-have

Das Objekt oder die Exporttabelle ist erforderlich, um den ausgewählten Fakt, eine Dimension, einen Filter, eine Kontrolle oder eine Abstimmung auf dem vereinbarten Grain zu erzeugen.

Mögliche Muster für einen produktbezogenen Pipeline-Use-Case sind:

- `Opportunity` als Quelle des kommerziellen Fakts;
- `OpportunityLineItem`, wenn der Ziel-Grain eine Position oder ein Produkt ist;
- `Account`, wenn Account-Merkmale für die Segmentierung benötigt werden;
- `PricebookEntry` und `Product2`, wenn Produkt- und Pricebook-Semantik erforderlich sind;
- `User` oder eine andere governte Owner-Referenz, wenn Verantwortlichkeit Teil der Entscheidung ist;
- ausgewählte Datums-, Währungs- oder Territory-Attribute, wenn sie die Kennzahl materiell beeinflussen.

#### Conditional

Das Objekt wird nur geladen, wenn eine benannte Anforderung freigegeben wurde.

Typische Beispiele sind:

- `Lead` für Lead-Funnel- oder Conversion-Analysen;
- `Campaign` und `CampaignMember` für ein definiertes Attributionsmodell;
- `Case` für Service-Ergebnisse oder die Interaktion zwischen Sales und Service;
- `Task` und `Event` für eine explizite Aktivitätskennzahl;
- die jeweils passenden History-Objekte oder Audit-Quellen für ausgewählte Änderungen;
- Custom Objects und Objekte aus Managed Packages, die den tatsächlichen Geschäftsprozess umsetzen;
- Contact Roles oder andere Beziehungsobjekte, wenn das Zielprodukt sie benötigt.

#### Deferred

Das Objekt kann später relevant werden, aber das aktuelle Produkt rechtfertigt Kosten oder Risiko noch nicht. Der Source Scope dokumentiert, welche Evidenz den Wechsel in den freigegebenen Umfang auslösen würde — beispielsweise eine finanzierte Kennzahl, ein bestätigter Owner, eine Retention-Entscheidung oder ein abgestimmtes Beziehungsmodell.

#### Excluded

Das Objekt hat keinen freigegebenen analytischen Zweck oder verletzt die aktuelle Scope-Grenze. Typische Beispiele sind ungenutzte Feature-Objekte, Setup- und UI-Hilfsobjekte, doppelte Exportstrukturen, uneingeschränkte Notizen oder Attachments sowie technische Logs ohne operativen Analytics-Use-Case.

![Salesforce-Objekte klassifizieren: Must-have, Conditional oder Skip](images/playbooks/salesforce-tables-for-analytics-img2-de.png)

### 5. Feldbasierte Controls anwenden

Die Aufnahme eines Objekts autorisiert nicht automatisch alle Felder. Erstelle eine Feld-Allowlist auf Basis von:

- Zweck als Kennzahl oder Dimension;
- Business-Definition;
- Join- oder Abstimmungsbedarf;
- Klassifikation personenbezogener Daten;
- Freitext- und Secret-Risiko;
- benötigter Präzision und Formatierung;
- Freshness- und Änderungsverhalten;
- Retention- und Löschanforderungen.

Felder wie Namen, E-Mail-Adressen, Telefonnummern, Anschriften, freie Beschreibungen, Kommentare und nutzerdefinierte Betreffzeilen sind separate Risikoentscheidungen. Benötigt der Use Case nur Kategorie, Anzahl, Flag oder Alter, sollte dieses kontrollierte Signal geladen oder abgeleitet werden, statt unbeschränkten Inhalt zu kopieren.

### 6. Löschung, Historie und Snapshot-Verhalten entscheiden

Salesforce-Datensätze können gelöscht, zusammengeführt oder soft-deleted werden. Ein Source Scope muss deshalb festlegen, ob Downstream Analytics einen solchen Datensatz:

- entfernt;
- als historisch gültig erhält;
- als gelöscht markiert;
- nach einer Zusammenführung der verbleibenden Business-Entität zuordnet;
- als Löschereignis für Audit oder Kennzahlenkorrektur aufbewahrt.

Gehe nicht davon aus, dass eine normale Current-Record-Abfrage gelöschte Zeilen enthält. Salesforce stellt Abfragemechanismen bereit, die noch verfügbare soft-deleted Datensätze einbeziehen können. Das analytische Erfordernis — nicht der Default der Extraktion — muss das Downstream-Verhalten bestimmen.

Für Historie werden die konkret benötigten Felder oder Events benannt. Native History-Daten hängen von Konfiguration und Lizenzierung ab; Umfang und Retention müssen in der tatsächlichen Organisation geprüft werden. Wird eine vollständige Point-in-time-Rekonstruktion benötigt, sind Plattform-Snapshots zu definieren, statt die Source-Historie pauschal als ausreichend anzunehmen.

### 7. Custom Configuration über Objektnamen stellen

Ein Standardobjektname garantiert keine standardisierte Business-Bedeutung. Organisationen können Labels umbenennen, Stage-Prozesse verändern, Record Types einführen, Validierungslogik ergänzen, Custom Fields anlegen, Managed Packages installieren und kritische Transaktionen in Custom Objects mit der Endung `__c` abbilden.

Prüfe den konfigurierten Prozess gemeinsam mit Business Ownern und Administratoren. Der richtige Scope kann weniger Standardobjekte und mehr Custom Objects enthalten als eine generische Referenzarchitektur vermuten lässt.

## Checklist

Nutze diese Checkliste vor der Freigabe des Salesforce Source Scope.

### Business und Grain

- [ ] Die Business-Frage benennt Nutzer, Entscheidung und Zeithorizont.
- [ ] Geschäftsprozess und relevantes Event sind explizit.
- [ ] Jeder Fakt besitzt einen definierten Ziel-Grain.
- [ ] Current State, Event-Historie und Snapshots sind getrennt.
- [ ] Kennzahlen besitzen abgestimmte Business-Definitionen und Datumssemantik.

### Objekte und Beziehungen

- [ ] Jedes eingeschlossene Objekt unterstützt eine benannte Anforderung.
- [ ] Standardobjekte werden als Beispiele, nicht als Pflichtumfang behandelt.
- [ ] Custom Objects und Objekte aus Managed Packages wurden geprüft.
- [ ] Kardinalität und Optionalität jeder Beziehung sind dokumentiert.
- [ ] Junction Objects besitzen eine Allokations- oder Ziel-Grain-Regel.
- [ ] Verhalten bei Duplikaten und verwaisten Datensätzen ist definiert.
- [ ] Business Keys und Salesforce-Identifier sind dokumentiert.

### Felder und Datenrisiko

- [ ] Für jedes eingeschlossene Objekt existiert eine Feld-Allowlist.
- [ ] PII und sensible Attribute sind klassifiziert.
- [ ] Freitext, Notizen, Aktivitäten und Dateien wurden separat freigegeben.
- [ ] Binäre Inhalte sind ausgeschlossen, solange der Use Case sie nicht benötigt.
- [ ] Zugriff, Maskierung, Retention und Permitted Use sind dokumentiert.

### Zeit und Lifecycle

- [ ] Freshness wird aus dem Entscheidungsbedarf abgeleitet.
- [ ] Soft-Deletion- und Merge-Verhalten sind definiert.
- [ ] Benötigte History-Felder und Retention wurden in der Organisation geprüft.
- [ ] Snapshot-Frequenz und Retention sind bei Bedarf festgelegt.
- [ ] Jedes zurückgestellte oder ausgeschlossene Objekt besitzt einen Review Trigger.

### Betrieb

- [ ] Ein Business Owner bestätigt die analytische Bedeutung.
- [ ] Ein Technical Owner validiert Schlüssel, Felder und Extrahierbarkeit.
- [ ] Erwartetes Volumen und Änderungsrate sind verstanden.
- [ ] Reconciliation Controls sind definiert.
- [ ] Offene Fragen besitzen Owner und Termin.

## Artifact

Erstelle je Analytics-Produkt oder kompatibler Produktgruppe ein governtes **Salesforce Source Scope**-Artefakt. Es ist kein rohes Objektinventar, sondern der freigegebene Vertrag zwischen Business-Anforderung und späterem Ingestion Design.

![Die Salesforce Source-Scope-Entscheidung dokumentieren](images/playbooks/salesforce-tables-for-analytics-img4-de.png)

### Pflichtfelder

| Feld | Zweck |
|---|---|
| Objekt oder Exporttabelle | Exakter Name des Quellobjekts, Views oder der Connector-Tabelle |
| Business Purpose | Entscheidung, Kennzahl oder Kontrolle, die durch die Quelle unterstützt wird |
| Target Data Product | Produkt, das die Quelle konsumiert |
| Beitrag zum Ziel-Grain | Wie das Objekt den Grain erzeugt, anreichert oder filtert |
| Required Fields | Freigegebene Feld-Allowlist |
| Beziehung und Key | Join-Pfad, Key, Kardinalität und Optionalität |
| Zeitbedarf | Current State, History Event oder Snapshot |
| PII- und Freitextrisiko | Klassifikation und Verarbeitungsentscheidung |
| Freshness | Erforderliche Verfügbarkeit gemäß Business-Entscheidung |
| Retention | Erforderliche Aufbewahrung in Source- und Analytics-Schicht |
| Owner | Fachliche und technische Verantwortlichkeit |
| Decision | Include, Defer oder Exclude |
| Rationale | Evidenz für die Entscheidung |
| Review Trigger | Bedingung für eine Neubewertung |

### Beispielhafte Entscheidungszeilen

| Objekt | Zweck | Beitrag zum Grain | Zeitbedarf | Risiko | Decision | Begründung / Review Trigger |
|---|---|---|---|---|---|---|
| `Opportunity` | Offene Pipeline und Stage-Analyse | Eine Opportunity | Current plus ausgewählte Stage-Historie | Geschäftlich sensibel | Include | Zentraler Fakt; Review bei Änderung von Sales-Prozess oder Stage-Modell |
| `Account` | Pipeline nach governten Account-Merkmalen segmentieren | Viele Opportunities zu einem Account | Current, außer historische Segmentierung ist erforderlich | Kann PII enthalten | Include mit Feld-Allowlist | Nur freigegebene Segmentierungsfelder; Review bei Person Accounts oder Hierarchieanalyse |
| `OpportunityLineItem` | Produktbezogene Pipeline | Eine Opportunity-Position | Current | Preissensitivität | Nur für Positions-Grain einschließen | Im Opportunity-Grain ausschließen; Review bei finanziertem Produkt-Analytics-Use-Case |
| `Product2` / `PricebookEntry` | Produkt- und Pricebook-Kontext auflösen | Referenz zur Opportunity-Position | Current oder Mapping mit Gültigkeitszeitraum | Niedrig bis mittel | Conditional | Nur aufnehmen, wenn Produkt- oder Pricing-Semantik benötigt wird |
| `User` | Verantwortlichen Owner auflösen | Viele Fakten zu einem Owner | Current plus kontrollierte Organisationshistorie bei Bedarf | Mitarbeiter-PII | Include mit minimalen Feldern | Nur freigegebene Identifier und Organisationsmerkmale |
| `Task` / `Event` | Aktivitätsbasierte Entscheidung | Ein freigegebenes Aktivitätsereignis | Event | Hohes Freitext- und PII-Risiko | Defer | Erst mit expliziter Aktivitätstaxonomie und Feld-Allowlist aufnehmen |
| Dateien und Notizen | Analyse von Dokumentinhalten | Separater Content- oder Link-Grain | Versionierter Inhalt | Hohe Vertraulichkeit und hohes Volumen | Exclude | Nur mit freigegebenem Content-Use-Case, Zugriff und Retention neu bewerten |
| Custom Object | Organisationsspezifischer kommerzieller Meilenstein | Gemäß konfiguriertem Prozess | Current, Event oder Snapshot | Zu klassifizieren | Conditional | Business Owner muss Bedeutung, Keys und Autorität bestätigen |

### Erforderliche Outputs

Das freigegebene Artefakt erzeugt:

- eine Liste freigegebener Extraktionsobjekte;
- eine Feld-Allowlist;
- eine explizite Skip-Liste;
- Beziehungs- und Grain-Regeln;
- Anforderungen an Historie, Löschung und Snapshots;
- PII- und Freitext-Controls;
- offene Fragen mit Ownern;
- den Handoff an Ingestion- und Incremental-Load-Design.

**„Nicht geladen“ ist eine dokumentierte Entscheidung.** Es ist weder ein zufälliges Versäumnis noch zwangsläufig dauerhaft. Der Review Trigger hält den Scope anpassbar, ohne die erste Lieferung in eine unkontrollierte Datenkopie zu verwandeln.

### Abzulehnende Anti-Patterns

| Anti-Pattern | Warum es scheitert | Erforderliche Korrektur |
|---|---|---|
| Alle Objekte „für später“ laden | Kosten, Risiko und Unklarheit wachsen ohne Decision Use Case | Zweck, Owner und Review Trigger pro Objekt verlangen |
| Standardobjekten Standardbedeutung unterstellen | Konfiguration, Custom Fields und Prozessdesign verändern die Semantik | Konfigurierten Prozess und Business-Definitionen validieren |
| Uneingeschränkten Freitext kopieren | Sensible und irrelevante Inhalte gelangen in breite Analytics-Zugriffe | Feld-Allowlist und separate Content-Freigabe verwenden |
| Soft Deletion und Historie ignorieren | Kennzahlen driften und frühere Zustände sind nicht erklärbar | Lösch-, Merge-, History- und Snapshot-Verhalten definieren |
| Beziehungstabellen ohne Ziel-Grain laden | Joins duplizieren Fakten oder erzeugen Many-to-many-Mehrdeutigkeit | Kardinalität, Keys und Allokationsregeln zuerst dokumentieren |

## Tools

- [Source Scope Builder](/tools/source-scope-builder) — Objekte, Felder, Beziehungen, Entscheidungen, Begründungen und Review Trigger dokumentieren.
- [Supplier Landscape: Salesforce](/tools/suppliers) — Salesforce in die breitere Source- und Plattformlandschaft einordnen, ohne den Supplier-Katalog zur Architektur zu machen.
- [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker) — Umfang personenbezogener Daten, Zugriff, Retention und Anforderungen aus Betroffenenrechten prüfen, bevor Freitext oder Identitätsattribute geladen werden.

## Resources

Die konfigurierte Salesforce-Organisation bleibt die fachliche und technische Source of Truth. Objektverfügbarkeit, Felder, Beziehungen und Berechtigungen müssen gegen die aktuelle Salesforce-Dokumentation geprüft werden.

- [Salesforce Object Reference: Standard Objects](https://developer.salesforce.com/docs/atlas.en-us.object_reference.meta/object_reference/sforce_api_objects_list.htm)
- [OpportunityLineItem Object Reference](https://developer.salesforce.com/docs/atlas.en-us.object_reference.meta/object_reference/sforce_api_objects_opportunitylineitem.htm)
- [PricebookEntry Object Reference](https://developer.salesforce.com/docs/atlas.en-us.object_reference.meta/object_reference/sforce_api_objects_pricebookentry.htm)
- [Salesforce Files Data Model](https://developer.salesforce.com/docs/platform/data-models/guide/salesforce-files.html)
- [QueryAll REST Resource](https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/resources_queryall.htm)
- [Field Audit Trail Implementation Guide](https://developer.salesforce.com/docs/atlas.en-us.field_history_retention.meta/field_history_retention/field_audit_trail.htm)

## Playbooks

- [Before Building the First Table](/playbooks/before-building-the-first-table) — mit Business-Frage, Kennzahl, Grain, Ownership und Qualitätserwartungen starten, bevor Technologie oder Quelltabellen ausgewählt werden.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — Klassifikation, Permitted Use, Zugriff, Retention und Evidenz für personenbezogene Daten über den gesamten analytischen Lifecycle definieren.

Diese Playbooks liefern die Business-first- und Privacy-Grundlagen. Diese Story wendet sie gezielt auf den Salesforce-Objekt- und Feld-Scope an; sie ersetzt die Playbooks nicht.

## Next step

Gib den Salesforce Source Scope frei, bevor Connector-Einstellungen, inkrementelle Extraktion, Landing-Strukturen oder Change-Data-Verarbeitung entworfen werden. Das Ingestion Design muss die freigegebene Objektliste, Feld-Allowlist, Beziehungsannahmen, Löschregeln und History-Anforderungen umsetzen — und darf den Umfang nicht unbemerkt erweitern.

Danach folgt **SaaS Exports: Tables to Skip**, um die Ausschlussmuster über Salesforce hinaus zu verallgemeinern, während supplier-spezifische Entscheidungen in jedem Source Scope erhalten bleiben.
