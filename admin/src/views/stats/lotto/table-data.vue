<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :border="true" :columns="columns" :data="items" :height="300 | table_height" :summary-method="handleSummary" border class="mb-10" show-summary>
            <template slot="balance" slot-scope="{row}">{{row.wallet.balance}}</template>

            <div slot="action" slot-scope="{row, index }">
                <Button @click="onUpdate(row)" size="small" type="primary">刷新</Button>
            </div>
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
        }
    },

    computed: {
        columns() {
            let header = [
                {
                    title: '日期',
                    key: 'date',
                    align: 'center',
                    width: 120,
                    fixed: 'left',
                    sortable: true,
                },
            ]
            let lottos = Object.values(this.$store.state.config.lotto_items)
            lottos.forEach((element) => {
                header.push({
                    title: element.title,
                    align: 'center',
                    children: [
                        {
                            title: '投注',
                            key: element.name + '_total',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(
                                            params.row[key]
                                        ),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '返奖',
                            key: element.name + '_bonus',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(
                                            params.row[key]
                                        ),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '盈亏',
                            key: element.name + '_profit',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.profitClass(
                                            params.row[key]
                                        ),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                        {
                            title: '有效流水',
                            key: element.name + '_effective',
                            align: 'right',
                            width: 100,
                            render: (h, params) => {
                                let key = params.column.key
                                return h(
                                    'div',
                                    {
                                        class: this.amountClass(
                                            params.row[key]
                                        ),
                                    },
                                    params.row[key]
                                )
                            },
                        },
                    ],
                })
            })

            return header
        },
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

            this.$get('/lotto-stats/index', query).then((res) => {
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
