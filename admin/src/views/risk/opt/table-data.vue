<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="510 | table_height" border>
            <template slot="type" slot-scope="{row}">{{opt_name[row.type]}}</template>
            <template slot="user" slot-scope="{row}">{{row.type == 'register' ? '新用户' : row.user_id}}</template>
            <template slot="verify_code" slot-scope="{row}">{{row.opt_value == 0 ? '随机验证码' : row.opt_value}}</template>
            <span :class="amountClass(row.value)" slot="value" slot-scope="{row}">{{row.value}}</span>
            <table-date :date="row.created_at" class="color-black" slot="created_at" slot-scope="{row}" />
        </Table>
        <lett-page :page="page" @on-change="onPage" />
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],

    data() {
        return {
            dataware: {
                index: {
                    api: '/admin-opt/index'
                }
            },
            columns: [
                {
                    title: '管理员ID',
                    key: 'admin_id',
                    width: 120
                },

                {
                    title: '类型',
                    slot: 'type'
                },
                {
                    title: '用户ID',
                    slot: 'user'
                },
                {
                    title: '手机号',
                    key: 'mobile'
                },

                {
                    title: '验证码',
                    slot: 'verify_code'
                },
                {
                    title: '时间',
                    slot: 'created_at',
                    align: 'center',
                    width: 160
                }
            ]
        }
    },
    computed: {
        opt_name() {
            return this.$store.state.config.admin_opts
        }
    },
    methods: {
        amountClass(amount) {
            if (parseFloat(amount) > 0) {
                return 'color-red'
            }
            return 'color-green'
        }
    }
}
</script>