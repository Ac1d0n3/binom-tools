/** @param {string[]} items */
function toJinjaList(items) {
    return `[${items.map((item) => `'${item}'`).join(', ')}]`;
}

/**
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildPiiTableGateMacro(state) {
    const reviewRoles = state.defaultReviewRoles ?? ['dpo', 'security'];
    const accessGroups = state.defaultModelAccessGroups ?? ['analyst', 'dpo'];

    return `{# macros/pii_table_gate.sql — Step 3: PII Table Gate Generator #}
{# Copy into macros/ — requires pii_model_accessible() from Step 1 (pii_governance.sql) #}
{# dbt run-operation pii_audit_unreviewed_models #}

{% macro pii_model_reviewed(model_name) %}
  {% set node = graph.nodes.get('model.' ~ project_name ~ '.' ~ model_name) %}
  {% if node is none %}
    {% set node = graph.nodes.get('source.' ~ project_name ~ '.' ~ model_name) %}
  {% endif %}
  {% if node is none %}
    {{ return(true) }}
  {% endif %}
  {{ return(node.meta.get('pii-reviewed') is true) }}
{% endmacro %}

{% macro pii_model_visible_for_role(model_name, user_role) %}
  {% if not pii_model_reviewed(model_name) %}
    {{ return(user_role in var('pii_review_roles', ${toJinjaList(reviewRoles)})) }}
  {% endif %}
  {{ return(pii_model_accessible(model_name, user_role)) }}
{% endmacro %}

{% macro pii_audit_unreviewed_models() %}
  {% for node in graph.nodes.values() %}
    {% if node.resource_type not in ['model', 'source'] %}
      {% continue %}
    {% endif %}

    {% if node.meta.get('pii-reviewed') is not true %}
      {{ log("=== " ~ node.name ~ " — missing meta.pii-reviewed: true ===", info=true) }}
      {{ log("    meta:", info=true) }}
      {{ log("      pii-reviewed: false", info=true) }}
      {{ log("      access_groups: " ~ ${toJinjaList(accessGroups)}, info=true) }}
      {{ log("    review_roles (may see unreviewed): " ~ ${toJinjaList(reviewRoles)}, info=true) }}
    {% endif %}
  {% endfor %}
{% endmacro %}
`;
}

/**
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildTableGateYamlExample(state) {
    const accessGroups = state.defaultModelAccessGroups ?? ['analyst', 'dpo'];

    return `# models/schema/example_table_gate.yml — Step 3: PII Table Gate Generator
# Default meta for new tables — hide from normal roles until reviewed (Step 2)

version: 2

models:
  - name: ${state.modelName}
    description: |
      New table — not yet PII-reviewed.
      Set meta.pii-reviewed: true after Step 2 policy is applied.
    meta:
      pii-reviewed: false
      access_groups: ${JSON.stringify(accessGroups)}
`;
}

/**
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildGatedViewExample(state) {
    return `{# models/marts/${state.modelName}_gated.sql — Step 3 example #}
{# Uses pii_model_visible_for_role() — unreviewed models hidden except for review roles #}

{{ config(materialized='view') }}

{% set user_role = var('pii_user_role', 'analyst') %}

{% if pii_model_visible_for_role('${state.modelName}', user_role) %}
  select * from {{ ref('${state.modelName}') }}
{% else %}
  select cast(null as varchar) as _access_denied where 1 = 0
{% endif %}
`;
}
