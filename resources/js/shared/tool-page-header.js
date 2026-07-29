const FLOW_QUERY = 'flow';

/**
 * @param {HTMLElement} root
 * @returns {string}
 */
function storageKey(root) {
    const toolId = root.dataset.toolId || root.id || 'tool';
    return `binom-tools-flow-open:${toolId}`;
}

/**
 * @returns {boolean}
 */
function urlWantsFlowOpen() {
    try {
        const params = new URLSearchParams(window.location.search);
        return params.get(FLOW_QUERY) === '1';
    } catch {
        return false;
    }
}

function stripFlowQuery() {
    try {
        const url = new URL(window.location.href);
        if (!url.searchParams.has(FLOW_QUERY)) {
            return;
        }
        url.searchParams.delete(FLOW_QUERY);
        const next = `${url.pathname}${url.search}${url.hash}`;
        window.history.replaceState(window.history.state, '', next);
    } catch {
        // ignore
    }
}

/**
 * @param {HTMLElement} root
 */
function initOne(root) {
    const flowPanel = root.querySelector('[data-tool-header-flow-panel]');
    const flowToggle = root.querySelector('[data-tool-header-flow-toggle]');
    const optionsDrawer = root.querySelector('[data-tool-header-options-drawer]');
    const optionsToggle = root.querySelector('[data-tool-header-options-toggle]');
    const panels = Array.from(root.querySelectorAll('[data-tool-header-panel]'));
    const tabs = Array.from(root.querySelectorAll('[data-tool-header-panel-toggle]'));

    /**
     * @param {boolean} open
     * @param {{ persist?: boolean }} [options]
     */
    const setFlowOpen = (open, options = {}) => {
        if (!(flowPanel instanceof HTMLElement) || !(flowToggle instanceof HTMLElement)) {
            return;
        }
        flowPanel.hidden = !open;
        flowToggle.setAttribute('aria-expanded', String(open));
        if (options.persist !== false) {
            try {
                sessionStorage.setItem(storageKey(root), open ? 'true' : 'false');
            } catch {
                // ignore
            }
        }
        if (open) {
            window.dispatchEvent(new CustomEvent('binom-tools:workflow-flowchart-relayout'));
        }
    };

    /**
     * @param {boolean} open
     */
    const setOptionsOpen = (open) => {
        if (!(optionsDrawer instanceof HTMLElement) || !(optionsToggle instanceof HTMLElement)) {
            return;
        }
        optionsDrawer.hidden = !open;
        optionsToggle.setAttribute('aria-expanded', String(open));
    };

    /**
     * @param {string} targetId
     */
    const activatePanel = (targetId) => {
        panels.forEach((panel) => {
            panel.hidden = panel.id !== targetId;
        });
        tabs.forEach((tab) => {
            const isActive = tab.dataset.toolHeaderPanelToggle === targetId;
            tab.classList.toggle('governance-hub__panel-tab--active', isActive);
            tab.setAttribute('aria-selected', String(isActive));
            tab.tabIndex = isActive ? 0 : -1;
        });
    };

    if (flowToggle instanceof HTMLElement && flowPanel instanceof HTMLElement) {
        const fromUrl = urlWantsFlowOpen();
        if (fromUrl) {
            setFlowOpen(true);
            stripFlowQuery();
        } else {
            setFlowOpen(false, { persist: false });
        }

        flowToggle.addEventListener('click', () => {
            setFlowOpen(flowPanel.hidden);
            flowToggle.blur();
        });
    }

    if (optionsToggle instanceof HTMLElement && optionsDrawer instanceof HTMLElement && tabs.length > 0) {
        const initialTab =
            tabs.find((tab) => tab.getAttribute('aria-selected') === 'true')?.dataset.toolHeaderPanelToggle
            || tabs[0]?.dataset.toolHeaderPanelToggle
            || panels[0]?.id
            || '';

        if (initialTab) {
            activatePanel(initialTab);
        }
        setOptionsOpen(false);

        optionsToggle.addEventListener('click', () => {
            const nextOpen = optionsDrawer.hidden;
            setOptionsOpen(nextOpen);
            if (nextOpen && !panels.some((panel) => !panel.hidden) && initialTab) {
                activatePanel(initialTab);
            }
            optionsToggle.blur();
        });

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const targetId = tab.dataset.toolHeaderPanelToggle;
                if (!targetId) {
                    return;
                }
                setOptionsOpen(true);
                activatePanel(targetId);
                tab.blur();
            });
        });
    }
}

export function initToolPageHeaders(scope = document) {
    scope.querySelectorAll('[data-tool-page-header]').forEach((root) => {
        if (!(root instanceof HTMLElement) || root.dataset.toolHeaderReady === 'true') {
            return;
        }
        root.dataset.toolHeaderReady = 'true';
        initOne(root);
    });
}
