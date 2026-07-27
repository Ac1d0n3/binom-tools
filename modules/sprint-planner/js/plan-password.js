/**
 * Local soft lock for plan instances.
 * Not real access control — hash lives in localStorage; unlock is session-only.
 */

const UNLOCK_PREFIX = 'bn-tools:sprint-planner:unlock:';

/**
 * @param {number} [bytes]
 * @returns {string}
 */
export function createSalt(bytes = 16) {
    const arr = new Uint8Array(bytes);
    crypto.getRandomValues(arr);
    return bytesToHex(arr);
}

/**
 * @param {string} password
 * @param {string} salt
 * @returns {Promise<string>}
 */
export async function hashPassword(password, salt) {
    const payload = `${salt}:${password}`;
    const data = new TextEncoder().encode(payload);
    const digest = await crypto.subtle.digest('SHA-256', data);
    return bytesToHex(new Uint8Array(digest));
}

/**
 * @param {string} password
 * @param {{passwordHash?: string|null, passwordSalt?: string|null}} instance
 */
export async function verifyPassword(password, instance) {
    if (!instance?.passwordHash || !instance?.passwordSalt) {
        return true;
    }
    const hash = await hashPassword(password, instance.passwordSalt);
    return hash === instance.passwordHash;
}

/**
 * @param {import('./storage.js').SpPlanInstance} instance
 */
export function isPasswordProtected(instance) {
    return Boolean(instance?.passwordHash && instance?.passwordSalt);
}

/**
 * @param {string} instanceId
 */
export function isPlanUnlocked(instanceId) {
    try {
        return sessionStorage.getItem(UNLOCK_PREFIX + instanceId) === '1';
    } catch {
        return false;
    }
}

/**
 * @param {string} instanceId
 */
export function unlockPlan(instanceId) {
    try {
        sessionStorage.setItem(UNLOCK_PREFIX + instanceId, '1');
        return true;
    } catch {
        return false;
    }
}

/**
 * @param {string} instanceId
 */
export function lockPlanSession(instanceId) {
    try {
        sessionStorage.removeItem(UNLOCK_PREFIX + instanceId);
        return true;
    } catch {
        return false;
    }
}

/**
 * @param {Uint8Array} bytes
 */
function bytesToHex(bytes) {
    return [...bytes].map((b) => b.toString(16).padStart(2, '0')).join('');
}
