import { describe, expect, it } from 'vitest';
import {
    collectGeneratorIssues,
    hasValidationErrors,
    validateContentHeuristicRules,
    validateNameHeuristicRules,
    validateRegex,
} from './validation.js';
import { createDefaultModelState } from './demo-model.js';

describe('validateRegex', () => {
    it('accepts valid patterns', () => {
        expect(validateRegex('^test@example\\.com$').ok).toBe(true);
    });

    it('rejects invalid patterns', () => {
        const result = validateRegex('([unclosed');
        expect(result.ok).toBe(false);
        if (!result.ok) {
            expect(result.detail).toBeTruthy();
        }
    });

    it('rejects empty patterns', () => {
        expect(validateRegex('   ').ok).toBe(false);
    });
});

describe('validateNameHeuristicRules', () => {
    it('flags empty patterns as errors', () => {
        const issues = validateNameHeuristicRules([{ pattern: '', piiType: 'email' }]);
        expect(issues.some((issue) => issue.code === 'name_pattern_empty')).toBe(true);
        expect(hasValidationErrors(issues)).toBe(true);
    });

    it('warns on duplicate patterns', () => {
        const issues = validateNameHeuristicRules([
            { pattern: 'email', piiType: 'email' },
            { pattern: 'EMAIL', piiType: 'phone' },
        ]);
        expect(issues.some((issue) => issue.code === 'name_pattern_duplicate')).toBe(true);
        expect(hasValidationErrors(issues)).toBe(false);
    });
});

describe('validateContentHeuristicRules', () => {
    it('flags invalid regex rows', () => {
        const issues = validateContentHeuristicRules([{ regex: '(?', piiType: 'email', minMatchRate: 5 }]);
        expect(issues.some((issue) => issue.code === 'regex_invalid')).toBe(true);
    });

    it('flags invalid min match rate', () => {
        const issues = validateContentHeuristicRules(
            [{ regex: '^a$', piiType: 'email', minMatchRate: 5 }],
            { rawMinRates: [200] },
        );
        expect(issues.some((issue) => issue.code === 'min_match_rate_invalid')).toBe(true);
    });
});

describe('collectGeneratorIssues', () => {
    it('requires masked or unmasked roles in rule-based mode', () => {
        const state = createDefaultModelState();
        state.useAccessRoles = false;
        state.accessRules = { masked: [], unmasked: [] };
        const issues = collectGeneratorIssues(state, 'govMacro');
        expect(hasValidationErrors(issues)).toBe(true);
    });
});
