import { get } from '@/libs/request'

export default {
    state: {
        audio: {},
        focus_mapping: {},
        focus_scope: {},
        lotto_items: {},
        lotto_control: ['bit28', 'de28', 'hero28','ylde28'],
        lotto_status: {
            1: '待开奖',
            2: '已开奖',
            3: '异常数据',
            4: '异常数据',
        },

        bet_status: {
            1: '待开奖',
            2: '已返奖',
            3: '取消投注',
            4: '异常数据',
        },

        withdraw_status: {
            1: '等待审核',
            2: '审核通过',
            3: '审核拒绝',
        },

        bank_status: {
            1: {
                name: '等待审核',
                class: 'color-blue',
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
        type: {
            usdt: 'USDT泰达币',
            bank: '银联',
            wechat: '微信',
            alipay: '支付宝',
            service: '系统客服',
            another:'其他',
        },
    },

    mutations: {
        setOptionItems(state, data) {
            state.items = data
            let keys = Object.keys(data)
            keys.forEach((element) => {
                state[element] = data[element]
            })
        },
    },

    actions: {
        getConfig({ state, commit }) {
            let local = localStorage.getItem('config_admin')
            if (local) commit('setOptionItems', JSON.parse(local))

            return get('/config/get', {}, false, 10).then((res) => {
                if (res.code !== 200) return res
                commit('setOptionItems', res.data)
                localStorage.setItem('config_admin', JSON.stringify(res.data))
                state.has_get = true
                return res
            })
        },
    },
}
