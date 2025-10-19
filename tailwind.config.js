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
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                lightgreen: {
                    50: '#f9fbf9',
                    100: '#f4f8f4',
                    200: '#e5f5e8',
                    400: '#9dd8b6',
                    500: '#6fcf97',
                    600: '#3ea76a',
                    800: '#2d3a32',
                },
            },
        },
    },

    plugins: [forms],
};
