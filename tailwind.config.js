import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#22c55e',
                    dark: '#16a34a',
                    light: '#4ade80',
                    soft: '#dcfce7',
                },
                clover: {
                    bg: '#f6f6f3',
                    surface: '#ffffff',
                    border: '#e8e8e4',
                    muted: '#6b7280',
                    dark: '#0c0c0c',
                    ink: '#171717',
                },
                secondary: {
                    DEFAULT: '#57534e',
                },
            },
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                arabic: ['Cairo', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(14px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                marquee: {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                shimmer: {
                    '100%': { transform: 'translateX(100%)' },
                },
            },
            animation: {
                'fade-up': 'fade-up 0.65s ease-out both',
                marquee: 'marquee 32s linear infinite',
                shimmer: 'shimmer 1.8s ease-in-out infinite',
            },
            boxShadow: {
                glow: '0 0 0 1px rgba(34, 197, 94, 0.2), 0 20px 55px -22px rgba(22, 163, 74, 0.25)',
                'card-soft':
                    '0 1px 2px rgba(12, 12, 12, 0.04), 0 12px 40px -16px rgba(12, 12, 12, 0.08)',
                'nav-pill': '0 1px 2px rgba(12, 12, 12, 0.06), 0 8px 32px -8px rgba(12, 12, 12, 0.12)',
                'clover-lg': '0 24px 64px -24px rgba(12, 12, 12, 0.18)',
            },
            borderRadius: {
                clover: '1.25rem',
            },
            backgroundImage: {
                'mesh-warm':
                    'radial-gradient(880px 400px at 8% -8%, rgba(34, 197, 94, 0.08), transparent 56%), radial-gradient(720px 380px at 92% 4%, rgba(22, 163, 74, 0.06), transparent 52%)',
            },
        },
    },

    plugins: [forms, typography],
};
