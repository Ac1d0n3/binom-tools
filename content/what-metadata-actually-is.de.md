---
title: Was Metadaten wirklich sind — Jenseits von Namen, Datentypen und Beschreibungen
description: Eine praxisnahe Grundlage für technische, fachliche, operative, Governance-, Sicherheits-, Qualitäts-, Nutzungs-, semantische und AI-Metadaten als gemeinsamen Kontext für vertrauenswürdige Daten.
category: Data Governance
tags:
  - metadata
  - metadata-governance
  - data-catalog
  - data-lineage
  - data-quality
  - data-security
  - semantic-layer
  - active-metadata
  - data-observability
  - ai-governance
  - rag
  - data-products
order: -1
author: Thomas Lindackers
hero: images/playbooks/what-metadata-actually-is-hero.png
---

## Metadaten werden häufig auf ihren kleinsten sichtbaren Teil reduziert

Wenn Teams über Metadaten sprechen, meinen sie häufig die Felder, die am einfachsten sichtbar sind:

- Tabellenname
- Feldname
- Datentyp
- Beschreibung
- eventuell einen Owner oder Tag

Diese Angaben sind nützlich. Sie beschreiben jedoch nur einen kleinen Teil des Kontexts, der erforderlich ist, um ein Datenobjekt zu verstehen und kontrolliert zu nutzen.

Betrachten wir eine Tabelle mit dem Namen `sales_order_line`.

Ihr Schema kann zeigen, dass `net_amount` ein Dezimalwert und `customer_id` ein String ist. Daraus ist aber noch nicht ersichtlich:

- was ein einzelner Datensatz repräsentiert
- ob stornierte Auftragspositionen enthalten sind
- welches System autoritativ ist
- wie häufig die Tabelle aktualisiert wird
- ob der letzte Ladevorgang erfolgreich war
- ob `customer_id` als sensibel eingestuft wird
- wer die fachliche Bedeutung verantwortet
- welche Qualitätsregeln erfüllt sein müssen
- welche Reports, Modelle oder Anwendungen davon abhängen
- ob das Datenobjekt für AI-Training oder Retrieval verwendet werden darf
- ob seine Definitionen noch der aktuellen Geschäftsrealität entsprechen

Ein technisch korrektes Schema kann daher gleichzeitig operativ unzuverlässig, semantisch mehrdeutig und für bestimmte Verwendungen ungeeignet sein.

> **Metadaten sind der gemeinsame Kontext, der erklärt, was ein Datenobjekt ist, wie es entstanden ist, wie es sich verhält, wer es verwenden darf und ob ihm vertraut werden kann.**

Diese Definition ist umfassender als ein Datenkatalog. Ein Katalog kann Metadaten speichern oder darstellen. Metadaten entstehen und existieren jedoch ebenso in Quellsystemen, Datenbankkatalogen, Transformationscode, Orchestrierungsprotokollen, Sicherheitsrichtlinien, Qualitätsergebnissen, BI-Anwendungen, Zugriffsprotokollen, Modellregistern und operativen Prozessen.

## Ein Datenobjekt benötigt mehrere Metadatenperspektiven

Dasselbe Datenobjekt `Sales Order Line` kann aus mehreren berechtigten Perspektiven beschrieben werden. Diese Perspektiven sollten nicht zu voneinander getrennten Katalogen werden. Sie sind unterschiedliche Bestandteile eines gemeinsamen Metadatenprofils.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/what-metadata-actually-is-img1-de.png"
        alt="Ein zentrales Sales-Order-Line-Datenobjekt mit technischen, fachlichen, operativen, Governance-, Sicherheits-, Qualitäts-, Nutzungs- und AI-Metadaten"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Namen und Datentypen bilden nur eine Metadatengruppe. Ein vertrauenswürdiges Datenobjekt verbindet technische Struktur mit Bedeutung, Ownership, Laufzeitevidenz, Kontrollen, Qualität, Nutzung und AI-Kontext.
    </figcaption>
</figure>

### Technische Metadaten

Technische Metadaten beschreiben die physische und logische Struktur von Daten.

Typische Beispiele sind:

- System-, Datenbank-, Schema-, Tabellen- und Feldnamen
- physische und logische Datentypen
- Nullability, Keys und Constraints
- Partitionen, Clustering und Indizes
- Dateiformate und Speicherorte
- Modellabhängigkeiten
- Transformationsausdrücke
- Source-to-Target-Mappings
- Schemaversionen

Für `sales_order_line` können technische Metadaten festhalten, dass ein Datensatz `order_id`, `order_line_id`, `product_id`, `quantity`, `currency_code` und `net_amount` enthält und dass die Kombination aus `order_id` und `order_line_id` eindeutig sein soll.

Technische Metadaten erklären, wie das Datenobjekt repräsentiert wird. Sie erklären nicht automatisch, warum es existiert oder wie der Fachbereich es interpretiert.

### Fachliche Metadaten

Fachliche Metadaten beschreiben Bedeutung, Zweck und Terminologie.

Dazu gehören:

- fachliche Definition
- Geschäftszweck
- Domäne und Subdomäne
- freigegebene Begriffe und Synonyme
- Scope und Ausschlüsse
- Geschäftsregeln
- fachlich verantwortliche Rolle
- relevante Prozesse und Entscheidungen

Beim durchgehenden Beispiel muss erklärt werden, ob eine Sales Order Line die ursprünglich angeforderte, aktuell aktive, gelieferte oder fakturierte Auftragsposition repräsentiert. Diese Interpretationen können trotz identischem Tabellennamen zu unterschiedlichen Kennzahlen führen.

Eine schwache Beschreibung lautet:

> `net_amount`: Umsatz.

Eine brauchbare Beschreibung lautet:

> Nettowert der aktiven Auftragsposition in Belegwährung nach positionsbezogenen Rabatten und vor Steuern. Stornierte Positionen verbleiben in der quellnahen Tabelle, werden aber aus der zertifizierten Kennzahl für offene Aufträge ausgeschlossen.

Die zweite Beschreibung verbindet das Feld mit Berechnungslogik, Scope und vorgesehener Nutzung.

### Operative Metadaten

Operative Metadaten beschreiben, was während der Erzeugung und Verarbeitung von Daten geschieht.

Beispiele sind:

- letzte erfolgreiche Aktualisierung
- erwartetes Aktualisierungsintervall
- Laufzeit und Dauer
- verarbeitete Datensatzanzahl
- fehlgeschlagene Ausführungen
- Retry-Status
- Aktualität und Latenz
- Schema-Drift-Ereignisse
- Verfügbarkeit der Quelle
- Incident-Historie

Operative Metadaten verändern sich häufig. Sie können zeigen, dass `sales_order_line` korrekt definiert ist, aber seit zwölf Stunden nicht aktualisiert wurde oder dass der letzte Lauf nur 20 Prozent des erwarteten Volumens verarbeitet hat.

Ohne operative Metadaten kann eine Dokumentation vertrauenswürdig wirken, obwohl das tatsächliche Datenobjekt veraltet oder unvollständig ist.

### Governance-Metadaten

Governance-Metadaten legen Verantwortung, Status und kontrollierte Nutzung fest.

Dazu können gehören:

- Data Owner
- Data Steward
- technischer Owner
- fachliche Domäne
- Zertifizierungsstatus
- Freigabestatus
- Policy-Zuordnung
- Aufbewahrungsklasse
- Status als autoritative Quelle
- Lifecycle-Status
- Überprüfungsdatum
- Ausnahmen und genehmigte Abweichungen

Governance-Metadaten beantworten, wer entscheiden darf, wer den Kontext pflegen muss und ob ein Datenobjekt für einen bestimmten Zweck freigegeben ist.

Ownership ist kein dekoratives Feld. Es muss mit realen Aufgaben verbunden sein, beispielsweise Definitionen freigeben, Qualitätsprobleme lösen, Zugriffe prüfen und veraltete Datenobjekte stilllegen.

### Sicherheits- und Datenschutzmetadaten

Sicherheitsmetadaten beschreiben, wie Informationen geschützt werden müssen.

Typische Felder sind:

- Sensitivitätsstufe
- Klassifikation personenbezogener Daten
- Vertraulichkeitskategorie
- Zugriffsgruppen oder Rollen
- Domäne für zeilenbasierte Zugriffskontrolle
- Maskierungs- oder Tokenisierungsrichtlinie
- zulässiger Verwendungszweck
- geografische oder vertragliche Einschränkungen
- Verschlüsselungsanforderungen
- Aufbewahrungs- und Löschkontrollen

Für `sales_order_line` kann `customer_id` als eingeschränkter interner Identifikator gelten, während eine verknüpfte E-Mail-Adresse als vertrauliches personenbezogenes Datum klassifiziert wird. Die Klassifikation schützt die Daten noch nicht. Sie liefert aber den Entscheidungsinput, den Zugriffskontrollen, Maskierung und Policies benötigen.

### Qualitätsmetadaten

Qualitätsmetadaten beschreiben, was „für den Zweck geeignet“ bedeutet und ob aktuelle Daten diese Erwartung erfüllen.

Dazu gehören:

- Regeln für Vollständigkeit, Gültigkeit, Eindeutigkeit und Konsistenz
- zulässige Wertebereiche
- Reconciliation-Regeln
- Schwellenwerte
- letzte Testergebnisse
- Qualitätsscore
- Incidents und Ausnahmen
- betroffene Datensätze
- Qualitätstrend
- verantwortliches Lösungsteam

Für das durchgehende Beispiel sind unter anderem folgende Erwartungen sinnvoll:

- `order_id` und `order_line_id` müssen vorhanden sein
- ihre Kombination muss innerhalb des aktiven Quellstands eindeutig sein
- `quantity` darf nur dann negativ sein, wenn die Position eine Retoure repräsentiert
- `currency_code` muss aus einer freigegebenen Werteliste stammen
- `net_amount` muss innerhalb einer vereinbarten Toleranz mit der Quelle übereinstimmen

Die Regeldefinition ist relativ stabil. Das letzte Ergebnis ist dynamische Metadateninformation.

### Nutzungsmetadaten

Nutzungsmetadaten beschreiben, wie ein Datenobjekt konsumiert wird.

Beispiele sind:

- abhängige Reports, Dashboards, Modelle und APIs
- aktive Consumer
- Abfragehäufigkeit
- letzter Zugriff
- häufige Filter und Joins
- kritische Geschäftsprozesse
- zertifizierte gegenüber experimenteller Nutzung
- Kosten- oder Workload-Anteil
- ungenutzte oder rückläufig genutzte Datenobjekte

Nutzungsmetadaten helfen, eine technisch verfügbare Tabelle von einem geschäftskritischen Datenprodukt zu unterscheiden. Sie unterstützen außerdem die Impact Analysis: Eine Änderung an `net_amount` ist wesentlich kritischer, wenn das Feld von Finanzreporting, Forecasting und kundenbezogenen Anwendungen verwendet wird.

Nutzung allein ist kein Qualitätsnachweis. Ein häufig verwendetes Feld kann falsch sein. Ein selten verwendetes Feld kann für einen kritischen Monatsprozess unverzichtbar sein.

### Semantische Metadaten

Semantische Metadaten beschreiben Beziehungen, Konzepte und analytisches Verhalten.

Dazu gehören:

- Fachkonzepte und ihre Beziehungen
- Bedeutung von Entitäten und Attributen
- Hierarchien
- Measures und Dimensions
- Aggregationsverhalten
- Einheiten und Währungen
- Synonyme und Terminologiezuordnungen
- gültige Join-Pfade
- semantische Typen
- kontextabhängige Regeln

Semantische Metadaten können festhalten, dass:

- `customer_id` eine Customer-Entität identifiziert
- `product_id` eine Product-Entität identifiziert
- `net_amount` innerhalb einer Währung additiv ist
- `order_date` die Rolle Order Date besitzt
- Sales Order Lines zu Sales Orders, Customers, Products und Sales Organizations aggregiert werden können

Dieser Kontext ist für BI, Suche, natürlichsprachliche Interfaces und AI-Systeme wichtig. Ein Modell kann korrektes analytisches Verhalten nicht zuverlässig allein aus Feldnamen ableiten.

### AI-Metadaten

AI-Metadaten beschreiben, ob und wie Daten durch AI-Systeme verwendet werden dürfen.

Dazu können gehören:

- zulässige AI-Use-Cases
- Trainingsfreigabe
- Eignung für RAG
- Herkunft und Provenance
- Inhaltsversion
- Sensitivität und Zugriffsfilter
- semantische Beziehungen
- Chunk- oder Datensatzkontext
- Embedding-Status
- Abhängigkeiten zu Modellen oder Prompts
- Evaluationsergebnisse
- bekannte Bias- oder Abdeckungsgrenzen
- Anforderungen an Löschung und Neuverarbeitung

Für das Sales-Datenobjekt kann der AI-Kontext beispielsweise unterscheiden zwischen:

- freigegebener Nutzung für internes Sales Forecasting
- untersagter Nutzung für allgemeines Modelltraining
- zulässiger Nutzung in einem kontrollierten RAG-Assistenten erst nach Entfernung von Kundenidentifikatoren
- ungeeigneter Nutzung für kundenbezogene Empfehlungen, weil Einwilligung oder Qualitätsnachweise fehlen

AI-Metadaten ersetzen Sicherheits-, Qualitäts- oder Governance-Metadaten nicht. Sie kombinieren und erweitern diese für AI-spezifische Entscheidungen.

## Metadaten beschreiben Daten, Prozesse, Modelle, Kontrollen und Nutzung

Metadaten werden häufig nur um Tabellen und Felder organisiert. Dadurch entsteht ein blinder Fleck, denn Unternehmensdaten werden durch ein größeres System erzeugt und genutzt.

### Metadaten über Daten

Sie beschreiben Datasets, Tabellen, Dateien, Felder, Datensätze, Fachentitäten und Datenprodukte.

Beispiele:

- Schema
- Felddefinitionen
- Sensitivität
- Qualitätserwartungen
- Domäne
- Aufbewahrung

### Metadaten über Prozesse

Sie beschreiben Ingestion, Transformation, Orchestrierung, Freigabe und operative Abläufe.

Beispiele:

- Zeitplan
- Abhängigkeiten
- Laufzeit
- Retry-Regel
- verantwortliches Team
- Deployment-Version
- Incident-Historie

Ein fehlgeschlagener Ingestion-Prozess kann mehrere ansonsten korrekte Datenobjekte unbrauchbar machen.

### Metadaten über Modelle

Dazu gehören analytische, semantische, statistische und AI-Modelle.

Beispiele:

- Modellzweck
- Eingangsfeatures
- Ausgabekennzahlen
- Trainingsdaten
- Version
- Validierungsnachweise
- Berechnungsverhalten
- unterstützte Dimensionen
- bekannte Einschränkungen

Ein Warehouse-Modell, ein semantisches Modell und ein Machine-Learning-Modell benötigen unterschiedliche Details, aber alle benötigen kontrollierten Kontext.

### Metadaten über Kontrollen

Kontrollen umfassen Policies, Tests, Validierungsregeln, Freigaben, Zugriffsregeln und technische Enforcement-Mechanismen.

Relevante Metadaten sind:

- Kontrollziel
- Auslösebedingung
- betroffene Datenobjekte
- verantwortlicher Owner
- Ort der Implementierung
- letzte Ausführung
- Ergebnis
- Ausnahmeprozess
- Evidenz

Damit lässt sich eine schriftlich dokumentierte Policy von einer technisch umgesetzten und überwachten Policy unterscheiden.

### Metadaten über Nutzung

Nutzungsmetadaten beschreiben Reports, Dashboards, APIs, Notebooks, Exporte, AI-Assistenten und operative Anwendungen.

Sie erklären:

- wer das Ergebnis verwendet
- von welchen Datenobjekten es abhängt
- welche Definitionen angewendet werden
- wie kritisch es ist
- welche Zugriffsregeln gelten
- ob lokale Logik die governte Bedeutung verändert

So entsteht ein End-to-End-Kontext statt eines auf Speicherobjekte begrenzten Katalogs.

## Metadaten haben unterschiedliche Ursprünge und Vertrauensstufen

Nicht alle Metadaten entstehen auf dieselbe Weise. Ein brauchbares Betriebsmodell unterscheidet vier Ursprünge: deklariert, erkannt, abgeleitet und beobachtet.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/what-metadata-actually-is-img2-de.png"
        alt="Vergleich von deklarierten, erkannten, abgeleiteten und beobachteten Metadaten mit Ursprung, Vertrauen, Prüfung und geeigneter Nutzung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der Ursprung bestimmt Vertrauen und Prüfbedarf. Erkannte oder abgeleitete Informationen dürfen freigegebene Deklarationen nicht unbemerkt überschreiben.
    </figcaption>
</figure>

### Deklarierte Metadaten

Deklarierte Metadaten werden bewusst durch eine verantwortliche Person oder einen Engineering-Prozess festgelegt.

Beispiele:

- freigegebene fachliche Definition
- benannter Owner
- Aufbewahrungsklasse
- erwartete Aktualität
- dokumentierte Transformationsregel
- zulässige AI-Nutzung

Deklarierte Metadaten können eine hohe Autorität besitzen. Das gilt aber nur, wenn Autor, Scope, Freigabestatus und Überprüfungsdatum bekannt sind. Eine manuell eingegebene Beschreibung ist nicht automatisch dauerhaft korrekt.

### Erkannte Metadaten

Erkannte Metadaten werden durch Scanning, Profiling, Parsing oder Klassifikation gefunden.

Beispiele:

- physischer Datentyp
- E-Mail- oder Telefonnummernmuster
- Schemaänderung
- Dateiformat
- wiederkehrende Werteverteilung
- aus Code geparste technische Abhängigkeit

Erkennung ist für Skalierung und Discovery wertvoll. Sie erzeugt normalerweise Evidenz oder einen Vorschlag, nicht automatisch eine endgültige Governance-Entscheidung.

Ein Klassifikator kann erkennen, dass ein Feld wie eine E-Mail-Adresse aussieht. Er kann nicht immer entscheiden, ob das Feld echte personenbezogene Daten, Testwerte, Hashes oder veraltete Inhalte enthält.

### Abgeleitete Metadaten

Abgeleitete Metadaten entstehen aus Beziehungen, Regeln oder anderen Metadaten.

Beispiele:

- entlang einer direkten Feldtransformation weitergegebene Sensitivität
- aus einer Domänenzuordnung übernommene Ownership
- aus abhängigen Anwendungen abgeleitete Kritikalität
- aus Keys und Lineage abgeleitete semantische Beziehungen
- wahrscheinlich doppelte Fachbegriffe

Ableitung kann Lücken schließen und manuellen Aufwand reduzieren. Sie muss jedoch erklärbar bleiben. Das System sollte Evidenz, Regel und Vertrauensniveau hinter dem Ergebnis erhalten.

Ein abgeleiteter Wert sollte normalerweise als `proposed` oder `unreviewed` markiert bleiben, bis der zuständige Workflow ihn freigibt.

### Beobachtete Metadaten

Beobachtete Metadaten werden aus dem Laufzeitverhalten gemessen.

Beispiele:

- letzte Aktualisierung
- Abfragehäufigkeit
- Zugriffsereignisse
- aktuelle Datensatzanzahl
- Fehlerrate
- Qualitätsergebnis
- Verarbeitungsdauer
- Aktualitätsverzug

Beobachtete Metadaten liefern aktuelle Evidenz. Sie zeigen, ob deklarierte Erwartungen tatsächlich erfüllt werden.

Beispiel:

```text
Deklarierte Aktualitätserwartung: alle 60 Minuten
Beobachtete letzte erfolgreiche Aktualisierung: vor 4 Stunden
Ergebnis: Freshness-Verletzung
```

Keines der beiden Felder reicht allein aus. Die deklarierte Erwartung definiert, was geschehen soll. Der beobachtete Wert zeigt, was tatsächlich geschehen ist.

## Freigabestatus und Ursprung müssen getrennt bleiben

Ursprung und Freigabe sind unterschiedliche Dimensionen.

Eine deklarierte Beschreibung kann noch ein Entwurf sein. Eine erkannte Klassifikation kann geprüft und freigegeben werden. Eine abgeleitete Beziehung kann ungeklärt bleiben. Eine beobachtete Laufzeitkennzahl kann als Messwert autoritativ sein, eignet sich aber nicht automatisch als dauerhafte Fachdefinition.

Ein pragmatischer Lifecycle ist:

```text
Vorgeschlagen → Geprüft → Freigegeben → Veraltet
```

Zusätzliche Zustände wie `unreviewed`, `rejected`, `expired` oder `retired` können sinnvoll sein. Entscheidend ist, dass Consumer einen Vorschlag von einer freigegebenen Governance-Entscheidung unterscheiden können.

Ein Metadateneintrag sollte deshalb mindestens enthalten:

- Wert
- Ursprung
- Quelle oder Evidenz
- gegebenenfalls Vertrauensniveau
- Status
- verantwortlichen Reviewer
- Freigabedatum
- Gültigkeitsbeginn
- Version
- Überprüfungs- oder Ablaufdatum

## Vertrauen benötigt statische und dynamische Metadaten

Einige Metadaten verändern sich langsam. Andere verändern sich im Minutentakt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/what-metadata-actually-is-img3-de.png"
        alt="Statische und langsam veränderliche Metadaten zusammen mit dynamischen und kontinuierlich beobachteten Metadaten in einem Metadatenprofil"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Definitionen, Ownership und Klassifikationen liefern stabilen Kontext. Aktualität, Qualität, Fehler, Schema Drift und Zugriffe liefern aktuelle operative Evidenz.
    </figcaption>
</figure>

### Statische oder langsam veränderliche Metadaten

Typische Beispiele sind:

- Tabellenzweck
- Felddefinition
- Owner
- Domäne
- Aufbewahrungsklasse
- Sensitivität
- freigegebene Terminologie
- erwarteter Grain

Diese Werte sollten sich nicht mit jedem Pipeline-Lauf verändern. Für Änderungen sind normalerweise Versionierung, Prüfung oder Freigabe erforderlich.

„Statisch“ bedeutet nicht unveränderbar. Fachdefinitionen, Ownership und Klassifikationen können sich ändern. Entscheidend ist, dass sie sich durch einen kontrollierten Lifecycle und nicht durch Laufzeitmessung verändern.

### Dynamische oder kontinuierlich beobachtete Metadaten

Typische Beispiele sind:

- letzte Aktualisierung
- aktueller Qualitätsscore
- aktive Consumer
- aktuelle Fehler
- Schema Drift
- Zugriffsereignisse
- aktuelles Volumen
- Laufzeit und Kosten

Dynamische Metadaten benötigen immer einen Zeitbezug. Ein Qualitätsscore ohne Zeitpunkt, Testversion und ausgewerteten Scope ist schwer interpretierbar.

Beide Kategorien müssen miteinander verbunden werden. Ein aktuelles Qualitätsergebnis muss auf eine definierte Regel verweisen. Eine Freshness-Beobachtung muss mit einem erwarteten Service Level verglichen werden. Ein Zugriffsereignis muss gegen eine freigegebene Policy bewertet werden.

## Die einfachste sinnvolle Metadatenumsetzung

Ein brauchbares Metadatenprogramm muss nicht mit einer großen Katalogplattform oder einem vollständigen Enterprise Graph beginnen.

Die einfachste sinnvolle Umsetzung kann ein versioniertes Metadatenprofil für ein wichtiges Datenobjekt sein.

Für `sales_order_line` könnte das minimale Profil enthalten:

| Bereich | Minimale Metadaten |
| --- | --- |
| **Identität** | stabiler Asset-Identifier, Name, System und Speicherort |
| **Bedeutung** | Zweck, Grain, fachliche Definition und Scope |
| **Struktur** | Schema, Keys, wichtige Felder und Datentypen |
| **Ownership** | Data Owner, Data Steward und technischer Owner |
| **Lineage** | autoritative Quelle und wichtigste nachgelagerte Consumer |
| **Betrieb** | Aktualitätserwartung und letzte erfolgreiche Aktualisierung |
| **Qualität** | kritische Regeln, letztes Ergebnis und offene Incidents |
| **Sicherheit** | Sensitivität, Zugriffserwartung und Policy-Zuordnung |
| **Lifecycle** | Status, Version, Überprüfungsdatum und Stilllegungsregel |
| **AI-Kontext** | zulässige Nutzung, untersagte Nutzung und Provenance-Anforderungen |

Dieses Profil kann zunächst in den Werkzeugen gepflegt werden, die dem Datenobjekt bereits am nächsten sind:

- Datenbankkommentare für grundlegende Struktur und Beschreibungen
- versioniertes YAML oder JSON für kontrollierte Deklarationen
- Transformationscode für modellbezogene Metadaten
- Orchestrierungsprotokolle für Laufzeitevidenz
- Zugriffssysteme für Sicherheitsereignisse
- BI-Metadaten für nachgelagerte Nutzung
- eine einfache Dokumentationsseite oder Registry für Discovery

Die minimale Umsetzung sollte fünf Anforderungen erfüllen:

1. Jeder kritische Wert besitzt eine verantwortliche Quelle.
2. Vorgeschlagene und freigegebene Werte sind unterscheidbar.
3. Laufzeitevidenz ist mit Zeitstempel versehen.
4. Dasselbe Datenobjekt besitzt systemübergreifend einen stabilen Identifier.
5. Metadaten können später exportiert oder verbunden werden.

Das Ziel ist nicht, sofort alles zu zentralisieren. Das Ziel ist, zu verhindern, dass wichtiger Kontext implizit bleibt oder nur im Wissen einzelner Personen existiert.

## Alternative Metadatenmuster

Unterschiedliche Organisationen benötigen unterschiedliche Betriebsmodelle.

### Quellnahe Metadaten

Metadaten verbleiben nahe an Datenbanken, Code, Pipelines und Anwendungen.

Vorteile:

- Engineers pflegen Kontext dort, wo Änderungen entstehen
- starke Verbindung zur Implementierung
- bessere Versionierbarkeit
- geringer initialer Plattformaufwand

Grenzen:

- erschwerte systemübergreifende Suche
- uneinheitliche Schemata
- fragmentierte Ownership und Statusinformationen
- begrenzte unternehmensweite Impact Analysis

Dies ist häufig der richtige Startpunkt. Er benötigt jedoch gemeinsame Konventionen und stabile Identifier.

### Zentrales Metadaten-Repository oder Datenkatalog

Metadaten aus mehreren Systemen werden in eine zentrale, durchsuchbare Plattform übernommen.

Vorteile:

- zentrale Discovery
- gemeinsames Vokabular
- systemübergreifende Lineage
- Governance-Workflows
- breitere Impact Analysis

Grenzen:

- zentrale Kopien können veralten
- nicht jedes System liefert ausreichende Details
- lokaler Kontext kann bei der Normalisierung verloren gehen
- ein Katalog kann passive Dokumentation bleiben, wenn er von Engineering und Kontrollen getrennt ist

Für Metadaten, die in einem Quellsystem entstehen, sollte dieses System autoritativ bleiben. Zentralisierung sollte Kontext verbinden und darstellen, nicht lokale Verantwortung blind ersetzen.

### Föderiertes Metadatenmodell

Domänen pflegen ihre Metadaten innerhalb gemeinsamer unternehmensweiter Regeln.

Vorteile:

- Domänenwissen bleibt lokal
- gemeinsame Governance kann mit dezentraler Ownership verbunden werden
- besser skalierbar als ein einziges zentrales Dokumentationsteam

Grenzen:

- benötigt klare Verträge und Pflichtfelder
- Qualität kann zwischen Domänen variieren
- Konfliktlösung und gemeinsame Begriffe benötigen Governance

### Metadaten-Graph

Datenobjekte, Prozesse, Begriffe, Kontrollen, Personen und Consumer werden als verbundene Entitäten modelliert.

Vorteile:

- flexible Lineage und Impact Analysis
- semantische Beziehungen
- Verbindungen zwischen Policies und Kontrollen
- wertvoller Kontext für Suche und AI

Grenzen:

- benötigt stabile Identifier und hochwertige Beziehungen
- Graph-Komplexität kompensiert keine schwachen Definitionen
- abgeleitete Beziehungen benötigen Erklärbarkeit und Prüfung

Das geeignete Muster hängt von Skalierung, Tool-Vielfalt, regulatorischen Anforderungen, organisatorischer Ownership und Reife der vorhandenen Engineering-Praktiken ab.

## Passive Dokumentation und aktive Metadaten sind unterschiedliche Reifestufen

Metadaten können rein beschreibend bleiben oder an operativen Entscheidungen teilnehmen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/what-metadata-actually-is-img4-de.png"
        alt="Reifepfad von Namen und Datentypen über durchsuchbare Dokumentation und vernetzte Metadaten bis zu Validierung, automatisierten Kontrollen und AI-fähigem Kontext"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Organisationen können sich schrittweise entwickeln. Aktive Metadaten werden wertvoll, wenn verlässlicher Kontext mit Validierung, Kontrollen und governtem Konsum verbunden wird.
    </figcaption>
</figure>

### Namen und Datentypen

Die Organisation kann Schemas und physische Objekte untersuchen.

Dies unterstützt technische Discovery, liefert aber kaum fachlichen oder Governance-Kontext.

### Durchsuchbare Dokumentation

Beschreibungen, Owner und Begriffe können in einem oder mehreren Dokumentationssystemen gefunden werden.

Dies reduziert die Abhängigkeit von individuellem Wissen. Nutzer müssen die Informationen jedoch weiterhin manuell interpretieren und anwenden.

### Vernetzter Metadaten-Graph

Datenobjekte sind mit Quellen, Transformationen, Ownern, Begriffen, Kontrollen und Consumern verbunden.

Dies unterstützt Lineage, Impact Analysis und umfassendere Discovery.

### Qualitäts- und Policy-Validierung

Metadaten werden gegen Regeln geprüft.

Beispiele:

- kritische Datenobjekte benötigen einen Owner
- personenbezogene Daten benötigen eine freigegebene Schutzzuordnung
- veröffentlichte Modelle benötigen Beschreibungen für fachlich sichtbare Felder
- zertifizierte Datenprodukte benötigen definierte Qualitätserwartungen
- AI-fähige Datenobjekte benötigen Metadaten zur zulässigen Nutzung

Die Validierung kann während Change Review, Deployment oder geplanten Governance-Prüfungen ausgeführt werden.

### Automatisierte Kontrollen

Freigegebene Metadaten lösen technisches Verhalten aus.

Beispiele:

- Sensitivitätsmetadaten wählen eine Maskierungsrichtlinie
- Domänenmetadaten wählen eine Zugriffsregel
- Aufbewahrungsmetadaten lösen Löschworkflows aus
- Qualitätsstatus blockiert die Veröffentlichung
- Deprecation-Status erzeugt Impact-Benachrichtigungen

Das sind aktive Metadaten: Kontext wird nicht mehr nur von Menschen gelesen, sondern dient als Input für kontrollierte Automatisierung.

Automatisierung muss transparent bleiben. Eine Klassifikation darf nicht über versteckte Logik eine unerklärte Kontrolle auslösen. Metadatenwert, Policy-Zuordnung, Implementierung und Evidenz müssen nachvollziehbar bleiben.

### AI-fähiger Kontext

Metadaten unterstützen governtes Retrieval, Auswahl von Modelleingaben, Quellenzuordnung, Zugriffsfilter und Evaluation.

AI-fähige Metadaten benötigen mehr als Embeddings. Sie benötigen verlässliche Bedeutung, Provenance, Berechtigungen, Aktualität und semantische Beziehungen.

Nicht jede Organisation benötigt sofort die höchste Reifestufe. Der richtige Zielzustand ist die einfachste Stufe, die aktuelle Entscheidungen und Risiken zuverlässig unterstützt.

## Ein konkretes Metadatenprofil für Sales Order Line

Das durchgehende Beispiel lässt sich als ein verbundenes Profil zusammenfassen.

### Asset-Identität

```text
Asset ID: sales.sales_order_line
Asset-Typ: analytische Tabelle
Domäne: Sales
Lifecycle-Status: aktiv
```

### Fachliche Bedeutung

```text
Zweck: Pro Auftragsposition einen quellnahen Datensatz für governte nachgelagerte Transformationen bereitstellen.
Grain: Ein Datensatz je Order ID und Order Line ID innerhalb der aktuellen Quellversion.
Scope: Standard-, Retouren- und Stornopositionen bleiben mit explizitem Status erhalten.
```

### Technischer Kontext

```text
Quelle: ERP-Tabelle für Auftragspositionen
Primäre Key-Erwartung: order_id + order_line_id
Aktualisierungsmodus: inkrementell
Primäres nachgelagertes Modell: sales_order_line_conformed
```

### Governance und Sicherheit

```text
Business Owner: Head of Sales Operations
Data Steward: Sales Data Steward
Technischer Owner: Data Platform Team
Sensitivität: intern
Kundenidentifikator: eingeschränkter interner Identifikator
Aufbewahrungsklasse: Policy für Vertriebstransaktionen
```

### Qualitätserwartungen

```text
Key-Vollständigkeit: 100 %
Key-Eindeutigkeit: für aktive Quellversion erforderlich
Währungsgültigkeit: freigegebene Werteliste
Betragsabgleich: innerhalb vereinbarter Toleranz
Freshness-Ziel: 60 Minuten
```

### Beobachtete Evidenz

```text
Letzte erfolgreiche Aktualisierung: Laufzeitwert mit Zeitstempel
Letztes Qualitätsergebnis: Pass, Warning oder Fail
Aktuelles Volumen: Datensatzanzahl und Abweichung von der Baseline
Offene Incidents: verknüpfte operative Vorgänge
```

### Nutzung und semantischer Kontext

```text
Consumer: Order Backlog, Sales Performance, Forecasting
Entitäten: Sales Order, Customer, Product
Measures: Quantity, Gross Amount, Net Amount
Gültige Joins: governte Customer-, Product- und Organization-Keys
```

### AI-Kontext

```text
Forecasting: für freigegebene interne Nutzung zulässig
Allgemeines Modelltraining: untersagt
RAG-Nutzung: nur über governte semantische Beschreibungen und zugriffsgefilterte Evidenz
Provenance: Quelle, Transformationsversion und Aktualisierungszeitpunkt erforderlich
```

Kein einzelnes System muss jede Zeile physisch besitzen. Entscheidend ist, dass das Profil konsistent aufgelöst werden kann und jeder Wert eine vertrauenswürdige Quelle besitzt.

## Typische Anti-Patterns

### Beschreibungen als vollständiges Metadatenmodell behandeln

Beschreibungen können Ownership, Qualität, Sicherheit, Lineage und Laufzeitevidenz nicht ersetzen.

### Beschreibungen verfassen, die nur den Feldnamen wiederholen

Beispiele wie „Sales ist Umsatz“ oder „Customer ID ist die Kunden-ID“ liefern kaum nutzbaren Kontext. Eine Feldbeschreibung sollte fachliche Bedeutung, Scope, Berechnung, zulässige Werte, Ausnahmen oder Beziehungen erklären.

### Metadaten zentralisieren, aber Verantwortung nicht festlegen

Ein zentraler Katalog ohne verantwortliche Owner wird zu einer größeren Sammlung veralteter Felder.

### Erkannte Metadaten mit freigegebenen Metadaten verwechseln

Automatische Klassifikation und Profiling erzeugen wertvolle Evidenz. Sie dürfen nicht unbemerkt zu bindenden Governance-Entscheidungen werden.

### Metadaten ohne Transformationssemantik kopieren

Ein nachgelagertes Feld kann mehrere Eingaben kombinieren, aggregieren, hashen oder fachlich neu interpretieren. Blinde Vererbung kann falsche Klassifikationen und Definitionen erzeugen.

### Dynamische Werte ohne Zeitstempel speichern

Ein „Qualitätsscore: 96“ ist ohne Zeitpunkt, ausgewerteten Scope, Regelversion und Schwellenwert bedeutungslos.

### Nutzung mit Vertrauen verwechseln

Häufige Verwendung beweist keine Korrektheit. Geringe Nutzung beweist keine Irrelevanz.

### Für jede Perspektive ein separates Metadaten-Repository aufbauen

Technische, fachliche, Sicherheits- und AI-Metadaten können in unterschiedlichen Systemen entstehen. Consumer benötigen dennoch eine auflösbare Sicht auf dasselbe Datenobjekt.

### Kontrollen automatisieren, bevor Metadaten verlässlich sind

Falsche oder ungeprüfte Metadaten können falsche Zugriffs-, Maskierungs-, Lösch- oder Veröffentlichungsentscheidungen auslösen. Automatisierung benötigt Status, Evidenz und Ausnahmebehandlung.

### Daten ohne Provenance und Berechtigungen für AI vorbereiten

Undokumentierte Inhalte in einen Retrieval-Index einzubetten macht sie nicht AI-fähig. Es kann lediglich dazu führen, dass falscher oder unzulässiger Kontext leichter gefunden wird.

## Entscheidungshilfe

Die folgenden Fragen helfen, den nächsten sinnvollen Schritt festzulegen:

| Frage | Konsequenz |
| --- | --- |
| Können Nutzer das Datenobjekt finden, aber nicht verstehen? | Zuerst fachliche und semantische Metadaten verbessern. |
| Ist die Bedeutung klar, aber die aktuelle Zuverlässigkeit unbekannt? | Operative und Qualitätsmetadaten verbinden. |
| Sind Ownership und Klassifikationen uneinheitlich? | Governance-Felder, Status und Freigabeworkflows etablieren. |
| Beschreiben mehrere Tools dasselbe Datenobjekt unterschiedlich? | Stabile Identifier und ein einheitliches Metadatenmodell einführen. |
| Sind Auswirkungen von Änderungen schwer zu bewerten? | Lineage-, Nutzungs- und Abhängigkeitsmetadaten verbessern. |
| Sind Policies dokumentiert, werden aber manuell umgesetzt? | Freigegebene Metadaten mit Validierung und Kontrollen verbinden. |
| Ist AI geplant? | Vor Indexierung oder Training Provenance, Berechtigungen, semantischen Kontext, Aktualität und AI-Nutzungsmetadaten ergänzen. |
| Veraltet der Katalog regelmäßig? | Pflege näher an die Quelle verlagern und beobachtbare Fakten automatisiert erfassen. |

Die wichtigste Designentscheidung ist nicht, welches Katalogprodukt gekauft wird. Entscheidend ist, welche Metadatenwerte autoritativ sind, wer sie verantwortet, wie sie geprüft werden und wie sie mit den tatsächlichen Systemen synchron bleiben.

## Wichtigste Empfehlungen

1. Metadaten als verbundenen Kontext und nicht als Sammlung von Katalogfeldern behandeln.
2. Technische, fachliche, operative, Governance-, Sicherheits-, Qualitäts-, Nutzungs-, semantische und AI-Metadaten unterscheiden.
3. Metadaten über Prozesse, Modelle, Kontrollen und Nutzung modellieren — nicht nur über Tabellen und Felder.
4. Ursprung, Evidenz, Vertrauensniveau und Freigabestatus erhalten.
5. Stabile Definitionen von dynamischen Beobachtungen trennen und anschließend miteinander verbinden.
6. Mit einem kritischen Datenobjekt und einem minimalen Metadatenprofil beginnen.
7. Metadaten nahe an dem System oder Team pflegen, das sie korrekt halten kann.
8. Discovery und Beziehungen zentralisieren, ohne lokale Verantwortung zu entfernen.
9. Automatisierung zunächst für Harvesting und Validierung nutzen; Enforcement nur mit freigegebenen und nachvollziehbaren Metadaten verbinden.
10. AI-Kontext durch Bedeutung, Provenance, Zugriff und Qualität vorbereiten — nicht allein durch Embeddings.

## Der nächste Schritt: verstehen, wo Metadaten entstehen

Bevor Metadaten erfasst, vereinheitlicht oder aktiviert werden können, müssen ihre Ursprünge verstanden werden.

Einige Metadaten werden bewusst in Quellanwendungen erzeugt. Andere existieren in Datenbankkatalogen und Transformationscode. Manche entstehen erst in Orchestrierungsprotokollen, BI-Anwendungen, Sicherheitssystemen oder durch Laufzeitbeobachtung. Anderer Kontext muss durch Business Owner und Stewards deklariert werden, weil kein Scanner ihn zuverlässig ableiten kann.

Der nächste Teil, **Wo Metadaten entstehen**, ordnet diese Ursprünge entlang des vollständigen Datenlebenszyklus ein und legt fest, welche Quelle für welche Art von Metadaten autoritativ bleiben sollte.
