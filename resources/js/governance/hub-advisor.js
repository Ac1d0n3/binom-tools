const texts = {
    de: {
        summary: {
            new: 'Neuaufbau: zuerst Zielbild, Quellen-Scope und Governance Gates klären.',
            extend: 'Bestehende Umgebung: erst Fit, Impact und neue Quelle prüfen.',
            help: 'Orientierung: Stories, Ressourcen und Lernpfade passend zur Lage starten.',
            dq: 'Data Quality: Problem, Schicht und Fehlerklasse klären, dann Regeln und Gates ableiten.',
        },
        groups: {
            tools: 'Empfohlene Tools',
            suppliers: 'Quellen',
            resources: 'Wissen, Stories & Nachweise',
        },
        priority: 'Warum passend',
        open: 'Öffnen',
        saved: 'Session dauerhaft gespeichert.',
        demoSaved: 'Demo-Session in dieser Browser-Sitzung gespeichert.',
        demoReportReady: 'Demo-Session gespeichert. Report ansehen.',
        loginNeeded: 'Bitte einloggen, um diese Session dauerhaft zu speichern.',
        saveFailed: 'Session konnte nicht gespeichert werden.',
        viewReport: 'Report ansehen',
        followup: {
            new: 'Welche Zielplattform und welche Quellfamilie müssen für den Start entschieden werden?',
            extend: 'Welcher Stack ist bereits gesetzt, und welche neue Quelle oder Lücke soll ergänzt werden?',
            help: 'Wo existiert schon Material, und in welchem Stack sollen Stories, Links oder Vorlagen helfen?',
        },
        domainLabel: {
            new: 'Welche Quellfamilie soll zuerst angebunden werden?',
            extend: 'Welche Quelle oder Domäne kommt dazu?',
            help: 'Zu welcher Domäne brauchst du Orientierung?',
        },
        platformLabel: {
            new: 'Welcher Ziel-Stack ist geplant?',
            extend: 'Welcher Stack wird aktuell genutzt?',
            help: 'In welchem Stack suchst du Hilfe?',
        },
    },
    en: {
        summary: {
            new: 'New build: clarify target architecture, source scope, and governance gates first.',
            extend: 'Existing environment: check fit, impact, and the new source first.',
            help: 'Orientation: start with stories, resources, and learning paths that match the situation.',
            dq: 'Data quality: clarify problem, layer, and issue class, then derive rules and gates.',
        },
        groups: {
            tools: 'Recommended tools',
            suppliers: 'Sources',
            resources: 'Knowledge, stories & evidence',
        },
        priority: 'Why it fits',
        open: 'Open',
        saved: 'Session saved permanently.',
        demoSaved: 'Demo session saved in this browser session.',
        demoReportReady: 'Demo session saved. View report.',
        loginNeeded: 'Please sign in to save this session permanently.',
        saveFailed: 'Session could not be saved.',
        viewReport: 'View report',
        followup: {
            new: 'Which target platform and source family need to be decided for the start?',
            extend: 'Which stack is already in use, and which new source or gap is being added?',
            help: 'Where does material already exist, and which stack needs stories, links, or templates?',
        },
        domainLabel: {
            new: 'Which source family should be onboarded first?',
            extend: 'Which source or domain is being added?',
            help: 'Which domain do you need orientation for?',
        },
        platformLabel: {
            new: 'Which target stack is planned?',
            extend: 'Which stack is currently used?',
            help: 'Which stack do you need help with?',
        },
    },
};

const labels = {
    'governance-stack-advisor': {
        group: 'tools',
        icon: 'fa-layer-group',
        title: { de: 'Governance-Stack auswählen', en: 'Governance Stack Advisor' },
        reason: {
            de: 'Vergleicht Fabric, Databricks, Snowflake/dbt, SAP und Open Source nach Zielbild, Betrieb und Governance.',
            en: 'Compares Fabric, Databricks, Snowflake/dbt, SAP, and open source by target architecture, operations, and governance.',
        },
        tags: ['stack', 'new', 'learning'],
    },
    'source-scope-builder': {
        group: 'tools',
        icon: 'fa-database',
        title: { de: 'Quellen-Scope festlegen', en: 'Source Scope Builder' },
        reason: {
            de: 'Sammelt Objekte, Must-have, Skip, PII, Owner und offene Review-Fragen für die erste Ladeentscheidung.',
            en: 'Collects objects, must-haves, skips, PII, owners, and open review questions for the first load decision.',
        },
        tags: ['supplier', 'new', 'extend', 'crm', 'erp', 'hcm', 'collab', 'finance'],
    },
    'kpi-requirements-intake': {
        group: 'tools',
        icon: 'fa-gauge-high',
        title: { de: 'KPI-Anforderungen erfassen', en: 'KPI Requirements Intake' },
        reason: {
            de: 'Macht aus Stakeholder-Wünschen Formel, Grain, Owner, Dimensionen und Akzeptanzbeispiele.',
            en: 'Turns stakeholder needs into formula, grain, owner, dimensions, and acceptance examples.',
        },
        tags: ['kpi', 'new', 'help', 'finance'],
    },
    'mart-design-brief-generator': {
        group: 'tools',
        icon: 'fa-table-cells',
        title: { de: 'Mart Design Brief erstellen', en: 'Mart Design Brief' },
        reason: {
            de: 'Führt von KPI-Karten zu Fact-/Dimension-Kandidaten, Grain und offenen Modellierungsentscheidungen.',
            en: 'Moves KPI cards into fact/dimension candidates, grain, and open modelling decisions.',
        },
        tags: ['kpi', 'new', 'extend', 'dq', 'mart_quality_gate'],
    },
    'pii-dsdr-readiness-checker': {
        group: 'tools',
        icon: 'fa-shield-halved',
        title: { de: 'PII/DSDR Readiness prüfen', en: 'PII/DSDR Readiness Checker' },
        reason: {
            de: 'Prüft PII, Freitext, Lösch-/Auskunftskeys, Retention und Access-Risiken vor der Umsetzung.',
            en: 'Checks PII, free text, deletion/access keys, retention, and access risks before implementation.',
        },
        tags: ['pii', 'new', 'extend', 'hcm', 'collab', 'finance'],
    },
    'decision-brief-generator': {
        group: 'tools',
        icon: 'fa-file-signature',
        title: { de: 'Entscheidungsvorlage erstellen', en: 'Decision Brief Generator' },
        reason: {
            de: 'Verdichtet Optionen, Annahmen, Risiken und nächste Schritte in eine entscheidbare Vorlage.',
            en: 'Condenses options, assumptions, risks, and next steps into a decision-ready brief.',
        },
        tags: ['stack', 'supplier', 'kpi', 'new', 'extend'],
    },
    'vendor-learning-path-builder': {
        group: 'tools',
        icon: 'fa-graduation-cap',
        title: { de: 'Lern- und Zertifizierungspfad planen', en: 'Vendor Learning Path Builder' },
        reason: {
            de: 'Ordnet Hersteller-Doku, Lernpfade und Zertifikate passend zu Stack und Rolle.',
            en: 'Maps vendor docs, learning paths, and certifications to stack and role.',
        },
        tags: ['learning', 'help', 'new', 'fabric', 'databricks', 'snowflake-dbt', 'sap'],
    },
    'architecture-fit': {
        group: 'tools',
        icon: 'fa-diagram-project',
        title: { de: 'Architecture Fit', en: 'Architecture Fit' },
        reason: {
            de: 'Klärt, ob die Ergänzung zur bestehenden Architektur, zum Betrieb und zu Governance-Auflagen passt.',
            en: 'Checks whether the extension fits the existing architecture, operations, and governance constraints.',
        },
        tags: ['stack', 'extend', 'opensource'],
    },
    'impact-effort': {
        group: 'tools',
        icon: 'fa-scale-balanced',
        title: { de: 'Impact Effort Matrix', en: 'Impact Effort Matrix' },
        reason: {
            de: 'Sortiert Ideen nach Nutzen, Aufwand und Risiko, wenn mehrere nächste Schritte konkurrieren.',
            en: 'Ranks ideas by value, effort, and risk when several next steps compete.',
        },
        tags: ['extend', 'help', 'kpi', 'supplier'],
    },
    'meta-export-generator': {
        group: 'tools',
        icon: 'fa-file-export',
        title: { de: 'Meta Export Generator', en: 'Meta Export Generator' },
        reason: {
            de: 'Hilft bei existierenden Systemen, Tabellen, Spalten und Ownership als Entscheidungsinput zu sammeln.',
            en: 'Helps collect existing systems, tables, columns, and ownership as decision input.',
        },
        tags: ['extend', 'supplier', 'dq', 'unknown', 'health_check', 'source', 'raw'],
    },
    'report-inventory': {
        group: 'tools',
        icon: 'fa-chart-simple',
        title: { de: 'Report Inventory Canvas', en: 'Report Inventory Canvas' },
        reason: {
            de: 'Findet vorhandene Reports, echte Geschäftsfragen, Owner und wiederkehrende KPI-Kandidaten.',
            en: 'Finds existing reports, real business questions, owners, and recurring KPI candidates.',
        },
        tags: ['help', 'kpi', 'extend', 'dq', 'report_stabilization', 'bi'],
    },
    'kpi-definition': {
        group: 'tools',
        icon: 'fa-square-poll-vertical',
        title: { de: 'KPI Definition Card', en: 'KPI Definition Card' },
        reason: {
            de: 'Schärft eine einzelne Kennzahl, bevor daraus Modell, Measure oder Report entsteht.',
            en: 'Sharpens one metric before it becomes a model, measure, or report.',
        },
        tags: ['kpi', 'help', 'finance'],
    },
    'pii-policy-generator': {
        group: 'tools',
        icon: 'fa-user-shield',
        title: { de: 'PII Policy Generator', en: 'PII Policy Generator' },
        reason: {
            de: 'Erzeugt erste Regeln für Klassifizierung, Maskierung, Rollen und Review-Punkte.',
            en: 'Creates first rules for classification, masking, roles, and review points.',
        },
        tags: ['pii', 'hcm', 'collab', 'finance'],
    },
    'pii-recommend-generator': {
        group: 'tools',
        icon: 'fa-lock',
        title: { de: 'PII Recommend Generator', en: 'PII Recommend Generator' },
        reason: {
            de: 'Gibt Feldempfehlungen für sensible Daten und Prioritäten in der Umsetzung.',
            en: 'Suggests sensitive-field handling and priorities for implementation.',
        },
        tags: ['pii', 'supplier', 'hcm', 'collab'],
    },
    'schema-yml-editor': {
        group: 'tools',
        icon: 'fa-code',
        title: { de: 'Schema YAML Editor', en: 'Schema YAML Editor' },
        reason: {
            de: 'Bringt beschriebene Modelle, Tests und Metadaten in eine dbt-nahe Arbeitsform.',
            en: 'Turns described models, tests, and metadata into a dbt-friendly working format.',
        },
        tags: ['dq', 'snowflake-dbt', 'opensource', 'transform', 'mart', 'semantic'],
    },
    'dbt-dq-macro-generator': {
        group: 'tools',
        icon: 'fa-code-branch',
        title: { de: 'dbt DQ Macro Generator', en: 'dbt DQ Macro Generator' },
        reason: {
            de: 'Erstellt die technische Basis für wiederverwendbare DQ-Checks und Governance-Makros.',
            en: 'Creates the technical base for reusable DQ checks and governance macros.',
        },
        tags: ['dq', 'snowflake-dbt', 'opensource', 'transform', 'business_rule', 'referential_integrity'],
    },
    'dbt-dq-rules-generator': {
        group: 'tools',
        icon: 'fa-list-check',
        title: { de: 'dbt DQ Rules Generator', en: 'dbt DQ Rules Generator' },
        reason: {
            de: 'Leitet aus Regeln und Erwartungen konkrete Data-Quality-Checks für dbt ab.',
            en: 'Turns rules and expectations into concrete data-quality checks for dbt.',
        },
        tags: ['dq', 'snowflake-dbt', 'opensource', 'completeness', 'duplicates', 'freshness', 'value_range', 'referential_integrity', 'business_rule'],
    },
    'dbt-dq-history-generator': {
        group: 'tools',
        icon: 'fa-clock-rotate-left',
        title: { de: 'DQ History Generator', en: 'DQ History Generator' },
        reason: {
            de: 'Plant Historie und Monitoring für DQ-Findings, Trends und wiederkehrende Gates.',
            en: 'Plans history and monitoring for DQ findings, trends, and recurring gates.',
        },
        tags: ['dq', 'health_check', 'known_issue', 'freshness', 'report_stabilization'],
    },
    'fabric-pii-governance-pattern-generator': {
        group: 'tools',
        icon: 'fa-window-maximize',
        title: { de: 'Fabric PII Governance Pattern', en: 'Fabric PII Governance Pattern' },
        reason: {
            de: 'Passt, wenn Fabric/Power BI schon gesetzt ist und PII-Gates konkret werden müssen.',
            en: 'Fits when Fabric/Power BI is selected and PII gates need to become concrete.',
        },
        tags: ['fabric', 'pii', 'dq'],
    },
    'databricks-pii-governance-pattern-generator': {
        group: 'tools',
        icon: 'fa-database',
        title: { de: 'Databricks PII Governance Pattern', en: 'Databricks PII Governance Pattern' },
        reason: {
            de: 'Passt für Unity Catalog, Grants, Tags, Maskierung und Lakehouse-Governance.',
            en: 'Fits Unity Catalog, grants, tags, masking, and lakehouse governance.',
        },
        tags: ['databricks', 'pii', 'dq'],
    },
    'unity-catalog-governance-generator': {
        group: 'tools',
        icon: 'fa-key',
        title: { de: 'Unity Catalog Governance', en: 'Unity Catalog Governance' },
        reason: {
            de: 'Hilft bei Unity-Catalog-Standards für Owner, Tags, Grants und PII-Spalten.',
            en: 'Helps with Unity Catalog standards for owners, tags, grants, and PII columns.',
        },
        tags: ['databricks', 'pii', 'dq'],
    },
};

const hubItems = {
    suppliers: {
        group: 'suppliers',
        icon: 'fa-plug',
        title: { de: 'Supplier Library', en: 'Supplier Library' },
        reason: {
            de: 'Startpunkt für Salesforce, HubSpot, SAP, Workday, ServiceNow, SharePoint und weitere Quellen.',
            en: 'Starting point for Salesforce, HubSpot, SAP, Workday, ServiceNow, SharePoint, and more sources.',
        },
        tags: ['supplier', 'new', 'extend', 'crm', 'erp', 'hcm', 'collab', 'finance'],
    },
    resources: {
        group: 'resources',
        icon: 'fa-book-open',
        title: { de: 'Vendor Resources & Zertifikate', en: 'Vendor resources & certifications' },
        reason: {
            de: 'Offizielle Docs, Governance-Seiten, Lernpfade, Cloud-Residency und Zertifikate sammeln.',
            en: 'Collect official docs, governance pages, learning paths, cloud residency, and certifications.',
        },
        tags: ['learning', 'help', 'stack', 'fabric', 'databricks', 'snowflake-dbt', 'sap'],
    },
    compliance: {
        group: 'resources',
        icon: 'fa-scale-balanced',
        title: { de: 'Compliance Hub', en: 'Compliance Hub' },
        reason: {
            de: 'Nutzen, wenn PII, DSDR, Retention, Access oder Nachweise zur Entscheidung gehoeren.',
            en: 'Use when PII, DSDR, retention, access, or evidence are part of the decision.',
        },
        tags: ['pii', 'finance', 'hcm', 'collab', 'new', 'extend'],
    },
    playbooks: {
        group: 'resources',
        icon: 'fa-book',
        title: { de: 'Governance Stories & Playbooks', en: 'Governance stories & playbooks' },
        reason: {
            de: 'Gut, wenn schon vieles vorhanden ist und du Beispiele, Stories und Vorgehen brauchst.',
            en: 'Useful when much already exists and you need examples, stories, and next actions.',
        },
        tags: ['help', 'learning', 'kpi', 'dq', 'supplier'],
    },
    learningPaths: {
        group: 'resources',
        icon: 'fa-route',
        title: { de: 'Learning Paths', en: 'Learning Paths' },
        reason: {
            de: 'Geführte Journeys (PII, DQ, Warehouse, Foundations) — enden im passenden Sprint-Plan.',
            en: 'Guided journeys (PII, DQ, warehouse, foundations) — each ends in a matching sprint plan.',
        },
        tags: ['help', 'learning', 'new', 'pii', 'dq', 'stack'],
    },
    roles: {
        group: 'resources',
        icon: 'fa-user-group',
        title: { de: 'Roles Hub', en: 'Roles Hub' },
        reason: {
            de: 'Decision Rights klären: Steward, Owner, Architect, Custodian, Consumer.',
            en: 'Clarify decision rights: steward, owner, architect, custodian, consumer.',
        },
        tags: ['help', 'learning', 'new', 'kpi'],
    },
    sprintPlanner: {
        group: 'resources',
        icon: 'fa-calendar-week',
        title: { de: 'Sprint Planner Templates', en: 'Sprint Planner templates' },
        reason: {
            de: 'Lern- oder Umsetzungsplan aus einer Vorlage starten und im Team abarbeiten.',
            en: 'Start a learning or delivery plan from a template and work it as a team.',
        },
        tags: ['help', 'learning', 'new', 'extend', 'dq', 'stack'],
    },
};

const sourceItems = {
    crm: {
        group: 'suppliers',
        icon: 'fa-handshake',
        title: { de: 'CRM & Revenue Quellen', en: 'CRM & revenue sources' },
        reason: {
            de: 'Salesforce, HubSpot und Dynamics: Accounts, Contacts, Deals, Campaigns, ARR/Pipeline und Formular-PII.',
            en: 'Salesforce, HubSpot, and Dynamics: accounts, contacts, deals, campaigns, ARR/pipeline, and form PII.',
        },
    },
    erp: {
        group: 'suppliers',
        icon: 'fa-building-columns',
        title: { de: 'ERP, Finance & Procurement Quellen', en: 'ERP, finance & procurement sources' },
        reason: {
            de: 'SAP S/4HANA, NetSuite, DATEV und Coupa: Belege, Kunden/Lieferanten, Kostenstellen und Finance-KPIs.',
            en: 'SAP S/4HANA, NetSuite, DATEV, and Coupa: documents, customers/suppliers, cost centers, and finance KPIs.',
        },
    },
    hcm: {
        group: 'suppliers',
        icon: 'fa-users-gear',
        title: { de: 'HCM & Workforce Quellen', en: 'HCM & workforce sources' },
        reason: {
            de: 'Workday, SuccessFactors und Personio: Organisation, Worker, Abwesenheit, Compensation und hohe PII-Priorität.',
            en: 'Workday, SuccessFactors, and Personio: organization, worker, absence, compensation, and high PII priority.',
        },
    },
    collab: {
        group: 'suppliers',
        icon: 'fa-comments',
        title: { de: 'Collaboration & Service Quellen', en: 'Collaboration & service sources' },
        reason: {
            de: 'SharePoint, Teams, Jira und ServiceNow: Tickets, Sites, Spaces, Freitext, Attachments und Access.',
            en: 'SharePoint, Teams, Jira, and ServiceNow: tickets, sites, spaces, free text, attachments, and access.',
        },
    },
    finance: {
        group: 'suppliers',
        icon: 'fa-file-invoice-dollar',
        title: { de: 'Reguliertes Finance Reporting', en: 'Regulated finance reporting' },
        reason: {
            de: 'Hilft bei Kennzahlen, Abschlusslogik, Nachweisen, Berechtigungen und auditierbaren Mart-Entscheidungen.',
            en: 'Helps with metrics, close logic, evidence, permissions, and auditable mart decisions.',
        },
    },
};

function pickLocale() {
    return document.documentElement.lang === 'de' ? 'de' : 'en';
}

function translate(value, locale) {
    return value?.[locale] || value?.en || value?.de || '';
}

function readConfig(root) {
    const element = root.querySelector('[data-governance-advisor-config]');

    if (!element) {
        return { links: { tools: {}, hubs: {} } };
    }

    try {
        return JSON.parse(element.textContent || '{}');
    } catch {
        return { links: { tools: {}, hubs: {} } };
    }
}

function getState(form) {
    const formData = new FormData(form);

    return {
        scenario: formData.get('scenario') || 'new',
        goal: formData.get('goal') || 'stack',
        domain: formData.get('domain') || 'unknown',
        platform: formData.get('platform') || 'unknown',
        dqMode: formData.get('dqMode') || 'health_check',
        dqLayer: formData.get('dqLayer') || 'source',
        dqIssues: formData.getAll('dqIssues[]').map(String),
    };
}

function itemUrl(item, config) {
    if (item.kind === 'hub') {
        return config.links?.hubs?.[item.id] || '#';
    }

    return config.links?.tools?.[item.id] || '#';
}

function scoreItem(item, state) {
    const tags = new Set(item.tags || []);
    let score = 0;

    if (tags.has(state.goal)) {
        score += 8;
    }

    if (tags.has(state.scenario)) {
        score += 5;
    }

    if (state.domain !== 'unknown' && tags.has(state.domain)) {
        score += 4;
    }

    if (state.platform !== 'unknown' && tags.has(state.platform)) {
        score += 4;
    }

    if (state.goal === 'dq') {
        if (tags.has(state.dqMode)) {
            score += 5;
        }
        if (tags.has(state.dqLayer)) {
            score += 5;
        }
        for (const issue of state.dqIssues || []) {
            if (tags.has(issue)) {
                score += 3;
            }
        }
    }

    if (state.goal === 'supplier' && item.group === 'suppliers') {
        score += 4;
    }

    if (state.scenario === 'help' && item.group === 'resources') {
        score += 3;
    }

    if (state.scenario === 'help' && ['learningPaths', 'roles', 'sprintPlanner', 'playbooks'].includes(item.id)) {
        score += 5;
    }

    if (state.scenario === 'new' && ['learningPaths', 'roles', 'sprintPlanner'].includes(item.id)) {
        score += 3;
    }

    if (state.scenario === 'new' && ['governance-stack-advisor', 'source-scope-builder', 'kpi-requirements-intake'].includes(item.id)) {
        score += 2;
    }

    if (state.scenario === 'extend' && ['architecture-fit', 'impact-effort', 'meta-export-generator'].includes(item.id)) {
        score += 3;
    }

    if (state.goal === 'dq' && ['dbt-dq-rules-generator', 'dbt-dq-macro-generator', 'dbt-dq-history-generator'].includes(item.id)) {
        score += 5;
    }

    return score;
}

function buildRecommendations(state, config) {
    const tools = Object.entries(labels).map(([id, item]) => ({ ...item, id, kind: 'tool' }));
    const hubs = Object.entries(hubItems).map(([id, item]) => ({ ...item, id, kind: 'hub' }));
    const domainItem = state.domain === 'unknown'
        ? []
        : [{ ...sourceItems[state.domain], id: 'suppliers', kind: 'hub', fixed: true }];
    const candidates = [...tools, ...hubs, ...domainItem]
        .map((item, index) => ({
            ...item,
            url: itemUrl(item, config),
            score: item.fixed ? 99 : scoreItem(item, state),
            order: index,
        }))
        .filter((item) => item.url !== '#' && item.score > 0)
        .sort((a, b) => b.score - a.score || a.order - b.order);

    const seen = new Set();

    return candidates.filter((item) => {
        const key = `${item.group}:${item.title.en}:${item.url}`;

        if (seen.has(key)) {
            return false;
        }

        seen.add(key);
        return true;
    }).slice(0, 9);
}

function serializableRecommendations(recommendations, locale) {
    return recommendations.map((item) => ({
        id: item.id,
        group: item.group,
        kind: item.kind,
        title: translate(item.title, locale),
        reason: translate(item.reason, locale),
        url: item.url,
        score: item.score,
    }));
}

function createRecommendation(item, locale, copy) {
    const link = document.createElement('a');
    link.className = 'governance-advisor__result-card';
    link.href = item.url;

    const icon = document.createElement('i');
    icon.className = `fa-solid ${item.icon || 'fa-arrow-right'}`;
    icon.setAttribute('aria-hidden', 'true');

    const body = document.createElement('span');
    const title = document.createElement('strong');
    title.textContent = translate(item.title, locale);

    const label = document.createElement('small');
    label.textContent = copy.priority;

    const reason = document.createElement('em');
    reason.textContent = translate(item.reason, locale);

    const cta = document.createElement('b');
    cta.textContent = copy.open;

    body.append(title, label, reason);
    link.append(icon, body, cta);

    return link;
}

function currentPayload(root, config) {
    const locale = pickLocale();
    const form = root.querySelector('[data-governance-advisor-form]');
    const title = root.querySelector('[data-governance-session-title]');
    const company = root.querySelector('[data-governance-session-company]');
    const project = root.querySelector('[data-governance-session-project]');
    const state = getState(form);
    const recommendations = buildRecommendations(state, config);

    return {
        title: String(title?.value || '').trim() || 'Governance Discovery',
        companyName: String(company?.value || '').trim(),
        projectName: String(project?.value || '').trim(),
        scenario: state.scenario,
        status: 'draft',
        currentStep: 'advisor',
        payload: {
            advisor: state,
            dataQuality: state.goal === 'dq'
                ? {
                    mode: state.dqMode,
                    layer: state.dqLayer,
                    issueTypes: state.dqIssues,
                    affectedSources: [],
                    affectedKpis: [],
                    affectedReports: [],
                    proposedRules: [],
                    validationFindings: [],
                    decisionStatus: 'draft',
                }
                : {},
            recommendations: serializableRecommendations(recommendations, locale),
        },
    };
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function demoSessionKey() {
    return 'binom-tools:governance-discovery-demo:v1';
}

function richDemoSession(root, config) {
    const base = currentPayload(root, config);
    const locale = pickLocale();
    const advisor = {
        scenario: 'extend',
        goal: 'dq',
        domain: 'erp',
        platform: 'fabric',
        dqMode: 'report_stabilization',
        dqLayer: 'bi',
        dqIssues: ['freshness', 'business_rule', 'completeness'],
    };
    const recommendations = serializableRecommendations(buildRecommendations(advisor, config), locale);

    return {
        ...base,
        title: 'Demo: Finance Governance Discovery',
        companyName: base.companyName || 'Acme GmbH',
        projectName: base.projectName || 'Management Reporting 2026',
        scenario: advisor.scenario,
        status: 'draft',
        payload: {
            advisor,
            kpis: [
                {
                    name: 'Net Revenue',
                    formula: 'Rechnungsbetrag abzüglich Gutschriften und Stornos.',
                    grain: 'Firma, Kunde, Rechnungsmonat',
                    owner: 'Finance Owner',
                    source: 'SAP S/4HANA Faktura',
                    status: 'agreed',
                },
                {
                    name: 'Offene Forderungen',
                    formula: 'Offene Posten nach Fälligkeitsklasse.',
                    grain: 'Firma, Kunde, Beleg, Tag',
                    owner: 'Debitoren Lead',
                    source: 'SAP FI-AR',
                    status: 'draft',
                },
            ],
            sourceScope: {
                supplier: 'SAP S/4HANA',
                mustHave: ['Fakturabelege', 'Kunden', 'Buchungskreis', 'Offene Posten'],
                optional: ['Kundenaufträge', 'Kostenstellen'],
                skip: ['Anhänge', 'lange Freitextnotizen'],
                owners: ['Finance Owner', 'Platform Owner'],
            },
            pii: {
                fields: ['E-Mail Rechnungskontakt', 'Name Rechnungskontakt'],
                dsdrKeys: ['customer_id', 'contact_email'],
                controls: ['Maskierung in BI-Extraktionen', 'Retention-Review vor Raw-Load'],
            },
            dataQuality: {
                mode: advisor.dqMode,
                layer: advisor.dqLayer,
                issueTypes: advisor.dqIssues,
                affectedSources: ['SAP S/4HANA Faktura'],
                affectedKpis: ['Net Revenue', 'Offene Forderungen'],
                affectedReports: ['Executive Finance Dashboard'],
                proposedRules: [
                    'billing_date darf nicht leer sein',
                    'invoice_amount muss nach Storno-Mapping >= 0 sein',
                    'Dashboard-Refresh darf maximal 24h alt sein',
                ],
                validationFindings: ['zwei Reports nutzen unterschiedliche Umsatzfilter'],
                decisionStatus: 'review',
            },
            decisionBrief: {
                recommendation: 'Bestehenden Fabric Finance Mart stabilisieren, bevor eine weitere ERP-Quelle angebunden wird.',
                openQuestions: ['finale Storno-Logik', 'Owner-Freigabe für PII-Maskierung'],
                nextSprint: ['Source-Scope-Review', 'DQ-Regeln', 'Decision-Brief-Freigabe'],
            },
            recommendations,
        },
    };
}

function saveDemoSession(root, config) {
    const session = {
        ...richDemoSession(root, config),
        id: `demo_${Date.now()}`,
        updatedAt: new Date().toISOString(),
    };
    const raw = sessionStorage.getItem(demoSessionKey());
    let sessions = [];
    try {
        sessions = raw ? JSON.parse(raw) : [];
    } catch {
        sessions = [];
    }
    sessions.unshift(session);
    sessionStorage.setItem(demoSessionKey(), JSON.stringify(sessions.slice(0, 12)));

    return session;
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function listHtml(items) {
    const values = Array.isArray(items) ? items.filter(Boolean) : [];

    if (values.length === 0) {
        return '<li>-</li>';
    }

    return values.map((item) => `<li>${escapeHtml(item)}</li>`).join('');
}

function factsHtml(items) {
    return items.map(([label, value]) => `
        <div>
            <dt>${escapeHtml(label)}</dt>
            <dd>${escapeHtml(Array.isArray(value) ? value.filter(Boolean).join(', ') || '-' : value || '-')}</dd>
        </div>
    `).join('');
}

function absoluteUrl(url) {
    try {
        return new URL(String(url || '#'), window.location.origin).href;
    } catch {
        return '#';
    }
}

function demoReportHtml(session) {
    const payload = session.payload || {};
    const advisor = payload.advisor || {};
    const dataQuality = payload.dataQuality || {};
    const recommendations = Array.isArray(payload.recommendations) ? payload.recommendations : [];
    const kpis = Array.isArray(payload.kpis) ? payload.kpis : [];
    const sourceScope = payload.sourceScope || {};
    const pii = payload.pii || {};
    const decisionBrief = payload.decisionBrief || {};

    return `<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>${escapeHtml(session.title || 'Governance Demo Report')}</title>
    <style>
        body { color: #17202a; font: 16px/1.5 system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; margin: 0; background: #f4f7fb; }
        main { max-width: 1040px; margin: 0 auto; padding: 32px 20px 48px; }
        header, section { background: #fff; border: 1px solid #d8e2ee; border-radius: 8px; margin-bottom: 16px; padding: 20px; }
        h1, h2, p { margin-top: 0; }
        h1 { font-size: 2rem; line-height: 1.15; }
        h2 { font-size: 1.1rem; }
        dl { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; }
        dt { color: #5f6f82; font-size: .78rem; font-weight: 800; text-transform: uppercase; }
        dd { margin: 4px 0 0; font-weight: 700; }
        article { border: 1px solid #d8e2ee; border-radius: 8px; margin-top: 10px; padding: 12px; }
        a, button { color: #1269a8; font-weight: 800; }
        button { border: 1px solid #b9c8d8; border-radius: 8px; background: #fff; cursor: pointer; padding: 10px 14px; }
        @media print { body { background: #fff; } button { display: none; } main { max-width: none; padding: 0; } }
    </style>
</head>
<body>
    <main>
        <header>
            <p>Report Ansicht</p>
            <h1>${escapeHtml(session.title || 'Governance Discovery')}</h1>
            <p>${escapeHtml(session.companyName || 'Demo-Session')}${session.projectName ? ` - ${escapeHtml(session.projectName)}` : ''}</p>
            <button type="button" onclick="window.print()">Drucken/PDF</button>
        </header>
        <section>
            <h2>Eingaben</h2>
            <dl>${factsHtml([
                ['Ausgangslage', advisor.scenario],
                ['Ziel', advisor.goal],
                ['Quelle/Domäne', advisor.domain],
                ['Stack', advisor.platform],
            ])}</dl>
        </section>
        <section>
            <h2>Empfehlungen</h2>
            ${recommendations.map((item) => `
                <article>
                    <strong>${escapeHtml(item.title || '-')}</strong>
                    <p>${escapeHtml(item.group || 'tool')}</p>
                    <p>${escapeHtml(item.reason || '')}</p>
                    ${item.url ? `<a href="${escapeHtml(absoluteUrl(item.url))}">Öffnen</a>` : ''}
                </article>
            `).join('') || '<p>Noch keine Empfehlungen gespeichert.</p>'}
        </section>
        <section>
            <h2>KPI-Karten</h2>
            ${kpis.map((kpi) => `
                <article>
                    <strong>${escapeHtml(kpi.name || '-')}</strong>
                    <p>${escapeHtml(kpi.formula || '-')}</p>
                    <p>Grain: ${escapeHtml(kpi.grain || '-')} · Owner: ${escapeHtml(kpi.owner || '-')}</p>
                </article>
            `).join('') || '<p>-</p>'}
        </section>
        <section>
            <h2>Source Scope</h2>
            <dl>${factsHtml([
                ['Supplier', sourceScope.supplier],
                ['Must-have', sourceScope.mustHave],
                ['Optional', sourceScope.optional],
                ['Skip', sourceScope.skip],
                ['Owner', sourceScope.owners],
            ])}</dl>
        </section>
        <section>
            <h2>PII/DSDR</h2>
            <ul>${listHtml([...(pii.fields || []), ...(pii.dsdrKeys || []), ...(pii.controls || [])])}</ul>
        </section>
        <section>
            <h2>Data Quality</h2>
            <dl>${factsHtml([
                ['Modus', dataQuality.mode || advisor.dqMode],
                ['Schicht', dataQuality.layer || advisor.dqLayer],
                ['Fehlerklassen', dataQuality.issueTypes || advisor.dqIssues],
                ['Betroffene Reports', dataQuality.affectedReports],
                ['Regelvorschläge', dataQuality.proposedRules],
            ])}</dl>
        </section>
        <section>
            <h2>Decision Brief</h2>
            <dl>${factsHtml([
                ['Empfehlung', decisionBrief.recommendation],
                ['Offene Fragen', decisionBrief.openQuestions],
                ['Nächster Sprint', decisionBrief.nextSprint],
            ])}</dl>
        </section>
    </main>
</body>
</html>`;
}

function demoReportUrl(session) {
    return URL.createObjectURL(new Blob([demoReportHtml(session)], { type: 'text/html;charset=utf-8' }));
}

async function savePermanentSession(root, config) {
    const apiUrl = config.links?.session?.apiUrl || config.session?.apiUrl;
    if (!apiUrl) {
        throw new Error('login-required');
    }

    const response = await fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
        body: JSON.stringify({ session: currentPayload(root, config) }),
    });

    if (response.status === 401 || response.status === 403) {
        throw new Error('login-required');
    }
    if (!response.ok) {
        throw new Error(`save-failed-${response.status}`);
    }

    return response.json();
}

function setSaveStatus(root, message) {
    const status = root.querySelector('[data-governance-save-status]');
    if (status) {
        status.textContent = message;
    }
}

function render(root, config) {
    const locale = pickLocale();
    const copy = texts[locale];
    const form = root.querySelector('[data-governance-advisor-form]');
    const summary = root.querySelector('[data-governance-advisor-summary]');
    const results = root.querySelector('[data-governance-advisor-results]');

    if (!form || !summary || !results) {
        return;
    }

    const state = getState(form);
    const followup = root.querySelector('[data-governance-followup-copy]');
    const domainLabel = root.querySelector('[data-governance-domain-label]');
    const platformLabel = root.querySelector('[data-governance-platform-label]');
    const recommendations = buildRecommendations(state, config);
    const grouped = recommendations.reduce((groups, item) => {
        const group = item.group || 'tools';
        groups[group] = groups[group] || [];
        groups[group].push(item);
        return groups;
    }, {});

    summary.textContent = copy.summary[state.scenario] || copy.summary.new;
    if (state.goal === 'dq') {
        summary.textContent = copy.summary.dq;
    }
    if (followup) {
        followup.textContent = copy.followup?.[state.scenario] || copy.followup.new;
    }
    if (domainLabel) {
        domainLabel.textContent = copy.domainLabel?.[state.scenario] || copy.domainLabel.new;
    }
    if (platformLabel) {
        platformLabel.textContent = copy.platformLabel?.[state.scenario] || copy.platformLabel.new;
    }
    results.replaceChildren();

    ['tools', 'suppliers', 'resources'].forEach((group) => {
        const items = grouped[group] || [];

        if (items.length === 0) {
            return;
        }

        const section = document.createElement('section');
        section.className = 'governance-advisor__result-group';

        const heading = document.createElement('h4');
        heading.textContent = copy.groups[group];

        const list = document.createElement('div');
        list.className = 'governance-advisor__result-list';

        items.forEach((item) => list.append(createRecommendation(item, locale, copy)));
        section.append(heading, list);
        results.append(section);
    });
}

function initPanelToggles(root) {
    const controls = root.querySelector('[data-governance-top-controls]');
    const panels = Array.from(root.querySelectorAll('[data-governance-panel]'));
    const toggles = Array.from(root.querySelectorAll('[data-governance-panel-toggle]'));
    const drawerToggle = root.querySelector('[data-governance-drawer-toggle]');

    if (!controls || panels.length === 0 || toggles.length === 0 || !drawerToggle) {
        return;
    }

    let scrollAnchor = null;

    const rememberScroll = () => {
        scrollAnchor = { x: window.scrollX, y: window.scrollY };
    };

    const activatePanel = (targetId) => {
        panels.forEach((panel) => {
            panel.hidden = panel.id !== targetId;
        });
        toggles.forEach((toggle) => {
            const isActive = toggle.dataset.governancePanelToggle === targetId;
            toggle.classList.toggle('governance-hub__panel-tab--active', isActive);
            toggle.setAttribute('aria-selected', String(isActive));
            toggle.tabIndex = isActive ? 0 : -1;
        });
    };

    const keepScrollPosition = (callback) => {
        const scrollX = scrollAnchor?.x ?? window.scrollX;
        const scrollY = scrollAnchor?.y ?? window.scrollY;
        callback();
        window.requestAnimationFrame(() => {
            window.scrollTo(scrollX, scrollY);
            window.setTimeout(() => {
                window.scrollTo(scrollX, scrollY);
                scrollAnchor = null;
            }, 40);
        });
    };

    const sync = () => {
        drawerToggle.setAttribute('aria-expanded', String(!controls.hidden));
    };

    drawerToggle.addEventListener('click', () => {
        controls.hidden = !controls.hidden;
        if (!controls.hidden && !panels.some((panel) => !panel.hidden)) {
            activatePanel(toggles[0]?.dataset.governancePanelToggle || panels[0]?.id || '');
        }
        sync();
    });

    toggles.forEach((toggle) => {
        toggle.addEventListener('pointerdown', rememberScroll);
        toggle.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                rememberScroll();
            }
        });
        toggle.addEventListener('click', () => {
            const target = root.querySelector(`#${toggle.dataset.governancePanelToggle}`);
            if (!(target instanceof HTMLElement)) {
                return;
            }
            keepScrollPosition(() => {
                controls.hidden = false;
                activatePanel(target.id);
                toggle.blur();
                sync();
            });
        });
    });

    activatePanel(toggles.find((toggle) => toggle.getAttribute('aria-selected') === 'true')?.dataset.governancePanelToggle || toggles[0].dataset.governancePanelToggle || panels[0].id);
    controls.hidden = true;
    sync();
}

const PERSONA_STORAGE_KEY = 'binom-governance-persona';

function setRadioValue(form, name, value) {
    const input = form.querySelector(`input[name="${name}"][value="${value}"]`);
    if (input instanceof HTMLInputElement) {
        input.checked = true;
    }
}

function applyPersonaHighlight() {
    // Intentionally empty: outlining/dimming whole guide blocks made nested frames.
}

function initPersonas(root, onChange) {
    const chips = Array.from(root.querySelectorAll('[data-governance-persona]'));
    if (chips.length === 0) {
        return;
    }

    const form = root.querySelector('[data-governance-advisor-form]');
    let active = '';
    try {
        active = localStorage.getItem(PERSONA_STORAGE_KEY) || '';
    } catch {
        active = '';
    }

    const apply = (persona, persist) => {
        active = persona;
        chips.forEach((chip) => {
            const isActive = chip.dataset.governancePersona === persona;
            chip.classList.toggle('governance-hub__persona--active', isActive);
            chip.setAttribute('aria-pressed', String(isActive));
        });
        applyPersonaHighlight(root, persona || null);
        if (persona && form) {
            const chip = chips.find((item) => item.dataset.governancePersona === persona);
            if (chip) {
                setRadioValue(form, 'scenario', chip.dataset.personaScenario || '');
                setRadioValue(form, 'goal', chip.dataset.personaGoal || '');
                form.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
        if (persist) {
            try {
                if (persona) {
                    localStorage.setItem(PERSONA_STORAGE_KEY, persona);
                } else {
                    localStorage.removeItem(PERSONA_STORAGE_KEY);
                }
            } catch {
                // ignore storage failures
            }
        }
        onChange?.();
    };

    chips.forEach((chip) => {
        chip.addEventListener('click', () => {
            const next = chip.dataset.governancePersona || '';
            apply(active === next ? '' : next, true);
        });
    });

    if (active) {
        apply(active, false);
    }
}

function normalizeGuidesFragment(fragment) {
    if (!fragment) {
        return '';
    }
    return fragment.startsWith('guides-') ? fragment.slice('guides-'.length) : fragment;
}

function activateSubtabGroup(group, panelId) {
    const toggles = Array.from(group.querySelectorAll('[data-governance-subtab-toggle]'));
    const scope = group.parentElement || group;
    const panels = Array.from(scope.querySelectorAll(':scope > [data-governance-subtab-panel], :scope > .governance-hub__guides-block[data-governance-subtab-panel]'));
    const allPanels = panels.length > 0
        ? panels
        : Array.from(scope.querySelectorAll('[data-governance-subtab-panel]'));
    const allowed = new Set(toggles.map((tab) => tab.dataset.governanceSubtabToggle || ''));
    const next = allowed.has(panelId) ? panelId : (toggles[0]?.dataset.governanceSubtabToggle || '');

    toggles.forEach((tab) => {
        const isActive = tab.dataset.governanceSubtabToggle === next;
        tab.classList.toggle('governance-hub__subtab--active', isActive);
        tab.setAttribute('aria-selected', String(isActive));
        tab.tabIndex = isActive ? 0 : -1;
    });
    allPanels.forEach((panel) => {
        panel.hidden = panel.dataset.governanceSubtabPanel !== next;
    });

    return next;
}

function initSubtabs(root) {
    root.querySelectorAll('[data-governance-subtabs]').forEach((group) => {
        const toggles = Array.from(group.querySelectorAll('[data-governance-subtab-toggle]'));
        const initial = toggles.find((tab) => tab.getAttribute('aria-selected') === 'true')?.dataset.governanceSubtabToggle
            || toggles[0]?.dataset.governanceSubtabToggle
            || '';
        toggles.forEach((tab) => {
            tab.addEventListener('click', () => {
                const id = tab.dataset.governanceSubtabToggle || '';
                activateSubtabGroup(group, id);
                if (group.dataset.governanceSubtabs === 'guides' && id) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', 'guides');
                    url.hash = `guides-${id}`;
                    window.history.replaceState({}, '', url);
                }
            });
        });
        activateSubtabGroup(group, initial);
    });
}

function activateGuidesSubtab(root, fragment) {
    const id = normalizeGuidesFragment(fragment);
    if (!id) {
        return;
    }
    const group = root.querySelector('[data-governance-subtabs="guides"]');
    if (!group) {
        return;
    }
    activateSubtabGroup(group, id);
}

function initTabs(root) {
    const tabs = Array.from(root.querySelectorAll('[data-governance-tabs] > [data-governance-tab-toggle]'));
    const allPanels = Array.from(root.querySelectorAll('[data-governance-tab-panel]'));

    if (tabs.length === 0 || allPanels.length === 0) {
        return;
    }

    const tabList = root.querySelector('[data-governance-tabs]');
    const fromAttr = tabList?.getAttribute('data-governance-initial-tab') || '';
    const fromFragmentAttr = tabList?.getAttribute('data-governance-initial-fragment') || '';
    const fromQuery = new URLSearchParams(window.location.search).get('tab') || '';
    const aliases = {
        hub: 'advisor',
        workflows: 'guides',
        decisions: 'guides',
        stacks: 'guides',
        kpi: 'guides',
        supplier: 'guides',
    };
    const fragmentAliases = {
        workflows: 'journeys',
        decisions: 'decisions',
        stacks: 'stacks',
        kpi: 'kpi',
        supplier: 'supplier',
    };
    const allowed = new Set(tabs.map((tab) => tab.dataset.governanceTabToggle || ''));
    let initial = aliases[fromQuery] || fromQuery || fromAttr || 'advisor';
    if (!allowed.has(initial)) {
        initial = 'advisor';
    }
    let pendingFragment = window.location.hash.replace(/^#/, '') || fromFragmentAttr || fragmentAliases[fromQuery] || '';

    const activate = (tabId, fragment = '') => {
        tabs.forEach((tab) => {
            const isActive = tab.dataset.governanceTabToggle === tabId;
            tab.classList.toggle('governance-hub__tab--active', isActive);
            tab.setAttribute('aria-selected', String(isActive));
            tab.tabIndex = isActive ? 0 : -1;
        });
        allPanels.forEach((panel) => {
            panel.hidden = panel.dataset.governanceTabPanel !== tabId;
        });

        const url = new URL(window.location.href);
        if (tabId === 'advisor') {
            url.searchParams.delete('tab');
        } else {
            url.searchParams.set('tab', tabId);
        }
        if (fragment && tabId === 'guides') {
            const clean = normalizeGuidesFragment(fragment);
            url.hash = clean ? `guides-${clean}` : '';
        } else if (tabId !== 'guides') {
            url.hash = '';
        }
        window.history.replaceState({}, '', url);

        if (tabId === 'guides' && fragment) {
            window.requestAnimationFrame(() => activateGuidesSubtab(root, fragment));
        }
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            activate(tab.dataset.governanceTabToggle || 'advisor');
        });
    });

    activate(initial, pendingFragment);
}

function initAdvisor(root) {
    const config = readConfig(root);
    const form = root.querySelector('[data-governance-advisor-form]');
    const dqPanel = root.querySelector('[data-governance-dq-panel]');
    const permanentButton = root.querySelector('[data-governance-save-session]');
    const demoButton = root.querySelector('[data-governance-save-demo]');
    const reportLink = root.querySelector('[data-governance-view-report]');
    let activeDemoReportUrl = null;

    initPanelToggles(root);
    initSubtabs(root);
    initTabs(root);
    initPersonas(root, () => {
        if (form) {
            render(root, config);
        }
    });

    if (!form) {
        return;
    }

    const syncDqPanel = () => {
        const state = getState(form);
        if (dqPanel) {
            dqPanel.hidden = state.goal !== 'dq';
        }
    };
    form.addEventListener('change', () => {
        syncDqPanel();
        render(root, config);
    });
    permanentButton?.addEventListener('click', async () => {
        const locale = pickLocale();
        permanentButton.disabled = true;
        try {
            const result = await savePermanentSession(root, config);
            if (activeDemoReportUrl) {
                URL.revokeObjectURL(activeDemoReportUrl);
                activeDemoReportUrl = null;
            }
            setSaveStatus(root, texts[locale].saved);
            if (reportLink instanceof HTMLAnchorElement && result?.reportUrl) {
                reportLink.href = result.reportUrl;
                reportLink.removeAttribute('target');
                reportLink.removeAttribute('rel');
                reportLink.hidden = false;
                reportLink.removeAttribute('aria-disabled');
                setSaveStatus(root, `${texts[locale].saved} ${texts[locale].viewReport}`);
            }
        } catch (error) {
            const isLogin = String(error?.message || '').includes('login-required');
            setSaveStatus(root, isLogin ? texts[locale].loginNeeded : texts[locale].saveFailed);
        } finally {
            permanentButton.disabled = false;
        }
    });
    demoButton?.addEventListener('click', () => {
        const locale = pickLocale();
        const session = saveDemoSession(root, config);
        if (reportLink instanceof HTMLAnchorElement) {
            if (activeDemoReportUrl) {
                URL.revokeObjectURL(activeDemoReportUrl);
            }
            activeDemoReportUrl = demoReportUrl(session);
            reportLink.href = activeDemoReportUrl;
            reportLink.target = '_blank';
            reportLink.rel = 'noopener noreferrer';
            reportLink.hidden = false;
            reportLink.removeAttribute('aria-disabled');
        }
        setSaveStatus(root, texts[locale].demoReportReady || texts[locale].demoSaved);
    });
    syncDqPanel();
    render(root, config);
}

document.querySelectorAll('[data-governance-advisor]').forEach(initAdvisor);
