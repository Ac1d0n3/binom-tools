import { validateEmail, validatePhone, validateIpv4, validateIpv6, validateCreditCard, validateIban, validateGeoCoordinates } from '@binom/sdk-governance/validators';

let findingCounter = 0;
function resetFindingCounter() {
    findingCounter = 0;
}
function createFindingId() {
    findingCounter += 1;
    return `finding_${findingCounter}`;
}
function scanRegex(input, type, pattern, confidence, validate, options) {
    const findings = [];
    const flags = pattern.flags.includes('g') ? pattern.flags : `${pattern.flags}g`;
    const regex = new RegExp(pattern.source, flags);
    let match;
    while ((match = regex.exec(input)) !== null) {
        const value = match[0];
        if (validate && !validate(value)) {
            continue;
        }
        const findingConfidence = options?.minConfidence
            ? Math.max(confidence, options.minConfidence)
            : confidence;
        findings.push({
            id: createFindingId(),
            type,
            start: match.index,
            end: match.index + value.length,
            value,
            confidence: findingConfidence,
        });
        if (match.index === regex.lastIndex) {
            regex.lastIndex += 1;
        }
    }
    return findings;
}
function isDetectorEnabled(detectors, id) {
    if (!detectors || !(id in detectors)) {
        return false;
    }
    const entry = detectors[id];
    if (typeof entry === 'boolean') {
        return entry;
    }
    return entry?.enabled !== false;
}
function getDetectorOptions(detectors, id) {
    if (!detectors) {
        return undefined;
    }
    const entry = detectors[id];
    if (typeof entry === 'object' && entry !== null) {
        return entry;
    }
    return undefined;
}
function mergeConfig(base, override) {
    const merged = {
        ...base,
        ...override,
        detectors: {
            ...base.detectors,
            ...override?.detectors,
        },
    };
    if (!merged.placeholderFormat && merged.placeholderTemplate) {
        merged.placeholderFormat = merged.placeholderTemplate;
    }
    return merged;
}

const EMAIL_PATTERN = /\b[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+\b/g;
function detectEmail(input, options) {
    return scanRegex(input, 'email', EMAIL_PATTERN, 0.95, validateEmail, options);
}

const PHONE_PATTERN = /(?:\+?\d{1,3}[\s.-]?)?(?:\(?\d{2,4}\)?[\s.-]?)?\d{3,4}[\s.-]?\d{3,4}(?:[\s.-]?\d{1,4})?/g;
function detectPhone(input, options) {
    return scanRegex(input, 'phone', PHONE_PATTERN, 0.85, validatePhone, options);
}

const IPV4_PATTERN = /\b(?:(?:25[0-5]|2[0-4]\d|[01]?\d?\d)\.){3}(?:25[0-5]|2[0-4]\d|[01]?\d?\d)\b/g;
const IPV6_PATTERN = /\b(?:[0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}\b|\b(?:[0-9a-fA-F]{1,4}:){1,7}:\b|\b::(?:[0-9a-fA-F]{1,4}:){0,6}[0-9a-fA-F]{1,4}\b/g;
function detectIpv4(input, options) {
    return scanRegex(input, 'ipv4', IPV4_PATTERN, 0.95, validateIpv4, options);
}
function detectIpv6(input, options) {
    return scanRegex(input, 'ipv6', IPV6_PATTERN, 0.95, validateIpv6, options);
}

const CREDIT_CARD_PATTERN = /\b(?:\d[ -]*?){13,19}\b/g;
function detectCreditCard(input, options) {
    return scanRegex(input, 'creditCard', CREDIT_CARD_PATTERN, 0.9, validateCreditCard, options);
}

const IBAN_PATTERN = /\b[A-Z]{2}\d{2}[A-Z0-9 ]{11,30}\b/gi;
function detectIban(input, options) {
    return scanRegex(input, 'iban', IBAN_PATTERN, 0.92, (value) => validateIban(value), options);
}

const URL_PATTERN = /\bhttps?:\/\/[^\s<>"{}|\\^`[\]]+/gi;
function detectUrl(input, options) {
    return scanRegex(input, 'url', URL_PATTERN, 0.9, undefined, options);
}

const TAX_VAT_PATTERN = /\b(?:DE\d{9}|ATU\d{8}|FR[A-Z0-9]{2}\d{9}|GB(?:\d{9}|\d{12}|GD\d{3}|HA\d{3})|NL\d{9}B\d{2})\b/gi;
function detectTaxVatId(input, options) {
    return scanRegex(input, 'taxVatId', TAX_VAT_PATTERN, 0.8, undefined, options);
}

const PASSPORT_PATTERN = /\b[A-Z]{1,2}\d{6,9}\b/g;
function detectPassportId(input, options) {
    return scanRegex(input, 'passportId', PASSPORT_PATTERN, 0.65, undefined, options);
}

const LICENSE_PLATE_PATTERN = /\b[A-ZÄÖÜ]{1,3}[-\s][A-Z]{1,2}\s?\d{1,4}(?:\s?[A-Z]{1,2})?\b/g;
function detectLicensePlate(input, options) {
    return scanRegex(input, 'licensePlate', LICENSE_PLATE_PATTERN, 0.7, undefined, options);
}

const DOB_PATTERN = /\b(?:0?[1-9]|[12]\d|3[01])[./-](?:0?[1-9]|1[0-2])[./-](?:19|20)\d{2}\b/g;
function detectDateOfBirth(input, options) {
    return scanRegex(input, 'dateOfBirth', DOB_PATTERN, 0.6, undefined, options);
}

const PERSON_NAME_PATTERN = /\b(?:Dr\.|Prof\.)?\s?[A-ZÄÖÜ][a-zäöüß]+(?:\s[A-ZÄÖÜ][a-zäöüß]+){1,2}\b/g;
function detectPersonName(input, options) {
    return scanRegex(input, 'personName', PERSON_NAME_PATTERN, 0.55, undefined, options);
}

const COMPANY_NAME_PATTERN = /\b[A-ZÄÖÜ][A-Za-zÄÖÜäöüß&.\s-]{2,40}(?:\s(?:GmbH|AG|UG|KG|OHG|e\.V\.|Ltd\.?|Inc\.?|Corp\.?|LLC))\b/g;
function detectCompanyName(input, options) {
    return scanRegex(input, 'companyName', COMPANY_NAME_PATTERN, 0.6, undefined, options);
}

const POSTAL_ADDRESS_PATTERN = /\b[A-ZÄÖÜ][a-zäöüß]+(?:straße|str\.|weg|platz|allee)\s+\d+[a-z]?(?:,\s*)?\d{5}\s+[A-ZÄÖÜ][a-zäöüß]+\b/gi;
function detectPostalAddress(input, options) {
    return scanRegex(input, 'postalAddress', POSTAL_ADDRESS_PATTERN, 0.65, undefined, options);
}

const GEO_COORD_PATTERN = /\b(?:geo:)?[+-]?\d{1,2}\.\d{3,},\s*[+-]?\d{1,3}\.\d{3,}\b/gi;
function detectGeoCoordinates(input, options) {
    return scanRegex(input, 'geoCoordinates', GEO_COORD_PATTERN, 0.85, validateGeoCoordinates, options);
}

function detectCustomRegex(input, options) {
    if (!options?.pattern) {
        return [];
    }
    try {
        const pattern = new RegExp(options.pattern, options.flags ?? 'g');
        return scanRegex(input, 'customRegex', pattern, 0.75, undefined, options);
    }
    catch {
        return [];
    }
}

const DETECTOR_FNS = {
    email: detectEmail,
    phone: detectPhone,
    ipv4: detectIpv4,
    ipv6: detectIpv6,
    creditCard: detectCreditCard,
    iban: detectIban,
    url: detectUrl,
    taxVatId: detectTaxVatId,
    passportId: detectPassportId,
    licensePlate: detectLicensePlate,
    dateOfBirth: detectDateOfBirth,
    personName: detectPersonName,
    companyName: detectCompanyName,
    postalAddress: detectPostalAddress,
    geoCoordinates: detectGeoCoordinates,
    customRegex: detectCustomRegex,
};
const DETECTOR_PRIORITIES = {
    iban: 100,
    creditCard: 95,
    email: 90,
    ipv6: 88,
    ipv4: 87,
    phone: 85,
    url: 80,
    taxVatId: 75,
    passportId: 70,
    licensePlate: 65,
    dateOfBirth: 60,
    postalAddress: 55,
    geoCoordinates: 54,
    companyName: 50,
    personName: 45,
    customRegex: 40,
};
function createBuiltinDetector(id) {
    const detectFn = DETECTOR_FNS[id];
    return {
        id,
        priority: DETECTOR_PRIORITIES[id],
        detect: (input, options) => detectFn(input, options),
    };
}
function getAllBuiltinDetectors() {
    return Object.keys(DETECTOR_FNS).map(createBuiltinDetector);
}

/**
 * Generated bundle index. Do not edit.
 */

export { createBuiltinDetector, createFindingId, getAllBuiltinDetectors, getDetectorOptions, isDetectorEnabled, mergeConfig, resetFindingCounter, scanRegex };
//# sourceMappingURL=binom-sdk-governance-detectors.mjs.map
