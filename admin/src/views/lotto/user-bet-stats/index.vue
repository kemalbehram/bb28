<template>
    <Card dis-hover>
        <table-header-new title="下注统计">
            <DatePicker :options="$store.state.date_picker" @on-change="getData" class="date-picker" format="yyyy-MM-dd" placeholder="请选择日期" placement="bottom-end" type="daterange"></DatePicker>

            <Button @click="getData(date)" class="refresh">刷新</Button>

            <div class="header-intro">默认查询为当天数据，本页面仅统计有数据用户，未产生数据用户将不做展示。</div>
        </table-header-new>

        <table-data ref="table" />
    </Card>
</template>

<script>
import tableData from './table-data'
// import JsMixins from '@/components-ue/table/table-index.js'
export default {
    // mixins: [JsMixins],
    name: 'userBetStats',
    components: { tableData },

    data() {
        return {
            date: null,
        }
    },

    methods: {
        getData(date = null) {
            this.date = date
            let params = []

            if (date) {
                params = {
                    date_start: date[0],
                    date_end: date[1],
                }
            }
            return this.$refs.table.getData(params)
        },
    },
}
</script>

<style lang="less" scoped>
.date-picker {
    width: 240px;
    position: absolute;
    right: 70px;
    top: 0;
}

.refresh {
    position: absolute;
    right: 0;
    top: 0;
}

.header-intro {
    color: @sub;
    margin-top: -4px;
}
</style>