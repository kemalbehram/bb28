import { Toast } from 'vant'

export function toastLoading(message = '请稍后...') {
    return Toast.loading({
        duration: 0,
        forbidClick: true,
        loadingType: 'spinner',
        message: message
    })
}
