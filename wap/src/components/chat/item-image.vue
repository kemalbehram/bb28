<template>
    <div :class="position" class="chat-item-image clear-both">
        <comm-avatar :class="position" :src="avatar" :wechat="wechat" />
        <div class="chat-item-image--container">
            <van-image :height="height" :src="data.message" :width="width" @click="Preview(data.message)" @load="imageLoad" radius="2">
                <van-loading size="20" slot="loading" type="spinner" />
            </van-image>
            <span class="chat-item-image--time">{{data.created_at.substring(11,16)}}</span>
        </div>
    </div>
</template>

<script>
import commAvatar from './comm-avatar'
import { ImagePreview } from 'vant'
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

    data() {
        return {
            width: 240,
            height: 160,
        }
    },

    computed: {
        position() {
            if (this.opposite === true) return 'right'
        },
    },

    methods: {
        imageLoad() {
            this.width = null
            this.height = null
            this.$store.dispatch('chatScrollBottom')
        },
        Preview(path) {
            ImagePreview([path])
        },
    },
}
</script>
<style lang="less" scoped>
.chat-item-image {
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
        padding: 0;
        float: left;

        /deep/ .van-image {
            display: block;
            max-width: 240px;
            border: 1px solid @line;
            img {
                // border-radius: 2px;
                display: block;
            }
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
            left: -36px;
        }
        &--container {
            float: right;
        }
    }
}
</style>