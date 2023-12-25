import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig(({ mode }) => {
    const envDir = "../../../";

    Object.assign(process.env, loadEnv(mode, envDir));

    return {
        build: {
            emptyOutDir: true,
        },

        envDir,
        server: {
            hmr: {
                host: 'localhost',
            },
        },
        plugins: [
            laravel({
                hotFile: "../../../public/shop-jedu-vite.hot",
                publicDirectory: "../../../public",
                buildDirectory: "themes/shop/jedu/build",
                input: [
                    "assets/css/app.css",
                    "assets/js/app.js",
                ],
                refresh: true,
            }),
        ],

        // experimental: {
        //     renderBuiltUrl(filename, { hostId, hostType, type }) {
        //         if (hostType === "css") {
        //             return path.basename(filename);
        //         }
        //     },
        // },
    };
});
