import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],

  darkMode: 'class',

  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        primary: {
          light: '#94a3a3',
          DEFAULT: '#475d5b', // Sleek dark teal-gray
          dark: '#2f3f3d',
        },
        background: {
          light: '#f7f8f9',
          dark: '#121212',
        },
        surface: {
          light: '#ffffff',
          dark: '#1f1f1f',
        },
        // === SIENNA AMBER THEME COLOR ADDED HERE ===
        sienna: {
          DEFAULT: '#CA8A04', // Sienna Amber: Primary color
          dark: '#A16207',    // Darker shade for hover states
          light: '#FDE68A',   // Lightest shade for backgrounds (e.g., hover:bg-sienna-light/50)
        }
        // ==========================================
      },
    },
  },

  plugins: [forms, typography],
}