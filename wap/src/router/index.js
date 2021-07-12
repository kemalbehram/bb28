import Vue from 'vue'
import VueRouter from 'vue-router'
import RouterBase from './router-base'
import RouterUser from './router-user'
import RouterWallet from './router-wallet'
import RouterLotto from './router-lotto'
import RouterConfigSetting from './router-config'
import store from '@/store'
// Vue.use(Toast)
Vue.use(VueRouter)
const originalReplace = VueRouter.prototype.replace;
VueRouter.prototype.replace = function replace (location) {
  return originalReplace.call(this, location).catch(err => err);
};
// 将配置文件放入薰铉数组中
const routersArr = [RouterBase, RouterUser, RouterWallet, RouterLotto,RouterConfigSetting]
const RouterConfig = []

// 循环路由配置 组装成 VueRouter 需要的格式
routersArr.forEach((arr) => {
  for (var i in arr) {
    RouterConfig.push(arr[i])
  }
})

// 创建VueRouter 实例
export const router = new VueRouter({
  mode: 'hash',
  // base: '/m',
  routes: RouterConfig,
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  },
})

router.beforeEach((to, from, next) => {

  let auth = ['authLogin', 'authRegister']
  store.state.back += 1
  if (auth.indexOf(to.name) >= 0 && store.state.user.token) {
    if (from.name) {

      return false
    } else {
    //   router.replace({
    //     name: 'home',
    //   })
    }
  }

  if (to.meta.auth === true && !store.state.user.token) {

    router.push({
      name: 'authLogin',
    })
  }
  if(from.path!=to.path && !from.meta.pop){
    store.state.config.router=from.path
  }
  
  return next()
})

router.afterEach((to) => {
  // 设置网页title
  const pageTitle = to.meta.title
  const appTitle = store.state.config.web_title==undefined?'英利28':store.state.config.web_title
  const resTitle = pageTitle ? pageTitle + ' - ' + appTitle : appTitle
  window.document.title = resTitle
})

export default router
