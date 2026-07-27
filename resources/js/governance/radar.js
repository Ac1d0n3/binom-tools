import {
    clearAllRadarRead,
    hasAnyRadarRead,
    isRadarItemRead,
    readRadarHideRead,
    toggleRadarItemRead,
    writeRadarHideRead,
} from './radar-read-state.js';

function normalize(value) {
    return String(value || '').toLowerCase().trim();
}

function splitTopics(value) {
    return String(value || '')
        .split('||')
        .map((topic) => topic.trim())
        .filter(Boolean);
}

const RADAR_COMPACT_STORAGE_KEY = 'binom-tools-governance-radar-compact';

function readRadarCompact() {
    try {
        return localStorage.getItem(RADAR_COMPACT_STORAGE_KEY) === 'true';
    } catch (error) {
        return false;
    }
}

function writeRadarCompact(enabled) {
    try {
        localStorage.setItem(RADAR_COMPACT_STORAGE_KEY, enabled ? 'true' : 'false');
    } catch (error) {
        // Ignore storage failures (private mode / blocked storage).
    }
}

function mountRadarCompact(root) {
    const toggle = root.querySelector('[data-governance-radar-compact-toggle]');
    const label = toggle?.querySelector('[data-compact-label]');
    const icon = toggle?.querySelector('[data-compact-icon]');
    const phoneMq = window.matchMedia('(max-width: 768px)');

    const applyCompact = (enabled) => {
        root.classList.toggle('is-compact', enabled);
        if (toggle) {
            toggle.setAttribute('aria-pressed', enabled ? 'true' : 'false');
        }
        if (label) {
            label.dataset.textDe = enabled ? 'Erweitert' : 'Kompakt';
            label.dataset.textEn = enabled ? 'Expand' : 'Compact';
            const lang = document.documentElement.lang === 'de' ? 'de' : 'en';
            label.textContent = lang === 'de' ? label.dataset.textDe : label.dataset.textEn;
        }
        if (icon) {
            icon.classList.toggle('fa-compress', !enabled);
            icon.classList.toggle('fa-expand', enabled);
        }
    };

    const syncPhone = () => {
        if (phoneMq.matches) {
            // Phone: always compact, no expand/collapse control.
            applyCompact(true);
            toggle?.setAttribute('hidden', '');
            return;
        }
        toggle?.removeAttribute('hidden');
        applyCompact(readRadarCompact());
    };

    syncPhone();
    document.documentElement.removeAttribute('data-radar-compact-boot');

    if (typeof phoneMq.addEventListener === 'function') {
        phoneMq.addEventListener('change', syncPhone);
    } else if (typeof phoneMq.addListener === 'function') {
        phoneMq.addListener(syncPhone);
    }

    toggle?.addEventListener('click', () => {
        if (phoneMq.matches) {
            return;
        }
        const next = !root.classList.contains('is-compact');
        writeRadarCompact(next);
        applyCompact(next);
    });
}

function mountRadar(root) {
    const search = root.querySelector('[data-governance-radar-search]');
    const topic = root.querySelector('[data-governance-radar-topic]');
    const typeMulti = root.querySelector('[data-governance-radar-type-multi]');
    const typeToggle = root.querySelector('[data-governance-radar-type-toggle]');
    const typePanel = root.querySelector('[data-governance-radar-type-panel]');
    const typeLabel = root.querySelector('[data-governance-radar-type-label]');
    const typeOptions = Array.from(root.querySelectorAll('[data-governance-radar-type-option]'));
    const stack = root.querySelector('[data-governance-radar-stack]');
    const region = root.querySelector('[data-governance-radar-region]');
    const reset = root.querySelector('[data-governance-radar-reset]');
    const count = root.querySelector('[data-governance-radar-count]');
    const empty = root.querySelector('[data-governance-radar-empty]');
    const unreadEmpty = root.querySelector('[data-governance-radar-unread-empty]');
    const hideReadToggle = root.querySelector('[data-governance-radar-hide-read]');
    const readResetButton = root.querySelector('[data-governance-radar-read-reset]');
    const items = Array.from(root.querySelectorAll('[data-governance-radar-item]'));
    const topicOptions = topic
        ? Array.from(topic.querySelectorAll('option')).filter((option) => option.value !== '')
        : [];

    let hideRead = readRadarHideRead();

    mountRadarCompact(root);

    const lang = () => (document.documentElement.lang === 'de' ? 'de' : 'en');

    const syncMarkReadButton = (button, read) => {
        if (!(button instanceof HTMLButtonElement)) {
            return;
        }
        button.setAttribute('aria-pressed', read ? 'true' : 'false');
        button.classList.toggle('is-read', read);
        const icon = button.querySelector('[data-mark-read-icon]');
        const label = button.querySelector('[data-mark-read-label]');
        if (icon) {
            icon.classList.add('fa-solid');
            icon.classList.toggle('fa-eye', !read);
            icon.classList.toggle('fa-eye-slash', read);
        }
        const title = lang() === 'de'
            ? (read ? 'Als ungelesen markieren' : 'Als gelesen markieren')
            : (read ? 'Mark as unread' : 'Mark as read');
        button.setAttribute('title', title);
        button.setAttribute('aria-label', title);
        if (label) {
            label.textContent = title;
        }
    };

    const syncItemReadUi = (item) => {
        const itemId = item.dataset.itemId || '';
        const read = isRadarItemRead(itemId);
        item.dataset.read = read ? '1' : '0';
        item.classList.toggle('is-read', read);
        const button = item.querySelector('[data-governance-radar-mark-read]');
        syncMarkReadButton(button, read);
    };

    const syncReadControls = () => {
        if (hideReadToggle instanceof HTMLButtonElement) {
            hideReadToggle.setAttribute('aria-pressed', hideRead ? 'true' : 'false');
            hideReadToggle.classList.toggle('tools-overview-read-controls__button--active', hideRead);
            const icon = hideReadToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !hideRead);
                icon.classList.toggle('fa-eye-slash', hideRead);
            }
            const label = hideRead
                ? (lang() === 'de' ? 'Gelesene anzeigen' : 'Show read items')
                : (lang() === 'de' ? 'Gelesene ausblenden' : 'Hide read items');
            hideReadToggle.setAttribute('title', label);
            hideReadToggle.setAttribute('aria-label', label);
            const sr = hideReadToggle.querySelector('.sr-only');
            if (sr) {
                sr.textContent = label;
            }
        }
        if (readResetButton instanceof HTMLButtonElement) {
            const canReset = hasAnyRadarRead();
            readResetButton.disabled = !canReset;
            readResetButton.setAttribute('aria-disabled', canReset ? 'false' : 'true');
            const resetLabel = lang() === 'de' ? 'Gelesen-Status zurücksetzen' : 'Reset read status';
            readResetButton.setAttribute('title', resetLabel);
            readResetButton.setAttribute('aria-label', resetLabel);
            const sr = readResetButton.querySelector('.sr-only');
            if (sr) {
                sr.textContent = resetLabel;
            }
        }
    };

    const selectedTypes = () => typeOptions
        .filter((option) => option.checked)
        .map((option) => option.value);

    const syncTypeLabel = () => {
        if (!typeLabel) {
            return;
        }
        const selected = selectedTypes();
        const lang = document.documentElement.lang === 'de' ? 'de' : 'en';
        if (selected.length === 0) {
            typeLabel.dataset.textDe = 'Alle Typen';
            typeLabel.dataset.textEn = 'All types';
            typeLabel.textContent = lang === 'de' ? 'Alle Typen' : 'All types';
            return;
        }
        if (selected.length === 1) {
            const option = typeOptions.find((entry) => entry.value === selected[0]);
            const label = lang === 'de'
                ? (option?.dataset.textDe || selected[0])
                : (option?.dataset.textEn || selected[0]);
            typeLabel.textContent = label;
            return;
        }
        typeLabel.dataset.textDe = `${selected.length} Typen`;
        typeLabel.dataset.textEn = `${selected.length} types`;
        typeLabel.textContent = lang === 'de' ? `${selected.length} Typen` : `${selected.length} types`;
    };

    const positionTypePanel = () => {
        if (!typePanel || !typeToggle || typePanel.hidden) {
            return;
        }
        const rect = typeToggle.getBoundingClientRect();
        const panelWidth = Math.max(rect.width, 224);
        const left = Math.min(
            Math.max(8, rect.left),
            Math.max(8, window.innerWidth - panelWidth - 8),
        );
        typePanel.style.top = `${Math.round(rect.bottom + 6)}px`;
        typePanel.style.left = `${Math.round(left)}px`;
        typePanel.style.width = `${Math.round(panelWidth)}px`;
    };

    const setTypePanelOpen = (open) => {
        typeMulti?.classList.toggle('is-open', open);
        if (typeToggle) {
            typeToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
        if (!typePanel) {
            return;
        }
        if (open) {
            if (typePanel.parentElement !== document.body) {
                document.body.appendChild(typePanel);
            }
            typePanel.hidden = false;
            positionTypePanel();
            return;
        }
        typePanel.hidden = true;
        typePanel.style.top = '';
        typePanel.style.left = '';
        typePanel.style.width = '';
    };

    const syncTopicOptions = () => {
        if (!topic) {
            return;
        }

        const selected = selectedTypes();
        const previousTopic = topic.value;
        let keepPrevious = previousTopic === '';

        topicOptions.forEach((option) => {
            const allowedTypes = String(option.dataset.topicTypes || '')
                .split('|')
                .map((entry) => entry.trim())
                .filter(Boolean);
            const visible = selected.length === 0
                || allowedTypes.some((typeValue) => selected.includes(typeValue));
            option.hidden = !visible;
            option.disabled = !visible;
            if (visible && option.value === previousTopic) {
                keepPrevious = true;
            }
        });

        if (!keepPrevious) {
            topic.value = '';
        }
    };

    const apply = () => {
        const query = normalize(search?.value);
        const selectedTopic = normalize(topic?.value);
        const selectedTypeValues = selectedTypes().map(normalize);
        const selectedStack = normalize(stack?.value);
        const selectedRegion = normalize(region?.value);
        let visible = 0;
        let wouldShowButRead = 0;

        items.forEach((item) => {
            syncItemReadUi(item);
            const haystack = normalize(item.dataset.search);
            const itemTopics = splitTopics(item.dataset.topics).map(normalize);
            const itemType = normalize(item.dataset.type);
            const itemStacks = normalize(item.dataset.stack);
            const itemRegion = normalize(item.dataset.region);
            const itemId = item.dataset.itemId || '';
            const read = isRadarItemRead(itemId);
            const matchesFilters = (!query || haystack.includes(query))
                && (!selectedTopic || itemTopics.includes(selectedTopic))
                && (selectedTypeValues.length === 0 || selectedTypeValues.includes(itemType))
                && (!selectedStack || itemStacks.includes(selectedStack))
                && (!selectedRegion || itemRegion === selectedRegion);

            if (matchesFilters && hideRead && read) {
                wouldShowButRead += 1;
            }

            const matches = matchesFilters && (!hideRead || !read);
            item.hidden = !matches;
            if (matches) {
                visible += 1;
            }
        });

        if (count) {
            count.textContent = String(visible);
        }
        const showUnreadEmpty = hideRead && visible === 0 && wouldShowButRead > 0;
        if (empty) {
            empty.hidden = visible > 0 || showUnreadEmpty;
        }
        if (unreadEmpty) {
            unreadEmpty.hidden = !showUnreadEmpty;
        }
        syncReadControls();
    };

    typeToggle?.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        setTypePanelOpen(!(typeMulti?.classList.contains('is-open')));
    });

    typeOptions.forEach((option) => {
        option.addEventListener('change', () => {
            syncTypeLabel();
            syncTopicOptions();
            apply();
        });
    });

    document.addEventListener('click', (event) => {
        if (!typePanel || typePanel.hidden) {
            return;
        }
        const target = event.target;
        if (typeToggle?.contains(target) || typePanel.contains(target)) {
            return;
        }
        setTypePanelOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setTypePanelOpen(false);
        }
    });

    window.addEventListener('resize', () => {
        if (!typePanel?.hidden) {
            positionTypePanel();
        }
    });

    window.addEventListener('scroll', () => {
        if (!typePanel?.hidden) {
            positionTypePanel();
        }
    }, true);

    [search, topic, stack, region].forEach((control) => {
        control?.addEventListener('input', apply);
        control?.addEventListener('change', apply);
    });

    reset?.addEventListener('click', () => {
        if (search) {
            search.value = '';
        }
        [topic, stack, region].forEach((control) => {
            if (control) {
                control.value = '';
            }
        });
        typeOptions.forEach((option) => {
            option.checked = false;
        });
        syncTypeLabel();
        syncTopicOptions();
        apply();
        setTypePanelOpen(false);
    });

    root.addEventListener('click', (event) => {
        const button = event.target.closest('[data-governance-radar-mark-read]');
        if (!button || !root.contains(button)) {
            return;
        }
        const itemId = button.dataset.itemId || button.closest('[data-governance-radar-item]')?.dataset.itemId || '';
        if (!itemId) {
            return;
        }
        toggleRadarItemRead(itemId);
        apply();
    });

    if (hideReadToggle instanceof HTMLButtonElement) {
        hideReadToggle.addEventListener('click', () => {
            hideRead = !hideRead;
            writeRadarHideRead(hideRead);
            apply();
        });
    }

    if (readResetButton instanceof HTMLButtonElement) {
        readResetButton.addEventListener('click', () => {
            const message = lang() === 'de'
                ? 'Alle Gelesen-Markierungen im Radar löschen? Das kann nicht rückgängig gemacht werden.'
                : 'Clear all read markers for radar items? This cannot be undone.';
            if (!window.confirm(message)) {
                return;
            }
            clearAllRadarRead();
            apply();
        });
    }

    window.addEventListener('binom-tools:radar-read', apply);
    window.addEventListener('binom-tools:radar-read-reset', apply);

    syncTypeLabel();
    syncTopicOptions();
    apply();
}

function sourceTemplate(source) {
    const article = document.createElement('article');
    article.className = 'governance-radar__custom-source';
    article.dataset.sourceId = source.id;

    const body = document.createElement('div');
    const title = document.createElement('h3');
    title.textContent = source.name || 'Eigene RSS-Quelle';
    const url = document.createElement('p');
    url.textContent = source.feedUrl || '';
    body.append(title, url);

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'governance-hub__button';
    button.dataset.governanceRadarDeleteSource = source.id;
    button.innerHTML = '<i class="fa-solid fa-trash-can" aria-hidden="true"></i><span data-text-de="Löschen" data-text-en="Delete">Löschen</span>';

    article.append(body, button);

    return article;
}

function mountSourceAdmin(root) {
    const apiUrl = root.dataset.radarSourcesApiUrl;
    const form = root.querySelector('[data-governance-radar-source-form]');
    const list = root.querySelector('[data-governance-radar-custom-source-list]');
    const count = root.querySelector('[data-governance-radar-source-count]');
    const status = root.querySelector('[data-governance-radar-source-status]');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    if (!apiUrl || !form || !list) {
        return;
    }

    const setStatus = (message, isError = false) => {
        if (!status) {
            return;
        }
        status.textContent = message;
        status.dataset.state = isError ? 'error' : 'ok';
    };

    const render = (sources = []) => {
        list.replaceChildren(...sources.map(sourceTemplate));
        if (count) {
            count.textContent = String(sources.length);
        }
        if (sources.length === 0) {
            const empty = document.createElement('p');
            empty.className = 'governance-radar__empty';
            empty.textContent = 'Noch keine eigenen RSS-Quellen gespeichert.';
            list.append(empty);
        }
    };

    const request = async (url, options = {}) => {
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                ...(csrf ? {'X-CSRF-TOKEN': csrf} : {}),
                ...(options.headers || {}),
            },
            ...options,
        });

        const payload = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(payload.message || 'Quelle konnte nicht gespeichert werden.');
        }

        return payload;
    };

    request(apiUrl)
        .then((payload) => render(payload.sources || []))
        .catch((error) => setStatus(error.message, true));

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const data = new FormData(form);
        const topics = String(data.get('topics') || '')
            .split(/[,;\n]+/)
            .map((entry) => entry.trim())
            .filter(Boolean);

        try {
            setStatus('Speichere Quelle...');
            const payload = await request(apiUrl, {
                method: 'POST',
                body: JSON.stringify({
                    name: data.get('name'),
                    feedUrl: data.get('feedUrl'),
                    type: data.get('type'),
                    topics,
                    region: 'Global',
                    language: 'de',
                    cadence: 'rss',
                    active: true,
                }),
            });
            render(payload.sources || []);
            form.reset();
            setStatus('Quelle gespeichert.');
        } catch (error) {
            setStatus(error.message, true);
        }
    });

    list.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-governance-radar-delete-source]');
        if (!button) {
            return;
        }

        const id = button.dataset.governanceRadarDeleteSource;
        if (!id) {
            return;
        }

        try {
            setStatus('Lösche Quelle...');
            const payload = await request(`${apiUrl}/${id}`, {method: 'DELETE'});
            render(payload.sources || []);
            setStatus('Quelle gelöscht.');
        } catch (error) {
            setStatus(error.message, true);
        }
    });
}

document.querySelectorAll('[data-governance-radar]').forEach(mountRadar);
document.querySelectorAll('[data-governance-radar]').forEach(mountSourceAdmin);
document.querySelectorAll('[data-governance-radar]').forEach(mountFeedSync);
document.querySelectorAll('[data-governance-radar]').forEach(mountEnrichAdmin);

function mountFeedSync(root) {
    const apiUrl = root.dataset.radarFeedSyncApiUrl;
    const button = root.querySelector('[data-governance-radar-feed-sync]');
    const syncedAt = root.querySelector('[data-governance-radar-feed-synced-at]');
    const errors = root.querySelector('[data-governance-radar-feed-errors]');
    const errorSummary = root.querySelector('[data-governance-radar-feed-error-summary]');
    const errorList = root.querySelector('[data-governance-radar-feed-error-list]');
    if (!apiUrl || !button) {
        return;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const lang = () => (document.documentElement.lang === 'de' ? 'de' : 'en');

    /**
     * @param {string[]} list
     */
    const renderErrors = (list) => {
        if (!(errors instanceof HTMLElement)) {
            return;
        }
        if (list.length === 0) {
            errors.hidden = true;
            if (errorList) {
                errorList.innerHTML = '';
            }
            return;
        }

        errors.hidden = false;
        if (errorSummary instanceof HTMLElement) {
            errorSummary.textContent = lang() === 'de'
                ? `${list.length} Quellen mit Sync-Problemen`
                : `${list.length} sources with sync issues`;
            errorSummary.setAttribute('data-text-de', `${list.length} Quellen mit Sync-Problemen`);
            errorSummary.setAttribute('data-text-en', `${list.length} sources with sync issues`);
        }
        if (errorList instanceof HTMLElement) {
            errorList.innerHTML = list
                .slice(0, 8)
                .map((item) => `<li>${item.replace(/[<>&]/g, (ch) => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;' }[ch] || ch))}</li>`)
                .join('');
        }
    };

    button.addEventListener('click', async () => {
        button.disabled = true;
        const label = button.querySelector('span');
        const previous = label?.textContent || '';
        if (label) {
            label.textContent = lang() === 'de' ? 'Aktualisiere…' : 'Refreshing…';
        }
        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: '{}',
            });
            const payload = await response.json().catch(() => ({}));
            if (!response.ok) {
                throw new Error(payload.message || 'Feed sync failed.');
            }
            if (syncedAt && payload.syncedAt) {
                syncedAt.setAttribute('datetime', payload.syncedAt);
                syncedAt.textContent = payload.syncedAt;
            }
            const list = Array.isArray(payload.errors) ? payload.errors : [];
            renderErrors(list);
            window.location.reload();
        } catch (error) {
            renderErrors([error.message || 'Feed sync failed.']);
        } finally {
            button.disabled = false;
            if (label) {
                label.textContent = previous;
            }
        }
    });
}

function mountEnrichAdmin(root) {
    const apiBase = root.dataset.radarOverlaysApiUrl;
    const dialog = root.querySelector('[data-governance-radar-enrich-dialog]');
    const form = root.querySelector('[data-governance-radar-enrich-form]');
    if (!apiBase || !dialog || !form) {
        return;
    }

    const itemIdInput = form.querySelector('[data-enrich-item-id]');
    const titleInput = form.querySelector('[data-enrich-title-de]');
    const summaryInput = form.querySelector('[data-enrich-summary-de]');
    const actionInput = form.querySelector('[data-enrich-action-de]');
    const noteInput = form.querySelector('[data-enrich-note]');
    const impactInput = form.querySelector('[data-enrich-impact]');
    const originalEl = form.querySelector('[data-governance-radar-enrich-original]');
    const statusEl = form.querySelector('[data-governance-radar-enrich-status]');
    const saveBtn = form.querySelector('[data-governance-radar-enrich-save]');
    const resetBtn = form.querySelector('[data-governance-radar-enrich-reset]');
    let activeButton = null;

    const setStatus = (message, isError = false) => {
        if (!statusEl) {
            return;
        }
        statusEl.hidden = !message;
        statusEl.textContent = message || '';
        statusEl.dataset.state = isError ? 'error' : 'ok';
    };

    const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const request = async (url, options = {}) => {
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                ...(options.headers || {}),
            },
            ...options,
        });
        const payload = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(payload.message || `Request failed (${response.status})`);
        }
        return payload;
    };

    const openForButton = (button) => {
        activeButton = button;
        const itemId = button.dataset.itemId || '';
        if (itemIdInput) {
            itemIdInput.value = itemId;
        }
        if (titleInput) {
            titleInput.value = button.dataset.overlayTitleDe || '';
        }
        if (summaryInput) {
            summaryInput.value = button.dataset.overlaySummaryDe || '';
        }
        if (actionInput) {
            actionInput.value = button.dataset.overlayActionDe || '';
        }
        if (noteInput) {
            noteInput.value = button.dataset.overlayNote || '';
        }
        if (impactInput) {
            impactInput.value = button.dataset.overlayImpact || '';
        }
        if (originalEl) {
            originalEl.textContent = button.dataset.originalTitle || '';
        }
        setStatus('');
        if (typeof dialog.showModal === 'function') {
            dialog.showModal();
        }
    };

    root.addEventListener('click', (event) => {
        const button = event.target.closest('[data-governance-radar-enrich]');
        if (!button || !root.contains(button)) {
            return;
        }
        openForButton(button);
    });

    saveBtn?.addEventListener('click', async () => {
        const itemId = itemIdInput?.value || '';
        if (!itemId) {
            return;
        }
        try {
            setStatus('Speichere Overlay...');
            const payload = await request(`${apiBase}/${encodeURIComponent(itemId)}/overlay`, {
                method: 'PUT',
                body: JSON.stringify({
                    titleDe: titleInput?.value || '',
                    summaryDe: summaryInput?.value || '',
                    recommendedActionDe: actionInput?.value || '',
                    editorialNote: noteInput?.value || '',
                    impact: impactInput?.value || '',
                }),
            });
            const overlay = payload.overlay || {};
            if (activeButton) {
                activeButton.dataset.overlayTitleDe = overlay.titleDe || '';
                activeButton.dataset.overlaySummaryDe = overlay.summaryDe || '';
                activeButton.dataset.overlayActionDe = overlay.recommendedActionDe || '';
                activeButton.dataset.overlayNote = overlay.editorialNote || '';
                activeButton.dataset.overlayImpact = overlay.impact || '';
            }
            setStatus('Gespeichert. Seite neu laden für Anzeige.');
        } catch (error) {
            setStatus(error.message, true);
        }
    });

    resetBtn?.addEventListener('click', async () => {
        const itemId = itemIdInput?.value || '';
        if (!itemId) {
            return;
        }
        try {
            setStatus('Lösche Overlay...');
            await request(`${apiBase}/${encodeURIComponent(itemId)}/overlay`, {method: 'DELETE'});
            if (titleInput) titleInput.value = '';
            if (summaryInput) summaryInput.value = '';
            if (actionInput) actionInput.value = '';
            if (noteInput) noteInput.value = '';
            if (impactInput) impactInput.value = '';
            if (activeButton) {
                activeButton.dataset.overlayTitleDe = '';
                activeButton.dataset.overlaySummaryDe = '';
                activeButton.dataset.overlayActionDe = '';
                activeButton.dataset.overlayNote = '';
                activeButton.dataset.overlayImpact = '';
            }
            setStatus('Overlay gelöscht. Seite neu laden für Anzeige.');
        } catch (error) {
            setStatus(error.message, true);
        }
    });
}
