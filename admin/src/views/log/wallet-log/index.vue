<template>
    <Card :bordered="bordered" dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="资金明细">
            <Select @on-change="onSearch" class="mr-10" size="small" v-model="config.value.field">
                <Option value="all">所有记录</Option>
                <Option value="balance">现金账户</Option>
                <Option value="bank">银行账户</Option>
                <!-- <Option value="fund">余额宝</Option> -->
                <Option value="balance-recharge">充值</Option>
                <Option value="balance-withdraw">提现</Option>
                <Option value="balance-withdraw-failed">提现失败</Option>
                <Option value="balance-recharge-back">充值返利</Option>
            </Select>

            <Button @click="onReset" size="small" type="primary">初始</Button>
            <Button @click="onSearch" class="ml-6" size="small" type="primary">刷新</Button>
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
