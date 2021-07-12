<template>
    <Drawer :closable="false" :footer-hide="true" @on-visible-change="onVisible" class="drawer-with-action" title="零钱记录" v-model="visible" width="840">
        <table-header-new :config.sync="config" @on-search="getIndex" class="mb-10" slot="header" title="资金记录">
            <Select @on-change="getIndex()" class="mr-10" size="small" style="min-width:150px" v-model="config.value.field">
                <Option value="all">所有记录</Option>
                <Option value="income">收入</Option>
                <Option value="outcome">支出</Option>
                <!-- <Option value="withdraw">提现记录</Option> -->
            </Select>
        </table-header-new>

        <lett-loading :visible="loading" @click="getIndex()" />
        <Table :columns="columns" :data="items" :height="230 | table_height" border>
            <div class="pointer" slot="target" slot-scope="{row}">{{row.target_info.id}} / {{row.target_info.nickname}}</div>
            <span :class="amountClass(row.amount)" slot="amount" slot-scope="{row}">{{row.amount}}</span>

            <div :class="source[row.source].class" slot="source" slot-scope="{row}">{{source[row.source].title}}</div>

            <div slot="award_agent" slot-scope="{row}" v-if="row.award">
                <template v-if="row.award.agent_refer">{{parseFloat(row.award.agent_refer) + parseFloat(row.award.agent_base) | currency}}</template>
                <template v-else>{{row.award.agent_base}}</template>
            </div>

            <template slot="created_at" slot-scope="{row}">
                <Button @click="onCancel(row.id)" class="mr-6" size="small" v-if="row.can_cancel">撤销</Button>
                <Button class="mr-6" disabled size="small" v-if="row.canceled_at">已撤销</Button>
                <span class="custom-tag">{{$moment(row.created_at).format('MM-DD HH:mm:ss')}}</span>
            </template>
        </Table>

        <lett-page :page="page" @on-change="getIndex" />
    </Drawer>
</template>

<script>
export default {
    data() {
        return {
            visible: false,
            loading: false,
            source: {
                income: {
                    title: '收入',
                    class: 'color-red',
                },
                outcome: {
                    title: '支出',
                    class: 'color-green',
                },
            },
            loading: false,
            page: {},
            items: [],
            config: {
                value: {
                    source: 'all',
                    page: 1,
                    user_id: null,
                },
                select: {
                    field: 'id',
                    value: '',
                },
                fields: [
                    {
                        value: 'id',
                        label: '记录编号',
                    },
                ],
            },
        }
    },

    computed: {
        member() {
            return this.$parent.data
        },

        columns() {
            let result = [
                {
                    title: 'ID',
                    key: 'id',
                    align: 'left',
                    maxWidth: 100,
                },

                {
                    title: '对方',
                    slot: 'target',
                    width: 200,
                },

                {
                    title: '类型',
                    slot: 'source',
                    widht: 60,
                },

                {
                    title: '金额',
                    slot: 'amount',
                    align: 'right',
                    // width: 140,
                    // sortable: true,
                },
            ]
            if (this.member.hasOwnProperty('agent') && this.member.agent.status === true) {
                result.push({
                    title: '提成',
                    slot: 'award_agent',
                    align: 'right',
                })
            } else {
                result.push({
                    title: '手续费',
                    key: 'transfer_fee',
                    align: 'right',
                })
            }

            result.push({
                title: '时间',
                slot: 'created_at',
                align: 'right',
                minWidth: 90,
                // width: 200
            })

            return result
        },
    },

    methods: {
        getIndex(page = 1) {
            this.config.value.user_id = this.$parent.data.id
            this.config.value.page = page
            const api = '/member/transfer-log'
            const params = this.config.value
            return this.$dataware.index(api, this, page, params)
        },

        onVisible(visible) {
            if (visible === false) {
                return false
            }

            return this.getIndex()
        },

        amountClass(amount) {
            if (parseFloat(amount) > 0) {
                return 'color-red'
            }
            return 'color-green'
        },

        onCancel(id) {
            let form = { id }
            this.loading = true
            this.$post('/transfer/cancel', form).then((res) => {
                this.loading = false
                if (res.code !== 200) return false
                this.getIndex()
            })
        },
    },
}
</script>


<style lang="less" scoped>
.drawer-with-action {
    /deep/.ivu-drawer-header {
        border: 0;
    }

    /deep/.ivu-drawer-body {
        padding-top: 0px;
    }
}
</style>
