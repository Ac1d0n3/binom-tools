import { initPlaybookLocale } from './playbooks/locale';
import { hydrateServerReadSlugs } from './playbooks/read-state';
import { initLocaleControls, initSidebarToggle } from './locale';
import { initOverviewFilters } from './overview-filter';
import { initShellLayoutControls } from './shell-layout';
import { initSidenavAccordions } from './sidenav-accordion';
import { initThemeControls } from './theme';
import { initExternalLinks } from './external-links';
import { initCookieConsent } from './cookie-consent';
import { initPlaybookCardActions } from './playbooks/card-actions';

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
initExternalLinks();
initCookieConsent();
initPlaybookCardActions();

if (document.querySelector('[data-playbook-offline-index], [data-playbook-card-offline]')) {
    void import('./playbooks/offline-ui')
        .then(({ initOfflineBanner, initPlaybookOfflineIndex }) => {
            initOfflineBanner();
            return initPlaybookOfflineIndex(document);
        })
        .catch((error) => {
            console.warn('Playbook offline controls failed to load.', error);
        });
}
