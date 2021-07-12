<template>
    <div>
        <userHeader title="提现" />
        <div class="fixed-content">
            <div class="withdraw">
                <div class="pt-10 pl-10 pb-10">提现到</div>

                <van-radio-group v-model="choosed">
                    <van-cell :key="index" v-for="(item,index) in widthdraw">
                        <template #icon>
                            <van-radio :name="index">{{item.name}}</van-radio>
                        </template>
                    </van-cell>
                </van-radio-group>
            </div>

            <div class="pt-20 pl-20 pr-20">
                <wechatItem :withdraw="items" ref="choosed" v-if="choosed=='wechat'" />
                <bankItem :withdraw="items" ref="choosed" v-else-if="choosed=='bank'" />
                <alipayItem :withdraw="items" ref="choosed" v-else-if="choosed=='alipay'" />
                <usdtItem :withdraw="items" ref="choosed" v-else-if="choosed=='usdt'" />
            </div>

            <div class="pl-20 pr-20 pb-40">
                <van-button :loading="loading" @click="withDraw" block class="mt-20" color="#F5A623" loading-type="spinner" type="primary">申请提现</van-button>
                <van-button @click="toBack" block class="mt-20" color="#F5A623" type="primary">返回</van-button>
            </div>
        </div>
    </div>
</template>
<script>
import wechatItem from './withdraw-content/wechat'
import bankItem from './withdraw-content/bank'
import alipayItem from './withdraw-content/alipay'
import usdtItem from './withdraw-content/usdt'
import userHeader from '@/components/user-header'
import { Notify } from 'vant'
export default {
    name: 'withdraw',
    data() {
        return {
            choosed: 'bank',
            items: {},
            loading: false,
        }
    },
    components: {
        userHeader,
        wechatItem,
        bankItem,
        alipayItem,
        usdtItem,
    },
    computed: {
        widthdraw() {
            return this.$store.state.config.withdraw_aisle
        },
    },

    activated() {
        this.getWithdraw()
    },
    methods: {
        getWithdraw() {
            this.$get('withdraw/get-with-draw').then((res) => {
                this.loading = true
                if (res.code !== 200) {
                    this.error = res.message
                    return false
                }
                this.loading = false
                this.items = res.data.items
            })
        },
        toBack() {
            this.$router.back(-1)
        },
        withDraw() {
            let that = this
            function beforeClose(action, done) {
                done()
                if (action === 'confirm') {
                    that.$router.push({ path: '/user/setting/safe-word' })
                }
            }
            this.loading = true
            let params = this.$refs.choosed.form
            params.type = this.choosed
            this.$post('withdraw/create', params, false).then((res) => {
                this.loading = false

                if (res.code != 200 && res.data.safe_word) {
                    this.$dialog.confirm({
                        title: '是否前往添加或修改资金密码',
                        message: '资金密码用于保护资金财产',
                        beforeClose,
                    })
                    return
                }
                this.$refs.choosed.form.amount = null
            })
        },
    },
}
</script>
<style lang="less" scoped>
.withdraw {
    // padding: 10px;
}
.fixed-content {
    height: calc(100% - 6vh);
    position: fixed;
    overflow-y: scroll;
}
</style>