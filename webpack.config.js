// webpack.config.js
const Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction)

    .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('css/app', './assets/css/global.scss')

    .enableSassLoader()
    // uncomment this if you want use jQuery in the following example
    .autoProvidejQuery()
    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
    })

    .disableSingleRuntimeChunk()


    ;

module.exports = Encore.getWebpackConfig();
