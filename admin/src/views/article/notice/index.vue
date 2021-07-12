<template>
    <Card dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="所有公告">
            <Select @on-change="onSearch" class="mr-10" size="small" v-model="config.value.type">
                <Option value="all">所有公告</Option>
                <Option value="index">首页弹框</Option>
                <Option value="scroll">首页滚动</Option>
                <Option value="list">列表</Option>
                <Option value="game">游戏</Option>
            </Select>
            <Button @click="$refs.table.createData(true)" size="small" type="primary">添加公告</Button>
        </table-header-new>

        <table-data @on-search="onSearch" ref="table" />
    </Card>
</template>

<script>
import tableData from './table-data'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'articleNews',
    components: {
        tableData,
    },

    data() {
        return {
            config: {
                value: {
                    page: '1',
                    type: 'all',
                },

                fields: [
                    {
                        value: 'title',
                        label: '文章标题',
                        default: true,
                    },
                ],
            },
        }
    },
}
</script>
