import { fileURLToPath, URL } from "url";

import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import svgLoader from "vite-svg-loader";
import tailwindcss from '@tailwindcss/vite'
import Components from 'unplugin-vue-components/vite';
import {PrimeVueResolver} from '@primevue/auto-import-resolver';

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: true,
                },
            },
        }),
        tailwindcss(),
        Components({
            resolvers: [
                PrimeVueResolver()
            ]
        }),
        svgLoader(),
    ],
    resolve: {
        alias: {
            "~": fileURLToPath(new URL("./resources", import.meta.url)),
        },
    },
    server: {
        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        hmr: {
            host: "localhost",
        },
    },
});
