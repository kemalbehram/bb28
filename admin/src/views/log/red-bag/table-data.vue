<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="300 | table_height" border>
            <table-user :data="row.user" slot="user" slot-scope="{row}" v-if="row.user" />
            <table-date :date="row.created_at" slot="created_at" slot-scope="{row}" />
            <div class="red-bag-wrap" slot="logs" slot-scope="{row}">
                <span :key="item.id" @click="$store.dispatch('onMemberInfo', item.user_id)" class="custom-tag red-bag-log" v-for="item in row.logs">{{item.user.nickname}} / {{item.amount}}</span>
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
                    api: '/red-bag/index'
                }
            },
            columns: [
                {
                    title: '流水ID',
                    width: 120,
                    key: 'id',
                    align: 'left'
                },
                {
                    title: '发送人',
                    slot: 'user',
                    width: 180
                },
                {
                    title: '红包码',
                    key: 'code',
                    width: 160
                },
                {
                    title: '总金额',
                    key: 'total',
                    align: 'right',
                    width: 140
                },
                {
                    title: '已领取',
                    key: 'received',
                    align: 'right',
                    width: 140
                },
                {
                    title: '领取用户',
                    slot: 'logs'
                },
                {
                    title: '退回金额',
                    key: 'returned',
                    width: 140,
                    align: 'right'
                },
                {
                    title: '创建时间',
                    slot: 'created_at',
                    width: 180,
                    align: 'right'
                }
            ]
        }
    }
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