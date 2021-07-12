<template>
    <Drawer :closable="false" :footer-hide="true" @on-visible-change="onVisible" class="drawer-with-action" v-model="visible" width="840">
        <wallet-log :assign="$parent.data.id" :tableColumns="columns" ref="betLog" />
    </Drawer>
</template>

<script>
import walletLog from '@/views/log/wallet-log'
export default {
    components: {
        walletLog,
    },

    data() {
        return {
            visible: false,
            loading: false,

            columns: [
                {
                    title: '流水ID',
                    key: 'id',
                    align: 'left',
                    width: 100,
                },

                {
                    title: '账户',
                    slot: 'field',
                    maxWidth: 80,
                },

                {
                    title: '描述',
                    key: 'title',
                    className: 'color-black',
                    width: 180,
                },

                {
                    title: '关联ID',
                    key: 'source_id',
                },

                {
                    title: '金额',
                    slot: 'amount',
                    align: 'right',
                    className: 'color-black',
                },
                {
                    title: '快照',
                    key: 'after',
                    align: 'right',
                },

                {
                    title: '时间',
                    slot: 'created_at',
                    align: 'right',
                    width: 140,
                },
            ],
        }
    },

    computed: {
        member() {
            return this.$parent.data
        },
    },

    methods: {
        onVisible(visible) {
            if (visible === false) {
                return false
            }
            return this.$refs.betLog.onSearch('user_id', this.$parent.data.id)
        },
    },
}
</script>

<style lang="less" scoped>
/deep/.ivu-drawer-body {
    padding: 0px;
}
</style>
