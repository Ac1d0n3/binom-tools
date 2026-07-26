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
    const type = root.querySelector('[data-governance-radar-type]');
    const stack = root.querySelector('[data-governance-radar-stack]');
    const reset = root.querySelector('[data-governance-radar-reset]');
    const count = root.querySelector('[data-governance-radar-count]');
    const empty = root.querySelector('[data-governance-radar-empty]');
    const items = Array.from(root.querySelectorAll('[data-governance-radar-item]'));
    const topicOptions = topic
        ? Array.from(topic.querySelectorAll('option')).filter((option) => option.value !== '')
        : [];

    mountRadarCompact(root);

    const syncTopicOptions = () => {
        if (!topic) {
            return;
        }

        const selectedType = type?.value || '';
        const previousTopic = topic.value;
        let keepPrevious = previousTopic === '';

        topicOptions.forEach((option) => {
            const allowedTypes = String(option.dataset.topicTypes || '')
                .split('|')
                .map((entry) => entry.trim())
                .filter(Boolean);
            const visible = selectedType === '' || allowedTypes.includes(selectedType);
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
        const selectedType = normalize(type?.value);
        const selectedStack = normalize(stack?.value);
        let visible = 0;

        items.forEach((item) => {
            const haystack = normalize(item.dataset.search);
            const itemTopics = splitTopics(item.dataset.topics).map(normalize);
            const itemType = normalize(item.dataset.type);
            const itemStacks = normalize(item.dataset.stack);
            const matches = (!query || haystack.includes(query))
                && (!selectedTopic || itemTopics.includes(selectedTopic))
                && (!selectedType || itemType === selectedType)
                && (!selectedStack || itemStacks.includes(selectedStack));

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

    type?.addEventListener('change', () => {
        syncTopicOptions();
        apply();
    });

    [search, topic, stack].forEach((control) => {
        control?.addEventListener('input', apply);
        control?.addEventListener('change', apply);
    });

    reset?.addEventListener('click', () => {
        if (search) {
            search.value = '';
        }
        [topic, type, stack].forEach((control) => {
            if (control) {
                control.value = '';
            }
        });
        syncTopicOptions();
        apply();
    });

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
