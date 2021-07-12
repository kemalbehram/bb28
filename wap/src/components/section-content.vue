<template>
    <div class="section-container">
        <div class="section-header">
            <div class="section-header-title">{{title}}</div>
            <div class="section-header-label">{{label}}</div>
            <div @click="onSubscript" class="section-header-subscript">{{subscript}}</div>
        </div>

        <div :style="contentStyle" class="section-content" v-if="$slots.default">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: '',
        label: '',
        subscript: '',
        height: {
            default: 300,
            type: [String, Number]
        }
    },

    computed: {
        contentStyle() {
            return 'height:' + this.height + 'px'
        }
    },

    methods: {
        onSubscript() {
            this.$emit('on-subscript')
        }
    }
}
</script>

<style lang="less" scoped>
.section-container {
    overflow: hidden;
}

.section-content {
    overflow: scroll;
    position: relative;
}

.section-header {
    padding: 10px 16px 12px;
    border-bottom: 10px solid @bg;
    overflow: hidden;
    position: relative;
    background: @white;
    z-index: 2;
    &-title {
        line-height: 26px;
        font-size: 16px;
    }
    &-label {
        line-height: 22px;
        color: @desc;
    }
    &-button {
        position: absolute;
        right: 16px;
        top: 10px;
        color: @green;
    }
    &-subscript {
        padding-top: 0;
        font-weight: normal;
        font-size: 12px;
        color: #fff;
        height: 24px;
        width: 100px;
        position: absolute;
        right: -26px;
        text-align: center;
        line-height: 24px;
        background-color: @green;
        transform: rotate(45deg);
        top: 15px;
        z-index: 1;
        opacity: 0.85;
    }
}
</style>