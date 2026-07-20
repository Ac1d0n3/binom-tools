---
title: Set Up Python for BI Exports — Windows, macOS and Local Project Folders
description: How to install Python, prepare a local directory, use virtual environments and run Qlik, Power BI or Tableau export scripts safely on your machine.
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

## Why run this locally?

BI export scripts often need API keys, tenant URLs or local files such as `model.bim`, `.twb` or `.twbx`. Those details do not belong in a browser tool.

The safer workflow is:

1. Install Python locally.
2. Create a dedicated project folder.
3. Download the Python script from the [BI Python Export Toolkit](/tools/bi-python-toolkit).
4. Set API keys as environment variables or read local files.
5. Use the result as CSV, Markdown or plan JSON in the Sprint Planner.

In this pattern, the web tool is only a download and orientation point. The connection to Qlik, Power BI or Tableau runs on your machine.

## Install Python on Windows

The simplest path is the official installer from `python.org`.

1. Open `https://www.python.org/downloads/windows/`.
2. Download the current stable Python version.
3. Enable **Add python.exe to PATH** in the installer.
4. Choose **Install Now**.
5. Open PowerShell and verify:

```powershell
python --version
pip --version
```

If `python` is not found, open a new terminal. If it still fails, Python is usually missing from `PATH`.

## Install Python on macOS with Homebrew

If Homebrew is available:

```bash
brew update
brew install python
python3 --version
pip3 --version
```

Homebrew usually exposes Python as `python3` and `pip3`. That is normal.

## Install Python on macOS without Homebrew

Without Homebrew, use the official macOS installer:

1. Open `https://www.python.org/downloads/macos/`.
2. Download the macOS installer.
3. Install Python.
4. Open Terminal and verify:

```bash
python3 --version
pip3 --version
```

On macOS, use `python3` for your own projects.

## Initialize a project folder

Create a dedicated folder for BI exports. This keeps scripts, outputs and dependencies separate from other projects.

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

When the environment is active, the terminal prompt often starts with `(.venv)`.

## Download scripts from the tool

Open the [BI Python Export Toolkit](/tools/bi-python-toolkit) and download the files you need:

- `bi_kpi_export.py` for KPI / measure formulas from Qlik, Power BI and Tableau
- `qlik_app_inventory.py` for Qlik app and optional sheet inventories
- `BI_EXPORT_TOOLKIT.md` as a quick reference

Place the files in your `bi-export` project folder.

## Install dependencies

Many exports use only the Python standard library. For Qlik Engine API access to master measures or sheets, install:

```bash
python -m pip install websocket-client
```

This is needed because Qlik sheet and measure details are read through the Engine API over WebSocket.

## Export a Qlik app and sheet inventory

Set tenant and API key as environment variables.

Windows PowerShell:

```powershell
$env:QLIK_TENANT="https://tenant.eu.qlikcloud.com"
$env:QLIK_API_KEY="your-api-key"
python qlik_app_inventory.py --include-sheets --format csv --output qlik-inventory.csv
```

macOS Terminal:

```bash
export QLIK_TENANT="https://tenant.eu.qlikcloud.com"
export QLIK_API_KEY="your-api-key"
python qlik_app_inventory.py --include-sheets --format csv --output qlik-inventory.csv
```

Without sheets it is faster and does not need the WebSocket dependency:

```bash
python qlik_app_inventory.py --format plan-json --output qlik-apps.plan.json
```

## Export KPI formulas

Qlik from a local JSON file:

```bash
python bi_kpi_export.py qlik --input qlik-measures.json --format plan-json --output qlik-kpis.plan.json
```

Power BI from `model.bim`:

```bash
python bi_kpi_export.py powerbi --input model.bim --format csv --output powerbi-kpis.csv
```

Tableau from `.twb` or `.twbx`:

```bash
python bi_kpi_export.py tableau --input workbook.twbx --format markdown --output tableau-kpis.md
```

## Reuse the output

Three formats are useful for plan work:

- `csv`: good for tables, Excel and quick inventory lists
- `markdown`: good for notes, reviews and pull request comments
- `plan-json`: good for structured handoff into Sprint-Planner-adjacent workflows

For governance work, the export is only the starting point. Owners, business purpose, grain, filters, version and open conflicts still need to be completed.

