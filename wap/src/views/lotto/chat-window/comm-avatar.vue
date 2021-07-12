<template>
    <van-image :src="src" class="chat-common-avatar" heigth="38" radius="38" v-if="font" />
    <div :class="content.user_id | sub_last" class="chat-common-avatar" v-else>
        <span>{{content.nickname | cut_name}}</span>
    </div>
</template>

<script>
export default {
    props: {
        src: '',
        font: false,
        content: {
            type: Object,
            default: {},
        },
    },
}
</script>

<style lang="less" scoped>
.chat-common-avatar {
    vertical-align: bottom;
    width: 30px;
    height: 30px;
    position: absolute;
    left: 0;
    top: 8px;
    border-radius: 30px;
    line-height: 30px;
    color: @white;
    text-align: center;
    &.right {
        right: 10px;
        left: auto;
    }
}
.color-0 {
    background: #fb6262;
}
.color-1 {
    background: rgb(149, 149, 221);
}
.color-2 {
    background: rgb(199, 199, 97);
}
.color-3 {
    background: rgb(63, 172, 63);
}
.color-4 {
    background: rgb(209, 177, 116);
}
.color-5 {
    background: rgb(201, 136, 147);
}
.color-6 {
    background: rgb(128, 100, 100);
}
.color-7 {
    background: rgb(166, 226, 76);
}
.color-8 {
    background: #8f7957;
}
.color-9 {
    background: #4b9f9f;
}
</style>