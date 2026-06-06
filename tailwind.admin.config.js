import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import baseConfig from './tailwind.config.js';

/** @type {import('tailwindcss').Config} */
export default {
    ...baseConfig,
    content: [
        './resources/views/admin.blade.php',
        './resources/views/partials/vite-admin-preloads.blade.php',
        './resources/js/Pages/Admin/**/*.vue',
        './resources/js/Layouts/AdminLayout.vue',
        './resources/js/Components/Admin/**/*.vue',
    ],
    plugins: [forms, typography],
};
