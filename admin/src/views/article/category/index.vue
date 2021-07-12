<template>
    <Card dis-hover>
        <table-header title="文章分类">
            <Button type="primary" size="small" @click="$refs.table.createData(true)">添加分类</Button>
        </table-header>

        <table-data ref="table"></table-data>
    </Card>
</template>

<script>
import tableData from './table-data'
export default {
    name: 'articleCategory',
    components: {
        tableData
    }
}
</script>