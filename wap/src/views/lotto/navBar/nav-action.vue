<template>
    <van-action-sheet :actions="actions" :round="false" @select="onSelect" cancel-text="关闭" v-model="show" />
</template>

<script>
export default {
    props: {
        content: String,
    },
    data() {
        return {
            show: false,
            // actions: [
            //     {name: '快速投注', action: 'lottotra'},
            //     { name: '游戏规则', action: 'lottoRule' },
            //     {
            //         name: '我要充值',
            //         action: 'route',
            //         route: { name: 'walletRecharge' },
            //     },
            //     {
            //         name: '用户中心',
            //         action: 'route',
            //         route: { name: 'user' },
            //     },
            //     {
            //         name: '投注记录',
            //         action: 'route',
            //         route: { name: 'vote' },
            //     },
            // ],
        }
    },
    computed: {
        actions() {
            let actions = [
                {
                    name: '投注记录',
                    action: 'route',
                    route: { name: 'vote' },
                },
                // {
                //     name: this.$route.path.indexOf('chat') != -1 ? '快速投注' : '聊天投注',

                //     action: this.$route.path.indexOf('chat') != -1 ? 'lottotra' : 'lottochat',
                // },
                { name: '游戏规则', action: 'lottoRule' },
                {
                    name: '我要充值',
                    action: 'route',
                    route: { name: 'walletRecharge' },
                },
                {
                    name: '我要提现',
                    action: 'route',
                    route: { name: 'walletWithdraw' },
                },
            ]

            return actions
        },
    },

    methods: {
        onSelect(data) {
            if (data.action === 'route') {
                this.$router.push(data.route)
            }
            if (data.action === 'lottoRule') {
                this.$parent.$refs.rule.show = true
            }
            if (data.action == 'lottotra') {
                this.$router.push({
                    name: 'lottoTraRoom',
                    params: {
                        name: this.$route.params.name,
                        room_id: this.$route.params.room_id,
                    },
                })
                // console.log(this.$route.params.name)
            }
            if (data.action == 'lottochat') {
                this.$router.push({
                    name: 'lottoChatRoom',
                    params: {
                        name: this.$route.params.name,
                        room_id: this.$route.params.room_id,
                    },
                })
            }
        },
    },
}
</script>
