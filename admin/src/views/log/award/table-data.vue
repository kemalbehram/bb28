<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="300 | table_height" border>
            <table-user :data="row.user" slot="user" slot-scope="{row}" />
            <table-date :date="row.created_at" class="color-black" slot="created_at" slot-scope="{row}" />
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
                    api: '/user-award/index',
                },
            },
            columns: [
                {
                    title: '流水ID',
                    width: 120,
                    key: 'id',
                    align: 'left',
                },

                {
                    title: '领取用户',
                    slot: 'user',
                    width: 280,
                },

                {
                    title: '标题',
                    key: 'title',
                },

                // {
                //     title: '类型',
                //     key: 'type'
                // },

                {
                    title: '金额',
                    key: 'amount',
                    align: 'right',
                },

                {
                    title: '创建时间',
                    slot: 'created_at',
                    // width: 180,
                    align: 'right',
                },
            ],
        }
    },
}
</script>