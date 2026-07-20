---
title: Python für BI-Exports einrichten — Windows, macOS und lokale Projektordner
description: Wie du Python installierst, ein lokales Verzeichnis vorbereitest, virtuelle Environments nutzt und Qlik-, Power-BI- oder Tableau-Export-Scripts sicher lokal ausführst.
category: Data Governance
tags:
  - python
  - qlik
  - power-bi
  - tableau
  - metadata
  - kpi-governance
  - inventory
order: -1
author: Thomas Lindackers
---

## Warum überhaupt lokal ausführen?

BI-Export-Scripts brauchen oft Zugriff auf API Keys, Tenant-URLs oder lokale Dateien wie `model.bim`, `.twb` oder `.twbx`. Diese Informationen gehören nicht in ein Browser-Tool.

Der sichere Ablauf ist deshalb:

1. Python lokal installieren.
2. Einen eigenen Projektordner anlegen.
3. Das Python-Script aus dem [BI Python Export Toolkit](/tools/bi-python-toolkit) herunterladen.
4. API Keys als Environment-Variablen setzen oder lokale Dateien einlesen.
5. Das Ergebnis als CSV, Markdown oder Plan-JSON in den Sprint Planner übernehmen.

Das Web-Tool ist in diesem Muster nur Download- und Orientierungspunkt. Die Verbindung zu Qlik, Power BI oder Tableau läuft auf deinem Rechner.

## Python auf Windows installieren

Die einfachste Variante ist der offizielle Installer von `python.org`.

1. Öffne `https://www.python.org/downloads/windows/`.
2. Lade die aktuelle stabile Python-Version herunter.
3. Aktiviere im Installer **Add python.exe to PATH**.
4. Wähle **Install Now**.
5. Öffne PowerShell und prüfe:

```powershell
python --version
pip --version
```

Wenn `python` nicht gefunden wird, öffne ein neues Terminal. Falls es weiterhin nicht klappt, ist Python meist nicht im `PATH`.

## Python auf macOS mit Homebrew installieren

Wenn Homebrew vorhanden ist:

```bash
brew update
brew install python
python3 --version
pip3 --version
```

Homebrew legt Python üblicherweise als `python3` und `pip3` ab. Das ist normal.

## Python auf macOS ohne Homebrew installieren

Ohne Homebrew kannst du den offiziellen macOS Installer verwenden:

1. Öffne `https://www.python.org/downloads/macos/`.
2. Lade den macOS Installer herunter.
3. Installiere Python.
4. Öffne Terminal und prüfe:

```bash
python3 --version
pip3 --version
```

Auf macOS solltest du in eigenen Projekten fast immer `python3` verwenden.

## Projektordner initialisieren

Lege für BI-Exports einen eigenen Ordner an. Dann bleiben Scripts, Outputs und Abhängigkeiten getrennt von anderen Projekten.

Windows PowerShell:

```powershell
mkdir bi-export
cd bi-export
python -m venv .venv
.\.venv\Scripts\Activate.ps1
python -m pip install --upgrade pip
```

macOS Terminal:

```bash
mkdir bi-export
cd bi-export
python3 -m venv .venv
source .venv/bin/activate
python -m pip install --upgrade pip
```

Wenn das Environment aktiv ist, steht meist `(.venv)` vor der Terminal-Zeile.

## Scripts aus dem Tool herunterladen

Öffne das [BI Python Export Toolkit](/tools/bi-python-toolkit) und lade die benötigten Dateien herunter:

- `bi_kpi_export.py` für KPI-/Measure-Formeln aus Qlik, Power BI und Tableau
- `qlik_app_inventory.py` für Qlik App- und optional Sheet-Inventare
- `BI_EXPORT_TOOLKIT.md` als Kurzreferenz

Lege die Dateien in deinen Projektordner `bi-export`.

## Abhängigkeiten installieren

Viele Exporte laufen mit der Python-Standardbibliothek. Für Qlik Engine API Zugriffe auf Master Measures oder Sheets brauchst du zusätzlich:

```bash
python -m pip install websocket-client
```

Das ist nötig, weil Qlik Sheet- und Measure-Details über die Engine API per WebSocket gelesen werden.

## Qlik App- und Sheet-Inventar exportieren

Setze Tenant und API Key als Environment-Variablen.

Windows PowerShell:

```powershell
$env:QLIK_TENANT="https://tenant.eu.qlikcloud.com"
$env:QLIK_API_KEY="dein-api-key"
python qlik_app_inventory.py --include-sheets --format csv --output qlik-inventory.csv
```

macOS Terminal:

```bash
export QLIK_TENANT="https://tenant.eu.qlikcloud.com"
export QLIK_API_KEY="dein-api-key"
python qlik_app_inventory.py --include-sheets --format csv --output qlik-inventory.csv
```

Ohne Sheets geht es schneller und braucht keine WebSocket-Abhängigkeit:

```bash
python qlik_app_inventory.py --format plan-json --output qlik-apps.plan.json
```

## KPI-Formeln exportieren

Qlik aus einer lokalen JSON-Datei:

```bash
python bi_kpi_export.py qlik --input qlik-measures.json --format plan-json --output qlik-kpis.plan.json
```

Power BI aus `model.bim`:

```bash
python bi_kpi_export.py powerbi --input model.bim --format csv --output powerbi-kpis.csv
```

Tableau aus `.twb` oder `.twbx`:

```bash
python bi_kpi_export.py tableau --input workbook.twbx --format markdown --output tableau-kpis.md
```

## Output weiterverwenden

Für Plan-Schritte sind drei Formate praktisch:

- `csv`: gut für Tabellen, Excel und schnelle Inventarlisten
- `markdown`: gut für Notizen, Reviews und Pull-Request-Kommentare
- `plan-json`: gut für strukturierte Übergabe in Sprint-Planner-nahe Workflows

Für Governance-Arbeit ist wichtig: Der Export ist nur der Startpunkt. Danach müssen Owner, fachlicher Zweck, Grain, Filter, Version und offene Konflikte ergänzt werden.

