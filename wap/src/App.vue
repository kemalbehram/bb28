<template>
    <div id="app">
        <keep-alive :exclude="exclude">
            <router-view></router-view>
        </keep-alive>
    </div>
</template>
<script>
import LaravelEcho from './libs/laravel-echo'
export default {
    data() {
        return {
            exclude: ['lottoTraRoom', 'traLottoPage', 'vote', 'userIndex', 'homelotto', 'userPromote', 'nextLevel', 'chatLottoPage', 'profit', 'room', 'bank'],
        }
    },
    created() {
        this.$store.dispatch('getConfig')
        this.$store.dispatch('getUserInfo')
        // const reg = new RegExp('(^|&)ref=([^&]*)(&|$)', 'i')
        // const temp = window.location.hash.substr(1).match(reg)
        const temp = this.getQuery('ref')

        // console.log(temp)
        if (temp != false) {
            // let ref = decodeURI(temp[2])
            this.$cookie.set('__mh_ref_id__', temp, { expires: 2592000 })
        }
    },

    watch: {
        '$store.state.user.info.id'(value) {
            if (!value) return false
            this.$store.dispatch('getChatItems')
            setTimeout(() => {
                LaravelEcho.connect()
            }, 500)
            setTimeout(() => {
                LaravelEcho.notify()
                //如果直接进入游戏路由
                if (this.$route.meta.ws === 'lotto') {
                    console.log('进入游戏')
                    LaravelEcho.lottoJoin(this.$route.params.name)
                }
            }, 500)
            setTimeout(() => {
                //如果直接进入聊天室
                if (this.$route.meta.ws === 'chat') {
                    //查询聊天记录
                    this.$store.dispatch('getChatsItems')

                    LaravelEcho.chatJoin()
                }
            }, 500)

            if (this.$store.state.user.info.real_name) return true
        },

        $route(to, from) {
            if (to.meta.ws === 'lotto' && this.$store.state.user.info.id) {
                this.$store.state.lotto.items = []
                setTimeout(() => {
                    LaravelEcho.lottoJoin(to.params.name)
                }, 500)
            }
            if (from.meta.ws === 'chat') {
                setTimeout(() => {
                    LaravelEcho.chatLeave()
                }, 500)
            }
            if (to.meta.ws === 'chat') {
                setTimeout(() => {
                    this.$store.dispatch('getChatsItems')
                    LaravelEcho.chatJoin()
                }, 500)
            }

            if (from.meta.ws === 'lotto') {
                setTimeout(() => {
                    LaravelEcho.lottoLeave(from.params.name)
                }, 500)
            }
            // if (to.name === 'home' && from.name !== null) {
            //     this.$store.dispatch('getConfig')
            // }
        },
    },
    mounted() {
        var that = this
        window.onunload = function () {
            if (this.$route.meta.ws === 'lotto') {
                console.log('onunload-lottoLeave')
                LaravelEcho.lottoLeave(this.$route.params.name)
            }
        }
        window.onbeforeunload = function () {
            if (this.$route.meta.ws === 'lotto') {
                console.log('onbeforeunload-lottoLeave')

                LaravelEcho.lottoLeave(this.$route.params.name)
            }
        }
    },
    methods: {
        getQuery(val) {
            const w = location.hash.indexOf('?')
            const query = location.hash.substring(w + 1)

            const vars = query.split('&')
            for (let i = 0; i < vars.length; i++) {
                const pair = vars[i].split('=')
                if (pair[0] == val) {
                    return pair[1]
                }
            }

            return false
        },
    },
}
</script>
<style lang="less">
body {
    background: #f6f6f6;
}
</style>
