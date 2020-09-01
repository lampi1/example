module.exports = {
  theme: {
    debugScreens: {
      position: ['bottom', 'right']
    },
    fontFamily: {
      body: ['Gotham', 'sans-serif'],
      logo: ['Avenir Next Condensed']
    },
    screens: {
      sm: '640px',
      // => @media (min-width: 640px) { ... }

      md: '768px',
      // => @media (min-width: 768px) { ... }

      lg: '1024px',
      // => @media (min-width: 1024px) { ... }

      xl: '1280px'
      // => @media (min-width: 1280px) { ... }
    },
    extend: {
      spacing: {
        '28': '7rem',
        '72': '18rem',
        '84': '21rem',
        '96': '24rem',
        '98': '27rem',
        '100': '30rem',
        '101': '35rem',
        '102': '40rem',
        '102.5': '44rem',
        '103': '45rem',
        '104': '48rem',
        '105': '55rem',
        '106': '60rem',
        '107': '65rem',
        '108': '70rem',
        '109': '75rem',
        '110': '80rem',
        '111': '85rem',
        '112': '90rem',
        '113': '95rem',
        '114': '100rem',
        '115': '105rem',
        '116': '110rem',
        '117': '115rem',
        '118': '120rem',
        '119': '125rem',
        '120': '130rem',
        '121': '135rem',
        '122': '140rem',
        '123': '145rem',
        '124': '150rem',
        '125': '155rem'
      },
      fontSize: {
        '6.5xl': '5.5rem',
        '7xl': '6rem',
        '8xl': '7rem'
      },
      borderWidth: {
        default: '1px',
        '0': '0',
        '2': '2px',
        '4': '4px'
      },
      colors: {
        first: '#EBB264',
        filter: '#EBEBEB',
        'first-light': '#EFD3AE',
        header: '#1D1D1D'
      }
    }
  },
  plugins: [
    require('tailwindcss-debug-screens'),
    require('tailwind-percentage-heights-plugin')(),
    require('@tailwindcss/custom-forms')
  ]
}
