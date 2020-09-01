const mix = require("laravel-mix");
const tailwindcss = require("tailwindcss");

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
//

// mix
// .js('resources/js/app.js', 'public/js/app.js')
// // .js('resources/js/vue.js', 'public/js/vue.js')
//
// .sass('resources/sass/app.scss', 'public/css/app.css')
//
//
// .styles(
//     ['public/css/app.css'],
//     'public/css/app.min.css'
// );

// .sass('resources/sass/select2/core.scss', 'public/css/select2.min.css');

// .styles(['resources/sass/glide.core.css','resources/sass/glide.theme.css'], 'public/css/glide.css');
// .browserSync({
//     host:'192.168.79.105',
//     proxy: 'franzini.test',
//     port: 4040,
//     online:true,
//     tunnel:'franzini'
// });

// mix DARAN
mix.js("app/Daran/resources/assets/js/app.js", "public/vendor/daran/js")
	.sass("app/Daran/resources/assets/css/app.scss", "public/vendor/daran/css")
	.sass("resources/sass/app.scss", "public/css/app.css")
	.options({
		processCssUrls: false,
		postCss: [tailwindcss("./tailwind.config.js")],
	});
// .browserSync({host:'192.168.79.152', proxy: 'franzini.test', port: 4040, online:true, tunnel:true})
