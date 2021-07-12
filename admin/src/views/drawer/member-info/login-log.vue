<template>
    <Drawer :footer-hide="true" title="登录记录" v-model="visible" width="840">
        <lett-loading :visible="loading" />登录记录
    </Drawer>
</template>

<script>
export default {
    data() {
        return {
            visible: false,
            form: {},
            loading: false,
            is_update: false
        }
    },

    computed: {
        member() {
            return this.$parent.data
        }
    },

    methods: {
        confirm() {
            this.$post('member/update', this.form).then(res => {
                if (res.code === 200) {
                    this.is_update = true
                }
            })
        }
    }
}
</script>
