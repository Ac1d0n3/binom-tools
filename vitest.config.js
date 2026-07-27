import { defineConfig } from 'vitest/config';

export default defineConfig({
    test: {
        environment: 'jsdom',
        include: [
            'resources/js/**/*.test.js',
            'modules/**/js/**/*.test.js',
        ],
    },
});
