/**
 * Remove known artist names and common "in the style of …" references from prompt text.
 */

/**
 * @param {string} value
 * @returns {string}
 */
function escapeRegex(value) {
    return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

/**
 * @param {string[]} names
 * @returns {string[]}
 */
export function normalizeArtistNames(names) {
    return [...new Set(names.map((name) => String(name).trim()).filter(Boolean))].sort(
        (a, b) => b.length - a.length,
    );
}

/**
 * @param {string} text
 * @param {string[]} names
 * @returns {{ text: string, removed: string[] }}
 */
export function stripArtistReferences(text, names) {
    const artistNames = normalizeArtistNames(names);
    if (!text.trim() || artistNames.length === 0) {
        return { text, removed: [] };
    }

    let result = text;
    /** @type {Set<string>} */
    const removed = new Set();

    for (const name of artistNames) {
        const escaped = escapeRegex(name);

        const patterns = [
            new RegExp(
                `\\b(?:in the style of|in style of|style of|sounds like|similar to|like|à la|a la|ähnlich wie|im stil von|im Stil von)\\s+${escaped}\\b`,
                'gi',
            ),
            new RegExp(`\\b${escaped}\\s+(?:style|vibe|sound|type beat)\\b`, 'gi'),
            new RegExp(`\\[Style:\\s*${escaped}\\s*\\]`, 'gi'),
            new RegExp(`\\b${escaped}\\b`, 'gi'),
        ];

        for (const pattern of patterns) {
            const next = result.replace(pattern, '');
            if (next !== result) {
                removed.add(name);
                result = next;
            }
        }
    }

    result = result
        .replace(/[ \t]{2,}/g, ' ')
        .replace(/[ \t]+\n/g, '\n')
        .replace(/\n[ \t]+/g, '\n')
        .replace(/\n{3,}/g, '\n\n')
        .replace(/,\s*,/g, ',')
        .replace(/:\s*\n/g, ':\n')
        .trim();

    return { text: result, removed: [...removed] };
}

/**
 * @param {string[]} blocklist
 * @param {Record<string, unknown>} [parameterValues]
 * @returns {string[]}
 */
export function resolveArtistNamesToStrip(blocklist, parameterValues = {}) {
    const user = parameterValues.forbiddenArtistNames;
    const userList = Array.isArray(user) ? user.map(String) : [];
    return normalizeArtistNames([...blocklist, ...userList]);
}

/**
 * @param {string} text
 * @param {{
 *   blocklist?: string[],
 *   parameterValues?: Record<string, unknown>,
 *   enabled?: boolean,
 * }} [options]
 * @returns {{ text: string, removed: string[] }}
 */
export function postProcessCompiledPrompt(text, options = {}) {
    const enabled = options.enabled !== false;
    const stripDisabled = options.parameterValues?.stripArtistNames === 'no';
    if (!enabled || stripDisabled) {
        return { text, removed: [] };
    }

    const artistNames = resolveArtistNamesToStrip(options.blocklist ?? [], options.parameterValues);
    return stripArtistReferences(text, artistNames);
}
