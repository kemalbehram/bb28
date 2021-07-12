<template>
    <van-tabbar :border="false" :route="true" :z-index="999" active-color="#ff6a00" class="shadow-top">
        <!-- <van-tabbar-item icon="chat" to="/dialogue"> -->
        <!-- <span>会话</span> -->
        <!-- <img :src="props.active ? icons.homeSelect : icons.home" slot="icon" slot-scope="props" /> -->
        <!-- </van-tabbar-item> -->
        <!-- <van-tabbar-item icon="apps-o" to="/collect">常用</van-tabbar-item> -->
        <!-- <van-tabbar-item to="/vote">
            <span>已投</span>
            <img :src="props.active ? icons.voteSelect : icons.vote" slot="icon" slot-scope="props" />
        </van-tabbar-item>-->

        <van-tabbar-item icon="wap-home" replace to="/">
            <span>首页</span>
            <!-- <img :src="props.active ? icons.homeSelect : icons.home" slot="icon" slot-scope="props" /> -->
        </van-tabbar-item>
        <van-tabbar-item icon="card" to="/wallet">
            <span>充提</span>
        </van-tabbar-item>
        <van-tabbar-item icon="friends-o" to="/user/promote">
            <span>代理</span>
        </van-tabbar-item>
        <van-tabbar-item icon="manager" to="/user">
            <span>我的</span>
        </van-tabbar-item>
    </van-tabbar>
</template>

<script>
export default {
    data() {
        return {}
    },
}
</script>
<style lang="less" scoped>
/deep/.van-tabbar {
    padding-bottom: env(safe-area-inset-bottom);
    /deep/.van-icon {
        display: block;
    }
}
</style>