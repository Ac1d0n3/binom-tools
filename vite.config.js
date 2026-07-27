import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

/** Prefer swap so icon fonts do not block LCP/FCP (Font Awesome ships with block). */
function fontAwesomeDisplaySwap() {
    return {
        name: 'fontawesome-font-display-swap',
        enforce: 'pre',
        transform(code, id) {
            if (!id.includes('@fortawesome') || !id.includes('.css')) {
                return null;
            }

            return {
                code: code.replaceAll('font-display:block', 'font-display:swap'),
                map: null,
            };
        },
        generateBundle(_options, bundle) {
            for (const chunk of Object.values(bundle)) {
                if (chunk.type === 'asset' && typeof chunk.source === 'string' && chunk.fileName.endsWith('.css')) {
                    chunk.source = chunk.source.replaceAll('font-display:block', 'font-display:swap');
                }
            }
        },
    };
}

export default defineConfig(({ command }) => ({
    // Relative asset URLs in CSS/JS — one build works for MAMP (/binom-tools/) and prod (/).
    // Laravel @vite resolves entry CSS/JS via asset() + APP_URL at runtime.
    base: command === 'build' ? './' : undefined,
    plugins: [
        fontAwesomeDisplaySwap(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'modules/playbooks/css/playbooks.css',
                'modules/playbooks/js/show.js',
                'modules/sprint-planner/css/sprint-planner.css',
                'modules/calendar/js/calendar-public.js',
                'modules/sprint-planner/js/index.js',
                'modules/sprint-planner/js/show.js',
                'modules/sprint-planner/js/people.js',
                'modules/sprint-planner/js/templates.js',
                'modules/sprint-planner/js/create.js',
                'modules/sprint-planner/js/settings.js',
                'modules/governance/js/hub-advisor.js',
                'modules/governance/js/radar.js',
                'modules/governance/js/discovery-canvas.js',
                'modules/admin/css/admin-hub.css',
                'modules/admin/js/admin-hub.js',
                'modules/tools/js/governance-advisory/index.js',
                'modules/tools/js/dbt-governance-macro-generator/index.js',
                'modules/tools/js/pii-recommend-generator/index.js',
                'modules/tools/js/pii-unreviewed-gate-generator/index.js',
                'modules/tools/js/prompt-studio/index.js',
                'modules/tools/js/governance-ai-sanitizer/index.js',
                'modules/tools/js/pii-policy-generator/index.js',
                'modules/tools/js/schema-yml-editor/index.js',
                'modules/tools/js/meta-export-generator/index.js',
                'modules/tools/js/stakeholder-matrix/index.js',
                'modules/tools/js/report-inventory/index.js',
                'modules/tools/js/kpi-definition/index.js',
                'modules/tools/js/bi-python-toolkit/index.js',
                'modules/tools/js/qlik-set-analysis-generator/index.js',
                'modules/tools/js/tableau-calculation-generator/index.js',
                'modules/tools/js/powerbi-dax-generator/index.js',
                'modules/tools/js/architecture-fit/index.js',
                'modules/tools/js/impact-effort/index.js',
                'modules/tools/js/dbt-dq-macro-generator/index.js',
                'modules/tools/js/dbt-dq-rules-generator/index.js',
                'modules/tools/js/dbt-dq-history-generator/index.js',
                'modules/tools/js/fabric-dq-pattern-generator/index.js',
                'modules/tools/js/databricks-dq-pattern-generator/index.js',
                'modules/tools/js/pureview-generator/index.js',
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
