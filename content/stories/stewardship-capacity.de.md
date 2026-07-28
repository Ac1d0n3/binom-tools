---
title: "Stewardship staffen — Capacity-Modelle für domain-eingebettete Rollen"
description: "Wie Data Stewardship mit eindeutigem Scope, geschützter Kapazität, kontrolliertem Intake, Service-Tiers und messbaren Ergebnissen besetzt wird."
category: Data Governance
tags:
  - Data Stewardship
  - Data Steward
  - Operating Model
  - Capacity Management
  - Decision Rights
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
order: -1
author: Thomas Lindackers
hero: images/playbooks/stewardship-capacity-hero.png
series: roles-hub
seriesTitle: Rollen und Entscheidungsrechte
seriesPart: 4
publishedAt: 2026-07-19 13:00
---

# Stewardship staffen — Capacity-Modelle für domain-eingebettete Rollen

Data Stewardship scheitert häufig aus einem einfachen Grund: Die Organisation vergibt die Rolle, aber nicht die Kapazität, die für ihre Ausübung erforderlich ist.

Ein Steward kann für eine Domain benannt sein, im Katalog als verantwortlich erscheinen und Governance-Workflows können Anfragen an diese Person weiterleiten. Daraus entsteht noch keine operative Fähigkeit, wenn Stewardship eine unbestimmte Aufgabe „neben dem Tagesgeschäft“ bleibt. Ohne geschützte Zeit, begrenzten Scope, Priorisierung und Eskalation wird Arbeit unsichtbar, Backlogs altern, kritische Reviews konkurrieren mit operativen Aufgaben und die Rolle wird an Erwartungen gemessen, für die sie nie ausgestattet wurde.

Das zentrale Prinzip lautet deshalb:

> Stewardship funktioniert nur, wenn Scope, Nachfrage, Entscheidungsbefugnis und verfügbare Kapazität explizit sind.

Dieses Playbook vertieft das Kapazitätsproblem aus [Fehlende Bausteine bei Ownership und Stewardship](./missing-pieces-ownership-stewardship). Dort wird erklärt, warum formal zugewiesene Rollen häufig passiv bleiben. Diese Story konzentriert sich gezielt auf die operative Kapazität, die aus einem benannten Steward einen verlässlichen Service für die Domain macht.

![Mehrere Stewardship-Bedarfsströme durchlaufen eine endliche Kapazitätsgrenze und werden in priorisierte Arbeit und verifizierte Ergebnisse überführt.](images/playbooks/stewardship-capacity-hero.png)

## Stewardship ist operative Kapazität, nicht nur ein Titel

Eine Stewardship-Zuweisung sollte vier Dinge gemeinsam definieren:

1. das Portfolio, für das der Steward einen Service bereitstellt;
2. die Entscheidungen und Workflows, die die Rolle bearbeiten soll;
3. die für diese Arbeit reservierte Kapazität;
4. die Entscheidungsbefugnis, den Eskalationsweg und die unterstützenden Rollen für den Fall, dass die lokale Kapazität überschritten wird.

Bleibt einer dieser Punkte unklar, entsteht ein strukturelles Problem.

Ein klarer Portfolio-Scope ohne geschützte Zeit wird zum unfinanzierten Auftrag. Geschützte Zeit ohne Entscheidungsrechte erzeugt Koordination ohne Abschluss. Entscheidungsrechte ohne Intake-Modell führen zu unkontrollierten Unterbrechungen. Ein zentrales Team ohne Beteiligung der Domain ersetzt Ownership, statt sie zu befähigen.

Das Capacity-Modell muss deshalb geplante Verbesserungsarbeit, verpflichtende wiederkehrende Arbeit und unvorhersehbare Nachfrage gemeinsam abdecken.

Typische Nachfrage entsteht durch:

- neue Data Products und wesentliche Datenänderungen;
- Entscheidungen zu Geschäftsdefinitionen und KPIs;
- Klassifizierungs- und Sensitivitätsprüfungen;
- Data-Quality-Incidents und Remediation-Entscheidungen;
- Fragen zu Zugriff, Nutzung und erlaubtem Zweck;
- Policy-Ausnahmen;
- regelmäßige Rezertifizierung;
- Evidenz für Governance-, Risiko- oder Audit-Aktivitäten.

Diese Nachfragearten unterscheiden sich hinsichtlich Dringlichkeit, Aufwand und erforderlicher Autorität. Werden sie als ein einheitlicher Backlog behandelt, ist belastbare Planung nicht möglich.

![Nachfrage, verfügbare Kapazität und Ergebnisse bilden gemeinsam ein explizites Stewardship-Capacity-Modell.](images/playbooks/stewardship-capacity-img1-de.png)

## Nutzbare Kapazität berechnen, bevor Service versprochen wird

Eine belastbare Capacity-Diskussion beginnt mit tatsächlich verfügbarer Zeit, nicht mit einem nominellen Prozentwert in einer Rollenbeschreibung.

Die praktische Gleichung lautet:

```text
Verfügbare Stewardship-Zeit
- Feste Taktung und verpflichtende Reviews
- Incident-Reserve
= Kapazität für geplante Verbesserungen
```

Jede Komponente muss explizit sein.

**Verfügbare Stewardship-Zeit** ist die geschützte Kapazität, die eine Person zuverlässig für Stewardship einsetzen kann. Zeit für Delivery, Betrieb, Führung oder andere Domain-Aufgaben ist darin nicht enthalten.

**Feste Taktung und verpflichtende Reviews** umfassen wiederkehrende Kontrollaktivitäten, die nicht beliebig verschoben werden dürfen: Zugriffsrezertifizierung, Klassifizierungsprüfung, Review kritischer Definitionen, Zertifizierung von Data Products, Policy-Bestätigungen oder vereinbarte Governance-Gremien.

**Incident-Reserve** schützt Kapazität für dringende Quality-Probleme, regulatorische Fragen, produktionsrelevante Unklarheiten und zeitkritische Ausnahmen. Ohne Reserve zerstört jeder Incident den Verbesserungsplan.

**Kapazität für geplante Verbesserungen** verbleibt für Backlog-Abbau, Metadatenverbesserung, Workflow-Optimierung, Quality-Prävention und Domain Enablement.

Diese Gleichung gibt keinen universellen Prozentsatz vor. Die richtige Aufteilung hängt von Risiko, Änderungshäufigkeit und Service-Nachfrage ab. Entscheidend ist, dass der Management-Trade-off sichtbar wird.

Übersteigt die Nachfrage die Kapazität, muss der Überschuss als priorisierter Backlog, reduziertes Service-Level, engerer Scope oder Staffing-Entscheidung behandelt werden. Er darf nicht zu unsichtbarer, unbezahlter Zusatzarbeit werden.

## Staffing-Modell nach Scope und Risiko wählen

Drei Operating Models sind üblich. Keines ist universell richtig.

### Dedizierter Domain Steward

Ein dedizierter Steward hat Stewardship als primäre Rolle für eine klar definierte Domain oder ein hochkritisches Portfolio.

Dieses Modell bietet:

- klaren Fokus;
- hohe Verfügbarkeit;
- starke Nähe zur Domain;
- konsistente Review-Taktung;
- bessere Kontinuität für komplexe Workflows.

Es erfordert eine höhere Staffing-Verpflichtung und ist dort gerechtfertigt, wo regulatorische Exposition, geschäftliche Kritikalität, Issue-Volumen oder Änderungsdruck hoch sind.

### Fractional Domain-Embedded Steward

Ein fractional Steward bringt bestehende Domain-Expertise für einen explizit geschützten Anteil seiner Rolle ein.

Dieses Modell kann für kleinere oder stabilere Portfolios gut funktionieren, weil es:

- bereits vorhandenes Wissen in der Domain nutzt;
- Entscheidungen nahe am operativen Geschäft hält;
- schrittweise skalierbar ist;
- den frühzeitigen Aufbau eines großen zentralen Teams vermeidet.

Das zentrale Risiko ist die Verdrängung durch operative Prioritäten. Eine nominelle Zuweisung wie „ungefähr ein Tag pro Woche“ ist nur dann glaubwürdig, wenn die Zeit geschützt, der Scope begrenzt und die entsprechende Reduktion anderer Aufgaben vom Management akzeptiert wird.

„Neben dem Tagesgeschäft“ ist kein Staffing-Modell. Es ist die Annahme, dass Governance-Arbeit freie Kapazität nutzt, die in der Praxis meist nicht existiert.

### Hybrides Stewardship Network

Ein hybrides Netzwerk kombiniert:

- Domain Stewards;
- zentrale Methoden, Standards und Tooling;
- gemeinsam genutzte Spezialisten für Privacy, Security, Architecture oder Quality;
- Backup- und Eskalationsunterstützung;
- einheitliche Intake- und Evidenzpraktiken.

Dieses Modell ist häufig am besten skalierbar, weil es Domain-Verantwortung von wiederverwendbarer Governance-Fähigkeit trennt. Die Domain bleibt für Bedeutung, Priorität und Business Acceptance verantwortlich. Die zentrale Funktion liefert Methoden, Workflow-Design, Tooling, Coaching, domainübergreifende Koordination und bei Bedarf unabhängige Prüfung.

![Drei valide Staffing-Modelle werden nach Scope, Risiko und Nachfrage gewählt, nicht nach einer universellen FTE-Quote.](images/playbooks/stewardship-capacity-img2-de.png)

### Entscheidungskriterien

Folgende Kriterien sollten gemeinsam betrachtet werden:

- Anzahl und Kritikalität der governeden Assets;
- regulatorische und datenschutzbezogene Exposition;
- finanzieller oder operativer Einfluss;
- Änderungs- und Release-Volumen;
- Anzahl der Consumer;
- Issue- und Ausnahmevolumen;
- Komplexität der Domain;
- Reifegrad von Definitionen und Controls;
- Verfügbarkeit qualifizierter Domain-Expertise;
- erforderliche Review-Geschwindigkeit;
- Abhängigkeit von gemeinsam genutzten Spezialisten.

Universelle Quoten wie „ein Steward pro 100 Assets“ oder „ein Steward pro Domain“ sollten vermieden werden. Reine Asset-Zahlen ignorieren Kritikalität, Komplexität und Workflow-Nachfrage. Ein hochriskantes Data Product mit wöchentlichen Änderungen kann mehr Stewardship-Kapazität erfordern als Hunderte stabile, risikoarme Referenz-Assets.

## Portfolio scopen, bevor eine Person zugewiesen wird

Ein Steward sollte nicht einer unbegrenzten Domain zugeordnet werden. Das Portfolio muss operativ beschrieben werden.

Drei Dimensionen sind besonders relevant.

### Kritikalität und Risiko

Bewertet werden:

- geschäftliche Kritikalität;
- PII oder regulierte Daten;
- finanzieller Einfluss;
- operativer Einfluss;
- vertragliche oder rechtliche Verpflichtungen;
- Folgen falscher Interpretation oder verspäteter Entscheidungen.

### Volumen und Komplexität

Bewertet werden:

- Anzahl der Assets und Business Terms;
- Anzahl der Data Products und KPIs;
- Anzahl der Systeme und Interfaces;
- semantische Komplexität;
- domainübergreifende Abhängigkeiten;
- Anzahl der Consumer-Gruppen.

### Änderungs- und Workflow-Nachfrage

Bewertet werden:

- Release-Frequenz;
- Häufigkeit von Definitionsänderungen;
- Volumen von Data-Quality-Incidents;
- Review-Taktung;
- Ausnahmevolumen;
- Zugriffs- oder Nutzungsfragen;
- erwarteter Evidenzbedarf.

Diese Dimensionen sollten in Service-Tiers übersetzt werden.

![Ein Stewardship-Portfolio wird vor der Zuweisung einer Person nach Kritikalität, Komplexität und Workflow-Nachfrage gestuft.](images/playbooks/stewardship-capacity-img3-de.png)

### Tier 1 — Aktives Stewardship

Geeignet für kritische, regulierte, häufig geänderte oder stark nachgefragte Assets.

Typische Service-Erwartungen:

- häufige Reviews;
- benanntes Backup;
- schnelle Eskalation;
- detaillierte Evidenz;
- explizite Reaktionsziele;
- aktive Beteiligung an Change- und Incident-Workflows.

### Tier 2 — Geplantes Stewardship

Geeignet für wichtige, aber stabilere Assets.

Typische Service-Erwartungen:

- regelmäßige geplante Reviews;
- Standard-Workflow;
- gemeinsam genutzte Kapazität;
- definierte Eskalation;
- Evidenz zu vereinbarten Kontrollpunkten.

### Tier 3 — Stewardship bei Bedarf

Geeignet für risikoarme Assets mit geringer Änderungsrate.

Typische Service-Erwartungen:

- erforderliche Mindestmetadaten;
- ereignisgetriggerte Prüfung;
- geringeres Service-Level;
- keine kontinuierliche manuelle Betreuung;
- Eskalation nur bei verändertem Risiko oder steigender Nachfrage.

Tiering verhindert, dass jedes Asset denselben teuren Service erhält. Gleichzeitig entsteht eine belastbare Grundlage für die Entscheidung, welche Arbeit nicht sofort bearbeitet wird.

Wird die Kapazität überschritten, hat das Management vier legitime Optionen:

1. Scope reduzieren;
2. Service-Tier für einen Teil des Portfolios senken;
3. geringwertige Aktivitäten entfernen;
4. Kapazität hinzufügen oder neu zuweisen.

Den Steward verantwortlich zu halten und gleichzeitig alle vier Optionen auszuschließen, ist kein Operating Model.

## Intake definieren, bevor Nachfrage zur Unterbrechung wird

Stewardship-Arbeit sollte über einen kontrollierten Intake-Prozess eingehen. Anfragen aus Meetings, direkten Nachrichten, E-Mails, Katalog-Kommentaren und Incident-Kanälen sollten in einer sichtbaren Queue normalisiert werden.

Ein Intake-Datensatz sollte mindestens enthalten:

- Anfrageart;
- betroffene Domain;
- betroffenes Asset, Business Term, KPI oder Data Product;
- Requester und Consumer-Auswirkung;
- Severity;
- erforderliches Entscheidungsdatum;
- regulatorische oder Policy-Relevanz;
- unterstützende Evidenz;
- Decision Owner;
- Rolle des Stewards im Workflow.

Nützliche Intake-Arten sind:

- Definition;
- Klassifizierung;
- Quality Issue;
- Ownership-Frage;
- Change Review;
- Ausnahme.

Eine Anfrage sollte nicht allein deshalb priorisiert werden, weil der Requester senior oder besonders beharrlich ist. Priorität sollte aus Business Impact, regulatorischer Exposition, Zahl betroffener Consumer, Produktionsauswirkung, Zeitkritikalität und Reversibilität abgeleitet werden.

![Ein kontrollierter Workflow überführt Intake in ein verifiziertes Stewardship-Ergebnis und wiederverwendbare Evidenz.](images/playbooks/stewardship-capacity-img4-de.png)

## Arbeitsklassen trennen

Die Queue sollte mindestens vier Arbeitsklassen unterscheiden.

### Verpflichtende wiederkehrende Arbeit

Beispiele sind Rezertifizierung, regulierte Reviews, Review kritischer Definitionen und Policy-Bestätigungen. Diese Arbeit wird zuerst eingeplant, weil sie vorhersehbar und häufig nicht optional ist.

### Incidents und dringende Entscheidungen

Beispiele sind produktive Quality-Fehler, strittige KPI-Interpretation im Reporting, Offenlegung sensibler Daten oder dringende Nutzungsfragen. Sie nutzen die geschützte Incident-Reserve.

### Geplante Verbesserungen

Beispiele sind bessere Definitionen, Reduktion wiederkehrender Fragen, Abbau von Metadaten-Schulden, Stärkung von Controls oder Redesign von Workflows. Diese Arbeit nutzt die Kapazität für geplante Verbesserungen.

### Beratung und Enablement

Beispiele sind Coaching von Delivery-Teams, Unterstützung von Product Owners bei der Vorbereitung von Contracts, Erklärung von Standards oder Self-Service-Unterstützung. Diese Arbeit muss bewusst budgetiert werden. Andernfalls kann nützliche Beratung die gesamte Kapazität verbrauchen, ohne sichtbare Portfolio-Ergebnisse zu erzeugen.

## Entscheidungsrechte und Grenzen klären

Der Steward darf nicht zum Default Owner jedes Metadatenfelds oder jeder Governance-Aufgabe werden.

Die Rolle bringt typischerweise Domain-Wissen ein, bereitet Evidenz vor, betreibt Workflows, identifiziert Konflikte, empfiehlt Entscheidungen und verifiziert die Anwendung vereinbarter Controls. Die finale Entscheidung kann je nach Thema beim Data Owner, Data Product Owner, Privacy-Rolle, Security-Rolle, Architect oder Governance-Gremium liegen.

Ein belastbares Modell unterscheidet:

- Entscheidungen, die der Steward selbst treffen darf;
- Entscheidungen, die der Steward empfiehlt;
- Entscheidungen, die Data-Owner-Freigabe benötigen;
- Entscheidungen, die Specialist- oder Cross-Domain-Review benötigen;
- Entscheidungen, die wegen Policy-, Risiko- oder Incentive-Konflikten eskaliert werden müssen.

Das schützt Geschwindigkeit und Accountability.

Der Steward muss außerdem das Recht haben, unvollständige Anfragen zurückzuweisen, Evidenz anzufordern, ungelöste Risiken zu eskalieren und eine Kapazitätsüberschreitung festzustellen. Accountability ohne diese Rechte ist strukturell unfair und operativ unwirksam.

## Einfachste tragfähige Implementierung

Eine schlanke Umsetzung kann ohne großes Plattformprogramm etabliert werden.

Startpunkt sind:

1. ein benanntes Portfolio;
2. drei Service-Tiers;
3. ein einfaches Intake-Formular;
4. Severity- und Business-Priority-Regeln;
5. ein sichtbarer Backlog;
6. ein regelmäßiges Capacity Review;
7. ein Eskalationsweg;
8. ein kleines Set an Outcome-Metriken.

Ein Spreadsheet, Work-Management-Board oder Katalog-Workflow kann die erste Version tragen. Das Tool ist sekundär. Die operativen Entscheidungen sind primär.

Für jeden Steward sollte dokumentiert werden:

```yaml
portfolio:
  domains:
  tier_1_assets:
  tier_2_assets:
  tier_3_assets:

capacity:
  protected_hours_per_month:
  mandatory_review_hours:
  incident_reserve_hours:
  planned_improvement_hours:

decision_rights:
  may_decide:
  may_recommend:
  requires_owner_approval:
  escalation_path:

service:
  intake_channel:
  review_cadence:
  backup:
  response_targets:
```

Zweck ist nicht administrative Detailtiefe, sondern überprüfbare Verbindlichkeit.

## Zusammenarbeit mit angrenzenden Rollen

### Data Owner

Der Data Owner akzeptiert wesentliche Business-Risiken, löst Policy-Konflikte und entscheidet, wann Scope, Priorität oder Service-Level geändert werden müssen. Der Owner darf Accountability nicht delegieren und gleichzeitig die dafür notwendige Kapazität ignorieren.

### Data Product Owner

Der Data Product Owner integriert Stewardship-Anforderungen in Product Backlog, Change-Prozess und Release-Entscheidung. Der Product Owner verantwortet Delivery und Consumer Value; der Steward stellt sicher, dass Bedeutung, Controls und Evidenz ausreichend bleiben.

### Governance Lead oder CoE

Governance Lead oder CoE liefern Standards, Workflows, Training, Tooling, Metriken und domainübergreifende Eskalation. Sie sollten nicht sämtliche Domain-Arbeit übernehmen. Wenn das zentrale Team dauerhaft Definitionen schreibt, lokale Quality-Issues löst und jeden Review ausführt, entsteht zentralisierte Ausführung ohne Domain Ownership.

### Data Architect

Der Architect unterstützt Strukturentscheidungen, Contract Design, Cross-Domain-Konsistenz und Change Impact. Der Steward liefert Domain-Semantik, Nutzungskontext und Kontrollnachweise.

### Privacy-, Security- und Risk-Spezialisten

Spezialisten liefern verbindliche Interpretation für regulierte oder hochriskante Fragen. Ihr Beitrag sollte über Shared-Service-Kapazität oder Eskalationsschwellen geplant werden, statt erst dann ad hoc angefragt zu werden, wenn jedes Thema bereits dringend geworden ist.

## Konkretes Beispiel

Eine Customer Domain weist einem fractional Steward 32 geschützte Stunden pro Monat zu.

Das Portfolio enthält:

- zwei Tier-1-Data-Products für regulatorisches und Executive Reporting;
- sechs analytische Tier-2-Produkte;
- ungefähr 120 Tier-3-Katalog-Assets;
- monatliche Klassifizierungsprüfung;
- vierteljährliche Definitionsrezertifizierung;
- wiederkehrende Quality-Incidents aus einem Quellsystem.

Der monatliche Capacity-Plan lautet:

```text
32 verfügbare Stunden
- 8 Stunden feste Reviews und Governance-Taktung
- 6 Stunden Incident-Reserve
= 18 Stunden Kapazität für geplante Verbesserungen
```

In einem Monat erzeugt eine neue regulatorische Klassifizierungsanforderung zehn Stunden verpflichtende Arbeit, und zwei kritische Incidents verbrauchen die vollständige Reserve.

Die richtige Reaktion besteht nicht darin, vom Steward zusätzlich die ursprüngliche Verbesserungsplanung zu erwarten. Der Backlog wird neu priorisiert. Zwei Tier-2-Beschreibungsverbesserungen werden in den nächsten Zyklus verschoben. Der Data Owner genehmigt vorübergehend ein reduziertes Service-Level für risikoarme Assets. Der Governance Lead stellt vier Stunden Specialist Support bereit. Das wiederkehrende Quellsystemproblem wird an den Product Owner eskaliert, weil es kontinuierlich Stewardship-Kapazität bindet.

Zum Monatsende prüft das Team die Nachfragemuster und erkennt, dass es sich nicht um eine einmalige Spitze handelt. Der Product Owner finanziert präventive Remediation, und das Stewardship-Capacity-Modell wird angepasst.

Das ist der Unterschied zwischen gesteuerter Kapazität und unsichtbarer Überlastung.

## Ergebnisse messen, nicht Aktivität

Nützliche Metriken zeigen, ob Stewardship Entscheidungen, Controls und Vertrauen verbessert.

Empfohlene Metriken sind:

- Time to Decision;
- Backlog-Alter nach Severity;
- wiedereröffnete Issues;
- Abdeckung kritischer Assets;
- überfällige Reviews;
- Quality nach der Lösung;
- Reduktion wiederkehrender Anfragen;
- Anteil der Arbeit innerhalb des definierten Service-Tiers;
- Incident-Nachfrage im Verhältnis zur reservierten Kapazität;
- tatsächlich geschützte Kapazität für geplante Verbesserungen.

Vanity Metrics sollten vermieden werden:

- bearbeitete Felder;
- besuchte Meetings;
- geschriebene Kommentare;
- Katalog-Logins ohne Ergebnis;
- Anzahl benannter Stewards;
- gestartete Workflows ohne Abschluss.

Aktivität kann bei der Workload-Diagnose helfen, ist aber kein Nachweis für Wert.

Eine starke Metrik beantwortet eine von drei Fragen:

1. Wurde eine Entscheidung schneller oder verlässlicher?
2. Wurden Risiko oder wiederholte Arbeit reduziert?
3. Blieb das Portfolio innerhalb des vereinbarten Service-Levels?

## Häufige Anti-Patterns

### Unbegrenzter Asset-Scope

Ein Steward wird einer vollständigen Domain ohne Tiering, Ausschlüsse oder Service-Level zugewiesen.

**Folge:** Kritische Arbeit konkurriert mit geringwertiger Pflege, und Accountability kann nicht sinnvoll bewertet werden.

### Keine geschützte Zeit

Die Rolle existiert nur in einer RACI oder im Katalog.

**Folge:** Delivery- und Betriebsarbeit gewinnt immer, während der Governance-Backlog verborgen bleibt.

### Jedes Metadatenfeld wird Steward-Arbeit

Der Steward soll Beschreibungen, technische Lineage, Ownership, Quality Rules, Klassifizierungen und Nutzungsdokumentation persönlich pflegen.

**Folge:** Die Rolle wird zu einem manuellen Metadatenservice statt zu einer Entscheidungs- und Kontrollfunktion.

### Backlog ohne Severity oder Business-Priorität

Anfragen werden nach Eingangsreihenfolge oder Stakeholder-Druck bearbeitet.

**Folge:** Dringende und wesentliche Themen werden mit kosmetischen Verbesserungen vermischt.

### Aktivitätszahlen werden als Erfolg genutzt

Das Programm berichtet Edits, Meetings oder Logins.

**Folge:** Sichtbare Bewegung ersetzt verifizierte Ergebnisse.

### Accountability ohne Entscheidungsbefugnis

Der Steward wird für ungelöste Issues verantwortlich gemacht, kann aber weder zurückweisen, freigeben, eskalieren noch Owner-Entscheidungen einfordern.

**Folge:** Die Rolle wird zum Koordinationsengpass.

### Zentrales Team übernimmt sämtliche Domain-Arbeit

Das CoE schreibt Definitionen, löst Issues und betreibt jeden Workflow.

**Folge:** In der Domain entsteht keine Capability, und die zentrale Queue wird zum neuen Bottleneck.

## Entscheidungshilfe

Ein dedizierter Steward ist sinnvoll, wenn das Portfolio kritisch, reguliert, komplex oder kontinuierlich in Veränderung ist.

Ein fractional domain-eingebetteter Steward ist sinnvoll, wenn der Scope kleiner ist, Domain-Expertise bereits vorhanden ist und das Management Kapazität tatsächlich schützen kann.

Ein hybrides Netzwerk ist sinnvoll, wenn mehrere Domains lokale Accountability benötigen, Methoden, Spezialisten, Tooling und Eskalation aber gemeinsam genutzt werden sollen.

Kapazität sollte erhöht oder Scope reduziert werden, wenn:

- der High-Severity-Backlog altert;
- verpflichtende Reviews überfällig werden;
- die Incident-Reserve wiederholt aufgebraucht wird;
- geplante Verbesserungsarbeit dauerhaft verdrängt wird;
- dieselben Fragen wiederkehren;
- kritische Assets keine Backup-Abdeckung haben;
- domainübergreifende Issues ungelöst bleiben;
- der Steward notwendige Entscheidungsrechte nicht ausüben kann.

Überlastung sollte nicht durch weitere Felder, Meetings oder generische Governance-Aufgaben behandelt werden. Zuerst ist Arbeit zu entfernen, die kein Steward-Urteil erfordert.

## Zentrale Empfehlungen

- Portfolio definieren, bevor der Steward benannt wird.
- Kapazität im Operating Plan schützen, nicht nur in der Rollenbeschreibung.
- Verpflichtende Reviews, Incident-Reserve und geplante Verbesserungsarbeit trennen.
- Service-Tiers nach Kritikalität, Komplexität und Nachfrage einsetzen.
- Arbeit über sichtbaren Intake und Prioritätsregeln steuern.
- Dem Steward explizite Entscheidungs- und Eskalationsrechte geben.
- Domain Accountability in der Domain belassen.
- Zentrale Governance für Methoden, Tooling, Specialist Support und domainübergreifende Koordination nutzen.
- Entscheidungsqualität, Geschwindigkeit, Risikoreduktion und Reduktion wiederholter Arbeit messen.
- Capacity Gaps als Management-Entscheidung behandeln, nicht als persönliches Leistungsproblem.

## Nächstes Playbook

Kapazität wird erst dann nachhaltig, wenn die übergreifende Governance-Organisation wiederverwendbare Methoden, Shared Services, Eskalation und Enablement bereitstellt, ohne Ownership aus den Domains abzuziehen.

Weiter mit der nächsten Roles-Hub-Story: **Governance CoE**.
