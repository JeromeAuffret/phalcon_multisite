'use strict'

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

module.exports = {
    pages: {
        auth: {
            entry: 'src/base/modules/auth/main.js',
            chunks: ['chunk-vendors', 'chunk-common', 'auth']
        }
    },
    filenameHashing: false,
    // chainWebpack: config => {
    //     config.plugins.delete('html')
    //     config.plugins.delete('preload')
    //     config.plugins.delete('prefetch')
    // }
}