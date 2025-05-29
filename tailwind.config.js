/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/Views/**/*.{html,js,twig}", // Paths to your template files
    "./public/scripts/**/*.js" // Paths to any JS files that might include Tailwind classes
  ],
  theme: {
    extend: {},
  },
  plugins: [],
  // Important for coexistence with existing styles:
  // Consider not using preflight if it causes too many conflicts initially.
  // corePlugins: {
  //  preflight: false,
  // }
}
