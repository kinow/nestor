var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
    //mix.less('');

    mix.copy(
    	'./public/js/libs/semantic/dist/themes',
    	'./public/css/themes'
    );

    mix.copy(
        './public/js/libs/jquery-ui/themes/smoothness/images',
        './public/css/images'
    );

    mix.styles([
    	'./public/js/libs/semantic/dist/semantic.min.css',
        './public/js/libs/parsleyjs/src/parsley.css',
        './public/js/libs/simplemde/dist/simplemde.min.css',
        './public/js/libs/jquery-ui/themes/smoothness/jquery-ui.min.css'
    ],
    'public/css/nestor.css');

 //    mix.scripts([
 //    	'./resources/assets/bower/jquery/dist/jquery.js',
 //    	'./resources/assets/bower/bootstrap-sass-official/assets/javascripts/bootstrap.min.js'
	// ],
	// 'public/js/nestor.js');
});
