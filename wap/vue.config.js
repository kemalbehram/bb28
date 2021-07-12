const path = require('path')

const resolve = (dir) => {
  return path.join(__dirname, dir)
}

const ASSETS_URL =
  process.env.NODE_ENV === 'production' ? process.env.VUE_APP_ASSETS_URL : '/'

module.exports = {
  productionSourceMap: false,

  publicPath: ASSETS_URL,

  outputDir: process.env.VUE_APP_OUTPUT_DIR,

  assetsDir: process.env.VUE_APP_ASSETS_DIR,

  indexPath:
    process.env.NODE_ENV === 'production' ? 'index.html' : 'index.html',

  // 如果你不需要使用eslint，把lintOnSave设为false即可
  lintOnSave: false,

  chainWebpack: (config) => {
    config.resolve.alias
      .set('@', resolve('src')) // key,value自行定义，比如.set('@@', resolve('src/components'))
      .set('_c', resolve('src/components'))
      .set('_conf', resolve('config'))
      .set('_m', resolve('node_modules'))

    config.module
      .rule('view-design')
      .test(/view-design.src.*?js$/)
      .use('babel')
      .loader('babel-loader')
      .end()
    return config
  },

  devServer: {
    proxy: {
      '/api': {
        target: process.env.VUE_APP_API_PROXY,
        changeOrigin: true,
      },
      '/trend-chart': {
        target: process.env.VUE_APP_TREND_PROXY,
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