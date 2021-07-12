<template>
    <div class="member-info-draw relative">
        <data-content :params="params" :parent="this" :top="top" ref="content" />

        <info-update ref="infoUpdate" />
        <wallet-update ref="walletUpdate" />
        <bet-log ref="betLog" />
        <wallet-log ref="walletLog" />
        <transfer-log ref="TransferLog" />
        <login-log ref="loginLog" />
        <notification ref="notification" />
        <next-level ref="nextLevel" />
    </div>
</template>

<script>
import dataContent from './content'
import infoUpdate from './info-update'
import walletUpdate from './wallet-update'
import betLog from './bet-log'
import walletLog from './wallet-log'
import TransferLog from './transfer-log'
import loginLog from './login-log'
import notification from './notification'
import nextLevel from './next-level'
export default {
    components: {
        dataContent,
        infoUpdate,
        walletUpdate,
        betLog,
        walletLog,
        TransferLog,
        loginLog,
        notification,
        nextLevel
    },

    props: {
        params: Object,
        top: Object
    },

    computed: {
        data() {
            return this.$refs.content.data
        }
    },

    methods: {
        getData(visible) {
            return this.$refs.content.getData(visible)
        }
    }
}
</script>