import { buildGuidance, normalizeOrgContext, normalizeRegulation, platformPreferenceHint, preferredPlatforms, preferredProductIds, stackBuilderContextBanner } from './advisor-guidance.js';
import { contentCardCandidates, matchesContentWhen, normalizeContentCard } from './advisor-content-cards.js';
import { openSharedModal } from '../../../resources/js/shared/modal.js';
import {
    derivePlatformTags,
    mountStackBuilder,
    normalizeSelection,
    preferredProductIdsForStartingPoint,
    readCustomStack,
    readSavedStacksLocal,
    readStartingPointProduct,
    readStartingPointReport,
    saveNamedStackLocal,
    startingPointStackBanner,
    summarizeSelection,
    writeCustomStack,
    writeSavedStacksLocal,
} from './stack-builder.js';

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
            certs: 'Nachweise & Zertifikate',
            gaps: 'Lücken & Brücken',
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
            new: {
                default: 'Welche Zielplattform und welche Quellfamilie müssen für den Start entschieden werden?',
                stack: 'Welcher Ziel-Stack soll zuerst bewertet werden?',
                supplier: 'Welche Quellfamilie soll zuerst angebunden werden?',
                kpi: 'Welche erste Kennzahl oder welcher Mart soll definiert werden?',
                pii: 'Welche Domäne hat den höchsten PII-/DSDR-Druck?',
                dq: 'Welches DQ-Problem und welche Schicht sollen zuerst geklärt werden?',
                learning: 'Welcher Stack braucht zuerst Lernpfade oder Zertifikate?',
            },
            extend: {
                default: 'Welcher Stack ist bereits gesetzt, und welche neue Quelle oder Lücke soll ergänzt werden?',
                stack: 'Welcher bestehende Stack wird erweitert — und wohin?',
                supplier: 'Welche neue Quelle oder Domäne kommt dazu?',
                kpi: 'Welche KPI oder welcher Mart fehlt im bestehenden Setup?',
                pii: 'Welche bestehende Domäne braucht jetzt PII-/Access-Gates?',
                dq: 'Welches bekannte DQ-Problem oder Gate soll nachgezogen werden?',
                learning: 'Für welchen Stack brauchst du Orientierung oder Zertifikate?',
            },
            help: {
                default: 'Wo existiert schon Material, und in welchem Stack sollen Stories, Links oder Vorlagen helfen?',
                stack: 'In welchem Stack suchst du Stories, Vergleiche oder Vorlagen?',
                supplier: 'Zu welcher Domäne brauchst du Beispiele und Playbooks?',
                kpi: 'Welche Kennzahl oder welcher Report braucht Orientierung?',
                pii: 'Wo brauchst du PII-/Compliance-Beispiele und Nachweise?',
                dq: 'Welche DQ-Schicht oder Fehlerklasse soll erklärt werden?',
                learning: 'Welcher Lern- oder Zertifikatspfad fehlt noch?',
            },
        },
        domainLabel: {
            new: 'Quelltyp',
            extend: 'Quelle / Domäne',
            help: 'Domäne',
            dq: 'Betroffene Domäne',
        },
        platformLabel: {
            new: 'Ziel-Stack',
            extend: 'Aktueller Stack',
            help: 'Stack',
            dq: 'Stack für DQ',
        },
        contextFiltered: 'Gefiltert',
        contextClear: 'Filter löschen',
        filterEmpty: 'Keine Treffer für diesen Filter.',
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
            certs: 'Evidence & certifications',
            gaps: 'Gaps & bridges',
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
            new: {
                default: 'Which target platform and source family need to be decided for the start?',
                stack: 'Which target stack should be evaluated first?',
                supplier: 'Which source family should be onboarded first?',
                kpi: 'Which first metric or mart should be defined?',
                pii: 'Which domain has the highest PII/DSDR pressure?',
                dq: 'Which DQ problem and layer should be clarified first?',
                learning: 'Which stack needs learning paths or certifications first?',
            },
            extend: {
                default: 'Which stack is already in use, and which new source or gap is being added?',
                stack: 'Which existing stack is being extended — and toward what?',
                supplier: 'Which new source or domain is being added?',
                kpi: 'Which KPI or mart is missing in the current setup?',
                pii: 'Which existing domain now needs PII/access gates?',
                dq: 'Which known DQ issue or gate should be tightened?',
                learning: 'Which stack needs orientation or certifications?',
            },
            help: {
                default: 'Where does material already exist, and which stack needs stories, links, or templates?',
                stack: 'Which stack needs stories, comparisons, or templates?',
                supplier: 'Which domain needs examples and playbooks?',
                kpi: 'Which metric or report needs orientation?',
                pii: 'Where do you need PII/compliance examples and evidence?',
                dq: 'Which DQ layer or issue class needs explanation?',
                learning: 'Which learning or certification path is still missing?',
            },
        },
        domainLabel: {
            new: 'Source type',
            extend: 'Source / domain',
            help: 'Domain',
            dq: 'Affected domain',
        },
        platformLabel: {
            new: 'Target stack',
            extend: 'Current stack',
            help: 'Stack',
            dq: 'Stack for DQ',
        },
        contextFiltered: 'Filtered',
        contextClear: 'Clear filter',
        filterEmpty: 'No matches for this filter.',
    },
};

const ROLE_PREFERRED_GOALS = {
    steward: ['dq', 'supplier'],
    owner: ['pii', 'learning'],
    'product-owner': ['kpi', 'stack'],
    architect: ['stack', 'supplier'],
    custodian: ['stack', 'dq'],
    consumer: ['kpi', 'learning'],
};

const HUB_CONTEXT_STORAGE_KEY = 'binom-governance-hub-context';
const CONTEXT_LABELS = {
    de: {
        steward: 'Data Steward',
        owner: 'Data Owner',
        'product-owner': 'Data Product Owner',
        architect: 'Data Architect',
        custodian: 'Data Custodian',
        consumer: 'Data Consumer',
        stack: 'Stack',
        supplier: 'Quelle',
        kpi: 'KPI',
        pii: 'PII',
        dq: 'DQ',
        learning: 'Lernen',
        crm: 'CRM',
        erp: 'ERP',
        hcm: 'HCM',
        collab: 'Collab',
        finance: 'Finance',
        fabric: 'Fabric',
        databricks: 'Databricks',
        'snowflake-dbt': 'Snowflake/dbt',
        sap: 'SAP',
        opensource: 'Open Source',
        custom: 'Eigener Stack',
        startup: 'Startup',
        midmarket: 'Midmarket',
        enterprise: 'Enterprise',
        'bank-finance': 'Bank/Finance',
        'public-sector': 'Öffentlicher Sektor',
        'gdpr-heavy': 'DSGVO-stark',
        regulated: 'Reguliert',
    },
    en: {
        steward: 'Data Steward',
        owner: 'Data Owner',
        'product-owner': 'Data Product Owner',
        architect: 'Data Architect',
        custodian: 'Data Custodian',
        consumer: 'Data Consumer',
        stack: 'Stack',
        supplier: 'Source',
        kpi: 'KPI',
        pii: 'PII',
        dq: 'DQ',
        learning: 'Learning',
        crm: 'CRM',
        erp: 'ERP',
        hcm: 'HCM',
        collab: 'Collab',
        finance: 'Finance',
        fabric: 'Fabric',
        databricks: 'Databricks',
        'snowflake-dbt': 'Snowflake/dbt',
        sap: 'SAP',
        opensource: 'Open source',
        custom: 'Custom stack',
        startup: 'Startup',
        midmarket: 'Mid-market',
        enterprise: 'Enterprise',
        'bank-finance': 'Bank/finance',
        'public-sector': 'Public sector',
        'gdpr-heavy': 'GDPR-heavy',
        regulated: 'Regulated',
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
        tags: ['stack', 'new', 'learning', 'fabric', 'databricks', 'snowflake-dbt', 'sap', 'opensource', 'architect', 'custodian', 'custom'],
    },
    'custom-stack-builder': {
        group: 'tools',
        icon: 'fa-cubes',
        title: { de: 'Eigenen Stack bauen', en: 'Custom Stack Builder' },
        reason: {
            de: 'Dokumentiert den Ist-/Ziel-Stack je Funktion und leitet Platform-Tags für Advisor und Filter ab.',
            en: 'Documents the current/target stack by function and derives platform tags for advisor and filters.',
        },
        tags: ['stack', 'new', 'extend', 'help', 'custom', 'fabric', 'databricks', 'snowflake-dbt', 'opensource', 'architect', 'custodian'],
    },
    'source-scope-builder': {
        group: 'tools',
        icon: 'fa-database',
        title: { de: 'Quellen-Scope festlegen', en: 'Source Scope Builder' },
        reason: {
            de: 'Sammelt Objekte, Must-have, Skip, PII, Owner und offene Review-Fragen für die erste Ladeentscheidung.',
            en: 'Collects objects, must-haves, skips, PII, owners, and open review questions for the first load decision.',
        },
        tags: ['supplier', 'new', 'extend', 'crm', 'erp', 'hcm', 'collab', 'finance', 'architect', 'steward'],
    },
    'kpi-requirements-intake': {
        group: 'tools',
        icon: 'fa-gauge-high',
        title: { de: 'KPI-Anforderungen erfassen', en: 'KPI Requirements Intake' },
        reason: {
            de: 'Macht aus Stakeholder-Wünschen Formel, Grain, Owner, Dimensionen und Akzeptanzbeispiele.',
            en: 'Turns stakeholder needs into formula, grain, owner, dimensions, and acceptance examples.',
        },
        tags: ['kpi', 'new', 'help', 'finance', 'product-owner', 'consumer'],
    },
    'mart-design-brief-generator': {
        group: 'tools',
        icon: 'fa-table-cells',
        title: { de: 'Mart Design Brief erstellen', en: 'Mart Design Brief' },
        reason: {
            de: 'Führt von KPI-Karten zu Fact-/Dimension-Kandidaten, Grain und offenen Modellierungsentscheidungen.',
            en: 'Moves KPI cards into fact/dimension candidates, grain, and open modelling decisions.',
        },
        tags: ['kpi', 'new', 'extend', 'dq', 'mart_quality_gate', 'product-owner', 'architect'],
    },
    'pii-dsdr-readiness-checker': {
        group: 'tools',
        icon: 'fa-shield-halved',
        title: { de: 'PII/DSDR Readiness prüfen', en: 'PII/DSDR Readiness Checker' },
        reason: {
            de: 'Prüft PII, Freitext, Lösch-/Auskunftskeys, Retention und Access-Risiken vor der Umsetzung.',
            en: 'Checks PII, free text, deletion/access keys, retention, and access risks before implementation.',
        },
        tags: ['pii', 'new', 'extend', 'hcm', 'collab', 'finance', 'owner'],
    },
    'decision-brief-generator': {
        group: 'tools',
        icon: 'fa-file-signature',
        title: { de: 'Entscheidungsvorlage erstellen', en: 'Decision Brief Generator' },
        reason: {
            de: 'Verdichtet Optionen, Annahmen, Risiken und nächste Schritte in eine entscheidbare Vorlage.',
            en: 'Condenses options, assumptions, risks, and next steps into a decision-ready brief.',
        },
        tags: ['stack', 'supplier', 'kpi', 'new', 'extend', 'architect', 'product-owner'],
    },
    'vendor-learning-path-builder': {
        group: 'tools',
        icon: 'fa-graduation-cap',
        title: { de: 'Lern- und Zertifizierungspfad planen', en: 'Vendor Learning Path Builder' },
        reason: {
            de: 'Ordnet Hersteller-Doku, Lernpfade und Zertifikate passend zu Stack und Rolle.',
            en: 'Maps vendor docs, learning paths, and certifications to stack and role.',
        },
        tags: ['learning', 'help', 'new', 'fabric', 'databricks', 'snowflake-dbt', 'sap', 'owner', 'architect', 'consumer'],
    },
    'architecture-fit': {
        group: 'tools',
        icon: 'fa-diagram-project',
        title: { de: 'Architecture Fit', en: 'Architecture Fit' },
        reason: {
            de: 'Klärt, ob die Ergänzung zur bestehenden Architektur, zum Betrieb und zu Governance-Auflagen passt.',
            en: 'Checks whether the extension fits the existing architecture, operations, and governance constraints.',
        },
        tags: ['stack', 'extend', 'opensource', 'fabric', 'databricks', 'snowflake-dbt', 'sap', 'architect'],
    },
    'impact-effort': {
        group: 'tools',
        icon: 'fa-scale-balanced',
        title: { de: 'Impact Effort Matrix', en: 'Impact Effort Matrix' },
        reason: {
            de: 'Sortiert Ideen nach Nutzen, Aufwand und Risiko, wenn mehrere nächste Schritte konkurrieren.',
            en: 'Ranks ideas by value, effort, and risk when several next steps compete.',
        },
        tags: ['extend', 'help', 'kpi', 'supplier', 'product-owner', 'architect'],
    },
    'meta-export-generator': {
        group: 'tools',
        icon: 'fa-file-export',
        title: { de: 'Meta Export Generator', en: 'Meta Export Generator' },
        reason: {
            de: 'Hilft bei existierenden Systemen, Tabellen, Spalten und Ownership als Entscheidungsinput zu sammeln.',
            en: 'Helps collect existing systems, tables, columns, and ownership as decision input.',
        },
        tags: ['extend', 'supplier', 'dq', 'unknown', 'health_check', 'source', 'raw', 'fabric', 'databricks', 'snowflake-dbt', 'steward', 'architect'],
    },
    'report-inventory': {
        group: 'tools',
        icon: 'fa-chart-simple',
        title: { de: 'Report Inventory Canvas', en: 'Report Inventory Canvas' },
        reason: {
            de: 'Findet vorhandene Reports, echte Geschäftsfragen, Owner und wiederkehrende KPI-Kandidaten.',
            en: 'Finds existing reports, real business questions, owners, and recurring KPI candidates.',
        },
        tags: ['help', 'kpi', 'extend', 'dq', 'report_stabilization', 'bi', 'product-owner'],
    },
    'kpi-definition': {
        group: 'tools',
        icon: 'fa-square-poll-vertical',
        title: { de: 'KPI Definition Card', en: 'KPI Definition Card' },
        reason: {
            de: 'Schärft eine einzelne Kennzahl, bevor daraus Modell, Measure oder Report entsteht.',
            en: 'Sharpens one metric before it becomes a model, measure, or report.',
        },
        tags: ['kpi', 'help', 'finance', 'product-owner'],
    },
    'pii-policy-generator': {
        group: 'tools',
        icon: 'fa-user-shield',
        title: { de: 'PII Policy Generator', en: 'PII Policy Generator' },
        reason: {
            de: 'Erzeugt erste Regeln für Klassifizierung, Maskierung, Rollen und Review-Punkte.',
            en: 'Creates first rules for classification, masking, roles, and review points.',
        },
        tags: ['pii', 'hcm', 'collab', 'finance', 'owner'],
    },
    'pii-recommend-generator': {
        group: 'tools',
        icon: 'fa-lock',
        title: { de: 'PII Recommend Generator', en: 'PII Recommend Generator' },
        reason: {
            de: 'Gibt Feldempfehlungen für sensible Daten und Prioritäten in der Umsetzung.',
            en: 'Suggests sensitive-field handling and priorities for implementation.',
        },
        tags: ['pii', 'supplier', 'hcm', 'collab', 'owner'],
    },
    'schema-yml-editor': {
        group: 'tools',
        icon: 'fa-code',
        title: { de: 'Schema YAML Editor', en: 'Schema YAML Editor' },
        reason: {
            de: 'Bringt beschriebene Modelle, Tests und Metadaten in eine dbt-nahe Arbeitsform.',
            en: 'Turns described models, tests, and metadata into a dbt-friendly working format.',
        },
        tags: ['dq', 'snowflake-dbt', 'opensource', 'transform', 'mart', 'semantic', 'steward'],
    },
    'dbt-dq-macro-generator': {
        group: 'tools',
        icon: 'fa-code-branch',
        title: { de: 'dbt DQ Macro Generator', en: 'dbt DQ Macro Generator' },
        reason: {
            de: 'Erstellt die technische Basis für wiederverwendbare DQ-Checks und Governance-Makros.',
            en: 'Creates the technical base for reusable DQ checks and governance macros.',
        },
        tags: ['dq', 'snowflake-dbt', 'opensource', 'transform', 'business_rule', 'referential_integrity', 'steward'],
    },
    'dbt-dq-rules-generator': {
        group: 'tools',
        icon: 'fa-list-check',
        title: { de: 'dbt DQ Rules Generator', en: 'dbt DQ Rules Generator' },
        reason: {
            de: 'Leitet aus Regeln und Erwartungen konkrete Data-Quality-Checks für dbt ab.',
            en: 'Turns rules and expectations into concrete data-quality checks for dbt.',
        },
        tags: ['dq', 'snowflake-dbt', 'opensource', 'completeness', 'duplicates', 'freshness', 'value_range', 'referential_integrity', 'business_rule', 'steward'],
    },
    'dbt-dq-history-generator': {
        group: 'tools',
        icon: 'fa-clock-rotate-left',
        title: { de: 'DQ History Generator', en: 'DQ History Generator' },
        reason: {
            de: 'Plant Historie und Monitoring für DQ-Findings, Trends und wiederkehrende Gates.',
            en: 'Plans history and monitoring for DQ findings, trends, and recurring gates.',
        },
        tags: ['dq', 'health_check', 'known_issue', 'freshness', 'report_stabilization', 'steward'],
    },
    'fabric-pii-governance-pattern-generator': {
        group: 'tools',
        icon: 'fa-window-maximize',
        title: { de: 'Fabric PII Governance Pattern', en: 'Fabric PII Governance Pattern' },
        reason: {
            de: 'Passt, wenn Fabric/Power BI schon gesetzt ist und PII-Gates konkret werden müssen.',
            en: 'Fits when Fabric/Power BI is selected and PII gates need to become concrete.',
        },
        tags: ['fabric', 'pii', 'dq', 'owner', 'steward', 'custodian'],
    },
    'databricks-pii-governance-pattern-generator': {
        group: 'tools',
        icon: 'fa-database',
        title: { de: 'Databricks PII Governance Pattern', en: 'Databricks PII Governance Pattern' },
        reason: {
            de: 'Passt für Unity Catalog, Grants, Tags, Maskierung und Lakehouse-Governance.',
            en: 'Fits Unity Catalog, grants, tags, masking, and lakehouse governance.',
        },
        tags: ['databricks', 'pii', 'dq', 'owner', 'steward', 'custodian'],
    },
    'unity-catalog-governance-generator': {
        group: 'tools',
        icon: 'fa-key',
        title: { de: 'Unity Catalog Governance', en: 'Unity Catalog Governance' },
        reason: {
            de: 'Hilft bei Unity-Catalog-Standards für Owner, Tags, Grants und PII-Spalten.',
            en: 'Helps with Unity Catalog standards for owners, tags, grants, and PII columns.',
        },
        tags: ['databricks', 'pii', 'dq', 'architect', 'steward'],
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
        tags: ['supplier', 'new', 'extend', 'crm', 'erp', 'hcm', 'collab', 'finance', 'architect', 'steward'],
    },
    resources: {
        group: 'resources',
        icon: 'fa-book-open',
        title: { de: 'Vendor Resources & Zertifikate', en: 'Vendor resources & certifications' },
        reason: {
            de: 'Offizielle Docs, Governance-Seiten, Lernpfade, Cloud-Residency und Zertifikate sammeln.',
            en: 'Collect official docs, governance pages, learning paths, cloud residency, and certifications.',
        },
        tags: ['learning', 'help', 'stack', 'fabric', 'databricks', 'snowflake-dbt', 'sap', 'owner', 'architect'],
    },
    compliance: {
        group: 'resources',
        icon: 'fa-scale-balanced',
        title: { de: 'Compliance Hub', en: 'Compliance Hub' },
        reason: {
            de: 'Nutzen, wenn PII, DSDR, Retention, Access oder Nachweise zur Entscheidung gehoeren.',
            en: 'Use when PII, DSDR, retention, access, or evidence are part of the decision.',
        },
        tags: ['pii', 'finance', 'hcm', 'collab', 'new', 'extend', 'owner'],
    },
    playbooks: {
        group: 'resources',
        icon: 'fa-book',
        title: { de: 'Governance Stories & Playbooks', en: 'Governance stories & playbooks' },
        reason: {
            de: 'Gut, wenn schon vieles vorhanden ist und du Beispiele, Stories und Vorgehen brauchst.',
            en: 'Useful when much already exists and you need examples, stories, and next actions.',
        },
        tags: ['help', 'learning', 'kpi', 'dq', 'supplier', 'steward', 'product-owner'],
    },
    learningPaths: {
        group: 'resources',
        icon: 'fa-route',
        title: { de: 'Learning Paths', en: 'Learning Paths' },
        reason: {
            de: 'Geführte Journeys (PII, DQ, Warehouse, Foundations) — enden im passenden Sprint-Plan.',
            en: 'Guided journeys (PII, DQ, warehouse, foundations) — each ends in a matching sprint plan.',
        },
        tags: ['help', 'learning', 'new', 'pii', 'dq', 'stack', 'owner', 'steward', 'architect'],
    },
    roles: {
        group: 'resources',
        icon: 'fa-user-group',
        title: { de: 'Roles Hub', en: 'Roles Hub' },
        reason: {
            de: 'Decision Rights klären: Steward, Owner, Architect, Custodian, Consumer.',
            en: 'Clarify decision rights: steward, owner, architect, custodian, consumer.',
        },
        tags: ['help', 'learning', 'new', 'kpi', 'architect', 'steward', 'owner', 'product-owner', 'custodian', 'consumer'],
    },
    sprintPlanner: {
        group: 'resources',
        icon: 'fa-calendar-week',
        title: { de: 'Sprint Planner Templates', en: 'Sprint Planner templates' },
        reason: {
            de: 'Lern- oder Umsetzungsplan aus einer Vorlage starten und im Team abarbeiten.',
            en: 'Start a learning or delivery plan from a template and work it as a team.',
        },
        tags: ['help', 'learning', 'new', 'extend', 'dq', 'stack', 'product-owner', 'architect'],
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

function getActiveRole(root) {
    const active = root.querySelector('[data-governance-persona].governance-hub__persona--active');
    const role = active?.dataset.governancePersona || '';
    return !role || role === 'all' ? '' : role;
}

function getState(form, root = null) {
    const formData = new FormData(form);
    const scope = root || form?.closest('[data-governance-advisor]') || document;

    return {
        scenario: formData.get('scenario') || 'new',
        goal: formData.get('goal') || 'stack',
        domain: formData.get('domain') || 'unknown',
        platform: formData.get('platform') || 'unknown',
        orgContext: normalizeOrgContext(String(formData.get('orgContext') || 'unknown')),
        regulationPressure: normalizeRegulation(String(formData.get('regulationPressure') || 'low')),
        role: getActiveRole(scope),
        dqMode: formData.get('dqMode') || 'health_check',
        dqLayer: formData.get('dqLayer') || 'source',
        dqIssues: formData.getAll('dqIssues[]').map(String),
    };
}

/**
 * @param {Record<string, unknown>} config
 * @returns {Record<string, string>}
 */
function guidanceLinksFromConfig(config) {
    const tools = config.links?.tools || {};
    const hubs = config.links?.hubs || {};
    const guidance = config.links?.guidance || {};

    return {
        roadmap: guidance.roadmap || '',
        eightPillars: guidance.eightPillars || '',
        learningPaths: hubs.learningPaths || '',
        cdmp: guidance.cdmp || '',
        cippE: guidance.cippE || '',
        iso27001: guidance.iso27001 || '',
        dsbDe: guidance.dsbDe || '',
        vendorLearningPathBuilder: tools['vendor-learning-path-builder'] || '',
        governanceStackAdvisor: tools['governance-stack-advisor'] || '',
        customStackBuilder: tools['custom-stack-builder'] || '',
        architectureFit: tools['architecture-fit'] || '',
        impactEffort: tools['impact-effort'] || '',
        kpiRequirementsIntake: tools['kpi-requirements-intake'] || '',
        piiDsdrReadiness: tools['pii-dsdr-readiness-checker'] || '',
        playbooks: hubs.playbooks || '',
        promptStudio: guidance.promptStudio || '',
        aiSanitizer: guidance.aiSanitizer || '',
        toolsOverview: guidance.toolsOverview || '',
        qlikSetAnalysis: guidance.qlikSetAnalysis || '',
        compliance: hubs.compliance || '',
        bridgeSolutionStory: guidance.bridgeSolutionStory || '',
        guidesStacks: guidance.guidesStacks || '',
        metadataCatalogStory: guidance.metadataCatalogStory || '',
        unityCatalogTool: guidance.unityCatalogTool || tools['unity-catalog-governance-generator'] || '',
        metaExportTool: guidance.metaExportTool || tools['meta-export-generator'] || '',
        dora: guidance.dora || '',
        nis2: guidance.nis2 || '',
        bsiC5: guidance.bsiC5 || '',
    };
}

/**
 * @param {ReturnType<typeof getState>} state
 * @param {Record<string, unknown>} config
 */
function guidanceRecommendations(state, config) {
    const { certs, gaps } = buildGuidance(state, guidanceLinksFromConfig(config));
    return [...certs, ...gaps].map((item) => ({
        ...item,
        kind: 'guidance',
        score: item.score ?? 50,
    }));
}

function resolveFollowup(copy, scenario, goal) {
    const byScenario = copy.followup?.[scenario] || copy.followup?.new;
    if (typeof byScenario === 'string') {
        return byScenario;
    }
    return byScenario?.[goal] || byScenario?.default || '';
}

function resolveDomainLabel(copy, scenario, goal) {
    if (goal === 'dq' && copy.domainLabel?.dq) {
        return copy.domainLabel.dq;
    }
    return copy.domainLabel?.[scenario] || copy.domainLabel?.new || '';
}

function resolvePlatformLabel(copy, scenario, goal) {
    if (goal === 'dq' && copy.platformLabel?.dq) {
        return copy.platformLabel.dq;
    }
    return copy.platformLabel?.[scenario] || copy.platformLabel?.new || '';
}

function itemUrl(item, config) {
    if (item.kind === 'hub') {
        return config.links?.hubs?.[item.id] || '#';
    }

    return config.links?.tools?.[item.id] || '#';
}

function scoreItem(item, state, boostToolIds = []) {
    const tags = new Set(item.tags || []);
    let score = 0;
    const preferred = preferredPlatforms(state.orgContext, state.regulationPressure);

    if (boostToolIds.includes(item.id)) {
        score += 12;
    }

    if (tags.has(state.goal)) {
        score += 8;
    }

    if (tags.has(state.scenario)) {
        score += 5;
    }

    if (state.domain !== 'unknown' && tags.has(state.domain)) {
        score += 7;
    }

    if (state.platform === 'custom') {
        const derived = derivePlatformTags(readCustomStack());
        if (derived.some((tag) => tags.has(tag)) || tags.has('custom')) {
            score += 7;
        }
        if (item.id === 'custom-stack-builder' || item.id === 'governance-stack-advisor') {
            score += 5;
        }
    } else if (state.platform !== 'unknown' && tags.has(state.platform)) {
        score += 7;
    } else if (state.platform === 'unknown' && preferred.some((id) => tags.has(id))) {
        score += 4;
    }

    if (state.role && tags.has(state.role)) {
        score += 6;
    }

    if (state.orgContext && state.orgContext !== 'unknown' && tags.has(state.orgContext)) {
        score += 3;
    }

    if (state.regulationPressure && state.regulationPressure !== 'low') {
        if (tags.has(state.regulationPressure) || tags.has('pii') || tags.has('compliance')) {
            score += 3;
        }
        if (['governance-stack-advisor', 'custom-stack-builder', 'pii-dsdr-readiness-checker'].includes(item.id)) {
            score += 2;
        }
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

    if (state.goal === 'stack' && ['governance-stack-advisor', 'custom-stack-builder'].includes(item.id)) {
        score += 3;
    }

    return score;
}

function buildRecommendations(state, config) {
    const links = guidanceLinksFromConfig(config);
    const { startToolIds } = buildGuidance(state, links);
    const tools = Object.entries(labels).map(([id, item]) => ({ ...item, id, kind: 'tool' }));
    const hubs = Object.entries(hubItems).map(([id, item]) => ({ ...item, id, kind: 'hub' }));
    const domainItem = state.domain === 'unknown'
        ? []
        : [{ ...sourceItems[state.domain], id: 'suppliers', kind: 'hub', fixed: true }];
    const contentItems = contentCardCandidates(config)
        .filter((item) => matchesContentWhen(item.when, state))
        .map((item) => normalizeContentCard(item));
    const candidates = [...tools, ...hubs, ...domainItem, ...contentItems]
        .map((item, index) => ({
            ...item,
            url: item.url || itemUrl(item, config),
            score: item.fixed
                ? 99
                : scoreItem(item, state, startToolIds) + (Number(item.baseScore) || 0),
            order: index,
        }))
        .filter((item) => item.url && item.url !== '#' && item.score > 0)
        .sort((a, b) => b.score - a.score || a.order - b.order);

    const seen = new Set();

    const scored = candidates.filter((item) => {
        const titleEn = item.title && typeof item.title === 'object' ? item.title.en : '';
        const key = `${item.group}:${titleEn}:${item.url}`;

        if (seen.has(key)) {
            return false;
        }

        seen.add(key);
        return true;
    }).slice(0, 9);

    const guidance = guidanceRecommendations(state, config).filter((item) => item.url && item.url !== '#');

    return [...scored, ...guidance];
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
    const state = getState(form, root);
    const recommendations = buildRecommendations(state, config);
    const guidance = buildGuidance(state, guidanceLinksFromConfig(config));
    const startingPoint = readStartingPointReport();

    return {
        title: String(title?.value || '').trim() || 'Governance Discovery',
        companyName: String(company?.value || '').trim(),
        projectName: String(project?.value || '').trim(),
        scenario: state.scenario,
        status: 'draft',
        currentStep: 'advisor',
        payload: {
            advisor: state,
            guidance: {
                certs: serializableRecommendations(guidance.certs, locale),
                gaps: serializableRecommendations(guidance.gaps, locale),
                stackNote: guidance.stackNote
                    ? serializableRecommendations([guidance.stackNote], locale)[0]
                    : null,
            },
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
            ...(startingPoint ? { startingPoint } : {}),
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
        orgContext: 'bank-finance',
        regulationPressure: 'regulated',
        dqMode: 'report_stabilization',
        dqLayer: 'bi',
        dqIssues: ['freshness', 'business_rule', 'completeness'],
    };
    const recommendations = serializableRecommendations(buildRecommendations(advisor, config), locale);
    const guidance = buildGuidance(advisor, guidanceLinksFromConfig(config));

    return {
        ...base,
        title: 'Demo: Finance Governance Discovery',
        companyName: base.companyName || 'Acme GmbH',
        projectName: base.projectName || 'Management Reporting 2026',
        scenario: advisor.scenario,
        status: 'draft',
        payload: {
            advisor,
            guidance: {
                certs: serializableRecommendations(guidance.certs, locale),
                gaps: serializableRecommendations(guidance.gaps, locale),
                stackNote: guidance.stackNote
                    ? serializableRecommendations([guidance.stackNote], locale)[0]
                    : null,
            },
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
            startingPoint: {
                product: 'fabric',
                productLabel: 'Microsoft Fabric',
                title: 'Finance Governance Starting Point',
                firstUseCase: 'Stabiler Monatsabschluss mit klarer Ownership',
                decisionStatus: 'readyForProofOfValue',
                preferredStartingPattern: 'Purview + Fabric Domains als Start, dann Lakehouse-Mart',
                decisionRationale: 'Bestehende Microsoft-Lizenzen und Power-BI-Nutzung sprechen für Fabric als Startplattform.',
                noRegretNextStep: 'Domain-Ownership und KPI Net Revenue als Pilot festziehen',
                knownGaps: ['Catalog-Ownership unklar', 'Retention-Regel fehlt'],
                openQuestions: ['Wer genehmigt PII-Maskierung?', 'Cutover-Regel Monatsabschluss'],
                blockers: [],
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
    const startingPoint = payload.startingPoint || {};

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
                ['Organisationskontext', advisor.orgContext],
                ['Regulierungsdruck', advisor.regulationPressure],
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
            <h2>Nachweise &amp; Zertifikate</h2>
            ${(() => {
                const certs = Array.isArray(payload.guidance?.certs) ? payload.guidance.certs : [];
                return certs.map((item) => `
                <article>
                    <strong>${escapeHtml(item.title || '-')}</strong>
                    <p>certs</p>
                    <p>${escapeHtml(item.reason || '')}</p>
                    ${item.url ? `<a href="${escapeHtml(absoluteUrl(item.url))}">Öffnen</a>` : ''}
                </article>
            `).join('') || '<p>Noch keine Nachweis-Hinweise gespeichert.</p>';
            })()}
        </section>
        <section>
            <h2>Lücken &amp; Brücken</h2>
            ${(() => {
                const gaps = Array.isArray(payload.guidance?.gaps) ? payload.guidance.gaps : [];
                const withoutStack = gaps.filter((item) => item.id !== 'stack-note');
                return withoutStack.map((item) => `
                <article>
                    <strong>${escapeHtml(item.title || '-')}</strong>
                    <p>gaps</p>
                    <p>${escapeHtml(item.reason || '')}</p>
                    ${item.url ? `<a href="${escapeHtml(absoluteUrl(item.url))}">Öffnen</a>` : ''}
                </article>
            `).join('') || '<p>Noch keine Lücken-/Brücken-Hinweise gespeichert.</p>';
            })()}
        </section>
        ${payload.guidance?.stackNote ? `
        <section>
            <h2>Stack-Begründung</h2>
            <article>
                <strong>${escapeHtml(payload.guidance.stackNote.title || '-')}</strong>
                <p>${escapeHtml(payload.guidance.stackNote.reason || '')}</p>
                ${payload.guidance.stackNote.url ? `<a href="${escapeHtml(absoluteUrl(payload.guidance.stackNote.url))}">Stacks &amp; Guides öffnen</a>` : ''}
            </article>
        </section>
        ` : ''}
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
            <h2>Starting-Point Decision</h2>
            <dl>${factsHtml([
                ['Produkt', startingPoint.productLabel || startingPoint.product],
                ['Titel', startingPoint.title],
                ['Status', startingPoint.decisionStatus],
                ['Startmuster', startingPoint.preferredStartingPattern],
                ['Begründung', startingPoint.decisionRationale],
                ['No-regret Next Step', startingPoint.noRegretNextStep],
                ['Lücken', startingPoint.knownGaps],
                ['Offene Fragen', startingPoint.openQuestions],
                ['Blocker', startingPoint.blockers],
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

/**
 * Soft-reorder platform options: preferred first, keep current value, badge labels.
 * @param {HTMLFormElement} form
 * @param {ReturnType<typeof getState>} state
 * @param {'de' | 'en'} locale
 */
function applyPlatformPreferences(form, state, locale) {
    const select = form.querySelector('[data-governance-platform-select], select[name="platform"]');
    const hint = form.closest('[data-governance-advisor]')?.querySelector('[data-governance-platform-hint]')
        || form.parentElement?.querySelector('[data-governance-platform-hint]');
    if (!(select instanceof HTMLSelectElement)) {
        return;
    }

    const preferred = preferredPlatforms(state.orgContext, state.regulationPressure);
    const preferredSet = new Set(preferred);
    const current = select.value;
    const options = Array.from(select.options);
    const pinned = ['unknown', 'custom'];
    const byValue = new Map(options.map((option) => [option.value, option]));

    const orderedValues = [
        'unknown',
        ...preferred.filter((id) => byValue.has(id) && !pinned.includes(id)),
        ...options
            .map((option) => option.value)
            .filter((value) => !pinned.includes(value) && !preferredSet.has(value)),
        'custom',
    ].filter((value, index, all) => byValue.has(value) && all.indexOf(value) === index);

    select.replaceChildren();
    orderedValues.forEach((value) => {
        const source = byValue.get(value);
        if (!source) {
            return;
        }
        const option = source.cloneNode(true);
        if (!(option instanceof HTMLOptionElement)) {
            return;
        }
        const baseDe = option.getAttribute('data-text-de') || option.textContent || value;
        const baseEn = option.getAttribute('data-text-en') || option.textContent || value;
        const isPreferred = preferredSet.has(value);
        option.toggleAttribute('data-preferred', isPreferred);
        const cleanDe = baseDe.replace(/\s·\sEmpfohlen$/, '');
        const cleanEn = baseEn.replace(/\s·\sRecommended$/, '');
        if (isPreferred && value !== 'unknown' && value !== 'custom') {
            option.setAttribute('data-text-de', `${cleanDe} · Empfohlen`);
            option.setAttribute('data-text-en', `${cleanEn} · Recommended`);
            option.textContent = locale === 'de' ? `${cleanDe} · Empfohlen` : `${cleanEn} · Recommended`;
        } else {
            option.setAttribute('data-text-de', cleanDe);
            option.setAttribute('data-text-en', cleanEn);
            option.textContent = locale === 'de' ? cleanDe : cleanEn;
        }
        select.append(option);
    });

    if (byValue.has(current)) {
        select.value = current;
    }

    if (hint instanceof HTMLElement) {
        const text = platformPreferenceHint(state.orgContext, state.regulationPressure, locale);
        hint.textContent = text;
        hint.hidden = text === '';
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

    const state = getState(form, root);
    const followup = root.querySelector('[data-governance-followup-copy]');
    const domainLabel = root.querySelector('[data-governance-domain-label]');
    const platformLabel = root.querySelector('[data-governance-platform-label]');
    applyPlatformPreferences(form, state, locale);
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
        followup.textContent = resolveFollowup(copy, state.scenario, state.goal);
    }
    if (domainLabel) {
        domainLabel.textContent = resolveDomainLabel(copy, state.scenario, state.goal);
    }
    if (platformLabel) {
        platformLabel.textContent = resolvePlatformLabel(copy, state.scenario, state.goal);
    }
    syncGoalPillPreference(form, state.role);
    results.replaceChildren();

    ['tools', 'suppliers', 'certs', 'gaps', 'resources'].forEach((group) => {
        const items = grouped[group] || [];

        if (items.length === 0) {
            return;
        }

        const section = document.createElement('section');
        section.className = 'governance-advisor__result-group';
        section.dataset.governanceAdvisorGroup = group;

        const heading = document.createElement('h4');
        heading.textContent = copy.groups[group];

        const list = document.createElement('div');
        list.className = 'governance-advisor__result-list';

        items.forEach((item) => list.append(createRecommendation(item, locale, copy)));
        section.append(heading, list);
        results.append(section);
    });

    persistAndApplyHubContext(root, state);
}

/**
 * Keep scroll position stable across drawer/tab toggles without fighting touch scroll.
 * Overview/hub pages scroll inside `.tools-shell__main`, not `window` — restoring
 * window.scrollY (often 0) or re-applying a stale anchor after 40ms made iPad feel
 * "stuck" until refresh. Layout shift is handled by CSS scrollbar-gutter instead.
 */
function createScrollLock() {
    return {
        remember() {},
        /**
         * @param {() => void} callback
         */
        run(callback) {
            callback();
        },
        /**
         * @param {Element} _element
         */
        bindTrigger(_element) {},
    };
}

function initPanelToggles(root) {
    const controls = root.querySelector('[data-governance-top-controls]');
    const panels = Array.from(root.querySelectorAll('[data-governance-panel]'));
    const toggles = Array.from(root.querySelectorAll('[data-governance-panel-toggle]'));
    const drawerToggle = root.querySelector('[data-governance-drawer-toggle]');

    if (!controls || panels.length === 0 || toggles.length === 0 || !drawerToggle) {
        return;
    }

    const scrollLock = createScrollLock();

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

    const openPanel = (targetId) => {
        if (!targetId || !root.querySelector(`#${CSS.escape(targetId)}`)) {
            return;
        }
        scrollLock.run(() => {
            controls.hidden = false;
            activatePanel(targetId);
            drawerToggle.setAttribute('aria-expanded', 'true');
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
        scrollLock.bindTrigger(toggle);
        toggle.addEventListener('click', () => {
            openPanel(toggle.dataset.governancePanelToggle || '');
        });
    });

    root.querySelectorAll('[data-governance-panel] [data-governance-open-panel]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            openPanel(button.dataset.governanceOpenPanel || '');
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

function setSelectValue(form, name, value) {
    const select = form.querySelector(`select[name="${name}"]`);
    if (select instanceof HTMLSelectElement) {
        select.value = value;
    }
}

function syncGoalPillPreference(form, role) {
    const preferred = new Set(ROLE_PREFERRED_GOALS[role] || []);
    form.querySelectorAll('.governance-advisor__pill').forEach((pill) => {
        const input = pill.querySelector('input[name="goal"]');
        if (!(input instanceof HTMLInputElement)) {
            return;
        }
        const isPreferred = preferred.size === 0 || preferred.has(input.value);
        pill.classList.toggle('governance-advisor__pill--preferred', preferred.size > 0 && preferred.has(input.value));
        pill.classList.toggle('governance-advisor__pill--muted', preferred.size > 0 && !isPreferred);
    });
}

function contextIsActive(ctx) {
    return Boolean(
        ctx?.role
        || (ctx?.domain && ctx.domain !== 'unknown')
        || (ctx?.platform && ctx.platform !== 'unknown'),
    );
}

function writeHubContext(ctx) {
    try {
        sessionStorage.setItem(HUB_CONTEXT_STORAGE_KEY, JSON.stringify(ctx));
    } catch {
        // ignore storage failures
    }
}

function contextLabelParts(ctx, locale) {
    const labels = CONTEXT_LABELS[locale] || CONTEXT_LABELS.en;
    const parts = [];
    if (ctx.role) {
        parts.push(labels[ctx.role] || ctx.role);
    }
    if (ctx.goal) {
        parts.push(labels[ctx.goal] || ctx.goal);
    }
    if (ctx.orgContext && ctx.orgContext !== 'unknown') {
        parts.push(labels[ctx.orgContext] || ctx.orgContext);
    }
    if (ctx.regulationPressure && ctx.regulationPressure !== 'low') {
        parts.push(labels[ctx.regulationPressure] || ctx.regulationPressure);
    }
    if (ctx.domain && ctx.domain !== 'unknown') {
        parts.push(labels[ctx.domain] || ctx.domain);
    }
    if (ctx.platform && ctx.platform !== 'unknown') {
        parts.push(labels[ctx.platform] || ctx.platform);
    }
    return parts;
}

function itemMatchesHubFilter(tagList, ctx) {
    if (!contextIsActive(ctx)) {
        return true;
    }
    const tags = new Set(tagList || []);

    // Role filter is strict: role tag or that role's preferred goals.
    if (ctx.role) {
        if (tags.has(ctx.role)) {
            return true;
        }
        const preferredGoals = ROLE_PREFERRED_GOALS[ctx.role] || [];
        if (preferredGoals.some((goal) => tags.has(goal))) {
            return true;
        }
        return false;
    }

    // Domain / platform without role: match constraint, allow goal as soft fallback.
    let hasConstraint = false;
    let matchedConstraint = false;

    if (ctx.domain && ctx.domain !== 'unknown') {
        hasConstraint = true;
        if (tags.has(ctx.domain)) {
            matchedConstraint = true;
        }
    }

    if (ctx.platform === 'custom') {
        hasConstraint = true;
        const derived = derivePlatformTags(readCustomStack());
        if (derived.some((tag) => tags.has(tag)) || tags.has('custom')) {
            matchedConstraint = true;
        }
    } else if (ctx.platform && ctx.platform !== 'unknown') {
        hasConstraint = true;
        if (tags.has(ctx.platform)) {
            matchedConstraint = true;
        }
    }

    if (!hasConstraint) {
        return true;
    }

    return matchedConstraint || tags.has(ctx.goal);
}

function personaMatches(dataPersona, role) {
    if (!role) {
        return true;
    }
    const set = new Set(String(dataPersona || '').split(/\s+/).filter(Boolean));
    return set.has(role);
}

function ensureFilterEmpty(container, locale, onClear) {
    let empty = container.querySelector('[data-governance-hub-filter-empty]');
    if (!empty) {
        empty = document.createElement('p');
        empty.className = 'governance-hub__filter-empty';
        empty.setAttribute('data-governance-hub-filter-empty', '');
        const copy = texts[locale];
        empty.append(document.createTextNode(copy.filterEmpty + ' '));
        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = copy.contextClear;
        button.addEventListener('click', onClear);
        empty.append(button);
        container.append(empty);
    }
    return empty;
}

function applyHubContextFilter(root, ctx) {
    const locale = pickLocale();
    const copy = texts[locale];
    const active = contextIsActive(ctx);
    const clearFilter = () => clearHubContext(root);

    const barLabel = root.querySelector('[data-governance-hub-context-label]');
    const headerReset = root.querySelector('[data-governance-header-filter-reset]');
    const idleCopy = locale === 'de'
        ? 'Kein Filter aktiv — Rolle „Alle“ und offene Domain/Stack.'
        : 'No filter active — role “All” and open domain/stack.';

    if (barLabel) {
        const parts = contextLabelParts(ctx, locale);
        barLabel.textContent = active && parts.length
            ? `${copy.contextFiltered}: ${parts.join(' · ')}`
            : idleCopy;
    }
    if (headerReset) {
        headerReset.hidden = !active;
    }

    const counts = { guides: 0, canvas: 0, tools: 0 };

    root.querySelectorAll('[data-persona]').forEach((card) => {
        let show = true;
        if (active) {
            if (ctx.role) {
                show = personaMatches(card.getAttribute('data-persona'), ctx.role);
            } else {
                const goalTags = String(card.getAttribute('data-goal') || '').split(/\s+/).filter(Boolean);
                show = goalTags.length === 0 || itemMatchesHubFilter(goalTags, ctx);
            }
        }
        card.classList.toggle('governance-hub__card--filtered-out', !show);
        card.toggleAttribute('aria-hidden', !show);
        if (!show) {
            counts.guides += 1;
        }
    });

    const guidesPanel = root.querySelector('[data-governance-tab-panel="guides"]');
    if (guidesPanel) {
        const cards = Array.from(guidesPanel.querySelectorAll('[data-persona]'));
        const allHidden = active && cards.length > 0 && cards.every((card) => card.classList.contains('governance-hub__card--filtered-out'));
        const empty = ensureFilterEmpty(guidesPanel, locale, clearFilter);
        empty.hidden = !allHidden;
    }

    root.querySelectorAll('[data-discovery-step][data-tool-id]').forEach((step) => {
        const toolId = step.getAttribute('data-tool-id') || '';
        const tags = labels[toolId]?.tags || [];
        const show = !active || itemMatchesHubFilter(tags, ctx);
        step.classList.toggle('governance-hub__card--filtered-out', !show);
        step.toggleAttribute('aria-hidden', !show);
        if (!show) {
            counts.canvas += 1;
        }
    });

    const canvasPanel = root.querySelector('[data-governance-tab-panel="canvas"]');
    if (canvasPanel) {
        const steps = Array.from(canvasPanel.querySelectorAll('[data-discovery-step][data-tool-id]'));
        const allHidden = active && steps.length > 0 && steps.every((step) => step.classList.contains('governance-hub__card--filtered-out'));
        const empty = ensureFilterEmpty(canvasPanel, locale, clearFilter);
        empty.hidden = !allHidden;
    }

    root.querySelectorAll('[data-tool-id].governance-hub__tool, a.governance-hub__tool[data-tool-id]').forEach((tool) => {
        const toolId = tool.getAttribute('data-tool-id') || '';
        const tags = labels[toolId]?.tags || [];
        const show = !active || itemMatchesHubFilter(tags, ctx);
        tool.classList.toggle('governance-hub__card--filtered-out', !show);
        tool.toggleAttribute('aria-hidden', !show);
        if (!show) {
            counts.tools += 1;
        }
    });

    root.querySelectorAll('[data-governance-setup-workflow]').forEach((workflow) => {
        const ids = String(workflow.getAttribute('data-tool-ids') || '').split(/\s+/).filter(Boolean);
        const show = !active || ids.some((id) => itemMatchesHubFilter(labels[id]?.tags || [], ctx));
        workflow.classList.toggle('governance-hub__card--filtered-out', !show);
        workflow.toggleAttribute('aria-hidden', !show);
        if (!show) {
            counts.tools += 1;
        }
    });

    const toolsPanel = root.querySelector('[data-governance-tab-panel="tools"]');
    if (toolsPanel) {
        const items = Array.from(toolsPanel.querySelectorAll('[data-tool-id].governance-hub__tool, [data-governance-setup-workflow]'));
        const allHidden = active && items.length > 0 && items.every((item) => item.classList.contains('governance-hub__card--filtered-out'));
        const empty = ensureFilterEmpty(toolsPanel, locale, clearFilter);
        empty.hidden = !allHidden;
    }

    ['guides', 'canvas', 'tools'].forEach((tabId) => {
        const tab = root.querySelector(`[data-governance-tab-toggle="${tabId}"]`);
        if (!tab) {
            return;
        }
        const badge = tab.querySelector('[data-governance-hub-filter-badge]');
        const countEl = tab.querySelector('[data-governance-hub-filter-count]');
        // Show filter icon whenever a hub filter is active (role / domain / platform).
        const filtered = active;
        if (badge) {
            badge.hidden = !filtered;
            badge.setAttribute('aria-hidden', String(!filtered));
            badge.title = filtered
                ? (locale === 'de' ? 'Filter aktiv — Klick zum Zurücksetzen' : 'Filter active — click to clear')
                : '';
        }
        if (countEl) {
            countEl.textContent = filtered && counts[tabId] > 0 ? String(counts[tabId]) : '';
            countEl.hidden = !(filtered && counts[tabId] > 0);
        }
    });
}

function persistAndApplyHubContext(root, state) {
    const ctx = {
        role: state.role || '',
        goal: state.goal || 'stack',
        scenario: state.scenario || 'new',
        domain: state.domain || 'unknown',
        platform: state.platform || 'unknown',
        orgContext: state.orgContext || 'unknown',
        regulationPressure: state.regulationPressure || 'low',
    };
    writeHubContext(ctx);
    applyHubContextFilter(root, ctx);
}

function clearHubContext(root) {
    const form = root.querySelector('[data-governance-advisor-form]');
    const chips = Array.from(root.querySelectorAll('[data-governance-persona]'));
    chips.forEach((chip) => {
        const isAll = chip.dataset.governancePersona === 'all';
        chip.classList.toggle('governance-hub__persona--active', isAll);
        chip.setAttribute('aria-pressed', String(isAll));
    });
    try {
        localStorage.removeItem(PERSONA_STORAGE_KEY);
    } catch {
        // ignore
    }
    if (form) {
        setSelectValue(form, 'domain', 'unknown');
        setSelectValue(form, 'platform', 'unknown');
        setSelectValue(form, 'orgContext', 'unknown');
        setSelectValue(form, 'regulationPressure', 'low');
        syncGoalPillPreference(form, '');
        form.dispatchEvent(new Event('change', { bubbles: true }));
    } else {
        writeHubContext({ role: '', goal: 'stack', scenario: 'new', domain: 'unknown', platform: 'unknown', orgContext: 'unknown', regulationPressure: 'low' });
        applyHubContextFilter(root, { role: '', goal: 'stack', scenario: 'new', domain: 'unknown', platform: 'unknown', orgContext: 'unknown', regulationPressure: 'low' });
    }
}

function initStackBuilderModal(root, config = {}) {
    const dialog = root.querySelector('[data-governance-stack-builder]');
    const form = root.querySelector('[data-governance-advisor-form]');
    const openButtons = Array.from(root.querySelectorAll('[data-governance-stack-builder-open]'));
    const platformSelect = form?.querySelector('select[name="platform"]');
    if (!form || openButtons.length === 0) {
        return;
    }

    const host = dialog?.querySelector('[data-stack-builder-root]') || null;
    const loadSelect = dialog?.querySelector('[data-stack-builder-load]');
    const statusEl = dialog?.querySelector('[data-stack-builder-status]');
    let api = null;
    let savedStacks = [];
    const locale = () => (document.documentElement.lang === 'de' ? 'de' : 'en');
    const defaultButtonLabel = () => (locale() === 'de' ? 'Stack Builder öffnen' : 'Open Stack Builder');

    const builderContextFromForm = () => {
        const state = getState(form, root);
        const fromHub = preferredProductIds({
            orgContext: state.orgContext,
            regulationPressure: state.regulationPressure,
        });
        const startProduct = readStartingPointProduct();
        const fromStart = preferredProductIdsForStartingPoint(startProduct);
        const hubBanner = stackBuilderContextBanner({
            orgContext: state.orgContext,
            regulationPressure: state.regulationPressure,
        }, locale());
        const startBanner = startingPointStackBanner(startProduct, locale());
        return {
            orgContext: state.orgContext,
            regulationPressure: state.regulationPressure,
            preferredProductIds: [...new Set([...fromStart, ...fromHub])],
            contextBanner: [startBanner, hubBanner].filter(Boolean).join(' '),
        };
    };

    const syncBuilderContext = () => {
        if (!api?.setContext) {
            return;
        }
        const ctx = builderContextFromForm();
        api.setContext({
            preferredProductIds: ctx.preferredProductIds,
            contextBanner: ctx.contextBanner,
        });
    };

    const setStatus = (message) => {
        if (!(statusEl instanceof HTMLElement)) {
            return;
        }
        if (!message) {
            statusEl.hidden = true;
            statusEl.textContent = '';
            return;
        }
        statusEl.hidden = false;
        statusEl.textContent = message;
    };

    const fillLoadSelect = () => {
        if (!(loadSelect instanceof HTMLSelectElement)) {
            return;
        }
        const keep = loadSelect.value;
        const placeholder = locale() === 'de' ? '— Auswählen —' : '— Choose —';
        loadSelect.replaceChildren();
        const empty = document.createElement('option');
        empty.value = '';
        empty.textContent = placeholder;
        loadSelect.append(empty);
        savedStacks.forEach((item) => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            loadSelect.append(option);
        });
        if (keep && savedStacks.some((item) => item.id === keep)) {
            loadSelect.value = keep;
        }
    };

    const refreshSavedStacks = async () => {
        if (config.workspace?.enabled && config.workspace?.activeUrl) {
            try {
                const response = await fetch(config.workspace.activeUrl, {
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
                if (response.ok) {
                    const payload = await response.json();
                    const remote = Array.isArray(payload?.workspace?.savedStacks) ? payload.workspace.savedStacks : [];
                    savedStacks = remote.map((item) => ({
                        id: String(item.id),
                        name: String(item.name || 'Stack'),
                        selection: normalizeSelection(item.selection),
                        updatedAt: String(item.updatedAt || ''),
                    }));
                    writeSavedStacksLocal(savedStacks);
                    fillLoadSelect();
                    return;
                }
            } catch {
                // fall through to local
            }
        }
        savedStacks = readSavedStacksLocal();
        fillLoadSelect();
    };

    const syncOpenButton = () => {
        const isCustom = platformSelect?.value === 'custom';
        openButtons.forEach((button) => {
            button.hidden = !isCustom;
            const label = button.querySelector('span');
            if (!label || !isCustom) {
                return;
            }
            const selection = readCustomStack();
            const hasProducts = Object.values(selection).some((items) => Array.isArray(items) && items.length > 0);
            label.textContent = hasProducts ? summarizeSelection(selection) : defaultButtonLabel();
        });
    };

    const openModal = async () => {
        if (!(dialog instanceof HTMLDialogElement)) {
            const link = document.querySelector('a[href*="custom-stack-builder"]');
            if (link instanceof HTMLAnchorElement) {
                window.location.href = link.href;
            }
            return;
        }

        await refreshSavedStacks();
        setStatus('');
        const builderCtx = builderContextFromForm();

        if (host && !api) {
            api = mountStackBuilder(host, {
                compact: true,
                selection: readCustomStack(),
                preferredProductIds: builderCtx.preferredProductIds,
                contextBanner: builderCtx.contextBanner,
                onChange: (selection) => {
                    writeCustomStack(selection);
                    syncOpenButton();
                    syncWorkspaceStack(root, config, { stack: 'custom', customStack: selection });
                },
            });
        } else if (api) {
            api.setSelection(readCustomStack());
            syncBuilderContext();
        }

        if (dialog instanceof HTMLDialogElement) {
            openSharedModal(dialog);
        }
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            openModal();
        });
    });

    const onPlatformChange = () => {
        syncOpenButton();
        if (platformSelect?.value === 'custom') {
            openModal();
        }
        syncWorkspaceStack(root, config, {
            stack: platformSelect?.value || 'unknown',
            customStack: platformSelect?.value === 'custom' ? readCustomStack() : null,
        });
    };

    platformSelect?.addEventListener('change', onPlatformChange);
    form.addEventListener('change', (event) => {
        if (event.target === platformSelect) {
            onPlatformChange();
        } else {
            syncOpenButton();
            const name = event.target instanceof HTMLElement ? event.target.getAttribute('name') : '';
            if (name === 'orgContext' || name === 'regulationPressure') {
                syncBuilderContext();
            }
        }
    });

    dialog?.querySelector('[data-governance-stack-builder-save]')?.addEventListener('click', () => {
        const selection = api?.getSelection?.() || readCustomStack();
        writeCustomStack(selection);
        setSelectValue(form, 'platform', 'custom');
        syncOpenButton();
        syncWorkspaceStack(root, config, { stack: 'custom', customStack: selection });
        form.dispatchEvent(new Event('change', { bubbles: true }));
    });

    dialog?.querySelector('[data-governance-stack-builder-save-as]')?.addEventListener('click', async (event) => {
        event.preventDefault();
        const selection = api?.getSelection?.() || readCustomStack();
        const hasProducts = Object.values(selection).some((items) => Array.isArray(items) && items.length > 0);
        if (!hasProducts) {
            setStatus(locale() === 'de' ? 'Bitte zuerst Produkte wählen.' : 'Choose products first.');
            return;
        }
        const suggested = summarizeSelection(selection, locale()).replace(/^Eigener Stack · |^Custom stack · /, '');
        const name = window.prompt(
            locale() === 'de' ? 'Name für diesen Stack:' : 'Name for this stack:',
            suggested || (locale() === 'de' ? 'Mein Stack' : 'My stack'),
        );
        if (!name || !String(name).trim()) {
            return;
        }

        if (config.workspace?.enabled && config.workspace?.savedStacksUrl) {
            try {
                const response = await fetch(config.workspace.savedStacksUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken(),
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ name: String(name).trim(), selection }),
                });
                if (response.ok) {
                    const payload = await response.json();
                    savedStacks = Array.isArray(payload.savedStacks)
                        ? payload.savedStacks.map((item) => ({
                            id: String(item.id),
                            name: String(item.name || 'Stack'),
                            selection: normalizeSelection(item.selection),
                            updatedAt: String(item.updatedAt || ''),
                        }))
                        : savedStacks;
                    writeSavedStacksLocal(savedStacks);
                    fillLoadSelect();
                    writeCustomStack(selection);
                    setSelectValue(form, 'platform', 'custom');
                    syncWorkspaceStack(root, config, { stack: 'custom', customStack: selection });
                    syncOpenButton();
                    setStatus(locale() === 'de'
                        ? `Gespeichert im Workspace: ${payload.savedStack?.name || name}`
                        : `Saved to workspace: ${payload.savedStack?.name || name}`);
                    return;
                }
                if (response.status === 422) {
                    setStatus(locale() === 'de'
                        ? 'Kein aktiver Workspace — bitte unter Profil Hub anlegen/aktivieren.'
                        : 'No active workspace — create/activate one in Profile Hub.');
                    return;
                }
            } catch {
                // fall through to local
            }
        }

        const local = saveNamedStackLocal(String(name).trim(), selection);
        savedStacks = readSavedStacksLocal();
        fillLoadSelect();
        writeCustomStack(selection);
        setSelectValue(form, 'platform', 'custom');
        syncOpenButton();
        setStatus(locale() === 'de'
            ? `Lokal gespeichert: ${local?.name || name}`
            : `Saved locally: ${local?.name || name}`);
    });

    loadSelect?.addEventListener('change', () => {
        const id = loadSelect.value;
        if (!id) {
            return;
        }
        const match = savedStacks.find((item) => item.id === id);
        if (!match) {
            return;
        }
        const selection = normalizeSelection(match.selection);
        writeCustomStack(selection);
        api?.setSelection?.(selection);
        setSelectValue(form, 'platform', 'custom');
        syncOpenButton();
        syncWorkspaceStack(root, config, { stack: 'custom', customStack: selection });
        setStatus(locale() === 'de' ? `Geladen: ${match.name}` : `Loaded: ${match.name}`);
        form.dispatchEvent(new Event('change', { bubbles: true }));
    });

    syncOpenButton();
    refreshSavedStacks();
}

let workspaceSyncTimer = null;

function syncWorkspaceStack(root, config, payload) {
    if (!config?.workspace?.enabled || !config?.workspace?.syncStackUrl) {
        return;
    }
    const stack = String(payload.stack || 'unknown');
    const body = {
        stack,
        customStack: stack === 'custom' ? (payload.customStack || readCustomStack()) : null,
    };
    window.clearTimeout(workspaceSyncTimer);
    workspaceSyncTimer = window.setTimeout(async () => {
        try {
            await fetch(config.workspace.syncStackUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify(body),
            });
        } catch {
            // ignore sync failures
        }
    }, 400);
}

function applyWorkspaceStack(root, config) {
    const active = config?.workspace?.active;
    if (!active || !root) {
        return;
    }
    const form = root.querySelector('[data-governance-advisor-form]');
    if (!form) {
        return;
    }
    if (typeof active.stack === 'string' && active.stack !== '') {
        setSelectValue(form, 'platform', active.stack);
    }
    if (active.stack === 'custom' && active.customStack) {
        writeCustomStack(normalizeSelection(active.customStack));
    }
}

function initHubContextControls(root) {
    root.querySelectorAll('[data-governance-hub-filter-clear]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            clearHubContext(root);
        });
    });
    root.querySelectorAll('[data-governance-hub-filter-badge]').forEach((badge) => {
        badge.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            clearHubContext(root);
        });
    });
}

function initPersonas(root, onChange, preferredRole = '') {
    const chips = Array.from(root.querySelectorAll('[data-governance-persona]'));
    if (chips.length === 0) {
        return;
    }

    const form = root.querySelector('[data-governance-advisor-form]');
    const knownPersonas = new Set(chips.map((chip) => chip.dataset.governancePersona || ''));

    const normalizePersona = (value) => {
        let next = String(value || '').trim() || 'all';
        if (next === 'analyst') {
            next = 'product-owner';
        }
        if (next === 'dpo') {
            next = 'owner';
        }
        return knownPersonas.has(next) ? next : 'all';
    };

    let active = 'all';
    const preferred = normalizePersona(preferredRole);
    if (preferred !== 'all') {
        active = preferred;
    } else {
        try {
            active = normalizePersona(localStorage.getItem(PERSONA_STORAGE_KEY) || 'all');
        } catch {
            active = 'all';
        }
    }

    const apply = (persona, persist) => {
        const normalized = !persona || persona === 'all' ? 'all' : persona;
        active = normalized;
        chips.forEach((chip) => {
            const isActive = chip.dataset.governancePersona === normalized;
            chip.classList.toggle('governance-hub__persona--active', isActive);
            chip.setAttribute('aria-pressed', String(isActive));
        });

        if (normalized !== 'all' && form) {
            const chip = chips.find((item) => item.dataset.governancePersona === normalized);
            if (chip) {
                setRadioValue(form, 'scenario', chip.dataset.personaScenario || '');
                setRadioValue(form, 'goal', chip.dataset.personaGoal || '');
            }
            syncGoalPillPreference(form, normalized);
            form.dispatchEvent(new Event('change', { bubbles: true }));
        } else if (form) {
            syncGoalPillPreference(form, '');
            form.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (persist) {
            try {
                if (normalized === 'all') {
                    localStorage.removeItem(PERSONA_STORAGE_KEY);
                } else {
                    localStorage.setItem(PERSONA_STORAGE_KEY, normalized);
                }
            } catch {
                // ignore storage failures
            }
        }
        onChange?.();
    };

    chips.forEach((chip) => {
        chip.addEventListener('click', () => {
            const next = chip.dataset.governancePersona || 'all';
            // Second click on a concrete role returns to All
            if (active === next && next !== 'all') {
                apply('all', true);
                return;
            }
            apply(next, true);
        });
    });

    apply(active, false);
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
    const scrollLock = createScrollLock();

    root.querySelectorAll('[data-governance-subtabs]').forEach((group) => {
        const toggles = Array.from(group.querySelectorAll('[data-governance-subtab-toggle]'));
        const initial = toggles.find((tab) => tab.getAttribute('aria-selected') === 'true')?.dataset.governanceSubtabToggle
            || toggles[0]?.dataset.governanceSubtabToggle
            || '';
        toggles.forEach((tab) => {
            scrollLock.bindTrigger(tab);
            tab.addEventListener('click', () => {
                const id = tab.dataset.governanceSubtabToggle || '';
                scrollLock.run(() => {
                    activateSubtabGroup(group, id);
                    if (group.dataset.governanceSubtabs === 'guides' && id) {
                        const url = new URL(window.location.href);
                        url.searchParams.set('tab', 'guides');
                        url.hash = `guides-${id}`;
                        window.history.replaceState({}, '', url);
                    }
                });
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
    const isPhone = window.matchMedia('(max-width: 768px)').matches;
    let initial = aliases[fromQuery] || fromQuery || fromAttr || 'advisor';
    // Advisor is desktop-only; on phone open Guides instead.
    if (isPhone && initial === 'advisor') {
        initial = 'guides';
    }
    if (!allowed.has(initial)) {
        initial = isPhone ? 'guides' : 'advisor';
    }
    let pendingFragment = window.location.hash.replace(/^#/, '') || fromFragmentAttr || fragmentAliases[fromQuery] || '';

    const scrollLock = createScrollLock();

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
        scrollLock.bindTrigger(tab);
        tab.addEventListener('click', () => {
            scrollLock.run(() => {
                activate(tab.dataset.governanceTabToggle || 'advisor');
            });
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
    initHubContextControls(root);
    applyWorkspaceStack(root, config);
    initStackBuilderModal(root, config);
    initPersonas(root, () => {
        if (form) {
            render(root, config);
        }
    }, config.preferredRole || '');

    if (!form) {
        return;
    }

    const syncDqPanel = () => {
        const state = getState(form, root);
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
