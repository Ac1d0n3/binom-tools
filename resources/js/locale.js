const STORAGE_KEY = 'binom-tools-locale';
const DEFAULT_LOCALE = 'en';

/** @typedef {'de' | 'en'} ToolsLocale */

/** @returns {string} */
export function getAppBasePath() {
    const fromDom = document.documentElement.dataset.appBase;

    if (typeof fromDom === 'string' && fromDom !== '') {
        return fromDom.replace(/\/$/, '');
    }

    return '';
}

/** @param {string} pathname */
export function stripAppBasePath(pathname) {
    const base = getAppBasePath();

    if (base && (pathname === base || pathname.startsWith(`${base}/`))) {
        return pathname.slice(base.length) || '/';
    }

    return pathname;
}

/** @param {string} pathname */
export function withAppBasePath(pathname) {
    const base = getAppBasePath();
    const path = pathname.startsWith('/') ? pathname : `/${pathname}`;

    if (base === '') {
        return path;
    }

    return path === '/' ? base : `${base}${path}`;
}

/** @returns {ToolsLocale | null} */
export function getLocaleFromPath(pathname = window.location.pathname) {
    const path = stripAppBasePath(pathname);

    if (path === '/de' || path.startsWith('/de/')) {
        return 'de';
    }

    if (path === '/en' || path.startsWith('/en/')) {
        return 'en';
    }

    return null;
}

/** @param {string} pathname */
export function stripLocalePrefix(pathname) {
    const path = stripAppBasePath(pathname);

    if (path === '/de' || path === '/en') {
        return '/';
    }

    if (path.startsWith('/de/')) {
        return path.slice(3) || '/';
    }

    if (path.startsWith('/en/')) {
        return path.slice(3) || '/';
    }

    return path;
}

/** @param {string} pathname @param {ToolsLocale} locale */
export function localeUrlForPath(pathname, locale) {
    const path = stripLocalePrefix(pathname);

    if (locale === 'de') {
        return withAppBasePath(path === '/' ? '/de' : `/de${path}`);
    }

    return withAppBasePath(path);
}

/** @returns {ToolsLocale} */
export function getLocale() {
    const fromPath = getLocaleFromPath();

    if (fromPath) {
        return fromPath;
    }

    const stored = localStorage.getItem(STORAGE_KEY);

    return stored === 'en' || stored === 'de' ? stored : DEFAULT_LOCALE;
}

/** @param {ToolsLocale} locale @param {string | null | undefined} targetUrl */
export function setLocale(locale, targetUrl = null) {
    localStorage.setItem(STORAGE_KEY, locale);

    const target = targetUrl ?? localeUrlForPath(window.location.pathname, locale);

    if (target !== window.location.pathname && target !== window.location.href) {
        window.location.assign(target);

        return;
    }

    applyLocaleToDocument(locale);
    window.dispatchEvent(new CustomEvent('binom-tools:locale', { detail: { locale } }));
}

/** @param {ToolsLocale} locale */
export function applyLocaleToDocument(locale) {
    document.documentElement.lang = locale === 'de' ? 'de' : 'en';
}

const shellLabels = {
    de: {
        'nav.tools': 'Governance',
        'nav.home': 'Startseite',
        'nav.overview': 'Übersicht',
        'nav.openMenu': 'Navigation öffnen',
        'nav.closeMenu': 'Navigation schließen',
        'footer.copyright': `© ${new Date().getFullYear()} Binom Governance`,
        'footer.website': 'binom.net',
        'footer.binomNgx': 'binom-ngx Docs',
        'footer.impressum': 'Impressum',
        'footer.privacy': 'Datenschutz',
        'footer.about': 'Über das Projekt',
        'meta.beta': 'BETA',
        'tools.overviewBetaNote': 'Governance-Workflows in aktiver Entwicklung.',
        'about.learnMore': 'Über das Projekt',
        'about.title': 'Über binom-tools',
        'about.lead': 'Hintergrund zum Projekt — was es ist und woher Inhalte und Visuals kommen.',
        'about.project.title': 'Was ist binom-tools?',
        'about.project.body':
            'binom-tools ist ein Open-Source-Hobbyprojekt: ein Governance Help Hub mit Markdown-Stories und interaktiven Referenz-Workflows — kein kommerzielles Produkt.',
        'about.stories.title': 'Stories',
        'about.stories.body':
            'Die Playbooks geben einen generellen Einblick in Governance und die Welten darum — von Datenplattformen und BI über Prozesse bis zu den Themen, die in der Praxis wirklich zählen. Es ist Wissen, das ich über die Jahre zusammengetragen habe: Erfahrungen, Modelle und Denkanstöße, nicht ein fertiges Handbuch.',
        'about.tools.title': 'Governance',
        'about.tools.body':
            'Interaktive Referenz-Workflows machen Ideen aus den Stories praktisch umsetzbar — Schritt für Schritt, copy-paste-fähig für Warehouse oder Governance-Setup.',
        'about.visuals.title': 'Visuals',
        'about.visuals.body':
            'Diagramme und Schaubilder zu Playbook-Beispielen erstelle ich mit KI — abgestimmt auf ein einheitliches Corporate Design, damit Stories lesbar und vergleichbar bleiben.',
        'about.feedback.title': 'Feedback & Mitmachen',
        'about.feedback.body':
            'Das Projekt lebt von Austausch. Feedback, Anregungen und Verbesserungsvorschläge sind willkommen — per GitHub.',
        'about.feedback.github': 'Issues & Pull Requests auf GitHub',
        'overview.sortLabel': 'Sortieren',
        'overview.sortDateDesc': 'Neueste zuerst',
        'overview.sortDateAsc': 'Älteste zuerst',
        'overview.sortNameAsc': 'Name A–Z',
        'overview.sortNameDesc': 'Name Z–A',
        'overview.layoutToggle': 'Story-Layout',
        'overview.layoutGrid': 'Kartenansicht',
        'overview.layoutList': 'Listenansicht',
        'overview.hideRead': 'Gelesene ausblenden',
        'overview.showRead': 'Gelesene anzeigen',
        'overview.resetRead': 'Gelesen-Status zurücksetzen',
        'overview.resetReadConfirm': 'Alle Gelesen-Markierungen für Stories löschen? Das kann nicht rückgängig gemacht werden.',
        'overview.noUnreadResults': 'Alle passenden Stories sind bereits gelesen.',
        'cookie.banner.text': 'Wir speichern Einstellungen lokal in Ihrem Browser. Externe Videos laden erst nach Ihrer Einwilligung zu externen Medien.',
        'cookie.banner.privacyLink': 'Datenschutz',
        'cookie.banner.essentialOnly': 'Nur notwendig',
        'cookie.banner.acceptAll': 'Alle akzeptieren',
        'home.title': 'Binom Governance',
        'home.lead':
            'Governance Help Hub mit Markdown-Stories, interaktiven Workflows und i18n — klonbar und ohne CMS.',
        'home.hero.headline': 'Governance Help Hub',
        'home.hero.headlineAccent': 'für Data-, BI- und Analytics-Teams.',
        'home.hero.tagline':
            'Stories zu Data Governance — von PII, Qualität und Lineage bis KPIs und Ownership. Plus interaktive Governance-Workflows, versionierbar wie Code.',
        'home.hero.notice':
            'Klonbar als Starter-Template: eigene Stories, Workflows und Branding für euren internen Help Hub.',
        'home.hero.ctaWorkflows': 'Governance öffnen',
        'home.hero.ctaSdk': 'binom-ngx SDKs',
        'home.hero.attribution': 'Design-Konzept von',
        'home.workflowsTitle': 'Workflow-Beispiele',
        'home.workflowsLead':
            'Interaktive Referenz-Workflows — Schritt für Schritt, copy-paste-fähig.',
        'home.toolsTitle': 'Governance',
        'home.aiTitle': 'AI-Tools',
        'home.aiLead':
            'Prompts erstellen und vor dem Senden an externe KI-Tools anonymisieren.',
        'home.storiesTitle': 'Governance-Stories',
        'home.storiesLead':
            'Playbooks zu allen Themen rund um Data Governance — Schritt für Schritt, von der Idee bis zur Umsetzung.',
        'home.sprintPlannerTitle': 'Sprint Planner',
        'home.sprintPlannerLead':
            'Pläne aus Vorlagen starten, Exports anhängen und Inventare in konkrete Aufgaben und Entscheidungen überführen.',
        'home.featuredPlanner.title': 'Sprint Planner',
        'home.featuredPlanner.description':
            'BI- und Governance-Arbeit mit Vorlagen planen, Tool-Exports anhängen und Inventare, KPI-Funde und offene Entscheidungen in nachvollziehbare Aufgaben überführen.',
        'home.viewAllTotal': 'insgesamt',
        'home.viewAllTools.title': 'Alle Workflows',
        'home.viewAllTools.description': 'Zur Governance-Übersicht mit Suche und allen Workflow-Beispielen.',
        'home.viewAllStories.title': 'Alle Stories',
        'home.viewAllStories.description': 'Zur Übersicht mit Suche, Tags und allen Governance-Guides.',
        'home.ecosystemTitle': 'Ökosystem',
        'tools.overviewTitle': 'Governance',
        'tools.overviewLead': 'Interaktive Referenz-Workflows — Schritt für Schritt, copy-paste-fähig.',
        'overview.searchLabel': 'Suchen',
        'overview.searchPlaceholder': 'Suchen…',
        'overview.productLabel': 'Produkt',
        'overview.productAll': 'Alle Produkte',
        'overview.tagAll': 'ALL',
        'overview.filterTitle': 'Filter',
        'overview.filterLead': 'Stories nach Kategorie und Thema eingrenzen.',
        'overview.categoryTitle': 'Kategorie',
        'overview.categoryAll': 'ALL',
        'overview.tagsSectionTitle': 'Tags',
        'overview.tagModeOr': 'OR',
        'overview.tagModeAnd': 'AND',
        'overview.filterReset': 'Filter zurücksetzen',
        'overview.tagsTitle': 'Tags',
        'overview.tagsLead': 'Stories nach Thema filtern. Die Zahl zeigt, wie oft ein Tag verwendet wird.',
        'overview.tagsSearchLabel': 'Tags durchsuchen',
        'overview.tagsSearchPlaceholder': 'Tags suchen…',
        'overview.tagsNoResults': 'Keine passenden Tags.',
        'overview.noResults': 'Keine Treffer für deine Suche.',
        'overview.viewStories': 'Stories',
        'overview.viewSeries': 'Serien',
        'overview.seriesStart': 'Serie starten',
        'overview.seriesNoResults': 'Keine passenden Serien.',
        'playbooks.seriesPartLabel': 'Teil',
        'theme.light': 'Hell',
        'theme.dark': 'Dunkel',
        'theme.toggleToDark': 'Dunkelmodus aktivieren',
        'theme.toggleToLight': 'Hellmodus aktivieren',
        'settings.openMenu': 'Einstellungen',
        'settings.fullWidth': 'Volle Breite',
        'settings.hideNavigation': 'Navigation ausblenden',
        'settings.hideSidebars': 'Sidebars ausblenden',
        'playbooks.focusExpand': 'Sidebars ausblenden',
        'playbooks.focusCollapse': 'Sidebars einblenden',
        'playbooks.tocShow': 'Inhaltsverzeichnis einblenden',
        'playbooks.tocHide': 'Inhaltsverzeichnis ausblenden',
        'card.exampleBadge': 'Beispiel',
        'nav.dbt-governance-macro-generator': 'PII Macro Generator',
        'nav.pii-recommend-generator': 'PII Recommend Generator',
        'card.dbt-governance-macro-generator.title': 'PII Macro Generator',
        'card.dbt-governance-macro-generator.description':
            'Laufzeit-Makros, Generic Tests und SETUP.md für dein dbt-Projekt.',
        'card.pii-recommend-generator.title': 'PII Recommend Generator',
        'card.pii-recommend-generator.description':
            'Name- und Content-Audit, Heuristik-Regeln und pii_recommend schema.yml.',
        'workflow.setupLabel.dbt-pii-governance': 'Security & Governance',
        'workflow.setupLabel.dbt-dq-governance': 'Datenqualität',
        'workflow.setupLabel.ai-prompt-workflow': 'AI Prompt Workflow',
        'workflow.setupLabel.discovery-assessment': 'Discovery & Assessment',
        'workflow.stepPrefix': 'Schritt',
        'workflow.prev': '← Zurück',
        'workflow.next': 'Weiter →',
        'workflow.step-dbt-governance-macro-generator': 'PII Macro Generator',
        'workflow.step-pii-policy-generator': 'PII Policy Generator',
        'workflow.step-pii-unreviewed-gate-generator': 'PII Table Gate',
        'workflow.step-pii-recommend-generator': 'PII Recommend Generator',
        'workflow.step-dbt-dq-macro-generator': 'DG Macro Generator',
        'workflow.step-dbt-dq-rules-generator': 'DQ Rules Generator',
        'workflow.step-dbt-dq-history-generator': 'DQ History Generator',
        'workflow.step-prompt-studio': 'Prompt Studio',
        'workflow.step-governance-ai-sanitizer': 'AI Sanitizer',
        'workflow.step-stakeholder-matrix': 'Stakeholder Matrix',
        'workflow.step-report-inventory': 'Report Inventory',
        'workflow.step-kpi-definition': 'KPI Definition',
        'workflow.step-bi-python-toolkit': 'BI Python Toolkit',
        'workflow.step-architecture-fit': 'Architecture Fit',
        'workflow.step-impact-effort': 'Impact–Effort',
        'nav.dbt-dq-macro-generator': 'DG Macro Generator',
        'card.dbt-dq-macro-generator.title': 'DQ Macro Generator',
        'card.dbt-dq-macro-generator.description':
            'Laufzeit-Makros, Generic Test dq_rule und SETUP_DQ.md für dein dbt-Projekt.',
        'nav.dbt-dq-rules-generator': 'DQ Rules Generator',
        'card.dbt-dq-rules-generator.title': 'DQ Rules Generator',
        'card.dbt-dq-rules-generator.description':
            'meta.dq_rules in schema.yml — Regeln pro Spalte und Model.',
        'nav.dbt-dq-history-generator': 'DQ History Generator',
        'card.dbt-dq-history-generator.title': 'DQ History Generator',
        'card.dbt-dq-history-generator.description':
            'dq_run_history, Report-Views und dq_collect_results Runbook.',
        'nav.pii-unreviewed-gate-generator': 'PII Table Gate',
        'card.pii-unreviewed-gate-generator.title': 'PII Table Gate',
        'card.pii-unreviewed-gate-generator.description':
            'Ungeprüfte Models identifizieren und für Rollen verstecken.',
        'nav.governance-ai-sanitizer': 'AI Sanitizer',
        'nav.prompt-studio': 'Prompt Studio',
        'card.prompt-studio.title': 'Prompt Studio',
        'card.prompt-studio.description':
            'Professionelle KI-Prompts für ChatGPT, Claude, Gemini, Suno, Midjourney und mehr.',
        'card.governance-ai-sanitizer.title': 'Governance AI Sanitizer',
        'card.governance-ai-sanitizer.description':
            'Prompt sanitisieren, Outbound kopieren, KI-Antwort wiederherstellen.',
        'nav.pii-policy-generator': 'PII Policy Generator',
        'card.pii-policy-generator.title': 'PII Policy Generator',
        'card.pii-policy-generator.description':
            'pii_details schema.yml-Beispiel und Secure Model — Zielzustand nach Review.',
        'nav.schema-yml-editor': 'Schema-YML-Editor',
        'card.schema-yml-editor.title': 'Schema YML Editor',
        'card.schema-yml-editor.description':
            'Hilfs-Tool: einzelne schema.yml bearbeiten — nicht Teil der Einrichtungs-Kette.',
        'nav.meta-export-generator': 'Meta Export Generator',
        'card.meta-export-generator.title': 'Meta Export Generator',
        'card.meta-export-generator.description':
            'Copy-Paste-SQL/Scripts für Schemas, Tabellen, Spalten und Access-Meta — 10 Plattformen, kein Live-Connect.',
        'nav.pureview-scan-generator': 'PureView Scan Generator',
        'nav.pureview-classification-generator': 'PureView Classification Generator',
        'nav.pureview-glossary-generator': 'PureView Glossary Generator',
        'nav.pureview-data-product-generator': 'PureView Data Product Generator',
        'nav.stakeholder-matrix': 'Stakeholder Matrix',
        'card.stakeholder-matrix.title': 'Stakeholder & RACI Matrix',
        'card.stakeholder-matrix.description':
            'Export für den Plan (keine Tool-Speicherung) — Einfluss, Interesse, optional RACI.',
        'nav.report-inventory': 'Report Inventory',
        'card.report-inventory.title': 'Report Inventory Canvas',
        'card.report-inventory.description':
            'Report-Inventar erzeugen und in den Sprint-Plan kopieren — nichts wird im Tool gespeichert.',
        'nav.kpi-definition': 'KPI Definition',
        'card.kpi-definition.title': 'KPI Definition Card',
        'card.kpi-definition.description':
            'KPI-Definitionen für Plan-Deliverables — nur Export, keine Speicherung im Tool.',
        'nav.bi-python-toolkit': 'BI Python Toolkit',
        'card.bi-python-toolkit.title': 'BI Python Export Toolkit',
        'card.bi-python-toolkit.description':
            'Python-Dateien für Qlik KPI-Export, App-/Sheet-Inventar und BI-Formel-Inventare herunterladen.',
        'nav.qlik-set-analysis-generator': 'Qlik Set Analysis Generator',
        'card.qlik-set-analysis-generator.title': 'Qlik Set Analysis Generator',
        'card.qlik-set-analysis-generator.description':
            'Child Measures und Variablen aus einer Base Measure plus Dimension-Wert-CSV erzeugen.',
        'nav.tableau-calculation-generator': 'Tableau Calculation Generator',
        'card.tableau-calculation-generator.title': 'Tableau Calculation Generator',
        'card.tableau-calculation-generator.description':
            'Calculated Fields, LOD-Varianten und Dokumentations-CSV aus Base Measures plus Definitionen erzeugen.',
        'nav.powerbi-dax-generator': 'Power BI DAX Measure Generator',
        'card.powerbi-dax-generator.title': 'Power BI DAX Measure Generator',
        'card.powerbi-dax-generator.description':
            'DAX Measures, Time-Intelligence-Vorlagen und Dokumentations-CSV aus Base Measures plus Definitionen erzeugen.',
        'tableauCalc.pageTitle': 'Tableau Calculation Generator',
        'tableauCalc.pageLead': 'Erzeuge Tableau Calculated Fields, LOD-Varianten und Dokumentations-CSV aus Base Measures plus wiederverwendbaren Definitionen.',
        'tableauCalc.help.title': 'Tableau Calculation Hilfe',
        'tableauCalc.help.lead': 'Workflow-Hilfe für Katalog, Definitionen, Base Measures, Hierarchie und Calculated Fields.',
        'tableauCalc.help.show': 'Hilfe anzeigen',
        'tableauCalc.help.hide': 'Hilfe ausblenden',
        'tableauCalc.help.productLink': 'Tableau Produktseite',
        'tableauCalc.help.productHelpLink': 'Tableau Hilfe-Portal',
        'tableauCalc.help.calculatedFieldsLink': 'Calculated Fields Dokumentation',
        'tableauCalc.help.lodLink': 'LOD Expressions Dokumentation',
        'tableauCalc.help.summary': 'Kurzlogik',
        'tableauCalc.help.step1': 'Katalog pflegen: Dimensionen, Measures und verfügbare Werte aus deiner Tableau Workbook-Struktur eintragen.',
        'tableauCalc.help.step2': 'Definitionen bauen: Eine Definition ist eine wiederverwendbare Bedingung wie Region DACH oder Current Year.',
        'tableauCalc.help.step3': 'Base Measures pflegen: Sales, Costs, Margin usw. bleiben reine Tableau-Ausdrücke.',
        'tableauCalc.help.step4': 'Ausgabe kopieren: In Tableau links im Data Pane per Rechtsklick oder Menü ein Calculated Field anlegen und Formel einfügen.',
        'tableauCalc.help.catalogTitle': '1. Katalog',
        'tableauCalc.help.definitionsTitle': '2. Definition',
        'tableauCalc.help.baseTitle': '3. Base Measures',
        'tableauCalc.help.outputTitle': '4. Ausgabe',
        'tableauCalc.help.whereTitle': 'Wo in Tableau?',
        'tableauCalc.help.whereBody': 'Calculated Fields kommen in Tableau Desktop in ein neues berechnetes Feld. Hierarchien werden im Data Pane angelegt, indem du Felder in der gewünschten Reihenfolge zu einer Hierarchie ziehst.',
        'tableauCalc.apps.save': 'App speichern',
        'tableauCalc.apps.load': 'App laden',
        'tableauCalc.apps.delete': 'App löschen',
        'tableauCalc.apps.name': 'App-Name',
        'tableauCalc.apps.saved': 'Gespeicherte Apps',
        'tableauCalc.apps.none': 'Keine App gewählt',
        'tableauCalc.apps.missingName': 'Bitte einen App-Namen eingeben.',
        'tableauCalc.apps.missingSelection': 'Bitte eine gespeicherte App wählen.',
        'tableauCalc.apps.savedMessage': 'App „{name}“ gespeichert.',
        'tableauCalc.apps.deletedMessage': 'App „{name}“ gelöscht.',
        'tableauCalc.workbench.title': 'Formel-Workbench',
        'tableauCalc.workbench.description': 'Katalog, Base Measures und Definitionen sind getrennte Arbeitsbereiche. Die Ausgabe darunter bleibt separat kopier- und exportierbar.',
        'tableauCalc.workbench.controlTitle': 'Formel-Workbench',
        'tableauCalc.workbench.controlHint': 'App-Kontext, Katalog, Base-Formeln, Filter und Hierarchie bearbeiten.',
        'tableauCalc.workbench.hide': 'Workbench ausblenden',
        'tableauCalc.workbench.show': 'Workbench anzeigen',
        'tableauCalc.layout.catalog': 'Katalog',
        'tableauCalc.layout.formula': 'Formel',
        'tableauCalc.layout.builder': 'Filter & Baum',
        'tableauCalc.layout.focusFormula': 'Fokus Formel',
        'tableauCalc.layout.showAll': 'Alles anzeigen',
        'tableauCalc.tabs.definitions': 'Definitionen',
        'tableauCalc.tabs.hierarchy': 'Hierarchie',
        'tableauCalc.tabs.extras': 'Extras',
        'tableauCalc.catalog.title': 'App-Katalog',
        'tableauCalc.catalog.fields': 'Felder',
        'tableauCalc.catalog.values': 'Dim-Werte',
        'tableauCalc.catalog.rawData': 'Rohdaten bearbeiten',
        'tableauCalc.catalog.rawDataHint': 'Fields und Values als CSV pflegen oder Dateien hochladen.',
        'tableauCalc.catalog.fieldsUpload': 'Fields CSV laden',
        'tableauCalc.catalog.valuesUpload': 'Values CSV laden',
        'tableauCalc.catalog.tabs.dimensions': 'Dimensionen',
        'tableauCalc.catalog.tabs.measures': 'Measures',
        'tableauCalc.catalog.hint': 'Chips ziehen oder anklicken. Rohdaten öffnest du über den Button darunter.',
        'tableauCalc.modal.close': 'Schließen',
        'tableauCalc.definitions.title': 'Definitionen',
        'tableauCalc.definitions.hint': 'Dimension und Werte wählen, speichern und als wiederverwendbare Bedingung nutzen.',
        'tableauCalc.definitions.dimension': 'Dimension',
        'tableauCalc.definitions.values': 'Werte',
        'tableauCalc.definitions.name': 'Name',
        'tableauCalc.definitions.add': 'Definition speichern',
        'tableauCalc.definitions.raw': 'Definitionen CSV',
        'tableauCalc.definitions.preview': 'Vorschau',
        'tableauCalc.definitions.dropTitle': 'Dimension hier ablegen',
        'tableauCalc.definitions.dropHint': 'Ziehe eine Dimension aus dem Katalog hierher, um daraus eine Definition zu bauen.',
        'tableauCalc.definitions.remove': 'Entfernen',
        'tableauCalc.formula.title': 'Formel',
        'tableauCalc.formula.hint': 'Hier baust und bearbeitest du die aktive Tableau Base Measure. Bausteine und Felder werden an der Cursor-Position eingefügt.',
        'tableauCalc.formula.current': 'Aktuelle Formel',
        'tableauCalc.formula.name': 'Measure Name',
        'tableauCalc.formula.field': 'Feld',
        'tableauCalc.formula.apply': 'Base Measure setzen',
        'tableauCalc.history.undo': 'Rückgängig',
        'tableauCalc.history.redo': 'Wiederholen',
        'tableauCalc.functions.title': 'Formel-Funktionen',
        'tableauCalc.functions.hint': 'Baustein anklicken, um die Formel zu erweitern.',
        'tableauCalc.baseList.title': 'Base Measures',
        'tableauCalc.baseList.hint': 'Aktive Base laden, speichern oder neu anlegen.',
        'tableauCalc.baseList.show': 'Anzeigen',
        'tableauCalc.baseList.hide': 'Ausblenden',
        'tableauCalc.baseList.active': 'Aktive Base',
        'tableauCalc.baseList.load': 'Base laden',
        'tableauCalc.baseList.saveCurrent': 'Current speichern',
        'tableauCalc.baseList.newBase': 'Neue Base',
        'tableauCalc.baseList.csv': 'Base Measures CSV',
        'tableauCalc.baseList.rawHint': 'CSV bleibt die Quelle für gespeicherte Base Measures.',
        'tableauCalc.description.title': 'Beschreibung',
        'tableauCalc.description.hint': 'Beschreibungstemplates werden für die generierten Tableau Calculated Fields genutzt.',
        'tableauCalc.description.de': 'Template DE',
        'tableauCalc.description.en': 'Template EN',
        'tableauCalc.extras.title': 'Extras',
        'tableauCalc.extras.hint': 'Generierungsmodus und LOD-Dimensionen für Fallback aus Values.',
        'tableauCalc.extras.mode': 'Generierungsmodus',
        'tableauCalc.extras.modeSingle': 'Ein Child pro Dimensionswert',
        'tableauCalc.extras.modeCombined': 'Dimensionen zu Varianten kombinieren',
        'tableauCalc.extras.modeDimensionGroup': 'Ein Child pro Dimension mit allen Werten',
        'tableauCalc.extras.lodDimensions': 'LOD FIXED Dimensionen',
        'tableauCalc.extras.lodHint': 'Kommagetrennt. Leer = Dimensions der jeweiligen Definition.',
        'tableauCalc.output.calcs': 'Calculated Fields',
        'tableauCalc.output.lod': 'LOD',
        'tableauCalc.output.hierarchy': 'Hierarchie',
        'tableauCalc.output.defs': 'Definitionen',
        'tableauCalc.output.csv': 'CSV',
        'tableauCalc.copy': 'Kopieren',
        'tableauCalc.downloadCsv': 'CSV laden',
        'tableauCalc.downloadXlsx': 'Excel laden',
        'tableauCalc.saved': 'Definition gespeichert.',
        'tableauCalc.hierarchy.title': 'Hierarchie',
        'tableauCalc.hierarchy.hint': 'Dimensionen ablegen oder eingerückte Ebenen pflegen. Der Child-Baum zeigt die Base Measure als Wurzel.',
        'tableauCalc.hierarchy.dropTitle': 'Hierarchie-Ebene hier ablegen',
        'tableauCalc.hierarchy.dropHint': 'Ziehe Dimensionen hierher. Sie werden an die Hierarchie angehängt.',
        'tableauCalc.tree.title': 'Measure-Child-Baum',
        'tableauCalc.tree.hint': 'Dimensionen auf die Base oder einen Knoten legen, um verschachtelte Pfade zu bauen.',
        'tableauCalc.tree.base': 'Base Measure',
        'tableauCalc.tree.dropOnBase': 'Dimension auf erster Ebene ablegen',
        'tableauCalc.tree.dropOnNode': 'Unter {level} ablegen',
        'tableauCalc.tree.remove': 'Entfernen',
        'powerbiDax.pageTitle': 'Power BI DAX Measure Generator',
        'powerbiDax.pageLead': 'Erzeuge DAX Measures, Time-Intelligence-Vorlagen, Hierarchie-Hinweise und Dokumentations-CSV aus Base Measures plus Definitionen.',
        'powerbiDax.help.title': 'Power BI DAX Hilfe',
        'powerbiDax.help.lead': 'Workflow-Hilfe für Katalog, Definitionen, Base Measures, Hierarchie und DAX Measures.',
        'powerbiDax.help.show': 'Hilfe anzeigen',
        'powerbiDax.help.hide': 'Hilfe ausblenden',
        'powerbiDax.help.productLink': 'Power BI Produktseite',
        'powerbiDax.help.productHelpLink': 'Power BI Hilfe-Portal',
        'powerbiDax.help.calculateLink': 'CALCULATE Dokumentation',
        'powerbiDax.help.measuresLink': 'Measures Dokumentation',
        'powerbiDax.help.summary': 'Kurzlogik',
        'powerbiDax.help.step1': 'Katalog pflegen: Tabellen, Spalten, Measures und Werte aus dem Power-BI-Modell eintragen.',
        'powerbiDax.help.step2': 'Definitionen bauen: Eine Definition wird zu einem DAX-Filter in CALCULATE.',
        'powerbiDax.help.step3': 'Base Measures sauber halten: Sales, Costs, Margin bleiben eigenständige Measures.',
        'powerbiDax.help.step4': 'Ausgabe kopieren: In Power BI Desktop ein neues Measure anlegen und DAX einfügen.',
        'powerbiDax.help.catalogTitle': '1. Katalog',
        'powerbiDax.help.definitionsTitle': '2. Definition',
        'powerbiDax.help.baseTitle': '3. Base Measures',
        'powerbiDax.help.outputTitle': '4. Ausgabe',
        'powerbiDax.help.whereTitle': 'Wo in Power BI?',
        'powerbiDax.help.whereBody': 'DAX Measures legst du in Power BI Desktop als neues Measure an. Hierarchien entstehen im Data Pane oder Model View, indem du Spalten unter eine Hierarchie ziehst.',
        'powerbiDax.apps.save': 'App speichern',
        'powerbiDax.apps.load': 'App laden',
        'powerbiDax.apps.delete': 'App löschen',
        'powerbiDax.apps.name': 'App-Name',
        'powerbiDax.apps.saved': 'Gespeicherte Apps',
        'powerbiDax.apps.none': 'Keine App gewählt',
        'powerbiDax.apps.missingName': 'Bitte einen App-Namen eingeben.',
        'powerbiDax.apps.missingSelection': 'Bitte eine gespeicherte App wählen.',
        'powerbiDax.apps.savedMessage': 'App „{name}“ gespeichert.',
        'powerbiDax.apps.deletedMessage': 'App „{name}“ gelöscht.',
        'powerbiDax.workbench.title': 'Formel-Workbench',
        'powerbiDax.workbench.description': 'Katalog, Base Measures und Definitionen sind getrennte Arbeitsbereiche. Die Ausgabe darunter bleibt separat kopier- und exportierbar.',
        'powerbiDax.workbench.controlTitle': 'Formel-Workbench',
        'powerbiDax.workbench.controlHint': 'App-Kontext, Katalog, Base-Formeln, Filter und Hierarchie bearbeiten.',
        'powerbiDax.workbench.hide': 'Workbench ausblenden',
        'powerbiDax.workbench.show': 'Workbench anzeigen',
        'powerbiDax.layout.catalog': 'Katalog',
        'powerbiDax.layout.formula': 'Formel',
        'powerbiDax.layout.builder': 'Filter & Baum',
        'powerbiDax.layout.focusFormula': 'Fokus Formel',
        'powerbiDax.layout.showAll': 'Alles anzeigen',
        'powerbiDax.tabs.definitions': 'Definitionen',
        'powerbiDax.tabs.hierarchy': 'Hierarchie',
        'powerbiDax.tabs.extras': 'Extras',
        'powerbiDax.catalog.title': 'Modell-Katalog',
        'powerbiDax.catalog.fields': 'Felder',
        'powerbiDax.catalog.values': 'Dim-Werte',
        'powerbiDax.catalog.rawData': 'Rohdaten bearbeiten',
        'powerbiDax.catalog.rawDataHint': 'Fields und Values als CSV pflegen oder Dateien hochladen.',
        'powerbiDax.catalog.fieldsUpload': 'Fields CSV laden',
        'powerbiDax.catalog.valuesUpload': 'Values CSV laden',
        'powerbiDax.catalog.tabs.dimensions': 'Dimensionen',
        'powerbiDax.catalog.tabs.measures': 'Measures',
        'powerbiDax.catalog.hint': 'Chips ziehen oder anklicken. Rohdaten öffnest du über den Button darunter.',
        'powerbiDax.modal.close': 'Schließen',
        'powerbiDax.definitions.title': 'Definitionen',
        'powerbiDax.definitions.hint': 'Spalte und Werte wählen, speichern und als CALCULATE-Filter nutzen.',
        'powerbiDax.definitions.column': 'Spalte',
        'powerbiDax.definitions.values': 'Werte',
        'powerbiDax.definitions.name': 'Name',
        'powerbiDax.definitions.add': 'Definition speichern',
        'powerbiDax.definitions.raw': 'Definitionen CSV',
        'powerbiDax.definitions.preview': 'DAX-Filter',
        'powerbiDax.definitions.dropTitle': 'Spalte hier ablegen',
        'powerbiDax.definitions.dropHint': 'Ziehe eine Dimension aus dem Katalog hierher, um daraus einen CALCULATE-Filter zu bauen.',
        'powerbiDax.definitions.remove': 'Entfernen',
        'powerbiDax.formula.title': 'Formel',
        'powerbiDax.formula.hint': 'Hier baust und bearbeitest du die aktive DAX Base Measure. Bausteine und Felder werden an der Cursor-Position eingefügt.',
        'powerbiDax.formula.current': 'Aktuelle Formel',
        'powerbiDax.formula.name': 'Measure Name',
        'powerbiDax.formula.field': 'Spalte',
        'powerbiDax.formula.apply': 'Base Measure setzen',
        'powerbiDax.history.undo': 'Rückgängig',
        'powerbiDax.history.redo': 'Wiederholen',
        'powerbiDax.functions.title': 'DAX-Funktionen',
        'powerbiDax.functions.hint': 'Baustein anklicken, um die Formel zu erweitern.',
        'powerbiDax.baseList.title': 'Base Measures',
        'powerbiDax.baseList.hint': 'Aktive Base laden, speichern oder neu anlegen.',
        'powerbiDax.baseList.show': 'Anzeigen',
        'powerbiDax.baseList.hide': 'Ausblenden',
        'powerbiDax.baseList.active': 'Aktive Base',
        'powerbiDax.baseList.load': 'Base laden',
        'powerbiDax.baseList.saveCurrent': 'Current speichern',
        'powerbiDax.baseList.newBase': 'Neue Base',
        'powerbiDax.baseList.csv': 'Base Measures CSV',
        'powerbiDax.baseList.rawHint': 'CSV bleibt die Quelle für gespeicherte Base Measures.',
        'powerbiDax.description.title': 'Beschreibung',
        'powerbiDax.description.hint': 'Beschreibungstemplates werden für die generierten Power BI Measures genutzt.',
        'powerbiDax.description.de': 'Template DE',
        'powerbiDax.description.en': 'Template EN',
        'powerbiDax.extras.title': 'Extras',
        'powerbiDax.extras.hint': 'Generierungsmodus und Date-Spalte für Time Intelligence.',
        'powerbiDax.extras.mode': 'Generierungsmodus',
        'powerbiDax.extras.modeSingle': 'Ein Child pro Dimensionswert',
        'powerbiDax.extras.modeCombined': 'Dimensionen zu Varianten kombinieren',
        'powerbiDax.extras.modeDimensionGroup': 'Ein Child pro Dimension mit allen Werten',
        'powerbiDax.extras.dateColumn': 'Date-Spalte (Time Intelligence)',
        'powerbiDax.extras.dateHint': "DAX-Spalte für YTD/PY/YoY, z.B. 'Date'[Date].",
        'powerbiDax.output.measures': 'Measures',
        'powerbiDax.output.time': 'Zeit',
        'powerbiDax.output.hierarchy': 'Hierarchie',
        'powerbiDax.output.defs': 'Definitionen',
        'powerbiDax.output.csv': 'CSV',
        'powerbiDax.copy': 'Kopieren',
        'powerbiDax.downloadCsv': 'CSV laden',
        'powerbiDax.downloadXlsx': 'Excel laden',
        'powerbiDax.saved': 'Definition gespeichert.',
        'powerbiDax.hierarchy.title': 'Hierarchie',
        'powerbiDax.hierarchy.hint': 'Dimensionen ablegen oder eingerückte Ebenen pflegen. Der Child-Baum zeigt die Base Measure als Wurzel.',
        'powerbiDax.hierarchy.dropTitle': 'Hierarchie-Ebene hier ablegen',
        'powerbiDax.hierarchy.dropHint': 'Ziehe Dimensionen hierher. Sie werden an die Hierarchie angehängt.',
        'powerbiDax.tree.title': 'Measure-Child-Baum',
        'powerbiDax.tree.hint': 'Dimensionen auf die Base oder einen Knoten legen, um verschachtelte Pfade zu bauen.',
        'powerbiDax.tree.base': 'Base Measure',
        'powerbiDax.tree.dropOnBase': 'Dimension auf erster Ebene ablegen',
        'powerbiDax.tree.dropOnNode': 'Unter {level} ablegen',
        'powerbiDax.tree.remove': 'Entfernen',
        'biPythonToolkit.pageTitle': 'BI Python Export Toolkit',
        'biPythonToolkit.pageLead':
            'Lade Python-Tools herunter, führe sie lokal aus und nutze die Outputs als CSV, Markdown oder Plan-JSON.',
        'biPythonToolkit.howto.intro':
            'Die Tools laufen auf deinem Rechner. Dieses Web-Tool speichert keine API Keys und verbindet sich nicht direkt mit Qlik, Power BI oder Tableau.',
        'biPythonToolkit.howto.step1': 'Python installieren und einen Projektordner mit virtuellem Environment anlegen.',
        'biPythonToolkit.howto.step2': 'Benötigte Python-Dateien hier herunterladen und in den Projektordner legen.',
        'biPythonToolkit.howto.step3': 'Exports lokal ausführen und CSV, Markdown oder Plan-JSON im Sprint Planner verwenden.',
        'biPythonToolkit.howto.tip':
            'Für Qlik-Sheets wird zusätzlich websocket-client benötigt. Ohne --include-sheets reicht die Python-Standardbibliothek.',
        'biPythonToolkit.setupStoryLink':
            'Python-Anleitung: Python installieren, Ordner initialisieren, Exports ausführen',
        'biPythonToolkit.openSetupStory': 'Python Setup Story',
        'biPythonToolkit.downloads.title': 'Downloads',
        'biPythonToolkit.downloads.lead': 'Speichere die Dateien lokal in deinem Python-Projektordner.',
        'biPythonToolkit.download.kpi': 'KPI Export Script',
        'biPythonToolkit.download.inventory': 'Qlik App Inventory Script',
        'biPythonToolkit.download.readme': 'README herunterladen',
        'biPythonToolkit.commands.title': 'Beispiel-Kommandos',
        'biPythonToolkit.commands.lead': 'Passe Dateinamen, Tenant und API Key an deine Umgebung an.',
        'biPythonToolkit.scripts.title': 'Scripts ansehen und kopieren',
        'biPythonToolkit.scripts.lead':
            'Du kannst die Python-Dateien direkt hier prüfen, kopieren oder als Datei herunterladen.',
        'biPythonToolkit.copyScript': 'Script kopieren',
        'biPythonToolkit.downloadScript': 'Download',
        'nav.architecture-fit': 'Architecture Fit',
        'card.architecture-fit.title': 'Architecture Fit Checklist',
        'card.architecture-fit.description':
            'Architekturdiagnose als Markdown für den Plan — Persistenz nur im Sprint Planner.',
        'nav.impact-effort': 'Impact–Effort',
        'card.impact-effort.title': 'Impact–Effort Prioritizer',
        'card.impact-effort.description':
            'Priorisierungsmatrix für den Plan exportieren — keine Speicherung im Tool.',
        'card.binom-ngx.title': 'binom-ngx',
        'card.binom-ngx.description': 'Angular UI Libraries, SDKs und interaktive Dokumentation.',
        'card.binom-ngx.meta': 'Docs & Demos',
        'nav.stories': 'Stories',
        'nav.storiesOverview': 'Übersicht',
        'nav.storiesMore': '+ {{count}} weitere Stories',
        'nav.sprintPlanner': 'Sprint Planner',
        'nav.sprintPlannerPlans': 'Meine Pläne',
        'nav.sprintPlannerTemplates': 'Vorlagen',
        'nav.sprintPlannerPeople': 'Teams & Personen',
        'nav.account': 'Konto',
        'nav.accountProfile': 'Profil',
        'nav.accountUsers': 'Benutzer',
        'nav.accountTeams': 'Teams',
        'nav.accountStoryAccess': 'Story-Zugriff',
        'nav.accountSignIn': 'Anmelden',
        'accounts.signIn': 'Anmelden',
        'accounts.signOut': 'Abmelden',
        'accounts.signedInAs': 'Angemeldet',
        'accounts.loginTitle': 'Anmelden',
        'accounts.loginLead': 'Lokale dateibasierte Accounts — Passwörter nur gehashed.',
        'accounts.email': 'E-Mail',
        'accounts.password': 'Passwort',
        'accounts.profileTitle': 'Konto',
        'accounts.displayName': 'Anzeigename',
        'accounts.currentPassword': 'Aktuelles Passwort (zum Ändern)',
        'accounts.newPassword': 'Neues Passwort',
        'accounts.confirmPassword': 'Neues Passwort bestätigen',
        'accounts.save': 'Speichern',
        'accounts.saved': 'Gespeichert.',
        'accounts.usersTitle': 'Benutzer',
        'accounts.usersLead': 'Verwaltung in users.json — Passwörter nur als Hash.',
        'accounts.addUser': 'Benutzer hinzufügen',
        'accounts.editUser': 'Benutzer bearbeiten',
        'accounts.existingUsers': 'Benutzer',
        'accounts.noUsers': 'Noch keine Benutzer.',
        'accounts.backToUsers': 'Zurück zu Benutzern',
        'accounts.teamsTitle': 'Teams',
        'accounts.teamsLead': 'Teams einzeln anlegen und bearbeiten. Mitgliedschaft wird mit Benutzerkonten synchronisiert.',
        'accounts.addTeam': 'Team hinzufügen',
        'accounts.editTeam': 'Team bearbeiten',
        'accounts.existingTeams': 'Teams',
        'accounts.noTeams': 'Noch keine Teams.',
        'accounts.backToTeams': 'Zurück zu Teams',
        'accounts.manageUsers': 'Benutzer verwalten',
        'accounts.manageTeams': 'Teams verwalten',
        'accounts.edit': 'Bearbeiten',
        'accounts.cancel': 'Abbrechen',
        'accounts.members': 'Mitglieder',
        'accounts.role': 'Rolle',
        'accounts.role.member': 'Member',
        'accounts.role.manager': 'Manager',
        'accounts.role.ceo': 'CEO',
        'accounts.roleHint': 'Manager sind oft Plan-Owner — jeder darf trotzdem eigene Pläne anlegen.',
        'accounts.teams': 'Teams',
        'accounts.filterMembers': 'Mitglieder filtern…',
        'accounts.filterTeams': 'Teams filtern…',
        'accounts.noItems': 'Noch keine Einträge.',
        'accounts.nameDe': 'Name (DE)',
        'accounts.nameEn': 'Name (EN)',
        'accounts.descriptionDe': 'Beschreibung (DE)',
        'accounts.descriptionEn': 'Beschreibung (EN)',
        'accounts.shortName': 'Kürzel',
        'accounts.shortNameHint': '2–3 Buchstaben (A–Z), optional.',
        'accounts.color': 'Farbe',
        'accounts.colorHint': 'Volle Farben oder weiße Chips mit solidem, dotted oder dashed Rahmen.',
        'accounts.avatarIcon': 'Avatar-Icon',
        'accounts.avatarIconHint': 'Optional. Wenn gesetzt, ersetzt das Icon das Kürzel auf den Chips.',
        'accounts.teamAvatarIconHint': 'Optional. Nur Icon, oder Icon plus 2–3 Buchstaben. Team-Chips bleiben eckig.',
        'accounts.avatarTrigram': 'Kürzel',
        'accounts.archived': 'Archiviert',
        'accounts.inactive': 'Inaktiv',
        'accounts.active': 'Aktiv',
        'accounts.canManageUsers': 'Benutzer verwalten',
        'accounts.canManageTeams': 'Teams verwalten',
        'accounts.newPasswordOptional': 'Neues Passwort (optional)',
        'accounts.deleteTeam': 'Team löschen',
        'accounts.deleteUser': 'Benutzer löschen',
        'accounts.memberCount': '{{count}} Mitglieder',
        'accounts.flash.teamCreated': 'Team erstellt.',
        'accounts.flash.teamUpdated': 'Team gespeichert.',
        'accounts.flash.teamDeleted': 'Team gelöscht.',
        'accounts.flash.userCreated': 'Benutzer erstellt.',
        'accounts.flash.userCreatedInvited': 'Benutzer erstellt — Einladung per E-Mail gesendet.',
        'accounts.flash.userCreatedInviteFailed': 'Benutzer erstellt, aber E-Mail fehlgeschlagen. Passwort unten kopieren.',
        'accounts.flash.userCreatedWithPassword': 'Benutzer erstellt. Temporäres Passwort unten kopieren.',
        'accounts.flash.userUpdated': 'Benutzer gespeichert.',
        'accounts.flash.userUpdatedInvited': 'Benutzer gespeichert — neues Passwort per E-Mail gesendet.',
        'accounts.flash.userUpdatedInviteFailed': 'Gespeichert, aber E-Mail fehlgeschlagen. Passwort unten kopieren.',
        'accounts.flash.userUpdatedWithPassword': 'Gespeichert. Temporäres Passwort unten kopieren.',
        'accounts.flash.userDeleted': 'Benutzer gelöscht.',
        'accounts.flash.mustChangePassword': 'Bitte Passwort jetzt ändern.',
        'accounts.flash.temporaryPasswordLabel': 'Temporäres Passwort (jetzt kopieren):',
        'accounts.generatePassword': 'Temporäres Passwort erzeugen',
        'accounts.sendInvite': 'Einladung per E-Mail mit Passwort senden',
        'accounts.mustChangePassword': 'Passwort beim ersten Login ändern',
        'accounts.mustChangePasswordLead': 'Bitte setze zuerst ein neues Passwort, bevor du die Tools nutzt.',
        'accounts.inviteHint': 'Standard: Passwort erzeugen, per Mail schicken, Wechsel nach Login erzwingen.',
        'accounts.resetGeneratePassword': 'Mit neuem temporärem Passwort zurücksetzen',
        'accounts.resendInvite': 'Neues Passwort per E-Mail senden',
        'accounts.passwordManual': 'Passwort (wenn nicht erzeugt)',
        'accounts.addUserSubmit': 'Benutzer hinzufügen',
        'accounts.temporaryPassword': 'Temporäres Passwort',
        'accounts.pendingPasswordChange': 'Passwortwechsel ausstehend',
        'accounts.storyAclTitle': 'Story-Zugriff',
        'accounts.storyAclLead': 'Playbooks sind standardmäßig öffentlich. Einzelne Stories auf Benutzer oder Teams einschränken.',
        'accounts.storyAclStories': 'Stories',
        'accounts.editStoryAcl': 'Story-Zugriff bearbeiten',
        'accounts.backToStoryAcl': 'Zurück zum Story-Zugriff',
        'accounts.filterStories': 'Stories filtern…',
        'accounts.filterUsers': 'Benutzer filtern…',
        'accounts.noStories': 'Keine Stories gefunden.',
        'accounts.visibility': 'Sichtbarkeit',
        'accounts.visibility.public': 'Öffentlich',
        'accounts.visibility.restricted': 'Eingeschränkt',
        'accounts.allowedUsers': 'Erlaubte Benutzer',
        'accounts.allowedTeams': 'Erlaubte Teams',
        'accounts.storyAclRestrictedNote': 'Benutzer- und Teamlisten gelten nur bei eingeschränkter Sichtbarkeit.',
        'accounts.flash.aclUpdated': 'Story-Zugriff gespeichert.',
        'playbooks.indexTitle': 'Stories',
        'playbooks.indexLead': 'Schritt-für-Schritt-Governance-Guides — von der Idee bis zur Umsetzung.',
        'playbooks.empty': 'Noch keine Stories veröffentlicht.',
        'playbooks.category': 'Kategorie',
        'playbooks.author': 'Autor',
        'playbooks.readingTime': 'Lesezeit',
        'playbooks.updated': 'Aktualisiert',
        'playbooks.tags': 'Tags',
        'playbooks.views': 'Aufrufe',
        'playbooks.like': 'Gefällt mir',
        'playbooks.share': 'Teilen',
        'playbooks.shareCopied': 'Link kopiert',
        'playbooks.shareFacebook': 'Facebook',
        'playbooks.shareLinkedIn': 'LinkedIn',
        'playbooks.shareXing': 'Xing',
        'playbooks.shareWhatsApp': 'WhatsApp',
        'playbooks.shareX': 'X',
        'playbooks.shareEmail': 'E-Mail',
        'playbooks.shareCopy': 'Link kopieren',
        'playbooks.tocToggle': 'Auf dieser Seite',
        'playbooks.tocTitle': 'Auf dieser Seite',
        'playbooks.lightbox.close': 'Schließen',
        'playbooks.lightbox.prev': 'Vorheriges Bild',
        'playbooks.lightbox.next': 'Nächstes Bild',
        'playbooks.previous': 'Zurück',
        'playbooks.next': 'Weiter',
        'playbooks.downloadStarter': 'Git-Repo klonen',
        'playbooks.repositoryLink': 'GitHub-Repository',
        'playbooks.offline.save': 'Offline speichern',
        'playbooks.offline.update': 'Offline aktualisieren',
        'playbooks.offline.remove': 'Offline entfernen',
        'playbooks.offline.saveAll': 'Alle offline speichern',
        'playbooks.offline.saveAllShort': 'Offline',
        'playbooks.offline.removeAll': 'Offline-Kopien entfernen ({count})',
        'playbooks.offline.removeAllShort': 'Entfernen',
        'playbooks.offline.badge': 'Offline',
        'playbooks.offline.saved': 'Offline verfügbar ({size})',
        'playbooks.offline.allSize': 'Geschätzte Größe: {size}',
        'playbooks.offline.preparing': 'Offline-Pack wird vorbereitet…',
        'playbooks.offline.downloading': 'Wird heruntergeladen ({size})…',
        'playbooks.offline.progress': 'Download {pct}%',
        'playbooks.offline.progressStory': 'Speichere: {slug}',
        'playbooks.offline.done': 'Story ist offline verfügbar.',
        'playbooks.offline.doneAll': 'Alle verfügbaren Stories sind offline gespeichert.',
        'playbooks.offline.removed': 'Offline-Kopie entfernt.',
        'playbooks.offline.removedAll': 'Alle Offline-Kopien entfernt.',
        'playbooks.offline.error': 'Offline-Speichern fehlgeschlagen. Bitte online erneut versuchen.',
        'playbooks.offline.quota': 'Nicht genug Speicherplatz. Speichere einzelne Stories statt alle.',
        'playbooks.offline.confirmAll': 'Alle Stories offline speichern? Geschätzte Größe: {size}. Externe Videos bleiben online-only.',
        'playbooks.offline.confirmRemoveAll': 'Alle Offline-Kopien wirklich entfernen?',
        'playbooks.offline.banner': 'Du bist offline. Gespeicherte Stories bleiben lesbar.',
        'playbooks.offline.unsupported': 'Offline-Speichern braucht einen sicheren Kontext (HTTPS oder localhost) und Service Worker.',
        'playbooks.openStory': 'Story öffnen',
        'legal.impressum.title': 'Impressum',
        'legal.impressum.summary': 'Angaben gemäß § 5 TMG',
        'legal.impressum.provider.title': 'Anbieter',
        'legal.impressum.provider.body': 'Thomas Lindackers\nVollckmarstr 28\n45219 Essen\nDeutschland\n\nE-Mail: support@governance.binom.net',
        'legal.impressum.responsible.title': 'Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV',
        'legal.impressum.responsible.body': 'Thomas Lindackers\nVollckmarstr 28\n45219 Essen',
    },
    en: {
        'nav.tools': 'Governance',
        'nav.home': 'Home',
        'nav.overview': 'Overview',
        'nav.openMenu': 'Open navigation',
        'nav.closeMenu': 'Close navigation',
        'footer.copyright': `© ${new Date().getFullYear()} Binom Governance`,
        'footer.website': 'binom.net',
        'footer.binomNgx': 'binom-ngx Docs',
        'footer.impressum': 'Legal Notice',
        'footer.privacy': 'Privacy',
        'footer.about': 'About',
        'meta.beta': 'BETA',
        'tools.overviewBetaNote': 'Governance workflows in active development.',
        'about.learnMore': 'About this project',
        'about.title': 'About binom-tools',
        'about.lead': 'Background on the project — what it is and where content and visuals come from.',
        'about.project.title': 'What is binom-tools?',
        'about.project.body':
            'binom-tools is an open-source hobby project: a governance help hub with Markdown stories and interactive reference workflows — not a commercial product.',
        'about.stories.title': 'Stories',
        'about.stories.body':
            'The playbooks offer a general introduction to governance and the worlds around it — from data platforms and BI to processes and the topics that matter in practice. It is knowledge collected over the years: experience, models, and ideas to explore, not a finished handbook.',
        'about.tools.title': 'Governance',
        'about.tools.body':
            'Interactive reference workflows make ideas from the stories practical — step by step, copy-paste ready for your warehouse or governance setup.',
        'about.visuals.title': 'Visuals',
        'about.visuals.body':
            'Diagrams and illustrations for playbook examples are created with AI, aligned with a consistent corporate design so stories stay readable and comparable.',
        'about.feedback.title': 'Feedback',
        'about.feedback.body':
            'The project benefits from exchange. Feedback, suggestions, and improvements are welcome on GitHub.',
        'about.feedback.github': 'Issues & pull requests on GitHub',
        'overview.sortLabel': 'Sort',
        'overview.sortDateDesc': 'Newest first',
        'overview.sortDateAsc': 'Oldest first',
        'overview.sortNameAsc': 'Name A–Z',
        'overview.sortNameDesc': 'Name Z–A',
        'overview.layoutToggle': 'Story layout',
        'overview.layoutGrid': 'Grid view',
        'overview.layoutList': 'List view',
        'overview.hideRead': 'Hide read stories',
        'overview.showRead': 'Show read stories',
        'overview.resetRead': 'Reset read status',
        'overview.resetReadConfirm': 'Clear all read markers for stories? This cannot be undone.',
        'overview.noUnreadResults': 'All matching stories are already read.',
        'cookie.banner.text': 'We store settings locally in your browser. External videos load only after you accept external media.',
        'cookie.banner.privacyLink': 'Privacy',
        'cookie.banner.essentialOnly': 'Essential only',
        'cookie.banner.acceptAll': 'Accept all',
        'home.title': 'Binom Governance',
        'home.lead':
            'Governance help hub with Markdown stories, interactive workflows, and i18n — cloneable and CMS-free.',
        'home.hero.headline': 'Governance help hub',
        'home.hero.headlineAccent': 'for data, BI, and analytics teams.',
        'home.hero.tagline':
            'Stories on data governance — from PII, quality, and lineage to KPIs and ownership. Plus interactive governance workflows, versioned like code.',
        'home.hero.notice':
            'Clone as a starter template: your own stories, workflows, and branding for an internal help hub.',
        'home.hero.ctaWorkflows': 'Open governance',
        'home.hero.ctaSdk': 'binom-ngx SDKs',
        'home.hero.attribution': 'Design concept by',
        'home.workflowsTitle': 'Workflow examples',
        'home.workflowsLead':
            'Interactive reference workflows — step by step, copy-paste ready.',
        'home.toolsTitle': 'Governance',
        'home.aiTitle': 'AI tools',
        'home.aiLead':
            'Build prompts and sanitize them before sending to external AI tools.',
        'home.storiesTitle': 'Governance stories',
        'home.storiesLead':
            'Playbooks on data governance topics — step by step, from idea to implementation.',
        'home.sprintPlannerTitle': 'Sprint Planner',
        'home.sprintPlannerLead':
            'Start plans from templates, attach exports, and turn inventories into concrete tasks and decisions.',
        'home.featuredPlanner.title': 'Sprint Planner',
        'home.featuredPlanner.description':
            'Use templates to plan BI and governance work, attach exports from tools, and turn inventories, KPI findings and open decisions into trackable tasks.',
        'home.viewAllTotal': 'total',
        'home.viewAllTools.title': 'All workflows',
        'home.viewAllTools.description': 'Go to the governance overview with search and all workflow examples.',
        'home.viewAllStories.title': 'All stories',
        'home.viewAllStories.description': 'Go to the overview with search, tags, and all governance guides.',
        'home.ecosystemTitle': 'Ecosystem',
        'tools.overviewTitle': 'Governance',
        'tools.overviewLead': 'Interactive reference workflows — step by step, copy-paste ready.',
        'overview.searchLabel': 'Search',
        'overview.searchPlaceholder': 'Search…',
        'overview.productLabel': 'Product',
        'overview.productAll': 'All products',
        'overview.tagAll': 'ALL',
        'overview.filterTitle': 'Filter',
        'overview.filterLead': 'Narrow stories by category and topic.',
        'overview.categoryTitle': 'Category',
        'overview.categoryAll': 'ALL',
        'overview.tagsSectionTitle': 'Tags',
        'overview.tagModeOr': 'OR',
        'overview.tagModeAnd': 'AND',
        'overview.filterReset': 'Reset filters',
        'overview.tagsTitle': 'Tags',
        'overview.tagsLead': 'Filter stories by topic. Numbers show how many stories use each tag.',
        'overview.tagsSearchLabel': 'Search tags',
        'overview.tagsSearchPlaceholder': 'Search tags…',
        'overview.tagsNoResults': 'No matching tags.',
        'overview.noResults': 'No matches for your search.',
        'overview.viewStories': 'Stories',
        'overview.viewSeries': 'Series',
        'overview.seriesStart': 'Start series',
        'overview.seriesNoResults': 'No matching series.',
        'playbooks.seriesPartLabel': 'Part',
        'theme.light': 'Light',
        'theme.dark': 'Dark',
        'theme.toggleToDark': 'Activate dark mode',
        'theme.toggleToLight': 'Activate light mode',
        'settings.openMenu': 'Settings',
        'settings.fullWidth': 'Full width',
        'settings.hideNavigation': 'Hide navigation',
        'settings.hideSidebars': 'Hide sidebars',
        'playbooks.focusExpand': 'Hide sidebars',
        'playbooks.focusCollapse': 'Show sidebars',
        'playbooks.tocShow': 'Show table of contents',
        'playbooks.tocHide': 'Hide table of contents',
        'card.exampleBadge': 'Example',
        'nav.dbt-governance-macro-generator': 'PII Macro Generator',
        'nav.pii-recommend-generator': 'PII Recommend Generator',
        'card.dbt-governance-macro-generator.title': 'PII Macro Generator',
        'card.dbt-governance-macro-generator.description':
            'Runtime macros, generic tests, and SETUP.md for your dbt project.',
        'card.pii-recommend-generator.title': 'PII Recommend Generator',
        'card.pii-recommend-generator.description':
            'Name and content audit, heuristic rules, and pii_recommend schema.yml.',
        'workflow.setupLabel.dbt-pii-governance': 'Security & governance',
        'workflow.setupLabel.dbt-dq-governance': 'Data quality',
        'workflow.setupLabel.ai-prompt-workflow': 'AI prompt workflow',
        'workflow.setupLabel.discovery-assessment': 'Discovery & assessment',
        'workflow.stepPrefix': 'Step',
        'workflow.prev': '← Previous',
        'workflow.next': 'Next →',
        'workflow.step-dbt-governance-macro-generator': 'PII Macro Generator',
        'workflow.step-pii-policy-generator': 'PII Policy Generator',
        'workflow.step-pii-unreviewed-gate-generator': 'PII Table Gate',
        'workflow.step-pii-recommend-generator': 'PII Recommend Generator',
        'workflow.step-dbt-dq-macro-generator': 'DG Macro Generator',
        'workflow.step-dbt-dq-rules-generator': 'DQ Rules Generator',
        'workflow.step-dbt-dq-history-generator': 'DQ History Generator',
        'workflow.step-prompt-studio': 'Prompt Studio',
        'workflow.step-governance-ai-sanitizer': 'AI Sanitizer',
        'workflow.step-stakeholder-matrix': 'Stakeholder Matrix',
        'workflow.step-report-inventory': 'Report Inventory',
        'workflow.step-kpi-definition': 'KPI Definition',
        'workflow.step-bi-python-toolkit': 'BI Python Toolkit',
        'workflow.step-architecture-fit': 'Architecture Fit',
        'workflow.step-impact-effort': 'Impact–Effort',
        'nav.dbt-dq-macro-generator': 'DG Macro Generator',
        'card.dbt-dq-macro-generator.title': 'DQ Macro Generator',
        'card.dbt-dq-macro-generator.description':
            'Runtime macros, dq_rule generic test, and SETUP_DQ.md for your dbt project.',
        'nav.dbt-dq-rules-generator': 'DQ Rules Generator',
        'card.dbt-dq-rules-generator.title': 'DQ Rules Generator',
        'card.dbt-dq-rules-generator.description':
            'meta.dq_rules in schema.yml — rules per column and model.',
        'nav.dbt-dq-history-generator': 'DQ History Generator',
        'card.dbt-dq-history-generator.title': 'DQ History Generator',
        'card.dbt-dq-history-generator.description':
            'dq_run_history, report views, and dq_collect_results runbook.',
        'nav.pii-unreviewed-gate-generator': 'PII Table Gate',
        'card.pii-unreviewed-gate-generator.title': 'PII Table Gate',
        'card.pii-unreviewed-gate-generator.description':
            'Identify and hide unreviewed models from roles.',
        'nav.governance-ai-sanitizer': 'AI Sanitizer',
        'nav.prompt-studio': 'Prompt Studio',
        'card.prompt-studio.title': 'Prompt Studio',
        'card.prompt-studio.description':
            'Professional AI prompts for ChatGPT, Claude, Gemini, Suno, Midjourney, and more.',
        'card.governance-ai-sanitizer.title': 'Governance AI Sanitizer',
        'card.governance-ai-sanitizer.description':
            'Sanitize prompt, copy outbound, restore AI response.',
        'nav.pii-policy-generator': 'PII Policy Generator',
        'card.pii-policy-generator.title': 'PII Policy Generator',
        'card.pii-policy-generator.description':
            'pii_details schema.yml example and secure model — target state after review.',
        'nav.schema-yml-editor': 'Schema YML Editor',
        'card.schema-yml-editor.title': 'Schema YML Editor',
        'card.schema-yml-editor.description':
            'Helper tool: edit individual schema.yml — not part of the setup chain.',
        'nav.meta-export-generator': 'Meta Export Generator',
        'card.meta-export-generator.title': 'Meta Export Generator',
        'card.meta-export-generator.description':
            'Copy-paste SQL/scripts for schemas, tables, columns and access meta — 10 platforms, no live connect.',
        'nav.pureview-scan-generator': 'PureView Scan Generator',
        'nav.pureview-classification-generator': 'PureView Classification Generator',
        'nav.pureview-glossary-generator': 'PureView Glossary Generator',
        'nav.pureview-data-product-generator': 'PureView Data Product Generator',
        'nav.stakeholder-matrix': 'Stakeholder Matrix',
        'card.stakeholder-matrix.title': 'Stakeholder & RACI Matrix',
        'card.stakeholder-matrix.description':
            'Export for the plan (nothing stored in the tool) — influence, interest, optional RACI.',
        'nav.report-inventory': 'Report Inventory',
        'card.report-inventory.title': 'Report Inventory Canvas',
        'card.report-inventory.description':
            'Build a report inventory and paste it into the sprint plan — nothing stored in the tool.',
        'nav.kpi-definition': 'KPI Definition',
        'card.kpi-definition.title': 'KPI Definition Card',
        'card.kpi-definition.description':
            'KPI definitions for plan deliverables — export only, nothing stored in the tool.',
        'nav.bi-python-toolkit': 'BI Python Toolkit',
        'card.bi-python-toolkit.title': 'BI Python Export Toolkit',
        'card.bi-python-toolkit.description':
            'Download Python files for Qlik KPI export, app/sheet inventory and BI formula inventories.',
        'nav.qlik-set-analysis-generator': 'Qlik Set Analysis Generator',
        'card.qlik-set-analysis-generator.title': 'Qlik Set Analysis Generator',
        'card.qlik-set-analysis-generator.description':
            'Generate child measures and variables from a base measure plus dimension-value CSV.',
        'nav.tableau-calculation-generator': 'Tableau Calculation Generator',
        'card.tableau-calculation-generator.title': 'Tableau Calculation Generator',
        'card.tableau-calculation-generator.description':
            'Generate calculated fields, LOD variants, and documentation CSV from base measures plus definitions.',
        'nav.powerbi-dax-generator': 'Power BI DAX Measure Generator',
        'card.powerbi-dax-generator.title': 'Power BI DAX Measure Generator',
        'card.powerbi-dax-generator.description':
            'Generate DAX measures, time-intelligence snippets, and documentation CSV from base measures plus definitions.',
        'tableauCalc.pageTitle': 'Tableau Calculation Generator',
        'tableauCalc.pageLead': 'Generate Tableau calculated fields, LOD variants and documentation CSV from base measures plus reusable definitions.',
        'tableauCalc.help.title': 'Tableau Calculation help',
        'tableauCalc.help.lead': 'Workflow help for catalog, definitions, base measures, hierarchy, and calculated fields.',
        'tableauCalc.help.show': 'Show help',
        'tableauCalc.help.hide': 'Hide help',
        'tableauCalc.help.productLink': 'Tableau product page',
        'tableauCalc.help.productHelpLink': 'Tableau help portal',
        'tableauCalc.help.calculatedFieldsLink': 'Calculated fields documentation',
        'tableauCalc.help.lodLink': 'LOD expressions documentation',
        'tableauCalc.help.summary': 'Quick logic',
        'tableauCalc.help.step1': 'Maintain the catalog: add dimensions, measures and available values from your Tableau workbook structure.',
        'tableauCalc.help.step2': 'Build definitions: a definition is a reusable condition such as Region DACH or Current Year.',
        'tableauCalc.help.step3': 'Maintain base measures: Sales, Costs, Margin etc. stay clean Tableau expressions.',
        'tableauCalc.help.step4': 'Copy output: in Tableau, create a calculated field from the Data pane and paste the formula.',
        'tableauCalc.help.catalogTitle': '1. Catalog',
        'tableauCalc.help.definitionsTitle': '2. Definition',
        'tableauCalc.help.baseTitle': '3. Base measures',
        'tableauCalc.help.outputTitle': '4. Output',
        'tableauCalc.help.whereTitle': 'Where in Tableau?',
        'tableauCalc.help.whereBody': 'Calculated fields go into a new calculated field in Tableau Desktop. Hierarchies are created in the Data pane by placing fields into a hierarchy in the desired order.',
        'tableauCalc.apps.save': 'Save app',
        'tableauCalc.apps.load': 'Load app',
        'tableauCalc.apps.delete': 'Delete app',
        'tableauCalc.apps.name': 'App name',
        'tableauCalc.apps.saved': 'Saved apps',
        'tableauCalc.apps.none': 'No app selected',
        'tableauCalc.apps.missingName': 'Please enter an app name.',
        'tableauCalc.apps.missingSelection': 'Please select a saved app.',
        'tableauCalc.apps.savedMessage': 'App “{name}” saved.',
        'tableauCalc.apps.deletedMessage': 'App “{name}” deleted.',
        'tableauCalc.workbench.title': 'Formula workbench',
        'tableauCalc.workbench.description': 'Catalog, base measures and definitions are separate work areas. The output below stays separate for copy and export.',
        'tableauCalc.workbench.controlTitle': 'Formula workbench',
        'tableauCalc.workbench.controlHint': 'Edit app context, catalog, base formulas, filters and hierarchy.',
        'tableauCalc.workbench.hide': 'Hide workbench',
        'tableauCalc.workbench.show': 'Show workbench',
        'tableauCalc.layout.catalog': 'Catalog',
        'tableauCalc.layout.formula': 'Formula',
        'tableauCalc.layout.builder': 'Filter & tree',
        'tableauCalc.layout.focusFormula': 'Focus formula',
        'tableauCalc.layout.showAll': 'Show all',
        'tableauCalc.tabs.definitions': 'Definitions',
        'tableauCalc.tabs.hierarchy': 'Hierarchy',
        'tableauCalc.tabs.extras': 'Extras',
        'tableauCalc.catalog.title': 'App catalog',
        'tableauCalc.catalog.fields': 'Fields',
        'tableauCalc.catalog.values': 'Dim values',
        'tableauCalc.catalog.rawData': 'Edit raw data',
        'tableauCalc.catalog.rawDataHint': 'Maintain fields and values as CSV or upload files.',
        'tableauCalc.catalog.fieldsUpload': 'Upload fields CSV',
        'tableauCalc.catalog.valuesUpload': 'Upload values CSV',
        'tableauCalc.catalog.tabs.dimensions': 'Dimensions',
        'tableauCalc.catalog.tabs.measures': 'Measures',
        'tableauCalc.catalog.hint': 'Drag or click chips. Open raw data with the button below.',
        'tableauCalc.modal.close': 'Close',
        'tableauCalc.definitions.title': 'Definitions',
        'tableauCalc.definitions.hint': 'Pick a dimension and values, save, and reuse as a condition.',
        'tableauCalc.definitions.dimension': 'Dimension',
        'tableauCalc.definitions.values': 'Values',
        'tableauCalc.definitions.name': 'Name',
        'tableauCalc.definitions.add': 'Save definition',
        'tableauCalc.definitions.raw': 'Definitions CSV',
        'tableauCalc.definitions.preview': 'Preview',
        'tableauCalc.definitions.dropTitle': 'Drop dimension here',
        'tableauCalc.definitions.dropHint': 'Drag a dimension from the catalog here to build a definition.',
        'tableauCalc.definitions.remove': 'Remove',
        'tableauCalc.formula.title': 'Formula',
        'tableauCalc.formula.hint': 'Build and edit the active Tableau base measure here. Blocks and fields are inserted at the cursor position.',
        'tableauCalc.formula.current': 'Current formula',
        'tableauCalc.formula.name': 'Measure name',
        'tableauCalc.formula.field': 'Field',
        'tableauCalc.formula.apply': 'Set base measure',
        'tableauCalc.history.undo': 'Undo',
        'tableauCalc.history.redo': 'Redo',
        'tableauCalc.functions.title': 'Formula functions',
        'tableauCalc.functions.hint': 'Click a block to extend the formula.',
        'tableauCalc.baseList.title': 'Base measures',
        'tableauCalc.baseList.hint': 'Load, save or create the active base measure.',
        'tableauCalc.baseList.show': 'Show',
        'tableauCalc.baseList.hide': 'Hide',
        'tableauCalc.baseList.active': 'Active base',
        'tableauCalc.baseList.load': 'Load base',
        'tableauCalc.baseList.saveCurrent': 'Save current',
        'tableauCalc.baseList.newBase': 'New base',
        'tableauCalc.baseList.csv': 'Base measures CSV',
        'tableauCalc.baseList.rawHint': 'CSV remains the source for saved base measures.',
        'tableauCalc.description.title': 'Description',
        'tableauCalc.description.hint': 'Description templates are used for the generated Tableau calculated fields.',
        'tableauCalc.description.de': 'Template DE',
        'tableauCalc.description.en': 'Template EN',
        'tableauCalc.extras.title': 'Extras',
        'tableauCalc.extras.hint': 'Generation mode and LOD dimensions for fallback from values.',
        'tableauCalc.extras.mode': 'Generation mode',
        'tableauCalc.extras.modeSingle': 'One child per dimension value',
        'tableauCalc.extras.modeCombined': 'Combine dimensions into variants',
        'tableauCalc.extras.modeDimensionGroup': 'One child per dimension with all values',
        'tableauCalc.extras.lodDimensions': 'LOD FIXED dimensions',
        'tableauCalc.extras.lodHint': 'Comma-separated. Empty uses each definition’s dimensions.',
        'tableauCalc.output.calcs': 'Calculated Fields',
        'tableauCalc.output.lod': 'LOD',
        'tableauCalc.output.hierarchy': 'Hierarchy',
        'tableauCalc.output.defs': 'Definitions',
        'tableauCalc.output.csv': 'CSV',
        'tableauCalc.copy': 'Copy',
        'tableauCalc.downloadCsv': 'Download CSV',
        'tableauCalc.downloadXlsx': 'Download Excel',
        'tableauCalc.saved': 'Definition saved.',
        'tableauCalc.hierarchy.title': 'Hierarchy',
        'tableauCalc.hierarchy.hint': 'Drop dimensions or edit indented levels. The child tree shows the base measure as root.',
        'tableauCalc.hierarchy.dropTitle': 'Drop hierarchy level here',
        'tableauCalc.hierarchy.dropHint': 'Drag dimensions here. They are appended to the hierarchy.',
        'tableauCalc.tree.title': 'Measure child tree',
        'tableauCalc.tree.hint': 'Drop dimensions on the base or a node to build nested paths.',
        'tableauCalc.tree.base': 'Base measure',
        'tableauCalc.tree.dropOnBase': 'Drop dimension on first level',
        'tableauCalc.tree.dropOnNode': 'Drop under {level}',
        'tableauCalc.tree.remove': 'Remove',
        'powerbiDax.pageTitle': 'Power BI DAX Measure Generator',
        'powerbiDax.pageLead': 'Generate DAX measures, time-intelligence snippets, hierarchy guidance and documentation CSV from base measures plus definitions.',
        'powerbiDax.help.title': 'Power BI DAX help',
        'powerbiDax.help.lead': 'Workflow help for catalog, definitions, base measures, hierarchy, and DAX measures.',
        'powerbiDax.help.show': 'Show help',
        'powerbiDax.help.hide': 'Hide help',
        'powerbiDax.help.productLink': 'Power BI product page',
        'powerbiDax.help.productHelpLink': 'Power BI help portal',
        'powerbiDax.help.calculateLink': 'CALCULATE documentation',
        'powerbiDax.help.measuresLink': 'Measures documentation',
        'powerbiDax.help.summary': 'Quick logic',
        'powerbiDax.help.step1': 'Maintain the catalog: add tables, columns, measures and values from your Power BI model.',
        'powerbiDax.help.step2': 'Build definitions: a definition becomes a DAX filter inside CALCULATE.',
        'powerbiDax.help.step3': 'Keep base measures clean: Sales, Costs, Margin stay independent measures.',
        'powerbiDax.help.step4': 'Copy output: create a new measure in Power BI Desktop and paste the DAX.',
        'powerbiDax.help.catalogTitle': '1. Catalog',
        'powerbiDax.help.definitionsTitle': '2. Definition',
        'powerbiDax.help.baseTitle': '3. Base measures',
        'powerbiDax.help.outputTitle': '4. Output',
        'powerbiDax.help.whereTitle': 'Where in Power BI?',
        'powerbiDax.help.whereBody': 'DAX measures are created as new measures in Power BI Desktop. Hierarchies are created in the Data pane or Model view by dragging columns into a hierarchy.',
        'powerbiDax.apps.save': 'Save app',
        'powerbiDax.apps.load': 'Load app',
        'powerbiDax.apps.delete': 'Delete app',
        'powerbiDax.apps.name': 'App name',
        'powerbiDax.apps.saved': 'Saved apps',
        'powerbiDax.apps.none': 'No app selected',
        'powerbiDax.apps.missingName': 'Please enter an app name.',
        'powerbiDax.apps.missingSelection': 'Please select a saved app.',
        'powerbiDax.apps.savedMessage': 'App “{name}” saved.',
        'powerbiDax.apps.deletedMessage': 'App “{name}” deleted.',
        'powerbiDax.workbench.title': 'Formula workbench',
        'powerbiDax.workbench.description': 'Catalog, base measures and definitions are separate work areas. The output below stays separate for copy and export.',
        'powerbiDax.workbench.controlTitle': 'Formula workbench',
        'powerbiDax.workbench.controlHint': 'Edit app context, catalog, base formulas, filters and hierarchy.',
        'powerbiDax.workbench.hide': 'Hide workbench',
        'powerbiDax.workbench.show': 'Show workbench',
        'powerbiDax.layout.catalog': 'Catalog',
        'powerbiDax.layout.formula': 'Formula',
        'powerbiDax.layout.builder': 'Filter & tree',
        'powerbiDax.layout.focusFormula': 'Focus formula',
        'powerbiDax.layout.showAll': 'Show all',
        'powerbiDax.tabs.definitions': 'Definitions',
        'powerbiDax.tabs.hierarchy': 'Hierarchy',
        'powerbiDax.tabs.extras': 'Extras',
        'powerbiDax.catalog.title': 'Model catalog',
        'powerbiDax.catalog.fields': 'Fields',
        'powerbiDax.catalog.values': 'Dim values',
        'powerbiDax.catalog.rawData': 'Edit raw data',
        'powerbiDax.catalog.rawDataHint': 'Maintain fields and values as CSV or upload files.',
        'powerbiDax.catalog.fieldsUpload': 'Upload fields CSV',
        'powerbiDax.catalog.valuesUpload': 'Upload values CSV',
        'powerbiDax.catalog.tabs.dimensions': 'Dimensions',
        'powerbiDax.catalog.tabs.measures': 'Measures',
        'powerbiDax.catalog.hint': 'Drag or click chips. Open raw data with the button below.',
        'powerbiDax.modal.close': 'Close',
        'powerbiDax.definitions.title': 'Definitions',
        'powerbiDax.definitions.hint': 'Pick a column and values, save, and reuse as a CALCULATE filter.',
        'powerbiDax.definitions.column': 'Column',
        'powerbiDax.definitions.values': 'Values',
        'powerbiDax.definitions.name': 'Name',
        'powerbiDax.definitions.add': 'Save definition',
        'powerbiDax.definitions.raw': 'Definitions CSV',
        'powerbiDax.definitions.preview': 'DAX filter',
        'powerbiDax.definitions.dropTitle': 'Drop column here',
        'powerbiDax.definitions.dropHint': 'Drag a dimension from the catalog here to build a CALCULATE filter.',
        'powerbiDax.definitions.remove': 'Remove',
        'powerbiDax.formula.title': 'Formula',
        'powerbiDax.formula.hint': 'Build and edit the active DAX base measure here. Blocks and fields are inserted at the cursor position.',
        'powerbiDax.formula.current': 'Current formula',
        'powerbiDax.formula.name': 'Measure name',
        'powerbiDax.formula.field': 'Column',
        'powerbiDax.formula.apply': 'Set base measure',
        'powerbiDax.history.undo': 'Undo',
        'powerbiDax.history.redo': 'Redo',
        'powerbiDax.functions.title': 'DAX functions',
        'powerbiDax.functions.hint': 'Click a block to extend the formula.',
        'powerbiDax.baseList.title': 'Base measures',
        'powerbiDax.baseList.hint': 'Load, save or create the active base measure.',
        'powerbiDax.baseList.show': 'Show',
        'powerbiDax.baseList.hide': 'Hide',
        'powerbiDax.baseList.active': 'Active base',
        'powerbiDax.baseList.load': 'Load base',
        'powerbiDax.baseList.saveCurrent': 'Save current',
        'powerbiDax.baseList.newBase': 'New base',
        'powerbiDax.baseList.csv': 'Base measures CSV',
        'powerbiDax.baseList.rawHint': 'CSV remains the source for saved base measures.',
        'powerbiDax.description.title': 'Description',
        'powerbiDax.description.hint': 'Description templates are used for the generated Power BI measures.',
        'powerbiDax.description.de': 'Template DE',
        'powerbiDax.description.en': 'Template EN',
        'powerbiDax.extras.title': 'Extras',
        'powerbiDax.extras.hint': 'Generation mode and date column for time intelligence.',
        'powerbiDax.extras.mode': 'Generation mode',
        'powerbiDax.extras.modeSingle': 'One child per dimension value',
        'powerbiDax.extras.modeCombined': 'Combine dimensions into variants',
        'powerbiDax.extras.modeDimensionGroup': 'One child per dimension with all values',
        'powerbiDax.extras.dateColumn': 'Date column (time intelligence)',
        'powerbiDax.extras.dateHint': "DAX column for YTD/PY/YoY, e.g. 'Date'[Date].",
        'powerbiDax.output.measures': 'Measures',
        'powerbiDax.output.time': 'Time',
        'powerbiDax.output.hierarchy': 'Hierarchy',
        'powerbiDax.output.defs': 'Definitions',
        'powerbiDax.output.csv': 'CSV',
        'powerbiDax.copy': 'Copy',
        'powerbiDax.downloadCsv': 'Download CSV',
        'powerbiDax.downloadXlsx': 'Download Excel',
        'powerbiDax.saved': 'Definition saved.',
        'powerbiDax.hierarchy.title': 'Hierarchy',
        'powerbiDax.hierarchy.hint': 'Drop dimensions or edit indented levels. The child tree shows the base measure as root.',
        'powerbiDax.hierarchy.dropTitle': 'Drop hierarchy level here',
        'powerbiDax.hierarchy.dropHint': 'Drag dimensions here. They are appended to the hierarchy.',
        'powerbiDax.tree.title': 'Measure child tree',
        'powerbiDax.tree.hint': 'Drop dimensions on the base or a node to build nested paths.',
        'powerbiDax.tree.base': 'Base measure',
        'powerbiDax.tree.dropOnBase': 'Drop dimension on first level',
        'powerbiDax.tree.dropOnNode': 'Drop under {level}',
        'powerbiDax.tree.remove': 'Remove',
        'biPythonToolkit.pageTitle': 'BI Python Export Toolkit',
        'biPythonToolkit.pageLead':
            'Download Python tools, run them locally, and use the outputs as CSV, Markdown or plan JSON.',
        'biPythonToolkit.howto.intro':
            'The tools run on your machine. This web tool does not store API keys and does not connect directly to Qlik, Power BI or Tableau.',
        'biPythonToolkit.howto.step1': 'Install Python and create a project folder with a virtual environment.',
        'biPythonToolkit.howto.step2': 'Download the required Python files here and place them in the project folder.',
        'biPythonToolkit.howto.step3': 'Run exports locally and use CSV, Markdown or plan JSON in the Sprint Planner.',
        'biPythonToolkit.howto.tip':
            'Qlik sheet extraction additionally needs websocket-client. Without --include-sheets, the Python standard library is enough.',
        'biPythonToolkit.setupStoryLink':
            'Python setup guide: install Python, initialize a folder, run the exports',
        'biPythonToolkit.openSetupStory': 'Python Setup Story',
        'biPythonToolkit.downloads.title': 'Downloads',
        'biPythonToolkit.downloads.lead': 'Save the files locally in your Python project folder.',
        'biPythonToolkit.download.kpi': 'KPI export script',
        'biPythonToolkit.download.inventory': 'Qlik app inventory script',
        'biPythonToolkit.download.readme': 'Download README',
        'biPythonToolkit.commands.title': 'Example commands',
        'biPythonToolkit.commands.lead': 'Adjust filenames, tenant and API key for your environment.',
        'biPythonToolkit.scripts.title': 'View and copy scripts',
        'biPythonToolkit.scripts.lead':
            'Review the Python files directly here, copy them, or download them as files.',
        'biPythonToolkit.copyScript': 'Copy script',
        'biPythonToolkit.downloadScript': 'Download',
        'nav.architecture-fit': 'Architecture Fit',
        'card.architecture-fit.title': 'Architecture Fit Checklist',
        'card.architecture-fit.description':
            'Architecture diagnosis as Markdown for the plan — persistence only in the Sprint Planner.',
        'nav.impact-effort': 'Impact–Effort',
        'card.impact-effort.title': 'Impact–Effort Prioritizer',
        'card.impact-effort.description':
            'Export a prioritization matrix for the plan — nothing stored in the tool.',
        'card.binom-ngx.title': 'binom-ngx',
        'card.binom-ngx.description': 'Angular UI libraries, SDKs, and interactive documentation.',
        'card.binom-ngx.meta': 'Docs & Demos',
        'nav.stories': 'Stories',
        'nav.storiesOverview': 'Overview',
        'nav.storiesMore': '+ {{count}} more stories',
        'nav.sprintPlanner': 'Sprint Planner',
        'nav.sprintPlannerPlans': 'My plans',
        'nav.sprintPlannerTemplates': 'Templates',
        'nav.sprintPlannerPeople': 'Teams & people',
        'nav.account': 'Account',
        'nav.accountProfile': 'Profile',
        'nav.accountUsers': 'Users',
        'nav.accountTeams': 'Teams',
        'nav.accountStoryAccess': 'Story access',
        'nav.accountSignIn': 'Sign in',
        'accounts.signIn': 'Sign in',
        'accounts.signOut': 'Sign out',
        'accounts.signedInAs': 'Signed in',
        'accounts.loginTitle': 'Sign in',
        'accounts.loginLead': 'Local file-based accounts — passwords are stored hashed only.',
        'accounts.email': 'Email',
        'accounts.password': 'Password',
        'accounts.profileTitle': 'Account',
        'accounts.displayName': 'Display name',
        'accounts.currentPassword': 'Current password (to change password)',
        'accounts.newPassword': 'New password',
        'accounts.confirmPassword': 'Confirm new password',
        'accounts.save': 'Save',
        'accounts.saved': 'Saved.',
        'accounts.usersTitle': 'Users',
        'accounts.usersLead': 'Managed in local users.json — passwords are hashed only.',
        'accounts.addUser': 'Add user',
        'accounts.editUser': 'Edit user',
        'accounts.existingUsers': 'Users',
        'accounts.noUsers': 'No users yet.',
        'accounts.backToUsers': 'Back to users',
        'accounts.teamsTitle': 'Teams',
        'accounts.teamsLead': 'Create and edit teams one at a time. Membership syncs to user accounts.',
        'accounts.addTeam': 'Add team',
        'accounts.editTeam': 'Edit team',
        'accounts.existingTeams': 'Teams',
        'accounts.noTeams': 'No teams yet.',
        'accounts.backToTeams': 'Back to teams',
        'accounts.manageUsers': 'Manage users',
        'accounts.manageTeams': 'Manage teams',
        'accounts.edit': 'Edit',
        'accounts.cancel': 'Cancel',
        'accounts.members': 'Members',
        'accounts.role': 'Role',
        'accounts.role.member': 'Member',
        'accounts.role.manager': 'Manager',
        'accounts.role.ceo': 'CEO',
        'accounts.roleHint': 'Managers often own team plans — anyone can still create their own plan.',
        'accounts.teams': 'Teams',
        'accounts.filterMembers': 'Filter members…',
        'accounts.filterTeams': 'Filter teams…',
        'accounts.noItems': 'No items yet.',
        'accounts.nameDe': 'Name (DE)',
        'accounts.nameEn': 'Name (EN)',
        'accounts.descriptionDe': 'Description (DE)',
        'accounts.descriptionEn': 'Description (EN)',
        'accounts.shortName': 'Trigram',
        'accounts.shortNameHint': '2–3 letters (A–Z), optional.',
        'accounts.color': 'Color',
        'accounts.colorHint': 'Solid fills or white chips with solid, dotted, or dashed colored borders.',
        'accounts.avatarIcon': 'Avatar icon',
        'accounts.avatarIconHint': 'Optional. When set, the icon replaces the trigram on chips.',
        'accounts.teamAvatarIconHint': 'Optional. Icon alone, or icon plus a 2–3 letter trigram. Team chips stay squared.',
        'accounts.avatarTrigram': 'Trigram',
        'accounts.archived': 'Archived',
        'accounts.inactive': 'Inactive',
        'accounts.active': 'Active',
        'accounts.canManageUsers': 'Can manage users',
        'accounts.canManageTeams': 'Can manage teams',
        'accounts.newPasswordOptional': 'New password (optional)',
        'accounts.deleteTeam': 'Delete team',
        'accounts.deleteUser': 'Delete user',
        'accounts.memberCount': '{{count}} members',
        'accounts.flash.teamCreated': 'Team created.',
        'accounts.flash.teamUpdated': 'Team saved.',
        'accounts.flash.teamDeleted': 'Team deleted.',
        'accounts.flash.userCreated': 'User created.',
        'accounts.flash.userCreatedInvited': 'User created — invitation email sent.',
        'accounts.flash.userCreatedInviteFailed': 'User created, but email failed. Copy the password below.',
        'accounts.flash.userCreatedWithPassword': 'User created. Copy the temporary password below.',
        'accounts.flash.userUpdated': 'User saved.',
        'accounts.flash.userUpdatedInvited': 'User saved — new password emailed.',
        'accounts.flash.userUpdatedInviteFailed': 'Saved, but email failed. Copy the password below.',
        'accounts.flash.userUpdatedWithPassword': 'Saved. Copy the temporary password below.',
        'accounts.flash.userDeleted': 'User deleted.',
        'accounts.flash.mustChangePassword': 'Please change your password now.',
        'accounts.flash.temporaryPasswordLabel': 'Temporary password (copy now):',
        'accounts.generatePassword': 'Generate temporary password',
        'accounts.sendInvite': 'Send invitation email with password',
        'accounts.mustChangePassword': 'Must change password on first login',
        'accounts.mustChangePasswordLead': 'Please set a new password before using the tools.',
        'accounts.inviteHint': 'Default: generate a password, email it, and require a change after login.',
        'accounts.resetGeneratePassword': 'Reset with generated temporary password',
        'accounts.resendInvite': 'Email the new password to the user',
        'accounts.passwordManual': 'Password (if not generated)',
        'accounts.addUserSubmit': 'Add user',
        'accounts.temporaryPassword': 'Temporary password',
        'accounts.pendingPasswordChange': 'Password change pending',
        'accounts.storyAclTitle': 'Story access',
        'accounts.storyAclLead': 'Playbooks are public by default. Restrict individual stories to selected users or teams.',
        'accounts.storyAclStories': 'Stories',
        'accounts.editStoryAcl': 'Edit story access',
        'accounts.backToStoryAcl': 'Back to story access',
        'accounts.filterStories': 'Filter stories…',
        'accounts.filterUsers': 'Filter users…',
        'accounts.noStories': 'No stories found.',
        'accounts.visibility': 'Visibility',
        'accounts.visibility.public': 'Public',
        'accounts.visibility.restricted': 'Restricted',
        'accounts.allowedUsers': 'Allowed users',
        'accounts.allowedTeams': 'Allowed teams',
        'accounts.storyAclRestrictedNote': 'User and team lists apply only when visibility is restricted.',
        'accounts.flash.aclUpdated': 'Story access saved.',
        'playbooks.indexTitle': 'Stories',
        'playbooks.indexLead': 'Step-by-step governance guides — from idea to implementation.',
        'playbooks.empty': 'No stories published yet.',
        'playbooks.category': 'Category',
        'playbooks.author': 'Author',
        'playbooks.readingTime': 'Reading time',
        'playbooks.updated': 'Updated',
        'playbooks.tags': 'Tags',
        'playbooks.views': 'Views',
        'playbooks.like': 'Like',
        'playbooks.share': 'Share',
        'playbooks.shareCopied': 'Link copied',
        'playbooks.shareFacebook': 'Facebook',
        'playbooks.shareLinkedIn': 'LinkedIn',
        'playbooks.shareXing': 'Xing',
        'playbooks.shareWhatsApp': 'WhatsApp',
        'playbooks.shareX': 'X',
        'playbooks.shareEmail': 'Email',
        'playbooks.shareCopy': 'Copy link',
        'playbooks.tocToggle': 'On this page',
        'playbooks.tocTitle': 'On this page',
        'playbooks.lightbox.close': 'Close',
        'playbooks.lightbox.prev': 'Previous image',
        'playbooks.lightbox.next': 'Next image',
        'playbooks.previous': 'Previous',
        'playbooks.next': 'Next',
        'playbooks.downloadStarter': 'Clone Git repo',
        'playbooks.repositoryLink': 'GitHub repository',
        'playbooks.offline.save': 'Save offline',
        'playbooks.offline.update': 'Update offline copy',
        'playbooks.offline.remove': 'Remove offline',
        'playbooks.offline.saveAll': 'Save all offline',
        'playbooks.offline.saveAllShort': 'Offline',
        'playbooks.offline.removeAll': 'Remove offline copies ({count})',
        'playbooks.offline.removeAllShort': 'Remove',
        'playbooks.offline.badge': 'Offline',
        'playbooks.offline.saved': 'Available offline ({size})',
        'playbooks.offline.allSize': 'Estimated size: {size}',
        'playbooks.offline.preparing': 'Preparing offline pack…',
        'playbooks.offline.downloading': 'Downloading ({size})…',
        'playbooks.offline.progress': 'Download {pct}%',
        'playbooks.offline.progressStory': 'Saving: {slug}',
        'playbooks.offline.done': 'Story is available offline.',
        'playbooks.offline.doneAll': 'All available stories are saved offline.',
        'playbooks.offline.removed': 'Offline copy removed.',
        'playbooks.offline.removedAll': 'All offline copies removed.',
        'playbooks.offline.error': 'Could not save offline. Try again while online.',
        'playbooks.offline.quota': 'Not enough storage. Save individual stories instead of all.',
        'playbooks.offline.confirmAll': 'Save all stories offline? Estimated size: {size}. External videos stay online-only.',
        'playbooks.offline.confirmRemoveAll': 'Remove all offline copies?',
        'playbooks.offline.banner': 'You are offline. Saved stories remain readable.',
        'playbooks.offline.unsupported': 'Offline saving needs a secure context (HTTPS or localhost) and a service worker.',
        'playbooks.openStory': 'Open story',
        'legal.impressum.title': 'Legal Notice',
        'legal.impressum.summary': 'Information pursuant to § 5 TMG (German Telemedia Act)',
        'legal.impressum.provider.title': 'Service provider',
        'legal.impressum.provider.body': 'Thomas Lindackers\nVollckmarstr 28\n45219 Essen\nGermany\n\nEmail: support@governance.binom.net',
        'legal.impressum.responsible.title': 'Responsible for content (§ 55 Abs. 2 RStV)',
        'legal.impressum.responsible.body': 'Thomas Lindackers\nVollckmarstr 28\n45219 Essen',
    },
};

/**
 * @param {string} key
 * @param {ToolsLocale} [locale]
 */
export function getShellLabel(key, locale = getLocale()) {
    return shellLabels[locale]?.[key] ?? shellLabels.en?.[key] ?? key;
}

/** @param {ToolsLocale} locale @param {boolean} isDark */
export function applyThemeToggleAria(locale, isDark) {
    const labels = shellLabels[locale];
    const toggle = document.querySelector('[data-theme-toggle]');
    if (!toggle) return;

    const key = isDark ? 'theme.toggleToLight' : 'theme.toggleToDark';
    if (labels[key]) {
        toggle.setAttribute('aria-label', labels[key]);
        toggle.setAttribute('title', labels[key]);
    }
}

/** @param {ToolsLocale} locale */
export function applyShellLabels(locale) {
    const labels = shellLabels[locale];

    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        const count = el.getAttribute('data-i18n-count');
        if (key && labels[key]) {
            el.textContent = count !== null
                ? labels[key].replace(/\{\{count\}\}/g, count)
                : labels[key];
        }
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach((el) => {
        const key = el.getAttribute('data-i18n-placeholder');
        if (key && labels[key] && 'placeholder' in el) {
            el.placeholder = labels[key];
        }
    });

    document.querySelectorAll('[data-i18n-nav]').forEach((el) => {
        const id = el.getAttribute('data-i18n-nav');
        const key = `nav.${id}`;
        if (labels[key]) {
            el.textContent = labels[key];
        }
    });

    document.querySelectorAll('[data-sidenav-bilingual]').forEach((el) => {
        const text = el.getAttribute(locale === 'de' ? 'data-text-de' : 'data-text-en');
        if (text) {
            el.textContent = text;
        }
    });

    document.querySelectorAll('[data-i18n-aria]').forEach((el) => {
        const key = el.getAttribute('data-i18n-aria');
        if (key && labels[key]) {
            el.setAttribute('aria-label', labels[key]);
            el.setAttribute('title', labels[key]);
        }
    });

    document.querySelectorAll('[data-card-id]').forEach((el) => {
        const id = el.getAttribute('data-card-id');
        const title = el.querySelector('.tools-card__title');
        const desc = el.querySelector('.tools-card__desc');
        const meta = el.querySelector('.tools-card__meta');
        const titleKey = `card.${id}.title`;
        const descKey = `card.${id}.description`;
        const metaKey = `card.${id}.meta`;
        if (title && labels[titleKey]) title.textContent = labels[titleKey];
        if (desc && labels[descKey]) desc.textContent = labels[descKey];
        if (meta && labels[metaKey]) meta.textContent = labels[metaKey];
    });
}

export function initLocaleControls() {
    const locale = getLocale();
    const fromPath = getLocaleFromPath();

    if (fromPath) {
        localStorage.setItem(STORAGE_KEY, fromPath);
    }

    applyLocaleToDocument(locale);
    applyShellLabels(locale);

    document.querySelectorAll('[data-locale]').forEach((btn) => {
        const value = btn.getAttribute('data-locale');
        if (value !== 'de' && value !== 'en') return;

        const isActive = value === locale;
        btn.classList.toggle('tools-btn--active', isActive);
        btn.setAttribute('aria-pressed', String(isActive));

        btn.addEventListener('click', () => {
            if (value === locale) {
                return;
            }

            const targetUrl = btn.getAttribute('data-locale-url');
            setLocale(/** @type {ToolsLocale} */ (value), targetUrl || undefined);
        });
    });

    window.addEventListener('binom-tools:locale', (event) => {
        const detail = /** @type {CustomEvent<{ locale: ToolsLocale }>} */ (event).detail;
        applyShellLabels(detail?.locale ?? getLocale());
    });
}

export function initSidebarToggle() {
    const shell = document.getElementById('tools-shell');
    const toggle = document.querySelector('[data-tools-sidebar-toggle]');
    const backdrop = document.querySelector('[data-tools-sidebar-backdrop]');

    if (!shell || !toggle) return;

    const close = () => {
        shell.classList.remove('tools-shell--sidebar-open');
        toggle.setAttribute('aria-expanded', 'false');
        if (backdrop) backdrop.hidden = true;
    };

    const open = () => {
        shell.classList.add('tools-shell--sidebar-open');
        toggle.setAttribute('aria-expanded', 'true');
        if (backdrop) backdrop.hidden = false;
    };

    toggle.addEventListener('click', () => {
        if (shell.classList.contains('tools-shell--sidebar-open')) {
            close();
        } else {
            open();
        }
    });

    backdrop?.addEventListener('click', close);
    window.addEventListener('resize', () => {
        const focus = document.documentElement.dataset.playbookFocus === 'true';
        if (window.innerWidth > 768 && !focus) {
            close();
        }
    });
}
