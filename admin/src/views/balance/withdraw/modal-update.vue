<template>
    <Drawer :footer-hide="true" @on-visible-change="getData" title="提现详情" v-model="visible" width="520">
        <lett-loading :visible="loading" />

        <Form :label-width="70" class="pt-20" label-position="right" v-if="form.id">
            <div class="alert-frame-base mb-20">
                <Button @click="onCheckIP" class="custom-button" size="small" type="primary">IP检查</Button>您正在对
                <b class="color-red">{{form.user.nickname}} / {{form.user.real_name}}</b> 进行提现操作；
                <br />请仔细核对用户提现信息，以免操作失误
            </div>

            <FormItem label="充值金额" v-if="form.extend && form.extend.type=='usdt'">
                <div class="display-flex">
                    <Input class="pr-20" v-model="form.amount">
                        <span slot="prepend">人民币</span>
                    </Input>
                    <Input v-model="usdt_amount">
                        <span slot="prepend">USDT</span>
                    </Input>
                </div>
            </FormItem>

            <FormItem label="充值金额" v-else>
                <Input v-model="form.amount" />
            </FormItem>

            <div v-if="form.extend">
                <Divider>详细信息</Divider>
                <FormItem :label="trans[index]" v-for="(item,index) in form.extend">
                    <img :src="item" alt class="image" v-if="index=='qrcode'" />
                    <Input :value="$store.state.config.type[item]" disabled v-else-if="index=='type'" />
                    <Input :value="item" disabled v-else />
                    <Icon @click="copy(item)" class="pl-10 copy-finger" type="md-albums" />
                </FormItem>
                <Divider>详细信息</Divider>
            </div>

            <FormItem label="审核状态">
                <Select @on-change="setRemark" v-model="form.status">
                    <Option :key="index" :value="index" v-for="(item,index) in this.$store.state.config.withdraw_status">{{ item }}</Option>
                </Select>
            </FormItem>

            <FormItem label="操作备注">
                <Input :rows="4" placeholder="请输入操作备注" type="textarea" v-model="form.remark" />
            </FormItem>

            <!-- <FormItem class="mb-40" label="操作密码">
                <Input placeholder="请输入您的操作密码" type="password" v-model="form.safe_word" />
            </FormItem>-->

            <Button @click="confirm" class="mb-20" long type="primary">确认审核</Button>
        </Form>
    </Drawer>
</template>

<script>
export default {
    props: {
        config: {},
    },

    data() {
        return {
            loading: false,
            form: {},

            remark_default: {
                1: '',
                2: '您的提现申请已审核通过，请注意查收',
                3: '您的提现申请未通过',
            },
            trans: {
                type: '提现类型',
                address: '地址',
                username: '名称',
                qrcode: '二维码',
                account: '账号',
                bank_card: '银行卡号',
                bank_name: '银行名称',
            },
        }
    },

    computed: {
        visible: {
            get() {
                return this.config.visible
            },
            set() {
                this.config.visible = false
                this.config.data = {}
            },
        },
        usdt_amount() {
            if (this.form.extend.type == 'usdt' && this.form.amount != '') {
                let money = this.form.amount / this.$store.state.config.usdt_rate
                // console.log(money)
                return money.toFixed(2)
            } else {
                return null
            }
        },

        disabled() {
            return this.config.data.status == '1' ? false : true
        },
    },

    methods: {
        confirm() {
            this.$post(this.config.api, this.form).then((res) => {
                this.$emit('success', false, res.data, true)
            })
        },

        getData() {
            if (this.visible !== true) return (this.form = {})
            var params = { id: this.config.data.id }
            this.$get('withdraw/get', params).then((res) => {
                if (res.code !== 200) return false
                res.data.status = res.data.status.toString()
                this.form = res.data
            })
        },

        setRemark(value) {
            this.form.remark = this.remark_default[value]
        },

        onCheckIP() {
            var params = { user_id: this.form.user_id }
            this.$get('member/check-ip', params).then((res) => {
                if (res.code !== 200) return false
                alert(res.message)
            })
        },
        copy(content) {
            var tag = document.createElement('input')
            tag.setAttribute('id', 'cp_hgz_input')
            tag.value = content
            document.getElementsByTagName('body')[0].appendChild(tag)
            document.getElementById('cp_hgz_input').select()
            document.execCommand('copy')
            document.getElementById('cp_hgz_input').remove()
            this.$Message.success('复制成功')
        },
    },
}
</script>

<style lang="less" scoped>
.cell-content {
    line-height: 22px;
    padding: 6px 0;
}

.item-padding {
    padding-right: 106px;
}

.avatar {
    display: block;
    width: 86px;
    margin: 8px 0;
    border: 1px solid @line;
    position: absolute;
    right: 16px;
    top: 29px;
}

.alert-frame-base {
    position: relative;

    .custom-button {
        position: absolute;
        right: 12px;
    }
}
.image {
    width: 100px;
    height: 100px;
}
/deep/.ivu-form-item-content {
    display: flex;
    .copy-finger {
        cursor: pointer;
    }
}
</style>