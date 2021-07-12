<template>
    <Card dis-hover>
        <table-header-new class="mb-14" title="用户充值"></table-header-new>
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
            <Input class="flex-1" placeholder="请输入充值金额" v-model="amount">
                <span slot="prepend">充值金额</span>
            </Input>
            <div class="tool-amount mr-10">
                <Button :key="item.value" @click="setAmount(item.value)" v-for="item in tool">{{item.label}}</Button>
            </div>
            <Button @click="onConfirm" type="primary">确认充值</Button>
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
            },
            tool: [
                { value: 1000, label: '1K' },
                { value: 2000, label: '2K' },
                { value: 5000, label: '5K' },
                { value: 10000, label: '10K' },
                { value: 20000, label: '20K' },
                { value: 50000, label: '50K' },
            ],
        }
    },
    methods: {
        onConfirm() {
            this.form.amount = this.amount
            if (!this.form.user_id) return this.$Message.warning('请输入充值用户ID')
            if (!this.form.nickname) return this.$Message.warning('请校验对方昵称')
            if (!this.form.amount) return this.$Message.warning('请输入转账金额')

            let message = '您确定要给<' + this.form.nickname + '>转账 ' + this.form.amount + ' 元吗？'

            if (confirm(message) !== true) {
                return false
            }

            this.loading = true
            this.$post('recharge/create', this.form).then((res) => {
                if (res.code !== 200) return (this.loading = false)
                this.form.amount = null
                this.amount = null
                this.form.user_id = ''
                this.form.nickname = ''
                this.$parent.$refs.table.getIndex()
                this.loading = res.message
            })
        },

        setAmount(item) {
            let temp = item
            if (this.amount != '' && this.amount !== null) {
                temp = parseFloat(this.amount) + item
            }
            this.amount = temp
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