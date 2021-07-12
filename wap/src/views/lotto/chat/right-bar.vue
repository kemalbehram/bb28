<template>
    <div class="content">
        <div @click="squint" v-show="show">
            <van-image src="https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/close-eye.png" />
            <span class="font-12">眯牌</span>
        </div>
        <div @click="showTrend" v-show="show">
            <van-image src="https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/icon_zs.png" />
            <span class="font-12">走势</span>
        </div>
        <div @click="showRule" v-show="show">
            <van-image src="https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/icon_gz.png" />
            <span class="font-12">规则</span>
        </div>
        <!-- <div class="show-hide mb-6">
            <van-icon @click="show=!show" color="white" name="arrow-down" size="20" v-if="show" />
            <van-icon @click="show=!show" color="white" name="arrow-up" size="20" v-else />
        </div>-->
        <div @click="$router.push('/service')" class="service font-12">客服</div>
        <div @click="$router.push('/wallet/recharge')" class="recharge font-12">充值</div>
        <div @click="$router.push('/wallet/withdraw')" class="withdraw font-12">提现</div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            show: true,
        }
    },
    methods: {
        showRule() {
            this.$parent.$refs.nav.$refs.rule.show = !this.$parent.$refs.nav.$refs.rule.show
        },
        showTrend() {
            this.$router.push({
                name: 'lottoTrend',
                params: {
                    name: this.$route.params.name,
                },
            })
        },
        squint() {
            this.$emit('squint')
        },
    },
}
</script>
<style lang="less" scoped>
.content {
    position: fixed;
    right: 5px;
    bottom: 10%;
    // height: 150px;
    z-index: 10;
    div {
        text-align: center;
        color: #aeaeae;
        /deep/.van-image {
            display: block;
            width: 10px;
            background: #56abef;
            padding: 8px 8px;
            height: 30px;
            width: 30px;
            border-radius: 50%;
        }
    }
    .show-hide {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #aad5f7;

        line-height: 40px;
    }
    .service,
    .recharge,
    .withdraw {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        line-height: 30px;
        color: white;
        margin-bottom: 6px;
    }
    .service {
        background: #a972d6;
    }
    .recharge {
        background: #e07752;
    }
    .withdraw {
        background: #77a3dc;
    }
}
</style>