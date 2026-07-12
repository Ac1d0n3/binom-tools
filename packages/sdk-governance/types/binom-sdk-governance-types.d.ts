type BnGovernanceDetectorId = 'personName' | 'email' | 'phone' | 'postalAddress' | 'geoCoordinates' | 'companyName' | 'url' | 'ipv4' | 'ipv6' | 'creditCard' | 'iban' | 'taxVatId' | 'passportId' | 'licensePlate' | 'dateOfBirth' | 'customRegex';
type BnGovernanceOverlapStrategy = 'longest' | 'priority';
type BnGovernancePlaceholderType = 'PERSON' | 'EMAIL' | 'PHONE' | 'ADDRESS' | 'GEO' | 'COMPANY' | 'URL' | 'IPV4' | 'IPV6' | 'CARD' | 'IBAN' | 'TAX_VAT' | 'PASSPORT' | 'LICENSE_PLATE' | 'DOB' | 'CUSTOM';
interface BnDetectorOptions {
    enabled?: boolean;
    minConfidence?: number;
    /** Custom regex pattern — used by customRegex detector. */
    pattern?: string;
    flags?: string;
    label?: string;
}
interface BnGovernanceMappingStore {
    save(mapping: BnGovernanceMapping): void;
    get(id: string): BnGovernanceMapping | undefined;
    delete(id: string): void;
    clearExpiredMappings(referenceDate?: Date): number;
    listIds(): string[];
}
interface BnGovernanceConfig {
    detectors: Partial<Record<BnGovernanceDetectorId, boolean | BnDetectorOptions>>;
    maskChar?: string;
    /** @deprecated Use placeholderFormat instead. */
    placeholderTemplate?: string;
    placeholderFormat?: string;
    reusePlaceholders?: boolean;
    mappingTtlMs?: number;
    mappingStore?: BnGovernanceMappingStore;
    overlapStrategy?: BnGovernanceOverlapStrategy;
}
interface BnGovernanceFinding {
    id: string;
    type: BnGovernanceDetectorId | string;
    start: number;
    end: number;
    value: string;
    confidence: number;
    placeholder?: string;
}
interface BnGovernanceMappingMeta {
    createdAt: string;
    expiresAt?: string;
    detectorIds: (BnGovernanceDetectorId | string)[];
    placeholderFormat: string;
}
interface BnGovernanceMapping {
    id: string;
    placeholders: Record<string, string>;
    meta: BnGovernanceMappingMeta;
}
interface BnGovernanceResult {
    text: string;
    /** @deprecated Use text instead. Kept for backward compatibility. */
    output: string;
    findings: BnGovernanceFinding[];
    mapping: BnGovernanceMapping;
    placeholderFormat: string;
    createdAt: string;
    expiresAt?: string;
}
interface BnGovernanceOutboundResult extends BnGovernanceResult {
    /** Sanitized user prompt with governance rules appended — ready to send to an LLM. */
    outbound: string;
}
/** Default token-handling rules appended to outbound user prompts. */
declare const BN_GOVERNANCE_DEFAULT_LLM_RULES = "- Keep all [[BNM_...]] placeholder tokens exactly as provided (e.g. [[BNM_EMAIL_...]], [[BNM_PHONE_...]], [[BNM_URL_...]], [[BNM_CARD_...]], [[BNM_ADDRESS_...]], [[BNM_GEO_...]]).\n- When mentioning sensitive values in replies, repeat those tokens verbatim.\n- Do not invent, replace, decode, or remove placeholder tokens.";
interface BnGovernanceDetector {
    id: BnGovernanceDetectorId | string;
    priority: number;
    detect(input: string, options?: BnDetectorOptions): BnGovernanceFinding[];
    validate?(value: string): boolean;
}
interface BnGovernanceDetectorDef {
    id: BnGovernanceDetectorId | string;
    priority?: number;
    pattern?: RegExp;
    validate?: (value: string) => boolean;
    confidence?: number;
    detect?: (input: string, options?: BnDetectorOptions) => BnGovernanceFinding[];
}
interface BnGovernanceRestoreOptions {
    store?: BnGovernanceMappingStore;
}
declare const BN_GOVERNANCE_DEFAULT_PLACEHOLDER_FORMAT = "[[BNM_{{TYPE}}_{{KEY}}]]";
declare const BN_GOVERNANCE_PLACEHOLDER_TYPE_MAP: Record<BnGovernanceDetectorId, BnGovernancePlaceholderType>;
declare const BN_GOVERNANCE_DETECTOR_IDS: BnGovernanceDetectorId[];
declare const BN_GOVERNANCE_DEFAULT_CONFIG: BnGovernanceConfig;

export { BN_GOVERNANCE_DEFAULT_CONFIG, BN_GOVERNANCE_DEFAULT_LLM_RULES, BN_GOVERNANCE_DEFAULT_PLACEHOLDER_FORMAT, BN_GOVERNANCE_DETECTOR_IDS, BN_GOVERNANCE_PLACEHOLDER_TYPE_MAP };
export type { BnDetectorOptions, BnGovernanceConfig, BnGovernanceDetector, BnGovernanceDetectorDef, BnGovernanceDetectorId, BnGovernanceFinding, BnGovernanceMapping, BnGovernanceMappingMeta, BnGovernanceMappingStore, BnGovernanceOutboundResult, BnGovernanceOverlapStrategy, BnGovernancePlaceholderType, BnGovernanceRestoreOptions, BnGovernanceResult };
