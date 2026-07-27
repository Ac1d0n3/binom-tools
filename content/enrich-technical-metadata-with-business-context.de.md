---
title: Technische Metadaten mit fachlichem Kontext anreichern — Strukturen mit Begriffen, Verantwortung, Regeln und realer Nutzung verbinden
description: Eine praxisnahe Methode, um erfasste Schemas und Felder mit Fachbegriffen, KPIs, Data Products, verantwortlichen Rollen, Nutzungsgrenzen, Policies, Evidenz und Freigabehistorie zu verbinden.
category: Data Governance
tags:
  - metadata
  - metadata-enrichment
  - metadata-governance
  - data-catalog
  - business-glossary
  - data-products
  - data-stewardship
  - metadata-provenance
  - kpi-governance
  - data-quality
  - semantic-layer
  - active-metadata
  - ai-ready-metadata
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 6
seriesTitle: MetaData Deep Dive
hero: images/playbooks/enrich-technical-metadata-with-business-context-hero.png
publishedAt: 2026-06-25 10:00
---

## Ein gescanntes Schema ist noch keine fachliche Metadatenbasis

Automatisiertes Harvesting kann erkennen, dass eine Tabelle namens `fct_sales_order_line` existiert. Es kann ihre Felder, Datentypen, Constraints, Lineage, Refresh-Historie und Consumer erfassen. Profiling kann zeigen, dass `net_sales_amount` Dezimalwerte enthält, `booking_date` wie ein Datum aussieht und `currency_code` eine kleine Menge dreistelliger Werte besitzt.

Diese Beobachtungen sind nützlich. Sie beantworten jedoch nicht die Fragen, die darüber entscheiden, ob jemand die Daten korrekt verwenden kann:

- Repräsentiert `net_sales_amount` bestellten, fakturierten oder realisierten Umsatz?
- Welche Rabatte, Steuern, Stornierungen und Gutschriften sind enthalten?
- Ist `booking_date` das Anlage-, Annahme- oder Buchungsdatum?
- Welche fachliche Domain verantwortet die Bedeutung?
- Welcher Data Steward gibt Änderungen frei?
- Welcher KPI und welches Data Product verwenden das Feld?
- Welche Nutzungen sind erlaubt, ungeeignet oder ausdrücklich verboten?
- Welche Einschränkungen sind bekannt?
- Wurde die Definition von einer Person geliefert, von einem Modell abgeleitet oder aus einem anderen System importiert?
- Wurde die Anreicherung geprüft und freigegeben?

Ein technisch vollständiger Katalog kann deshalb semantisch schwach bleiben.

> **Technische Metadaten werden erst dann entscheidungsfähig, wenn Strukturen mit Begriffen, Verantwortung, fachlicher Nutzung, Regeln, Evidenz und Freigabe verbunden werden.**

Anreicherung bedeutet nicht, einem Feld lediglich einen freundlicheren Namen zu geben. Sie bedeutet, um ein Asset kontrolliert ein vertrauenswürdiges Metadatenprofil aufzubauen.

## Anreicherung verbindet unterschiedliche Arten von Evidenz

Ein belastbares Profil entsteht in mehreren Schichten. Jede Schicht liefert eine andere Wissensart und besitzt eine andere Autorität.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/enrich-technical-metadata-with-business-context-img1-de.png"
        alt="Mehrstufiger Pfad von technischen Metadaten über Profiling, Fachvokabular, Ownership, Nutzungs- und Policy-Kontext zu einem vertrauenswürdigen Metadatenprofil"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Profiling und Detection können Evidenz und Vorschläge erzeugen. Fachliche Bedeutung, Verantwortlichkeit und governte Nutzung benötigen explizite Ownership und Freigabe.
    </figcaption>
</figure>

### Technische Metadaten

Technische Metadaten beschreiben das implementierte Objekt:

- nativer Name und Identifier
- Quellsystem und Umgebung
- Asset-Typ
- Schema-, Tabellen- und Feldhierarchie
- Datentyp und Format
- Nullability, Keys und Constraints
- Lineage- und Transformationsreferenzen
- Refresh-, Runtime- und Nutzungsbeobachtungen

Diese Schicht belegt, was existiert und wie es sich technisch verhält. Sie etabliert jedoch nicht automatisch die fachliche Bedeutung.

### Profiling und Detection

Profiling kann beobachtete Evidenz ergänzen:

- Wertemuster
- Minimum, Maximum und Verteilungen
- Null- und Distinct-Raten
- repräsentative Beispiele
- wahrscheinliche Identifier
- wahrscheinliche Datums-, Währungs- oder Ländercodes
- mögliche Kategorien sensitiver Daten
- Ähnlichkeit zu bereits freigegebenen Assets

Diese Signale können die Anreicherung beschleunigen. Sie müssen von freigegebenen Fakten unterscheidbar bleiben.

Ein Feld mit Werten wie `EUR`, `USD` und `GBP` ist wahrscheinlich ein Währungscode. Damit ist noch nicht geklärt, ob es sich um Document Currency, lokale Buchungswährung oder Reporting Currency handelt. Ein Name wie `customer_id` kann einem freigegebenen Glossarbegriff ähneln, obwohl der Identifier nur innerhalb eines Tenants gilt und als unternehmensweiter Customer Key ungeeignet ist.

### Fachvokabular

Fachvokabular ergänzt die semantische Interpretation:

- Business Name
- Definition
- Synonyme und Abkürzungen
- Domain und Subdomain
- verwandte Glossarbegriffe
- Berechnungskonzept
- Business Events und Zustände
- Abgrenzung zu ähnlichen Konzepten

Das Ziel ist nicht, den physischen Namen zu ersetzen. Das Ziel ist, das implementierte Asset mit dem Vokabular zu verbinden, das für sein Verständnis erforderlich ist.

### Ownership und Verantwortlichkeit

Ein vertrauenswürdiges Profil identifiziert verantwortliche Rollen:

- Business Owner für Bedeutung und fachliche Akzeptanz
- Data Steward für Definitionsqualität und laufende Prüfung
- Technical Owner für Implementierung und Betrieb
- Data Product Owner für Entscheidungen auf Produktebene
- Policy Owner für konkrete Governance-Anforderungen

In einem kleinen Team kann eine Person mehrere Rollen übernehmen. Die Verantwortlichkeiten sollten trotzdem explizit bleiben.

### Nutzungs- und Policy-Kontext

Fachliche Bedeutung bleibt ohne Nutzungsgrenzen unvollständig:

- vorgesehene analytische Nutzung
- verbotene oder ungeeignete Nutzung
- bekannte Einschränkungen
- freigegebene Consumer
- konsumierende Reports, semantische Modelle und AI Use Cases
- anwendbare Sensitivitäts-, Retention- und Access-Regeln
- erforderliche Quality Controls
- Zertifizierungs- oder Freigabestatus

Ein Feld kann korrekt definiert und trotzdem für eine bestimmte Entscheidung ungeeignet sein. Auftragseingang kann beispielsweise für operatives Vertriebsreporting geeignet, aber für realisierten Umsatz ungeeignet sein.

## Ein vertrauenswürdiges Metadatenprofil benötigt mehr als eine Beschreibung

Eine narrative Definition ist zentral. Sie sollte jedoch durch strukturierte Beziehungen und governte Attribute ergänzt werden.

Für ein fachlich relevantes Feld kann ein praktisches Profil wie folgt aussehen:

```yaml
asset:
  id: warehouse.prod.fct_sales_order_line.net_sales_amount
  type: column
  technical_name: net_sales_amount
  native_type: decimal(18,2)

business_context:
  business_name: Net Sales Amount
  definition: >
    Nettowert einer akzeptierten Sales-Order-Position nach freigegebenen
    positionsbezogenen Rabatten und vor Steuern, Versand und späteren Gutschriften.
  domain: Sales
  data_product: Sales Performance
  synonyms:
    - net order value
    - net sales value

relationships:
  implements_term: glossary.net_revenue
  contributes_to_kpi:
    - kpi.monthly_net_revenue
  consumed_by:
    - report.sales_performance_monthly
  governed_by:
    - policy.confidential_commercial_data
  validated_by:
    - quality_rule.valid_reporting_currency

accountability:
  business_owner: role.sales_operations_owner
  steward: role.sales_data_steward
  technical_owner: team.commercial_data_platform

use_context:
  intended_use:
    - Auftragseingangsreporting
    - operative Vertriebsanalyse
  prohibited_use:
    - Reporting für realisierten Umsatz
    - gesetzliches Steuerreporting
  known_limitations:
    - spätere Billing-Gutschriften bleiben im Billing-Modell

provenance:
  definition_source: steward_entry
  supplied_by: user:steward-184
  supplied_at: 2026-07-20T09:15:00Z
  approved_by: role.sales_data_owner
  approved_at: 2026-07-21T14:30:00Z
  approval_status: approved
  effective_version: 4
```

Die konkreten Feldnamen können je Plattform variieren. Die Designprinzipien sollten gleich bleiben:

- die technische Identität bleibt stabil;
- semantische Links werden als explizite Beziehungen dargestellt;
- Rollen werden getrennt von Freitext abgebildet;
- vorgesehene und verbotene Nutzung werden gemeinsam erfasst;
- Provenance und Freigabe sind Metadaten erster Klasse;
- das aktuelle Profil wird versioniert und nicht still überschrieben.

## Assets, Begriffe, KPIs und Data Products als Graph verbinden

Metadatenanreicherung wird wertvoller, wenn Kontext verlinkt statt in isolierte Textfelder kopiert wird.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/enrich-technical-metadata-with-business-context-img2-de.png"
        alt="Metadatengraph, der den Business Term Net Revenue, den KPI Monthly Net Revenue, das Data Product Sales Performance, eine Sales-Faktentabelle, Felder, Owner, Reports, Policies und Quality Rules verbindet"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Explizite Beziehungstypen bewahren den Unterschied zwischen Business Term, KPI, Data Product, implementierter Tabelle, beitragenden Feldern und den umgebenden Controls.
    </figcaption>
</figure>

Betrachten wir die folgende Kette:

```text
Business Term: Net Revenue
↕
KPI: Monthly Net Revenue
↕
Data Product: Sales Performance
↕
Tabelle: fct_sales_order_line
↕
Felder: net_sales_amount, booking_date, currency_code
```

Diese Objekte hängen zusammen. Sie sind jedoch nicht austauschbar.

Der Business Term definiert das Konzept. Der KPI definiert eine governte Berechnung und ein Reporting-Zeitfenster. Das Data Product definiert eine bereitgestellte Fähigkeit mit Ownership und Service-Erwartungen. Die Tabelle implementiert einen Teil dieses Produkts. Die Felder liefern Werte sowie Zeit- oder Währungskontext.

Beziehungstypen sollten diese Unterschiede sichtbar machen:

```text
Feld implementiert Business Term
KPI wird aus Feldern berechnet
Asset gehört zu Data Product
Data Product wird von Report konsumiert
Asset wird durch Policy geregelt
KPI wird durch Quality Rule validiert
Data Product gehört zu verantwortlicher Rolle
```

Dieser Graph unterstützt mehrere praktische Fragen:

- Welche physischen Felder implementieren den Enterprise Term `Net Revenue`?
- Welche KPIs verwenden `net_sales_amount`?
- Welche Reports sind betroffen, wenn sich die Felddefinition ändert?
- Wer muss eine Änderung freigeben?
- Welche Policy und welche Quality Rules gelten?
- Verwendet ein anderes Data Product denselben Begriff mit einer abweichenden Berechnung?

Dieselbe Definition in jedes Objekt zu kopieren erschwert diese Fragen. Eine Beziehung kann traversiert, governed und unabhängig geändert werden.

## Name Matching ist ein Vorschlag und keine Freigabe

Automatisches Matching ist für Skalierung wertvoll. Gleichzeitig ist es eine der einfachsten Möglichkeiten, falsche semantische Sicherheit zu erzeugen.

Ein Matcher kann folgende Signale verwenden:

- exakte und unscharfe Namensähnlichkeit
- Beschreibungen
- Kontext des Quellsystems
- Datenprofile
- Lineage-Nachbarn
- vorhandene freigegebene Mappings
- Domain-Zugehörigkeit
- gemeinsame Nutzung in Reports
- Embedding-Ähnlichkeit

Das Ergebnis sollte als Vorschlag mit Evidenz dargestellt werden:

```yaml
suggestion:
  proposed_relationship: implements_term
  target: glossary.net_revenue
  source: semantic_matcher_v3
  confidence: 0.87
  status: proposed
  evidence:
    - name similarity: net_sales_amount
    - profile: decimal monetary values
    - lineage neighbour: currency_code
    - similar approved asset: mart_sales.net_order_value
```

Ein Confidence Score ist kein Freigabestatus.

Hohe Confidence kann eine Priorisierung oder automatische Zuordnung zu einer Review Queue rechtfertigen. Sie sollte eine abgeleitete Beziehung nicht still in ein freigegebenes Enterprise Mapping umwandeln, sofern keine eng definierte Governance-Regel genau diese Aktion ausdrücklich erlaubt.

## Profiling unterstützt Bedeutung, liefert sie aber nicht

Profiling ist besonders nützlich, wenn technische Namen schwach oder undokumentiert sind.

Ein Feld namens `AMT_17` könnte beispielsweise Folgendes zeigen:

```text
98,7 % gefüllt
Wertebereich: -125.000,00 bis 2.400.000,00
häufige Werte: 0,00, 49,99, 99,00
gekoppelt mit: CURR_CD
verwendet in: Sales Margin Report
Lineage aus: Order Line Pricing
```

Diese Evidenz deutet stark auf einen monetären Geschäftswert hin. Sie beantwortet trotzdem nicht:

- brutto oder netto?
- bestellt, fakturiert oder realisiert?
- welche Rabattklassen?
- welches Umrechnungsdatum?
- welche Behandlung von Retouren oder Stornierungen?
- welche zulässige Nutzung?

Repräsentative Werte können Anomalien sichtbar machen und Reviews unterstützen. Sie sollten gesampelt, geschützt und als Beispiele statt als Definitionen gekennzeichnet werden. Sensitive Werte können Masking, Tokenisierung oder synthetische Beispiele erfordern.

Die richtige Regel lautet:

> **Maschinen können beobachten, vergleichen und vorschlagen. Verantwortliche Menschen geben Bedeutung und Nutzung frei.**

## Die einfachste tragfähige Anreicherung

Ein Team benötigt keinen vollständigen Enterprise Knowledge Graph, bevor es starten kann. Ein sinnvoller Minimalansatz lässt sich rund um ein wichtiges Data Product implementieren.

### 1. Einen begrenzten Scope auswählen

Wähle ein Data Product, eine Domain oder einen Reporting-Prozess, bei dem Mehrdeutigkeit reale Kosten verursacht. Beziehe die wichtigsten Tabellen, Felder, KPIs und Consumer ein.

Ein begrenzter Scope macht Ownership, Review-Kapazität und Erfolgskriterien sichtbar.

### 2. Die technische Basis harvesten

Erfasse stabile Asset-Identifier, Hierarchie, Typen, Lineage und aktuelle Consumer. Data Stewards sollten keine Fakten manuell rekonstruieren müssen, die Systeme bereits liefern.

### 3. Ein minimales fachliches Profil definieren

Fordere für relevante Assets eine kleine Menge verpflichtender Attribute:

```text
Business Name
fachliche Definition
Domain
Owner
Steward
vorgesehene Nutzung
verbotene Nutzung
bekannte Einschränkungen
Freigabestatus
```

Zusätzliche Beziehungen können bei Bedarf ergänzt werden:

```text
Glossarbegriff
KPI
Data Product
Policy
Quality Rule
Report oder Consumer
```

### 4. Vorschläge mit Evidenz erzeugen

Nutze Namen, Profile, Lineage und vorhandene freigegebene Beispiele, um wahrscheinliche Begriffe, Klassifikationen, Owner und ähnliche Assets vorzuschlagen.

Jeder Vorschlag sollte Folgendes enthalten:

```text
Quelle
Confidence
Evidenz
Status
Erstellungszeit
```

### 5. Nach Ausnahme und Business Impact prüfen

Priorisiere wirkungsstarke oder mehrdeutige Assets, anstatt für jedes Feld denselben manuellen Aufwand zu erzwingen.

Typische Prioritätssignale sind:

- Nutzung für Management- oder regulatorische Entscheidungen
- hohe Anzahl an Consumern
- sensitive Daten
- widersprüchliche Mappings
- häufige Änderungen
- schwache Beschreibungen
- fehlgeschlagene Quality Checks
- Nutzung in AI oder automatisierten Entscheidungen

### 6. Freigeben und versionieren

Erfasse, wer die Anreicherung freigegeben hat, wann sie wirksam wurde und welche vorherige Version sie ersetzt. Abgelehnte Vorschläge sollten für Audit und die Verbesserung zukünftiger Matcher erhalten bleiben.

### 7. Dort veröffentlichen, wo User arbeiten

Stelle freigegebenen Kontext in Catalog Search, Data-Product-Seiten, Semantic-Modelling-Workflows, BI-Entwicklung, Quality Review und AI Retrieval bereit. Anreicherung, die nur in einer versteckten Governance-Maske existiert, besitzt begrenzten operativen Wert.

## Ein praktischer Steward-Enrichment-Workflow

Stewardship sollte als wiederholbarer Workflow gestaltet werden und nicht als offene Aufforderung, „die Daten zu dokumentieren“.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/enrich-technical-metadata-with-business-context-img3-de.png"
        alt="Steward-Workflow vom geharvesteten Asset über erzeugte Vorschläge, Domain-Zuordnung, Definition, Verknüpfung von Begriffen und Regeln, Beispielprüfung, Freigabe und Veröffentlichung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Vorschläge beschleunigen Reviews, wenn Quelle, Confidence und Status sichtbar bleiben. Eine Freigabe überführt Proposals in governte Metadaten, ohne abgelehnte Evidenz zu löschen.
    </figcaption>
</figure>

Eine praktische Reihenfolge lautet:

```text
Asset harvesten
→ Vorschläge erzeugen
→ Domain zuweisen
→ Steward auswählen
→ Definition ergänzen oder bestätigen
→ Begriffe und Regeln verknüpfen
→ Beispiele prüfen
→ Freigeben
→ Veröffentlichen
```

### Asset harvesten

Der Workflow beginnt mit einem realen technischen Objekt und einer stabilen Identität. Zuerst einen unverknüpften Dokumentationseintrag anzulegen erhöht Duplicate- und Matching-Risiken.

### Vorschläge erzeugen

Mögliche Proposals sind:

- wahrscheinlicher Business Term
- wahrscheinliche Kategorie sensitiver Daten
- wahrscheinlicher Business Owner oder Steward
- ähnliches freigegebenes Asset
- wahrscheinliches Data Product
- wahrscheinliche Quality Rule

Jeder Vorschlag benötigt Quelle, Confidence und Status.

### Domain und Steward zuweisen

Die Domain-Zuordnung bestimmt den Review-Kontext. Der ausgewählte Steward muss über ausreichendes Wissen oder einen klaren Eskalationsweg verfügen.

Routing ausschließlich nach technischer Plattform reicht meist nicht aus. Ein Warehouse Administrator kann wissen, wo ein Feld gespeichert ist, ohne seine fachliche Bedeutung zu kennen.

### Definition ergänzen oder bestätigen

Der Steward kann eine vorgeschlagene Definition akzeptieren, bearbeiten oder ablehnen. Der Workflow sollte ungelöste Punkte wie fehlende Nutzungsgrenzen oder mehrdeutige Zeitsemantik hervorheben.

### Begriffe und Regeln verknüpfen

Beziehungen sollten bewusst ausgewählt werden. Eine Begriffsverknüpfung, ein KPI-Beitrag und eine Policy-Zuweisung stellen unterschiedliche Aussagen dar und können unterschiedliche Approver benötigen.

### Beispiele prüfen

Beispiele helfen, die Interpretation zu verifizieren. Sampling-Quelle, Masking-Status und Beobachtungszeit sollten sichtbar sein.

### Freigeben und veröffentlichen

Die Freigabe dokumentiert die verantwortliche Rolle und effektive Version. Die Veröffentlichung macht das Ergebnis auffindbar und für nachgelagerte Systeme nutzbar.

## Bulk Enrichment sollte Wiederholung reduzieren, nicht Verantwortlichkeit

Große Umgebungen benötigen Bulk Operations. Ohne sie verbringen Data Stewards Zeit damit, offensichtlichen Kontext über Hunderte ähnlicher Felder zu wiederholen. Schlecht governte Bulk Updates können jedoch einen Fehler über die gesamte Datenlandschaft verteilen.

Sinnvolle Bulk Operations sind beispielsweise:

- eine Domain auf Assets unterhalb eines governten Namespace anwenden;
- einen Steward für einen definierten Data-Product-Scope zuweisen;
- ein Glossary Mapping für Felder mit identischem Lineage-Muster vorschlagen;
- eine gemeinsame Policy auf bestätigte sensitive Felder anwenden;
- Intended-Use-Text von einem Data Product auf interne Assets vererben;
- ein freigegebenes Definitions-Template wiederverwenden und Asset-spezifische Ausnahmen verpflichtend machen;
- identische technische Kopien als Referenzen auf eine autoritative fachliche Definition markieren.

Eine Bulk Action sollte ihren exakten Scope vor der Ausführung anzeigen:

```text
Auswahlregel
Anzahl der Assets
betroffene Umgebungen
hinzuzufügende oder zu ersetzende Attribute
Konflikte
aktuelle freigegebene Werte
erforderlicher Approver
Rollback-Version
```

Bulk Enrichment sollte keine universelle Last-Write-Wins-Regel verwenden.

Ein sicheres Muster trennt drei Aktionen:

```text
In Bulk vorschlagen
→ Konflikte und Ausnahmen prüfen
→ ausgewählte Änderungen freigeben
```

Für Attribute mit geringem Risiko kann eine freigegebene Regel automatische Propagation erlauben. Bei semantischen Definitionen, verbotener Nutzung, Ownership oder Policy-Klassifikation ist Review normalerweise angemessener.

## Provenance für jede Anreicherung erhalten

Ein Metadatenwert ohne Provenance lässt sich bei Konflikten nur schwer bewerten.

Für jede Anreicherung sollten mindestens folgende Informationen erfasst werden:

- Wert
- Attributtyp
- Quellsystem oder Workflow
- Source Object
- geliefert von
- Lieferzeit
- Methode: deklariert, importiert, erkannt, inferiert oder abgeleitet
- Confidence, sofern relevant
- Freigabestatus
- freigegeben von
- Freigabezeit
- effektives Intervall oder Version
- Grund für Ablehnung oder Override

Damit kann die Plattform unterscheiden zwischen:

```text
importierter Source-Beschreibung
erkanntem PII-Vorschlag
vom Steward bearbeiteter Definition
vom Domain Owner freigegebenem Enterprise Mapping
vom Data Product geerbter Policy
lokaler Ausnahme mit freigegebenem Ablaufdatum
```

Diese Werte sollten nicht zu früh in einen anonymen aktuellen Wert abgeflacht werden.

Die veröffentlichte Sicht kann einen effektiven Wert darstellen und gleichzeitig Evidenz sowie Entscheidungshistorie im Hintergrund erhalten.

## Konflikte lösen, ohne legitime lokale Bedeutung zu zerstören

Fachsprache ist selten über alle Systeme und Domains vollständig einheitlich.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/enrich-technical-metadata-with-business-context-img4-de.png"
        alt="Konfliktlösungsmodell, das die Labels Customer, Debtor und Account anhand lokaler Bedeutung, Enterprise Vocabulary, Domain Context, freigegebener Mappings und Steward-Entscheidungen vergleicht"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Enterprise Vocabulary sollte lokale Bedeutungen verbinden und nicht gültige Unterschiede löschen. Das korrekte Ergebnis kann ein Mapping, Synonym, beibehaltenes lokales Label oder ein ungelöster Konflikt sein.
    </figcaption>
</figure>

Angenommen, drei Systeme verwenden unterschiedliche Labels:

```text
CRM: Customer
ERP: Debtor
Sales App: Account
```

Ein naiver Normalisierungsprozess könnte alle drei durch `Customer` ersetzen. Das kann falsch sein.

Im CRM kann `Customer` eine kommerzielle Partei mit betreuter Kundenbeziehung darstellen. Im ERP kann `Debtor` die juristische Einheit bezeichnen, die für Forderungen verantwortlich ist. In der Sales App kann `Account` auch Prospects enthalten, die noch nie gekauft haben.

Der Resolver sollte Folgendes bewerten:

```text
Local Source Meaning
+ Enterprise Vocabulary
+ Domain Context
+ Approved Mapping
+ Steward Decision
```

Mögliche Ergebnisse sind:

### Lokales Label beibehalten

Nutze dieses Ergebnis, wenn das lokale Konzept gültig und innerhalb seiner Domain präziser ist.

### Auf Enterprise Term mappen

Nutze dieses Ergebnis, wenn das lokale Konzept innerhalb des definierten Scopes semantisch gleichwertig ist.

### Synonym ergänzen

Nutze dieses Ergebnis für alternative Sprache, die Search verbessert, ohne vollständige Gleichwertigkeit zu behaupten.

### Konflikt ungelöst lassen

Nutze dieses Ergebnis, wenn die vorhandene Evidenz nicht ausreicht oder die Konzepte sich überschneiden, ohne identisch zu sein.

Ein ungelöster Konflikt ist besser als eine irreführende universelle Definition.

Mappings sollten Scope-aware sein:

```yaml
mapping:
  local_term: ERP.Debtor
  enterprise_term: Party.ResponsibleForReceivable
  relationship: narrower_than
  scope: Finance.AccountsReceivable
  status: approved
```

Das ist präziser, als `Debtor = Customer` als globales Synonym zu speichern.

## Alternative Anreicherungsmuster

Unterschiedliche Betriebsmodelle können dieselben Prinzipien umsetzen.

### Zentraler Steward-Workflow

Ein zentrales Governance-Team verwaltet Definitionen und Mappings in einer Plattform.

Das kann Konsistenz schaffen, aber zu einem Bottleneck werden und Source-spezifisches Wissen verlieren. Das Modell eignet sich am besten für Enterprise Terms, domainübergreifende KPIs und Policy-Entscheidungen statt für jedes lokale Feld.

### Domain-owned Enrichment

Domain-Teams reichern ihre eigenen Data Products mithilfe gemeinsamer Templates und Vocabulary Services an.

Damit bleibt Verantwortung nahe am Business Context. Gemeinsame Identity, Relationship Types, Approval States und Quality Controls sind erforderlich, damit die Metadaten interoperabel bleiben.

### Metadata-as-Code

Definitionen, Mappings und Ownership können in versionierten Dateien nahe an Transformations- oder Semantic Code gepflegt werden.

Das unterstützt Review, Änderungshistorie und Deployment-Workflows. Es benötigt trotzdem Business-Beteiligung und einen Mechanismus, um Beziehungen in die breitere Metadatenumgebung zu veröffentlichen.

### Workflow-driven Catalog Enrichment

Geharvestete Assets gelangen abhängig von Priorität, Risiko und fehlenden Metadaten in Queues. Data Stewards prüfen Vorschläge in einer Benutzeroberfläche, anschließend werden freigegebene Ergebnisse zentral veröffentlicht.

Dieses Muster ist für nichttechnische Rollen zugänglich. Versionierung, Provenance und Exportierbarkeit müssen jedoch erhalten bleiben.

### Hybrides Muster

Ein häufiges Modell lautet:

```text
Source-native Context nahe an der Quelle
+ Metadata-as-Code für Transformationsbedeutung
+ Catalog Workflow für systemübergreifende Beziehungen
+ Governance-Plattform für Enterprise Vocabulary und Freigaben
```

Die entscheidende Designfrage lautet nicht, wo jeder Wert angezeigt wird. Entscheidend ist, wo der Wert erstellt, freigegeben und aktuell gehalten wird.

## Häufige Anti-Patterns

### Anreicherung nur durch Friendly Names

`CUST_ID` in `Customer ID` umzubenennen verbessert die Lesbarkeit. Scope, Stabilität und fachliche Rolle bleiben trotzdem undefiniert.

### Automatische Glossarfreigabe durch Name Matching

Ein ähnliches Label ist Evidenz, aber kein Beweis für Gleichwertigkeit.

### Eine Beschreibung wird in jede Schicht kopiert

Source-Feld, transformiertes Feld, Semantic Measure und KPI können zusammenhängen, ohne identische Definitionen zu besitzen.

### Profiling wird als fachliche Wahrheit dargestellt

Beobachtete Werte können vorgesehene Bedeutung, rechtmäßige Nutzung oder verantwortliche Ownership nicht etablieren.

### Owner als ungeprüfte E-Mail-Adresse

Ownership sollte auf eine aktive Rolle oder Identity mit Lifecycle Handling verweisen und nicht auf einen unverwalteten String.

### Verbotene Nutzung fehlt

Nur die vorgesehene Nutzung zu dokumentieren lässt User ohne explizite Grenzen zurück.

### Bulk Overwrite ohne Konflikterkennung

Große Änderungen benötigen Preview, Exception Handling, Freigabe und Rollback.

### Freigegebener Wert ohne Evidenzhistorie

Vorschläge, vorherige Versionen und Ablehnungsgründe zu entfernen erschwert spätere Konfliktklärung.

### Enterprise Vocabulary löscht Domain-Unterschiede

Universelle Definitionen können vage oder falsch werden, wenn legitime lokale Unterschiede zusammengelegt werden.

### Anreicherung ist von Consumern getrennt

Kontext, der nicht in Search, BI-Entwicklung, Semantic Modelling, Quality Review oder AI Retrieval verfügbar ist, beeinflusst tägliche Entscheidungen nicht.

## Entscheidungshilfe

Nutze folgende Fragen beim Design eines Enrichment-Prozesses.

### Scope und Priorität

1. Welche Data Products, KPIs oder Entscheidungen besitzen das höchste semantische Risiko?
2. Welche Assets haben die meisten Consumer?
3. Welche Objekte enthalten sensitive oder regulierte Daten?
4. Welche Definitionen sind aktuell widersprüchlich oder fehlen?

### Autorität

5. Wer kennt die lokale Source-Bedeutung?
6. Wer verantwortet den Enterprise Term?
7. Wer gibt vorgesehene und verbotene Nutzung frei?
8. Welche Rolle löst domainübergreifende Konflikte?

### Automatisierung

9. Welche Evidenz kann geharvestet oder profiliert werden?
10. Welche Beziehungen dürfen ausschließlich vorgeschlagen werden?
11. Welche risikoarmen Attribute können durch eine freigegebene Regel propagiert werden?
12. Welche Änderungen benötigen immer menschliches Review?

### Provenance und Lifecycle

13. Kann jeder Wert zu Quelle und Lieferant zurückverfolgt werden?
14. Sind erkannte, vorgeschlagene und freigegebene Zustände getrennt?
15. Werden Freigaben versioniert und mit Effective Dates versehen?
16. Können abgelehnte Vorschläge und frühere Werte auditiert werden?

### Consumption

17. Wo suchen User nach Daten?
18. Wo implementieren Engineers Transformationen?
19. Wo werden KPIs und Reports definiert?
20. Wie erreicht freigegebener Kontext AI- und RAG-Systeme, ohne ungeprüfte Vorschläge als Fakten darzustellen?

Die Antworten entscheiden darüber, ob Enrichment eine Dokumentationsübung oder eine operative Governance-Fähigkeit ist.

## Zentrale Empfehlungen

1. Starte mit geharvesteten technischen Assets und stabilen Identifiern.
2. Behandle Enrichment als kontrollierte Beziehungen und governte Attribute, nicht nur als Friendly Labels.
3. Ergänze Business Names, Definitionen, Synonyme, Domains, Data Products, Owner und Stewards dort, wo sie Entscheidungen materiell verbessern.
4. Trenne Technical Ownership, Business Ownership, Stewardship und Data-Product-Verantwortung.
5. Verbinde Felder und Tabellen mit Business Terms und KPIs über explizite Relationship Types.
6. Behandle Name Similarity oder Embedding Similarity niemals als semantische Freigabe.
7. Nutze Profiling, Lineage, Beispiele und Consumer Context als Evidenz für Vorschläge.
8. Halte erkannte, inferierte, vorgeschlagene, freigegebene und abgelehnte Zustände getrennt.
9. Erfasse Quelle, Lieferant, Zeit, Methode, Confidence und Freigabe für jede Anreicherung.
10. Dokumentiere vorgesehene Nutzung, verbotene Nutzung und bekannte Einschränkungen gemeinsam.
11. Bevorzuge rollenbasierte Ownership-Referenzen mit Lifecycle Management gegenüber Freitextkontakten.
12. Gestalte Steward Queues nach Risiko, Wirkung und Mehrdeutigkeit statt nur nach Anzahl der Assets.
13. Unterstütze Bulk Proposal und Bulk Review, vermeide jedoch unqualifizierte Bulk Approval.
14. Zeige Scope, Konflikte und Ersetzungen vor jeder Bulk-Änderung an.
15. Erhalte abgelehnte Vorschläge, damit Matching verbessert werden kann, ohne Auditierbarkeit zu verlieren.
16. Nutze Scope-aware Mappings für lokale und Enterprise-Terminologie.
17. Erhalte legitime Domain-Unterschiede, wenn eine universelle Definition irreführend wäre.
18. Veröffentliche freigegebenen Kontext in den Tools und Workflows, in denen Daten ausgewählt, transformiert, analysiert und konsumiert werden.
19. Versioniere semantische Änderungen und verbinde sie mit Impact Analysis.
20. Beginne mit einem wertvollen Data Product und belege den vollständigen Weg von geharvesteter Struktur zu freigegebenem und konsumiertem Business Context.

## Der nächste Schritt: ein einheitliches Metadatenmodell aufbauen

Enrichment ergänzt den fachlichen Kontext, der technischen Strukturen fehlt.

Das Ergebnis verteilt sich jedoch über viele Objekttypen und Beziehungen:

```text
Systeme
Assets
Felder
Begriffe
KPIs
Data Products
Personen und Rollen
Policies
Quality Rules
Reports
Vorschläge
Freigaben
Versionen
```

Ohne ein kohärentes Modell kann jeder Connector oder Workflow diese Objekte unterschiedlich darstellen. Identity wird instabil, Beziehungen werden mehrdeutig und Provenance lässt sich nur schwer konsistent abfragen.

Der nächste Teil, **Ein einheitliches Metadatenmodell aufbauen**, behandelt gemeinsame Entities, Identifier, Relationship Types, Provenance-Strukturen, Versionierungsregeln und Extension Patterns, die diesen Kontext verbinden, ohne ihn in einen übergroßen Datensatz abzuflachen.
