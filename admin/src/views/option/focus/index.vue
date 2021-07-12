<template>
    <Card dis-hover>
        <table-header-new title="焦点图设置">
            <Button @click="$refs.table.createData(true)" size="small" type="primary">添加焦点图</Button>
        </table-header-new>

        <table-data @on-search="onSearch" ref="table" />
    </Card>
</template>

<script>
import tableData from './table-data.vue'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'OptionFocus',
    components: {
        tableData
    }
}
</script>

