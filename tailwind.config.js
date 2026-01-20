import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

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
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Oswald', 'sans-serif'], // Fuente para títulos agresivos
                body: ['Inter', 'sans-serif'],
            },
            colors: {
                // Colores extraídos de tu diseño React
                primary: '#e60000', // Rojo intenso
                background: '#09090b', // Negro casi puro
                surface: '#18181b', // Gris oscuro para tarjetas
            },
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
            }
        },
    },

    plugins: [forms],
};