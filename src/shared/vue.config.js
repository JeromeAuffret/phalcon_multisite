// 'use strict'
//
// // const glob = require('glob')
// // const pages = {}
// //
// // glob.sync('./modules/*/src/*/*.js').forEach(path => {
// //     const module = path.split('./modules/')[1].split('/src/')[0]
// //     const page = path.split('/'+module+'/src/')[1].split('/')[0]
// //     const action = path.split('/'+page+'/')[1].split('.js')[0]
// //
// //     const chunk = module + '_' + page + '_' + action;
// //
// //     pages[chunk] = {
// //         entry: path,
// //         template: 'public/index.html',
// //         title: chunk,
// //         chunks: ['chunk-vendors', 'chunk-common', chunk]
// //     }
// // })
//
//
module.exports = {
    // pages,

    filenameHashing: false,
    // chainWebpack: config => {
    //     // config.plugins.delete('named-chunks')
    //     config.plugins.delete('html')
    //     config.plugins.delete('preload')
    //     config.plugins.delete('prefetch')
    // }

}