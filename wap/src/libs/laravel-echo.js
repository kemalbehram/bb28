import Echo from 'laravel-echo'
import io from 'socket.io-client'
import Store from '@/store'
import { Notify, Toast } from 'vant'

export default {
    connect: function() {
        window.io = io
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            // host: 'http://' + document.domain + ':6008',
            host: Store.state.user.info.ws_host,
            //host:'http://localhost:6001',
            auth: {
                headers: {
                    Authorization: 'Bearer ' + Store.state.user.token,
                },
            },
        })
    },

    notify: function() {
        var userId = Store.state.user.info.id
        window.Echo.private('user.' + userId)
            .listen('Service', (e) => {
                Store.state.push.items.push(e)
                Store.state.push.unread = true

                if (e.type == 'bet') {
                    Store.dispatch('chatScrollBottom')
                }

                if (e.from == '100000' && e.message_type == 'text') {
                    Notify({ type: 'success', message: '您有新的客服消息' })
                } else {
                }

                // if (e.data.message_type == 'text') {
                //     Notify({ type: 'success', message: '您有新的客服消息' })
                // }
            })
            .listen('Toast', (e) => {
                // Toast.success()
                Notify({ type: 'success', message: e.message })
                if (e.wallet) {
                    Store.state.user.wallet = e.wallet
                }

                // if (e.action) {
                //     Store.state.ws_action[e.action] += 1
                // }
            })
            .listen('Notice', (e) => {
                Notify({
                    type: e.type || 'primary',
                    message: e.message,
                    duration: e.duration || 5000,
                })

                if (e.wallet) {
                    Store.state.user.wallet = e.wallet
                }
            })
            .listen('Notify', (e) => {
                Notify({
                    type: e.type || 'primary',
                    message: e.message,
                    duration: e.duration || 5000,
                })

                if (e.wallet) {
                    Store.state.user.wallet = e.wallet
                }
            })
    },

    lottoJoin: function($lotto) {
        window.Echo.join('lotto.' + $lotto).listen('ChatBet', (e) => {
            // console.log(e.lotto_name, e.play_type)
            // console.log(Store.state.lotto[e.lotto_name])
            setTimeout(() => {
                if (e.cat != 6) {
                    Store.state.lotto[e.lotto_name][e.play_type].push(e)
                }
            }, 500)
        })
    },
    lottoLeave: function($lotto) {
        // Store.state.lotto.items = []
        Store.dispatch('removeLotto')
        window.Echo.leave('lotto.' + $lotto)
    },

    //群聊
    chatJoin: function() {
        window.Echo.join('chat.group')
            .here((users) => {
                Store.commit('updateUser', users)
                //console.log(Store.state.chat.online_user)
            })
            .joining((user) => {
                Notify(user.nickname + '加入聊天室')
                console.log(user.nickname + '加入聊天室')
            })
            .listen('Message', (e) => {
                Store.state.chat.chat_contents.push(e)
                Store.dispatch('chatsScrollBottom')
            })
            .leaving((user) => {
                let new_online_user = Store.state.chat.online_user.filter(
                    (item) => item.id != user.id
                )
                Store.commit('updateUser', new_online_user)
            })
    },

    chatLeave: function() {
        window.Echo.leave('chat.group')
    },

    disconnect: function() {
        window.Echo.disconnect()
    },
}
