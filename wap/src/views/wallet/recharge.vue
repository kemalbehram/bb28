<template>
    <div class="recharge-area">
        <userHeader title="充值" />
        <div class="note">充值前请先加客服QQ:{{$store.state.config.service_qq[0]}},{{$store.state.config.service_qq[1]}}</div>
        <van-cell-group>
            <van-cell :key="index" :title="item.title" :value="item.intro" @click="toRecharge(item)" v-for="(item,index) in items" />
        </van-cell-group>
        <!-- <van-grid :border="false" :column-num="2" class="mb-10">
            <van-grid-item :key="index" v-for="(item,index) in this.$store.state.config.service_qq">
                <div class="item">
                    <p>客服QQ</p>
                    <p>{{item}}</p>
                </div>
            </van-grid-item>
        </van-grid>-->
        <!-- <van-button @click="toBack()" block class="recharge-button">返回</van-button> -->
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
    },
    created() {
        // console.log(this.$store.state.config.service_qq)
    },
    computed: {
        serviceQQ() {
            return this.$store.state.config.service_qq
        },
        items() {
            return this.$store.state.config.recharge_aisle
        },
    },
    methods: {
        toBack() {
            this.$router.back(-1)
        },
        toRecharge(item) {
            this.$router.push({ path: '/wallet/recharge-item', query: { type: item.type } })
        },
    },
}
</script>
<style lang="less" scoped>
.recharge-area {
    height: 100%;
    background: white;
    .note {
        background: white;
        padding-left: 16px;
        color: #ff5742;
        height: 5vh;
        line-height: 5vh;
    }
    /deep/.van-grid {
        background: white;
        .item {
            width: 70%;
            text-align: center;
            background: #f5f5f5;
            padding: 0 20px;
            border-radius: 10px;
            p {
                line-height: 10px;
            }
        }
    }
    .recharge-button {
        background: #ff5742;
        color: white;
        margin: 0 auto;
        width: 92%;
        border-radius: 10px;
    }
}
</style>