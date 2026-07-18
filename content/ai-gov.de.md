---
title: AI Governance — Wie KI kontrollierbar und verantwortbar wird
description: Wie Rollen, Risikoklassifikation, freigegebene Modelle und Use Cases, kontrollierte Daten, Versionierung, Zugriff, Human Oversight, Auditierbarkeit und Lifecycle-Gates AI zu einer verantwortbaren Enterprise-Fähigkeit machen.
category: Künstliche Intelligenz
tags:
  - ai
  - ai-foundations
  - ai-governance
  - responsible-ai
  - risk-management
  - model-governance
  - data-governance
  - human-oversight
  - auditability
  - ai-lifecycle
  - access-control
  - privacy
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 7
seriesTitle: AI Foundations
hero: images/playbooks/ai-gov-hero.png
---

## AI Governance ist ein Betriebsmodell — kein Policy-Dokument

Die vorherigen Teile dieser Serie haben erklärt, wie Modelle lernen, wie Sprachmodelle Ausgaben erzeugen, wie Unternehmenswissen über RAG in AI gelangt, wie Agenten Aktionen ausführen, wo AI-Systeme scheitern und wie Qualität im Produktionsbetrieb bewertet werden kann.

Die abschließende Frage ist organisatorisch:

> **Wie entscheidet eine Organisation, welche AI für welchen Zweck, mit welchen Daten, unter wessen Verantwortung und mit welchen Kontrollen eingesetzt werden darf?**

AI Governance liefert dafür das Betriebsmodell.

Sie verbindet Business Ownership, Data Governance, Model Governance, Datenschutz, Security, Risikomanagement, Engineering, Betrieb und menschliche Entscheidungen. Ihr Zweck ist nicht, Experimente zu verhindern. Sie soll Experimente, Produktivsetzung und Skalierung **kontrollierbar, nachvollziehbar und verantwortbar** machen.

Ein kontrolliertes AI-System sollte folgende Fragen beantworten können:

- Welchen Geschäftszweck verfolgt der Use Case?
- Wer verantwortet Ergebnis und Risiko?
- Welche Modelle, Prompts, Agenten und Tools sind freigegeben?
- Welche Datenklassen dürfen verarbeitet werden?
- Woher stammen Trainings-, Evaluations- und Kontextdaten?
- Welche Systemversion hat ein Ergebnis erzeugt?
- Welche Kontrollen waren aktiv?
- Wer hat die Produktivsetzung freigegeben?
- Wann ist eine menschliche Prüfung erforderlich?
- Wie wird die Leistung überwacht?
- Wie kann das System geändert, zurückgesetzt oder stillgelegt werden?

Ohne diese Antworten besitzt eine Organisation möglicherweise einen AI-Prototyp, aber noch keine verantwortbar betriebene AI-Fähigkeit.

```flow linear vertical
Geschäftszweck
Verantwortliche Ownership
Risikobasierte Kontrollen
Kontrollierte Umsetzung
Messbarer Betrieb
Nachvollziehbare Änderung
Gesteuerte Stilllegung
```

## AI Governance baut auf bestehender Governance auf

AI Governance wird häufig wie eine neue Spezialdisziplin behandelt, die mit Model Cards, Ethikprinzipien oder einem AI Committee beginnt.

Diese Elemente können sinnvoll sein, reichen jedoch nicht aus.

Ein AI-System ist auf Fähigkeiten angewiesen, die in der Organisation bereits vorhanden sein sollten:

- Datenqualität und Source Ownership,
- Metadaten, Katalog und Lineage,
- PII- und Privacy Governance,
- Access & Security Governance,
- Ownership und Stewardship,
- KPI & Metric Governance,
- Lifecycle und Retention,
- Richtlinien, Standards und Change Control.

AI ergänzt neue Governance-Objekte — Modelle, Prompts, Agenten, Tools, Trainingsläufe, Evaluationsdaten, Guardrails und Ergebnisse. Sie ersetzt das bestehende Fundament nicht.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-gov.img1-de.png"
        alt="Scope der AI Governance mit Use Cases, Modellen, Prompts, Agenten, Trainingsdaten, Kontextdaten, Richtlinien und Ergebnissen auf Basis bestehender Governance-Fähigkeiten"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Vertrauenswürdige AI benötigt ein vertrauenswürdiges Governance-Fundament. Schwache Datenqualität, unklare Ownership, fehlende Lineage oder unkontrollierter Zugriff bleiben mit AI bestehen und können im großen Maßstab verstärkt werden.
    </figcaption>
</figure>

Der Zusammenhang ist unmittelbar:

| Bestehende Governance-Fähigkeit | Bedeutung für AI |
| --- | --- |
| **Data Quality Governance** | freigegebene Datenquellen, Qualitätsregeln, Schwellenwerte, bekannte Einschränkungen und Behebung |
| **Metadata, Catalog & Lineage** | Herkunft von Trainings-, Evaluations- und Kontextdaten sowie nachvollziehbare Abhängigkeiten |
| **PII & Privacy Governance** | rechtmäßige Nutzung, Zweckbindung, Minimierung, Aufbewahrung und Schutz betroffener Personen |
| **Access & Security Governance** | Identitäten, Rollen, Secrets, APIs, Tools, Datenrechte und Funktionstrennung |
| **Ownership & Stewardship** | verantwortliche Business-, Daten-, Modell- und Betriebsrollen |
| **KPI & Metric Governance** | kontrollierte Geschäftsdefinitionen und belastbare Entscheidungssignale |
| **Lifecycle & Retention** | kontrollierte Einführung, Änderung, Nutzung, Archivierung und Löschung |
| **Policies & Standards** | konsistente Entscheidungsregeln, Reviews und Evidenzanforderungen |

> **AI Governance beginnt nicht beim Modell. Sie beginnt beim Geschäftszweck und reicht über jede Abhängigkeit, die das Ergebnis beeinflussen kann.**

## Was muss tatsächlich kontrolliert werden?

„Das Modell kontrollieren“ ist für moderne AI-Systeme zu eng.

Ein Produktivsystem kann Folgendes kombinieren:

```text
Business Use Case
+ Modell und Provider
+ System- und Aufgabenprompts
+ abgerufener Kontext
+ Tools und APIs
+ Agenten-Orchestrierung
+ Policies und Guardrails
+ Laufzeitkonfiguration
+ Human Review
+ nachgelagerter Geschäftsprozess
```

Jede Komponente kann Verhalten, Risiko und Verantwortung verändern.

Ein belastbares AI-Inventar benötigt deshalb mehr als eine Liste von Modellnamen.

| Governance-Objekt | Mindestinformationen |
| --- | --- |
| **AI Use Case** | Zweck, Owner, Nutzer, Entscheidung oder Aktion, erwarteter Nutzen, Risikoklasse, Status |
| **Modell** | Provider, Hosting, Modellfamilie, Version, erlaubte Nutzung, Einschränkungen, Freigabestatus |
| **Prompt** | Owner, Version, Zweck, Inputs, Ausgabeschema, verbotenes Verhalten, Teststatus |
| **Agent** | Ziel, Orchestrierungslogik, Tools, Berechtigungen, Stop-Bedingungen, Freigabepunkte |
| **Trainings- / Fine-Tuning-Daten** | Quelle, Rechte, Klassifikation, Qualität, Lineage, Snapshot, Retention |
| **Evaluationsdaten** | Fälle, Herkunft, erwartetes Verhalten, Risikoabdeckung, Owner, Version |
| **Kontext- / RAG-Daten** | Quelle, Gültigkeit, Zugriffsregeln, Indexversion, Lösch- und Aktualisierungsprozess |
| **Tool / API** | erlaubte Operationen, Credentials, Parametergrenzen, Seiteneffekte, Monitoring |
| **Policy / Guardrail** | Regel, Owner, Implementierung, Version, Tests, Ausnahmeprozess |
| **Deployment** | Umgebung, Konfiguration, Modell- und Prompt-Version, Nutzer, Zugriffsrollen |
| **Entscheidung / Output** | Eingangskontext, Systemversion, Evidenz, Ergebnis, Review, Aktion und Outcome |

Das Inventar ist nicht nur Dokumentation. Es bildet die Grundlage für Freigabe, Monitoring, Incident Response, Change Management und Stilllegung.

## Rollen und Verantwortlichkeiten überschreiten Organisationsgrenzen

AI Governance scheitert, wenn sie vollständig an Data Science, IT, Legal oder ein isoliertes AI Committee delegiert wird.

Das Business verantwortet Zweck und Ergebnis. Datenrollen verantworten die zulässige und verlässliche Datennutzung. Technische Rollen bauen und betreiben das System. Privacy, Security, Risk und Compliance definieren oder validieren Kontrollen. Human Reviewer bleiben dort verantwortlich, wo der Prozess menschliches Urteil benötigt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-gov.img2-de.png"
        alt="Rollen und Verantwortlichkeiten der AI Governance mit Business, Product, Daten, Modell, Entwicklung, Privacy, Security, Risk, Human Review und Operations rund um einen AI Use Case"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein AI Use Case umfasst normalerweise mehrere Verantwortungsbereiche. „Das AI-Team ist verantwortlich“ ist kein ausreichendes Rollenmodell.
    </figcaption>
</figure>

### Business Owner

Verantwortet:

- Geschäftszweck und erwarteten Nutzen,
- akzeptable Geschäftsauswirkungen und Restrisiken,
- Ergebnisqualität und Prozessintegration,
- den fortbestehenden Bedarf,
- die finale Eskalation bei wesentlichen Geschäftsauswirkungen.

Der Business Owner sollte keine technischen Details allein freigeben, die er nicht fachgerecht beurteilen kann. Er bleibt jedoch dafür verantwortlich, warum das System eingesetzt wird und wie seine Ergebnisse das Geschäft beeinflussen.

### AI Product Owner

Verantwortlich für:

- den End-to-End-Backlog des Use Cases,
- Anforderungen und Stakeholder-Koordination,
- Lifecycle-Status und Release Readiness,
- Vollständigkeit der Dokumentation,
- Koordination von Risk Reviews, Freigaben und Neubewertungen.

### Data Owner und Data Steward

Der Data Owner genehmigt, ob bestimmte Daten für den genannten Zweck verwendet werden dürfen.

Der Data Steward stellt sicher, dass:

- die Klassifikation korrekt ist,
- Qualitätsanforderungen definiert sind,
- Lineage und Metadaten gepflegt werden,
- bekannte Einschränkungen sichtbar bleiben,
- Löschung, Korrektur und Retention umgesetzt werden.

### Model Owner

Verantwortlich für:

- freigegebenes Modell und Modellversion,
- dokumentierte Fähigkeiten und Grenzen,
- Evaluationsnachweise,
- Modellrisiko und Performance,
- Upgrade-, Rollback- und Stilllegungsentscheidungen.

Bei einer externen Modell-API verschwindet die Ownership nicht. Die Organisation benötigt weiterhin eine interne Rolle, die Auswahl, erlaubte Nutzung und Provider-Änderungen verantwortet.

### AI Developer / Data Scientist

Verantwortlich für:

- Implementierung,
- Prompt- und Agentenlogik,
- Evaluation und Tests,
- technische Dokumentation,
- reproduzierbare Builds und Releases,
- sichere Integration von Modellen, Daten und Tools.

### Privacy / Legal

Bewertet:

- rechtmäßige Verarbeitung,
- Zweckbindung und Datenminimierung,
- PII und besondere Datenkategorien,
- Rechte betroffener Personen,
- Verträge und Provider-Bedingungen,
- geistiges Eigentum und Vertraulichkeit,
- anwendbare rechtliche und regulatorische Anforderungen.

### Information Security

Kontrolliert:

- Identitäten und Rollen,
- Secrets und Service Accounts,
- Tool- und API-Berechtigungen,
- Threat Modeling,
- Schutz vor Datenabfluss und Exfiltration,
- Incident Detection und Response,
- sicheres Hosting und Provider-Integration.

### Risk / Compliance

Koordiniert:

- Use-Case- und Impact-Klassifikation,
- Kontrollanforderungen,
- Ausnahmen und akzeptiertes Risiko,
- Evidenz für interne und externe Prüfungen,
- regelmäßige Neubewertung,
- regulatorisches Mapping.

### Human Reviewer

Ein Human Reviewer ist nicht einfach „irgendein Mensch im Prozess“.

Die Rolle benötigt:

- definierte Review-Kriterien,
- ausreichende Informationen und Evidenz,
- Befugnis zur Ablehnung, Korrektur oder Eskalation,
- ausreichende Zeit und Kompetenz,
- Schutz vor Automation Bias,
- Dokumentation wesentlicher Overrides.

### Operations

Verantwortlich für:

- Deployment und Laufzeitkonfiguration,
- Verfügbarkeit und Observability,
- Alerts und Incident Handling,
- Nutzungs- und Kostenmonitoring,
- Umsetzung von Änderungen,
- Rollback und Stilllegung.

## Entscheidungsrechte sind wichtiger als Committee-Namen

Ein AI Council oder Governance Board kann Standards koordinieren und Ausnahmen entscheiden. Es sollte nicht zum Owner jedes einzelnen Ergebnisses werden.

Ein praktisches Entscheidungsmodell unterscheidet mindestens:

| Entscheidung | Verantwortliche Rolle |
| --- | --- |
| Ist der Use Case wertvoll und weiterhin erforderlich? | Business Owner |
| Dürfen bestimmte Daten für diesen Zweck verwendet werden? | Data Owner, bei Bedarf mit Privacy / Legal |
| Ist das Modell für diesen Kontext freigegeben? | Model Owner mit Risk, Security und relevanten Spezialisten |
| Ist die Umsetzung technisch produktionsreif? | Engineering / Technical Owner |
| Werden Restrisiken akzeptiert? | benannte Business- und Risk-Autorität |
| Darf das System produktiv gehen? | definierte Release-Autorität |
| Muss ein Ergebnis geprüft oder blockiert werden? | durch Policy festgelegte operative Rolle |
| Muss das System gestoppt oder stillgelegt werden? | Business Owner und Betriebs- / Risk-Autorität |

Das konkrete RACI unterscheidet sich je Organisation. Nicht verhandelbar ist, dass jede wesentliche Entscheidung eine benannte verantwortliche Rolle besitzt.

## Den Use Case klassifizieren — nicht nur das Modell

Ein Modell ist nicht in jedem Kontext automatisch risikoarm oder risikoreich.

Dasselbe Sprachmodell kann eingesetzt werden, um:

- interne Texte umzuformulieren,
- Richtlinien zu durchsuchen,
- Kundenaktionen zu empfehlen,
- Mitarbeiterfälle zu priorisieren,
- Kredite zu genehmigen,
- einen sicherheitsrelevanten Prozess zu steuern.

Das Modell kann identisch sein. Die Auswirkung ist es nicht.

Die Risikoklassifikation sollte deshalb beim **Use Case**, bei den betroffenen Menschen, dem Automatisierungsgrad, der Datensensitivität und den Folgen von Fehlern oder Missbrauch beginnen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-gov.img3-de.png"
        alt="Risikomatrix für AI Use Cases aus Datensensitivität und potenzieller Auswirkung von assistiver Nutzung bis zu kritischen automatisierten Entscheidungen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Höhere Auswirkungen und höhere Datensensitivität erfordern stärkere Kontrollen. Die Matrix ist ein internes Governance-Instrument und ersetzt keine formale rechtliche oder regulatorische Klassifikation.
    </figcaption>
</figure>

### Sinnvolle Klassifikationsdimensionen

#### Zweck und betroffener Prozess

- Entwurf und Produktivität,
- Wissenszugriff,
- operative Unterstützung,
- Empfehlung oder Entscheidungsunterstützung,
- automatisierte Entscheidung,
- autonome oder sicherheitsrelevante Aktion.

#### Potenzielle Auswirkung

- Unannehmlichkeit oder geringe Nacharbeit,
- finanzieller Verlust,
- Verweigerung einer Leistung oder Chance,
- Diskriminierung oder unfaire Behandlung,
- Privacy- oder Vertraulichkeitsverletzung,
- rechtliche oder vertragliche Folge,
- Auswirkung auf Gesundheit, Sicherheit oder Grundrechte.

#### Datensensitivität

- öffentlich,
- intern,
- vertraulich,
- personenbezogene Daten,
- besondere Datenkategorien oder hochsensible Daten,
- regulierte oder gesetzlich geschützte Informationen.

#### Autonomie

- erzeugt einen Entwurf,
- liefert eine Empfehlung,
- startet einen Workflow,
- führt eine reversible Aktion aus,
- führt eine irreversible Aktion aus,
- trifft oder bestimmt faktisch eine wesentliche Entscheidung.

#### Skalierung und Reversibilität

- Anzahl betroffener Personen oder Transaktionen,
- Ausführungshäufigkeit,
- Erkennbarkeit und Korrigierbarkeit eines Fehlers,
- Zeit bis zum möglichen Schaden,
- Verfügbarkeit von Rollback oder Kompensation.

#### Exposition und Bedrohung

- nur interne Nutzer,
- Kunden oder externe Nutzer,
- öffentlicher Zugang,
- Fähigkeit zum Tool-Aufruf,
- Verarbeitung nicht vertrauenswürdiger Inhalte,
- Anreiz für Manipulation oder Missbrauch.

### Ein internes Risikomodell

Eine pragmatische interne Klassifikation kann vier Stufen verwenden:

| Stufe | Typisches Profil | Kontrollintensität |
| --- | --- | --- |
| **Niedrig** | assistiv, geringe Auswirkung, nicht sensibel, leicht prüfbar | Standarddokumentation, Zugriffskontrolle, Basistests und Monitoring |
| **Moderat** | operativer Einfluss oder vertrauliche Daten | dokumentiertes Assessment, stärkere Evaluation, Logging, regelmäßiges Review |
| **Hoch** | wesentliche Entscheidungen, PII, hohe finanzielle oder menschliche Auswirkung | unabhängige Prüfung, verpflichtende Oversight, robuste Tests, formale Freigabe, kontinuierliches Monitoring |
| **Eingeschränkt** | inakzeptabel oder nur ausnahmsweise erlaubt | explizite Executive- / Legal-Freigabe oder Verbot; stärkste Kontrollen und vollständige Evidenz |

> **Diese interne Matrix unterstützt Governance-Entscheidungen. Sie darf nicht als Ersatz für Risikokategorien, Pflichten oder rechtliche Bewertungen nach anwendbarem Recht — einschließlich des EU AI Act — dargestellt werden.**

## Freigegebene Modelle und freigegebene Use Cases sind unterschiedliche Kontrollen

Eine häufige Kontrollmaßnahme ist eine Liste freigegebener Modelle. Das ist sinnvoll, aber unvollständig.

Ein Modell kann für einen Zweck freigegeben und für einen anderen verboten sein.

Beispiel:

| Modellstatus | Use Case | Entscheidung |
| --- | --- | --- |
| freigegebenes Enterprise-Modell | öffentliche Dokumente zusammenfassen | unter Standardkontrollen zulässig |
| dasselbe Modell | vertrauliche Verträge verarbeiten | nur in freigegebener Hosting- und Datengrenze zulässig |
| dasselbe Modell | Kundenkommunikation entwerfen | mit Review und Logging zulässig |
| dasselbe Modell | autonome Personalentscheidung | ohne separate Prüfung und explizite Freigabe unzulässig |
| nicht freigegebener öffentlicher Consumer Service | vertrauliche Unternehmensdaten | verboten |

Die Freigabe sollte mehrere Dimensionen abdecken:

- Modellprovider und Vertrag,
- Hosting und Datenstandort,
- Retention und Provider-Training-Einstellungen,
- unterstützte Datenklassen,
- zulässige Regionen und Nutzer,
- erlaubte und verbotene Verwendung,
- Modellversion und Update-Verhalten,
- Security- und Privacy-Bewertung,
- Evaluationsnachweise,
- Fallback- und Exit-Strategie.

Der Approved Model Catalog darf keine statische Tabelle werden. Er benötigt Version, Owner, Status, Gültigkeitsdatum, Einschränkungen und Neubewertungsdatum.

## Datenklassifikation, PII und Zweckbindung

AI-Systeme können Daten über viele Wege erhalten:

- von Nutzern eingegebene Prompts,
- hochgeladene Dateien,
- Fine-Tuning-Datensätze,
- Feature Pipelines,
- RAG-Indizes,
- Tool-Ergebnisse,
- Gesprächshistorien,
- Logs und Traces,
- menschliches Feedback,
- gecachte Ausgaben.

Eine Policy, die nur Trainingsdaten kontrolliert, verfehlt die meisten modernen Enterprise-AI-Datenflüsse.

### Vor der Verbindung klassifizieren

Für jeden Datenpfad ist festzulegen:

- Klassifikation,
- Owner,
- erlaubter Zweck,
- erlaubtes Modell oder Provider,
- geografische und vertragliche Einschränkungen,
- Retention,
- Maskierungs- oder Anonymisierungsanforderungen,
- Zugriffsrollen,
- Löschprozess,
- Monitoring-Anforderungen.

Die Anwendung sollte diese Regeln technisch durchsetzen, soweit dies möglich ist. Eine Prompt-Anweisung wie „keine vertraulichen Daten ausgeben“ ist kein Zugriffskontrollsystem.

### PII verschwindet nicht in Embeddings oder Logs

Personenbezogene oder vertrauliche Inhalte können weiterhin sensibel sein, wenn sie:

- eingebettet,
- indexiert,
- gecacht,
- zusammengefasst,
- in Prompts aufgenommen,
- in Traces gespeichert,
- in Evaluationsdaten kopiert,
- als Human-Review-Beispiele verwendet

werden.

Ein Embedding ist nicht automatisch anonym. Ein Vektorindex benötigt weiterhin Klassifikation, Zugriffskontrolle, Löschung und Lifecycle Management entsprechend seinem Quellinhalt.

### Datenminimierung by Design

Ein System sollte nicht mehr Daten erhalten, als der Use Case benötigt.

Beispiele:

- nur autorisierte und relevante Dokumentabschnitte abrufen,
- Identifikatoren vor der Modellverarbeitung maskieren,
- strukturierte Tools für Live-Daten verwenden, statt breite Datenbankextrakte zu kopieren,
- Mandanten- und Regionalindizes trennen,
- keine vollständigen Prompts loggen, wenn Metadaten ausreichen,
- sensible Traces kürzer aufbewahren,
- für Tests geeignete synthetische oder de-identifizierte Daten verwenden.

## Herkunft und Qualität von Trainings- und Kontextdaten

Die Formulierung „mit Unternehmensdaten trainiert“ kann mehrere unterschiedliche Mechanismen verbergen:

| Mechanismus | Governance-Fokus |
| --- | --- |
| **Pre-Training durch Provider** | Provider-Transparenz, Rechte, Modellgrenzen und Procurement Assessment |
| **Fine-Tuning** | Datenrechte, Repräsentativität, PII, Qualität, Version und Rollback |
| **Klassisches ML-Training** | Features, Labels, Sampling, Bias, Drift, Reproduzierbarkeit und Validierung |
| **RAG / Context Retrieval** | freigegebene Quellen, Gültigkeit, Access Filtering, Indexversion, Aktualität und Löschung |
| **In-Context-Beispiele** | Herkunft, Korrektheit, Vertraulichkeit und Prompt-Injection-Risiko |
| **Human Feedback** | Qualität der Reviewer, Privacy, Bias, Retention und Nachvollziehbarkeit |

Für jeden kontrollierten Datensatz sollten mindestens folgende Informationen dokumentiert werden:

```text
Dataset-ID und Version
Zweck und erlaubte Nutzung
Owner und Steward
Quellsysteme
Erhebungs- und Transformationsprozess
Klassifikation und erforderliche Rechtsgrundlage
Qualitätsregeln und bekannte Einschränkungen
Sampling und Abdeckung
Kennzeichnung von Schätzungen, Korrekturen und synthetischen Daten
Lineage und Gültigkeitszeitraum
Retention- und Löschregeln
Abhängige Modelle, Indizes und Use Cases
```

Ein technisch sauberer Datensatz kann für einen Use Case dennoch ungeeignet sein. Qualität muss am Zweck definiert werden.

Relevante Fragen:

- Repräsentieren die Daten Population und Szenarien des späteren Systems?
- Sind seltene, aber kritische Fälle enthalten?
- Sind Labels konsistent und prüfbar?
- Sind historische Entscheidungen selbst verzerrt oder falsch?
- Bleiben Schätzwerte sichtbar?
- Sind alte und neue Richtlinien unterscheidbar?
- Können gelöschte oder korrigierte Quellen aus Indizes und Testdaten entfernt werden?
- Werden Qualitätsänderungen an abhängige AI-Systeme weitergegeben?

Das verwandte Playbook [Trash In, Trash Out](/playbooks/trash-iinout) untersucht diese Datenkette im Detail.

## Zugriffskontrolle muss Modelle, Daten, Prompts, Tools und Administration umfassen

AI Access Control ist breiter als der Zugriff auf ein Chatfenster.

Unterschiedliche Rechte werden benötigt für:

- Nutzung eines Modells,
- Zugriff auf eine Datenquelle,
- Retrieval eines Dokuments oder Datensatzes,
- Änderung eines produktiven Prompts,
- Änderung eines Agenten-Workflows,
- Hinzufügen oder Ändern von Tools,
- Freigabe eines Deployments,
- Einsicht in sensible Traces,
- Überschreiben eines Guardrails,
- Deaktivierung von Monitoring,
- Löschen von Audit-Evidenz.

Ein produktives Design sollte unterscheiden:

```flow linear vertical
Nutzeridentität
Erlaubter Use Case
Erlaubte Daten
Erlaubtes Modell
Erlaubte Tools
Erlaubte Aktion
Erforderliche Freigabe
Protokolliertes Ergebnis
```

### Berechtigungen vor dem Modell durchsetzen

Bei RAG und Tool-Nutzung sollte die Autorisierung in Anwendung, Retrieval Layer, API oder Zielsystem geprüft werden.

Das Modell darf unzulässige Inhalte nicht erst erhalten und anschließend verbergen sollen.

### Least Privilege für Agenten

Ein Agent sollte nur die Tools und Berechtigungen erhalten, die er für die aktuelle Aufgabe benötigt.

Zu bevorzugen sind:

- eng definierte Tools statt generischem Datenbank- oder Shell-Zugriff,
- Allow Lists,
- Parametervalidierung,
- Mandanten- und Nutzerkontext,
- Read-only als Standard,
- Schritt-, Zeit- und Kostenlimits,
- separate Freigabe für folgenreiche Schreiboperationen,
- Idempotenz, Rollback oder Kompensation, soweit möglich.

### Kritische Funktionen trennen

Dieselbe Person sollte nicht ohne unabhängige Kontrolle:

- einen Produktivprompt ändern,
- einen Guardrail abschwächen,
- die Änderung freigeben,
- sie deployen
- und anschließend die Evidenz löschen

können.

Der Grad der Funktionstrennung sollte dem Risiko entsprechen.

## Ein kontrollierter Lifecycle von der Idee bis zur Stilllegung

AI Governance muss als Lifecycle funktionieren, nicht als einmalige Freigabe.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-gov-img4-de.png"
        alt="Kontrollierter AI Lifecycle von Idee und Klassifikation über Datenbewertung, Entwicklung, Tests, Risikoprüfung, Freigabe, Bereitstellung, Monitoring, Change Management und Stilllegung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Governance Gates erzeugen explizite Entscheidungen und Evidenz über den gesamten Lifecycle. Änderungen an Modellen, Prompts, Agenten, Daten oder Policies können eine erneute Prüfung am passenden Gate auslösen.
    </figcaption>
</figure>

### 1. Idee

Definieren:

- Problem und gewünschtes Ergebnis,
- betroffene Nutzer und Stakeholder,
- Geschäftsnutzen,
- Alternativen ohne AI,
- verantwortlicher Business Owner.

Die erste Governance-Frage lautet nicht „Welches Modell verwenden wir?“, sondern „Soll für dieses Problem überhaupt AI eingesetzt werden?“

### 2. Use-Case-Klassifikation

Dokumentieren:

- Zweck,
- Auswirkung,
- Datensensitivität,
- Autonomiegrad,
- betroffene Personen,
- vorläufige Risikoklasse,
- erforderlicher Kontrollpfad.

### 3. Datenbewertung

Bestätigen:

- freigegebene Quellen,
- Ownership und Klassifikation,
- PII- und Vertraulichkeitsanforderungen,
- Qualität und Abdeckung,
- Lineage und Gültigkeit,
- Retention und Löschung,
- technische Zugriffsdurchsetzung.

### 4. Entwicklung

Umsetzen mit:

- freigegebenen Modellen und Providern,
- versionierten Prompts und Agentenlogiken,
- eng definierten Tools und expliziten Policies,
- sicheren Secrets und Umgebungen,
- nachvollziehbarer Konfiguration.

### 5. Testing und Evaluation

Bewerten:

- Aufgabenqualität,
- Source Grounding,
- Retrieval,
- Robustheit,
- Bias und Fairness, soweit relevant,
- Privacy und Security,
- Tool-Ausführung,
- Fehlerpfade,
- Human-Review-Workflow,
- Latenz und Kosten.

Part 6, [AI evaluieren und betreiben](/playbooks/ai-eval), beschreibt dieses Evaluationsmodell im Detail.

### 6. Risikoprüfung

Bewerten:

- Wirksamkeit der Kontrollen,
- ungelöste Einschränkungen,
- Restrisiko,
- Betriebsbereitschaft,
- Incident- und Rollback-Pläne,
- Bedarf zusätzlicher Freigaben oder Einschränkungen.

### 7. Freigabe

Ein Freigabeprotokoll sollte enthalten:

- freigegebene Systemversion,
- freigegebenen Scope und Nutzerkreis,
- freigegebene Datenklassen,
- Bedingungen und Ausnahmen,
- erforderliches Monitoring,
- Ablauf- oder Review-Datum,
- benannte Approver.

### 8. Bereitstellung

Das Produktiv-Deployment sollte aktivieren:

- Zugriffsrollen,
- Guardrails,
- Logging und Nachvollziehbarkeit,
- Monitoring und Alerts,
- Rollback-Konfiguration,
- operative Ownership.

### 9. Betrieb und Monitoring

Überwachen:

- Qualität und Fehlerraten,
- nicht belegte Ausgaben,
- Retrieval- und Tool-Fehler,
- Drift,
- Zugriffsanomalien,
- Policy-Verstöße,
- Overrides und Eskalationen,
- Incidents,
- Latenz und Kosten,
- Nutzer- und Business-Outcomes.

### 10. Change Management

Änderungen bewerten an:

- Modell,
- Prompt,
- Agent,
- Tool,
- Datenquelle,
- Retrieval Index,
- Policy,
- Nutzerpopulation,
- Geschäftsprozess,
- Hosting oder Provider.

Nicht jede Änderung erfordert einen vollständigen Neustart. Jede wesentliche Änderung benötigt jedoch eine definierte Impact-Bewertung sowie den passenden Re-Test- und Re-Approval-Pfad.

### 11. Stilllegung

Stilllegung umfasst mehr als das Abschalten der Benutzeroberfläche.

Erforderlich sein können:

- Zugriffe und Credentials entfernen,
- Tools und Agenten deaktivieren,
- Modellartefakte archivieren oder löschen,
- Logs nach Policy löschen oder aufbewahren,
- Vektorindizes und gecachten Kontext entfernen,
- Provider-Integrationen beenden,
- Endstatus dokumentieren,
- abhängige Prozesse umleiten,
- erforderliche Audit-Evidenz erhalten.

## Das vollständige AI-System versionieren

Eine Modellversion allein reproduziert kein AI-Ergebnis.

Das Verhalten kann außerdem abhängen von:

- System Prompt,
- Task Prompt,
- Beispielen,
- Retrieval Index,
- Embedding-Modell,
- Reranker,
- Tools,
- Policies,
- Guardrails,
- Laufzeitparametern,
- Code,
- Umgebung,
- Nutzer- und Berechtigungskontext.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-gov-img5-de.png"
        alt="AI-Governance-Artefakte, die versioniert werden müssen, und operative Evidenz, die auditierbar sein muss"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Reproduzierbarkeit und Verantwortung benötigen versionierte technische Artefakte sowie einen Audit Trail für Zugriffe, Änderungen, Freigaben, Performance, Vorfälle und Retention.
    </figcaption>
</figure>

Ein Release Manifest kann diese Elemente verbinden:

```yaml
use_case:
  id: customer-policy-assistant
  version: 3.2
  risk_class: moderate
  owner: customer-operations

model:
  provider: approved-provider
  model: enterprise-language-model
  version: 2026-06
  hosting_profile: eu-enterprise

prompts:
  system_prompt: 7.4
  answer_template: 3.1

retrieval:
  source_collection: governed-policies
  index_snapshot: 2026-07-15
  embedding_model: embed-v4
  permission_filter: acl-policy-5

agent:
  workflow_version: 2.6
  tools:
    - policy-search: 4.0
    - ticket-create: 2.1
  max_steps: 6
  write_approval_required: true

controls:
  policy_bundle: ai-assistant-4.3
  output_validator: 2.2
  monitoring_profile: moderate-risk-3

evidence:
  evaluation_run: eval-2026-0716-04
  approval_record: approval-2026-0717-02
  deployed_at: 2026-07-18T08:00:00Z
```

Das konkrete Schema ist implementierungsspezifisch. Die Governance-Anforderung lautet, dass ein Ergebnis mit dem relevanten Systemzustand verbunden werden kann.

## Was muss auditierbar sein?

Auditierbarkeit ist die Fähigkeit, wesentliche Ereignisse und Entscheidungen mit geeigneter Evidenz zu rekonstruieren.

Abhängig von Risiko und rechtlichen Grenzen kann die Evidenz umfassen:

### Identität und Zugriff

- Nutzer- und Service-Identität,
- zugewiesene Rollen,
- Daten- und Modellzugriffe,
- Tool-Berechtigungen,
- administrative Aktionen,
- privilegierte Overrides.

### Inputs und Kontext

- Input-Kategorie oder vollständiger Input, soweit zulässig,
- relevanter Gesprächszustand,
- abgerufene Quellen und Versionen,
- angewendete Filter,
- Datenklassifikation,
- vom Modell verwendete Tool-Ergebnisse.

Sensible Inhalte sollten nicht ohne Zweck geloggt werden. Auditierbarkeit und Datenminimierung müssen durch risikobasiertes Logging, Redaction, Hashes, Referenzen und kontrollierte Retention ausbalanciert werden.

### Systemzustand

- Modell und Konfiguration,
- Prompt- und Agentenversion,
- Tools und Policies,
- Laufzeitparameter,
- Code- und Deployment-Version,
- aktive Feature Flags und Guardrails.

### Entscheidungen und Aktionen

- erzeugtes Ergebnis,
- Confidence- oder Qualitätsindikatoren, soweit sinnvoll,
- Evidenz und Quellen,
- Human Review und Override,
- Tool Calls und Seiteneffekte,
- nachgelagerte Business-Aktion,
- finales Outcome.

### Änderungen und Freigaben

- Change Request,
- Grund und Impact Assessment,
- Testnachweise,
- Approver,
- Gültigkeitsdatum,
- Ausnahme und Ablauf,
- Rollback-Historie.

### Incidents und Monitoring

- Alerts,
- Policy-Verstöße,
- verdächtige Zugriffe,
- fehlerhafte oder schädliche Ausgaben,
- Root-Cause-Analyse,
- Mitigation und Recovery,
- Lessons Learned und neue Regressionstests.

> **Auditierbarkeit bedeutet nicht, alles unbegrenzt zu speichern. Sie bedeutet, die richtige Evidenz für den richtigen Zweck unter kontrolliertem Zugriff und definierter Retention aufzubewahren.**

## Human Oversight muss wirksam sein

Human Oversight wird häufig auf eine Checkbox reduziert: „Ein Mensch bleibt in the loop.“

Diese Aussage ist zu ungenau.

Sinnvolle Oversight-Modelle sind:

| Oversight-Modell | Bedeutung |
| --- | --- |
| **Human informed** | eine Person erhält Informationen, genehmigt aber nicht jeden Fall |
| **Human reviews exceptions** | Normalfälle laufen weiter; definierte Auffälligkeiten werden eskaliert |
| **Human approves action** | das System bereitet eine Empfehlung vor; ein Mensch autorisiert die Ausführung |
| **Human makes the decision** | AI liefert Evidenz oder Analyse; der Mensch entscheidet |
| **Human can stop the system** | autorisierte Personen können Betrieb oder Autonomie aussetzen |

Wirksame Oversight erfordert:

- einen klar definierten Entscheidungspunkt,
- verständliche Evidenz,
- ausreichende Kompetenz und Zeit,
- Befugnis zur Ablehnung oder Änderung,
- Eskalationswege,
- Schutz vor bloßem Abnicken,
- Messung von Overrides und übersehenen Fehlern,
- regelmäßige Prüfung, ob die Oversight weiterhin funktioniert.

### Automation Bias ist ein Governance-Risiko

Ein Human Reviewer kann eine AI-Empfehlung übernehmen, weil sie präzise, selbstsicher oder technisch überlegen wirkt.

Mögliche Kontrollen:

- Evidenz und Unsicherheit vor der Empfehlung anzeigen,
- bei High-Impact-Fällen Begründungen verlangen,
- unabhängige Berechnungen oder Source Checks nutzen,
- Reviewer schulen und rotieren,
- akzeptierte und abgelehnte Entscheidungen stichprobenartig prüfen,
- Override-Muster messen,
- Interfaces vermeiden, die AI visuell als Autorität darstellen.

Human Oversight sollte Risiko reduzieren und Verantwortung nicht nur auf einen Operator verlagern, der keine reale Kontrolle besitzt.

## Monitoring muss mit Aktionen verbunden sein

Ein Dashboard ist keine Governance, wenn Signale keine definierten Reaktionen auslösen.

Jeder überwachte Indikator benötigt:

- Owner,
- Schwellenwert oder Entscheidungsregel,
- Severity,
- Reaktionszeit,
- Eskalationsweg,
- Remediation-Pfad,
- Evidenz und Abschluss.

Sinnvolle Governance-Metriken:

| Bereich | Beispielindikatoren |
| --- | --- |
| **Inventar** | aktive Use Cases, nicht klassifizierte Use Cases, abgelaufene Freigaben |
| **Daten** | Qualitätsverletzungen, veraltete Quellen, ungelöste Lineage-Lücken |
| **Modelle** | nicht unterstützte Versionen, Evaluationsregression, Provider-Änderungen |
| **Security** | blockierte Zugriffe, verdächtige Tool Calls, Secret- oder Datenabfluss |
| **Privacy** | PII-Funde, Retention-Ausnahmen, fehlgeschlagene Löschungen |
| **Human Oversight** | Review-Rate, Override-Rate, Eskalationsrate, Reviewer-Übereinstimmung |
| **Operations** | Incidents, Rollback-Häufigkeit, Verfügbarkeit, Latenz und Kosten |
| **Change** | nicht freigegebene Änderungen, fehlgeschlagene Gates, überfällige Re-Assessments |
| **Outcomes** | Korrekturrate, schädliche Ergebnisse, Prozesswert, Vertrauenssignale |

Eine niedrige Override-Rate ist nicht automatisch positiv. Sie kann ein gutes System, schwache Prüfung oder Automation Bias bedeuten. Metriken benötigen Interpretation und Kontext.

## Ausnahmen benötigen Ownership und Ablaufdatum

Organisationen werden gelegentlich temporäre Risiken akzeptieren:

- eine Legacy-Integration liefert noch keine vollständige Lineage,
- ein Modellupgrade ist verzögert,
- eine manuelle Kontrolle ersetzt vorübergehend Automatisierung,
- ein Pilot nutzt eine eingeschränkte Nutzergruppe,
- während einer Migration besteht eine Monitoring-Lücke.

Eine Ausnahme sollte enthalten:

- Scope,
- Grund,
- Risiko,
- kompensierende Kontrolle,
- verantwortlichen Owner,
- Approver,
- Start- und Ablaufdatum,
- Remediation-Plan,
- Review-Status.

Dauerhafte undokumentierte Ausnahmen sind Policy-Fehler.

## Ein pragmatischer Implementierungspfad

Eine Organisation benötigt keine große AI-Governance-Plattform, bevor sie Kontrolle aufbauen kann.

Ein sinnvolles Minimum:

```flow linear vertical
Ein AI-Use-Case-Inventar aufbauen
Verantwortliche Owner benennen
Interne Risikoklassifikation definieren
Freigegebene Modelle und verbotene Nutzungen veröffentlichen
Datenklassifikation und Access Rules verbinden
Lifecycle Gates einführen
Vollständige Release-Konfiguration versionieren
Evaluationsnachweise verlangen
Monitoring und Incident Handling aktivieren
Use Cases überprüfen und stilllegen
```

### Minimale Inventarfelder

Starten mit:

- Use-Case-ID und Titel,
- Geschäftszweck,
- Business Owner,
- Product / Technical Owner,
- Status,
- Nutzergruppe,
- Modell und Provider,
- Datenklassen,
- Autonomiegrad,
- Risikoklasse,
- Freigabeprotokoll,
- letzte Evaluation,
- nächstes Review,
- Produktivendpunkt,
- Stilllegungsstatus.

### Mit wenigen durchsetzbaren Policies beginnen

Beispiele:

1. Vertrauliche oder personenbezogene Daten dürfen nur über freigegebene Enterprise Services verarbeitet werden.
2. Jeder produktive AI Use Case benötigt einen Business Owner.
3. High-Impact-Aktionen benötigen eine explizite menschliche Autorisierung, sofern sie nicht separat freigegeben wurden.
4. Produktive Prompts, Agenten, Tools und Policies werden versioniert.
5. Jedes Release verweist auf reproduzierbare Evaluationsnachweise.
6. Wesentliche Änderungen benötigen Impact Assessment und erneute Freigabe.
7. Jeder produktive Use Case besitzt Monitoring, Incident Ownership und Stilllegungsprozess.

Eine kurze technisch und operativ durchgesetzte Policy ist nützlicher als ein umfangreiches Prinzipienpapier ohne Wirkung auf reale Systeme.

## Häufige Fehlmuster

### Nur Procurement kontrollieren

Die Provider-Prüfung ist erforderlich, kontrolliert aber nicht Use Case, Daten, Prompt, Agent, Tools oder Business Outcome.

### Nur das Modell kontrollieren

Das Systemverhalten kann sich ändern, obwohl das Modell unverändert bleibt.

### Jedes freigegebene Modell für jeden Zweck zulassen

Freigaben müssen nach Datenklasse, Use Case, Nutzerkreis, Region und Automatisierungsgrad begrenzt sein.

### PII Detection mit vollständiger Privacy Governance verwechseln

Privacy umfasst außerdem Zweck, Minimierung, Zugriff, Retention, Rechte, Provider-Bedingungen und Löschung.

### Human Oversight als Disclaimer verwenden

Ein Reviewer ohne Evidenz, Zeit oder Befugnis ist keine wirksame Kontrolle.

### Standardmäßig alles loggen

Unbegrenzte Speicherung von Prompts und Traces kann ein neues Privacy- und Security-Risiko erzeugen.

### Einmal freigeben und vergessen

Modelle, Daten, Nutzer, Provider, Policies und Geschäftsprozesse verändern sich.

### Ein Inventar ohne operative Gates bauen

Ein Katalog, der Zugriff, Deployment, Monitoring oder Change nicht beeinflusst, bleibt Dokumentation statt Governance.

### Das AI Committee für alle Outcomes verantwortlich machen

Zentrale Governance sollte Richtlinien definieren, koordinieren und challengen. Die Verantwortung für Business Outcomes muss bei benannten operativen Ownern bleiben.

## Die zentrale Erkenntnis

AI Governance ist keine separate Insel und keine Bremse, die nachträglich auf Innovation gesetzt wird.

Sie ist das Betriebsmodell, das Folgendes verbindet:

- Nutzen mit Verantwortung,
- Daten mit zulässigem Zweck,
- Modelle mit Use Cases,
- Autonomie mit Kontrolle,
- Releases mit Evidenz,
- Betrieb mit Monitoring,
- Änderungen mit Neubewertung,
- Stilllegung mit kontrollierter Bereinigung.

> **AI wird kontrollierbar, wenn die Organisation den verantwortlichen Owner, den zulässigen Zweck, die vollständige Systemversion, die aktiven Kontrollen, die unterstützende Evidenz und den Weg zum Stoppen oder Ändern benennen kann.**

Das Ziel ist keine risikofreie AI. Kein komplexes technisches oder organisatorisches System kann dies garantieren.

Das Ziel ist AI, deren Risiken sichtbar, Entscheidungen explizit, Kontrollen angemessen und Outcomes verantwortbar bleiben.

## Die Serie im Überblick

```flow linear vertical
AI Foundations — How AI Works [done]
Language Models and Providers [done]
Enterprise Data, Embeddings and RAG [done]
AI Agents and Automation [done]
Known Problems and Failure Modes [done]
Evaluation, Costs and Operations [done]
AI Governance [active]
```

## Verwandte Playbooks

- [AI Foundations — How AI Works](/playbooks/ai-basics)
- [Unternehmenswissen für AI — Embeddings, Vektorsuche und RAG](/playbooks/ai-rag)
- [AI-Agenten und Automatisierung](/playbooks/ai-agents)
- [Bekannte Probleme und Fehlermuster](/playbooks/ai-failures)
- [AI evaluieren und betreiben](/playbooks/ai-eval)
- [Trash In, Trash Out](/playbooks/trash-iinout)
- [The Missing Pieces – Part 1: Data Quality](/playbooks/missing-pieces-data-quality)
- [Data Quality Governance](/playbooks/data-quality-governance)

## Quellen und weiterführende Dokumentation

- [NIST — AI Risk Management Framework](https://www.nist.gov/itl/ai-risk-management-framework)
- [NIST — Generative AI Profile](https://www.nist.gov/publications/artificial-intelligence-risk-management-framework-generative-artificial-intelligence)
- [NIST — AI RMF Playbook](https://airc.nist.gov/airmf-resources/playbook/)
- [Europäische Union — Verordnung (EU) 2024/1689, Artificial Intelligence Act](https://eur-lex.europa.eu/eli/reg/2024/1689/oj?locale=de)
- [OECD — AI Principles](https://oecd.ai/en/ai-principles)
- [ISO — ISO/IEC 42001 AI Management Systems](https://www.iso.org/standard/42001)
- [ISO — ISO/IEC 42005 AI System Impact Assessment](https://www.iso.org/standard/42005)

> **Hinweis:** Dieses Playbook beschreibt ein praktisches Governance-Betriebsmodell. Es ist keine Rechtsberatung und ersetzt keine use-case-spezifische Bewertung durch Legal, Privacy, Security, Risk und Compliance.
