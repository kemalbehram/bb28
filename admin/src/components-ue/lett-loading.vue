<template>
    <Spin :style="style" fix v-if="loading">
        <span class="ivu-spin-dot display" v-if="!message"></span>
        <div @click="onClick" class="message" v-if="message.length > 0">{{message}}</div>
    </Spin>
</template>

<script>
import store from '@/store'
export default {
    props: {
        visible: false,
        zIndex: 0
    },

    data() {
        return {
            loading: this.visible,
            message: ''
        }
    },

    watch: {
        visible(value) {
            if (typeof this.visible === 'boolean') {
                this.loading = this.visible
                this.message = ''
            }

            if (typeof this.visible === 'string') {
                this.loading = true
                this.message = this.visible
            }
        }
    },

    computed: {
        style() {
            if (this.zIndex) {
                return 'z-index:' + this.zIndex
            }
        }
    },

    methods: {
        onClick() {
            return this.$emit('click')
        }
    }
}
</script>

<style scoped>
/* .ivu-spin-fix {
    z-index: 1000;
} */
.display {
    display: inline-block !important;
}
.message {
    height: 50px;
    line-height: 50px;
    cursor: pointer;
}
</style>

