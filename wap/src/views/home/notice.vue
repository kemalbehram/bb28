<template>
    <van-dialog :show-confirm-button="false" v-model="$parent.show">
        <div class="notice-box">
            <div class="pd-border" />
            <van-swipe :loop="true" :show-indicators="false" ref="swiper" touchable>
                <van-swipe-item :key="index" v-for="(item,index) in content">
                    <div style="padding-right:10px" v-html="item.content"></div>
                </van-swipe-item>
            </van-swipe>
            <div class="prev-next">
                <div @click="goPrev">上一条</div>
                <div @click="goNext">下一条</div>
            </div>
            <van-button @click="close" class="confirm" color="#FEBD40" round>
                <span class="color-zong">关闭</span>
            </van-button>
        </div>
    </van-dialog>
</template>

<script>
export default {
    props: {
        content: {
            type: Array,
            default: '',
        },
    },

    methods: {
        close() {
            this.$emit('close')
        },
        goPrev() {
            // console.log(this.$refs.swiper)
            this.$refs.swiper.prev()
        },
        goNext() {
            this.$refs.swiper.next()
        },
    },
}
</script>

<style lang="less" scoped>
.notice-box {
    height: 100%;
    width: 100%;
    border-radius: 10px;
    background-image: url('../../assets/images/notice-bg.png');
    background-size: 100% 100%;
    position: absolute;
    background-repeat-y: no-repeat;
}
.pd-border {
    padding: 2vh 6vw;
}
.color-zong {
    color: #99590d;
    font-size: 18px;
    font-weight: 600;
}
.confirm {
    width: 35vw;
    position: absolute;
    bottom: 2vh;
    left: 20vw;
}
/deep/.van-swipe {
    height: 72%;
    width: 90%;
    padding: 0 10px;
    margin: 0 auto;
}
/deep/.van-swipe-item {
    overflow: auto !important;
}
.prev-next {
    display: flex;
    justify-content: space-between;

    div {
        padding: 0px 16px;
    }
}

/deep/.notice,
.van-dialog {
    background-color: transparent !important;
}
</style>