/**
 * Admin Hub client helpers — collapsible help panels.
 */
function initAdminHelp(root = document) {
    root.querySelectorAll('[data-admin-help-root]').forEach((panelRoot) => {
        const toggle = panelRoot.querySelector('[data-admin-help-toggle]');
        const panel = panelRoot.querySelector('[data-admin-help]');
        if (!toggle || !panel) {
            return;
        }

        const key = `binom-tools:admin-help:${panel.dataset.adminHelp || 'default'}`;
        let open = true;
        try {
            const stored = localStorage.getItem(key);
            if (stored === '0') {
                open = false;
            }
        } catch {
            // ignore
        }

        const sync = () => {
            panel.hidden = !open;
            toggle.setAttribute('aria-expanded', String(open));
            const show = toggle.querySelector('[data-admin-help-show]');
            const hide = toggle.querySelector('[data-admin-help-hide]');
            if (show) {
                show.hidden = open;
            }
            if (hide) {
                hide.hidden = !open;
            }
        };

        sync();
        toggle.addEventListener('click', () => {
            open = !open;
            try {
                localStorage.setItem(key, open ? '1' : '0');
            } catch {
                // ignore
            }
            sync();
        });
    });
}

document.addEventListener('DOMContentLoaded', () => initAdminHelp());
