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

mix.copyDirectory('resources/assets/images', 'public/images')
    .js(['node_modules/babel-polyfill/lib/index.js','resources/assets/js/app.js'],'public/js/app.js')
    .extract(['vue','element-ui'])
    //.js(['node_modules/babel-polyfill/lib/index.js','resources/assets/js/m.js'],'public/js/m.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/m.scss', 'public/css')
    .version();
