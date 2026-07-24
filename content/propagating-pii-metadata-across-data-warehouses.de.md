---
title: PII-Metadaten durch Data Warehouses propagieren
description: Ein kontrollierter Ansatz, um PII-Klassifikationen beim Übergang durch RAW-, Conform-, Core- und Analytics-Modelle zu erhalten, zusammenzuführen und zu prüfen.
category: Data Governance
tags:
  - data-governance
  - pii
  - metadaten-propagierung
  - dbt
  - column-lineage
  - data-lineage
  - datenklassifikation
  - data-warehouse
  - datenschutz
  - ci
order: -1
author: Thomas Lindackers
series: end-to-end-data-governance
seriesPart: 4
seriesTitle: End-to-End Data Governance
hero: images/playbooks/propagating-pii-metadata-across-data-warehouses-hero.png
---

## PII-Metadaten müssen den Daten folgen

Part 3, [Automatische RAW-Generierung mit dbt-Macros](/playbooks/automatic-raw-generation-using-dbt-macros), hat kontrollierte RAW-Modelle aus physischen Warehouse-Metadaten und freigegebenen Governance-Metadaten erzeugt.

Der RAW Layer ist nun explizit, reviewbar und versioniert.

Das nächste Problem beginnt, sobald ein nachgelagertes Modell diese Spalten auswählt, umbenennt, kombiniert oder transformiert.

```sql
select
    customer_id,
    lower(trim(email)) as normalized_email,
    concat(first_name, ' ', last_name) as customer_name
from {{ ref('raw_crm_customer') }}
```

Die Quellspalten können bereits freigegebene Metadaten besitzen:

```yaml
email:
  pii: true
  pii_category: email
  sensitivity: confidential
  classification_status: approved

first_name:
  pii: true
  pii_category: name
  sensitivity: confidential
  classification_status: approved

last_name:
  pii: true
  pii_category: name
  sensitivity: confidential
  classification_status: approved
```

Auch die nachgelagerten Spalten bleiben sensibel.

dbt leitet das korrekte Governance-Ergebnis jedoch nicht automatisch aus der SQL-Semantik ab.

> **Data Lineage zeigt, woher eine Spalte stammt. Eine Propagierungsregel bestimmt, welche Metadaten daraus entstehen.**

## Modell-Lineage reicht nicht aus

Modell-Lineage beantwortet:

```text
Welches Modell hängt von welchem Upstream-Modell ab?
```

Das ist für Deployment, Impact-Analyse und Ownership wichtig.

PII-Propagierung benötigt eine präzisere Frage:

```text
Welche Upstream-Spalten tragen zu dieser Zielspalte bei?
```

Dafür ist Column-Level Lineage oder ein explizites Column Mapping erforderlich.

Ein Modell kann von einem sensiblen Upstream-Modell abhängen, ohne sensible Spalten auszuwählen.

Umgekehrt kann eine einzige abgeleitete Spalte mehrere sensible Quellspalten kombinieren.

Ein Propagierungssystem benötigt deshalb mindestens:

- Modellabhängigkeiten;
- Namen der Zielspalten;
- referenzierte Quellspalten;
- Transformationstyp;
- freigegebene Quellmetadaten;
- explizite Ziel-Overrides;
- Konfliktregeln.

Das dbt-Manifest stellt Ressourcenmetadaten und direkte Ressourcenbeziehungen bereit. Column-Level Lineage benötigt SQL-Parsing oder einen anderen Lineage-Mechanismus und kann bei mehrdeutigem SQL, komplexen Lateral-Konstrukten oder Python-Modellen unvollständig sein.

Die Architektur muss Unsicherheit verarbeiten, statt vollständige automatische Auflösung vorzutäuschen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/propagating-pii-metadata-across-data-warehouses-img1-de.png"
        alt="PII-Metadaten bewegen sich durch RAW-, Conform-, Core-, Analytics- und Consumption-Layer mit einem getrennten kontrollierten Pfad für Lineage, Propagierungsregeln, Validierung und Freigabe"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Lineage liefert die Grundlage. Propagierungsregeln und Governance-Review bestimmen die freigegebenen Downstream-Metadaten.
    </figcaption>
</figure>

## Metadaten nicht blind kopieren

Die einfachste denkbare Umsetzung kopiert alle Quellmetadaten auf jede nachgelagerte Spalte.

Das scheitert schnell.

Betrachte folgende Transformationen:

```sql
email as email
lower(email) as normalized_email
split_part(email, '@', 2) as email_domain
sha2(email) as email_hash
count(distinct email) as customer_count
'CRM' as source_system
```

Alle sechs Spalten referenzieren dieselbe Quellspalte oder stehen neben ihr. Sie besitzen dennoch nicht dieselbe Bedeutung.

Blindes Kopieren erzeugt zwei Fehlerarten.

### Unterklassifikation

Ein transformiertes Feld identifiziert oder beschreibt weiterhin eine Person, verliert aber seine Metadaten.

Beispiele:

- umbenannte E-Mail-Adresse;
- normalisierte Telefonnummer;
- zusammengesetzter vollständiger Name;
- deterministischer Kunden-Hash;
- extrahierte Account-ID.

### Überklassifikation

Ein technisches oder ausreichend aggregiertes Ergebnis übernimmt eine Klassifikation, die nicht mehr gilt.

Beispiele:

- konstante Quellsystembezeichnung;
- freigegebenes hoch aggregiertes Ergebnis;
- boolesches Quality Flag;
- Datensatzanzahl;
- technischer Ladezeitstempel.

Die Propagation Engine muss deshalb die Transformation klassifizieren und darf nicht nur eine Abhängigkeit erkennen.

## Propagierungsregeln nach Transformationstyp definieren

Ein praktikables Regelwerk kann mit sieben Transformationsklassen beginnen.

### 1. Direkte Weitergabe

```sql
email
```

Empfohlene Regel:

```text
Freigegebene Spaltenmetadaten kopieren.
```

Dies ist der stärkste Kandidat für automatische Propagierung.

### 2. Umbenennung

```sql
email as customer_email
```

Empfohlene Regel:

```text
Freigegebene Metadaten kopieren und die Quellspalte dokumentieren.
```

Ein neuer Name verändert nicht die Bedeutung der Daten.

### 3. Cast oder Formatierung

```sql
lower(trim(email)) as normalized_email
cast(customer_id as varchar) as customer_id_text
```

Empfohlene Regel:

```text
Quellklassifikation erhalten, sofern keine freigegebene Regel etwas anderes festlegt.
```

Case Conversion, Trimming, Formatierung und Typkonvertierung verändern üblicherweise die Darstellung, nicht die Sensitivität.

### 4. Kombination

```sql
concat(first_name, ' ', last_name) as full_name
coalesce(mobile_phone, landline_phone) as preferred_phone
```

Empfohlene Regel:

```text
Klassifikationen aller beitragenden Quellen zusammenführen.
```

Das Ergebnis sollte normalerweise die strengste Sensitivität und die kontrollierte Vereinigung relevanter PII-Kategorien erhalten.

Beispiel:

```yaml
pii: true
pii_category:
  - name
sensitivity: confidential
classification_status: proposed
propagated_from:
  - raw_crm_customer.first_name
  - raw_crm_customer.last_name
```

Der Status darf nur dann `approved` bleiben, wenn eine freigegebene Transformationsregel diese Kombination abdeckt. Andernfalls ist `proposed` zu verwenden.

### 5. Hashing, Tokenisierung oder Pseudonymisierung

```sql
sha2(email) as customer_token
```

Empfohlene Regel:

```text
Sensitivität erhalten, sofern keine freigegebene Governance-Regel sie explizit ändert.
```

Ein deterministischer Hash kann weiterhin Matching, Verknüpfung oder Re-Identifikation ermöglichen. Er darf nicht allein deshalb als anonym gelten, weil der Klartext nicht mehr sichtbar ist.

Die Kategorie kann sich ändern:

```yaml
pii: true
pii_category: pseudonymous_identifier
sensitivity: confidential
classification_status: proposed
```

### 6. Aggregation

```sql
count(distinct customer_id) as customer_count
```

Empfohlene Regel:

```text
Nicht automatisch herabstufen.
Das Ergebnis durch eine Aggregationsprüfung führen.
```

Das korrekte Ergebnis hängt ab von:

- Aggregationsgranularität;
- Mindestgruppengröße;
- Filtern;
- dünn besetzten Dimensionen;
- Suppression Rules;
- Differencing-Risiken;
- erlaubter Nutzung.

Eine monatliche Anzahl je Land kann in einem Kontext nicht personenbezogen sein. Eine Anzahl nach seltener Erkrankung und Postleitzahl kann weiterhin hochsensibel sein.

### 7. Konstante oder technische Spalte

```sql
'CRM' as source_system
current_timestamp as loaded_at
```

Empfohlene Regel:

```text
Keine Klassifikation von benachbarten Quellspalten übernehmen.
```

Technische Spalten benötigen eigene Metadaten, aber nicht die PII-Metadaten unabhängiger Inputs.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/propagating-pii-metadata-across-data-warehouses-img2-de.png"
        alt="Sieben Propagierungsregeln für direkte Weitergabe, Umbenennung, Formatierung, Kombination, Hashing, Aggregation und technische Spalten mit automatischen, regelbasierten und reviewpflichtigen Ergebnissen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Unterschiedliche Transformationstypen erfordern unterschiedliches Propagierungsverhalten. Nur semantisch sichere Fälle sollten vollständig automatisiert werden.
    </figcaption>
</figure>

## Explizite Metadatenergebnisse verwenden

Ein propagiertes Ergebnis sollte zeigen, wie es entstanden ist.

Beispiel:

```yaml
columns:
  - name: normalized_email
    config:
      meta:
        pii: true
        pii_category: email
        sensitivity: confidential
        classification_status: approved
        propagation_method: approved_rule
        propagated_from:
          - raw_crm_customer.email
        propagation_rule: retain_on_normalization
```

Ein generierter Vorschlag kann so aussehen:

```yaml
columns:
  - name: full_name
    config:
      meta:
        pii: true
        pii_category:
          - name
        sensitivity: confidential
        classification_status: proposed
        propagation_method: inferred
        propagated_from:
          - raw_crm_customer.first_name
          - raw_crm_customer.last_name
        review_reason: multiple_sensitive_inputs
```

Eine nicht auflösbare Transformation bleibt explizit:

```yaml
columns:
  - name: customer_segment_code
    config:
      meta:
        classification_status: unreviewed
        propagation_method: unresolved
        review_reason: unsupported_sql_expression
```

Das System darf Unsicherheit niemals in eine stillschweigende Freigabe umwandeln.

## Mehrere Inputs deterministisch auflösen

Eine abgeleitete Spalte kann mehrere Upstream-Inputs mit unterschiedlichen Klassifikationen besitzen.

Beispiel:

```sql
concat(customer_id, ':', email) as contact_key
```

Input-Metadaten:

| Input | PII | Sensitivität | Status |
| --- | --- | --- | --- |
| `customer_id` | true | internal | approved |
| `email` | true | confidential | approved |

Ein konservativer Default lautet:

```text
PII = true, wenn mindestens eine beitragende Quelle PII ist
Sensitivität = strengster Wert der beitragenden Quellen
PII-Kategorie = kontrollierte Zusammenführung oder neue abgeleitete Kategorie
Status = proposed, sofern keine freigegebene Regel die Kombination auflöst
```

Der Metadata Resolver benötigt eine explizite Rangfolge:

```text
public
< internal
< confidential
< restricted
```

Zusätzlich braucht er kontrollierte Category Mappings.

Die Vereinigung von `email` und `customer_identifier` darf nicht zu einem freien String werden:

```text
email + customer id
```

Stattdessen wird eine freigegebene abgeleitete Kategorie verwendet:

```yaml
pii_category: contact_identifier
```

## Priorität von Overrides und Vorschlägen definieren

Nicht jedes Ergebnis sollte generiert werden.

Die stärkste Source of Truth muss gewinnen.

Ein praktikables Prioritätsmodell lautet:

```text
Freigegebener Target Override
> freigegebene Transformationsregel
> propagierte freigegebene Quellmetadaten
> Inference- oder Detection-Vorschlag
```

### Freigegebener Target Override

Ein Steward klassifiziert die Zielspalte explizit.

```yaml
classification_status: approved
classification_source: steward_override
```

Der Propagierungsprozess muss diese Entscheidung erhalten.

### Freigegebene Transformationsregel

Eine wiederverwendbare Regel wurde geprüft.

Beispiel:

```text
lower(trim(email))
→ E-Mail-Klassifikation erhalten
```

Die Engine darf diese Regel automatisch anwenden.

### Propagierte Quellmetadaten

Direkte Weitergabe oder Umbenennung kann freigegebene Upstream-Metadaten übernehmen.

### Detection-Vorschlag

Namens- oder inhaltsbasierte Erkennung kann eine Klassifikation vorschlagen.

Sie darf niemals eine freigegebene Entscheidung überschreiben.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/propagating-pii-metadata-across-data-warehouses-img3-de.png"
        alt="Mehrere klassifizierte Quellspalten werden über Column Lineage, Transformationsregeln, Target Overrides und Konfliktregeln in freigegebene, vorgeschlagene oder ungelöste Zielmetadaten überführt"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Konflikte benötigen eine deterministische Priorität. Freigegebene Zielentscheidungen bleiben stärker als generierte Propagierungsergebnisse.
    </figcaption>
</figure>

## Auch zeilenbasierte Governance muss propagiert werden

Spaltenklassifikation ist nur ein Teil der Governance.

Ein Modell kann neue zeilenbasierte Dimensionen hinzufügen oder entfernen:

- Gesellschaft;
- Region;
- Mandant;
- Abteilung;
- Kundenportfolio;
- Vertragsbereich.

Ein Join kann eine neue Access Domain hinzufügen, obwohl sich keine PII-Spalte verändert.

Beispiel:

```sql
select
    o.order_id,
    o.customer_id,
    c.legal_entity,
    o.amount
from {{ ref('conform_orders') }} o
join {{ ref('conform_customer') }} c
  on o.customer_id = c.customer_id
```

Das Modell enthält jetzt `legal_entity`, das für Row-Level Enforcement erforderlich sein kann.

Column Lineage allein definiert nicht die richtige Zugriffsregel für das gesamte Modell.

Verwende Modellmetadaten:

```yaml
config:
  meta:
    row_access_domain: legal_entity
    row_access_status: proposed
```

Der Propagierungsprozess kann mögliche Access Dimensions erkennen. Das finale Policy Mapping benötigt jedoch ein Review.

## Technische Lineage und Governance Lineage trennen

Technische Lineage beantwortet:

```text
Welcher SQL-Ausdruck hat diese Spalte erzeugt?
```

Governance Lineage ergänzt:

```text
Welche Klassifikationsentscheidung wurde übernommen?
Welche Regel hat sie verändert?
Wer hat das Ergebnis genehmigt?
Welche Schutz-Policy soll gelten?
```

Ein Governance-Lineage-Datensatz kann enthalten:

```yaml
target: analytics_customer.customer_contact
sources:
  - conform_customer.normalized_email
rule: retain_on_formatting
previous_status: approved
result_status: approved
approved_by: data_governance
policy_mapping: email_mask
```

Damit entsteht ein Nachweis, der über einen visuellen Dependency Graph hinausgeht.

## Einen Diff erzeugen — keine versteckte Metadatenänderung

Propagierung sollte wie Codegenerierung funktionieren.

Empfohlener Workflow:

```text
dbt-Projekt parsen
→ Modelle kompilieren oder parsen
→ Column Mappings erzeugen
→ freigegebene Metadaten lesen
→ Propagierungsregeln anwenden
→ Zielmetadaten vergleichen
→ deterministischen Diff erzeugen
→ Governance-Validierung ausführen
→ Review anfordern
```

Der generierte Diff kann:

- Metadaten für eine neue Zielspalte ergänzen;
- `propagated_from` aktualisieren;
- einen Konflikt als ungelöst markieren;
- eine strengere Sensitivität vorschlagen;
- eine fehlende Masking Policy melden;
- einen verwaisten Override erkennen;
- veraltete generierte Metadaten nach Review entfernen.

Er darf den Produktionskatalog oder das Warehouse nicht stillschweigend verändern.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/propagating-pii-metadata-across-data-warehouses-img4-de.png"
        alt="Pull-Request-Workflow, der Modelle parst, Column Lineage erzeugt, Propagierungsregeln anwendet, Metadaten-Diffs erzeugt, Konflikte validiert und vor dem Deployment eine Steward-Freigabe verlangt"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Propagierte Metadaten verändern governten Code. Git-Review hält Inferences, Konflikte und Overrides sichtbar.
    </figcaption>
</figure>

## Das Downstream-Ergebnis validieren

Ein Governance Validator sollte die finalen Zielmetadaten prüfen und nicht nur den Propagierungsprozess.

Sinnvolle Regeln sind:

- jede freigegebene PII-Spalte besitzt eine PII-Kategorie;
- jede freigegebene vertrauliche oder eingeschränkte PII-Spalte besitzt ein Protection Mapping;
- keine `unreviewed`-Spalte gelangt in ein geschütztes Analytics-Modell;
- propagierte Metadaten referenzieren gültige Upstream-Spalten;
- freigegebene Overrides enthalten einen Owner oder Review-Verweis;
- Sensitivität darf ohne freigegebene Regel nicht herabgestuft werden;
- gelöschte Quellspalten hinterlassen keine veralteten Propagierungsverweise;
- Row Access Domains existieren im Entitlement-Modell;
- ein Modell mit sensiblen Spalten besitzt einen verantwortlichen Owner.

Beispielregel:

```text
Wenn pii = true
und classification_status = approved
und sensitivity in [confidential, restricted]
dann muss masking_policy vorhanden sein.
```

Damit werden die Metadaten für das Runtime Enforcement in Part 5 vorbereitet.

## Unvollständige Lineage sicher behandeln

SQL-Lineage kann unvollständig sein.

Typische schwierige Fälle:

- dynamisches SQL;
- Macros mit komplex erzeugten Ausdrücken;
- JSON-Extraktion;
- Lateral Joins;
- Wildcard Selection;
- Python-Modelle;
- adapterspezifische Syntax;
- User-Defined Functions;
- Stored Procedures.

Die sichere Reaktion besteht nicht im Raten.

Verwende vier mögliche Ergebnisse:

```text
Automatisch aufgelöst
Durch freigegebene Regel aufgelöst
Durch expliziten Override aufgelöst
Ungelöst — Review erforderlich
```

Eine ungelöste Spalte sollte die Promotion blockieren, wenn sie sensible Daten offenlegen könnte.

## Empfohlene Implementierungsarchitektur

Eine praktische Umsetzung kann aus folgenden Komponenten bestehen.

### 1. Metadata Registry

Speichert freigegebene Governance-Werte und Target Overrides.

Diese können als code-nahe technische Metadaten in dbt-YAML verbleiben und auf externe Policy- oder Glossarsysteme verweisen.

### 2. Lineage Extractor

Liest:

- dbt-Ressourcen und Abhängigkeiten;
- kompiliertes SQL;
- Column Mappings aus Parser oder Katalog;
- explizite Mappings für nicht unterstützte Fälle.

### 3. Propagation Engine

Wendet an:

- Transformationsklassen;
- Sensitivity Ordering;
- Category Mappings;
- Prioritätsregeln;
- Exception Rules.

### 4. Diff Generator

Erzeugt stabile YAML-Änderungen oder einen Review-Bericht.

### 5. CI Validator

Verhindert, dass ungelöste oder ungültige Metadaten governte Layer erreichen.

### 6. Governance Review

Genehmigt Ausnahmen, Aggregate Downgrades und neue Transformationsregeln.

Das System kann klein beginnen. Direkte Weitergabe, Umbenennung und einfache Normalisierungsregeln reduzieren bereits große Mengen wiederkehrender Arbeit.

## Häufige Propagierungs-Anti-Patterns

### Alle Modellmetadaten auf jede Spalte kopieren

Modellkontext ist keine gültige Spaltenklassifikation.

### Jedes Upstream-PII-Flag auf jede Zielspalte kopieren

Konstanten und unabhängige abgeleitete Felder werden überklassifiziert.

### PII nach Hashing entfernen

Pseudonyme Identifikatoren können weiterhin verknüpfbar und sensibel sein.

### Jedes Aggregat automatisch herabstufen

Kleine Gruppen und Filter können weiterhin Personen offenlegen.

### Spaltennamen als Lineage behandeln

Ein gleicher Name beweist keine gemeinsame Herkunft.

### Detection darf freigegebene Metadaten überschreiben

Eine Heuristik wird stärker als eine Governance-Entscheidung.

### Propagierung in einem Catalog Sync verstecken

Änderungen werden schwer reviewbar, reproduzierbar und auditierbar.

### Row-Access-Dimensionen ignorieren

Column Masking kann funktionieren, während Nutzer weiterhin unzulässige Zeilen erhalten.

## Entscheidungshilfe

| Transformation | Standardentscheidung |
| --- | --- |
| Direkte Weitergabe | Freigegebene Metadaten kopieren |
| Umbenennung | Freigegebene Metadaten kopieren |
| Cast, Trim, Case Conversion | Klassifikation erhalten |
| Kombination von Spalten | Zusammenführen und prüfen, sofern keine Regel freigegeben ist |
| Hash oder Token | Sensitivität erhalten; abgeleitete PII-Kategorie prüfen |
| Aggregat | Vor Herabstufung prüfen |
| Konstante oder technisches Feld | Nicht erben |
| Nicht unterstützter oder mehrdeutiger Ausdruck | Als ungelöst markieren |
| Expliziter freigegebener Target Override | Override erhalten |
| Reines Detection-Ergebnis | Nur als Vorschlag speichern |

## Die zentrale Erkenntnis

> **PII-Metadaten sollen den Daten durch das Warehouse folgen. Jedes Propagierungsergebnis muss jedoch durch Lineage, eine kontrollierte Regel oder einen expliziten freigegebenen Override erklärbar sein.**

Das stärkste Muster lautet:

```text
Column Lineage
+ freigegebene Metadaten
+ Transformationsregeln
+ deterministische Priorität
→ reviewbarer Metadaten-Diff
→ Governance-Validierung
→ freigegebener Downstream-Vertrag
```

Part 5, [Snowflake Masking Policies + Qlik Section Access](/playbooks/snowflake-masking-policies-qlik-section-access), überführt diesen freigegebenen Downstream-Vertrag in ergänzende Warehouse- und Anwendungskontrollen.

## Quellen und weiterführende Dokumentation

- [dbt — manifest JSON](https://docs.getdbt.com/reference/artifacts/manifest-json)
- [dbt — Column-level lineage](https://docs.getdbt.com/docs/explore/column-level-lineage)
- [dbt — meta configuration](https://docs.getdbt.com/reference/resource-configs/meta)
- [dbt — columns property](https://docs.getdbt.com/reference/resource-properties/columns)
- [dbt — about dbt artifacts](https://docs.getdbt.com/reference/artifacts/dbt-artifacts)

> **Funktionsstand:** Juli 2026. Column-Lineage-Funktionen unterscheiden sich zwischen dbt-Produkten und externen Parsern. SQL-Parsing kann bei mehrdeutigen oder nicht unterstützten Transformationen unvollständig sein. Ungelöste Lineage muss deshalb reviewbar bleiben.
