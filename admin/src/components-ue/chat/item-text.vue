<template>
    <div :class="position" class="chat-item-text clear-both">
        <comm-avatar :class="position" :src="avatar" />
        <div class="chat-item-text--container">
            {{data.message}}
            <span class="chat-item-text--time">{{data.created_at.substring(11,16)}}</span>
        </div>
    </div>
</template>

<script>
import commAvatar from './comm-avatar'
export default {
    components: {
        commAvatar
    },
    props: {
        data: {},
        opposite: false,
        avatar: ''
    },

    computed: {
        position() {
            if (this.opposite === true) return 'right'
        }
    }
}
</script>


<style lang="less" scoped>
.chat-item-text {
    position: relative;
    padding: 8px 74px 8px 44px;
    word-break: break-all;

    &--time {
        display: inline-block;
        position: absolute;
        right: -36px;
        color: @desc;
        font-size: 13px;
        top: 0;
        line-height: 32px;
    }
    &--container {
        vertical-align: text-top;
        min-height: 32px;
        background: @bg-base;
        position: relative;
        border-radius: 2px;
        font-size: 14px;
        line-height: 22px;
        padding: 5px 10px;
        float: left;
    }
    &--container::before {
        content: '';
        width: 0;
        height: 0;
        border: 5px solid;
        position: absolute;
        left: -10px;
        top: 12px;
        border-color: transparent @bg-base transparent transparent;
    }
}
//右边开始
.right {
    &.chat-item-text {
        padding: 8px 44px 8px 74px;
    }

    .chat-item-text {
        &--time {
            right: auto;
            left: -36px;
        }
        &--container {
            float: right;
            background: @green;
            color: @white;
        }
        &--container::before {
            right: -10px;
            left: auto;
            border-color: transparent transparent transparent @green;
        }
    }
}
</style>