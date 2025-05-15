import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#5A67D8',
                primaryLight: '#E2E8F0',
                primaryLighter: '#F7F8FC',
                primaryDark: '#2B2D42',
            },
        },
    },
    variants: {
        extend: {
          backgroundColor: ['checked', 'disabled'],
          opacity: ['dark'],
          overflow: ['hover'],
        },
      },
    plugins: [],
};
