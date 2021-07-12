<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="510 | table_height" border>
            <table-user :data="row.user" slot="nickname" slot-scope="{row}" />

            <template slot="balance" slot-scope="{row}">{{row.wallet.balance}}</template>
            <div @click="setCreate(row)" class="table-pointer" slot="user_id" slot-scope="{row}">{{row.user_id}}</div>
            <template slot="channel" slot-scope="{row}">{{row.channel_info.bank_name}}</template>

            <div :class="$store.state.config.bank_status[row.status.toString()].class" slot="status" slot-scope="{row}" v-html="$store.state.config.bank_status[row.status.toString()].name" />

            <div slot="action" slot-scope="{row, index}">
                <!-- <Button @click="cancelRecharge(row)" size="small" v-if="row.can_cancel">撤消</Button> -->
                <Button @click="updateData(true,row)" size="small" type="primary">操作</Button>
            </div>
            <template slot="type" slot-scope="{row}">{{$store.state.config.type[row.channel]}}</template>
            <table-date :date="row.created_at" class="color-black" slot="created_at" slot-scope="{row}" />
        </Table>
        <lett-page :page="page" @on-change="onPage" />
        <modal-update :config.sync="dataware.update" @success="updateData" />
    </div>
</template>

<script>
import modalUpdate from './modal-update'
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],
    components: {
        modalUpdate,
    },
    data() {
        return {
            dataware: {
                index: {
                    api: '/recharge/index',
                },
                update: {
                    api: '/recharge/update',
                    visible: false,
                    data: {},
                    fields: ['status', 'updated_at'],
                },
            },
            columns: [
                {
                    title: '流水ID',
                    maxWidth: 120,
                    key: 'id',
                },
                {
                    title: '昵称',
                    slot: 'nickname',
                    width: 200,
                },
                {
                    title: '用户ID',
                    slot: 'user_id',
                    align: 'center',
                    width: 200,
                },
                {
                    title: '充值金额',
                    key: 'amount',
                    align: 'right',
                    width: 140,
                },
                {
                    title: '提现类型',
                    align: 'right',
                    slot: 'type',
                    width: 200,
                },
                {
                    title: '状态',
                    slot: 'status',
                    align: 'center',
                },
                // {
                //     title: '渠道',
                //     slot: 'channel'
                // },

                {
                    title: '操作',
                    slot: 'action',
                    width: 100,
                    align: 'center',
                },
                {
                    title: '时间',
                    slot: 'created_at',
                    align: 'center',
                },
            ],
        }
    },

    methods: {
        setCreate(data) {
            this.$parent.$parent.$refs.create.form.user_id = data.user_id
            this.$parent.$parent.$refs.create.form.nickname = data.user.nickname
        },

        cancelRecharge(data) {
            let message = '您确定要撤回该笔充值吗？'
            if (confirm(message) !== true) {
                return false
            }

            this.$post('/recharge/cancel', data).then((res) => {
                res.data.status = res.data.status.toString()
                this.form = res.data
                this.status = res.data.status
                this.getIndex()
            })
        },
    },
}
</script>