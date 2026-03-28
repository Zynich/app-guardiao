import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'guardiao-dark': '#0a0a0a',
                'guardiao-card': '#161616',
                'guardiao-blue': '#93c5fd',
                'guardiao-primary': '#3b82f6',
                'guardiao-brand-start': '#186073',
                'guardiao-brand-end': '#17A2B8',
            },
                keyframes: {
                'progress-shrink': {
                    '0%': { transform: 'scaleX(1)' },
                    '100%': { transform: 'scaleX(0)' },
                }
            },
            animation: {
                'progress-shrink': 'progress-shrink 5s linear forwards',
            },
        },
    },

    plugins: [forms],
};
