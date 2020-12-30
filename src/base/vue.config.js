'use strict'

// const path = require('path');
// const glob = require('glob')
// const pages = {}

// const HtmlWebpackPlugin = require('html-webpack-plugin');


// glob.sync('./modules/*/src/*.js').forEach(path => {
//     const chunk = path.split('./modules/')[1].split('/src/main.js')[0];
//
//     pages[chunk] = {
//         entry: path,
//         chunks: ['chunk-vendors', 'chunk-common', chunk]
//     }
// })

// https://vue-loader.vuejs.org/en/configurations/extract-css.html
// https://blog.antsand.com/singlepost/index/5619/How-to-integrate-php-Phalcon-and-Vue.js-components-(*.vue-files)


module.exports = {
    publicPath: 'src/base/public',

    pages: {
        auth_index: 'src/base/modules/auth/pages/index/index.js'
    },

    configureWebpack: {
        resolve: {
            alias: {
                'vue$': 'vue/dist/vue.esm.js'
            }
        },
    },

    chainWebpack: config => {
        const options = module.exports
        const pages = options.pages
        const pageKeys = Object.keys(pages)

        // https://stackoverflow.com/questions/51242317/how-to-split-vue-cli-3-pages-vendor-file/61089300#61089300
        config.optimization
            .splitChunks({
                cacheGroups: {
                    ...pageKeys.map(key => ({
                        name: `${key}-chunk-vendors`,
                        priority: -11,
                        chunks: chunk => chunk.name === key,
                        enforce: true
                    })),
                    common: {
                        name: 'chunk-common',
                        priority: -20,
                        chunks: 'initial',
                        minChunks: 2,
                        reuseExistingChunk: true,
                        enforce: true,
                    },
                },
            })
    }
}