<template>
    <div @click="showOpenLog" class="win-code">
        <div class="win-code-content">
            <div class="title font-12 mb-2">
                <span>第</span>
                <b class="color-orange" v-if="last.short_id">{{last.id}}</b>
                <b class="color-orange" v-else>0000000</b>
                <span>期</span>
            </div>
            <van-count-down :time="10000" @finish="finishCountDown" ref="lastCountDown" v-show="false" />
            <kenoHe :data="last" :items="logs" ref="table"></kenoHe>
        </div>
    </div>
</template>
<script>
import kenoHe from './keno-he'
export default {
    props: {
        lotto: Object,
    },
    components: {
        kenoHe,
    },
    computed: {
        last() {
            return this.lotto.open_last.length > 0 ? this.lotto.open_last[0] : {}
        },

        logs() {
            return this.lotto.open_last
        },
        code() {
            return this.data.win_extend
        },
    },
    destroyed() {
        clearInterval(this.clearTimeSet)
    },
    watch: {
        last(new_val, old_val) {
            if (new_val.open_code !== old_val.open_code && old_val.id) {
                // console.log('xxxx')
                //  clearInterval(this.lotto.clearTimeSet);
                this.lotto.clearTime()
                // this.lotto.chatOpen(old_val.id)
                this.lotto.getUnited()
            }
            // console.log('32132')
            if (new_val.open_code != null) {
                this.$store.state.lotto.stop_bet = false
                this.lotto.stop_bet = false
                this.lotto.clearTime()
                // this.lotto.setTime()
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

        showOpenLog() {
            this.lotto.show_open_dom = true
            this.lotto.show_open_log = !this.lotto.show_open_log
            // console.log(this.lotto.show_open_log)
        },
    },
}
</script>
<style lang="less" scoped>
.win-code {
    position: fixed;
    width: 100%;
    z-index: 21;
    top: 82px;
    padding: 2px 0;
    background: white;
    max-width: 768px;
    .win-code-content {
        display: flex;
        justify-content: center;

        .title {
            margin-right: 10px;
        }
    }
}
</style>