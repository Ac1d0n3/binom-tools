import { initPlaybookPrism } from './prism-init';
import { initPlaybookReadingPosition } from './reading-position';
import { initPlaybookToc } from './toc';

/** @type {{ disconnect: () => void } | null} */
let tocController = null;

function getActiveLocalePanel(root) {
    return root.querySelector('[data-playbook-locale-panel]:not([hidden])');
}

function destroyPlaybookToc() {
    tocController?.disconnect();
    tocController = null;
}

function initActiveLocalePanel(root) {
    destroyPlaybookToc();

    const panel = getActiveLocalePanel(root);

    if (!panel) return;

    tocController = initPlaybookToc(panel);
}

export function initPlaybookDetail(root) {
    if (!root) return;

    initActiveLocalePanel(root);
    initPlaybookReadingPosition(root);

    try {
        initPlaybookPrism(root);
    } catch (error) {
        console.warn('Playbook syntax highlighting failed.', error);
    }

    window.addEventListener('binom-tools:playbook-locale', () => {
        initActiveLocalePanel(root);
        initPlaybookReadingPosition(root);

        try {
            initPlaybookPrism(root);
        } catch (error) {
            console.warn('Playbook syntax highlighting failed.', error);
        }
    });
}

document.querySelectorAll('[data-playbook-root]').forEach((root) => {
    initPlaybookDetail(root);
});
