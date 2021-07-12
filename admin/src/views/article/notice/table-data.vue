<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="300 | table_height" border>
            <Tooltip :content="row.updated_at" placement="top" slot="updatedTime" slot-scope="{row}">
                <Time :interval="1" :time="row.updated_at" />
            </Tooltip>

            <template slot="status" slot-scope="{row}">
                <Tag v-if="row.status == true">正常</Tag>
                <Tag color="error" v-if="row.status == false">隐藏</Tag>
            </template>

            <template slot="action" slot-scope="{row, index }">
                <Button @click="updateData(true,row)" class="mr-6" size="small" type="primary">编辑</Button>
                <Button @click="deleteData(row)" size="small" type="error">删除</Button>
            </template>
        </Table>

        <lett-page :page="page" @on-change="getIndex" />

        <modal-create :config.sync="dataware.create" @success="createData" action="create" title="创建公告" />
        <modal-update :config.sync="dataware.update" @success="updateData" action="update" title="修改公告" />
    </div>
</template>

<script>
import modalUpdate from './modal-form'
import modalCreate from './modal-form'
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],
    components: {
        modalUpdate,
        modalCreate,
    },

    data() {
        return {
            dataware: {
                index: {
                    api: '/article/1001/index',
                },
                create: {
                    api: '/article/1001/create',
                    visible: false,
                },
                update: {
                    api: '/article/1001/update',
                    visible: false,
                    data: { name: '' },
                    fields: ['title', 'status', 'category', 'content', 'excerpt', 'thumb', 'cat_id', 'updated_at'],
                },
                delete: {
                    api: '/article/1001/delete',
                },
            },
            columns: [
                {
                    title: 'ID',
                    width: 100,
                    key: 'id',
                },
                {
                    title: '标题',
                    key: 'title',
                    minWidth: 200,
                },
                {
                    title: '类型',
                    key: 'type_name',
                    minWidth: 200,
                },

                {
                    title: '状态',
                    key: 'status',
                    maxWidth: 160,
                    slot: 'status',
                    align: 'center',
                },
                {
                    title: '最后修改',
                    key: 'updated_at',
                    maxWidth: 200,
                    slot: 'updatedTime',
                },

                {
                    title: '创建于',
                    key: 'created_at',
                    maxWidth: 200,
                },
                {
                    title: '操作',
                    slot: 'action',
                    width: 160,
                    align: 'center',
                },
            ],
        }
    },

    created() {
        this.getIndex(1)
    },
}
</script>