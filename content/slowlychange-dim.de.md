---
title: Slowly Changing Dimensions — Historie mit SQL und Qlik IntervalMatch bewahren
description: Ein technischer End-to-End-Leitfaden zu SCD-Typen 0–7, Typ-2-Dimensionsverarbeitung, Point-in-Time-Joins in SQL, Qlik IntervalMatch, mehreren Quelltabellen, verspäteten Änderungen und Datenqualitätskontrollen.
category: Datenarchitektur
tags:
  - slowly-changing-dimensions
  - scd
  - scd-type-2
  - dimensional-modeling
  - data-warehouse
  - sql
  - qlik
  - intervalmatch
  - point-in-time
  - data-quality
  - data-governance
  - surrogate-keys
order: -1
author: Thomas Lindackers
hero: images/playbooks/slowlychange-dim-hero.png
---

## Slowly Changing bedeutet nicht unwichtig

Kunden wechseln die Region. Produkte werden neu klassifiziert. Mitarbeitende wechseln die Abteilung. Account Manager übernehmen andere Vertriebsgebiete. Lieferanten erhalten eine neue Risikobewertung. Organisationsstrukturen werden umgebaut.

Diese Attribute verändern sich normalerweise langsamer als Transaktionen. Deshalb bezeichnet die dimensionale Modellierung sie als **Slowly Changing Dimensions**. Das Wort *slowly* ist relativ: Ein Kunde kann Tausende Verkaufszeilen erzeugen, während sich sein Segment nur zweimal ändert. Werden diese beiden Änderungen falsch modelliert, können sie trotzdem sämtliche historischen Berichte verändern.

Die technische Frage lautet nicht nur, ob sich ein Attribut geändert hat.

Die entscheidende Frage lautet:

> **Welche Version der Dimension muss ein Fakt verwenden — die aktuelle Version, die zum Ereigniszeitpunkt gültige Version oder beide?**

Diese Entscheidung bestimmt, ob ein Bericht eine **As-was-**, eine **As-is-Frage** oder eine unbeabsichtigte Mischung aus beiden beantwortet.

> **Hinweis zum Beispiel:** Die Schaubilder verwenden fokussierte Ausschnitte, damit Tabellen und Beziehungen lesbar bleiben. Die technischen Tabellen und Codebeispiele dieses Playbooks erweitern dasselbe Muster auf das vollständige Sales-Beispiel aus mehreren Quellen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img1-de.png"
        alt="Vergleich zwischen einer aktuellen Kundendimension, die historische Verkäufe überschreibt, und einer historischen Typ-2-Dimension, die jeden Verkauf mit der zum Verkaufsdatum gültigen Version verknüpft"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein Join auf den aktuellen Zustand wendet heutige Kundeneigenschaften auf jedes Datum an. Eine Typ-2-Dimension bewahrt jede historische Version und verbindet Fakten mit dem zum Geschäftsereignis gültigen Zustand.
    </figcaption>
</figure>

## Eine realistische Sales-Landschaft beginnt mit mehreren Quelltabellen

Ein Warehouse erhält nur selten eine fertige Faktentabelle und eine bereits historisierte Dimension. Der benötigte Kontext ist über operative Systeme verteilt.

Das durchgehende Beispiel nutzt vier Quelldomänen.

### ERP-Auftragsköpfe

| order_id | order_date | customer_id | currency |
| --- | --- | --- | --- |
| SO-1001 | 12.01.2026 | C-100 | EUR |
| SO-1002 | 08.02.2026 | C-200 | EUR |
| SO-1003 | 18.03.2026 | C-100 | EUR |
| SO-1004 | 11.04.2026 | C-300 | EUR |
| SO-1005 | 09.05.2026 | C-100 | EUR |
| SO-1006 | 03.06.2026 | C-200 | EUR |
| SO-1007 | 21.06.2026 | C-300 | EUR |

### ERP-Auftragspositionen

| order_id | line_id | product_id | quantity | net_amount |
| --- | ---: | --- | ---: | ---: |
| SO-1001 | 10 | P-10 | 4 | 8.000 |
| SO-1001 | 20 | P-20 | 2 | 3.000 |
| SO-1002 | 10 | P-30 | 10 | 5.000 |
| SO-1003 | 10 | P-10 | 3 | 6.000 |
| SO-1003 | 20 | P-30 | 8 | 4.000 |
| SO-1004 | 10 | P-20 | 5 | 7.500 |
| SO-1005 | 10 | P-10 | 6 | 12.000 |
| SO-1005 | 20 | P-40 | 2 | 9.000 |
| SO-1006 | 10 | P-30 | 12 | 6.000 |
| SO-1006 | 20 | P-40 | 1 | 4.500 |
| SO-1007 | 10 | P-20 | 4 | 8.000 |
| SO-1007 | 20 | P-40 | 2 | 7.000 |

Die Granularität der späteren Faktentabelle lautet damit:

> **Eine Zeile je Auftragsposition.**

Das Auftragsdatum befindet sich im Kopf, Produkt, Menge und Betrag in der Position. Beide Tabellen werden benötigt, bevor historische Dimensionsversionen zugeordnet werden können.

### CRM-Kundenänderungen

| customer_id | customer_name | region | segment | effective_from | fachliche Bedeutung |
| --- | --- | --- | --- | --- | --- |
| C-100 | Alpha Systems | West | SMB | 01.01.2026 | Ausgangszustand |
| C-100 | Alpha Systems | North | Enterprise | 01.04.2026 | Regions- und Segmentwechsel |
| C-200 | Beta Retail | South | Mid-Market | 01.01.2026 | Ausgangszustand |
| C-200 | Beta Retail GmbH | South | Mid-Market | 15.05.2026 | Namenskorrektur |
| C-300 | Gamma Industries | North | Enterprise | 01.01.2026 | Ausgangszustand |
| C-300 | Gamma Industries | Central | Enterprise | 01.06.2026 | Regionswechsel |

Die Änderungen von C-100 und C-300 sind historisch relevant und werden als Typ 2 behandelt. Die Namensänderung von C-200 ist eine Typ-1-Korrektur, weil der vorherige Name als falsch und nicht als historisch bedeutend gilt.

### Produktänderungen

| product_id | product_name | category | effective_from |
| --- | --- | --- | --- |
| P-10 | Analytics Appliance | Hardware | 01.01.2026 |
| P-20 | Integration Setup | Services | 01.01.2026 |
| P-30 | Platform Licence | Software | 01.01.2026 |
| P-40 | Governance Package | Software | 01.01.2026 |
| P-40 | Governance Package | Professional Services | 20.05.2026 |

Ein Verkauf von P-40 am 9. Mai gehört zur Kategorie **Software**. Ein Verkauf am 3. Juni gehört zu **Professional Services**.

### Account-Manager-Zuordnungen

| customer_id | sales_rep_id | assigned_from | assigned_to |
| --- | --- | --- | --- |
| C-100 | S-01 | 01.01.2026 | 01.04.2026 |
| C-100 | S-04 | 01.04.2026 | 31.12.9999 |
| C-200 | S-02 | 01.01.2026 | 31.12.9999 |
| C-300 | S-03 | 01.01.2026 | 01.06.2026 |
| C-300 | S-02 | 01.06.2026 | 31.12.9999 |

Diese Quelle ist bereits intervallbasiert. Sie muss nicht zwingend Teil der Kundendimension sein. Es kann sich um eine eigenständige Beziehung handeln, deren Gültigkeit ebenfalls zum Verkaufsdatum aufgelöst werden muss.

Wichtig ist die Unterscheidung:

- `load_timestamp` beschreibt, wann das Warehouse einen Datensatz erhalten hat.
- `effective_from` beschreibt, wann der fachliche Zustand gültig wurde.

Beides ist nicht automatisch identisch. Wird die Ladezeit als fachlicher Gültigkeitsbeginn verwendet, können technisch konsistente, aber historisch falsche Ergebnisse entstehen.

## Was ein Join auf den aktuellen Zustand mit dem Ergebnis macht

Werden alle Verkäufe ausschließlich mit dem aktuellen Kundendatensatz verbunden, erscheinen sämtliche Umsätze von C-100 unter North / Enterprise und sämtliche Umsätze von C-300 unter Central / Enterprise.

Das historisch korrekte Ergebnis lautet:

| historische Region | Umsatz |
| --- | ---: |
| West | 21.000 |
| North | 28.500 |
| South | 15.500 |
| Central | 15.000 |
| **Gesamt** | **80.000** |

Ein Join auf den aktuellen Kundenstand erzeugt dagegen:

| aktuelle Region | Umsatz |
| --- | ---: |
| North | 42.000 |
| South | 15.500 |
| Central | 22.500 |
| West | 0 |
| **Gesamt** | **80.000** |

Die Gesamtsumme ist weiterhin korrekt. Die Klassifizierung ist falsch.

Genau deshalb sind SCD-Fehler gefährlich: Eine Abstimmung, die nur den Gesamtumsatz kontrolliert, kann erfolgreich sein, obwohl Region, Segment und Vertriebsverantwortung erheblich falsch ausgewiesen werden.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img2-de.png"
        alt="Sales-Beispiel mit falscher Aggregation über aktuelle Kundeneigenschaften und korrekter Aggregation über historische Typ-2-Kundenversionen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Wird Historie ignoriert, ändert sich nicht zwingend die Gesamtsumme. Es ändert sich, wo diese Summe erscheint — und damit ändern sich Performanceanalyse, Verantwortung und Entscheidungen.
    </figcaption>
</figure>

## SCD-Typen sind fachliche Regeln und keine Reifegrade

SCD-Typen bilden keine aufsteigende Skala, bei der eine höhere Nummer automatisch besser ist. Jeder Typ beantwortet eine andere fachliche Anforderung.

### Typ 0 — Originalwert bewahren

Der Wert wird niemals geändert.

Beispiel: Der ursprüngliche Akquisitionskanal eines Kunden bleibt `Partner`, auch wenn der Kunde später durch den Direktvertrieb betreut wird.

Typ 0 passt, wenn ausdrücklich der ursprüngliche Zustand benötigt wird.

### Typ 1 — überschreiben

Der alte Wert wird ersetzt. Eine Historie bleibt nicht erhalten.

Beispiel: `Beta Retail` wird zu `Beta Retail GmbH` korrigiert.

Typ 1 eignet sich für Korrekturen oder Attribute ohne historischen Analysewert. Innerhalb einer Typ-2-Dimension muss definiert werden, ob eine Typ-1-Korrektur nur die aktuelle Zeile oder alle historischen Zeilen desselben dauerhaften Business Keys aktualisiert. Bei echten Fehlerkorrekturen ist die Aktualisierung aller Versionen häufig konsistenter.

### Typ 2 — neue Zeile anlegen

Eine neue Dimensionszeile mit einem neuen Surrogat-Schlüssel wird erzeugt. Die vorherige Version bleibt mit einem geschlossenen Gültigkeitsintervall erhalten.

Beispiel:

| customer_sk | customer_id | region | segment | valid_from | valid_to | is_current |
| ---: | --- | --- | --- | --- | --- | --- |
| 1001 | C-100 | West | SMB | 01.01.2026 | 01.04.2026 | false |
| 1002 | C-100 | North | Enterprise | 01.04.2026 | 31.12.9999 | true |

Typ 2 ist das Standardmuster, wenn historische Zustände reproduzierbar bleiben müssen.

### Typ 3 — vorherigen Wert als Attribut ergänzen

Die Zeile speichert den aktuellen und einen oder mehrere ausdrücklich definierte vorherige Werte.

Beispiel:

| customer_id | current_region | previous_region |
| --- | --- | --- |
| C-100 | North | West |

Typ 3 eignet sich für einen begrenzten, vorhersehbaren Vergleich. Es ist keine vollständige Änderungshistorie.

### Typ 4 — Mini-Dimension

Schnell wechselnde Attribute werden in eine separate Dimension ausgelagert.

Beispiel: Risikoklasse, Engagement Score oder Verhaltensprofil ändern sich wesentlich häufiger als Kundenstammdaten. Jede Kombination in der Hauptdimension zu speichern, könnte zu starkem Wachstum führen.

### Typ 5 — Mini-Dimension plus aktuelle Typ-1-Referenz

Fakten behalten den historischen Mini-Dimensionsschlüssel. Gleichzeitig stellt die Hauptdimension das aktuelle Profil für schnelle Current-State-Filter bereit.

### Typ 6 — Typ-1-Attribute innerhalb einer Typ-2-Dimension

Eine Typ-2-Zeile bewahrt historische Attribute und enthält zusätzlich Current-State-Attribute. Dadurch werden As-was- und ausgewählte As-is-Analysen aus einer Struktur möglich. Die Aktualisierungslogik wird jedoch komplexer.

### Typ 7 — parallele Typ-1- und Typ-2-Perspektiven

Das Modell stellt zwei Wege bereit:

- einen Typ-2-Pfad über den Surrogat-Schlüssel für historische As-was-Berichte
- einen Pfad über den dauerhaften Schlüssel beziehungsweise den aktuellen Datensatz für As-is-Berichte

Typ 7 bietet hohe analytische Flexibilität, wenn Benutzer bewusst zwischen beiden Perspektiven wechseln müssen.

Dieses Playbook verwendet die Kimball-Namenskonvention. Manche Quellen bezeichnen eine separate Historientabelle als „Typ 4“. Deshalb sollte die verwendete Konvention im Datenmodell dokumentiert und nicht vorausgesetzt werden.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img3-de.png"
        alt="Praxisvergleich der Slowly-Changing-Dimension-Typen null bis sieben mit Beispielen zu Kunden, Regionen, Risiko und Reporting"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der richtige SCD-Typ folgt der fachlichen Bedeutung eines Attributs und der Reportingfrage. Er ist keine rein technische Entscheidung.
    </figcaption>
</figure>

## Aufbau einer governten Typ-2-Dimension

Eine robuste Typ-2-Dimension enthält normalerweise mehr als `valid_from` und `valid_to`.

| Spalte | Zweck |
| --- | --- |
| `customer_sk` | Surrogat-Schlüssel einer konkreten Version |
| `customer_id` | stabiler Business Key oder Durable Key |
| historisierte Attribute | Felder, deren Änderung eine neue Version erzeugt |
| Typ-1-Attribute | korrigierte oder aktuelle Felder |
| `valid_from` | inklusiver Beginn der Gültigkeit |
| `valid_to` | exklusives Ende der Gültigkeit |
| `is_current` | kennzeichnet die aktive Version |
| `hash_diff` | optionaler Hash der normalisierten historisierten Attribute |
| `load_ts` | Ladezeitpunkt im Warehouse |
| `source_system` | Herkunft des Datensatzes |
| `is_deleted` | optionaler expliziter Löschstatus der Quelle |

Die sicherste Zeitkonvention ist ein **halboffenes Intervall**:

```text
valid_from <= event_timestamp < valid_to
```

Endet eine Version am `01.04.2026 00:00:00`, kann die nächste Version exakt zu diesem Zeitpunkt beginnen, ohne dass eine Überlappung entsteht.

Endinklusive Intervalle mit Werten wie `23:59:59` sind fehleranfällig, sobald sich die Timestamp-Präzision von Sekunden auf Millisekunden oder Mikrosekunden ändert.

## Änderungserkennung verlangt Normalisierung vor dem Hash

Ein Hash ist nur dann sinnvoll, wenn gleiche fachliche Werte zu identischen Eingaben führen.

Vor der Berechnung von `hash_diff` sollten standardisiert werden:

- Datentypen
- führende und nachgestellte Leerzeichen
- Groß- und Kleinschreibung, sofern fachlich irrelevant
- Darstellung von Nullwerten
- Datums- und Timestamp-Präzision
- Dezimalskalen
- Quellcodes und Mappingwerte

Andernfalls können `North`, `NORTH` und `North ` drei Dimensionsversionen erzeugen, obwohl fachlich nur ein Wert vorliegt.

Zusätzlich sollte die Logik klar unterscheiden zwischen:

- Typ-1-Attributen
- Typ-2-Attributen
- ignorierten oder abgeleiteten Attributen

Ein volatiler `last_login_timestamp` darf nicht versehentlich jeden Tag eine neue Kundenversion erzeugen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img4-de.png"
        alt="Technischer Prozess zum Laden von Quelländerungen, Standardisieren, Erkennen von Hash-Unterschieden, Ablaufen der alten Typ-2-Zeile, Einfügen einer neuen Version und Validieren der Intervalle"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Typ-2-Verarbeitung ist ein kontrollierter Versionierungsprozess: standardisieren, vergleichen, klassifizieren, ablaufen lassen, einfügen und validieren.
    </figcaption>
</figure>

## SQL-Implementierung — die Kundenhistorie als Typ 2 erzeugen

Das folgende Muster ist strukturell datenbankneutral. Funktionen für Hashes, Verkettung, temporäre Tabellen und generierte Schlüssel unterscheiden sich je Plattform.

Ein inkrementeller Batch darf je Business Key höchstens eine fachlich wirksame Änderung enthalten. Alternativ müssen mehrere Änderungen in der Reihenfolge von `effective_from` verarbeitet werden. Ein vollständiges, unsortiertes Change Log lässt sich nicht korrekt in einem einzigen Durchlauf nur mit der aktuellen Dimensionszeile vergleichen.

### 1. Eingehende Datensätze standardisieren und klassifizieren

```sql
-- Beispiel im PostgreSQL-/Snowflake-Stil.
-- Hash- und Nullvergleichsfunktionen an die Plattform anpassen.

CREATE TEMPORARY TABLE customer_delta AS
WITH standardized AS (
    SELECT
        customer_id,
        TRIM(customer_name) AS customer_name,
        UPPER(TRIM(region)) AS region,
        UPPER(TRIM(segment)) AS segment,
        CAST(effective_from AS TIMESTAMP) AS effective_from,

        MD5(
            CONCAT_WS(
                '|',
                COALESCE(UPPER(TRIM(region)), '∅'),
                COALESCE(UPPER(TRIM(segment)), '∅')
            )
        ) AS type2_hash
    FROM stg_crm_customer
),
current_version AS (
    SELECT *
    FROM dim_customer
    WHERE is_current = TRUE
)
SELECT
    s.*,
    d.customer_sk AS current_customer_sk,
    d.hash_diff AS current_type2_hash,
    d.customer_name AS current_customer_name,
    d.valid_from AS current_valid_from,
    CASE
        WHEN d.customer_sk IS NULL THEN 'NEW'
        WHEN d.hash_diff <> s.type2_hash THEN 'TYPE2'
        WHEN d.customer_name <> s.customer_name THEN 'TYPE1'
        ELSE 'UNCHANGED'
    END AS change_action
FROM standardized s
LEFT JOIN current_version d
    ON d.customer_id = s.customer_id;
```

In produktiven Modellen sollten nullsichere Vergleiche verwendet werden. Ein normaler `<>`-Vergleich behandelt `NULL` nicht wie einen gewöhnlichen Wert.

### 2. Typ-1-Korrekturen anwenden

Bei einer echten Korrektur wie einer Berichtigung des Firmennamens sorgt die Aktualisierung aller historischen Versionen für eine konsistente Bezeichnung.

```sql
UPDATE dim_customer d
SET
    customer_name = s.customer_name,
    load_ts = CURRENT_TIMESTAMP
FROM customer_delta s
WHERE d.customer_id = s.customer_id
  AND s.change_action IN ('TYPE1', 'TYPE2')
  AND COALESCE(d.customer_name, '∅')
      <> COALESCE(s.customer_name, '∅');
```

Soll der alte Name historisch sichtbar bleiben, handelt es sich nicht um ein Typ-1-Attribut. Dann gehört der Name in den Typ-2-Hash und muss eine neue Version erzeugen.

### 3. Aktuelle Typ-2-Zeile ablaufen lassen

```sql
UPDATE dim_customer d
SET
    valid_to = s.effective_from,
    is_current = FALSE,
    load_ts = CURRENT_TIMESTAMP
FROM customer_delta s
WHERE s.change_action = 'TYPE2'
  AND d.customer_sk = s.current_customer_sk
  AND s.effective_from > d.valid_from;
```

Die letzte Bedingung schützt vor einem Intervall mit Länge null. Verspätete rückwirkende Änderungen löst sie nicht; dafür ist eine eigene Logik erforderlich.

### 4. Neue und geänderte Versionen einfügen

Der Surrogat-Schlüssel sollte aus einer Identity-Spalte oder Sequenz stammen. `MAX(customer_sk) + 1` ist nicht transaktionssicher.

```sql
INSERT INTO dim_customer (
    customer_id,
    customer_name,
    region,
    segment,
    valid_from,
    valid_to,
    is_current,
    hash_diff,
    load_ts,
    source_system
)
SELECT
    customer_id,
    customer_name,
    region,
    segment,
    effective_from,
    TIMESTAMP '9999-12-31 00:00:00',
    TRUE,
    type2_hash,
    CURRENT_TIMESTAMP,
    'CRM'
FROM customer_delta
WHERE change_action IN ('NEW', 'TYPE2');
```

Das Ablaufen und Einfügen sollte in einer Transaktion erfolgen. Konsumenten dürfen keine geschlossene aktuelle Zeile ohne Nachfolgeversion sehen.

## SQL-Implementierung — jede Verkaufsposition zum fachlichen Datum auflösen

Zunächst entsteht die Faktengranularität aus ERP-Kopf und -Position.

```sql
WITH sales_source AS (
    SELECT
        h.order_id,
        l.line_id,
        h.order_date,
        h.customer_id,
        l.product_id,
        l.quantity,
        l.net_amount
    FROM erp_sales_order_header h
    JOIN erp_sales_order_line l
      ON l.order_id = h.order_id
)
SELECT
    s.order_id,
    s.line_id,
    s.order_date,
    c.customer_sk,
    p.product_sk,
    a.sales_rep_id,
    s.quantity,
    s.net_amount
FROM sales_source s
JOIN dim_customer c
  ON c.customer_id = s.customer_id
 AND s.order_date >= c.valid_from
 AND s.order_date <  c.valid_to
JOIN dim_product p
  ON p.product_id = s.product_id
 AND s.order_date >= p.valid_from
 AND s.order_date <  p.valid_to
LEFT JOIN sales_customer_assignment a
  ON a.customer_id = s.customer_id
 AND s.order_date >= a.assigned_from
 AND s.order_date <  a.assigned_to;
```

Die resultierende Faktentabelle enthält stabile Referenzen auf die zum Verkaufsdatum gültigen Versionen.

| order_id | line_id | order_date | customer_sk | product_sk | sales_rep_id | net_amount |
| --- | ---: | --- | ---: | ---: | --- | ---: |
| SO-1001 | 10 | 12.01.2026 | 1001 | 501 | S-01 | 8.000 |
| SO-1001 | 20 | 12.01.2026 | 1001 | 502 | S-01 | 3.000 |
| SO-1002 | 10 | 08.02.2026 | 2001 | 503 | S-02 | 5.000 |
| SO-1003 | 10 | 18.03.2026 | 1001 | 501 | S-01 | 6.000 |
| SO-1003 | 20 | 18.03.2026 | 1001 | 503 | S-01 | 4.000 |
| SO-1004 | 10 | 11.04.2026 | 3001 | 502 | S-03 | 7.500 |
| SO-1005 | 10 | 09.05.2026 | 1002 | 501 | S-04 | 12.000 |
| SO-1005 | 20 | 09.05.2026 | 1002 | 504 | S-04 | 9.000 |
| SO-1006 | 10 | 03.06.2026 | 2001 | 503 | S-02 | 6.000 |
| SO-1006 | 20 | 03.06.2026 | 2001 | 505 | S-02 | 4.500 |
| SO-1007 | 10 | 21.06.2026 | 3002 | 502 | S-02 | 8.000 |
| SO-1007 | 20 | 21.06.2026 | 3002 | 505 | S-02 | 7.000 |

Surrogat-Schlüssel bereits beim Laden der Warehouse-Fakten aufzulösen, ist normalerweise besser als Range Joins in jedem Bericht zu wiederholen. Dadurch entsteht ein klassisches Star Schema und die historische Regel wird zentralisiert.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img5-de.png"
        alt="End-to-End-Architektur von CRM ERP und HR über Staging, Typ-2-Dimensionen, Fakten mit Surrogat-Schlüsseln und semantischer Schicht bis zum vertrauenswürdigen historischen Reporting"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Historische Auflösung gehört in einen gesteuerten Datenpfad: Quelländerungen bewahren, Dimensionsversionen erzeugen, Fakten mit den richtigen Surrogat-Schlüsseln versehen und bewusste As-was- oder As-is-Sichten bereitstellen.
    </figcaption>
</figure>

## Qlik-Implementierung — Historie mit Extended IntervalMatch auflösen

Qlik kann eine Slowly Changing Dimension im Ladeskript auflösen, wenn das Warehouse keine versionierten Surrogat-Schlüssel bereitstellt.

`IntervalMatch` verbindet diskrete numerische Werte mit Intervallen. Qlik-Datums- und Timestamp-Felder sind Dual Values mit numerischer Repräsentation. Deshalb müssen Datumsfelder konsistent normalisiert werden. Die **erweiterte Syntax** ergänzt einen oder mehrere Business Keys, beispielsweise `CustomerID`.

Die Kernbedingung lautet:

```text
CustomerValidFrom <= OrderDate <= CustomerValidTo
```

Qlik IntervalMatch behandelt die Intervallgrenzen inklusiv. Damit auf einem Versionswechsel kein doppelter Treffer entsteht, müssen Qlik-Intervalle entsprechend modelliert werden — bei Tageswerten beispielsweise mit dem Vortag als Intervallende oder bei Timestamps mit einer bewusst angepassten Obergrenze. Das unterscheidet sich von der zuvor verwendeten halboffenen SQL-Konvention und muss dokumentiert werden.

### Verkaufspositionen laden

```qlik
Sales:
LOAD
    AutoNumberHash128(OrderID, LineID)            AS %SaleLineKey,
    OrderID,
    LineID,
    Floor(OrderDate)                              AS OrderDate,
    CustomerID,
    ProductID,
    Quantity,
    NetAmount,

    AutoNumberHash128(CustomerID, Floor(OrderDate))
                                                    AS %CustomerDateKey,
    AutoNumberHash128(ProductID, Floor(OrderDate))
                                                    AS %ProductDateKey
FROM [lib://Data/Sales.qvd] (qvd);
```

### Kundenhistorie laden

Das Warehouse-Beispiel verwendet ein exklusives `valid_to`. Da Qlik IntervalMatch inklusive Grenzen nutzt, wird die Obergrenze in diesem Beispiel mit Tagesgranularität auf den Vortag umgerechnet.

```qlik
CustomerHistory:
LOAD
    CustomerSK,
    CustomerID,
    Floor(CustomerValidFrom)                      AS CustomerValidFrom,
    Floor(
        Alt(CustomerValidTo, MakeDate(2999, 12, 31))
    ) - 1                                         AS CustomerValidTo,
    Region,
    Segment,
    AccountManager
FROM [lib://Data/DimCustomerHistory.qvd] (qvd);
```

### Eindeutige Kunden-Datums-Kombinationen erzeugen

Dadurch wird die temporäre Bridge nicht größer als nötig, wenn mehrere Auftragspositionen denselben Kunden und dasselbe Datum besitzen.

```qlik
CustomerEventDates:
LOAD DISTINCT
    %CustomerDateKey,
    CustomerID,
    OrderDate                                     AS CustomerMatchDate
RESIDENT Sales;
```

### Extended IntervalMatch anwenden

```qlik
CustomerIntervalMatch:
INTERVALMATCH (CustomerMatchDate, CustomerID)
LOAD
    CustomerValidFrom,
    CustomerValidTo,
    CustomerID
RESIDENT CustomerHistory;
```

Die erzeugte Match-Tabelle enthält Ereignisdatum, Kundenschlüssel und die Grenzen des passenden Intervalls.

### Versionsattribute zum Match-Ergebnis ergänzen

```qlik
LEFT JOIN (CustomerIntervalMatch)
LOAD
    CustomerID,
    CustomerValidFrom,
    CustomerValidTo,
    CustomerSK,
    Region,
    Segment,
    AccountManager
RESIDENT CustomerHistory;
```

### Aufgelöste Version an die eindeutigen Ereignisschlüssel anhängen

```qlik
LEFT JOIN (CustomerEventDates)
LOAD
    CustomerMatchDate,
    CustomerID,
    CustomerSK,
    Region          AS SaleRegion,
    Segment         AS SaleSegment,
    AccountManager  AS SaleAccountManager
RESIDENT CustomerIntervalMatch;
```

### Ergebnis an jede Verkaufsposition anhängen

```qlik
LEFT JOIN (Sales)
LOAD
    %CustomerDateKey,
    CustomerSK,
    SaleRegion,
    SaleSegment,
    SaleAccountManager
RESIDENT CustomerEventDates;

DROP TABLE CustomerIntervalMatch;
DROP TABLE CustomerEventDates;
DROP TABLE CustomerHistory;
```

Das gleiche Muster kann für Produkte mit `%ProductDateKey`, `ProductValidFrom` und `ProductValidTo` verwendet werden.

Eine separate Historie der Vertriebszuordnung lässt sich ebenfalls nach Kunde und Auftragsdatum auflösen. Können mehrere Vertriebsmitarbeitende gleichzeitig gültig sein, handelt es sich jedoch nicht um einen einwertigen SCD-Lookup. Dann liegt eine Many-to-many-Zuordnung vor, die eine Bridge Table und gegebenenfalls Allokationsgewichte benötigt.

### Warum das Ergebnis materialisieren?

Mehrere IntervalMatch-Bridges im assoziativen Modell können verursachen:

- synthetische Schlüssel
- mehrdeutige Assoziationen
- Circular References
- unerwartete Zeilenvervielfachung
- größere App-Modelle
- schwierigere Validierung

Die aufgelösten Surrogat-Schlüssel oder Attribute bereits im Ladeskript in die Sales-Tabelle zu materialisieren, ist häufig leichter zu steuern. Ein bewusst entworfenes Link-Table-Modell bleibt sinnvoll, wenn mehrere Fakten dieselbe zeitabhängige Beziehung gemeinsam nutzen.

### Qlik-spezifische Prüfungen

Vor der Nutzung sollte geprüft werden:

- alle Datumsfelder sind numerisch und besitzen dieselbe Granularität
- offene Intervalle besitzen eine numerische Obergrenze
- für denselben Business Key überlappt kein Intervall
- jede Verkaufsposition erhält genau einen Kunden-Treffer
- jede Verkaufsposition erhält genau einen Produkt-Treffer
- temporäre Match-Tabellen vervielfachen nicht die Faktengranularität
- Zeitzonen werden normalisiert, bevor `Floor()` den Zeitanteil entfernt

Die offizielle Qlik-Dokumentation weist darauf hin, dass überlappende Intervalle mit allen passenden Intervallen verbunden werden. In einem Typ-2-Modell bedeutet das normalerweise, dass ein Fakt dupliziert wird, sofern die Überlappung nicht bewusst vorgesehen ist.

## SQL und Qlik lösen unterschiedliche Schichten desselben Problems

| Kriterium | Auflösung in SQL / Warehouse | Auflösung mit Qlik IntervalMatch |
| --- | --- | --- |
| bevorzugter Ort | zentrale Datenplattform | Analytics-Ladeschicht |
| Speicherung im Fakt | Fakt enthält Surrogat-Schlüssel | App löst Schlüssel oder Attribute beim Reload auf |
| Wiederverwendung | für alle Tools nutzbar | Qlik-App oder wiederverwendbare QVD-Schicht |
| Abfragen | einfache Star-Schema-Joins | Intervalllogik läuft beim Reload |
| Governance | zentral | muss über Qlik-Apps standardisiert werden |
| Performance | Range Join einmal beim Fact Load | abhängig von Reload-Volumen und Match-Kardinalität |
| guter Einsatzfall | governte Enterprise-Faktentabellen | Legacy-Fakten, app-spezifische Historie, fehlende Upstream-Auflösung |
| Hauptrisiko | fehlerhafte ETL-Intervalllogik | Überlappungen, synthetische Schlüssel und Zeilenvervielfachung |

Die stärkste Standardarchitektur lautet:

> **Typ-2-Dimensionen im Warehouse erzeugen und validieren, Surrogat-Schlüssel beim Fact Load auflösen und Qlik IntervalMatch dort einsetzen, wo die zeitliche Beziehung tatsächlich in die Qlik-Ladeschicht gehört oder keine Upstream-Auflösung verfügbar ist.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img6-de.png"
        alt="End-to-End-Beispiel einer Typ-2-Dimension von der Quelländerung über Änderungserkennung und Versionierung bis zur Faktverknüpfung und historisch korrekten Berichterstattung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine Quelländerung muss die gesamte Kette durchlaufen: erkennen, neue Version erzeugen, spätere Fakten mit dem neuen Surrogat-Schlüssel verbinden und frühere Fakten auf der alten Version bewahren.
    </figcaption>
</figure>

## Verspätete Änderungen erfordern eine Aufteilung des Intervalls

Das einfache Muster aus Ablaufen und Einfügen setzt voraus, dass Änderungen in der Reihenfolge ihrer fachlichen Gültigkeit eintreffen.

Beispiel:

- Version A gilt ab 1. Januar
- Version B gilt ab 1. Juni
- am 15. Juli erhält das Warehouse eine fehlende Änderung mit Gültigkeit ab 1. April

Das korrekte Ergebnis lautet:

| Version | valid_from | valid_to |
| --- | --- | --- |
| A | 01.01.2026 | 01.04.2026 |
| verspätete Version | 01.04.2026 | 01.06.2026 |
| B | 01.06.2026 | 31.12.9999 |

Nur die aktuelle Version zu schließen wäre falsch. Der Ladeprozess muss:

1. das Intervall finden, in dem das verspätete Gültigkeitsdatum liegt
2. dieses Intervall aufteilen
3. die fehlende Version einfügen
4. die spätere Version bewahren
5. Fakten im betroffenen Zeitraum neu zuordnen, sofern Surrogat-Schlüssel bereits gespeichert wurden

Das ist ein wichtiger Grund, Rohdaten und Change Events einschließlich Lademetadaten zu bewahren. Ohne Quellhistorie lässt sich möglicherweise nicht mehr rekonstruieren, was bekannt und was fachlich gültig war.

## Fakten treffen vor der Dimension ein

Ein Verkauf kann eintreffen, bevor der zugehörige Kundendatensatz verfügbar ist.

Übliche Strategien:

- einen **Unknown-Member-Surrogat-Schlüssel** verwenden und den Fakt später neu zuordnen
- den Fakt in einer Suspense-Tabelle halten, bis die Dimension existiert
- ein vorläufiges Dimensionselement nur mit dem Business Key anlegen und später anreichern

Die richtige Strategie hängt von Latenzanforderungen und Auditierbarkeit ab. Den Fakt still zu verwerfen oder später einfach mit dem aktuellen Datensatz zu verbinden, ist keine sichere Lösung.

## As-was und As-is sind beide gültig — wenn sie klar getrennt werden

### As-was

Der Fakt ist über den historischen Surrogat-Schlüssel verbunden.

Frage:

> Welche Region, welches Segment und welcher Account Manager waren zum Verkaufszeitpunkt verantwortlich?

### As-is

Historische Fakten werden nach der heutigen Kundenstruktur gruppiert.

Frage:

> Wie würde der gesamte historische Umsatz unter den aktuellen Vertriebsgebieten aussehen?

Ein Unternehmen kann beide Perspektiven benötigen. Das Modell sollte sie als getrennte, benannte Sichten bereitstellen. Typ-6- oder Typ-7-Muster können diese Anforderung unterstützen.

Zu vermeiden ist ein Bericht mit der Bezeichnung „historische Performance“, der unbemerkt aktuelle Attribute verwendet, weil diese einfacher zu verbinden waren.

## Wesentliche Validierungsabfragen

### Überlappungen und Lücken erkennen

```sql
WITH ordered AS (
    SELECT
        customer_id,
        customer_sk,
        valid_from,
        valid_to,
        LAG(valid_to) OVER (
            PARTITION BY customer_id
            ORDER BY valid_from
        ) AS previous_valid_to
    FROM dim_customer
)
SELECT
    customer_id,
    customer_sk,
    previous_valid_to,
    valid_from,
    CASE
        WHEN previous_valid_to > valid_from THEN 'OVERLAP'
        WHEN previous_valid_to < valid_from THEN 'GAP'
    END AS issue
FROM ordered
WHERE previous_valid_to <> valid_from;
```

Eine Lücke kann fachlich erlaubt sein, muss dann aber ausdrücklich dokumentiert werden.

### Ungültige aktuelle Zeilen erkennen

```sql
SELECT
    customer_id,
    SUM(CASE WHEN is_current THEN 1 ELSE 0 END) AS current_rows
FROM dim_customer
GROUP BY customer_id
HAVING SUM(CASE WHEN is_current THEN 1 ELSE 0 END) <> 1;
```

Gelöschte Entitäten können entsprechend einer dokumentierten Regel ohne aktive Zeile bleiben. Ansonsten sollte jeder dauerhafte Schlüssel normalerweise genau eine aktuelle Version besitzen.

### Fakten ohne oder mit mehreren Treffern erkennen

```sql
SELECT
    s.order_id,
    s.line_id,
    COUNT(c.customer_sk) AS customer_matches
FROM sales_source s
LEFT JOIN dim_customer c
  ON c.customer_id = s.customer_id
 AND s.order_date >= c.valid_from
 AND s.order_date <  c.valid_to
GROUP BY
    s.order_id,
    s.line_id
HAVING COUNT(c.customer_sk) <> 1;
```

Null Treffer bedeuten eine fehlende Dimension oder eine Intervalllücke. Mehr als ein Treffer weist auf überlappende Intervalle oder doppelte Dimensionszeilen hin.

## Governance gehört zum SCD-Design

Eine Typ-2-Pipeline benötigt eindeutige Verantwortung.

Business Owner oder Data Steward sollten definieren:

- welche Attribute Typ 0, 1, 2 oder ein anderes Muster verwenden
- welche Quelle das fachliche Gültigkeitsdatum bestimmt
- ob Korrekturen rückwirkend gelten
- ob rückdatierte Änderungen erlaubt sind
- wie Löschungen dargestellt werden
- ob As-is-Berichte benötigt werden
- welche historischen Ergebnisse neu berechnet werden dürfen
- wie lange Historie aufbewahrt wird

Data Architect oder Data Engineer sollten definieren:

- Business Keys und Surrogat-Schlüssel
- Intervallsemantik und Timestamp-Präzision
- Ladereihenfolge und Transaktionsgrenzen
- Normalisierung für Hashes
- Behandlung verspäteter Änderungen
- Unknown-Member-Strategie
- Performance und Indizierung
- automatisierte Validierung
- Lineage und operatives Monitoring

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img7-de.png"
        alt="Governance- und Engineering-Best-Practices für Typ-2-Dimensionen mit Business Keys, historisierten Attributen, Gültigkeitsintervallen, Datenqualität, Automatisierung, Zugriff und Audit"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Typ-2-Historie bleibt nur dann vertrauenswürdig, wenn Intervallregeln, Attributverantwortung, automatisierte Tests und kontrollierte Änderungen dauerhaft gesteuert werden.
    </figcaption>
</figure>

## Praktische Designregeln

1. Die Faktengranularität vor der Dimensionsauflösung definieren.
2. Business Keys und Surrogat-Schlüssel einer Version trennen.
3. Fachliche Gültigkeitszeit getrennt von der Ladezeit speichern.
4. Eine dokumentierte Intervallkonvention verwenden.
5. Werte vor der Änderungserkennung normalisieren.
6. Typ-1- und Typ-2-Attribute ausdrücklich kennzeichnen.
7. Surrogat-Schlüssel nach Möglichkeit zentral auflösen.
8. Prüfen, dass jeder Fakt genau den vorgesehenen Treffer erhält.
9. Überlappungen als Fehler behandeln, sofern die Beziehung nicht bewusst Many-to-many ist.
10. Verspätete Änderungen und Löschungen vor dem Produktivbetrieb entwerfen.
11. As-was und As-is als benannte semantische Perspektiven bereitstellen.
12. Historie niemals nur deshalb überschreiben, weil der aktuelle Datensatz leichter abzufragen ist.

## Die zentrale Erkenntnis

Slowly Changing Dimensions sind nicht in erster Linie eine ETL-Technik.

Sie sind ein Vertrag über Zeit.

Eine Typ-2-Dimension legt fest, dass ein Fakt den fachlichen Kontext bewahrt, der zum Ereigniszeitpunkt gültig war. Point-in-Time-Joins in SQL, Surrogat-Schlüssel und Qlik IntervalMatch sind technische Mechanismen zur Umsetzung dieses Vertrags.

Ohne diesen Vertrag kann ein technisch korrekter Join die Vergangenheit umschreiben.

Mit ihm lassen sich Berichte auch nach fachlichen Veränderungen reproduzieren, auditieren und vergleichen.

## Weiterführende Ressourcen

- [Kimball Group — Dimensional Modeling Techniques](https://www.kimballgroup.com/data-warehouse-business-intelligence-resources/kimball-techniques/dimensional-modeling-techniques/)
- [Kimball Group — Slowly Changing Dimension Types 0, 4, 5, 6 and 7](https://www.kimballgroup.com/2013/02/design-tip-152-slowly-changing-dimension-types-0-4-5-6-7/)
- [Kimball Group — SQL MERGE für Slowly Changing Dimensions](https://www.kimballgroup.com/2008/11/design-tip-107-using-the-sql-merge-statement-for-slowly-changing-dimension-processing/)
- [Qlik Help — Matching intervals to discrete data](https://help.qlik.com/en-US/sense/November2025/Subsystems/Hub/Content/Sense_Hub/LoadData/matching-intervals-to-discrete-data.htm)
- [Qlik Help — IntervalMatch prefix](https://help.qlik.com/en-US/qlikview/September2025/Subsystems/Client/Content/QV_QlikView/Scripting/ScriptPrefixes/IntervalMatch.htm)
- [dbt Developer Hub — Snapshots und Typ-2-Historie](https://docs.getdbt.com/docs/build/snapshots)

> **Hinweis zu dbt:** Snapshots können Typ-2-Historie aus veränderlichen Quelltabellen erzeugen. Der von dbt erkannte Änderungszeitpunkt ist jedoch nicht automatisch identisch mit einem von der Quelle gelieferten fachlichen Gültigkeitsdatum. Dieser Unterschied muss bei Point-in-Time-Berichten berücksichtigt werden.
