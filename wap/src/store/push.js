import { get, post } from '@/libs/request'

export default {
    state: {
        items: [],
        unread: false
    },

    actions: {
        chatScrollBottom() {
            setTimeout(() => {
                let msg = document.getElementById('chat-content')
                if (msg) {
                    msg.scrollTop = msg.scrollHeight
                }
            }, 0)
        },

        getChatItems({ state }) {
            get('service/index').then((res) => {
                var item = res.data.items.reverse()
                state.items = item.concat(state.items)
                state.unread = res.data.unread
                this.dispatch('chatScrollBottom')
            })
        }
    },
    mutations: {
        read(state) {
            get('service/read').then((res) => {
                state.unread = false
                this.dispatch('chatScrollBottom')
            })
        }
    }
}
