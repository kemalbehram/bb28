import { get, post } from '@/libs/request'
import { setToken, getToken, removeToken } from '@/libs/util'

export default {
    state: {
        token: getToken(),
        access: '',
        avatarImgPath: '',
        userId: '',
        hasGetInfo: false,
        userName: '',
        wsHost: null,
        serviceToken: null,
    },
    getters: {},
    mutations: {
        setAccess (state, access) {
            state.access = access
        },
        setToken (state, token) {
            state.token = token
            setToken(token)
        },
        setServiceToken (state, token) {
            state.serviceToken = token
        },
        setHasGetInfo (state, status) {
            state.hasGetInfo = status
        },
        setAvatar (state, avatarPath) {
            state.avatarImgPath = avatarPath
        },
        setUserId (state, userId) {
            state.userId = userId
        },
        setUserName (state, name) {
            state.userName = name
        },
        setWsHost (state, data) {
            state.wsHost = data
        },
    },
    actions: {
        handleLogin ({ commit }, that) {
            that.loading = true
            return post('/auth/login', that.form, false).then((response) => {
                that.loading = false
                if (response.code !== 200) {
                    return that.$Message.warning(response.message)
                }
                that.$Message.success(response.message)

                setToken(response.data.access_token)
                commit('setAccess', [])
                commit('setToken', response.data.access_token)
                return response
            })
        },

        // 退出登录
        handleLogOut ({ state, commit }) {
            return new Promise((resolve, reject) => {
                // logout(state.token).then(() => {
                //   commit('setToken', '')
                //   commit('setAccess', [])
                //   resolve()
                // }).catch(err => {
                //   reject(err)
                // })
                // 如果你的退出登录无需请求接口，则可以直接使用下面三行代码而无需使用logout调用接口
                commit('setToken', '')
                commit('setAccess', [])
                resolve()
            })
        },

        removeToken () {
            removeToken()
        },

        getUserInfo ({ commit }) {
            return get('/auth/get', {}, false).then((res) => {

                if (res.code === 200) {
                    const data = res.data
                    commit('setUserId', data.id)
                    commit('setAvatar', data.avatar)
                    commit('setAccess', [])
                    commit('setHasGetInfo', true)
                    commit('setServiceToken', data.service_token)
                    commit('setWsHost', data.ws_host)
                    commit('setAccess', data.access)
                }
                return res.data
            })
        },
    },
}
