<template>
    <div class="table mt-10">
        <div class="header-action">
            <div>
                <Icon color="#FFF" size="16" type="ios-list-box-outline" />
                <span class="color-white">本期下注记录</span>
            </div>
            <div>
                <span class="pr-3 color-white">期号:</span>
                <Input @on-enter="onSearch" class="pr-10" placeholder="请输入查询的期号" style="width:150px" v-model="config.value.issue" />
                <span class="pr-3 color-white">彩种:</span>
                <Select @on-change="onSearch" style="width:150px" v-model="config.value.type">
                    <Option :key="item.value" :value="item.value" v-for="item in lotto_items">{{ item.text }}</Option>
                </Select>

                <Icon @click="refresh" class="cursor" color="#FFF" size="20" style="padding-left:10px;cursor: pointer;" title="刷新" type="md-refresh" />
            </div>
        </div>
        <div class="pd-20">
            <log-table ref="table" />
        </div>
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table-index.js'
import logTable from './log-table'
export default {
    mixins: [JsMixins],
    components: {
        logTable,
    },
    props: {
        lotto_items: {
            default: [],
        },
    },
    data() {
        return {
            config: {
                value: {
                    issue: null,
                    type: 'ca28',
                },
            },
            default: {},
        }
    },
    created() {
        const timer1 = setInterval(() => {
            this.refresh()
        }, 60000)
        this.$once('hook:beforeDestroy', () => {
            clearInterval(timer1)
        })
    },
    methods: {
        refresh() {
            this.$refs.table.loading = true
            this.$refs.table.getIndex()
        },
    },
}
</script>

<style lang="less" scoped>
.table {
    background: @white;
    .header-action {
        height: 40px;
        display: flex;
        justify-content: space-between;
        padding: 0 10px;
        align-items: center;
        background: @blue;
    }
}
.pd-20 {
    padding: 20px;
    height: 548px;
    overflow-y: scroll;
}
</style>