/**
 * Advisor guidance — certs, gaps/bridges, stack rationale by orgContext + regulation.
 * Pure rules module; hub-advisor.js renders the cards.
 */

/**
 * @typedef {{ de: string, en: string }} LocaleString
 * @typedef {{
 *   id: string,
 *   group: 'certs' | 'gaps',
 *   icon: string,
 *   title: LocaleString,
 *   reason: LocaleString,
 *   url: string,
 *   score?: number,
 * }} GuidanceCard
 * @typedef {{
 *   scenario?: string,
 *   goal?: string,
 *   domain?: string,
 *   platform?: string,
 *   orgContext?: string,
 *   regulationPressure?: string,
 *   role?: string,
 * }} AdvisorState
 * @typedef {Record<string, string>} GuidanceLinks
 * @typedef {{
 *   card: GuidanceCard,
 *   startToolIds: string[],
 * }} StackRationale
 */

/** @type {Record<string, string>} */
const ORG_ALIASES = {
    sme: 'midmarket',
};

/**
 * @param {string | undefined} raw
 * @returns {string}
 */
export function normalizeOrgContext(raw) {
    const value = typeof raw === 'string' && raw !== '' ? raw : 'unknown';
    return ORG_ALIASES[value] || value;
}

/**
 * @param {string | undefined} raw
 * @returns {'low' | 'gdpr-heavy' | 'regulated'}
 */
export function normalizeRegulation(raw) {
    if (raw === 'gdpr-heavy' || raw === 'regulated') {
        return raw;
    }
    return 'low';
}

const PLATFORM_LABELS = {
    fabric: { de: 'Fabric', en: 'Fabric' },
    databricks: { de: 'Databricks', en: 'Databricks' },
    'snowflake-dbt': { de: 'Snowflake / dbt', en: 'Snowflake / dbt' },
    sap: { de: 'SAP', en: 'SAP' },
    opensource: { de: 'Open Source', en: 'Open source' },
    custom: { de: 'Eigener Stack', en: 'Custom stack' },
};

/**
 * Soft platform preference order for advisor selects (never hard-filters).
 * @param {string | undefined} orgRaw
 * @param {string | undefined} regulationRaw
 * @returns {string[]}
 */
export function preferredPlatforms(orgRaw, regulationRaw) {
    const org = normalizeOrgContext(orgRaw);
    const regulation = normalizeRegulation(regulationRaw);

    /** @type {string[]} */
    let preferred;
    if (org === 'startup') {
        preferred = ['opensource', 'snowflake-dbt', 'fabric'];
    } else if (org === 'midmarket') {
        preferred = ['fabric', 'snowflake-dbt', 'opensource'];
    } else if (org === 'enterprise') {
        preferred = ['fabric', 'databricks', 'snowflake-dbt'];
    } else if (org === 'bank-finance') {
        preferred = ['fabric', 'sap', 'databricks'];
    } else if (org === 'public-sector') {
        preferred = ['opensource', 'fabric', 'sap'];
    } else {
        preferred = ['fabric', 'snowflake-dbt', 'opensource'];
    }

    if (regulation === 'regulated') {
        preferred = ['fabric', 'sap', 'databricks', ...preferred.filter((id) => !['fabric', 'sap', 'databricks'].includes(id))];
    } else if (regulation === 'gdpr-heavy') {
        preferred = ['fabric', 'opensource', ...preferred.filter((id) => !['fabric', 'opensource'].includes(id))];
    }

    return [...new Set(preferred)];
}

/**
 * Product ids to prioritize in the stack builder for org/regulation context.
 * @param {{ orgContext?: string, regulationPressure?: string }} context
 * @returns {string[]}
 */
export function preferredProductIds(context = {}) {
    const org = normalizeOrgContext(context.orgContext);
    const regulation = normalizeRegulation(context.regulationPressure);
    /** @type {string[]} */
    const ids = [];

    if (org === 'startup') {
        ids.push('airbyte', 'postgres', 'dbt', 'openmetadata', 'airflow');
    } else if (org === 'midmarket') {
        ids.push('fivetran', 'snowflake', 'dbt', 'powerbi', 'purview');
    } else if (org === 'enterprise') {
        ids.push('adf', 'fabric-lakehouse', 'databricks', 'dbt', 'powerbi', 'purview', 'unity-catalog');
    } else if (org === 'bank-finance') {
        ids.push('adf', 'informatica', 'fabric-lakehouse', 'databricks', 'powerbi', 'purview', 'collibra', 'unity-catalog');
    } else if (org === 'public-sector') {
        ids.push('airbyte', 'postgres', 'dbt', 'openmetadata', 'airflow', 'purview');
    }

    if (regulation === 'regulated' || regulation === 'gdpr-heavy' || org === 'bank-finance') {
        ids.push('purview', 'unity-catalog', 'collibra', 'alation', 'openmetadata');
    }
    if (org === 'public-sector' || regulation === 'gdpr-heavy') {
        ids.push('airbyte', 'postgres', 'openmetadata', 'airflow');
    }

    return [...new Set(ids)];
}

/**
 * Short hint under the platform select.
 * @param {string | undefined} orgRaw
 * @param {string | undefined} regulationRaw
 * @param {'de' | 'en'} [lang]
 * @returns {string}
 */
export function platformPreferenceHint(orgRaw, regulationRaw, lang = 'en') {
    const org = normalizeOrgContext(orgRaw);
    const regulation = normalizeRegulation(regulationRaw);
    if (org === 'unknown' && regulation === 'low') {
        return '';
    }

    const preferred = preferredPlatforms(org, regulation).slice(0, 3);
    const labels = preferred
        .map((id) => PLATFORM_LABELS[id]?.[lang] || PLATFORM_LABELS[id]?.en || id)
        .join(', ');

    if (lang === 'de') {
        if (org === 'bank-finance' || regulation === 'regulated') {
            return `Für Bank/Finance bzw. Regulierung eher prüfen: ${labels}.`;
        }
        if (org === 'public-sector') {
            return `Für den öffentlichen Sektor eher prüfen: ${labels}.`;
        }
        if (org === 'startup') {
            return `Für Startups eher schlank starten: ${labels}.`;
        }
        return `Zum Kontext passende Stacks zuerst: ${labels}.`;
    }

    if (org === 'bank-finance' || regulation === 'regulated') {
        return `For bank/finance or regulated setups, check first: ${labels}.`;
    }
    if (org === 'public-sector') {
        return `For public sector, check first: ${labels}.`;
    }
    if (org === 'startup') {
        return `For startups, start lean with: ${labels}.`;
    }
    return `Context-fit stacks first: ${labels}.`;
}

/**
 * One-line banner for the stack builder modal/tool.
 * @param {{ orgContext?: string, regulationPressure?: string }} context
 * @param {'de' | 'en'} [lang]
 * @returns {string}
 */
export function stackBuilderContextBanner(context = {}, lang = 'en') {
    const org = normalizeOrgContext(context.orgContext);
    const regulation = normalizeRegulation(context.regulationPressure);
    if (org === 'unknown' && regulation === 'low') {
        return '';
    }

    if (lang === 'de') {
        if (regulation === 'regulated' || org === 'bank-finance') {
            return 'Regulierter Kontext: Catalog-/Governance-Produkte früh wählen (Purview, Unity Catalog, Collibra).';
        }
        if (regulation === 'gdpr-heavy') {
            return 'DSGVO-Druck: Catalog und Privacy-fähige Produkte priorisieren; Residenz mitdenken.';
        }
        if (org === 'public-sector') {
            return 'Öffentlicher Sektor: Open-Source- und Residenz-taugliche Bausteine bevorzugen.';
        }
        if (org === 'startup') {
            return 'Startup-Kontext: schlanke Open-/dbt-Bausteine bevorzugen, Overhead vermeiden.';
        }
        if (org === 'enterprise') {
            return 'Enterprise-Kontext: Platform-Fit und Catalog/Ownership früh absichern.';
        }
        return 'Kontext gesetzt: bevorzugte Produkte sind hervorgehoben.';
    }

    if (regulation === 'regulated' || org === 'bank-finance') {
        return 'Regulated context: pick catalog/governance products early (Purview, Unity Catalog, Collibra).';
    }
    if (regulation === 'gdpr-heavy') {
        return 'GDPR pressure: prioritize catalog and privacy-ready products; consider residency.';
    }
    if (org === 'public-sector') {
        return 'Public sector: prefer open-source and residency-friendly building blocks.';
    }
    if (org === 'startup') {
        return 'Startup context: prefer lean open/dbt building blocks; avoid overhead.';
    }
    if (org === 'enterprise') {
        return 'Enterprise context: lock platform fit and catalog/ownership early.';
    }
    return 'Context set: preferred products are highlighted.';
}

/**
 * @param {GuidanceLinks} links
 * @param {string} key
 * @param {string} [fallback]
 * @returns {string}
 */
function linkOf(links, key, fallback = '#') {
    const value = links?.[key];
    return typeof value === 'string' && value !== '' ? value : fallback;
}

/**
 * Lean path shared by startup + midmarket (+ legacy sme via alias).
 * @param {GuidanceLinks} links
 * @param {'startup' | 'midmarket'} org
 * @returns {GuidanceCard[]}
 */
function leanOrgCerts(links, org) {
    const roadmap = linkOf(links, 'roadmap', '/compliance/roadmap');
    const eightPillars = linkOf(links, 'eightPillars', '/playbooks/eight-pillars');
    const learningPaths = linkOf(links, 'learningPaths', '/learning-paths');

    const startupBias = org === 'startup';

    return [
        {
            id: 'cert-pillars',
            group: 'certs',
            icon: 'fa-landmark',
            title: { de: '8 Säulen als Praxisstart', en: '8 pillars as a practical start' },
            reason: {
                de: startupBias
                    ? 'Startups: schlankes Governance-Gerüst zuerst — kein Zert-Overkill.'
                    : 'Für Midmarket/KMU oft schlanker als sofortige Cert-Pfade — gemeinsames Gerüst zuerst.',
                en: startupBias
                    ? 'Startups: lean governance frame first — no cert overkill.'
                    : 'For mid-market/SMEs often leaner than jumping straight into certs — shared frame first.',
            },
            url: eightPillars,
            score: 90,
        },
        {
            id: 'cert-foundations-path',
            group: 'certs',
            icon: 'fa-route',
            title: { de: 'Foundations Learning Path', en: 'Foundations learning path' },
            reason: {
                de: 'Kuratierter Einstieg; CDMP nur optional als Fachsprache.',
                en: 'Curated onboarding; CDMP only optional as shared language.',
            },
            url: learningPaths,
            score: 82,
        },
        ...(startupBias
            ? []
            : [{
                id: 'cert-roadmap-optional',
                group: 'certs',
                icon: 'fa-certificate',
                title: { de: 'Zertifizierungs-Roadmap (optional)', en: 'Certification roadmap (optional)' },
                reason: {
                    de: 'Orientierung, wenn später Nachweise gefragt werden — ohne Pflichtprogramm.',
                    en: 'Orientation when evidence is asked later — not a mandatory track.',
                },
                url: roadmap,
                score: 70,
            }]),
    ];
}

/**
 * @param {AdvisorState} state
 * @param {GuidanceLinks} links
 * @returns {GuidanceCard[]}
 */
function buildCertCards(state, links) {
    const org = normalizeOrgContext(state.orgContext);
    const regulation = normalizeRegulation(state.regulationPressure);
    /** @type {GuidanceCard[]} */
    const cards = [];

    const roadmap = linkOf(links, 'roadmap', '/compliance/roadmap');
    const eightPillars = linkOf(links, 'eightPillars', '/playbooks/eight-pillars');
    const learningPaths = linkOf(links, 'learningPaths', '/learning-paths');
    const cdmp = linkOf(links, 'cdmp', '/compliance/cdmp');
    const cippE = linkOf(links, 'cippE', '/compliance/cipp-e');
    const iso27001 = linkOf(links, 'iso27001', '/compliance/iso27001-li');
    const dsbDe = linkOf(links, 'dsbDe', '/compliance/dsb-de');
    const vendorLearning = linkOf(links, 'vendorLearningPathBuilder', '/tools/vendor-learning-path-builder');
    const dora = linkOf(links, 'dora', '/compliance/dora');
    const nis2 = linkOf(links, 'nis2', '/compliance/nis2');
    const bsiC5 = linkOf(links, 'bsiC5', '/compliance/bsi-c5');

    if (org === 'startup' || org === 'midmarket') {
        cards.push(...leanOrgCerts(links, org));
    } else if (org === 'enterprise') {
        cards.push(
            {
                id: 'cert-cdmp',
                group: 'certs',
                icon: 'fa-book',
                title: { de: 'CDMP (DAMA) als Fachsprache', en: 'CDMP (DAMA) as shared language' },
                reason: {
                    de: 'Enterprise-Teams brauchen oft eine gemeinsame Standardsprache neben lokalen Prozessen.',
                    en: 'Enterprise teams often need a shared standards language alongside local processes.',
                },
                url: cdmp,
                score: 92,
            },
            {
                id: 'cert-platform',
                group: 'certs',
                icon: 'fa-graduation-cap',
                title: { de: 'Platform-/Vendor-Lernpfad', en: 'Platform / vendor learning path' },
                reason: {
                    de: 'Stack-Certs und Vendor-Pfade an den Ziel-Stack koppeln.',
                    en: 'Tie stack certs and vendor paths to the target platform.',
                },
                url: vendorLearning,
                score: 86,
            },
            {
                id: 'cert-roadmap-enterprise',
                group: 'certs',
                icon: 'fa-certificate',
                title: { de: 'Consultant-Roadmap', en: 'Consultant roadmap' },
                reason: {
                    de: 'Phasen und Regionen für Nachweise — CDMP plus Security/Privacy nach Bedarf.',
                    en: 'Phases and regions for evidence — CDMP plus security/privacy as needed.',
                },
                url: roadmap,
                score: 78,
            },
        );
    } else if (org === 'bank-finance') {
        cards.push(
            {
                id: 'cert-cippe-bank',
                group: 'certs',
                icon: 'fa-shield-halved',
                title: { de: 'CIPP/E (Privacy)', en: 'CIPP/E (privacy)' },
                reason: {
                    de: 'Regulierter Finance-Kontext: Privacy-Nachweis und DSGVO-Sprache zählen.',
                    en: 'Regulated finance context: privacy evidence and GDPR language matter.',
                },
                url: cippE,
                score: 94,
            },
            {
                id: 'cert-iso-bank',
                group: 'certs',
                icon: 'fa-lock',
                title: { de: 'ISO 27001 / C5-Orientierung', en: 'ISO 27001 / C5 orientation' },
                reason: {
                    de: 'Security- und Control-Nachweise für Banken und Finanzdienstleister.',
                    en: 'Security and control evidence for banks and financial services.',
                },
                url: iso27001 !== '#' ? iso27001 : bsiC5,
                score: 88,
            },
            {
                id: 'cert-cdmp-bank',
                group: 'certs',
                icon: 'fa-book',
                title: { de: 'CDMP als Fachsprache', en: 'CDMP as professional language' },
                reason: {
                    de: 'Gemeinsame Data-Governance-Sprache neben regulatorischen Pflichten.',
                    en: 'Shared data-governance language alongside regulatory duties.',
                },
                url: cdmp,
                score: 80,
            },
            {
                id: 'cert-roadmap-bank',
                group: 'certs',
                icon: 'fa-certificate',
                title: { de: 'Roadmap inkl. DORA/NIS2-Kontext', en: 'Roadmap incl. DORA/NIS2 context' },
                reason: {
                    de: 'Bestehende Compliance-Karten und Phasen als Orientierungsrahmen nutzen.',
                    en: 'Use existing compliance cards and phases as an orientation frame.',
                },
                url: roadmap,
                score: 76,
            },
        );
    } else if (org === 'public-sector') {
        cards.push(
            {
                id: 'cert-cippe-public',
                group: 'certs',
                icon: 'fa-shield-halved',
                title: { de: 'CIPP/E', en: 'CIPP/E' },
                reason: {
                    de: 'Öffentlicher Sektor: Privacy- und Transfer-Konzepte müssen nachvollziehbar sein.',
                    en: 'Public sector: privacy and transfer concepts must be explainable.',
                },
                url: cippE,
                score: 93,
            },
            {
                id: 'cert-dsb',
                group: 'certs',
                icon: 'fa-user-shield',
                title: { de: 'DE-DSB / Fachkunde', en: 'DE DPO / competence' },
                reason: {
                    de: 'Lokale DSB- und Behördenpraxis ergänzt CIPP/E.',
                    en: 'Local DPO and public-sector practice complements CIPP/E.',
                },
                url: dsbDe,
                score: 87,
            },
            {
                id: 'cert-roadmap-public',
                group: 'certs',
                icon: 'fa-certificate',
                title: { de: 'Roadmap & Residenz-Hinweise', en: 'Roadmap & residency notes' },
                reason: {
                    de: 'Sovereign/Residenz über Resources und Stacks; CDMP optional.',
                    en: 'Sovereign/residency via resources and stacks; CDMP optional.',
                },
                url: roadmap,
                score: 74,
            },
        );
    } else {
        cards.push(
            {
                id: 'cert-roadmap-unknown',
                group: 'certs',
                icon: 'fa-certificate',
                title: { de: 'Zertifizierungs-Roadmap', en: 'Certification roadmap' },
                reason: {
                    de: 'Organisationskontext noch offen — Roadmap und Säulen als neutrale Orientierung.',
                    en: 'Org context still open — roadmap and pillars as neutral orientation.',
                },
                url: roadmap,
                score: 85,
            },
            {
                id: 'cert-pillars-unknown',
                group: 'certs',
                icon: 'fa-landmark',
                title: { de: '8 Säulen der Data Governance', en: '8 pillars of data governance' },
                reason: {
                    de: 'Praxisgerüst bevor Cert-Pfade priorisiert werden.',
                    en: 'Practical frame before prioritizing cert tracks.',
                },
                url: eightPillars,
                score: 82,
            },
        );
    }

    applyRegulationOverlay(cards, {
        org,
        regulation,
        links: {
            cippE,
            iso27001,
            dsbDe,
            roadmap,
            dora,
            nis2,
            bsiC5,
            learningPaths,
        },
    });

    return cards;
}

/**
 * Boost or inject cert cards based on regulationPressure.
 * @param {GuidanceCard[]} cards
 * @param {{
 *   org: string,
 *   regulation: 'low' | 'gdpr-heavy' | 'regulated',
 *   links: Record<string, string>,
 * }} opts
 */
function applyRegulationOverlay(cards, opts) {
    const { org, regulation, links } = opts;
    if (regulation === 'low') {
        return;
    }

    const hasId = (id) => cards.some((c) => c.id === id);
    const bump = (id, delta) => {
        const card = cards.find((c) => c.id === id);
        if (card) {
            card.score = (card.score ?? 50) + delta;
        }
    };

    if (regulation === 'gdpr-heavy') {
        bump('cert-cippe-bank', 4);
        bump('cert-cippe-public', 4);
        if (!hasId('cert-cippe-bank') && !hasId('cert-cippe-public') && !hasId('cert-cippe-overlay')) {
            cards.push({
                id: 'cert-cippe-overlay',
                group: 'certs',
                icon: 'fa-shield-halved',
                title: { de: 'CIPP/E (Privacy-Fokus)', en: 'CIPP/E (privacy focus)' },
                reason: {
                    de: 'DSGVO-starker Druck: Privacy-Nachweis und gemeinsame Sprache priorisieren.',
                    en: 'GDPR-heavy pressure: prioritize privacy evidence and shared language.',
                },
                url: links.cippE,
                score: 91,
            });
        }
        if (org === 'public-sector') {
            bump('cert-dsb', 3);
        }
        bump('cert-roadmap-optional', 2);
        bump('cert-roadmap-unknown', 2);
        bump('cert-roadmap-enterprise', 2);
        return;
    }

    // regulated
    bump('cert-cippe-bank', 3);
    bump('cert-cippe-public', 3);
    bump('cert-iso-bank', 4);
    bump('cert-roadmap-bank', 5);
    bump('cert-roadmap-public', 3);

    if (!hasId('cert-cippe-bank') && !hasId('cert-cippe-public') && !hasId('cert-cippe-overlay')) {
        cards.push({
            id: 'cert-cippe-overlay',
            group: 'certs',
            icon: 'fa-shield-halved',
            title: { de: 'CIPP/E', en: 'CIPP/E' },
            reason: {
                de: 'Regulierter Kontext: Privacy-Nachweis früh mitdenken.',
                en: 'Regulated context: fold privacy evidence in early.',
            },
            url: links.cippE,
            score: 90,
        });
    }

    if (!hasId('cert-iso-bank') && !hasId('cert-iso-overlay')) {
        cards.push({
            id: 'cert-iso-overlay',
            group: 'certs',
            icon: 'fa-lock',
            title: { de: 'ISO 27001 / C5-Orientierung', en: 'ISO 27001 / C5 orientation' },
            reason: {
                de: 'Regulierungsdruck: Security- und Control-Nachweise absichern.',
                en: 'Regulatory pressure: secure security and control evidence.',
            },
            url: links.iso27001 !== '#' ? links.iso27001 : links.bsiC5,
            score: 86,
        });
    }

    if (!hasId('cert-dora-nis2') && (org === 'bank-finance' || org === 'enterprise' || org === 'unknown')) {
        cards.push({
            id: 'cert-dora-nis2',
            group: 'certs',
            icon: 'fa-scale-balanced',
            title: { de: 'DORA / NIS2 Orientierung', en: 'DORA / NIS2 orientation' },
            reason: {
                de: 'Bestehende Compliance-Seiten als Einstieg — Roadmap und Framework-Karten nutzen.',
                en: 'Use existing compliance pages as entry — roadmap and framework cards.',
            },
            url: links.dora !== '#' ? links.dora : links.roadmap,
            score: 84,
        });
        if (links.nis2 && links.nis2 !== '#' && links.dora === '#') {
            const card = cards.find((c) => c.id === 'cert-dora-nis2');
            if (card) {
                card.url = links.nis2;
            }
        }
    }
}

/**
 * @param {AdvisorState} state
 * @param {GuidanceLinks} links
 * @returns {GuidanceCard[]}
 */
function buildGapCards(state, links) {
    const org = normalizeOrgContext(state.orgContext);
    const goal = state.goal || 'stack';
    const scenario = state.scenario || 'new';
    const platform = state.platform || 'unknown';
    /** @type {GuidanceCard[]} */
    const cards = [];

    const stackAdvisor = linkOf(links, 'governanceStackAdvisor', '/tools/governance-stack-advisor');
    const architectureFit = linkOf(links, 'architectureFit', '/tools/architecture-fit');
    const impactEffort = linkOf(links, 'impactEffort', '/tools/impact-effort');
    const kpiIntake = linkOf(links, 'kpiRequirementsIntake', '/tools/kpi-requirements-intake');
    const piiChecker = linkOf(links, 'piiDsdrReadiness', '/tools/pii-dsdr-readiness-checker');
    const playbooks = linkOf(links, 'playbooks', '/playbooks');
    const promptStudio = linkOf(links, 'promptStudio', '/tools/prompt-studio');
    const aiSanitizer = linkOf(links, 'aiSanitizer', '/tools/governance-ai-sanitizer');
    const toolsOverview = linkOf(links, 'toolsOverview', '/tools');
    const qlik = linkOf(links, 'qlikSetAnalysis', '/tools/qlik-set-analysis-generator');
    const compliance = linkOf(links, 'compliance', '/compliance');
    const bridgeStory = linkOf(links, 'bridgeSolutionStory', '/playbooks/bridge-solution');
    const trustedMetrics = linkOf(links, 'learningPaths', '/learning-paths');
    const metadataStory = linkOf(links, 'metadataCatalogStory', '/playbooks/metadata-catalog-lineage');
    const unityCatalog = linkOf(links, 'unityCatalogTool', '/tools/unity-catalog-governance-generator');
    const metaExport = linkOf(links, 'metaExportTool', '/tools/meta-export-generator');

    const lakehousePlatforms = new Set(['databricks', 'snowflake-dbt', 'fabric']);
    const biNearPlatforms = new Set(['fabric', 'databricks', 'snowflake-dbt']);

    if (platform === 'unknown' && goal === 'stack') {
        cards.push({
            id: 'gap-stack-unknown',
            group: 'gaps',
            icon: 'fa-layer-group',
            title: { de: 'Stack zuerst eingrenzen', en: 'Narrow the stack first' },
            reason: {
                de: 'Ohne Ziel-Stack bleiben Supplier- und Gate-Empfehlungen vage — Stack-Advisor oder Guides-Stacks.',
                en: 'Without a target stack, supplier and gate advice stays vague — use Stack Advisor or Guides → Stacks.',
            },
            url: stackAdvisor,
            score: 91,
        });
    }

    if (platform !== 'unknown' && (goal === 'stack' || scenario === 'extend' || goal === 'supplier')) {
        cards.push({
            id: 'gap-metadata-catalog',
            group: 'gaps',
            icon: 'fa-sitemap',
            title: { de: 'Metadata & Catalog-Brücke', en: 'Metadata & catalog bridge' },
            reason: {
                de: 'Stack ohne Catalog/Metadata-Säule bleibt blind — Pillar-Story und Catalog-Tool parallel öffnen.',
                en: 'A stack without a catalog/metadata pillar stays blind — open the pillar story and catalog tool together.',
            },
            url: metadataStory,
            score: 85,
        });
        if (platform === 'databricks') {
            cards.push({
                id: 'gap-unity-catalog',
                group: 'gaps',
                icon: 'fa-cubes',
                title: { de: 'Unity Catalog Governance', en: 'Unity Catalog governance' },
                reason: {
                    de: 'Databricks-Zielbild: Catalog- und Access-Muster früh festziehen.',
                    en: 'Databricks target: lock catalog and access patterns early.',
                },
                url: unityCatalog,
                score: 82,
            });
        } else {
            cards.push({
                id: 'gap-meta-export',
                group: 'gaps',
                icon: 'fa-file-export',
                title: { de: 'Meta-Export / Inventar', en: 'Meta export / inventory' },
                reason: {
                    de: 'Catalog-Lücke über Export und Inventar schließen, wenn kein natives Catalog-Tool greift.',
                    en: 'Close the catalog gap via export and inventory when no native catalog tool fits.',
                },
                url: metaExport !== '#' ? metaExport : playbooks,
                score: 74,
            });
        }
    }

    if (goal === 'kpi') {
        cards.push({
            id: 'gap-kpi-intake',
            group: 'gaps',
            icon: 'fa-gauge-high',
            title: { de: 'KPI Intake & Trusted Metrics', en: 'KPI intake & trusted metrics' },
            reason: {
                de: 'Kennzahl schärfen bevor Modell oder Report gebaut wird; Learning Paths für Metrics nutzen.',
                en: 'Sharpen the metric before building a model or report; use metrics learning paths.',
            },
            url: kpiIntake,
            score: 88,
        });
        cards.push({
            id: 'gap-kpi-path',
            group: 'gaps',
            icon: 'fa-route',
            title: { de: 'Lernpfad Metrics / KPI', en: 'Metrics / KPI learning path' },
            reason: {
                de: 'Kuratierte Journey ergänzt den Intake.',
                en: 'A curated journey complements intake.',
            },
            url: trustedMetrics,
            score: 72,
        });
    } else if (goal === 'stack' || biNearPlatforms.has(platform)) {
        cards.push({
            id: 'gap-bi-kpi-governance',
            group: 'gaps',
            icon: 'fa-gauge-high',
            title: { de: 'BI ohne KPI-Governance schließen', en: 'Close BI without KPI governance' },
            reason: {
                de: 'Stack/BI ohne KPI-Intake erzeugt Report-Chaos — Kennzahlvertrag vor Formel-Werkbank.',
                en: 'Stack/BI without KPI intake creates report chaos — metric contract before the formula workbench.',
            },
            url: kpiIntake,
            score: 79,
        });
    }

    if (goal === 'pii') {
        cards.push({
            id: 'gap-pii-readiness',
            group: 'gaps',
            icon: 'fa-user-shield',
            title: { de: 'PII/DSDR Readiness', en: 'PII/DSDR readiness' },
            reason: {
                de: org === 'bank-finance' || org === 'public-sector'
                    ? 'Regulierter Kontext: Readiness-Check plus Compliance-Hub stärker gewichten.'
                    : 'Klassifikation und DSDR-Keys prüfen, bevor Rohdaten landen.',
                en: org === 'bank-finance' || org === 'public-sector'
                    ? 'Regulated context: weight readiness plus the compliance hub more heavily.'
                    : 'Check classification and DSDR keys before raw loads land.',
            },
            url: piiChecker,
            score: 90,
        });
        cards.push({
            id: 'gap-pii-playbooks',
            group: 'gaps',
            icon: 'fa-book-open',
            title: { de: 'Privacy-Playbooks', en: 'Privacy playbooks' },
            reason: {
                de: 'Stories zu PII/DSDR als Brücke zwischen Policy und Technik.',
                en: 'PII/DSDR stories bridge policy and engineering.',
            },
            url: playbooks,
            score: 76,
        });
        if (org === 'bank-finance' || org === 'public-sector') {
            cards.push({
                id: 'gap-pii-compliance',
                group: 'gaps',
                icon: 'fa-scale-balanced',
                title: { de: 'Compliance-Hub', en: 'Compliance hub' },
                reason: {
                    de: 'Frameworks und Nachweise neben dem technischen Readiness-Check.',
                    en: 'Frameworks and evidence beside the technical readiness check.',
                },
                url: compliance,
                score: 84,
            });
        }
    }

    if (scenario === 'extend' && platform !== 'unknown') {
        cards.push({
            id: 'gap-fit',
            group: 'gaps',
            icon: 'fa-diagram-project',
            title: { de: 'Architecture Fit', en: 'Architecture fit' },
            reason: {
                de: 'Bestehenden Stack erweitern: Fit und Abhängigkeiten vor neuer Quelle prüfen.',
                en: 'Extending an existing stack: check fit and dependencies before a new source.',
            },
            url: architectureFit,
            score: 86,
        });
        cards.push({
            id: 'gap-impact',
            group: 'gaps',
            icon: 'fa-chart-line',
            title: { de: 'Impact–Effort', en: 'Impact–effort' },
            reason: {
                de: 'Priorisierung der Ergänzung statt alles parallel.',
                en: 'Prioritize the extension instead of doing everything in parallel.',
            },
            url: impactEffort,
            score: 80,
        });
        cards.push({
            id: 'gap-bridge-story',
            group: 'gaps',
            icon: 'fa-bridge',
            title: { de: 'Bridge-Solution Story', en: 'Bridge-solution story' },
            reason: {
                de: lakehousePlatforms.has(platform)
                    ? 'Gewachsenes BI + neues Lakehouse: Übergangsmuster zwischen Alt und Zielbild.'
                    : 'Muster für Übergangslösungen zwischen Alt und Zielbild.',
                en: lakehousePlatforms.has(platform)
                    ? 'Grown BI + new lakehouse: transitional patterns between legacy and target.'
                    : 'Pattern for transitional solutions between legacy and target.',
            },
            url: bridgeStory,
            score: lakehousePlatforms.has(platform) ? 88 : 68,
        });
    }

    if (goal === 'learning' || scenario === 'help') {
        cards.push({
            id: 'gap-prompt-studio',
            group: 'gaps',
            icon: 'fa-wand-magic-sparkles',
            title: { de: 'Prompt Studio → AI Sanitizer', en: 'Prompt Studio → AI sanitizer' },
            reason: {
                de: 'Prompt bauen und vor dem Versand an externe KI anonymisieren — Sanitizer priorisieren.',
                en: 'Build the prompt and sanitize before sending to external AI — prioritize the sanitizer.',
            },
            url: promptStudio,
            score: 83,
        });
        cards.push({
            id: 'gap-ai-sanitizer',
            group: 'gaps',
            icon: 'fa-mask',
            title: { de: 'Governance AI Sanitizer', en: 'Governance AI sanitizer' },
            reason: {
                de: 'Zweiter Schritt der AI-Werkbank im gleichen Governance-Kontext — nicht überspringen.',
                en: 'Second step of the AI workbench in the same governance context — do not skip.',
            },
            url: aiSanitizer,
            score: 80,
        });
    }

    if (goal === 'kpi' || goal === 'stack') {
        cards.push({
            id: 'gap-bi-workbench',
            group: 'gaps',
            icon: 'fa-calculator',
            title: { de: 'BI-Formel-Werkbank', en: 'BI formula workbench' },
            reason: {
                de: 'Qlik/Tableau/DAX-Generatoren gehören zur gleichen Governance-Werkbank für Report-Logik — BI gehört dazu.',
                en: 'Qlik/Tableau/DAX generators belong to the same governance workbench for report logic — BI is part of it.',
            },
            url: qlik !== '#' ? qlik : toolsOverview,
            score: 70,
        });
    }

    return cards;
}

/**
 * @param {AdvisorState} state
 * @param {GuidanceLinks} links
 * @returns {StackRationale | null}
 */
function buildStackRationale(state, links) {
    const platform = state.platform || 'unknown';
    const org = normalizeOrgContext(state.orgContext);
    const regulation = normalizeRegulation(state.regulationPressure);
    const goal = state.goal || 'stack';
    const scenario = state.scenario || 'new';
    const preferred = preferredPlatforms(org, regulation);
    const preferredLabelDe = preferred.slice(0, 3).map((id) => PLATFORM_LABELS[id]?.de || id).join(', ');
    const preferredLabelEn = preferred.slice(0, 3).map((id) => PLATFORM_LABELS[id]?.en || id).join(', ');

    if (platform === 'unknown' && org === 'unknown' && regulation === 'low') {
        return null;
    }

    const stackAdvisor = linkOf(links, 'governanceStackAdvisor', '/tools/governance-stack-advisor');
    const guidesStacks = linkOf(links, 'guidesStacks', '/governance#guides-stacks');
    const customBuilder = linkOf(links, 'customStackBuilder', '/tools/custom-stack-builder');

    /** @type {LocaleString} */
    let reason = {
        de: 'Stack-Hinweis aus Organisationskontext und Zielplattform — Guides und Stack-Advisor nutzen.',
        en: 'Stack note from org context and target platform — use guides and Stack Advisor.',
    };

    if (org === 'public-sector') {
        reason = {
            de: platform !== 'unknown'
                ? `Ziel-Stack „${platform}“ im Behördenkontext: Residenz/Sovereign und Open-Hinweise in Stacks prüfen.`
                : `Öffentlicher Sektor: zuerst ${preferredLabelDe} prüfen (Residenz/Sovereign), bevor der Ziel-Stack feststeht.`,
            en: platform !== 'unknown'
                ? `Target stack “${platform}” in public sector: check residency/sovereign and open-source notes in stacks.`
                : `Public sector: check ${preferredLabelEn} first (residency/sovereign) before locking the target.`,
        };
    } else if (org === 'bank-finance') {
        reason = {
            de: platform !== 'unknown'
                ? `Ziel-Stack „${platform}“ für Bank/Finance: Control- und Nachweis-Anforderungen früh einbeziehen.`
                : `Bank/Finance: Control-/Nachweisbedarf früh einrechnen — bevorzugte Stacks: ${preferredLabelDe}.`,
            en: platform !== 'unknown'
                ? `Target stack “${platform}” for bank/finance: fold control and evidence needs in early.`
                : `Bank/finance: fold control and evidence needs in early — preferred stacks: ${preferredLabelEn}.`,
        };
    } else if (org === 'startup') {
        reason = {
            de: platform !== 'unknown'
                ? `Ziel-Stack „${platform}“: für Startups schlank halten — Gates und Lernpfade ohne Plattform-Overkill.`
                : `Startup: leichtgewichtigen Pfad bevorzugen (${preferredLabelDe}), bis Fit und Owner klar sind.`,
            en: platform !== 'unknown'
                ? `Target stack “${platform}”: keep it lean for startups — gates and learning paths without platform overkill.`
                : `Startup: prefer a lightweight path (${preferredLabelEn}) until fit and owners are clear.`,
        };
    } else if (org === 'midmarket') {
        reason = {
            de: platform !== 'unknown'
                ? `Ziel-Stack „${platform}“: Midmarket-Fit — praxisnahe Gates statt Enterprise-Overhead.`
                : `Midmarket: Stack an realem Bedarf ausrichten — zuerst ${preferredLabelDe}.`,
            en: platform !== 'unknown'
                ? `Target stack “${platform}”: mid-market fit — practical gates instead of enterprise overhead.`
                : `Mid-market: align to real need — start with ${preferredLabelEn}.`,
        };
    } else if (org === 'enterprise' && platform !== 'unknown') {
        reason = {
            de: `Ziel-Stack „${platform}“: Platform-Certs, Fit und Vendor-Pfade an dieser Plattform ausrichten.`,
            en: `Target stack “${platform}”: align platform certs, fit, and vendor paths to this platform.`,
        };
    } else if (platform !== 'unknown') {
        reason = {
            de: `Ziel-Stack „${platform}“: Fit, Gates und Lernpfade an dieser Plattform ausrichten.`,
            en: `Target stack “${platform}”: align fit, gates, and learning paths to this platform.`,
        };
    } else if (org !== 'unknown' || regulation !== 'low') {
        reason = {
            de: `Noch kein Ziel-Stack gewählt — zum Kontext passen zuerst: ${preferredLabelDe}.`,
            en: `No target stack yet — context-fit options first: ${preferredLabelEn}.`,
        };
    }

    if (regulation === 'regulated') {
        reason = {
            de: `${reason.de} Regulierungsdruck: Nachweise und Control-Gates in die Stack-Entscheidung einrechnen.`,
            en: `${reason.en} Regulatory pressure: fold evidence and control gates into the stack decision.`,
        };
    } else if (regulation === 'gdpr-heavy') {
        reason = {
            de: `${reason.de} DSGVO-Druck: Privacy-/Residenz-Aspekte der Plattform prüfen.`,
            en: `${reason.en} GDPR pressure: check privacy/residency aspects of the platform.`,
        };
    }

    /** @type {string[]} */
    const startToolIds = ['governance-stack-advisor'];
    if (goal === 'stack' || platform === 'custom' || platform === 'unknown') {
        startToolIds.push('custom-stack-builder');
    }
    if (platform === 'databricks') {
        startToolIds.push('unity-catalog-governance-generator');
    }
    if (scenario === 'extend' || platform !== 'unknown') {
        startToolIds.push('architecture-fit');
    }
    if (goal === 'kpi' || platform === 'fabric' || (goal === 'stack' && platform !== 'databricks')) {
        startToolIds.push('kpi-requirements-intake');
    }
    const uniqueTools = [...new Set(startToolIds)].slice(0, 4);

    return {
        card: {
            id: 'stack-note',
            group: 'gaps',
            icon: 'fa-cubes',
            title: { de: 'Stack-Begründung', en: 'Stack rationale' },
            reason,
            url: platform === 'unknown'
                ? (guidesStacks !== '#' ? guidesStacks : stackAdvisor)
                : (guidesStacks !== '#' ? guidesStacks : (customBuilder !== '#' ? customBuilder : stackAdvisor)),
            score: 72,
        },
        startToolIds: uniqueTools,
    };
}

/**
 * @param {AdvisorState} state
 * @param {GuidanceLinks} [links]
 * @returns {{
 *   certs: GuidanceCard[],
 *   gaps: GuidanceCard[],
 *   stackNote: GuidanceCard | null,
 *   startToolIds: string[],
 * }}
 */
export function buildGuidance(state = {}, links = {}) {
    const certs = buildCertCards(state, links);
    const gaps = buildGapCards(state, links);
    const rationale = buildStackRationale(state, links);
    const stackNote = rationale?.card ?? null;
    if (stackNote) {
        gaps.push(stackNote);
    }

    return {
        certs,
        gaps,
        stackNote,
        startToolIds: rationale?.startToolIds ?? [],
    };
}
