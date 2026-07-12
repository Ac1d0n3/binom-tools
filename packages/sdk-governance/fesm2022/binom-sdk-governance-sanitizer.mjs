import { BN_GOVERNANCE_DEFAULT_CONFIG } from '@binom/sdk-governance/types';
import { detect, sanitize, appendGovernanceRules, buildOutboundUserMessage, mask, restore } from '@binom/sdk-governance/core';
import { mergeConfig } from '@binom/sdk-governance/detectors';

class BnGovernanceSanitizerSvc {
    config;
    constructor(defaultConfig) {
        this.config = mergeConfig(BN_GOVERNANCE_DEFAULT_CONFIG, defaultConfig);
    }
    detect(input, config) {
        return detect(input, this.mergeRuntimeConfig(config));
    }
    sanitize(input, config) {
        return sanitize(input, this.mergeRuntimeConfig(config));
    }
    appendGovernanceRules(sanitized, rules) {
        return appendGovernanceRules(sanitized, rules);
    }
    buildOutboundUserMessage(input, rules, config) {
        return buildOutboundUserMessage(input, rules, this.mergeRuntimeConfig(config));
    }
    mask(input, config) {
        return mask(input, this.mergeRuntimeConfig(config));
    }
    restore(input, mappingOrId, options) {
        return restore(input, mappingOrId, options);
    }
    updateConfig(config) {
        this.config = mergeConfig(this.config, config);
    }
    getConfig() {
        return { ...this.config, detectors: { ...this.config.detectors } };
    }
    mergeRuntimeConfig(config) {
        return mergeConfig(this.config, config);
    }
}

/**
 * Generated bundle index. Do not edit.
 */

export { BnGovernanceSanitizerSvc };
//# sourceMappingURL=binom-sdk-governance-sanitizer.mjs.map
