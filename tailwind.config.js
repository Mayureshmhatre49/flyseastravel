/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './app/Livewire/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        'fs-primary':       '#0B6E5A',
        'fs-primary-dark':  '#084839',
        'fs-primary-light': '#E8F3F0',
        'fs-primary-tint':  '#F4FAF8',
        'fs-accent':        '#E8A33A',
        'fs-accent-dark':   '#B5781F',
        'fs-accent-light':  '#FCF3E2',
        'fs-whatsapp':      '#25D366',
        'fs-whatsapp-dark': '#128C7E',
        'fs-ink':           '#0F1A1F',
        'fs-ink-muted':     '#4A5963',
        'fs-ink-soft':      '#7C8A93',
        'fs-ink-faint':     '#AFB8BE',
        'fs-bg':            '#FFFFFF',
        'fs-bg-soft':       '#F8FAF9',
        'fs-bg-cream':      '#FBF9F4',
        'fs-line':          '#E5E9EC',
        'fs-line-soft':     '#F1F4F6',
      },
      fontFamily: {
        display: ['Fraunces', 'Georgia', 'serif'],
        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
      },
      borderRadius: {
        'fs-sm':   '6px',
        'fs-md':   '10px',
        'fs-lg':   '16px',
        'fs-xl':   '24px',
        'fs-pill': '999px',
      },
      boxShadow: {
        'fs-sm': '0 1px 2px rgba(15,26,31,.04), 0 1px 3px rgba(15,26,31,.06)',
        'fs-md': '0 4px 12px rgba(15,26,31,.06), 0 2px 4px rgba(15,26,31,.04)',
        'fs-lg': '0 12px 32px rgba(15,26,31,.08), 0 4px 8px rgba(15,26,31,.04)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
