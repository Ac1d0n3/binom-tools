---
title: Zentrale, föderierte oder verteilte Metadaten — Eine Architektur für Auffindbarkeit, Verantwortung und Steuerung wählen
description: Ein praxisnaher Entscheidungsrahmen, der zentrale Discovery, föderierte Ownership, verteilte Quellmetadaten und selektive zentrale Kontrolle verbindet, ohne eine ungepflegte zweite Wahrheit zu erzeugen.
category: Data Governance
tags:
  - metadata
  - metadata-architecture
  - metadata-governance
  - data-catalog
  - federated-governance
  - distributed-metadata
  - metadata-mesh
  - metadata-index
  - metadata-ownership
  - active-metadata
  - data-products
  - data-lineage
  - metadata-provenance
  - enterprise-governance
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 8
seriesTitle: MetaData Deep Dive
hero: images/playbooks/centralized-federated-or-distributed-metadata-hero.png
---

## Metadatenarchitektur ist keine binäre Plattformentscheidung

Organisationen stellen Metadatenarchitektur häufig als Entscheidung zwischen zwei Extremen dar:

```text
Ein zentraler Catalog
oder
jede Domain verwaltet ihre eigenen Metadaten
```

Keines der beiden Extreme beschreibt, wie Metadaten tatsächlich entstehen und gepflegt werden.

Technische Metadaten entstehen in Databases, Pipelines, Repositories, Semantic Models, BI-Plattformen, Security-Systemen und Runtime Logs. Fachliche Definitionen werden von Domain Experts geliefert. Enterprise Policies benötigen domainübergreifende Autorität. Quality Expectations können einem Data-Product-Team gehören, während Ausführungsevidenz in einer Observability-Plattform verbleibt. Access Events können zu sensitiv und zu umfangreich sein, um sie in einen allgemeinen Catalog zu kopieren. Cross-System Lineage kann dagegen nicht aus einer einzelnen Quelle verstanden werden.

Eine zentrale Plattform kann einen konsistenten Einstiegspunkt bieten. Sie wird dadurch nicht automatisch zum richtigen Authoring-Ort für jeden Wert. Eine Domain kann ihre Definitionen verantworten. Lokale Ownership allein liefert jedoch keine Enterprise Search, kein gemeinsames Vokabular und keine domainübergreifende Impact Analysis. Ein Quellsystem kann autoritativ bleiben. Jede Detailabfrage zur Laufzeit gegen alle Quellen zu senden, kann jedoch Latenz-, Verfügbarkeits- und Integrationsprobleme erzeugen.

Die sinnvolle Frage lautet deshalb nicht:

```text
Sollen Metadaten zentralisiert oder dezentralisiert werden?
```

Sondern:

```text
Welche Metadatenfähigkeit sollte zentralisiert werden,
welche Verantwortung sollte föderiert bleiben
und welche Evidenz sollte verteilt an ihrer Quelle verbleiben?
```

> **Die meisten Organisationen benötigen zentrale Discovery, föderierte Ownership und selektive zentrale Kontrolle. Der physische Ort von Metadaten sollte Autorität, Freshness, Sensitivität, Verfügbarkeit und operative Nutzung abbilden — nicht einen Architekturtrend.**

Dieses Prinzip trennt fünf Entscheidungen, die häufig fälschlich zu einer einzigen zusammengezogen werden:

```text
Authoring
Storage
Discovery
Approval
Enforcement
```

Dasselbe Metadatenattribut kann für jede dieser Entscheidungen einen anderen Ort verwenden.

Eine fachliche Definition kann von der Sales Domain erstellt, in einem Domain Repository autoritativ gespeichert, zentral indexiert, über einen Enterprise Workflow freigegeben und durch eine Semantic-Model-Validierung enforced werden. Ein technisches Schema kann vom Quellsystem erzeugt, zentral für Search gecacht und für aktuelle Details direkt abgefragt werden. Ein Access Event kann in der Security-Plattform verbleiben und zentral nur als Zusammenfassung oder Referenz verfügbar sein.

Architektur wird beherrschbar, wenn diese Verantwortungen explizit designt werden.

## Drei Architekturmuster basieren auf unterschiedlichen Betriebsannahmen

Zentrale, föderierte und verteilte Metadaten sind keine Reifestufen. Es sind Muster mit unterschiedlichen Stärken, Risiken und Betriebsanforderungen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/centralized-federated-or-distributed-metadata-img1-de.png"
        alt="Drei nebeneinander dargestellte Metadatenarchitekturen vergleichen zentrale, föderierte und verteilte Ownership, Konsistenz, Geschwindigkeit, Integrationsaufwand, Resilienz und das Risiko veralteter Kopien"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Kein Muster ist universell richtig. Das passende Design hängt davon ab, wer Metadaten pflegen kann, wie schnell sie sich ändern, wie breit sie wiederverwendet werden und welche Controls domainübergreifend funktionieren müssen.
    </figcaption>
</figure>

## Zentrales Metadaten-Repository

Ein zentrales Repository speichert und governed den größten Teil der Metadaten in einer Plattform.

Typische Eigenschaften sind:

- eine primäre Metadaten-Database;
- zentral verwaltete Schemas und Workflows;
- gemeinsame Authoring-Oberflächen;
- ein Governance-Team, das den Großteil der Regeln definiert;
- geplante oder eventbasierte Ingestion aus Quellsystemen;
- eine gemeinsame Enterprise-Search- und API-Schicht;
- zentrale Persistenz von Beziehungen und Historie.

Dieses Muster kann wirksam sein, wenn:

- die Anzahl der Systeme und Domains begrenzt ist;
- ein Team die meisten Assets tatsächlich versteht;
- sich Metadaten mit beherrschbarer Geschwindigkeit ändern;
- zentrale Standards wichtiger sind als Domain-Autonomie;
- Quell-APIs schwach oder nicht vorhanden sind;
- konsistentes Reporting und Audit primäre Ziele sind.

Die Vorteile sind klar:

- ein technisches Betriebsmodell;
- ein Ort für Security, Backup und Monitoring;
- konsistente Workflow States;
- einfacheres Enterprise Reporting;
- leichter umsetzbare globale Search;
- geringerer anfänglicher Koordinationsaufwand.

Das Hauptrisiko ist nicht die zentrale Technologie. Das Hauptrisiko ist zentrale Pflege ohne zentrales Wissen.

Ein Repository wird zur zweiten Wahrheit, wenn es kopierte Beschreibungen, Ownership und Klassifikationen speichert, die kein verantwortliches Team aktualisiert. Die Plattform kann verfügbar und durchsuchbar bleiben, während der Inhalt still von Quellsystemen und Domain-Entscheidungen abweicht.

Zentralisierung erzeugt außerdem Konzentrationsrisiken:

- Connector-Fehler können Quelländerungen verdecken;
- zentrale Ingestion-Latenz kann Metadaten veralten lassen;
- ein Plattformausfall kann Discovery für die gesamte Organisation entfernen;
- das zentrale Team kann zum Review Bottleneck werden;
- lokale Teams können ihre Accountability abgeben, weil „das Catalog-Team zuständig ist“.

Ein zentrales Repository ist deshalb nur geeignet, wenn zentrale Ownership real existiert und nicht nur in einer Architekturgrafik zugeordnet wurde.

## Zentraler Metadatenindex

Ein zentraler Index ist enger gefasst als ein zentrales Repository.

Er speichert ausreichend Informationen, um Metadaten zu finden, zu identifizieren und zu verbinden. Er versucht jedoch nicht, jedes Quellattribut zentral zu persistieren.

Ein praktischer Index kann enthalten:

```text
kanonischer Asset-Identifier
Quellsystem und Umgebung
qualifizierte Quellreferenz
Display Name
Asset-Typ
Domain
Owner oder verantwortliche Rolle
Lifecycle Status
ausgewählte Klassifikationen
Relationship Pointer
durchsuchbare Beschreibung
Freshness Status
letzte erfolgreiche Synchronisierung
```

Detaillierte technische Attribute, Runtime Metrics, Audit Events oder große Profiling-Ergebnisse können in ihren Ursprungssystemen verbleiben.

Der Index unterstützt:

- Enterprise Search;
- Cross-System Identity Resolution;
- High-Level Lineage;
- Navigation über Domains und Ownership;
- gemeinsames Vokabular;
- Links zu quellnativen Details;
- API-basierte Discovery.

Dieses Muster reduziert Duplikation und zentrales Storage-Volumen. Gleichzeitig bewahrt es Quellautorität natürlicher.

Seine Schwäche ist die Abhängigkeit von Quellverfügbarkeit. Search Results können sichtbar bleiben, während die Detailansicht der Quelle nicht verfügbar ist. Mehrere Systeme für eine Seite oder API-Antwort abzufragen, kann unvorhersehbare Latenz erzeugen. Quell-APIs können unterschiedliche Authentifizierung, Pagination, Rate Limits und Semantik besitzen.

Ein zentraler Index benötigt deshalb explizites Degradation Behaviour:

```text
Aktuelles Quelldetail verfügbar
Gecachtes Detail verfügbar
Quelle vorübergehend nicht verfügbar
Metadaten-Freshness unbekannt
Referenz nicht mehr auflösbar
```

Eine Referenz ohne Verfügbarkeits- und Freshness-Status ist keine belastbare Architektur.

## Föderierte Metadata Governance

Federation trennt Enterprise-Koordination von Domain Accountability.

Domains verantworten die Metadaten, die sie fachlich korrekt pflegen können:

- Definitionen;
- lokale Terminologie;
- Data-Product-Kontext;
- Quality Expectations;
- freigegebene Mappings;
- lokale Steward-Zuordnungen;
- bekannte Einschränkungen;
- Intended und Prohibited Use.

Enterprise Governance definiert Mindeststandards:

- Pflichtfelder;
- kanonische Beziehungstypen;
- gemeinsame Klassifikationstaxonomie;
- Freigabeanforderungen;
- Interoperabilitätsregeln;
- Policy Framework;
- Eskalationswege;
- Evidenz- und Audit-Anforderungen.

Eine zentrale Plattform stellt Discovery und domainübergreifende Services bereit. Die Domains bleiben trotzdem für Korrektheit verantwortlich.

Federation eignet sich, wenn:

- mehrere Domains eigenes fachliches Wissen besitzen;
- zentrale Teams den Detailkontext nicht pflegen können;
- Data Products klare accountable Owner haben;
- lokale Geschwindigkeit relevant ist;
- domainübergreifende Konsistenz weiterhin erforderlich ist;
- Enterprise Policies über dezentrale Delivery Teams hinweg gelten müssen.

Federation bedeutet nicht, dass jede Domain ein eigenes Modell erfindet.

Ohne gemeinsame Contracts wird Federation zu Fragmentierung. Ein funktionierendes föderiertes Design benötigt einen kleinen verpflichtenden Kern und kontrollierte Extension Points.

Beispiel:

```yaml
required_metadata:
  - domain
  - accountable_role
  - lifecycle_status
  - source_reference
  - classification_status
  - definition_status
  - freshness_expectation

domain_extensions:
  sales:
    - revenue_recognition_scope
    - sales_channel
  finance:
    - legal_entity
    - accounting_standard
  customer_service:
    - interaction_type
    - case_sensitivity
```

Der Enterprise Core unterstützt Vergleich und Kontrolle. Domain Extensions bewahren legitime lokale Bedeutung.

## Verteilte Domain-Metadaten

In einem verteilten Muster verbleiben Metadaten primär in Quellsystemen, Code Repositories, Domain-Plattformen und operativen Tools.

Die zentrale Schicht enthält möglicherweise nur:

- Service Endpoints;
- Quellreferenzen;
- Identity Mappings;
- minimale Search Records;
- ausgewählte Policy States;
- gecachte Relationship Summaries.

Detaillierte Metadaten werden bei Bedarf abgefragt.

Dieses Muster ist sinnvoll, wenn:

- sich Metadaten zu schnell für periodisches Kopieren ändern;
- Quellsysteme belastbare APIs bereitstellen;
- Sensitivität breite Replikation verhindert;
- Domains bereits reife Metadatenservices betreiben;
- zentrales Storage unvertretbare Duplikation erzeugen würde;
- operative Controls quellnativen State benötigen.

Verteilte Architektur kann Freshness verbessern, weil direkt gegen die Quelle abgefragt wird. Sie kann auch die Resilienz erhöhen, wenn Domains während eines zentralen Ausfalls unabhängig weiterarbeiten.

Der Integrationsaufwand verlagert sich jedoch in die Laufzeit:

- jede Quelle benötigt Authentication und Authorization;
- APIs verwenden unterschiedliche Modelle und Identifier;
- Verfügbarkeit variiert;
- Latenz summiert sich;
- Cross-Source Queries sind schwieriger;
- historische Werte können verschwinden, wenn Quellen sie nicht speichern;
- Enterprise Reporting wird komplexer.

Distribution reduziert einen Teil der Copy-Kosten, erhöht aber Orchestration-, Observability- und Contract-Management-Kosten.

## Metadata Mesh ist ein Betriebsmodell, kein weiteres Catalog Label

Ein Metadata Mesh überträgt Product- und Platform-Prinzipien auf Metadaten.

Domains veröffentlichen Metadaten als governte, interoperable Produkte. Eine gemeinsame Plattform stellt zentrale Fähigkeiten bereit:

- Identity;
- Search;
- Vocabulary;
- Lineage Exchange;
- Policy Contracts;
- Access Control;
- Eventing;
- APIs;
- Quality- und Freshness-Indikatoren.

Ein Metadata Product sollte besitzen:

```text
Owner
Scope
Schema
API- oder Event-Contract
Service Level
Freshness Expectation
Quality Checks
Versionierung
Deprecation Policy
Support Path
```

Der Mesh-Ansatz wird sinnvoll, wenn Domains bereits unabhängig arbeiten und verlässliche Interfaces bereitstellen können.

Er wird zur Ausrede für Fragmentierung, wenn Domains „Metadaten besitzen“ sollen, aber keine Plattformunterstützung, gemeinsame Contracts, Finanzierung oder messbaren Pflichten erhalten.

Ein Metadata Mesh ist deshalb nicht der Standardstartpunkt für eine kleine Organisation. Es ist ein Operating Model für Umgebungen mit realer Domain-Autonomie und ausreichender Engineering-Reife.

## Authoring, Storage, Discovery, Approval und Enforcement trennen

Ein Metadatenwert kann mehrere Systeme durchlaufen, ohne dass sich seine Autorität ändert.

Betrachten wir eine Business-Term-Definition:

```text
Authoring:
Sales Domain Repository

Autoritativer Storage:
Sales Metadata Service

Indexierung:
zentrale Metadatenplattform

Approval:
Domain Owner plus Enterprise Review bei Shared Use

Enforcement:
Semantic-Model-Validierung und Publication Checks
```

Nun ein physischer Feldtyp:

```text
Authoring:
Database DDL

Autoritativer Storage:
Database Catalog

Cache:
zentraler Metadatenindex

Approval:
keine manuelle Freigabe für beobachtete Struktur

Enforcement:
Database Engine und Deployment Validation
```

Und eine Klassifikation personenbezogener Daten:

```text
Detection:
Classification Scanner

Proposal:
zentraler Governance Workflow

Approval:
Domain Steward oder Privacy-Rolle

Autoritativer Storage:
Governance-Plattform

Distribution:
Warehouse Tags, Masking Rules und Catalog Index

Enforcement:
Access Platform und Data Engine
```

Diese Beispiele zeigen, warum ein System nicht automatisch zum System of Record für jede Metadatendimension werden kann.

Eine praktische Architektur sollte jedes wichtige Attribut über eine Verantwortungsmatrix dokumentieren.

| Metadatenattribut | Autoritativer Autor | Autoritativer Store | Discovery-Ort | Approval-Autorität | Enforcement-Ort |
| --- | --- | --- | --- | --- | --- |
| Technisches Schema | Quellplattform oder Code | Source Catalog oder Repository | Zentraler Index | Automatisierte Validierung | Quellplattform |
| Fachliche Definition | Domain Expert | Domain- oder Governance Repository | Zentrale Search | Domain Owner oder Steward | Dokumentation und Semantic Checks |
| Enterprise-Term-Mapping | Domain Steward | Governance-Plattform | Zentrale Search | Enterprise Vocabulary Authority | Modeling- und Publication Checks |
| Klassifikation | Detector plus Steward | Governance-Plattform | Zentrale Search und Source Tags | freigegebene Governance-Rolle | Datenplattform und Access Controls |
| Quality Expectation | Data-Product-Team | Quality Contract oder Repository | Zentrale Search | Product Owner oder Data Owner | Pipeline und Observability |
| Runtime Result | Ausführungssystem | Operational Store | zentrale Zusammenfassung | normalerweise keine Freigabe | Alerting und Publication Control |
| Access Event | Security-Plattform | Audit Store | eingeschränkte Zusammenfassung oder Referenz | Security Policy | Identity- und Access-Plattform |

Diese Tabelle sollte nicht universell übernommen werden. Ihr Zweck ist, Autorität explizit zu machen.

## Entscheiden, was zentral gespeichert werden soll

Zentraler Storage sollte pro Metadatenkategorie bewusst gewählt werden und nicht das Default-Verhalten jedes Connectors sein.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/centralized-federated-or-distributed-metadata-img2-de.png"
        alt="Entscheidungsmatrix zum Speichern, Cachen, Referenzieren oder bedarfsgesteuerten Abfragen von Identifiern, Definitionen, Schemas, Metriken, Access Events, Policies, Klassifikationen, Nutzungsstatistiken und Profiling Samples"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der Storage-Modus sollte sich nach Änderungsfrequenz, Sensitivität, Quellverfügbarkeit, systemübergreifender Nutzung, Historienanforderung und Enforcement-Latenz richten.
    </figcaption>
</figure>

Vier Modi decken die meisten Anforderungen ab.

### Store

Metadaten werden als governter Datensatz zentral persistiert.

Nutze diesen Modus, wenn:

- der Wert Enterprise-owned ist;
- systemübergreifende Historie benötigt wird;
- zentrale Workflows ihn ändern oder freigeben;
- Quellsysteme die benötigte Historie nicht bewahren;
- mehrere Controls von einem konsistenten State abhängen;
- zentrale Verfügbarkeit verpflichtend ist.

Typische Kandidaten sind:

- kanonische Identifier;
- Identity Mappings;
- Enterprise Vocabulary;
- freigegebene Cross-Domain Mappings;
- Policy Definitions;
- Approval Records;
- Relationship History;
- Exceptions;
- zentraler Lifecycle State.

Auch zentraler Storage muss Provenance und Quellreferenzen erhalten.

### Cache

Eine temporäre oder synchronisierte Kopie wird gespeichert, während die Quelle autoritativ bleibt.

Nutze diesen Modus, wenn:

- schnelle Search relevant ist;
- die Quelle ausfallen kann;
- sich Daten häufig, aber nicht kontinuierlich ändern;
- Eventual Consistency akzeptabel ist;
- die Plattform Freshness und Synchronisierungsstatus anzeigen kann.

Typische Kandidaten sind:

- technische Schemas;
- Quellbeschreibungen;
- ausgewählte Runtime Aggregates;
- Usage Statistics;
- Source-Ownership-Referenzen;
- aktuelle Lineage Edges;
- Quality Summaries.

Ein Cache muss offenlegen:

```text
source
collected_at
source_observed_at
expected_refresh_interval
last_successful_sync
completeness
staleness status
```

Ein gecachter Wert ohne diese Felder kann fälschlich als autoritativer aktueller Wert interpretiert werden.

### Reference

Ein stabiler Pointer und ausreichend Metadaten zur Identifikation des Ziels werden gespeichert.

Nutze diesen Modus, wenn:

- Detailinhalte in ein spezialisiertes System gehören;
- Kopieren Sensitivitäts- oder Lizenzprobleme erzeugen würde;
- eine Quell-UI oder API die beste Interpretation liefert;
- zentrale Consumer hauptsächlich Navigation statt lokale Persistenz benötigen;
- der Payload groß ist.

Typische Kandidaten sind:

- detaillierte Access Investigations;
- vollständige Policy-Dokumente;
- Code Repositories;
- Dashboard Definitions;
- große Profiling Reports;
- Incident Records;
- Model-Evaluation-Artefakte.

Eine Referenz sollte einen stabilen Source Identifier enthalten und nicht nur eine fragile URL.

### Query on Demand

Metadaten werden direkt beim Request abgerufen.

Nutze diesen Modus, wenn:

- Freshness nahezu in Real Time benötigt wird;
- die Source API zuverlässig ist;
- der Query Scope klein bleibt;
- zentrale Persistenz unerwünscht ist;
- Authorization durch die Quelle bewertet werden muss;
- der Wert wenig Cross-System Reuse besitzt.

Typische Kandidaten sind:

- aktueller Runtime Status;
- aktuelle Access Details;
- Live-Quellverfügbarkeit;
- aktueller Job State;
- High-Volume Event Details;
- sensitive Audit Evidence.

On-Demand Queries benötigen Timeouts, Retries, Circuit Breaking, Cache Rules und klares Fallback Behaviour.

## Entscheidungskriterien konsistent anwenden

Jede Metadatenkategorie sollte anhand derselben Fragen bewertet werden.

### Änderungsfrequenz

Wie schnell kann sich der Wert ändern?

Ein stabiler Business Term kann zentral gespeichert werden. Ein Job Status, der sich alle paar Sekunden ändert, sollte normalerweise operativ verbleiben und abgefragt oder aggregiert werden.

### Sensitivität

Wer darf die Metadaten sehen?

Metadaten können selbst sensitiv sein. Access Logs, Security Groups, Sample Values, Model Prompts und Profiling-Ergebnisse können personenbezogene, vertrauliche oder security-relevante Informationen offenlegen.

Zentrale Discovery rechtfertigt keine uneingeschränkte zentrale Kopie.

### Quellverfügbarkeit

Kann die Quelle bei Discovery und Control Execution zuverlässig verwendet werden?

Eine unzuverlässige Quelle kann einen Cache erfordern. Eine verlässliche Quelle mit vertraglicher API kann References oder On-Demand Queries unterstützen.

### Systemübergreifende Nutzung

Wie viele Systeme benötigen den Wert?

Kanonische Identitäten, Enterprise Terms und Cross-Domain Relationships gewinnen durch zentrale Persistenz. Stark lokale Runtime Details möglicherweise nicht.

### Benötigte Historie

Müssen frühere Zustände erklärbar bleiben?

Wenn eine Quelle nur den aktuellen State liefert, kann zentraler Storage für Audit und Impact Analysis erforderlich sein.

### Enforcement-Latenz

Wie schnell muss ein Control reagieren?

Eine Policy für Real-Time Access Decisions darf nicht von einem langsamen nächtlichen Catalog Sync abhängen. Enforcement sollte in der Plattform stattfinden, die die Latenzanforderung erfüllen kann, selbst wenn die freigegebene Policy zentral verwaltet wird.

## Zentrale Discovery mit föderierter Ownership ist das häufigste Zielbild

Das praktischste Enterprise-Muster ist weder ein vollständig zentrales Repository noch uneingeschränkte Distribution.

Es lautet:

```text
Domain-owned Authoring und Accountability
+
Enterprise-Mindeststandards
+
zentrale Identity, Discovery und Relationships
+
selektives zentrales Approval und Control
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/centralized-federated-or-distributed-metadata-img3-de.png"
        alt="Sales, Finance, Customer Service und Operations verantworten Definitionen, Stewards, Quality Expectations und Mappings, während eine zentrale Plattform Search, Vocabulary, Lineage, Policy Framework, APIs und Eskalation bereitstellt"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Enterprise Governance definiert den minimalen interoperablen Contract. Die Domains bleiben für Metadaten verantwortlich, die ihr fachliches Wissen benötigen.
    </figcaption>
</figure>

Die zentrale Plattform sollte nicht jede Verantwortung übernehmen.

Sie sollte Fähigkeiten bereitstellen, deren Duplikation ineffizient wäre:

- kanonische Identity;
- Enterprise Search;
- Cross-Domain Relationships;
- Shared Vocabulary;
- Policy Framework;
- gemeinsame Workflow States;
- zentrale Eskalation;
- API- und Event Contracts;
- Audit von Freigaben;
- Plattform-Observability.

Domains sollten Verantwortungen behalten, die lokales Wissen benötigen:

- fachliche Definitionen;
- Domain-Terminologie;
- valide lokale Unterschiede;
- Data-Product-Scope;
- Quality Expectations;
- lokale Stewardship;
- bekannte Einschränkungen;
- freigegebene lokale Mappings;
- Intended Use;
- domain-spezifische Deprecation Decisions.

Enterprise Governance sollte Mindestanforderungen an Evidenz und Konsistenz definieren, ohne Domain-Inhalte neu zu schreiben.

Eine brauchbare Regel lautet:

> Zentralisiere die Fähigkeit, wenn sie domainübergreifend funktionieren muss. Föderiere die Entscheidung, wenn ihre Korrektheit von Domain-Wissen abhängt.

## Konkretes Beispiel: ein Customer-Konzept in vier Domains

Nehmen wir an, vier Domains verwenden zusammenhängende Kundeninformationen.

### Sales

Sales pflegt:

```text
Account
Sales Territory
Opportunity Relationship
Commercial Contact
Revenue Attribution
```

Die Definition von `Customer` kann je nach Prozess Prospects oder aktive Accounts umfassen.

### Finance

Finance pflegt:

```text
Debtor
Legal Entity
Billing Account
Credit Status
Receivable Relationship
```

Ein Debtor ist nicht automatisch mit dem Sales Account identisch.

### Customer Service

Customer Service pflegt:

```text
Contact
Service Account
Case Participant
Communication Preference
Escalation Status
```

Ein Service Account kann mehrere Contacts bedienen.

### Operations

Operations pflegt:

```text
Delivery Recipient
Installation Location
Service Location
Operational Status
Fulfilment Relationship
```

Eine Delivery Location muss weder dem Legal Debtor noch dem Commercial Account entsprechen.

Ein schwacher zentraler Ansatz erzeugt eine universelle `Customer`-Definition und zwingt jede Domain, sie zu verwenden. Der Begriff wird dadurch weit genug, um akzeptiert zu werden, aber zu unpräzise für korrekte Entscheidungen.

Ein schwacher verteilter Ansatz lässt jede Domain unverbundene Metadaten ohne Mappings veröffentlichen. Search findet mehrere Begriffe, User können ihre Beziehungen jedoch nicht bestimmen.

Ein föderiertes Design erhält lokale Konzepte und ergänzt freigegebene Beziehungen:

```text
Sales Account
mappt auf Enterprise Concept Party in Commercial Role

Finance Debtor
mappt auf Enterprise Concept Party in Financial Obligation

Service Contact
mappt auf Enterprise Concept Person or Organization in Service Interaction

Delivery Recipient
mappt auf Enterprise Concept Party in Fulfilment Role
```

Die zentrale Plattform speichert oder indexiert:

- kanonische Identitäten;
- Cross-Domain Mappings;
- Synonyme;
- Beziehungstypen;
- Approval Status;
- Provenance;
- Effective Dates;
- bekannte Nichtgleichheiten.

Die Domains behalten ihre Definitionen und Beispiele.

Damit lassen sich domainübergreifende Fragen beantworten, ohne Bedeutung zu löschen:

- Welche Sales Accounts besitzen Finance Debtors?
- Welche Service Contacts gehören zu einem Account?
- Welche Delivery Locations sind mit einer Legal Entity verbunden?
- Welche Reports behandeln Account, Debtor und Recipient fälschlich als denselben Grain?
- Welche Data Products verwenden das Enterprise Concept, implementieren aber unterschiedliche lokale Rollen?

Die Architektur koordiniert Semantik. Sie behauptet nicht, dass jeder lokale Begriff identisch ist.

## Resilienz, Latenz, Duplikation und Integrationskosten sind Trade-offs

Jedes Muster bezahlt Konsistenz an einer anderen Stelle.

### Resilienz

Ein zentrales Repository vereinfacht Backup und Monitoring, erzeugt aber eine zentrale Abhängigkeit.

Eine verteilte Architektur erlaubt Domain-Autonomie, benötigt für Enterprise Queries jedoch viele Services.

Eine föderierte Architektur sollte Degraded Operation definieren:

- zentrale Search nicht verfügbar;
- ein Domain Endpoint nicht verfügbar;
- gecachte Metadaten veraltet;
- Approval Service nicht verfügbar;
- Policy Distribution verzögert;
- Source Reference nicht auflösbar.

Controls zum Schutz von Daten sollten nach expliziter Policy fehlschlagen und nicht nach zufälligem Connector-Verhalten.

### Latenz

Zentrale Kopien erzeugen Synchronisierungsverzögerung.

On-Demand Queries erzeugen Request-Latenz und Quellabhängigkeit.

Hybride Designs können trennen:

```text
schnelle zentrale Discovery
+
quellnative aktuelle Details
+
eventbasierte Updates für kritische Änderungen
```

Nicht jede Metadatenart benötigt dieselbe Freshness.

Ein Business Term kann tägliche Synchronisierung tolerieren. Eine entzogene Access Rule kann sofortige Propagation benötigen. Ein Runtime Status sollte live bleiben. Eine Schemaänderung kann Event-Driven Detection vor Publication erfordern.

### Duplikation

Duplikation ist nicht automatisch schlecht.

Ein Search Cache kann wertvoll sein, wenn er explizit als Kopie mit Freshness und Provenance gekennzeichnet ist. Duplikation wird gefährlich, wenn kopierter Inhalt unabhängig editiert werden kann oder ohne Authority Status angezeigt wird.

Eine sichere Kopie beantwortet:

```text
Woher stammt dieser Wert?
Wann wurde er beobachtet?
Ist er autoritativ?
Kann er hier editiert werden?
Wann wird er aktualisiert?
Was passiert bei einer Quelländerung?
```

### Integrationskosten

Zentralisierung bezahlt Integrationskosten bei Ingestion und Normalisierung.

Distribution bezahlt Integrationskosten bei jeder föderierten Query und User Interaction.

Federation ergänzt organisatorische Contract-Kosten:

- Domain-Rollen;
- Standards;
- Review-Pflichten;
- Service Levels;
- Eskalation;
- Change Management.

Die günstigste Architektur auf einem Schaubild kann im Betrieb die teuerste sein.

## Die einfachste tragfähige Umsetzung hängt von der Organisationsgröße ab

Architektur sollte mit dem kleinsten Modell beginnen, das reale Anforderungen erfüllt.

### Kleines Team: quellnative Metadaten plus ein Search Index

Ein kleines Team mit wenigen Systemen benötigt selten sofort ein vollständiges Metadata Mesh.

Ein praktisches Minimum ist:

1. Technische Definitionen verbleiben in Source DDL, Transformation Repositories und Semantic Models.
2. Fachliche Definitionen und Ownership werden an einem kontrollierten Ort gepflegt.
3. Ein minimaler durchsuchbarer Index wird automatisiert erfasst.
4. Kanonische Identifier und Source References werden zentral gespeichert.
5. Nur die Relationships werden ergänzt, die für aktuelle Discovery- und Impact-Fragen benötigt werden.
6. Synchronisierungs-Freshness wird gemessen.
7. Direkte Links führen zu quellnativen Details.
8. Pro Metadatenkategorie wird eine accountable Person oder Rolle benannt.

Dieses Muster vermeidet verfrühte Plattformkomplexität.

### Wachsende Organisation: föderierter Catalog mit Domain Ownership

Wenn Domains und Data Products wachsen, ergänze:

- verpflichtende Enterprise-Metadatenfelder;
- Domain-Steward-Queues;
- kontrollierte Vocabulary Mappings;
- gemeinsame Klassifikationstaxonomie;
- zentrales Approval für Cross-Domain Terms und Policies;
- Relationship- und Lineage-Historie;
- veröffentlichte APIs;
- Connector Ownership und Service Levels.

Das zentrale Team sollte Plattform und Standards betreiben, nicht jede Domain-Definition schreiben.

### Große Organisation: Metadatenplattform plus Domain Products

Eine große Organisation kann benötigen:

- verteilte Domain-Metadatenservices;
- zentrale Identity Resolution;
- eventbasierten Metadatenaustausch;
- Shared Policy Distribution;
- Cross-Domain Graph und Search;
- eingeschränkte Specialist Stores für Security- und AI-Metadaten;
- versionierte Contracts;
- Delegated Administration;
- Platform Reliability Objectives;
- messbare Domain-Pflichten.

Auch dieses Design sollte vermeiden, dass eine Enterprise-Plattform zum Authoring Tool für jedes quellenspezifische Detail wird.

## Die einfachste tragfähige Metadatenarchitektur wählen

Ein Entscheidungsweg sollte mit Systemen, Domains und Betriebsfähigkeit beginnen — nicht mit einer Produktkategorie.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/centralized-federated-or-distributed-metadata-img4-de.png"
        alt="Entscheidungsweg von Anzahl der Systeme, verfügbaren Maintainers, Lineage-Bedarf, zentralem Enforcement, Freshness und Zuverlässigkeit der Source APIs zu source-native, zentralem Index, föderiertem Catalog oder Enterprise Active Metadata Platform"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Wähle die am wenigsten komplexe Architektur, die Discovery-, Ownership-, Lineage-, Freshness- und Enforcement-Anforderungen erfüllt. Jedes Ergebnis besitzt einen passenden Kontext und eine spezifische Warnung.
    </figcaption>
</figure>

### Source-native only

Geeigneter Kontext:

- wenige Systeme;
- ein Team;
- geringer Bedarf an Cross-System Discovery;
- Metadaten werden direkt in Code und Source Platforms gepflegt.

Warnung:

- Relationships und gemeinsame Definitionen können über Tools hinweg unsichtbar bleiben.

Nutze dieses Ergebnis bewusst und dokumentiere, wo User Metadaten finden.

### Lightweight Central Index

Geeigneter Kontext:

- mehrere Systeme;
- gemeinsame Search ist erforderlich;
- Quellsysteme bleiben autoritativ;
- domainübergreifende Controls sind noch begrenzt.

Warnung:

- References und gecachte Werte benötigen Freshness-, Availability- und Ownership-Status.

Dies ist häufig die beste erste zentrale Fähigkeit.

### Federated Catalog

Geeigneter Kontext:

- mehrere Business Domains;
- Domain-Teams können Kontext pflegen;
- Shared Vocabulary und Lineage werden benötigt;
- Enterprise Standards müssen mit lokaler Accountability koexistieren.

Warnung:

- Federation ohne Contracts, Steward Capacity und Escalation wird zu fragmentierter Dokumentation.

Dies ist das häufige Zielbild für reife Data-Product-Organisationen.

### Enterprise Active Metadata Platform

Geeigneter Kontext:

- viele Systeme und Domains;
- Policy-, Quality-, Lineage- und Lifecycle-Controls hängen von Metadaten ab;
- nahezu direkte Propagation wird benötigt;
- APIs und Events müssen viele Plattformen integrieren.

Warnung:

- Automation verstärkt falsche Metadaten, wenn Evidenz, Approval und Exception Handling schwach sind.

Eine Active Platform sollte das Ergebnis bewährter Governance sein und kein Ersatz dafür.

## Häufige Anti-Patterns

### Ein zentraler Catalog als einziger erlaubter Metadatenort

Dadurch werden Metadaten aus Code, Systemen und Teams entfernt, die sie korrekt pflegen können.

### Alles kopieren, weil Storage günstig ist

Storage-Kosten sind nicht das primäre Problem. Autorität, Freshness, Sensitivität und Lifecycle sind entscheidend.

### Jede Domain definiert alles unabhängig

Lokale Autonomie ohne gemeinsame Identifier, Contracts und Vocabulary verhindert Enterprise Discovery und Control.

### Zentrale Governance schreibt Domain-Definitionen

Ein zentrales Team kann Standards definieren und Konfliktlösung moderieren. Es kann Domain-Wissen normalerweise nicht ersetzen.

### Zentrale Search ohne Source Health

Ein Ergebnis, das auf eine nicht verfügbare oder gelöschte Quelle verweist, darf nicht gesund aussehen.

### Editierbare Kopien auf beiden Seiten

Bidirektionales Authoring ohne Feldautorität und Konfliktregeln erzeugt Synchronisierungsschleifen.

### Real-Time-Architektur für statische Metadaten

Nicht jede Definition benötigt Streaming. Unnötige Echtzeitintegration erhöht Betriebskosten, ohne Entscheidungen zu verbessern.

### Nächtliche Synchronisierung für zugriffskritische Policies

Ein Control, das sofort reagieren muss, darf nicht von einem langsamen zentralen Refresh abhängen.

### Metadata Mesh ohne Metadata Products

Domain Ownership ohne APIs, Contracts, Service Levels, Finanzierung und Observability ist organisatorische Delegation ohne Fähigkeit.

### Zentrale Plattform ohne accountable Content Owner

Platform Ownership ist nicht gleich Metadata Ownership.

### Verteilte Queries ohne Timeout- und Fallback-Design

Eine föderierte Oberfläche, die unbegrenzt auf mehrere Quellsysteme wartet, ist nicht resilient.

### Veraltete Kopien hinter einem Preferred Value verstecken

Die bevorzugte Sicht muss weiterhin Quelle, Autorität, Freshness und Konflikte offenlegen.

## Entscheidungshilfe

Nutze die folgenden Fragen vor der Auswahl einer Architektur.

| Frage | Architektonische Auswirkung |
| --- | --- |
| Wie viele Systeme und Domains sind im Scope? | Ein kleiner Scope kann source-native bleiben; ein breiter Scope benötigt meist zentralen Index und gemeinsame Identity. |
| Wer kann die Metadaten korrekt pflegen? | Authoring und Accountability sollten bei dem Team bleiben, das das notwendige Wissen besitzt. |
| Wird Cross-Domain Discovery benötigt? | Zentrale Search, kanonische Identifier und Relationship Indexing einführen. |
| Wird Cross-Domain Lineage benötigt? | Normalisierte Relationship Edges und Versionen persistieren oder austauschen. |
| Werden Enterprise Policies zentral freigegeben? | Policy Authority zentralisieren, aber in Plattformen mit passender Latenz enforcen. |
| Wie aktuell muss jede Kategorie sein? | Pro Kategorie Stored, Cached, Event-Driven oder On-Demand wählen. |
| Sind Source APIs zuverlässig und unterstützt? | Verlässliche APIs ermöglichen References und Distributed Queries; schwache APIs rechtfertigen Caching oder Ingestion. |
| Wird historische Rekonstruktion benötigt? | Versionen zentral speichern, wenn Quellen nur aktuellen State liefern. |
| Sind Metadaten selbst sensitiv? | Zentrale Kopien einschränken und Zusammenfassungen oder References bereitstellen. |
| Können Domains Service-Verpflichtungen erfüllen? | Federation benötigt benannte Rollen, Kapazität, Quality Expectations und Eskalation. |
| Was geschieht bei Source- oder Plattformausfall? | Degradation, Fallback und Fail-Safe Behaviour definieren. |
| Welche Werte triggern automatisierte Controls? | Explizite Authority, Approval State, Provenance und Exception Handling verlangen. |

Die Entscheidung sollte pro Metadatenfähigkeit und -kategorie getroffen werden. Eine Organisation kann alle vier Ergebnisse gleichzeitig sinnvoll einsetzen.

Beispiel:

```text
Source-native only:
detaillierte Transformationskonfiguration

Lightweight Central Index:
technische Assets und Source References

Federated Catalog:
Definitionen, Ownership und Data-Product-Kontext

Enterprise Active Metadata:
freigegebene Klassifikationen und Access-Control-Signale
```

Eine hybride Architektur ist kein Kompromiss. Sie ist meistens die korrekte Abbildung unterschiedlicher Autoritäts- und Latenzanforderungen.

## Zentrale Empfehlungen

1. Behandle Metadatenarchitektur nicht als binäre Entscheidung zwischen einem Catalog und vollständiger Dezentralisierung.
2. Trenne Authoring, Storage, Discovery, Approval und Enforcement für jede wichtige Metadatenkategorie.
3. Zentralisiere Enterprise Discovery, kanonische Identity und Cross-Domain Relationships dort, wo sie gemeinsamen Nutzen erzeugen.
4. Halte Authoring und Accountability nah an den Domains und Systemen, die Korrektheit erhalten können.
5. Speichere Enterprise-owned Records und Historie zentral. Cache source-owned Details nur mit Freshness und Provenance.
6. Verwende stabile References für spezialisierte oder sensitive Metadaten, die nicht breit kopiert werden sollten.
7. Nutze Query on Demand nur bei belastbaren Source APIs, Latenzen, Authorization und Fallback Behaviour.
8. Definiere Enterprise-Mindeststandards, ohne legitime Domain Extensions zu löschen.
9. Kennzeichne jede Kopie mit Authority, Source, Collection Time, Expected Refresh und Editability.
10. Designe für Source-Ausfälle, stale Caches, broken References und Ausfall der zentralen Plattform.
11. Richte den Enforcement-Ort an benötigter Latenz und Failure Policy aus.
12. Behandle Federation als Operating Contract mit Rollen, Service Levels, Review-Pflichten und Eskalation.
13. Verwende Metadata-Mesh-Muster nur, wenn Domains verlässliche Metadata Products veröffentlichen können.
14. Beginne mit der einfachsten tragfähigen Architektur und ergänze Distribution oder Active Control nur für nachgewiesenen Bedarf.
15. Verhindere, dass die zentrale Plattform zur ungepflegten zweiten Wahrheit wird, indem Ownership, Freshness und ungelöste Konflikte gemessen werden.

## Der nächste Schritt: Native Metadaten im gesamten Data Stack nutzen

Dieser Teil hat definiert, wo Metadatenfähigkeiten und Verantwortungen betrieben werden sollten.

Die nächste Frage ist, wie jede Plattform im Data Stack daran teilnehmen soll.

Databases, Transformation Repositories, Orchestrierungsplattformen, Semantic Models, BI Tools, Identity-Systeme, Observability Services und AI-Plattformen stellen unterschiedliche native Metadaten bereit. Einige sollten autoritativ bleiben. Einige sollten Events veröffentlichen. Andere sollten freigegebene Klassifikationen, Ownership oder Policy Decisions empfangen.

Der nächste Teil, **Native Metadaten im gesamten Data Stack**, untersucht, wie diese nativen Fähigkeiten genutzt werden können, ohne dieselben Metadaten manuell in jedem Produkt neu aufzubauen und ohne die hier etablierte zentrale Discovery und föderierte Accountability zu verlieren.
