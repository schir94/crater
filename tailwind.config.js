const plugin = require('tailwindcss/plugin')

module.exports = {
  purge: [
    './resources/views/**/*.php',
    './resources/assets/js/**/*.js',
    './resources/assets/js/**/*.vue',
    './resources/assets/sass/**/*.scss',
    './node_modules/\\@bytefury/spacewind/src/**/*.js',
    './node_modules/\\@bytefury/spacewind/src/**/*.vue',
    './node_modules/\\@bytefury/spacewind/plugin/**/*.js',
    'flatpickr/**/*.js',
    './public/js/pace/**/*.js'
  ],
  theme: {
    extend: {
      fontFamily: {
        base: ['Poppins', 'sans-serif']
      },
      colors: {
        primary: {
          50: '#E52444',
          100: '#DB1A3A',
          200: '#C91835',
          300: '#B71530',
          400: '#A2132B',
          500: '#921127',
          600: '#800F22',
          700: '#6E0D1D',
          800: '#5B0B18',
          900: '#490914'
        },
        black: '#040405'
      },
      spacing: {
        7: '1.75rem',
        9: '2.25rem',
        72: '18rem',
        80: '20rem',
        88: '22rem',
        96: '24rem'
      },
      screens: {
        xxl: '1440px'
      }
    }
  },
  variants: {
    textColor: ['responsive', 'hover', 'focus', 'active', 'visited'],
    borderColor: ['responsive', 'hover', 'focus', 'active', 'focus-within'],
    borderRadius: ['responsive', 'hover', 'first', 'last'],
    boxShadow: ['responsive', 'hover', 'focus', 'active', 'group-hover'],
    borderStyle: ['responsive', 'hover', 'first', 'last'],
    borderWidth: ['responsive', 'last', 'hover', 'focus']
  },
  plugins: [
    require('@bytefury/spacewind/plugin'),

    plugin(({ config, addBase }) => {
      let craterDefaultTypography = {
        fontFamily: config('theme.fontFamily.base')
      }
      addBase({
        '.h1': {
          ...craterDefaultTypography
        },
        '.h2': {
          ...craterDefaultTypography
        },
        '.h3': {
          ...craterDefaultTypography
        },
        '.h4': {
          ...craterDefaultTypography
        },
        '.h5': {
          ...craterDefaultTypography
        },
        '.h6': {
          ...craterDefaultTypography
        },
        '.page-title': {
          ...craterDefaultTypography
        },
        '.section-title': {
          ...craterDefaultTypography
        }
      })
    })
  ]
}
