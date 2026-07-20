#!/usr/bin/env python3
"""
Export a Qlik Cloud app and sheet inventory.

The app list is read through Qlik Cloud REST APIs. Sheet details are optional
and use the Qlik Engine JSON-RPC websocket endpoint.
"""

from __future__ import annotations

import argparse
import csv
import json
import os
import ssl
import sys
from dataclasses import asdict, dataclass
from datetime import datetime, timezone
from pathlib import Path
from typing import Any
from urllib.error import HTTPError
from urllib.parse import urlencode
from urllib.request import Request, urlopen


INVENTORY_COLUMNS = [
    {"id": "app_id", "label": "App ID"},
    {"id": "app_name", "label": "App"},
    {"id": "space_id", "label": "Space ID"},
    {"id": "space_name", "label": "Space"},
    {"id": "space_type", "label": "Space type"},
    {"id": "app_published", "label": "App published"},
    {"id": "publish_time", "label": "Publish time"},
    {"id": "last_reload_time", "label": "Last reload"},
    {"id": "sheet_id", "label": "Sheet ID"},
    {"id": "sheet_title", "label": "Sheet"},
    {"id": "sheet_published", "label": "Sheet published"},
    {"id": "sheet_approved", "label": "Sheet approved"},
    {"id": "sheet_owner", "label": "Sheet owner"},
    {"id": "source", "label": "Source"},
]


@dataclass
class InventoryRow:
    app_id: str
    app_name: str
    space_id: str = ""
    space_name: str = ""
    space_type: str = ""
    app_published: str = ""
    publish_time: str = ""
    last_reload_time: str = ""
    sheet_id: str = ""
    sheet_title: str = ""
    sheet_published: str = ""
    sheet_approved: str = ""
    sheet_owner: str = ""
    source: str = "Qlik Cloud"
    description: str = ""
    owner_id: str = ""
    extra: dict[str, Any] | None = None

    def cells(self) -> dict[str, str]:
        return {column["id"]: str(getattr(self, column["id"], "") or "") for column in INVENTORY_COLUMNS}


def compact(value: Any) -> str:
    if value is None:
        return ""
    return " ".join(str(value).split())


def truth(value: Any) -> str:
    if value is None or value == "":
        return ""
    return "yes" if bool(value) else "no"


def md_cell(value: str) -> str:
    return compact(value).replace("|", "\\|")


def rows_to_markdown(rows: list[InventoryRow], title: str) -> str:
    headers = [column["label"] for column in INVENTORY_COLUMNS]
    lines = [f"# {title}", "", "| " + " | ".join(headers) + " |", "| " + " | ".join(["---"] * len(headers)) + " |"]
    for row in rows:
        cells = row.cells()
        lines.append("| " + " | ".join(md_cell(cells[column["id"]]) for column in INVENTORY_COLUMNS) + " |")
    return "\n".join(lines) + "\n"


def to_plan_payload(rows: list[InventoryRow], title: str) -> dict[str, Any]:
    return {
        "v": 1,
        "kind": "qlik-app-sheet-inventory",
        "title": title,
        "generatedAt": datetime.now(timezone.utc).isoformat(),
        "columns": INVENTORY_COLUMNS,
        "rows": [
            {
                "id": f"qlik_inventory_{index + 1}",
                "cells": row.cells(),
                "description": row.description,
                "ownerId": row.owner_id,
                "extra": row.extra or {},
            }
            for index, row in enumerate(rows)
        ],
        "markdown": rows_to_markdown(rows, title),
    }


def write_output(rows: list[InventoryRow], output_format: str, output: str | None, title: str) -> None:
    if output_format == "plan-json":
        text = json.dumps(to_plan_payload(rows, title), ensure_ascii=False, indent=2) + "\n"
    elif output_format == "json":
        text = json.dumps([asdict(row) for row in rows], ensure_ascii=False, indent=2) + "\n"
    elif output_format == "markdown":
        text = rows_to_markdown(rows, title)
    elif output_format == "csv":
        import io

        handle = io.StringIO()
        writer = csv.DictWriter(handle, fieldnames=[column["id"] for column in INVENTORY_COLUMNS])
        writer.writeheader()
        for row in rows:
            writer.writerow(row.cells())
        text = handle.getvalue()
    else:
        raise ValueError(f"Unsupported output format: {output_format}")

    if output:
        Path(output).write_text(text, encoding="utf-8")
    else:
        print(text, end="")


class QlikRestClient:
    def __init__(self, tenant: str, api_key: str, insecure: bool = False):
        self.tenant = tenant.rstrip("/")
        self.api_key = api_key
        self.context = ssl._create_unverified_context() if insecure else None

    def get(self, path: str, params: dict[str, str] | None = None) -> dict[str, Any]:
        query = f"?{urlencode(params)}" if params else ""
        url = f"{self.tenant}{path}{query}"
        request = Request(url, headers={"Authorization": f"Bearer {self.api_key}", "Accept": "application/json"})
        try:
            with urlopen(request, context=self.context, timeout=60) as response:
                return json.loads(response.read().decode("utf-8"))
        except HTTPError as exc:
            detail = exc.read().decode("utf-8", errors="replace")
            raise RuntimeError(f"Qlik REST error {exc.code} for {path}: {detail}") from exc

    def paged_get(self, path: str, params: dict[str, str] | None = None) -> list[dict[str, Any]]:
        items: list[dict[str, Any]] = []
        next_cursor = ""
        while True:
            request_params = dict(params or {})
            if next_cursor:
                request_params["next"] = next_cursor
            data = self.get(path, request_params)
            batch = data.get("data", [])
            if isinstance(batch, list):
                items.extend(item for item in batch if isinstance(item, dict))
            links = data.get("links", {}) if isinstance(data.get("links"), dict) else {}
            next_link = links.get("next", {}) if isinstance(links.get("next"), dict) else {}
            href = compact(next_link.get("href", ""))
            if not href or "next=" not in href:
                break
            next_cursor = href.split("next=", 1)[1].split("&", 1)[0]
        return items

    def apps(self, space_type: str = "") -> list[dict[str, Any]]:
        params = {"resourceType": "app", "limit": "100", "sort": "+name", "noActions": "true"}
        if space_type:
            params["spaceType"] = space_type
        return self.paged_get("/api/v1/items", params)

    def app_details(self, app_id: str) -> dict[str, Any]:
        return self.get(f"/api/v1/apps/{app_id}")


class QlikEngineClient:
    def __init__(self, tenant: str, app_id: str, api_key: str, insecure: bool = False):
        try:
            import websocket  # type: ignore
        except ImportError as exc:
            raise RuntimeError(
                "Sheet export needs the optional package 'websocket-client'. "
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
        url = f"wss://{tenant_host}/app/{self.app_id}"
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
        handle = result.get("qReturn", {}).get("qHandle")
        if not isinstance(handle, int):
            raise RuntimeError("Qlik OpenDoc did not return an app handle")
        return handle

    def sheets(self) -> list[dict[str, Any]]:
        self.connect()
        try:
            app_handle = self.open_app()
            infos = self.rpc(app_handle, "GetAllInfos").get("qInfos", [])
            sheets: list[dict[str, Any]] = []
            for info in infos:
                if not isinstance(info, dict) or info.get("qType") != "sheet":
                    continue
                q_id = compact(info.get("qId", ""))
                if not q_id:
                    continue
                obj = self.rpc(app_handle, "GetObject", {"qId": q_id}).get("qReturn", {})
                handle = obj.get("qHandle")
                props = self.rpc(handle, "GetProperties") if isinstance(handle, int) else {}
                layout = self.rpc(handle, "GetLayout") if isinstance(handle, int) else {}
                sheets.append({"info": info, "properties": props.get("qProp", props), "layout": layout.get("qLayout", layout)})
            return sheets
        finally:
            self.close()


def app_to_row(item: dict[str, Any], details: dict[str, Any] | None = None) -> InventoryRow:
    resource = item.get("resourceAttributes", {}) if isinstance(item.get("resourceAttributes"), dict) else {}
    space = item.get("space", {}) if isinstance(item.get("space"), dict) else {}
    details_attrs = details.get("attributes", {}) if isinstance(details, dict) and isinstance(details.get("attributes"), dict) else {}
    app_id = compact(item.get("resourceId") or resource.get("id") or item.get("id") or details_attrs.get("id"))
    publish_time = compact(details_attrs.get("publishTime") or resource.get("publishTime") or item.get("publishedAt"))
    published_flag = details_attrs.get("published", resource.get("published"))
    return InventoryRow(
        app_id=app_id,
        app_name=compact(item.get("name") or resource.get("name") or details_attrs.get("name")),
        space_id=compact(item.get("spaceId") or space.get("id") or details_attrs.get("spaceId")),
        space_name=compact(space.get("name") or item.get("spaceName")),
        space_type=compact(space.get("type") or item.get("spaceType")),
        app_published="yes" if publish_time else truth(published_flag),
        publish_time=publish_time,
        last_reload_time=compact(details_attrs.get("lastReloadTime") or resource.get("lastReloadTime")),
        description=compact(item.get("description") or resource.get("description") or details_attrs.get("description")),
        owner_id=compact(resource.get("ownerId") or details_attrs.get("ownerId")),
        extra={"itemId": item.get("id"), "resourceType": item.get("resourceType")},
    )


def sheet_to_row(app_row: InventoryRow, raw_sheet: dict[str, Any]) -> InventoryRow:
    props = raw_sheet.get("properties", {}) if isinstance(raw_sheet.get("properties"), dict) else {}
    layout = raw_sheet.get("layout", {}) if isinstance(raw_sheet.get("layout"), dict) else {}
    info = raw_sheet.get("info", {}) if isinstance(raw_sheet.get("info"), dict) else {}
    meta = props.get("qMetaDef", {}) if isinstance(props.get("qMetaDef"), dict) else {}
    rank = props.get("rank", props.get("qRank", ""))
    title = compact(meta.get("title") or layout.get("title") or props.get("title") or info.get("qId"))
    return InventoryRow(
        **{key: getattr(app_row, key) for key in app_row.cells().keys() if key.startswith("app_") or key.startswith("space_") or key in {"publish_time", "last_reload_time", "source"}},
        sheet_id=compact(info.get("qId") or props.get("qInfo", {}).get("qId")),
        sheet_title=title,
        sheet_published=truth(meta.get("published", layout.get("published"))),
        sheet_approved=truth(meta.get("approved", layout.get("approved"))),
        sheet_owner=compact(meta.get("owner") or meta.get("ownerId")),
        description=compact(meta.get("description")),
        owner_id=app_row.owner_id,
        extra={"rank": rank},
    )


def collect_inventory(args: argparse.Namespace) -> list[InventoryRow]:
    if args.input:
        raw = json.loads(args.input.read_text(encoding="utf-8"))
        items = raw.get("data", raw) if isinstance(raw, dict) else raw
        return [app_to_row(item) for item in items if isinstance(item, dict)]

    missing = [name for name in ("tenant", "api_key") if not getattr(args, name)]
    if missing:
        raise RuntimeError(f"Missing Qlik settings: {', '.join(missing)}")

    rest = QlikRestClient(args.tenant, args.api_key, args.insecure)
    rows: list[InventoryRow] = []
    for item in rest.apps(args.space_type):
        app_id = compact(item.get("resourceId") or item.get("id"))
        details = {}
        if app_id and not args.skip_app_details:
            try:
                details = rest.app_details(app_id)
            except Exception as exc:
                details = {"_error": str(exc)}
        app_row = app_to_row(item, details)
        if not args.include_sheets:
            rows.append(app_row)
            continue

        try:
            sheets = QlikEngineClient(args.tenant, app_row.app_id, args.api_key, args.insecure).sheets()
            if sheets:
                rows.extend(sheet_to_row(app_row, sheet) for sheet in sheets)
            else:
                rows.append(app_row)
        except Exception as exc:
            app_row.extra = {**(app_row.extra or {}), "sheetError": str(exc)}
            rows.append(app_row)
    return rows


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="Export Qlik Cloud apps and optionally published/unpublished sheets.")
    parser.add_argument("--tenant", default=os.getenv("QLIK_TENANT"), help="Qlik Cloud tenant URL, e.g. https://tenant.eu.qlikcloud.com")
    parser.add_argument("--api-key", default=os.getenv("QLIK_API_KEY"), help="Qlik API key / bearer token.")
    parser.add_argument("--space-type", default="", help="Optional filter: shared, managed, personal, data.")
    parser.add_argument("--include-sheets", action="store_true", help="Open each app through Engine API and include sheet rows.")
    parser.add_argument("--skip-app-details", action="store_true", help="Only use /items list data; faster but less complete.")
    parser.add_argument("--input", type=Path, help="Offline /api/v1/items JSON fixture.")
    parser.add_argument("--format", choices=["plan-json", "json", "markdown", "csv"], default="plan-json")
    parser.add_argument("--output", help="Write to file instead of stdout.")
    parser.add_argument("--title", default="Qlik app and sheet inventory")
    parser.add_argument("--insecure", action="store_true", help="Disable TLS certificate verification for self-managed test hosts.")
    return parser


def main(argv: list[str] | None = None) -> int:
    args = build_parser().parse_args(argv)
    try:
        rows = collect_inventory(args)
        write_output(rows, args.format, args.output, args.title)
        return 0
    except Exception as exc:
        print(f"qlik_app_inventory: {exc}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    raise SystemExit(main())
