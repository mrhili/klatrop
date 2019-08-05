let mix = require('laravel-mix');



mix.js('resources/js/app.js', 'public/js')
.sass('resources/sass/app.scss', 'public/css');

mix.browserSync({proxy: '127.0.0.1:8000'});
