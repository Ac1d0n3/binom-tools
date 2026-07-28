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
 * Build series card meta text, optionally including read progress.
 * Shows completed reading time as "read", never as remaining (total − read).
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
export function buildSeriesCardMetaText({
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

    const partsLabel = readParts > 0
        ? (locale === 'de' ? `${readParts}/${parts} Teile` : `${readParts}/${parts} parts`)
        : (locale === 'de' ? `${parts} Teile` : `${parts} parts`);

    if (read <= 0) {
        return locale === 'de'
            ? `${partsLabel} · ${totalLabel} gesamt`
            : `${partsLabel} · ${totalLabel} total`;
    }

    const readLabel = formatReadingTime(read, locale);

    return locale === 'de'
        ? `${partsLabel} · ${readLabel} gelesen · ${totalLabel} gesamt`
        : `${partsLabel} · ${readLabel} read · ${totalLabel} total`;
}
