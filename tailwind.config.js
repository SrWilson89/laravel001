import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    
    // 1. SAFELIST: Actualizado para usar el prefijo 'custom-'
    safelist: [
        'bg-custom-default', 'bg-custom-red', 'bg-custom-orange-red', 'bg-custom-orange', 'bg-custom-yellow-orange', 
        'bg-custom-yellow', 'bg-custom-yellow-green', 'bg-custom-green', 'bg-custom-blue-green', 'bg-custom-blue', 
        'bg-custom-blue-violet', 'bg-custom-violet', 'bg-custom-red-violet',
    ],

    theme: {
        extend: {
            // 2. EXTEND COLORS: Aquí definimos el valor hexadecimal para cada clase personalizada
            // Usamos 'custom-' como prefijo para evitar conflictos con la paleta por defecto de Tailwind (ej. 'red-600')
            colors: {
                'custom-default': '#f8fafc', // Gris muy claro
                'custom-red': '#fecaca',     // Rojo-200
                'custom-orange-red': '#fed7aa', // Naranja-200
                'custom-orange': '#fbbf24',    // Ámbar-400
                'custom-yellow-orange': '#fde047', // Amarillo-300
                'custom-yellow': '#fef08a',    // Amarillo-200
                'custom-yellow-green': '#d9f99d', // Lima-200
                'custom-green': '#86efad',    // Verde-300
                'custom-blue-green': '#67e8f9', // Cian-300
                'custom-blue': '#93c5fd',     // Azul-300
                'custom-blue-violet': '#a78bfa', // Violeta-400
                'custom-violet': '#d8b4fe',    // Púrpura-300
                'custom-red-violet': '#f0abfc', // Magenta-300
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};