import { BnGovernanceConfig, BnGovernanceFinding, BnGovernanceResult, BnGovernanceMapping, BnGovernanceRestoreOptions, BnGovernanceOutboundResult } from '@binom/sdk-governance/types';

declare function detect(input: string, config?: Partial<BnGovernanceConfig>): BnGovernanceFinding[];
declare function sanitize(input: string, config?: Partial<BnGovernanceConfig>): BnGovernanceResult;
declare function mask(input: string, config?: Partial<BnGovernanceConfig>): BnGovernanceResult;
declare function restore(input: string, mappingOrId: BnGovernanceMapping | string, options?: BnGovernanceRestoreOptions): string;

/** Append governance rules to a sanitized user prompt (does not alter app role prompts). */
declare function appendGovernanceRules(sanitized: string, rules: string): string;
/** Sanitize PII and append governance rules for outbound LLM user messages. */
declare function buildOutboundUserMessage(input: string, rules: string, config?: Partial<BnGovernanceConfig>): BnGovernanceOutboundResult;

export { appendGovernanceRules, buildOutboundUserMessage, detect, mask, restore, sanitize };
