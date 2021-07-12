<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />
        <!-- {{table_columns}} -->
        <Table :columns="table_columns" :data="items" :height="300 | table_height" border>
            <template slot="win_extend" slot-scope="{ row }">
                <div class="table-win-extend" v-if="row.win_extend">
                    <span v-if="row.win_extend.code_str">{{row.win_extend.code_str}}</span>
                    <span v-if="row.win_extend.code_he">{{row.win_extend.code_he}}</span>
                    <span class="font-12" v-if="row.win_extend.code_bos">{{row.win_extend.code_bos}}</span>
                    <span class="font-12" v-if="row.win_extend.code_sod">{{row.win_extend.code_sod}}</span>
                </div>

                <Select @on-change="onLottoControl(row)" class="control-button" placeholder="指定和值" size="small" style="width:124px" v-else-if="$store.state.config.lotto_control.indexOf(lotto) != -1" v-model="row.control">
                    <Option value="cancel">未控制</Option>
                    <Option value="bet">投注分配</Option>
                    <Option value="he_00">和值-00</Option>
                    <Option :key="item" :value="'he_'+(Array(2).join('0') + item).slice(-2)" v-for="item in 27">和值-{{ (Array(2).join('0') + item).slice(-2)}}</Option>
                </Select>
            </template>

            <div class="table-win-extend" slot="win_ext_st" slot-scope="{ row }" v-if="row.win_ext_st">
                <span v-if="row.win_ext_st.code_str">{{row.win_ext_st.code_str}}</span>
                <span v-if="row.win_ext_st.code_he">{{row.win_ext_st.code_he}}</span>
            </div>
            <div class="table-win-extend" slot="lhb" slot-scope="{ row }" v-if="row.win_extend">
                <span v-if="row.win_extend.code_long">龙</span>
                <span v-if="row.win_extend.code_hu">虎</span>
                <span v-if="row.win_extend.code_bao">豹</span>
            </div>
            <div class="table-win-extend" slot="bds" slot-scope="{ row }" v-if="row.win_extend">
                <span v-if="row.win_extend.code_baozi">豹子</span>
                <span v-if="row.win_extend.code_dui">对子</span>
                <span v-if="row.win_extend.code_shun">顺子</span>
            </div>

            <div class="table-win-extend" slot="win_ext_el" slot-scope="{ row }" v-if="row.win_ext_el">
                <span v-if="row.win_ext_el.code_str">{{row.win_ext_el.code_str}}</span>
                <span v-if="row.win_ext_el.code_he">{{row.win_ext_el.code_he}}</span>
            </div>

            <div class="table-win-extend" slot="win_ext_gyh" slot-scope="{ row }" v-if="row.win_extend">
                <template v-if="row.win_extend.code_gyh">
                    <span>{{row.win_extend.code_gyh.gyh}}</span>
                    <span>{{row.win_extend.code_gyh.bos}}</span>
                    <span>{{row.win_extend.code_gyh.sod}}</span>
                </template>
            </div>

            <div class="bet-stats-wrap" slot="bet_stats" slot-scope="{ row }">
                <template v-if="row.bet_stats">
                    <span :class="colorItem(row.bet_stats.bet_people)" class="bet-stats-amount" v-html="row.bet_stats.bet_total" />
                    <span :class="colorItem(row.bet_stats.bet_people)" class="bet-stats-people" v-html="row.bet_stats.bet_people" />
                </template>
            </div>

            <div class="bet-stats-wrap" slot="win_stats" slot-scope="{ row }">
                <template v-if="row.bet_stats">
                    <span :class="colorItem(row.bet_stats.win_people)" class="bet-stats-amount" v-html="row.bet_stats.win_total" />
                    <span :class="colorItem(row.bet_stats.win_people)" class="bet-stats-people" v-html="row.bet_stats.win_people" />
                </template>
            </div>

            <template slot="action" slot-scope="{ row }">
                <Button @click="$store.dispatch('onLottoGet', [row.id, lotto])" size="small" type="primary">详情</Button>
            </template>
        </Table>
        <lett-page :page="page" @on-change="onPage" />
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],
    props: {
        lotto: null,
    },

    computed: {
        table_columns() {
            // let group = this.$store.state.config.lotto_items[this.lotto].group
            // console.log(this.lotto, group)

            let all_keno = [
                {
                    title: '期号',
                    width: 100,
                    key: 'short_id',
                    align: 'center',
                },
                {
                    title: '开奖时间',
                    key: 'lotto_at',
                    align: 'center',
                    width: 140,
                },
                {
                    title: '28开奖',
                    slot: 'win_extend',
                    align: 'center',
                    width: 240,
                },
                {
                    title: '龙/虎/豹',
                    slot: 'lhb',
                    align: 'center',
                    width: 100,
                },
                {
                    title: '豹子/对子/顺子',
                    slot: 'bds',
                    align: 'center',
                    width: 180,
                },
                {
                    title: '源号码',
                    key: 'open_code',
                    align: 'center',
                    tooltip: true,
                },
                {
                    title: '采集时间',
                    key: 'opened_at',
                    align: 'center',
                    width: 140,
                },
                {
                    title: '操作',
                    slot: 'action',
                    width: 140,
                    align: 'center',
                },
            ]

            let base_keno = [
                {
                    title: '期号',
                    width: 140,
                    key: 'short_id',
                    align: 'center',
                },
                {
                    title: '开奖时间',
                    key: 'lotto_at',
                    align: 'center',
                    width: 200,
                },
                {
                    title: '28开奖',
                    slot: 'win_extend',
                    align: 'center',
                    width: 300,
                },
                {
                    title: '源号码',
                    key: 'open_code',
                    align: 'center',
                    tooltip: true,
                },
                {
                    title: '采集时间',
                    key: 'opened_at',
                    align: 'center',
                    width: 200,
                },
                {
                    title: '操作',
                    slot: 'action',
                    width: 140,
                    align: 'center',
                },
            ]

            let base_pk10 = [
                {
                    title: '期号',
                    width: 140,
                    key: 'short_id',
                    align: 'center',
                },
                {
                    title: '开奖时间',
                    key: 'lotto_at',
                    align: 'center',
                    width: 200,
                },
                {
                    title: '冠亚和',
                    slot: 'win_ext_gyh',
                    align: 'center',
                    width: 300,
                },
                {
                    title: '源号码',
                    key: 'open_code',
                    align: 'center',
                    tooltip: true,
                },
                {
                    title: '采集时间',
                    key: 'opened_at',
                    align: 'center',
                    width: 200,
                },
                {
                    title: '操作',
                    slot: 'action',
                    width: 140,
                    align: 'center',
                },
            ]

            let base_table = [
                {
                    title: '期号',
                    width: 140,
                    key: 'short_id',
                    align: 'center',
                },
                {
                    title: '开奖时间',
                    key: 'lotto_at',
                    align: 'center',
                    width: 200,
                },
                {
                    title: '开奖',
                    slot: 'win_extend',
                    align: 'center',
                    width: 300,
                },
                {
                    title: '源号码',
                    key: 'open_code',
                    align: 'center',
                    tooltip: true,
                },
                {
                    title: '采集时间',
                    key: 'opened_at',
                    align: 'center',
                    width: 200,
                },
                {
                    title: '操作',
                    slot: 'action',
                    width: 140,
                    align: 'center',
                },
            ]

            let mapping = {
                ca28: all_keno,
                cw28: all_keno,
                de28: all_keno,
                bj28: all_keno,
                pc28: base_keno,
                bit28: base_keno,
                pk10: base_pk10,
            }

            if (mapping.hasOwnProperty(this.lotto)) {
                return mapping[this.lotto]
            } else {
                return base_table
            }
        },
    },

    methods: {
        getIndex(page = 1, params) {
            if (!this.lotto) {
                return false
            }
            const api = '/lotto/' + this.lotto + '/data/index'
            return this.$dataware.index(api, this, page, params)
        },

        updateData(open, data, success = false) {
            const fields = this.dataware.update.fields
            return this.$dataware.update(this, open, data, success, fields)
        },

        onLottoGet(row) {
            this.$store.dispatch('onLottoGet', [row.id, this.lotto])
        },

        colorItem(data) {
            if (data != '0') return 'color-green'
        },

        onLottoControl(row) {
            if (row.lotto_name !== this.lotto) return false
            if (!row.control) return false
            const form = { control: row.control, id: row.id }
            const api = '/lotto/' + this.lotto + '/data/control'
            this.$post(api, form)
        },
    },
}
</script>

<style lang="less" scoped>
/deep/ .bet-stats {
    &-wrap {
        width: 90px;
        padding-right: 10px;
        margin: 0 auto;
        position: relative;
        text-align: right;
    }
    &-people {
        text-align: center;
        background: @bg-light;
        width: 20px;
        display: inline-block;
        position: absolute;
        left: 0;
        border: 1px solid @line;
        border-radius: 100%;
        line-height: 18px;
        font-size: 13px;
    }
}

.table-win-extend {
    span {
        box-shadow: inset 14px -20px 26px -1px @red !important;
        color: @white;
        line-height: 22px;
        display: inline-block;
        border-radius: 2px;
        margin: 0 3px;
        padding: 0 4px;
        min-width: 24px;
        text-align: center;
        font-size: 13px;
    }
}

.control-button {
    text-align: left;

    /deep/ .ivu-select-selected-value {
        font-size: 13px;
    }

    /deep/ .ivu-select-item {
        font-size: 13px !important;
    }

    /deep/ .ivu-select-placeholder {
        font-size: 13px !important;
    }
}
</style>
