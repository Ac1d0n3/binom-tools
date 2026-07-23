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
