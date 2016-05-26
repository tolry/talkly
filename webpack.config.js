var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var fs = require('fs');
var yaml = require('yaml-js');

var config = yaml.load(fs.readFileSync('./app/config/parameters.yml').toString());

module.exports = {
    entry: './src/js/App',

    output: {
        filename: 'app.js',
        path: 'web/build',
        publicPath: (config.parameters.public_path || '/') + 'build/'
    },

    plugins: [
        new ExtractTextPlugin('style.css', {allChunks: true})
    ],

    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                loaders: ["babel-loader"]
            },
            {
                test: /\.scss$/,
                loaders: ["style", "css", "sass"]
            },
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract('style-loader', 'css-loader?modules&importLoaders=1&localIdentName=[name]__[local]___[hash:base64:5]!postcss-loader')
            },
            {
                test: /\.(jpe?g|png|gif)$/i,
                loader: 'file-loader'
            },
            {
                test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
                loader: "url-loader?limit=10000&minetype=application/font-woff"
            },
            {
                test: /\.(ttf|eot|svg)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
                loader: "file-loader"
            }
        ]
    },
    resolve: {
        extensions: ['', '.js', '.jsx', '.css', '.scss']
    },

    // Additional plugins for CSS post processing using postcss-loader
    postcss: [
        require('autoprefixer') // Automatically include vendor prefixes
        //require('postcss-nested') // Enable nested rules, like in Sass
    ]
};