import { getAppBasePath, getShellLabel, withAppBasePath } from '../locale.js';

const DB_NAME = 'binom-playbooks-offline';
const DB_VERSION = 1;
const STORE = 'stories';
const SHELL_CACHE = 'binom-playbooks-shell-v1';
const STORY_PREFIX = 'binom-playbooks-story-';
const META_CACHE = 'binom-playbooks-meta-v1';

/** @typedef {{ slug: string, title: string, titleDe?: string, titleEn?: string, modifiedAt: string, bytesEstimate: number, savedAt: string, urlCount: number }} OfflineStoryMeta */

/** @type {Promise<IDBDatabase> | null} */
let dbPromise = null;

/**
 * @returns {Promise<IDBDatabase>}
 */
function openDb() {
    if (dbPromise) {
        return dbPromise;
    }

    dbPromise = new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onerror = () => reject(request.error ?? new Error('IndexedDB open failed'));
        request.onsuccess = () => resolve(request.result);
        request.onupgradeneeded = () => {
            const db = request.result;
            if (!db.objectStoreNames.contains(STORE)) {
                db.createObjectStore(STORE, { keyPath: 'slug' });
            }
        };
    });

    return dbPromise;
}

/**
 * @returns {Promise<OfflineStoryMeta[]>}
 */
export async function listOfflineStories() {
    const db = await openDb();

    return new Promise((resolve, reject) => {
        const tx = db.transaction(STORE, 'readonly');
        const req = tx.objectStore(STORE).getAll();
        req.onsuccess = () => resolve(/** @type {OfflineStoryMeta[]} */ (req.result ?? []));
        req.onerror = () => reject(req.error ?? new Error('Failed to list offline stories'));
    });
}

/**
 * @param {string} slug
 * @returns {Promise<OfflineStoryMeta | null>}
 */
export async function getOfflineStory(slug) {
    const db = await openDb();

    return new Promise((resolve, reject) => {
        const tx = db.transaction(STORE, 'readonly');
        const req = tx.objectStore(STORE).get(slug);
        req.onsuccess = () => resolve(req.result ?? null);
        req.onerror = () => reject(req.error ?? new Error('Failed to read offline story'));
    });
}

/**
 * @param {OfflineStoryMeta} meta
 */
async function putOfflineStory(meta) {
    const db = await openDb();

    return new Promise((resolve, reject) => {
        const tx = db.transaction(STORE, 'readwrite');
        tx.objectStore(STORE).put(meta);
        tx.oncomplete = () => resolve();
        tx.onerror = () => reject(tx.error ?? new Error('Failed to save offline meta'));
    });
}

/**
 * @param {string} slug
 */
async function deleteOfflineMeta(slug) {
    const db = await openDb();

    return new Promise((resolve, reject) => {
        const tx = db.transaction(STORE, 'readwrite');
        tx.objectStore(STORE).delete(slug);
        tx.oncomplete = () => resolve();
        tx.onerror = () => reject(tx.error ?? new Error('Failed to delete offline meta'));
    });
}

/**
 * @returns {string}
 */
export function serviceWorkerUrl() {
    return withAppBasePath('/sw-playbooks.js');
}

/**
 * @returns {Promise<ServiceWorkerRegistration | null>}
 */
export async function ensurePlaybookServiceWorker() {
    if (!('serviceWorker' in navigator)) {
        return null;
    }

    const base = getAppBasePath();
    const scope = base === '' ? '/' : `${base}/`;
    const existing = await navigator.serviceWorker.getRegistration(scope);

    if (existing) {
        return existing;
    }

    return navigator.serviceWorker.register(serviceWorkerUrl(), { scope });
}

/**
 * @param {string} url
 * @returns {string}
 */
function normalizeCacheUrl(url) {
    try {
        if (url.startsWith('http://') || url.startsWith('https://')) {
            return new URL(url).href;
        }

        if (url.startsWith('/')) {
            return new URL(url, window.location.origin).href;
        }

        return new URL(withAppBasePath(`/${url.replace(/^\.\//, '')}`), window.location.origin).href;
    } catch {
        return url;
    }
}

/**
 * @param {string[]} urls
 * @param {(done: number, total: number, url: string) => void} [onProgress]
 * @returns {Promise<{ cached: number, failed: string[] }>}
 */
async function cacheUrls(cacheName, urls, onProgress) {
    const cache = await caches.open(cacheName);
    const unique = [...new Set(urls.map(normalizeCacheUrl).filter(Boolean))];
    let cached = 0;
    /** @type {string[]} */
    const failed = [];

    for (const url of unique) {
        try {
            const response = await fetch(url, { credentials: 'same-origin', cache: 'no-cache' });
            if (!response.ok) {
                failed.push(url);
            } else {
                await cache.put(url, response.clone());
                cached += 1;
            }
        } catch {
            failed.push(url);
        }

        onProgress?.(cached + failed.length, unique.length, url);
    }

    return { cached, failed };
}

/**
 * @param {{
 *   slug: string,
 *   title: string,
 *   titleDe?: string,
 *   titleEn?: string,
 *   modifiedAt: string,
 *   bytesEstimate: number,
 *   urls: string[],
 * }} manifest
 * @param {(done: number, total: number) => void} [onProgress]
 */
export async function downloadStoryOffline(manifest, onProgress) {
    await ensurePlaybookServiceWorker();

    const shellUrls = manifest.urls.filter((url) => {
        const path = (() => {
            try {
                return new URL(normalizeCacheUrl(url)).pathname;
            } catch {
                return url;
            }
        })();

        return (
            path.includes('/build/')
            || path.endsWith('/sw-playbooks.js')
            || path.endsWith('/playbooks-offline-fallback.html')
            || path.endsWith('favicon.ico')
            || path.endsWith('favicon.svg')
            || path.endsWith('apple-touch-icon.png')
        );
    });

    const storyUrls = manifest.urls;

    await cacheUrls(SHELL_CACHE, shellUrls.length > 0 ? shellUrls : storyUrls.slice(0, 0), (done, total) => {
        onProgress?.(done, total + storyUrls.length);
    });

    const result = await cacheUrls(STORY_PREFIX + manifest.slug, storyUrls, (done, total) => {
        onProgress?.(shellUrls.length + done, shellUrls.length + total);
    });

    if (result.cached === 0) {
        throw new Error(getShellLabel('playbooks.offline.error'));
    }

    /** @type {OfflineStoryMeta} */
    const meta = {
        slug: manifest.slug,
        title: manifest.title,
        titleDe: manifest.titleDe,
        titleEn: manifest.titleEn,
        modifiedAt: manifest.modifiedAt,
        bytesEstimate: manifest.bytesEstimate,
        savedAt: new Date().toISOString(),
        urlCount: result.cached,
    };

    await putOfflineStory(meta);
    await caches.open(META_CACHE).then((cache) =>
        cache.add(normalizeCacheUrl(withAppBasePath('/playbooks-offline-fallback.html'))).catch(() => undefined),
    );

    return meta;
}

/**
 * @param {(done: number, total: number, slug?: string) => void} [onProgress]
 */
export async function downloadAllStoriesOffline(onProgress) {
    await ensurePlaybookServiceWorker();

    /** @type {{ bytesEstimate: number, shellUrls: string[], indexUrls: string[], stories: Array<{ slug: string, title: string, titleDe?: string, titleEn?: string, modifiedAt: string, bytesEstimate: number, urls: string[] }> }} */
    const bulk = await fetchBulkManifest();

    await cacheUrls(SHELL_CACHE, [...(bulk.shellUrls ?? []), ...(bulk.indexUrls ?? [])], (done, total) => {
        onProgress?.(done, total + (bulk.stories?.length ?? 0));
    });

    let index = 0;
    for (const story of bulk.stories ?? []) {
        index += 1;
        await downloadStoryOffline(story, (done, total) => {
            onProgress?.(index - 1 + done / Math.max(total, 1), (bulk.stories?.length ?? 0), story.slug);
        });
    }

    return bulk;
}

/**
 * @param {string} seriesId
 * @param {(done: number, total: number, slug?: string) => void} [onProgress]
 */
export async function downloadSeriesOffline(seriesId, onProgress) {
    await ensurePlaybookServiceWorker();

    const bulk = await fetchSeriesManifest(seriesId);

    await cacheUrls(SHELL_CACHE, [...(bulk.shellUrls ?? []), ...(bulk.indexUrls ?? [])], (done, total) => {
        onProgress?.(done, total + (bulk.stories?.length ?? 0));
    });

    let index = 0;
    for (const story of bulk.stories ?? []) {
        index += 1;
        await downloadStoryOffline(story, (done, total) => {
            onProgress?.(index - 1 + done / Math.max(total, 1), (bulk.stories?.length ?? 0), story.slug);
        });
    }

    return bulk;
}

/**
 * @param {string[]} slugs
 */
export async function removeSeriesOffline(slugs) {
    for (const slug of slugs) {
        if (typeof slug === 'string' && slug !== '') {
            await removeStoryOffline(slug);
        }
    }
}

/**
 * @param {string} slug
 */
export async function removeStoryOffline(slug) {
    await caches.delete(STORY_PREFIX + slug);
    await deleteOfflineMeta(slug);

    const remaining = await listOfflineStories();
    if (remaining.length === 0) {
        await caches.delete(SHELL_CACHE);
    }
}

export async function removeAllStoriesOffline() {
    const stories = await listOfflineStories();
    await Promise.all(stories.map((story) => caches.delete(STORY_PREFIX + story.slug)));
    await caches.delete(SHELL_CACHE);
    await caches.delete(META_CACHE);

    const db = await openDb();
    await new Promise((resolve, reject) => {
        const tx = db.transaction(STORE, 'readwrite');
        tx.objectStore(STORE).clear();
        tx.oncomplete = () => resolve();
        tx.onerror = () => reject(tx.error ?? new Error('Failed to clear offline meta'));
    });
}

/**
 * @param {number} bytes
 * @returns {string}
 */
export function formatBytes(bytes) {
    if (!Number.isFinite(bytes) || bytes <= 0) {
        return '0 B';
    }

    const units = ['B', 'KB', 'MB', 'GB'];
    let value = bytes;
    let unit = 0;

    while (value >= 1024 && unit < units.length - 1) {
        value /= 1024;
        unit += 1;
    }

    return `${value.toFixed(unit === 0 ? 0 : 1)} ${units[unit]}`;
}

/**
 * @param {string} slug
 */
export async function fetchStoryManifest(slug) {
    const response = await fetch(withAppBasePath(`/playbooks/${slug}/offline-manifest`), {
        credentials: 'same-origin',
        headers: { Accept: 'application/json' },
    });

    if (!response.ok) {
        throw new Error(getShellLabel('playbooks.offline.error'));
    }

    return response.json();
}

/** @type {Promise<object> | null} */
let bulkManifestPromise = null;

/**
 * @param {{ force?: boolean }} [options]
 */
export async function fetchBulkManifest(options = {}) {
    if (options.force) {
        bulkManifestPromise = null;
    }

    if (!bulkManifestPromise) {
        bulkManifestPromise = (async () => {
            const response = await fetch(withAppBasePath('/playbooks/offline-manifest'), {
                credentials: 'same-origin',
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) {
                throw new Error(getShellLabel('playbooks.offline.error'));
            }

            return response.json();
        })().catch((error) => {
            bulkManifestPromise = null;
            throw error;
        });
    }

    return bulkManifestPromise;
}

/**
 * @param {string} seriesId
 */
export async function fetchSeriesManifest(seriesId) {
    const response = await fetch(
        withAppBasePath(`/playbooks/series/${encodeURIComponent(seriesId)}/offline-manifest`),
        {
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        },
    );

    if (!response.ok) {
        throw new Error(getShellLabel('playbooks.offline.error'));
    }

    return response.json();
}

export function isOfflineSupported() {
    return 'serviceWorker' in navigator && 'caches' in window && 'indexedDB' in window;
}
