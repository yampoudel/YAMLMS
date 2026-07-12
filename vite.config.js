import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Keep these temporarily for legacy Blade views still require them,
                // but will delete them once those specific modules are fully converted to Vue.
                'resources/js/course/course-validation.js',
                'resources/js/user/user-validation.js',
                'resources/js/lesson/lesson-validation.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            // Fix: Standardize on the proper Vue package and add the '@' shortcut for our files
            'vue': 'vue',
            '@': '/resources/js',
        },
    },
});
