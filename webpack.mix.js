const path = require('path');
const mix = require('laravel-mix');

require('laravel-mix-merge-manifest');
require('laravel-mix-clean');

const prodPublicPath = path.join('publishable', 'assets');
const devPublicPath = path.join('public');
const publicPath = mix.inProduction() ? prodPublicPath : devPublicPath;

console.log((`Assets will be published in: ${publicPath}`));

const assetsPath = path.join('Resources', 'assets');
const jsPath = path.join(assetsPath, 'js');

mix.js(path.join(jsPath, 'slider.js'), 'public/js').sourceMaps();
mix
    .setPublicPath(publicPath)

    .js(path.join(jsPath, 'app-core.js'), 'js/velocity-core.js')
    .js(path.join(jsPath, 'app.js'), 'js/velocity.js')
    .vue()
    .alias({
        '@Components': path.join(jsPath, 'UI', 'components')
    })
    .extract({
            to: `/js/components.js`,
            test(mod) {
                return /(component|style|loader|node)/.test(mod.nameForCondition())
            }
        }
    )

    .clean({
        // enable `dry` before adding new paths:
        // dry: true,
        cleanOnceBeforeBuildPatterns: [
            'js/**/*',
            'mix-manifest.json',
        ]
    })

    .options({
        processCssUrls: false,
        clearConsole: mix.inProduction()
    })

    .disableNotifications()
    .mergeManifest()
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}
mix.sass('resources/assets/sass/app.scss', 'public/css').version();

mix.sass('resources/assets/sass/admin/app.scss', 'public/css/admin-app.css').version();
mix.copy(
    'node_modules/@fortawesome/fontawesome-free/webfonts',
    'public/webfonts'
);
mix.copy('node_modules/@splidejs/splide/dist/css/splide.min.css', 'public/css');
mix.combine([
    'node_modules/vue-slick-carousel/dist/vue-slick-carousel.css',
    'node_modules/vue-slick-carousel/dist/vue-slick-carousel-theme.css'
], 'public/css/slick.css');
