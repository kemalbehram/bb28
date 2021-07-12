<template>
    <van-popup :overlay="false" :style="lotto.chat_open_log ? 'position:absolute;margin-top:25px;' :'position:absolute;'" @closed="lotto.show_open_dom = false" class="lotto-win-pop" get-container="#lotto-win-table" position="top" v-model="lotto.show_open_log">
        <keno :lotto="lotto"></keno>
        <div class="more">
            <van-button @click="showTrend" color="#fff" size="small" type="info">查看更多开奖结果</van-button>
        </div>
    </van-popup>
</template>

<script>
import keno from './keno'

export default {
    components: {
        keno,
    },

    props: {
        lotto: {
            default: function () {
                return this.$parent
            },
            type: Object,
        },
    },

    data() {
        return {
            loading: false,
            page: {
                current: 1,
            },
            items: [],
            query: {
                source: 'all',
                page: 1,
            },
        }
    },

    methods: {
        refreshIndex(loading = false) {
            if (this.page.current === 1) this.getIndex(1, false)
        },

        getIndex(page = 1, loading = true) {
            this.query.page = page
            if (loading) this.loading = true
            let api = '/lotto/' + this.$route.params.name + '/open-log'
            this.$get(api, this.query).then((res) => {
                if (res.code !== 200) {
                    return false
                }
                this.loading = false
                this.items = res.data.items
                this.page = res.data.page
            })
        },
        showTrend() {
            this.$router.replace({
                name: 'lottoTrend',
                params: {
                    name: this.$route.params.name,
                },
            })
        },
        amountClass(amount) {
            if (parseFloat(amount) > 0) return 'color-red'
        },

        onBetting(id) {
            this.lotto.bet_current = this.lotto.bet_newest[id]
            this.lotto.current_tab = this.$store.state.user.option.bet_model
        },
    },
}
</script>
<style lang="less" scoped>
.more {
    text-align: center;
    background: white;
    border-top: 1px solid rgb(226, 226, 226);
    height: 35px;
    /deep/.van-button {
        width: 80%;
        display: inline-block;
        color: #ff6a00 !important;
        line-height: 35px;
    }
}
</style>