/** Default token-handling rules appended to outbound user prompts. */
const BN_GOVERNANCE_DEFAULT_LLM_RULES = `- Keep all [[BNM_...]] placeholder tokens exactly as provided (e.g. [[BNM_EMAIL_...]], [[BNM_PHONE_...]], [[BNM_URL_...]], [[BNM_CARD_...]], [[BNM_ADDRESS_...]], [[BNM_GEO_...]]).
- When mentioning sensitive values in replies, repeat those tokens verbatim.
- Do not invent, replace, decode, or remove placeholder tokens.`;
const BN_GOVERNANCE_DEFAULT_PLACEHOLDER_FORMAT = '[[BNM_{{TYPE}}_{{KEY}}]]';
const BN_GOVERNANCE_PLACEHOLDER_TYPE_MAP = {
    personName: 'PERSON',
    email: 'EMAIL',
    phone: 'PHONE',
    postalAddress: 'ADDRESS',
    geoCoordinates: 'GEO',
    companyName: 'COMPANY',
    url: 'URL',
    ipv4: 'IPV4',
    ipv6: 'IPV6',
    creditCard: 'CARD',
    iban: 'IBAN',
    taxVatId: 'TAX_VAT',
    passportId: 'PASSPORT',
    licensePlate: 'LICENSE_PLATE',
    dateOfBirth: 'DOB',
    customRegex: 'CUSTOM',
};
const BN_GOVERNANCE_DETECTOR_IDS = [
    'personName',
    'email',
    'phone',
    'postalAddress',
    'geoCoordinates',
    'companyName',
    'url',
    'ipv4',
    'ipv6',
    'creditCard',
    'iban',
    'taxVatId',
    'passportId',
    'licensePlate',
    'dateOfBirth',
    'customRegex',
];
const BN_GOVERNANCE_DEFAULT_CONFIG = {
    detectors: {
        email: true,
        phone: true,
        url: true,
        ipv4: true,
        ipv6: true,
        creditCard: true,
        iban: true,
        postalAddress: true,
        geoCoordinates: true,
        taxVatId: true,
        passportId: true,
        licensePlate: true,
        dateOfBirth: true,
        personName: false,
        companyName: false,
        customRegex: false,
    },
    maskChar: '*',
    placeholderFormat: BN_GOVERNANCE_DEFAULT_PLACEHOLDER_FORMAT,
    reusePlaceholders: true,
    overlapStrategy: 'longest',
};

/**
 * Generated bundle index. Do not edit.
 */

export { BN_GOVERNANCE_DEFAULT_CONFIG, BN_GOVERNANCE_DEFAULT_LLM_RULES, BN_GOVERNANCE_DEFAULT_PLACEHOLDER_FORMAT, BN_GOVERNANCE_DETECTOR_IDS, BN_GOVERNANCE_PLACEHOLDER_TYPE_MAP };
//# sourceMappingURL=binom-sdk-governance-types.mjs.map
