<template>
    <div class="comfirm-area">
        <van-dialog :lockScroll="false" :overlay="false" @cancel="cacelBet" @confirm="lotto.betConfirmfinal" get-container="#bet-confirm" show-cancel-button title="下注清单" v-model="visible">
            <div :border="false" class="bet-confirm">
                <!-- <van-cell :border="false" class="small left" title="游戏">
                    <span>{{lotto.config.title}}</span>
                    <span class="ml-6">第</span>
                    <span class="color-red" v-if="lotto.bet_current.id">{{lotto.bet_current.id}}</span>
                    <span>期</span>
                </van-cell>-->
                <!-- <van-cell :border="false" :value="params.str_place" class="small left" title="投注" /> -->
                <!-- <van-cell :border="false" class="small left" title="费用">
                    <span>共</span>
                    <span class="color-red">{{lotto.bet_total_count }}</span>
                    <span>注，合计</span>
                    <span class="color-red">{{lotto.bet_total}}</span>
                    <span>元</span>
                </van-cell>-->
                <van-cell-group>
                    <!-- <van-cell :key="index" :title="item.name" :v-if="lotto.bet_list.length>0" :value="item.amount" v-for="(item,index) in lotto.bet_list" /> -->
                    <van-cell :v-if="lotto.bet_list.length>0">
                        <div v-for="(item,index) in lotto.bet_list">
                            <div>【{{item.name}}】× {{item.amount}}</div>
                        </div>
                    </van-cell>
                    <van-cell>【合计】总投注数:{{lotto.bet_total_count }},总金额:{{lotto.bet_total}}</van-cell>
                </van-cell-group>
            </div>
        </van-dialog>
        <van-overlay :show="overlay" id="bet-confirm" z-index="4000" />
    </div>
</template>

<script>
export default {
    props: {
        lotto: {
            default: function () {
                return this.$parent
            },
            type: Object,
        },
    },
    data() {
        return {
            params: {},
            visible: false,
            overlay: false,
        }
    },
    methods: {
        cacelBet() {
            this.overlay = false

            this.lotto.bet_list = []
        },
    },
}
</script>

<style lang="less" scoped>
/deep/.van-dialog {
    max-height: 80% !important;
    overflow: overlay !important;
    .van-dialog__header {
        padding-top: 10px;
    }
}
.bet-confirm {
    text-align: center;

    /deep/ .van-cell__title {
        width: 40px;
        text-align: left;
        color: @sub;
    }
}
/deep/.van-cell__value {
    text-align: center;
}
</style>