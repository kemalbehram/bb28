<template>
    <div class="chat-item-time">
        <span>
            <slot />
        </span>
    </div>
</template>


<script>
export default {
    props: {
        time: ''
    }
}
</script>

<style lang="less" scoped>
.chat-item-time {
    text-align: center;
    span {
        margin: 0 auto;
        border-radius: 2px;
        line-height: 22px;
        display: inline-block;
        padding: 0 8px;
        font-size: 13px;
        color: @desc;
        background: @bg-light;
    }
}
</style>