<template>
    <div class="relative mb-10">
        <div class="loading-container mb-20" v-if="loading">
            <lett-loading :visible="loading" @click="loading = false" class="border-all" />
        </div>

        <template v-else>
            <stats-items :fields="items.data_base" :stats="stats" class="mb-20" />
            <stats-items :fields="items.data_transfer_user" :stats="stats" class="mb-20" v-if="stats.user_id" />
            <stats-items :fields="items.data_transfer" :stats="stats" class="mb-20" v-else />
            <stats-items :fields="items.data_red_bag" :stats="stats" class="mb-20" />
        </template>
    </div>
</template>

<script>
import statsItems from './stats-items'
export default {
    props: {
        assign: {
            default: null,
            type: [String, Number],
        },
    },
    components: {
        statsItems,
    },
    data() {
        return {
            loading: false,
            stats: {},
            date: [],
            user_id: '',
        }
    },

    computed: {
        items() {
            return this.$store.state.config.stats_simple
        },
    },

    created() {
        this.getData()
    },

    watch: {
        assign(value) {
            console.log(value)
        },
    },

    methods: {
        getData(date = []) {
            let query = {}
            query.date_start = date[0]
            query.date_end = date[1]
            query.user_id = this.assign
            this.loading = true
            this.$get('/data-stats/date-simple', query, false).then((res) => {
                this.loading = false
                if (res.code !== 200) return this.$Message.warning(res.message)
                this.stats = res.data
            })
        },

        getSheet(day) {
            this.getData(day)
            this.show = false
        },

        amountClass(amount) {
            if (parseFloat(amount) == 0) {
                return 'color-desc'
            }
        },

        profitClass(amount) {
            let value = parseFloat(amount)
            if (value == 0) {
                return false
            } else if (value > 0) {
                return 'color-green'
            } else if (value < 0) {
                return 'color-black'
            }
        },
    },
}
</script>

<style lang="less" scoped>
.date-picker {
    width: 200px;
}

.loading-container {
    border: 1px solid @line;
    height: 357px;
    margin-top: -1px;
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
