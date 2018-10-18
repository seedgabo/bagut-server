const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

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

elixir(mix => {
	mix
		// .sass('app.scss')
       	.webpack('app.js')
		.less('app.less')
		.less('admin-lte/AdminLTE.less', 'public/vendor/adminlte/dist/css/AdminLTE.min.css')
		.less('admin-lte/skins/_all-skins.less', 'public/vendor/adminlte/dist/css/skins/_all-skins.min.css')
		.browserSync({
		    proxy: 'project.dev'
		});
});
