/** Clipboard helpers for Supplier Library Hub copy buttons. */
export function initSupplierLibraryCopy() {
    const root = document.querySelector('[data-supplier-library]');
    if (!root) {
        return;
    }

    root.querySelectorAll('[data-supplier-copy]').forEach((button) => {
        if (!(button instanceof HTMLButtonElement)) {
            return;
        }

        button.addEventListener('click', async () => {
            const prev = button.previousElementSibling;
            const source =
                prev instanceof HTMLElement && prev.hasAttribute('data-supplier-copy-source')
                    ? prev
                    : button.closest('td, .supplier-measure-card__formula-row')?.querySelector(
                          '[data-supplier-copy-source]',
                      );

            const text = (source?.textContent || '').trim();
            if (!text || !navigator.clipboard?.writeText) {
                return;
            }

            try {
                await navigator.clipboard.writeText(text);
            } catch {
                return;
            }

            const original = button.textContent || 'Copy';
            const locale = document.documentElement.getAttribute('lang') || 'en';
            button.textContent = locale.startsWith('de') ? 'Kopiert' : 'Copied';
            window.setTimeout(() => {
                button.textContent = original;
            }, 1400);
        });
    });
}

/** Tablist ↔ panels for Supplier Library detail (SA help-tab pattern). */
export function initSupplierLibraryTabs() {
    const root = document.querySelector('[data-supplier-library]');
    if (!root) {
        return;
    }

    const tabs = root.querySelectorAll('[data-supplier-tab]');
    const panels = root.querySelectorAll('[data-supplier-panel]');
    if (tabs.length === 0 || panels.length === 0) {
        return;
    }

    const activate = (target) => {
        if (!target) {
            return;
        }

        tabs.forEach((tab) => {
            const active = tab.getAttribute('data-supplier-tab') === target;
            tab.classList.toggle('is-active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
            if (tab instanceof HTMLButtonElement) {
                tab.tabIndex = active ? 0 : -1;
            }
        });

        panels.forEach((panel) => {
            const active = panel.getAttribute('data-supplier-panel') === target;
            panel.classList.toggle('is-active', active);
            panel.toggleAttribute('hidden', !active);
        });
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            activate(tab.getAttribute('data-supplier-tab'));
        });
    });

    const hash = (window.location.hash || '').replace(/^#/, '');
    const allowed = new Set([...tabs].map((tab) => tab.getAttribute('data-supplier-tab')).filter(Boolean));
    if (hash && allowed.has(hash)) {
        activate(hash);
    }
}
