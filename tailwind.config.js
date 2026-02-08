module.exports = {
  content: ["./source/**/*.html"],
  theme: {
    extend: {
      fontSize: {
        base: '1rem',
      },
      fontFamily: {
        sans: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', 'sans-serif'],
        mono: ['JetBrainsMono', 'Menlo', 'Monaco', 'Consolas', "Liberation Mono", "Courier New", 'monospace'],
      },
      maxWidth: {
        'notion': '720px',
      },
      colors: {
        // Notion-inspired pure white theme
        'notion-bg': '#FFFFFF',
        'notion-text': '#191919',
        'notion-text-secondary': '#6B6B6B',
        'notion-border': '#E8E8E8',
        'notion-link': '#0077CC',
        'notion-hover': '#F5F5F5',
        // Callout colors
        'notion-yellow': '#FFF8DC',
        'notion-green': '#E8F5E9',
        'notion-blue': '#E3F2FD',
        'notion-purple': '#F3E5F5',
        'notion-pink': '#FCE4EC',
        'notion-red': '#FFEBEE',
        'notion-orange': '#FFF3E0',
        'notion-gray': '#F5F5F5',
        // Legacy colors (kept for compatibility)
        'foundation': 'rgba(25, 25, 28, .3)',
        'baseblack': '#19191c',
        'fbg': 'rgba(25, 25, 28, .1)',
        'hborder': 'rgba(39, 40, 44, .2)',
      },
    },

    screens: {
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
      'smmax': {'max': '550px'}
    }
  },
  plugins: [
    require('tailwindcss'),
    require('autoprefixer'),
  ],
}
