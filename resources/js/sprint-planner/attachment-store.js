/**
 * IndexedDB blob store for local sprint-planner file attachments.
 */

const DB_NAME = 'bn-tools-sprint-planner-blobs-v1';
const STORE = 'blobs';
const DB_VERSION = 1;

/** @type {Promise<IDBDatabase>|null} */
let dbPromise = null;

/**
 * @returns {Promise<IDBDatabase>}
 */
function openDb() {
    if (dbPromise) {
        return dbPromise;
    }
    dbPromise = new Promise((resolve, reject) => {
        if (typeof indexedDB === 'undefined') {
            reject(new Error('indexeddb-unavailable'));
            return;
        }
        const request = indexedDB.open(DB_NAME, DB_VERSION);
        request.onupgradeneeded = () => {
            const db = request.result;
            if (!db.objectStoreNames.contains(STORE)) {
                db.createObjectStore(STORE);
            }
        };
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error || new Error('indexeddb-open-failed'));
    });
    return dbPromise;
}

/**
 * @param {string} planId
 * @param {string} attachmentId
 */
function blobKey(planId, attachmentId) {
    return `${planId}:${attachmentId}`;
}

/**
 * @param {string} planId
 * @param {string} attachmentId
 * @param {Blob} blob
 */
export async function putLocalBlob(planId, attachmentId, blob) {
    const db = await openDb();
    return new Promise((resolve, reject) => {
        const tx = db.transaction(STORE, 'readwrite');
        tx.objectStore(STORE).put(blob, blobKey(planId, attachmentId));
        tx.oncomplete = () => resolve();
        tx.onerror = () => reject(tx.error || new Error('indexeddb-put-failed'));
    });
}

/**
 * @param {string} planId
 * @param {string} attachmentId
 * @returns {Promise<Blob|null>}
 */
export async function getLocalBlob(planId, attachmentId) {
    const db = await openDb();
    return new Promise((resolve, reject) => {
        const tx = db.transaction(STORE, 'readonly');
        const req = tx.objectStore(STORE).get(blobKey(planId, attachmentId));
        req.onsuccess = () => resolve(req.result || null);
        req.onerror = () => reject(req.error || new Error('indexeddb-get-failed'));
    });
}

/**
 * @param {string} planId
 * @param {string} attachmentId
 */
export async function deleteLocalBlob(planId, attachmentId) {
    const db = await openDb();
    return new Promise((resolve, reject) => {
        const tx = db.transaction(STORE, 'readwrite');
        tx.objectStore(STORE).delete(blobKey(planId, attachmentId));
        tx.oncomplete = () => resolve();
        tx.onerror = () => reject(tx.error || new Error('indexeddb-delete-failed'));
    });
}

/** @type {Map<string, string>} */
const objectUrlCache = new Map();

/**
 * Resolve a display/download href for an attachment (server URL, link, or Object URL).
 * @param {string} planId
 * @param {import('./attachments.js').SpAttachment} attachment
 * @returns {Promise<string|null>}
 */
export async function resolveAttachmentHref(planId, attachment) {
    if (!attachment) {
        return null;
    }
    if (attachment.kind === 'link' && attachment.href) {
        return attachment.href;
    }
    if (attachment.href && !attachment.localBlob) {
        return attachment.href;
    }
    if (!attachment.localBlob) {
        return attachment.href || null;
    }
    const cacheKey = blobKey(planId, attachment.id);
    if (objectUrlCache.has(cacheKey)) {
        return objectUrlCache.get(cacheKey) || null;
    }
    try {
        const blob = await getLocalBlob(planId, attachment.id);
        if (!blob) {
            return null;
        }
        const url = URL.createObjectURL(blob);
        objectUrlCache.set(cacheKey, url);
        return url;
    } catch {
        return null;
    }
}

/**
 * @param {string} planId
 * @param {string} attachmentId
 */
export function revokeAttachmentObjectUrl(planId, attachmentId) {
    const cacheKey = blobKey(planId, attachmentId);
    const url = objectUrlCache.get(cacheKey);
    if (url) {
        URL.revokeObjectURL(url);
        objectUrlCache.delete(cacheKey);
    }
}
