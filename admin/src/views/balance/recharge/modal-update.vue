<template>
    <Drawer :footer-hide="true" @on-visible-change="getData" title="充值详情" v-model="visible" width="520">
        <lett-loading :visible="loading" />

        <Form :label-width="70" class="pt-20" label-position="right" v-if="form.id">
            <div class="alert-frame-base mb-20">
                您正在对
                <b class="color-red">{{form.user.nickname}} / {{form.user.real_name}}</b> 进行充值，请谨慎操作
                <br />当前用户余额：
                <b class="color-red">{{form.wallet.balance}}</b>
                <br />审核通过后，系统将自动给用户增加充值金额。
            </div>

            <FormItem label="充值金额" v-if="form.channel=='usdt'">
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

            <FormItem label="充值方式">
                <Input disabled v-model="$store.state.config.type[form.channel]" />
            </FormItem>

            <template v-if="form.extend">
                <FormItem label="充币地址" v-if="form.channel=='usdt'">
                    <Input disabled v-model="form.extend.usdt_address" />
                    <Icon @click="copy(form.extend.usdt_address)" class="pl-10 copy-finger" type="md-albums" />
                </FormItem>

                <FormItem label="备注码" v-else>
                    <Input disabled v-model="form.extend.code" />
                    <Icon @click="copy(form.extend.code)" class="pl-10 copy-finger" type="md-albums" />
                </FormItem>
            </template>

            <!-- <FormItem :label="trans[index]" v-for="(item,index) in form.extend">
                    <Input :value="item" disabled v-if="index!='qrcode'" />
                    <img :src="item" alt class="image" v-else />
                    <Icon @click="copy(item)" class="pl-10 copy-finger" type="md-albums" />
            </FormItem>-->

            <!-- <FormItem label="充值渠道">
                <Input disabled v-model="form.channel_info.bank_name + ' / ' + form.channel_info.card_no + ' / ' + form.channel_info.channel " />
            </FormItem>-->

            <FormItem label="审核状态">
                <Select :disabled="status != 1" @on-change="setRemark" v-model="form.status">
                    <Option :key="index" :value="index" v-for="(item,index) in this.$store.state.config.bank_status">{{ item.name }}</Option>
                </Select>
            </FormItem>

            <Divider />

            <template v-if="status == 1">
                <FormItem label="操作备注">
                    <Input :rows="4" placeholder="请输入操作备注" type="textarea" v-model="form.remark" />
                </FormItem>
                <!-- 
                <FormItem class="mb-40" label="操作密码">
                    <Input placeholder="请输入您的操作密码" type="password" v-model="form.safe_word" />
                </FormItem>-->

                <Button @click="confirm" class="mb-20" long type="primary">确认审核</Button>
            </template>

            <template v-else>
                <div class="alert-frame-base mb-20">
                    该充值订单已
                    <span class="color-red">{{this.$store.state.config.bank_status[status].name}}</span>，如需撤销操作，请谨慎操作。
                    <br />如超过1小时未撤销，将无法再撤销。
                </div>

                <FormItem label="撤销备注">
                    <Input :rows="4" placeholder="请输入撤销备注" type="textarea" v-model="form.cancel" />
                </FormItem>

                <!-- <FormItem class="mb-40" label="操作密码">
                    <Input placeholder="请输入您的操作密码" type="password" v-model="form.safe_word" />
                </FormItem>-->

                <Button @click="cancel" class="mb-20" long type="primary">确认撤销</Button>
            </template>
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
                2: '您的充值申请已审核通过，请注意查收',
                3: '未查询您的相关充值款项，如已转账请联系在线客服',
            },
            cancel_default: '人工操作失误，撤销申请重新审核',
            status: null,
            cancel_temp: '',
            remark_temp: '',
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

        disabled() {
            return this.config.data.status == '1' ? false : true
        },
        usdt_amount() {
            if (this.form.channel == 'usdt' && this.form.amount != '') {
                let money = this.form.amount / this.$store.state.config.usdt_rate
                console.log(money)
                return money.toFixed(2)
            } else {
                return null
            }
        },
    },

    methods: {
        confirm() {
            this.$post(this.config.api, this.form).then((res) => {
                res.data.status = res.data.status.toString()
                this.form = res.data
                this.status = res.data.status
                this.$emit('success', true, res.data, true)
            })
        },

        cancel() {
            this.$post('/recharge/cancel', this.form).then((res) => {
                res.data.status = res.data.status.toString()
                this.form = res.data
                this.status = res.data.status
                this.$emit('success', true, res.data, true)
            })
        },

        getData() {
            if (this.visible !== true) {
                this.form = {}
                this.status = null
                return true
            }
            var params = { id: this.config.data.id }
            this.$get('recharge/get', params).then((res) => {
                if (res.code !== 200) return false
                res.data.status = res.data.status.toString()
                this.form = res.data
                this.status = res.data.status
                if (res.data.status != '1') {
                    this.form.cancel = this.cancel_default
                }
            })
        },

        setRemark(value) {
            this.form.remark = this.remark_default[value]
        },
        setCancelRemark(value) {
            console.log(value)
            this.form.cancel = value
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
/deep/.ivu-form-item-content {
    display: flex;
    .copy-finger {
        cursor: pointer;
    }
}
</style>