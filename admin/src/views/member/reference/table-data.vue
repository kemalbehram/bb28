<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="300 | table_height" border>
            <table-user :data="row.user" slot="user" slot-scope="{row}" />

            <div class="display-flex" slot="superiors" slot-scope="{row}">
                <table-user :avatar="false" :data="item" :key="item.id" class="mr-20" v-for="item in row.superiors" />
            </div>

            <table-date :date="row.created_at" class="color-black" slot="created_at" slot-scope="{row}" />

            <div slot="action" slot-scope="{row, index }">
                <Button @click="$store.dispatch('onMemberInfo', row.user_id)" size="small" type="primary">详情</Button>
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
                    api: '/member/reference'
                }
            },
            columns: [
                {
                    title: '昵称',
                    slot: 'user',
                    width: 200
                },
                {
                    title: '上级用户',
                    slot: 'superiors'
                },

                {
                    title: '创建时间',
                    key: 'created_at',
                    slot: 'created_at',
                    align: 'center',
                    width: 140
                },
                {
                    title: '操作',
                    slot: 'action',
                    width: 140,
                    align: 'center'
                }
            ]
        }
    }
}
</script>