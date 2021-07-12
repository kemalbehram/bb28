import Vue from 'vue'
import store from '@/store'
import accounting from 'accounting'

Vue.filter('lotto_name', function(val) {
    if (!val) return null
    var lotto = store.state.config.lotto_items
    return lotto[val].title
})

Vue.filter('currency', function(val) {
    return accounting.formatNumber(val, 3, '')
    // return (Array(3).join(0) + val).slice(-3)
})

Vue.filter('lotto_status', function(val) {
    if (!val) return null
    var status = store.state.config.lotto_status
    return status[val]
})
Vue.filter('bet_status', function(val) {
    if (!val) return null
    var status = store.state.config.bet_status
    return status[val]
})

Vue.filter('play_type', function(val) {
    if (!val) return null
    var result = store.state.config.play_types
    return result[val].title
})

Vue.filter('table_height', function(val) {
    var clientHeight = 0
    if (document.body.clientHeight && document.documentElement.clientHeight) {
        var clientHeight =
            document.body.clientHeight < document.documentElement.clientHeight
                ? document.body.clientHeight
                : document.documentElement.clientHeight
    } else {
        var clientHeight =
            document.body.clientHeight > document.documentElement.clientHeight
                ? document.body.clientHeight
                : document.documentElement.clientHeight
    }

    var result = clientHeight - val
    return result < 400 ? 400 : result
})

Vue.filter('trim', function(str) {
    if (str == null || str === '' || str === undefined) {
        return ''
    }
    return str.replace(/\n|\r\n/g, '<br/>')
})
