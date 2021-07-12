<template>
    <van-tabs @click="getTab" background="none" class="tab-card" sticky>
        <van-tab :key="index" :title="item" class="tab-card--item" v-for="(item,index) in menu">
            <component :field="active" :is="currentTab" ref="item" v-if="active==index"></component>
        </van-tab>
    </van-tabs>
</template>
<script>
import allItem from './item.vue'
import winItem from './item.vue'
import loseItem from './item.vue'
import waitItem from './item.vue'
export default {
    components: {
        allItem,
        winItem,
        loseItem,
        waitItem,
    },
    data() {
        return {
            active: 'all',
            menu: {
                all: '全部',
                win: '已中奖',
                lose: '未中奖',
                wait: '待开奖',
            },
        }
    },
    computed: {
        currentTab() {
            return this.active + 'Item'
        },
    },
    methods: {
        getTab(param) {
            if (param == 0) {
                this.active = 'all'
            } else if (param == 1) {
                this.active = 'win'
            } else if (param == 2) {
                this.active = 'lose'
            } else if (param == 3) {
                this.active = 'wait'
            }
        },
    },
}
</script>
<style lang="less" scoped>
.tab-card {
    // /deep/.van-sticky {
    //     padding: 0% 12%;
    //     background: @white;
    //     border-bottom: 1px solid rgb(227, 228, 233);
    //     // /deep/.van-tab {
    //     .van-tab {
    //         width: 66px;
    //         height: 33px;
    //         background: #e3e4e9;
    //         margin: 15px 5px;
    //         color: @black;
    //         border-radius: 5px;
    //     }
    //     .van-tabs__line {
    //         height: 0px;
    //     }

    //     .van-tab--active {
    //         background: #4f8dfe;
    //         color: @white;
    //     }
    //     /deep/.van-tabs__nav--line {
    //         padding-bottom: 0px;
    //     }
    //     .van-tabs__wrap {
    //         height: auto !important;
    //     }
    // }
    /deep/.van-tabs__line {
        background-color: #ff6a00;
    }
}
</style>