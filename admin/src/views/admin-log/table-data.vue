<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="tableColumns || columns" :data="items" :height="300 | table_height" border>
            <span class="custom-tag" slot="created_at" slot-scope="{row}">{{$moment(row.created_at).format('MM-DD HH:mm:ss')}}</span>
        </Table>

        <lett-page :page="page" @on-change="onPage" />
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],

    props: {
        tableColumns: {
            type: Array,
            default() {
                return this.columns
            },
        },
    },

    data() {
        return {
            dataware: {
                index: {
                    api: '/admin-log/index',
                },
            },

            columns: [
                {
                    title: '管理员ID',
                    key: 'admin_id',
                    align: 'left',
                    width: 140,
                },
                {
                    title: '请求源地址',
                    key: 'referer',
                    width: 200,
                },
                {
                    title: '请求接口',
                    key: 'path',
                },
                {
                    title: '请求代理',
                    key: 'user_agent',
                },
                {
                    title: '请求IP',
                    key: 'ip',
                },
                {
                    title: '时间',
                    slot: 'created_at',
                    align: 'right',
                    width: 140,
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

<style lang="less" scoped>
.red-bag-wrap {
    padding: 3px 0;
}
.red-bag-log {
    margin: 3px 6px 3px 0;
    cursor: pointer;
}
</style>