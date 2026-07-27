/**
 * @typedef {'details' | 'recommend'} YamlMetaMode
 */

/**
 * @param {import('./demo-model.js').DbtModelState} state
 * @param {{ metaMode?: YamlMetaMode, piiReviewed?: boolean }} [options]
 * @returns {string}
 */
export function buildDbtSchemaYaml(state, options = {}) {
    const metaMode = options.metaMode ?? 'details';
    const piiReviewed = options.piiReviewed ?? metaMode === 'details';

    /** @type {string[]} */
    let descriptionLines = state.modelDescription.split('\n');

    if (state.descriptionExtra.trim()) {
        descriptionLines = [...descriptionLines, ...state.descriptionExtra.split('\n')];
    }

    while (descriptionLines.length > 0 && !descriptionLines[descriptionLines.length - 1].trim()) {
        descriptionLines.pop();
    }

    const accessGroups = state.defaultModelAccessGroups?.length
        ? state.defaultModelAccessGroups
        : ['analyst', 'dpo'];

    const lines = [
        `version: ${state.version}`,
        '',
        'models:',
        `  - name: ${state.modelName}`,
        '    meta:',
        `      pii-reviewed: ${piiReviewed}`,
        `      access_groups: [${accessGroups.join(', ')}]`,
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
            const metaKey = metaMode === 'recommend' ? 'pii_recommend' : 'pii_details';
            lines.push('        meta:');
            lines.push(`          ${metaKey}:`);
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
