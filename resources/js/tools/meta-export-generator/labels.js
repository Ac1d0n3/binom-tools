import { getLocale } from '../../locale.js';

/** @type {Record<'de'|'en', Record<string, string>>} */
const labels = {
    de: {
        'metaExport.pageTitle': 'Meta Export Generator',
        'metaExport.pageLead':
            'Copy-Paste-SQL (oder mongosh) für Schemas, Tabellen, Spalten und Zugriffs-Meta — ohne Live-DB-Connect.',
        'metaExport.howto.summary': 'So funktioniert’s',
        'metaExport.howto.overview.intro':
            'Wähle die Plattform, kopiere die Queries und exportiere die Ergebnisse als Tabelle (CSV) für Source-Analysen.',
        'metaExport.howto.overview.step1': 'Plattform wählen (Cloud, Relational oder Document).',
        'metaExport.howto.overview.step2': 'Queries in deinem Warehouse / Client ausführen.',
        'metaExport.howto.overview.step3': 'Ergebnisse als CSV/Excel speichern und weiteranalysieren.',
        'metaExport.howto.overview.tip':
            'Kein Live-Connect: Credentials bleiben in deinem Client. Region/Database-Platzhalter in den Queries anpassen.',
        'metaExport.platform': 'Plattform',
        'metaExport.group.cloud': 'Cloud / Lakehouse',
        'metaExport.group.relational': 'Relational',
        'metaExport.group.document': 'Document',
        'metaExport.platformNote': 'Hinweis',
        'metaExport.section.schemas': 'Catalog / Schemas',
        'metaExport.section.tables': 'Tables / Collections',
        'metaExport.section.columns': 'Columns / Fields',
        'metaExport.section.access': 'Access / Grants',
        'metaExport.copy': 'Kopieren',
        'metaExport.copied': 'Kopiert',
        'metaExport.queryLabel': 'Query / Script',
        'metaExport.howto.schemas.intro': 'Liefert Datenbanken/Schemas (oder Datasets) als Tabelle.',
        'metaExport.howto.tables.intro': 'Liefert Tabellen, Views oder Collections.',
        'metaExport.howto.columns.intro': 'Liefert Spalten bzw. inferierte Felder (MongoDB: Sample).',
        'metaExport.howto.access.intro': 'Best-effort Grants/Rollen — Rechte und Catalog-Limits beachten.',
    },
    en: {
        'metaExport.pageTitle': 'Meta Export Generator',
        'metaExport.pageLead':
            'Copy-paste SQL (or mongosh) for schemas, tables, columns and access metadata — no live DB connect.',
        'metaExport.howto.summary': 'How it works',
        'metaExport.howto.overview.intro':
            'Pick a platform, copy the queries, and export results as a table (CSV) for source analysis.',
        'metaExport.howto.overview.step1': 'Choose a platform (cloud, relational, or document).',
        'metaExport.howto.overview.step2': 'Run the queries in your warehouse / client.',
        'metaExport.howto.overview.step3': 'Save results as CSV/Excel and continue analysis.',
        'metaExport.howto.overview.tip':
            'No live connect: credentials stay in your client. Adjust region/database placeholders in the queries.',
        'metaExport.platform': 'Platform',
        'metaExport.group.cloud': 'Cloud / lakehouse',
        'metaExport.group.relational': 'Relational',
        'metaExport.group.document': 'Document',
        'metaExport.platformNote': 'Note',
        'metaExport.section.schemas': 'Catalog / schemas',
        'metaExport.section.tables': 'Tables / collections',
        'metaExport.section.columns': 'Columns / fields',
        'metaExport.section.access': 'Access / grants',
        'metaExport.copy': 'Copy',
        'metaExport.copied': 'Copied',
        'metaExport.queryLabel': 'Query / script',
        'metaExport.howto.schemas.intro': 'Returns databases/schemas (or datasets) as a table.',
        'metaExport.howto.tables.intro': 'Returns tables, views, or collections.',
        'metaExport.howto.columns.intro': 'Returns columns or inferred fields (MongoDB: sample).',
        'metaExport.howto.access.intro': 'Best-effort grants/roles — watch privileges and catalog limits.',
    },
};

/** @param {string} key */
export function t(key) {
    const locale = getLocale();
    return labels[locale]?.[key] ?? labels.en[key] ?? key;
}

export function applyMetaExportLabels() {
    const locale = getLocale();
    const map = labels[locale] || labels.en;
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (key && map[key]) {
            el.textContent = map[key];
        }
    });
}
