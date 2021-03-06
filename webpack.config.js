let Encore = require('@symfony/webpack-encore');

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
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.scss) if you JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
    .addEntry('AdminDashboard', './assets/js/AdminDashboard/AdminDashboard.js')
    .addEntry('AdminDashboard-menu-article', './assets/js/AdminDashboard/AdminDashboard-menu-article.js')
    .addEntry('AdminDashboard-menu-category', './assets/js/AdminDashboard/AdminDashboard-menu-category.js')
    .addEntry('AdminDashboard-menu-tag', './assets/js/AdminDashboard/AdminDashboard-menu-tag.js')
    .addEntry('AdminDashboard-menu-user', './assets/js/AdminDashboard/AdminDashboard-menu-user.js')
    .addEntry('article', './assets/js/Article/article.js')
    .addEntry('listArticle', './assets/js/Article/listArticle.js')
    .addEntry('index', './assets/js/index.js')
    .addEntry('login', './assets/js/Security/login.js')
    .addEntry('fileBrowser', './assets/js/fileBrowser/fileBrowser.js')
    .addEntry('profil', './assets/js/profil/profil.js')

    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')
    //.addStyleEntry('css/app', ['./assets/css/app.scss', './assets/css/article.css'])

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

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

    // enables @babel/preset-env polyfills
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes()

    // uncomment if you're having problems with a jQuery plugin

    .autoProvidejQuery()
    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery'
    });


    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')


module.exports = Encore.getWebpackConfig();
