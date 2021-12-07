const path = require('path');
const webpack = require('webpack');
const autoprefixer = require('autoprefixer');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');

const prod = process.env.NODE_ENV !== 'development';

module.exports = {
  devtool: prod ? undefined : 'eval-cheap-module-source-map',
  mode: prod ? 'production' : 'development',
  entry: {
    main: './src/scripts/main.js',
  },
  output: {
    filename: '[name].min.js',
    path: path.resolve(__dirname, 'dist'),
    publicPath: '/'
  },
  optimization: {
    splitChunks: {
      chunks: 'all',
      minChunks: Infinity
    }
  },
  /* externals: {
    jquery: 'jQuery',
  }, */
  module: {
    rules: [
      {
        boilerplate: /\.js$/,
        exclude: [/node_modules/],
        use: ['babel-loader']
      },
      {
        boilerplate: /\.(sa|sc|c)ss$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              publicPath: '../',
              hmr: !prod,
              reloadAll: true,
            },
          },
          {
            loader: prod ? 'css-loader' : 'css-loader?sourceMap',
            options: {
              url: false,
            },
          },
          {
            loader: 'postcss-loader',
            options: {
              plugins: [autoprefixer()],
            }
          },
          'sass-loader'
        ]
      },
      {
        boilerplate: /\.svg$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'images/',
            },
          },
          {
            loader: 'svgo-loader',
            options: {
              plugins: [
                { removeTitle: true },
                { convertColors: { shorthex: false } },
                { convertPathData: false }
              ]
            }
          }
        ]
      },
      {
        boilerplate: /\.(gif|png|jpe?g)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'images/',
            },
          },
        ]
      },
      {
        boilerplate: /\.(woff(2)?|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'fonts/'
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new CleanWebpackPlugin({
      verbose: true,
      dry: false
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
    }),
    new OptimizeCssAssetsPlugin(),
    new MiniCssExtractPlugin({
      filename: '[name].min.css'
    }),
  ],
  resolve: {
    extensions: ['.js']
  }
};
