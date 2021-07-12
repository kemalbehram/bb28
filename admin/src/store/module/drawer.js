export default {
    state: {
        lotto_get: {
            lotto: null,
            id: null,
            visible: false,
        },

        lotto_control: {
            lotto: null,
            id: null,
            visible: false,
        },

        bet_log: {
            model: false,
            id: null,
            visible: false,
        },

        member_info: {
            id: null,
            visible: false,
        },
        rebate_config: {
            id: null,
            visible: false,
        },
    },

    actions: {
        onBetLogGet({ state }, id) {
            state.bet_log.visible = true
            state.bet_log.id = id
            state.bet_log.model = 'detail'
        },

        onBetLogGroup({ state }, id) {
            state.bet_log.visible = true
            state.bet_log.id = id
            state.bet_log.model = 'group'
        },

        onLottoGet({ state }, params) {
            state.lotto_get.visible = true
            state.lotto_get.id = params[0]
            state.lotto_get.lotto = params[1]
        },

        onLottoControl({ state }, params) {
            state.lotto_control.visible = true
            state.lotto_control.id = params[0]
            state.lotto_control.lotto = params[1]
        },

        onMemberInfo({ state }, id) {
            state.member_info.visible = true
            state.member_info.id = id
        },
        onRebateConfig({ state }) {
            state.rebate_config.visible = true
        },
    },
}
