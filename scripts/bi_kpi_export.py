#!/usr/bin/env python3
"""
Export KPI / measure formulas from BI artifacts into a Sprint-Planner-friendly format.

Supported inputs:
  - Qlik Cloud / Qlik Sense Engine API master measures
  - Qlik measure JSON fixtures, for offline testing or copied API responses
  - Power BI model.bim / PBIP semantic model JSON
  - Tableau .twb / .twbx workbook XML

The default output is "plan-json": columns + rows + Markdown. Those column ids match
the KPI Definition Card and Sprint Planner table transfer format.
"""

from __future__ import annotations

import argparse
import csv
import json
import os
import sys
import tempfile
import zipfile
from dataclasses import asdict, dataclass
from datetime import datetime, timezone
from pathlib import Path
from typing import Any
from urllib.parse import urlencode
from xml.etree import ElementTree


PLAN_COLUMNS = [
    {"id": "name", "label": "KPI"},
    {"id": "synonyms", "label": "Synonyms"},
    {"id": "formula", "label": "Formula"},
    {"id": "grain", "label": "Grain"},
    {"id": "filters", "label": "Filters"},
    {"id": "owner", "label": "Owner"},
    {"id": "source", "label": "Source / report"},
    {"id": "status", "label": "Status"},
]


@dataclass
class KpiMeasure:
    name: str
    formula: str
    source_system: str
    source: str
    owner: str = ""
    description: str = ""
    grain: str = ""
    filters: str = ""
    synonyms: str = ""
    status: str = "Draft"
    technical_id: str = ""
    expression_language: str = ""
    extra: dict[str, Any] | None = None

    def plan_cells(self) -> dict[str, str]:
        return {
            "name": self.name,
            "synonyms": self.synonyms,
            "formula": self.formula,
            "grain": self.grain,
            "filters": self.filters,
            "owner": self.owner,
            "source": self.source,
            "status": self.status,
        }


def read_json(path: Path) -> Any:
    with path.open("r", encoding="utf-8") as handle:
        return json.load(handle)


def compact(value: Any) -> str:
    if value is None:
        return ""
    if isinstance(value, str):
        return " ".join(value.split())
    return " ".join(str(value).split())


def first_present(data: dict[str, Any], keys: list[str], default: str = "") -> str:
    for key in keys:
        value = data.get(key)
        if value not in (None, ""):
            return compact(value)
    return default


def md_cell(value: str) -> str:
    return compact(value).replace("|", "\\|")


def rows_to_markdown(measures: list[KpiMeasure], title: str) -> str:
    headers = [column["label"] for column in PLAN_COLUMNS]
    lines = [f"# {title}", "", "| " + " | ".join(headers) + " |", "| " + " | ".join(["---"] * len(headers)) + " |"]
    for measure in measures:
        cells = measure.plan_cells()
        lines.append("| " + " | ".join(md_cell(cells[column["id"]]) for column in PLAN_COLUMNS) + " |")
    return "\n".join(lines) + "\n"


def to_plan_payload(measures: list[KpiMeasure], title: str) -> dict[str, Any]:
    return {
        "v": 1,
        "kind": "bi-kpi-inventory",
        "title": title,
        "generatedAt": datetime.now(timezone.utc).isoformat(),
        "columns": PLAN_COLUMNS,
        "rows": [
            {
                "id": f"kpi_{index + 1}",
                "cells": measure.plan_cells(),
                "sourceSystem": measure.source_system,
                "technicalId": measure.technical_id,
                "expressionLanguage": measure.expression_language,
                "description": measure.description,
                "extra": measure.extra or {},
            }
            for index, measure in enumerate(measures)
        ],
        "markdown": rows_to_markdown(measures, title),
    }


def write_output(payload: dict[str, Any], measures: list[KpiMeasure], output_format: str, output: str | None, title: str) -> None:
    if output_format == "plan-json":
        text = json.dumps(payload, ensure_ascii=False, indent=2) + "\n"
    elif output_format == "json":
        text = json.dumps([asdict(measure) for measure in measures], ensure_ascii=False, indent=2) + "\n"
    elif output_format == "markdown":
        text = rows_to_markdown(measures, title)
    elif output_format == "csv":
        from_io = tempfile.SpooledTemporaryFile(mode="w+", encoding="utf-8", newline="")
        writer = csv.DictWriter(from_io, fieldnames=[column["id"] for column in PLAN_COLUMNS])
        writer.writeheader()
        for measure in measures:
            writer.writerow(measure.plan_cells())
        from_io.seek(0)
        text = from_io.read()
        from_io.close()
    else:
        raise ValueError(f"Unsupported output format: {output_format}")

    if output:
        Path(output).write_text(text, encoding="utf-8")
    else:
        print(text, end="")


def extract_qlik_measure(raw: dict[str, Any], source: str) -> KpiMeasure | None:
    measure = raw.get("qMeasure") if isinstance(raw.get("qMeasure"), dict) else raw
    q_info = measure.get("qInfo") if isinstance(measure.get("qInfo"), dict) else {}
    if not q_info and isinstance(raw.get("qInfo"), dict):
        q_info = raw["qInfo"]
    q_meta = measure.get("qMetaDef") if isinstance(measure.get("qMetaDef"), dict) else {}
    q_data = measure.get("qData") if isinstance(measure.get("qData"), dict) else {}

    name = first_present(measure, ["qLabel", "label", "name", "title"])
    if not name:
        name = first_present(q_meta, ["title", "qName", "name"])
    formula = first_present(measure, ["qDef", "qExpression", "formula", "expression"])
    if not formula:
        expressions = measure.get("qExpressions")
        if isinstance(expressions, list) and expressions:
            formula = compact(expressions[0])
    if not name and not formula:
        return None

    return KpiMeasure(
        name=name or formula[:60],
        formula=formula,
        source_system="qlik",
        source=source,
        owner=first_present(q_data, ["owner", "businessOwner"]),
        description=first_present(measure, ["qDescription", "description"]),
        filters=first_present(q_data, ["filters"]),
        technical_id=first_present(q_info, ["qId", "id"]),
        expression_language="Qlik expression",
        extra={"tags": measure.get("qTags", [])},
    )


def collect_qlik_from_json(path: Path, source: str) -> list[KpiMeasure]:
    data = read_json(path)
    candidates: list[Any]
    if isinstance(data, list):
        candidates = data
    elif isinstance(data, dict):
        for key in ("measures", "items", "qItems", "rows", "data"):
            if isinstance(data.get(key), list):
                candidates = data[key]
                break
        else:
            candidates = [data]
    else:
        candidates = []
    return [measure for raw in candidates if isinstance(raw, dict) for measure in [extract_qlik_measure(raw, source)] if measure]


class QlikEngineClient:
    def __init__(self, tenant: str, app_id: str, api_key: str, insecure: bool = False):
        try:
            import websocket  # type: ignore
        except ImportError as exc:
            raise RuntimeError(
                "Qlik API mode needs the optional package 'websocket-client'. "
                "Install it with: python3 -m pip install websocket-client"
            ) from exc

        self.websocket = websocket
        self.tenant = tenant.rstrip("/")
        self.app_id = app_id
        self.api_key = api_key
        self.insecure = insecure
        self.next_id = 1
        self.ws: Any = None

    def connect(self) -> None:
        tenant_host = self.tenant.replace("https://", "").replace("http://", "").rstrip("/")
        query = urlencode({"qlik-web-integration-id": "bi-kpi-export"})
        url = f"wss://{tenant_host}/app/{self.app_id}?{query}"
        headers = [f"Authorization: Bearer {self.api_key}"]
        sslopt = {"cert_reqs": 0} if self.insecure else {}
        self.ws = self.websocket.create_connection(url, header=headers, sslopt=sslopt)

    def close(self) -> None:
        if self.ws:
            self.ws.close()

    def rpc(self, handle: int, method: str, params: dict[str, Any] | None = None) -> dict[str, Any]:
        request_id = self.next_id
        self.next_id += 1
        message: dict[str, Any] = {"jsonrpc": "2.0", "id": request_id, "handle": handle, "method": method}
        if params is not None:
            message["params"] = params
        self.ws.send(json.dumps(message))
        while True:
            response = json.loads(self.ws.recv())
            if response.get("id") != request_id:
                continue
            if "error" in response:
                raise RuntimeError(f"Qlik Engine API error calling {method}: {response['error']}")
            return response.get("result", {})

    def open_app(self) -> int:
        result = self.rpc(-1, "OpenDoc", {"qDocName": self.app_id})
        q_return = result.get("qReturn", {})
        handle = q_return.get("qHandle")
        if not isinstance(handle, int):
            raise RuntimeError("Qlik OpenDoc did not return an app handle")
        return handle

    def collect_master_measures(self) -> list[KpiMeasure]:
        self.connect()
        try:
            app_handle = self.open_app()
            infos = self.rpc(app_handle, "GetAllInfos").get("qInfos", [])
            measures: list[KpiMeasure] = []
            for info in infos:
                if not isinstance(info, dict) or info.get("qType") != "measure":
                    continue
                q_id = info.get("qId")
                if not q_id:
                    continue
                generic = self.rpc(app_handle, "GetMeasure", {"qId": q_id}).get("qReturn", {})
                measure_handle = generic.get("qHandle")
                if not isinstance(measure_handle, int):
                    continue
                raw_measure = self.rpc(measure_handle, "GetMeasure")
                extracted = extract_qlik_measure(raw_measure, f"Qlik app {self.app_id}")
                if extracted:
                    measures.append(extracted)
            return measures
        finally:
            self.close()


def collect_powerbi_model(path: Path, source: str) -> list[KpiMeasure]:
    data = read_json(path)
    model = data.get("model", data) if isinstance(data, dict) else {}
    tables = model.get("tables", []) if isinstance(model, dict) else []
    measures: list[KpiMeasure] = []
    for table in tables:
        if not isinstance(table, dict):
            continue
        table_name = compact(table.get("name", ""))
        for measure in table.get("measures", []) or []:
            if not isinstance(measure, dict):
                continue
            expression = measure.get("expression", "")
            if isinstance(expression, list):
                expression = "\n".join(str(line) for line in expression)
            name = compact(measure.get("name", ""))
            if not name and not expression:
                continue
            measures.append(
                KpiMeasure(
                    name=name or compact(expression)[:60],
                    formula=compact(expression),
                    source_system="powerbi",
                    source=f"{source}{' / ' + table_name if table_name else ''}",
                    description=first_present(measure, ["description"]),
                    technical_id=first_present(measure, ["lineageTag", "id", "name"]),
                    expression_language="DAX",
                    extra={"table": table_name, "formatString": measure.get("formatString", "")},
                )
            )
    return measures


def iter_twb_roots(path: Path) -> list[ElementTree.Element]:
    if path.suffix.lower() == ".twbx":
        roots: list[ElementTree.Element] = []
        with zipfile.ZipFile(path) as archive:
            for name in archive.namelist():
                if name.lower().endswith(".twb"):
                    roots.append(ElementTree.fromstring(archive.read(name)))
        return roots
    return [ElementTree.parse(path).getroot()]


def collect_tableau_workbook(path: Path, source: str) -> list[KpiMeasure]:
    measures: list[KpiMeasure] = []
    for root in iter_twb_roots(path):
        workbook_name = root.attrib.get("name") or path.stem
        for datasource in root.findall(".//datasource"):
            datasource_name = datasource.attrib.get("caption") or datasource.attrib.get("name") or ""
            for column in datasource.findall(".//column"):
                calculation = column.find("calculation")
                if calculation is None:
                    continue
                formula = compact(calculation.attrib.get("formula", ""))
                name = column.attrib.get("caption") or column.attrib.get("name") or ""
                role = column.attrib.get("role", "")
                if not formula:
                    continue
                measures.append(
                    KpiMeasure(
                        name=compact(name).strip("[]") or formula[:60],
                        formula=formula,
                        source_system="tableau",
                        source=f"{source} / {workbook_name}{' / ' + datasource_name if datasource_name else ''}",
                        description=compact(column.attrib.get("desc", "")),
                        technical_id=compact(column.attrib.get("name", "")),
                        expression_language="Tableau calculation",
                        extra={"role": role, "datatype": column.attrib.get("datatype", "")},
                    )
                )
    return measures


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="Export BI KPI formulas for Sprint Planner plan steps.")

    def add_output_args(target: argparse.ArgumentParser) -> None:
        target.add_argument("--format", choices=["plan-json", "json", "markdown", "csv"], default="plan-json")
        target.add_argument("--output", help="Write to file instead of stdout.")
        target.add_argument("--title", default="KPI inventory")

    add_output_args(parser)

    subparsers = parser.add_subparsers(dest="platform", required=True)

    qlik = subparsers.add_parser("qlik", help="Export Qlik master measures.")
    add_output_args(qlik)
    qlik.add_argument("--input", type=Path, help="Offline Qlik measure JSON response or fixture.")
    qlik.add_argument("--tenant", default=os.getenv("QLIK_TENANT"), help="Qlik Cloud tenant URL, e.g. https://tenant.eu.qlikcloud.com")
    qlik.add_argument("--app-id", default=os.getenv("QLIK_APP_ID"), help="Qlik app id.")
    qlik.add_argument("--api-key", default=os.getenv("QLIK_API_KEY"), help="Qlik API key / bearer token.")
    qlik.add_argument("--insecure", action="store_true", help="Disable TLS certificate verification for self-managed test hosts.")

    powerbi = subparsers.add_parser("powerbi", help="Export Power BI measures from model.bim / PBIP JSON.")
    add_output_args(powerbi)
    powerbi.add_argument("--input", type=Path, required=True, help="Path to model.bim or semantic model JSON.")

    tableau = subparsers.add_parser("tableau", help="Export Tableau calculated fields from .twb / .twbx.")
    add_output_args(tableau)
    tableau.add_argument("--input", type=Path, required=True, help="Path to Tableau .twb or .twbx workbook.")

    return parser


def main(argv: list[str] | None = None) -> int:
    args = build_parser().parse_args(argv)
    try:
        if args.platform == "qlik":
            if args.input:
                measures = collect_qlik_from_json(args.input, f"Qlik fixture {args.input.name}")
            else:
                missing = [name for name in ("tenant", "app_id", "api_key") if not getattr(args, name)]
                if missing:
                    raise RuntimeError(f"Missing Qlik API settings: {', '.join(missing)}")
                measures = QlikEngineClient(args.tenant, args.app_id, args.api_key, args.insecure).collect_master_measures()
        elif args.platform == "powerbi":
            measures = collect_powerbi_model(args.input, f"Power BI model {args.input.name}")
        elif args.platform == "tableau":
            measures = collect_tableau_workbook(args.input, f"Tableau workbook {args.input.name}")
        else:
            raise RuntimeError(f"Unsupported platform: {args.platform}")

        payload = to_plan_payload(measures, args.title)
        write_output(payload, measures, args.format, args.output, args.title)
        return 0
    except Exception as exc:
        print(f"bi_kpi_export: {exc}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    raise SystemExit(main())
