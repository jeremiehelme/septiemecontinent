const path = require("path");
const fs = require('fs');
const express = require('express');
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');
const app = express();

// css extraction and minification
const MiniCssExtractPlugin = require("mini-css-extract-plugin"); 
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

// Image minification
const ImageMinimizerPlugin = require( 'image-minimizer-webpack-plugin' );
const CopyPlugin = require( 'copy-webpack-plugin' );

// clean out build dir in-between builds
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

const chucksCSS = ["main", "editor"];
const ignoreEmit = [
  /css\.min\..+\.js/
];
for (let i = 0; i < chucksCSS.length; i++) {
  ignoreEmit.push(new RegExp(`${chucksCSS[i]}\\.min\\..+\\.js$`));
}

module.exports = [
  {
    entry: {
      index: ["./assets/src/js/index.js"],
      css: ["./assets/src/js/css.js"],
    },
    output: {
      filename: "./js/[name].min.[fullhash].js",
      assetModuleFilename: './img/[name][ext]',
      path: path.resolve( __dirname, 'assets/build' ),
    },
    // Ne pas rebuild les fichiers non modifié
    cache: {
      type: 'filesystem',  // Utilisation du cache basé sur le système de fichiers
      buildDependencies: {
        config: [__filename],  // Invalider le cache si le fichier de config change
      },
    },
    devtool: 'source-map',
    module: {
      rules: [
        // js babelization
        {
          test: /\.(js|jsx)$/,
          exclude: /node_modules/,
          loader: "babel-loader",
        },
        // css loader
        {
          test: /\.css$/,
          use: ["style-loader", "css-loader"],
        },
        // sass compilation
        {
          test: /\.s[ac]ss$/i,
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: "css-loader",
              options: {
                sourceMap: true,
              },
            },
            {
              loader: "postcss-loader",
              options: {
                sourceMap: true,
                postcssOptions: {
                  plugins: [
                    // Other plugins,
                    [
                      "postcss-preset-env",
                      {
                        // Options
                      },
                    ],
                  ],
                },
              },
            },
            {
              loader: "sass-loader",
              options: {
                sourceMap: true,
              },
            },
          ],
        },
        {
          test: /\.(svg)$/i,
          type: 'asset/resource',
          generator: {
            filename: 'img/svg/[name][ext]',
          },
        },
        {
          test: /\.(ico|png|jpg|jpeg|gif|webp|tiff)$/i,
          type: 'asset/resource',
          generator: {
            filename: 'img/[name][ext]',
          },
        },
      ],
    },
    plugins: [
      // clear out build directories on each build
      new CleanWebpackPlugin({
        cleanAfterEveryBuildPatterns: [
          './js/*.LICENSE.txt',
          './css/*.LICENSE.txt',
          './*.js',
          './*.map',
          './*.LICENSE.txt',
          path.resolve(__dirname, 'dist/js/*.LICENSE.txt'),
        ],
      }),
      // css extraction into dedicated file
      new MiniCssExtractPlugin({
        filename: ({ chunk }) =>
        `./css/${chunk.name}.min.[fullhash].css`,
        // filename: "./assets/build/css/[name].min.[fullhash].css",
      }),
      //pour les images qui ne passent pas directement par code css ou js
      new CopyPlugin( {
        patterns: [
          { from: './assets/src/img/', to: 'img' },
        ],
      } ),
      new IgnoreEmitPlugin(ignoreEmit)
    ],
    optimization: {
      // minification - only performed when mode = production
      minimizer: [
        // js minification - special syntax enabling webpack 5 default terser-webpack-plugin
        `...`,
        // css minification
        new CssMinimizerPlugin(),
        // image minification
				new ImageMinimizerPlugin( {
					minimizer: {
						implementation: ImageMinimizerPlugin.imageminMinify,
						options: {
							// Lossless optimization with custom option
							// Feel free to experiment with options for better result for you
							plugins: [
								[ 'gifsicle', { interlaced: true } ],
								[ 'jpegtran', { progressive: true } ],
								[ 'optipng', { optimizationLevel: 5 } ],
								[
									'svgo',
									{
										plugins: [
											{
												name: 'preset-default',
												params: {
													overrides: {
														removeViewBox: false,
														removeUselessStrokeAndFill: false,
														removeUnknownsAndDefaults: false,
														addAttributesToSVGElement: {
															params: {
																attributes: [
																	{ xmlns: 'http://www.w3.org/2000/svg' },
																],
															},
														},
													},
												},
											},
										],
									},
								],
							],
						},
					},
					concurrency: 5, //Parallelisation des taches pour optimiser la rapidité du build
				} ),
      ],
      splitChunks: {
        cacheGroups: {
          ...chucksCSS
          .map((name) => ({
            name,
            test: new RegExp(`${name}\\.s?css$`),
            chunks: "all",
            enforce: true,
          }))
          .reduce((acc, current) => {
            acc[current.name] = current;
            return acc;
          }, {}),
        },
      },
    },
  },
];
