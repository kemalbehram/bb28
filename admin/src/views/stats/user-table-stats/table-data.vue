<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getData($parent.$parent.date)" />

        <Table :columns="columns" :data="items" :height="300 | table_height" :summary-method="handleSummary" border class="mb-10" no-data-text="暂无相关数据 请尝试更换搜索条件" show-summary>
            <table-user :data="row.user" slot="user" slot-scope="{row}" v-if="row" />
            <span slot="requested_at" slot-scope="{row}">{{$moment(row.requested_at).format('MM-DD HH:mm')}}</span>
            <span :class="profitClass(row.system_profit)" slot="system_profit" slot-scope="{row}">{{row.system_profit | currency}}</span>
            <span slot="wallet_total" slot-scope="{row}">{{row.wallet_total | currency}}</span>
            <span :class="diffClass(row.wallet_diff)" slot="wallet_diff" slot-scope="{row}">{{row.wallet_diff | currency}}</span>
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
                    fixed: 'left',
                    width: 160,
                },
                {
                    title: '平台盈亏',
                    slot: 'system_profit',
                    key: 'system_profit',
                    align: 'right',
                    fixed: 'left',
                    width: 100,
                    sortable: true,
                },
                {
                    title: '基本信息',
                    align: 'center',
                    children: [
                        {
                            title: '最后活跃',
                            slot: 'requested_at',
                            key: 'requested_at',
                            align: 'center',
                            width: 100,
                            sortable: true,
                        },

                        {
                            title: '资金',
                            slot: 'wallet_total',
                            key: 'wallet_total',
                            align: 'right',
                            width: 100,
                            sortable: true,
                        },
                        // {
                        //     title: '差额',
                        //     slot: 'wallet_diff',
                        //     key: 'wallet_diff_float',
                        //     align: 'right',
                        //     width: 100,
                        //     sortable: true,
                        // },
                    ],
                },
                {
                    title: '游戏投注',
                    align: 'center',
                    children: [
                        {
                            title: '投注盈亏',
                            key: 'bet_profit',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '投注总额',
                            key: 'bet_total',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '返奖总额',
                            key: 'bet_bonus',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '有效投注',
                            key: 'bet_total_effective',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                    ],
                },
                {
                    title: '其它综合',
                    align: 'center',
                    children: [
                        {
                            title: '充值',
                            key: 'wallet_recharge',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '提现',
                            key: 'wallet_withdraw',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '余额宝利息',
                            key: 'wallet_fund_interest',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '任务奖励',
                            key: 'user_award_total',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                    ],
                },
                {
                    title: '转账统计',
                    align: 'center',
                    children: [
                        {
                            title: '转账转入',
                            key: 'transfer_in',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '转账转出',
                            key: 'transfer_out',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '利润-基本',
                            key: 'transfer_award_agent_base',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '利润-下线',
                            key: 'transfer_award_agent_refer',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '用户奖励',
                            key: 'transfer_award_user_total',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '转账手续费',
                            key: 'transfer_fee',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                    ],
                },
                {
                    title: '红包统计',
                    align: 'center',
                    children: [
                        {
                            title: '红包领取',
                            key: 'red_bag_log',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '红包发送',
                            key: 'red_bag_send',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '红包退回',
                            key: 'red_bag_returned',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '红包被领取',
                            key: 'red_bag_received',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(params.row[key]),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                    ],
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

            const api = '/data-stats/user-table-stats'
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

            if (parseFloat(amount) == 0) {
                return 'color-desc'
            }
        },

        diffClass(amount) {
            if (Math.abs(parseFloat(amount)) != 0) {
                return 'color-red'
            } else {
                return 'color-desc'
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

/deep/.ivu-table-border th,
/deep/.ivu-table-border td {
    border-right-width: 1px;
}
</style>