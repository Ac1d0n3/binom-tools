import { applyLocaleToDocument, getLocale, getShellLabel } from '../locale.js';
import {
    downloadAllStoriesOffline,
    downloadStoryOffline,
    fetchBulkManifest,
    fetchStoryManifest,
    formatBytes,
    getOfflineStory,
    isOfflineSupported,
    listOfflineStories,
    removeAllStoriesOffline,
    removeStoryOffline,
} from './offline.js';

const OFFLINE_CHANGED_EVENT = 'binom-tools:playbook-offline-changed';

/**
 * @param {HTMLElement} statusEl
 * @param {string} message
 * @param {'info' | 'success' | 'error' | 'progress'} [kind]
 */
function setStatus(statusEl, message, kind = 'info') {
    if (!statusEl) {
        return;
    }

    statusEl.hidden = !message;
    statusEl.textContent = message;
    statusEl.dataset.statusKind = kind;
}

/**
 * @param {HTMLButtonElement} button
 * @param {boolean} busy
 */
function setBusy(button, busy) {
    button.disabled = busy;
    button.setAttribute('aria-busy', busy ? 'true' : 'false');
}

function notifyOfflineChanged() {
    window.dispatchEvent(new CustomEvent(OFFLINE_CHANGED_EVENT));
}

/**
 * @param {HTMLElement} card
 * @param {boolean} saved
 */
function applyCardOfflineState(card, saved) {
    card.classList.toggle('is-offline-saved', saved);

    const badge = card.querySelector('[data-playbook-offline-badge]');
    if (badge instanceof HTMLElement) {
        badge.hidden = !saved;
    }

    const button = card.querySelector('[data-playbook-card-offline]');
    if (!(button instanceof HTMLButtonElement)) {
        return;
    }

    const saveIcon = button.querySelector('[data-offline-icon="save"]');
    const removeIcon = button.querySelector('[data-offline-icon="remove"]');
    if (saveIcon instanceof HTMLElement) {
        saveIcon.hidden = saved;
    }
    if (removeIcon instanceof HTMLElement) {
        removeIcon.hidden = !saved;
    }

    button.classList.toggle('is-saved', saved);
    const label = getShellLabel(saved ? 'playbooks.offline.remove' : 'playbooks.offline.save');
    button.setAttribute('aria-label', label);
    button.setAttribute('title', label);
}

/**
 * @returns {Promise<Set<string>>}
 */
async function syncAllCardOfflineStates() {
    const stories = await listOfflineStories();
    const slugs = new Set(stories.map((story) => story.slug));

    document.querySelectorAll('[data-playbook-index-card][data-playbook-slug]').forEach((card) => {
        if (!(card instanceof HTMLElement)) {
            return;
        }

        const slug = card.getAttribute('data-playbook-slug');
        applyCardOfflineState(card, slug !== null && slugs.has(slug));
    });

    return slugs;
}

/**
 * @param {HTMLElement} root
 */
export async function initPlaybookOfflineDetail(root) {
    const slug = root.getAttribute('data-playbook-slug');
    const controlGroups = [...root.querySelectorAll('[data-playbook-offline]')].filter(
        (el) => el instanceof HTMLElement,
    );

    if (!slug || controlGroups.length === 0) {
        return;
    }

    if (!isOfflineSupported()) {
        controlGroups.forEach((controls) => {
            controls.hidden = true;
        });
        return;
    }

    /**
     * @param {HTMLElement} controls
     */
    const wire = async (controls) => {
        const saveBtn = controls.querySelector('[data-playbook-offline-save]');
        const removeBtn = controls.querySelector('[data-playbook-offline-remove]');
        const statusEl = controls.querySelector('[data-playbook-offline-status]');

        if (!(saveBtn instanceof HTMLButtonElement) || !(removeBtn instanceof HTMLButtonElement)) {
            return;
        }

        const refresh = async () => {
            const meta = await getOfflineStory(slug);
            const saved = meta !== null;
            removeBtn.hidden = !saved;
            saveBtn.hidden = false;
            saveBtn.textContent = saved
                ? getShellLabel('playbooks.offline.update')
                : getShellLabel('playbooks.offline.save');

            if (saved) {
                setStatus(
                    statusEl,
                    getShellLabel('playbooks.offline.saved').replace('{size}', formatBytes(meta.bytesEstimate)),
                    'success',
                );
            } else {
                setStatus(statusEl, '', 'info');
            }
        };

        saveBtn.addEventListener('click', async () => {
            setBusy(saveBtn, true);
            setBusy(removeBtn, true);
            setStatus(statusEl, getShellLabel('playbooks.offline.preparing'), 'progress');

            try {
                const manifest = await fetchStoryManifest(slug);
                setStatus(
                    statusEl,
                    getShellLabel('playbooks.offline.downloading')
                        .replace('{size}', formatBytes(manifest.bytesEstimate)),
                    'progress',
                );

                await downloadStoryOffline(manifest, (done, total) => {
                    const pct = total > 0 ? Math.round((done / total) * 100) : 0;
                    setStatus(
                        statusEl,
                        getShellLabel('playbooks.offline.progress').replace('{pct}', String(pct)),
                        'progress',
                    );
                });

                await refresh();
                setStatus(statusEl, getShellLabel('playbooks.offline.done'), 'success');
                notifyOfflineChanged();
            } catch (error) {
                console.warn('Offline story download failed', error);
                const quota = error instanceof DOMException && error.name === 'QuotaExceededError';
                setStatus(
                    statusEl,
                    getShellLabel(quota ? 'playbooks.offline.quota' : 'playbooks.offline.error'),
                    'error',
                );
            } finally {
                setBusy(saveBtn, false);
                setBusy(removeBtn, false);
                await refresh();
            }
        });

        removeBtn.addEventListener('click', async () => {
            setBusy(saveBtn, true);
            setBusy(removeBtn, true);

            try {
                await removeStoryOffline(slug);
                await refresh();
                setStatus(statusEl, getShellLabel('playbooks.offline.removed'), 'info');
                notifyOfflineChanged();
            } catch (error) {
                console.warn('Offline story remove failed', error);
                setStatus(statusEl, getShellLabel('playbooks.offline.error'), 'error');
            } finally {
                setBusy(saveBtn, false);
                setBusy(removeBtn, false);
            }
        });

        await refresh();
    };

    await Promise.all(controlGroups.map((controls) => wire(controls)));
}

/**
 * Card offline clicks are handled by card-actions.js (capture delegation).
 * This only refreshes offline badges/icons after external changes.
 * @param {ParentNode} [root]
 */
function wireCardOfflineButtons(root = document) {
    root.querySelectorAll('[data-playbook-index-card][data-playbook-slug]').forEach((card) => {
        if (card instanceof HTMLElement) {
            card.dataset.offlineWired = '1';
        }
    });
}

/**
 * @param {ParentNode} [root]
 */
export async function initPlaybookOfflineIndex(root = document) {
    const controls = root.querySelector('[data-playbook-offline-index]');
    const hasCards = root.querySelector('[data-playbook-card-offline]') !== null;

    if (!(controls instanceof HTMLElement) && !hasCards) {
        return;
    }

    const offlineOk = isOfflineSupported();

    if (!offlineOk && controls instanceof HTMLElement) {
        controls.title = getShellLabel('playbooks.offline.unsupported');
        controls.querySelectorAll('button').forEach((button) => {
            if (button instanceof HTMLButtonElement) {
                button.disabled = true;
            }
        });
    }

    const saveAllBtn = controls?.querySelector('[data-playbook-offline-save-all]');
    const removeAllBtn = controls?.querySelector('[data-playbook-offline-remove-all]');
    const statusEl = document.querySelector('[data-playbook-offline-status]')
        ?? controls?.querySelector('[data-playbook-offline-status]');
    const sizeEl = controls?.querySelector('[data-playbook-offline-size]');

    const refreshToolbar = async () => {
        const slugs = await syncAllCardOfflineStates();

        if (removeAllBtn instanceof HTMLButtonElement) {
            removeAllBtn.hidden = slugs.size === 0;
            const removeLabel = getShellLabel('playbooks.offline.removeAll')
                .replace('{count}', String(slugs.size));
            removeAllBtn.setAttribute('aria-label', removeLabel);
            removeAllBtn.setAttribute('title', removeLabel);
        }
    };

    wireCardOfflineButtons(root);
    window.addEventListener(OFFLINE_CHANGED_EVENT, () => {
        void refreshToolbar();
    });

    if (offlineOk && saveAllBtn instanceof HTMLButtonElement && removeAllBtn instanceof HTMLButtonElement) {
        try {
            const bulk = await fetchBulkManifest();
            if (sizeEl instanceof HTMLElement) {
                sizeEl.textContent = getShellLabel('playbooks.offline.allSize')
                    .replace('{size}', formatBytes(bulk.bytesEstimate));
                sizeEl.hidden = false;
            }
            const sizeHint = getShellLabel('playbooks.offline.allSize')
                .replace('{size}', formatBytes(bulk.bytesEstimate));
            const saveLabel = `${getShellLabel('playbooks.offline.saveAll')} — ${sizeHint}`;
            saveAllBtn.setAttribute('title', saveLabel);
            saveAllBtn.setAttribute('aria-label', saveLabel);
        } catch {
            if (sizeEl instanceof HTMLElement) {
                sizeEl.hidden = true;
            }
        }

        saveAllBtn.addEventListener('click', async () => {
            let estimateLabel = '';
            try {
                const bulk = await fetchBulkManifest();
                estimateLabel = formatBytes(bulk.bytesEstimate);
            } catch {
                estimateLabel = '';
            }

            const confirmed = window.confirm(
                getShellLabel('playbooks.offline.confirmAll').replace('{size}', estimateLabel || '?'),
            );

            if (!confirmed) {
                return;
            }

            setBusy(saveAllBtn, true);
            setBusy(removeAllBtn, true);
            setStatus(statusEl, getShellLabel('playbooks.offline.preparing'), 'progress');

            try {
                await downloadAllStoriesOffline((_done, _total, slug) => {
                    setStatus(
                        statusEl,
                        slug
                            ? getShellLabel('playbooks.offline.progressStory').replace('{slug}', slug)
                            : getShellLabel('playbooks.offline.preparing'),
                        'progress',
                    );
                });
                setStatus(statusEl, getShellLabel('playbooks.offline.doneAll'), 'success');
                notifyOfflineChanged();
            } catch (error) {
                console.warn('Offline bulk download failed', error);
                const quota = error instanceof DOMException && error.name === 'QuotaExceededError';
                setStatus(
                    statusEl,
                    getShellLabel(quota ? 'playbooks.offline.quota' : 'playbooks.offline.error'),
                    'error',
                );
            } finally {
                setBusy(saveAllBtn, false);
                setBusy(removeAllBtn, false);
                await refreshToolbar();
            }
        });

        removeAllBtn.addEventListener('click', async () => {
            if (!window.confirm(getShellLabel('playbooks.offline.confirmRemoveAll'))) {
                return;
            }

            setBusy(saveAllBtn, true);
            setBusy(removeAllBtn, true);

            try {
                await removeAllStoriesOffline();
                setStatus(statusEl, getShellLabel('playbooks.offline.removedAll'), 'info');
                notifyOfflineChanged();
            } catch (error) {
                console.warn('Offline bulk remove failed', error);
                setStatus(statusEl, getShellLabel('playbooks.offline.error'), 'error');
            } finally {
                setBusy(saveAllBtn, false);
                setBusy(removeAllBtn, false);
                await refreshToolbar();
            }
        });
    }

    if (offlineOk) {
        await refreshToolbar();
    }
}

export function initOfflineBanner() {
    const banner = document.querySelector('[data-playbook-offline-banner]');

    if (!(banner instanceof HTMLElement)) {
        return;
    }

    const sync = () => {
        const offline = !navigator.onLine;
        banner.hidden = !offline;
        if (offline) {
            banner.textContent = getShellLabel('playbooks.offline.banner');
        }
    };

    window.addEventListener('online', sync);
    window.addEventListener('offline', sync);
    window.addEventListener('binom-tools:locale', () => {
        applyLocaleToDocument(getLocale());
        sync();
        void syncAllCardOfflineStates();
    });
    sync();
}
