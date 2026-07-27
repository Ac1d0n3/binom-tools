/**
 * Story overview card actions: like + offline download.
 * Uses capture-phase delegation so clicks always reach the handlers.
 */

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

/**
 * @param {string} url
 * @param {RequestInit} [init]
 */
async function jsonFetch(url, init = {}) {
    const headers = new Headers(init.headers ?? {});
    headers.set('Accept', 'application/json');
    headers.set('X-Requested-With', 'XMLHttpRequest');

    if (init.method && init.method.toUpperCase() !== 'GET') {
        headers.set('X-CSRF-TOKEN', csrfToken());
    }

    const response = await fetch(url, {
        credentials: 'same-origin',
        ...init,
        headers,
    });

    if (!response.ok) {
        throw new Error(`Request failed: ${response.status}`);
    }

    return response.json();
}

/**
 * @param {HTMLElement} card
 * @param {{ views?: number, likes?: number, liked?: boolean }} data
 */
function renderCardStats(card, data) {
    const viewsEl = card.querySelector('[data-playbook-card-views]');
    const likesEl = card.querySelector('[data-playbook-card-likes]');
    const likeBtn = card.querySelector('[data-playbook-card-like]');
    const likeIcon = likeBtn?.querySelector('[data-like-icon]');

    if (viewsEl instanceof HTMLElement && typeof data.views === 'number') {
        viewsEl.textContent = String(data.views);
    }

    if (likesEl instanceof HTMLElement && typeof data.likes === 'number') {
        likesEl.textContent = String(data.likes);
    }

    if (likeBtn instanceof HTMLButtonElement && typeof data.liked === 'boolean') {
        likeBtn.setAttribute('aria-pressed', data.liked ? 'true' : 'false');
        likeBtn.classList.toggle('tools-card__story-like--active', data.liked);
        if (likeIcon instanceof HTMLElement) {
            likeIcon.classList.toggle('fa-solid', data.liked);
            likeIcon.classList.toggle('fa-regular', !data.liked);
        }
    }
}

/**
 * @param {HTMLButtonElement} button
 * @param {boolean} busy
 */
function setBusy(button, busy) {
    button.disabled = busy;
    button.setAttribute('aria-busy', busy ? 'true' : 'false');
}

async function handleCardLikeClick(button) {
    const card = button.closest('[data-playbook-index-card]');
    if (!(card instanceof HTMLElement)) {
        return;
    }

    const likeUrl = card.dataset.statsLikeUrl ?? '';
    if (likeUrl === '') {
        console.warn('Missing data-stats-like-url on story card');
        return;
    }

    setBusy(button, true);

    try {
        const data = await jsonFetch(likeUrl, { method: 'POST' });
        renderCardStats(card, data);
    } catch (error) {
        console.warn('Card like failed', error);
    } finally {
        setBusy(button, false);
    }
}

async function handleCardOfflineClick(button) {
    const card = button.closest('[data-playbook-index-card][data-playbook-slug]');
    if (!(card instanceof HTMLElement)) {
        return;
    }

    const slug = card.getAttribute('data-playbook-slug');
    if (!slug) {
        return;
    }

    setBusy(button, true);

    try {
        const {
            downloadStoryOffline,
            fetchStoryManifest,
            getOfflineStory,
            isOfflineSupported,
            removeStoryOffline,
        } = await import('./offline.js');

        if (!isOfflineSupported()) {
            window.alert(
                'Offline-Speichern braucht HTTPS oder localhost und einen Service Worker.',
            );
            return;
        }

        const existing = await getOfflineStory(slug);

        if (existing) {
            await removeStoryOffline(slug);
        } else {
            const manifest = await fetchStoryManifest(slug);
            await downloadStoryOffline(manifest);
        }

        window.dispatchEvent(new CustomEvent('binom-tools:playbook-offline-changed'));
    } catch (error) {
        console.warn('Card offline action failed', error);
        const quota = error instanceof DOMException && error.name === 'QuotaExceededError';
        window.alert(
            quota
                ? 'Nicht genug Speicherplatz. Speichere einzelne Stories statt alle.'
                : 'Offline-Speichern fehlgeschlagen. Bitte online erneut versuchen.',
        );
    } finally {
        setBusy(button, false);
    }
}

/**
 * @param {HTMLButtonElement} button
 */
async function handleSeriesOfflineClick(button) {
    const seriesId = button.dataset.seriesId ?? '';
    const slugs = (button.dataset.seriesSlugs ?? '')
        .split(',')
        .map((slug) => slug.trim())
        .filter(Boolean);

    if (seriesId === '' || slugs.length === 0) {
        return;
    }

    setBusy(button, true);

    try {
        const {
            downloadSeriesOffline,
            getOfflineStory,
            isOfflineSupported,
            removeSeriesOffline,
        } = await import('./offline.js');

        if (!isOfflineSupported()) {
            window.alert(
                'Offline-Speichern braucht HTTPS oder localhost und einen Service Worker.',
            );
            return;
        }

        const savedFlags = await Promise.all(slugs.map((slug) => getOfflineStory(slug)));
        const allSaved = savedFlags.every((meta) => meta !== null);

        if (allSaved) {
            await removeSeriesOffline(slugs);
        } else {
            await downloadSeriesOffline(seriesId);
        }

        window.dispatchEvent(new CustomEvent('binom-tools:playbook-offline-changed'));
    } catch (error) {
        console.warn('Series offline action failed', error);
        const quota = error instanceof DOMException && error.name === 'QuotaExceededError';
        window.alert(
            quota
                ? 'Nicht genug Speicherplatz. Speichere einzelne Stories statt alle.'
                : 'Offline-Speichern fehlgeschlagen. Bitte online erneut versuchen.',
        );
    } finally {
        setBusy(button, false);
    }
}

/**
 * Install capture-phase click handlers for overview cards.
 */
export function initPlaybookCardActions() {
    if (document.documentElement.dataset.cardActionsWired === '1') {
        return;
    }

    document.documentElement.dataset.cardActionsWired = '1';

    document.addEventListener(
        'click',
        (event) => {
            const target = event.target;
            if (!(target instanceof Element)) {
                return;
            }

            const likeBtn = target.closest('[data-playbook-card-like]');
            if (likeBtn instanceof HTMLButtonElement) {
                event.preventDefault();
                event.stopPropagation();
                void handleCardLikeClick(likeBtn);
                return;
            }

            const seriesOfflineBtn = target.closest('[data-playbook-series-offline]');
            if (seriesOfflineBtn instanceof HTMLButtonElement) {
                event.preventDefault();
                event.stopPropagation();
                void handleSeriesOfflineClick(seriesOfflineBtn);
                return;
            }

            const offlineBtn = target.closest('[data-playbook-card-offline]');
            if (offlineBtn instanceof HTMLButtonElement) {
                event.preventDefault();
                event.stopPropagation();
                void handleCardOfflineClick(offlineBtn);
            }
        },
        true,
    );
}
