/* eslint-disable import/order */
const config = require('./webpack.config');

const webpack = require('webpack');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const RemoveFilesPlugin = require('remove-files-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');

const webpackBase = {
    devtool: 'source-map',
    entry: config.entry,
    output: {
        path: path.join(__dirname, './../web/wp-content/themes', config.theme_name, 'assets'),
        filename: config.output_filename,
        publicPath: config.assets_publicPath,
        chunkFilename: () => './js/chunks/[contenthash].js',
        clean: true, // Clean the output directory before emit.
    },
    resolve: {
        extensions: ['.js', '.css'],
        alias: {
            // to import node_modules in js files
            // cropperjs: path.resolve(__dirname, '../node_modules/cropperjs/')
        },
    },
    module: {
        rules: [
            // Loaders
            {
                test: /\.js$/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                        plugins: ['@babel/plugin-syntax-dynamic-import'],
                    },
                },
            },
            {
                test: /\.(css|sass|scss)$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: { sourceMap: true },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sassOptions: {
                                indentWidth: 4,
                            },
                        },
                    },
                ],
            },
            {
                test: /(.*)(\.(eot|ttf|woff2?)(\?.*)?$)/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        context: path.resolve(__dirname, './../assets'),
                        name: '[path][name].[ext]',
                    },
                }],
            },
            {
                test: /(.*)(\.(mp4|ogv|webm)(\?.*)?$)/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        context: path.resolve(__dirname, './../assets'),
                        name: '[path][name].[ext]',
                    },
                }],
            },
            {
                test: /(.*)(\.(png|jpe?g|gif|svg|ico|webmanifest)(\?.*)?$)/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        context: path.resolve(__dirname, './../assets'),
                        name: '[path][name].[ext]',
                    },
                }],
            },
        ],
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
            'window.$': 'jquery',
        }),
        new ESLintPlugin({
            context: './assets/js/',
            formatter: 'table',
            files: '**/*.js',
            exclude: ['node_modules', 'bower_components', 'vendors'],
        }),
        new MiniCssExtractPlugin({
            filename: 'css/[name].css',
        }),
        new CopyWebpackPlugin({
            patterns: [
                { from: './assets/fonts', to: 'fonts' },
               
                { from: './assets/favicon', to: 'favicon' },
                { from: './assets/img', to: 'img' },
            ],
        }),
        new RemoveFilesPlugin({
            before: {
                include: [path.join(__dirname, './../web/wp-content/themes', config.theme_name, 'assets')],
            },
        }),
    ],
    performance: {
        hints: config.debug ? false : 'warning',
    },
};

module.exports = webpackBase;
