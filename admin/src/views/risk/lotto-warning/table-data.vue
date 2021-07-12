<template>
    <Card dis-hover>
        <table-header-new title="报警记录">
            <div class="color-sub mt--4">
                记录分为三个等级，如遇
                <span class="color-red">错误</span>等级，有可能被刷分，请仔细核对处理
            </div>

            <div class="header-action">
                <Button @click="getIndex(1)">刷新</Button>
            </div>
        </table-header-new>

        <div class="relative">
            <lett-loading :visible="loading" @click="getIndex(1)" class="border-all" />
            <Table :columns="columns" :data="items" :height="800 | table_height" border no-data-text="暂无相关报警记录 请持续关注">
                <span @click="onFix(row)" class="pointer" slot="model_name" slot-scope="{row}">{{$store.state.config.lotto_waning.title[row.model_name]}}</span>

                <span :class="amountClass(row.value)" slot="value" slot-scope="{row}">{{row.value}}</span>

                <template slot="level" slot-scope="{row}">
                    <span class="color-red" v-if="row.level === 'error'">错误</span>
                    <span class="color-sub" v-if="row.level === 'system'">系统</span>
                    <span class="color-orange" v-if="row.level === 'warning'">警告</span>
                </template>

                <span class="custom-tag" slot="value" slot-scope="{row}">{{row.value}}</span>
                <span class="custom-tag" slot="source" slot-scope="{row}">{{row.source}}</span>

                <table-date :date="row.created_at" slot="created_at" slot-scope="{row}" />

                <template slot="handle" slot-scope="{row}">
                    <table-date :date="row.handled_at" class="color-black" v-if="row.handled_at !== null " />

                    <template v-else>
                        <Button @click="onHandle(row)" size="small">确认处理</Button>
                    </template>
                </template>
            </Table>
        </div>
        <lett-page :page="page" @on-change="getIndex" />
    </Card>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],

    data() {
        return {
            dataware: {
                index: {
                    api: '/lotto/warning/index',
                },
            },
            columns: [
                {
                    title: '彩票',
                    slot: 'model_name',
                },
                {
                    title: '期号',
                    key: 'model_id',
                },
                {
                    title: '错误级别',
                    slot: 'level',
                    align: 'center',
                },
                {
                    title: '正确值',
                    slot: 'value',
                    align: 'center',
                    width: 180,
                },
                {
                    title: '错误值',
                    slot: 'source',
                    align: 'center',
                    width: 180,
                },
                {
                    title: '操作备注',
                    key: 'content',
                    minWidth: 300,
                },
                {
                    title: '创建时间',
                    slot: 'created_at',
                    align: 'center',
                },
                {
                    title: '操作',
                    slot: 'handle',
                    align: 'center',
                },
            ],
        }
    },

    methods: {
        onHandle(data) {
            let message = '请确认已核对并处理完相关信息'
            if (confirm(message) !== true) return false

            let form = { id: data.id }
            this.loading = true
            this.$post('/lotto/warning/handle', form, false).then((res) => {
                if (res.code !== 200) {
                    this.loading = false
                    return this.$Message.warning(res.message)
                }
                this.$Message.success(res.message)
                this.getIndex()
            })
        },

        onFix(data) {
            this.$parent.$refs.create.form.model_name = data.model_name
            this.$parent.$refs.create.form.model_id = data.model_id
            this.$parent.$refs.create.form.field = data.field
        },
    },
}
</script>

<style lang="less" scoped>
.header-action {
    position: absolute;
    right: 0;
    top: 3px;
}
</style>
