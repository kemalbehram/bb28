<template>
    <Card dis-hover>
        <table-header title="综合报表">
            <div class="header-action">
                <DatePicker  @on-change="getIndex" class="date-picker" format="yyyy-MM-dd" placeholder="请选择日期" placement="bottom-end" type="daterange"></DatePicker>
                <Button @click="getIndex" class="ml-10">刷新</Button>
            </div>
            <div class="header-intro">为了考虑服务器性能，请尽量减少查询天数间隔</div>
        </table-header>

        <table-data ref="table" />
        </div>
    </Card>
</template>

<script>
import tableData from './table-data'

export default {
    name: 'dataStatsTable',
    components: {
        tableData
    },
    data() {
        return {
            date: [],
        }
    },

    methods : {
        getIndex(date = []) {
            console.log(date)
            this.$refs.table.getIndex(date)
        }
    }
}
</script>

<style lang="less" scoped>
.date-picker {
    width: 200px;
}

/deep/.stats-table-item {
    width: 16.6666666%;
}

.loading-container {
    border: 1px solid @line;
    height: 200px;
}

.header-action {
    position: absolute;
    right: 0;
    top: 3px;
}

.header-intro {
    color: @sub;
    margin-top: -4px;
}
</style>
