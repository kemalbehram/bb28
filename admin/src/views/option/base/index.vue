<template>
    <grid-form :gutter="20" left="12" right="12">
        <template slot="left">
            <Card dis-hover title="基本设置">
                <Button @click="updateData" size="small" slot="extra" type="primary">保存设置</Button>
                <lett-loading :visible="loading" @click="getData" />

                <Form :label-width="100" class="pt-10" label-position="left">
                    <FormItem label="网站标题后缀">
                        <Input type="text" v-model="items.web_title"></Input>
                    </FormItem>

                    <FormItem label="PC顶部LOGO">
                        <Input type="text" v-model="items.logo_web_header"></Input>
                    </FormItem>

                    <FormItem label="PC底部LOGO">
                        <Input type="text" v-model="items.logo_web_footer"></Input>
                    </FormItem>

                    <FormItem label="PC头部广告">
                        <Input type="text" v-model="items.banner_web_1"></Input>
                    </FormItem>

                    <FormItem label="PC文章广告">
                        <Input type="text" v-model="items.banner_web_2"></Input>
                    </FormItem>

                    <FormItem label="PC底部版权">
                        <Input :rows="2" type="textarea" v-model="items.web_copyright"></Input>
                    </FormItem>

                    <FormItem label="首页热门活动">
                        <Input class="mb-10" placeholder="首页热门活动图片" type="text" v-model="items.web_home_promotion_image"></Input>
                        <Input class="mb-10" placeholder="首页热门活动标题" type="text" v-model="items.web_home_promotion_title"></Input>
                        <Input placeholder="首页热门活动时间" type="text" v-model="items.web_home_promotion_time"></Input>
                    </FormItem>

                    <FormItem label="PC底部法律">
                        <Input type="text" v-model="items.web_lawyer"></Input>
                    </FormItem>

                    <FormItem label="单IP登陆限制">
                        <Input placeholder="每个IP最多可登陆用户，填0为不限制" type="text" v-model="items.ip_limit"></Input>
                    </FormItem>

                    <FormItem label="限制IP白名单">
                        <Input :rows="6" placeholder="请输入IP白名单，多个IP用小写逗号分隔" type="textarea" v-model="items.ip_white"></Input>
                    </FormItem>

                    <FormItem label="客服QQ两个">
                        <Input :rows="2" type="textarea" v-model="items.service_qq"></Input>
                    </FormItem>

                    <FormItem label="网站备用域名">
                        <Input :rows="4" type="textarea" v-model="items.domain_backup"></Input>
                    </FormItem>

                    <FormItem label="转账手续费">
                        <Input type="text" v-model="items.transfer_fee_rate"></Input>
                    </FormItem>

                    <FormItem label="用户笔笔返">
                        <Input type="text" v-model="items.transfer_award_user_base"></Input>
                    </FormItem>

                    <FormItem label="用户首冲奖励">
                        <Input type="text" v-model="items.transfer_award_user_first"></Input>
                    </FormItem>

                    <FormItem label="下线流水日返">
                        <Input type="text" v-model="items.ref_1_bet_rebate"></Input>
                    </FormItem>

                    <FormItem label="余额宝日利率">
                        <Input type="text" v-model="items.wallet_fund_rate"></Input>
                    </FormItem>

                    <FormItem label="默认用户查询">
                        <Input type="text" v-model="items.user_query_default"></Input>
                    </FormItem>

                    <FormItem label="下注报警下限">
                        <Input type="text" v-model="items.bet_warning_total"></Input>
                    </FormItem>

                    <FormItem label="中奖报警下限">
                        <Input type="text" v-model="items.bet_warning_bonus"></Input>
                    </FormItem>

                    <FormItem label="万能验证码">
                        <Input type="text" v-model="items.sms_un_check_code"></Input>
                    </FormItem>
                    <FormItem label="充值笔笔送">
                        <Input type="text" v-model="items.recharge_back"></Input>
                    </FormItem>
                    <FormItem label="USDT汇率">
                        <Input type="text" v-model="items.usdt_rate"></Input>
                    </FormItem>
                    <FormItem label="支付宝充值">
                        <i-switch v-model="items.alipay" />
                    </FormItem>
                    <FormItem label="微信充值">
                        <i-switch v-model="items.wechat" />
                    </FormItem>
                    <FormItem label="银联充值">
                        <i-switch v-model="items.bank" />
                    </FormItem>
                    <FormItem label="USDT充值">
                        <i-switch v-model="items.usdt" />
                    </FormItem>
                </Form>
            </Card>
        </template>

        <template slot="right">
            <Card dis-hover title="其它设置">
                <Button @click="updateData" size="small" slot="extra" type="primary">保存设置</Button>
                <lett-loading :visible="loading" @click="getData" />

                <Form :label-width="100" class="pt-10" label-position="left">
                    <FormItem label="封盘前10秒提示">
                        <Input :rows="12" type="textarea" v-model="items.second_notice"></Input>
                    </FormItem>
                    <FormItem label="封盘提示">
                        <Input :rows="12" type="textarea" v-model="items.close_notice"></Input>
                    </FormItem>
                    <FormItem label="开盘提示">
                        <Input :rows="12" type="textarea" v-model="items.open_notice"></Input>
                    </FormItem>
                    <FormItem label="广告1">
                        <Input :rows="12" type="textarea" v-model="items.advertising_1"></Input>
                    </FormItem>
                    <FormItem label="广告2">
                        <Input :rows="12" type="textarea" v-model="items.advertising_2"></Input>
                    </FormItem>

                    <FormItem label="银行中心简介">
                        <Input :rows="12" type="textarea" v-model="items.bank_intro"></Input>
                    </FormItem>

                    <FormItem label="下线推广简介">
                        <Input :rows="14" type="textarea" v-model="items.refer_intro"></Input>
                    </FormItem>

                    <FormItem label="聊天下注教程">
                        <Input :rows="14" type="textarea" v-model="items.bet_chat_tutorial"></Input>
                    </FormItem>

                    <FormItem label="用户转账简介">
                        <Input :rows="8" type="textarea" v-model="items.transfer_intro_user"></Input>
                    </FormItem>

                    <FormItem label="三方统计代码">
                        <Input :rows="4" type="textarea" v-model="items.web_stats_code"></Input>
                    </FormItem>
                </Form>
            </Card>
        </template>
    </grid-form>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            items: {},
        }
    },

    created() {
        this.getData()
    },

    methods: {
        updateData() {
            this.$post('/option/update/patch', this.items)
        },

        getData() {
            this.$get('/option/get').then((res) => {
                this.items = res.data
                this.items.alipay = this.items.alipay == 1 ? true : false
                this.items.bank = this.items.bank == 1 ? true : false
                this.items.wechat = this.items.wechat == 1 ? true : false
                this.items.usdt = this.items.usdt == 1 ? true : false
                // console.log(this.items)
            })
        },
    },
}
</script>
