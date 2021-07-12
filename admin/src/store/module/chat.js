import { get, post } from '@/libs/request'

export default {
  state: {
    online_user: [],
    chat_contents: [],
  },

  mutations: {
    updateUser (state, p) {
      state.online_user = p
    },
    updateChatContents (state, content) {
      state.chat_contents = content
    }
  },

  actions: {
    chatsScrollBottom () {

      setTimeout(() => {
        let msg = document.getElementById('chat-content')

        if (msg) {

          msg.scrollTop = msg.scrollHeight;
        }
      }, 0)
    },
    getChatsItems ({ state }) {
      get('chat/index').then((res) => {
        state.chat_contents = [];
        var item = res.data.items.reverse();
        state.chat_contents = item.concat(state.chat_contents)
        this.dispatch('chatsScrollBottom')
      })
    }
  },
}
