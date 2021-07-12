<template>
    <Drawer :closable="false" :footer-hide="true" @on-visible-change="onVisible" v-model="visible" width="840">
        <bet-log :assign="$parent.data.id" :columns="columns" ref="betLog" />
    </Drawer>
</template>

<script>
import betLog from '@/views/lotto/bet'
export default {
    components: { betLog },
    data() {
        return {
            visible: false,

            columns: [
                {
                    title: '流水ID',
                    width: 80,
                    slot: 'id',
                    align: 'left',
                },

                {
                    title: '游戏',
                    slot: 'lotto',
                    align: 'left',
                    width: 200,
                },

                {
                    title: '金额',
                    key: 'total',
                    align: 'right',
                },

                {
                    title: '中奖',
                    slot: 'bonus',
                    align: 'right',
                },

                {
                    title: '盈亏',
                    align: 'right',
                    slot: 'profit',
                },

                {
                    title: '下注时间',
                    slot: 'created_at',
                    width: 130,
                    align: 'right',
                },

                {
                    title: '详情',
                    slot: 'action',
                    align: 'right',
                    width: 70,
                },
            ],
        }
    },

    methods: {
        onVisible(visible) {
            if (visible === false) {
                return false
            }
            return this.$refs.betLog.onSearch()
        },
    },
}
</script>


<style lang="less" scoped>
/deep/.ivu-drawer-body {
    padding: 0px;
}
</style>