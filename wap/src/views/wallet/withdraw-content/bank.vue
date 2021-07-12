<template>
    <div class="bank">
        <van-field class="mt-10" placeholder="请输入提现的金额" v-model="form.amount" />
        <van-field class="mt-10" placeholder="请输入您开户姓名" v-model="form.username">
            <template #left-icon>
                <van-icon color="#FF6A00" name="manager-o" size="16" />
            </template>
        </van-field>
        <van-field class="mt-10" placeholder="请输入您的银行卡号" v-model="form.bank_card">
            <template #button>
                <van-button :loading="loading" @click="verifyCard" color="#FF6A00" loading-type="spinner" size="small" type="primary">验证银行卡</van-button>
            </template>
            <template #left-icon>
                <van-icon class="pt-5" color="#FF6A00" name="debit-pay" size="16" />
            </template>
        </van-field>
        <van-field class="mt-10" placeholder="验证银行" readonly v-model="form.bank_name">
            <template #left-icon>
                <van-icon color="#FF6A00" name="certificate" size="16" />
            </template>
        </van-field>
        <van-field class="mt-10" placeholder="请输入您的开户地址" v-model="form.address">
            <template #left-icon>
                <van-icon color="#FF6A00" name="location-o" size="16" />
            </template>
        </van-field>
        <van-field class="mt-10" placeholder="验证资金密码" type="password" v-model="form.safe_word">
            <template #left-icon>
                <van-icon color="#FF6A00" name="browsing-history-o" size="16" />
            </template>
        </van-field>
    </div>
</template>

<script>
export default {
    props: {
        withdraw: {
            type: Object,
            default: {},
        },
    },
    data() {
        return {
            form: {
                bank_name: '',
            },
            loading: false,
        }
    },
    created() {
        if (this.withdraw.hasOwnProperty('bank')) {
            this.form = this.withdraw['bank'].extend
            this.qrcode = this.withdraw['bank'].qrcode
        }
    },
    methods: {
        verifyCard() {
            this.loading = true
            this.$post('bank-card/check', this.form, false).then((res) => {
                if (res.code !== 200) return false
                this.form.bank_name = res.data.bank_name
                this.loading = false
            })
        },
    },
}
</script>

<style lang="less" scoped>
</style>