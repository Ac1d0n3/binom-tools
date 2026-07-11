/**
 * @typedef {'error' | 'warning'} ValidationSeverity
 * @typedef {'govMacro' | 'policy' | 'gate' | 'recommend' | 'schema'} GeneratorContext
 * @typedef {Object} ValidationIssue
 * @property {string} code
 * @property {string} messageKey
 * @property {ValidationSeverity} severity
 * @property {Record<string, string | number>} [params]
 * @property {string} [field]
 * @property {number} [rowIndex]
 */

/**
 * @param {string} pattern
 * @returns {{ ok: true } | { ok: false, detail: string }}
 */
export function validateRegex(pattern) {
    const trimmed = pattern.trim();
    if (!trimmed) {
        return { ok: false, detail: 'empty' };
    }
    try {
        // eslint-disable-next-line no-new
        new RegExp(trimmed);
        return { ok: true };
    } catch (error) {
        const message = error instanceof Error ? error.message : String(error);
        return { ok: false, detail: message };
    }
}

/**
 * @param {import('./heuristic-rules.js').HeuristicRule[]} rules
 * @returns {ValidationIssue[]}
 */
export function validateNameHeuristicRules(rules) {
    /** @type {ValidationIssue[]} */
    const issues = [];
    const seen = new Map();

    rules.forEach((rule, index) => {
        const row = index + 1;
        const pattern = rule.pattern?.trim() ?? '';
        if (!pattern) {
            issues.push({
                code: 'name_pattern_empty',
                messageKey: 'validation.namePatternEmpty',
                severity: 'error',
                field: 'pattern',
                rowIndex: index,
                params: { row },
            });
            return;
        }

        const key = pattern.toLowerCase();
        if (seen.has(key)) {
            issues.push({
                code: 'name_pattern_duplicate',
                messageKey: 'validation.namePatternDuplicate',
                severity: 'warning',
                field: 'pattern',
                rowIndex: index,
                params: { row, pattern },
            });
        } else {
            seen.set(key, index);
        }
    });

    return issues;
}

/**
 * @param {import('./content-heuristic-rules.js').ContentHeuristicRule[]} rules
 * @param {{ rawMinRates?: (number | string | null | undefined)[] }} [options]
 * @returns {ValidationIssue[]}
 */
export function validateContentHeuristicRules(rules, options = {}) {
    /** @type {ValidationIssue[]} */
    const issues = [];
    const seen = new Map();

    rules.forEach((rule, index) => {
        const row = index + 1;
        const regex = rule.regex?.trim() ?? '';

        if (!regex) {
            issues.push({
                code: 'regex_empty',
                messageKey: 'validation.regexEmpty',
                severity: 'error',
                field: 'regex',
                rowIndex: index,
                params: { row },
            });
            return;
        }

        const regexResult = validateRegex(regex);
        if (!regexResult.ok) {
            issues.push({
                code: 'regex_invalid',
                messageKey: 'validation.regexInvalid',
                severity: 'error',
                field: 'regex',
                rowIndex: index,
                params: { row, detail: regexResult.detail },
            });
        }

        const rawRate = options.rawMinRates?.[index] ?? rule.minMatchRate;
        const num = Number(rawRate);
        if (rawRate !== undefined && rawRate !== '' && (!Number.isFinite(num) || num <= 0 || num > 100)) {
            issues.push({
                code: 'min_match_rate_invalid',
                messageKey: 'validation.minMatchRateInvalid',
                severity: 'error',
                field: 'minMatchRate',
                rowIndex: index,
                params: { row },
            });
        }

        if (seen.has(regex)) {
            issues.push({
                code: 'regex_duplicate',
                messageKey: 'validation.regexDuplicate',
                severity: 'warning',
                field: 'regex',
                rowIndex: index,
                params: { row },
            });
        } else {
            seen.set(regex, index);
        }
    });

    return issues;
}

/**
 * @param {import('./demo-model.js').DbtModelState} state
 * @returns {ValidationIssue[]}
 */
export function validateAccessConfig(state) {
    /** @type {ValidationIssue[]} */
    const issues = [];

    if (state.useAccessRoles) {
        if (!state.defaultAccessRoles?.length) {
            issues.push({
                code: 'access_roles_empty',
                messageKey: 'validation.accessRolesEmpty',
                severity: 'warning',
            });
        }
    } else {
        const masked = state.accessRules?.masked ?? [];
        const unmasked = state.accessRules?.unmasked ?? [];
        if (!masked.length && !unmasked.length) {
            issues.push({
                code: 'access_rules_empty',
                messageKey: 'validation.accessRulesEmpty',
                severity: 'error',
            });
        }
    }

    return issues;
}

/**
 * @param {import('./demo-model.js').DbtModelState} state
 * @returns {ValidationIssue[]}
 */
export function validateModelState(state) {
    /** @type {ValidationIssue[]} */
    const issues = [];

    if (!state.modelName?.trim()) {
        issues.push({
            code: 'model_name_empty',
            messageKey: 'validation.modelNameEmpty',
            severity: 'error',
        });
    }

    const seen = new Map();
    (state.columns ?? []).forEach((column, index) => {
        const name = column.name?.trim() ?? '';
        if (!name) {
            issues.push({
                code: 'column_name_empty',
                messageKey: 'validation.columnNameEmpty',
                severity: 'error',
                rowIndex: index,
                params: { row: index + 1 },
            });
            return;
        }
        if (seen.has(name)) {
            issues.push({
                code: 'column_name_duplicate',
                messageKey: 'validation.columnNameDuplicate',
                severity: 'error',
                params: { name },
            });
        } else {
            seen.set(name, index);
        }
    });

    return issues;
}

/**
 * @param {import('./demo-model.js').DbtModelState} state
 * @returns {ValidationIssue[]}
 */
export function validateReviewRoles(state) {
    if (state.defaultReviewRoles?.length) {
        return [];
    }
    return [
        {
            code: 'review_roles_empty',
            messageKey: 'validation.reviewRolesEmpty',
            severity: 'warning',
        },
    ];
}

/**
 * @param {import('./demo-model.js').DbtModelState} state
 * @param {GeneratorContext} context
 * @returns {ValidationIssue[]}
 */
export function collectGeneratorIssues(state, context) {
    /** @type {ValidationIssue[]} */
    const issues = [];

    if (context === 'govMacro' || context === 'policy' || context === 'recommend') {
        issues.push(...validateAccessConfig(state));
    }

    if (context === 'policy' || context === 'schema') {
        issues.push(...validateModelState(state));
    }

    if (context === 'gate') {
        issues.push(...validateReviewRoles(state));
    }

    if (context === 'recommend') {
        issues.push(...validateNameHeuristicRules(state.nameHeuristicRules ?? []));
        issues.push(...validateContentHeuristicRules(state.contentHeuristicRules ?? []));
    }

    return issues;
}

/** @param {ValidationIssue[]} issues @returns {boolean} */
export function hasValidationErrors(issues) {
    return issues.some((issue) => issue.severity === 'error');
}

/**
 * @param {string} code
 * @returns {string}
 */
export function yamlErrorToMessageKey(code) {
    const map = {
        empty: 'validation.yaml.empty',
        missing_model: 'validation.yaml.missingModel',
        missing_columns: 'validation.yaml.missingColumns',
        invalid_structure: 'validation.yaml.invalidStructure',
        unsupported: 'validation.yaml.unsupported',
    };
    return map[/** @type {keyof typeof map} */ (code)] ?? 'validation.yaml.invalidStructure';
}
