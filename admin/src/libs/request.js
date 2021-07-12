import axios from '@/libs/axios'

export function get(url, params = {}, loading = true, retry = 0) {
    if (loading === true) {
        var that = this
        that.loading = true
    }

    return new Promise((resolve, reject) => {
        let headers = { ErrorRetry: retry }
        axios
            .get(url, { params, headers })
            .then((res) => {
                if (loading === true) {
                    that.loading = false
                    if (res.code !== 200) {
                        return (that.loading = res.message)
                    }
                }
                resolve(res)
            })
            .catch((err) => {
                reject(err)
            })
    })
}

export function post(url, data = {}, loading = true) {
    if (loading === true) {
        var that = this
        that.loading = true
    }
    return new Promise((resolve, reject) => {
        axios.post(url, data).then(
            (res) => {
                if (loading === true) {
                    that.loading = false
                    if (res.code !== 200) {
                        return that.$Message.warning(res.message)
                    }
                    that.$Message.success(res.message)
                }
                resolve(res)
            },
            (err) => {
                reject(err)
            }
        )
    })
}
