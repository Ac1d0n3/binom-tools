---
title: Snowflake Masking Policies + Qlik Section Access
description: Wie freigegebene Governance-Metadaten durch Snowflake Masking und Row Policies sowie Qlik-Anwendungszugriff und dynamische Datenreduktion in ergänzende Laufzeitkontrollen überführt werden.
category: Data Governance
tags:
  - data-governance
  - snowflake
  - qlik
  - section-access
  - masking-policy
  - row-access-policy
  - pii
  - data-security
  - access-control
  - dbt
products:
  - snowflake
  - dbt
  - qlik
order: -1
author: Thomas Lindackers
series: end-to-end-data-governance
seriesPart: 5
seriesTitle: End-to-End Data Governance
hero: images/playbooks/snowflake-masking-policies-qlik-section-access-hero.png
---

## Governance-Metadaten werden wertvoll, wenn sie das Laufzeitergebnis verändern

Parts 1–4 haben einen kontrollierten Pfad aufgebaut:

```text
Governance-Richtlinie
→ dbt-Metadatenvertrag
→ generierte RAW-Modelle
→ propagierte Downstream-Metadaten
```

Der letzte Schritt ist das Runtime Enforcement.

Eine freigegebene Spalte kann enthalten:

```yaml
pii: true
pii_category: email
sensitivity: confidential
classification_status: approved
masking_policy: email_mask
```

Ein governtes Modell kann enthalten:

```yaml
row_access_domain: legal_entity
row_access_status: approved
```

Diese Werte schützen die Daten noch nicht selbstständig.

Sie müssen in technische Kontrollen überführt werden.

Dieser Artikel verbindet zwei ergänzende Ebenen:

```text
Snowflake
→ zentrales Warehouse Enforcement

Qlik
→ Anwendungszugriff und anwendungsbezogene Datenreduktion
```

> **Snowflake schützt das governte Datenprodukt zur Abfragezeit. Qlik Section Access kontrolliert, was ein Nutzer in einer bestimmten Anwendung öffnen und sehen darf.**

Keine der beiden Ebenen ersetzt automatisch die andere.

## Eine Entscheidung kann mehrere Kontrollen erfordern

Betrachte einen Customer-Service-Datensatz.

Governance-Entscheidungen:

```text
E-Mail ist vertrauliche PII.
Agents dürfen nur Kunden ihrer Gesellschaft sehen.
Supervisors dürfen alle Kunden ihrer Region sehen.
Data Stewards dürfen E-Mail-Werte im Klartext prüfen.
```

Die Umsetzung kann benötigen:

- Snowflake Masking Policy für `email`;
- Snowflake Role- oder Entitlement-Mapping;
- Snowflake Row Access Policy für `legal_entity`;
- Qlik App Authorization;
- Qlik Section Access Reduction nach `LEGAL_ENTITY`;
- optionales Qlik Field Omission für anwendungsspezifische Felder;
- Persona Tests und Audit-Nachweise.

Die Kontrollen überschneiden sich im Zweck, greifen aber an unterschiedlichen Stellen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/snowflake-masking-policies-qlik-section-access-img1-de.png"
        alt="Freigegebene Governance-Metadaten verzweigen in zentrales Snowflake Masking und Row Access sowie Qlik-Anwendungszugriff und Section-Access-Datenreduktion, bevor die effektive Benutzersicht entsteht"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine Governance-Entscheidung kann Warehouse- und Anwendungskontrollen erfordern. Der zentrale Schutz bleibt auch außerhalb von Qlik wirksam.
    </figcaption>
</figure>

## Snowflake Dynamic Data Masking schützt Spaltenwerte

Snowflake Dynamic Data Masking ist eine Column-Level-Security-Funktion.

Eine Masking Policy wird zur Abfragezeit ausgewertet und kann abhängig von Ausführungskontext und Berechtigung unterschiedliche Darstellungen zurückgeben.

Mögliche Ergebnisse:

```text
Berechtigte Rolle
→ Klartext

Eingeschränkte Rolle
→ teilweise maskierter Wert

Nicht berechtigte Rolle
→ vollständig maskierter Wert oder null
```

Eine vereinfachte Masking Policy kann so aussehen:

```sql
create or replace masking policy governance.email_mask
as (value string) returns string ->
    case
        when is_role_in_session('PII_CLEAR_ROLE') then value
        when is_role_in_session('PII_PARTIAL_ROLE')
            then regexp_replace(value, '(^.).*(@.*$)', '\\1***\\2')
        else null
    end;
```

Der konkrete Policy Body muss zum Role- und Entitlement-Modell der Organisation passen.

Masking sollte nicht von anwendungsspezifischen Annahmen abhängen, wenn dasselbe Datenprodukt durch Qlik, Notebooks, APIs oder andere Werkzeuge verwendet wird.

## Direkte Zuweisung oder Tag-basiertes Masking

Snowflake unterstützt zwei grundlegende Mapping-Muster.

### Direkte Policy-Zuweisung

Die Policy wird an eine konkrete Spalte gebunden.

```sql
alter table analytics.customer
    modify column email
    set masking policy governance.email_mask;
```

Vorteile:

- explizit;
- für Ausnahmen leicht verständlich;
- direkte Beziehung zwischen Spalte und Policy.

Nachteile:

- bei großer Anzahl repetitiv;
- mehr Attachment Operations;
- schwerer über viele Schemas und Modelle zu verwalten.

### Tag-basiertes Masking

Ein Governance Tag wird einer Spalte zugewiesen. Eine Masking Policy wird mit dem Tag verbunden.

Konzeptionell:

```text
dbt-Metadaten
→ Snowflake Tag Value
→ Tag-basierte Masking Policy
→ geschützte Spalte
```

Damit entsteht ein skalierbares Mapping von:

```yaml
pii_category: email
masking_policy: email_mask
```

zu:

```text
Tag: PII_CATEGORY = EMAIL
Policy: EMAIL_MASK
```

Neue Spalten mit dem governten Tag können über die Tag-Policy-Beziehung geschützt werden.

Tag-basiertes Masking eignet sich besonders, wenn:

- viele Spalten dieselbe Policy teilen;
- Tags konsistent verwaltet werden;
- Datentypen kontrolliert sind;
- Policy Ownership vom Model Ownership getrennt ist;
- Policy-Referenzen zentral auditiert werden.

Direkte Zuweisung bleibt für explizite Ausnahmen sinnvoll.

## Policy Administration und Model Ownership trennen

Ein Data Engineer kann ein Modell besitzen, ohne entscheiden zu dürfen, wer PII im Klartext sieht.

Eine sinnvolle Funktionstrennung ist:

| Verantwortung | Typischer Owner |
| --- | --- |
| dbt-Modell und Metadatenreferenz | Data Engineering |
| Freigabe der PII-Klassifikation | Data Steward oder Privacy |
| Definition der Masking Policy | Security oder Privacy Engineering |
| Tag-Policy-Mapping | Governance Security Administration |
| Rollen- und Entitlement-Mapping | Identity and Access Management |
| Anwendungsreduktion | BI- oder Application Owner |
| Validierung des effektiven Zugriffs | Governance, Security und Application Owner |

Dadurch kann ein Model Owner nicht stillschweigend eine Schutz-Policy abschwächen.

## Metadaten-Mappings kontrolliert deployen

Das dbt-Modell kann das freigegebene Mapping enthalten:

```yaml
columns:
  - name: email
    config:
      meta:
        pii: true
        pii_category: email
        sensitivity: confidential
        classification_status: approved
        masking_policy: email_mask
```

Ein Deployment-Schritt kann:

1. den kompilierten Metadatenvertrag lesen;
2. `email_mask` zu einer freigegebenen Snowflake Policy auflösen;
3. Tag- oder Policy-DDL erzeugen;
4. Soll- und Ist-Zustand vergleichen;
5. die genehmigte Änderung anwenden;
6. Policy References abfragen;
7. Persona Tests ausführen;
8. Nachweise speichern.

Diese Logik kann mit kontrollierten Macros, Operations, Infrastructure Code oder einem Governance Deployment Service umgesetzt werden.

Entscheidend ist, dass das Policy Attachment:

- deterministisch;
- reviewt;
- idempotent;
- auditierbar;
- von gewöhnlicher Query-Logik getrennt bleibt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/snowflake-masking-policies-qlik-section-access-img2-de.png"
        alt="Ablauf von dbt-Governance-Metadaten über Policy Mapping und Snowflake Tag oder direkte Policy-Zuweisung zu maskierten Abfrageergebnissen für berechtigte, eingeschränkte und nicht berechtigte Rollen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Freigegebene Metadaten wählen ein kontrolliertes Policy Mapping. Snowflake wertet die Masking Policy aus, wenn die Spalte abgefragt wird.
    </figcaption>
</figure>

## Row Access ist von Masking getrennt

Masking steuert den zurückgegebenen Wert einer Spalte.

Row Access steuert, welche Datensätze zurückgegeben werden.

Ein Nutzer darf möglicherweise Folgendes sehen:

```text
alle Spalten
aber nur Zeilen für legal_entity = DE
```

Ein anderer Nutzer sieht:

```text
alle Gesellschaften
aber maskierte E-Mail-Werte
```

Das sind unterschiedliche Berechtigungsdimensionen.

Eine vereinfachte Snowflake Row Access Policy kann ein Entitlement Mapping verwenden:

```sql
create or replace row access policy governance.legal_entity_access
as (legal_entity string) returns boolean ->
    exists (
        select 1
        from governance.user_legal_entity_access a
        where a.role_name = current_role()
          and a.legal_entity = legal_entity
    );
```

Sie kann an eine Tabelle oder View gebunden werden:

```sql
alter table analytics.customer
    add row access policy governance.legal_entity_access
    on (legal_entity);
```

Besitzt ein Objekt sowohl eine Row Access Policy als auch Masking Policies, wertet Snowflake den Row Access vor dem Masking aus.

Dieses zentrale Enforcement schützt Warehouse-Zugriffe außerhalb von Qlik.

## Qlik Section Access kontrolliert eine Anwendung

Section Access ist Teil des Qlik Load Scripts.

Es definiert:

- wer die Anwendung öffnen darf;
- welche Zeilen dem authentifizierten Nutzer zur Verfügung stehen;
- optional, welche Felder ausgelassen werden.

Das Script besitzt zwei Bereiche:

```qlik
Section Access;

LOAD
    ACCESS,
    USERID,
    LEGAL_ENTITY,
    OMIT
FROM ...;

Section Application;

Customer:
LOAD
    CUSTOMER_ID,
    LEGAL_ENTITY,
    EMAIL,
    CUSTOMER_NAME
FROM ...;
```

Abhängig von der Qlik-Umgebung kann die Identität durch `USERID` oder `USER.EMAIL` dargestellt werden.

Das Reduktionsfeld muss in `Section Access` und `Section Application` mit exakt demselben Namen existieren. Qlik behandelt Feldnamen und Werte im Access-Bereich in Großbuchstaben. Das Reduktionsdesign muss sie deshalb konsistent normalisieren.

Beim Öffnen der App gleicht Qlik die Security Rows des Nutzers mit den Anwendungsdaten ab und blendet ausgeschlossene Daten aus.

## Section Access hat drei unterschiedliche Aufgaben

### 1. Application Authorization

Besitzt der Nutzer keine gültige Security Row, wird die App unter Strict Exclusion verweigert.

Das unterscheidet sich von der Plattformberechtigung, das App-Objekt in einem Space oder Stream zu sehen.

Sowohl Plattformberechtigung als auch Section Access können relevant sein.

### 2. Row Reduction

Ein Reduktionsfeld verbindet Security Rows mit Application Rows.

Beispiel:

```text
ACCESS | USERID             | LEGAL_ENTITY
USER   | DOMAIN\ANALYST_A   | DE
USER   | DOMAIN\ANALYST_B   | NL
ADMIN  | DOMAIN\STEWARD     | *
```

Ein normaler Nutzer sieht nur passende Daten.

Der Wildcard muss kontrolliert werden. In Reduktionsfeldern repräsentiert sie Werte, die in der Section-Access-Tabelle vorhanden sind, nicht beliebige zukünftige Werte, die ausschließlich in den Anwendungsdaten existieren.

### 3. Field Omission

Das optionale Feld `OMIT` kann benannte Anwendungsfelder für bestimmte Nutzer ausblenden.

Das kann anwendungsspezifische Anforderungen unterstützen, sollte Snowflake Masking für zentral sensible Spalten aber nicht ersetzen.

Die Qlik-Dokumentation empfiehlt, `OMIT` nicht auf Key Fields anzuwenden, da dies zu verwirrendem oder unvollständigem Anwendungsverhalten führen kann.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/snowflake-masking-policies-qlik-section-access-img3-de.png"
        alt="Qlik-Section-Access-Ablauf vom authentifizierten Nutzer und der Security Table über übereinstimmende Reduktionsfelder in Großbuchstaben zu einer governten Anwendung mit verweigertem, reduziertem oder erweitert freigegebenem Datenzugriff"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Section Access schützt eine Qlik-Anwendung durch Authorization und Datenreduktion. Es schützt keinen direkten Zugriff auf das zugrunde liegende Warehouse.
    </figcaption>
</figure>

## Entitlements nicht unabhängig in jeder App pflegen

Eine häufige Umsetzung bettet Nutzer direkt in das Script ein:

```qlik
Section Access;

LOAD * INLINE [
ACCESS, USERID, LEGAL_ENTITY
USER, DOMAIN\USER_A, DE
USER, DOMAIN\USER_B, NL
];
```

Für einen Prototyp kann das funktionieren.

Als Enterprise Access Model skaliert es nicht.

Ein stärkeres Muster nutzt eine governte Entitlement Source:

```text
Identität oder Rolle
→ Entitlement-Tabelle
→ Warehouse Access Policy
→ Qlik Section Access Extract
```

Beispiel:

| Principal | Access Domain | Wert | Application Scope | Gültig ab | Gültig bis |
| --- | --- | --- | --- | --- | --- |
| Regional Analyst DE | legal_entity | DE | customer_app | 2026-01-01 | null |
| Regional Analyst NL | legal_entity | NL | customer_app | 2026-01-01 | null |
| Data Steward | legal_entity | * | customer_app | 2026-01-01 | null |

Dieselbe führende Quelle kann speisen:

- Snowflake Row Access Logic;
- Qlik Section Access Tables;
- Access-Review-Berichte;
- Ablaufprüfungen;
- Audit-Nachweise.

Die generierte Qlik Security Table darf weiterhin anwendungsspezifisch sein. Die Entitlement-Entscheidung sollte jedoch nicht in jeder App manuell neu aufgebaut werden.

## Warehouse Roles und Qlik Users sauber ausrichten

Snowflake kann eine Service Role sehen, die von der Qlik-Verbindung genutzt wird. Qlik wendet innerhalb der App die Reduktion für den Endnutzer an.

Damit entsteht eine wichtige Trennung.

```text
Snowflake Query Identity
→ Qlik Connection oder Service Role

Qlik Application Identity
→ authentifizierter Endnutzer
```

Kann die Qlik Connection Role breite Daten laden, ist Section Access für die nutzerbezogene Reduktion des App-Ergebnisses verantwortlich.

Das Warehouse muss sensible Spalten gegenüber dieser Connection Role weiterhin entsprechend der Zielarchitektur schützen.

Mögliche Muster:

### Breite governte Service Role

Die Qlik Service Role kann alle für die App erforderlichen Zeilen laden. Snowflake Masking schützt Werte, die die App niemals erhalten soll.

Qlik Section Access reduziert anschließend die Zeilen je Endnutzer.

### Mehrere Application Roles oder Datenprodukte

Unterschiedliche Apps oder Domänen verwenden engere Snowflake Roles oder governte Views.

Dadurch wird die Datenmenge begrenzt, die jede App-Verbindung erhalten kann.

### Endnutzerbezogene Query Patterns

Soweit die gewählte Architektur dies unterstützt, erhält das Warehouse einen Endnutzer- oder Entitlement-Kontext.

Das kann zentrales Enforcement verstärken, erhöht aber Komplexität bei Identity Passing und Query Design.

Das richtige Design hängt von Qlik Deployment Mode, Connection Model, Latenz und Security-Anforderungen ab.

Unverzichtbar ist:

> **Dokumentiere, welche Identität Snowflake und welche Identität Qlik auswertet.**

## Änderungen an Section Access benötigen Reload und Validierung

Section Access wird in die Anwendung geladen.

Änderungen an Security Table oder Script benötigen einen App Reload, bevor sie wirksam werden.

Auch die Reload Identity muss berücksichtigt werden. In Qlik Sense on Windows können Scheduler- oder Service-Identitäten expliziten administrativen Zugriff oder ein freigegebenes Impersonation Setup benötigen.

Ein fehlerhaftes Service-Account-Design kann verursachen:

- fehlgeschlagene Reloads;
- unbeabsichtigt breiten Zugriff;
- Aussperrung aus der Anwendung;
- veraltete Entitlements.

Service Accounts gehören in die Testmatrix und dürfen kein nachträglicher Sonderfall sein.

## Effektiven Zugriff testen — nicht nur Konfiguration

Eine Policy kann existieren und dennoch das falsche Ergebnis erzeugen.

Die Validierung benötigt konkrete Personas.

Beispiel:

| Persona | Snowflake Role | Erwartete E-Mail | Erwartete Zeilen | Qlik App |
| --- | --- | --- | --- | --- |
| Data Steward | `PII_CLEAR_ROLE` | Klartext | freigegebener breiter Scope | breiter governter Zugriff |
| Regional Analyst DE | `ANALYST_ROLE` | maskiert | nur DE | nur DE |
| Customer Service User | `SERVICE_ROLE` | teilweise maskiert | nur zugewiesene Gesellschaft | nur zugewiesene Gesellschaft |
| Qlik Reload Service | `QLIK_LOAD_ROLE` | gemäß App Contract | App Load Scope | Reload erfolgreich |
| Nicht berechtigter Nutzer | keine | kein Zugriff | keine | App verweigert |

Tests sollten prüfen:

- Klartext, teilweise und vollständig maskierte Werte;
- Gesellschafts- oder Regions-Scope;
- verweigerten Zugriff;
- Verhalten bei fehlendem Entitlement;
- Wildcard-Verhalten;
- nicht passende Reduktionswerte;
- App Reload;
- direkte Snowflake-Abfrage außerhalb von Qlik;
- Verhalten kopierter oder neu publizierter Apps;
- Nachweise nach Policy-Änderungen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/snowflake-masking-policies-qlik-section-access-img4-de.png"
        alt="Personabasierte Validierungsmatrix mit Snowflake Role, maskiertem Spaltenergebnis, Warehouse-Zeilenscope und Qlik-Anwendungsergebnis sowie anschließender Nachweiserfassung und Freigabe"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Tests des effektiven Zugriffs validieren den gesamten Pfad. Reine Konfigurationsprüfungen beweisen nicht, dass jede Persona das vorgesehene Ergebnis erhält.
    </figcaption>
</figure>

## Konfigurierte und effektive Kontrollen auditieren

Snowflake stellt Metadatenansichten und Funktionen zur Prüfung der Policy-Konfiguration bereit.

Nützliche Nachweise:

- Definitionen der Masking Policies;
- Policy References;
- Tag References;
- Definitionen der Row Access Policies;
- Role Grants;
- Änderungen an Entitlement Tables;
- Ergebnisse von Query Tests.

Qlik-Nachweise können enthalten:

- Version der Section-Access-Quelle;
- App Reload Status;
- Ergebnisse effektiver User Tests;
- veröffentlichte App-Version;
- fehlgeschlagene Zugriffsversuche;
- Entitlement Extracts;
- Application Ownership.

Ein sinnvoller Nachweis verbindet:

```text
Governance-Entscheidung
→ dbt-Metadatenreferenz
→ Snowflake Policy Attachment
→ Qlik Security Mapping
→ Persona Test
→ Review-Freigabe
```

## Die Deployment-Reihenfolge ist relevant

Eine sichere Reihenfolge verhindert temporäre Offenlegung.

Beispiel:

```text
Freigegebene Policy erstellen oder aktualisieren
→ Policy oder governten Tag anbinden
→ Policy Reference prüfen
→ Warehouse Personas testen
→ Qlik-Anwendung reloaden
→ Qlik Personas testen
→ Deployment freigeben
```

Beim Ersetzen von Policies darf kein unkontrolliertes Zeitfenster entstehen, in dem das Objekt ungeschützt ist.

Änderungen an Tag, Masking Policy, Row Policy, Rolle oder Section Access Table sind Security Changes und keine gewöhnlichen kosmetischen Konfigurationen.

## Tag-basierter Row Access ist für diese Architektur nicht erforderlich

Snowflake hat im Juli 2026 Tag-basierte Unterstützung für weitere Policy-Typen einschließlich Row Access als Public Preview eingeführt.

Dieser Artikel setzt diese Preview-Funktion nicht voraus.

Die stabile Kernarchitektur kann verwenden:

- Tag-basiertes Masking für Spaltenschutz;
- direkt zugewiesene Snowflake Row Access Policies;
- Qlik Section Access für anwendungsbezogene Reduktion.

Preview-Funktionen können getrennt im Feature-Adoption-Prozess der Organisation bewertet werden.

## Häufige Enforcement-Anti-Patterns

### Nur den Masking-Policy-Namen in dbt speichern

Der Metadatenvertrag existiert, aber keine Laufzeit-Policy ist angebunden.

### PII ausschließlich in Qlik schützen

Direkter Snowflake-Zugriff kann die Anwendungskontrolle umgehen.

### Masking für Row Security verwenden

Maskierte Werte verhindern nicht, dass unzulässige Zeilen zurückgegeben werden.

### Section Access als Enterprise Identity System verwenden

Nutzer- und Entitlement-Logik wird über Apps dupliziert.

### Breite Wildcard ohne Tests für neue Werte verwenden

Neue Gesellschaften oder Bereiche können sich anders verhalten als erwartet.

### Key Fields auslassen

Die Anwendung kann unverständlich oder unvollständig werden.

### Nur mit Administratoren testen

Normale Nutzer, verweigerte Nutzer und Service Accounts bleiben ungeprüft.

### Annehmen, dass Qlik User und Snowflake User identisch sind

Das Warehouse kann eine gemeinsame Connection Role auswerten.

### Policy-Austausch ohne kontrollierte Reihenfolge deployen

Ein temporär ungeschützter Zustand kann entstehen.

## Entscheidungshilfe

| Anforderung | Primäre Kontrolle |
| --- | --- |
| Sensible Spaltenwerte zentral maskieren | Snowflake Masking Policy |
| Dieselbe Masking-Regel auf viele Spalten anwenden | Snowflake Tag-basiertes Masking |
| Warehouse-Zeilen beschränken | Snowflake Row Access Policy |
| Zugriff auf eine Qlik-App beschränken | Qlik Section Access plus Plattformberechtigung |
| App-Zeilen je Nutzer reduzieren | Qlik Section Access Reduction Field |
| Anwendungsspezifisches Feld ausblenden | Qlik `OMIT`, vorsichtig eingesetzt |
| Wiederverwendbare Entitlement-Entscheidungen pflegen | Governte Entitlement Source |
| Effektiven Zugriff nachweisen | Personabasierte Warehouse- und Qlik-Tests |
| Zugriff außerhalb von Qlik schützen | Snowflake-Kontrollen |
| Anwendungsspezifische Reduktion umsetzen | Qlik Section Access |

## Die zentrale Erkenntnis

> **Governance Enforcement ist am stärksten, wenn Snowflake das gemeinsame Datenprodukt zentral schützt und Qlik eine kontrollierte anwendungsspezifische Authorization und Reduktion ergänzt.**

Das vollständige Betriebsmodell lautet:

```text
Freigegebene Metadaten
→ kontrolliertes Policy Mapping
→ Snowflake Masking und Row Access
→ governter Qlik Load
→ Section Access Reduction
→ Persona Tests
→ Audit-Nachweise
```

Damit ist die Serie abgeschlossen:

1. Architektur;
2. Metadatenvertrag;
3. kontrollierte RAW-Generierung;
4. Metadatenpropagierung;
5. Runtime Enforcement.

## Quellen und weiterführende Dokumentation

- [Snowflake — Understanding Dynamic Data Masking](https://docs.snowflake.com/en/user-guide/security-column-ddm-intro)
- [Snowflake — Tag-based masking policies](https://docs.snowflake.com/en/user-guide/tag-based-masking-policies)
- [Snowflake — Understanding row access policies](https://docs.snowflake.com/en/user-guide/security-row-intro)
- [Snowflake — Attribute-based access control using tag-based policies](https://docs.snowflake.com/en/user-guide/tag-based-policies)
- [Snowflake — POLICY_REFERENCES](https://docs.snowflake.com/en/sql-reference/functions/policy_references)
- [Qlik Sense May 2026 — Managing data security with Section Access](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Scripting/Security/manage-security-with-section-access.htm)
- [Qlik Sense May 2026 — Section statement](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Scripting/ScriptRegularStatements/Section.htm)
- [Qlik Sense May 2026 — Star statement and Section Access behavior](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Scripting/ScriptRegularStatements/Star.htm)

> **Funktionsstand:** Juli 2026. Snowflake Dynamic Data Masking benötigt Enterprise Edition oder höher. Tag-basierte Unterstützung für Row Access und mehrere weitere Policy-Typen wurde am 21. Juli 2026 als Public Preview eingeführt. Die Kernempfehlung dieses Artikels hängt nicht von dieser Preview ab.
