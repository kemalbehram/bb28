<template>
    <div class="alipay">
        <div class="content">
            <div class="recharge-info bg-white content-qrcode">
                <van-image :src="qrcode" fit="contain" width="50%" />
                <div class="pd-3 pt-5">
                    <span class="font-16">{{user.info.nickname}}</span>
                </div>
            </div>
            <div class="mt-1 bg-white content-intro">
                <van-icon @oversize="onOversize" class="icon-intro" color="#e57d92" name="qr" size="17" />
                <span class="icon-intro color-pink">
                    USDT泰达币收币二维码
                    <span class="color-red">(USDT费率: 1:{{$store.state.config.usdt_rate}})</span>
                </span>
            </div>
            <div class="bg-white mt-5 content-intro" v-if="usdt_amount">
                将收到
                <span>
                    <b class="color-red">{{usdt_amount}}</b> 个USDT
                </span>
            </div>
            <van-uploader :after-read="uploadQrcode" :max-size="500 * 1024" class="width-100 mt-10" v-if="form.qrcode==undefined">
                <van-button :loading="loading_image" block color="#26A3FF" loading-type="spinner" size="small">上传收款码</van-button>
            </van-uploader>
            <van-field class="mt-10" placeholder="请输入提现的金额" v-model="form.amount" />
            <van-field class="mt-10" placeholder="请输入USDT提币地址" v-model="form.address">
                <template #left-icon>
                    <van-icon color="#FF6A00" name="debit-pay" size="16" />
                </template>
            </van-field>
            <van-field class="mt-10" placeholder="验证资金密码" type="password" v-model="form.safe_word">
                <template #left-icon>
                    <van-icon color="#FF6A00" name="browsing-history-o" size="16" />
                </template>
            </van-field>
        </div>
    </div>
</template>

<script>
import qrcode from '@/assets/images/qrcode-palceholder.png'
import Toast from 'vant'
import lrz from 'lrz'
export default {
    data() {
        return {
            form: {},
            qrcode,
            loading_image: false,
            usdt_amount: null,
        }
    },
    computed: {
        user() {
            return this.$store.state.user
        },
    },
    props: {
        withdraw: {
            type: Object,
            default: {},
        },
    },
    created() {
        if (this.withdraw.hasOwnProperty('usdt')) {
            this.form = this.withdraw['usdt'].extend
            this.qrcode = this.withdraw['usdt'].qrcode
        }
    },
    watch: {
        'form.amount'(val) {
            if (val > 0) {
                let money = val / this.$store.state.config.usdt_rate
                this.usdt_amount = money.toFixed(2)
            }
            if (val == '') {
                this.usdt_amount = null
            }
        },
    },
    methods: {
        uploadQrcode(file) {
            var that = this
            lrz(file.content, { width: 1000, height: 1000, quality: 0.9 })
                .then(function (res) {
                    let params = { file: res.base64, type: 'base64', path: '/yl28/qrcode' }
                    that.loading_image = true
                    that.$post('/image/create', params, false, false).then((res) => {
                        that.loading_image = false
                        if (res.code !== 200) return that.$notify(res.message)
                        that.qrcode = res.data.url
                        that.form.qrcode = res.data.url
                    })
                })
                .catch(function (error) {
                    return that.$notify('图片转换失败 请重试')
                })
        },
        onOversize() {
            Toast('文件大小不能超过 500kb')
        },
    },
}
</script>

<style lang="less" scoped>
.content {
    // padding: 15px;
    .bg-white {
        background: @white;
    }
    &-qrcode {
        border-top: 2px solid @orange;
        padding: 30px;
        padding-bottom: 0px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    &-intro {
        padding: 5px 0;
        text-align: center;
        font-size: 15px;

        .icon-intro {
            vertical-align: middle;
        }
    }
}
.color-pink {
    color: #e57d92;
}
/deep/.van-uploader__input-wrapper {
    width: 100%;
}
</style>