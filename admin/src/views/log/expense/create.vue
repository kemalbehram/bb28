<template>
    <Card dis-hover>
        <table-header-new class="mb-14" title="添加开支"></table-header-new>
        <lett-loading :visible="loading" @click="loading = false" />
        <div class="display-flex mb-14">
            <Input @on-blur="checkUser" class="pr-10" placeholder="请输入用户ID" v-model="form.user_id">
                <span slot="prepend">用户ID</span>
            </Input>
            <Input disabled v-model="form.nickname">
                <span slot="prepend">用户昵称</span>
            </Input>
        </div>

        <div class="display-flex mb-10">
            <Input class="flex-1" placeholder="请输入开支金额" v-model="amount">
                <span slot="prepend">金额</span>
            </Input>
            <Input class="flex-1" placeholder="支出备注" v-model="form.remark">
                <span slot="prepend">备注</span>
            </Input>
            <Button @click="onConfirm" type="primary">确认</Button>
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
                nickname: '',
                user_id: '',
                amount: '',
                remark: '',
                admin_id: this.$store.state.user.userId,
            },
        }
    },
    methods: {
        onConfirm() {
            this.form.amount = this.amount
            if (!this.form.user_id) return this.$Message.warning('请输入用户ID')
            if (!this.form.nickname) return this.$Message.warning('请校验对方昵称')
            if (!this.form.amount) return this.$Message.warning('请输入开支金额')

            this.loading = true
            this.$post('data-stats/expenseCreate', this.form).then((res) => {
                if (res.code !== 200) return (this.loading = false)
                this.form.amount = null
                this.amount = null
                this.form.user_id = ''
                this.form.nickname = ''
                this.$parent.$refs.table.getIndex()
                this.loading = res.message
            })
        },

        checkUser() {
            if (!this.form.user_id) {
                return (this.form.nickname = null)
            }
            this.$post('recharge/check-user', this.form).then((res) => {
                if (res.code !== 200) return false
                this.form.nickname = res.data.nickname
            })
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
    .ivu-btn {
        padding: 0;
        margin-left: 10px;
        width: 54px;
    }
}
</style>