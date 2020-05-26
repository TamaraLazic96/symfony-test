var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
// if (!Encore.isRuntimeEnvironmentConfigured()) {
//     Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
// }

// for compiling the css and js
// ./node_modules/.bin/encore dev
// watching every time something changes
// ./node_modules/.bin/encore dev --watch

Encore
    .enableSingleRuntimeChunk()
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    .addEntry('js/app', [
        './node_modules/jquery/dist/jquery.slim.js',
        './node_modules/popper.js/dist/popper.js',
        './node_modules/bootstrap/dist/js/bootstrap.min.js',
        './node_modules/holderjs/holder.min.js',
    ])
    .addStyleEntry('css/app', [
        './node_modules/bootstrap/dist/css/bootstrap.min.css',
        './assets/css/app.css'
    ])
;

module.exports = Encore.getWebpackConfig();
