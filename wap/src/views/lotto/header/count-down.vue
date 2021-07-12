<template>
    <div class="has-pop">
        <div class="title font-12">
            <template v-if="bet_current.id">
                <strong>{{bet_current.short_id}}</strong>
                <span>投注截止</span>
            </template>

            <template v-else>暂无最新一期</template>
        </div>

        <van-count-down :class=" color ==='red' ? 'count-down-content font-roman color-red' : 'count-down-content font-roman color-white'" :time="count_down" format="HH:mm:ss" v-if="lotto.stop === false">
            <template v-slot="timeData">
                <span class="date">{{ timeData.hours | pre_zero }}</span>
                <span>:</span>
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
                this.color = 'white'
            }
            if (oldVal < 0 && oldVal > -9000) {
                this.lotto.stop = true
            }
        },
    },
}
</script>

<style lang="less" scoped>
.has-pop::after {
    content: '';
    position: absolute;
    border-top: 10px solid transparent;
    border-right: 6px solid @black;
    border-bottom: 10px solid transparent;
    right: 0;
    top: 21px;
}
.title {
    color: #cce6d8;
}
.count-down {
    &-content {
        display: inline-block;
        padding: 0 14px;
        font-size: 16px !important;
        font-weight: bold;

        vertical-align: middle;

        color: @sub;

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
    color: white;
}
</style>