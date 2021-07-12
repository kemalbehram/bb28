<template>
    <div class="setting">
        <userHeader title="密码安全" />
        <div class="withdraw-account">
            <van-button class="button-info" color="@orange" v-if="user.real_name">尊敬的{{user.real_name}}你好</van-button>
            <van-button @click="toUrl('/user/setting/realname')" class="button-info" color="@orange" v-else>你还未绑定姓名</van-button>
        </div>
        <van-cell-group>
            <van-cell :icon="item.icon" :title="item.title" :value="item.value" @click="toUrl(item.route)" is-link v-for="(item,index) in menu" />
        </van-cell-group>
    </div>
</template>

<script>
import config from '@/config'
import userHeader from '@/components/user-header'
import LaravelEcho from '@/libs/laravel-echo'
export default {
    computed: {
        user() {
            return this.$store.state.user.info
        },
        version() {
            return config.version
        },
    },
    components: {
        userHeader,
    },
    computed: {
        user() {
            return this.$store.state.user.info
        },
        menu() {
            return [
                // {
                //     icon: 'phone-o',
                //     route: '/user/setting/mobile',
                //     title: '绑定手机',
                //     value: this.user.mobile ? this.user.mobile : '未绑定手机',
                // },
                {
                    icon: 'edit',
                    route: '/user/setting/qq',
                    title: '绑定QQ',
                    value: '',
                },
                // {
                //     icon: 'idcard',
                //     route: '/user/setting/bank',
                //     title: '绑定银行卡',
                //     value: this.user.bind_bank ? '' : '未绑定银行卡',
                // },
                // {
                //     icon: 'user-o',
                //     route: '/user/setting/realname',
                //     title: '提现姓名',
                //     value: this.user.real_name ? this.user.real_name : '未绑定姓名',
                // },
                {
                    icon: 'coupon-o',
                    route: '/user/setting/password',
                    title: '登录密码',
                    value: '',
                },
                {
                    icon: 'birthday-cake-o',
                    route: '/user/setting/safe-word',
                    title: '资金密码',
                    value: '',
                },
            ]
        },
    },
    methods: {
        toUrl(path) {
            this.$router.push({ path: path })
        },
    },
}
</script>

<style  lang="less" scoped>
.withdraw-account {
    height: 32vh;
    width: 100%;
    background: url(http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/bg/accountbg.png) 50% no-repeat;
    background-size: cover;
    display: flex;
    justify-content: center;
    .button-info {
        // width: 4.8rem;
        top: 75%;
        background: @orange;
        width: 50%;
        height: 2.5rem;
        border-radius: 0.3rem;
        position: relative;
        margin: 0 auto;
    }
}
.van-cell {
    padding: 16px;
}
</style>
