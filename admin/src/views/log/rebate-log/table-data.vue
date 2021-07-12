<template>
    <div>
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="300 | table_height" border>
            <table-user :data="row.user" slot="user" slot-scope="{row}" v-if="row.user" />
            <table-user :data="row.child_info" slot="child" slot-scope="{row}" v-if="row.extend.child" />
            <span slot="orign_money" slot-scope="{row}">{{row.extend.balance}}</span>
            <span class="custom-tag" slot="created_at" slot-scope="{row}">{{$moment(row.created_at).format('MM-DD HH:mm:ss')}}</span>
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
                    api: '/user-award/rebate-log',
                },
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
                    title: '返利金额',
                    key: 'amount',
                    align: 'right',
                    className: 'color-black',
                    width: 150,
                },
                {
                    title: '下线',
                    slot: 'child',
                    width: 200,
                },
                {
                    title: '描述',
                    key: 'title',
                    className: 'color-black',
                },
                {
                    title: '初始金额',
                    slot: 'orign_money',
                    align: 'right',
                    className: 'color-black',
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
}
</script>
<style lang="less" scoped>
</style>