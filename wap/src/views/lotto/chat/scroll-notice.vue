<template>
    <div class="scroll-notice van-hairline--top van-hairline--bottom">
        <!-- <van-notice-bar :text="notice.title" color="#FFFFFF" scrollable v-if="notice.id" /> -->
        <van-notice-bar :speed="50" :text="notice.title" background="#ffffff" color="#101010" scrollable />
    </div>
</template>

<script>
export default {
    computed: {
        notice() {
            return this.$store.state.config.notice.game
        },
    },
}
</script>

<style lang="less" scoped>
.scroll-notice {
    position: fixed;
    width: 100%;
    z-index: 21;
    top: 105px;
    padding: 2px 0;
    background: white;
    width: 100vw;
    box-shadow: 0px 2px 2px 0px #d3d3d3;
    // border-bottom: 1px solid #ddd;
    // border-top: 1px solid #ddd;
}
// /deep/.van-notice-bar__wrap {
//     line-height: 50px;
// }
/deep/.van-notice-bar {
    height: 20px;
}
</style>