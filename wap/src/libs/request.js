import axios from '@/libs/axios'
import { Toast } from 'vant'
import { Notify } from 'vant'

export function get(url, params = {}, retry = 3) {
   
    return new Promise((resolve, reject) => {
        let headers = { ErrorRetry: retry }
        axios
            .get(url, { params, headers })
            .then((res) => {
                resolve(res)
            })
            .catch((err) => {
                reject(err)
            })
    })
}

export function post(url, data = {}, loading = true, notify = true) {
    Notify.clear()
    if (loading === true) {
        Toast.loading('请稍后...')
    }
    return new Promise((resolve, reject) => {
        axios.post(url, data).then(
            (res) => {
                if (loading === true) Toast.clear()
                if (notify === true) {
                    if (res.code != 200) {
                        Notify(res.message)
                    } else {
                        Notify({
                            message: res.message,
                            background: '#07c160',
                        })
                    }
                }
                resolve(res)
            },
            (err) => {
                reject(err)
            }
        )
    })
}

export function getList(url, params = {}) {
    return new Promise((resolve, reject) => {
        let getParam = {
            params: params,
        }
        getParam.params.page = this.page.current + 1
        this.loading = true
        axios
            .get(url, getParam)
            .then((res) => {
                if (res.code !== 200) {
                    this.error = true
                    this.loading = false
                    this.errorText = res.message
                    return resolve(res)
                }

                this.loading = false
                this.items = this.items.concat(res.data.items)
                if (res.data.page.current >= res.data.page.last) {
                    this.finished = true
                }
                this.page = res.data.page
                resolve(res)
            })
            .catch((err) => {
                reject(err)
            })
    })
}
