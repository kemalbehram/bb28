export default {
    data() {
        return {
            dataware: {
                index: {
                    api: '',
                },
            },
            columns: [],
            loading: false,
            page: {},
            items: [],
        }
    },

    created() {
        this.$nextTick(function() {
            this.getIndex()
        })
    },

    methods: {
        onPage(page) {
            this.$emit('on-search', 'page', page)
        },

        onSort(data) {
            this.$emit('on-sort', data)
        },

        getIndex(page = 1, params) {
            const api = this.dataware.index.api
            return this.$dataware.index(api, this, page, params)
        },

        updateData(open, data, success = false) {
            const fields = this.dataware.update.fields
            return this.$dataware.update(this, open, data, success, fields)
        },
        createData(open, data) {
            return this.$dataware.create(this, open, data)
        },

        deleteData(data) {
            const api = this.dataware.delete.api
            return this.$dataware.remove(api, this, data)
        },
    },
}
