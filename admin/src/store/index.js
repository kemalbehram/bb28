import Vue from 'vue'
import Vuex from 'vuex'
import user from './module/user'
import app from './module/app'
import goods from './module/goods'
import article from './module/article'
import config from './module/config'
import drawer from './module/drawer'
import service from './module/service'
import chat from './module/chat'
import home from './module/home'

Vue.use(Vuex)
export default new Vuex.Store({
    state: {
        date_picker: {
            shortcuts: [
                {
                    text: '今天',
                    value() {
                        const end = new Date()
                        const start = new Date()
                        return [start, end]
                    },
                },
                {
                    text: '昨天',
                    value() {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 1)
                        end.setTime(end.getTime() - 3600 * 1000 * 24 * 1)
                        return [start, end]
                    },
                },
                {
                    text: '一周',
                    value () {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
                        return [start, end]
                    },
                },
                {
                    text: '一月',
                    value () {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
                        return [start, end]
                    },
                },
                {
                    text: '三月',
                    value () {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
                        return [start, end]
                    },
                },
                {
                    text: '一年',
                    value () {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 365)
                        return [start, end]
                    },
                },
                {
                    text: '三年',
                    value () {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(
                            start.getTime() - 3600 * 1000 * 24 * 365 * 3
                        )
                        return [start, end]
                    },
                },
            ],
        },
    },
    mutations: {},
    actions: {},
    modules: {
        user,
        app,
        goods,
        article,
        config,
        drawer,
        service,
        chat,
        home
    },
})
