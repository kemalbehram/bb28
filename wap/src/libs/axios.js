import axios from 'axios'
import config from '@/config'
import store from '@/store'

// axios 配置
const baseUrl =
    process.env.NODE_ENV === 'development'
        ? config.baseUrl.dev
        : config.baseUrl.pro
axios.defaults.timeout = 30000
axios.defaults.baseURL = baseUrl

// http request 拦截器
axios.interceptors.request.use(
    (config) => {
        let bearerToken = store.state.user.token
        bearerToken && (config.headers.Authorization = 'Bearer ' + bearerToken)
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

// http response 拦截器
axios.interceptors.response.use(
    (response) => {
        if (response.data.code === 401) {
            store.dispatch('removeToken')
        }

        try {
            if (
                response.data.data.wallet &&
                response.data.data.wallet.balance
            ) {
                store.state.user.wallet = response.data.data.wallet
            }
        } catch (error) {
            console.log(error)
        }

        return response.data
    },
    (error) => {
        var headers = error.config.headers
        if (headers.ErrorRetry > 0) {
            headers.ErrorCount = headers.ErrorCount || 0
            if (headers.ErrorCount < headers.ErrorRetry) {
                headers.ErrorCount += 1
                var backOff = new Promise((resolve) => {
                    setTimeout(() => {
                        resolve()
                    }, 3000)
                })
                return backOff.then((r) => {
                    let baseURL = error.config.baseURL
                    let newURL = error.config.url.replace(baseURL, '')
                    error.config.url = newURL
                    return axios(error.config)
                })
            }
        }

        var data = { code: 500, message: '程序内部错误 请联系客服处理' }
        return data
    }
)

export default axios
