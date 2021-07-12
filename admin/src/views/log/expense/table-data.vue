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
                    api: '/data-stats/expense-log',
                },
            },
            columns: [
                {
                    title: '流水ID',
                    key: 'id',
                    align: 'left',
                    width: 100,
                },
                {
                    title: '用户id',
                    slot: 'user',
                    width: 150,
                },
                {
                    title: '福利金额',
                    key: 'amount',
                    align: 'right',
                    className: 'color-black',
                    width: 100,
                },

                {
                    title: '备注',
                    key: 'remarks',
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