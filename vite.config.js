import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import legacy from '@vitejs/plugin-legacy';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    build: {
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
        legacy({
            targets: ['defaults', 'not IE 11', 'chrome >= 49', 'safari >= 10', 'ios >= 10', 'android >= 5'],
            additionalLegacyPolyfills: ['regenerator-runtime/runtime'],
        }),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
    },
});
