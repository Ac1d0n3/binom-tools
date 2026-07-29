import { initPlaybookLocale } from '../../modules/playbooks/js/locale';
import { hydrateServerReadSlugs } from '../../modules/playbooks/js/read-state';
import { initLocaleControls, initSidebarToggle } from './shell/locale';
import { initOverviewFilters } from './shared/overview-filter';
import { initShellLayoutControls } from './shell/shell-layout';
import { initSidenavAccordions } from './shell/sidenav-accordion';
import { initThemeControls } from './shell/theme';
import { initExternalLinks } from './shell/external-links';
import { initCookieConsent } from './shell/cookie-consent';
import { initDisclaimerBanner } from './shell/disclaimer-banner';
import { initPlaybookCardActions } from '../../modules/playbooks/js/card-actions';
import { initSupplierLibraryCopy, initSupplierLibraryTabs } from '../../modules/suppliers/js/suppliers-copy';
import { initToolsPhoneGate } from './shell/tools-phone-gate';
import { initWorkflowFlowcharts } from './shared/workflow-flowchart';
import { initToolPageHeaders } from './shared/tool-page-header';

try {
    const raw = document.documentElement.dataset.accountsReadSlugs;
    if (raw) {
        hydrateServerReadSlugs(JSON.parse(raw));
    }
} catch {
    // ignore invalid bootstrap
}

initThemeControls();
initShellLayoutControls();
initLocaleControls();
initSidebarToggle();
initSidenavAccordions();
initPlaybookLocale();
initOverviewFilters();
initToolsPhoneGate();
initExternalLinks();
initCookieConsent();
initDisclaimerBanner();
initPlaybookCardActions();
initSupplierLibraryCopy();
initSupplierLibraryTabs();
initWorkflowFlowcharts();
initToolPageHeaders();

if (document.querySelector('[data-glossary-quiz], [data-glossary-quiz-modal]')) {
    void import('../../modules/glossary/js/glossary-quiz.js').catch((error) => {
        console.warn('Glossary quiz failed to load.', error);
    });
}

if (document.querySelector('[data-glossary-bingo]')) {
    void import('../../modules/glossary/js/glossary-bingo.js').catch((error) => {
        console.warn('Glossary bingo failed to load.', error);
    });
}

if (document.querySelector('[data-playbook-offline-index], [data-playbook-card-offline], [data-playbook-series-offline]')) {
    void import('../../modules/playbooks/js/offline-ui')
        .then(({ initOfflineBanner, initPlaybookOfflineIndex }) => {
            initOfflineBanner();
            return initPlaybookOfflineIndex(document);
        })
        .catch((error) => {
            console.warn('Playbook offline controls failed to load.', error);
        });
}

if (document.querySelector('[data-playbook-slides-open]')) {
    void import('../../modules/playbooks/js/slides-gallery.js')
        .then(({ initPlaybookSlidesGallery }) => {
            initPlaybookSlidesGallery(document);
        })
        .catch((error) => {
            console.warn('Playbook slides gallery failed to load.', error);
        });
}
