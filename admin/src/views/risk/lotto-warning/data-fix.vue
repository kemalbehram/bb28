<template>
    <Card dis-hover>
        <table-header-new class="mb-14" title="数据修正"></table-header-new>
        <!-- {{form }} -->

        <div class="relative">
            <lett-loading :visible="loading" @click="loading = false" />
            <Form :label-width="0" class="mt-10" label-position="left">
                <FormItem class="mb-14">
                    <div class="display-flex">
                        <Select class="pr-10" placeholder="请选择修正游戏" v-model="form.model_name">
                            <Option :key="model" :value="model" v-for="(title, model) in $store.state.config.lotto_waning.title">{{ title }}</Option>
                        </Select>

                        <Input class="pr-10" placeholder="请输入对应期号" v-model="form.model_id ">
                            <span slot="prepend">对应期号</span>
                        </Input>

                        <Select placeholder="请选择修正类型" v-model="form.field">
                            <Option :key="item.name" :value="item.name" v-for="item in fix_name">{{ item.label }}</Option>
                        </Select>
                    </div>
                </FormItem>

                <FormItem class="mb-10">
                    <div class="display-flex">
                        <Input class="flex-1 mr-10 mt--1" placeholder="请输入修正值" v-model="form.value">
                            <span slot="prepend">修正值</span>
                        </Input>

                        <Button @click="onConfirm" type="primary">确认修正</Button>
                    </div>
                </FormItem>
            </Form>
        </div>
    </Card>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            amount: null,
            form: {
                model_name: '',
                model_id: '',
                value: '',
                field: '',
            },
            fix_name: [
                { name: 'lotto_at', label: '开奖时间' },
                { name: 'open_code', label: '开奖号码' },
            ],
        }
    },
    methods: {
        onConfirm() {
            this.form.amount = this.amount
            if (!this.form.model_name) return this.$Message.warning('请选择修正游戏')

            if (!this.form.model_id) return this.$Message.warning('请输入修正ID')

            if (!this.form.field) return this.$Message.warning('请选择修正类型')

            if (!this.form.value) return this.$Message.warning('输入修正值')

            let message = '请确认要修正吗？'

            if (confirm(message) !== true) {
                return false
            }

            this.loading = true
            this.$post('/lotto/warning/fix', this.form, false).then((res) => {
                if (res.code !== 200) return (this.loading = res.message)
                this.form.model_name = ''
                this.form.model_id = ''
                this.form.value = ''
                this.form.field = ''
                this.loading = res.message
            })
        },

        setDate(date) {
            this.form.date = date
        },
    },
}
</script>
<style lang="less" scoped>
.form-header {
    width: 500px;
    font-size: 16px;
    margin-bottom: 16px;
}

.tool-amount {
    // flex: 1;
    margin-top: -2px;
    .ivu-btn {
        padding: 0;
        margin-left: 10px;
        width: 54px;
    }
    // span:active {
    //     background: @bg;
    // }
}
</style>
