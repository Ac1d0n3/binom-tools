import { BnGovernanceSanitizerSvc } from '@binom/sdk-governance/sanitizer';

const PLACEHOLDER_TOKEN = '{{PLACEHOLDERS}}';

/** @param {string[]} placeholders */
function formatPlaceholderList(placeholders) {
    if (placeholders.length === 0) return '';
    if (placeholders.length === 1) return placeholders[0];
    if (placeholders.length === 2) return `${placeholders[0]} and ${placeholders[1]}`;
    return `${placeholders.slice(0, -1).join(', ')}, and ${placeholders[placeholders.length - 1]}`;
}

export class GovernanceState {
    /** @param {{ reusePlaceholders?: boolean }} [options] */
    constructor(options = {}) {
        this.sanitizer = new BnGovernanceSanitizerSvc({
            reusePlaceholders: options.reusePlaceholders ?? true,
        });

        this.input = '';
        this.original = '';
        this.sanitized = '';
        this.outbound = '';
        this.aiResponse = '';
        this.restored = '';
        this.aiResponseTemplate = '';
        this.llmRules = '';
        this.findings = [];
        /** @type {import('@binom/sdk-governance/types').BnGovernanceMapping | null} */
        this.mapping = null;
    }

    /** @param {string} value */
    setInput(value) {
        this.input = value;
        this.original = value;
        this.sanitized = '';
        this.outbound = '';
        this.mapping = null;
        this.restored = '';
    }

    /** @param {string} rules */
    setLlmRules(rules) {
        this.llmRules = rules;
    }

    /** @param {string} template */
    setAiResponseTemplate(template) {
        this.aiResponseTemplate = template;
    }

    /** @param {string} value */
    setAiResponse(value) {
        this.aiResponse = value;
        this.restored = '';
    }

    runSanitize() {
        const source = this.original || this.input;
        const rules = this.llmRules.trim();

        if (rules) {
            const result = this.sanitizer.buildOutboundUserMessage(source, rules);
            this.original = source;
            this.input = result.outbound;
            this.sanitized = result.text;
            this.outbound = result.outbound;
            this.findings = result.findings;
            this.mapping = result.mapping;
        } else {
            const result = this.sanitizer.sanitize(source);
            this.original = source;
            this.input = result.text;
            this.sanitized = result.text;
            this.outbound = result.text;
            this.findings = result.findings;
            this.mapping = result.mapping;
        }

        this.aiResponse = '';
        this.restored = '';
    }

    buildSimulatedAiResponse(template = this.aiResponseTemplate) {
        const placeholders = this.mapping ? Object.keys(this.mapping.placeholders) : [];
        const placeholderText = formatPlaceholderList(placeholders);
        const sanitized = this.sanitized;

        let aiResponse = template;
        if (template.includes(PLACEHOLDER_TOKEN)) {
            aiResponse = template.replace(PLACEHOLDER_TOKEN, placeholderText || sanitized);
        } else if (sanitized) {
            aiResponse = `${template}\n\n${sanitized}`;
        }

        this.aiResponse = aiResponse;
        this.restored = '';
    }

    runRestore() {
        if (!this.mapping || !this.aiResponse) return;
        this.restored = this.sanitizer.restore(this.aiResponse, this.mapping);
    }

    canRestore() {
        return Boolean(this.mapping && this.aiResponse);
    }

    hasFindings() {
        return this.findings.length > 0;
    }

    findingCount() {
        return this.findings.length;
    }
}
