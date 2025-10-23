import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/changeLangForm.js',
                'resources/js/darkMode.js',
                'resources/js/modalUser.js',
                'resources/js/printTicket.js',
                'resources/js/scannerQr.js',
                'resources/js/submissionCards.js'
            ],
            refresh: true,
        }),
    ],
})
