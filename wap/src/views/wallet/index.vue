<template>
    <div class="relative">
        <van-nav-bar @click-right="toWallet" class fixed right-text="账户明细" title="我的钱包">
            <!-- <van-image :src="user.avatar" height="40px" round slot="left" width="40px" /> -->
            <!-- <header-icon slot="left" /> -->
        </van-nav-bar>
        <div class="wallet-content">
            <div class="wallet-content--text">
                <van-icon name="gold-coin" />
                <span>账户总额</span>
            </div>
            <div class="wallet-content--amount">{{wallet}}</div>
        </div>
        <!-- <div class="wallet-content--image">
            <img :src="money_logo" alt />
        </div>-->
        <div class="wallet-content--label">
            <!-- <div>展开账户详情</div> -->
        </div>
        <div class="wallet-content--action van-hairline--bottom">
            <van-grid :border="false" :column-num="2">
                <van-grid-item :key="index" :to="item.route" class="action-icon" v-for="(item,index) in action_items">
                    <div>
                        <img :src="item.icon" alt class="action-icon--image" />
                    </div>
                    <span class="action-icon--text" v-html="item.text" />
                </van-grid-item>
            </van-grid>
        </div>

        <!-- <van-cell class="wallet-content--bank" v-if="bank_items && bank_items.name">
        
            <template #title>
                <van-image :src="bank_items.logo" height="50px" round width="50px" />
                <div class="bank-item">
                    <div>{{bank_items.name}}-{{bank_items.card_type}}</div>
                    <div>
                        <span class="bank-detail">{{bank_items.card ? bank_items.card.replace(/^(\d{4})\d+(\d{4})$/,"$1 **** **** $2"):''}}</span>
                        <span class="bank-detail">{{bank_items.real_name}}</span>
                    </div>
                </div>
            </template>
        </van-cell>-->

        <!-- <div class="wallet-content--label">
            <div @click="show=!show" v-if="!this.$store.state.user.info.bind_bank">+添加提现账户</div>
            <div @click="show=!show" v-else>修改提现账户</div>
        </div>-->

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
        <lett-tabbar />
    </div>
</template>
<script>
import headerIcon from '_c/header-icon'
import lettTabbar from '_c/lett-tabbar'
export default {
    components: {
        headerIcon,
        lettTabbar,
    },
    data() {
        return {
            show: false,
            form: {
                real_name: '',
                bank_card: '',
                bank_name: '',
                type_name: '',
            },
            money_logo: 'http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/config/money-logo1.png',
            action_items: [
                {
                    icon: 'http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/config/account-logo1.png',
                    route: '/wallet/recharge',
                    text: '充值上分',
                },
                {
                    icon: 'http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/config/account-logo2.png',
                    route: '/wallet/withdraw',
                    text: '提现下分',
                },
                // {
                //     icon: 'http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/config/account-logo3.png',
                //     route: '/user/account-change?field=rebate',
                //     text: '返水统计',
                // },
                // {
                //     icon: 'http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/config/account-logo4.png',
                //     route: '/user/account-change?field=bet',
                //     text: '游戏下注',
                // },
            ],
            bank_items: {
                name: '',
                card: '',
                logo: '',
                real_name: '',
            },
        }
    },
    created() {
        this.getCard()
    },
    methods: {
        toWallet() {
            this.$router.push({ name: 'walletReWiLog' })
        },
        toAdd() {
            this.$router.push({ name: 'userSettingBank' })
        },
        getCard() {
            this.loading = true
            this.error = ''
            this.$get('bank-card/index').then((res) => {
                if (res.code !== 200) {
                    this.error = res.message
                    return false
                }
                this.loading = false
                this.bank_items = res.data.items

                this.form.real_name = this.items != null ? this.items.real_name : ''
            })
        },
        onCreate() {
            if (this.checked !== true) return this.$notify('请先验证银行卡')
            if (this.form.real_name == '') return this.$notify('请填写真实姓名')
            this.$post('bank-card/create', this.form).then((res) => {
                if (res.code === 200) {
                    this.show = false
                    this.bank_items = res.data.items
                    this.$store.dispatch('getUserInfo')
                    // console.log(this.$store.state.user.bind_bank)
                }
            })
        },
        onUpate() {
            if (this.checked !== true) return this.$notify('请先验证银行卡')
            if (this.form.real_name == '') return this.$notify('请填写真实姓名')
            this.$post('bank-card/update', this.form).then((res) => {
                if (res.code === 200) {
                    this.show = false
                    this.bank_items = res.data.items
                }
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
    },
    computed: {
        wallet() {
            // console.log(this.$store.state.user.info)
            return this.$store.state.user.info.wallet ? this.$store.state.user.info.wallet.total : '0.00'
        },
    },
}
</script>
<style lang="less" scoped>
.wallet-content {
    padding-top: 80px;
    text-align: center;
    &--text {
        font-size: 16px;
        * {
            vertical-align: middle;
            padding: 0px 2px;
        }
    }
    &--amount {
        padding: 15px;
        font-size: 35px;
    }
    &--image {
        img {
            width: 100%;
        }
    }
    &--label {
        display: flex;
        justify-content: center;
        padding: 15px;
        div {
            color: #fff;
            display: inline-block;
            background: #acafba;
            // padding: 0.18667rem 0.4rem;
            border-radius: 30px;
            width: 100px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            font-size: 12px;
        }
    }
    &--action {
        .action-icon {
            &--image {
                width: 50px;
                height: 50px;
            }
        }
    }
    &--bank {
        .van-cell__title {
            display: flex;
        }
        .bank-item {
            padding-left: 10px;
        }
    }
}
.content {
    padding: 16px 16px 120px;
}
/deep/.van-nav-bar__title,
/deep/.van-nav-bar__text {
    color: @white;
}
</style>