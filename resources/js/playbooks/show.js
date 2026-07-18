import { initPlaybookEngagement } from './engagement';
import { initPlaybookImageLightbox } from './image-lightbox';
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

/**
 * Prism is large — load after first paint / when code blocks exist.
 * @param {ParentNode} root
 */
function schedulePlaybookPrism(root) {
    const run = () => {
        void import('./prism-init')
            .then(({ initPlaybookPrism }) => {
                try {
                    initPlaybookPrism(root);
                } catch (error) {
                    console.warn('Playbook syntax highlighting failed.', error);
                }
            })
            .catch((error) => {
                console.warn('Playbook syntax highlighting failed to load.', error);
            });
    };

    const hasCode = root.querySelector('.playbook-code');

    if (!hasCode) {
        return;
    }

    if ('requestIdleCallback' in window) {
        window.requestIdleCallback(run, { timeout: 2000 });
    } else {
        window.setTimeout(run, 1);
    }
}

export function initPlaybookDetail(root) {
    if (!root) return;

    initActiveLocalePanel(root);
    initPlaybookReadingPosition(root);
    initPlaybookReadTracker(root);
    initPlaybookEngagement(root);
    schedulePlaybookPrism(root);
    initPlaybookVideoEmbeds(root);

    window.addEventListener('binom-tools:playbook-locale', () => {
        initActiveLocalePanel(root);
        initPlaybookEngagement(root);
        schedulePlaybookPrism(root);
        initPlaybookVideoEmbeds(getActiveLocalePanel(root) ?? root);
    });
}

document.querySelectorAll('[data-playbook-root]').forEach((root) => {
    initPlaybookDetail(root);
});
