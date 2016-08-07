var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var fs = require('fs');
var yaml = require('yaml-js');
var config = yaml.load(fs.readFileSync('./app/config/local.yml').toString());


module.exports = {
    devtool: 'cheap-module-source-map',

    entry: [
        './src/js/index'
    ],

    output: {
        filename: 'app.js',
        path: path.join(__dirname, 'web/build'),
        publicPath: path.join(config.parameters.public_path || '/', 'build/')
    },

    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                'NODE_ENV': JSON.stringify('production')
            }
        }),
        new ExtractTextPlugin('style.css', {allChunks: true}),
    ],

    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                include: path.join(__dirname, 'src'),
                loaders: ["babel"]
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