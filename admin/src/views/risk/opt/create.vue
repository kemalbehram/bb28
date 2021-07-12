<template>
    <Card dis-hover>
        <table-header-new class="mb-14" title="发送验证码"></table-header-new>
        <lett-loading :visible="loading" @click="loading = false" />
        <div class="display-flex mb-10">
            <Select class="mr-10" placeholder="请选择类型" v-model="form.type">
                <Option :key="item" :value="item" v-for="(label,item) in opt_name">{{ label }}</Option>
            </Select>

            <Input class="pr-10" placeholder="手机号" v-model="form.mobile">
                <span slot="prepend">手机号</span>
            </Input>
            <Input class="pr-10" placeholder="输入六位数的验证码，若不填，则发送随机验证码" v-model="form.opt_value">
                <span slot="prepend">验证码</span>
            </Input>
            <Button @click="onConfirm" type="primary">确认发送</Button>
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
                mobile: '',
                type: '',
                opt_value: ''
            }
        }
    },

    computed: {
        opt_name() {
            return this.$store.state.config.admin_opts
        }
    },
    methods: {
        onConfirm() {
            if (!this.form.type) return this.$Message.warning('请选择发送类型')
            if (!this.form.mobile) return this.$Message.warning('请输入用户电话号码')
            if (!/^1[3456789]\d{9}$/.test(this.form.mobile)) return this.$Message.warning('请输入正确的手机号')
            if (this.form.opt_value) {
                if (!/^\d{6}$/.test(this.form.opt_value)) return this.$Message.warning('请输入六位数字的验证码')
            }

            let message = '请确认要发送吗？'

            if (confirm(message) !== true) {
                return false
            }

            this.loading = true
            this.$post('admin-opt/create', this.form).then(res => {
                if (res.code !== 200) return (this.loading = false)
                this.form.type = ''
                this.form.mobile = ''
                this.form.opt_value = ''
                this.$parent.$refs.table.getIndex()
                this.loading = res.message
            })
        },
        setDate(date) {
            this.form.date = date
        }
    }
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