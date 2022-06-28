const mix = require('laravel-mix');
const path = require('path');

require('./mix.extend.js');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('public');

mix.ts('resources/js/app.ts', 'public/app/js', {
   configFile: 'tsconfig.json',
   transpileOnly: true,
})
   .webpackConfig({
      output: {
         filename: '[name].js',
         chunkFilename: 'chunks/[name].[chunkhash].js',
      },
   })
   .vue({ version: 3 })
   .alias({
      '~': path.join(__dirname, 'resources', 'js'),
      ziggy: path.resolve('vendor/tightenco/ziggy/dist'),
   })
   .ziggy({
      path: 'resources/js/utils/ziggy.js',
      enable: true,
   });

mix.postCss('resources/css/app.css', 'public/app/css', [
   //
]);

mix.copyDirectory('resources/dist/', 'public');

if (mix.inProduction()) {
   mix.version()
      .extract();
}
