import { initPlaybookLocale } from './playbooks/locale';
import { initLocaleControls, initSidebarToggle } from './locale';
import { initOverviewFilters } from './overview-filter';
import { initThemeControls } from './theme';
import { initExternalLinks } from './external-links';

initThemeControls();
initLocaleControls();
initSidebarToggle();
initPlaybookLocale();
initOverviewFilters();
initExternalLinks();
