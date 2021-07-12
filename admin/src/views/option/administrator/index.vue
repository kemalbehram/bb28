<template>
    <Card dis-hover>
        <table-header-new title="所有管理员">
            <Button @click="$refs.table.createData(true)" size="small" type="primary">创建管理员</Button>
        </table-header-new>

        <table-data ref="table" />
    </Card>
</template>

<script>
import tableData from './table-data.vue'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'administratorHome',
    components: {
        tableData
    }
}
</script>

