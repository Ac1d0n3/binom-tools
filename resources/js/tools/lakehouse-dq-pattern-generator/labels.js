import { getLocale } from '../../locale';

const labels = {
    de: {
        'lakehouseDq.fabric.pageTitle': 'Fabric DQ Pattern Generator',
        'lakehouseDq.fabric.lead': 'Erzeugt SQL- und Notebook-Patterns fuer Fabric Lakehouse/Warehouse: DQ-Regeln, Delta Loads, SCD2 und Pipeline-Gates.',
        'lakehouseDq.databricks.pageTitle': 'Databricks DQ Pattern Generator',
        'lakehouseDq.databricks.lead': 'Erzeugt DLT Expectations, Delta MERGE/SCD2 und Unity-Catalog-Governance-Patterns fuer Databricks.',
        'lakehouseDq.howto.summary': 'Hilfe',
        'lakehouseDq.fabric.howto.intro': 'Nutze den Generator, um aus wenigen Modelldaten ein kopierbares Fabric-Pattern zu erzeugen.',
        'lakehouseDq.databricks.howto.intro': 'Nutze den Generator, um aus wenigen Modelldaten ein kopierbares Databricks-Pattern zu erzeugen.',
        'lakehouseDq.howto.step1': 'Trage Tabelle, Schluessel, Pflichtfelder, Freshness-Spalte und Owner ein.',
        'lakehouseDq.howto.step2': 'Waehle, ob du DQ, Delta Load, SCD2 oder Governance vorbereiten willst.',
        'lakehouseDq.howto.step3': 'Kopiere SQL, Notebook-Snippet oder Runbook in dein Projekt und passe Namen/Policies final an.',
        'lakehouseDq.fabric.howto.tip': 'Die Ausgabe ist bewusst ein Startpunkt fuer Fabric Pipelines, Notebooks und Warehouse Checks, kein Live-Deployment.',
        'lakehouseDq.databricks.howto.tip': 'Die Ausgabe ist bewusst ein Startpunkt fuer Databricks Jobs, DLT Pipelines und Unity Catalog, kein Live-Deployment.',
        'lakehouseDq.input.title': 'Pattern-Eingaben',
        'lakehouseDq.input.description': 'Diese Felder schneiden den generierten Code auf dein Modell zu.',
        'lakehouseDq.input.table': 'Zieltabelle',
        'lakehouseDq.input.keys': 'Key-Spalten',
        'lakehouseDq.input.required': 'Pflichtfelder',
        'lakehouseDq.input.freshness': 'Freshness-Spalte',
        'lakehouseDq.input.pii': 'PII/Sensitive Spalten',
        'lakehouseDq.input.owner': 'Owner / Gruppe',
        'lakehouseDq.input.pattern': 'Pattern',
        'lakehouseDq.fabric.output.sql': 'Fabric SQL Pattern',
        'lakehouseDq.fabric.output.notebook': 'Fabric Notebook Snippet',
        'lakehouseDq.databricks.output.sql': 'Databricks SQL / DLT Pattern',
        'lakehouseDq.databricks.output.notebook': 'Databricks Notebook Snippet',
        'lakehouseDq.output.runbook': 'Runbook / Checkliste',
        'lakehouseDq.copy': 'Kopieren',
        'shared.copied': 'Kopiert!',
    },
    en: {
        'lakehouseDq.fabric.pageTitle': 'Fabric DQ Pattern Generator',
        'lakehouseDq.fabric.lead': 'Generates SQL and notebook patterns for Fabric Lakehouse/Warehouse: DQ rules, Delta loads, SCD2, and pipeline gates.',
        'lakehouseDq.databricks.pageTitle': 'Databricks DQ Pattern Generator',
        'lakehouseDq.databricks.lead': 'Generates DLT expectations, Delta MERGE/SCD2, and Unity Catalog governance patterns for Databricks.',
        'lakehouseDq.howto.summary': 'Help',
        'lakehouseDq.fabric.howto.intro': 'Use the generator to turn a few model inputs into a copyable Fabric pattern.',
        'lakehouseDq.databricks.howto.intro': 'Use the generator to turn a few model inputs into a copyable Databricks pattern.',
        'lakehouseDq.howto.step1': 'Enter table, keys, required fields, freshness column, and owner.',
        'lakehouseDq.howto.step2': 'Choose whether you want to prepare DQ, Delta load, SCD2, or governance.',
        'lakehouseDq.howto.step3': 'Copy SQL, notebook snippet, or runbook into your project and adjust names/policies before use.',
        'lakehouseDq.fabric.howto.tip': 'The output is a starting point for Fabric pipelines, notebooks, and warehouse checks, not a live deployment.',
        'lakehouseDq.databricks.howto.tip': 'The output is a starting point for Databricks jobs, DLT pipelines, and Unity Catalog, not a live deployment.',
        'lakehouseDq.input.title': 'Pattern inputs',
        'lakehouseDq.input.description': 'These fields tailor the generated code to your model.',
        'lakehouseDq.input.table': 'Target table',
        'lakehouseDq.input.keys': 'Key columns',
        'lakehouseDq.input.required': 'Required fields',
        'lakehouseDq.input.freshness': 'Freshness column',
        'lakehouseDq.input.pii': 'PII/sensitive columns',
        'lakehouseDq.input.owner': 'Owner / group',
        'lakehouseDq.input.pattern': 'Pattern',
        'lakehouseDq.fabric.output.sql': 'Fabric SQL pattern',
        'lakehouseDq.fabric.output.notebook': 'Fabric notebook snippet',
        'lakehouseDq.databricks.output.sql': 'Databricks SQL / DLT pattern',
        'lakehouseDq.databricks.output.notebook': 'Databricks notebook snippet',
        'lakehouseDq.output.runbook': 'Runbook / checklist',
        'lakehouseDq.copy': 'Copy',
        'shared.copied': 'Copied!',
    },
};

export function t(locale, key) {
    return labels[locale]?.[key] ?? labels.en[key] ?? key;
}

export function applyLakehouseDqLabels() {
    const locale = getLocale();
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (!key?.startsWith('lakehouseDq.') && !key?.startsWith('shared.')) return;
        el.textContent = t(locale, key);
    });
}
