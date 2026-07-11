import { createDefaultModelState } from './demo-model.js';

/** @param {string} raw */
function parseList(raw) {
    const trimmed = raw.trim();
    if (trimmed.startsWith('[') && trimmed.endsWith(']')) {
        return trimmed
            .slice(1, -1)
            .split(',')
            .map((item) => item.trim())
            .filter(Boolean);
    }
    return trimmed
        .split(',')
        .map((item) => item.trim())
        .filter(Boolean);
}

/** @param {import('./demo-model.js').DbtModelState} state @param {string} line */
function extractDescriptionMetadata(state, line) {
    const trimmed = line.trim();
    if (!trimmed) return;

    if (trimmed.startsWith("This model contains data from the '") && trimmed.endsWith("' table.")) {
        state.sourceTable = trimmed.slice(37, -9);
    } else if (trimmed.startsWith('PII details version:')) {
        state.piiVersion = trimmed.slice(20).trim();
    } else if (trimmed.startsWith('Access mode: role-based')) {
        state.useAccessRoles = true;
    } else if (trimmed.startsWith('Access mode: rule-based')) {
        state.useAccessRoles = false;
    }
}

/**
 * @param {string} yaml
 * @returns {import('./demo-model.js').DbtModelState | null}
 */
export function parseDbtSchemaYaml(yaml) {
    try {
        /** @type {import('./demo-model.js').DbtModelState} */
        const state = createDefaultModelState();
        state.modelDescription = '';
        state.descriptionExtra = '';
        state.columns = [];

        const lines = yaml.split('\n');
        let inDescription = false;
        const modelDescriptionLines = [];
        /** @type {import('./demo-model.js').ModelColumn | null} */
        let currentColumn = null;
        let modelNameSet = false;
        let inAccessRules = false;
        let inColumnMeta = false;

        for (const rawLine of lines) {
            const trimmed = rawLine.trim();

            if (inDescription) {
                if (/^\s{4}\S/.test(rawLine) && !rawLine.startsWith('      ')) {
                    inDescription = false;
                } else if (rawLine.startsWith('      ')) {
                    const descLine = rawLine.slice(6);
                    extractDescriptionMetadata(state, descLine);
                    modelDescriptionLines.push(descLine.trimEnd());
                    continue;
                } else if (!trimmed) {
                    modelDescriptionLines.push('');
                    continue;
                }
            }

            if (!trimmed || trimmed.startsWith('#')) continue;

            if (trimmed.startsWith('version:')) {
                state.version = Number(trimmed.slice(8).trim()) || 2;
                continue;
            }

            if (trimmed.startsWith('pii-reviewed:')) {
                continue;
            }

            if (trimmed.startsWith('access_groups:')) {
                state.defaultModelAccessGroups = parseList(trimmed.slice(14));
                continue;
            }

            if (trimmed.startsWith('- name:')) {
                const name = trimmed.slice(7).trim();
                const indent = rawLine.match(/^\s*/)?.[0].length ?? 0;

                if (indent <= 4 && !modelNameSet) {
                    state.modelName = name;
                    modelNameSet = true;
                    currentColumn = null;
                    inAccessRules = false;
                    inColumnMeta = false;
                    continue;
                }

                if (currentColumn) state.columns.push(currentColumn);
                currentColumn = { name, category: 'none', description: '' };
                inAccessRules = false;
                inColumnMeta = false;
                continue;
            }

            if (trimmed === 'description: |') {
                inDescription = true;
                continue;
            }

            if (
                trimmed.startsWith('description:') &&
                trimmed !== 'description: |' &&
                !currentColumn &&
                modelNameSet
            ) {
                state.modelDescription = trimmed.slice(12).trim();
                continue;
            }

            if (trimmed.startsWith('description:') && currentColumn) {
                currentColumn.description = trimmed.slice(12).trim();
                continue;
            }

            if (trimmed === 'pii_details:' || trimmed === 'pii_recommend:') {
                inColumnMeta = true;
                continue;
            }

            if (trimmed.startsWith('category:') && currentColumn && inColumnMeta) {
                currentColumn.category = trimmed.slice(9).trim();
                continue;
            }

            if (trimmed.startsWith('access_roles:')) {
                state.useAccessRoles = true;
                if (currentColumn && inColumnMeta) {
                    currentColumn.accessRoles = parseList(trimmed.slice(13));
                }
                continue;
            }

            if (trimmed === 'access_rules:') {
                if (currentColumn && inColumnMeta) {
                    state.useAccessRoles = false;
                    inAccessRules = true;
                }
                continue;
            }

            if (inAccessRules && currentColumn) {
                if (trimmed.startsWith('masked:')) {
                    state.accessRules.masked = parseList(trimmed.slice(7));
                }
                if (trimmed.startsWith('unmasked:')) {
                    state.accessRules.unmasked = parseList(trimmed.slice(9));
                }
            }
        }

        if (currentColumn) state.columns.push(currentColumn);

        while (modelDescriptionLines.length > 0 && !modelDescriptionLines[modelDescriptionLines.length - 1].trim()) {
            modelDescriptionLines.pop();
        }
        state.modelDescription = modelDescriptionLines.join('\n');

        if (!state.modelName || state.columns.length === 0) return null;
        if (!state.sourceTable) state.sourceTable = 'raw.example_table';
        if (!state.piiVersion) state.piiVersion = 'cf38c9353be46d305f35c22a8d926c62';

        return state;
    } catch {
        return null;
    }
}
