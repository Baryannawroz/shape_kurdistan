import forms from '@tailwindcss/forms';
import baseConfig from './tailwind.config.js';

/** @type {import('tailwindcss').Config} */
export default {
    ...baseConfig,
    content: [
        './resources/views/layouts/guest.blade.php',
        './resources/views/auth/**/*.blade.php',
        './resources/views/components/**/*.blade.php',
    ],
    plugins: [forms],
};
