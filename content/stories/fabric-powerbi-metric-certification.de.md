---
title: Metrik-Zertifizierung in Fabric und Power BI
description: Definiere, was certified und endorsed für eine Kennzahl bedeuten sollen, welche Evidenz und Entscheidungsrechte erforderlich sind und wie Trusted Metrics in Fabric und Power BI geprüft, veröffentlicht, rezertifiziert und stillgelegt werden.
author: Thomas Lindackers
tags:
  - metric-governance
  - metric-certification
  - trusted-metrics
  - kpi-governance
  - power-bi
  - microsoft-fabric
  - semantic-model
  - data-quality
  - data-lineage
products:
  - qlik
  - fabric
  - powerbi
publishedAt: 2026-07-28
category: Data Governance
order: -1
hero: images/playbooks/fabric-powerbi-metric-certification-hero.png
series: bi-governance-decisions
seriesTitle: BI-Governance-Entscheidungen
seriesPart: 2
---

Ein Zertifizierungs-Badge kann die Auffindbarkeit verbessern. Es beweist allein jedoch nicht, dass eine Kennzahl eine freigegebene Bedeutung, den richtigen Grain, einen verantwortlichen Owner, eine kontrollierte Implementierung oder aktuelle Evidenz besitzt.

Metrik-Zertifizierung ist deshalb eine **verantwortete Evidenzentscheidung**. Die Organisation entscheidet, ob eine Kennzahl für einen definierten Scope vertrauenswürdig ist. Fabric oder Power BI zeigt anschließend ein geeignetes Plattform-Endorsement auf dem governeden Item, das die Implementierung enthält.

![Eine governte Kennzahl durchläuft Evidenzprüfungen, bevor sie für vertrauenswürdige Wiederverwendung veröffentlicht wird](images/playbooks/fabric-powerbi-metric-certification-hero.png)

## Problem

Organisationen verwenden den Begriff `certified` häufig für unterschiedliche Dinge:

- ein Endorsement-Badge in Power BI oder Fabric;
- die Freigabe eines Semantic Models;
- die Freigabe einer einzelnen Business-Kennzahl;
- den Abschluss eines Governance-Workflows;
- eine berufliche Qualifikation wie CDMP;
- eine Datenschutz-, Security- oder Compliance-Zertifizierung.

Diese Bedeutungen sind nicht austauschbar.

Ein Plattform-Badge ist nützliches Metadatum. Es hilft Nutzern, Inhalte zu finden, die die Organisation als wertvoll oder autoritativ einstuft. Es definiert jedoch nicht, welche Evidenz geprüft wurde, welcher Kennzahlen-Scope freigegeben ist oder unter welchen Bedingungen der Vertrauensanspruch gültig bleibt.

### Das Plattform-Label ist nicht die Governance-Definition

Stand Juli 2026 unterstützt Microsoft Fabric die Endorsement-Labels **Promoted**, **Certified** und **Master data** für geeignete Fabric Items. Das Power-BI-Endorsement konzentriert sich auf **Promoted** und **Certified**. Certification wird über Tenant Settings und benannte Zertifizierer kontrolliert, während Promotion breiter durch Item Owner oder Nutzer mit Schreibberechtigung gesetzt werden kann.

Diese Funktionen wirken auf Item-Ebene. Eine Power-BI-Measure ist ein Objekt innerhalb eines Semantic Models und erhält kein eigenes eingebautes Endorsement-Badge. Ein zertifiziertes Semantic Model kann daher enthalten:

- freigegebene gemeinsame Measures;
- technische Hilfs-Measures;
- Measures, die nur in einem begrenzten Kontext gültig sind;
- deprecated Measures während einer Migration;
- ungegovernte lokale Berechnungen, die downstream entstehen.

Die Organisation muss die Zertifizierungsentscheidung auf Kennzahlenebene separat führen und mit der produktiven Implementierung verknüpfen.

### Fünf typische Anti-Patterns

**Badge ohne freigegebene Definition.** Das Semantic Model ist zertifiziert, weil es häufig verwendet wird. Der KPI besitzt aber weiterhin keine formal abgestimmte Population, Ausschlüsse, Zeitlogik und keinen eindeutigen Basis-Grain.

**Owner-Feld ohne Entscheidungsbefugnis.** Im Katalog steht ein Name, aber diese Person kann Bedeutung nicht freigeben, Risiko nicht akzeptieren, Remediation nicht priorisieren und die Kennzahl nicht stilllegen.

**Zertifizierung wird über Umgebungen kopiert.** Ein Development- oder Test-Artefakt gilt als vertrauenswürdig, weil ein verwandtes Production Item zertifiziert ist. Oder der Trust-Status wird bei einem Deployment als automatisch übertragen angenommen. Umgebungsspezifische Quellen, Regeln, Refreshes, Zugriffe und Evidenz werden nicht erneut geprüft.

**Keine Rezertifizierung nach Änderungen.** Quelle, Formel, Beziehung, Grain, Filterregel, Access Policy oder Owner ändern sich, während Badge und Trusted-Metric-Eintrag unverändert bleiben.

**Zertifiziertes Semantic Model mit ungegovernten lokalen Kennzahlen.** Nutzer erstellen Report-lokale, visuelle oder Composite-Model-Berechnungen, die das Vertrauenssignal übernehmen, aber die freigegebene fachliche Bedeutung neu definieren.

Das Problem ist nicht die Endorsement-Funktion. Das Problem entsteht, wenn das Badge die governte Entscheidung ersetzt.

### Zertifizierung muss eine präzise Frage beantworten

Eine belastbare Zertifizierungsaussage ist abgegrenzt:

> `Net Revenue` Version 3.2 ist für das monatliche Management Reporting in EUR auf Sales-Order-Line-Grain freigegeben. Sie verwendet die dokumentierten Regeln für Eligibility, Stornos, Retouren und Währung, ist im produktiven Sales Semantic Model implementiert, reconciled innerhalb der freigegebenen Toleranz gegen das Finance-Referenzergebnis und wird quartalsweise oder unmittelbar nach einem definierten Change Trigger geprüft.

Diese Aussage ist testbar. „Zertifizierter KPI“ ohne Scope, Version, Implementierung und Review-Bedingung ist es nicht.

## Entscheidung

Definiere Metrik-Zertifizierung als Lifecycle-Entscheidung mit expliziten Zuständen, Evidenz, Entscheidungsrechten und Review Triggern.

Die interne Governance-Entscheidung erfolgt **vor** dem Setzen des Plattform-Labels.

![Zertifizierung ist eine Evidenzentscheidung und kein Badge](images/playbooks/fabric-powerbi-metric-certification-img1-de.png)

Ein praktikabler Decision Flow ist:

```text
Kennzahlenkandidat
→ Prüfung von Definition und Grain
→ Owner-Freigabe
→ Lineage- und Quality-Evidenz
→ Reconciliation und Consumer-Validierung
→ Prüfung von Zugriff und erlaubter Nutzung
→ Zertifizierungsentscheidung
→ Veröffentlichte Trusted Metric
```

Die möglichen Ergebnisse sind nicht auf certified oder rejected begrenzt:

- **Certified** — die Kennzahl erfüllt den erforderlichen Evidenz- und Kontrollstandard für den freigegebenen Scope.
- **Promoted** — die Kennzahl oder das enthaltende Item ist nützlich und auffindbar, aber die vollständige Zertifizierungsevidenz ist noch nicht abgeschlossen.
- **Zur Remediation zurückgegeben** — der Kandidat ist grundsätzlich valide, aber Evidenzlücken müssen geschlossen werden.
- **Abgelehnt** — vorgeschlagene Bedeutung, Implementierung oder Kontrolldesign sind nicht akzeptabel.
- **Deprecated oder Retired** — die Kennzahl ist nicht mehr für neue Nutzung freigegeben und Consumer müssen migrieren.

### Trust States unabhängig von Plattformmechanik definieren

Verwende vier organisationsinterne Zustände.

| Zustand | Governance-Bedeutung | Erlaubter Vertrauensanspruch |
| --- | --- | --- |
| **Working** | Lokale Entwicklung oder Analyse. Definition, Implementierung oder Evidenz können sich noch ändern. | Kein Anspruch auf Enterprise Trust. |
| **Promoted** | Nützlich, benannt und auffindbar. Ein Owner ist identifiziert, aber Evidenz kann unvollständig oder der Scope begrenzt sein. | Für Evaluation oder kontrollierte Wiederverwendung empfohlen. |
| **Certified** | Definition, Grain, Implementierung, Evidenz, Zugriff und Lifecycle Controls sind für einen benannten Scope freigegeben. | Vertrauenswürdig für die freigegebenen Entscheidungen und Consumer. |
| **Deprecated oder Retired** | Neue Nutzung ist gestoppt. Ersatz, Migrationspfad und Stilllegungsstatus sind dokumentiert. | Nur historische Referenz oder keine erlaubte Nutzung. |

![Trust States und Entscheidungsrechte definieren](images/playbooks/fabric-powerbi-metric-certification-img2-de.png)

Ordne diese internen Zustände nur dann Microsoft-Plattformlabels zu, wenn die Zuordnung sachlich passt.

| Organisationszustand | Mögliche Umsetzung in Fabric oder Power BI | Wichtige Grenze |
| --- | --- | --- |
| **Working** | Kein Endorsement | Development Content darf keinen Production-Trust-Anspruch erben. |
| **Promoted** | Promoted Item | Promotion verbessert Sichtbarkeit, beweist aber keine vollständige Metrik-Evidenz. |
| **Certified** | Certified Production Semantic Model oder anderes geeignetes Item | Das Item-Badge zertifiziert nicht jede interne Measure und keine downstream erzeugte lokale Berechnung. |
| **Deprecated** | Endorsement entfernt oder geändert; Ersatz verlinkt | Consumer benötigen ein explizites Migrationssignal, weil das Plattform-Badge den Business Lifecycle nicht steuert. |
| **Retired** | Item gemäß Policy entfernt, archiviert oder im Zugriff eingeschränkt | Evidenz und Entscheidungshistorie bleiben für Audit und historische Interpretation erhalten. |

`Master data` ist ein separates Fabric Endorsement für geeignete datenhaltende Items. Es ist kein Synonym für eine zertifizierte Kennzahl.

### Metrik-Zertifizierung und Semantic-Model-Zertifizierung trennen

Semantic Model und Kennzahl sind verbundene Governance-Objekte, aber nicht identisch.

Eine **Metrik-Zertifizierung** gibt frei:

- Business-Frage und beabsichtigte Entscheidung;
- Definition, Einschlüsse und Ausschlüsse;
- Basis-Grain und Aggregationsverhalten;
- Zeit-, Filter- und Dimensionssemantik;
- verantwortlichen Owner und erlaubte Consumer;
- Referenzergebnis und Reconciliation-Toleranz;
- Version, Effective Date und Review Trigger.

Eine **Semantic-Model-Zertifizierung** gibt das wiederverwendbare Item frei, das eine oder mehrere Kennzahlen und Dimensionen implementiert:

- Modellstruktur und Beziehungen;
- freigegebene Measures und Berechnungsverhalten;
- Refresh- und Betriebscontrols;
- Security und Build Access;
- Lineage, Ownership und Support auf Modellebene;
- kontrollierten Release- und Change-Prozess;
- Eignung für governte Wiederverwendung.

Der Zusammenhang muss explizit sein:

```text
Certified Metric Record
→ Referenz auf freigegebene Implementierung
→ Production Semantic Model
→ technisches Measure-Objekt
→ erlaubte Reports und Consumer
```

Die Zertifizierung des Modells ist erforderlich, wenn das Modell der governte Delivery Channel ist. Sie reicht nicht aus, um jede enthaltene Kennzahl zu zertifizieren.

### Entscheidungsrechte zuweisen

Zertifizierung darf keine alleinige Selbstfreigabe des Implementierungsteams sein.

| Rolle | Entscheidungsverantwortung |
| --- | --- |
| **Data Owner** | Gibt fachliche Bedeutung, Risikoakzeptanz, erlaubte Nutzung und Stilllegung frei. |
| **Metric oder Data Product Owner** | Pflegt Scope, Version, Consumer-Auswirkungen, Review-Daten und Lifecycle-Status. |
| **Data Steward** | Validiert Terminologie, Evidenzvollständigkeit, Duplikate, Lineage-Referenzen und Policy Alignment. |
| **BI- oder Platform-Team** | Implementiert den freigegebenen Status, die technische Measure, Model-Metadaten, Access-Konfiguration und Deployment Controls. |
| **Quality- oder Control-Reviewer** | Prüft Tests, Schwellenwerte, Incidents und Reconciliation-Evidenz, wenn unabhängige Kontrolle erforderlich ist. |
| **Consumer Representatives** | Validieren Nutzbarkeit, erwartete Interpretation und Eignung für die Entscheidung. |
| **Autorisierter Plattform-Zertifizierer** | Setzt oder entfernt die Fabric- oder Power-BI-Zertifizierung nach der Governance-Entscheidung. |

In kleineren Organisationen kann eine Person mehrere Rollen übernehmen. Die Verantwortlichkeiten müssen trotzdem unterscheidbar bleiben. Wer die DAX Expression schreibt, darf die fachliche Bedeutung nicht still selbst festlegen.

### Umgebungsspezifische Freigabe verlangen

Development, Test und Production besitzen unterschiedliche Trust-Auswirkungen.

Ein Certification Record identifiziert das exakte Production Item und die Implementierungsversion. Ein Deployment in einen anderen Workspace oder eine andere Stage beweist nicht, dass:

- das Ziel dieselbe governte Quelle verwendet;
- Gateway, Credentials und Refresh funktionieren;
- Access und RLS gleichwertig sind;
- Deployment Rules auf die beabsichtigte Umgebung zeigen;
- Reconciliation-Ergebnisse innerhalb der Toleranz bleiben;
- das endorsed Item tatsächlich das freigegebene Production Object ist.

Behandle Production Certification als Release Gate. Kopiere den Trust-Anspruch nicht nur deshalb, weil Content kopiert wurde.

### Auffindbarkeit ermöglichen, ohne Discovery und Access zu verwechseln

Endorsement unterstützt Discovery. In Power BI kann ein endorsed Semantic Model discoverable gemacht werden, sodass Nutzer es auch ohne aktuellen Zugriff finden und Access anfordern können. Discoverability erteilt keine Build Permission und umgeht keine Security.

Der Trusted-Metric-Eintrag sollte daher sichtbar machen:

- Kennzahlenname, Zweck und Definition;
- Status und freigegebenen Scope;
- verantwortlichen Owner und Supportkontakt;
- Production Semantic Model und Measure;
- Freshness und letzten erfolgreichen Evidenzreview;
- erlaubte Consumer und Access-Request-Pfad;
- Ersatz bei Deprecation;
- Links zu Lineage, Tests und Reconciliation-Ergebnissen.

Discovery zeigt, dass ein freigegebenes Asset existiert. Access Controls bestimmen weiterhin, ob es genutzt werden darf.

### Zertifizierung ist ein Lifecycle

Ein permanentes Badge erzeugt veraltetes Vertrauen. Zertifizierung muss bei veränderter Evidenz oder verändertem Kontext in den Review zurückkehren.

![Zertifizierung ist ein Lifecycle](images/playbooks/fabric-powerbi-metric-certification-img4-de.png)

Verwende einen geschlossenen Loop:

```text
Vorschlagen
→ Evidenz prüfen
→ Lücken beheben
→ Freigeben und veröffentlichen
→ Nutzung und Qualität überwachen
→ Änderung erkennen
→ Neu bewerten
→ Rezertifizieren oder deprecated setzen
```

Unmittelbare Reassessment Trigger sind:

- Änderung an Quellsystem oder Quelltabelle;
- Änderung an Formel, Grain, Beziehung oder Aggregation;
- neuer Consumer-Kontext oder wesentlich andere Entscheidung;
- Quality Threshold Breach oder ungelöster Incident;
- Refresh- oder Freshness-Fehler;
- Änderung an Access-, PII- oder Permitted-Use-Policy;
- Wechsel von Owner oder Steward;
- Production-Migration oder wesentliche Plattformänderung;
- geplantes Review-Datum.

Ein kalenderbasierter Review bleibt erforderlich, weil nicht jede wesentliche Änderung automatisch erkannt wird. Eine risikobasierte Cadence kann kritische Executive- oder regulierte Kennzahlen quartalsweise, breit wiederverwendete operative Kennzahlen halbjährlich und stabile Kennzahlen mit geringerem Risiko jährlich prüfen. Change Trigger haben immer Vorrang vor dem Kalender.

## Checkliste

Nutze diese Checkliste, bevor eine Kennzahl als certified freigegeben wird.

![Erforderliche Evidenz für eine zertifizierte Kennzahl](images/playbooks/fabric-powerbi-metric-certification-img3-de.png)

### Definition

- [ ] Business-Frage und unterstützte Entscheidung sind explizit.
- [ ] Formel, Einschlüsse, Ausschlüsse und Exception Rules sind freigegeben.
- [ ] Die Kennzahl besitzt eine stabile ID und Version.
- [ ] Ähnliche Bezeichnungen und konkurrierende Definitionen wurden geprüft.

### Grain und Semantik

- [ ] Basis-Grain und gültige Aggregation sind dokumentiert.
- [ ] Zeitbasis, Effective Date und Periodenverhalten sind explizit.
- [ ] Filterverhalten und unterstützte Dimensionen sind definiert.
- [ ] Währung, Einheit, Vorzeichen und Null-Verhalten sind kontrolliert.
- [ ] Erlaubte lokale Ableitungen sind von Neudefinitionen getrennt.

### Ownership und Entscheidungsrechte

- [ ] Ein verantwortlicher Data Owner kann Bedeutung freigeben und Risiko akzeptieren.
- [ ] Ein Metric oder Product Owner pflegt den Lifecycle.
- [ ] Ein Steward validiert Terminologie und Evidenz.
- [ ] Ein Implementation Custodian unterstützt das technische Objekt.
- [ ] Autorisierte Plattform-Zertifizierer sind über Tenant Governance definiert.

### Lineage und Implementierung

- [ ] Governte Quellsysteme und Tabellen sind verlinkt.
- [ ] Transformationen und Orte der Fachregeln sind nachvollziehbar.
- [ ] Das exakte Production Semantic Model und die Measure sind identifiziert.
- [ ] Downstream Reports, Composite Models und wesentliche Consumer sind bekannt.
- [ ] Development- und Test-Objekte können nicht mit Production verwechselt werden.

### Qualität und Freshness

- [ ] Quality Rules, Thresholds und Severity Levels sind dokumentiert.
- [ ] Testergebnisse sind aktuell und reproduzierbar.
- [ ] Refresh- und Freshness-Erwartungen sind explizit.
- [ ] Incident Ownership, Escalation und Remediation sind definiert.
- [ ] Bekannte Einschränkungen und akzeptierte Ausnahmen besitzen Ablaufdaten.

### Reconciliation und Consumer-Validierung

- [ ] Ein Referenzergebnis oder autoritativer Vergleich ist benannt.
- [ ] Reconciliation-Grain, Zeitraum und Toleranz sind dokumentiert.
- [ ] Abweichungen sind erklärt und freigegeben.
- [ ] Consumer Representatives haben Interpretation und Nutzbarkeit validiert.
- [ ] Evidenz ist verlinkt und nicht nur in Screenshots konserviert.

### Protection und erlaubte Nutzung

- [ ] Access-Anforderungen und Build Permission wurden geprüft.
- [ ] RLS, OLS oder andere relevante Controls sind getestet, wenn sie eingesetzt werden.
- [ ] PII-, Sensitivity- und Permitted-Use-Klassifikationen sind dokumentiert.
- [ ] Discovery legt keine eingeschränkten Details offen.
- [ ] Export-, Sharing- und Downstream-Reuse-Bedingungen sind explizit.

### Lifecycle

- [ ] Effective Date und Review-Datum sind gesetzt.
- [ ] Change Trigger sind registriert.
- [ ] Owner und Evidenzanforderungen für Rezertifizierung sind klar.
- [ ] Deprecation-Status, Ersatz und Migrationsplan sind definiert.
- [ ] Der Trust Marker wird entfernt oder geändert, wenn die Freigabe abläuft.

### Plattform-Mapping

- [ ] Der interne Trust State ist vor dem Plattform-Endorsement entschieden.
- [ ] Der gewählte Item Type ist für das beabsichtigte Endorsement geeignet.
- [ ] Tenant Settings und Certifier Permissions sind geprüft.
- [ ] Das Endorsement wird auf das exakt freigegebene Production Item angewendet.
- [ ] Metrik-Evidenz bleibt unabhängig vom Item-Badge zugänglich.

## Artefakt

Das zentrale Ergebnis ist ein **Metric Certification Record**. Er ist das evidenzbasierte Entscheidungsartefakt hinter Katalogeintrag und Plattform-Endorsement.

Eine praktische Struktur kann so aussehen:

```yaml
metric_certification:
  metric_id: net-revenue
  metric_name: Net Revenue
  version: 3.2
  governance_state: certified
  effective_date: 2026-07-28

  business_scope:
    question: Wie viel zulässiger Umsatz verbleibt nach freigegebenen Stornos und Retouren?
    decisions:
      - monatliches-management-reporting
      - regionaler-sales-review
    approved_consumers:
      - executive-sales-report
      - regional-sales-analysis
    prohibited_uses:
      - statutory-revenue-reporting-ohne-finance-adjustments

  definition:
    approved_definition: /governance/metrics/net-revenue/3.2
    formula_components:
      - eligible-sales-lines
      - approved-cancellations
      - approved-returns
      - reporting-currency-conversion
    base_grain: sales-order-line
    aggregation: additive-by-approved-reporting-dimensions
    time_basis: posting-date
    exclusions:
      - test-orders
      - unapproved-manual-adjustments

  ownership:
    data_owner: sales-finance-owner
    metric_owner: sales-data-product-owner
    steward: commercial-data-steward
    implementation_custodian: bi-platform-team
    platform_certifier: authorized-certifier-group

  implementation:
    environment: production
    workspace: governed-sales-bi
    semantic_model: sales-performance
    semantic_model_item_id: linked-platform-id
    measure: Net Revenue
    deployment_version: release-2026.07.28
    endorsement:
      requested: certified
      applied_after_governance_decision: true

  evidence:
    lineage: /lineage/net-revenue/3.2
    quality_results: /quality/net-revenue/latest
    freshness_slo: data-available-by-07-00-cet
    reconciliation:
      reference: finance-monthly-close
      tolerance: 0.10-percent
      latest_result: passed
    consumer_validation: /reviews/net-revenue-consumers
    access_review: /access/sales-performance

  lifecycle:
    review_cadence: quarterly
    next_review: 2026-10-28
    change_triggers:
      - source-change
      - formula-or-grain-change
      - quality-threshold-breach
      - access-policy-change
      - ownership-change
    deprecation_replacement: null

  decision:
    outcome: certified
    approved_by: accountable-data-owner
    reviewed_by:
      - commercial-data-steward
      - quality-control-reviewer
      - consumer-representative
    decision_date: 2026-07-28
    open_exceptions: []
```

Der Record sollte auf dauerhafte Evidenzorte verweisen. Kopierte Screenshots veralten schnell, sind schwer abfragbar und verlieren häufig den Kontext, in dem sie erzeugt wurden.

### Mindestergebnisse

Der Zertifizierungsworkflow sollte erzeugen:

- Zertifizierungsentscheidung und freigegebenen Scope;
- verantwortlichen Owner und Implementation Custodian;
- Referenz auf die Production-Implementierung;
- Ergebnis der Evidenzvollständigkeit;
- Aktion für das Plattform-Endorsement;
- ungelöste Ausnahmen und Ablaufdaten;
- Review-Datum und Change Trigger;
- Deprecation- oder Migrationsmaßnahme, falls erforderlich.

Sinnvolle Betriebsmetriken sind:

- Certification Lead Time;
- Evidence Completeness;
- Wiederverwendung zertifizierter Kennzahlen;
- ungelöste Exceptions;
- überfällige Reviews;
- Quality Incidents mit Auswirkung auf zertifizierte Kennzahlen;
- Consumer-Migration von deprecated Metrics.

Diese Kennzahlen messen die Gesundheit des Trust-Prozesses. Die reine Anzahl der Badges tut das nicht.

## Tools

Nutze die bestehenden Binom Tools, um das Evidenzpaket zu erstellen und zu validieren:

- [KPI Definition Card](/tools/kpi-definition) — erfasst Business-Frage, freigegebene Definition, Grain, Formelkomponenten, Owner, Status und Version.
- [Report Inventory Canvas](/tools/report-inventory) — identifiziert konkurrierende Kennzahlenimplementierungen, lokale Overrides, betroffene Consumer und Migrationsscope.
- [BI Python Export Toolkit](/tools/bi-python-toolkit) — extrahiert größere Inventare von Power BI Semantic Models, Measures, Expressions und Report-Abhängigkeiten für den Review.
- [Power BI DAX Measure Generator](/tools/powerbi-dax-generator) — erzeugt Implementierungscode und Dokumentation erst nach Freigabe von Definition und Zertifizierungsscope.

Die Tools erzeugen Evidenz oder Implementierungsartefakte. Sie geben keine fachliche Bedeutung frei, akzeptieren kein Risiko und setzen keine Zertifizierung autonom.

## Ressourcen

- [Semantic Layer vs Measure im Report](/stories/semantic-layer-vs-report-measure) — entscheide vor der Zertifizierung, wo die Kennzahl implementiert werden soll.
- [Trusted Metrics Learning Path](/paths/trusted-metrics) — verbindet Definition, Ownership, Implementierung, Evidenz und Lifecycle.
- [Microsoft Learn — Endorsement von Power-BI-Inhalten](https://learn.microsoft.com/de-de/power-bi/collaborate-share/service-endorsement-overview)
- [Microsoft Learn — Fabric- und Power-BI-Items endorsen](https://learn.microsoft.com/de-de/fabric/fundamentals/endorsement-promote-certify)
- [Microsoft Learn — Item Certification aktivieren](https://learn.microsoft.com/de-de/fabric/admin/endorsement-certification-enable)
- [Microsoft Learn — Auffindbarkeit von Semantic Models](https://learn.microsoft.com/de-de/power-bi/collaborate-share/service-discovery)
- [Microsoft Learn — Build Permission für Semantic Models](https://learn.microsoft.com/de-de/power-bi/connect-data/service-datasets-build-permissions)
- [Microsoft Learn — Metadata Scanning](https://learn.microsoft.com/de-de/fabric/governance/metadata-scanning-overview)
- [Microsoft Learn — OneLake Catalog erkunden](https://learn.microsoft.com/de-de/fabric/governance/onelake-catalog-explore)
- [Microsoft Learn — Fabric Deployment Process](https://learn.microsoft.com/de-de/fabric/cicd/deployment-pipelines/understand-the-deployment-process)

> **Feature-Stand:** Juli 2026. Microsoft-Produktnamen, geeignete Item Types, Tenant Controls, Certifier Permissions, Discovery-Verhalten, APIs und Lizenzbedingungen können sich ändern. Prüfe vor der Umsetzung die aktuelle Microsoft-Dokumentation und die Konfiguration des eingesetzten Tenants.

Berufliche Qualifikationen oder externe Compliance-Zertifizierungen können individuelle Kompetenz oder organisatorische Controls nachweisen. Sie ersetzen keine metrikspezifische Definition, Lineage, Qualität, Reconciliation, Access- und Lifecycle-Evidenz.

## Playbooks

Verwende diese Playbooks wieder, statt ihre Governance-Entscheidungen neu zu erstellen:

- [KPI & Metric Governance](/playbooks/kpi-metric-governance) — definiert, wie fachliche Bedeutung, Berechnungen, Dimensionen, Ownership und kontrollierte Änderungen konsistent bleiben.
- [The Missing Pieces — Trusted Metrics](/playbooks/missing-pieces-trusted-metrics) — prüft, ob Definition, Ownership, Lineage, Qualität, Access und Lifecycle für vertrauenswürdige Nutzung ausreichen.
- [KPI-Definition, Ownership und Versionierung](/playbooks/define-kpi) — erzeugt den freigegebenen und historisch reproduzierbaren Kennzahlenvertrag für den Certification Record.

Diese Story ersetzt die Playbooks nicht. Sie definiert die engere Entscheidung, die deren Evidenz in einen kontrollierten Trust State und eine Plattformimplementierung überführt.

## Nächster Schritt

Wähle eine geschäftskritische Kennzahl, die bereits in einem produktiven Power BI Semantic Model existiert.

Beginne nicht mit der Zertifizierung des gesamten Workspaces. Erstelle einen vollständigen Metric Certification Record und prüfe die Kette:

```text
Freigegebene KPI-Definition
→ Metric Placement Decision
→ Production-Implementierung
→ aktuelles Evidenzpaket
→ Owner- und Reviewer-Freigabe
→ Plattform-Endorsement
→ Discovery und erlaubte Wiederverwendung
→ Rezertifizierungs-Lifecycle
```

Nutze eine Kennzahl mit sichtbarer Business-Auswirkung und mindestens einer bekannten konkurrierenden Implementierung. Reconcile das Ergebnis, schließe Evidenzlücken oder begrenze sie zeitlich, weise ein Review-Datum zu und setze das Plattform-Badge erst nach abgeschlossener Governance-Entscheidung.

Eine Trusted Metric ist nicht die Kennzahl mit dem auffälligsten Badge. Es ist die Kennzahl, deren Bedeutung, Implementierung, Evidenz und Lifecycle reviewbar bleiben.
