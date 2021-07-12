<template>
    <Card dis-hover>
        <table-header-new title="卡奖处理">
            <div class="color-sub mt--4">如需要手动开奖，请仔细核对开奖结果</div>

            <div class="header-action">
                <Button @click="getIndex(1)">刷新</Button>
            </div>
        </table-header-new>

        <div class="relative mb-10">
            <lett-loading :visible="loading" @click="getIndex(1)" class="border-all" />
            <Table :columns="columns" :data="items" :height="200" border no-data-text=" ">
                <span @click="onFix(row)" class="pointer" slot="model_name" slot-scope="{row}">{{$store.state.config.lotto_waning.title[row.model_name]}}</span>
                <table-date :date="row.lotto_at" slot="lotto_at" slot-scope="{row}" />
                <template slot="handle" slot-scope="{row}">
                    <Button @click="onFix(row)" size="small">手动开奖</Button>
                </template>
            </Table>
        </div>
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
                    api: '/lotto/warning/delay'
                }
            },
            columns: [
                {
                    title: '彩票',
                    slot: 'model_name',
                    width: 200
                },
                {
                    title: '期号',
                    key: 'id',
                    width: 200
                },

                {
                    title: '开奖时间',
                    slot: 'lotto_at',
                    align: 'left'
                },
                {
                    title: '操作',
                    slot: 'handle',
                    align: 'center',
                    width: 140
                }
            ]
        }
    },

    methods: {
        onHandle(data) {
            let message = '请确认已核对并处理完相关信息'
            if (confirm(message) !== true) return false

            let form = { id: data.id }
            this.loading = true
            this.$post('/lotto/warning/handle', form, false).then(res => {
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
            this.$parent.$refs.create.form.model_id = data.id
            this.$parent.$refs.create.form.field = 'open_code'
        }
    }
}
</script>

<style lang="less" scoped>
.header-action {
    position: absolute;
    right: 0;
    top: 3px;
}
</style>
