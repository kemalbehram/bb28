const path = require('path')

const resolve = (dir) => {
  return path.join(__dirname, dir)
}

const BASE_URL = process.env.NODE_ENV === 'production' ? '/m' : '/'

const ASSETS_URL =
  process.env.NODE_ENV === '/'

module.exports = {
  productionSourceMap: false,

  publicPath: '',

  outputDir: './dist',

  assetsDir: '',
  indexPath: './index.html',

  // 如果你不需要使用eslint，把lintOnSave设为false即可
  lintOnSave: false,

  chainWebpack: (config) => {
    config.resolve.alias
      .set('@', resolve('src')) // key,value自行定义，比如.set('@@', resolve('src/components'))
      .set('_c', resolve('src/components'))
      .set('_conf', resolve('config'))
      .set('_m', resolve('node_modules'))
  },

  devServer: {
    proxy: {
      '/api': {
        target: 'http://caipiao:8888',
        changeOrigin: true,
      },
    },

    disableHostCheck: true,
  },

  pluginOptions: {
    'style-resources-loader': {
      preProcessor: 'less',
      patterns: [resolve('src/assets/variables.less')],
    },
  },

  css: {
    loaderOptions: {
      less: {
        lessOptions: {
          javascriptEnabled: true,
        },
      },
    },
  },
}
