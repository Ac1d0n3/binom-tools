import '../css/calendar.scss';

const HIDDEN_STORAGE_KEY = 'binom-tools-calendar-hidden-layers';
const SIDEBAR_SECTIONS_STORAGE_KEY = 'binom-tools-calendar-sidebar-sections';
const FILTERS_STORAGE_KEY = 'binom-tools-calendar-filters';
const GRID_START_HOUR = 6;
const GRID_END_HOUR = 22;
const SLOT_MINUTES = 30;

function readBootstrap() {
    const node = document.getElementById('calendar-bootstrap');
    if (!node?.textContent) {
        return null;
    }

    try {
        return JSON.parse(node.textContent);
    } catch {
        return null;
    }
}

/**
 * Layer ids are numeric for plan calendars and string slugs for file-store holiday sources
 * (e.g. "de-nw-school-holidays"). Always compare as strings — Number("de-nw-…") is NaN and
 * collapses every holiday layer into one broken toggle.
 */
function layerId(value) {
    if (value == null || value === '') {
        return null;
    }

    const id = String(value);

    return id === 'NaN' || id === 'null' || id === 'undefined' ? null : id;
}

function readHiddenLayers() {
    try {
        const raw = localStorage.getItem(HIDDEN_STORAGE_KEY);
        const parsed = raw ? JSON.parse(raw) : {};
        const toIdSet = (values) => new Set(
            (values ?? []).map(layerId).filter((id) => id !== null),
        );

        return {
            calendars: toIdSet(parsed.calendars),
            holiday_sources: toIdSet(parsed.holiday_sources),
        };
    } catch {
        return { calendars: new Set(), holiday_sources: new Set() };
    }
}

function writeHiddenLayers(hidden) {
    try {
        localStorage.setItem(HIDDEN_STORAGE_KEY, JSON.stringify({
            calendars: [...hidden.calendars],
            holiday_sources: [...hidden.holiday_sources],
        }));
    } catch {
        // Ignore storage errors.
    }
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function parseDate(value) {
    if (!value) {
        return new Date();
    }

    const raw = String(value);
    if (/^\d{4}-\d{2}-\d{2}$/.test(raw)) {
        const date = new Date(`${raw}T12:00:00`);
        return Number.isNaN(date.getTime()) ? new Date() : date;
    }

    const date = new Date(raw);
    return Number.isNaN(date.getTime()) ? new Date() : date;
}

function dateKeyFromIso(isoString) {
    if (!isoString) {
        return formatDateKey(new Date());
    }

    const raw = String(isoString);
    const match = raw.match(/^(\d{4}-\d{2}-\d{2})/);
    if (match) {
        return match[1];
    }

    return formatDateKey(parseDate(raw));
}

function formatDateKey(date) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');

    return `${y}-${m}-${d}`;
}

function weekStartFromState(state) {
    return state?.bootstrap?.week_start === 'sunday' ? 'sunday' : 'monday';
}

function startOfWeek(date, weekStart = 'monday') {
    const copy = new Date(date);
    const day = copy.getDay();

    if (weekStart === 'sunday') {
        copy.setDate(copy.getDate() - day);
    } else {
        const iso = day === 0 ? 7 : day;
        copy.setDate(copy.getDate() - (iso - 1));
    }

    return copy;
}

function isWeekend(date) {
    const day = date.getDay();

    return day === 0 || day === 6;
}

function dayOffset(fromKey, toKey) {
    const from = parseDate(fromKey);
    const to = parseDate(toKey);

    return Math.round((to.getTime() - from.getTime()) / 86400000);
}

function addDays(date, days) {
    const copy = new Date(date);
    copy.setDate(copy.getDate() + days);

    return copy;
}

function addMonths(date, months) {
    const copy = new Date(date);
    copy.setMonth(copy.getMonth() + months);

    return copy;
}

function rangeForView(view, anchor, weekStart = 'monday') {
    const start = new Date(anchor);

    if (view === 'week') {
        const from = startOfWeek(start, weekStart);
        const to = addDays(from, 6);

        return { from, to };
    }

    if (view === 'day') {
        return { from: start, to: start };
    }

    if (view === 'list') {
        const from = new Date(start.getFullYear(), start.getMonth(), 1);
        const to = new Date(start.getFullYear(), start.getMonth() + 3, 0);

        return { from, to };
    }

    const from = new Date(start.getFullYear(), start.getMonth(), 1);
    const to = new Date(start.getFullYear(), start.getMonth() + 1, 0);

    return { from, to };
}

function isLayerVisible(hidden, type, id) {
    const key = layerId(id);
    if (key === null) {
        return true;
    }

    const set = type === 'holiday' ? hidden.holiday_sources : hidden.calendars;

    return !set.has(key);
}

function readFilters() {
    try {
        const raw = localStorage.getItem(FILTERS_STORAGE_KEY);
        const parsed = raw ? JSON.parse(raw) : {};

        return {
            myTasks: parsed.myTasks === true,
            hideDone: parsed.hideDone !== false,
        };
    } catch {
        return { myTasks: false, hideDone: true };
    }
}

function writeFilters(filters) {
    try {
        localStorage.setItem(FILTERS_STORAGE_KEY, JSON.stringify(filters));
    } catch {
        // Ignore storage errors.
    }
}

function filterData(state) {
    const currentUserId = state.bootstrap.current_user_id ?? null;
    let entries = state.entries.filter((entry) => isLayerVisible(state.hidden, 'calendar', entry.calendar_id));

    if (state.filters.hideDone) {
        entries = entries.filter((entry) => entry.completed !== true);
    }

    if (state.filters.myTasks && currentUserId) {
        entries = entries.filter((entry) => {
            if (entry.kind === 'story' || entry.kind === 'series') {
                return true;
            }

            return String(entry.assignee_user_id ?? '') === String(currentUserId);
        });
    }

    const holidays = state.holidays.filter((holiday) => {
        if (!state.bootstrap.holidays_enabled) {
            return false;
        }

        if (holiday.source_id == null) {
            return true;
        }

        return isLayerVisible(state.hidden, 'holiday', holiday.source_id);
    });

    return { entries, holidays };
}

function entryDateRange(entry) {
    const start = dateKeyFromIso(entry.starts_at);
    let end = entry.ends_at ? dateKeyFromIso(entry.ends_at) : start;

    if (end < start) {
        end = start;
    }

    return { start, end };
}

function eachDayKeyInRange(startKey, endKey) {
    const days = [];
    let day = parseDate(startKey);
    const end = parseDate(endKey);

    while (day <= end) {
        days.push(formatDateKey(day));
        day = addDays(day, 1);
    }

    return days;
}

function touchesDay(entry, dayKey) {
    const range = entryDateRange(entry);

    return dayKey >= range.start && dayKey <= range.end;
}

function isAllDayEntry(entry) {
    return entry.all_day === true;
}

function calendarColor(state, calendarId) {
    const calendar = state.bootstrap.calendars.find((item) => item.id === calendarId);

    return calendar?.color ?? 'var(--calendar-accent)';
}

function eventMarkup(entry) {
    if (isStoryOrSeriesEntry(entry)) {
        return storyBadgeMarkup(entry);
    }

    const color = entry.calendar_color ?? 'var(--calendar-accent)';
    const title = escapeHtml(entry.title);
    const style = `--calendar-event-color: ${escapeHtml(color)}`;
    const recurring = entry.is_recurring
        ? '<span class="calendar-event-chip__repeat" aria-hidden="true" title="Serie">↻</span>'
        : '';

    if (entry.url) {
        return `<a href="${escapeHtml(entry.url)}" class="calendar-event-chip" style="${style}">${recurring}<span class="calendar-event-chip__text">${title}</span></a>`;
    }

    return `<span class="calendar-event-chip" style="${style}">${recurring}<span class="calendar-event-chip__text">${title}</span></span>`;
}

function holidayMarkup(holiday) {
    const label = holiday.title ?? holiday.name ?? '';

    return `<span class="calendar-holiday-chip">${escapeHtml(label)}</span>`;
}

const MAX_MONTH_CELL_EVENTS = 3;
const MAX_MONTH_STORY_BADGES = 8;
const MAX_MONTH_BAR_LANES = 4;

const HOLIDAY_TYPE_SORT_ORDER = {
    public_holiday: 0,
    school_holiday: 1,
    custom: 2,
};

function holidayTypeSortKey(item) {
    if (!item.is_holiday) {
        return 99;
    }

    return HOLIDAY_TYPE_SORT_ORDER[item.holiday_type] ?? 3;
}

function compareCellItems(a, b) {
    const holidayDiff = Number(b.is_holiday) - Number(a.is_holiday);
    if (holidayDiff !== 0) {
        return holidayDiff;
    }

    if (a.is_holiday && b.is_holiday) {
        const typeDiff = holidayTypeSortKey(a) - holidayTypeSortKey(b);
        if (typeDiff !== 0) {
            return typeDiff;
        }
    }

    return String(a.title ?? '').localeCompare(String(b.title ?? ''));
}

function sortCellItems(items) {
    return [...items].sort(compareCellItems);
}

function isStoryEntry(entry) {
    return entry?.kind === 'story';
}

function isSeriesEntry(entry) {
    return entry?.kind === 'series';
}

function isStoryOrSeriesEntry(entry) {
    return isStoryEntry(entry) || isSeriesEntry(entry);
}

function isSpanBarEntry(entry) {
    if (entry.is_holiday) {
        return entry.range.start !== entry.range.end;
    }

    // Stories/series are single-day icon badges, never multi-day bars.
    if (isStoryOrSeriesEntry(entry)) {
        return false;
    }

    const range = entryDateRange(entry);

    return range.start !== range.end || isAllDayEntry(entry);
}

function storyBadgeMarkup(entry, options = {}) {
    const withLabel = Boolean(options.withLabel);
    const title = escapeHtml(entry.title);
    const classes = ['calendar-story-badge'];
    const color = entry.calendar_color ?? entry.color ?? null;
    const style = color ? ` style="--calendar-story-color: ${escapeHtml(color)}"` : '';
    const isSeries = isSeriesEntry(entry);
    const icon = isSeries ? 'fa-layer-group' : 'fa-book-open';
    const partCount = Number(entry.part_count ?? 0);
    const tooltip = isSeries && partCount > 0
        ? escapeHtml(`${entry.title} (${partCount})`)
        : title;

    if (isSeries) {
        classes.push('calendar-story-badge--series');
    }

    if (withLabel) {
        classes.push('calendar-story-badge--labeled');
    }

    const labelText = isSeries && partCount > 0 && withLabel
        ? `${title} <span class="calendar-story-badge__count">(${partCount})</span>`
        : title;
    const inner = withLabel
        ? `<i class="fa-solid ${icon}" aria-hidden="true"></i><span class="calendar-story-badge__label">${labelText}</span>`
        : `<i class="fa-solid ${icon}" aria-hidden="true"></i><span class="sr-only">${tooltip}</span>`;

    if (entry.url) {
        return `<a href="${escapeHtml(entry.url)}" class="${classes.join(' ')}"${style} title="${tooltip}" aria-label="${tooltip}">${inner}</a>`;
    }

    return `<span class="${classes.join(' ')}"${style} title="${tooltip}" aria-label="${tooltip}">${inner}</span>`;
}

function holidayColor(state, holiday) {
    if (holiday.color) {
        return holiday.color;
    }

    const source = state.bootstrap.holiday_sources?.find(
        (item) => layerId(item.id) === layerId(holiday.source_id),
    );

    return source?.color ?? '#94a3b8';
}

function holidayAsEntry(holiday, state, range = null) {
    const date = holiday.date ?? dateKeyFromIso(holiday.starts_at);
    const displayRange = range ?? { start: date, end: date };

    return {
        is_holiday: true,
        title: holiday.name,
        url: null,
        color: holidayColor(state, holiday),
        holiday_type: holiday.type ?? null,
        range: displayRange,
    };
}

function holidayGroupKey(holiday) {
    return `${holiday.source_id ?? ''}:${holiday.name ?? ''}`;
}

function groupHolidayRanges(holidays) {
    const buckets = new Map();

    holidays.forEach((holiday) => {
        const date = holiday.date ?? dateKeyFromIso(holiday.starts_at);
        const key = holidayGroupKey(holiday);

        if (!buckets.has(key)) {
            buckets.set(key, { holiday, dates: [] });
        }

        buckets.get(key).dates.push(date);
    });

    const groups = [];

    buckets.forEach((bucket) => {
        const dates = [...new Set(bucket.dates)].sort();
        let runStart = dates[0];
        let runEnd = dates[0];

        for (let index = 1; index < dates.length; index += 1) {
            const date = dates[index];

            if (dayOffset(runEnd, date) === 1) {
                runEnd = date;
            } else {
                groups.push({ holiday: bucket.holiday, start: runStart, end: runEnd });
                runStart = date;
                runEnd = date;
            }
        }

        groups.push({ holiday: bucket.holiday, start: runStart, end: runEnd });
    });

    return groups;
}

function eventBoxMarkup(entry, state, options = {}) {
    if (isStoryOrSeriesEntry(entry)) {
        return storyBadgeMarkup(entry);
    }

    const resolvedColor = entry.color
        ?? entry.calendar_color
        ?? calendarColor(state, entry.calendar_id);
    const title = escapeHtml(entry.title);
    const style = `--calendar-event-color: ${escapeHtml(resolvedColor)}`;
    const classes = ['calendar-month__event'];

    if (entry.is_holiday) {
        classes.push('calendar-month__event--holiday');
    }

    if (options.continuesBefore) {
        classes.push('calendar-month__event--continues-before');
    }

    if (options.continuesAfter) {
        classes.push('calendar-month__event--continues-after');
    }

    const recurring = entry.is_recurring
        ? '<span class="calendar-month__event-repeat" aria-hidden="true" title="Serie">↻</span>'
        : '';
    const inner = `${recurring}<span class="calendar-month__event-text">${title}</span>`;

    if (entry.url) {
        return `<a href="${escapeHtml(entry.url)}" class="${classes.join(' ')}" style="${style}">${inner}</a>`;
    }

    return `<span class="${classes.join(' ')}" style="${style}">${inner}</span>`;
}

function eventSpanMarkup(state, segment) {
    const { item, colStart, span, continuesBefore, continuesAfter } = segment;
    const box = eventBoxMarkup(item, state, { continuesBefore, continuesAfter });

    return `<div class="calendar-month__bar-slot" style="grid-column:${colStart + 1} / span ${span};--bar-lane:${segment.lane}">${box}</div>`;
}

function buildWeekBarSegments(spanItems, weekStartKey, weekEndKey) {
    return spanItems.flatMap((item) => {
        const range = item.range ?? entryDateRange(item);

        // Skip bars that do not intersect this week (avoids invalid grid columns
        // that render as truncated ghost chips on Mondays of earlier weeks).
        if (range.end < weekStartKey || range.start > weekEndKey) {
            return [];
        }

        const clippedStart = range.start < weekStartKey ? weekStartKey : range.start;
        const clippedEnd = range.end > weekEndKey ? weekEndKey : range.end;
        const colStart = dayOffset(weekStartKey, clippedStart);
        const span = dayOffset(clippedStart, clippedEnd) + 1;

        if (span < 1 || colStart < 0 || colStart > 6) {
            return [];
        }

        return [{
            item,
            colStart,
            span,
            continuesBefore: range.start < weekStartKey,
            continuesAfter: range.end > weekEndKey,
            range,
        }];
    });
}

function packBarLanes(segments) {
    const sorted = [...segments].sort((a, b) => {
        const holidayDiff = Number(b.item?.is_holiday) - Number(a.item?.is_holiday);
        if (holidayDiff !== 0) {
            return holidayDiff;
        }

        return a.colStart - b.colStart || b.span - a.span;
    });
    const lanes = [];

    sorted.forEach((segment) => {
        let placed = false;

        for (let lane = 0; lane < lanes.length; lane += 1) {
            const overlaps = lanes[lane].some((other) => !(
                segment.colStart + segment.span <= other.colStart
                || other.colStart + other.span <= segment.colStart
            ));

            if (!overlaps) {
                segment.lane = lane;
                lanes[lane].push(segment);
                placed = true;
                break;
            }
        }

        if (!placed) {
            segment.lane = lanes.length;
            lanes.push([segment]);
        }
    });

    return sorted;
}

function renderMonth(state, viewport) {
    const { entries, holidays } = filterData(state);
    const weekStart = weekStartFromState(state);
    const anchor = parseDate(state.anchor.date);
    const monthStart = new Date(anchor.getFullYear(), anchor.getMonth(), 1);
    const monthEnd = new Date(anchor.getFullYear(), anchor.getMonth() + 1, 0);
    const gridStart = startOfWeek(monthStart, weekStart);
    const gridEnd = addDays(startOfWeek(monthEnd, weekStart), 6);
    const today = formatDateKey(new Date());

    const spanItems = [];
    const cellItemsByDay = new Map();

    const addCellItem = (key, item) => {
        if (!cellItemsByDay.has(key)) {
            cellItemsByDay.set(key, []);
        }

        const list = cellItemsByDay.get(key);
        const dedupeKey = item.is_holiday
            ? `holiday:${item.title}:${item.range?.start}`
            : `${item.id}:${item.starts_at}`;

        if (!list.some((existing) => {
            const existingKey = existing.is_holiday
                ? `holiday:${existing.title}:${existing.range?.start}`
                : `${existing.id}:${existing.starts_at}`;

            return existingKey === dedupeKey;
        })) {
            list.push(item);
        }
    };

    entries.forEach((entry) => {
        if (isSpanBarEntry(entry)) {
            spanItems.push({ ...entry, range: entryDateRange(entry) });
        } else {
            const range = entryDateRange(entry);
            eachDayKeyInRange(range.start, range.end).forEach((key) => addCellItem(key, entry));
        }
    });

    groupHolidayRanges(holidays).forEach((group) => {
        const range = { start: group.start, end: group.end };
        const item = holidayAsEntry(group.holiday, state, range);

        if (group.start === group.end) {
            addCellItem(group.start, item);
        } else {
            spanItems.push(item);
        }
    });

    const weekdays = (state.bootstrap.weekdays ?? []).map((label) => (
        `<span class="calendar-month__weekday" role="columnheader">${escapeHtml(label)}</span>`
    )).join('');

    let weekCount = 0;
    let weeksHtml = '';
    for (let weekStartDate = new Date(gridStart); weekStartDate <= gridEnd; weekStartDate = addDays(weekStartDate, 7)) {
        weekCount += 1;
        const weekEndDate = addDays(weekStartDate, 6);
        const weekStartKey = formatDateKey(weekStartDate);
        const weekEndKey = formatDateKey(weekEndDate);
        const segments = packBarLanes(buildWeekBarSegments(spanItems, weekStartKey, weekEndKey))
            .filter((segment) => segment.lane < MAX_MONTH_BAR_LANES);
        const laneCount = segments.reduce((max, segment) => Math.max(max, segment.lane + 1), 0);

        let spansHtml = '';
        if (segments.length > 0) {
            const barSlots = segments.map((segment) => eventSpanMarkup(state, segment)).join('');
            spansHtml = `
                <div class="calendar-month__week-spans" style="--bar-rows:${laneCount}">
                    ${barSlots}
                </div>
            `;
        }

        let daysHtml = '';
        for (let offset = 0; offset < 7; offset += 1) {
            const day = addDays(weekStartDate, offset);
            const key = formatDateKey(day);
            const cellItems = cellItemsByDay.get(key) ?? [];
            const isMuted = day.getMonth() !== anchor.getMonth();
            const classes = ['calendar-month__day'];

            if (isMuted) {
                classes.push('is-muted');
            }

            if (key === today) {
                classes.push('is-today');
            }

            if (isWeekend(day)) {
                classes.push('is-weekend');
            }

            const sortedCellItems = sortCellItems(cellItems);
            const storyItems = sortedCellItems.filter(isStoryOrSeriesEntry);
            const otherItems = sortedCellItems.filter((item) => !isStoryOrSeriesEntry(item));
            const visibleStories = storyItems.slice(0, MAX_MONTH_STORY_BADGES);
            const visibleOthers = otherItems.slice(0, MAX_MONTH_CELL_EVENTS);
            const hiddenCount = (storyItems.length - visibleStories.length)
                + (otherItems.length - visibleOthers.length);
            const storiesHtml = visibleStories.length
                ? `<div class="calendar-month__story-badges">${visibleStories.map(storyBadgeMarkup).join('')}</div>`
                : '';
            const eventsHtml = visibleOthers.map((item) => eventBoxMarkup(item, state)).join('');
            const more = hiddenCount > 0
                ? `<span class="calendar-month__more">+${hiddenCount}</span>`
                : '';

            daysHtml += `
                <div class="${classes.join(' ')}">
                    <span class="calendar-month__date">${day.getDate()}</span>
                    <div class="calendar-month__events">${storiesHtml}${eventsHtml}${more}</div>
                </div>
            `;
        }

        weeksHtml += `
            <div class="calendar-month__week" style="--bar-rows:${laneCount}">
                <div class="calendar-month__days">${daysHtml}</div>
                ${spansHtml}
            </div>
        `;
    }

    viewport.innerHTML = `
        <div class="calendar-month">
            <div class="calendar-month__weekdays">${weekdays}</div>
            <div class="calendar-month__grid" style="--week-count:${weekCount}">${weeksHtml}</div>
        </div>
    `;
}

function syncViewportHeight(state) {
    const layout = document.querySelector('[data-calendar-root]')?.closest('.calendar-layout')
        ?? document.querySelector('.calendar-layout');
    const main = document.querySelector('.calendar-layout__main');
    const card = document.querySelector('[data-calendar-root] .calendar-card');
    const viewport = document.querySelector('[data-calendar-viewport]');

    if (!layout || !main || !viewport) {
        return;
    }

    // Use the viewport bottom, not the footer's document position — in tools-shell the
    // footer sits in normal flow, so measuring footerTop while growing the layout
    // creates an infinite height feedback loop.
    const layoutTop = layout.getBoundingClientRect().top;
    const shell = layout.closest('.tools-shell') ?? document.querySelector('.tools-shell');
    const footer = shell?.querySelector('.tools-shell__footer');
    const footerReserve = Math.max(48, footer?.offsetHeight ?? 64);
    const padding = 12;
    const available = Math.max(320, Math.floor(window.innerHeight - layoutTop - footerReserve - padding));
    const syncKey = `${state.view}:${available}`;

    if (layout.dataset.calendarSyncedHeight === syncKey) {
        return;
    }

    layout.dataset.calendarSyncedHeight = syncKey;
    layout.style.maxHeight = `${available}px`;
    layout.style.height = `${available}px`;
    main.style.minHeight = '0';

    if (state.view !== 'month') {
        viewport.style.removeProperty('min-height');
        card?.style.removeProperty('min-height');

        return;
    }

    const chrome = card?.querySelector('.calendar-card__chrome');
    const chromeHeight = chrome?.offsetHeight ?? 0;
    const weekdaysHeight = viewport.querySelector('.calendar-month__weekdays')?.offsetHeight ?? 0;
    const gridAvailable = Math.max(240, available - chromeHeight - weekdaysHeight - 24);

    // Fill the fixed layout height via flex — do not push document growth with min-heights.
    viewport.style.minHeight = '0';
    viewport.style.flex = '1 1 auto';
    if (card) {
        card.style.minHeight = '0';
        card.style.height = '100%';
    }

    const grid = viewport.querySelector('.calendar-month__grid');
    if (grid) {
        grid.style.minHeight = `${gridAvailable}px`;
    }
}

function minutesFromMidnight(isoString) {
    const date = new Date(isoString);
    if (Number.isNaN(date.getTime())) {
        return GRID_START_HOUR * 60;
    }

    return date.getHours() * 60 + date.getMinutes();
}

function layoutOverlapping(events) {
    const sorted = [...events].sort((a, b) => minutesFromMidnight(a.starts_at) - minutesFromMidnight(b.starts_at));
    const columns = [];

    sorted.forEach((event) => {
        const start = minutesFromMidnight(event.starts_at);
        const end = Math.max(start + SLOT_MINUTES, minutesFromMidnight(event.ends_at ?? event.starts_at));
        let placed = false;

        for (let index = 0; index < columns.length; index += 1) {
            const last = columns[index][columns[index].length - 1];
            const lastEnd = Math.max(
                minutesFromMidnight(last.starts_at) + SLOT_MINUTES,
                minutesFromMidnight(last.ends_at ?? last.starts_at),
            );

            if (start >= lastEnd) {
                columns[index].push(event);
                event._col = index;
                placed = true;
                break;
            }
        }

        if (!placed) {
            event._col = columns.length;
            columns.push([event]);
        }

        event._colCount = columns.length;
    });

    return sorted;
}

function renderTimeGrid(state, viewport, view) {
    const { entries, holidays } = filterData(state);
    const anchor = parseDate(state.anchor.date);
    const weekStart = weekStartFromState(state);
    const days = view === 'day'
        ? [anchor]
        : Array.from({ length: 7 }, (_, index) => addDays(startOfWeek(anchor, weekStart), index));
    const totalMinutes = (GRID_END_HOUR - GRID_START_HOUR) * 60;
    const slotCount = totalMinutes / SLOT_MINUTES;
    const today = formatDateKey(new Date());

    const timeLabels = [];
    for (let hour = GRID_START_HOUR; hour < GRID_END_HOUR; hour += 1) {
        timeLabels.push(`<span class="calendar-time-grid__time-label">${String(hour).padStart(2, '0')}:00</span>`);
    }

    const header = days.map((day) => {
        const key = formatDateKey(day);
        const label = day.toLocaleDateString(state.bootstrap.locale ?? undefined, { weekday: 'short', day: 'numeric' });

        return `<div class="calendar-time-grid__day-header ${key === today ? 'is-today' : ''}">${escapeHtml(label)}</div>`;
    }).join('');

    const allDayRow = days.map((day) => {
        const key = formatDateKey(day);
        const dayHolidays = holidays.filter((holiday) => touchesDay({ starts_at: `${holiday.date}T00:00:00`, ends_at: holiday.ends_at }, key));
        const dayAllDay = entries.filter((entry) => isAllDayEntry(entry) && touchesDay(entry, key));

        return `<div class="calendar-time-grid__allday-cell">${[...dayHolidays.map(holidayMarkup), ...dayAllDay.map(eventMarkup)].join('')}</div>`;
    }).join('');

    let body = '';
    days.forEach((day) => {
        const key = formatDateKey(day);
        const dayEntries = entries.filter((entry) => !isAllDayEntry(entry) && touchesDay(entry, key));
        const laidOut = layoutOverlapping(dayEntries);
        let eventsHtml = '';

        laidOut.forEach((entry) => {
            const start = Math.max(0, minutesFromMidnight(entry.starts_at) - GRID_START_HOUR * 60);
            const end = Math.min(totalMinutes, minutesFromMidnight(entry.ends_at ?? entry.starts_at) - GRID_START_HOUR * 60);
            const height = Math.max((SLOT_MINUTES / totalMinutes) * 100, ((end - start) / totalMinutes) * 100);
            const top = (start / totalMinutes) * 100;
            const width = 100 / (entry._colCount || 1);
            const left = width * (entry._col || 0);
            const color = entry.calendar_color ?? calendarColor(state, entry.calendar_id);

            eventsHtml += `
                <div class="calendar-time-grid__event"
                     style="top:${top}%;height:${height}%;left:${left}%;width:calc(${width}% - 4px);--calendar-event-color:${escapeHtml(color)}">
                    ${eventMarkup(entry)}
                </div>
            `;
        });

        body += `<div class="calendar-time-grid__day-column" data-date="${key}">${eventsHtml}</div>`;
    });

    const slots = Array.from({ length: slotCount }, (_, index) => (
        `<div class="calendar-time-grid__slot" style="--slot-index:${index}"></div>`
    )).join('');

    let agenda = '';
    if (view === 'day') {
        const key = formatDateKey(anchor);
        const dayEntries = entries.filter((entry) => touchesDay(entry, key));
        agenda = `
            <aside class="calendar-day-agenda">
                <h3 class="calendar-day-agenda__title">${anchor.toLocaleDateString(state.bootstrap.locale ?? undefined, { weekday: 'long', day: 'numeric', month: 'long' })}</h3>
                <ul class="calendar-day-agenda__list">
                    ${dayEntries.length === 0
        ? `<li class="calendar-day-agenda__empty">${escapeHtml(document.querySelector('[data-calendar-root]')?.dataset.emptyLabel ?? '')}</li>`
        : dayEntries.map((entry) => `<li>${eventMarkup(entry)}</li>`).join('')}
                </ul>
            </aside>
        `;
    }

    viewport.innerHTML = `
        <div class="calendar-time-grid-wrap ${view === 'day' ? 'calendar-time-grid-wrap--day' : ''}">
            <div class="calendar-time-grid">
                <div class="calendar-time-grid__corner"></div>
                <div class="calendar-time-grid__headers">${header}</div>
                <div class="calendar-time-grid__axis">${timeLabels.join('')}</div>
                <div class="calendar-time-grid__allday">${allDayRow}</div>
                <div class="calendar-time-grid__slots">${slots}</div>
                <div class="calendar-time-grid__body">${body}</div>
            </div>
            ${agenda}
        </div>
    `;
}

function renderList(state, viewport) {
    const { entries, holidays } = filterData(state);
    const byDay = new Map();

    entries.forEach((entry) => {
        const range = entryDateRange(entry);
        eachDayKeyInRange(range.start, range.end).forEach((key) => {
            if (!byDay.has(key)) {
                byDay.set(key, []);
            }
            byDay.get(key).push(entry);
        });
    });

    holidays.forEach((holiday) => {
        const end = holiday.ends_at ? formatDateKey(parseDate(holiday.ends_at)) : holiday.date;
        eachDayKeyInRange(holiday.date, end).forEach((key) => {
            if (!byDay.has(key)) {
                byDay.set(key, []);
            }
            byDay.get(key).push({
                title: holiday.name,
                starts_at: `${holiday.date}T00:00:00`,
                url: null,
                all_day: true,
                is_holiday: true,
            });
        });
    });

    const days = [...byDay.keys()].sort();
    const emptyLabel = state.emptyLabel ?? 'No events';

    if (days.length === 0) {
        viewport.innerHTML = `<p class="calendar-list__empty">${escapeHtml(emptyLabel)}</p>`;

        return;
    }

    const html = days.map((key) => {
        const items = sortCellItems(byDay.get(key)).map((item) => {
            if (item.is_holiday) {
                return `<li>${holidayMarkup(item)}</li>`;
            }

            if (isStoryOrSeriesEntry(item)) {
                return `<li>${storyBadgeMarkup(item, { withLabel: true })}</li>`;
            }

            return `<li>${eventMarkup(item)}</li>`;
        }).join('');

        return `
            <section class="calendar-list__day">
                <h3 class="calendar-list__heading">${escapeHtml(key)}</h3>
                <ul class="calendar-list__items">${items}</ul>
            </section>
        `;
    }).join('');

    viewport.innerHTML = `<div class="calendar-list calendar-list--grouped">${html}</div>`;
}

function render(state) {
    const viewport = document.querySelector('[data-calendar-viewport]');
    if (!viewport) {
        return;
    }

    document.querySelector('[data-calendar-root]')?.setAttribute('data-calendar-view', state.view);

    document.querySelectorAll('[data-calendar-view-switcher] [data-calendar-view]').forEach((button) => {
        const active = button.getAttribute('data-calendar-view') === state.view;
        button.classList.toggle('is-active', active);
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
    });

    if (state.view === 'month') {
        renderMonth(state, viewport);
    } else if (state.view === 'week' || state.view === 'day') {
        renderTimeGrid(state, viewport, state.view);
    } else {
        renderList(state, viewport);
    }

    syncToolbar(state);
    syncMiniMonth(state);
    syncViewportHeight(state);
}

function syncToolbar(state) {
    const anchor = parseDate(state.anchor.date);
    const monthSelect = document.querySelector('[data-calendar-month]');
    const yearSelect = document.querySelector('[data-calendar-year]');

    if (monthSelect) {
        monthSelect.value = String(anchor.getMonth() + 1);
    }

    if (yearSelect) {
        yearSelect.value = String(anchor.getFullYear());
    }
}

function syncMiniMonth(state) {
    const label = document.querySelector('[data-calendar-mini-label]');
    const grid = document.querySelector('[data-calendar-mini-grid]');
    const anchor = parseDate(state.anchor.date);
    const locale = state.bootstrap.locale ?? undefined;
    const weekStart = weekStartFromState(state);
    const weekStartDow = weekStart === 'sunday' ? 0 : 1;
    const today = formatDateKey(new Date());
    const selected = state.anchor.date;

    if (label) {
        label.textContent = anchor.toLocaleDateString(locale, { month: 'long', year: 'numeric' });
    }

    if (!grid) {
        document.querySelectorAll('[data-calendar-date]').forEach((button) => {
            button.classList.toggle('calendar-mini-month__day--selected', button.getAttribute('data-calendar-date') === selected);
        });

        return;
    }

    const weekdays = state.bootstrap.weekdays ?? [];
    const monthStart = new Date(anchor.getFullYear(), anchor.getMonth(), 1);
    const daysInMonth = new Date(anchor.getFullYear(), anchor.getMonth() + 1, 0).getDate();
    const leadingPads = (monthStart.getDay() - weekStartDow + 7) % 7;
    const parts = [];

    weekdays.forEach((weekday) => {
        parts.push(`<span class="calendar-mini-month__weekday" role="columnheader">${escapeHtml(weekday)}</span>`);
    });

    for (let index = 0; index < leadingPads; index += 1) {
        parts.push('<span class="calendar-mini-month__pad" aria-hidden="true"></span>');
    }

    for (let day = 1; day <= daysInMonth; day += 1) {
        const date = formatDateKey(new Date(anchor.getFullYear(), anchor.getMonth(), day));
        const classes = ['calendar-mini-month__day'];

        if (date === today) {
            classes.push('calendar-mini-month__day--today');
        }

        if (date === selected) {
            classes.push('calendar-mini-month__day--selected');
        }

        parts.push(
            `<button type="button" class="${classes.join(' ')}" data-calendar-date="${date}" role="gridcell"><span class="calendar-mini-month__num">${day}</span></button>`,
        );
    }

    grid.innerHTML = parts.join('');
}

async function fetchRange(state) {
    const { from, to } = rangeForView(state.view, parseDate(state.anchor.date), weekStartFromState(state));
    const params = new URLSearchParams({
        from: formatDateKey(from),
        to: formatDateKey(to),
    });

    const [entriesResponse, holidaysResponse] = await Promise.all([
        fetch(`${state.bootstrap.urls.entries}?${params.toString()}`, { headers: { Accept: 'application/json' } }),
        state.bootstrap.holidays_enabled
            ? fetch(`${state.bootstrap.urls.holidays}?${params.toString()}`, { headers: { Accept: 'application/json' } })
            : Promise.resolve(null),
    ]);

    if (entriesResponse.ok) {
        const payload = await entriesResponse.json();
        state.entries = payload.data ?? [];
    }

    if (holidaysResponse?.ok) {
        const payload = await holidaysResponse.json();
        state.holidays = payload.data ?? [];
    }

    render(state);
}

function updateAnchor(state, date) {
    state.anchor = {
        year: date.getFullYear(),
        month: date.getMonth() + 1,
        date: formatDateKey(date),
    };
}

function bindLayers(state) {
    document.querySelectorAll('.calendar-layer-toggle__input').forEach((input) => {
        const type = input.getAttribute('data-layer-type');
        const id = layerId(input.getAttribute('data-layer-id'));
        const hiddenKey = type === 'holiday' ? 'holiday_sources' : 'calendars';

        if (id === null) {
            return;
        }

        input.checked = isLayerVisible(state.hidden, type, id);

        input.addEventListener('change', () => {
            if (input.checked) {
                state.hidden[hiddenKey].delete(id);
            } else {
                state.hidden[hiddenKey].add(id);
            }

            writeHiddenLayers(state.hidden);
            render(state);
        });
    });
}

function bindTaskFilters(state) {
    const myTasks = document.querySelector('[data-calendar-filter="my-tasks"]');
    const hideDone = document.querySelector('[data-calendar-filter="hide-done"]');

    if (myTasks) {
        myTasks.checked = state.filters.myTasks;
        myTasks.addEventListener('change', () => {
            state.filters.myTasks = myTasks.checked;
            writeFilters(state.filters);
            render(state);
        });
    }

    if (hideDone) {
        hideDone.checked = state.filters.hideDone;
        hideDone.addEventListener('change', () => {
            state.filters.hideDone = hideDone.checked;
            writeFilters(state.filters);
            render(state);
        });
    }
}

function bindPanelCollapse(state) {
    const layout = document.querySelector('[data-module-sidebar-layout="calendar"]')
        ?? document.querySelector('.calendar-layout');
    const toggle = layout?.querySelector('[data-module-sidebar-toggle]');

    if (!layout || !toggle || layout.getAttribute('data-sidebar-panel-collapsible') !== 'true') {
        return;
    }

    const storageKey = 'binom-tools-calendar-sidebar-panel-collapsed';

    const apply = (collapsed) => {
        layout.classList.toggle('calendar-layout--sidebar-panel-collapsed', collapsed);
        layout.dataset.sidebarPanelCollapsed = collapsed ? 'true' : 'false';
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        toggle.setAttribute(
            'aria-label',
            collapsed
                ? (toggle.dataset.labelExpand || '')
                : (toggle.dataset.labelCollapse || ''),
        );

        try {
            localStorage.setItem(storageKey, collapsed ? '1' : '0');
        } catch {
            // Ignore storage errors.
        }

        delete layout.dataset.calendarSyncedHeight;
        syncViewportHeight(state);
    };

    let collapsed = false;
    try {
        collapsed = localStorage.getItem(storageKey) === '1';
    } catch {
        collapsed = false;
    }

    // Prefer open sidebar on first visit so filters are discoverable.
    apply(collapsed);

    toggle.addEventListener('click', () => {
        apply(layout.classList.contains('calendar-layout--sidebar-panel-collapsed') === false
            ? true
            : false);
    });
}

function bindToolbar(state) {
    const toolbar = document.querySelector('[data-calendar-toolbar]');
    if (!toolbar) {
        return;
    }

    toolbar.addEventListener('click', (event) => {
        const nav = event.target.closest('[data-calendar-nav]');
        if (!nav) {
            return;
        }

        const anchor = parseDate(state.anchor.date);
        const step = state.view === 'week' ? 7 : state.view === 'day' ? 1 : 0;
        const mode = nav.getAttribute('data-calendar-nav');

        if (mode === 'today') {
            updateAnchor(state, new Date());
        } else if (mode === 'prev') {
            updateAnchor(state, step > 0 ? addDays(anchor, -step) : addMonths(anchor, -1));
        } else if (mode === 'next') {
            updateAnchor(state, step > 0 ? addDays(anchor, step) : addMonths(anchor, 1));
        }

        fetchRange(state);
    });

    const monthSelect = toolbar.querySelector('[data-calendar-month]');
    const yearSelect = toolbar.querySelector('[data-calendar-year]');
    const onPeriodChange = () => {
        const anchor = parseDate(state.anchor.date);
        updateAnchor(state, new Date(
            Number(yearSelect?.value ?? anchor.getFullYear()),
            Number(monthSelect?.value ?? anchor.getMonth() + 1) - 1,
            anchor.getDate(),
        ));
        fetchRange(state);
    };

    monthSelect?.addEventListener('change', onPeriodChange);
    yearSelect?.addEventListener('change', onPeriodChange);
}

function bindViewSwitcher(state) {
    document.querySelector('[data-calendar-view-switcher]')?.addEventListener('click', (event) => {
        const button = event.target.closest('[data-calendar-view]');
        if (!button) {
            return;
        }

        state.view = button.getAttribute('data-calendar-view') ?? 'month';
        const url = new URL(window.location.href);
        url.searchParams.set('view', state.view);
        window.history.replaceState({}, '', url);
        fetchRange(state);
    });
}

function readSidebarSections() {
    try {
        const raw = localStorage.getItem(SIDEBAR_SECTIONS_STORAGE_KEY);
        return raw ? JSON.parse(raw) : {};
    } catch {
        return {};
    }
}

function writeSidebarSection(id, open) {
    try {
        const stored = readSidebarSections();
        stored[id] = open;
        localStorage.setItem(SIDEBAR_SECTIONS_STORAGE_KEY, JSON.stringify(stored));
    } catch {
        // Ignore storage errors.
    }
}

function bindCollapsibleSections(onToggle) {
    const stored = readSidebarSections();

    document.querySelectorAll('[data-calendar-collapsible]').forEach((details) => {
        const id = details.getAttribute('data-calendar-collapsible');
        if (id && Object.prototype.hasOwnProperty.call(stored, id)) {
            details.open = stored[id] === true;
        } else {
            details.open = false;
        }

        details.addEventListener('toggle', () => {
            if (id) {
                writeSidebarSection(id, details.open);
            }

            onToggle?.();
        });
    });
}

function bindMiniMonth(state) {
    const section = document.querySelector('[data-calendar-mini-month]');
    if (!section) {
        return;
    }

    section.addEventListener('click', (event) => {
        const prev = event.target.closest('[data-calendar-mini-prev]');
        const next = event.target.closest('[data-calendar-mini-next]');
        const day = event.target.closest('[data-calendar-date]');
        const anchor = parseDate(state.anchor.date);

        if (prev) {
            updateAnchor(state, addMonths(anchor, -1));
            fetchRange(state);

            return;
        }

        if (next) {
            updateAnchor(state, addMonths(anchor, 1));
            fetchRange(state);

            return;
        }

        if (day) {
            updateAnchor(state, parseDate(day.getAttribute('data-calendar-date')));
            state.view = 'day';
            fetchRange(state);
        }
    });
}

function init() {
    const root = document.querySelector('[data-calendar-root]');
    const bootstrap = readBootstrap();

    if (!root || !bootstrap) {
        return;
    }

    const state = {
        bootstrap,
        view: bootstrap.view ?? 'month',
        anchor: bootstrap.anchor ?? { date: formatDateKey(new Date()) },
        entries: bootstrap.entries ?? [],
        holidays: bootstrap.holidays ?? [],
        hidden: readHiddenLayers(),
        filters: readFilters(),
        emptyLabel: root.dataset.emptyLabel ?? '',
    };

    bindLayers(state);
    bindTaskFilters(state);
    bindPanelCollapse(state);
    bindToolbar(state);
    bindViewSwitcher(state);
    bindMiniMonth(state);
    bindCollapsibleSections(() => {
        syncViewportHeight(state);
    });
    bindViewportResize(state);
    render(state);
}

function bindViewportResize(state) {
    let frame = 0;
    const resync = () => {
        // Allow height to be recomputed on window resize.
        const layout = document.querySelector('.calendar-layout');
        if (layout) {
            delete layout.dataset.calendarSyncedHeight;
        }

        if (frame) {
            cancelAnimationFrame(frame);
        }

        frame = requestAnimationFrame(() => {
            frame = 0;
            syncViewportHeight(state);
        });
    };

    window.addEventListener('resize', resync);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
