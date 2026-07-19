import { createLocalId } from './ids.js';

/** Max upload size per file (10 MB). */
export const MAX_ATTACHMENT_BYTES = 10 * 1024 * 1024;

/** @type {ReadonlySet<string>} */
export const ALLOWED_ATTACHMENT_MIME = new Set([
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp',
    'image/svg+xml',
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'text/csv',
]);

/**
 * @typedef {Object} SpAttachment
 * @property {string} id
 * @property {string} name
 * @property {string} mime
 * @property {number} size
 * @property {'link'|'file'} kind
 * @property {string} href
 * @property {boolean} previewable
 * @property {string} uploadedAt
 * @property {string|null} uploadedBy
 * @property {boolean} [localBlob] — true when blob lives in IndexedDB
 * @property {boolean} [blobMissing] — export/import hint
 */

/**
 * @returns {string}
 */
export function createAttachmentId() {
    return createLocalId('att_');
}

/**
 * @param {string} mime
 * @returns {boolean}
 */
export function isPreviewableMime(mime) {
    return typeof mime === 'string' && mime.startsWith('image/');
}

/**
 * @param {string} mime
 * @param {string} [fileName]
 * @returns {boolean}
 */
export function isAllowedAttachment(mime, fileName = '') {
    if (ALLOWED_ATTACHMENT_MIME.has(mime)) {
        return true;
    }
    const lower = String(fileName).toLowerCase();
    return /\.(jpe?g|png|gif|webp|svg|pdf|docx?|pptx?|xlsx?|csv)$/i.test(lower);
}

/**
 * @param {unknown} value
 * @returns {SpAttachment[]}
 */
export function normalizeAttachments(value) {
    if (!Array.isArray(value)) {
        return [];
    }
    /** @type {SpAttachment[]} */
    const out = [];
    const seen = new Set();
    for (const entry of value) {
        if (!entry || typeof entry !== 'object') {
            continue;
        }
        const raw = /** @type {Record<string, unknown>} */ (entry);
        const id = String(raw.id || '').trim() || createAttachmentId();
        if (seen.has(id)) {
            continue;
        }
        seen.add(id);
        const name = String(raw.name || 'attachment').trim() || 'attachment';
        const mime = String(raw.mime || 'application/octet-stream').trim();
        const kind = raw.kind === 'link' ? 'link' : 'file';
        const href = String(raw.href || '').trim();
        out.push({
            id,
            name,
            mime,
            size: Number(raw.size) || 0,
            kind,
            href,
            previewable: raw.previewable === true || isPreviewableMime(mime),
            uploadedAt: String(raw.uploadedAt || new Date().toISOString()),
            uploadedBy: raw.uploadedBy ? String(raw.uploadedBy) : null,
            localBlob: raw.localBlob === true,
            blobMissing: raw.blobMissing === true,
        });
    }
    return out;
}

/**
 * @param {{label?: string, href: string, mime?: string, uploadedBy?: string|null}} opts
 * @returns {SpAttachment}
 */
export function createLinkAttachment(opts) {
    const href = String(opts.href || '').trim();
    const name = String(opts.label || opts.href || 'Link').trim() || 'Link';
    const mime = String(opts.mime || 'text/uri-list');
    return {
        id: createAttachmentId(),
        name,
        mime,
        size: 0,
        kind: 'link',
        href,
        previewable: isPreviewableMime(mime) || /\.(jpe?g|png|gif|webp|svg)(\?|$)/i.test(href),
        uploadedAt: new Date().toISOString(),
        uploadedBy: opts.uploadedBy || null,
        localBlob: false,
        blobMissing: false,
    };
}

/**
 * @param {{name: string, mime: string, size: number, href?: string, uploadedBy?: string|null, localBlob?: boolean}} opts
 * @returns {SpAttachment}
 */
export function createFileAttachmentMeta(opts) {
    const mime = String(opts.mime || 'application/octet-stream');
    return {
        id: createAttachmentId(),
        name: String(opts.name || 'file').trim() || 'file',
        mime,
        size: Number(opts.size) || 0,
        kind: 'file',
        href: String(opts.href || ''),
        previewable: isPreviewableMime(mime),
        uploadedAt: new Date().toISOString(),
        uploadedBy: opts.uploadedBy || null,
        localBlob: opts.localBlob === true,
        blobMissing: false,
    };
}

/**
 * @param {File} file
 * @returns {{ok: true}|{ok: false, error: string}}
 */
export function validateAttachmentFile(file) {
    if (!file) {
        return { ok: false, error: 'attachment-invalid' };
    }
    if (file.size > MAX_ATTACHMENT_BYTES) {
        return { ok: false, error: 'attachment-too-large' };
    }
    if (!isAllowedAttachment(file.type, file.name)) {
        return { ok: false, error: 'attachment-type' };
    }
    return { ok: true };
}
