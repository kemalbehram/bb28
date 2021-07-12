<template>
    <div class="container">
        <lett-loading :visible="loading" />
        <div class="stats">
            <div>
                <p>类型总次数:</p>
                <p>
                    <b>{{total[0]}}</b>
                </p>
                <p>
                    总额
                    <b>{{total[1]}}</b>
                </p>
            </div>
            <div>
                <p>
                    大小单双:
                    <b>{{dxds[0]}}</b>次
                </p>
                <p>
                    比例：
                    <b>{{(Math.round(dxds[0] / total[0] * 10000) / 100.00 + "%")}}</b>
                </p>
                <p>
                    总额:
                    <b>{{dxds[1]}}</b>
                </p>
            </div>
            <div>
                <p>
                    组合:
                    <b>{{combo[0]}}</b> 次
                </p>
                <p>
                    比例：
                    <b>{{(Math.round(combo[0] / total[0] * 10000) / 100.00 + "%")}}</b>
                </p>
                <p>
                    总额:
                    <b>{{combo[1]}}</b>
                </p>
            </div>
            <div>
                <p>
                    13、14:
                    <b>{{key_13_14[0]}}</b> 次
                </p>
                <p>
                    比例：
                    <b>{{(Math.round(key_13_14[0] / total[0] * 10000) / 100.00 + "%")}}</b>
                </p>
                <p>
                    总额:
                    <b>{{key_13_14[1]}}</b>
                </p>
            </div>
            <div>
                <p>
                    极值:
                    <b>{{jz[0]}}</b> 次
                </p>
                <p>
                    比例：
                    <b>{{(Math.round(jz[0] / total[0] * 10000) / 100.00 + "%")}}</b>
                </p>
                <p>
                    总额:
                    <b>{{jz[1]}}</b>
                </p>
            </div>
        </div>
        <Table :columns="columns" :data="items">
            <table-user :data="row.user" slot="user" slot-scope="{row}" />
            <template slot="bet_info" slot-scope="{row}">
                <span :key="index" v-for="(item,index) in row.details">{{item.name}}:{{item.amount}}/</span>
            </template>
            <template slot="wallet" slot-scope="{row}">
                <span>{{row.user.wallet.total}}</span>
            </template>

            <template slot="footer">
                <div class="footer">
                    <div>
                        <span>大：</span>
                        <span>
                            <b>{{big[1]}}</b>
                        </span>
                    </div>
                    <div>
                        <span>小：</span>
                        <span>
                            <b>{{sml[1]}}</b>
                        </span>
                    </div>
                    <div>
                        <span>单：</span>
                        <span>
                            <b>{{sig[1]}}</b>
                        </span>
                    </div>
                    <div>
                        <span>双：</span>
                        <span>
                            <b>{{dob[1]}}</b>
                        </span>
                    </div>
                    <div>
                        <span>大单：</span>
                        <span>
                            <b>{{bsg[1]}}</b>
                        </span>
                    </div>
                    <div>
                        <span>大双：</span>
                        <span>
                            <b>{{bdo[1]}}</b>
                        </span>
                    </div>
                    <div>
                        <span>小单：</span>
                        <span>
                            <b>{{ssg[1]}}</b>
                        </span>
                    </div>
                    <div>
                        <span>小双：</span>
                        <span>
                            <b>{{sdo[1]}}</b>
                        </span>
                    </div>
                    <div>
                        <span>13：</span>
                        <span>
                            <b>{{key_13[1]}}</b>
                        </span>
                    </div>
                    <div>
                        <span>14：</span>
                        <span>
                            <b>{{key_14[1]}}</b>
                        </span>
                    </div>
                    <div>
                        <span>总额：</span>
                        <span>
                            <b>{{total[1]}}</b>
                        </span>
                    </div>
                </div>
            </template>
        </Table>

        <!-- <lett-page :page="page" @on-change="onPage" /> -->
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],
    data() {
        return {
            columns: [
                {
                    title: '玩家名称/备注',
                    key: 'user',
                    slot: 'user',
                },
                {
                    title: '彩种类型',
                    key: 'lotto_title',
                },
                {
                    title: '下注信息',
                    key: 'bet_info',
                    slot: 'bet_info',
                    minWidth: 300,
                },
                {
                    title: '下注金额',
                    key: 'total',
                    align: 'right',
                },
                {
                    title: '剩余分数',
                    key: 'wallet',
                    align: 'right',
                    slot: 'wallet',
                },
            ],
            updated: true,
            items: [],
            total: [0, 0],
            jz: [0, 0],
            dxds: [0, 0],
            combo: [0, 0],
            key_13_14: [0, 0],
            big: [0, 0],
            sml: [0, 0],
            sig: [0, 0],
            dob: [0, 0],
            bsg: [0, 0],
            bdo: [0, 0],
            ssg: [0, 0],
            sdo: [0, 0],
            key_13: [0, 0],
            key_14: [0, 0],
        }
    },
    watch: {
        '$store.state.home.lotto'(value) {
            if (!this.updated) {
                return
            }
            if (value.lotto_name != this.$parent.config.value.type) {
                return
            }
            if (this.items.length > 0) {
                if (this.items[0].lotto_id != value.lotto_id) {
                    this.items = []
                    // this.page.current = 1
                    // this.page.total = 0
                }
            }
            this.items.unshift(value)
            // console.log(this.items)
            // this.page.total++
        },
        '$store.state.home.changed'(value) {
            if (!this.updated) {
                return
            }
            if (this.$store.state.home.update_lotto != this.$parent.config.value.type) {
                return
            }
            this.getIndex()
        },
        items(val) {
            if (this.updated) {
                console.log('根更改')
                // let issue = this.$parent.config.value.issue
                let type = this.$parent.config.value.type
                if (this.$store.state.home.lotto[type] == undefined) {
                    this.$set(this.$store.state.home.lotto, type, [])
                }
                this.$store.state.home.lotto[type] = val
                this.updated = false
            }
        },
    },
    methods: {
        getIndex(page = 1, params = {}) {
            const api = '/home/the-bet-log'
            params.type = this.$parent.config.value.type
            this.$get(api, params, false, false).then((res) => {
                this.loading = false
                if (res.code == 200) {
                    this.items = res.data.items
                    this.total = res.data.total
                    this.jz = res.data.jz
                    this.dxds = res.data.dxds
                    this.combo = res.data.combo
                    this.key_13_14 = res.data.key_13_14

                    this.big = res.data.big
                    this.sml = res.data.sml
                    this.sig = res.data.sig
                    this.dob = res.data.dob
                    this.bsg = res.data.bsg
                    this.bdo = res.data.bdo
                    this.ssg = res.data.ssg
                    this.sdo = res.data.sdo
                    this.key_13 = res.data.key_13
                    this.key_14 = res.data.key_14
                }
            })
            // if (this.$parent.config.value.issue != '' && this.$parent.config.value.issue != null) {
            //     this.updated = false
            // } else {
            //     this.updated = true
            // }
            // params.type = this.$parent.config.value.type
            // return this.$dataware.index(api, this, page, params)
        },
    },
}
</script>

<style lang="less" scoped>
.stats {
    display: flex;
    justify-content: space-around;
    padding-bottom: 20px;
    div {
        text-align: center;
        b {
            color: red;
        }
    }
}
.footer {
    display: flex;
    justify-content: space-around;
    div {
        span {
            padding-left: 5px;
        }
        text-align: center;
        b {
            color: red;
        }
    }
}
</style>