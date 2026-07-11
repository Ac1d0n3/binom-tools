/**
 * Step 2 uses macros from Step 1 (macros/pii_governance.sql) — no per-model macro duplicate.
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildDbtMacro(state) {
    return `{# Step 2 — DBT Policy Generator #}
{# Requires: macros/pii_governance.sql from Step 1 (Governance Macro Generator) #}
{# Copy pii_governance.sql into your project — pii_column_for_role() reads meta.pii_details from schema.yml #}

{# Example usage in models/marts/${state.modelName}_secure.sql:
   {{ pii_column_for_role('agent_name', var('pii_user_role'), '${state.modelName}') }}
#}

{# No additional model-specific macro required when using graph-based pii_column_for_role from Step 1. #}
`;
}

/** @param {string[]} items */
function toJinjaList(items) {
    return `[${items.map((item) => `'${item}'`).join(', ')}]`;
}

export { toJinjaList };
