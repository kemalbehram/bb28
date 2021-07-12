<template>
    <div>
        <create-recharge class="mb-20" ref="create" />
        <Card dis-hover>
            <table-header-new :config.sync="config" @on-search="onSearch" title="修正记录">
                <div class="color-sub">此处展示历史修正记录，如果需要修改或者删除修正记录 请联系程序工程师</div>
            </table-header-new>

            <table-data @on-search="onSearch" ref="table" />
        </Card>
    </div>
</template>

<script>
import createRecharge from './create'
import tableData from './table-data'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'riskStatsFix',
    components: {
        tableData,
        createRecharge,
    },

    data() {
        return {
            loading: false,
            config: {
                value: {
                    status: '0',
                },
                select: {
                    field: 'admin_id',
                    value: '',
                },
                fields: [
                    {
                        value: 'admin_id',
                        label: '管理员ID',
                        default: true,
                    },
                    {
                        value: 'id',
                        label: '流水ID',
                    },
                    {
                        value: 'value',
                        label: '操作金额',
                    },
                ],
            },
        }
    },
}
</script>
