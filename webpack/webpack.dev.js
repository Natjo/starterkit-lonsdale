/* eslint-disable import/order */
const webpackBase = require('./webpack.base');
const config = require('./webpack.config');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

webpackBase.mode = 'development';
webpackBase.plugins.push(
    // if webpack is runniing on your desktop, comment the following BrowserSync config
    // new BrowserSyncPlugin({
    //     host: config.host,
    //     proxy: config.proxy,
    //     port: config.port,
    //     files: ['web/wp-content/themes/**/*.+(html|php|twig)'],
    // }),
    // docker nginx communication
    new BrowserSyncPlugin({
        https: {
            key: `/etc/certs/${config.domain}.key`,
            cert: `/etc/certs/${config.domain}.crt`,
        },
        host: 'nginx', // DO NOT CHANGE ! nginx is the name of the docker container
        proxy: `https://${config.domain}`, // DO NOT CHANGE ! nginx is the name of the docker container
        port: config.port,
        files: ['web/wp-content/themes/**/*.+(html|php|twig)'],
        open: false,
    }),
);

module.exports = webpackBase;
