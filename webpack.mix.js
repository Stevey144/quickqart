const mix = require('laravel-mix');

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

mix.js('resources/cp/js/app.js', 'public/cp')
    .sass('resources/cp/sass/app.scss', 'public/cp')
    .js('resources/setup/js/app.js', 'public/setup')
    .sass('resources/setup/sass/app.scss', 'public/setup')
    .js('resources/store/js/app.js', 'public/store')
    .sass('resources/store/sass/app.scss', 'public/store')

    .sass('resources/public/sass/app.scss', 'public/parent/assets/css/main.css')

    .scripts([
        'public/cp/vendor/jquery.min.js',
        'public/cp/vendor/popper.min.js',
        'public/cp/vendor/bootstrap.min.js',
        'public/cp/vendor/simplebar.min.js',
        'public/cp/vendor/dom-factory.js',
        'public/cp/vendor/material-design-kit.js',
        'public/cp/js/dropdown.js',
        'public/cp/js/sidebar-mini.js',
        'public/cp/js/app.js',
        'public/cp/jc/jc.min.js',
    ], 'public/cp/all.js')
    .styles([
        'public/cp/vendor/simplebar.min.css',
        'public/cp/css/vendor-material-icons.css',
        'public/cp/css/vendor-material-icons.rtl.css',
        'public/cp/css/vendor-fontawesome-free.css',
        'public/cp/css/vendor-fontawesome-free.rtl.css'
    ], 'public/cp/all.css')
    .options({processCssUrls: false})
.version();
