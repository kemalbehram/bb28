<template>
    <div :class="position" class="chat-item-image clear-both">
        <comm-avatar :class="position" :src="avatar" />
        <div class="chat-item-image--container">
            <img :src="data.message" @click="toImage(data.message)" />
            <span class="chat-item-image--time">{{data.created_at.substring(11,16)}}</span>
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
    },

    methods: {
        toImage(src) {
            window.open(src)
        }
    }
}
</script>


<style lang="less" scoped>
.chat-item-image {
    position: relative;
    padding: 8px 74px 8px 52px;

    &--time {
        display: inline-block;
        position: absolute;
        right: -32px;
        color: #bbbdbf;
        font-size: 12px;
        top: 0;
        line-height: 38px;
    }
    &--container {
        vertical-align: text-top;
        min-height: 38px;
        // background: @bg;
        position: relative;
        border-radius: 2px;
        font-size: 14px;
        line-height: 22px;
        padding: 0;
        float: left;

        img {
            border-radius: 2px;
            display: block;
            max-width: 240px;
            border: 1px solid @line;
        }
    }
}
//右边开始
.right {
    &.chat-item-image {
        padding: 8px 52px 8px 74px;
    }

    .chat-item-image {
        &--time {
            right: auto;
            left: -32px;
        }
        &--container {
            float: right;
        }
    }
}
</style>