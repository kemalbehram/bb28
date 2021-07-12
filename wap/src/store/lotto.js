export default {
    state: {
        tw28: {
            room1: [],
            room2: [],
            room3: [],
            room4: [],
            room5: [],
            room6: [],
        },
        ca28: {
            room1: [],
            room2: [],
            room3: [],
            room4: [],
            room5: [],
            room6: [],
        },
        cw28: {
            room1: [],
            room2: [],
            room3: [],
            room4: [],
            room5: [],
            room6: [],
        },
        de28: {
            room1: [],
            room2: [],
            room3: [],
            room4: [],
            room5: [],
            room6: [],
        },
        ylde28: {
            room1: [],
            room2: [],
            room3: [],
            room4: [],
            room5: [],
            room6: [],
        },
        bit28: {
            room1: [],
            room2: [],
            room3: [],
            room4: [],
            room5: [],
            room6: [],
        },
        stop_bet: true,
    },

    actions: {
        lottoScrollBottom() {
            setTimeout(() => {
                let el = document.getElementById('lotto-chat-window')
                if (el) {
                    el.scrollTop = el.scrollHeight
                }
            }, 10)
        },
        lottoScrollTop() {
            setTimeout(() => {
                let el = document.getElementById('lotto-chat-window')
                if (el) {
                    el.scrollTop = 0
                }
            }, 10)
        },
        removeLotto({ state }) {
            state = {
                tw28: {
                    room1: {},
                    room2: {},
                },
                ca28: {
                    room1: [],
                    room2: [],
                },
                cw28: {
                    room1: [],
                    room2: [],
                },
                de28: {
                    room1: [],
                    room2: [],
                },
                ylde28: {
                    room1: [],
                    room2: [],
                },
                stop_bet: true,
            }

            // for (let key in state) {
            //     if (key !== 'unread') {
            //         state[key] = []
            //     }
            // }
        },
    },
}
