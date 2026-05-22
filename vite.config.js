import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                'resources/views/**',
                'app/Livewire/**',
                'app/Http/Controllers/**',
            ],
        }),
    ],
    server: {
        host: '0.0.0.0',   // utile sur NixOS / VMs
        port: 5173,
    },
});
