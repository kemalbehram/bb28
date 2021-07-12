<template>
    <div class="relative mb-10">
        <lett-loading :visible="loading" @click="getIndex" />

        <Collapse accordion v-model="collapse">
            <Panel :key="index" :name="item.id.toString()" v-for="(item,index) in items">
                <span>{{item.title}}</span>
                <div class="collapse-button">
                    <Button @click.stop="updateData(true,item)" class="mr-6" size="small" type="primary">修改</Button>
                    <Button @click.stop="deleteData(item)" size="small" type="error">删除</Button>
                </div>
                <span class="hidden">{{item._index = index}}</span>
                <p slot="content" v-html="$options.filters.trim(item.content)"></p>
            </Panel>
        </Collapse>

        <modal-create :config.sync="dataware.create" @success="createData" action="create" />
        <modal-update :config.sync="dataware.update" @refresh="getIndex" @success="updateData" action="update" />
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
            collapse: '1',

            dataware: {
                index: {
                    api: '/single-page/about/index'
                },
                create: {
                    api: '/single-page/about/create',
                    visible: false
                },
                update: {
                    api: '/single-page/about/update',
                    visible: false,
                    data: {},
                    fields: ['title', 'content', 'sort', 'updated_at']
                },
                delete: {
                    api: '/single-page/about/delete'
                }
            }
        }
    }
}
</script>

<style lang="less" scoped>
.collapse-button {
    position: absolute;
    right: 16px;
    top: 0;
}

/deep/.ivu-collapse-content > .ivu-collapse-content-box {
    padding-left: 30px;
}
/deep/ .ivu-collapse > .ivu-collapse-item > .ivu-collapse-header {
    color: @black;
}
</style>