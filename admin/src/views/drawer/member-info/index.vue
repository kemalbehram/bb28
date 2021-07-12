<template>
    <div class="member-info-draw">
        <Drawer :footer-hide="true" @on-visible-change="getData" title="用户详情" v-model="params.visible" width="920">
            <data-content :params="params" :parent="this" ref="content" />
        </Drawer>

        <info-update ref="infoUpdate" />
        <wallet-update ref="walletUpdate" />
        <wallet-bank ref="walletBank" />
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
import walletBank from './wallet-bank'
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
        walletBank,
        betLog,
        walletLog,
        TransferLog,
        loginLog,
        notification,
        nextLevel,
    },

    data() {
        return {
            params: this.$store.state.drawer.member_info,
        }
    },

    computed: {
        data() {
            return this.$refs.content.data
        },
    },

    methods: {
        getData(visible) {
            return this.$refs.content.getData(visible)
        },
    },
}
</script>