<template>
    <Card dis-hover>
        <table-header-new class="mb-14" title="统计修正"></table-header-new>
        <lett-loading :visible="loading" @click="loading = false" />
        <div class="display-flex mb-14">
            <DatePicker @on-change="setDate" class="width-100 mr-10" placeholder="请输入修正日期" type="date"></DatePicker>

            <Select class="mr-10" placeholder="请选择修正类型" v-model="form.name">
                <Option :key="item" :value="item" v-for="(label,item) in fix_name">{{ label }}</Option>
            </Select>

            <Input class="pr-10" placeholder="用户ID" v-model="form.user_id">
                <span slot="prepend">用户ID</span>
            </Input>
            <Input placeholder="修正金额" v-model="form.value">
                <span slot="prepend">修正金额</span>
            </Input>
        </div>
        <div class="display-flex mb-10">
            <Input class="flex-1 mr-10" placeholder="请输入修正原因" v-model="form.remark">
                <span slot="prepend">修正原因</span>
            </Input>

            <Button @click="onConfirm" type="primary">确认修正</Button>
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
                date: '',
                remark: '',
                value: '',
                name: '',
            },
        }
    },

    computed: {
        fix_name() {
            return this.$store.state.config.data_fix_label
        },
    },
    methods: {
        onConfirm() {
            this.form.amount = this.amount
            if (!this.form.date) return this.$Message.warning('请选择修正日期')
            if (!this.form.name) return this.$Message.warning('请选择修正类型')
            if (!this.form.value) return this.$Message.warning('请输入修正金额')
            if (!this.form.remark) return this.$Message.warning('输入修正原因')

            let message = '请确认要修正吗？'

            if (confirm(message) !== true) {
                return false
            }

            this.loading = true
            this.$post('stats-fix/create', this.form).then((res) => {
                if (res.code !== 200) return (this.loading = false)
                this.form.date = ''
                this.form.name = ''
                this.form.value = ''
                this.form.remark = ''
                this.$parent.$refs.table.getIndex()
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
