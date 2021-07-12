<template>
    <div>
        <create-withdraw class="mb-20" ref="create" />
        <Card dis-hover>
            <table-header-new :config.sync="config" @on-search="onSearch" title="提现管理">
                <Select @on-change="onSearch" size="small" v-model="config.value.status">
                    <Option value="0">所有提现</Option>
                    <Option value="1">等待处理</Option>
                    <Option value="2">提现成功</Option>
                    <Option value="3">提现失败</Option>
                </Select>

                <Button @click="onReset" class="ml-10" size="small" type="primary">初始</Button>
                <Button @click="onSearch" class="ml-6" size="small" type="primary">
                    <Checkbox @on-change="autoLoad" size="small"></Checkbox>
                    <span class="font-13">刷新</span>
                </Button>
            </table-header-new>

            <table-data @on-search="onSearch" ref="table" />
        </Card>
    </div>
</template>

<script>
import tableData from './table-data'
import JsMixins from '@/components-ue/table/table-index.js'
import createWithdraw from './create'
export default {
    mixins: [JsMixins],
    name: 'balanceWithdraw',
    components: {
        tableData,
        createWithdraw
    },

    data() {
        return {
            config: {
                value: {
                    status: '0'
                },

                select: {
                    field: 'user_id',
                    value: ''
                },

                fields: [
                    {
                        value: 'user_id',
                        label: '用户ID',
                        default: true
                    },
                    {
                        value: 'id',
                        label: '提现ID'
                    },
                    {
                        value: 'amount',
                        label: '提现金额'
                    }
                ]
            }
        }
    }
}
</script>
