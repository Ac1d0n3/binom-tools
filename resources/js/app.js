import { initPlaybookLocale } from './playbooks/locale';
import { initLocaleControls, initSidebarToggle } from './locale';
import { initOverviewFilters } from './overview-filter';
import { initShellLayoutControls } from './shell-layout';
import { initThemeControls } from './theme';
import { initExternalLinks } from './external-links';

initThemeControls();
initShellLayoutControls();
initLocaleControls();
initSidebarToggle();
initPlaybookLocale();
initOverviewFilters();
initExternalLinks();
