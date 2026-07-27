import { splitCategoryValue } from './demo-model.js';

/**
 * @param {import('./demo-model.js').ModelColumn} column
 * @returns {string}
 */
export function columnAccordionSummary(column) {
    const name = column.name?.trim() || '(unnamed)';
    const category = column.category && column.category !== 'none' ? column.category : 'none';
    const description = column.description?.trim();
    if (description) {
        const short = description.length > 48 ? `${description.slice(0, 48)}…` : description;
        return `${name} · ${category} — ${short}`;
    }
    return `${name} · ${category}`;
}

/**
 * @param {Object} opts
 * @param {import('./demo-model.js').ModelColumn[]} opts.columns
 * @param {import('./demo-model.js').DbtModelState} opts.state
 * @param {boolean} opts.showAccessRoles
 * @param {(key: string) => string} opts.t
 * @param {Record<string, string>} opts.labelKeys
 * @param {string} opts.inputClass
 * @param {string} opts.categoryClass
 * @param {(selected: string) => string} opts.renderTypeOptions
 * @param {(selected: string) => string} opts.renderScopeOptions
 * @param {(value: string) => string} opts.escapeAttr
 * @returns {string}
 */
export function buildColumnsAccordionHtml(opts) {
    const {
        columns,
        state,
        showAccessRoles,
        t,
        labelKeys,
        inputClass,
        categoryClass,
        renderTypeOptions,
        renderScopeOptions,
        escapeAttr,
    } = opts;

    return columns
        .map((column, index) => {
            const { piiType, scope } = splitCategoryValue(column.category);
            const accessRolesValue = (column.accessRoles ?? state.defaultAccessRoles).join(', ');
            const accessField = showAccessRoles
                ? `<label class="tools-column-accordion__field">
                    <span class="tools-column-accordion__label">${t(labelKeys.accessRoles)}</span>
                    <input class="${inputClass}" type="text" data-field="accessRoles" value="${escapeAttr(accessRolesValue)}" ${piiType === 'none' ? 'disabled' : ''} />
                   </label>`
                : '';

            return `<details class="tools-column-accordion__item" data-column-index="${index}" ${index === 0 ? 'open' : ''}>
                <summary class="tools-column-accordion__summary">
                    <span class="tools-column-accordion__summary-label">${escapeAttr(columnAccordionSummary(column))}</span>
                </summary>
                <div class="tools-column-accordion__body">
                    <div class="tools-column-accordion__grid">
                        <label class="tools-column-accordion__field">
                            <span class="tools-column-accordion__label">${t(labelKeys.name)}</span>
                            <input class="${inputClass}" type="text" data-field="name" value="${escapeAttr(column.name)}" />
                        </label>
                        <label class="tools-column-accordion__field tools-column-accordion__field--full">
                            <span class="tools-column-accordion__label">${t(labelKeys.description)}</span>
                            <input class="${inputClass}" type="text" data-field="description" value="${escapeAttr(column.description ?? '')}" placeholder="${escapeAttr(t(labelKeys.descriptionHint))}" />
                        </label>
                        <label class="tools-column-accordion__field">
                            <span class="tools-column-accordion__label">${t(labelKeys.piiType)}</span>
                            <select class="${inputClass}" data-field="piiType">${renderTypeOptions(piiType)}</select>
                        </label>
                        <label class="tools-column-accordion__field">
                            <span class="tools-column-accordion__label">${t(labelKeys.scope)}</span>
                            <select class="${inputClass}" data-field="scope">${renderScopeOptions(scope)}</select>
                        </label>
                        <label class="tools-column-accordion__field">
                            <span class="tools-column-accordion__label">${t(labelKeys.category)}</span>
                            <code class="${categoryClass}" data-field="categoryPreview">${escapeAttr(column.category)}</code>
                        </label>
                        ${accessField}
                    </div>
                    <div class="tools-column-accordion__actions">
                        <button type="button" class="tools-btn" data-action="remove-row">${t(labelKeys.remove)}</button>
                    </div>
                </div>
            </details>`;
        })
        .join('');
}

/**
 * @param {HTMLElement} panel
 * @param {number} index
 * @param {import('./demo-model.js').ModelColumn[]} columns
 * @param {import('./demo-model.js').DbtModelState} state
 * @param {(csv: string) => string[]} splitCsv
 * @param {(piiType: string, scope: import('./demo-model.js').PiiScope) => string} buildCategoryValue
 */
export function syncColumnFromPanel(panel, index, columns, state, splitCsv, buildCategoryValue) {
    const column = columns[index];
    if (!column) return;

    const name = /** @type {HTMLInputElement} */ (panel.querySelector('[data-field="name"]')).value.trim();
    const description = /** @type {HTMLInputElement} */ (panel.querySelector('[data-field="description"]')).value.trim();
    const piiType = /** @type {HTMLSelectElement} */ (panel.querySelector('[data-field="piiType"]')).value;
    const scope = /** @type {import('./demo-model.js').PiiScope} */ (
        /** @type {HTMLSelectElement} */ (panel.querySelector('[data-field="scope"]')).value
    );
    const accessRolesInput = /** @type {HTMLInputElement | null} */ (panel.querySelector('[data-field="accessRoles"]'));

    column.name = name;
    column.description = description;
    column.category = buildCategoryValue(piiType, scope);

    if (accessRolesInput && piiType !== 'none') {
        const roles = splitCsv(accessRolesInput.value);
        column.accessRoles = roles.length ? roles : [...state.defaultAccessRoles];
    } else {
        delete column.accessRoles;
    }

    const preview = panel.querySelector('[data-field="categoryPreview"]');
    if (preview) preview.textContent = column.category;
    if (accessRolesInput) accessRolesInput.disabled = piiType === 'none';

    const summaryLabel = panel.querySelector('.tools-column-accordion__summary-label');
    if (summaryLabel) summaryLabel.textContent = columnAccordionSummary(column);
}
