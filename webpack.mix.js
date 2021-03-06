require('laravel-mix-versionhash');

const mix = require('laravel-mix');
const path = require('path');
const WebpackNotifierPlugin = require('webpack-notifier');

const dev = !mix.inProduction();
const sassOptions = dev ? null : { sassOptions: { outputStyle: 'compressed' } };

/**
 * Copy Static Asset Resources
 */
mix.copyDirectory('resources/assets/js/plugins.js', 'public/js/plugins.js');
mix.copyDirectory('resources/assets/js/home.plugins.js', 'public/js/home.plugins.js');
mix.copyDirectory('resources/assets/maps', 'public/maps');
mix.copyDirectory('resources/assets/pdf', 'public/pdf');
mix.copyDirectory('resources/assets/fonts', 'public/fonts');
mix.copyDirectory('resources/assets/images', 'public/images');
mix.copyDirectory('resources/assets/san-diego', 'public/san-diego');

/**
 * Configure Web Pack
 */
mix.webpackConfig({
    plugins: [
        new WebpackNotifierPlugin( {
            title: dev ? 'Development' : 'Production',
            alwaysNotify: true,
            contentImage: path.join(__dirname, 'docs/img/icon.png')
        })
    ],
    devtool: dev ? 'source-map' : false
});

/**
 * Compile New Static Assets
 */
mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/home.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css', sassOptions)
    .options({
        processCssUrls: false
    })
    .disableNotifications()
    .sourceMaps(dev)
    .versionHash();
