/** @typedef {'prompt' | 'rule' | 'agent-task'} OutputKind */

export const OUTPUT_KINDS = /** @type {const} */ (['prompt', 'rule', 'agent-task']);

/**
 * @param {unknown} value
 * @returns {OutputKind}
 */
export function normalizeOutputKind(value) {
    return OUTPUT_KINDS.includes(/** @type {OutputKind} */ (value))
        ? /** @type {OutputKind} */ (value)
        : 'prompt';
}

/**
 * Build Markdown for project rules or agent tasks from compiled prompt text + meta.
 * @param {{ kind: OutputKind, title: string, body: string, globs?: string, alwaysApply?: boolean }} options
 */
export function buildMarkdownDocument(options) {
    const kind = normalizeOutputKind(options.kind);
    const title = String(options.title || 'untitled').trim() || 'untitled';
    const body = String(options.body || '').trim();

    if (kind === 'rule') {
        const globs = options.globs?.trim() || '**/*';
        const alwaysApply = options.alwaysApply === true;
        return [
            '---',
            `description: ${title}`,
            `globs: ${globs}`,
            `alwaysApply: ${alwaysApply ? 'true' : 'false'}`,
            '---',
            '',
            `# ${title}`,
            '',
            body,
            '',
        ].join('\n');
    }

    if (kind === 'agent-task') {
        return [
            '---',
            `name: ${slugify(title)}`,
            'kind: agent-task',
            '---',
            '',
            `# ${title}`,
            '',
            '## Goal',
            '',
            body || '_Describe the outcome._',
            '',
            '## Context',
            '',
            '_Repo paths, constraints, and relevant docs._',
            '',
            '## Constraints',
            '',
            '- Follow project conventions',
            '- Prefer the smallest safe change',
            '',
            '## Steps',
            '',
            '1. Investigate',
            '2. Implement',
            '3. Verify',
            '',
            '## Done when',
            '',
            '- [ ] Goal met',
            '- [ ] Tests or manual checks pass',
            '',
        ].join('\n');
    }

    return body;
}

/**
 * @param {string} title
 * @param {OutputKind} kind
 */
export function markdownFilename(title, kind) {
    const base = slugify(title) || 'document';
    if (kind === 'rule') return `${base}.mdc.md`;
    if (kind === 'agent-task') return `${base}.agent-task.md`;
    return `${base}.md`;
}

/**
 * Resolve output kind from a task definition (defaults to prompt).
 * @param {{ outputKind?: unknown } | null | undefined} task
 * @returns {OutputKind}
 */
export function getTaskOutputKind(task) {
    return normalizeOutputKind(task?.outputKind);
}

/**
 * @param {string} text
 * @param {string} filename
 */
export function downloadTextFile(text, filename) {
    const blob = new Blob([text], { type: 'text/markdown;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
}

/** @param {string} value */
function slugify(value) {
    return String(value || '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '')
        .slice(0, 80);
}
