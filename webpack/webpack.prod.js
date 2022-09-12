/* eslint-disable import/order */
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const webpackBase = require('./webpack.base.js');

webpackBase.mode = 'production';
webpackBase.devtool = false;
webpackBase.optimization = {
    minimize: true,
    minimizer: [
        new CssMinimizerPlugin(),
        new TerserPlugin(
            {
                // test: /\.js(\?.*)?$/i,
                extractComments: false,
                terserOptions: {
                    format: {
                        comments: false,
                    },
                    compress: {
                        drop_console: true,
                        warnings: false,
                    },
                },
            },
        ),
    ],
};

webpackBase.plugins.push(
    new ImageminPlugin({
        jpegtran: {
            progressive: true,
        },
        mozjpeg: {
            progressive: true,
            quality: 65,
        },
        optipng: {
            optimizationLevel: 5,
        },
        pngquant: {
            quality: '65-90',
            speed: 4,
        },
        gifsicle: {
            interlaced: false,
        },
        svgo: {
            removeTitle: true,
        },
        webp: {
            quality: 75,
        },
    }),
);

module.exports = webpackBase;
