export default {
    data() {
        return {
            loading: false,
            config: {
                value: {
                    page: '1',
                    order_key: null,
                    order_value: null,
                },
                select: {
                    field: 'id',
                    value: '',
                },
            },
            default: {},
            auto_load: null,
        }
    },

    created() {
        this.default = JSON.parse(JSON.stringify(this.config))
    },

    methods: {
        onSearch(field = null, value = null) {
            if (field && value) {
                if (field in this.config.value) {
                    this.config.value[field] = value
                } else {
                    this.config.select.field = field
                    this.config.select.value = value
                }
            }

            var params = JSON.parse(JSON.stringify(this.config.value))
            params[this.config.select.field] = this.config.select.value

            for (var key in params) {
                if (params[key] === '') {
                    delete params[key]
                }
            }

            if (field !== 'page') params.page = 1
            
            this.$refs.table.getIndex(1, params)
        },

        onSort(data) {
            var params = JSON.parse(JSON.stringify(this.config.value))
            if (data.order !== 'normal') {
                params.order_key = data.key
                params.order_value = data.order

                this.config.value.order_key = data.key
                this.config.value.order_value = data.order
            } else {
                this.config.value.order_key = null
                this.config.value.order_value = null
            }

            for (var key in params) {
                if (params[key] === '') {
                    delete params[key]
                }
            }

            params.page = 1

            this.$refs.table.getIndex(1, params)
        },

        onReset() {
            this.config = JSON.parse(JSON.stringify(this.default))
            this.onSearch()
        },

        autoLoad(value) {
            if (value === true) {
                this.auto_load = setInterval(() => {
                    this.onSearch()
                }, 10000)
            }

            if (value === false) {
                clearInterval(this.auto_load)
                this.auto_load = null
            }
        },
    },
}
