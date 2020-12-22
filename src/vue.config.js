// 'use strict'
//
// const glob = require('glob')
// const pages = {}
//
// glob.sync('./modules/*/src/*.js').forEach(path => {
//     const chunk = path.split('./modules/')[1].split('/src/main.js')[0];
//
//     pages[chunk] = {
//         entry: path,
//         // chunks: ['chunk-vendors', 'chunk-common', chunk]
//     }
// })
//
module.exports = {
    pages: {
        auth: {
            // entry for the page
            entry: 'base/modules/auth/main.js',
            // output as dist/index.html
            filename: 'auth.html',
            // chunks to include on this page, by default includes
            // extracted common chunks and vendor chunks.
            chunks: ['chunk-vendors', 'chunk-common', 'auth']
        }
    },
    filenameHashing: false,
    chainWebpack: config => {
        config.plugins.delete('html')
        config.plugins.delete('preload')
        config.plugins.delete('prefetch')
    }
}