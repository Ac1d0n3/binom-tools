import { RULE_TYPE_DEFS, RULE_TYPE_IDS, createDefaultRule, normalizeRule } from '../dq-shared/rule-types.js';

/**
 * @param {string} value
 * @returns {string}
 */
function escapeAttr(value) {
    return value.replaceAll('&', '&amp;').replaceAll('"', '&quot;').replaceAll('<', '&lt;');
}

/**
 * @param {import('../dq-shared/rule-types.js').DqRule} rule
 * @param {number} ruleIndex
 * @param {(key: string) => string} tr
 * @returns {string}
 */
function renderRuleRow(rule, ruleIndex, tr) {
    const def = RULE_TYPE_DEFS[rule.type];
    const typeOptions = RULE_TYPE_IDS.map(
        (id) => `<option value="${id}" ${rule.type === id ? 'selected' : ''}>${RULE_TYPE_DEFS[id].label}</option>`,
    ).join('');
    const severityOptions = ['error', 'warn']
        .map((s) => `<option value="${s}" ${rule.severity === s ? 'selected' : ''}>${s}</option>`)
        .join('');

    let paramFields = '';
    for (const param of def?.params ?? []) {
        if (param === 'values') {
            paramFields += `<input class="pii-policy-input dq-rule-param" type="text" data-param="values" value="${escapeAttr((rule.values ?? []).join(', '))}" placeholder="a, b, c" />`;
        } else if (param === 'pattern') {
            paramFields += `<input class="pii-policy-input dq-rule-param" type="text" data-param="pattern" value="${escapeAttr(rule.pattern ?? '')}" />`;
        } else if (param === 'min') {
            paramFields += `<input class="pii-policy-input dq-rule-param" type="number" data-param="min" value="${rule.min ?? ''}" />`;
        } else if (param === 'max') {
            paramFields += `<input class="pii-policy-input dq-rule-param" type="number" data-param="max" value="${rule.max ?? ''}" />`;
        } else if (param === 'sql') {
            paramFields += `<input class="pii-policy-input dq-rule-param" type="text" data-param="sql" value="${escapeAttr(rule.sql ?? '')}" />`;
        } else if (param === 'max_hours') {
            paramFields += `<input class="pii-policy-input dq-rule-param" type="number" data-param="max_hours" value="${rule.max_hours ?? ''}" />`;
        }
    }

    return `<tr class="dq-rule-row" data-rule-index="${ruleIndex}">
      <td><input class="pii-policy-input" type="text" data-field="id" value="${escapeAttr(rule.id)}" /></td>
      <td><select class="pii-policy-input" data-field="type">${typeOptions}</select></td>
      <td><select class="pii-policy-input" data-field="severity">${severityOptions}</select></td>
      <td class="dq-rule-params">${paramFields}</td>
      <td><button type="button" class="tools-btn tools-btn--ghost dq-rule-remove" data-i18n="dqRules.rules.remove">${tr('dqRules.rules.remove')}</button></td>
    </tr>`;
}

/**
 * @param {import('../dq-shared/dq-demo-model.js').DqColumn} column
 * @param {number} columnIndex
 * @param {(key: string) => string} tr
 * @returns {string}
 */
export function renderColumnPanel(column, columnIndex, tr) {
    const rulesHtml = column.dqRules
        .map((rule, ruleIndex) => renderRuleRow(rule, ruleIndex, tr))
        .join('');

    return `<details class="tools-column-accordion__item dq-column-panel" data-column-index="${columnIndex}" ${columnIndex === 0 ? 'open' : ''}>
      <summary class="tools-column-accordion__summary">
        <span class="tools-column-accordion__summary-label">${escapeAttr(column.name || '(unnamed)')} · ${column.dqRules.length} rules</span>
      </summary>
      <div class="tools-column-accordion__body">
        <div class="tools-column-accordion__grid">
        <label class="pii-policy-field">
          <span>${tr('dqRules.columns.name')}</span>
          <input class="pii-policy-input" type="text" data-field="name" value="${escapeAttr(column.name)}" />
        </label>
        <label class="pii-policy-field">
          <span>${tr('dqRules.columns.description')}</span>
          <input class="pii-policy-input" type="text" data-field="description" value="${escapeAttr(column.description ?? '')}" />
        </label>
        </div>
        <table class="pii-policy-table dq-rules-table">
          <thead>
            <tr>
              <th>${tr('dqRules.rules.id')}</th>
              <th>${tr('dqRules.rules.type')}</th>
              <th>${tr('dqRules.rules.severity')}</th>
              <th>Params</th>
              <th></th>
            </tr>
          </thead>
          <tbody class="dq-rules-body">${rulesHtml}</tbody>
        </table>
        <button type="button" class="tools-btn tools-btn--ghost dq-add-rule-btn">${tr('dqRules.rules.add')}</button>
      </div>
    </details>`;
}

/**
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @param {(key: string) => string} tr
 * @returns {string}
 */
export function renderModelRulesTable(state, tr) {
    return state.modelRules
        .map((rule, ruleIndex) => renderRuleRow(rule, ruleIndex, tr))
        .join('');
}

/** @param {import('../dq-shared/rule-types.js').DqRule} rule @param {HTMLTableRowElement} row */
export function readRuleFromRow(rule, row) {
    const next = { ...rule };
    const idInput = /** @type {HTMLInputElement | null} */ (row.querySelector('[data-field="id"]'));
    const typeSelect = /** @type {HTMLSelectElement | null} */ (row.querySelector('[data-field="type"]'));
    const severitySelect = /** @type {HTMLSelectElement | null} */ (row.querySelector('[data-field="severity"]'));
    if (idInput) next.id = idInput.value.trim();
    if (typeSelect) next.type = /** @type {import('../dq-shared/rule-types.js').DqRuleType} */ (typeSelect.value);
    if (severitySelect) next.severity = /** @type {import('../dq-shared/rule-types.js').DqSeverity} */ (severitySelect.value);

    row.querySelectorAll('.dq-rule-param').forEach((el) => {
        const input = /** @type {HTMLInputElement} */ (el);
        const param = input.getAttribute('data-param');
        if (param === 'values') {
            next.values = input.value.split(',').map((v) => v.trim()).filter(Boolean);
        } else if (param === 'pattern') {
            next.pattern = input.value;
        } else if (param === 'min') {
            next.min = input.value === '' ? undefined : Number(input.value);
        } else if (param === 'max') {
            next.max = input.value === '' ? undefined : Number(input.value);
        } else if (param === 'sql') {
            next.sql = input.value;
        } else if (param === 'max_hours') {
            next.max_hours = input.value === '' ? undefined : Number(input.value);
        }
    });

    return normalizeRule(next);
}

export { createDefaultRule };
