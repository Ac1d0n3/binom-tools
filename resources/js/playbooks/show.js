import { initPlaybookImageLightbox } from './image-lightbox';
import { initPlaybookPrism } from './prism-init';
import { initPlaybookReadingPosition } from './reading-position';
import { initPlaybookReadTracker } from './read-tracker';
import { initPlaybookToc } from './toc';
import { initPlaybookVideoEmbeds } from './video-embed';

/** @type {{ disconnect: () => void } | null} */
let tocController = null;
/** @type {{ disconnect: () => void } | null} */
let lightboxController = null;

function getActiveLocalePanel(root) {
    return root.querySelector('[data-playbook-locale-panel]:not([hidden])');
}

function destroyPlaybookToc() {
    tocController?.disconnect();
    tocController = null;
}

function destroyPlaybookLightbox() {
    lightboxController?.disconnect();
    lightboxController = null;
}

function initActiveLocalePanel(root) {
    destroyPlaybookToc();
    destroyPlaybookLightbox();

    const panel = getActiveLocalePanel(root);

    if (!panel) return;

    tocController = initPlaybookToc(panel);
    lightboxController = initPlaybookImageLightbox(panel);
}

export function initPlaybookDetail(root) {
    if (!root) return;

    initActiveLocalePanel(root);
    initPlaybookReadingPosition(root);
    initPlaybookReadTracker(root);

    try {
        initPlaybookPrism(root);
    } catch (error) {
        console.warn('Playbook syntax highlighting failed.', error);
    }

    initPlaybookVideoEmbeds(root);

    window.addEventListener('binom-tools:playbook-locale', () => {
        initActiveLocalePanel(root);

        try {
            initPlaybookPrism(root);
        } catch (error) {
            console.warn('Playbook syntax highlighting failed.', error);
        }

        initPlaybookVideoEmbeds(getActiveLocalePanel(root) ?? root);
    });
}

document.querySelectorAll('[data-playbook-root]').forEach((root) => {
    initPlaybookDetail(root);
});
