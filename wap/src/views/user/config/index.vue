<template>
    <div class="relative">
        <userHeader title="设置" />
        <van-cell-group>
            <van-cell title="消息时间显示">
                <!-- 使用 title 插槽来自定义标题 -->
                <template #default>
                    <van-switch @change="change" size="20px" v-model="checked" />
                </template>
            </van-cell>
        </van-cell-group>
        <van-cell-group>
            <van-cell @click="toUrl" is-link title="关于我们" />
        </van-cell-group>
        <van-cell-group>
            <van-cell @click="onLogout" is-link title="退出" />
        </van-cell-group>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
    },
    computed: {
        checked() {
            let user = this.$store.state.user
            if (user != undefined) {
                console.log(user.option)
                return user.option.block_time
            }
            return false
        },
    },
    methods: {
        onLogout() {
            this.$store.dispatch('removeToken')
            // window.Echo.disconnect()
            this.$router.replace({
                path: '/auth/login',
                query: {
                    verification: 1022,
                },
            })
        },
        change(value) {
            this.$post('user/option-update', { block_time: value }).then((res) => {
                if (res.code === 200) {
                    this.$store.state.user.option.block_time = value
                }
            }) //
        },
        toUrl() {
            this.$router.push({ path: '/config/setting/about' })
        },
    },
}
</script>
<style lang="less" scoped>
.van-cell-group {
    margin-top: 13px;
    .van-cell {
        padding: 15px;
    }
    /deep/.van-switch--on {
        background-color: #ff6a00;
    }
}
</style>