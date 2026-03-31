/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', 'sans-serif'],
            },
            colors: {
                numnam: {
                    50: '#fff5f8',
                    100: '#ffe2ec',
                    200: '#fcc3d4',
                    300: '#fea0b4',
                    400: '#fe7d94',
                    500: '#fd6772',
                    600: '#fc5d4d',
                    700: '#e04030',
                    800: '#bc2e1f',
                    900: '#961e12',
                },
                pastel: {
                    pink: '#fe7d94',
                    yellow: '#fecd26',
                    lavender: '#b3b7ec',
                    green: '#4c760e',
                    coral: '#fc5d4d',
                    cyan: '#60d5d8',
                    mint: '#8cd9bf',
                    cream: '#f1dbc0',
                },
            },
            boxShadow: {
                soft: '0 12px 36px -18px rgba(17, 24, 39, 0.35)',
            },
        },
    },
    plugins: [],
};
