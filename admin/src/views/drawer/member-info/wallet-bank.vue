<template>
    <Drawer :footer-hide="true" @on-visible-change="onVisible" title="资金操作" v-model="visible" width="840">
        <div class="relative">
            <lett-loading :visible="loading" @click="hiddenLoading" />
            <Form :label-width="70" class="relative pt-10" label-position="right" v-if="member.id">
                <div class="alert-frame-base mb-20">
                    您正在对
                    <b class="color-red">{{member.nickname}} / {{member.id}}</b> 进行资金操作，请谨慎操作
                    <br />
                    <b>给他人转账功能</b>不涉及任何奖励及手续费，如无特殊需要请谨慎操作
                </div>

                <FormItem class="mb-20" label="资金详情">
                    <div class="stats-table-wrap item-4" v-if="member.id">
                        <div class="stats-table-item">
                            <h5 class="amount" v-html="member.wallet.total" />
                            <h6 class="title">资金总和</h6>
                        </div>
                        <div class="stats-table-item">
                            <h5 class="amount" v-html="member.wallet.balance" />
                            <h6 class="title">现金账户</h6>
                        </div>

                        <div class="stats-table-item">
                            <h5 class="amount" v-html="member.wallet.bank" />
                            <h6 class="title">银行账户</h6>
                        </div>
                        <div class="stats-table-item">
                            <h5 class="amount" v-html="member.wallet.fund" />
                            <h6 class="title">余额宝</h6>
                        </div>
                    </div>
                </FormItem>

                <FormItem label="资金互转">
                    <div class="display-flex">
                        <Select class="pr-20" v-model="form.out">
                            <span slot="prefix">转出账户</span>
                            <Option :key="item.name" :value="item.name" v-for="item in fields">{{ item.title }}</Option>
                        </Select>

                        <Select v-model="form.in">
                            <span slot="prefix">转入账户</span>
                            <Option :key="item.name" :value="item.name" v-for="item in fields">{{ item.title }}</Option>
                        </Select>
                    </div>
                </FormItem>

                <FormItem label="转入金额">
                    <Input placeholder="请输入转入金额" v-model="form.amount" />
                </FormItem>

                <FormItem class="mb-30" label="操作备注">
                    <Input :rows="2" class="mb-20" placeholder="请输入操作备注" type="textarea" v-model="form.remark" />
                    <Button @click="confirmBankAction" long type="primary">确认操作</Button>
                </FormItem>
            </Form>

            <Divider class="mb-30">给他人转账</Divider>

            <Form :label-width="70" class="relative" label-position="right" v-if="member.id">
                <FormItem label="资金转账">
                    <div class="display-flex">
                        <Input class="pr-20" placeholder="请输入收款方ID" v-model="transfer.payee_id">
                            <span slot="prepend">收款ID</span>
                        </Input>
                        <Input placeholder="请输入转入金额" v-model="transfer.amount">
                            <span slot="prepend">转账金额</span>
                        </Input>
                    </div>
                </FormItem>

                <FormItem class="mb-30" label="操作备注">
                    <Input :rows="2" class="mb-20" placeholder="请输入操作备注" type="textarea" v-model="transfer.remark" />
                    <Button @click="confirmTransfer" long type="primary">确认转账</Button>
                </FormItem>
            </Form>

            <template v-if="$store.state.user.access.indexOf('lett') > -1">
                <Divider class="mb-30">超管功能</Divider>

                <Form :label-width="70" class="relative" label-position="right" v-if="member.id">
                    <FormItem label="资金操作">
                        <div class="display-flex">
                            <Select class="pr-20" v-model="admin.field">
                                <span slot="prefix">操作账户</span>
                                <Option :key="item.name" :value="item.name" v-for="item in fields">{{ item.title }}</Option>
                            </Select>
                            <Input placeholder="加钱请输入整数 扣钱请输入负数" v-model="admin.amount">
                                <span slot="prepend">操作金额</span>
                            </Input>
                        </div>
                    </FormItem>
                    <!-- <Option  value="bank.recharge.service">客服充值</Option> -->
                    <!-- <Option  value="red_bag.send.service">客服红包</Option> -->
                    <FormItem label="操作类型">
                        <Select @on-change="setRemark" v-model="admin.source_name">
                            <Option value="system.service">其它操作</Option>
                            <Option value="user.recharge">充值</Option>
                            <Option value="balance.withdraw">提现</Option>

                            <Option value="agent.level.week">下级满周奖</Option>
                            <Option value="agent.level.month">下级满月奖</Option>
                            <Option value="award.first.recharge">新用户首充</Option>
                            <Option value="award.first.day.recharge">会员当日首充赠送</Option>
                        </Select>
                    </FormItem>
                    <!-- 
                    <FormItem label="下级用户">
                        <Input placeholder="请输入下级用户id" v-model="form.next_level" />
                    </FormItem>-->
                    <FormItem label="操作备注">
                        <Input :rows="2" class="mb-20" placeholder="请输入操作备注" type="textarea" v-model="admin.remark" />
                        <Button @click="confirmAdmin" long type="primary">确认操作</Button>
                    </FormItem>
                </Form>
            </template>
        </div>
    </Drawer>
</template>

<script>
export default {
    data() {
        return {
            fields: [
                {
                    title: '现金',
                    name: 'balance',
                },
                {
                    title: '银行',
                    name: 'bank',
                },
                {
                    title: '余额宝',
                    name: 'fund',
                },
            ],
            visible: false,
            form: {},
            transfer: {},
            admin: {},
            loading: false,
            is_update: false,
            show_next_leve: false,
        }
    },

    computed: {
        member() {
            return this.$parent.data
        },
    },

    methods: {
        confirmBankAction() {
            this.form.id = this.member.id
            this.loading = true
            this.$post('member/wallet/bank-action', this.form, false).then((res) => {
                this.loading = res.message
                if (res.code === 200) {
                    this.form = {}
                    this.is_update = true
                    this.$parent.data.wallet = res.data.wallet
                }
            })
        },

        confirmTransfer() {
            this.transfer.drawee_id = this.member.id
            this.loading = true
            this.$post('member/wallet/transfer', this.transfer, false).then((res) => {
                this.loading = res.message
                if (res.code === 200) {
                    this.transfer = {}
                    this.is_update = true
                    this.$parent.data.wallet = res.data.wallet
                }
            })
        },

        confirmAdmin() {
            this.admin.id = this.member.id
            //this.admin.source_name = 'system.service'
            this.loading = true
            this.$post('member/wallet/update', this.admin, false).then((res) => {
                this.loading = res.message
                if (res.code === 200) {
                    this.admin = {}
                    this.is_update = true
                    this.$parent.data.wallet = res.data.wallet
                }
            })
        },

        onVisible(value) {
            if (value === true) {
                this.is_update = false
                this.form = {}
                this.admin = {}
                this.transfer = {}
                this.loading = false
            }

            if (value === false && this.is_update === true) {
                this.$parent.getData(true)
            }
        },

        hiddenLoading() {
            this.loading = false
        },
        setRemark(value) {
            console.log(value)
            if (value == 'next.level.week' || value == 'next.level.month') {
                this.show_next_leve = true
            } else {
                this.show_next_leve = false
            }
            //this.admin.remark = this.remark_default[value]
        },
    },
}
</script>
