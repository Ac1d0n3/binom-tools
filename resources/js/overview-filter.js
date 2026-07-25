/** Client-side search and tag filtering for overview index pages. */
import { getLocale, getShellLabel } from './locale';
import { syncSelectOptionAvailability } from './overview-filter.cascade.js';
import { compareStoryItemsForSort } from './overview-filter.sort.js';
import {
    clearAllPlaybookRead,
    hasAnyPlaybookRead,
    isPlaybookRead,
} from './playbooks/read-state';

const TAG_SIDEBAR_STORAGE_KEY = 'binom-tools-tag-sidebar';
const OVERVIEW_VIEW_STORAGE_KEY = 'binom-tools-overview-view';
const OVERVIEW_SORT_STORAGE_KEY = 'binom-tools-overview-sort';
const OVERVIEW_LAYOUT_STORAGE_KEY = 'binom-tools-overview-layout';
const OVERVIEW_HIDE_READ_STORAGE_KEY = 'binom-tools-overview-hide-read';
const FILTER_TAG_MODE_STORAGE_KEY = 'binom-tools-filter-tag-mode';

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
    initTagSidebarSearch(root);
    initOverviewViewToggle(root);
    initOverviewLayoutToggle(root);

    const searchInput = /** @type {HTMLInputElement | null} */ (
        root.querySelector('[data-overview-search]')
    );
    const productSelect = /** @type {HTMLSelectElement | null} */ (
        root.querySelector('[data-overview-product]')
    );
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

    /** @type {Set<string>} */
    const activeTags = new Set();

    /** @type {TagMatchMode} */
    let tagMatchMode = readTagMatchMode();

    /** @type {OverviewSortKey} */
    let activeSort = readOverviewSort(root);

    let hideRead = localStorage.getItem(OVERVIEW_HIDE_READ_STORAGE_KEY) === 'true';

    /** @param {string} value */
    const normalize = (value) => value.toLowerCase().trim();

    /** @returns {ToolsLocale} */
    const locale = () => getLocale();

    /** @returns {string} */
    const activeProduct = () => productSelect?.value || 'all';

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

    /** @param {number} vendorCount */
    const syncResultCount = (vendorCount) => {
        if (!(resultCountEl instanceof HTMLElement)) {
            return;
        }

        const count = String(vendorCount);
        resultCountEl.setAttribute('data-i18n-count', count);
        const template = getShellLabel('resources.visibleVendorCount', locale());
        resultCountEl.textContent = template.replace(/\{\{count\}\}/g, count);
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
            activeStack() !== 'all';
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
            const matchesProduct = productSelect === null || matchesProductFilter(products);
            const matchesModel = modelSelect === null || matchesModelFilter(models);
            const matchesResidency = residencySelect === null || matchesResidencyFilter(residencies);
            const matchesVendor = vendorSelect === null || matchesVendorFilter(vendor);
            const matchesStack = stackSelect === null || matchesStackFilter(stacks);
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

        syncResultCount(visibleVendors.size);
        syncOverviewReadControls(hideReadToggle, readResetButton, hideRead);
        syncFilterReset();
        syncStackBanner();
        applyWorkflowSections();
        sortStories();
    };

    const applySeries = () => {
        const query = normalize(searchInput?.value ?? '');
        let visible = 0;

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
            const matchesSearch = query === '' || text.includes(query);
            const matchesProduct = productSelect === null || matchesProductFilter(products);
            const matchesModel = modelSelect === null || matchesModelFilter(models);
            const matchesResidency = residencySelect === null || matchesResidencyFilter(residencies);
            const matchesVendor = vendorSelect === null || matchesVendorFilter(vendor);
            const matchesStack = stackSelect === null || matchesStackFilter(stacks);
            const show =
                matchesSearch &&
                matchesProduct &&
                matchesModel &&
                matchesResidency &&
                matchesVendor &&
                matchesStack;

            item.hidden = !show;
            if (show) visible += 1;
        });

        if (seriesEmptyEl instanceof HTMLElement) {
            seriesEmptyEl.hidden = visible > 0;
        }

        syncFilterReset();
        syncStackBanner();
        sortSeries();
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

    const readFiltersFromUrl = () => {
        const params = new URLSearchParams(window.location.search);
        const vendor = params.get('vendor');
        const product = params.get('product');
        const query = params.get('q') ?? params.get('search');

        if (vendor && selectHasOption(vendorSelect, vendor) && vendorSelect) {
            vendorSelect.value = vendor;
        }

        if (product && selectHasOption(productSelect, product) && productSelect) {
            productSelect.value = product;
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

        if (productSelect) {
            setOrDelete('product', productSelect.value);
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
    });

    initOverviewSort(root);

    if (tagModeRoot instanceof HTMLElement) {
        setTagMatchMode(tagModeRoot, tagMatchMode);
    }

    readFiltersFromUrl();
    apply();
}

/**
 * @param {Element | null} hideReadToggle
 * @param {Element | null} readResetButton
 * @param {boolean} hideRead
 */
function syncOverviewReadControls(hideReadToggle, readResetButton, hideRead) {
    if (hideReadToggle instanceof HTMLButtonElement) {
        hideReadToggle.setAttribute('aria-pressed', hideRead ? 'true' : 'false');
        hideReadToggle.classList.toggle('tools-overview-read-controls__button--active', hideRead);

        const icon = hideReadToggle.querySelector('i');
        const labelKey = hideRead ? 'overview.showRead' : 'overview.hideRead';
        const label = getShellLabel(labelKey);

        if (icon instanceof HTMLElement) {
            icon.classList.toggle('fa-eye', !hideRead);
            icon.classList.toggle('fa-eye-slash', hideRead);
        }

        hideReadToggle.setAttribute('aria-label', label);
        hideReadToggle.setAttribute('title', label);
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

    if (toggles.length === 0 || !(storiesGrid instanceof HTMLElement)) {
        return;
    }

    const stored = localStorage.getItem(OVERVIEW_LAYOUT_STORAGE_KEY);
    const initialLayout = stored === 'list' ? 'list' : 'grid';

    /** @param {OverviewLayoutMode} layout */
    const setLayout = (layout) => {
        const isList = layout === 'list';

        storiesGrid.classList.toggle('tools-card-grid--list', isList);

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
    const toggle = root.querySelector('[data-tag-sidebar-toggle]');

    if (!(sidebar instanceof HTMLElement) || !(toggle instanceof HTMLElement)) {
        return;
    }

    const stored = localStorage.getItem(TAG_SIDEBAR_STORAGE_KEY);
    const collapsed = stored === 'collapsed';

    setTagSidebarCollapsed(sidebar, toggle, collapsed);

    toggle.addEventListener('click', () => {
        const nextCollapsed = sidebar.dataset.collapsed !== 'true';
        setTagSidebarCollapsed(sidebar, toggle, nextCollapsed);
        localStorage.setItem(TAG_SIDEBAR_STORAGE_KEY, nextCollapsed ? 'collapsed' : 'open');
    });
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
    const stored = localStorage.getItem(OVERVIEW_VIEW_STORAGE_KEY);
    const initialView = stored === 'series' && seriesPanel instanceof HTMLElement ? 'series' : 'stories';

    /** @param {'stories' | 'series'} view */
    const setView = (view) => {
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

    setView(initialView);
}

/**
 * @param {HTMLElement} sidebar
 * @param {Element | null} toggle
 * @param {boolean} collapsed
 */
function setTagSidebarCollapsed(sidebar, toggle, collapsed) {
    sidebar.dataset.collapsed = collapsed ? 'true' : 'false';

    const layout = sidebar.closest('.tools-overview-layout');
    layout?.classList.toggle('tools-overview-layout--tags-collapsed', collapsed);

    if (toggle instanceof HTMLElement) {
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    }
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
