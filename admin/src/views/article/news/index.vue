<template>
    <Card dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="所有活动">
            <Button @click="$refs.table.createData(true)" size="small" type="primary">添加活动</Button>
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
        tableData
    },

    data() {
        return {
            config: {
                value: {
                    page: '1'
                },

                fields: [
                    {
                        value: 'title',
                        label: '文章标题',
                        default: true
                    }
                ]
            }
        }
    }
}
</script>
