/**
 * Shared tablist helpers — Aria/Keyboard only.
 * Domains keep business hooks (e.g. governance tab persistence).
 */

/**
 * @param {HTMLElement} root
 * @param {{
 *   tabSelector?: string,
 *   panelSelector?: string,
 *   onChange?: (tabId: string) => void,
 * }} [options]
 */
export function initTablist(root, options = {}) {
    const tabSelector = options.tabSelector || '[role="tab"]';
    const tabs = Array.from(root.querySelectorAll(tabSelector)).filter(
        (el) => el instanceof HTMLElement && root.contains(el),
    );

    if (tabs.length === 0) {
        return { tabs: [], selectTab() {} };
    }

    /**
     * @param {HTMLElement} tab
     * @param {{ focus?: boolean }} [opts]
     */
    const selectTab = (tab, opts = {}) => {
        const tabId = tab.getAttribute('data-tab-id')
            || tab.dataset.governanceTabToggle
            || tab.dataset.filterTab
            || tab.dataset.supplierTab
            || tab.getAttribute('aria-controls')
            || '';

        tabs.forEach((item) => {
            const selected = item === tab;
            item.setAttribute('aria-selected', String(selected));
            item.tabIndex = selected ? 0 : -1;
            item.classList.toggle('is-active', selected);
        });

        if (opts.focus) {
            tab.focus();
        }

        options.onChange?.(tabId);
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', (event) => {
            event.preventDefault();
            selectTab(tab);
        });

        tab.addEventListener('keydown', (event) => {
            const index = tabs.indexOf(tab);
            if (index < 0) {
                return;
            }
            let next = -1;
            if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                next = (index + 1) % tabs.length;
            } else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                next = (index - 1 + tabs.length) % tabs.length;
            } else if (event.key === 'Home') {
                next = 0;
            } else if (event.key === 'End') {
                next = tabs.length - 1;
            }
            if (next < 0) {
                return;
            }
            event.preventDefault();
            selectTab(tabs[next], { focus: true });
        });
    });

    return { tabs, selectTab };
}
