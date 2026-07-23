import { loadTemplates, saveTemplates } from './storage.js';

const SEED_FLAG_KEY = 'binom-tools-prompt-studio-templates-seeded';

/**
 * Seed starter templates once when the user library is empty.
 * @returns {boolean} true if seeds were written
 */
export function ensureStarterTemplates() {
    if (localStorage.getItem(SEED_FLAG_KEY) === '1') {
        return false;
    }
    const loaded = loadTemplates();
    const existing = loaded && 'data' in loaded ? loaded.data : [];
    if (existing.length > 0) {
        localStorage.setItem(SEED_FLAG_KEY, '1');
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
    ];

    saveTemplates(seeds, 'manual');
    localStorage.setItem(SEED_FLAG_KEY, '1');
    return true;
}
