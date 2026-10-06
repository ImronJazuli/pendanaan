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
            colors: {
                primary: {
                    DEFAULT: '#087F5B',
                    hover: '#066A4C',
                    active: '#05563E',
                    subtle: '#E6F4EF',
                    50: '#F0F9F6',
                    100: '#E6F4EF',
                    600: '#087F5B',
                    700: '#066A4C',
                    800: '#05563E',
                },
                secondary: {
                    DEFAULT: '#123B32',
                    hover: '#1A5144',
                },
                surface: {
                    DEFAULT: '#FFFFFF',
                    muted: '#EEF3F1',
                },
                tulungagung: {
                    bg: '#F6F8F7',
                    text: '#17211E',
                    muted: '#73817C',
                    border: '#D9E2DE',
                },
                brand: {
                    primary: '#087F5B',
                    'primary-hover': '#066A4C',
                    'primary-active': '#05563E',
                    'primary-subtle': '#E6F4EF',
                    secondary: '#123B32',
                    'secondary-hover': '#1A5144',
                    teal: '#087F5B',
                    'teal-hover': '#066A4C',
                    'teal-active': '#05563E',
                    'teal-subtle': '#E6F4EF',
                    dark: '#123B32',
                    'dark-hover': '#1A5144',
                    bg: '#F6F8F7',
                    surface: '#FFFFFF',
                },
            },
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                heading: ['"Plus Jakarta Sans"', 'sans-serif'],
                display: ['"Plus Jakarta Sans"', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
