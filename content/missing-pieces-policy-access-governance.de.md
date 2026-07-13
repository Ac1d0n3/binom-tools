---
title: "The Missing Pieces – Teil 5: Richtlinien- & Zugriffs-Governance"
description: "Warum dokumentierte Richtlinien nicht automatisch zu operativen Kontrollen werden – und wie Organisationen Absicht, Identität, Zugriffsentscheidungen, Durchsetzung, Reviews und Nachweise verbinden können."
author: Thomas Lindackers
category: Data Governance
tags:
  - the-missing-pieces
  - policy-governance
  - access-governance
  - identity-governance
  - zugriffskontrolle
  - least-privilege
  - datensicherheit
  - data-compliance
  - data-governance
order: -1
hero: images/playbooks/mp-access-hero.png
series: missing-pieces
seriesPart: 5
seriesTitle: The Missing Pieces
---

## Eine Richtlinie kann auf dem Papier vollständig und in der Praxis unvollständig sein

Den meisten Organisationen fehlen nicht grundsätzlich Richtlinien.

Sie verfügen über Sicherheitsstandards, Datenklassifizierungen, Datenschutzanforderungen, Aufbewahrungsregeln, Zugriffsmodelle, Freigabeprozesse und Rollendefinitionen.

Häufig existieren außerdem ausgereifte technische Fähigkeiten:

- Identity Provider und Single Sign-on,
- rollenbasierte Zugriffskontrolle,
- attributbasierte Richtlinien,
- Zeilen- und Spaltenberechtigungen,
- Maskierung und Verschlüsselung,
- Entitlement Management,
- Workflows für Zugriffsanforderungen,
- Access Reviews und Rezertifizierungen,
- Audit Logs und Monitoring.

Diese Fähigkeiten sind wichtig. Sie bilden die technischen und organisatorischen Bausteine für kontrollierten Zugriff.

Die offene Frage lautet, ob die ursprüngliche Absicht einer Richtlinie dauerhaft mit den Kontrollen verbunden bleibt, die in Systemen, Plattformen und Geschäftsprozessen tatsächlich ausgeführt werden.

Eine Richtlinie kann beispielsweise festlegen:

> *Kontaktinformationen von Kunden dürfen nur von autorisierten Teams für freigegebene Geschäftszwecke verwendet werden.*

Für die operative Umsetzung müssen trotzdem viele Entscheidungen getroffen werden:

- Welche Datenobjekte enthalten Kundenkontaktdaten?
- Welche Identitäten repräsentieren autorisierte Nutzer, Anwendungen und Service-Konten?
- Welche Business-Rollen benötigen Zugriff?
- Auf welcher Ebene soll Zugriff gewährt werden: Domain, Katalog, Schema, Tabelle, Zeile oder Spalte?
- Sollen Daten sichtbar, maskiert oder aggregiert werden?
- Wer genehmigt eine Anforderung?
- Wie lange soll der Zugriff gültig bleiben?
- Welche Ausnahmen sind zulässig?
- Wie wird der fortbestehende Bedarf überprüft?
- Welche Nachweise zeigen, dass die Richtlinie tatsächlich wirkt?

Die Richtlinie beschreibt die Absicht.

Die Kontrollumgebung setzt sie um.

> **Governance wird operativ, wenn sich Richtlinienabsicht bis zu Zugriffsentscheidung, technischer Durchsetzung, Review und Nachweis nachvollziehen lässt.**

Dieses Playbook stellt weder Richtlinien noch IAM-Systeme oder Plattformkontrollen infrage. Es betrachtet die mögliche Lücke zwischen dem Dokumentieren einer Regel und ihrer konsistenten Ausführung in einer sich laufend verändernden Datenlandschaft.

## Policy Governance und Access Governance hängen zusammen – sind aber nicht dasselbe

Die Begriffe überschneiden sich, lösen jedoch unterschiedliche Teile des Problems.

| Fähigkeit | Hauptzweck | Typische Fragen |
| --- | --- | --- |
| Policy Governance | Regeln und Verpflichtungen definieren, freigeben, kommunizieren und aktuell halten | Was soll erlaubt, verpflichtend, eingeschränkt oder überprüft werden? |
| Identity Governance | den Lifecycle von Mitarbeitenden, Partnern, Dienstleistern, Anwendungen und technischen Identitäten steuern | Wer oder was fordert Zugriff an, und ist diese Identität weiterhin gültig? |
| Access Governance | Berechtigungen anfordern, genehmigen, bereitstellen, überprüfen und entziehen | Wer soll auf welche Ressource zugreifen, zu welchem Zweck und für welchen Zeitraum? |
| Zugriffskontrolle | Berechtigungen und Einschränkungen technisch durchsetzen | Was darf diese Identität in diesem System tatsächlich sehen oder ausführen? |
| Datensicherheitskontrollen | sensible Inhalte durch Maskierung, Filterung, Verschlüsselung und verwandte Mechanismen schützen | Welche Datenwerte dürfen unter welchen Bedingungen sichtbar werden? |
| Monitoring & Detection | Nutzung, Anomalien, Richtlinienverstöße und Kontrollwirksamkeit beobachten | Wird Zugriff wie erwartet genutzt und wo entstehen Risiken? |
| Audit & Nachweise | Entscheidungen, Änderungen, Reviews und Durchsetzung nachvollziehbar dokumentieren | Kann die Organisation belegen, wer was wann und warum genehmigt hat? |
| Ausnahmemanagement | begründete Abweichungen von Standardrichtlinien steuern | Welche Ausnahme besteht, wer akzeptiert das Risiko und wann wird sie erneut geprüft? |

Ein Policy Repository kann die Absicht dokumentieren, ohne Zugriff technisch durchzusetzen.

Eine IAM-Plattform kann Identitäten verwalten, ohne die vollständige fachliche Bedeutung jedes Datenobjekts zu kennen.

Eine Datenplattform kann Berechtigungen durchsetzen, ohne zu wissen, ob der gewährte Zugriff noch dem aktuellen Geschäftsbedarf entspricht.

Ein Datenkatalog kann Klassifizierungen und Owner sichtbar machen, ohne selbst das System zu sein, das Berechtigungen provisioniert.

Der Nutzen entsteht, wenn diese Fähigkeiten durch ein klares Operating Model miteinander verbunden werden.

## Die Kette von der Richtlinie zur Kontrolle

Ein praktikabler Policy-Lifecycle lässt sich als fünf verbundene Stufen betrachten:

1. **Richtlinie definieren**  
   Absicht, Geltungsbereich, Verpflichtungen, verbotene Nutzung und erwartete Kontrollen werden freigegeben.

2. **Verantwortung zuweisen**  
   Owner, Stewards, Genehmiger, Reviewer und technische Kontrollverantwortliche werden benannt.

3. **Zugriff umsetzen**  
   Rollen, Privilegien, Filter, Maskierungen, Entitlements und Plattformkontrollen werden konfiguriert.

4. **Zugriff überprüfen**  
   Nutzung, fortbestehender Geschäftsbedarf, Rollenwechsel, Konflikte und Ausnahmen werden bewertet.

5. **Zugriff durchsetzen und verbessern**  
   Nicht mehr benötigte Berechtigungen werden entfernt, Verstöße behoben, Ausnahmen gesteuert und Richtlinien oder Kontrolldesign bei Bedarf angepasst.

Jede Stufe kann für sich ausgereift sein, während die End-to-End-Verbindung trotzdem unvollständig bleibt.

Beispiele:

- Eine Richtlinie existiert, aber niemand ist für die technische Umsetzung verantwortlich.
- Eine Rolle ist zugewiesen, aber die dahinterliegenden Berechtigungen unterscheiden sich je Plattform.
- Zugriff wird bereitgestellt, aber Ablauf- oder Review-Datum fehlen.
- Reviews werden abgeschlossen, aber das Ergebnis wird nicht im Zielsystem umgesetzt.
- Ausnahmen werden genehmigt, bleiben aber aktiv, nachdem der ursprüngliche Grund entfallen ist.
- Technische Kontrollen funktionieren, aber Nutzer verstehen nicht, warum Zugriff gewährt oder verweigert wurde.
- Nachweise liegen in mehreren Systemen, aber der vollständige Entscheidungsweg lässt sich nicht rekonstruieren.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-access-img1-de.png"
        alt="Lifecycle für Richtlinien- und Zugriffs-Governance von der Richtliniendefinition und Verantwortungszuweisung bis zu Umsetzung, Überprüfung und Durchsetzung einschließlich typischer Lücken zwischen Policy und Praxis"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine dokumentierte Richtlinie wird erst dann operativ, wenn Verantwortlichkeiten, technische Kontrollen, Reviews, Ausnahmen und Nachweise dauerhaft miteinander verbunden bleiben.
    </figcaption>
</figure>

## Häufig fehlt nicht die Kontrolle – sondern die Verbindung

Moderne Plattformen stellen viele Mechanismen für Zugriffskontrolle bereit.

Snowflake unterstützt rollenbasierte und objektbezogene Berechtigungsmodelle. Databricks Unity Catalog kombiniert Privilegien, Ownership, attributbasierte Richtlinien, Zeilenfilter, Spaltenmaskierung und Workspace-Einschränkungen. AWS Lake Formation unterstützt fein granulare und tagbasierte Kontrollen. Cloud-IAM-Plattformen bieten Rollen, Bedingungen, temporäre Berechtigungen und Access Analysis.

Die Herausforderung besteht deshalb selten darin, dass überhaupt keine technische Möglichkeit existiert.

Die Herausforderung besteht vielmehr in den Entscheidungen:

- Welche Kontrolle setzt welche Richtlinie um?
- An welcher Stelle soll die Kontrolle durchgesetzt werden?
- Wie bleibt dieselbe Absicht über mehrere Plattformen hinweg konsistent?
- Wer verantwortet die Abbildung von Policy auf technische Umsetzung?
- Wie werden Ausnahmen dargestellt?
- Wie werden Änderungen getestet?
- Wie entstehen Nachweise?
- Wie werden veraltete Berechtigungen entfernt?

Eine Klassifizierung wie `Vertraulich – Kunden-PII` kann beispielsweise Einfluss haben auf:

- Sichtbarkeit im Katalog,
- Zugriffsanforderungen für Datenprodukte,
- Warehouse-Berechtigungen,
- Zeilenfilter,
- Spaltenmaskierung,
- Report-Level Security,
- Exportberechtigungen,
- API-Zugriff,
- Machine-Learning-Workspaces,
- Berechtigungen von Service-Konten,
- Aufbewahrungs- und Löschkontrollen.

Die Klassifizierung selbst ist noch keine Kontrolle.

Sie ist Kontext, der eine oder mehrere Kontrollen steuern sollte.

> **Ein gesteuertes Label wird dann wertvoll, wenn seine Bedeutung konsistent in operatives Verhalten übersetzt wird.**

## Von der Identität zur Zugriffskontrolle

Access Governance beginnt, bevor eine Berechtigung vergeben wird.

Sie beginnt mit der Identität.

Eine Organisation muss möglicherweise folgende Identitäten steuern:

- Mitarbeitende,
- Führungskräfte,
- externe Partner,
- Dienstleister,
- temporäre Kräfte,
- Gäste,
- Service-Konten,
- Anwendungen,
- Automatisierungsidentitäten,
- Empfänger von Data Shares,
- KI-Agenten und andere Machine Identities.

Die Zugriffsentscheidung hängt anschließend von Kontext ab, zum Beispiel:

- Business-Rolle,
- Organisationseinheit,
- Projekt oder Domain,
- geografischer Standort,
- Beschäftigungsstatus,
- Geräte- oder Netzwerkkontext,
- Datenklassifizierung,
- Nutzungszweck,
- beantragte Dauer,
- Risikostufe,
- Anforderungen zur Funktionstrennung,
- vertragliche oder regulatorische Verpflichtungen.

Daraus entsteht ein Lifecycle:

Identität  
→ Rolle, Gruppe und Attribute  
→ Zugriffsanforderung oder automatisiertes Entitlement  
→ Policy- und Risikoprüfung  
→ Genehmigung oder automatisierte Entscheidung  
→ Bereitstellung  
→ Nutzungsmonitoring  
→ Review und Rezertifizierung  
→ Anpassung oder Entzug

Der Prozess sollte nicht davon ausgehen, dass jede Zugriffsanforderung eine lange manuelle Genehmigungskette benötigt.

Zugriffe mit geringem Risiko und klaren Regeln können häufig innerhalb freigegebener Leitplanken automatisiert werden.

Kritische oder außergewöhnliche Zugriffe benötigen möglicherweise zusätzliche Prüfung, begrenzte Laufzeit und stärkere Nachweise.

Das Ziel ist proportionale Governance:

> **Der Kontrollaufwand sollte Sensitivität, Umfang, Dauer und potenzielle Auswirkung des Zugriffs widerspiegeln.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-access-img3-de.png"
        alt="Lifecycle von der Identität zur Zugriffskontrolle mit Identitäten, Rollen, Zugriffsanforderungen, Entscheidungen, Bereitstellung, Monitoring, Governance-Kontrollen, Risiken und Ergebnissen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Wirksame Access Governance verbindet Identität, fachlichen Bedarf, Policy-Prüfung, technische Bereitstellung, kontinuierliches Monitoring und rechtzeitigen Entzug.
    </figcaption>
</figure>

## Least Privilege ist eine Richtung – keine einmalige Konfiguration

Least Privilege ist ein breit anerkannter Sicherheitsgrundsatz: Es werden nur die Berechtigungen vergeben, die für die vorgesehene Aufgabe benötigt werden.

In der Praxis lässt sich Least Privilege kaum allein durch ein initiales Design dauerhaft erreichen.

Zugriffsanforderungen verändern sich, weil:

- Mitarbeitende Teams wechseln,
- Projekte enden,
- Verantwortlichkeiten wachsen oder kleiner werden,
- Anwendungen ersetzt werden,
- Datenprodukte weiterentwickelt werden,
- Notfallzugriffe vergeben werden,
- temporäre Arbeit dauerhaft wird,
- Service-Konten zusätzliche Aufgaben erhalten,
- Rollendefinitionen mit der Zeit breiter werden.

Eine anfangs angemessene Berechtigung kann später zu weitreichend sein.

Deshalb benötigt Least Privilege einen Lifecycle:

Definieren  
→ Gewähren  
→ Beobachten  
→ Überprüfen  
→ Reduzieren oder entziehen  
→ Neu bewerten

Nutzungsevidenz kann Reviewern helfen, aktiv benötigte Zugriffe von Berechtigungen zu unterscheiden, die möglicherweise nicht mehr verwendet werden.

Nutzung allein reicht jedoch nicht aus.

Eine Berechtigung kann verwendet und trotzdem unangemessen sein.

Eine Berechtigung kann über einen ruhigen Zeitraum ungenutzt bleiben und dennoch für einen gültigen saisonalen oder Notfallprozess erforderlich sein.

Fachlicher Kontext bleibt notwendig.

## Rollen helfen bei der Skalierung – können Komplexität aber auch verbergen

Rollenbasierte Zugriffskontrolle ist hilfreich, weil Berechtigungen Rollen zugewiesen werden können, statt sie für jeden Nutzer einzeln zu verwalten.

Das Rollendesign erzeugt jedoch eigene Governance-Fragen:

- Welche fachliche Verantwortung bildet die Rolle ab?
- Welche technischen Berechtigungen enthält sie?
- Sind die Berechtigungen über Umgebungen hinweg konsistent?
- Ist die Rolle zu breit?
- Kombiniert sie widersprüchliche Aufgaben?
- Wer verantwortet die Rollendefinition?
- Wer darf die Rolle beantragen oder genehmigen?
- Wie oft wird die Rolle überprüft?
- Sind geerbte Berechtigungen sichtbar?
- Können Nutzer verstehen, was sie durch die Rolle erhalten?

Mit der Zeit können entstehen:

- Rollenexplosion – zu viele sehr eng definierte Rollen,
- Rollenakkumulation – Nutzer behalten Rollen aus früheren Verantwortungsbereichen,
- Rolleninflation – bestehende Rollen erhalten immer mehr Berechtigungen, weil ein neues Modell aufwendig erscheint,
- verschachtelte Komplexität – Gruppen und Vererbung erschweren die Erklärung effektiver Berechtigungen.

Die Antwort muss nicht sein, Rollen grundsätzlich aufzugeben.

Möglicherweise sollten Rollen mit Attributen, Bedingungen, Zeitbegrenzungen, Tags und klarer Ownership kombiniert werden.

## RBAC, ABAC und datenbezogene Kontrollen lösen unterschiedliche Aufgaben

Unterschiedliche Zugriffskontrollmodelle können sich ergänzen.

| Kontrollmodell | Stärke | Governance-Frage |
| --- | --- | --- |
| Role-Based Access Control (RBAC) | skalierbare Zuweisung über Job- oder Verantwortungsrollen | Sind Rollen verständlich, aktuell und angemessen zugeschnitten? |
| Attribute-Based Access Control (ABAC) | dynamische Entscheidungen anhand von Identitäts-, Ressourcen- und Kontextattributen | Sind Attribute gesteuert, zuverlässig und konsistent interpretiert? |
| Policy-Based Access Control | zentrale Regeln bewerten Zugriff anhand definierter Policy-Logik | Wer verantwortet die Policy-Logik und wie wird sie getestet? |
| Row-Level Security | begrenzt, welche Datensätze ein Nutzer sehen kann | Ist die Filterlogik über Tools und Anwendungsfälle hinweg konsistent? |
| Column-Level Security | beschränkt Zugriff auf sensible Felder | Sind sensible Spalten vollständig klassifiziert und aktuell? |
| Dynamische Maskierung | zeigt abhängig vom Kontext veränderte oder verborgene Werte | Verstehen Nutzer, ob sie Originalwerte, maskierte oder aggregierte Werte sehen? |
| Zweck- oder einwilligungsbasierte Kontrollen | beschränken Nutzung anhand genehmigten Zwecks oder rechtlicher Grundlage | Lässt sich der Zweck über den vollständigen Datenpfad darstellen und durchsetzen? |
| Zeitlich begrenzter Zugriff | entzieht Zugriff automatisch nach einem definierten Zeitraum | Entspricht die Dauer dem Geschäftsbedarf und kann sie mit Nachweis verlängert werden? |

Kein einzelnes Modell löst jedes Szenario.

Die wichtige Governance-Frage ist, ob die Organisation erklären kann, warum ein bestimmtes Kontrollmodell gewählt wurde und wie es die beabsichtigte Richtlinie umsetzt.

## Zugriff auf Daten ist mehr als Zugriff auf Tabellen

Aus Sicht der Data Governance muss der vollständige Nutzungspfad betrachtet werden.

Daten können genutzt werden über:

- Warehouse-Abfragen,
- Lakehouse-Notebooks,
- semantische Modelle,
- BI-Dashboards,
- Report-Abonnements,
- Exporte,
- Spreadsheets,
- APIs,
- Anwendungen,
- Reverse ETL,
- Data Shares,
- Machine-Learning-Features,
- KI-Assistenten und Agenten.

Ein Dashboard kann eingeschränkt sein, während das zugrunde liegende semantische Modell breit zugänglich ist.

Ein Nutzer besitzt möglicherweise keinen direkten Tabellenzugriff, kann aber detaillierte Datensätze aus einem Report exportieren.

Eine im Warehouse maskierte Spalte kann in einem replizierten Downstream-System unmaskiert erscheinen.

Eine Anwendung kann ein Service-Konto verwenden, dessen Berechtigungen über den Bedarf einzelner Nutzer hinausgehen.

Ein Data Share kann fortbestehen, nachdem die ursprüngliche Zusammenarbeit beendet wurde.

Die relevante Frage lautet deshalb nicht nur:

> *Wer kann diese Tabelle abfragen?*

Sondern auch:

> *Über welche Kanäle können diese Informationen angesehen, heruntergeladen, kombiniert, weitergegeben oder von automatisierten Systemen genutzt werden?*

## Access Reviews sind Entscheidungen – keine Checkbox-Übungen

Regelmäßige Access Reviews können dabei helfen zu bestätigen, ob Nutzer, Gruppen, Gäste, Anwendungen und privilegierte Rollen weiterhin Zugriff benötigen.

Ein Review wird dann wertvoll, wenn der Reviewer ausreichend Kontext für eine Entscheidung besitzt.

Ein schwacher Review zeigt möglicherweise nur:

`Nutzer: Maria Beispiel`  
`Gruppe: FIN_DATA_READ`  
`Entscheidung: Genehmigen / Ablehnen`

Ein stärkerer Review kann zusätzlich zeigen:

- fachlichen Zweck,
- Rolle und Abteilung,
- Datensensitivität,
- beantragten Umfang,
- ursprünglichen Genehmiger,
- Alter der Berechtigung,
- letzte Nutzung oder Nutzungsmuster,
- zugehöriges Projekt oder Vertragsverhältnis,
- bekannte Konflikte bei der Funktionstrennung,
- Ablaufdatum,
- Empfehlung des Owners,
- Auswirkung eines Entzugs.

Das Ziel ist nicht, jedes Review-Formular möglichst groß und komplex zu machen.

Es geht um den minimal notwendigen Kontext für eine verantwortungsvolle Entscheidung.

Review Fatigue ist ein operatives Risiko.

Erhalten Reviewer Hunderte schlecht erklärte Berechtigungen, kann die Freigabe zu einer routinemäßigen Bestätigung statt zu einer wirksamen Kontrolle werden.

Priorisierung kann helfen:

- kritische Zugriffe zuerst,
- privilegierte Zugriffe häufiger,
- risikoreiche Ausnahmen separat,
- Standardzugriffe mit geringem Risiko über Automatisierung und Stichproben,
- ungenutzte oder auffällige Berechtigungen hervorheben,
- Reviews an Personen weiterleiten, die den fachlichen Bedarf verstehen.

## Ausnahmen sollten als eigenständige Governance-Objekte behandelt werden

Kein Policy-Modell deckt jede legitime Geschäftssituation vollständig ab.

Ausnahmen können notwendig sein wegen:

- dringender Incident Response,
- Migrationsprojekten,
- regulatorischen Untersuchungen,
- temporärer bereichsübergreifender Zusammenarbeit,
- Vendor Support,
- Einschränkungen von Legacy-Systemen,
- Business Continuity,
- noch nicht gelösten technischen Begrenzungen.

Die Existenz einer Ausnahme ist nicht automatisch ein Governance-Fehler.

Das Risiko entsteht durch eine ungesteuerte Ausnahme.

Eine gesteuerte Ausnahme sollte üblicherweise enthalten:

| Kontext | Beispiel |
| --- | --- |
| Betroffene Richtlinie oder Kontrolle | Richtlinie für Zugriff auf Kunden-PII |
| Fachliche Begründung | Temporäre Validierung einer Migration |
| Scope | benanntes Projektteam; nur ausgewählte Datensätze |
| Risikobewertung | begrenztes Exportrisiko; überwachte Umgebung |
| Kompensierende Kontrollen | Logging, eingeschränkter Workspace, kein externes Teilen |
| Genehmiger | Data Owner und Security Owner |
| Startdatum | 15. Juli 2026 |
| Ablaufdatum | 30. September 2026 |
| Review-Trigger | Projektmeilenstein oder Scope-Änderung |
| Nachweise | Freigabe, Zugriffslogs und Abschlussbestätigung |

Eine Ausnahme ohne Enddatum kann unbemerkt zu einer dauerhaften alternativen Richtlinie werden.

> **Temporärer Zugriff benötigt einen expliziten Lifecycle – keine implizite Erinnerung.**

## Policy Owner, Data Owner und technischer Owner haben nicht dieselbe Aufgabe

Policy- und Access-Governance umfassen meist mehrere Verantwortlichkeiten.

| Rolle | Hauptverantwortung |
| --- | --- |
| Policy Owner | definiert Absicht, Geltungsbereich, Verpflichtungen, Ausnahmen und Review-Erwartungen |
| Data Owner | entscheidet über akzeptable Nutzung, Risikohaltung und Zugriffsgrundsätze für eine Data Domain oder ein Datenprodukt |
| Data Steward | pflegt fachlichen Kontext, Klassifizierungen, Zugriffshinweise und koordiniert Probleme |
| Identity- / IAM-Owner | steuert Identity Lifecycle, Entitlement-Prozesse, Rollen und Governance-Kontrollen |
| Platform Owner | implementiert und betreibt plattformspezifische Berechtigungen, Filter, Maskierung und Audit-Funktionen |
| Application- / Report-Owner | verantwortet Zugriff und Downstream-Verhalten in der Nutzungsschicht |
| Security / Privacy | liefert Kontrollanforderungen, Risikoleitlinien, Monitoring und Assurance |
| Genehmiger | bewertet eine konkrete Anforderung oder Ausnahme innerhalb delegierter Befugnisse |
| Reviewer | bestätigt fortbestehenden Bedarf und Angemessenheit vorhandener Zugriffe |
| Audit / Assurance | bewertet, ob Kontrollen wie erwartet gestaltet, betrieben und nachgewiesen werden |

In kleineren Organisationen kann eine Person mehrere Rollen übernehmen.

Entscheidend ist Klarheit:

- Wer definiert die Regel?
- Wer entscheidet über Zugriff?
- Wer setzt die Kontrolle technisch um?
- Wer prüft den fortbestehenden Bedarf?
- Wer löst Konflikte?
- Wer bestätigt die Wirksamkeit?

Fehlt diese Abgrenzung, kann ein Zugriffsproblem zwischen Teams weitergereicht werden, ohne einen klaren Verantwortlichen zu finden.

## Business-freundliche Access Governance ist wichtig

Access Governance muss Daten schützen, ohne angemessenen Zugriff unnötig zu erschweren.

Ist der freigegebene Weg langsam, unklar oder zu technisch, suchen Nutzer möglicherweise alternative Wege:

- sie beantragen breitere Rollen als benötigt,
- bitten Kollegen um Datenexporte,
- erstellen lokale Kopien,
- teilen Zugangsdaten oder Dateien außerhalb des vorgesehenen Prozesses,
- nutzen alte Berechtigungen weiter, weil neue Zugriffe schwer zu erhalten sind.

Das rechtfertigt keine schwachen Kontrollen.

Es unterstützt ein Usability-Prinzip:

> **Der gesteuerte Weg sollte leichter verständlich sein als der Workaround.**

Eine nützliche Zugriffsanforderung kann erklären:

- was das Datenprodukt enthält,
- welche Klassifizierung gilt,
- welche Nutzungen vorgesehen oder untersagt sind,
- welche Zugriffsstufen verfügbar sind,
- welcher Genehmigungsweg erwartet wird,
- wie lange die Bearbeitung voraussichtlich dauert,
- wer Owner und Ansprechpartner ist,
- ob Zugriff zeitlich begrenzt ist,
- was protokolliert oder überprüft wird.

Wo möglich, sollte Fachsprache vor technischen Rollennamen stehen.

Statt Nutzer zwischen kryptischen Gruppen wählen zu lassen, kann die Erfahrung mit dem Zweck beginnen:

> *Ich benötige aggregierten Kundenumsatz für die monatliche regionale Planung.*

Das System kann diesen Zweck anschließend dem passenden Datenprodukt, der geeigneten Rolle und den erforderlichen Kontrollen zuordnen.

## Policy-as-Code kann Konsistenz verbessern – ersetzt aber keine Governance

Policy-as-Code und Infrastructure-as-Code können Zugriffsregeln versionierbar, testbar, reviewbar und wiederholbar machen.

Das kann verbessern:

- Konsistenz zwischen Umgebungen,
- Peer Review,
- automatisierte Validierung,
- Deployment-Nachvollziehbarkeit,
- Rollback,
- Drift-Erkennung,
- Nachweiserzeugung.

Policy-as-Code benötigt trotzdem Entscheidungen zu:

- Richtlinienabsicht,
- fachlicher Terminologie,
- Ownership,
- Ausnahmen,
- Risikoakzeptanz,
- Testszenarien,
- Review-Zyklen.

Eine technisch gültige Regel kann trotzdem die falsche fachliche Interpretation umsetzen.

Code verbessert die Disziplin der Ausführung.

Er ersetzt keine Verantwortung.

## Effektiver Zugriff ist wichtiger als konfigurierte Berechtigung

In komplexen Umgebungen können Zugriffe vererbt werden über:

- verschachtelte Gruppen,
- Rollenhierarchien,
- direkte Grants,
- Objekt-Ownership,
- sekundäre Rollen,
- Workspace-Berechtigungen,
- Application Entitlements,
- gemeinsam genutzte Credentials,
- Service Principals,
- delegierte Administration,
- Data Shares.

Die konfigurierte Berechtigung ist nur ein Teil des Gesamtbilds.

Organisationen müssen auch den effektiven Zugriff verstehen:

> *Worauf kann diese Identität tatsächlich zugreifen, nachdem alle Grants, Vererbungen, Bedingungen, Filter und Ausnahmen ausgewertet wurden?*

Das ist wichtig für:

- Access Reviews,
- Incident-Untersuchungen,
- Policy-Validierung,
- Analyse von Funktionstrennung,
- Bewertung von Datenexposition,
- Audit-Nachweise.

Die Analyse effektiver Zugriffe sollte technische Identitäten ebenso einbeziehen wie Menschen.

## Nachweise sollten im Prozess entstehen

Audit-Evidenz lässt sich leichter bereitstellen, wenn Nachvollziehbarkeit bereits im normalen Betrieb erfasst wird.

Nützliche Nachweise können umfassen:

- freigegebene Policy-Version,
- Zuordnung zu Kontrollen,
- Zustand von Identität und Rolle,
- Zugriffsanforderung und fachliche Begründung,
- Genehmigungsentscheidung,
- Provisionierungsergebnis,
- Ergebnis der Policy-Auswertung,
- Ausnahmegenehmigung,
- Review- und Rezertifizierungsentscheidung,
- Nutzung oder Monitoring-Evidenz,
- Bestätigung von Entzug oder Ablauf,
- Änderungshistorie.

Das Ziel ist nicht, jedes Ereignis unbegrenzt aufzubewahren.

Nachweise sollten Risiko, Richtlinie und regulatorischem Bedarf entsprechen.

Der stärkere Grundsatz lautet:

> **Eine Kontrolle ist leichter vertrauenswürdig, wenn sich ihr Entscheidungsweg rekonstruieren lässt.**

## Wirkung von Policy- und Access-Governance messen

Die Anzahl von Richtlinien oder Rollen zeigt noch nicht, ob Governance wirksam ist.

Mögliche Kennzahlen sind:

| Kennzahl | Mögliche Aussage |
| --- | --- |
| Policy-to-Control Coverage | ob kritische Policy-Anforderungen mit umgesetzten Kontrollen verbunden sind |
| Classification-to-Control Coverage | ob sensible Assets den erwarteten Schutz erhalten |
| Bearbeitungszeit von Zugriffsanforderungen | ob angemessener Zugriff effizient bereitgestellt wird |
| Anteil automatisierter Genehmigungen | ob standardisierte Zugriffe mit geringem Risiko innerhalb von Leitplanken verarbeitet werden |
| Festgestellte übermäßige Berechtigungen | wo Berechtigungen definierten Bedarf oder Richtlinie überschreiten |
| Anteil inaktiver Zugriffe | wie viele Berechtigungen möglicherweise ungenutzt sind und geprüft werden sollten |
| Verwaiste Berechtigungen | Zugriffe inaktiver Identitäten, beendeter Projekte oder ohne Owner |
| Review Completion | ob erforderliche Access Reviews fristgerecht abgeschlossen werden |
| Qualität von Review-Entscheidungen | ob Reviewer Berechtigungen tatsächlich ändern oder entfernen, statt alles standardmäßig zu bestätigen |
| Revocation Latency | wie schnell veraltete oder nicht konforme Zugriffe entfernt werden |
| Alter von Ausnahmen | ob temporäre Ausnahmen über den vorgesehenen Zeitraum hinaus offen bleiben |
| Entfernung abgelaufener Zugriffe | ob zeitlich begrenzte Berechtigungen tatsächlich entzogen werden |
| Policy Drift | wo die technische Umsetzung von der freigegebenen Absicht abweicht |
| Vollständigkeit der Nachweise | ob Entscheidungen und Durchsetzung rekonstruiert werden können |
| User Experience | ob Nutzer verstehen, wie sie Zugriff anfordern, nutzen und wieder abgeben |

Kennzahlen benötigen Interpretation.

Eine hohe Ablehnungsquote ist nicht automatisch gute Governance.

Eine sehr niedrige Ablehnungsquote ist nicht automatisch schwache Governance.

Das Ergebnis ist entscheidend:

> **Erhalten die richtigen Identitäten den richtigen Zugriff für einen begründeten Zweck, für einen angemessenen Zeitraum, mit verständlichen Kontrollen und verlässlichen Nachweisen?**

## Häufige Anti-Patterns

### Policy nur als Dokumentation

Die Richtlinie ist freigegeben und veröffentlicht, aber keine Zuordnung zeigt, welche technischen Kontrollen sie umsetzen.

### Historisch angesammelte Berechtigungen

Nutzer behalten Rollen und Berechtigungen aus früheren Teams, Projekten und Verantwortungsbereichen.

### Breite Rollen als Standardlösung

Eine große Rolle wird vergeben, weil dies schneller erscheint, als den minimal notwendigen Zugriff zu identifizieren.

### Manuelle Genehmigung ohne nützlichen Kontext

Genehmiger sehen technische Gruppennamen, aber nicht Geschäftsgrund, Sensitivität, Dauer oder Risiko.

### Review als Massenbestätigung

Hunderte Berechtigungen werden genehmigt, weil Reviewern Kontext oder Zeit fehlen.

### Dauerhafter temporärer Zugriff

Notfall- oder Projektzugriff besitzt kein Ablaufdatum, keinen Owner und keine Abschlussprüfung.

### Menschen werden gesteuert, Maschinen nicht

Service-Konten, Anwendungen und Automatisierungen behalten breite Zugriffe ohne vergleichbare Reviews.

### Kontrolle nur auf einer Ebene

Das Warehouse ist geschützt, aber Exporte, semantische Modelle, Reports, APIs oder Downstream-Kopien eröffnen alternative Zugriffspfade.

### Klassifizierung ohne Durchsetzung

Sensible Daten sind korrekt markiert, aber das Label beeinflusst weder Zugriff noch Maskierung, Monitoring oder Review.

### Sicherheit ohne Nutzbarkeit

Der gesteuerte Zugriffsweg ist so langsam oder schwierig, dass Nutzer informelle Alternativen schaffen.

### Tool Ownership ohne Policy Ownership

Das IAM- oder Plattformteam betreibt die Technologie, kann aber nicht die fachliche Bedeutung akzeptablen Zugriffs entscheiden.

## Ein praktikables Operating Model

Ein möglicher Ansatz:

1. **Policy-Absicht in Fachsprache definieren**  
   Zweck, Geltungsbereich, verbotene Nutzung, erwartete Kontrollen, Ausnahmeregeln und Review-Anforderungen festlegen.

2. **Kritische Daten und Zugriffspfade identifizieren**  
   Klassifizierungen, Datenprodukte, semantische Modelle, Reports, Exporte, APIs und technische Konsumenten verbinden.

3. **Verantwortliche Rollen zuweisen**  
   Policy Owner, Data Owner, Stewards, Genehmiger, IAM Owner, Platform Owner und Reviewer benennen.

4. **Policy in Kontrollmuster übersetzen**  
   Definieren, wann RBAC, ABAC, Maskierung, Zeilenfilter, zeitlich begrenzter Zugriff, Genehmigung oder automatisierte Entscheidungen eingesetzt werden.

5. **Verständliche Zugriffswege schaffen**  
   Nutzer über Geschäftszweck und Datenprodukt-Kontext anfordern lassen, nicht nur über technische Gruppen.

6. **Standardzugriffe innerhalb von Leitplanken automatisieren**  
   Manuelle Arbeit für wiederkehrende, risikoarme Szenarien reduzieren und Nachvollziehbarkeit erhalten.

7. **Ausnahmen explizit behandeln**  
   Begründung, Scope, Owner, kompensierende Kontrollen, Ablauf und Review verlangen.

8. **Effektiven Zugriff und Nutzung überwachen**  
   Übermäßige, inaktive, verwaiste, auffällige und policy-inkonsistente Zugriffe erkennen.

9. **Proportional überprüfen**  
   Menschliche Aufmerksamkeit auf sensible, privilegierte, ungewöhnliche oder wirkungsstarke Zugriffe konzentrieren.

10. **Review-Ergebnisse umsetzen**  
   Sicherstellen, dass Ablehnung, Entzug, Reduzierung oder Ablauf im Zielsystem ankommen.

11. **Nachweise von Anfang an mitdenken**  
   Entscheidungsweg und Kontrollergebnis bewahren, ohne später alles manuell rekonstruieren zu müssen.

12. **Policy und Kontrollen gemeinsam verbessern**  
   Incidents, Feedback, Zugriffsmuster und Ausnahmen nutzen, um sowohl Absicht als auch Umsetzung weiterzuentwickeln.

## Praktische Fragen für Teams

Für ein kritisches Datenprodukt, eine Richtlinie oder einen Zugriffspfad:

1. **Welche freigegebene Richtlinie steuert diesen Zugriff?**
2. **Können wir die Policy-Anforderung bis zu der technischen Kontrolle verfolgen, die sie durchsetzt?**
3. **Sind Owner, Steward, Genehmiger und technischer Kontrollverantwortlicher klar benannt?**
4. **Verstehen Nutzer in Fachsprache, welchen Zugriff sie beantragen?**
5. **Ist der beantragte Umfang auf das Notwendige begrenzt?**
6. **Ist die Dauer angemessen und kann temporärer Zugriff automatisch auslaufen?**
7. **Sind Zeilen-, Spalten-, Maskierungs-, Export- und Downstream-Kontrollen aufeinander abgestimmt?**
8. **Erhalten technische Identitäten dieselbe Governance-Aufmerksamkeit wie menschliche Identitäten?**
9. **Sehen Reviewer Geschäftsgrund, Sensitivität, Nutzung und Risiko?**
10. **Sind Ausnahmen sichtbar, zeitlich begrenzt und überprüft?**
11. **Können wir effektiven Zugriff nach Vererbung und allen Policy-Bedingungen bestimmen?**
12. **Werden Review-Entscheidungen tatsächlich auf der Zielplattform umgesetzt?**
13. **Lässt sich der vollständige Entscheidungs- und Durchsetzungsweg für Audits rekonstruieren?**
14. **Wissen Nutzer, warum Zugriff gewährt, verweigert, maskiert oder entzogen wurde?**
15. **Ermöglicht der gesteuerte Prozess legitime Arbeit, ohne Workarounds zu fördern?**

Diese Fragen verlangen nicht, dass jede Organisation dasselbe IAM-Produkt, dieselbe Policy Engine oder dasselbe Genehmigungsmodell verwendet.

Sie prüfen, ob dokumentierte Absicht mit operativer Realität verbunden bleibt.

## Fazit

Policy- und Access-Governance sind keine fehlenden Technologien.

Moderne Identity-Plattformen, Datenplattformen und Security-Tools bieten bereits leistungsfähige Funktionen für Rollen, Attribute, Privilegien, Maskierung, Genehmigungen, Reviews, Monitoring und Nachweise.

Das mögliche fehlende Teil ist die Verbindung zwischen ihnen.

Eine Richtlinie setzt sich nicht selbst durch.

Eine Klassifizierung schützt nicht automatisch jede Downstream-Kopie.

Eine Rollenzuweisung beweist keinen fortbestehenden Geschäftsbedarf.

Ein Access Review erzeugt keinen Nutzen, wenn der Reviewer keinen Kontext besitzt oder das Ergebnis nie umgesetzt wird.

Eine technische Kontrolle belegt keine Policy-Wirksamkeit, solange Zweck, Ownership und Nachweise nicht verständlich sind.

Die operative Kette lautet:

> **Policy-Absicht → gesteuerte Identität → begründeter Zugriffsbedarf → Entscheidung → technische Durchsetzung → Monitoring → Review → Entzug oder Verlängerung → Nachweis → Verbesserung**

Die zentrale Frage ist deshalb:

> **Ist die Richtlinie nur dokumentiert – oder wird sie konsistent umgesetzt?**

Vielleicht bedeutet vertrauenswürdiger Zugriff nicht, dass alle dieselben Berechtigungen besitzen.

Vielleicht bedeutet er, dass sich Zugriff erklären lässt:

- wer oder was Zugriff besitzt,
- auf welche Daten,
- für welchen Zweck,
- unter welcher Richtlinie,
- mit welchen Einschränkungen,
- für welchen Zeitraum,
- von wem genehmigt,
- wann überprüft,
- und wann wieder entzogen.

## Weiterführende Ressourcen

- [NIST SP 800-207: Zero Trust Architecture](https://csrc.nist.gov/pubs/sp/800/207/final)
- [Microsoft Learn: Was sind Microsoft Entra Access Reviews?](https://learn.microsoft.com/en-us/entra/id-governance/access-reviews-overview)
- [Microsoft Learn: Bereitstellung von Microsoft Entra Access Reviews planen](https://learn.microsoft.com/en-us/entra/id-governance/deploy-access-reviews)
- [Microsoft Learn: Data Governance mit Microsoft Purview](https://learn.microsoft.com/en-us/purview/data-governance-overview)
- [Microsoft Learn: Data-Governance-Rollen und -Berechtigungen in Microsoft Purview](https://learn.microsoft.com/en-us/purview/data-governance-roles-permissions)
- [AWS Documentation: Security Best Practices in IAM](https://docs.aws.amazon.com/IAM/latest/UserGuide/best-practices.html)
- [AWS Documentation: Policies and Permissions in IAM](https://docs.aws.amazon.com/IAM/latest/UserGuide/access_policies.html)
- [AWS Documentation: Lake Formation Tag-Based Access Control](https://docs.aws.amazon.com/lake-formation/latest/dg/tag-based-access-control.html)
- [Google Cloud Documentation: IAM sicher verwenden](https://cloud.google.com/iam/docs/using-iam-securely)
- [Google Cloud Documentation: Privileged Access Manager Overview](https://cloud.google.com/iam/docs/pam-overview)
- [Snowflake Documentation: Overview of Access Control](https://docs.snowflake.com/en/user-guide/security-access-control-overview)
- [Snowflake Documentation: Access Control Best Practices](https://docs.snowflake.com/en/user-guide/security-access-control-considerations)
- [Databricks Documentation: Access Control in Unity Catalog](https://docs.databricks.com/aws/en/data-governance/unity-catalog/access-control/)
- [Databricks Documentation: Unity Catalog Permissions Model Concepts](https://docs.databricks.com/aws/en/data-governance/unity-catalog/access-control/permissions-concepts)
