import Vue from 'vue'
import Vuex from 'vuex'
import router from '@/router'
import config from './config'
import user from './user'
import push from './push'
import lotto from './lotto'
import chat from './chat'

Vue.use(Vuex)
export default new Vuex.Store({
    state: {
        back: 0,
    },

    mutations: {},

    actions: {
        toBack ({ state }) {
            if (state.back <= 1) {
                router.push('/')
            } else {
                router.back(-1)
            }
        },
    },

    modules: {
        config,
        user,
        push,
        lotto,
        chat
    },
})
