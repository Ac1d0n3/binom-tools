import { createDefaultModelState } from './demo-model.js';
import { tValidation } from './validation-labels.js';
import { yamlErrorToMessageKey } from './validation.js';

/** @typedef {{ code: 'empty' | 'missing_model' | 'missing_columns' | 'invalid_structure' | 'unsupported', line?: number, detail?: string }} YamlParseError */

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
 * @returns {{ ok: true, state: import('./demo-model.js').DbtModelState } | { ok: false, error: YamlParseError }}
 */
export function parseDbtSchemaYamlResult(yaml) {
    if (!yaml.trim()) {
        return { ok: false, error: { code: 'empty' } };
    }

    if (/^\s*sources\s*:/m.test(yaml) && !/^\s*models\s*:/m.test(yaml)) {
        return { ok: false, error: { code: 'unsupported' } };
    }

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

        for (let lineIndex = 0; lineIndex < lines.length; lineIndex += 1) {
            const rawLine = lines[lineIndex];
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

        if (!modelNameSet) {
            return { ok: false, error: { code: 'missing_model' } };
        }

        if (state.columns.length === 0) {
            return { ok: false, error: { code: 'missing_columns' } };
        }

        if (!state.sourceTable) state.sourceTable = 'raw.example_table';
        if (!state.piiVersion) state.piiVersion = 'cf38c9353be46d305f35c22a8d926c62';

        return { ok: true, state };
    } catch (error) {
        const detail = error instanceof Error ? error.message : String(error);
        return { ok: false, error: { code: 'invalid_structure', detail } };
    }
}

/**
 * @param {string} yaml
 * @returns {import('./demo-model.js').DbtModelState | null}
 */
export function parseDbtSchemaYaml(yaml) {
    const result = parseDbtSchemaYamlResult(yaml);
    return result.ok ? result.state : null;
}

/**
 * @param {import('./validation-labels.js').ToolsLocale} locale
 * @param {YamlParseError} error
 * @returns {string}
 */
export function formatYamlParseErrorMessage(locale, error) {
    const key = yamlErrorToMessageKey(error.code);
    let message = tValidation(locale, key);

    if (error.line) {
        message += tValidation(locale, 'validation.yaml.lineSuffix', { line: error.line });
    }

    return message;
}
