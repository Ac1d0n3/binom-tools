import { markPlaybookRead, isPlaybookRead } from './read-state.js';
import { getScrollContainer, getScrollTop } from './toc.js';

const SCROLL_END_THRESHOLD = 0.92;

/** @type {WeakSet<HTMLElement>} */
const initializedRoots = new WeakSet();

function getActivePanel(root) {
    return root.querySelector('[data-playbook-locale-panel]:not([hidden])');
}

/**
 * @param {Element | Window} container
 */
function isNearScrollEnd(container) {
    const scrollTop = getScrollTop(container);
    const scrollHeight = container === window
        ? document.documentElement.scrollHeight
        : container.scrollHeight;
    const clientHeight = container === window
        ? window.innerHeight
        : container.clientHeight;

    if (scrollHeight <= 0) {
        return false;
    }

    return scrollTop + clientHeight >= scrollHeight * SCROLL_END_THRESHOLD;
}

/**
 * @param {HTMLElement} root
 */
export function initPlaybookReadTracker(root) {
    const slug = root.dataset.playbookSlug;

    if (!slug || isPlaybookRead(slug)) {
        return;
    }

    if (initializedRoots.has(root)) {
        return;
    }

    initializedRoots.add(root);

    let saveTimer = null;
    /** @type {(() => void) | null} */
    let detachScroll = null;

    const checkRead = () => {
        if (isPlaybookRead(slug)) {
            detachScroll?.();
            detachScroll = null;
            return;
        }

        const panel = getActivePanel(root);

        if (!panel) {
            return;
        }

        const container = getScrollContainer(panel);

        if (!isNearScrollEnd(container)) {
            return;
        }

        if (markPlaybookRead(slug)) {
            detachScroll?.();
            detachScroll = null;
        }
    };

    const bindScrollListener = () => {
        detachScroll?.();

        const panel = getActivePanel(root);
        const container = panel ? getScrollContainer(panel) : window;
        const scrollTarget = container === window ? window : container;

        const onScroll = () => {
            window.clearTimeout(saveTimer);
            saveTimer = window.setTimeout(checkRead, 150);
        };

        scrollTarget.addEventListener('scroll', onScroll, { passive: true });
        detachScroll = () => {
            scrollTarget.removeEventListener('scroll', onScroll);
            window.clearTimeout(saveTimer);
        };

        checkRead();
    };

    bindScrollListener();

    window.addEventListener('binom-tools:playbook-locale', bindScrollListener);
}
