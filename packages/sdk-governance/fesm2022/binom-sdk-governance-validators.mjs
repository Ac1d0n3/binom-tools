function validateEmail(value) {
    if (!value || typeof value !== 'string') {
        return false;
    }
    const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/;
    return emailRegex.test(value.trim());
}

function validatePhone(value) {
    if (!value || typeof value !== 'string') {
        return false;
    }
    const normalized = value.replace(/[\s().-]/g, '');
    if (normalized.startsWith('+')) {
        return /^\+[1-9]\d{6,14}$/.test(normalized);
    }
    return /^[0-9]{7,15}$/.test(normalized);
}

function validateIpv4(value) {
    if (!value || typeof value !== 'string') {
        return false;
    }
    const parts = value.trim().split('.');
    if (parts.length !== 4) {
        return false;
    }
    return parts.every((part) => {
        if (!/^\d{1,3}$/.test(part)) {
            return false;
        }
        const num = Number(part);
        return num >= 0 && num <= 255;
    });
}
function validateIpv6(value) {
    if (!value || typeof value !== 'string') {
        return false;
    }
    const normalized = value.trim().toLowerCase();
    const ipv6Regex = /^(([0-9a-f]{1,4}:){7}[0-9a-f]{1,4}|([0-9a-f]{1,4}:){1,7}:|([0-9a-f]{1,4}:){1,6}:[0-9a-f]{1,4}|([0-9a-f]{1,4}:){1,5}(:[0-9a-f]{1,4}){1,2}|([0-9a-f]{1,4}:){1,4}(:[0-9a-f]{1,4}){1,3}|([0-9a-f]{1,4}:){1,3}(:[0-9a-f]{1,4}){1,4}|([0-9a-f]{1,4}:){1,2}(:[0-9a-f]{1,4}){1,5}|[0-9a-f]{1,4}:((:[0-9a-f]{1,4}){1,6})|:((:[0-9a-f]{1,4}){1,7}|:))$/;
    return ipv6Regex.test(normalized);
}

function luhnCheck(value) {
    const digits = value.replace(/\D/g, '');
    if (digits.length < 13 || digits.length > 19) {
        return false;
    }
    let sum = 0;
    let alternate = false;
    for (let i = digits.length - 1; i >= 0; i--) {
        let n = Number(digits[i]);
        if (alternate) {
            n *= 2;
            if (n > 9) {
                n -= 9;
            }
        }
        sum += n;
        alternate = !alternate;
    }
    return sum % 10 === 0;
}
function validateCreditCard(value) {
    if (!value || typeof value !== 'string') {
        return false;
    }
    const digits = value.replace(/[\s-]/g, '');
    if (!/^\d{13,19}$/.test(digits)) {
        return false;
    }
    return luhnCheck(digits);
}

const IBAN_LENGTHS = {
    AD: 24, AE: 23, AL: 28, AT: 20, AZ: 28, BA: 20, BE: 16, BG: 22, BH: 22,
    BR: 29, BY: 28, CH: 21, CR: 22, CY: 28, CZ: 24, DE: 22, DK: 18, DO: 28,
    EE: 20, EG: 29, ES: 24, FI: 18, FO: 18, FR: 27, GB: 22, GE: 22, GI: 23,
    GL: 18, GR: 27, GT: 28, HR: 21, HU: 28, IE: 22, IL: 23, IS: 26, IT: 27,
    JO: 30, KW: 30, KZ: 20, LB: 28, LC: 32, LI: 21, LT: 20, LU: 20, LV: 21,
    MC: 27, MD: 24, ME: 22, MK: 19, MR: 27, MT: 31, MU: 30, NL: 18, NO: 15,
    PK: 24, PL: 28, PS: 29, PT: 25, QA: 29, RO: 24, RS: 22, SA: 24, SE: 24,
    SI: 19, SK: 24, SM: 27, TN: 24, TR: 26, UA: 29, VA: 22, VG: 24, XK: 20,
};
function mod97(iban) {
    let remainder = iban;
    while (remainder.length > 2) {
        const block = remainder.slice(0, 9);
        remainder = (Number(block) % 97).toString() + remainder.slice(block.length);
    }
    return Number(remainder) % 97;
}
function validateIban(value) {
    if (!value || typeof value !== 'string') {
        return false;
    }
    const iban = value.replace(/\s/g, '').toUpperCase();
    if (!/^[A-Z]{2}\d{2}[A-Z0-9]+$/.test(iban)) {
        return false;
    }
    const country = iban.slice(0, 2);
    const expectedLength = IBAN_LENGTHS[country];
    if (expectedLength && iban.length !== expectedLength) {
        return false;
    }
    const rearranged = iban.slice(4) + iban.slice(0, 4);
    const numeric = rearranged.replace(/[A-Z]/g, (ch) => String(ch.charCodeAt(0) - 55));
    return mod97(numeric) === 1;
}

function validateGeoCoordinates(value) {
    const normalized = value.replace(/^geo:/i, '');
    const parts = normalized.split(',').map((part) => part.trim());
    if (parts.length !== 2) {
        return false;
    }
    const latitude = Number.parseFloat(parts[0]);
    const longitude = Number.parseFloat(parts[1]);
    if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
        return false;
    }
    return Math.abs(latitude) <= 90 && Math.abs(longitude) <= 180;
}

/**
 * Generated bundle index. Do not edit.
 */

export { luhnCheck, validateCreditCard, validateEmail, validateGeoCoordinates, validateIban, validateIpv4, validateIpv6, validatePhone };
//# sourceMappingURL=binom-sdk-governance-validators.mjs.map
