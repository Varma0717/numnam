/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
                heading: ['Poppins', 'sans-serif'],
                body: ['Inter', 'sans-serif'],
            },
            colors: {
                numnam: {
                    50: '#fff0f5',
                    100: '#ffd6e5',
                    200: '#ffafc8',
                    300: '#ff8aab',
                    400: '#ff6b8a',
                    500: '#f54d71',
                    600: '#dd3259',
                    700: '#c01e46',
                    800: '#9c112e',
                    900: '#7a0a21',
                },
                kids: {
                    coral: '#FF6B8A',
                    yellow: '#FFD93D',
                    mint: '#4ECDC4',
                    lavender: '#9B8EC4',
                    sky: '#60D5D8',
                    peach: '#FFB347',
                    green: '#6BCB77',
                    cream: '#FFFDF8',
                    pink: '#FFF0F5',
                    blue: '#F0F9FF',
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
                candy: {
                    bg: '#FFFDF8',
                    text: '#2D2D3F',
                    muted: '#5e6478',
                },
            },
            boxShadow: {
                soft: '0 12px 36px -18px rgba(17, 24, 39, 0.35)',
                kids: '0 8px 28px rgba(255, 107, 138, 0.20)',
                'kids-yellow': '0 8px 28px rgba(255, 217, 61, 0.30)',
                'candy-coral': '0 4px 0 #dd3259',
                'candy-yellow': '0 4px 0 #e6c235',
                'candy-mint': '0 4px 0 #3db8b0',
                'candy-lavender': '0 4px 0 #7B6BA8',
            },
            borderRadius: {
                'candy': '1.5rem',
                'candy-lg': '2rem',
                'candy-xl': '2.5rem',
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
};
