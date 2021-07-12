<template>
    <div>
        <userHeader title="今日盈利" />
        <div class="my-info">
            <span class="title">盈利金额(元)</span>
            <span class="balance font-roman">{{calProfit}}</span>
            <span class="formula">
                盈亏计算：
                <b class="color-red">中奖-投注+活动+返点</b>
            </span>
        </div>
        <van-button @click="showTimePick" block plain style="border:none;">
            <van-icon class="left" color="#c2c2c2" name="arrow-left" size="14" />
            <b>{{nowDate}}</b>
            <van-icon class="right" color="#c2c2c2" name="arrow" />
        </van-button>
        <van-grid :border="false" :column-num="3" class="mt-6">
            <van-grid-item class="has-border">
                <p class="font-18 color-red">{{bet_total}}</p>
                <p>投注金额</p>
            </van-grid-item>
            <van-grid-item class="has-border middle">
                <p class="font-18 color-red">{{bet_bonus}}</p>
                <p>中奖金额</p>
            </van-grid-item>
            <van-grid-item class="has-border">
                <p class="font-18 color-red">{{user_award}}</p>
                <p>活动金额</p>
            </van-grid-item>
            <van-grid-item>
                <p class="font-18 color-red">{{rebate}}</p>
                <p>返水金额</p>
            </van-grid-item>
            <van-grid-item class="middle">
                <p class="font-18 color-red">{{recharge}}</p>
                <p>充值金额</p>
            </van-grid-item>
            <van-grid-item>
                <p class="font-20 color-red">{{withdraw}}</p>
                <p>提现金额</p>
            </van-grid-item>
        </van-grid>
        <van-popup position="bottom" v-model="$store.state.config.show_pop">
            <van-datetime-picker :max-date="maxDate" :min-date="minDate" @cancel="cancelPicker" @confirm="getCurrentTime" cancel-button-text="重置" title="选择年月日" type="date" v-model="currentDate" />
        </van-popup>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    name: 'profit',
    components: {
        userHeader,
    },
    data() {
        return {
            nowDate: '',
            minDate: new Date(2020, 0, 1),
            maxDate: new Date(2021, 10, 1),
            currentDate: new Date(),
            bet_total: 0,
            bet_bonus: 0,
            user_award: 0,
            rebate: 0.0,
            recharge: 0,
            withdraw: 0,
            dateSearch: false,
        }
    },
    created() {
        this.getStats()
        this.getToday()
    },
    computed: {
        calProfit() {
            let profit = parseFloat(this.bet_bonus) - parseFloat(this.bet_total) + parseFloat(this.user_award) + parseFloat(this.rebate)
            return profit > 0 ? profit : 0.0
        },
    },
    methods: {
        getStats() {
            let params = {}
            if (this.dateSearch) {
                params.date_start = this.nowDate
            }
            this.$get('user/stats', params).then((res) => {
                this.bet_total = res.data.bet_total
                this.bet_bonus = res.data.bet_bonus
                this.user_award = res.data.user_award
                this.recharge = res.data.wallet_recharge
                this.withdraw = res.data.wallet_withdraw
            })
        },
        showTimePick() {
            this.$store.state.config.show_pop = true
        },
        getToday() {
            var date = new Date()
            let nowMonth = date.getMonth() + 1
            this.nowDate = date.getFullYear() + '-' + nowMonth + '-' + date.getDate()
            console.log(this.nowDate)
        },
        getCurrentTime(value) {
            let nowMonth = value.getMonth() + 1
            this.nowDate = value.getFullYear() + '-' + nowMonth + '-' + value.getDate()
            this.$store.state.config.show_pop = false
            this.dateSearch = true
            this.getStats()
        },
        cancelPicker() {
            this.$store.state.config.show_pop = false
            this.dateSearch = false
            this.getToday()
            this.getStats()
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
    span {
        display: block;
        text-align: center;
        padding-top: 20px;
    }
    .title {
        color: #d7bb99;
    }
    .balance {
        font-size: 32px;

        color: #d7bb99;
    }
    .formula {
        font-size: 14px;
    }
}
/deep/.van-button {
    position: relative;
    .left {
        position: absolute;
        left: 15%;
    }
    .right {
        position: absolute;
        right: 15%;
    }
}
.middle {
    border-left: 1px solid #f0f0f0;
    border-right: 1px solid #f0f0f0;
}
.has-border {
    border-bottom: 1px solid #f0f0f0;
}
</style>