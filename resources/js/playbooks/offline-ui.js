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
 * @param {HTMLElement | null} statusEl
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

    syncSeriesOfflineStates(slugs);

    return slugs;
}

/**
 * @param {Set<string>} savedSlugs
 */
function syncSeriesOfflineStates(savedSlugs) {
    document.querySelectorAll('[data-playbook-series-offline]').forEach((button) => {
        if (!(button instanceof HTMLButtonElement)) {
            return;
        }

        const partSlugs = (button.dataset.seriesSlugs ?? '')
            .split(',')
            .map((slug) => slug.trim())
            .filter(Boolean);
        const saved = partSlugs.length > 0 && partSlugs.every((slug) => savedSlugs.has(slug));

        button.classList.toggle('is-saved', saved);

        const saveIcon = button.querySelector('[data-offline-icon="save"]');
        const removeIcon = button.querySelector('[data-offline-icon="remove"]');
        if (saveIcon instanceof HTMLElement) {
            saveIcon.hidden = saved;
        }
        if (removeIcon instanceof HTMLElement) {
            removeIcon.hidden = !saved;
        }

        const labelEl = button.querySelector('[data-offline-label]');
        const label = getShellLabel(saved ? 'playbooks.offline.removeSeriesShort' : 'playbooks.offline.saveSeriesShort');
        if (labelEl instanceof HTMLElement) {
            labelEl.textContent = label;
        }

        const aria = getShellLabel(saved ? 'playbooks.offline.removeSeries' : 'playbooks.offline.saveSeries');
        button.setAttribute('aria-label', aria);
        button.setAttribute('title', aria);
    });
}

/**
 * @param {{ title?: string, titleDe?: string, titleEn?: string, slug: string }} story
 */
function storyTitle(story) {
    const locale = getLocale();
    if (locale === 'de') {
        return story.titleDe || story.title || story.titleEn || story.slug;
    }

    return story.titleEn || story.title || story.titleDe || story.slug;
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
    const modal = root.querySelector('[data-playbook-offline-modal]')
        ?? document.querySelector('[data-playbook-offline-modal]');
    const hasCards = root.querySelector('[data-playbook-card-offline]') !== null;

    if (!(controls instanceof HTMLElement) && !hasCards && !(modal instanceof HTMLDialogElement)) {
        return;
    }

    const offlineOk = isOfflineSupported();
    const openBtn = controls?.querySelector('[data-playbook-offline-open]');
    const countEl = controls?.querySelector('[data-playbook-offline-count]');

    if (!offlineOk && controls instanceof HTMLElement) {
        controls.title = getShellLabel('playbooks.offline.unsupported');
        controls.querySelectorAll('button').forEach((button) => {
            if (button instanceof HTMLButtonElement) {
                button.disabled = true;
            }
        });
    }

    wireCardOfflineButtons(root);

    /**
     * @param {number} count
     */
    const updateToolbarCount = (count) => {
        if (!(openBtn instanceof HTMLButtonElement)) {
            return;
        }

        const title = getShellLabel('playbooks.offline.manageTitle');
        openBtn.setAttribute('aria-label', title);
        openBtn.setAttribute('title', title);

        if (countEl instanceof HTMLElement) {
            if (count > 0) {
                countEl.hidden = false;
                countEl.textContent = String(count);
            } else {
                countEl.hidden = true;
                countEl.textContent = '';
            }
        }
    };

    const refreshCardsAndToolbar = async () => {
        const slugs = await syncAllCardOfflineStates();
        updateToolbarCount(slugs.size);
        return slugs;
    };

    window.addEventListener(OFFLINE_CHANGED_EVENT, () => {
        void refreshCardsAndToolbar();
        if (modal instanceof HTMLDialogElement && modal.open) {
            void renderModalState();
        }
    });

    if (!(modal instanceof HTMLDialogElement) || !(openBtn instanceof HTMLButtonElement)) {
        if (offlineOk) {
            await refreshCardsAndToolbar();
        }
        return;
    }

    const closeBtn = modal.querySelector('[data-playbook-offline-close]');
    const summaryEl = modal.querySelector('[data-playbook-offline-summary]');
    const sizeHintEl = modal.querySelector('[data-playbook-offline-size-hint]');
    const downloadBtn = modal.querySelector('[data-playbook-offline-download-all]');
    const downloadLabel = modal.querySelector('[data-playbook-offline-download-label]');
    const progressWrap = modal.querySelector('[data-playbook-offline-progress]');
    const progressBar = modal.querySelector('[data-playbook-offline-progress-bar]');
    const progressLabel = modal.querySelector('[data-playbook-offline-progress-label]');
    const listEl = modal.querySelector('[data-playbook-offline-list]');
    const emptyEl = modal.querySelector('[data-playbook-offline-empty]');
    const removeAllBtn = modal.querySelector('[data-playbook-offline-remove-all]');
    const confirmWrap = modal.querySelector('[data-playbook-offline-confirm-remove]');
    const confirmYes = modal.querySelector('[data-playbook-offline-confirm-yes]');
    const confirmNo = modal.querySelector('[data-playbook-offline-confirm-no]');

    let busy = false;

    /**
     * @param {boolean} nextBusy
     */
    const setModalBusy = (nextBusy) => {
        busy = nextBusy;
        setBusy(openBtn, nextBusy);
        if (downloadBtn instanceof HTMLButtonElement) {
            setBusy(downloadBtn, nextBusy);
        }
        if (removeAllBtn instanceof HTMLButtonElement && !nextBusy) {
            // enabled state refreshed in renderModalState
        } else if (removeAllBtn instanceof HTMLButtonElement) {
            removeAllBtn.disabled = true;
        }
        modal.querySelectorAll('[data-playbook-offline-remove-one]').forEach((btn) => {
            if (btn instanceof HTMLButtonElement) {
                btn.disabled = nextBusy;
            }
        });
    };

    /**
     * @param {boolean} visible
     * @param {number} [pct]
     * @param {string} [message]
     */
    const setProgress = (visible, pct = 0, message = '') => {
        if (progressWrap instanceof HTMLElement) {
            progressWrap.hidden = !visible;
        }
        if (progressBar instanceof HTMLProgressElement) {
            progressBar.value = Math.max(0, Math.min(100, pct));
        }
        if (progressLabel instanceof HTMLElement) {
            progressLabel.textContent = message;
        }
    };

    const hideConfirm = () => {
        if (confirmWrap instanceof HTMLElement) {
            confirmWrap.hidden = true;
        }
        if (removeAllBtn instanceof HTMLButtonElement) {
            removeAllBtn.hidden = false;
        }
    };

    const showConfirm = () => {
        if (confirmWrap instanceof HTMLElement) {
            confirmWrap.hidden = false;
        }
        if (removeAllBtn instanceof HTMLButtonElement) {
            removeAllBtn.hidden = true;
        }
    };

    const loadSizeHint = async () => {
        if (!(sizeHintEl instanceof HTMLElement) || !(downloadBtn instanceof HTMLButtonElement)) {
            return;
        }

        try {
            const bulk = await fetchBulkManifest();
            const sizeHint = getShellLabel('playbooks.offline.allSize')
                .replace('{size}', formatBytes(bulk.bytesEstimate));
            sizeHintEl.textContent = sizeHint;
            sizeHintEl.hidden = false;
            downloadBtn.setAttribute('title', sizeHint);
        } catch {
            sizeHintEl.hidden = true;
        }
    };

    const renderModalState = async () => {
        const stories = await listOfflineStories();
        stories.sort((a, b) => storyTitle(a).localeCompare(storyTitle(b), getLocale()));

        const totalBytes = stories.reduce((sum, story) => sum + (story.bytesEstimate || 0), 0);

        if (summaryEl instanceof HTMLElement) {
            summaryEl.textContent = getShellLabel('playbooks.offline.manageSummary')
                .replace('{count}', String(stories.length))
                .replace('{size}', formatBytes(totalBytes));
        }

        if (downloadLabel instanceof HTMLElement) {
            downloadLabel.textContent = getShellLabel('playbooks.offline.downloadAllAction');
        }

        if (emptyEl instanceof HTMLElement) {
            emptyEl.hidden = stories.length > 0;
            emptyEl.textContent = getShellLabel('playbooks.offline.manageEmpty');
        }

        if (listEl instanceof HTMLElement) {
            listEl.replaceChildren();

            for (const story of stories) {
                const li = document.createElement('li');
                li.className = 'playbook-offline-modal__item';
                li.dataset.slug = story.slug;

                const meta = document.createElement('div');
                meta.className = 'playbook-offline-modal__item-meta';

                const title = document.createElement('span');
                title.className = 'playbook-offline-modal__item-title';
                title.textContent = storyTitle(story);

                const size = document.createElement('span');
                size.className = 'playbook-offline-modal__item-size';
                size.textContent = formatBytes(story.bytesEstimate);

                meta.append(title, size);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'tools-btn tools-btn--ghost tools-btn--compact playbook-offline-modal__item-remove';
                removeBtn.dataset.playbookOfflineRemoveOne = story.slug;
                removeBtn.setAttribute('aria-label', getShellLabel('playbooks.offline.remove'));
                removeBtn.title = getShellLabel('playbooks.offline.remove');
                removeBtn.disabled = busy;
                removeBtn.innerHTML = '<i class="fa-solid fa-trash-can" aria-hidden="true"></i>';

                li.append(meta, removeBtn);
                listEl.append(li);
            }
        }

        if (removeAllBtn instanceof HTMLButtonElement) {
            const label = getShellLabel('playbooks.offline.removeAll')
                .replace('{count}', String(stories.length));
            removeAllBtn.disabled = busy || stories.length === 0;
            removeAllBtn.setAttribute('aria-label', label);
            removeAllBtn.setAttribute('title', label);
            if (!busy) {
                removeAllBtn.hidden = false;
            }
        }

        updateToolbarCount(stories.length);
    };

    const openModal = async () => {
        if (!offlineOk) {
            window.alert(getShellLabel('playbooks.offline.unsupported'));
            return;
        }

        hideConfirm();
        setProgress(false);
        await renderModalState();
        modal.showModal();
        void loadSizeHint();
    };

    openBtn.addEventListener('click', () => {
        void openModal();
    });

    closeBtn?.addEventListener('click', () => {
        if (!busy) {
            modal.close();
        }
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal && !busy) {
            modal.close();
        }
    });

    modal.addEventListener('cancel', (event) => {
        if (busy) {
            event.preventDefault();
        }
    });

    if (listEl instanceof HTMLElement) {
        listEl.addEventListener('click', async (event) => {
            const target = event.target;
            if (!(target instanceof Element) || busy) {
                return;
            }

            const button = target.closest('[data-playbook-offline-remove-one]');
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            const slug = button.dataset.playbookOfflineRemoveOne ?? '';
            if (slug === '') {
                return;
            }

            setModalBusy(true);
            try {
                await removeStoryOffline(slug);
                notifyOfflineChanged();
                await renderModalState();
                setProgress(true, 100, getShellLabel('playbooks.offline.removed'));
            } catch (error) {
                console.warn('Offline story remove failed', error);
                setProgress(true, 0, getShellLabel('playbooks.offline.error'));
            } finally {
                setModalBusy(false);
                await renderModalState();
            }
        });
    }

    downloadBtn?.addEventListener('click', async () => {
        if (!(downloadBtn instanceof HTMLButtonElement) || busy) {
            return;
        }

        setModalBusy(true);
        hideConfirm();
        setProgress(true, 0, getShellLabel('playbooks.offline.preparing'));

        try {
            let lastListedStory = -1;
            await downloadAllStoriesOffline((done, total, slug) => {
                const pct = total > 0 ? Math.round((done / total) * 100) : 0;
                const message = slug
                    ? getShellLabel('playbooks.offline.progressStory').replace('{slug}', slug)
                    : getShellLabel('playbooks.offline.progress').replace('{pct}', String(pct));
                setProgress(true, pct, message);

                const storyIndex = Math.floor(done);
                if (storyIndex !== lastListedStory) {
                    lastListedStory = storyIndex;
                    void renderModalState();
                }
            });

            setProgress(true, 100, getShellLabel('playbooks.offline.doneAll'));
            notifyOfflineChanged();
            await renderModalState();
        } catch (error) {
            console.warn('Offline bulk download failed', error);
            const quota = error instanceof DOMException && error.name === 'QuotaExceededError';
            setProgress(
                true,
                0,
                getShellLabel(quota ? 'playbooks.offline.quota' : 'playbooks.offline.error'),
            );
        } finally {
            setModalBusy(false);
            await renderModalState();
        }
    });

    removeAllBtn?.addEventListener('click', () => {
        if (busy) {
            return;
        }
        showConfirm();
    });

    confirmNo?.addEventListener('click', () => {
        hideConfirm();
    });

    confirmYes?.addEventListener('click', async () => {
        if (busy) {
            return;
        }

        setModalBusy(true);
        hideConfirm();

        try {
            await removeAllStoriesOffline();
            setProgress(true, 100, getShellLabel('playbooks.offline.removedAll'));
            notifyOfflineChanged();
            await renderModalState();
        } catch (error) {
            console.warn('Offline bulk remove failed', error);
            setProgress(true, 0, getShellLabel('playbooks.offline.error'));
        } finally {
            setModalBusy(false);
            await renderModalState();
        }
    });

    window.addEventListener('binom-tools:locale', () => {
        if (modal.open) {
            void renderModalState();
            void loadSizeHint();
        }
    });

    if (offlineOk) {
        await refreshCardsAndToolbar();
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
