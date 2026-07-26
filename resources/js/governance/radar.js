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

    applyCompact(readRadarCompact());
    document.documentElement.removeAttribute('data-radar-compact-boot');

    toggle?.addEventListener('click', () => {
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
    const items = Array.from(root.querySelectorAll('[data-governance-radar-item]'));
    const topicOptions = topic
        ? Array.from(topic.querySelectorAll('option')).filter((option) => option.value !== '')
        : [];

    mountRadarCompact(root);

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

        items.forEach((item) => {
            const haystack = normalize(item.dataset.search);
            const itemTopics = splitTopics(item.dataset.topics).map(normalize);
            const itemType = normalize(item.dataset.type);
            const itemStacks = normalize(item.dataset.stack);
            const itemRegion = normalize(item.dataset.region);
            const matches = (!query || haystack.includes(query))
                && (!selectedTopic || itemTopics.includes(selectedTopic))
                && (selectedTypeValues.length === 0 || selectedTypeValues.includes(itemType))
                && (!selectedStack || itemStacks.includes(selectedStack))
                && (!selectedRegion || itemRegion === selectedRegion);

            item.hidden = !matches;
            if (matches) {
                visible += 1;
            }
        });

        if (count) {
            count.textContent = String(visible);
        }
        if (empty) {
            empty.hidden = visible !== 0;
        }
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
    if (!apiUrl || !button) {
        return;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    button.addEventListener('click', async () => {
        button.disabled = true;
        const label = button.querySelector('span');
        const previous = label?.textContent || '';
        if (label) {
            label.textContent = document.documentElement.lang === 'de' ? 'Aktualisiere…' : 'Refreshing…';
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
            if (errors) {
                const list = Array.isArray(payload.errors) ? payload.errors.slice(0, 5) : [];
                errors.textContent = list.join(' · ');
                errors.hidden = list.length === 0;
            }
            window.location.reload();
        } catch (error) {
            if (errors) {
                errors.hidden = false;
                errors.textContent = error.message || 'Feed sync failed.';
            }
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
