<template>
    <div class="relative member-info-wrap">
        <lett-loading :visible="loading" @click="loading = false" />

        <div class="drawer-common-wrap mb-20" v-if="data.id">
            <lett-cell-item title="用户ID">
                <span class="mr-6">{{data.id}}</span>
                <Tag class="mb-0" color="red" v-if="data.agent.status">代理</Tag>
                <Tag class="mb-0" color="red" v-if="data.disable">禁登</Tag>
                <Tag class="mb-0" color="volcano" v-if="data.trial">试玩</Tag>
            </lett-cell-item>
            <lett-cell-item title="用户昵称">{{data.nickname}} / {{data.real_name || '暂未实名'}}</lett-cell-item>
            <lett-cell-item title="银行卡">{{data.bank_card ? data.bank_card.real_name+' / 卡号：'+data.bank_card.card :'未绑定'}}</lett-cell-item>
            <lett-cell-item :value="data.mobile" title="登录手机" />
            <lett-cell-item :value="data.contact_qq" title="联系QQ" />
            <lett-cell-item title="最后登录">
                {{data.requested_at}} /
                <a :href="'http://www.ip.cn?ip=' + data.requested_ip" target="_blank">{{data.requested_ip}}</a>
            </lett-cell-item>
            <lett-cell-item title="注册时间">
                {{data.created_at}} /
                <a :href="'http://www.ip.cn?ip=' + data.created_ip" target="_blank">{{data.created_ip}}</a>
            </lett-cell-item>
            <lett-cell-item title="上级用户">
                <template v-if="data.ref_info">{{data.ref_info.nickname}} / {{data.ref_info.ref_id}}</template>
                <template v-else>暂无上级</template>
            </lett-cell-item>
            <lett-cell-item title="最后转入">
                <template v-if="data.transfer_in_last">{{data.transfer_in_last.nickname}} / {{data.transfer_in_last.id}}</template>
                <template v-else>暂无最后转入记录</template>
            </lett-cell-item>
            <lett-cell-item title="最后转出">
                <template v-if="data.transfer_out_last">{{data.transfer_out_last.nickname}} / {{data.transfer_out_last.id}}</template>
                <template v-else>暂无最后转出记录</template>
            </lett-cell-item>
            <Avatar :size="40" :src="data.avatar" class="drawer-common-avatar" />

            <div class="drawer-common-button">
                <Button @click="startServiceChat" class="mr-10" size="small">立即对话</Button>
                <Button @click="showComponents('infoUpdate')" size="small">修改资料</Button>
            </div>
        </div>

        <Row :gutter="10" class="mt-20" style="width:calc(100% - 354px)" v-if="data.id">
            <Col span="4">
                <Button @click="showComponents('walletBank')" class="button-reset" long type="primary">资金操作</Button>
            </Col>
            <Col span="4">
                <Button @click="showComponents('TransferLog')" class="button-reset" long type="primary">转账记录</Button>
            </Col>
            <Col span="4">
                <Button @click="showComponents('walletLog')" class="button-reset" long type="primary">资金记录</Button>
            </Col>
            <Col span="4">
                <Button @click="showComponents('betLog')" class="button-reset" long type="primary">投注记录</Button>
            </Col>
            <Col span="4">
                <Button @click="showComponents('nextLevel')" class="button-reset" long type="primary">下级用户</Button>
            </Col>
            <Col span="4">
                <Button @click="onCheckIP" class="button-reset" long type="primary">IP检查</Button>
            </Col>
        </Row>

        <div class="stats-table-wrap mb-20 mt-10 item-6" v-if="data.id">
            <div class="stats-table-item">
                <h5 class="amount" v-html="wallet.total" />
                <h6 class="title">资金总和</h6>
            </div>
            <div class="stats-table-item">
                <h5 class="amount" v-html="wallet.balance" />
                <h6 class="title">现金账户</h6>
            </div>

            <div class="stats-table-item">
                <h5 class="amount" v-html="wallet.bank" />
                <h6 class="title">银行账户</h6>
            </div>
            <div class="stats-table-item">
                <h5 class="amount" v-html="wallet.fund" />
                <h6 class="title">余额宝</h6>
            </div>
        </div>

        <data-stats v-if="data.id" />
        <fee-limit :data="data.fee_limit" :wallet="wallet" class="mt-20 mb-20" v-if="data.fee_limit" />

        <div class="stats-table-wrap item-6" v-if="data.user_level">
            <div class="stats-table-item">
                <h5 class="amount" v-html="data.user_level.level_title" />
                <h6 class="title">用户当前等级</h6>
            </div>

            <div class="stats-table-item">
                <h5 class="amount" v-html="data.user_level.level_exp" />
                <h6 class="title">当前等级经验</h6>
            </div>

            <div class="stats-table-item">
                <h5 class="amount" v-html="data.user_level.exp_diff" />
                <h6 class="title">下级所需经验</h6>
            </div>
        </div>
    </div>
</template>

<script>
import feeLimit from './fee-limit'
import dataStats from './data-stats'
export default {
    props: {
        params: Object,
        parent: Object,
        top: Object,
    },

    components: {
        feeLimit,
        dataStats,
    },

    data() {
        return {
            loading: false,
            data: {
                agent: {
                    status: false,
                },

                option: {
                    transfer_fee_model: null,
                },
            },
            stats: {},
            logs: [],
            stats_value: 'today',
        }
    },

    created() {
        if (this.params.id) {
            this.getData(true)
        }
    },

    computed: {
        wallet() {
            return this.data.hasOwnProperty('wallet') ? this.data.wallet : {}
        },
    },

    methods: {
        getData(visible) {
            if (visible === null) return false
            if (visible === true) {
                var params = { id: this.params.id }
                this.loading = true
                this.$get('/member/get', params).then((res) => {
                    if (res.code !== 200) {
                        return (this.loading = res.message)
                    }
                    this.loading = false
                    this.data = res.data
                    this.parent.$refs.infoUpdate.is_update = false

                    try {
                        this.top.setQueryLog(res.data.id)
                    } catch (error) {}
                })
            } else {
                this.data = {}
            }
        },

        onCheckIP() {
            var params = { user_id: this.data.id }
            this.$get('member/check-ip', params).then((res) => {
                if (res.code !== 200) return false
                alert(res.message)
            })
        },

        showComponents(ref) {
            this.parent.$refs[ref].visible = true
        },

        startServiceChat() {
            this.$store.dispatch('startServiceChat', this.data)
        },
    },
}
</script>

<style lang="less" scoped>
.member-info-wrap {
    min-height: 600px;
}

.drawer-common-avatar {
    position: absolute;
    right: 24px;
    top: 12px;
}

.drawer-common-button {
    position: absolute;
    right: 12px;
    bottom: 12px;
}

.button-reset {
    margin: 0 0 10px 0;
    padding: 0;
}
</style>