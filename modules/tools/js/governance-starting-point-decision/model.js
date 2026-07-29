/** @typedef {'fabric'|'databricks'|'snowflake'|'bigquery'|'dbt'|'multiple'|''} GspdProductId */

export const DRAFT_STORAGE_KEY = 'bn-tools:governance-starting-point-decision:draft:v1';

export const PRODUCT_IDS = /** @type {const} */ ([
    'fabric',
    'databricks',
    'snowflake',
    'bigquery',
    'dbt',
    'multiple',
]);

/** Map stack-builder platform tags → product ids */
export const TAG_TO_PRODUCT = {
    fabric: 'fabric',
    databricks: 'databricks',
    snowflake: 'snowflake',
    bigquery: 'bigquery',
    dbt: 'dbt',
};

export const AREA_STATUS = [
    'notAssessed',
    'missing',
    'partial',
    'defined',
    'validated',
    'approved',
];

export const DECISION_STATUS = [
    'draft',
    'readyForReview',
    'readyForProofOfValue',
    'conditional',
    'approved',
    'blocked',
    'rejected',
];

export const GOVERNANCE_AREAS = [
    'ownership',
    'identityAccess',
    'classification',
    'policies',
    'lineage',
    'dataQuality',
    'incidentOwnership',
    'retention',
    'environmentModel',
    'costAccountability',
    'semanticOwnership',
];

/** @type {Record<string, string[]>} */
export const PRODUCT_FIELDS = {
    fabric: [
        'tenantCapacity',
        'domainsWorkspaces',
        'purviewBoundary',
        'semanticModelOwnership',
        'deploymentModel',
    ],
    databricks: [
        'cloudRegion',
        'accountMetastore',
        'catalogSchemaBoundaries',
        'workspacesCompute',
        'unityCatalogPolicies',
    ],
    snowflake: [
        'accountRegion',
        'roleHierarchy',
        'tagsClassification',
        'maskingRowAccess',
        'sharingBoundary',
    ],
    bigquery: [
        'organizationProjects',
        'region',
        'datasetModel',
        'iamFineGrained',
        'exportCostBoundary',
    ],
    dbt: [
        'supportedPlatforms',
        'sourceModelScope',
        'metadataSchema',
        'contractsTests',
        'pullRequestApproval',
        'deploymentEvidence',
        'catalogPolicyHandoff',
    ],
};

export const MULTIPLE_ROW_FIELDS = [
    'platform',
    'workload',
    'authoritativeMetadata',
    'identityAuthority',
    'classificationAuthority',
    'enforcementPoint',
    'lineageHandoff',
    'qualityEvidence',
    'semanticOwner',
    'incidentOwner',
    'costOwner',
    'consolidationTrigger',
];

export const LIST_KEYS = [
    'knownGaps',
    'requiredTests',
    'openQuestions',
    'blockers',
    'exceptions',
    'evidenceLinks',
];

/**
 * @returns {ReturnType<typeof createEmptyState>}
 */
export function createEmptyState() {
    /** @type {Record<string, {status: string, description: string, owner: string, evidence: string, gap: string}>} */
    const areas = {};
    for (const id of GOVERNANCE_AREAS) {
        areas[id] = {
            status: 'notAssessed',
            description: '',
            owner: '',
            evidence: '',
            gap: '',
        };
    }

    /** @type {Record<string, Record<string, string>>} */
    const productFields = {};
    for (const [productId, fields] of Object.entries(PRODUCT_FIELDS)) {
        productFields[productId] = Object.fromEntries(fields.map((f) => [f, '']));
    }

    return {
        product: '',
        context: {
            title: '',
            firstUseCase: '',
            existingContext: '',
            decisionGoal: '',
            decisionOwner: '',
            dataOwner: '',
            dataSteward: '',
            technicalOwner: '',
            reviewDate: '',
        },
        areas,
        productFields,
        multipleRows: [],
        lists: {
            knownGaps: [],
            requiredTests: [],
            openQuestions: [],
            blockers: [],
            exceptions: [],
            evidenceLinks: [],
        },
        exceptionMeta: {
            exceptionOwner: '',
            expiryDate: '',
        },
        decision: {
            status: 'draft',
            preferredStartingPattern: '',
            conditionalAlternative: '',
            noNewPlatformAlternative: '',
            blockers: '',
            validationPlan: '',
            noRegretNextStep: '',
            decisionRationale: '',
            implementationOwner: '',
            approvalOwner: '',
            reviewDate: '',
        },
    };
}

/**
 * @param {unknown} raw
 * @returns {ReturnType<typeof createEmptyState>}
 */
export function normalizeState(raw) {
    const base = createEmptyState();
    if (!raw || typeof raw !== 'object') {
        return base;
    }
    const data = /** @type {Record<string, unknown>} */ (raw);
    if (typeof data.product === 'string' && (PRODUCT_IDS.includes(/** @type {any} */ (data.product)) || data.product === '')) {
        base.product = data.product;
    }
    if (data.context && typeof data.context === 'object') {
        Object.assign(base.context, data.context);
    }
    if (data.areas && typeof data.areas === 'object') {
        for (const id of GOVERNANCE_AREAS) {
            const area = /** @type {Record<string, unknown>} */ (data.areas)[id];
            if (area && typeof area === 'object') {
                Object.assign(base.areas[id], area);
            }
        }
    }
    if (data.productFields && typeof data.productFields === 'object') {
        for (const [productId, fields] of Object.entries(PRODUCT_FIELDS)) {
            const src = /** @type {Record<string, unknown>} */ (data.productFields)[productId];
            if (src && typeof src === 'object') {
                for (const f of fields) {
                    if (typeof /** @type {Record<string, unknown>} */ (src)[f] === 'string') {
                        base.productFields[productId][f] = /** @type {string} */ (
                            /** @type {Record<string, unknown>} */ (src)[f]
                        );
                    }
                }
            }
        }
    }
    if (Array.isArray(data.multipleRows)) {
        base.multipleRows = data.multipleRows
            .filter((row) => row && typeof row === 'object')
            .map((row) => {
                const r = /** @type {Record<string, unknown>} */ (row);
                return Object.fromEntries(
                    MULTIPLE_ROW_FIELDS.map((f) => [f, typeof r[f] === 'string' ? r[f] : '']),
                );
            });
    }
    if (data.lists && typeof data.lists === 'object') {
        for (const key of LIST_KEYS) {
            const list = /** @type {Record<string, unknown>} */ (data.lists)[key];
            if (Array.isArray(list)) {
                base.lists[key] = list.map((item) => String(item ?? ''));
            }
        }
    }
    if (data.exceptionMeta && typeof data.exceptionMeta === 'object') {
        Object.assign(base.exceptionMeta, data.exceptionMeta);
    }
    if (data.decision && typeof data.decision === 'object') {
        Object.assign(base.decision, data.decision);
    }
    return base;
}

/**
 * @param {string[]} tags
 * @returns {GspdProductId}
 */
export function productFromTags(tags) {
    const mapped = [...new Set(tags.map((t) => TAG_TO_PRODUCT[t]).filter(Boolean))];
    if (mapped.length === 1) {
        return /** @type {GspdProductId} */ (mapped[0]);
    }
    if (mapped.length > 1) {
        return 'multiple';
    }
    return '';
}

/**
 * @returns {Record<string, string>}
 */
export function emptyMultipleRow() {
    return Object.fromEntries(MULTIPLE_ROW_FIELDS.map((f) => [f, '']));
}
