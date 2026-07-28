/**
 * Format minutes as "12 min", "1 Std 5 min" / "1 h 5 min", or whole hours without minutes.
 *
 * @param {number} minutes
 * @param {'de' | 'en'} locale
 * @returns {string}
 */
export function formatReadingTime(minutes, locale = 'en') {
    const total = Math.max(0, Math.floor(Number(minutes) || 0));

    if (total < 60) {
        return `${total} min`;
    }

    const hours = Math.floor(total / 60);
    const remainder = total % 60;
    const hourUnit = locale === 'de' ? 'Std' : 'h';

    if (remainder === 0) {
        return `${hours} ${hourUnit}`;
    }

    return `${hours} ${hourUnit} ${remainder} min`;
}

/**
 * Split series card meta into a stable primary line + optional progress line.
 * Progress never uses remaining time (total − read).
 *
 * @param {{
 *   partCount: number,
 *   totalMinutes: number,
 *   readMinutes?: number,
 *   readPartCount?: number,
 *   locale: 'de' | 'en',
 * }} options
 * @returns {{ primary: string, progress: string }}
 */
export function buildSeriesCardMetaLines({
    partCount,
    totalMinutes,
    readMinutes = 0,
    readPartCount = 0,
    locale = 'en',
}) {
    const parts = Math.max(0, Math.floor(Number(partCount) || 0));
    const readParts = Math.min(parts, Math.max(0, Math.floor(Number(readPartCount) || 0)));
    const totalLabel = formatReadingTime(totalMinutes, locale);
    const read = Math.max(0, Math.floor(Number(readMinutes) || 0));

    const primary = locale === 'de'
        ? `${parts} Teile · ${totalLabel} gesamt`
        : `${parts} parts · ${totalLabel} total`;

    if (read <= 0 || readParts <= 0) {
        return { primary, progress: '' };
    }

    const readLabel = formatReadingTime(read, locale);
    const progress = locale === 'de'
        ? `${readParts}/${parts} · ${readLabel} gelesen`
        : `${readParts}/${parts} · ${readLabel} read`;

    return { primary, progress };
}

/**
 * Build series card meta as a single string (tests / plain text).
 *
 * @param {{
 *   partCount: number,
 *   totalMinutes: number,
 *   readMinutes?: number,
 *   readPartCount?: number,
 *   locale: 'de' | 'en',
 * }} options
 * @returns {string}
 */
export function buildSeriesCardMetaText(options) {
    const { primary, progress } = buildSeriesCardMetaLines(options);
    return progress === '' ? primary : `${primary}\n${progress}`;
}
