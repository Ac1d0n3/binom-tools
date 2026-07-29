---
title: "Snowflake als Governance-Einstieg"
description: "Nutze Snowflake als Governance-Einstieg, wenn SQL-zentrierte Data Products, kontrolliertes Sharing und Policy Enforcement zentral sind und verantwortliche Rollen Identity, Klassifizierung, Lifecycle, Evidenz und Kostenkontrollen betreiben können."
author: Thomas Lindackers
tags:
  - data-governance
  - snowflake
  - sql-analytics
  - data-sharing
  - access-control
  - data-classification
  - operating-model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/snowflake-governance-start-hero.png
series: governance-platform-starting-points
seriesTitle: "Governance-Einstiegspunkte für Datenplattformen"
seriesPart: 4
---

![Snowflake als Governance-Einstieg](images/playbooks/snowflake-governance-start-hero.png)

## Problem

Snowflake kann eine starke Operating Surface für SQL Analytics, Warehouse-zentrierte Data Products und kontrolliertes Data Sharing bilden. Access-Control-Modell, Tags, Masking Policies, Row Access Policies, Secure Views, Sharing-Mechanismen, Account-Usage-Evidenz und Warehouse Controls können ein konsistentes Governance-Design technisch umsetzen.

Sie erzeugen dieses Design nicht automatisch.

Ein Snowflake Object besitzt einen Owner, aber Object Ownership ist ein administratives Privileg und kein Nachweis für verantwortliche Data Ownership. Ein Tag kann eine Klassifizierung dokumentieren, entscheidet aber nicht über erlaubten Zweck, Permitted Use, Retention oder Ausnahmeakzeptanz. Ein Secure View oder Share kann die Provider-seitige Bereitstellung kontrollieren, definiert jedoch nicht, was der Consumer nach dem Zugriff mit abgeleiteten Daten tun darf.

Die relevante Entscheidungsfrage lautet deshalb nicht:

> Besitzt Snowflake Governance- und Security-Funktionen?

Sondern:

> Kann Snowflake die Entscheidungen für das erste governede SQL Data Product durchsetzen und gleichzeitig verantwortliche Ownership, testbaren Zugriff, kontrolliertes Sharing, Lifecycle-Evidenz und Kostenverantwortung erhalten?

Snowflake ist ein plausibler Governance-Einstieg, wenn der erste governede Use Case von SQL Transformation, Warehouse Consumption oder governed Data Exchange geprägt ist und die Organisation folgende Grenzen explizit betreiben kann:

- Business Purpose und Data Ownership
- Account-, Cloud-, Region- und Environment-Design
- Role Hierarchy und Privilege Administration
- Human- und Service Identities
- Klassifizierungs- und Tag Governance
- Masking-, Row-Access- und Object-Access-Policies
- Secure Views, Shares und Consumer-Use-Grenzen
- Lineage, Access History und Change Evidence
- Retention, Recovery und Product Lifecycle
- Warehouse-, Budget- und Kostenverantwortung

Drei wiederkehrende Fehler führen dazu, dass eine technisch korrekte Snowflake-Implementierung schwach governed bleibt.

### Object Ownership wird mit Data Ownership verwechselt

Das Privileg `OWNERSHIP` ermöglicht einer Rolle, ein Snowflake Object zu kontrollieren und Zugriff entsprechend dem Access-Control-Modell zu übertragen oder zu vergeben. Dadurch ist die Rolle operativ mächtig. Sie ist damit nicht automatisch verantwortlich für Business Purpose, Definition, Criticality, Quality Expectation, Permitted Use oder Risk Acceptance der Daten.

Der verantwortliche Data Owner kann eine Policy freigeben, ohne Object Ownership zu besitzen. Umgekehrt kann eine Plattformrolle Object Ownership halten, ohne eine neue Business-Nutzung genehmigen zu dürfen. Das Operating Model muss diese Verantwortungen trennen, selbst wenn eine Person vorübergehend mehrere Rollen ausübt.

### Klassifizierung wird mit Enforcement verwechselt

Snowflake Tags können Metadaten dokumentieren und in tag-basierten Masking- oder Row-Access-Designs verwendet werden. Sensitive Data Classification kann beim Erkennen potenziell sensibler Spalten unterstützen. Diese Mechanismen reduzieren manuelle Arbeit, aber Klassifizierung benötigt weiterhin Freigabe, Policy Selection, Effective-Access-Tests und Review.

Eine als personenbezogen getaggte Spalte ist nicht allein durch den Tag geschützt. Schutz besteht erst, wenn die freigegebene Policy zugeordnet ist, die vorgesehenen Identity- oder Attributbedingungen korrekt auswertet, über die realen Query Paths das erwartete Ergebnis liefert und über Änderungen hinweg governed bleibt.

### Secure Sharing wird mit Downstream Control verwechselt

Secure Data Sharing kann ausgewählte Snowflake Objects bereitstellen, ohne die gespeicherten Provider-Daten in den Consumer Account zu kopieren. Imported Databases sind read-only, und Secure Views werden empfohlen, wenn der Provider Struktur oder Zeilen der Bereitstellung begrenzen muss.

Diese Provider-seitige Kontrolle beseitigt die Downstream-Verantwortung nicht. Ein Consumer kann den erlaubten Zugriff entsprechend seinen Privilegien und Tools für lokale abgeleitete Assets, Exporte oder analytische Ergebnisse nutzen. Der Provider benötigt deshalb eine dokumentierte Permitted-Use-Grenze, einen Consumer Owner, eine Retention Rule, einen Incident Contact, einen Review-Prozess und einen getesteten Revocation-Pfad.

![Governance-Entscheidungen und Snowflake-Enforcement](images/playbooks/snowflake-governance-start-img1-de.png)

Die Plattform-Controls müssen mit verantwortlichen Entscheidungen verbunden sein. Snowflake setzt den Control technisch um; das Operating Model liefert die Autorität.

## Entscheidung

Nutze Snowflake als Governance-Einstieg, wenn SQL-zentrierte Data Products, kontrolliertes Sharing und Policy Enforcement zentral sind und Business Ownership, Role Design, Klassifizierung, Lifecycle, Evidenz und Kostenverantwortung explizit zugeordnet werden.

Behandle das Ergebnis als begrenzte Einstiegspunkt-Entscheidung für einen ersten governeden Use Case. Nutze sie nicht als Beweis, dass Snowflake zur universellen Plattform für jedes Engineering-, BI-, Streaming-, AI- oder Operational-Workload werden muss.

### 1. Mit dem governeden SQL- oder Sharing-Use-Case beginnen

Wähle ein konkretes Produkt statt eines abstrakten Plattformprogramms. Die erste Validierung muss identifizieren:

- unterstützte Business-Entscheidung oder Prozess
- autoritative Quelle
- Product Grain und Refresh-Anforderung
- SQL-Transformation- und Warehouse-Bedarf
- interne und externe Konsumenten
- sensible Felder und erlaubte Zwecke
- erwartetes Sharing-Muster
- Quality Expectations und Incident-Pfad
- Retention- und Recovery-Anforderung
- erwartetes Compute-Profil und Cost Owner

Snowflake ist eher der richtige Einstieg, wenn der Use Case ein governed relationales Modell, wiederverwendbare SQL Transformationen, getrennten Compute, stabile analytische Consumption oder kontrollierte Cross-Account-Verteilung benötigt.

Der Current Stack kann die bessere Option bleiben, wenn nur eine kleine Reporting-Verbesserung erforderlich ist, Ownership und Prozesse die eigentlichen Lücken darstellen oder ein neuer Account und ein neues Operating Model mehr Komplexität als Governance-Nutzen erzeugen würden.

### 2. Account-, Region- und Environment-Grenzen zuerst entwerfen

Der Snowflake Account ist eine wesentliche Security-, Administrations-, Commercial- und Evidenzgrenze. Vor dem Anlegen von Product Schemas sind zu dokumentieren:

- Cloud Platform und unterstützte Region
- Residency- und rechtliche Anforderungen
- für die Controls erforderliche Snowflake Edition
- Organization- und Account-Modell
- Trennung von Development, Test und Production
- Netzwerk- und Private-Connectivity-Anforderungen
- Replication-, Failover- und Cross-Region-Sharing-Erwartungen
- Identity-Provider- und Provisioning-Modell
- Plattformadministration und Emergency Access
- Account-weites Monitoring, Budgets und Evidence Retention

Feature-Parität über Editions, Cloud Platforms und Regionen darf nicht vorausgesetzt werden. Die aktuelle Snowflake-Dokumentation ordnet Masking Policies, Row Access Policies, Sensitive Data Classification und die Account-Usage-View `ACCESS_HISTORY` der Enterprise Edition oder höher zu. Extended Time Travel bis zu 90 Tagen benötigt ebenfalls Enterprise Edition. Private Connectivity und bestimmte Funktionen für regulierte Workloads benötigen Business Critical oder höher. Edition, Region und Account-Typ gehören deshalb in die Readiness-Entscheidung und dürfen nicht als späteres Procurement-Detail behandelt werden.

Environment Separation kann getrennte Accounts, Databases, Schemas, Roles, Warehouses oder eine kontrollierte Kombination nutzen. Das richtige Design hängt von erforderlicher Isolation, Deployment-Pfad, Recovery-Modell und Evidenzgrenze ab. Eine Naming Convention ist keine Environment Isolation.

### 3. Business Accountability von Snowflake Administration trennen

Definiere die Rollen vor den Grants.

| Accountability | Erforderliche Entscheidung |
|---|---|
| Data Owner | Purpose, Permitted Use, Criticality, Quality Expectation, Sharing Approval und Risk Acceptance |
| Data Steward | Definition, Klassifizierung, Metadata Completeness, Review Workflow und Issue Coordination |
| Security oder Privacy Owner | Policy Standards, Identity Conditions, Sensitive-Data-Controls und Exception Requirements |
| Snowflake Object Administrator | Databases, Schemas, Objects, Ownership Transfers, Grants, Policies und technische Evidenz |
| Engineering Owner | Transformation Code, Deployment, Data Tests, Recovery und technische Incidents |
| Sharing Owner | Provider-Consumer-Vertrag, Consumer Registration, Usage Review, Change Notice und Revocation |
| Platform und FinOps Owner | Accounts, Warehouses, Resource Monitors, Budgets, Cost Attribution und Operational Support |

In einem kleinen Team kann eine Person mehrere Verantwortungen übernehmen, aber jede Entscheidung muss sichtbar bleiben. Eine Rolle, die `SELECT` vergeben kann, darf nicht stillschweigend zur Rolle werden, die den Business Purpose freigibt.

### 4. Die Role Hierarchy nach Duties und Products aufbauen

Snowflake unterstützt Role-Based Access Control, Role Hierarchies und Database Roles. Privileges werden auf Securable Objects vergeben und über die Role Hierarchy vererbt. Direkte User Grants sind über User-Based Access Control möglich, aber RBAC bleibt die empfohlene Production-Grundlage.

Eine praktikable Hierarchie unterscheidet üblicherweise:

- streng kontrollierte Account Administration
- Security und Grant Administration
- Platform Operations
- Database- und Schema-Administration
- Policy Administration
- Engineering- und Deployment-Rollen
- produktspezifische Read-, Write- und Operate-Rollen
- Consumer Roles
- Audit- und Evidence-Review-Rollen

Database Roles können Privileges innerhalb einer Database bündeln und an Account Roles vergeben werden. Sie sind für Product-scoped Access nützlich, aber Vererbung, Sharing-Verhalten und Einschränkungen müssen für das geplante Design getestet werden.

Für jedes governede Product ist Effective Access zu prüfen und nicht nur die sichtbaren Direct Grants:

```text
effective access
= User oder Service Identity
+ zugewiesene Account Roles
+ aktive Secondary Roles
+ Database-Role-Inheritance
+ Object Ownership und administrative Privileges
+ Future Grants
+ Auswertung von Masking und Row Access
+ Secure-View- oder Share-Grenze
+ Warehouse- und Execution-Pfad
```

Das Review muss zeigen, wer das Produkt entdecken, abfragen, verändern, freigeben, besitzen, klassifizieren, mit Policies versehen, teilen und administrieren kann. Es muss außerdem zeigen, wie Zugriff nach Rollenwechsel, Vertragsende oder Exception Expiry entfernt wird.

### 5. Kontrollierte Service Identities einsetzen

Production Ingestion, Transformation, Orchestration, BI und Data-Sharing-Prozesse dürfen nicht von persönlichen Identities abhängen.

Snowflake User Objects unterscheiden menschliche und serviceorientierte Nutzung. Aktuelle Authentifizierungsoptionen umfassen Federated Authentication für Personen und stärkere programmatische Muster wie Workload Identity Federation, Key-Pair Authentication und Programmatic Access Tokens für unterstützte Service-Szenarien. Snowflake schafft außerdem passwortbasierten Zugriff für Service Users ab; neue Designs sollten daher keine langlebigen Service-Passwörter als Standard erzeugen.

Für jede Service Identity sind zu dokumentieren:

- Workload und verantwortlicher Technical Owner
- User Type und Authentication Method
- zugewiesene Role und Least-Privilege-Grants
- erlaubte Netzwerk- oder Identity-Provider-Grenze
- Warehouse und Resource Limits
- Secret- oder Credential-Lifecycle, falls relevant
- Non-interactive Monitoring
- Rotation, Revocation und Break-glass Process
- Deployment- und Change Owner

Eine Service Identity ist nicht nur ein technisches Credential. Sie ist ein operativer Actor, dessen Zugriff, Kosten und Änderungen zugeordnet werden müssen.

### 6. Klassifizierung mit Policies und Zugriff verbinden

Die governede Policy Chain muss explizit sein:

```text
Business Classification
→ freigegebener Metadata Tag
→ Policy Selection
→ Role- oder Attribute Condition
→ Masking, Row Restriction oder Object Access
→ Effective-Access-Test
→ Audit Evidence
→ Recertification
```

![Klassifizierung mit Policies und Zugriff verbinden](images/playbooks/snowflake-governance-start-img2-de.png)

Nutze Tags für governede Metadaten wie Sensitivity, PII Category, Business Domain, Owner, Retention Class, Product Status oder Cost Center. Kontrolliere, wer diese Tags erstellen, verändern und zuweisen darf. Wenn Edition und Design es erlauben, können Tag-based Masking oder Tag-based Row Access die objektweise Policy Administration reduzieren.

Das Policy Design muss weiterhin definieren:

- Policy Owner
- freigegebene Business Rule
- Scope und unterstützte Data Types
- Role-, Attribute- oder Mapping-Table-Condition
- Verhalten für privilegierte und nicht privilegierte Identities
- Verhalten bei null, unknown und neuen Classification Values
- Policy Priority und Konflikte
- Deployment- und Rollback-Methode
- Test Identities und erwartete Ergebnisse
- Exception Route und Expiry
- Evidenz- und Recertification-Trigger

Eine direkt zugewiesene Masking Policy kann Vorrang vor einer Tag-based Policy haben. Eine Row Access Policy wird vor Masking Policies ausgewertet, wenn beide gelten. Diese Interaktionen müssen Teil der Tests sein; andernfalls kann das Metadatenmodell korrekt aussehen, während das Query Result falsch ist.

Temporäre Ausnahmen benötigen einen verantwortlichen Owner, expliziten Zweck, engeren Scope, Expiry, Evidenz und Review. Dauerhafte breite Bypass Roles dürfen nicht verwendet werden, um ungelöstes Policy Design zu umgehen.

### 7. Secure Views und Data Sharing als Verträge governen

Nutze ein Provider-to-Consumer-Modell und behandle einen Share nicht nur als technischen Endpoint.

![Secure Sharing ohne Verlust der Verantwortlichkeit](images/playbooks/snowflake-governance-start-img3-de.png)

Auf der Provider-Seite werden dokumentiert:

- autoritative governede Quelle
- freigegebenes Data Product und Grain
- verwendeter Secure View, Shared Object oder Listing
- bereitgestellte Felder, Zeilen und Historie
- Permitted Purpose
- Klassifizierungs- und Policy-Verhalten
- Freshness- und Quality-Erwartung
- Retention- und Revocation-Regel
- Sharing Owner und Support Contact

Auf der Consumer-Seite werden dokumentiert:

- benannter Account, Organization oder Business Domain
- freigegebener Purpose und Consumer Owner
- lokale Rollen und Access-Review-Owner
- Downstream-Copy- und Export-Grenze
- Retention- und Deletion-Verpflichtung
- Incident- und Breach-Contact
- Beschränkungen für Derived Products und Onward Sharing
- Review- und Termination-Datum

Cross-Boundary Evidence umfasst Vertrag oder Freigabe, Lineage, Access Evidence, Usage Review, Change Notice und Revocation Test.

Secure Data Sharing vermeidet Provider-seitige Datenkopien, ersetzt aber keine Permitted-Use-Governance. Imported Data ist in der Imported Database read-only; dies verhindert für sich allein nicht, dass ein Consumer lokale abgeleitete Objects oder Exporte erstellt. Die fachliche und technische Grenze muss deshalb in beiden Accounts getestet werden.

Für sensible Daten empfiehlt Snowflake Secure Views oder Secure UDFs statt direktem Sharing von Base Tables. Durch Masking oder Row Access Policies geschützte Daten können mit unterstützten Database-Role-Mustern geteilt werden. Direct-Share-Restrictions können außerdem gelten, wenn Provider- und Consumer-Accounts unterschiedliche Security- oder Compliance-Level besitzen. Diese Bedingungen sind vor der Freigabe des Shares zu validieren.

Cross-Region- oder Cross-Cloud-Verteilung erzeugt zusätzliche Fragen zu Replication, Auto-Fulfilment, Residency, Latency und Kosten. Ein Same-Region-Direct-Share-Design darf nicht als identisch für andere Regionen oder Cloud Platforms angenommen werden.

### 8. Evidenz über eine sichtbare Policy Definition hinaus aufbauen

Eine Policy Definition ist Configuration Evidence. Governance benötigt zusätzlich den Nachweis, dass der Control freigegeben, deployed, wirksam und reviewed wurde.

Das Evidence Package kombiniert:

- Ownership- und Steward-Records
- Object- und Role-Grants
- Tag Assignments und Classification State
- Policy Definitions und Policy References
- Effective-Access-Test-Results
- Query und Access History
- Login- und Authentication Evidence
- Object-, Schema- und Deployment-Change-Records
- Transformation und Source Lineage
- Data-Quality-Results und Incidents
- Share Configuration und Consumer Reviews
- Warehouse Usage und Cost Attribution
- Exceptions, Expiry und Recertification

Die View `SNOWFLAKE.ACCOUNT_USAGE.ACCESS_HISTORY` liefert detaillierte Access Evidence für unterstützte erfolgreiche Aktivitäten und hält Records 365 Tage mit dokumentierter Latenz vor. Sie darf nicht als einzige Audit-Quelle behandelt werden. Fehlgeschlagene Zugriffsversuche, Authentication Activity, Grant Changes, Deployment Evidence und externe Consumer Actions benötigen weitere Views, Logs oder Operating Records.

Nutze `POLICY_REFERENCES` und zugehörige Account-Usage- oder Organization-Usage-Views, um Policy Associations zu identifizieren. Nutze begrenzte Queries und explizite Retention-Entscheidungen, wenn Evidenz länger als native History Windows aufbewahrt werden muss.

Lineage muss den vollständigen Product Path abdecken:

```text
Quelle
→ Load oder Ingestion
→ Transformation
→ governede Table oder View
→ Shared oder Semantic Product
→ BI-, API- oder externer Consumer
```

Snowflake kann Object- und Access Evidence innerhalb seiner Grenze bereitstellen. Externe Ingestion, Orchestration, BI Measures, Exporte und Consumer-seitige Derivatives benötigen gegebenenfalls Metadata Integrations oder separate Records. Markiere diese Kanten als Evidenzlücken, bis sie kontrolliert oder explizit akzeptiert wurden.

### 9. Retention, Recovery und Lifecycle getrennt definieren

Data Retention ist nicht eine einzelne Einstellung.

Der Product Lifecycle unterscheidet:

- Business-Retention-Anforderung
- Active-Data-Retention
- Time-Travel-Periode
- Fail-safe-Verhalten
- Backup- oder Disaster-Recovery-Anforderung
- Verwendung temporärer und transienter Objects
- Beendigung geteilter Daten
- Retention von Audit Evidence
- Legal Hold oder Deletion Obligation
- Product Deprecation und Consumer Migration

Snowflake Standard Time Travel beträgt einen Tag. Enterprise Edition kann Time Travel für unterstützte Objects auf bis zu 90 Tage erweitern. Fail-safe ist ein Best-Effort-Recovery-Service und kein Ersatz für user-controlled Historical Access oder eine fachliche Backup Policy. Temporary und Transient Tables besitzen unterschiedliche Recovery-Eigenschaften und dürfen nicht nur zur Reduzierung von Storage Cost für autoritative langlebige Daten verwendet werden.

Für jedes governede Product sind Object Types, Retention Parameters, Recovery Test, Deletion Workflow und Owner zu dokumentieren. Ein Retention Tag ohne technische Lifecycle Action ist Metadata und kein Enforcement.

### 10. Warehouses und Kosten verantwortlich machen

Snowflake trennt Storage und Compute über Virtual Warehouses. Dadurch wird Compute Assignment zur Governance-Entscheidung.

Für jedes Warehouse sind zu definieren:

- Purpose und governede Workloads
- Environment
- Owner und Support Team
- erlaubte Roles und Service Identities
- Size, Scaling und Auto-suspend Policy
- Concurrency und Workload Isolation
- Resource Monitor oder Budget
- Cost Center und Product Attribution
- Alert Thresholds und Escalation
- Change- und Review-Prozess

Resource Monitors können Warehouse Credit Consumption verfolgen und bei definierten Schwellen benachrichtigen oder zugewiesene Warehouses suspendieren. Budgets können breitere unterstützte Credit Usage überwachen, und Custom Budget Actions können Reaktionen automatisieren. Diese Funktionen bestimmen nicht, wer zahlen soll oder welches Business Result die Kosten rechtfertigt. Cost Allocation Tags, Warehouse Ownership und Product-level Accountability müssen durch das Operating Model definiert werden.

Vermeide ein breites Warehouse für nicht zusammengehörende Products, wenn dadurch Cost Attribution, Workload Isolation oder Incident Ownership unmöglich werden. Vermeide ein Warehouse pro kleinem Object, wenn der Betriebsaufwand den Control-Nutzen übersteigt. Nutze die kleinste Grenze, die erklärbare Access-, Performance- und Cost-Evidenz erzeugt.

## Checkliste

Nutze diese Checkliste, bevor Snowflake als Governance-Einstieg freigegeben wird.

### Ausgangskontext

- [ ] Erster governed Use Case, Business-Entscheidung, Grain und Konsumenten sind benannt.
- [ ] SQL-, Warehouse- oder governed-Sharing-Bedarf ist für den Use Case wesentlich.
- [ ] Die Current-Stack-Alternative wurde bewertet.
- [ ] Cloud, Region, Edition und Account-Typ wurden validiert.
- [ ] Residency-, Netzwerk- und regulierte Workload-Anforderungen sind dokumentiert.

### Ownership und Operating Model

- [ ] Data Owner und Data Steward sind benannt.
- [ ] Object Ownership ist von Business Accountability getrennt.
- [ ] Verantwortungen für Security, Platform, Engineering, Sharing und FinOps sind zugeordnet.
- [ ] Exception Approval und Escalation sind definiert.
- [ ] Review- und Recertification-Daten sind gesetzt.

### Identity und Access

- [ ] Die Role Hierarchy ist dokumentiert und getestet.
- [ ] Database Roles, Future Grants und Ownership Powers sind in Effective-Access-Reviews enthalten.
- [ ] Production Workloads nutzen kontrollierte Service Identities.
- [ ] Authentication- und Network Controls passen zu Human- und Service-Use-Cases.
- [ ] Joiner-, Mover-, Leaver- und Revocation-Pfade sind getestet.

### Klassifizierung und Policy

- [ ] Classification Model und kontrolliertes Tag Vocabulary sind freigegeben.
- [ ] Tag Administration und Assignment Rights sind beschränkt.
- [ ] Masking, Row Access und Object Access sind mit verantwortlichen Entscheidungen verbunden.
- [ ] Policy Precedence, Bypass Conditions und unsupported Paths sind getestet.
- [ ] Exceptions besitzen Owner, Evidenz, Expiry und Review.

### Sharing und Downstream Use

- [ ] Provider- und Consumer Owner sind benannt.
- [ ] Regeln für Permitted Use, Copy, Export, Retention und Onward Sharing sind explizit.
- [ ] Secure Views oder vergleichbare governede Objects stellen nur freigegebene Daten bereit.
- [ ] Cross-Account-Policy-Verhalten ist getestet.
- [ ] Change Notice, Usage Review und Revocation Tests sind definiert.

### Evidenz, Lifecycle und Kosten

- [ ] Access-, Authentication-, Grant-, Policy-, Lineage-, Quality- und Change-Evidenz wird aufbewahrt.
- [ ] Native History Windows und Latenzen sind verstanden.
- [ ] Time Travel, Fail-safe, Backup und Deletion werden nicht vermischt.
- [ ] Warehouses besitzen Owner, Limits, Monitoring und Cost Attribution.
- [ ] Der Proof of Value enthält Access Denial, Policy Change, Incident und Revocation-Szenarien.

## Artefakt

Das finale Deliverable ist eine **Snowflake Policy and Control Map** plus Readiness-Entscheidung.

![Snowflake-Governance-Readiness-Entscheidung dokumentieren](images/playbooks/snowflake-governance-start-img4-de.png)

### Snowflake Policy and Control Map

Erstelle eine Zeile für jede governede Entscheidung und jeden Enforcement-Pfad.

| Feld | Erforderlicher Inhalt |
|---|---|
| Governed Use Case | Business-Entscheidung, Product, Grain, Sources und Consumers |
| Business Decision | Purpose, Permitted Use, Classification, Retention, Quality oder Sharing Approval |
| Accountable Role | Data Owner, Steward, Security, Sharing Owner, Platform oder FinOps Owner |
| Snowflake Scope | Organization, Account, Database, Schema, Table, View, Tag, Policy, Role, Share oder Warehouse |
| Identity Condition | User, Group, Account Role, Database Role, Service Identity, Consumer Account oder Attribute |
| Enforcement Control | Privilege, Ownership Rule, Masking Policy, Row Access Policy, Secure View, Share, Authentication Policy, Resource Monitor oder Budget |
| Policy Owner | Rolle, die Policy Definition und Change Approval verantwortet |
| Implementation Owner | Rolle, die den Control deployed und betreibt |
| Test | Positive-, Negative-, Bypass-, Revoked-User-, Changed-Classification- und Consumer-Test |
| Evidence Source | Grants, Policy References, Access History, Query History, Login History, Deployment Record, Quality Result oder Vertrag |
| Exception | Scope, Approver, Grund, Compensating Control und Expiry |
| Lifecycle | Effective Date, Review Date, Retention, Deprecation und Revocation Trigger |
| Cost Responsibility | Warehouse, Budget, Cost Center, Product Owner und Escalation |
| Gap | Fehlendes Feature, Edition, Regional Support, externe Evidenz oder Operating Capacity |

### Verpflichtende Readiness-Felder

Der Decision Record muss enthalten:

- erster governed Use Case
- Account-, Region- und Environment-Modell
- Data Owner und Steward
- Role Hierarchy und Service Identities
- Classification- und Tag-Modell
- Masking- und Row-Access-Policy-Design
- Sharing- und Permitted-Use-Grenze
- Lineage- und Audit-Evidenz
- Retention und Lifecycle
- Warehouse- und Kostenverantwortung
- Gaps und Proof-of-Value-Tests

### Readiness-Ergebnisse

Wähle ein explizites Ergebnis.

**Ready**

Der erste Use Case kann End-to-End governed werden, erforderliche Capabilities und Edition sind verfügbar, Ownership ist zugeordnet, Effective Access ist testbar, Sharing-Grenzen sind freigegeben und Evidenz kann aufbewahrt werden.

**Conditional**

Snowflake ist der bevorzugte Einstiegspunkt, aber benannte Bedingungen müssen vor Production erfüllt werden, zum Beispiel Enterprise-Edition-Entscheidung, Role Redesign, Service-Identity-Migration, Consumer Contract, Lineage Integration oder Cost-Control-Setup.

**Blocker**

Ein verpflichtender Control kann aktuell nicht umgesetzt oder betrieben werden. Beispiele sind ungelöste Residency, fehlende verantwortliche Ownership, nicht unterstützte regionale Capability, nicht freigegebene Sensitive-Data-Nutzung, unvollständige Consumer Accountability oder kein nachhaltiger Platform Support.

**Current-Stack-Alternative**

Die bestehende Umgebung kann den ersten Use Case mit weniger organisatorischer Reibung erfüllen, oder die eigentlichen Lücken liegen in Ownership, Metadaten und Prozessen statt im Platform Enforcement.

Setze für jedes Ergebnis ein Review-Datum. Eine Readiness-Entscheidung ohne Review Trigger wird zu veralteter Plattformdoktrin.

## Tools

### Governance Stack Advisor

Nutze den [Governance Stack Advisor](/tools/governance-stack-advisor), um Snowflake mit Fabric, Databricks, BigQuery und der Current-Stack-Option anhand derselben Governance-Evidenzkategorien zu vergleichen.

Erwarteter Output:

- erster governed Use Case
- Mandatory Controls
- Organizational Fit
- Plattform- und Edition-Fragen
- Operating-Model-Gaps
- Validation Plan

### Architecture Fit

Nutze [Architecture Fit](/tools/architecture-fit), um Account Topology, Cloud und Region, Data Movement, Integration, Network, Workload, Recovery und Cost Boundaries zu bewerten.

Erwarteter Output:

- Account- und Environment-Modell
- Source- und Consumer-Integration-Map
- Residency- und Network Constraints
- Warehouse- und Workload-Grenzen
- Migration- oder Coexistence-Impact
- technischer No-Regret Next Step

Die Tools strukturieren Evidenz. Sie genehmigen weder Purpose noch Ownership, Access oder Sharing.

## Ressourcen

Validiere vor der Implementierung die ausgewählte Cloud, Region, Edition und den aktuellen Release-Status. Snowflake Capabilities ändern sich kontinuierlich, und bestimmte Governance Controls sind editionsabhängig.

### Access Control und Identity

- [Overview of Access Control](https://docs.snowflake.com/en/user-guide/security-access-control-overview)
- [Access control best practices](https://docs.snowflake.com/en/user-guide/security-access-control-considerations)
- [Access control privileges](https://docs.snowflake.com/en/user-guide/security-access-control-privileges)
- [Database roles and role hierarchies](https://docs.snowflake.com/en/user-guide/security-access-control-overview#database-roles)
- [User management](https://docs.snowflake.com/en/user-guide/admin-user-management)
- [Overview of Snowflake authentication](https://docs.snowflake.com/en/user-guide/security-authentication-overview)
- [Authentication policies](https://docs.snowflake.com/en/user-guide/authentication-policies)
- [Workload identity federation](https://docs.snowflake.com/en/user-guide/workload-identity-federation)

### Klassifizierung und Policies

- [Introduction to object tagging](https://docs.snowflake.com/en/user-guide/object-tagging/introduction)
- [Sensitive data classification](https://docs.snowflake.com/en/user-guide/classify-intro)
- [Dynamic Data Masking](https://docs.snowflake.com/en/user-guide/security-column-ddm-intro)
- [Row access policies](https://docs.snowflake.com/en/user-guide/security-row-intro)
- [Tag-based masking policies](https://docs.snowflake.com/en/user-guide/tag-based-masking-policies)
- [Tag-based row access policies](https://docs.snowflake.com/en/user-guide/tag-based-row-access-policies)
- [`POLICY_REFERENCES`](https://docs.snowflake.com/en/sql-reference/functions/policy_references)

### Sharing, Evidenz und Lifecycle

- [About Secure Data Sharing](https://docs.snowflake.com/en/user-guide/data-sharing-intro)
- [Use secure objects to control data access](https://docs.snowflake.com/en/user-guide/data-sharing-secure-views)
- [Share data protected by a policy](https://docs.snowflake.com/en/user-guide/data-sharing-policy-protected-data)
- [Direct share restrictions](https://docs.snowflake.com/en/user-guide/direct-share-restrictions)
- [Share data across regions and cloud platforms](https://docs.snowflake.com/en/user-guide/secure-data-sharing-across-regions-platforms)
- [`ACCESS_HISTORY`](https://docs.snowflake.com/en/sql-reference/account-usage/access_history)
- [Snowflake Time Travel](https://docs.snowflake.com/en/user-guide/data-time-travel)
- [Fail-safe](https://docs.snowflake.com/en/user-guide/data-failsafe)
- [Backups](https://docs.snowflake.com/en/user-guide/backups)

### Editions, Regionen und Kosten

- [Snowflake editions](https://docs.snowflake.com/en/user-guide/intro-editions)
- [Supported cloud regions](https://docs.snowflake.com/en/user-guide/intro-regions)
- [Working with resource monitors](https://docs.snowflake.com/en/user-guide/resource-monitors)
- [Monitor credit usage with budgets](https://docs.snowflake.com/en/user-guide/budgets)
- [Object tags for resource usage](https://docs.snowflake.com/en/user-guide/object-tagging/introduction#using-tags-to-monitor-resource-usage)

## Playbooks

Nutze diese Story zusammen mit dem breiteren Governance-Entscheidungs- und Implementierungsmaterial:

- [Governance-Einstiegspunkte für Datenplattformen](/series/governance-platform-starting-points)
- [Fabric, Databricks, Snowflake oder BigQuery — Den Governance-Einstieg auswählen](/stories/choose-governance-platform-starting-point)
- [Fabric vs Databricks — Den Governance-Einstieg auswählen](/stories/fabric-vs-databricks-governance-start)
- [Nicht mit der Plattform beginnen](/stories/do-not-start-with-the-platform)
- [Den ersten governeden Vertical Slice bauen](/stories/build-first-governed-vertical-slice)
- [Den Data Product Contract definieren](/playbooks/data-product-contract)
- [Ownership vor Tooling definieren](/playbooks/data-ownership)
- [Operational Data Quality](/series/operational-data-quality)
- [Governance Architecture](/series/governance-architecture)

Der Plattform-Chooser bestimmt, ob Snowflake auf die Shortlist gehört. Diese Story bestimmt, ob die Organisation bereit ist, Snowflake als Enforcement-Einstieg für das erste governede SQL Data Product oder die erste governede Sharing-Beziehung zu nutzen.

## Nächster Schritt

Wähle ein Warehouse-zentriertes Product oder einen Provider-to-Consumer-Sharing-Pfad und vervollständige die Snowflake Policy and Control Map.

Führe anschließend einen Proof of Value über die vollständige Control Chain durch:

1. Data Owner, Steward und Technical Owner benennen
2. Cloud-, Region-, Edition-, Account- und Environment-Grenzen bestätigen
3. Role Hierarchy und kontrollierte Service Identity anlegen
4. sensible Felder klassifizieren und Tag Vocabulary freigeben
5. Klassifizierung mit Masking-, Row-Access- oder Object-Access-Policies verbinden
6. erlaubten und verweigerten Zugriff mit benannten Identities testen
7. ausschließlich den freigegebenen Secure View oder Share veröffentlichen
8. Consumer Purpose, Copy Boundary, Retention und Revocation dokumentieren
9. Policy References, Access Evidence, Lineage und Quality Results erfassen
10. Classification Change, Role Removal, Policy Rollback und Share Revocation testen
11. Warehouse Limits, Budget, Cost Center und Escalation zuordnen
12. Ready, Conditional, Blocker oder Current-Stack-Alternative dokumentieren
13. Review-Datum setzen

Der No-Regret Next Step ist nicht der Aufbau einer großen Role Hierarchy oder Enterprise-Tag-Taxonomy. Er besteht darin zu beweisen, dass eine verantwortliche Business-Entscheidung in Snowflake Controls übersetzt, über den realen Consumption Path getestet und als reviewbare Evidenz aufbewahrt werden kann.
