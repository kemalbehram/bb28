<template>
    <div class="relative pb-10">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" border expand-key="name">
            <Tooltip :content="row.updated_at" placement="top" slot="updatedAt" slot-scope="{row}">
                <Time :interval="1" :time="row.updated_at" />
            </Tooltip>

            <template slot="action" slot-scope="{row, index }">
                <Button @click="updateData(true,row)" class="mr-6" size="small" type="primary">修改</Button>
                <Button @click="deleteData(row)" size="small" type="error">删除</Button>
            </template>
        </Table>

        <modal-create :config.sync="dataware.create" @success="createData" action="create" title="创建分类" />
        <modal-update :config.sync="dataware.update" @success="updateData" action="update" title="修改分类" />
    </div>
</template>

<script>
import modalUpdate from './modal-form'
import modalCreate from './modal-form'
export default {
    components: {
        modalUpdate,
        modalCreate
    },
    data() {
        return {
            loading: false,

            dataware: {
                index: {
                    api: '/article/cat/index'
                },
                create: {
                    api: '/article/cat/create',
                    visible: false
                },
                update: {
                    api: '/article/cat/update',
                    visible: false,
                    data: { name: '' },
                    fields: ['name', 'desc', 'updated_at']
                },
                delete: {
                    api: '/article/cat/delete'
                }
            },

            columns: [
                {
                    title: 'ID',
                    width: 100,
                    key: 'id',
                    tooltip: true
                },
                {
                    title: '名称',
                    key: 'name',
                    tooltip: true
                },
                {
                    title: '文章数',
                    key: 'article_count',
                    maxWidth: 320,
                    tooltip: true,
                    align: 'center'
                },
                {
                    title: '最后修改',
                    slot: 'updatedAt',
                    maxWidth: 320
                },
                {
                    title: '操作',
                    width: 160,
                    slot: 'action',
                    align: 'center'
                }
            ]
        }
    },

    created() {
        this.getIndex()
    },

    computed: {
        items() {
            return this.$store.state.article.category
        }
    },

    methods: {
        getIndex() {
            this.$store.dispatch('getArticleCat', this)
        },

        createData(open, data) {
            if (data) {
                this.$store.state.article.category = data.items
            }
            return this.$dataware.create(this, open)
        },

        updateData(open, data, success = false) {
            if (success === true) {
                this.$store.state.article.category = data.items
            }
            const fields = this.dataware.update.fields
            return this.$dataware.update(this, open, data, false)
        },

        deleteData(data) {
            const api = this.dataware.delete.api
            return this.$dataware.remove(api, this, data)
        }
    }
}
</script>