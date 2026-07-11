/**
 * @param {import('../pii-shared/demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildDbtPolicy(state) {
    const piiColumns = state.columns.filter((col) => col.category && col.category !== 'none');
    const mode = state.useAccessRoles ? 'access_roles' : 'access_rules';

    const lines = [
        '# macros/pii_policy.yml — governance reference (copy alongside schema.yml)',
        `# Model: ${state.modelName}`,
        `# PII version: ${state.piiVersion}`,
        `# Access mode: ${mode}`,
        '',
        'governance:',
        `  model: ${state.modelName}`,
        `  pii_version: "${state.piiVersion}"`,
        `  access_mode: ${mode}`,
    ];

    if (state.useAccessRoles) {
        lines.push('  default_access_roles:', ...state.defaultAccessRoles.map((role) => `    - ${role}`));
        lines.push('', '  role_semantics:');
        lines.push('    # Roles listed in meta.pii_details.access_roles receive the unmasked column.');
        lines.push('    # All other roles should receive masked output via pii_column_for_role().');
    } else {
        lines.push('  access_rules:');
        lines.push('    masked:', ...state.accessRules.masked.map((role) => `      - ${role}`));
        lines.push('    unmasked:', ...state.accessRules.unmasked.map((role) => `      - ${role}`));
        lines.push('', '  rule_semantics:');
        lines.push('    # masked roles: see tokenized/masked values');
        lines.push('    # unmasked roles: see raw values');
        lines.push('    # roles in neither list: no access (null)');
    }

    lines.push('', '  columns:');
    for (const column of piiColumns) {
        lines.push(`    - name: ${column.name}`);
        lines.push(`      category: ${column.category}`);
        if (state.useAccessRoles) {
            const roles = column.accessRoles?.length ? column.accessRoles : state.defaultAccessRoles;
            lines.push('      access_roles:', ...roles.map((role) => `        - ${role}`));
        }
    }

    lines.push(
        '',
        '# dbt var example (profiles or dbt_project.yml):',
        "# vars:",
        "#   pii_user_role: analyst",
    );

    return `${lines.join('\n')}\n`;
}
