/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/Views/**/*.php",
    "./src/Templates/**/*.php",
    "./public/js/**/*.js",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
      colors: {
        primary: {
          50: '#f0f9fa',
          100: '#d7eff2',
          200: '#b2e0e6',
          300: '#7ecad6',
          400: '#3fabbe',
          500: '#177a8d', // Tervion Teal/Petrol Blue (Exact brand logo core)
          600: '#136778',
          700: '#115463',
          800: '#124552',
          900: '#133a44',
          950: '#09242c',
        },
        indigo: {
          50: '#f0f9fa',
          100: '#d7eff2',
          200: '#b2e0e6',
          300: '#7ecad6',
          400: '#3fabbe',
          500: '#177a8d',
          600: '#136778',
          700: '#115463',
          800: '#124552',
          900: '#133a44',
        },
        purple: {
          50: '#f0fdf4',   // Emerald 50
          100: '#dcfce7',  // Emerald 100
          200: '#bbf7d0',  // Emerald 200
          300: '#86efac',  // Emerald 300
          400: '#4ade80',  // Emerald 400
          500: '#10b981',  // Emerald 500
          600: '#059669',  // Emerald 600
          700: '#047857',  // Emerald 700
          800: '#065f46',  // Emerald 800
          900: '#064e3b',  // Emerald 900
          950: '#022c22',  // Emerald 950
        }
      }
    },
  },
  plugins: [],
}
