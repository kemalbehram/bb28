<template>
    <Card dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="转账明细">
            <Button @click="onReset" size="small" type="primary">初始</Button>
            <Button @click="onSearch" class="ml-6" size="small" type="primary">刷新</Button>
        </table-header-new>

        <table-data @on-search="onSearch" ref="table" />
    </Card>
</template>

<script>
import tableData from './table-data'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'dataTransfer',
    components: {
        tableData
    },

    data() {
        return {
            config: {
                fields: [
                    {
                        value: 'payee_id',
                        label: '收款用户ID',
                        default: true
                    },
                    {
                        value: 'drawee_id',
                        label: '付款用户ID'
                    },
                    {
                        value: 'id',
                        label: '转账流水ID'
                    }
                ]
            }
        }
    }
}
</script>
