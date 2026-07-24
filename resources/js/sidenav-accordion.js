const STORAGE_KEY = 'binom-tools-sidenav-accordions';

/**
 * @returns {string[]}
 */
export function readOpenAccordions() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) {
            return [];
        }

        const parsed = JSON.parse(raw);
        if (!Array.isArray(parsed)) {
            return [];
        }

        return parsed.filter((id) => typeof id === 'string' && id.length > 0);
    } catch {
        return [];
    }
}

/**
 * @param {string[]} ids
 */
export function writeOpenAccordions(ids) {
    const unique = [...new Set(ids.filter((id) => typeof id === 'string' && id.length > 0))];
    localStorage.setItem(STORAGE_KEY, JSON.stringify(unique));
}

/**
 * Restore and persist tools sidebar product accordion open state.
 */
export function initSidenavAccordions() {
    const items = document.querySelectorAll('[data-sidenav-accordion]');
    if (items.length === 0) {
        return;
    }

    const stored = new Set(readOpenAccordions());

    items.forEach((item) => {
        if (!(item instanceof HTMLElement)) {
            return;
        }

        const id = item.getAttribute('data-sidenav-accordion');
        const input = item.querySelector('.tools-sidenav__accordion-input');
        if (!id || !(input instanceof HTMLInputElement)) {
            return;
        }

        // Keep server-checked (active route) groups open and restore previously opened ones.
        if (stored.has(id)) {
            input.checked = true;
        }

        input.addEventListener('change', () => {
            persistOpenAccordions(items);
        });
    });

    // Remember active-route groups even if the user never toggled manually.
    persistOpenAccordions(items);
}

/**
 * @param {NodeListOf<Element> | Element[]} items
 */
function persistOpenAccordions(items) {
    const openIds = [];
    items.forEach((node) => {
        if (!(node instanceof HTMLElement)) {
            return;
        }
        const groupId = node.getAttribute('data-sidenav-accordion');
        const checkbox = node.querySelector('.tools-sidenav__accordion-input');
        if (groupId && checkbox instanceof HTMLInputElement && checkbox.checked) {
            openIds.push(groupId);
        }
    });
    writeOpenAccordions(openIds);
}
