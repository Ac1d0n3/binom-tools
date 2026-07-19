
## 1. Ziel

Der **Sprint Planner** ist ein eigener Bereich innerhalb von BN Tools, vergleichbar mit den bestehenden Stories und Serien, aber für ausführbare Arbeitspläne.

Er soll vollständig ohne Datenbank und ohne Login funktionieren.

Der Sprint Planner soll:

- Planvorlagen aus Markdown-Dateien laden
- persönliche und teambezogene Planinstanzen erzeugen
- Wochen oder Sprints mit Aufgaben, Deliverables, Feldern und Notizen darstellen
- Aufgaben und Deliverables per Checkbox verwalten
- Fortschritt automatisch berechnen
- erledigte Inhalte ein- und ausblenden
- neue Sprints direkt in der Oberfläche erstellen
- vorhandene Sprints bearbeiten, duplizieren, verschieben und löschen
- lokale Benutzerprofile und Teams definieren
- Aufgaben und Deliverables Personen oder Teams zuordnen
- nach Team, Person, Status und Sprint filtern
- Deutsch und Englisch vollständig unterstützen
- alle Daten lokal im Browser speichern
- Arbeitsstände als JSON exportieren und importieren

Die Markdown-Dateien definieren wiederverwendbare Vorlagen. Persönlicher Fortschritt, lokale Benutzer, Teams und über die Oberfläche ergänzte Inhalte werden separat in `localStorage` gespeichert.

---

## 2. Verbindliche technische Leitplanken

- Keine Datenbank
- Kein Login
- Keine Benutzerkonten
- Keine serverseitige Speicherung von Fortschrittsdaten
- Keine externe Synchronisierung in Version 1
- Bestehende BN-Tools-Architektur, Komponenten, Styles und Routing-Konventionen wiederverwenden
- Keine neue UI-Bibliothek einführen
- Bestehenden Markdown-Parser erweitern, falls möglich
- Keine bestehende Story-, Series- oder Tool-Funktionalität beschädigen
- Planvorlagen liegen als `.de.md` und `.en.md` im Repository
- Persönlicher Status liegt in `localStorage`
- Export und Import erfolgen als JSON
- Stabile IDs für Pläne, Sprints, Aufgaben, Deliverables, Felder, Benutzer und Teams
- Responsive und barrierearme Umsetzung
- Serverseitige Darstellung und clientseitige Interaktivität sauber trennen
- Bestehende Design Tokens, Buttons, Cards, Accordions, Dialoge, Formulare und Filter wiederverwenden

Empfohlene Storage Keys:

```text
bn-tools:sprint-planner:workspace:v1
bn-tools:sprint-planner:preferences:v1
```

---

## 3. Bereich und Navigation

Neuer Hauptnavigationspunkt:

```text
Sprint Planner
```

Unterbereiche:

```text
Meine Pläne
Vorlagen
Teams & Personen
Archiv
```

Empfohlene Routen:

```text
/sprint-planner
/sprint-planner/templates
/sprint-planner/create
/sprint-planner/people
/sprint-planner/{instanceId}
/sprint-planner/{instanceId}/settings
```

Accounts-API (nur eingeloggt):

```text
/api/sprint-planner/user-templates
/api/sprint-planner/user-templates/{templateId}
```

Die tatsächlichen Routen müssen an die vorhandene Routing- und Lokalisierungsstruktur von BN Tools angepasst werden.

---

## 4. Hauptansichten

### 4.1 Übersicht

Die Übersichtsseite zeigt:

- aktive Planinstanzen
- verfügbare Vorlagen
- archivierte Planinstanzen

Jede Plan-Karte zeigt mindestens:

- Titel
- Beschreibung
- Dauer
- Einheit
- Fortschritt
- aktuellen Sprint
- Status
- zugeordnetes Team
- letzte Änderung
- Aktion zum Öffnen oder Starten

Filter:

```text
Alle
Meine Pläne
Team-Pläne
Abgeschlossen
Archiviert
```

### 4.2 Planansicht

Die Planansicht zeigt:

- Titel und Beschreibung
- Startdatum
- Gesamtfortschritt
- aktuellen Sprint
- zugeordnetes Team
- beteiligte Personen
- Filter
- alle Sprints als aufklappbare Bereiche
- Aufgaben
- Deliverables
- dynamische Felder
- Notizen
- benutzerdefinierte Aufgaben
- benutzerdefinierte Sprints
- Export, Import, Duplizieren, Archivieren und Zurücksetzen

### 4.3 Teams & Personen

Diese Ansicht verwaltet lokale Profile und Teams.

Funktionen:

- Person anlegen
- Person bearbeiten
- Person archivieren
- Team anlegen
- Team bearbeiten
- Personen einem Team zuordnen
- lokale aktive Person auswählen
- Standard-Team festlegen
- Import und Export

Es gibt keinen Login. Die Auswahl der aktiven Person dient nur der lokalen Filterung und Zuordnung.

---

## 5. Sprachunterstützung DE / EN

Alle festen UI-Texte laufen über das vorhandene Übersetzungssystem.

Mindestens zu übersetzen:

- Navigation
- Buttons
- Filter
- Statuswerte
- Fehlermeldungen
- Dialoge
- Formulare
- leere Zustände
- Import- und Exporthinweise
- Bestätigungsdialoge
- Accessibility Labels

Planvorlagen liegen paarweise vor:

```text
data-reporting-first-quarter.de.md
data-reporting-first-quarter.en.md
```

Beide Dateien verwenden dieselben:

- `slug`
- `version`
- Sprint-IDs
- Task-IDs
- Deliverable-IDs
- Field-IDs

Nur sichtbare Texte werden übersetzt. Dadurch bleibt der Fortschritt beim Sprachwechsel erhalten.

Bei benutzerdefinierten Inhalten:

- Text in der aktiven Sprache ist verpflichtend
- Übersetzung in die zweite Sprache ist optional
- fehlt die Übersetzung, wird der vorhandene Text als Fallback verwendet
- Übersetzungen können später ergänzt werden

---

## 6. Planvorlage und Planinstanz

### 6.1 Planvorlage

Eine Markdown-Datei definiert:

- Metadaten
- Beschreibung
- Sprints
- Aufgaben
- Deliverables
- dynamische Felder
- Hinweise

Sie enthält keinen persönlichen Fortschritt.

### 6.2 Planinstanz

Eine Planinstanz enthält:

- Instanz-ID
- Template-Slug
- Template-Version
- Startdatum
- Status
- Team-Zuordnung
- beteiligte Personen
- erledigte Aufgaben
- erledigte Deliverables
- Feldwerte
- Notizen
- benutzerdefinierte Aufgaben
- benutzerdefinierte Deliverables
- benutzerdefinierte Sprints
- Änderungen an Vorlagen-Sprints
- Sortierung
- Archivstatus
- Zeitstempel

Mehrere Instanzen derselben Vorlage müssen möglich sein.

---

## 7. Markdown-Format

### 7.1 Frontmatter

```yaml
---
type: sprint-plan
title: Data & Reporting – Erstes Quartal
slug: data-reporting-first-quarter
description: Die Daten- und Reporting-Landschaft verstehen und die erste nachhaltige Verbesserung umsetzen.
duration: 13
unit: week
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Data Platform
  - Reporting
  - Governance
---
```

Pflichtfelder:

```text
type
title
slug
description
duration
unit
version
locale
```

`type` muss den Wert `sprint-plan` besitzen.

### 7.2 Sprint-Block

````markdown
```sprint
id: week-01
number: 1
title: Orientierung und Mandat
goal: Auftrag, Erwartungen und relevante Stakeholder verstehen.

linkedStories:
  - data-ownership-stewardship

links:
  - label: DQ Rules Generator
    href: /tools/dbt-dq-rules-generator

tasks:
  - id: align-management-expectations
    label: Erwartungen mit der Führung abstimmen
    assigneeType: person
    assigneeId: null
    helpText: |
      Kurze, handlungsorientierte Anleitung zur Aufgabe.
    linkedStories: data-ownership-stewardship, eight-pillars
    helpLinks:
      - label: Data Ownership & Stewardship
        href: /playbooks/data-ownership-stewardship
  - id: identify-stakeholders
    label: Relevante Stakeholder identifizieren
    assigneeType: team
    assigneeId: null

deliverables:
  - id: stakeholder-list
    label: Stakeholder-Liste erstellt
  - id: initial-mandate
    label: Initialen Auftrag dokumentiert

fields:
  - id: management-expectations
    label: Erwartungen der Führung
    type: textarea
    placeholder: Ziele, Erwartungen und Erfolgskriterien
  - id: primary-owner
    label: Hauptverantwortlicher
    type: text
    placeholder: Name oder Rolle

notes: true
```
````

Pflichtfelder pro Sprint:

```text
id
number
title
goal
```

Optionale Felder:

```text
description
tasks
deliverables
fields
notes
estimated_effort
linkedStories
stories
links
flowVariant
flowLayout
flowSteps
```

Sprint-Stories und Links:

```text
stories        = [{ slug, required }]  (Pflichtlektüre vs. optional)
linkedStories  = Playbook-Slugs (Legacy → required: true)
links          = externe Hilfe-/Community-Links [{ label, href }]
flowSteps      = Schritte für Playbook-FLOW-Darstellung (mind. 2)
```

`links` und `helpLinks` dürfen **nur** externe URLs sein (`https://`, `http://`, `mailto:`). Keine bare Playbook-Slugs und keine `/playbooks/…`-Pfade — Stories gehören ausschließlich ins Stories-Feld.

Task-/Deliverable-Hilfe:

```text
helpText       = lokalisierter Hilfetext
stories        = [{ slug, required }]
linkedStories  = Legacy-CSV/Liste → required: true
helpLinks      = [{ label, href }]  (nur externe URLs)
demoCode       = optionaler Demo-/Checklisten-Block
attachments    = optionale Anhänge (siehe § Anhänge)
table          = optionale Datentabelle (Spalten + Zeilen)
```

### Task-Tabellen

Aufgaben/Deliverables können eine editierbare Tabelle tragen (z. B. Quellen sammeln):

```text
table: {
  columns: [{ id, label }],
  rows: [{ id, cells: { [columnId]: string } }]
}
```

Vorlagen/Creator können Spalten vorgeben (z. B. Source, Owner, Access, Consumed by); Instanzen füllen Zeilen. In Markdown-Vorlagen: `tableColumns: Name, Rolle, …`. Darstellung als aufklappbare Tabelle unter der Item-Zeile (Zeilen bearbeiten), im Item-Dialog höchstens Spalten (Custom-Items), und in Detailed-/Documentation-Reports (readonly).

Hilfe-Panel (UI): Story-Karten mit Titel, Pflicht/Optional, gelesen/ungelesen, Öffnen, Als gelesen markieren. Item-Zeile zeigt `?` mit Lese-Fortschritt, keine Slug-Badges.

### Anhänge

Aufgaben und Deliverables können Anhänge tragen:

```text
attachments: [{
  id, name, mime, size,
  kind: 'link' | 'file',
  href, previewable,
  uploadedAt, uploadedBy,
  localBlob?
}]
```

Erlaubt: Bilder, PDF, Word, PowerPoint, Excel (inkl. CSV). Max. 10 MB pro Datei.

Speicherung:

- **Link-Anhänge:** immer (lokal und Accounts)
- **Datei-Anhänge lokal/Demo:** Metadaten im Workspace-JSON, Blob in IndexedDB (`bn-tools-sprint-planner-blobs-v1`)
- **Datei-Anhänge Accounts (eingeloggt):** Upload unter `/api/sprint-planner/plans/{planId}/attachments`, Dateien neben dem Plan-JSON auf dem Server

Im Detailed-Report erscheinen Bilder als Preview, andere Dateien als Download-Link.

Blocker (Instanz-Overrides / Custom-Items):

```text
status: blocked
blockerReason: "…"
blockerSince: YYYY-MM-DD
```

Sprint-interne Abhängigkeiten (Parallel vs. Kette):

```text
dependsOn: string[]   // statusKeys derselben Sprint-ID
```

- **Parallel** = leeres `dependsOn`; **Kette** = ein oder mehrere Vorgänger (Tasks und/oder Deliverables im gleichen Sprint).
- Solange ein Vorgänger nicht `completed` ist, ist das abhängige Item **effektiv `blocked`** (Auto-Grund „Wartet auf …“), ohne den gespeicherten Basisstatus zu überschreiben.
- Manuelles `blocked` + `blockerReason` bleibt für externe Blockaden parallel möglich.
- Keine Selbst-Referenzen, keine Zyklen, keine Cross-Sprint-Refs.
- In Template-YAML optional als lokale Item-IDs (`dependsOn: [task-a]`), zur Laufzeit zu Status-Keys aufgelöst.

Statusbericht: druckbare HTML-Ansicht (Browser „Als PDF speichern“) mit drei Modi:

- **Executive (CEO):** KPI-Strip (Fortschritt, aktueller Sprint, Blocker-Anzahl, Unassigned), Sprint-Ziel, Top-Blocker, offene Items des aktuellen Sprints, Fortschritt je Sprint
- **Detailed:** vollständige Item-Liste pro Sprint inkl. Status, Assignee, Notiz, Hilfetext, Hilfe-Links, Tabellen und Anhänge
- **Documentation:** Doku ohne Fortschritts-KPIs — Ziele, Hilfetext, externe Links, Tabellen, Notizen, Anhänge

Stack-Vorlagen (neben dem generischen First Quarter):

```text
data-reporting-fq-fivetran-snowflake-qlik
data-reporting-fq-fivetran-snowflake-powerbi
data-reporting-fq-fabric-qlik-qvd
```

`helpText` und `helpLinks[].label` sind lokalisiert (DE/EN-Dateien). `href`-Werte und Story-Slugs sind strukturell und müssen zwischen Locales übereinstimmen.

### 7.3 Feldtypen

Version 1 unterstützt:

```text
text
textarea
number
date
select
multiselect
url
checkbox
person
team
```

Unbekannte Feldtypen werden als `text` gerendert und erzeugen im Entwicklungsmodus eine Warnung.

---

## 8. Stabile IDs

Alle interaktiven Elemente benötigen explizite IDs.

Status-ID:

```text
templateSlug:sprintId:itemType:itemId
```

Beispiel:

```text
data-reporting-first-quarter:week-01:task:identify-stakeholders
```

IDs dürfen bei einer Übersetzung nicht verändert werden.

Lokale IDs erhalten einen Präfix:

```text
person_
team_
plan_
sprint_
task_
deliverable_
```

---

## 9. Neue Sprints direkt in der Oberfläche

Der Benutzer kann innerhalb einer Planinstanz neue Sprints erstellen.

Button:

```text
Sprint hinzufügen
```

Formularfelder:

- Titel DE
- Titel EN
- Ziel DE
- Ziel EN
- Beschreibung DE
- Beschreibung EN
- Position
- optionale Startwoche
- optionale Zielwoche
- Team
- verantwortliche Person
- Aufgaben
- Deliverables
- dynamische Felder
- Notizen aktivieren

Nach dem Speichern wird der Sprint ausschließlich in der Planinstanz abgelegt. Die Markdown-Vorlage bleibt unverändert.

Erforderliche Aktionen:

- Sprint erstellen
- Sprint bearbeiten
- Sprint duplizieren
- Sprint verschieben
- Sprint löschen
- Sprint zurücksetzen
- Sprint ein- und ausklappen

Änderungen an Vorlagen-Sprints werden als Overlay gespeichert.

---

## 10. Aufgaben und Deliverables

Pro Sprint können Aufgaben und Deliverables verwaltet werden.

Funktionen:

- hinzufügen
- bearbeiten
- abhaken
- duplizieren
- sortieren
- löschen
- Person zuordnen
- Team zuordnen
- Fälligkeitsdatum setzen
- Status setzen
- Priorität setzen
- Notiz ergänzen
- Hilfetext und Hilfe-Links hinterlegen (Vorlage und eigene Aufgaben)
- Hilfe-Panel öffnen (ohne Seitenwechsel)

Tasks und Deliverables bleiben getrennt:

```text
Tasks        = Was ist zu tun?
Deliverables = Welches belastbare Ergebnis muss vorliegen?
```

### 10.1 Hilfe-Panel

Aufgaben mit `helpText`, `helpLinks` oder `linkedStories` zeigen einen Hilfe-Button (`?`).

Verhalten:

```text
Desktop  = rechte Drawer-Sidebar (~360px), Plan bleibt bedienbar
Mobile   = Bottom-Sheet (~55–70vh)
Links    = öffnen in neuem Tab (Plan-Kontext bleibt erhalten)
Schließen = X, Backdrop oder Escape
```

Sprint-Links (`linkedStories` / `links`) werden im Sprint-Körper angezeigt und sind im Sprint-Dialog editierbar.

Empfohlene Statuswerte:

```text
open
in_progress
on_hold
blocked
completed
```

Darstellung:

- Status erscheint als Meta-Text am Item (keine Chips neben dem Titel).
- Rahmenfarbe: `blocked` → amber; `in_progress` → blau; `on_hold` → slate/grau.
- Plan-Header: Status-Übersicht mit Icons + Counts für alle fünf Werte.
- Zugeklappte Sprint-Summary: kompakte Marker (Icon + Badge) für Blockiert / In Arbeit / Pausiert.
- `dependsOn` (gleicher Sprint): offene Vorgänger → effektiver Status `blocked` mit Auto-Hinweis; leer = parallel.

Empfohlene Prioritäten:

```text
low
normal
high
critical
```

---

## 11. Lokale Benutzer und Teams ohne Login

### 11.1 Personen

Eine Person besitzt:

```json
{
  "id": "person_01",
  "displayName": "Thomas Lindackers",
  "shortName": "TLI",
  "email": "",
  "role": "BI Platform Architect",
  "colorToken": "accent-1",
  "archived": false
}
```

`shortName` ist ein **Trigramm** (genau 3 Zeichen) und erscheint in der Planansicht als farbiger Avatar-Chip.

E-Mail ist optional und wird nicht verwendet, um jemanden anzumelden.

### 11.2 Teams

Ein Team besitzt:

```json
{
  "id": "team_bi",
  "name": {
    "de": "BI & Data Platform",
    "en": "BI & Data Platform"
  },
  "description": {
    "de": "Verantwortlich für Datenplattform und Reporting.",
    "en": "Responsible for the data platform and reporting."
  },
  "shortName": "BIP",
  "colorToken": "accent-2",
  "memberIds": [
    "person_01",
    "person_02"
  ],
  "archived": false
}
```

Teams haben `colorToken` und optionales `shortName`. Planinstanzen nutzen `teamIds: string[]` (Legacy-`teamId` wird migriert).
### 11.3 Aktive Person

Da es keinen Login gibt, wird eine aktive lokale Person ausgewählt:

```text
Arbeiten als: Thomas Lindackers
```

Diese Auswahl beeinflusst:

- Filter „Meine Aufgaben“
- Standardzuordnung bei neuen Aufgaben und Deliverables
- Standardzuordnung aller Template-Tasks beim Start einer Planinstanz (wenn eine aktive Person gesetzt ist)
- Anzeige persönlicher Fortschritte
- Aktion „Übernehmen“ (Pick) für unzugewiesene Elemente
- keine Rechte oder Sicherheit

Die aktive Person ist keine Authentifizierung.

Unzugewiesene Elemente zeigen in der Zeile **Übernehmen** (setzt die aktive Person) und **Zuweisen** (öffnet den Dialog). Wenn alle Elemente eines Plans unzugewiesen sind, erscheint ein Banner mit **Alle übernehmen**.

### 11.4 Berechtigungen

In Version 1 gibt es keine echten Berechtigungen.

Alle lokal gespeicherten Personen und Teams können bearbeitet werden.

Die Oberfläche muss deutlich machen:

```text
Lokaler Arbeitsbereich – keine Anmeldung, keine Zugriffskontrolle
```

---

## 12. Workspace-Datenmodell

Storage Key:

```text
bn-tools:sprint-planner:workspace:v1
```

Root-Struktur:

```json
{
  "schemaVersion": 1,
  "workspace": {
    "id": "workspace_default",
    "name": "Local Workspace",
    "locale": "de",
    "activePersonId": "person_01",
    "defaultTeamId": "team_bi"
  },
  "people": {},
  "teams": {},
  "instances": {}
}
```

Beispiel einer Planinstanz:

```json
{
  "id": "plan_20260901_a1b2c3",
  "templateSlug": "data-reporting-first-quarter",
  "templateVersion": 1,
  "translations": {
    "de": {
      "title": "Data & Reporting – Erstes Quartal"
    },
    "en": {
      "title": "Data & Reporting – First Quarter"
    }
  },
  "startedAt": "2026-09-01",
  "status": "active",
  "teamIds": ["team_bi"],
  "participantIds": [
    "person_01",
    "person_02"
  ],
  "completedTasks": [],
  "completedDeliverables": [],
  "fieldValues": {},
  "sprintNotes": {},
  "customTasks": {},
  "customDeliverables": {},
  "customSprints": [],
  "sprintOverrides": {},
  "archived": false,
  "createdAt": "2026-09-01T08:00:00Z",
  "updatedAt": "2026-09-01T08:00:00Z"
}
```

---

## 13. Fortschrittsberechnung

Sprint-Fortschritt:

```text
erledigte Aufgaben + erledigte Deliverables
------------------------------------------------
gesamte Aufgaben + gesamte Deliverables
```

Benutzerdefinierte Aufgaben und Deliverables zählen mit.

Dynamische Felder zählen standardmäßig nicht zum Fortschritt.

Gesamtfortschritt wird über alle Elemente aller Sprints berechnet, nicht als Durchschnitt der Sprint-Prozente.

Ein Sprint gilt als abgeschlossen, wenn:

- alle Aufgaben abgeschlossen sind
- alle Deliverables abgeschlossen sind
- alle benutzerdefinierten Elemente abgeschlossen sind

---

## 14. Aktueller Sprint und Planwochen

Bei `unit: week`:

```text
currentSprintNumber = floor((today - startedAt) / 7 Tage) + 1
```

Grenzen:

- vor dem Startdatum: **kein** aktueller Sprint (Anzeige „Noch nicht gestartet · ab …“); Sprint 1 trägt Badge „Startet bald“
- nach dem letzten Sprint: letzter Sprint
- manuelle Auswahl bleibt möglich

Jeder Sprint N erhält ab `startedAt` einen Datumsrange und eine ISO-Kalenderwoche:

```text
rangeStart = startedAt + (N - 1) × 7 Tage
rangeEnd   = rangeStart + 6 Tage
```

Anzeige z. B. `#1 · KW 34 · 17.–23. Aug`. Tasks zeigen geplante Fälligkeit = Wochenende des Sprints (oder explizites `dueDate`).

**Verzögerung:** Offene Items in Sprints vor der aktuellen Planwoche zählen als Rückstand. Die UI zeigt Wochen hinter Plan und die verursachenden Sprints.

Personen, die einem Task zugewiesen werden, landen automatisch in `participantIds` (inkl. Backfill beim Öffnen).

---

## 15. Filter

Mindestens:

```text
Nur aktuelle Planwoche
Erledigte ausblenden
Nur offene Elemente
Blockierte Elemente
Meine Aufgaben
Mein Team
Person auswählen
Team auswählen
Priorität
Status
```

Filtereinstellungen werden lokal gespeichert.

---

## 16. Notizen

Pro Sprint und optional pro Aufgabe:

- automatisches Speichern
- Debounce bei Eingaben
- sichtbarer Speicherstatus
- Zeilenumbrüche erhalten
- sinnvolle Maximallänge
- verständliche Fehlermeldung

---

## 17. Export und Import

### 17.1 Export

Exportierbar:

- einzelne Planinstanz
- kompletter lokaler Workspace
- Personen und Teams
- alle Pläne

Dateinamen:

```text
sprint-plan-{slug}-{startdate}.json
bn-tools-sprint-workspace-{date}.json
```

Workspace-Export:

```json
{
  "exportType": "bn-tools-sprint-workspace",
  "schemaVersion": 1,
  "exportedAt": "2026-09-13T12:00:00Z",
  "workspace": {},
  "people": {},
  "teams": {},
  "instances": {}
}
```

### 17.2 Import

Prüfen:

- valides JSON
- passender `exportType`
- unterstützte `schemaVersion`
- Mindestfelder
- ID-Konflikte

Konfliktoptionen:

```text
Bestehende Daten ersetzen
Zusammenführen
Mit neuen IDs importieren
Abbrechen
```

Importierte Daten werden validiert und normalisiert.

---

## 18. Zurücksetzen und Löschen

Aktionen:

```text
Sprint zurücksetzen
Plan zurücksetzen
Plan duplizieren
Plan archivieren
Plan löschen
Workspace exportieren
Lokale Daten vollständig löschen
```

Destruktive Aktionen benötigen eine Bestätigung.

---

## 19. Darstellung

Bestehendes BN-Tools-Design verwenden.

Plan-Kopf:

- Titel
- Beschreibung
- Startdatum
- aktueller Sprint
- Gesamtfortschritt
- Team
- beteiligte Personen
- Planstatus
- Aktionen

Sprint-Kopf:

- Nummer
- Titel
- Ziel
- Fortschritt
- Status
- Team
- verantwortliche Person
- Kennzeichnung der aktuellen Woche
- Auf- und Zuklappen

Fortschritt darf nicht nur farblich kommuniziert werden.

---

## 20. Barrierefreiheit

Mindestens:

- semantische Überschriften
- echte Form Controls
- sichtbare Labels
- Accordion-Buttons mit `aria-expanded`
- Fortschrittsanzeigen mit Text und ARIA-Werten
- vollständige Tastaturbedienung
- sichtbare Fokuszustände
- keine reine Farbcodierung
- korrektes Fokusmanagement in Dialogen
- verständliche Fehlermeldungen
- `prefers-reduced-motion` berücksichtigen

---

## 21. Fehlerbehandlung

Abfangen:

- fehlende Markdown-Datei
- ungültiges Frontmatter
- nicht parsebarer Sprint-Block
- doppelte IDs
- `localStorage` nicht verfügbar
- `localStorage` voll
- beschädigte gespeicherte Daten
- ungültige Importdatei
- nicht unterstützte Schema-Version
- Verweise auf gelöschte Personen oder Teams

In Produktion verständliche Hinweise, im Entwicklungsmodus präzise technische Details.

---

## 22. Validierung

Beim Laden einer Vorlage:

- `type === sprint-plan`
- Pflichtfelder vorhanden
- Slug eindeutig
- Sprint-IDs eindeutig
- Sprint-Nummern eindeutig
- Task-, Deliverable- und Field-IDs innerhalb eines Sprints eindeutig
- DE- und EN-Datei haben identische Struktur-IDs
- Versionen stimmen überein
- Dauer passt zur Anzahl definierter Sprints oder erzeugt nur eine Warnung

---

## 23. Beispielvorlage

Als erste Vorlage implementieren:

```text
Data & Reporting – Erstes Quartal
13 Wochen
```

Inhalte:

1. Orientierung und Mandat
2. Reporting-Landschaft
3. Quellsysteme
4. Datenentstehung
5. End-to-End-Lineage
6. KPI-Inventar
7. Datenqualität und Risiken
8. Architekturdiagnose
9. Priorisierung
10. Zielbild
11. Pilot umsetzen
12. Pilot validieren
13. Quartalsabschluss

Dafür zwei Dateien anlegen:

```text
data-reporting-first-quarter.de.md
data-reporting-first-quarter.en.md
```

Alle IDs müssen in beiden Dateien identisch sein.

---

## 24. Tests

Mindestens testen:

- Markdown-Parsing
- DE-/EN-Strukturvergleich
- Fortschrittsberechnung
- aktueller Sprint
- Erstellen, Bearbeiten und Löschen eigener Sprints
- Zuordnung von Personen und Teams
- Default-Assignee beim Planstart und Pick/Claim-all
- Filter „Meine Aufgaben“
- Anhänge (Normalisierung, MIME-Whitelist)
- LocalStorage-Lesen und -Schreiben
- beschädigte Daten
- Export und Import
- Migration der Schema-Version
- Sprachwechsel ohne Verlust des Fortschritts

Vorhandene Testinfrastruktur verwenden. Keine neue Testbibliothek ohne zwingenden Grund.

---

## 25. Akzeptanzkriterien

Die Umsetzung ist abgeschlossen, wenn:

1. Der neue Bereich in der Navigation erreichbar ist.
2. DE und EN vollständig funktionieren.
3. Eine Vorlage aus Markdown geladen wird.
4. Eine Planinstanz ohne Datenbank gestartet werden kann.
5. Aufgaben und Deliverables abgehakt werden können.
6. Fortschritt korrekt berechnet wird.
7. Erledigte Elemente ausgeblendet werden können.
8. Neue Sprints über die Oberfläche angelegt werden können.
9. Sprints bearbeitet, dupliziert, verschoben und gelöscht werden können.
10. Mehrere lokale Personen und Teams angelegt werden können.
11. Aufgaben Personen oder Teams zugeordnet werden können.
12. „Meine Aufgaben“ anhand der aktiven lokalen Person funktioniert.
13. Alle Daten nach einem Reload erhalten bleiben.
14. Export und Import funktionieren.
15. Bestehende BN-Tools-Bereiche unverändert funktionieren.
16. Responsive Darstellung und Tastaturbedienung funktionieren.
17. Es wurden passende Tests ergänzt.
18. Unzugewiesene Tasks können per Übernehmen/Zuweisen/Alle übernehmen der aktiven Person zugeordnet werden.
19. Berichte Executive, Detailed und Documentation sind druckbar; Detailed zeigt Notizen, Hilfetext, Links, Tabellen und Anhänge; Documentation ohne Progress-KPIs.
20. Anhänge (Links immer; Dateien lokal via IndexedDB bzw. Accounts via Upload-API) funktionieren.
21. Assignee-Filter sind XOR (All / My tasks / Unassigned / Person / Team) — nicht UND.
22. Hilfe-Links sind nur externe URLs; Stories bleiben im Stories-Feld.
23. Eingeloggte Nutzer können User-Templates erstellen, bearbeiten und löschen (Plan Creator).
24. Task-Tabellen mit vorgebbaren Spalten und editierbaren Zeilen funktionieren.
25. Status als Meta-Text an Items; Blocked/In-Arbeit/Pausiert-Marker in zugeklappten Sprint-Summaries.
26. Sprint-interne `dependsOn`: Auto-blocked bei offenen Vorgängern; Zyklen und Cross-Sprint-Refs abgewiesen.
26. „My Plans“ öffnet den zuletzt geöffneten Plan; Liste über Back/`?list=1`.
27. Plan History (Accounts): Actor, Diff, Restore; Session-Undo lokal und Accounts.

---

## 26. Nicht Bestandteil von Version 1

Nicht implementieren:

- gemeinsame Echtzeitbearbeitung
- Cloud-Synchronisierung über Geräte hinweg (außer Accounts-Pläne auf derselben Instanz)
- E-Mail-Benachrichtigungen
- Kalenderintegration
- relationale Datenbank
- komplexes Kanban
- Drag-and-drop, falls es zusätzliche große Dependencies erfordert

**Hinweis:** Accounts-Modus (Login, geteilte Pläne, Datei-Upload für Anhänge, User-Templates) existiert als optionale Server-Erweiterung. Der lokale Modus ohne Login bleibt vollständig nutzbar; Dateianhänge liegen dann in IndexedDB. Der Plan Creator kann lokal einen Plan mit `templateSnapshot` starten; Speichern als wiederverwendbare Vorlage erfordert Login.

### Zuletzt geöffneter Plan

Preferences speichern `lastOpenedPlanId`. Navigation zu „My Plans“ (`/sprint-planner` ohne `?list=1`) stellt den zuletzt geöffneten Plan wieder her, sofern er noch existiert. „Zurück zu den Plänen“ und explizites Listen-Browsing nutzen `?list=1`.

### Plan History (Accounts)

Bei jedem Server-Save einer bestehenden Planinstanz wird eine Revision unter `storage/app/bn-tools/plans/{planId}/history/` geschrieben (Snapshot vor der Änderung, Actor, Action/Summary). Retention: max. 50 Revisionen. API: list / detail / restore. Zusätzlich gibt es einen Session-Undo-Stack im Browser-Tab (auch lokal).

### User-Templates und Plan Creator

User-Templates liegen unter `storage/app/bn-tools/user-templates/` (nicht in Git). Beim FTP-Deploy (`npm run deploy:ftp`) werden lokale `users.json`, `teams.json`, `story-acl.json` und User-Templates nach `app/SprintPlanner/bn-tools-seed/` gespiegelt und mit ausgeliefert. Zur Laufzeit füllt `BnToolsSeedStore` fehlende Dateien unter `storage/app/bn-tools/` aus diesen Seeds (analog Playbook-Stats-Seeds).

- Repo-Markdown-Vorlagen bleiben read-only.
- Eingeloggte Nutzer speichern eigene Vorlagen unter `storage/app/bn-tools/user-templates/` (`utpl_*`).
- Plan Creator (`/sprint-planner/create`): Sprints/Tasks mit Hilfe, externen Links, optionalen Tabellen-Spalten; Aktionen „Als Vorlage speichern“ und „Plan starten“.
- Custom-Pläne speichern bei Bedarf `templateSnapshot` auf der Instanz, damit die Struktur ohne Repo-Datei auflösbar bleibt.

### Plan-Filter (Assignee)

Eine Dimension `assigneeFilter`: `all` | `myTasks` | `unassigned` | `person` | `team` (nicht unabhängige UND-Checkboxen). Person/Team-Selects nutzen „Beliebig“ als Leerwert. Leere Treffer zeigen einen Hinweis statt weißer Fläche.
