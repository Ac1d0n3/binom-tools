/** @typedef {'DE' | 'AT' | 'CH' | 'NL' | 'GB' | 'US' | 'FR'} DqRegionId */

/**
 * @typedef {Object} DqRegionDef
 * @property {DqRegionId} id
 * @property {{ de: string, en: string }} label
 * @property {string} ibanCountry
 * @property {number} ibanLength
 * @property {string} postalPattern
 * @property {string} postalHint
 * @property {string[]} addressOrder
 * @property {string} addressOrderHint
 */

/** @type {DqRegionDef[]} */
export const DQ_REGIONS = [
    {
        id: 'DE',
        label: { de: 'Deutschland', en: 'Germany' },
        ibanCountry: 'DE',
        ibanLength: 22,
        postalPattern: '^[0-9]{5}$',
        postalHint: '5-digit PLZ',
        addressOrder: ['street', 'house_number', 'postal_code', 'city'],
        addressOrderHint: 'street → house_number → postal_code → city',
    },
    {
        id: 'AT',
        label: { de: 'Österreich', en: 'Austria' },
        ibanCountry: 'AT',
        ibanLength: 20,
        postalPattern: '^[0-9]{4}$',
        postalHint: '4-digit PLZ',
        addressOrder: ['street', 'house_number', 'postal_code', 'city'],
        addressOrderHint: 'street → house_number → postal_code → city',
    },
    {
        id: 'CH',
        label: { de: 'Schweiz', en: 'Switzerland' },
        ibanCountry: 'CH',
        ibanLength: 21,
        postalPattern: '^[0-9]{4}$',
        postalHint: '4-digit PLZ',
        addressOrder: ['street', 'house_number', 'postal_code', 'city'],
        addressOrderHint: 'street → house_number → postal_code → city',
    },
    {
        id: 'NL',
        label: { de: 'Niederlande', en: 'Netherlands' },
        ibanCountry: 'NL',
        ibanLength: 18,
        postalPattern: '^[0-9]{4}\\s?[A-Z]{2}$',
        postalHint: '1234 AB',
        addressOrder: ['street', 'house_number', 'postal_code', 'city'],
        addressOrderHint: 'street → house_number → postal_code → city',
    },
    {
        id: 'GB',
        label: { de: 'Vereinigtes Königreich', en: 'United Kingdom' },
        ibanCountry: 'GB',
        ibanLength: 22,
        postalPattern: '^[A-Z]{1,2}[0-9][0-9A-Z]?\\s?[0-9][A-Z]{2}$',
        postalHint: 'UK postcode',
        addressOrder: ['street', 'house_number', 'city', 'postal_code'],
        addressOrderHint: 'street → house_number → city → postal_code',
    },
    {
        id: 'US',
        label: { de: 'USA', en: 'United States' },
        ibanCountry: 'US',
        ibanLength: 0,
        postalPattern: '^[0-9]{5}(-[0-9]{4})?$',
        postalHint: 'ZIP / ZIP+4',
        addressOrder: ['street', 'city', 'state', 'postal_code'],
        addressOrderHint: 'street → city → state → zip',
    },
    {
        id: 'FR',
        label: { de: 'Frankreich', en: 'France' },
        ibanCountry: 'FR',
        ibanLength: 27,
        postalPattern: '^[0-9]{5}$',
        postalHint: '5-digit code postal',
        addressOrder: ['street', 'house_number', 'postal_code', 'city'],
        addressOrderHint: 'street → house_number → postal_code → city',
    },
];

/** @type {DqRegionId} */
export const DEFAULT_DQ_REGION = 'DE';

/** @type {DqRegionId[]} */
export const DQ_REGION_IDS = DQ_REGIONS.map((r) => r.id);

/**
 * @param {string | null | undefined} value
 * @returns {DqRegionId}
 */
export function normalizeDqRegionId(value) {
    const upper = String(value || '').trim().toUpperCase();
    return DQ_REGION_IDS.includes(/** @type {DqRegionId} */ (upper))
        ? /** @type {DqRegionId} */ (upper)
        : DEFAULT_DQ_REGION;
}

/**
 * @param {string | null | undefined} regionId
 * @returns {DqRegionDef}
 */
export function getDqRegion(regionId) {
    const id = normalizeDqRegionId(regionId);
    return DQ_REGIONS.find((r) => r.id === id) ?? DQ_REGIONS[0];
}

/**
 * @param {DqRegionId} regionId
 * @param {'de' | 'en'} locale
 * @returns {string}
 */
export function dqRegionLabel(regionId, locale = 'en') {
    const region = getDqRegion(regionId);
    return region.label[locale] ?? region.label.en;
}
