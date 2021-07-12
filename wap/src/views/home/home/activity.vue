<template>
    <div class="activity">
        <div class="head">
            <img alt src="https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/home-star.png" />
            <span>活动中心</span>
        </div>
        <div class="content">
            <div @click="blockDetail(item)" class="item" v-for="(item,index) in items">
                <img alt src="https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/home-zp.png" />
                <div>
                    <span class="font-12">{{item.title}}</span>
                    <br />
                    <span class="font-12">{{item.excerpt}}</span>
                </div>
            </div>
        </div>
        <van-popup :style="{ width: '100%' ,height: '100%'}" position="right" v-model="$store.state.config.show_pop">
            <detail :article="article" />
        </van-popup>
    </div>
</template>
<script>
import detail from '../../promotion/detail'
export default {
    data() {
        return {
            article: {},
            items: [],
        }
    },
    created() {
        this.getIndex()
    },
    components: {
        detail,
    },
    methods: {
        blockDetail(item) {
            this.article = item

            this.$store.state.config.show_pop = true
        },
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
    },
}
</script>
<style lang="less" scoped>
.activity {
    .head {
        background: white;
        height: 50px;
        margin-bottom: 10px;
        box-shadow: 0 6px 13px -7px #0205131a;

        // line-height: 50px;
        img {
            width: 22px;
            height: 22px;
            margin-top: 15px;
            margin-left: 12px;
            margin-right: 12px;
            display: inline-block;
        }
        span {
            vertical-align: super;
        }
    }
    .content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        width: 94%;
        margin: 0 auto;

        // justify-content: center;
        .item {
            width: 48.5%;
            display: flex;
            background: white;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 6px 13px -7px #0205131a;
            position: relative;
            img {
                display: inline-block;
                width: 45px;
                height: 45px;
                padding: 10px;
            }
            div {
                padding-top: 13px;
            }
            /deep/.van-icon {
                position: absolute;
                right: 5%;
                top: 10%;
            }
        }
    }
}
</style>