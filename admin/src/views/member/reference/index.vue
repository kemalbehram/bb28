<template>
    <Card dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="下级推广">
            <Button @click="onSearch" size="small" type="primary">刷新</Button>
        </table-header-new>

        <table-data @on-search="onSearch" ref="table" />
    </Card>
</template>

<script>
import tableData from './table-data'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'memberAgent',
    components: {
        tableData,
    },

    data() {
        return {
            config: {
                value: {
                    page: '1',
                },
                select: {
                    field: 'user_id',
                    value: '',
                },
                fields: [
                    {
                        value: 'user_id',
                        label: '用户ID',
                    },
                    {
                        value: 'ref_str',
                        label: '上级ID',
                    },
                ],
            },
        }
    },
}
</script>
