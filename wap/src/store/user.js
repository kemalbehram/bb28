import cookie from 'js-cookie'
import config from '@/config'
import { get, post } from '@/libs/request'

export default {
    state: {
        token: cookie.get(config.tokenField),
        info: {
            id: null,
        },
        wallet: {},

        option: {
            bet_chat: true,
        },
        was_login: false,
    },

    getters: {
        hideMobile (state, getters) {
            return function () {
                if (state.info.mobile != undefined) {
                    let str = '';
                    let phoneArr = state.info.mobile.split('');
                    phoneArr.map((res, index) => {
                        if (index > 2 && index < 7) {
                            str += '*';

                        } else {
                            str += res;

                        }
                    });
                    return str;
                }

            }
        }
    },

    mutations: {
        setUserInfo (state, info) {
            state.info = info
        },
        // setSafeWord (state) {
        //     state.info.safe_word = true
        // }

    },

    actions: {
        setToken ({ state }, token) {
            if (token) {
                let key = config.tokenField
                cookie.set(key, token, { expires: 2592000 })
                state.token = token
            }
        },

        removeToken ({ state }) {
            state.token = ''
            state.info = {}
            state.wallet = {}
            state.was_login = true
            cookie.remove(config.tokenField)
        },

        getUserInfo ({ state, commit }) {
            let local = localStorage.getItem('user_option')
            if (local) state.option = JSON.parse(local)

            if (state.info.length > 0) {
                return new Promise((resolve) => resolve(state.ids))
            }
            return get('/user/get', {}, false).then((res) => {
                if (res.code === 200) {
                    commit('setUserInfo', res.data)
                    state.wallet = res.data.wallet
                    state.option = res.data.option
                    localStorage.setItem(
                        'user_option',
                        JSON.stringify(res.data.option)
                    )
                }
            })
        },

    },
}
