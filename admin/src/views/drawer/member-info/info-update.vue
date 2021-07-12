<template>
    <Drawer :footer-hide="true" @on-visible-change="onVisible" title="修改资料" v-model="visible" width="840">
        <Form :label-width="70" class="relative pt-10" label-position="right" v-if="form.id">
            <lett-loading :visible="loading" @click="hiddenLoading" />
            <img :src="form.avatar" class="avatar" />

            <FormItem class="item-padding" label="用户ID">
                <Input placeholder="请输入用户新ID" v-model="new_id">
                    <span slot="prepend">{{form.id}}</span>
                    <Button @click="updateId" slot="append">保存</Button>
                </Input>
            </FormItem>

            <FormItem class="item-padding" label="用户昵称">
                <Input v-model="form.nickname" />
            </FormItem>

            <FormItem label="联系信息">
                <div class="display-flex">
                    <Input class="pr-20" v-model="form.mobile">
                        <span slot="prepend">登陆手机</span>
                    </Input>
                    <Input v-model="form.contact_qq">
                        <span slot="prepend">联系QQ</span>
                    </Input>
                </div>
            </FormItem>

            <FormItem label="禁止使用">
                <i-switch v-model="form.disable" />
                <span class="form-desc-middle">开启禁用后，该用户将无法使用</span>
            </FormItem>

            <FormItem label="开启转账">
                <i-switch v-model="form.transfer" />
                <span class="form-desc-middle">关闭后将不可以用用转账功能</span>
            </FormItem>

            <FormItem label="开启投注">
                <i-switch v-model="form.bet" />
                <span class="form-desc-middle">关闭后将不可以投注</span>
            </FormItem>
            <FormItem label="禁止下分">
                <i-switch v-model="form.withdraw" />
                <span class="form-desc-middle">开启后用户将禁止下分</span>
            </FormItem>

            <FormItem label="密码设置">
                <div class="display-flex">
                    <Input class="pr-20" placeholder="输入密码后 将重置用户登录密码" v-model="form.password">
                        <span slot="prepend">登陆密码</span>
                    </Input>
                    <Input placeholder="输入密码后 将重置用户安全密码" v-model="form.safe_word">
                        <span slot="prepend">安全密码</span>
                    </Input>
                </div>
            </FormItem>

            <!-- <FormItem class="mb-20" label="浮动抽水">
                <Input placeholder="输入密码后 将重置用户安全密码" v-model="form.option.fd_tax" />
            </FormItem>-->

            <FormItem label="下分模式">
                <div class="display-flex">
                    <Select :disabled="!$store.state.config.transfer_model_setting" class="mr-20" v-model="form.option.transfer_fee_model">
                        <Option value="base">倍数模式</Option>
                        <Option value="bet">流水模式</Option>
                    </Select>

                    <Input v-model="form.option.bet_usable">
                        <span slot="prepend">流水值</span>
                    </Input>

                    <Input class="pl-20" v-if="form.option.transfer_fee_model === 'base'" v-model="form.option.fee_limit">
                        <span slot="prepend">最近充值</span>
                    </Input>
                </div>
            </FormItem>

            <FormItem label="投注限制">
                <Input v-model="form.option.bet_limit">
                    <span slot="prepend">最高下注(小于房间最高下注)</span>
                </Input>
            </FormItem>

            <FormItem>
                <Button @click="updateProfile" class="mb-20" long type="primary">确认修改</Button>
            </FormItem>

            <Divider class="mb-30">代理设置</Divider>

            <!-- <FormItem label="提成设置">
                <div class="display-flex">
                    <Input class="pr-10" v-model="form.agent.transfer_rate">
                        <span slot="prepend">基本提成</span>
                    </Input>

                    <Input class="pl-10" v-model="form.agent.transfer_refer">
                        <span slot="prepend">直属提成</span>
                    </Input>
                </div>
            </FormItem>-->

            <!-- <FormItem class="mb-14" label="其它设置">
                <div class="display-flex">
                    <Input class="pr-10" v-model="form.agent.advance">
                        <span slot="prepend">铺货分</span>
                    </Input>

                    <Input class="pl-10" v-model="form.agent.contact_qq">
                        <span slot="prepend">联系QQ</span>
                    </Input>
                </div>
            </FormItem>-->

            <FormItem class="mb-10" label="代理权限">
                <i-switch v-model="form.agent.status" />
                <span class="form-desc-middle">开启后，该用户将启用代理功能</span>
            </FormItem>

            <!-- <FormItem class="mb-14" label="合作商家">
                <i-switch v-model="form.agent.visible_admin" />
                <span class="form-desc-middle">开启禁用后，该用户将合作商家列表中展示</span>
            </FormItem>-->

            <FormItem>
                <Button @click="updateAgent" class="mb-20" long type="primary">确认修改</Button>
            </FormItem>
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
            visible: false,
            loading: false,
            is_update: false,
            new_id: null,
            form: {
                option: {
                    transfer_fee_model: null,
                },
            },
        }
    },

    methods: {
        updateProfile() {
            this.$post('member/update', this.form).then((res) => {
                if (res.code !== 200) return false
                this.is_update = true
                this.loading = res.message
            })
        },

        updateId() {
            if (confirm('确定要修改该用户ID吗？修改成功后，请刷新页面') !== true) return false
            let form = { id: this.form.id, new_id: this.new_id }
            console.log(form)
            this.$post('member/id-update', form).then((res) => {
                if (res.code !== 200) return false
                this.is_update = true
                this.loading = res.message
                this.$parent.data.id = res.data.id
                this.$store.state.drawer.member_info.id = res.data.id
            })
        },
        updateAgent() {
            this.$post('member/agent/update', this.form.agent).then((res) => {
                if (res.code !== 200) return false
                this.is_update = true
                this.loading = res.message
            })
        },

        onVisible(value) {
            if (value === true) {
                this.is_update = false
                this.form = {}
                this.loading = false
                this.form = JSON.parse(JSON.stringify(this.$parent.data))
            }

            if (value === false && this.is_update === true) {
                this.$parent.getData(true)
            }
        },

        hiddenLoading() {
            this.loading = false
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
    border: 1px solid @line;
    position: absolute;
    right: 0px;
    top: 12px;
}
</style>
