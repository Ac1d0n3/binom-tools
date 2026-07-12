import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ command }) => ({
    // Relative asset URLs in CSS/JS — one build works for MAMP (/binom-tools/) and prod (/).
    // Laravel @vite resolves entry CSS/JS via asset() + APP_URL at runtime.
    base: command === 'build' ? './' : undefined,
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/playbooks.css',
                'resources/js/playbooks/show.js',
                'resources/js/tools/dbt-governance-macro-generator/index.js',
                'resources/js/tools/pii-recommend-generator/index.js',
                'resources/js/tools/pii-unreviewed-gate-generator/index.js',
                'resources/js/tools/prompt-studio/index.js',
                'resources/js/tools/governance-ai-sanitizer/index.js',
                'resources/js/tools/pii-policy-generator/index.js',
                'resources/js/tools/schema-yml-editor/index.js',
                'resources/js/tools/dbt-dq-macro-generator/index.js',
                'resources/js/tools/dbt-dq-rules-generator/index.js',
                'resources/js/tools/dbt-dq-history-generator/index.js',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
}));
