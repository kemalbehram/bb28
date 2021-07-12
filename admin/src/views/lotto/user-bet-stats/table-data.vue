<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getData($parent.$parent.date)" />

        <Table :columns="columns" :data="items" :height="300 | table_height" :summary-method="handleSummary" border class="mb-10" no-data-text="暂无相关数据 请尝试更换搜索条件" show-summary>
            <table-user :data="row" slot="user" slot-scope="{row}" v-if="row" />

            <span :class="amountClass(row.wallet_total)" slot="wallet_total" slot-scope="{row}">{{row.wallet_total | currency}}</span>
            <span :class="amountClass(row.transfer_in)" slot="transfer_in" slot-scope="{row}">{{row.transfer_in | currency}}</span>
            <span :class="amountClass(row.transfer_out)" slot="transfer_out" slot-scope="{row}">{{row.transfer_out | currency}}</span>
            <span :class="amountClass(row.bet_total)" slot="bet_total" slot-scope="{row}">{{row.bet_total | currency}}</span>
            <span :class="amountClass(row.bet_bonus)" slot="bet_bonus" slot-scope="{row}">{{row.bet_bonus | currency}}</span>
            <span :class="profitClass(row.bet_profit)" slot="bet_profit" slot-scope="{row}">{{row.bet_profit | currency}}</span>
        </Table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            items: [],
            columns: [
                {
                    title: '用户',
                    slot: 'user',
                    align: 'left',
                },
                {
                    title: '资金总额',
                    key: 'wallet_total',
                    slot: 'wallet_total',
                    align: 'right',
                    sortable: true,
                },
                {
                    title: '充值总额',
                    key: 'transfer_in',
                    slot: 'transfer_in',
                    align: 'right',
                    sortable: true,
                },
                {
                    title: '提现总额',
                    key: 'transfer_out',
                    slot: 'transfer_out',
                    align: 'right',
                    sortable: true,
                },
                {
                    title: '下注总额',
                    key: 'bet_total',
                    slot: 'bet_total',
                    align: 'right',
                    sortable: true,
                },
                {
                    title: '返奖总额',
                    key: 'bet_bonus',
                    slot: 'bet_bonus',
                    align: 'right',
                    sortable: true,
                },
                {
                    title: '投注盈亏',
                    key: 'bet_profit',
                    slot: 'bet_profit',
                    align: 'right',
                    sortable: true,
                },
            ],
        }
    },

    created() {
        this.getData()
    },

    methods: {
        getData(params) {
            this.loading = true

            const api = '/lotto/bet-log/user-bet-stats'
            this.$get(api, params).then((res) => {
                if (res.code !== 200) return false
                this.loading = false
                this.items = res.data.items
            })
        },

        amountClass(amount) {
            if (parseFloat(amount) == 0) {
                return 'color-desc'
            }
        },
        profitClass(amount) {
            if (parseFloat(amount) > 0) {
                return 'color-red'
            }
        },

        handleSummary({ columns, data }) {
            const sums = {}
            columns.forEach((column, index) => {
                const key = column.key
                if (index === 0) {
                    let value = '合计' + this.items.length + '人'
                    sums[key] = { key, value }
                    return
                }
                const values = data.map((item) => Number(item[key]))
                if (!values.every((value) => isNaN(value))) {
                    const v = values.reduce((prev, curr) => {
                        const value = Number(curr)
                        if (!isNaN(value)) {
                            return prev + curr
                        } else {
                            return prev
                        }
                    }, 0)

                    sums[key] = {
                        key,
                        value: this.$options.filters.currency(v),
                    }
                } else {
                    sums[key] = {
                        key,
                        value: '0.000',
                    }
                }
            })

            return sums
        },
    },
}
</script>

<style lang="less" scoped>
.bet-status {
    background: @white;
    margin-left: 6px !important;
    &.status-1 {
        border-color: @green;
        color: @green;
    }

    &.status-3 {
        border-color: @red;
        color: @red;
    }
}
</style>