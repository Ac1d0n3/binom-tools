---
title: Metadatengetriebene Governance mit dbt meta
description: Wie Ownership, Klassifikation, Sensitivität, Aufbewahrung und Schutzentscheidungen mit dbt meta in einen versionierten und validierten Governance-Vertrag überführt werden.
category: Data Governance
tags:
  - data-governance
  - dbt
  - dbt-meta
  - metadata-driven
  - pii
  - klassifikation
  - manifest-json
  - ci
  - data-contract
  - analytics-engineering
order: -1
author: Thomas Lindackers
series: end-to-end-data-governance
seriesPart: 2
seriesTitle: End-to-End Data Governance
hero: images/playbooks/metadata-driven-governance-with-dbt-meta-hero.png
---

## Governance-Entscheidungen benötigen einen maschinenlesbaren Vertrag

Part 1, [End-to-End-Governance-Architektur](/playbooks/end-to-end-governance-architecture), hat Governance als verbundenes System definiert:

```text
Richtlinie
→ Metadaten
→ Code
→ Durchsetzung
→ Nachweis
```

Die Architektur benötigt nun einen technischen Vertrag, der Development, Review und Automatisierung durchlaufen kann.

dbt `meta` bietet dafür einen praktikablen Ort.

Die `meta`-Konfiguration akzeptiert eigene Key-Value-Paare auf dbt-Ressourcen. Die Werte werden in `manifest.json` aufgenommen und können in der generierten Dokumentation erscheinen.

Damit eignet sich `meta` unter anderem für:

- Ownership;
- Domäne;
- PII-Klassifikation;
- Sensitivität;
- Aufbewahrung;
- Schutz-Policy;
- Quality Tier;
- Review-Status;
- Generierungsstatus.

Ein frei verwendbares Dictionary ist jedoch noch keine Governance.

> **dbt `meta` wird erst dann zu einem Governance-Vertrag, wenn Vokabular, Geltungsbereich, Validierung, Freigabe und nachgelagerte Verwendung explizit definiert sind.**

## `meta` speichert Kontext — erzwingt aber nicht jede Regel

Ein Metadateneintrag wie:

```yaml
config:
  meta:
    sensitivity: confidential
    masking_policy: email_mask
```

bindet nicht automatisch eine Masking Policy im Warehouse an.

Er verhindert nicht automatisch ein unberechtigtes Deployment.

Er beweist nicht, dass ein Steward die Klassifikation genehmigt hat.

Er garantiert nicht, dass eine abgeleitete Downstream-Spalte dieselbe Bedeutung besitzt.

Der Wert des Vertrags entsteht durch die Systeme um ihn herum:

```text
dbt-YAML oder SQL-Config
→ Code Review
→ dbt parse
→ Manifest-Validierung
→ Generierungs- oder Propagierungslogik
→ Warehouse-Implementierung
→ Laufzeitprüfung
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-driven-governance-with-dbt-meta-img1-de.png"
        alt="dbt meta als Governance-Vertrag zwischen kontrolliertem Governance-Vokabular, Ressourcenmetadaten, Manifest-Artefakten, Validierung, Automatisierung und Laufzeitkontrollen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        dbt speichert den technischen Vertrag. Review, Validierung und nachgelagerte Automatisierung machen daraus operative Governance.
    </figcaption>
</figure>

## Das Vokabular vor den Keys definieren

Ein häufiger Fehler besteht darin, dass jeder Entwickler neue Keys einführt, sobald eine neue Anforderung entsteht.

Das Ergebnis ist gültiges YAML, aber semantisch inkonsistente Metadaten:

```yaml
pii: yes
personal_data: true
contains_pii: Y
data_class: personal
sensitive: email
```

Alle fünf Einträge können ein ähnliches Konzept beschreiben. Automatisierung kann sie nicht sicher als gleichwertig behandeln.

Ein kontrolliertes Vokabular sollte definieren:

- exakten Key;
- Zweck;
- erlaubte Werte;
- Geltungsbereich;
- Owner;
- Default;
- Freigaberegel;
- Propagierungsverhalten;
- Enforcement-Mapping.

Ein kompaktes Governance-Dictionary kann so beginnen:

| Key | Geltungsbereich | Beispiel | Zweck |
| --- | --- | --- | --- |
| `domain` | Source, Modell | `customer` | Fachliche Domäne |
| `data_owner` | Source, Modell | `sales_operations` | Fachliche Verantwortung |
| `steward` | Source, Modell, Spalte | `data_governance` | Metadaten-Stewardship |
| `technical_owner` | Modell | `data_platform` | Technische Verantwortung |
| `pii` | Spalte | `true` | Personenbezogene Daten |
| `pii_category` | Spalte | `email` | Kontrollierte PII-Kategorie |
| `sensitivity` | Modell, Spalte | `confidential` | Schutzklassifikation |
| `classification_status` | Modell, Spalte | `approved` | Review-Status |
| `masking_policy` | Spalte | `email_mask` | Freigegebenes Schutz-Mapping |
| `retention_class` | Source, Modell | `operational_7y` | Lifecycle-Regel |
| `quality_tier` | Modell | `critical` | Validierungspriorität |
| `generated` | Modell, Spalte | `true` | Generierungsherkunft |

Dieses Dictionary sollte wie Code versioniert werden.

## Explizite Review-Status verwenden

Ein Boolean wie `pii: true` beschreibt eine Klassifikation, aber nicht deren Freigabestatus.

Eine erkannte oder neu generierte Spalte darf nicht wie eine durch einen Steward genehmigte Klassifikation aussehen.

Verwende einen expliziten Workflow:

```text
unreviewed
→ proposed
→ approved
→ rejected
→ deprecated
```

Die genauen Status können abweichen. Die Unterscheidung muss jedoch sichtbar bleiben.

Beispiel:

```yaml
columns:
  - name: email
    config:
      meta:
        pii: true
        pii_category: email
        sensitivity: confidential
        classification_status: approved

  - name: contact_channel
    config:
      meta:
        pii: null
        classification_status: unreviewed
```

`unreviewed` sollte normalerweise verhindern, dass ein neues oder potenziell sensibles Feld in governte Downstream-Nutzung überführt wird.

## Metadaten am richtigen Scope ablegen

dbt unterstützt Metadaten auf mehreren Ressourcentypen und Ebenen.

Für diese Serie sind besonders wichtig:

- Source;
- Source-Tabelle;
- Modell;
- Spalte.

### Metadaten auf Source-Ebene

Source-Metadaten beschreiben das Quellsystem oder den Ingestion-Kontext.

```yaml
version: 2

sources:
  - name: crm_landing
    database: LANDING
    schema: CRM

    config:
      meta:
        source_system: crm
        ingestion_owner: data_platform
        data_owner: sales_operations
        retention_class: source_delivery_30d
```

### Metadaten auf Source-Tabellenebene

Metadaten auf Tabellenebene beschreiben ein einzelnes geliefertes Objekt.

```yaml
    tables:
      - name: customer
        config:
          meta:
            domain: customer
            source_of_record: true
            classification_status: approved
```

### Metadaten auf Modellebene

Modellmetadaten beschreiben das governte Datenprodukt oder den Transformationsknoten.

```yaml
models:
  - name: raw_crm_customer
    description: Source-aligned customer data.

    config:
      meta:
        domain: customer
        data_owner: sales_operations
        steward: data_governance
        technical_owner: data_platform
        quality_tier: critical
        generated: true
```

### Metadaten auf Spaltenebene

Spaltenmetadaten beschreiben Bedeutung und Behandlung eines einzelnen Feldes.

```yaml
    columns:
      - name: email
        description: Customer email address.

        config:
          meta:
            pii: true
            pii_category: email
            sensitivity: confidential
            classification_status: approved
            masking_policy: email_mask
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-driven-governance-with-dbt-meta-img2-de.png"
        alt="Metadatenhierarchie über Source, Source-Tabelle, Modell und Spalte mit beispielhaften Governance-Keys und dem Hinweis, dass Spaltenmetadaten nicht vom Modell erben"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Jede Entscheidung gehört auf die Ebene, für die sie gilt. Governance auf Spaltenebene bleibt explizit und darf nicht aus Modellmetadaten abgeleitet werden.
    </figcaption>
</figure>

## Spaltenmetadaten erben nicht vom Modell

Dieser Punkt ist für das Governance-Design entscheidend.

Spalten sind keine eigenständigen dbt-Ressourcen. Ihre `meta`-Werte erben nicht die `meta`-Werte der übergeordneten Ressource.

Ein Modell kann als vertraulich klassifiziert sein:

```yaml
config:
  meta:
    sensitivity: confidential
```

Das bedeutet nicht, dass jede Spalte automatisch Folgendes enthält:

```yaml
config:
  meta:
    sensitivity: confidential
```

Eigene Validierungs- oder Propagierungslogik kann einen organisatorischen Fallback definieren. Das ist jedoch eine eigene Regel und keine implizite dbt-Vererbung.

Ein sinnvolles Muster lautet:

```text
Modellmetadaten
→ Default-Kontext für das Review

Spaltenmetadaten
→ explizite Klassifikations- und Schutzentscheidung
```

## Ownership, Klassifikation, Schutz und Lifecycle trennen

Eine flache Liste von Keys wird schwer steuerbar, wenn voneinander unabhängige Konzepte vermischt werden.

Gruppiere die Metadaten logisch.

### Ownership

```yaml
data_owner: sales_operations
steward: data_governance
technical_owner: data_platform
domain: customer
```

### Klassifikation

```yaml
pii: true
pii_category: email
sensitivity: confidential
classification_status: approved
```

### Schutz

```yaml
masking_policy: email_mask
row_access_domain: legal_entity
allowed_usage:
  - customer_service
  - finance_reporting
```

### Lifecycle

```yaml
retention_class: operational_7y
deletion_rule: source_contract
source_of_record: true
```

### Betrieb

```yaml
quality_tier: critical
criticality: tier_1
review_date: 2027-01-31
generated: true
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-driven-governance-with-dbt-meta-img3-de.png"
        alt="Praktisches dbt-Governance-Metadatenschema mit Gruppen für Ownership, Klassifikation, Schutz, Lifecycle und Betrieb"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein kontrolliertes Schema macht Metadaten für Menschen verständlich und für Automatisierung vorhersehbar.
    </figcaption>
</figure>

## Ein vollständiges Modellbeispiel

Eine praktische Properties-Datei kann so aussehen:

```yaml
version: 2

models:
  - name: raw_crm_customer
    description: Source-aligned customer data from the CRM landing table.

    config:
      materialized: view
      tags:
        - raw
        - customer
      meta:
        domain: customer
        data_owner: sales_operations
        steward: data_governance
        technical_owner: data_platform
        retention_class: operational_7y
        quality_tier: critical
        classification_status: approved
        generated: true

    columns:
      - name: customer_id
        description: Source customer identifier.
        data_tests:
          - not_null
          - unique
        config:
          meta:
            pii: false
            sensitivity: internal
            classification_status: approved

      - name: email
        description: Customer email address.
        config:
          meta:
            pii: true
            pii_category: email
            sensitivity: confidential
            classification_status: approved
            masking_policy: email_mask

      - name: phone
        description: Customer phone number.
        config:
          meta:
            pii: true
            pii_category: phone
            sensitivity: confidential
            classification_status: proposed
            masking_policy: phone_mask

      - name: preferred_contact_time
        description: Newly delivered source field.
        config:
          meta:
            classification_status: unreviewed
```

Das Beispiel trennt bewusst:

- Modell-Ownership;
- Spaltenklassifikation;
- Schutz-Mapping;
- Review-Status;
- Quality Tests.

## Stabile Policy-Referenzen in `meta` ablegen — keine Runtime-Secrets

Gute `meta`-Werte sind:

- deterministisch;
- reviewbar;
- ausreichend stabil für Versionierung;
- außerhalb einer einzelnen Ausführung verständlich;
- sicher in Artefakten und Dokumentation.

Geeignete Beispiele:

```yaml
domain: customer
sensitivity: confidential
masking_policy: email_mask
retention_class: operational_7y
```

Nicht geeignet sind:

- Passwörter;
- Tokens;
- Secret Keys;
- persönliche Zugangsdaten;
- aktuelle Benutzerberechtigungen;
- große Access-Control-Listen;
- volatile Laufzeitwerte;
- unstrukturierte rechtliche Bewertungen.

Die Metadaten sollten eine Policy oder Entitlement-Domäne referenzieren und nicht das gesamte Laufzeit-Security-System einbetten.

## Das Manifest macht den Vertrag konsumierbar

dbt nimmt Ressourcenmetadaten in `manifest.json` auf.

Damit wird das Manifest zu einem nützlichen Integrationsartefakt für:

- CI-Validierung;
- Dokumentation;
- Kataloge;
- Impact-Analyse;
- Codegenerierung;
- Metadatenpropagierung;
- Warehouse-Policy-Mapping;
- Governance-Reporting.

Die Manifest-Version ist an das dbt-Artefaktschema gebunden. Konsumenten sollten das korrekte Schema für die eingesetzte dbt-Version verwenden und nicht davon ausgehen, dass die JSON-Struktur unverändert bleibt.

Ein vereinfachter Validierungsprozess lautet:

```text
dbt parse
→ target/manifest.json lesen
→ Modell- und Spaltenmetadaten prüfen
→ Organisationsregeln anwenden
→ Pull Request stoppen oder freigeben
```

## Den Vertrag vor dem Build validieren

YAML-Syntaxprüfung reicht nicht aus.

Die folgende Datei ist gültiges YAML:

```yaml
config:
  meta:
    pii: perhaps
    sensitivity: very_secret
    classification_status: done
```

Sie enthält jedoch keine gültigen Governance-Metadaten, wenn die freigegebenen Werte lauten:

```text
pii: true | false | null
sensitivity: public | internal | confidential | restricted
classification_status: unreviewed | proposed | approved | rejected | deprecated
```

Ein CI-Validator sollte mindestens prüfen:

- Pflicht-Ownership auf governten Modellen;
- erlaubte Enum-Werte;
- PII-Spalten besitzen eine Kategorie;
- freigegebene PII-Spalten besitzen ein freigegebenes Schutz-Mapping;
- ungeprüfte Spalten gelangen nicht in geschützte Downstream-Layer;
- kritische Modelle definieren einen Technical Owner;
- Retention Classes existieren im Policy-Katalog;
- generierte Metadaten überschreiben keine freigegebenen manuellen Entscheidungen.

Ein vereinfachter Python-Validator kann das Manifest untersuchen:

```python
for node in manifest["nodes"].values():
    if node.get("resource_type") != "model":
        continue

    model_meta = node.get("config", {}).get("meta", {})

    require(model_meta, "data_owner")
    require(model_meta, "technical_owner")
    validate_enum(model_meta, "quality_tier", QUALITY_TIERS)

    for column_name, column in node.get("columns", {}).items():
        column_meta = column.get("meta", {})

        validate_enum(
            column_meta,
            "classification_status",
            CLASSIFICATION_STATES,
        )

        if column_meta.get("pii") is True:
            require(column_meta, "pii_category")

            if column_meta.get("classification_status") == "approved":
                require(column_meta, "masking_policy")
```

Die konkrete Manifest-Struktur muss zum dbt-Artefaktschema des Projekts passen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-driven-governance-with-dbt-meta-img4-de.png"
        alt="Pull-Request-Validierung von Änderungen an dbt-YAML oder SQL über dbt parse, Manifest-Prüfung, Governance-Regeln, Review und kontrollierten Build"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Metadaten werden zu einem Vertrag, wenn ungültige oder unvollständige Entscheidungen den Delivery-Prozess vor dem Deployment stoppen.
    </figcaption>
</figure>

## Automatisierte Validierung ersetzt keine Freigabe

Automatisierung kann prüfen:

- ein Wert ist vorhanden;
- ein Wert gehört zu einer erlaubten Menge;
- zusammengehörige Felder sind konsistent;
- eine freigegebene PII-Spalte referenziert eine Masking Policy;
- eine neue Spalte bleibt ungeprüft;
- ein Modell besitzt einen Owner.

Automatisierung kann nicht zuverlässig entscheiden:

- ob ein Feld im konkreten Kontext rechtlich personenbezogen ist;
- ob ein fachlicher Zweck erlaubt ist;
- ob eine Aggregation ausreichend anonym ist;
- ob eine Retention-Ausnahme gerechtfertigt ist;
- ob eine Downstream-Nutzung akzeptabel ist.

Der Workflow benötigt deshalb beides:

```text
Automatisierte Validierung
+
menschliche Governance-Freigabe
```

Ein grünes CI-Ergebnis beweist die strukturelle Einhaltung des Vertrags. Es beweist nicht, dass jede Klassifikation fachlich korrekt ist.

## Das Business-Glossar verbinden — nicht duplizieren

dbt `meta` eignet sich für technische Governance-Attribute in der Nähe des Codes.

Es sollte nicht zum einzigen Enterprise-Glossar werden.

Eine sinnvolle Aufteilung ist:

| Information | Bevorzugtes führendes System |
| --- | --- |
| Technische Modell- und Spaltenmetadaten | dbt |
| Freigegebene Glossardefinition | Katalog- oder Glossarsystem |
| Richtlinientext | Policy Repository |
| Laufzeitrollen und Entitlements | Identity- und Access-Systeme |
| Warehouse-Policy-Implementierung | Warehouse |
| Anwendungsbezogene Reduktionszuordnung | Qlik-Security-Daten |
| Systemübergreifende Nachweise | Governance-Reporting oder Audit Store |

dbt kann Referenzen speichern:

```yaml
glossary_term: customer_email
policy_id: privacy_pii_001
retention_class: operational_7y
```

Dadurch bleibt der Code mit Governance verbunden, ohne vollständige Richtliniendokumente zu duplizieren.

## Für Generierung und Propagierung entwerfen

Der Vertrag sollte die nächsten beiden Parts der Serie unterstützen.

### Part 3: kontrollierte RAW-Generierung

Ein Generator kann Folgendes kombinieren:

```text
Physische Warehouse-Metadaten
+
freigegebene Governance-Metadaten
+
Generierungskonventionen
```

und daraus Source-YAML, RAW-SQL und Model Properties erzeugen.

Neue Spalten sollten mit folgendem Status generiert werden:

```yaml
classification_status: unreviewed
```

### Part 4: Metadatenpropagierung

Ein Propagierungsprozess kann Lineage und Transformationssemantik untersuchen und entscheiden, ob Metadaten:

- kopiert;
- zusammengeführt;
- herabgestuft;
- hochgestuft;
- entfernt;
- zur Prüfung vorgelegt werden.

Dieser Prozess benötigt stabile und explizite Metadaten-Keys. Freie Notizen reichen dafür nicht aus.

## Häufige Metadaten-Anti-Patterns

### Ein Boolean für jede Sensitivität

`pii: true` definiert weder Kategorie, Sensitivität, Status noch Schutz.

### Freie Werte

`high`, `very high`, `secret`, `sensitive` und `restricted` lassen sich nicht konsistent automatisieren.

### Kein Freigabestatus

Erkannte und genehmigte Klassifikationen sind nicht unterscheidbar.

### Metadaten nur auf Modellebene

Sensible Spalten bleiben technisch uneindeutig.

### Angenommene Vererbung

Spaltenverhalten hängt von nicht dokumentierter Fallback-Logik ab.

### Eingebettete Laufzeitberechtigungen

Versionierte Projektdateien werden zu einem zweiten Identity-Management-System.

### Generierung überschreibt freigegebene Metadaten

Ein Schema-Refresh zerstört stillschweigend Steward-Entscheidungen.

### Metadaten ohne Enforcement-Mapping

Die Plattform dokumentiert ein Risiko, reduziert es aber nicht.

## Die zentrale Erkenntnis

> **dbt `meta` ist der versionierte technische Vertrag zwischen Governance-Entscheidungen und Plattformautomatisierung.**

Der Vertrag muss:

- kontrolliert;
- explizit;
- validiert;
- reviewbar;
- maschinenlesbar;
- mit führenden Policies verbunden;
- von Runtime-Secrets getrennt;
- für Generierung und Propagierung vorbereitet sein.

Part 3, [Automatische RAW-Generierung mit dbt-Macros](/playbooks/automatic-raw-generation-using-dbt-macros), verwendet diesen Vertrag, um wiederkehrendes RAW-Grundgerüst zu erzeugen, ohne Git-Review, Lineage oder Governance-Kontrolle zu verlieren.

## Quellen und weiterführende Dokumentation

- [dbt — meta configuration](https://docs.getdbt.com/reference/resource-configs/meta)
- [dbt — columns property](https://docs.getdbt.com/reference/resource-properties/columns)
- [dbt — source configurations](https://docs.getdbt.com/reference/source-configs)
- [dbt — manifest JSON](https://docs.getdbt.com/reference/artifacts/manifest-json)
- [dbt — about dbt artifacts](https://docs.getdbt.com/reference/artifacts/dbt-artifacts)
- [dbt — docs commands](https://docs.getdbt.com/reference/commands/cmd-docs)
