/** @typedef {'image' | 'video' | 'music' | 'code' | 'business' | 'writing' | 'mail' | 'personal' | 'agent'} PromptCategory */

/** @type {PromptCategory[]} */
export const PROMPT_CATEGORIES = [
    'image',
    'video',
    'music',
    'code',
    'business',
    'writing',
    'mail',
    'personal',
    'agent',
];

/**
 * @param {unknown} value
 * @returns {PromptCategory | ''}
 */
export function normalizeCategory(value) {
    const id = String(value ?? '').trim();
    return PROMPT_CATEGORIES.includes(/** @type {PromptCategory} */ (id))
        ? /** @type {PromptCategory} */ (id)
        : '';
}

/**
 * @param {string} locale
 * @param {string} category
 * @param {(locale: string, key: string) => string} t
 * @returns {string}
 */
export function categoryLabel(locale, category, t) {
    const id = normalizeCategory(category);
    if (!id) return '';
    return t(locale, `promptStudio.category.${id}`);
}
