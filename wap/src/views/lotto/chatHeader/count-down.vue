<template>
    <div class="has-pop">
        <div class="title font-12">
            <template v-if="bet_current.id">
                <span>第</span>
                <strong class="color-orange">
                    <van-notice-bar :text="bet_current.id" background="#FFFFFF" class="issue" scrollable speed="15" />
                </strong>
                <span>期开奖</span>
            </template>

            <template v-else>暂无最新一期</template>
        </div>

        <van-count-down :class=" color ==='red' ? 'count-down-content font-roman color-red' : 'count-down-content font-roman'" :time="count_down" format="mm:ss" v-if="lotto.stop === false">
            <template v-slot="timeData">
                <span class="date">{{ timeData.minutes | pre_zero }}</span>
                <span>:</span>
                <span class="date">{{ timeData.seconds | pre_zero }}</span>
            </template>
        </van-count-down>

        <span class="stop" v-else>封盘中</span>
    </div>
</template>

<script>
export default {
    props: {
        lotto: Object,
    },
    data() {
        return {
            color: 'white',
        }
    },
    methods: {
        // finishCountDown () {
        //   console.log(111);
        // }
    },
    computed: {
        count_down() {
            if (this.bet_current.id == null) return 0
            var past = this.lotto.newest_past
            let temp = JSON.parse(JSON.stringify(past))
            return (this.bet_current.bet_count_down - temp + 1) * 1000
        },

        bet_current() {
            return this.lotto.bet_current
        },
    },
    watch: {
        count_down(oldVal, newVal) {
            if (oldVal <= 11000) {
                this.color = 'red'
            } else {
                if (oldVal == 12000) {
                    // this.lotto.addInfo(5)
                    this.lotto.clearTime()
                }
                this.color = 'white'
            }
            if (oldVal < 0 && oldVal > -9000) {
                this.lotto.stop = true
            }
        },
        // bet_current(newVal, oldVla) {
        //     console.log('old---', oldVla.id)
        //     console.log('new---', newVal.id)
        // },
    },
}
</script>

<style lang="less" scoped>
// .has-pop::after {
//     content: '';
//     position: absolute;
//     border-top: 10px solid transparent;
//     border-right: 6px solid @black;
//     border-bottom: 10px solid transparent;
//     right: 0;
//     top: 21px;
// }
.title {
    color: @black;
    display: flex;
    align-items: center;
}
.count-down {
    &-content {
        display: inline-block;
        padding: 1px 4px;
        font-size: 12px !important;
        font-weight: bold;

        vertical-align: middle;
        border-radius: 5px;
        // color: @sub;
        color: @black;
        // background: #2f3242;
        span {
            vertical-align: middle;
            margin-right: 2px;
        }

        span:last-child {
            margin-right: 0;
        }

        .date {
            // border: 2px solid @yellow;
            text-align: center;
            font-size: 16px;
        }
    }
}
.stop {
    color: red;

    padding: 1px 4px;
    // margin-top: 10px;
    border-radius: 5px;
    // color: @sub;
    color: @black;
    // background: #2f3242;
}
.issue {
    height: 23px;
    width: 55px;
    padding: 0 2px;
}
</style>