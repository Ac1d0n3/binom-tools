import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

function resolveBuildBase(appUrl, explicitBase) {
    const trimmed = explicitBase?.trim();

    if (trimmed) {
        return trimmed.endsWith('/') ? trimmed : `${trimmed}/`;
    }

    if (appUrl) {
        try {
            const pathname = new URL(appUrl).pathname.replace(/\/$/, '');

            if (pathname && pathname !== '/') {
                return `${pathname}/build/`;
            }
        } catch {
            // Ignore invalid APP_URL values and fall back to root build path.
        }
    }

    return '/build/';
}

export default defineConfig(({ mode, command }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const appUrl = env.APP_URL ?? '';
    const buildBase = resolveBuildBase(appUrl, env.VITE_BUILD_BASE);

    return {
        base: command === 'build' ? buildBase : undefined,
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
                    'resources/js/tools/governance-ai-sanitizer/index.js',
                    'resources/js/tools/pii-policy-generator/index.js',
                    'resources/js/tools/schema-yml-editor/index.js',
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
    };
});
