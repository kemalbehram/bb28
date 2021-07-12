<template>
    <span>
        <Avatar :shape="shape" :size="size" icon="ios-person" v-if="failed" />
        <Avatar :shape="shape" :size="size" :src="src" @on-error="error" class="border-all" v-else />
        <slot>{{content}}</slot>
    </span>
</template>

<script>
export default {
    props: {
        src: {
            default: '',
            type: String
        },

        content: {
            default: '',
            type: String
        },

        size: {
            default: 'small',
            type: String
        },

        shape: {
            default: 'circle',
            type: String
        }
    },

    data() {
        return {
            failed: false
        }
    },

    methods: {
        error(event) {
            this.failed = true
        }
    }
}
</script>
