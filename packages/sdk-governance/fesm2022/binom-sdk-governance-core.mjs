import { BN_GOVERNANCE_PLACEHOLDER_TYPE_MAP, BN_GOVERNANCE_DEFAULT_CONFIG } from '@binom/sdk-governance/types';
import { getRegisteredDetectors } from '@binom/sdk-governance/registry';
import { mergeConfig, resetFindingCounter, isDetectorEnabled, getDetectorOptions } from '@binom/sdk-governance/detectors';

function resolvePlaceholderType(detectorType) {
    if (detectorType in BN_GOVERNANCE_PLACEHOLDER_TYPE_MAP) {
        return BN_GOVERNANCE_PLACEHOLDER_TYPE_MAP[detectorType];
    }
    return String(detectorType).toUpperCase();
}
function buildPlaceholderToken(finding, format, key, index) {
    const typeLabel = resolvePlaceholderType(finding.type);
    return format
        .replace(/\{\{TYPE\}\}/g, typeLabel)
        .replace(/\{\{KEY\}\}/g, key)
        .replace(/\{\{index\}\}/g, String(index));
}

const KEY_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
function generatePlaceholderKey(length = 4) {
    const size = Math.max(1, length);
    const bytes = new Uint8Array(size);
    if (typeof crypto !== 'undefined' && typeof crypto.getRandomValues === 'function') {
        crypto.getRandomValues(bytes);
    }
    else {
        for (let i = 0; i < size; i += 1) {
            bytes[i] = Math.floor(Math.random() * 256);
        }
    }
    let key = '';
    for (let i = 0; i < size; i += 1) {
        key += KEY_ALPHABET[bytes[i] % KEY_ALPHABET.length];
    }
    return key;
}

class BnPlaceholderSession {
    reuse;
    format;
    reuseCache = new Map();
    index = 1;
    constructor(format, reusePlaceholders = true) {
        this.format = format;
        this.reuse = reusePlaceholders;
    }
    createPlaceholder(finding) {
        const cacheKey = `${finding.type}::${finding.value}`;
        if (this.reuse) {
            const cached = this.reuseCache.get(cacheKey);
            if (cached) {
                return cached;
            }
        }
        const key = generatePlaceholderKey();
        const placeholder = buildPlaceholderToken(finding, this.format, key, this.index);
        this.index += 1;
        if (this.reuse) {
            this.reuseCache.set(cacheKey, placeholder);
        }
        return placeholder;
    }
}

function createMappingId() {
    if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
        return crypto.randomUUID();
    }
    return `bnm_${Date.now()}_${Math.random().toString(36).slice(2, 10)}`;
}
function isMappingExpired(mapping, referenceDate = new Date()) {
    const expiresAt = mapping.meta?.expiresAt;
    if (!expiresAt) {
        return false;
    }
    return referenceDate.getTime() >= Date.parse(expiresAt);
}
function resolvePlaceholderFormat(config, fallback = '[[BNM_{{TYPE}}_{{KEY}}]]') {
    return config.placeholderFormat ?? config.placeholderTemplate ?? fallback;
}

function resolveConfig(config) {
    return mergeConfig(BN_GOVERNANCE_DEFAULT_CONFIG, config);
}
function overlaps(a, b) {
    return a.start < b.end && b.start < a.end;
}
function resolveOverlaps(findings, strategy, detectors) {
    const sorted = [...findings].sort((a, b) => {
        if (strategy === 'priority') {
            const priorityDiff = (detectors.get(b.type) ?? 0) - (detectors.get(a.type) ?? 0);
            if (priorityDiff !== 0) {
                return priorityDiff;
            }
        }
        const lengthDiff = b.end - b.start - (a.end - a.start);
        if (lengthDiff !== 0) {
            return lengthDiff;
        }
        return a.start - b.start;
    });
    const accepted = [];
    for (const finding of sorted) {
        const hasOverlap = accepted.some((existing) => overlaps(existing, finding));
        if (!hasOverlap) {
            accepted.push(finding);
        }
    }
    return accepted.sort((a, b) => a.start - b.start);
}
function maskValue(finding, maskChar) {
    const { value, type } = finding;
    switch (type) {
        case 'email': {
            const [local, domain] = value.split('@');
            if (!domain) {
                return maskChar.repeat(value.length);
            }
            const visible = local.slice(0, 1);
            return `${visible}${maskChar.repeat(Math.max(local.length - 1, 1))}@${domain}`;
        }
        case 'creditCard':
        case 'iban': {
            const visible = value.slice(-4);
            return `${maskChar.repeat(Math.max(value.length - 4, 0))}${visible}`;
        }
        case 'phone': {
            const digits = value.replace(/\D/g, '');
            const visible = digits.slice(-2);
            return value.replace(/\d/g, maskChar).replace(new RegExp(`${maskChar}{2}$`), visible);
        }
        default:
            if (value.length <= 2) {
                return maskChar.repeat(value.length);
            }
            return `${value[0]}${maskChar.repeat(value.length - 2)}${value[value.length - 1]}`;
    }
}
function buildEmptyResult(text, placeholderFormat, createdAt, expiresAt) {
    return {
        text,
        output: text,
        findings: [],
        mapping: {
            id: createMappingId(),
            placeholders: {},
            meta: {
                createdAt,
                expiresAt,
                detectorIds: [],
                placeholderFormat,
            },
        },
        placeholderFormat,
        createdAt,
        expiresAt,
    };
}
function resolveMapping(mappingOrId, options) {
    if (typeof mappingOrId === 'string') {
        return options?.store?.get(mappingOrId);
    }
    return mappingOrId;
}
function detect(input, config) {
    if (!input || typeof input !== 'string') {
        return [];
    }
    resetFindingCounter();
    const resolved = resolveConfig(config);
    const detectors = getRegisteredDetectors();
    const priorityMap = new Map(detectors.map((d) => [d.id, d.priority]));
    const findings = [];
    for (const detector of detectors) {
        const id = detector.id;
        if (!isDetectorEnabled(resolved.detectors, id)) {
            continue;
        }
        const options = getDetectorOptions(resolved.detectors, id);
        const minConfidence = options?.minConfidence ?? 0;
        const detected = detector.detect(input, options).filter((f) => f.confidence >= minConfidence);
        findings.push(...detected);
    }
    return resolveOverlaps(findings, resolved.overlapStrategy ?? 'longest', priorityMap);
}
function sanitize(input, config) {
    const resolved = resolveConfig(config);
    const placeholderFormat = resolvePlaceholderFormat(resolved);
    const createdAt = new Date().toISOString();
    const expiresAt = resolved.mappingTtlMs
        ? new Date(Date.parse(createdAt) + resolved.mappingTtlMs).toISOString()
        : undefined;
    if (!input || typeof input !== 'string') {
        return buildEmptyResult(input ?? '', placeholderFormat, createdAt, expiresAt);
    }
    const findings = detect(input, resolved);
    const placeholders = {};
    const session = new BnPlaceholderSession(placeholderFormat, resolved.reusePlaceholders ?? true);
    let text = input;
    const sorted = [...findings].sort((a, b) => b.start - a.start);
    for (const finding of sorted) {
        const placeholder = session.createPlaceholder(finding);
        finding.placeholder = placeholder;
        placeholders[placeholder] = finding.value;
        text = text.slice(0, finding.start) + placeholder + text.slice(finding.end);
    }
    const mapping = {
        id: createMappingId(),
        placeholders,
        meta: {
            createdAt,
            expiresAt,
            detectorIds: [...new Set(findings.map((f) => f.type))],
            placeholderFormat,
        },
    };
    if (resolved.mappingStore) {
        resolved.mappingStore.save(mapping);
    }
    return {
        text,
        output: text,
        findings: [...findings].sort((a, b) => a.start - b.start),
        mapping,
        placeholderFormat,
        createdAt,
        expiresAt,
    };
}
function mask(input, config) {
    const resolved = resolveConfig(config);
    const placeholderFormat = resolvePlaceholderFormat(resolved);
    const createdAt = new Date().toISOString();
    const expiresAt = resolved.mappingTtlMs
        ? new Date(Date.parse(createdAt) + resolved.mappingTtlMs).toISOString()
        : undefined;
    if (!input || typeof input !== 'string') {
        return buildEmptyResult(input ?? '', placeholderFormat, createdAt, expiresAt);
    }
    const findings = detect(input, resolved);
    const maskChar = resolved.maskChar ?? '*';
    let text = input;
    const sorted = [...findings].sort((a, b) => b.start - a.start);
    for (const finding of sorted) {
        const masked = maskValue(finding, maskChar);
        text = text.slice(0, finding.start) + masked + text.slice(finding.end);
    }
    return {
        text,
        output: text,
        findings,
        mapping: {
            id: createMappingId(),
            placeholders: {},
            meta: {
                createdAt,
                expiresAt,
                detectorIds: [...new Set(findings.map((f) => f.type))],
                placeholderFormat,
            },
        },
        placeholderFormat,
        createdAt,
        expiresAt,
    };
}
function restore(input, mappingOrId, options) {
    if (!input) {
        return input ?? '';
    }
    const mapping = resolveMapping(mappingOrId, options);
    if (!mapping?.placeholders) {
        return input;
    }
    if (isMappingExpired(mapping)) {
        return input;
    }
    let output = input;
    const keys = Object.keys(mapping.placeholders).sort((a, b) => b.length - a.length);
    for (const key of keys) {
        output = output.split(key).join(mapping.placeholders[key]);
    }
    return output;
}

/** Append governance rules to a sanitized user prompt (does not alter app role prompts). */
function appendGovernanceRules(sanitized, rules) {
    const body = sanitized.trim();
    const governanceRules = rules.trim();
    if (!governanceRules) {
        return body;
    }
    if (!body) {
        return governanceRules;
    }
    return `${body}\n\n${governanceRules}`;
}
/** Sanitize PII and append governance rules for outbound LLM user messages. */
function buildOutboundUserMessage(input, rules, config) {
    const result = sanitize(input, config);
    const outbound = appendGovernanceRules(result.text, rules);
    return {
        ...result,
        outbound,
    };
}

/**
 * Generated bundle index. Do not edit.
 */

export { appendGovernanceRules, buildOutboundUserMessage, detect, mask, restore, sanitize };
//# sourceMappingURL=binom-sdk-governance-core.mjs.map
