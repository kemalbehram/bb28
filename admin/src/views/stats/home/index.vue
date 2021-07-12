<template>
    <div class="relative">
        <Card class="mb-20" dis-hover>
            <table-header-new title="平台总数据">
                <div class="header-action">
                    <Button @click="$refs.total.getData()" class="ml-10">刷新</Button>
                </div>
                <div class="header-intro">该组数据为平台开站以来的总数据，不可根据时间戳查询指定时间的数据。</div>
            </table-header-new>
            <total ref="total" />
        </Card>

        <Card dis-hover>
            <table-header-new title="综合数据统计">
                <div class="header-action">
                    <Checkbox border class="checkbox" v-model="is_all">详细版</Checkbox>
                    <DatePicker :options="$store.state.date_picker" @on-change="getDateData" class="date-picker" format="yyyy-MM-dd" placeholder="请选择日期" placement="bottom-end" type="daterange"></DatePicker>
                    <Button @click="$refs.date.getData(date, user_id)" class="ml-10">刷新</Button>
                </div>
                <div class="header-intro">为保证正常运行，请尽量避免频繁刷新。默认查询为今天的实时数据。</div>
            </table-header-new>
            <keep-alive>
                <date-all ref="date" v-if="is_all" />
                <date-simple ref="date" v-else />
            </keep-alive>
        </Card>
    </div>
</template>


<script>
import total from './total'
import dateAll from './date-all'
import dateSimple from './date-simple'
export default {
    name: 'dataStatsHome',
    components: {
        total,
        dateAll,
        dateSimple,
    },

    data() {
        return {
            user_id: null,
            is_all: false,
            is_change: false,
            date: [],
        }
    },

    watch: {
        is_all(val) {
            if (this.is_change === true) {
                this.$nextTick(function () {
                    this.getDateData(this.date)
                    this.is_change = false
                })
            }
        },

        date(value) {
            this.is_change = true
        },
    },

    methods: {
        getDateData(date) {
            this.date = date
            return this.$refs.date.getData(this.date, this.user_id)
        },
    },
}
</script>

<style lang="less" scoped>
.date-picker {
    width: 180px;
}

.header-action {
    position: absolute;
    right: 0;
    top: 3px;
    .checkbox {
        right: 250px;
        top: 0px;
        position: absolute;
        width: 96px;
        border-color: @line;
        font-size: 13px;

        /deep/ .ivu-checkbox {
            margin-right: 6px;
        }
    }
}

.header-intro {
    color: @sub;
    margin-top: -4px;
}

/deep/.ivu-card-body {
    padding-bottom: 0 !important;
}
</style>
