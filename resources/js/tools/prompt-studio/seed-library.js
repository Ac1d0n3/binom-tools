import { loadTemplates, saveTemplates } from './storage.js';

const SEED_FLAG_KEY = 'binom-tools-prompt-studio-templates-seeded';
const MAIL_SEED_FLAG_KEY = 'binom-tools-prompt-studio-templates-mail-seeded-v1';

/**
 * Seed starter templates once when the user library is empty.
 * @returns {boolean} true if seeds were written
 */
export function ensureStarterTemplates() {
    if (localStorage.getItem(SEED_FLAG_KEY) === '1') {
        ensureMailStarterTemplates();
        return false;
    }
    const loaded = loadTemplates();
    const existing = loaded && 'data' in loaded ? loaded.data : [];
    if (existing.length > 0) {
        localStorage.setItem(SEED_FLAG_KEY, '1');
        ensureMailStarterTemplates();
        return false;
    }

    const now = new Date().toISOString();
    /** @type {import('./template-store.js').UserTemplate[]} */
    const seeds = [
        {
            id: 'seed_song_idea',
            name: 'Song idea (starter)',
            kind: 'prompt',
            roleId: 'songwriter',
            taskId: 'song-develop',
            modelId: 'chatgpt',
            parameterValues: { goal: 'Write a hopeful indie-pop song about starting over' },
            sections: {},
            sectionOverrides: {},
            tags: ['seed', 'song', 'prompt'],
            savedAt: now,
        },
        {
            id: 'seed_cover',
            name: 'Cover art (starter)',
            kind: 'prompt',
            roleId: 'art-director',
            taskId: 'cover',
            modelId: 'chatgpt',
            parameterValues: { goal: 'Minimal album cover for a night-drive synth track' },
            sections: {},
            sectionOverrides: {},
            tags: ['seed', 'visual', 'prompt'],
            savedAt: now,
        },
        {
            id: 'seed_business_analysis',
            name: 'Business analysis (starter)',
            kind: 'prompt',
            roleId: 'business-analyst',
            taskId: 'analysis',
            modelId: 'chatgpt',
            parameterValues: { goal: 'Analyze reporting ownership gaps in a mid-size company' },
            sections: {},
            sectionOverrides: {},
            tags: ['seed', 'business', 'prompt'],
            savedAt: now,
        },
        {
            id: 'seed_angular_signals_rule',
            name: 'Angular Signals rule',
            kind: 'rule',
            roleId: 'angular-engineer',
            taskId: 'angular-signals',
            modelId: 'chatgpt',
            parameterValues: {
                goal: 'Enforce Signals and modern Angular syntax in this repo',
            },
            sections: {
                system: 'You write Cursor project rules for Angular codebases.',
                task: 'Create a project rule that requires Angular Signals and modern control flow.',
                output:
                    'Prefer signal(), computed(), effect(); use @if/@for/@switch; avoid NgModules when standalone is available; no legacy patterns unless justified.',
            },
            sectionOverrides: {},
            tags: ['seed', 'rule', 'angular'],
            savedAt: now,
        },
        {
            id: 'seed_debug_agent_task',
            name: 'Debug agent task',
            kind: 'agent-task',
            roleId: 'debug-engineer',
            taskId: 'debug-investigate',
            modelId: 'chatgpt',
            parameterValues: {
                goal: 'Investigate a failing feature and propose a minimal fix',
            },
            sections: {
                system: 'You plan agent tasks for debugging.',
                task: 'Produce a structured agent task for debugging with repro, hypotheses, and verification.',
                output: 'Include Goal, Context, Constraints, Steps, and Done when checkboxes.',
            },
            sectionOverrides: {},
            tags: ['seed', 'agent-task', 'debug'],
            savedAt: now,
        },
        ...mailSeedTemplates(now),
    ];

    saveTemplates(seeds, 'manual');
    localStorage.setItem(SEED_FLAG_KEY, '1');
    localStorage.setItem(MAIL_SEED_FLAG_KEY, '1');
    return true;
}

/**
 * Append mail/text starter templates for existing users (once).
 * @returns {boolean}
 */
export function ensureMailStarterTemplates() {
    if (localStorage.getItem(MAIL_SEED_FLAG_KEY) === '1') {
        return false;
    }
    const loaded = loadTemplates();
    /** @type {import('./template-store.js').UserTemplate[]} */
    const existing =
        loaded && 'data' in loaded
            ? /** @type {import('./template-store.js').UserTemplate[]} */ (loaded.data)
            : [];
    const existingIds = new Set(existing.map((t) => t.id));
    const now = new Date().toISOString();
    const toAdd = mailSeedTemplates(now).filter((t) => !existingIds.has(t.id));
    if (toAdd.length === 0) {
        localStorage.setItem(MAIL_SEED_FLAG_KEY, '1');
        return false;
    }
    saveTemplates([...toAdd, ...existing], 'manual');
    localStorage.setItem(MAIL_SEED_FLAG_KEY, '1');
    return true;
}

/**
 * @param {string} now
 * @returns {import('./template-store.js').UserTemplate[]}
 */
function mailSeedTemplates(now) {
    return [
        {
            id: 'seed_mail_inbox_triage',
            name: 'Posteingang sichten (Starter)',
            kind: 'prompt',
            roleId: 'correspondence-writer',
            taskId: 'email-inbox-triage',
            modelId: 'chatgpt',
            parameterValues: {
                goal: 'Sichte alle Mails: gruppiere nach Thema, markiere Dringlichkeit (P0/P1/P2/P3), wer antworten sollte, und was ignoriert/archiviert werden kann.',
                writingLanguage: 'de',
                incomingMessage:
                    '[Hier alle Mails / Threads einfügen — bei personenbezogenen Daten vorher AI Sanitizer nutzen]',
            },
            sections: {},
            sectionOverrides: {},
            tags: ['seed', 'mail', 'triage'],
            savedAt: now,
        },
        {
            id: 'seed_mail_top_problems',
            name: 'Top-Probleme aus Mails (Starter)',
            kind: 'prompt',
            roleId: 'correspondence-writer',
            taskId: 'email-top-problems',
            modelId: 'chatgpt',
            parameterValues: {
                goal: 'Leite die Top 3 Probleme aus den Mails ab. Pro Problem: Kurzbeschreibung, Belege, Impact, nächster Schritt.',
                writingLanguage: 'de',
                incomingMessage: '[Mail-Stapel einfügen]',
            },
            sections: {},
            sectionOverrides: {},
            tags: ['seed', 'mail', 'problems'],
            savedAt: now,
        },
        {
            id: 'seed_mail_action_items',
            name: 'To-dos aus Mails (Starter)',
            kind: 'prompt',
            roleId: 'correspondence-writer',
            taskId: 'email-action-items',
            modelId: 'chatgpt',
            parameterValues: {
                goal: 'Extrahiere alle Action Items: Aufgabe | Owner | Frist | Quelle | Priorität.',
                writingLanguage: 'de',
                incomingMessage: '[Mails einfügen]',
            },
            sections: {},
            sectionOverrides: {},
            tags: ['seed', 'mail', 'actions'],
            savedAt: now,
        },
        {
            id: 'seed_mail_status_digest',
            name: 'Status-Digest Führung (Starter)',
            kind: 'prompt',
            roleId: 'correspondence-writer',
            taskId: 'email-status-digest',
            modelId: 'chatgpt',
            parameterValues: {
                goal: 'Status-Digest: Was läuft, was blockiert, was braucht Entscheidung, Risiken. Max. eine Seite.',
                tone: 'concise',
                writingLanguage: 'de',
                messageLength: 'short',
                incomingMessage: '[Relevante Mails der Woche einfügen]',
            },
            sections: {},
            sectionOverrides: {},
            tags: ['seed', 'mail', 'digest'],
            savedAt: now,
        },
        {
            id: 'seed_text_exec_brief',
            name: 'Exec-Brief (Starter)',
            kind: 'prompt',
            roleId: 'technical-writer',
            taskId: 'text-executive-brief',
            modelId: 'chatgpt',
            parameterValues: {
                goal: 'Exec-Brief: Kontext in 2 Sätzen, Empfehlung, 3 Gründe, Risiken, benötigte Entscheidung.',
                writingLanguage: 'de',
                documents: '[Quelltext / Notizen einfügen]',
            },
            sections: {},
            sectionOverrides: {},
            tags: ['seed', 'writing', 'exec'],
            savedAt: now,
        },
    ];
}
