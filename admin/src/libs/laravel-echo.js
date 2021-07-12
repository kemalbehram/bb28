import Echo from 'laravel-echo'
import io from 'socket.io-client'
import Store from '@/store'
import { Notice, Message } from 'view-design'

export default {
    connect: function() {
        window.io = io
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: Store.state.user.wsHost + ':6010',
            // host: 'https://ws.1000200.com:6020',
            // host: 'http://localhost:6001',

            auth: {
                headers: {
                    Authorization: 'Bearer ' + Store.state.user.serviceToken,
                    Source: 'admin',
                },
            },
        })
    },

    service: function() {
        window.Echo.private('user.100000')
            .listen('Service', (e) => {
                var target = null
                if (e.from === 100000) {
                    target = e.to
                } else {
                    target = e.from
                    var audio = new Audio(Store.state.config.audio.message)
                    audio.play()
                    // Store.state.service.modal = true
                }
                if (target in Store.state.service.lasts) {
                    Store.state.service.lasts[target].record.push(e)
                    Store.state.service.lasts[target].last_at = e.last_at

                    if (
                        Store.state.service.active === target &&
                        Store.state.service.modal === true
                    ) {
                        Store.state.service.lasts[target].unread = false
                    } else {
                        Store.state.service.lasts[target].unread = true
                    }

                    if (Store.state.service.modal !== true) {
                        Store.state.service.new = true
                    }
                    setTimeout(() => {
                        let msg = document.getElementById('chat-window')
                        msg.scrollTop = msg.scrollHeight
                    }, 100)
                } else {
                    Store.state.service.user_new = true
                    // var data = {
                    //     record: [e],
                    //     last_at: e.last_at,
                    //     unread: true,
                    //     target: e.target
                    // }
                    // Store.state.service.lasts[target] = data
                    // Store.state.service.lasts = JSON.parse(
                    //     JSON.stringify(Store.state.service.lasts)
                    // )
                }
            })
            .listen('Notice', (e) => {
                if (e.type == 'bet') {
                    Store.state.home.lotto = e.detail
                    console.log(Store.state.home.lotto)
                    return
                }
                // if (e.message) {
                //     Notice.open({
                //         duration: e.duration ? e.duration : 0,
                //         title: e.message,
                //         desc: e.desc,
                //     })
                // }
                if (
                    (e.audio == 'recharge' || e.audio == 'withdraw') &&
                    e.detail != undefined
                ) {
                    Store.state.home.audit.push(e.detail)
                }
                if (e.audio) {
                    var audio = new Audio(Store.state.config.audio[e.audio])
                    audio.play()
                }
            })
    },
    //群聊
    chatJoin: function() {
        window.Echo.join('chat.group')
            .here((users) => {
                Store.commit('updateUser', users)
                // console.log(1);
                // console.log(Store.state.chat.online_user)
            })
            .joining((user) => {
                // this.$Message.success(user.nickname + '加入聊天室')
                Message.warning(user.nickname + '加入聊天室')

                // Notify();
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
        console.log('退出')
        window.Echo.leave('chat.group')
    },

    disconnect: function() {
        window.Echo.disconnect()
    },
}
