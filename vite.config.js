import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    tailwindcss(),
    vue(),
  ],
  resolve: {
    alias: {
      vue: 'vue',
      '@': '/resources/js',
    },
  },
  server: {
    port: 5173,
    strictPort: false,
    host: true,
    hmr: {
      host: 'localhost',
    },
  },
});
