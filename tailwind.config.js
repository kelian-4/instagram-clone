import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/livewire/src/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Livewire/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ig: {
                    bg:      '#000000',
                    border:  '#262626',
                    hover:   '#1a1a1a',
                    muted:   '#a8a8a8',
                    blue:    '#0095f6',
                    red:     '#ff3040',
                    badge:   '#ff3040',
                },
            },
        },
    },
    plugins: [],
};
