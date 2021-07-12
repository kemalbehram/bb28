<template>
    <div class="relative">
        <div class="loading-container mb-20" v-if="loading">
            <lett-loading :visible="loading" @click="getData()" class="border-all" />
        </div>

        <template v-else>
            <stats-items :fields="item" :key="index" :stats="stats" class="mb-20" v-for="(item,index) in $store.state.config.stats_total" />
        </template>
    </div>
</template>

<script>
import statsItems from './stats-items'
export default {
    components: {
        statsItems,
    },
    data() {
        return {
            loading: false,
            stats: {},
        }
    },

    created() {
        this.getData()
    },

    methods: {
        getData(date = []) {
            this.loading = true
            this.$get('/data-stats/total').then((res) => {
                if (res.code !== 200) return (this.loading = res.message)
                this.stats = res.data
            })
        },
    },
}
</script>

<style lang="less" scoped>
.loading-container {
    border: 1px solid @line;
    height: 357px;
    margin-top: -1px;
}
</style>
