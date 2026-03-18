const mix = require("laravel-mix");

mix.js("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .sourceMaps();

mix.copy(
    "vendor/proengsoft/laravel-jsvalidation/resources/views",
    "resources/views/vendor/jsvalidation"
).copy(
    "vendor/proengsoft/laravel-jsvalidation/public",
    "public/vendor/jsvalidation"
);
