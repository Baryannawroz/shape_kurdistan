import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    build: {
        target: ['es2015', 'chrome63', 'safari11.1', 'firefox60', 'edge79'],
        cssTarget: ['chrome63', 'safari11.1'],
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (! id.includes('node_modules')) {
                        return undefined;
                    }

                    if (id.includes('@tinymce')) {
                        return 'vendor-tinymce';
                    }

                    if (id.includes('@heroicons')) {
                        return 'vendor-icons';
                    }

                    if (id.includes('vuedraggable')) {
                        return 'vendor-draggable';
                    }

                    if (id.includes('ziggy-js')) {
                        return 'vendor-ziggy';
                    }

                    if (id.includes('vue') || id.includes('@inertiajs') || id.includes('@vue')) {
                        return 'vendor-vue';
                    }
                },
            },
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/admin.css',
                'resources/js/admin.js',
                'resources/css/auth.css',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
    },
});
