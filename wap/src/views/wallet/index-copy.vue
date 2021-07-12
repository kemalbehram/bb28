<template>
    <div class="wrap pb-64">
        <div class="my-info">
            <span class="title">余额(元)</span>
            <span class="balance">{{wallet.balance}}</span>
        </div>
        <van-cell :icon="withdraw_img" class="nav" is-link title="提现" to="/wallet/withdraw" />
        <van-cell :icon="reacharge_img" class="nav" is-link title="充值" to="/wallet/recharge" />
        <br />
        <van-cell :icon="bank_img" class="nav" is-link title="我的银行卡" to="user/setting/bank" />
        <van-cell class="nav" icon="manager" is-link title="在线客服" to="/service" />
        <lettTabbar />
    </div>
</template>
<script>
import lettTabbar from '_c/lett-tabbar'
export default {
    data() {
        return {
            withdraw_img: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/withdraw.png',
            reacharge_img: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/recharge.png',
            bank_img: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/bank.png',
        }
    },
    components: {
        lettTabbar,
    },
    computed: {
        wallet() {
            return this.$store.state.user.wallet
        },
    },
}
</script>
<style lang="less" scoped>
.my-info {
    height: 26vh;
    background-image: url('https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_info.jpg');
    background-size: 100% 100%;
    position: relative;
    color: white;
    .title {
        position: absolute;
        top: 35%;
        left: 6%;
    }
    .balance {
        font-size: 32px;
        position: absolute;
        top: 60%;
        left: 6%;
    }
}
.nav {
    /deep/.van-cell__title {
        margin-left: 10px;
    }
}
</style>