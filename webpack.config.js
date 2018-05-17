var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    // create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    // enable source maps during development
    .enableSourceMaps(!Encore.isProduction())

    // define the assets of the project
    .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('css/app', './assets/scss/app.scss')

    // use Sass/SCSS files
    .enableSassLoader()
    // use vue
    .enableVueLoader()
;

module.exports = Encore.getWebpackConfig();
