const mix = require("laravel-mix");
require("dotenv").config();
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

mix.babelConfig({
  plugins: ["@babel/plugin-syntax-dynamic-import"] // important to install -D
});

mix.webpackConfig({
  // output: { publicPath: "/" },
  resolve: {
    extensions: [".js", ".vue", ".json"],
    alias: {
      "@": __dirname + "/resources/js"
    }
  }
});

mix.options({
  extractVueStyles: true,
  globalVueStyles: "resources/sass/_variables.scss"
});

mix
  .js("resources/js/app.js", "public/js")
  .sass("resources/sass/app.scss", "public/css");
// .babel("resources/js/helpers/*", "public/js/helpers.js")
