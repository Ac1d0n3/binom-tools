---
title: Ein Data-Governance-Center of Excellence aufbauen
description: Ein Governance CoE definieren, das Domänen befähigt, unternehmensweite Entscheidungen koordiniert, Eskalationen steuert und Ergebnisse nachweist, ohne die Verantwortung der Domänen zu übernehmen.
category: Data Governance
tags:
  - governance-coe
  - governance-operating-model
  - decision-rights
  - data-stewardship
  - governance-evidence
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
order: -1
author: Thomas Lindackers
hero: images/playbooks/governance-coe-hero.png
series: roles-hub
seriesTitle: Rollen und Entscheidungsrechte
seriesPart: 5
publishedAt: 2026-07-19 14:00
---

# Ein Data-Governance-Center of Excellence aufbauen

Ein Data Governance Center of Excellence, kurz Governance CoE, entsteht häufig dann, wenn ein Unternehmen erkennt, dass einzelne Richtlinien, lokale Stewardship-Initiativen und Katalogprojekte keine konsistenten unternehmensweiten Entscheidungen erzeugen. Benötigt wird eine kleine zentrale Fähigkeit, die gemeinsame Standards etabliert, Domänen verbindet, domänenübergreifende Konflikte löst und Executive Sponsors zeigt, ob Governance einen messbaren geschäftlichen Nutzen liefert.

Das Risiko besteht darin, dass das CoE zum zentralen Betriebsteam für jede Definition, jedes Problem, jede Freigabe und jedes Data Product wird. Dieses Modell wirkt zunächst kontrolliert, skaliert jedoch nicht. Gleichzeitig entzieht es den Fachdomänen die Verantwortung, obwohl diese die Daten verstehen, Zielkonflikte bewerten und die Ergebnisse verantworten müssen.

Ein tragfähiges Governance CoE benötigt deshalb ein bewusst begrenztes Mandat: Es befähigt, koordiniert und sichert ab. Domänen und Plattformteams führen aus.

## Das zentrale Operating-Model-Prinzip

Das CoE soll die Voraussetzungen für gute Governance-Entscheidungen schaffen, ohne selbst Eigentümer jeder Entscheidung zu werden.

Seine Aufgabe ist es:

- praxistaugliche Standards, Vorlagen und Entscheidungsrahmen bereitzustellen;
- Data Owner, Stewards und Delivery-Teams einzuarbeiten;
- ein unternehmensweites Vokabular und domänenübergreifende Abhängigkeiten zu koordinieren;
- Eskalationswege für ungelöste Konflikte und wesentliche Ausnahmen zu pflegen;
- Councils mit entscheidungsreifen Sachverhalten statt mit Statusberichten zu unterstützen;
- gemeinsame Nachweise und Ergebniskennzahlen zu definieren;
- Governance-Leistung gegenüber Executive Sponsors zu berichten.

Die Domänen bleiben dafür verantwortlich, Definitionen zu pflegen, Kontrollen umzusetzen, operative Qualitätsprobleme zu lösen, Data Products zu betreiben und geschäftliche Ergebnisse zu verantworten. Plattformteams bleiben für zuverlässige technische Services, Integrationen und die technische Umsetzung von Kontrollen innerhalb ihrer Plattformgrenzen zuständig.

![Mission und Abgrenzung des Governance CoE](images/playbooks/governance-coe-img1-de.png)

Diese Trennung ist die zentrale Designentscheidung. Das CoE ist keine Freigabewarteschlange für jede Routineänderung. Es ist nicht Eigentümer jedes Datasets. Es ersetzt weder Data Owner noch Stewards, Produktteams, Architekten oder Kontrollfunktionen.

## Mandat und Grenzen

Ein belastbares CoE-Mandat sollte fünf Fragen ausdrücklich beantworten.

### 1. Was darf das CoE definieren?

Das CoE darf unternehmensweite Governance-Prinzipien, Mindestanforderungen an Kontrollen, wiederverwendbare Vorlagen, Entscheidungsnachweise, Evidenzanforderungen und Eskalationsschwellen definieren. Diese Artefakte sollen Leitplanken schaffen und nicht jedes lokale Implementierungsdetail vorschreiben.

### 2. Was darf das CoE entscheiden?

Das CoE darf Themen innerhalb seines delegierten Mandats entscheiden, beispielsweise das Verfahren für den Governance Intake, die Pflichtfelder eines Ausnahmeantrags oder die Mindestevidenz für das Onboarding einer kritischen Domäne. Wesentliche Richtlinienausnahmen, ungelöste Ownership-Konflikte und die Akzeptanz unternehmensweiter Risiken benötigen in der Regel ein Council oder einen Executive Sponsor.

### 3. Was bleibt in den Domänen?

Domänen entscheiden über routinemäßige fachliche Definitionen, lokale Prioritäten, die Reihenfolge von Verbesserungsmaßnahmen und produktbezogene Zielkonflikte innerhalb genehmigter Leitplanken. Sie benennen außerdem verantwortliche Rollen, liefern Evidenz und übernehmen Verantwortung für das operative Ergebnis.

### 4. Was benötigt gemeinsame Spezialisten?

Auswirkungen auf Datenschutz, Security, Recht, Risiko, Architektur und Plattformen müssen durch die jeweils zuständigen Spezialisten geprüft werden. Das CoE koordiniert diese Prüfung, ersetzt diese Funktionen jedoch nicht und genehmigt keine Themen außerhalb seiner Fachkompetenz.

### 5. Was löst eine Eskalation aus?

Eskalationen sollten schwellenwertbasiert sein. Typische Auslöser sind wesentliche regulatorische Risiken, inkompatible domänenübergreifende Definitionen, ungeklärte Ownership, kostenintensive Ausnahmen, wiederholtes Kontrollversagen, strategischer Finanzierungsbedarf oder Konflikte, die innerhalb der delegierten Domänenverantwortung nicht gelöst werden können.

Klare Grenzen verhindern die beiden häufigsten Fehlentwicklungen: ein machtloses CoE, das nur Dokumente veröffentlicht, und ein überzentralisiertes CoE, das zum operativen Eigentümer der Governance-Arbeit wird.

## Zentrale, föderierte und hybride Muster

Das organisatorische Muster sollte zur Größe, zum Risikoprofil und zum Reifegrad des Unternehmens passen.

Ein zentrales Muster kann in der Aufbauphase oder in einer kleinen Organisation funktionieren. Ein kleines Team entwickelt das Operating Model, wählt erste Domänen aus, coacht die ersten Rolleninhaber und etabliert Evidenz. Die Begrenzung liegt in der Kapazität: Führt das Zentrum weiterhin lokale Arbeit aus, wächst die Nachfrage schneller als das Team.

Ein föderiertes Muster verlagert die Ausführung in die Domänen. Das CoE stellt Standards, Vorlagen, gemeinsame Kennzahlen und Eskalationsunterstützung bereit. Data Owner, Stewards und Produktteams treffen und implementieren Domänenentscheidungen. Dieses Modell skaliert, aber nur dann, wenn die Domänen echte Kapazität erhalten und unternehmensweite Konflikte über einen glaubwürdigen Entscheidungsweg gelöst werden können.

Ein hybrides Muster ist in der Praxis häufig. Das CoE behält die zentrale Verantwortung für unternehmensweite Methoden, Council-Betrieb, domänenübergreifende Koordination, Assurance und Sponsor-Reporting. Die Domänen führen lokal aus, während ausgewählte Shared Services wie Katalogkonfiguration, Policy-Automatisierung oder Quality Tooling zentral durch Plattformteams betrieben werden können.

Die Unterscheidung betrifft die operative Verantwortung und nicht die Architektur der Metadatenspeicherung. Ein föderiertes Governance-Modell kann eine zentrale Plattform verwenden, und ein zentrales Governance-Team kann trotzdem daran scheitern, unternehmensweite Konsistenz herzustellen.

![Zentraler Kern, föderierte Ausführung](images/playbooks/governance-coe-img2-de.png)

## Die einfachste tragfähige Umsetzung

Ein Governance CoE benötigt zum Start keine große Abteilung. Es benötigt ein kleines, explizites Betriebssystem.

Die minimal tragfähige Umsetzung besteht aus sieben Elementen:

1. **Ein schriftliches Mandat**, das Zweck, delegierte Autorität, Grenzen und Sponsor definiert.
2. **Ein priorisiertes Domänenportfolio**, basierend auf Wert, Risiko, Reife und Sponsorship.
3. **Ein gemeinsamer Intake-Pfad** für Probleme, Policy-Fragen, Ausnahmen und domänenübergreifende Konflikte.
4. **Ein Triage-Modell**, das Arbeit an lokale Lösung, Spezialistenprüfung, Council-Entscheidung oder Sponsor-Eskalation weiterleitet.
5. **Ein Entscheidungsnachweis** mit Owner, Fälligkeitsdatum, Begründung, Evidenz und Follow-up.
6. **Ein kleines Evidenzset**, das Abdeckung, Entscheidungsleistung, Kontrollergebnisse und Adoption misst.
7. **Eine regelmäßige operative Kadenz** für Operational Review, Council-Entscheidungen und Sponsor-Reporting.

Dies kann mit einem leichtgewichtigen Workflow und einem kontrollierten Repository beginnen. Ein Katalog, Ticketing-System oder Workflow-Tool kann den Prozess unterstützen, ist aber nicht das Operating Model. Zuerst muss definiert werden, wer welche Entscheidungsklasse treffen darf, welche Evidenz erforderlich ist und wie ungelöste Arbeit eskaliert wird.

## Council-Kadenz und Eskalation

Governance Councils scheitern häufig, weil sie ohne Entscheidungsagenda tagen. Die Beteiligten prüfen Folien, diskutieren ungelöste Themen und gehen auseinander, ohne Owner, Termin oder Evidenzanforderung festzulegen.

Ein stärkeres Modell trennt vier operative Ebenen.

### Operational Intake

Anfragen gehen kontinuierlich ein. Sie können ein neues Problem, eine Policy-Auslegung, einen Ausnahmeantrag oder einen domänenübergreifenden Konflikt betreffen. Der Intake sollte genügend Kontext erfassen, um Schweregrad, betroffene Domänen, Assets, Verpflichtungen und erforderliches Entscheidungsdatum zu klassifizieren.

### Triage

Die Triage bestimmt die niedrigste angemessene Entscheidungsebene. Der größte Teil der Arbeit sollte lokal verbleiben. Eine Spezialistenprüfung ist erforderlich, wenn Datenschutz, Security, Architektur, Recht oder Risiko wesentlich betroffen sind. Nur unternehmensweite Themen, wesentliche Ausnahmen und ungelöste Konflikte sollten auf die Council-Agenda gelangen.

### Governance Council

Das Council entscheidet unternehmensweite Themen, genehmigt wesentliche Ausnahmen innerhalb seines Mandats, löst Ownership-Konflikte und priorisiert gemeinsame Fähigkeiten. Jeder Agenda-Punkt sollte mit Optionen, Auswirkungen, einer Entscheidungsempfehlung und erforderlicher Evidenz vorbereitet werden.

### Executive Sponsor

Der Sponsor ist kein symbolischer Teilnehmer. Er stellt Autorität bereit, löst Finanzierungsblockaden, akzeptiert wesentliche unternehmensweite Risiken und entscheidet strategische Konflikte, die das Mandat des Councils überschreiten.

Die Kadenz sollte anpassbar statt starr sein: kontinuierlicher Intake, regelmäßiges Operational Review, geplante Council-Termine, periodisches Sponsor-Review und dringende Eskalation, sobald definierte Schwellenwerte erreicht sind.

![Council-Kadenz und Eskalationswege](images/playbooks/governance-coe-img3-de.png)

Jede Entscheidung sollte fünf Ergebnisse erzeugen:

- einen verantwortlichen Owner;
- ein Fälligkeitsdatum;
- die Begründung und den Zielkonflikt;
- erforderliche Evidenz;
- einen Follow-up- oder Review-Zeitpunkt.

Ohne diese Ergebnisse ist ein Council ein Diskussionsforum und kein Governance-Mechanismus.

## Minimale Fähigkeiten des CoE

Ein CoE benötigt ein ausgewogenes Kompetenzprofil. Es sollte nicht ausschließlich mit Policy-Spezialisten oder Katalogadministratoren besetzt sein.

### Governance Operating Model

Das Team muss Mandate, Entscheidungsschwellen, Eskalationswege, Councils, Evidenzanforderungen und Schnittstellen zwischen zentralen und föderierten Rollen gestalten können.

### Stewardship-Facilitation

Das CoE sollte Stewards und Data Owner dabei unterstützen, Definitionen, Entscheidungen und Issue Resolution strukturiert vorzubereiten. Facilitation ist besonders wichtig, wenn Geschäftsbereiche unterschiedliche Begriffe oder Anreize haben.

### Architektur und Metadaten

Das Team benötigt ausreichend Architektur- und Metadatenkompetenz, um Lineage, Data Products, semantische Abhängigkeiten, technische Kontrollen und Plattformrestriktionen zu verstehen. Es muss nicht jede technische Umsetzung selbst verantworten.

### Koordination von Datenschutz, Security und Risiko

Das CoE muss erkennen, wann eine Spezialistenprüfung verpflichtend ist, wie sie weitergeleitet wird und wie Evidenz erhalten bleibt. Es ersetzt nicht die verantwortlichen Datenschutz-, Security-, Rechts- oder Risikofunktionen.

### Change Management

Governance verändert Verhalten, Anreize und Delivery-Praktiken. Rollen-Onboarding, Coaching, Kommunikation und Adoption Support sind deshalb zentrale Betriebsfähigkeiten und keine optionalen Projektaktivitäten.

### Messung und Evidenz

Das CoE muss Kennzahlen definieren, die Governance-Arbeit mit Ergebnissen verbinden. Es sollte Aktivität, Adoption, Kontrollleistung und Geschäftswert unterscheiden können.

In einer kleinen Organisation kann eine Person mehrere Fähigkeiten abdecken. In einer größeren oder risikoreichen Umgebung sollte das CoE einen Governance Lead, Operating-Model-Kompetenz, Stewardship-Facilitation, Metadaten- oder Architekturkompetenz, Change-Kompetenz und Measurement Support kombinieren. Spezialfunktionen können außerhalb des CoE bleiben, müssen aber Teil des operativen Netzwerks sein.

## Staffing und Priorisierung der Domänen

Die richtige Teamgröße hängt weniger von der Gesamtzahl der Datasets ab als von der Zahl aktiver Domänen, dem Entscheidungsvolumen, dem Risiko, dem Änderungsbedarf und der Reife der lokalen Rollen.

Ein CoE sollte nicht alle Domänen gleichzeitig onboarden. Es sollte ein erstes Portfolio anhand von vier Kriterien auswählen:

- **Wert:** Unterstützt die Domäne wesentliche Umsatz-, Kunden-, Betriebs- oder Entscheidungsanwendungsfälle?
- **Risiko:** Enthält sie regulierte, sensible, finanzielle oder betriebskritische Daten?
- **Reife:** Sind Owner, Stewards, Delivery-Teams und grundlegende Metadaten verfügbar?
- **Sponsorship:** Gibt es eine verantwortliche Führungskraft, die Kapazität bereitstellt und Entscheidungen durchsetzt?

Eine hochwertige, aber vollständig unvorbereitete Domäne kann die gesamte CoE-Kapazität verbrauchen, ohne Evidenz zu erzeugen. Eine risikoarme, leicht umsetzbare Domäne kann viel Aktivität, aber wenig Unternehmenswert liefern. Das erste Portfolio sollte deshalb ein oder zwei sichtbare Geschäftsergebnisse mit ausreichender Umsetzungsreife verbinden.

Kapazität sollte als Portfolio und nicht als generische Headcount-Zahl geplant werden. Das CoE benötigt Zeit für Standards, Onboarding, operative Triage, Council-Vorbereitung, domänenübergreifende Facilitation, Messung und kontinuierliche Verbesserung. Domänenarbeit, die dauerhaft zentrale Kapazität bindet, ist ein Signal für unvollständige lokale Verantwortung oder unzureichendes Staffing.

## Zusammenarbeit mit angrenzenden Rollen

Der Governance Lead verantwortet das CoE-Betriebssystem und die Sponsor-Beziehung. Data Owner bleiben für Domänenentscheidungen und Ergebnisse verantwortlich. Stewards bereiten Definitionen, Evidenz und Issue Resolution vor. Data Product Owner steuern Produktzweck, Serviceerwartungen und Delivery-Zielkonflikte. Data Architects pflegen architektonische Leitplanken und prüfen wesentliche domänenübergreifende Designs. Platform Operations implementiert und betreibt gemeinsame technische Services. Privacy, Security, Risk und Compliance liefern spezialisierte Anforderungen und Genehmigungen innerhalb ihrer Mandate.

Das CoE verbindet diese Rollen über einen konsistenten Entscheidungsweg. Es fasst sie nicht zu einem einzigen zentralen Team zusammen.

Deshalb sollte das CoE auch nicht für jede Aufgabe neue RACI-Mechaniken aufbauen. Die detaillierte Zuweisung von Verantwortlichkeiten gehört in operative Verfahren und die entsprechenden Playbooks. Das CoE konzentriert sich auf Entscheidungsklassen, Schwellenwerte, Evidenz und Eskalation.

## Konkretes Beispiel: Customer- und Finance-Domänen onboarden

Angenommen, ein Unternehmen möchte die Zuverlässigkeit des Customer-Profitability-Reportings verbessern. Customer und Finance verwenden unterschiedliche Definitionen für aktiven Kunden, Umsatzkorrektur und Berichtsdatum. Die Analytics-Plattform enthält mehrere doppelte Measures, und kein einzelnes Team kann den Konflikt allein lösen.

Das CoE schreibt nicht selbst alle Definitionen neu. Es übernimmt folgende Aufgaben:

1. Es bestätigt das Geschäftsergebnis und den Sponsor.
2. Es wählt Customer und Finance als erstes Domänenportfolio aus, weil der Anwendungsfall hohen Wert, finanzielle Auswirkungen und sichtbares Sponsorship besitzt.
3. Es onboardet Data Owner und Stewards in den Entscheidungsprozess.
4. Es stellt eine gemeinsame Definitionsvorlage und Evidenzanforderungen bereit.
5. Es leitet die bilanzielle Behandlung an Finance, die Kundenstatuslogik an Customer und die semantische Umsetzung an Produkt- und Architekturteams weiter.
6. Es moderiert die ungelösten domänenübergreifenden Entscheidungen.
7. Es eskaliert nur den wesentlichen Konflikt, der innerhalb der delegierten Domänenautorität nicht gelöst werden kann.
8. Es dokumentiert die endgültige Entscheidung, Begründung, Owner, Implementierungstermin und Validierungsevidenz.
9. Es berichtet, ob das vertrauenswürdige Measure genutzt wird, ob doppelte Berechnungen zurückgehen und ob sich die Entscheidungszeit verbessert.

Das Ergebnis ist nicht die Zahl der Workshops oder ausgefüllten Felder. Das Ergebnis ist eine geregelte, implementierte und genutzte unternehmensweite Entscheidung mit Evidenz.

## Ergebnisse statt Governance-Aktivität berichten

Executive Sponsors benötigen Evidenz dafür, dass Governance Kontrollen, Entscheidungsqualität und Delivery verbessert. Sie benötigen keinen Tätigkeitskatalog des CoE.

Ein aussagekräftiger Sponsor-Bericht enthält vier Evidenzgruppen.

### Abdeckung

Gemessen wird, ob priorisierte Domänen onboardet sind, kritische Assets geregelt werden und verantwortliche Rollen zugewiesen sind. Die Abdeckung sollte risiko- und wertbasiert sein und nicht als Prozentsatz aller theoretisch möglichen Metadaten verstanden werden.

### Entscheidungsleistung

Gemessen werden Entscheidungsdurchlaufzeit, ungelöste Eskalationen, Alter von Ausnahmen und wiederkehrende Konflikte. Diese Kennzahlen zeigen, ob das Operating Model Arbeit tatsächlich löst oder nur anhäuft.

### Kontrollergebnisse

Gemessen werden Policy Compliance, Qualitätsverbesserung, umgesetzter Schutz und Vollständigkeit der Audit-Evidenz. Die Kennzahl sollte den Zustand nach der Intervention zeigen und nicht nur dokumentieren, dass eine Kontrolle beschrieben wurde.

### Adoption und Wert

Gemessen werden wiederverwendete Standards, reduzierter manueller Aufwand, Nutzung vertrauenswürdiger Assets und Stakeholder Confidence. Adoption-Evidenz sollte Governance-Artefakte mit ihrer operativen Nutzung verbinden.

![Ergebnisse statt Governance-Aktivität berichten](images/playbooks/governance-coe-img4-de.png)

Durchgeführte Meetings, erstellte Dokumente, ausgefüllte Felder und Trainingsteilnahmen können Kontext liefern. Sie belegen keine Governance-Ergebnisse. Ein reifes CoE nutzt Aktivitätskennzahlen zur Erklärung der Arbeitslast, während das Sponsor-Reporting auf Abdeckung, Entscheidungen, Kontrollen und Wert fokussiert bleibt.

## Häufige Anti-Patterns

### Das CoE besitzt jede Entscheidung

Das zentrale Team wird zum Engpass, Domänenrollen werden passiv und Routinearbeit wartet auf Freigabe. Die Gegenmaßnahme ist delegierte Autorität mit expliziten Eskalationsschwellen.

### Eine Policy-Fabrik ohne Adoption

Das CoE veröffentlicht Standards, die nicht in Delivery-Workflows, Product Contracts oder Domänenentscheidungen eingebettet werden. Jeder Standard benötigt deshalb einen Owner, einen Implementierungsweg, Evidenz und einen Review-Zyklus.

### Katalogadministration wird mit Governance gleichgesetzt

Die Metadatenvollständigkeit wird zum eigentlichen Ziel. Ein Katalog kann Governance unterstützen, aber Governance ist das Operating Model aus Entscheidung, Verantwortung, Kontrolle und Evidenz rund um die Metadaten.

### Das zentrale Team ersetzt die Domänenverantwortung

Das CoE schreibt Definitionen und löst Probleme, weil den Domänen Kapazität fehlt. Temporäre Unterstützung kann notwendig sein, sollte jedoch einen expliziten Kapazitätsplan und eine Übergabe auslösen.

### Councils tagen ohne Entscheidungen oder Evidenz

Themen kehren wiederholt zurück, weil kein Owner, Fälligkeitsdatum oder Entscheidungsgrund dokumentiert wird. Die Gegenmaßnahme ist eine entscheidungsreife Agenda mit verpflichtenden Ergebnissen.

### Reporting zählt Aktivität statt Ergebnisse

Hohe Aktivität kann gleichzeitig mit ungelösten Risiken und geringer Adoption auftreten. Sponsor-Reporting muss zeigen, was sich in der geregelten Umgebung tatsächlich verändert hat.

## Entscheidungshilfe

Ein Governance CoE sollte aufgebaut oder neu gestaltet werden, wenn mehrere Domänen gemeinsame Standards benötigen, unternehmensweite Konflikte ungelöst bleiben, Governance-Rollen inkonsistent sind, Spezialistenprüfungen fragmentiert erfolgen oder Sponsoren nicht erkennen können, ob Governance funktioniert.

Das CoE sollte klein und befähigend bleiben, wenn die Domänen bereits über reife Ownership- und Delivery-Fähigkeiten verfügen. Zentrale Koordination wird benötigt, wenn domänenübergreifende Entscheidungen, regulatorische Verpflichtungen oder gemeinsame Kontrollen Konsistenz erfordern. Assurance wird benötigt, wenn das Unternehmen nachweisen muss, dass Entscheidungen und Kontrollen umgesetzt wurden.

Die zentrale Teamgröße sollte nicht allein deshalb wachsen, weil die Nachfrage hoch ist. Zuerst ist zu klären, ob die Nachfrage legitime unternehmensweite Koordination darstellt oder Arbeit, die durch Domänen- und Plattformrollen ausgeführt werden muss. Zentrales Wachstum ohne delegierte Ausführung erhöht meistens die Abhängigkeit statt den Reifegrad.

## Zentrale Empfehlungen

Ein praxistaugliches Governance CoE sollte:

1. ein enges Mandat und explizite Grenzen definieren;
2. Befähigung, Koordination und Assurance von der Domänenausführung trennen;
3. Routineentscheidungen delegieren und nur anhand definierter Schwellenwerte eskalieren;
4. ein priorisiertes Portfolio statt aller Domänen gleichzeitig onboarden;
5. eine entscheidungsreife Council-Kadenz betreiben;
6. Operating Model, Stewardship, Architektur, Change und Measurement als Fähigkeiten kombinieren;
7. Ergebnis-Evidenz an Sponsoren berichten;
8. wiederkehrende zentrale Ausführung als Domänenkapazitätsproblem behandeln;
9. Tooling zur Unterstützung des Operating Models einsetzen und nicht als Ersatz dafür;
10. Standards anhand von Adoption Feedback und wiederkehrenden Entscheidungsmustern weiterentwickeln.

## Wie es weitergeht

Ein Governance CoE kann das Operating Model nur koordinieren, wenn die beteiligten Rollen über explizite Autorität und ausreichende Kapazität verfügen. Als nächstes eignet sich **Stewardship Capacity**, um Stewardship-Portfolios, Nachfrage und Eskalation praktisch zu dimensionieren. **RACI for Data Governance** ist der passende Anschluss, wenn in einem konkreten Prozess weiterhin unklar ist, wie Verantwortlichkeiten verteilt sind.
