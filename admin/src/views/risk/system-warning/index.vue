<template>
    <Card dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="系统报警">
            <div class="color-sub">正在开发中</div>
        </table-header-new>

        <!-- <table-data @on-search="onSearch" ref="table" /> -->
    </Card>
</template>

<script>
import tableData from './table-data'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'riskWalletSystem',
    components: {
        tableData
    },

    data() {
        return {
            config: {
                value: {
                    page: '1'
                },

                fields: [
                    {
                        value: 'user_id',
                        label: '对方ID',
                        default: true
                    }
                ]
            }
        }
    }
}
</script>
