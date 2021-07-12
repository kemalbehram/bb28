<template>
    <div class="wrap pb-64">
        <div class="header">
            <!-- <header-icon /> -->
            <span>最新游戏</span>
        </div>

        <van-cell :label="item.title" icon="volume" is-link title="公告通知" to="/notice-list" />
        <van-cell icon="service" is-link label="提供各种问题疑难排解" title="在线客服" to="/service" />

        <lett-Tabbar />
    </div>
</template>
<script>
import lettTabbar from '_c/lett-tabbar'
import headerIcon from '_c/header-icon'
export default {
    components: {
        lettTabbar,
        headerIcon,
    },
    data() {
        return {
            item: {},
        }
    },
    created() {
        this.getIndex()
    },
    methods: {
        getIndex() {
            this.$get('/article/1001/new', {}, false).then((res) => {
                if (res.code === 200) {
                    this.item = res.data.item
                }
            })
        },
    },
}
</script>
<style lang="less" scoped>
.header {
    height: 44px;
    // background: linear-gradient(90deg, #4f8dfe, #34bcfe);
    background: #ff6a00;
    position: relative;
    span {
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 16px;
        color: white;
        transform: translate(-50%, -50%);
    }
}
</style>