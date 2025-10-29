import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/login.css',
        'resources/css/register.css',
        'resources/css/dashboard.css',
        'resources/css/landing.css',
        'resources/js/lockout-countdown.js',
        'resources/js/email-sync.js',
        'resources/js/navbar.js'
      ],
      refresh: true,
    }),
    tailwindcss(),
  ],
});
