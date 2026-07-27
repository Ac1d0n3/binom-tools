/**
 * Escape a Markdown table cell.
 * @param {unknown} value
 */
export function mdCell(value) {
    const text = value == null ? '' : String(value);
    return text.replace(/\|/g, '\\|').replace(/\r?\n/g, ' ').trim();
}

/**
 * @param {string[]} headers
 * @param {string[][]} rows
 */
export function rowsToMarkdownTable(headers, rows) {
    const head = `| ${headers.map(mdCell).join(' | ')} |`;
    const sep = `| ${headers.map(() => '---').join(' | ')} |`;
    const body = rows.map((row) => `| ${row.map(mdCell).join(' | ')} |`).join('\n');
    return body ? `${head}\n${sep}\n${body}` : `${head}\n${sep}`;
}

/**
 * @param {unknown} value
 */
function csvCell(value) {
    const text = value == null ? '' : String(value);
    if (/[",\r\n]/.test(text)) {
        return `"${text.replace(/"/g, '""')}"`;
    }
    return text;
}

/**
 * @param {string[]} headers
 * @param {string[][]} rows
 */
export function rowsToCsv(headers, rows) {
    const lines = [headers.map(csvCell).join(',')];
    for (const row of rows) {
        lines.push(row.map(csvCell).join(','));
    }
    return lines.join('\n');
}

/**
 * @param {Array<{ checked?: boolean, label: string, note?: string }>} items
 */
export function checklistToMarkdown(items) {
    return items
        .map((item) => {
            const mark = item.checked ? 'x' : ' ';
            const note = item.note?.trim() ? ` — ${item.note.trim()}` : '';
            return `- [${mark}] ${item.label}${note}`;
        })
        .join('\n');
}
