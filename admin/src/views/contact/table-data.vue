<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" border>
            <div slot="user" slot-scope="{row}">
                <lett-avatar :src="row.user.avatar" />
                {{row.user.nickname}}
            </div>

            <Tooltip :content="row.created_at" placement="top" slot="createdAt" slot-scope="{row}">
                <Time :interval="1" :time="row.created_at" />
            </Tooltip>

            <template slot="status" slot-scope="{row}">
                <Tag v-if="row.status == 1">等待处理</Tag>
                <Tag v-if="row.status == 2">处理中</Tag>
                <Tag v-if="row.status == 3">处理结束</Tag>
            </template>

            <template slot="action" slot-scope="{row, index }">
                <Button @click="updateData(true,row)" class="mr-6" size="small" type="primary">详情</Button>
                <Button @click="deleteData(row)" size="small" type="error">删除</Button>
            </template>
        </Table>

        <lett-page :page="page" @on-change="getIndex" />

        <modal-update :config.sync="dataware.update" @success="updateData" action="update" title="留言处理" />
    </div>
</template>

<script>
import modalUpdate from './modal-form'
export default {
    components: {
        modalUpdate
    },

    data() {
        return {
            loading: false,

            dataware: {
                index: {
                    api: '/contact/index'
                },
                update: {
                    api: '/contact/update',
                    visible: false,
                    data: {},
                    fields: ['status', 'updated_at', 'created_at']
                },
                delete: {
                    api: '/contact/delete'
                }
            },
            columns: [
                {
                    title: '用户',
                    slot: 'user',
                    tooltip: true,
                    width: 200
                },
                {
                    title: '内容',
                    key: 'content',
                    tooltip: true
                },

                {
                    title: '状态',
                    key: 'status',
                    maxWidth: 160,
                    slot: 'status',
                    align: 'center'
                },
                {
                    title: '留言时间',
                    key: 'created_at',
                    maxWidth: 200,
                    slot: 'createdAt'
                },
                {
                    title: '操作',
                    slot: 'action',
                    width: 160,
                    align: 'center'
                }
            ],
            items: [],
            page: {},
            query: {}
        }
    },

    created() {
        this.getIndex(1)
    },

    methods: {
        getIndex(page, query) {
            if (query) this.query = query
            query = this.query
            const api = this.dataware.index.api
            return this.$dataware.index(api, this, page, query)
        },

        updateData(open, data, success = false) {
            const fields = this.dataware.update.fields
            return this.$dataware.update(this, open, data, success, fields)
        },

        deleteData(data) {
            const api = this.dataware.delete.api
            return this.$dataware.remove(api, this, data)
        }
    }
}
</script>