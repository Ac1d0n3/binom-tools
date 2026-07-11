import { buildCategoryValue } from '../pii-shared/demo-model.js';
import { getWarehouseTemplate } from '../pii-shared/warehouse-templates.js';

/** @param {string[]} items */
function toJinjaList(items) {
    return `[${items.map((item) => `'${item}'`).join(', ')}]`;
}

/** @param {string} value */
function escapeJinjaString(value) {
    return value.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}

/**
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
function buildSuggestCategoryMacro(state) {
    const rules = state.nameHeuristicRules ?? [];
    const ruleLines = rules.map((rule) => {
        const escaped = escapeJinjaString(rule.pattern);
        if (rule.piiType === 'none') {
            return `  {% if '${escaped}' in lower %}{{ return('none') }}{% endif %}`;
        }
        return `  {% if '${escaped}' in lower %}{{ return('${buildCategoryValue(rule.piiType, state.defaultScope)}') }}{% endif %}`;
    });

    return `{% macro pii_suggest_category(column_name, default_scope='${state.defaultScope}') %}
  {% set lower = column_name | lower | trim %}
  {% if not lower %}{{ return('none') }}{% endif %}
  {% if lower == 'id' %}{{ return('none') }}{% endif %}
${ruleLines.join('\n')}
  {{ return('none') }}
{% endmacro %}`;
}

/**
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildPiiAuditByNameMacro(state) {
    const suggestMacro = buildSuggestCategoryMacro(state);

    return `{# macros/pii_audit_by_name.sql — Step 4: PII Recommend Generator #}
{# Copy into macros/ — run: dbt run-operation pii_audit_by_name #}
{# Scans column NAMES using your substring rules — does not read cell values #}

${suggestMacro}

{% macro pii_audit_by_name() %}
  {% for node in graph.nodes.values() %}
    {% if node.resource_type not in ['model', 'source'] %}
      {% continue %}
    {% endif %}

    {% for col in node.columns.values() %}
      {% set details = col.meta.get('pii_details') if col.meta is mapping else none %}
      {% if details is none %}
        {% set suggested = pii_suggest_category(col.name) %}
        {% if suggested != 'none' %}
          {{ log("  - name: " ~ col.name ~ " (model: " ~ node.name ~ ")", info=true) }}
          {{ log("    source: name_heuristic", info=true) }}
          {{ log("    meta:", info=true) }}
          {{ log("      pii_recommend:", info=true) }}
          {{ log("        category: " ~ suggested, info=true) }}
${state.useAccessRoles
    ? `          {{ log("        access_roles: ${toJinjaList(state.defaultAccessRoles)}", info=true) }}`
    : `          {{ log("        access_rules:", info=true) }}
          {{ log("          masked: ${toJinjaList(state.accessRules.masked)}", info=true) }}
          {{ log("          unmasked: ${toJinjaList(state.accessRules.unmasked)}", info=true) }}`}
        {% endif %}
      {% endif %}
    {% endfor %}
  {% endfor %}
{% endmacro %}
`;
}

/**
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildPiiContentScanMacro(state) {
    const wh = getWarehouseTemplate(state.selectedWarehouse);
    const rules = (state.contentHeuristicRules ?? []).filter((rule) => rule.piiType !== 'none');
    const defaultMinRate = (state.contentScanDefaultMinMatchRate ?? 5) / 100;

    const ruleEntries = rules.map((rule) => {
        const minRate = (rule.minMatchRate ?? state.contentScanDefaultMinMatchRate ?? 5) / 100;
        return `    {'regex': '${escapeJinjaString(rule.regex)}', 'category': '${buildCategoryValue(rule.piiType, state.defaultScope)}', 'min_rate': ${minRate}}`;
    });

    const rulesBlock =
        ruleEntries.length > 0
            ? ruleEntries.join(',\n')
            : `    {'regex': '^[^@\\\\s]+@[^@\\\\s]+\\\\.[^@\\\\s]+$', 'category': '${buildCategoryValue('email', state.defaultScope)}', 'min_rate': ${defaultMinRate}}`;

    return `{# macros/pii_content_scan.sql — Step 4: PII Recommend Generator #}
{# Warehouse: ${wh.label} | Copy into macros/ #}
{# run: dbt run-operation pii_audit_by_content --args '{sample_limit: 1000}' #}
{# Samples CELL VALUES using your regex rules — independent of column names #}

{% macro pii_content_rules() %}
  {{ return([
${rulesBlock}
  ]) }}
{% endmacro %}

{% macro pii_audit_by_content(sample_limit=1000) %}
  {% for node in graph.nodes.values() %}
    {% if node.resource_type not in ['model', 'source'] %}
      {% continue %}
    {% endif %}

    {% set relation = adapter.get_relation(node.database, node.schema, node.identifier) %}
    {% if relation is none %}
      {% continue %}
    {% endif %}

    {% for col in node.columns.values() %}
      {% set details = col.meta.get('pii_details') if col.meta is mapping else none %}
      {% if details is none %}
        {% for rule in pii_content_rules() %}
          {% set _sql = "select count(*) as total, sum(case when ${wh.regexMatch('val', "' ~ rule.regex ~ '")} then 1 else 0 end) as hits from (select " ~ col.name ~ " as val from " ~ relation ~ " ${wh.sampleClause('__LIMIT__')} ) s" | replace('__LIMIT__', sample_limit | string) %}
          {% set _result = run_query(_sql) %}
          {% if _result and _result.rows | length > 0 %}
            {% set _total = _result.rows[0][0] | int %}
            {% set _hits = _result.rows[0][1] | int %}
            {% if _total > 0 and (_hits / _total) >= rule.min_rate %}
              {{ log("  - name: " ~ col.name ~ " (model: " ~ node.name ~ ")", info=true) }}
              {{ log("    source: content_scan", info=true) }}
              {{ log("    match_rate: " ~ ((_hits / _total) * 100) | round(1) ~ "%", info=true) }}
              {{ log("    meta:", info=true) }}
              {{ log("      pii_recommend:", info=true) }}
              {{ log("        category: " ~ rule.category, info=true) }}
            {% endif %}
          {% endif %}
        {% endfor %}
      {% endif %}
    {% endfor %}
  {% endfor %}
{% endmacro %}
`;
}

/** @deprecated Use buildPiiAuditByNameMacro */
export function buildPiiAuditMacro(state) {
    return buildPiiAuditByNameMacro(state);
}
