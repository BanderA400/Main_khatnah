import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Tajawal', ...defaultTheme.fontFamily.sans],
                serif: ['Amiri', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                primary: {
                    50: '#f5f3ff',
                    100: '#ede9fe',
                    200: '#ddd6fe',
                    300: '#c4b5fd',
                    400: '#a78bfa',
                    500: '#8b5cf6',
                    600: '#7c3aed',
                    700: '#6d28d9',
                },
                secondary: {
                    50: '#ecfdf5',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669',
                },
                accent: {
                    50: '#fffbeb',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                },
            },
        },
    },

    plugins: [forms],
};
