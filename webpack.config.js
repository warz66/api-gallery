var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */

    /****** BACK ******/

    /****** Admin Base CSS & SCSS ******/ 
    .addStyleEntry('css/admin.app', './assets/css/admin/admin.app.scss')

    /****** Admin Publication ******/

    /****** Admin Galerie  CSS ******/
    .addStyleEntry('css/admin.galerie.edit.app', './assets/css/admin/galerie/edit/admin.galerie.edit.app.css')
    .addStyleEntry('css/admin.galerie.new', './assets/css/admin/galerie/new/admin.galerie.new.css')
    .addStyleEntry('css/admin.galerie.index', './assets/css/admin/galerie/index/admin.galerie.index.css')

    /****** Admin base JS ******/
    .addEntry('js/admin.app', './assets/js/admin/admin.app.js')

    /****** Admin Galerie JS ******/
    .addEntry('js/admin.galerie.edit.app', './assets/js/admin/galerie/edit/admin.galerie.edit.app.js')
    .addEntry('js/admin.galerie.new', './assets/js/admin/galerie/new/admin.galerie.new.js')
    .addEntry('js/admin.galerie.index.app', './assets/js/admin/galerie/index/admin.galerie.index.app.js')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    //.enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    //.splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
