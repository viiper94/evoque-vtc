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

mix.js([
    'resources/js/app.js',
    'resources/js/modules/common.js',
    'resources/js/modules/apply.js',
    'resources/js/modules/profile.js',
    'resources/js/modules/members.js',
    'resources/js/modules/rp.js',
    'resources/js/modules/tab.js',
    'resources/js/modules/convoy.js',
    'resources/js/modules/plans.js',
    'resources/js/modules/applications.js',
    'resources/js/modules/dlc.js',
    'resources/js/modules/test.js',
    ], 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .version();
