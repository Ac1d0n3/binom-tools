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
        'nav.tools': 'Tools',
        'nav.home': 'Startseite',
        'nav.overview': 'Übersicht',
        'nav.openMenu': 'Navigation öffnen',
        'nav.closeMenu': 'Navigation schließen',
        'footer.copyright': `© ${new Date().getFullYear()} Binom Governance`,
        'footer.website': 'binom.net',
        'footer.binomNgx': 'binom-ngx Docs',
        'footer.impressum': 'Impressum',
        'footer.privacy': 'Datenschutz',
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
            'Governance Help Hub mit Markdown-Stories, interaktiven Tools und i18n — klonbar und ohne CMS.',
        'home.hero.headline': 'Governance Help Hub',
        'home.hero.headlineAccent': 'für Data-, BI- und Analytics-Teams.',
        'home.hero.tagline':
            'Markdown-Playbooks, interaktive Referenz-Tools und ein schlanker Stack ohne CMS — Governance-Wissen versionierbar wie Code.',
        'home.hero.notice':
            'Klonbar als Starter-Template: eigene Stories, Tools und Branding für euren internen Help Hub.',
        'home.hero.ctaWorkflows': 'Interaktive Tools',
        'home.hero.ctaSdk': 'binom-ngx SDKs',
        'home.hero.attribution': 'Design-Konzept von',
        'home.workflowsTitle': 'Workflow-Beispiele',
        'home.workflowsLead':
            'Interaktive Referenz-Workflows — Schritt für Schritt, copy-paste-fähig.',
        'home.toolsTitle': 'Tools',
        'home.storiesTitle': 'Stories',
        'home.storiesLead':
            'Schritt-für-Schritt-Governance-Guides — von der Idee bis zur Umsetzung.',
        'home.viewAllTotal': 'insgesamt',
        'home.viewAllTools.title': 'Alle Tools',
        'home.viewAllTools.description': 'Zur Übersicht mit Suche und allen Workflow-Beispielen.',
        'home.viewAllStories.title': 'Alle Stories',
        'home.viewAllStories.description': 'Zur Übersicht mit Suche, Tags und allen Governance-Guides.',
        'home.ecosystemTitle': 'Ökosystem',
        'tools.overviewTitle': 'Tools',
        'tools.overviewLead': 'Interaktive Referenz-Workflows — Schritt für Schritt, copy-paste-fähig.',
        'overview.searchLabel': 'Suchen',
        'overview.searchPlaceholder': 'Suchen…',
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
        'card.binom-ngx.title': 'binom-ngx',
        'card.binom-ngx.description': 'Angular UI Libraries, SDKs und interaktive Dokumentation.',
        'card.binom-ngx.meta': 'Docs & Demos',
        'nav.stories': 'Stories',
        'nav.storiesOverview': 'Übersicht',
        'playbooks.indexTitle': 'Stories',
        'playbooks.indexLead': 'Schritt-für-Schritt-Governance-Guides — von der Idee bis zur Umsetzung.',
        'playbooks.empty': 'Noch keine Stories veröffentlicht.',
        'playbooks.category': 'Kategorie',
        'playbooks.author': 'Autor',
        'playbooks.readingTime': 'Lesezeit',
        'playbooks.updated': 'Aktualisiert',
        'playbooks.tags': 'Tags',
        'playbooks.tocToggle': 'Auf dieser Seite',
        'playbooks.tocTitle': 'Auf dieser Seite',
        'playbooks.previous': 'Zurück',
        'playbooks.next': 'Weiter',
        'playbooks.downloadStarter': 'Git-Repo klonen',
        'playbooks.repositoryLink': 'GitHub-Repository',
        'legal.impressum.title': 'Impressum',
        'legal.impressum.summary': 'Angaben gemäß § 5 TMG',
        'legal.impressum.provider.title': 'Anbieter',
        'legal.impressum.provider.body': 'Thomas Lindackers\nVollckmarstr 28\n45219 Essen\nDeutschland\n\nE-Mail: support@governance.binom.net',
        'legal.impressum.responsible.title': 'Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV',
        'legal.impressum.responsible.body': 'Thomas Lindackers\nVollckmarstr 28\n45219 Essen',
    },
    en: {
        'nav.tools': 'Tools',
        'nav.home': 'Home',
        'nav.overview': 'Overview',
        'nav.openMenu': 'Open navigation',
        'nav.closeMenu': 'Close navigation',
        'footer.copyright': `© ${new Date().getFullYear()} Binom Governance`,
        'footer.website': 'binom.net',
        'footer.binomNgx': 'binom-ngx Docs',
        'footer.impressum': 'Legal Notice',
        'footer.privacy': 'Privacy',
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
            'Governance help hub with Markdown stories, interactive tools, and i18n — cloneable and CMS-free.',
        'home.hero.headline': 'Governance help hub',
        'home.hero.headlineAccent': 'for data, BI, and analytics teams.',
        'home.hero.tagline':
            'Markdown playbooks, interactive reference tools, and a lean stack without a CMS — version governance knowledge like code.',
        'home.hero.notice':
            'Clone as a starter template: your own stories, tools, and branding for an internal help hub.',
        'home.hero.ctaWorkflows': 'Explore tools',
        'home.hero.ctaSdk': 'binom-ngx SDKs',
        'home.hero.attribution': 'Design concept by',
        'home.workflowsTitle': 'Workflow examples',
        'home.workflowsLead':
            'Interactive reference workflows — step by step, copy-paste ready.',
        'home.toolsTitle': 'Tools',
        'home.storiesTitle': 'Stories',
        'home.storiesLead':
            'Step-by-step governance guides — from idea to implementation.',
        'home.viewAllTotal': 'total',
        'home.viewAllTools.title': 'All tools',
        'home.viewAllTools.description': 'Go to the overview with search and all workflow examples.',
        'home.viewAllStories.title': 'All stories',
        'home.viewAllStories.description': 'Go to the overview with search, tags, and all governance guides.',
        'home.ecosystemTitle': 'Ecosystem',
        'tools.overviewTitle': 'Tools',
        'tools.overviewLead': 'Interactive reference workflows — step by step, copy-paste ready.',
        'overview.searchLabel': 'Search',
        'overview.searchPlaceholder': 'Search…',
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
        'card.binom-ngx.title': 'binom-ngx',
        'card.binom-ngx.description': 'Angular UI libraries, SDKs, and interactive documentation.',
        'card.binom-ngx.meta': 'Docs & Demos',
        'nav.stories': 'Stories',
        'nav.storiesOverview': 'Overview',
        'playbooks.indexTitle': 'Stories',
        'playbooks.indexLead': 'Step-by-step governance guides — from idea to implementation.',
        'playbooks.empty': 'No stories published yet.',
        'playbooks.category': 'Category',
        'playbooks.author': 'Author',
        'playbooks.readingTime': 'Reading time',
        'playbooks.updated': 'Updated',
        'playbooks.tags': 'Tags',
        'playbooks.tocToggle': 'On this page',
        'playbooks.tocTitle': 'On this page',
        'playbooks.previous': 'Previous',
        'playbooks.next': 'Next',
        'playbooks.downloadStarter': 'Clone Git repo',
        'playbooks.repositoryLink': 'GitHub repository',
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
        if (key && labels[key]) {
            el.textContent = labels[key];
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
        applyShellLabels(detail.locale);
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
        if (window.innerWidth > 768) close();
    });
}
