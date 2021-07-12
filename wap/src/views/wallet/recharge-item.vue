<template>
    <div class="recharge">
        <userHeader :title="recharge.title">
            <h2 @click="backHome" name="action">返回大厅</h2>
        </userHeader>
        <div class="content">
            <div class="recharge-info bg-white content-qrcode">
                <van-image :src="recharge.qrcode" fit="contain" width="50%" />
                <div :key="index" class="pd-3" v-for="(item,index) in recharge.extend">
                    <span class="icon-intro wrap-intro">
                        {{item}}
                        <van-icon @click="copy(item)" class="icon-intro cursor-pointer pl-5" name="description" size="18" />
                    </span>
                </div>
                <div class="pd-3" v-if="recharge.type=='usdt'">
                    <span class="icon-intro color-red">USDT汇率: 1:{{$store.state.config.usdt_rate}}</span>
                </div>
            </div>
            <div class="mt-1 bg-white content-intro">
                <van-icon class="icon-intro" color="#e57d92" name="qr" size="17" />
                <span class="icon-intro color-pink" v-if="recharge.type!='usdt'">{{recharge.intro}}</span>
                <span class="icon-intro color-pink" v-else>
                    {{recharge.intro}}
                    <span v-if="usdt_pay">
                        <b class="color-red">{{usdt_pay}}</b>
                        <span>个</span>
                    </span>
                </span>
            </div>

            <div class="content-amount">
                <van-field placeholder="请填写充值金额" type="number" v-model="amount" />
            </div>

            <div class="pd-20 font-16" v-if="recharge.type!='usdt'">
                转账备注码:
                <span class="color-pink">{{rand_number}}</span>
            </div>
            <div class="content-amount mt-10" v-else>
                <van-field placeholder="请填写您的钱包地址" type="text" v-model="usdt_address" />
            </div>
            <div class="pd-20 font-16">
                <div class="color-pink">温馨提示</div>
                <span>
                    识别二维码付款时请填写
                    <span class="color-pink">备注码</span> 再填写转账金额提交
                </span>
            </div>

            <van-button :loading="loading" @click="toRecharge" block class="content-botton" color="#F5A623" loading-type="spinner" type="primary">充值</van-button>

            <van-button @click="toBack" block class="content-botton" color="#F5A623" type="primary">返回</van-button>
        </div>
    </div>
</template>

<script>
import userHeader from '@/components/user-header'
import { Toast } from 'vant'
export default {
    components: {
        userHeader,
    },
    data() {
        return {
            amount: '',
            rand_number: null,
            loading: false,
            usdt_address: '',
            usdt_pay: null,
        }
    },
    computed: {
        recharge() {
            if (this.$route.query.type != undefined) {
                return this.$store.state.config.recharge_aisle[this.$route.query.type]
            }
            return ''
        },
    },
    watch: {
        amount(val) {
            if (this.recharge.type == 'usdt' && val > 0) {
                let money = val / this.$store.state.config.usdt_rate
                this.usdt_pay = money.toFixed(2)
            }
            if (val == '') {
                this.usdt_pay = null
            }
        },
    },
    methods: {
        backHome() {
            this.$router.push({ path: '/' })
        },
        toBack() {
            this.$router.back(-1)
        },
        toRecharge() {
            this.loading = true
            let params = {
                amount: this.amount,
                channel: this.recharge.type,
                usdt_address: this.usdt_address,
                code: this.rand_number,
            }
            this.$post('recharge/create', params, false).then((res) => {
                this.loading = false
                this.amount = null
            })
        },
        copy(info) {
            let index = info.indexOf('：') //获取“=”首次出现的位置
            let content = info.substring(index + 1)
            var tag = document.createElement('input')
            tag.setAttribute('id', 'cp_hgz_input')
            tag.value = content
            document.getElementsByTagName('body')[0].appendChild(tag)
            document.getElementById('cp_hgz_input').select()
            document.execCommand('copy')
            document.getElementById('cp_hgz_input').remove()
            Toast.success('复制成功')
        },
    },
    created() {
        let min = 1000
        let max = 9999
        this.rand_number = Math.floor(Math.random() * (max - min)) + min
    },
}
</script>

<style lang="less" scoped>
.content {
    padding: 15px;
    height: calc(100% - 6vh);
    position: fixed;
    overflow-y: scroll;
    &-qrcode {
        border-top: 2px solid @orange;
        padding: 30px;
        padding-bottom: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    &-intro {
        padding: 5px 0;
        text-align: center;
        font-size: 15px;
    }

    &-amount {
        margin-top: 40px;
    }
    &-botton {
        margin-top: 20px;
    }
}
.color-pink {
    color: #e57d92;
}
.bg-white {
    background: @white;
}
.recharge-info {
    width: 100%;
}
.icon-intro {
    vertical-align: middle;
}
.wrap-intro {
    display: block;
    width: 70vw;
    padding-left: 15vw;
    word-wrap: break-word;
}
.pd-3 {
    padding: 3px;
    font-size: 16px;
    color: @blue;
    font-weight: bolder;
}
.pd-20 {
    padding: 15px 0;
}
.mt-1 {
    margin-top: 1px;
}
.van-image {
    padding-bottom: 6px;
}
</style>