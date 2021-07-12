import Vue from 'vue'
import App from './App'
import router from './router/index'
import store from './store'
import ViewUI from 'view-design'
import config from '@/config'
import dataware from '@/libs/dataware'
import { post, get } from './libs/request'
import componentsUe from '@/components-ue'
import moment from 'moment'

import '@/libs/filter.js'
import '@/assets/style/index.less'
import '@/assets/view-ui/index.less'
import 'vant/lib/index.less'
Vue.use(componentsUe)

import {
    CountDown,
    Popover,
    Dialog
} from 'vant'
Vue.use(CountDown)
Vue.use(Popover)
Vue.use(Dialog)
Vue.use(ViewUI, {
    modal: {
        maskClosable: false,
    },
})

Vue.config.productionTip = false
Vue.prototype.$config = config

Vue.prototype.$Message.config({
    top: 50,
    duration: 5,
})

moment.locale('zh-cn', {
    calendar: {
        sameDay: '[今天] HH:mm',
        lastDay: '[昨天] HH:mm',
        lastWeek: '[上] dddd HH:mm',
    },
})

Vue.prototype.$post = post
Vue.prototype.$get = get
Vue.prototype.$dataware = dataware
Vue.prototype.$moment = moment

let app = new Vue({
    router,
    el: '#app',
    store,
    render: (h) => h(App),
})

Vue.use({
    app,
})
