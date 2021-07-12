<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :border="true" :columns="columns" :data="items" :height="300 | table_height" :summary-method="handleSummary" border class="mb-10" show-summary>
            <span :class="profitClass(row.system_profit)" slot="system_profit" slot-scope="{row}">{{row.system_profit | currency}}</span>
        </Table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            page: {},
            items: [],
            query: {},
            date: [],
            columns: [
                {
                    title: '日期',
                    key: 'date',
                    align: 'center',
                    width: 120,
                    fixed: 'left',
                    sortable: true,
                },
                {
                    title: '平台总盈亏',
                    slot: 'system_profit',
                    key: 'system_profit',
                    align: 'right',
                    fixed: 'left',
                    width: 120,
                    sortable: true,
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
                            title: '代理转用户',
                            key: 'transfer_agent_to_user',
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
                        // {
                        //     title: '用户转代理',
                        //     key: 'transfer_user_to_agent',
                        //     align: 'right',
                        //     width: 100,
                        //     render: (h, params) => {
                        //         let key = params.column.key
                        //         return h(
                        //             'div',
                        //             {
                        //                 class: this.amountClass(params.row[key]),
                        //             },
                        //             params.row[key]
                        //         )
                        //     },
                        // },
                        // {
                        //     title: '利润-基本',
                        //     key: 'transfer_award_agent_base',
                        //     align: 'right',
                        //     width: 100,
                        //     render: (h, params) => {
                        //         let key = params.column.key
                        //         return h(
                        //             'div',
                        //             {
                        //                 class: this.amountClass(params.row[key]),
                        //             },
                        //             params.row[key]
                        //         )
                        //     },
                        // },
                        // {
                        //     title: '利润-下线',
                        //     key: 'transfer_award_agent_refer',
                        //     align: 'right',
                        //     width: 100,
                        //     render: (h, params) => {
                        //         let key = params.column.key
                        //         return h(
                        //             'div',
                        //             {
                        //                 class: this.amountClass(params.row[key]),
                        //             },
                        //             params.row[key]
                        //         )
                        //     },
                        // },
                        // {
                        //     title: '用户奖励',
                        //     key: 'transfer_award_user_total',
                        //     align: 'right',
                        //     width: 100,
                        //     render: (h, params) => {
                        //         let key = params.column.key
                        //         return h(
                        //             'div',
                        //             {
                        //                 class: this.amountClass(params.row[key]),
                        //             },
                        //             params.row[key]
                        //         )
                        //     },
                        // },
                        // {
                        //     title: '转账手续费',
                        //     key: 'transfer_fee',
                        //     align: 'right',
                        //     width: 100,
                        //     render: (h, params) => {
                        //         let key = params.column.key
                        //         return h(
                        //             'div',
                        //             {
                        //                 class: this.amountClass(params.row[key]),
                        //             },
                        //             params.row[key]
                        //         )
                        //     },
                        // },
                    ],
                },
            ],
        }
    },

    created() {
        this.getIndex([])
    },

    methods: {
        getIndex(date = []) {
            console.log(date)
            let query = {}
            if (date.length == 2) this.date = date
            if (this.date.length == 2) {
                query.date_start = this.date[0]
                query.date_end = this.date[1]
            }

            this.$get('/data-stats/table-data', query).then((res) => {
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
            let value = parseFloat(amount)
            if (value == 0) {
                return 'color-desc'
            } else if (value > 0) {
                return 'color-red'
            }
        },

        handleSummary({ columns, data }) {
            const sums = {}
            columns.forEach((column, index) => {
                const key = column.key
                if (index === 0) {
                    let value = '合计' + this.items.length + '天'
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
/deep/.ivu-table-border th,
/deep/.ivu-table-border td {
    border-right-width: 1px;
}

/deep/.ivu-table-cell {
    padding: 0 10px;
}

/deep/.ivu-table-with-summary .ivu-table-tbody td {
    height: 44px;
}
/deep/.ivu-table-summary .ivu-table-tbody td {
    height: 48px;
}
</style>