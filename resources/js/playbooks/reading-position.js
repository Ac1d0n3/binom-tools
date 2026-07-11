import { getScrollContainer, getScrollTop, scrollToAnchor } from './toc';

const STORAGE_PREFIX = 'binom-tools-playbook-position:';

function storageKey(slug, locale) {
    return `${STORAGE_PREFIX}${slug}:${locale}`;
}

function readPosition(slug, locale) {
    try {
        const raw = localStorage.getItem(storageKey(slug, locale));
        return raw ? JSON.parse(raw) : null;
    } catch {
        return null;
    }
}

function writePosition(slug, locale, payload) {
    localStorage.setItem(storageKey(slug, locale), JSON.stringify(payload));
}

function getActivePanel(root) {
    return root.querySelector('[data-playbook-locale-panel]:not([hidden])');
}

function currentAnchorFromViewport(panel) {
    const headings = [...panel.querySelectorAll('.playbook-prose h2[id], .playbook-prose h3[id]')];
    const scrollContainer = getScrollContainer();
    const containerTop = scrollContainer === window ? 0 : scrollContainer.getBoundingClientRect().top;
    const marker = getScrollTop() + 24;

    let active = null;

    headings.forEach((heading) => {
        const top = heading.getBoundingClientRect().top - containerTop + getScrollTop();

        if (top <= marker) {
            active = heading.id;
        }
    });

    return active ? `#${active}` : null;
}

function restorePosition(root, panel, slug, locale) {
    const hash = window.location.hash;

    if (hash.length > 1) {
        const id = hash.slice(1);
        if (panel.querySelector(`#${CSS.escape(id)}`)) {
            scrollToAnchor(id, panel);
        }
        return;
    }

    const saved = readPosition(slug, locale);

    if (!saved) return;

    if (saved.anchor) {
        const id = saved.anchor.replace(/^#/, '');
        if (panel.querySelector(`#${CSS.escape(id)}`)) {
            scrollToAnchor(id, panel);
        }
        return;
    }

    if (typeof saved.scrollY === 'number') {
        const container = getScrollContainer();

        if (container === window) {
            window.scrollTo({ top: saved.scrollY, behavior: 'auto' });
        } else {
            container.scrollTop = saved.scrollY;
        }
    }
}

export function initPlaybookReadingPosition(root) {
    const slug = root.dataset.playbookSlug;

    if (!slug) return;

    const restoreActive = () => {
        const panel = getActivePanel(root);
        const locale = panel?.dataset.playbookLocalePanel;

        if (!panel || !locale) return;

        restorePosition(root, panel, slug, locale);
    };

    restoreActive();

    let saveTimer = null;

    const save = () => {
        const panel = getActivePanel(root);
        const locale = panel?.dataset.playbookLocalePanel;

        if (!panel || !locale) return;

        writePosition(slug, locale, {
            anchor: currentAnchorFromViewport(panel),
            scrollY: getScrollTop(),
        });
    };

    const container = getScrollContainer();
    const scrollTarget = container === window ? window : container;

    scrollTarget.addEventListener(
        'scroll',
        () => {
            window.clearTimeout(saveTimer);
            saveTimer = window.setTimeout(save, 150);
        },
        { passive: true },
    );

    window.addEventListener('hashchange', save);

    window.addEventListener('binom-tools:playbook-locale', () => {
        restoreActive();
    });
}
