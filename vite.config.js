import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/unit-scanner.js',
                'resources/css/filament/admin/theme.css',
                'resources/css/filament/central/theme.css'
            ],
            refresh: true,
        }),
    ],
    build: {
        // Windows/Docker bind-mount can't rmSync public/build/assets (EPERM) — overwrite
        // in place instead of emptying the dir first. Old hashed files linger harmlessly;
        // the manifest always points to the fresh build.
        emptyOutDir: false,
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        origin: 'http://localhost:5173',
    },
});
