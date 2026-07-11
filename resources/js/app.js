import { initPlaybookLocale } from './playbooks/locale';
import { initLocaleControls, initSidebarToggle } from './locale';
import { initOverviewFilters } from './overview-filter';
import { initThemeControls } from './theme';

initThemeControls();
initLocaleControls();
initSidebarToggle();
initPlaybookLocale();
initOverviewFilters();
