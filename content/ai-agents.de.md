---
title: AI-Agenten und Automatisierung — Vom Sprachmodell zur kontrollierten Handlung
description: Wie AI-Agenten Ziele über mehrere Schritte verfolgen, Tools kontrolliert einsetzen und innerhalb deterministischer Prozessgrenzen sicher betrieben werden.
category: Künstliche Intelligenz
tags:
  - ai
  - ai-foundations
  - ai-agents
  - agentic-ai
  - automation
  - tool-calling
  - orchestration
  - human-in-the-loop
  - workflows
  - ai-governance
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 4
seriesTitle: AI Foundations
hero: images/playbooks/ai-agents-hero.png
---

## Von der Antwort zur Handlung

Ein Sprachmodell erzeugt zunächst eine Ausgabe. Es kann erklären, strukturieren, klassifizieren oder einen nächsten Schritt vorschlagen. Eine reale Aktion entsteht daraus noch nicht automatisch.

Damit ein AI-System beispielsweise einen Datensatz abruft, eine E-Mail vorbereitet, einen Termin prüft, ein Ticket anlegt, einen Bericht aktualisiert oder eine Aktion in einem Geschäftssystem ausführt, benötigt es zusätzliche Softwarekomponenten.

Ein AI-Agent ist deshalb nicht nur ein Modell und auch kein autonom denkendes digitales Wesen.

> **Ein AI-Agent ist eine orchestrierte Softwarearchitektur, die ein Ziel, ein Sprachmodell, einen Arbeitszustand, definierte Tools und technische Kontrollmechanismen miteinander verbindet.**

Das Modell kann innerhalb dieser Architektur bewerten, welcher nächste Schritt plausibel ist. Ob dieser Schritt zulässig ist, wie er ausgeführt wird und welche Grenzen gelten, muss jedoch durch die umgebende Anwendung kontrolliert werden.

```flowchart
Ziel
Aktuellen Zustand verstehen
Nächsten Schritt auswählen
Tool kontrolliert ausführen
Ergebnis beobachten
Fortschritt bewerten
```

Die Schleife endet nicht nur bei Erfolg. Sie kann auch durch eine Freigabe, ein Zeitlimit, ein Budget, eine Policy, einen Fehler oder eine Eskalation beendet werden.

## Chatbot, Tool-Assistent und Agent sind nicht dasselbe

Die Begriffe werden häufig vermischt. Ein Chatbot kann sehr leistungsfähig sein, ohne ein Agent zu sein. Ebenso macht ein einzelner Tool-Aufruf aus einem Assistenten noch keinen mehrstufigen Agenten.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-agents-img1-de.png"
        alt="Vergleich von Chatbot, Tool-Using Assistant und AI-Agent anhand von Ausführungszustand, Tool-Nutzung und mehrstufigem Zielverhalten"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein Chatbot erzeugt primär Inhalte. Ein Tool-Assistent kann eine definierte Funktion aufrufen. Ein Agent verfolgt ein Ziel über mehrere kontrollierte Schritte und verarbeitet die Ergebnisse seiner Aktionen.
    </figcaption>
</figure>

### Chatbot

```text
Frage
→ Modell
→ Antwort
```

Typische Aufgaben sind Fragen beantworten, Texte formulieren, Inhalte zusammenfassen oder Vorschläge erzeugen. Der Benutzer initiiert jeden neuen Schritt. Es existiert normalerweise kein eigener Ausführungszustand, der eine laufende Aufgabe über mehrere Aktionen verfolgt.

### Tool-Using Assistant

```text
Frage
→ Modell
→ Tool-Aufruf
→ Tool-Ergebnis
→ Antwort
```

Ein Tool-Assistent kann beispielsweise eine Kundennummer nachschlagen, eine Berechnung ausführen oder einen verfügbaren Termin suchen. Der Tool-Aufruf kann trotzdem Teil einer einzelnen Anfrage bleiben. Häufig bestätigt der Benutzer die eigentliche Aktion oder startet den nächsten Schritt erneut.

### AI-Agent

```text
Ziel
→ Zustand analysieren
→ nächsten Schritt planen
→ Tool verwenden
→ Ergebnis beobachten
→ Zustand aktualisieren
→ fortfahren oder beenden
```

Typische Merkmale sind:

- mehrstufige Ausführung
- Arbeitszustand
- Tool-Auswahl
- Beobachtung von Zwischenergebnissen
- Anpassung des weiteren Pfads
- Abbruch- und Eskalationsbedingungen
- möglicherweise menschliche Freigaben

Die Grenze ist nicht mathematisch eindeutig. In der Praxis ist entscheidend, ob das System selbstständig mehrere Ausführungsschritte koordiniert und neue Beobachtungen in die weitere Bearbeitung einbezieht.

## Tool Calling ist ein Protokoll, keine Berechtigung

Ein Sprachmodell führt ein Geschäftssystem nicht direkt aus. Es erzeugt üblicherweise eine strukturierte Anforderung, die von einer Anwendung ausgewertet wird.

```json
{
  "name": "create_support_ticket",
  "description": "Create a support ticket after validation and approval.",
  "input_schema": {
    "type": "object",
    "properties": {
      "customer_id": { "type": "string" },
      "category": {
        "type": "string",
        "enum": ["billing", "relocation", "meter", "outage", "contract"]
      },
      "priority": {
        "type": "string",
        "enum": ["low", "medium", "high"]
      },
      "summary": { "type": "string" }
    },
    "required": ["customer_id", "category", "priority", "summary"]
  }
}
```

Das Modell kann anschließend einen passenden Tool-Aufruf vorschlagen:

```json
{
  "tool": "create_support_ticket",
  "arguments": {
    "customer_id": "C-10472",
    "category": "outage",
    "priority": "high",
    "summary": "Customer reports a complete power outage."
  }
}
```

Dieser Vorschlag darf nicht mit einer Berechtigung verwechselt werden. Die Anwendung muss prüfen:

- Ist das Tool für diesen Use Case zugelassen?
- Darf der aktuelle Benutzer dieses Tool verwenden?
- Sind die Parameter syntaktisch und fachlich gültig?
- Enthält die Eingabe sensible oder unerlaubte Daten?
- Ist eine menschliche Freigabe erforderlich?
- Wurde das Kosten- oder Schrittbudget überschritten?
- Wurde die Aktion bereits ausgeführt?
- Muss die Aktion protokolliert werden?

```flowchart
Modell schlägt vor
Orchestrator validiert
Tool führt aus
Ergebnis wird zurückgegeben
```

Die technische Ausführung liegt nicht beim Sprachmodell, sondern bei der kontrollierenden Software.

## Der Agent arbeitet in einer Ausführungsschleife

Ein Agent löst eine mehrstufige Aufgabe nicht vollständig in einem einzigen Schritt. Er verarbeitet jeweils den aktuellen Zustand, wählt eine zulässige Aktion aus und bewertet anschließend das Ergebnis.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-agents-img2-de.png"
        alt="Agent-Ausführungszyklus aus Ziel, Zustandsverständnis, Planung, Tool-Auswahl, Aktion, Beobachtung und Fortschrittsbewertung mit Leitplanken und möglichen Ergebnissen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der Agent arbeitet iterativ. Jede Beobachtung verändert den Arbeitszustand und beeinflusst die Entscheidung über den nächsten zulässigen Schritt.
    </figcaption>
</figure>

### Ziel und Zustand

Eine belastbare Zieldefinition enthält gewünschtes Ergebnis, fachlichen Kontext, erlaubte Systeme, verbotene Aktionen, Erfolgskriterien, Zeit- und Kostenlimits sowie notwendige Freigaben.

Der Arbeitszustand verbindet unter anderem:

- ursprüngliches Ziel
- Benutzeridentität
- bisherige Schritte
- Tool-Ergebnisse
- gefundene Dokumente
- offene Teilaufgaben
- verbrauchtes Budget
- verbleibende Zeit
- vorhandene Freigaben
- Fehler und Wiederholungen

Dieser Zustand kann teilweise im Modellkontext liegen. Für längere oder unterbrechbare Prozesse sollte er zusätzlich außerhalb des Modells persistent gespeichert werden.

### Planung, Tool-Auswahl und Aktion

Der Agent bewertet, welche Aktion den größten Fortschritt verspricht. Er kann Informationen suchen, eine Rückfrage stellen, ein Tool verwenden, eine Freigabe anfordern oder den Vorgang beenden.

Der Agent darf nur Tools sehen oder verwenden, die für den aktuellen Kontext freigegeben sind. Die Verfügbarkeit kann von Benutzerrolle, Mandant, Datenklassifikation, Prozessphase, Region, Risikoklasse oder aktuellem Freigabestatus abhängen.

Die Ausführung erfolgt über kontrollierte Komponenten wie API Gateway, Workflow Engine, Function Runtime, Message Queue oder isolierte Sandbox. Die Ausführungskomponente validiert Parameter erneut und übernimmt keine frei erzeugten Befehle ungeprüft.

### Beobachtung und Fortschrittsbewertung

Tool-Ergebnisse sollten strukturiert zurückgegeben werden:

```json
{
  "status": "success",
  "ticket_draft_id": "DRAFT-7821",
  "missing_fields": [],
  "requires_approval": true
}
```

Ein Fehler ist ebenfalls eine Beobachtung:

```json
{
  "status": "failed",
  "error_code": "CUSTOMER_NOT_FOUND",
  "retryable": false,
  "recommended_next_step": "request_customer_identifier"
}
```

Am Ende jeder Iteration wird geprüft:

- Ist das Ziel erreicht?
- Fehlen Informationen?
- Ist eine Freigabe erforderlich?
- Ist ein alternativer Pfad sinnvoll?
- Wurde eine Policy verletzt?
- Ist das Schritt-, Zeit- oder Kostenlimit erreicht?
- Muss der Prozess abgebrochen oder eskaliert werden?

```text
Erfolgreich abgeschlossen
Teilfortschritt — Schleife fortsetzen
Menschliche Freigabe erforderlich
Durch Policy gestoppt
Fehler — Retry, Kompensation oder Eskalation
```

## Der Arbeitszustand ist mehr als Chatverlauf

Ein langer Chatverlauf ist kein belastbarer Prozesszustand. Ein produktiver Agent benötigt strukturierte Zustandsinformationen:

```json
{
  "run_id": "RUN-2026-0717-0042",
  "goal": "Prepare an approved response for the customer request.",
  "current_stage": "approval_pending",
  "completed_steps": [
    "classify_request",
    "retrieve_customer",
    "draft_response"
  ],
  "pending_steps": [
    "manager_approval",
    "send_response"
  ],
  "tool_results": {
    "customer_id": "C-10472",
    "classification": "billing"
  },
  "limits": {
    "max_steps": 12,
    "used_steps": 5,
    "max_cost_eur": 0.80
  }
}
```

Ein strukturierter Zustand unterstützt Wiederaufnahme nach Unterbrechung, Auditierbarkeit, Fehleranalyse, Rollback, Übergabe an Menschen und deterministische Validierung. Das Modell sollte nur den Teil des Zustands erhalten, den es für den nächsten Schritt tatsächlich benötigt.

## Aufbau eines Enterprise AI-Agenten

Ein produktiver Agent besteht aus mehreren Schichten, die klar voneinander getrennt werden sollten.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-agents-img3-de.png"
        alt="Architektur eines Enterprise AI-Agenten mit Eingaben, Agent-Orchestrator, Sprachmodell, Planung, Tool-Registry, Arbeitszustand, Retrieval, Berechtigungen, Policies, Monitoring, externen Systemen und Kontrollschicht"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der Agent ist ein orchestriertes System. Das Sprachmodell ist eine zentrale Komponente, aber nicht die gesamte Architektur.
    </figcaption>
</figure>

### Benutzer und Anwendungen

Agenten können über Chat, Webanwendung, Fachanwendung, API, Ereignis, Queue, geplante Aufgabe oder Workflow gestartet werden. Die Oberfläche sollte sichtbar machen, was der Agent tun kann, welche Systeme verwendet werden, wann eine Aktion vorbereitet oder ausgeführt wird und wann eine Freigabe erforderlich ist.

### Agent-Orchestrator

Der Orchestrator steuert die Ausführung. Typische Verantwortlichkeiten sind:

- Modell aufrufen
- Tool-Aufrufe verarbeiten
- Zustand laden und speichern
- Policies prüfen
- Freigaben verwalten
- Schrittlimits durchsetzen
- Fehler und Wiederholungen behandeln
- Ergebnisse protokollieren
- Lauf fortsetzen oder stoppen

### Sprachmodell

Das Modell unterstützt Zielinterpretation, Aufgabenzerlegung, Auswahl zwischen zugelassenen Tools, Verarbeitung unstrukturierter Ergebnisse und Erzeugung von Entwürfen. Es sollte nicht die alleinige Autorität für Berechtigungen, finanzielle Limits oder irreversible Aktionen sein.

### Tool-Registry

Die Tool-Registry beschreibt verfügbare Fähigkeiten:

- Tool-Name und Zweck
- Eingabe- und Ausgabeschema
- Risikoklasse
- benötigte Berechtigungen
- Freigaberegeln
- Timeouts und Kosten
- Wiederholbarkeit
- Owner und Version

| Tool | Risiko | Freigabe | Wiederholbarkeit |
| --- | --- | --- | --- |
| Kundendaten lesen | Mittel | Rollenprüfung | Ja |
| Ticketentwurf anlegen | Niedrig | Keine zusätzliche | Ja |
| Kunden-E-Mail senden | Mittel | Benutzerfreigabe | Bedingt |
| Zahlung auslösen | Hoch | Vier-Augen-Prinzip | Nein |
| Datensatz löschen | Hoch | Fachliche und technische Freigabe | Nein |

### Retrieval und Wissen

Der Agent kann RAG verwenden, um Richtlinien, Produktinformationen, Prozessbeschreibungen, Datenkataloge oder frühere Tickets abzurufen. Retrieval liefert Kontext. Tools liefern Live-Daten oder führen Aktionen aus. Beide Funktionen sollten getrennt betrachtet werden.

### Identität und Berechtigungen

Der Agent benötigt eine technische Identität. Zusätzlich muss die Identität des auslösenden Benutzers erhalten bleiben.

Zu klären ist:

- Handelt der Agent im Namen eines Benutzers?
- Besitzt er eine eigene Service-Identität?
- Werden Benutzerrechte delegiert?
- Welche Rechte gelten bei geplanten oder eventgetriebenen Läufen?
- Wie werden Mandant und Region bestimmt?
- Wie werden temporäre Berechtigungen widerrufen?

Eine pauschal hoch privilegierte Agentenidentität erzeugt ein erhebliches Risiko.

### Policy Engine

Policies sollten außerhalb des Sprachmodells technisch auswertbar sein.

```text
Wenn Tool = delete_customer_record
Dann menschliche Freigabe erforderlich.

Wenn classification >= confidential
Dann nur internes Modell oder freigegebener Endpunkt.

Wenn amount > 5.000 EUR
Dann Vier-Augen-Freigabe.

Wenn step_count >= 12
Dann Lauf stoppen und eskalieren.
```

System-Prompts können Regeln erklären. Sie ersetzen keine durchgesetzte Policy.

### Logging und Monitoring

Ein Agentenlauf sollte mindestens Run-ID, Benutzer- und Agentenidentität, Ziel, Modellversion, Prompt- und Policy-Version, Tool-Aufrufe, Tool-Ergebnisse, Freigaben, Schrittzahl, Laufzeit, Kosten und Endstatus erfassen.

Sensible Inhalte müssen nach Datenklassifikation behandelt werden. Vollständiges Logging darf nicht zu einem unkontrollierten zweiten Datenspeicher werden.

## Deterministisch oder agentisch?

Nicht jeder Prozess profitiert von einem Agenten. Stabile und klar definierte Abläufe sind mit klassischer Automatisierung meist einfacher, günstiger und besser nachvollziehbar.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-agents-img4-de.png"
        alt="Vergleich eines deterministischen Workflows mit einem agentischen Workflow und einem hybriden Ansatz aus festen Prozessgrenzen und eingegrenzter agentischer Entscheidung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Deterministische und agentische Logik lösen unterschiedliche Probleme. In Unternehmen ist ein hybrider Aufbau häufig die belastbarste Lösung.
    </figcaption>
</figure>

### Deterministischer Workflow

```text
Fester Auslöser
→ feste Validierung
→ feste Transformation
→ feste Aktion
→ festes Ergebnis
```

Geeignet für klare Geschäftsregeln, strukturierte Eingaben, hohe Wiederholungsraten, regulatorisch definierte Abläufe und exakte Berechnungen.

Vorteile:

- vorhersehbares Verhalten
- einfache Tests
- klare Fehlerpfade
- geringe Modellkosten
- gute Auditierbarkeit

### Agentischer Workflow

```text
Ziel
→ Situation interpretieren
→ nächsten Schritt auswählen
→ Tool verwenden
→ Ergebnis bewerten
→ Pfad anpassen
```

Geeignet für variable und unstrukturierte Eingaben, mehrere mögliche Bearbeitungswege, Informationssuche über verschiedene Systeme und Aufgaben mit Interpretation.

Vorteile:

- flexible Bearbeitung
- Anpassung an neue Beobachtungen
- geringerer Bedarf, jeden Pfad vorab zu programmieren
- natürliche Verarbeitung unstrukturierter Inhalte

Nachteile:

- weniger Vorhersagbarkeit
- höherer Testaufwand
- zusätzliche Laufzeit und Kosten
- komplexere Fehleranalyse
- größere Angriffs- und Missbrauchsfläche

### Der hybride Mittelweg

```flowchart
Deterministischer Prozess
Eingegrenzte agentische Entscheidung
Deterministische Ausführung
Deterministische Validierung
```

Beispiel:

1. Ein Workflow prüft Identität, Datenklassifikation und Pflichtfelder.
2. Ein Agent analysiert die unstrukturierte Anfrage und schlägt Kategorie sowie Bearbeitungsweg vor.
3. Eine feste Regel validiert die Kategorie.
4. Ein deterministischer Service erstellt einen Entwurf.
5. Ein Mensch genehmigt den Versand.
6. Ein fester Prozess protokolliert und archiviert das Ergebnis.

Der Agent übernimmt dort Interpretation, wo Flexibilität benötigt wird. Kontrolle und irreversible Aktionen bleiben deterministisch.

## Autonomie ist keine Ja-Nein-Entscheidung

| Stufe | Verhalten | Beispiel |
| --- | --- | --- |
| **1 — Nur analysieren** | Erzeugt eine Empfehlung, führt nichts aus | Risiko eines Vertrags bewerten |
| **2 — Aktion vorbereiten** | Erstellt einen Entwurf | E-Mail oder Ticket vorbereiten |
| **3 — Freigabe erforderlich** | Pausiert vor der Ausführung | Antwort senden, Datensatz ändern |
| **4 — Innerhalb von Grenzen ausführen** | Nutzt freigegebene Tools bis zu definierten Limits | Standardtickets bearbeiten |
| **5 — Mehrstufig autonom** | Plant und koordiniert mehrere Aktionen | Komplexe Recherche und Vorgangsvorbereitung |

Die höchste Stufe ist nicht automatisch die beste. Die passende Autonomie hängt von möglichem Schaden, Umkehrbarkeit, Datenklassifikation, finanzieller Wirkung, regulatorischer Relevanz und Verfügbarkeit eines Rollbacks ab.

> **Autonomie sollte nur so hoch sein, wie es der konkrete Prozess rechtfertigt.**

## Human-in-the-Loop muss technisch Teil des Prozesses sein

Eine Freigabe ist mehr als die Aufforderung im Prompt, vorsichtig zu sein. Ein belastbarer Freigabeprozess benötigt:

- eindeutige Run-ID
- konkrete geplante Aktion
- sichtbare Parameter
- zugrunde liegende Evidenz
- Risiko- und Policy-Hinweise
- freigabeberechtigte Person
- Ablaufzeit
- Entscheidung mit Zeitstempel
- unveränderte Fortsetzung nach der Freigabe

```flowchart
Agent bereitet Aktion vor
Ausführung pausiert
Mensch prüft Parameter und Evidenz
Freigeben oder ablehnen
Lauf kontrolliert fortsetzen
```

Nach einer Freigabe sollten die genehmigten Parameter nicht unbemerkt neu vom Modell erzeugt werden.

## Sichere Tool-Schnittstellen

Ein Agent sollte keine allgemein mächtigen Tools erhalten, wenn eine engere Funktion genügt.

Ungünstig:

```text
execute_sql(sql)
run_shell(command)
call_any_api(url, method, body)
```

Besser:

```text
get_customer_summary(customer_id)
create_ticket_draft(category, priority, summary)
list_available_appointments(region, date_range)
request_record_deletion(record_id, reason)
```

Eng definierte Tools reduzieren unerlaubte Parameter, Datenabfluss, Prompt-Injection-Folgen und unbeabsichtigte Seiteneffekte.

Weitere Schutzmaßnahmen sind Schema-Validierung, Allow Lists, Parametergrenzen, Output-Validierung, Timeouts, Rate Limits, Sandboxing, Netzwerkbeschränkungen, getrennte Lese- und Schreibtools sowie transaktionale Ausführung.

## Wiederholungen und doppelte Aktionen

Agenten können Tools erneut aufrufen, wenn eine Antwort verloren geht oder ein Schritt unklar bleibt. Bei schreibenden Aktionen kann dies zu Duplikaten führen.

Schreibende Tools sollten möglichst idempotent gestaltet werden:

```json
{
  "idempotency_key": "RUN-2026-0717-0042:create-ticket",
  "customer_id": "C-10472",
  "operation": "create_ticket"
}
```

Erhält das System denselben Schlüssel erneut, liefert es das vorhandene Ergebnis zurück, statt die Aktion zu wiederholen.

## Fehler, Retry und Kompensation

| Fehlerart | Mögliche Behandlung |
| --- | --- |
| Temporäres Netzwerkproblem | begrenzter Retry mit Backoff |
| Rate Limit | warten oder freigegebenen alternativen Endpunkt verwenden |
| Ungültige Parameter | nicht unverändert wiederholen; Eingabe korrigieren |
| Fehlende Berechtigung | stoppen und eskalieren |
| Fachlicher Konflikt | menschliche Entscheidung |
| Teilweise ausgeführte Aktion | Kompensation oder manueller Recovery-Prozess |
| Policy-Verletzung | sofort stoppen und protokollieren |

Ein Agent benötigt eine maximale Anzahl an Wiederholungen. Sonst kann eine Ausführungsschleife unnötige Kosten und Systemlast erzeugen.

## Multi-Agent-Systeme sind kein Standardziel

Mehrere Agenten können Aufgaben nach Rollen aufteilen, beispielsweise Research, Planning, Validation oder Execution. Das kann bei klar getrennten Verantwortlichkeiten sinnvoll sein, erzeugt aber zusätzliche Modellaufrufe, schwierigere Zustandsübergaben, mehr Fehlerquellen und komplexeres Monitoring.

Ein einzelner Agent mit gut definierten Tools und deterministischer Orchestrierung ist häufig der bessere Startpunkt. Multi-Agent sollte eingesetzt werden, wenn die Aufteilung einen messbaren Vorteil bringt.

## Governance und Kontrolle über den gesamten Lebenszyklus

AI-Agenten verbinden generative Modelle mit realen Systemen und Aktionen. Governance muss deshalb Strategie, Entwicklung, Betrieb, Evaluation und Nachweis gemeinsam abdecken.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-agents-img5-de.png"
        alt="Governance- und Kontrollrahmen für Enterprise AI-Agenten mit Strategie, Design, Betrieb, Review, Compliance, Governance-Säulen und kontrolliertem Agentenzyklus"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Leitplanken müssen über den vollständigen Lebenszyklus wirken: von Zweck und Design über Betrieb und Evaluation bis zu Audit und Compliance.
    </figcaption>
</figure>

### 1. Strategie und Politik

Vor der technischen Umsetzung müssen Geschäftsziel, erlaubte und ausgeschlossene Aufgaben, Verantwortlichkeiten, Risikotoleranz, zugelassene Datenklassen und Erfolgskriterien festgelegt werden.

### 2. Design und Aufbau

Governance by Design umfasst:

- minimale Tool-Rechte
- sichere Standardwerte
- getrennte Lese- und Schreibaktionen
- Datenminimierung
- klar definierte Zustände
- Freigabepunkte
- Tests gegen unerwartete Eingaben
- Prompt-Injection-Schutz
- Fallback und Rollback
- Versionierung

### 3. Betrieb und Überwachung

Im Betrieb werden kontinuierlich beobachtet:

- Erfolgsquote
- Tool-Fehler
- Latenz und Kosten
- Schrittzahl
- Policy-Stopps
- Freigabe- und Abbruchquote
- ungewöhnliche Tool-Sequenzen
- Modell- und Prompt-Drift
- Benutzerfeedback

### 4. Prüfen und verbessern

Agenten müssen mit normalen Vorgängen, Grenzfällen, unvollständigen Informationen, widersprüchlichen Daten, Tool-Ausfällen, falschen Berechtigungen und nicht erreichbaren Zielen getestet werden.

Verbesserungen können Tool-Beschreibungen, Schemata, Systemanweisungen, Policies, Retrieval, Modelle, Schwellenwerte oder Freigaberegeln betreffen.

### 5. Absichern und Compliance

Nachweisbar sein sollten:

- welche Agentenversion ausgeführt wurde
- welche Tools verfügbar waren
- welche Policies galten
- welche Freigaben erteilt wurden
- welche Daten verwendet wurden
- welche Aktionen ausgeführt wurden
- welche Risiken bewertet wurden
- welche Tests bestanden wurden
- wer für Betrieb und Ergebnis verantwortlich ist

Governance ist keine separate Dokumentationsphase am Ende. Sie ist eine durchgängige technische und organisatorische Kontrollschicht.

## Beispiel: Agent für eingehende Serviceanfragen

Ein realistischer Agent könnte neue Kundenanfragen vorbereiten.

### Ziel

> Analysiere neue Anfragen, finde den zugehörigen Kunden, bestimme Kategorie und Priorität, rufe relevante Richtlinien ab und erstelle einen Ticket- sowie Antwortentwurf. Sende keine Nachricht ohne Freigabe.

### Erlaubte Tools

```text
read_incoming_request
find_customer
retrieve_service_policy
check_known_outage
create_ticket_draft
create_response_draft
request_human_approval
```

### Nicht erlaubte Tools

```text
send_email
change_contract
issue_refund
delete_customer
```

### Ablauf

```flow linear vertical
Neue Anfrage lesen
Personenbezogene Daten minimieren
Kundenbezug ermitteln
Kategorie und Priorität bestimmen
Relevante Richtlinie abrufen
Bekannte Störung prüfen
Ticketentwurf erstellen
Antwortentwurf erzeugen
Menschliche Freigabe anfordern
Deterministischer Versandprozess
```

### Abbruchbedingungen

- Kunde nicht eindeutig gefunden
- sensible Anfrage außerhalb des Use Cases
- widersprüchliche Richtlinien
- fehlende Berechtigung
- mehr als zwei fehlgeschlagene Tool-Aufrufe
- keine ausreichende Evidenz
- maximaler Bearbeitungszeitraum überschritten

Der Agent ersetzt in diesem Beispiel nicht das Ticketing-System. Er koordiniert kontrollierte Schritte rund um vorhandene Systeme.

## Was für einen produktiven Agenten dokumentiert werden sollte

| Bereich | Zu dokumentieren |
| --- | --- |
| **Use Case** | Ziel, Benutzergruppen, Nutzen und Ausschlüsse |
| **Autonomiestufe** | Analyse, Vorbereitung, Freigabe oder eigenständige Ausführung |
| **Owner** | Fachlicher, technischer und Governance-Verantwortlicher |
| **Modell** | Anbieter, Modellversion und Konfiguration |
| **Orchestrierung** | Framework, Zustandsmodell und Ausführungslogik |
| **Tools** | Zweck, Schema, Owner, Version und Risikoklasse |
| **Berechtigungen** | Benutzer-, Agenten- und Systemidentitäten |
| **Policies** | Erlaubte Aktionen, Limits und Stop-Bedingungen |
| **Freigaben** | Welche Aktionen von wem freigegeben werden |
| **Zustand** | Speicherung, Aufbewahrung und Wiederaufnahme |
| **Daten** | Eingaben, Klassifikation, Speicherorte und Übertragungswege |
| **Evaluation** | Aufgabenqualität, Tool-Nutzung, Sicherheit und Kosten |
| **Monitoring** | Runs, Schritte, Latenz, Kosten, Fehler und Abweichungen |
| **Recovery** | Retry, Idempotenz, Rollback und Kompensation |
| **Versionierung** | Modell, Prompt, Tools, Policies und Workflow |
| **Retirement** | Abschaltung, Widerruf von Rechten und Datenbereinigung |

## Die zentrale Erkenntnis

> **Ein AI-Agent ist keine einzelne Modellfunktion. Er ist eine kontrollierte Ausführungsarchitektur aus Modell, Zustand, Tools, Orchestrierung, Identität, Policies, Freigaben und Monitoring.**

Tool Calling ermöglicht Aktionen. Eine Agentenschleife koordiniert mehrere Schritte. Deterministische Prozesse setzen die sicheren Grenzen.

Die beste Architektur verwendet daher nicht möglichst viel Autonomie, sondern genau so viel flexible Entscheidungslogik, wie der Use Case benötigt.

## Die Serie im Überblick

```flow linear vertical
AI Foundations — How AI Works [done]
Language Models and Providers [done]
Enterprise Data, Embeddings and RAG [done]
AI Agents and Automation [active]
Known Problems and Failure Modes
Evaluation, Costs and Operations
AI Governance
```

Part 5 behandelt als Nächstes **Known Problems and Failure Modes**: Halluzinationen, Prompt Injection, Datenabfluss, Tool-Missbrauch, Schleifen, fehlerhafte Automatisierung und weitere Risiken, die bei agentischen Systemen zusätzliche Auswirkungen entfalten können.

## Quellen und weiterführende Dokumentation

- [OpenAI Agents SDK — Overview](https://openai.github.io/openai-agents-python/)
- [OpenAI Agents SDK — Tools](https://openai.github.io/openai-agents-python/tools/)
- [OpenAI Agents SDK — Running Agents](https://openai.github.io/openai-agents-python/running_agents/)
- [OpenAI Agents SDK — Human in the Loop](https://openai.github.io/openai-agents-python/human_in_the_loop/)
- [OpenAI Agents SDK — Tracing](https://openai.github.io/openai-agents-python/tracing/)
- [Anthropic — Tool Use](https://docs.anthropic.com/en/docs/agents-and-tools/tool-use/overview)
- [Anthropic — Implement Tool Use](https://docs.anthropic.com/en/docs/agents-and-tools/tool-use/implement-tool-use)
- [Anthropic — Computer Use and the Agent Loop](https://docs.anthropic.com/en/docs/agents-and-tools/computer-use)
- [Google Agent Development Kit](https://google.github.io/adk-docs/)
- [Google ADK — Safety and Security for AI Agents](https://google.github.io/adk-docs/safety/)
- [Google ADK — Evaluating Agents](https://google.github.io/adk-docs/evaluate/)
- [NIST AI Risk Management Framework](https://www.nist.gov/itl/ai-risk-management-framework)
- [NIST AI RMF — Generative AI Profile](https://www.nist.gov/publications/artificial-intelligence-risk-management-framework-generative-artificial-intelligence)
