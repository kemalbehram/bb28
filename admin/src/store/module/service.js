export default {
    state: {
        modal: false,
        award_modal: false,
        new: false,
        active: null,
        lasts: {},
        user_new: false,
    },

    mutations: {
        setLasts(state, params) {
            state.lasts = params
        },
    },

    actions: {
        startServiceChat({ state, commit }, params) {
            if (state.lasts.hasOwnProperty(params.id) == false) {
                let temp = {
                    id: params.id,
                    last_at: Date.parse(new Date()) / 1000,
                    record: [],
                    has_get: true,
                    target: {
                        avatar: params.avatar,
                        id: params.id,
                        nickname: params.nickname,
                        unread: true,
                    },
                }

                let lasts = JSON.parse(JSON.stringify(state.lasts))
                lasts[params.id] = temp
                commit('setLasts', lasts)
            }
            state.active = params.id
            state.modal = true
        },
    },
}
