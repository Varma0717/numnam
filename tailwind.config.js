/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                numnam: {
                    50: '#fff8f1',
                    100: '#feecd8',
                    200: '#fcd5aa',
                    300: '#f9b977',
                    400: '#f59a4c',
                    500: '#f2812d',
                    600: '#e36521',
                    700: '#bc4b1d',
                    800: '#963c20',
                    900: '#79331d',
                },
            },
            boxShadow: {
                soft: '0 12px 36px -18px rgba(17, 24, 39, 0.35)',
            },
        },
    },
    plugins: [],
};
