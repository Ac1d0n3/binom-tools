# BI KPI Export

`scripts/bi_kpi_export.py` exports KPI / measure formulas into a neutral inventory that can be reused in Sprint Planner plan steps.

Use the web page `/tools/bi-python-toolkit` to download the Python files and run them locally. The site does not store API keys and does not connect to BI systems directly.

Default output is `plan-json`:

- `columns`: table columns matching the KPI Definition Card (`name`, `formula`, `grain`, `filters`, `owner`, `source`, `status`)
- `rows`: normalized KPI rows
- `markdown`: copy/paste inventory for notes or attachments

## Qlik

Offline fixture or copied API response:

```bash
python3 scripts/bi_kpi_export.py qlik --input path/to/qlik-measures.json --format plan-json
```

Qlik Cloud / Engine API master measures:

```bash
QLIK_TENANT="https://tenant.eu.qlikcloud.com" \
QLIK_APP_ID="00000000-0000-0000-0000-000000000000" \
QLIK_API_KEY="..." \
python3 scripts/bi_kpi_export.py qlik --output qlik-kpis.plan.json
```

API mode uses Qlik Engine JSON-RPC over WebSocket and needs:

```bash
python3 -m pip install websocket-client
```

## Power BI

Export from `model.bim` or a PBIP semantic model JSON file:

```bash
python3 scripts/bi_kpi_export.py powerbi --input path/to/model.bim --output powerbi-kpis.plan.json
```

The parser reads `model.tables[].measures[]` and normalizes DAX expressions.

## Tableau

Export calculated fields from a `.twb` or `.twbx` workbook:

```bash
python3 scripts/bi_kpi_export.py tableau --input path/to/workbook.twbx --format markdown
```

The parser reads workbook XML calculation formulas and maps them into the same KPI inventory columns.

## Qlik app and sheet inventory

Download `qlik_app_inventory.py` from `/tools/bi-python-toolkit`.

Apps only:

```bash
QLIK_TENANT="https://tenant.eu.qlikcloud.com" \
QLIK_API_KEY="..." \
python3 qlik_app_inventory.py --format csv --output qlik-apps.csv
```

Apps plus sheets:

```bash
python3 -m pip install websocket-client

QLIK_TENANT="https://tenant.eu.qlikcloud.com" \
QLIK_API_KEY="..." \
python3 qlik_app_inventory.py --include-sheets --format plan-json --output qlik-inventory.plan.json
```

The inventory columns include app id/name, space, app published state, publish time, sheet id/title, sheet published state and sheet approval state where the Engine API exposes that metadata.
