<template>
    <Drawer @on-visible-change="onVisible" :footer-hide="true" title="资金操作" v-model="visible" width="840">
        <Form :label-width="70" class="relative pt-10" label-position="right" v-if="member.id">
            <lett-loading :visible="loading" @click="hiddenLoading"/>
            <div class="alert-frame-base mb-20">
                您正在对
                <b class="color-red">{{member.nickname}}</b> 进行资金操作，请谨慎操作</br>
                现金账户： <b class="color-red">{{member.wallet.balance}}</b> 银行账户： <b class="color-red">{{member.wallet.bank}}</b>
            </div>

        <FormItem label="操作账户">
                <Select  @on-change="setRemark" v-model="form.field">
                    <Option  value="bank">银行账户</Option>
                    <Option  value="balance">现金账户</Option>
                </Select>
            </FormItem>

            <FormItem label="资金操作">
                <Input placeholder="加钱请输入整数 扣钱请输入负数" v-model="form.amount" />
            </FormItem>

            <FormItem label="操作类型">
                <Select  @on-change="setRemark" v-model="form.source_name">
                    <Option  value="system.service">其它操作</Option>
                    <!-- <Option  value="bank.recharge.service">客服充值</Option> -->
                    <!-- <Option  value="red_bag.send.service">客服红包</Option> -->
                </Select>
            </FormItem>

            <FormItem label="操作备注">
                <Input :rows="4" placeholder="请输入操作备注" type="textarea" v-model="form.remark" />
            </FormItem>

            <!-- <FormItem class="mb-40" label="操作密码">
                <Input placeholder="请输入您的操作密码" type="password" v-model="form.check_password" />
            </FormItem> -->

            <Button @click="confirm" class="mb-20 mt-20" long type="primary">确认修改</Button>
        </Form>
    </Drawer>
</template>

<script>
export default {
    data() {
        return {
            visible: false,
            form: {},
            loading: false,
            is_update: false,
            remark_default: {
                'red_bag.send.service': '客服赠送红包',
                'balance.recharge.service': '您的充值已入账，请注意查收',
                'system': '系统操作'
            },
        }
    },

    computed: {
        member() {
            return this.$parent.data
        }
    },

    methods: {
        confirm() {
            this.form.id = this.member.id
            this.loading = true 
            this.$post('member/wallet/update', this.form, false).then(res => {
                this.loading = res.message
                if (res.code === 200) {
                    this.form = {}
                    this.is_update = true
                }
            })
        },

        onVisible(value) {
            if(value === true) {
                this.is_update = false
                this.form = {}
                this.loading = false 
            }

            if(value === false && this.is_update === true) {
                this.$parent.getData(true) 
            }
        },

        hiddenLoading() {
            this.loading = false
        },

        setRemark(value) {
            this.form.remark = this.remark_default[value]
        },
    }
}
</script>
