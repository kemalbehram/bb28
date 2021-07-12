import Vue from 'vue'
import accounting from 'accounting'

Vue.filter('currency', function(val) {
    return accounting.formatNumber(val, 3, '')
})

Vue.filter('date_lint', function(val) {
    return val.substring(5)
})

Vue.filter('time_lint', function(val) {
    return val.substring(11)
})

Vue.filter('pre_zero', function(num, n = 2) {
    if (num.length > n) {
        return num
    }
    return (Array(n).join(0) + num).slice(-n)
})
Vue.filter('cut_name', function(num) {
    return num.substr(0,1).toUpperCase();
})
Vue.filter('sub_last', function(val) {
    let newval=val.toString()
    return 'color-'+newval.substr(newval.length-1,1)
})

Vue.filter('contact_qq', function(qq) {
    return 'tencent://message/?uin=' + qq + '&Menu=yes'
})

Vue.filter('trim', function(str, tag = 'br') {
    if (str == null || str === '' || str === undefined) {
        return ''
    }

    var html = '<' + tag + '/>'
    return str.replace(/\n|\r\n/g, html)
})

Vue.filter('hidden_user', function(val) {
    return '**' + val.toString().substr(-2)
})
