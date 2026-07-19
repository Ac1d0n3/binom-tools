import { initPlaybookLocale } from './playbooks/locale';
import { hydrateServerReadSlugs } from './playbooks/read-state';
import { initLocaleControls, initSidebarToggle } from './locale';
import { initOverviewFilters } from './overview-filter';
import { initShellLayoutControls } from './shell-layout';
import { initThemeControls } from './theme';
import { initExternalLinks } from './external-links';
import { initCookieConsent } from './cookie-consent';

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
initPlaybookLocale();
initOverviewFilters();
initExternalLinks();
initCookieConsent();
