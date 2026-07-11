const STORAGE_KEY = 'binom-tools-locale';
const DEFAULT_LOCALE = 'de';

/** @typedef {'de' | 'en'} ToolsLocale */

/** @returns {ToolsLocale} */
export function getLocale() {
    const stored = localStorage.getItem(STORAGE_KEY);
    return stored === 'en' || stored === 'de' ? stored : DEFAULT_LOCALE;
}

/** @param {ToolsLocale} locale */
export function setLocale(locale) {
    localStorage.setItem(STORAGE_KEY, locale);
    document.documentElement.lang = locale === 'de' ? 'de' : 'en';
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
        'overview.tagAll': 'Alle',
        'overview.noResults': 'Keine Treffer für deine Suche.',
        'theme.light': 'Hell',
        'theme.dark': 'Dunkel',
        'theme.toggleToDark': 'Dunkelmodus aktivieren',
        'theme.toggleToLight': 'Hellmodus aktivieren',
        'card.exampleBadge': 'Beispiel',
        'nav.dbt-governance-macro-generator': 'Governance-Makro-Generator',
        'nav.pii-recommend-generator': 'PII-Empfehlungs-Generator',
        'card.dbt-governance-macro-generator.title': 'Governance Macro Generator',
        'card.dbt-governance-macro-generator.description':
            'Schritt 1/4: Laufzeit-Makros, Generic Tests und SETUP.md für dein dbt-Projekt.',
        'card.pii-recommend-generator.title': 'PII Recommend Generator',
        'card.pii-recommend-generator.description':
            'Schritt 4/4: Name- und Content-Audit, Heuristik-Regeln und pii_recommend schema.yml.',
        'workflow.setupLabel': 'dbt PII Governance Einrichtung',
        'workflow.stepPrefix': 'Schritt',
        'workflow.prev': '← Zurück',
        'workflow.next': 'Weiter →',
        'workflow.step-dbt-governance-macro-generator': 'Governance-Makro-Generator',
        'workflow.step-pii-policy-generator': 'dbt-Policy-Generator',
        'workflow.step-pii-unreviewed-gate-generator': 'Table Gate',
        'workflow.step-pii-recommend-generator': 'PII-Empfehlungs-Generator',
        'nav.pii-unreviewed-gate-generator': 'Unreviewed Table Gate',
        'card.pii-unreviewed-gate-generator.title': 'Unreviewed Table Gate Generator',
        'card.pii-unreviewed-gate-generator.description':
            'Schritt 3/4: Ungeprüfte Models identifizieren und für Rollen verstecken.',
        'nav.governance-ai-sanitizer': 'Governance-AI-Sanitizer',
        'card.governance-ai-sanitizer.title': 'Governance AI Sanitizer',
        'card.governance-ai-sanitizer.description':
            'Referenz-Beispiel: Prompt sanitisieren, Outbound kopieren, KI-Antwort wiederherstellen.',
        'nav.pii-policy-generator': 'dbt-Policy-Generator',
        'card.pii-policy-generator.title': 'DBT Policy Generator',
        'card.pii-policy-generator.description':
            'Schritt 2/4: pii_details schema.yml-Beispiel und Secure Model — Zielzustand nach Review.',
        'nav.schema-yml-editor': 'Schema-YML-Editor',
        'card.schema-yml-editor.title': 'Schema YML Editor',
        'card.schema-yml-editor.description':
            'Hilfs-Tool: einzelne schema.yml bearbeiten — nicht Teil der Einrichtungs-Kette.',
        'card.binom-ngx.title': 'binom-ngx',
        'card.binom-ngx.description': 'Angular UI Libraries, SDKs und interaktive Dokumentation.',
        'card.binom-ngx.meta': 'Docs & Demos',
        'nav.playbooks': 'Playbooks',
        'nav.playbooksOverview': 'Übersicht',
        'playbooks.indexTitle': 'Governance Playbooks',
        'playbooks.indexLead': 'Schritt-für-Schritt-Governance-Guides — von der Idee bis zur Umsetzung.',
        'playbooks.empty': 'Noch keine Playbooks veröffentlicht.',
        'playbooks.category': 'Kategorie',
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
        'overview.tagAll': 'All',
        'overview.noResults': 'No matches for your search.',
        'theme.light': 'Light',
        'theme.dark': 'Dark',
        'theme.toggleToDark': 'Activate dark mode',
        'theme.toggleToLight': 'Activate light mode',
        'card.exampleBadge': 'Example',
        'nav.dbt-governance-macro-generator': 'Governance Macro Generator',
        'nav.pii-recommend-generator': 'PII Recommend Generator',
        'card.dbt-governance-macro-generator.title': 'Governance Macro Generator',
        'card.dbt-governance-macro-generator.description':
            'Step 1/4: Runtime macros, generic tests, and SETUP.md for your dbt project.',
        'card.pii-recommend-generator.title': 'PII Recommend Generator',
        'card.pii-recommend-generator.description':
            'Step 4/4: Name and content audit, heuristic rules, and pii_recommend schema.yml.',
        'workflow.setupLabel': 'dbt PII Governance setup',
        'workflow.stepPrefix': 'Step',
        'workflow.prev': '← Previous',
        'workflow.next': 'Next →',
        'workflow.step-dbt-governance-macro-generator': 'Governance Macro Generator',
        'workflow.step-pii-policy-generator': 'DBT Policy Generator',
        'workflow.step-pii-unreviewed-gate-generator': 'Table Gate',
        'workflow.step-pii-recommend-generator': 'PII Recommend Generator',
        'nav.pii-unreviewed-gate-generator': 'Unreviewed Table Gate',
        'card.pii-unreviewed-gate-generator.title': 'Unreviewed Table Gate Generator',
        'card.pii-unreviewed-gate-generator.description':
            'Step 3/4: Identify and hide unreviewed models from roles.',
        'nav.governance-ai-sanitizer': 'Governance AI Sanitizer',
        'card.governance-ai-sanitizer.title': 'Governance AI Sanitizer',
        'card.governance-ai-sanitizer.description':
            'Reference example: sanitize prompt, copy outbound, restore AI response.',
        'nav.pii-policy-generator': 'DBT Policy Generator',
        'card.pii-policy-generator.title': 'DBT Policy Generator',
        'card.pii-policy-generator.description':
            'Step 2/4: pii_details schema.yml example and secure model — target state after review.',
        'nav.schema-yml-editor': 'Schema YML Editor',
        'card.schema-yml-editor.title': 'Schema YML Editor',
        'card.schema-yml-editor.description':
            'Helper tool: edit individual schema.yml — not part of the setup chain.',
        'card.binom-ngx.title': 'binom-ngx',
        'card.binom-ngx.description': 'Angular UI libraries, SDKs, and interactive documentation.',
        'card.binom-ngx.meta': 'Docs & Demos',
        'nav.playbooks': 'Playbooks',
        'nav.playbooksOverview': 'Overview',
        'playbooks.indexTitle': 'Governance Playbooks',
        'playbooks.indexLead': 'Step-by-step governance guides — from idea to implementation.',
        'playbooks.empty': 'No playbooks published yet.',
        'playbooks.category': 'Category',
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
    applyLocaleToDocument(locale);
    applyShellLabels(locale);

    document.querySelectorAll('[data-locale]').forEach((btn) => {
        const value = btn.getAttribute('data-locale');
        if (value !== 'de' && value !== 'en') return;

        const isActive = value === locale;
        btn.classList.toggle('tools-btn--active', isActive);
        btn.setAttribute('aria-pressed', String(isActive));

        btn.addEventListener('click', () => {
            setLocale(/** @type {ToolsLocale} */ (value));
            applyShellLabels(value);
            document.querySelectorAll('[data-locale]').forEach((other) => {
                const otherLocale = other.getAttribute('data-locale');
                const active = otherLocale === value;
                other.classList.toggle('tools-btn--active', active);
                other.setAttribute('aria-pressed', String(active));
            });
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
