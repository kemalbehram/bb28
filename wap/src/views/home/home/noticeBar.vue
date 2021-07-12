<template>
    <div class="bg-white">
        <div class="bar">
            <van-notice-bar :text="notices" color="#2f3242" left-icon="volume-o" replay />
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            leftIcon: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/left_icon.png',
            items: [],
        }
    },
    created() {
        this.getIndex()
    },
    methods: {
        getIndex() {
            this.$get('/article/1001/index', {}, false).then((res) => {
                if (res.code === 200) {
                    this.items = res.data.items
                }
            })
        },
    },
    computed: {
        notices() {
            let notice_str = ''
            this.items.forEach((element) => {
                notice_str += element.title + '——————'
            })
            // console.log(notice_str)
            return notice_str
        },
    },
}
</script>
<style lang='less' scoped>
.bg-white {
    background: white;
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
