import { get, post } from '@/libs/request'

export default {
    state: {
        loading: true,
        router:'/',
        show_pop:false,
        lotto_group: {},
        lotto_items: {},
        service_qq: ['790600000', '790800000'],
        status_bank: {
            1: {
                name: '等待审核',
                class: 'color-sub',
            },
            2: {
                name: '审核通过',
                class: 'color-green',
            },
            3: {
                name: '审核拒绝',
                class: 'color-red',
            },
            4: {
                name: '充值撤消',
                class: 'color-sub',
            },
        },
        about_route: [
            { title: '法律声明', id: 11 },
            { title: '关于我们', id: 12 },
            { title: '联系我们', id: 13 },
            { title: '商务合作', id: 14 },
            { title: '隐私声明', id: 15 },
        ],
    },

    mutations: {
        setConfigItems(state, data) {
            state.items = data
            let keys = Object.keys(data)
            keys.forEach((element) => {
                state[element] = data[element]
            })
        },
    },

    actions: {
        getConfig({ state, commit }) {
            let local = localStorage.getItem('pc_config')
            if (local) {
                commit('setConfigItems', JSON.parse(local))
            } else {
                state.loading = true
            }
            return get('/option/get', {}).then((res) => {
                if (res.code !== 200) {
                    return (state.loading = res.message)
                }
                state.loading = false
                if (res.code === 200) {
                    commit('setConfigItems', res.data)
                    localStorage.setItem('pc_config', JSON.stringify(res.data))
                    if (res.data.web_stats_code) {
                        let script = document.createElement('script')
                        script.type = 'text/javascript'
                        script.src = res.data.web_stats_code
                        document.getElementsByTagName('head')[0].appendChild(script)
                        // 引入成功
                        script.onload = function () {
                            console.log('js资源已加载成功了')
                        }
                        // 引入失败
                        script.onerror = function () {
                            console.log('js资源加载失败了')
                        }
                    }
                    return res.data
                }
            })
        },
    },
}
