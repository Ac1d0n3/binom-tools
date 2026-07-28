---
title: "SaaS-Exporte: Tabellen, die man nicht laden sollte"
description: "Ein herstellerunabhängiger Entscheidungsrahmen, um autoritative Geschäftsdaten vor der Übernahme eines SaaS-Exports von Logs, Caches, doppelten Snapshots, Freitexten und Anhängen zu trennen."
author: Thomas Lindackers
tags:
  - source scope
  - SaaS exports
  - data ingestion
  - data governance
  - PII
publishedAt: 2026-07-28
category: Data Governance
order: -1
hero: images/playbooks/saas-exports-tables-to-skip-hero.png
series: source-load-decisions
seriesTitle: Lade-Entscheidungen für Quellsysteme
seriesPart: 2
---

Ein SaaS-Export ist meist für Produktbetrieb, Support, Migration oder Datensicherung ausgelegt. Er ist kein freigegebenes analytisches Modell. Der Export kann zentrale Geschäftsobjekte enthalten, aber ebenso interne Konfiguration, UI-Zustände, temporäre Strukturen, wiederholte Snapshots, umfangreiche Audit-Ereignisse, unbeschränkten Freitext und binäre Inhalte.

Die Entscheidung über den Quellumfang muss deshalb vor der Connector-Konfiguration fallen. Die erste Frage lautet nicht, ob eine Tabelle extrahiert werden kann. Entscheidend ist, ob eine definierte Entscheidung, Kontrolle oder ein Data Product sie benötigt und ob Bedeutung, Autorität, Granularität, Risiko und Betriebskosten beherrscht werden können.

## Problem

Herstellerexporte stellen mehr Strukturen bereit, als Analytics normalerweise benötigt, weil die Anwendung Workflows, Berechtigungen, Automatisierung, Integrationen, Wiederherstellung, Benutzeroberflächen und operative Diagnose unterstützen muss. Diese Implementierungsanforderungen erzeugen Tabellen, die technisch verfügbar sind, aber nicht automatisch nutzbare Geschäftsdaten darstellen.

![Ein SaaS-Export ist kein Analytics-Modell](images/playbooks/saas-exports-tables-to-skip-img1-de.png)

Ein praxistaugliches Inventar enthält meist sieben Kategorien:

1. **Geschäftsdatensätze** wie Accounts, Abonnements, Fälle, Aufträge oder Projekte.
2. **Beziehungstabellen**, die Geschäftsobjekte, Benutzer, Produkte, Gebiete oder Berechtigungen verbinden.
3. **Referenz- und Konfigurationsdaten** wie Statuscodes, Kategorien, Routing-Regeln oder Feature-Einstellungen.
4. **Historien und Snapshots**, die beabsichtigte Geschäftshistorie, technische Änderungsverfolgung oder wiederholte Kopien desselben aktuellen Zustands darstellen können.
5. **Audit- und System-Logs**, die für Nachvollziehbarkeit, Fehleranalyse, Sicherheit oder Plattformbetrieb erzeugt werden.
6. **UI-Caches und temporäre Strukturen**, die die Produktleistung verbessern, aber keine dauerhafte analytische Bedeutung besitzen.
7. **Freitext, Dateien und Anhänge**, deren Inhalt sensibel, schwer klassifizierbar und teuer in der Aufbewahrung sein kann.

Keine dieser Kategorien führt automatisch zu einer Include- oder Exclude-Entscheidung. Auch eine Geschäftstabelle kann ungeeignet sein, wenn Autorität oder Granularität unklar sind. Eine Historientabelle kann unverzichtbar sein, wenn sie wirtschaftlich relevante Statuswechsel aufzeichnet. Ein Audit-Log kann ein separates Security-Produkt rechtfertigen. Metadaten zu Anhängen können sinnvoll sein, obwohl der Dateiinhalt außerhalb der analytischen Plattform bleibt.

Das zentrale Risiko ist ein unbeabsichtigter Scope. Das Laden aller verfügbaren Tabellen erzeugt mehrere Fehlermuster:

- doppelte Repräsentationen desselben Geschäftsereignisses führen zu Doppelzählungen;
- technische Zeitstempel werden mit beabsichtigter Geschäftshistorie verwechselt;
- Current-State- und Snapshot-Tabellen werden ohne Autoritätsregel verbunden;
- operative Ereignisse werden mit Business Facts auf inkompatiblen Grains vermischt;
- Freitext und Anhänge vergrößern die Grenzen für PII, Aufbewahrung und Zugriff;
- ungenutzte Tabellen erhöhen Extraktionsvolumen, Modellkomplexität, Testaufwand und Störungsfläche.

Speicher kann im Vergleich zu Governance, Interpretation und Lifecycle günstig sein. „Alles laden, weil Speicher billig ist“ ist daher keine neutrale Entscheidung. Ungeklärte Quellfragen werden lediglich in jedes nachgelagerte Modell verschoben.

## Entscheidung

Wende auf jede Exporttabelle denselben gestuften Entscheidungstest an — unabhängig vom Hersteller.

![Vor dem Laden jeder Tabelle einen Entscheidungstest anwenden](images/playbooks/saas-exports-tables-to-skip-img2-de.png)

### 1. Benötigt eine definierte Entscheidung die Tabelle?

Benenne den Report, KPI, die Kontrolle, den Workflow oder das Data Product, das die Tabelle konsumiert. Eine allgemeine Aussage wie „Vielleicht brauchen wir sie später“ ist kein ausreichender Use Case.

Wenn keine benannte Entscheidung oder Kontrolle die Daten benötigt, wird die Tabelle aus dem aktuellen Scope ausgeschlossen. Die Entscheidung wird dokumentiert, damit der Ausschluss sichtbar bleibt und nicht als vergessene Arbeit erscheint.

### 2. Liefert sie eine eindeutige fachliche Bedeutung?

Prüfe, ob die Tabelle ein neues Objekt, eine Beziehung, einen Statuswechsel, eine Klassifikation oder ein Ereignis beiträgt. Denormalisierte Convenience-Exporte, replizierte API-Views und wiederholte Snapshots enthalten häufig dieselbe Bedeutung nur in einer anderen Form.

Wenn mehrere Strukturen dasselbe Konzept repräsentieren, wird genau eine Autorität ausgewählt. Duplikate werden nicht allein deshalb behalten, weil sie verfügbar sind.

### 3. Ist die autoritative Quelle bekannt?

Dokumentiere, welche Tabelle oder welches Objekt das System of Record für das analytische Konzept ist. Die Autorität muss explizit sein, wenn Current-State-Tabellen, Historien, Integrationskopien und Convenience-Exporte überlappen.

Ist die Autorität ungeklärt, wird die Tabelle zurückgestellt, statt nachgelagerte Teams unabhängig entscheiden zu lassen.

### 4. Können Grain und Schlüssel beschrieben werden?

Beschreibe eine Zeile in fachlichen Begriffen. Identifiziere Business Key, Technical Key, Beziehungsschlüssel und erwartete Eindeutigkeit. Eine Tabelle sollte nicht als Faktenquelle geladen werden, wenn der Ziel-Grain nicht formuliert und getestet werden kann.

Ein Event-Log kann beispielsweise eine Zeile pro Systemaktion enthalten, während der analytische Fact eine Zeile pro Auftragsposition besitzt. Beide können valide Produkte sein, sind aber nicht austauschbar.

### 5. Wird Historie bewusst benötigt?

Ein Zeitstempel macht eine Tabelle noch nicht zu nützlicher Historie. Kläre, welche Änderung aufgezeichnet wird, ob die Sequenz vollständig ist, wie Korrekturen erscheinen und welche Entscheidung den historischen Zustand benötigt.

Sinnvolle Beispiele sind Statushistorien für Funnel-Analysen, Vertragsversionen für Effective-Date-Reporting oder Ownership-Historien für Nachvollziehbarkeit. Wiederholte Exporte desselben aktuellen Zustands ohne temporalen Vertrag sind normalerweise doppelte Snapshots und keine governte Historie.

### 6. Sind PII, Aufbewahrung und Zugriff gerechtfertigt?

Klassifiziere Identifikatoren, Freitext, Notizen, Anhänge und benutzergenerierte Inhalte vor der Übernahme. Definiere zulässige Nutzung, Zugriffsgrenzen, Retention, Löschweitergabe und ob eine inhaltliche Suche tatsächlich erforderlich ist.

Sensible Inhalte ohne freigegebenen Zweck werden ausgeschlossen. Werden nur Metadaten benötigt, können Dateiname, Typ, Owner, Zeitstempel und Klassifikation geladen werden, ohne die Binärdatei zu kopieren.

### 7. Können Qualität und Kosten kontrolliert werden?

Prüfe, ob Volumen, Aktualität, Vollständigkeit, Eindeutigkeit und referenzielle Integrität messbar sind. Schätze Kosten für Extraktion, Speicherung, Transformation, Indexierung, Security und Support.

Eine technisch extrahierbare Tabelle bleibt außerhalb des Scopes, wenn ihre Qualität nicht validiert werden kann oder ihre Kosten nicht im Verhältnis zur unterstützten Entscheidung stehen.

Das Ergebnis ist eine von vier Entscheidungen:

- **Include:** benötigte Bedeutung, Autorität, Grain, Controls und Ownership sind klar.
- **Defer:** der Bedarf ist valide, aber Autorität, Grain, Ownership, Zugriff oder Qualität sind noch ungeklärt.
- **Exclude:** es existiert kein unterstützter Use Case, der Inhalt ist redundant oder Risiko und Kosten sind nicht gerechtfertigt.
- **Separates Produkt:** ein operativer, Security- oder Compliance-Use-Case existiert, die Daten dürfen aber nicht mit dem fachlichen Analytics-Produkt vermischt werden.

## Checkliste

Nutze diese Checkliste, bevor eine Tabelle für die Extraktion freigegeben wird.

![Typische Skip-Muster und ihre Ausnahmen](images/playbooks/saas-exports-tables-to-skip-img3-de.png)

### Fachliche Bedeutung

- Welche Geschäftsentscheidung, welcher KPI, welche Kontrolle oder welcher Workflow benötigt die Tabelle?
- Welches neue Objekt, welche Beziehung, welcher Zustand oder welches Ereignis kommt hinzu?
- Ist die Tabelle autoritativ, abgeleitet, repliziert oder lediglich bequem?
- Kann eine Zeile fachlich beschrieben werden?
- Welche Schlüssel sichern Eindeutigkeit und Beziehungen?

### Historie

- Ist die Tabelle Current State, Event History, Effective-Dated History oder ein wiederholter Snapshot?
- Welche Änderung ist analytisch relevant?
- Sind nachträgliche Korrekturen, Löschungen und Reprocessing verstanden?
- Kann derselbe Datensatz in mehreren Exportstrukturen erscheinen?
- Ist eine Autoritätsregel dokumentiert, die Doppelzählungen verhindert?

### Risiko und Lifecycle

- Enthält die Tabelle direkte Identifikatoren, Quasi-Identifikatoren, Secrets, Notizen oder unbeschränkten Freitext?
- Referenziert sie Dateien oder enthält sie binäre Inhalte?
- Ist die Aufbewahrung für Quelle und analytische Kopie definiert?
- Können Quelllöschungen und Legal Holds weitergegeben werden?
- Sind Zugriff, Maskierung und zulässige Nutzung freigegeben?

### Betrieb

- Können Vollständigkeit, Eindeutigkeit, Aktualität und Beziehungen getestet werden?
- Steht das erwartete Volumen im Verhältnis zum Use Case?
- Gibt es einen Owner für die fachliche Bedeutung und einen Owner für den Betrieb?
- Kann die Tabelle unterstützt werden, wenn der Hersteller das Schema ändert?
- Ist für zurückgestellte oder ausgeschlossene Tabellen ein Review Trigger definiert?

### Typische Skip-Muster und valide Ausnahmen

**UI-Caches und temporäre Strukturen** werden normalerweise ausgeschlossen, weil sie Implementierungszustände des Produkts darstellen. Eine Aufnahme ist nur gerechtfertigt, wenn eine benannte Kontrolle davon abhängt und der Herstellervertrag eine stabile Bedeutung zusichert.

**Tabellen ungenutzter Features** werden normalerweise ausgeschlossen, weil sie leere oder irreführende Strukturen erzeugen. Sie werden neu bewertet, sobald das zugehörige Business Feature aktiviert und ein Consumer benannt ist.

**Doppelte denormalisierte Snapshots** werden normalerweise ausgeschlossen, weil sie autoritative Daten wiederholen und Doppelzählungen begünstigen. Einer dieser Snapshots wird nur dann aufgenommen, wenn er als freigegebene Autorität für einen bestimmten Grain dient und konkurrierende Repräsentationen explizit verworfen werden.

**Umfangreiche Audit-Logs** werden normalerweise vom Business Analytics getrennt. Sie gehören in ein governtes Security-, Operations- oder Compliance-Produkt, wenn Eventmodell, Retention, Zugriff und Evidenzanforderung definiert sind.

**Unbeschränkte Freitext-Blobs** werden normalerweise ausgeschlossen. Ausgewählte Texte werden erst nach Klassifikation, Zweckfreigabe, Retention Design und eingerichteten Qualitätskontrollen aufgenommen.

**Dateien und Anhänge** werden normalerweise nicht in das Warehouse kopiert. Attachment Metadata kann aufgenommen werden, wenn sie Vollständigkeit, Suchrouting oder Compliance Evidence unterstützt. Der Inhalt selbst wird nur bei einem governte Search-, Legal-, regulatorischen oder analytischen Use Case kopiert.

**Systemkonfigurationsrauschen** wird normalerweise ausgeschlossen. Ausgewählte Konfigurationen können aufgenommen werden, wenn sie Geschäftsverhalten, Routing, Schwellenwerte oder Policy Decisions erklären und als versionierte Referenzdaten geführt werden können.

## Artefakt

Das freigegebene Ergebnis ist ein governtes Source-Scope-Register mit genau einer Entscheidung pro Tabelle oder Exportobjekt.

![Skip-Entscheidungen als governter Quellumfang dokumentieren](images/playbooks/saas-exports-tables-to-skip-img4-de.png)

Verwende drei explizite Portfolios: **Jetzt enthalten**, **Zurückgestellt** und **Ausgeschlossen**. Ungeprüfte Tabellen dürfen nicht in einem impliziten Backlog verbleiben.

Ein minimaler Entscheidungsdatensatz enthält:

| Feld | Zweck |
|---|---|
| Tabelle oder Objekt | Exakte Exportstruktur in der Prüfung |
| Kategorie | Business, Beziehung, Referenz, Historie, Audit, Cache, Text oder Anhang |
| Use Case | Benannte Entscheidung, Kontrolle oder Data Product |
| Autorität | System-of-Record-Aussage und konkurrierende Repräsentationen |
| Grain | Fachliche Bedeutung einer Zeile |
| Schlüssel | Business Key, Technical Key und Beziehungsschlüssel |
| Historienbedarf | Current, Event, Effective-Dated, Snapshot oder keiner |
| PII oder Freitext | Klassifikation und Inhaltsrisiko |
| Entscheidung | Include, Defer, Exclude oder separates Produkt |
| Begründung | Evidenz für die Entscheidung |
| Owner | Verantwortliche Business- oder Governance-Rolle |
| Review Trigger | Requirement-, Feature-, Kontroll-, Incident- oder Policy-Änderung |
| Downstream-Auswirkung | Betroffene Products, Marts, Semantic Models und Controls |

Das Register sollte fünf operative Outputs erzeugen:

1. **Field Allowlist** — nur freigegebene Felder aus enthaltenen Tabellen.
2. **Input für den Source Contract** — Grain, Schlüssel, Autorität, Freshness und Change Expectations.
3. **Retention Boundary** — was in den analytischen Lifecycle gelangt und was außerhalb bleibt.
4. **Erwartete Volumenreduktion** — Nachweis, dass Scope Control Extraktions- und Betriebskosten reduziert.
5. **Offene Fragen** — explizite Abhängigkeiten für zurückgestellte Entscheidungen.

### Beispiel für ein Entscheidungsregister

| Tabelle | Kategorie | Entscheidung | Begründung | Review Trigger |
|---|---|---|---|---|
| `subscription` | Geschäftsdatensatz | Include | Autoritativer aktueller Vertrag mit einer Zeile pro Subscription | Änderung des Vertragsmodells |
| `subscription_status_history` | Historie | Include | Erforderlich für Conversion- und Churn-Stage-Analyse | Änderung der Historiendefinition |
| `subscription_export_snapshot` | Doppelter Snapshot | Exclude | Wiederholt den aktuellen Zustand ohne eigenständige temporale Bedeutung | Neue regulatorische Snapshot-Anforderung |
| `ui_recent_items` | UI-Cache | Exclude | UI-Zustand ohne unterstützte analytische Entscheidung | Benannte Product-Usage-Kontrolle |
| `system_audit_event` | Audit-Log | Separates Produkt | Erforderlich als Security Evidence auf System-Event-Grain | Änderung der Security-Retention-Policy |
| `attachment` | Dateimetadaten | Defer | Metadaten könnten Case-Vollständigkeit unterstützen; Binärinhalt ist nicht freigegeben | Freigegebener Search- oder Compliance-Bedarf |
| `case_notes` | Freitext | Defer | Potenzieller Service-Mehrwert, aber Klassifikation, Zugriff und Retention sind ungeklärt | Freigegebener Text-Analytics-Zweck und Controls |

Eine ausgelassene Tabelle kann erneut bewertet werden, aber nur durch eine neue Anforderung, einen neuen Control Need oder eine wesentliche Änderung der Quelle. Die Neubewertung erzeugt einen neuen Entscheidungsdatensatz; sie darf die ursprüngliche Ausschlussbegründung nicht stillschweigend umgehen.

## Tools

Nutze den [Source Scope Builder](/tools/source-scope-builder), um Include-, Defer-, Exclude- und Separate-Product-Entscheidungen auf Tabellenebene mit Autorität, Grain, Risiko und Review Triggern zu erfassen.

Nutze den [Metadata Export Generator](/tools/meta-export-generator), um den freigegebenen Scope in wiederverwendbare Metadaten für Source Contracts, Field Allowlists und die Übergabe an die Implementierung zu überführen.

Die Tools unterstützen den Entscheidungsprozess. Sie ersetzen keine Freigaben durch Data Owner, Steward, Architecture, Privacy oder Security, wenn diese Rollen erforderlich sind.

## Ressourcen

- [Salesforce-Tabellen für Analytics](/stories/salesforce-tables-for-analytics) — Teil 1 dieser Serie mit Fokus auf beziehungszentrierte Scope-Entscheidungen für eine konkrete SaaS-Quelle.
- Exportinventar des Herstellers oder Connectors.
- Data-Classification- und Retention-Policy.
- Bestehendes Report-, KPI- und Control-Inventar.
- Source Contract, Schemadokumentation und Änderungshistorie.
- Data-Product-Backlog und Consumer Map.

Die wichtigste Quellevidenz ist nicht die Gesamtzahl verfügbarer Tabellen. Entscheidend ist die nachvollziehbare Beziehung zwischen einem benannten fachlichen Bedarf und einer autoritativen, testbaren Quellstruktur.

## Playbooks

Wende [Vor dem Bau der ersten Tabelle](/playbooks/before-building-the-first-table) vor der Implementierung an. Verwende dessen Entscheidungen zu Business Question, Grain, Ownership und Acceptance Criteria wieder, statt sie im Source-Scope-Register neu zu erfinden.

Diese Story ergänzt die Grenze des Quellimports: Das Playbook definiert, was das erste Data Product beantworten muss; dieser Entscheidungsrahmen definiert, welche Exportstrukturen dafür verwendet werden dürfen.

## Nächster Schritt

Gib das Source-Scope-Register frei, bevor die Produktionsextraktion konfiguriert wird. Übergib enthaltene Tabellen und Field Allowlist an das Ingestion Design, leite gerechtfertigte Audit- oder Security-Daten in ein separates Produkt und halte zurückgestellte sowie ausgeschlossene Strukturen mit Ownern und Review Triggern sichtbar.

Das richtige Ergebnis ist nicht die größtmögliche Landing Zone. Es ist der kleinste governte Quellumfang, der die erforderlichen Entscheidungen unterstützt, ohne Autorität, Historie, Kontrolle oder Evidenz zu verlieren.
