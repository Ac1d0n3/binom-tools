/**
 * @param {import('./demo-model.js').DbtModelState} state
 * @returns {string}
 */
export function buildDbtSchemaYaml(state) {
    /** @type {string[]} */
    let descriptionLines = state.modelDescription.split('\n');

    if (state.descriptionExtra.trim()) {
        descriptionLines = [...descriptionLines, ...state.descriptionExtra.split('\n')];
    }

    while (descriptionLines.length > 0 && !descriptionLines[descriptionLines.length - 1].trim()) {
        descriptionLines.pop();
    }

    const lines = [
        `version: ${state.version}`,
        '',
        'models:',
        `  - name: ${state.modelName}`,
        '    description: |',
        ...descriptionLines.map((line) => `      ${line}`),
        '',
        '    columns:',
    ];

    for (const column of state.columns) {
        lines.push(`      - name: ${column.name}`);
        if (column.description?.trim()) {
            lines.push(`        description: ${column.description.trim()}`);
        }
        if (column.category && column.category !== 'none') {
            lines.push('        meta:');
            lines.push('          pii_details:');
            lines.push(`            category: ${column.category}`);

            if (state.useAccessRoles) {
                const roles = column.accessRoles?.length ? column.accessRoles : state.defaultAccessRoles;
                lines.push(`            access_roles: [${roles.join(', ')}]`);
            } else {
                lines.push('            access_rules:');
                lines.push(`              masked: [${state.accessRules.masked.join(', ')}]`);
                lines.push(`              unmasked: [${state.accessRules.unmasked.join(', ')}]`);
            }
        }
    }

    return `${lines.join('\n')}\n`;
}
