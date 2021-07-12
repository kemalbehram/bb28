import axios from 'axios'
import config from '@/config'
import store from '@/store'
import router from '@/router'
import { getToken } from '@/libs/util'

// axios 配置
const baseUrl =
    process.env.NODE_ENV === 'development'
        ? config.baseUrl.dev
        : config.baseUrl.pro
axios.defaults.timeout = 60000
axios.defaults.baseURL = baseUrl

// http request 拦截器
axios.interceptors.request.use(
    (config) => {
        var bearerToken = getToken()
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
            router.replace({ name: 'userLogin' })
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
        var data = { code: 500, message: '网络错误 请刷新重新' }
        return data
    }
)

export default axios
