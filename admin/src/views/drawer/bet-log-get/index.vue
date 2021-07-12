<template>
    <Drawer :footer-hide="true" :title="title" @on-visible-change="getData" v-model="params.visible" width="720">
        <lett-loading :visible="loading" />

        <div class="drawer-common-wrap">
            <lett-cell-item title="彩票期号">{{data.lotto_name | lotto_name}}-{{data.play_type | play_type}} · {{data.lotto_id}}</lett-cell-item>
            <lett-cell-item title="开奖状态">
                <span class="color-red">{{data.status | bet_status}}</span>
                <span class="ml-6 mr-6">/</span>
                <span class="color-sub" v-if="data.effective=== 1">有效流水</span>
                <span class="color-sub" v-if="data.effective=== 2">无效流水</span>
                <template v-if="data.auto_id">
                    <span class="ml-6 mr-6">/</span>
                    <span class="color-sub">自动投注 {{data.auto_id }} / {{data.tool_id}}</span>
                </template>
            </lett-cell-item>

            <lett-cell-item :value="data.user.nickname" title="投注用户" />
            <lett-cell-item title="开奖时间">
                {{data.lotto_at}}
                <template v-if="data.lotto">
                    <span class="ml-6 mr-6 color-sub">/</span>
                    <span class="color-sub">{{data.lotto.opened_at}}</span>
                </template>
            </lett-cell-item>
            <lett-cell-item title="下注时间">
                {{data.created_at}}
                <template v-if="data.bet_time_diff_a">
                    <span class="ml-6 mr-6 color-sub">/</span>
                    <span class="color-sub">{{data.bet_time_diff_a}} · {{data.bet_time_diff_b}}</span>
                </template>
            </lett-cell-item>
            <lett-cell-item :value="data.confirmed_at" title="派奖时间" />

            <component :is="win_extend_template" :lotto="data.lotto" v-if="data.lotto && data.lotto.win_extend"></component>

            <Button @click="onCancelBet" class="drawer-common-button" size="small">取消投注</Button>
        </div>

        <div class="stats-table-wrap mt-20 mb-20">
            <div class="stats-table-item" span="6">
                <div class="amount" v-html="data.total || '0.00'" />
                <div class="title">投注总额</div>
            </div>

            <div class="stats-table-item" span="6">
                <div class="amount" v-html="data.bonus || '0.00'" />
                <div class="title">返奖总额</div>
            </div>
            <div class="stats-table-item" span="6">
                <div class="amount" v-html="data.profit || '0.00'" />
                <div class="title">投注盈亏</div>
            </div>
            <div class="stats-table-item" span="6">
                <div class="amount" v-html="data.bet_details.length || '0'" v-if="data.lotto" />
                <div class="title">投注数量</div>
            </div>
        </div>

        <Table :columns="columns" :data="data.bet_details" :height="400 | table_height" :row-class-name="rowClass" border size="small"></Table>
    </Drawer>
</template>

<script>
import baseTemplate from './win-extend/base-template'
import kenoSt from './win-extend/keno-st'
import kenoEl from './win-extend/keno-el'
import kenoH1 from './win-extend/keno-h1'
import kenoH2 from './win-extend/keno-h2'
import kenoH3 from './win-extend/keno-h3'
import racing from './win-extend/racing'
export default {
    components: {
        baseTemplate,
        kenoSt,
        kenoEl,
        kenoH1,
        kenoH2,
        kenoH3,
        racing,
    },
    data() {
        return {
            params: this.$store.state.drawer.bet_log,
            columns: [
                {
                    title: '下注',
                    key: 'title',
                    align: 'left',
                    className: 'color-black',
                },

                {
                    title: '金额',
                    key: 'amount',
                    align: 'center',
                },

                {
                    title: '中奖',
                    key: 'bonus',
                    align: 'center',
                },

                {
                    title: '结算赔率',
                    key: 'odds_settle',
                    align: 'center',
                },
                {
                    title: '标准赔率',
                    key: 'odds',
                    align: 'center',
                    className: 'color-black',
                },
            ],
            loading: false,
            data: {
                user: {},
            },
            stats: {},
            logs: [],
        }
    },

    computed: {
        title() {
            let model = {
                group: '单期模式',
                detail: '明细模式',
            }
            return '下注详情 - ' + model[this.params.model]
        },

        win_extend_template() {
            let racing = ['gyh', 'dwd', 'smp', 'ptt', 'ptn', 'cha']
            if (this.data.play_type === 'st') return 'kenoSt'
            if (this.data.play_type === 'el') return 'kenoEl'
            if (this.data.play_type === 'h1') return 'kenoH1'
            if (this.data.play_type === 'h2') return 'kenoH2'
            if (this.data.play_type === 'h3') return 'kenoH3'
            if (racing.indexOf(this.data.play_type) !== -1) return 'racing'
            return 'baseTemplate'
        },
    },

    methods: {
        getData(visible) {
            if (visible === true) {
                const api = '/lotto/bet-log/get'
                var params = {
                    id: this.params.id,
                    model: this.params.model,
                }
                this.loading = true
                this.$get(api, params).then((res) => {
                    if (res.code !== 200) {
                        return (this.loading = res.message)
                    }
                    this.loading = false
                    this.data = res.data
                })
            } else {
                this.data = {
                    user: {},
                }
            }
        },

        onCancelBet() {
            if (confirm('确定要取消投注嘛') === false) return false

            var params = { id: this.data.id }
            this.$post('/lotto/bet-log/cancel', params).then((res) => {
                if (res.code !== 200) return false
            })
        },

        rowClass(row, index) {
            return row.bonus > 0 ? 'color-red' : null
        },
    },
}
</script>

<style lang="less" scoped>
.drawer-common-button {
    position: absolute;
    right: 12px;
    bottom: 12px;
}
</style>