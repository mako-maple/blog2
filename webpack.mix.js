let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .disableNotifications()
    .js('resources/assets/js/app.js', 'public/js')
    .extract(['vue'])
//    .sourceMaps()
    .sass('resources/assets/sass/app.scss', 'public/css')
    .options({
        postCss: [
            require('autoprefixer')
        ],
        polyfills: [
            'Promise'
        ]
    });

if (mix.config.inProduction) {
    mix.version();
//} else {
//    mix.browserSync({
//        proxy:     process.env.MIX_DEV_SERVER,
//        startPath: '/'
//    });
}
