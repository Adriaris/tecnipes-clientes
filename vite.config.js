import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/sass/app.scss",
                "resources/js/app.js",
                "resources/js/admin.js",
                "resources/js/archivos.js",
                "resources/js/basculas.js",
                "resources/js/clientes.js",
                "resources/js/imagenes.js",
                "resources/js/videos.js",
                "resources/js/papelera.js",
            ],
            refresh: true,
        }),
    ],
});
