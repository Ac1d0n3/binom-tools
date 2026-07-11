import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode, command }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const appUrl = env.APP_URL ?? '';
    const subdirectoryBase = appUrl.includes('/binom-tools') ? '/binom-tools/build/' : '/build/';

    return {
        base: command === 'build' ? subdirectoryBase : undefined,
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
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
