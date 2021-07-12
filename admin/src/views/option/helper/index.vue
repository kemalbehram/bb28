<template>
    <Card dis-hover>
        <table-header-new title="帮助中心">
            <Button @click="$refs.table.createData(true)" size="small" type="primary">添加信息</Button>
        </table-header-new>

        <table-data ref="table"></table-data>
    </Card>
</template>

<script>
import tableData from './table-data.vue'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'optionHelper',
    components: {
        tableData
    }
}
</script>

