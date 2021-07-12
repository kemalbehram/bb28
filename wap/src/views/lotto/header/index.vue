<template>
    <div class="lotto-header-wrap">
        <div class="lotto-header max-width">
            <win-code :lotto="lotto" class="lotto-header-item" />

            <count-down :lotto="lotto" @click.native="lotto.$refs.betLog.visible = true" @finish="finishCountDown()" class="lotto-header-item flex-1" ref="lastCountDown" />
        </div>
    </div>
</template>

<script>
import winCode from './win-code'
import countDown from './count-down'
export default {
    components: {
        countDown,
        winCode,
    },
    props: {
        lotto: {
            default: function () {
                return this.$parent
            },
            type: Object,
        },
    },

    computed: {
        last() {
            return this.lotto.open_last.length > 0 ? this.lotto.open_last[0] : {}
        },
    },

    watch: {
        last(new_val, old_val) {
            if (new_val.open_code !== old_val.open_code && old_val.id && new_val.open_code != null) {
                // this.lotto.chatOpen(old_val.id)
            }
            if (new_val.open_code != null) {
                // this.lotto.stop_bet = false
                this.$store.state.lotto.stop_bet = false
            }
        },
    },

    methods: {
        finishCountDown() {
            this.$refs.lastCountDown.reset()
            if (this.last.win_extend === null) {
                this.lotto.getOpenLast()
            }
        },
    },
}
</script>

<style lang="less" scoped>
.lotto-header {
    position: fixed;
    width: 100%;
    z-index: 20;
    top: 46px;
    height: 56px;
    background: linear-gradient(to bottom, #7787a1, #474f5e);
    display: flex;
    &-item {
        height: 52px;
        width: 51%;
        text-align: center;
        padding-top: 6px;
        position: relative;
    }

    &-item:first-child::after {
        content: ' ';
        position: absolute;
        height: 56px;
        width: 1px;
        background: black;
        right: -1px;
        top: 14%;
        margin-top: -10px;
    }
    .stop {
        position: absolute;
        right: 20%;
        top: 62%;
        margin-top: -10px;
        color: white;
    }
    // .border-right {
    //     border-color: @line-dark;
    // }
}
</style>