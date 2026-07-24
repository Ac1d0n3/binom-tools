---
title: Metadaten schreiben, die Menschen und Maschinen verstehen — Tabellen, Felder und Kennzahlen mit Bedeutung, Grenzen und Beispielen beschreiben
description: Eine praxisnahe Methode für Beschreibungen von Tabellen, Feldern, KPIs, Identifiern, Statuswerten, Zeitstempeln, berechneten Feldern und AI Features, die für Menschen, Kataloge, RAG-Systeme und AI Assistants nutzbar bleiben.
category: Data Governance
tags:
  - metadata
  - metadata-governance
  - data-catalog
  - data-documentation
  - business-glossary
  - data-quality
  - semantic-layer
  - rag
  - ai-ready-metadata
  - data-products
  - kpi-governance
  - data-contracts
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 5
seriesTitle: MetaData Deep Dive
hero: images/playbooks/write-metadata-people-and-machines-can-understand-hero.png
---

## Ein technisch korrekter Name ist noch keine brauchbare Beschreibung

Eine Metadatenplattform kann einen Tabellennamen, Datentyp, Lineage-Pfad und den letzten Refresh automatisiert erfassen. Sie kann belegen, dass ein Feld existiert, seine Herkunft zeigen und Reports identifizieren, die es verwenden.

Damit ist noch nicht erklärt, was das Feld bedeutet.

Namen wie `sales_amount`, `customer_id`, `order_date` und `status` wirken vertraut. Genau diese scheinbare Klarheit ist gefährlich, weil unterschiedliche Teams denselben Namen unterschiedlich interpretieren können:

- Ist `sales_amount` brutto, netto, fakturiert, bestellt oder als Umsatz realisiert?
- Sind Steuern, Versand, Rabatte, Stornierungen und spätere Gutschriften enthalten?
- Bleibt `customer_id` über Quellsysteme, Gesellschaften und Customer Merges stabil?
- Steht `order_date` für Anlage, Buchung, Bestätigung oder Erfüllung?
- Ist `status` ein Source Code, ein normalisierter Lifecycle State oder der zuletzt beobachtete Zustand?

Eine Beschreibung wie `Sales = Revenue` löst keine dieser Fragen. Sie ersetzt lediglich einen mehrdeutigen Begriff durch einen anderen.

> **Eine brauchbare Beschreibung ergänzt Informationen, die sich nicht aus dem technischen Namen ableiten lassen. Sie erklärt Bedeutung, Grain, Berechnung, Zeitbezug, Einheiten, Grenzen, Ausnahmen, Beziehungen und vorgesehenen Einsatz.**

Das ist für Menschen und Maschinen relevant.

Ein Business User benötigt genug Kontext, um das richtige Feld auszuwählen. Ein Engineer benötigt ausreichende Präzision für Implementierung und Tests. Ein Data Steward braucht Struktur für Ownership, Qualität und Policies. Ein Katalog benötigt konsistente Attribute für Suche und Vergleich. Ein RAG-System oder AI Assistant benötigt explizite Grenzen, damit ein plausibler Name nicht als vollständige Definition behandelt wird.

Gute Metadaten sind deshalb keine dekorative Prosa. Sie sind ein kompakter Entscheidungsvertrag.

## Beschreibungen für Entscheidungen schreiben, nicht für Inventarlisten

Eine schwache Beschreibung beantwortet nur:

```text
Wie heißt dieses Objekt?
```

Eine entscheidungsfähige Beschreibung beantwortet:

```text
Was wird dargestellt?
Auf welchem Grain?
Für welche Population?
Zu welchem Zeitpunkt?
In welcher Einheit oder Währung?
Wie wird der Wert berechnet?
Welche Ausnahmen gelten?
Wie unterscheidet er sich von ähnlichen Feldern?
Wofür darf er verwendet werden?
Wofür darf er nicht verwendet werden?
Wer verantwortet die Bedeutung?
```

Nicht jedes Asset benötigt denselben Textumfang. Der notwendige Detailgrad sollte von Mehrdeutigkeit, geschäftlicher Wirkung, Wiederverwendung, Sensitivität und dem Risiko einer falschen Interpretation abhängen.

Ein technisches Staging-Feld, das ausschließlich in einer Pipeline verwendet wird, benötigt möglicherweise nur ein kurzes Source Mapping und einen Transformationshinweis. Ein zertifizierter Enterprise KPI für Managemententscheidungen benötigt dagegen eine vollständige Definition mit Formel, Nenner, Zeitfenster, Ausschlüssen, Restatement-Regel, Owner und freigegebenem Verwendungszweck.

Das Ziel ist nicht maximale Dokumentationsmenge. Das Ziel ist ausreichend Information für eine korrekte Entscheidung.

## Anatomie einer brauchbaren Tabellenbeschreibung

Eine Tabellenbeschreibung muss das Dataset als governte Population von Zeilen erklären. Den Tabellennamen zu wiederholen oder einige Felder aufzuzählen reicht nicht aus.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/write-metadata-people-and-machines-can-understand-img1-de.png"
        alt="Anatomie einer brauchbaren Beschreibung für die Tabelle fct_sales_order_line mit Zweck, Grain, Population, Zeit, Kennzahlen und Schlüsseln, Einschränkungen und vorgesehener Nutzung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine brauchbare Tabellenbeschreibung definiert den dargestellten Prozess, den Zeilengrain, die Populationsgrenzen, das Zeitverhalten, wichtige Keys und Measures, bekannte Einschränkungen sowie die vorgesehene analytische Nutzung.
    </figcaption>
</figure>

Betrachten wir die Faktentabelle `fct_sales_order_line`.

Eine belastbare Tabellenbeschreibung sollte sieben Komponenten abdecken.

### Zweck

Beschreibe, welchen Geschäftsprozess oder analytischen Gegenstand die Tabelle repräsentiert.

Schwach:

```text
Sales-Order-Line-Faktentabelle.
```

Besser:

```text
Repräsentiert kommerzielle Sales-Order-Positionen, die vom Auftragsprozess akzeptiert und für operative Vertriebsanalysen sowie Management Reporting aufbereitet wurden.
```

Die bessere Variante erklärt Geschäftsprozess und Rolle der Tabelle. Sie wiederholt nicht nur den Objektnamen.

### Grain

Beschreibe exakt, was eine Zeile repräsentiert.

Beispiel:

```text
Eine Zeile je Source-Sales-Order-Position und effektiver Version.
```

Das Wort `Version` ist entscheidend. Ohne diesen Hinweis kann ein User eine aktuelle Zeile je Auftragsposition annehmen und in einem historisierten Modell doppelt zählen.

Der Grain sollte das kleinste stabile Geschäftsereignis oder die kleinste Entität einer Zeile identifizieren. Bei Snapshots oder historisierten Datasets muss zusätzlich Snapshot-Datum, Effective Interval oder Versionierungsmechanismus beschrieben werden.

### Population

Definiere, welche Datensätze enthalten und ausgeschlossen sind.

Beispiel:

```text
Enthält akzeptierte Standard-, Service- und Ersatzauftragspositionen aus der europäischen Order-Plattform. Ausgeschlossen sind Angebote, Testaufträge, intern abgelehnte Positionen und Datensätze, die vor der Source-Bestätigung entfernt wurden.
```

Die Population ist häufig der entscheidende Unterschied zwischen zwei Tabellen, die scheinbar denselben Gegenstand enthalten.

### Zeit

Erkläre relevante Business-Daten und Update-Verhalten.

Beispiel:

```text
Das primäre Business-Datum ist booking_date. Source Events werden inkrementell geladen. Späte Korrekturen können eine neue effektive Version einer bereits berichteten Auftragsposition erzeugen.
```

Eine Tabelle kann viele Zeitstempel enthalten. Die Beschreibung sollte nennen, welcher Zeitbezug normalerweise das Reporting steuert und wie späte Änderungen historische Ergebnisse beeinflussen.

### Measures und Keys

Nenne die wichtigsten Identifier und Kennzahlen, ohne das vollständige Schema zu wiederholen.

Beispiel:

```text
Der Business Key besteht aus sales_order_id + sales_order_line_id. Zentrale additive Measures sind ordered_quantity und net_sales_amount in Reporting Currency.
```

Damit wird erkennbar, wie die Tabelle verbunden und aggregiert werden kann. Eine vollständige Liste technischer Surrogate Keys und Audit-Felder ist nicht erforderlich.

### Bekannte Einschränkungen

Dokumentiere relevante Schwächen, Lücken und Ausnahmen.

Beispiel:

```text
Historische Source States vor 2024-01-01 sind für eine Legacy-Region unvollständig. Gutschriften werden im Billing-Modell dargestellt und nicht rückwirkend auf net_sales_amount in dieser Tabelle angewendet.
```

Eine bekannte Einschränkung gehört zum Produktvertrag. Sie zu verbergen erhöht nicht das Vertrauen.

### Vorgesehene Nutzung

Beschreibe geeignete analytische Einsatzbereiche.

Beispiel:

```text
Geeignet für Auftragseingang, operative Pipeline-Analyse und Reconciliation zur Order-Plattform. Nicht geeignet als autoritative Quelle für realisierten Umsatz oder final fakturierten Wert.
```

Damit wird verhindert, dass ein technisch verwandtes Dataset für die falsche Geschäftsentscheidung verwendet wird.

## Anatomie einer brauchbaren Feldbeschreibung

Eine Feldbeschreibung sollte präziser als die Beschreibung der übergeordneten Tabelle sein. User finden Felder häufig über Suchergebnisse, semantische Modelle oder AI-Antworten, ohne die vollständige Dataset-Seite zu lesen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/write-metadata-people-and-machines-can-understand-img2-de.png"
        alt="Strukturierte Metadatenkarte für net_sales_amount mit fachlicher Bedeutung, Berechnung, Währung, Zeitbezug, Null-Verhalten, Vorzeichenkonvention, Ausnahmen, Beziehungen, Nutzung und Ownership"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Feldbeschreibungen werden belastbar, wenn Bedeutung, Berechnung, Einheit, Zeitbezug, Null-Verhalten, Vorzeichen, Ausnahmen, Beziehungen und Nutzungsgrenzen explizit dargestellt werden.
    </figcaption>
</figure>

Für `net_sales_amount` könnte eine brauchbare Definition mit folgendem Satz beginnen:

```text
Nettowert einer Auftragsposition nach positionsbezogenen Rabatten,
vor Steuern, Versandkosten und späteren Gutschriften.
```

Dieser Satz liefert die zentrale fachliche Bedeutung. Ein vollständiges und wiederverwendbares Metadatenprofil sollte weitere Attribute getrennt abbilden.

### Fachliche Bedeutung

Beschreibe das Business-Konzept, das der Wert repräsentiert.

Beginne nicht mit Implementierungsdetails, sofern die Implementierung nicht selbst das Konzept ist. `Berechnet aus AMT_01 minus DISC_02` ist keine fachliche Definition.

### Berechnung

Erkläre die Berechnung in dem Detailgrad, der für das Verständnis des Ergebnisses erforderlich ist.

Beispiel:

```text
Basiswert der Position abzüglich freigegebener positionsbezogener Rabatte. Kopfbezogene Nachlässe, Steuern, Versandkosten und spätere Billing-Korrekturen sind ausgeschlossen.
```

Die Beschreibung sollte die Logik zusammenfassen. Der autoritative ausführbare Ausdruck sollte mit dem versionierten Code oder dem semantischen Modell verbunden bleiben, das ihn ausführt.

### Einheit oder Währung

Beschreibe, ob der Wert eine Anzahl, ein Prozentsatz, eine Dauer, Menge, Bewertung, Local Currency, Document Currency oder Reporting Currency ist.

Bei Währungsfeldern müssen Currency Source und Umrechnungszeitpunkt erkennbar sein.

Beispiel:

```text
In Reporting Currency gespeichert. Der Währungscode steht in reporting_currency_code. Die Umrechnung verwendet den freigegebenen Tageskurs für booking_date.
```

Ein Decimal-Datentyp verrät keine Einheit.

### Zeitbezug

Erkläre, welches Ereignis, welche Periode oder welches Effective Date der Wert repräsentiert.

Beispiel:

```text
Repräsentiert den Wert der Auftragsposition in der für das booking_date-Reporting gültigen effektiven Version. Spätere Gutschriften bleiben separat.
```

Das ist für veränderliche Geschäftsprozesse und neu berechnete Kennzahlen wesentlich.

### Null- und Default-Verhalten

Unterscheide unbekannt, nicht anwendbar, nicht geliefert und null.

Beispiel:

```text
NULL bedeutet, dass der Betrag wegen fehlender Preis- oder Währungsumrechnungsdaten nicht ermittelt werden konnte. Null ist bei kostenfreien Positionen ein gültiges berechnetes Ergebnis.
```

Null durch 0 zu ersetzen verändert die Bedeutung. Die Beschreibung muss das sichtbar machen.

### Vorzeichenkonvention

Definiere, wie positive und negative Werte interpretiert werden.

Beispiel:

```text
Positive Werte erhöhen den Auftragseingang. Negative Werte stellen vom Order-Prozess erzeugte Umkehrpositionen dar. Billing-Gutschriften sind hier nicht enthalten.
```

### Ausnahmen

Dokumentiere Geschäftsfallvarianten, die nicht der Standardregel folgen.

Dazu können manuelle Overrides, Legacy-Regionen, Migrationszeiträume, fehlende Source States, besondere Produkttypen und regulatorische Anpassungen gehören.

### Beziehung zu anderen Feldern

Grenze das Feld von ähnlichen Werten ab.

Beispiel:

```text
Unterscheidet sich von gross_sales_amount, das vor Rabatten berechnet wird, und invoiced_net_amount, das den fakturierten Wert nach Rechnungskorrekturen darstellt.
```

Diese Abgrenzung ist häufig wertvoller als eine längere isolierte Definition.

### Geeignete und ungeeignete Nutzung

Dokumentiere beides.

Beispiel:

```text
Geeignet für Auftragseingang und Rabattanalysen auf Auftragspositions-Grain.
Nicht geeignet für Steuerreporting, Zahlungseingang, realisierten Umsatz oder Rechnungsabstimmung.
```

### Owner

Identifiziere die Rolle, die Definition und Ausnahmen verantwortet.

Der Owner des physischen Datenbankfelds und der Owner der fachlichen Bedeutung können unterschiedlich sein. Beide können erfasst werden. Der Beschreibungsvertrag sollte jedoch sichtbar machen, wer die semantische Definition freigibt.

## Fachliche Erklärung von ausführbarer Logik trennen

Berechnungsmetadaten müssen präzise sein, ohne eine manuell gepflegte Kopie des Implementierungscodes zu erzeugen.

Ein brauchbares Muster besitzt drei Ebenen:

```text
Fachliche Definition
+ strukturierte Berechnungszusammenfassung
+ Referenz auf autoritative ausführbare Logik und Version
```

Beispiel:

```yaml
name: net_sales_amount
business_definition: >
  Nettowert einer Auftragsposition nach positionsbezogenen Rabatten,
  vor Steuern, Versandkosten und späteren Gutschriften.
calculation_summary: >
  Basiswert der Position abzüglich freigegebener positionsbezogener Rabatte.
  Kopfbezogene Nachlässe sind ausgeschlossen.
implementation_reference:
  system: transformation-repository
  asset: model.fct_sales_order_line
  field: net_sales_amount
  version: git:8f42c1a
```

Damit werden zwei Fehlerbilder vermieden.

Das erste ist vage Prosa, die die Berechnung nicht erklärt. Das zweite ist ein kopierter SQL-Ausdruck, der nach einer Codeänderung veraltet.

Die Beschreibung erklärt Intention und Grenzen. Die verknüpfte Implementierung belegt, wie die aktuelle Version diese Intention ausführt.

Für komplexe KPIs sollte ein Formelmodell mit expliziten Komponenten ergänzt werden:

```text
Zähler
Nenner
Aggregation
Zeitfenster
Populationsfilter
Ausschlüsse
Währung oder Einheit
Restatement-Regel
Rundung
```

Eine Formel wie `revenue / customers` ist erst vollständig, wenn beide Terme, Population, Zeitfenster und Aggregationsebene definiert sind.

## Beispiele und Gegenbeispiele reduzieren Interpretationsrisiken

Definitionen werden belastbarer, wenn sie repräsentative Beispiele enthalten.

Für einen Status Code:

```text
FULFILLED — alle erforderlichen Mengen wurden ausgeliefert und es besteht keine offene Fulfillment-Aufgabe mehr.
```

Ergänze ein Gegenbeispiel:

```text
Eine Position mit Teillieferung und verbleibendem Rückstand ist nicht FULFILLED.
```

Für einen Customer Identifier:

```text
Beispiel: 47110815 identifiziert einen Kundendatensatz im europäischen CRM Tenant.
Gegenbeispiel: Es handelt sich nicht um einen unternehmensweit gültigen Legal-Party-Identifier; der Wert kann sich nach einem Customer Merge ändern.
```

Für einen KPI:

```text
Beispiel: Ein am 31. März gebuchter und am 2. April stornierter Auftrag bleibt im Brutto-Auftragseingang für März enthalten, wird aber aus dem aktuellen Open Order Value entfernt.
```

Beispiele sind besonders wertvoll, wenn Regeln von Zeit, Statusübergängen oder Ausschlüssen abhängen.

Sie unterstützen Leser, Unit-Test-Design, Katalogsuche, RAG Retrieval und AI Assistants. Beispiele sollten realistisch sein, aber keine vertraulichen Personen- oder Transaktionsdaten offenlegen.

## Schwache Beschreibungen erzeugen selbstsichere Fehler

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/write-metadata-people-and-machines-can-understand-img3-de.png"
        alt="Vorher-Nachher-Vergleich schwacher und entscheidungsfähiger Beschreibungen für sales_amount, customer_id, order_date und status"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Schwache Beschreibungen wiederholen Labels. Entscheidungsfähige Beschreibungen definieren Grenzen, Stabilität, Zeitverhalten, gültige Zustände und analytische Nutzung.
    </figcaption>
</figure>

### `sales_amount`

Schwach:

```text
Umsatzbetrag.
```

Entscheidungsfähig:

```text
Nettowert einer Auftragsposition in Reporting Currency nach positionsbezogenen Rabatten und vor Steuern, Versandkosten sowie späteren Gutschriften. Innerhalb desselben Reporting-Currency-Kontexts über Auftragspositionen additiv.
```

### `customer_id`

Schwach:

```text
Kundennummer.
```

Entscheidungsfähig:

```text
Vom europäischen CRM Tenant vergebener Identifier für einen Kundendatensatz. Innerhalb dieses Tenants eindeutig, nicht unternehmensweit gültig und nach Duplicate-Record-Merges nicht garantiert stabil. Eine Wiederverwendung nach Löschung ist im Source-Prozess unzulässig.
```

### `order_date`

Schwach:

```text
Datum des Auftrags.
```

Entscheidungsfähig:

```text
Kalenderdatum, an dem der Source-Auftrag zur Buchung akzeptiert wurde, abgeleitet aus dem Source Event Timestamp in Europe/Berlin. Der Wert kann durch ein späteres Source Event korrigiert werden. Für Auftragseingangsreporting verwenden, nicht für Versand- oder Rechnungsperioden.
```

### `status`

Schwach:

```text
Aktueller Status.
```

Entscheidungsfähig:

```text
Normalisierter aktueller Lifecycle State der Auftragsposition. Erlaubte Werte sind OPEN, PARTIALLY_FULFILLED, FULFILLED und CANCELLED. FULFILLED und CANCELLED sind terminale Zustände. Ein Wechsel von FULFILLED zu OPEN benötigt ein Source Correction Event und wird zur Prüfung markiert.
```

Die verbesserten Beschreibungen sind länger. Länge ist jedoch nicht der zentrale Unterschied. Sie enthalten entscheidungsrelevante Constraints.

## Ein generisches Template reicht nicht aus

Tabellen, Felder, KPIs, Identifier, Statuswerte, Zeitstempel, berechnete Felder und AI Features besitzen unterschiedliche Fehlerbilder. Deshalb benötigen sie unterschiedliche Beschreibungsfragen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/write-metadata-people-and-machines-can-understand-img4-de.png"
        alt="Template-Bibliothek mit unterschiedlichen Beschreibungsfragen für Tabellen, Felder, KPIs, Identifier, Status Codes, Zeitstempel, berechnete Felder und AI Features"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Wiederverwendbare Templates sollten zum Asset-Typ passen. Ein einziges Freitextfeld kann nicht zuverlässig alle semantischen, zeitlichen und Governance-Anforderungen erfassen.
    </figcaption>
</figure>

### Tabellen-Template

Fragen:

1. Welchen Prozess, welche Entität oder welches Ereignis repräsentiert die Tabelle?
2. Was stellt eine Zeile dar?
3. Welche Datensätze sind enthalten und ausgeschlossen?
4. Welches Business-Datum und welches Update-Verhalten sind relevant?
5. Welche vorgesehenen Nutzungen und bekannten Einschränkungen bestehen?

### Feld-Template

Fragen:

1. Welche fachliche Bedeutung trägt der Wert?
2. Welche Einheit, Währung, welches Format oder Code System gilt?
3. Was bedeuten Null, 0 und Default Values?
4. Welche Ausnahmen und ähnlichen Felder müssen abgegrenzt werden?
5. Wofür darf und wofür darf das Feld nicht verwendet werden?

### KPI-Template

Fragen:

1. Welche Entscheidung unterstützt der KPI?
2. Welche Formel-, Zähler-, Nenner- und Aggregationsregeln gelten?
3. Welche Population, welches Zeitfenster und welche Ausschlüsse gelten?
4. Wie werden Währung, Rundung, Late Data und Restatements behandelt?
5. Wer gibt Definition und Änderungen frei?

### Identifier-Template

Fragen:

1. Welches System vergibt den Identifier?
2. Innerhalb welchen Scopes ist er eindeutig?
3. Ist er über Zeit, Merges, Migrationen und Quellsysteme stabil?
4. Kann er wiederverwendet werden?
5. Welcher Enterprise Identifier oder Crosswalk ist verknüpft?

### Status-Code-Template

Fragen:

1. Welchen Prozesszustand repräsentiert jeder Code?
2. Welche Übergänge sind gültig?
3. Welche Zustände sind terminal?
4. Wie werden unbekannte oder Legacy Codes behandelt?
5. Handelt es sich um aktuellen Zustand, Event State oder historischen Zustand?

### Timestamp-Template

Fragen:

1. Welches Ereignis oder welche Systemaktion repräsentiert der Timestamp?
2. Welche Zeitzone und Präzision gelten?
3. Ist es Source Event Time, Ingestion Time, Processing Time oder Effective Time?
4. Kann der Wert korrigiert werden oder verspätet eintreffen?
5. Welche Reporting-Entscheidungen sollten ihn verwenden?

### Template für berechnete Felder

Fragen:

1. Welches Business-Konzept wird abgeleitet?
2. Welche Inputs, Filter und Transformationsregeln gelten?
3. Auf welchem Grain ist die Berechnung gültig?
4. Wie werden Nulls, Vorzeichen, Rundung und Sonderfälle behandelt?
5. Wo liegt die autoritative ausführbare Logik?

### AI-Feature-Template

Fragen:

1. Wie wird das Feature abgeleitet und aus welchem Source-Zeitraum?
2. Welche Entität und welchen Prediction Point repräsentiert es?
3. Welche Training Window- und Freshness-Regel gilt?
4. Besteht Target Leakage, Proxy Discrimination oder ein Risiko unzulässiger Informationen?
5. Für welches Modell, welche Population und welchen Zweck ist das Feature freigegeben?

Ein AI Feature benötigt eine Beschreibung seiner Ableitung und zeitlichen Gültigkeit. Ein technisch korrekt berechnetes Feature kann trotzdem ungeeignet sein, wenn es Informationen verwendet, die zum Prediction Time nicht verfügbar gewesen wären.

## Die einfachste tragfähige Umsetzung

Ein Team muss nicht die gesamte Metadatenplattform neu gestalten, bevor es Beschreibungen verbessert.

Starte mit einem wichtigen Datenprodukt und fünf Controls.

### 1. Pflichtfelder je Asset-Typ definieren

Nutze strukturierte Attribute für wiederkehrende Fragen und eine kurze narrative Zusammenfassung.

Für ein Feld kann ein minimales Profil so aussehen:

```yaml
name: net_sales_amount
summary: >
  Nettowert einer Auftragsposition nach positionsbezogenen Rabatten,
  vor Steuern, Versandkosten und späteren Gutschriften.
unit_type: currency
currency_field: reporting_currency_code
time_reference: booking_date
null_meaning: unavailable_input
zero_is_valid: true
sign_convention: positive_increases_order_intake
suitable_use:
  - order_intake
  - discount_analysis
unsuitable_use:
  - recognized_revenue
  - tax_reporting
owner: sales-data-product-owner
```

### 2. Beschreibungen im Delivery Workflow validieren

Eine Beschreibung sollte geprüft werden, wenn sich Code, Schema oder semantische Logik ändert.

Sinnvolle automatisierte Checks sind:

- Pflichtbeschreibung fehlt;
- Beschreibung ist identisch mit Asset Name oder Display Label;
- unzulässige Platzhalter wie `TBD`, `same as source` oder `self-explanatory`;
- fehlende Einheit für numerische Measures;
- fehlende Zeitzone für Timestamps;
- fehlende Allowed Values für governte Codes;
- fehlender Owner für zertifizierte KPIs;
- referenziertes Feld oder Asset existiert nicht;
- Implementation Reference verweist auf eine veraltete Version.

Automation kann Unvollständigkeit erkennen. Sie kann nicht entscheiden, ob eine fachliche Definition inhaltlich korrekt ist.

### 3. Authoring Point nah am verantworteten Wissen halten

Source Meaning sollte durch Source Team oder Business Owner gepflegt werden. Kontext abgeleiteter Felder gehört zum Transformationscode. Measure Behaviour gehört zum semantischen Modell. Enterprise Terms und Freigaben gehören in Governance Workflows.

Der zentrale Katalog kann alle Werte mit Provenance indexieren und darstellen.

### 4. Menschenlesbare und strukturierte Repräsentationen veröffentlichen

Die narrative Zusammenfassung hilft Menschen beim schnellen Verständnis. Strukturierte Attribute ermöglichen Filter, Validation, Machine Retrieval und Vergleich.

Ein AI Assistant sollte `currency`, `timezone`, `terminal status` oder `unsuitable use` nicht aus einem langen Absatz erraten müssen, wenn diese Werte explizit dargestellt werden können.

### 5. Beispiele und Grenzen mit echten Consumern prüfen

Lass Analyst, Engineer und verantwortlichen Business Owner die Beschreibung für eine reale Entscheidung verwenden.

Wenn weiterhin gefragt werden muss, was enthalten ist, welches Datum gilt oder ob ein Feld aggregiert werden darf, ist die Beschreibung unvollständig.

## Alternative Betriebsmodelle

### Repository-first Documentation

Beschreibungen liegen bei versionierten Modellen, Schemas und semantischen Definitionen. CI validiert Pflichtfelder und veröffentlicht sie in einen Katalog.

Geeignet, wenn technische und abgeleitete Metadaten über Code Review geändert werden.

Hauptrisiko: Business Owner arbeiten möglicherweise nicht komfortabel in Repository Workflows.

### Catalog-first Authoring

Business User und Data Stewards pflegen Definitionen in einer zentralen Governance-Plattform. Freigegebene Werte werden mit technischen Assets verknüpft oder synchronisiert.

Geeignet, wenn Glossar, Ownership und Approval Workflows dominieren.

Hauptrisiko: Technische Logik und Source Changes können von kopierten Beschreibungen abweichen.

### Föderiertes Authoring mit zentraler Discovery

Jedes System bleibt autoritativ für die Metadaten, die es korrekt pflegen kann. Eine zentrale Schicht verbindet Beschreibungen, Provenance, Lineage, Ownership und Suche.

Geeignet für heterogene Datenlandschaften.

Hauptrisiko: Konflikt- und Precedence-Regeln müssen explizit sein.

### Kontrolliertes Write-back

Eine zentrale Plattform schlägt eine Beschreibungsänderung vor. Diese wird geprüft und in das autoritative Repository oder Source System zurückgeschrieben.

Geeignet, wenn User eine komfortable Oberfläche benötigen, das Repository aber System of Record bleiben muss.

Hauptrisiko: Unbeschränktes bidirektionales Editing erzeugt Loops und stille Überschreibungen.

Das richtige Muster hängt davon ab, wo verantwortetes Wissen vorhanden ist, nicht davon, welche Oberfläche am attraktivsten wirkt.

## Beschreibungen für Kataloge, RAG und AI Assistants nutzbar machen

AI-Systeme beseitigen den Bedarf an disziplinierten Metadaten nicht. Sie erhöhen ihn.

Ein RAG-System ruft Fragmente ab. Ein Assistant kann eine Feldbeschreibung erhalten, ohne die vollständige Tabellenseite zu sehen. Ein Modell kann ein Feld nach semantischer Ähnlichkeit auswählen. Mehrdeutige Beschreibungen führen deshalb zu plausiblen, aber falschen Antworten.

AI-ready Metadata sollte enthalten:

- eine kurze eigenständig verständliche Zusammenfassung;
- expliziten Asset-Typ;
- Parent Asset und Grain;
- Business Term und Synonyme;
- Einheit, Währung und Zeitzone;
- Allowed Values und Null-Semantik;
- geeignete und ungeeignete Nutzung;
- Beispiele und Gegenbeispiele;
- Owner und Approval State;
- Source- und Implementation References;
- effektive Version und Update-Zeitpunkt;
- Links auf verwandte und abzugrenzende Felder.

Beschreibungen sollten ungelöste Pronomen wie `dieser Wert` vermeiden, wenn sie außerhalb ihrer ursprünglichen Seite abgerufen werden. Das Konzept sollte direkt benannt werden.

Ein maschinenlesbares Profil sollte Sprache und Übersetzungsbeziehungen erhalten. Englische und deutsche Beschreibungen können gleichwertig sein, dürfen sich aber nicht still überschreiben. Sprache, Translation Status und Approval State sollten erfasst werden.

Für Retrieval sollte die atomare Definition nahe an der Asset Identity liegen. Lange Policy-Dokumente können zusätzlichen Kontext liefern. Die Feldbedeutung sollte jedoch nicht davon abhängen, dass ein AI-System fünf getrennte Textstellen korrekt zusammensetzt.

## Ein konkretes End-to-End-Beispiel

Das folgende Profil verbindet Tabellen- und Feldkontext, ohne ausführbaren Code zu duplizieren.

```yaml
asset:
  name: fct_sales_order_line
  type: table
  purpose: >
    Repräsentiert akzeptierte Sales-Order-Positionen für operative
    Vertriebsanalysen und Management Reporting.
  grain: eine Zeile je Sales-Order-Position und effektiver Version
  population:
    includes:
      - akzeptierte Standardauftragspositionen
      - Serviceauftragspositionen
      - Ersatzauftragspositionen
    excludes:
      - Angebote
      - Testaufträge
      - intern abgelehnte Positionen
  primary_time_reference: booking_date
  limitations:
    - Historie einer Legacy-Region vor 2024-01-01 unvollständig
    - spätere Billing-Gutschriften liegen in einem anderen Modell
  suitable_use:
    - Auftragseingang
    - operative Pipeline-Analyse
    - Source Reconciliation
  unsuitable_use:
    - realisierter Umsatz
    - final fakturierter Wert

field:
  name: net_sales_amount
  business_meaning: >
    Nettowert einer Auftragsposition nach positionsbezogenen Rabatten,
    vor Steuern, Versandkosten und späteren Gutschriften.
  calculation_summary: >
    Basiswert der Position abzüglich freigegebener positionsbezogener Rabatte.
  unit_type: currency
  currency_field: reporting_currency_code
  time_reference: booking_date
  null_behavior: null bedeutet fehlende Preis- oder Umrechnungsinputs
  zero_behavior: 0 ist für kostenfreie Positionen gültig
  sign_convention: positiv erhöht Auftragseingang; negativ steht für Umkehrpositionen
  related_fields:
    gross_sales_amount: vor Rabatten
    invoiced_net_amount: fakturierter Wert nach Rechnungskorrekturen
  suitable_use:
    - Auftragseingang
    - positionsbezogene Rabattanalyse
  unsuitable_use:
    - Steuerreporting
    - Zahlungseingang
    - realisierter Umsatz
  semantic_owner: sales-data-product-owner
  implementation_reference:
    system: transformation-repository
    asset: model.fct_sales_order_line
    field: net_sales_amount
```

Ein Mensch kann die narrativen Werte verstehen. Ein Validation Process kann Pflichtattribute prüfen. Ein Katalog kann Beziehungen indexieren. Ein AI Assistant kann das Feld von Brutto-, Faktura- und realisierten Umsatzwerten unterscheiden.

## Häufige Anti-Patterns

### Den Namen wiederholen

```text
customer_id: Kunden-Identifier
```

Das ergänzt keine Information.

### Nur die Formel dokumentieren

```text
margin_pct = margin / revenue
```

Zähler, Nenner, Population, Zeitfenster, Behandlung eines Null-Nenners und Aggregationsregel bleiben unbekannt.

### Implementierungscode in Prosa kopieren

Der kopierte Ausdruck driftet von der ausführbaren Version und ist für Business Reader schwer zu interpretieren.

### Nur den Happy Path beschreiben

Ausnahmen, Late Changes, Nulls, Stornierungen und Restatements sind die Stellen, an denen analytische Fehler entstehen.

### Dieselbe Beschreibung für mehrere Assets verwenden

Ein Source Field, ein transformiertes Feld, ein semantisches Measure und ein Dashboard KPI können dasselbe Label tragen und trotzdem unterschiedliche Logik implementieren.

### Ungeeignete Nutzung nicht nennen

User erkennen häufig, welchem Konzept ein Feld ähnelt. Sie erkennen nicht automatisch, wo seine Grenze endet.

### Detected Metadata als freigegebene Bedeutung behandeln

Ein Detector kann vorschlagen, dass ein Feld wie eine E-Mail-Adresse aussieht. Er kann nicht eigenständig fachliche Definition, zulässigen Zweck oder Policy Classification freigeben.

### Uneingeschränkten Freitext erlauben

Freitext ist sinnvoll. Ohne strukturierte Felder lassen sich Währung, Zeitzone, erlaubte Zustände, Ownership und Nutzungsgrenzen jedoch schwer validieren.

### Beschreibungen von Tribal Knowledge abhängig machen

Formulierungen wie `Standardlogik`, `wie üblich` oder `wie im Legacy Report` sind keine portablen Metadaten.

### Auf Completeness Scores optimieren

Ein Katalog kann 100 Prozent befüllte Beschreibungen anzeigen, obwohl überall `selbsterklärend` steht. Coverage ist nicht Qualität.

## Entscheidungshilfe

Beantworte für jedes wichtige Metadaten-Asset die folgenden Fragen.

### Bedeutung und Scope

1. Welches Business-Konzept, Ereignis oder welche Entität wird repräsentiert?
2. Was stellt eine Zeile oder ein Wert dar?
3. Welche Population ist enthalten und ausgeschlossen?
4. Welche ähnlichen Konzepte müssen abgegrenzt werden?

### Berechnung und Aggregation

5. Ist der Wert sourced, normalisiert, abgeleitet oder manuell erfasst?
6. Welche Berechnung, Filter und Dependencies sind relevant?
7. Auf welchem Grain ist der Wert gültig?
8. Ist er additiv, semi-additiv, nicht additiv oder nicht aggregierbar?
9. Wo liegt die autoritative ausführbare Logik?

### Einheit und Zeit

10. Welche Einheit, Währung, welches Format oder Code System gilt?
11. Welcher Timestamp oder welche Business Period steuert die Interpretation?
12. Welche Zeitzone und Präzision gelten?
13. Können Late Changes oder Restatements historische Ergebnisse verändern?

### Nulls, Zustände und Ausnahmen

14. Was bedeuten Null, Blank, 0 und Default Values?
15. Welche Werte sind zulässig?
16. Welche Übergänge sind gültig?
17. Welche Ausnahmen und bekannten Einschränkungen gelten?

### Nutzung und Verantwortung

18. Welche Entscheidungen dürfen das Asset verwenden?
19. Welche Entscheidungen dürfen es nicht verwenden?
20. Wer verantwortet die semantische Definition?
21. Wer pflegt die Implementierung?
22. Welcher Review- oder Approval State gilt?

### Maschinelle Nutzung

23. Ist die Beschreibung außerhalb ihrer ursprünglichen Seite eigenständig verständlich?
24. Sind zentrale Constraints als strukturierte Attribute vorhanden?
25. Gibt es Beispiele, Gegenbeispiele, Synonyme und verwandte Felder?
26. Bleiben Provenance und Versionsinformationen erhalten?
27. Kann ein AI Assistant dieses Asset von ähnlich benannten Alternativen unterscheiden?

Wenn diese Fragen nicht beantwortet werden können, ist das Asset möglicherweise technisch verfügbar, aber noch nicht entscheidungsfähig.

## Zentrale Empfehlungen

1. Jede brauchbare Beschreibung muss Informationen ergänzen, die über den technischen Namen hinausgehen.
2. Tabellen über Zweck, Grain, Population, Zeit, Keys, Measures, Einschränkungen und vorgesehene Nutzung beschreiben.
3. Felder über fachliche Bedeutung, Berechnung, Einheit, Zeitbezug, Null-Verhalten, Vorzeichen, Ausnahmen, Beziehungen und Nutzungsgrenzen beschreiben.
4. KPIs mit Zähler, Nenner, Aggregation, Population, Zeitfenster, Ausschlüssen, Restatement und Rundungsregeln definieren.
5. Identifier über ausgebendes System, Eindeutigkeitsscope, Stabilität, Merge-Verhalten und Wiederverwendung definieren.
6. Status Codes über Allowed Values, Bedeutung der Übergänge, terminale Zustände und Behandlung unbekannter Codes definieren.
7. Timestamps über dargestelltes Ereignis, Zeitzone, Präzision, Update-Verhalten und Reporting-Nutzung definieren.
8. Ausführbare Logik im System belassen, das sie ausführt, und Beschreibungen mit der aktuellen Version verknüpfen.
9. Beispiele und Gegenbeispiele für mehrdeutige, zeitabhängige oder statusabhängige Regeln verwenden.
10. Geeignete und ungeeignete Nutzungen explizit dokumentieren.
11. Felder und Kennzahlen mit ähnlichen Labels eindeutig voneinander abgrenzen.
12. Asset-spezifische Templates statt eines universellen Beschreibungsfelds verwenden.
13. Eine kurze narrative Zusammenfassung mit strukturierten Metadatenattributen kombinieren.
14. Pflichtfelder, Platzhalter, Referenzen, Einheiten, Zeitzonen und Ownership im Delivery Workflow validieren.
15. Authoring Responsibility nah an dem Team halten, das die Bedeutung korrekt pflegen kann.
16. Provenance, Sprache, Version, Approval State und Effective Time erhalten.
17. Detected oder generierte Beschreibungen als Vorschläge behandeln, bis ein verantwortlicher Prozess sie freigibt.
18. Beschreibungen anhand realer analytischer und operativer Entscheidungen testen.
19. Metadatenqualität über Korrektheit, Klarheit und Decision Fitness messen, nicht nur über Feldbefüllung.
20. Beschreibungen so gestalten, dass Menschen, Kataloge, RAG-Systeme und AI Assistants dieselben Grenzen erhalten.

## Der nächste Schritt: Technische Metadaten mit fachlichem Kontext anreichern

Klare Beschreibungen definieren, was eine einzelne Tabelle, ein Feld, eine Kennzahl, ein Identifier oder ein Status bedeutet.

Sie verbinden jedoch noch nicht jedes technische Asset mit übergeordneten Enterprise-Konzepten, Prozessen, Policies, Domänen und Accountability-Strukturen.

Ein Feld kann gut beschrieben und trotzdem vom Business Term isoliert sein, den es implementiert. Ein KPI kann präzise sein, aber keine Verbindung zum unterstützten Ziel besitzen. Eine Tabelle kann einen klaren Grain haben, aber nicht mit dem Prozess, Produkt oder der Domäne verbunden sein, die sie verantwortet.

Der nächste Teil, **Technische Metadaten mit fachlichem Kontext anreichern**, erklärt, wie automatisch erfasste technische Metadaten und präzise Beschreibungen mit Business Vocabulary, Ownership, Policies, Prozessen, Kritikalität und Entscheidungskontext verbunden werden können, ohne ihre Source Provenance zu verlieren.
