<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="300 | table_height" @on-sort-change="onSort" border>
            <table-user :data="row" slot="nickname" slot-scope="{row}" />

            <template slot="wallet" slot-scope="{row}">{{row.wallet.total}}</template>

            <Tooltip :content="row.requested_at" placement="top" slot="requestedAt" slot-scope="{row}" v-if="row.requested_at">
                <Time :interval="1" :time="row.requested_at" />
            </Tooltip>

            <div @click="$emit('on-search', 'real_name', row.real_name)" class="pointer" slot="real_name" slot-scope="{row}">{{row.real_name}}</div>
            <div @click="$emit('on-search', 'requested_ip', row.requested_ip)" class="pointer" slot="requested_ip" slot-scope="{row}">{{row.requested_ip}}</div>

            <div slot="action" slot-scope="{row, index }">
                <Button @click="$store.dispatch('onMemberInfo', row.id)" size="small" type="primary">详情</Button>
            </div>
        </Table>
        <lett-page :page="page" @on-change="onPage" />
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],
    data() {
        return {
            dataware: {
                index: {
                    api: '/member/index'
                }
            },
            columns: [
                {
                    title: '用户ID',
                    width: 140,
                    key: 'id',
                    sortable: 'custom'
                },
                {
                    title: '昵称',
                    slot: 'nickname',
                    minWidth: 100
                },
                {
                    title: '姓名',
                    slot: 'real_name'
                },
                {
                    title: '登陆手机',
                    key: 'mobile'
                },
                {
                    title: '总资产',
                    slot: 'wallet',
                    key: 'wallet',
                    align: 'right',
                    sortable: 'custom'
                },
                // {
                //     title: '银行',
                //     slot: 'bank',
                //     align: 'right',
                //     minWidth: 80
                // },
                {
                    title: '最后访问',
                    key: 'requested_at',
                    align: 'center',
                    slot: 'requestedAt',
                    sortable: 'custom'
                },

                {
                    title: '最后IP',
                    slot: 'requested_ip',
                    align: 'right'
                },

                {
                    title: '操作',
                    slot: 'action',
                    width: 160,
                    align: 'center'
                }
            ]
        }
    }
}
</script>
