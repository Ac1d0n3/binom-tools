const texts = {
    de: {
        summary: {
            new: 'Neuaufbau: zuerst Zielbild, Quellen-Scope und Governance Gates klaeren.',
            extend: 'Bestehende Umgebung: erst Fit, Impact und neue Quelle pruefen.',
            help: 'Orientierung: Stories, Ressourcen und Lernpfade passend zur Lage starten.',
        },
        groups: {
            tools: 'Empfohlene Tools',
            suppliers: 'Supplier & Quellen',
            resources: 'Wissen, Stories & Nachweise',
        },
        priority: 'Warum passend',
        open: 'Oeffnen',
    },
    en: {
        summary: {
            new: 'New build: clarify target architecture, source scope, and governance gates first.',
            extend: 'Existing environment: check fit, impact, and the new source first.',
            help: 'Orientation: start with stories, resources, and learning paths that match the situation.',
        },
        groups: {
            tools: 'Recommended tools',
            suppliers: 'Suppliers & sources',
            resources: 'Knowledge, stories & evidence',
        },
        priority: 'Why it fits',
        open: 'Open',
    },
};

const labels = {
    'governance-stack-advisor': {
        group: 'tools',
        icon: 'fa-layer-group',
        title: { de: 'Governance Stack Advisor', en: 'Governance Stack Advisor' },
        reason: {
            de: 'Vergleicht Fabric, Databricks, Snowflake/dbt, SAP und Open Source nach Zielbild, Betrieb und Governance.',
            en: 'Compares Fabric, Databricks, Snowflake/dbt, SAP, and open source by target architecture, operations, and governance.',
        },
        tags: ['stack', 'new', 'learning'],
    },
    'source-scope-builder': {
        group: 'tools',
        icon: 'fa-database',
        title: { de: 'Source Scope Builder', en: 'Source Scope Builder' },
        reason: {
            de: 'Sammelt Objekte, Must-have, Skip, PII, Owner und offene Review-Fragen fuer die erste Ladeentscheidung.',
            en: 'Collects objects, must-haves, skips, PII, owners, and open review questions for the first load decision.',
        },
        tags: ['supplier', 'new', 'extend', 'crm', 'erp', 'hcm', 'collab', 'finance'],
    },
    'kpi-requirements-intake': {
        group: 'tools',
        icon: 'fa-gauge-high',
        title: { de: 'KPI Requirements Intake', en: 'KPI Requirements Intake' },
        reason: {
            de: 'Macht aus Stakeholder-Wuenschen Formel, Grain, Owner, Dimensionen und Akzeptanzbeispiele.',
            en: 'Turns stakeholder needs into formula, grain, owner, dimensions, and acceptance examples.',
        },
        tags: ['kpi', 'new', 'help', 'finance'],
    },
    'mart-design-brief-generator': {
        group: 'tools',
        icon: 'fa-table-cells',
        title: { de: 'Mart Design Brief', en: 'Mart Design Brief' },
        reason: {
            de: 'Fuehrt von KPI-Karten zu Fact-/Dimension-Kandidaten, Grain und offenen Modellierungsentscheidungen.',
            en: 'Moves KPI cards into fact/dimension candidates, grain, and open modelling decisions.',
        },
        tags: ['kpi', 'new', 'extend', 'dq'],
    },
    'pii-dsdr-readiness-checker': {
        group: 'tools',
        icon: 'fa-shield-halved',
        title: { de: 'PII/DSDR Readiness Checker', en: 'PII/DSDR Readiness Checker' },
        reason: {
            de: 'Prueft PII, Freitext, Loesch-/Auskunftskeys, Retention und Access-Risiken vor der Umsetzung.',
            en: 'Checks PII, free text, deletion/access keys, retention, and access risks before implementation.',
        },
        tags: ['pii', 'new', 'extend', 'hcm', 'collab', 'finance'],
    },
    'decision-brief-generator': {
        group: 'tools',
        icon: 'fa-file-signature',
        title: { de: 'Decision Brief Generator', en: 'Decision Brief Generator' },
        reason: {
            de: 'Verdichtet Optionen, Annahmen, Risiken und naechste Schritte in eine entscheidbare Vorlage.',
            en: 'Condenses options, assumptions, risks, and next steps into a decision-ready brief.',
        },
        tags: ['stack', 'supplier', 'kpi', 'new', 'extend'],
    },
    'vendor-learning-path-builder': {
        group: 'tools',
        icon: 'fa-graduation-cap',
        title: { de: 'Vendor Learning Path Builder', en: 'Vendor Learning Path Builder' },
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
            de: 'Klaert, ob die Ergaenzung zur bestehenden Architektur, zum Betrieb und zu Governance-Auflagen passt.',
            en: 'Checks whether the extension fits the existing architecture, operations, and governance constraints.',
        },
        tags: ['stack', 'extend', 'opensource'],
    },
    'impact-effort': {
        group: 'tools',
        icon: 'fa-scale-balanced',
        title: { de: 'Impact Effort Matrix', en: 'Impact Effort Matrix' },
        reason: {
            de: 'Sortiert Ideen nach Nutzen, Aufwand und Risiko, wenn mehrere naechste Schritte konkurrieren.',
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
        tags: ['extend', 'supplier', 'dq', 'unknown'],
    },
    'report-inventory': {
        group: 'tools',
        icon: 'fa-chart-simple',
        title: { de: 'Report Inventory Canvas', en: 'Report Inventory Canvas' },
        reason: {
            de: 'Findet vorhandene Reports, echte Geschaeftsfragen, Owner und wiederkehrende KPI-Kandidaten.',
            en: 'Finds existing reports, real business questions, owners, and recurring KPI candidates.',
        },
        tags: ['help', 'kpi', 'extend'],
    },
    'kpi-definition': {
        group: 'tools',
        icon: 'fa-square-poll-vertical',
        title: { de: 'KPI Definition Card', en: 'KPI Definition Card' },
        reason: {
            de: 'Schaerft eine einzelne Kennzahl, bevor daraus Modell, Measure oder Report entsteht.',
            en: 'Sharpens one metric before it becomes a model, measure, or report.',
        },
        tags: ['kpi', 'help', 'finance'],
    },
    'pii-policy-generator': {
        group: 'tools',
        icon: 'fa-user-shield',
        title: { de: 'PII Policy Generator', en: 'PII Policy Generator' },
        reason: {
            de: 'Erzeugt erste Regeln fuer Klassifizierung, Maskierung, Rollen und Review-Punkte.',
            en: 'Creates first rules for classification, masking, roles, and review points.',
        },
        tags: ['pii', 'hcm', 'collab', 'finance'],
    },
    'pii-recommend-generator': {
        group: 'tools',
        icon: 'fa-lock',
        title: { de: 'PII Recommend Generator', en: 'PII Recommend Generator' },
        reason: {
            de: 'Gibt Feldempfehlungen fuer sensible Daten und Prioritaeten in der Umsetzung.',
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
        tags: ['dq', 'snowflake-dbt', 'opensource'],
    },
    'dbt-dq-rules-generator': {
        group: 'tools',
        icon: 'fa-list-check',
        title: { de: 'dbt DQ Rules Generator', en: 'dbt DQ Rules Generator' },
        reason: {
            de: 'Leitet aus Regeln und Erwartungen konkrete Data-Quality-Checks fuer dbt ab.',
            en: 'Turns rules and expectations into concrete data-quality checks for dbt.',
        },
        tags: ['dq', 'snowflake-dbt', 'opensource'],
    },
    'fabric-pii-governance-pattern-generator': {
        group: 'tools',
        icon: 'fa-window-maximize',
        title: { de: 'Fabric PII Governance Pattern', en: 'Fabric PII Governance Pattern' },
        reason: {
            de: 'Passt, wenn Fabric/Power BI schon gesetzt ist und PII-Gates konkret werden muessen.',
            en: 'Fits when Fabric/Power BI is selected and PII gates need to become concrete.',
        },
        tags: ['fabric', 'pii', 'dq'],
    },
    'databricks-pii-governance-pattern-generator': {
        group: 'tools',
        icon: 'fa-database',
        title: { de: 'Databricks PII Governance Pattern', en: 'Databricks PII Governance Pattern' },
        reason: {
            de: 'Passt fuer Unity Catalog, Grants, Tags, Maskierung und Lakehouse-Governance.',
            en: 'Fits Unity Catalog, grants, tags, masking, and lakehouse governance.',
        },
        tags: ['databricks', 'pii', 'dq'],
    },
    'unity-catalog-governance-generator': {
        group: 'tools',
        icon: 'fa-key',
        title: { de: 'Unity Catalog Governance', en: 'Unity Catalog Governance' },
        reason: {
            de: 'Hilft bei Unity-Catalog-Standards fuer Owner, Tags, Grants und PII-Spalten.',
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
            de: 'Startpunkt fuer Salesforce, HubSpot, SAP, Workday, ServiceNow, SharePoint und weitere Quellen.',
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
        icon: 'fa-route',
        title: { de: 'Governance Stories & Playbooks', en: 'Governance stories & playbooks' },
        reason: {
            de: 'Gut, wenn schon vieles vorhanden ist und du Beispiele, Stories und Vorgehen brauchst.',
            en: 'Useful when much already exists and you need examples, stories, and next actions.',
        },
        tags: ['help', 'learning', 'kpi', 'dq', 'supplier'],
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
            de: 'Workday, SuccessFactors und Personio: Organisation, Worker, Abwesenheit, Compensation und hohe PII-Prioritaet.',
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

    if (state.goal === 'supplier' && item.group === 'suppliers') {
        score += 4;
    }

    if (state.scenario === 'help' && item.group === 'resources') {
        score += 3;
    }

    if (state.scenario === 'new' && ['governance-stack-advisor', 'source-scope-builder', 'kpi-requirements-intake'].includes(item.id)) {
        score += 2;
    }

    if (state.scenario === 'extend' && ['architecture-fit', 'impact-effort', 'meta-export-generator'].includes(item.id)) {
        score += 3;
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
    const recommendations = buildRecommendations(state, config);
    const grouped = recommendations.reduce((groups, item) => {
        const group = item.group || 'tools';
        groups[group] = groups[group] || [];
        groups[group].push(item);
        return groups;
    }, {});

    summary.textContent = copy.summary[state.scenario] || copy.summary.new;
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

function initAdvisor(root) {
    const config = readConfig(root);
    const form = root.querySelector('[data-governance-advisor-form]');

    if (!form) {
        return;
    }

    form.addEventListener('change', () => render(root, config));
    render(root, config);
}

document.querySelectorAll('[data-governance-advisor]').forEach(initAdvisor);
