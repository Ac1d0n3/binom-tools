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
 *
 * @param {{
 *   partCount: number,
 *   totalMinutes: number,
 *   readMinutes?: number,
 *   locale: 'de' | 'en',
 * }} options
 * @returns {string}
 */
export function buildSeriesCardMetaText({
    partCount,
    totalMinutes,
    readMinutes = 0,
    locale = 'en',
}) {
    const partsLabel = locale === 'de'
        ? `${partCount} Teile`
        : `${partCount} parts`;
    const totalLabel = formatReadingTime(totalMinutes, locale);
    const read = Math.max(0, Math.floor(Number(readMinutes) || 0));

    if (read <= 0) {
        return locale === 'de'
            ? `${partsLabel} · ${totalLabel} gesamt`
            : `${partsLabel} · ${totalLabel} total`;
    }

    const readLabel = formatReadingTime(read, locale);

    return locale === 'de'
        ? `${partsLabel} · ${readLabel} von ${totalLabel}`
        : `${partsLabel} · ${readLabel} of ${totalLabel}`;
}
