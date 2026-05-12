import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/sass/app.scss",
                "resources/js/app.js",
                "resources/css/style.css",
                "resources/js/translate.js",
                "resources/css/home.css",
                "resources/js/vocabulary-modal.js",
                "resources/js/quiz.js",
                "resources/js/timezone.js",
                "resources/js/report.js",
                "resources/css/profile.css"
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: "public/build", // ← ここに追加
    },
});
