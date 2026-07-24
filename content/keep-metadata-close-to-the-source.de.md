---
title: Metadaten gehören möglichst nah an die Quelle — Kontext dort pflegen, wo Wissen und Verantwortung vorhanden sind
description: Eine praxisnahe Architektur, die Metadaten den Systemen und Teams zuordnet, die sie korrekt halten können, und gleichzeitig zentrale Suche, Governance, Lineage und kontrollierte Synchronisierung ermöglicht.
category: Data Governance
tags:
  - metadata
  - metadata-governance
  - data-catalog
  - data-provenance
  - data-lineage
  - dbt
  - semantic-layer
  - business-intelligence
  - federated-governance
  - metadata-ownership
  - active-metadata
  - data-products
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 3
seriesTitle: MetaData Deep Dive
hero: images/playbooks/keep-metadata-close-to-the-source-hero.png
---

## Metadaten werden unzuverlässig, wenn sie am falschen Ort gepflegt werden

Eine zentrale Metadatenplattform vermittelt ein attraktives Versprechen: ein gemeinsamer Ort für Definitionen, Ownership, Klassifikationen, Lineage, Qualität, Policies und Suche.

Das Problem beginnt, wenn aus „ein Ort, an dem Metadaten gefunden werden können“ die Forderung „ein Ort, an dem jeder Metadatenwert manuell gepflegt werden muss“ wird.

Damit wird Kontext von den Systemen und Teams getrennt, die ihn korrekt halten können.

Ein Datenbankfeld wird umbenannt, aber die kopierte Katalogbeschreibung bleibt unverändert. Eine Transformationsregel wird im Code angepasst, ihre manuell übertragene Erklärung jedoch nicht. Ein semantisches Measure verändert sein Filterverhalten, während das zentrale Glossar weiterhin die alte Definition anzeigt. Eine Policy-Ausnahme läuft im Governance-Workflow aus, aber ein kopierter Tag bleibt in einem anderen System aktiv.

Der Katalog kann weiterhin vollständig aussehen. Die Informationen sind trotzdem nicht mehr vertrauenswürdig.

> **Metadaten sollten so nah wie praktisch möglich an dem System, dem Code und dem verantwortlichen Team gepflegt werden, das ihre Bedeutung kennt. Eine zentrale Plattform sollte diesen Kontext verbinden, indexieren und verteilen, anstatt zum einzigen Ort zu werden, an dem Wahrheit manuell neu erstellt wird.**

„Nah an der Quelle“ bedeutet nicht, dass jeder Metadatenwert physisch isoliert bleiben muss. Es bedeutet, dass Autorität und Pflegeverantwortung dort bleiben, wo das notwendige Wissen zur Korrektur vorhanden ist.

Ein zentrales System kann eine durchsuchbare Kopie speichern. Es kann Werte normalisieren, Beziehungen verbinden und Workflows auslösen. Es muss jedoch weiterhin erkennbar bleiben, woher ein Wert stammt, welches System ihn freigibt und ob die zentrale Darstellung autoritativ, referenziert, kopiert oder synchronisiert ist.

## Jede Metadatenart gehört an den passenden Ort

Unterschiedliche Metadatenarten benötigen unterschiedliche Systems of Record.

Eine Quellanwendung kennt die ursprüngliche Feldbedeutung, gültige Prozesszustände und Source Constraints. Transformationscode kennt abgeleitete Logik, Tests und Abhängigkeiten. Ein Semantic Layer kennt Measures, Dimensions und Darstellungsverhalten. Eine Governance-Plattform kennt unternehmensweite Begriffe, Policies, Verantwortlichkeiten und systemübergreifende Entscheidungen.

Diese Perspektiven müssen verbunden, aber nicht in einem anonymen zentralen Datensatz zusammengepresst werden.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/keep-metadata-close-to-the-source-img1-de.png"
        alt="Quellsystem, Transformations-Repository, semantische und BI-Schicht sowie Governance-Plattform verbunden mit einer einheitlichen Discovery- und Control-Schicht"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Jedes Metadatenattribut benötigt einen verantwortlichen Ursprung. Zentrale Discovery verbindet quellnative, transformatorische, semantische und Governance-Kontexte, ohne jedes Attribut zentral pflegen zu müssen.
    </figcaption>
</figure>

### Quellnative Metadaten gehören zur operativen Quelle

Das Quellsystem oder das dafür verantwortliche Fachteam sollte normalerweise autoritativ bleiben für:

- ursprüngliche Bedeutung von Geschäftsobjekten und Feldern
- gültige Codes und Statusübergänge
- Quellidentifikatoren
- Source Keys und Constraints
- Source-of-Record-Verantwortung
- operative Prozessregeln
- quellseitige Hilfetexte
- Audit-Verhalten
- lokale System-Ownership

Ein Auftragsmanagementsystem kann beispielsweise definieren, dass `ORDER_STATUS = C` bedeutet, dass eine Auftragsposition vor der Erfüllung storniert wurde. Diese Quellbedeutung darf nicht erst downstream erfunden werden.

Die zentrale Schicht kann ein normalisiertes Label wie `Storniert` anzeigen. Sie sollte aber weiterhin den ursprünglichen Code, das Quellobjekt, die Quellbeschreibung und den verantwortlichen Source Owner erhalten.

### Transformationsmetadaten gehören zum Transformationscode

Abgeleitete Bedeutung sollte bei der versionierten Implementierung bleiben, die sie erzeugt.

Typische Transformationsmetadaten sind:

- Source References
- umbenannte und abgeleitete Felder
- Joins
- Filter
- Berechnungen
- Historisierungsregeln
- Modellabhängigkeiten
- Tests
- Contracts
- Modell- und Feldbeschreibungen
- Codeversion
- fachliche Begründung der Transformation

Definiert ein Modell `is_open_order`, dann ist die versionierte Transformationsregel die autoritative technische Logik. Wird die Formel zusätzlich manuell in einen Katalog kopiert, entsteht eine zweite Darstellung, die abweichen kann.

Das bessere Muster lautet:

```text
Katalogdarstellung
→ referenziert Transformationsmodell und Version
→ zeigt geparste oder exportierte Logik
→ ergänzt die freigegebene fachliche Interpretation
```

Die technische Implementierung bleibt beim Code. Die Governance-Plattform verbindet sie mit fachlicher Bedeutung, Ownership und Policy.

### Semantische Metadaten gehören zum Semantic oder BI Layer

Der Semantic Layer sollte autoritativ bleiben für analytisches Verhalten wie:

- Measures
- Dimensions
- Hierarchien
- Anzeigenamen
- Zahlen- und Datumsformate
- Einheiten und Währungen
- Aggregationsverhalten
- Default Filter
- Time-Intelligence-Regeln
- unterstützte analytische Beziehungen
- Zertifizierung gemeinsamer Measures
- benutzerorientierte Synonyme

Ein Warehouse-Feld kann technisch korrekt sein, während ein semantisches Measure festlegt, wie Anwender es tatsächlich verwenden.

`net_amount_reporting_currency` kann beispielsweise nach Kunde und Produkt additiv sein. Das zertifizierte Measure `Open Order Value` kann zusätzlich Statusfilter und eine Stichtagslogik anwenden. Diese semantischen Regeln sollten mit dem Modell verbunden bleiben, das sie ausführt.

### Governance-Metadaten gehören zu verantwortlichen Governance-Prozessen

Unternehmensweite Entscheidungen sollten normalerweise in dem Governance-System oder Workflow gepflegt werden, der sie kontrolliert.

Beispiele sind:

- freigegebene Fachbegriffe
- unternehmensweite Definitionen
- Accountability von Data Owner und Data Steward
- Sensitivitätsklassifikation
- Aufbewahrungsklasse
- zulässige Nutzung
- Policy-Zuordnung
- Ausnahmefreigabe
- Zertifizierungsstatus
- Überprüfungsdaten
- systemübergreifende Beziehungen
- Entscheidungen zur Konfliktauflösung

Governance-Metadaten sind nicht automatisch „zentrale Metadaten“. Eine Aufbewahrungsklasse kann zentral freigegeben, aber in mehreren Plattformen technisch umgesetzt werden. Ein Owner kann in einem Domänenregister gepflegt und vom Katalog referenziert werden. Entscheidend ist, welcher Prozess den Wert kontrolliert und aktuell halten kann.

## Autorität, Speicherort und Sichtbarkeit sind unterschiedliche Entscheidungen

Für jedes Metadatenattribut müssen drei Fragen getrennt beantwortet werden:

1. **Wo ist der Wert autoritativ?**
2. **Wo wird der Wert physisch gespeichert oder gecacht?**
3. **Wo muss der Wert sichtbar und nutzbar sein?**

Die Antworten können auf drei unterschiedliche Systeme zeigen.

Eine Feldbeschreibung kann in einem versionierten Repository autoritativ sein, als Kopie in einem zentralen Suchindex liegen und in einer BI-Entwicklungsoberfläche angezeigt werden. Die zentrale Kopie unterstützt Discovery. Das Repository bleibt der freigegebene Ursprung.

Ein Qualitätsergebnis kann im Monitoring-System autoritativ sein, dort als Zeitreihe gespeichert und zentral für Governance-Reporting zusammengefasst werden.

Eine unternehmensweite Sensitivitätsklassifikation kann in einem Governance-Workflow freigegeben, als Warehouse-Tag synchronisiert und in einer BI-Plattform angezeigt werden. Der Governance-Workflow bleibt die Freigabeinstanz, auch wenn technische Kopien die Durchsetzung steuern.

Wer diese Dimensionen vermischt, zentralisiert unnötig.

## Die einfachste tragfähige Umsetzung

Eine brauchbare Architektur kann ohne große Enterprise-Metadatenplattform beginnen.

Wähle ein wichtiges Datenprodukt und erstelle ein Metadata Ownership Register. Für jedes Attribut werden festgehalten:

- stabiler Asset Identifier
- Metadatenattribut
- autoritatives System oder Prozess
- verantwortliche Rolle
- Quellobjekt oder Quelldatensatz
- Erfassungsmethode
- Aktualisierungsrichtung
- Synchronisierungsfrequenz
- Freigabestatus
- letzte erfolgreiche Erfassung
- Konfliktregel
- nachgelagerte Consumer

Ein kleines Beispiel:

```yaml
asset_id: sales.order_line.net_amount

attributes:
  source_meaning:
    authority: source.order_management.NETWR
    accountable_role: Sales Operations Data Owner
    mode: reference

  physical_type:
    authority: warehouse.prod.sales_order_line.net_amount
    mode: harvested_copy
    refresh: every_schema_scan

  transformation_logic:
    authority: dbt.model.fct_sales_order_line.net_amount
    mode: reference
    version: git_commit

  semantic_label:
    authority: semantic.sales.net_amount
    mode: harvested_copy

  sensitivity:
    authority: governance.classification.sales_amount
    mode: approved_sync
```

Die Umsetzung kann zunächst verwenden:

- Datenbankkommentare für quellnahe Beschreibungen
- versioniertes YAML für Transformations- und Governance-Deklarationen
- geparste Transformationsartefakte für Abhängigkeiten und Tests
- BI-Exporte oder APIs für Measures und Reportbeziehungen
- Governance-Workflow-Datensätze für Ownership, Klassifikation und Freigabe
- einen zentralen Index, der stabile Identifikatoren und Provenance verbindet

Das Ziel ist nicht, jeden Wert zu duplizieren. Es muss nachgewiesen werden, dass jedes wichtige Attribut einen bekannten Ursprung, einen verantwortlichen Maintainer und einen kontrollierten Weg in die zentrale Discovery besitzt.

## Speichern, kopieren, referenzieren oder synchronisieren

Nicht jeder Metadatenwert sollte auf dieselbe Weise bewegt werden.

Das passende Muster hängt davon ab, wo das Wissen gepflegt wird, ob die Quelle abgefragt werden kann, ob eine lokale Kopie erforderlich ist, wie schnell sich der Wert verändert und welches System ihn freigibt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/keep-metadata-close-to-the-source-img2-de.png"
        alt="Entscheidungsfluss zur Auswahl zwischen zentralem Speichern, Referenzieren der Quelle, Kopieren mit Provenance und bidirektionaler Synchronisierung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das Bewegungsmuster wird pro Attribut gewählt. Unkontrollierte bidirektionale Bearbeitung verursacht das höchste Konflikt- und Verantwortungsrisiko.
    </figcaption>
</figure>

### Zentral speichern

Zentrale Speicherung ist geeignet, wenn die Metadaten eine unternehmensweite Entscheidung repräsentieren und keinen besseren operativen Pflegeort besitzen.

Beispiele:

- unternehmensweiter Glossarbegriff
- plattformübergreifende Datendomänenzuordnung
- Governance-Review-Status
- Policy-Ausnahme
- unternehmensweite Zertifizierungsentscheidung
- Beziehung zwischen Assets aus unterschiedlichen Systemen

Hauptrisiko:

- die zentrale Plattform wird zum Ablageort für Werte, die weiterhin mit ihren Quellsystemen hätten verbunden bleiben sollen

Zentrale Speicherung wird durch zentrale Verantwortung begründet, nicht allein durch zentrale Sichtbarkeit.

### Quelle referenzieren

Eine Referenz ist geeignet, wenn die Quelle erreichbar bleibt und den Wert zuverlässig bereitstellt.

Beispiele:

- Link auf eine versionierte Transformationsdefinition
- Code-Liste eines Quellsystems
- semantische Measure-Definition
- Policy-Datensatz
- Quality Monitor
- Run Evidence

Hauptrisiko:

- die Referenz bricht, Berechtigungen verhindern den Zugriff oder die Quelle bietet keinen stabilen Identifier

Eine Referenz sollte deshalb enthalten:

- Identität des Quellsystems
- stabiles Quellobjekt
- gegebenenfalls Quellversion
- Zugriffsmethode
- letzte erfolgreiche Validierung
- Fallback-Verhalten

### Mit Provenance kopieren

Eine Kopie ist geeignet, wenn zentrale Suche, Performance, Resilienz oder historische Vergleiche eine lokale Darstellung benötigen.

Beispiele:

- in einen Katalogindex übernommenes Datenbankschema
- aus einem Repository kopierte Modellbeschreibungen
- importierte semantische Objekte
- für die Suche gecachte Ownership-Datensätze
- zentral zusammengefasster aktueller Qualitätsstatus

Hauptrisiko:

- die Kopie wird veraltet und fälschlich als autoritativer Wert behandelt

Jeder kopierte Wert sollte enthalten:

- autoritative Quelle
- Identifier des Quellobjekts
- Erfassungszeitpunkt
- Quellversion
- beim Import angewandte Transformation
- Synchronisierungsstatus
- Ablauf- oder Freshness-Erwartung
- Konfliktstatus

Ein kopierter Wert ohne Provenance ist kein governter Metadatenwert. Er ist ein undokumentiertes Duplikat.

### Bidirektional synchronisieren

Bidirektionale Synchronisierung ist nur geeignet, wenn beide Systeme kontrollierte Bearbeitung unterstützen müssen und ein eindeutiges Konfliktmodell vorhanden ist.

Mögliche Beispiele:

- eine im Katalog vorgeschlagene Fachbeschreibung wird über einen geprüften Pull Request in ein Repository zurückgeschrieben
- ein Governance-Workflow gibt eine Klassifikation frei und veröffentlicht sie in technische Enforcement-Systeme
- ein Quellteam aktualisiert Ownership über einen kontrollierten zentralen Workflow, der in das Domänenregister schreibt

Hauptrisiko:

- konkurrierende Änderungen, Schleifen, stille Überschreibungen und unklare Freigabeautorität

Das sichere Muster ist keine uneingeschränkte Zwei-Wege-Bearbeitung, sondern ein kontrollierter Workflow:

```text
Änderung vorschlagen
→ validieren
→ Autorität bestimmen
→ prüfen
→ freigeben
→ in autoritatives System schreiben
→ bestätigten Wert erneut veröffentlichen
```

Die zentrale Plattform kann eine Aufgabe oder einen Vorschlag auslösen. Sie darf einen quellverantworteten Wert nicht still überschreiben.

## Source Ownership mit zentraler Discovery

Eine föderierte Metadatenarchitektur hält die Autorität verteilt und macht Metadaten trotzdem zentral auffindbar und steuerbar.

Quellsysteme veröffentlichen ihre Metadaten über Connectoren, Exporte, Events oder APIs. Der zentrale Index erzeugt Suche, Beziehungen, Governance Views und maschinenlesbare Schnittstellen.

Rückwege stehen für Aufgaben, Review Requests oder freigegebene Änderungen. Sie sind keine Erlaubnis für stille Ersetzungen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/keep-metadata-close-to-the-source-img3-de.png"
        alt="Datenbankkatalog, dbt-Repository, semantisches Modell, Identity-Plattform und Observability-System veröffentlichen Metadaten in einen zentralen Metadatenindex"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Zentrale Discovery kann verteilte Metadatenautoritäten verbinden. Änderungen sollten über explizite Aufgaben und Freigabeworkflows zurückgeführt werden, nicht über unkontrollierte Überschreibungen.
    </figcaption>
</figure>

Ein zentraler Metadatendatensatz sollte folgende Fragen beantworten können:

- Welche Quelle hat diesen Wert geliefert?
- Ist der Wert autoritativ, kopiert, abgeleitet oder vorgeschlagen?
- Wer kann eine Änderung freigeben?
- Welche Quellversion wurde erfasst?
- Wann wurde zuletzt synchronisiert?
- Hat sich die Quelle seit der Erfassung verändert?
- Zeigt ein anderes System einen widersprüchlichen Wert?
- Welche nachgelagerten Systeme verwenden den Wert?
- Was geschieht, wenn die Quelle nicht erreichbar ist?
- Kann der Wert eine automatisierte Kontrolle auslösen?

Damit wird die zentrale Plattform zu einer Control Plane und nicht zu einer zweiten Autorenumgebung für jedes Metadatenfeld.

## Konkretes Beispiel: `sales_amount`

Betrachten wir ein Quellfeld `NETWR`, das in `sales_amount` transformiert, über ein semantisches Measure `Net Sales` bereitgestellt und in einer Governance-Plattform dokumentiert wird.

### Quellsystem

Das Quellsystem bleibt autoritativ für:

- `NETWR` als ursprünglichen technischen Identifier
- Speicherung in Belegwährung
- gültige Quelldatensätze
- quellseitige Stornierungszustände
- Source Owner
- Source Constraints

### Transformations-Repository

Die Transformationsschicht bleibt autoritativ für:

- Umbenennung von `NETWR` in `sales_amount`
- Währungsumrechnung
- Ausschlussregeln
- Behandlung von Retouren
- Joins auf Wechselkurse
- Modelltests
- Source-to-Target-Lineage
- Codeversion

Beispiel:

```yaml
models:
  - name: fct_sales_order_line
    description: Governed sales-order-line fact model.
    columns:
      - name: sales_amount
        description: >
          Net order-line amount in reporting currency after approved
          line-level discounts and before tax.
        config:
          meta:
            authoritative_logic: true
            source_field: ORDER_ITEM.NETWR
            business_term: net_sales_amount
```

Die Repository-Beschreibung erklärt das abgeleitete Feld. Sie sollte nicht den gesamten Quellprozess oder die unternehmensweite Policy neu definieren.

### Semantic und BI Layer

Der Semantic Layer bleibt autoritativ für:

- benutzerorientiertes Label `Net Sales`
- Aggregationsverhalten
- Währungsformatierung
- unterstützte Dimensions
- Default Date Context
- Identifier des zertifizierten Measures
- Status als lokales oder gemeinsames Measure

Ein Report kann weiterhin ein lokales Measure erstellen. Die zentrale Metadatenschicht sollte diese Abweichung erfassen, anstatt sie still als zertifiziert zu behandeln.

### Governance-Plattform

Die Governance-Plattform bleibt autoritativ für:

- freigegebenen Unternehmensbegriff
- Data Owner und Data Steward
- Sensitivität
- zulässige Nutzung
- Zertifizierungsstatus
- Überprüfungsdatum
- Beziehung zur Financial-Reporting-Policy

Die Plattform verbindet diese Entscheidungen mit Quellfeld, Transformationsspalte und semantischem Measure.

### Zentraler Index

Der zentrale Index wählt nicht ein Textfeld aus und verwirft den Rest. Er zeigt ein verbundenes Profil:

```text
Ursprüngliche Quellbedeutung
+ abgeleitete Transformationsdefinition
+ semantisches Verhalten
+ Governance-Entscheidung
+ aktuelle operative Evidenz
+ Consumer-Beziehungen
```

Jeder Beitrag behält Provenance und Autorität.

## Praktische Umsetzungsmuster

### Datenbanken

Nutze datenbanknative Metadaten für Fakten, die die Datenbank aktuell halten kann:

- Objekt- und Feldidentität
- Datentypen
- Nullability
- Keys und Constraints
- Grants
- Views und Abhängigkeiten
- Datenbankkommentare, wenn sie operativ gepflegt werden

Der Datenbankkatalog sollte nicht zum Enterprise Glossary gemacht werden. Ein Feldkommentar kann lokalen Kontext liefern. Unternehmensbegriffe, Policy-Freigaben und plattformübergreifende Beziehungen benötigen normalerweise einen anderen Prozess.

Sind Source Comments wertvoll, sollten sie automatisiert erfasst und mit dem Identifier des Quellobjekts erhalten werden.

### dbt und Transformations-Repositories

Abgeleitete Logik, Tests, Abhängigkeiten, Contracts und Modellbeschreibungen gehören zum versionierten Transformationscode.

Ein praktisches Muster lautet:

```text
YAML und SQL
→ Metadatenartefakte parsen oder erzeugen
→ Pflichtfelder validieren
→ in zentralen Index veröffentlichen
→ Repository bleibt autoritativ
```

Custom Metadata kann Ownership-Referenzen, Klassifikationen, Domäne, Lifecycle-Status oder Governance-Verknüpfungen aufnehmen. Diese Werte benötigen ein kontrolliertes Vokabular und Validierung. Eine frei verwendbare Key-Value-Struktur allein erzeugt noch keine Governance.

SQL-Ausdrücke sollten nicht als manuell gepflegte Kopie in einen Katalog übertragen werden. Der Katalog sollte Modell, Feld und Codeversion referenzieren und anschließend die fachliche Erklärung ergänzen, die Code allein nicht liefern kann.

### Semantic und BI Tools

Measures, Dimensions, Formate, Hierarchien und Presentation Rules gehören zu dem semantischen Modell, das sie ausführt.

Erfasst werden sollten:

- Modellobjekte
- Measure Expressions
- Reportabhängigkeiten
- lokale Berechnungen
- Nutzung
- Owner
- Zertifizierungsstatus
- Zugriffsbeziehungen

Die zentrale Plattform sollte unterscheiden zwischen:

- gemeinsamem semantischem Measure
- reportlokaler Berechnung
- kopiertem Label
- Business-Glossary-Begriff
- erkanntem Synonym

Diese Objekte können ähnliche Namen verwenden, besitzen aber unterschiedliche Autorität.

### Governance-Plattformen

Governance-Plattformen eignen sich für Entscheidungen, die mehrere Systeme betreffen:

- Fachvokabular
- Accountability
- Policy
- Zertifizierung
- Exception Management
- Review Workflows
- systemübergreifende Beziehungen
- Konfliktauflösung

Für importierte technische Metadaten sollte die Plattform Referenzen und Provenance speichern. Data Stewards sollten nicht gezwungen werden, Schemas, Lineage oder Laufzeitevidenz manuell neu zu erstellen, wenn Maschinen sie aus den Ursprungssystemen erfassen können.

## Konflikt- und Vorrangregeln müssen explizit sein

Verteilte Metadaten erzeugen Konflikte. Die Architektur muss sie sichtbar machen und auflösen, statt sie zu verstecken.

Ein pragmatisches Vorrangmodell lautet:

```text
Freigegebene autoritative Deklaration
> autoritativ erzeugter Systemfakt
> freigegebene synchronisierte Kopie
> erfasste Kopie
> abgeleiteter Vorschlag
> erkannter Vorschlag
> ungeprüfter manueller Eintrag
```

Diese Reihenfolge ist nicht universell. Vorrang muss pro Attributtyp definiert werden.

Beispiele:

- der aktuelle physische Datentyp kommt aus der Datenbank, nicht aus dem Glossar
- die freigegebene fachliche Definition kommt aus dem verantwortlichen Governance-Prozess, nicht aus einem Scanner
- der Transformationsausdruck kommt aus versioniertem Code, nicht aus kopierter Prosa
- die aktuelle Measure Expression kommt aus dem semantischen Modell, nicht aus einem Report-Screenshot
- die letzte Aktualisierung kommt aus operativer Evidenz, nicht aus einem erwarteten Zeitplan

Bei Konflikten sollten beide Werte und ihre Provenance erhalten bleiben, bis der Konflikt gelöst ist.

Ein Konfliktdatensatz sollte enthalten:

- Asset und Attribut
- konkurrierende Werte
- autoritative Quellen
- Versionen und Zeitpunkte
- verantwortlichen Decision Owner
- Impact Assessment
- Lösungsstatus
- ausgewählten Wert
- Begründung
- Gültigkeitsbeginn

Ein stilles Last-Write-Wins-Verhalten ist für governte Metadaten ungeeignet.

## Ausschließlich zentrale Dokumentation ist ein Anti-Pattern

Ausschließlich zentrale Dokumentation beginnt meist mit einer guten Absicht. Teams wünschen einen gemeinsamen Suchort und kopieren deshalb Beschreibungen in einen Katalog.

Das Problem wird erst später sichtbar.

Das Quellfeld ändert sich. Die Transformationslogik entwickelt sich weiter. Das semantische Measure wird angepasst. Die zentrale Beschreibung bleibt unverändert, weil die eigentliche Änderung in einem anderen System stattgefunden hat. Anwender sehen weiterhin eine sauber formulierte, aber veraltete Erklärung.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/keep-metadata-close-to-the-source-img4-de.png"
        alt="Vergleich zwischen einem Anti-Pattern mit ausschließlich zentraler Dokumentation und einem Zielmuster mit Quellbedeutung, Transformationslogik, Provenance und kontrolliertem Review für sales_amount"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Manuelle zentrale Kopien driften, wenn Quellen und Transformationen an anderer Stelle verändert werden. Das Zielmuster belässt Bedeutung und Logik bei ihren verantwortlichen Ursprüngen und nutzt die zentrale Plattform für Provenance, Verknüpfungen und Review.
    </figcaption>
</figure>

Typische Warnsignale sind:

- dieselbe Beschreibung ist in mehreren Systemen editierbar
- kein Feld benennt die autoritative Quelle
- kopierte Werte besitzen keinen Erfassungszeitpunkt
- Quellversionen werden nicht erhalten
- Ownership benennt eine Person, aber keine Entscheidungsverantwortung
- Transformationen werden manuell neu beschrieben, statt referenziert
- lokale BI-Berechnungen bleiben unsichtbar
- Synchronisierungsfehler erzeugen keinen Incident
- der Katalog zeigt veraltete Werte ohne Warnung
- ein Connector kann freigegebenen fachlichen Kontext überschreiben

Die Korrektur besteht nicht darin, die zentrale Plattform abzuschaffen. Ihre Rolle muss neu definiert werden.

## Alternative Betriebsmodelle

### Quellnah mit gemeinsamen Konventionen

Metadaten bleiben hauptsächlich in Quellsystemen, Repositories und BI-Modellen.

Geeignet, wenn:

- die Plattformlandschaft klein ist
- Teams konsistente Versionierung und Benennung verwenden
- zentrale Discovery-Anforderungen begrenzt sind
- Ownership eindeutig ist

Erforderliche Schutzmaßnahmen:

- stabile Identifikatoren
- gemeinsames Metadatenschema
- dokumentierte Autorität
- reproduzierbare Exporte
- regelmäßige Validierung

### Zentraler Index mit verteilter Autorität

Metadaten werden in eine zentrale Such- und Beziehungsschicht übernommen, während ihre autoritative Pflege verteilt bleibt.

Geeignet, wenn:

- mehrere Plattformen gemeinsam durchsucht werden müssen
- Lineage und Impact Systemgrenzen überschreiten
- Governance-Teams unternehmensweite Views benötigen
- technische Teams ihre Plattformverantwortung behalten

Dies ist meist das Zielmuster für einen heterogenen Data Stack.

### Governance-gesteuerte Attribute mit technischer Verteilung

Ausgewählte Attribute werden zentral freigegeben und an Ausführungsplattformen verteilt.

Geeignet für:

- Sensitivität
- Aufbewahrung
- Zertifizierung
- zulässige Nutzung
- Domänenzuordnung
- Policy-Referenzen

Erforderliche Schutzmaßnahmen:

- eine Freigabeautorität
- kontrolliertes Vokabular
- technische Zuordnung
- Synchronisierungsevidenz
- Rollback- und Exception Handling

### Kontrollierte Beiträge über Pull Requests oder Workflow Tasks

Anwender können zentrale Änderungen vorschlagen. Das autoritative System wird jedoch erst nach Review aktualisiert.

Geeignet, wenn:

- Source Repositories versioniert sind
- Governance-Anwender Code nicht direkt bearbeiten sollen
- jede Änderung auditierbar sein muss
- automatisierte Validierung verfügbar ist

Dieses Muster verbindet nutzbare zentrale Workflows mit quellverantworteter Wahrheit.

## Entscheidungshilfe

Für jedes Metadatenattribut sollten folgende Fragen beantwortet werden:

1. Wo entsteht das zugrunde liegende Wissen?
2. Welches Team kann erkennen, dass der Wert falsch ist?
3. Welches System ändert sich, wenn sich die reale Bedeutung ändert?
4. Ist der Wert erzeugt, beobachtet, deklariert oder abgeleitet?
5. Benötigt der Wert eine fachliche Freigabe?
6. Muss der Wert technische Kontrollen steuern?
7. Erfordert zentrale Suche eine lokale Kopie?
8. Wie schnell kann der Wert veralten?
9. Kann die Quelle einen stabilen Identifier und eine Version liefern?
10. Welche Konfliktregel gilt?
11. Kann eine zentrale Änderung sicher zurückgeschrieben werden?
12. Welche Evidenz beweist eine erfolgreiche Synchronisierung?

Die Antworten bestimmen, ob gespeichert, referenziert, kopiert oder synchronisiert wird.

## Zentrale Empfehlungen

1. Weise jedem wichtigen Metadatenattribut einen verantwortlichen Ursprung zu.
2. Trenne Autorität von physischem Speicherort und Sichtbarkeit.
3. Halte Quellbedeutung bei Quellsystemen und verantwortlichen Quellteams.
4. Halte abgeleitete Logik, Tests und Abhängigkeiten beim versionierten Transformationscode.
5. Halte Measures, Dimensions und analytisches Verhalten im Semantic Layer.
6. Halte Unternehmensvokabular, Policies, Accountability und systemübergreifende Entscheidungen in Governance-Prozessen.
7. Nutze zentrale Plattformen für Discovery, Beziehungen, Workflows, APIs und Control Views.
8. Kopiere Metadaten nur mit Provenance, Zeitpunkten, Versionen und Synchronisierungsstatus.
9. Bevorzuge Referenzen, wenn die Quelle stabil und erreichbar ist.
10. Begrenze bidirektionale Synchronisierung auf kontrollierte Vorschlags-, Validierungs- und Freigabeworkflows.
11. Definiere Vorrangregeln pro Metadatenattribut und nicht als universelles Last-Write-Wins.
12. Erhalte widersprüchliche Werte, bis eine verantwortliche Entscheidung sie auflöst.
13. Unterscheide quellverantwortete, erfasste, abgeleitete, vorgeschlagene und freigegebene Werte.
14. Führe Aufgaben und freigegebene Änderungen an Quellsysteme zurück; verwende keine stillen Überschreibungen.
15. Beginne mit einem kritischen Datenprodukt und beweise das Ownership- und Synchronisierungsmodell End-to-End.

## Der nächste Schritt: Metadaten automatisiert erfassen

Metadaten nah an ihrer autoritativen Quelle zu halten, löst das Ownership-Problem. Es löst noch nicht das Skalierungsproblem.

Schemas, Codeabhängigkeiten, Modellbeschreibungen, semantische Objekte, Laufzeitevidenz und Nutzung verändern sich kontinuierlich. Würden sie manuell erfasst, entstünde dasselbe Problem veralteter zentraler Dokumentation nur in einer anderen Form.

Der nächste Teil, **Metadaten automatisiert erfassen**, erklärt, wie Connectoren, Scanner, Parser, APIs, Logs und Events quellverantwortete Metadaten erfassen können, ohne Provenance, Versionen, Vertrauensniveau und Freigabegrenzen zu verlieren.
