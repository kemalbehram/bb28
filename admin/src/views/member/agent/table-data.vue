<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="300 | table_height" @on-sort-change="onSort" border>
            <table-user :data="row" slot="nickname" slot-scope="{row}" />

            <template slot="wallet" slot-scope="{row}">{{row.wallet.total}}</template>
            <template slot="contact_qq" slot-scope="{row}">{{row.agent.contact_qq}}</template>
            <template slot="advance" slot-scope="{row}">{{row.agent.advance}}</template>
            <template slot="transfer_rate" slot-scope="{row}">{{row.agent.transfer_rate}}</template>
            <template slot="transfer_refer" slot-scope="{row}">{{row.agent.transfer_refer}}</template>

            <Tooltip :content="row.requested_at" placement="top" slot="requestedAt" slot-scope="{row}" v-if="row.requested_at">
                <Time :interval="1" :time="row.requested_at" />
            </Tooltip>

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
                    api: '/member/agent-index',
                },
            },
            columns: [
                {
                    title: 'ID',
                    width: 120,
                    key: 'id',
                    sortable: 'custom',
                },
                {
                    title: '昵称',
                    slot: 'nickname',
                    minWidth: 100,
                },
                {
                    title: '登陆手机',
                    key: 'mobile',
                },
                {
                    title: 'QQ',
                    slot: 'contact_qq',
                    align: 'right',
                },
                {
                    title: '总资产',
                    slot: 'wallet',
                    align: 'right',
                    key: 'wallet',
                    sortable: 'custom',
                },
                // {
                //     title: '铺货分',
                //     slot: 'advance',
                //     align: 'right'
                // },
                // {
                //     title: '基本提成',
                //     slot: 'transfer_rate',
                //     align: 'right'
                // },
                // {
                //     title: '直属提成',
                //     slot: 'transfer_refer',
                //     align: 'right'
                // },
                {
                    title: '最后访问',
                    key: 'requested_at',
                    slot: 'requestedAt',
                    align: 'center',
                },
                {
                    title: '操作',
                    slot: 'action',
                    width: 140,
                    align: 'center',
                },
            ],
        }
    },
}
</script>
