import { BnGovernanceConfig, BnGovernanceFinding, BnGovernanceResult, BnGovernanceOutboundResult, BnGovernanceMapping, BnGovernanceRestoreOptions } from '@binom/sdk-governance/types';

declare class BnGovernanceSanitizerSvc {
    private config;
    constructor(defaultConfig?: Partial<BnGovernanceConfig>);
    detect(input: string, config?: Partial<BnGovernanceConfig>): BnGovernanceFinding[];
    sanitize(input: string, config?: Partial<BnGovernanceConfig>): BnGovernanceResult;
    appendGovernanceRules(sanitized: string, rules: string): string;
    buildOutboundUserMessage(input: string, rules: string, config?: Partial<BnGovernanceConfig>): BnGovernanceOutboundResult;
    mask(input: string, config?: Partial<BnGovernanceConfig>): BnGovernanceResult;
    restore(input: string, mappingOrId: BnGovernanceMapping | string, options?: BnGovernanceRestoreOptions): string;
    updateConfig(config: Partial<BnGovernanceConfig>): void;
    getConfig(): BnGovernanceConfig;
    private mergeRuntimeConfig;
}

export { BnGovernanceSanitizerSvc };
