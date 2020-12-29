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
    pages: {
        auth_index: 'src/base/modules/auth/pages/index/index.js',
        auth_application:  'src/base/modules/auth/pages/application/index.js'
    },
    publicPath: 'src/base/public',
    // filenameHashing: false,
    chainWebpack: config => {
        config.plugins.delete('html')
        config.plugins.delete('preload')
        config.plugins.delete('prefetch')

        // config.plugin('copy').tap(([options])=> {
        //     options[0].ignore.push('temp/**/*');
        //     return [options];
        // })
    }
}