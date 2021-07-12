<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="tableColumns || columns" :data="items" :height="300 | table_height" border>
            <table-user :data="row.user" slot="user" slot-scope="{row}" v-if="row.user" />
            <span :class="amountClass(row.amount)" slot="amount" slot-scope="{row}">{{row.amount}}</span>
            <div slot="field" slot-scope="{row}">{{fields[row.field]}}</div>
            <span class="custom-tag" slot="created_at" slot-scope="{row}">{{$moment(row.created_at).format('MM-DD HH:mm:ss')}}</span>
        </Table>

        <lett-page :page="page" @on-change="onPage" />
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],

    props: {
        tableColumns: {
            type: Array,
            default() {
                return this.columns
            },
        },
    },

    data() {
        return {
            dataware: {
                index: {
                    api: '/member/wallet-log',
                },
            },

            fields: {
                bank: '银行',
                balance: '现金',
                fund: '余额宝',
            },

            columns: [
                {
                    title: '流水ID',
                    key: 'id',
                    align: 'left',
                    width: 140,
                },
                {
                    title: '用户',
                    slot: 'user',
                    width: 200,
                },
                {
                    title: '账户',
                    slot: 'field',
                },
                {
                    title: '描述',
                    key: 'title',
                    className: 'color-black',
                },
                {
                    title: '关联ID',
                    key: 'source_id',
                },
                {
                    title: '金额',
                    slot: 'amount',
                    align: 'right',
                    className: 'color-black',
                },
                {
                    title: '快照',
                    key: 'after',
                    align: 'right',
                },
                {
                    title: '时间',
                    slot: 'created_at',
                    align: 'right',
                    width: 140,
                },
            ],
        }
    },

    methods: {
        amountClass(amount) {
            if (parseFloat(amount) > 0) {
                return 'color-red'
            }
            return 'color-green'
        },
    },
}
</script>

<style lang="less" scoped>
.red-bag-wrap {
    padding: 3px 0;
}
.red-bag-log {
    margin: 3px 6px 3px 0;
    cursor: pointer;
}
</style>