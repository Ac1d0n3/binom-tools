/** Client-side search and tag filtering for overview index pages. */
import { getLocale, getShellLabel } from './locale';
import { syncSelectOptionAvailability } from './overview-filter.cascade.js';
import { compareStoryItemsForSort } from './overview-filter.sort.js';
import {
    clearAllPlaybookRead,
    hasAnyPlaybookRead,
    isPlaybookRead,
} from './playbooks/read-state';
import { attachOverviewProgressiveReveal } from './overview-progressive-reveal.js';

const TAG_SIDEBAR_STORAGE_KEY = 'binom-tools-tag-sidebar';
const FILTER_TAB_STORAGE_KEY = 'binom-tools-filter-tab';
const OVERVIEW_VIEW_STORAGE_KEY = 'binom-tools-overview-view';
const OVERVIEW_SORT_STORAGE_KEY = 'binom-tools-overview-sort';
const OVERVIEW_LAYOUT_STORAGE_KEY = 'binom-tools-overview-layout';
const OVERVIEW_HIDE_READ_STORAGE_KEY = 'binom-tools-overview-hide-read-v2';
const FILTER_TAG_MODE_STORAGE_KEY = 'binom-tools-filter-tag-mode';
const GLOSSARY_AZ_PANEL_STORAGE_KEY = 'binom-tools-glossary-az-panel';

/** @typedef {'date-desc' | 'date-asc' | 'name-asc' | 'name-desc'} OverviewSortKey */
/** @typedef {'grid' | 'list'} OverviewLayoutMode */
/** @typedef {'or' | 'and'} TagMatchMode */

export function initOverviewFilters() {
    const root = document.querySelector('[data-overview-filter-root]');
    if (!root || root.dataset.overviewFiltersBound === 'true') {
        return;
    }

    root.dataset.overviewFiltersBound = 'true';

    initTagSidebar(root);
    initFilterSidebarTabs(root);
    initTagSidebarSearch(root);
    initOverviewViewToggle(root);
    initOverviewLayoutToggle(root);
    initGlossaryAzFilter(root);

    const searchInput = /** @type {HTMLInputElement | null} */ (
        root.querySelector('[data-overview-search]')
    );
    const letterButtons = root.querySelectorAll('[data-glossary-letter]');
    const productSelect = /** @type {HTMLSelectElement | null} */ (
        root.querySelector('select[data-overview-product]')
    );
    const productButtons = root.querySelectorAll('button[data-overview-product]');
    const modelSelect = /** @type {HTMLSelectElement | null} */ (
        root.querySelector('[data-overview-model]')
    );
    const residencySelect = /** @type {HTMLSelectElement | null} */ (
        root.querySelector('[data-overview-residency]')
    );
    const vendorSelect = /** @type {HTMLSelectElement | null} */ (
        root.querySelector('[data-overview-vendor]')
    );
    const stackSelect = /** @type {HTMLSelectElement | null} */ (
        root.querySelector('[data-overview-stack]')
    );
    const stackBanner = root.querySelector('[data-overview-stack-banner]');
    const stackBannerTitle = root.querySelector('[data-overview-stack-banner-title]');
    const stackBannerDesc = root.querySelector('[data-overview-stack-banner-desc]');
    const stackBannerChips = root.querySelector('[data-overview-stack-banner-chips]');
    const categoryButtons = root.querySelectorAll('[data-overview-category]');
    const tagButtons = root.querySelectorAll('[data-overview-tag]');
    const tagModeRoot = root.querySelector('[data-tag-match-mode]');
    const filterResetButton = root.querySelector('[data-overview-filter-reset]');
    const storyItems = root.querySelectorAll('[data-overview-item]');
    const seriesItems = root.querySelectorAll('[data-overview-series-item]');
    const workflowSections = root.querySelectorAll('[data-overview-workflow-section]');
    const emptyEl = root.querySelector('[data-overview-empty]');
    const unreadEmptyEl = root.querySelector('[data-overview-unread-empty]');
    const seriesEmptyEl = root.querySelector('[data-overview-series-empty]');
    const resultCountEl = root.querySelector('[data-overview-result-count]');
    const hideReadToggle = root.querySelector('[data-overview-hide-read]');
    const readResetButton = root.querySelector('[data-overview-read-reset]');

    /** @type {string} */
    let activeCategoryKey = 'all';

    /** @type {string} */
    let activeProductKey = 'all';

    /** @type {string} */
    let activeLetterKey = 'all';

    /** @type {Set<string>} */
    const activeTags = new Set();

    /** @type {TagMatchMode} */
    let tagMatchMode = readTagMatchMode();

    /** @type {OverviewSortKey} */
    let activeSort = readOverviewSort(root);

    let hideRead = localStorage.getItem(OVERVIEW_HIDE_READ_STORAGE_KEY) !== 'false';
    if (localStorage.getItem(OVERVIEW_HIDE_READ_STORAGE_KEY) === null) {
        localStorage.setItem(OVERVIEW_HIDE_READ_STORAGE_KEY, 'true');
    }

    /** @type {{ refresh: () => void, destroy: () => void } | null} */
    let progressiveReveal = null;

    /** @param {string} value */
    const normalize = (value) => value.toLowerCase().trim();

    /** @returns {ToolsLocale} */
    const locale = () => getLocale();

    /** @returns {string} */
    const activeProduct = () => {
        if (productButtons.length > 0) {
            return activeProductKey;
        }

        return productSelect?.value || 'all';
    };

    /** @returns {boolean} */
    const hasProductFilter = () => productButtons.length > 0 || productSelect !== null;

    /** @returns {string} */
    const activeModel = () => modelSelect?.value || 'all';

    /** @returns {string} */
    const activeResidency = () => residencySelect?.value || 'all';

    /** @returns {string} */
    const activeVendor = () => vendorSelect?.value || 'all';

    /** @returns {string} */
    const activeStack = () => stackSelect?.value || 'all';

    /**
     * @param {Element} grid
     * @param {string} itemSelector
     * @param {(a: Element, b: Element) => number} compare
     */
    const reorderGrid = (grid, itemSelector, compare) => {
        const items = Array.from(grid.querySelectorAll(itemSelector));
        const visible = items.filter((item) => !(item instanceof HTMLElement && item.hidden));
        const hidden = items.filter((item) => item instanceof HTMLElement && item.hidden);

        visible.sort(compare);
        [...visible, ...hidden].forEach((item) => grid.appendChild(item));
    };

    /** @param {Element} a @param {Element} b */
    const compareStoryItems = (a, b) => compareStoryItemsForSort(a, b, activeSort, locale());

    /** @param {Element} a @param {Element} b */
    const compareSeriesItems = (a, b) => {
        const titleKey = locale() === 'de' ? 'data-sort-title-de' : 'data-sort-title-en';
        const dateA = Number(a.getAttribute('data-sort-date') ?? 0);
        const dateB = Number(b.getAttribute('data-sort-date') ?? 0);
        const countA = Number(a.getAttribute('data-sort-part-count') ?? 0);
        const countB = Number(b.getAttribute('data-sort-part-count') ?? 0);
        const titleA = normalize(a.getAttribute(titleKey) ?? '');
        const titleB = normalize(b.getAttribute(titleKey) ?? '');

        if (activeSort.startsWith('date-')) {
            const cmp = dateA - dateB;

            if (cmp !== 0) {
                return activeSort === 'date-desc' ? -cmp : cmp;
            }

            if (countA !== countB) {
                return countB - countA;
            }

            return titleA.localeCompare(titleB, locale());
        }

        const nameCmp = titleA.localeCompare(titleB, locale());

        if (nameCmp !== 0) {
            return activeSort === 'name-desc' ? -nameCmp : nameCmp;
        }

        if (countA !== countB) {
            return countB - countA;
        }

        return dateB - dateA;
    };

    const sortStories = () => {
        const grid = root.querySelector('[data-overview-stories-grid]');

        if (grid instanceof HTMLElement) {
            reorderGrid(grid, '[data-overview-item]', compareStoryItems);
        }
    };

    const sortSeries = () => {
        const grid = root.querySelector('#playbook-overview-series .tools-card-grid');

        if (grid instanceof HTMLElement) {
            reorderGrid(grid, '[data-overview-series-item]', compareSeriesItems);
        }
    };

    /** @returns {'stories' | 'series'} */
    const activeView = () => {
        const layout = root.querySelector('.tools-overview-layout');

        return layout?.classList.contains('tools-overview-layout--view-series') ? 'series' : 'stories';
    };

    /** @param {string[]} tags */
    const matchesTagFilter = (tags) => {
        if (activeTags.size === 0) {
            return true;
        }

        if (tagMatchMode === 'or') {
            return [...activeTags].some((tag) => tags.includes(tag));
        }

        return [...activeTags].every((tag) => tags.includes(tag));
    };

    /** @param {string[]} products */
    const matchesProductFilter = (products) => activeProduct() === 'all' || products.includes(activeProduct());

    /** @param {string[]} models */
    const matchesModelFilter = (models) => activeModel() === 'all' || models.includes(activeModel());

    /** @param {string[]} residencies */
    const matchesResidencyFilter = (residencies) =>
        activeResidency() === 'all' || residencies.includes(activeResidency());

    /** @param {string} vendor */
    const matchesVendorFilter = (vendor) => activeVendor() === 'all' || vendor === activeVendor();

    /** @param {string[]} stacks */
    const matchesStackFilter = (stacks) => activeStack() === 'all' || stacks.includes(activeStack());

    /** @param {Element} item */
    const matchesLetterFilter = (item) => {
        if (activeLetterKey === 'all' || letterButtons.length === 0) {
            return true;
        }

        const attr = locale() === 'de' ? 'data-letter-de' : 'data-letter-en';
        const letter = item.getAttribute(attr) ?? item.getAttribute('data-letter-en') ?? '';

        return letter === activeLetterKey;
    };

    const syncLetterChips = () => {
        if (letterButtons.length === 0) {
            return;
        }

        const loc = locale();
        letterButtons.forEach((button) => {
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            const key = button.getAttribute('data-glossary-letter') ?? '';
            button.classList.toggle('tools-filter-chip--active', key === activeLetterKey);

            if (key === 'all') {
                button.disabled = false;
                button.removeAttribute('aria-disabled');
                return;
            }

            const available =
                button.getAttribute(loc === 'de' ? 'data-letter-available-de' : 'data-letter-available-en') ===
                '1';
            button.disabled = !available;
            button.setAttribute('aria-disabled', available ? 'false' : 'true');
            if (!available && activeLetterKey === key) {
                activeLetterKey = 'all';
            }
        });

        letterButtons.forEach((button) => {
            const key = button.getAttribute('data-glossary-letter') ?? '';
            button.classList.toggle('tools-filter-chip--active', key === activeLetterKey);
        });
    };

    /** @param {number} count */
    const syncResultCount = (count) => {
        if (!(resultCountEl instanceof HTMLElement)) {
            return;
        }

        const countText = String(count);
        resultCountEl.setAttribute('data-i18n-count', countText);
        if (resultCountEl.hasAttribute('data-overview-count-badge')) {
            resultCountEl.textContent = countText;
            return;
        }
        const key = resultCountEl.getAttribute('data-i18n') || 'resources.visibleVendorCount';
        const template = getShellLabel(key, locale());
        resultCountEl.textContent = template.replace(/\{\{count\}\}/g, countText);
    };

    /** @param {Element} item */
    const itemFamilies = (item) =>
        (item.getAttribute('data-products') ?? '')
            .split(',')
            .map((value) => value.trim())
            .filter(Boolean);

    /** @param {Element} item */
    const itemStacks = (item) =>
        (item.getAttribute('data-stacks') ?? '')
            .split(',')
            .map((value) => value.trim())
            .filter(Boolean);

    /**
     * Filter Hersteller options by all upstream dropdowns
     * (stack, family, model, residency) — not by the vendor itself.
     */
    const syncCascadingFilters = () => {
        if (!vendorSelect || storyItems.length === 0) {
            return;
        }

        /** @param {Element} item */
        const itemModels = (item) =>
            (item.getAttribute('data-models') ?? '')
                .split(',')
                .map((value) => value.trim())
                .filter(Boolean);

        /** @param {Element} item */
        const itemResidencies = (item) =>
            (item.getAttribute('data-residency') ?? '')
                .split(',')
                .map((value) => value.trim())
                .filter(Boolean);

        /** @param {Element} item */
        const matchesUpstreamFilters = (item) =>
            matchesProductFilter(itemFamilies(item)) &&
            matchesStackFilter(itemStacks(item)) &&
            matchesModelFilter(itemModels(item)) &&
            matchesResidencyFilter(itemResidencies(item));

        syncSelectOptionAvailability(vendorSelect, (vendorId) =>
            Array.from(storyItems).some((item) => {
                if ((item.getAttribute('data-vendor') ?? '') !== vendorId) {
                    return false;
                }

                return matchesUpstreamFilters(item);
            }),
        );

        // Re-apply locale labels after options were rebuilt.
        const loc = locale();
        Array.from(vendorSelect.options).forEach((option) => {
            const i18nKey = option.getAttribute('data-i18n');
            if (i18nKey) {
                option.textContent = getShellLabel(i18nKey, loc);
                return;
            }

            const text = option.getAttribute(loc === 'de' ? 'data-text-de' : 'data-text-en');
            if (text) {
                option.textContent = text;
            }
        });
    };

    const syncStackBanner = () => {
        if (!(stackBanner instanceof HTMLElement) || !stackSelect) {
            return;
        }

        const stackId = activeStack();
        if (stackId === 'all') {
            stackBanner.hidden = true;
            if (stackBannerTitle instanceof HTMLElement) {
                stackBannerTitle.textContent = '';
            }
            if (stackBannerDesc instanceof HTMLElement) {
                stackBannerDesc.textContent = '';
            }
            if (stackBannerChips instanceof HTMLElement) {
                stackBannerChips.replaceChildren();
                stackBannerChips.classList.remove('vendor-resources-stack-banner__chips--slots');
            }
            return;
        }

        const option = stackSelect.selectedOptions[0] ?? null;
        if (!(option instanceof HTMLOptionElement)) {
            stackBanner.hidden = true;
            return;
        }

        const loc = locale();
        const title =
            option.getAttribute(loc === 'de' ? 'data-text-de' : 'data-text-en') ||
            option.textContent ||
            stackId;
        const description =
            option.getAttribute(loc === 'de' ? 'data-description-de' : 'data-description-en') || '';
        const productIds = (option.getAttribute('data-products') ?? '')
            .split(',')
            .map((id) => id.trim())
            .filter(Boolean);

        /** @type {Array<{role?: {de?: string, en?: string}, products?: string[], chooseOne?: boolean}>} */
        let slots = [];
        const slotsRaw = option.getAttribute('data-slots') ?? '';
        if (slotsRaw !== '') {
            try {
                const parsed = JSON.parse(slotsRaw);
                if (Array.isArray(parsed)) {
                    slots = parsed;
                }
            } catch {
                slots = [];
            }
        }

        if (stackBannerTitle instanceof HTMLElement) {
            stackBannerTitle.textContent = title;
        }
        if (stackBannerDesc instanceof HTMLElement) {
            stackBannerDesc.textContent = description;
            stackBannerDesc.hidden = description === '';
        }
        if (stackBannerChips instanceof HTMLElement) {
            stackBannerChips.replaceChildren();

            /**
             * @param {string} productId
             * @returns {string}
             */
            const labelForProduct = (productId) => {
                const card = root.querySelector(`[data-product-id="${CSS.escape(productId)}"]`);
                return (
                    (card instanceof HTMLElement
                        ? card.getAttribute(loc === 'de' ? 'data-sort-title-de' : 'data-sort-title-en')
                        : null) || productId
                );
            };

            if (slots.length > 0) {
                stackBannerChips.classList.add('vendor-resources-stack-banner__chips--slots');
                const orLabel = getShellLabel('resources.stackChooseOr', loc);

                slots.forEach((slot) => {
                    const slotProducts = Array.isArray(slot.products)
                        ? slot.products.filter((id) => typeof id === 'string' && id !== '')
                        : [];
                    if (slotProducts.length === 0) {
                        return;
                    }

                    const roleLabel =
                        (loc === 'de' ? slot.role?.de : slot.role?.en) ||
                        slot.role?.en ||
                        slot.role?.de ||
                        '';
                    const chooseOne = Boolean(slot.chooseOne);

                    const group = document.createElement('li');
                    group.className = 'vendor-resources-stack-banner__slot';

                    if (roleLabel !== '') {
                        const roleEl = document.createElement('span');
                        roleEl.className = 'vendor-resources-stack-banner__slot-role';
                        roleEl.textContent = roleLabel;
                        group.appendChild(roleEl);
                    }

                    const productsWrap = document.createElement('span');
                    productsWrap.className = 'vendor-resources-stack-banner__slot-products';

                    slotProducts.forEach((productId, index) => {
                        if (chooseOne && index > 0) {
                            const sep = document.createElement('span');
                            sep.className = 'vendor-resources-stack-banner__or';
                            sep.textContent = orLabel;
                            productsWrap.appendChild(sep);
                        }

                        const chip = document.createElement('span');
                        chip.className = 'vendor-resources-stack-banner__chip';
                        chip.textContent = labelForProduct(productId);
                        productsWrap.appendChild(chip);
                    });

                    group.appendChild(productsWrap);
                    stackBannerChips.appendChild(group);
                });
            } else {
                stackBannerChips.classList.remove('vendor-resources-stack-banner__chips--slots');
                productIds.forEach((productId) => {
                    const li = document.createElement('li');
                    li.className = 'vendor-resources-stack-banner__chip';
                    li.textContent = labelForProduct(productId);
                    stackBannerChips.appendChild(li);
                });
            }
        }

        stackBanner.hidden = false;
    };

    const syncTagAllChip = () => {
        tagButtons.forEach((button) => {
            const tag = button.getAttribute('data-overview-tag') ?? '';

            if (tag === 'all') {
                button.classList.toggle('tools-filter-chip--active', activeTags.size === 0);
            }
        });
    };

    const syncFilterReset = () => {
        if (!(filterResetButton instanceof HTMLButtonElement)) {
            return;
        }

        const hasFilters =
            activeCategoryKey !== 'all' ||
            activeTags.size > 0 ||
            activeProduct() !== 'all' ||
            activeModel() !== 'all' ||
            activeResidency() !== 'all' ||
            activeVendor() !== 'all' ||
            activeStack() !== 'all' ||
            activeLetterKey !== 'all';
        filterResetButton.disabled = !hasFilters;
        filterResetButton.setAttribute('aria-disabled', hasFilters ? 'false' : 'true');
    };

    const applyWorkflowSections = () => {
        if (workflowSections.length === 0) {
            return;
        }

        workflowSections.forEach((section) => {
            const products = (section.getAttribute('data-products') ?? '')
                .split(',')
                .map((product) => product.trim())
                .filter(Boolean);

            const childItems = section.querySelectorAll('[data-overview-item]');
            const hasVisibleChild =
                childItems.length === 0
                    ? true
                    : Array.from(childItems).some(
                          (item) => item instanceof HTMLElement && !item.hidden,
                      );

            section.hidden = !matchesProductFilter(products) || !hasVisibleChild;
        });
    };

    const applyStories = () => {
        const query = normalize(searchInput?.value ?? '');
        let visible = 0;
        let wouldShowButRead = 0;
        /** @type {Set<string>} */
        const visibleVendors = new Set();

        storyItems.forEach((item) => {
            const text = normalize(item.getAttribute('data-search-text') ?? '');
            const tags = (item.getAttribute('data-tags') ?? '')
                .split(',')
                .map((tag) => tag.trim())
                .filter(Boolean);
            const products = (item.getAttribute('data-products') ?? '')
                .split(',')
                .map((product) => product.trim())
                .filter(Boolean);
            const models = (item.getAttribute('data-models') ?? '')
                .split(',')
                .map((model) => model.trim())
                .filter(Boolean);
            const residencies = (item.getAttribute('data-residency') ?? '')
                .split(',')
                .map((value) => value.trim())
                .filter(Boolean);
            const vendor = item.getAttribute('data-vendor') ?? '';
            const stacks = (item.getAttribute('data-stacks') ?? '')
                .split(',')
                .map((stack) => stack.trim())
                .filter(Boolean);
            const slug = item.getAttribute('data-playbook-slug') ?? '';
            const categoryKey = item.getAttribute('data-category-key') ?? '';
            const matchesSearch = query === '' || text.includes(query);
            const matchesCategory = activeCategoryKey === 'all' || categoryKey === activeCategoryKey;
            const matchesTag = matchesTagFilter(tags);
            const matchesProduct = !hasProductFilter() || matchesProductFilter(products);
            const matchesModel = modelSelect === null || matchesModelFilter(models);
            const matchesResidency = residencySelect === null || matchesResidencyFilter(residencies);
            const matchesVendor = vendorSelect === null || matchesVendorFilter(vendor);
            const matchesStack = stackSelect === null || matchesStackFilter(stacks);
            const matchesLetter = matchesLetterFilter(item);
            const read = isPlaybookRead(slug);

            if (
                matchesSearch &&
                matchesCategory &&
                matchesTag &&
                matchesProduct &&
                matchesModel &&
                matchesResidency &&
                matchesVendor &&
                matchesStack &&
                matchesLetter &&
                hideRead &&
                read
            ) {
                wouldShowButRead += 1;
            }

            const show =
                matchesSearch &&
                matchesCategory &&
                matchesTag &&
                matchesProduct &&
                matchesModel &&
                matchesResidency &&
                matchesVendor &&
                matchesStack &&
                matchesLetter &&
                (!hideRead || !read);

            item.hidden = !show;
            if (show) {
                visible += 1;
                if (vendor !== '') {
                    visibleVendors.add(vendor);
                }
            }
        });

        const showUnreadEmpty = hideRead && visible === 0 && wouldShowButRead > 0;

        if (emptyEl instanceof HTMLElement) {
            emptyEl.hidden = visible > 0 || showUnreadEmpty;
        }

        if (unreadEmptyEl instanceof HTMLElement) {
            unreadEmptyEl.hidden = !showUnreadEmpty;
        }

        syncResultCount(
            resultCountEl instanceof HTMLElement &&
                resultCountEl.getAttribute('data-overview-count-mode') === 'items'
                ? visible
                : visibleVendors.size,
        );
        syncOverviewReadControls(hideReadToggle, readResetButton, hideRead);
        syncFilterReset();
        syncStackBanner();
        applyWorkflowSections();
        sortStories();
        progressiveReveal?.refresh();
    };

    const applySeries = () => {
        const query = normalize(searchInput?.value ?? '');
        let visible = 0;
        let wouldShowButCompleted = 0;

        seriesItems.forEach((item) => {
            const text = normalize(item.getAttribute('data-search-text') ?? '');
            const products = (item.getAttribute('data-products') ?? '')
                .split(',')
                .map((product) => product.trim())
                .filter(Boolean);
            const models = (item.getAttribute('data-models') ?? '')
                .split(',')
                .map((model) => model.trim())
                .filter(Boolean);
            const residencies = (item.getAttribute('data-residency') ?? '')
                .split(',')
                .map((value) => value.trim())
                .filter(Boolean);
            const vendor = item.getAttribute('data-vendor') ?? '';
            const stacks = (item.getAttribute('data-stacks') ?? '')
                .split(',')
                .map((stack) => stack.trim())
                .filter(Boolean);
            const partLinks = Array.from(item.querySelectorAll('[data-playbook-series-part][data-slug]'));
            let readParts = 0;
            partLinks.forEach((link) => {
                const slug = link.getAttribute('data-slug') ?? '';
                const read = isPlaybookRead(slug);
                link.classList.toggle('is-read', read);
                link.dataset.read = read ? '1' : '0';
                if (read) {
                    readParts += 1;
                }
            });
            const allPartsRead = partLinks.length > 0 && readParts === partLinks.length;
            const matchesSearch = query === '' || text.includes(query);
            const matchesProduct = !hasProductFilter() || matchesProductFilter(products);
            const matchesModel = modelSelect === null || matchesModelFilter(models);
            const matchesResidency = residencySelect === null || matchesResidencyFilter(residencies);
            const matchesVendor = vendorSelect === null || matchesVendorFilter(vendor);
            const matchesStack = stackSelect === null || matchesStackFilter(stacks);
            const matchesFilters =
                matchesSearch &&
                matchesProduct &&
                matchesModel &&
                matchesResidency &&
                matchesVendor &&
                matchesStack;

            if (matchesFilters && hideRead && allPartsRead) {
                wouldShowButCompleted += 1;
            }

            const show = matchesFilters && (!hideRead || !allPartsRead);

            item.hidden = !show;
            if (show) visible += 1;
        });

        const showCompletedEmpty = hideRead && visible === 0 && wouldShowButCompleted > 0;

        if (seriesEmptyEl instanceof HTMLElement) {
            if (showCompletedEmpty) {
                seriesEmptyEl.setAttribute('data-i18n', 'overview.noIncompleteSeries');
                seriesEmptyEl.textContent = getShellLabel('overview.noIncompleteSeries');
                seriesEmptyEl.hidden = false;
            } else {
                seriesEmptyEl.setAttribute('data-i18n', 'overview.seriesNoResults');
                seriesEmptyEl.textContent = getShellLabel('overview.seriesNoResults');
                seriesEmptyEl.hidden = visible > 0;
            }
        }

        syncResultCount(visible);
        syncOverviewReadControls(hideReadToggle, readResetButton, hideRead, {
            hideKey: 'overview.hideCompletedSeries',
            showKey: 'overview.showCompletedSeries',
        });
        syncFilterReset();
        syncStackBanner();
        sortSeries();
        progressiveReveal?.refresh();
    };

    /**
     * @param {HTMLSelectElement | null} select
     * @param {string} value
     */
    const selectHasOption = (select, value) => {
        if (!select) {
            return false;
        }

        return Array.from(select.options).some((option) => option.value === value);
    };

    /**
     * @param {string} value
     */
    const productChipExists = (value) =>
        Array.from(productButtons).some(
            (button) => (button.getAttribute('data-overview-product') ?? '') === value,
        );

    const syncProductChips = () => {
        productButtons.forEach((button) => {
            const key = button.getAttribute('data-overview-product') ?? '';
            button.classList.toggle('tools-filter-chip--active', key === activeProductKey);
        });
    };

    const readFiltersFromUrl = () => {
        const params = new URLSearchParams(window.location.search);
        const vendor = params.get('vendor');
        const product = params.get('product');
        const query = params.get('q') ?? params.get('search');
        const letter = params.get('letter');

        if (vendor && selectHasOption(vendorSelect, vendor) && vendorSelect) {
            vendorSelect.value = vendor;
        }

        if (product) {
            if (productButtons.length > 0 && productChipExists(product)) {
                activeProductKey = product;
                syncProductChips();
            } else if (selectHasOption(productSelect, product) && productSelect) {
                productSelect.value = product;
            }
        }

        if (letter !== null && letterButtons.length > 0) {
            const normalized = letter.trim().toUpperCase();
            const exists = Array.from(letterButtons).some(
                (button) => (button.getAttribute('data-glossary-letter') ?? '') === normalized
                    || (normalized === 'ALL' && (button.getAttribute('data-glossary-letter') ?? '') === 'all'),
            );

            if (normalized === 'ALL' || normalized === '') {
                activeLetterKey = 'all';
            } else if (exists) {
                activeLetterKey = normalized;
            }
        }

        if (query !== null && searchInput) {
            searchInput.value = query;
        }
    };

    const syncFiltersToUrl = () => {
        const url = new URL(window.location.href);

        /**
         * @param {string} key
         * @param {string} value
         * @param {string[]} emptyValues
         */
        const setOrDelete = (key, value, emptyValues = ['', 'all']) => {
            if (emptyValues.includes(value)) {
                url.searchParams.delete(key);
            } else {
                url.searchParams.set(key, value);
            }
        };

        if (vendorSelect) {
            setOrDelete('vendor', vendorSelect.value);
        }

        if (hasProductFilter()) {
            setOrDelete('product', activeProduct());
        }

        if (letterButtons.length > 0) {
            setOrDelete('letter', activeLetterKey);
        }

        if (searchInput) {
            setOrDelete('q', searchInput.value.trim(), ['']);
            url.searchParams.delete('search');
        }

        const next = `${url.pathname}${url.search}${url.hash}`;
        const current = `${window.location.pathname}${window.location.search}${window.location.hash}`;

        if (next !== current) {
            window.history.replaceState(null, '', next);
        }
    };

    const apply = () => {
        const sortSelect = root.querySelector('[data-overview-sort]');

        if (sortSelect instanceof HTMLSelectElement) {
            const value = sortSelect.value;

            if (
                value === 'date-desc'
                || value === 'date-asc'
                || value === 'name-asc'
                || value === 'name-desc'
            ) {
                activeSort = value;
            }
        }

        syncCascadingFilters();

        if (activeView() === 'series') {
            if (emptyEl instanceof HTMLElement) {
                emptyEl.hidden = true;
            }
            applySeries();
            syncFiltersToUrl();
            return;
        }

        if (seriesEmptyEl instanceof HTMLElement) {
            seriesEmptyEl.hidden = true;
        }
        applyStories();
        syncFiltersToUrl();
    };

    const resetFilters = () => {
        activeCategoryKey = 'all';
        activeProductKey = 'all';
        activeLetterKey = 'all';
        activeTags.clear();

        if (productSelect) {
            productSelect.value = 'all';
        }

        if (modelSelect) {
            modelSelect.value = 'all';
        }

        if (residencySelect) {
            residencySelect.value = 'all';
        }

        if (vendorSelect) {
            vendorSelect.value = 'all';
        }

        if (stackSelect) {
            stackSelect.value = 'all';
        }

        categoryButtons.forEach((button) => {
            const key = button.getAttribute('data-overview-category') ?? '';
            button.classList.toggle('tools-filter-chip--active', key === 'all');
        });

        syncProductChips();
        syncLetterChips();

        tagButtons.forEach((button) => {
            const tag = button.getAttribute('data-overview-tag') ?? '';
            button.classList.toggle('tools-filter-chip--active', tag === 'all');
        });

        apply();
    };

    searchInput?.addEventListener('input', apply);
    productSelect?.addEventListener('change', apply);
    modelSelect?.addEventListener('change', apply);
    residencySelect?.addEventListener('change', apply);
    vendorSelect?.addEventListener('change', apply);
    stackSelect?.addEventListener('change', apply);

    categoryButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (activeView() === 'series') {
                return;
            }

            activeCategoryKey = button.getAttribute('data-overview-category') ?? 'all';
            categoryButtons.forEach((other) => {
                other.classList.toggle('tools-filter-chip--active', other === button);
            });
            apply();
        });
    });

    productButtons.forEach((button) => {
        button.addEventListener('click', () => {
            activeProductKey = button.getAttribute('data-overview-product') ?? 'all';
            syncProductChips();
            apply();
        });
    });

    letterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (button instanceof HTMLButtonElement && button.disabled) {
                return;
            }

            activeLetterKey = button.getAttribute('data-glossary-letter') ?? 'all';
            syncLetterChips();
            apply();
        });
    });

    tagButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (activeView() === 'series') {
                return;
            }

            const tag = button.getAttribute('data-overview-tag') ?? '';

            if (tag === 'all') {
                activeTags.clear();
                tagButtons.forEach((other) => {
                    const otherTag = other.getAttribute('data-overview-tag') ?? '';
                    other.classList.toggle('tools-filter-chip--active', otherTag === 'all');
                });
                apply();
                return;
            }

            if (activeTags.has(tag)) {
                activeTags.delete(tag);
                button.classList.remove('tools-filter-chip--active');
            } else {
                activeTags.add(tag);
                button.classList.add('tools-filter-chip--active');
            }

            syncTagAllChip();
            apply();
        });
    });

    tagModeRoot?.addEventListener('click', (event) => {
        const target = event.target;

        if (!(target instanceof Element)) {
            return;
        }

        const button = target.closest('[data-overview-tag-mode-toggle]');

        if (!(button instanceof HTMLButtonElement) || !(tagModeRoot instanceof HTMLElement)) {
            return;
        }

        const mode = button.getAttribute('data-overview-tag-mode-toggle');

        if (mode !== 'or' && mode !== 'and') {
            return;
        }

        event.preventDefault();
        setTagMatchMode(tagModeRoot, mode);
        tagMatchMode = mode;
        localStorage.setItem(FILTER_TAG_MODE_STORAGE_KEY, mode);
        apply();
    });

    filterResetButton?.addEventListener('click', resetFilters);

    if (hideReadToggle instanceof HTMLButtonElement) {
        syncOverviewReadControls(hideReadToggle, readResetButton, hideRead);

        hideReadToggle.addEventListener('click', () => {
            hideRead = !hideRead;
            localStorage.setItem(OVERVIEW_HIDE_READ_STORAGE_KEY, hideRead ? 'true' : 'false');
            apply();
        });
    }

    if (readResetButton instanceof HTMLButtonElement) {
        readResetButton.addEventListener('click', () => {
            if (!hasAnyPlaybookRead()) {
                return;
            }

            const confirmed = window.confirm(getShellLabel('overview.resetReadConfirm'));

            if (!confirmed) {
                return;
            }

            clearAllPlaybookRead();
            apply();
        });
    }

    window.addEventListener('binom-tools:playbook-read', apply);
    window.addEventListener('binom-tools:playbook-read-reset', apply);
    window.addEventListener('pageshow', apply);
    window.addEventListener('binom-tools:locale', () => {
        syncStackBanner();
        syncOverviewReadControls(hideReadToggle, readResetButton, hideRead);
        syncLetterChips();
        apply();
    });

    initOverviewSort(root);

    if (tagModeRoot instanceof HTMLElement) {
        setTagMatchMode(tagModeRoot, tagMatchMode);
    }

    readFiltersFromUrl();
    syncLetterChips();

    if (activeLetterKey !== 'all') {
        const azPanel = root.querySelector('[data-glossary-az-panel]');
        const azToggle = root.querySelector('[data-glossary-az-toggle]');
        if (azPanel instanceof HTMLElement && azToggle instanceof HTMLElement) {
            azPanel.hidden = false;
            azToggle.setAttribute('aria-expanded', 'true');
            localStorage.setItem(GLOSSARY_AZ_PANEL_STORAGE_KEY, 'open');
        }
    }

    progressiveReveal = attachOverviewProgressiveReveal(root, {
        getSearchQuery: () => searchInput?.value ?? '',
    });
    apply();
}

/**
 * Collapsible A–Z letter strip for the glossary hub header.
 *
 * @param {ParentNode} root
 */
function initGlossaryAzFilter(root) {
    const toggle = root.querySelector('[data-glossary-az-toggle]');
    const panel = root.querySelector('[data-glossary-az-panel]');

    if (!(toggle instanceof HTMLElement) || !(panel instanceof HTMLElement)) {
        return;
    }

    const stored = localStorage.getItem(GLOSSARY_AZ_PANEL_STORAGE_KEY);
    const expanded = stored === 'open';

    /**
     * @param {boolean} open
     */
    const setExpanded = (open) => {
        panel.hidden = !open;
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        localStorage.setItem(GLOSSARY_AZ_PANEL_STORAGE_KEY, open ? 'open' : 'collapsed');
    };

    toggle.addEventListener('click', () => {
        setExpanded(panel.hidden);
    });

    setExpanded(expanded);
}

/**
 * @param {Element | null} hideReadToggle
 * @param {Element | null} readResetButton
 * @param {boolean} hideRead
 * @param {{ hideKey?: string, showKey?: string }} [labels]
 */
function syncOverviewReadControls(hideReadToggle, readResetButton, hideRead, labels = {}) {
    if (hideReadToggle instanceof HTMLButtonElement) {
        hideReadToggle.setAttribute('aria-pressed', hideRead ? 'true' : 'false');
        hideReadToggle.classList.toggle('tools-overview-read-controls__button--active', hideRead);

        const icon = hideReadToggle.querySelector('i');
        // When read items are hidden, the next action is "show them again".
        const labelKey = hideRead
            ? (labels.showKey ?? 'overview.showRead')
            : (labels.hideKey ?? 'overview.hideRead');
        const label = getShellLabel(labelKey);

        if (icon instanceof HTMLElement) {
            icon.classList.toggle('fa-eye', !hideRead);
            icon.classList.toggle('fa-eye-slash', hideRead);
        }

        hideReadToggle.setAttribute('data-i18n-aria', labelKey);
        hideReadToggle.setAttribute('aria-label', label);
        hideReadToggle.setAttribute('title', label);

        const srOnly = hideReadToggle.querySelector('.sr-only');
        if (srOnly instanceof HTMLElement) {
            srOnly.setAttribute('data-i18n', labelKey);
            srOnly.textContent = label;
        }
    }

    if (readResetButton instanceof HTMLButtonElement) {
        const canReset = hasAnyPlaybookRead();
        readResetButton.disabled = !canReset;
        readResetButton.setAttribute('aria-disabled', canReset ? 'false' : 'true');
    }
}

/**
 * @returns {TagMatchMode}
 */
function readTagMatchMode() {
    const stored = localStorage.getItem(FILTER_TAG_MODE_STORAGE_KEY);

    return stored === 'and' ? 'and' : 'or';
}

/**
 * @param {HTMLElement} root
 * @param {TagMatchMode} mode
 */
function setTagMatchMode(root, mode) {
    root.dataset.tagMatchMode = mode;

    root.querySelectorAll('[data-overview-tag-mode-toggle]').forEach((button) => {
        const buttonMode = button.getAttribute('data-overview-tag-mode-toggle');
        const active = buttonMode === mode;
        button.classList.toggle('tools-filter-sidebar__tag-mode-btn--active', active);

        if (button instanceof HTMLElement) {
            button.setAttribute('aria-pressed', active ? 'true' : 'false');
        }
    });
}

/**
 * @param {ParentNode} root
 * @returns {OverviewSortKey}
 */
function readOverviewSort(root) {
    const select = root.querySelector('[data-overview-sort]');
    const stored = localStorage.getItem(OVERVIEW_SORT_STORAGE_KEY);

    /** @type {OverviewSortKey} */
    const fallback = stored === 'date-asc'
        || stored === 'name-asc'
        || stored === 'name-desc'
        ? stored
        : 'date-desc';

    if (select instanceof HTMLSelectElement) {
        select.value = fallback;
    }

    return fallback;
}

/**
 * @param {ParentNode} root
 */
function initOverviewLayoutToggle(root) {
    const toggles = root.querySelectorAll('[data-overview-layout-toggle]');
    const storiesGrid = root.querySelector('[data-overview-stories-grid]');
    const seriesGrid = root.querySelector('[data-overview-series-grid]');

    if (toggles.length === 0 || (!(storiesGrid instanceof HTMLElement) && !(seriesGrid instanceof HTMLElement))) {
        return;
    }

    const stored = localStorage.getItem(OVERVIEW_LAYOUT_STORAGE_KEY);
    const initialLayout = stored === 'list' ? 'list' : 'grid';

    /** @param {OverviewLayoutMode} layout */
    const setLayout = (layout) => {
        const isList = layout === 'list';

        if (storiesGrid instanceof HTMLElement) {
            storiesGrid.classList.toggle('tools-card-grid--list', isList);
        }
        if (seriesGrid instanceof HTMLElement) {
            seriesGrid.classList.toggle('tools-card-grid--list', isList);
        }

        toggles.forEach((button) => {
            const active = button.getAttribute('data-overview-layout-toggle') === layout;
            button.classList.toggle('tools-overview-layout-toggle__button--active', active);

            if (button instanceof HTMLElement) {
                button.setAttribute('aria-pressed', active ? 'true' : 'false');
            }
        });

        localStorage.setItem(OVERVIEW_LAYOUT_STORAGE_KEY, layout);
    };

    toggles.forEach((button) => {
        button.addEventListener('click', () => {
            const layout = button.getAttribute('data-overview-layout-toggle');

            if (layout === 'grid' || layout === 'list') {
                setLayout(layout);
            }
        });
    });

    setLayout(initialLayout);
}

/**
 * @param {ParentNode} root
 */
function initOverviewSort(root) {
    const select = /** @type {HTMLSelectElement | null} */ (
        root.querySelector('[data-overview-sort]')
    );

    if (!select) {
        return;
    }

    select.addEventListener('change', () => {
        const value = select.value;

        if (
            value === 'date-desc'
            || value === 'date-asc'
            || value === 'name-asc'
            || value === 'name-desc'
        ) {
            localStorage.setItem(OVERVIEW_SORT_STORAGE_KEY, value);
        }

        const searchInput = root.querySelector('[data-overview-search]');

        if (searchInput instanceof HTMLInputElement) {
            searchInput.dispatchEvent(new Event('input'));
        }
    });
}

/**
 * @param {ParentNode} root
 */
function initTagSidebar(root) {
    const sidebar = root.querySelector('[data-tag-sidebar]');
    const toggles = root.querySelectorAll('[data-tag-sidebar-toggle]');

    if (!(sidebar instanceof HTMLElement) || toggles.length === 0) {
        return;
    }

    const stored = localStorage.getItem(TAG_SIDEBAR_STORAGE_KEY);
    const collapsed = stored === 'collapsed';

    setTagSidebarCollapsed(sidebar, toggles, collapsed);

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const nextCollapsed = sidebar.dataset.collapsed !== 'true';
            setTagSidebarCollapsed(sidebar, toggles, nextCollapsed);
            localStorage.setItem(TAG_SIDEBAR_STORAGE_KEY, nextCollapsed ? 'collapsed' : 'open');
        });
    });
}

/**
 * @param {ParentNode} root
 */
function initFilterSidebarTabs(root) {
    const tabRoot = root.querySelector('[data-filter-tab-root]');
    if (!(tabRoot instanceof HTMLElement)) {
        return;
    }

    const tabs = Array.from(tabRoot.querySelectorAll('[data-filter-tab]'));
    const panels = Array.from(tabRoot.querySelectorAll('[data-filter-tab-panel]'));
    if (tabs.length === 0 || panels.length === 0) {
        return;
    }

    const available = new Set(
        tabs
            .map((tab) => tab.getAttribute('data-filter-tab') ?? '')
            .filter((id) => id !== ''),
    );

    const stored = localStorage.getItem(FILTER_TAB_STORAGE_KEY);
    const initial =
        stored && available.has(stored)
            ? stored
            : (tabRoot.getAttribute('data-filter-tab-active') ?? tabs[0]?.getAttribute('data-filter-tab') ?? '');

    /**
     * @param {string} tabId
     */
    const setTab = (tabId) => {
        if (!available.has(tabId)) {
            return;
        }

        tabRoot.setAttribute('data-filter-tab-active', tabId);
        localStorage.setItem(FILTER_TAB_STORAGE_KEY, tabId);

        tabs.forEach((tab) => {
            const active = (tab.getAttribute('data-filter-tab') ?? '') === tabId;
            tab.classList.toggle('tools-filter-sidebar__tab--active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
            tab.setAttribute('tabindex', active ? '0' : '-1');
        });

        panels.forEach((panel) => {
            const active = (panel.getAttribute('data-filter-tab-panel') ?? '') === tabId;
            if (panel instanceof HTMLElement) {
                panel.hidden = !active;
            }
        });
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const tabId = tab.getAttribute('data-filter-tab') ?? '';
            if (tabId !== '') {
                setTab(tabId);
            }
        });

        tab.addEventListener('keydown', (event) => {
            if (!(event instanceof KeyboardEvent)) {
                return;
            }
            if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft' && event.key !== 'Home' && event.key !== 'End') {
                return;
            }

            event.preventDefault();
            const currentIndex = tabs.indexOf(tab);
            if (currentIndex < 0) {
                return;
            }

            let nextIndex = currentIndex;
            if (event.key === 'ArrowRight') {
                nextIndex = (currentIndex + 1) % tabs.length;
            } else if (event.key === 'ArrowLeft') {
                nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;
            } else if (event.key === 'Home') {
                nextIndex = 0;
            } else if (event.key === 'End') {
                nextIndex = tabs.length - 1;
            }

            const next = tabs[nextIndex];
            if (!(next instanceof HTMLElement)) {
                return;
            }
            const nextId = next.getAttribute('data-filter-tab') ?? '';
            setTab(nextId);
            next.focus();
        });
    });

    if (initial) {
        setTab(initial);
    }
}

/**
 * @param {ParentNode} root
 */
function initOverviewViewToggle(root) {
    const toggles = root.querySelectorAll('[data-overview-view-toggle]');
    const storiesPanel = root.querySelector('[data-overview-view-panel="stories"]');
    const seriesPanel = root.querySelector('[data-overview-view-panel="series"]');

    if (toggles.length === 0 || !(storiesPanel instanceof HTMLElement)) {
        return;
    }

    const layout = root.querySelector('.tools-overview-layout');
    const params = new URLSearchParams(window.location.search);
    const queryView = params.get('view');
    const stored = localStorage.getItem(OVERVIEW_VIEW_STORAGE_KEY);
    let initialView = 'stories';
    if (queryView === 'series' && seriesPanel instanceof HTMLElement) {
        initialView = 'series';
    } else if (queryView === 'stories') {
        initialView = 'stories';
    } else if (stored === 'series' && seriesPanel instanceof HTMLElement) {
        initialView = 'series';
    }

    /** @param {'stories' | 'series'} view */
    const setView = (view, { syncUrl = true } = {}) => {
        const isSeries = view === 'series' && seriesPanel instanceof HTMLElement;

        layout?.classList.toggle('tools-overview-layout--view-series', isSeries);

        storiesPanel.hidden = isSeries;
        if (seriesPanel instanceof HTMLElement) {
            seriesPanel.hidden = !isSeries;
        }

        toggles.forEach((button) => {
            const active = button.getAttribute('data-overview-view-toggle') === view;
            button.classList.toggle('tools-overview-view-toggle__button--active', active);

            if (button instanceof HTMLElement) {
                button.setAttribute('aria-selected', active ? 'true' : 'false');
            }
        });

        localStorage.setItem(OVERVIEW_VIEW_STORAGE_KEY, view);

        if (syncUrl) {
            const url = new URL(window.location.href);
            if (view === 'series') {
                url.searchParams.set('view', 'series');
            } else {
                url.searchParams.delete('view');
            }
            window.history.replaceState({}, '', url);
        }

        const searchInput = root.querySelector('[data-overview-search]');
        if (searchInput instanceof HTMLInputElement) {
            searchInput.dispatchEvent(new Event('input'));
        }
    };

    toggles.forEach((button) => {
        button.addEventListener('click', () => {
            const view = button.getAttribute('data-overview-view-toggle');

            if (view === 'series' || view === 'stories') {
                setView(view);
            }
        });
    });

    setView(initialView, { syncUrl: queryView === 'series' || queryView === 'stories' });
}

/**
 * @param {HTMLElement} sidebar
 * @param {NodeListOf<Element> | Element[]} toggles
 * @param {boolean} collapsed
 */
function setTagSidebarCollapsed(sidebar, toggles, collapsed) {
    sidebar.dataset.collapsed = collapsed ? 'true' : 'false';

    const layout = sidebar.closest('.tools-overview-layout');
    layout?.classList.toggle('tools-overview-layout--tags-collapsed', collapsed);

    Array.from(toggles).forEach((toggle) => {
        if (!(toggle instanceof HTMLElement)) {
            return;
        }
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    });
}

/**
 * @param {ParentNode} root
 */
function initTagSidebarSearch(root) {
    const sidebar = root.querySelector('[data-tag-sidebar]');
    if (!(sidebar instanceof HTMLElement)) {
        return;
    }

    const tagSearchInput = /** @type {HTMLInputElement | null} */ (
        sidebar.querySelector('[data-tag-sidebar-search]')
    );
    const tagButtons = sidebar.querySelectorAll('[data-overview-tag]');
    const emptyEl = sidebar.querySelector('[data-tag-sidebar-empty]');

    /** @param {string} value */
    const normalize = (value) => value.toLowerCase().trim();

    const applyTagSearch = () => {
        const query = normalize(tagSearchInput?.value ?? '');
        let visibleTags = 0;

        tagButtons.forEach((button) => {
            const tag = button.getAttribute('data-overview-tag') ?? '';

            if (tag === 'all') {
                button.hidden = false;
                return;
            }

            const label = normalize(
                button.querySelector('.tools-tag-sidebar__option-label')?.textContent ?? tag,
            );
            const show = query === '' || label.includes(query);

            button.hidden = !show;
            if (show) {
                visibleTags += 1;
            }
        });

        if (emptyEl instanceof HTMLElement) {
            emptyEl.hidden = query === '' || visibleTags > 0;
        }
    };

    tagSearchInput?.addEventListener('input', applyTagSearch);
    applyTagSearch();
}
