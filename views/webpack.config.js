const path = require('path');
const webpack = require('webpack');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

let config = {
  entry: {
    grid: [
      './js/grid',
    ]
  },
  output: {
    path: path.resolve(__dirname, 'public'),
    filename: '[name].bundle.js'
  },
  //devtool: 'source-map', // uncomment me to build source maps (really slow)
  resolve: {
    extensions: ['.js', '.ts'],
    alias: {
      '@PSTypes': path.resolve(__dirname, '../../../admin-dev/themes/new-theme/js/types'),
      '@components': path.resolve(__dirname, '../../../admin-dev/themes/new-theme/js/components'),
      '@app': path.resolve(__dirname, '../../../admin-dev/themes/new-theme/js/app')
    }
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        include: path.resolve(__dirname, '../js'),
        use: [{
          loader: 'babel-loader',
          options: {
            presets: [
              ['es2015', { modules: false }]
            ]
          }
        }]
      },
      {
        test: /\.ts?$/,
        loader: 'ts-loader',
        options: {
          onlyCompileBundledFiles: true,
        },
        exclude: /node_modules/,
      },
      {
        test: /\.(scss|sass|css)$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
          },
          {
            loader: 'css-loader',
          },
          {
            loader: 'postcss-loader',
          },
          {
            loader: 'sass-loader',
          },
        ],
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css',
    }),
  ]
};

if (process.env.NODE_ENV === 'production') {
  config = {
    ...config,
    optimization: {
      minimize: true,
      minimizer: [
        new TerserPlugin(),
      ],
    },
  }
} else {
  config.plugins.push(new webpack.HotModuleReplacementPlugin());
}

module.exports = config;
