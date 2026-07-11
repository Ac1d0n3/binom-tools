/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const validationLabels = {
    de: {
        'validation.regexInvalid': 'Ungültiger Regex in Zeile {row}: {detail}',
        'validation.regexDuplicate': 'Doppelter Regex in Zeile {row}.',
        'validation.regexEmpty': 'Regex darf nicht leer sein (Zeile {row}).',
        'validation.namePatternEmpty': 'Pattern darf nicht leer sein (Zeile {row}).',
        'validation.namePatternDuplicate': 'Doppeltes Pattern „{pattern}" (Zeile {row}).',
        'validation.minMatchRateInvalid': 'Trefferquote muss zwischen 1 und 100 liegen (Zeile {row}).',
        'validation.modelNameEmpty': 'Modellname fehlt.',
        'validation.columnNameEmpty': 'Spaltenname fehlt (Zeile {row}).',
        'validation.columnNameDuplicate': 'Doppelter Spaltenname „{name}".',
        'validation.accessRulesEmpty': 'access_rules: masked und unmasked dürfen nicht beide leer sein.',
        'validation.accessRolesEmpty': 'access_roles ist leer — Standard-Rollen prüfen.',
        'validation.reviewRolesEmpty': 'Review-Rollen (pii_review_roles) sind leer.',
        'validation.outputBlocked': 'Ausgabe blockiert — bitte Fehler oben beheben.',
        'validation.buildFailed': 'Code konnte nicht generiert werden: {detail}',
        'validation.storageCorrupt':
            'Gespeicherte Einstellungen konnten nicht geladen werden — Standardwerte werden verwendet.',
        'validation.promptEmpty': 'Bitte zuerst Text eingeben.',
        'validation.sanitizeFailed': 'Sanitizer-Fehler: {detail}',
        'validation.yaml.empty': 'YAML ist leer.',
        'validation.yaml.missingModel': 'Kein Model gefunden — erwartet „models:" mit „- name:".',
        'validation.yaml.missingColumns': 'Model ohne Spalten — mindestens eine Spalte mit „- name:" erforderlich.',
        'validation.yaml.invalidStructure': 'YAML-Struktur ungültig{line}.',
        'validation.yaml.unsupported': 'Nur dbt schema.yml mit models: wird unterstützt.',
        'validation.yaml.lineSuffix': ' (Zeile {line})',
    },
    en: {
        'validation.regexInvalid': 'Invalid regex in row {row}: {detail}',
        'validation.regexDuplicate': 'Duplicate regex in row {row}.',
        'validation.regexEmpty': 'Regex must not be empty (row {row}).',
        'validation.namePatternEmpty': 'Pattern must not be empty (row {row}).',
        'validation.namePatternDuplicate': 'Duplicate pattern "{pattern}" (row {row}).',
        'validation.minMatchRateInvalid': 'Match rate must be between 1 and 100 (row {row}).',
        'validation.modelNameEmpty': 'Model name is missing.',
        'validation.columnNameEmpty': 'Column name is missing (row {row}).',
        'validation.columnNameDuplicate': 'Duplicate column name "{name}".',
        'validation.accessRulesEmpty': 'access_rules: masked and unmasked must not both be empty.',
        'validation.accessRolesEmpty': 'access_roles is empty — check default roles.',
        'validation.reviewRolesEmpty': 'Review roles (pii_review_roles) are empty.',
        'validation.outputBlocked': 'Output blocked — please fix errors above.',
        'validation.buildFailed': 'Could not generate code: {detail}',
        'validation.storageCorrupt':
            'Saved settings could not be loaded — using defaults.',
        'validation.promptEmpty': 'Please enter text first.',
        'validation.sanitizeFailed': 'Sanitizer error: {detail}',
        'validation.yaml.empty': 'YAML is empty.',
        'validation.yaml.missingModel': 'No model found — expected "models:" with "- name:".',
        'validation.yaml.missingColumns': 'Model has no columns — at least one "- name:" column required.',
        'validation.yaml.invalidStructure': 'Invalid YAML structure{line}.',
        'validation.yaml.unsupported': 'Only dbt schema.yml with models: is supported.',
        'validation.yaml.lineSuffix': ' (line {line})',
    },
};

/** @param {ToolsLocale} locale @param {string} key @param {Record<string, string | number>} [params] */
export function tValidation(locale, key, params = {}) {
    let text = validationLabels[locale]?.[key] ?? validationLabels.en[key] ?? key;
    for (const [name, value] of Object.entries(params)) {
        text = text.replace(`{${name}}`, String(value));
    }
    return text;
}

/**
 * @param {ToolsLocale} locale
 * @param {(locale: ToolsLocale, key: string) => string} toolT
 * @returns {(key: string, params?: Record<string, string | number>) => string}
 */
export function mergeValidationTranslator(locale, toolT) {
    return (key, params = {}) => {
        if (key.startsWith('validation.')) {
            return tValidation(locale, key, params);
        }
        return toolT(locale, key);
    };
}
