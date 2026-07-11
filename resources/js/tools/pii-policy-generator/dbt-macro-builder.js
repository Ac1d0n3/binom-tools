/**
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildDbtMacro(state) {
    const piiColumns = state.columns.filter((col) => col.category && col.category !== 'none');
    const metaEntries = piiColumns.map((col) => {
        const roles = col.accessRoles?.length ? col.accessRoles : state.defaultAccessRoles;
        if (state.useAccessRoles) {
            return `    "${col.name}": {"category": "${col.category}", "access_roles": ${toJinjaList(roles)}}`;
        }
        return `    "${col.name}": {"category": "${col.category}", "access_rules": {"masked": ${toJinjaList(state.accessRules.masked)}, "unmasked": ${toJinjaList(state.accessRules.unmasked)}}}`;
    });

    return `{# macros/pii_governance.sql — copy into your dbt project #}
{# Model: ${state.modelName} | PII version: ${state.piiVersion} #}
{# Mode: ${state.useAccessRoles ? 'access_roles (role must be listed to see unmasked value)' : 'access_rules (masked vs unmasked role lists)'} #}

{% macro pii_mask(column_name, category) %}
  concat('***', substring({{ column_name }}, 1, 2), '…', substring({{ column_name }}, greatest(length({{ column_name }}) - 1, 1)))
{% endmacro %}

{% macro pii_meta_for_column(model_name, column_name) %}
  {% if model_name != '${state.modelName}' %}
    {{ return(none) }}
  {% endif %}

  {% set mapping = {
${metaEntries.join(',\n')}
  } %}

  {{ return(mapping.get(column_name)) }}
{% endmacro %}

{% macro pii_column_for_role(column_name, user_role, model_name='${state.modelName}') %}
  {% set meta = pii_meta_for_column(model_name, column_name) %}

  {% if meta is none %}
    {{ column_name }}
  {% elif meta.access_roles is defined %}
    {# Role-based: only listed roles see the raw column #}
    {% if user_role in meta.access_roles %}
      {{ column_name }}
    {% else %}
      {{ pii_mask(column_name, meta.category) }}
    {% endif %}
  {% else %}
    {# Rule-based: unmasked list wins over masked list #}
    {% if user_role in meta.access_rules.unmasked %}
      {{ column_name }}
    {% elif user_role in meta.access_rules.masked %}
      {{ pii_mask(column_name, meta.category) }}
    {% else %}
      cast(null as {{ dbt.type_string() }})
    {% endif %}
  {% endif %}
{% endmacro %}

{# Example in a model:
   select
     id,
     {{ pii_column_for_role('agent_name', var('pii_user_role')) }} as agent_name,
     {{ pii_column_for_role('customer_email', var('pii_user_role')) }} as customer_email
   from {{ source('raw', 'example_table') }}
#}
`;
}

/** @param {string[]} items */
function toJinjaList(items) {
    return `[${items.map((item) => `'${item}'`).join(', ')}]`;
}
