const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {

    mix.sass([
        'app.scss',
        'four13.scss'
    //     'custom.scss',
    //     '../../../node_modules/font-awesome/css/font-awesome.css',
    //     '../../../node_modules/dropzone/src/dropzone.scss',
    //     '../../../node_modules/croppie/croppie.css'
    ]);

    mix.webpack([
        'app.js'
    //     '../../../node_modules/croppie/croppie.js',
    //     'custom.js',
    //     'cms/croppie.js',
    //     'cms/dropzone.js',
    //     'cms/fileupload.js',
    //     'cms/helper.js'
    ], 'public/js/all.js');

    mix.version([
        'public/css/app.css',
        'public/js/all.js'
    ]);

    // mix.copy('node_modules/font-awesome/fonts','public/build/fonts');
    // mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap/','public/build/fonts/bootstrap');
});
