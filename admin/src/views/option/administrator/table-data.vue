<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />
        <Table :columns="columns" :data="items" border>
            <template slot="nickname" slot-scope="{row}">
                <lett-avatar :src="row.avatar" />
                {{row.nickname}}
            </template>

            <template slot="permission" slot-scope="{row}">
                <Tag :key="index" v-for="(item, index) in row.permissions">{{item.title}}</Tag>
            </template>

            <Tooltip :content="row.requested_at" placement="top" slot="requestedAt" slot-scope="{row}" v-if="row.requested_at">
                <Time :interval="1" :time="row.requested_at" />
            </Tooltip>

            <template slot="action" slot-scope="{row, index }">
                <Button @click="updateData(true,row)" class="mr-6" size="small" type="primary">修改</Button>
                <Button @click="deleteData(row)" size="small" type="error">删除</Button>
            </template>
        </Table>

        <lett-page :page="page" @on-change="getIndex" />

        <modal-create :config.sync="dataware.create" @success="createData" title="创建管理员" />
        <modal-update :config.sync="dataware.update" @success="updateData" title="修改管理员" />
    </div>
</template>

<script>
import modalCreate from './modal-create'
import modalUpdate from './modal-update'
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],
    components: {
        modalCreate,
        modalUpdate
    },
    data() {
        return {
            dataware: {
                index: {
                    api: '/administrator/index'
                },
                create: {
                    api: '/administrator/create',
                    visible: false
                },
                update: {
                    api: '/administrator/update',
                    visible: false,
                    data: {},
                    fields: [
                        'avatar',
                        'nickname',
                        'username',
                        'updated_at',
                        'created_at'
                    ]
                },
                delete: {
                    api: '/administrator/delete'
                }
            },
            columns: [
                {
                    title: 'ID',
                    key: 'id',
                    maxWidth: 100
                },
                {
                    title: '昵称',
                    slot: 'nickname'
                },

                {
                    title: '用户名',
                    key: 'username',
                    tooltip: true
                },
                {
                    title: '最后访问',
                    key: 'requested_at',
                    maxWidth: 200,
                    slot: 'requestedAt'
                },

                {
                    title: '最后IP',
                    key: 'requested_ip',
                    maxWidth: 200
                },
                {
                    title: '操作',
                    slot: 'action',
                    width: 160,
                    align: 'center'
                }
            ],
            permissionAll: {}
        }
    }
}
</script>