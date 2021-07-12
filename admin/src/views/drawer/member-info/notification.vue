<template>
    <Drawer :footer-hide="true" title="消息通知" v-model="visible" width="520">
        <lett-loading :visible="loading" />消息通知
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
