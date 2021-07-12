<template>
    <div class="chat-item-notice">
        <div class="content">
            <slot />
        </div>
    </div>
</template>


<style lang="less" scoped>
.chat-item-notice {
    position: relative;
    text-align: center;
    padding: 8px 0px;
    .content {
        background: @bg-shading;
        color: @sub;
        line-height: 20px;
        font-size: 13px;
        border-radius: 2px;
        padding: 6px 12px;
        text-align: center;
        display: inline-block;
        margin: 0 auto;
    }
}
</style>