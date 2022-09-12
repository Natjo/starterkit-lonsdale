const config = {
    entry: {
        app: ['./assets/scss/app.scss', './assets/js/app.js'],
    },
    domain: process.env.DOMAIN,
    proxy: `https://${process.env.DOMAIN}/`,
    port: process.env.NODE_PORT,
    theme_name: process.env.WP_THEME_NAME,
    output_filename: 'js/[name].js',
    assets_publicPath: `/wp-content/themes/${process.env.WP_THEME_NAME}/assets/`, // Urls dans le fichier final
    debug: (process.env.NODE_ENV === 'development'),
};

module.exports = config;
