<template>
    <div>
        <create-recharge class="mb-20" ref="create" />
        <Card dis-hover>
            <table-header-new :config.sync="config" @on-search="onSearch" title="发送记录">
                <div class="color-sub">此处展示历史发送记录，如果需要修改或者删除发送记录 请联系程序工程师</div>
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
        createRecharge
    },

    data() {
        return {
            loading: false,
            config: {
                value: {},
                select: {
                    field: 'admin_id',
                    value: ''
                },
                fields: [
                    {
                        value: 'admin_id',
                        label: '管理员ID',
                        default: true
                    },
                    {
                        value: 'user_id',
                        label: '用户ID'
                    },
                    {
                        value: 'mobile',
                        label: '手机号'
                    }
                ]
            }
        }
    }
}
</script>
