<template>
    <div class="relative mb-10">
        <lett-loading :visible="loading" />

        <Table :columns="columns" :data="items" border>
            <template slot="mapping" slot-scope="{row,index}">{{ mapKeyTrans(row.mapping) }}</template>

            <template slot="param" slot-scope="{row,index}">
                <Tag :key="key" v-for="(item,key) in row.params">{{key}}: {{item}}</Tag>
            </template>

            <template slot="scope" slot-scope="{row}">
                <Tag :key="key" v-for="(item,key) in row.scope">{{scope[item]}}</Tag>
            </template>

            <template slot="action" slot-scope="{row, index}">
                <Button @click="updateData(true,row)" class="mr-6" size="small" type="primary">修改</Button>
                <Button @click="deleteData(row)" size="small" type="error">删除</Button>
            </template>
        </Table>

        <modal-create :config.sync="dataware.create" @success="createData" action="create" />
        <modal-update :config.sync="dataware.update" @success="updateData" action="update" />
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
        modalCreate
    },
    data() {
        return {
            dataware: {
                index: {
                    api: '/option/focus/index'
                },
                create: {
                    api: '/option/focus/create',
                    visible: false
                },
                update: {
                    api: '/option/focus/update',
                    visible: false,
                    data: {},
                    fields: [
                        'image',
                        'mapping',
                        'params',
                        'scope',
                        'updated_at'
                    ]
                },
                delete: {
                    api: '/option/focus/delete'
                }
            },
            columns: [
                {
                    title: 'ID',
                    key: 'id',
                    width: 80
                },
                {
                    title: '链接',
                    slot: 'mapping',
                    maxWidth: 140
                },
                {
                    title: '参数',
                    slot: 'param',
                    maxWidth: 240
                },
                {
                    title: '展示位置',
                    slot: 'scope'
                },
                {
                    title: '创建',
                    key: 'created_at',
                    maxWidth: 200
                },
                {
                    title: '操作',
                    slot: 'action',
                    width: 160,
                    align: 'center'
                }
            ]
        }
    },

    computed: {
        scope() {
            return this.$store.state.config.focus_scope
        }
    },

    methods: {
        mapKeyTrans(key) {
            return this.$store.state.config.focus_mapping[key].name
        }
    }
}
</script>