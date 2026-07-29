import { createDefaultRule, normalizeRule } from './rule-types.js';
import { DEFAULT_DQ_REGION, getDqRegion, normalizeDqRegionId } from './dq-regions.js';

/**
 * @typedef {Object} DqPackColumnTemplate
 * @property {string} name
 * @property {{ de: string, en: string }} description
 * @property {import('./rule-types.js').DqRule[]} rules
 */

/**
 * @typedef {Object} DqPackExtraCheck
 * @property {string} column
 * @property {import('./rule-types.js').DqRuleType} type
 * @property {import('./rule-types.js').DqSeverity} [severity]
 * @property {string} [pattern]
 * @property {string} [sql]
 * @property {number} [min]
 * @property {number} [max]
 * @property {string[]} [values]
 * @property {number} [max_hours]
 */

/**
 * @typedef {Object} DqRulePack
 * @property {string} id
 * @property {{ de: string, en: string }} label
 * @property {{ de: string, en: string }} description
 * @property {'*' | import('./dq-regions.js').DqRegionId[]} regions
 * @property {(regionId: import('./dq-regions.js').DqRegionId) => {
 *   columns: DqPackColumnTemplate[],
 *   modelRules: import('./rule-types.js').DqRule[],
 *   extraChecks: DqPackExtraCheck[],
 *   notes: string[],
 * }} build
 */

const EMAIL_PATTERN = '^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$';
const PHONE_PATTERN = '^\\+?[0-9]{10,15}$';

/**
 * @param {import('./dq-regions.js').DqRegionId} regionId
 * @returns {string}
 */
function ibanPatternForRegion(regionId) {
    const region = getDqRegion(regionId);
    if (!region.ibanLength) {
        return '^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}$';
    }
    const bodyLen = region.ibanLength - 4;
    return `^${region.ibanCountry}[0-9]{2}[A-Z0-9]{${bodyLen}}$`;
}

/**
 * @param {string} id
 * @param {import('./rule-types.js').DqRuleType} type
 * @param {Partial<import('./rule-types.js').DqRule>} [extra]
 */
function rule(id, type, extra = {}) {
    return normalizeRule({ ...createDefaultRule(type), id, ...extra });
}

/** @type {DqRulePack[]} */
export const DQ_RULE_PACKS = [
    {
        id: 'pii-detection',
        label: { de: 'PII Detection', en: 'PII Detection' },
        description: {
            de: 'Format-Checks für E-Mail, Telefon und IBAN (Warn).',
            en: 'Format checks for email, phone, and IBAN (warn).',
        },
        regions: '*',
        build(regionId) {
            const ibanPattern = ibanPatternForRegion(regionId);
            const region = getDqRegion(regionId);
            /** @type {DqPackColumnTemplate[]} */
            const columns = [
                {
                    name: 'email',
                    description: { de: 'E-Mail (PII)', en: 'Email (PII)' },
                    rules: [
                        rule('email_not_null', 'not_null', { severity: 'error' }),
                        rule('email_format', 'regex', { pattern: EMAIL_PATTERN, severity: 'warn' }),
                    ],
                },
                {
                    name: 'phone',
                    description: { de: 'Telefon (PII)', en: 'Phone (PII)' },
                    rules: [rule('phone_format', 'regex', { pattern: PHONE_PATTERN, severity: 'warn' })],
                },
            ];
            if (region.ibanLength > 0) {
                columns.push({
                    name: 'iban',
                    description: { de: `IBAN ${region.ibanCountry}`, en: `IBAN ${region.ibanCountry}` },
                    rules: [rule('iban_format', 'regex', { pattern: ibanPattern, severity: 'warn' })],
                });
            }
            return {
                columns,
                modelRules: [],
                extraChecks: columns.flatMap((col) =>
                    col.rules
                        .filter((r) => r.type === 'regex' || r.type === 'not_null')
                        .map((r) => ({
                            column: col.name,
                            type: r.type,
                            severity: r.severity,
                            pattern: r.pattern,
                        })),
                ),
                notes: [
                    `PII pack for region ${regionId}`,
                    region.ibanLength > 0
                        ? `IBAN expects ${region.ibanCountry} length ${region.ibanLength}`
                        : 'IBAN not typical for this region — generic pattern skipped',
                ],
            };
        },
    },
    {
        id: 'address-format',
        label: { de: 'Adressformat', en: 'Address format' },
        description: {
            de: 'PLZ/Postcode und Spaltenreihenfolge je Region.',
            en: 'Postal code and column order by region.',
        },
        regions: '*',
        build(regionId) {
            const region = getDqRegion(regionId);
            /** @type {DqPackColumnTemplate[]} */
            const columns = [
                {
                    name: 'street',
                    description: { de: 'Straße', en: 'Street' },
                    rules: [rule('street_not_null', 'not_null', { severity: 'error' })],
                },
                {
                    name: 'postal_code',
                    description: {
                        de: `PLZ (${region.postalHint})`,
                        en: `Postal code (${region.postalHint})`,
                    },
                    rules: [
                        rule('postal_not_null', 'not_null', { severity: 'error' }),
                        rule('postal_format', 'regex', {
                            pattern: region.postalPattern,
                            severity: 'error',
                        }),
                    ],
                },
                {
                    name: 'city',
                    description: { de: 'Ort', en: 'City' },
                    rules: [rule('city_not_null', 'not_null', { severity: 'error' })],
                },
            ];
            if (regionId === 'US') {
                columns.splice(2, 0, {
                    name: 'state',
                    description: { de: 'US-Bundesstaat (2 Buchstaben)', en: 'US state (2 letters)' },
                    rules: [
                        rule('state_not_null', 'not_null', { severity: 'error' }),
                        rule('state_format', 'regex', { pattern: '^[A-Z]{2}$', severity: 'error' }),
                    ],
                });
            } else {
                columns.splice(1, 0, {
                    name: 'house_number',
                    description: { de: 'Hausnummer', en: 'House number' },
                    rules: [rule('house_number_not_null', 'not_null', { severity: 'warn' })],
                });
            }
            const orderSql = region.addressOrder
                .map((col) => `${col} is not null`)
                .join(' and ');
            return {
                columns,
                modelRules: [
                    rule('address_fields_present', 'expression', {
                        sql: orderSql,
                        severity: 'warn',
                    }),
                ],
                extraChecks: [
                    ...columns.flatMap((col) =>
                        col.rules.map((r) => ({
                            column: col.name,
                            type: r.type,
                            severity: r.severity,
                            pattern: r.pattern,
                            sql: r.sql,
                        })),
                    ),
                    {
                        column: '_model',
                        type: /** @type {import('./rule-types.js').DqRuleType} */ ('expression'),
                        severity: /** @type {import('./rule-types.js').DqSeverity} */ ('warn'),
                        sql: orderSql,
                    },
                ],
                notes: [
                    `Address column order (${regionId}): ${region.addressOrderHint}`,
                    `Postal pattern: ${region.postalPattern}`,
                ],
            };
        },
    },
    {
        id: 'status-enum',
        label: { de: 'Status-Enum', en: 'Status enum' },
        description: {
            de: 'accepted_values für typische Status-Spalten.',
            en: 'accepted_values for typical status columns.',
        },
        regions: '*',
        build() {
            const values = ['pending', 'active', 'cancelled', 'completed'];
            return {
                columns: [
                    {
                        name: 'status',
                        description: { de: 'Status', en: 'Status' },
                        rules: [
                            rule('status_not_null', 'not_null', { severity: 'error' }),
                            rule('status_values', 'accepted_values', { values, severity: 'error' }),
                        ],
                    },
                ],
                modelRules: [],
                extraChecks: [
                    { column: 'status', type: 'not_null', severity: 'error' },
                    {
                        column: 'status',
                        type: 'accepted_values',
                        severity: 'error',
                        values,
                    },
                ],
                notes: ['Status must be one of: pending, active, cancelled, completed'],
            };
        },
    },
    {
        id: 'amount-range',
        label: { de: 'Betrags-Range', en: 'Amount range' },
        description: {
            de: 'Plausibler Betragsbereich (0 … 999999).',
            en: 'Plausible amount range (0 … 999999).',
        },
        regions: '*',
        build() {
            return {
                columns: [
                    {
                        name: 'amount',
                        description: { de: 'Betrag', en: 'Amount' },
                        rules: [
                            rule('amount_not_null', 'not_null', { severity: 'error' }),
                            rule('amount_range', 'range', { min: 0, max: 999999, severity: 'error' }),
                        ],
                    },
                ],
                modelRules: [],
                extraChecks: [
                    { column: 'amount', type: 'not_null', severity: 'error' },
                    { column: 'amount', type: 'range', severity: 'error', min: 0, max: 999999 },
                ],
                notes: ['Amount must be between 0 and 999999'],
            };
        },
    },
    {
        id: 'freshness-volume',
        label: { de: 'Freshness & Volumen', en: 'Freshness & volume' },
        description: {
            de: 'Aktualität (48h) und Zeilenanzahl-Korridor.',
            en: 'Freshness (48h) and row-count corridor.',
        },
        regions: '*',
        build() {
            return {
                columns: [
                    {
                        name: 'updated_at',
                        description: { de: 'Letzte Aktualisierung', en: 'Last updated' },
                        rules: [rule('updated_fresh', 'freshness', { max_hours: 48, severity: 'warn' })],
                    },
                ],
                modelRules: [
                    rule('row_count_corridor', 'row_count_between', {
                        min: 1,
                        max: 5000000,
                        severity: 'warn',
                    }),
                ],
                extraChecks: [
                    {
                        column: 'updated_at',
                        type: 'freshness',
                        severity: 'warn',
                        max_hours: 48,
                    },
                    {
                        column: '_model',
                        type: 'row_count_between',
                        severity: 'warn',
                        min: 1,
                        max: 5000000,
                    },
                ],
                notes: ['Freshness max 48h on updated_at', 'Row count between 1 and 5,000,000'],
            };
        },
    },
    {
        id: 'unique-business-key',
        label: { de: 'Unique Business Key', en: 'Unique business key' },
        description: {
            de: 'not_null + unique auf der Business-Key-Spalte.',
            en: 'not_null + unique on the business key column.',
        },
        regions: '*',
        build() {
            return {
                columns: [
                    {
                        name: 'business_key',
                        description: { de: 'Business Key', en: 'Business key' },
                        rules: [
                            rule('business_key_not_null', 'not_null', { severity: 'error' }),
                            rule('business_key_unique', 'unique', { severity: 'error' }),
                        ],
                    },
                ],
                modelRules: [],
                extraChecks: [
                    { column: 'business_key', type: 'not_null', severity: 'error' },
                    { column: 'business_key', type: 'unique', severity: 'error' },
                ],
                notes: ['business_key must be present and unique'],
            };
        },
    },
];

/**
 * @param {string | null | undefined} regionId
 * @returns {DqRulePack[]}
 */
export function listPacksForRegion(regionId) {
    const id = normalizeDqRegionId(regionId);
    return DQ_RULE_PACKS.filter((pack) => pack.regions === '*' || pack.regions.includes(id));
}

/**
 * @param {string} packId
 * @returns {DqRulePack | undefined}
 */
export function getRulePack(packId) {
    return DQ_RULE_PACKS.find((pack) => pack.id === packId);
}

/**
 * @param {import('./dq-demo-model.js').DqModelState} state
 * @param {string} packId
 * @param {string} [regionId]
 * @param {'de' | 'en'} [locale]
 * @returns {import('./dq-demo-model.js').DqModelState}
 */
export function applyPackToDqModelState(state, packId, regionId = DEFAULT_DQ_REGION, locale = 'en') {
    const pack = getRulePack(packId);
    if (!pack) return state;

    const region = normalizeDqRegionId(regionId);
    if (pack.regions !== '*' && !pack.regions.includes(region)) {
        return state;
    }

    const built = pack.build(region);
    const columns = state.columns.map((col) => ({
        ...col,
        dqRules: [...col.dqRules],
    }));

    for (const template of built.columns) {
        const existing = columns.find((col) => col.name === template.name);
        const desc = template.description[locale] ?? template.description.en;
        if (existing) {
            if (!existing.description) existing.description = desc;
            for (const nextRule of template.rules) {
                const idx = existing.dqRules.findIndex((r) => r.id === nextRule.id || (r.type === nextRule.type && r.pattern === nextRule.pattern));
                if (idx >= 0) {
                    existing.dqRules[idx] = normalizeRule({ ...existing.dqRules[idx], ...nextRule });
                } else {
                    existing.dqRules.push(normalizeRule(nextRule));
                }
            }
        } else {
            columns.push({
                name: template.name,
                description: desc,
                dqRules: template.rules.map((r) => normalizeRule(r)),
            });
        }
    }

    const modelRules = [...state.modelRules];
    for (const nextRule of built.modelRules) {
        const idx = modelRules.findIndex((r) => r.id === nextRule.id);
        if (idx >= 0) {
            modelRules[idx] = normalizeRule({ ...modelRules[idx], ...nextRule });
        } else {
            modelRules.push(normalizeRule(nextRule));
        }
    }

    return {
        ...state,
        columns,
        modelRules,
    };
}

/**
 * @param {string} packId
 * @param {string} [regionId]
 */
export function buildPackExtraChecks(packId, regionId = DEFAULT_DQ_REGION) {
    const pack = getRulePack(packId);
    if (!pack) return { extraChecks: /** @type {DqPackExtraCheck[]} */ ([]), notes: /** @type {string[]} */ ([]) };
    const region = normalizeDqRegionId(regionId);
    if (pack.regions !== '*' && !pack.regions.includes(region)) {
        return { extraChecks: [], notes: [] };
    }
    const built = pack.build(region);
    return { extraChecks: built.extraChecks, notes: built.notes };
}

/**
 * @param {string} packId
 * @param {'de' | 'en'} locale
 */
export function packLabel(packId, locale = 'en') {
    const pack = getRulePack(packId);
    if (!pack) return packId;
    return pack.label[locale] ?? pack.label.en;
}
