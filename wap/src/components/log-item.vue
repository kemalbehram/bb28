<template>
    <div :class="border && 'border-private'" class="log-item">
        <div class="log-item-icon" v-if="$slots.icon">
            <slot name="icon" />
        </div>

        <div class="log-item-content">
            <div class="log-item__title">
                <slot name="title">{{title}}</slot>
            </div>
            <div class="log-item__time">
                <slot name="time">{{time}}</slot>
            </div>
            <div class="log-item__value">
                <slot name="value">{{value}}</slot>
            </div>
            <div class="log-item__excerpt">
                <slot name="excerpt">{{excerpt}}</slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: '',
        time: '',
        excerpt: '',
        value: '',
        border: {
            default: false,
            type: Boolean,
        },
    },
}
</script>

<style lang="less" scoped>
.log-item {
    position: relative;
    display: flex;
    background: @white;
    padding: 12px 16px 10px;
    position: relative;
    line-height: 22px;
    &-content {
        flex: 1;
        position: relative;
        height: 46px;
    }
    &-icon {
        margin-right: 12px;
        .icon-default {
            font-size: 40px;
            line-height: 44px;
        }
    }
    &__title {
        line-height: 24px;
        font-size: 14px;
    }
    &__time {
        color: @desc;
        font-size: 13px;
    }

    &__value {
        position: absolute;
        right: 0;
        top: 0;
        font-size: 15px;
        color: #555758;
        line-height: 24px;
        // font-family: 'GoogleSans';
    }

    &__excerpt {
        position: absolute;
        right: 0;
        bottom: 0;
        font-size: 13px;
        color: @desc;
    }
}

// .log-item:not(:last-child)::after {
.log-item.border-private::after {
    border: 0;
    position: absolute;
    box-sizing: border-box;
    content: ' ';
    pointer-events: none;
    right: 0;
    bottom: 0;
    left: 16px;
    border-bottom: 1px solid @line;
    transform: scaleY(0.5);
}
</style>