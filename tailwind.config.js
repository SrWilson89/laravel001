import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    
    // 1. SAFELIST: Para asegurarnos de que estas clases se mantengan aunque no se usen en el HTML base.
    safelist: [
        'bg-default', 'bg-red', 'bg-orange-red', 'bg-orange', 'bg-yellow-orange', 
        'bg-yellow', 'bg-yellow-green', 'bg-green', 'bg-blue-green', 'bg-blue', 
        'bg-blue-violet', 'bg-violet', 'bg-red-violet',
    ],

    theme: {
        extend: {
            // 2. EXTEND COLORS: Aquí definimos el valor hexadecimal para cada clase personalizada (bg-nombre)
            colors: {
                'default': '#f8fafc', // Gris muy claro
                'red': '#fecaca',     // Rojo-200
                'orange-red': '#fed7aa', // Naranja-200
                'orange': '#fbbf24',    // Ámbar-400
                'yellow-orange': '#fde047', // Amarillo-300
                'yellow': '#fef08a',    // Amarillo-200
                'yellow-green': '#d9f99d', // Lima-200
                'green': '#86efad',    // Verde-300
                'blue-green': '#67e8f9', // Cian-300
                'blue': '#93c5fd',     // Azul-300
                'blue-violet': '#a78bfa', // Violeta-400
                'violet': '#d8b4fe',    // Púrpura-300
                'red-violet': '#f0abfc', // Magenta-300
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};