<template>
    <div class="wrap pb-64">
        <userHeader title="绑定银行卡" />
        <div class="bank-info" v-if="this.$store.state.user.info.bind_bank">
            <span class="bank-name">{{items.name}}</span>
            <span class="card-type">{{items.card_type}}</span>
            <span class="card">{{items.card ? items.card.replace(/^(\d{4})\d+(\d{4})$/,"$1 **** **** $2"):''}}</span>
        </div>
        <van-empty description="暂未绑定银行卡" v-else />
        <van-button @click="showSheet" block class="showSheet">{{this.$store.state.user.info.bind_bank ? '修改银行卡':'添加银行卡'}}</van-button>

        <van-action-sheet :title="this.$store.state.user.info.bind_bank ? '修改银行卡':'添加银行卡'" v-model="show">
            <div class="content">
                <van-field label="真实姓名" placeholder="请输入真实姓名" v-model="form.real_name" />
                <van-field label="卡号" placeholder="请输入卡号" v-model="form.bank_card" />
                <van-field disabled label="银行名称" placeholder="请验证银行" v-model="form.bank_name">
                    <van-button @click="onCheck" round size="small" slot="button" type="primary">验证</van-button>
                </van-field>
                <van-button @click="onUpate" block class="add-bank" type="primary" v-if="this.$store.state.user.info.bind_bank">更改</van-button>
                <van-button @click="onCreate" block class="add-bank" type="primary" v-else>添加</van-button>
            </div>
        </van-action-sheet>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    name: 'bank',
    data() {
        return {
            show: false,
            input: '',
            form: {
                real_name: '',
                bank_card: '',
                bank_name: '',
                type_name: '',
            },
            loading: true,
            checked: false,

            items: {},
        }
    },
    components: {
        userHeader,
    },
    created() {
        this.getIndex()

        // console.log(this.items)
    },
    methods: {
        showSheet() {
            this.show = true
        },
        getIndex() {
            this.loading = true
            this.error = ''
            this.$get('bank-card/index').then((res) => {
                if (res.code !== 200) {
                    this.error = res.message
                    return false
                }
                this.loading = false
                this.items = res.data.items
                // console.log(this.items)
                // this.total = res.data.items.length

                this.form.real_name = this.items != null ? this.items.real_name : ''
            })
        },
        onCheck() {
            this.$post('bank-card/check', this.form).then((res) => {
                if (res.code !== 200) return false
                this.form.bank_name = res.data.bank_name
                this.form.type_name = res.data.type_name
                this.checked = true
            })
        },

        onCreate() {
            if (this.checked !== true) return this.$notify('请先验证银行卡')
            if (this.form.real_name == '') return this.$notify('请填写真实姓名')
            this.$post('bank-card/create', this.form).then((res) => {
                if (res.code === 200) {
                    this.show = false
                    this.getIndex()
                    this.$store.dispatch('getUserInfo')
                    console.log(this.$store.state.user.bind_bank)
                }
            })
        },
        onUpate() {
            if (this.checked !== true) return this.$notify('请先验证银行卡')
            if (this.form.real_name == '') return this.$notify('请填写真实姓名')
            this.$post('bank-card/update', this.form).then((res) => {
                if (res.code === 200) {
                    this.show = false
                    this.getIndex()
                }
            })
        },
    },
    computed: {},
}
</script>
<style lang="less" scoped>
.content {
    padding: 16px 16px 120px;
}
.add-bank {
    width: 60%;
    margin: 20px auto;
}
.bank-info {
    height: 120px;
    background: linear-gradient(to bottom, #d2b68d, #baa37d);
    width: 92%;
    margin: 10px auto;
    border-radius: 10px;
    position: relative;
    .bank-name {
        position: absolute;
        top: 12%;
        left: 5%;
        color: white;
        font-size: 16px;
    }
    .card-type {
        position: absolute;
        top: 30%;
        left: 5%;
        color: white;
        font-size: 12px;
        font-weight: 300;
    }
    .card {
        position: absolute;
        top: 66%;
        left: 30%;
        color: white;
        font-size: 24px;
    }
}
.showSheet {
    width: 92%;
    margin: 0 auto;
    border-radius: 10px;
    background: #ff5742;
    color: white;
}
</style>