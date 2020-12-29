'use strict'

// const glob = require('glob')
// const pages = {}

// glob.sync('./modules/*/src/*.js').forEach(path => {
//     const chunk = path.split('./modules/')[1].split('/src/main.js')[0];
//
//     pages[chunk] = {
//         entry: path,
//         chunks: ['chunk-vendors', 'chunk-common', chunk]
//     }
// })

module.exports = {

    publicPath: './public',

    pages: {
        auth_index: 'src/base/modules/auth/pages/index/index.js'
    },

    chainWebpack: config => {
        const options = module.exports
        const pages = options.pages
        const pageKeys = Object.keys(pages)

        config.plugins.delete('html')
        config.plugins.delete('preload')
        config.plugins.delete('prefetch')

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