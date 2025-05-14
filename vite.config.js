import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command }) => ({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],


  server: command === 'serve'
    ? {
        hmr: {
          host: 'localhost',
          port: 8080,
        },
      }
    : false,
}));

