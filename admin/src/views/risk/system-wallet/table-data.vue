<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="300 | table_height" border>
            <table-user :data="row.user" slot="user" slot-scope="{row}" />
            <span :class="amountClass(row.amount)" slot="amount" slot-scope="{row}">{{row.amount}}</span>
            <div slot="field" slot-scope="{row}">{{fields[row.field].title}}</div>
            <span class="custom-tag" slot="created_at" slot-scope="{row}">{{$moment(row.created_at).format('MM-DD HH:mm:ss')}}</span>
        </Table>

        <lett-page :page="page" @on-change="getIndex" />
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],
    components: {},

    data() {
        return {
            dataware: {
                index: {
                    api: '/risk/system-wallet',
                },
            },
            fields: {
                bank: {
                    title: '银行',
                },
                balance: {
                    title: '现金',
                },
                fund: {
                    title: '余额宝',
                },
            },
            columns: [
                {
                    title: '流水ID',
                    key: 'id',
                    align: 'left',
                },
                {
                    title: '用户',
                    slot: 'user',
                    align: 'left',
                },

                {
                    title: '描述',
                    key: 'title',
                },
                {
                    title: '账户',
                    slot: 'field',
                    maxWidth: 80,
                },
                {
                    title: '金额',
                    slot: 'amount',
                    align: 'right',
                    sortable: true,
                    className: 'color-black',
                },
                {
                    title: '快照',
                    key: 'after',
                    align: 'right',
                    sortable: true,
                },

                {
                    title: '备注',
                    key: 'remark',
                    className: 'color-black',
                    minWidth: 80,
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
