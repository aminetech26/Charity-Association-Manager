/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/**/*.{php,css,js}',
    './admin/**/*.{php,css,js}',   
    './public/**/*.{php,css,js}',  
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#2C5282', // blue-800
          hover: '#2B6CB0',   // blue-700
        },
        secondary: {
          DEFAULT: '#4299E1', // blue-500
          hover: '#63B3ED',   // blue-400
        },
        
        text: {
          primary: '#1A202C',   // gray-900
          secondary: '#718096', // gray-600
        },
        background: {
          light: '#F7FAFC',     // gray-50
        },
        
        // Couleurs d'état
        state: {
          success: '#48BB78',  // green-500
          error: '#E53E3E',    // red-600
          warning: '#ECC94B',  // yellow-400
        }
      }
    },
  },
  plugins: [],
}