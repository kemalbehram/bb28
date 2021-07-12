<template>
    <div class="relative">
        <div class="loading-container mb-20" v-if="loading">
            <lett-loading :visible="loading" @click="loading = false" class="border-all" />
        </div>

        <template v-else>
            <stats-items :fields="items.data_base" :stats="stats" class="mb-20" />
            <stats-items :fields="items.data_transfer_user" :stats="stats" class="mb-20" v-if="stats.user_id" />
            <stats-items :fields="items.data_transfer" :stats="stats" class="mb-20" v-else />
            <stats-items :fields="data_lotto" :stats="stats" class="mb-20" />
            <stats-items :fields="items.data_award" :stats="stats" class="mb-20" />
            <stats-items :fields="items.data_commission" :stats="stats" class="mb-20" />
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

    created() {
        this.getData()
    },

    computed: {
        data_lotto() {
            let config = this.$store.state.config.lotto_items
            let names = Object.keys(config)
            var result = []
            names.forEach((name) => {
                result.push({
                    title: config[name].title,
                    field: config[name].name + '_profit',
                })
            })

            return result
        },

        items() {
            return this.$store.state.config.stats_simple
        },
    },

    watch: {
        assign(value) {
            console.log(value)
        },
    },

    methods: {
        getData(date = [], user_id = null) {
            let query = {}
            query.date_start = date[0]
            query.date_end = date[1]
            query.user_id = this.assign
            this.loading = true
            this.$get('/data-stats/date-all', query, false).then((res) => {
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
                return 'color-red'
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
    height: 972px;
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
