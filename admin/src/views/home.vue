<template>
    <div class="relative">
        <!-- <h3 class="relative">欢迎使用管理后台系统</h3> -->
        <Row :gutter="30">
            <Col class="text-center" span="18">
                <Row :gutter="30">
                    <Col span="6">
                        <Card :padding="20">
                            <h2 slot="title">{{recharge_amount}}/{{withdraw_amount}}</h2>
                            <p>今日总上分/总下分</p>
                        </Card>
                    </Col>
                    <Col span="6">
                        <Card :padding="20">
                            <h2 slot="title">{{turnover_amount}}</h2>
                            <p>今日总流水</p>
                        </Card>
                    </Col>
                    <Col span="6">
                        <Card :padding="20">
                            <h2 slot="title">{{profit_amount}}</h2>
                            <p>下注总盈亏</p>
                        </Card>
                    </Col>
                    <Col span="6">
                        <Card :padding="20">
                            <h2 slot="title">{{user_amount}}/{{user_balance}}</h2>
                            <p>玩家人数/总积分</p>
                        </Card>
                    </Col>
                </Row>
                <home-bet-log :lotto_items="lotto_items" />
            </Col>
            <Col span="6">
                <div class="qrcode">
                    <div class="qrcode-botton">
                        <div>
                            <Button @click="choose_button='wechat'" type="warning">微信登录二维码</Button>
                        </div>
                        <div>
                            <Button @click="choose_button='download'" type="warning">APP下载二维码</Button>
                        </div>
                    </div>
                    <div class="qrcode-image">
                        <p v-if="choose_button=='wechat'">微信登录二维码</p>
                        <p v-else>APP下载二维码</p>
                        <img :src="choose_button=='wechat'?qrcode.wechat_qrcode:qrcode.download_qrcode" alt width="120" />
                    </div>
                </div>
                <home-audit />
            </Col>
        </Row>
    </div>
</template>

<script>
import homeBetLog from './home-page/bet-log'
import homeAudit from './home-page/wallet-audit'
// import homeIssue from './home-page/issue-info'
export default {
    components: {
        homeBetLog,
        homeAudit,
        // homeIssue,
    },
    data() {
        return {
            recharge_amount: 0,
            withdraw_amount: 0,
            turnover_amount: 0,
            profit_amount: 0,
            user_amount: 0,
            user_balance: 0,
            form: {
                lotto_name: 'bit28',
            },
            lotto: {},
            next_lotto: {},
            qrcode: {
                wechat_qrcode: '',
                download_qrcode: '',
            },
            choose_button: 'wechat',
        }
    },
    created() {
        this.getStatis()
    },
    computed: {
        lotto_items() {
            let items = []
            let values = Object.values(this.$store.state.config.lotto_items)

            values.forEach((item, index) => {
                items.push({ text: item.title, value: item.name, web_site: item.web_site })
            })
            return items
        },
    },
    methods: {
        getStatis() {
            this.$get('/home/index', this.form).then((res) => {
                if (res.code == 200) {
                    this.qrcode = res.data.qrcode
                    this.recharge_amount = res.data.statis.today_recharge
                    this.withdraw_amount = res.data.statis.today_withdraw
                    this.turnover_amount = res.data.statis.today_turnover
                    this.profit_amount = res.data.statis.today_profit
                    this.user_amount = res.data.statis.user_amount
                    this.user_balance = res.data.statis.user_wallet_amount
                    this.lotto = res.data.lotto.items[0]
                    this.next_lotto = res.data.lotto.items[1]
                }
            })
        },
        // sleep(time) {
        //     return new Promise((resolve) => setTimeout(resolve, time))
        // },
        chooseLotto(value) {
            this.form.lotto_name = value
        },
    },
}
</script>

<style lang="less" scoped>
.relative {
    padding: 0 20px;
    .text-center {
        text-align: center;
    }
    .qrcode {
        display: flex;
        height: 180px;
        background: @white;
        padding: 20px;
        border-radius: 5px;
        &-botton {
            line-height: 60px;
            padding-left: 20px;
        }
        &-image {
            text-align: center;
            width: 100%;
            img {
                width: 120px;
            }
        }
    }
}
</style>