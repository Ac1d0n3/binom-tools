---
title: Cloud vs. Self-Hosted Data Platforms — Kosten, Kontrolle, Governance und Business Fit
description: Eine umfassende Entscheidungshilfe für Cloud, Self-Hosted, souveräne Cloud und hybride Datenplattformen — mit TCO, Betriebsaufwand, Datenschutz, Governance, Performance und pragmatischen Mixed-Modellen.
category: Data Platforms
tags:
  - cloud
  - self-hosted
  - on-prem
  - hybrid-cloud
  - sovereign-cloud
  - data-platform
  - data-warehouse
  - total-cost-of-ownership
  - finops
  - data-governance
  - data-protection
  - gdpr
order: -1
author: Thomas Lindackers
hero: images/stories/host-vs-cloud-hero.png
---

## Cloud oder Self-Hosted ist oft die falsche erste Frage

Die Diskussion über moderne Datenplattformen beginnt häufig mit einer scheinbar einfachen Alternative:

*„Gehen wir in die Cloud — oder betreiben wir die Plattform weiterhin selbst?“*

Diese Frage ist verständlich, greift aber zu kurz. Eine Datenplattform ist kein einzelner Server und auch kein einzelner Cloud-Service. Sie besteht aus Quellsystemen, Datenaufnahme, Replikation, Speicher, Transformation, Datenmodellen, Governance, Security, Reporting, Monitoring, Backup, Support und den Menschen, die fachliche Bedeutung aus Daten erzeugen.

Die sinnvollere Ausgangsfrage lautet deshalb:

> **Welches Betriebsmodell liefert für einen konkreten Workload den größten geschäftlichen Mehrwert — bei vertretbaren Kosten, Risiken und Betriebsaufwänden?**

Cloud und Self-Hosted sind keine Ideologien. Sie sind Betriebsmodelle mit unterschiedlichen Stärken, Verantwortlichkeiten und Kostenprofilen.

Eine Cloud kann Bereitstellung beschleunigen, elastische Rechenleistung liefern und den Betrieb technischer Basiskomponenten reduzieren. Eine selbst betriebene Plattform kann bei stabilen Workloads, vorhandener Infrastruktur und lokalem Datenbedarf sehr kontrollierbar und wirtschaftlich sein. Eine europäische oder souveräne Cloud kann zwischen diesen Polen liegen. Und für viele Unternehmen ist ein bewusst gestaltetes Hybridmodell die realistischste Zielarchitektur.

Diese Story betrachtet deshalb nicht nur **Cloud gegen On-Prem**, sondern das gesamte Spektrum von selbst betriebenen Plattformen bis zu globalen Hyperscalern — einschließlich Kosten, Mehrwert, Betriebsaufwand, Datenschutz, Governance, Performance und sinnvoller Mischformen.

## Das Betriebsmodell ist ein Spektrum

Zwischen dem eigenen Rechenzentrum und einem globalen Hyperscaler liegen mehrere Betriebsmodelle. Sie unterscheiden sich vor allem darin, wie viel Infrastrukturkontrolle, operative Verantwortung, Skalierbarkeit und Managed Services ein Unternehmen benötigt beziehungsweise abgeben möchte.

<figure class="playbook-prose__figure">
    <img
        src="images/stories/host-vs-cloud-img1-de.png"
        alt="Betriebsmodell-Spektrum für Datenplattformen von selbst betriebenen Umgebungen über Colocation, Private Cloud und souveräne Cloud bis zu Public Cloud und globalen Hyperscalern"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Mehr Kontrolle bedeutet meist auch mehr eigene Verantwortung. Mehr Managed Services erhöhen häufig Agilität und Skalierbarkeit, verändern aber Abhängigkeiten, Kostenmodelle und Governance-Anforderungen.
    </figcaption>
</figure>

### 1. Self-Hosted / On-Premises

Die Infrastruktur läuft im eigenen Rechenzentrum oder an einem direkt kontrollierten Standort. Hardware, Virtualisierung, Betriebssysteme, Datenbanken, Netzwerk, Backup und Betrieb liegen überwiegend beim Unternehmen.

Typische Vorteile:

- hohe technische und organisatorische Kontrolle
- direkte Integration in lokale Netze und Quellsysteme
- planbare Kapazität bei stabilen Workloads
- freie Wahl von Wartungsfenstern, Software und Betriebsprozessen
- vorhandene Infrastruktur und Kompetenzen können weitergenutzt werden

Typische Herausforderungen:

- Hardware-Lifecycle und Kapazitätsplanung
- Patching, Hochverfügbarkeit und Disaster Recovery
- eigene Betriebs- und Security-Kompetenz
- langsamere Bereitstellung bei neuen Kapazitäten
- begrenzte Elastizität ohne zusätzliche Infrastruktur

### 2. Colocation

Eigene Hardware steht in einem professionellen Rechenzentrum eines Dienstleisters. Strom, Kühlung, physische Sicherheit und Konnektivität werden teilweise ausgelagert, die Plattform selbst bleibt jedoch überwiegend in eigener Verantwortung.

Colocation kann sinnvoll sein, wenn ein Unternehmen die physische Rechenzentrumsinfrastruktur nicht selbst betreiben möchte, aber Hardware, Netzwerkdesign und Plattformstack weiterhin kontrollieren will.

### 3. Private Cloud

Eine dedizierte, stärker automatisierte Infrastruktur wird für eine Organisation betrieben — intern oder durch einen Dienstleister. Self-Service, APIs, Infrastructure as Code und standardisierte Bereitstellung können cloudähnlich sein, obwohl die Infrastruktur exklusiver oder stärker kontrolliert bleibt.

Private Cloud reduziert nicht automatisch den Betriebsaufwand. Sie kann sogar zusätzliche Plattformkompetenz erfordern, wenn das Unternehmen die Automatisierungs- und Orchestrierungsschicht selbst verantwortet.

### 4. Europäische oder souveräne Cloud

Europäische und souveräne Cloud-Angebote versuchen, Cloud-Komfort mit stärkeren Anforderungen an Datenresidenz, Jurisdiktion, operative Kontrolle und digitale Souveränität zu verbinden.

Dabei ist wichtig: **„europäisch“, „souverän“ und „Daten in Deutschland“ sind keine vollständig austauschbaren Begriffe.** Die konkrete Bewertung muss immer auf Ebene des Dienstes, der Region, des Vertrags, der Unterauftragnehmer, der Supportzugriffe und der technischen Datenflüsse erfolgen.

### 5. Public Cloud

Public-Cloud-Plattformen stellen gemeinsam genutzte, logisch isolierte Infrastruktur und zahlreiche Managed Services bereit. Kapazitäten können schnell bereitgestellt, skaliert und wieder reduziert werden.

Die stärksten Vorteile entstehen meist dort, wo ein Unternehmen tatsächlich von Elastizität, globaler Verfügbarkeit, Managed Services oder schneller Innovation profitiert.

### 6. Globaler Hyperscaler

Globale Hyperscaler bieten die größte Servicebreite, weltweite Regionen, umfangreiche Automatisierung und sehr hohe Skalierbarkeit. Gleichzeitig steigen potenziell die Komplexität der Serviceauswahl, die Abhängigkeit von proprietären Diensten und die Anforderungen an Kosten-, Identitäts- und Daten-Governance.

## Für ein Unternehmen, das überwiegend in Deutschland arbeitet

Ein rein oder überwiegend deutsches Geschäftsmodell ist **kein automatisches Argument gegen die Cloud**. Es verändert aber die Gewichtung einiger Vorteile.

Wenn ein Unternehmen:

- seine wichtigsten Quellsysteme in Deutschland oder im eigenen Netzwerk betreibt
- überwiegend interne Nutzer in Deutschland versorgt
- stabile Datenmengen und planbare Ladefenster hat
- keine weltweite Skalierung benötigt
- bereits Infrastruktur-, Datenbank- oder BI-Kompetenz besitzt
- keine große Zahl spezieller Managed Services benötigt

ist der zusätzliche Mehrwert einer global verteilten Public Cloud möglicherweise geringer als bei einem internationalen Digitalgeschäft.

Gleichzeitig kann eine Cloud weiterhin sinnvoll sein, wenn:

- ein kleines Team den Infrastruktur- und Datenbankbetrieb nicht selbst leisten kann
- neue Umgebungen schnell bereitgestellt werden müssen
- die Plattform stark wächst oder Lasten deutlich schwanken
- AI-, ML-, Streaming- oder Spezialservices genutzt werden sollen
- Hochverfügbarkeit und Disaster Recovery intern nur mit hohem Aufwand erreichbar wären
- europäische oder deutsche Cloud-Regionen die Anforderungen ausreichend erfüllen

Die geografische Reichweite des Unternehmens ist deshalb nur **ein Faktor**. Entscheidend sind Datenlage, Workloads, vorhandene Skills, Servicebedarf und langfristiges Betriebsmodell.

## Die realen Kosten einer Datenplattform

Kostenvergleiche zwischen Cloud und Self-Hosted sind häufig unvollständig.

Ein typischer Fehler lautet:

`Cloud-Rechnung ↔ Serverkauf`

Das ist kein TCO-Vergleich.

Eine belastbare Betrachtung muss mindestens folgende Kategorien einbeziehen:

```text
Infrastruktur
+ Software und Lizenzen
+ Datenverarbeitung
+ Speicher
+ Netzwerk und Datentransfer
+ Hochverfügbarkeit
+ Backup und Disaster Recovery
+ Monitoring und Security
+ Support
+ Personal und Betrieb
+ Migration und Parallelbetrieb
+ Kostenmanagement
+ Lifecycle und Exit
```

Außerdem existieren Aufgaben, die in **beiden** Modellen notwendig bleiben:

- Data Engineering
- Datenmodellierung
- Geschäftslogik
- Datenqualität
- Data Governance
- Metadaten und Lineage
- BI- und Analytics-Entwicklung
- Business Ownership
- Dokumentation und Enablement

<figure class="playbook-prose__figure">
    <img
        src="images/stories/host-vs-cloud-img2-de.png"
        alt="Illustrativer TCO-Vergleich einer selbst betriebenen und einer Cloud-Datenplattform mit Kostenkategorien für Infrastruktur, Services, Betrieb und Personal"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die dargestellten Werte sind grobe, gerundete Planungsschätzungen für ein beispielhaftes mittelständisches Unternehmensprofil. Sie sind keine Marktpreise, Angebote oder allgemein gültigen Benchmarks.
    </figcaption>
</figure>

> **Wichtiger Kostenhinweis:** Die Zahlen im Schaubild sind ausschließlich als Größenordnung und Diskussionsgrundlage zu verstehen. Das Beispiel nimmt ungefähr 500–1.500 Mitarbeitende, 100–500 BI-Nutzer, 5–20 TB analytische Daten, tägliche oder stündliche Ladeprozesse, moderates Wachstum und hohe Verfügbarkeitsanforderungen an. Reale Kosten hängen unter anderem von vorhandener Infrastruktur, Abschreibungen, Vertragskonditionen, Region, Architektur, Datenvolumen, Abfrageverhalten, Verfügbarkeitsmodell, Rabatten, Personalzuordnung und Lizenzbestand ab. Einmalige Migration, Transformation, Parallelbetrieb und organisatorische Veränderung können zusätzlich erheblich sein.

Die Bandbreiten überlappen bewusst. Genau das ist die Kernaussage: **Weder Cloud noch Self-Hosted ist grundsätzlich günstiger.** Eine gute Architektur kann in beiden Modellen wirtschaftlich sein. Eine schlecht gesteuerte Plattform kann in beiden Modellen teuer werden.

### Self-Hosted-Kosten, die häufig unterschätzt werden

- Hardware, Storage und Netzwerkkomponenten
- Virtualisierung und Betriebssysteme
- Datenbank-, ETL-, Monitoring- und Security-Lizenzen
- Rackspace, Strom, Kühlung und Konnektivität
- Ersatzhardware und Hardwareerneuerung
- Backup-Infrastruktur und zweiter Standort
- Monitoring, Patching und Schwachstellenmanagement
- Rufbereitschaft, Support und Eskalation
- Kapazitätsreserven für Wachstum und Spitzenlast
- interne Personalanteile, die nicht direkt der Plattform zugerechnet werden

Bestehende Infrastruktur kann die Grenzkosten stark reduzieren. Sie ist aber nicht automatisch kostenlos. Abschreibung, Betrieb, Erneuerung und gebundene Kapazität gehören weiterhin zur wirtschaftlichen Betrachtung.

### Cloud-Kosten, die häufig unterschätzt werden

- dauerhaft laufende Compute-Ressourcen
- getrennte Entwicklungs-, Test- und Produktionsumgebungen
- Storage für Hot-, Cool- und Archive-Tiers
- Datenverarbeitung, Warehouses, Pipelines und Streaming
- Managed Datenbanken, Kataloge und Orchestrierung
- Logging, Metriken, Tracing und Auditdaten
- Backups, Snapshots und Cross-Region-Replikation
- Netzwerk, Egress und Inter-Region-Traffic
- Premium Support und höhere SLA-Stufen
- Security-Services, Schlüsselverwaltung und Threat Detection
- nicht gelöschte Testressourcen und überdimensionierte Instanzen
- FinOps, Kostenallokation und laufende Optimierung

Cloud-Kosten sind häufig variabler und granularer. Das ist ein Vorteil, wenn Ressourcen tatsächlich bedarfsgerecht skaliert werden. Es wird zum Risiko, wenn Nutzung, Ownership und Budgets nicht transparent sind.

### CAPEX gegen OPEX ist nicht die ganze Entscheidung

Self-Hosted wird häufig mit CAPEX, Cloud mit OPEX gleichgesetzt. Das ist als erste Orientierung hilfreich, aber zu einfach.

Auch Self-Hosted enthält laufende OPEX-Kosten für Personal, Strom, Lizenzen, Support und Betrieb. Cloud kann wiederum längerfristige Commitments, Reservierungen oder Mindestabnahmen enthalten. Für die Entscheidung ist nicht nur die Bilanzkategorie relevant, sondern:

- Wie hoch sind die Gesamtkosten über drei bis fünf Jahre?
- Wie stark schwanken Nutzung und Datenvolumen?
- Welche Investitionen existieren bereits?
- Welche Kapazität bleibt ungenutzt?
- Welche Betriebsrisiken werden tatsächlich übertragen?
- Welche neuen Kompetenzen werden benötigt?
- Welchen Wert erzeugt schnellere Bereitstellung?

### Ein sinnvolles TCO-Modell

Ein pragmatisches Modell kann so aufgebaut werden:

```text
TCO über 3–5 Jahre
=
Plattformkosten
+ Personalkosten
+ Lizenz- und Supportkosten
+ Netzwerk und Datentransfer
+ Security und Compliance
+ Hochverfügbarkeit und Recovery
+ Migration und Parallelbetrieb
+ Schulung und organisatorische Veränderung
+ Exit- und Portabilitätskosten
− wiederverwendbare Investitionen
− vermiedener Betriebsaufwand
```

Die Berechnung sollte mindestens drei Szenarien enthalten:

1. **Base Case** — erwartete Datenmenge und normale Nutzung
2. **Growth Case** — höheres Datenvolumen, mehr Nutzer und zusätzliche Workloads
3. **Stress Case** — Spitzenlast, erhöhte Verfügbarkeit, größere Replikation oder unerwarteter Datenverkehr

Für Cloud-Szenarien sollten aktuelle Anbieterrechner und tatsächliche Vertragskonditionen verwendet werden. Für Self-Hosted müssen interne Vollkosten, Abschreibungen, Betriebsanteile und Erneuerungszyklen einbezogen werden.

## Mehrwert gegen operative Verantwortung

Managed Services sind nicht nur ein Kostenblock. Sie können echten Mehrwert erzeugen, wenn sie undifferenzierte Betriebsarbeit reduzieren.

Beispiele:

- Hardware muss nicht beschafft und betrieben werden
- Basissysteme können schneller bereitgestellt werden
- Datenbanken können Patching, Replikation oder Failover teilweise als Service anbieten
- elastische Ressourcen können kurzfristig skaliert werden
- neue Services sind oft ohne eigene Plattforminstallation verfügbar
- standardisierte APIs und Automatisierung können Time-to-Value reduzieren

Die operative Verantwortung verschwindet aber nicht vollständig. Sie verschiebt sich.

<figure class="playbook-prose__figure">
    <img
        src="images/stories/host-vs-cloud-img3-de.png"
        alt="Vergleich von Nutzen und operativer Verantwortung zwischen selbst betriebenen Plattformen und Cloud-Plattformen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Cloud kann Verantwortung für Infrastruktur und technische Plattformdienste verlagern. Verantwortung für Daten, Geschäftslogik, Governance, Zugriff, Compliance und Business Value bleibt beim Unternehmen.
    </figcaption>
</figure>

### Was ein Provider übernehmen kann

Abhängig von IaaS, PaaS oder SaaS kann ein Provider unter anderem übernehmen:

- physische Rechenzentren
- Hardware, Strom und Kühlung
- Netzwerk-Backbone
- Virtualisierung
- Basispattform und Teile des Betriebssystems
- Betrieb verwalteter Datenbanken
- automatische Skalierung
- technische Replikation und Snapshots
- Teile von Patching und Hochverfügbarkeit

### Was beim Unternehmen bleibt

Unabhängig vom Hosting-Modell bleiben typischerweise beim Unternehmen:

- Auswahl und Klassifikation der Daten
- Data Ownership und fachliche Verantwortlichkeit
- Datenmodelle und Geschäftslogik
- KPI-Definitionen und semantische Modelle
- Datenqualität und fachliche Kontrollen
- Rollen, Berechtigungen und Freigabeprozesse
- Aufbewahrung und Löschung
- Datenschutzbewertung
- Konfiguration der Cloud-Ressourcen
- Identitäten, Secrets und technische Benutzer
- Kostenverantwortung und Budgetsteuerung
- Auditierbarkeit und Nachweisführung
- Business Value und Nutzungserfolg

Das Shared-Responsibility-Modell ist deshalb keine Entlastung von Governance. Es ist eine **Neuverteilung technischer Aufgaben**.

> **Managed Services reduzieren vor allem undifferenzierte Betriebsarbeit. Sie ersetzen weder Datenverantwortung noch fachliche Kompetenz.**

### Time-to-Value und Opportunitätskosten

Ein reiner Infrastrukturvergleich kann den geschäftlichen Nutzen übersehen.

Wenn eine Cloud-Plattform ein Projekt sechs Monate früher ermöglicht, kann dieser Zeitgewinn einen höheren Infrastrukturpreis rechtfertigen. Wenn eine vorhandene Self-Hosted-Plattform einen stabilen Reporting-Workload ohne neue Investitionen abdeckt, kann eine Migration dagegen viel Aufwand erzeugen, ohne zusätzlichen Business Value zu liefern.

Deshalb sollten zusätzlich betrachtet werden:

- Zeit bis zur ersten produktiven Nutzung
- Aufwand für neue Umgebungen
- Verfügbarkeit benötigter Skills
- Geschwindigkeit von Experimenten
- Zeitaufwand für Patching und Plattformpflege
- Auswirkungen auf Produktivität und Innovationsfähigkeit
- Risiko, dass ein Projekt durch Plattformkomplexität verzögert wird

## Datenschutz: mehr als der Standort des Rechenzentrums

Datenschutzdiskussionen werden häufig auf eine Frage reduziert:

*„Stehen die Server in Deutschland?“*

Der Standort ist wichtig, aber nicht ausreichend.

Für personenbezogene, vertrauliche oder regulierte Daten sollten mindestens folgende Aspekte geprüft werden:

| Governance- und Datenschutzaspekt | Self-Hosted | Europäische / souveräne Cloud | Globale Public Cloud |
| --- | --- | --- | --- |
| **Physische Datenkontrolle** | direkt steuerbar | vertraglich und regional definiert | region- und dienstabhängig |
| **Datenresidenz** | intern festlegbar | häufig auf EU/EWR oder ausgewählte Regionen begrenzbar | konkrete Region und Service prüfen |
| **Verarbeitungsort** | intern kontrollierbar | Dienstbeschreibung und Datenflüsse prüfen | nicht automatisch identisch mit Speicherort |
| **Backup-Standort** | selbst festgelegt | Region, Geo-Redundanz und Restore-Pfade prüfen | dienst- und konfigurationsabhängig |
| **Metadaten und Telemetrie** | intern steuerbar | separat prüfen | können andere Datenflüsse als Nutzdaten haben |
| **Administrativer Zugriff** | eigene Organisation | Betreiber-, Support- und Notfallzugriffe prüfen | Shared Responsibility und Supportmodell prüfen |
| **Unterauftragnehmer** | meist überschaubar | Vertragsunterlagen prüfen | häufig größere Lieferkette |
| **Verschlüsselung** | selbst konfiguriert | meist als Plattformfunktion verfügbar | meist umfangreich, aber korrekt zu konfigurieren |
| **Schlüsselkontrolle** | vollständig möglich | BYOK/HYOK und Schlüsselstandort prüfen | dienstabhängig |
| **Löschung und Retention** | selbst implementiert | Dienstfunktion und Backup-Lifecycle prüfen | dienstspezifisch konfigurieren |
| **Auditierbarkeit** | eigene Logs und Kontrollen | Testate, Reports und eigene Logs kombinieren | Provider-Nachweise plus eigene Kontrollen |
| **Exit und Portabilität** | technischer Stack entscheidet | Exportformate, APIs und Kündigungsprozess prüfen | Egress, proprietäre Services und Migrationsaufwand prüfen |

Diese Tabelle ist keine juristische Bewertung. Die konkrete Zulässigkeit hängt vom Anwendungsfall, den verarbeiteten Daten, Rollen, Verträgen und technischen Maßnahmen ab.

### Zentrale Prüfbereiche

```text
Datenstandort
Verarbeitungsstandort
Backup- und Recovery-Standort
Metadaten und Telemetrie
Administrativer Zugriff
Support- und Notfallzugriffe
Unterauftragnehmer
Auftragsverarbeitung
Drittlandtransfers
Verschlüsselung
Schlüsselverantwortung
Identity & Access Management
Audit Logging
Aufbewahrung
Löschung
Portabilität
Exit-Strategie
```

Die DSGVO bleibt auch bei Cloud-Nutzung Verantwortung des Unternehmens. Bei Auftragsverarbeitung sind unter anderem geeignete vertragliche Regelungen und Kontrollen relevant. Bei Übermittlungen in Drittländer gelten zusätzliche Anforderungen. Abhängig vom Risiko kann außerdem eine Datenschutz-Folgenabschätzung erforderlich sein.

### Cloud ist nicht automatisch unsicher — On-Prem nicht automatisch sicher

Eine professionell betriebene Cloud kann umfangreiche Security-Funktionen, Zertifizierungen, physische Schutzmaßnahmen und standardisierte Kontrollen bereitstellen. Gleichzeitig können Fehlkonfigurationen, zu breite Berechtigungen, unkontrollierte Datenkopien oder unklare Verantwortlichkeiten erhebliche Risiken erzeugen.

Ein lokaler Server ist wiederum nicht automatisch datenschutzkonform. Fehlende Patches, ungetestete Backups, gemeinsame Administratorkonten, mangelnde Segmentierung oder fehlendes Logging können ein hohes Risiko darstellen.

Die relevante Frage lautet:

> **Welches Modell ermöglicht für diesen Workload nachweisbar angemessene technische und organisatorische Maßnahmen?**

### BSI C5 als Orientierung

Der Cloud Computing Compliance Criteria Catalogue des BSI beschreibt Mindestanforderungen und Transparenzkriterien für sicheres Cloud Computing. Ein C5-Testat kann bei der Bewertung eines Cloud-Anbieters hilfreich sein, ersetzt aber nicht die eigene Risikoanalyse, die Prüfung des konkreten Services oder die Verantwortung für die eigene Konfiguration.

## Europäische, deutsche und souveräne Cloud-Optionen

„Cloud“ bedeutet nicht automatisch „globale US-Public-Cloud ohne regionale Kontrolle“.

Neben globalen Hyperscalern existieren europäische Anbieter und souveräne Betriebsmodelle. Beispiele sind:

| Beispiel | Regionale Einordnung | Möglicher Einsatz | Weiterhin zu prüfen |
| --- | --- | --- | --- |
| **STACKIT** | Rechenzentren in Deutschland und Österreich | IaaS, Plattformdienste, souveränitätsorientierte Workloads | konkreter Dienst, Region, Zertifizierungen, Support- und Unterauftragnehmermodell |
| **IONOS Cloud** | unter anderem Standorte in Frankfurt und Berlin | virtuelle Rechenzentren, Compute, Storage, Plattformdienste | ausgewählter Standort, Serviceumfang, Vertrags- und Betriebsmodell |
| **T Cloud Public** | EU-DE in Deutschland, EU-NL in den Niederlanden | europäische Public-Cloud-Infrastruktur und Managed Services | Region, konkrete Servicebeschreibung, Datenflüsse und Betriebsverantwortung |
| **Hyperscaler mit EU-Regionen oder souveränen Angeboten** | mehrere europäische Regionen; teilweise zusätzliche Sovereignty-Angebote | breite Managed-Service-Portfolios, globale Skalierung, AI und Spezialdienste | servicegenaue Datenresidenz, Metadaten, Supportzugriffe, Ausnahmen, Vertrags- und Exit-Modell |

Die Tabelle ist weder Ranking noch Produktempfehlung. Anbieterportfolios, Regionen, Zertifizierungen und Vertragsbedingungen ändern sich. Deshalb sollte jede Entscheidung anhand aktueller offizieller Dokumentation und konkreter Verträge geprüft werden.

Ein Beispiel für die notwendige Detailtiefe ist Microsofts EU Data Boundary: Sie beschreibt eine geografische Zusage für bestimmte Enterprise-Onlinedienste, dokumentiert aber auch fortbestehende beziehungsweise serviceabhängige Datenübertragungen und Ausnahmen. Die Aussage „EU Data Boundary“ ersetzt deshalb nicht die Prüfung des konkreten Dienstes und der verwendeten Funktionen.

Auch globale Anbieter erweitern ihre Sovereignty-Angebote. AWS hat 2026 eine eigenständige European Sovereign Cloud allgemein verfügbar gemacht. Solche Angebote erweitern die Auswahl, müssen aber weiterhin hinsichtlich Serviceumfang, Betriebsmodell, Datenflüssen, Kosten und Portabilität bewertet werden.

## Governance bleibt unabhängig vom Hosting-Modell

Eine Datenplattform benötigt dieselben grundlegenden Governance-Fähigkeiten — unabhängig davon, wo sie läuft.

### Daten-Governance

- Data Owner und Data Stewards
- Fachbegriffe und Business Glossary
- Datenklassifikation
- PII- und Sensitivitätskennzeichnung
- Qualitätsregeln
- Lineage und Herkunft
- Zertifizierung vertrauenswürdiger Datenprodukte
- Verantwortlichkeiten für Kennzahlen

### Zugriffs- und Security-Governance

- Rollen- und Berechtigungsmodelle
- Least Privilege
- Trennung von Administration und Nutzung
- technische Benutzer und Secrets
- privilegierte Zugriffe
- regelmäßige Rezertifizierung
- Maskierung und Row-/Column-Level Security
- Audit Logs und Nachvollziehbarkeit

### Lifecycle-Governance

- Aufbewahrungsfristen
- Löschregeln
- Archivierung
- Legal Holds
- Backup-Lifecycle
- Stilllegung nicht mehr benötigter Datenprodukte

### Kosten-Governance

In der Cloud wird Kosten-Governance zu einer zusätzlichen Plattformdisziplin:

- Kostenstellen und Produktverantwortung
- Accounts, Subscriptions, Projekte und Tenants
- Tags und Labels
- Budgets und Warnschwellen
- Showback oder Chargeback
- Kosten pro Datenprodukt, Team oder Umgebung
- Erkennung ungenutzter Ressourcen
- Rightsizing und Nutzungsoptimierung
- Commitments und Reservierungen
- Kosten des Datentransfers

FinOps ist dabei nicht nur Rechnungsprüfung. Es verbindet Nutzung, Kosten, Ownership und Business Value. Diese Denkweise ist zunehmend auch für hybride Landschaften, Rechenzentren, Lizenzen und SaaS relevant.

## Performance: Daten und Rechenleistung sollten zusammenpassen

Die Hosting-Entscheidung beeinflusst Performance nicht allein. Entscheidend ist, wo Daten liegen, wo sie verarbeitet werden und wie häufig große Datenmengen über Standort- oder Cloud-Grenzen bewegt werden.

Eine ungünstige Hybridarchitektur kann beispielsweise so aussehen:

```text
Lokale Quellsysteme
        ↓
Cloud Warehouse
        ↓
Lokales BI mit vielen Live-Abfragen
        ↓
ständiger Netzwerkverkehr und hohe Latenz
```

Eine robustere Architektur platziert analytische Verarbeitung näher an den Daten und überträgt gezielt:

```text
Quellsysteme
        ↓
inkrementelle Replikation / CDC
        ↓
analytischer Speicher nahe der Compute-Schicht
        ↓
kuratierte Modelle
        ↓
Reporting und Datenprodukte
```

### Muss für Performance alles gespiegelt werden?

Nicht zwingend vollständig und nicht zwingend in Echtzeit.

Typische Muster sind:

| Anforderung | Sinnvolles Muster |
| --- | --- |
| tägliches oder stündliches BI | Batch oder inkrementelle Replikation |
| operative Analytics mit geringer Latenz | CDC oder Micro-Batches |
| stark verteilte Nutzer | regionale Caches, Replikate oder bereitgestellte Datenprodukte |
| AI/ML mit temporär hoher Rechenlast | kuratierte Daten gezielt in eine elastische Compute-Umgebung bringen |
| lokale Kernsysteme und Cloud-Spezialservice | minimale, klassifizierte Datensätze übertragen; Ergebnisse zurückführen |

Die wichtigste Regel lautet:

> **Nicht jede Abfrage sollte Standortgrenzen überqueren. Datenbewegung muss bewusst entworfen, gemessen und gesteuert werden.**

Bei Cloud-Plattformen ist zusätzlich zu beachten, dass Datentransfer Kosten verursachen kann. Bei Self-Hosted beeinflussen WAN-Kapazität, Latenz und Netzwerkbetrieb die Wirtschaftlichkeit. In beiden Modellen sollten Datenbewegungen Teil der Architektur- und Kosten-Governance sein.

## Eine pragmatische hybride Datenplattform

Hybrid sollte nicht bedeuten, dass Komponenten zufällig über mehrere Umgebungen verteilt werden.

Ein gutes Mixed-Modell platziert jeden Workload dort, wo er den größten Wert erzeugt — unter einer gemeinsamen Governance.

<figure class="playbook-prose__figure">
    <img
        src="images/stories/host-vs-cloud-img4-de.png"
        alt="Pragmatische hybride Datenplattform mit selbst betriebener oder souveräner Kernplattform, optionalen Cloud-Services und zentraler Governance"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Kern- und sensible Daten können in einer kontrollierten Plattform verbleiben, während Cloud-Services gezielt für Elastizität, AI, Experimente oder Spezialfunktionen genutzt werden. Governance bleibt übergreifend.
    </figcaption>
</figure>

### Mögliche Mixed-Modelle

#### Modell A — kontrollierter Core, Cloud für AI

```text
SAP / ERP / CRM
        ↓
Self-Hosted oder souveränes Core Warehouse
        ↓
kuratierte und klassifizierte Daten
        ↓
kontrolliertes AI Gateway
        ↓
Cloud AI Service
```

Geeignet, wenn Kern- und PII-Daten kontrolliert bleiben sollen, aber spezialisierte Modelle oder elastische AI-Infrastruktur genutzt werden.

Wichtige Kontrollen:

- Datenminimierung
- Anonymisierung oder Pseudonymisierung
- Prompt- und Response-Logging nach klaren Regeln
- Provider- und Modellfreigaben
- Schutz vor unkontrollierter Datenweitergabe
- zentrale Kosten- und Nutzungsüberwachung

#### Modell B — stabile Grundlast selbst betreiben, Lastspitzen in der Cloud

```text
stabile Warehouse- und BI-Last
        → Self-Hosted / Private / Sovereign Core

temporäre hohe Rechenlast
        → elastische Cloud Compute
```

Das kann sinnvoll sein, wenn der tägliche Betrieb gut planbar ist, einzelne Transformations-, Forecasting- oder ML-Workloads aber stark schwanken.

#### Modell C — produktive Kernplattform kontrolliert, Entwicklung flexibel

```text
Produktion
→ kontrollierte Kernplattform

Entwicklung und Tests
→ temporäre Cloud-Umgebungen
→ synthetische, maskierte oder anonymisierte Daten
```

Vorteil: schnelle Bereitstellung und isolierte Experimente.

Risiko: Testdaten und Konfigurationen dürfen nicht unkontrolliert produktive Informationen enthalten.

#### Modell D — europäische Cloud als Kern, lokale Integration

```text
lokale SAP-, ERP- und Produktionssysteme
        ↓
private Verbindung / kontrollierte Replikation
        ↓
europäische oder souveräne Cloud-Datenplattform
        ↓
BI, Analytics und Datenprodukte
```

Geeignet, wenn interne Betriebsressourcen begrenzt sind, aber europäische Datenresidenz und ein regionales Betriebsmodell wichtig sind.

#### Modell E — lokales Reporting, selektive Cloud-Datenprodukte

```text
lokales Warehouse und Reporting
        +
externe Markt-, Geo- oder SaaS-Daten aus der Cloud
        +
kuratierte Cloud-Ergebnisse
```

Hier sollte vermieden werden, dass jede Dashboardabfrage live auf viele externe Systeme zugreift. Replizierte oder kuratierte Datenprodukte sind meist robuster.

## Entscheidungsmatrix

Die folgende Matrix ist keine automatische Produktauswahl. Sie zeigt Tendenzen.

| Anforderung oder Ausgangslage | Self-Hosted / Private | Europäische / souveräne Cloud | Public Cloud / Hyperscaler | Hybrid |
| --- | --- | --- | --- | --- |
| stabile, langfristige Grundlast | stark | stark | möglich, Kosten optimieren | stark |
| vorhandene Infrastruktur und Betriebsteam | stark | möglich | Nutzen der Migration belegen | stark |
| sehr kleines Infrastrukturteam | anspruchsvoll | stark | stark | möglich |
| stark schwankende oder unvorhersehbare Last | begrenzt | stark | sehr stark | sehr stark |
| globale Nutzer und Regionen | aufwendig | abhängig vom Anbieter | sehr stark | stark |
| schnelle neue Umgebungen | mittel | stark | sehr stark | stark |
| umfangreiche AI-/ML-Managed-Services | begrenzt | wachsend | sehr stark | sehr stark |
| sensible lokale Kerndaten | stark | stark nach Prüfung | möglich nach Prüfung | sehr stark |
| maximale technische Kontrolle | sehr stark | stark | geringer | stark |
| geringe Anbieterabhängigkeit | stark bei offenen Standards | mittel | dienstabhängig | stark bei klaren Grenzen |
| einfache Kostenplanbarkeit | oft gut bei stabiler Last | mittel | benötigt aktive Steuerung | mittel |
| vorhandene Cloud-Kompetenz | nicht zwingend | wichtig | sehr wichtig | sehr wichtig |
| sehr schnelle Time-to-Value | abhängig von Bestand | stark | sehr stark | stark |

## Ein strukturierter Entscheidungsprozess

### Schritt 1 — Workloads statt Plattformen bewerten

Nicht die gesamte Landschaft pauschal klassifizieren. Einzelne Workloads können unterschiedliche Anforderungen haben:

- Core Warehouse
- operative Reports
- Self-Service BI
- AI/ML
- Streaming
- externe Datenfreigabe
- Entwicklungsumgebungen
- Archivierung
- Disaster Recovery

### Schritt 2 — Nicht verhandelbare Anforderungen definieren

Beispiele:

- Datenresidenz
- maximale Wiederanlaufzeit
- maximale Datenverlustzeit
- regulatorische Anforderungen
- erlaubte Provider und Regionen
- Verschlüsselungs- und Schlüsselmodell
- Anforderungen an Audit und Logging
- Verfügbarkeit interner Skills

### Schritt 3 — Ist-Kosten vollständig erfassen

Nicht nur Rechnungen betrachten. Erfassen:

- Infrastruktur und Abschreibung
- Lizenzen
- Personalanteile
- externe Dienstleister
- Support
- Rechenzentrum
- Netzwerk
- Backup und DR
- Security
- ungeplante Ausfälle und technische Schulden

### Schritt 4 — Zielarchitektur und TCO modellieren

Für jede Variante:

- Base-, Growth- und Stress-Szenario
- drei bis fünf Jahre
- einmalige Migration
- Parallelbetrieb
- Schulung
- neue Rollen und Kompetenzen
- Exit-Kosten
- erwartete Einsparungen
- erwarteter Business Value

### Schritt 5 — Performance mit echten Daten testen

Ein Proof of Concept sollte nicht nur eine Demoabfrage zeigen. Er sollte reale Datenverteilungen, typische Transformationen, gleichzeitige Nutzer, Ladefenster und Wiederherstellung berücksichtigen.

### Schritt 6 — Datenschutz und Governance vor der Migration klären

Nicht erst nach dem Go-live:

- Datenklassifikation
- Rollenmodell
- Auftragsverarbeitung
- Drittlandbewertung
- Logging
- Retention
- Löschung
- Kostenallokation
- Provider-Exit

### Schritt 7 — Portabilität realistisch bewerten

„Wir nutzen Container“ verhindert nicht automatisch Vendor Lock-in. Abhängigkeit kann entstehen durch:

- proprietäre SQL-Erweiterungen
- spezielle Datenformate
- Managed ETL- oder Orchestrierungsdienste
- proprietäre Identity-Modelle
- Event- und Messaging-Services
- AI-APIs
- Kataloge und Governance-Metadaten
- Betriebsprozesse und Team-Skills

Portabilität bedeutet nicht, jeden Service jederzeit austauschen zu können. Sie bedeutet, kritische Abhängigkeiten zu kennen und einen realistischen Exit-Pfad zu besitzen.

## Typische Fehlentscheidungen

### Cloud-Fehlentscheidungen

- Migration ohne klaren Business Case
- Lift-and-Shift ohne Anpassung der Architektur
- jede neue Funktion als Managed Service einkaufen
- fehlende Tags, Budgets und Kostenverantwortung
- keine Kontrolle über Entwicklungs- und Testressourcen
- Datentransfer und Logging-Kosten nicht modellieren
- Governance erst nach der Migration aufbauen
- EU-Region mit vollständiger rechtlicher und technischer Souveränität gleichsetzen
- keinen Exit-Plan definieren

### Self-Hosted-Fehlentscheidungen

- vorhandene Hardware als „kostenlos“ behandeln
- Personal- und Bereitschaftsaufwand ausblenden
- veraltete Plattformen nur wegen bestehender Skills behalten
- Hochverfügbarkeit annehmen, ohne sie zu testen
- Backups erstellen, aber Restores nicht prüfen
- Kapazität für Spitzenlast dauerhaft überdimensionieren
- manuelle Deployments als notwendige Folge von On-Prem akzeptieren
- Security und Governance allein mit dem physischen Standort begründen

### Hybrid-Fehlentscheidungen

- zufällige Verteilung ohne Workload-Prinzipien
- doppelte Datenhaltung ohne klare Quelle der Wahrheit
- Live-Abfragen über langsame oder teure Netzwerkgrenzen
- unterschiedliche Identitäten und Rollenmodelle ohne zentrale Governance
- mehrere Plattformen ohne ausreichende Betriebs- und Supportkompetenz
- keine einheitliche Kosten- und Metadaten-Sicht

## Eine pragmatische Empfehlung

Für viele mittelständische, überwiegend in Deutschland tätige Unternehmen kann folgende Denkweise sinnvoll sein:

1. **Stabile, geschäftskritische Grundlasten nicht ohne Business Case migrieren.**
2. **Vorhandene Infrastruktur und Skills als echten Wert berücksichtigen — aber nicht als kostenlos behandeln.**
3. **Cloud dort nutzen, wo Elastizität, Managed Services oder schnelle Bereitstellung messbaren Nutzen erzeugen.**
4. **Europäische und souveräne Angebote als eigenständige Option prüfen.**
5. **Sensible Daten nicht pauschal ausschließen, sondern nach Klassifikation, Dienst, Region und Kontrollen entscheiden.**
6. **Governance, Identity, Metadaten und Kostenverantwortung unabhängig vom Hosting-Modell zentral halten.**
7. **Daten und Compute möglichst nah beieinander platzieren.**
8. **Hybrid bewusst gestalten — nicht als ungeplante Ansammlung verschiedener Plattformen.**

## Fazit

Cloud ist kein Zielzustand. Self-Hosting ist kein Prinzip. Beide sind Werkzeuge für unterschiedliche Anforderungen.

Eine Cloud-Plattform kann operativen Aufwand reduzieren, Skalierung beschleunigen und Zugang zu spezialisierten Diensten schaffen. Eine selbst betriebene Plattform kann bei stabilen Workloads, vorhandener Infrastruktur und hoher Kontrollanforderung weiterhin sehr sinnvoll sein. Europäische und souveräne Clouds erweitern das Spektrum. Hybride Modelle können die Stärken kombinieren — wenn Datenbewegung, Governance und Verantwortung bewusst gestaltet werden.

Der wichtigste Unterschied liegt nicht darin, ob Daten „in der Cloud“ oder „im Keller“ liegen.

Er liegt darin:

- welche Aufgaben ein Provider übernimmt
- welche Verantwortung beim Unternehmen bleibt
- welche Kosten wirklich entstehen
- welcher Mehrwert dadurch schneller oder besser erreichbar wird
- wie Daten, Zugriffe und Lifecycle kontrolliert werden
- wie abhängig und wie portabel die Plattform langfristig ist

Die beste Entscheidung lautet deshalb selten pauschal **Cloud** oder **Self-Hosted**.

Sie lautet:

> **Den stabilen Core dort betreiben, wo er kontrollierbar und wirtschaftlich ist — und Cloud gezielt dort einsetzen, wo sie nachweisbar Mehrwert schafft.**

## Quellen und weiterführende Ressourcen

### Kosten und FinOps

- [FinOps Framework — FinOps Foundation](https://www.finops.org/framework/)
- [FinOps Framework 2026 — Überblick](https://www.finops.org/insights/2026-finops-framework/)
- [AWS Pricing Calculator](https://aws.amazon.com/calculator/)
- [Microsoft Azure Preisrechner](https://azure.microsoft.com/de-de/pricing/calculator/)
- [Google Cloud Pricing Calculator](https://cloud.google.com/products/calculator)
- [STACKIT Cloud-Preise und Kalkulator](https://www.stackit.de/en/pricing/cloud-services/)

### Datenschutz, Sicherheit und Compliance

- [Datenschutz-Grundverordnung — EUR-Lex](https://eur-lex.europa.eu/eli/reg/2016/679/oj/deu)
- [BSI C5 — Cloud Computing Compliance Criteria Catalogue](https://www.bsi.bund.de/DE/Themen/Unternehmen-und-Organisationen/Informationen-und-Empfehlungen/Empfehlungen-nach-Angriffszielen/Cloud-Computing/Kriterienkatalog-C5/kriterienkatalog-c5.html)
- [AWS Shared Responsibility Model](https://aws.amazon.com/de/compliance/shared-responsibility-model/)
- [Microsoft EU Data Boundary — Überblick](https://learn.microsoft.com/en-us/privacy/eudb/eu-data-boundary-learn)
- [Microsoft EU Data Boundary — fortbestehende Übertragungen](https://learn.microsoft.com/en-us/privacy/eudb/eu-data-boundary-transfers-for-all-services)

### Europäische und souveräne Cloud-Optionen

- [STACKIT — Sovereign Cloud](https://www.stackit.de/en/)
- [IONOS Cloud — Rechenzentren](https://cloud.ionos.de/rechenzentren)
- [T Cloud Public — Portfolio und Regionen](https://www.open-telekom-cloud.com/en/products-services/roadmap)
- [AWS European Sovereign Cloud](https://aws.amazon.com/compliance/europe-digital-sovereignty/)
