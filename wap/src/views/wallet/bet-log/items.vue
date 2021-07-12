<template>
    <van-collapse accordion v-model="collapse">
        <van-collapse-item :border="false" :is-link="false" :key="index" v-for="(item,index) in items">
            <logItem :title="item.lotto_title" slot="title">
                <!-- <van-icon :name="item.lotto_icon" class-prefix="icon-lotto" class="icon-default" slot="icon" v-if="showIcon" /> -->

                <template slot="title">
                    <span>{{item.lotto_title}}</span>
                    <span v-if="item.play_type">-{{$store.state.config.play_types[item.play_type].title}}</span>
                    <span class="total">{{item.total}}</span>
                </template>
                <template slot="time">
                    <span class="font-14">第{{item.short_id}}</span>期
                    <span>查看详情</span>
                    <van-icon class="ml-2" name="arrow-down" size="12" />
                </template>
                <span :class="getExcerpt(item).class" class="font-14" slot="value" v-html="getExcerpt(item).value" />
            </logItem>
            <div class="log-info-detail">
                <van-cell :border="false" class="small left" title="开奖号码" v-if="item.win_extend">
                    <div class="inline-block">
                        <span class="color-sub color-red">{{item.win_extend.code_arr.join('+')}} = {{item.win_extend.code_he}}</span>
                        <span class="ml-2 mr-2" v-if></span>
                        <span class="color-red" v-if="item.win_extend.code_bos">{{item.win_extend.code_bos}}</span>
                        <span class="ml-2 mr-2" v-if></span>
                        <span class="color-red" v-if="item.win_extend.code_sod">{{item.win_extend.code_sod}}</span>
                        <span class="ml-2 mr-2" v-if>-</span>
                        <span class="color-red" v-if="item.win_extend.code_long">龙/</span>

                        <span class="color-red" v-if="item.win_extend.code_hu">虎/</span>
                        <span class="color-red" v-if="item.win_extend.code_bao">豹/</span>
                        <span class="color-red" v-if="item.win_extend.code_dui">对子/</span>
                        <span class="color-red" v-if="item.win_extend.code_baozi">豹子/</span>
                        <span class="color-red" v-if="item.win_extend.code_shun">顺子/</span>

                        <!-- <span class="color-red font-14">{{detail.bonus}}</span> -->
                    </div>
                </van-cell>
                <van-cell :border="false" :value="item.str_place" class="small left" title="投注号码">
                    <div :key="detail.id" class="inline-block" v-for="detail in item.details">
                        <span class="color-sub">{{detail.name}}</span>
                        <span class="ml-2 mr-2">-</span>
                        <span class="font-14">{{detail.amount}}</span>
                    </div>
                </van-cell>
                <van-cell :border="false" :value="item.total" class="small left" title="投注总额" />

                <van-cell :border="false" class="small left" title="中奖号码" v-if="item.bonus > 0">
                    <div :key="detail.id" :v-if="detail.bonus > 0" class="inline-block" v-for="detail in item.details">
                        <span class="color-sub">{{detail.name}}</span>
                        <span class="ml-2 mr-2">-</span>
                        <span class="color-red font-14">{{detail.bonus}}</span>
                    </div>
                </van-cell>
                <!-- <van-cell :border="false" :value="item.total" class="small left" title="投注总额" /> -->
                <van-cell :border="false" :value="item.created_at" class="small left" title="投注时间" />
                <van-cell :border="false" :value="item.confirmed_at" class="small left" title="派奖时间" v-if="item.confirmed_at" />
            </div>
        </van-collapse-item>
    </van-collapse>
</template>

<script>
import logItem from '@/components/log-item'
export default {
    props: {
        items: Array,
        showIcon: {
            default: true,
            type: Boolean,
        },
    },
    components: {
        logItem,
    },
    data() {
        return {
            collapse: null,
        }
    },

    methods: {
        getExcerpt(item) {
            var result = { class: 'color-sub', value: '' }

            if (item.status === 1) {
                result.value = '待开奖'
                result.class = 'color-green'
            }

            if (item.status === 2) {
                result.value = '未中奖'
            }

            if (parseFloat(item.bonus) > 0 && item.status === 2) {
                result.class = 'color-red'
                result.value = '<span class="font-15">' + item.profit + '</span>'
            }

            return result
        },
    },
}
</script>

<style lang="less" scoped>
.log-item {
    /deep/ &__title .total {
        background: #f2f4f5;
        font-size: 12px;
        line-height: 16px;
        display: inline-block;
        padding: 0 4px;
        border-radius: 2px;
        color: @sub;
        border: 1px solid #ecedef;
        margin-top: -2px;
        vertical-align: middle;
        margin-left: 4px;
    }
}

.log-info {
    &-detail {
        background: @bg;
        border-radius: 4px;
        overflow: hidden;
        padding: 8px 8px;
        word-break: keep-all;
        /deep/.van-cell {
            background: @bg;
        }
    }
}

.van-collapse {
    &-item {
        overflow: hidden;
    }
    /deep/.van-cell {
        padding: 0;
        /deep/.van-cell__title {
            flex: 1;
        }
        /deep/.van-cell__value {
            flex: 3;
        }
    }

    /deep/.van-collapse-item__content {
        padding: 0 16px 16px;
    }
}

/deep/[class*='van-hairline']::after {
    margin-left: 22px;
}
</style>