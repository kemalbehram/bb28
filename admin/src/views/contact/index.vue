<template>
    <Card dis-hover>
        <table-header :query-field="queryField" @on-query="onQuery" title="所有留言">
            <Select @on-change="onQueryStatus" size="small" v-model="query.status">
                <Option value="0">所有留言</Option>
                <Option value="1">等待处理</Option>
                <Option value="2">处理中</Option>
                <Option value="3">处理结束</Option>
            </Select>
        </table-header>

        <table-data ref="table"></table-data>
    </Card>
</template>

<script>
import tableData from './table-data'
export default {
    name: 'articleHome',
    components: {
        tableData
    },

    data() {
        return {
            total: 0,
            query: {
                status: '0'
            },
            queryField: [
                {
                    value: 'content',
                    label: '留言内容',
                    default: true
                },

                {
                    value: 'name',
                    label: '姓名'
                },

                {
                    value: 'mobile',
                    label: '手机号'
                }
            ]
        }
    },

    methods: {
        onQuery(query) {
            this.$refs.table.getIndex(1, query)
        },

        onQueryStatus(status) {
            var params = { status }
            this.onQuery(params)
        }
    }
}
</script>
