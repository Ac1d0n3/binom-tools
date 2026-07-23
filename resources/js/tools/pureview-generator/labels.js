import { getLocale } from '../../locale';

const labels = {
    de: {
        'pureview.scan.pageTitle': 'PureView Scan Generator',
        'pureview.scan.lead': 'Erzeugt Beispiel-Artefakte fuer Data Source, Scan Scope und erste Scan-Checkliste.',
        'pureview.classification.pageTitle': 'PureView Classification Generator',
        'pureview.classification.lead': 'Erzeugt Klassifikations- und Sensitivity-Beispiele fuer Spaltenreviews.',
        'pureview.glossary.pageTitle': 'PureView Glossary Generator',
        'pureview.glossary.lead': 'Erzeugt Business-Term-, Synonym- und Asset-Mapping-Beispiele fuer das Glossary.',
        'pureview.dataProduct.pageTitle': 'PureView Data Product Generator',
        'pureview.dataProduct.lead': 'Erzeugt einen Data-Product-Steckbrief mit Ownern, DQ-Gates und Consumer-Hinweisen.',
        'pureview.howto.summary': 'Hilfe',
        'pureview.scan.howto.intro': 'Nutze den Generator, um ein erstes PureView-Scan-Setup als kopierbares Beispiel vorzubereiten.',
        'pureview.classification.howto.intro': 'Nutze den Generator, um aus Spaltennamen ein Review-Mapping fuer Klassifikationen und Sensitivity zu erzeugen.',
        'pureview.glossary.howto.intro': 'Nutze den Generator, um Business Terms, KPI-Begriffe und Asset-Verknuepfungen vorzubereiten.',
        'pureview.dataProduct.howto.intro': 'Nutze den Generator, um ein Data Product als Governance-Steckbrief vorzubereiten.',
        'pureview.howto.step1': 'Trage Asset, Domain, Plattform, Owner, Steward und relevante Spalten ein.',
        'pureview.howto.step2': 'Markiere sensitive Spalten und waehle eine Scan- oder Review-Frequenz.',
        'pureview.howto.step3': 'Kopiere JSON, Mapping oder Runbook in dein Setup-Ticket und passe IDs/Policies final an.',
        'pureview.scan.howto.tip': 'Die Ausgabe ist ein Startpunkt fuer Data Source und Scan Setup, kein Live-Deployment.',
        'pureview.classification.howto.tip': 'Die Ausgabe hilft beim ersten Steward-Review und kann danach in Purview/Fabric/dbt uebernommen werden.',
        'pureview.glossary.howto.tip': 'Die Ausgabe trennt Definition, Owner und technische Assets, damit Glossary Reviews schneller werden.',
        'pureview.dataProduct.howto.tip': 'Die Ausgabe ist bewusst als Data-Product-Canvas fuer Review, Onboarding und Release-Gates gedacht.',
        'pureview.input.title': 'PureView Eingaben',
        'pureview.input.description': 'Diese Felder steuern die Beispiel-Artefakte fuer Setup und Governance Review.',
        'pureview.input.asset': 'Asset / Begriff / Data Product',
        'pureview.input.domain': 'Domain / Collection',
        'pureview.input.platform': 'Plattform',
        'pureview.input.owner': 'Owner / Gruppe',
        'pureview.input.steward': 'Steward',
        'pureview.input.columns': 'Spalten / Felder',
        'pureview.input.sensitive': 'Sensitive Spalten',
        'pureview.input.frequency': 'Frequenz',
        'pureview.output.json': 'PureView JSON / API Beispiel',
        'pureview.output.mapping': 'Mapping / YAML Beispiel',
        'pureview.output.runbook': 'Setup-Hilfe / Runbook',
        'pureview.copy': 'Kopieren',
        'shared.copied': 'Kopiert!',
    },
    en: {
        'pureview.scan.pageTitle': 'PureView Scan Generator',
        'pureview.scan.lead': 'Generates example artifacts for data source, scan scope, and first scan checklist.',
        'pureview.classification.pageTitle': 'PureView Classification Generator',
        'pureview.classification.lead': 'Generates classification and sensitivity examples for column reviews.',
        'pureview.glossary.pageTitle': 'PureView Glossary Generator',
        'pureview.glossary.lead': 'Generates business term, synonym, and asset mapping examples for the glossary.',
        'pureview.dataProduct.pageTitle': 'PureView Data Product Generator',
        'pureview.dataProduct.lead': 'Generates a data product profile with owners, DQ gates, and consumer notes.',
        'pureview.howto.summary': 'Help',
        'pureview.scan.howto.intro': 'Use the generator to prepare a first PureView scan setup as a copyable example.',
        'pureview.classification.howto.intro': 'Use the generator to turn column names into a review mapping for classifications and sensitivity.',
        'pureview.glossary.howto.intro': 'Use the generator to prepare business terms, KPI terms, and asset links.',
        'pureview.dataProduct.howto.intro': 'Use the generator to prepare a data product as a governance profile.',
        'pureview.howto.step1': 'Enter asset, domain, platform, owner, steward, and relevant columns.',
        'pureview.howto.step2': 'Mark sensitive columns and choose a scan or review frequency.',
        'pureview.howto.step3': 'Copy JSON, mapping, or runbook into your setup ticket and adjust IDs/policies before use.',
        'pureview.scan.howto.tip': 'The output is a starting point for data source and scan setup, not a live deployment.',
        'pureview.classification.howto.tip': 'The output supports the first steward review and can then be moved into Purview/Fabric/dbt.',
        'pureview.glossary.howto.tip': 'The output separates definition, owner, and technical assets so glossary reviews move faster.',
        'pureview.dataProduct.howto.tip': 'The output is designed as a data product canvas for review, onboarding, and release gates.',
        'pureview.input.title': 'PureView inputs',
        'pureview.input.description': 'These fields shape the example artifacts for setup and governance review.',
        'pureview.input.asset': 'Asset / term / data product',
        'pureview.input.domain': 'Domain / collection',
        'pureview.input.platform': 'Platform',
        'pureview.input.owner': 'Owner / group',
        'pureview.input.steward': 'Steward',
        'pureview.input.columns': 'Columns / fields',
        'pureview.input.sensitive': 'Sensitive columns',
        'pureview.input.frequency': 'Frequency',
        'pureview.output.json': 'PureView JSON / API example',
        'pureview.output.mapping': 'Mapping / YAML example',
        'pureview.output.runbook': 'Setup help / runbook',
        'pureview.copy': 'Copy',
        'shared.copied': 'Copied!',
    },
};

export function t(locale, key) {
    return labels[locale]?.[key] ?? labels.en[key] ?? key;
}

export function applyPureViewLabels() {
    const locale = getLocale();
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (!key?.startsWith('pureview.') && !key?.startsWith('shared.')) return;
        el.textContent = t(locale, key);
    });
}
