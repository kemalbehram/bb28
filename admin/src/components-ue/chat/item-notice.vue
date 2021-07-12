<template>
    <div class="chat-item-notice">
        <div class="chat-item-notice__content bg-texture">
            <slot />
        </div>
    </div>
</template>


<style lang="less" scoped>
.chat-item-notice {
    position: relative;
    text-align: center;
    padding: 8px 0px;
    &__content {
        background-color: #24936e;
        color: #fff;
        line-height: 20px;
        font-size: 12px;
        border-radius: 2px;
        padding: 6px 12px;
        text-align: center;
        display: inline-block;
        margin: 0 auto;
    }
}
</style>