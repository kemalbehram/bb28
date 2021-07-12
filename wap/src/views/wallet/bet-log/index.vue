<template>
    <div class="relative">
        <van-nav-bar @click-left="$store.dispatch('toBack')" fixed left-arrow>
            <van-dropdown-menu slot="title">
                <van-dropdown-item :title="menus[active]" ref="item">
                    <van-grid :border="false" :column-num="3" gutter="6">
                        <van-grid-item :class="active === key && 'nav-checked'" :key="key" :text="value" @click="onNavClick(key)" v-for="(value, key) in menus" />
                    </van-grid>
                </van-dropdown-item>
            </van-dropdown-menu>

            <van-icon @click="show_pop = true" name="bars" size="22" slot="right" />
        </van-nav-bar>

        <van-action-sheet :round="false" title="系统提示" v-model="show_pop">
            <div class="sheet-content">
                历史记录中仅展示最近30天记录
                <br />如需查询更多历史记录，请联系在线客服
            </div>
        </van-action-sheet>

        <component :is="currentTab" :status="active"></component>
    </div>
</template>

<script>
import logItem0 from './item-wrap'
import logItem1 from './item-wrap'
import logItem2 from './item-wrap'
import logItem3 from './item-wrap'
export default {
    name: 'betLog',
    components: {
        logItem0,
        logItem1,
        logItem2,
        logItem3
    },
    props: {
        room: 0
    },
    data() {
        return {
            show_pop: false,
            active: '0',
            menus: {
                0: '所有记录',
                1: '未开奖',
                2: '已开奖',
                3: '已中奖',
                4: '未中奖'
            }
        }
    },

    computed: {
        currentTab() {
            return 'logItem' + this.active
        }
    },

    methods: {
        onNavClick(key) {
            this.active = key
            this.$refs.item.toggle()
        }
    }
}
</script>

<style lang="less" scoped>
/deep/.van-dropdown-menu::after {
    display: none;
}

// /deep/.van-dropdown-item {
//     top: 52px !important;
// }

/deep/.van-dropdown-item__content {
    padding: 18px 10px 14px 10px;
}

/deep/ .van-grid-item__content {
    background: @bg;
}

/deep/.van-grid-item {
    margin-bottom: 4px;
    &__content {
        padding: 0 !important;
        border-radius: 20px;
    }
    &__text {
        line-height: 30px;
        color: @black;
        font-size: 14px;
    }
}
.nav-checked /deep/.van-grid-item__content {
    background: linear-gradient(40deg, #ff6f64, #f44);
    color: @white;

    .van-grid-item__text {
        color: @white;
    }
}

.sheet-content {
    text-align: center;
    padding: 14px 16px;
    text-align: center;
    line-height: 28px;
    color: @desc;
    font-size: 15px;
}
</style>
