<template>
    <Drawer :footer-hide="true" @on-visible-change="getData" title="彩票详情" v-model="params.visible" width="860">
        <lett-loading :visible="loading" />

        <div class="drawer-common-wrap">
            <lett-cell-item title="彩票期号">{{data.lotto_name | lotto_name}} · {{data.short_id}}</lett-cell-item>
            <lett-cell-item title="开奖状态">
                <span :class="'status-' + data.status">{{data.status | lotto_status}}</span>
            </lett-cell-item>
            <lett-cell-item :value="data.open_code || 'Null'" title="开奖号码" />
            <lett-cell-item :value="data.lotto_at || 'Null'" title="开奖时间" />
            <lett-cell-item :value="data.opened_at || 'Null'" title="采集时间" />

            <div class="drawer-common-code" v-if="data.win_extend">
                <span v-if="data.win_extend.code_str">{{data.win_extend.code_str || 'NA'}}</span>
                <span v-if="data.win_extend.code_he">{{data.win_extend.code_he}}</span>
                <span class="font-16" v-if="data.win_extend.code_bos">{{data.win_extend.code_bos}}</span>
                <span class="font-16" v-if="data.win_extend.code_sod">{{data.win_extend.code_sod}}</span>
                <!-- <span v-if="data.win_extend.code_gyh">{{data.win_extend.code_gyh}}</span> -->

                <template v-if="data.win_extend.code_gyh">
                    <span>{{data.win_extend.code_gyh.gyh}}</span>
                    <span>{{data.win_extend.code_gyh.bos}}</span>
                    <span>{{data.win_extend.code_gyh.sod}}</span>
                </template>
            </div>

            <Select @on-open-change="onLottoControl" class="control-button" placeholder="指定和值" style="width:100px" v-else-if="$store.state.config.lotto_control.indexOf(params.lotto) != -1" v-model="data.control">
                <Option value="cancel">未控制</Option>
                <Option value="bet">投注分配</Option>
                <Option value="he_00">和值-00</Option>
                <Option :key="item" :value="'he_'+(Array(2).join('0') + item).slice(-2)" v-for="item in 27">和值-{{ (Array(2).join('0') + item).slice(-2)}}</Option>
            </Select>
        </div>

        <!-- <Row class="stats-grid-wrap">
            <Col class="stats-grid-item" span="4">
                <div class="stats-grid-item-title">投注总额</div>
                <div class="stats-grid-item-content" v-html="stats.bet_total || '0.00'" />
            </Col>

            <Col class="stats-grid-item bg-light" span="4">
                <div class="stats-grid-item-title">投注人数</div>
                <div class="stats-grid-item-content" v-html="stats.bet_people || 0" />
            </Col>

            <Col class="stats-grid-item" span="4">
                <div class="stats-grid-item-title">返奖总额</div>
                <div class="stats-grid-item-content" v-html="stats.win_total || '0.00'" />
            </Col>

            <Col class="stats-grid-item bg-light" span="4">
                <div class="stats-grid-item-title">返奖人数</div>
                <div class="stats-grid-item-content" v-html="stats.win_people || 0" />
            </Col>
        </Row>-->

        <Table :columns="columns" :data="logs" :height="300 |table_height" :row-class-name="rowClass" border class="mt-20">
            <!-- <template slot="user" slot-scope="{row}">{{row.user.nickname}}</template> -->
            <div @click="$store.dispatch('onMemberInfo', row.user_id)" class="pointer" slot="user" slot-scope="{row}">
                <Avatar :src="row.user.avatar" class="mr-10" shape="circle" size="22" />
                <span>{{row.user.nickname}}</span>
                <Tag class="ml-6 mb-0" color="volcano" v-if="row.user.trial">试玩</Tag>
            </div>

            <template slot="created_at" slot-scope="{row}">{{$moment(row.created_at).format('MM-DD HH:mm:ss')}}</template>

            <template slot="action" slot-scope="{row}">
                <Button @click="$store.dispatch('onBetLogGet',row.id)" size="small" type="text">详情</Button>
            </template>
        </Table>
    </Drawer>
</template>

<script>
export default {
    data() {
        return {
            params: this.$store.state.drawer.lotto_get,
            columns: [
                {
                    title: '用户',
                    width: 240,
                    key: 'user_id',
                    slot: 'user',
                    className: 'font-13',
                    sortable: true,
                },
                {
                    title: '投注',
                    key: 'total',
                    align: 'center',
                    sortable: true,
                },

                {
                    title: '中奖',
                    key: 'bonus',
                    align: 'center',
                    sortable: true,
                },

                {
                    title: '下注时间',
                    align: 'center',
                    slot: 'created_at',
                    minWidth: 70,
                    sortable: true,
                },

                {
                    title: '操作',
                    align: 'center',
                    slot: 'action',
                    width: 80,
                },
            ],
            loading: false,
            data: {},
            // stats: {},
            logs: [],
        }
    },

    methods: {
        getData(visible) {
            if (visible === true) {
                const api = '/lotto/' + this.params.lotto + '/data/get'
                var params = { id: this.params.id }
                this.loading = true
                this.$get(api, params).then((res) => {
                    if (res.code !== 200) {
                        return (this.loading = res.message)
                    }
                    this.loading = false
                    this.data = res.data
                    res.data.bet_log && (this.logs = res.data.bet_log)
                    // res.data.bet_stats && (this.stats = res.data.bet_stats)
                })
            } else {
                this.data = {}
                this.stats = {}
                this.logs = []
            }
        },

        rowClass(row, index) {
            return row.bonus > 0 ? 'color-green' : null
        },

        onLottoControl(visible) {
            if (this.data.control == undefined || visible === true) {
                return false
            }
            const form = { control: this.data.control, id: this.data.id }
            const api = '/lotto/' + this.params.lotto + '/data/control'
            console.log(form)

            this.$post(api, form).then((res) => {
                if (res.code !== 200) {
                    this.data.control = 'cancel'
                }
            })
        },
    },
}
</script>

<style lang="less" scoped>
/deep/.ivu-col-span-4 {
    width: 25% !important;
}

.control-button {
    position: absolute;
    right: 12px;
    top: 12px;
}
</style>