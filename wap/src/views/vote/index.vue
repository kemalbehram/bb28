<template>
    <div class="relative">
        <userHeader title="下注记录">
            <h2 @click="showPicker" name="action">记录筛选</h2>
        </userHeader>
        <menu-item :condition="condition" ref="menu" />
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
import menuItem from './menu'

export default {
    name: 'vote',
    components: {
        userHeader,
        menuItem,
    },
    data() {
        return {
            option: [],
            condition: {},
        }
    },
    methods: {
        showPicker(param) {
            // this.$refs.menu.$refs.item[0].showPicker = true
            this.$store.state.config.show_pop = true
        },
    },
}
</script>
<style lang="less" scoped>
.bet-list {
    .custom-value {
        span {
            display: block;
            background: red;
        }
    }
}
</style>