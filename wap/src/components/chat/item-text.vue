<template>
    <div :class="position" class="chat-item-text clear-both">
        <comm-avatar :class="position" :src="avatar" :wechat="wechat" />
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
        commAvatar,
    },
    props: {
        data: {},
        opposite: false,
        avatar: '',
        wechat: false,
    },

    computed: {
        position() {
            if (this.opposite === true) return 'right'
        },
    },
}
</script>


<style lang="less" scoped>
.chat-item-text {
    position: relative;
    padding: 8px 74px 8px 52px;

    &--time {
        display: inline-block;
        position: absolute;
        right: -36px;
        color: #999b9c;
        font-size: 12px;
        top: 0;
        line-height: 38px;
    }
    &--container {
        vertical-align: text-top;
        min-height: 38px;
        background: @bg;
        position: relative;
        border-radius: 2px;
        font-size: 14px;
        line-height: 22px;
        padding: 8px 10px;
        float: left;
        word-break: break-all;
    }
    &--container::before {
        content: '';
        width: 0;
        height: 0;
        border: 5px solid;
        position: absolute;
        left: -10px;
        top: 14px;
        border-color: transparent @bg transparent transparent;
    }
}
//右边开始
.right {
    &.chat-item-text {
        padding: 8px 52px 8px 74px;
    }

    .chat-item-text {
        &--time {
            right: auto;
            left: -36px;
        }
        &--container {
            float: right;
            background: linear-gradient(40deg, #ff6f64, #f44);
            color: @white;
        }
        &--container::before {
            right: -10px;
            left: auto;
            border-color: transparent transparent transparent #f44;
        }
    }
}
</style>