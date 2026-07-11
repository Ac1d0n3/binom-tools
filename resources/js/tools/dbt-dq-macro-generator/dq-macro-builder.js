import { getWarehouseTemplate } from '../pii-shared/warehouse-templates.js';

/**
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqGovernanceMacro(state) {
    const wh = getWarehouseTemplate(state.selectedWarehouse);
    const regexBody = wh.regexMatch('{{ column_ref }}', '{{ pattern }}')
        .replace(/\{\{ column_ref \}\}/g, '{{ column_ref }}')
        .replace(/'\{\{ pattern \}\}'/g, "'{{ pattern }}'");

    return `{# macros/dq_governance.sql — Step 1: DQ Macro Generator #}
{# Warehouse: ${wh.label} | Copy into macros/ in your dbt project #}
{# Requires: meta.dq_rules in schema.yml (Step 2) and dq_run_history (Step 3) #}

{% macro dq_effective_rules(column_meta) %}
  {% if column_meta is mapping and column_meta.get('dq_rules') is not none %}
    {{ return(column_meta.get('dq_rules')) }}
  {% else %}
    {{ return([]) }}
  {% endif %}
{% endmacro %}

{% macro dq_model_rules(model) %}
  {% if model.meta.get('dq_rules') is not none %}
    {{ return(model.meta.get('dq_rules')) }}
  {% else %}
    {{ return([]) }}
  {% endif %}
{% endmacro %}

{% macro dq_regex_match(column_ref, pattern) %}
  ${regexBody}
{% endmacro %}

{% macro dq_check_not_null(column_name) %}
  select *
  from {{ model }}
  where {{ column_name }} is null
{% endmacro %}

{% macro dq_check_unique(column_name) %}
  select {{ column_name }}, count(*) as cnt
  from {{ model }}
  group by 1
  having count(*) > 1
{% endmacro %}

{% macro dq_check_accepted_values(column_name, values) %}
  select *
  from {{ model }}
  where {{ column_name }} is not null
    and {{ column_name }} not in (
      {% for v in values %}
        '{{ v }}'{% if not loop.last %}, {% endif %}
      {% endfor %}
    )
{% endmacro %}

{% macro dq_check_regex(column_name, pattern) %}
  select *
  from {{ model }}
  where {{ column_name }} is not null
    and not {{ dq_regex_match(column_name, pattern) }}
{% endmacro %}

{% macro dq_check_range(column_name, min_val, max_val) %}
  select *
  from {{ model }}
  where {{ column_name }} is not null
    and ({{ column_name }} < {{ min_val }} or {{ column_name }} > {{ max_val }})
{% endmacro %}

{% macro dq_check_expression(sql_predicate) %}
  select *
  from {{ model }}
  where not ({{ sql_predicate }})
{% endmacro %}

{% macro dq_check_freshness(column_name, max_hours) %}
  select *
  from {{ model }}
  where {{ column_name }} is null
     or {{ column_name }} < {{ dbt.dateadd('hour', -1 * max_hours, dbt.current_timestamp()) }}
{% endmacro %}

{% macro dq_check_row_count_between(min_rows, max_rows) %}
  {% set cnt_query %}
    select count(*) as total_rows from {{ model }}
  {% endset %}
  {% set results = run_query(cnt_query) %}
  {% if execute %}
    {% set total = results.columns[0].values()[0] %}
    {% if total < min_rows or total > max_rows %}
      select 'row_count {{ total }} outside [{{ min_rows }}, {{ max_rows }}]' as failure_reason
    {% else %}
      select 1 as ok where 1 = 0
    {% endif %}
  {% else %}
    select 1 as ok where 1 = 0
  {% endif %}
{% endmacro %}

{% macro dq_record_result(model_name, column_name, rule_id, rule_type, severity, status, failed_rows, total_rows, metric_value) %}
  {# Called from dq_collect_results (Step 3) — append to dq_run_history #}
  {% set sql %}
    insert into {{ ref('dq_run_history') }} (
      run_id, executed_at, model_name, column_name, rule_id, rule_type,
      severity, status, failed_rows, total_rows, metric_value
    ) values (
      '{{ invocation_id }}',
      {{ dbt.current_timestamp() }},
      '{{ model_name }}',
      {% if column_name %}'{{ column_name }}'{% else %}null{% endif %},
      '{{ rule_id }}',
      '{{ rule_type }}',
      '{{ severity }}',
      '{{ status }}',
      {{ failed_rows }},
      {{ total_rows if total_rows is not none else 'null' }},
      {% if metric_value is not none %}{{ metric_value }}{% else %}null{% endif %}
    )
  {% endset %}
  {% do run_query(sql) %}
{% endmacro %}
`;
}

/**
 * @returns {string}
 */
export function buildDqRuleTest() {
    return `{# tests/generic/dq_rule.sql — Step 1: DQ Macro Generator #}

{% test dq_rule(model, column_name, rule) %}
  {% set rule_type = rule.get('type') %}
  {% set rule_id = rule.get('id', rule_type) %}
  {% set severity = rule.get('severity', 'error') %}

  {% if rule_type == 'not_null' %}
    {{ dq_check_not_null(column_name) }}
  {% elif rule_type == 'unique' %}
    {{ dq_check_unique(column_name) }}
  {% elif rule_type == 'accepted_values' %}
    {{ dq_check_accepted_values(column_name, rule.get('values', [])) }}
  {% elif rule_type == 'regex' %}
    {{ dq_check_regex(column_name, rule.get('pattern')) }}
  {% elif rule_type == 'range' %}
    {{ dq_check_range(column_name, rule.get('min'), rule.get('max')) }}
  {% elif rule_type == 'expression' %}
    {{ dq_check_expression(rule.get('sql')) }}
  {% elif rule_type == 'freshness' %}
    {{ dq_check_freshness(column_name, rule.get('max_hours')) }}
  {% elif rule_type == 'row_count_between' %}
    {{ dq_check_row_count_between(rule.get('min'), rule.get('max')) }}
  {% else %}
    select 'Unknown dq_rule type: {{ rule_type }}' as failure_reason
  {% endif %}
{% endtest %}
`;
}

/**
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildSetupDqMarkdown(state) {
    const wh = getWarehouseTemplate(state.selectedWarehouse);

    return `# dbt Data Quality — SETUP

Generated for **${wh.label}**. Settings are configured in binom-tools and shared via localStorage across all DQ setup steps.

## Copy these files into your dbt project

| File | From step | Target path |
|------|-----------|-------------|
| \`dq_governance.sql\` | 1 — DQ Macro Generator | \`macros/dq_governance.sql\` |
| \`dq_rule.sql\` | 1 — DQ Macro Generator | \`tests/generic/dq_rule.sql\` |
| \`example_dq_schema.yml\` | 2 — DQ Rules Generator | \`models/schema/${state.modelName}.yml\` |
| \`dq_run_history.sql\` | 3 — DQ History Generator | \`models/marts/dq_run_history.sql\` |
| \`dq_score_daily.sql\` | 3 — DQ History Generator | \`models/marts/dq_score_daily.sql\` |
| \`dq_collect_results.sql\` | 3 — DQ History Generator | \`macros/dq_collect_results.sql\` |

## Runbook

\`\`\`bash
dbt test --select tag:dq
dbt run-operation dq_collect_results
dbt run --select dq_run_history+
\`\`\`

## meta.dq_rules convention

Define rules per column or at model level in \`schema.yml\`:

\`\`\`yaml
columns:
  - name: email
    meta:
      dq_rules:
        - id: email_not_null
          type: not_null
          severity: error
\`\`\`

## Reporting

Query \`dq_score_daily\`, \`dq_trend_weekly\`, or \`dq_open_failures\` in your BI tool for trends and open issues.

Model: **${state.modelName}** | Warehouse: **${wh.label}**
`;
}
