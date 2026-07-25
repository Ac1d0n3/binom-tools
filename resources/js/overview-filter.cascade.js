/** @type {WeakMap<HTMLSelectElement, HTMLOptionElement[]>} */
const optionTemplates = new WeakMap();

/**
 * Rebuild select options from a cached template, keeping only available values.
 * Removing options (instead of option.hidden) works reliably across browsers.
 *
 * @param {HTMLSelectElement} select
 * @param {(optionValue: string) => boolean} isAvailable
 * @returns {boolean} true when the selected value was reset to "all"
 */
export function syncSelectOptionAvailability(select, isAvailable) {
    if (!optionTemplates.has(select)) {
        optionTemplates.set(
            select,
            Array.from(select.options).map((option) =>
                /** @type {HTMLOptionElement} */ (option.cloneNode(true)),
            ),
        );
    }

    const templates = optionTemplates.get(select) ?? [];
    const previous = select.value;
    /** @type {HTMLOptionElement[]} */
    const nextOptions = [];

    templates.forEach((template) => {
        const value = template.value;
        if (value === 'all' || isAvailable(value)) {
            nextOptions.push(/** @type {HTMLOptionElement} */ (template.cloneNode(true)));
        }
    });

    select.replaceChildren(...nextOptions);

    const stillAvailable =
        previous === 'all' || nextOptions.some((option) => option.value === previous);

    if (!stillAvailable) {
        select.value = 'all';
        return true;
    }

    select.value = previous;
    return false;
}
