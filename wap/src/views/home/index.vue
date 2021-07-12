<template>
    <div style="height:100vh">
        <van-nav-bar fixed title="游戏大厅"></van-nav-bar>
        <div class="layout">
            <div class="bar">
                <van-notice-bar :text="scrollContent" color="#2f3242" left-icon="volume-o" replay />
            </div>
            <van-cell-group class="notice-group">
                <van-cell :label="item.title" icon="volume" is-link title="公告通知" to="/notice-list" v-show="active == 0">
                    <template #icon>
                        <van-image class="mr-10" fit="contain" height="45px" round src="http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/icon/notice.png" width="45px" />
                    </template>
                </van-cell>
            </van-cell-group>
            <van-cell-group class>
                <lotto-list v-show="active == 0" />
            </van-cell-group>
        </div>
        <notice-item :content="content" @close="close" class="notice" v-if="show && content!=null" />
        <!-- 底部导航 -->
        <lett-tabbar />
    </div>
</template>
<script>
import lettTabbar from '_c/lett-tabbar'
import noticeItem from './notice'
import childSwipe from './home/swiper'
import noticeBar from './home/noticeBar'
import lottery from './home/lottery'
import lottoList from './home/lottoList'
import Lottery from './home/lottery.vue'
import headerIcon from '_c/header-icon'
import config from '@/config'
import activity from './home/activity'

export default {
    name: 'home',

    data() {
        return {
            item: {},
            active: 0,
            show: true,
        }
    },
    created() {
        this.getIndex()
    },
    components: {
        lettTabbar,
        childSwipe,
        noticeBar,
        lottery,
        headerIcon,
        lottoList,
        noticeItem,
        activity,
    },
    computed: {
        download_url() {
            return config.wapDomain + '/app/index.html'
        },
        content() {
            let notice = this.$store.state.config.notice.index
            let notice_array = []
            if (notice) {
                notice_array = notice
            }
            return notice_array
        },
        scrollContent() {
            let notice = this.$store.state.config.notice.scroll

            if (notice.length == 0) return '暂无公告'
            let notice_str = ''
            notice.forEach((element) => {
                notice_str += element.title + '——————'
            })
            return notice_str
        },
    },
    methods: {
        getIndex() {
            this.$get('/article/1001/new', {}, false).then((res) => {
                if (res.code === 200) {
                    this.item = res.data.item
                }
            })
        },
        goService() {
            this.$router.push('/service')
        },
        close() {
            this.show = false
        },
    },
}
</script>
<style lang="less" scoped>
.header {
    height: 44px;
    background: #ff6a00;
    width: 100%;
    text-align: center;
    line-height: 44px;
    span {
        // position: absolute;
        // top: 50%;
        // left: 50%;

        font-size: 16px;
        color: white;
        transform: translate(-50%, -50%);
    }
    .tab {
        width: 50%;
        background: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        height: 80%;
        border-radius: 5px;
        display: flex;
        justify-content: space-around;
        align-items: center;
        span {
            display: inline-block;
            text-align: center;
            font-size: 15px;
            border-radius: 5px;
            width: 45%;
            height: 85%;
            line-height: 30px;
            // line-height: 44px;
            color: #b7b7c7;
        }
        .active {
            color: white;
            background: linear-gradient(180deg, #3bb6ff, #4b96fe);
        }
    }
}
.menu {
    display: flex;
    justify-content: space-around;
    box-shadow: 0 6px 13px -7px #0205131a;
    padding: 8px 0;
    background: white;
    .item {
        text-align: center;
        img {
            width: 45px;
            height: 45px;
        }
        span {
            display: block;
            font-size: 12px;
        }
        .app {
            color: #7963e9;
        }
    }
}
.safe-distance {
    margin-top: 46px;
}
/deep/.van-nav-bar__title,
/deep/.van-nav-bar__text {
    color: @white;
}
.layout {
    position: fixed;
    height: calc(100% - 96px);
    width: 100%;
    overflow-y: scroll;
    top: 46px;
    .notice-group {
        margin-bottom: 15px;
    }
}
.notice {
    position: fixed;
    height: 65vh;
    width: 75vw;
}

.bar {
    width: 94%;

    // background: blue;
    margin: 0 auto;
    border-radius: 5px;

    /deep/.van-notice-bar {
        font-size: 12px;
        height: 30px;
        padding: unset;
        background: #f3f3f6 !important;
    }
    /deep/.van-icon {
        color: #4f8dfe;
        // background: #4f8dfe;
        // color: white;
        // width: 20px;
        // height: 20px;
        // border-radius: 30px;
        // text-align: center;
        padding-left: 10px;
        // margin-right: 6px;
        // font-size: 10px;
        // line-height: 15px;
        // // padding-right: 20px;
    }
}
</style>