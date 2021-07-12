<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="510 | table_height" border>
            <table-user :data="row.user" slot="user" slot-scope="{row}" />
            <template slot="name" slot-scope="{row}">{{row.title}} / {{row.name}}</template>
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
                    api: '/stats-fix/index',
                },
            },
            columns: [
                {
                    title: '管理员ID',
                    key: 'admin_id',
                    width: 120,
                },
                {
                    title: '修正日期',
                    key: 'date',
                    width: 180,
                },
                {
                    title: '修正类型',
                    slot: 'name',
                },
                {
                    title: '用户',
                    slot: 'user',
                    width: 160,
                },
                {
                    title: '修正金额',
                    slot: 'value',
                    align: 'right',
                    width: 100,
                },
                {
                    title: '操作备注',
                    key: 'remark',
                },
                {
                    title: '时间',
                    slot: 'created_at',
                    align: 'center',
                    width: 160,
                },
            ],
        }
    },

    methods: {
        amountClass(amount) {
            if (parseFloat(amount) > 0) {
                return 'color-red'
            }
            return 'color-green'
        },
    },
}
</script>
