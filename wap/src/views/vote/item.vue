<template>
    <div>
        <van-grid :column-num="3">
            <van-grid-item>
                <div>投注金额</div>
                <span class="money-amount">¥{{parseFloat(amount.bet).toFixed(2)}}</span>
            </van-grid-item>
            <!-- <div class="van-hairline--right"></div> -->
            <van-grid-item>
                <div>中奖金额</div>
                <span class="money-amount">¥{{parseFloat(amount.bonus).toFixed(2)}}</span>
            </van-grid-item>
            <van-grid-item>
                <div>盈利金额</div>
                <span class="money-amount">¥{{parseFloat(amount.profit).toFixed(2)}}</span>
            </van-grid-item>
        </van-grid>
        <van-list :error-text="errorText" :error.sync="error" :finished="finished" @load="getIndex" class="reset mb-14" v-if="items.length > 0" v-model="loading">
            <bet-log-items :items="items" :show-icon="false" ref="logItem" />
        </van-list>
        <van-popup :style="{ height: '80%' }" position="bottom" v-model="$store.state.config.show_pop">
            <picker @success="success" />
        </van-popup>
        <!-- <van-empty description="暂无数据" v-if="items.length == 0" /> -->
    </div>
</template>
<script>
import picker from './picker'
import BetLogItems from '@/views/wallet/bet-log/items'
export default {
    props: {
        field: {
            default: null,
            type: [String, Number],
        },
    },
    components: {
        BetLogItems,
        picker,
    },
    created() {
        this.getIndex()
    },
    data() {
        return {
            amount: {
                bet: 0,
                bonus: 0,
                profit: 0,
            },
            loading: false,
            finished: false,
            error: false,
            errorText: '请求失败，点击重新加载',
            page: {
                current: 0,
            },
            items: [],
            condition: {},
        }
    },
    methods: {
        onLoad() {
            this.page++
            this.getBetLoging()
        },
        getIndex() {
            var params = { field: this.field }

            params = Object.assign(params, this.condition)

            this.$getList('/wallet/bet-log', params).then((res) => {
                this.amount = res.data.amount
                if (res.data.page.total === 0) {
                    this.errorText = '暂无相关记录 请重新查询'
                }
            })
        },
        success(param) {
            this.$store.state.config.show_pop = false
            this.condition = param
            this.page.current = 0
            this.items = []
            this.getIndex()
        },
    },
}
</script>
<style lang="less" scoped>
.money-amount {
    color: #e4593e;
    line-height: 20px;
}
/deep/.van-grid-item__content {
    padding: 7px;
}
.reset {
    position: fixed;
    height: calc(100% - 6vh - 97px);
    overflow-y: scroll;
    width: 100%;
}
</style>