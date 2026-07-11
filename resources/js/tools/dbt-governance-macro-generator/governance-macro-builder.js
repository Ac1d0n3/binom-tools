import { getWarehouseTemplate } from '../pii-shared/warehouse-templates.js';

/**
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildPiiGovernanceMacro(state) {
    const wh = getWarehouseTemplate(state.selectedWarehouse);
    const nullCast = wh.nullCast();

    return `{# macros/pii_governance.sql — Step 1: Governance Macro Generator #}
{# Warehouse: ${wh.label} | Copy into macros/ in your dbt project #}
{# Requires: schema meta.pii_details (Step 2) — pii_recommend is NOT applied at runtime #}

{% macro pii_mask(column_name, category) %}
  ${wh.maskExpr(column_name)}
{% endmacro %}

{% macro pii_effective_meta(column_meta) %}
  {% if column_meta is mapping and column_meta.get('pii_details') is not none %}
    {{ return(column_meta.get('pii_details')) }}
  {% else %}
    {{ return(none) }}
  {% endif %}
{% endmacro %}

{% macro pii_column_for_role(column_name, user_role, model_name) %}
  {% set node = graph.nodes.get('model.' ~ project_name ~ '.' ~ model_name) %}
  {% if node is none %}
    {{ return(column_name) }}
  {% endif %}

  {% set col_meta = none %}
  {% for col in node.columns.values() %}
    {% if col.name == column_name %}
      {% set col_meta = col.meta %}
    {% endif %}
  {% endfor %}

  {% set meta = pii_effective_meta(col_meta) %}
  {% if meta is none %}
    {{ column_name }}
  {% elif meta.get('access_roles') is not none %}
    {% if user_role in meta.get('access_roles', []) %}
      {{ column_name }}
    {% else %}
      {{ pii_mask(column_name, meta.get('category')) }}
    {% endif %}
  {% elif meta.get('access_rules') is mapping %}
    {% if user_role in meta.get('access_rules', {}).get('unmasked', []) %}
      {{ column_name }}
    {% elif user_role in meta.get('access_rules', {}).get('masked', []) %}
      {{ pii_mask(column_name, meta.get('category')) }}
    {% else %}
      ${nullCast}
    {% endif %}
  {% else %}
    {{ column_name }}
  {% endif %}
{% endmacro %}

{% macro pii_model_accessible(model_name, user_role) %}
  {% set node = graph.nodes.get('model.' ~ project_name ~ '.' ~ model_name) %}
  {% if node is none %}
    {{ return(true) }}
  {% endif %}
  {% set groups = node.meta.get('access_groups', []) %}
  {% if groups | length == 0 %}
    {{ return(true) }}
  {% endif %}
  {{ return(user_role in groups) }}
{% endmacro %}
`;
}

/**
 * @returns {string}
 */
export function buildPiiReviewedTest() {
    return `{# tests/generic/pii_reviewed.sql — Step 1: Governance Macro Generator #}

{% test pii_reviewed(model) %}
  {% if model.meta.get('pii-reviewed') is not true %}
    select 'Model {{ model.name }} missing meta.pii-reviewed: true' as failure_reason
  {% else %}
    select 1 as ok where 1 = 0
  {% endif %}
{% endtest %}
`;
}

/**
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildSetupMarkdown(state) {
    const wh = getWarehouseTemplate(state.selectedWarehouse);

    return `# dbt PII Governance — SETUP

Generated for **${wh.label}**. Settings are configured in binom-tools and shared via localStorage across all setup steps.

## Copy these files into your dbt project

| File | From step | Target path |
|------|-----------|-------------|
| \`pii_governance.sql\` | 1 — Governance Macro Generator | \`macros/pii_governance.sql\` |
| \`pii_reviewed.sql\` | 1 — Governance Macro Generator | \`tests/generic/pii_reviewed.sql\` |
| \`example_table.yml\` (pii_details) | 2 — DBT Policy Generator | \`models/schema/example_table.yml\` |
| \`example_table_secure.sql\` | 2 — DBT Policy Generator | \`models/marts/example_table_secure.sql\` |
| \`pii_table_gate.sql\` | 3 — Unreviewed Table Gate Generator | \`macros/pii_table_gate.sql\` |
| \`example_table_gate.yml\` | 3 — Unreviewed Table Gate Generator | \`models/schema/example_table_gate.yml\` |
| \`pii_audit_by_name.sql\` | 4 — PII Recommend Generator | \`macros/pii_audit_by_name.sql\` |
| \`pii_content_scan.sql\` | 4 — PII Recommend Generator | \`macros/pii_content_scan.sql\` |
| \`example_table.yml\` (pii_recommend) | 4 — PII Recommend Generator | compare / use for new tables |

## dbt_project.yml vars (example)

\`\`\`yaml
vars:
  pii_user_role: analyst
  pii_review_roles: [dpo, security]
\`\`\`

## Verify

\`\`\`bash
dbt run-operation pii_audit_unreviewed_models
dbt run-operation pii_audit_by_name
dbt run-operation pii_audit_by_content --args '{"sample_limit": 1000}'
dbt test --select test_type:generic
dbt run --select example_table_secure --vars '{"pii_user_role": "analyst"}'
\`\`\`

## Meta conventions

- \`meta.pii_recommend\` — suggestion only (Step 4 example). Not used by runtime macros.
- \`meta.pii_details\` — production classification (Step 2 example). Used by \`pii_column_for_role\`.
- \`meta.pii-reviewed: true\` — required after manual review (\`pii_reviewed\` test).
- Unreviewed models — hidden via Step 3 gate until \`pii-reviewed: true\`.

Model: **${state.modelName}** | PII version: **${state.piiVersion}**
`;
}
