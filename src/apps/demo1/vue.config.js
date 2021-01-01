'use strict'

module.exports = {
    publicPath: 'src/base/public',

    pages: {
        dashboard_index: 'src/apps/demo1/modules/dashboard/pages/index/index.js'
    },

    configureWebpack: {
        resolve: {
            alias: {
                'vue$': 'vue/dist/vue.esm.js',
                'bootstrap-vue$': 'bootstrap-vue/src/index.js'
            }
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules\/(?!bootstrap-vue\/src\/)/,
                }
            ]
        }
    },

    chainWebpack: config => {
        const options = module.exports
        const pages = options.pages

        // https://stackoverflow.com/questions/51242317/how-to-split-vue-cli-3-pages-vendor-file/61089300#61089300
        config.optimization
            .splitChunks({
                cacheGroups: {
                    ...Object.keys(pages).map(key => ({
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