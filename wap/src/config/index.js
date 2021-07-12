export default {
    productionSourceMap: false,
    /**
     * description 配置显示在浏览器标签的title
     */
    title: 'WELCOME',

    version: '20200620',

    tokenField: process.env.VUE_APP_TOKEN_FIELD,

    /**
     * description api请求基础路径
     */

    baseUrl: {
        // dev: 'http://leshare.localhost.com/api/client',
        dev: '/api/client',
        pro: '/api/client',
        // dev: 'http://master-api.mahua28.com/api/client',
        // pro: 'http://master-api.mahua28.com/api/client',
    },

    /**
     * description 默认打开的首页的路由name值，默认为home
     */
    homeRoute: 'home',

    /**
     * description 默认打开的登陆页路由name值
     */
    loginRoute: 'userLogin',
    wapDomain: 'http://a-wap.youhaoche.cn',
}
