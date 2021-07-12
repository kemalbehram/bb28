<template>
    <div @click="showOpenLog" class="relative">
        <div class="title font-12 mb-2">
            <b v-if="last.short_id">{{last.short_id}}</b>
            <b v-else>0000000</b>
            <span>期</span>
            <span>开奖号码</span>
            <van-icon name="arrow-down" />
        </div>
        <van-count-down :time="10000" @finish="finishCountDown" ref="lastCountDown" v-show="false" />
        <kenoHe :data="last" :items="logs" ref="table"></kenoHe>
    </div>
</template>


<script>
import kenoHe from './keno-he'

export default {
    components: {
        kenoHe,
    },

    props: {
        lotto: Object,
    },

    computed: {
        last() {
            return this.lotto.open_last.length > 0 ? this.lotto.open_last[0] : {}
        },

        logs() {
            return this.lotto.open_last
        },
    },

    watch: {
        last(new_val, old_val) {
            if (new_val.open_code !== old_val.open_code && old_val.id) {
                this.lotto.getUnited()
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
        },
    },
}
</script>

<style lang="less" scoped>
.relative {
    cursor: pointer;
}
.title {
    color: #cce6d8;
}
/deep/.win-code-content {
    span {
        margin-right: 4px;
    }

    span:last-child {
        margin: 0;
    }
}
</style>