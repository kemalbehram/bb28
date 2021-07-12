<template>
    <Avatar :src="src" class="chat-common-avatar" shape="square" size="32" heigth="32" radius="2" />
</template>

<script>
export default {
    props: {
        src: ''
    }
}
</script>

<style lang="less" scoped>
.chat-common-avatar {
    vertical-align: bottom;
    width: 32px;
    height: 32px;
    position: absolute;
    left: 0;
    top: 8px;

    &.right {
        right: 0;
        left: auto;
    }
}
</style>