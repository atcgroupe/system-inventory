const colors = require('tailwindcss/colors')

module.exports = {
  content: ["./templates/**/*.html.twig"],
  theme: {
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      black: colors.black,
      white: colors.white,
      gray: colors.gray,
      emerald: colors.emerald,
      indigo: colors.indigo,
      yellow: colors.yellow,
      blue: colors.blue,
      red: colors.red,
    },
    extend: {},
  },
  plugins: [],
}
