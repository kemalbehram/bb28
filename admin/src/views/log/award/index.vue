<template>
    <Card dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="奖励明细">
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
    name: 'dataAward',
    components: {
        tableData
    },

    data() {
        return {
            config: {
                select: {
                    field: 'user_id',
                    value: ''
                },
                fields: [
                    {
                        value: 'user_id',
                        label: '领取用户'
                    }
                ]
            }
        }
    }
}
</script>
