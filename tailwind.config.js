const plugin = require('tailwindcss/plugin');

module.exports = {
    content: [
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {},
    variants: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio')
    ],
}
