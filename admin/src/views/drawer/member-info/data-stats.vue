<template>
    <div class="relative">
        <div class="header-action">
            <Checkbox border class="checkbox" v-model="is_all">详细版</Checkbox>
            <DatePicker :options="$store.state.date_picker" @on-change="getDateData" class="date-picker" format="yyyy-MM-dd" placeholder="请选择日期" placement="bottom-end" type="daterange"></DatePicker>
            <Button @click="$refs.date.getData(date, user_id)" class="ml-10">刷新</Button>
        </div>

        <keep-alive>
            <date-all :assign="current_id" ref="date" v-if="is_all" />
            <date-simple :assign="current_id" ref="date" v-else />
        </keep-alive>
    </div>
</template>

<script>
import dateAll from '@/views/stats/home/date-all'
import dateSimple from '@/views/stats/home/date-simple'
export default {
    components: {
        dateAll,
        dateSimple
    },
    data() {
        return {
            is_all: false,
            is_change: false,
            date: []
        }
    },

    computed: {
        current_id() {
            return this.$parent.data.id
        }
    },
    watch: {
        '$parent.data.id'(val) {
            this.$nextTick(function() {
                this.is_change = true
                this.getDateData(this.date)
            })
        },
        is_all(val) {
            if (this.is_change === true) {
                this.$nextTick(function() {
                    this.getDateData(this.date)
                    this.is_change = false
                })
            }
        },

        date(value) {
            this.is_change = true
        }
    },

    methods: {
        getDateData(date) {
            this.date = date
            return this.$refs.date.getData(this.date, this.user_id)
        }
    }
}
</script>

<style lang="less" scoped>
.date-picker {
    width: 180px;
}

.header-action {
    position: absolute;
    right: 0;
    top: -151px;
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
</style>

