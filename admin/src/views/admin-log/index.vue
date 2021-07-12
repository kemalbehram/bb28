<template>
    <Card :bordered="bordered" dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="资金明细">
            <DatePicker :options="$store.state.date_picker" @on-change="getData" class="date-picker" format="yyyy-MM-dd" placeholder="请选择日期" placement="bottom-end" type="daterange"></DatePicker>
        </table-header-new>

        <table-data :table-columns="tableColumns" @on-search="onSearch" ref="table" />
    </Card>
</template>

<script>
import tableData from './table-data'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'dataRedBag',
    components: {
        tableData,
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
                    field: 'admin_id',
                    value: '',
                },
                value: {
                    // field: 'all',
                    page: 1,
                },
                fields: [
                    {
                        value: 'admin_id',
                        label: '管理员ID',
                    },
                    {
                        value: 'ip',
                        label: '请求IP',
                    },
                ],
            },
            date: {},
        }
    },

    computed: {
        bordered() {
            return this.assign ? false : true
        },
    },
    methods: {
        getData(date = null) {
            this.date = date
            let params = []

            if (date) {
                params = {
                    start: date[0],
                    end: date[1],
                }
            }
            return this.$refs.table.getIndex(this.config.page, params)
        },
    },
}
</script>
<style lang="less" scoped>
.date-picker {
    /deep/.ivu-select-dropdown {
        left: 0px !important;
    }
}
</style>
