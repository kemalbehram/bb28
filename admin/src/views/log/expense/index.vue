<template>
    <div>
        <create-data class="mb-20" ref="create" />
        <Card :bordered="bordered" dis-hover>
            <table-header-new :config.sync="config" @on-search="onSearch" title="福利开支">
                <Button @click="onReset" size="small" type="primary">初始</Button>
                <Button @click="onSearch" class="ml-6" size="small" type="primary">刷新</Button>
            </table-header-new>

            <table-data :table-columns="tableColumns" @on-search="onSearch" ref="table" />
        </Card>
    </div>
</template>
<script>
import createData from './create'
import tableData from './table-data'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'userRebateLog',
    components: {
        tableData,
        createData,
    },
    props: {
        tableColumns: {
            type: Array,
        },
        assign: {
            default: null,
            type: [String, Number],
        },
    },

    data() {
        return {
            config: {
                select: {
                    field: 'user_id',
                    value: '',
                },
                value: {
                    field: 'all',
                    page: 1,
                },
                fields: [
                    {
                        value: 'user_id',
                        label: '用户ID',
                    },
                ],
            },
        }
    },
    computed: {
        bordered() {
            return this.assign ? false : true
        },
    },
}
</script>
<style lang="less" scoped>
</style>