var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    entry: './src/js/app.js',
    output: {
        filename: 'app.js',
        path: 'web/js/'
    },

    plugins: [
        new ExtractTextPlugin('style.css', { allChunks: true })
    ],

    module: {
        loaders: [
            { test: /\.jsx?$/, exclude: /node_modules/, loaders: ["babel-loader"] },
            { test: /\.s?css$/, loader: ExtractTextPlugin.extract('style-loader', 'css-loader?modules&importLoaders=1&localIdentName=[name]__[local]___[hash:base64:5]!postcss-loader') }
        ]
    },
    resolve: {
        extensions: ['', '.js', '.jsx', '.css', '.scss']
    },

    // Additional plugins for CSS post processing using postcss-loader
    postcss: [
        require('autoprefixer'), // Automatically include vendor prefixes
        require('postcss-nested') // Enable nested rules, like in Sass
    ]
};