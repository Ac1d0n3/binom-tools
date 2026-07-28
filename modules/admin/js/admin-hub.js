/**
 * Admin Hub client helpers — help panels, modals, locale/section tabs, link rows.
 */
import { bindSharedModal, openSharedModal, closeSharedModal } from '../../../resources/js/shared/modal.js';
import { initTablist } from '../../../resources/js/shared/tabs.js';

function initAdminHelp(root = document) {
    root.querySelectorAll('[data-admin-help-root]').forEach((panelRoot) => {
        const toggle = panelRoot.querySelector('[data-admin-help-toggle]');
        const panel = panelRoot.querySelector('[data-admin-help]');
        if (!toggle || !panel) {
            return;
        }

        const key = `binom-tools:admin-help:${panel.dataset.adminHelp || 'default'}`;
        let open = true;
        try {
            const stored = localStorage.getItem(key);
            if (stored === '0') {
                open = false;
            }
        } catch {
            // ignore
        }

        const sync = () => {
            panel.hidden = !open;
            toggle.setAttribute('aria-expanded', String(open));
            const show = toggle.querySelector('[data-admin-help-show]');
            const hide = toggle.querySelector('[data-admin-help-hide]');
            if (show) {
                show.hidden = open;
            }
            if (hide) {
                hide.hidden = !open;
            }
        };

        sync();
        toggle.addEventListener('click', () => {
            open = !open;
            try {
                localStorage.setItem(key, open ? '1' : '0');
            } catch {
                // ignore
            }
            sync();
        });
    });
}

function initAdminTabs(root = document) {
    root.querySelectorAll('[data-admin-tabs]').forEach((tabsRoot) => {
        if (tabsRoot.dataset.adminTabsBound === 'true') {
            return;
        }
        tabsRoot.dataset.adminTabsBound = 'true';

        initTablist(tabsRoot, {
            tabSelector: ':scope > .admin-hub__tablist > [role="tab"]',
            onChange: (tabId) => {
                const panelsRoot = tabsRoot.querySelector(':scope > .admin-hub__tab-panels');
                (panelsRoot || tabsRoot).querySelectorAll(':scope > [data-admin-tab-panel]').forEach((panel) => {
                    const match = panel.getAttribute('data-admin-tab-panel') === tabId
                        || panel.id === tabId;
                    panel.hidden = !match;
                });
            },
        });
    });
}

function initAdminModals(root = document) {
    root.querySelectorAll('dialog[data-admin-modal]').forEach((dialog) => {
        if (!(dialog instanceof HTMLDialogElement) || dialog.dataset.adminModalBound === 'true') {
            return;
        }
        dialog.dataset.adminModalBound = 'true';
        bindSharedModal(dialog);
    });

    root.querySelectorAll('[data-admin-open-modal]').forEach((trigger) => {
        if (trigger.dataset.adminOpenBound === 'true') {
            return;
        }
        trigger.dataset.adminOpenBound = 'true';
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            const id = trigger.getAttribute('data-admin-open-modal');
            const dialog = id ? document.getElementById(id) : null;
            if (!(dialog instanceof HTMLDialogElement)) {
                return;
            }

            const fill = trigger.getAttribute('data-admin-fill');
            if (fill) {
                try {
                    const payload = JSON.parse(fill);
                    applyAdminFill(dialog, payload);
                } catch {
                    // ignore bad payload
                }
            }

            const vendorId = trigger.getAttribute('data-admin-vendor-id');
            const vendorForm = dialog.querySelector('[data-admin-vendor-edit-form]');
            if (vendorId && vendorForm instanceof HTMLFormElement) {
                const template = vendorForm.getAttribute('data-action-template') || '';
                if (template) {
                    vendorForm.action = template.replace('__ID__', encodeURIComponent(vendorId));
                }
            }

            const glossaryId = trigger.getAttribute('data-admin-glossary-id');
            const glossaryForm = dialog.querySelector('[data-admin-glossary-edit-form]');
            if (glossaryId && glossaryForm instanceof HTMLFormElement) {
                const template = glossaryForm.getAttribute('data-action-template') || '';
                if (template) {
                    glossaryForm.action = template.replace('__ID__', encodeURIComponent(glossaryId));
                }
            }

            const sourceId = trigger.getAttribute('data-admin-source-id');
            const sourceForm = dialog.querySelector('[data-admin-source-edit-form]');
            if (sourceId && sourceForm instanceof HTMLFormElement) {
                const template = sourceForm.getAttribute('data-action-template') || '';
                if (template) {
                    sourceForm.action = template.replace('__ID__', encodeURIComponent(sourceId));
                }
            }

            const supplierId = trigger.getAttribute('data-admin-supplier-id');
            const supplierForm = dialog.querySelector('[data-admin-supplier-edit-form]');
            if (supplierId && supplierForm instanceof HTMLFormElement) {
                const template = supplierForm.getAttribute('data-action-template') || '';
                if (template) {
                    supplierForm.action = template.replace('__ID__', encodeURIComponent(supplierId));
                }
            }

            const itemId = trigger.getAttribute('data-admin-item-id');
            const itemForm = dialog.querySelector('[data-admin-item-edit-form]');
            if (itemId && itemForm instanceof HTMLFormElement) {
                const template = itemForm.getAttribute('data-action-template') || '';
                if (template) {
                    itemForm.action = template.replace('__ID__', encodeURIComponent(itemId));
                }
            }

            const title = trigger.getAttribute('data-admin-modal-title');
            if (title) {
                const titleEl = dialog.querySelector('.admin-hub__modal-header h2');
                if (titleEl) {
                    titleEl.textContent = title;
                }
            }

            // One admin modal at a time.
            document.querySelectorAll('dialog[data-admin-modal]').forEach((openDialog) => {
                if (openDialog instanceof HTMLDialogElement && openDialog !== dialog && openDialog.open) {
                    closeSharedModal(openDialog);
                }
            });

            openSharedModal(dialog);
        });
    });
}

/**
 * @param {HTMLElement} root
 * @param {Record<string, string>} payload
 */
function applyAdminFill(root, payload) {
    Object.entries(payload).forEach(([name, value]) => {
        const field = root.querySelector(`[name="${CSS.escape(name)}"]`);
        if (field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement || field instanceof HTMLSelectElement) {
            field.value = value ?? '';
        }
    });
}

function initAdminLinkRows(root = document) {
    root.querySelectorAll('[data-admin-link-list]').forEach((list) => {
        if (list.dataset.adminLinkBound === 'true') {
            return;
        }
        list.dataset.adminLinkBound = 'true';
        const group = list.getAttribute('data-admin-link-list') || 'help';
        const addBtn = list.parentElement?.querySelector(`[data-admin-link-add="${group}"]`);

        const renumber = () => {
            list.querySelectorAll('[data-admin-link-row]').forEach((row, index) => {
                row.querySelectorAll('[data-admin-link-field]').forEach((field) => {
                    const key = field.getAttribute('data-admin-link-field');
                    if (!key || !(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement)) {
                        return;
                    }
                    field.name = `links[${group}][${index}][${key}]`;
                });
            });
        };

        addBtn?.addEventListener('click', (event) => {
            event.preventDefault();
            const template = list.querySelector('[data-admin-link-row]');
            if (!(template instanceof HTMLElement)) {
                return;
            }
            const clone = template.cloneNode(true);
            if (!(clone instanceof HTMLElement)) {
                return;
            }
            clone.querySelectorAll('input, textarea').forEach((el) => {
                if (el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement) {
                    el.value = '';
                }
            });
            clone.querySelectorAll('[data-admin-tabs]').forEach((tabsRoot) => {
                if (tabsRoot instanceof HTMLElement) {
                    delete tabsRoot.dataset.adminTabsBound;
                }
            });
            list.appendChild(clone);
            renumber();
            initAdminTabs(clone);
        });

        list.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }
            const remove = target.closest('[data-admin-link-remove]');
            if (!remove) {
                return;
            }
            event.preventDefault();
            const row = remove.closest('[data-admin-link-row]');
            if (row && list.querySelectorAll('[data-admin-link-row]').length > 1) {
                row.remove();
                renumber();
            } else if (row) {
                row.querySelectorAll('input, textarea').forEach((el) => {
                    if (el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement) {
                        el.value = '';
                    }
                });
            }
        });
    });
}

/**
 * @param {string} vendorId
 * @returns {Array<Record<string, unknown>>}
 */
function productsForVendor(vendorId) {
    /** @type {Array<Record<string, unknown>>} */
    const products = [];
    const seen = new Set();
    document.querySelectorAll(`[data-admin-edit-vendor][data-admin-vendor-id="${CSS.escape(vendorId)}"][data-admin-edit-product]`).forEach((btn) => {
        try {
            const parsed = JSON.parse(btn.getAttribute('data-admin-edit-product') || '{}');
            const id = String(parsed.id || '');
            if (!id || seen.has(id)) {
                return;
            }
            seen.add(id);
            products.push(/** @type {Record<string, unknown>} */ (parsed));
        } catch {
            // ignore
        }
    });
    return products;
}

/**
 * Resolve full product payload from list-row edit buttons.
 * @param {string} productId
 * @returns {Record<string, unknown>|null}
 */
function findProductPayload(productId) {
    if (!productId) {
        return null;
    }
    const buttons = document.querySelectorAll('[data-admin-edit-product]');
    for (const btn of buttons) {
        try {
            const parsed = JSON.parse(btn.getAttribute('data-admin-edit-product') || '{}');
            if (parsed && typeof parsed === 'object' && String(parsed.id || '') === productId) {
                return /** @type {Record<string, unknown>} */ (parsed);
            }
        } catch {
            // ignore
        }
    }
    return null;
}

/**
 * @param {HTMLElement} root
 * @param {Record<string, unknown>} product
 */
function fillProductFields(root, product) {
    const set = (name, value) => {
        const field = root.querySelector(`[name="${CSS.escape(name)}"]`);
        if (field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement || field instanceof HTMLSelectElement) {
            field.value = String(value ?? '');
        }
    };

    set('id', product.id ?? '');
    set('vendor', product.vendor ?? '');
    set('family', product.family ?? '');
    set('brandColor', product.brandColor ?? '');
    set('logo', product.logo ?? '');
    set('label_de', product.label?.de ?? product.label?.en ?? '');
    set('label_en', product.label?.en ?? product.label?.de ?? '');
    set('purpose_de', product.purpose?.de ?? '');
    set('purpose_en', product.purpose?.en ?? '');
    set('models', Array.isArray(product.models) ? product.models.join(', ') : '');
    set('residency', Array.isArray(product.residency) ? product.residency.join(', ') : '');

    const groups = ['help', 'governance', 'learning', 'certifications', 'compliance'];
    groups.forEach((group) => {
        const list = root.querySelector(`[data-admin-link-list="${group}"]`);
        if (!(list instanceof HTMLElement)) {
            return;
        }
        const links = Array.isArray(product[group]) ? product[group] : [];
        const template = list.querySelector('[data-admin-link-row]');
        if (!(template instanceof HTMLElement)) {
            return;
        }
        list.innerHTML = '';
        const rows = links.length > 0 ? links : [{}];
        rows.forEach((link, index) => {
            const row = template.cloneNode(true);
            if (!(row instanceof HTMLElement)) {
                return;
            }
            const map = {
                href: link.href ?? '',
                label_de: link.label?.de ?? '',
                label_en: link.label?.en ?? '',
                description_de: link.description?.de ?? '',
                description_en: link.description?.en ?? '',
                id: link.id ?? '',
            };
            row.querySelectorAll('[data-admin-link-field]').forEach((field) => {
                const key = field.getAttribute('data-admin-link-field');
                if (!key || !(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement)) {
                    return;
                }
                field.name = `links[${group}][${index}][${key}]`;
                field.value = map[key] ?? '';
            });
            list.appendChild(row);
        });
        // Re-bind add/remove after regenerating rows
        delete list.dataset.adminLinkBound;
    });
    initAdminLinkRows(root);
    initAdminTabs(root);
}

/**
 * @param {HTMLButtonElement|HTMLElement} trigger
 */
function openVendorWorkspace(trigger) {
    const dialog = document.getElementById('admin-vendor-edit-modal');
    const form = dialog?.querySelector('[data-admin-vendor-workspace]');
    if (!(dialog instanceof HTMLDialogElement) || !(form instanceof HTMLFormElement)) {
        return;
    }

    const vendorId = trigger.getAttribute('data-admin-vendor-id') || '';
    const preferredProductId = trigger.getAttribute('data-admin-product-id') || '';
    form.dataset.vendorId = vendorId;

    const fill = trigger.getAttribute('data-admin-fill');
    if (fill) {
        try {
            applyAdminFill(form, JSON.parse(fill));
        } catch {
            // ignore
        }
    }

    const title = trigger.getAttribute('data-admin-modal-title');
    if (title) {
        const titleEl = dialog.querySelector('.admin-hub__modal-header h2');
        if (titleEl) {
            titleEl.textContent = title;
        }
    }

    const products = productsForVendor(vendorId);
    const switcher = form.querySelector('[data-admin-product-switcher]');
    const empty = form.querySelector('[data-admin-vendor-products-empty]');
    const fields = form.querySelector('[data-admin-vendor-product-fields]');

    if (switcher instanceof HTMLSelectElement) {
        switcher.innerHTML = '';
        products.forEach((product) => {
            const opt = document.createElement('option');
            opt.value = String(product.id || '');
            const label = product.label && typeof product.label === 'object'
                ? (product.label.en || product.label.de || product.id)
                : product.id;
            opt.textContent = String(label || product.id || 'Product');
            switcher.appendChild(opt);
        });
        if (preferredProductId && products.some((p) => String(p.id) === preferredProductId)) {
            switcher.value = preferredProductId;
        }
    }

    const hasProducts = products.length > 0;
    if (empty instanceof HTMLElement) {
        empty.hidden = hasProducts;
    }
    if (fields instanceof HTMLElement) {
        fields.hidden = !hasProducts;
    }
    if (switcher instanceof HTMLSelectElement) {
        switcher.hidden = !hasProducts;
        const switcherField = switcher.closest('.admin-hub__field');
        if (switcherField instanceof HTMLElement) {
            switcherField.hidden = !hasProducts;
        }
    }

    if (hasProducts) {
        const selectedId = switcher instanceof HTMLSelectElement ? switcher.value : preferredProductId;
        const selected = products.find((p) => String(p.id) === selectedId) || products[0];
        if (selected) {
            fillProductFields(form, selected);
        }
    }

    document.querySelectorAll('dialog[data-admin-modal]').forEach((openDialog) => {
        if (openDialog instanceof HTMLDialogElement && openDialog !== dialog && openDialog.open) {
            closeSharedModal(openDialog);
        }
    });
    openSharedModal(dialog);
}

function initVendorWorkspace(root = document) {
    const dialog = document.getElementById('admin-vendor-edit-modal');
    const form = dialog?.querySelector('[data-admin-vendor-workspace]');
    if (!(dialog instanceof HTMLDialogElement) || !(form instanceof HTMLFormElement)) {
        return;
    }
    if (form.dataset.adminWorkspaceBound === 'true') {
        return;
    }
    form.dataset.adminWorkspaceBound = 'true';

    const switcher = form.querySelector('[data-admin-product-switcher]');
    if (switcher instanceof HTMLSelectElement) {
        switcher.addEventListener('change', () => {
            const product = findProductPayload(switcher.value);
            if (product) {
                fillProductFields(form, product);
            }
        });
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const vendorId = form.dataset.vendorId || '';
        const token = form.querySelector('input[name="_token"]');
        const tokenValue = token instanceof HTMLInputElement ? token.value : '';

        try {
            if (vendorId) {
                const vendorTemplate = form.getAttribute('data-vendor-action-template') || '';
                const vendorUrl = vendorTemplate.replace('__ID__', encodeURIComponent(vendorId));
                const vendorBody = new FormData();
                vendorBody.append('_token', tokenValue);
                vendorBody.append('_method', 'PUT');
                const nameDe = form.querySelector('[name="name_de"]');
                const nameEn = form.querySelector('[name="name_en"]');
                vendorBody.append('name_de', nameDe instanceof HTMLInputElement ? nameDe.value : '');
                vendorBody.append('name_en', nameEn instanceof HTMLInputElement ? nameEn.value : '');
                await fetch(vendorUrl, {
                    method: 'POST',
                    body: vendorBody,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
            }

            const fields = form.querySelector('[data-admin-vendor-product-fields]');
            const productIdInput = form.querySelector('[name="id"]');
            const productId = productIdInput instanceof HTMLInputElement ? productIdInput.value.trim() : '';
            if (fields instanceof HTMLElement && !fields.hidden && productId) {
                const productTemplate = form.getAttribute('data-product-action-template') || '';
                const productUrl = productTemplate.replace('__ID__', encodeURIComponent(productId));
                const productBody = new FormData(form);
                productBody.set('_method', 'PUT');
                productBody.delete('name_de');
                productBody.delete('name_en');
                await fetch(productUrl, {
                    method: 'POST',
                    body: productBody,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
            }

            window.location.reload();
        } catch {
            // keep modal open on failure
        }
    });

    root.querySelectorAll('[data-admin-edit-vendor]').forEach((btn) => {
        if (btn.dataset.adminVendorEditBound === 'true') {
            return;
        }
        btn.dataset.adminVendorEditBound = 'true';
        btn.addEventListener('click', (event) => {
            event.preventDefault();
            openVendorWorkspace(btn);
        });
    });
}

function initExpandToggles(root = document) {
    root.querySelectorAll('[data-admin-expand-toggle], [data-admin-vendor-toggle]').forEach((btn) => {
        if (btn.dataset.adminExpandBound === 'true') {
            return;
        }
        btn.dataset.adminExpandBound = 'true';
        btn.addEventListener('click', () => {
            const controls = btn.getAttribute('aria-controls');
            const panel = controls ? document.getElementById(controls) : null;
            if (!(panel instanceof HTMLElement)) {
                return;
            }
            const open = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', String(!open));
            panel.hidden = open;
        });
    });
}

function initAdminFilter(root = document) {
    root.querySelectorAll('[data-overview-filter-root]').forEach((filterRoot) => {
        if (filterRoot.dataset.adminFilterBound === 'true') {
            return;
        }
        filterRoot.dataset.adminFilterBound = 'true';
        const search = filterRoot.querySelector('[data-overview-search]');
        const items = filterRoot.querySelectorAll('[data-overview-item]');
        const empty = filterRoot.querySelector('[data-overview-empty]');
        if (!(search instanceof HTMLInputElement) || items.length === 0) {
            return;
        }

        const apply = () => {
            const q = search.value.trim().toLowerCase();
            let visible = 0;
            items.forEach((item) => {
                const text = (item.getAttribute('data-search-text') || item.textContent || '').toLowerCase();
                const show = q === '' || text.includes(q);
                item.hidden = !show;
                if (show) {
                    visible += 1;
                }
            });
            if (empty instanceof HTMLElement) {
                empty.hidden = visible > 0;
            }
        };

        search.addEventListener('input', apply);
        apply();
    });
}

function initAdminUploadAuto(root = document) {
    root.querySelectorAll('[data-admin-upload-auto]').forEach((form) => {
        if (!(form instanceof HTMLFormElement) || form.dataset.adminUploadBound === 'true') {
            return;
        }
        form.dataset.adminUploadBound = 'true';
        const input = form.querySelector('[data-admin-upload-input]');
        if (!(input instanceof HTMLInputElement)) {
            return;
        }
        input.addEventListener('change', () => {
            if (input.files?.length) {
                form.requestSubmit();
            }
        });
    });
}

function initAdminImagesRail(root = document) {
    root.querySelectorAll('[data-admin-images-rail]').forEach((editor) => {
        if (!(editor instanceof HTMLElement) || editor.dataset.adminImagesBound === 'true') {
            return;
        }
        editor.dataset.adminImagesBound = 'true';
        const toggle = editor.querySelector('[data-admin-images-toggle]');
        const showLabel = toggle?.querySelector('[data-admin-images-toggle-show]');
        const hideLabel = toggle?.querySelector('[data-admin-images-toggle-hide]');
        const key = 'binom-tools:admin-story-images-open';
        let open = true;
        try {
            open = localStorage.getItem(key) !== '0';
        } catch {
            open = true;
        }

        const sync = () => {
            editor.classList.toggle('is-images-collapsed', !open);
            toggle?.setAttribute('aria-expanded', String(open));
            if (showLabel instanceof HTMLElement) {
                showLabel.hidden = open;
            }
            if (hideLabel instanceof HTMLElement) {
                hideLabel.hidden = !open;
            }
        };

        sync();
        toggle?.addEventListener('click', () => {
            open = !open;
            try {
                localStorage.setItem(key, open ? '1' : '0');
            } catch {
                // ignore
            }
            sync();
        });
    });
}

async function copyAdminText(value) {
    const text = String(value || '').trim();
    if (!text) {
        return;
    }
    try {
        await navigator.clipboard.writeText(text);
    } catch {
        const area = document.createElement('textarea');
        area.value = text;
        area.setAttribute('readonly', '');
        area.style.position = 'fixed';
        area.style.left = '-9999px';
        document.body.append(area);
        area.select();
        document.execCommand('copy');
        area.remove();
    }
}

function initAdminCopy(root = document) {
    root.querySelectorAll('[data-admin-copy]').forEach((el) => {
        if (!(el instanceof HTMLElement) || el.dataset.adminCopyBound === 'true') {
            return;
        }
        el.dataset.adminCopyBound = 'true';
        el.addEventListener('click', async (event) => {
            event.preventDefault();
            const value = el.getAttribute('data-admin-copy') || el.textContent || '';
            await copyAdminText(value);
            const previous = el.textContent;
            el.textContent = 'Copied';
            window.setTimeout(() => {
                el.textContent = previous || value;
            }, 1000);
        });
    });
}

function initAdminStoryDraft(root = document) {
    root.querySelectorAll('[data-admin-story-draft]').forEach((bar) => {
        if (bar.dataset.adminStoryDraftBound === 'true') {
            return;
        }
        bar.dataset.adminStoryDraftBound = 'true';

        const seriesWrap = bar.querySelector('[data-admin-draft-series-wrap]');
        const sync = () => {
            const selected = bar.querySelector('[data-admin-draft-template]:checked');
            const isSeries = selected?.value === 'series';
            if (seriesWrap) {
                seriesWrap.hidden = !isSeries;
            }
        };

        bar.querySelectorAll('[data-admin-draft-template]').forEach((input) => {
            input.addEventListener('change', sync);
        });
        sync();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminHelp();
    initAdminTabs();
    initAdminModals();
    initAdminLinkRows();
    initVendorWorkspace();
    initExpandToggles();
    initAdminFilter();
    initAdminUploadAuto();
    initAdminImagesRail();
    initAdminCopy();
    initAdminStoryDraft();
});
