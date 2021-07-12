<template>
    <div id="app">
        <lett-loading :visible="loading" @click="getConfig" z-index="1000" />
        <lett-loading :visible="confirm" @click="confirm = false" z-index="1000" />
        <router-view></router-view>

        <lotto-get />
        <member-info />
        <bet-log-get />

        <Modal class-name="service-chat" draggable footer-hide scrollable v-if="$store.state.user.hasGetInfo" v-model="$store.state.service.modal" width="1000">
            <service />
        </Modal>
        <Modal class-name="award-chat" draggable footer-hide scrollable v-if="$store.state.user.hasGetInfo" v-model="$store.state.service.award_modal" width="1000">
            <awardchat />
            <!-- <div>conetent contetn</div> -->
        </Modal>
    </div>
</template>

<script>
import lottoGet from '@/views/drawer/lotto-get'
import betLogGet from '@/views/drawer/bet-log-get'
import memberInfo from '@/views/drawer/member-info'
import service from '@/views/service'
import awardchat from '@/views/awardchat'
import { getToken } from '@/libs/util'
import LaravelEcho from './libs/laravel-echo'
export default {
    components: {
        lottoGet,
        betLogGet,
        memberInfo,
        service,
        awardchat,
    },
    name: 'App',
    data() {
        return {
            loading: false,
            count: 0,
            serv_modal: true,
            confirm: false,
            auto_load: null,
        }
    },

    watch: {
        '$store.state.user.hasGetInfo'(value) {
            if (value !== true) return false

            LaravelEcho.connect()
            setTimeout(() => {
                LaravelEcho.service()
            }, 3000)
            setTimeout(() => {
                LaravelEcho.chatJoin()
            }, 3000)
            this.checkUnread()
        },
    },

    created() {
        this.$nextTick(() => {
            this.confirm = '请人工点击此页面 关闭提示层'
        })
        this.getConfig()
        this.$Notice.config({
            top: 112,
        })
    },

    methods: {
        getConfig() {
            this.count++
            if (this.count > 1) this.loading = true
            this.$store.dispatch('getConfig').then((res) => {
                if (res.code !== 200) {
                    return (this.loading = '全局配置加载失败 请点击重试')
                }
                this.loading = false
            })
        },

        checkUnread() {
            this.auto_load = setInterval(() => {
                this.$get('/service/check', {}, false).then((res) => {
                    if (res.code !== 200) return false
                    if (res.data.unread >= 1) {
                        var audio = new Audio(this.$store.state.config.audio.message)
                        audio.play()
                    }
                })
            }, 20000)
        },
    },
}
</script>

<style lang="less">
.size {
    width: 100%;
    height: 100%;
}
html,
body {
    .size;
    overflow: hidden;
    margin: 0;
    padding: 0;
}
#app {
    .size;
}

.service-chat {
    .ivu-modal-body {
        background: @bg-base;
        padding: 0;
    }

    .ivu-modal-close {
        top: 12px;
        right: 6px;
    }
}
.award-chat {
    .ivu-modal-body {
        height: 800px;
    }
}
</style>
