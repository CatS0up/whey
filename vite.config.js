import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Styles
                'resources/css/app.css',
                'node_modules/filepond/dist/filepond.min.css',
                'node_modules/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css',
                'node_modules/tippy.js/dist/tippy.css',

                // Scripts
                'resources/js/app.js',
                'node_modules/filepond/dist/filepond.min.js',
                'node_modules/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js',
                'node_modules/tippy.js/dist/tippy-bundle.umd.js',
            ],
            refresh: true,
        }),
    ],
});
