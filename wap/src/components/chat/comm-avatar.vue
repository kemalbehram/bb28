<template>
    <van-image :src="src" class="chat-common-avatar" heigth="38" radius="38" v-if="position || wechat" />
    <div class="chat-common-avatar" v-else>
        <span>{{user.nickname | cut_name}}</span>
    </div>
</template>

<script>
export default {
    props: {
        src: '',
        position: {
            default: false,
            type: Boolean,
        },
        wechat: {
            default: false,
            type: Boolean,
        },
    },
    computed: {
        user() {
            return this.$store.state.user.info
        },
    },
}
</script>

<style lang="less" scoped>
.chat-common-avatar {
    vertical-align: bottom;
    width: 38px;
    height: 38px;
    position: absolute;
    left: 0;
    top: 8px;
    line-height: 38px;
    text-align: center;
    background: #ce9f42;
    border-radius: 38px;
    font-size: 18px;
    color: @white;
    &.right {
        right: 0;
        left: auto;
    }
}
</style>