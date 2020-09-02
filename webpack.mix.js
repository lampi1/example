const mix = require("laravel-mix");
const tailwindcss = require("tailwindcss");


/*
 |--------------------------------------------------------------------------
 | Mix Asset - FRONT
 |--------------------------------------------------------------------------
 */

mix.js('resources/js/app.js', 'public/js/app.js')
    .sass("resources/sass/app.scss", "public/css/app.css")
    .options({
	    processCssUrls: false,
	    postCss: [tailwindcss("./tailwind.config.js")],
    });


/*
 |--------------------------------------------------------------------------
 | Mix Asset - BACK
 |--------------------------------------------------------------------------
 */

// mix.js("app/Daran/resources/assets/js/app.js", "public/vendor/daran/js")
//    .sass("app/Daran/resources/assets/css/app.scss", "public/vendor/daran/css")
