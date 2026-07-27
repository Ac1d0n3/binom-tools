/**
 * Advisor guidance — certs, gaps/bridges, optional stack note by orgContext.
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
 *   role?: string,
 * }} AdvisorState
 * @typedef {Record<string, string>} GuidanceLinks
 */

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
 * @param {AdvisorState} state
 * @param {GuidanceLinks} links
 * @returns {GuidanceCard[]}
 */
function buildCertCards(state, links) {
    const org = state.orgContext || 'unknown';
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

    if (org === 'sme') {
        cards.push(
            {
                id: 'cert-pillars',
                group: 'certs',
                icon: 'fa-landmark',
                title: { de: '8 Säulen als Praxisstart', en: '8 pillars as a practical start' },
                reason: {
                    de: 'Für KMU oft schlanker als sofortige Cert-Pfade — gemeinsames Governance-Gerüst zuerst.',
                    en: 'For SMEs often leaner than jumping straight into certs — shared governance frame first.',
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
            {
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
            },
        );
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
                url: iso27001,
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

    return cards;
}

/**
 * @param {AdvisorState} state
 * @param {GuidanceLinks} links
 * @returns {GuidanceCard[]}
 */
function buildGapCards(state, links) {
    const org = state.orgContext || 'unknown';
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
                de: 'Muster für Übergangslösungen zwischen Alt und Zielbild.',
                en: 'Pattern for transitional solutions between legacy and target.',
            },
            url: bridgeStory,
            score: 68,
        });
    }

    if (goal === 'learning' || scenario === 'help') {
        cards.push({
            id: 'gap-prompt-studio',
            group: 'gaps',
            icon: 'fa-wand-magic-sparkles',
            title: { de: 'Prompt Studio → AI Sanitizer', en: 'Prompt Studio → AI sanitizer' },
            reason: {
                de: 'Prompt bauen und vor dem Versand an externe KI anonymisieren — eine Kette.',
                en: 'Build the prompt and sanitize before sending to external AI — one chain.',
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
                de: 'Zweiter Schritt der AI-Werkbank im gleichen Governance-Kontext.',
                en: 'Second step of the AI workbench in the same governance context.',
            },
            url: aiSanitizer,
            score: 78,
        });
    }

    if (goal === 'kpi' || goal === 'stack') {
        cards.push({
            id: 'gap-bi-workbench',
            group: 'gaps',
            icon: 'fa-calculator',
            title: { de: 'BI-Formel-Werkbank', en: 'BI formula workbench' },
            reason: {
                de: 'Qlik/Tableau/DAX-Generatoren gehören zur gleichen Governance-Werkbank für Report-Logik.',
                en: 'Qlik/Tableau/DAX generators belong to the same governance workbench for report logic.',
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
 * @returns {GuidanceCard | null}
 */
function buildStackNote(state, links) {
    const platform = state.platform || 'unknown';
    const org = state.orgContext || 'unknown';
    if (platform === 'unknown' && org === 'unknown') {
        return null;
    }

    const stackAdvisor = linkOf(links, 'governanceStackAdvisor', '/tools/governance-stack-advisor');
    const guidesStacks = linkOf(links, 'guidesStacks', '/governance#guides-stacks');

    let reason = {
        de: 'Stack-Hinweis aus Organisationskontext und Zielplattform — Guides und Stack-Advisor nutzen.',
        en: 'Stack note from org context and target platform — use guides and Stack Advisor.',
    };

    if (org === 'public-sector') {
        reason = {
            de: 'Öffentlicher Sektor: Residenz/Sovereign und Open-Hinweise in Stacks prüfen.',
            en: 'Public sector: check residency/sovereign and open-source notes in stacks.',
        };
    } else if (org === 'bank-finance') {
        reason = {
            de: 'Bank/Finance: Control- und Nachweis-Anforderungen früh in die Stack-Wahl einbeziehen.',
            en: 'Bank/finance: fold control and evidence needs into the stack choice early.',
        };
    } else if (platform !== 'unknown') {
        reason = {
            de: `Ziel-Stack „${platform}“: Fit, Gates und Lernpfade an dieser Plattform ausrichten.`,
            en: `Target stack “${platform}”: align fit, gates, and learning paths to this platform.`,
        };
    }

    return {
        id: 'stack-note',
        group: 'gaps',
        icon: 'fa-cubes',
        title: { de: 'Stack-Begründung', en: 'Stack rationale' },
        reason,
        url: platform === 'unknown' ? guidesStacks : stackAdvisor,
        score: 60,
    };
}

/**
 * @param {AdvisorState} state
 * @param {GuidanceLinks} [links]
 * @returns {{ certs: GuidanceCard[], gaps: GuidanceCard[], stackNote: GuidanceCard | null }}
 */
export function buildGuidance(state = {}, links = {}) {
    const certs = buildCertCards(state, links);
    const gaps = buildGapCards(state, links);
    const stackNote = buildStackNote(state, links);
    if (stackNote) {
        gaps.push(stackNote);
    }

    return { certs, gaps, stackNote };
}
