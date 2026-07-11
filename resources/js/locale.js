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
        'nav.overview': 'Übersicht',
        'footer.copyright': `© ${new Date().getFullYear()} Binom Tools`,
        'footer.website': 'binom.net',
        'footer.binomNgx': 'binom-ngx Docs',
        'home.title': 'Binom Tools',
        'home.lead':
            'Interaktive Werkzeuge zum Erkunden von Binom SDKs und Workflows — Schritt für Schritt, ohne Gateway.',
        'home.hero.headline': 'BI- & Governance-Workflows',
        'home.hero.headlineAccent': 'als ready-to-use Beispiele.',
        'home.hero.tagline':
            'Referenz-Implementierungen für PII-Policies, dbt schema.yml und AI-Sanitization — nutzbar mit Binom SDKs, ohne Gateway.',
        'home.hero.notice':
            'Für Governance-, BI- und Data-Teams: zeigt konkrete Umsetzungspatterns, nicht nur Konzepte.',
        'home.hero.ctaWorkflows': 'Workflow-Beispiele erkunden',
        'home.hero.ctaSdk': 'binom-ngx SDKs',
        'home.hero.attribution': 'Design-Konzept von',
        'home.workflowsTitle': 'Workflow-Beispiele',
        'home.workflowsLead':
            'Interaktive Referenz-Workflows — Schritt für Schritt, copy-paste-fähig.',
        'home.toolsTitle': 'Tools',
        'home.ecosystemTitle': 'Ökosystem',
        'theme.light': 'Hell',
        'theme.dark': 'Dunkel',
        'theme.toggleToDark': 'Dunkelmodus aktivieren',
        'theme.toggleToLight': 'Hellmodus aktivieren',
        'card.exampleBadge': 'Beispiel',
        'nav.governance-ai-sanitizer': 'Governance AI Sanitizer',
        'card.governance-ai-sanitizer.title': 'Governance AI Sanitizer',
        'card.governance-ai-sanitizer.description':
            'Referenz-Beispiel: Prompt sanitisieren, Outbound kopieren, KI-Antwort wiederherstellen.',
        'nav.pii-policy-generator': 'DBT Policy Generator',
        'card.pii-policy-generator.title': 'DBT Policy Generator',
        'card.pii-policy-generator.description':
            'Referenz-Beispiel: dbt schema.yml, Macro und Policy aus Spalten mit meta.pii_details generieren.',
        'nav.schema-yml-editor': 'Schema YML Editor',
        'card.schema-yml-editor.title': 'Schema YML Editor',
        'card.schema-yml-editor.description':
            'Referenz-Beispiel: schema.yml typo-sicher per Formular bearbeiten — synchron mit DBT Policy Generator.',
        'card.binom-ngx.title': 'binom-ngx',
        'card.binom-ngx.description': 'Angular UI Libraries, SDKs und interaktive Dokumentation.',
        'card.binom-ngx.meta': 'Docs & Demos',
    },
    en: {
        'nav.tools': 'Tools',
        'nav.overview': 'Overview',
        'footer.copyright': `© ${new Date().getFullYear()} Binom Tools`,
        'footer.website': 'binom.net',
        'footer.binomNgx': 'binom-ngx Docs',
        'home.title': 'Binom Tools',
        'home.lead':
            'Interactive utilities for exploring Binom SDKs and workflows — step by step, without a gateway.',
        'home.hero.headline': 'BI & governance workflows',
        'home.hero.headlineAccent': 'as ready-to-use examples.',
        'home.hero.tagline':
            'Reference implementations for PII policies, dbt schema.yml, and AI sanitization — usable with Binom SDKs, no gateway required.',
        'home.hero.notice':
            'For governance, BI, and data teams: demonstrates concrete implementation patterns, not just concepts.',
        'home.hero.ctaWorkflows': 'Explore workflow examples',
        'home.hero.ctaSdk': 'binom-ngx SDKs',
        'home.hero.attribution': 'Design concept by',
        'home.workflowsTitle': 'Workflow examples',
        'home.workflowsLead':
            'Interactive reference workflows — step by step, copy-paste ready.',
        'home.toolsTitle': 'Tools',
        'home.ecosystemTitle': 'Ecosystem',
        'theme.light': 'Light',
        'theme.dark': 'Dark',
        'theme.toggleToDark': 'Activate dark mode',
        'theme.toggleToLight': 'Activate light mode',
        'card.exampleBadge': 'Example',
        'nav.governance-ai-sanitizer': 'Governance AI Sanitizer',
        'card.governance-ai-sanitizer.title': 'Governance AI Sanitizer',
        'card.governance-ai-sanitizer.description':
            'Reference example: sanitize prompt, copy outbound, restore AI response.',
        'nav.pii-policy-generator': 'DBT Policy Generator',
        'card.pii-policy-generator.title': 'DBT Policy Generator',
        'card.pii-policy-generator.description':
            'Reference example: generate dbt schema.yml, macro, and policy from columns with meta.pii_details.',
        'nav.schema-yml-editor': 'Schema YML Editor',
        'card.schema-yml-editor.title': 'Schema YML Editor',
        'card.schema-yml-editor.description':
            'Reference example: edit schema.yml via form without YAML typos — synced with DBT Policy Generator.',
        'card.binom-ngx.title': 'binom-ngx',
        'card.binom-ngx.description': 'Angular UI libraries, SDKs, and interactive documentation.',
        'card.binom-ngx.meta': 'Docs & Demos',
    },
};

/** @param {ToolsLocale} locale */
export function applyShellLabels(locale) {
    const labels = shellLabels[locale];

    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (key && labels[key]) {
            el.textContent = labels[key];
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
