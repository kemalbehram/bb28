import Vue from 'vue'
import App from './App.vue'
import router from './router'
import RouterVue from 'vue-router'
import store from './store'
import '@/assets/style/index.less'
import '@/assets/style/reset-vant.less'
import cookie from 'js-cookie'
import '@/libs/filter.js'
import { post, get, getList } from './libs/request'
import loading from '@/components/loading'
import vueTouch from 'kim-vue-touch'

import {
    Icon,
    Button,
    Toast,
    Tabbar,
    TabbarItem,
    Swipe,
    SwipeItem,
    Image as VanImage,
    Lazyload,
    NoticeBar,
    Grid,
    GridItem,
    CountDown,
    Field,
    Cell,
    Divider,
    Tab,
    Tabs,
    List,
    PullRefresh,
    CellGroup,
    Empty,
    ActionSheet,
    Popup,
    Dialog,
    NumberKeyboard,
    PasswordInput,
    Switch,
    SwitchCell,
    DropdownMenu,
    DropdownItem,
    NavBar,
    Overlay,
    Collapse,
    CollapseItem,
    DatetimePicker,
    Uploader,
    Progress,
    TreeSelect,
    Skeleton,
    Loading,
    Pagination,
    Radio,
    RadioGroup,
    Picker
} from 'vant'

Vue.use(Icon)
Vue.use(Button)
Vue.use(Toast)
Vue.use(Tabbar)
Vue.use(TabbarItem)
Vue.use(Swipe)
Vue.use(SwipeItem)
Vue.use(VanImage)
Vue.use(Lazyload)
Vue.use(NoticeBar)
Vue.use(Grid)
Vue.use(GridItem)
Vue.use(CountDown)
Vue.use(Field)
Vue.use(Cell)
Vue.use(Divider)
Vue.use(Tabs)
Vue.use(Tab)
Vue.use(List)
Vue.use(PullRefresh)
Vue.use(CellGroup)
Vue.use(Empty)
Vue.use(ActionSheet)
Vue.use(Popup)
Vue.use(Dialog)
Vue.use(NumberKeyboard)
Vue.use(PasswordInput)
Vue.use(Switch)
Vue.use(SwitchCell)
Vue.use(DropdownMenu)
Vue.use(DropdownItem)
Vue.use(NavBar)
Vue.use(Overlay)
Vue.use(Collapse)
Vue.use(CollapseItem)
Vue.use(DatetimePicker)
Vue.use(Uploader)
Vue.use(Progress)
Vue.use(TreeSelect)
Vue.use(Skeleton)
Vue.use(Loading)
Vue.use(Pagination)
Vue.use(Radio)
Vue.use(RadioGroup)
Vue.use(Picker)


Vue.use(vueTouch)
Vue.component('loading', loading)

Toast.setDefaultOptions('loading', {
    duration: 0,
    forbidClick: true,
    loadingType: 'spinner',
    message: '请稍后...',
})

// Vue.prototype.$clipboard = clipboard
Vue.prototype.$post = post
Vue.prototype.$get = get
Vue.prototype.$getList = getList
Vue.prototype.$cookie = cookie

Vue.config.productionTip = false

Vue.config.devtools = true

const originalPush = RouterVue.prototype.push
RouterVue.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
}
new Vue({
    router,
    store,
    render: function(h) {
        return h(App)
    },
}).$mount('#app')
