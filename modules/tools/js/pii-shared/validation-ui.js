import { tValidation } from './validation-labels.js';
import { hasValidationErrors } from './validation.js';

/**
 * @param {string} key
 * @param {Record<string, string | number>} [params]
 * @param {(key: string, params?: Record<string, string | number>) => string} [t]
 * @returns {string}
 */
function resolveMessage(key, params, t) {
    if (t) {
        return t(key, params);
    }
    return tValidation('en', key, params);
}

/**
 * @param {import('./validation.js').ValidationIssue} issue
 * @param {(key: string, params?: Record<string, string | number>) => string} t
 * @returns {string}
 */
export function formatValidationIssue(issue, t) {
    return resolveMessage(issue.messageKey, issue.params, t);
}

/**
 * @param {HTMLElement | null} el
 * @param {import('./validation.js').ValidationIssue[]} issues
 * @param {(key: string, params?: Record<string, string | number>) => string} t
 */
export function renderValidationBanner(el, issues, t) {
    if (!el) return;

    if (!issues.length) {
        el.hidden = true;
        el.innerHTML = '';
        el.classList.remove('tools-validation-banner--has-errors', 'tools-validation-banner--has-warnings');
        return;
    }

    const hasErrors = issues.some((issue) => issue.severity === 'error');
    const hasWarnings = issues.some((issue) => issue.severity === 'warning');
    el.hidden = false;
    el.classList.toggle('tools-validation-banner--has-errors', hasErrors);
    el.classList.toggle('tools-validation-banner--has-warnings', !hasErrors && hasWarnings);

    el.innerHTML = `<ul class="tools-validation-banner__list">${issues
        .map((issue) => {
            const text = formatValidationIssue(issue, t);
            return `<li class="tools-validation-banner__item tools-validation-banner__item--${issue.severity}">${escapeHtml(text)}</li>`;
        })
        .join('')}</ul>`;
}

/**
 * @param {HTMLElement | null} el
 * @param {string} message
 */
export function renderValidationMessage(el, message) {
    if (!el) return;
    el.hidden = false;
    el.classList.add('tools-validation-banner--has-errors');
    el.classList.remove('tools-validation-banner--has-warnings');
    el.innerHTML = `<ul class="tools-validation-banner__list"><li class="tools-validation-banner__item tools-validation-banner__item--error">${escapeHtml(message)}</li></ul>`;
}

/**
 * @param {HTMLElement | null} input
 * @param {boolean} hasError
 */
export function markFieldInvalid(input, hasError) {
    if (!input) return;
    input.classList.toggle('tools-input--invalid', hasError);
    input.setAttribute('aria-invalid', hasError ? 'true' : 'false');
}

/**
 * @param {ParentNode | null} container
 * @param {string} rowSelector
 * @param {string} fieldSelector
 * @param {import('./validation.js').ValidationIssue[]} issues
 * @param {string} fieldName
 */
export function markInvalidRuleRows(container, rowSelector, fieldSelector, issues, fieldName) {
    if (!container) return;

    container.querySelectorAll(rowSelector).forEach((row, index) => {
        const fieldIssues = issues.filter(
            (issue) => issue.field === fieldName && issue.rowIndex === index && issue.severity === 'error',
        );
        const input = /** @type {HTMLElement | null} */ (row.querySelector(fieldSelector));
        markFieldInvalid(input, fieldIssues.length > 0);
    });
}

/**
 * @param {() => string} buildFn
 * @returns {{ ok: true, value: string } | { ok: false, errorMessage: string }}
 */
export function safeBuildOutput(buildFn) {
    try {
        return { ok: true, value: buildFn() };
    } catch (error) {
        const detail = error instanceof Error ? error.message : String(error);
        return { ok: false, errorMessage: detail };
    }
}

/**
 * @param {(HTMLElement | null)[]} pres
 * @param {string} [blockedMessage]
 */
export function clearOutputPres(pres, blockedMessage = '') {
    pres.forEach((pre) => {
        if (pre) pre.textContent = blockedMessage;
    });
}

/** @param {string} value */
function escapeHtml(value) {
    return value
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}

/**
 * @param {{
 *   bannerEl: HTMLElement | null,
 *   outputPres: (HTMLElement | null)[],
 *   issues: import('./validation.js').ValidationIssue[],
 *   builds: Array<{ el: HTMLElement | null, fn: () => string }>,
 *   t: (key: string, params?: Record<string, string | number>) => string,
 * }} options
 */
export function renderValidatedOutputs({ bannerEl, outputPres, issues, builds, t }) {
    renderValidationBanner(bannerEl, issues, t);

    if (hasValidationErrors(issues)) {
        clearOutputPres(outputPres, t('validation.outputBlocked'));
        return;
    }

    for (const { el, fn } of builds) {
        if (!el) continue;
        const result = safeBuildOutput(fn);
        if (!result.ok) {
            renderValidationBanner(
                bannerEl,
                [
                    {
                        code: 'build_failed',
                        messageKey: 'validation.buildFailed',
                        severity: 'error',
                        params: { detail: result.errorMessage },
                    },
                ],
                t,
            );
            clearOutputPres(outputPres, t('validation.outputBlocked'));
            return;
        }
        el.textContent = result.value;
    }
}
