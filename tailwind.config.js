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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};

/* ============================================
   TAILWIND CONFIG EXTENSIONS
   Add these to your tailwind.config.js:

   theme: {
     extend: {
       colors: {
         primary: {
           50: '#e6f0ff',
           100: '#cce0ff',
           200: '#99c2ff',
           300: '#66a3ff',
           400: '#3385ff',
           500: '#0066ff',
           600: '#0052cc',
           700: '#003d99',
           800: '#002966',
           900: '#001433',
         }
       }
     }
   }
   ============================================ */