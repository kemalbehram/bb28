<template>
    <div class="bg-white">
        <div class="focus-container">
            <van-swipe :autoplay="3000" indicator-color="#4f8dfe">
                <van-swipe-item :key="index" v-for="(image, index) in bannerList">
                    <router-link to="/promotion">
                        <van-image :src="image" class="my-swipe" />
                    </router-link>
                </van-swipe-item>
            </van-swipe>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            bannerList: ['https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/banner-min.jpg', 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/banner-min.jpg'],
        }
    },
}
</script>

<style lang="less" scoped>
.bg-white {
    background: white;
    padding: 10px 0;
}
.focus-container {
    width: 94%;
    margin: 0 auto;

    /deep/.van-swipe {
        border-radius: 5px;
        /deep/.van-image {
            border-radius: 5px;
        }
    }
}
</style>