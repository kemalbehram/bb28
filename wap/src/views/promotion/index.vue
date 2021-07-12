<template>
    <div class="wrap pb-64">
        <userHeader :fixed="true" title="活动中心" />
        <!-- <van-image :src="promotion_bg" class="avatar" fit="cover" /> -->
        <div class="holder"></div>
        <!-- <van-collapse v-model="activeNames">
            <van-collapse-item :key="index" :name="index" :title="item.title" v-for="(item,index) in items">
                <div style="width:100%;" v-html="item.content"></div>
            </van-collapse-item>
        </van-collapse>-->
        <div @click="blockDetail(item)" class="container" v-for="(item,index) in items">
            <div class="container-content">
                <van-image :src="item.thumb_wap" class="container-content--image" />
            </div>
            <div class="container-text">
                <span>{{item.title}}</span>
                <span class="container-text--effective">{{item.effective_date}}</span>
            </div>
        </div>

        <van-popup :style="{ width: '100%' ,height: '100%'}" @closed="preventBackUnset" @opened="onOpened" position="right" v-model="$store.state.config.show_pop">
            <detail :article="article" />
        </van-popup>

        <lettTabbar />
    </div>
</template>


<script>
import preventBack from '@/libs/prevent-back'
import userHeader from '@/components/user-header'
import lettTabbar from '_c/lett-tabbar'
import detail from './detail'
export default {
    mixins: [preventBack],
    components: {
        lettTabbar,
        userHeader,
        detail,
    },

    data() {
        return {
            items: [],
            loading: false,
            promotion_icon: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/promotion_icon.png',
            promotion_bg: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/promotion_bg.jpg',
            activeNames: [],
            show: false,
            article: {},
        }
    },

    created() {
        this.getIndex()
    },

    // watch: {
    //     '$store.state.config.show_pop'(val) {
    //         if (val == true) {
    //             window.history.pushState(null, null, '#')
    //         }
    //     },
    // },
    // activated() {
    //     window.addEventListener(
    //         'popstate',
    //         (e) => {
    //             if (this.$store.state.config.show_pop == true) {
    //                 this.$store.state.config.show_pop = false
    //             } else {
    //                 this.$router.push({ path: '/user' })
    //             }
    //         },
    //         false
    //     )
    // },
    // deactivated() {
    //     window.addEventListener(
    //         'popstate',
    //         (e) => {
    //             if (this.$store.state.config.show_pop == true) {
    //                 this.$store.state.config.show_pop = false
    //             } else {
    //                 this.$router.push({ path: '/user' })
    //             }
    //         },
    //         false
    //     )
    // },

    methods: {
        getIndex() {
            let local = localStorage.getItem('article_1000')
            if (local) {
                this.items = JSON.parse(local)
            } else {
                this.loading = true
            }

            this.$get('/article/1000/index').then((res) => {
                if (res.code !== 200) return (this.loading = res.message)
                this.loading = false
                this.items = res.data.items
                localStorage.setItem('article_1000', JSON.stringify(res.data.items))
            })
        },
        blockDetail(item) {
            this.article = item
            this.$store.state.config.show_pop = true
        },
        onOpened() {
            let that = this
            this.preventBackSet(function () {
                that.$store.state.config.show_pop = false
            })
        },
    },
}
</script>

<style lang="less" scoped>
.container {
    margin: 20px;
    border-radius: 10px;
    background: @white;
    &-content {
        padding: 10px;
        padding-bottom: 5px;
    }
    &-text {
        line-height: 24px;
        padding-left: 10px;
        padding-bottom: 10px;
        span {
            color: #2f3242;
            display: table-row-group;
        }
        &--effective {
            font-size: 13px;
            color: #575c73 !important;
        }
    }
}
.holder {
    margin-top: 8vh;
}
// .item {
//     background: white;
//     margin-bottom: 10px;
//     .item-title {
//         /deep/.van-image {
//             display: inline-block;
//             margin: 4% 0 0 4%;
//             vertical-align: text-bottom;
//         }
//         .title {
//             display: inline-block;
//             margin-left: 1%;
//         }
//     }
//     .item-content {
//         width: 80%;
//         margin: 0 auto;
//         padding-bottom: 1px;
//     }
// }
// .chat-img {
//     display: block;
//     width: 100%;
//     margin: 0 auto;
//     padding-bottom: 4px;
// }
</style>