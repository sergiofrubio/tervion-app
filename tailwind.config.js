/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/Views/**/*.php",
    "./src/Templates/**/*.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
      colors: {
        primary: {
          50: '#f0f5ff',
          100: '#d9e6ff',
          200: '#bacfff',
          300: '#91b1ff',
          400: '#5e88ff',
          500: '#0052d9', // Royal Blue (from logo)
          600: '#0040b3',
          700: '#00308c',
          800: '#002266',
          900: '#001640',
        },
        indigo: {
          50: '#ecf9ff',
          100: '#d9f1ff',
          200: '#bde7ff',
          300: '#8fd7ff',
          400: '#4cbaff',
          500: '#009eff', // Cyan/Light Blue (from logo)
          600: '#007ee6',
          700: '#0063b8',
          800: '#004f99',
          900: '#004280',
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
