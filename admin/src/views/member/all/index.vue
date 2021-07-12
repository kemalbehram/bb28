<template>
    <Card dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="用户管理">
            <Select @on-change="onSearch" class="mr-10" size="small" v-model="config.value.user_type">
                <Option value="all">所有用户</Option>
                <Option value="agent">代理用户</Option>
                <Option value="trial">试玩账户</Option>
                <Option value="transfer">禁止转账</Option>
                <Option value="bet">禁止下注</Option>
            </Select>
            <Select @on-change="onSearch" size="small" v-model="config.value.disable">
                <Option value="0">所有状态</Option>
                <Option value="1">禁止登陆</Option>
            </Select>

            <Button @click="onReset" class="ml-10" size="small" type="primary">初始</Button>
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
    name: 'memberHome',
    components: {
        tableData,
    },

    data() {
        return {
            config: {
                value: {
                    user_type: 'all',
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
