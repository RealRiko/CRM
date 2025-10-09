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
      },
    },
  },

  plugins: [forms, typography],
}

