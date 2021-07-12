<template>
    <Card dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="合作代理">
            <Button @click="onReset" size="small" type="primary">初始</Button>
            <Button @click="onSearch" class="ml-6" size="small" type="primary">刷新</Button>
        </table-header-new>

        <table-data @on-search="onSearch" @on-sort="onSort" ref="table" />
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
                    user_type: 'agent',
                    disable: '0',
                    page: '1',
                },
                select: {
                    field: 'id',
                    value: '',
                },
                fields: [
                    {
                        value: 'id',
                        label: '用户ID',
                    },
                    {
                        value: 'mobile',
                        label: '手机号',
                        default: true,
                    },
                    {
                        value: 'nickname',
                        label: '昵称',
                    },
                    {
                        value: 'real_name',
                        label: '姓名',
                    },
                    {
                        value: 'requested_ip',
                        label: '最后IP',
                    },
                    {
                        value: 'created_ip',
                        label: '注册IP',
                    },
                ],
            },
        }
    },
}
</script>
