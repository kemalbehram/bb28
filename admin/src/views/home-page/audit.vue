<template>
    <div class="bg-grey">
        <lett-loading :visible="loading" />
        <p class="pt-6 pl-6">当日会员统计信息</p>
        <table class="table">
            <tr>
                <th>充值次数</th>
                <th>充值总计</th>
                <th>提现次数</th>
                <th>提现总计</th>
                <th>盈亏总计</th>
                <th>下注总计</th>
            </tr>
            <tr>
                <td>{{statis.recharge_count}}</td>
                <td>{{statis.recharge_amount}}</td>
                <td>{{statis.withdraw_count}}</td>
                <td>{{statis.withdraw_amount}}</td>
                <td>{{statis.profit_amount}}</td>
                <td>{{statis.bet_amount}}</td>
            </tr>
        </table>
        <p class="pt-6 pl-6">当日会员下注明细(按结算时间排序)</p>
        <table class="table">
            <tr>
                <th>期号</th>
                <th>彩种</th>
                <th>下注时间</th>
                <th>下注信息</th>
                <th>下注总额</th>
                <th>盈亏</th>
            </tr>
            <tr :key="index" v-for="(item,index) in items">
                <td>{{item.lotto_id}}</td>
                <td>{{item.lotto_title}}</td>
                <td>{{item.created_at}}</td>
                <td>
                    <span :key="bet_index" v-for="(bet_item,bet_index) in item.details">{{bet_item.name}}:{{bet_item.amount}}/</span>
                </td>
                <td>{{item.total}}</td>
                <td>{{item.profit}}</td>
            </tr>
        </table>
        <lett-page :page="page" @on-change="onPage" />
        <p class="pt-6 pl-6">申请信息</p>
        <table class="table">
            <tr>
                <th>用户</th>
                <th>提交时间</th>
                <th>申请金额</th>
                <th>申请类型</th>
                <th>当前余额</th>
                <th>支付方式</th>
            </tr>
            <tr>
                <td>
                    <table-user :data="audit_info.user" slot="nickname" />
                </td>
                <td>{{audit_info.created_at}}</td>
                <td>{{audit_info.amount}}</td>
                <td>
                    <span v-if="audit_info.type==0">充值</span>
                    <span v-else>提现</span>
                </td>
                <td>{{audit_info.user.total}}</td>
                <td>{{$store.state.config.type[audit_info.channel]}}</td>
            </tr>
        </table>

        <p class="pt-6 pl-6">审核处理</p>
        <div class="pd-20">
            <div>
                <span class="pr-20">支付方式</span>
                <RadioGroup v-model="audit_info.channel">
                    <Radio :label="index" border v-for="(item,index) in $store.state.config.type">{{item}}</Radio>
                </RadioGroup>
            </div>
            <div class="mt-20">
                <span class="pr-20">充值金额</span>
                <Input placeholder="输入充值金额" style="width: 500px" v-model="audit_info.amount" />
            </div>
            <div class="mt-20">
                <span class="pr-20">审核备注</span>
                <Input placeholder="输入备注信息" style="width: 500px" v-model="remark" />
            </div>
        </div>

        <div class="pt-10 pb-20">
            <div class="center-align">
                <Button @click="pass" class="mr-10" type="success">通过</Button>
                <Button @click="refuse" class="ml-10" type="warning">拒绝</Button>
            </div>
        </div>
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],
    props: {
        audit_info: {
            default: null,
            type: Object,
        },
    },
    watch: {},
    created() {
        this.getStatis()
    },
    methods: {
        getStatis() {
            this.$get('/home/user-statis', this.audit_info).then((res) => {
                this.statis = res.data
            })
        },

        getIndex(page = 1, params = {}) {
            params.user_id = this.audit_info.user_id
            const api = '/home/bet-log'
            return this.$dataware.index(api, this, page, params)
        },
        refuse() {
            let param = this.audit_info
            param.status = 3
            param.remark = this.remark
            let path = param.type == 0 ? '/recharge/update' : '/withdraw/update'
            this.loading = true
            this.$post(path, param).then((res) => {
                this.loading = false
                this.$emit('success')
            })
        },
        pass() {
            let param = this.audit_info
            param.status = 2
            param.remark = this.remark
            param.notice = true
            let path = param.type == 0 ? '/recharge/update' : '/withdraw/update'
            this.loading = true
            this.$post(path, param).then((res) => {
                this.loading = false
                this.$emit('success')
            })
        },
    },
    data() {
        return {
            remark: '',
            value: '',
            loading: false,
            dataware: {
                index: {
                    api: '/home/bet-log',
                },
            },
            statis: {
                recharge_count: 0,
                recharge_amount: 0,
                withdraw_count: 0,
                withdraw_amount: 0,
                profit_amount: 0,
                bet_amount: 0,
            },
        }
    },
}
</script>

<style lang="less" scoped>
.bg-grey {
    background: #f4f3fb;
}
.table {
    width: 100%;
    text-align: center;
    tr {
        height: 30px;
        line-height: 30px;
        th {
            background: #2b679c;
            color: @white;
        }
        td {
            background: @white;
        }
    }

    // td {
    //     text-align: center;
    // }
}
.pd-20 {
    padding: 20px;
}
.center-align {
    text-align: center;
}
</style>